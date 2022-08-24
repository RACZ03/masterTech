<?php
	session_start();
	include("../config.php");
	include("../helper/common.php");
	$wms = new wms_core();
	if(isset($_POST['token']) && $_POST['token'] == 'save_parts_to_cart'){
		$cart_array = array();
		if(isset($_SESSION['parts_cart']) && !empty($_SESSION['parts_cart'])) {
			$cart_array = $_SESSION['parts_cart'];
			$exist = false;
			for($i=0;$i<count($cart_array);$i++){
				if((int)$cart_array[$i]['parts_id'] == (int)$_POST['parts_id']) {
					$cart_array[$i]['qty'] = (int)$cart_array[$i]['qty'] + (int)$_POST['qty'];
					$exist = true;
					break;
				}
			}
			if(!$exist) {
				$cart_array[] = array(
					'parts_id'	=> $_POST['parts_id'],
					'name'		=> $_POST['name'],
					'price'		=> $_POST['price'],
					'qty'		=> $_POST['qty'],
					'warranty'	=> $_POST['warranty'],
					'condition'	=> $_POST['condition']
				);
			}
		} else {
			$cart_array[] = array(
				'parts_id'	=> $_POST['parts_id'],
				'name'		=> $_POST['name'],
				'price'		=> $_POST['price'],
				'qty'		=> $_POST['qty'],
				'warranty'	=> $_POST['warranty'],
				'condition'	=> $_POST['condition']
			);
		}
		$_SESSION['parts_cart'] = $cart_array;
		echo $wms->loadMiniCartHtml();
		die();
	} else if(isset($_POST['token']) && $_POST['token'] == 'delete_parts_to_cart'){
		if(isset($_SESSION['parts_cart']) && !empty($_SESSION['parts_cart'])) {
			$cart_array = $_SESSION['parts_cart'];
			for($i=0;$i<count($cart_array);$i++){
				if((int)$cart_array[$i]['parts_id'] == (int)$_POST['parts_id']) {
					array_splice($cart_array, $i, 1);
					break;
				}
			}
			$_SESSION['parts_cart'] = $cart_array;
		}
	} else if(isset($_POST['token']) && $_POST['token'] == 'return_sold_parts'){
		$wms->deleteAndReturnPartsData($link, $_POST['sold_id'], $_POST['parts_id'], $_POST['qty']);
	} else {
		echo 'wrong request';
		die();
	}
?>
