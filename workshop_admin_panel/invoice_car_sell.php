<?php 
include('../helper/common.php');  
include_once('../config.php');
if(isset($_GET['invoice_id']) && !empty($_GET['invoice_id'])) {
	$site_name = '';
	$currency = '';
	$email = '';
	$address = '';
	$invoice_data = array();
	//get general info
	$wms = new wms_core();
	$result = $wms->getWebsiteSettingsInformation($link);
	if(!empty($result)) {
		$site_name = $result['site_name'];
		$currency = $result['currency'];
		$email = $result['email'];
		$address = $result['address'];
	}
	
	$row = $wms->carSellInvoiceGenerate($link,$_GET['invoice_id']);
	if(!empty($row) && count($row) > 0) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Invoice Preview</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<style type="text/css">
		.totals-row td {
			border-right:none !important;
			border-left:none !important;
		}
		.totals-row td strong,.items-table th {
			white-space:nowrap;
		}
		</style>
</head>
<body cz-shortcut-listen="true">
<div id="editor" class="edit-mode-wrap" style="margin-top: 20px">
  <style type="text/css">
* { margin:0; padding:0; }
body { background:#fff; font-family:Arial, Helvetica, sans-serif; font-size:12px; line-height:20px; }
#extra {text-align: right; font-size: 22px; width:250px; font-weight: 700}
.invoice-wrap { width:700px; margin:0 auto; background:#FFF; color:#000 }
.invoice-inner { margin:0 15px; padding:20px 0 }
.invoice-address { border-top: 3px double #000000; margin: 25px 0; padding-top: 25px; }
.bussines-name { font-size:18px; font-weight:100 }
.invoice-name { font-size:22px; font-weight:700 }
.listing-table th { background-color: #e5e5e5; border-bottom: 1px solid #555555; border-top: 1px solid #555555; font-weight: bold; text-align:left; padding:6px 4px }
.listing-table td { border-bottom: 1px solid #555555; text-align:left; padding:5px 6px; vertical-align:top }
.total-table td { border-left: 1px solid #555555; }
.total-row { background-color: #e5e5e5; border-bottom: 1px solid #555555; border-top: 1px solid #555555; font-weight: bold; }
.row-items { margin:5px 0; display:block }
.notes-block { margin:50px 0 0 0 }
/*tables*/
table td { vertical-align:top}
.items-table { border:1px solid #1px solid #555555; border-collapse:collapse; width:100%}
.items-table td, .items-table th { border:1px solid #555555; padding:4px 5px ; text-align:left}
.items-table th { background:#f5f5f5;}
.totals-row .wide-cell { border:1px solid #fff; border-right:1px solid #555555; border-top:1px solid #555555}
</style>
  <div class="invoice-wrap">
    <div class="invoice-inner">
      <table width="100%" cellspacing="0" cellpadding="0" border="0">
        <tbody>
          <tr>
            <td class="is_logo" valign="top" align="left"><img class="editable-area" id="logo" src="../img/logo.png" width="122" height="102"></td>
            <td valign="top" align="right"><div class="business_info">
                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                  <tbody>
                    <tr>
                      <td><span class="editable-area" id="business_info"><span id="company_title"><?php echo $site_name; ?></span><br>
                        <div style="white-space: pre-wrap;"><?php echo $address; ?></div>
                        </span></td>
                    </tr>
                  </tbody>
                </table>
              </div></td>
            <td valign="top" align="right"><p class="editable-text" id="extra">Car Sale Invoice</p></td>
          </tr>
        </tbody>
      </table>
      <div class="invoice-address">
        <table width="100%" cellspacing="0" cellpadding="0" border="0">
          <tbody>
            <tr>
              <td width="50%" valign="top" align="left"><table cellspacing="0" cellpadding="0" border="0">
                  <tbody>
                    <tr>
                      <td style="" width="" valign="top"><strong><span class="editable-text" id="label_bill_to">Bill To</span></strong></td>
                      <td valign="top"><div class="client_info">
                          <table cellspacing="0" cellpadding="0" border="0">
                            <tbody>
                              <tr>
                                <td style="padding-left:25px;"><span class="editable-area" id="client_info"><?php echo $row['buyer_name']; ?><br>
                                  <div style="white-space: pre-wrap;"><?php echo $row['present_address']; ?></div>
                                  </span></td>
                              </tr>
                            </tbody>
                          </table>
                        </div></td>
                    </tr>
                  </tbody>
                </table></td>
              <td width="50%" valign="top" align="right"><table cellspacing="0" cellpadding="0" border="0">
                  <tbody>
                    <tr>
                      <td align="right"><strong><span class="editable-text" id="label_invoice_no">Invoice no.</span></strong></td>
                      <td style="padding-left:20px" align="left"><span class="editable-text" id="no"><?php echo isset($_GET['invoice_id']) ? $_GET['invoice_id'] : ''; ?></span></td>
                    </tr>
                    <tr>
                      <td align="right"><strong><span class="editable-text" id="label_date">Date</span></strong></td>
                      <td style="padding-left:20px" align="left"><span class="editable-text" id="date"><?php echo $wms->mySqlToDatePicker($row['selling_date']); ?></span></td>
                    </tr>
                    <!-- Fieldl-->
                    <tr class="field1_row">
                      <td align="right"><strong><span class="editable-text" id="label_field1"></span></strong></td>
                      <td style="padding-left:20px;" align="left"><span class="editable-text" id="field1_value"></span></td>
                    </tr>
                    <!-- /Fieldl-->
                    <!-- Field2-->
                    <tr class="field2_row">
                      <td align="right"><strong><span class="editable-text" id="label_field2"></span></strong></td>
                      <td style="padding-left:20px;" align="left"><span class="editable-text" id="field2_value"></span></td>
                    </tr>
                    <!-- /Field2-->
                    <!-- Field3-->
                    <tr class="field3_row">
                      <td align="right"><strong><span class="editable-text" id="label_field3"></span></strong></td>
                      <td style="padding-left:20px;" align="left"><span class="editable-text" id="field3_value"></span></td>
                    </tr>
                    <!-- /Field3-->
                    <!-- Field4-->
                    <tr class="field4_row">
                      <td align="right"><strong><span class="editable-text" id="label_field4"></span></strong></td>
                      <td style="padding-left:20px;" align="left"><span class="editable-text" id="field4_value"></span></td>
                    </tr>
                    <!-- /Field4-->
                    <!-- Field5-->
                    <tr class="field5_row">
                      <td align="right"><strong><span class="editable-text" id="label_field5"></span></strong></td>
                      <td style="padding-left:20px;" align="left"><span class="editable-text" id="field5_value"></span></td>
                    </tr>
                    <!-- /Field5-->
                    <!-- Field6-->
                    <tr class="field6_row">
                      <td align="right"><strong><span class="editable-text" id="label_field6"></span></strong></td>
                      <td style="padding-left:20px;" align="left"><span class="editable-text" id="field6_value"></span></td>
                    </tr>
                    <!-- /Field6-->
                    <!-- Field7-->
                    <tr class="field7_row">
                      <td align="right"><strong><span class="editable-text" id="label_field7"></span></strong></td>
                      <td style="padding-left:20px;" align="left"><span class="editable-text" id="field7_value"></span></td>
                    </tr>
                    <!-- /Field7-->
                    <!-- Field8-->
                    <tr class="field8_row">
                      <td align="right"><strong><span class="editable-text" id="label_field8"></span></strong></td>
                      <td style="padding-left:20px;" align="left"><span class="editable-text" id="field8_value"></span></td>
                    </tr>
                    <!-- /Field8-->
                    <!-- Field9-->
                    <tr class="field9_row">
                      <td align="right"><strong><span class="editable-text" id="label_field9"></span></strong></td>
                      <td style="padding-left:20px;" align="left"><span class="editable-text" id="field9_value"></span></td>
                    </tr>
                    <!-- /Field9-->
                    <!-- Field10-->
                    <tr class="field10_row">
                      <td align="right"><strong><span class="editable-text" id="label_field10"></span></strong></td>
                      <td style="padding-left:20px;" align="left"><span class="editable-text" id="field9_value"></span></td>
                    </tr>
                    <!-- /Field9-->
                  </tbody>
                </table></td>
            </tr>
          </tbody>
        </table>
      </div>
	  <br/>
      <div id="items-list">
        <div style="font-weight:bold;font-size:12px;border-bottom:solid 1px #000;margin-bottom:10px;">Car Information</div>
		<table class="table">
			<tr>
				<td><div style="border-bottom:solid 1px #000;width:200px;"><b>Make :</b>&emsp;<?php echo $row['make_name']; ?></div></td>
				<td><div style="border-bottom:solid 1px #000;width:200px;margin-left:32px;"><b>Model :</b>&emsp;<?php echo $row['model_name']; ?></div></td>
				<td><div style="border-bottom:solid 1px #000;width:200px;margin-left:32px;"><b>Year :</b>&emsp;<?php echo $row['year_name']; ?></div></td>
			</tr>
			<tr>
				<td style="padding-top:10px;"><div style="border-bottom:solid 1px #000;width:200px;"><b>Chasis No :</b>&emsp;<?php echo $row['car_chasis_no']; ?></div></td>
				<td style="padding-top:10px;"><div style="border-bottom:solid 1px #000;width:200px;margin-left:32px;"><b>Mileage :</b>&emsp;<?php echo $row['car_totalmileage']; ?></div></td>
				<td style="padding-top:10px;"><div style="border-bottom:solid 1px #000;width:200px;margin-left:32px;"><b>No of Door :</b>&emsp;<?php echo $row['door_name']; ?></div></td>
			</tr>
			<tr>
				
				<td style="padding-top:10px;"><div style="border-bottom:solid 1px #000;width:200px;"><b>Car Condition :</b>&emsp;<?php echo $row['car_condition']; ?></div></td>
				<td style="padding-top:10px;"><div style="border-bottom:solid 1px #000;width:200px;margin-left:32px;"><b>Car Color :</b>&emsp;<?php echo $row['color_name']; ?></div></td>
				<td style="padding-top:10px;"><div style="border-bottom:solid 1px #000;width:200px;margin-left:32px;"><b>Car Engine :</b>&emsp;<?php echo $row['car_engine_name']; ?></div></td>
			</tr>
		</table>
      </div>
	  <br/>
	  <br/>
      <div id="items-list">
        <table class="table table-bordered table-condensed table-striped items-table">
          <thead>
            <tr>
              <th>Description</th>
			  <th>Warranty</th>
              <th>Price</th>
              <th width="100">Total</th>
            </tr>
          </thead>
          <tfoot>
            <tr class="totals-row">
              <td colspan="2" class="wide-cell"></td>
              <td><strong>Total</strong></td>
              <td coslpan="2"><?php echo $currency; ?><?php echo $row['selling_price']; ?></td>
            </tr>
            <tr class="totals-row">
              <td colspan="2" class="wide-cell"></td>
              <td><strong>Paid Amount </strong></td>
              <td coslpan="2"><?php echo $currency; ?><?php echo $row['advance_amount']; ?></td>
            </tr>
            <tr class="totals-row">
              <td colspan="2" class="wide-cell"></td>
              <td><strong>Balance Due </strong></td>
              <td coslpan="2"><?php echo $currency; ?><?php echo $row['due_amount']; ?></td>
            </tr>
          </tfoot>
          <tbody>
            <tr>
              <td><?php echo $row['car_name']; ?></td>
              <td><?php echo $row['service_warranty']; ?></td>
			  <td><?php echo $currency; ?><?php echo $row['selling_price']; ?></td>
              <td><?php echo $currency; ?><?php echo $row['selling_price']; ?></td>
            </tr>
          </tbody>
        </table>
      </div>
	  <br/>
      <div class="notes-block">
        <table width="100%" cellspacing="0" cellpadding="0" border="0">
          <tbody>
            <tr>
              <td><div class="editable-area" id="notes" style="font-style:italic;border-top:solid 1px #000;width:150px;text-align:center;">Customer Signature</div></td>
            </tr>
          </tbody>
        </table>
      </div>
      <br>
      <br>
      <br>
      <br>
      &nbsp;</div>
  </div>
</div>
<style>
body {
    background: #EBEBEB;
}
.invoice-wrap {box-shadow: 0 0 4px rgba(0, 0, 0, 0.1); margin-bottom: 20px; }
#mobile-preview-close a {
position:fixed; left:20px; bottom:30px; 
background-color: #f5f5f5;
font-weight: 600;
outline: 0 !important;
line-height: 1.5;
border-radius: 3px;
font-size: 14px;
padding: 7px 10px;
border:1px solid #000;
color:#000;
text-decoration:none;
}
#company_title {
	font-weight:bold;
	font-size:28px;
}
#mobile-preview-close img {
	width:20px;
	height:auto;
}
#mobile-preview-close a:nth-child(2) {
	background:#f5f5f5;
	margin-bottom: 50px;
}
#mobile-preview-close a:nth-child(2) img {
    height: auto;
	position: relative;
	top: 2px;
}
.invoice-wrap {padding: 20px;}

@media screen and projection {
    a {
        display:inline;
    }
}
@media print {
    a {
        display:none;
    }
}
@page {margin:0 -6cm}
html {margin:0 6cm}
@media print {
  #mobile-preview-close a {
  display:none
}
.invoice-wrap {0}
body {
    background: none;
}
.invoice-wrap {box-shadow: none; margin-bottom: 0px;}

}
</style>
<div id="mobile-preview-close"><a style="" href="javascript:window.print();"><img src="<?php echo WEB_URL; ?>img/print.png" style="float:left; margin:0 10px 0 0;"> Print </a> <a style="" href="<?php echo WEB_URL; ?>dashboard.php"><img src="<?php echo WEB_URL; ?>img/back.png" style="float:left; margin:0 10px 0 0;"> Back </a> </div>
</body>
</html>
<?php } else { ?>
<div>Wrong invoice or no recored found for this invoice No.</div>
<?php } ?>
<?php } else { ?>
<div>Direct Access Not Allow.</div>
<?php } ?>
