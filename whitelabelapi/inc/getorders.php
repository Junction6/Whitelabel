<?php
    
    $oOrder = getOrderInSession();
    
    if ($items = getOrderItemsInSession()) :?>
    <h3>My Order - <?php echo $oOrder->Status ?></h3>
    <?php $subTotalPrice =0; $totalPrice=0; ?>
    <table class="table table-condensed table-striped">
        <tbody>
            <?php foreach( $items as $item) :?> 
            <?php if (is_object($item)) :?>
            
            <tr class="product_orderitem orderitem orderattribute">
                <td class="title">
                    <?php 
                        $title = ($item->Title) ? $item->Title : 'unknown'; 
                        $productTitle = property_exists($item, 'Product') ?  $item->Product->Title . " - " . $title : $title;
                        echo $productTitle; 
                    ?> 
                </td>
                <td class="price">
                    <?php $subTotalPrice += $item->Quantity * $item->UnitPrice;
                          $totalPrice = $subTotalPrice;
                        echo sprintf("%s @ <strong>%s</strong>",
                                    $item->Quantity,number_format($item->UnitPrice,2)); 
                    ?>
                </td>
                <td>
                    <a class="hover-expand btn ajaxQuantityLink" 
                       href="shoppingcart.php?action=deleteitem&id=<?php echo property_exists($item, 'Product') ? 
                                      "{$item->OrderItemID}&productid={$item->Product->ID}" 
                                      : "{$item->OrderItemID}";?>" 
                        data-tooltip="" title="<?php echo $productTitle; ?>">
                        <i class="icon-remove icon-white"></i>
                        <span class="expand btn btn-danger">
                                <i class="icon-remove icon-white"></i>
                                <span> Delete</span>
                        </span>
                    </a>
                </td>
            </tr>
            <?php endif;?>
            <?php endforeach; ?>
            <tr>
                <td class="subtotal">Subtotal:</td>
                <td><strong id="Cart_Order_SubTotal"><?php echo number_format($subTotalPrice, 2); ?></strong></td>
                <td></td>
            </tr>
            <tr>
            <td class="total" >Total:</td>
            <td><strong id="Cart_Order_Total"><?php echo number_format($totalPrice, 2); ?></strong></td>
            <td></td>
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