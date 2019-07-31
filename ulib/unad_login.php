<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
*/
function login_ActualizarClave($sUsuario, $sNueva, $idTercero, $objdb){
	$sRes='';
	require './app.php';
	//Actualizamos la clave en unadsys... uno nunca sabe...
	$iDia=fecha_DiaMod();
	$sHash=password_hash($sNueva, PASSWORD_DEFAULT);
	$sql='UPDATE unad11terceros SET unad11clave="'.$sHash.'", unad11fechaclave='.$iDia.' WHERE unad11id='.$idTercero.'';
	$result=$objdb->ejecutasql($sql);
	//Termina de actualizar unadsys
	$bHayDB=false;
	if ($sRes==''){
		if (isset($APP->dbhostcampus)==0){
			$sRes='Error 351 al intentar iniciar el proceso de login, por favor informar al administrador del sistema.';
			}
		}
	if ($sRes==''){
	
		//Se cambia la clave en los servicios moodle...
		$objdbcampus=new clsdbadmin($APP->dbhostcampus, $APP->dbusercampus, $APP->dbpasscampus, $APP->dbnamecampus);
		if ($APP->dbpuertocampus!=''){$objdbcampus->dbPuerto=$APP->dbpuertocampus;}
		if ($objdbcampus->conectar()){
			$bHayDB=true;
			$sql='SELECT password, idnumber, username FROM mdl_user WHERE username="'.$sUsuario.'"';
			$tabla=$objdbcampus->ejecutasql($sql);
			if ($objdbcampus->nf($tabla)>0){
				$fila=$objdbcampus->sf($tabla);
				$idMoodle=$fila['idnumber'];
				$sql='UPDATE mdl_user SET password="'.$sHash.'" WHERE idnumber='.$idMoodle.'';
				$tabla=$objdbcampus->ejecutasql($sql);
				//Auditar.
				$sdetalle='EL usuario cambia la clave de acceso.';
				seg_auditar(111, $idTercero, 3, $idTercero, $sdetalle, $objdb);
				}
			}else{
			$sRes='Error al intentar actualizar la contrase&ntilde;a<br>'.$objdbcampus->serror.' <br>Por favor informa al administrador del sistema.';
			}
		}
	if ($bHayDB){
		$objdbcampus->CerrarConexion();
		}
	return $sRes;
	}
function login_sesiones($sHost, $sNavegador, $sIP, $objdb, $bDebug=false){
	$sRes='';
	$iNumSesiones=0;
	$sDebug='';
	$iHoy=fecha_DiaMod();
	require './app.php';
	$iMinuto=fecha_MinutoMod()-$APP->tiempolimite;
	$sTabla='unad71sesion'.fecha_agno();
	// AND TB.unad71hostname="'.$sHost.'"
	// AND TB.unad71iporigen="'.$sIP.'" 
	//-- LA IP 190.66.14.194  es la ip para todo lo que este dentro de la unad...
	$sql='SELECT TB.unad71idtercero, T1.unad11usuario
FROM '.$sTabla.' AS TB, unad11terceros AS T1 
WHERE TB.unad71fechaini='.$iHoy.' AND TB.unad71iporigen IN ("'.$sIP.'", "190.66.14.194") AND TB.unad71hostname="'.$sHost.'" AND TB.unad71navegador="'.$sNavegador.'" 
AND TB.unad71tiempototal=0 
AND (((TB.unad71horafin*60)+TB.unad71minutofin)>('.$iMinuto.') OR ((TB.unad71horafin+TB.unad71minutofin)=0)) 
AND TB.unad71idtercero=T1.unad11id
GROUP BY TB.unad71idtercero, T1.unad11usuario';
	if ($bDebug){
		$sDebug=$sql;
		}
	//$sError=$sNavegador;
	$tabla=$objdb->ejecutasql($sql);
	while($fila=$objdb->sf($tabla)){
		if ($sRes!=''){$sRes=$sRes.'|';}
		$sRes=$sRes.$fila['unad11usuario'];
		$iNumSesiones++;
		}
	return array($sRes, $iNumSesiones, $sDebug);
	}
function login_validarV2($sDoc, $sUsuario, $spw, $objdb, $bDebug=false){
	require './app.php';
	$sRes='';
	$sDebug='';
	$sDoc=numeros_validar($sDoc);
	$sUsuario=htmlspecialchars($sUsuario);
	/*
	//Aqui no esta dando el documento...., con el documento toca buscar el usuario moodle y luego si validar sobre la db moodle.
	$sql='SELECT unad11usuario FROM unad11terceros WHERE unad11doc="'.$sDoc.'"';
	$tabla=$objdb->ejecutasql($sql);
	if ($objdb->nf($tabla)>0){
		$fila=$objdb->sf($tabla);
		$sUsuario=$fila['unad11usuario'];
		}else{
		$sRes='Documento no encontrado o contrase&ntilde;a incorrecta..';
		}
	*/
	if ($sRes==''){
		if ($sDoc==''){
			if ($sUsuario==''){
				$sRes='Usuario no v&aacute;lido';
				}
			}
		}
	$bHayDB=false;
	if ($sRes==''){
		if (isset($APP->dbhostcampus)==0){
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Hace falta el parametro dbhostcampus en el archivo de configuraci&oacute;n<br>';}
			$sRes='Error 351 al intentar iniciar el proceso de login, por favor informar al administrador del sistema.';
			}
		}
	if ($sRes==''){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Intentando conectar con la db de campus en '.$APP->dbhostcampus.' db '.$APP->dbnamecampus.'<br>';}
		$objdbcampus=new clsdbadmin($APP->dbhostcampus, $APP->dbusercampus, $APP->dbpasscampus, $APP->dbnamecampus);
		if ($APP->dbpuertocampus!=''){$objdbcampus->dbPuerto=$APP->dbpuertocampus;}
		if ($objdbcampus->conectar()){
			$bHayDB=true;
			if ((int)$sDoc==0){
				$sql='SELECT password, idnumber, username FROM mdl_user WHERE username="'.$sUsuario.'"';
				}else{
				$sql='SELECT password, idnumber, username FROM mdl_user WHERE idnumber='.$sDoc.'';
				}
			$tabla=$objdbcampus->ejecutasql($sql);
			if ($objdbcampus->nf($tabla)>0){
				$fila=$objdbcampus->sf($tabla);
				$sUsuario=$fila['username'];
				$sHash=$fila['password'];
				if (password_verify($spw, $sHash)){
					$sRes='pasa';
					}else{
					if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' No pasa por HASH<br>';}
					$sRes='Contrase&ntilde;a incorrecta.';
					//Vamos a ver si viene con md5 o pura.
					if (md5($spw)==$fila['password']){
						$sRes='pasa';
						if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Logeado por MD5<br>';}
						//Construir el hash...
						$sHash=password_hash($spw, PASSWORD_DEFAULT);
						$sql='UPDATE mdl_user SET password="'.$sHash.'" WHERE username="'.$sUsuario.'"';
						$tabla=$objdbcampus->ejecutasql($sql);
						}else{
						if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' No pasa por MD5 <!-- '.$fila['password'].' --><br>';}
						//Consultar si es la clave de RCA..., en dicho caso... armar el hash....
						//
						}
					}
				}else{
				$sRes='Documento no encontrado.';
				}
			
			}else{
			$sRes='Error 352 al intentar iniciar el proceso de login<br>'.$objdbcampus->serror.' <br>Por favor informa al administrador del sistema.';
			}
		}
	if ($sRes==''){
		//ya biene la objdbcampus.
		}
	if ($bHayDB){
		$objdbcampus->CerrarConexion();
		}
	return array($sRes, $sDebug);
	}
?>