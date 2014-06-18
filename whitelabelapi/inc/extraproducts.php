<?php if (property_exists($productSelected, 'ExtraProducts') && 
          is_array($productSelected->ExtraProducts) && count($productSelected->ExtraProducts) > 0) :?>
    <div id="" class="field form-group  bootstrapcomposite  nolabel ">
        <div class="">
            <div class="">
                <div class="clearfix field CompositeField">
                    <h2 id="Form_BuyForm_RelatedProducts">Do you require any Extras?</h2>
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
                                foreach ($productSelected->ExtraProducts as $oExtraProduct) :?>
                                <?php
                                    if ($oExtraProduct->Item && $item = $oExtraProduct->Item) :?>
                                    <?php if ($item->ShowOnWhitelabel || $productSelected->ShowOnWhitelabel) :?>
                                        <tr>
                                            <td><?php echo $item->InternalItemID; ?></td>
                                            <td><?php  $price = number_format($item->Price,2);
                                               echo $price; ?></td>
                                            <td>
                                                <div class="input-prepend input-append input-group">
                                                    <button class="btn btn-primary input-group-addon">-</button>
                                                    <input class="text input-quantity js-quantity" 
                                                           name="<?php echo "ExtraProductsItems[{$item->ID}][Quantity]"; ?>" 
                                                           value="0" type="text">
                                                    <button class="btn btn-primary input-group-addon">+</button>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endif;?>
                                    <?php endif; ?>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php endif ;
    
            