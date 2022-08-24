<?php include('../mech_panel/header.php'); ?>
<?php
//dashboard widget data
$total_paid = 0;
$total_due = 0;
$total_earn = 0;

$total_amount_paid = $wms->getMechaniceTotalPaidAmount($link, $_SESSION['objMech']['user_id']);
if(!empty($total_amount_paid['total_paid_amount'])) {
	$total_paid = $total_amount_paid['total_paid_amount'];
}
if(!empty($total_amount_paid['total_due_amount'])) {
	$total_due = $total_amount_paid['total_due_amount'];
}
if(!empty($total_amount_paid['total'])) {
	$total_earn = $total_amount_paid['total'];
}
$total_hour = $wms->getMechaniceTotalHourList($link, $_SESSION['objMech']['user_id']);
$car_stock = $wms->getAllActiveCarList($link);
$mechanic = $wms->getAllMechanicsList($link);
$settings = $wms->getWebsiteSettingsInformation($link);

//get all parts info by current year
$sold_parts = $wms->getMechaniceMonthlyHourData($link, date('Y'), $_SESSION['objMech']['user_id']);
$total_parts_year_sold = 0;
$parts_sell_report = '';
$parts_sell_segments = array();
if(!empty($sold_parts)) {
	foreach($sold_parts as $sparts) {
		$ccode = $wms->getChartColorCodeByMonth($sparts['month_name']);
		$parts_sell_report[] = array(
			'value'			=> $sparts['total_hour'],
			'color'			=> $ccode,
			'highlight'		=> $ccode,
			'label'			=> ' Hour Worked'.' '.$sparts['month_name']
		);
		$total_parts_year_sold += (int)$sparts['total_hour'];
	}
	if(!empty($parts_sell_report)){
		$parts_sell_segments = $parts_sell_report;
		$parts_sell_report = json_encode($parts_sell_report, JSON_NUMERIC_CHECK);
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
$s_data = $wms->getMechaniceSalaryReportChartData($link, date('Y'), $_SESSION['objMech']['user_id']);

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
          <h3><?php echo $total_hour; ?></h3>
          <p><?php if($total_hour > 1){echo 'HOURS ';} else {'HOUR ';}; ?>TOTAL WORK</p>
        </div>
        <div class="icon"> <img height="80" width="80" src="<?php echo WEB_URL;?>img/car_parts.png"></a> </div>
        <br/></div>
    </div>
    <!-- ./col end -->
    <!-- col start -->
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-red">
        <div class="inner">
          <h3><?php echo $currency.$total_earn; ?></h3>
          <p>TOTAL INCOME</p>
        </div>
        <div class="icon"> <img height="80" width="80" src="<?php echo WEB_URL;?>img/dollar.png"></a> </div>
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
    <div class="col-md-8">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-money"></i> Monthly Salary Report (<?php echo $currency; ?>)</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <p class="text-center"> <strong>Salary Report: 1 Jan, <?php echo date('Y');?> - 31 December, <?php echo date('Y');?></strong> </p>
              <div class="chart">
                <canvas id="salesChart" style="height: 180px;"></canvas>
              </div>
            </div>
          </div>
        </div>
        <div class="box-footer no-padding">
          <ul class="nav nav-pills nav-stacked">
            <li><a><b>Total Salary Paid <?php echo date('Y');?></b><span class="pull-right label label-success" style="font-size:12px;"><b><?php echo $currency.$total_salary; ?></b></span></a></li>
          </ul>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-wrench"></i> Monthly Working Hour Report</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-lg-12 col-xs-12 col-md-12">
              <div class="chart-responsive">
                <canvas id="pieChart" height="119"></canvas>
              </div>
            </div>
          </div>
          <div class="col-md-12">
            <?php if(!empty($parts_sell_segments)) { ?>
            <ul class="chart-legend clearfix">
              <?php foreach($parts_sell_segments as $hdata) { ?>
              <li><i class="fa fa-circle-o" style="color:<?php echo $hdata['color'];?>"></i> <?php echo $hdata['value'];?> <?php echo $hdata['label'];?></li>
              <?php } ?>
            </ul>
            <?php } ?>
          </div>
        </div>
        <div class="box-footer no-padding">
          <ul class="nav nav-pills nav-stacked">
            <li><a><b>Total Hour Worked Year <?php echo date('Y');?></b><span class="pull-right label label-success" style="font-size:12px;"><b><?php echo $total_parts_year_sold; ?></b></span></a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Latest 10 Work List</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="table-responsive">
            <table class="table no-margin">
              <thead>
                <tr>
                  <th>Worked Date</th>
                  <th>Total Hour</th>
                  <th>Work Details</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php $result = $wms->getAllWorkStatusList($link, $_SESSION['objMech']['user_id']); ?>
                <?php $i=1; foreach($result as $row) { ?>
                <tr>
                  <td><label class="label label-success"><?php echo $wms->mySqlToDatePicker($row['work_date']); ?></label></td>
                  <td><label class="label label-danger"><?php echo $row['total_hour']; ?></label></td>
                  <td><?php echo $row['work_details']; ?></td>
                  <td><a class="btn btn-success" data-toggle="tooltip" href="<?php echo WEB_URL;?>mech_panel/addworkstatus.php?wid=<?php echo $row['work_id']; ?>" data-original-title="Edit"><i class="fa fa-pencil"></i></a> <a class="btn btn-danger" data-toggle="tooltip" onClick="deleteCustomer(<?php echo $row['work_id']; ?>);" href="javascript:;" data-original-title="Delete"><i class="fa fa-trash-o"></i></a></td>
                </tr>
                <?php if($i==10) break;?>
                <?php $i++;} ?>
              </tbody>
            </table>
          </div>
          <!-- /.table-responsive -->
        </div>
        <!-- /.box-body -->
        <div class="box-footer clearfix"><a href="<?php echo WEB_URL;?>mech_panel/workstatus.php" class="btn btn-sm btn-success btn-flat pull-right"><b><i class="fa fa-list"></i>  &nbsp;View All List</b></a> </div>
        <!-- /.box-footer -->
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
  
  
  // -------------
  // - PIE CHART -
  // -------------
  // Get context with jQuery - using jQuery's .get() method.
  var pieChartCanvas = $('#pieChart').get(0).getContext('2d');
  var pieChart       = new Chart(pieChartCanvas);
  var PieData        = <?php echo $parts_sell_report; ?>;
  var pieOptions     = {
    // Boolean - Whether we should show a stroke on each segment
    segmentShowStroke    : true,
    // String - The colour of each segment stroke
    segmentStrokeColor   : '#fff',
    // Number - The width of each segment stroke
    segmentStrokeWidth   : 1,
    // Number - The percentage of the chart that we cut out of the middle
    percentageInnerCutout: 50, // This is 0 for Pie charts
    // Number - Amount of animation steps
    animationSteps       : 100,
    // String - Animation easing effect
    animationEasing      : 'easeOutBounce',
    // Boolean - Whether we animate the rotation of the Doughnut
    animateRotate        : true,
    // Boolean - Whether we animate scaling the Doughnut from the centre
    animateScale         : false,
    // Boolean - whether to make the chart responsive to window resizing
    responsive           : true,
    // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
    maintainAspectRatio  : false,
    // String - A legend template
   // legendTemplate       : '<ul class=\'<%=name.toLowerCase()%>-legend\'><% for (var i=0; i<segments.length; i++){%><li><span style=\'background-color:<%=segments[i].fillColor%>\'></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>',
    // String - A tooltip template
   // tooltipTemplate      : '<%=value %> <%=label%>'
  };
  // Create pie or douhnut chart
  // You can switch between pie and douhnut using the method below.
  pieChart.Doughnut(PieData, pieOptions);
  // -----------------
  // - END PIE CHART -
  // -----------------
</script>
<?php include('../mech_panel/footer.php'); ?>
