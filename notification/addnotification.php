<?php include_once('../header.php');include_once('../helper/common.php');
$wms = new wms_core();
$invoice_no = '';
$sql = '';
$token = false;
$customer_id = 0;
$row = array();
$estimate_data = array();
$alert_box = false;
$msg = '';
$customer_email = '';

/************************ Insert Query ***************************/
if (!empty($_POST['txtInvoiceNo']) && !empty($_POST['txtInvoiceNo'])) {
	$row = $wms->getEstimateAndCarAndCustomerDetails($link, $_POST['txtInvoiceNo']);
	$invoice_no = $_POST['txtInvoiceNo'];
}
if(isset($_POST['send_email']) && !empty($_POST['send_email'])) {
	$wms->sendCustomerEmailNotification($link, $_POST['car_id'], $_POST['progress'], $_POST['invoice_id']);
	$alert_box = true;
	$msg = 'Sent email notification successfully';
}

//send custom email here
if(isset($_POST['send_custom_email']) && !empty($_POST['send_custom_email'])) {
	$from = $_POST['txtEmailFrom'];
	$to = $_POST['txtEmailTo'];
	$subject = $_POST['txtEmailSubject'];
	$details = $_POST['txtEmailDetails'];
	if(!empty($from) && !empty($to) && !empty($subject) && !empty($details)) {
		//send ready
		$wms->sendCustomerCustomEmail($from, $to, $subject, $details);
		$alert_box = true;
		$msg = 'Sent email successfully';
	} else {
		echo 'email information missing kindly reload page and try again';
		die();
	}
}


//system info
$system_info = $wms->getWebsiteSettingsInformation($link);
?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1><i class="fa fa-envelope-o"></i> Add Customer Notification </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Reminder</li>
    <li class="active">Send Notification</li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
<!-- Full Width boxes (Stat box) -->
<div class="row">
  <div class="col-md-12">
    <?php if($alert_box) { ?>
    <div class="alert alert-success alert-dismissable">
      <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-close"></i></button>
      <h4><i class="icon fa fa-check"></i> Success!</h4>
      <?php echo $msg; ?> </div>
    <?php } ?>
    <form id="frmcarstock" method="post" enctype="multipart/form-data">
      <div class="box box-success" id="box_model">
        <div class="box-body">
          <div class="box-header">
            <h3 class="box-title"><i class="fa fa-search"></i> Find Car</h3>
          </div>
          <div class="form-group col-md-12">
            <label for="txtInvoiceNo">Car Estimate No :</label>
            <input type="text" name="txtInvoiceNo" id="txtInvoiceNo" value="<?php echo $invoice_no; ?>" class="form-control" />
          </div>
          <div class="form-group col-md-12">
            <button type="submit" class="btn btn-success btn-large btn-block"><b>SEARCH</b></button>
          </div>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
    </form>
    <?php if(!empty($row) && count($row) > 0) { ?>
    <form id="frmemailsend" onsubmit="return confirmEmail();" method="post" enctype="multipart/form-data">
      <div class="box box-success" id="box_model">
        <div class="box-body">
          <div id="eloader" style="position:absolute;margin-left:400px;display:none;"><img src="<?php echo WEB_URL ?>/img/eloader.gif" /></div>
          <div class="form-group col-md-12" style="padding-top:10px;">
            <div class="pull-left">
              <label class="label label-danger" style="font-size:15px;">EST INVOICE - <?php echo $_POST['txtInvoiceNo']; ?></label>
            </div>
            <div class="pull-right">
              <button type="submit" class="btn btn-info btnsp"><i class="fa fa-envelope-o fa-2x"></i><br/>
              Send Email Notification</button>&nbsp;&nbsp;
              <button type="button" onclick="javascript:$('#custom_email_box').modal('show');" class="btn btn-success btnsp"><i class="fa fa-envelope-o fa-2x"></i><br/>
              Send Custom Email</button>
            </div>
          </div>
        </div>
      </div>
      <input type="hidden" name="progress" value="<?php echo $row['work_status']; ?>" />
      <input type="hidden" name="send_email" value="send_email" />
      <input type="hidden" name="car_id" value="<?php echo $row['car_id']; ?>" />
      <input type="hidden" name="invoice_id" value="<?php echo $invoice_no; ?>" />
    </form>
    <div class="box box-success" id="box_model">
      <div class="box-body">
        <?php
				if(!empty($row['estimate_data'])) {
					$estimate_data = json_decode($row['estimate_data']);
				}
				$customer_id = $row['customer_id'];
				$image = WEB_URL . 'img/no_image.jpg';
				if(file_exists(ROOT_PATH . '/img/upload/' . $row['car_image']) && $row['car_image'] != ''){
					$image = WEB_URL . 'img/upload/' . $row['car_image']; //car image
				}
				$token = true;
				
				?>
        <div class="box-header">
          <h3 class="box-title"><i class="fa fa-user"></i> Car Owner Details</h3>
        </div>
        <div class="form-group col-md-12 row">
          <?php
				$image_cust = WEB_URL . 'img/no_image.jpg';
				if(file_exists(ROOT_PATH . '/img/upload/' . $row['image']) && $row['image'] != ''){
					$image_cust = WEB_URL . 'img/upload/' . $row['image']; //car image
				}
				$customer_email = $row['c_email'];
			?>
          <div style="width:100%;height:auto;border:solid 1px #ccc;padding:10px;margin:10px;">
            <div class="col-md-3 text-center"><img class="img-thumbnail" style="width:150px;height:150px;" src="<?php echo $image_cust; ?>" /></div>
            <div class="col-md-3 text-left">
              <div>
                <h4 style="font-weight:bold;"><u><?php echo $row['c_name']; ?></u></h4>
              </div>
              <div><b>Email:</b> <?php echo $row['c_email']; ?></div>
              <div><b>Address:</b> <?php echo $row['c_address']; ?></div>
            </div>
            <div class="col-md-3 text-left">
              <div>
                <h4 style="font-weight:bold;">&nbsp;</h4>
              </div>
              <div><b>Home Telephone:</b> <?php echo $row['c_home_tel']; ?></div>
              <div><b>Work Telephone:</b> <?php echo $row['c_work_tel']; ?></div>
              <div><b>Mobile:</b> <?php echo $row['c_mobile']; ?></div>
            </div>
            <div style="clear:both;">&nbsp;</div>
          </div>
        </div>
        <div class="col-md-12" style="border-top:solid 2px #00a65a"><br/>
        </div>
        <div class="box-header">
          <h3 class="box-title"><i class="fa fa-wrench"></i> Repair Car Details</h3>
        </div>
        <div style="width:98%;height:auto;border:solid 1px #ccc;padding:10px;margin:10px;">
          <div class="col-md-3 text-center"><img style="width:150px;height:150px;" src="<?php echo $image; ?>" /></div>
          <div class="col-md-3 text-left">
            <div>
              <h4 style="font-weight:bold;"><u><?php echo $row['car_name']; ?></u></h4>
            </div>
            <div><b>Make:</b> <?php echo $row['make_name']; ?></div>
            <div><b>Model:</b> <?php echo $row['model_name']; ?></div>
            <div><b>Year:</b> <?php echo $row['model_name']; ?></div>
            <div><b>Chasis No:</b> <?php echo $row['chasis_no']; ?></div>
            <div><b>VIN#:</b> <?php echo $row['VIN']; ?></div>
          </div>
          <div class="col-md-3 text-left">
            <div>
              <h4>&nbsp;</h4>
            </div>
            <div><b>Car Registration No:</b> <?php echo $row['car_reg_no']; ?></div>
            <div><b>Added Date:</b> <?php echo $row['added_date']; ?></div>
			<div><b>Delivery Date:</b> <?php echo $row['estimate_delivery_date']; ?></div>
            <div><b>Job Status:</b> <span style="font-size:12px;" class="label label-<?php if($row['job_status'] == '0'){echo 'danger';} else {echo 'success';} ?>">
              <?php if($row['job_status'] == '0'){echo 'Processing';} else {echo 'Done';} ?>
              </span></div>
          </div>
          <div class="col-md-3 text-left">
            <div>
              <h4>&nbsp;</h4>
            </div>
            <div style="margin-bottom:5px;"><b>Delivery Status:</b> <span style="font-size:12px;" class="label label-<?php if($row['delivery_status'] == '0'){echo 'danger';} else {echo 'success';} ?>">
              <?php if($row['delivery_status'] == '0'){echo 'Pending';} else {echo 'Done';} ?>
              </span></div>
            <div><b>Repair Progress:</b>
              <div class="progress">
                <div class="progress-bar progress-bar-success bar" role="progressbar" aria-valuenow="<?php echo $row['work_status']; ?>"
  aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $row['work_status']; ?>%"></div>
                <span><?php echo $row['work_status']; ?>%</span> </div>
            </div>
          </div>
          <div style="clear:both;">&nbsp;</div>
        </div>
        <br/>
        <div class="col-md-12" style="border-top:solid 2px #00a65a"><br/>
        </div>
        <div class="box-header">
          <h3 class="box-title"><i class="fa fa-table"></i> Repair Car Estimate Details</h3>
        </div>
        <div class="box-body">
          <table id="labour_table" class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <td class="text-center"><b>Repair</b></td>
                <td class="text-center"><b>Replace</b></td>
                <td class="text-center"><b>Description</b></td>
                <td class="text-center"><b>Price</b></td>
                <td class="text-center"><b>Quantity</b></td>
                <td class="text-center"><b>Labour</b></td>
                <td class="text-center"><b>Total</b></td>
              </tr>
            </thead>
            <tbody>
              <?php foreach($estimate_data as $estimate) { ?>
              <tr>
                <td align="center"><?php if(isset($estimate->repair)){?>
                  <i class="fa fa-check-square-o"></i>
                  <?php } else {?>
                  <i class="fa fa-close"></i>
                  <?php } ?></td>
                <td align="center"><?php if(isset($estimate->replace)){?>
                  <i class="fa fa-check-square-o"></i>
                  <?php } else {?>
                  <i class="fa fa-close"></i>
                  <?php } ?></td>
                <td align="center"><?php if(!empty($estimate->discription)){echo $estimate->discription; } ?></td>
                <td align="center"><?php if(!empty($estimate->discription)){echo $estimate->price; } ?></td>
                <td align="center"><?php if(!empty($estimate->discription)){echo $estimate->quantity; } ?></td>
                <td align="center"><?php if(!empty($estimate->discription)){echo $estimate->labour; } ?></td>
                <td align="center"><?php if(!empty($estimate->discription)){echo $estimate->total; } ?></td>
              </tr>
              <?php } ?>
            </tbody>
            <tfoot>
              <tr>
                <td colspan="5"></td>
                <td align="right"><strong>Total: (<?php echo $currency;?>)</strong></td>
                <td><input style="text-align:center;font-weight:bold;" id="total_price" type="text" value="<?php echo $row['total_cost']; ?>" size="1" disabled="disabled" class="form-control allownumberonly" /></td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
    <?php } ?>
    <?php if(!$token && !empty($_POST)) { ?>
    <div align="center"><strong style="color:#FF0000;">No car found based on your selected query.</strong></div>
    <?php } ?>
  </div>
</div>
<div id="custom_email_box" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header orange_header">
        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><i class="fa fa-close"></i></span></button>
        <h3 class="modal-title"><i class="fa fa-envelope-o"></i> Send Email To Customer</h3>
      </div>
      <form method="post" enctype="multipart/form-data">
	  <div class="modal-body">
        <div class="form-group">
			<label for="txtEmailFrom">From: </label>
			<input type="text" name="txtEmailFrom" id="txtEmailFrom" class="form-control" value="<?php echo $system_info['email']; ?>" required />
		</div>
		<div class="form-group">
			<label for="txtEmailTo">To: </label>
			<input type="text" name="txtEmailTo" id="txtEmailTo" value="<?php echo $customer_email; ?>" class="form-control" required />
		</div>
		<div class="form-group">
			<label for="txtEmailSubject">Subject: </label>
			<input type="text" name="txtEmailSubject" id="txtEmailSubject" class="form-control" required />
		</div>
		<div class="form-group">
			<label for="txtEmailDetails">Details: </label>
			<textarea id="txtEmailDetails" class="form-control" name="txtEmailDetails" required></textarea>
		</div>
		<div class="form-group">
			<input type="submit" class="btn btn-success" value="Send" />
		</div>
      </div>
	  <input type="hidden" name="send_custom_email" value="send_custom_email" />
	  </form>
    </div>
    <!-- /.modal-content -->
  </div>
</div>
<?php include('../footer.php'); ?>
