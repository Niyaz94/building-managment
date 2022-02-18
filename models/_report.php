<?php
    include_once "../_general/backend/_header.php";
    include_once "_reportClass.php";
    $_report=new _reportClass($database);
    if (isset($_POST["type"]) || isset($_GET["type"])){
        if ($_POST["type"] == "adminActive"){
            extract($_POST);
            $STFRegisterDate="";
           // if($startDate<=$endDate){
           //     $STFRegisterDate="substring_index(substring_index(STFRegisterDate,' ',1),' ',-1) between '".$startDate."' and '".$endDate."' and ";
           // }
            echo json_encode($database->return_data("
                SELECT
                    staff.*
                FROM
                    staff
                WHERE                
                    STFProfileType=$typeUser and
                    STFDeleted=$stateUser
            ","key_all"));
        }
        if ($_POST["type"] == "shopDetail"){
            extract($_POST);
            $STFRegisterDate="";
            echo json_encode($database->return_data("
                SELECT
                    *
                FROM
                    shop
                WHERE
                    ".($buildType==3?"":"SOPCategory=".$buildType." and ")."
                    ".($buildStiuation==2?"":($buildStiuation==1?"SOPType=1 and ":"SOPType<>1 and "))."
                    SOPDeleted=0
            ","key_all"));
        }
        if ($_POST["type"] == "rentMonthly"){
            extract($_POST);
            echo json_encode($database->return_data("
                SELECT
                    CUSName,
                    SOPNumber,
                    AGRWorkype,
                    AGRShopTitle,
                    GROUP_CONCAT(ARTRentType,'_',ARTPaidType) as type
                FROM
                    agreement,
                    agreementrent,
                    customer,
                    shop
                WHERE
                    AGRID=ARTAGRFORID AND
                    SUBSTRING(ARTDate,1,7)='$month' AND
                    SOPID=AGRSOPFORID AND
                    CUSID=AGRCUSFORID AND
                    CUSDeleted=0 AND
                    AGRDeleted=0 AND
                    AGRDeleted=0 AND
                    ARTDeleted=0
                group BY
                    AGRID
            ","key_all"));
        }
        if($_POST["type"] == "electricPerMonth"){
            echo $_report->report4($_POST);
        }
        if($_POST["type"] == "customerRentDetail"){
            echo $_report->report5($_POST);
        }
        if ($_POST["type"] == "agreementDetail"){
            echo $_report->report6($_POST);
        }
        if($_POST["type"] == "monthlyTotal"){
            extract($_POST);
            echo $_report->report7($month);
        }
        if($_POST["type"] == "monthlyExpense"){
            extract($_POST);
            echo $_report->report8($month);
        }
        if($_POST["type"] == "dailyTotal"){
            extract($_POST);
            echo $_report->report9($date);
        }
        if($_POST["type"] == "expense"){
            echo $_report->report10($_POST);
        }
        if($_POST["type"] == "extraIncome"){
            echo $_report->report11($_POST);
        } 
    }else{
        header("Location:../");
        exit;
    }
    
?>
                        