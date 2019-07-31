<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2014 - 2018 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 0.4.0 viernes, 07 de febrero de 2014
--- Modelo Version 1.0.0 jueves, 03 de abril de 2014
--- Modelo Versión 2.7.9 domingo, 07 de junio de 2015
--- Modelo Versión 2.9.7 lunes, 23 de noviembre de 2015
--- Modelo Versión 2.18.1 viernes, 26 de mayo de 2017 
--- Modelo Versión 2.22.3 miércoles, 15 de agosto de 2018
*/
if (file_exists('./err_control.php')){require './err_control.php';}
$bDebug=false;
$sDebug='';
if (isset($_REQUEST['debug'])!=0){
	if ($_REQUEST['debug']==1){$bDebug=true;}
	}else{
	$_REQUEST['debug']=0;
	}
if ($bDebug){
	$iSegIni=microtime(true);
	$iSegundos=floor($iSegIni);
	$sMili=floor(($iSegIni-$iSegundos)*1000);
	if ($sMili<100){if ($sMili<10){$sMili=':00'.$sMili;}else{$sMili=':0'.$sMili;}}else{$sMili=':'.$sMili;}
	$sDebug=$sDebug.''.date('H:i:s').$sMili.' Inicia pagina <br>';
	}
if (!file_exists('./app.php')){
	echo '<b>Error N 1 de instalaci&oacute;n</b><br>No se ha establecido un archivo de configuraci&oacute;n, por favor comuniquese con el administrador del sistema.';
	die();
	}
mb_internal_encoding('UTF-8');
require './app.php';
require $APP->rutacomun.'unad_sesion.php';
if (isset($APP->https)==0){$APP->https=0;}
if ($APP->https==2){
	$bObliga=false;
	if (isset($_SERVER['HTTPS'])==0){
		$bObliga=true;
		}else{
		if ($_SERVER['HTTPS']!='on'){$bObliga=true;}
		}
	if ($bObliga){
		$pageURL='https://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
		header('Location:'.$pageURL);
		die();
		}
	}
//if (!file_exists('./opts.php')){require './opts.php';if ($OPT->opcion==1){$bOpcion=true;}}
$bPeticionXAJAX=false;
if ($_SERVER['REQUEST_METHOD']=='POST'){if (isset($_POST['xjxfun'])){$bPeticionXAJAX=true;}}
if (!$bPeticionXAJAX){$_SESSION['u_ultimominuto']=(date('W')*1440)+(date('H')*60)+date('i');}
require $APP->rutacomun.'unad_todas.php';
require $APP->rutacomun.'libs/clsdbadmin.php';
require $APP->rutacomun.'unad_librerias.php';
require $APP->rutacomun.'libhtml.php';
require $APP->rutacomun.'xajax/xajax_core/xajax.inc.php';
require $APP->rutacomun.'unad_xajax.php';
if (($bPeticionXAJAX)&&($_SESSION['unad_id_tercero']==0)){
	// viene por xajax.
	$xajax=new xajax();
	$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
	$xajax->register(XAJAX_FUNCTION,'sesion_abandona_V2');
	$xajax->processRequest();
	die();
	}
$grupo_id=1;
// -- 107 unad07usuarios
require $APP->rutacomun.'lib107.php';
$iCodModulo=f107_CodModulo($APP->idsistema);
$audita[1]=false;
$audita[2]=true;
$audita[3]=true;
$audita[4]=true;
$audita[5]=false;
// -- Se cargan los archivos de idioma
$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
$mensajes_107=$APP->rutacomun.'lg/lg_107_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_107)){$mensajes_107=$APP->rutacomun.'lg/lg_107_es.php';}
require $mensajes_todas;
require $mensajes_107;
$xajax=NULL;
$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
if (isset($APP->piel)==0){$APP->piel=1;}
$iPiel=$APP->piel;
$iPiel=1; //Piel 2018.
if (!seg_revisa_permiso($iCodModulo, 1, $objDB)){
	header('Location:nopermiso.php');
	die();
	}
if (!$bPeticionXAJAX){
	if (noticias_pendientes($objDB)){
		$objDB->CerrarConexion();
		header('Location:noticia.php?ret=unadusuarios.php');
		die();
		}
	}
//PROCESOS DE LA PAGINA
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso'])==0){
	$_REQUEST['paso']=-1;
	if ($audita[1]){seg_auditaingreso($iCodModulo, $_SESSION['unad_id_tercero'], $objDB);}
	}
$xajax=new xajax();
$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
$xajax->register(XAJAX_FUNCTION,'unad11_Mostrar_v2');
$xajax->register(XAJAX_FUNCTION,'unad11_TraerXid');
$xajax->register(XAJAX_FUNCTION,'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION,'sesion_mantener');
$xajax->register(XAJAX_FUNCTION,'f107_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f107_ExisteDato');
$xajax->register(XAJAX_FUNCTION,'f107_Busquedas');
$xajax->register(XAJAX_FUNCTION,'f107_HtmlBusqueda');
$xajax->register(XAJAX_FUNCTION,'frevisa_HtmlTabla');
$xajax->processRequest();
if ($bPeticionXAJAX){
	die(); // Esto hace que las llamadas por xajax terminen aquí.
	}
$bcargo=false;
$sError='';
$sErrorCerrando='';
$iTipoError=0;
$bLimpiaHijos=false;
$bMueveScroll=false;
$iSector=1;
// -- Se inicializan las variables, primero las que controlan la visualización de la página.
if (isset($_REQUEST['iscroll'])==0){$_REQUEST['iscroll']=0;}
if (isset($_REQUEST['paginaf107'])==0){$_REQUEST['paginaf107']=1;}
if (isset($_REQUEST['lppf107'])==0){$_REQUEST['lppf107']=20;}
if (isset($_REQUEST['boculta107'])==0){$_REQUEST['boculta107']=1;}
if (isset($_REQUEST['boculta99'])==0){$_REQUEST['boculta99']=1;}
// -- Inicializar variables de datos.
if (isset($_REQUEST['unad07idperfil'])==0){$_REQUEST['unad07idperfil']='';}
if (isset($_REQUEST['unad07idtercero'])==0){$_REQUEST['unad07idtercero']=0;}//$_SESSION['unad_id_tercero'];}
if (isset($_REQUEST['unad07idtercero_td'])==0){$_REQUEST['unad07idtercero_td']=$APP->tipo_doc;}
if (isset($_REQUEST['unad07idtercero_doc'])==0){$_REQUEST['unad07idtercero_doc']='';}
if (isset($_REQUEST['unad07vigente'])==0){$_REQUEST['unad07vigente']='S';}
if (isset($_REQUEST['unad07fechavence'])==0){$_REQUEST['unad07fechavence']='';}//fecha_hoy();}

if (isset($_REQUEST['csv_separa'])==0){$_REQUEST['csv_separa']=',';}
if (isset($_REQUEST['brevdocumento'])==0){$_REQUEST['brevdocumento']='';}
if (isset($_REQUEST['brevmodulo'])==0){$_REQUEST['brevmodulo']='';}
if (isset($_REQUEST['bdocumento'])==0){$_REQUEST['bdocumento']='';}
if (isset($_REQUEST['brazonsocial'])==0){$_REQUEST['brazonsocial']='';}
if (isset($_REQUEST['masidperfil'])==0){$_REQUEST['masidperfil']='';}
if (isset($_REQUEST['bperfil'])==0){$_REQUEST['bperfil']='';}
//Si Modifica o Elimina Cargar los campos
if (($_REQUEST['paso']==1)||($_REQUEST['paso']==3)){
	$_REQUEST['unad07idtercero_td']=$APP->tipo_doc;
	$_REQUEST['unad07idtercero_doc']='';
	$sSQL='SELECT * FROM unad07usuarios WHERE unad07idperfil='.$_REQUEST['unad07idperfil'].' AND unad07idtercero="'.$_REQUEST['unad07idtercero'].'"';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$_REQUEST['unad07idperfil']=$fila['unad07idperfil'];
		$_REQUEST['unad07idtercero']=$fila['unad07idtercero'];
		$_REQUEST['unad07vigente']=$fila['unad07vigente'];
		$_REQUEST['unad07fechavence']=$fila['unad07fechavence'];
		$bcargo=true;
		$_REQUEST['paso']=2;
		$_REQUEST['boculta107']=0;
		$bLimpiaHijos=true;
		}else{
		$_REQUEST['paso']=0;
		}
	}
//Insertar o modificar un elemento
if (($_REQUEST['paso']==10)||($_REQUEST['paso']==12)){
	$bMueveScroll=true;
	list($_REQUEST, $sError, $iTipoError, $sDebugGuardar)=f107_db_GuardarV2($_REQUEST, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugGuardar;
	if ($sError==''){
		$sError='<b>'.$ETI['msg_itemguardado'].'</b>';
		$iTipoError=1;
		}
	}
//Eliminar un elemento
if ($_REQUEST['paso']==13){
	$_REQUEST['paso']=2;
	$_REQUEST['unad07idperfil']=numeros_validar($_REQUEST['unad07idperfil']);
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sWhere='unad07idperfil='.$_REQUEST['unad07idperfil'].' AND unad07idtercero="'.$_REQUEST['unad07idtercero'].'"';
		$sql='DELETE FROM unad07usuarios WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sql);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sql.' -->';
			}else{
			if ($audita[4]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, 0, $sWhere, $objDB);}
			$_REQUEST['paso']=-1;
			$sError=$ETI['msg_itemeliminado'];
			$iTipoError=1;
			}
		}
	}
if (($_REQUEST['paso']==50)){
	$_REQUEST['paso']=2;
	if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){
		$sError=$ERR['2'];
		}
	if ($sError==''){
		list($sError, $iTipoError, $sDebugP)=f107_ProcesarArchivo($_REQUEST, $_FILES, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugP;
		}
	}
//limpiar la pantalla
if ($_REQUEST['paso']==-1){
	$_REQUEST['unad07idperfil']='';
	$_REQUEST['unad07idtercero']=0;//$_SESSION['unad_id_tercero'];
	$_REQUEST['unad07idtercero_td']=$APP->tipo_doc;
	$_REQUEST['unad07idtercero_doc']='';
	$_REQUEST['unad07vigente']='S';
	$_REQUEST['unad07fechavence']='';//fecha_hoy();
	$_REQUEST['paso']=0;
	}
if ($bLimpiaHijos){
	}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
//DATOS PARA COMPLETAR EL FORMULARIO
//Crear los controles que requieran llamado a base de datos
$sWherePerfil='unad05reservado="N"';
if ($APP->idsistema!=1){
	$sWherePerfil='unad05aplicativo='.$APP->idsistema.' AND unad05delegable="S" AND '.$sWherePerfil;
	}
$objCombos=new clsHtmlCombos('n');
list($unad07idtercero_rs, $_REQUEST['unad07idtercero'], $_REQUEST['unad07idtercero_td'], $_REQUEST['unad07idtercero_doc'])=html_tercero($_REQUEST['unad07idtercero_td'], $_REQUEST['unad07idtercero_doc'], $_REQUEST['unad07idtercero'], 0, $objDB);
if ((int)$_REQUEST['paso']==0){
	$html_unad07idperfil=html_combo('unad07idperfil', 'unad05id', 'unad05nombre', 'unad05perfiles', $sWherePerfil, 'unad05nombre', $_REQUEST['unad07idperfil'], $objDB, 'RevisaLlave()', true, '{'.$ETI['msg_seleccione'].'}', '');
	}else{
	list($unad07idperfil_nombre, $sErrorDet)=tabla_campoxid('unad05perfiles','unad05nombre','unad05id',$_REQUEST['unad07idperfil'],'{'.$ETI['msg_sindato'].'}', $objDB);
	$html_unad07idperfil=html_oculto('unad07idperfil', $_REQUEST['unad07idperfil'], $unad07idperfil_nombre);
	}
//Alistar datos adicionales
if ($APP->idsistema==1){
	$objCombos->nuevo('masidperfil', $_REQUEST['masidperfil'], true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='';
	$html_masidperfil=$objCombos->html('SELECT unad05id AS id, unad05nombre AS nombre FROM unad05perfiles ORDER BY unad05nombre ', $objDB);
	}
if ($APP->idsistema==1){$sWherePerfil='';}
$html_bperfil=html_combo('bperfil', 'unad05id', 'unad05nombre', 'unad05perfiles', $sWherePerfil, 'unad05nombre', $_REQUEST['bperfil'], $objDB, 'paginarf107()', true, '{'.$ETI['msg_todos'].'}', '');
$id_rpt=0;
//Permisos adicionales
$seg_5=0;
$seg_6=0;
if (seg_revisa_permiso($iCodModulo, 6, $objDB)){$seg_6=1;}
if ($seg_6==1){}
if (false){
	$objCombos->nuevo('csv_separa', $_REQUEST['csv_separa'], false);
	$objCombos->addItem(',', $ETI['msg_coma']);
	$objCombos->addItem(';', $ETI['msg_puntoycoma']);
	$csv_separa='<label class="Label90">'.$ETI['msg_separador'].'</label><label class="Label130">'.$objCombos->html('', $objDB).'</label>';
	}else{
	$csv_separa='<input id="csv_separa" name="csv_separa" type="hidden" value="," />';
	}
$iNumFormatosImprime=0;
$iModeloReporte=107;
$html_iFormatoImprime='<input id="iformatoimprime" name="iformatoimprime" type="hidden" value="0" />
';
if ($_REQUEST['paso']>0){
	if (seg_revisa_permiso($iCodModulo, 5, $objDB)){
		$seg_5=1;
		}
	}
if ($APP->idsistema==1){
	//Cargar las tablas de datos
	$aParametros[101]=1;//$_REQUEST['paginaf107'];
	$aParametros[102]=20;//$_REQUEST['lppf107'];
	$aParametros[103]=$_REQUEST['brevdocumento'];
	$aParametros[104]=$_REQUEST['brevmodulo'];
	$sTabla107rev=frevisa_TablaDetalle($aParametros, $objDB);
	}

$aParametros[101]=$_REQUEST['paginaf107'];
$aParametros[102]=$_REQUEST['lppf107'];
$aParametros[103]=$APP->idsistema;
$aParametros[111]=$_REQUEST['bdocumento'];
$aParametros[112]=$_REQUEST['brazonsocial'];
$aParametros[113]=$_REQUEST['bperfil'];
list($sTabla107, $sDebugTabla)=f107_TablaDetalleV2($aParametros, $objDB, $bDebug);
$sDebug=$sDebug.$sDebugTabla;
list($et_menu, $sDebugM)=html_menuV2($APP->idsistema, $objDB, $iPiel, $bDebug);
$objDB->CerrarConexion();
//FORMA
require $APP->rutacomun.'unad_forma_v2.php';
forma_cabeceraV3($xajax, $ETI['titulo_107']);
echo $et_menu;
forma_mitad();
if (false){
?>
<link rel="stylesheet" href="../ulib/css/criticalPath.css" type="text/css"/>
<link rel="stylesheet" href="../ulib/css/principal.css" type="text/css"/>
<link rel="stylesheet" href="../ulib/unad_estilos2018.css" type="text/css"/>
<?php
	}
?>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>css/criticalPath.css" type="text/css"/>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>css/principal.css" type="text/css"/>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>unad_estilos2018.css" type="text/css"/>
<?php
?>
<script language="javascript">
<!--
function limpiapagina(){
	expandesector(98);
	window.document.frmedita.paso.value=-1;
	window.document.frmedita.submit();
	}
function enviaguardar(){
	window.document.frmedita.iscroll.value=window.pageYOffset;
	expandesector(98);
	var dpaso=window.document.frmedita.paso;
	if (dpaso.value==0){
		dpaso.value=10;
		}else{
		dpaso.value=12;
		}
	window.document.frmedita.submit();
	}
function cambiapagina(){
	expandesector(98);
	window.document.frmedita.submit();
	}
function cambiapaginaV2(){
	expandesector(98);
	window.document.frmedita.paso.value=1;
	window.document.frmedita.submit();
	}
function expandepanel(codigo,estado,valor){
	var objdiv= document.getElementById('div_p'+codigo);
	var objban= document.getElementById('boculta'+codigo);
	var otroestado='none';
	if (estado=='none'){otroestado='block';}
	objdiv.style.display=estado;
	objban.value=valor;
	verboton('btrecoge'+codigo,estado);
	verboton('btexpande'+codigo,otroestado);
	}
function verboton(idboton,estado){
	var objbt=document.getElementById(idboton);
	objbt.style.display=estado;
	}
function expandesector(codigo){
	document.getElementById('div_sector1').style.display='none';
	document.getElementById('div_sector2').style.display='none';
	document.getElementById('div_sector95').style.display='none';
	document.getElementById('div_sector96').style.display='none';
	document.getElementById('div_sector97').style.display='none';
	document.getElementById('div_sector98').style.display='none';
	document.getElementById('div_sector'+codigo).style.display='block';
	var sEst='none';
	if (codigo==1){sEst='block';}
	document.getElementById('cmdGuardarf').style.display=sEst;
	}
function ter_retorna(){
	var sRetorna=window.document.frmedita.div96v2.value;
	if (sRetorna!=''){
		var idcampo=window.document.frmedita.div96campo.value;
		var illave=window.document.frmedita.div96llave.value;
		var did=document.getElementById(idcampo);
		var dtd=document.getElementById(idcampo+'_td');
		var ddoc=document.getElementById(idcampo+'_doc');
		dtd.value=window.document.frmedita.div96v1.value;
		ddoc.value=sRetorna;
		did.value=window.document.frmedita.div96v3.value;
		ter_muestra(idcampo, illave);
		}
	MensajeAlarmaV2('', 0);
	retornacontrol();
	}
function ter_muestra(idcampo, illave){
	var params=new Array();
	params[1]=document.getElementById(idcampo+'_doc').value;
	if (params[1]!=''){
		params[0]=document.getElementById(idcampo+'_td').value;
		params[2]=idcampo;
		params[3]='div_'+idcampo;
		if (illave==1){params[4]='RevisaLlave';}
		//if (illave==1){params[5]='FuncionCuandoNoEsta';}
		xajax_unad11_Mostrar_v2(params);
		}else{
		document.getElementById(idcampo).value=0;
		document.getElementById('div_'+idcampo).innerHTML='';
		//FuncionCuandoNoHayNada
		}
	}
function ter_traerxid(idcampo, vrcampo){
	var params=new Array();
	params[0]=vrcampo;
	params[1]=idcampo;
	if (params[0]!=0){
		if (idcampo=='unad07idtercero'){
			params[4]='RevisaLlave';
			}
		xajax_unad11_TraerXid(params);
		}
	}
function imprimelista(){
	if (window.document.frmedita.seg_6.value==1){
		window.document.frmlista.consulta.value=window.document.frmedita.consulta_107.value;
		window.document.frmlista.titulos.value=window.document.frmedita.titulos_107.value;
		window.document.frmlista.nombrearchivo.value='Usuarios';
		window.document.frmlista.submit();
		}else{
		window.alert("<?php echo $ERR['6']; ?>");
		}
	}
function asignarvariables(){
	//window.document.frmimpp.v1.value=window.document.frmedita.bnombre.value;
	//window.document.frmimpp.separa.value=window.document.frmedita.csv_separa.value.trim();
	}
function imprimeexcel(){
	if (window.document.frmedita.seg_6.value==1){
		asignarvariables();
		window.document.frmimpp.action='<?php echo $APP->rutacomun; ?>e107.php';
		window.document.frmimpp.submit();
		}else{
		window.alert("<?php echo $ERR['6']; ?>");
		}
	}
function imprimep(){
	if (window.document.frmedita.seg_5.value==1){
		asignarvariables();
		window.document.frmimpp.action='<?php echo $APP->rutacomun; ?>p107.php';
		window.document.frmimpp.submit();
		}else{
		window.alert("<?php echo $ERR['5']; ?>");
		}
	}
function verrpt(){
	window.document.frmimprime.submit();
	}
function eliminadato(){
	if (confirm("<?php echo $ETI['confirma_eliminar']; ?>?")){
		expandesector(98);
		window.document.frmedita.paso.value=13;
		window.document.frmedita.submit();
		}
	}
function RevisaLlave(){
	var datos= new Array();
	datos[1]=window.document.frmedita.unad07idperfil.value;
	datos[2]=window.document.frmedita.unad07idtercero.value;
	if ((datos[1]!='')&&(datos[2]!='')){
		xajax_f107_ExisteDato(datos);
		}
	}
function cargadato(llave1, llave2){
	window.document.frmedita.unad07idperfil.value=String(llave1);
	window.document.frmedita.unad07idtercero.value=String(llave2);
	window.document.frmedita.paso.value=1;
	window.document.frmedita.submit();
	}
function paginarf107(){
	var params=new Array();
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf107.value;
	params[102]=window.document.frmedita.lppf107.value;
	params[103]=<?php echo $APP->idsistema; ?>;
	params[111]=window.document.frmedita.bdocumento.value;
	params[112]=window.document.frmedita.brazonsocial.value;
	params[113]=window.document.frmedita.bperfil.value;
	xajax_f107_HtmlTabla(params);
	}
function f107_cargamasiva(){
	extensiones_permitidas=new Array(".xls", ".xlsx");
	var sError='';
	var archivo=window.document.frmedita.archivodatos.value;
	if (sError==''){
		if (!archivo){
			sError = "No has seleccionado ning\u00fan archivo";
			}
		}
	if (sError==''){
		//recupero la extensión de este nombre de archivo
		extension = (archivo.substring(archivo.lastIndexOf("."))).toLowerCase();
		//compruebo si la extensión está entre las permitidas
		permitida=false;
		for (var i=0; i<extensiones_permitidas.length; i++){
			if (extensiones_permitidas[i] == extension){
				permitida = true;
				break;
				}
			}
	if (!permitida) {
		sError="Comprueba la extensi\u00f3n de los archivos a subir. \nS\u00f3lo se pueden subir archivos con extensiones: " + extensiones_permitidas.join();
		}else{
		expandesector(98);
		window.document.frmedita.paso.value=50;
		window.document.frmedita.submit();
		return 1;
		}
	}
	//si estoy aqui es que no se ha podido submitir
	alert (sError);
	return 0;
	}
function revfoco(objeto){
	setTimeout(function(){objeto.focus();},10);
	}
function siguienteobjeto(){}
document.onkeydown=function(e){
	if (document.all){
		if (event.keyCode==13){event.keyCode=9;}
		}else{
		if (e.which==13){siguienteobjeto();}
		}
	}
function objinicial(){
	document.getElementById("unad07idperfil").focus();
	}
function buscarV2016(sCampo){
	window.document.frmedita.iscroll.value=window.pageYOffset;
	expandesector(98);
	window.document.frmedita.scampobusca.value=sCampo;
	var params=new Array();
	params[1]=sCampo;
	//params[2]=window.document.frmedita.iagno.value;
	//params[3]=window.document.frmedita.itipo.value;
	xajax_f107_Busquedas(params);
	}
function retornacontrol(){
	expandesector(1);
	window.scrollTo(0, window.document.frmedita.iscroll.value);
	}
function Devuelve(sValor){
	var sCampo=window.document.frmedita.scampobusca.value;
	if (sCampo=='unad07idtercero'){
		ter_traerxid('unad07idtercero', sValor);
		}
	retornacontrol();
	}
function mantener_sesion(){xajax_sesion_mantener();}
setInterval ('xajax_sesion_abandona_V2();', 60000);
function AyudaLocal(sCampo){
	var divAyuda=document.getElementById('div_ayuda_'+sCampo);
	if (typeof divAyuda==='undefined'){
		}else{
		verboton('cmdAyuda_'+sCampo, 'none');
		var sMensaje='Lo que quiera decir.';
		//if (sCampo=='sNombreCampo'){sMensaje='Mensaje para otro campo.';}
		divAyuda.innerHTML=sMensaje;
		divAyuda.style.display='block';
		}
	}
function cierraDiv96(ref){
	var sRetorna=window.document.frmedita.div96v2.value;
	MensajeAlarmaV2('', 0);
	retornacontrol();
	}
function paginarfrevisa(){
	var params=new Array();
	//params[0]=window.document.frmedita.p1_107.value;
	params[101]=1;
	params[102]=20;
	params[103]=window.document.frmedita.brevdocumento.value;
	params[104]=window.document.frmedita.brevmodulo.value;
	xajax_frevisa_HtmlTabla(params);
	}
// -->
</script>
<form id="frmlista" name="frmlista" method="post" action="listados.php" target="_blank">
<input id="titulos" name="titulos" type="hidden" value="" />
<input id="consulta" name="consulta" type="hidden" value="" />
<input id="nombrearchivo" name="nombrearchivo" type="hidden" value="" />
</form>
<div id="interna">
<form id="frmedita" name="frmedita" method="post" action="" enctype="multipart/form-data" autocomplete="off">
<input id="bNoAutocompletar" name="bNoAutocompletar" type="password" value="" style="display:none;"/>
<input id="paso" name="paso" type="hidden" value="<?php echo $_REQUEST['paso']; ?>" />
<input id="shoy" name="shoy" type="hidden" value="<?php echo fecha_hoy(); ?>" />
<input id="ihoy" name="ihoy" type="hidden" value="<?php echo fecha_DiaMod(); ?>" />
<input id="shora" name="shora" type="hidden" value="<?php echo fecha_hora(); ?>" />
<input id="stipodoc" name="stipodoc" type="hidden" value="<?php echo $APP->tipo_doc; ?>" />
<input id="idusuario" name="idusuario" type="hidden" value="<?php echo $_SESSION['unad_id_tercero']; ?>" />
<input id="seg_5" name="seg_5" type="hidden" value="<?php echo $seg_5; ?>" />
<input id="seg_6" name="seg_6" type="hidden" value="<?php echo $seg_6; ?>" />
<div id="div_sector1">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda" name="cmdAyuda" type="button" class="btUpAyuda" onclick="muestraayuda(<?php echo $APP->idsistema.', '.$iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
<?php
if ($_REQUEST['paso']==2){
?>
<input id="cmdEliminar" name="cmdEliminar" type="button" class="btUpEliminar" onclick="eliminadato();" title="<?php echo $ETI['bt_eliminar']; ?>" value="<?php echo $ETI['bt_eliminar']; ?>"/>
<?php
	}
$bHayImprimir=false;
$sScript='imprimelista()';
$sClaseBoton='btEnviarExcel';
if ($seg_6==1){$bHayImprimir=true;}
if ($_REQUEST['paso']!=0){
	if ($seg_5==1){
		//$bHayImprimir=true;
		//$sScript='imprimep()';
		//$sClaseBoton='btEnviarPDF'; //btUpPrint
		//if ($id_rpt!=0){$sScript='verrpt()';}
		}
	}
if ($bHayImprimir){
?>
<input id="cmdImprimir" name="cmdImprimir" type="button" class="<?php echo $sClaseBoton; ?>" onclick="<?php echo $sScript; ?>" title="<?php echo $ETI['bt_imprimir']; ?>" value="<?php echo $ETI['bt_imprimir']; ?>"/>
<?php
	}
?>
<input id="cmdLimpiar" name="cmdLimpiar" type="button" class="btUpLimpiar" onclick="limpiapagina();" title="<?php echo $ETI['bt_limpiar']; ?>" value="<?php echo $ETI['bt_limpiar']; ?>"/>
<input id="cmdGuardar" name="cmdGuardar" type="button" class="btUpGuardar" onclick="enviaguardar();" title="<?php echo $ETI['bt_guardar']; ?>" value="<?php echo $ETI['bt_guardar']; ?>"/>
</div>
<div class="titulosI">
<?php
echo '<h2>'.$ETI['titulo_107'].'</h2>';
?>
</div>
</div>
<div class="areaform">
<div class="areatrabajo">
<div class="GrupoCampos450">
<label class="Label130">
<?php
echo $ETI['unad07idperfil'];
?>
</label>
<label>
<?php
echo $html_unad07idperfil;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['unad07fechavence'];
?>
</label>
<div class="Campo220">
<?php
echo html_fecha("unad07fechavence", $_REQUEST['unad07fechavence'], true);
?>
</div>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['unad07vigente'];
?>
</label>
<label class="Label60">
<?php
	echo html_sino('unad07vigente', $_REQUEST['unad07vigente']);
?>
</label>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['unad07idtercero'];
?>
</label>
<div class="salto1px"></div>
<input id="unad07idtercero" name="unad07idtercero" type="hidden" value="<?php echo $_REQUEST['unad07idtercero']; ?>"/>
<div id="div_unad07idtercero_llaves">
<?php
$bOculto=true;
if ($_REQUEST['paso']!=2){$bOculto=false;}
echo html_DivTerceroV2('unad07idtercero', $_REQUEST['unad07idtercero_td'], $_REQUEST['unad07idtercero_doc'], $bOculto, 1, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_unad07idtercero" class="L"><?php echo $unad07idtercero_rs; ?></div>
<div class="salto1px"></div>
</div>
<?php
// -- Inicia la carga masiva
if ($APP->idsistema==1){
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['msg_plano'];
?>
</label>
<?php
//Div para ocultar
$bconexpande=true;
if ($bconexpande){
?>
<div class="ir_derecha" style="width:62px;">
<input id="boculta107" name="boculta107" type="hidden" value="<?php echo $_REQUEST['boculta107']; ?>" />
<label class="Label30">
<input id="btexpande107" name="btexpande107" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(107,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta107']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge107" name="btrecoge107" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(107,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta107']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div id="div_p107" style="display:<?php if ($_REQUEST['boculta107']==0){echo 'block'; }else{echo 'none';} ?>;">
<?php
	}
//Mostrar formulario para editar
?>
<div class="salto1px"></div>
<input id="MAX_FILE_SIZE" name="MAX_FILE_SIZE" type="hidden" value="100000" />
<label class="Label90"></label>
<label class="Label500">
<input id="archivodatos" name="archivodatos" type="file" />
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['unad07idperfil'];
?>
</label>
<label>
<?php
echo $html_masidperfil;
?>
</label>
<div class="salto1px"></div>
<label class="Label90"></label>
<label class="Label130">
<input id="cmdanexar" name="cmdanexar" type="button" class="btSoloAnexar" value="<?php echo $ETI['msg_subir']; ?>" onclick="f107_cargamasiva()" title="<?php echo $ETI['msg_subir']; ?>"/>
</label>
<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<?php
echo $ETI['msg_infoplano'];
?>
<div class="salto1px"></div>
</div>
<?php
if ($bconexpande){
	//Este es el cierre del div_p107
?>
<div class="salto1px"></div>
</div>
<?php
	}
?>
<div class="salto1px"></div>
</div>
<?php
	}
//Termina la carga masiva.
if (false){
	//Ejemplo de boton de ayuda
	//echo html_BotonAyuda('NombreCampo');
	//echo html_DivAyudaLocal('NombreCampo');
	}
//Mostrar el contenido de la tabla
if ($APP->idsistema==1){
?>

<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo ''.$ETI['bloque_revisa'].'';
?>
</label>

<div class="ir_derecha" style="width:62px;">
<input name="boculta99" type="hidden" id="boculta99" value="<?php echo $_REQUEST['boculta99']; ?>" />
<label class="Label30">
<input type="button" id="btexpande99" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(99,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta99']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input type="button" id="btrecoge99" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(99,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta99']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div id="div_p99" style="display:<?php if ($_REQUEST['boculta99']==0){echo 'block'; }else{echo 'none';} ?>;">

<label class="Label130">
Documento
</label>
<label>
<input name="brevdocumento" type="text" id="brevdocumento" value="<?php echo $_REQUEST['brevdocumento']; ?>" onchange="paginarfrevisa()"/>
</label>
<label class="Label130">
Modulo
</label>
<label>
<input name="brevmodulo" type="text" id="brevmodulo" value="<?php echo $_REQUEST['brevmodulo']; ?>" onchange="paginarfrevisa()"/>
</label>
<div class="salto1px"></div>
<div id="div_frevisadetalle">
<?php
echo $sTabla107rev;
?>
</div>

</div>
<div class="salto1px"></div>
</div>
<?php
	}
?>

</div><!-- CIERRA EL DIV areatrabajo -->
</div><!-- CIERRA EL DIV areaform -->
<div class="areaform">
<div class="areatitulo">
<?php
echo '<h3>'.$ETI['bloque1'].'</h3>';
?>
</div>
<div class="areatrabajo">
<div class="ir_derecha">
Documento
<input name="bdocumento" type="text" id="bdocumento" value="<?php echo $_REQUEST['bdocumento']; ?>" onchange="paginarf107()"/>
Razon social
<input name="brazonsocial" type="text" id="brazonsocial" value="<?php echo $_REQUEST['brazonsocial']; ?>" onchange="paginarf107()"/>
<?php
echo $ETI['unad07idperfil'];
echo $html_bperfil;
?>
</div>
<div class="salto1px"></div>
<?php
echo ' '.$csv_separa;
?>
<div id="div_f107detalle">
<?php
echo $sTabla107;
?>
</div>
</div><!-- /div_areatrabajo -->
</div><!-- /DIV_areaform -->
</div><!-- /DIV_Sector1 -->

<div id="div_sector2" style="display:none">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda2" name="cmdAyuda2" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
<input id="cmdVolverSec2" name="cmdVolverSec2" type="button" class="btSupVolver" onclick="expandesector(1);" title="<?php echo $ETI['bt_volver']; ?>" value="<?php echo $ETI['bt_volver']; ?>"/>
</div>
<div class="titulosI">
<?php
echo '<h2>'.$ETI['titulo_sector2'].'</h2>';
?>
</div>
</div>
<div id="cargaForm">
<div id="area">
</div><!-- /div_area -->
</div><!-- /DIV_cargaForm -->
</div><!-- /DIV_Sector2 -->


<div id="div_sector95" style="display:none">
<div id="cargaForm">
<div id="div_95cuerpo"></div>
</div><!-- /DIV_cargaForm -->
</div><!-- /DIV_Sector95 -->


<div id="div_sector96" style="display:none">
<input id="div96v1" name="div96v1" type="hidden" value="" />
<input id="div96v2" name="div96v2" type="hidden" value="" />
<input id="div96v3" name="div96v3" type="hidden" value="" />
<input id="div96campo" name="div96campo" type="hidden" value="" />
<input id="div96llave" name="div96llave" type="hidden" value="" />
<input id="titulo_107" name="titulo_107" type="hidden" value="<?php echo $ETI['titulo_107']; ?>" />
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda96" name="cmdAyuda96" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
</div>
<div class="titulosI" id="div_96titulo"></div>
</div>
<div id="cargaForm">
<div id="div_96cuerpo"></div>
</div><!-- /DIV_cargaForm -->
</div><!-- /DIV_Sector96 -->


<div id="div_sector97" style="display:none">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda97" name="cmdAyuda97" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
<input id="cmdVolverSec97" name="cmdVolverSec97" type="button" class="btSupVolver" onclick="retornacontrol();" title="<?php echo $ETI['bt_volver']; ?>" value="<?php echo $ETI['bt_volver']; ?>"/>
</div>
<div class="titulosI" id="div_97titulo">
<?php
echo '<h2>'.$ETI['titulo_107'].'</h2>';
?>
</div>
</div>
<div id="cargaForm">
<div id="area">
<div id="div_97params"></div>
<div class="salto1px"></div>
<div id="div_97tabla"></div>
</div><!-- /div_area -->
</div><!-- /DIV_cargaForm -->
</div><!-- /DIV_Sector97 -->


<div id="div_sector98" style="display:none">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda98" name="cmdAyuda98" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
</div>
<div class="titulosI">
<?php
echo '<h2>'.$ETI['titulo_107'].'</h2>';
?>
</div>
</div>
<div id="cargaForm">
<div id="area">
<div class="MarquesinaMedia">
<?php
echo $ETI['msg_espere'];
?>
</div><!-- /Termina la marquesina -->
</div><!-- /div_area -->
</div><!-- /DIV_cargaForm -->
</div><!-- /DIV_Sector98 -->


<?php
if ($sDebug!=''){
	$iSegFin=microtime(true);
	$iSegundos=$iSegFin-$iSegIni;
	echo '<div class="salto1px"></div><div class="GrupoCampos" id="div_debug">'.$sDebug.fecha_microtiempo().' Tiempo total del proceso: <b>'.$iSegundos.'</b> Segundos'.'<div class="salto1px"></div></div>';
	}
?>
<input id="scampobusca" name="scampobusca" type="hidden" value=""/>
<input id="iscroll" name="iscroll" type="hidden" value="<?php echo $_REQUEST['iscroll']; ?>"/>
<input id="itipoerror" name="itipoerror" type="hidden" value="<?php echo $iTipoError; ?>"/>
<input id="debug" name="debug" type="hidden" value="<?php echo $_REQUEST['debug']; ?>"/>
</form>
</div><!-- /DIV_interna -->
<div class="flotante">
<input id="cmdGuardarf" name="cmdGuardarf" type="button" class="btSoloGuardar" onClick="enviaguardar();" value="<?php echo $ETI['bt_guardar']; ?>"/>
</div>
<?php
echo html_DivAlarmaV2($sError, $iTipoError);
	//El script que cambia el sector que se muestra
?>

<script language="javascript">
<!--
<?php
if ($iSector!=1){
	echo 'setTimeout(function(){expandesector('.$iSector.');}, 10);
';
	}
if ($bMueveScroll){
	echo 'setTimeout(function(){retornacontrol();}, 2);
';
	}
?>
-->
</script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/jquery-3.3.1.min.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/popper.min.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/bootstrap.min.js"></script>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/bootstrap.min.css" type="text/css"/>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/jquery.autocomplete.css" type="text/css"/>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/jquery.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/jquery.autocomplete.js"></script>
<script language="javascript" src="ac_107.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas.js?ver=8"></script>
<?php
forma_piedepagina();
?>