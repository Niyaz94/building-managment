<?php
	include_once "../_general/backend/_header.php";
	if (isset($_POST['type']) || isset($_GET['type'])){
		if ($_POST["type"] == "given"){
			$agreement=$database->return_data("
				SELECT
					count(*) as total,
					concat(
						case ARTRentType
						when '0' then 'Area'
						when '1' then 'Service'
						when '2' then 'Electric'
						end
						,'_',
						case ARTPaidType
						when '0' then 'NotPaid'
						when '1' then 'PartialPaid'
						when '2' then 'Paid'
						end
					) as label
				FROM
					agreement,
					agreementrent
				WHERE
					AGRDeleted=0 AND
					ARTDeleted=0 AND
					ARTAGRFORID=AGRID and
					SUBSTRING(ARTDate,1,7)='".date("Y-m")."'
				group BY
					ARTRentType,ARTPaidType
			","key_all");

			echo json_encode(array($agreement));
		}
	}else{
		header("Location:../");
		exit;
	}
?>