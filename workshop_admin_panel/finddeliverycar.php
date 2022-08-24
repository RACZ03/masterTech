<?php 
include_once('../workshop_admin_panel/header.php');
$invoice_no = '';
$sql = '';
$token = false;
$customer_id = 0;
$row = array();
$estimate_data = array();

/************************ Insert Query ***************************/
if (!empty($_POST)) {
	$row = $wms->getEstimateAndCarAndCustomerDetails($link, $_POST['txtInvoiceNo']);
	$invoice_no = $_POST['txtInvoiceNo'];
}
?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1><i class="fa fa-car"></i> Find Car For Delivery </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Find Delivery Car</li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
<!-- Full Width boxes (Stat box) -->
<div class="row">
  <div class="col-md-12">
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
    <div class="box box-success" id="box_model">
      <div class="box-body">
        <div id="eloader" style="position:absolute;margin-left:400px;display:none;"><img src="<?php echo WEB_URL ?>/img/eloader.gif" /></div>
        <div class="form-group col-md-12" style="padding-top:10px;">
          <div class="pull-left">
            <label class="label label-success" style="font-size:15px;">INVOICE - <?php echo $_POST['txtInvoiceNo']; ?></label>
            <label class="label label-danger" style="font-size:15px;">Delivery Done ? :
            <input style="padding-top:2px;" type="checkbox" name="chkdeliver" <?php if($row['delivery_status'] == '1'){echo 'checked'; }?> id="chkdeliver">
            </label>
            <div> <br/>
              <label>Delivery Date:</label>
              <input type="text" id="txtDeliveryDate" value="<?php echo $row['delivery_done_date'] != '0000-00-00' ? $wms->mySqlToDatePicker($row['delivery_done_date']) : date('d/m/Y');?>" class="form-control datepicker" />
            </div>
          </div>
          <div class="pull-right">
            <button type="button" onclick="saveWorkshopEstimateData(<?php echo $row['customer_id']; ?>,<?php echo $row['car_id']; ?>,'<?php echo $row['estimate_no']; ?>','<?php echo WEB_URL ?>');" class="btn btn-info btnsp"><i class="fa fa-print fa-2x"></i><br/>
            Update & Generate Invoice</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank" href="<?php echo WEB_URL;?>workshop_admin_panel/invoice.php?invoice_id=<?php echo $_POST['txtInvoiceNo']; ?>" class="btn btn-warning btnsp"><i class="fa fa-eye fa-2x"></i><br/>
            Invoice Preview</a> </div>
        </div>
      </div>
    </div>
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
        <div class="form-group col-md-12">
          <?php
				$image_cust = WEB_URL . 'img/no_image.jpg';
				if(file_exists(ROOT_PATH . '/img/upload/' . $row['image']) && $row['image'] != ''){
					$image_cust = WEB_URL . 'img/upload/' . $row['image']; //car image
				}
		  ?>
          <div style="width:98%;height:auto;border:solid 1px #ccc;padding:10px;margin:10px;">
            <div class="col-md-3"><img class="img-thumbnail" style="width:150px;height:150px;" src="<?php echo $image_cust; ?>" /></div>
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
          <?php //} ?>
        </div>
        <div class="col-md-12" style="border-top:solid 2px #00a65a"><br/>
        </div>
        <div class="box-header">
          <h3 class="box-title"><i class="fa fa-wrench"></i> Repair Car Details</h3>
        </div>
        <div style="width:98%;height:auto;border:solid 1px #ccc;padding:10px;margin:10px;">
          <div class="col-md-3"><img class="img-thumbnail" style="width:150px;height:150px;" src="<?php echo $image; ?>" /></div>
          <div class="col-md-3 text-left">
            <div>
              <h4 style="font-weight:bold;"><u><?php echo $row['car_name']; ?></u></h4>
            </div>
            <div><b>Make:</b> <?php echo $row['make_name']; ?></div>
            <div><b>Model:</b> <?php echo $row['model_name']; ?></div>
            <div><b>Year:</b> <?php echo $row['year_name']; ?></div>
            <div><b>Chasis No:</b> <?php echo $row['chasis_no']; ?></div>
            <div><b>VIN#:</b> <?php echo $row['VIN']; ?></div>
          </div>
          <div class="col-md-3 text-left">
            <div>
              <h4>&nbsp;</h4>
            </div>
            <div><b>Car Registration No:</b> <?php echo $row['car_reg_no']; ?></div>
            <div><b>Added Date:</b> <?php echo $row['added_date']; ?></div>
            <div><b>Delivery Date:</b> <label style="color:red;font-weight:bold;"><?php echo $row['estimate_delivery_date']; ?></label></div>
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
				<td class="text-center"><b>Warranty</b></td>
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
                <td align="center"><?php if(!empty($estimate->price)){echo $estimate->price; } ?></td>
                <td align="center"><?php if(!empty($estimate->quantity)){echo $estimate->quantity; } ?></td>
                <td align="center"><?php if(!empty($estimate->labour)){echo $estimate->labour; } ?></td>
				<td align="center"><?php if(!empty($estimate->warranty)){echo str_replace("-"," ",$estimate->warranty); } ?></td>
                <td align="center"><?php if(!empty($estimate->total)){echo $estimate->total; } ?></td>
              </tr>
              <?php } ?>
            </tbody>
            <tfoot>
              <tr>
                <td colspan="6"></td>
                <td align="right"><strong>Total (<?php echo $currency; ?>):</strong></td>
                <td><input style="text-align:center;font-weight:bold;" id="total_price" type="text" value="<?php echo $row['total_cost']; ?>" size="1" disabled="disabled" class="form-control allownumberonly" /></td>
              </tr>
              <tr>
                <td colspan="6"></td>
                <td align="right"><strong>Discount (%):</strong></td>
                <td><input style="text-align:center;font-weight:bold;" id="total_discount" value="<?php echo $row['discount']; ?>" type="text" size="1" class="form-control allownumberonly" /></td>
              </tr>
              <tr>
                <td colspan="6"></td>
                <td align="right"><strong>Balance Paid (<?php echo $currency; ?>):</strong></td>
                <td><input style="text-align:center;font-weight:bold;" id="total_paid" type="text" value="<?php echo $row['payment_done']; ?>" size="1" class="form-control allownumberonly" /></td>
              </tr>
              <tr>
                <td colspan="6"></td>
                <td align="right"><strong>Balance Due (<?php echo $currency; ?>):</strong></td>
                <td><input style="text-align:center;font-weight:bold;" id="total_due" type="text" value="<?php echo $row['payment_due']; ?>" size="1" disabled="disabled" class="form-control allownumberonly" /></td>
              </tr>
              <tr>
                <td colspan="6"></td>
                <td align="right"><strong>Grand Total (<?php echo $currency; ?>):</strong></td>
                <td><input style="text-align:center;font-weight:bold;" id="total_grand_total" type="text" value="<?php echo $row['grand_total']; ?>" size="1" disabled="disabled" class="form-control allownumberonly" /></td>
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
<?php include('../footer.php'); ?>
