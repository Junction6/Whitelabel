<?php if (property_exists($productSelected, 'Addons') && 
          is_array($productSelected->Addons) && count($productSelected->Addons) > 0) :?>
    <div id="" class="field form-group  bootstrapcomposite  nolabel ">
        <div class="">
            <div class="">
                <div class="clearfix field CompositeField">
                    <h2 id="Form_BuyForm_RelatedProducts">Package Addons</h2>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                  foreach ($productSelected->Addons as $oAddon) :?>
                                <?php
                                    if ($oAddon->Item && $item = $oAddon->Item ) :?>
                                    <?php if ($item->ShowOnWhitelabel || $productSelected->ShowOnWhitelabel) :?>
                                        <tr>
                                            <td><?php echo $oAddon->Title ?></td>
                                            <td><?php  $price = number_format($oAddon->Price,2);
                                               echo $price; ?></td>
                                            <input name="<?php echo "AddonsItems[{$item->ID}][Quantity]"; ?>" 
                                                       value="1" type="hidden">
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
