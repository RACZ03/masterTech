<?php include('layout/header.php'); ?>

<div class="page-title mechanic_profile">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h1>Contact Us</h1>
        <ol class="breadcrumb">
          <li><a href="<?php echo WEB_URL; ?>">Home</a></li>
          <li class="active">Contact Us</li>
        </ol>
      </div>
    </div>
  </div>
</div>
<div class="main-container">
  <div class="container ">
    <div class="content-placeholder">
      <div class="row">
        <!-- GOOGLE MAP -->
        <div class="col-md-12">
          <div class="map-area">
		  	<div id="map" data-address="<?php echo !empty($map_address) ? $map_address : ''; ?>" style="position: relative; overflow: hidden;"></div>
		  </div>
        </div>
        <!-- GOOGLE MAP -->
        <!-- Contact From -->
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="page-header section-header clearfix">
            <h2><strong> Get in Touch with us</strong></h2>
            <span class="decor"><span class="inner"></span></span> </div><?php echo $contact_us_text_1; ?>
		  <div class="col-md-12 col-sm-12 col-xs-12 contact_form_style">
            <div class="page-header section-header clearfix">
              <h2><strong> Send us a Message</strong></h2>
              <span class="decor"><span class="inner"></span></span> </div>
            <div class="contact-form-area">
              <form id="contact-form" method="POST">
                <div class="col-md-6 col-sm-12 col-xs-12">
                  <div class="form-single">
                    <input name="name" required="" id="name" class="form-control" placeholder="Your Name*" type="text">
                  </div>
                </div>
                <div class="col-md-6 col-sm-12 col-xs-12">
                  <div class="form-single">
                    <input name="email" required="" id="email" class="form-control" placeholder="Mail*" type="text">
                  </div>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="form-single">
                    <input name="subject" required="" id="subject" class="form-control" placeholder="Subject*" type="text">
                  </div>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="form-textarea">
                    <textarea name="message" required="" id="message" rows="8" class="form-control" placeholder="Type Yor Message*"></textarea>
                  </div>
                  <div class="form-button">
                    <button class="contact-submit" type="submit">SEND</button>
                    <p class="form-messege"></p>
                    <p class="form-messege"></p>
                  </div>
                </div>
				<div class="apointment_loader"><img src="<?php echo WEB_URL ;?>/img/ajax-loader.gif" /></div>
              </form>
            </div>
          </div>
        </div>
        <!-- Contact From -->
      </div>
    </div>
  </div>
</div>
<?php $link = $wms->close_db_connection($link); ?>
<?php include('layout/footer.php'); ?>
