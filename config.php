<?php  /// Moodle Configuration File 

unset($CFG);


$CFG = new stdClass();
$CFG->dbtype    = 'mysqli';
$CFG->dbhost    = 'mysql10';
$CFG->dbname    = 'unadsys';
$CFG->dbuser    = 'unadsys';
$CFG->dbpass    = 'vn4D2y2!!';
$CFG->dbpersist =  false;
$CFG->prefix    = 'mdl_';

$CFG->wwwroot   = 'http://129.191.25.161/omarbautista';
$CFG->dirroot   = 'E:\website\unad\moodle';
$CFG->dataroot  = 'E:\website\unad\datosmoodle';
$CFG->admin     = 'admin';

$CFG->directorypermissions = 00777;  // try 02777 on a server in Safe Mode

$CFG->passwordsaltmain = '6yEVSKRwZ%0).&@A9SDFGghjE';

//require_once("$CFG->dirroot/lib/setup.php");
// MAKE SURE WHEN YOU EDIT THIS FILE THAT THERE ARE NO SPACES, BLANK LINES,
// RETURNS, OR ANYTHING ELSE AFTER THE TWO CHARACTERS ON THE NEXT LINE.
if (isset($SITE)==0){
	$SITE=new stdclass();
	$SITE->fullname='Aplicaciones administrativas';
	}
function require_login(){
	$bPasa=true;
	//session_start();
	if (isset($_SESSION['USER'])==0){
		$USER=new stdclass();
		$USER->id=2;
		$USER->idnumber=2;
		$USER->sesskey=123;
		$_SESSION['USER']=$USER;
		}
	$_SESSION['unad_id_tercero']=2;
	/*
	if (isset($_SESSION['unad_id_tercero'])==0){
		$bPasa=true;
		}else{
		if ((int)$_SESSION['unad_id_tercero']>0){$bPasa=true;}
		}
	if (!$bPasa){
		if (isset($APP->urllogin)==0){$APP->urllogin='http://campus.unad.edu.co';}
		header("Location:".$APP->urllogin);
		die();
		}
	*/
	}
function build_navigation($datos){
	}
function print_header($sTitulo, $modulo_nombre, $modulo_sigla,$nada_1,$nada_2,$nada_3, $langmenu){
	require 'app.php';
	if (isset($APP->rutaplantilla)==0){$APP->rutaplantilla='../theme/';}
	$sHTML='<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="'.$APP->rutaplantilla.'standard/styles.css" />
<link rel="stylesheet" type="text/css" href="'.$APP->rutaplantilla.'grisAzul/stylesgrisAzul.css" />
<!--[if IE 7]>
<link rel="stylesheet" type="text/css" href="'.$APP->rutaplantilla.'standard/styles_ie7.css" />
<![endif]-->
<!--[if IE 6]>
<link rel="stylesheet" type="text/css" href="'.$APP->rutaplantilla.'standard/styles_ie6.css" />
<![endif]-->
<meta name="keywords" content="aplicaciones administrativas UNAD" />
<title>'.$sTitulo.'</title>
<link rel="shortcut icon" href="'.$APP->rutaplantilla.'grisAzul/img/favicon.png" />
</head>

<body  class="oai course-1 notloggedin dir-ltr lang-es_utf8" id="oai-forma">
<div id="page">
<div class="container_12">
<div id="wrap">
<div class="wrapper">
    <div id="header" class=" clearfix">    <div class="grid_3" id="logo">
		<div class="logo">
				<img src="'.$APP->rutaplantilla.'grisAzul/img/logo.png" />
		</div>
	</div>
	<div class="grid_9" id="topnav">
		<div class="topnav">
<ul id="nav" class="dropdown dropdown-horizontal">
<li><a href="salir.php">Salir</a></li>
</ul>
		</div>
	</div>
	<div class="clear">&nbsp;</div>
    </div>
    <div class="navbar clearfix">
<div class="navbutton">

</div></div>
	<div class="breadcrumb"><div class="padder"><h2 class="accesshide " >Usted est&aacute; aqu&iacute;</h2> <ul>
<li class="first"><a  onclick="this.target=\'_top\'" href="http://administrativo.unadvirtual.org/cc/">Aplicativos Administrativos</a></li><li> <span class="accesshide " >/&nbsp;</span><span class="arrow sep">&#x25BA;</span> Panel</li></ul></div></div>
    <div class="clear">&nbsp;</div>
    <!-- END OF HEADER -->
    <div id="content">';
	echo $sHTML;
	}
function print_footer($nada){
	if ($_SESSION['unad_id_tercero']>0){
		$sHTML='';
		}else{
		$sHTML='<div class="logininfo">Usted no se ha autentificado. (<a  href="http://campus.unad.edu.co">Entrar</a>)</div>';
		}
	$sHTML='</div>
<hr>
</div><div id="footer"><div id="footer-bar"><span class="helplink"></span></div>'.$sHTML.'</div><div class="clear">&nbsp;</div>
<div class="footer">
<div class="padder"></div>
<div class="clear">&nbsp;</div>
</div>
<div class="clear">&nbsp;</div>
<div class="altrwrap"></div>
<div class="altlwrap"></div>
<div class="altlrwrap"></div>
</div>
</div>
</div>
</body>
</html>';
	echo $sHTML;
	}
?>