<?php if (property_exists($productSelected, 'Next10Events') && 
          is_array($productSelected->Next10Events) && count($productSelected->Next10Events) > 0) :?>
<div class="field control-group optionset  " id="Product_EventItem<?php echo $productSelected->ID ?>">
<label for="Form_Product_EventItem<?php echo $productSelected->ID ?>" >Select Date</label>
    <div class="controls">
        <ul class="optionset " id="Form_Product_EventItem<?php echo $productSelected->ID ?>">
            
<?php $checked = "checked=checked";
    foreach ($productSelected->Next10Events as $event) :?>
            <li class="val<?php echo $event->ID?>">
                <input type="radio" class="radio" value="<?php echo $event->ID?>" 
                       name="EventID[<?php echo $productSelected->ID ?>]" 
                       id="Form_Product_EventItem<?php echo $productSelected->ID . "_" . $event->ID ?>" 
                       <?php echo $checked; $checked="";?> >
                <label for="Form_Product_EventItem<?php echo $productSelected->ID . "_" . $event->ID ?>">
                       <?php 
                                $time =  ($event->Start  && $event->End) ? 
                                             " - ". $event->Start . "-" . $event->End  : "";
                                echo $event->Date . " $time (" . 
                                        $event->Quantity . " - left Status: ". $event->ManifestStatus . ")";
                        ?>
                        
                </label>
            </li>
    <?php endforeach; ?>
        </ul>
    </div>
</div>
<?php endif; 
