<?php include_once('../workshop_admin_panel/header.php'); ?>
<section class="content-header">
  <h1><i class="fa fa-calculator"></i> Aproximate Repair Estimate</h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Sample Estimate</li>
  </ol>
  <div>&nbsp;</div>
</section>
<!-- Main content -->
<form onsubmit="return validateEstimate();" method="post" enctype="multipart/form-data">
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-success" id="box_model">
          <div class="box-header">
            <h3 class="box-title"><i class="fa fa-wrench"></i> Sample Estimate</h3>
          </div>
          <div class="box-body">
            <div class="form-group col-md-12">
              <div class="table-responsive">
                <table id="labour_table" class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <th class="text-center"><b>Repair</b></th>
                      <th class="text-center"><b>Replace</b></th>
                      <th class="text-center"><b>Parts</b></th>
                      <th class="text-center"><b>Description</b></th>
                      <th class="text-center"><b>Price(<?php echo $currency; ?>)</b></th>
                      <th class="text-center"><b>Quantity</b></th>
                      <th class="text-center"><b>Labour(<?php echo $currency; ?>)</b></th>
                      <th class="text-center"><b>Warranty</b></th>
                      <th class="text-center"><b>Total</b></th>
                      <th>&nbsp;</th>
                    </tr>
                  </thead>
                  <tbody>
                    
                  </tbody>
                  <tfoot>
                    <tr>
                      <td colspan="9"></td>
                      <td class="text-left"><button type="button" onclick="addEstimate();" data-toggle="tooltip" title="Add Estimate" class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i></button></td>
                    </tr>
                    <tr>
                      <td colspan="8" class="text-right">Total(<?php echo $currency; ?>):</td>
                      <td><input id="total_price" type="text" value="0.00" disabled="disabled" class="form-control allownumberonly" /></td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td colspan="8" class="text-right">Paid(<?php echo $currency; ?>):</td>
                      <td><input id="total_paid" type="text" value="0.00" disabled="disabled" class="form-control allownumberonly" /></td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td colspan="8" class="text-right">Due(<?php echo $currency; ?>):</td>
                      <td><input id="total_due" type="text" value="0.00" disabled="disabled" class="form-control allownumberonly" /></td>
                      <td>&nbsp;</td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</form>
<script type="text/javascript"><!--
var row = 0;
function addEstimate() {
	html  = '<tr id="estimate-row' + row + '">';
    html += '  <td class="text-right"><div class="checkbox"><label style="font-size: 1.7em"><input type="checkbox" name="estimate_data[' + row + '][repair]" /><span class="cr"><i class="cr-icon fa fa-check"></i></span></label></div></td>';
	html += '  <td class="text-right"><div class="checkbox"><label style="font-size: 1.7em"><input type="checkbox" name="estimate_data[' + row + '][replace]" /><span class="cr"><i class="cr-icon fa fa-check"></i></span></label></div></td>';
	html += '  <td class="text-right"><button data-toggle="tooltip" title="Add Parts From Our Stock" type="button" name="estimate_data[' + row + '][button]" onClick=loadModal(' + row + '); class="btn btn-info btnsp btn-sm"><i class="fa fa-plus"></i></button><input type="hidden" id="parts_id_' + row + '" name="estimate_data[' + row + '][stock_parts]" value="0" /></td>';
	html += '  <td class="text-right"><input id="parts_desc_' + row + '" type="text" name="estimate_data[' + row + '][discription]" class="form-control parts_list" /></td>';
	html += '  <td class="text-right"><input type="text" id="price_' + row + '" name="estimate_data[' + row + '][price]" value="0.00" class="form-control eFire allownumberonly" /></td>';
	html += '  <td class="text-right"><input id="qty_' + row + '" type="text" name="estimate_data[' + row + '][quantity]" value="0" class="form-control eFire allownumberonly" /></td>';
	html += '  <td class="text-right"><input id="labour_' + row + '"  type="text" name="estimate_data[' + row + '][labour]" value="0.00" class="form-control eFire allownumberonly" /></td>';
	html += '  <td class="text-right"><input id="warranty_' + row + '"  type="text" name="estimate_data[' + row + '][warranty]" value="" class="form-control" /></td>';
	html += '  <td class="text-right"><input type="text" id="total_' + row + '" name="estimate_data[' + row + '][total]" value="0.00" class="form-control etotal allownumberonly" /></td>';
	html += '  <td class="text-left"><button type="button" onclick="$(\'#estimate-row' + row + '\').remove();totalEstCost();" data-toggle="tooltip" title="Remove" class="btn btn-danger btn-sm"><i class="fa fa-minus-circle"></i></button></td>';
	html += '</tr>';
	$('#labour_table tbody').append(html);
	row++;
	reloadQtyRow();
	numberAllow();
}

function reloadSelect() {
	$(".chzn-select").chosen({allow_single_deselect:true});
}


function addDataToEstimate(obj, parts_id, price, qty, warranty) {
	if(parseInt(qty) > 0) {
		var row = $("#estimate_row").val();
		var parts_name = $(obj).find(".parts_name").html();
		$("#parts_desc_"+row).val(parts_name);
		$("#price_"+row).val(price);
		$("#qty_"+row).val('1');
		$("#total_"+row).val(price);
		$("#parts_id_"+row).val(parts_id);
		$("#warranty_"+row).val(warranty);
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
//--></script>
<div id="filter_popup" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header green_header">
        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><i class="fa fa-close"></i></span></button>
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
              <select class="form-control" onchange="loadYear(this.value);" name="ddlMake" id="ddlMake">
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
              <select class="form-control" onchange="loadYearData(this.value);" name="ddlModel" id="ddl_model">
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
              <input class="form-control" type="text" name="txtPartsName" id="txtPartsName"/>
            </div>
            <div class="form-group col-md-12">
              <div align="center" class="page_loader"><img src="<?php echo WEB_URL; ?>/img/ajax-loader.gif" /></div>
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
