<?php
	include("../config.php");
	if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' ) ){
		include("../helper/common.php");
		$wms = new wms_core();
		$html_model = '<option value="">Select Year</option>';
		$html_year = '<option value="">Select Model</option>';
		$html = '';
		if(!empty($_POST['make']) && !empty($_POST['model'])){
			$results = $wms->getYearlListByMakeIdAndModelId($link, $_POST['make'], $_POST['model']);
			foreach($results as $result) {
				$html_model .= '<option value="'.$result['year_id'].'">'.$result['year_name'].'</option>';
			}
			$html = $html_model;
		} else if(!empty($_POST['make'])){
			$results = $wms->getModelListByMakeId($link, $_POST['make']);
			foreach($results as $result) {
				$html_year .= '<option value="'.$result['model_id'].'">'.$result['model_name'].'</option>';
			}
			$html = $html_year;
		}
		header('Content-Type: text/html');
		echo $html;
		die();
	} else {
		$url = WEB_URL.'index.php';
		header("Location: $url");
		die();
	}
?>
