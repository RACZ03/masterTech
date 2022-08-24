<?php 
include_once('../workshop_admin_panel/header.php');
include_once('../helper/common.php');
$delivery_list = $wms->getAllDeliveryCarList($link);
?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <!-- <h1><i class="fa fa-list"></i> Delivery List </h1> -->
  <h1><i class="fa fa-list repair-car-heading"><span class="repair-car-txt"> Lista de Entregas </span></i></h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Lista de Entregas</li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
<!-- Full Width boxes (Stat box) -->
<div class="row">
  <div class="col-xs-12">
    <div class="box box-success">
      <div class="box-header">
        <h3 class="box-title">Lista de Entregas</h3>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <table class="table sakotable table-bordered table-striped dt-responsive">
          <thead>
            <tr>
              <th>ID de la Orden</th>
              <th>Cotización</th>
              <th>Cliente</th>
              <th>Máquina</th>
              <th>Teléfono</th>
              <th>Email</th>
              <th>Serie</th>
              <th>Entrega</th>
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
              <td><?php echo $data['c_name']; ?></td>
              <td><?php echo $data['car_name']; ?></td>
              <td><?php echo $data['c_mobile']; ?></td>
              <td><?php echo $data['c_email']; ?></td>
              <td><label class="label label-info"><?php echo $data['car_reg_no']; ?></label></td>
              <td><label class="label label-danger"><?php echo $wms->mySqlToDatePicker($data['delivery_done_date']); ?></label></td>
              <td><a class="btn btn-info" target="_blank" data-toggle="tooltip" href="<?php echo WEB_URL; ?>workshop_admin_panel/invoice.php?invoice_id=<?php echo $data['estimate_no']; ?>" data-original-title="Invoice"><i class="fa fa-file-text-o"></i></a> <a class="btn btn-success" data-toggle="tooltip" href="javascript:;" onClick="$('#estimate_view_<?php echo $data['car_id']; ?>').modal('show');" data-original-title="View"><i class="fa fa-eye"></i></a>
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
                      <div class="modal-header gteen_header">
                        <h3 class="modal-title"><i class="fa fa-table"></i> <b>Estimate Details</b></h3>
                      </div>
                      <div class="modal-body">
                        <div class="col-sm-12">
                          <table class="table">
                            <thead>
                              <tr>
                                <th>Estimate No</th>
                                <th>Status</th>
                                <th>Total Cost</th>
                                <th>Delivery Date</th>
                                <th>Created Date</th>
                                <th>Action</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php 
							$car_estimate_data = $wms->getRepairCarAllEstimateData($link,$data['car_id'],$data['customer_id']);
							if(!empty($car_estimate_data)) { foreach($car_estimate_data as $cedata) { ?>
                              <tr>
                                <td><label class="label label-success"><?php echo $cedata['estimate_no']?></label></td>
                                <td><?php echo ($cedata['delivery_status'] == '1') ? '<label class="label label-success">Delivered</label>' : '<label class="label label-danger">Pending</label>'; ?></td>
                                <td><?php echo $currency.$cedata['total_cost']?></td>
                                <td><?php echo $cedata['delivery_done_date']?></td>
                                <td><?php echo date('d/m/Y', strtotime($cedata['created_date'])); ?></td>
                                <td><a style="padding: 2px 6px; !important;" data-toggle="tooltip" data-original-title="Edit Estimate" href="<?php echo WEB_URL; ?>estimate/estimate_form.php?carid=<?php echo $data['car_id']; ?>&customer_id=<?php echo $data['customer_id']; ?>&estimate_no=<?php echo $data['estimate_no']; ?>" style="font-weight:bold;font-size:14px;" class="btn btn-success"><i class="fa fa-edit"></i> </a></td>
                              </tr>
                              <?php } ?>
                              <?php } ?>
                            </tbody>
                          </table>
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
  <!-- /.col -->
</div>
<!-- /.row -->
<?php include('../footer.php'); ?>
