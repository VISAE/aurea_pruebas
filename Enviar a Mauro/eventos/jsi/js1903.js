// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Cursos
// --- Modelo Versión 2.23.5 Tuesday, August 27, 2019
function cod_even03idcurso(){
	var dcod=window.document.frmedita.even03idcurso_cod.value.trim();
	window.document.frmedita.even03idcurso.value=0;
	if (dcod!=''){
		var params=new Array();
		params[0]=dcod;
		params[1]='even03idcurso';
		params[2]='div_even03idcurso';
		params[9]=window.document.frmedita.debug.value;
		xajax_f1903_Busqueda_even03idcurso(params);
		}else{
		document.getElementById('div_even03idcurso').innerHTML='';
		}
	}
function guardaf1903(){
	var params=new Array();
	var valores=new Array();
	valores[1]=window.document.frmedita.even02id.value;
	valores[2]=window.document.frmedita.even03idcurso.value;
	valores[3]=window.document.frmedita.even03id.value;
	valores[4]=window.document.frmedita.even03vigente.value;
	params[0]=window.document.frmedita.even02id.value;
	//params[1]=window.document.frmedita.p1_1903.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf1903.value;
	params[102]=window.document.frmedita.lppf1903.value;
	xajax_f1903_Guardar(valores, params);
	}
function limpiaf1903(){
	MensajeAlarmaV2('', 0);
	var params=new Array();
	xajax_f1903_PintarLlaves(params);
	window.document.frmedita.even03vigente.value='S';
	verboton('belimina1903','none');
	}
function eliminaf1903(){
	var params=new Array();
	params[0]=window.document.frmedita.even02id.value;
	params[1]=window.document.frmedita.even02id.value;
	params[2]=window.document.frmedita.even03idcurso.value;
	params[3]=window.document.frmedita.even03id.value;
	//params[6]=window.document.frmedita.p1_1903.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf1903.value;
	params[102]=window.document.frmedita.lppf1903.value;
	if (window.document.frmedita.even03id.value!=''){
		if (confirm("Esta seguro de eliminar el dato?")){
			xajax_f1903_Eliminar(params);
			}
		}
	}
function revisaf1903(){
	var params=new Array();
	params[0]=1;
	params[1]=window.document.frmedita.even02id.value;
	params[2]=window.document.frmedita.even03idcurso.value;
	params[3]=window.document.frmedita.even03id.value;
	if ((params[2]!='')){
		xajax_f1903_Traer(params);
		}
	}
function cargadatof1903(llave1){
	window.document.frmedita.even03idcurso.value=String(llave1);
	revisaf1903();
	}
function cargaridf1903(llave1){
	var params=new Array();
	params[0]=2;
	params[103]=llave1;
	xajax_f1903_Traer(params);
	expandepanel(1903,'block',0);
	}
function paginarf1903(){
	var params=new Array();
	params[0]=window.document.frmedita.even02id.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf1903.value;
	params[102]=window.document.frmedita.lppf1903.value;
	//params[103]=window.document.frmedita.bnombre1903.value;
	//params[104]=window.document.frmedita.blistar1903.value;
	document.getElementById('div_f1903detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf1903" name="paginaf1903" type="hidden" value="'+params[101]+'" /><input id="lppf1903" name="lppf1903" type="hidden" value="'+params[102]+'" />';
	xajax_f1903_HtmlTabla(params);
	}
function imprime1903(){
	window.document.frmlista.consulta.value=window.document.frmedita.consulta_1903.value;
	window.document.frmlista.titulos.value=window.document.frmedita.titulos_1903.value;
	window.document.frmlista.nombrearchivo.value='Cursos';
	window.document.frmlista.submit();
	}
