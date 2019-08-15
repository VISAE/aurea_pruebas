<?php
/*
--- � Angel Mauro Avellaneda Barreto - UNAD - 2017 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versi�n 2.17.0 s�bado, 25 de marzo de 2017
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
require_once '../config.php';
mb_internal_encoding('UTF-8');
require './app.php';
require $APP->rutacomun.'unad_todas.php';
require $APP->rutacomun.'libs/clsdbadmin.php';
require $APP->rutacomun.'unad_librerias.php';
$grupo_id=0;
$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
$mensajes_200='lg/lg_200_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_200)){$mensajes_200='lg/lg_200_es.php';}
require $mensajes_todas;
require $mensajes_200;
$xajax=NULL;
$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
require_login();
require $APP->rutacomun.'unad_compatibilidad.php';
$modnombre='Plataforma de Desarrollo - UNAD';
$modsigla='AUREA';
$sError='';
if (isset($_REQUEST['id'])!=0){
	$grupo_id=$_REQUEST['id'];
	}
if ($grupo_id==-1){
	list($html_menu, $sDebug)=html_MenuGrupoV2(-1, 99, $objdb, false, $bDebug);
	}else{
	list($html_menu, $sDebug)=html_MenuGrupoV2($grupo_id, $APP->idsistema, $objdb, false, $bDebug);
	}
$et_menu=html_menu($APP->idsistema, $objdb);
$objdb->CerrarConexion();
//FORMA
if ($_SESSION['cfg_movil']==1){
	require $APP->rutacomun.'unad_formamovil.php';
	}else{
	require $APP->rutacomun.'unad_forma.php';
	}
forma_cabeceraV2($CFG, $SITE, $xajax, $ETI['titulo_200'], $ETI['app_nombre'].'|index.php@'.$ETI['grupo_nombre'].'|gm.php?id='.$grupo_id.'@'.$ETI['titulo_200'].'|');
echo $et_menu;
forma_mitad();
if (false){
?>
<link rel="stylesheet" href="../ulib/unad_estilos.css" type="text/css"/>
<?php
	}
?>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>unad_estilos.css" type="text/css"/>
<?php
?>
<div id="interna">
<form id="frmedita" name="frmedita" method="post" action="">
<div class="areaform">
<div class="areatrabajo">
<?php
echo $html_menu;
?>
</div>
</div>
<?php

if ($sDebug!=''){
	$iSegFin=microtime(true);
	$iSegundos=$iSegFin-$iSegIni;
	echo '<div class="salto1px"></div><div class="GrupoCampos" id="div_debug">'.$sDebug.fecha_microtiempo().' Tiempo total del proceso: <b>'.$iSegundos.'</b> Segundos'.'<div class="salto1px"></div></div>';
	}
?>
<input id="debug" name="debug" type="hidden" value="<?php echo $_REQUEST['debug']; ?>"/>
</form>
</div><!-- /DIV_interna -->
<?php
forma_piedepagina();
?>