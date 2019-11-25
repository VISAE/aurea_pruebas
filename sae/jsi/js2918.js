// JavaScript Document
// --- © Cristhiam Dario Silva Chavez - UNAD - 2019 ---
// --- cristhiam.silva@unad.edu.co - http://www.unad.edu.co
// ---  
// --- Modelo Versión 2.23.7 Tuesday, October 22, 2019
function carga_combo_plab18ubidep(){
	var params=new Array();
	params[0]=window.document.frmedita.plab18ubipais.value;
	xajax_f2918_Comboplab18ubidep(params);
	}
function carga_combo_plab18ubiciudad(){
	var params=new Array();
	params[0]=window.document.frmedita.plab18ubidep.value;
	xajax_f2918_Comboplab18ubiciudad(params);
	}
function guardaf2918(){
	var params=new Array();
	var valores=new Array();
	valores[1]=window.document.frmedita.plab10id.value;
	valores[2]=window.document.frmedita.plab18consecutivo.value;
	valores[3]=window.document.frmedita.plab18id.value;
	valores[4]=window.document.frmedita.plab18ubipais.value;
	valores[5]=window.document.frmedita.plab18ubidep.value;
	valores[6]=window.document.frmedita.plab18ubiciudad.value;
	params[0]=window.document.frmedita.plab10id.value;
	//params[1]=window.document.frmedita.p1_2918.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf2918.value;
	params[102]=window.document.frmedita.lppf2918.value;
	xajax_f2918_Guardar(valores, params);
	}
function limpiaf2918(){
	MensajeAlarmaV2('', 0);
	var params=new Array();
	xajax_f2918_PintarLlaves(params);
	window.document.frmedita.plab18ubipais.value='';
	window.document.frmedita.plab18ubidep.value='';
	window.document.frmedita.plab18ubiciudad.value='';
	verboton('belimina2918','none');
	}
function eliminaf2918(){
	var params=new Array();
	params[0]=window.document.frmedita.plab10id.value;
	params[1]=window.document.frmedita.plab10id.value;
	params[2]=window.document.frmedita.plab18consecutivo.value;
	params[3]=window.document.frmedita.plab18id.value;
	//params[8]=window.document.frmedita.p1_2918.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf2918.value;
	params[102]=window.document.frmedita.lppf2918.value;
	if (window.document.frmedita.plab18id.value!=''){
		if (confirm("Esta seguro de eliminar el dato?")){
			xajax_f2918_Eliminar(params);
			}
		}
	}
function revisaf2918(){
	var params=new Array();
	params[0]=1;
	params[1]=window.document.frmedita.plab10id.value;
	params[2]=window.document.frmedita.plab18consecutivo.value;
	params[3]=window.document.frmedita.plab18id.value;
	if ((params[2]!='')){
		xajax_f2918_Traer(params);
		}
	}
function cargadatof2918(llave1){
	window.document.frmedita.plab18consecutivo.value=String(llave1);
	revisaf2918();
	}
function cargaridf2918(llave1){
	var params=new Array();
	params[0]=2;
	params[103]=llave1;
	xajax_f2918_Traer(params);
	expandepanel(2918,'block',0);
	}
function paginarf2918(){
	var params=new Array();
	params[0]=window.document.frmedita.plab10id.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf2918.value;
	params[102]=window.document.frmedita.lppf2918.value;
	//params[103]=window.document.frmedita.bnombre2918.value;
	//params[104]=window.document.frmedita.blistar2918.value;
	document.getElementById('div_f2918detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf2918" name="paginaf2918" type="hidden" value="'+params[101]+'" /><input id="lppf2918" name="lppf2918" type="hidden" value="'+params[102]+'" />';
	xajax_f2918_HtmlTabla(params);
	}
function imprime2918(){
	window.document.frmlista.consulta.value=window.document.frmedita.consulta_2918.value;
	window.document.frmlista.titulos.value=window.document.frmedita.titulos_2918.value;
	window.document.frmlista.nombrearchivo.value='';
	window.document.frmlista.submit();
	}
