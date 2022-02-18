
<?php
    require_once "header.php";
    $style=file_get_contents("../_general/style/pdf/report/adminActive.css");
    $mpdf =new \Mpdf\Mpdf([
        "tempDir" => __DIR__ . "/tmp",
        "mode" => "utf-8", 
        "format" => "A4", 
        "default_font_size" => "0", 
        "default_font" => "Arial",
        "margin_left" => "4",
        "margin_right" => "4",
        "margin_top" => "50", 
        "margin_bottom" => "10", 
        "margin_header" => "0", 
        "margin_footer" => "10", 
        "orientation" => "P"
    ]); 
    $mpdf->setHTMLHeader('
        <div>
            <div style="text-align:center;padding-top: 10px;">
                <img src="../_general/image/pdf/logo.jpeg" width="100px" heigth="80px" alt="">
            </div>
            <h2 style="text-align:center;padding-bottom:0px;margin-bottom:0px;">
                Admin Information
            </h2>           
        </div>
        <hr>
    ');	
    $mpdf->setFooter("|{PAGENO} of {nb}|");
    $mpdf->WriteHTML($style,1);	
    extract($_GET);
   
    $admin=$database->return_data("
        SELECT
            staff.*
        FROM
            staff
        WHERE                
            STFProfileType=$typeUser and
            STFDeleted=$stateUser
    ","key_all");

    $table='
        <table  id="meanTable">
            <thead>
                <tr class="border-double bg-blue">
                    <th width="5%">No</th>
				    <th width="15%">UserName</th>
				    <th width="20%">FullName</th>
				    <th width="15%">PNumber</th>
				    <th width="20%">Email</th>
				    <th width="25%">Created Date</th>
                </tr>
            </thead>
            <tbody>
    ';
    for($i=0;$i<count($admin);$i++){
        $table.='
            <tr>
                <td >'.($i+1).'</td>
                <td >'.$admin[$i]["STFUsername"].'</td>
                <td >'.$admin[$i]["STFFullname"].'</td>
                <td >'.$admin[$i]["STFPhoneNumber"].'</td>
                <td >'.$admin[$i]["STFEmail"].'</td>
                <td >'.$admin[$i]["STFRegisterDate"].'</td>
            </tr>
        ';
    }
    $table.='
        </tbody>
        </table>
    ';
    $mpdf->WriteHTML('
       '.$table.'
    ',2);	
    $mpdf->Output();
?>
        

                        