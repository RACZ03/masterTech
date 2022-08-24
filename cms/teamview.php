<?php include('../header.php')?>
<?php
$delinfo = 'none';
$addinfo = 'none';
$msg = "";

if(isset($_POST['chkTeamId'])){
	$wms->saveUpdateTeamWidgetView($link, $_POST);
	$addinfo = 'block';
	$msg = "Updated team widget Successfully";
}
function teamExist($array, $val) {
	foreach($array as $arr) {
		if($arr['team_id'] == $val) {
			return true;
			break;
		}
	}
	return false;
	exit();
}
function teamSortOrder($array, $val) {
	foreach($array as $arr) {
		if($arr['team_id'] == $val) {
			return $arr['sort_order'];
			break;
		}
	}
	return 0;
	exit();
}
$teamdata = $wms->getTeamWidgetData($link);
?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1> Home page Team View </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Module</li>
    <li class="active">Team View</li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
<!-- Full Width boxes (Stat box) -->
<div class="row">
  <div class="col-xs-12">
    <div id="me" class="alert alert-danger alert-dismissable" style="display:<?php echo $delinfo; ?>">
      <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-close"></i></button>
      <h4><i class="icon fa fa-ban"></i> Deleted!</h4>
      Deleted service successfully. </div>
    <div id="you" class="alert alert-success alert-dismissable" style="display:<?php echo $addinfo; ?>">
      <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-close"></i></button>
      <h4><i class="icon fa fa-check"></i> Success!</h4>
      <?php echo $msg; ?> </div>
    <div align="right" style="margin-bottom:1%;"> <a class="btn btn-success" data-toggle="tooltip" href="javascript:;" onclick="javascript:$('#frm_team').submit();" data-original-title="Add Slider"><i class="fa fa-save"></i></a> <a class="btn btn-warning" data-toggle="tooltip" href="<?php echo WEB_URL; ?>dashboard.php" data-original-title="Dashboard"><i class="fa fa-dashboard"></i></a> </div>
    <div class="box box-success">
      <!-- /.box-header -->
      <div class="box-body">
        <form id="frm_team" method="post" enctype="multipart/form-data">
          <table class="table sakotable table-bordered table-striped dt-responsive">
            <thead>
              <tr>
                <th>Select</th>
				<th>Mechanics</th>
                <th>Designation</th>
                <th>Sort Order</th>
              </tr>
            </thead>
            <tbody>
			  <?php
				$teams = $wms->getAllMechanicsList($link);
				foreach($teams as $row) {
					$image = WEB_URL . 'img/no_image.jpg';	
					if(file_exists(ROOT_PATH . '/img/employee/' . $row['m_image']) && $row['m_image'] != ''){
						$image = WEB_URL . 'img/employee/' . $row['m_image'];
					}
				?>
              <tr>
			  	<td align="center"><input <?php if(teamExist($teamdata, $row['mechanics_id'])){echo 'checked';}?> type="checkbox" name="chkTeamId[]" value="<?php echo $row['mechanics_id']?>" /></td>
                <td><img class="photo_img_round" style="width:20%" src="<?php echo $image;  ?>" /></td>
                <td><?php echo $row['title']; ?></td>
                <td align="center"><input type="text" style="text-align:center;" class="form-control" name="txtSortOrder[<?php echo $row['mechanics_id']?>]" value="<?php echo teamSortOrder($teamdata, $row['mechanics_id']); ?>" /></td>
              </tr>
              <?php } $wms->close_db_connection($link); ?>
            </tbody>
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
</script>
<?php include('../footer.php'); ?>
