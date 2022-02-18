<?php
    include_once "../_general/backend/_header.php";
    if (isset($_POST["type"]) || isset($_GET["type"])){
        if( isset($_GET["type"]) && ($_GET["type"] == "load")){
            $startDate=date("Y")."-".date("m")."-"."1";
            $endDate=date("Y")."-".(date("m")+1)."-"."1";
            $table = "(
                SELECT
                    EMPID,
                    EMPFullName,
                    EMPPhoto,
                    EMPJobTitle,
                    EMPPhone,
                    EMPGender,
                    EMPAddress,
                    EMPDateEmployment,
                    EMPSalary,
                    (
                        SELECT
                            PAYMonthSalary
                        FROM
                            payment
                        WHERE
                            PAYDeleted=0 AND
                            PAYEMPFORID=EMPID AND
                            PAYMonth between '$startDate' and '$endDate'
                    ) as PAYMonthSalary
                FROM
                    employee
                WHERE
                    EMPDeleted=0 
                    
            ) AS table1";
           // echo $table;exit;
            $primaryKey = "EMPID";
            $where="";
            $columns =  array(
                array( "db" => "EMPID", "dt" =>  0),  
                array( "db" => "EMPPhoto", "dt" =>  1),
                array( "db" => "EMPFullName", "dt" => 2 ),
                array( "db" => "EMPJobTitle", "dt" =>  3),
                array( "db" => "EMPPhone", "dt" => 4 ),
                array( "db" => "EMPGender", "dt" =>  5),
                array( "db" => "EMPAddress", "dt" => 6 ),
                array( "db" => "EMPDateEmployment", "dt" =>  7),
                array( "db" => "EMPSalary", "dt" => 8 ),
                array( "db" => "PAYMonthSalary", "dt" => 9 )
            );
            echo json_encode(
                SSP::complex( $_GET, $datatable_connection, $table, $primaryKey, $columns ,null, $where )
            );
            exit;
        }
        if ($_POST["type"] == "create") {	
            //testData($_FILES,0);
            $fileUpload=uploadingImageFileV2($_FILES,"../_general/image/employee/");
            if(!is_array($fileUpload)){
                echo $fileUpload;
                exit;
            }else{
                $_POST=array_merge($fileUpload,$_POST);
            } 
            $validation=new class_validation($_POST,"EMP");
            $data=$validation->returnLastVersion();
            $res = $database->insert_data2("employee",$data);
            if ($res) {	
                echo jsonMessages(true,2);
                exit;
            }else{
                echo jsonMessages(false,1);
                exit;
            }
        }
        if ($_POST["type"] == "update") {           
            $fileUpload=uploadingImageFileV2($_FILES,"../_general/image/employee/");
            if(!is_array($fileUpload)){
                echo $fileUpload;
                exit;
            }else{
                $_POST=array_merge($fileUpload,$_POST);
            } 
            $validation=new class_validation($_POST,"EMP");
            $data=$validation->returnLastVersion();
            extract($data);
            $res = $database->update_data2(array(
                "tablesName"=>"employee",
                "userData"=>$data,
                "conditions"=>array()
            ));
            if ($res) {
                echo jsonMessages(true,1);
                exit;
            }else{
                echo jsonMessages(false,1);
                exit;
            }
        }
        if ($_POST["type"] == "getSalary") {     
            extract($_POST);      
            $res = $database->return_data2(array(
                "tablesName"=>array("payment"),
                "columnsName"=>array("PAYID","PAYMonthSalary","PAYNote"),
                "conditions"=>array(
                    array("columnName"=>"PAYEMPFORID","operation"=>"=","value"=>$PAYEMPFORID,"link"=>"And"),
                    array("columnName"=>"PAYMonth","operation"=>"between","value"=>array(date("Y")."-".date("m")."-"."1",date("Y")."-".(date("m")+1)."-"."1"),"link"=>"And"),
                    array("columnName"=>"PAYDeleted","operation"=>"=","value"=>0,"link"=>""),
                ),
                "others"=>"",
                "returnType"=>"key"
            ));
            //testData($res,0);
            if ($res) {
                echo html_entity_decode(jsonMessages2(true,$res));
                exit;
            }else if(empty($res)){
                echo jsonMessages(true,5);
                exit;
            }else{
                echo jsonMessages(false,1);
                exit;
            }
        }
        if ($_POST["type"] == "updateSalary"){
            //testData($_POST,0);
            $validation=new class_validation($_POST,"PAY");
            $data=$validation->returnLastVersion();
            $data["PAYMonth"]=date("Y-m-d");
            if($data["PAYID"]==-1){
                $res = $database->insert_data2("payment",$data);
                if ($res) {	
                    echo jsonMessages(true,2);
                    exit;
                }else{
                    echo jsonMessages(false,1);
                    exit;
                }
            }else{
                //testData($data,0);
                $res = $database->update_data2(array(
                    "tablesName"=>"payment",
                    "userData"=>$data,
                    "conditions"=>array()
                ));
                if ($res) {
                    echo jsonMessages(true,1);
                    exit;
                }else{
                    echo jsonMessages(false,1);
                    exit;
                }
            }
            
            //testData($_POST,0);
        }
        if ($_POST["type"] == "returnEmployeeHistory"){
            extract($_POST);
            $employeeInfo = $database->return_data2(array(
                "tablesName"=>array("employee"),
                "columnsName"=>array("*"),
                "conditions"=>array(
                    array("columnName"=>"EMPID","operation"=>"=","value"=>$EMPID,"link"=>"And"),
                    array("columnName"=>"EMPDeleted","operation"=>"=","value"=>0,"link"=>""),
                ),
                "others"=>"",
                "returnType"=>"key"
            ));
            echo jsonMessages2(true,returnSalary($database,$employeeInfo,$EMPID));
            exit;
        }
        if ($_POST["type"] == "getAllSalary"){
            $employeeInfo = $database->return_data2(array(
                "tablesName"=>array("employee"),
                "columnsName"=>array("*"),
                "conditions"=>array(
                    array("columnName"=>"EMPDeleted","operation"=>"=","value"=>0,"link"=>""),
                ),
                "others"=>"",
                "returnType"=>"key_all"
            ));
            $employeeInfoLength=count($employeeInfo);
            $totalSalaryForMonth=array();
            /*
                __ 1st Employee --
                -------------------------------
                *     date    *  salary       *
                -------------------------------
                * 2018-05-01  *     700       *
                -------------------------------
                * 2018-06-01  *     600       *
                -------------------------------
                * 2018-07-01  *     800       *
                -------------------------------

                 __ 2nd Employee --
                -------------------------------
                *     date    *  salary       *
                -------------------------------
                -------------------------------
                * 2018-07-01  *     600       *
                -------------------------------
                * 2018-08-01  *     800       *
                -------------------------------

                Steps :-
                1- first general array free
                2- search inside employee one
                3- remove day from date then check if this day inside general array or not
                4- if not inside andd new row inside general array
                5- otherwise if this date inside the general array then addition salary of them
                6- the process continue until all data of first employee finished
                7- then go to the 2nd,3rd,... employee until find total salary for each month
            */
            for($x=0;$x<$employeeInfoLength;++$x){
                $salaryForOneEmployee=returnSalary($database,$employeeInfo[$x],$employeeInfo[$x]["EMPID"]);
                for($i=0;$i<count($salaryForOneEmployee);++$i){
                    if(count($totalSalaryForMonth)==0){
                        array_push($totalSalaryForMonth,$salaryForOneEmployee[$i]);
                    }else{
                        $checkCounter=0;
                        for($j=0;$j<count($totalSalaryForMonth);++$j){

                            $employeeTime=explode("-",$totalSalaryForMonth[$j]["date"]);
                            $totalTime=explode("-",$salaryForOneEmployee[$i]["date"]);
                            if(strtotime($employeeTime[0]."-".$employeeTime[1])==strtotime($totalTime[0]."-".$totalTime[1])){
                                $totalSalaryForMonth[$j]["monthSalary"]+=$salaryForOneEmployee[$i]["monthSalary"];
                                ++$checkCounter;
                                break;
                            }
                        }
                        if($checkCounter==0){
                            array_push($totalSalaryForMonth,$salaryForOneEmployee[$i]);
                        }
                    }   
                }
            }
            //convert the data to json
            $returnData=array();
            for($i=0;$i<count($totalSalaryForMonth);++$i){
                $returnData[]=array("date"=>date("Y-m",strtotime($totalSalaryForMonth[$i]["date"])),"totalSalary"=>$totalSalaryForMonth[$i]["monthSalary"]);
            }
            echo jsonMessages2(true,$returnData);
        }
    }else{
        header("Location:../");
        exit;
    }
    function returnSalary($database,$employeeInfo,$EMPID){
        //return all payment for this employee
        $payment = $database->return_data2(array(
            "tablesName"=>array("payment"),
            "columnsName"=>array("PAYMonth","PAYBasicSalary","PAYMonthSalary","PAYNote"),
            "conditions"=>array(
                array("columnName"=>"PAYEMPFORID","operation"=>"=","value"=>$EMPID,"link"=>"And"),
                array("columnName"=>"PAYDeleted","operation"=>"=","value"=>0,"link"=>""),
            ),
            "others"=>"",
            "returnType"=>"key_all"
        ));
        //return all system log for this employee
        $systemlog = $database->return_data('
            SELECT
                SLDOldValue,SLDNewValue,SLDCreateAt
            FROM
                system_log,system_log_detail
            WHERE
                LOGID=SLDForID AND
                LOGTable="employee" AND
                LOGAction="Update" AND
                LogColumnName="EMPSalary" AND
                LOGRowID='.$EMPID.'
        ','key_all');

        /* 
            if EMPRegisterDate 2018-01-10 10:40:50 am
            then :-
                startMonth => 2018-01-10
                dayOfStart => 10
                currentMonth => This Year - This month - dayOfStart(10 for example above)
        */
        $startMonth=strtotime(explode(" ",$employeeInfo["EMPRegisterDate"])[0]);
        $dayOfStart=explode("-",explode(" ",$employeeInfo["EMPRegisterDate"])[0])[2];
        $currentMonth=strtotime(date("Y-m")."-".$dayOfStart);
        $allMonthEmployeePaid=array();
        //creating array for all month salary
        // allMonthEmployeePaid => Array ( [0] => 2018-06-15 [1] => 2018-07-15 [2] => 2018-08-15 [3] => 2018-09-15 [4] => 2018-10-15 ) this is output sample
        for(;$startMonth<=$currentMonth;$startMonth=strtotime("+1 month",$startMonth)){
            $allMonthEmployeePaid[]=date("Y-m-d",$startMonth);
        }
        $employeeHistorySalary=array();
        for($end=count($allMonthEmployeePaid)-1;0<=$end;--$end){ //start at the end    
            //search inside payment, if found the salary in the payment then get data and stop
            for($i=count($payment)-1;$i>=0;--$i){
                if(strtotime($payment[$i]["PAYMonth"])>=strtotime($allMonthEmployeePaid[$end]) && strtotime($payment[$i]["PAYMonth"])<strtotime("+1 month",strtotime($allMonthEmployeePaid[$end]))){
                    array_push($employeeHistorySalary,array("date"=>date("Y-m",strtotime($allMonthEmployeePaid[$end])),"basicSalary"=>$payment[$i]["PAYBasicSalary"],"monthSalary"=>$payment[$i]["PAYMonthSalary"],"note"=>strip_tags(html_entity_decode($payment[$i]["PAYNote"]))));
                    continue 2;
                }
            }
            //if not found data in payment then go to systemlog
           
            //if no system log exist then return data from employee table
            if(count($systemlog)==0){
                $employeeHistorySalary[]=array("date"=>date("Y-m",strtotime($allMonthEmployeePaid[$end])),"basicSalary"=>$employeeInfo["EMPSalary"],"monthSalary"=>$employeeInfo["EMPSalary"],"note"=>"");
                continue;
            }
            /*
                -------------------------------------------------------
                *     date    *   oldSalary      *    newSalary      *    
                -------------------------------------------------------
                * 2017-10-10  *     200          *       500         *
                -------------------------------------------------------
                * 2017-12-10  *     500          *       700         *
                -------------------------------------------------------
                * 2018-02-05  *     700          *       600         *
                -------------------------------------------------------
            */

            // if the date 2017-05-20 this if work and return 200, also you can use insert row from system log because the same value
            if(strtotime(explode(" ",$systemlog[0]["SLDCreateAt"])[0])>strtotime($allMonthEmployeePaid[$end])){
                $employeeHistorySalary[]=array("date"=>date("Y-m",strtotime($allMonthEmployeePaid[$end])),"basicSalary"=>$systemlog[0]["SLDOldValue"],"monthSalary"=>$systemlog[0]["SLDOldValue"],"note"=>"");
                continue;
            }
            // if the date 2018-05-20 this if work and return 600, also you can return the current value inside employee table because the same value
            if(strtotime(explode(" ",$systemlog[0]["SLDCreateAt"])[count($systemlog)-1])<=strtotime($allMonthEmployeePaid[$end])){
                $employeeHistorySalary[]=array("date"=>date("Y-m",strtotime($allMonthEmployeePaid[$end])),"basicSalary"=>$systemlog[count($systemlog)-1]["SLDNewValue"],"monthSalary"=>$systemlog[count($systemlog)-1]["SLDNewValue"],"note"=>"");
                continue;
            }
            /*
                this loop search inside the table above, first check last date [2018-02-05] if smollar than the date we searched for 
                then return last newSalary [600] and stop searching otherwise go to the second row in the end this process continues until find the correct salary
            */ 
            for($i=count($systemlog)-1;$i>=0;--$i){
                if(strtotime(explode(" ",$systemlog[0]["SLDCreateAt"])[$i])<strtotime($allMonthEmployeePaid[$end]) ){
                    $employeeHistorySalary[]=array("date"=>date("Y-m",strtotime($allMonthEmployeePaid[$end])),"basicSalary"=>$systemlog[$i]["SLDNewValue"],"monthSalary"=>$systemlog[$i]["SLDNewValue"],"note"=>"");
                    continue 2;
                }
            }
        }

        return $employeeHistorySalary;
    }
?>
    