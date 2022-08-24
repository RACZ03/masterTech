<?php 
include_once('../header.php');
$site_name = '';
$currency = '';
$email = '';
$address = '';
$success = false;
$img_logo = WEB_URL . 'img/no_image.jpg';
$img_track = '';
$footer_text_1 = '';
$footer_text_2 = '';
$footer_text_3 = '';
$footer_text_4 = '';
$footer_text_5 = '';
$header_text_1 = '';
$header_text_2 = '';
$contact_us_text_1 = '';
$google_api = '';
$map_address = '';
$mc_api_key = '';
$mc_list_id = '';

//$row_box_1 = 0;

if (!empty($_POST)) {
	$image_url = uploadImage();
	if(empty($image_url)) {
		$image_url = $_POST['img_exist'];
	}
	$wms->saveSystemInformation($link, $_POST, $image_url);
	$success = true;
}
//
//get info
$result = $wms->getWebsiteSettingsInformation($link);
if(!empty($result)) {
	$site_name = $result['site_name'];
	$currency = $result['currency'];
	$email = $result['email'];
	$address = $result['address'];
	if($result['site_logo'] != ''){
		$img_logo = WEB_URL . 'img/' . $result['site_logo'];
		$img_track = $result['site_logo'];
	}
	$footer_text_1 = trim($result['footer_box_1']);
	$footer_text_2 = trim($result['footer_box_2']);
	$footer_text_3 = trim($result['footer_box_3']);
	$footer_text_4 = trim($result['footer_box_4']);
	$footer_text_5 = trim($result['footer_box_5']);
	$header_text_1 = trim($result['header_box_1']);
	$header_text_2 = trim($result['header_box_2']);
	$contact_us_text_1 = trim($result['contact_us_text_1']);
	$google_api = trim($result['gogole_api_key']);
	$map_address = trim($result['map_address']);
	$mc_api_key = trim($result['mc_api_key']);
	$mc_list_id = trim($result['mc_list_id']);
}
function uploadImage(){
	if((!empty($_FILES["uploaded_file"])) && ($_FILES['uploaded_file']['error'] == 0)) {
	  $filename = basename($_FILES['uploaded_file']['name']);
	  $ext = substr($filename, strrpos($filename, '.') + 1);
	  if(($ext == "jpg" && $_FILES["uploaded_file"]["type"] == 'image/jpeg') || ($ext == "png" && $_FILES["uploaded_file"]["type"] == 'image/png') || ($ext == "gif" && $_FILES["uploaded_file"]["type"] == 'image/gif')){   
	  	$temp = explode(".",$_FILES["uploaded_file"]["name"]);
	  	$newfilename = 'logo'.'.'.end($temp);
		move_uploaded_file($_FILES["uploaded_file"]["tmp_name"], ROOT_PATH . '/img/' . $newfilename);
		return $newfilename;
	  }
	  else{
	  	return '';
	  }
	}
	return '';
}
?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1><i class="fa fa-wrench"></i> System Setup </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Car Setting</li>
    <li class="active">Add Car Setting</li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
<!-- Full Width boxes (Stat box) -->
<div class="row">
  <div class="col-md-12">
    <?php if($success) {?>
    <div id="you" class="alert alert-success alert-dismissable">
      <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-close"></i></button>
      <h4><i class="icon fa fa-check"></i> Success!</h4>
      Updated System Information successfully.</div>
    <?php } ?>
    <form id="frmcarstock" method="post" enctype="multipart/form-data">
      <div class="box box-success" id="box_model">
        <div class="box-body">
          <div class="box-header">
            <h3 class="box-title"><i class="fa fa-list"></i> system information</h3>
          </div>
          <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#system">System</a></li>
            <li><a data-toggle="tab" href="#theme">Theme</a></li>
            <li><a data-toggle="tab" href="#header">Header</a></li>
            <li><a data-toggle="tab" href="#footer">Footer</a></li>
            <li><a data-toggle="tab" href="#contact">Contact US</a></li>
			<li><a data-toggle="tab" href="#mailchimp">Subscribe</a></li>
          </ul>
          <div class="tab-content">
            <div id="system" class="tab-pane fade in active">
              <p>
              <div class="form-group col-md-12">
                <label for="txtWorkshopName">Workshop Name :</label>
                <input type="text" name="txtWorkshopName" id="txtWorkshopName" value="<?php echo $site_name; ?>" class="form-control" />
              </div>
              <div class="form-group col-md-12">
                <label for="txtCurrency">Currency :</label>
                <input type="text" name="txtCurrency" id="txtCurrency" value="<?php echo $currency; ?>" class="form-control" />
              </div>
              <div class="form-group col-md-12">
                <label for="txtCurrency">System Email :</label>
                <input type="text" name="txtEmailAddress" id="txtEmailAddress" value="<?php echo $email; ?>" class="form-control" />
              </div>
              <div class="form-group col-md-12">
                <label for="txtCurrency">Address :</label>
                <textarea name="txtAddress" class="form-control"><?php echo $address; ?></textarea>
              </div>
              </p>
            </div>
            <div id="theme" class="tab-pane fade">
              <p>
              <div class="form-group col-md-12">
                <label>Website Logo (250X80 px) :</label>
                <img class="form-control" src="<?php echo $img_logo; ?>" style="height:80px;width:250px;" id="output"/>
                <input type="hidden" name="img_exist" value="<?php echo $img_track; ?>" />
              </div>
              <div class="form-group col-md-12"> <span class="btn btn-file btn btn-primary">Upload Image
                <input type="file" name="uploaded_file" onchange="loadFile(event)" />
                </span> </div>
              </p>
            </div>
            <div id="header" class="tab-pane fade">
              <p>
              <div style="color:red;font-weight:bold;">*** Dont Change any html tag, css id or css class just change your text inside tag.</div>
              <div class="col-md-12">
                <fieldset>
                <legend>Box 1</legend>
                <div class="form-group col-md-12">
                  <textarea name="header_box_1" style="height:250px;" id="header_box_1" class="form-control"><?php echo $header_text_1; ?></textarea>
                </div>
                </fieldset>
              </div>
              <div class="col-md-12">
                <fieldset>
                <legend>Box 2</legend>
                <div class="form-group col-md-12">
                  <textarea name="header_box_2" style="height:250px;" id="header_box_2" class="form-control"><?php echo $header_text_2; ?></textarea>
                </div>
                </fieldset>
              </div>
              </p>
            </div>
            <div id="footer" class="tab-pane fade">
              <p>
              <div style="color:red;font-weight:bold;">*** Dont Change any html tag, css id or css class just change your text inside tag.</div>
              <div class="col-md-12">
                <fieldset>
                <legend>Box 1</legend>
                <div class="form-group col-md-12">
                  <textarea name="footer_box_1" style="height:250px;" id="footer_box_1" class="form-control"><?php echo $footer_text_1; ?></textarea>
                  <!--<div class="table-responsive">
                    <table id="footer_box_1" class="table table-striped table-bordered table-hover">
                      <thead>
                        <tr>
                          <td class="text-center"><b>Text</b></td>
                          <td class="text-center"><b>Link</b></td>
                          <td>&nbsp;</td>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                      <tfoot>
                        <tr>
                          <td colspan="2"></td>
                          <td class="text-left"><button type="button" onclick="addFooterBox1();" data-toggle="tooltip" title="Add" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                        </tr>
                      </tfoot>
                    </table>
                  </div>-->
                </div>
                </fieldset>
              </div>
              <div class="col-md-12">
                <fieldset>
                <legend>Box 2</legend>
                <div class="form-group col-md-12">
                  <textarea name="footer_box_2" style="height:250px;" id="footer_box_2" class="form-control"><?php echo $footer_text_2; ?></textarea>
                </div>
                </fieldset>
              </div>
              <div class="col-md-12">
                <fieldset>
                <legend>Box 3</legend>
                <div class="form-group col-md-12">
                  <textarea name="footer_box_3" style="height:250px;" id="footer_box_3" class="form-control"><?php echo $footer_text_3; ?></textarea>
                </div>
                </fieldset>
              </div>
              <div class="col-md-12">
                <fieldset>
                <legend>Box 4</legend>
                <div class="form-group col-md-12">
                  <textarea name="footer_box_4" style="height:250px;" id="footer_box_4" class="form-control"><?php echo $footer_text_4; ?></textarea>
                </div>
                </fieldset>
              </div>
			  <div class="col-md-12">
                <fieldset>
                <legend>Footer Copyright</legend>
                <div class="form-group col-md-12">
                  <textarea name="footer_box_5" style="height:250px;" id="footer_box_5" class="form-control"><?php echo $footer_text_5; ?></textarea>
                </div>
                </fieldset>
              </div>
              </p>
            </div>
            <div id="contact" class="tab-pane fade">
              <p>
              <div style="color:red;font-weight:bold;">*** Dont Change any html tag, css id or css class just change your text inside tag.</div>
              <div class="col-md-12">
                <fieldset>
                <legend>Change Contact Us Information</legend>
                <div class="form-group col-md-12">
                  <textarea name="contact_us_text_1" style="height:250px;" id="contact_us_text_1" class="form-control"><?php echo $contact_us_text_1; ?></textarea>
                </div>
                </fieldset>
              </div>
			  
			  <div class="col-md-12">
                <fieldset>
                <legend>Add google map api key and Address</legend>
                <div class="form-group col-md-12">
                  <input type="text" name="google_api_key" id="google_api_key" value="<?php echo $google_api; ?>" placeholder="API KEY" class="form-control" />
                </div>
				<div class="form-group col-md-12">
                  <input type="text" name="map_address" id="map_address" value="<?php echo $map_address; ?>" placeholder="Map Address" class="form-control" />
                </div>
                </fieldset>
              </div>
			  
              </p>
            </div>
			
			
			<div id="mailchimp" class="tab-pane fade">
              <p>
              
			  <div class="col-md-12">
                <fieldset>
                <legend>Add Mailchimp API KEY AND LIST ID</legend>
                <div class="form-group col-md-12">
                  <input type="text" name="mc_api_key" id="mc_api_key" value="<?php echo $mc_api_key; ?>" placeholder="Mailchimp API KEY" class="form-control" />
                </div>
				<div class="form-group col-md-12">
                  <input type="text" name="mc_list_id" id="mc_list_id" value="<?php echo $mc_list_id; ?>" placeholder="Mailchimp List ID" class="form-control" />
                </div>
                </fieldset>
              </div>
			  
              </p>
            </div>
			
          </div>
          <div class="form-group col-md-12">
            <button type="submit" class="btn btn-success btn-large btn-block"><b>Save Information</b></button>
          </div>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
    </form>
  </div>
</div>
<script type="text/javascript"><!--
/*var row_box_1 = <?php //echo $row_box_1; ?>;
function addFooterBox1() {
	html  = '<tr id="box1-row' + row_box_1 + '">';
	html += '  <td class="text-right"><input id="_' + row_box_1 + '" type="text" name="footerbox1[' + row_box_1 + '][text]" class="form-control parts_list" /></td>';
	html += '  <td class="text-right"><input type="text" id="price_' + row_box_1 + '" name="footerbox1[' + row_box_1 + '][link]" class="form-control" /></td>';
	html += '  <td class="text-left"><button type="button" onclick="$(\'#box1-row' + row_box_1 + '\').remove();" data-toggle="tooltip" title="Remove" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
	html += '</tr>';
	$('#footer_box_1 tbody').append(html);
	row_box_1++;
}*/
</script>
<?php include('../footer.php'); ?>
