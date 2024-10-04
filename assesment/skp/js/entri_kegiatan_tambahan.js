function addRowTambahan()
{
	if (!document.getElementsByTagName) return;
	tabBody=document.getElementsByTagName("TBODY").item(2);
	
	var rownum = tabBody.rows.length;
	row=document.createElement("TR");
	  
	/* KOLOM 1 */
	cell = document.createElement("TD");
	var element = document.createElement("input");
	element.type = "text";
	element.name = "reqNoUrutTambahan[]";
	element.id = "reqNoUrutTambahan"+rownum;
	element.className='easyui-validatebox';	
	element.readOnly = true;
	element.value = rownum + 1;
	element.style.width = "30px";
	cell.appendChild(element);
	row.appendChild(cell);

	/* KOLOM 2 */
	cell = document.createElement("TD");
	var element = document.createElement("input");
	element.type = "text";
	element.name = "reqNamaTambahan[]";
	element.id = "reqNamaTambahan"+rownum;
	element.style.width = "1100px";
	element.className='easyui-validatebox';	
	cell.appendChild(element);
	row.appendChild(cell);
		
	/* KOLOM 4 */
	cell = document.createElement("TD");
	var element = document.createElement("input");
	element.type = "text";
	element.name = "reqKuantitasTambahan[]";
	element.id = "reqKuantitasTambahan"+rownum;
	element.style.width = "50px";
	element.className='easyui-validatebox';		
	cell.appendChild(element);
	row.appendChild(cell);
		
	/* KOLOM 5 */
	cell = document.createElement("TD");
	var element = document.createElement("input");
	element.type = "text";
	element.name = "reqKuantitasSatuanTambahan[]";
	element.id = "reqKuantitasSatuanTambahan"+rownum;
	element.style.width = "100px";
	element.className='easyui-combobox';		
	cell.appendChild(element);
	row.appendChild(cell);	
		
	/* KOLOM 10 */
	cell = document.createElement("TD");
	var button = document.createElement('label');
	button.style.textAlign='center';
	button.innerHTML = '<center><a style="cursor:pointer;" onclick="deleteRowDrawTable(\'dataTableRowDinamisTambahan\', this)"><img src="../WEB/images/delete-icon.png" width="15" height="15" border="0" /></a></center>';
	cell.appendChild(button);
	row.appendChild(cell);
	
	tabBody.appendChild(row);
	
	var rowCount = tabBody.rows.length;
	rowCount= rowCount-1;
	
	$('#reqKuantitasSatuanTambahan'+rowCount).combobox({
		url:'../json-skp/kuantitas_satuan_combo_json.php',
		valueField:'id',
        textField:'text',
        panelHeight:'120'
	});
	
	$('#reqKuantitasTambahan'+rowCount).keypress(function(e) {
		if( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
		{
		return false;
		}
	});
	
}
	
function deleteRowDrawTable(tableID, id) {
	if(confirm('Apakah anda ingin menghapus data terpilih?') == false)
		return "";
			
	try {
	var table = document.getElementById(tableID);
	var rowCount = table.rows.length;
	var id=id.parentNode.parentNode.parentNode.parentNode.rowIndex;
	
	for(var i=0; i<=rowCount; i++) {
		if(id == i) {
			table.deleteRow(i);
		}
	}
	}catch(e) {
		alert(e);
	}
}

function deleteRowDrawTablePhp(tableID, id) {
	if(confirm('Apakah anda ingin menghapus data terpilih?') == false)
		return "";
			
	try {
	var table = document.getElementById(tableID);
	var rowCount = table.rows.length;
	var id=id.parentNode.parentNode.parentNode.rowIndex;
	
	for(var i=0; i<=rowCount; i++) {
		if(id == i) {
			table.deleteRow(i);
		}
	}
	}catch(e) {
		alert(e);
	}
}