<?php
//include_once('../config.php');
class wms_core {
	public function getAllCountries($con) {
		$countries = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_countries order by name ASC");
		while($row = mysqli_fetch_array($result)){
			$countries[] = array(
				'country_id'	=> $row['id'],
				'name'	=> $row['name']
			);
		}
		return $countries;
	}
	
	//return all states based on country
	public function getStateByCountryId($con, $cid) {
		$states = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_states WHERE country_id = ".(int)$cid." order by name ASC");
		while($row = mysqli_fetch_array($result)){
			$states[] = array(
				'id'	=> $row['id'],
				'name'	=> $row['name']
			);
		}
		return $states;
	}
	
	/*
	* @get all customer list
	*/
	public function getAllCustomerList($con) {
		$data = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_add_customer order by c_name ASC");
		while($row = mysqli_fetch_array($result)){
			$data[] = $row;
		}
		return $data;
	}
	
	/*
	* @get all work status list
	*/
	public function getAllWorkStatusList($con, $mech_id) {
		$data = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_daily_work WHERE mechanic_id = '".(int)$mech_id."' order by work_id DESC");
		while($row = mysqli_fetch_array($result)){
			$data[] = $row;
		}
		return $data;
	}
	
	/*
	* @get all mechanic salery list
	*/
	public function getAllMechnahicSaleryList($con) {
		$data = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_mcncsslary ms left join tbl_add_mechanics am on am.mechanics_id = ms.mechanics_id order by ms.m_salary_id");
		while($row = mysqli_fetch_array($result)){
			$data[] = $row;
		}
		return $data;
	}
	
	/*
	* @get all mechanic list sort byName
	*/
	public function getAllMechanicListSortByName($con) {
		$data = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_add_mechanics order by m_name ASC");
		while($row = mysqli_fetch_array($result)){
			$data[] = $row;
		}
		return $data;
	}
	
	/*
	* @get all state list
	*/
	public function getAllStateData($con, $country_id) {
		$data = array();
		$result = mysqli_query($con,"SELECT * from tbl_states where country_id = ".(int)$country_id);
		while($row = mysqli_fetch_array($result)){
			$data[] = $row;
		}
		return $data;
	}
	
	/*
	* @get all repair car list
	*/
	public function getAllRepairCarList($con) {
		$data = array();
		$result = mysqli_query($con,"SELECT *,ac.image as car_image,c.c_name,c.image as customer_image, c.c_email, c.c_mobile, mec.mechanics_id, mec.m_image, mec.m_name, mec.m_cost, mec.m_phone_number, mec.m_email, m.make_name, mo.model_name, y.year_name, ac.repair_car_id FROM tbl_add_car ac inner join tbl_add_customer c on c.customer_id = ac.customer_id inner join tbl_add_mechanics mec on mec.mechanics_id = ac.mechanics_id inner join tbl_make m on m.make_id = ac.car_make inner join tbl_model mo on mo.model_id = ac.car_model inner join tbl_year y on y.year_id = ac.year order by ac.car_id DESC");
		while($row = mysqli_fetch_array($result)){
			$data[] = $row;
		}
		return $data;
	}
	
	/*
	* @get customer repair car list
	*/
	public function getCustomerRepairCarList($con, $customer_id) {
		$data = array();
		$result = mysqli_query($con,"SELECT *,ac.image as car_image,c.c_name,c.image as customer_image,c.c_email,c.c_mobile,m.make_name,mo.model_name,y.year_name,ac.repair_car_id FROM tbl_add_car ac inner join tbl_add_customer c on c.customer_id = ac.customer_id inner join tbl_make m on m.make_id = ac.car_make inner join tbl_model mo on mo.model_id = ac.car_model inner join tbl_year y on y.year_id = ac.year WHERE ac.customer_id = ".(int)$customer_id." order by ac.car_id DESC");
		while($row = mysqli_fetch_array($result)){
			$data[] = $row;
		}
		return $data;
	}
	
	
	/*
	* @get customer repair car details 
	*/
	public function getCustomerRepairCarDetails($con, $customer_id, $repair_car_id) {
		$data = array();
		$result = mysqli_query($con,"SELECT *,ac.image as car_image,c.c_name,c.image as customer_image,c.c_email,c.c_mobile,m.make_name,mo.model_name,y.year_name,ac.repair_car_id FROM tbl_add_car ac inner join tbl_add_customer c on c.customer_id = ac.customer_id inner join tbl_make m on m.make_id = ac.car_make inner join tbl_model mo on mo.model_id = ac.car_model inner join tbl_year y on y.year_id = ac.year WHERE ac.customer_id = ".(int)$customer_id." AND ac.repair_car_id = ".(int)$repair_car_id." order by ac.car_id DESC");
		if($row = mysqli_fetch_assoc($result)){
			$data = $row;
		}
		return $data;
	}
	
	/*
	* @estimate ajax parts list filter
	*/
	public function ajaxPartsListByMakeModelYear($con, $post) {
		$data = array();
		$result = mysqli_query($con,"SELECT * from tbl_parts_fit_data fd INNER JOIN  tbl_parts_stock_manage ps ON ps.parts_id = fd.parts_id where fd.make_id = '" . (int)$post['make_id'] . "' AND fd.model_id = '" . (int)$post['model_id'] . "' AND fd.year_id = '" . (int)$post['year_id'] . "'");
		while($row = mysqli_fetch_array($result)){
			$data[] = $row;
		}
		return $data;
	}
	
	/*
	* @estimate ajax parts like filter
	*/
	public function ajaxPartsListByPartsName($con, $post) {
		$data = array();
		$result = mysqli_query($con,"SELECT * from tbl_parts_stock_manage where parts_name LIKE '" . trim($post['filter_name']) . "%'");
		while($row = mysqli_fetch_array($result)){
			$data[] = $row;
		}
		return $data;
	}
	
	/*
	* @ajax mechanics salary
	*/
	public function ajaxGetMechanicsSalary($con, $mid) {
		$data = array();
		$result = mysqli_query($con,"SELECT * from tbl_add_mechanics where mechanics_id = '" . (int)$mid . "'");
		if($row = mysqli_fetch_array($result)){
			$data = $row;
		}
		return $data;
	}
	
	/*
	* @ajax mechanics per month hour
	*/
	public function ajaxGetMechanicsMonthTotalHour($con, $mechanic_id, $month_id, $year_id) {
		$data = array();
		$result = mysqli_query($con,"SELECT sum(total_hour) as total_hour from tbl_daily_work where mechanic_id = '" . (int)$mechanic_id . "' AND MONTH(work_date) = '".$month_id."' AND YEAR(work_date) = '".$year_id."'");
		if($row = mysqli_fetch_array($result)){
			$data = $row;
		}
		return $data;
	}
	
	/*
	* @get all manufacturer list
	*/
	public function getAllManufacturerList($con) {
		$manufacturers = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_manufacturer order by manufacturer_name ASC");
		while($row = mysqli_fetch_array($result)){
			$image = WEB_URL . 'img/no_image.jpg';
			if($row['manufacturer_image'] != ''){
				$image = WEB_URL . 'img/upload/' . $row['manufacturer_image'];
			}
			$manufacturers[] = array(
				'id'		=> $row['manufacturer_id'],
				'name'		=> $row['manufacturer_name'],
				'image' 	=> $image
			);
		}
		return $manufacturers;
	}
	/*
	* get manufacturer id for supplier
	*/
	public function getManufacturersForSupplier($con, $sid) {
		$supplierManufacturer = array();
		$result = mysqli_query($con,"SELECT *,m.manufacturer_name,m.manufacturer_image FROM tbl_supplier_manufacturer sm inner join tbl_manufacturer m on m.manufacturer_id = sm.manufacturer_id WHERE sm.supplier_id = ".(int)$sid." order by sm.manufacturer_id ASC");
		while($row = mysqli_fetch_array($result)){
			$image = '';
			if($row['manufacturer_image'] != ''){
				$image = WEB_URL . 'img/upload/' . $row['manufacturer_image'];
			}
			$supplierManufacturer[] = array(
				'image'					=> $image,
				'name'					=> $row['manufacturer_name'],
				'supplier_id'			=> $row['supplier_id'],
				'manufacturer_id'		=> $row['manufacturer_id']
			);
		}
		return $supplierManufacturer;
	}
	
	/*
	* @get all parts list
	*/
	public function getAllPartsList($con, $page, $limit) {
		$parts_list = array();
		$start_from = ($page-1) * $limit;
		//$result = mysqli_query($con,"SELECT *,s.s_name,mu.manufacturer_name,m.make_name,mo.model_name,y.year_name FROM tbl_parts_stock ps LEFT JOIN tbl_add_supplier s ON s.supplier_id = ps.supplier_id LEFT JOIN tbl_manufacturer mu ON mu.manufacturer_id = ps.manufacturer_id left join tbl_make m on m.make_id = ps.make_id left join tbl_model mo on mo.model_id = ps.model_id left join tbl_year y on y.year_id = ps.year_id ORDER BY ps.parts_name ASC");
		$result = mysqli_query($con,"SELECT *,s.s_name,mu.manufacturer_name FROM tbl_parts_stock_manage ps LEFT JOIN tbl_add_supplier s ON s.supplier_id = ps.supplier_id LEFT JOIN tbl_manufacturer mu ON mu.manufacturer_id = ps.manufacturer_id ORDER BY ps.parts_name ASC LIMIT ".$start_from.",".$limit);
		while($row = mysqli_fetch_array($result)){
			$image = '';
			if($row['parts_image'] != ''){ $image = WEB_URL . 'img/upload/' . $row['parts_image'];}
			$parts_list[] = array(
				'parts_id'				=> $row['parts_id'],
				'parts_name'			=> $row['parts_name'],
				//'make_id'				=> $row['make_id'],
				//'model_id'			=> $row['model_id'],
				//'year_id'				=> $row['year_id'],
				'supplier_id'			=> $row['supplier_id'],
				'manufacturer_id'		=> $row['manufacturer_id'],
				'parts_condition'		=> $row['condition'],
				'quantity'				=> $row['quantity'],
				'parts_warranty'		=> !empty($row['parts_warranty']) ? $row['parts_warranty'] : 'NO',
				'parts_sku'				=> $row['part_no'],
				'parts_sell_price'		=> $row['price'],
				'parts_image'			=> $image,
				//'make'				=> $row['make_name'],
				//'model'				=> $row['model_name'],
				//'year'				=> $row['year_name'],
				'manufacturer_name'		=> $row['manufacturer_name'],
				'supplier_name'			=> $row['s_name']
			);
		}
		return $parts_list;
	}
	
	/*
	* @count all parts list by M M Y
	*/
	public function countAllPartsListByMakeModelYear($con, $data) {
		$count = 0;
		$result = array();
		if(!empty($data['make']) && !empty($data['model']) && !empty($data['year'])) {
			$result = mysqli_query($con,"SELECT count(ps.parts_id) as total FROM tbl_parts_fit_data pft INNER JOIN tbl_parts_stock_manage ps ON ps.parts_id = pft.parts_id LEFT JOIN tbl_add_supplier s ON s.supplier_id = ps.supplier_id LEFT JOIN tbl_manufacturer mu ON mu.manufacturer_id = ps.manufacturer_id WHERE pft.make_id = ".(int)$data['make']." AND pft.model_id = ".(int)$data['model']." AND pft.year_id = ".(int)$data['year']." ORDER BY ps.parts_name ASC");	
		} else if(!empty($data['make']) && !empty($data['model'])) {
			$result = mysqli_query($con,"SELECT count(ps.parts_id) as total FROM tbl_parts_fit_data pft INNER JOIN tbl_parts_stock_manage ps ON ps.parts_id = pft.parts_id LEFT JOIN tbl_add_supplier s ON s.supplier_id = ps.supplier_id LEFT JOIN tbl_manufacturer mu ON mu.manufacturer_id = ps.manufacturer_id WHERE pft.make_id = ".(int)$data['make']." AND pft.model_id = ".(int)$data['model']." ORDER BY ps.parts_name ASC");	
		} else if(!empty($data['make'])) {
			$result = mysqli_query($con,"SELECT count(ps.parts_id) as total FROM tbl_parts_fit_data pft INNER JOIN tbl_parts_stock_manage ps ON ps.parts_id = pft.parts_id LEFT JOIN tbl_add_supplier s ON s.supplier_id = ps.supplier_id LEFT JOIN tbl_manufacturer mu ON mu.manufacturer_id = ps.manufacturer_id WHERE pft.make_id = ".(int)$data['make']." ORDER BY ps.parts_name ASC");		
		}
		if(!empty($result)) {
			if($row = mysqli_fetch_array($result)){
				$count = $row['total'];
			}
		}
		return $count;
	}
	
	/*
	* @get all parts list based on make model and year
	*/
	public function getAllPartsListByMakeModelYear($con, $page, $limit, $data) {
		$parts_list = array();
		$start_from = ($page-1) * $limit;
		$result = '';
		if(!empty($data['make']) && !empty($data['model']) && !empty($data['year'])) {
			$result = mysqli_query($con,"SELECT *,s.s_name,mu.manufacturer_name FROM tbl_parts_fit_data pft INNER JOIN tbl_parts_stock_manage ps ON ps.parts_id = pft.parts_id LEFT JOIN tbl_add_supplier s ON s.supplier_id = ps.supplier_id LEFT JOIN tbl_manufacturer mu ON mu.manufacturer_id = ps.manufacturer_id WHERE pft.make_id = ".(int)$data['make']." AND pft.model_id = ".(int)$data['model']." AND pft.year_id = ".(int)$data['year']." ORDER BY ps.parts_name ASC LIMIT ".$start_from.",".$limit);	
		} else if(!empty($data['make']) && !empty($data['model'])) {
			$result = mysqli_query($con,"SELECT *,s.s_name,mu.manufacturer_name FROM tbl_parts_fit_data pft INNER JOIN tbl_parts_stock_manage ps ON ps.parts_id = pft.parts_id LEFT JOIN tbl_add_supplier s ON s.supplier_id = ps.supplier_id LEFT JOIN tbl_manufacturer mu ON mu.manufacturer_id = ps.manufacturer_id WHERE pft.make_id = ".(int)$data['make']." AND pft.model_id = ".(int)$data['model']." ORDER BY ps.parts_name ASC LIMIT ".$start_from.",".$limit);	
		} else if(!empty($data['make'])) {
			$result = mysqli_query($con,"SELECT *,s.s_name,mu.manufacturer_name FROM tbl_parts_fit_data pft INNER JOIN tbl_parts_stock_manage ps ON ps.parts_id = pft.parts_id LEFT JOIN tbl_add_supplier s ON s.supplier_id = ps.supplier_id LEFT JOIN tbl_manufacturer mu ON mu.manufacturer_id = ps.manufacturer_id WHERE pft.make_id = ".(int)$data['make']." ORDER BY ps.parts_name ASC LIMIT ".$start_from.",".$limit);		
		}
		if(!empty($result)) {
			while($row = mysqli_fetch_array($result)){
				$image = '';
				if($row['parts_image'] != ''){ $image = WEB_URL . 'img/upload/' . $row['parts_image'];}
				$parts_list[] = array(
					'parts_id'				=> $row['parts_id'],
					'parts_name'			=> $row['parts_name'],
					'supplier_id'			=> $row['supplier_id'],
					'manufacturer_id'		=> $row['manufacturer_id'],
					'parts_condition'		=> $row['condition'],
					'quantity'				=> $row['quantity'],
					'parts_warranty'		=> !empty($row['parts_warranty']) ? $row['parts_warranty'] : 'NO',
					'parts_sku'				=> $row['part_no'],
					'parts_sell_price'		=> $row['price'],
					'parts_image'			=> $image,
					'manufacturer_name'		=> $row['manufacturer_name'],
					'supplier_name'			=> $row['s_name']
				);
			}
		}
		return $parts_list;
	}
	
	/*
	* @get parts info by id
	*/
	/*public function getPartsInformationById($parts_id, $con) {
		$parts_list = array();
		$result = mysqli_query($con,"SELECT *,s.s_name,mu.manufacturer_name,m.make_name,mo.model_name,y.year_name FROM tbl_parts_stock ps LEFT JOIN tbl_add_supplier s ON s.supplier_id = ps.supplier_id LEFT JOIN tbl_manufacturer mu ON mu.manufacturer_id = ps.manufacturer_id left join tbl_make m on m.make_id = ps.make_id left join tbl_model mo on mo.model_id = ps.model_id left join tbl_year y on y.year_id = ps.year_id WHERE parts_id = '".(int)$parts_id."'");
		if($row = mysqli_fetch_array($result)){
			$image = '';
			if($row['parts_image'] != ''){ $image = WEB_URL . 'img/upload/' . $row['parts_image'];}
			$parts_list = array(
				'parts_id'				=> $row['parts_id'],
				'parts_name'			=> $row['parts_name'],
				'make_id'				=> $row['make_id'],
				'model_id'				=> $row['model_id'],
				'year_id'				=> $row['year_id'],
				'supplier_id'			=> $row['supplier_id'],
				'manufacturer_id'		=> $row['manufacturer_id'],
				'parts_condition'		=> $row['parts_condition'],
				'parts_buy_price'		=> $row['parts_buy_price'],
				'parts_quantity'		=> $row['parts_quantity'],
				'parts_sku'				=> $row['parts_sku'],
				'parts_warranty'		=> !empty($row['parts_warranty']) ? $row['parts_warranty'] : 'N/A',
				'parts_sell_price'		=> $row['parts_sell_price'],
				'parts_image'			=> $image,
				'parts_added_date'		=> $row['parts_added_date'],
				'make'					=> $row['make_name'],
				'model'					=> $row['model_name'],
				'year'					=> $row['year_name'],
				'manufacturer_name'		=> $row['manufacturer_name'],
				'supplier_name'			=> $row['s_name']
			);
		}
		return $parts_list;
	}*/
	
	public function getPartsInformationById($parts_id, $con) {
		$parts_list = array();
		$result = mysqli_query($con,"SELECT *,s.s_name,mu.manufacturer_name FROM tbl_parts_stock_manage ps LEFT JOIN tbl_add_supplier s ON s.supplier_id = ps.supplier_id LEFT JOIN tbl_manufacturer mu ON mu.manufacturer_id = ps.manufacturer_id WHERE parts_id = '".(int)$parts_id."'");
		if($row = mysqli_fetch_array($result)){
			$image = '';
			if($row['parts_image'] != ''){ $image = WEB_URL . 'img/upload/' . $row['parts_image'];}
			$parts_list = array(
				'parts_id'				=> $row['parts_id'],
				'parts_name'			=> $row['parts_name'],
				'supplier_id'			=> $row['supplier_id'],
				'manufacturer_id'		=> $row['manufacturer_id'],
				'parts_condition'		=> $row['condition'],
				'parts_quantity'		=> $row['quantity'],
				'parts_sku'				=> $row['part_no'],
				'parts_warranty'		=> !empty($row['parts_warranty']) ? $row['parts_warranty'] : 'N/A',
				'parts_sell_price'		=> $row['price'],
				'parts_image'			=> $image,
				'manufacturer_name'		=> $row['manufacturer_name'],
				'supplier_name'			=> $row['s_name']
			);
		}
		return $parts_list;
	}
	
	/*
	* @get all make list
	*/
	public function get_all_make_list($con) {
		$make = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_make order by make_name ASC");
		while($row = mysqli_fetch_array($result)){
			$make[] = array(
				'make_id'		=> $row['make_id'],
				'make_name'		=> $row['make_name']
			);
		}
		return $make;
	}

	/*
	* @get make by Name
	*/
	public function get_make_by_name($con, $makeName) {
		$make = array();
		$result = mysqli_query($con,"SELECT * FROM `tbl_make` WHERE make_name = '". $makeName ."'");
		while($row = mysqli_fetch_array($result)){
			$make[] = array(
				'make_id'		=> $row['make_id'],
				'make_name'		=> $row['make_name']
			);
		}
		return $make;
	}
	
	/*
	* @get all model list
	*/
	public function get_all_model_list($con) {
		$model = array();
		$result = mysqli_query($con,"SELECT *,ma.make_name FROM tbl_model mo INNER JOIN tbl_make ma ON mo.make_id = ma.make_id order by ma.make_name ASC");
		while($row = mysqli_fetch_array($result)){
			$model[] = array(
				'model_id'		=> $row['model_id'],
				'make_id'		=> $row['make_id'],
				'model_name'	=> $row['model_name'],
				'make_name'		=> $row['make_name']
			);
		}
		return $model;
	}

	/*
	* @get all model list
	*/
	public function get_all_car_door_list($con) {
		$model = array();
		$result = mysqli_query($con,"SELECT * FROM `tbl_cardoor`");
		while($row = mysqli_fetch_array($result)){
			$model[] = array(
				'door_id'		=> $row['door_id'],
				'door_name'		=> $row['door_name']
			);
		}
		return $model;
	}

	public function get_model_by_name($con, $modelName) {
		$model = array();
		$result = mysqli_query($con, "SELECT * FROM tbl_model WHERE model_name = '". $modelName ."'");
		while($row = mysqli_fetch_array($result)){
			$model[] = array(
				'model_id'		=> $row['model_id'],
				'model_name'	=> $row['model_name']
			);
		}
		return $model;
	}
	
	/*
	* @get all year list
	*/
	public function get_all_year_list($con) {
		$model = array();
		$result = mysqli_query($con,"SELECT *,m.make_name,mo.model_name FROM tbl_year y inner join tbl_make m on m.make_id = y.make_id inner join tbl_model mo on mo.model_id = y.model_id order by make_name ASC");
		while($row = mysqli_fetch_array($result)){
			$model[] = array(
				'year_id'		=> $row['year_id'],
				'make_id'		=> $row['make_id'],
				'model_id'		=> $row['model_id'],
				'year_name'		=> $row['year_name'],
				'make_name'		=> $row['make_name'],
				'model_name'	=> $row['model_name']
			);
		}
		return $model;
	}
	
	/*
	* @get all year list
	*/
	/*public function getEstimateRespairData($con, $car_id, $customer_id) {
		$data = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_add_car WHERE car_id = '".(int)$car_id."' AND customer_id = '".(int)$customer_id."'");
		if($row = mysqli_fetch_array($result)){
			$data = $row;
		}
		return $data;
	}*/
	
	/*
	* @get customer and car info by card id
	*/
	public function getCustomerAndCarDetailsByCarId($con, $car_id) {
		$data = array();
		$result = mysqli_query($con,"SELECT *,cu.customer_id, cu.c_name, cu.c_email FROM tbl_add_car ca INNER JOIN tbl_add_customer cu on ca.customer_id = cu.customer_id WHERE ca.car_id = '".(int)$car_id."'");
		if($row = mysqli_fetch_array($result)){
			$data = $row;
		}
		return $data;
	}
	
	/*
	* @get buy parts list by parts id
	*/
	public function getBuyPartsListByPartsId($con, $invoice_id) {
		$data = array();
		$result = mysqli_query($con,"SELECT *,psm.price FROM tbl_parts_stock ps INNER JOIN tbl_parts_stock_manage psm on psm.parts_id = ps.parts_id where ps.invoice_id = '".trim($invoice_id) . "'");
		if($row = mysqli_fetch_array($result)){
			$data = $row;
		}
		return $data;
	}
	
	/*
	* @get parts stock information by parts id
	*/
	public function getPartsStockInfoByPartsId($con, $parts_id) {
		$data = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_parts_stock_manage psm where psm.parts_id = '" . (int)$parts_id . "'");
		if($row = mysqli_fetch_array($result)){
			$data = $row;
		}
		return $data;
	}
	
	/*
	* @get website settings data
	*/
	public function getWebsiteSettingsInformation($con) {
		$data = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_settings");
		if($row = mysqli_fetch_array($result)){
			$data = $row;
		}
		return $data;
	}
	/*
	* @save customer remind deatils
	*/
	public function saveCustomerRemindDetails($con, $invoice_id, $car_id, $customer_id, $progress) {
		mysqli_query($con,"INSERT INTO tbl_customer_notification(invoice_id, car_id, customer_id, progress, notify_date) VALUES('".$invoice_id."',".(int)$car_id.",".(int)$customer_id.",".(int)$progress.",'".date('Y-m-d H:i:s')."')");
	}
	
	/*
	* @save system information
	*/
	public function saveSystemInformation($con,$data,$site_logo) {
		mysqli_query($con,"DELETE FROM tbl_settings");
		$footer_text_1 = mysqli_real_escape_string($con,$data['footer_box_1']);
		$footer_text_2 = mysqli_real_escape_string($con,$data['footer_box_2']);
		$footer_text_3 = mysqli_real_escape_string($con,$data['footer_box_3']);
		$footer_text_4 = mysqli_real_escape_string($con,$data['footer_box_4']);
		$footer_text_5 = mysqli_real_escape_string($con,$data['footer_box_5']);
		//header
		$header_text_1 = mysqli_real_escape_string($con,$data['header_box_1']);
		$header_text_2 = mysqli_real_escape_string($con,$data['header_box_2']);
		//contact us
		$contact_us_text_1 = mysqli_real_escape_string($con,$data['contact_us_text_1']);
		$map_api_key = mysqli_real_escape_string($con,$data['google_api_key']);
		$map_address = mysqli_real_escape_string($con,$data['map_address']);
		//subscribe
		$mc_api_key = mysqli_real_escape_string($con,$data['mc_api_key']);
		$mc_list_id = mysqli_real_escape_string($con,$data['mc_list_id']);
		
		mysqli_query($con,"INSERT INTO tbl_settings(site_name,currency,email,address,site_logo,footer_box_1,footer_box_2,footer_box_3,footer_box_4,footer_box_5,header_box_1,header_box_2,contact_us_text_1,gogole_api_key,map_address,mc_api_key,mc_list_id) VALUES('".$data['txtWorkshopName']."','".$data['txtCurrency']."','".$data['txtEmailAddress']."','".$data['txtAddress']."','".$site_logo."','".$footer_text_1."','".$footer_text_2."','".$footer_text_3."','".$footer_text_4."','".$footer_text_5."','".$header_text_1."','".$header_text_2."','".$contact_us_text_1."','".$map_api_key."','".$map_address."','".$mc_api_key."','".$mc_list_id."')");
	}
	
	/*
	* @get car and customer information by invoice ID
	*/
	public function getCarAndCustomerInformationByInvoiceId($con,$invoice_id) {
		$data = array();
		$result = mysqli_query($con,"SELECT *,ac.image as car_image,c.c_name,c.image as customer_image,c.c_address,c.c_email,c.c_mobile,m.make_name,mo.model_name,y.year_name,ac.estimate_no FROM tbl_add_car ac inner join tbl_add_customer c on c.customer_id = ac.customer_id inner join tbl_make m on m.make_id = ac.car_make inner join tbl_model mo on mo.model_id = ac.car_model inner join tbl_year y on y.year_id = ac.year WHERE ac.estimate_no = '".(int)$invoice_id."'");
		if($row = mysqli_fetch_array($result)){
			$data = $row;
		}
		return $data;
	}
	
	/*
	* @get all delivery car list
	*/
	public function getAllDeliveryCarList($con) {
		$data = array();
		//$result = mysqli_query($con,"SELECT *,ac.image as car_image,c.c_name,c.image as customer_image,c.c_address,c.c_email,c.c_mobile,m.make_name,mo.model_name,y.year_name,ac.estimate_no FROM tbl_add_car ac inner join tbl_add_customer c on c.customer_id = ac.customer_id inner join tbl_make m on m.make_id = ac.car_make inner join tbl_model mo on mo.model_id = ac.car_model inner join tbl_year y on y.year_id = ac.year WHERE ac.delivery_status = 1");
		$result = mysqli_query($con,"SELECT *,c.image as customer_image,ac.image as car_image,m.make_name,mo.model_name,y.year_name FROM tbl_car_estimate ce INNER JOIN tbl_add_car ac on ce.car_id = ac.car_id INNER JOIN tbl_add_customer c on c.customer_id = ce.customer_id left join tbl_make m on m.make_id = ac.car_make left join tbl_model mo on mo.model_id = ac.car_model left join tbl_year y on y.year_id = ac.year WHERE ce.delivery_status = 1");
		while($row = mysqli_fetch_array($result)){
			$data[] = $row;
		}
		return $data;
	}
	
	/*
	* @get all delivery repair car list for report
	*/
	public function getRepairCarDateForReport($con, $filter) {
		$data = array();
		$sql = "SELECT *,c.image as customer_image,ac.image as car_image,m.make_name,mo.model_name,y.year_name FROM tbl_car_estimate ce INNER JOIN tbl_add_car ac on ce.car_id = ac.car_id INNER JOIN tbl_add_customer c on c.customer_id = ce.customer_id left join tbl_make m on m.make_id = ac.car_make left join tbl_model mo on mo.model_id = ac.car_model left join tbl_year y on y.year_id = ac.year";
		if($filter['status'] == '') {
			$sql .= " WHERE ce.delivery_status IN(1,0)";
		} else {
			$sql .= " WHERE ce.delivery_status = '".$filter['status']."'";
		}
		if(!empty($filter['date'])) {
			$sql .= " AND ce.delivery_done_date = '".$this->datepickerDateToMySqlDate($filter['date'])."'";
		} else {
			if($filter['status']=='1') {
				if(!empty($filter['month'])) {
					$sql .= " AND MONTH(ce.delivery_done_date) = '".$filter['month']."'";
				}
				if(!empty($filter['year'])) {
					$sql .= " AND YEAR(ce.delivery_done_date) = '".$filter['year']."'";
				}
			} else {
				if(!empty($filter['month'])) {
					$sql .= " AND MONTH(ce.created_date) = '".$filter['month']."'";
				}
				if(!empty($filter['year'])) {
					$sql .= " AND YEAR(ce.created_date) = '".$filter['year']."'";
				}
			}
		}
		if(!empty($filter['payment'])) {
			if($filter['payment'] == 'due') {
				$sql .= " AND payment_due > 0";
			} else {
				$sql .= " AND payment_due = 0.00";
			}
		}
		//echo $sql;
		//die();
		//
		$result = mysqli_query($con,$sql);
		while($row = mysqli_fetch_assoc($result)){
			$data[] = $row;
		}
		return $data;
	}
	
	/*
	* @get customer notification data
	*/
	public function getCustomerNotificationEmails($con) {
		$data = array();
		$result = mysqli_query($con,"SELECT cn.n_id,cn.progress,cn.notify_date,c.c_name,ac.car_name FROM tbl_customer_notification cn inner join tbl_add_customer c on c.customer_id = cn.customer_id inner join tbl_add_car ac on ac.car_id = cn.car_id order by cn.n_id desc");
		while($row = mysqli_fetch_array($result)){
			$data[] = $row;
		}
		return $data;
	}
	
	/*
	* @dashboard sell car pie chart
	*/
	public function getSellCarMonthlyData($con, $year) {
		$data = array();
		$result = mysqli_query($con,"SELECT MONTHNAME(`selling_date`) as month_name,count(`carsell_id`) as total_sell FROM tbl_carsell WHERE YEAR(`selling_date`) = '".$year."' GROUP BY MONTHNAME(`selling_date`) ORDER BY MONTHNAME(`selling_date`)");
		while($row = mysqli_fetch_assoc($result)){
			$data[] = $row;
		}
		return $data;
	}
	
	
	/*
	* @dashboard sell parts pie chart
	*/
	public function getSellPartsMonthlyData($con, $year) {
		$data = array();
		//$result = mysqli_query($con,"SELECT MONTHNAME(`invoice_date`) as month_name, (select SUM(quantity) as total_parts from tbl_parts_sell ps WHERE ps.sold_id = tbl_parts_sold_invoice.sold_id ) as total_parts FROM tbl_parts_sold_invoice WHERE YEAR(`invoice_date`) = '".$year."' GROUP BY MONTH(`invoice_date`) ORDER BY MONTH(`invoice_date`)");
		$result = mysqli_query($con,"select sum(total_parts) as total_parts from vw_parts_sold where year_name = '".$year."' group by month_name order by month_name ASC");
		while($row = mysqli_fetch_assoc($result)){
			$data[] = $row;
		}
		return $data;
	}
	
	/*
	* @dashboard mechanice monthly hour report
	*/
	public function getMechaniceMonthlyHourData($con, $year, $mech_id) {
		$data = array();
		$result = mysqli_query($con,"SELECT MONTHNAME(`work_date`) as month_name,sum(`total_hour`) as total_hour FROM tbl_daily_work WHERE YEAR(`work_date`) = '".$year."' AND mechanic_id = ".(int)$mech_id." GROUP BY MONTHNAME(`work_date`) ORDER BY MONTHNAME(`work_date`) ASC");
		while($row = mysqli_fetch_assoc($result)){
			$data[] = $row;
		}
		return $data;
	}
	
	/*
	* @dashboard car repair chart
	*/
	public function getCarRepairChartData($con, $year) {
		$data = array();
		$result = mysqli_query($con,"SELECT MONTHNAME(`delivery_done_date`) as month_name,count(`estimate_id`) as total_repair FROM tbl_car_estimate WHERE YEAR(`delivery_done_date`) = '".$year."' GROUP BY MONTHNAME(`delivery_done_date`) ORDER BY MONTHNAME(`delivery_done_date`)");
		while($row = mysqli_fetch_assoc($result)){
			$data[] = $row;
		}
		return $data;
	}
	
	/*
	* @dashboard mechanices salary chart each month
	*/
	public function getMechaniceSalaryReportChartData($con, $year,$mech_id) {
		$data = array();
		$result = mysqli_query($con,"select MONTHNAME(STR_TO_DATE(`month_id`, '%m')) as month_name, paid_amount from tbl_mcncsslary WHERE year_id = ".$year." AND mechanics_id=".(int)$mech_id);
		while($row = mysqli_fetch_assoc($result)){
			$data[] = $row;
		}
		return $data;
	}
	
	/*
	* @dashboard customer repair chart by month
	*/
	public function getCustomerRepairReportChartData($con, $year, $cust_id) {
		$data = array();
		$result = mysqli_query($con,"select MONTHNAME(`created_date`) as month_name, total_cost as paid_amount from tbl_car_estimate WHERE job_status = 1 AND YEAR(created_date) = ".$year." AND customer_id=".(int)$cust_id);
		while($row = mysqli_fetch_assoc($result)){
			$data[] = $row;
		}
		return $data;
	}
	
	/*
	* @dashboard mechanices get total hour
	*/
	public function getMechaniceTotalHourList($con, $mech_id) {
		$data = 0;
		$result = mysqli_query($con,"select sum(total_hour) as total_hour from tbl_daily_work WHERE mechanic_id=".(int)$mech_id);
		if($row = mysqli_fetch_assoc($result)){
			$data = $row['total_hour'];
		}
		return $data;
	}
	
	/*
	* @dashboard mechanices get total paid amount
	*/
	public function getMechaniceTotalPaidAmount($con, $mech_id) {
		$data = array();
		$result = mysqli_query($con,"select sum(total) as total, sum(paid_amount) as total_paid_amount, sum(due_amount) as total_due_amount from tbl_mcncsslary WHERE mechanics_id=".(int)$mech_id);
		if($row = mysqli_fetch_assoc($result)){
			$data = $row;
		}
		return $data;
	}
	
	/*
	* @get all car list
	*/
	public function getAllCarList($con) {
		$data = array();
		$result = mysqli_query($con,"SELECT *,cc.color_name,cd.door_name,m.make_name,mo.model_name,y.year_name FROM tbl_buycar bc left join tbl_carcolor cc on cc.color_id = bc.car_color left join tbl_cardoor cd on cd.door_id = bc.car_door left join tbl_make m on m.make_id = bc.make_id left join tbl_model mo on mo.model_id = bc.model_id left join tbl_year y on y.year_id = bc.year_id order by bc.buycar_id ASC");
		while($row = mysqli_fetch_array($result)){
			$data[] = $row;
		}
		return $data;
	}
	
	
	/*
	* @get all sell car report info
	*/
	public function getSellCarReportInformationList($con, $filter) {
		$data = array();
		$sql = '';
		if($filter['condition'] != 'both') {
			$sql = "SELECT * FROM tbl_carsell cs left join tbl_buycar bc on bc.buycar_id = cs.car_id WHERE cs.car_condition = '".(string)$filter['condition']."' AND cs.is_return = 0";
		} else {
			$sql = "SELECT * FROM tbl_carsell cs left join tbl_buycar bc on bc.buycar_id = cs.car_id WHERE cs.is_return = 0";		
		}
		if(!empty($filter['date'])) {
			$sql .= " AND cs.selling_date = '".$this->datepickerDateToMySqlDate($filter['date'])."'";
		} else {
			if(!empty($filter['month'])) {
				$sql .= " AND MONTH(cs.selling_date) = '".$filter['month']."'";
			}
			if(!empty($filter['year'])) {
				$sql .= " AND YEAR(cs.selling_date) = '".$filter['year']."'";
			}
		}
		if(!empty($filter['payment'])) {
			if($filter['payment'] == 'due') {
				$sql .= " AND due_amount > 0";
			} else {
				$sql .= " AND due_amount = 0.00";
			}
		}
		$result = mysqli_query($con,$sql);
		while($row = mysqli_fetch_assoc($result)){
			$data[] = $row;
		}
		return $data;
	}
	
	/*
	* @get all buy car list // Report
	*/
	public function getAllBuyCarReportList($con, $filter) {
		$data = array();
		$sql = '';
		if($filter['condition'] != 'both') {
			$sql = "SELECT *,cc.color_name,cd.door_name,m.make_name,mo.model_name,y.year_name FROM tbl_buycar bc left join tbl_carcolor cc on cc.color_id = bc.car_color left join tbl_cardoor cd on cd.door_id = bc.car_door left join tbl_make m on m.make_id = bc.make_id left join tbl_model mo on mo.model_id = bc.model_id left join tbl_year y on y.year_id = bc.year_id WHERE bc.car_status = '".(int)$filter['status']."' AND bc.car_condition = '".(string)$filter['condition']."'";
		} else {
			$sql = "SELECT *,cc.color_name,cd.door_name,m.make_name,mo.model_name,y.year_name FROM tbl_buycar bc left join tbl_carcolor cc on cc.color_id = bc.car_color left join tbl_cardoor cd on cd.door_id = bc.car_door left join tbl_make m on m.make_id = bc.make_id left join tbl_model mo on mo.model_id = bc.model_id left join tbl_year y on y.year_id = bc.year_id WHERE bc.car_status = '".(int)$filter['status']."'";
		}
		
		if(!empty($filter['date'])) {
			$sql .= " AND bc.buy_date = '".$this->datepickerDateToMySqlDate($filter['date'])."'";
		} else {
			if(!empty($filter['month'])) {
				$sql .= " AND MONTH(bc.buy_date) = '".$filter['month']."'";
			}
			if(!empty($filter['year'])) {
				$sql .= " AND YEAR(bc.buy_date) = '".$filter['year']."'";
			}
		}
		if(!empty($filter['payment'])) {
			if($filter['payment'] == 'due') {
				$sql .= " AND bc.buy_price - bc.buy_given_amount > 0";
			} else {
				$sql .= " AND bc.buy_price - bc.buy_given_amount = 0";
			}
		}
		//
		$result = mysqli_query($con,$sql);
		while($row = mysqli_fetch_assoc($result)){
			$data[] = $row;
		}
		return $data;
	}
	
	/*
	* @get all buy parts list // Report
	*/
	public function getAllPurchasedPartsReportList($con, $filter) {
		$data = array();
		$sql = '';
		if($filter['condition']=='both') {
			$sql = 'SELECT *,s.s_name,mu.manufacturer_name,s.s_name as supplier_name FROM tbl_parts_stock ps LEFT JOIN tbl_add_supplier s ON s.supplier_id = ps.supplier_id LEFT JOIN tbl_manufacturer mu ON mu.manufacturer_id = ps.manufacturer_id WHERE parts_condition IN("new","old")';
		} else {
			$sql = 'SELECT *,s.s_name,mu.manufacturer_name,s.s_name as supplier_name FROM tbl_parts_stock ps LEFT JOIN tbl_add_supplier s ON s.supplier_id = ps.supplier_id LEFT JOIN tbl_manufacturer mu ON mu.manufacturer_id = ps.manufacturer_id WHERE parts_condition = "'.$filter['condition'].'"';
		}
		
		if(!empty($filter['date'])) {
			$sql .= " AND ps.parts_added_date = '".$this->datepickerDateToMySqlDate($filter['date'])."'";
		} else {
			if(!empty($filter['month'])) {
				$sql .= " AND MONTH(ps.parts_added_date) = '".$filter['month']."'";
			}
			if(!empty($filter['year'])) {
				$sql .= " AND YEAR(ps.parts_added_date) = '".$filter['year']."'";
			}
		}
		if(!empty($filter['payment'])) {
			if($filter['payment'] == 'due') {
				$sql .= " AND ps.pending_amount > 0";
			} else {
				$sql .= " AND ps.pending_amount = 0";
			}
		}
		
		$sql .= " ORDER BY ps.parts_name ASC";
		//
		$result = mysqli_query($con,$sql);
		while($row = mysqli_fetch_assoc($result)){
			$data[] = $row;
		}
		return $data;
	}
	
	/*
	* @get all sell parts list // Report
	*/
	public function getAllSellPartsReportList($con, $filter) {
		$data = array();
		$sql = 'SELECT *,(SELECT count(sold_id) FROM tbl_parts_sell ps WHERE ps.sold_id = si.sold_id) as total_parts FROM tbl_parts_sold_invoice si WHERE si.grand_total > 0';
		if(!empty($filter['date'])) {
			$sql .= " AND si.invoice_date = '".$this->datepickerDateToMySqlDate($filter['date'])."'";
		} else {
			if(!empty($filter['month'])) {
				$sql .= " AND MONTH(si.invoice_date) = '".$filter['month']."'";
			}
			if(!empty($filter['year'])) {
				$sql .= " AND YEAR(si.invoice_date) = '".$filter['year']."'";
			}
		}
		if(!empty($filter['payment'])) {
			if($filter['payment'] == 'due') {
				$sql .= " AND si.due_amount > 0";
			} else {
				$sql .= " AND si.due_amount = 0";
			}
		}
		if(!empty($filter['invoice_no'])) {
			$sql .= " AND si.invoice_id = '".$filter['invoice_no']."'";
		}
		
		$sql .= " order by si.invoice_date ASC";
		//
		$result = mysqli_query($con,$sql);
		while($row = mysqli_fetch_assoc($result)){
			$data[] = $row;
		}
		return $data;
	}
	
	/*
	* @get all Mechanices salary // Report
	*/
	public function getMechanicesSalaryReportList($con, $filter) {
		$data = array();
		$sql = "SELECT * FROM tbl_mcncsslary ms left join tbl_add_mechanics am on am.mechanics_id = ms.mechanics_id WHERE ms.mechanics_id > 0";
		if(!empty($filter['mid'])) {
			$sql .= " AND ms.mechanics_id = '".$filter['mid']."'";
		}
		if(!empty($filter['date'])) {
			$sql .= " AND ms.sl_date = '".$this->datepickerDateToMySqlDate($filter['date'])."'";
		} else {
			if(!empty($filter['month'])) {
				$sql .= " AND month_id = '".$filter['month']."'";
			}
			if(!empty($filter['year'])) {
				$sql .= " AND year_id = '".$filter['year']."'";
			}
		}
		if(!empty($filter['payment'])) {
			if($filter['payment'] == 'due') {
				$sql .= " AND ms.due_amount > 0";
			} else {
				$sql .= " AND ms.due_amount = 0";
			}
		}
		$sql .= " order by ms.m_salary_id";
		//
		$result = mysqli_query($con,$sql);
		while($row = mysqli_fetch_assoc($result)){
			$data[] = $row;
		}
		return $data;
	}
	
	/*
	* @get all car list
	*/
	public function getAllActiveCarList($con) {
		$data = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_buycar WHERE car_status = 0");
		while($row = mysqli_fetch_array($result)){
			$data[] = $row;
		}
		return $data;
	}
	
	/*
	* @filter car list
	*/
	public function filterCarListByMakeModelYear($con, $datax) {
		$data = array();
		$result = array();
		if(!empty($datax['make']) && !empty($datax['model']) && !empty($datax['year'])) {
			$result = mysqli_query($con,"SELECT *,cc.color_name,cd.door_name,m.make_name,mo.model_name,y.year_name FROM tbl_buycar bc left join tbl_carcolor cc on cc.color_id = bc.car_color left join tbl_cardoor cd on cd.door_id = bc.car_door left join tbl_make m on m.make_id = bc.make_id left join tbl_model mo on mo.model_id = bc.model_id left join tbl_year y on y.year_id = bc.year_id WHERE m.make_id = $datax[make] AND mo.model_id = $datax[model] AND y.year_id = $datax[year] AND bc.car_status = 0 order by bc.buycar_id ASC");
		} else if(!empty($datax['make']) && !empty($datax['model'])) {
			$result = mysqli_query($con,"SELECT *,cc.color_name,cd.door_name,m.make_name,mo.model_name,y.year_name FROM tbl_buycar bc left join tbl_carcolor cc on cc.color_id = bc.car_color left join tbl_cardoor cd on cd.door_id = bc.car_door left join tbl_make m on m.make_id = bc.make_id left join tbl_model mo on mo.model_id = bc.model_id left join tbl_year y on y.year_id = bc.year_id WHERE m.make_id = $datax[make] AND mo.model_id = $datax[model] AND bc.car_status = 0 order by bc.buycar_id ASC");
		} else if(!empty($datax['make'])) {
			$result = mysqli_query($con,"SELECT *,cc.color_name,cd.door_name,m.make_name,mo.model_name,y.year_name FROM tbl_buycar bc left join tbl_carcolor cc on cc.color_id = bc.car_color left join tbl_cardoor cd on cd.door_id = bc.car_door left join tbl_make m on m.make_id = bc.make_id left join tbl_model mo on mo.model_id = bc.model_id left join tbl_year y on y.year_id = bc.year_id WHERE m.make_id = $datax[make] AND bc.car_status = 0 order by bc.buycar_id ASC");
		}
		while($row = mysqli_fetch_array($result)){
			$data[] = $row;
		}
		return $data;
	}
	
	/*
	* @get all car list with pagination
	*/
	public function getAllCarListWithPagination($con, $page, $limit) {
		$data = array();
		$start_from = ($page-1) * $limit;
		$result = mysqli_query($con,"SELECT *,cc.color_name,cd.door_name,m.make_name,mo.model_name,y.year_name FROM tbl_buycar bc left join tbl_carcolor cc on cc.color_id = bc.car_color left join tbl_cardoor cd on cd.door_id = bc.car_door left join tbl_make m on m.make_id = bc.make_id left join tbl_model mo on mo.model_id = bc.model_id left join tbl_year y on y.year_id = bc.year_id WHERE car_status = 0 order by bc.buycar_id ASC LIMIT ".$start_from.",".$limit);
		while($row = mysqli_fetch_array($result)){
			$data[] = $row;
		}
		return $data;
	}
	
	/*
	* @get car door information
	*/
	public function getCarDoorInformation($con) {
		$data = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_cardoor order by door_name ASC");
		while($row = mysqli_fetch_array($result)){
			$data[] = $row;
		}
		return $data;
	}
	
	/*
	* @get car color information
	*/
	public function getCarColorInformation($con) {
		$data = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_carcolor order by color_name ASC");
		while($row = mysqli_fetch_array($result)){
			$data[] = $row;
		}
		return $data;
	}
	
	/*
	* @get all supplier list
	*/
	public function getAllSupplierData($con) {
		$data = array();
		$result = mysqli_query($con,"SELECT *,c.name as country_name,sa.name as state_name FROM tbl_add_supplier s inner join tbl_countries c on c.id = s.country_id inner join tbl_states sa on sa.id = s.state_id order by s.supplier_id");
		while($row = mysqli_fetch_assoc($result)){
			$data[] = $row;
		}
		return $data;
	}
	
	/*
	* @count all car list
	*/
	public function countAllCarList($con) {
		$data = 0;
		$result = mysqli_query($con,"SELECT count(buycar_id) as total FROM tbl_buycar WHERE car_status = 0");
		if($row = mysqli_fetch_array($result)){
			$data = $row['total'];
		}
		return $data;
	}
	
	/*
	* @count all parts list
	*/
	public function countAllPartsList($con) {
		$data = 0;
		$result = mysqli_query($con,"SELECT count(parts_id) as total FROM tbl_parts_stock_manage WHERE status = 1");
		if($row = mysqli_fetch_array($result)){
			$data = $row['total'];
		}
		return $data;
	}
	
	/*
	* @get all car make list
	*/
	public function getAllCarMakeList($con) {
		$data = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_make order by make_name ASC");
		while($row = mysqli_fetch_array($result)){
			$data[] = $row;
		}
		return $data;
	}
	
	/*
	* @get all car model list
	*/
	public function getModelListByMakeId($con, $makeId) {
		$data = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_model WHERE make_id = ".(int)$makeId." order by model_name ASC");
		while($row = mysqli_fetch_assoc($result)){
			$data[] = $row;
		}
		return $data;
	}
	
	/*
	* @get car model by name
	*/
	public function getModelByName($con, $modelName) {
		$data = array();
		$result = mysqli_query($con, "SELECT * FROM tbl_model where model_name = '". $modelName ."'");
		while($row = mysqli_fetch_assoc($result)){
			$data[] = $row;
		}
		return $data;
	}

	/*
	* @get all car year list
	*/
	public function getYearlListByMakeIdAndModelId($con, $makeId, $modelId) {
		$data = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_year WHERE model_id = ".(int)$modelId." AND make_id = ".(int)$makeId." order by year_name ASC");
		while($row = mysqli_fetch_assoc($result)){
			$data[] = $row;
		}
		return $data;
	}

	/*
	* @delete notification email
	*/
	public function deleteNotificationEmailAlert($con, $n_id) {
		mysqli_query($con,"DELETE FROM tbl_customer_notification WHERE n_id = '".(int)$n_id."'");
	}
	
	/*
	* @delete make data
	*/
	public function deleteMakeData($con, $make_id) {
		mysqli_query($con,"DELETE from tbl_make where make_id = ".(int)$make_id);
	}
	
	/*
	* @delete model data
	*/
	public function deleteModelData($con, $model_id) {
		mysqli_query($con,"DELETE from tbl_model where model_id = " .(int)$model_id);
	}
	
	/*
	* @delete year data
	*/
	public function deleteYearData($con, $year_id) {
		mysqli_query($con,"DELETE from tbl_year where year_id = " .(int)$year_id);
	}
	
	/*
	* @delete car color data
	*/
	public function deleteCarColorData($con, $color_id) {
		mysqli_query($con,"DELETE from tbl_carcolor where color_id = '".(int)$color_id. "'");
	}
	
	/*
	* @delete car door data
	*/
	public function deleteCarDoorData($con, $door_id) {
		mysqli_query($con,"DELETE from tbl_cardoor where door_id = '" . (int)$door_id . "'");
	}
	
	/*
	* @delete supplier info
	*/
	public function deleteSupplierInformation($con, $s_id) {
		mysqli_query($con,"DELETE FROM `tbl_add_supplier` WHERE supplier_id = ".(int)$s_id);
	}
	
	/*
	* @delete parts list data
	*/
	public function deleteBuyPartsInformation($con, $invoice_id) {
		mysqli_query($con,"DELETE FROM `tbl_parts_stock` WHERE invoice_id = ".trim($invoice_id));
	}
	
	/*
	* @delete parts from stock
	*/
	public function deleteStockPartsInformation($con, $parts_id) {
		mysqli_query($con,"DELETE FROM `tbl_parts_stock_manage` WHERE parts_id = ".(int)$parts_id);
	}
	
	/*
	* @delete parts list data
	*/
	public function deleteCarInformation($con, $car_id) {
		mysqli_query($con,"DELETE FROM `tbl_buycar` WHERE buycar_id = ".(int)$car_id);
	}
	
	/*
	* @delete customer
	*/
	public function deleteCustomer($con, $cid) {
		mysqli_query($con,"DELETE FROM `tbl_add_customer` WHERE customer_id = ".(int)$cid);
	}
	
	/*
	* @delete work status
	*/
	public function deleteWorkStatus($con, $wid) {
		mysqli_query($con,"DELETE FROM `tbl_daily_work` WHERE work_id = ".(int)$wid);
	}
	
	/*
	* @delete mechanice salery
	*/
	public function deleteMechanicSalery($con, $sid) {
		mysqli_query($con,"DELETE FROM `tbl_mcncsslary` WHERE m_salary_id = ".(int)$sid);
	}
	
	/*
	* @delete repair car
	*/
	public function deleteRepairCar($con, $cid) {
		mysqli_query($con,"DELETE FROM `tbl_add_car` WHERE car_id = ".(int)$cid);
	}
	
	/*
	* @contact us reply email
	*/
	public function sendContactReplyEmail($con, $to, $subject, $details) {
		$site_config_data = $this->getWebsiteSettingsInformation($con);
		$from = $site_config_data['email'];
		$variables = array('logo'=>WEB_URL.'img/logo.png','site_url'=>WEB_URL,'site_name'=>$site_config_data['site_name'],'subject'=>$subject,'message'=>$details);
		$headers = "From: " . strip_tags($from) . "\r\n";
		$headers .= "Reply-To: ". strip_tags($from) . "\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		$message = $this->loadEmailTemplate('tmp_contact_us_reply.html', $variables);
		mail($to, $subject, $message, $headers);
	}
	
	/*
	* @forgot email
	*/
	public function sendForgotPasswordEmail($con, $to, $subject, $details) {
		$site_config_data = $this->getWebsiteSettingsInformation($con);
		$from = $site_config_data['email'];
		$variables = array('logo'=>WEB_URL.'img/logo.png','site_url'=>WEB_URL,'site_name'=>$site_config_data['site_name'],'subject'=>$subject,'message'=>$details);
		$headers = "From: " . strip_tags($from) . "\r\n";
		$headers .= "Reply-To: ". strip_tags($from) . "\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		$message = $this->loadEmailTemplate('tmp_forgot_password.html', $variables);
		mail($to, $subject, $message, $headers);
	}
	
	/*
	* @contact us email
	*/
	public function sendContactUSEmail($con, $name, $from, $subject, $details) {
		$site_config_data = $this->getWebsiteSettingsInformation($con);
		$variables = array('logo'=>WEB_URL.'img/logo.png','site_url'=>WEB_URL,'site_name'=>$site_config_data['site_name'],'name'=>$name,'email'=>$from,'subject'=>$subject,'message'=>$details);
		$to = $site_config_data['email'];
		$headers = "From: " . strip_tags($from) . "\r\n";
		$headers .= "Reply-To: ". strip_tags($from) . "\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		$message = $this->loadEmailTemplate('tmp_contact_us.html', $variables);
		mail($to, $subject, $message, $headers);
	}
	
	/*
	* @customer custom email function
	*/
	public function sendCustomerCustomEmail($from, $to, $subject, $details) {
		$headers = "From: " . strip_tags($from) . "\r\n";
		$headers .= "Reply-To: ". strip_tags($from) . "\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		$message = '<html><body>';
		$message .= '<div style="border-bottom:solid 1px #666;"><img src="'.WEB_URL.'img/logo.png"></div><br/><br/>';
		$message .= '<div>'.$details.',</div>';
		$message .= '</body></html>';
		mail($to, $subject, $message, $headers);
	}
	
	/*
	* @email function
	*/
	public function sendCustomerEmailNotification($con, $car_id, $progress, $invoice_id) {
		if($data = $this->getCustomerAndCarDetailsByCarId($con, $car_id)) {
			$this->saveCustomerRemindDetails($con, $invoice_id, $car_id, $data['customer_id'], $progress);
			if($site_config_data = $this->getWebsiteSettingsInformation($con)) {
				$from = $site_config_data['email'];
				$to = $data['c_email'];
				$subject = 'Car Repair Progress Notification';
				$headers = "From: " . strip_tags($from) . "\r\n";
				$headers .= "Reply-To: ". strip_tags($from) . "\r\n";
				$headers .= "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
				$message = '<html><body>';
				$message .= '<div style="border-bottom:solid 1px #666;"><img src="'.WEB_URL.'img/logo.png"></div>';
				$message .= '<h4>Your Car Repair Notification</h4>';
				$message .= '<div>Dear '.$data['c_name'].',</div>';
				$message .= '<div>Your car '.$data['car_name'].', registration no '.$data['car_reg_no'].', progress is '.$progress.'%. We will complete your job soon and send you final notification email thanks.</div>';
				$message .= '</body></html>';
				mail($to, $subject, $message, $headers);
			}
		}
	}
	
	/*
	* @get estimate information with car and customer details for delivery
	*/
	public function getEstimateAndCarAndCustomerDetails($con, $estimate_no) {
		$data = array();
		if(!empty($estimate_no)) {
			$result = mysqli_query($con,"SELECT *,m.make_name,mo.model_name,y.year_name,c.image as car_image FROM tbl_car_estimate e INNER JOIN tbl_add_car c on c.car_id = e.car_id INNER JOIN tbl_add_customer ac on ac.customer_id = e.customer_id left join tbl_make m on m.make_id = c.car_make left join tbl_model mo on mo.model_id = c.car_model left join tbl_year y on y.year_id = c.year WHERE e.estimate_no = '".trim($estimate_no)."'");
			if($row = mysqli_fetch_array($result)){
				$data = $row;
			}
		}
		return $data;
	}

	public function getAllEstimateCars($con) {
		$data = array();
		$result = mysqli_query($con, "SELECT e.estimate_no, c.car_name FROM tbl_car_estimate e INNER JOIN tbl_add_car c on c.car_id = e.car_id WHERE c.mechanics_id > 0;");
		while($row = mysqli_fetch_array($result)){
			$data[] = $row;
		}
		return $data;
	}
	
	/*
	* @get sell information for invoice view
	*/
	public function carSellInvoiceGenerate($con, $invoice_no) {
		$data = array();
		if(!empty($invoice_no)) {
			$result = mysqli_query($con,"SELECT * FROM tbl_carsell WHERE invoice_id = '".trim($invoice_no)."'");
			if($row = mysqli_fetch_array($result)){
				$data = $row;
			}
		}
		return $data;
	}
	
	/*
	* @get sell information for based on sold if
	*/
	public function carSoldDetailsBasedOnSellId($con, $sell_id) {
		$data = array();
		if(!empty($sell_id)) {
			$result = mysqli_query($con,"SELECT * FROM tbl_carsell WHERE carsell_id = '".(int)$sell_id."'");
			if($row = mysqli_fetch_array($result)){
				$data = $row;
			}
		}
		return $data;
	}
	
	/*
	* @get sell car information
	*/
	public function getSellCarInformationList($con) {
		$data = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_carsell cs left join tbl_buycar bc on bc.buycar_id = cs.car_id WHERE cs.is_return = 0 ORDER BY cs.carsell_id ASC");
		while($row = mysqli_fetch_array($result)){
			$data[] = $row;
		}
		return $data;
	}
	
	/*
	* @car return
	*/
	public function returnSellCarInformation($con, $rid, $rcarid) {
		mysqli_query($con,"UPDATE tbl_carsell SET is_return = 1 WHERE carsell_id = ".(int)$rid);
		mysqli_query($con,"UPDATE tbl_buycar SET car_status = 0 WHERE buycar_id = ".(int)$rcarid); 
	}
	
	/*
	* @delete car sell information
	*/
	public function deleteCarSellInformation($con, $id) {
		mysqli_query($con,"DELETE FROM `tbl_carsell` WHERE carsell_id = ".(int)$id);
	}
	
	/*
	* @save car sell information
	*/
	public function saveCarSaleInformatiom($con, $data) {
		if(!empty($data)) {
			$query = "INSERT INTO tbl_carsell(car_id,buyer_name,buyer_mobile,buyer_email,sellernid,company_name,ctl, present_address,permanent_address,selling_price,advance_amount,due_amount,selling_date,sell_note,invoice_id,car_name,make_name, model_name, year_name, color_name, door_name, car_condition, car_totalmileage, car_chasis_no, car_engine_name, service_warranty) values('$data[carid]','$data[txtBuyerName]','$data[txtMobile]','$data[txtEmail]','$data[txtNid]','$data[txtCompanyname]','$data[txtCTL]','$data[txtprestAddress]','$data[txtpermanentAddress]','$data[txtSellPrice]','$data[txtAdvanceamount]','$data[due_amount]','".$this->datepickerDateToMySqlDate($data['txtSellDate'])."','$data[txtSellnote]','$data[invoice_id]','$data[_car_name]','$data[_make]','$data[_model]','$data[_year]','$data[_color]','$data[_door]','$data[_condition]','$data[_total_mileage]','$data[_chasis_no]','$data[_engine_name]','$data[ddlServiceWarranty]')";
			mysqli_query($con,$query);
			//update sold status
			$query_sold = "UPDATE tbl_buycar set car_status = 1 WHERE buycar_id = ".(int)$_POST['carid'];
			mysqli_query($con,$query_sold);
		}
	}
	
	/*
	* @update car sell information
	*/
	public function updateCarSaleInformatiom($con, $data) {
		if(!empty($data)) {
			$query = "UPDATE `tbl_carsell` SET `car_id`='".$data['carid']."',`buyer_name`='".$data['txtBuyerName']."',`buyer_mobile`='".$data['txtMobile']."',`buyer_email`='".$data['txtEmail']."',`sellernid`='".$data['txtNid']."',`company_name`='".$data['txtCompanyname']."',`ctl`='".$data['txtCTL']."',`present_address`='".$data['txtprestAddress']."',`permanent_address`='".$data['txtpermanentAddress']."',`selling_price`='".$data['txtSellPrice']."',`advance_amount`='".$data['txtAdvanceamount']."',`selling_date`='".$this->datepickerDateToMySqlDate($data['txtSellDate'])."',`sell_note`='".$data['txtSellnote']."',`service_warranty`='".$data['ddlServiceWarranty']."',`selling_price`='".$data['txtSellPrice']."',`advance_amount`='".$data['txtAdvanceamount']."',`due_amount`='".$data['due_amount']."' WHERE carsell_id='".$data['hdn']."'";
			mysqli_query($con,$query);
		}
	}
	
	/*
	* @ajax update estimate Data
	*/
	public function ajaxUpdateEstimateData($con, $data) {
		if(!empty($data)) {
			mysqli_query($con,"UPDATE tbl_car_estimate SET delivery_status = '".(int)$data['deliver']."', discount = '".(float)$data['discount']."',payment_done = '".(float)$data['payment_done'] ."', payment_due = '".(float)$data['payment_due']."', grand_total = '".(float)$data['grand_total']."', delivery_done_date = '".$this->datepickerDateToMySqlDate($data['deliver_date'])."' WHERE car_id = '".(int)$data['car_id']."' AND estimate_no = '".(string)$data['estimate_no']."' AND customer_id = '".(string)$data['customer_id']."'");
		}
	}
	public function getRepairCarEstimateData($con, $estimate_no) {
		$data = array();
		if(!empty($estimate_no)) {
			$result = mysqli_query($con,"SELECT * FROM tbl_car_estimate WHERE estimate_no = '".trim($estimate_no)."'");
			if($row = mysqli_fetch_array($result)){
				$data = $row;
			}
		}
		return $data;
	}
	
	public function getRepairCarAllEstimateData($con, $car_id, $customer_id) {
		$data = array();
		try {
			if(!empty($car_id)) {
				$result = mysqli_query($con,"SELECT * FROM tbl_car_estimate WHERE car_id = '".(int)$car_id."' AND customer_id = '".(int)$customer_id."' ORDER BY estimate_id DESC");
				while($row = mysqli_fetch_array($result)){
					$data[] = $row;
				}
			}
			return $data;
		} catch (\Throwable $th) {
			//throw $th;
			return array();
		}
	}
	
	/*
	* @get customer all estimate list
	*/
	public function getAllRepairCarEstimateList($con, $customer_id) {
		$data = array();
		if(!empty($customer_id)) {
			$result = mysqli_query($con,"SELECT * FROM tbl_car_estimate WHERE customer_id = '".(int)$customer_id."' ORDER BY estimate_id DESC");
			while($row = mysqli_fetch_array($result)){
				$data[] = $row;
			}
		}
		return $data;
	}
	
	/*
	* @filter sell parts list
	*/
	public function filterSellPartsList($con, $query) {
		$data = array();
		if(!empty($query)) {
			$result = mysqli_query($con,$query);
			while($row = mysqli_fetch_array($result)){
				$data[] = $row;
			}
		}
		return $data;
	}
	
	/*added some changes*/
	public function saveUpdateCarEstimateDate($con, $data = array()) {
		$row = $this->getRepairCarEstimateData($con, $data['estimate_no']);
		if(!empty($row) && count($row) > 0) {
			//update
			mysqli_query($con,"UPDATE tbl_car_estimate SET estimate_data = '".(string)$data['estimate_data']."', job_status = '".(int)$data['job_status']."', work_status = '".(int)$data['work_status']."', total_cost = '".(float)$data['total_cost']."', payment_due = '".(float)$data['payment_due']."', grand_total = '".(float)$data['grand_total']."', estimate_delivery_date = '".$data['estimate_delivery_date']."' WHERE estimate_no = '".(string)trim($data['estimate_no'])."' AND customer_id = '".(int)$data['customer_id']."' AND car_id = '".(int)$data['car_id']."'");
		} else {
			//insert;
			mysqli_query($con,"INSERT INTO `tbl_car_estimate`(`estimate_no`, `car_id`, `work_status`, `job_status`, `estimate_data`, `total_cost`, `payment_due`, `grand_total`, `customer_id`, `created_date`, `estimate_delivery_date`) VALUES ('".trim($data['estimate_no'])."','".$data['car_id']."','".$data['work_status']."','".$data['job_status']."','".$data['estimate_data']."','".$data['total_cost']."','".$data['payment_due']."','".$data['grand_total']."','".$data['customer_id']."','".date('Y-m-d H:i:s')."','".$data['estimate_delivery_date']."')");
		}
	}
	
	/*
	* @insert parts checkout data
	*/
	public function savePartsCheckoutData($con, $data) {
		if(isset($_SESSION['parts_cart']) && !empty($_SESSION['parts_cart'])) {
			mysqli_query($con,"INSERT INTO tbl_parts_sold_invoice(invoice_id, total, discount, paid_amount, due_amount, grand_total, customer_name, telephone, email, company_name, customer_address, delivery_address, note, invoice_date) values('$data[invoice_id]', '$data[total]', '$data[discount]', '$data[paid_amount]', '$data[due_amount]', '$data[grand_total]', '$data[customer_name]', '$data[telephone]', '$data[email]', '$data[company_name]', '$data[customer_address]', '$data[delivery_address]', '$data[note]', '$data[invoice_date]')");
			$sold_id = mysqli_insert_id($con);
			foreach($_SESSION['parts_cart'] as $cartdata) {
				mysqli_query($con,"INSERT INTO tbl_parts_sell(sold_id, parts_name, parts_warranty, parts_id, quantity, parts_price,parts_condition) values($sold_id, '$cartdata[name]', '$cartdata[warranty]', '$cartdata[parts_id]', '$cartdata[qty]', '$cartdata[price]', '$cartdata[condition]')");
				$result_parts = mysqli_query($con,"SELECT * FROM tbl_parts_stock_manage where parts_id=".(int)$cartdata['parts_id']);
				if($row_parts = mysqli_fetch_array($result_parts)){
					$qty = $row_parts['quantity'];
					if((int)$qty > 0) {
						$qty = (int)$qty - (int)$cartdata['qty'];
						mysqli_query($con,"UPDATE tbl_parts_stock_manage SET quantity=".(int)$qty." WHERE parts_id=".(int)$cartdata['parts_id']);
					}
				}
			}
			unset($_SESSION['parts_cart']);
		} else {
			echo 'ohh no session expired';
			die();
		}
	}
	
	/*
	* @insert parts checkout data
	*/
	public function updatePartsCheckoutData($con, $data) {
		mysqli_query($con,"UPDATE tbl_parts_sold_invoice SET total='$data[total]', discount='$data[discount]', paid_amount='$data[paid_amount]', due_amount='$data[due_amount]', grand_total='$data[grand_total]', customer_name='$data[customer_name]', telephone='$data[telephone]', email='$data[email]', company_name='$data[company_name]', customer_address='$data[customer_address]', delivery_address='$data[delivery_address]', note='$data[note]', invoice_date='$data[invoice_date]' WHERE sold_id=$data[sold_id]");
	}
	
	/*
	* @delete sold parts and recover
	*/
	public function deleteAndReturnPartsData($con, $sold_id, $parts_id, $sold_qty=0) {
		$result_parts = mysqli_query($con,"SELECT * FROM tbl_parts_stock where parts_id=".(int)$parts_id);
		if($row_parts = mysqli_fetch_array($result_parts)){
			$qty = (int)$row_parts['parts_quantity'] + (int)$sold_qty;
			mysqli_query($con,"UPDATE tbl_parts_stock SET parts_quantity=".(int)$qty." WHERE parts_id=".(int)$parts_id);
		}
		mysqli_query($con,"DELETE FROM tbl_parts_sell WHERE sold_id=".(int)$sold_id." AND parts_id=".(int)$parts_id);
	}
	
	/*
	* @parts sell details by id
	*/
	public function getSellPartsInformationBySellId($con, $psid) {
		$data = array();
		if(!empty($psid)) {
			$result = mysqli_query($con,"SELECT *,pas.parts_name,pas.parts_condition,pas.parts_warranty,pas.parts_sku FROM tbl_parts_sell ps INNER JOIN tbl_parts_stock pas on pas.parts_id = ps.parts_id where ps.parts_sell_id=" .(int)$psid);
			if($row = mysqli_fetch_array($result)){
				$data = $row;
			}
		}
		return $data;
	}
	
	/*
	* @parts sold details by invoiceId
	*/
	public function getSoldPartsInformation($con) {
		$data = array();
		$result = mysqli_query($con,"SELECT *,(SELECT count(sold_id) FROM tbl_parts_sell ps WHERE ps.sold_id = si.sold_id) as total_parts FROM tbl_parts_sold_invoice si order by si.sold_id DESC");
		while($row_parts = mysqli_fetch_assoc($result)){
			$data[] = $row_parts;
		}
		return $data;
	}
	
	/*
	* @delete parts sold information
	*/
	public function deletePartsSoldItem($con, $sold_id) {
		mysqli_query($con,"DELETE FROM `tbl_parts_sold_invoice` WHERE sold_id = ".(int)$sold_id);
		mysqli_query($con,"DELETE FROM `tbl_parts_sell` WHERE sold_id = ".(int)$sold_id);
	}
	
	/*
	* @parts sold details by invoiceId
	*/
	public function getSellPartsInformationByInvoiceId($con, $invoiceId) {
		$data = array();
		if(!empty($invoiceId)) {
			$result = mysqli_query($con,"SELECT * FROM tbl_parts_sold_invoice WHERE invoice_id =" .(int)$invoiceId);
			if($row = mysqli_fetch_assoc($result)){
				$data['invoice'] = $row;
				$result_parts = mysqli_query($con,"SELECT * FROM tbl_parts_sell WHERE sold_id =" .(int)$row['sold_id']);
				$gtotal = 0;
				while($row_parts = mysqli_fetch_assoc($result_parts)){
					$xtotal = (float)$row_parts['parts_price'] * (int)$row_parts['quantity']; 
					$data['parts'][] = array(
						'parts_id'			=> $row_parts['parts_id'],
						'parts_name'		=> $row_parts['parts_name'],
						'parts_warranty'	=> $row_parts['parts_warranty'],
						'parts_condition'	=> $row_parts['parts_condition'],
						'quantity'			=> $row_parts['quantity'],
						'parts_price'		=> $row_parts['parts_price'],
						'total'				=> number_format($xtotal,2)
					);
					$gtotal += (float)$xtotal;
				}
				$data['total'] = number_format($gtotal,2);
			}
		}
		return $data;
	}
	
	/*
	* @parts purchase details by invoiceId
	*/
	public function getPurchasePartsInformationByInvoiceId($con, $invoiceId) {
		$data = array();
		if(!empty($invoiceId)) {
			$result = mysqli_query($con,"SELECT *,s.s_name,mu.manufacturer_name,s.s_name as supplier_name FROM tbl_parts_stock ps LEFT JOIN tbl_add_supplier s ON s.supplier_id = ps.supplier_id LEFT JOIN tbl_manufacturer mu ON mu.manufacturer_id = ps.manufacturer_id WHERE invoice_id = '".trim($invoiceId)."'");
			while ($row = mysqli_fetch_array($result)) {
				$data[] = $row;
			}
		}
		return $data;
	}
	
	/*
	* @parts sold details by invoiceId
	*/
	public function getSellPartsInformationBySoldId($con, $sold_id) {
		$data = array();
		if(!empty($sold_id)) {
			$result = mysqli_query($con,"SELECT * FROM tbl_parts_sold_invoice WHERE sold_id =" .(int)$sold_id);
			if($row = mysqli_fetch_assoc($result)){
				$data['invoice'] = $row;
				$result_parts = mysqli_query($con,"SELECT * FROM tbl_parts_sell WHERE sold_id =" .(int)$sold_id);
				$gtotal = 0;
				while($row_parts = mysqli_fetch_assoc($result_parts)){
					$xtotal = (float)$row_parts['parts_price'] * (int)$row_parts['quantity']; 
					$data['parts'][] = array(
						'parts_id'			=> $row_parts['parts_id'],
						'parts_name'		=> $row_parts['parts_name'],
						'parts_warranty'	=> $row_parts['parts_warranty'],
						'quantity'			=> $row_parts['quantity'],
						'parts_price'		=> $row_parts['parts_price'],
						'total'				=> number_format($xtotal,2)
					);
					$gtotal += (float)$xtotal;
				}
				$data['total'] = number_format($gtotal,2);
			}
		}
		return $data;
	}
	
	/*
	* @mini cart html load
	*/
	public function loadMiniCartHtml() {
		$data = 0;
		if(isset($_SESSION['parts_cart']) && !empty($_SESSION['parts_cart'])) {
			foreach($_SESSION['parts_cart'] as $cartdata) {
				$data += (int)$cartdata['qty'];
			}
		}
		return $data;
	}
	
	/*
	* @retrieve shopping cart html
	*/
	public function getShoppingCartDate($con) {
		$html = '';
		if(isset($_SESSION['parts_cart']) && !empty($_SESSION['parts_cart'])) {
			$total = 0;
			$settings = $this->getWebsiteSettingsInformation($con);
			foreach($_SESSION['parts_cart'] as $cartdata) {
				$parts_total = (float)$cartdata['price'] * $cartdata['qty'];
				$total += (float)$parts_total;
				$parts_total = number_format($parts_total,2);
				$parts_info = $this->getPartsInformationById($cartdata['parts_id'], $con);
				$html .= "<tr>";
				$html .= "	<td><img class='img-thumbnail' style='width:70px;' src='".$parts_info['parts_image']."'></td>";
				$html .= "	<td>".$parts_info['parts_name']."</td>";
				$html .= "	<td>".$parts_info['parts_warranty']."</td>";
				$html .= "	<td style='text-transform:capitalize;'>".$parts_info['parts_condition']."</td>";
				$html .= "	<td>".$settings['currency'].$cartdata['price']."</td>";
				$html .= "	<td>".$cartdata['qty']."</td>";
				$html .= "	<td><b>".$settings['currency'].$parts_total."</b></td>";
				$html .= "	<td><a href='javascript:;' onclick='deleteCartParts(".$cartdata['parts_id'].");' class='btn btn-danger'><i class='fa fa-trash'></a></a></td>";
				$html .= "</tr>";
			}
			$html .= "<tr>";
			$html .= "	<td>&nbsp;</td>";
			$html .= "	<td>&nbsp;</td>";
			$html .= "	<td>&nbsp;</td>";
			$html .= "	<td>&nbsp;</td>";
			$html .= "	<td>&nbsp;</td>";
			//$html .= "	<td align='right'><b>Total: </b></td>";
			$html .= "	<td align='right'><b>Sub-Total: </b></td>";
			$html .= "	<td><b>".$settings['currency'].number_format($total,2)."</b><input type='hidden' id='hdntotal' name='hdntotal' value='".$total."'></td>";
			$html .= "	<td>&nbsp;</td>";
			$html .= "</tr>";
			$html .= "<tr>";
			$html .= "	<td>&nbsp;</td>";
			$html .= "	<td>&nbsp;</td>";
			$html .= "	<td>&nbsp;</td>";
			$html .= "	<td>&nbsp;</td>";
			$html .= "	<td>&nbsp;</td>";
			// $html .= "	<td align='right'><b>Discount (%): </b></td>";
			$html .= "	<td align='right'><b>Descuento (%): </b></td>";
			$html .= "	<td><input type='text' class='allownumberonly'' style='text-align:left;font-weight:bold;border:none;border-bottom:solid 1px #ccc;' size='8' name='txtSellDiscount' value='0.00' id='txtSellDiscount'></td>";
			$html .= "	<td>&nbsp;</td>";
			$html .= "</tr>";
			$html .= "<tr style=display:none;>";
			$html .= "	<td>&nbsp;</td>";
			$html .= "	<td>&nbsp;</td>";
			$html .= "	<td>&nbsp;</td>";
			$html .= "	<td>&nbsp;</td>";
			$html .= "	<td>&nbsp;</td>";
			$html .= "	<td align='right'><b>Paid Amount (".$settings['currency']."): </b></td>";
			$html .= "	<td><input type='text' class='allownumberonly' style='text-align:left;font-weight:bold;border:none;border-bottom:solid 1px #ccc;' size='8' name='txtSellPaidamount' value='0' id='txtSellPaidamount'></td>";
			$html .= "	<td>&nbsp;</td>";
			$html .= "</tr>";
			$html .= "<tr style=display:none;>";
			$html .= "	<td>&nbsp;</td>";
			$html .= "	<td>&nbsp;</td>";
			$html .= "	<td>&nbsp;</td>";
			$html .= "	<td>&nbsp;</td>";
			$html .= "	<td>&nbsp;</td>";
			$html .= "	<td align='right'><b>Due Amount : </b></td>";
			$html .= "	<td><b>".$settings['currency']."<span id='due_amount'>".number_format($total,2)."</span></b></td>";
			$html .= "	<td>&nbsp;<input type='hidden' name='hdn_due_amount' id='hdn_due_amount' value='".$total."'></td>";
			$html .= "</tr>";
			$html .= "<tr>";
			$html .= "	<td>&nbsp;</td>";
			$html .= "	<td>&nbsp;</td>";
			$html .= "	<td>&nbsp;</td>";
			$html .= "	<td>&nbsp;</td>";
			$html .= "	<td>&nbsp;</td>";
			$html .= "	<td align='right'><b>Grand Total: </b></td>";
			$html .= "	<td><b>".$settings['currency']."<span id='grand_total'>".number_format($total,2)."</span></b></td>";
			$html .= "	<td>&nbsp;<input type='hidden' name='hdn_grand_total' id='hdn_grand_total' value='".number_format($total,2)."'></td>";
			$html .= "</tr>";
			
		}
		return $html;
	}
	
	/*
	* @retrieve shopping cart html based on sold id
	*/
	public function getShoppingCartHtmlBasedOnSoldId($con, $cartInfo, $discount, $due_amount, $paid_amount, $grand_total, $sold_id) {
		$html = '';
		if(!empty($cartInfo)) {
			$total = 0;
			$grand_total = 0;
			$due_amount = 0;
			$settings = $this->getWebsiteSettingsInformation($con);
			foreach($cartInfo as $cartdata) {
				$parts_total = (float)$cartdata['parts_price'] * $cartdata['quantity'];
				$total += (float)$parts_total;
				$parts_total = number_format($parts_total,2);
				$parts_info = $this->getPartsInformationById($cartdata['parts_id'], $con);
				$html .= "<tr>";
				$html .= "	<td><img class='img-thumbnail' style='width:70px;' src='".$parts_info['parts_image']."'></td>";
				$html .= "	<td>".$parts_info['parts_name']."</td>";
				$html .= "	<td>".$parts_info['parts_warranty']."</td>";
				$html .= "	<td style='text-transform:capitalize;'>".$parts_info['parts_condition']."</td>";
				$html .= "	<td>".$settings['currency'].$cartdata['parts_price']."</td>";
				$html .= "	<td>".$cartdata['quantity']."</td>";
				$html .= "	<td><b>".$settings['currency'].$parts_total."</b></td>";
				$html .= "	<td><a href='javascript:;' onclick='deleteCartPartsAfterSold(".$sold_id.",".$cartdata['parts_id'].",".$cartdata['quantity'].");' class='btn btn-danger'><i class='fa fa-trash'></a></a></td>";
				$html .= "</tr>";
			}
			/*Discount*/
			if((float)$discount > 0) {
				$grand_total = (float)((float)$total - (float)((float)((float)$total * (float)$discount) / 100));
			} else {
				$grand_total = $total;
			}
			if((float)$paid_amount > 0) {
				$due_amount = (float)$grand_total - (float)$paid_amount;
			}
			$html .= "<tr>";
			$html .= "	<td>&nbsp;</td>";
			$html .= "	<td>&nbsp;</td>";
			$html .= "	<td>&nbsp;</td>";
			$html .= "	<td>&nbsp;</td>";
			$html .= "	<td align='right'><b>Total: </b></td>";
			$html .= "	<td><b>".$settings['currency'].number_format($total,2)."</b><input type='hidden' id='hdntotal' name='hdntotal' value='".$total."'></td>";
			$html .= "	<td>&nbsp;</td>";
			$html .= "</tr>";
			$html .= "<tr>";
			$html .= "	<td>&nbsp;</td>";
			$html .= "	<td>&nbsp;</td>";
			$html .= "	<td>&nbsp;</td>";
			$html .= "	<td>&nbsp;</td>";
			$html .= "	<td align='right'><b>Discount (%): </b></td>";
			$html .= "	<td><input type='text' class='allownumberonly'' style='text-align:left;font-weight:bold;border:none;border-bottom:solid 1px #ccc;' size='8' name='txtSellDiscount' value='".$discount."' id='txtSellDiscount'></td>";
			$html .= "	<td>&nbsp;</td>";
			$html .= "</tr>";
			$html .= "<tr>";
			$html .= "	<td>&nbsp;</td>";
			$html .= "	<td>&nbsp;</td>";
			$html .= "	<td>&nbsp;</td>";
			$html .= "	<td>&nbsp;</td>";
			$html .= "	<td align='right'><b>Paid Amount (".$settings['currency']."): </b></td>";
			$html .= "	<td><input type='text' class='allownumberonly' style='text-align:left;font-weight:bold;border:none;border-bottom:solid 1px #ccc;' size='8' name='txtSellPaidamount' value='".$paid_amount."' id='txtSellPaidamount'></td>";
			$html .= "	<td>&nbsp;</td>";
			$html .= "</tr>";
			$html .= "<tr>";
			$html .= "	<td>&nbsp;</td>";
			$html .= "	<td>&nbsp;</td>";
			$html .= "	<td>&nbsp;</td>";
			$html .= "	<td>&nbsp;</td>";
			$html .= "	<td align='right'><b>Due Amount : </b></td>";
			$html .= "	<td><b>".$settings['currency']."<span id='due_amount'>".number_format($due_amount,2)."</span></b></td>";
			$html .= "	<td>&nbsp;<input type='hidden' name='hdn_due_amount' id='hdn_due_amount' value='".number_format($due_amount,2)."'></td>";
			$html .= "</tr>";
			$html .= "<tr>";
			$html .= "	<td>&nbsp;</td>";
			$html .= "	<td>&nbsp;</td>";
			$html .= "	<td>&nbsp;</td>";
			$html .= "	<td>&nbsp;</td>";
			$html .= "	<td align='right'><b>Grand Total: </b></td>";
			$html .= "	<td><b>".$settings['currency']."<span id='grand_total'>".number_format($grand_total,2)."</span></b></td>";
			$html .= "	<td>&nbsp;<input type='hidden' name='hdn_grand_total' id='hdn_grand_total' value='".number_format($grand_total,2)."'></td>";
			$html .= "</tr>";
			
		}
		return $html;
	}
	
	/*
	* @CMS menu work
	*/
	public function getMenuList($con) {
		$data = array();
		$result = mysqli_query($con,"SELECT *,(select menu_name from tbl_menu me where me.menu_id = m.parent_id) as p_menu FROM tbl_menu m LEFT JOIN tbl_cms c ON c.cms_id = m.cms_page order by m.menu_name ASC");
		while($row = mysqli_fetch_assoc($result)){
			$data[] = $row;
		}
		return $data;
	}
	/*
	* @get parent menu list
	*/
	public function getParentMenuList($con) {
		$data = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_menu where parent_id = 0 order by menu_name ASC");
		while($row = mysqli_fetch_assoc($result)){
			$data[] = $row;
		}
		return $data;
	}
	/*
	* @get cms page list
	*/
	public function getCMSPageList($con) {
		$data = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_cms order by page_title");
		while($row = mysqli_fetch_assoc($result)){
			$data[] = $row;
		}
		return $data;
	}
	
	/*
	* @get all service list
	*/
	public function getServiceList($con) {
		$data = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_service ORDER BY sort_order ASC");
		while($row = mysqli_fetch_assoc($result)){
			$data[] = $row;
		}
		return $data;
	}
	
	/*
	* @get all active service list
	*/
	public function getAllActiveServiceList($con) {
		$data = array();
		$result = mysqli_query($con,"SELECT *,c.seo_url FROM tbl_service s LEFT JOIN tbl_cms c ON c.cms_id = s.page_id WHERE s.status = 1 ORDER BY sort_order ASC");
		while($row = mysqli_fetch_assoc($result)){
			$data[] = $row;
		}
		return $data;
	}
	
	/*
	* @get all slider list
	*/
	public function getAllSliderList($con) {
		$data = array();
		$result = mysqli_query($con,"SELECT * FROM  tbl_slider order by sort_id ASC");
		while($row = mysqli_fetch_assoc($result)){
			$data[] = $row;
		}
		return $data;
	}
	
	/*
	* @get all Comments list
	*/
	public function getAllCommentsList($con) {
		$data = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_customer_comments order by comments_id DESC");
		while($row = mysqli_fetch_assoc($result)){
			$data[] = $row;
		}
		return $data;
	}
	
	/*
	* @get all News Comments list
	*/
	public function getAllNewsCommentsList($con) {
		$data = array();
		$result = mysqli_query($con,"SELECT *,b.blog_title FROM tbl_blog_comments bc INNER JOIN tbl_blog b ON b.blog_id = bc.blog_id order by bc.blog_id DESC");
		while($row = mysqli_fetch_assoc($result)){
			$data[] = $row;
		}
		return $data;
	}
	
	/*
	* @get all home view Comments list
	*/
	public function getAllActiveCommentsList($con) {
		$data = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_customer_comments WHERE status = 1 AND approve = 1 order by comments_id DESC");
		while($row = mysqli_fetch_assoc($result)){
			$image = '';
			if($row['image_url'] != ''){
				$image = WEB_URL . 'img/comments/' . $row['image_url'];
			}
			$data[] = array(
				'comments'		=> $row['comments'],
				'author'		=> $row['author'],
				'profession'	=> $row['profession'],
				'image_url'		=> $image
			);
		}
		return $data;
	}
	
	/*
	* @get all active slider list
	*/
	public function getAllActiveSliderList($con) {
		$data = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_slider WHERE status = 1 ORDER BY sort_id ASC");
		while($row = mysqli_fetch_assoc($result)){
			$data[] = $row;
		}
		return $data;
	}
	
	/*
	* @get all mechanics deg list
	*/
	public function getMechanicsDesignation($con) {
		$data = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_mechanics_designation ORDER BY title ASC");
		while($row = mysqli_fetch_assoc($result)){
			$data[] = $row;
		}
		return $data;
	}
	
	/*
	* @get frontend menu array
	*/
	public function getFrontentMenuList($con) {
		$menu_array = array();
		$result = mysqli_query($con,"select *,c.seo_url from tbl_menu m LEFT JOIN tbl_cms c ON c.cms_id = m.cms_page where m.parent_id = 0 and m.menu_status= 1 order by m.menu_sort_order ASC");
		while($row = mysqli_fetch_assoc($result)){			
			//1 label
			$href_parent = '';
			$child_menu = array();
			$result_child = mysqli_query($con,"select *,c.seo_url from tbl_menu m LEFT JOIN tbl_cms c ON c.cms_id = m.cms_page where m.parent_id = '".$row['menu_id']."' and m.menu_status= 1 order by m.menu_sort_order ASC");
			while($row_child = mysqli_fetch_assoc($result_child)){
				$href_child = '';
				if(!empty($row_child['fixed_page_url'])) {
					$href_child = $row_child['fixed_page_url'];
				} else {
					$href_child = $row_child['seo_url'];
				}
				$child_menu[] = array(
					'menu_id'			=> $row_child['menu_id'],
					'parent_id'			=> $row_child['parent_id'],
					'menu_name'			=> $row_child['menu_name'],
					'menu_sort_order'	=> $row_child['menu_sort_order'],
					'href'				=> $href_child,
					'menu_status'		=> $row_child['menu_status']
				);
			}
			if(!empty($row['fixed_page_url'])) {
				$href_parent = $row['fixed_page_url'];
			} else {
				$href_parent = $row['seo_url'];
			}
			$menu_array[] = array(
				'menu_id'			=> $row['menu_id'],
				'parent_id'			=> $row['parent_id'],
				'menu_name'			=> $row['menu_name'],
				'menu_sort_order'	=> $row['menu_sort_order'],
				'href'				=> $href_parent,
				'menu_status'		=> $row['menu_status'],
				'url_slug'			=> $row['url_slug'],
				'child_menu'		=> $child_menu
			);
		}
		return $menu_array;
	}
	
	/*
	* @save/update service information
	*/
	public function saveUpdateServiceInformation($con, $data, $image_url) {
		if(!empty($data)) {
			$cmspage = 0;
			if(isset($data['rbCMSPage']) && is_numeric($data['rbCMSPage']) && (int)$data['rbCMSPage'] > 0) {
				$cmspage = $data['rbCMSPage'];
			}
			if($data['service_id'] == '0') {
				mysqli_query($con,"INSERT INTO tbl_service(service_name,image_url,short_description,page_id,sort_order,status) values('$data[service_title]','$image_url','$data[service_sort_desc]','$cmspage','$data[sort_order]','$data[status]')");
			} else {
				mysqli_query($con,"UPDATE tbl_service SET service_name='$data[service_title]',image_url='$image_url',short_description='$data[service_sort_desc]',page_id='$cmspage',sort_order='$data[sort_order]',status='$data[status]' WHERE service_id=$data[service_id]");
			}
		}
	}
	
	/*
	* @save/update cms information
	*/
	public function saveUpdateCMSInformation($con, $data) {
		if(!empty($data)) {
			$seo_url = '';
			$desc = mysqli_real_escape_string($con,$data['txtCmcontent']);
			if(!empty($data['txtSeo'])) {
				$seo_url = $data['txtSeo'];
			} else {
				$seo_url = $this->generateSeoUrl($data['txtPtitle']);
			}
			if($data['cms_id'] == '0') {
				mysqli_query($con,"INSERT INTO tbl_cms(page_title,seo_url,cms_status,page_details) values('$data[txtPtitle]','$seo_url','$data[txtStatus]','$desc')");
			} else {
				mysqli_query($con,"UPDATE `tbl_cms` SET `page_title`='".$data['txtPtitle']."',`seo_url`='".$seo_url."',`cms_status`='".$data['txtStatus']."',`page_details`='".$desc."' WHERE cms_id='".(int)$data['cms_id']."'");
			}
		}
	}
	
	/*
	* @get service info by id
	*/
	public function getServiceInfoByServiceId($con, $service_id) {
		$data = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_service where service_id = '".(int)$service_id. "'");
		if($row = mysqli_fetch_assoc($result)){
			$data = $row;
		}
		return $data;
	}
	
	/*
	* @get slider info by id
	*/
	public function getSliderInfoBySliderId($con, $slider_id) {
		$data = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_slider where slider_id = '" .(int)$slider_id. "'");
		if($row = mysqli_fetch_assoc($result)){
			$data = $row;
		}
		return $data;
	}
	
	/*
	* @get comments info by id
	*/
	public function getCommentsInfoByCommentsId($con, $comments_id) {
		$data = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_customer_comments where comments_id = '" .(int)$comments_id. "'");
		if($row = mysqli_fetch_assoc($result)){
			$data = $row;
		}
		return $data;
	}
	
	/*
	* @get comments info by id
	*/
	public function getNewsCommentsInfoByCommentsId($con, $comments_id) {
		$data = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_blog_comments where comments_id = '" .(int)$comments_id. "'");
		if($row = mysqli_fetch_assoc($result)){
			$data = $row;
		}
		return $data;
	}
	
	/*
	* @gallery home view
	*/
	public function getBlogAllCommentsByBlogId($con, $blog_id) {
		$data = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_blog_comments WHERE status = 1 AND approve = 1 AND blog_id = ".(int)$blog_id." ORDER BY comments_id DESC");
		while($row = mysqli_fetch_assoc($result)){
			$image = WEB_URL . 'img/no_comment.png';
			if($row['image'] != '' && file_exists(ROOT_PATH.'img/upload/'.$row['image'])){
				$image = WEB_URL . 'img/upload/' . $row['image'];
			}
			$phpdate = strtotime( $row['comments_date'] );
			$mysqldate = date( 'd F Y', $phpdate );
			$data[] = array(
				'name'			=> $row['name'],
				'email'			=> $row['email'],
				'comments'		=> $row['comments'],
				'image'			=> $image,
				'status'		=> $row['status'],
				'comments_date'	=> $mysqldate
			);
		}
		return $data;
	}
	
	/*
	* @delete service
	*/
	public function deleteService($con, $service_id) {
		mysqli_query($con,"DELETE FROM tbl_service where service_id = '".(int)$service_id. "'");
	}
	
	/*
	* @delete slider
	*/
	public function deleteSlider($con, $slider_id) {
		mysqli_query($con,"DELETE FROM tbl_slider where slider_id = '".(int)$slider_id. "'");
	}
	
	/*
	* @delete comments
	*/
	public function deleteComments($con, $comments_id) {
		mysqli_query($con,"DELETE FROM tbl_customer_comments where comments_id = '".(int)$comments_id. "'");
	}
	
	/*
	* @delete news comments
	*/
	public function deleteNewsComments($con, $comments_id) {
		mysqli_query($con,"DELETE FROM tbl_blog_comments where comments_id = '".(int)$comments_id. "'");
	}
	
	/*
	* @delete gallery
	*/
	public function deleteGallery($con, $gallery_id) {
		mysqli_query($con,"DELETE FROM tbl_gallery_category where gallery_id = '".(int)$gallery_id. "'");
		mysqli_query($con,"DELETE FROM tbl_gallery_images where category_id = '".(int)$gallery_id. "'");
	}
	
	/*
	* @delete cms
	*/
	public function deleteCMS($con, $cms_id) {
		mysqli_query($con,"DELETE FROM `tbl_cms` WHERE cms_id = ".(int)$cms_id);
	}
	
	/*
	* @save/update slider information
	*/
	public function saveUpdateSliderInformation($con, $data, $image_url) {
		if(!empty($data)) {
			if($data['slider_id'] == '0') {
				mysqli_query($con,"INSERT INTO tbl_slider(slider_text,slider_url,html_text,slider_image,sort_id,status) values('$data[txtStext]','$data[txtSurl]','$data[html_text]','$image_url','$data[txtSid]','$data[status]')");
			} else {
				mysqli_query($con,"UPDATE tbl_slider SET slider_text='$data[txtStext]',slider_url='$data[txtSurl]',html_text='$data[html_text]',slider_image='$image_url',sort_id='$data[txtSid]',status='$data[status]' WHERE slider_id=$data[slider_id]");
			}
		}
	}
	
	
	/*
	* @save/update customer news comments
	*/
	public function saveUpdateNewsCommentsInformation($con, $data, $image_url) {
		if(!empty($data)) {
			$approve = 0;
			if(isset($data['chkApprove']) && $data['chkApprove'] == 'on') {
				$approve = 1;
			}
			$added_date = date('Y-m-d');
			$desc = mysqli_real_escape_string($con,$data['txtComments']);
			if($data['comments_id'] == '0') {
				mysqli_query($con,"INSERT INTO  tbl_blog_comments(blog_id,name,email,comments,image,approve,status,comments_date) values('$data[ddlBlog]','$data[txtAuthorName]','$data[txtEmail]','$desc','$image_url','$approve','$data[status]','$added_date')");
			} else {
				mysqli_query($con,"UPDATE  tbl_blog_comments SET blog_id='$data[ddlBlog]',name='$data[txtAuthorName]',email='$data[txtEmail]',comments='$desc',image='$image_url',approve='$approve',status='$data[status]',comments_date='$added_date' WHERE comments_id=$data[comments_id]");
			}
		}
	}
	
	
	/*
	* @save/update customer comments
	*/
	public function saveUpdateCommentsInformation($con, $data, $image_url) {
		if(!empty($data)) {
			$approve = 0;
			if(isset($data['chkApprove']) && $data['chkApprove'] == 'on') {
				$approve = 1;
			}
			if($data['comments_id'] == '0') {
				mysqli_query($con,"INSERT INTO tbl_customer_comments(comments,author,profession,image_url,approve,status) values('$data[txtComments]','$data[txtAuthorName]','$data[txtAuthorProfession]','$image_url','$approve','$data[status]')");
			} else {
				mysqli_query($con,"UPDATE tbl_customer_comments SET comments='$data[txtComments]',author='$data[txtAuthorName]',profession='$data[txtAuthorProfession]',image_url='$image_url',approve='$approve',status='$data[status]' WHERE comments_id=$data[comments_id]");
			}
		}
	}
	
	/*
	* @save/update gallery category
	*/
	public function saveUpdateGalleryCategoryInformation($con, $data) {
		$gallery_id = 0;
		if(!empty($data)) {
			if($data['gallery_id'] == '0') {
				mysqli_query($con,"INSERT INTO tbl_gallery_category(gallery_name,sort_order,status) values('$data[txtWorkCategoryName]','$data[txtSortOrder]','$data[status]')");
				$gallery_id = mysqli_insert_id($con);
			} else {
				mysqli_query($con,"UPDATE tbl_gallery_category SET gallery_name='$data[txtWorkCategoryName]',sort_order='$data[txtSortOrder]',status='$data[status]' WHERE gallery_id=$data[gallery_id]");
				$gallery_id = $data['gallery_id'];
			}
		}
		if($gallery_id > 0) {
			mysqli_query($con,"DELETE FROM  tbl_gallery_images where category_id = '".(int)$gallery_id. "'");
		}
		return $gallery_id;
	}
	
	/*
	* @gallery home view
	*/
	public function galleryHomeView($con) {
		$data = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_gallery_category WHERE status = 1 ORDER BY sort_order ASC");
		while($row = mysqli_fetch_assoc($result)){
			$data[] = array(
				'gallery_id'	=> $row['gallery_id'],
				'gallery_name'	=> $row['gallery_name'],
				'class'			=> $this->generateSeoUrl($row['gallery_name']),
				'sort_order'	=> $row['sort_order'],
				'status'		=> $row['status'],
				'images'		=> $this->getAllGalleryImagesByCategoryId($con, $row['gallery_id'])
			);
		}
		return $data;
	}
	
	/*
	* @save gallery images
	*/
	public function saveUpdateGalleryInformation($con, $data) {
		if(!empty($data)) {
			mysqli_query($con,"INSERT INTO tbl_gallery_images(category_id,image_url,text,sort_order) values('$data[category_id]','$data[image_url]','$data[text]','$data[sort_order]')");
		}
	}
	
	/*
	* @get all gellery Information
	*/
	public function getAllGalleryInformation($con) {
		$data = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_gallery_category ORDER BY gallery_name ASC");
		while($row = mysqli_fetch_assoc($result)){
			$data[] = array(
				'gallery_id'	=> $row['gallery_id'],
				'gallery_name'	=> $row['gallery_name'],
				'sort_order'	=> $row['sort_order'],
				'status'		=> $row['status'],
				'images'		=> $this->getAllGalleryImagesByCategoryId($con, $row['gallery_id'])
			);
		}
		return $data;
	}
	public function getAllGalleryImagesByCategoryId($con, $category_id) {
		$data = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_gallery_images WHERE category_id = ".(int)$category_id." ORDER BY sort_order ASC");
		while($row = mysqli_fetch_assoc($result)){
			$image = '';
			if($row['image_url'] != ''){
				$image = WEB_URL . 'img/gallery/' . $row['image_url'];
			}
			$data[] = array(
				'image' 		=> $row['image_url'],
				'image_url' 	=> $image,
				'sort_order'	=> $row['sort_order'],
				'text'			=> $row['text']
			);
		}
		return $data;
	}
	public function getGalleryInformationById($con, $category_id) {
		$data = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_gallery_category WHERE gallery_id = ".(int)$category_id." ORDER BY gallery_name ASC");
		if($row = mysqli_fetch_assoc($result)){
			$data = array(
				'gallery_id'	=> $row['gallery_id'],
				'gallery_name'	=> $row['gallery_name'],
				'sort_order'	=> $row['sort_order'],
				'status'		=> $row['status'],
				'images'		=> $this->getAllGalleryImagesByCategoryId($con, $category_id)
			);
		}
		return $data;
	}
	
	/*
	* @save/update mechanics information
	*/
	public function saveUpdateMechanicsInformation($con, $data, $image_url) {
		if(!empty($data)) {
			$join_date = $this->datepickerDateToMySqlDate($data['txtJoiningDate']);
			if($data['mechanics_id'] == '0') {
				// mysqli_query($con,"INSERT INTO tbl_add_mechanics(m_name,m_cost,m_phone_number,m_password,m_email,m_present_address,m_permanent_address,m_notes,m_image,designation_id,status,joining_date) values('$data[txtSName]','$data[cost_per_month]','$data[txtPhonenumber]','$data[txtSPassword]','$data[txtSEmail]','$data[present_address]','$data[permanent_address]','$data[notes]','$image_url','$data[designation]','$data[status]','$join_date')");
				mysqli_query($con,"INSERT INTO tbl_add_mechanics(m_name,m_phone_number,m_password,m_email,m_present_address,m_permanent_address,m_notes,m_image,designation_id,status,joining_date) values('$data[txtSName]','$data[txtPhonenumber]','$data[txtSPassword]','$data[txtSEmail]','$data[present_address]','$data[permanent_address]','$data[notes]','$image_url','$data[designation]','$data[status]','$join_date')");
			} else {
				//mysqli_query($con,"UPDATE `tbl_add_mechanics` SET `m_name`='".$data['txtSName']."',`m_cost`='".$data['cost_per_month']."',`m_phone_number`='".$data['txtPhonenumber']."',`m_password`='".$data['txtSPassword']."',`m_email`='".$data['txtSEmail']."',`m_present_address`='".$data['present_address']."',`m_permanent_address`='".$data['permanent_address']."',`m_notes`='".$data['notes']."',`m_image`='".$image_url."',designation_id='".$data['designation']."',status='".$data['status']."',joining_date='".$join_date."' WHERE mechanics_id='".(int)$data['mechanics_id']."'");
				mysqli_query($con,"UPDATE `tbl_add_mechanics` SET `m_name`='".$data['txtSName']."',`m_phone_number`='".$data['txtPhonenumber']."',`m_password`='".$data['txtSPassword']."',`m_email`='".$data['txtSEmail']."',`m_present_address`='".$data['present_address']."',`m_permanent_address`='".$data['permanent_address']."',`m_notes`='".$data['notes']."',`m_image`='".$image_url."',designation_id='".$data['designation']."',status='".$data['status']."',joining_date='".$join_date."' WHERE mechanics_id='".(int)$data['mechanics_id']."'");
			}
		}
	}
	
	/*
	* @save/update mechanics information
	*/
	public function saveUpdateCustomerInformation($con, $data, $image_url) {
		if(!empty($data)) {
			if($data['customer_id'] == '0') {
				mysqli_query($con,"INSERT INTO tbl_add_customer(c_name,c_email, c_address, c_home_tel,c_work_tel,c_mobile,c_password,image) values('$data[txtCName]','$data[txtCEmail]','$data[txtCAddress]','$data[txtCHomeTel]','$data[txtCWorkTel]','$data[txtCMobile]','$data[txtCPassword]','$image_url')");
			} else {
				mysqli_query($con,"UPDATE `tbl_add_customer` SET `c_name`='".$data['txtCName']."',`c_email`='".$data['txtCEmail']."',`c_address`='".$data['txtCAddress']."',`c_home_tel`='".$data['txtCHomeTel']."',`c_work_tel`='".$data['txtCWorkTel']."',`c_mobile`='".$data['txtCMobile']."',`c_password`='".$data['txtCPassword']."',`image`='".$image_url."' WHERE customer_id='".(int)$data['customer_id']."'");
			}
		}
	}
	
	/*
	* @save/update mechanics work status
	*/
	public function saveUpdateMechanicsWorkStatus($con, $data) {
		if(!empty($data)) {
			if($data['work_id'] == '0') {
				mysqli_query($con,"INSERT INTO tbl_daily_work(mechanic_id,work_date, total_hour, work_details) values('$data[mechanic_id]','".$this->datepickerDateToMySqlDate($data['txtWorkDate'])."','$data[txtTotalHour]','$data[txtWorkDetails]')");
			} else {
				mysqli_query($con,"UPDATE `tbl_daily_work` SET `work_date`='".$this->datepickerDateToMySqlDate($data['txtWorkDate'])."',`total_hour`='".$data['txtTotalHour']."',`work_details`='".$data['txtWorkDetails']."' WHERE work_id='".(int)$data['work_id']."'");
			}
		}
	}
	
	/*
	* @update user profile
	*/
	public function updateAdminUserProfile($con, $data, $image_url) {
		if(!empty($data)) {
			mysqli_query($con,"UPDATE `tbl_admin` SET `name`='".$data['txtUserName']."',`email`='".$data['txtEmail']."',`password`='".$data['txtPassword']."',`image`='".$image_url."' WHERE user_id = '".(int)$data['hdnUserId']."'");
		}
	}
	
	/*
	* @update mechanice profile
	*/
	public function updateMechanicesUserProfile($con, $data, $image_url) {
		if(!empty($data)) {
			mysqli_query($con,"UPDATE `tbl_add_mechanics` SET `m_name`='".$data['txtUserName']."',`m_email`='".$data['txtEmail']."',`m_password`='".$data['txtPassword']."',`m_image`='".$image_url."' WHERE mechanics_id = '".(int)$data['hdnUserId']."'");
		}
	}
	
	/*
	* @update customer profile
	*/
	public function updateCustomerUserProfile($con, $data, $image_url) {
		if(!empty($data)) {
			mysqli_query($con,"UPDATE `tbl_add_customer` SET `c_name`='".$data['txtUserName']."',`c_email`='".$data['txtEmail']."',`c_password`='".$data['txtPassword']."',`image`='".$image_url."' WHERE customer_id = '".(int)$data['hdnUserId']."'");
		}
	}
	
	/*
	* @save/update manufacturer information
	*/
	public function saveUpdateManufacturerInformation($con, $data, $image_url) {
		if(!empty($data)) {
			if($data['manufacturer_id'] == '0') {
				mysqli_query($con,"INSERT INTO tbl_manufacturer(manufacturer_name,manufacturer_image) values('$data[txtCName]','$image_url')");
			} else {
				mysqli_query($con,"UPDATE `tbl_manufacturer` SET `manufacturer_name`='".$data['txtCName']."',`manufacturer_image`='".$image_url."' WHERE manufacturer_id='".$data['manufacturer_id']."'");
			}
		}
	}
	
	/*
	* @save/update buy car information
	*/
	public function saveUpdateBuyCarInformation($con, $data, $image_url) {
		if(!empty($data)) {
			if($data['buycar_id'] == '0') {
			 //mysqli_query($con,"INSERT INTO tbl_buycar(owner_name,owner_mobile,owner_email,nid,company_name,ctl, owner_address,car_name,car_condition,car_color,car_door,make_id,model_id,year_id,car_reg_no,car_reg_date,car_chasis_no,car_engine_name,car_totalmileage,car_sit,car_note,car_image,buy_price,asking_price,buy_given_amount,buy_note,buy_date) values('$data[txtOwnerName]','$data[txtMobile]','$data[txtEmail]','$data[txtNid]','$data[txtCompanyname]','$data[txtCTL]','$data[txtAddress]','$data[txtCarname]','$data[txtCondition]','$data[txtCarcolor]','$data[txtCardoor]','$data[ddlMake]','$data[ddlModel]','$data[ddlYear]','$data[txtRegnumber]','$data[txtRegDate]','$data[txtChasisnumber]','$data[txtEnginename]','$data[txtTotalmileasge]','$data[txtCarSeat]','$data[txtNote]','$image_url','$data[txtBuyPrice]','$data[txtAskingPrice]','$data[txtGivamount]','$data[txtBuynote]','".$this->datepickerDateToMySqlDate($data['txtBuyDate'])."')");
			 mysqli_query($con,"INSERT INTO tbl_buycar(owner_name,owner_mobile,owner_email,nid,company_name,owner_address,car_name,car_condition,car_color,car_door,make_id,model_id,year_id,car_reg_date,car_chasis_no,car_engine_name,car_note,car_image,buy_price,asking_price,buy_given_amount,buy_note,buy_date) values('$data[txtOwnerName]','$data[txtMobile]','$data[txtEmail]','$data[txtNid]','$data[txtCompanyname]','$data[txtAddress]','$data[txtCarname]','$data[txtCondition]','$data[txtCarcolor]','$data[txtCardoor]','$data[ddlMake]','$data[ddlModel]','$data[ddlYear]','$data[txtRegDate]','$data[txtChasisnumber]','$data[txtEnginename]','$data[txtNote]','$image_url','$data[txtBuyPrice]','$data[txtAskingPrice]','$data[txtGivamount]','$data[txtBuynote]','".$this->datepickerDateToMySqlDate($data['txtBuyDate'])."')");
			//print_r(mysqli_error($con));die;
			} else {
				//mysqli_query($con,"UPDATE `tbl_buycar` SET `owner_name`='".$data['txtOwnerName']."',`owner_mobile`='".$data['txtMobile']."',`owner_email`='".$data['txtEmail']."',`nid`='".$data['txtNid']."',`company_name`='".$data['txtCompanyname']."',`ctl`='".$data['txtCTL']."',`owner_address`='".$data['txtAddress']."',`car_name`='".$data['txtCarname']."',`car_condition`='".$data['txtCondition']."',`car_color`='".$data['txtCarcolor']."',`car_door`='".$data['txtCardoor']."',`make_id`='".$data['ddlMake']."',`model_id`='".$data['ddlModel']."',`year_id`='".$data['ddlYear']."',`car_reg_no`='".$data['txtRegnumber']."',`car_reg_date`='".$data['txtRegDate']."',`car_chasis_no`='".$data['txtChasisnumber']."',`car_engine_name`='".$data['txtEnginename']."',`car_totalmileage`='".$data['txtTotalmileasge']."',`car_sit`='".$data['txtCarSeat']."',`car_note`='".$data['txtNote']."',`car_image`='".$image_url."',`buy_price`='".$data['txtBuyPrice']."',`asking_price`='".$data['txtAskingPrice']."',`buy_given_amount`='".$data['txtGivamount']."',`buy_note`='".$data['txtBuynote']."',`buy_date`='".$this->datepickerDateToMySqlDate($data['txtBuyDate'])."' WHERE buycar_id='".$data['buycar_id']."'");
				mysqli_query($con,"UPDATE `tbl_buycar` SET `owner_name`='".$data['txtOwnerName']."',`owner_mobile`='".$data['txtMobile']."',`owner_email`='".$data['txtEmail']."',`nid`='".$data['txtNid']."',`company_name`='".$data['txtCompanyname']."',`owner_address`='".$data['txtAddress']."',`car_name`='".$data['txtCarname']."',`car_condition`='".$data['txtCondition']."',`car_color`='".$data['txtCarcolor']."',`car_door`='".$data['txtCardoor']."',`make_id`='".$data['ddlMake']."',`model_id`='".$data['ddlModel']."',`year_id`='".$data['ddlYear']."',`car_reg_date`='".$data['txtRegDate']."',`car_chasis_no`='".$data['txtChasisnumber']."',`car_engine_name`='".$data['txtEnginename']."',`car_note`='".$data['txtNote']."',`car_image`='".$image_url."',`buy_price`='".$data['txtBuyPrice']."',`asking_price`='".$data['txtAskingPrice']."',`buy_given_amount`='".$data['txtGivamount']."',`buy_note`='".$data['txtBuynote']."',`buy_date`='".$this->datepickerDateToMySqlDate($data['txtBuyDate'])."' WHERE buycar_id='".$data['buycar_id']."'");
			}
		}
	} 
	
	/*
	* @save/update mechanics salery information
	*/
	public function saveUpdateMechanicSaleryInformation($con, $data) {
		if(!empty($data)) {
			if($data['salery_id'] == '0') {
				//mysqli_query($con,"INSERT INTO tbl_mcncsslary(mechanics_id,fix_salary,total_time,paid_amount,due_amount,total,month_id,year_id,sl_date) values('$data[ddlMechanicslist]','$data[txtFixsalary]','$data[txtTotaltime]','$data[given_amount]','$data[pending_amount]','$data[txtTotal]','$data[ddlMonth]','$data[ddlYear]','".$this->datepickerDateToMySqlDate($data['txtSalarydate'])."')");
				mysqli_query($con,"INSERT INTO tbl_mcncsslary(mechanics_id,month_id,year_id) values('$data[ddlMechanicslist]','$data[ddlMonth]','$data[ddlYear]')");
			} else {
				mysqli_query($con,"UPDATE `tbl_mcncsslary` SET `mechanics_id`='".$data['ddlMechanicslist']."',`fix_salary`='".$data['txtFixsalary']."',`total_time`='".$data['txtTotaltime']."',`paid_amount`='".$data['given_amount']."',`due_amount`='".$data['pending_amount']."',`total`='".$data['txtTotal']."',`month_id`='".$data['ddlMonth']."',`year_id`='".$data['ddlYear']."',`sl_date`='".$this->datepickerDateToMySqlDate($data['txtSalarydate'])."' WHERE m_salary_id='".$data['salery_id']."'");
			}
		}
	}
	
	/*
	* @save/update supplier information
	*/
	public function saveUpdateSupplierInformation($con, $data, $image_url) {
		if(!empty($data)) {
			if($data['supplier_id'] == '0') {
				mysqli_query($con,$sql = "INSERT INTO tbl_add_supplier(s_name,s_email,s_address,country_id,state_id,phone_number,fax_number,post_code,website_url,s_password,image) values('$data[txtSName]','$data[txtSEmail]','$data[txtSAddress]','$data[ddlCountry]','$data[ddlState]','$data[txtPhonenumber]','$data[txtFax]','$data[txtPostcode]','$data[txtWebsite]','$data[txtSPassword]','$image_url')");
				$sup_id = mysqli_insert_id($con);
				if(!empty($data['manufacturer']) && count($data['manufacturer'])> 0) {
					foreach($data['manufacturer'] as $mid) {
						mysqli_query($con,"INSERT INTO tbl_supplier_manufacturer(supplier_id,manufacturer_id) values('$sup_id','$mid')");
					}
				}
			} else {
				mysqli_query($con,"UPDATE `tbl_add_supplier` SET `s_name`='".$data['txtSName']."',`s_email`='".$data['txtSEmail']."',`s_address`='".$data['txtSAddress']."',`country_id`='".$data['ddlCountry']."',`state_id`='".$data['ddlState']."',`phone_number`='".$data['txtPhonenumber']."', `fax_number`='".$data['txtFax']."', `post_code`='".$data['txtPostcode']."', `website_url`='".$data['txtWebsite']."',`s_password`='".$data['txtSPassword']."',`image`='".$image_url."' WHERE supplier_id='".$data['supplier_id']."'");
				mysqli_query($con,'DELETE FROM tbl_supplier_manufacturer WHERE supplier_id ='.(int)$data['supplier_id']);
				if(!empty($data['manufacturer']) && count($data['manufacturer']) > 0) {
					foreach($data['manufacturer'] as $mid) {
						mysqli_query($con,"INSERT INTO tbl_supplier_manufacturer(supplier_id,manufacturer_id) values('$data[supplier_id]','$mid')");
					}
				}
			}
		}
	}
	
	/*
	* @save/update buy parts list information
	*/
	public function saveUpdateBuyPartsInformation($con, $data, $image_url) {
		if(!empty($data)) {
			$parts_id = $data['parts_id'];
			if(!empty($data['ddl_e_parts']) && (int)$data['ddl_e_parts'] > 0) {
				$parts_id = $data['ddl_e_parts'];
				$buy_data =[];
				$buy_part_data = mysqli_query($con,"SELECT * from tbl_buy_parts");
				while ($row = mysqli_fetch_array($buy_part_data)) {
					$buy_data[] = $row;
				}
				if(empty($buy_data)){
					mysqli_query($con,"INSERT INTO tbl_parts_stock(invoice_id,parts_id,parts_name,supplier_id,manufacturer_id,parts_condition,parts_buy_price,parts_quantity,parts_sku,parts_warranty,total_amount,given_amount,pending_amount,parts_image,parts_added_date) values('$data[invoice_id]','$parts_id','$data[parts_names]','$data[ddl_supplier]','$data[ddl_load_manufracturer]','$data[txtCondition]','$data[buy_prie]','$data[parts_quantity]','$data[parts_sku]','$data[parts_warranty]','$data[total_amount]','$data[given_amount]','$data[pending_amount]','$image_url','".$this->datepickerDateToMySqlDate($data['parts_add_date'])."')");
					//mysqli_query($con,"INSERT INTO tbl_parts_stock select * from tbl_buy_parts");
					//mysqli_query($con,"DELETE FROM tbl_buy_parts");
				}else{
					mysqli_query($con,"INSERT INTO tbl_parts_stock select * from tbl_buy_parts");					
					mysqli_query($con,"DELETE FROM tbl_buy_parts");
				}
				$stock_table = $this->getPartsStockStatusFromStockTable($con, $parts_id);
				if(!empty($stock_table)) {
					$qty = (int)$stock_table['quantity'] + (int)$data['parts_quantity'];
					mysqli_query($con,"UPDATE `tbl_parts_stock_manage` SET `parts_name` = '".$data['parts_names']."', `parts_image`='".$image_url."', `part_no`='".$data['parts_sku']."',`price`='".$data['parts_sell_price']."', `condition`='".$data['txtCondition']."', `parts_warranty`='".$data['parts_warranty']."', `supplier_id`='".$data['ddl_supplier']."', `manufacturer_id`='".$data['ddl_load_manufracturer']."',`quantity`='".(int)$qty."' WHERE parts_id = '".(int)$parts_id."'");
				}
			} else {
				$parts_id = $data['parts_id'];
				if($parts_id == '0') {
					//insert into stock table
					mysqli_query($con,"INSERT INTO `tbl_parts_stock_manage`(`parts_id`, `parts_name`, `parts_image`, `part_no`, `price`, `condition`, `parts_warranty`, `supplier_id`, `manufacturer_id`,`quantity`) VALUES (".(int)$parts_id.",'".$data['parts_names']."','".$image_url."','".$data['parts_sku']."','".$data['parts_sell_price']."','".$data['txtCondition']."','".$data['parts_warranty']."','".$data['ddl_supplier']."','".$data['ddl_load_manufracturer']."','".$data['parts_quantity']."')");
					
					$parts_id = mysqli_insert_id($con);
					
					//insert into putchase invoice table
					mysqli_query($con,"INSERT INTO tbl_parts_stock(invoice_id,parts_id,parts_name,supplier_id,manufacturer_id,parts_condition,parts_buy_price,parts_quantity,parts_sku,parts_warranty,total_amount,given_amount,pending_amount,parts_image,parts_added_date) values('$data[invoice_id]','$parts_id','$data[parts_names]','$data[ddl_supplier]','$data[ddl_load_manufracturer]','$data[txtCondition]','$data[buy_prie]','$data[parts_quantity]','$data[parts_sku]','$data[parts_warranty]','$data[total_amount]','$data[given_amount]','$data[pending_amount]','$image_url','".$this->datepickerDateToMySqlDate($data['parts_add_date'])."')");
					
				} else {
					mysqli_query($con,"UPDATE `tbl_parts_stock` SET `parts_name`='".$data['parts_names']."',`supplier_id`='".$data['ddl_supplier']."',`manufacturer_id`='".$data['ddl_load_manufracturer']."',`parts_condition`='".$data['txtCondition']."',`parts_buy_price`='".$data['buy_prie']."',`parts_quantity`='".$data['parts_quantity']."',`parts_sku`='".$data['parts_sku']."',`parts_warranty`='".$data['parts_warranty']."',`total_amount`='".$data['total_amount']."',`given_amount`='".$data['given_amount']."',`pending_amount`='".$data['pending_amount']."',`parts_image`='".$image_url."',`parts_added_date`='".$this->datepickerDateToMySqlDate($data['parts_add_date'])."' WHERE invoice_id='".trim($data['invoice_id'])."'");
					
					if((int)$parts_id > 0) {
						$old_qty = $data['old_qty'];
						$new_qty = $data['parts_quantity'];
						$stock_table = $this->getPartsStockStatusFromStockTable($con, $parts_id);
						if(!empty($stock_table)) {
							$current_qty = (int)$stock_table['quantity'];
							$current_qty = (int)$current_qty - (int)$old_qty;
							$current_qty = (int)$current_qty + (int)$new_qty;
							//update stock table
							//$this->saveUpdatePartsVirtualStockTable($con, $parts_id, $current_qty, 'u');
							mysqli_query($con,"UPDATE `tbl_parts_stock_manage` SET `parts_name` = '".$data['parts_names']."', `parts_image`='".$image_url."', `part_no`='".$data['parts_sku']."',`price`='".$data['parts_sell_price']."', `condition`='".$data['txtCondition']."', `parts_warranty`='".$data['parts_warranty']."', `supplier_id`='".$data['ddl_supplier']."', `manufacturer_id`='".$data['ddl_load_manufracturer']."',`quantity`='".(int)$current_qty."' WHERE parts_id = '".(int)$parts_id."'");
						}
					}
				}
			}
			//clear filter tabale for this parts
			mysqli_query($con,"DELETE FROM `tbl_parts_fit_data` WHERE parts_id = ".(int)$parts_id);
			//add again new
			if(isset($data['partsfilter']) && $data['partsfilter'] != '' && $parts_id > 0){
				foreach($data['partsfilter'] as $partsdata){
					mysqli_query($con,"INSERT INTO tbl_parts_fit_data SET parts_id = '" . (int)$parts_id . "',make_id = '" . (int)$partsdata['make'] . "',model_id = '" . (int)$partsdata['model'] . "',year_id = '" . (int)$partsdata['year'] . "'");
				}
			}
		}
	}
	
	
	/*
	* @update parts stock information
	*/
	public function updatePartsStockInformation($con, $data, $image_url) {
		$parts_id = $data['parts_id'];
		if(!empty($data)) {
			mysqli_query($con,"UPDATE `tbl_parts_stock_manage` SET `parts_name` = '".$data['parts_names']."', `parts_image`='".$image_url."', `part_no`='".$data['parts_sku']."',`price`='".$data['parts_sell_price']."', `condition`='".$data['txtCondition']."', `parts_warranty`='".$data['parts_warranty']."', `supplier_id`='".$data['ddl_supplier']."', `manufacturer_id`='".$data['ddl_load_manufracturer']."',`quantity`='".$data['parts_quantity']."',`status`='".$data['ddl_status']."' WHERE parts_id = '".(int)$parts_id."'");
		}
		mysqli_query($con,"DELETE FROM `tbl_parts_fit_data` WHERE parts_id = ".(int)$parts_id);
		if(isset($data['partsfilter']) && $data['partsfilter'] != '' && $parts_id > 0){
			foreach($data['partsfilter'] as $partsdata){
				mysqli_query($con,"INSERT INTO tbl_parts_fit_data SET parts_id = '" . (int)$parts_id . "',make_id = '" . (int)$partsdata['make'] . "',model_id = '" . (int)$partsdata['model'] . "',year_id = '" . (int)$partsdata['year'] . "'");
			}
		}
	}
	
	/*
	* @get specific parts exist to stock maintain table
	*/
	public function getPartsStockStatusFromStockTable($con, $parts_id) {
		$data = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_parts_stock_manage WHERE parts_id=".(int)$parts_id);
		if($row = mysqli_fetch_assoc($result)){
			$data = $row;
		}
		return $data;
	}
	/*
	* @save/update parts virtual stock table
	*/
	public function saveUpdatePartsVirtualStockTable($con, $parts_id, $stock, $token, $data) {
		if($token=='u') {
			mysqli_query($con,"UPDATE `tbl_parts_stock_manage` SET `stock`='".(int)$stock."' WHERE parts_id='".(int)$parts_id."'");
		} else {
			
		}
	}
	
	/*
	* @get all parts fit data
	*/
	public function getAllPartsFitDate($con, $parts_id) {
		$data = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_parts_fit_data WHERE parts_id=".(int)$parts_id);
		while($row = mysqli_fetch_assoc($result)){
			$data[] = $row;
		}
		return $data;
	}
	
	/*
	* @save/update repair car information
	*/
	public function saveUpdateRepairCarInformation($con, $data, $image_url) {
		if(!empty($data)) {
			if($data['repair_car'] == '0') {
				mysqli_query($con,"INSERT INTO tbl_add_car(repair_car_id,car_name,customer_id,mechanics_id,car_make,car_model,year,chasis_no,car_reg_no,VIN,note,added_date,image) values('$data[hfInvoiceId]','$data[car_names]','$data[ddlCustomerList]','$data[ddlMechanicList]','$data[ddlMake]','$data[ddlModel]','$data[ddlYear]','$data[car_chasis_no]','$data[registration]','$data[vin]','$data[car_note]','$data[add_date]','$image_url')");
			} else {
				mysqli_query($con,"UPDATE `tbl_add_car` SET `customer_id`='".$data['ddlCustomerList']."',`mechanics_id`='".$data['ddlMechanicList']."',`car_name`='".$data['car_names']."',`car_make`='".$data['ddlMake']."',`car_model`='".$data['ddlModel']."',`year`='".$data['ddlYear']."',`chasis_no`='".$data['car_chasis_no']."',`car_reg_no`='".$data['registration']."',`VIN`='".$data['vin']."',`note`='".$data['car_note']."',`added_date`='".$data['add_date']."',`image`='".$image_url."' WHERE car_id='".$data['repair_car']."'");
			}
		}
	}
	
	/*
	* @save/update mechanics page builder
	*/
	public function saveUpdateMechanicsPageInformation($con, $data) {
		if(!empty($data)) {
			mysqli_query($con,"DELETE FROM tbl_mechanics_page where mechanic_id = '".(int)$data['mechanics_id']. "'");
			$show_header = 0;
			if(isset($data['chkPageTitle']) && $data['chkPageTitle'] == 'on') {
				$show_header = 1;
			}
			$desc = mysqli_real_escape_string($con,$data['page_description']);
			mysqli_query($con,"INSERT INTO tbl_mechanics_page(mechanic_id,page_title,seo_url,page_details,hide_top_header,status) values('$data[mechanics_id]','$data[txtPageTitle]','$data[txtSeoUrl]','$desc','$show_header','$data[status]')");
		}
	}
	
	/*
	* @save/update menu
	*/
	public function saveUpdateMenuInformation($con, $data) {
		if(!empty($data)) {
			$fixed_url = '';
			$cmspage = 0;
			if(isset($data['rbCMSPage']) && is_numeric($data['rbCMSPage']) && (int)$data['rbCMSPage'] > 0) {
				$cmspage = $data['rbCMSPage'];
			}
			else if(isset($data['rbCMSPage']) && is_string($data['rbCMSPage']) && !empty($data['rbCMSPage'])) {
				$fixed_url = $data['rbCMSPage'];
			}
			$_parent_id = 0;
			if(!empty($data['txtParent'])){
				$_parent_id = $data['txtParent'];
			}
			if($data['menu_id'] == '0') {
				mysqli_query($con,"INSERT INTO tbl_menu(parent_id,menu_name,url_slug,menu_sort_order,menu_seo_url,cms_page,fixed_page_url,menu_banner,menu_status) values('$_parent_id','$data[txtMenuname]','$data[txtParentUrlSlug]','$data[txtSortodder]','','$cmspage','$fixed_url','','$data[txtStatus]')");
			} else {
				mysqli_query($con,"UPDATE `tbl_menu` SET `parent_id`='".$_parent_id."',`menu_name`='".$data['txtMenuname']."',`url_slug`='".$data['txtParentUrlSlug']."',`menu_sort_order`='".$_POST['txtSortodder']."',`menu_status`='".$_POST['txtStatus']."',`cms_page`='".$cmspage."',`fixed_page_url`='".$fixed_url."' WHERE menu_id='".(int)$data['menu_id']."'");
			}
		}
	}
	
	/*
	* @get all mechanics information
	*/
	public function getAllMechanicsList($con) {
		$data = array();
		$result = mysqli_query($con,"SELECT *,d.title FROM tbl_add_mechanics m LEFT JOIN tbl_mechanics_designation d ON d.designation_id = m.designation_id ORDER BY m.mechanics_id DESC");
		while($row = mysqli_fetch_assoc($result)){
			$data[] = $row;
		}
		return $data;
	}
	
	/*
	* @delete mechanics
	*/
	public function deleteMechanics($con, $mechanics_id) {
		mysqli_query($con,"DELETE FROM tbl_add_mechanics where mechanics_id = '".(int)$mechanics_id. "'");
	}
	
	/*
	* @delete manufacturer
	*/
	public function deleteManufacturer($con, $manufacturer_id) {
		mysqli_query($con,"DELETE FROM `tbl_manufacturer` WHERE manufacturer_id = ".(int)$manufacturer_id);
	}
	
	/*
	* @delete menu
	*/
	public function deleteMenu($con, $menu_id) {
		mysqli_query($con,"DELETE FROM `tbl_menu` WHERE menu_id = ".(int)$menu_id);
	}
	
	/*
	* @get mechanics info by id
	*/
	public function getMechanicsInfoByMechanicsId($con, $mechanics_id) {
		$data = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_add_mechanics where mechanics_id = '" .(int)$mechanics_id. "'");
		if($row = mysqli_fetch_assoc($result)){
			$data = $row;
		}
		return $data;
	}
	
	/*
	* @get customer info by id
	*/
	public function getCustomerInfoByCustomerId($con, $customer_id) {
		$data = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_add_customer where customer_id = '" .(int)$customer_id. "'");
		if($row = mysqli_fetch_assoc($result)){
			$data = $row;
		}
		return $data;
	}
	
	/*
	* @get work status info by id
	*/
	public function getWorkStatusInfoById($con, $w_id) {
		$data = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_daily_work where work_id = '" .(int)$w_id. "'");
		if($row = mysqli_fetch_assoc($result)){
			$data = $row;
		}
		return $data;
	}
	
	/*
	* @get manufacturer info by manufacturer id
	*/
	public function getManufacturerInfoByManufacturerId($con, $manufacturer_id) {
		$data = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_manufacturer where manufacturer_id = '" . (int)$manufacturer_id . "'");
		if($row = mysqli_fetch_assoc($result)){
			$data = $row;
		}
		return $data;
	}
	
	/*
	* @get buy car info by id
	*/
	public function getBuyCarInfoById($con, $buycar_id) {
		$data = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_buycar where buycar_id = '" .(int)$buycar_id. "'");
		if($row = mysqli_fetch_assoc($result)){
			$data = $row;
		}
		return $data;
	}
	
	/*
	* @get mechanic salery date by salery id
	*/
	public function getMechanicSlaeryInfoBySaleryId($con, $salery_id) {
		$data = array();
		$result = mysqli_query($con,"SELECT *,m.m_name FROM `tbl_mcncsslary` ms inner join tbl_add_mechanics m on m.mechanics_id = ms.mechanics_id where ms.m_salary_id = '" . (int)$salery_id."'");
		if($row = mysqli_fetch_assoc($result)){
			$data = $row;
		}
		return $data;
	}
	
	/*
	* @get supplier info by id
	*/
	public function getSupplierInfoBySupplierId($con, $supplier_id) {
		$data = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_add_supplier where supplier_id=".(int)$supplier_id);
		if($row = mysqli_fetch_assoc($result)){
			$data = $row;
		}
		return $data;
	}
	
	/*
	* @get customer car info by car id
	*/
	public function getCustomerCarInfoByCardId($con, $car_id) {
		$data = array();
		$result = mysqli_query($con,"SELECT *,m.make_name,mo.model_name,y.year_name FROM tbl_add_car c left join tbl_make m on m.make_id = c.car_make left join tbl_model mo on mo.model_id = c.car_model left join tbl_year y on y.year_id = c.year where c.car_id = ".(int)$car_id);
		if($row = mysqli_fetch_assoc($result)){
			$data = $row;
		}
		return $data;
	}
	
	/*
	* @get repair car info by id
	*/
	public function getRepairCarInfoByRepairCarId($con, $car_id) {
		$data = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_add_car where car_id = '" .(int)$car_id. "'");
		if($row = mysqli_fetch_assoc($result)){
			$data = $row;
		}
		return $data;
	}
	
	/*
	* @get all menu info by id
	*/
	public function getMenuInfoByMenuId($con, $menu_id) {
		$data = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_menu where menu_id = '" .(int)$menu_id. "'");
		if($row = mysqli_fetch_assoc($result)){
			$data = $row;
		}
		return $data;
	}
	
	/*
	* @get mechanics page info by id
	*/
	public function getMechanicsPageInfoByMechanicsId($con, $mechanics_id) {
		$data = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_mechanics_page where mechanic_id = '" .(int)$mechanics_id. "'");
		if($row = mysqli_fetch_assoc($result)){
			$data = $row;
		}
		return $data;
	}
	
	/*
	* @team view save/update
	*/
	public function saveUpdateTeamWidgetView($con, $data) {
		if(!empty($data)) {
			mysqli_query($con,"DELETE FROM tbl_home_team_widget");
			foreach($data['chkTeamId'] as $team) {
				$sort_order = $data['txtSortOrder'][$team];
				mysqli_query($con,"INSERT INTO `tbl_home_team_widget`(`team_id`, `sort_order`) VALUES ($team,$sort_order)");
			}
		}
	}
	
	/*
	* @get all FAQ information
	*/
	public function getFAQInformation($con) {
		$data = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_faq ORDER BY sort_order ASC");
		while($row = mysqli_fetch_assoc($result)){
			$data[] = $row;
		}
		return $data;
	}
	
	/*
	* @FAQ save/update
	*/
	public function saveUpdateFAQ($con, $data) {
		mysqli_query($con,"DELETE FROM tbl_faq");
		if(!empty($data)) {
			foreach($data['faq'] as $faq) {
				$title = mysqli_real_escape_string($con,$faq['title']);
				$content = mysqli_real_escape_string($con,$faq['content']);
				$sort = $faq['sort'];
				mysqli_query($con,"INSERT INTO `tbl_faq`(`title`, `content`, `sort_order`) VALUES ('".$title."', '".$content."', $sort)");
			}
		}
	}
	
	/*
	* @get team widget data
	*/
	public function getTeamWidgetData($con) {
		$data = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_home_team_widget order by sort_order ASC");
		while($row = mysqli_fetch_assoc($result)){
			$data[] = $row;
		}
		return $data;
	}
	
	/*
	* @filter repair car
	*/
	public function filterRepairCarInfo($con, $post) {
		$data = array();
		$result = mysqli_query($con,"SELECT *,m.make_name,mo.model_name,y.year_name FROM tbl_add_car c left join tbl_make m on m.make_id = c.car_make left join tbl_model mo on mo.model_id = c.car_model left join tbl_year y on y.year_id = c.year where c.repair_car_id = '".$post['txtInvoiceNo']."'");
		if($row = mysqli_fetch_assoc($result)){
			$data = $row;
		}
		return $data;
	}
	
	/*
	* @filter car by estimate no
	*/
	public function filterRepairCarByEstimateNo($con, $invoice_no) {
		$data = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_car_estimate e WHERE e.estimate_no = '".$invoice_no."'");
		if($row = mysqli_fetch_assoc($result)){
			$data = $row;
		}
		return $data;
	}
	
	/*
	* @buy parts list
	*/
	public function buyPartsList($con) {
		$data = array();
		$result = mysqli_query($con,"SELECT *,s.s_name,mu.manufacturer_name,s.s_name as supplier_name FROM tbl_parts_stock ps LEFT JOIN tbl_add_supplier s ON s.supplier_id = ps.supplier_id LEFT JOIN tbl_manufacturer mu ON mu.manufacturer_id = ps.manufacturer_id ORDER BY ps.parts_name ASC");
		while($row = mysqli_fetch_assoc($result)){
			$data[] = $row;
		}
		return $data;
	}
	
	/*
	* @parts stock list
	*/
	public function partsStockList($con) {
		$data = array();
		$result = mysqli_query($con,"SELECT *,s.s_name,mu.manufacturer_name,s.s_name as supplier_name FROM tbl_parts_stock_manage ps LEFT JOIN tbl_add_supplier s ON s.supplier_id = ps.supplier_id LEFT JOIN tbl_manufacturer mu ON mu.manufacturer_id = ps.manufacturer_id ORDER BY ps.parts_name ASC");
		while($row = mysqli_fetch_assoc($result)){
			$data[] = $row;
		}
		return $data;
	}
	
	/*
	* @parts total qty for dashboard
	*/
	public function partsStockTotalQty($con) {
		$data = 0;
		$result = mysqli_query($con,"SELECT sum(quantity) as total_parts FROM tbl_parts_stock_manage");
		if($row = mysqli_fetch_assoc($result)){
			$data = $row['total_parts'];
		}
		return $data;
	}
	
	/*
	* @get all widget list
	*/
	public function getAllWidgetList($con) {
		$data = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_widgets order by name ASC");
		while($row = mysqli_fetch_assoc($result)){
			$data[] = $row;
		}
		return $data;
	}
	
	/*
	* @get all widget list
	*/
	public function getAllSupplierList($con) {
		$data = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_add_supplier order by s_name ASC");
		while($row = mysqli_fetch_assoc($result)){
			$data[] = $row;
		}
		return $data;
	}
	
	/*
	* @get cms page details by cms_id
	*/
	public function getCMSDetailsByCMSId($con, $cms_id) {
		$data = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_cms where cms_id='".(int)$cms_id."'");
		if($row = mysqli_fetch_assoc($result)){
			$data = $row;
		}
		return $data;
	}
	
	
	/*
	* @get team widget home view data
	*/
	public function getTeamWidgetHomeData($con) {
		$data = array();
		$result = mysqli_query($con,"SELECT *,am.m_name,am.m_image,md.title,mp.seo_url FROM tbl_home_team_widget hw INNER JOIN tbl_add_mechanics am ON am.mechanics_id = hw.team_id INNER JOIN tbl_mechanics_designation md ON md.designation_id = am.designation_id LEFT JOIN tbl_mechanics_page mp ON mp.mechanic_id = hw.team_id order by hw.sort_order ASC");
		while($row = mysqli_fetch_assoc($result)){
			$image = '';
			$link = '';
			if($row['m_image'] != ''){
				$image = WEB_URL . 'img/employee/' . $row['m_image'];
			}
			if(!empty($row['seo_url'])) {
				$link = $row['seo_url'];
			}
			$data[] = array(
				'image'		=> $image,
				'name'		=> $row['m_name'],
				'title'		=> $row['title'],
				'link'		=> $link,
				'status'	=> $row['status']
			);
		}
		return $data;
	}
	
	/*
	* @get all team members
	*/
	public function getAllTeamMembers($con) {
		$data = array();
		$result = mysqli_query($con,"SELECT *,am.m_name,am.m_image,md.title,mp.seo_url FROM tbl_add_mechanics am INNER JOIN tbl_mechanics_designation md ON md.designation_id = am.designation_id LEFT JOIN tbl_mechanics_page mp ON mp.mechanic_id = am.mechanics_id order by am.m_name ASC");
		while($row = mysqli_fetch_assoc($result)){
			$image = '';
			$link = '';
			if($row['m_image'] != ''){
				$image = WEB_URL . 'img/employee/' . $row['m_image'];
			}
			if(!empty($row['seo_url'])) {
				$link = $row['seo_url'];
			}
			$data[] = array(
				'image'		=> $image,
				'name'		=> $row['m_name'],
				'title'		=> $row['title'],
				'link'		=> $link
			);
		}
		return $data;
	}
	
	/***************************** News Author ********************************************/
	/*
	* @get author data
	*/
	public function getAuthorData($con) {
		$data = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_author order by author_name asc");
		while($row = mysqli_fetch_assoc($result)){
			$data[] = $row;
		}
		return $data;
	}
	
	/*
	* @get author data by id
	*/
	public function getAuthorDataByAuthorId($con, $authod_id) {
		$data = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_author where author_id = '".(int)$authod_id."'");
		if($row = mysqli_fetch_assoc($result)){
			$data = $row;
		}
		return $data;
	}
	
	/*
	* @delete author
	*/
	public function deleteAuthor($con, $author_id) {
		mysqli_query($con,"DELETE FROM `tbl_author` WHERE author_id = ".(int)$author_id);
	}
	
	/*
	* @save/update author information
	*/
	public function saveUpdateAuthorInformation($con, $data) {
		if(!empty($data)) {
			if($data['author_id'] == '0') {
				mysqli_query($con,"INSERT INTO tbl_author(author_name) values('$data[author_name]')");
			} else {
				mysqli_query($con,"UPDATE `tbl_author` SET `author_name`='".$data['author_name']."' WHERE author_id='".(int)$data['author_id']."'");
			}
		}
	}
	/***************************** News Category********************************************/
	/*
	* @save/update category information
	*/
	public function saveUpdateCategoryInformation($con, $data) {
		if(!empty($data)) {
			$seo_url = $data['category_seo_url'];
			if(empty($seo_url)) {
				$seo_url = $this->generateSeoUrl($data['category_name']);
			}
			if($data['category_id'] == '0') {
				mysqli_query($con,"INSERT INTO tbl_category(category_name,seo_url) values('$data[category_name]','$seo_url')");
			} else {
				mysqli_query($con,"UPDATE `tbl_category` SET `category_name`='".$data['category_name']."',`seo_url`='".$seo_url."' WHERE category_id='".(int)$data['category_id']."'");
			}
		}
	}
	/*
	* @delete category
	*/
	public function deleteCategory($con, $category_id) {
		mysqli_query($con,"DELETE FROM `tbl_category` WHERE category_id = ".(int)$category_id);
	}
	
	/*
	* @get category data by id
	*/
	public function getCategoryDataByCategoryId($con, $category_id) {
		$data = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_category where category_id = '".(int)$category_id."'");
		if($row = mysqli_fetch_assoc($result)){
			$data = $row;
		}
		return $data;
	}
	
	/*
	* @get category data
	*/
	public function getCategoryData($con) {
		$data = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_category order by category_name ASC");
		while($row = mysqli_fetch_assoc($result)){
			$data[] = $row;
		}
		return $data;
	}
	
	/*
	*	@save apointment information
	*/
	public function saveApointmentRequest($data, $con) {
		if(!empty($data)) {
			$name = mysqli_real_escape_string($con,$data['name']);
			$email = mysqli_real_escape_string($con,$data['email']);
			$telephone = mysqli_real_escape_string($con,$data['phone']);
			$details = mysqli_real_escape_string($con,$data['message']);
			mysqli_query($con,"INSERT INTO tbl_apointment(name, email, telephone, details, added_date) values('$name','$email','$telephone','$details', NOW())");
		}
	}
	
	/*
	*	@save car request information
	*/
	public function saveCarRequestInformation($data, $con) {
		if(!empty($data)) {
			$carid = mysqli_real_escape_string($con,$data['carid']);
			$name = mysqli_real_escape_string($con,$data['name']);
			$email = mysqli_real_escape_string($con,$data['email']);
			$price = mysqli_real_escape_string($con,$data['price']);
			$telephone = mysqli_real_escape_string($con,$data['phone']);
			$details = mysqli_real_escape_string($con,$data['message']);
			mysqli_query($con,"INSERT INTO  tbl_car_request(car_id, name, email, phone, price, details, requested_date) values('$carid','$name','$email','$telephone','$price','$details', NOW())");
		}
	}
	
	/*
	*	@save conatct information
	*/
	public function saveContactInfo($data, $con) {
		if(!empty($data)) {
			$name = mysqli_real_escape_string($con,$data['name']);
			$email = mysqli_real_escape_string($con,$data['email']);
			$subject = mysqli_real_escape_string($con,$data['subject']);
			$message = mysqli_real_escape_string($con,$data['message']);
			mysqli_query($con,"INSERT INTO tbl_contact(name, email, subject, message, added_date) values('$name','$email','$subject','$message', NOW())");
		}
	}
	
	/*
	* @update apointment request status
	*/
	public function setCarRequestStatus($con, $car_request_id) {
		mysqli_query($con,"UPDATE tbl_car_request set status = 1 WHERE car_request_id = ".(int)$car_request_id);
	}
	
	/*
	* @update contact status
	*/
	public function setContactStatus($con, $contact_id) {
		mysqli_query($con,"UPDATE tbl_contact set status = 1 WHERE contact_id = ".(int)$contact_id);
	}
	
	/*
	* @get all apointment list
	*/
	public function setApointmentRequestStatus($con, $callid) {
		mysqli_query($con,"UPDATE tbl_apointment set status = 1 WHERE apointment_id = ".(int)$callid);
	}
	public function get_all_apointment_list($con, $aid) {
		$data = array();
		$result = '';
		if((int)$aid > 0) {
			$result = mysqli_query($con,"SELECT * FROM tbl_apointment WHERE apointment_id = ".(int)$aid);
		} else {
			$result = mysqli_query($con,"SELECT * FROM tbl_apointment order by apointment_id DESC");
		}
		while($row = mysqli_fetch_array($result)){
			$data[] = $row;
		}
		return $data;
	}
	
	/*
	* @get all car requested list
	*/
	public function get_all_car_request_list($con, $rid) {
		$data = array();
		$result = '';
		if((int)$rid > 0) {
			$result = mysqli_query($con,"SELECT *,car_name,car_image FROM tbl_car_request cr INNER JOIN tbl_buycar bc ON bc.buycar_id = cr.car_id WHERE car_request_id = ".(int)$rid);
		} else {
			$result = mysqli_query($con,"SELECT *,car_name,car_image FROM tbl_car_request cr INNER JOIN tbl_buycar bc ON bc.buycar_id = cr.car_id order by car_request_id DESC");
		}
		while($row = mysqli_fetch_array($result)){
			$data[] = $row;
		}
		return $data;
	}
	
	/*
	* @get all contact list
	*/
	public function get_all_contact_list($con, $cid) {
		$data = array();
		$result = '';
		if((int)$cid > 0) {
			$result = mysqli_query($con,"SELECT * FROM tbl_contact WHERE contact_id = ".(int)$cid);
		} else {
			$result = mysqli_query($con,"SELECT * FROM tbl_contact order by contact_id DESC");
		}
		while($row = mysqli_fetch_array($result)){
			$data[] = $row;
		}
		return $data;
	}
	
	/*
	* @get all waiting apointment list
	*/
	public function get_all_waiting_apointment_list($con) {
		$data = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_apointment WHERE status = 0 order by apointment_id DESC");
		while($row = mysqli_fetch_array($result)){
			$data[] = $row;
		}
		return $data;
	}
	
	/*
	* @get all waiting car request list
	*/
	public function get_all_waiting_car_list($con) {
		$data = array();
		$result = mysqli_query($con,"SELECT *,car_name,car_image FROM tbl_car_request cr INNER JOIN tbl_buycar bc ON bc.buycar_id = cr.car_id WHERE cr.status = 0 order by cr.car_request_id DESC");
		while($row = mysqli_fetch_array($result)){
			$data[] = $row;
		}
		return $data;
	}
	
	/*
	* @get all waiting contact us list
	*/
	public function get_all_contact_us_list($con) {
		$data = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_contact WHERE status = 0 order by contact_id DESC");
		while($row = mysqli_fetch_array($result)){
			$data[] = $row;
		}
		return $data;
	}
	
	/***********************************News*****************************************/
	/*
	* @News save/update
	*/
	public function saveUpdateNewsInformation($con, $data, $image_url, $image_url_2) {
		if(!empty($data)) {
			$allow_comments = 0;
			$show_home = 0;
			$seo_url = '';
			if(isset($data['allow_comment']) && $data['allow_comment'] == 'on') {
				$allow_comments = 1;
			}
			if(isset($data['show_home']) && $data['show_home'] == 'on') {
				$show_home = 1;
			}
			if(!empty($data['blog_seo_url'])) {
				$seo_url = $data['blog_seo_url'];
			} else {
				$seo_url = $this->generateSeoUrl($data['blog_title']);
			}
			
			$desc = mysqli_real_escape_string($con,$data['blog_details']);
			$sdesc = mysqli_real_escape_string($con,$data['blog_short_details']);
			$date = $this->datepickerDateToMySqlDate($data['blog_date']);
			if($data['blog_id'] == '0') {
				mysqli_query($con,"INSERT INTO tbl_blog(blog_cat,blog_title,seo_url,blog_author,blog_details,short_desc,blog_image,thumb_image,blog_status,allow_comment,show_home,blog_date_time,blog_time) values('$data[blog_cat]','$data[blog_title]','$seo_url','$data[blog_author]','$desc','$sdesc','$image_url','$image_url_2','$data[blog_status]','$allow_comments','$show_home','$date','$data[blog_time]')");
			} else {
				mysqli_query($con,"UPDATE `tbl_blog` SET `blog_cat`='".$data['blog_cat']."',`blog_title`='".$data['blog_title']."',`seo_url`='".$seo_url."',`blog_author`='".$data['blog_author']."',`allow_comment`='".$allow_comments."',`show_home`='".$show_home."',`blog_details`='".$desc."',`short_desc`='".$sdesc."',`blog_status`='".$data['blog_status']."',`blog_image`='".$image_url."',`thumb_image`='".$image_url_2."',`blog_date_time`='".$date."',`blog_time`='".$data['blog_time']."' WHERE blog_id='".$data['blog_id']."'");
			}
		}
	}
	
	/*
	* @get news list
	*/
	public function getNewsData($con) {
		$data = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_blog order by blog_title ASC");
		while($row = mysqli_fetch_assoc($result)){
			$data[] = $row;
		}
		return $data;
	}
	
	/*
	* @get news category and count
	*/
	public function getNewsCategoryAndCount($con) {
		$data = array();
		$result = mysqli_query($con,"SELECT *,(select count(blog_id) as total from tbl_blog where tbl_blog.blog_cat = tbl_category.category_id) as total FROM tbl_category order by category_name ASC");
		while($row = mysqli_fetch_assoc($result)){
			$data[] = $row;
		}
		return $data;
	}
	
	/*
	* @filter parts based on make model year
	*/
	/*public function filterPartsBasedOnMakeModelYear($con, $filter) {
		$data = array();
		$sql = '';
		if(!empty($filter['makeid']) && !empty($filter['modelid']) && !empty($filter['yearid'])) {
			$sql = mysqli_query($con,'SELECT * FROM tbl_parts_fit_data pfd INNER JOIN tbl_parts_stock_manage psm ON psm.parts_id = pfd.parts_id WHERE pfd.make_id = "'.(int)$filter['makeid'].'" AND pfd.model_id = "'.(int)$filter['modelid'].'" AND pfd.year_id = "'.(int)$filter['yearid'].'"');
		} else if(!empty($filter['makeid']) && !empty($filter['modelid'])) {
			$sql = mysqli_query($con,'SELECT * FROM tbl_parts_fit_data pfd INNER JOIN tbl_parts_stock_manage psm ON psm.parts_id = pfd.parts_id WHERE pfd.make_id = "'.(int)$filter['makeid'].'" AND pfd.model_id = "'.(int)$filter['modelid'].'"');
		} else if(!empty($filter['makeid'])) {
			$sql = mysqli_query($con,'SELECT * FROM tbl_parts_fit_data pfd INNER JOIN tbl_parts_stock_manage psm ON psm.parts_id = pfd.parts_id WHERE pfd.make_id = "'.(int)$filter['makeid'].'"');
		}
		if(!empty($sql)) {
			while($row = mysqli_fetch_assoc($sql)){
				$data[] = $row;
			}
		}
		return $data;
	}*/
	
	/*
	* @get news info by id
	*/
	public function getNewsDataByNewsId($con, $newsid) {
		$data = array();
		$result = mysqli_query($con,"SELECT *,c.category_id,b.seo_url as blog_seo_url,c.seo_url as category_seo_url FROM tbl_blog b LEFT JOIN tbl_category c ON c.category_id = b.blog_cat where b.blog_id = '" .(int)$newsid. "'");
		if($row = mysqli_fetch_assoc($result)){
			$data = $row;
		}
		return $data;
	}
	
	/*
	* @get news list for home page
	*/
	public function getNewsDataForHomePage($con) {
		$data = array();
		$result = mysqli_query($con,"SELECT *,c.category_name,b.seo_url as blog_seo_url,c.seo_url as category_seo_url,(select count(blog_id) from tbl_blog_comments where blog_id = b.blog_id) as total_comments FROM tbl_blog b left join tbl_category c on c.category_id = b.blog_cat where b.show_home = 1 order by b.blog_title ASC");
		while($row = mysqli_fetch_assoc($result)){
			$image_thumb = '';
			if($row['thumb_image'] != ''){
				$image_thumb = WEB_URL . 'img/blog/' . $row['thumb_image'];
			}
			$image_main = '';
			if($row['blog_image'] != ''){
				$image_main = WEB_URL . 'img/blog/' . $row['blog_image'];
			}
			$phpdate = strtotime( $row['blog_date_time'] );
			$mysqldate = date( 'F d, Y', $phpdate );
			
			$data[] = array(
				'blog_title'		=> $row['blog_title'],
				'seo_url'			=> $row['blog_seo_url'],
				'category'			=> $row['category_seo_url'],
				'blog_cat'			=> $row['blog_cat'],
				'blog_author'		=> $row['blog_author'],
				'blog_details'		=> $row['blog_details'],
				'short_desc'		=> $row['short_desc'],
				'blog_image'		=> $image_main,
				'thumb_image'		=> $image_thumb,
				'blog_status'		=> $row['blog_status'],
				'allow_comment'		=> $row['allow_comment'],
				'show_home'			=> $row['show_home'],
				'comments'			=> $row['total_comments'],
				'blog_date_time'	=> $mysqldate,
			);
		}
		return $data;
	}
	
	/*
	* @get news list by category
	*/
	public function getNewsByCategory($con, $seo_key) {
		$data = array();
		$result = mysqli_query($con,"SELECT *,c.category_name,b.seo_url as blog_seo_url,c.seo_url as category_seo_url,(select count(blog_id) from tbl_blog_comments where blog_id = b.blog_id) as total_comments FROM tbl_blog b inner join tbl_category c on c.category_id = b.blog_cat WHERE c.seo_url = '".$seo_key."' order by b.blog_title ASC");
		while($row = mysqli_fetch_assoc($result)){
			$image_thumb = '';
			if($row['thumb_image'] != ''){
				$image_thumb = WEB_URL . 'img/blog/' . $row['thumb_image'];
			}
			$image_main = '';
			if($row['blog_image'] != ''){
				$image_main = WEB_URL . 'img/blog/' . $row['blog_image'];
			}
			$phpdate = strtotime( $row['blog_date_time'] );
			$mysqldate = date( 'F d, Y', $phpdate );
			if(empty($data['category'])) {
				$data['category']	= $row['category_name'];
			}
			$data['blogs'][] = array(
				'blog_title'		=> $row['blog_title'],
				'category_name'		=> $row['category_name'],
				'seo_url'			=> $row['blog_seo_url'],
				'comments'			=> $row['total_comments'],
				'category'			=> $row['category_seo_url'],
				'blog_cat'			=> $row['blog_cat'],
				'blog_author'		=> $row['blog_author'],
				'blog_details'		=> $row['blog_details'],
				'short_desc'		=> $row['short_desc'],
				'blog_image'		=> $image_main,
				'thumb_image'		=> $image_thumb,
				'blog_status'		=> $row['blog_status'],
				'allow_comment'		=> $row['allow_comment'],
				'show_home'			=> $row['show_home'],
				'blog_date_time'	=> $mysqldate,
			);
		}
		return $data;
	}
	
	/*
	* @get all news collection
	*/
	public function getAllNewsCollections($con) {
		$data = array();
		$result = mysqli_query($con,"SELECT *,c.category_name,b.seo_url as blog_seo_url,c.seo_url as category_seo_url,(select count(blog_id) from tbl_blog_comments where blog_id = b.blog_id) as total_comments FROM tbl_blog b inner join tbl_category c on c.category_id = b.blog_cat order by b.blog_title ASC");
		while($row = mysqli_fetch_assoc($result)){
			$image_thumb = '';
			if($row['thumb_image'] != ''){
				$image_thumb = WEB_URL . 'img/blog/' . $row['thumb_image'];
			}
			$image_main = '';
			if($row['blog_image'] != ''){
				$image_main = WEB_URL . 'img/blog/' . $row['blog_image'];
			}
			$phpdate = strtotime( $row['blog_date_time'] );
			$mysqldate = date( 'F d, Y', $phpdate );
			if(empty($data['category'])) {
				$data['category']	= $row['category_name'];
			}
			$data['blogs'][] = array(
				'blog_title'		=> $row['blog_title'],
				'category_name'		=> $row['category_name'],
				'seo_url'			=> $row['blog_seo_url'],
				'comments'			=> $row['total_comments'],
				'category'			=> $row['category_seo_url'],
				'blog_cat'			=> $row['blog_cat'],
				'blog_author'		=> $row['blog_author'],
				'blog_details'		=> $row['blog_details'],
				'short_desc'		=> $row['short_desc'],
				'blog_image'		=> $image_main,
				'thumb_image'		=> $image_thumb,
				'blog_status'		=> $row['blog_status'],
				'allow_comment'		=> $row['allow_comment'],
				'show_home'			=> $row['show_home'],
				'blog_date_time'	=> $mysqldate,
			);
		}
		return $data;
	}
	
	/*
	* @get 8 latest news list
	*/
	public function getFiveLatestNews($con) {
		$data = array();
		$result = mysqli_query($con,"SELECT *,c.category_name,b.seo_url as blog_seo_url,c.seo_url as category_seo_url FROM tbl_blog b left join tbl_category c on c.category_id = b.blog_cat order by blog_id DESC Limit 5");
		while($row = mysqli_fetch_assoc($result)){
			$image_thumb = '';
			if($row['thumb_image'] != ''){
				$image_thumb = WEB_URL . 'img/blog/' . $row['thumb_image'];
			}
			$image_main = '';
			if($row['blog_image'] != ''){
				$image_main = WEB_URL . 'img/blog/' . $row['blog_image'];
			}
			$phpdate = strtotime( $row['blog_date_time'] );
			$mysqldate = date( 'F d, Y', $phpdate );
			
			$data[] = array(
				'blog_title'		=> $row['blog_title'],
				'category_name'		=> $row['category_name'],
				'seo_url'			=> $row['blog_seo_url'],
				'category'			=> $row['category_seo_url'],
				'blog_cat'			=> $row['blog_cat'],
				'blog_author'		=> $row['blog_author'],
				'blog_details'		=> $row['blog_details'],
				'short_desc'		=> $row['short_desc'],
				'blog_image'		=> $image_main,
				'thumb_image'		=> $image_thumb,
				'blog_status'		=> $row['blog_status'],
				'allow_comment'		=> $row['allow_comment'],
				'show_home'			=> $row['show_home'],
				'blog_time'			=> $row['blog_time'],
				'blog_date_time'	=> $mysqldate,
			);
		}
		return $data;
	}
	
	/*
	* @get news list for home page
	*/
	public function singlePageBlogDetailsBySeoUrl($con, $seo_url) {
		$data = array();
		$result = mysqli_query($con,"SELECT *,c.category_name,b.seo_url as blog_seo_url,c.seo_url as category_seo_url FROM tbl_blog b left join tbl_category c on c.category_id = b.blog_cat where b.seo_url = '$seo_url' order by b.blog_title ASC");
		if($row = mysqli_fetch_assoc($result)){
			$image_thumb = '';
			if($row['thumb_image'] != ''){
				$image_thumb = WEB_URL . 'img/blog/' . $row['thumb_image'];
			}
			$image_main = '';
			if($row['blog_image'] != ''){
				$image_main = WEB_URL . 'img/blog/' . $row['blog_image'];
			}
			$phpdate = strtotime( $row['blog_date_time'] );
			$mysqldate = date( 'F d, Y', $phpdate );
			
			$data = array(
				'blog_id'			=> $row['blog_id'],
				'blog_title'		=> $row['blog_title'],
				'seo_url'			=> $row['blog_seo_url'],
				'category'			=> $row['category_seo_url'],
				'blog_cat'			=> $row['blog_cat'],
				'blog_author'		=> $row['blog_author'],
				'blog_details'		=> $row['blog_details'],
				'short_desc'		=> $row['short_desc'],
				'blog_image'		=> $image_main,
				'thumb_image'		=> $image_thumb,
				'blog_status'		=> $row['blog_status'],
				'allow_comment'		=> $row['allow_comment'],
				'show_home'			=> $row['show_home'],
				'blog_date_time'	=> $mysqldate,
				'blog_day'			=> date( 'd', $phpdate ),
				'blog_month'		=> date( 'F', $phpdate ),
				'blog_year'			=> date( 'Y', $phpdate )
			);
		}
		return $data;
	}
	
	/*
	* @delete blog
	*/
	public function deleteNews($con, $newsid) {
		mysqli_query($con,"DELETE FROM `tbl_blog` WHERE blog_id = ".(int)$newsid);
	}
	
	/*
	* @delete apointment request
	*/
	public function deleteApointmentRequest($con, $apointment_id) {
		mysqli_query($con,"DELETE FROM `tbl_apointment` WHERE apointment_id = ".(int)$apointment_id);
	}
	
	/*
	* @delete car request
	*/
	public function deleteCarRequest($con, $car_request_id) {
		mysqli_query($con,"DELETE FROM `tbl_car_request` WHERE car_request_id = ".(int)$car_request_id);
	}
	
	/*
	* @delete contact us data
	*/
	public function deleteContactRequest($con, $contact_id) {
		mysqli_query($con,"DELETE FROM `tbl_contact` WHERE contact_id = ".(int)$contact_id);
	}
	
	/*
	* @save or update car color
	*/
	public function saveUpdateCarColor($con, $data) {
		if(!empty($data)) {
			if((int)$data['submit_token'] > 0) {
				mysqli_query($con,"UPDATE tbl_carcolor SET color_name = '".trim($data['txtColorname'])."' WHERE color_id = ".(int)$data['submit_token']);
			} else {
				mysqli_query($con,"INSERT INTO tbl_carcolor(color_name) values('$data[txtColorname]')");
			}
		}
	}
	
	/*
	* @save or update car color
	*/
	public function saveUpdateCarDoor($con, $data) {
		if(!empty($data)) {
			if((int)$data['submit_token'] > 0) {
				mysqli_query($con,"UPDATE tbl_cardoor SET door_name = '".$data['txtDoor']."' WHERE door_id = ".(int)$data['submit_token']);
			} else {
				mysqli_query($con,"INSERT INTO tbl_cardoor(door_name) values('".$data['txtDoor']."')");
			}
		}
	}

	public function get_car_door_by_name($con, $door_name){
		$make = array();
		$result = mysqli_query($con,"SELECT * FROM `tbl_cardoor` WHERE door_name = '".$door_name."'");
		while($row = mysqli_fetch_array($result)){
			$make[] = array(
				'door_id'		=> $row['door_id'],
				'door_name'		=> $row['door_name']
			);
		}
		return $make;
	}
	
	/*
	* @save or update make settings
	*/
	public function saveUpdateMakeSetup($con, $data) {
		if(!empty($data)) {
			if((int)$data['submit_token'] > 0) {
				mysqli_query($con,"UPDATE tbl_make SET make_name = '".trim($data['txtMakeName'])."' WHERE make_id = ".(int)$data['submit_token']);
			} else {
				mysqli_query($con,"INSERT INTO tbl_make(make_name) values('$data[txtMakeName]')");
			}
		}
	}
	
	/*
	* @save or update model settings
	*/
	public function saveUpdateModelSetup($con, $data) {
		if(!empty($data)) {
			if((int)$data['submit_token'] > 0) {
				mysqli_query($con,"UPDATE tbl_model SET make_id = '".$data['ddlMake']."', model_name = '".trim($data['txtModelName'])."' WHERE model_id = ".(int)$data['submit_token']);
			} else {
				mysqli_query($con,"INSERT INTO tbl_model(make_id, model_name) values('$data[ddlMake]', '$data[txtModelName]')");
			}
		}
	}
	
	/*
	* @save or update year settings
	*/
	public function saveUpdateYearSetup($con, $data) {
		if(!empty($data)) {
			if((int)$data['submit_token'] > 0) {
				mysqli_query($con,"UPDATE tbl_year SET make_id = '".$data['ddlMake']."',model_id = '".$data['ddlModel']."', year_name = '".trim($data['txtYear'])."' WHERE year_id = ".(int)$data['submit_token']);
			} else {
				mysqli_query($con,"INSERT INTO tbl_year(make_id, model_id, year_name) values('$data[ddlMake]', '$data[ddlModel]', '$data[txtYear]')");
			}
		}
	}
	
	/*
	* @get car make data by make id
	*/
	public function getCarMakeDataByMakeId($con, $make_id) {
		$data = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_make where make_id = '" . (int)$make_id . "'");
		if($row = mysqli_fetch_array($result)){
			$data = $row;
		}
		return $data;
	}
	
	/*
	* @get car model data by model id
	*/
	public function getCarModelDataByModelId($con, $model_id) {
		$data = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_model where model_id = '" . (int)$model_id . "'");
		if($row = mysqli_fetch_array($result)){
			$data = $row;
		}
		return $data;
	}
	
	/*
	* @get car year data by year id
	*/
	public function getCarYearDataByYearId($con, $year_id) {
		$data = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_year where year_id = '" .(int)$year_id . "'");
		if($row = mysqli_fetch_array($result)){
			$data = $row;
		}
		return $data;
	}
	
	/*
	* @get car color info by color id
	*/
	public function getCarColorDataByColorId($con, $color_id) {
		$data = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_carcolor where color_id = '" . (int)$color_id . "'");
		if($row = mysqli_fetch_array($result)){
			$data = $row;
		}
		return $data;
	}
	
	/*
	* @get car door info by door id
	*/
	public function getCarDoorDataByDoorId($con, $door_id) {
		$data = array();
		$result = mysqli_query($con,"SELECT * FROM tbl_cardoor where door_id = '" .(int)$door_id. "'");
		if($row = mysqli_fetch_array($result)){
			$data = $row;
		}
		return $data;
	}
	
	/*
	* @fit html build
	*/
	public function getmakeHtml($makeid,$row,$con){
		$make_html = "<select class='form-control' id=make_" . $row . " onchange='loadModelDatax(this,".$row.");' name='partsfilter[".$row."][make]'><option value=''>--Select Make--</option>";
		$rows = $this->get_all_make_list($con);
		foreach($rows as $row){
			if($row['make_id'] == $makeid){
				$make_html .= "<option selected='selected' value='".$row['make_id']."'>".$row['make_name']."</option>";
			}
			else{
				$make_html .= "<option value='".$row['make_id']."'>".$row['make_name']."</option>";
			}
		}
		return $make_html . "</select>";
	}
	public function getmodelHtml($makeid,$modelid,$row,$con){
		$model_html = "<select class='form-control' onchange='loadYearData(this,".$row.");' name='partsfilter[".$row."][model]' id='model_".$row."'><option value=''>--Select Model--</option>";
		$rows = $this->getModelListByMakeId($con, $makeid);
		foreach($rows as $row){
			if($row['model_id'] == $modelid){
				$model_html .= "<option selected='selected' value='".$row['model_id']."'>".$row['model_name']."</option>";
			}
			else{
				$model_html .= "<option value='".$row['model_id']."'>".$row['model_name']."</option>";
			}	
		}
		return $model_html . "</select>";
	}
	public function getyearHtml($makeid,$modelid,$yearid,$row,$con){
		$year_html = "<select class='form-control' name='partsfilter[".$row."][year]' id='year_".$row."'><option value=''>--Select Year--</option>";
		$rows = $this->getYearlListByMakeIdAndModelId($con, $makeid, $modelid);
		foreach($rows as $row){
			if($row['year_id'] == $yearid){
				$year_html .= "<option selected='selected' value='".$row['year_id']."'>".$row['year_name']."</option>";
			}
			else{
				$year_html .= "<option value='".$row['year_id']."'>".$row['year_name']."</option>";
			}	
		}
		return $year_html . "</select>";
	}	
	
	
	/*
	* @load email template
	*/
	public function loadEmailTemplate($temp_name, $variables = array()) {
		$template = file_get_contents(ROOT_PATH."email_templates/".$temp_name);
		foreach($variables as $key => $value){
			$template = str_replace('{{ '.$key.' }}', $value, $template);
		}
		return $template;
	}
	
	/*public function loadEmailTemplate($temp_name, $email_variables = array()) {
		$url = '../email_templates/'.$temp_name;
		$data = http_build_query($email_variables);
		// use key 'http' even if you send the request to https://...
		$options = array('http' => array(
			'method'  => 'POST',
			'content' => $data
		));
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		return $result;
	}*/
	
	/*
	* @seo url generate
	*/
	public function generateSeoUrl($text) {
		$title=trim($text);
		$title=htmlentities($title);
		$newtitle=$this->string_limit_words($title, 6);
		$urltitle=preg_replace('/[^a-z0-9]/i',' ', $newtitle);
		$newurltitle=str_replace(" ","-",$newtitle);
		$url=$newurltitle.'.html';
		$url=strtolower($newurltitle);
		return $url;
	}
	public function string_limit_words($string, $word_limit) {
	   $words = explode(' ', $string);
	   return implode(' ', array_slice($words, 0, $word_limit));
	}
	
	/*
	* @get mechanics page info by id
	*/
	public function getSeoDetailsById($con, $seo_key, $table) {
		$data = array();
		$result = mysqli_query($con,"SELECT * FROM ".$table." where seo_url = '" .trim($seo_key). "'");
		if($row = mysqli_fetch_assoc($result)){
			$data = $row;
		}
		return $data;
	}
	
	/*
	* @execute multiple rows query
	*/
	public function deleteWorkProcessImage($image_dir_url) {
		if (file_exists($image_dir_url)) {
    		unlink($image_dir_url);
		}
	}
	
	/*
	* @execute multiple rows query
	*/
	public function getMultipleRowData($con, $query) {
		$data = array();
		$result = mysqli_query($con,$query);
		while($row = mysqli_fetch_array($result)){
			$data[] = $row;
		}
		return $data;
	}
	
	/*
	* @datepicker date conversion //helper
	*/
	public function datepickerDateToMySqlDate($date) {
		$cdate = '0000-00-00';
		if(!empty($date)) {
			$x =  explode('/',$date);
			$cdate = $x[2].'-'.$x[1].'-'.$x[0];
		}
		return $cdate;
	}
	public function mySqlToDatePicker($date) {
		$cdate = '';
		if(!empty($date)) {
			$x =  explode('-',$date);
			$cdate = $x[2].'/'.$x[1].'/'.$x[0];
		}
		return $cdate;
	}
	
	/*
	* @ month color code for chart
	*/
	public function getMonthValueToMonthName($monthNum) {
		return date('F', mktime(0, 0, 0, $monthNum, 10));
	}
	public function getChartColorCodeByMonth($month) {
		$cc = '';
		if(!empty($month)){
			switch ($month) {
				case 'January':
					$cc = '#ff6384';
					break;
				case 'February':
					$cc = '#ff9f40';
					break;
				case 'March':
					$cc = '#ffcd56';
					break;
				case 'April':
					$cc = '#4bc0c0';
					break;
				case 'May':
					$cc = '#36a2eb';
					break;
				case 'June':
					$cc = '#9966ff';
					break;
				case 'July':
					$cc = '#c9cbcf';
					break;
				case 'Auguest':
					$cc = '#00a65a';
					break;
				case 'September':
					$cc = '#0aff8c';
					break;
				case 'October':
					$cc = '#fc0202';
					break;
				case 'November':
					$cc = '#eef213';
					break;
				case 'December':
					$cc = '#ff851b';
					break;
				default:
					$cc = '#4bc0c0';
			} 
		}
		return $cc;
	}
	
	/*
	* @login process
	*/
	//login sql prevention improved
	public function login_operation($con, $data){
   		
		$obj_login = array();
		$user_name = $this->make_safe($con, $data['username']); //Escaping Strings
		$password = $this->make_safe($con, $data['password']); //Escaping Strings
		if($data['ddlLoginType'] == 'admin'){
			$stmt = $con->prepare("SELECT * FROM tbl_admin WHERE email = ? and password = ?");
			$stmt->bind_param('ss',$user_name, $password);
			$stmt->execute();
			$resultSet = $stmt->get_result();
			if(!empty($resultSet) && (int)$resultSet->num_rows > 0){
				$row = $resultSet->fetch_array(); //fetch_all -> get all - fetch_array --> single row
				$obj_login = array(
					'user_id'		=> $row['user_id'],
					'name'			=> $row['name'],
					'email'			=> $row['email'],
					'password'		=> $row['password'],
					'image'			=> $row['image']
				);
			}
		} else if($data['ddlLoginType'] == 'workshop_admin'){
			$stmt = $con->prepare("SELECT * FROM tbl_workshop_admin WHERE w_email = ? and w_password = ?");
			$stmt->bind_param('ss',$user_name, $password);
			$stmt->execute();
			$resultSet = $stmt->get_result();
			if(!empty($resultSet) && (int)$resultSet->num_rows > 0){
				$row = $resultSet->fetch_array(); //fetch_all -> get all - fetch_array --> single row
				$obj_login = array(
					'user_id'		=> $row['workshop_admin_id'],
					'name'			=> $row['w_name'],
					'email'			=> $row['w_email'],
					'password'		=> $row['w_password'],
					'image'			=> $row['w_image']
				);
			}
		} else if($data['ddlLoginType'] == 'customer'){
			$stmt= $con->prepare("SELECT * FROM tbl_add_customer WHERE c_email = ? and c_password = ?");
			$stmt->bind_param('ss',$user_name, $password);
			$stmt->execute();
			$resultSet = $stmt->get_result();
			if(!empty($resultSet) && (int)$resultSet->num_rows > 0){
				$row = $resultSet->fetch_array();
				$obj_login = array(
					'user_id'		=> $row['customer_id'],
					'name'			=> $row['c_name'],
					'email'			=> $row['c_email'],
					'password'		=> $row['c_password'],
					'image'			=> $row['image']
				);
			}
		} else if($_POST['ddlLoginType'] == 'mechanics'){
			$stmt= $con->prepare("SELECT * FROM tbl_add_mechanics WHERE m_email = ? and m_password = ?");
			$stmt->bind_param('ss',$user_name, $password);
			$stmt->execute();
			$resultSet = $stmt->get_result();
			if(!empty($resultSet) && (int)$resultSet->num_rows > 0){
				$row = $resultSet->fetch_array();
				$obj_login = array(
					'user_id'		=> $row['mechanics_id'],
					'name'			=> $row['m_name'],
					'email'			=> $row['m_email'],
					'password'		=> $row['m_password'],
					'image'			=> $row['m_image']
				);
			}
		}
		return $obj_login;
	}
	
	/*
	* @login process
	*/
	public function forgot_operation($con, $data){
   		$obj_login = array();
		if($data['ddlLoginType'] == 'admin'){
			$sql= mysqli_query($con,"SELECT * FROM tbl_admin WHERE email = '".$this->make_safe($con, $data['username'])."'");
			if($row = mysqli_fetch_assoc($sql)){
				$obj_login = array(
					'user_id'		=> $row['user_id'],
					'name'			=> $row['name'],
					'email'			=> $row['email'],
					'password'		=> $row['password'],
					'image'			=> $row['image']
				);
			}
		}else if($data['ddlLoginType'] == 'workshop_admin'){
			$sql= mysqli_query($con,"SELECT * FROM tbl_workshop_admin WHERE email = '".$this->make_safe($con, $data['username'])."'");
			if($row = mysqli_fetch_assoc($sql)){
				$obj_login = array(
					'user_id'		=> $row['workshop_admin_id'],
					'name'			=> $row['w_name'],
					'email'			=> $row['w_email'],
					'password'		=> $row['w_password'],
					'image'			=> $row['w_image']
				);
			}
		} else if($data['ddlLoginType'] == 'customer'){
			$sql= mysqli_query($con,"SELECT * FROM tbl_add_customer WHERE c_email = '".$this->make_safe($con, $data['username'])."'");
			if($row = mysqli_fetch_assoc($sql)){
				$obj_login = array(
					'user_id'		=> $row['customer_id'],
					'name'			=> $row['c_name'],
					'email'			=> $row['c_email'],
					'password'		=> $row['c_password'],
					'image'			=> $row['image']
				);
			}
		} else if($_POST['ddlLoginType'] == 'mechanics'){
			$sql= mysqli_query($con,"SELECT * FROM tbl_add_mechanics WHERE m_email = '".$this->make_safe($con, $data['username'])."'");
			if($row = mysqli_fetch_assoc($sql)){
				$obj_login = array(
					'user_id'		=> $row['mechanics_id'],
					'name'			=> $row['m_name'],
					'email'			=> $row['m_email'],
					'password'		=> $row['m_password'],
					'image'			=> $row['m_image']
				);
			}
		}
		return $obj_login;
	}
	
	/*
	* @customer email exist checking
	*/
	public function checkCustomerEmailAddress($con, $email, $data) {
		if((int)$data['customer_id'] > 0){
			return false;
		} else {
			$result = mysqli_query($con,"SELECT * FROM tbl_add_customer WHERE c_email='".trim($email)."'");
			if($row = mysqli_fetch_assoc($result)){
				return true;
			}
			return false;
		}
		exit();
	}
	
	/*
	* @mechanics email exist checking
	*/
	public function checkMechanicsEmailAddress($con, $email, $data) {
		if((int)$data['mechanics_id'] > 0){
			return false;
		} else {
			$result = mysqli_query($con,"SELECT * FROM tbl_add_mechanics WHERE m_email='".trim($email)."'");
			if($row = mysqli_fetch_assoc($result)){
				return true;
				exit();
			}
			return false;
		}
	}
	
	/*
	* @supplier email exist checking
	*/
	public function checkSupplierEmailAddress($con, $email, $data) {
		if((int)$data['supplier_id'] > 0){
			return false;
		} else {
			$result = mysqli_query($con,"SELECT * FROM tbl_add_supplier WHERE s_email='".trim($email)."'");
			if($row = mysqli_fetch_assoc($result)){
				return true;
				exit();
			}
			return false;
		}
	}
	
	/*
	* @input string filter
	*/
	public function make_safe($con, $variable){
   		$variable = mysqli_real_escape_string($con, strip_tags(trim($variable)));
   		return $variable; 
	}
	
	/*
	* @distroy all connection
	*/
	public function close_db_connection($con){
   		mysqli_close($con);
		$con = NULL;
	}
	
	/*
	* getlastBuyCar
	*/
	public function getLastBuyCar($con)
	{
		$lastBuyCar = array();
		$result = mysqli_query($con,"select buycar_id from tbl_buycar order by buycar_id desc LIMIT 1;");
		
		while($row = mysqli_fetch_array($result)){
			$lastBuyCar[] = array(
				'buycar_id'	=> $row['buycar_id'],
			);
		}

		return $lastBuyCar;
	}

	/*
	* @mailchimp
	*/
	public function sendMailChimp($data, $con){
		$settings = $this->getWebsiteSettingsInformation($con);
		if(!empty($settings) && !empty($settings['mc_api_key']) && !empty($settings['mc_list_id'])) {
			require_once(ROOT_PATH . 'library/MCAPI.class.php');
			$API_KEY = $settings['mc_api_key'];
			$LIST_ID = $settings['mc_list_id'];
			if(!$data['email']){ return "No email address provided"; } 
			if(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*$/i", $data['email'])) { 
				return "Email address is invalid"; 
			}
			$api = new MCAPI($API_KEY);
			$mergeVars = array('FNAME'=>'','LNAME'=>'');
			if($api->listSubscribe($LIST_ID, $data['email'], $mergeVars) === true) {
				return -99;
			}
			else { return 'Error: ' . $api->errorMessage; }	
		} else { return 'Please set your API key and LIST ID'; }	
	}
	
	
	/***END HERE */
	
	
	/*
	* @get page name
	*/
	
	/*public function getPageName() {
		if(basename($_SERVER['PHP_SELF']) == 'index.php') {
			return 'index';
		} else if(basename($_SERVER['PHP_SELF']) == 'news-latest-collection.php') {
			return str_replace('.php','',basename($_SERVER['PHP_SELF']));
		} else if(basename($_SERVER['PHP_SELF']) == 'cms.php') {
			return pathinfo($this->curPageURL(),PATHINFO_FILENAME);
		} else {
			return str_replace('.php','',basename($_SERVER['PHP_SELF']));
		}
	}
	public function curPageURL() {
	 $pageURL = 'http';
	 if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	 $pageURL .= "://";
	 if ($_SERVER["SERVER_PORT"] != "80") {
	  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	 } else {
	  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	 }
	 return $pageURL;
	}*/
}

?>