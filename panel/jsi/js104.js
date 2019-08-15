// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2018 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Permiso por modulo
// --- Modelo Versión 2.22.6b miércoles, 5 de diciembre de 2018
function guardaf104(){
	var params=new Array();
	var valores=new Array();
	valores[1]=window.document.frmedita.unad02id.value;
	valores[2]=window.document.frmedita.unad04idpermiso.value;
	valores[3]=window.document.frmedita.unad04vigente.value;
	params[0]=window.document.frmedita.unad02id.value;
	//params[1]=window.document.frmedita.p1_104.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf104.value;
	params[102]=window.document.frmedita.lppf104.value;
	xajax_f104_Guardar(valores, params);
	}
function limpiaf104(){
	MensajeAlarmaV2('', 0);
	var params=new Array();
	xajax_f104_PintarLlaves(params);
	window.document.frmedita.unad04vigente.value='S';
	verboton('belimina104','none');
	}
function eliminaf104(){
	var params=new Array();
	params[0]=window.document.frmedita.unad02id.value;
	params[1]=window.document.frmedita.unad02id.value;
	params[2]=window.document.frmedita.unad04idpermiso.value;
	//params[5]=window.document.frmedita.p1_104.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf104.value;
	params[102]=window.document.frmedita.lppf104.value;
	if (window.document.frmedita.unad04idpermiso.value!=''){
		if (confirm("Esta seguro de eliminar el dato?")){
			xajax_f104_Eliminar(params);
			}
		}
	}
function revisaf104(){
	var params=new Array();
	params[0]=1;
	params[1]=window.document.frmedita.unad02id.value;
	params[2]=window.document.frmedita.unad04idpermiso.value;
	if ((params[2]!='')){
		xajax_f104_Traer(params);
		}
	}
function cargadatof104(llave1){
	window.document.frmedita.unad04idpermiso.value=String(llave1);
	revisaf104();
	}
function cargaridf104(llave1){
	var params=new Array();
	params[0]=1;
	params[1]=window.document.frmedita.unad02id.value;
	params[2]=llave1;
	xajax_f104_Traer(params);
	expandepanel(104,'block',0);
	}
function paginarf104(){
	var params=new Array();
	params[0]=window.document.frmedita.unad02id.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf104.value;
	params[102]=window.document.frmedita.lppf104.value;
	//params[103]=window.document.frmedita.bnombre104.value;
	//params[104]=window.document.frmedita.blistar104.value;
	//document.getElementById('div_f104detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf104" name="paginaf104" type="hidden" value="'+params[101]+'" /><input id="lppf104" name="lppf104" type="hidden" value="'+params[102]+'" />';
	xajax_f104_HtmlTabla(params);
	}
function imprime104(){
	window.document.frmlista.consulta.value=window.document.frmedita.consulta_104.value;
	window.document.frmlista.titulos.value=window.document.frmedita.titulos_104.value;
	window.document.frmlista.nombrearchivo.value='Permiso por modulo';
	window.document.frmlista.submit();
	}
