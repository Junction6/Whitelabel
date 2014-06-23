<?php   include 'inc/config.php';
        include 'inc/utilities.php'; ?>
<!DOCTYPE html>
<html>
    <head>
        <meta content="text/html; charset=UTF-8" http-equiv="Content-type">
        <?php
                 
                $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
                $oProduct = null;
                
                if ($id){
                    $url = sprintf(J6_BASE_URL."products"."?token=".J6_TOKEN.
                                   "&ProductID=%s", urlencode($id));
                    $product = cCurl($url);
                    
                    if ($product){
                        $product = json_decode($product);
                        $oProduct = property_exists($product, 'response') ? $product->response : null;
                    }
                }
        ?>
        <?php if ($oProduct) :?>
            <title><?php echo $oProduct->Title; ?> » Junction6</title>
        <?php endif; ?>
        <link href="css/select2.css" type="text/css" rel="stylesheet">
        <link href="css/datepicker.css" type="text/css" rel="stylesheet">
        <link href="css/bootstrap.css" type="text/css" rel="stylesheet">
        <link href="css/bootstrap-responsive.css" type="text/css" rel="stylesheet">
        <link href="css/twitter-bootstrap-adjustments.css" type="text/css" rel="stylesheet">
        <link href="css/form.css" type="text/css" rel="stylesheet">
        <link href="css/layout.css" type="text/css" rel="stylesheet">
        <link href="css/webinterface.css" type="text/css" rel="stylesheet">
        <script type="text/javascript" src="js/jquery-1.7.js"></script>
        <script type="text/javascript" src="js/bootstrap.js"></script>
    </head>
    <body>
        <div class="container-fluid">
            <div class="row-fluid">
                <div id="aside"  class="span3">
                    <div class="well">
                        <div id="ShoppingCart">
                              <?php 
                                   include 'inc/getorders.php' ;
                              ?>
                        </div>
                    </div>
                </div>
                <div id="main" class="span6">
                    <div class="well">
                      
                        <?php if ($oProduct) :?>
                        <h1><?php echo $oProduct->Title ?></h1>
                        <?php if ($oProduct->Variations && count($oProduct->Variations) > 0) :?>
                            <h3>Pricing</h3>
                            <table class="table table-striped table-bordered" id="prices">
                                <tbody>
                                    <tr><th>Variation</th><th>Price</th></tr>
                                    <?php  usort($oProduct->Variations, "cmp_obj"); ?>
                                    <?php foreach ($oProduct->Variations as $variation) :?>
                                        <?php echo "<tr><td>
                                                    {$variation->InternalItemID}
                                                </td>
                                                <td>
                                                    $variation->Price
                                                </td></tr>";
                                        ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                            <!-- calendar view in case of any -->
                            <!--- end of calendar display -->
                            <?php if ($oProduct->Content) :?>
                                <div>
                                    <h2>Product Details</h2>
                                    <?php  echo htmlspecialchars_decode($oProduct->Content) ?>
                                </div>
                            <?php endif; ?>
                            <?php if ($oProduct->VoucherContent) :?>
                                <div>
                                    <h2>Voucher Content</h2>
                                    <?php htmlspecialchars_decode($oProduct->VoucherContent) ?>
                                </div>
                            <?php endif; ?>
                        <?php endif;?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
