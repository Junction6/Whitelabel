<?php if (property_exists($productSelected, 'Products') && 
          is_array($productSelected->Products) && count($productSelected->Products) > 0) :?>
             
    <div id="" class="field form-group  bootstrapcomposite  nolabel ">
        <div class="">
            <div class="">
                <div class="clearfix field CompositeField">
                    <h2 id="Form_BuyForm_RelatedProducts">Package Products</h2>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Price</th>
                                <th>Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                  foreach ($productSelected->Products as $oPProduct) :?>
                                <?php
                                    if ($oPProduct->Item && $item = $oPProduct->Item ) :?>
                                    <?php if ($item->ShowOnWhitelabel || $productSelected->ShowOnWhitelabel) :?>
                                        <tr>
                                            <td><?php echo $oPProduct->Title ; ?></td>
                                            <td><?php  $price = number_format($item->Price,2);
                                               echo $price; ?></td>
                                            <td>
                                                <?php echo $oPProduct->Quantity ?>
                                                    <input
                                                           name="<?php echo "PackageProductItems[{$item->ID}][Quantity]"; ?>" 
                                                           value="<?php echo $oPProduct->Quantity ?>" type="hidden">
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php endif; ?>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php endif ;
