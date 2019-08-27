// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// --- Modelo Versión 2.23.5 Tuesday, August 27, 2019
$().ready(function(){
$("#even02idorganizador_doc").autocomplete("ac_111.php",{width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#even02idorganizador_doc").result(function(event, data, formatted){
	if (data[1]!=''){
		$("#even02idorganizador_td").val(data[2]);
		$("#even02idorganizador_doc").val(data[1]);
		ter_muestra('even02idorganizador', 0);
		}
	});
$("#even02idcertificado_cod").autocomplete('ac_1906.php', {width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#even02idcertificado_cod").result(function(event, data, formatted){
	if (data[1]!=''){
		$("#even02idcertificado_cod").val(data[1]);
		cod_even02idcertificado();
		}
	});
$("#even02idrubrica_cod").autocomplete('ac_0.php', {width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#even02idrubrica_cod").result(function(event, data, formatted){
	if (data[1]!=''){
		$("#even02idrubrica_cod").val(data[1]);
		cod_even02idrubrica();
		}
	});
$("#even03idcurso_cod").autocomplete('ac_140.php', {width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#even03idcurso_cod").result(function(event, data, formatted){
	if (data[1]!=''){
		$("#even03idcurso_cod").val(data[1]);
		cod_even03idcurso();
		}
	});
$("#even04idparticipante_doc").autocomplete("ac_111.php",{width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#even04idparticipante_doc").result(function(event, data, formatted){
	if (data[1]!=''){
		$("#even04idparticipante_td").val(data[2]);
		$("#even04idparticipante_doc").val(data[1]);
		ter_muestra('even04idparticipante', 1);
		}
	});
$("#even05idtercero_doc").autocomplete("ac_111.php",{width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#even05idtercero_doc").result(function(event, data, formatted){
	if (data[1]!=''){
		$("#even05idtercero_td").val(data[2]);
		$("#even05idtercero_doc").val(data[1]);
		ter_muestra('even05idtercero', 0);
		}
	});
});