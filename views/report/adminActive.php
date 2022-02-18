<?php 
    $fileName=__FILE__;
    include_once "header.php";
?>
<style>
    table td,table th{
        text-align:center;
    }
</style>
<div class="panel panel-flat">
    <div class="panel-heading">
        <div class="heading-elements">
			<ul class="icons-list">
	    		<li><a data-action="collapse"></a></li>
	    		<li><a data-action="reload"></a></li>
	    	</ul>
		</div>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" method="post" id="addcampForm" enctype="multipart/form-data">
            <div class="col-sm-12">
                <div class="form-group"><?php 
                    echo input2("col-sm-6",array(0=>"Active",1=>"Delete",2=>"Deactive"),"Staff State","stateUser","","icon-users",0,"select2Class");
                    echo input2("col-sm-6",array(0=>"Permission",1=>"Admin"),"Staff Type","typeUser","","icon-users",0,"select2Class");
                ?></div>
                <div class="form-group"><?php 
                    //echo input1("col-sm-6","text","Start Date","startDate","required"," icon-calendar","","","dateStyle");
                    //echo input1("col-sm-6","text","Start Date","endDate","required"," icon-calendar","","","dateStyle");
                ?></div>
                <div class="form-group"><?php
                    //echo input1("col-sm-6","number","Start Number","startNumber","required","icon-tree6","0","","","min=0");
                    //echo input1("col-sm-6","number","End Number","endNumber","required",  "icon-tree6","0","","","min=0");
                ?></div>
            </div>  
            <div class="text-right"><?php
                echo button2("return_data","button","Report","icon-book","btn btn-warning btn-xlg btn-labeled btn-labeled-right");
                echo button2("pdf_data","button","PDF File","icon-file-pdf","btn btn-success btn-xlg btn-labeled btn-labeled-right");
            ?></div>
        </form> 
    </div>
</div>
<div class="panel panel-flat">
	<div class="panel-heading">
		<div class="heading-elements">
			<ul class="icons-list">
	    		<li><a data-action="collapse"></a></li>
	    		<li><a data-action="reload"></a></li>
	    	</ul>
		</div>
	</div>
	<div class="panel-body">
		<div class="table-responsive">
			<table class="table table-bordered table-framed table-sm">
				<thead>
                    <tr class="border-double bg-blue">
						<th width="10%">No</th>
						<th width="15%">UserName</th>
						<th width="20%">FullName</th>
						<th width="15%">PhoneNumber</th>
						<th width="25%">Email</th>
						<th width="15%">Created Date</th>
					</tr>
				</thead>
				<tbody id="reportBody">	
				</tbody>
                <tfoot>
					<tr class="border-double bg-blue">
                        <th width="10%">No</th>
						<th width="15%">UserName</th>
						<th width="20%">FullName</th>
						<th width="15%">PhoneNumber</th>
						<th width="25%">Email</th>
						<th width="15%">Created Date</th>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>
<script>
    $(document).ready(function () {
        generalConfig();
        $("#return_data").on("click",function(){
            $.ajax({
                url: "models/_report.php",
                type: "POST",
                dataType:"json",
                data: {
                    "type":"adminActive",
                    "typeUser":$("#typeUser").val(),
                    "stateUser":$("#stateUser").val(),
                    "startDate":$("#startDate").val(),
                    "endDate":$("#endDate").val(),
                    "startNumber":$("#startNumber").val(),
                    "endNumber":$("#endNumber").val(),
                },
                complete: function () {
                    oneCloseLoader("#"+$(this).parent().id,"self");
                },
                beforeSend: function () {
                    oneOpenLoader("#"+$(this).parent().id,"self","dark");
                },
                success: function (res) {
                    $("#reportBody").empty();
                    for (let index = 0; index < res.length; index++) {
                        $("#reportBody").append(`
                            <tr>
                                <td>${index+1}</td>
                                <td>${res[index]["STFUsername"]}</td>
                                <td>${res[index]["STFFullname"]}</td>
                                <td>${res[index]["STFPhoneNumber"]}</td>
                                <td>${res[index]["STFEmail"]}</td>
                                <td>${res[index]["STFRegisterDate"]}</td>
                            </tr>
                        `);
                    }
                },
                fail: function (err){
                },
                always:function(){
                }
            });
        });
        $("#pdf_data").on("click",function(){
            window.open(`pdf/adminActive.php?typeUser=${$("#typeUser").val()}&stateUser=${$("#stateUser").val()}`);
        });
    });
</script>