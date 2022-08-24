<?php 
include_once('../header.php');
include_once('../helper/common.php');
$delivery_list = array();
$token = 0;
$filter = array(
	'date'		=> '',
	'month'		=> '',
	'year'		=> '',
	'status'	=> '',
	'payment'	=> ''
);
if(!empty($_POST)) {
	$filter = array(
		'date'		=> '',
		'month'		=> '',
		'year'		=> '',
		'status'	=> $_POST['ddlRepairStatus'],
		'payment'	=> $_POST['ddlPayment']
	);
	if(!empty($_POST['txtDeliveryDate'])) {
		$filter['date'] = $_POST['txtDeliveryDate'];
	}
	if(!empty($_POST['ddlMonth'])) {
		$filter['month'] = $_POST['ddlMonth'];
	}
	if(!empty($_POST['ddlYear'])) {
		$filter['year'] = $_POST['ddlYear'];
	}
	$delivery_list = $wms->getRepairCarDateForReport($link, $filter);
	$token = 1;
}
?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1><i class="fa fa-line-chart"></i> Car Repair Reports </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Car Repair Report</li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
<!-- Full Width boxes (Stat box) -->
<div class="row">
  <div class="col-xs-12">
    <form id="frmcarstock" method="post" enctype="multipart/form-data">
      <div class="box box-success" id="box_model">
        <div class="box-body">
          <div class="box-header">
            <h3 class="box-title"><i class="fa fa-search"></i> Find Repair Car</h3>
          </div>
          <div class="form-group  col-md-12" style="color:red; font-weight:bold;">** Select delivery date or month and year together or month or year</div>
		  <div class="form-group col-md-6">
            <label for="txtCondition">Delivery Date :</label>
            <input type="text" name="txtDeliveryDate" value="<?php echo !empty($filter['date']) ? $filter['date'] : ''; ?>" id="txtDeliveryDate" class="form-control datepicker" />
          </div>
		  <div class="form-group col-md-6">
            <label for="ddlRepairStatus">Repair Status :</label>
            <select class="form-control" name="ddlRepairStatus" id="ddlRepairStatus">
			  <option <?php echo $filter['status'] == '' ? 'selected' : ''; ?> value=''>Both</option>
			  <option <?php echo $filter['status'] == '1' ? 'selected' : ''; ?> value='1'>Delivered</option>
			  <option <?php echo $filter['status'] == '0' ? 'selected' : ''; ?> value='0'>Pending</option>
            </select>
          </div>
		  <div class="form-group col-md-6">
            <label for="ddlMonth">Select Month :</label>
            <select class="form-control" name="ddlMonth" id="ddlMonth">
              <option <?php echo $filter['month'] == '' ? 'selected' : ''; ?> value=''>--Select Month--</option>
			  <option <?php echo $filter['month'] == '1' ? 'selected' : ''; ?> value='1'>January</option>
              <option <?php echo $filter['month'] == '2' ? 'selected' : ''; ?> value='2'>February</option>
			  <option <?php echo $filter['month'] == '3' ? 'selected' : ''; ?> value='3'>March</option>
			  <option <?php echo $filter['month'] == '4' ? 'selected' : ''; ?> value='4'>April</option>
			  <option <?php echo $filter['month'] == '5' ? 'selected' : ''; ?> value='5'>May</option>
			  <option <?php echo $filter['month'] == '6' ? 'selected' : ''; ?> value='6'>June</option>
			  <option <?php echo $filter['month'] == '7' ? 'selected' : ''; ?> value='7'>July</option>
			  <option <?php echo $filter['month'] == '8' ? 'selected' : ''; ?> value='8'>August</option>
			  <option <?php echo $filter['month'] == '9' ? 'selected' : ''; ?> value='9'>September</option>
			  <option <?php echo $filter['month'] == '10' ? 'selected' : ''; ?> value='10'>October</option>
			  <option <?php echo $filter['month'] == '11' ? 'selected' : ''; ?> value='11'>November</option>
			  <option <?php echo $filter['month'] == '12' ? 'selected' : ''; ?> value='12'>December</option>
            </select>
          </div>
		  <div class="form-group col-md-6">
            <label for="txtCondition">Select Year :</label>
            <select class="form-control" name="ddlYear" id="ddlYear">
              <option <?php echo $filter['year'] == '' ? 'selected' : ''; ?> value=''>--Select Year--</option>
              <?php for($i=2000;$i<=date('Y');$i++){
			  	if($filter['year'] == $i) {
					echo '<option selected value="'.$i.'">'.$i.'</option>';
				} else {
					echo '<option value="'.$i.'">'.$i.'</option>';
				}
			    }?>
            </select>
          </div>
		  <div class="form-group col-md-12">
            <label for="ddlPayment">Payment :</label>
            <select class="form-control" name="ddlPayment" id="ddlPayment">
			  <option <?php echo $filter['payment'] == '' ? 'selected' : ''; ?> value=''>--select--</option>
			  <option <?php echo $filter['payment'] == 'due' ? 'selected' : ''; ?> value='due'>Due</option>
			  <option <?php echo $filter['payment'] == 'paid' ? 'selected' : ''; ?> value='paid'>Paid</option>
            </select>
          </div>
          <div class="form-group col-md-12">
            <button type="submit" class="btn btn-success btn-large btn-block"><b><i class="fa fa-filter"></i> SEARCH</b></button>
          </div>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
    </form>
  </div>
  <?php if(!empty($delivery_list)) { ?>
  <?php
  $car_repair = count($delivery_list);
  $delivery_done = 0;
  $delivery_pending = 0;
  $total_cost = 0.00;
  $total_pending = 0.00;
  $total_paid = 0.00;
  foreach($delivery_list as $rdata) {
  	if($rdata['delivery_status']=='1'){
		$delivery_done += (int)1;
	}
	if($rdata['delivery_status']=='0'){
		$delivery_pending += (int)1;
	}
	$total_cost += (float)$rdata['total_cost'];
	$total_pending += (float)$rdata['payment_due'];
	$total_paid += (float)$rdata['payment_done'];
  }
  ?>
  <div class="col-xs-12 state-overview">
    <div class="box box-success">
      <div class="box-body">
        <div class="col-lg-4 col-sm-4">
          <section class="panel">
            <div class="symbol terques"> <i class="fa fa-car" data-original-title="" title=""></i> </div>
            <div class="value">
              <h1 class=" count2"><?php echo $car_repair; ?></h1>
              <p>Total Car Repair</p>
            </div>
          </section>
        </div>
        <div class="col-lg-4 col-sm-4">
          <section class="panel">
            <div class="symbol primary"> <i class="fa fa-car" data-original-title="" title=""></i> </div>
            <div class="value">
              <h1 class=" count2"><?php echo $delivery_done; ?></h1>
              <p>Total Delivered</p>
            </div>
          </section>
        </div>
		<div class="col-lg-4 col-sm-4">
          <section class="panel">
            <div class="symbol red"> <i class="fa fa-car" data-original-title="" title=""></i> </div>
            <div class="value">
              <h1 class=" count2"><?php echo $delivery_pending; ?></h1>
              <p>Pending Delivery</p>
            </div>
          </section>
        </div>
        <div class="col-lg-4 col-sm-4">
          <section class="panel">
            <div class="symbol purple"> <i class="fa fa-usd" data-original-title="" title=""></i> </div>
            <div class="value">
              <h1 class=" count2"><?php echo $currency.number_format($total_cost,2); ?></h1>
              <p>Total Amount</p>
            </div>
          </section>
        </div>
		<div class="col-lg-4 col-sm-4">
          <section class="panel">
            <div class="symbol yellow"> <i class="fa fa-usd" data-original-title="" title=""></i> </div>
            <div class="value">
              <h1 class=" count2"><?php echo $currency.number_format($total_pending,2); ?></h1>
              <p>Total Due Amount</p>
            </div>
          </section>
        </div>
		<div class="col-lg-4 col-sm-4">
          <section class="panel">
            <div class="symbol terques"> <i class="fa fa-usd" data-original-title="" title=""></i> </div>
            <div class="value">
              <h1 class=" count2"><?php echo $currency.number_format($total_paid,2); ?></h1>
              <p>Total Paid</p>
            </div>
          </section>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xs-12">
    <div class="box box-success">
      <div class="box-header">
        <h3 class="box-title">Delivery List</h3>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <table class="table sakotable table-bordered table-striped dt-responsive">
          <thead>
            <tr>
              <th>Repair Car ID</th>
              <th>Estimate No</th>
              <th>Delivered</th>
			  <th>Due Amount</th>
			  <th>Paid Amount</th>
			  <th>Total Amount</th>
			  <th>Delivery Date</th>
              <th>&nbsp;</th>
            </tr>
          </thead>
          <tbody>
            <?php
				if(!empty($delivery_list)) {
				foreach($delivery_list as $data) {
					$image = WEB_URL . WEB_URL.'img/no_image.jpg';	
					$image_car = WEB_URL . WEB_URL.'img/no_image.jpg';
					if(file_exists(ROOT_PATH . '/img/upload/' . $data['customer_image']) && $data['customer_image'] != ''){
						$image = WEB_URL . 'img/upload/' . $data['customer_image'];
					}
					if(file_exists(ROOT_PATH . '/img/upload/' . $data['car_image']) && $data['car_image'] != ''){
						$image_car = WEB_URL . 'img/upload/' . $data['car_image'];
					}
				
				?>
            <tr>
              <td><label class="label label-success"><?php echo $data['repair_car_id']; ?></label></td>
              <td><label class="label label-success"><?php echo $data['estimate_no']; ?></label></td>
              <td><?php echo ($data['delivery_status'] == '1') ? '<label class="label label-success">Delivered</label>' : '<label class="label label-danger">Pending</label>'; ?></td>
			  <td><label class="label label-warning"><?php echo $currency. $data['payment_due']; ?></label></td>
			  <td><label class="label label-info"><?php echo $currency.$data['payment_done']; ?></label></td>
			  <td><label class="label label-primary"><?php echo $currency.$data['total_cost']; ?></label></td>
              <td><?php echo $wms->mySqlToDatePicker($data['delivery_done_date']); ?></td>
              <td><a class="btn btn-success" data-toggle="tooltip" href="javascript:;" onClick="$('#estimate_view_<?php echo $data['car_id']; ?>').modal('show');" data-original-title="View"><i class="fa fa-eye"></i></a> <a data-toggle="tooltip" data-original-title="Edit Estimate" href="<?php echo WEB_URL; ?>estimate/estimate_form.php?carid=<?php echo $data['car_id']; ?>&customer_id=<?php echo $data['customer_id']; ?>&estimate_no=<?php echo $data['estimate_no']; ?>" class="btn btn-warning"><i class="fa fa-edit"></i> </a>
                <div id="estimate_view_<?php echo $data['car_id']; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header green_header">
                        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><i class="fa fa-close"></i></span></button>
                        <h3 class="modal-title"><i class="fa fa-user"></i> <b>Customer Details</b></h3>
                      </div>
                      <div class="modal-body">
                        <div class="col-sm-4"><img class="img-thumbnail" style="width:100px;height:100px;" src="<?php echo $image;  ?>" /></div>
                        <div class="col-sm-8">
                          <div><b>Name:</b> <?php echo $data['c_name']; ?></div>
                          <div><b>Email:</b> <?php echo $data['c_email']; ?></div>
                          <div><b>Phone:</b> <?php echo $data['c_mobile']; ?></div>
                        </div>
                        <div style="clear:both;"></div>
                      </div>
                      <div class="modal-header orange_header">
                        <h3 class="modal-title"><i class="fa fa-car"></i> <b>Car Details</b></h3>
                      </div>
                      <div class="modal-body">
                        <div class="col-sm-4"><img class="img-thumbnail" style="width:100px;height:100px;" src="<?php echo $image_car;  ?>" /></div>
                        <div class="col-sm-8">
                          <div><b>Name:</b> <?php echo $data['car_name']; ?></div>
                          <div><b>Make:</b> <?php echo $data['make_name']; ?></div>
                          <div><b>Model:</b> <?php echo $data['model_name']; ?></div>
                          <div><b>Year:</b> <?php echo $data['year_name']; ?></div>
                          <div><b>Chasis No:</b> <?php echo $data['chasis_no']; ?></div>
                          <div><b>Car Reg No:</b> <?php echo $data['car_reg_no']; ?></div>
                          <div><b>VIN:</b> <?php echo $data['VIN']; ?></div>
                          <div><b>Car Added Date:</b> <?php echo date('d/m/Y', strtotime($data['added_date'])); ?></div>
                        </div>
                        <div style="clear:both;"></div>
                      </div>
                    </div>
                  </div>
                  <!-- /.modal-content -->
                </div></td>
            </tr>
			<?php } } $wms->close_db_connection($link); ?>
          </tbody>
        </table>
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
  <?php } ?>
  
  <?php if(empty($delivery_list) && $token=='1') {?>
  <div class="col-xs-12">
    <div class="box box-success">
      <!-- /.box-header -->
      <div class="box-body empty_record">No record found.</div>
   </div>
  </div>
  <?php } ?>
  <!-- /.col -->
</div>
<!-- /.row -->
<?php include('../footer.php'); ?>
