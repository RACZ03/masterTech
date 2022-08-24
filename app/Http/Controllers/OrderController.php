<?php
 
namespace App\Http\Controllers;
 
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
 
class OrderController extends Controller
{
    /**
     * Show the profile for a given user.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function getRepairCarAllEstimateData($car_id, $customer_id)
    {
        $data = array();
		if(!empty($car_id)) {
            $sql = "SELECT * FROM tbl_car_estimate WHERE car_id = '".(int)$car_id."' AND customer_id = '".(int)$customer_id."' ORDER BY estimate_id DESC";
            $data = DB::select($sql);
		}
		return json_encode($data);
    }
}