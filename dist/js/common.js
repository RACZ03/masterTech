/*menu handler*/
/*$(function(){
  function stripTrailingSlash(str) {
    if(str.substr(-1) == '/') {
      return str.substr(0, str.length - 1);
    }
    return str;
  }

  var url = window.location.pathname;  
  var activePage = stripTrailingSlash(url);
  $('.sidebar-menu li a').each(function(){  
    var currentPage = stripTrailingSlash($(this).attr('href'));
    if (activePage == currentPage) {
      $(this).parent().addClass('active');
    } 
  });
});*/
// here we done delete function for all page
var gobal_url = '';
function deleteMe(url){
	if(url != ''){
		var iAnswer = confirm("Are you sure you want to delete this row ?");
		if(iAnswer){
			window.location.href = url;
		}
	}
}

jQuery( document ).ready(function() {
	setTimeout(function() {
		  $("#me").hide(300);
		  $("#you").hide(300);
	}, 3000);
});

jQuery(document).ready(function() {
	if(jQuery(".js-example-basic-single").length > 0) {
		jQuery(".js-example-basic-single").select2();
	}
});

//return ajax date for dropdownlist
function getDropdownlistDate(val,token,ddl_name){
	$.get("../ajax/ajax_response.php?id="+val+'&token='+token, function(data, status){
        if(data != 0){
			$("#"+ddl_name).html(data);
		}
		else{
			alert("Bad Request");
		}
    });
}

//return ajax date for dropdownlistWard
function getDropdownlistWard(val,token,ddl_name){
	$.get("../ajax/ajax_response_ward.php?id="+val+'&token='+token, function(data, status){
        if(data != 0){
			$("#"+ddl_name).html(data);
		}
		else{
			alert("Bad Request");
		}
    });
}

//here we get patient details for diagonostic
function getDiagonosticPatientDetailsById(){
	var pid = $("#txtPtId").val();
	if(pid != ''){
		$.get("../ajax/getPatient.php?pid="+pid, function(data, status){
			if(data != 0){
				var xid = data.split(",");
				$("#txtPathologyName").val(xid[0]);
				$("#txtPathologyEmail").val(xid[1]);
				$("#txtPathologyConNo").val(xid[2]);
				$("#txtareaAddress").html(xid[3]);
			}
			else{
				alert("No Information Found");
			}
		});
	}
	else{
		alert("Patient Id Required");
	}
}

//here we get patient details for physiotherapy
function getPatientDetailsById(){
	var pid = $("#txtPtId").val();
	if(pid != ''){
		$.get("../ajax/getPatient.php?pid="+pid, function(data, status){
			if(data != 0){
				var xid = data.split(",");
				$("#txtPhyName").val(xid[0]);
				$("#txtPhyEmail").val(xid[1]);
				$("#txtPhyConNo").val(xid[2]);
				$("#txtPhyAddress").html(xid[3]);
			}
			else{
				alert("No Information Found");
			}
		});
	}
	else{
		alert("Patient Id Required");
	}
}

function getBloodUnitPrice(gn,type,box_name){
	if(gn != ''){
		$.get("../ajax/getBloodGroupPrice.php?group_name="+gn+'&type='+type, function(data, status){
			$("#"+box_name).val(data);
		});
	}
	else{
		alert('Please Select Blood Group');
		$("#"+box_name).val('0.00');
	}
}

function changeDonourMode(value){
	if(value == 'Hospital Purpose'){
		getBloodUnitPrice($("#selBloodGrp").val(),'b','txtDrblprice');
	}
	else{
		$("#txtDrblprice").val('0.00');
	}
}



function calPrice(){
	calculateGrandTotal();
}

function saveUserInfo(){
	alert('Update profile information successfully');
	$(window).colorbox.close();
}

//$('.time').mask('00:00');



function numberAllow() {
	$(".allownumberonly").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode == 65 && ( e.ctrlKey === true || e.metaKey === true ) ) || 
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
}

//here for allownumbers only
$(document).ready(function() {
    gobal_url = $("#web_url").val();
	numberAllow();
	
	// Override summernotes image manager
	//var url = '';
	//var filename = '';
	//$('.summernote').summernote('insertImage', url, filename);
	$('.summernote').each(function() {
		var element = this;
		
		$(element).summernote({
			disableDragAndDrop: true,
			height: 300,
			/*toolbar: [
				['style', ['style']],
				['font', ['bold', 'underline', 'clear']],
				['fontname', ['fontname']],
				['color', ['color']],
				['para', ['ul', 'ol', 'paragraph']],
				['table', ['table']],
				['insert', ['link', 'image', 'video']],
				['view', ['fullscreen', 'codeview', 'help']]
			]*/
			/*buttons: {
    			image: function() {
					var ui = $.summernote.ui;

					// create button
					var button = ui.button({
						contents: '<i class="fa fa-image" />',
						tooltip: $.summernote.lang[$.summernote.options.lang].image.image,
						click: function () {
							$('#modal-image').remove();
						
							$.ajax({
								url: 'index.php?route=common/filemanager&token=' + getURLVar('token'),
								dataType: 'html',
								beforeSend: function() {
									$('#button-image i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
									$('#button-image').prop('disabled', true);
								},
								complete: function() {
									$('#button-image i').replaceWith('<i class="fa fa-upload"></i>');
									$('#button-image').prop('disabled', false);
								},
								success: function(html) {
									$('body').append('<div id="modal-image" class="modal">' + html + '</div>');
									
									$('#modal-image').modal('show');
									
									$('#modal-image a.thumbnail').on('click', function(e) {
										e.preventDefault();
										
										$(element).summernote('insertImage', $(this).attr('href'));
																	
										$('#modal-image').modal('hide');
									});
								}
							});						
						}
					});
				
					return button.render();
				}
  			}*/
		});
	});
	
	
	//here for aditional price	
	/*$("#txtEdiPrice").keyup(function() {
		 if(isTestSelected()){
			  calculateGrandTotal();
		  }
		  else{
		  	$("#txtEdiPrice").val('0.00');
		  }
		 
	});*/
	//here for discount
	/*$("#txtDiscount").keyup(function() {
		 if(forDiscountValidation()){
			calculateGrandTotal();
		 }
		 else{
		 	$("#txtDiposite").val('0.00');
		 }
	});*/
	//here deposit calculation
	/*$("#txtDiposite").keyup(function() {
		 if(isTestSelected()){
			calculateGrandTotal();
		 }
		 else{
		 	$("#txtDiposite").val('0.00');
		 }
	});*/
	
	//here for physiotherapy
	/*$(".phyppc").keyup(function() {
		getTotalPhyPrice();
	});*/
	
	/*function resetBloodPrice(){
		alert('');
		$("#txtBQty").val('0');
		$("#txtBTotalPrice").val('0.00');
	}*/
	
	/*$("#txtUPrice").keyup(function() {
		bloodPriceCalculate();
	});
	$("#txtBQty").keyup(function() {
		bloodPriceCalculate();
	});
	
	$("#txtStockItemQty").keyup(function() {
		stockPriceCalculation();
	});
	$("#txtStockItemPrice").keyup(function() {
		stockPriceCalculation();
	});*/
	
	/*use for WMS*/
	$("#total_paid,#total_discount").keyup(function() {
		deliveryInvoiceCalculation();
	});
	$("#txtBuyPrice").keyup(function() {
		calculateWMSBuyPrice();
	});
	$("#txtGivamount").keyup(function() {
		 calculateWMSBuyPrice();
	});
	//
	/*use for WMS*/
	$(".ssprice").keyup(function() {
		calculateWMSCommon();
	});
	$(".ssquantity").keyup(function() {
		 calculateWMSCommon();
	});
	$(".ssgiven").keyup(function() {
		 calculateWMSCommon();
	});
	$("#txtSellDiscount, #txtSellPaidamount").keyup(function() {
		partsSellDiscount();
	});
	//parts buy event
	$(".ppcal").keyup(function() {
		partsBuyPriceCalculation();
	});
	
});

//using wms
function reloadQtyRow() {
	$(".eFire").keyup(function() {
		estimateTotalCalculate(this.id);
	});
}

function partsBuyPriceCalculation() {
	var parts_price = 0;
	var qty = 0;
	var given_amount = 0;
	
	if($("#buy_prie").val() != '') {
		parts_price = $("#buy_prie").val();
	}
	if($("#parts_quantity").val() != '') {
		qty = $("#parts_quantity").val();
	}
	if($("#given_amount").val() != '') {
		given_amount = $("#given_amount").val();
	}
	var total = parseFloat(parseFloat(parts_price) * parseInt(qty));
	var ptotal = parseFloat(parseFloat(parseFloat(parts_price) * parseInt(qty)) - parseFloat(given_amount));
	ptotal = ptotal.toFixed(2);
	total = total.toFixed(2);
	$("#pending_amount").val(ptotal);
	$("#total_amount").val(total);
}

function partsSellDiscount() {
	var discount = 0;
	var sellPaid = 0;
	var total_after_discount = 0;
	//
	if($("#txtSellDiscount").val() != '') {
		discount = $("#txtSellDiscount").val();
	}
	//
	if($("#txtSellPaidamount").val() != '') {
		sellPaid = $("#txtSellPaidamount").val();
	}
	//
	var total_price = $("#hdntotal").val();
	
	if(parseInt(discount) > 0) {
		total_after_discount = parseFloat(parseFloat(parseFloat(total_price) * parseFloat(discount)) / 100);
	}
	total_price = parseFloat(total_price) - parseFloat(total_after_discount);
	
	//due work
	var due_amount = parseFloat(total_price) - parseFloat(sellPaid);
	$("#hdn_due_amount").val(parseFloat(due_amount).toFixed(2));
	due_amount = (due_amount < 0) ? due_amount * -1 : due_amount;
	due_amount = numberWithCommas(parseFloat(due_amount).toFixed(2));
	$("#due_amount").html(due_amount);
	$("#hdn_grand_total").val(parseFloat(total_price).toFixed(2));
	total_price = numberWithCommas(parseFloat(total_price).toFixed(2));
	$("#grand_total").html(total_price);
	//$("#due_amount").html(total_price);
}

function deliveryInvoiceCalculation() {
	var total_price = 0;
	var total_due = 0;
	var discount = 0;
	var paid = 0;
	var total_after_discount = 0;
	var xtotal = 0;
	
	if($("#total_discount").val() != '') {
		discount = $("#total_discount").val();
	}
	if($("#total_price").val() != '') {
		total_price = $("#total_price").val();
	}
	/*if($("#total_due").val() != '') {
		total_due = $("#total_due").val();
	}*/
	if($("#total_paid").val() != '') {
		paid = $("#total_paid").val();
	}
	
	if(parseInt(discount) > 0) {
		total_after_discount = parseFloat(parseFloat(parseFloat(total_price) * parseFloat(discount)) / 100);
	}
	total_price = parseFloat(total_price) - parseFloat(total_after_discount);
	xtotal = total_price.toFixed(2);
	total_price = parseFloat(total_price) - parseFloat(paid);
	total_price = total_price.toFixed(2);
	$("#total_grand_total").val(xtotal);
	$("#total_due").val(total_price);
}

//usign wms
function estimateTotalCalculate(id) {
	var total = 0;
	var labour = 0;
	var qty = 0;
	var price = 0;
	
	var row_id = id.split('_')[1];

	if($("#labour_"+row_id).val() != '') {
		labour = $("#labour_"+row_id).val();
	}
	if($("#qty_"+row_id).val() != '') {
		qty = $("#qty_"+row_id).val();
	}
	if($("#price_"+row_id).val() != '') {
		price = $("#price_"+row_id).val();
	}
	if(parseInt(qty) > 0) {
		total = parseInt(qty) * parseFloat(price);
	} else if(parseInt(qty) == 0) {
		total = parseFloat(price);
	}
	total = parseFloat(total) + parseFloat(labour);
	total = parseFloat(total).toFixed(2);
	$("#total_"+row_id).val(total);
	totalEstCost();
}

//est total calculation
function totalEstCost() {
	var total = 0;
	if($(".etotal").length > 0) {
		for(var i=0;i<$(".etotal").length;i++) {
			total += parseFloat($(".etotal")[i].value);
		}
	}
	total = parseFloat(total).toFixed(2);
	$("#total_price").val(total);
	$("#hfTotalCost").val(total);
	
	//due and done
	var payment_done = $("#total_paid").val();
	var payment_due = parseFloat(total) - parseFloat(payment_done);
	payment_due = parseFloat(payment_due).toFixed(2);
	$("#total_due").val(payment_due);
	$("#hfDue").val(payment_due);
}

//using WMS
function calculateWMSCommon() {
	var price = 0;
	var quantity = 0
	var given = 0
	
	if($(".ssprice").val() != '') {
		price = $(".ssprice").val();
	}
	if($(".ssquantity").val() != '') {
		quantity = $(".ssquantity").val();
	}
	if($(".ssgiven").val() != '') {
		given = $(".ssgiven").val();
	}
	var total = parseFloat(price) * parseInt(quantity);
	total = parseFloat(total) - parseFloat(given);
	total = parseFloat(total).toFixed(2);
	$(".ssresult").val(total);
}

//using WMS
function calculateWMSBuyPrice() {
	var buy_price = 0;
	var given_price = 0
	if($("#txtBuyPrice").val() != '') {
		buy_price = $("#txtBuyPrice").val();
	}
	if($("#txtGivamount").val() != '') {
		given_price = $("#txtGivamount").val();
	}
	var total = parseFloat(buy_price) - parseFloat(given_price);
	total = parseFloat(total).toFixed(2);
	$("#txtDue").val(total);
	if($("#hdn_due_amount").length > 0) {
		$("#hdn_due_amount").val(total);
	}
}

function stockPriceCalculation(){
	var qty = 0;
	var item_price = 0.00;
	var total = 0.00;
	if($("#txtStockItemPrice").val() != ''){
		item_price = $("#txtStockItemPrice").val();
	}
	if($("#txtStockItemQty").val() != ''){
		qty = $("#txtStockItemQty").val();
	}
	total = parseFloat(item_price) * parseInt(qty);
	total = total.toFixed(2);
	$("#txtStockTotalAmount").val(total);
}

function getMedicineInfor(val,type,fill){
	if(val != ''){
		$.get("../ajax/getMedicineInfo.php?val="+val+'&type='+type, function(data, status){
			var xplore = data.split("~");
			$("#"+fill).val(xplore[0]);
			$("#txtCompanyName").val(xplore[1]);
		});
	}
}

function getToken(){
	var rid = $("#txtTokenId").val();
	window.location.href = "use_token.php?rid=" + rid;
}

function getReport(){
	var rid = $("#txtReportId").val();
	window.location.href = "report_delivery.php?rid=" + rid;
}

function openDialogPopup(){
  $( "#dialog-message" ).dialog({
	modal: true,
	width:400,
	height:300
  });
}
//Report Delivery here
function updateDeliveryStatus(pid){
	if(pid != ''){
		var isdel = 0;
		if(document.getElementById('chkDeliveryStatus').checked){
			isdel = 1;
		}
		$("#meAjax").show();
		$.get("ajax/updateDeliveryStatus.php?pid=" + pid + '&pay=' + $("#txtAddDueMoney").val() + '&isdel=' + isdel, function(data, status){
			alert("Update Record Successfully");
			window.location.reload();
		});
	}
}

//Report Delivery here
function getEmailAddress(empid,dcolume,fill){
	if(empid != ''){
		$.get("../ajax/getemployee.php?empid=" + empid + '&dcolume=' + dcolume, function(data, status){
			if(data != ''){
				var xdata = data.split(":");
				$("#" + fill).val(xdata[0]);
				$("#txtBranchName").val(xdata[1]);
			}
			else{
				alert('No information found');
				$("#" + fill).val('');
				$("#txtBranchName").val('');
			}
		});
	}
}

//login form submit
function validateLoginForm(){
	var bcon = true;
	if($("#username").val() == ''){
		alert("Email Required");
		$("#username").focus();
		bcon = false;
	}
	else if(!checkEmail('username')){
		bcon = false;
	}
	else if($("#password").val() == ''){
		alert("Please enter your password");
		$("#password").focus();
		bcon = false;
	}
	else if($("#ddlLoginType").val() == '-1'){
		alert("Please select login type");
		bcon = false;
	}
	else if($("#ddlBranch").val() == '-1'){
		alert("Please select Your Branch");
		bcon = false;
	}
	return bcon;
}

function checkEmail(txtEmail) {
    var email = document.getElementById(txtEmail);
    var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if (!filter.test(email.value)) {
   	 	alert('Please provide a valid email address');
    	email.focus;
    	return false;
 	}
	return true;
}

function getDepartment(val){
	if(val != ''){
		if(val == '3'){
			$("#deplogin").show();
		}
		else{
			$("#deplogin").hide();
		}
	}
	else{
		alert('Select Login Type');
		$("#deplogin").hide();
	}
}

function apointmentUpdate(obj){
	$("#sloader").show();
	var txt = $("#txt_" + obj.value).val();
	if(obj.checked){$.get("../ajax/updatedoctor.php?id=" + obj.value + '&status=1&txt=' + txt, function(data, status){$("#sloader").hide();});}
	else{$.get("../ajax/updatedoctor.php?id=" + obj.value + '&status=0&txt=' + txt, function(data, status){$("#sloader").hide();});}
}

$(function () {
	if($('input[type="checkbox"].minimal, input[type="radio"].minimal').length > 0) {
		$('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
		  checkboxClass: 'icheckbox_minimal-blue',
		  radioClass: 'iradio_minimal-blue'
		});
	}
});

var loadFile = function(event) {
	var output = document.getElementById('output');
	output.src = URL.createObjectURL(event.target.files[0]);
};

var previewImage = function(event,id) {
	var output = document.getElementById(id);
	output.src = URL.createObjectURL(event.target.files[0]);
};

$(document).ready(function() {
	if ( $(".sakotable") ) {
		// Siempre será validado, incluso si #undiv no existe
		if($('sakotable').length > 0) {
			$('.sakotable').dataTable({
			  "bPaginate": true,
			  "bLengthChange": true,
			  "bFilter": true,
			  "retrieve": true,
			  "bSort": true,
			  "bInfo": true,
			  "bAutoWidth": false
			  /*"dom": 'T<"clear">lfrtip',
				"tableTools": {
					"sSwfPath": gobal_url + "dist/swf/copy_csv_xls_pdf.swf",
					"aButtons": [
						"print",
						"csv",
						"xls",
						"pdf"
					]
				}*/
			});
		}
	}
});

//get date and time
$(function() {
	if($( ".datepicker" ).length > 0) {
		$( ".datepicker" ).datepicker({format: 'dd/mm/yyyy', autoclose: true});
	}
	/*if($('.tool_always').length > 0) {
		$('.tool_always').popover({
			placement: 'top'
			html: true,
			content: function() {
				return "Yes";
			},
		
			// We specify a template in order to set a class (an ID is overwritten) to the popover for styling purposes
			//template: '<div class="popover my-popover" role="tooltip"><div class="arrow"></div><div class="popover-content"></div></div>'
		});
		
		//$('.tool_always').popover('show', {position: 'top'});
	}*/
});

//get print for windows
function printContent(area,title){
	$("#"+area).printThis({
		 pageTitle: title
	});
}

//Report Delivery here
function updateDeliveryStatus(pid){
	if(pid != ''){
		var isdel = 0;
		if(document.getElementById('chkDeliveryStatus').checked){
			isdel = 1;
		}
		$("#meAjax").show();
		$.get("../ajax/updateDeliveryStatus.php?pid=" + pid + '&pay=' + $("#txtAddDueMoney").val() + '&isdel=' + isdel, function(data, status){
			alert("Update Record Successfully");
			window.location.reload();
		});
	}
}

function validateEmail(email) {
    var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

//get ajax parts list //uses
function loadPartsData(){
   var make_id = $("#ddlMake").val();
   var model_id = $("#ddl_model").val();
   var year_id = $("#ddlYear").val();
   $("#laod_parts_data").html('');
   $(".page_loader").show();
   if(make_id != '' && model_id != '' && year_id != ''){
	   $.ajax({
		  url: '../ajax/getpartslist.php',
		  type: 'POST',
		  data: 'make_id=' + make_id + '&model_id=' + model_id + '&year_id=' + year_id + '&token=mmy',
		  dataType: 'html',
		  success: function(data) {
			$("#laod_parts_data").html(data);
			$(".page_loader").hide();
		  }
	   });
   }
   $(".page_loader").hide();
}

//get ajax parts list //uses

$("#txtPartsName").keyup(function (e) {
	 $(".page_loader").show();
		$.ajax({
		  url: '../ajax/getpartslist.php',
		  type: 'POST',
		  data: 'filter_name=' + this.value + '&token=name',
		  dataType: 'html',
		  success: function(data) {
			$("#laod_parts_data").html(data);
			$(".page_loader").hide();
		  }
	 	});									
});

//get mechanics salary
function loadSalaryPayment(mid) {
	$.ajax({
		  url: '../ajax/getpartslist.php',
		  type: 'POST',
		  data: 'mid=' + mid + '&token=getsalaryamount',
		  dataType: 'html',
		  success: function(data) {
			jQuery("[name='txtFixsalary']").val(data);
		  }
	});
}

//get mechanics salary hour
function loadWorkedHour() {
	var mech_id = $("#ddlMechanicslist").val();
	var month_id = $("#ddlMonth").val();
	var year_id = $("#ddlYear").val();
	//
	if(mech_id != '' && month_id != '' && year_id != '') {
		$.ajax({
			  url: '../ajax/getpartslist.php',
			  type: 'POST',
			  data: 'mechanic_id=' + mech_id + '&month_id=' + month_id + '&year_id=' + year_id + '&token=getmothhour',
			  dataType: 'html',
			  success: function(data) {
				if(data != '') {
					jQuery("[name='txtTotaltime']").val(data);
				} else {
					jQuery("[name='txtTotaltime']").val('0');
				}
				partsBuyPriceCalculation();
			  }
		});
	}
}

//parts cart
function addPartsToCart(partsId, name, price, warranty, condition){
   var max_qty = $("#qty_"+partsId).attr('max');
   if(parseFloat($("#qty_"+partsId).val()) > parseFloat(max_qty)){
   		alert("You can add maximum "+max_qty+" or may be product is out of stock.");
   		$("#qty_"+partsId).focus();
   } else {
	   $("#load_"+partsId).show();
	   $.ajax({
		  url: '../ajax/parts_cart.php',
		  type: 'POST',
		  data: 'parts_id=' + partsId + '&name=' + name + '&price=' + price + '&qty=' + $("#qty_"+partsId).val() + '&warranty=' + warranty + '&condition=' + condition + '&token=save_parts_to_cart',
		  dataType: 'json',
		  success: function(data) {
			 if(data != '-99'){
				console.log(data); 
				$(".parts_cart").html(data);
				$("#minicart").modal();
			 }
			 else{
				window.location.href = '../index.php';
			 }
			 $("#load_"+partsId).hide();
		  }
	   });
   }
}

//for employee designation info
function getDesgInfo(unit_id){
   var emp_name = $("#ddlEmpName").val();
   if(emp_name != ''){
	   $.ajax({
		  url: '../ajax/getunit.php',
		  type: 'POST',
		  data: 'emp_id=' + emp_name + '&token=getDesgInfo',
		  dataType: 'html',
		  success: function(data) {
			 if(data != '-99'){
			 	$("#txtEmpDesignation").val(data);
				$("#hdnDesg").val(data);
			 }
			 else{
			 	window.location.href = '../index.php';
			 }
		  }
	   });
   }
}

//for floor and unit retrive
function loadStates(cid){
   $.ajax({
	  url: '../ajax/getstate.php',
	  type: 'POST',
	  data: 'cid=' + cid + '&token=getstate',
	  dataType: 'html',
	  success: function(data) {
		$("#ddlState").html(data);
	  }
   });
}

//load model data
function loadYear(mid){
   $.ajax({
	  url: '../ajax/getstate.php',
	  type: 'POST',
	  data: 'mid=' + mid + '&token=getmodel',
	  dataType: 'html',
	  success: function(data) {
		$("#ddl_model").html(data);
		$("#ddlYear").val('');
	  }
   });
}

function loadYearData(moid){
   var mid = $("#ddlMake").val();
   $.ajax({
	  url: '../ajax/getstate.php',
	  type: 'POST',
	  data: 'mid=' + mid + '&moid=' + moid + '&token=getyear',
	  dataType: 'html',
	  success: function(data) {
		$("#ddlYear").html(data);
	  }
   });
}
function saveEstimateData(customer_id,car_id,invoice_id,web_url){
   console.log("test");
   $("#eloader").show();
   var deliver = 0;
   if(document.getElementById('chkdeliver')) { deliver = 1;}
   $.ajax({
	  url: '../ajax/getstate.php',
	  type: 'POST',
	  data: 'customer_id=' + customer_id + '&estimate_no=' + invoice_id + '&car_id=' + car_id + '&deliver=' + deliver + '&discount=' + $("#total_discount").val() + '&payment_done=' + $("#total_paid").val() + '&payment_due=' + $("#total_due").val() + '&grand_total=' + $("#total_grand_total").val() + '&deliver_date=' + $("#txtDeliveryDate").val() + '&token=save_estimate_data',
	  dataType: 'html',
	  success: function(response) {
		Swal.fire({
			title: 'Datos estimados actualizados con éxito',
			icon: 'success',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Imprimir'
		}).then((result) => {
			if (result.isConfirmed) {
			// alert(response);
				var url = web_url+'invoice/invoice.php?invoice_id='+invoice_id;
				openInNewTab(url);
				$("#eloader").hide();
			} else {
				$("#eloader").hide();
			}
		});
	  }
   });
}

function saveWorkshopEstimateData(customer_id,car_id,invoice_id,web_url){
	$("#eloader").show();
	var deliver = 0;
	if(document.getElementById('chkdeliver')) { deliver = 1;}
	$.ajax({
	   url: '../ajax/getstate.php',
	   type: 'POST',
	   data: 'customer_id=' + customer_id + '&estimate_no=' + invoice_id + '&car_id=' + car_id + '&deliver=' + deliver + '&discount=' + $("#total_discount").val() + '&payment_done=' + $("#total_paid").val() + '&payment_due=' + $("#total_due").val() + '&grand_total=' + $("#total_grand_total").val() + '&deliver_date=' + $("#txtDeliveryDate").val() + '&token=save_estimate_data',
	   dataType: 'html',
	   success: function(response) {
		 alert(response);
		 var url = web_url+'invoice/invoice.php?invoice_id='+invoice_id;
		 openInNewTab(url);
		 $("#eloader").hide();
	   }
	});
 }

function confirmEmail() {
	if(confirm("Are you sure you want to send notification email ?")) {
		return true;
	} else {
		return false;
	}
}

function deleteCartParts(parts_id) {
	if(confirm("Are you sure you want to delete parts from cart ?")) {
		if(parseInt(parts_id) > 0) {
			$.ajax({
			  url: '../ajax/parts_cart.php',
			  type: 'POST',
			  data: 'parts_id=' + parts_id + '&token=delete_parts_to_cart',
			  dataType: 'html',
			  success: function(response) {alert("Deleted parts successfully");window.location.reload();}
		   });
		}
	}
}

function deleteCartPartsAfterSold(sold_id, parts_id, qty) {
	if(confirm("Are you sure you want to delete parts from database and add with your quantity ?")) {
		if(parseInt(sold_id) > 0) {
			$.ajax({
			  url: '../ajax/parts_cart.php',
			  type: 'POST',
			  data: 'sold_id=' + sold_id +'&parts_id=' + parts_id +'&qty=' + qty + '&token=return_sold_parts',
			  dataType: 'html',
			  success: function(response) {alert("Deleted parts successfully and recover with your stock");window.location.reload();}
		   });
		}
	}
}

function numberWithCommas(x) {
    x = x.toString();
    var pattern = /(-?\d+)(\d{3})/;
    while (pattern.test(x))
        x = x.replace(pattern, "$1,$2");
    return x;
}
function openInNewTab(url) {
  var win = window.open(url, '_blank');
  win.focus();
}
function validateEmail(email) {
  var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(email);
}

