<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2017 - 2018 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
*/
function AUREA_ActualizarPerfilMoodle($idTercero, $objDB, $bDebug=false){
	$sError='';
	$sDebug='';
	require './app.php';
	if (isset($APP->dbhostcampus)==0){$APP->dbhostcampus='';}
	if ($APP->dbhostcampus!=''){
		//alistamos los datos 
		$sSQL='SELECT unad11doc, unad11usuario, unad11nombre1, unad11nombre2, unad11apellido1, unad11apellido2, unad11presentacion, unad11pais, unad11ciudaddoc FROM unad11terceros WHERE unad11id='.$idTercero.'';
		$tabla=$objDB->ejecutasql($sSQL);
		$fila=$objDB->sf($tabla);
		$sCodCiudad=$fila['unad11ciudaddoc'];
		$sNomCiudad='';
		$sCodPais=$fila['unad11pais'];
		$sPrefijoPais='CO';
		if ($sCodCiudad!=''){
			$sSQL='SELECT unad20nombre FROM unad20ciudad WHERE unad20codigo="'.$sCodCiudad.'"';
			$tabla20=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla20)>0){
				$fila20=$objDB->sf($tabla20);
				$sNomCiudad=$fila20['unad20nombre'];
				}
			}
		$bEntraPais=false;
		if ($sCodPais!=''){
			if ($sCodPais!='057'){
				$sSQL='SELECT unad18sufijo FROM unad18pais WHERE unad18codigo="'.$sCodPais.'"';
				$tabla20=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla20)>0){
					$fila20=$objDB->sf($tabla20);
					$sPrefijoPais=strtoupper($fila20['unad18sufijo']);
					}
				}
			}
		$objDBext=new clsdbadmin($APP->dbhostcampus, $APP->dbusercampus, $APP->dbpasscampus, $APP->dbnamecampus);
		if ($APP->dbpuertocampus!=''){$objDBext->dbPuerto=$APP->dbpuertocampus;}
		if ($objDBext->Conectar()){
			list($sCorreoUsuario, $sErrorC, $sDebugC, $sCorreoInstitucional)=AUREA_CorreoPrimario($idTercero, $objDB, $bDebug);
			$sDebug=$sDebug.$sDebugC;
			$sSQL=utf8_decode('UPDATE mdl_user SET idnumber="'.$fila['unad11doc'].'", email="'.$sCorreoUsuario.'", firstname="'.trim($fila['unad11nombre1'].' '.$fila['unad11nombre2']).'", lastname="'.trim($fila['unad11apellido1'].' '.$fila['unad11apellido2']).'", country="'.$sPrefijoPais.'", city="'.$sNomCiudad.'", description="'.$fila['unad11presentacion'].'" WHERE username="'.$fila['unad11usuario'].'"');
			$result=$objDBext->ejecutasql($sSQL);
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' PERFIL CAMPUS - Consulta de actualizacion: '.$sSQL.'.<br>';}
			if ($sCorreoInstitucional==''){$sCorreoInstitucional=$sCorreoUsuario;}
			// Marzo 13 de 2019
			//Aqui es actualizar el correo institucional en caso de que exista.
			$sSQL='UPDATE main_user SET email="'.$sCorreoInstitucional.'" WHERE username="'.$fila['unad11usuario'].'"';
			$result=$objDBext->ejecutasql($sSQL);
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' PERFIL main_user - Consulta de actualizacion: '.$sSQL.'.<br>';}
			//Termina la actualizacion..
			$objDBext->CerrarConexion();
			}else{
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' PERFIL CAMPUS - No es posible conectarse a la db de campus Error: '.$objDBext->serror.'.<br>';}
			}
		}else{
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' PERFIL CAMPUS - No se ha establecido el parametro dbhostcampus en el archivo app.php por tanto no se puede actualizar el perfil del tercero.<br>';}
		}
	return array($sError, $sDebug);
	}
function AUREA_Aplicativos($idTercero, $objDB){
	$sLista='-99';
	//, unad07fechavence
	$sSQL='SELECT T5.unad05aplicativo FROM unad07usuarios AS TB, unad05perfiles AS T5 WHERE TB.unad07idtercero='.$idTercero.' AND TB.unad07idperfil=T5.unad05id AND TB.unad07vigente="S" AND T5.unad05aplicativo=-1';
	$tabla07=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla07)>0){
		$sSQL='SELECT unad01id FROM unad01sistema WHERE unad01publico="S"';
		$tabla07=$objDB->ejecutasql($sSQL);
		while($fila07=$objDB->sf($tabla07)){
			$sLista=$sLista.','.$fila07['unad01id'];
			}
		}else{
		$sSQL='SELECT T5.unad05aplicativo FROM unad07usuarios AS TB, unad05perfiles AS T5 WHERE TB.unad07idtercero='.$idTercero.' AND TB.unad07idperfil=T5.unad05id AND TB.unad07vigente="S" AND T5.unad05aplicativo>0 GROUP BY T5.unad05aplicativo';
		$tabla07=$objDB->ejecutasql($sSQL);
		while($fila07=$objDB->sf($tabla07)){
			$sLista=$sLista.','.$fila07['unad05aplicativo'];
			}
		}
	return $sLista;
	}
function AUREA_SmtpMejor($sTabla, $objDB, $bDebug=false){
	//Valor por defecto
	$idSMTP=2;
	$sDebug='';
	$aLista=array();
	$iTotal=0;
	//Cargar el listado de SMTPS.
	$sSQL='SELECT TB.unad89idsmtp FROM unad89loginsmtp AS TB, unad69smtp AS T1 WHERE TB.unad89activo="S" AND TB.unad89idopciones=1 AND TB.unad89idsmtp=T1.unad69id AND T1.unad69confirmado="S"';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$iTotal++;
		$aLista[$iTotal]['cod']=$fila['unad89idsmtp'];
		$aLista[$iTotal]['uso']=0;
		}
	if ($iTotal>0){
		//Ver que tanto uso ha tenido cada smtp
		$aure01fecha=fecha_DiaMod();
		$sSQL='SELECT aure01idsmtp, COUNT(aure01id) AS Total FROM '.$sTabla.' WHERE aure01fecha='.$aure01fecha.' GROUP BY aure01idsmtp';
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			for ($k=1;$k<=$iTotal;$k++){
				if ($aLista[$k]['cod']==$fila['aure01idsmtp']){
					$aLista[$k]['uso']=$fila['Total'];
					$k=$iTotal+1;
					}
				}
			}
		$iMenor=999999;
		for ($k=1;$k<=$iTotal;$k++){
			if ($aLista[$k]['uso']<$iMenor){
				$iMenor=$aLista[$k]['uso'];
				$idSMTP=$aLista[$k]['cod'];
				}
			}
		}
	return array($idSMTP, $sDebug);
	}
function AUREA_ConfirmarCorreoNotifica($idTercero, $objDB, $sFrase='', $bDebug=false){
	$sError='';
	$sDebug='';
	$aure01codigo='';
	require './app.php';
	$sTabla='aure01login'.date('Ym');
	$bexiste=$objDB->bexistetabla($sTabla);
	if ($objDB->dbmodelo=='M'){
		if (!$bexiste){
			$sSQL="CREATE TABLE ".$sTabla." (aure01idtercero int NOT NULL, aure01consec int NOT NULL, aure01id int NULL DEFAULT 0, aure01fecha int NULL DEFAULT 0, aure01min int NULL DEFAULT 0, aure01codigo varchar(20) NULL, aure01fechaaplica int NULL DEFAULT 0, aure01minaplica int NULL DEFAULT 0, aure01ip varchar(50) NULL, aure01punto varchar(100) NULL, aure01idsmtp int NULL DEFAULT 0)";
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError='No es posible iniciar el codigo de acceso para  '.date('Ym');
				}else{
				$sSQL="ALTER TABLE ".$sTabla." ADD PRIMARY KEY(aure01id)";
				$result=$objDB->ejecutasql($sSQL);
				$sSQL="ALTER TABLE ".$sTabla." ADD UNIQUE INDEX aure01solmodclave_id(aure01idtercero, aure01consec)";
				$result=$objDB->ejecutasql($sSQL);
				}
			}
		}
	$sCorreoUsuario='';
	if ($sError==''){
		$sSQL='SELECT unad11correonotifica, unad11correoinstitucional, unad11fechaconfmail, unad11correonotificanuevo FROM unad11terceros WHERE unad11id='.$idTercero.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$bHayCorreo=false;
			if ($fila['unad11fechaconfmail']==0){
				if (correo_VerificarDireccion($fila['unad11correonotifica'])){
					$bHayCorreo=true;
					$sCorreoUsuario=$fila['unad11correonotifica'];
					}
				}else{
				if (correo_VerificarDireccion($fila['unad11correonotificanuevo'])){
					$bHayCorreo=true;
					$sCorreoUsuario=$fila['unad11correonotificanuevo'];
					}
				}
			}
		if ($sCorreoUsuario==''){
			$sError='No se ha establecido un correo electr&oacute;nico de notificaciones v&aacute;lido.';
			}
		}
	if ($sError==''){
		list($idSMTP, $sDebugS)=AUREA_SmtpMejor($sTabla, $objDB, $bDebug);
		//Agregar el punto.
		$sProtocolo='http';
		if (isset($_SERVER['HTTPS'])!=0){
			if ($_SERVER['HTTPS']=='on'){$sProtocolo='https';}
			}
		$aure01punto=$sProtocolo.'://'.$_SERVER['SERVER_NAME'].formato_UrlLimpia($_SERVER['REQUEST_URI']);
		$aure01consec=0;
		$bInserta=true;
		$sSQL='SELECT aure01id FROM '.$sTabla.' WHERE aure01idtercero='.$idTercero.' AND aure01consec=0';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$bInserta=false;
			$fila=$objDB->sf($tabla);
			$aure01id=$fila['aure01id'];
			}
		$aure01fecha=fecha_DiaMod();
		$aure01min=fecha_MinutoMod();
		$aure01codigo=md5($aure01fecha.$aure01min.$idTercero.$sTabla);
		$aure01codigo=numeros_validar($aure01codigo);
		$aure01codigo=substr($aure01codigo, 0, 10);
		$aure01ip=sys_traeripreal();
		if ($bInserta){
			$aure01id=tabla_consecutivo($sTabla, 'aure01id', '', $objDB);
			$scampos='aure01idtercero, aure01consec, aure01id, aure01fecha, 
aure01min, aure01codigo, aure01fechaaplica, aure01minaplica, aure01ip, aure01punto, aure01idsmtp';
			$svalores=''.$idTercero.', '.$aure01consec.', '.$aure01id.', '.$aure01fecha.', 
'.$aure01min.', "'.$aure01codigo.'", -1, 0, "'.$aure01ip.'", "'.$aure01punto.'", '.$idSMTP.'';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO '.$sTabla.' ('.$scampos.') VALUES ('.utf8_encode($svalores).');';
				}else{
				$sSQL='INSERT INTO '.$sTabla.' ('.$scampos.') VALUES ('.$svalores.');';
				}
			}else{
			//actualizar el consecutivo 0
			$sSQL='UPDATE '.$sTabla.' SET aure01fecha='.$aure01fecha.', aure01min='.$aure01min.', aure01codigo="'.$aure01codigo.'", aure01fechaaplica=-1, aure01minaplica=0, aure01ip="'.$aure01ip.'", aure01punto="'.$aure01punto.'", aure01idsmtp='.$idSMTP.' WHERE aure01id='.$aure01id.'';
			}
		$result=$objDB->ejecutasql($sSQL);
		}
	if ($sError==''){
		//Ahora que se genero el codigo enviarlo al correo.
		require $APP->rutacomun.'libmail.php';
		$sNomEntidad='UNIVERSIDAD NACIONAL ABIERTA Y A DISTANCIA - UNAD';
		//$URL=url_encode(''.$aure01id.'|'.$aure01codigo.'|'.$sFrase);
		$sMsg='<h1>C&oacute;digo de confirmaci&oacute;n de correo de notificaciones en '.$sNomEntidad.'</h1>
Su c&oacute;digo de confirmaci&oacute;n es:<br>
<h2>'.$aure01codigo.'</h2><br>
<br>Este c&oacute;digo estar&aacute; vigente durante todo el d&iacute;a.<br>
<b>Comedidamente:</b><br>
Equipo de Soporte T&eacute;cnico.';
		//Enviar el mensaje.
		$objMail=new clsMail_Unad($objDB);
		$objMail->TraerSMTP($idSMTP);
		$objMail->sAsunto=utf8_encode('Confirmación de correo electrónico en '.$sNomEntidad.' '.fecha_hoy().' '.html_TablaHoraMin(fecha_hora(), fecha_minuto()).'');
		$objMail->addCorreo($sCorreoUsuario, $sCorreoUsuario);
		if ($sError==''){
			$objMail->sCuerpo=$sMsg;
			$sError=$objMail->Enviar();
			}
		if ($sError!=''){
			}else{
			}
		//Termina el envio del codigo...
		}
	return array($aure01codigo, $sError, $sDebug);
	}
function AUREA_CorreoNotifica($idTercero, $objDB, $bDebug=false){
	$sError='';
	$sCorreoUsuario='';
	$sDebug='';
	$sSQL='SELECT unad11correo, unad11aceptanotificacion, unad11correonotifica, unad11correoinstitucional, unad11fechaconfmail, unad11rolunad, unad11correofuncionario 
FROM unad11terceros WHERE unad11id='.$idTercero.'';
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta para el correo de notificaciones '.$sSQL.' <br>';}
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$bHayCorreo=false;
		if ($fila['unad11fechaconfmail']!=0){
			//Este proceso es independiente de que acepte notificaciones o no....
			if (correo_VerificarDireccion(trim($fila['unad11correonotifica']))){
				$bHayCorreo=true;
				$sCorreoUsuario=trim($fila['unad11correonotifica']);
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Correo de notificaciones '.$sCorreoUsuario.' validado<br>';}
				}
			}
		if (!$bHayCorreo){
			if (correo_VerificarDireccion(trim($fila['unad11correofuncionario']))){
				$bHayCorreo=true;
				$sCorreoUsuario=trim($fila['unad11correofuncionario']);
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Correo de notificaciones '.$sCorreoUsuario.' {Correo del funcionario}<br>';}
				}
			}
		if (!$bHayCorreo){
			$sOpcion1=trim($fila['unad11correo']);
			$sOpcion2=trim($fila['unad11correoinstitucional']);
			if ($fila['unad11rolunad']>0){
				//$sOpcion1=$fila['unad11correoinstitucional'];
				//$sOpcion2=$fila['unad11correo'];
				}
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Verificando correo '.$sOpcion1.' <br>';}
			if (correo_VerificarDireccion($sOpcion1)){
				$bHayCorreo=true;
				$sCorreoUsuario=$sOpcion1;
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Opcion 1 '.$sOpcion1.' validada<br>';}
				}
			if (!$bHayCorreo){
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Verificando correo 2 '.$sOpcion2.' <br>';}
				if (correo_VerificarDireccion($sOpcion2)){
					$bHayCorreo=true;
					$sCorreoUsuario=$sOpcion2;
					if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Opcion 2 '.$sOpcion2.' validada<br>';}
					}
				}
			}
		}
	if ($sCorreoUsuario==''){
		$sError='No se ha establecido un correo electr&oacute;nico v&aacute;lido para el usuario.';
		}
	return array($sCorreoUsuario, $sError, $sDebug);
	}
function AUREA_CorreoPrimario($idTercero, $objDB, $bDebug=false){
	$sError='';
	$sCorreoUsuario='';
	$sCorreoInstitucional='';
	$sDebug='';
	$sSQL='SELECT unad11correo, unad11aceptanotificacion, unad11correonotifica, unad11correoinstitucional, unad11fechaconfmail, unad11rolunad, unad11correofuncionario 
FROM unad11terceros 
WHERE unad11id='.$idTercero.'';
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta para los correos '.$sSQL.' <br>';}
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$bHayCorreo=false;
		if (correo_VerificarDireccion(trim($fila['unad11correofuncionario']))){
			$bHayCorreo=true;
			$sCorreoUsuario=trim($fila['unad11correofuncionario']);
			$sCorreoInstitucional=$sCorreoUsuario;
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Correo primario '.$sCorreoUsuario.' <br>';}
			}
		if (!$bHayCorreo){
			if (correo_VerificarDireccion(trim($fila['unad11correoinstitucional']))){
				$bHayCorreo=true;
				$sCorreoUsuario=trim($fila['unad11correoinstitucional']);
				$sCorreoInstitucional=$sCorreoUsuario;
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Correo primario '.$sCorreoUsuario.' <br>';}
				}
			}
		if (!$bHayCorreo){
			if ($fila['unad11fechaconfmail']!=0){
				//Este proceso es independiente de que acepte notificaciones o no....
				if (correo_VerificarDireccion(trim($fila['unad11correonotifica']))){
					$bHayCorreo=true;
					$sCorreoUsuario=trim($fila['unad11correonotifica']);
					if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Correo de notificaciones '.$sCorreoUsuario.' validado<br>';}
					}
				}
			}
		if (!$bHayCorreo){
			$sOpcion1=trim($fila['unad11correo']);
			$sOpcion2=trim($fila['unad11correoinstitucional']);
			if ($fila['unad11rolunad']>0){
				//$sOpcion1=$fila['unad11correoinstitucional'];
				//$sOpcion2=$fila['unad11correo'];
				}
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Verificando correo '.$sOpcion1.' <br>';}
			if (correo_VerificarDireccion($sOpcion1)){
				$bHayCorreo=true;
				$sCorreoUsuario=$sOpcion1;
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Opcion 1 '.$sOpcion1.' validada<br>';}
				}
			if (!$bHayCorreo){
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Verificando correo 2 '.$sOpcion2.' <br>';}
				if (correo_VerificarDireccion($sOpcion2)){
					$bHayCorreo=true;
					$sCorreoUsuario=$sOpcion2;
					if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Opcion 2 '.$sOpcion2.' validada<br>';}
					}
				}
			}
		}
	if ($sCorreoUsuario==''){
		$sError='No se ha establecido un correo electr&oacute;nico v&aacute;lido para el usuario.';
		}
	return array($sCorreoUsuario, $sError, $sDebug, $sCorreoInstitucional);
	}
function AUREA_CorreoRecupera($idTercero, $objDB, $bDebug=false){
/**
* @date miercoles, 22 de mayo de 2019
* Esta funcion es similar a AUREA_CorreoNotifica solo que tiene reglas diferentes.
*/
	$sError='';
	$sCorreoUsuario='';
	$sDebug='';
	$sSQL='SELECT unad11correo, unad11aceptanotificacion, unad11correonotifica, unad11correoinstitucional, unad11fechaconfmail, unad11rolunad, unad11correofuncionario 
FROM unad11terceros WHERE unad11id='.$idTercero.'';
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta para el correo de notificaciones '.$sSQL.' <br>';}
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$bHayCorreo=false;
		if ($fila['unad11fechaconfmail']!=0){
			//Este proceso es independiente de que acepte notificaciones o no....
			if (correo_VerificarDireccion(trim($fila['unad11correonotifica']))){
				$bHayCorreo=true;
				$sCorreoUsuario=trim($fila['unad11correonotifica']);
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Correo de notificaciones '.$sCorreoUsuario.' validado<br>';}
				}
			}
		if (!$bHayCorreo){
			if (correo_VerificarDireccion(trim($fila['unad11correofuncionario']))){
				$bHayCorreo=true;
				$sCorreoUsuario=trim($fila['unad11correofuncionario']);
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Correo de notificaciones '.$sCorreoUsuario.' {Correo del funcionario}<br>';}
				}
			}
		if (!$bHayCorreo){
			$sOpcion1=trim($fila['unad11correo']);
			$sOpcion2=trim($fila['unad11correoinstitucional']);
			if ($fila['unad11rolunad']>0){
				//$sOpcion1=$fila['unad11correoinstitucional'];
				//$sOpcion2=$fila['unad11correo'];
				}
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Verificando correo '.$sOpcion1.' <br>';}
			if (correo_VerificarDireccion($sOpcion1)){
				$bHayCorreo=true;
				$sCorreoUsuario=$sOpcion1;
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Opcion 1 '.$sOpcion1.' validada<br>';}
				}
			if (!$bHayCorreo){
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Verificando correo 2 '.$sOpcion2.' <br>';}
				if (correo_VerificarDireccion($sOpcion2)){
					$bHayCorreo=true;
					$sCorreoUsuario=$sOpcion2;
					if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Opcion 2 '.$sOpcion2.' validada<br>';}
					}
				}
			}
		}
	if ($sCorreoUsuario==''){
		$sError='No se ha establecido un correo electr&oacute;nico v&aacute;lido para el usuario.';
		}
	return array($sCorreoUsuario, $sError, $sDebug);
	}
function AUREA_IniciarLogin($idTercero, $objDB, $sFrase='', $iUso=0, $bDebug=false){
	//El uso 0 es para iniciar sesion , el 1 es para recuperar contraseña. y 2 para recuperar contrase;a desde soporte.
	$sError='';
	$sDebug='';
	$aure01codigo='';
	$bDesdeSoporte=false;
	if ($iUso==2){
		$bDesdeSoporte=true;
		$iUso=1;
		}
	$sInfoRastro='Se inicia envio de codigo de acceso';
	require './app.php';
	$sTabla='aure01login'.date('Ym');
	$bexiste=$objDB->bexistetabla($sTabla);
	if ($objDB->dbmodelo=='M'){
		if (!$bexiste){
			$sSQL="CREATE TABLE ".$sTabla." (aure01idtercero int NOT NULL, aure01consec int NOT NULL, aure01id int NULL DEFAULT 0, aure01fecha int NULL DEFAULT 0, aure01min int NULL DEFAULT 0, aure01codigo varchar(20) NULL, aure01fechaaplica int NULL DEFAULT 0, aure01minaplica int NULL DEFAULT 0, aure01ip varchar(50) NULL, aure01punto varchar(100) NULL, aure01idsmtp int NULL DEFAULT 0)";
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError='No es posible iniciar el codigo de acceso para  '.date('Ym').'';
				}else{
				$sSQL="ALTER TABLE ".$sTabla." ADD PRIMARY KEY(aure01id)";
				$result=$objDB->ejecutasql($sSQL);
				$sSQL="ALTER TABLE ".$sTabla." ADD UNIQUE INDEX aure01solmodclave_id(aure01idtercero, aure01consec)";
				$result=$objDB->ejecutasql($sSQL);
				}
			}
		}
	$sCorreoUsuario='';
	$sMailSeguridad='soporte.campus@unad.edu.co';
	$iCodigoRastro=0;
	if ($sError==''){
		list($sCorreoUsuario, $sError, $sDebugM)=AUREA_CorreoNotifica($idTercero, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugM;
		}
	if ($sError==''){
		list($idSMTP, $sDebugS)=AUREA_SmtpMejor($sTabla, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugS;
		//Agregar el punto.
		$sProtocolo='http';
		if (isset($_SERVER['HTTPS'])!=0){
			if ($_SERVER['HTTPS']=='on'){$sProtocolo='https';}
			}
		$aure01punto=$sProtocolo.'://'.$_SERVER['SERVER_NAME'].formato_UrlLimpia($_SERVER['REQUEST_URI']);
		$aure01consec=tabla_consecutivo($sTabla, 'aure01consec', 'aure01idtercero='.$idTercero.'', $objDB);
		$aure01id=tabla_consecutivo($sTabla, 'aure01id', '', $objDB);
		$aure01fecha=fecha_DiaMod();
		$aure01min=fecha_MinutoMod();
		$aure01codigo=md5($aure01fecha.$aure01min.$idTercero.$sTabla);
		$aure01codigo=numeros_validar($aure01codigo);
		$aure01codigo=substr($aure01codigo, 0, 10);
		$aure01ip=sys_traeripreal();
		$scampos='aure01idtercero, aure01consec, aure01id, aure01fecha, 
aure01min, aure01codigo, aure01fechaaplica, aure01minaplica, aure01ip, aure01punto, aure01idsmtp';
		$svalores=''.$idTercero.', '.$aure01consec.', '.$aure01id.', '.$aure01fecha.', 
'.$aure01min.', "'.$aure01codigo.'", -1, 0, "'.$aure01ip.'", "'.$aure01punto.'", '.$idSMTP.'';
		if ($APP->utf8==1){
			$sSQL='INSERT INTO '.$sTabla.' ('.$scampos.') VALUES ('.utf8_encode($svalores).');';
			}else{
			$sSQL='INSERT INTO '.$sTabla.' ('.$scampos.') VALUES ('.$svalores.');';
			}
		$result=$objDB->ejecutasql($sSQL);
		//Ahora que se genero el codigo enviarlo al correo.
		if (!class_exists('clsMail_Unad')){
			require $APP->rutacomun.'libmail.php';
			}
		$sNomEntidad='UNIVERSIDAD NACIONAL ABIERTA Y A DISTANCIA - UNAD';
		if ($iUso==0){
			$sInfoRastro='Se inicia envio de codigo de acceso al correo '.$sCorreoUsuario.'';
			$sTituloCorreo='Acceso a plataforma institucional en '.$sNomEntidad.'';
			$URL=url_encode(''.$aure01id.'|'.$aure01codigo.'|'.$sFrase);
			/*
			$sMsg='<h1>ACCESO A LA PLATAFORMA INSTITUCIONAL '.$sNomEntidad.'</h1>
Para acceder a la plataforma por favor utilice el siguiente c&oacute;digo:<br>
<h2>'.$aure01codigo.'</h2><br>
o ingrese al siguiente link:<br>
<a href="'.$aure01punto.'?u='.$URL.'">'.$aure01punto.'?u='.$URL.'</a>
<br>Recuerde que este acceso vence en dos horas.<br>
<b>Comedidamente:</b><br>
Equipo de Soporte T&eacute;cnico.';
			*/
			$sMsg='<div style="background: url(\'http://datateca.unad.edu.co/img/fondo201804.png\'); height: 509px; margin: 0 auto; width: 985px; padding-top:290px;">
<h2 style="text-align: center; color: #333; font-size: 22px;">Continuaci&oacute;n proceso de ingreso de campus virtual</h2>
<h3 style="text-align: center; color: #22A7E0; font-size: 22px;">UNIVERSIDAD NACIONAL ABIERTA Y A DISTANCIA - UNAD</h3>
<p style="max-width: 500px; margin: 0 auto; padding-top: 10px; font-size: 18px; color: #666; ">Para continuar con el proceso de ingreso a Campus Virtual por favor utilice el siguiente c&oacute;digo de verificaci&oacute;n:</p>
<div class="" style="-webkit-box-shadow: -4px 16px 23px -13px rgba(0,0,0,0.75);
-moz-box-shadow: -4px 16px 23px -13px rgba(0,0,0,0.75);
box-shadow: -4px 16px 23px -13px rgba(0,0,0,0.75); font-size: 30px; border: #FFF solid 1px; max-width: 300px; margin: 10px auto; padding: 10px 10px; background: #E7E8EB; color: #22A7E0;  text-align: center;">'.$aure01codigo.'</div>
<p style="color: #22A7E0; font-size: 16px; text-align: center;">o utilice el siguiente enlace:</p>
<a href="'.$aure01punto.'?u='.$URL.'" style="padding:0; margin: 0 auto; color: #22A7E0; font-size:12px; text-align: center; max-width:900px; display:block; overflow:auto;">'.$aure01punto.'?u='.$URL.'</a>
</div>';
			}else{
			//Recuperar la clave...
			$sAdvertencia='En caso de que usted no haya solicitado la recuperaci&oacute;n de su contrase&ntilde;a por favor reenvie este mensaje a '.$sMailSeguridad.'';
			$sInfoRastro='Se envia codigo para CAMBIO DE CLAVE al correo '.$sCorreoUsuario.'';
			$sTituloCorreo=('Codigo de acceso en ').$sNomEntidad.'';
			$sMsg='<h1>Recuperar Contrase&ntilde;a Campus Virtual '.$sNomEntidad.'</h1>';
			$sVencimiento='en dos horas.';
			$iCodigoRastro=3;
			if ($bDesdeSoporte){
				$sInfoRastro='Se envia codigo para REESTABLECER CLAVE al correo '.$sCorreoUsuario.'';
				$aure01punto='https://campus0d.unad.edu.co/campus/recuperar.php';
				$sVencimiento='en doce horas.';
				if ($aure01min>719){
					$sVencimiento='a las 11:59 PM.';
					}
				$URL=url_encode(''.$aure01id.'|'.$aure01codigo.'|97531|'.$sFrase);
				$sAdvertencia='Este mensaje es enviado por el equipo de soporte t&eacute;cnico.';
				$sMsg=$sMsg.'Para continuar con el proceso de recuperaci&oacute;n de contrase&ntilde;a por favor utilice el siguiente enlace: <a href="'.$aure01punto.'?u='.$URL.'">'.$aure01punto.'?u='.$URL.'</a><br>';
				}else{
				$sMsg=$sMsg.'Para continuar con el proceso de recuperaci&oacute;n de contrase&ntilde;a por favor utilice el siguiente c&oacute;digo de verificaci&oacute;n:<br>
<h2>'.$aure01codigo.'</h2><br>';
				}
			$sMsg=$sMsg.'<br>Recuerde que este acceso vence '.$sVencimiento.'<br>
'.$sAdvertencia.' - N&deg; de proceso: '.$aure01id.' - '.date('Ym').'
<b>Comedidamente:</b><br>
Equipo de Soporte T&eacute;cnico.';
			}
		//Enviar el mensaje.
		$objMail=new clsMail_Unad($objDB);
		$objMail->TraerSMTP($idSMTP);
		$objMail->sAsunto=$sTituloCorreo.' '.fecha_hoy().' '.html_TablaHoraMin(fecha_hora(), fecha_minuto()).'';
		$objMail->addCorreo($sCorreoUsuario, $sCorreoUsuario);
		if ($sError==''){
			$objMail->sCuerpo=$sMsg;
			$sError=$objMail->Enviar();
			if ($bDesdeSoporte){
				list($bRes, $sDebugR)=seg_rastro(17, $iCodigoRastro, 0, $_SESSION['unad_id_tercero'], $sInfoRastro, $objDB, $bDebug, $idTercero);
				}else{
				list($bRes, $sDebugR)=seg_rastro(17, $iCodigoRastro, 0, $idTercero, $sInfoRastro, $objDB, $bDebug, $idTercero);
				}
			$sDebug=$sDebug.$sDebugR;
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Se envia correo a '.$sCorreoUsuario.'<br>';}
			}
		if ($sError!=''){
			}else{
			}
		//Termina el envio del codigo...
		}
	return array($aure01codigo, $sError, $sDebug);
	}
function AUREA_RequiereDobleAutenticacion($idTercero, $objDB){
	$bRes=false;
	$bConAlumnos=false;
	//Primero ver si esta habilitado el servicio.
	$sSQL='SELECT unad88loginmail, unad88doblelogest FROM unad88opciones WHERE unad88id=1';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		if ($fila['unad88loginmail']=='S'){
			$bRes=true;
			if ($fila['unad88doblelogest']=='S'){$bConAlumnos=true;}
			}
		}
	if ($bRes){
		$sSQL='SELECT unad11exluirdobleaut, unad11fechaconfmail FROM unad11terceros WHERE unad11id='.$idTercero.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			if ($fila['unad11exluirdobleaut']=='S'){$bRes=false;}
			if ($fila['unad11fechaconfmail']==0){$bRes=false;}
			}
		}
	if ($bRes){
		if (!$bConAlumnos){
			//Saber si la persona tiene un rol diferente a alumno.
			$sSQL='SELECT unad47peraca FROM unad47tablero WHERE unad47idtercero='.$idTercero.' AND unad47activo="S" AND unad47idrol<>5 LIMIT 0,1';
			$tabla=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla)==0){
				//Es un alumno....
				$bRes=false;
				}
			if (!$bRes){
				//Puede ser usuario en alguna aplicacion....
				$sSQL='SELECT unad07idperfil FROM unad07usuarios WHERE unad07idtercero='.$idTercero.' AND unad07vigente="S"';
				$tabla=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla)>0){
					$bRes=true;
					}
				}
			}
		}
	return $bRes;
	}
function AUREA_ClaveValidaV2($sValor, $idTercero, $objDB, $sPermitidos='*._-!'){
	list($sError, $sDebug)=AUREA_ClaveValidaV3($sValor, $idTercero, $objDB, $sPermitidos);
	return $sError;
	}
function AUREA_ClaveValidaV3($sValor, $idTercero, $objDB, $sPermitidos='*._-!', $bDebug=false){
	$sError='';
	$sDebug='';
	if (strlen($sValor)<6){$sError='La contrase&ntilde;a debe ser de m&iacute;nimo 6 caracteres.';}
	if ($sError==''){
		$sValidado=cadena_letrasynumeros($sValor, $sPermitidos);
		if ($sValidado!=$sValor){
			$sError='La contrase&ntilde;a contiene caracteres no permitidos.';
			}
		}
	if ($sError==''){
		//Validar que tenga los minimos...
		//una mayuscula.
		$sValidado=cadena_limpiar($sValor, 'ABCDEFGHIJKLMNÑOPQRSTUVWXYZ');
		if ($sValidado==''){$sError='La contrase&ntilde;a ingresada no contiene may&uacute;sculas';}
		}
	if ($sError==''){
		//una mayuscula.
		$sValidado=cadena_limpiar($sValor, 'abcdefghijklmnñopqrstuvwxyz');
		if ($sValidado==''){$sError='La contrase&ntilde;a ingresada no contiene min&uacute;scula';}
		}
	if ($sError==''){
		$sValidado=cadena_limpiar($sValor, '1234567890');
		if ($sValidado==''){$sError='La contrase&ntilde;a ingresada no contiene numeros';}
		}
	if ($sError==''){
		$sValidado=cadena_limpiar($sValor, $sPermitidos);
		if ($sValidado==''){$sError='La contrase&ntilde;a ingresada no contiene caracteres especiales';}
		}
	if ($sError==''){
		//Que no este poniendo la misma.
		$sHash=password_hash($sValor, PASSWORD_DEFAULT);
		$sSQL='SELECT unad11clave FROM unad11terceros WHERE unad11id='.$idTercero.'';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Verificando la clave actual. '.$sSQL.'<br>';}
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			if (password_verify($sValor, $fila['unad11clave'])){
				$sError='La contrase&ntilde;a ingresada es la misma que usa actualmente. Debe cambiarla.';
				}
			}
		}
	if ($sError==''){
		//Ver que no se haya usado recientemente.
		$sSQL='SELECT unae10hash, unae10fecha FROM unae10historialclave WHERE unae10idtercero='.$idTercero.' ORDER BY unae10fecha DESC LIMIT 0, 3';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Historial de hash '.$sSQL.'<br>';}
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			//Revisar que las claves historicas no sean las mismas.
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Verficando hash del '.$fila['unae10fecha'].'<br>';}
			if (password_verify($sValor, $fila['unae10hash'])){
				$sError='La contrase&ntilde;a ingresada ha sido usada recientemente';
				}
			}
		}
	return array($sError, $sDebug);
	}
?>