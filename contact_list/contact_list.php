<?php include('../header.php')?>
<?php
$delinfo = 'none';
$addinfo = 'none';
$msg = "";
if(isset($_GET['delid']) && $_GET['delid'] != '' && $_GET['delid'] > 0){
	$wms->deleteContactRequest($link, $_GET['delid']); 
	$delinfo = 'block';
}

if(isset($_POST['contact_id']) && !empty($_POST['contact_id'])) {
	$wms->setContactStatus($link, $_POST['contact_id']);
	$wms->sendContactReplyEmail($link, $_POST['reply_email'], $_POST['reply_subject'], $_POST['reply_message']);
	$addinfo = 'block';
	$msg = "Sent reply email successfully";
}
?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1> Contact US List </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Contact us</li>
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
      Contact US Deleted Successfully. </div>
    <div id="you" class="alert alert-success alert-dismissable" style="display:<?php echo $addinfo; ?>">
      <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-close"></i></button>
      <h4><i class="icon fa fa-check"></i> Success!</h4>
      <?php echo $msg; ?> </div>
    <div align="right" style="margin-bottom:1%;"> <a class="btn btn-success" data-toggle="tooltip" href="<?php echo WEB_URL; ?>contact_list/contact_list.php" data-original-title="Reload Page"><i class="fa fa-refresh"></i></a> <a class="btn btn-primary" data-toggle="tooltip" href="<?php echo WEB_URL; ?>dashboard.php" data-original-title="Dashboard"><i class="fa fa-dashboard"></i></a> </div>
    <div class="box box-success">
      <div class="box-header">
        <h3 class="box-title">Contact US List</h3>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <table class="table sakotable table-bordered table-striped dt-responsive">
          <thead>
            <tr>
              <th>Name</th>
              <th>Email</th>
              <th>Subject</th>
              <th>Reply</th>
              <th>Requested Date</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
			 $cid = 0;
			 if(isset($_GET['cid']) && (int)$_GET['cid'] > 0) {
			 	$cid = $_GET['cid'];
			 }
			 $results = $wms->get_all_contact_list($link, $cid);
			 foreach($results as $row) { ?>
            <tr>
              <td><?php echo $row['name']; ?></td>
              <td><?php echo $row['email']; ?></td>
              <td><?php echo $row['subject']; ?></td>
              <td><?php if($row['status']==1) echo "<span class='label label-success'>Done</span>"; else echo "<span class='label label-danger'>Pending</span>"; ?></td>
              <td><b><?php echo $wms->mySqlToDatePicker($row['added_date']);?></b></td>
              <td><a class="btn btn-warning" data-toggle="tooltip" href="javascript:;" onClick="$('#nurse_view_<?php echo $row['contact_id']; ?>').modal('show');" data-original-title="Message"><i class="fa fa-envelope-o"></i></a> <a class="btn btn-success" href="javascript:;" onClick="$('#sender_view_<?php echo $row['contact_id']; ?>').modal('show');" data-original-title="Reply"><i class="fa fa-reply"></i></a> <a class="btn btn-danger" data-toggle="tooltip" onClick="deletemechanics(<?php echo $row['contact_id']; ?>);" href="javascript:;" data-original-title="Delete"><i class="fa fa-trash-o"></i></a>
                <div id="nurse_view_<?php echo $row['contact_id']; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header orange_header">
                        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><i class="fa fa-close"></i></span></button>
                        <h3 class="modal-title">Message</h3>
                      </div>
                      <div class="modal-body">
                        <h3 style="text-decoration:underline;">Details Infromation</h3>
                        <div class="row">
                          <div class="col-xs-12"><?php echo $row['message']; ?></div>
                        </div>
                      </div>
                    </div>
                    <!-- /.modal-content -->
                  </div>
                </div>
                <div id="sender_view_<?php echo $row['contact_id']; ?>" class="modal fade email_reply" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header orange_header">
                        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><i class="fa fa-close"></i></span></button>
                        <h3 class="modal-title"><i class="fa fa-envelope-o"></i> Email</h3>
                      </div>
                      <form method="post" enctype="application/x-www-form-urlencoded">
                        <div class="modal-body">
                          <h3 style="text-decoration:underline;"><i class="fa fa-edit"></i> Email Reply</h3>
                          <div class="row">
                            <div class="col-xs-12 mb-10">
                              <div class="col-xs-12">
                                <label for="reply_email">Reply Email: </label>
                              </div>
                              <div class="col-xs-12">
                                <input type="text" required name="reply_email" value="<?php echo $row['email']; ?>" id="reply_email" class="form-control" />
                              </div>
                            </div>
                            <div class="col-xs-12 mb-10">
                              <div class="col-xs-12">
                                <label for="reply_subject">Subject: </label>
                              </div>
                              <div class="col-xs-12 mb-10">
                                <input type="text" required name="reply_subject" value="<?php echo $row['subject']; ?>" id="reply_subject" class="form-control" />
                              </div>
                            </div>
                            <div class="col-xs-12">
                              <div class="col-xs-12">
                                <label for="reply_message">Reply Message: </label>
                              </div>
                              <div class="col-xs-12 mb-10">
                                <textarea name="reply_message" required id="message" rows="8" class="form-control"></textarea>
                              </div>
                              <div class="col-xs-12 mb-10">
                                <input type="hidden" name="contact_id" id="contact_id" value="<?php echo $row['contact_id']; ?>" />
                                <button class="btn btn-success" type="submit"><b>SEND</b></button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </form>
                    </div>
                    <!-- /.modal-content -->
                  </div>
                </div></td>
            </tr>
            <?php } $wms->close_db_connection($link); ?>
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
<script type="text/javascript">
function deletemechanics(Id){
  	var iAnswer = confirm("Are you sure you want to delete this request ?");
	if(iAnswer){
		window.location = '<?php echo WEB_URL; ?>contact_list/contact_list.php?delid=' + Id;
	}
}
  
  $( document ).ready(function() {
	setTimeout(function() {
		  $("#me").hide(300);
		  $("#you").hide(300);
	}, 3000);
});
</script>
<?php include('../footer.php'); ?>
