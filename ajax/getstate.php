<?php
	include("../config.php");
	if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' ) ){	
		include("../helper/common.php");
		$wms = new wms_core();
		if(isset($_POST['token']) && $_POST['token'] == 'getstate'){
			$html = '<option value="">--Select State--</option>';
			if(isset($_POST['cid']) && (int)$_POST['cid'] > 0){
				$result = $wms->getAllStateData($link, $_POST['cid']);
				foreach($result as $rows) {
					$html .= '<option value="'.$rows['id'].'">'.$rows['name'] . '</option>';
				}
			}
			echo $html;
			die();
		}
		
		if(isset($_POST['token']) && $_POST['token'] == 'getmodel'){
			$html = '<option value="">--Select Model--</option>';
			if(isset($_POST['mid']) && (int)$_POST['mid'] > 0){
				$result_model = $wms->getModelListByMakeId($link, $_POST['mid']);
				foreach($result_model as $rows) {
					$html .= '<option value="'.$rows['model_id'].'">'.$rows['model_name'] . '</option>';
				}
			}
			echo $html;
			die();
		}
		
		if(isset($_POST['token']) && $_POST['token'] == 'getyear'){
			$html = '<option value="">--Select Year--</option>';
			if(isset($_POST['mid']) && (int)$_POST['mid'] > 0 && isset($_POST['moid']) && (int)$_POST['moid'] > 0){
				$result_year = $wms->getYearlListByMakeIdAndModelId($link, $_POST['mid'], $_POST['moid']);
				foreach($result_year as $rows){
					$html .= '<option value="'.$rows['year_id'].'">'.$rows['year_name'] . '</option>';
				}
			}
			echo $html;
			die();
		}
		
		if(isset($_POST['token']) && $_POST['token'] == 'save_estimate_data'){
			if(isset($_POST['car_id']) && (int)$_POST['car_id'] > 0) {
				$wms->ajaxUpdateEstimateData($link, $_POST);
				echo 'Updated estimate data successfully';
				die();
			}
			echo 'opps error occured refresh your page';
			die();
		}
	} else {
		$url = WEB_URL.'index.php';
		header("Location: $url");
		die();
	}
?>
