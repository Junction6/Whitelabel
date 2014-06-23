<?php


   function getSessionOrderLabel(){
       return "whitelabel.api.order." . J6_TOKEN;
   }
   function getOrderInSession(){
        return (
                isset($_SESSION[getSessionOrderLabel()]) && is_object($_SESSION[getSessionOrderLabel()])
                    ? $_SESSION[getSessionOrderLabel()] : null
                );
   }
   function saveOrderToSession($oOrder){
       $_SESSION[getSessionOrderLabel()] = $oOrder;
   }
   function clearOrderInSession(){
       unset($_SESSION[getSessionOrderLabel()]);
   }
   function getOrderItemsInSession(){
        $oOrder = getOrderInSession();
        if ($oOrder && property_exists($oOrder,'Items') && $oOrder->Items ){
            return $oOrder->Items;
        }
        return null;
   }
   
   function getSessionProductLabel(){
       return "whitelabel.api.products." . J6_TOKEN;
   }
   function getProductsInSession(){
        return (
                isset($_SESSION[getSessionProductLabel()]) && is_array($_SESSION[getSessionProductLabel()])
                    ? $_SESSION[getSessionProductLabel()] : null
                );
   }
   function saveProductsToSession($oProducts){
       $_SESSION[getSessionProductLabel()] = $oProducts;
   }

