<?php 
include_once('../header.php');
$invoice_no = '';
$sql = '';
$token = false;

/************************ Insert Query ***************************/
if (!empty($_POST)) {
	$invoice_no = $_POST['txtInvoiceNo'];
}
?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1><i class="fa fa-wrench repair-car-heading"><span class="repair-car-txt"> Reparación de máquina </span></i></h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Find Slot Machine Estimate</li>
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
            <h3 class="box-title"><i class="fa fa-search"></i> Buscar por ID de la Orden</h3>
          </div>
          <div class="form-group col-md-12">
            <label for="txtInvoiceNo">ID de la Orden :</label>
            <input type="text" name="txtInvoiceNo" id="txtInvoiceNo" value="<?php echo $invoice_no; ?>" class="form-control" />
          </div>
          <div class="form-group col-md-12">
            <button type="submit" class="btn btn-large btn-block btn-bg"><b>BUSCAR</b></button>
          </div>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
    </form>
    <div class="box box-success" id="box_model">
      <div class="box-body">
        <div class="box-header">
          <h3 class="box-title"><i class="fa fa-list"></i><span class="subheading-txt">Listado de Ordens</span></h3>
        </div>
        <?php if (!empty($_POST)) { 
				$row = $wms->filterRepairCarInfo($link, $_POST);
				if(!empty($row)){
				$image = WEB_URL . 'img/no_image.jpg';
				if(file_exists(ROOT_PATH . '/img/upload/' . $row['image']) && $row['image'] != ''){
					$image = WEB_URL . 'img/upload/' . $row['image']; //car image
				}
				$token = true;
				
				?>
        <div style="width:98%;height:auto;border:solid 1px #ccc;padding:10px;margin:10px;">
          <div class="col-md-3"><img class='img-thumbnail' style="width:150px;height:150px;" src="<?php echo $image; ?>" /></div>
          <div class="col-md-3 text-left">
            <div>
              <h4 style="font-weight:bold;"><u><?php echo $row['car_name']; ?></u></h4>
            </div>
            <div><b>Marca:</b> <?php echo $row['make_name']; ?></div>
            <div><b>Modelo:</b> <?php echo $row['model_name']; ?></div>
            <div><b>Serie:</b> <?php echo $row['model_name']; ?></div>
            <!-- <div><b>Chasis No:</b> <?php echo $row['chasis_no']; ?></div>
            <div><b>VIN#:</b> <?php echo $row['VIN']; ?></div> -->
          </div>
          <div class="col-md-3 text-left">
            <div>
              <h4>&nbsp;</h4>
            </div>
            <!-- <div><b>Car Registration No:</b> <?php echo $row['car_reg_no']; ?></div> -->
            <div><b>Fecha de la Orden:</b> <?php echo $row['added_date']; ?></div>
            <!--<div><b>Job Status:</b> <span style="font-size:12px;" class="label label-<?php //if($row['job_status'] == '0'){echo 'danger';} else {echo 'success';} ?>">
              <?php //if($row['job_status'] == '0'){echo 'Processing';} else {echo 'Done';} ?>
              </span></div>-->
          </div>
          <div class="col-md-3 text-left">
            <div>
              <h4>&nbsp;</h4>
            </div>
            <!--<div style="margin-bottom:5px;"><b>Delivery Status:</b> <span style="font-size:12px;" class="label label-<?php if($row['delivery_status'] == '0'){echo 'danger';} else {echo 'success';} ?>">
              <?php if($row['delivery_status'] == '0'){echo 'Pending';} else {echo 'Done';} ?>
              </span></div>
			        <div><b>Repair Progress:</b>
              <div class="progress">
                <div class="progress-bar progress-bar-success bar" role="progressbar" aria-valuenow="<?php //echo $row['work_status']; ?>"
                  aria-valuemin="0" aria-valuemax="100" style="width:<?php //echo $row['work_status']; ?>%"></div>
                <span><?php //echo $row['work_status']; ?>%</span> </div>
            </div>-->
            <div class="form-group text-right"><a href="<?php echo WEB_URL; ?>estimate/estimate_form.php?carid=<?php echo $row['car_id']; ?>&customer_id=<?php echo $row['customer_id']; ?>&mechanics_id=<?php echo $row['mechanics_id'];?>" style="font-weight:bold;font-size:17px;" class="btn btn-danger"><i class="fa fa-plus"></i> Agregar Cotización</a></div>
            <div class="form-group text-right"><a data-toggle="tooltip" href="javascript:;" onClick="$('#estimate_view_<?php echo $row['car_id']; ?>').modal('show');" data-original-title="All Estimate History" style="font-weight:bold;font-size:17px;" class="btn btn-primary"><i class="fa fa-calendar-check-o"></i> Consultar Cotizaciónes</a></div>
          </div>
          <div style="clear:both;">&nbsp;</div>
          <div id="estimate_view_<?php echo $row['car_id']; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header green_header">
                  <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><i class="fa fa-close"></i></span></button>
                  <h3 class="modal-title"><b>Estimate History</b></h3>
                </div>
                <!--<div class="modal-body model_view" align="center">&nbsp;
                        <div><img class="img-thumbnail" style="width:100px;height:100px;" src="<?php //echo $image;  ?>" /></div>
                        <div class="model_title"><?php //echo $row['car_name']; ?></div>
                      </div>-->
                <div class="modal-body" style="overflow:auto;">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>Estimate No</th>
                        <th>Status</th>
                        <th>Total Cost</th>
                        <th>Deliverd</th>
						            <th>Delivery Date</th>
                        <th>Created Date</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
							$car_estimate_data = $wms->getRepairCarAllEstimateData($link,$row['car_id'],$row['customer_id'],$row['mechanics_id'],$row['repair_car_id']);
							if(!empty($car_estimate_data)) { foreach($car_estimate_data as $cedata) { ?>
                      <tr>
                        <td><label class="label label-danger"><?php echo $cedata['estimate_no']?></label></td>
                        <td><?php echo ($cedata['delivery_status'] == '1') ? '<label class="label label-success">Delivered</label>' : '<label class="label label-danger">Pending</label>'; ?></td>
                        <td><?php echo $cedata['total_cost']?></td>
                        <td><?php echo $wms->mySqlToDatePicker($cedata['delivery_done_date']);?></td>
						            <td><?php echo $wms->mySqlToDatePicker($cedata['estimate_delivery_date']);?></td>
                        <td><?php echo date('d/m/Y', strtotime($cedata['created_date'])); ?></td>
                        <td><a style="padding: 2px 6px; !important;" data-toggle="tooltip" data-original-title="Edit Estimate" href="<?php echo WEB_URL; ?>estimate/estimate_form.php?carid=<?php echo $row['car_id']; ?>&customer_id=<?php echo $row['customer_id']; ?>&mechanics_id=<?php echo $row['mechanics_id']; ?>&estimate_no=<?php echo $cedata['estimate_no']; ?>" style="font-weight:bold;font-size:14px;" class="btn btn-success"><i class="fa fa-edit"></i> </a></td>
                      </tr>
                      <?php } ?>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>
              <!-- /.modal-content -->
            </div>
          </div>
        </div>
        <?php } } ?>
        <?php if(!$token && !empty($_POST)) { ?>
        <div align="center">No car found based on your selected query.</div>
        <?php } ?>
      </div>
    </div>
  </div>
</div>
</section>
<?php include('../footer.php'); ?>
