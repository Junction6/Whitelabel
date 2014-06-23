<?php include 'inc/config.php';
      include 'inc/utilities.php'; 
        ?>
<!DOCTYPE html>

<html>
    <head>
        <meta content="text/html; charset=UTF-8" http-equiv="Content-type">
        <title>All Products » Junction6</title>
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
                              <?php include 'inc/getorders.php'; ?>
                        </div>
                    </div>
                </div>
                <div id="main" class="span6">
                    <div class="well">
                        <h1>All Products</h1>
                        <form class="form-horizontal" enctype="application/x-www-form-urlencoded" method="post" action="products/all/ResultsForm?">
                            <?php
                                    getHtmlAlertMessage();
                                    $url =  J6_BASE_URL."products?token=".J6_TOKEN."&limit=200";//&ProductID=1895";
                                    $products = cCurl($url);
                                 
                                    $oProducts = null;
                                    if ($products){
                                        $products = json_decode($products);
                                        $oProducts = property_exists($products, 'response') ? $products->response : null;
                                        if ($oProducts && !is_array($oProducts)){ $oProducts = array(0=>$oProducts);}
                                
                                        }
                            ?>
                            <?php if ($oProducts) :?>
                            <fieldset>
                                <div class="ExtendedTableListField TableListField FormField RequestHandler field">
                                    <table class="data table table-striped table-condensed">
                                        <thead>
                                            <tr class="">
                                                <th class="Title"><span class="sortTitle">Title</span></th>
                                                <th class="PriceRange">Price Range</th>
                                                <th class="AddToCart"><span>Add to Cart</span></th>
                                            </tr>
                                        </thead>
                                        <tbody >
                                            <?php foreach ($oProducts as $oProduct) :?>
                                                <?php if ($oProduct->ShowOnWhitelabel) :?>
                                                        <!-- presence of the Products means a Package -->
                                                        <?php if (property_exists($oProduct, 'Products') && 
                                                                is_array($oProduct->Products) && count($oProduct->Products) > 0) :?>
                                                               <?php $showVariationButtons = false; ?>
                                                        <?php endif;?>
                                                        <!-- presence of ExtraProducts means Related Product available -->
                                                        <?php if (property_exists($oProduct, 'ExtraProducts') && 
                                                                is_array($oProduct->ExtraProducts) && count($oProduct->ExtraProducts) > 0) :?>
                                                            <?php $showVariationButtons = false; ?>
                                                        <?php endif ?>
                                                        <!-- presence of Addons -->
                                                        <?php if (property_exists($oProduct, 'Addons') && 
                                                                is_array($oProduct->Addons) && count($oProduct->Addons) > 0) :?>
                                                                <?php $showVariationButtons = false; ?>
                                                        <?php endif ?>
                                                    <tr>
                                                        
                                                        <td class='field-Title first Title'>
                                                            <a href='buy.php?id=<?php echo $oProduct->ID ?>'>
                                                                <?php 
                                                                        echo $oProduct->Title;
                                                                ?>
                                                            </a>
                                                            
                                                        </td>
                                                        <?php 
                                                            
                                                                 usort($oProduct->Variations, "cmp_obj");
                                                                 $price = number_format( ($oProduct->Variations) ? $oProduct->Variations[0]->Price 
                                                                                                    : $oProduct->Price,
                                                                                        2);
                                                                $priceTitle = sprintf("From %s", $price);
                                                                echo "<td class='field-PriceRange PriceRange'>{$priceTitle}</td>".        
                                                                     "<td class='field-AddToCart last AddToCart'>
                                                                        <a class='btn nobr' href='buy.php?id={$oProduct->ID}'>
                                                                            Add To Cart
                                                                        </a>
                                                                      </td>" ;
                                                                     ?>
                                                    </tr>
                                                <?php endif;?>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>    
                            </fieldset>
                            <?php //place products in the session
                                saveProductsToSession($oProducts);
                            ?>
                            <?php else :?>
                                <!-- unable to retrieve products -->
                                <?php var_dump($products) ?>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
