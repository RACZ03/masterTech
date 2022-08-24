<?php include('../header.php')?>
<?php
$delinfo = 'none';
$addinfo = 'none';
$msg = "";
$row_val = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	$wms->saveUpdateFAQ($link, $_POST);
	$addinfo = 'saveUpdateFAQ';
	$msg = "Updated FAQ Information Successfully";
}
$faqs = $wms->getFAQInformation($link);
if(!empty($faqs)) {
	$row_val = count($faqs);
}
?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1> FAQ Builder </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">FAQ</li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
<!-- Full Width boxes (Stat box) -->
<div class="row">
  <div class="col-xs-12">
    <div id="you" class="alert alert-success alert-dismissable" style="display:<?php echo $addinfo; ?>">
      <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-close"></i></button>
      <h4><i class="icon fa fa-check"></i> Success!</h4>
      <?php echo $msg; ?> </div>
    <div align="right" style="margin-bottom:1%;"> <a class="btn btn-success" data-toggle="tooltip" href="javascript:;" onclick="javascript:$('#frm_faq').submit();" data-original-title="Add FAQ"><i class="fa fa-save"></i></a> <a class="btn btn-warning" data-toggle="tooltip" href="<?php echo WEB_URL; ?>dashboard.php" data-original-title="Dashboard"><i class="fa fa-dashboard"></i></a> </div>
    <div class="box box-success">
      <!-- /.box-header -->
      <div class="box-body">
        <form id="frm_faq" method="post" enctype="multipart/form-data">
          <table id="faqtable" class="table table-bordered table-striped dt-responsive">
            <thead>
              <tr>
                <th>FAQ Title</th>
				<th>FAQ Content</th>
                <th>Sort Order</th>
              </tr>
            </thead>
			<?php foreach($faqs as $faq) { ?>
				<tbody id="faq-row<?php echo $row_val; ?>">
				<tr>
					<td class="left"><input type="text" class="form-control" value="<?php echo $faq['title']; ?>" name="faq[<?php echo $row_val; ?>][title]"></td>
					<td class="left"><textarea class="form-control" name="faq[<?php echo $row_val; ?>][content]"><?php echo $faq['content']; ?></textarea></td>
					<td class="left"><input size="2" type="text" value="<?php echo $faq['sort_order']; ?>" class="form-control" name="faq[<?php echo $row_val; ?>][sort]"></td>
					<td class="left"><button class="btn btn-danger" title="Remove" data-toggle="tooltip" onclick="$('#faq-row<?php echo $row_val; ?>').remove();" type="button"><i class="fa fa-minus-circle"></i></button></td>
				</tr>
				</tbody>
				<?php $row_val++; ?>
			 <?php } $wms->close_db_connection($link); ?>
			<tfoot>
                <tr>
                  <td colspan="3"></td>
                  <td class="left"><button class="btn btn-primary" title="" data-toggle="tooltip" onclick="addFAQ();" type="button" data-original-title="Add FAQ"><i class="fa fa-plus-circle"></i></button></td>
                </tr>
              </tfoot>
          </table>
        </form>
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
  <!-- /.col -->
</div>
<!-- /.row -->
<script type="text/javascript">
	function deleteme(Id){
  		var iAnswer = confirm("Are you sure you want to delete this service ?");
		if(iAnswer){window.location = '<?php echo WEB_URL; ?>cms/servicelist.php?delid=' + Id;}
	}
  
	$( document ).ready(function() {
		setTimeout(function() {
			  $("#me").hide(300);
			  $("#you").hide(300);
		}, 3000);
	});
	
var faq_rows = <?php echo $row_val; ?>;
function addFAQ() {
	html  = '<tbody id="faq-row' + faq_rows + '">';
	html += '  <tr>';
	html += '    <td class="left"><input type="text" class="form-control" name="faq[' + faq_rows + '][title]"></td>';
	html += '    <td class="left"><textarea class="form-control" name="faq[' + faq_rows + '][content]"></textarea></td>';
	html += '    <td class="left"><input size="2" type="text" value="0" class="form-control" name="faq[' + faq_rows + '][sort]"></td>';
	html += '    <td class="left"><button class="btn btn-danger" title="Remove" data-toggle="tooltip" onclick="$(\'#faq-row' + faq_rows + '\').remove();" type="button"><i class="fa fa-minus-circle"></i></button></td>';
	html += '  </tr>';	
	html += '</tbody>';
	
	$('#faqtable tfoot').before(html);
	
	faq_rows++;
}
	
	
</script>
<?php include('../footer.php'); ?>
