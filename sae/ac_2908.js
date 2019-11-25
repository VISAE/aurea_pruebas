// JavaScript Document
// --- © Sandra Milena Cifuentes Alfonso - Punto Software C&S S.A.S - UNAD - 2019 ---
// --- samicial@puntosoftware.net - http://www.puntosoftware.net 
// --- Desarrollo por encargo para la UNAD Contrato OS-2019-000130 
// --- Conforme a la metodología de desarrollo de la plataforma AUREA.
// --- Modelo Versión 2.23.7 Friday, October 18, 2019
$().ready(function(){
$("#plab08idtercero_doc").autocomplete("ac_111.php",{width:360,matchContains:true,no_result:'No coincidentes',selectFirst:false});
$("#plab08idtercero_doc").result(function(event, data, formatted){
	if (data[1]!=''){
		$("#plab08idtercero_td").val(data[2]);
		$("#plab08idtercero_doc").val(data[1]);
		ter_muestra('plab08idtercero', 1);
		}
	});
});