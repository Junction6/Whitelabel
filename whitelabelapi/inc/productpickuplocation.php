<?php if (property_exists($productSelected, 'ProductPickupLocations') && 
        $productSelected->ProductPickupLocations && $ProductPickupLocations = $productSelected->ProductPickupLocations) :?>
    <div id="SystemPickupLocationID" class="field form-group control-group dropdown   required input-medium gutter-top ">
        <label class="control-label" >Please select a pickup location</label>
        <div class="controls">
            <div class="">
                <div style="width: 150px" id="s2id1" class="select2-container  hidden
                     required input-medium gutter-top">   
                    <a href="javascript:void(0)" class="select2-choice">   
                        <span></span>
                        <abbr class="select2-search-choice-close" style="display:none;"></abbr>   
                        <div><b></b></div>
                    </a>    
                    <div class="select2-drop select2-offscreen">   
                        <div class="select2-search">       
                            <input tabindex="0" autocomplete="off" class="select2-input" type="text">   
                        </div>   
                        <ul class="select2-results">   </ul>
                    </div>
                </div>
                <select style="display: none;" class=" required input-medium gutter-top" 
                                name="ProductPickupLocationID[<?php echo $productSelected->ID ?>]">
                    <?php $selected = "";
                      
                        foreach ($ProductPickupLocations as $ProductPickupLocation => $PickupPointLocation ) :?>
                            <?php
                                    $PickupPoint = $PickupPointLocation->PickupPoint;
                            ?>
                        <?php if ($PickupPointLocation->DefaultPickup){ $selected ="selected='selected'";} ?>
                        <?php if ($PickupPoint->AvailableToWhitelabel || true) :?>
                            <option <?php echo $selected; $selected="";?> value="<?php echo $PickupPointLocation->ID ?>">
                                <?php echo $PickupPoint->Title; ?>
                            </option>
                        <?php endif;?>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>            
<?php endif;?>

    
