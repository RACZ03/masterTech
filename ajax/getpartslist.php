<?php
	include("../config.php");
	$json = array();
	$html = '';
	if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' ) ){
		include("../helper/common.php");
		$wms = new wms_core();
		if(!empty($_POST['token']) && $_POST['token'] == 'mmy'){
			$result = $wms->ajaxPartsListByMakeModelYear($link, $_POST);
			foreach($result as $rows) {
				$warranty = 'N/A';
				if(!empty($rows['parts_warranty'])) {
					$warranty = string_sanitize(trim($rows['parts_warranty']));
				}
				
				$image = '';
				if($rows['parts_image'] != ''){ $image = WEB_URL . 'img/upload/' . $rows['parts_image'];}
				$html .= "<tr onclick=addDataToEstimate(this,'".$rows['parts_id']."','".$rows['price']."','".$rows['quantity']."','".$warranty."'); style='cursor:pointer;'><td><img style='width:50px;height:50px;' class='img-thumbnail' src='".$image."' /></td><td class='parts_name'>".$rows['parts_name']."</td><td>".$rows['price']."</td><td align='center'>".$rows['parts_warranty']."</td><td class='text-center'>".$rows['quantity']."</td></tr>";
			}
		} else if(!empty($_POST['token']) && $_POST['token'] == 'name'){
			$result = $wms->ajaxPartsListByPartsName($link, $_POST);
			foreach($result as $rows) {
				$warranty = 'N/A';
				if(!empty($rows['parts_warranty'])) {
					$warranty = string_sanitize(trim($rows['parts_warranty']));
				}
				$image = '';
				if($rows['parts_image'] != ''){ $image = WEB_URL . 'img/upload/' . $rows['parts_image'];}
				$html .= "<tr onclick=addDataToEstimate(this,'".$rows['parts_id']."','".$rows['price']."','".$rows['quantity']."','".$warranty."'); style='cursor:pointer;'><td><img style='width:50px;height:50px;' class='img-thumbnail' src='".$image."' /></td><td class='parts_name'>".$rows['parts_name']."</td><td>".$rows['price']."</td><td align='center'>".$rows['parts_warranty']."</td><td class='text-center'>".$rows['quantity']."</td></tr>";
			}
		} else if(!empty($_POST['token']) && $_POST['token'] == 'getsalaryamount'){
			$result = $wms->ajaxGetMechanicsSalary($link, $_POST['mid']);
			if(!empty($result) && isset($result['m_cost'])){
				$html = $result['m_cost'];
			}
		} else if(!empty($_POST['token']) && $_POST['token'] == 'getmothhour'){
			$result = $wms->ajaxGetMechanicsMonthTotalHour($link, $_POST['mechanic_id'], $_POST['month_id'], $_POST['year_id']);
			if(!empty($result) && isset($result['total_hour'])){
				$html = $result['total_hour'];
			}
		}
		header('Content-Type: text/html');
		echo $html;
		die();
	} else {
		$url = WEB_URL.'index.php';
		header("Location: $url");
		die();
	}
	
	/*$sort_order = array();

	foreach ($json as $key => $value) {
		$sort_order[$key] = $value['name'];
	}

	array_multisort($sort_order, SORT_ASC, $json);

	header('Content-Type: application/json');
	echo json_encode($json);*/
function string_sanitize($s) {
    $result = preg_replace("/[^a-zA-Z0-9]+/", "-", $s);
    return $result;
}	
?>
