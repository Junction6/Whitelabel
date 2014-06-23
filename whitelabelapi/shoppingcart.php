<?php
    include 'inc/config.php';   include 'inc/utilities.php';

    $oOrder = getOrderInSession();

    $_SESSION['alert-message']='';

    if ($action && $action=="additem"){

        if (isset($_POST['Variation']) && $variations = $_POST['Variation']){
            $productid =    isset($_GET['id']) ? $_GET['id'] : null;
            foreach($variations as $variationId => $variationQuantity){

                $quantity = $variationQuantity['Quantity'];
                if ($quantity){
                    $eventId  = isset($_POST['EventID'][$productid]) ? $_POST['EventID'][$productid] : null;
                    $productPickupLocationID = isset($_POST['ProductPickupLocationID'][$productid]) 
                                               ? $_POST['ProductPickupLocationID'][$productid] : null;
                    $oOrder = ShoppingCart::addItem($oOrder, $variationId, $quantity, $eventId,$productPickupLocationID);
                }
            } 
            //extra products
            if (isset($_POST['ExtraProductsItems']) && $extraProductsItems = $_POST['ExtraProductsItems']){
                foreach($extraProductsItems as $itemId => $itemQuantity){
                   $quantity = $itemQuantity['Quantity'];

                   if ($quantity){
                        $eventId  = isset($_POST['EventID'][$itemId]) ? $_POST['EventID'][$itemId] : null;
                        $productPickupLocationID = isset($_POST['ProductPickupLocationID'][$itemId]) 
                                               ? $_POST['ProductPickupLocationID'][$itemId] : null;
                        $oOrder = ShoppingCart::addItem($oOrder, $itemId, $quantity, $eventId, $productPickupLocationID);
                   }

                }
            }
        }

    }
    elseif ($action && $action == "deleteitem" && $oOrder && property_exists($oOrder,'Items') && $oOrder->Items){

        /* delete items within the order */
        //TODO XSS and SQL attack prevention
        $itemId = isset($_GET['id']) ? $_GET['id'] : '';
        ShoppingCart::deleteItem($oOrder, $itemId);
    }
    elseif ($action && $action == "cancel" && $oOrder){
        /* empty cart -- delete current order */
        $orderId =$oOrder->ExternalOrderID;
        $url = J6_BASE_URL."deleteorder"."?token=".J6_TOKEN."&orderid=$orderId";
        $orderDeleted = cCurl($url);

        if ($orderDeleted){
            $orderDeleted = json_decode($orderDeleted);
            $oOrderDeleted = property_exists($orderDeleted, 'response') ? $orderDeleted->response : null;
            if ($oOrderDeleted){
                //successful
                $_SESSION['alert-message'] = "Your shopping cart emptied.";
                $_SESSION['alert-type'] = 'success';
            }

        }
        clearOrderInSession();
    }

    RedirectTo($redirectBackUrl);
     
class ShoppingCart{
    
    static function addItem($oOrder, $variationId, $quantity=1,$eventId=null,$productPickupLocationID=null){
        if (!$oOrder && !is_object($oOrder)){
            /* create a new order */
            $url =  J6_BASE_URL."neworder"."?token=".J6_TOKEN;
            $order = cCurl($url);
            if ($order){
               $order = json_decode($order);
               $oOrder = property_exists($order, 'response') ? $order->response : null;
            } 
        }
        if ($oOrder){
   
            /* put the selected product and variation in the Order*/
            $orderId = $oOrder->ExternalOrderID;
            $url = sprintf(J6_BASE_URL."orderadditem"."?token=".J6_TOKEN.
                       "&orderid=%s&productvariation=%s&quantity=%s",
                        urlencode($orderId), urlencode($variationId), urlencode($quantity));

            $url .= ($eventId) ?  "&eventid=" . urlencode($eventId) : "";
            $url .= ($productPickupLocationID) ? "&productpickuplocationid=" . urlencode($productPickupLocationID) : "";
            $newItemAdded = cCurl($url);

            if ($newItemAdded){
                $newItemAdded = json_decode($newItemAdded);
                $oNewItemAdded = property_exists($newItemAdded, 'response') ? $newItemAdded->response : null;
                if ($oNewItemAdded){
                /* successfully added item to the order */
                    $products =  getProductsInSession();
                    $order = ShoppingCart::applyMissingProductPpty($oNewItemAdded,$products);
                    if ($item = FindProduct($products, $variationId)){
                        $title = ($item->Variation->InternalItemID) ? 
                                  $item->Variation->InternalItemID . " ({$item->Variation->Price}) " : 'unknown'; 
                        $message = "Item: (". $item->Title . " - " . $title . ") added to Your Order #$orderId.";
                        $_SESSION['alert-message'] .= $message . "<br/>";
                        $_SESSION['alert-type'] = 'success';
                    }
                    saveOrderToSession($order);
                    return $order;
                }
            }
        }  
        return $oOrder;   
    }
    static function deleteItem($oOrder, $itemId){
        
        $orderId = $oOrder->ExternalOrderID;
        $url = sprintf(J6_BASE_URL."orderdeleteitem"."?token=".J6_TOKEN."&orderid=%s&orderItemID=%s",
                $orderId, $itemId);

        $itemDeleted = cCurl($url);

        if ($itemDeleted){
            $itemDeleted = json_decode($itemDeleted);
            $oItemDeleted = property_exists($itemDeleted, 'response') ? $itemDeleted->response : null;
            if ($oItemDeleted){
                //successful
                $item = FindOrderItem($oOrder->Items, $itemId);
                if ($item && is_object($item)){
                    $title = ($item->Title) ? $item->Title : 'unknown'; 
                    $message = "Item: (". $item->Product->Title . " - " . $title . ") deleted from Your Order #$orderId";
                    $_SESSION['alert-message'] = $message;
                    $_SESSION['alert-type'] = 'success';
                }
                $order = ShoppingCart::applyMissingProductPpty($oItemDeleted, getProductsInSession());

                saveOrderToSession($order);     
            }
        }
    }
    static function applyMissingProductPpty($order, $products){ 
        /* apply missing product's properties on the items */
        if ($products){
            $items = $order->Items;
            foreach ($items as $item ){
                if (is_object($item)){
                    if ($prod = FindProduct($products, $item->ProductVariationID)){
                        $item->Product = $prod;
                    }
                $item->SubTitle = "";
                }
            }
        }
        $order->SalesTax = 'N/A';
        return $order;
    }
}