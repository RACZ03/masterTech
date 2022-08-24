<?php

use Mockery\Undefined;

 include('../header.php');
?>
<?php
  $delinfo = 'none';
  $addinfo = 'none';
  $invoice_no = '';
  $msg = "";
  $token = false;
  if(isset($_GET['id']) && $_GET['id'] != '' && $_GET['id'] > 0){
    $wms->deleteRepairCar($link, $_GET['id']); 
    $delinfo = 'block';
    $msg = "Deleted repair car information successfully";
  }
  if(isset($_GET['m']) && $_GET['m'] == 'add'){
    $addinfo = 'block';
    $msg = "Added repair car Information Successfully";
  }
  if(isset($_GET['m']) && $_GET['m'] == 'up'){
    $addinfo = 'block';
    $msg = "Updated repair car Information Successfully";
  }

  $result = $wms->getAllRepairCarList($link);
  $delivery_list = $wms->getAllDeliveryCarList($link);
  $estimate_list = $wms->getAllEstimateCars($link);
  $estimate_data = array();

  /************************ Insert Query ***************************/
if (isset($_GET['estimate_no'])) {
	$foundEstimate = $wms->getEstimateAndCarAndCustomerDetails($link, $_GET['estimate_no']);
	$invoice_no = $_GET['estimate_no'];
}
?>

<!-- Content Header (Page header) -->
<div style="margin-left: 15px; margin-right: 15px;">

  <section class="content-header">
    <h1>
      <i class="fa fa-wrench repair-car-heading" style="background: transparent !important;"> 
        <span class="repair-car-txt">Ordenes de reparación</span>
      </i>
    </h1>
    <ol class="breadcrumb" style="background: transparent !important;">
      <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Repair Slot Machine List</li>
    </ol>
  </section>
  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist" id="myTabs">
    <li id="orders" role="presentation" class="active"><a href="#orders" aria-controls="orders" role="tab" data-toggle="tab">Ordenes</a></li>
    <li id="quotes" role="presentation"><a href="#quotes" aria-controls="quotes" role="tab" data-toggle="tab">Cotizaciones</a></li>
    <li id="deliveries" role="presentation" class=""><a href="#deliveries" aria-controls="deliveries" role="tab" data-toggle="tab">Entregas</a></li>
    <li id="deliveries2" role="presentation" class=""><a href="#deliveries2" aria-controls="deliveries2" role="tab" data-toggle="tab">Entregar y Facturar</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content" style="background: #FFF;">
    <!-- Div Orders -->
    <div role="tabpanel" class="tab-pane active" id="divOrders">
      <!-- Main content -->
      <section class="content">
      <!-- Full Width boxes (Stat box) -->
      <div class="row">
        <div class="col-xs-12">
          <div id="me" class="alert alert-danger alert-dismissable" style="display:<?php echo $delinfo; ?>">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-close"></i></button>
            <h4><i class="icon fa fa-ban"></i> Deleted!</h4>
            <?php echo $msg; ?> </div>
          <div id="you" class="alert alert-success alert-dismissable" style="display:<?php echo $addinfo; ?>">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-close"></i></button>
            <h4><i class="icon fa fa-check"></i> Success!</h4>
            <?php echo $msg; ?> </div>
          <div align="right" style="margin-bottom:1%;"> <a class="btn btn-success" data-toggle="tooltip" href="<?php echo WEB_URL; ?>repaircar/addcar.php" data-original-title="Add Repair Car"><i class="fa fa-plus"></i></a> <a class="btn btn-warning" data-toggle="tooltip" href="<?php echo WEB_URL; ?>dashboard.php" data-original-title="Dashboard"><i class="fa fa-dashboard"></i></a> </div>
          <div class="box box-success">
            <div class="box-header">
              <h3 class="box-title"><i class="fa fa-list"></i><span class="subheading-txt">Lista de órdenes de reparación</span></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table class="table sakotable table-bordered table-striped dt-responsive" id="tableOrders">
                <thead>
                  <tr>
                    <th>ID de Orden</th>
                    <th>Imagen</th>
                    <th>Maquina</th>
                    <th>Cliente</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Placa</th>
                    <th>Serie</th>
                    <th>Acción</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    $result = $wms->getAllRepairCarList($link);
                    foreach($result as $row) {
                      $image = WEB_URL . 'img/no_image.jpg';
                      $image_customer = WEB_URL . 'img/no_image.jpg';
                        
                      if(file_exists(ROOT_PATH . '/img/upload/' . $row['car_image']) && $row['car_image'] != ''){
                      	$image = WEB_URL . 'img/upload/' . $row['car_image']; //car image
                      }
                      if(file_exists(ROOT_PATH . '/img/upload/' . $row['customer_image']) && $row['customer_image'] != ''){
                      	$image_customer = WEB_URL . 'img/upload/' . $row['customer_image']; //customer iamge
                      }
                    
                    ?>
                    <tr>
                  <td style="text-align: center;">
                    <span class="label label-success label-bg-color"><?php echo $row['repair_car_id']; ?></span>
                  </td>
                  <td><img class="photo_img_round" style="width:50px;height:50px;" src="<?php echo $image;  ?>" /></td>
                  <td><?php echo $row['car_name']; ?></td>
                  <td><?php echo $row['c_name']; ?></td>
                    <td><?php echo $row['make_name']; ?></td>
                    <td><?php echo $row['model_name']; ?></td>
                    <td><span class="label label-danger"><?php echo $row['chasis_no']; ?></span></td>
                    <td><?php echo $row['year_name']; ?></td>
                    <td>
                    <a class="btn btn-success" data-toggle="tooltip" href="javascript:;" onClick="$('#nurse_view_<?php echo $row['car_id']; ?>').modal('show');" data-original-title="View"><i class="fa fa-eye"></i></a>  <a class="btn btn-primary" data-toggle="tooltip" href="<?php echo WEB_URL;?>repaircar/addcar.php?id=<?php echo $row['car_id']; ?>" data-original-title="Edit"><i class="fa fa-pencil"></i></a> <a class="btn btn-danger" data-toggle="tooltip" onClick="deleteCustomer(<?php echo $row['car_id']; ?>);" href="javascript:;" data-original-title="Delete"><i class="fa fa-trash-o"></i></a>
                      <div id="nurse_view_<?php echo $row['car_id']; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><i class="fa fa-close"></i></span></button>
                              <h3 class="modal-title">Detalle</h3>
                            </div>
                            <!-- <div class="modal-body model_view" align="center">&nbsp;
                              <div><img class="photo_img_round" style="width:100px;height:100px;" src="<?php echo $image;  ?>" /></div>
                              <div class="model_title"><?php echo $row['car_name']; ?></div>
                    <div style="color:#fff;font-size:15px;font-weight:bold;">Invoice No: <?php echo $row['repair_car_id']; ?></div>
                            </div> -->
                          <div class="modal-body">
                              <h3 style="text-decoration:underline;">Car Details Infromation</h3>
                              <div class="row">
                                <div class="col-xs-12">
                                  <b>Car Name :</b> <?php echo $row['car_name']; ?><br/>
                                  <b>Car Repair ID :</b> <?php echo $row['repair_car_id']; ?><br/>
                                  <b>Car Make :</b> <?php echo $row['make_name']; ?><br/>
                                  <b>Car Model :</b> <?php echo $row['model_name']; ?><br/>
                                  <b>Year :</b> <?php echo $row['year_name']; ?><br/>
                                  <b>Chasis No :</b> <?php echo $row['chasis_no']; ?><br/>
                                  <b>Registration No :</b> <?php echo $row['car_reg_no']; ?><br/>
                                  <b>VIN No :</b> <?php echo $row['VIN']; ?><br/>
                                  <b>Note :</b> <?php echo $row['note']; ?><br/>
                                  <b>Added Date :</b> <?php echo date('d/m/Y h:m:s', strtotime($row['added_date'])); ?>
                                </div>
                              </div>
                            </div>				  
                          </div>
                          <!-- /.modal-content -->
                          <div class="modal-content">
                            <!-- <div class="modal-header orange_header">
                              <h3 class="modal-title">Customer Details</h3>
                            </div> -->
                            <!-- <div class="modal-body model_view" align="center">&nbsp;
                              <div><img class="photo_img_round" style="width:100px;height:100px;" src="<?php echo $image_customer;  ?>" /></div>
                              <div class="model_title"><?php echo $row['c_name']; ?></div>
                            </div> -->
                              <div class="modal-body">
                              <h3 style="text-decoration:underline;">Customer Details Infromation</h3>
                              <div class="row">
                                <div class="col-xs-12"> 
                                  <b>Customer Name :</b> <?php echo $row['c_name']; ?><br/>
                                  <b>Customer Email :</b> <?php echo $row['c_email']; ?><br/>
                                  <b>Customer Telephone :</b> <?php echo $row['c_mobile']; ?><br/>
            
                                </div>
                              </div>
                            </div>				  
                          </div>
                  <!-- /.modal-content -->
                        </div>
                      </div>
                    </td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      </section>
    </div>
    <!-- Div Quotes -->
    <div role="tabpanel" class="tab-pane" id="divQuotes">
      <!-- Main content -->
      <section class="content">
        <!-- Full Width boxes (Stat box) -->
        <div class="row">
          <div class="col-md-12">
            <form>
              <div class="box box-success" id="box_model">
                <div class="box-body">
                  <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-search"></i> Buscar Ordenes</h3>
                  </div>
                  <div class="row">
                    <div class="form-group col-lg-4 col-md-4 col-sm-6 col-sm-12">
                      <!-- <label for="txtInvoiceNo">ID de la Orden :</label> -->
                      <select class="form-control" name="txtInvoiceNo" id="txtInvoiceNo" onchange="onSelectOrders(this)" >
                        <option value="#" selected>Seleccionar una Orden</option>
                        <?php foreach($result as $row) { ?>
                          <option value="<?php echo $row['repair_car_id']; ?>"><?php echo $row['repair_car_id'].' - '.$row['car_name']; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                    <div class="form-group col-lg-2 col-md-3 col-sm-3 col-sm-12">
                      <button type="button" onclick="onSearchOrders()" class="btn btn-large btn-block btn-bg"><b>Buscar</b></button>
                    </div>
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

                <div class="d-none">
                  <div style="width:98%;height:auto;border:solid 1px #ccc;padding:10px;margin:10px;" >
                    <div class="col-md-3">
                      <img class='img-thumbnail' style="width:150px;height:150px;" src="../img/no_image.jpg" />
                    </div>
                    <div class="col-md-3 text-left">
                      <div>
                        <h4 style="font-weight:bold;"><u>Máquina</u></h4>
                      </div>
                      <div><b>Marca:</b> <label id="txtMar" style="color: #666; margin-left: 5px;"> -- </label></div>
                      <div><b>Modelo:</b> <label id="txtModels" style="color: #666; margin-left: 5px;"> -- </label></div>
                      <div><b>Serie:</b> <label id="txtSerie" style="color: #666; margin-left: 5px;"> -- </label></div>
                    </div>
                    <div class="col-md-3 text-left">
                      <div>
                        <h4>&nbsp;</h4>
                      </div>
                      <div><b>Fecha de la Orden:</b><label id="txtDate" style="color: #666; margin-left: 5px;"> -- </label></div>
                    </div>
                    <div class="col-md-3 text-left">
                      <div>
                        <h4>&nbsp;</h4>
                      </div>
                      <div class="form-group text-right">
                        <a id="addCotization" href="#" style="font-weight:bold;font-size:17px;" class="btn btn-danger" disabled>
                          <i class="fa fa-plus"></i> 
                            Agregar Cotización
                        </a>
                      </div>
                      <div class="form-group text-right">
                        <button id="btnConsultC" data-original-title="All Estimate History" disabled style="font-weight:bold;font-size:17px;" class="btn btn-primary">
                          <i class="fa fa-calendar-check-o"></i> 
                          Consultar Cotizaciónes
                        </button>
                      </div>
                    </div>
                    <div style="clear:both;">&nbsp;</div>
                    <div id="estimate_view" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                      <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                          <div class="modal-header green_header">
                            <button onclick="onCloseModal()" class="close" type="button"><span aria-hidden="true"><i class="fa fa-close"></i></span></button>
                            <h3 class="modal-title"><b>Estimate History</b></h3>
                          </div>
    
                          <div class="modal-body" style="overflow:auto;">
                            <table class="table" id="table2">
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
                                $car_id = isset($_GET['car_id']) ? $_GET['car_id'] : '';
                                $customer_id = isset( $_GET['customer_id'] ) ? $_GET['customer_id'] : '';
                                $mechanics_id = isset( $_GET['mechanics_id'] ) ? $_GET['mechanics_id'] : '';

                                if( $car_id != '' && $customer_id != '' && $mechanics_id != '' ) {
                                  $car_estimate_data = $wms->getRepairCarAllEstimateData($link, $car_id, $customer_id);
                                  if(!empty($car_estimate_data)) { 
                                    foreach($car_estimate_data as $cedata) { ?>
                                    <tr>
                                      <td><label class="label label-danger"><?php echo $cedata['estimate_no']?></label></td>
                                      <td><?php echo ($cedata['delivery_status'] == '1') ? '<label class="label label-success">Delivered</label>' : '<label class="label label-danger">Pending</label>'; ?></td>
                                      <td><?php echo $cedata['total_cost']?></td>
                                      <td><?php echo $wms->mySqlToDatePicker($cedata['delivery_done_date']);?></td>
                                      <td><?php echo $wms->mySqlToDatePicker($cedata['estimate_delivery_date']);?></td>
                                      <td><?php echo date('d/m/Y', strtotime($cedata['created_date'])); ?></td>
                                      <td>
                                        <a style="padding: 2px 6px !important;" data-toggle="tooltip" data-original-title="Edit Estimate" 
                                            href="<?php echo WEB_URL; ?>estimate/estimate_form.php?carid=<?php echo $car_id; ?>&customer_id=<?php echo $customer_id ?>&mechanics_id=<?php echo $mechanics_id ?>&estimate_no=<?php echo $cedata['estimate_no']; ?>" style="font-weight:bold;font-size:14px;" class="btn btn-success">
                                          <i class="fa fa-edit"></i> 
                                        </a>
                                      </td>
                                    </tr>
                                  <?php } ?>
                                <?php }
                                } 
                                ?>
                              </tbody>
                            </table>
                          </div>
                        </div>
                        <!-- /.modal-content -->
                      </div>
                    </div>
                  </div>
                </div>
                <?php if(!$token && !empty($_POST)) { ?>
                <div align="center">No car found based on your selected query.</div>
                <?php } ?>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
    <!-- Div Deliveries -->
    <div role="tabpanel" class="tab-pane" id="divDeliveries">
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
                      <td><a class="btn btn-info" target="_blank" data-toggle="tooltip" href="<?php echo WEB_URL; ?>invoice/invoice.php?invoice_id=<?php echo $data['estimate_no']; ?>" data-original-title="Invoice"><i class="fa fa-file-text-o"></i></a> <a class="btn btn-success" data-toggle="tooltip" href="javascript:;" onClick="$('#estimate_view_<?php echo $data['car_id']; ?>').modal('show');" data-original-title="View"><i class="fa fa-eye"></i></a>
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
      </section>
    </div>
    <!-- Div Deliveries and Invoice -->
    <div role="tabpanel" class="tab-pane" id="divDeliveries2">
      <!-- Main content -->
      <section class="content">
        <!-- Full Width boxes (Stat box) -->
        <div class="row">
          <div class="col-md-12">
            <form id="frmcarstock">
              <div class="box box-success" id="box_model">
                <div class="box-body">
                  <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-search"></i>Buscar Máquina</h3>
                  </div>
                  <div class="row">
                    <div class="form-group col-lg-4 col-md-4 col-sm-6 col-sm-12">
                      <label for="txtInvoiceNo">No. Estimacion:</label>
                      <select class="form-control" name="txtInvoiceNo" id="txtInvoiceNo2" onchange="onSelectOrders(this)" >
                        <option value="#" selected>Seleccionar</option>
                        <?php foreach($estimate_list as $row) { ?>
                          <option value="<?php echo $row['estimate_no']; ?>"><?php echo $row['estimate_no'].' - '.$row['car_name']; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                    <div class="form-group col-lg-2 col-md-3 col-sm-3 col-sm-12" style="margin-top: 25px;">
                      <button type="button" onclick="onSearchOrders(2)" class="btn btn-large btn-block btn-bg"><b>Buscar</b></button>
                    </div>
                  </div>
                </div>
                <!-- /.box-body -->
              </div>
              <!-- /.box -->
            </form>
            <?php if(!empty($foundEstimate) && count($foundEstimate) > 0) { ?>
            <div class="box box-success" id="box_model">
              <div class="box-body">
                <div id="eloader" style="position:absolute;margin-left:400px;display:none;"><img src="<?php echo WEB_URL ?>/img/eloader.gif" /></div>
                <div class="form-group col-md-12" style="padding-top:10px;">
                  <div class="pull-left">
                    <label class="label label-success" style="font-size:15px; margin-right: 10px;" id="txtInvoceOrder">INVOICE - <?php echo isset($_GET['estimate_no']) ? $_GET['estimate_no'] : '---'; ?></label>
                    <label class="label label-danger" style="font-size:15px;">Delivery Done ? :
                    <input style="padding-top:2px;" type="checkbox" name="chkdeliver" <?php if($foundEstimate['delivery_status'] == '1'){echo 'checked'; }?> id="chkdeliver">
                    </label>
                    <div> <br/>
                      <label>Delivery Date:</label>
                      <input type="text" id="txtDeliveryDate" value="<?php echo $foundEstimate['delivery_done_date'] != '0000-00-00' ? $wms->mySqlToDatePicker($foundEstimate['delivery_done_date']) : date('d/m/Y');?>" class="form-control datepicker" />
                    </div>
                  </div>
                  <div class="pull-right">
                    <button type="button" onclick="saveEstimateData(<?php echo $foundEstimate['customer_id']; ?>,<?php echo $foundEstimate['car_id']; ?>,'<?php echo $foundEstimate['estimate_no']; ?>','<?php echo WEB_URL ?>');" class="btn btn-info btnsp">
                      <i class="fa fa-print fa-2x"></i><br/>Update & Generate Invoice
                    </button>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <a target="_blank" href="<?php echo WEB_URL;?>/invoice/invoice.php?invoice_id=<?php echo $_GET['estimate_no']; ?>" class="btn btn-warning btnsp">
                      <i class="fa fa-eye fa-2x"></i><br/>Invoice Preview
                    </a> 
                  </div>
                </div>
              </div>
            </div>
            <div class="box box-success" id="box_model">
              <div class="box-body">
                <?php
                  if(!empty($foundEstimate['estimate_data'])) {
                    $estimate_data = json_decode($foundEstimate['estimate_data']);
                  }
                  $customer_id = $foundEstimate['customer_id'];
                  $image = WEB_URL . 'img/no_image.jpg';
                  if(file_exists(ROOT_PATH . '/img/upload/' . $foundEstimate['car_image']) && $foundEstimate['car_image'] != ''){
                    $image = WEB_URL . 'img/upload/' . $foundEstimate['car_image']; //car image
                  }
                  $token = true;
                ?>
                <div class="box-header">
                  <h3 class="box-title"><i class="fa fa-user"></i> Car Owner Details</h3>
                </div>
                <div class="form-group col-md-12">
                  <?php
                    $image_cust = WEB_URL . 'img/no_image.jpg';
                    // if(file_exists(ROOT_PATH . '/img/upload/' . $row['image']) && $row['image'] != ''){
                    //   $image_cust = WEB_URL . 'img/upload/' . $row['image']; //car image
                    // }
                  ?>
                  <div style="width:98%;height:auto;border:solid 1px #ccc;padding:10px;margin:10px;">
                    <div class="col-md-3"><img class="img-thumbnail" style="width:150px;height:150px;" src="<?php echo $image_cust; ?>" /></div>
                    <div class="col-md-3 text-left">
                      <div>
                        <h4 style="font-weight:bold;"><u id="txtCustomerName"> <?php echo isset($foundEstimate['c_name']) ? $foundEstimate['c_name'] : '---'  ?> </u></h4>
                      </div>
                      <div><b>Email:</b> <?php echo isset($foundEstimate['c_email']) ? $foundEstimate['c_email'] : '---'; ?></div>
                      <div><b>Address:</b> <?php echo isset($foundEstimate['c_address']) ? $foundEstimate['c_address'] : '---'; ?></div>
                    </div>
                    <div class="col-md-3 text-left">
                      <div>
                        <h4 style="font-weight:bold;">&nbsp;</h4>
                      </div>
                      <div><b>Home Telephone:</b> <?php echo isset($foundEstimate['c_home_tel']) ? $foundEstimate['c_home_tel'] : '---'; ?></div>
                      <div><b>Work Telephone:</b> <?php echo isset($foundEstimate['c_work_tel']) ? $foundEstimate['c_work_tel'] : '---'; ?></div>
                      <div><b>Mobile:</b> <?php echo isset($foundEstimate['c_mobile']) ? $foundEstimate['c_mobile'] : '---'; ?></div>
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
                      <h4 style="font-weight:bold;"><u><?php echo isset($foundEstimate['car_name']) ? $foundEstimate['car_name'] : '---'; ?></u></h4>
                    </div>
                    <div><b>Make:</b> <?php echo isset($foundEstimate['make_name']) ? $foundEstimate['make_name'] : '---'; ?></div>
                    <div><b>Model:</b> <?php echo isset($foundEstimate['model_name']) ? $foundEstimate['model_name'] : '---'; ?></div>
                    <div><b>Year:</b> <?php echo isset($foundEstimate['year_name']) ? $foundEstimate['year_name'] : '---'; ?></div>
                    <div><b>Chasis No:</b> <?php echo isset($foundEstimate['chasis_no']) ? $foundEstimate['chasis_no']: '---'; ?></div>
                    <div><b>VIN#:</b> <?php echo isset($foundEstimate['VIN']) ? $foundEstimate['VIN'] : '---'; ?></div>
                  </div>
                  <div class="col-md-3 text-left">
                    <div>
                      <h4>&nbsp;</h4>
                    </div>
                    <div><b>Car Registration No:</b> <?php echo isset($foundEstimate['car_reg_no']) ?  $foundEstimate['car_reg_no'] : '---'; ?></div>
                    <div><b>Added Date:</b> <?php echo isset($foundEstimate['added_date']) ? $foundEstimate['added_date'] : '---'; ?></div>
                    <div><b>Delivery Date:</b> <label style="color:red;font-weight:bold;"><?php echo isset($foundEstimate['estimate_delivery_date']) ? $foundEstimate['estimate_delivery_date'] : '---'; ?></label></div>
                    <div><b>Job Status:</b> <span style="font-size:12px;" class="label label-<?php if($foundEstimate['job_status'] == '0'){echo 'danger';} else {echo 'success';} ?>">
                      <?php if($foundEstimate['job_status'] == '0'){echo 'Processing';} else {echo 'Done';} ?>
                      </span></div>
                  </div>
                  <div class="col-md-3 text-left">
                    <div>
                      <h4>&nbsp;</h4>
                    </div>
                    <div style="margin-bottom:5px;"><b>Delivery Status:</b> <span style="font-size:12px;" class="label label-<?php if($foundEstimate['delivery_status'] == '0'){echo 'danger';} else {echo 'success';} ?>">
                      <?php if($foundEstimate['delivery_status'] == '0'){echo 'Pending';} else {echo 'Done';} ?>
                      </span></div>
                    <div><b>Repair Progress:</b>
                      <div class="progress">
                        <div class="progress-bar progress-bar-success bar" role="progressbar" aria-valuenow="<?php echo $foundEstimate['work_status']; ?>"
                          aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $foundEstimate['work_status']; ?>%"></div>
                        <span><?php echo isset($foundEstimate['work_status']) ? $foundEstimate['work_status'] : '0'; ?>%</span> </div>
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
                        <td align="right"><strong>Total (<?php echo isset($currency) ? $currency : '$'; ?>):</strong></td>
                        <td><input style="text-align:center;font-weight:bold;" id="total_price" type="text" value="<?php echo isset($foundEstimate['total_cost']) ? $foundEstimate['total_cost'] : '0' ; ?>" size="1" disabled="disabled" class="form-control allownumberonly" /></td>
                      </tr>
                      <tr>
                        <td colspan="6"></td>
                        <td align="right"><strong>Discount (%):</strong></td>
                        <td><input style="text-align:center;font-weight:bold;" id="total_discount" value="<?php echo isset($foundEstimate['discount']) ? $foundEstimate['discount'] : '0' ; ?>" type="text" size="1" class="form-control allownumberonly" /></td>
                      </tr>
                      <tr>
                        <td colspan="6"></td>
                        <td align="right"><strong>Balance Paid (<?php echo isset($currency) ? $currency : '$'; ?>):</strong></td>
                        <td><input style="text-align:center;font-weight:bold;" id="total_paid" type="text" value="<?php echo isset($foundEstimate['payment_done']) ? $foundEstimate['payment_done'] : '0' ; ?>" size="1" class="form-control allownumberonly" /></td>
                      </tr>
                      <tr>
                        <td colspan="6"></td>
                        <td align="right"><strong>Balance Due (<?php echo isset($currency) ? $currency : '$'; ?>):</strong></td>
                        <td><input style="text-align:center;font-weight:bold;" id="total_due" type="text" value="<?php echo isset($foundEstimate['payment_due']) ? $foundEstimate['payment_due'] : '0' ; ?>" size="1" disabled="disabled" class="form-control allownumberonly" /></td>
                      </tr>
                      <tr>
                        <td colspan="6"></td>
                        <td align="right"><strong>Grand Total (<?php echo isset($currency) ? $currency : '$'; ?>):</strong></td>
                        <td><input style="text-align:center;font-weight:bold;" id="total_grand_total" type="text" value="<?php echo isset($foundEstimate['grand_total']) ? $foundEstimate['grand_total'] : '0' ; ?>" size="1" disabled="disabled" class="form-control allownumberonly" /></td>
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
      </section>
    </div>
  </div>
</div>
<br>
<br>

<!-- /.row -->
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="text/javascript">
  var hr = window.location.href;
  div_active = hr.includes('car_id=');
  div_active2 = hr.includes('estimate_no=');
  var dataT = <?php echo json_encode($result) ?>;
  $( document ).ready(function() {
    setTimeout(function() {
          $("#me").hide(300);
          $("#you").hide(300);
      }, 3000);
    
    
    if ( div_active != undefined && div_active ) {
      $('#orders').removeClass('active');
      $('#quotes').addClass('active');
      $('#deliveries').removeClass('active');
      $('#deliveries2').removeClass('active');

      $('#divOrders').removeClass('active');
      $('#divQuotes').addClass('active');
      $('#divDeliveries').removeClass('active');
      $('#divDeliveries2').removeClass('active');

      setTimeout(() => {
        $('#estimate_view').modal('show'); // abrir
      }, 500);
    }

    if ( div_active2 != undefined && div_active2 ) {
      $('#orders').removeClass('active');
      $('#quotes').removeClass('active');
      $('#deliveries').removeClass('active');
      $('#deliveries2').addClass('active');

      $('#divOrders').removeClass('active');
      $('#divQuotes').removeClass('active');
      $('#divDeliveries').removeClass('active');
      $('#divDeliveries2').addClass('active');
    }

  });
  
  $('#myTabs li').click(function (e) {
    e.preventDefault()
    if (this.id == 'orders') {
      $('#orders').addClass('active');
      $('#quotes').removeClass('active');
      $('#deliveries').removeClass('active');
      $('#deliveries2').removeClass('active');

      $('#divOrders').addClass('active');
      $('#divQuotes').removeClass('active');
      $('#divDeliveries').removeClass('active');
      $('#divDeliveries2').removeClass('active');
    } else if (this.id == 'quotes') {
      $('#orders').removeClass('active');
      $('#quotes').addClass('active');
      $('#deliveries').removeClass('active');
      $('#deliveries2').removeClass('active');

      $('#divOrders').removeClass('active');
      $('#divQuotes').addClass('active');
      $('#divDeliveries').removeClass('active');
      $('#divDeliveries2').removeClass('active');
    } else if (this.id == 'deliveries'){
      $('#orders').removeClass('active');
      $('#quotes').removeClass('active');
      $('#deliveries').addClass('active');
      $('#deliveries2').removeClass('active');

      $('#divOrders').removeClass('active');
      $('#divQuotes').removeClass('active');
      $('#divDeliveries').addClass('active');
      $('#divDeliveries2').removeClass('active');
    } else {
      $('#orders').removeClass('active');
      $('#quotes').removeClass('active');
      $('#deliveries').removeClass('active');
      $('#deliveries2').addClass('active');

      $('#divOrders').removeClass('active');
      $('#divQuotes').removeClass('active');
      $('#divDeliveries').removeClass('active');
      $('#divDeliveries2').addClass('active');
    }

  })

    
</script>
<script src="../assets/js/pages/carList.js"></script>
<?php include('../footer.php'); ?>
