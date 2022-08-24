<?php include('layout/header.php'); ?>
<?php
$url=mysql_real_escape_string($_GET['url']);
$seo_key = pathinfo($url, PATHINFO_FILENAME);
$result = array();
$latestPost = $wms->getFiveLatestNews($link);
$categories = $wms->getNewsCategoryAndCount($link);
if(!empty($seo_key)) {
	$result = $wms->getNewsByCategory($link, $seo_key);
}
?>
<div class="page-title mechanic_profile">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h1><?php echo isset($result['category']) ? $result['category'] : 'News Categoty'; ?></h1>
        <ol class="breadcrumb">
          <li><a href="<?php echo WEB_URL; ?>">Home</a></li>
          <li class="active"> <?php echo isset($result['category']) ? $result['category'] : 'News Categoty'; ?></li>
        </ol>
      </div>
    </div>
  </div>
</div>
<div class="main-container">
  <div class="container ">
    <div class="content-placeholder">
      <div class="row news_details">
        <div class="col-md-9">
          <div class="row">
            <?php if(!empty($result)) { ?>
            <?php foreach($result['blogs'] as $newx) { ?>
            <div class="col-md-6 cp-bottom-mobile-space mb-20">
              <div class="latest-single">
                <div class="ltst-img"> <img src="<?php echo $newx['thumb_image']?>" alt=""> </div>
                <div class="latest-content text-center">
                  <div class="ltst-single">
                    <h6><a href="<?php echo WEB_URL.'news/'.$newx['category'].'/'.$newx['seo_url'];?>"><?php echo $newx['blog_title']; ?></a></h6>
                    <div class="ltst-post"> <span><i class="fa fa-calendar" aria-hidden="true"></i>&nbsp; <?php echo $newx['blog_date_time']; ?></span>&nbsp; <span><i class="fa fa-comments-o" aria-hidden="true"></i>&nbsp; <?php echo $newx['comments']; ?> <?php echo (int)$newx['comments'] > 1 ? 'Comments' : 'Comment'; ?></span> </div>
                    <p><?php echo $newx['short_desc']?></p>
                    <div class="lastest-btn"> <a href="<?php echo WEB_URL.'news/'.$newx['category'].'/'.$newx['seo_url'];?>">READ MORE</a> </div>
                  </div>
                </div>
              </div>
            </div>
            <?php } ?>
            <?php } else  { ?>
            <div class="alert alert-success not_found_news"><i class="fa fa-info-circle"></i> No news found for this.</div>
            <?php } ?>
          </div>
        </div>
        <div class="col-md-3 mt50">
          <div class="category-list mb-55-space">
            <div class="deatils-title">
              <h5><?php echo $_lang['__category']; ?></h5>
            </div>
            <ul class="category_tree">
              <?php foreach($categories as $category) { ?>
              <li><a <?php if($seo_key==$category['seo_url']){echo 'class="active"';}?> href="<?php echo WEB_URL.'category/'.$category['seo_url'];?>"><i class="fa fa-angle-double-right"></i>&nbsp; <?php echo $category['category_name'];?><span class="label label-danger"><?php echo $category['total'];?></span></a></li>
              <?php } ?>
            </ul>
          </div>
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
</div>
<?php $link = $wms->close_db_connection($link); ?>
<?php include('layout/footer.php'); ?>
