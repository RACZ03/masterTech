<?php include('layout/header.php'); ?>
<?php
$results = $wms->getFAQInformation($link);
$latestPost = $wms->getFiveLatestNews($link);
$categories = $wms->getAllActiveServiceList($link);
?>

<div class="page-title mechanic_profile">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h1>FAQ</h1>
        <ol class="breadcrumb">
          <li><a href="<?php echo WEB_URL; ?>">Home</a></li>
          <li class="active">FAQ</li>
        </ol>
      </div>
    </div>
  </div>
</div>
<div class="main-container">
  <div class="container ">
    <div class="content-placeholder">
      <div class="row">
        <div class="col-md-9 pr-50 middle_main_holder">
          <p>
          <ul id="basics" class="cd-faq-group">
            <?php $i=0; foreach($results as $result) { ?>
            <li> <a class="cd-faq-trigger" href="#<?php echo $i; ?>"><?php echo $result['title']; ?></a>
              <div class="cd-faq-content">
                <p><?php echo $result['content']; ?></p>
              </div>
            </li>
            <?php $i++; } ?>
          </ul>
          </p>
        </div>
        <div class="col-md-3 mt50">
          <div class="category-list mb-55-space">
            <div class="deatils-title">
              <h5>OUR SERVICE</h5>
            </div>
            <ul class="category_tree">
              <?php foreach($categories as $category) { ?>
              <?php if(!empty($category['seo_url'])) {?>
              <li><a <?php echo !empty($seo_key) && $seo_key == $category['seo_url'] ? "class='active'" : ''; ?> href="<?php echo WEB_URL.'services/'.$category['seo_url'];?>"><i class="fa fa-angle-double-right"></i>&nbsp; <?php echo $category['service_name'];?></a></li>
              <?php } else { ?>
              <li><a href="#"><i class="fa fa-angle-double-right"></i>&nbsp; <?php echo $category['service_name'];?></a></li>
              <?php } ?>
              <?php } ?>
            </ul>
          </div>
          <!--<div class="category-list mb-55-space">
            <div><img src="<?php //echo WEB_URL ;?>/img/offer.png" /></div>
          </div>-->
          <div class="latest-post-list">
            <div class="deatils-title">
              <h5>LATEST POST</h5>
            </div>
            <div class="latest-post-box">
              <!-- Lastest Post Single -->
              <?php foreach($latestPost as $lpost) { ?>
              <div class="latest-post-single mb-15">
                <div class="ltst-pst-img"> <img src="<?php echo $lpost['thumb_image']; ?>" alt="<?php echo $lpost['blog_title']; ?>"> </div>
                <div class="list_5_news">
                  <p><a href="<?php echo WEB_URL.'news/'.$lpost['category'].'/'.$lpost['seo_url'];?>"><?php echo $lpost['blog_title']; ?></a></p>
                  <div class="ltst-post"> <span><i class="fa fa-calendar" aria-hidden="true"></i>&nbsp; <?php echo $lpost['blog_date_time']; ?></span>&nbsp; <span><i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo $lpost['blog_time']; ?></span> </div>
                </div>
              </div>
              <?php } ?>
              <!-- Lastest Post Single -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php $link = $wms->close_db_connection($link); ?>
<?php include('layout/footer.php'); ?>
