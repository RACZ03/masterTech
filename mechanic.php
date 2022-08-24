<?php include('layout/header.php'); ?>
<?php
$latestPost = $wms->getFiveLatestNews($link);
$categories = $wms->getAllActiveServiceList($link);

$url=mysqli_real_escape_string($link, $_GET['id']);
$seo_key = pathinfo($url, PATHINFO_FILENAME);
$result = array();
if(!empty($seo_key)) {
	$result = $wms->getSeoDetailsById($link, $seo_key, 'tbl_mechanics_page');
}
if(!empty($result)) { 
?>

<div class="page-title mechanic_profile">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h1><?php echo $result['page_title']; ?></h1>
        <ol class="breadcrumb">
          <li><a href="<?php echo WEB_URL; ?>">Home</a></li>
          <li class="active"><?php echo $result['page_title']; ?></li>
        </ol>
      </div>
    </div>
  </div>
</div>
<div class="main-container">
  <div class="container ">
    <div class="content-placeholder">
      <div class="col-md-9 pr-50 middle_main_holder">
        <p><?php echo $result['page_details']; ?></p>
      </div>
      <div class="col-md-3">
        <div class="category-list mb-55-space">
          <div class="deatils-title">
            <h5><?php echo $_lang['__our_service']; ?></h5>
          </div>
          <ul class="category_tree">
            <?php foreach($categories as $category) { ?>
            <?php if(!empty($category['seo_url'])) {?>
            <li><a href="<?php echo WEB_URL.'services/'.$category['seo_url'];?>"><i class="fa fa-angle-double-right"></i>&nbsp; <?php echo $category['service_name'];?></a></li>
            <?php } else { ?>
            <li><a href="#"><i class="fa fa-angle-double-right"></i>&nbsp; <?php echo $category['service_name'];?></a></li>
            <?php } ?>
            <?php } ?>
          </ul>
        </div>
        <!--<div class="category-list mb-55-space">
            <div><img src="<?php //WEB_URL; ?>img/offer.png" /></div>
          </div>-->
        <div class="latest-post-list">
          <div class="deatils-title">
            <h5><?php echo $_lang['__latest_post']; ?></h5>
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
<?php } else { ?>
<div>opps ! something wrong here.</div>
<?php } ?>
<?php $link = $wms->close_db_connection($link); ?>
<?php include('layout/footer.php'); ?>
