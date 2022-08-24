<?php
	include("../config.php");
	if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' ) ){
		include("../helper/common.php");
		$wms = new wms_core();
		$html = '';
		if(!empty($_POST['name'])){
			$wms->saveContactInfo($_POST, $link);
			$wms->sendContactUSEmail($link, trim($_POST['name']), trim($_POST['email']), trim($_POST['subject']), trim($_POST['message']));
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
