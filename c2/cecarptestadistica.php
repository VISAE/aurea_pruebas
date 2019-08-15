<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
*/
/** Archivo cecarptestadistica.php.
* Modulo 2408 ceca08estadisticacurso.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @param debug=1 (Opcional), bandera para indicar si se generan datos de depuración
* @date sábado, 20 de julio de 2019
*/
if (file_exists('./err_control.php')){require './err_control.php';}
$bDebug=false;
$sDebug='';
$iSegIni='';
if (isset($_REQUEST['deb_doc'])!=0){
	$_REQUEST['debug']=1;
	}
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
require $APP->rutacomun.'libdatos.php';
if (($bPeticionXAJAX)&&($_SESSION['unad_id_tercero']==0)){
	// viene por xajax.
	$xajax=new xajax();
	$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
	$xajax->register(XAJAX_FUNCTION,'sesion_abandona_V2');
	$xajax->processRequest();
	die();
	}
$grupo_id=1;//Necesita ajustarlo...
$iCodModulo=2408;
$audita[1]=false;
$audita[2]=true;
$audita[3]=true;
$audita[4]=true;
$audita[5]=false;
// -- Se cargan los archivos de idioma
$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
$mensajes_2408='lg/lg_2408_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_2408)){$mensajes_2408='lg/lg_2408_es.php';}
require $mensajes_todas;
require $mensajes_2408;
$xajax=NULL;
$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
if (isset($APP->piel)==0){$APP->piel=1;}
$iPiel=$APP->piel;
$iPiel=1; //Piel 2018.
if ($bDebug){
	$sDebug=$sDebug.''.fecha_microtiempo().' Probando conexi&oacute;n con la base de datos <b>'.$APP->dbname.'</b> en <b>'.$APP->dbhost.'</b><br>';
	}
if (!$objDB->Conectar()){
	$bCerrado=true;
	if ($bDebug){
		$sDebug=$sDebug.''.fecha_microtiempo().' Error al intentar conectar con la base de datos <b>'.$objDB->serror.'</b><br>';
		}
	}
$idTercero=$_SESSION['unad_id_tercero'];
$bOtroUsuario=false;
if (isset($_REQUEST['deb_doc'])!=0){
	if (seg_revisa_permiso($iCodModulo, 1707, $objDB)){
		$sSQL='SELECT unad11id, unad11razonsocial FROM unad11terceros WHERE unad11doc="'.$_REQUEST['deb_doc'].'"';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$idTercero=$fila['unad11id'];
			$bOtroUsuario=true;
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Se verifica la ventana de trabajo para el usuario '.$fila['unad11razonsocial'].'.<br>';}
			}else{
			$sError='No se ha encontrado el documento &quot;'.$_REQUEST['deb_doc'].'&quot;';
			$_REQUEST['deb_doc']='';
			}
		}else{
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' No cuenta con permiso de ingreso como otro usuario Modulo '.$iCodModulo.' Permiso.<br>';}
		$_REQUEST['deb_doc']='';
		}
	}else{
	$_REQUEST['deb_doc']='';
	}
if (!seg_revisa_permiso($iCodModulo, 1, $objDB)){
	header('Location:nopermiso.php');
	die();
	}
if (!$bPeticionXAJAX){
	if (noticias_pendientes($objDB)){
		$objDB->CerrarConexion();
		header('Location:noticia.php?ret=cecarptestadistica.php');
		die();
		}
	}
//PROCESOS DE LA PAGINA
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso'])==0){
	$_REQUEST['paso']=-1;
	if ($audita[1]){seg_auditaingreso($iCodModulo, $_SESSION['unad_id_tercero'], $objDB);}
	}
// -- 2408 ceca08estadisticacurso
require 'lib2408.php';
$xajax=new xajax();
$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
$xajax->register(XAJAX_FUNCTION,'formatear_moneda');
$xajax->register(XAJAX_FUNCTION,'f2408_Comboceca08idcurso');
$xajax->register(XAJAX_FUNCTION,'f2408_Comboceca08idtutor');
$xajax->register(XAJAX_FUNCTION,'f2408_Comboceca08idcentro');
$xajax->register(XAJAX_FUNCTION,'f2408_Comboceca08idprograma');
$xajax->register(XAJAX_FUNCTION,'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION,'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION,'f2408_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f2408_ExisteDato');
$xajax->register(XAJAX_FUNCTION,'f2408_Comboceca08idzona');
$xajax->register(XAJAX_FUNCTION,'f2408_Comboceca08idescuela');
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
if (isset($_REQUEST['paginaf2408'])==0){$_REQUEST['paginaf2408']=1;}
if (isset($_REQUEST['lppf2408'])==0){$_REQUEST['lppf2408']=20;}
if (isset($_REQUEST['boculta2408'])==0){$_REQUEST['boculta2408']=0;}
// -- Inicializar variables de datos.
if (isset($_REQUEST['ceca08idperaca'])==0){$_REQUEST['ceca08idperaca']='';}
if (isset($_REQUEST['ceca08idcurso'])==0){$_REQUEST['ceca08idcurso']='';}
if (isset($_REQUEST['ceca08idtutor'])==0){$_REQUEST['ceca08idtutor']='';}
if (isset($_REQUEST['ceca08idzona'])==0){$_REQUEST['ceca08idzona']='';}
if (isset($_REQUEST['ceca08idcentro'])==0){$_REQUEST['ceca08idcentro']='';}
if (isset($_REQUEST['ceca08idescuela'])==0){$_REQUEST['ceca08idescuela']='';}
if (isset($_REQUEST['ceca08idprograma'])==0){$_REQUEST['ceca08idprograma']='';}
//if (isset($_REQUEST['ceca08sexo'])==0){$_REQUEST['ceca08sexo']='';}
if (isset($_REQUEST['ceca08edad'])==0){$_REQUEST['ceca08edad']='';}
if (isset($_REQUEST['ceca08id'])==0){$_REQUEST['ceca08id']='';}
if (isset($_REQUEST['ceca08tiporegistro'])==0){$_REQUEST['ceca08tiporegistro']=1;}
// Espacio para inicializar otras variables
if (isset($_REQUEST['csv_separa'])==0){$_REQUEST['csv_separa']=',';}
if (isset($_REQUEST['bnombre'])==0){$_REQUEST['bnombre']='';}
if (isset($_REQUEST['incluirceca08sexo'])==0){$_REQUEST['incluirceca08sexo']='N';}
if (isset($_REQUEST['unae18id'])==0){$_REQUEST['unae18id']='';}
//if (isset($_REQUEST['blistar'])==0){$_REQUEST['blistar']='';}
//limpiar la pantalla
if ($_REQUEST['paso']==-1){
	$_REQUEST['ceca08idperaca']='';
	$_REQUEST['ceca08idcurso']='';
	$_REQUEST['ceca08idtutor']='';
	$_REQUEST['ceca08idzona']='';
	$_REQUEST['ceca08idcentro']='';
	$_REQUEST['ceca08idescuela']='';
	$_REQUEST['ceca08idprograma']='';
	//$_REQUEST['ceca08sexo']='';
	$_REQUEST['ceca08edad']='';
	$_REQUEST['ceca08id']='';
	$_REQUEST['paso']=0;
	$_REQUEST['incluirceca08sexo']='N';
	$_REQUEST['unae18id']='';
	}
if ($bLimpiaHijos){
	}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
//DATOS PARA COMPLETAR EL FORMULARIO
//Crear los controles que requieran llamado a base de datos
$objCombos=new clsHtmlCombos();
$objTercero=new clsHtmlTercero();
$objCombos->nuevo('ceca08tiporegistro', $_REQUEST['ceca08tiporegistro'], false, '{'.$ETI['msg_seleccione'].'}');
$objCombos->sAccion='paginarf2408()';
$sSQL='SELECT ceca10id AS id, ceca10nombre AS nombre FROM ceca10tiporegestad ORDER BY ceca10id';
$html_ceca08tiporegistro=$objCombos->html($sSQL, $objDB);
$html_ceca08idperaca=f2408_HTMLComboV2_ceca08idperaca($objDB, $objCombos, $_REQUEST['ceca08idperaca']);
$html_ceca08idcurso=f2408_HTMLComboV2_ceca08idcurso($objDB, $objCombos, $_REQUEST['ceca08idcurso'], $_REQUEST['ceca08idperaca']);
$html_ceca08idtutor=f2408_HTMLComboV2_ceca08idtutor($objDB, $objCombos, $_REQUEST['ceca08idtutor'], $_REQUEST['ceca08idcurso']);
$html_ceca08idzona=f2408_HTMLComboV2_ceca08idzona($objDB, $objCombos, $_REQUEST['ceca08idzona']);
$html_ceca08idcentro=f2408_HTMLComboV2_ceca08idcentro($objDB, $objCombos, $_REQUEST['ceca08idcentro'], $_REQUEST['ceca08idzona']);
$html_ceca08idescuela=f2408_HTMLComboV2_ceca08idescuela($objDB, $objCombos, $_REQUEST['ceca08idescuela']);
$html_ceca08idprograma=f2408_HTMLComboV2_ceca08idprograma($objDB, $objCombos, $_REQUEST['ceca08idprograma'], $_REQUEST['ceca08idescuela']);
$html_unae18rangoedad=f2408_HTMLComboV2_unae18rangoedad($objDB, $objCombos, $_REQUEST['unae18id']);
//$html_ceca08sexo=html_combo('ceca08sexo', 'unad22codopcion', 'unad22nombre', 'unad22combos', 'unad22idmodulo=111 AND unad22consec=1 AND unad22activa="S"', 'unad22orden', $_REQUEST['ceca08sexo'], $objDB, 'paginarf2408()', true, '{'.$ETI['msg_na'].'}', '');
$objCombos->nuevo('incluirceca08sexo', $_REQUEST['incluirceca08sexo'], false);
$objCombos->sino();
$objCombos->sAccion='paginarf2408()';
$html_incluirceca08sexo=$objCombos->html('', $objDB);
//Alistar datos adicionales
$id_rpt=0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
/*
$objCombos->nuevo('blistar', $_REQUEST['blistar'], true, '{'.$ETI['msg_todos'].'}');
$objCombos->sAccion='paginarf2408()';
$html_blistar=$objCombos->html('', $objDB);
//$html_blistar=$objCombos->comboSistema(2408, 1, $objDB, 'paginarf2408()');
*/
//Permisos adicionales
$seg_5=0;
$seg_6=0;
if (seg_revisa_permiso($iCodModulo, 6, $objDB)){$seg_6=1;}
if ($seg_6==1){}
if (true){
	$objCombos->nuevo('csv_separa', $_REQUEST['csv_separa'], false);
	$objCombos->addItem(',', $ETI['msg_coma']);
	$objCombos->addItem(';', $ETI['msg_puntoycoma']);
	$csv_separa='<label class="Label130">'.$ETI['msg_separador'].'</label><label class="Label130">'.$objCombos->html('', $objDB).'</label>';
	}else{
	$csv_separa='<input id="csv_separa" name="csv_separa" type="hidden" value="," />';
	}
$iNumFormatosImprime=0;
$iModeloReporte=2408;
$html_iFormatoImprime='<input id="iformatoimprime" name="iformatoimprime" type="hidden" value="0" />
';
if ($_REQUEST['paso']>0){
	if (seg_revisa_permiso($iCodModulo, 5, $objDB)){
		$seg_5=1;
		}	
	}
//Cargar las tablas de datos
$aParametros[0]='';//$_REQUEST['p1_2408'];
$aParametros[101]=$_REQUEST['paginaf2408'];
$aParametros[102]=$_REQUEST['lppf2408'];
$aParametros[103]=$_REQUEST['ceca08tiporegistro'];
$aParametros[104]=$_REQUEST['ceca08idperaca'];
$aParametros[105]=$_REQUEST['ceca08idcurso'];
$aParametros[106]=$_REQUEST['ceca08idtutor'];
$aParametros[107]=$_REQUEST['ceca08idzona'];
$aParametros[108]=$_REQUEST['ceca08idcentro'];
$aParametros[109]=$_REQUEST['ceca08idescuela'];
$aParametros[110]=$_REQUEST['ceca08idprograma'];
$aParametros[111]=$_REQUEST['incluirceca08sexo'];
$aParametros[112]=$_REQUEST['unae18id'];

list($sTabla2408, $sDebugTabla)=f2408_TablaDetalleV2($aParametros, $objDB, $bDebug);
$sDebug=$sDebug.$sDebugTabla;
list($et_menu, $sDebugM)=html_menuV2($APP->idsistema, $objDB, $iPiel, $bDebug, $idTercero);
$sDebug=$sDebug.$sDebugM;
/* prueba */
//require $APP->rutacomun.'libc2.php';
//f2401_ArmarEstadisticaCurso(611, 122001, $objDB, $bDebug);
/* fin de la prueba*/
$objDB->CerrarConexion();
//FORMA
require $APP->rutacomun.'unad_forma_v2.php';
forma_cabeceraV3($xajax, $ETI['titulo_2408']);
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
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/jquery-3.3.1.min.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/popper.min.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/bootstrap.min.js"></script>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/bootstrap.min.css" type="text/css"/>
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
	}
function imprimelista(){
	if (window.document.frmedita.seg_6.value==1){
		window.document.frmlista.consulta.value=window.document.frmedita.consulta_2408.value;
		window.document.frmlista.titulos.value=window.document.frmedita.titulos_2408.value;
		window.document.frmlista.nombrearchivo.value='Estadistica de calificaciones';
		window.document.frmlista.submit();
		}else{
		window.alert("<?php echo $ERR['6']; ?>");
		}
	}
function asignarvariables(){
var sEncabeado='';
	window.document.frmimpp.v3.value=window.document.frmedita.ceca08tiporegistro.value;
	window.document.frmimpp.v4.value=window.document.frmedita.ceca08idperaca.value;
	window.document.frmimpp.v5.value=window.document.frmedita.ceca08idcurso.value;
	window.document.frmimpp.v6.value=window.document.frmedita.ceca08idtutor.value;
	window.document.frmimpp.v7.value=window.document.frmedita.ceca08idzona.value;
	window.document.frmimpp.v8.value=window.document.frmedita.ceca08idcentro.value;
	window.document.frmimpp.v9.value=window.document.frmedita.ceca08idescuela.value;
	window.document.frmimpp.v10.value=window.document.frmedita.ceca08idprograma.value;
	window.document.frmimpp.v11.value=window.document.frmedita.incluirceca08sexo.value;
	window.document.frmimpp.v12.value=window.document.frmedita.unae18id.value;
	if(window.document.frmedita.ceca08tiporegistro.value!=''){
		sEncabeado=sEncabeado+'Tipo de estadistica '+window.document.frmedita.ceca08tiporegistro.options[window.document.frmedita.ceca08tiporegistro.selectedIndex].text +'  '+'\n';
	}
	if(window.document.frmedita.ceca08idperaca.value!=''){
		sEncabeado=sEncabeado+'Peraca '+window.document.frmedita.ceca08idperaca.options[window.document.frmedita.ceca08idperaca.selectedIndex].text+'  '+'\n';
	}
	if(window.document.frmedita.ceca08idcurso.value!=''){
		sEncabeado=sEncabeado+'Curso '+window.document.frmedita.ceca08idcurso.options[window.document.frmedita.ceca08idcurso.selectedIndex].text+'  '+'\n';
	}
	
	if(window.document.frmedita.ceca08idtutor.value!=''){
		sEncabeado=sEncabeado+'Tutor '+((window.document.frmedita.ceca08idtutor.options[window.document.frmedita.ceca08idtutor.selectedIndex].text))+'  '+'\n';
	}
	if(window.document.frmedita.ceca08idzona.value!=''){
		sEncabeado=sEncabeado+'Zona '+window.document.frmedita.ceca08idzona.options[window.document.frmedita.ceca08idzona.selectedIndex].text+'  '+'\n';
	}
	
	if(window.document.frmedita.ceca08idcentro.value!=''){
		sEncabeado=sEncabeado+'Centro '+window.document.frmedita.ceca08idcentro.options[window.document.frmedita.ceca08idcentro.selectedIndex].text+'  '+'\n';
	}
	
	if(window.document.frmedita.ceca08idescuela.value!=''){
		sEncabeado=sEncabeado+'Escuela '+window.document.frmedita.ceca08idescuela.options[window.document.frmedita.ceca08idescuela.selectedIndex].text+'  '+'\n';
	}
	
	if(window.document.frmedita.ceca08idprograma.value!=''){
		sEncabeado=sEncabeado+'Programa '+window.document.frmedita.ceca08idprograma.options[window.document.frmedita.ceca08idprograma.selectedIndex].text+'  '+'\n';
	}
	
	/*if(window.document.frmedita.incluirceca08sexo.value!=''){
		sEncabeado=sEncabeado+'Sexo '+window.document.frmedita.incluirceca08sexo.options[window.document.frmedita.incluirceca08sexo.selectedIndex].text+'  '+'\n';
	}
	*/
	if(window.document.frmedita.unae18id.value!=''){
		sEncabeado=sEncabeado+'Rango de edad '+window.document.frmedita.unae18id.options[window.document.frmedita.unae18id.selectedIndex].text+'  '+'\n';
	}
	
	window.document.frmimpp.vEncabezado.value=sEncabeado;
	window.document.frmimpp.separa.value=window.document.frmedita.csv_separa.value.trim();
	}
function imprimeexcel(){
	if(window.document.frmedita.ceca08idperaca.value==''){
		window.alert("<?php echo $ERR['ceca08idperaca']; ?>");  
  	}else{
		if (window.document.frmedita.seg_6.value==1){
			asignarvariables();
			window.document.frmimpp.action='t2408.php';
			window.document.frmimpp.submit();
			}else{
			window.alert("<?php echo $ERR['6']; ?>");
			}
		}
	}
function imprimep(){
	if (window.document.frmedita.seg_5.value==1){
		asignarvariables();
		window.document.frmimpp.action='p2408.php';
		window.document.frmimpp.submit();
<?php
if ($iNumFormatosImprime>0){
?>
		expandesector(1);
<?php
	}
?>
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
function carga_combo_ceca08idcurso(){
	var params=new Array();
	params[0]=window.document.frmedita.ceca08idperaca.value;
	xajax_f2408_Comboceca08idcurso(params);
	params[0]='';
	xajax_f2408_Comboceca08idtutor(params);
	params[0]='';
	params[1]='';
	xajax_f2408_Comboceca08idzona(params);
	params[0]='';
	params[1]='';
	xajax_f2408_Comboceca08idcentro(params);
	params[0]='';
	xajax_f2408_Comboceca08idescuela(params);
	params[0]='';
	params[1]='';
	xajax_f2408_Comboceca08idprograma(params);
	}
function carga_combo_ceca08idtutor(){
	var params=new Array();
	params[0]=window.document.frmedita.ceca08idcurso.value;
	xajax_f2408_Comboceca08idtutor(params);
	params[0]='';
	params[1]=window.document.frmedita.ceca08idcurso.value;
	xajax_f2408_Comboceca08idzona(params);
	params[0]='';
	xajax_f2408_Comboceca08idcentro(params);
	//params[0]='';
	//xajax_f2408_Comboceca08idescuela(params);
	params[0]='';
	params[1]='';
	xajax_f2408_Comboceca08idprograma(params);
	}
	
function carga_combo_ceca08idzona(){ // nosotros
	var params=new Array();
	params[0]=window.document.frmedita.ceca08idtutor.value;
	params[1]=window.document.frmedita.ceca08idcurso.value;
	//console.log('params[1] '+params[1]+ ' params[0] '+params[0] );
	xajax_f2408_Comboceca08idzona(params);
	//params[0]='';
	//xajax_f2408_Comboceca08idcentro(params);
	//params[0]='';
	//xajax_f2408_Comboceca08idescuela(params);
	//params[0]='';
	//xajax_f2408_Comboceca08idprograma(params);
	}
	
		
function carga_combo_ceca08idcentro(){
	var params=new Array();
	params[0]=window.document.frmedita.ceca08idzona.value;
	params[1]=window.document.frmedita.ceca08idtutor.value;
	xajax_f2408_Comboceca08idcentro(params);
	//params[0]='';
	//xajax_f2408_Comboceca08idescuela(params);
	//params[0]='';
	//xajax_f2408_Comboceca08idprograma(params);
	}

function carga_combo_ceca08idescuela(){ // nosotros
	var params=new Array();
	params[0]=window.document.frmedita.ceca08idcurso.value;
	params[1]=window.document.frmedita.ceca08idtutor.value;
	xajax_f2408_Comboceca08idescuela(params);
	
	/*params[0]=window.document.frmedita.ceca08idescuela.value;
	params[1]=window.document.frmedita.ceca08idtutor.value;
	params[2]=window.document.frmedita.ceca08idcurso.value;
	xajax_f2408_Comboceca08idprograma(params);
	*/
	}	

function carga_combo_ceca08idprograma(){
	var params=new Array();
	params[0]=window.document.frmedita.ceca08idescuela.value;
    params[1]=window.document.frmedita.ceca08idtutor.value;
	params[2]=window.document.frmedita.ceca08idcurso.value;
	xajax_f2408_Comboceca08idprograma(params);
	
	}
function paginarf2408(){
	var params=new Array();
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf2408.value;
	params[102]=window.document.frmedita.lppf2408.value;
	params[103]=window.document.frmedita.ceca08tiporegistro.value;
	params[104]=window.document.frmedita.ceca08idperaca.value;
	params[105]=window.document.frmedita.ceca08idcurso.value;
	params[106]=window.document.frmedita.ceca08idtutor.value;
	params[107]=window.document.frmedita.ceca08idzona.value;
	params[108]=window.document.frmedita.ceca08idcentro.value;
	params[109]=window.document.frmedita.ceca08idescuela.value;
	params[110]=window.document.frmedita.ceca08idprograma.value;
	params[111]=window.document.frmedita.incluirceca08sexo.value;
	params[112]=window.document.frmedita.unae18id.value;
	
	//params[104]=window.document.frmedita.blistar.value;
	document.getElementById('div_f2408detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere...</div></div><input id="paginaf2408" name="paginaf2408" type="hidden" value="'+params[101]+'" /><input id="lppf2408" name="lppf2408" type="hidden" value="'+params[102]+'" />';
	xajax_f2408_HtmlTabla(params);
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
	document.getElementById("ceca08idperaca").focus();
	}
function buscarV2016(sCampo){
	window.document.frmedita.iscroll.value=window.pageYOffset;
	expandesector(98);
	window.document.frmedita.scampobusca.value=sCampo;
	var params=new Array();
	params[1]=sCampo;
	//params[2]=window.document.frmedita.iagno.value;
	//params[3]=window.document.frmedita.itipo.value;
	xajax_f2408_Busquedas(params);
	}
function retornacontrol(){
	expandesector(1);
	window.scrollTo(0, window.document.frmedita.iscroll.value);
	}
function Devuelve(sValor){
	var sCampo=window.document.frmedita.scampobusca.value;
	if (sCampo=='ceca08idtutor'){
		ter_traerxid('ceca08idtutor', sValor);
		}
	retornacontrol();
	}
function mantener_sesion(){xajax_sesion_mantenerV4();}
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
	
function cargar_zona_y_escuela(){
	carga_combo_ceca08idzona();
	carga_combo_ceca08idescuela();
	}	
	
function cargar_tutor_escuela_zona(){
	carga_combo_ceca08idzona();
	carga_combo_ceca08idtutor();
	carga_combo_ceca08idescuela();
	
	}		

// -->
</script>
<?php
//if ($_REQUEST['paso']!=0){
?>
<form id="frmimpp" name="frmimpp" method="post" action="t2408.php" target="_blank">
<input id="r" name="r" type="hidden" value="2408" />
<input id="id2408" name="id2408" type="hidden" value="<?php echo $_REQUEST['ceca08id']; ?>" />
<input id="v3" name="v3" type="hidden" value="" />
<input id="v4" name="v4" type="hidden" value="" />
<input id="v5" name="v5" type="hidden" value="" />
<input id="v6" name="v6" type="hidden" value="" />
<input id="v7" name="v7" type="hidden" value="" />
<input id="v8" name="v8" type="hidden" value="" />
<input id="v9" name="v9" type="hidden" value="" />
<input id="v10" name="v10" type="hidden" value="" />
<input id="v11" name="v11" type="hidden" value="" />
<input id="v12" name="v12" type="hidden" value="" />
<input id="vEncabezado" name="vEncabezado" type="hidden" value="" />
<input id="iformato94" name="iformato94" type="hidden" value="0" />
<input id="separa" name="separa" type="hidden" value="," />
<input id="rdebug" name="rdebug" type="hidden" value="<?php echo $_REQUEST['debug']; ?>"/>
<input id="clave" name="clave" type="hidden" value="" />
</form>
<?php
//	}
?>
<form id="frmlista" name="frmlista" method="post" action="listados.php" target="_blank">
<input id="titulos" name="titulos" type="hidden" value="" />
<input id="consulta" name="consulta" type="hidden" value="" />
<input id="nombrearchivo" name="nombrearchivo" type="hidden" value="" />
</form>
<div id="interna">
<form id="frmedita" name="frmedita" method="post" action="" autocomplete="off">
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
$bHayImprimir=false;
//$sScript='imprimelista()';
$sScript='imprimeexcel()';
$sClaseBoton='btEnviarExcel';
if ($seg_6==1){$bHayImprimir=true;}
if ($_REQUEST['paso']!=0){
	if ($seg_5==1){
		//$bHayImprimir=true;
		//$sScript='imprimep()';
		//if ($iNumFormatosImprime>0){
			//$sScript='expandesector(94)';
			//}
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
</div>
<div class="titulosI">
<?php
echo '<h2>'.$ETI['titulo_2408'].'</h2>';
?>
</div>
</div>
<div class="areaform">
<div class="areatrabajo">
<?php
//Div para ocultar
$bconexpande=false;
if ($bconexpande){
?>
<div class="ir_derecha" style="width:62px;">
<input id="boculta2408" name="boculta2408" type="hidden" value="<?php echo $_REQUEST['boculta2408']; ?>" />
<label class="Label30">
<input id="btexpande2408" name="btexpande2408" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(2408,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta2408']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge2408" name="btrecoge2408" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(2408,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta2408']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div id="div_p2408" style="display:<?php if ($_REQUEST['boculta2408']==0){echo 'block'; }else{echo 'none';} ?>;">
<?php
	}
//Mostrar formulario para editar
?>
<label class="Label160">
<?php
echo $ETI['ceca08tiporegistro'];
?>
</label>
<label>
<?php
echo $html_ceca08tiporegistro;
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['ceca08idperaca'];
?>
</label>
<label>
<?php
echo $html_ceca08idperaca;
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['ceca08idcurso'];
?>
</label>
<label>
<div id="div_ceca08idcurso">
<?php
echo $html_ceca08idcurso;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['ceca08idtutor'];
?>
</label>
<label>
<div id="div_ceca08idtutor">
<?php
echo $html_ceca08idtutor;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['ceca08idzona'];
?>
</label>
<label>
<div id="div_ceca08idzona">
<?php
echo $html_ceca08idzona;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['ceca08idcentro'];
?>
</label>
<label>
<div id="div_ceca08idcentro">
<?php
echo $html_ceca08idcentro;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['ceca08idescuela'];
?>
</label>
<label>
<div id="div_ceca08idescuela">
<?php
echo $html_ceca08idescuela;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['ceca08idprograma'];
?>
</label>
<label>
<div id="div_ceca08idprograma">
<?php
echo $html_ceca08idprograma;
?>
</div>
</label>
<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<div class="salto1px"></div>
<?php
echo '<b>'.$ETI['msg_incluir_campos'].'</b>';
?>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['ceca08sexo'];
?>
</label>

<label class="Label90">
<?php
echo $html_incluirceca08sexo;
?>
</label>

<label class="Label160">
<?php
echo $ETI['unae18id'];
?>
</label>

<label class="Label320">
<?php
echo $html_unae18rangoedad;
?>
</label>


<?php
if (false){
	//Ejemplo de boton de ayuda
	//echo html_BotonAyuda('NombreCampo');
	//echo html_DivAyudaLocal('NombreCampo');
	}
if ($bconexpande){
	//Este es el cierre del div_p2408
?>

<?php
	}
//Mostrar el contenido de la tabla
?>

<?php
echo ' '.$csv_separa;
?>

<div class="salto1px"></div>
</div>
</div>
<div id="div_f2408detalle">
<?php
echo $sTabla2408;
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
<input id="titulo_2408" name="titulo_2408" type="hidden" value="<?php echo $ETI['titulo_2408']; ?>" />
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
echo '<h2>'.$ETI['titulo_2408'].'</h2>';
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
echo '<h2>'.$ETI['titulo_2408'].'</h2>';
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
<?php
?>
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
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas.js?ver=8"></script>
<?php
forma_piedepagina();
?>