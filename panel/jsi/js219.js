// JavaScript Document
// --- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
// --- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
// ---  Rangos
// --- Modelo Versión 2.23.5 Wednesday, August 14, 2019
function guardaf219(){
	var params=new Array();
	var valores=new Array();
	valores[1]=window.document.frmedita.unae18id.value;
	valores[2]=window.document.frmedita.unae19consec.value;
	valores[3]=window.document.frmedita.unae19id.value;
	valores[4]=window.document.frmedita.unae19titulo.value;
	valores[5]=window.document.frmedita.unae19base.value;
	valores[6]=window.document.frmedita.unae19techo.value;
	params[0]=window.document.frmedita.unae18id.value;
	//params[1]=window.document.frmedita.p1_219.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf219.value;
	params[102]=window.document.frmedita.lppf219.value;
	xajax_f219_Guardar(valores, params);
	}
function limpiaf219(){
	MensajeAlarmaV2('', 0);
	var params=new Array();
	xajax_f219_PintarLlaves(params);
	window.document.frmedita.unae19titulo.value='';
	window.document.frmedita.unae19base.value='';
	window.document.frmedita.unae19techo.value='';
	verboton('belimina219','none');
	}
function eliminaf219(){
	var params=new Array();
	params[0]=window.document.frmedita.unae18id.value;
	params[1]=window.document.frmedita.unae18id.value;
	params[2]=window.document.frmedita.unae19consec.value;
	params[3]=window.document.frmedita.unae19id.value;
	//params[8]=window.document.frmedita.p1_219.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf219.value;
	params[102]=window.document.frmedita.lppf219.value;
	if (window.document.frmedita.unae19id.value!=''){
		if (confirm("Esta seguro de eliminar el dato?")){
			xajax_f219_Eliminar(params);
			}
		}
	}
function revisaf219(){
	var params=new Array();
	params[0]=1;
	params[1]=window.document.frmedita.unae18id.value;
	params[2]=window.document.frmedita.unae19consec.value;
	params[3]=window.document.frmedita.unae19id.value;
	if ((params[2]!='')){
		xajax_f219_Traer(params);
		}
	}
function cargadatof219(llave1){
	window.document.frmedita.unae19consec.value=String(llave1);
	revisaf219();
	}
function cargaridf219(llave1){
	var params=new Array();
	params[0]=2;
	params[103]=llave1;
	xajax_f219_Traer(params);
	expandepanel(219,'block',0);
	}
function paginarf219(){
	var params=new Array();
	params[0]=window.document.frmedita.unae18id.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf219.value;
	params[102]=window.document.frmedita.lppf219.value;
	//params[103]=window.document.frmedita.bnombre219.value;
	//params[104]=window.document.frmedita.blistar219.value;
	document.getElementById('div_f219detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf219" name="paginaf219" type="hidden" value="'+params[101]+'" /><input id="lppf219" name="lppf219" type="hidden" value="'+params[102]+'" />';
	xajax_f219_HtmlTabla(params);
	}
function imprime219(){
	window.document.frmlista.consulta.value=window.document.frmedita.consulta_219.value;
	window.document.frmlista.titulos.value=window.document.frmedita.titulos_219.value;
	window.document.frmlista.nombrearchivo.value='Rangos';
	window.document.frmlista.submit();
	}
