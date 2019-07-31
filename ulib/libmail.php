<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2016 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.12.0 martes, 19 de enero de 2016
--- Modelo Versión 2.22.4 miércoles, 31 de octubre de 2018
--- la ideas es centralizar todas las funciones de envio de mail en una libreria...
*/
class clsMail_Unad{
var $idSMTP=-1;
var $sOrigen='';
var $sNombreOrigen='';
var $iAdjuntos=0;
var $iDestinatarios=0;
var $correos=array();
var $nombres=array();
var $tipoenvio=array();
var $adjuntos=array();
var $sCuerpo='';
var $sAsunto='';
var $bHTML=true;
var $objMAIL=NULL;

var $objdb;
var $fila17;
function addAdjunto($sRuta){
	$this->iAdjuntos++;
	$this->adjuntos[$this->iAdjuntos]=$sRuta;
	}
function addCorreo($sDirCorreo, $sNombreDestino='', $sTipoEnvio=''){
	$bPasa=false;
	$sDirCorreo=trim($sDirCorreo);
	if (correo_VerificarDireccion($sDirCorreo)){
		$this->iDestinatarios++;
		$this->correos[$this->iDestinatarios]=$sDirCorreo;
		$this->nombres[$this->iDestinatarios]=$sNombreDestino;
		switch($sTipoEnvio){
			case 'P':
			case 'C':
			case 'O':
			case 'p':
			case 'c':
			case 'o':
			$sTipoEnvio=strtoupper($sTipoEnvio);
			break;
			default:
			$sTipoEnvio='';
			break;
			}
		if ($sTipoEnvio==''){
			if ($this->iDestinatarios==1){
				$this->tipoenvio[$this->iDestinatarios]='P';
				}else{
				$this->tipoenvio[$this->iDestinatarios]='C';
				}
			}else{
			$this->tipoenvio[$this->iDestinatarios]=$sTipoEnvio;
			}
		$bPasa=true;
		}
	return $bPasa;
	}
//Es decir que se lo envia el sistema a la entidad..
function autoMensaje(){
	$sError='';
	//Cuando nos mandamos una notificacion...
	$this->addCorreo('soporte.campus@unad.edu.co');
	return $sError;
	}
function Enviar($bDebug=false){
	$sPref='unad69';
	$sError='';
	if ($this->iDestinatarios==0){
		$sError='No se han agregado destinatarios.';
		}
	//Ubicar el SMTP de origen.
	if ($sError==''){
		if ($this->idSMTP==-1){
			$this->TraerSMTP(1);
			if ($this->idSMTP==-1){
				$sError='No se ha podido determinar un correo para notificaciones.';
				}
			}
		}
	if ($sError==''){
		$fila17=$this->fila17;
		//Enviar el correo.
		if (!class_exists('PHPMailer')){
			require './app.php';
			require $APP->rutacomun.'PHPmailer/class.phpmailer.php';
			require $APP->rutacomun.'PHPmailer/class.smtp.php';
			}
		$mail=new PHPMailer();
		$mail->IsSMTP();
		$mail->Username=$fila17[$sPref.'usuariomail'];
		$mail->From=$fila17[$sPref.'usuariomail'];
		$mail->FromName=$fila17[$sPref.'titulo'];
		if ($fila17[$sPref.'autenticacion']!=''){
			$mail->SMTPAuth=true;
			$mail->SMTPSecure=strtolower($fila17[$sPref.'autenticacion']);
			$mail->Password=$fila17[$sPref.'pwdmail'];
			}else{
			$mail->SMTPAuth=false;
			if ($this->sOrigen==''){
				}else{
				$mail->Username=$this->sOrigen;
				$mail->From=$this->sOrigen;
				$mail->FromName=$this->sNombreOrigen;
				}
			}
		$mail->Port=$fila17[$sPref.'puertomail'];
		$mail->Host=$fila17[$sPref.'servidorsmtp'];
		$mail->Subject=utf8_decode($this->sAsunto);
		for ($k=1;$k<=$this->iDestinatarios; $k++){
			switch($this->tipoenvio[$k]){
				case 'C':
				$mail->AddCC($this->correos[$k], $this->nombres[$k]);
				break;
				case 'O':
				$mail->AddBCC($this->correos[$k], $this->nombres[$k]);
				break;
				default:
				$mail->AddAddress($this->correos[$k], $this->nombres[$k]);
				break;
				}
			}
		$mail->WordWrap = 50;
		if ($this->bHTML){
			$mail->IsHTML(true);
			$mail->Body='<html><body>'.$this->sCuerpo.'</body></html>';
			}else{
			$mail->Body=$this->sCuerpo;
			}
		$mail->CharSet='UTF-8';
		for ($k=1;$k<=$this->iAdjuntos; $k++){
			$mail->addAttachment($this->adjuntos[$k]);
			}
		try{
			if ($mail->Send()){
				}else{
				$sError='Error enviando el mensaje de correo, por favor comuniquese con el administrador del sistema.';
				if ($bDebug){$sError=$sError.'<br>Error: '.$mail->ErrorInfo;}
				}
			} catch (Exception $e) {
			$sError='Error al intentar enviar el mensaje de correo, por favor comuniquese con el administrador del sistema.';
			}
		unset($mail);
		//Termina de enviar el correo.
		}
	return $sError;
	}
function NuevoMensaje(){
	//Alista la libreria para que se vuelva a enviar el mensaje pero a otro destinatario.
	$this->iDestinatarios=0;
	$this->correos=array();
	$this->nombres=array();
	if (is_null($this->objMAIL)){
		}else{
		$this->objMAIL->clearAllRecipients();
		$this->objMAIL->clearAttachments();
		}
	}
function RecurrenteIniciar($bDebug=false){
	$sPref='unad69';
	$sError='';
	//Ubicar el SMTP de origen.
	if ($sError==''){
		if ($this->idSMTP==-1){
			$this->TraerSMTP(1);
			if ($this->idSMTP==-1){
				$sError='No se ha podido determinar un correo para notificaciones.';
				}
			}
		}
	if ($sError==''){
		$fila17=$this->fila17;
		//Enviar el correo.
		if (!class_exists('PHPMailer')){
			require './app.php';
			require $APP->rutacomun.'PHPmailer/class.phpmailer.php';
			require $APP->rutacomun.'PHPmailer/class.smtp.php';
			}
		$mail=new PHPMailer();
		$mail->IsSMTP();
		$mail->Username=$fila17[$sPref.'usuariomail'];
		$mail->From=$fila17[$sPref.'usuariomail'];
		$mail->FromName=$fila17[$sPref.'titulo'];
		if ($fila17[$sPref.'autenticacion']!=''){
			$mail->SMTPAuth=true;
			$mail->SMTPSecure=strtolower($fila17[$sPref.'autenticacion']);
			$mail->Password=$fila17[$sPref.'pwdmail'];
			}else{
			$mail->SMTPAuth=false;
			if ($this->sOrigen==''){
				}else{
				$mail->Username=$this->sOrigen;
				$mail->From=$this->sOrigen;
				$mail->FromName=$this->sNombreOrigen;
				}
			}
		$mail->Port=$fila17[$sPref.'puertomail'];
		$mail->Host=$fila17[$sPref.'servidorsmtp'];
		$this->objMAIL=$mail;
		}
	return $sError;
	}
function RecurrenteEnviar($bDebug=false){
	$sError='';
	if ($this->iDestinatarios==0){
		$sError='No se han agregado destinatarios.';
		}
	if ($sError==''){
		$mail=$this->objMAIL;
		$mail->Subject=utf8_decode($this->sAsunto);
		for ($k=1;$k<=$this->iDestinatarios; $k++){
			switch($this->tipoenvio[$k]){
				case 'C':
				$mail->AddCC($this->correos[$k], $this->nombres[$k]);
				break;
				case 'O':
				$mail->AddBCC($this->correos[$k], $this->nombres[$k]);
				break;
				default:
				$mail->AddAddress($this->correos[$k], $this->nombres[$k]);
				break;
				}
			}
		$mail->WordWrap = 50;
		if ($this->bHTML){
			$mail->IsHTML(true);
			$mail->Body='<html><body>'.$this->sCuerpo.'</body></html>';
			}else{
			$mail->Body=$this->sCuerpo;
			}
		$mail->CharSet='UTF-8';
		try{
			if ($mail->Send()){
				}else{
				$sError='Error enviando el mensaje de correo, por favor comuniquese con el administrador del sistema.';
				//('.$mail->ErrorInfo.')
				if ($bDebug){$sError=$sError.'<br>Error: '.$mail->ErrorInfo;}
				}
			} catch (Exception $e) {
			$sError='Error al intentar enviar el mensaje de correo, por favor comuniquese con el administrador del sistema.';
			//<!-- '.$e->getMessage().' -->
			}
		//Termina de enviar el correo.
		}
	return $sError;
	}
function RecurrenteCerrar(){
	unset($this->objMAIL);
	}
function sListaCorreos($bCompletos=true){
	$sRes='';
	$iIni=1;
	if (!$bCompletos){$iIni=2;}
	for ($k=$iIni;$k<=$this->iDestinatarios; $k++){
		if ($sRes!=''){$sRes=$sRes.', ';}
		$sRes=$sRes.$this->correos[$k];
		}
	return $sRes;
	}
function TraerSMTP($idSMTP){
	$objdb=$this->objdb;
	if ($idSMTP>-1){
		$sql='SELECT * FROM unad69smtp WHERE unad69id='.$idSMTP;
		$tabla=$objdb->ejecutasql($sql);
		if ($objdb->nf($tabla)){
			$this->idSMTP=$idSMTP;
			$this->fila17=$objdb->sf($tabla);
			}
		}
	}
function __construct($objdb){
	$this->objdb=$objdb;
	}
}
?>