// JavaScript Document
// --- © Cristhiam Dario Silva Chavez - UNAD - 2019 ---
// --- cristhiam.silva@unad.edu.co - http://www.unad.edu.co
// ---  aplicacion a oferta
// --- Modelo Versión 2.23.7 Tuesday, October 22, 2019
function guardaf2914(){
	var params=new Array();
	var valores=new Array();
	valores[1]=window.document.frmedita.plab10id.value;
	valores[2]=window.document.frmedita.plab14hv.value;
	valores[3]=window.document.frmedita.plab14id.value;
	valores[4]=window.document.frmedita.plab14fechaaplica.value;
	valores[5]=window.document.frmedita.plab14estado.value;
	valores[6]=window.document.frmedita.plab14fechacancela.value;
	valores[7]=window.document.frmedita.plab14motivocancela.value;
	params[0]=window.document.frmedita.plab10id.value;
	//params[1]=window.document.frmedita.p1_2914.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf2914.value;
	params[102]=window.document.frmedita.lppf2914.value;
	xajax_f2914_Guardar(valores, params);
	}
function limpiaf2914(){
	var sfbase=window.document.frmedita.shoy.value;
	var iFechaBaseNum=window.document.frmedita.ihoy.value;
	MensajeAlarmaV2('', 0);
	var params=new Array();
	xajax_f2914_PintarLlaves(params);
	fecha_AsignarNum('plab14fechaaplica', iFechaBaseNum);
	window.document.frmedita.plab14estado.value='';
	fecha_asignar('plab14fechacancela', sfbase);
	window.document.frmedita.plab14motivocancela.value='';
	verboton('belimina2914','none');
	}
function eliminaf2914(){
	var params=new Array();
	params[0]=window.document.frmedita.plab10id.value;
	params[1]=window.document.frmedita.plab10id.value;
	params[2]=window.document.frmedita.plab14hv.value;
	params[3]=window.document.frmedita.plab14id.value;
	//params[9]=window.document.frmedita.p1_2914.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf2914.value;
	params[102]=window.document.frmedita.lppf2914.value;
	if (window.document.frmedita.plab14id.value!=''){
		if (confirm("Esta seguro de eliminar el dato?")){
			xajax_f2914_Eliminar(params);
			}
		}
	}
function revisaf2914(){
	var params=new Array();
	params[0]=1;
	params[1]=window.document.frmedita.plab10id.value;
	params[2]=window.document.frmedita.plab14hv.value;
	params[3]=window.document.frmedita.plab14id.value;
	if ((params[2]!='')){
		xajax_f2914_Traer(params);
		}
	}
function cargadatof2914(llave1){
	window.document.frmedita.plab14hv.value=String(llave1);
	revisaf2914();
	}
function cargaridf2914(llave1){
	var params=new Array();
	params[0]=2;
	params[103]=llave1;
	xajax_f2914_Traer(params);
	expandepanel(2914,'block',0);
	}
function paginarf2914(){
	var params=new Array();
	params[0]=window.document.frmedita.plab10id.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf2914.value;
	params[102]=window.document.frmedita.lppf2914.value;
	//params[103]=window.document.frmedita.bnombre2914.value;
	//params[104]=window.document.frmedita.blistar2914.value;
	document.getElementById('div_f2914detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf2914" name="paginaf2914" type="hidden" value="'+params[101]+'" /><input id="lppf2914" name="lppf2914" type="hidden" value="'+params[102]+'" />';
	xajax_f2914_HtmlTabla(params);
	}
function imprime2914(){
	window.document.frmlista.consulta.value=window.document.frmedita.consulta_2914.value;
	window.document.frmlista.titulos.value=window.document.frmedita.titulos_2914.value;
	window.document.frmlista.nombrearchivo.value='aplicacion a oferta';
	window.document.frmlista.submit();
	}
