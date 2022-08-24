<?php include('layout/header.php'); ?>
<?php
$name = '';
$email = '';
$alert = '';
$url=mysqli_real_escape_string($link, $_GET['id']);
$seo_key = pathinfo($url, PATHINFO_FILENAME);
$result = array();
$latestPost = $wms->getFiveLatestNews($link);
$categories = $wms->getNewsCategoryAndCount($link);
if(!empty($seo_key)) {
	$result = $wms->singlePageBlogDetailsBySeoUrl($link, $seo_key);
}

if(!empty($result)) {
$comments = $wms->getBlogAllCommentsByBlogId($link, $result['blog_id']);
if(isset($_POST['name']) && !empty($_POST['name'])) {
	$post_array = array(
		'comments_id'			=> '0',
		'ddlBlog' 				=> $_POST['blog_id'],
		'txtAuthorName' 		=> $_POST['name'],
		'txtEmail' 				=> $_POST['email'],
		'txtComments' 			=> $_POST['message'],
		'status'				=> 0
	);
	$image = '';
	if(isset($_SESSION['objCust']) && !empty($_SESSION['objCust']['image'])) {
		$image = $_SESSION['objCust']['image'];
	}
	$wms->saveUpdateNewsCommentsInformation($link, $post_array, $image);
	$alert = 'success';
}
if(isset($_SESSION['objCust']) && !empty($_SESSION['objCust'])) {
	$name = $_SESSION['objCust']['name'];
	$email = $_SESSION['objCust']['email'];
}
?>
<div class="page-title mechanic_profile">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h1>Our News</h1>
        <ol class="breadcrumb">
          <li><a href="<?php echo WEB_URL; ?>">Home</a></li>
          <li><a href="<?php echo WEB_URL; ?>news-latest-collection">News</a></li>
          <li class="active"> <?php echo $result['blog_title']; ?></li>
        </ol>
      </div>
    </div>
  </div>
</div>
<div class="main-container">
  <div class="container ">
    <div class="content-placeholder">
      <div class="row news_details">
        <div class="col-md-9 news_content_area pr-50">
          <div class="news_image"><img src="<?php echo $result['blog_image']; ?>" />
            <h3><?php echo $result['blog_day'];?><span><?php echo $result['blog_month'];?>,<?php echo $result['blog_year'];?></span></h3>
          </div>
          <div class="news-top-title">
            <h2><?php echo $result['blog_title']; ?></h2>
          </div>
          <div class="post-info"> <span><i class="fa fa-user"></i> By : Admin</span>&nbsp; | &nbsp;<span><i class="fa fa-comments-o"></i> <a href="#comments"><?php echo count($comments); ?> Comments</a></span> </div>
          <div>
            <p><?php echo $result['blog_details']; ?></p>
          </div>
          <?php if(!empty($comments)) { ?>
		  <div class="comment-box" id="comments">
            <div class="deatils-title mb-60-space">
              <h5><i class="fa fa-comments-o" aria-hidden="true"></i> COMMENTS (<?php echo count($comments); ?>)</h5>
            </div>
			<?php foreach($comments as $comment) {?>
            <!-- Comment Single Box -->
            <div class="comment-single-box">
              <div class="comment-pic"> <img class="comments_img" src="<?php echo $comment['image'];?>" alt="<?php echo $comment['name'];?>"> </div>
              <div class="comment-content">
                <div class="comment-author">
                  <h3><?php echo $comment['name'];?></h3>
                  <ul>
                    <li><?php echo $comment['comments_date'];?></li>
                  </ul>
                </div>
                <div class="comment-dis">
                  <p><?php echo $comment['comments'];?></p>
                </div>
              </div>
            </div>
            <!-- Comment Single Box -->
			<?php } ?>
          </div>
		  <?php } ?>
          <?php if($result['allow_comment']) {?>
		  <div class="leave-reply-box">
            <div class="deatils-title pt-15-space">
              <h5><i class="fa fa-edit" aria-hidden="true"></i> LEAVE A REPLY</h5>
            </div>
            <div class="contact-form-area">
              <?php if(!empty($alert)) { ?>
			  <div class="alert alert-success"><i class="fa fa-check-circle" aria-hidden="true"></i> <b>&nbsp;Added comments successfully wait for approval.</b></div>
			  <?php } ?>
			  <form enctype="multipart/form-data" method="POST">
                <div class="row">
                  <div class="col-md-6 col-sm-12 col-xs-12">
                    <div class="form-single">
                      <input name="name" required id="name" class="form-control" value="<?php echo $name; ?>" placeholder="Your Name*" type="text">
                    </div>
                  </div>
                  <div class="col-md-6 col-sm-12 col-xs-12">
                    <div class="form-single">
                      <input name="email" required id="email" class="form-control" value="<?php echo $email; ?>" placeholder="Mail*" type="text">
                    </div>
                  </div>
                  <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="form-textarea">
                      <textarea name="message" required id="message" rows="8" class="form-control" placeholder="Type Yor Message*"></textarea>
                    </div>
                    <div class="form-button">
                      <button class="contact-submit" type="submit">SUBMIT</button>
                    </div>
                  </div>
                </div>
				<input type="hidden" name="blog_id" value="<?php echo $result['blog_id']; ?>" />
              </form>
            </div>
          </div>
		  <?php } ?>
        </div>
		<div class="col-md-3 mt50">
          <div class="category-list mb-55-space">
            <div class="deatils-title">
              <h5><?php echo $_lang['__category']; ?></h5>
            </div>
            <ul>
              <?php foreach($categories as $category) { ?>
              <li><a href="<?php echo WEB_URL.'category/'.$category['seo_url'];?>"><i class="fa fa-angle-double-right"></i>&nbsp; <?php echo $category['category_name'];?><span class="label label-danger"><?php echo $category['total'];?></span></a></li>
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
<?php } else { ?>
<div>opps ! something wrong here.</div>
<?php } ?>
<?php $link = $wms->close_db_connection($link); ?>
<?php include('layout/footer.php'); ?>
