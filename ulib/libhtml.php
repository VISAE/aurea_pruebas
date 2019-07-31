<?php
/*
--- © Angel Mauro Avellaneda Barreto - Ideas - 2016 ---
--- mauro@avellaneda.co - http://www.ideasw.com
--- Modelo Versión 2.13.3 miércoles, 13 de julio de 2016
*/
class clsHtmlCombos{
	var $bConVacio=true;
	var $bConDebug=false;
	var $aItem=array();
	var $iItems=0;
	// Los origenes es de donde tomamos los datos 0= manual, 1=sql  la consulta debe ser enviada.
	var $iOrigen=0;
	var $sAccion='';
	var $sClaseCombo='';
	var $sEtiVacio='';
	var $sNombre='';
	var $sVrVacio='';
	var $sValorCombo='';
	function addArreglo($aDatos, $iCantidad, $sEstilo=''){
		for ($k=1;$k<=$iCantidad;$k++){
			$bAdiciona=true;
			if (isset($aDatos[$k])==0){$aDatos[$k]='';}
			if ($aDatos[$k]==''){$bAdiciona=false;}
			if ($bAdiciona){
				$this->iItems++;
				$i=$this->iItems;
				$this->aItem[$i]['v']=$k;
				$this->aItem[$i]['e']=cadena_notildes($aDatos[$k]);
				$this->aItem[$i]['c']=$sEstilo;
				}
			}
		}
	function addItem($sValor, $sEtiqueta, $sEstilo=''){
		$this->iItems++;
		$i=$this->iItems;
		$this->aItem[$i]['v']=$sValor;
		$this->aItem[$i]['e']=$sEtiqueta;
		$this->aItem[$i]['c']=$sEstilo;
		}
	function comboSistema($idModulo, $iConsec, $objdb, $sAccion=''){
		$this->sAccion=$sAccion;
		$sql='SELECT sys54codopcion, sys54nombre FROM sys54combos WHERE sys54idmodulo='.$idModulo.' AND sys54consec='.$iConsec.' AND sys54activa="S" ORDER BY sys54orden, sys54nombre';
		$tablac=$objdb->ejecutasql($sql);
		while ($fila=$objdb->sf($tablac)){
			$this->addItem($fila['sys54codopcion'], $fila['sys54nombre']);
			}
		return $this->html('');
		}
	function comun($idTabla, $objdb){
		$sql='';
		switch($idTabla){
			case 1101:$sql='SELECT gen01id AS id, CONCAT(CASE gen01activa WHEN "S" THEN "" ELSE "[INACTIVA] " END, gen01nombre) AS nombre FROM gen01sede WHERE gen01identidad='.$_SESSION['u_identidad'].' ORDER BY gen01activa DESC, gen01nombre';break;
			case 1102:$sql='SELECT gen02codigo AS id, gen02codigo AS nombre FROM gen02agno WHERE gen02identidad='.$_SESSION['u_identidad'].' ORDER BY gen02codigo DESC';break;
			}
		if ($sql!=''){
			$tablac=$objdb->ejecutasql($sql);
			while ($fila=$objdb->sf($tablac)){
				$this->addItem($fila['id'], $fila['nombre']);
				}
			}
		}
	function debug($bDebug=true){
		$this->bConDebug=$bDebug;
		}
	function html($sConsulta='', $objdb=NULL, $iComun=0){
		if ($iComun!=0){
			$this->comun($iComun, $objdb);
			$sConsulta='';
			}
		$sDebug='';
		$sRes='';
		$sAccion='';
		if ($this->sAccion!=''){$sAccion=' onChange="'.$this->sAccion.'"';}
		$sRes='<select id="'.$this->sNombre.'" name="'.$this->sNombre.'"'.$sAccion.'>';
		if ($this->bConVacio){
			$sEstilo='';
			if ($this->sVrVacio===''){$sEstilo=' style="color:#FF0000"';}
			$sRes=$sRes.'<option value="'.$this->sVrVacio.'"'.$sEstilo.'>'.$this->sEtiVacio.'</option>';
			}
		for ($k=1;$k<=$this->iItems;$k++){
			$sSel='';
			$sEstilo='';
			if ($this->aItem[$k]['v']==$this->sValorCombo){$sSel=' selected';}
			if ($this->aItem[$k]['c']!=''){$sEstilo=' style="'.$sEstilo.'"';}
			$sRes=$sRes.'<option value="'.$this->aItem[$k]['v'].'"'.$sSel.$sEstilo.'>'.cadena_notildes($this->aItem[$k]['e']).'</option>';
			}
		if ($sConsulta!=''){
			$sEstilo='';
			$tablac=$objdb->ejecutasql($sConsulta);
			if ($this->bConDebug){
				if ($tablac==false){$sDebug=$sConsulta;}
				}
			while ($fila=$objdb->sf($tablac)){
				$sSel='';
				if ($fila['id']==$this->sValorCombo){$sSel=' selected';}
				$sRes=$sRes.'<option value="'.$fila['id'].'"'.$sSel.$sEstilo.'>'.cadena_notildes($fila['nombre']).'</option>';
				}
			}
		$sRes=$sRes.'</select>'.$sDebug;
		return utf8_encode($sRes);
		}
	function meses($sEstilo=''){
		$sMeses=array('','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
		for ($k=1;$k<=12;$k++){
			$this->addItem($k, $sMeses[$k], $sEstilo);
			}
		}
	function nuevo($sNombre, $sValorCombo='', $bConVacio=true, $sEtiVacio='{Seleccione Uno}', $sVrVacio=''){
		$this->bConVacio=$bConVacio;
		$this->aItem=array();
		$this->iItems=0;
		$this->sAccion='';
		$this->sClaseCombo='';
		$this->sEtiVacio=$sEtiVacio;
		$this->sNombre=$sNombre;
		$this->sVrVacio=$sVrVacio;
		$this->sValorCombo=$sValorCombo;
		}
	function numeros($iNumIni, $iNumFin, $iOrden=0, $sEstilo=''){
		if ($iOrden==0){
			for ($k=$iNumIni;$k<=$iNumFin;$k++){
				$this->addItem($k, $k, $sEstilo);
				}
			}else{
			for ($k=$iNumFin;$k>=$iNumIni;$k--){
				$this->addItem($k, $k, $sEstilo);
				}
			}
		}
	function sino($sEtiquetaSi='Si', $sEtiquetaNo='No', $sValorSi='S', $sValorNo='N', $sEstiloSi='', $sEstiloNo=''){
		$this->addItem($sValorSi, $sEtiquetaSi, $sEstiloSi);
		$this->addItem($sValorNo, $sEtiquetaNo, $sEstiloNo);
		}
	function __construct($sNombre, $sValorCombo='', $bConVacio=true, $sEtiVacio='{Seleccione Uno}', $sVrVacio=''){
		$this->nuevo($sNombre, $sValorCombo, $bConVacio, $sEtiVacio);
		}
	}
class clsHtmlCuerpoItem{
var $iTipo=0; 
/*
-- Tipos Aceptados: 0 HTML puro. 1 - Etiqueta. 100 - Salto de linea; 
-- 1000 Un bloque HTML (Es decir un grupo campos.)
*/
var $bNegrilla=false;
var $bAlerta=false;
var $sCuerpo='';
var $sEstilo='';
var $objBloque=NULL;
function __construct($iTipo, $sCuerpo=''){
	$this->iTipo=$iTipo;
	$this->sCuerpo=$sCuerpo;
	}
}
class clsHtmlCuerpo{
var $iEstilo=1;
var $aItems=array();
var $iItems=0;
function addBloque($objBloque, $sEstilo=''){
	$objItem=new clsHtmlCuerpoItem(1000);
	$objItem->objBloque=$objBloque;
	$objItem->sEstilo=$sEstilo;
	$this->iItems++;
	$this->aItems[$this->iItems]=$objItem;
	return $this->iItems;
	}
function addEtiqueta($sContenido, $sEstilo='', $bNegrilla=false){
	$objItem=new clsHtmlCuerpoItem(1, $sContenido);
	$objItem->sEstilo=$sEstilo;
	$objItem->bNegrilla=$bNegrilla;
	$this->iItems++;
	$this->aItems[$this->iItems]=$objItem;
	return $this->iItems;
	}
function addHTML($sCuerpo){
	$objItem=new clsHtmlCuerpoItem(0);
	$objItem->sCuerpo=$sCuerpo;
	$this->iItems++;
	$this->aItems[$this->iItems]=$objItem;
	return $this->iItems;
	}
function addSalto(){
	$objItem=new clsHtmlCuerpoItem(100);
	$this->iItems++;
	$this->aItems[$this->iItems]=$objItem;
	return $this->iItems;
	}
function armarBoton($sNombre, $sAccion='', $sTitulo='', $sClase='', $sDescripcion=''){
	$sRes='';
	switch ($this->iEstilo){
		case 2:
		break;
		default:
		$hTitulo='';
		if ($sTitulo!=''){$hTitulo=' value="'.$sTitulo.'"';}
		$hClase='';
		switch($sClase){
			case 'proceso':$hClase=' class="botonProceso"';break;
			}
		$hAccion='';
		if ($sAccion!=''){$hAccion=' onclick="'.$sAccion.'"';}
		$hDesc='';
		if ($sDescripcion!=''){$hDesc=' title="'.$sDescripcion.'"';}
		$sRes='<input id="'.$sNombre.'" name="'.$sNombre.'" type="button"'.$hTitulo.$hClase.$hAccion.$hDesc.'/>';
		break;
		}
	return $sRes;
	}
function html(){
	$sRes='';
	for ($k=1;$k<=$this->iItems;$k++){
		$objItem=$this->aItems[$k];
		switch($objItem->iTipo){
			case 0: //HTML puro.
			$sRes=$sRes.$objItem->sCuerpo;
			break;
			case 1: //Etiqueta.
			switch ($this->iEstilo){
				case 2:
				break;
				default:
				$sComp='';
				switch($objItem->sEstilo){
					case '30': $sComp=' class="Label30"';break;
					case '60': $sComp=' class="Label60"';break;
					case '90': $sComp=' class="Label90"';break;
					case '130': $sComp=' class="Label130"';break;
					case '160': $sComp=' class="Label160"';break;
					case '200': $sComp=' class="Label200"';break;
					case 'AreaS': $sComp=' class="txtAreaS"';break;
					case 'L': $sComp=' class="L"';break;
					}
				$sPrev='';
				$sPost='';
				if ($objItem->bNegrilla){
					$sPrev='<b>';
					$sPost='</b>';
					}
				if ($objItem->bAlerta){
					$sPrev='<span class="rojo">'.$sPrev;
					$sPost=$sPost.'</span>';
					}
				$sRes=$sRes.'<label'.$sComp.'>'.$sPrev.$objItem->sCuerpo.$sPost.'</label>';
				break;
				}
			break;
			case 100: //Salto de linea.
			switch ($this->iEstilo){
				case 2:
				break;
				default:
				$sRes=$sRes.'<div class="salto1px"></div>';
				break;
				}
			break;
			case 1000: //Grupo campos.
			$sPrev='<div class="GrupoCampos">'.$this->iEstilo;
			$sPost='<div class="salto1px"></div></div>';
			switch ($objItem->sEstilo){
				case 450:
				$sPrev='<div class="GrupoCampos450">';
				break;
				default:
				break;
				}
			$sRes=$sRes.$sPrev.$objItem->objBloque->html().$sPost;
			break;
			}
		//Termina de recorrer cada item.
		}
	return $sRes;
	}
function __construct($iEstilo=1){
	$this->iEstilo=$iEstilo;
	}
}
class clsHtmlFecha{
	var $sNombre='';
	var $sValor='';
	function __construct($sNombre, $sValor){
		$this->sNombre=$sNombre;
		$this->sValor=$sValor;
		}
	}
class clsHtmlForma{
var $iPiel=0;
var $sAddEstiloTitulo='';
var $aBotones=array();
var $iBotones=0;
function addBoton($sNombre, $sClase, $sAccion, $sTitulo){
	$this->iBotones++;
	$k=$this->iBotones;
	$this->aBotones[$k]['nombre']=$sNombre;
	$this->aBotones[$k]['clase']=$sClase;
	$this->aBotones[$k]['accion']=$sAccion;
	$this->aBotones[$k]['titulo']=$sTitulo;
	}
function htmlBotonSolo($sNombre, $sClase, $sAccion, $sTitulo, $iLabel=0, $sAdicional=''){
	$res='';
	$sAddB='';
	if ($sAdicional!=''){$sAddB=' '.$sAdicional;}
	switch($this->iPiel){
		case 0:
		$sClaseFin=$sClase;
		switch ($sClase){
			case 'btSoloReasignar':
			$sClaseFin='botonProceso';
			break;
			}
		$res='<input id="'.$sNombre.'" name="'.$sNombre.'" type="button" value="'.$sTitulo.'" class="'.$sClaseFin.'" onclick="'.$sAccion.'" title="'.$sTitulo.'"'.$sAddB.'/>
';
		break;
		default:
		$bEntra=true;
		$bLargo=false;
		$sImgLnk='../ulib/img/btUpAyuda.jpg';
		$sClaseFin='BotonAzul';
		$sAdd='';
		switch($sClase){
			case 'botonProceso':
			$res='<input id="'.$sNombre.'" name="'.$sNombre.'" type="button" value="'.$sTitulo.'" class="image" data-icono="../../img/pinon.png" onclick="'.$sAccion.'" title="'.$sTitulo.'"'.$sAddB.'/>';
			//$res='<a id="'.$sNombre.'" name="'.$sNombre.'" href="'.$sAccion.'" class="image" data-icono="pinon.png">'.$sTitulo.'</a>';
			$bEntra=false;
			break;
			case 'btEnviarExcel':
			case 'btMiniExcel':
			$sImgLnk='../ulib/img/excel.png';
			break;
			case 'btEnviarPDF':
			$sImgLnk='../ulib/img/pdf.png';
			break;
			case 'btGuardarHoja':
			$sImgLnk='../ulib/img/hoja-guardar.png';
			break;
			case 'btGuardarS';
			case 'btMiniGuardar':
			$sImgLnk='../ulib/img/guardar18.png';
			break;
			case 'btMiniActualizar':
			$sImgLnk='../ulib/img/recarga18.png';
			break;
			case 'btMiniBuscar':
			$sImgLnk='../ulib/img/lupa18.png';
			break;
			case 'btMiniEliminar':
			$sImgLnk='../ulib/img/x18.png';
			break;
			case 'btMiniHoy':
			$sImgLnk='../ulib/img/h18.png';
			break;
			case 'btMiniLimpiar':
			$sImgLnk='../ulib/img/hoja18.png';
			break;
			case 'btMiniPersona':
			$sImgLnk='../ulib/img/persona18.png';
			break;
			case 'btSupCerrar':
			$sImgLnk='../ulib/img/hoja-check.png';
			break;
			case 'btSupVolver':
			$sImgLnk='../ulib/img/btSupVolver.jpg';
			break;
			case 'btUpEliminar':
			$sImgLnk='../ulib/img/x.png';
			break;
			case 'btUpGuardar':
			case 'btSoloGuardar':
			$sImgLnk='../ulib/img/guardar.png';
			break;
			case 'btUpLimpiar':
			$sImgLnk='../ulib/img/hoja.png';
			break;
			}
		if ($bLargo){
			$res='<input id="'.$sNombre.'" name="'.$sNombre.'" type="button" value="'.$sTitulo.'" class="image" onclick="'.$sAccion.'" title="'.$sTitulo.'" data-icono="'.$sImgNombre.'" style="background-image: url(\''.$sImgLnk.'\');"'.$sAddB.'/>
';
			}else{
			//$res='<button id="'.$sNombre.'" name="'.$sNombre.'" type="button" onClick="'.$sAccion.'" title="'.$sTitulo.'"'.$sAddB.'><img src="'.$sImgLnk.'"'.$sAdd.'></button>';
			$res='<input id="'.$sNombre.'" name="'.$sNombre.'" type="button" value="'.$sTitulo.'"  onClick="'.$sAccion.'" class="'.$sClaseFin.'" title="'.$sTitulo.'"/>';
			}
		break;
		}
	switch($iLabel){
		case 30:
		$res='<label class="Label30">'.$res.'</label>';
		break;
		case 130:
		$res='<label class="Label130">'.$res.'</label>';
		break;
		case 160:
		$res='<label class="Label160">'.$res.'</label>';
		case 200:
		$res='<label class="Label200">'.$res.'</label>';
		case 250:
		$res='<label class="Label250">'.$res.'</label>';
		break;
		}
	return $res;
	}
function htmlInicioMarco($sTitulo=''){
	$res='';
	$sHtmlTitulo='';
	switch($this->iPiel){
		default:
		if ($sTitulo!=''){$sHtmlTitulo='<div id="titulo"><h3>'.$sTitulo.'</h3></div>';}
		$res='<div class="areaform">'.$sHtmlTitulo.'
<div class="areatrabajo">
';
		break;
		}
	return $res;
	}
function htmlInicioMarcoSimple(){
	$res='';
	switch($this->iPiel){
		case 0:
		$res='<div id="cargaForm">';
		break;
		default:
		$res='';
		break;
		}
	return $res;
	}
function htmlFinMarco(){
	$res='';
	switch($this->iPiel){
		default:
		$res='</div>
</div>
';
		break;
		}
	return $res;
	}
function htmlFinMarcoSimple(){
	$res='';
	switch($this->iPiel){
		case 0:
		$res='</div>';
		break;
		default:
		$res='';
		break;
		}
	return $res;
	}
function htmlEspere($sMsgEspere='Este proceso puede tomar algunos momentos, por favor espere...'){
	$res='<div class="MarquesinaMedia">'.$sMsgEspere.'</div>';
	return $res;
	}
function htmlExpande($sCodigo, $iValor, $sTituloMostrar='Mostrar', $sTituloOcultar='Ocultar'){
	$res='<input id="boculta'.$sCodigo.'" name="boculta'.$sCodigo.'" type="hidden" value="'.$iValor.'" />';
	$sEstado1='none';
	$sEstado2='block';
	if ($iValor!=0){
		$sEstado1='block';
		$sEstado2='none';
		}
	switch($this->iPiel){
		case 0:
		$res=$res.'<label class="Label30">
<input id="btexpande'.$sCodigo.'" name="btexpande'.$sCodigo.'" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel('.$sCodigo.',\'block\',0);" title="'.$sTituloMostrar.'" style="display:'.$sEstado1.';"/>
</label>
<label class="Label30">
<input id="btrecoge'.$sCodigo.'" name="btrecoge'.$sCodigo.'" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel('.$sCodigo.',\'none\',1);" title="'.$sTituloOcultar.'" style="display:'.$sEstado2.';"/>
</label>';
		break;
		default:
		$res=$res.'<label class="Label30">
<button id="btexpande'.$sCodigo.'" name="btexpande'.$sCodigo.'" type="button" onClick="javascript:expandepanel('.$sCodigo.',\'block\',0);" title="'.$sTituloMostrar.'" style="display:'.$sEstado1.';"><img src="../ulib/img/fl-abajo18.png"/></button>
</label>
<label class="Label30">
<button id="btrecoge'.$sCodigo.'" name="btrecoge'.$sCodigo.'" type="button" onClick="javascript:expandepanel('.$sCodigo.',\'none\',1);" title="'.$sTituloOcultar.'" style="display:'.$sEstado2.';"><img src="../ulib/img/fl-arriba18.png"/></button>
</label>';
		break;
		}
	return $res;
	}
function htmlTitulo($sTitulo, $iCodModulo, $sId=''){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$res=''.$sTitulo.'';
	$sAddE='';
	$sAddId='';
	if ($this->sAddEstiloTitulo!=''){
		$sAddE=' style="'.$this->sAddEstiloTitulo.'"';
		}
	if ($sId!=''){
		$sAddId=' id="'.$sId.'"';
		}
	switch ($this->iPiel){
		default:
		$res='<div class="titulos">
<div class="titulosD">';
		for ($k=1;$k<=$this->iBotones;$k++){
			$sBoton=$this->htmlBotonSolo($this->aBotones[$k]['nombre'], $this->aBotones[$k]['clase'], $this->aBotones[$k]['accion'], $this->aBotones[$k]['titulo']);
			$res=$res.$sBoton;
			}
		$sComp1='';
		$sComp2='';
		if ($sAddE==''){
			$sComp1='<h2>';
			$sComp2='</h2>';
			}
		$res=$res.'</div>
<div class="titulosI"'.$sAddE.$sAddId.'>'.$sComp1.$sTitulo.$sComp2.'</div>
</div>';
		break;
		}
	return $res;
	}
function __construct($iPiel){
	$this->iPiel=$iPiel;
	}
}
class clsHtmlMenu{
var $iPiel=1;
var $iSuperior=0;
var $aMenu=array();
var $iMenu=0;
function addItem($sTitulo, $sDestino){
	$this->iMenu++;
	$k=$this->iMenu;
	$this->aMenu[$k]['titulo']=$sTitulo;
	$this->aMenu[$k]['destino']=$sDestino;
	}
function htmlMenu(){
	$sPrimera='';
	$sClaseMSup='';
	$sPrevSup='';
	$sPostSup='';
	if ($this->iPiel==0){
		$sPrimera='<li class="ini"></li>';
		if ($this->iSuperior==1){
			$sClaseMSup=' class="ppal"';
			}
		$sPrevSup='<span>';
		$sPostSup='</span>';
		}
	$res='';
	for ($k=1;$k<=$this->iMenu;$k++){
		$etiqueta=$this->aMenu[$k]['titulo'];
		$sDestino=$this->aMenu[$k]['destino'];
		$res=$res.'<li><a href="'.$sDestino.'"'.$sClaseMSup.'>'.$sPrevSup.''.$etiqueta.''.$sPostSup.'</a></li>';
		}
	return $res;
	}
function __construct($iPiel=1, $iSuperior=0){
	$this->iPiel=$iPiel;
	$this->iSuperior=$iSuperior;
	}
}
function html_BotonAyuda($sNombreCampo, $sTituloCampo='Informaci&oacute;n relevante'){
	$res='<label class="Label30">
<input id="cmdAyuda_'.$sNombreCampo.'" name="cmdAyuda_'.$sNombreCampo.'" type="button" class="btMiniAyuda" onclick="AyudaLocal(\''.$sNombreCampo.'\');" title="'.$sTituloCampo.'" />
</label>';
	return $res;
	}
function html_DivAlarmaV2($sError, $iTipoError, $bDebug=false){
	$sClase='';
	$iMomento=0;
	if ($bDebug){$sError=$sError.' -- '.$iTipoError.'';}
	if ($iTipoError==''){$iTipoError=0;}
	if ($sError!=''){
		$sClase=' class="alarma_roja"';
		$bPasa=false;
		if ($iTipoError==1){$bPasa=true;$iMomento=1;}
		if ($iTipoError==='verde'){$bPasa=true;$iMomento=2;}
		if ($bPasa){
			$sClase=' class="alarma_verde"';
			$iTipoError=1;
			}
		$bPasa=false;
		if ($iTipoError==2){$bPasa=true;$iMomento=3;}
		if ($iTipoError==='azul'){$bPasa=true;$iMomento=4;}
		if ($bPasa){
			$sClase=' class="alarma_azul"';
			$iTipoError=2;
			}
		if (strlen($sError)>1000){$sError='<div class="divScroll200">'.$sError.'</div>';}
		}
	if ($bDebug){$sError=$sError.' -- '.$iMomento.'';}
	$sRes='<div id="div_alarma"'.$sClase.'>'.$sError.'</div>';
	return $sRes;
	}
function html_DivAyudaLocal($sNombreCampo){
	$res='<div class="salto1px"></div>
<div id="div_ayuda_'.$sNombreCampo.'" style="display:none" class="GrupoCamposAyuda"></div>
<div class="salto1px"></div>';
	return $res;
	}
function html_DivTerceroV2($sNombreCampo, $sTipoDoc, $sDoc, $bOculto, $idAccion=0, $sPlaceHolder='', $iBotones=3){
	return html_DivTerceroV3($sNombreCampo, $sTipoDoc, $sDoc, $bOculto, 0, $idAccion, $sPlaceHolder, $iBotones);
	}
function html_DivTerceroV3($sNombreCampo, $sTipoDoc, $sDoc, $bOculto, $iPiel, $idAccion=0, $sPlaceHolder='', $iBotones=3){
	$sRes='';
	if ($bOculto){
		$sRes='<label class="Label350">'.html_oculto($sNombreCampo.'_td',$sTipoDoc).' '.html_oculto($sNombreCampo.'_doc',$sDoc).'</label>';
		}else{
		$sAdd='';
		if ($sPlaceHolder!=''){$sAdd=' placeholder="'.$sPlaceHolder.'"';}
		$sRes=html_tipodocV2($sNombreCampo.'_td', $sTipoDoc, "ter_muestra('".$sNombreCampo."', ".$idAccion.")", false).'
<input id="'.$sNombreCampo.'_doc" name="'.$sNombreCampo.'_doc" type="text" value="'.$sDoc.'" onchange="ter_muestra(\''.$sNombreCampo.'\','.$idAccion.')" maxlength="13" onclick="revfoco(this);"'.$sAdd.'/>
</label>';
		$bConbuscar=false;
		$bConCrear=false;
		switch($iBotones){
			case 1:
			$bConbuscar=true;
			break;
			case 3:
			$bConbuscar=true;
			break;
			}
		if ($bConbuscar){
			$sRes=$sRes.'<label class="Label30">
<input type="button" name="b'.$sNombreCampo.'" value="Buscar" class="btMiniBuscar" onclick="buscarV2016(\''.$sNombreCampo.'\')" title="Buscar Tercero"/>
</label>';
			}
		if ($bConCrear){
			$sRes=$sRes.'<label class="Label30">
<input type="button" name="c'.$sNombreCampo.'" value="Buscar" class="btMiniPersona" onclick="ter_crea(\''.$sNombreCampo.'\','.$idAccion.')" title="Crear Tercero"/>';
			}
		}
	return '<label class="Label350">'.$sRes.'</label>';
	}
function html_FechaEnNumero($nomcampo, $valor=0, $bvacio=false, $accion='' ,$iagnoini=0,$iagnofin=0,$idiafijo=0,$imesfijo=0){
	if (!$bvacio){
		if ((int)$valor==0){$valor=fecha_DiaMod();}
		}
	list($_dia, $_mes, $_agno)=fecha_DividirNumero($valor);
	if ($iagnoini==0){$iagnoini=2000;}
	if ($iagnofin==0){
		if ($_agno==0){
			$iagnofin=date('Y')+5;
			}else{
			$iagnofin=$_agno+5;
			}
		}
	$res='';
	if ($idiafijo==0){
		$res=html_ComboDia($nomcampo.'_dia', $_dia, $bvacio, 'fecha_AjustaNum(\''.$nomcampo."','".$accion.'\');');
		}else{
		$svr=$idiafijo;
		if ($idiafijo<10){$svr='0'.$svr;}
		$res=$res.'<input id="'.$nomcampo.'_dia" name="'.$nomcampo.'_dia" type="hidden" value="'.$svr.'"/>&nbsp;<b>'.$svr.'/</b>';
		}
	$res=$res.' '.html_ComboMes($nomcampo.'_mes', $_mes, $bvacio, 'fecha_AjustaNum('."'".$nomcampo."','".$accion."'".')').' ';
	if ($iagnofin<$iagnoini){$iagnofin=$iagnoini;}
	$bconagno=true;
	if ($iagnofin==$iagnoini){$bconagno=false;}
	if ($bconagno){
		$res=$res.'<select id="'.$nomcampo.'_agno" name="'.$nomcampo.'_agno" onchange="fecha_AjustaNum('."'".$nomcampo."','".$accion."'".')">
';
		if ($bvacio){$res=$res.'<option value="0"></option>
';}
		for ($size=$iagnofin;$size>=$iagnoini;$size--){
			$ssel='';
			if ($size==$_agno){$ssel=' selected';}
			$res=$res.'<option'.$ssel.' value="'.$size.'">'.$size.'</option>
';
			}
		$res=$res.'</select>
';
		}else{
		$res=$res.'<input id="'.$nomcampo.'_agno" name="'.$nomcampo.'_agno" type="hidden" value="'.$iagnoini.'"/>&nbsp;<b>/'.$iagnoini.'</b>
';
		}
	if (trim($valor)==''){$valor='0';}
	$res=$res.'<input id="'.$nomcampo.'" name="'.$nomcampo.'" type="hidden" value="'.$valor.'"/>
';
	return $res;
	}
function html_NoPermiso($iCodModulo, $sTituloModulo, $iPiel=0){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$sCambiaSesion=$ETI['msg_nopermiso'];
	if ($_SESSION['u_idtercero']==0){
		$sCambiaSesion=''.$ETI['msg_nosesion'];
		}
	switch($iPiel){
		default:
		$rRes='<div id="interna">
<form id="frmedita" name="frmedita" method="post" action="">
<div id="titulacion">
<div id="titulacionD">
<input id="cmdAyuda" name="cmdAyuda" type="button" class="btUpAyuda" onclick="muestraayuda('.$iCodModulo.');" title="'.$ETI['bt_ayuda'].'" value="'.$ETI['bt_ayuda'].'"/>
</div>
<div id="titulacionI">
<h2>'.$sTituloModulo.'</h2>
</div>
</div>
<div id="cargaForm">
<div id="area">
<div class="MarquesinaMedia">
'.$sCambiaSesion.'
</div>
</div>
</div>
</form>
</div>';
		break;
		}
	return $rRes;
	}
function html_notaV3($nota,$bocultacero=true, $iVrAprueba=3, $iVrMaximo=5, $iDecimales=1){
	$res='';
	if ($nota<=0){
		if (!$bocultacero){$res='<font class="rojo">'.formato_numero(0, $iDecimales).'</font>';}
		}else{
		$sMuestra=formato_numero($nota, $iDecimales);
		if ($nota<$iVrAprueba){
			$res='<font class="rojo">'.$sMuestra.'</font>';
			}else{
			if ($nota<=$iVrMaximo){
				$res='<font class="verde">'.$sMuestra.'</font>';
				}
			}
		}
	return $res;
	}
?>