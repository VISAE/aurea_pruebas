// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Participantes
// --- Modelo Versión 2.23.5 Tuesday, August 27, 2019
function guardaf1904(){
	var params=new Array();
	var valores=new Array();
	valores[1]=window.document.frmedita.even02id.value;
	valores[2]=window.document.frmedita.even04idparticipante.value;
	valores[3]=window.document.frmedita.even04id.value;
	valores[4]=window.document.frmedita.even04institucion.value;
	valores[5]=window.document.frmedita.even04cargo.value;
	valores[6]=window.document.frmedita.even04correo.value;
	valores[7]=window.document.frmedita.even04telefono.value;
	valores[8]=window.document.frmedita.even04estadoasistencia.value;
	params[0]=window.document.frmedita.even02id.value;
	//params[1]=window.document.frmedita.p1_1904.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf1904.value;
	params[102]=window.document.frmedita.lppf1904.value;
	xajax_f1904_Guardar(valores, params);
	}
function limpiaf1904(){
	MensajeAlarmaV2('', 0);
	var params=new Array();
	xajax_f1904_PintarLlaves(params);
	window.document.frmedita.even04institucion.value='';
	window.document.frmedita.even04cargo.value='';
	window.document.frmedita.even04correo.value='';
	window.document.frmedita.even04telefono.value='';
	window.document.frmedita.even04estadoasistencia.value='';
	verboton('belimina1904','none');
	}
function eliminaf1904(){
	var params=new Array();
	params[0]=window.document.frmedita.even02id.value;
	params[1]=window.document.frmedita.even02id.value;
	params[2]=window.document.frmedita.even04idparticipante.value;
	params[3]=window.document.frmedita.even04id.value;
	//params[10]=window.document.frmedita.p1_1904.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf1904.value;
	params[102]=window.document.frmedita.lppf1904.value;
	if (window.document.frmedita.even04id.value!=''){
		if (confirm("Esta seguro de eliminar el dato?")){
			xajax_f1904_Eliminar(params);
			}
		}
	}
function revisaf1904(){
	var params=new Array();
	params[0]=1;
	params[1]=window.document.frmedita.even02id.value;
	params[2]=window.document.frmedita.even04idparticipante.value;
	params[3]=window.document.frmedita.even04id.value;
	if ((params[2]!='')){
		xajax_f1904_Traer(params);
		}
	}
function cargadatof1904(llave1){
	window.document.frmedita.even04idparticipante.value=String(llave1);
	revisaf1904();
	}
function cargaridf1904(llave1){
	var params=new Array();
	params[0]=2;
	params[103]=llave1;
	xajax_f1904_Traer(params);
	expandepanel(1904,'block',0);
	}
function paginarf1904(){
	var params=new Array();
	params[0]=window.document.frmedita.even02id.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf1904.value;
	params[102]=window.document.frmedita.lppf1904.value;
	//params[103]=window.document.frmedita.bnombre1904.value;
	//params[104]=window.document.frmedita.blistar1904.value;
	document.getElementById('div_f1904detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf1904" name="paginaf1904" type="hidden" value="'+params[101]+'" /><input id="lppf1904" name="lppf1904" type="hidden" value="'+params[102]+'" />';
	xajax_f1904_HtmlTabla(params);
	}
function imprime1904(){
	window.document.frmlista.consulta.value=window.document.frmedita.consulta_1904.value;
	window.document.frmlista.titulos.value=window.document.frmedita.titulos_1904.value;
	window.document.frmlista.nombrearchivo.value='Participantes';
	window.document.frmlista.submit();
	}
