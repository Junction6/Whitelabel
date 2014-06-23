<?php include 'inc/config.php';
      include 'inc/utilities.php'; 
        $productSelected = null;
        if ($id = $_GET['id']){
            $url =  J6_BASE_URL."products?token=".J6_TOKEN."&ProductID=$id";
            $products = cCurl($url);
            $oProducts = null;
            if ($products){
                $products = json_decode($products);
                $oProducts = property_exists($products, 'response') ? $products->response : null;
                if ($oProducts && !is_array($oProducts)){ $oProducts = array(0=>$oProducts);}
            }
            if ($oProducts){$productSelected = array_pop($oProducts);}
        }

        if ($productSelected && $productSelected->ShowOnWhitelabel ) :?>
<!DOCTYPE html>
<html>
    <head>
        <meta content="text/html; charset=UTF-8" http-equiv="Content-type">
        <title><?php echo $productSelected->Title; ?> » Junction6</title>
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
        <script type="text/javascript" src="js/select2.js"></script>
        <script type="text/javascript" src="js/utility.js"></script>
        <script type ='text/javascript' src="js/BootstrapQuantityField.js"></script>
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
                <h1> <?php echo $productSelected->Title; ?></h1>
	        <form class="form-horizontal" id="Form_BuyForm" action="shoppingcart.php?action=additem&id=<?php echo $productSelected->ID; ?>" method="post" enctype="application/x-www-form-urlencoded">
                    <fieldset>
                        <div>
                            <?php
                            if ($productSelected->Content){
                                $content = htmlspecialchars_decode($productSelected->Content);
                                echo "{$content}";
                            }
                            if ($productSelected->VoucherContent){
                                $voucherContent = htmlspecialchars_decode($productSelected->VoucherContent);
                                echo "<h2>Voucher Content</h2>{$voucherContent}";
                            }

                            ?>
                            <br/>
                        </div>
                        <table class="table">
                             <thead>
                                 <tr>
                                     <th>Title</th>
                                     <th>Price</th>
                                     <th>Quantity</th>
                                 </tr>
                             </thead>
                             <tbody>
                                <?php if ($productSelected->Variations && count($productSelected->Variations) > 0) :?>
                                    <?php  usort($productSelected->Variations, "cmp_obj"); ?>
                                    <?php $vQuantity = 1;
                                        foreach ($productSelected->Variations as $variation) :?>
                                        <tr>
                                            <td><?php echo $variation->InternalItemID; ?></td>
                                            <td><?php  $price = number_format($variation->Price,2);
                                               echo $price; ?></td>
                                            <td>
                                                <div class="input-prepend input-append input-group">
                                                    <button class="btn btn-primary input-group-addon">-</button>
                                                    <input class="text input-quantity js-quantity" 
                                                           name="<?php echo "Variation[{$variation->ID}][Quantity]"; ?>" 
                                                           value="<?php echo $vQuantity; $vQuantity=0?>" type="text">
                                                    <button class="btn btn-primary input-group-addon">+</button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif ?>
                             </tbody>
                         </table>

                        <!-- Calendar to display the available events -->
                        <?php include 'inc/productevents.php'; ?>
                        
                        <!-- the ProductPickupLocatioin if any for the Product -->
                        <?php include 'inc/productpickuplocation.php';?>
                        <!-- any Products within a Package -->
                        <?php include 'inc/productswithinapackage.php';?>
                        <!-- any Addons within a Package -->
                        <?php include 'inc/addonproducts.php'; ?>
                          <!-- any Extra Products -->
                        <?php include 'inc/extraproducts.php'; ?>
                    </fieldset>
                    <div class="Actions form-actions">
                        <a class="btn btn-default" href="index.php">Go Back</a>
                        <button class="btn action  btn btn-primary" type="submit" 
                                name="Buy" value="Add to cart">
                            Add to cart
                        </button>
                    </div>
                </form>

			
		
	</div>
</div>
                
                
            </div>
        </div>
    </body>
</html>
<?php else :?>
 <?php RedirectTo('index.php');
?>
<?php endif;
