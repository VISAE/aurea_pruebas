<?php
/*
--- © Angel Mauro Avellaneda Barreto - Ideas - 2015 ---
--- mauro@avellaneda.co - http://www.mauroavellaneda.com
--- El objetivo de esta libreria es reducir el tamaño de los archivo tipo p____.php
--- Mayo 5 de 2015 Se agregan funciones para encriptado.
*/
if(function_exists('mcrypt_encrypt')){
	function RC4($key, $data){
		return mcrypt_encrypt(MCRYPT_ARCFOUR, $key, $data, MCRYPT_MODE_STREAM, '');
		}
	}else{
	function RC4($key, $data){
        static $last_key, $last_state;
        if($key != $last_key){
            $k = str_repeat($key, 256/strlen($key)+1);
            $state = range(0, 255);
            $j = 0;
            for ($i=0; $i<256; $i++){
                $t = $state[$i];
                $j = ($j + $t + ord($k[$i])) % 256;
                $state[$i] = $state[$j];
                $state[$j] = $t;
            	}
            $last_key = $key;
            $last_state = $state;
        	}else{
			$state = $last_state;
			}
        $len = strlen($data);
        $a = 0;
        $b = 0;
        $out = '';
        for ($i=0; $i<$len; $i++){
            $a = ($a+1) % 256;
            $t = $state[$a];
            $b = ($b+$t) % 256;
            $state[$a] = $state[$b];
            $state[$b] = $t;
            $k = $state[($state[$a]+$state[$b]) % 256];
            $out .= chr(ord($data[$i]) ^ $k);
			}
		return $out;
		}
	}
// -- Funciones para encriptar
// -- Terminan las funciones de encriptado
function p_Alto($objpdf){
	return $objpdf->GetY();
	}
function p_AddDebug($sDetalle, $objpdf){
	if ($sDetalle!=''){
		$objpdf->iDebug++;
		$objpdf->aDebug[$objpdf->iDebug]=fecha_microtiempo().' '.$sDetalle.'';
		}
	}
function p_AddError($sdetalle, $objpdf){
	if ($objpdf->sError!=''){$objpdf->sError=$objpdf->sError.'<br>';}
	$objpdf->sError=$objpdf->sError.$sdetalle;
	}
function p_AddFondo($sRutaFondo, $objpdf){
	if ($sRutaFondo!=''){
		if (file_exists($sRutaFondo)){
			//Detectar el ancho de la imagen.
			list($sMime, $iAnchoPx, $iAlto, $sError)=archivos_ImagenPropiedades($sRutaFondo);
			$iAncho=medida_pixel_a_mm($iAnchoPx, 0);
			if ($iAncho>$objpdf->iAnchoTotal){$iAncho=$objpdf->iAnchoTotal;}
			if ($iAncho>0){
				$objpdf->sFondo=$sRutaFondo;
				$objpdf->iAnchoFondo=$iAncho;
				if ($objpdf->bDebug){
					p_AddDebug('Archivo de fondo '.$sRutaFondo.' 
Ancho '.$iAncho.' mm', $objpdf);
					}
				}else{
				p_AddDebug('No se pudo determinar el ancho del archivo de fondo '.$sRutaFondo, $objpdf);
				}
			}else{
			p_AddDebug('No se ha encontrado el archivo de fondo '.$sRutaFondo, $objpdf);
			}
		}
	}
function p_AddIndice($sTitulo, $iPagina, $iNivel, $objpdf){
	$objpdf->indCantidad++;
	$sNumeracion='';
	if ($objpdf->bNumerarTitulos){
		switch($iNivel){
			case 1:
			$objpdf->iNumTitulo1++;
			$objpdf->iNumTitulo2=0;
			$objpdf->iNumTitulo3=0;
			$sNumeracion=$objpdf->iNumTitulo1.$objpdf->sNumSepara;
			break;
			case 2:
			$objpdf->iNumTitulo2++;
			$objpdf->iNumTitulo3=0;
			$sNumeracion=$objpdf->iNumTitulo1.$objpdf->sNumMarca.$objpdf->iNumTitulo2.$objpdf->sNumSepara;
			break;
			case 3:
			$objpdf->iNumTitulo3++;
			$sNumeracion=$objpdf->iNumTitulo1.$objpdf->sNumMarca.$objpdf->iNumTitulo2.$objpdf->sNumMarca.$objpdf->iNumTitulo3.$objpdf->sNumSepara;
			break;
			}
		}
	$objpdf->indTitulo[$objpdf->indCantidad]=$sNumeracion.$sTitulo;
	$objpdf->indPag[$objpdf->indCantidad]=$iPagina;
	$objpdf->indNivel[$objpdf->indCantidad]=$iNivel;
	return $sNumeracion;
	}

function p_Firma($iIzquierda, $iAncho, $sNombre, $sCargo, $objpdf){
	$iAlto=p_Alto($objpdf);
	$objpdf->Line($iIzquierda, $iAlto, $iAncho+$iIzquierda, $iAlto);
	$objpdf->SetXY($iIzquierda, $iAlto+1);
	p_FuenteNormal($objpdf);
	$objpdf->MultiCell($iAncho, 4, ($sNombre), 0, 'C');
	if ($sCargo!=''){
		$objpdf->SetX($iIzquierda);
		p_FuentePequena($objpdf);
		$objpdf->MultiCell($iAncho, 3, ($sCargo), 0, 'C');
		}
	}
//Funciones para trabajo de datos.
function p_FuenteGrande($objpdf, $sEstilo=''){
	$objpdf->SetFont('Arial',$sEstilo,14);
	}
function p_FuenteNormal($objpdf, $sEstilo=''){
	$objpdf->SetFont('Arial',$sEstilo,12);
	}
function p_FuenteMedia($objpdf, $sEstilo=''){
	$objpdf->SetFont('Arial',$sEstilo,11);
	}
function p_FuentePequena($objpdf, $sEstilo=''){
	$objpdf->SetFont('Arial',$sEstilo,10);
	}
function p_FuenteMini($objpdf, $sEstilo=''){
	$objpdf->SetFont('Arial',$sEstilo,8);
	}
function p_FuenteGrandeV2($objpdf, $sEstilo='', $iFamilia=1){
	$sFamilia=$objpdf->sFuenteFamilia;
	if ($iFamilia==2){$sFamilia=$objpdf->sFuenteFamilia2;}
	$objpdf->SetFont($sFamilia,$sEstilo,$objpdf->iFuenteTamGrande);
	}
function p_FuenteNormalV2($objpdf, $sEstilo='', $iFamilia=1){
	$sFamilia=$objpdf->sFuenteFamilia;
	if ($iFamilia==2){$sFamilia=$objpdf->sFuenteFamilia2;}
	$objpdf->SetFont($sFamilia,$sEstilo,$objpdf->iFuenteTamNormal);
	}
function p_FuenteMediaV2($objpdf, $sEstilo='', $iFamilia=1){
	$sFamilia=$objpdf->sFuenteFamilia;
	if ($iFamilia==2){$sFamilia=$objpdf->sFuenteFamilia2;}
	$objpdf->SetFont($sFamilia,$sEstilo,$objpdf->iFuenteTamMedia);
	}
function p_FuentePequenaV2($objpdf, $sEstilo='', $iFamilia=1){
	$sFamilia=$objpdf->sFuenteFamilia;
	if ($iFamilia==2){$sFamilia=$objpdf->sFuenteFamilia2;}
	$objpdf->SetFont($sFamilia,$sEstilo,$objpdf->iFuenteTamPequena);
	}
function p_FuenteMiniV2($objpdf, $sEstilo='', $iFamilia=1){
	$sFamilia=$objpdf->sFuenteFamilia;
	if ($iFamilia==2){$sFamilia=$objpdf->sFuenteFamilia2;}
	$objpdf->SetFont($sFamilia,$sEstilo,$objpdf->iFuenteTamMini);
	}
function p_TituloGrande($sTitulo, $objpdf){
	$objpdf->Cell(1,3,'');
	$objpdf->Ln();
	$objpdf->SetFont('Arial','B',14);
	$objpdf->MultiCell($objpdf->iAnchoLibre,5,($sTitulo),0,'C');
	}
function p_TituloBloque($sTitulo, $objpdf){
	$objpdf->Cell(1,3,'');
	$objpdf->Ln();
	$objpdf->SetFont('Arial','B',12);
	$objpdf->Cell($objpdf->iAnchoLibre,5,($sTitulo),0,0,'C');
	$objpdf->Ln();
	}
function p_SubTituloBloque($sTitulo, $objpdf){
	$objpdf->SetFont('Arial','',11);
	$objpdf->Cell(10,5,'');
	$objpdf->Cell($objpdf->iAnchoLibre-10,5,($sTitulo));
	$objpdf->Ln();
	}
function p_Separador($objpdf){
	//Este es un espacio separador.
	$objpdf->Cell(1,3,'');
	$objpdf->Ln();
	}
function p_VerificarEspacioMinimo($objpdf, $iMilimetros){
	$y=$objpdf->GetY();
	$iMax=$objpdf->iAltoTotal-$objpdf->iBordeInferior-$iMilimetros;
	if ($y>$iMax){
		$objpdf->AddPage();
		}
	}
function p_DatosTercero($idTercero, $objdb, $bConDoc=true){
	$sRes='';
	$sql='SELECT sys11tipodoc, sys11doc, sys11razonsocial FROM sys11terceros WHERE sys11id='.$idTercero;
	$ttemp=$objdb->ejecutasql($sql);
	if ($objdb->nf($ttemp)>0){
		$ftemp=$objdb->sf($ttemp);
		if ($bConDoc){
			$sRes=$ftemp['sys11tipodoc'].$ftemp['sys11doc'].' '.$ftemp['sys11razonsocial'];
			}else{
			$sRes=$ftemp['sys11razonsocial'];
			}
		}
	return $sRes;
	}
function p_DatosTerceroV2($idTercero, $objdb){
	$ftemp=NULL;
	$sql='SELECT * FROM sys11terceros WHERE sys11id='.$idTercero;
	$ttemp=$objdb->ejecutasql($sql);
	if ($objdb->nf($ttemp)>0){
		$ftemp=$objdb->sf($ttemp);
		}
	return $ftemp;
	}
//Mostrar la información de depuración.
function p_PaginaDebug($objpdf){
	if ($objpdf->iFormato==1){
		$iPagina=$objpdf->PageNo();
		if(($iPagina%2)==0){$objpdf->AddPage();}
		}
	$objpdf->SetFont('Arial','',11);
	for ($k=1;$k<=$objpdf->iDebug;$k++){
		$sDato=$objpdf->aDebug[$k];
		$objpdf->MultiCell($objpdf->iAnchoLibre-20,5,($sDato));
		}
	}
//Pintar la pagina Incide
function p_PaginaIndice($objpdf){
	if ($objpdf->iFormato==1){
		$iPagina=$objpdf->PageNo();
		if(($iPagina%2)==0){$objpdf->AddPage();}
		}
	$sNumera=p_AddIndice('Indice', $objpdf->PageNo(), 1, $objpdf);
	$objpdf->SetFont('Arial','B',14);
	$objpdf->Cell($objpdf->iAnchoLibre,5,'I N D I C E',0,0,'C');
	$objpdf->Ln();
	$objpdf->SetFont('Arial','',12);
	for ($k=1;$k<=$objpdf->indCantidad;$k++){
		$iAnchoVineta=1;
		switch($objpdf->indNivel[$k]){
			case 1:
			$objpdf->Ln();
			break;
			case 2:
			$iAnchoVineta=5;
			break;
			case 3:
			$iAnchoVineta=10;
			}
		if ($objpdf->bNumerarTitulos){
			$iAnchoVineta=1;
			}
		//El problema es que el multicel puede saltar a una nueva pagina, por tanto hay que pintarlo primero.
		$pagIni=$objpdf->PageNo();
		$mx=$objpdf->GetX();
		$my=$objpdf->GetY();
		$objpdf->Cell($iAnchoVineta,5,''); //Marco la viñeta.
		$objpdf->MultiCell($objpdf->iAnchoLibre-($iAnchoVineta+10),5,utf8_decode($objpdf->indTitulo[$k]));//,0,0,'C'
		//Si estoy en la misma pagina, Vuelvo al origen.
		if ($pagIni==$objpdf->PageNo()){
			$objpdf->SetXY($mx,$my);
			}else{
			$iTop=20;
			//if ($objpdf->iFormato==1){$iTop=25;}
			$objpdf->SetY($iTop);
			}
		$objpdf->Cell($objpdf->iAnchoLibre-10,5,''); //Corro el carro al final de la linea.
		$objpdf->Cell(10,5,$objpdf->indPag[$k],0,0,'R');
		$objpdf->Ln();
		//$objpdf->SetXY($mx+$iAnchoVineta,$my);
		}
	}
function p_Rotar($objpdf, $angulo,$x=-1,$y=-1){
    if($x==-1){$x=$objpdf->x;}
    if($y==-1){$y=$objpdf->y;}
    if($objpdf->angulo!=0){$objpdf->_out('Q');}
    $objpdf->angulo=$angulo;
    if($angulo!=0){
        $angulo*=M_PI/180;
        $c=cos($angulo);
        $s=sin($angulo);
        $cx=$x*$objpdf->k;
        $cy=($objpdf->h-$y)*$objpdf->k;
        $objpdf->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy));
		}
    }
function P_RotarTexto($objpdf, $x, $y, $txt, $angulo){
    //Text rotated around its origin
    p_Rotar($objpdf, $angulo,$x,$y);
    $objpdf->Text($x,$y,$txt);
    p_Rotar($objpdf, 0);
	}
//Titulo de la entidad
function p_TituloEntidad($objpdf, $bConSeparador=true){
	$objpdf->SetFont('Arial','B',14);
	$sMensaje=$objpdf->filaent['sys24nombre'];
	$objpdf->Cell($objpdf->iAnchoLibre,5,utf8_decode($sMensaje),0,0,'C');
	$objpdf->Ln();
	if ($objpdf->filaent['sys11doc']!=''){
		$sMensaje=$objpdf->filaent['sys11tipodoc'].' '.$objpdf->filaent['sys11doc'];
		switch ($objpdf->filaent['sys24regimen']){
			case 'COC':
			$sMensaje=$sMensaje.utf8_encode(' Régimen Común');
			break;
			case 'COS':
			$sMensaje=$sMensaje.' Régimen Simplificado';
			break;
			}
		$objpdf->Cell($objpdf->iAnchoLibre,5,utf8_decode($sMensaje),0,0,'C');
		$objpdf->Ln();
		$objpdf->SetFont('Arial','',12);
		$sMensaje=$objpdf->filaent['sys11direccion'].' '.$objpdf->filaent['sys11telefono'];
		$objpdf->Cell($objpdf->iAnchoLibre,5,utf8_decode($sMensaje),0,0,'C');
		$objpdf->Ln();
		}
	if ($bConSeparador){
		p_Separador($objpdf);
		}
	}
// Intérprete de HTML
function p_WriteHTML($html, $objpdf, $iTamFila=5){
	$html = str_replace("\n",' ',$html);
	$a = preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
	foreach($a as $i=>$e){
		if($i%2==0){
			// Text
			if($objpdf->HREF){
				p_PutLink($objpdf->HREF,$e, $objpdf, $iTamFila);
				}else{
				$data=str_replace("[[","<",$e);
				$data=str_replace("]]",">",$data);
				$objpdf->Write($iTamFila,$data);
				}
			}else{
			// Etiqueta
			if($e[0]=='/'){
				p_CloseTag(strtoupper(substr($e,1)), $objpdf);
				}else{
				// Extraer atributos
				$a2 = explode(' ',$e);
				$tag = strtoupper(array_shift($a2));
				$attr = array();
				foreach($a2 as $v){
				if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
				$attr[strtoupper($a3[1])] = $a3[2];
				}
			p_OpenTag($tag, $attr, $objpdf, $iTamFila);
			}
		}
	}
	}
function p_OpenTag($tag, $attr, $objpdf, $iTamFila=5){
	// Etiqueta de apertura
	if($tag=='B' || $tag=='I' || $tag=='U')
	p_SetStyle($tag,true, $objpdf);
	if($tag=='A')
	$objpdf->HREF = $attr['HREF'];
	if($tag=='BR')
	$objpdf->Ln($iTamFila);
	}
function p_CloseTag($tag, $objpdf){
	// Etiqueta de cierre
	if($tag=='B' || $tag=='I' || $tag=='U')
	p_SetStyle($tag,false, $objpdf);
	if($tag=='A')
	$objpdf->HREF = '';
	}
function p_SetStyle($tag, $enable, $objpdf){
	// Modificar estilo y escoger la fuente correspondiente
	$objpdf->$tag += ($enable ? 1 : -1);
	$style = '';
	foreach(array('B', 'I', 'U') as $s){
		if($objpdf->$s>0)
		$style .= $s;
		}
	$objpdf->SetFont('',$style);
	}
function p_PutLink($URL, $txt, $objpdf, $iTamFila=5){
	// Escribir un hiper-enlace
	$objpdf->SetTextColor(0,0,255);
	p_SetStyle('U',true, $objpdf);
	$objpdf->Write($iTamFila,$txt,$URL);
	p_SetStyle('U',false, $objpdf);
	$objpdf->SetTextColor(0);
	}

?>