<?php
	include("../config.php");
	if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' ) ){
		include("../helper/common.php");
		$wms = new wms_core();
		$html = '';
		if(!empty($_POST['name'])){
			$wms->saveCarRequestInformation($_POST, $link);
			$html = 'Sent car request information successfully, we will back to you soon thanks.';
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
