<?php $subItemTitle = "<ul>";
foreach($pProducts as $pProduct){
    
    if ($pProduct->Item && $pitem = $pProduct->Item ){
        $pProductTitle = $pProduct->Title ;
        $pstatus = " UNCONFIRMED ";
        $subItemEventTitle="";
        if (property_exists($pitem, 'Event') && $event = $pitem->Event){
            $time ="";
            $pstatus = $event->ManifestStatus && $event->ManifestStatus != 'Open' ? "$event->ManifestStatus " : "";
            $pickupPoint = property_exists($pitem, 'PickupLocationInfo') && 
                           $pitem->PickupLocationInfo ? 
                    ", Pickup Point: {$pitem->PickupLocationInfo}" : "";
            if ($event->Date){
                if (property_exists($event, 'Start') && 
                        $event->Start && 
                    property_exists($event, 'End') && 
                        $event->End){
                    $time = 
                            date('h:i:s A', strtotime($event->Start)) 
                            . " - " .
                            date('h:i:s A', strtotime($event->End));
                }
                $date = date('l jS  F Y', strtotime($event->Date));
                $subItemEventTitle = "Booking: {$date} {$time}{$pickupPoint}";
            }
        }
//        else{
//            $subItemEventTitle = "<small>Booking: <span class='label label-info'>Travel date not set</span>";
//        }
        $subItemTitle .= strtoupper($pstatus) . $pProductTitle .
                                              "<li>$subItemEventTitle</li>";
    }
}
$subItemTitle . "</ul>";