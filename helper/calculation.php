<?php

class wms_calculation {
	public function getResultFromTwoValues($val_1, $val_2, $token) {
		$total = 0;
		if(!empty($val_1) && is_numeric($val_1) && !empty($val_2) && is_numeric($val_2)) {
			if($token == '-') {
				$total = (float)$val_1 - (float)$val_2;
				$total = number_format($total, 2, '.', ''); 
			}
			else {
				$total = (float)$val_1 + (float)$val_2;
				$total = number_format($total, 2, '.', '');
			}
		}
		
		return $total;
	}
}

?>