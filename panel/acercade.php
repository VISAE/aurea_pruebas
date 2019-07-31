<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
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
//require_once '../config.php';
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
require $APP->rutacomun.'unad_todas.php';
require $APP->rutacomun.'libs/clsdbadmin.php';
require $APP->rutacomun.'unad_librerias.php';
require $APP->rutacomun.'libhtml.php';
$iPiel=1;

// -- Se cargan los archivos de idioma
$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
$mensajes_200='lg/lg_2200_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_200)){$mensajes_200='lg/lg_2200_es.php';}
require $mensajes_todas;
require $mensajes_200;
$xajax=NULL;
$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
include $APP->rutacomun.'unad_compatibilidad.php';
$modnombre='Concentrador de recursos';
$modsigla='CORE';
list($et_menu, $sDebugM)=html_menuV2($APP->idsistema, $objdb, $iPiel, $bDebug);
$objdb->CerrarConexion();
//FORMA
require $APP->rutacomun.'unad_forma_v2.php';
forma_cabeceraV3($xajax, $ETI['titulo_2200']);
echo $et_menu;
forma_mitad();
?>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>css/criticalPath.css" type="text/css"/>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>css/principal.css" type="text/css"/>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>unad_estilos2018.css" type="text/css"/>
<?php
?>
<div id="interna">
<div id="div_sector1">
<div class="titulos">
<h1>Concentrador de Recursos Ver. 1.0</h1>
</div>
<div class="areaform">
<div class="areatrabajo">
<h2>&copy; Universidad Nacional Abierta y a Distancia - UNAD 2018</h2>
<b>Rector :</b> Licenciado Jaime Alberto Leal Afanador<br />
<a href="http://www.unad.edu.co" target="_blank">http://www.unad.edu.co</a>
<h3>Gerencia de Innovaci&oacute;n y Desarrollo Tecnol&oacute;gico</h3>
<b>Gerente :</b> <br />
Ingeniero Andres Ernesto Salinas<br />
<b>Jefe de Proyecto :</b> <br />
Ingeniero Miguel Pinto Aparicio<br />
<b>Dise&ntilde;o y Programaci&oacute;n :</b> <br />
Ingeniero Angel Mauro Avellaneda Barreto<br /> 
<a href="http://www.mauroavellaneda.com" target="_blank"><img src="img/logoMA.gif" alt="Mauro Avellaneda" /></a><br />
<b>Maquetado</b><br />
Ing Edison Johan Bernal Mu&ntilde;oz<br />
<b>Para soporte t&eacute;cnico :</b> soporte.campus@unad.edu.co<br />
</div><!-- CIERRA EL DIV areatrabajo -->
</div><!-- CIERRA EL DIV areaform -->
</div><!-- /DIV_Sector1 -->
<input id="debug" name="debug" type="hidden" value="<?php echo $_REQUEST['debug']; ?>"/>
</form>
</div><!-- /DIV_interna -->
<script language="javascript" type="text/javascript" charset="UTF-8" src="<?php echo $APP->rutacomun; ?>js/jquery-3.3.1.min.js"></script>
<script language="javascript" type="text/javascript" charset="UTF-8" src="<?php echo $APP->rutacomun; ?>js/popper.min.js"></script>
<script language="javascript" type="text/javascript" charset="UTF-8" src="<?php echo $APP->rutacomun; ?>js/bootstrap.min.js"></script>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/bootstrap.min.css" type="text/css"/>
<?php
forma_piedepagina();
?>