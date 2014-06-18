<?php
        include 'inc/config.php';   include 'inc/utilities.php';
        
        $oOrder =    isset($_SESSION["whitelabel.api.order"]) && is_object($_SESSION["whitelabel.api.order"])
                    ? $_SESSION["whitelabel.api.order"] : null;
        
        $_SESSION['alert-message']='';
        if ($action && $action=="additem"){
         
            //Any Product variation i.e. the provided prices IDs
            if (isset($_POST['Variation']) && $variations = $_POST['Variation']){
                $productid =    isset($_GET['id']) ? $_GET['id'] : null;
                foreach($variations as $variationId => $variationQuantity){
                    
                    $quantity = $variationQuantity['Quantity'];
                    if ($quantity){
                        $eventId  = isset($_POST['EventID'][$productid]) ? $_POST['EventID'][$productid] : null;
                      
                        $oOrder = ShoppingCart::addItem($oOrder, $variationId, $quantity, $eventId);
                    }
                } 
                //extra products
                if (isset($_POST['ExtraProductsItems']) && $extraProductsItems = $_POST['ExtraProductsItems']){
                    foreach($extraProductsItems as $itemId => $itemQuantity){
                       $quantity = $itemQuantity['Quantity'];
                       
                       if ($quantity){
                           $eventId  = isset($_POST['EventID'][$itemId]) ? $_POST['EventID'][$itemId] : null;
                           $oOrder = ShoppingCart::addItem($oOrder, $productid, $itemId, $quantity);
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
                    unset($_SESSION["whitelabel.api.order"]);
                    $_SESSION['alert-message'] = "Your shopping cart emptied.";
                    $_SESSION['alert-type'] = 'success';
                }
               
            }
        }

       
        $host  = $_SERVER['HTTP_HOST'];
        $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $redirectBackUrl = ($redirectBackUrl) ? "/$redirectBackUrl" : "";
        header("Location: http://$host$uri$redirectBackUrl");


class ShoppingCart{
    
    static function addItem($oOrder, $variationId, $quantity=1,$eventId=null){
        if ($oOrder == null){
                
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
                
                if ($eventId){  $url .= "&eventid=" . urlencode($eventId); }
                $newItemAdded = cCurl($url);

                
                if ($newItemAdded){
                    $newItemAdded = json_decode($newItemAdded);
                    $oNewItemAdded = property_exists($newItemAdded, 'response') ? $newItemAdded->response : null;
                    if ($oNewItemAdded){
                    /* successfully added item to the order */
                        $products = $_SESSION["whitelabel.api.products"];
                        $order = ShoppingCart::applyMissingProductPpty($oNewItemAdded, $products);
                        if ($item = FindProduct($products, $variationId)){
                            $title = ($item->Variation->InternalItemID) ? 
                                      $item->Variation->InternalItemID : 'unknown'; 
                            $message = "Item: (". $item->Title . " - " . $title . ") added to Your Order #$orderId.";
                            $_SESSION['alert-message'] .= "<br/>".$message;
                            $_SESSION['alert-type'] = 'success';
                        }
                        $_SESSION["whitelabel.api.order"] = $order;
                    return $order;
                }
            }
        }  
        return $oOrder;   
    }
    static function deleteItem($oOrder, $itemId){
        
            $orderId =$oOrder->ExternalOrderID;
            $url = sprintf(J6_BASE_URL."orderdeleteitem"."?token=".J6_TOKEN."&orderid=%s&orderItemID=%s",
                    $orderId, $itemId);
            
            $itemDeleted = cCurl($url);

            if ($itemDeleted){
                $itemDeleted = json_decode($itemDeleted);
                $oItemDeleted = property_exists($itemDeleted, 'response') ? $itemDeleted->response : null;
                if ($oItemDeleted){
                    //successful
                    $item = FindOrderItem($oOrder->Items, $itemId);
                    
                    $title = ($item->Title) ? $item->Title : 'unknown'; 
                    $message = "Item: (". $item->Product->Title . " - " . $title . ") deleted from Your Order #$orderId";
                    $_SESSION['alert-message'] = $message;
                    $_SESSION['alert-type'] = 'success';

                    $products = $_SESSION["whitelabel.api.products"];
                    $order = ShoppingCart::applyMissingProductPpty($oItemDeleted, $products);
                    
                    $_SESSION["whitelabel.api.order"] = $order;
                        
                }
            }
    }
    static function applyMissingProductPpty($order, $products){ 
        /* apply missing product's properties on the items */
      
        $items = $order->Items;
        foreach ($items as $item ){
            if (is_object($item)){
                    if ($prod = FindProduct($products, $item->ProductVariationID)){
                       $item->Product = $prod;
                    }
            $item->SubTitle = "";}
        }
        $order->SalesTax = 'N/A';
        return $order;
    }
    
    
}