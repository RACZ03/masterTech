<?php 
include_once('../mech_panel/header.php');
include_once('../helper/common.php');
include('../helper/calculation.php');
$delivery_list = array();
$token = 0;
$filter = array(
	'mid'		=> '',
	'date'		=> '',
	'month'		=> '',
	'year'		=> '',
	'payment'	=> ''
);
if(!empty($_POST)) {
	$filter = array(
		'date'		=> '',
		'month'		=> '',
		'year'		=> '',
		'payment'	=> $_POST['ddlPayment']
	);
	if(!empty($_POST['ddlMechanicslist'])) {
		$filter['mid'] = $_POST['ddlMechanicslist'];
	}
	if(!empty($_POST['txtBuyDate'])) {
		$filter['date'] = $_POST['txtBuyDate'];
	}
	if(!empty($_POST['ddlMonth'])) {
		$filter['month'] = $_POST['ddlMonth'];
	}
	if(!empty($_POST['ddlYear'])) {
		$filter['year'] = $_POST['ddlYear'];
	}
	$results = $wms->getMechanicesSalaryReportList($link, $filter);
	$token = 1;
}
$wmscalc = new wms_calculation();
?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1><i class="fa fa-line-chart"></i> Salary Reports </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active"> Mechanics Salary</li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
<!-- Full Width boxes (Stat box) -->
<div class="row">
  <div class="col-xs-12">
    <div align="right" style="margin-bottom:1%;"> <a class="btn btn-warning" data-toggle="tooltip" href="<?php echo WEB_URL; ?>mech_panel/mech_dashboard.php" data-original-title="Dashboard"><i class="fa fa-dashboard"></i></a> </div>
	<form id="frmcarstock" method="post" enctype="multipart/form-data">
      <div class="box box-success" id="box_model">
        <div class="box-body">
          <div class="box-header">
            <h3 class="box-title"><i class="fa fa-search"></i> Filter Salary</h3>
          </div>
          <div class="form-group  col-md-12" style="color:red; font-weight:bold;">** Select Salary date or month and year together or month or year.</div>
          <div class="form-group col-md-4">
            <label for="ddlPayment">Salary :</label>
            <select class="form-control" name="ddlPayment" id="ddlPayment">
              <option <?php echo $filter['payment'] == '' ? 'selected' : ''; ?> value=''>--select--</option>
              <option <?php echo $filter['payment'] == 'due' ? 'selected' : ''; ?> value='due'>Due</option>
              <option <?php echo $filter['payment'] == 'paid' ? 'selected' : ''; ?> value='paid'>Paid</option>
            </select>
          </div>
          <div class="form-group col-md-4">
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
          <div class="form-group col-md-4">
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
          <div class="form-group col-md-12">
            <button type="submit" class="btn btn-success btn-large btn-block"><b><i class="fa fa-filter"></i> SEARCH</b></button>
          </div>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
	  <input type="hidden" name="ddlMechanicslist" value="<?php echo $_SESSION['objMech']['user_id']; ?>" />
    </form>
  </div>
  <?php if(!empty($results)) { ?>
  <?php
  $buy = 0.00;
  $due = 0.00;
  $paid = 0.00;
  foreach($results as $row) {
  	$buy += (float)$row['total'];
	$due += (float)$row['due_amount'];
	$paid += (float)$row['paid_amount'];
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
              <p>Total Amount</p>
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
        <h3 class="box-title">Mechanics Salary List</h3>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <table class="table sakotable table-bordered table-striped dt-responsive">
          <thead>
            <tr>
              <th>Name</th>
              <th>Salary Monthly/Hourly</th>
              <th>Hour/Month</th>
              <th>Total</th>
              <th>Paid</th>
              <th>Due</th>
              <th>Salary Date</th>
              <th>Details</th>
            </tr>
          </thead>
          <tbody>
            <?php
				foreach($results as $row) {
					$image = WEB_URL . 'img/no_image.jpg';	
					if(file_exists(ROOT_PATH . '/img/employee/' . $row['m_image']) && $row['m_image'] != ''){
						$image = WEB_URL . 'img/employee/' . $row['m_image'];
					}
				
				?>
            <tr>
              <td><?php echo $row['m_name']; ?></td>
              <td><?php echo $currency.$row['fix_salary']; ?></td>
              <td><?php echo $row['total_time']; ?></td>
              <td><span class="label label-success"><?php echo $currency.$row['total']; ?></span></td>
              <td><span class="label label-info"><?php echo $currency.$row['paid_amount']; ?></span></td>
              <td><span class="label label-danger"><?php echo $currency.$row['due_amount']; ?></span></td>
              <td><span class="label label-warning"><?php echo $wms->mySqlToDatePicker($row['sl_date']); ?></span></td>
              <td><a class="btn btn-success" data-toggle="tooltip" href="javascript:;" onClick="$('#nurse_view_<?php echo $row['m_salary_id']; ?>').modal('show');" data-original-title="View"><i class="fa fa-eye"></i></a>
                <div id="nurse_view_<?php echo $row['m_salary_id']; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header orange_header">
                        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><i class="fa fa-close"></i></span></button>
                        <h3 class="modal-title">Mechanics Details</h3>
                      </div>
                      <div class="modal-body model_view" align="center">&nbsp;
                        <div><img class="photo_img_round" style="width:200px;height:200px;" src="<?php echo $image;  ?>" /></div>
                        <div class="model_title"><?php echo $row['m_name']; ?></div>
                      </div>
                      <div class="modal-body">
                        <h3 style="text-decoration:underline;">Details Infromation</h3>
                        <div class="row">
                          <div class="col-xs-12"> <b>Name :</b> <?php echo $row['m_name']; ?><br/>
                            <b>Salary :</b> <?php echo $row['fix_salary']; ?><br/>
                            <b>Time :</b> <?php echo $row['total_time']; ?><br/>
                            <b>Total :</b> <?php echo $currency.$row['total']; ?><br/>
                            <b>Paid :</b> <?php echo $currency.$row['paid_amount']; ?><br/>
                            <b>Due :</b> <?php echo $currency.$row['due_amount']; ?><br/>
                            <b>Paid Month :</b> <span class="label label-info"><?php echo $wms->getMonthValueToMonthName($row['month_id']); ?></span><br/>
							<b>Phone Number :</b> <?php echo $row['m_phone_number']; ?><br/>
							<b>Paid Year :</b> <span class="label label-danger"><?php echo $row['year_id']; ?></span><br/>
                            <b>Email :</b> <?php echo $row['m_email']; ?><br/>
                            <b>Salery Date :</b> <span class="label label-success"><?php echo $wms->mySqlToDatePicker($row['sl_date']); ?></span> </div>
                        </div>
                      </div>
                    </div>
                    <!-- /.modal-content -->
                  </div>
                </div></td>
            </tr>
            <?php } ?>
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
<?php include('../mech_panel/footer.php'); ?>
