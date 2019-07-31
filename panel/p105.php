<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2015 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 2.7.10 miércoles, 10 de junio de 2015
*/
//error_reporting(E_ALL);
//ini_set("display_errors", 1);
if (!file_exists('app.php')){
	echo '<b>Error N 1 de instalaci&oacute;n</b><br>No se ha establecido un archivo de configuraci&oacute;n, por favor comuniquese con el administrador del sistema.';
	die();
	}
require 'app.php';
require $APP->rutacomun.'unad_todas.php';
require $APP->rutacomun.'libs/clsdbadmin.php';
require $APP->rutacomun.'unad_librerias.php';
require $APP->rutacomun.'fpdf/fpdf.php';
require $APP->rutacomun.'libp.php';
class clsPDF extends FPDF{
	var $iFormato=0;
	var $iAnchoLibre=186;
	var $iAnchoTotal=216;
	var $iAltoTotal=297;
	var $iBordeEncabezado=10;
	var $iBordeSuperior=25;
	var $iBordeInferior=10;
	var $iReporte=0;
	var $iFirmaReporte='http://www.unad.edu.co';
	var $bConPagina=true;
	var $filaent=NULL;
	var $filaentorno=NULL;
	var $sError;
	var $sRefRpt='';
	//var $smes=array('','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
	var $xPrevia=0;
	var $yPrevia=0;
	//se Crean porque no permite en modo seguro tenerlas en forma implicita
	var $HREF='';
	var $I='';
	var $U='';
	//Armado del indice
	var $bNumerarTitulos=true;
	var $iNumTitulo1=0;
	var $iNumTitulo2=0;
	var $iNumTitulo3=0;
	var $sNumSepara=' ';
	// -- Funciones para encriptar
	var $encrypted = false;
	var $Uvalue;
	var $Ovalue;
	var $Pvalue;
	var $enc_obj_id;
	function SetProtection($permissions=array(), $user_pass='', $owner_pass=null){
		$options = array('print' => 4, 'modify' => 8, 'copy' => 16, 'annot-forms' => 32 );
		$protection = 192;
		foreach($permissions as $permission){
			if (!isset($options[$permission])){
				$this->Error('Incorrect permission: '.$permission);
				}
			$protection += $options[$permission];
			}
		if ($owner_pass === null){
			$owner_pass = uniqid(rand());
			}
		$this->encrypted = true;
		$this->padding = "\x28\xBF\x4E\x5E\x4E\x75\x8A\x41\x64\x00\x4E\x56\xFF\xFA\x01\x08\x2E\x2E\x00\xB6\xD0\x68\x3E\x80\x2F\x0C\xA9\xFE\x64\x53\x69\x7A";
		$this->_generateencryptionkey($user_pass, $owner_pass, $protection);
		}
	function _putstream($s){
		if ($this->encrypted){
			$s = RC4($this->_objectkey($this->n), $s);
			}
		parent::_putstream($s);
		}
	function _textstring($s){
		if ($this->encrypted){
			$s=RC4($this->_objectkey($this->n), $s);
			}
		return parent::_textstring($s);
		}
	function _objectkey($n){
		return substr($this->_md5_16($this->encryption_key.pack('VXxx',$n)),0,10);
		}
	function _putresources(){
		parent::_putresources();
		if ($this->encrypted){
			$this->_newobj();
			$this->enc_obj_id = $this->n;
			$this->_out('<<');
			$this->_putencryption();
			$this->_out('>>');
			$this->_out('endobj');
			}
		}
	function _putencryption(){
		$this->_out('/Filter /Standard');
		$this->_out('/V 1');
		$this->_out('/R 2');
		$this->_out('/O ('.$this->_escape($this->Ovalue).')');
		$this->_out('/U ('.$this->_escape($this->Uvalue).')');
		$this->_out('/P '.$this->Pvalue);
		}
	function _puttrailer(){
		parent::_puttrailer();
		if ($this->encrypted){
			$this->_out('/Encrypt '.$this->enc_obj_id.' 0 R');
			$this->_out('/ID [()()]');
			}
		}
	function _md5_16($string){
		return pack('H*',md5($string));
		}
	function _Ovalue($user_pass, $owner_pass){
		$tmp = $this->_md5_16($owner_pass);
		$owner_RC4_key = substr($tmp,0,5);
		return RC4($owner_RC4_key, $user_pass);
		}
	function _Uvalue(){
		return RC4($this->encryption_key, $this->padding);
		}
	function _generateencryptionkey($user_pass, $owner_pass, $protection){
		$user_pass = substr($user_pass.$this->padding,0,32);
		$owner_pass = substr($owner_pass.$this->padding,0,32);
		$this->Ovalue = $this->_Ovalue($user_pass,$owner_pass);
		$tmp = $this->_md5_16($user_pass.$this->Ovalue.chr($protection)."\xFF\xFF\xFF");
		$this->encryption_key = substr($tmp,0,5);
		$this->Uvalue = $this->_Uvalue();
		$this->Pvalue = -(($protection^255)+1);
		}
	// -- Fin de poner encriptacion.
	//Encabezado
	function Header(){
		//Aqui va el encabezado
		$iConFondo=0;
		if (file_exists('pcfg.php')){include 'pcfg.php';}
		if (isset($rpt[$this->iReporte]['fondo'])!=0){
			$sRuta=$rpt[$this->iReporte]['fondo'];
			if (file_exists($sRuta)){
				$this->Image($sRuta, 0, 0, $this->iAnchoTotal);
				$iConFondo=1;
				}
			}
		if ($iConFondo==0){
			p_TituloEntidad($this, false);
			}
		if ($this->iReporte==105){
			//Ubique aqui los componentes adicionales del encabezado
			$this->Cell($this->iAnchoLibre,5,utf8_encode('Permisos aplicados al perfil '.$this->filaentorno['unad05nombre']), 0, 0, 'C');
			$this->Ln();
			}
		$yPos=$this->GetY();
		if ($yPos<$this->iBordeSuperior){
			$this->SetY($this->iBordeSuperior);
			}
		}
	//Pie de página
	function Footer(){
		switch($this->iFormato){
			case 1:
			$iPagina=$this->PageNo();
			$this->SetY(-8);
			$this->SetFont('Arial','',11);
			if(($iPagina%2)==0){
				//Margen par
				$this->Cell(0,5,''.$this->PageNo().'',0,0,'L');
				//$this->AddPage();
				}else{
				//Margen impar
				$this->Cell(0,5,''.$this->PageNo().'',0,0,'R');
				}
			break;
			default:
			$bConPagina=$this->bConPagina;
			if ($this->iReporte==-1){$bConPagina=false;}
			$this->SetRightMargin(5);
			if ($bConPagina){
				$this->SetY(-8);
				$this->SetFont('Arial','I',8);
				$this->Cell(0,5,utf8_decode('Página ').$this->PageNo().' de {nb}',0,0,'R');
				}
			$this->SetY(-4);
			$this->SetFont('Arial','',7);
			$this->Cell(0,3,$this->iFirmaReporte,0,0,'R');
			$this->SetRightMargin(15);
			}
		}
	function VerificarEspacioMinimo($iMilimetros){
		$y=$this->GetY();
		$iMax=$this->iAltoTotal-$this->iBordeInferior-$iMilimetros;
		if ($y>$iMax){
			$this->AddPage();
			}
		}
	//Funciones del reporte.
	function PintarPermisos($idMod, $sMod, $sPermisos){
		if ($sPermisos!=''){
			$this->Cell(5, 4, '');
			$this->Cell(15, 4, $idMod);
			$this->Cell(90, 4, utf8_decode($sMod));
			$this->Ln();
			p_FuentePequena($this);
			$this->Cell(20, 4, '');
			$this->MultiCell($this->iAnchoLibre-20, 4, $sPermisos);
			p_FuenteMedia($this);
			}
		}
	function ArmarReporte105($PARAMS, $objdb){
		$this->SetTextColor(0,0,0);
		$this->SetFillColor(0,0,0);
		$this->SetDrawColor(0,0,0);
		p_FuenteNormal($this);
		$sql='SELECT T1.unad02idsistema, TB.unad06idmodulo, TB.unad06idpermiso, T1.unad02nombre, T23.unad01nombre, T2.unad03nombre 
FROM unad06perfilmodpermiso AS TB, unad02modulos AS T1 LEFT JOIN unad01sistema AS T23 ON (T1.unad02idsistema=T23.unad01id), unad03permisos AS T2 
WHERE TB.unad06idperfil='.$PARAMS['id105'].' AND TB.unad06vigente="S" AND TB.unad06idmodulo=T1.unad02id AND TB.unad06idpermiso=T2.unad03id 
ORDER BY T1.unad02idsistema, T1.unad02nombre, TB.unad06idpermiso';
		$tabla1=$objdb->ejecutasql($sql);
		$idSistema=-99;
		$idMod=-999;
		$sMod='';
		$sPermisos='';
		//$this->MultiCell($this->iAnchoLibre, 5, $sql);
		while($fila1=$objdb->sf($tabla1)){
			if ($idMod!=$fila1['unad06idmodulo']){
				$this->PintarPermisos($idMod, $sMod, $sPermisos);
				$sMod=$fila1['unad02nombre'];
				$idMod=$fila1['unad06idmodulo'];
				$sPermisos='';
				}
			if ($sPermisos!=''){$sPermisos=$sPermisos.', ';}
			$sPermisos=$sPermisos.$fila1['unad06idpermiso'].' - '.$fila1['unad03nombre'];
			if ($idSistema!=$fila1['unad02idsistema']){
				if ($idSistema!=-99){
					p_Separador($this);
					}
				switch($fila1['unad02idsistema']){
					case 0:
					case 1:
					$sSistema='Archivos comunes';
					break;
					case 99:
					$sSistema='Modulos controlados';
					break;
					default:
					$sSistema=$fila1['unad01nombre'];
					}
				p_FuenteNormal($this);
				$this->Cell($this->iAnchoLibre-50, 5, 'Sistema: '.$sSistema);
				$this->Ln();
				p_FuenteMedia($this);
				$idSistema=$fila1['unad02idsistema'];
				}
			}
		//Pintar el ultimo
		$this->PintarPermisos($idMod, $sMod, $sPermisos);
		
		p_Separador($this);
		p_Separador($this);
		p_FuenteNormal($this);
		$this->Cell($this->iAnchoLibre, 5, 'Usuarios aplicados al perfil', 0, 0, 'C');
		$this->Ln();
		p_FuentePequena($this);
		$sql='SELECT TB.unad07idtercero, T1.unad11tipodoc, T1.unad11doc, T1.unad11razonsocial FROM unad07usuarios AS TB, unad11terceros AS T1 WHERE TB.unad07idperfil='.$PARAMS['id105'].' AND TB.unad07vigente="S" AND TB.unad07idtercero=T1.unad11id';
		$tabla1=$objdb->ejecutasql($sql);
		while($fila1=$objdb->sf($tabla1)){
			$this->Cell(40, 4, $fila1['unad11tipodoc'].' '.$fila1['unad11doc']);
			$this->Cell($this->iAnchoLibre-40, 4, utf8_decode($fila1['unad11razonsocial']));
			$this->Ln();
			}
		// Fin de ArmarReporte105
		}
	// Fin de clsPDF
	}
function pdfReporte($iReporte, $PARAMS, $iFormato, $objdb){
	$objpdf=NULL;
	$sError='';
	if ($objdb==NULL){
		$sError='No se ha definido un origen de datos';
		}
	if ($sError==''){
		//Cargar los parametros previos.
		}
	// -- Validaciones de los parametros del reporte
	if ($sError==''){
		$filaentorno=NULL;
		$sql='';
		switch ($iReporte){
			case 105:
			//if (isset($PARAMS['idtercero'])==0){$PARAMS['idtercero']='';}
			//if ((int)$PARAMS['idtercero']==0){$sError='No se ha ingresado un tercero';}
			$sql='SELECT unad05nombre, unad05id FROM unad05perfiles WHERE unad05id='.$PARAMS['id105'];
			break;
			}
		if ($sql!=''){
			$tablaent=$objdb->ejecutasql($sql);
			if ($tablaent==false){
				$sError='No fue posible cargar los datos del reporte [Entorno]<!-- '.$sql.' -->';
				}else{
				$filaentorno=$objdb->sf($tablaent);
				}
			}
		}
	// -- Empezamos la generacion del reporte
	if ($sError==''){
		switch ($iReporte){
			case 999: //Ejemplo de apaisado
			$objpdf=new clsPDF('L','mm','Letter');
			$objpdf->SetTopMargin(10);
			$objpdf->SetLeftMargin(15);
			$objpdf->SetRightMargin(15);
			$objpdf->iAnchoLibre=267;
			$objpdf->iAnchoTotal=297;
			$objpdf->iAltoTotal=216;
			break;
			default:
			$objpdf=new clsPDF('P','mm','Letter');
			$iSup=25;
			$bConCFG=false;
			if ($iReporte==1){$bConCFG=true;}
			//if ($iReporte==105){$bConCFG=true;}
			if ($bConCFG){
				if (file_exists('pcfg.php')){
					include 'pcfg.php';
					}
				if (isset($rpt[$iReporte]['bordesup'])==0){$rpt[$iReporte]['bordesup']=25;}
				if (isset($rpt[$iReporte]['bordeinf'])==0){$rpt[$iReporte]['bordeinf']=$objpdf->iBordeInferior;}
				$iSup=$rpt[$iReporte]['bordesup'];
				$objpdf->iBordeInferior=$rpt[$iReporte]['bordeinf'];
				if ($iSup>0){
					$objpdf->iBordeSuperior=$iSup;
					}
				}
			$sClave='';
			if (isset($PARAMS['clave'])!=0){$sClave=trim($PARAMS['clave']);}
			if ($sClave!=''){
				$objpdf->SetProtection(array(), $sClave);
				}
			$objpdf->SetTopMargin(10);
			$objpdf->SetLeftMargin(15);
			$objpdf->SetRightMargin(15);
			$objpdf->SetAutoPageBreak(true, $objpdf->iBordeInferior);
			}
		//Iniciar la generacion del reporte
		switch ($iReporte){
			case 105:
			$objpdf->sRefRpt=$PARAMS['id105'];
			break;
			}
		$objpdf->AliasNbPages();
		$objpdf->iFormato=$iFormato;
		$objpdf->iReporte=$iReporte;
		$objpdf->filaent=NULL;
		$objpdf->filaentorno=$filaentorno;
		$objpdf->AddPage();
		switch ($iReporte){
			case 105:
			$objpdf->ArmarReporte105($PARAMS, $objdb);
			//$objpdf->AddPage();
			//p_PaginaIndice($objpdf);
			break;
			}
		$sError=$objpdf->sError;
		}
	return array($objpdf, $sError);
	}
//Empezar revisando que haya una sesion.
if ($_SESSION['unad_id_tercero']==0){
	die();
	}
$sError='';
$iReporte=0;
$bEntra=false;
if (isset($_REQUEST['r'])!=0){$iReporte=numeros_validar($_REQUEST['r']);}
if ((int)$iReporte!=0){$bEntra=true;}
if ($bEntra){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$iFormato=0;
	if (isset($_REQUEST['f'])!=0){if ($_REQUEST['f']==1){$iFormato=1;}}
	//if (isset($_REQUEST['variable'])==0){$_REQUEST['variable']=0;}
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$bEntra=false;
	$sTituloRpt='Reporte';
	if ($iReporte==105){$sTituloRpt='Perfiles';}
	switch ($iReporte){
		case 105:
		list($pdf, $sError)=pdfReporte($iReporte, $_REQUEST, $iFormato, $objdb);
		$bEntra=true;
		}
	if ($sError==''){if (!$bEntra){$sError='No se ha encontrado el reporte solicitado {'.$iReporte.'}';}}
	if ($sError==''){$sError=$pdf->sError;}
	if ($sError==''){
		switch ($iReporte){
			case 105:
			$sTituloRpt=$sTituloRpt.'_'.$pdf->sRefRpt;
			}
		$pdf->Output($sTituloRpt.'.pdf','D');
		}else{
		echo $sError;
		}
	}
?>