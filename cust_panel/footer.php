</div>
<!-- /.content-wrapper -->

<footer class="main-footer">
  <!-- <div class="pull-right hidden-xs"> <b>Version</b> 3.0 </div>
  <strong>Copyright &copy; 2014-2018 <a href="http://sakosys.com">sakosys.com</a>.</strong> All rights reserved. </footer> -->
<!-- /.control-sidebar -->
<!-- Add the sidebar's background. This div must be placed
 immediately after the control sidebar -->
<div class="modal fade" role="dialog" id="user_profile" aria-labelledby="gridSystemModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="gridSystemModalLabel">Update Your Profile</h4>
      </div>
      <div class="modal-body">
        <?php 
	  	$image = WEB_URL . 'img/no_image.jpg';	
		if(isset($_SESSION['objLogin']['image'])){
			if(file_exists(ROOT_PATH . '/img/upload/' . $_SESSION['objLogin']['image']) && $_SESSION['objLogin']['image'] != ''){
				$image = WEB_URL . 'img/upload/' . $_SESSION['objLogin']['image'];
			}
		}
	  ?>
        <div align="center"><img class="photo_img_round" style="width:100px;height:100px;" src="<?php echo $image;  ?>" /></div>
        <h4 align="center"><?php //echo $_SESSION['objLogin']['name']; ?></h4>
        <h4 align="center">Designation:&nbsp;&nbsp;
          <?php //if($_SESSION['login_type'] == '1'){echo 'Admin';} else if($_SESSION['login_type'] == '2'){echo 'Doctor';}?>
        </h4>
        <div class="form-group">
          <label class="control-label">Change Password:&nbsp;&nbsp;</label>
          <input type="text" class="form-control" id="txtPassword" name="txtPassword" value="<?php //echo $_SESSION['objLogin']['password']; ?>">
        </div>
        <div style="color:orange;font-weight:bold;text-align:left;font-size:15px;">You can update only your password, if you need more thing to update please conatct with administator.</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary">Update</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<div class='control-sidebar-bg'></div>
</div>
<!-- ./wrapper -->
<!-- Bootstrap 3.3.2 JS -->
<script src="<?php echo WEB_URL; ?>bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<!-- SlimScroll -->
<script src="<?php echo WEB_URL; ?>plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<!-- FastClick -->
<script src='<?php echo WEB_URL; ?>plugins/fastclick/fastclick.min.js'></script>
<!-- AdminLTE App -->
<script src="<?php echo WEB_URL; ?>dist/js/app.min.js" type="text/javascript"></script>
<!-- Demo -->
<script src="<?php echo WEB_URL; ?>dist/js/demo.js" type="text/javascript"></script>
<script src="<?php echo WEB_URL; ?>plugins/iCheck/icheck.min.js" type="text/javascript"></script>
<!-- iCheck 1.0.1 -->
<!-- DATA TABES SCRIPT -->
<script src="<?php echo WEB_URL; ?>plugins/datatables/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="<?php echo WEB_URL; ?>plugins/datatables/dataTables.bootstrap.min.js" type="text/javascript"></script>
<!-- iCheck 1.0.1 -->
<script src="<?php echo WEB_URL; ?>dist/js/jquery.mask.min.js"></script>
<script src="<?php echo WEB_URL; ?>dist/js/common.js" type="text/javascript"></script>
<script src="<?php echo WEB_URL; ?>dist/js/dataTables.responsive.min.js" type="text/javascript"></script>
<script src="<?php echo WEB_URL; ?>dist/js/dataTables.tableTools.min.js" type="text/javascript"></script>
<script src="<?php echo WEB_URL; ?>plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
<input type="hidden" id="web_url" value="<?php echo WEB_URL; ?>" />
</body></html>