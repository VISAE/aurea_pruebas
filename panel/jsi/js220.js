// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Distribucion
// --- Modelo Versión 2.23.5 Wednesday, August 14, 2019
function guardaf220(){
	var params=new Array();
	var valores=new Array();
	valores[1]=window.document.frmedita.unae18id.value;
	valores[2]=window.document.frmedita.unae20edad.value;
	valores[3]=window.document.frmedita.unae20id.value;
	valores[4]=window.document.frmedita.unae20idrango.value;
	params[0]=window.document.frmedita.unae18id.value;
	//params[1]=window.document.frmedita.p1_220.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf220.value;
	params[102]=window.document.frmedita.lppf220.value;
	xajax_f220_Guardar(valores, params);
	}
function limpiaf220(){
	MensajeAlarmaV2('', 0);
	var params=new Array();
	xajax_f220_PintarLlaves(params);
	window.document.frmedita.unae20idrango.value='';
	verboton('belimina220','none');
	}
function eliminaf220(){
	var params=new Array();
	params[0]=window.document.frmedita.unae18id.value;
	params[1]=window.document.frmedita.unae18id.value;
	params[2]=window.document.frmedita.unae20edad.value;
	params[3]=window.document.frmedita.unae20id.value;
	//params[6]=window.document.frmedita.p1_220.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf220.value;
	params[102]=window.document.frmedita.lppf220.value;
	if (window.document.frmedita.unae20id.value!=''){
		if (confirm("Esta seguro de eliminar el dato?")){
			xajax_f220_Eliminar(params);
			}
		}
	}
function revisaf220(){
	var params=new Array();
	params[0]=1;
	params[1]=window.document.frmedita.unae18id.value;
	params[2]=window.document.frmedita.unae20edad.value;
	params[3]=window.document.frmedita.unae20id.value;
	if ((params[2]!='')){
		xajax_f220_Traer(params);
		}
	}
function cargadatof220(llave1){
	window.document.frmedita.unae20edad.value=String(llave1);
	revisaf220();
	}
function cargaridf220(llave1){
	var params=new Array();
	params[0]=2;
	params[103]=llave1;
	xajax_f220_Traer(params);
	expandepanel(220,'block',0);
	}
function paginarf220(){
	var params=new Array();
	params[0]=window.document.frmedita.unae18id.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf220.value;
	params[102]=window.document.frmedita.lppf220.value;
	//params[103]=window.document.frmedita.bnombre220.value;
	//params[104]=window.document.frmedita.blistar220.value;
	document.getElementById('div_f220detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf220" name="paginaf220" type="hidden" value="'+params[101]+'" /><input id="lppf220" name="lppf220" type="hidden" value="'+params[102]+'" />';
	xajax_f220_HtmlTabla(params);
	}
function imprime220(){
	window.document.frmlista.consulta.value=window.document.frmedita.consulta_220.value;
	window.document.frmlista.titulos.value=window.document.frmedita.titulos_220.value;
	window.document.frmlista.nombrearchivo.value='Distribucion';
	window.document.frmlista.submit();
	}
