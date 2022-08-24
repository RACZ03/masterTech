<?php
include_once('../header.php');
$row = 0;
$percent_value = 0;
//$parts_lists = $wms->getAllPartsList($link);
$invoice_id = substr(number_format(time() * rand(),0,'',''),0,6);
$car_id = 0;
$customer_id = 0;
$mechanics_id = 0;
$delinfo = 'none';
$addinfo = 'none';
$form_url = '';
$estimate_data = array();
$total_cost = '0.00';
$total_paid = '0.00';
$total_due = '0.00';
$estimate_delivery_date = '';

if(isset($_GET['carid']) && $_GET['customer_id'] && $_GET['mechanics_id']) {
	$car_id = $_GET['carid'];
	$customer_id = $_GET['customer_id'];
     $mechanics_id = $_GET['mechanics_id'];
} else {
	die("Direct Access Not Allow Here");
}

/*save estimate data here*/
if(!empty($_POST)) {
	$estimate_delivery_date = $wms->datepickerDateToMySqlDate($_POST['txtDeliveryDate']);
	$estimate_data = '';
	$invoice_id = $_POST['hfInvoiceId'];
	$car_id = $_POST['hfCarId'];
	$customer_id = $_POST['hfCustomerId'];
     $mechanics_id = $_POST['hfMechanicsId'];
	$total_cost =  $_POST['hfTotalCost'];
	$due =  $_POST['hfDue'];
	$job_status = 0;
	if((int)$_POST['hf_work_progress'] == 100) {
		$job_status = 1;
	}	
	if(isset($_POST['estimate_data']) && !empty($_POST['estimate_data'])) {
		$estimate_data = json_encode($_POST['estimate_data']);
	}
	$data = array(
		'estimate_no' 				=> $invoice_id,
		'car_id' 					=> $car_id,
		'work_status' 				=> $_POST['hf_work_progress'],
		'job_status' 				=> $job_status,
		'estimate_data' 			=> $estimate_data,
		'total_cost' 				=> $total_cost,
		'payment_due' 				=> $due,
		'grand_total' 				=> $total_cost,
		'customer_id' 				=> $customer_id,
          'mechanics_id'        => $mechanics_id,     
		'estimate_delivery_date' 	=> $estimate_delivery_date
	);
	$wms->SaveUpdateCarEstimateDate($link, $data);
	
	//send email if checked
	if(isset($_POST['email_notification']) && $_POST['email_notification'] == 'on') {
		$wms->sendCustomerEmailNotification($link, $car_id, $_POST['hf_work_progress'], $invoice_id);
	}
	$url = WEB_URL . 'estimate/estimate_form.php?carid='.$car_id.'&customer_id='.$customer_id.'&mechanics_id='.$mechanics_id.'&estimate_no='.$invoice_id.'&token=save';
	header("Location: $url");
}
/**/

/*load estimate data from database*/
if(isset($_GET['estimate_no']) && !empty($_GET['estimate_no'])) {
	$invoice_id = $_GET['estimate_no'];
     $result_car_data = $wms->getRepairCarEstimateData($link, $invoice_id);
	if(!empty($result_car_data)) {
		if(!empty($result_car_data['estimate_data'])) {
			$estimate_data = json_decode($result_car_data['estimate_data']);
               // print_r($estimate_data);
			// $row = count($estimate_data);
		}
		if($result_car_data['estimate_delivery_date'] != '0000-00-00') {
			$estimate_delivery_date = $wms->mySqlToDatePicker($result_car_data['estimate_delivery_date']);
		}
		$percent_value = $result_car_data['work_status'];
		$total_cost = $result_car_data['total_cost'];
		$total_paid = $result_car_data['payment_done'];
		$total_due  = $result_car_data['payment_due'];
	}
}

if(isset($_GET['token']) && $_GET['token'] == 'save') {
	$addinfo = 'block';
}

/**/

?>

<section class="content-header">
     <h1><i class="fa fa-edit repair-car-heading"> <span class="repair-car-txt">Agregar o Editar la Orden<span></i></h1>
     <ol class="breadcrumb">
          <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Slot Machine Setting</li>
          <li class="active">Add Slot Machine Setting</li>
     </ol>
     <div>&nbsp;</div>
     <div id="me" class="alert alert-danger alert-dismissable" style="display:<?php echo $delinfo; ?>">
          <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i
                    class="fa fa-close"></i></button>
          <h4><i class="icon fa fa-ban"></i> Deleted!</h4>
          Deleted estimate successfully.
     </div>
     <div id="you" class="alert alert-success alert-dismissable" style="display:<?php echo $addinfo; ?>">
          <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i
                    class="fa fa-close"></i></button>
          <h4><i class="icon fa fa-check"></i> Success!</h4>
          Save estimate information successfully.
     </div>
</section>
<!-- Main content -->
<form onsubmit="return validateEstimate();" method="post" enctype="multipart/form-data">
     <section class="content">
          <!-- Full Width boxes (Stat box) -->
          <div class="row">
               <div class="col-md-12">
                    <div class="box box-success" id="box_model">
                         <div class="box-body">
                              <div class="form-group col-md-12" style="padding-top:10px;">
                                   <div class="pull-left">
                                        <label class="label label-danger label-color" style="font-size:15px;"><i
                                                  class="fa fa-car car-align"></i>ID de la
                                             Orden-<?php echo $invoice_id; ?></label>
                                        <div> <br />
                                             <label>Fecha estimada:</label>
                                             <input type="text" id="txtDeliveryDate" name="txtDeliveryDate"
                                                  value="<?php echo !empty($estimate_delivery_date) ? $estimate_delivery_date : date('d/m/Y') ;?>"
                                                  class="form-control datepicker" />
                                        </div>
                                   </div>
                                   <div class="pull-right">
                                        <button type="submit" class="btn btn-success btnsp"><span
                                                  class="print-txt">GUARDAR</span>&emsp;<i class="fa fa-save fa-2x"></i>
                                        </button>
                                        <a target="_blank" style="display:none;"
                                             href="<?php echo WEB_URL;?>/invoice/invoice.php?invoice_id=<?php echo $invoice_id; ?>"
                                             class="btn btn-warning btnsp"><i class="fa fa-edit fa-2x"></i><br />
                                             Generate Invoice</a>
                                   </div>
                              </div>
                         </div>
                    </div>
                    <div class="box box-success" id="box_model">
                         <div class="box-header">
                              <h3 class="box-title"><i class="fa fa-user"></i> Detalles del Cliente</h3>
                         </div>
                         <div class="box-body">
                              <div class="form-group col-md-12">
                                   <?php
                                      $row_customer = $wms->getCustomerInfoByCustomerId($link, $customer_id);
                                      if(!empty($row_customer)) {
                                        $image = WEB_URL . 'img/no_image.jpg';
                                        if(file_exists(ROOT_PATH . '/img/upload/' . $row_customer['image']) && $row_customer['image'] != ''){
                                          $image = WEB_URL . 'img/upload/' . $row_customer['image']; //car image
                                        }
                                    ?>
                                   <div style="width:98%;height:auto;border:solid 1px #ccc;padding:10px;margin:10px;">
                                        <div class="col-md-3 text-left"><img class="img-thumbnail"
                                                  style="width:150px;height:150px;" src="<?php echo $image; ?>" /></div>
                                        <div class="col-md-3 text-left">
                                             <div>
                                                  <h4 style="font-weight:bold;">
                                                       <u><?php echo $row_customer['c_name']; ?></u>
                                                  </h4>
                                             </div>
                                             <div><b>Email:</b> <?php echo $row_customer['c_email']; ?></div>
                                             <div><b>Dirección:</b> <?php echo $row_customer['c_address']; ?></div>
                                        </div>
                                        <div class="col-md-3 text-left">
                                             <div>
                                                  <h4 style="font-weight:bold;">&nbsp;</h4>
                                             </div>
                                             <div><b>Home Telephone:</b> <?php echo $row_customer['c_home_tel']; ?>
                                             </div>
                                             <div><b>Work Telephone:</b> <?php echo $row_customer['c_work_tel']; ?>
                                             </div>
                                             <div><b>Mobile:</b> <?php echo $row_customer['c_mobile']; ?></div>
                                        </div>
                                        <div style="clear:both;">&nbsp;</div>
                                   </div>
                                   <?php } ?>
                              </div>
                         </div>
                         <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                    <div class="box box-success" id="box_model">
                         <div class="box-header">
                              <h3 class="box-title"><i class="fa fa-car"></i> Reparación de Máquina</h3>
                         </div>
                         <div class="box-body">
                              <div class="form-group col-md-12">
                                   <?php
                                    $row_car = $wms->getCustomerCarInfoByCardId($link, $car_id);
                                    if(!empty($row_car)) {
                                      $image_car = WEB_URL . 'img/no_image.jpg';
                                      if(file_exists(ROOT_PATH . '/img/upload/' . $row_car['image']) && $row_customer['image'] != ''){
                                        $image_car = WEB_URL . 'img/upload/' . $row_car['image']; //car image
                                      }
                                      //$percent_value = $row_car['work_status'];
                                    ?>
                                   <div style="width:98%;height:auto;border:solid 1px #ccc;padding:10px;margin:10px;">
                                        <div class="col-md-3 text-left"><img class="img-thumbnail"
                                                  style="width:150px;height:150px;" src="<?php echo $image_car; ?>" />
                                        </div>
                                        <div class="col-md-3 text-left">
                                             <div>
                                                  <h4 style="font-weight:bold;">
                                                       <u><?php echo $row_car['car_name']; ?></u>
                                                  </h4>
                                             </div>
                                             <div><b>Make:</b> <?php echo $row_car['make_name']; ?></div>
                                             <div><b>Model:</b> <?php echo $row_car['model_name']; ?></div>
                                             <div><b>Year:</b> <?php echo $row_car['model_name']; ?></div>
                                             <div><b>Chasis No:</b> <?php echo $row_car['chasis_no']; ?></div>
                                             <div><b>VIN#:</b> <?php echo $row_car['VIN']; ?></div>
                                        </div>
                                        <div class="col-md-3 text-left">
                                             <div>
                                                  <h4>&nbsp;</h4>
                                             </div>
                                             <div><b>Slot Machine Registration No:</b> <?php echo $row_car['car_reg_no']; ?>
                                             </div>
                                             <div><b>Added Date:</b> <?php echo $row_car['added_date']; ?></div>
                                             <!-- <div><b>Delivery Date:</b> <?php echo $row_car['delivary_date']; ?></div>-->
                                             <!--<div><b>Job Status:</b> <span style="font-size:12px;" class="label label-<?php if($row_car['job_status'] == '0'){echo 'danger';} else {echo 'success';} ?>">
                                             <?php if($row_car['job_status'] == '0'){echo 'Processing';} else {echo 'Done';} ?>
                                             </span></div>-->
                                        </div>
                                        <?php if((int)$percent_value > 0) { ?>
                                        <div class="col-md-3 text-left">
                                             <div>
                                                  <h4>&nbsp;</h4>
                                             </div>
                                             <div><b>Repair Progress:</b>
                                                  <div class="progress">
                                                       <div class="progress-bar progress-bar-success bar"
                                                            role="progressbar"
                                                            aria-valuenow="<?php echo $percent_value; ?>"
                                                            aria-valuemin="0" aria-valuemax="100"
                                                            style="width:<?php echo $percent_value; ?>%"></div>
                                                       <span><?php echo $percent_value; ?>%</span>
                                                  </div>
                                             </div>
                                        </div>
                                        <?php } ?>
                                        <div style="clear:both;">&nbsp;</div>
                                   </div>
                                   <?php } ?>
                              </div>
                         </div>
                         <!-- /.box-body -->
                    </div>
                    <!-- /.box -->

                    <div class="box box-success" id="box_model">
                      <div class="box-header">
                          <h3 class="box-title"><i class="fa fa-user"></i> Tècnico</h3>
                      </div>
                      <div class="box-body">
                          <div class="form-group col-md-12">
                                <?php
                                  $row_mechanic = $wms->getMechanicsInfoByMechanicsId($link, $mechanics_id);
                                  if(!empty($row_mechanic)) {
                                    $image = WEB_URL . 'img/no_image.jpg';
                                    if(file_exists(ROOT_PATH . '/img/employee/' . $row_mechanic['m_image']) && $row_mechanic['m_image'] != ''){
                                      $image = WEB_URL . 'img/employee/' . $row_mechanic['m_image']; //car image
                                    }
                                    // if($row_mechanic['m_image'] != ''){
                                    //   $image_sup = WEB_URL . 'img/employee/' . $row_mechanic['m_image'];
                                    //   $img_track = $row_mechanic['m_image'];
                                    // }
                                ?>
                                <div style="width:98%;height:auto;border:solid 1px #ccc;padding:10px;margin:10px;">
                                    <div class="col-md-3 text-left"><img class="img-thumbnail"
                                              style="width:150px;height:150px;" src="<?php echo $image; ?>" /></div>
                                    <div class="col-md-3 text-left">
                                          <div>
                                              <h4 style="font-weight:bold;">
                                                    <u><?php echo $row_mechanic['m_name']; ?></u>
                                              </h4>
                                          </div>
                                          <div><b>Costo Por Mes:</b> <?php echo $row_mechanic['m_cost']; ?></div>
                                          <div><b>Email:</b> <?php echo $row_mechanic['m_email']; ?></div>
                                          <div><b>Regalo Dirección:</b> <?php echo $row_mechanic['m_present_address']; ?></div>
                                    </div>
                                    <div class="col-md-3 text-left">
                                          <div>
                                              <h4 style="font-weight:bold;">&nbsp;</h4>
                                          </div>
                                          <div><b>Permanente Dirección:</b> <?php echo $row_mechanic['m_permanent_address']; ?></div>
                                          <div><b>Mobile:</b> <?php echo $row_mechanic['m_phone_number']; ?></div>
                                    </div>
                                    <div style="clear:both;">&nbsp;</div>
                                </div>
                                <?php } ?>
                          </div>
                      </div>
                      <!-- /.box-body -->
                    </div>

                    <div class="box box-success" id="box_model">
                         <div class="box-header">
                              <h3 class="box-title"><i class="fa fa-wrench"></i> Agregar o Editar la Orden</h3>
                         </div>
                         <div class="box-body">
                              <div class="form-group col-md-12">
                                   <div class="table-responsive">
                                        <table id="labour_table" class="table table-striped table-bordered table-hover">
                                             <thead>
                                                  <tr>
                                                       <th class="text-center"><b>Reparar</b></th>
                                                       <th class="text-center"><b>Reemplazar</b></th>
                                                       <th class="text-center"><b>Repuesto</b></th>
                                                       <th class="text-center"><b>Descripción</b></th>
                                                       <th class="text-center"><b>Precio(<?php echo $currency; ?>)</b>
                                                       </th>
                                                       <th class="text-center"><b>Cantidad</b></th>
                                                       <th class="text-center"><b>Mano de
                                                                 obra(<?php echo $currency; ?>)</b></th>
                                                       <th class="text-center"><b>Garantía</b></th>
                                                       <th class="text-center"><b>Total</b></th>
                                                       <th>&nbsp;</th>
                                                  </tr>
                                             </thead>
                                             <tbody>
                                                  <?php foreach($estimate_data as $estimate) {  ?>
                                                  <tr id="estimate-row<?php echo $row; ?>">
                                                       <td class="text-right">
                                                            <div class="checkbox"><label style="font-size: 1.7em"><input
                                                                           <?php if(isset($estimate->repair)){echo 'checked';}?>
                                                                           type="checkbox"
                                                                           name="estimate_data[<?php echo $row; ?>][repair]" /><span
                                                                           class="cr"><i
                                                                                class="cr-icon fa fa-check"></i></span></label>
                                                            </div>
                                                       </td>
                                                       <td class="text-right">
                                                            <div class="checkbox"><label style="font-size: 1.7em"><input
                                                                           <?php if(isset($estimate->replace)){echo 'checked';}?>
                                                                           type="checkbox"
                                                                           name="estimate_data[<?php echo $row; ?>][replace]" /><span
                                                                           class="cr"><i
                                                                                class="cr-icon fa fa-check"></i></span></label>
                                                            </div>
                                                       </td>
                                                       <td class="text-right"><button data-toggle="tooltip"
                                                                 title="Add Parts From Our Stock" type="button"
                                                                 name="estimate_data[<?php echo $row; ?>][button]"
                                                                 onClick=loadModal(<?php echo $row; ?>);
                                                                 class="btn btn-success btn-sm btnsp"><i
                                                                      class="fa fa-plus"></i></button>
                                                            <input type="hidden" id="parts_id_<?php echo $row; ?>"
                                                                 name="estimate_data[<?php echo $row; ?>][stock_parts]"
                                                                 value="<?php echo $estimate->stock_parts; ?>" />
                                                       </td>
                                                       <td class="text-right"><input id="parts_desc_<?php echo $row; ?>"
                                                                 type="text"
                                                                 value="<?php echo $estimate->discription; ?>"
                                                                 name="estimate_data[<?php echo $row; ?>][discription]"
                                                                 class="form-control parts_list" /></td>
                                                       <td class="text-right"><input type="text"
                                                                 id="price_<?php echo $row; ?>"
                                                                 name="estimate_data[<?php echo $row; ?>][price]"
                                                                 value="<?php echo $estimate->price; ?>"
                                                                 class="form-control eFire allownumberonly" /></td>
                                                       <td class="text-right"><input id="qty_<?php echo $row; ?>"
                                                                 type="text"
                                                                 name="estimate_data[<?php echo $row; ?>][quantity]"
                                                                 value="<?php echo $estimate->quantity; ?>"
                                                                 class="form-control eFire allownumberonly" /></td>
                                                       <td class="text-right"><input id="labour_<?php echo $row; ?>"
                                                                 type="text"
                                                                 name="estimate_data[<?php echo $row; ?>][labour]"
                                                                 value="<?php echo $estimate->labour; ?>"
                                                                 class="form-control eFire allownumberonly" /></td>
                                                       <td class="text-right"><input id="warranty_<?php echo $row; ?>"
                                                                 type="text"
                                                                 name="estimate_data[<?php echo $row; ?>][warranty]"
                                                                 value="<?php echo !empty($estimate->warranty) ? $estimate->warranty : ''; ; ?>"
                                                                 class="form-control eFire allownumberonly" /></td>
                                                       <td class="text-right"><input type="text"
                                                                 id="total_<?php echo $row; ?>"
                                                                 name="estimate_data[<?php echo $row; ?>][total]"
                                                                 value="<?php echo $estimate->total; ?>"
                                                                 class="form-control etotal allownumberonly" /></td>
                                                       <td class="text-left"><button type="button"
                                                                 onclick="$('#estimate-row<?php echo $row; ?>').remove();totalEstCost();"
                                                                 data-toggle="tooltip" title="Remove"
                                                                 class="btn btn-danger btn-sm"><i
                                                                      class="fa fa-close"></i></button></td>
                                                  </tr>
                                                  <?php $row++; } ?>
                                             </tbody>
                                             <tfoot>
                                                  <tr>
                                                       <td colspan="9"></td>
                                                       <td class="text-left"><button type="button"
                                                                 onclick="addEstimate();" data-toggle="tooltip"
                                                                 title="Add Estimate" class="btn btn-primary btn-sm"><i
                                                                      class="fa fa-plus"></i></button></td>
                                                  </tr>
                                                  <tr>
                                                       <td colspan="8" class="text-right">
                                                            Total(<?php echo $currency; ?>):</td>
                                                       <td><input id="total_price" type="text"
                                                                 value="<?php echo $total_cost; ?>" disabled="disabled"
                                                                 class="form-control allownumberonly" /></td>
                                                       <td>&nbsp;</td>
                                                  </tr>
                                                  <tr>
                                                       <td colspan="8" class="text-right">
                                                            Pagado(<?php echo $currency; ?>):</td>
                                                       <td><input id="total_paid" type="text"
                                                                 value="<?php echo $total_paid; ?>" disabled="disabled"
                                                                 class="form-control allownumberonly" /></td>
                                                       <td>&nbsp;</td>
                                                  </tr>
                                                  <tr>
                                                       <td colspan="8" class="text-right">
                                                            Saldo(<?php echo $currency; ?>):</td>
                                                       <td><input id="total_due" type="text"
                                                                 value="<?php echo $total_due; ?>" disabled="disabled"
                                                                 class="form-control allownumberonly" /></td>
                                                       <td>&nbsp;</td>
                                                  </tr>
                                             </tfoot>
                                        </table>
                                   </div>
                              </div>
                         </div>
                    </div>
                    <div class="box box-success" id="box_model">
                         <div class="box-header">
                              <h3 class="box-title"><i class="fa fa-cogs"></i> Set Working Progress</h3>
                         </div>
                         <div class="box-body">
                              <div class="form-group col-md-12">
                                   <div id="slider-connect"></div>
                              </div>
                              <div class="form-group col-md-12">
                                   <div class="progress">
                                        <div class="progress-bar progress-bar-success bar" id="work_progress"
                                             role="progressbar" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"
                                             style="width:<?php echo $percent_value; ?>%"></div>
                                        <span id="range_percent"><?php echo $percent_value; ?>%</span>
                                   </div>
                              </div>
                         </div>
                    </div>
                    <div class="box box-success" id="box_model">
                         <div class="box-header">
                              <h3 class="box-title"><i class="fa fa-envelope-o"></i> Customer notification</h3>
                         </div>
                         <div class="box-body">
                              <div class="form-group pull-left">
                                   <label for="chkemailnotification">Email Notification?</label>
                                   <div class="checkbox">
                                        <label style="font-size: 2em">
                                             <input type="checkbox" id="chkemailnotification"
                                                  name="email_notification" />
                                             <span class="cr"><i class="cr-icon fa fa-check"></i></span></label>
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>
          </div>
          <input type="hidden" id="hf_work_progress" name="hf_work_progress" value="<?php echo $percent_value; ?>" />
          <input type="hidden" name="hfInvoiceId" value="<?php echo $invoice_id; ?>" />
          <input type="hidden" name="hfCarId" value="<?php echo $car_id ; ?>" />
          <input type="hidden" name="hfCustomerId" value="<?php echo $customer_id; ?>" />
          <input type="hidden" name="hfMechanicsId" value="<?php echo $mechanics_id; ?>" />
          <input type="hidden" id="hfTotalCost" name="hfTotalCost" value="<?php echo $total_cost; ?>" />
          <input type="hidden" id="hfDue" name="hfDue" value="<?php echo $total_due; ?>" />
     </section>
</form>

<script type="text/javascript">    
var row = <?php echo $row; ?>;
function addEstimate() {
     html = '<tr id="estimate-row' + row + '">';
     html +=
          '  <td class="text-right"><div class="checkbox"><label style="font-size: 1.7em"><input type="checkbox" name="estimate_data[' +
          row + '][repair]" /><span class="cr"><i class="cr-icon fa fa-check"></i></span></label></div></td>';
     html +=
          '  <td class="text-right"><div class="checkbox"><label style="font-size: 1.7em"><input type="checkbox" name="estimate_data[' +
          row + '][replace]" /><span class="cr"><i class="cr-icon fa fa-check"></i></span></label></div></td>';
     html +=
          '  <td class="text-right"><button data-toggle="tooltip" title="Add Parts From Our Stock" type="button" name="estimate_data[' +
          row + '][button]" onClick=loadModal(' + row +
          '); class="btn btn-success btn-sm btnsp"><i class="fa fa-plus"></i></button><input type="hidden" id="parts_id_' +
          row + '" name="estimate_data[' + row + '][stock_parts]" value="0" /></td>';
     html += '  <td class="text-right"><input id="parts_desc_' + row + '" type="text" name="estimate_data[' + row +
          '][discription]" class="form-control parts_list" /></td>';
     html += '  <td class="text-right"><input type="text" id="price_' + row + '" name="estimate_data[' + row +
          '][price]" value="0.00" class="form-control eFire allownumberonly" /></td>';
     html += '  <td class="text-right"><input id="qty_' + row + '" type="text" name="estimate_data[' + row +
          '][quantity]" value="0" class="form-control eFire allownumberonly" /></td>';
     html += '  <td class="text-right"><input id="labour_' + row + '"  type="text" name="estimate_data[' + row +
          '][labour]" value="0.00" class="form-control eFire allownumberonly" /></td>';
     html += '  <td class="text-right"><input id="warranty_' + row + '"  type="text" name="estimate_data[' + row +
          '][warranty]" value="" class="form-control" /></td>';
     html += '  <td class="text-right"><input type="text" id="total_' + row + '" name="estimate_data[' + row +
          '][total]" value="0.00" class="form-control etotal allownumberonly" /></td>';
     html += '  <td class="text-left"><button type="button" onclick="$(\'#estimate-row' + row +
          '\').remove();totalEstCost();" data-toggle="tooltip" title="Remove" class="btn btn-danger btn-sm"><i class="fa fa-close"></i></button></td>';
     html += '</tr>';
     $('#labour_table tbody').append(html);
     row++;
     reloadQtyRow();
     numberAllow();
}

$(document).ready(function() {
     var connectSlider = document.getElementById('slider-connect');
     noUiSlider.create(connectSlider, {
          start: <?php echo $percent_value; ?>,
          connect: [true, false],
          step: 1,
          range: {
               'min': 0,
               'max': 100
          }
     });
     var directionField = document.getElementById('range_percent');
     connectSlider.noUiSlider.on('update', function(values, handle) {
          directionField.innerHTML = parseInt(values[handle]) + '%';
          $("#work_progress").css("width", parseInt(values[handle]) + '%');
          $("#hf_work_progress").val(parseInt(values[handle]));
     });
     reloadQtyRow();
});

function reloadSelect() {
     $(".chzn-select").chosen({
          allow_single_deselect: true
     });
}


// Category
/*function autocompleteFireEvent() {
	$.ajax({
		url: "<?php //echo WEB_URL; ?>ajax/getpartslist.php?filter_name=" +  encodeURIComponent(request),
		dataType: 'json',
		success: function(json) {
			
		}
	});
}*/

function validateEstimate() {
     if (parseInt($("#total_price").val()) > 0) {
          return true;
     } else {
          alert("Have you forgot to estimate car ? go to Add/Update Estimate section and click Plus icon.");
          return false;
     }
}

function addDataToEstimate(obj, parts_id, price, qty, warranty) {
     if (parseInt(qty) > 0) {
          var row = $("#estimate_row").val();
          var parts_name = $(obj).find(".parts_name").html();
          $("#parts_desc_" + row).val(parts_name);
          $("#price_" + row).val(price);
          $("#qty_" + row).val('1');
          $("#total_" + row).val(price);
          $("#parts_id_" + row).val(parts_id);
          $("#warranty_" + row).val(warranty);
          totalEstCost();
          $("#filter_popup").modal("hide")
     } else {
          alert("Stock Empty so you cannot add parts");
     }
}

function loadModal(row) {
     $("#estimate_row").val(row);
     $("#filter_popup").modal("show")
}

</script>
<div id="filter_popup" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
     <div class="modal-dialog">
          <div class="modal-content">
               <div class="modal-header green_header">
                    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
                              aria-hidden="true"><i class="fa fa-close"></i></span></button>
                    <h3 class="modal-title">Filter Parts</h3>
               </div>
               <div class="modal-body">
                    <div class="box box-info" id="box_model">
                         <div class="box-body">
                              <div class="box-header">
                                   <h3 class="box-title"><i class="fa fa-search"></i> Search Parts</h3>
                              </div>
                              <div class="form-group col-md-4">
                                   <label for="ddlMake">Make :</label>
                                   <select class="form-control" onchange="loadYear(this.value);" name="ddlMake"
                                        id="ddlMake">
                                        <option value=''>--Select Make--</option>
                                        <?php
						$make_list = $wms->get_all_make_list($link);
						foreach($make_list as $make) {
							echo "<option value='".$make['make_id']."'>".$make['make_name']."</option>";
						}
					
					?>
                                   </select>
                              </div>
                              <div class="form-group col-md-4">
                                   <label for="ddl_model">Model :</label>
                                   <select class="form-control" onchange="loadYearData(this.value);" name="ddlModel"
                                        id="ddl_model">
                                        <option value=''>--Select Model--</option>
                                   </select>
                              </div>
                              <div class="form-group col-md-4">
                                   <label for="ddlYear">Year :</label>
                                   <select class="form-control" name="ddlYear" onchange="loadPartsData();" id="ddlYear">
                                        <option value=''>--Select Year--</option>
                                   </select>
                              </div>
                              <div class="form-group col-md-12">
                                   <label for="txtPartsName">Type parts name :</label>
                                   <input class="form-control" type="text" name="txtPartsName" id="txtPartsName" />
                              </div>
                              <div class="form-group col-md-12">
                                   <div align="center" class="page_loader"><img
                                             src="<?php echo WEB_URL; ?>/img/ajax-loader.gif" /></div>
                                   <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-hover">
                                             <thead>
                                                  <tr>
                                                       <td class="text-center"><b>Image</b></td>
                                                       <td class="text-center"><b>Name</b></td>
                                                       <td class="text-center"><b>Price</b></td>
                                                       <td class="text-center"><b>Warranty</b></td>
                                                       <td class="text-center"><b>Quantity</b></td>
                                                  </tr>
                                             </thead>
                                             <tbody id="laod_parts_data">
                                             </tbody>
                                        </table>
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>
          </div>
          <!-- /.modal-content -->
     </div>
</div>
<input type="hidden" id="estimate_row" value="0" />
<?php include('../footer.php'); ?>