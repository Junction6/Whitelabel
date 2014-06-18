<?php
    
    $oOrder =    isset($_SESSION["whitelabel.api.order"]) && is_object($_SESSION["whitelabel.api.order"])
                    ? $_SESSION["whitelabel.api.order"] : null;
    
    if ($oOrder && property_exists($oOrder,'Items') && $oOrder->Items ) :?>
    <h3>My Order - <?php echo $oOrder->Status ?></h3>
    <?php $items = $oOrder->Items; $subTotalPrice =0; $totalPrice=0; ?>
    <table class="table table-condensed table-striped">
        <tbody>
            <?php foreach( $items as $item) :?> 
            <?php if (is_object($item)) :?>
            
            <tr class="product_orderitem orderitem orderattribute">
                <td class="title"><?php $title = ($item->Title) ? $item->Title : 'unknown'; 
                echo $item->Product->Title . " - " . $title; ?> </td>
                <td class="price">
                    <?php $subTotalPrice += $item->Quantity * $item->UnitPrice;
                          $totalPrice = $subTotalPrice;
                        echo sprintf("%s @ <strong>%s</strong>",
                                    $item->Quantity,number_format($item->UnitPrice,2)); 
                    ?>
                </td>
            </tr>
            <?php endif;?>
            <?php endforeach; ?>
            <tr>
                <td class="subtotal">Subtotal:</td>
                <td><strong id="Cart_Order_SubTotal"><?php echo number_format($subTotalPrice, 2); ?></strong></td>
            </tr>
            <tr>
            <td class="total">Total:</td>
            <td><strong id="Cart_Order_Total"><?php echo number_format($totalPrice, 2); ?></strong></td>
            </tr>
        </tbody>
    </table>
    <div class="buyProducts">
        <p>
            <a href="shoppingcart.php?action=cancel" class="btn btn-small">
                Empty cart
            </a>&nbsp;
             <a href="checkout.php?BackURL=index.php" class="btn btn-primary btn-small">
                 <i class="icon-shopping-cart icon-white"></i> Go to checkout
             </a>
        </p>
    </div>
<?php else :?>
    <h3>My Order</h3>
    <p class="noItems">There are no items in your cart.</p>
<?php endif; ?>