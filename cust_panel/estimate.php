<?php 
include_once('../cust_panel/header.php');
$repair_id = '';
$car_id = '';

/************************ Insert Query ***************************/
if (isset($_GET['rid']) && !empty($_GET['rid'])) {
	$repair_id = $_GET['rid'];
}
?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1><i class="fa fa-car"></i> Car Estimate History </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Car Estimate</li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
<!-- Full Width boxes (Stat box) -->
<div class="row">
  <div class="col-md-12">
    <!-- <div align="right" style="margin-bottom:1%;"><a class="btn btn-warning" data-toggle="tooltip" href="<?php echo WEB_URL; ?>cust_panel/cust_dashboard.php" data-original-title="Dashboard"><i class="fa fa-dashboard"></i></a> </div> -->
	<div class="box box-success" id="box_model">
      <div class="box-body">
        <div class="box-header">
          <h3 class="box-title"><i class="fa fa-search"></i> Find Car for Estimate</h3>
        </div>
        <div class="form-group col-md-12">
          <label for="txtInvoiceNo">Select Repair CAR ID :</label>
          <select onchange="load_history(this.value);" class="form-control" name="ddlRepairCarId">
            <option value="">--Select ID--</option>
            <?php
				$resultx = $wms->getCustomerRepairCarList($link, $_SESSION['objCust']['user_id']);
				foreach($resultx as $rowx) {
					if($rowx['repair_car_id'] == $repair_id ) {
						echo '<option selected value="'.$rowx['repair_car_id'].'">'.$rowx['car_name']. ' ('.$rowx['repair_car_id'].')'.'</option>';
					} else {
						echo '<option value="'.$rowx['repair_car_id'].'">'.$rowx['car_name']. ' ('.$rowx['repair_car_id'].')'.'</option>';
					}
				}
				?>
          </select>
        </div>
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
    <?php if(!empty($repair_id)) { ?>
    <div class="box box-success" id="box_model">
      <div class="box-body">
        <div class="box-header">
          <h3 class="box-title"><i class="fa fa-car"></i> Car Details</h3>
          <?php
				$row = $wms->getCustomerRepairCarDetails($link, $_SESSION['objCust']['user_id'], $repair_id);
				$image = WEB_URL . 'img/no_image.jpg';
				if(file_exists(ROOT_PATH . '/img/upload/' . $row['car_image']) && $row['image'] != ''){
					$image = WEB_URL . 'img/upload/' . $row['car_image']; //car image
				}
				$car_id = $row['car_id'];
				?>
          <div style="height:auto;border:solid 1px #ccc;padding:10px;margin:10px 0px 0px 0px;">
            <div class="col-md-3"><img class='img-thumbnail' style="width:150px;height:150px;" src="<?php echo $image; ?>" /></div>
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
            </div>
            <div style="clear:both;">&nbsp;</div>
          </div>
        </div>
      </div>
    </div>
    <?php if(!empty($car_id)) { ?>
    <div class="box box-success" id="box_model">
      <div class="box-body">
        <table class="table sakotable table-bordered table-striped dt-responsive">
          <thead>
            <tr>
              <th>Estimate No</th>
              <th>Status</th>
              <th>Work Status</th>
              <th>Total Cost</th>
              <th>Deliverd</th>
              <th>Estimate Delivery Date</th>
              <th>Added Date</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php 
			$car_estimate_data = $wms->getRepairCarAllEstimateData($link,$car_id,$_SESSION['objCust']['user_id']);
			if(!empty($car_estimate_data)) { foreach($car_estimate_data as $cedata) { ?>
            <tr>
              <td><label class="label label-danger"><?php echo $cedata['estimate_no']?></label></td>
              <td><?php echo ($cedata['delivery_status'] == '1') ? '<label class="label label-success">Delivered</label>' : '<label class="label label-danger">Pending</label>'; ?></td>
              <td><label class="label label-default"><?php echo $cedata['work_status']?>%</label></td>
              <td><label class="label label-info"><?php echo $currency.$cedata['total_cost']?></label></td>
              <td><label class="label label-danger"><?php echo $wms->mySqlToDatePicker($cedata['delivery_done_date']);?></label></td>
              <td><?php echo $wms->mySqlToDatePicker($cedata['estimate_delivery_date']);?></td>
              <td><?php echo date('d/m/Y', strtotime($cedata['created_date'])); ?></td>
              <td><a class="btn btn-info" data-toggle="tooltip" href="javascript:;" onClick="$('#nurse_view_<?php echo $cedata['estimate_no']; ?>').modal('show');circularInit();" data-original-title="Details"><i class="fa fa-file-text-o"></i></a>
                <div id="nurse_view_<?php echo $cedata['estimate_no']; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                      <div class="modal-header orange_header">
                        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><i class="fa fa-close"></i></span></button>
                        <h3 class="modal-title">Repair Invoice & Status</h3>
                      </div>
                      <div class="modal-body">
                        <div class="row">
                          <div class="col-xs-12">
                            <?php
						  	$estimate_data = array();
							$row = $wms->getEstimateAndCarAndCustomerDetails($link, $cedata['estimate_no']);
							if(!empty($row) && count($row) > 0) {
								if(!empty($row['estimate_data'])) {
									$estimate_data = json_decode($row['estimate_data']);
								}
							}
						  ?>
                            <div align="center"><u>
                              <h2><b>Invoice no. <?php echo $cedata['estimate_no']; ?></b></h2>
                              </u></div>
                            <div class="progress-pie-chart" data-percent="<?php echo $cedata['work_status']; ?>">
                              <div class="ppc-progress">
                                <div class="ppc-progress-fill"></div>
                              </div>
                              <div class="ppc-percents">
                                <div class="pcc-percents-wrapper"> <span><?php echo $cedata['work_status']; ?>% Job Done</span> </div>
                              </div>
                            </div>
                            <br/>
                            <div align="center">
                              <h3><b>Repair Description</b></h3>
                            </div>
							<div class="items-list">
                              <table class="items-table">
                                <thead>
                                  <tr>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Labour</th>
                                    <th>Warranty</th>
                                    <th width="100">Total</th>
                                  </tr>
                                </thead>
                                <tfoot>
                                  <tr class="totals-row">
                                    <td colspan="4" class="wide-cell"></td>
                                    <td><strong>Total</strong></td>
                                    <td coslpan="4"><b><?php echo $currency; ?><?php echo $cedata['total_cost']; ?></b></td>
                                  </tr>
                                  <tr class="totals-row">
                                    <td colspan="4" class="wide-cell"></td>
                                    <td><strong>Discount</strong></td>
                                    <td coslpan="4"><?php echo $cedata['discount']; ?>%</td>
                                  </tr>
                                  <tr class="totals-row">
                                    <td colspan="4" class="wide-cell"></td>
                                    <td><strong>Paid Amount </strong></td>
                                    <td coslpan="4"><b><?php echo $currency; ?><?php echo $cedata['payment_done']; ?></b></td>
                                  </tr>
                                  <tr class="totals-row">
                                    <td colspan="4" class="wide-cell"></td>
                                    <td><strong>Balance Due </strong></td>
                                    <td coslpan="4"><?php echo $currency; ?><?php echo $cedata['payment_due']; ?></td>
                                  </tr>
                                </tfoot>
                                <tbody>
                                  <?php foreach($estimate_data as $estimate) { ?>
                                  <tr>
                                    <td><?php if(!empty($estimate->discription)){echo $estimate->discription; } ?></td>
                                    <td><?php echo $currency; ?><?php if(!empty($estimate->price)){echo $estimate->price; } ?></td>
                                    <td><?php if(!empty($estimate->quantity)){echo $estimate->quantity; } ?></td>
                                    <td><?php echo $currency; ?><?php if(!empty($estimate->labour)){echo $estimate->labour; } ?></td>
                                    <td><?php if(!empty($estimate->warranty)){echo str_replace("-"," ",$estimate->warranty); } ?></td>
                                    <td><?php echo $currency; ?><?php if(!empty($estimate->total)){echo $estimate->total; } ?></td>
                                  </tr>
                                  <?php } ?>
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div></td>
            </tr>
            <?php } ?>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
    <?php } ?>
    <?php } ?>
  </div>
</div>
<script type="text/javascript">
function load_history(rid) {
	window.location.href = "<?php echo WEB_URL;?>cust_panel/estimate.php?rid="+rid;
}
function circularInit() {
	var $ppc = $('.progress-pie-chart'),
  percent = parseInt($ppc.data('percent')),
  deg = 360*percent/100;
  if (percent > 50) {
    $ppc.addClass('gt-50');
  }
  $('.ppc-progress-fill').css('transform','rotate('+ deg +'deg)');
  $('.ppc-percents span').html(percent+'% Job Done');
}
</script>
<?php include('../cust_panel/footer.php'); ?>
