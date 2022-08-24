<?php
$ddl_e_parts = $_POST['ddl_e_parts'];
$parts_names = $_POST['parts_names'];
$ddl_supplier = $_POST['ddl_supplier'];
$ddl_load_manufracturer = $_POST['ddl_load_manufracturer'];
$txtCondition = $_POST['txtCondition'];
$buy_prie = $_POST['buy_prie'];
$parts_quantity = $_POST['parts_quantity'];
$parts_sell_price = $_POST['parts_sell_price'];
$parts_sku = $_POST['parts_sku'];
$parts_add_date = $_POST['parts_add_date'];
$parts_warranty = $_POST['parts_warranty'];
$total_amount = $_POST['total_amount'];
$given_amount = $_POST['given_amount'];
$pending_amount = $_POST['pending_amount'];
$img_exist = $_POST['img_exist'];
$old_qty = $_POST['old_qty'];
$parts_id = $_POST['parts_id'];
$invoice_id = $_POST['invoice_id'];
$date1 = strtr($parts_add_date, '/', '-');
$parts_added_date = date('Y-m-d', strtotime($date1));

//$conn = new mysqli('localhost', 'root1', 'root@123ROOT', 'qqnmnheetg');
$conn = new mysqli('localhost', 'qqnmnheetg', 'Qsb93d32m5', 'qqnmnheetg');
if($conn->connect_error){
  die("Error in DB connection: ".$conn->connect_errno." : ".$conn->connect_error);    
}
if (isset($_POST['ddl_e_parts'])) {
  $insert= "insert into tbl_buy_parts(invoice_id, parts_id , parts_name, supplier_id, manufacturer_id, parts_condition, parts_buy_price, parts_quantity, parts_sku, parts_warranty, total_amount, given_amount, pending_amount, parts_image, parts_added_date) 
  values ('$invoice_id','$ddl_e_parts', '$parts_names', '$ddl_supplier', '$ddl_load_manufracturer','$txtCondition', '$buy_prie', '$parts_quantity', '$parts_sku', '$parts_warranty', '$total_amount', '$given_amount', '$pending_amount', '$img_exist', '$parts_added_date')"; 
  $conn->query($insert);
  $update= "UPDATE tbl_buy_parts SET invoice_id= $invoice_id";
  $conn->query($update);
}
mysqli_close($connection);
?>    