<?php include('../cust_panel/header.php'); ?>
<?php
//dashboard widget data
$total_paid = 0;
$total_due = 0;

$settings = $wms->getWebsiteSettingsInformation($link);
$total_car =$wms->getCustomerRepairCarList($link, $_SESSION['objCust']['user_id']);
$car_repair = $wms->getAllRepairCarEstimateList($link, $_SESSION['objCust']['user_id']);
if(!empty($car_repair)) {
	foreach($car_repair as $xcr) {
		$total_paid += (float)$xcr['payment_done'];
		$total_due += (float)$xcr['payment_due'];
	}
}

//get car repair chart data
$months = array(
    'January',
    'February',
    'March',
    'April',
    'May',
    'June',
    'July ',
    'August',
    'September',
    'October',
    'November',
    'December',
);

$total_salary = 0;
$salary_default_data = '0,0,0,0,0,0';
$s_data = $wms->getCustomerRepairReportChartData($link, date('Y'), $_SESSION['objCust']['user_id']);

if(!empty($s_data)) {
	$salary_report_data= '';
	foreach($months as $month){
		$salary_report_data .= arrayValueExist($s_data, $month).',';
	}
	$salary_report_data = trim($salary_report_data, ',');
	$salary_default_data = $salary_report_data;
	foreach($s_data as $arr) {
		$total_salary += (int)$arr['paid_amount'];
	}
}

function arrayValueExist($array, $value) {
	foreach($array as $arr) {
		if(trim($arr['month_name']) == trim($value)) {
			return $arr['paid_amount'];
			break;
		}
	}
	return 0;
}

?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1 style="text-transform:uppercase;font-weight:bold;color:#00a65a;"> <?php echo $settings['site_name'] .' Dashboard';?></h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL; ?>dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Dashboard</li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
  <!-- /.row start -->
  <div class="row home_dash_box mech_dashboard">
    <!-- col start -->
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-aqua">
        <div class="inner">
          <h3><?php echo count($total_car); ?></h3>
          <p><?php if(count($total_car) > 1){echo 'CARS ';} else {'HOUR ';}; ?>CAR</p>
        </div>
        <div class="icon"> <img height="80" width="80" src="<?php echo WEB_URL;?>img/car.png"></a> </div>
        <br/></div>
    </div>
    <!-- ./col end -->
    <!-- col start -->
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-red">
        <div class="inner">
          <h3><?php echo count($car_repair); ?></h3>
          <p>TOTAL REPAIR</p>
        </div>
        <div class="icon"> <img height="80" width="80" src="<?php echo WEB_URL;?>img/car_parts.png"></a> </div>
        <br/></div>
    </div>
    <!-- ./col end -->
	<!-- col start -->
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-orange">
        <div class="inner">
           <h3><?php echo $currency.$total_paid; ?></h3>
          <p>TOTAL PAID</p>
        </div>
        <div class="icon"> <img height="80" width="80" src="<?php echo WEB_URL;?>img/salary.png"></a> </div>
        <br/></div>
    </div>
    <!-- ./col end -->
    <!-- col start -->
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-green">
        <div class="inner">
          <h3><?php echo $currency.$total_due; ?></h3>
          <p>TOTAL DUE</p>
        </div>
        <div class="icon"> <img height="80" width="80" src="<?php echo WEB_URL;?>img/money.png"></a> </div>
        <br/></div>
    </div>
    <!-- ./col end -->
  </div>
  <!-- /.row end -->
  <div class="row">
    <div class="col-md-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-money"></i> Monthly Repair Report (<?php echo $currency; ?>)</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <p class="text-center"> <strong>Repair Report: 1 Jan, <?php echo date('Y');?> - 31 December, <?php echo date('Y');?></strong> </p>
              <div class="chart">
                <canvas id="salesChart" style="height: 180px;"></canvas>
              </div>
            </div>
          </div>
        </div>
        <div class="box-footer no-padding">
          <ul class="nav nav-pills nav-stacked">
            <li><a><b>Total Repair Cost <?php echo date('Y');?></b><span class="pull-right label label-success" style="font-size:12px;"><b><?php echo $currency.number_format($total_salary,2); ?></b></span></a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- /.content -->
<script type="text/javascript">
// Get context with jQuery - using jQuery's .get() method.
  var salesChartCanvas = $('#salesChart').get(0).getContext('2d');
  // This will get the first returned node in the jQuery collection.
  var salesChart       = new Chart(salesChartCanvas);

  var salesChartData = {
    //labels  : ['January', 'February', 'March', 'April', 'May', 'June', 'July ', 'August', 'September', 'October', 'November', 'December'],
	labels  : ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
    datasets: [
      {
        label               : 'Salary Report',
        fillColor           : 'rgba(0, 166, 90, 1)',
        strokeColor         : 'rgba(0, 166, 90, 1)',
        pointColor          : '#00a65a',
        pointStrokeColor    : 'rgba(0, 166, 90, 1)',
        pointHighlightFill  : '#fff',
        pointHighlightStroke: 'rgba(0, 166, 90, 1)',
        data                : [<?php echo $salary_default_data; ?>]
      }
    ]
  };

  var salesChartOptions = {
    // Boolean - If we should show the scale at all
    showScale               : true,
    // Boolean - Whether grid lines are shown across the chart
    scaleShowGridLines      : true,
    // String - Colour of the grid lines
    scaleGridLineColor      : 'rgba(0,0,0,.05)',
    // Number - Width of the grid lines
    scaleGridLineWidth      : 1,
    // Boolean - Whether to show horizontal lines (except X axis)
    scaleShowHorizontalLines: true,
    // Boolean - Whether to show vertical lines (except Y axis)
    scaleShowVerticalLines  : true,
    // Boolean - Whether the line is curved between points
    bezierCurve             : true,
    // Number - Tension of the bezier curve between points
    bezierCurveTension      : 0.3,
    // Boolean - Whether to show a dot for each point
    pointDot                : true,
    // Number - Radius of each point dot in pixels
    pointDotRadius          : 4,
    // Number - Pixel width of point dot stroke
    pointDotStrokeWidth     : 1,
    // Number - amount extra to add to the radius to cater for hit detection outside the drawn point
    pointHitDetectionRadius : 20,
    // Boolean - Whether to show a stroke for datasets
    datasetStroke           : true,
    // Number - Pixel width of dataset stroke
    datasetStrokeWidth      : 2,
    // Boolean - Whether to fill the dataset with a color
    datasetFill             : true,
    // String - A legend template
    //legendTemplate          : '<ul class=\'<%=name.toLowerCase()%>-legend\'><% for (var i=0; i<datasets.length; i++){%><li><span style=\'background-color:<%=datasets[i].lineColor%>\'></span><%=datasets[i].label%></li><%}%></ul>',
    // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
    maintainAspectRatio     : true,
    // Boolean - whether to make the chart responsive to window resizing
    responsive              : true
  };

  // Create the line chart
  salesChart.Line(salesChartData, salesChartOptions);

  // ---------------------------
  // - END MONTHLY SALES CHART -
  // ---------------------------
  
</script>
<?php include('../cust_panel/footer.php'); ?>
