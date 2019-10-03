// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Noticias
// --- Modelo Versión 2.23.5 Tuesday, August 27, 2019
function guardaf1905(){
	var params=new Array();
	var valores=new Array();
	valores[1]=window.document.frmedita.even02id.value;
	valores[2]=window.document.frmedita.even05consec.value;
	valores[3]=window.document.frmedita.even05id.value;
	valores[4]=window.document.frmedita.even05fecha.value;
	valores[5]=window.document.frmedita.even05publicar.value;
	valores[6]=window.document.frmedita.even05idtercero.value;
	valores[7]=window.document.frmedita.even05noticia.value;
	params[0]=window.document.frmedita.even02id.value;
	//params[1]=window.document.frmedita.p1_1905.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf1905.value;
	params[102]=window.document.frmedita.lppf1905.value;
	xajax_f1905_Guardar(valores, params);
	}
function limpiaf1905(){
	var sfbase=window.document.frmedita.shoy.value;
	var iFechaBaseNum=window.document.frmedita.ihoy.value;
	MensajeAlarmaV2('', 0);
	var params=new Array();
	xajax_f1905_PintarLlaves(params);
	window.document.frmedita.even05publicar.value='';
	window.document.frmedita.even05noticia.value='';
	verboton('belimina1905','none');
	}
function eliminaf1905(){
	var params=new Array();
	params[0]=window.document.frmedita.even02id.value;
	params[1]=window.document.frmedita.even02id.value;
	params[2]=window.document.frmedita.even05consec.value;
	params[3]=window.document.frmedita.even05id.value;
	//params[9]=window.document.frmedita.p1_1905.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf1905.value;
	params[102]=window.document.frmedita.lppf1905.value;
	if (window.document.frmedita.even05id.value!=''){
		if (confirm("Esta seguro de eliminar el dato?")){
			xajax_f1905_Eliminar(params);
			}
		}
	}
function revisaf1905(){
	var params=new Array();
	params[0]=1;
	params[1]=window.document.frmedita.even02id.value;
	params[2]=window.document.frmedita.even05consec.value;
	params[3]=window.document.frmedita.even05id.value;
	if ((params[2]!='')){
		xajax_f1905_Traer(params);
		}
	}
function cargadatof1905(llave1){
	window.document.frmedita.even05consec.value=String(llave1);
	revisaf1905();
	}
function cargaridf1905(llave1){
	var params=new Array();
	params[0]=2;
	params[103]=llave1;
	xajax_f1905_Traer(params);
	expandepanel(1905,'block',0);
	}
function paginarf1905(){
	var params=new Array();
	params[0]=window.document.frmedita.even02id.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf1905.value;
	params[102]=window.document.frmedita.lppf1905.value;
	//params[103]=window.document.frmedita.bnombre1905.value;
	//params[104]=window.document.frmedita.blistar1905.value;
	document.getElementById('div_f1905detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf1905" name="paginaf1905" type="hidden" value="'+params[101]+'" /><input id="lppf1905" name="lppf1905" type="hidden" value="'+params[102]+'" />';
	xajax_f1905_HtmlTabla(params);
	}
function imprime1905(){
	window.document.frmlista.consulta.value=window.document.frmedita.consulta_1905.value;
	window.document.frmlista.titulos.value=window.document.frmedita.titulos_1905.value;
	window.document.frmlista.nombrearchivo.value='Noticias';
	window.document.frmlista.submit();
	}
