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
                                name="SystemPickupLocationID">
                    <?php $selected = "";
                      
                        foreach ($ProductPickupLocations as $ProductPickupLocation => $PickupPointLocation) :?>

                                                        <?php
                                                            /*
                                                             * 0] => stdClass Object ( [DefaultPickup] => 0
                                                             *  [PickupPoint] => stdClass Object ( 
                                                             * [ClassName] => PickupPoint 
                                                             * [Created] => 2013-11-08 19:22:33 [LastEdited] => 2013-11-08 19:22:33 
                                                             * [Title] => Narita T2 8pm and drop back to Tokyo Prince Htl [isAHub] => 0 [isInRoute] => 0 [ClientID] => 4241
                                                             */
                                                                $PickupPoint = $PickupPointLocation->PickupPoint;
                                                                print_r($PickupPointLocation);
                                                                print_r($PickupPoint);
                                                                /* pickuppoint
                                                                 * stdClass Object (     [ClassName] => PickupPoint   
                                                                 *   [Created] => 2013-11-08 19:22:33    
                                                                 *  [LastEdited] => 2013-11-08 19:22:33    
                                                                 *  [Title] => Narita T2 8pm and drop back to Tokyo Prince Htl 
                                                                 *     [isAHub] => 0     [isInRoute] => 0   
                                                                 *   [ClientID] => 4241     [PrimaryPickup] => 0     [Default] => 0     [AvailableToWhitelabel] => 1     [Surcharge] => 0.00     [LocationID] => 1356     [ID] => 1357     [RecordClassName] => PickupPoint )
                                                                 */

                                                            ?>
                        <?php if ($PickupPointLocation->DefaultPickup){ $selected ="selected='selected'";} ?>
                        <?php if ($PickupPoint->AvailableToWhitelabel) :?>
                            <option <?php echo $selected; $selected="";?> value="<?php echo $PickupPoint->ID ?>">
                                <?php echo $PickupPoint->Title; ?>
                            </option>
                        <?php endif;?>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>            
<?php endif;?>

    
