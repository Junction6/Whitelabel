<?php
       include 'inc/config.php';
       include 'inc/utilities.php';
      
        $oOrder =    isset($_SESSION["whitelabel.api.order"]) && is_object($_SESSION["whitelabel.api.order"])
                    ? $_SESSION["whitelabel.api.order"] : null;
       
        if ($oOrder && sizeof($_POST) > 0){
            
            
            $orderid = $oOrder->ExternalOrderID;
            
            //TODO, XSS and SQL attack prevention
            $firstname = isset($_POST['Firstname']) ? $_POST['Firstname'] : '';
            $surname   = isset($_POST['Surname']) ? $_POST['Surname'] : '';
            $email     = isset($_POST['Email']) ? $_POST['Email'] : '';
            $country   = isset($_POST['Country']) ? $_POST['Country'] : '';
           
            //create the customer to the current order
            $url = sprintf(J6_BASE_URL."newcustomer"."?token=".J6_TOKEN.
                            "&firstname=%s&surname=%s&email=%s&country=%s", 
                            urlencode($firstname), urlencode($surname), urlencode($email), urlencode($country));
            $newCustomer = cCurl($url);
            
            if ($newCustomer){
                $newCustomer = json_decode($newCustomer);
                $oNewCustomer = property_exists($newCustomer, 'response') ? $newCustomer->response : null;
                if ($oNewCustomer){
                    
                    $customerId = $oNewCustomer->ID;
                    
                    //add customer to the order
                    
                    if ($customerId){
                        
                        $url = sprintf(J6_BASE_URL."orderaddcustomer"."?token=".J6_TOKEN.
                                "&orderid=%s&customerid=%s", urlencode($orderid), urlencode($customerId));

                        $customerAddToOrder = cCurl($url);
                        
                        if ($customerAddToOrder){
                            $customerAddToOrder = json_decode($customerAddToOrder);
                            $oCustomerAddToOrder = property_exists($customerAddToOrder, 'response') 
                                                    ? $customerAddToOrder->response : null;
                            if ($oCustomerAddToOrder){
                                
                                $url = sprintf(J6_BASE_URL."completeorder"."?token=".J6_TOKEN.
                                    "&orderid=%s", urlencode($orderid));
                                $completeOrder = cCurl($url);

                                if ($completeOrder){
                                    $completeOrder = json_decode($completeOrder);
                                    $oCompleteOrder = property_exists($completeOrder, 'response') ? $completeOrder->response : null;
                                    if ($oCompleteOrder){
                                    /* successfully completed an order
                                     */
                                        unset($_SESSION["whitelabel.api.order"]);
                                        $_SESSION['alert-message'] = "The Order was successfully checked out.";
                                        $_SESSION['alert-type'] = 'success';
                                        $redirectBackUrl = "";
                                    }
                                }
                            }
                        }
                    }
                }
            } 
        }
        $host  = $_SERVER['HTTP_HOST'];
        $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $redirectBackUrl = ($redirectBackUrl) ? "/$redirectBackUrl" : "";
        header("Location: http://$host$uri$redirectBackUrl");
