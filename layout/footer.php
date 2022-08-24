<!--Footer Section Start Here -->
<footer>
  <div id="scroll_to_top"><i class="fa fa-angle-up"></i></div>
  <div id="zo-footer-top">
    <div class="container">
      <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 cp-bottom-mobile-space">
          <aside id="nav_menu-2" class="widget">
            <?php echo $footer_box_1; ?>
          </aside>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 cp-bottom-mobile-space">
          <aside id="nav_menu-2" class="widget">
            <?php echo $footer_box_2; ?>
          </aside>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 cp-bottom-mobile-space">
          <aside id="nav_menu-2" class="widget">
            <?php echo $footer_box_3; ?>
          </aside>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
          <aside id="text-2" class="widget">
            <?php echo $footer_box_4; ?>
          </aside>
        </div>
      </div>
    </div>
  </div>
</footer>
<!--Footer Section End Here -->
</div>
<div class="modal join-now-form fade" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times; </button>
        <header class="page-header">
          <h2><strong>Get An Apointment</strong></h2>
        </header>
      </div>
      <form id="frm_apointment">
        <div class="modal-body">
          <div class="apointment_loader"><img src="<?php echo WEB_URL ;?>/img/ajax-loader.gif" /></div>
          <div class="row">
            <div class="col-xs-12">
              <div class="form-group col-xs-12 col-sm-12">
                <label for="name-join">Name<span>*</span></label>
                <input type="text" required class="form-control" id="name-join" name="name">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12">
              <div class="form-group col-xs-12 col-sm-6">
                <label for="email-join">Email<span>*</span></label>
                <input type="text" required class="form-control" id="email-join" name="email">
              </div>
              <div class="form-group col-xs-12 col-sm-6">
                <label for="phone-join">Phone<span>*</span></label>
                <input type="text" required class="form-control" id="phone-join" name="phone">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12">
              <div class="form-group col-xs-12">
                <label for="message-join">Message<span>*</span></label>
                <textarea required class="form-control" id="message-join" name="message"></textarea>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12">
              <div class="form-group col-xs-12">
                <button type="submit" class="btn btn-default pull-right"> Submit </button>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<div class="copyright">
  <div class="container">
    <div class="row">
      <div class="col-xs-12"><span><?php echo $footer_box_5; ?></span></div>
    </div>
  </div>
</div>
<script src="<?php echo WEB_URL; ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<script src="<?php echo WEB_URL; ?>assets/frontend/js/bootstrap.min.js"></script>
<script src="<?php echo WEB_URL; ?>assets/frontend/js/jquery.easing.min.js"></script>
<script src="<?php echo WEB_URL; ?>assets/frontend/js/jquery.masonry.min.js"></script>
<script src="<?php echo WEB_URL; ?>assets/frontend/js/site.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo !empty($api_key) ? $api_key : ''; ?>"></script>
<script src="<?php echo WEB_URL; ?>assets/frontend/js/gmap.min.js"></script>
</body></html>