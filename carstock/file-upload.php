<?php
    // require '../vendor/PHPExcel/Classes/PHPExcel.php';
    // require_once '../vendor/PHPExcel/Classes/PHPExcel/IOFactory.php';
    include_once('../header.php');
    require_once('../vendor/autoload.php');

    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\IOFactory;
    use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
    use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

    $uploadFile = $_FILES['uploadFile']['tmp_name'];
    $reader = new Xlsx();
    $spreadsheet = $reader->load($uploadFile);
    $sheet = $spreadsheet->getActiveSheet();
    $highestRow = $sheet->getHighestRow();
    $highestColumn = $sheet->getHighestColumn();
    $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn);
    $dataTemp = [];
    for ($row = 1; $row <= $highestRow; ++$row) {
        for ($col = 1; $col <= $highestColumnIndex; ++$col) {
            $dataTemp[$row][] = $sheet->getCellByColumnAndRow($col, $row)->getValue();
        }
    }

    $data = [];

    for ( $row = 2; $row < count($dataTemp); $row++) {
        $machine = $dataTemp[$row][0];
        $customer = $dataTemp[$row][1];
        $address = $dataTemp[$row][2];
        $businessName = $dataTemp[$row][3];
        $tresps = $dataTemp[$row][4];
        $ruc = $dataTemp[$row][5];
        $lock = $dataTemp[$row][6];
        $maker = $dataTemp[$row][7];
        $model = $dataTemp[$row][8];
        $set = $dataTemp[$row][9];
        $setName = $dataTemp[$row][10];
        $serial = $dataTemp[$row][11];

        // Validations
        if ($customer == null || $businessName == null || $tresps == null || $lock == null || $maker == null) {
            $columna = ($customer == null ? 'cliente' : ($businessName == null ? 'razon social' : (($tresps == null) ? '3ps' : (($lock == null) ? 'chapa' : (($maker == null) ? 'fabricante' : '')))));
            // echo '<script>alert("Se encontraron la columna ' . ($cliente == null ? 'cliente' : ($razonSocial == null ? 'razon social' : (($tresps == null) ? '3ps' : (($chapa == null) ? 'chapa' : (($fabricante == null) ? 'fabricante' : ''))))) . ' vacía en la fila ' . $row . '")</script>';
            echo '
                <html>
                    <body>
                        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                        <script>
                        Swal.fire({
                            title: "No se pudo guardar \nfalta la columna ' . $columna . ' en la fila ' . $row . '",
                            icon: "warning",
                            confirmButtonColor: "#3085d6",
                            confirmButtonText: "Aceptar"
                          }).then((result) => {
                            if (result.isConfirmed) {
                                window.location = "buycarlist.php";
                            }
                          });
                        </script>
                    </body>
                </html>
            ';
            die();
        }
        $data[] = array('maquina' => $machine, //1
                                'cliente' => $customer,//2
                                'direccion' => $address,//3
                                'company_name' => $businessName,//4
                                'owner_name' => $tresps,//5
                                'ruc' => $ruc,//6
                                'chapa' => $lock,//7
                                'maker' => $maker,//8
                                'modelo' => $model,//9
                                'juego' => $set,//10
                                'nombreJuego' => $setName,//11
                                'serial' => $serial,//12
                                'make_id'=>null,//13
                                'model_id'=> null,//14
                                'car_door'=>null//15
                            );
    }
    // var_dump($data[1]);die();

    $makers = $wms->get_all_make_list($link);
    $models = $wms->get_all_model_list($link);
    $car_doors = $wms->get_all_car_door_list($link);
    $buy_date = date("Y") . "-" . date("n") . "-" . date("j");
    // assign maker
    for ($i=2; $i < sizeof($data); $i++) { 
        if (isset($data[$i][7]) && $data[$i]['maker'] != '-' && $data[$i]['maker'] != ' ' && $data[$i]['maker'] != '')
        {
            foreach($makers as $mak) {
                if ($mak['make_name'] == $data[$i]['maker']){
                    $data[$i]['make_id'] = $mak['make_id'];
                    break;
                }
            }

            // if doesn't exist the maker, create new
            if ($data[$i]['make_id'] == null)
            {
                $wms->saveUpdateMakeSetup($link, array('txtMakeName' => $data[$i]['maker'], 'submit_token' => 0));
                $lastMaker = $wms->get_make_by_name($link, $data[$i]['maker']);
                $makers[] = array('make_id'=> $lastMaker[0]['make_id'], 'make_name' => $data[$i]['maker']);
                $data[$i]['make_id'] = $lastMaker[0]['make_id'];
            }
        }
    }

    // if data containt a model, assign it
    
    for ($i=2; $i < sizeof($data); $i++) { 
        if (isset($data[$i]['modelo']) && $data[$i]['modelo'] != '-' && $data[$i]['modelo'] != ' ' && $data[$i]['modelo'] != '')
        {
            foreach($models as $mol) {
                if ($mol['model_name'] == $data[$i]['modelo']){
                    $data[$i]['model_id'] = $mol['model_id'];
                    break;
                }
            }

            // if doesn't existe model, create new
            if ($data[$i]['model_id'] == null)
            {
                $wms->saveUpdateModelSetup($link, array('ddlMake' => $data[$i]['make_id'], 'txtModelName' => $data[$i]['modelo'], 'submit_token' => 0));
                $lastModel = $wms->get_model_by_name($link, $data[$i]['modelo']);
                $models[] = array('model_id'=> $lastModel[0]['model_id'], 'model_name' => $data[$i]['modelo']);
                $data[$i]['model_id'] = $lastModel[0]['model_id'];
            }
        }
    }

    // Assign juego
    for ($i=2; $i < sizeof($data); $i++) { 
        if (isset($data[$i]['juego']) && $data[$i]['juego'] != '-' && $data[$i]['juego'] != ' ' && $data[$i]['juego'] != '')
        {
            if (strpos($data[$i]['juego'], "'")){
                $data[$i]['juego'] = str_replace("'", "\'", $data[$i]['juego']);
            }
            foreach($car_doors as $cd) {
                if ($cd['door_name'] == $data[$i]['juego']){
                    $data[$i]['car_door'] = $cd['door_id'];
                    break;
                }
            }
            if ($data[$i]['car_door'] == null)
            {
                $wms->saveUpdateCarDoor($link, array('txtDoor' => $data[$i]['juego'], 'submit_token' => 0));
                $lastDoor = $wms->get_car_door_by_name($link, $data[$i]['juego']);
                $car_doors[] =  array('door_id'=> $lastDoor[0]['door_id'], 'door_name' => $data[$i]['juego']);
                $data[$i]['door_id'] = $lastDoor[0]['door_id'];
            }
        }
    }
    
    for ($i=2; $i < sizeof($data); $i++) { 
        if (strpos($data[$i]['maquina'], "'")){
            $data[$i]['maquina'] = str_replace("'", "\'", $data[$i]['maquina']);
        }
        // var_dump($data[$i]['model_id']);
        // $query = "INSERT INTO tbl_buycar(owner_name, company_name, owner_address, make_id, model_id, owner_client, car_plate, nid, car_door, car_status, car_condition) values(".($data[$i]['owner_name'] == null ? 'null' : "'".$data[$i]['owner_name']."'").",".($data[$i]['company_name'] == null ? 'null' : "'".$data[$i]['company_name']."'").",".($data[$i]['direccion'] == null ? 'null' : "'".$data[$i]['direccion']."'").",".($data[$i]['make_id'] == null ? 'null' : "".$data[$i]['make_id']."").", ".($data[$i]['model_id'] == null ? 'null' : "".$data[$i]['model_id']."").",". ($data[$i]['cliente'] == null ? 'null' : "'".$data[$i]['cliente']."'") . ", " . ($data[$i]['chapa'] == null ? 'null' : "'".$data[$i]['chapa']."'") ."," . ($data[$i]['ruc'] == null ? 'null' : "'" . $data[$i]['ruc'] . "'") . "," . ($data[$i]['car_door'] == null ? 'null' : $data[$i]['car_door']) . ", 0, 'new')";
        $query = "INSERT INTO tbl_buycar(owner_name, company_name, owner_address, make_id, model_id, /*car_plate,*/ nid, car_door, car_name, car_status, car_condition, buy_date) values("
        .($data[$i]['owner_name'] == null ? '\' \'' : "'".$data[$i]['owner_name']."'").","
        .($data[$i]['company_name'] == null ? 'null' : "'".$data[$i]['company_name']."'").","
        .($data[$i]['direccion'] == null ? '\'  \'' : "'".$data[$i]['direccion']."'").","
        .($data[$i]['make_id'] == null ? '0' : $data[$i]['make_id']).", "
        .($data[$i]['model_id'] == null ? '0' : $data[$i]['model_id']).", " 
        // .($data[$i]['chapa'] == null ? 'null' : "'".$data[$i]['chapa']."'") ."," 
        .($data[$i]['ruc'] == null ? '\' \'' : "'" . $data[$i]['ruc'] . "'") . "," 
        .($data[$i]['car_door'] == null ? '0' : $data[$i]['car_door']) . ", "
        .($data[$i]['maquina'] == null ? '\' \'' : "'" . $data[$i]['maquina'] . "'")
        . ", 0, 'new', ". "'" . $buy_date . "'" .")";

        // echo '<br>';
        // var_dump($query);
        // echo '<br>';
        // echo '<br>';
        // mysqli_query($link, $query);
        echo '<br><br>';
        var_dump($query);
        mysqli_query($link,$query);

        $lastBuyCar = $wms->getLastBuyCar($link);

        // $queryCarSell = "INSERT INTO `tbl_carsell`(`car_id`, `buyer_name`, `buyer_mobile`, `buyer_email`, `sellernid`, `company_name`, `ctl`, `present_address`, `permanent_address`, `selling_price`, `advance_amount`, `due_amount`, `selling_date`, `sell_note`, `is_return`, `invoice_id`, `car_name`, `make_name`, `model_name`, `year_name`, `color_name`, `door_name`, `car_condition`, `car_totalmileage`, `car_chasis_no`, `car_engine_name`, `service_warranty`) VALUES (".$lastBuyCar[0]["buycar_id"].",'".$data[$i]['cliente']."',' ',' ',' ','".$data[$i]['company_name']."',' ','".$data[$i]['direccion']."','".$data[$i]['direccion']."', 0, 0, 0,' selling_d ','".$buy_date."', 0, 0,'".$data[$i]['maquina']."',' ".$data[$i]['maker']." ',' ".$data[$i]['modelo']." ',' ',' ',' ".$data[$i]['juego']." ','new',' ',' ',' ','# Year')";
        $queryCarSell = "INSERT INTO `tbl_carsell`(`car_id`, `buyer_name`, `buyer_mobile`, `buyer_email`, `sellernid`, `company_name`, `ctl`, `present_address`, `permanent_address`, `selling_price`, `advance_amount`, `due_amount`, `selling_date`, `sell_note`, `is_return`, `invoice_id`, `car_name`, `make_name`, `model_name`, `year_name`, `color_name`, `door_name`, `car_condition`, `car_totalmileage`, `car_chasis_no`, `car_engine_name`, `service_warranty`) VALUES (".$lastBuyCar[0]["buycar_id"].",'".$data[$i]['cliente']."',' ',' ',' ','".$data[$i]['company_name']."',' ','".$data[$i]['direccion']."','".$data[$i]['direccion']."', 0, 0, 0,'".$buy_date."', 0, 0, 0,'".$data[$i]['maquina']."',' ".$data[$i]['maker']." ',' ".$data[$i]['modelo']." ',' ',' ',' ".$data[$i]['juego']." ','new',' ',' ',' ','# Year')";
        
        echo '<br><br>';
        var_dump($queryCarSell);
        mysqli_query($link,$queryCarSell);
    }
    // ownername => ownername
    // companyName => companyName
    // ownerAddress => direccion



    // var_dump($data[0]);
    $host= $_SERVER["HTTP_HOST"];

    // Cambiar en produccion por el metodo para redireccionar si se utiliza alguno en especifico
    header("Location: http://" . $host ."/Devcoders/masterTech/carstock/buycarlist.php");
    die();

    // foreach( $objExcel->getWorksheetIterator() as $worksheet ) {
    //     $highestrow = $worksheet->getHighestRow();

    //     var_dump($highestrow);die();

    //     for ($row=2; $row < $highestrow; $row++) { 
    //         // obtener values 
    //         $machine = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
    //         $customer = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
    //         $address = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
    //         $businessName = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
    //         $tresps = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
    //         $ruc = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
    //         $lock = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
    //         $maker = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
    //         $model = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
    //         $set = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
    //         $setName = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
    //         $serial = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
            
    //         // validate data 
    //         // var_dump($serial);
    //         // var_dump($cliente);
    //         // var_dump($razonSocial);
    //         // var_dump($tresps);
    //         // var_dump($chapa);
    //         // var_dump($fabricante);
    //         // var_dump('-----------------------------------------------------------------');
    //         // echo('<br>');

    //         if ($customer == null || $businessName == null || $tresps == null || $lock == null || $maker == null) {
    //                 $columna = ($customer == null ? 'cliente' : ($businessName == null ? 'razon social' : (($tresps == null) ? '3ps' : (($lock == null) ? 'chapa' : (($maker == null) ? 'fabricante' : '')))));
    //                 // echo '<script>alert("Se encontraron la columna ' . ($cliente == null ? 'cliente' : ($razonSocial == null ? 'razon social' : (($tresps == null) ? '3ps' : (($chapa == null) ? 'chapa' : (($fabricante == null) ? 'fabricante' : ''))))) . ' vacía en la fila ' . $row . '")</script>';
    //                 echo '
    //                     <html>
    //                         <body>
    //                             <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    //                             <script>
    //                             Swal.fire({
    //                                 title: "No se pudo guardar \nfalta la columna ' . $columna . ' en la fila ' . $row . '",
    //                                 icon: "warning",
    //                                 confirmButtonColor: "#3085d6",
    //                                 confirmButtonText: "Aceptar"
    //                               }).then((result) => {
    //                                 if (result.isConfirmed) {
    //                                     window.location = "buycarlist.php";
    //                                 }
    //                               });
    //                             </script>
    //                         </body>
    //                     </html>
    //                 ';
    //                 die();
    //         }
    //         else
    //         {
    //             $data[] = array('maquina' => $machine, 
    //                             'cliente' => $customer,
    //                             'direccion' => $address,
    //                             'company_name' => $businessName,
    //                             'owner_name' => $tresps,
    //                             'ruc' => $ruc,
    //                             'chapa' => $lock,
    //                             'maker' => $maker,
    //                             'modelo' => $model,
    //                             'juego' => $set,
    //                             'nombreJuego' => $setName,
    //                             'serial' => $serial,
    //                             'make_id'=>null,
    //                             'model_id'=> null,
    //                             'car_door'=>null
    //                         );
    //         }

    //     }
    //     $makers = $wms->get_all_make_list($link);
    //     $models = $wms->get_all_model_list($link);
    //     $car_doors = $wms->get_all_car_door_list($link);
    //     $buy_date = date("Y") . "-" . date("n") . "-" . date("j");
    //     // assign maker
    //     for ($i=0; $i < sizeof($data); $i++) { 
    //         if (isset($data[$i]['maker']) && $data[$i]['maker'] != '-' && $data[$i]['maker'] != ' ' && $data[$i]['maker'] != '')
    //         {
    //             foreach($makers as $mak) {
    //                 if ($mak['make_name'] == $data[$i]['maker']){
    //                     $data[$i]['make_id'] = $mak['make_id'];
    //                     break;
    //                 }
    //             }
    
    //             // if doesn't exist the maker, create new
    //             if ($data[$i]['make_id'] == null)
    //             {
    //                 $wms->saveUpdateMakeSetup($link, array('txtMakeName' => $data[$i]['maker'], 'submit_token' => 0));
    //                 $lastMaker = $wms->get_make_by_name($link, $data[$i]['maker']);
    //                 $makers[] = array('make_id'=> $lastMaker[0]['make_id'], 'make_name' => $data[$i]['maker']);
    //                 $data[$i]['make_id'] = $lastMaker[0]['make_id'];
    //             }
    //         }
    //     }

    //     // if data containt a model, assign it
        
    //     for ($i=0; $i < sizeof($data); $i++) { 
    //         if (isset($data[$i]['modelo']) && $data[$i]['modelo'] != '-' && $data[$i]['modelo'] != ' ' && $data[$i]['modelo'] != '')
    //         {
    //             foreach($models as $mol) {
    //                 if ($mol['model_name'] == $data[$i]['modelo']){
    //                     $data[$i]['model_id'] = $mol['model_id'];
    //                     break;
    //                 }
    //             }
    
    //             // if doesn't existe model, create new
    //             if ($data[$i]['model_id'] == null)
    //             {
    //                 $wms->saveUpdateModelSetup($link, array('ddlMake' => $data[$i]['make_id'], 'txtModelName' => $data[$i]['modelo'], 'submit_token' => 0));
    //                 $lastModel = $wms->get_model_by_name($link, $data[$i]['modelo']);
    //                 $models[] = array('model_id'=> $lastModel[0]['model_id'], 'model_name' => $data[$i]['modelo']);
    //                 $data[$i]['model_id'] = $lastModel[0]['model_id'];
    //             }
    //         }
    //     }

    //     // Assign juego
    //     for ($i=0; $i < sizeof($data); $i++) { 
    //         if (isset($data[$i]['juego']) && $data[$i]['juego'] != '-' && $data[$i]['juego'] != ' ' && $data[$i]['juego'] != '')
    //         {
    //             if (strpos($data[$i]['juego'], "'")){
    //                 $data[$i]['juego'] = str_replace("'", "\'", $data[$i]['juego']);
    //             }
    //             foreach($car_doors as $cd) {
    //                 if ($cd['door_name'] == $data[$i]['juego']){
    //                     $data[$i]['car_door'] = $cd['door_id'];
    //                     break;
    //                 }
    //             }
    //             if ($data[$i]['car_door'] == null)
    //             {
    //                 $wms->saveUpdateCarDoor($link, array('txtDoor' => $data[$i]['juego'], 'submit_token' => 0));
    //                 $lastDoor = $wms->get_car_door_by_name($link, $data[$i]['juego']);
    //                 $car_doors[] =  array('door_id'=> $lastDoor[0]['door_id'], 'door_name' => $data[$i]['juego']);
    //                 $data[$i]['door_id'] = $lastDoor[0]['door_id'];
    //             }
    //         }
    //     }
        
    //     for ($i=0; $i < sizeof($data); $i++) { 
    //         if (strpos($data[$i]['maquina'], "'")){
    //             $data[$i]['maquina'] = str_replace("'", "\'", $data[$i]['maquina']);
    //         }
    //         // var_dump($data[$i]['model_id']);
    //         // $query = "INSERT INTO tbl_buycar(owner_name, company_name, owner_address, make_id, model_id, owner_client, car_plate, nid, car_door, car_status, car_condition) values(".($data[$i]['owner_name'] == null ? 'null' : "'".$data[$i]['owner_name']."'").",".($data[$i]['company_name'] == null ? 'null' : "'".$data[$i]['company_name']."'").",".($data[$i]['direccion'] == null ? 'null' : "'".$data[$i]['direccion']."'").",".($data[$i]['make_id'] == null ? 'null' : "".$data[$i]['make_id']."").", ".($data[$i]['model_id'] == null ? 'null' : "".$data[$i]['model_id']."").",". ($data[$i]['cliente'] == null ? 'null' : "'".$data[$i]['cliente']."'") . ", " . ($data[$i]['chapa'] == null ? 'null' : "'".$data[$i]['chapa']."'") ."," . ($data[$i]['ruc'] == null ? 'null' : "'" . $data[$i]['ruc'] . "'") . "," . ($data[$i]['car_door'] == null ? 'null' : $data[$i]['car_door']) . ", 0, 'new')";
    //         $query = "INSERT INTO tbl_buycar(owner_name, company_name, owner_address, make_id, model_id, /*car_plate,*/ nid, car_door, car_name, car_status, car_condition, buy_date) values("
    //         .($data[$i]['owner_name'] == null ? '\' \'' : "'".$data[$i]['owner_name']."'").","
    //         .($data[$i]['company_name'] == null ? 'null' : "'".$data[$i]['company_name']."'").","
    //         .($data[$i]['direccion'] == null ? '\'  \'' : "'".$data[$i]['direccion']."'").","
    //         .($data[$i]['make_id'] == null ? '0' : $data[$i]['make_id']).", "
    //         .($data[$i]['model_id'] == null ? '0' : $data[$i]['model_id']).", " 
    //         // .($data[$i]['chapa'] == null ? 'null' : "'".$data[$i]['chapa']."'") ."," 
    //         .($data[$i]['ruc'] == null ? '\' \'' : "'" . $data[$i]['ruc'] . "'") . "," 
    //         .($data[$i]['car_door'] == null ? '0' : $data[$i]['car_door']) . ", "
    //         .($data[$i]['maquina'] == null ? '\' \'' : "'" . $data[$i]['maquina'] . "'")
    //         . ", 0, 'new', ". "'" . $buy_date . "'" .")";

    //         // echo '<br>';
    //         // var_dump($query);
    //         // echo '<br>';
    //         // echo '<br>';
    //         // mysqli_query($link, $query);
    //         echo '<br><br>';
    //         var_dump($query);
    //         mysqli_query($link,$query);
    //     }
    //     // ownername => ownername
    //     // companyName => companyName
    //     // ownerAddress => direccion



    //     // var_dump($data[0]);
    //     $host= $_SERVER["HTTP_HOST"];

    //     // Cambiar en produccion por el metodo para redireccionar si se utiliza alguno en especifico
    //     header("Location: http://" . $host ."/Devcoders/masterTech/carstock/buycarlist.php");
    //     die();
    // }

?>