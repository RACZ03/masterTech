<?php 
include('../header.php');
$success = "none";
$gallery_name = '';
$sort_order = 0;
$status = 1;
$images = array();
		
$title = 'Add New Gallery';
$button_text="Save Information";
$hdnid="0";
$image_sup = WEB_URL . 'img/no_image.jpg';  
$img_track = '';

$row = 0;

if(isset($_POST['txtWorkCategoryName'])){
	$category_info = array(
		'txtWorkCategoryName'	=> $_POST['txtWorkCategoryName'],
		'txtSortOrder'			=> $_POST['txtSortOrder'],
		'status'				=> $_POST['status'],
		'gallery_id'			=> $_POST['gallery_id']
	);
	//category information
	$gallery_id = $wms->saveUpdateGalleryCategoryInformation($link, $category_info);
	//
	if((int)$gallery_id > 0) {
		$i=0;
		$img_collection = array();
		if(!empty($_FILES['uploaddata'])) {
			foreach ($_FILES['uploaddata']['name'] as $key => $value) {
				$img_collection[] = uploadImage($_FILES["uploaddata"],$key);
			}
		}
		foreach ($_POST['gallery'] as $rowdata) {
			$image_url = '';
			if (!empty($img_collection[$i])) {
				$image_url = $img_collection[$i];
			} else {
				$image_url = $rowdata['img'];
			}
			$gallery_data = array(
				'category_id'	=> $gallery_id,
				'image_url'		=> $image_url,
				'text'			=> $rowdata['text'],
				'sort_order'	=> $rowdata['sort_order']
			);
			$wms->saveUpdateGalleryInformation($link, $gallery_data);
			$i++;
		}
	}
	if((int)$_POST['gallery_id'] > 0){
		$url = WEB_URL.'cms/gallerylist.php?m=up';
		header("Location: $url");
	} else {		
		$url = WEB_URL.'cms/gallerylist.php?m=add';
		header("Location: $url");
	}
	
}

if(isset($_GET['id']) && $_GET['id'] != ''){
	$row = $wms->getGalleryInformationById($link, $_GET['id']);
	if(!empty($row)){
		$gallery_name = $row['gallery_name'];
		$sort_order = $row['sort_order'];
		$status = $row['status'];
		$images = $row['images'];
		$row = count($row);
		
		$hdnid = $_GET['id'];
		$title = 'Update Gallery';
		$button_text="Update Gallery";
	}
}

//for image upload
function uploadImage($data,$rowx){
	if((!empty($data)) && ($data['error'][$rowx]['frontImg'] == 0)) {
	  $filename = basename($data['name'][$rowx]['frontImg']);
	  $ext = substr($filename, strrpos($filename, '.') + 1);
	  if(($ext == "jpg" && $data["type"][$rowx]['frontImg'] == 'image/jpeg') || ($ext == "png" && $data["type"][$rowx]['frontImg'] == 'image/png') || ($ext == "gif" && $data["type"][$rowx]['frontImg'] == 'image/gif')){   
	  	$temp = explode(".",$data["name"][$rowx]['frontImg']);
	  	$newfilename = NewGuid() . '.' .end($temp);
		move_uploaded_file($data["tmp_name"][$rowx]['frontImg'], ROOT_PATH . '/img/gallery/' . $newfilename);
		return $newfilename;
	  }
	  else{
	  	return '';
	  }
	}
	return '';
}
function NewGuid() { 
    $s = strtoupper(md5(uniqid(rand(),true))); 
    $guidText = 
        substr($s,0,8) . '-' . 
        substr($s,8,4) . '-' . 
        substr($s,12,4). '-' . 
        substr($s,16,4). '-' . 
        substr($s,20); 
    return $guidText;
}
	
?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1><i class="fa fa-leaf"></i> Add Work Gallery </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Work</li>
    <li class="active">Add Gallery</li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
<!-- Full Width boxes (Stat box) -->
<div class="row">
  <div class="col-md-12">
    <div align="right" style="margin-bottom:1%;"> <a class="btn btn-success" onclick="javascript:$('#frm_comments').submit();" data-toggle="tooltip" href="javascript:;" data-original-title="<?php echo $button_text; ?>"><i class="fa fa-save"></i></a> &nbsp;<a class="btn btn-warning" title="" data-toggle="tooltip" href="<?php echo WEB_URL; ?>cms/commentslist.php" data-original-title="Back"><i class="fa fa-reply"></i></a> </div>
    <div class="box box-success">
      <div class="box-header">
        <h3 class="box-title col-md-12"><?php echo $title; ?></h3>
      </div>
      <form id="frm_comments" onSubmit="return validateMe();" method="post" enctype="multipart/form-data">
        <div class="box-body">
          <div class="form-group col-md-12">
            <label for="txtWorkCategoryName"><span style="color:red;">*</span> Gallery Category Name :</label>
            <input type="text" name="txtWorkCategoryName" value="<?php echo $gallery_name;?>" id="txtWorkCategoryName" class="form-control" />
          </div>
          <div class="form-group col-md-12">
            <label for="txtSortOrder">Sort Order</label>
            <input type="text" name="txtSortOrder" value="<?php echo $sort_order;?>" id="txtSortOrder" class="form-control" />
          </div>
          <div class="form-group col-md-12">
            <label for="status">Status :</label>
            <select class="form-control" name="status" id="status">
              <option <?php if($status == '1'){echo 'selected'; }?> value='1'>Enable</option>
              <option <?php if($status == '0'){echo 'selected'; }?> value='0'>Disable</option>
            </select>
          </div>
          <div class="form-group col-md-12">
            <label>Gallery Images</label>
            <table id="gallery_panel" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>Upload Image</span></th>
                  <th>Gallery Text</span></th>
                  <th>Sort Order</span></th>
                  <th>Preview</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <?php $xrow = 0; ?>
				<?php foreach($images as $image) { ?>
                <tr id="gallery-row<?php echo $xrow; ?>">
                  <td><label class="btn btn-success">
                    <input onchange=readURL(this,'<?php echo $xrow; ?>',"preview_"); type="file" style="display:none;" name="uploaddata[<?php echo $xrow; ?>][frontImg]" />
                    <i class="icon-folder-open"></i> Upload</label><input type="hidden" value="<?php echo $image['image']; ?>" name="gallery[<?php echo $xrow; ?>][img]" /></td>
                  <td><input type="text" class="form-control" value="<?php echo $image['text']; ?>" required name="gallery[<?php echo $xrow; ?>][text]" /></td>
                  <td><input type="text" class="form-control" value="<?php echo $image['sort_order']; ?>" required name="gallery[<?php echo $xrow; ?>][sort_order]" /></td>
                  <td><img style="width:50px;height:50px" src="<?php echo $image['image_url']; ?>" class="img-thumbnail" id="preview_<?php echo $xrow; ?>" /></td>
                  <td><a class="btn btn-danger" data-toggle="tooltip" title="Delete Image" href="javascript:;" onclick="$('#gallery-row<?php echo $xrow; ?>').remove();"> <i class="fa fa-trash"></i></a></td>
                </tr>
                <?php $xrow++; }?>
              </tbody>
              <tfoot>
                <tr>
                  <td colspan="5" align="right"><a href="javascript:;" onclick="addGalleryImage();" data-toggle="tooltip" title="Add Image" class="btn btn-success"><i class="fa fa-plus"></i> </a></td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
        <input type="hidden" value="<?php echo $hdnid; ?>" name="gallery_id"/>
      </form>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
</div>
<!-- /.row -->
<script type="text/javascript">
function validateMe(){
	if($("#txtComments").val() == ''){
		alert("Comments text required !!!");
		$("#txtComments").focus();
		return false;
	}
	else if($("#txtAuthorName").val() == ''){
		alert("Author name required !!!");
		$("#txtAuthorName").focus();
		return false;
	}
	else if($("#txtAuthorProfession").val() == ''){
		alert("Author profession required !!!");
		$("#txtAuthorProfession").focus();
		return false;
	}
	else{
		return true;
	}
}

var xcrow = <?php echo $row; ?>;
function addGalleryImage(){
	var xhtml = '';
	xhtml  = 	'<tr id="gallery-row' + xcrow + '">';
	xhtml += 		'<td><label class="btn btn-success"><input onchange=readURL(this,'+xcrow+',"preview_"); type="file" style="display:none;" name="uploaddata[' + xcrow + '][frontImg]" /><i class="icon-folder-open"></i> Upload</label></td>';
	xhtml += 		'<td><input type="text" class="form-control" required name="gallery[' + xcrow + '][text]" /></td>';
	xhtml += 		'<td><input type="text" class="form-control" required name="gallery[' + xcrow + '][sort_order]" /></td>';
	xhtml += 		'<td><img style="width:50px;height:50px" class="img-thumbnail" id="preview_' + xcrow + '" /></td>';
	xhtml += 		'<td><a class="btn btn-danger" data-toggle="tooltip" title="Delete Image" href="javascript:;" onclick="$(\'#gallery-row' + xcrow + '\').remove();"> <i class="fa fa-trash"></i></a></td>';
	xhtml += 	'</tr>';

	$('#gallery_panel tbody').append(xhtml);
	
	xcrow++;
}
function readURL(input,rowid,side) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#'+side+rowid).attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
<?php include('../footer.php'); ?>
