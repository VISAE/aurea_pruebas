<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2014 - 2018 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 1.2.0 viernes, 11 de julio de 2014
--- Jueves 30 de Abril de 2015 - si es el destiono es 0 - No ofertado, no se cambia el estado campus.
*/
function f1730_CambiaEstado($idOferta, $idOrigen, $idDestino, $sNota, $objDB, $bDebug=false){
	$sError='';
	$ofer30consec=tabla_consecutivo('ofer30ofertahistestcampus', 'ofer30consec', 'ofer30idoferta='.$idOferta.'', $objDB);
	if ($ofer30consec==-1){$sError=$objDB->serror;}
	if ($sError==''){
		$ofer30id=tabla_consecutivo('ofer30ofertahistestcampus', 'ofer30id', '', $objDB);
		if ($ofer30id==-1){$sError=$objDB->serror;}
		}
	if ($sError==''){
		$iHoy=fecha_DiaMod();
		$ofer30fecha=fecha_hoy();
		$ofer30hora=fecha_hora();
		$ofer30minuto=fecha_minuto();
		$iMinuto=($ofer30hora*60)+$ofer30minuto;
		$ofer30anotacion=str_replace('&quot;', '\"', $sNota);
		$scampos='ofer30idoferta, ofer30consec, ofer30id, ofer30fecha, ofer30hora, ofer30minuto, ofer30idactor, ofer30estadoorigen, ofer30estadodestino, ofer30anotacion';
		$svalores=''.$idOferta.', '.$ofer30consec.', '.$ofer30id.', "'.$ofer30fecha.'", '.$ofer30hora.', '.$ofer30minuto.', '.$_SESSION['unad_id_tercero'].', '.$idOrigen.', '.$idDestino.', "'.$ofer30anotacion.'"';
		$sSQL='INSERT INTO ofer30ofertahistestcampus ('.$scampos.') VALUES ('.$svalores.');';
		$result=$objDB->ejecutasql($sSQL);
		//ahora si el cambio de estado...
		$sAdd='ofer08estadocampus='.$idDestino.', ';
		//Diciembre 4 de 2018, se agrega esta data de avance para saber como va la cosa.
		switch($idDestino){
			case 0:
			$sAdd='';
			break;
			case 20: //Para alistar.
			$sAdd='ofer08av_avance=10, '.$sAdd;
			break;
			case 2: //En alistamiento.
			$sAdd='ofer08av_avance=20, ofer08av_fechaalista='.$iHoy.', ofer08av_horaalista='.$iMinuto.', '.$sAdd;
			$bPasa=false;
			if ($idOrigen==8){$bPasa=true;}
			if ($idOrigen==11){$bPasa=true;}
			if ($bPasa){
				//hacer el registro de la devolucion..
				$sAdd='ofer08numdevoluciones=(ofer08numdevoluciones+1), '.$sAdd;
				}
			break;
			case 8: //En acreditacion.
			case 11: //En certificacion
			//Saber si el avance esta en 20, de lo contrario nada.
			$sSQL='SELECT ofer08av_avance FROM ofer08oferta WHERE ofer08id='.$idOferta.'';
			$tabla=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla)>0){
				$fila=$objDB->sf($tabla);
				if ($fila['ofer08av_avance']<30){
					$sAdd='ofer08av_avance=30, ofer08av_fechaevaluado='.$iHoy.', ofer08av_horaevaluado='.$iMinuto.', '.$sAdd;
					}
				}
			$sAdd='ofer08numrevisiones=(ofer08numrevisiones+1), '.$sAdd;
			break;
			case 9: //Ajustes finales.
			$sSQL='SELECT ofer08av_avance FROM ofer08oferta WHERE ofer08id='.$idOferta.'';
			$tabla=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla)>0){
				$fila=$objDB->sf($tabla);
				if ($fila['ofer08av_avance']<40){
					$sAdd='ofer08av_avance=40, ofer08av_fechaajustes='.$iHoy.', ofer08av_horaajustes='.$iMinuto.', '.$sAdd;
					}
				}
			$bPasa=false;
			if ($idOrigen==8){$bPasa=true;}
			if ($idOrigen==11){$bPasa=true;}
			if ($bPasa){
				//hacer el registro de la cantidad de ajustes..
				$sAdd='ofer08numajustes=(ofer08numajustes+1), '.$sAdd;
				}
			break;
			case 10: //Acreditado
			case 12: //Certificado.
			$sAdd='ofer08av_avance=60, ofer08av_fechatermina='.$iHoy.', ofer08av_horatermina='.$iMinuto.', '.$sAdd;
			break;
			}
		if ($idOrigen==9){
			//Viene de ajustes finales.
			$sAdd='ofer08av_avance=50, ofer08av_fechatermajustes='.$iHoy.', ofer08av_horaterajustes='.$iMinuto.', '.$sAdd;
			}
		$sSQL='UPDATE ofer08oferta SET '.$sAdd.'ofer08fechaestadocampus="'.$ofer30fecha.'" WHERE ofer08id='.$idOferta.'';
		$result=$objDB->ejecutasql($sSQL);
		//Gestionar el proceso de los directores.
		f1730_GestionarDirectores($idOferta, $objDB, $bDebug, true);
		//Armar las notificaciones...
		switch($idDestino){
			case 2: //En alistar.
			case 7: //En evaluación.
			case 8: //En acreditación.
			case 9: //En ajustes finales
			case 10: //Acreditado.
			case 11: //En certificacion.
			case 12: //Certificado.
			case 20: //Para alistar.
			f1730_NotificarEstado($idOferta, $objDB, $bDebug);
			break;
			}
		}
	return $sError;
	}
function f1730_GestionarDirectores($idOferta, $objDB, $bDebug=false, $bConRespositorio=false){
	$sDebug='';
	$ofer38activo='N';
	$ofer38grupo=0;
	$ofer38id=0;
	$ofer38usuario=$_SESSION['unad_id_tercero'];
	$ofer38fechamat=fecha_hoy();
	$ofer38horamat=fecha_hora();
	$ofer38minmat=fecha_minuto();
	$idPeraca=-1;
	$idCurso=-1;
	$sError='';
	$sAlerta='';
	$scampos38='ofer38idoferta, ofer38idtercero, ofer38idrol, ofer38grupo, ofer38origenmatricula, ofer38id, ofer38activo, ofer38detalle, ofer38usuario, ofer38fechamat, ofer38horamat, ofer38minmat';
	//Abril 9 de 2018 - Los perfiles ya no se administran por rol, sino que se hacen por estado y proceso....
	//Limpiarlos a todos...
	$sSQL='UPDATE ofer38matricula SET ofer38activo="N" WHERE ofer38idoferta='.$idOferta.' AND ofer38origenmatricula=17';
	$result=$objDB->ejecutasql($sSQL);
	if ($bDebug){$sDebug=$sDebug.''.fecha_microtiempo().' - MATRICULA ACTORES - Se retiran todos de la oferta  {'.$idOferta.'}.<br>';}
	//Traer el peraca y el curso de la oferta.
	$sSQL='SELECT ofer08idper_aca, ofer08idcurso, ofer08estadooferta, ofer08estadocampus, ofer08obligaacreditar FROM ofer08oferta WHERE ofer08id='.$idOferta.'';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$idPeraca=$fila['ofer08idper_aca'];
		$idCurso=$fila['ofer08idcurso'];
		$sProceso=$fila['ofer08obligaacreditar'];
		$iEstado=$fila['ofer08estadocampus'];
		if ($fila['ofer08estadooferta']!=1){
			$sError='Curso no ofertado... pare de contar.';
			}
		}else{
		$sError='Oferta no encontrada {'.$idOferta.'}';
		}
	if ($sError==''){
		//No accede en certificacion.
		$sCampoRol='0';
		if ($sProceso=='N'){
			//Certificacion
			switch($iEstado){
				case -1:$sCampoRol='T1.ofer10perfilestcer99';break;
				case 1:$sCampoRol='T1.ofer10perfilestcer01';break;
				case 20:
				case 2:$sCampoRol='T1.ofer10perfilestcer02';break;
				case 4:$sCampoRol='T1.ofer10perfilestcer04';break;
				case 9:$sCampoRol='T1.ofer10perfilestcer09';break;
				case 11:$sCampoRol='T1.ofer10perfilest11';break;
				case 12:$sCampoRol='T1.ofer10perfilest12';break;
				case 15:$sCampoRol='T1.ofer10perfilestcer15';break;
				case 19:$sCampoRol='T1.ofer10perfilestcer19';break;
				default:
				$sAlerta='Proceso certificaci&oacute;n, estado '.$iEstado.' no tiene rol a asignar.';
				if ($bDebug){$sDebug=$sDebug.''.fecha_microtiempo().' - MATRICULA ACTORES - Proceso Certificacion, estado '.$iEstado.' no tiene rol a asignar.<br>';}
				break;
				}
			}else{
			//Acreditacion y casos especiales.
			switch($iEstado){
				case -1:$sCampoRol='T1.ofer10perfilest99';break;
				case 1:$sCampoRol='T1.ofer10perfilest01';break;
				case 20:
				case 2:$sCampoRol='T1.ofer10perfilest02';break;
				case 4:$sCampoRol='T1.ofer10perfilest04';break;
				case 7:$sCampoRol='T1.ofer10perfilest07';break;
				case 8:$sCampoRol='T1.ofer10perfilest08';break;
				case 9:$sCampoRol='T1.ofer10perfilest09';break;
				case 10:$sCampoRol='T1.ofer10perfilest10';break;
				case 15:$sCampoRol='T1.ofer10perfilest15';break;
				case 19:$sCampoRol='T1.ofer10perfilest19';break;
				default:
				$sAlerta='Proceso acreditaci&oacute;n, estado '.$iEstado.' no tiene rol a asignar.';
				if ($bDebug){$sDebug=$sDebug.''.fecha_microtiempo().' - MATRICULA ACTORES - Proceso Acreditacion, estado '.$iEstado.' no tiene rol a asignar.<br>';}
				break;
				}
			}
		$iFilas=0;
		$aFila=array();
		$sSQL='SELECT TB.ofer11idrol, TB.ofer11idtercero, '.$sCampoRol.' AS Rol 
FROM ofer11actores AS TB, ofer10rol AS T1 
WHERE ofer11idcurso='.$idCurso.' AND ofer11per_aca='.$idPeraca.' AND TB.ofer11idrol=T1.ofer10id';
		if ($bDebug){$sDebug=$sDebug.''.fecha_microtiempo().' - MATRICULA ACTORES - Roles por tercero {'.$sSQL.'}.<br>';}
		$tabla11=$objDB->ejecutasql($sSQL);
		while ($fila11=$objDB->sf($tabla11)){
			if ($fila11['Rol']!=0){
				$iFilas++;
				$aFila[$iFilas]['idter']=$fila11['ofer11idtercero'];
				$aFila[$iFilas]['rol']=$fila11['Rol'];
				}
			}
		}
	if ($sError==''){
		//hacer la matricula....
		for ($k=1;$k<=$iFilas;$k++){
			//Ver si esta o no.
			$idTercero=$aFila[$k]['idter'];
			$ofer38idrol=$aFila[$k]['rol'];
			$sSQL='SELECT ofer38id FROM ofer38matricula WHERE ofer38idtercero='.$idTercero.' AND ofer38idoferta='.$idOferta.' AND ofer38idrol='.$ofer38idrol.' AND ofer38grupo='.$ofer38grupo.' AND ofer38origenmatricula=17';
			$tabla38=$objDB->ejecutasql($sSQL);
			if ($bDebug){$sDebug=$sDebug.''.fecha_microtiempo().' - MATRICULA DIRECTORES - Consulta de verificacion de rol para {'.$idTercero.'} '.$sSQL.'<br>';}
			if ($objDB->nf($tabla38)==0){
				$ofer38id=tabla_consecutivo('ofer38matricula', 'ofer38id', '', $objDB);
				$svalores=''.$idOferta.', '.$idTercero.', '.$ofer38idrol.', '.$ofer38grupo.', 17, '.$ofer38id.', "S", "MATRICULA DESDE OAI", "'.$ofer38usuario.'", "'.$ofer38fechamat.'", '.$ofer38horamat.', '.$ofer38minmat.'';
				$sSQL='INSERT INTO ofer38matricula ('.$scampos38.') VALUES ('.$svalores.');';
				}else{
				$fila38=$objDB->sf($tabla38);
				$sSQL='UPDATE ofer38matricula SET ofer38activo="S" WHERE ofer38id='.$fila38['ofer38id'].'';
				}
			$result=$objDB->ejecutasql($sSQL);
			if ($bDebug){$sDebug=$sDebug.''.fecha_microtiempo().' - MATRICULA DIRECTORES - Se activado a {'.$idTercero.'} para el rol {'.$ofer38idrol.'}.<br>';}
			}
		if ($bConRespositorio){
			list ($sErrorRep, $sDebugRep)=f1730_CargarCursoARepositorio($idPeraca, $idCurso, $objDB, $bDebug);
			$sDebug=$sDebug.$sDebugRep;
			}
		//Armar la tabla para maria p....
		list($sErrorE, $sDebugE)=f1730_ArmarActores($idOferta, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugE;
		//Termina si encuentra la oferta
		}
	return array($sDebug, $sAlerta);
	}
function f1730_GestionarDirectoresV0($idOferta, $objDB, $bDebug=false, $bConRespositorio=false){
	$sDebug='';
	$ofer38activo='N';
	$ofer38idrol=17;
	$ofer38grupo=0;
	$ofer38id=0;
	$ofer38usuario=$_SESSION['unad_id_tercero'];
	$ofer38fechamat=fecha_hoy();
	$ofer38horamat=fecha_hora();
	$ofer38minmat=fecha_minuto();
	$idPeraca=-1;
	$idCurso=-1;
	$scampos38='ofer38idoferta, ofer38idtercero, ofer38idrol, ofer38grupo, ofer38origenmatricula, ofer38id, ofer38activo, ofer38detalle, ofer38usuario, ofer38fechamat, ofer38horamat, ofer38minmat';
	//Limpiarlos a todos...
	$sSQL='UPDATE ofer38matricula SET ofer38activo="N" WHERE ofer38idoferta='.$idOferta.' AND ofer38idrol IN (3, 9) AND ofer38origenmatricula=17';
	$result=$objDB->ejecutasql($sSQL);
	if ($bDebug){$sDebug=$sDebug.''.fecha_microtiempo().' - MATRICULA DIRECTORES - Se retiran los directores de la oferta sobre el rol '.$ofer38idrol.' {'.$idOferta.'}.<br>';}
	//Traer el peraca y el curso de la oferta.
	$sSQL='SELECT ofer08idper_aca, ofer08idcurso, ofer08estadooferta, ofer08estadocampus FROM ofer08oferta WHERE ofer08id='.$idOferta.'';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$idPeraca=$fila['ofer08idper_aca'];
		$idCurso=$fila['ofer08idcurso'];
		if ($idPeraca==87){
			$bActivaDirectores=true;
			}else{
			$bActivaDirectores=false;
			if ($fila['ofer08idper_aca']==87){$bActivaDirectores=true;}
			if ($fila['ofer08estadooferta']==1){
				switch($fila['ofer08estadocampus']){
					case 20: //Para alistar.
					case 2: //En alistamiento.
					case 9: //En ajustes finales
					$bActivaDirectores=true;
					break;
					}
				}
			}
		if ($bActivaDirectores){
			}else{
			//Septiembre 20 de 2016 - No se retiran sino que se dejan con el rol 3
			//$ofer38idrol=3;
			$ofer38idrol=9; //Septiembre 26 de 2017 lo que se manejaba por rol 3 ahora va por rol 9
			}
		$sSQL='SELECT ofer11idtercero FROM ofer11actores, ofer10rol WHERE ofer11per_aca='.$idPeraca.' AND ofer11idcurso='.$idCurso.' AND ofer11idrol=ofer10id AND ofer10claserol=4';
		//Los directores solo deben estar activos en estado 1 y 9... Esto con relación a la tabla de matricula manual.
		$tabla11=$objDB->ejecutasql($sSQL);
		while ($fila11=$objDB->sf($tabla11)){
			if ($bActivaDirectores){
				//Qitarles el rol 3 para que no queden repetidos.
				//Septiembre 26 de 2017 ya no es el rol 3 sino el 9 (Pero se le retiran ambos).
				$sSQL='UPDATE ofer38matricula SET ofer38activo="N" WHERE ofer38idtercero='.$fila11['ofer11idtercero'].' AND ofer38idoferta='.$idOferta.' AND ofer38idrol IN (3, 9) AND ofer38origenmatricula=17';
				$result=$objDB->ejecutasql($sSQL);
				}
			//Ver si esta o no.
			$sSQL='SELECT ofer38id FROM ofer38matricula WHERE ofer38idtercero='.$fila11['ofer11idtercero'].' AND ofer38idoferta='.$idOferta.' AND ofer38idrol='.$ofer38idrol.' AND ofer38grupo='.$ofer38grupo.' AND ofer38origenmatricula=17';
			$tabla38=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla38)==0){
				$ofer38id=tabla_consecutivo('ofer38matricula', 'ofer38id', '', $objDB);
				$svalores=''.$idOferta.', '.$fila11['ofer11idtercero'].', '.$ofer38idrol.', '.$ofer38grupo.', 17, '.$ofer38id.', "S", "MATRICULA DESDE OAI", "'.$ofer38usuario.'", "'.$ofer38fechamat.'", '.$ofer38horamat.', '.$ofer38minmat.'';
				$sSQL='INSERT INTO ofer38matricula ('.$scampos38.') VALUES ('.$svalores.');';
				}else{
				$fila38=$objDB->sf($tabla38);
				$sSQL='UPDATE ofer38matricula SET ofer38activo="S" WHERE ofer38id='.$fila38['ofer38id'].'';
				}
			$result=$objDB->ejecutasql($sSQL);
			if ($bDebug){$sDebug=$sDebug.''.fecha_microtiempo().' - MATRICULA DIRECTORES - Se activado a {'.$fila11['ofer11idtercero'].'}.<br>';}
			}
		//Ahora los evaluadores.
		list($sDebugE)=f1730_GestionarEvaluadores($idOferta, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugE;
		if ($bConRespositorio){
			list ($sErrorRep, $sDebugRep)=f1730_CargarCursoARepositorio($idPeraca, $idCurso, $objDB, $bDebug);
			$sDebug=$sDebug.$sDebugRep;
			}
		//Termina si encuentra la oferta
		}
	return array($sDebug);
	}
function f1730_GestionarEvaluadores($idOferta, $objDB, $bDebug=false){
	$sDebug='';
	$ofer38activo='N';
	$ofer38idrol=11;
	$ofer38grupo=0;
	$ofer38id=0;
	$ofer38usuario=$_SESSION['unad_id_tercero'];
	$ofer38fechamat=fecha_hoy();
	$ofer38horamat=fecha_hora();
	$ofer38minmat=fecha_minuto();
	$idPeraca=-1;
	$idCurso=-1;
	$scampos38='ofer38idoferta, ofer38idtercero, ofer38idrol, ofer38grupo, ofer38origenmatricula, ofer38id, ofer38activo, ofer38detalle, ofer38usuario, ofer38fechamat, ofer38horamat, ofer38minmat';
	//Limpiarlos a todos...
	$sSQL='UPDATE ofer38matricula SET ofer38activo="N" WHERE ofer38idoferta='.$idOferta.' AND ofer38idrol=11 AND ofer38origenmatricula=17';
	$result=$objDB->ejecutasql($sSQL);
	//Traer el peraca y el curso de la oferta.
	$sSQL='SELECT ofer08idper_aca, ofer08idcurso, ofer08estadooferta, ofer08estadocampus FROM ofer08oferta WHERE ofer08id='.$idOferta.'';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$idPeraca=$fila['ofer08idper_aca'];
		$idCurso=$fila['ofer08idcurso'];
		/*
		if ($idPeraca==87){
			$bActivaDirectores=true;
			}else{
			$bActivaDirectores=false;
			if ($fila['ofer08idper_aca']==87){$bActivaDirectores=true;}
			if ($fila['ofer08estadooferta']==1){
				switch($fila['ofer08estadocampus']){
					case 20: //Para alistar.
					case 2: //En alistamiento.
					case 9: //En ajustes finales
					$bActivaDirectores=true;
					break;
					}
				}
			}
		if ($bActivaDirectores){
			}else{
			//Septiembre 20 de 2016 - No se retiran sino que se dejan con el rol 3
			//$ofer38idrol=9;
			}
		*/
		$sSQL='SELECT ofer11idtercero FROM ofer11actores, ofer10rol WHERE ofer11per_aca='.$idPeraca.' AND ofer11idcurso='.$idCurso.' AND ofer11idrol=ofer10id AND ofer10claserol=5';
		//Los evaluadores solo deben estar activos en estado 1 y 9... Esto con relación a la tabla de matricula manual.
		$tabla11=$objDB->ejecutasql($sSQL);
		while ($fila11=$objDB->sf($tabla11)){
			//Qitarles el rol 3, 9 y 10 para que no queden repetidos.
			$sSQL='UPDATE ofer38matricula SET ofer38activo="N" WHERE ofer38idtercero='.$fila11['ofer11idtercero'].' AND ofer38idoferta='.$idOferta.' AND ofer38idrol IN (3, 9, 10) AND ofer38origenmatricula=17';
			$result=$objDB->ejecutasql($sSQL);
			//Ver si esta o no.
			$sSQL='SELECT ofer38id FROM ofer38matricula WHERE ofer38idtercero='.$fila11['ofer11idtercero'].' AND ofer38idoferta='.$idOferta.' AND ofer38idrol='.$ofer38idrol.' AND ofer38grupo='.$ofer38grupo.' AND ofer38origenmatricula=17';
			$tabla38=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla38)==0){
				$ofer38id=tabla_consecutivo('ofer38matricula', 'ofer38id', '', $objDB);
				$svalores=''.$idOferta.', '.$fila11['ofer11idtercero'].', '.$ofer38idrol.', '.$ofer38grupo.', 17, '.$ofer38id.', "S", "MATRICULA DESDE OAI - EVALUADORES", "'.$ofer38usuario.'", "'.$ofer38fechamat.'", '.$ofer38horamat.', '.$ofer38minmat.'';
				$sSQL='INSERT INTO ofer38matricula ('.$scampos38.') VALUES ('.$svalores.');';
				}else{
				$fila38=$objDB->sf($tabla38);
				$sSQL='UPDATE ofer38matricula SET ofer38activo="S" WHERE ofer38id='.$fila38['ofer38id'].'';
				}
			$result=$objDB->ejecutasql($sSQL);
			if ($bDebug){$sDebug=$sDebug.''.fecha_microtiempo().' - MATRICULA EVALUADORES - Se activado a {'.$fila11['ofer11idtercero'].'}.<br>';}
			}
		//Termina si encuentra la oferta
		}
	return array($sDebug);
	}
function f1730_CargarCursoARepositorio($idPeraca, $idCurso, $objDB, $bDebug=false){
	$sDebug='';
	$sError='';
	$bEntra=false;
	$sSQL='SELECT unad40id, unad40nombre 
FROM unad40curso 
WHERE unad40id='.$idCurso.'';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		if (class_exists('nusoap_client')){
			$bEntra=true;
			}else{
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' <b>NO SE HA CARGADO LA CLASE NUSOAP</b><br>';}
			}
		}
	if ($bEntra){
		$iVrDebug=0;
		//$sURL='http://stadium.unad.edu.co/stadium/w_s/Web_Service_UNAD.php';
		//$sURL='http://192.168.1.60/w_s/Web_Service_UNAD2.php';
		//$sURL='http://192.168.1.60/w_s/Web_Service_UNAD_produccion.php';
		//$sURL='http://66.165.175.241/w_s/Web_Service_UNAD_produccion.php'; //Esta quedo hasta julio 11 de 2017
		$sURL='http://129.191.25.108/w_s/Web_Service_UNAD_produccion.php';
		$cliente = new nusoap_client($sURL,false);
		if ($bDebug){
			$sDebug=$sDebug.fecha_microtiempo().' Enviando Curso '.$fila['unad40id'].' al repositirio ['.$sURL.']<br>';
			$iVrDebug=1;
			}
		$Parametros_Crear_Curso_para_WebService = array ('Codigo_Curso' =>$fila['unad40id'], 'Nombre_Curso'=>$fila['unad40nombre'] );
		$respuesta_WebService = $cliente->call('Creacion_y_Validacion_Curso_en_Repositorio',$Parametros_Crear_Curso_para_WebService);
		//Primero sacar los roles de director.
		$ids10='-99';
		$sSQL='SELECT ofer10id FROM ofer10rol WHERE ofer10claserol IN (4)';
		$tabla=$objDB->ejecutasql($sSQL);
		while ($fila=$objDB->sf($tabla)){
			$ids10=$ids10.','.$fila['ofer10id'];
			}
		$idRol=1;
		$sSQL='SELECT TB.ofer11idcurso, T11.unad11doc, T11.unad11razonsocial, T11.unad11correoinstitucional, T11.unad11correofuncionario
FROM ofer11actores AS TB, unad11terceros AS T11 
WHERE TB.ofer11per_aca='.$idPeraca.' AND TB.ofer11idcurso='.$idCurso.' AND TB.ofer11idrol IN ('.$ids10.') AND 
TB.ofer11idtercero=T11.unad11id ';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($tabla==false){
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().'Falla sql: '.$sSQL.'<br>';}
			}
		while ($fila=$objDB->sf($tabla)){
			$sCorreo='';
			if (correo_VerificarDireccion(trim($fila['unad11correofuncionario']))){
				$sCorreo=trim($fila['unad11correofuncionario']);
				}else{
				if (correo_VerificarDireccion(trim($fila['unad11correoinstitucional']))){
					$sCorreo=trim($fila['unad11correoinstitucional']);
					}
				}
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Enviando actor al repositirio Curso '.$fila['ofer11idcurso'].' Documento '.$fila['unad11doc'].'<br>';}
			$Parametros_Aginar_Usuario_al_Curso = array ('Codigo_Curso' =>$fila['ofer11idcurso'], 
											'Documento_Usuario' => $fila['unad11doc'],
											'Nombre_Usuario' => $fila['unad11razonsocial'], 
											'Correo_Usuario' => $sCorreo,
											'Rol_Usuario' => $idRol,
											'iDebug' =>$iVrDebug);
			$respuesta_WebService=$cliente->call('Asignar_Usuario_al_Curso',$Parametros_Aginar_Usuario_al_Curso);
			if ($cliente->fault) {
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Fallo la consulta al web service '.$cliente->getError().'<br>';}
				} else {
				$err=$cliente->getError();
				if ($err) {
					if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Error al consultar el web service '.$err.'<br>';}
					}else{
					if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Respuesta desde el web service '.$respuesta_WebService.'<br>';}
					}
				}
			}
		//Ahora los roles de acreditador.
		$ids10='-99';
		$sSQL='SELECT ofer10id FROM ofer10rol WHERE ofer10claserol IN (8)';
		$tabla=$objDB->ejecutasql($sSQL);
		while ($fila=$objDB->sf($tabla)){
			$ids10=$ids10.','.$fila['ofer10id'];
			}
		$idRol=3;
		$sSQL='SELECT TB.ofer11idcurso, T11.unad11doc, T11.unad11razonsocial, T11.unad11correoinstitucional, T11.unad11correofuncionario
FROM ofer11actores AS TB, unad11terceros AS T11 
WHERE TB.ofer11per_aca='.$idPeraca.' AND TB.ofer11idcurso='.$idCurso.' AND TB.ofer11idrol IN ('.$ids10.') AND 
TB.ofer11idtercero=T11.unad11id ';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($tabla==false){
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().'Falla sql: '.$sSQL.'<br>';}
			}
		while ($fila=$objDB->sf($tabla)){
			$sCorreo='';
			if (correo_VerificarDireccion(trim($fila['unad11correofuncionario']))){
				$sCorreo=trim($fila['unad11correofuncionario']);
				}else{
				if (correo_VerificarDireccion(trim($fila['unad11correoinstitucional']))){
					$sCorreo=trim($fila['unad11correoinstitucional']);
					}
				}
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Enviando actor al repositirio Curso '.$fila['ofer11idcurso'].' Documento '.$fila['unad11doc'].'<br>';}
			$Parametros_Aginar_Usuario_al_Curso = array ('Codigo_Curso' =>$fila['ofer11idcurso'], 
											'Documento_Usuario' => $fila['unad11doc'],
											'Nombre_Usuario' => $fila['unad11razonsocial'], 
											'Correo_Usuario' => $sCorreo,
											'Rol_Usuario' => $idRol,
											'iDebug' =>$iVrDebug);
			$respuesta_WebService=$cliente->call('Asignar_Usuario_al_Curso',$Parametros_Aginar_Usuario_al_Curso);
			if ($cliente->fault) {
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Fallo la consulta al web service '.$cliente->getError().'<br>';}
				} else {
				$err=$cliente->getError();
				if ($err) {
					if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Error al consultar el web service '.$err.'<br>';}
					}else{
					if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Respuesta desde el web service '.$respuesta_WebService.'<br>';}
					}
				}
			}
		//Ahora los demas
		$ids10='-99';
		//$sSQL='SELECT ofer10id FROM ofer10rol WHERE ofer10claserol NOT IN (4, 8)';
		$sSQL='SELECT ofer10id FROM ofer10rol WHERE ofer10claserol IN (5)';
		$tabla=$objDB->ejecutasql($sSQL);
		while ($fila=$objDB->sf($tabla)){
			$ids10=$ids10.','.$fila['ofer10id'];
			}
		$idRol=2;
		$sSQL='SELECT TB.ofer11idcurso, T11.unad11doc, T11.unad11razonsocial, T11.unad11correoinstitucional, T11.unad11correofuncionario
FROM ofer11actores AS TB, unad11terceros AS T11 
WHERE TB.ofer11per_aca='.$idPeraca.' AND TB.ofer11idcurso='.$idCurso.' AND TB.ofer11idrol IN ('.$ids10.') AND 
TB.ofer11idtercero=T11.unad11id ';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($tabla==false){
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().'Falla sql: '.$sSQL.'<br>';}
			}
		while ($fila=$objDB->sf($tabla)){
			$sCorreo='';
			if (correo_VerificarDireccion(trim($fila['unad11correofuncionario']))){
				$sCorreo=trim($fila['unad11correofuncionario']);
				}else{
				if (correo_VerificarDireccion(trim($fila['unad11correoinstitucional']))){
					$sCorreo=trim($fila['unad11correoinstitucional']);
					}
				}
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Enviando actor al repositorio Curso '.$fila['ofer11idcurso'].' Documento '.$fila['unad11doc'].'<br>';}
			$Parametros_Aginar_Usuario_al_Curso = array ('Codigo_Curso' =>$fila['ofer11idcurso'], 
											'Documento_Usuario' => $fila['unad11doc'],
											'Nombre_Usuario' => $fila['unad11razonsocial'], 
											'Correo_Usuario' => $sCorreo,
											'Rol_Usuario' => $idRol,
											'iDebug' =>$iVrDebug);
			$respuesta_WebService=$cliente->call('Asignar_Usuario_al_Curso',$Parametros_Aginar_Usuario_al_Curso);
			if ($cliente->fault) {
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Fallo la consulta al web service '.$cliente->getError().'<br>';}
				} else {
				$err=$cliente->getError();
				if ($err) {
					if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Error al consultar el web service '.$err.'<br>';}
					}else{
					if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Respuesta desde el web service '.$respuesta_WebService.'<br>';}
					}
				}
			}
		}
	return array($sError, $sDebug);
	}
function f1730_NotificarEstado($idOferta, $objDB, $bDebug=false){
	mb_internal_encoding('UTF-8');
	require './app.php';
	$sDebug='';
	if ($bDebug){$sDebug=$sDebug.'Iniciando notificaciones para id='.$idOferta.'<br>';}
	$bNotifica=false;
	$idDestino=0;
	$sSQL='SELECT ofer08estadocampus FROM ofer08oferta  WHERE ofer08id='.$idOferta.'';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$idDestino=$fila['ofer08estadocampus'];
		}
	$idRol=0;
	$sAsunto='';
	$sNomEstado='';
	$sSuperior='';
	$sInferior='';
	switch($idDestino){
		case 20: //Para alistar. -- OJO AQUI TENIA EL 1... 
		$bNotifica=true;
		$idRol=4; //Director de curso.
		$sNomEstado='Para Alistar';
		break;
		case 2: //En alistar.
		$bNotifica=true;
		$idRol=4; //Director de curso.
		$sNomEstado='En Alistamiento';
		break;
		case 7: //En evaluación.
		$bNotifica=true;
		$idRol=5; //Evaluador.
		$sNomEstado='En Evaluación';
		break;
		case 8: //En acreditación.
		$bNotifica=true;
		$idRol=8; //Acreditador.
		$sNomEstado='En Acreditación';
		break;
		case 11: //En certificacion.
		$bNotifica=true;
		$idRol=4; //Director de curso.
		$idRol=10; //Certificador.
		$sNomEstado='En Certificación';
		break;
		case 9: //Ajustes finales
		$bNotifica=true;
		$idRol=4; //Director de curso.
		$sNomEstado='En Ajustes Finales';
		break;
		case 10: //Acreditado
		$bNotifica=true;
		$idRol=4; //Director de curso.
		$sNomEstado='Acreditado';
		break;
		case 12: //Certificado
		$bNotifica=true;
		$idRol=4; //Director de curso.
		$sNomEstado='Certificado';
		break;
		default:
		if ($bDebug){$sDebug=$sDebug.'El estado del curso no es para notificar<br>';}
		break;
		}
	if ($bNotifica){
		//Septiembre 25 de 2016 -- Debemos incluir el cuerpo del mensaje en forma personalizada...
		$sCuerpoMensaje='El presente mensaje es para informarle que el curso |@CodCurso| en el periodo |@NomPeriodo| paso al estado '.cadena_notildes($sNomEstado).'<br>Por tanto el proceso requiere su atenci&oacute;n.';
		$sRoles='-99';
		$bHayRoles=false;
		//Saber si hay un mensaje personalizado.
		$sSQL='SELECT TB.ofer08idper_aca, T2.exte02nombre, TB.ofer08idcurso, T40.unad40nombre, TB.ofer08obligaacreditar 
FROM ofer08oferta AS TB, exte02per_aca AS T2, unad40curso AS T40  
WHERE TB.ofer08id='.$idOferta.' AND TB.ofer08idper_aca=T2.exte02id AND TB.ofer08idcurso=T40.unad40id';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$idPeraca=$fila['ofer08idper_aca'];
			$idCurso=$fila['ofer08idcurso'];
			$sNomCurso=cadena_notildes($fila['unad40nombre']);
			$sNomPeraca=cadena_notildes($fila['exte02nombre']);
			switch($fila['ofer08obligaacreditar']){
				case 'S':
				$sNomProceso='Acreditaci&oacute;n';
				break;
				case 'N':
				$sNomProceso='Certificaci&oacute;n';
				break;
				default:
				$sNomProceso='Casos especiales';
				break;
				}
			}else{
			if ($bDebug){$sDebug=$sDebug.'Fallo al encontrar el curso {'.$sSQL.'}<br>';}
			$bNotifica=false;
			}
		$sSQL='SELECT ofer39mensaje, ofer39id FROM ofer39mensaje WHERE ofer39per_aca IN (0, '.$idPeraca.') AND ofer39idestadocampus='.$idDestino.' ORDER BY ofer39per_aca DESC';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$sCuerpoMensaje=cadena_notildes($fila['ofer39mensaje']);
			$sSQL='SELECT ofer40idrol FROM ofer40mensajeactor WHERE ofer40idmensaje='.$fila['ofer39id'].' AND ofer40activo="S"';
			$tabla=$objDB->ejecutasql($sSQL);
			while($fila=$objDB->sf($tabla)){
				$sRoles=$sRoles.','.$fila['ofer40idrol'];
				$bHayRoles=true;
				}
			}
		if (!$bHayRoles){
			$sSQL='SELECT ofer10id FROM ofer10rol WHERE ofer10claserol='.$idRol;
			$tabla=$objDB->ejecutasql($sSQL);
			while ($fila=$objDB->sf($tabla)){
				$sRoles=$sRoles.','.$fila['ofer10id'];
				}
			if ($bDebug){$sDebug=$sDebug.'Roles cargados {'.$sRoles.'} '.$sSQL.'<br>';}
			}
		}
	if ($bNotifica){
		//Traer los nombres de los actores asignados.
		$sNomDirector='{Sin director}';
		$sNomAcreditador=' '; //{Sin acreditador}
		$sNomCertificador=' '; //{Sin certificador}
		$sNomEvaluador='{Sin evaluador}';
		$sSQL='SELECT TB.ofer11idtercero, T1.unad11razonsocial, TB.ofer11idrol 
FROM ofer11actores AS TB, unad11terceros AS T1 
WHERE TB.ofer11idcurso='.$idCurso.' AND TB.ofer11per_aca='.$idPeraca.' AND TB.ofer11idrol IN (2, 5, 6, 10) AND TB.ofer11idtercero=T1.unad11id ';
		$tabla=$objDB->ejecutasql($sSQL);
		while ($fila=$objDB->sf($tabla)){
			switch($fila['ofer11idrol']){
				case 2: // Director.
				$sNomDirector=cadena_notildes(strtoupper($fila['unad11razonsocial']));
				break;
				case 6: // Acreditador
				$sNomAcreditador=cadena_notildes(strtoupper($fila['unad11razonsocial']));
				break;
				case 10: // Cerficador
				$sNomCertificador=cadena_notildes(strtoupper($fila['unad11razonsocial']));
				break;
				case 5: // Evaluador
				$sNomEvaluador=cadena_notildes(strtoupper($fila['unad11razonsocial']));
				break;
				}
			}
		}
	if ($bNotifica){
		//Armar el asunto, debemos traer la oferta.
		$sAsunto=cadena_Reemplazar($sCuerpoMensaje, '|@CodCurso|', $idCurso);
		$sAsunto=cadena_Reemplazar($sAsunto, '|@NomEstado|', cadena_notildes($sNomEstado));
		$sAsunto=cadena_Reemplazar($sAsunto, '|@NomPeriodo|', $sNomPeraca);
		$sAsunto=cadena_Reemplazar($sAsunto, '|@NomCurso|', $sNomCurso);
		$sAsunto=cadena_Reemplazar($sAsunto, '|@NomProceso|', $sNomProceso);
		$sAsunto=cadena_Reemplazar($sAsunto, '|@NomDirector|', $sNomDirector);
		$sAsunto=cadena_Reemplazar($sAsunto, '|@NomAcreditador|', $sNomAcreditador);
		$sAsunto=cadena_Reemplazar($sAsunto, '|@NomCertificador|', $sNomCertificador);
		$sAsunto=cadena_Reemplazar($sAsunto, '|@NomEvaluador|', $sNomEvaluador);
		$sSuperior='<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr>
<td align="center"><img src="http://congresos.unadvirtual.org/apps/logo_unad.png" alt="Universidad Nacional Abierta y a Distancia - UNAD"/></td>
<td><h1 align="center">Oferta Acad&eacute;mica Integrada</h1>
<h2 align="center"><br />Notificaci&oacute;n de cambio de estado</h2></td>
</tr>
</table>
<div align="center"><hr size="2" width="100%"/></div>
<p><em><b>'.formato_fechalarga(fecha_hoy()).' '.html_TablaHoraMin(fecha_hora(),fecha_minuto()).'</b></em><br />
<br />';
		$sInferior='<br />
Atentamente, <br />
<b>VIMEP<br /><br />
<br />
<em>Este es un correo autom&aacute;tico del sistema de Oferta Acad&eacute;mica Integrada de la PTI. Por favor  no responder a este correo.</em><br />
</p>
<div align="center"><hr size="2" width="100%"/></div>
<p><b>Calle 14 Sur  No. 14-23<br />
PBX: 344 37 00<br />
Bogot&aacute;, Colombia<br />
</p>';
		if ($bDebug){$sDebug=$sDebug.'Se ha armado el asunto<br>';}
		}
	if ($sAsunto==''){$bNotifica=false;}
	if ($bNotifica){
		//Traer el SMTP
		$idSMTP=0;
		$sSQL='SELECT ofer01idsmtp FROM ofer01params WHERE ofer01id=1';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$idSMTP=$fila['ofer01idsmtp'];
			if ($bDebug){$sDebug=$sDebug.'Se ha cargado el SMTP '.$idSMTP.'<br>';}
			}
		if ($idSMTP==0){
			$bNotifica=false;
			if ($bDebug){$sDebug=$sDebug.'No hay un SMTP<br>';}
			}
		}
	if ($bNotifica){
		$sTituloMensaje='Notificación de cambio de estado en el curso '.$idCurso.' periodo '.$sNomPeraca.'';
		$objMail=new clsMail_Unad($objDB);
		//Traer a los detinatarios.
		$sSQL='SELECT TB.ofer11idtercero, T1.unad11razonsocial, T1.unad11aceptanotificacion, T1.unad11correonotifica, T1.unad11correoinstitucional, T1.unad11correo, T2.ofer10nombre 
FROM ofer11actores AS TB, unad11terceros AS T1, ofer10rol AS T2 
WHERE TB.ofer11idcurso='.$idCurso.' AND TB.ofer11per_aca='.$idPeraca.' AND TB.ofer11idrol IN ('.$sRoles.') AND TB.ofer11idtercero=T1.unad11id AND TB.ofer11idrol=T2.ofer10id';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)==0){
			if ($bDebug){$sDebug=$sDebug.'No se han encontrado actores <br>'.$sSQL.'<br>';}
			}
		while ($fila=$objDB->sf($tabla)){
			$sMail=trim($fila['unad11correoinstitucional']);
			$sMail3='';
			$sMail2='';
			if ($bDebug){$sMail3='soporte.campus@unad.edu.co';}
			if ($fila['unad11aceptanotificacion']=='S'){
				if (correo_VerificarDireccion($fila['unad11correonotifica'])){
					$sMail2=$fila['unad11correonotifica'];
					}
				}
			if ($sMail==''){
				if (correo_VerificarDireccion($fila['unad11correo'])){
					$sMail=$fila['unad11correo'];
					}
				}
			if ($bDebug){$sDebug=$sDebug.'Mail de destino '.$sMail.', Parametros:'.$fila['unad11aceptanotificacion'].' - '.$fila['unad11correonotifica'].' - '.$fila['unad11correo'].' - '.trim($fila['unad11correoinstitucional']).'<br>';}
			if ($sMail!=''){
				$objMail->NuevoMensaje();
				//$sAsuntoFinal=cadena_Reemplazar($sAsunto, '|@NomActor|', $sNomCurso);
				//Se&ntilde;or (a)
				$sCuerpo='Docente<br />
<b>'.cadena_notildes(strtoupper($fila['unad11razonsocial'])).'</b> <br/>
'.cadena_notildes($fila['ofer10nombre']).'<br />
<br />'.$sAsunto;
				$objMail->TraerSMTP($idSMTP);
				if ($sMail!=''){$objMail->addCorreo($sMail);}
				if ($sMail2!=''){$objMail->addCorreo($sMail2);}
				if ($sMail3!=''){$objMail->addCorreo($sMail3);}
				$objMail->sAsunto=$sTituloMensaje;
				$objMail->sCuerpo=$sSuperior.$sCuerpo.$sInferior;
				$sError=$objMail->Enviar();
				if ($bDebug){
					if ($sError==''){
						$sDebug=$sDebug.'Se ha enviado el correo a '.$sMail.'<br>';
						}else{
						$sDebug=$sDebug.'Respuesta al enviar correo a '.$sMail.' {'.$sError.'}<br>';
						}
					}
				}
			//Fin de si hay a quien enviarle el correo...
			}
		//Fin de enviar el correo a un actor.
		}
	return $sDebug;
	}
// -- Funciones base.
function f1730_db_Guardar($valores, $objDB){
	$icodmodulo=1730;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1730='lg/lg_1730_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1730)){$mensajes_1730='lg/lg_1730_es.php';}
	require $mensajes_todas;
	require $mensajes_1730;
	$sError='';
	$binserta=false;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$ofer30idoferta=numeros_validar($valores[1]);
	$ofer30consec=numeros_validar($valores[2]);
	$ofer30id=numeros_validar($valores[3], true);
	$ofer30fecha=$valores[4];
	$ofer30hora=numeros_validar($valores[5]);
	$ofer30minuto=numeros_validar($valores[6]);
	$ofer30idactor=numeros_validar($valores[7]);
	$ofer30estadoorigen=numeros_validar($valores[8]);
	$ofer30estadodestino=numeros_validar($valores[9]);
	$ofer30anotacion=htmlspecialchars($valores[10]);
	//if ($ofer30hora==''){$ofer30hora=0;}
	//if ($ofer30minuto==''){$ofer30minuto=0;}
	//if ($ofer30estadoorigen==''){$ofer30estadoorigen=0;}
	//if ($ofer30estadodestino==''){$ofer30estadodestino=0;}
	if ($ofer30anotacion==''){$sError=$ERR['ofer30anotacion'];}
	if ($ofer30estadodestino==''){$sError=$ERR['ofer30estadodestino'];}
	if ($ofer30estadoorigen==''){$sError=$ERR['ofer30estadoorigen'];}
	if ($ofer30idactor==0){$sError=$ERR['ofer30idactor'];}
	if ($ofer30minuto==''){$sError=$ERR['ofer30minuto'];}
	if ($ofer30hora==''){$sError=$ERR['ofer30hora'];}
	if (!fecha_esvalida($ofer30fecha)){
		//$ofer30fecha='00/00/0000';
		$sError=$ERR['ofer30fecha'];
		}
	//if ($ofer30id==''){$sError=$ERR['ofer30id'];}//CONSECUTIVO
	//if ($ofer30consec==''){$sError=$ERR['ofer30consec'];}//CONSECUTIVO
	if ($ofer30idoferta==''){$sError=$ERR['ofer30idoferta'];}
	if ($sError==''){
		if ((int)$ofer30id==0){
			if ((int)$ofer30consec==0){
				$ofer30consec=tabla_consecutivo('ofer30ofertahistestcampus', 'ofer30consec', 'ofer30idoferta='.$ofer30idoferta.'', $objDB);
				if ($ofer30consec==-1){$sError=$objDB->serror;}
				}
			$sSQL='SELECT ofer30idoferta FROM ofer30ofertahistestcampus WHERE ofer30idoferta='.$ofer30idoferta.' AND ofer30consec='.$ofer30consec.'';
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)!=0){
				$sError=$ERR['existe'];
				}else{
				if (!seg_revisa_permiso($icodmodulo, 2, $objDB)){$sError=$ERR['2'];}
				}
			if ($sError==''){
				$ofer30id=tabla_consecutivo('ofer30ofertahistestcampus', 'ofer30id', '', $objDB);
				if ($ofer30id==-1){$sError=$objDB->serror;}
				$binserta=true;
				}
			}else{
			if (!seg_revisa_permiso($icodmodulo, 3, $objDB)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		//Si el campo ofer30anotacion permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$ofer30anotacion=str_replace('"', '\"', $ofer30anotacion);
		$ofer30anotacion=str_replace('&quot;', '\"', $ofer30anotacion);
		if ($binserta){
			$scampos='ofer30idoferta, ofer30consec, ofer30id, ofer30fecha, ofer30hora, ofer30minuto, ofer30idactor, ofer30estadoorigen, ofer30estadodestino, ofer30anotacion';
			$svalores=''.$ofer30idoferta.', '.$ofer30consec.', '.$ofer30id.', "'.$ofer30fecha.'", '.$ofer30hora.', '.$ofer30minuto.', "'.$ofer30idactor.'", '.$ofer30estadoorigen.', '.$ofer30estadodestino.', "'.$ofer30anotacion.'"';
			$sSQL='INSERT INTO ofer30ofertahistestcampus ('.$scampos.') VALUES ('.$svalores.');';
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError='Error critico al tratar de guardar Cambios de situacion, por favor informe al administrador del sistema.<!-- '.$sSQL.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], 2, 0, $sSQL, $objDB);
					}
				}
			}else{
			$scampo1730[1]='ofer30fecha';
			$scampo1730[2]='ofer30hora';
			$scampo1730[3]='ofer30minuto';
			$scampo1730[4]='ofer30idactor';
			$scampo1730[5]='ofer30estadoorigen';
			$scampo1730[6]='ofer30estadodestino';
			$scampo1730[7]='ofer30anotacion';
			$svr1730[1]=$ofer30fecha;
			$svr1730[2]=$ofer30hora;
			$svr1730[3]=$ofer30minuto;
			$svr1730[4]=$ofer30idactor;
			$svr1730[5]=$ofer30estadoorigen;
			$svr1730[6]=$ofer30estadodestino;
			$svr1730[7]=$ofer30anotacion;
			$inumcampos=7;
			$sWhere='ofer30idoferta='.$ofer30idoferta.' AND ofer30consec='.$ofer30consec.'';
			$sSQL='SELECT * FROM ofer30ofertahistestcampus WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filaorigen=$objDB->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo1730[$k]]!=$svr1730[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo1730[$k].'="'.$svr1730[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				$sSQL='UPDATE ofer30ofertahistestcampus SET '.$sdatos.' WHERE '.$sWhere.';';
				$result=$objDB->ejecutasql($sSQL);
				if ($result==false){
					$sError='Error critico al tratar de guardar Cambios de situacion, por favor informe al administrador del sistema.<!-- '.$sSQL.' -->';
					}else{
					if ($bAudita[3]){
						seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], 3, 0, $sSQL, $objDB);
						}
					}
				}
			}
		}
	return array($sError);
	}
function f1730_db_Eliminar($params, $objDB){
	$icodmodulo=1730;
	$bAudita[4]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1730='lg/lg_1730_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1730)){$mensajes_1730='lg/lg_1730_es.php';}
	require $mensajes_todas;
	require $mensajes_1730;
	$sError='';
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$ofer30idoferta=numeros_validar($params[1]);
	$ofer30consec=numeros_validar($params[2]);
/*	if (!comprobacion){
		$sError='No se puede eliminar';//EXPLICAR LA RAZON
		}*/
	if ($sError==''){
		if (!seg_revisa_permiso($icodmodulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		//acciones previas
		$sWhere='ofer30idoferta='.$ofer30idoferta.' AND ofer30consec='.$ofer30consec.'';
		$sSQL='DELETE FROM ofer30ofertahistestcampus WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError='Error critico al tratar de eliminar Cambios de situacion, por favor informe al administrador del sistema.<!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], 4, 0, $sSQL, $objDB);
				}
			}
		}
	return $sError;
	}
function f1730_TablaDetalle($params, $objDB){
	$mensajes_1730='lg/lg_1730_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1730)){$mensajes_1730='lg/lg_1730_es.php';}
	require $mensajes_1730;
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$ofer08id=$params[0];
	$pagina=$params[101];
	$lineastabla=$params[102];
	$babierta=true;
	$sSQLadd='';
	//if (isset($params[103])==0){$params[103]='';}
	//if ((int)$params[103]!=-1){$sSQLadd=$sSQLadd.' AND TB.campo='.$params[103];}
	//if ($params[103]!=''){$sSQLadd=$sSQLadd.' AND TB.campo2 LIKE "%'.$params[103].'%"';}
	$sSQL='SELECT TB.ofer30idoferta, TB.ofer30consec, TB.ofer30id, TB.ofer30fecha, TB.ofer30hora, TB.ofer30minuto, T7.unad11razonsocial AS C7_nombre, T8.ofer15nombre AS Previo, T8.ofer15tono AS TonoPrevio, T9.ofer15nombre AS Post, T9.ofer15tono AS TonoPost, TB.ofer30anotacion, TB.ofer30idactor, T7.unad11tipodoc AS C7_td, T7.unad11doc AS C7_doc, TB.ofer30estadoorigen, TB.ofer30estadodestino, T7.unad11telefono, T7.unad11correo 
FROM ofer30ofertahistestcampus AS TB, unad11terceros AS T7, ofer15estadocampus AS T8, ofer15estadocampus AS T9 
WHERE TB.ofer30idoferta='.$ofer08id.' AND TB.ofer30idactor=T7.unad11id AND TB.ofer30estadoorigen=T8.ofer15id AND TB.ofer30estadodestino=T9.ofer15id '.$sSQLadd.' 
ORDER BY TB.ofer30consec DESC';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	$sErrConsulta='';
	if ($tabladetalle==false){
		$sErrConsulta='..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		}
	$registros=$objDB->nf($tabladetalle);
	if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
	if ($registros>$lineastabla){
		$rbase=($pagina-1)*$lineastabla;
		$limite=' LIMIT '.$rbase.', '.$lineastabla;
		$tabladetalle=$objDB->ejecutasql($sSQL.$limite);
		}
	$res=$sErrConsulta.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr class="fondoazul">
<td colspan="2"><b>'.$ETI['ofer30fecha'].'</b></td>
<td colspan="2"><b>'.$ETI['ofer30idactor'].'</b></td>
<td><b>'.$ETI['ofer30estadoorigen'].'</b></td>
<td><b>'.$ETI['ofer30estadodestino'].'</b></td>
<td align="right">
'.html_paginador("paginaf1730", $registros, $lineastabla, $pagina, "paginarf1730()").'
'.html_lpp("lppf1730", $lineastabla, "paginarf1730()").'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sprefijo='';
		$ssufijo='';
		$sClass='';
		$sLink='';
		if (false){
			$sprefijo='<b>';
			$ssufijo='</b>';
			}
		if(($tlinea%2)==0){$sClass=' class="resaltetabla"';}
		$tlinea++;
		$et_ofer30fecha='';
		if ($filadet['ofer30fecha']!='00/00/0000'){$et_ofer30fecha=$filadet['ofer30fecha'];}
		if (false){
			$sLink='<a href="javascript:cargaridf1730('."'".$filadet['ofer30id']."'".')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		//<td><b>'.$ETI['ofer30anotacion'].'</b></td>
		$res=$res.'<tr'.$sClass.'>
<td>'.$sprefijo.$et_ofer30fecha.$ssufijo.'</td>
<td>'.$sprefijo.html_TablaHoraMin($filadet['ofer30hora'], $filadet['ofer30minuto']).$ssufijo.'</td>
<td>'.$sprefijo.$filadet['C7_td'].' '.$filadet['C7_doc'].$ssufijo.'</td>
<td>'.$sprefijo.cadena_notildes($filadet['C7_nombre']).$ssufijo.'</td>
<td><span color="#'.$filadet['TonoPrevio'].'"><b>'.cadena_notildes($filadet['Previo']).'</b></span></td>
<td><span color="#'.$filadet['TonoPost'].'"><b>'.cadena_notildes($filadet['Post']).'</b></span></td>
<td></td>
</tr>';
		if ($filadet['ofer30anotacion']!=''){
			$res=$res.'<tr'.$sClass.'>
<td></td>
<td colspan="6">'.$sprefijo.cadena_notildes($filadet['ofer30anotacion']).$ssufijo.'</td>
</tr>';
			}
		}
	$res=$res.'</table>';
	return utf8_encode($res);
	}
// -- 1730 Cambios de situacion XAJAX 
function f1730_Guardar($valores, $params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	//if(!is_array($valores)){$datos=json_decode(str_replace('\"','"',$valores),true);}
	//if (isset($datos[0])==0){$datos[0]='';}
	//if ($datos[0]==''){$sError=$ERR[''];}
	if ($sError==''){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		list($sError)=f1730_db_Guardar($valores, $objDB);
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		$sdetalle=f1730_TablaDetalle($params, $objDB);
		$objResponse->assign("div_f1730detalle","innerHTML",$sdetalle);
		$objResponse->call("limpiaf1730");
		$objResponse->assign("alarma","innerHTML",'item guardado');
		}else{
		$objResponse->assign("alarma","innerHTML",$sError);
		}
	return $objResponse;
	}
function f1730_Traer($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	require './app.php';
	$sError='';
	$besta=false;
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$paso=$params[0];
	if ($paso==1){
		$ofer30idoferta=numeros_validar($params[1]);
		$ofer30consec=numeros_validar($params[2]);
		if (($ofer30idoferta!='')&&($ofer30consec!='')){$besta=true;}
		}else{
		$ofer30id=$params[103];
		if ((int)$ofer30id!=0){$besta=true;}
		}
	if ($besta){
		$besta=false;
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$sSQLcondi='';
		if ($paso==1){
			$sSQLcondi=$sSQLcondi.'ofer30idoferta='.$ofer30idoferta.' AND ofer30consec='.$ofer30consec.'';
			}else{
			$sSQLcondi=$sSQLcondi.'ofer30id='.$ofer30id.'';
			}
		$sSQL='SELECT * FROM ofer30ofertahistestcampus WHERE '.$sSQLcondi;
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$besta=true;
			}
		}
	$objResponse=new xajaxResponse();
	if ($besta){
		$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
		if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
		require $mensajes_todas;
		$ofer30idactor_id=(int)$fila['ofer30idactor'];
		$ofer30idactor_td=$APP->tipo_doc;
		$ofer30idactor_doc='';
		$ofer30idactor_nombre='';
		if ($ofer30idactor_id!=0){
			list($ofer30idactor_id, $ofer30idactor_td, $ofer30idactor_doc, $ofer30idactor_nombre)=tabla_terceros_traer($ofer30idactor_id, $ofer30idactor_td, $ofer30idactor_doc, $objDB);
			}
		$ofer30consec_nombre='';
		$html_ofer30consec=html_oculto('ofer30consec', $fila['ofer30consec'], $ofer30consec_nombre);
		$objResponse->assign('div_ofer30consec', 'innerHTML', $html_ofer30consec);
		$ofer30id_nombre='';
		$html_ofer30id=html_oculto('ofer30id', $fila['ofer30id'], $ofer30id_nombre);
		$objResponse->assign('div_ofer30id', 'innerHTML', $html_ofer30id);
		$objResponse->assign('ofer30fecha', 'value', $fila['ofer30fecha']);
		$objResponse->assign("ofer30fecha_dia","value",substr($fila['ofer30fecha'],0,2));
		$objResponse->assign("ofer30fecha_mes","value",substr($fila['ofer30fecha'],3,2));
		$objResponse->assign("ofer30fecha_agno","value",substr($fila['ofer30fecha'],6,4));
		$objResponse->assign('ofer30hora', 'value', $fila['ofer30hora']);
		$objResponse->assign('ofer30minuto', 'value', $fila['ofer30minuto']);
		$objResponse->assign('ofer30idactor', 'value', $fila['ofer30idactor']);
		$objResponse->assign('ofer30idactor_td', 'value', $ofer30idactor_td);
		$objResponse->assign('ofer30idactor_doc', 'value', $ofer30idactor_doc);
		$objResponse->assign('div_ofer30idactor', 'innerHTML', $ofer30idactor_nombre);
		$objResponse->assign('ofer30estadoorigen', 'value', $fila['ofer30estadoorigen']);
		$objResponse->assign('ofer30estadodestino', 'value', $fila['ofer30estadodestino']);
		$objResponse->assign('ofer30anotacion', 'value', $fila['ofer30anotacion']);
		$objResponse->assign("alarma","innerHTML",'');
		$objResponse->call("verboton('belimina1730','block')");
		}else{
		if ($paso==1){
			$objResponse->assign("ofer30consec","value",$ofer30consec);
			}else{
			$objResponse->assign("alarma","innerHTML",'No se encontro el registro de referencia:'.$ofer30id);
			}
		}
	return $objResponse;
	}
function f1730_Eliminar($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	//if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$sError=f1730_db_Eliminar($params, $objDB);
	$objResponse=new xajaxResponse();
	if ($sError==''){
		$sDetalle=f1730_TablaDetalle($params, $objDB);
		$objResponse->assign("div_f1730detalle","innerHTML",$sDetalle);
		$objResponse->call("limpiaf1730");
		$sError='Item eliminado';
		}
	$objResponse->assign("alarma","innerHTML",$sError);
	return $objResponse;
	}
function f1730_HtmlTabla($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$sDetalle=f1730_TablaDetalle($params, $objDB);
	$objResponse=new xajaxResponse();
	$objResponse->assign("div_f1730detalle","innerHTML",$sDetalle);
	return $objResponse;
	}
function f1730_PintarLlaves(){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$html_ofer30consec='<input id="ofer30consec" name="ofer30consec" type="text" value="" onchange="revisaf1730()" class="cuatro"/>';
	$html_ofer30id='<input id="ofer30id" name="ofer30id" type="hidden" value=""/>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_ofer30consec','innerHTML', $html_ofer30consec);
	$objResponse->assign('div_ofer30id','innerHTML', $html_ofer30id);
	return $objResponse;
	}
// -- Funciones auxiliares.

function f1730_ArmarActores($idOferta, $objDB, $bDebug=false){
	$sError='';
	$sDebug='';
	$sSQL='SELECT ofer08idper_aca, ofer08idcurso, ofer08estadooferta FROM ofer08oferta WHERE ofer08id='.$idOferta.'';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$idPeraca=$fila['ofer08idper_aca'];
		$idCurso=$fila['ofer08idcurso'];
		$sCodCurso=$idCurso.'A_'.$idPeraca;
		if ($idPeraca==87){
			//$sCodCurso=$idCurso.'1_'.$idPeraca;
			}
		//Se marcan para control.
		$iDia=fecha_DiaMod();
		$iMinuto=fecha_MinutoMod();
		$sSQL='UPDATE matriculaoai SET control='.$iDia.' WHERE cod_curso="'.$sCodCurso.'" AND peraca='.$idPeraca.' AND control=0';
		$tabla=$objDB->ejecutasql($sSQL);
		$sSQLbase='INSERT INTO matriculaoai (idnumber, cod_curso, grupo, role, peraca, fecha, minuto, control) VALUES ';
		//Ahora traer lo que esta en la matricula...
		$sSQL='SELECT TB.ofer38idrol, TB.ofer38grupo, T11.unad11doc 
FROM ofer38matricula AS TB, unad11terceros AS T11 
WHERE TB.ofer38idoferta='.$idOferta.' AND TB.ofer38activo="S" AND TB.ofer38idtercero=T11.unad11id';
		$tabla=$objDB->ejecutasql($sSQL);
		while ($fila=$objDB->sf($tabla)){
			$sWhere='cod_curso="'.$sCodCurso.'" AND idnumber="'.$fila['unad11doc'].'" AND grupo="'.$fila['ofer38grupo'].'" AND role="'.$fila['ofer38idrol'].'" AND peraca='.$idPeraca.'';
			$sSQL='SELECT peraca FROM matriculaoai WHERE '.$sWhere;
			$tablarev=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablarev)==0){
				$sSQL=$sSQLbase.'("'.$fila['unad11doc'].'", "'.$sCodCurso.'", "'.$fila['ofer38grupo'].'", "'.$fila['ofer38idrol'].'", '.$idPeraca.', '.$iDia.', '.$iMinuto.', 0)';
				}else{
				$sSQL='UPDATE matriculaoai SET control=0 WHERE '.$sWhere;
				}
			$tablarev=$objDB->ejecutasql($sSQL);
			}
		//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' - Insersion de datos en tabla de exportacion '.$sSQL.'<br>';}
		if (false){
			//Se eliminan los que quedaron en control.
			$sSQL='DELETE FROM matriculaoai WHERE cod_curso="'.$sCodCurso.'" AND peraca='.$idPeraca.' AND control<>0';
			$tabla=$objDB->ejecutasql($sSQL);
			}
		}
	return array($sError, $sDebug);
	}
?>
