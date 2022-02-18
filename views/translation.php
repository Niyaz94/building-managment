<?php 
    $fileName=__FILE__;
    include_once "header.php";
?>

<div id="addtranslationCollapse" class="panel-collapse collapse panel" aria-expanded="false" style="height: 0px; position: relative; top:-3px; width:98%; margin:0px auto;">
	<div class="panel-body">
		<form class="form-horizontal" method="post" id="add_form" enctype="multipart/form-data">
			<div>
				<input type="hidden" id="type" name="type" value="create">
				<div class="col-sm-12">
					<div class="form-group"><?php 
						echo input1("col-sm-12","text","Keyword","KEYName_ISZ","required","icon-typography");
					?></div>
				</div>
			</div>
			<div class="text-right">
				<?php
				 	echo button2("submit_add_btn","submit","Save","icon-floppy-disk");
					//echo button2("add_form_cancel","button","Close","icon-cross","btn btn-labeled btn-labeled-left bg-danger heading-btn",'data-toggle="collapse"');
				?>
				
				<a href="#addtranslationCollapse" id="add_form_cancel" class="btn btn-labeled btn-labeled-left bg-danger heading-btn" data-toggle="collapse">
					<span class="multi_lang">Close</span> <b><i class="icon-cross"></i></b>
				</a>
			</div>
		</form>
	</div>
</div>

<div class="panel panel-flat" id="testLoader">
	<table class="table" id="datatabletranslationView">
		<thead>
			<tr>
				<th>ID</th>
				<th class="multi_lang" width="35%">Keyword</th>
				<th class="multi_lang" width="35%">Translation</th>
				<th class="multi_lang">Language</th>
				<th class="multi_lang">Language Code</th>
				<th class="multi_lang" width="5%">Language Direction</th>
				<th class="multi_lang" width="15%">Actions</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
		<tfoot>
			<tr>
				<td></td>
				<td>username</td>
				<td>full_name</td>
				<td>EMail</td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
		</tfoot>
	</table>
</div>
<script type="text/javascript" src="controllers/translation.js?random=<?php echo uniqid(); ?>"></script>