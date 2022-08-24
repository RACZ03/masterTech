<?php
	include("../config.php");
	if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' ) ){
		include("../helper/common.php");
		$wms = new wms_core();
		$html = '';
		if(!empty($_POST['email'])){
			$html = $wms->sendMailChimp($_POST, $link);
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
