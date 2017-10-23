var oTable;
$(document).ready(function(){
	oTable = $('#attendeeTable').dataTable( {
		"processing": true,
		"serverSide": true,
		"ajax": {
			"url": admin_path ()+'users/ajax_list/',
			"type": "POST"
		},
		/*aoColumnDefs: [
			{
			 bSortable: false,
			 aTargets: [ -1 ]
		  }
		],
		"order": [[ 6, "desc" ]]*/
	} )//.columnFilter({sPlaceHolder:"head:after",aoColumns: [null, null, null, null,null,null,null,{ type:"select",values:[{"label":"Active","value":"1"},{"label":"In Active","value":"0"}]} ]});*/
});
