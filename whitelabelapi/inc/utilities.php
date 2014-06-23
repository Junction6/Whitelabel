<?php
    /* sort array objects based on 'Price' property on the object */
    function cmp_obj($a, $b)
    {
        if ($a && $b){
            if ($a->Price == $b->Price) {
                return 0;
            }
            return ($a->Price < $b->Price) ? +1 : -1;
        }
        return null;
    }
    function cCurl($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        return curl_exec ($ch);                   
    }
    function getHtmlAlertMessage(){
        if (isset($_SESSION['alert-message']) 
                 && $message = $_SESSION['alert-message']){
                 
                $alertType = isset($_SESSION['alert-type']) ? $_SESSION['alert-type'] : 'warning';
                echo "<div class='alert alert-{$alertType} alert-dismissable'>
                        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                        {$message}
                     </div>";
                unset($_SESSION['alert-message']);
        }
    }
    function FindProduct($products,$variationId){
       $oProd = null;
        array_walk($products, function($product) use($variationId, &$oProd){
           if ($product && $variations = $product->Variations){
               foreach($variations as $variation){
                   if ($variation->ID == $variationId){
                        $oProd = $product;
                        $oProd->Variation = $variation;
                        return;
                   }
               }
               if (property_exists($product, 'ExtraProducts')){
                   $extraProducts = $product->ExtraProducts;
                   foreach ($extraProducts as $extraProduct){
                       if ($item = $extraProduct->Item){
                           if ($item->ID == $variationId){
                               $oProd = $product;
                               $oProd->Variation = $item;
                               return;
                           }
                       }
                   }
               }
               
               
           }
        });
        return $oProd;
   }
   function FindOrderItem($items,$id){
       $oItem = null;
       array_walk($items, function($item) use($id, &$oItem){
            if ($item && is_object($item) && $item->OrderItemID == $id){
                $oItem = $item; 
                return;
            }
        });
        return $oItem;
   }
   
   function RedirectTo($url = null){
        $host  = $_SERVER['HTTP_HOST'];
        $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $url = ($url) ? "/$url" : "";
        header("Location: http://$host$uri$url");
   }
   