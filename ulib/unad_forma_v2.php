<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
*/
function forma_cabeceraV2($CFG, $SITE, $XAJAX=NULL, $modnombre='', $sLinks=''){
	forma_cabeceraV3($XAJAX, $modnombre);
	}
function forma_cabeceraV3($XAJAX=NULL, $modnombre='', $bConMenu=true){
	require './app.php';
	echo '<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">';
	if ($XAJAX!=NULL){$XAJAX->printJavascript();}
	$sBaseTitulo=$modnombre;
	if ($modnombre!=''){$sBaseTitulo=$modnombre.' - ';}
	$sAddMenu='<div class="menuSuperior"><ul>';
	if (!$bConMenu){$sAddMenu='';}
	echo '<title>'.$sBaseTitulo.'Universidad Nacional Abierta y a Distancia UNAD</title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link href="'.$APP->rutacomun.'css/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
<link rel="stylesheet" href="'.$APP->rutacomun.'css/formav2.css" type="text/css" />
</head>
<body>
'.$sAddMenu.'';
	}
function forma_mitad($bConMenu=true){
	$sAddMenu='</ul></div>';
	if (!$bConMenu){$sAddMenu='';}
	echo $sAddMenu.'
<header>
<div class="barraHeader">
</div>
</header>
<main>
<div class="salto1px"></div>
<div class="cuerpo">
';
	}
function forma_piedepagina($bConTiempo=true){
	require './app.php';
	$sMuestra='';
	if (!$bConTiempo){$sMuestra='display:none;';}
	echo '
</div>
<div class="salto1px"></div>
<div id="div_tiempo" style="width:150px;'.$sMuestra.'" class="ir_derecha"></div>
<div class="salto1px"></div>
</main>
<footer id="footer">
<p>Sede nacional Jos&eacute; Celestino Mutis: Calle 14 sur No. 14 - 23<br>
PBX:<a style="color:#FFF;" href="tel:+5713443700"> ( +57 1 ) 344 3700</a> Bogot&aacute; D.C., Colombia<br>
Línea nacional gratuita desde Colombia: <a style="color:#FFF;" href="tel:018000115223">018000115223</a><br>
Atenci&oacute;n al usuario: atencionalusuario@unad.edu.co<br>
Institución de Educaci&oacute;n Superior sujeta a inspecci&oacute;n y vigilancia por el Ministerio de Educación Nacional
</p>
</footer>
<link rel="stylesheet" href="'.$APP->rutacomun.'css/general.css">
<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
</body>
</html>';
	}
?>