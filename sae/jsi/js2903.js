// JavaScript Document
// --- © Sandra Milena Cifuentes Alfonso - Punto Software C&S S.A.S - UNAD - 2019 ---
// --- samicial@puntosoftware.net - http://www.puntosoftware.net 
// --- Desarrollo por encargo para la UNAD Contrato OS-2019-000130 
// --- Conforme a la metodología de desarrollo de la plataforma AUREA.
// ---  rango salarial
// --- Modelo Versión 2.23.7 Friday, October 18, 2019
function guardaf2903(){
	var params=new Array();
	var valores=new Array();
	valores[1]=window.document.frmedita.plab09id.value;
	valores[2]=window.document.frmedita.plab03consecutivo.value;
	valores[3]=window.document.frmedita.plab03id.value;
	valores[4]=window.document.frmedita.plab03activo.value;
	valores[5]=window.document.frmedita.plab03nombre.value;
	params[0]=window.document.frmedita.plab09id.value;
	//params[1]=window.document.frmedita.p1_2903.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf2903.value;
	params[102]=window.document.frmedita.lppf2903.value;
	xajax_f2903_Guardar(valores, params);
	}
function limpiaf2903(){
	MensajeAlarmaV2('', 0);
	var params=new Array();
	xajax_f2903_PintarLlaves(params);
	window.document.frmedita.plab03activo.value='S';
	window.document.frmedita.plab03nombre.value='';
	verboton('belimina2903','none');
	}
function eliminaf2903(){
	var params=new Array();
	params[0]=window.document.frmedita.plab09id.value;
	params[1]=window.document.frmedita.plab09id.value;
	params[2]=window.document.frmedita.plab03consecutivo.value;
	params[3]=window.document.frmedita.plab03id.value;
	//params[7]=window.document.frmedita.p1_2903.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf2903.value;
	params[102]=window.document.frmedita.lppf2903.value;
	if (window.document.frmedita.plab03id.value!=''){
		if (confirm("Esta seguro de eliminar el dato?")){
			xajax_f2903_Eliminar(params);
			}
		}
	}
function revisaf2903(){
	var params=new Array();
	params[0]=1;
	params[1]=window.document.frmedita.plab09id.value;
	params[2]=window.document.frmedita.plab03consecutivo.value;
	params[3]=window.document.frmedita.plab03id.value;
	if ((params[2]!='')){
		xajax_f2903_Traer(params);
		}
	}
function cargadatof2903(llave1){
	window.document.frmedita.plab03consecutivo.value=String(llave1);
	revisaf2903();
	}
function cargaridf2903(llave1){
	var params=new Array();
	params[0]=2;
	params[103]=llave1;
	xajax_f2903_Traer(params);
	expandepanel(2903,'block',0);
	}
function paginarf2903(){
	var params=new Array();
	params[0]=window.document.frmedita.plab09id.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf2903.value;
	params[102]=window.document.frmedita.lppf2903.value;
	//params[103]=window.document.frmedita.bnombre2903.value;
	//params[104]=window.document.frmedita.blistar2903.value;
	document.getElementById('div_f2903detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf2903" name="paginaf2903" type="hidden" value="'+params[101]+'" /><input id="lppf2903" name="lppf2903" type="hidden" value="'+params[102]+'" />';
	xajax_f2903_HtmlTabla(params);
	}
function imprime2903(){
	window.document.frmlista.consulta.value=window.document.frmedita.consulta_2903.value;
	window.document.frmlista.titulos.value=window.document.frmedita.titulos_2903.value;
	window.document.frmlista.nombrearchivo.value='rango salarial';
	window.document.frmlista.submit();
	}
