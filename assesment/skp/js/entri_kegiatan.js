function addRow()
{
	if (!document.getElementsByTagName) return;
	tabBody=document.getElementsByTagName("TBODY").item(1);
	
	var rownum = tabBody.rows.length;
	row=document.createElement("TR");
	  
	/* KOLOM 1 */
	cell = document.createElement("TD");
	var element = document.createElement("input");
	element.type = "text";
	element.name = "reqNoUrut[]";
	element.id = "reqNoUrut"+rownum;
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
	element.name = "reqNama[]";
	element.id = "reqNama"+rownum;
	element.style.width = "500px";
	element.className='easyui-validatebox';	
	cell.appendChild(element);
	row.appendChild(cell);
		
	/* KOLOM 3 */
	cell = document.createElement("TD");
	var element = document.createElement("input");
	element.type = "text";
	element.name = "reqAK[]";
	element.id = "reqAK"+rownum;
	element.style.width = "50px";
	element.className='easyui-validatebox';		
	cell.appendChild(element);
	row.appendChild(cell);
		
	/* KOLOM 4 */
	cell = document.createElement("TD");
	var element = document.createElement("input");
	element.type = "text";
	element.name = "reqKuantitas[]";
	element.id = "reqKuantitas"+rownum;
	element.style.width = "50px";
	element.className='easyui-validatebox';		
	cell.appendChild(element);
	row.appendChild(cell);
	
	
		
	/* KOLOM 5 */
	cell = document.createElement("TD");
	var element = document.createElement("input");
	element.type = "text";
	element.name = "reqKuantitasSatuan[]";
	element.id = "reqKuantitasSatuan"+rownum;
	element.style.width = "100px";
	element.className='easyui-combobox';		
	cell.appendChild(element);
	row.appendChild(cell);
		
	/* KOLOM 6 */
	cell = document.createElement("TD");
	var element = document.createElement("input");
	element.type = "text";
	element.name = "reqKualitas[]";
	element.id = "reqKualitas"+rownum;
	element.style.width = "50px";
	element.className='easyui-validatebox';		
	cell.appendChild(element);
	row.appendChild(cell);
		
	/* KOLOM 7 */
	cell = document.createElement("TD");
	var element = document.createElement("input");
	element.type = "text";
	element.name = "reqWaktu[]";
	element.id = "reqWaktu"+rownum;
	element.style.width = "50px";
	element.className='easyui-validatebox';		
	cell.appendChild(element);
	row.appendChild(cell);
		
	/* KOLOM 8 */
	cell = document.createElement("TD");
	var element = document.createElement("input");
	element.type = "text";
	element.name = "reqWaktuSatuan[]";
	element.id = "reqWaktuSatuan"+rownum;
	element.style.width = "100px";
	element.className='easyui-combobox';		
	cell.appendChild(element);
	row.appendChild(cell);
		
	/* KOLOM 9 */
	cell = document.createElement("TD");
	var element = document.createElement("input");
	element.type = "text";
	element.name = "reqBiaya[]";
	element.id = "reqBiaya"+rownum;
	element.className='easyui-validatebox';	
	element.setAttribute('style', 'text-align: right; width: 100px;');
	element.onfocus = function() {  
		FormatAngka("reqBiaya"+rownum);
	};
	element.onkeyup = function() {  
		FormatUang("reqBiaya"+rownum);
	};
	element.onblur = function() {  
		FormatUang("reqBiaya"+rownum);
	};
	cell.appendChild(element);
	row.appendChild(cell);
	
		
	/* KOLOM 10 */
	cell = document.createElement("TD");
	var button = document.createElement('label');
	button.style.textAlign='center';
	button.innerHTML = '<center><a style="cursor:pointer;" onclick="deleteRowDrawTable(\'dataTableRowDinamisMain\', this)"><img src="../WEB/images/delete-icon.png" width="15" height="15" border="0" /></a></center>';
	cell.appendChild(button);
	row.appendChild(cell);
	
	tabBody.appendChild(row);
	
	var rowCount = tabBody.rows.length;
	rowCount= rowCount-1;
	
	$('#reqKuantitasSatuan'+rowCount).combobox({
		url:'../json-skp/kuantitas_satuan_combo_json.php',
		valueField:'id',
        textField:'text',
        panelHeight:'120'
	});
	
	$('#reqWaktuSatuan'+rowCount).combobox({
		url:'../json-skp/waktu_satuan_combo_json.php',
		valueField:'id',
        textField:'text',
        panelHeight:'120'
	});
	
	$('#reqAK'+rowCount).keypress(function(e) {
		if( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
		{
		return false;
		}
	});
	
	$('#reqKuantitas'+rowCount).keypress(function(e) {
		if( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
		{
		return false;
		}
	});
	
	$('#reqWaktu'+rowCount).keypress(function(e) {
		if( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
		{
		return false;
		}
	});
	
	$('#reqKualitas'+rowCount).keypress(function(e) {
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