<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 2.23.5 Tuesday, August 27, 2019
*/
//error_reporting(E_ALL);
//ini_set("display_errors", 1);
if (!file_exists('./app.php')){
	echo '<b>Error N 1 de instalaci&oacute;n</b><br>No se ha establecido un archivo de configuraci&oacute;n, por favor comuniquese con el administrador del sistema.';
	die();
	}
mb_internal_encoding('UTF-8');
require './app.php';
require $APP->rutacomun.'unad_todas.php';
require $APP->rutacomun.'libs/clsdbadmin.php';
require $APP->rutacomun.'unad_librerias.php';
require $APP->rutacomun.'fpdf/fpdf.php';
require $APP->rutacomun.'libp.php';
class clsPDF extends FPDF{
	var $aDebug=array();
	var $bCodificar=false;
	var $bConPagina=true;
	var $bConFechaImprime=true;
	var $bDebug=false;
	var $iAnchoFondo=0;
	var $iAnchoLibre=186;
	var $iAnchoTotal=216;
	var $iAltoTotal=279;
	var $iBordeEncabezado=10;
	var $iBordeSuperior=25;
	var $iBordeInferior=10;
	var $iBordeIzquierda=15;
	var $iBordeDerecha=15;
	var $iDebug=0;
	var $iFormato=0;
	var $iFuenteTamGrande=14;
	var $iFuenteTamNormal=12;
	var $iFuenteTamMedia=11;
	var $iFuenteTamPequena=10;
	var $iFuenteTamMini=8;
	var $iReporte=0;
	var $iSector=0;
	var $filaent=NULL;
	var $filaentorno=NULL;
	var $sError='';
	var $sFirmaReporte='http://www.unad.edu.co';
	var $sFondo='';
	var $sFuenteFamilia='Arial';
	var $sFuenteFamilia2='Courier';
	var $sRefRpt='';
	var $sNumCopia='';
	//var $smes=array('','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
	var $xPrevia=0;
	var $yPrevia=0;
	//se Crean porque no permite en modo seguro tenerlas en forma implicita
	var $HREF='';
	var $B='';
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
		if ($this->iSector==98){
			p_FuenteGrandeV2($this,'B');
			$this->Cell($this->iAnchoLibre,5,utf8_decode('Información de depuración'), 0, 0, 'C');
			$this->Ln();
			return;
			}
		$iConFondo=0;
		if ($this->sFondo!=''){
			if (file_exists($this->sFondo)){
				$this->Image($this->sFondo, 0, 0, $this->iAnchoFondo);
				$iConFondo=1;
				}
			}
		$yPos=$this->GetY();
		if ($yPos<$this->iBordeEncabezado){
			$this->SetY($this->iBordeEncabezado);
			}
		if ($iConFondo==0){
			p_TituloEntidad($this, false);
			}else{
			p_FuenteGrandeV2($this,'B');
			}
		if ($this->iReporte==1902){
			//Ubique aqui los componentes adicionales del encabezado
			$this->Cell($this->iAnchoLibre,5,'Eventos '.$this->sRefRpt, 0, 0, 'C');
			$this->Ln();
			}
		$yPos=$this->GetY();
		if ($yPos<$this->iBordeSuperior){
			$this->SetY($this->iBordeSuperior);
			}
		}
	//Pie de página
	function Footer(){
		$bModMargen=false;
		if ($this->bConPagina){$bModMargen=true;}
		if ($this->bConFechaImprime){$bModMargen=true;}
		if (trim($this->sFirmaReporte)!=''){$bModMargen=true;}
		if ($bModMargen){
			$this->SetRightMargin(5);
			}
		if ($this->bConPagina){
			$this->SetY(-8);
			$this->SetFont('Arial','I',8);
			$sEtiqueta='Página ';
			$this->Cell(0,5, utf8_decode($sEtiqueta).$this->PageNo().' de {nb}',0,0,'R');
			}
		$sEtiqueta='';
		if ($this->sNumCopia!=''){
			$sEtiqueta='Copia '.$this->sNumCopia.' - ';
			}
		if ($this->bConFechaImprime){
			$sEtFImp='Fecha de impresión ';
			$sEtiqueta=$sEtiqueta.utf8_decode($sEtFImp).formato_fechalarga(fecha_hoy(), true).' '.html_TablaHoraMin(fecha_hora(), fecha_minuto());
			}
		if ($sEtiqueta!=''){
			$this->SetY(-8);
			$this->SetFont('Arial','I',8);
			$this->Cell(0, 5, $sEtiqueta);
			}
		if (trim($this->sFirmaReporte)!=''){
			$this->SetY(-4);
			$this->SetFont('Arial','',7);
			$this->Cell(0,3,$this->sFirmaReporte,0,0,'R');
			}
		if ($bModMargen){
			$this->SetRightMargin($this->iBordeDerecha);
			}
		}
	//Funciones del reporte.
	function ArmarReporte1902($PARAMS, $objDB){
		$this->SetTextColor(0,0,0);
		$this->SetFillColor(0,0,0);
		$this->SetDrawColor(0,0,0);
		p_FuenteNormalV2($this);
		//$iPuntoX=$this->GetX();
		//$sTitulo='Titulo 1';
		//$sNumera=p_AddIndice($sTitulo, $this->PageNo(), 1, $this);
		$sSQL='SELECT * FROM even02evento WHERE even02id='.$PARAMS['id1902'].'';
		if ($this->bDebug){p_AddDebug('Consulta para el reporte '.$sSQL, $this);}
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$this->Cell($this->iAnchoLibre, 5, 'Consec: '.$fila['even02consec']);
			$this->Ln();
			$seven02tipo=$fila['even02tipo'];
			$sSQL='SELECT even01nombre FROM even01tipoevento WHERE even01id='.$fila['even02tipo'];
			$tablat=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablat)>0){
				$filat=$objDB->sf($tablat);
				$seven02tipo=$filat['even01nombre'];
				if ($this->bCodificar){$seven02tipo=utf8_encode($filat['even01nombre']);}
				}
			$this->Cell($this->iAnchoLibre, 5, 'Tipo: '.utf8_decode($seven02tipo));
			$this->Ln();
			$seven02categoria=$fila['even02categoria'];
			$sSQL='SELECT even41titulo FROM even41categoria WHERE even41id='.$fila['even02categoria'];
			$tablat=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablat)>0){
				$filat=$objDB->sf($tablat);
				$seven02categoria=$filat['even41titulo'];
				if ($this->bCodificar){$seven02categoria=utf8_encode($filat['even41titulo']);}
				}
			$this->Cell($this->iAnchoLibre, 5, 'Categoria: '.utf8_decode($seven02categoria));
			$this->Ln();
			$seven02estado=$fila['even02estado'];
			$sSQL='SELECT even14nombre FROM even14estadoevento WHERE even14id='.$fila['even02estado'];
			$tablat=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablat)>0){
				$filat=$objDB->sf($tablat);
				$seven02estado=$filat['even14nombre'];
				if ($this->bCodificar){$seven02estado=utf8_encode($filat['even14nombre']);}
				}
			$this->Cell($this->iAnchoLibre, 5, 'Estado: '.utf8_decode($seven02estado));
			$this->Ln();
			$seven02publicado=$fila['even02publicado'];
			$sSQL='SELECT  FROM  WHERE ='.$fila['even02publicado'];
			$tablat=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablat)>0){
				$filat=$objDB->sf($tablat);
				$seven02publicado=$filat[''];
				if ($this->bCodificar){$seven02publicado=utf8_encode($filat['']);}
				}
			$this->Cell($this->iAnchoLibre, 5, 'Publicado: '.utf8_decode($seven02publicado));
			$this->Ln();
			$this->Cell($this->iAnchoLibre, 5, 'Nombre: '.$fila['even02nombre']);
			$this->Ln();
			$seven02idzona=$fila['even02idzona'];
			$sSQL='SELECT unad23nombre FROM unad23zona WHERE unad23id='.$fila['even02idzona'];
			$tablat=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablat)>0){
				$filat=$objDB->sf($tablat);
				$seven02idzona=$filat['unad23nombre'];
				if ($this->bCodificar){$seven02idzona=utf8_encode($filat['unad23nombre']);}
				}
			$this->Cell($this->iAnchoLibre, 5, 'Zona: '.utf8_decode($seven02idzona));
			$this->Ln();
			$seven02idcead=$fila['even02idcead'];
			$sSQL='SELECT unad24nombre FROM unad24sede WHERE unad24id='.$fila['even02idcead'];
			$tablat=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablat)>0){
				$filat=$objDB->sf($tablat);
				$seven02idcead=$filat['unad24nombre'];
				if ($this->bCodificar){$seven02idcead=utf8_encode($filat['unad24nombre']);}
				}
			$this->Cell($this->iAnchoLibre, 5, 'Cead: '.utf8_decode($seven02idcead));
			$this->Ln();
			$seven02peraca=$fila['even02peraca'];
			$sSQL='SELECT exte02nombre FROM exte02per_aca WHERE exte02id='.$fila['even02peraca'];
			$tablat=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablat)>0){
				$filat=$objDB->sf($tablat);
				$seven02peraca=$filat['exte02nombre'];
				if ($this->bCodificar){$seven02peraca=utf8_encode($filat['exte02nombre']);}
				}
			$this->Cell($this->iAnchoLibre, 5, 'Peraca: '.utf8_decode($seven02peraca));
			$this->Ln();
			$this->Cell($this->iAnchoLibre, 5, 'Lugar: '.$fila['even02lugar']);
			$this->Ln();
			$this->Cell($this->iAnchoLibre, 5, 'Inifecha: '.$fila['even02inifecha']);
			$this->Ln();
			$this->Cell($this->iAnchoLibre, 5, 'Inihora: '.$fila['even02inihora']);
			$this->Ln();
			$this->Cell($this->iAnchoLibre, 5, 'Iniminuto: '.$fila['even02iniminuto']);
			$this->Ln();
			$this->Cell($this->iAnchoLibre, 5, 'Finfecha: '.$fila['even02finfecha']);
			$this->Ln();
			$this->Cell($this->iAnchoLibre, 5, 'Finhora: '.$fila['even02finhora']);
			$this->Ln();
			$this->Cell($this->iAnchoLibre, 5, 'Finminuto: '.$fila['even02finminuto']);
			$this->Ln();
			$et_even02idorganizador=p_DatosTercero($fila['even02idorganizador'], $objDB);
			if ($this->bCodificar){$et_even02idorganizador=utf8_encode($et_even02idorganizador);}
			$this->Cell($this->iAnchoLibre, 5, 'Organizador: '.utf8_decode($et_even02idorganizador));
			$this->Ln();
			$this->Cell($this->iAnchoLibre, 5, 'Contacto: '.$fila['even02contacto']);
			$this->Ln();
			$this->Cell($this->iAnchoLibre, 5, 'Insfechaini: '.$fila['even02insfechaini']);
			$this->Ln();
			$this->Cell($this->iAnchoLibre, 5, 'Insfechafin: '.$fila['even02insfechafin']);
			$this->Ln();
			$this->Cell($this->iAnchoLibre, 5, 'Certificado: '.$fila['even02idcertificado']);
			$this->Ln();
			$this->Cell($this->iAnchoLibre, 5, 'Rubrica: '.$fila['even02idrubrica']);
			$this->Ln();
			$this->Cell($this->iAnchoLibre, 5, 'Detalle: '.$fila['even02detalle']);
			$this->Ln();
			$sTitulo='Cursos';
			//$sNumera=p_AddIndice($sTitulo, $this->PageNo(), 1, $this);
			p_TituloBloque($sTitulo, $this);
			p_FuenteNormalV2($this);
			$sSQL='SELECT * FROM even03eventocurso WHERE even03idevento='.$fila['even02id'].'';
			if ($this->bDebug){p_AddDebug('Consulta hija '.$sSQL, $this);}
			$tabla1=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla1)>0){
				//Encabezados.
				$c1=20;
				$c2=20;
				$c3=20;
				$c4=20;
				$this->Cell($c2, 5, 'Curso');
				$this->Cell($c4, 5, 'Vigente');
				$this->Ln();
				}
			while($fila1=$objDB->sf($tabla1)){
				$this->Cell($c1, 5, $fila1['even03idcurso']);
				$this->Cell($c2, 5, $fila1['even03vigente']);
				$this->Ln();
				}
			p_Separador($this);
			$sTitulo='Participantes';
			//$sNumera=p_AddIndice($sTitulo, $this->PageNo(), 1, $this);
			p_TituloBloque($sTitulo, $this);
			p_FuenteNormalV2($this);
			$sSQL='SELECT * FROM even04eventoparticipante WHERE even04idevento='.$fila['even02id'].'';
			if ($this->bDebug){p_AddDebug('Consulta hija '.$sSQL, $this);}
			$tabla2=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla2)>0){
				//Encabezados.
				$c1=20;
				$c2=20;
				$c3=20;
				$c4=20;
				$c5=20;
				$c6=20;
				$c7=20;
				$c8=20;
				$this->Cell($c2, 5, 'Participante');
				$this->Cell($c4, 5, 'Institucion');
				$this->Cell($c5, 5, 'Cargo');
				$this->Cell($c6, 5, 'Correo');
				$this->Cell($c7, 5, 'Telefono');
				$this->Cell($c8, 5, 'Estadoasistencia');
				$this->Ln();
				}
			while($fila2=$objDB->sf($tabla2)){
				$et_even04idparticipante=p_DatosTercero($fila2['even04idparticipante'], $objDB);
				if ($this->bCodificar){$et_even04idparticipante=utf8_encode($et_even04idparticipante);}
				$this->Cell($c1, 5, utf8_decode($et_even04idparticipante));
				$this->Cell($c2, 5, $fila2['even04institucion']);
				$this->Cell($c3, 5, $fila2['even04cargo']);
				$this->Cell($c4, 5, $fila2['even04correo']);
				$this->Cell($c5, 5, $fila2['even04telefono']);
				$seven04estadoasistencia=$fila2['even04estadoasistencia'];
				$sSQL='SELECT even13nombre FROM even13estadoasistencia WHERE even13id='.$fila2['even04estadoasistencia'];
				$tablat=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablat)>0){
					$filat=$objDB->sf($tablat);
					$seven04estadoasistencia=$filat['even13nombre'];
					if ($this->bCodificar){$seven04estadoasistencia=utf8_encode($filat['even13nombre']);}
					}
				$this->Cell($c6, 5, utf8_decode($seven04estadoasistencia));
				$this->Ln();
				}
			p_Separador($this);
			$sTitulo='Noticias';
			//$sNumera=p_AddIndice($sTitulo, $this->PageNo(), 1, $this);
			p_TituloBloque($sTitulo, $this);
			p_FuenteNormalV2($this);
			$sSQL='SELECT * FROM eve05eventonoticia WHERE even05idevento='.$fila['even02id'].'';
			if ($this->bDebug){p_AddDebug('Consulta hija '.$sSQL, $this);}
			$tabla3=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla3)>0){
				//Encabezados.
				$c1=20;
				$c2=20;
				$c3=20;
				$c4=20;
				$c5=20;
				$c6=20;
				$c7=20;
				$this->Cell($c2, 5, 'Consec');
				$this->Cell($c4, 5, 'Fecha');
				$this->Cell($c5, 5, 'Publicar');
				$this->Cell($c6, 5, 'Tercero');
				$this->Cell($c7, 5, 'Noticia');
				$this->Ln();
				}
			while($fila3=$objDB->sf($tabla3)){
				$this->Cell($c1, 5, $fila3['even05consec']);
				$this->Cell($c2, 5, $fila3['even05fecha']);
				$seven05publicar=$fila3['even05publicar'];
				$sSQL='SELECT  FROM  WHERE ='.$fila3['even05publicar'];
				$tablat=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablat)>0){
					$filat=$objDB->sf($tablat);
					$seven05publicar=$filat[''];
					if ($this->bCodificar){$seven05publicar=utf8_encode($filat['']);}
					}
				$this->Cell($c3, 5, utf8_decode($seven05publicar));
				$et_even05idtercero=p_DatosTercero($fila3['even05idtercero'], $objDB);
				if ($this->bCodificar){$et_even05idtercero=utf8_encode($et_even05idtercero);}
				$this->Cell($c4, 5, utf8_decode($et_even05idtercero));
				$this->Cell($c5, 5, $fila3['even05noticia']);
				$this->Ln();
				}
			p_Separador($this);
			}
		// Fin de ArmarReporte1902
		}
	// Fin de clsPDF
	}
function pdfReporteV2($iReporte, $PARAMS, $iFormato, $sNumCopiaReporte, $bCodificarUTF8, $objDB, $bDebug=false){
	$objpdf=NULL;
	$sError='';
	if ($objDB==NULL){
		$sError='No se ha definido un origen de datos';
		}
	if ($sError==''){
		//Cargar los parametros previos.
		}
	// -- Validaciones de los parametros del reporte
	if ($sError==''){
		$filaentorno=NULL;
		$sSQL=''; //Aqui debe ubicar la consulta de entorno del reporte.
		//if (isset($PARAMS['idtercero'])==0){$PARAMS['idtercero']='';}
		//if ((int)$PARAMS['idtercero']==0){$sError='No se ha ingresado un tercero';}
		if ($sSQL!=''){
			$tablaent=$objDB->ejecutasql($sSQL);
			if ($tablaent==false){
				$sError='No fue posible cargar los datos del reporte [Entorno]<!-- '.$sSQL.' -->';
				}else{
				$filaentorno=$objDB->sf($tablaent);
				}
			}
		}
	// -- Empezamos la generacion del reporte
	if ($sError==''){
		$iAncho=216;
		$iAlto=279;
		$TP="Letter";
		$sPagina='$TP="Letter";';
		$Posicion='P';
		$bConCFG=true;
		$iSup=25;
		$rpt[$iReporte]['bordeencabezado']=10;
		$rpt[$iReporte]['bordesup']=25;
		$rpt[$iReporte]['bordeinf']=10;
		$rpt[$iReporte]['borde_izquierda']=15;
		$rpt[$iReporte]['borde_derecha']=15;
		$rpt[$iReporte]['fechaimpreso']=1;
		$rpt[$iReporte]['fondo']='';
		$rpt[$iReporte]['pagina_formato']=0;
		$rpt[$iReporte]['pagina_orientacion']=0;
		$id73=0;
		if ($id73!=0){
			$sSQL='SELECT che73idformato FROM che73tipodocumento WHERE che73id='.$id73.'';
			$tabla=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla)>0){
				$fila=$objDB->sf($tabla);
				if ($fila['che73idformato']!=0){
					$bConCFG=false;
					$sSQL='SELECT TB.sys94formatopag, T1.sys57ancho, T1.sys57alto, T1.sys57fpdf, TB.sys94fondo, TB.sys94bordeencab, TB.sys94bordeinf, TB.sys94bordesup, TB.sys94entidadnombre, TB.sys94entidadsubtitulo, TB.sys94fechaimpreso, TB.sys94firmacargo, TB.sys94firmanombre, TB.sys94firmaruta 
FROM sys94formatoreportes AS TB, sys57pagtamano AS T1 
WHERE TB.sys94id='.$fila['che73idformato'].' AND TB.sys94formatopag=T1.sys57id';
					$tabla=$objDB->ejecutasql($sSQL);
					if ($objDB->nf($tabla)>0){
						$fila=$objDB->sf($tabla);
						$rpt[$iReporte]['pagina_formato']=$fila['sys94formatopag'];
						$rpt[$iReporte]['bordeencabezado']=$fila['sys94bordeencab'];
						$rpt[$iReporte]['bordesup']=$fila['sys94bordesup'];
						$rpt[$iReporte]['bordeinf']=$fila['sys94bordeinf'];
						$rpt[$iReporte]['fechaimpreso']=$fila['sys94fechaimpreso'];
						$rpt[$iReporte]['fondo']=$fila['sys94fondo'];
						//Termina de cargar el formato
						}
					}
				}
			}
		if ($bConCFG){
			$sRutaConfig='pcfg.php';
			if (file_exists($sRutaConfig)){
				include $sRutaConfig;
				}
			}
		$iSup=$rpt[$iReporte]['bordesup'];
		$idFormatoPag=$rpt[$iReporte]['pagina_formato'];
		switch($idFormatoPag){
			case 0:
			case 1:
			break;
			case 2:
			$TP='Legal';
			$iAlto=356;
			break;
			default:
			$sSQL='SELECT T1.sys57ancho, T1.sys57alto, T1.sys57fpdf 
FROM sys57pagtamano AS T1 
WHERE T1.sys57id='.$idFormatoPag.'';
			$tabla=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla)>0){
				$fila=$objDB->sf($tabla);
				$sPagina='$TP='.$fila['sys57fpdf'].';';
				$iAncho=$fila['sys57ancho'];
				$iAlto=$fila['sys57alto'];
				eval($sPagina);
				}
			break;
			}
		if ($rpt[$iReporte]['pagina_orientacion']==1){
			$Posicion='L';
			$iTemp=$iAncho;
			$iAncho=$iAlto;
			$iAlto=$iTemp;
			}
		$objpdf=new clsPDF($Posicion, 'mm', $TP);
		$objpdf->bDebug=$bDebug;
		$objpdf->iBordeEncabezado=$rpt[$iReporte]['bordeencabezado'];
		$objpdf->iBordeInferior=$rpt[$iReporte]['bordeinf'];
		$objpdf->iBordeIzquierda=$rpt[$iReporte]['borde_izquierda'];
		$objpdf->iBordeDerecha=$rpt[$iReporte]['borde_derecha'];
		if ($rpt[$iReporte]['fechaimpreso']==0){$objpdf->bConFechaImprime=false;}
		if ($iSup>0){
			$objpdf->iBordeSuperior=$iSup;
			}
		$objpdf->iAnchoLibre=$iAncho-($objpdf->iBordeIzquierda+$objpdf->iBordeDerecha);
		$objpdf->iAnchoTotal=$iAncho;
		$objpdf->iAltoTotal=$iAlto;
		p_AddFondo($rpt[$iReporte]['fondo'], $objpdf);
		$objpdf->SetTopMargin($objpdf->iBordeSuperior);
		$objpdf->SetLeftMargin($objpdf->iBordeIzquierda);
		$objpdf->SetRightMargin($objpdf->iBordeDerecha);
		$objpdf->SetAutoPageBreak(true, $objpdf->iBordeInferior);
		$sClave='';
		if (isset($PARAMS['clave'])!=0){$sClave=trim($PARAMS['clave']);}
		if ($sClave!=''){
			$objpdf->SetProtection(array(), $sClave);
			}
		$objpdf->sNumCopia=$sNumCopiaReporte;
		//Iniciar la generacion del reporte
		if (trim($filaent['sys24firmareportes'])!=''){
			$objpdf->sFirmaReporte=trim($filaent['sys24firmareportes']);
			}
		//$objpdf->sRefRpt=$PARAMS['id1902'];
		$objpdf->AliasNbPages();
		$objpdf->bCodificar=$bCodificarUTF8;
		$objpdf->iFormato=$iFormato;
		$objpdf->iReporte=$iReporte;
		$objpdf->filaent=NULL;
		$objpdf->filaentorno=$filaentorno;
		$objpdf->AddPage();
		$objpdf->ArmarReporte1902($PARAMS, $objDB);
		//$objpdf->AddPage();
		//p_PaginaIndice($objpdf);
		if ($bDebug){
			$objpdf->iSector=98;
			$objpdf->AddPage();
			p_PaginaDebug($objpdf);
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
$iFormato94=0;
$bEntra=false;
$bDebug=false;
if (isset($_REQUEST['r'])!=0){$iReporte=numeros_validar($_REQUEST['r']);}
if (isset($_REQUEST['rdebug'])!=0){if ($_REQUEST['rdebug']==1){$bDebug=true;}}
if (isset($_REQUEST['iformato94'])!=0){$iFormato94=$_REQUEST['iformato94'];}
if ((int)$iReporte!=0){$bEntra=true;}
if ($bEntra){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$iFormato=0;
	if (isset($_REQUEST['f'])!=0){if ($_REQUEST['f']==1){$iFormato=1;}}
	//if (isset($_REQUEST['variable'])==0){$_REQUEST['variable']=0;}
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$bCodificarUTF8=false;
	if ($APP->utf8==1){$bCodificarUTF8=true;}
	$bEntra=false;
	$sTituloRpt='Reporte';
	if ($iReporte==1902){$sTituloRpt='Eventos';}
	$bReporteControlado=false;
	$sNumCopiaReporte='';
	// Definir si un reporte es controlado
	//$idRef=$PARAMS['id1902'];
	if ($bReporteControlado){
		require $APP->rutacomun.'lib1093.php';
		$bCopiaBloqueada=true;
		$bRequierePermiso=true;
		// Definir cuando una copia NO esta bloqueada.
		$iModPermiso=1902;
		if (f1093_NumCopias($iReporte, $idRef, $objDB)==0){
			//$iPermisoCopia=5;
			$bCopiaBloqueada=false;
			$bRequierePermiso=false;
			}else{
			$iPermisoCopia=9;
			}
		if ($bRequierePermiso){
			if (seg_revisa_permiso($iModPermiso, $iPermisoCopia, $objDB)){$bCopiaBloqueada=false;}
			}
		if ($bCopiaBloqueada){
			echo 'No tiene permiso para imprimir copia de este reporte';
			die();
			}
		// Hacer el registro de la copia.
		$sNumCopiaReporte=f1093_RegistrarCopia($iReporte, $idRef, $objDB);
		}
	list($pdf, $sError)=pdfReporteV2($iReporte, $_REQUEST, $iFormato, $sNumCopiaReporte, $bCodificarUTF8, $objDB, $bDebug);
	//if ($sError==''){if (!$bEntra){$sError='No se ha encontrado el reporte solicitado {'.$iReporte.'}';}}
	if ($sError==''){$sError=$pdf->sError;}
	if ($sError==''){
		$sTituloRpt=$sTituloRpt.'_'.$pdf->sRefRpt;
		$pdf->Output($sTituloRpt.'.pdf','D');
		}else{
		echo $sError;
		}
	}
?>