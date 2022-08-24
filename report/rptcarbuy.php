<?php 
include_once('../header.php');
include_once('../helper/common.php');
include('../helper/calculation.php');
$delivery_list = array();
$token = 0;
$filter = array(
	'date'		=> '',
	'month'		=> '',
	'year'		=> '',
	'status'	=> '1',
	'condition'	=> 'new',
	'payment'	=> ''
);
if(!empty($_POST)) {
	$filter = array(
		'date'		=> '',
		'month'		=> '',
		'year'		=> '',
		'status'	=> $_POST['ddlCarStatus'],
		'condition'	=> $_POST['ddlCondition'],
		'payment'	=> $_POST['ddlPayment']
	);
	if(!empty($_POST['txtBuyDate'])) {
		$filter['date'] = $_POST['txtBuyDate'];
	}
	if(!empty($_POST['ddlMonth'])) {
		$filter['month'] = $_POST['ddlMonth'];
	}
	if(!empty($_POST['ddlYear'])) {
		$filter['year'] = $_POST['ddlYear'];
	}
	$results = $wms->getAllBuyCarReportList($link, $filter);
	$token = 1;
}
$wmscalc = new wms_calculation();
?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1><i class="fa fa-line-chart"></i> Car Buy Reports </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Car Buy Report</li>
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
            <h3 class="box-title"><i class="fa fa-search"></i> Find Buy Car</h3>
          </div>
          <div class="form-group  col-md-12" style="color:red; font-weight:bold;">** Select Buy date or month and year together or month or year.</div>
          <div class="form-group col-md-12">
            <label for="txtBuyDate">Buy Date :</label>
            <input type="text" name="txtBuyDate" value="<?php echo !empty($filter['date']) ? $filter['date'] : ''; ?>" id="txtBuyDate" class="form-control datepicker" />
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
            <label for="ddlYear">Select Year :</label>
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
          <div class="form-group col-md-6">
            <label for="ddlCarStatus">Car Status :</label>
            <select class="form-control" name="ddlCarStatus" id="ddlCarStatus">
              <option <?php echo $filter['status'] == '1' ? 'selected' : ''; ?> value='1'>Sold</option>
              <option <?php echo $filter['status'] == '0' ? 'selected' : ''; ?> value='0'>Available</option>
            </select>
          </div>
          <div class="form-group col-md-6">
            <label for="ddlCondition">Car Condition :</label>
            <select class="form-control" name="ddlCondition" id="ddlCondition">
              <option <?php echo $filter['condition'] == 'new' ? 'selected' : ''; ?> value='new'>New</option>
              <option <?php echo $filter['condition'] == 'old' ? 'selected' : ''; ?> value='old'>Old</option>
			  <option <?php echo $filter['condition'] == 'both' ? 'selected' : ''; ?> value='both'>Both</option>
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
  <?php if(!empty($results)) { ?>
  <?php
  $buy = 0.00;
  $due = 0.00;
  $paid = 0.00;
  foreach($results as $row) {
  	$buy += (float)$row['buy_price'];
	$due += (float)$row['buy_price'] - (float)$row['buy_given_amount'];
	$paid += (float)$row['buy_given_amount'];
  }
  ?>
  <div class="col-xs-12 state-overview">
    <div class="box box-success">
      <div class="box-body">
        <div class="col-lg-4 col-sm-4">
          <section class="panel">
            <div class="symbol terques"> <i class="fa fa-line-chart" data-original-title="" title=""></i> </div>
            <div class="value">
              <h1 class=" count2"><?php echo $currency.number_format($buy, 2);?></h1>
              <p>Total Price</p>
            </div>
          </section>
        </div>
        <div class="col-lg-4 col-sm-4">
          <section class="panel">
            <div class="symbol red"> <i class="fa fa-bar-chart-o" data-original-title="" title=""></i> </div>
            <div class="value">
              <h1 class=" count2"><?php echo $currency.number_format($due, 2);?></h1>
              <p>Due Amount</p>
            </div>
          </section>
        </div>
        <div class="col-lg-4 col-sm-4">
          <section class="panel">
            <div class="symbol purple"> <i class="fa fa-area-chart" data-original-title="" title=""></i> </div>
            <div class="value">
              <h1 class=" count2"><?php echo $currency.number_format($paid, 2);?></h1>
              <p>Paid Amount</p>
            </div>
          </section>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xs-12">
    <div class="box box-success">
      <div class="box-header">
        <h3 class="box-title">Car Buy List</h3>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <table class="table sakotable table-bordered table-striped dt-responsive">
          <thead>
            <tr>
              <th>Image</th>
              <th>Car Name</th>
              <th>Condition</th>
              <th>Car Status</th>
              <th>Payment</th>
              <th>Buy Date</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
				foreach($results as $row) {				
					$image = WEB_URL . 'img/no_image.jpg';
					if(file_exists(ROOT_PATH . '/img/upload/' . $row['car_image']) && $row['car_image'] != ''){
						$image = WEB_URL . 'img/upload/' . $row['car_image']; //car image
					}
				?>
            <tr>
              <td><img class="img_size" style="width:50px;height:50px;" src="<?php echo $image;  ?>" /></td>
              <td><?php echo $row['car_name']; ?></td>
              <td align="center"><span class="label label-success" style="text-transform:uppercase;"><?php echo $row['car_condition']; ?></span></td>
              <td align="center"><?php if($row['car_status'] == '0'){echo '<span class="label label-success">Available</span>';} else {echo '<span class="label label-danger">Sold</span>';} ?></td>
              <td><span class="label label-danger"><?php echo $currency; ?><?php echo $wmscalc->getResultFromTwoValues($row['buy_price'],$row['buy_given_amount'],'-'); ?></span></td>
              <td><?php echo $wms->mySqlToDatePicker($row['buy_date']); ?></td>
              <td><a class="btn btn-success" data-toggle="tooltip" href="javascript:;" onClick="$('#nurse_view_<?php echo $row['buycar_id']; ?>').modal('show');" data-original-title="View"><i class="fa fa-eye"></i></a> <a class="btn btn-primary" data-toggle="tooltip" href="<?php echo WEB_URL;?>carstock/buycar.php?id=<?php echo $row['buycar_id']; ?>" data-original-title="Edit"><i class="fa fa-pencil"></i></a> <a class="btn btn-danger" data-toggle="tooltip" onClick="deleteCustomer(<?php echo $row['buycar_id']; ?>);" href="javascript:;" data-original-title="Delete"><i class="fa fa-trash-o"></i></a>
                <div id="nurse_view_<?php echo $row['buycar_id']; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header orange_header">
                        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><i class="fa fa-close"></i></span></button>
                        <h3 class="modal-title">Car Details</h3>
                      </div>
                      <div class="modal-body model_view" align="center">&nbsp;
                        <div><img class="photo_img_round" style="width:100px;height:100px;" src="<?php echo $image;  ?>" /></div>
                        <div class="model_title"><?php echo $row['car_name']; ?></div>
                      </div>
                      <div class="modal-body">
                        <div class="row">
                          <div class="col-xs-6">
                            <h3 style="text-decoration:underline;">Owner Infromation</h3>
                            <b>Owner Name :</b> <?php echo $row['owner_name']; ?><br/>
                            <b>Owner Mobile :</b> <?php echo $row['owner_mobile']; ?><br/>
                            <b>Email :</b> <?php echo $row['owner_email']; ?><br/>
                            <b>NID :</b> <?php echo $row['nid']; ?><br/>
                            <b>Company Name :</b> <?php echo $row['company_name']; ?><br/>
                            <b>Company Trade Lincence :</b> <?php echo $row['ctl']; ?><br/>
                            <b>Address :</b> <?php echo $row['owner_address']; ?><br/>
                          </div>
                          <div class="col-xs-6">
                            <h3 style="text-decoration:underline;">Car Infromation</h3>
                            <b>Car Name :</b> <?php echo $row['car_name']; ?><br/>
                            <b>Condition :</b> <?php echo $row['car_condition']; ?><br/>
                            <b>Color :</b> <?php echo $row['color_name']; ?><br/>
                            <b>No of Door</b> <?php echo $row['door_name']; ?><br/>
                            <b>Make :</b> <?php echo $row['make_name']; ?><br/>
                            <b>Model :</b> <?php echo $row['model_name']; ?><br/>
                            <b>Year :</b> <?php echo $row['year_name']; ?><br/>
                            <b>Registration Number :</b> <?php echo $row['car_reg_no']; ?><br/>
                            <b>Registration Date :</b> <?php echo $row['car_reg_date']; ?><br/>
                            <b>Chasis No :</b> <?php echo $row['car_chasis_no']; ?><br/>
                            <b>Engine Name :</b> <?php echo $row['car_engine_name']; ?><br/>
                            <b>Car Totalmileage :</b> <?php echo $row['car_totalmileage']; ?><br/>
                            <b>Car Seat :</b> <?php echo $row['car_sit']; ?><br/>
                          </div>
                          <div class="col-xs-12">
                            <h3 style="text-decoration:underline;">Buying Infromation</h3>
                            <b>Buy Price :</b> <?php echo $currency; ?><?php echo $row['buy_price']; ?><br/>
                            <b>Sell Price :</b> <?php echo $currency; ?><?php echo $row['asking_price']; ?><br/>
                            <b>Given Price :</b> <?php echo $currency; ?><?php echo $row['buy_given_amount']; ?><br/>
                            <b>Due Amount :</b> <span class="label label-danger"><?php echo $currency; ?><?php echo $wmscalc->getResultFromTwoValues($row['buy_price'],$row['buy_given_amount'],'-'); ?></span><br/>
                            <b>Buy Date :</b> <?php echo $row['buy_date']; ?><br/>
                            <b>Note :</b> <?php echo $row['buy_note']; ?><br/>
                            <b>Car Status :</b> <span class="label label-success">
                            <?php if($row['car_status'] == '0'){echo 'Available';} else {echo 'Sold';} ?>
                            </span> <br/>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div></td>
            </tr>
           <?php } $wms->close_db_connection($link); ?>
          </tbody>
        </table>
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
  <?php } ?>
  <?php if(empty($results) && $token=='1') {?>
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
