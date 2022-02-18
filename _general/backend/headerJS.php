<?php
	function headerJS($page){
		
		$link='';
		if($page=="languages"){
			$link='
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/datatables.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/forms/selects/select2.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/buttons.min.js"></script>

				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/row_reorder.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/fixed_header.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/key_table.min.js"></script>

				<script type="text/javascript" src="../API/assets/js/core/app.js"></script>
			';
		}else if($page=="translation"){
			$link='
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/datatables.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/forms/selects/select2.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/buttons.min.js"></script>


				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/row_reorder.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/fixed_header.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/key_table.min.js"></script>

				<script type="text/javascript" src="../API/assets/js/plugins/forms/inputs/touchspin.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/forms/inputs/maxlength.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/forms/inputs/formatter.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/ui/moment/moment.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/pickers/daterangepicker.js"></script>
			
				<script type="text/javascript" src="../API/assets/js/core/app.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/forms/selects/bootstrap_select.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/pages/components_popups.js"></script>
			';
		}elseif($page=="admin"){
			$link='

				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/datatables.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/forms/selects/select2.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/buttons.min.js"></script>


				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/row_reorder.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/fixed_header.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/key_table.min.js"></script>

				<script type="text/javascript" src="../API/assets/js/core/app.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/forms/selects/select2.min.js"></script>
				<!-- file need for bootstrap_multiselect -->
				<script type="text/javascript" src="../API/assets/js/plugins/notifications/pnotify.min.js"></script>	
				<script type="text/javascript" src="../API/assets/js/plugins/forms/selects/bootstrap_multiselect.js"></script>
				<!-- file need for switch -->		
				<script type="text/javascript" src="../API/assets/js/plugins/forms/styling/switch.min.js"></script>
				
			';
		}else if($page=="systemlog"){
			$link='
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/datatables.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/forms/selects/select2.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/buttons.min.js"></script>

				<script type="text/javascript" src="../API/assets/js/core/app.js"></script>

				<script type="text/javascript" src="../API/assets/js/plugins/ui/moment/moment.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/pickers/anytime.min.js"></script>
			
			';
		}else if($page=="profile"){
			$link='
				<script type="text/javascript" src="../API/assets/js/plugins/forms/selects/select2.min.js"></script>
			';
		}else if($page=="setting"){
			$link.='
				<script type="text/javascript" src="../API/assets/js/plugins/forms/selects/select2.min.js"></script>
			';

		}else if($page=="shop"){
			$link.='

			 	<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/datatables.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/forms/selects/select2.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/buttons.min.js"></script>


				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/row_reorder.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/fixed_header.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/key_table.min.js"></script>

				<script type="text/javascript" src="../API/assets/js/core/app.js"></script>

				<script type="text/javascript" src="../API/assets/js/plugins/forms/inputs/touchspin.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/forms/selects/select2.min.js"></script>
				<script type="text/javascript" src="../API/assets/ckeditor/ckeditor.js"></script>

			';

		}else if($page=="employee"){
			$link.='
			 	<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/datatables.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/forms/selects/select2.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/buttons.min.js"></script>


				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/row_reorder.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/fixed_header.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/key_table.min.js"></script>

				<script type="text/javascript" src="../API/assets/js/core/app.js"></script>

				<script type="text/javascript" src="../API/assets/js/plugins/ui/moment/moment.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/pickers/daterangepicker.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/forms/selects/select2.min.js"></script>
				<script type="text/javascript" src="../API/assets/ckeditor/ckeditor.js"></script>

			';

		}else if($page=="customer"){
			$link.='
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/datatables.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/forms/selects/select2.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/buttons.min.js"></script>


				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/row_reorder.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/fixed_header.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/key_table.min.js"></script>

				<script type="text/javascript" src="../API/assets/js/core/app.js"></script>
				<script type="text/javascript" src="../API/assets/ckeditor/ckeditor.js"></script>
			';
		}else if($page=="agreement"){
			$link.='
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/datatables.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/forms/selects/select2.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/buttons.min.js"></script>


				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/row_reorder.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/fixed_header.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/key_table.min.js"></script>

				<script type="text/javascript" src="../API/assets/js/core/app.js"></script>

				<script type="text/javascript" src="../API/assets/js/plugins/ui/moment/moment.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/pickers/daterangepicker.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/pickers/anytime.min.js"></script>

				<script type="text/javascript" src="../API/assets/js/plugins/forms/selects/select2.min.js"></script>
				<script type="text/javascript" src="../API/assets/ckeditor/ckeditor.js"></script>
			';
		}else if($page=="watch"){
			$link.='
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/datatables.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/forms/selects/select2.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/buttons.min.js"></script>

				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/row_reorder.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/fixed_header.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/key_table.min.js"></script>

				<script type="text/javascript" src="../API/assets/js/core/app.js"></script>

			';
		}else if($page=="watchrent"){
			$link.='
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/datatables.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/forms/selects/select2.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/buttons.min.js"></script>


				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/row_reorder.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/fixed_header.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/key_table.min.js"></script>

				<script type="text/javascript" src="../API/assets/js/core/app.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/ui/moment/moment.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/pickers/anytime.min.js"></script>

				<script type="text/javascript" src="../API/assets/ckeditor/ckeditor.js"></script>
			';
		}else if($page=="agreementrent"){
			$link.='
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/datatables.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/forms/selects/select2.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/buttons.min.js"></script>


				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/row_reorder.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/fixed_header.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/key_table.min.js"></script>

				<script type="text/javascript" src="../API/assets/js/core/app.js"></script>

				<script type="text/javascript" src="../API/assets/js/plugins/notifications/pnotify.min.js"></script>	
				<script type="text/javascript" src="../API/assets/js/plugins/forms/selects/bootstrap_multiselect.js"></script>

				<script type="text/javascript" src="../API/assets/ckeditor/ckeditor.js"></script>
			';
		}else if($page=="expensesgroup"){
			$link.='
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/datatables.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/forms/selects/select2.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/buttons.min.js"></script>

				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/row_reorder.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/fixed_header.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/key_table.min.js"></script>

				<script type="text/javascript" src="../API/assets/js/core/app.js"></script>
				<script type="text/javascript" src="../API/assets/ckeditor/ckeditor.js"></script>
			';
		}else if($page=="expenses"){
			$link.='
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/datatables.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/forms/selects/select2.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/buttons.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/row_reorder.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/fixed_header.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/key_table.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/core/app.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/ui/moment/moment.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/pickers/daterangepicker.js"></script>
				<script type="text/javascript" src="../API/assets/ckeditor/ckeditor.js"></script>
			';
		}else if($page=="returnadvance"){
			$link.='
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/datatables.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/forms/selects/select2.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/buttons.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/row_reorder.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/fixed_header.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/key_table.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/core/app.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/ui/moment/moment.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/pickers/daterangepicker.js"></script>
				<script type="text/javascript" src="../API/assets/ckeditor/ckeditor.js"></script>
			';
		}else if($page=="advance"){
			$link.='
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/datatables.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/forms/selects/select2.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/buttons.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/row_reorder.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/fixed_header.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/key_table.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/core/app.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/ui/moment/moment.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/pickers/daterangepicker.js"></script>
				<script type="text/javascript" src="../API/assets/ckeditor/ckeditor.js"></script>
			';
		}else if($page=="capital"){
			$link.='
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/datatables.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/forms/selects/select2.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/buttons.min.js"></script>

				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/row_reorder.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/fixed_header.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/key_table.min.js"></script>

				<script type="text/javascript" src="../API/assets/js/core/app.js"></script>

				<script type="text/javascript" src="../API/assets/js/plugins/ui/moment/moment.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/pickers/daterangepicker.js"></script>
				<script type="text/javascript" src="../API/assets/ckeditor/ckeditor.js"></script>
			';
		}else if($page=="payment"){
			$link.='
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/datatables.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/forms/selects/select2.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/buttons.min.js"></script>

				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/row_reorder.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/fixed_header.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/tables/datatables/extensions/key_table.min.js"></script>

				<script type="text/javascript" src="../API/assets/js/core/app.js"></script>

				<script type="text/javascript" src="../API/assets/js/plugins/notifications/pnotify.min.js"></script>	
				<script type="text/javascript" src="../API/assets/js/plugins/forms/selects/bootstrap_multiselect.js"></script>
				
				<script type="text/javascript" src="../API/assets/js/plugins/ui/moment/moment.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/pickers/daterangepicker.js"></script>
				<script type="text/javascript" src="../API/assets/ckeditor/ckeditor.js"></script>
			';
		}elseif($page=="dashboard"){
			$link='
				<script type="text/javascript" src="../API/assets/js/plugins/visualization/echarts/echarts.js"></script>
				<script type="text/javascript" src="../API/assets/js/core/app.js"></script>
				<script type="text/javascript" src="../API/assets/js/charts/echarts/timeline_option.js"></script>
			';
		}else if($page=="adminActive"){
			$link='
			    <script type="text/javascript" src="../API/assets/js/plugins/forms/selects/select2.min.js"></script>
                <script type="text/javascript" src="../API/assets/js/core/app.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/ui/moment/moment.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/pickers/daterangepicker.js"></script>
			';
		}else if($page=="shopDetail"){
			$link='
			    <script type="text/javascript" src="../API/assets/js/plugins/forms/selects/select2.min.js"></script>
                <script type="text/javascript" src="../API/assets/js/core/app.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/ui/moment/moment.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/pickers/daterangepicker.js"></script>
			';
		}else if($page=="agreementDetail"){
			$link='
			    <script type="text/javascript" src="../API/assets/js/plugins/forms/selects/select2.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/ui/moment/moment.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/pickers/daterangepicker.js"></script>
			';
		}else if($page=="rentMonthly"){
			$link='
			    <script type="text/javascript" src="../API/assets/js/plugins/forms/selects/select2.min.js"></script>
                <script type="text/javascript" src="../API/assets/js/core/app.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/ui/moment/moment.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/pickers/anytime.min.js"></script>
			';
		}else if($page=="electricPerMonth"){
			$link='
                <script type="text/javascript" src="../API/assets/js/core/app.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/ui/moment/moment.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/pickers/anytime.min.js"></script>
			';
		}else if($page=="customerRentDetail"){
			$link='
				<script type="text/javascript" src="../API/assets/js/plugins/forms/selects/select2.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/ui/moment/moment.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/pickers/daterangepicker.js"></script>
			';
		}else if($page=="monthlyTotal"){
			$link='
				<script type="text/javascript" src="../API/assets/js/plugins/ui/moment/moment.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/pickers/anytime.min.js"></script>
			';
		}else if($page=="monthlyExpense"){
			$link='
				<script type="text/javascript" src="../API/assets/js/plugins/ui/moment/moment.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/pickers/anytime.min.js"></script>
			';
		}else if($page=="dailyTotal"){
			$link='
				<script type="text/javascript" src="../API/assets/js/plugins/ui/moment/moment.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/pickers/daterangepicker.js"></script>
			';
		}else if($page=="expense"){
			$link='
				<script type="text/javascript" src="../API/assets/js/plugins/forms/selects/select2.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/ui/moment/moment.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/pickers/daterangepicker.js"></script>
			';
		}else if($page=="extraIncome"){
			$link='
				<script type="text/javascript" src="../API/assets/js/plugins/forms/selects/select2.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/ui/moment/moment.min.js"></script>
				<script type="text/javascript" src="../API/assets/js/plugins/pickers/daterangepicker.js"></script>
			';
		}

		$link.='
			<script type="text/javascript" src="../API/assets/js/plugins/notifications/pnotify.min.js"></script>
			<script type="text/javascript" src="../API/assets/js/plugins/notifications/noty.min.js"></script>
			<script type="text/javascript" src="../API/assets/js/plugins/notifications/jgrowl.min.js"></script>
			<script type="text/javascript" src="../API/assets/js/plugins/notifications/sweet_alert.min.js"></script>
			<script type="text/javascript" src="../API/assets/js/plugins/forms/styling/uniform.min.js"></script>

			<script type="text/javascript" src="../API/assets/js/plugins/forms/tags/tagsinput.min.js"></script>
			<script type="text/javascript" src="../API/assets/js/plugins/forms/tags/tokenfield.min.js"></script>

			<script type="text/javascript" src="_general/frontend/alert.js"></script>
			<script type="text/javascript" src="_general/frontend/messege.js"></script>
			<script type="text/javascript" src="_general/frontend/loader.js"></script>
			<script type="text/javascript" src="_general/frontend/buttons.js"></script>
			<script type="text/javascript" src="_general/frontend/tooltip.js"></script>
			<script type="text/javascript" src="_general/frontend/dataTable.js"></script>
			<script type="text/javascript" src="_general/frontend/generalValidation.js"></script>
			<script type="text/javascript" src="_general/frontend/generalFunctions.js"></script>
		';

		return $link;
	}
?>


