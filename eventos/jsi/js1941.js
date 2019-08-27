// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Categorias
// --- Modelo Versión 2.23.5 Tuesday, August 27, 2019
function guardaf1941(){
	var params=new Array();
	var valores=new Array();
	valores[1]=window.document.frmedita.even01id.value;
	valores[2]=window.document.frmedita.even41consec.value;
	valores[3]=window.document.frmedita.even41id.value;
	valores[4]=window.document.frmedita.even41activo.value;
	valores[5]=window.document.frmedita.even41titulo.value;
	params[0]=window.document.frmedita.even01id.value;
	//params[1]=window.document.frmedita.p1_1941.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf1941.value;
	params[102]=window.document.frmedita.lppf1941.value;
	xajax_f1941_Guardar(valores, params);
	}
function limpiaf1941(){
	MensajeAlarmaV2('', 0);
	var params=new Array();
	xajax_f1941_PintarLlaves(params);
	window.document.frmedita.even41activo.value='S';
	window.document.frmedita.even41titulo.value='';
	verboton('belimina1941','none');
	}
function eliminaf1941(){
	var params=new Array();
	params[0]=window.document.frmedita.even01id.value;
	params[1]=window.document.frmedita.even01id.value;
	params[2]=window.document.frmedita.even41consec.value;
	params[3]=window.document.frmedita.even41id.value;
	//params[7]=window.document.frmedita.p1_1941.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf1941.value;
	params[102]=window.document.frmedita.lppf1941.value;
	if (window.document.frmedita.even41id.value!=''){
		if (confirm("Esta seguro de eliminar el dato?")){
			xajax_f1941_Eliminar(params);
			}
		}
	}
function revisaf1941(){
	var params=new Array();
	params[0]=1;
	params[1]=window.document.frmedita.even01id.value;
	params[2]=window.document.frmedita.even41consec.value;
	params[3]=window.document.frmedita.even41id.value;
	if ((params[2]!='')){
		xajax_f1941_Traer(params);
		}
	}
function cargadatof1941(llave1){
	window.document.frmedita.even41consec.value=String(llave1);
	revisaf1941();
	}
function cargaridf1941(llave1){
	var params=new Array();
	params[0]=2;
	params[103]=llave1;
	xajax_f1941_Traer(params);
	expandepanel(1941,'block',0);
	}
function paginarf1941(){
	var params=new Array();
	params[0]=window.document.frmedita.even01id.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf1941.value;
	params[102]=window.document.frmedita.lppf1941.value;
	//params[103]=window.document.frmedita.bnombre1941.value;
	//params[104]=window.document.frmedita.blistar1941.value;
	document.getElementById('div_f1941detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf1941" name="paginaf1941" type="hidden" value="'+params[101]+'" /><input id="lppf1941" name="lppf1941" type="hidden" value="'+params[102]+'" />';
	xajax_f1941_HtmlTabla(params);
	}
function imprime1941(){
	window.document.frmlista.consulta.value=window.document.frmedita.consulta_1941.value;
	window.document.frmlista.titulos.value=window.document.frmedita.titulos_1941.value;
	window.document.frmlista.nombrearchivo.value='Categorias';
	window.document.frmlista.submit();
	}
