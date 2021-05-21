$(function(){
	var casosAbiertos = $('#casignados').dataTable({
			"fnRowCallback": function( nRow, aData, iDisplayIndex ) {
				//nRow.className = aData[4]=="Yes" ? "success" : "warning";
				nRow.className = "success";
			},
			"bJQueryUI": false,
			"sPaginationType": "full_numbers",
			"bProcessing": true,
			"sAjaxSource": '../php/casos_admin.php?tidf=2', 
			"aoColumns": [
				{ "bVisible": 1 }, //Columna ID
				null,
				null,
				{ "sWidth": "10%" },
				null,
				null,
				null,
				null
			]
		});
});