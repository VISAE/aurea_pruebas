<?php
/*
--- © Angel Mauro Avellaneda Barreto - Ideas - 2016 ---
--- mauro@avellaneda.co - http://www.ideasw.com
--- Modelo Versión 2.14.5 sabado, 23 de julio de 2016
*/
function texto_ParaHtml($origen){
	$nuevo=$origen;
	$sT=array('á','é','í','ó','ú', 'è','ì','ò','ñ','Ñ', 
	'Á','É','Í','Ó','Ú', '¿','¬','°','Â','Ç', 
	'©','¡','ª','­','–', '™','ê','ã','ç','â',
	'õ','“','”', '´', 'ü', 'Ü');
	$sH=array('&aacute;','&eacute;','&iacute;','&oacute;','&uacute;', '&egrave;','&igrave;','&ograve;','&ntilde;','&Ntilde;', 
	'&Aacute;','&Eacute;','&Iacute;','&Oacute;','&Uacute;', '&iquest;','&not;','&deg;','&Acirc;','&Ccedil;', 
	'&copy;','&iexcl;','&ordf;','&shy;','&ndash;', '&trade;','&ecirc;','&atilde;','&ccedil;','&acirc;',
	'&otilde;','&ldquo;','&rdquo;','&acute;', '&uuml;', '&Uuml;');
	$iTotal=35;
	for ($k=0;$k<=$iTotal;$k++){
		$nuevo=str_replace($sT[$k],$sH[$k],$nuevo);
		}
	return $nuevo;
	}
?>