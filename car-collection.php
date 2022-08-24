<?php include('layout/header.php'); ?>
<?php include('library/pagination.php'); ?>
<?php
$limit = 5;
$page = 1;
if(isset($_GET['page']) && !empty($_GET['page'])) {
	$page = $_GET['page'];
}

$total = $wms->countAllCarList($link);
$pagination = new Pagination();
$pagination->total = $total;
$pagination->page = $page;
$pagination->limit = $limit;
$pagination->url = WEB_URL.'car-collection?page={page}';
$pagination = $pagination->render();
		
$result = array();
$latestPost = $wms->getFiveLatestNews($link);
$categories = $wms->getAllActiveServiceList($link);
$teams = $wms->getAllTeamMembers($link);
$results = $wms->getAllCarListWithPagination($link, $page, $limit);
?>

<div class="page-title mechanic_profile">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h1>Our Car Collection</h1>
        <ol class="breadcrumb">
          <li><a href="<?php echo WEB_URL; ?>">Home</a></li>
          <li class="active"> Car Collection</li>
        </ol>
      </div>
    </div>
  </div>
</div>
<div class="main-container">
  <div class="container ">
    <div class="content-placeholder">
      <div class="row">
        <div class="col-md-9 pr-50">
          <div>
            <div class="row">
              <div class="col-md-12">
                <div class="result-sorting-wrapper">
                  <div class="sorting-count">
                    <p class="car_filter_title"><?php echo $page; ?> - <?php echo $limit; ?> <span>of <?php echo $total; ?> Listings</span></p>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <?php foreach($results as $car) {
					$image = WEB_URL . 'img/no_image.jpg';
					if(file_exists(ROOT_PATH . '/img/upload/' . $car['car_image']) && $car['car_image'] != ''){
						$image = WEB_URL . 'img/upload/' . $car['car_image']; //car image
					}
				?>
                <div class="product-listing-m gray-bg">
                  <div class="product-listing-img"> <a href="#"><img src="<?php echo $image; ?>" class="img-responsive" alt="Image"> </a>
                    <div class="label_icon"><?php echo $car['car_condition']; ?></div>
                  </div>
                  <div class="product-listing-content">
                    <!--<h5><a href="#"><?php //echo $car['car_name']; ?></a></h5>-->
                    <h5 id="rcn_<?php echo $car['buycar_id']; ?>"><?php echo $car['car_name']; ?></h5>
                    <p class="list-price"><?php echo $currency.$car['asking_price']; ?></p>
                    <ul>
                      <li><i class="fa fa-paint-brush" aria-hidden="true"></i><?php echo $car['color_name']; ?></li>
                      <li><i class="fa fa-tachometer" aria-hidden="true"></i><?php echo $car['car_totalmileage']; ?> km</li>
                      <li><i class="fa fa-user" aria-hidden="true"></i><?php echo $car['door_name']; ?></li>
                      <li><i class="fa fa-car" aria-hidden="true"></i><?php echo $car['make_name']; ?></li>
                      <li><i class="fa fa-car" aria-hidden="true"></i><?php echo $car['model_name']; ?></li>
                      <li><i class="fa fa-calendar" aria-hidden="true"></i><?php echo $car['year_name']; ?></li>
                      <li><i class="fa fa-user" aria-hidden="true"></i><?php echo $car['car_sit']; ?></li>
                      <li><i class="fa fa-bolt" aria-hidden="true"></i><?php echo $car['car_engine_name']; ?></li>
                    </ul>
                    <a href="javascript:;" onclick="send_car_request(<?php echo $car['buycar_id']; ?>);" class="btn_car"><?php echo $_lang['__send_request']; ?> <span class="angle_arrow"><i class="fa fa-angle-right" aria-hidden="true"></i></span></a> </div>
                </div>
                <?php } ?>
              </div>
            </div>
            <div class="pag_top"><?php echo $pagination; ?></div>
          </div>
        </div>
        <div class="col-md-3 mt50">
          <div class="category-list mb-55-space">
            <div class="deatils-title">
              <h5><?php echo $_lang['__find_cars']; ?></h5>
            </div>
            <div>
              <div class="form-group select">
                <select onchange="loadModelData(this.value);" name="ddlMake" id="ddlMake" class="form-control">
                  <option value="">Select Make</option>
                  <?php 
					$results = $wms->getAllCarMakeList($link);
					foreach($results as $result) {
						echo "<option value='".$result['make_id']."'>".$result['make_name']."</option>";
				  } ?>
                </select>
              </div>
              <div class="form-group select">
                <select onchange="loadYearData(this.value);" name="ddlModel" id="ddlModel" class="form-control">
                  <option value="">Select Model</option>
                </select>
              </div>
              <div class="form-group select">
                <select name="ddlYear" id="ddlYear" class="form-control">
                  <option value="">Select Year</option>
                </select>
              </div>
              <div class="form-group" align="center">
                <button onclick="filterCarCollection();" class="btn_find_car" type="button"><i class="fa fa-car"></i>&nbsp;&nbsp; <?php echo $_lang['__find_cars_button']; ?></button>
              </div>
            </div>
          </div>
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
<!--request modal-->
<div class="modal car_sell_request fade" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times; </button>
        <header class="page-header">
          <h2><strong>Car Buy Request</strong></h2>
        </header>
      </div>
      <form id="frm_car_request">
        <div class="modal-body">
          <div class="car_request_loader"><img src="<?php echo WEB_URL ;?>/img/ajax-loader.gif" /></div>
          <div align="center"><u>
            <h5><strong id="car_request_name"></strong></h5>
            </u></div>
          <div class="row">
            <div class="col-xs-12">
              <div class="form-group col-xs-12 col-sm-6">
                <label for="name-join">Name <span>*</span></label>
                <input type="text" required class="form-control" id="name-join" name="name">
              </div>
              <div class="form-group col-xs-12 col-sm-6">
                <label for="email-join">Email <span>*</span></label>
                <input type="text" required class="form-control" id="email-join" name="email">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12">
              <div class="form-group col-xs-12 col-sm-6">
                <label for="phone-join">Phone <span>*</span></label>
                <input type="text" required class="form-control" id="phone-join" name="phone">
              </div>
              <div class="form-group col-xs-6">
                <label for="your-price">Your Price (<?php echo $currency; ?>)</label>
                <input type="text" class="form-control" id="your-price" name="price">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12">
              <div class="form-group col-xs-12">
                <label for="message-join">Message <span>*</span></label>
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
        <input type="hidden" name="carid" id="hdn_car_id" />
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<?php $link = $wms->close_db_connection($link); ?>
<?php include('layout/footer.php'); ?>
