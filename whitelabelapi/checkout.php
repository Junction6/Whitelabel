<?php include 'inc/config.php';
      include 'inc/utilities.php'; 
        ?>
<?php 
    $order = isset($_SESSION["whitelabel.api.order"]) ? $_SESSION["whitelabel.api.order"] : null;
    if ($order && is_object($order) && $order->Items) :?>
<!DOCTYPE html>

<html>
    <head>
        <meta content="text/html; charset=UTF-8" http-equiv="Content-type">
        <title>Checkout » Junction6</title>
        <link href="css/select2.css" type="text/css" rel="stylesheet">
        <link href="css/datepicker.css" type="text/css" rel="stylesheet">
        <link href="css/bootstrap.css" type="text/css" rel="stylesheet">
        <link href="css/bootstrap-responsive.css" type="text/css" rel="stylesheet">
        <link href="css/twitter-bootstrap-adjustments.css" type="text/css" rel="stylesheet">
        <link href="css/form.css" type="text/css" rel="stylesheet">
        <link href="css/layout.css" type="text/css" rel="stylesheet">
        <link href="css/webinterface.css" type="text/css" rel="stylesheet">
        <link href="css/css.css" type="text/css" rel="stylesheet">
        <script type="text/javascript" src="js/jquery-1.7.js"></script>
        <script type="text/javascript" src="js/bootstrap.js"></script>
        <script type="text/javascript" src="js/select2.js"></script>
        <script type="text/javascript" src="js/utility.js"></script>
    </head>
    <body>
         <?php 
                getHtmlAlertMessage();
             
         ?>
        <div class="container-fluid">
            <div class="row-fluid">
                <div id="main" class="span9">
                    <div class="well">
                        <div id="Checkout">
                            <div class="typography">
				<h2 class="pageTitle">Your Purchase</h2>
                            </div>	
                            <form id="checkoutform" class="checkoutform" action="completeorder.php" method="post" enctype="application/x-www-form-urlencoded" 
                                  class="form-vertical">
                                <div id="checkoutform_error" class="alert alert-" style="display: none"></div>
                                    <fieldset>
                                        <div>
                                            <button class="btn action  hide" id="checkoutform_Update-Cart" 
                                                    type="submit" name="action_updateOrder" value="">Update Cart
                                            </button>
                                        </div>
                                        <div id="" class="field form-group  bootstrapcomposite  nolabel ">
                                            <div class="">
						<div class="">
							<div class="clearfix field CompositeField">
                                                            <div id="" class="field form-group  bootstrapcomposite  nolabel ">
                                                                <div class="">
                                                                    <div class="">
                                                                        <div class="clearfix field CompositeField">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <table class="editable table table-striped table-bordered">
                                                                <tbody>
                                                                    <tr>
                                                                        <th>Products</th>
                                                                        <th>Unit Price</th>
                                                                        <th>Quantity</th>
                                                                        <th>Total Price</th>
                                                                        <th>&nbsp;</th>
                                                                    </tr>
                                                                    <!-- next row display item within the order  -->
                                                                    <?php $items = $order->Items; $totalPrice=0; $subTotalPrice=0; ?>
                                                                    <?php foreach( $items as $item) :?>
                                                                    <?php if (is_object($item)) :?>
                                                                        <tr id="" class="product_orderitem orderitem orderattribute 
                                                                            hideOnZeroItems orderItemHolder">
                                                                            <td class="product title" scope="row">
                                                                                <?php 
                                                                                    $subItemTitle="";
                                                                                    $variationTitle = ($item->Title) ? "{$item->Title}" : 'Unknown'; 
                                                                                    $mainItemTitle = $item->Product->Title . " ($variationTitle) " ;
                                                                                    $status = "";
                                                                                    $mainItemEventTitle = "";
                                                                                    if (property_exists($item, 'Event') && $event = $item->Event){
                                                                                        $time ="";
                                                                                        $status = $event->ManifestStatus && $event->ManifestStatus != 'Open' ? "$event->ManifestStatus " : "";
                                                                                        $pickupPoint = property_exists($item, 'PickupLocationInfo') && 
                                                                                                       $item->PickupLocationInfo ? 
                                                                                                ", Pickup Point: {$item->PickupLocationInfo}" : "";
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
                                                                                            $mainItemEventTitle = "Booking: {$date} {$time}{$pickupPoint}";
                                                                                        }
                                                                                    }
                                                                                    $mainItemTitle = strtoupper($status) . $mainItemTitle;
                                                                                    //ok, is this item from the PackageProduct?
                                                                                    //we need to display information about the item in the Products within the PackageProduct
                                                                                    if (property_exists($item->Product, 'Products') && $pProducts= $item->Product->Products){
                                                                                        include 'inc/subitems.php';
                                                                                    }
                                                                                ?>
                                                                                <div>
                                                                                    <small>
                                                                                        <?php echo $item->Product->Supplier->Name; ?>
                                                                                    </small>
                                                                                </div>
                                                                                    
                                                                                <a href="productdetails.php?id=<?php echo $item->Product->ID ?>">
                                                                                    <?php echo $mainItemTitle ?>
                                                                                </a>
                                                                                <div class="tableSubTitle" id="">
                                                                                    <small><?php echo $mainItemEventTitle; ?>
                                                                                        <small><?php echo $subItemTitle; ?></small>
                                                                                    </small>
                                                                                </div>
<!--                                                                                <small>
                                                                                    <small>
                                                                                    </small>
                                                                                </small>-->
                                                                            </td>
                                                                            <td class="right unitprice">
                                                                                <?php echo number_format($item->UnitPrice,2); ?>
                                                                            </td>
                                                                            <td class="center quantity">
                                                                                <?php echo number_format($item->Quantity,2);?>
                                                                            </td>
                                                                            <td class="right total" id="">
                                                                                <?php $subTotalPrice = $item->Quantity * $item->UnitPrice; 
                                                                                      $totalPrice += $subTotalPrice;
                                                                                      echo number_format($subTotalPrice,2); ?>
                                                                            </td>
                                                                            <td class="right action">
                                                                                <strong>
                                                                                    <!--
                                                                                    <a class="hover-expand btn" href="shoppingcart.php?action=edit&BackURL=checkout.php" data-toggle="collapse" data-prevent="default" data-target=".485680_extra" data-tooltip="">
                                                                                        <i class="icon-pencil"></i>
                                                                                        <span class="btn expand">
                                                                                            <i class="icon-pencil"></i>
                                                                                            <span> Edit<span></span>
                                                                                            </span>
                                                                                        </span>
                                                                                    </a>-->
                                                                                    <a class="hover-expand btn ajaxQuantityLink" href="shoppingcart.php?action=deleteitem&id=<?php echo "{$item->OrderItemID}&productid={$item->Product->ID}"; ?>&BackURL=checkout.php" data-tooltip="" title="<?php echo $mainItemTitle; ?>">
                                                                                        <i class="icon-remove icon-white"></i>
                                                                                        <span class="expand btn btn-danger">
                                                                                                <i class="icon-remove icon-white"></i>
                                                                                                <span> Delete</span>
                                                                                        </span>
                                                                                    </a>
                                                                                </strong>
                                                                            </td>
                                                                        </tr>
                                                                        <?php endif; ?>
                                                                    <?php endforeach; ?>
                                                                    <!-- next row display the total cost of the order -->
                                                                    <tr class="totals">
                                                                        <th>Sales Tax:</th>
                                                                        <td>&nbsp;</td>
                                                                        <th>&nbsp;</th>
                                                                        <th class="monetary-cell"><?php echo $order->SalesTax;?></th>	
                                                                        <td>&nbsp;</td>
                                                                    </tr>
                                                                    <tr class="totals">
                                                                        <th>Total (exc. Sales Tax):</th>
                                                                        <td>&nbsp;</td>
                                                                        <th>&nbsp;</th>
                                                                        <th class="monetary-cell"><?php echo number_format($totalPrice,2); ?></th>	
                                                                        <td>&nbsp;</td>
                                                                    </tr>
                                                                    <tr class="totals">
                                                                        <th>Total:</th>
                                                                        <td>&nbsp;</td>
                                                                        <td></td>
                                                                        <th class="monetary-cell"><?php echo number_format($totalPrice,2); ?></th>
                                                                        <td></td>
                                                                    </tr>	
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <div id="" class="field form-group  bootstrapcomposite  col-md-5 form-horizontal nolabel ">
                                            <div class="">
                                                <div class="">
                                                    <div id="RightOrder" class="clearfix field CompositeField">
                                                        <h3 id="checkoutform_HeaderFieldCustomer-Information">Customer Information</h3>
                                                            
                                                            <div id="" class="field form-group  bootstrapcomposite  nolabel ">
                                                                <div class="">
                                                                    <div class="">
                                                                        <div class="clearfix field CompositeField">
                                                                            <div id="Firstname" class="field form-group control-group text  required ">
                                                                                <label class="control-label" for="checkoutform_Firstname">
                                                                                    Firstname
                                                                                </label>
                                                                                <div class="controls">
                                                                                    <div class="">
                                                                                        <input class="text required" id="checkoutform_Firstname" 
                                                                                               name="Firstname" type="text" required>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div id="Surname" class="field form-group control-group text  required ">
                                                                                <label class="control-label" for="checkoutform_Surname">
                                                                                   Surname
                                                                                </label>
                                                                                <div class="controls">
                                                                                    <div class="">
                                                                                        <input class="text required" id="checkoutform_Surname" 
                                                                                               name="Surname" type="text" required>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div id="Email" class="field form-group control-group email required ">
                                                                            <label class="control-label" for="checkoutform_Email">
                                                                                Email
                                                                            </label>
                                                                            <div class="controls">
                                                                                <div class="">
                                                                                    <input class="text required" id="checkoutform_Email" 
                                                                                           name="Email" type="text">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div id="ConfirmEmail" class="field form-group control-group email js-confirm-email required">
                                                                            <label class="control-label" for="checkoutform_ConfirmEmail">
                                                                                Confirm Email
                                                                            </label>
                                                                            <div class="controls">
                                                                                <div class="">
                                                                                    <input class="text js-confirm-email required" id="checkoutform_ConfirmEmail" 
                                                                                           name="ConfirmEmail" type="text" required>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div id="Country" class="field form-group control-group 
                                                                             dropdown  required ">
                                                                            <label class="control-label" for="checkoutform_Country">
                                                                                Country
                                                                            </label>
                                                                            <div class="controls">
                                                                                <div class="">
                                                                                    <div style="width: 220px" id="s2id1" class="select2-container  required hidden">
                                                                                        <a tabindex="-1" href="javascript:void(0)" class="select2-choice">   
                                                                                            <span>New Zealand</span>
                                                                                            <abbr class="select2-search-choice-close" style="display:none;"></abbr>
                                                                                            <div><b></b></div>
                                                                                        </a>
                                                                                        <div style="display: block;" class="select2-drop select2-with-searchbox select2-offscreen">
                                                                                            <div class="select2-search">
                                                                                                <input tabindex="0" autocomplete="off" class="select2-input select2-focused" type="text">
                                                                                            </div>
                                                                                            <ul class="select2-results"></ul>
                                                                                        </div>    
                                                                                    </div>
                                                                                    <select style="display: none;" class=" required" id="checkoutform_Country" name="Country" required>
                                                                                        <option value="AF">Afghanistan</option>
                                                                                        <option value="AL">Albania</option><option value="DZ">Algeria</option>
                                                                                        <option value="AS">American Samoa</option><option value="AD">Andorra</option>
                                                                                        <option value="AO">Angola</option><option value="AI">Anguilla</option><option value="AQ">Antarctica</option>
                                                                                        <option value="AG">Antigua and Barbuda</option><option value="AR">Argentina</option><option value="AM">Armenia</option>
                                                                                        <option value="AW">Aruba</option><option value="AP">Asia/Pacific Region</option><option value="AU">Australia</option>
                                                                                        <option value="AT">Austria</option><option value="AZ">Azerbaijan</option><option value="BS">Bahamas</option>
                                                                                        <option value="BH">Bahrain</option><option value="BD">Bangladesh</option><option value="BB">Barbados</option>
                                                                                        <option value="BY">Belarus</option><option value="BE">Belgium</option><option value="BZ">Belize</option>
                                                                                        <option value="BJ">Benin</option><option value="BM">Bermuda</option><option value="BT">Bhutan</option>
                                                                                        <option value="BO">Bolivia</option>
                                                                                        <option value="BA">Bosnia and Herzegovina</option><option value="BW">Botswana</option><option value="BV">Bouvet Island</option>
                                                                                        <option value="BR">Brazil</option><option value="IO">British Indian Ocean Territory</option>
                                                                                        <option value="BN">Brunei Darussalam</option><option value="BG">Bulgaria</option>
                                                                                        <option value="BF">Burkina Faso</option><option value="BI">Burundi</option><option value="KH">Cambodia</option>
                                                                                        <option value="CM">Cameroon</option><option value="CA">Canada</option><option value="CV">Cape Verde</option>
                                                                                        <option value="KY">Cayman Islands</option><option value="CF">Central African Republic</option>
                                                                                        <option value="TD">Chad</option><option value="CL">Chile</option><option value="CN">China</option>
                                                                                        <option value="CX">Christmas Island</option><option value="CC">Cocos (Keeling) Islands</option>
                                                                                        <option value="CO">Colombia</option><option value="KM">Comoros</option><option value="CG">Congo</option>
                                                                                        <option value="CK">Cook Islands</option><option value="CR">Costa Rica</option><option value="CI">Cote D'Ivoire</option>
                                                                                        <option value="HR">Croatia</option><option value="CU">Cuba</option><option value="CY">Cyprus</option>
                                                                                        <option value="CZ">Czech Republic</option><option value="DK">Denmark</option><option value="DJ">Djibouti</option>
                                                                                        <option value="DM">Dominica</option><option value="DO">Dominican Republic</option><option value="TL">East Timor</option>
                                                                                        <option value="EC">Ecuador</option><option value="EG">Egypt</option><option value="SV">El Salvador</option>
                                                                                        <option value="GQ">Equatorial Guinea</option><option value="ER">Eritrea</option><option value="EE">Estonia</option>
                                                                                        <option value="ET">Ethiopia</option><option value="EU">Europe</option><option value="FK">Falkland Islands (Malvinas)</option>
                                                                                        <option value="FO">Faroe Islands</option><option value="FJ">Fiji</option><option value="FI">Finland</option>
                                                                                        <option value="FR">France</option><option value="FX">France (Metropolitan)</option><option value="GF">French Guiana</option>
                                                                                        <option value="PF">French Polynesia</option><option value="TF">French Southern Territories</option>
                                                                                        <option value="GA">Gabon</option><option value="GM">Gambia</option><option value="GE">Georgia</option>
                                                                                        <option value="DE">Germany</option><option value="GH">Ghana</option><option value="GI">Gibraltar</option>
                                                                                        <option value="GR">Greece</option><option value="GL">Greenland</option><option value="GD">Grenada</option>
                                                                                        <option value="GP">Guadeloupe</option><option value="GU">Guam</option><option value="GT">Guatemala</option>
                                                                                        <option value="GN">Guinea</option><option value="GW">Guinea-Bissau</option><option value="GY">Guyana</option>
                                                                                        <option value="HT">Haiti</option><option value="HM">Heard Island and McDonald Islands</option>
                                                                                        <option value="VA">Holy See (Vatican City State)</option><option value="HN">Honduras</option><option value="HK">Hong Kong</option><option value="HU">Hungary</option><option value="IS">Iceland</option><option value="IN">India</option><option value="ID">Indonesia</option><option value="IR">Iran - Islamic Republic of</option>
                                                                                        <option value="IQ">Iraq</option><option value="IE">Ireland</option><option value="IL">Israel</option><option value="IT">Italy</option><option value="JM">Jamaica</option><option value="JP">Japan</option><option value="JO">Jordan</option><option value="KZ">Kazakhstan</option><option value="KE">Kenya</option><option value="KI">Kiribati</option>
                                                                                        <option value="KW">Kuwait</option><option value="KG">Kyrgyzstan</option><option value="LA">Lao People's Democratic Republic</option>
                                                                                        <option value="LV">Latvia</option><option value="LB">Lebanon</option><option value="LS">Lesotho</option>
                                                                                        <option value="LR">Liberia</option><option value="LY">Libyan Arab Jamahiriya</option><option value="LI">Liechtenstein</option>
                                                                                        <option value="LT">Lithuania</option><option value="LU">Luxembourg</option><option value="MO">Macao</option><option value="MK">Macedonia - the Former Yugoslav Republic of</option>
                                                                                        <option value="MG">Madagascar</option><option value="MW">Malawi</option>
                                                                                        <option value="MY">Malaysia</option><option value="MV">Maldives</option><option value="ML">Mali</option>
                                                                                        <option value="MT">Malta</option><option value="MH">Marshall Islands</option><option value="MQ">Martinique</option>
                                                                                        <option value="MR">Mauritania</option><option value="MU">Mauritius</option><option value="YT">Mayotte</option>
                                                                                        <option value="MX">Mexico</option><option value="FM">Micronesia - Federated States of</option><option value="MD">Moldova - Republic of</option><option value="MC">Monaco</option><option value="MN">Mongolia</option><option value="ME">Montenegro</option>
                                                                                        <option value="MS">Montserrat</option><option value="MA">Morocco</option><option value="MZ">Mozambique</option><option value="MM">Myanmar</option>
                                                                                        <option value="NA">Namibia</option><option value="NR">Nauru</option>
                                                                                        <option value="NP">Nepal</option><option value="NL">Netherlands</option><option value="AN">Netherlands Antilles</option><option value="NC">New Caledonia</option><option selected="selected" value="NZ">New Zealand</option>
                                                                                        <option value="NI">Nicaragua</option>
                                                                                        <option value="NE">Niger</option><option value="NG">Nigeria</option>
                                                                                        <option value="NU">Niue</option><option value="NF">Norfolk Island</option><option value="KP">North Korea</option>
                                                                                        <option value="MP">Northern Mariana Islands</option><option value="NO">Norway</option><option value="OM">Oman</option><option value="PK">Pakistan</option><option value="PW">Palau</option>
                                                                                        <option value="PS">Palestinian Territory - Occupied</option><option value="PA">Panama</option><option value="PG">Papua New Guinea</option><option value="PY">Paraguay</option><option value="PE">Peru</option>
                                                                                        <option value="PH">Philippines</option><option value="PN">Pitcairn</option><option value="PL">Poland</option>
                                                                                        <option value="PT">Portugal</option><option value="PR">Puerto Rico</option><option value="QA">Qatar</option><option value="RE">Reunion</option>
                                                                                        <option value="RO">Romania</option><option value="RU">Russian Federation</option><option value="RW">Rwanda</option>
                                                                                        <option value="SH">Saint Helena</option><option value="KN">Saint Kitts and Nevis</option><option value="LC">Saint Lucia</option><option value="PM">Saint Pierre and Miquelon</option><option value="VC">Saint Vincent and the Grenadines</option>
                                                                                        <option value="WS">Samoa</option><option value="SM">San Marino</option><option value="ST">Sao Tome and Principe</option>
                                                                                        <option value="SA">Saudi Arabia</option><option value="SN">Senegal</option><option value="RS">Serbia</option>
                                                                                        <option value="SC">Seychelles</option><option value="SL">Sierra Leone</option><option value="SG">Singapore</option>
                                                                                        <option value="SK">Slovakia</option><option value="SI">Slovenia</option><option value="SB">Solomon Islands</option><option value="SO">Somalia</option>
                                                                                        <option value="ZA">South Africa</option>
                                                                                        <option value="GS">South Georgia and the South Sandwich Islands</option><option value="KR">South Korea</option>
                                                                                        <option value="ES">Spain</option><option value="LK">Sri Lanka</option><option value="SD">Sudan</option><option value="SR">Suriname</option>
                                                                                        <option value="SJ">Svalbard and Jan Mayen</option><option value="SZ">Swaziland</option>
                                                                                        <option value="SE">Sweden</option><option value="CH">Switzerland</option><option value="SY">Syrian Arab Republic</option>
                                                                                        <option value="TW">Taiwan</option><option value="TJ">Tajikistan</option>
                                                                                        <option value="TZ">Tanzania (United Republic of)</option><option value="TH">Thailand</option><option value="TG">Togo</option>
                                                                                        <option value="TK">Tokelau</option><option value="TO">Tonga</option><option value="TT">Trinidad and Tobago</option><option value="TN">Tunisia</option>
                                                                                        <option value="TR">Turkey</option><option value="TM">Turkmenistan</option>
                                                                                        <option value="TC">Turks and Caicos Islands</option><option value="TV">Tuvalu</option><option value="X1">UK - England</option>
                                                                                        <option value="X3">UK - Northern Ireland</option><option value="X2">UK - Scotland</option>
                                                                                        <option value="X4">UK - Wales</option><option value="UG">Uganda</option><option value="UA">Ukraine</option>
                                                                                        <option value="AE">United Arab Emirates</option><option value="GB">United Kingdom</option><option value="US">United States</option><option value="UM">United States Minor Outlying Islands</option><option value="UY">Uruguay</option>
                                                                                        <option value="UZ">Uzbekistan</option><option value="VU">Vanuatu</option><option value="VE">Venezuela</option>
                                                                                        <option value="VN">Vietnam</option><option value="VG">Virgin Islands - British</option><option value="VI">Virgin Islands - U.S.</option>
                                                                                        <option value="WF">Wallis and Futuna</option><option value="EH">Western Sahara</option><option value="YE">Yemen</option>
                                                                                        <option value="ZR">Zaire</option><option value="ZM">Zambia</option><option value="ZW">Zimbabwe</option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                    </div>
                                                    <div id="" class="field form-group  bootstrapcomposite  nolabel ">
                                                        <div class="">
                                                            <div class="">
                                                                <div class="clearfix field CompositeField"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="" class="field form-group  bootstrapcomposite  nolabel ">
                                                        <div class="">
                                                            <div class="">
                                                                <div class="clearfix field CompositeField"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <input class="hidden" id="checkoutform_manageCustomerEmail" 
                                                           name="manageCustomerEmail" value="0" type="hidden">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div id="" class="field form-group  bootstrapcomposite  col-md-7 form-horizontal nolabel ">
                                            <div class="">
                                                <div class="">
                                                    <div id="LeftOrder" class="clearfix field CompositeField">
                                                        <h3 id="checkoutform_HeaderFieldPayment-Information">Payment Information</h3>
                                                        <div id="TotalAmount" class="field form-group control-group">
                                                            <label class="control-label">
                                                                Amount to Pay</label>
                                                            <div class="controls">
                                                                <div class="">
                                                                    <span class="readonly  ">
                                                                        <span class="symbol"></span>
                                                                        <span id="checkoutform_TotalAmount"><?php echo number_format($totalPrice,2); ?></span>
                                                                        <span></span>
                                                                        <input name="TotalAmount" value="<?php echo number_format($totalPrice,2); ?>" type="hidden">
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="field form-group  bootstrapselectiongroup  js-selectGroupToggle required " id="ClientPaymentClassID">
                                                            <label  class="control-label">Payment Method</label>
                                                        </div>
                                                        <div id="PayAmount" class="field form-group control-group ">
                                                            <label class="control-label" for="checkoutform_PayAmount">
                                                                Amount Being Paid Now</label>
                                                            <div class="controls">
                                                                <div class="">
                                                                    <span class="readonly  ">
                                                                        <span class="symbol"></span>
                                                                        <span><?php echo number_format($totalPrice,2); ?></span>
                                                                        <span></span>
                                                                        <input name="PayAmount" value="<?php echo number_format($totalPrice,2); ?>" type="hidden">
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clear"><!-- --></div>
                    
                                    </fieldset>

                                <div class="">
                                    <a class="btn btn-default" href="index.php">Go Back</a>
                                    <button class="btn action  btn-default btn-xs" type="submit" 
                                            name="processOrderContinue" value="Place order and continue">
                                        Place order and continue
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>  
                </div>
            </div>
         </div>
    </body>
</html>
<?php else :?>
 <?php $host  = $_SERVER['HTTP_HOST'];
        $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location: http://$host$uri/index.php");
?>
<?php endif;