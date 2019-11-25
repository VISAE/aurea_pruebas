<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2014 - 2018 ---
Libreria de funciones para la oferta academica integrada.
*/
mb_internal_encoding('UTF-8');
//Saber si Esta en un Perfil
function OAI_bEsAcreditador($iPerAca, $idTercero, $idCurso, $objDB){
	$res=false;
	$sSQL='SELECT TB.ofer11idcurso FROM ofer11actores AS TB, ofer10rol AS T2 WHERE TB.ofer11idrol=T2.ofer10id AND TB.ofer11per_aca='.$iPerAca.' AND TB.ofer11idtercero='.$idTercero.' AND TB.ofer11idcurso='.$idCurso.' AND T2.ofer10claserol=8';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){$res=true;}
	return $res;
	}
function OAI_bEsCertificador($iPerAca, $idTercero, $idCurso, $objDB){
	$res=false;
	$sSQL='SELECT TB.ofer11idcurso FROM ofer11actores AS TB, ofer10rol AS T2 WHERE TB.ofer11idrol=T2.ofer10id AND TB.ofer11per_aca='.$iPerAca.' AND TB.ofer11idtercero='.$idTercero.' AND TB.ofer11idcurso='.$idCurso.' AND T2.ofer10claserol=8';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){$res=true;}
	return $res;
	}
function OAI_bEsEvaluador($iPerAca, $idTercero, $idCurso, $objDB){
	list($res, $sDebug)=OAI_bEsEvaluadorV2($iPerAca, $idTercero, $idCurso, $objDB);
	return $res;
	}
function OAI_bEsEvaluadorV2($iPerAca, $idTercero, $idCurso, $objDB, $bDebug=false){
	$res=false;
	$sDebug='';
	$sSQL='SELECT TB.ofer11idcurso FROM ofer11actores AS TB, ofer10rol AS T2 WHERE TB.ofer11idtercero='.$idTercero.' AND TB.ofer11idcurso='.$idCurso.' AND TB.ofer11per_aca='.$iPerAca.' AND TB.ofer11idrol=T2.ofer10id AND T2.ofer10claserol=5';
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta de verificacion de evaluador: '.$sSQL.'<br>';}
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)!=0){$res=true;}
	return array($res, $sDebug);
	}
// Datos para mostrar del curso.
function OAI_NombreCurso($idCurso, $objDB){
	$sRes='{Sin dato}';
	//, T1.unad42nombre
	$sSQL='SELECT TB.unad40nombre, TB.unad40numcreditos, T2.unad41nombre 
FROM (unad40curso AS TB) LEFT JOIN unad41tipocurso AS T2 ON (TB.unad40tipocurso=T2.unad41id) 
WHERE TB.unad40id='.$idCurso;
	// LEFT JOIN unad42tipostandard AS T1 ON (TB.unad40tipostandard=T1.unad42id)
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$sRes=cadena_notildes($fila['unad40nombre']).' - Cr&eacute;ditos: '.$fila['unad40numcreditos'].' - Tipo de curso: '.cadena_notildes($fila['unad41nombre']);
		}
	return $sRes;
	}
//Tnos para cursos...
function OAI_TonoEstado($idEstado){
	$sRes='FF0000';
	$sC1='FF0000';
	$sC2='FF6600';
	$sC3='003300';
	$sC4='000033';
	$colorEC['-1']=$sC1;
	$colorEC[0]=$sC1;
	$colorEC[1]=$sC2;
	$colorEC[2]=$sC2;
	$colorEC[4]=$sC1;
	$colorEC[7]=$sC3;
	$colorEC[8]=$sC3;
	$colorEC[9]=$sC3;
	$colorEC[10]=$sC4;
	$colorEC[11]=$sC3;
	$colorEC[12]=$sC4;
	$colorEC[15]=$sC3;
	$colorEC[19]=$sC3;
	$colorEC[20]=$sC4;
	if (isset($colorEC[$idEstado])!=0){
		$sRes=$colorEC[$idEstado];
		}
	return $sRes;
	}
// Crear una nueva agenda AVA para un curso. 12 Junio de 2014
// 7 de Julio -- Si existe una agenda sin actividades no se crea, sino que se devuelve la existente.
// 27 de marzo de 2015 si no tiene estuidantes se le actualiza a 50
function OAI_Agenda_Nueva($idCurso, $objDB){
	return array(0, 0, 'Esta funci&oacute;n ha sido deshabilitada (OAI_Agenda_Nueva), Por favor informe al administrador del sistema de este error');
	}
function OAI_Agenda_NuevaV2($idCurso, $idPeraca, $sIdioma, $idAgendaBase, $objDB, $bDebug=false){
	//Febrero de 2016
	//Cambios con respecto a la agenda anterior:
	//Antes se controlaba por el consecutivo y serie de la agenda, ahora se controla por curso peraca.
	//Por tanto los numero de serie eran importantes, ahora en cada agenda nace un nuevo consecutivo y la serie la manetenemos en 1.
	//Esto quiere decir que se elimina el uso de las agendas tipo CORE.
	//Ademas se incluye la clonaci�n de las agendas para evitar que tengan que repetir trabajo...
	require './app.php';
	$mensajes_1705='lg/lg_1705_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1705)){$mensajes_1705='lg/lg_1705_es.php';}
	require $mensajes_1705;
	$idAgenda=0;
	$ofer05consec=0;
	$sError='';
	$sDebug='';
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' CLONANDO AGENDA - Agenda base '.$idAgendaBase.'<br>';}
	//Ubicar el curso y ver que tenga una agenda abierta.
	/*
	$sSQL='SELECT TB.unad40idagenda, T5.ofer05consec, T5.ofer05cerrada FROM unad40curso AS TB, ofer05agenda AS T5 WHERE TB.unad40id='.$idCurso.' AND TB.unad40idagenda=T5.ofer05id';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)==0){$sError=$ERR['no_curso'];}
	if ($sError==''){
		$fila=$objDB->sf($tabla);
		if ($fila['ofer05cerrada']=='S'){$sError=$ERR['nueva_desde_cerrado'];}
		}
	*/
	if ($sError==''){
		//Se deshabilita esta comprobaci�n que lo que trataba de hacer era tener una agenda base...
		/*
		$consec=$fila['ofer05consec'];
		$sSQL='SELECT ofer05nombre FROM ofer05agenda WHERE ofer05consec='.$consec.' AND ofer05serie=0';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)==0){
			$sError=$ERR['no_agenda0'];
			}else{
			$fila=$objDB->sf($tabla);
			}
		*/
		//Se valida que solo haya una agenda para el peraca para el curso.
		if ($idPeraca!=87){
			$sSQL='SELECT ofer05id FROM ofer05agenda WHERE ofer05idcurso='.$idCurso.' AND ofer05idperaca='.$idPeraca.' AND ofer05idioma="'.$sIdioma.'"';
			$tabla=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla)>0){
				$sError=$ERR['agenda_yaexiste'];
				}
			}
		}
	$bEstaLibre=false;
	if ($sError==''){
		//ver si existe una libre.
		//Tambien se deshabilita a partir de peraca 289 porque simplemente las agendas son por periodo... si sobran no hay problema...
		$sNomAgenda='';
		/*
		$sSQL='SELECT ofer05id, (SELECT COUNT(ofer06id) FROM ofer06agendaactividad WHERE ofer06idagenda=ofer05id) AS Total, ofer05nombre FROM ofer05agenda WHERE ofer05consec='.$consec.' AND ofer05serie<>0 AND ofer05idcurso='.$idCurso.'';
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			if ($fila['Total']==0){
				$idAgenda=$fila['ofer05id'];
				$sNomAgenda=$fila['ofer05nombre'];
				$bEstaLibre=true;
				break;
				}
			}
		*/
		$sSQL='SELECT unad40nombre FROM unad40curso WHERE unad40id='.$idCurso;
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$sNomAgenda=$idCurso.' '.substr($fila['unad40nombre'], 0, 75).' - Periodo '.$idPeraca.' '.$sIdioma.'';
			}
		}
	if (!$bEstaLibre){
		if ($idPeraca<289){
			//Esto evita que se intente clonar una agenda que no sea peraca 289 o superior.
			$idAgendaBase=0;
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' CLONANDO AGENDA - Se retira la agenda base porque el periodo es '.$idPeraca.'<br>';}
			}
		if ($sError==''){
			// sACAR LA SERIE Y EL ID
			//$id05serie=tabla_consecutivo('ofer05agenda','ofer05serie', 'ofer05consec='.$consec, $objDB);
			$ofer05consec=tabla_consecutivo('ofer05agenda','ofer05consec', '', $objDB);
			$id05serie=1;
			$id05=tabla_consecutivo('ofer05agenda','ofer05id', '', $objDB);
			}
		if ($sError==''){
			//Crear replicar la nueva agenda.
			$scampos='ofer05consec, ofer05serie, ofer05id, ofer05cerrada, ofer05nombre, ofer05detalle, ofer05propietario, ofer05idcurso, ofer05idioma, ofer05idperaca';
			$svalores=''.$ofer05consec.', '.$id05serie.', '.$id05.', "N", "'.$sNomAgenda.'", "", '.$_SESSION['unad_id_tercero'].', '.$idCurso.', "'.$sIdioma.'", '.$idPeraca.'';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO ofer05agenda ('.$scampos.') VALUES ('.utf8_encode($svalores).');';
				}else{
				$sSQL='INSERT INTO ofer05agenda ('.$scampos.') VALUES ('.$svalores.');';
				}
			$result=$objDB->ejecutasql($sSQL);
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' CLONANDO AGENDA - Se inserta el encabezado '.$sSQL.'<br>';}
			if ($result==false){
				$sError=$ERR['no_agenda_insertar'].'<!-- '.$sSQL.' --><br>ERROR: '.$objDB->serror;
				}else{
				$idAgenda=$id05;
				if ($idAgendaBase>0){
					//Clonar todo lo que falte...
					//Es posible que la agenda base sea por peraca o sea una 87... por tanto tenemos que tener en cuenta eso y primero cargar los id a clonar e insertar los ids equivalentes.....
					$iNumActividades=0;
					$ACT=array();
					$h02=array();
					$h03=array();
					$h04=array();
					$h05=array();
					$sIdsFase='-99';
					$sIdsUnidad='-99';
					$sIdsActividad='-99';
					$sSQL='SELECT ofer06idfase, ofer06idunidad, ofer06idactividad, ofer06orden, ofer06diaini, ofer06dias, ofer06diasretro, ofer06peso, ofer07detalle FROM ofer06agendaactividad WHERE ofer06idagenda='.$idAgendaBase;
					$tabla=$objDB->ejecutasql($sSQL);
					while ($fila=$objDB->sf($tabla)){
						$iNumActividades++;
						$ACT[$iNumActividades]['fase']=$fila['ofer06idfase'];
						$ACT[$iNumActividades]['unidad']=$fila['ofer06idunidad'];
						$ACT[$iNumActividades]['actividad']=$fila['ofer06idactividad'];
						$ACT[$iNumActividades]['orden']=$fila['ofer06orden'];
						$ACT[$iNumActividades]['diaini']=$fila['ofer06diaini'];
						$ACT[$iNumActividades]['dias']=$fila['ofer06dias'];
						$ACT[$iNumActividades]['retro']=$fila['ofer06diasretro'];
						$ACT[$iNumActividades]['peso']=$fila['ofer06peso'];
						$ACT[$iNumActividades]['detalle']=$fila['ofer07detalle'];
						$ACT[$iNumActividades]['idfase']=0;
						$ACT[$iNumActividades]['idunidad']=0;
						$ACT[$iNumActividades]['idactividad']=0;
						$sIdsFase=$sIdsFase.','.$fila['ofer06idfase'];
						$sIdsUnidad=$sIdsUnidad.','.$fila['ofer06idunidad'];
						$sIdsActividad=$sIdsActividad.','.$fila['ofer06idactividad'];
						}
					//ofer02cursofase
					$ofer02id=tabla_consecutivo('ofer02cursofase', 'ofer02id', '', $objDB);
					$ofer02consec=tabla_consecutivo('ofer02cursofase', 'ofer02consec', '', $objDB);
					$scampos='ofer02consec, ofer02id, ofer02nombre, ofer02fechabase, ofer02idcurso, ofer02idagenda';
					$svalores='';
					for ($k=1;$k<=$iNumActividades;$k++){
						$idFasePrev=$ACT[$k]['fase'];
						if (isset($h02[$idFasePrev])==0){
							$sSQL='SELECT ofer02nombre, ofer02fechabase, ofer02idcurso FROM ofer02cursofase WHERE ofer02id='.$ACT[$k]['fase'].'';
							$tabla=$objDB->ejecutasql($sSQL);
							if ($objDB->nf($tabla)>0){
								$fila=$objDB->sf($tabla);
								if ($svalores!=''){$svalores=$svalores.',';}
								$svalores=$svalores.'('.$ofer02consec.', '.$ofer02id.', "'.$fila['ofer02nombre'].'", '.$fila['ofer02fechabase'].', '.$fila['ofer02idcurso'].', '.$id05.')';
								$h02[$idFasePrev]=$ofer02id;
								if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' CLONANDO AGENDA - La fase '.$idFasePrev.' se homologa a '.$ofer02id.'<br>';}
								$ofer02id++;
								$ofer02consec++;
								}
							}
						$ACT[$k]['idfase']=$h02[$idFasePrev];
						}
					if ($svalores!=''){
						if ($APP->utf8==1){
							$sSQL='INSERT INTO ofer02cursofase ('.$scampos.') VALUES '.utf8_encode($svalores).';';
							}else{
							$sSQL='INSERT INTO ofer02cursofase ('.$scampos.') VALUES '.$svalores.';';
							}
						$result=$objDB->ejecutasql($sSQL);
						if ($tabla==false){
							$sError=$sSQL;
							}
						}
					//ofer03cursounidad
					$ofer03id=tabla_consecutivo('ofer03cursounidad', 'ofer03id', '', $objDB);
					$ofer03consec=tabla_consecutivo('ofer03cursounidad', 'ofer03consec', '', $objDB);
					$scampos='ofer03consec, ofer03id, ofer03nombre, ofer03idcurso, ofer03idagenda, ofer03idmomento';
					$svalores='';
					for ($k=1;$k<=$iNumActividades;$k++){
						$idUnidadPrev=$ACT[$k]['unidad'];
						if (isset($h03[$idUnidadPrev])==0){
							$sSQL='SELECT ofer03nombre, ofer03idcurso, ofer03idmomento FROM ofer03cursounidad WHERE ofer03id='.$ACT[$k]['unidad'].'';
							$tabla=$objDB->ejecutasql($sSQL);
							if ($objDB->nf($tabla)>0){
								$fila=$objDB->sf($tabla);
								if ($svalores!=''){$svalores=$svalores.',';}
								$svalores=$svalores.'('.$ofer03consec.', '.$ofer03id.', "'.$fila['ofer03nombre'].'", '.$fila['ofer03idcurso'].', '.$id05.', '.$fila['ofer03idmomento'].')';
								$h03[$idUnidadPrev]=$ofer03id;
								if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' CLONANDO AGENDA - La unidad '.$idUnidadPrev.' se homologa a '.$ofer03id.'<br>';}
								$ofer03id++;
								$ofer03consec++;
								}else{
								$h03[$idUnidadPrev]=0;
								if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' CLONANDO AGENDA - ERROR no se encuentra La unidad '.$idUnidadPrev.' NO ES POSIBLE HOMOLOGAR<br>';}
								}
							}
						$ACT[$k]['idunidad']=$h03[$idUnidadPrev];
						}
					if ($svalores!=''){
						if ($APP->utf8==1){
							$sSQL='INSERT INTO ofer03cursounidad ('.$scampos.') VALUES '.utf8_encode($svalores).';';
							}else{
							$sSQL='INSERT INTO ofer03cursounidad ('.$scampos.') VALUES '.$svalores.';';
							}
						$result=$objDB->ejecutasql($sSQL);
						if ($tabla==false){
							$sError=$sSQL;
							}
						}
					//ofer04cursoactividad
					$ofer04id=tabla_consecutivo('ofer04cursoactividad', 'ofer04id', '', $objDB);
					$ofer04consec=tabla_consecutivo('ofer04cursoactividad', 'ofer04consec', '', $objDB);
					$scampos='ofer04consec, ofer04id, ofer04nombre, ofer04detalle, ofer04retroalimentacion, ofer04idtipoactividad, ofer04paraestudiantes, ofer04paraava, ofer04idcurso, ofer04idagenda';
					$svalores='';
					for ($k=1;$k<=$iNumActividades;$k++){
						$sSQL='SELECT ofer04nombre, ofer04detalle, ofer04retroalimentacion, ofer04idtipoactividad, ofer04paraestudiantes, ofer04paraava, ofer04idcurso FROM ofer04cursoactividad WHERE ofer04id='.$ACT[$k]['actividad'].'';
						$tabla=$objDB->ejecutasql($sSQL);
						if ($objDB->nf($tabla)>0){
							$fila=$objDB->sf($tabla);
							if ($svalores!=''){$svalores=$svalores.',';}
							$svalores=$svalores.'('.$ofer04consec.', '.$ofer04id.', "'.$fila['ofer04nombre'].'", "'.$fila['ofer04detalle'].'", "'.$fila['ofer04retroalimentacion'].'", '.$fila['ofer04idtipoactividad'].', "'.$fila['ofer04paraestudiantes'].'", "'.$fila['ofer04paraava'].'", '.$fila['ofer04idcurso'].', '.$id05.')';
							$ACT[$k]['idactividad']=$ofer04id;
							$ofer04id++;
							$ofer04consec++;
							}
						}
					if ($svalores!=''){
						if ($APP->utf8==1){
							$sSQL='INSERT INTO ofer04cursoactividad ('.$scampos.') VALUES '.utf8_encode($svalores).';';
							}else{
							$sSQL='INSERT INTO ofer04cursoactividad ('.$scampos.') VALUES '.$svalores.';';
							}
						$result=$objDB->ejecutasql($sSQL);
						if ($tabla==false){
							$sError=$sSQL;
							}
						}
					//ofer06agendaactividad
					$sInfo06='';
					$id06=tabla_consecutivo('ofer06agendaactividad', 'ofer06id', '', $objDB);
					for ($k=1;$k<=$iNumActividades;$k++){
						if ($sInfo06!=''){$sInfo06=$sInfo06.',';}
						$sInfo06=$sInfo06.'('.$id05.', '.$ACT[$k]['idfase'].', '.$ACT[$k]['idunidad'].', '.$ACT[$k]['idactividad'].', '.$id06.', '.$ACT[$k]['orden'].', '.$ACT[$k]['diaini'].', '.$ACT[$k]['dias'].', '.$ACT[$k]['retro'].', '.$ACT[$k]['peso'].', "'.$ACT[$k]['detalle'].'")';
						$id06++;
						}
					if ($sInfo06!=''){
						$sSQL='INSERT INTO ofer06agendaactividad (ofer06idagenda, ofer06idfase, ofer06idunidad, ofer06idactividad, ofer06id, ofer06orden, ofer06diaini, ofer06dias, ofer06diasretro, ofer06peso, ofer07detalle) VALUES '.$sInfo06;
						$tabla=$objDB->ejecutasql($sSQL);
						if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' '.$sSQL.'<br>';}
						if ($tabla==false){
							$sError=$sSQL;
							}
						}
					}else{
					if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' CLONANDO AGENDA - No hay agenda base. Se termina el proceso.<br>';}
					}
				}
			}
		}
	return array($idAgenda, $ofer05consec, $sError, $sDebug);
	}
//Armar la agenda para revisar la carga del NAV
//27 de Marzo de 2015 - Se revisa que si hay 0 alumnos se ubiquen 50
function OAI_ArmarAgenda($idperaca, $idcurso, $numaula, $bForzar, $objDB, $bControlaFechas=true){
	list($sError, $sDebug)=OAI_ArmarAgendaV2($idperaca, $idcurso, $numaula, $bForzar, $objDB, $bControlaFechas);
	return $sError;
	}
function OAI_ArmarAgendaV2($idperaca, $idcurso, $numaula, $bForzar, $objDB, $bControlaFechas=true, $bDebug=false){
	//borrar lo que haya previo, tener en cuenta que es solo el origen agenda.
	//ubicar que este ofertado.
	//buscar la info de la agenda
	//cargar el encabezado
	//cargar los detalles
	//-- 
	$sError='';
	$sAlerta='';
	$iDiaInicial=0;
	$iDiaAula1=0;
	$idAgenda=0;
	$idNav=0;
	$iNumEstudiantes=0;
	$sDebug='';
	if ($numaula==1){
		//Ver que no esten bloqueadas las agendas
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Verificando que se pueda editar agendas.<br>';}
		$sSQL='SELECT exte02permiteditaragenda FROM exte02per_aca WHERE exte02id='.$idperaca;
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			if ($fila['exte02permiteditaragenda']!='S'){
				$sError='El proceso de edici&oacute;n de agendas se encuentra cerrado, la acci&oacute;n NO se permite.';
				}
			}
		}
	if ($sError==''){
		if (!$bForzar){
			//Ver que el curso no est� ya acreditado.
			$sSQL='SELECT ofer08estadocampus FROM ofer08oferta WHERE ofer08idper_aca='.$idperaca.' AND ofer08idcurso='.$idcurso.' AND ofer08cead=0';
			$tabla=$objDB->ejecutasql($sSQL);
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Revisando el estado campus {'.$sSQL.'}.<br>';}
			if ($objDB->nf($tabla)){
				$fila=$objDB->sf($tabla);
				//Marzo 3 de 2015 si el peraca es 222 o superior se cambia la validaci�n.
				if ($idperaca<222){
					if ($fila['ofer08estadocampus']>9){
						$sError='El curso se encuentra acreditado, no se permite actualizar la agenda.';
						}
					}else{
					switch ($fila['ofer08estadocampus']){
						case 10:
						$sError='El curso se encuentra acreditado, no se permite actualizar la agenda.';
						break;
						case 12:
						$sError='El curso se encuentra certificado, no se permite actualizar la agenda.';
						break;
						}
					}
				}
			}
		}
	//Ver que se hayan configurado dias...
	if ($sError==''){
		if ($numaula==1){
			$sSQL='SELECT ofer19numdia, ofer19fecha FROM ofer19diasperaca WHERE ofer19per_aca='.$idperaca.' AND ofer19numdia>0 ORDER BY ofer19numdia DESC LIMIT 0,1';
			$tabladia=$objDB->ejecutasql($sSQL);
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Revisando configuraci&oacute;n de d&iacute;as {'.$sSQL.'}.<br>';}
			if ($objDB->nf($tabladia)==0){
				$sError='No se han configurado d&iacute;as para el per&iacute;odo solicitado, por favor informe al administrador del sistema.';
				}
			}
		}
	if ($sError==''){
		//borrar lo que haya previo, tener en cuenta que es solo el origen agenda.
		$sSQL='DELETE FROM ofer18cargaxnavxdia WHERE ofer18per_aca='.$idperaca.' AND ofer18curso='.$idcurso.' AND ofer18numaula='.$numaula;
		$result=$objDB->ejecutasql($sSQL);
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Borrando carga inicial {'.$sSQL.'}.<br>';}
		if ($result==false){
			$sError='Error al intentar borrar la carga inicial de la agenda. <!-- '.$sSQL.' -->';
			}
		//ubicar que este ofertado.
		$sSQL='SELECT ofer08id, ofer08estadooferta, ofer08idagenda, ofer08diainical, ofer08numestaula1, ofer08idnav 
FROM ofer08oferta 
WHERE ofer08idcurso='.$idcurso.' AND ofer08idper_aca='.$idperaca.' AND ofer08cead=0';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Ver que el curso este ofertado {'.$sSQL.'}.<br>';}
		if ($objDB->nf($tabla)==0){
			//el curso no esta.
			$sError='Curso No ofertado';
			}else{
			$infocurso=$objDB->sf($tabla);
			if ($infocurso['ofer08estadooferta']!=1){
				$sError='Curso No ofertado';
				}else{
				$idAgenda=$infocurso['ofer08idagenda'];
				$idNav=$infocurso['ofer08idnav'];
				$iNumEstudiantes=$infocurso['ofer08numestaula1'];
				$iDiaInicial=$infocurso['ofer08diainical'];
				$iDiaAula1=$iDiaInicial;
				if ($iNumEstudiantes==0){
					$id08=$infocurso['ofer08id'];
					$iNumEstudiantes=50;
					$sSQL='UPDATE ofer08oferta SET ofer08numestaula1=50 WHERE ofer08id='.$id08;
					$result=$objDB->ejecutasql($sSQL);
					OAI_TotalEstudiantes_Actualizar($id08, $objDB);
					}
				}
			}
		if ($sError==''){
			if ($numaula>1){
				//Sacar la carga de estudiantes que le corresponda.
				$sSQL='SELECT unad48diainicial, unad48idnav, unad48numestudiantes FROM unad48cursoaula WHERE unad48per_aca='.$idperaca.' AND unad48idcurso='.$idcurso.' AND unad48consec='.$numaula;
				$tabla=$objDB->ejecutasql($sSQL);
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Sacar la carga de estudiantes {'.$sSQL.'}.<br>';}
				if ($objDB->nf($tabla)==0){
					//el curso no esta.
					$sError='No se ha encontrado informaci&oacute;n del aula '.$numaula;
					}else{
					$infocurso=$objDB->sf($tabla);
					$iDiaInicial=$infocurso['unad48diainicial'];
					$idNav=$infocurso['unad48idnav'];
					$iNumEstudiantes=$infocurso['unad48numestudiantes'];
					}
				}
			if ($idNav<1){$sError='NAV no asignado {Aula '.$numaula.'}';}
			if ($iNumEstudiantes<1){$sError='Sin aginacion de estudiantes {Aula '.$numaula.'}';}
			if ($idAgenda<1){$sError='Agenda no asignada';}
			}
		}
	if ($sError==''){
		//Establecer la agenda a usar, si es cerrada se usa la padre, si no.... se pone a 0 a menos que exista.
		$sSQL='SELECT ofer05serie, ofer05cerrada FROM ofer05agenda WHERE ofer05id='.$idAgenda.'';
		$result=$objDB->ejecutasql($sSQL);
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Buscando la agenda que corresponde {'.$sSQL.'}.<br>';}
		if ($objDB->nf($result)>0){
			$fila=$objDB->sf($result);
			if (($fila['ofer05cerrada']!='S')&&($fila['ofer05serie']==0)){
				$idAgenda=0;
				//Si La agenda es abierta y la serie es 0 entonces no se ha establecido una agenda.
				$sError='La agenda asignada no es editable';
				}
			}else{
			//La agenda muy posiblemente fue borrada asi que liberar la agenda en la oferta 
			$sSQL='SELECT unad40idagenda FROM unad40curso WHERE unad40id='.$idcurso;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)!=0){
				$fila=$objDB->sf($result);
				$sSQL='UPDATE ofer08oferta SET ofer08idagenda='.$fila['unad40idagenda'].' WHERE ofer08idper_aca='.$idperaca.' AND ofer08idcurso='.$idcurso.' AND ofer08cead=0';
				$result=$objDB->ejecutasql($sSQL);
				$sError='<!-- [[]] -->La agenda asignada no fue encontrada (es posible que haya sido eliminada) por este motivo se ha reiniciado la agenda, por favor vuelva a ingresar a esta ventana para actualizar los datos de la agenda';
				}
			}
		}
	if ($sError==''){
		//Verificar el encabezado
		$swhere='ofer17curso='.$idcurso.' AND ofer17per_aca='.$idperaca.' AND ofer17numaula='.$numaula.'';
		$sSQL='SELECT ofer17idagenda FROM ofer17cargaxnav WHERE '.$swhere;
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Verificando encabezado de la agenda {'.$sSQL.'}.<br>';}
		$result=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($result)==0){
			$scampos='ofer17per_aca, ofer17curso, ofer17numaula, ofer17idagenda, ofer17diacierre, ofer17numestudiantes, ofer17nav';
			$svalores=''.$idperaca.', '.$idcurso.', '.$numaula.', '.$idAgenda.', '.$iDiaInicial.', '.$iNumEstudiantes.', '.$idNav.'';
			$sSQL='INSERT INTO ofer17cargaxnav ('.$scampos.') VALUES ('.$svalores.');';
			}else{
			$sAdd='';
			if ($idAgenda!=0){
				$sAdd='ofer17idagenda='.$idAgenda.', ';
				}else{
				$fila=$objDB->sf($result);
				$idAgenda=$fila['ofer17idagenda'];
				}
			$sSQL='UPDATE ofer17cargaxnav SET '.$sAdd.'ofer17diacierre='.$iDiaInicial.', ofer17numestudiantes='.$iNumEstudiantes.', ofer17nav='.$idNav.' WHERE '.$swhere;
			}
		$result=$objDB->ejecutasql($sSQL);
		//si la agenda es abierta, ver si ya tiene una asignada
		if ($idAgenda==0){
			$sError='No se ha establecido una agenda para el curso -- '.$idAgenda;
			}
		}
	if ($sError==''){
		//buscar la info de la agenda
		if ($numaula>1){
			// Septiembre 26 de 2015 - Es una agenda hija hay que tomarla como copia de la aula principal
			$sSQL='SELECT TB.ofer18fase AS ofer06idfase, TB.ofer18unidad AS ofer06idunidad, TB.ofer18idactividad AS ofer06idactividad, TB.ofer18diaini AS ofer06diaini, TB.ofer18duracion AS ofer06dias, TB.ofer18diasretro AS ofer06diasretro, TB.ofer18peso AS ofer06peso, ofer02fechabase, TB.ofer18orden AS ofer06orden, TB.ofer18detalle AS ofer07detalle 
FROM ofer18cargaxnavxdia AS TB, ofer02cursofase AS T1 
WHERE TB.ofer18curso='.$idcurso.' AND TB.ofer18per_aca='.$idperaca.' AND TB.ofer18numaula=1 AND TB.ofer18fase=ofer02id 
ORDER BY ofer02fechabase, TB.ofer18orden';
			}else{
			$sSQL='SELECT ofer06idfase, ofer06idunidad, ofer06idactividad, ofer06diaini, ofer06dias, ofer06diasretro, ofer06peso, ofer02fechabase, ofer06orden, ofer07detalle 
FROM ofer06agendaactividad AS TB, ofer02cursofase AS T1 
WHERE TB.ofer06idagenda='.$idAgenda.' AND TB.ofer06idfase=ofer02id  
ORDER BY ofer02fechabase, TB.ofer06orden';
			}
		$tablaagenda=$objDB->ejecutasql($sSQL);
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Buscando la informaci&oacute;n para la agenda {'.$sSQL.'}.<br>';}
		if ($objDB->nf($tablaagenda)==0){
			$sError='Agenda sin actividades';
			}
		}
	if ($sError==''){
		//Cargar los dias adicionales al arranque del ciclo.
		$iDiaFase[0]=0;
		$iDiaFase[3]=0;
		$iDiaFase[4]=0;
		$iDiaFase[5]=0;
		$iDiaFase[9]=0;
		$sDebug2='';
		$sSQL='SELECT ofer14fechaini40, ofer14fechainisupletorio, ofer14fechainihabilitacion, ofer14fechaini25poc FROM ofer14per_acaparams WHERE ofer14idper_aca='.$idperaca;
		$result=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($result)>0){
			$fdet=$objDB->sf($result);
			for ($k=1;$k<=4;$k++){
				$sCampo='ofer14fechaini40';
				$j=3;
				if ($k==2){$sCampo='ofer14fechainisupletorio';$j=5;}
				if ($k==3){$sCampo='ofer14fechainihabilitacion';$j=9;}
				if ($k==4){$sCampo='ofer14fechaini25poc';$j=4;}
				if (fecha_esvalida($fdet[$sCampo])){
					$iDiaParaActividad=0;
					$sSQL='SELECT ofer19numdia FROM ofer19diasperaca WHERE ofer19per_aca='.$idperaca.' AND ofer19fecha="'.$fdet[$sCampo].'"';
					$result2=$objDB->ejecutasql($sSQL);
					if ($objDB->nf($result2)>0){
						$fnumdia=$objDB->sf($result2);
						if ($fnumdia['ofer19numdia']==-1){
							//Es un festivo hay que traer el d�a siguiente.
							$sSQL='SELECT ofer19numdia FROM ofer19diasperaca WHERE ofer19per_aca='.$idperaca.' AND STR_TO_DATE(ofer19fecha, "%d/%m/%Y")>STR_TO_DATE("'.$fdet[$sCampo].'","%d/%m/%Y") LIMIT 0,1';
							$result2=$objDB->ejecutasql($sSQL);
							if ($objDB->nf($result2)>0){
								$fnumdia=$objDB->sf($result2);
								$iDiaParaActividad=$fnumdia['ofer19numdia']-1;
								}
							}else{
							$iDiaParaActividad=$fnumdia['ofer19numdia']-1;
							}
						//Se corre atras un dia para tener en 0 la fecha anterior al dia que resulta.
						//$sDebug2=$sDebug2.'Dia para actividad '.$j.'='.$iDiaParaActividad.' <br>';
						$iDiaFase[$j]=$iDiaParaActividad;
						if ($iDiaFase[$j]<0){$iDiaFase[$j]=0;}
						}
					}
				}
			}
		//$sError=$iDiaFase[3].' '.$iDiaFase[5].' '.$iDiaFase[9]; //Para verificar el proceso.
		//cargar los detalles -- Tabla 18
		$scampos='ofer18per_aca, ofer18curso, ofer18numaula, ofer18fase, ofer18unidad, ofer18idactividad, ofer18diaini, ofer18fechainicio, ofer18duracion, ofer18fechacierrre, ofer18diasretro, ofer18fecharetro, ofer18orden, ofer18peso, ofer18detalle';
		$svalores='';
		while ($filaagenda=$objDB->sf($tablaagenda)){
			if ($svalores!=''){$svalores=$svalores.', ';}
			$idia=$iDiaFase[$filaagenda['ofer02fechabase']]+$filaagenda['ofer06diaini'];
			if ($idia==0){$idia=1;}
			//La fecha inicial debe ser la misma para todas las aulas.
			if ($numaula>1){
				$iDiaIni=$filaagenda['ofer06diaini'];
				$iduracion=$filaagenda['ofer06dias']+$iDiaInicial;
				$iretro=$filaagenda['ofer06diasretro']+$iDiaInicial;
				$bCambiaFecha=false;
				if ($filaagenda['ofer02fechabase']==3){$bCambiaFecha=true;}
				if ($filaagenda['ofer02fechabase']==4){$bCambiaFecha=true;}
				if ($bCambiaFecha){
					$iduracion=$filaagenda['ofer06dias'];
					$iretro=$filaagenda['ofer06diasretro'];
					}
				}else{
				//Se corren las fechas.
				$iDiaIni=$idia+$iDiaAula1;
				$idia=$idia+$iDiaInicial;
				$iduracion=$idia+$filaagenda['ofer06dias'];
				$iretro=$iduracion+$filaagenda['ofer06diasretro'];
				}
			$svalores=$svalores.'('.$idperaca.', '.$idcurso.', '.$numaula.', '.$filaagenda['ofer06idfase'].', '.$filaagenda['ofer06idunidad'].', '.$filaagenda['ofer06idactividad'].', '.$iDiaIni.', "00/00/0000", '.$iduracion.', "00/00/0000", '.$iretro.', "00/00/0000", '.$filaagenda['ofer06orden'].', '.$filaagenda['ofer06peso'].', "'.$filaagenda['ofer07detalle'].'")';
			}
		if ($svalores!=''){
			$sSQL='INSERT INTO ofer18cargaxnavxdia ('.$scampos.') VALUES '.$svalores.';';
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Insertando actividades {'.$sSQL.'}.<br>';}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError='Error al intentar insertar las actividades ['.$objDB->serror.']';
				}
			//actualizar las fechas...
			$sSQL='UPDATE ofer18cargaxnavxdia , ofer19diasperaca SET ofer18fechainicio=ofer19fecha WHERE  ofer18per_aca=ofer19per_aca AND ofer18diaini=ofer19numdia AND ofer18per_aca='.$idperaca.' AND ofer18curso='.$idcurso.' AND ofer18numaula='.$numaula.'';
			$result=$objDB->ejecutasql($sSQL);
			//ofer18duracion, ofer18fechacierrre, 
			$sSQL='UPDATE ofer18cargaxnavxdia , ofer19diasperaca SET  ofer18fechacierrre=ofer19fecha WHERE  ofer18per_aca= ofer19per_aca AND ofer18duracion=ofer19numdia AND ofer18per_aca='.$idperaca.' AND ofer18curso='.$idcurso.' AND ofer18numaula='.$numaula.'';
			$result=$objDB->ejecutasql($sSQL);
			//ofer18diasretro, ofer18fecharetro
			$sSQL='UPDATE ofer18cargaxnavxdia , ofer19diasperaca SET  ofer18fecharetro=ofer19fecha WHERE  ofer18per_aca= ofer19per_aca AND ofer18diasretro=ofer19numdia AND ofer18per_aca='.$idperaca.' AND ofer18curso='.$idcurso.' AND ofer18numaula='.$numaula.'';
			$result=$objDB->ejecutasql($sSQL);
			//Controlar que no se salga ninguna fecha de cierre.
			$sSQL='SELECT TB.ofer18diaini, TB.ofer18fechainicio, TB.ofer18duracion, TB.ofer18orden, TB.ofer18idactividad, T4.ofer04nombre, TB.ofer18duracion 
FROM ofer18cargaxnavxdia AS TB, ofer04cursoactividad AS T4 
WHERE TB.ofer18curso='.$idcurso.' AND TB.ofer18per_aca='.$idperaca.' AND TB.ofer18numaula='.$numaula.' AND TB.ofer18fechacierrre="00/00/0000" AND TB.ofer18idactividad=T4.ofer04id';
			//$sDebug2=$sSQL;
			$tabla=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla)>0){
				//Sacar la ultima fecha que exista...
				$sSQL='SELECT ofer19numdia, ofer19fecha FROM ofer19diasperaca WHERE ofer19per_aca='.$idperaca.' AND ofer19numdia>0 ORDER BY ofer19numdia DESC LIMIT 0,1';
				$tabladia=$objDB->ejecutasql($sSQL);
				$filadia=$objDB->sf($tabladia);
				//mostrar los errores
				if ($numaula>1){
					$sError=$sDebug2.'<br>Las actividades que se listas a continuaci&oacute;n superan la fecha m&aacute;xima de cierre permisible, <br>por favor ajuste en la agenda la duraci&oacute;n de las actividades y vuelva a intentar este proceso; las actividades son:';
					while ($fila=$objDB->sf($tabla)){
						$iMaxDia=$filadia['ofer19numdia']-$fila['ofer18diaini'];
						$iDiaActual=$fila['ofer18duracion']-$fila['ofer18diaini'];
						$sError=$sError.'<br><b>'.cadena_notildes($fila['ofer04nombre']).'</b> Duraci&oacute;n m&aacute;xima permisible:'.$iMaxDia.' {actual '.$iDiaActual.'}';
						}
					//Reversar la insersion.
					$sSQL='DELETE FROM ofer18cargaxnavxdia WHERE ofer18curso='.$idcurso.' AND ofer18per_aca='.$idperaca.' AND ofer18numaula='.$numaula.'';
					$result=$objDB->ejecutasql($sSQL);
					if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Reversando los datos insertados {'.$sSQL.'}.<br>';}
					}else{
					//Poner el dia final e informar.
					if ($sAlerta!=''){$sAlerta=$sAlerta.'<br>';}
					$sAlerta=$sAlerta.'A las siguientes actividades se les cambio la fecha de cierre en el aula '.$numaula.'';
					while ($fila=$objDB->sf($tabla)){
						$sAlerta=$sAlerta.'<br><b>'.cadena_notildes($fila['ofer04nombre']).'</b>';
						}
					$sSQL='UPDATE ofer18cargaxnavxdia SET ofer18fechacierrre='.$filadia['ofer19fecha'].' WHERE ofer18per_aca='.$idperaca.' AND ofer18curso='.$idcurso.' AND ofer18numaula='.$numaula.' AND ofer18fechacierrre="00/00/0000"';
					$result=$objDB->ejecutasql($sSQL);
					}
				}
			}
		if ($sError==''){
			$bPasa=false;
			if ($numaula==1){
				//Si es el aula 1 ver que las fechas de cierre no sobrepasen los topes para el d�a.
				$bPasa=true;
				if ($idperaca<289){$bPasa=false;}
				}
			if (!$bControlaFechas){$bPasa=false;}
			if ($bPasa){
				$bFalla=false;
				$sNavNombre='{'.$idNav.'}';
				$iNavTope=15000;
				$sNavError='';
				$sSQL='SELECT unad39numestudiantes, unad39nombre FROM unad39nav WHERE unad39id='.$idNav.'';
				$tabla=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla)>0){
					$fila=$objDB->sf($tabla);
					$sNavNombre=$fila['unad39nombre'];
					$iNavTope=$fila['unad39numestudiantes'];
					}
				$sFechas='"NA"';
				$sSQL='SELECT ofer18fechacierrre FROM ofer18cargaxnavxdia WHERE ofer18curso='.$idcurso.' AND ofer18per_aca='.$idperaca.' AND ofer18numaula='.$numaula.' GROUP BY ofer18fechacierrre';
				$tabla=$objDB->ejecutasql($sSQL);
				while ($fila=$objDB->sf($tabla)){
					$sFechas=$sFechas.', "'.$fila['ofer18fechacierrre'].'"';
					}
				//Las fecha base 0 y 3 - 75% y Actividades finales POA
				$sSQL='SELECT ofer18fechacierrre, SUM(ofer17numestudiantes) as total FROM vofer17carga2 WHERE ofer17nav='.$idNav.' AND ofer18fechacierrre IN ('.$sFechas.') AND ofer02fechabase IN (0, 3) GROUP BY ofer18fechacierrre';
				$tabla=$objDB->ejecutasql($sSQL);
				while ($fila=$objDB->sf($tabla)){
					$iBaseFecha=$fila['total'];
					if ($iBaseFecha>$iNavTope){
						$bFalla=true;
						$sNavError=$sNavError.'<br>'.$fila['ofer18fechacierrre'].' Actividades:';
						//Informar que actividades se tienen para ese d�a.
						$sSQL='SELECT T4.ofer04nombre FROM ofer18cargaxnavxdia AS TB, ofer04cursoactividad AS T4 WHERE TB.ofer18curso='.$idcurso.' AND TB.ofer18per_aca='.$idperaca.' AND TB.ofer18numaula='.$numaula.' AND TB.ofer18fechacierrre="'.$fila['ofer18fechacierrre'].'" AND TB.ofer18idactividad=T4.ofer04id';
						$tabladet=$objDB->ejecutasql($sSQL);
						while ($filadet=$objDB->sf($tabladet)){
							$sNavError=$sNavError.' '.$filadet['ofer04nombre'];
							}
						}
					}
				if ($bFalla){
					$sSQL='DELETE FROM ofer18cargaxnavxdia WHERE ofer18curso='.$idcurso.' AND ofer18per_aca='.$idperaca.' AND ofer18numaula='.$numaula;
					$result=$objDB->ejecutasql($sSQL);
					if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Reversando porque supera los topes del NAV {'.$sSQL.'}.<br>';}
					$sError='Las siguientes fechas de cierre superan los topes de concurrencia de estudiantes permitidos ('.$iNavTope.' estudiantes para el NAV '.$sNavNombre.'), por favor ajuste las actividades para que las fechas de cierre se den en fechas menos concurridas.<br>Las fechas que debe modificar son: '.$sNavError;
					}
				}
			}
		if ($sError==''){
			//Ajustar las fechas Retro que no alcancen a estar en el periodo.
			$sSQL='SELECT ofer18fase, ofer18unidad, ofer18idactividad, ofer18diasretro 
FROM ofer18cargaxnavxdia AS TB 
WHERE ofer18curso='.$idcurso.' AND ofer18per_aca='.$idperaca.' AND ofer18numaula='.$numaula.' AND ofer18fecharetro="00/00/0000"';
			$tabla=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla)>0){
				//Encontrar el d�a 1
				$sSQL='SELECT ofer19fecha FROM ofer19diasperaca WHERE ofer19per_aca='.$idperaca.' AND ofer19numdia=1';
				$tabladia=$objDB->ejecutasql($sSQL);
				$filadia=$objDB->sf($tabladia);
				$sDia1=$filadia['ofer19fecha'];
				while ($fila=$objDB->sf($tabla)){
					//Calcular el d�a y guardarlo.
					$sDia=fecha_sumardias($sDia1, $fila['ofer18diasretro']);
					$sSQL='UPDATE ofer18cargaxnavxdia SET ofer18fecharetro="'.$sDia.'" WHERE ofer18curso='.$idcurso.' AND ofer18per_aca='.$idperaca.' AND ofer18numaula='.$numaula.' AND ofer18fase='.$fila['ofer18fase'].' AND ofer18unidad='.$fila['ofer18unidad'].' AND ofer18idactividad='.$fila['ofer18idactividad'].'';
					$result=$objDB->ejecutasql($sSQL);
					}
				}
			}
		}
	if (($sError!='')&&($sAlerta!='')){$sAlerta='<br>'.$sAlerta;}
	$sError=$sError.$sAlerta;
	if ($numaula==1){
		//Crear las agendas de las aulas adicionales.
		$sSQL='SELECT unad48consec, unad48numestudiantes FROM unad48cursoaula WHERE unad48per_aca='.$idperaca.' AND unad48idcurso='.$idcurso.'';
		$tablaaulas=$objDB->ejecutasql($sSQL);
		while ($filaaula=$objDB->sf($tablaaulas)){
			if ($filaaula['unad48numestudiantes']>0){
				list($sAlerta, $sDebugB)=OAI_ArmarAgendaV2($idperaca, $idcurso, $filaaula['unad48consec'], $bForzar, $objDB, true, $bDebug);
				if ($sAlerta!=''){
					if ($sError!=''){$sError=$sError.'<br>';}
					$sError=$sError.$sAlerta;
					}
				}
			}
		}
	return array($sError, $sDebug);
	}
//arma los dias que se usaran en un per_aca
function OAI_ArmarDias($idperaca, $objDB){
	//marcamos lo anterior a num dia -2.
	//verificamos fecha inicial y final
	//buscamos el dia inicial.
	//eliminamos TODOS los dias ... SEGUN VERIFICACION.
	//montamos los dias que falten.
	//calculamos el numdia.
	//----
	$sError='';
	//verificamos fecha inicial y final
	$sSQL='SELECT ofer14fechaini60, ofer14fechafinhabilitacion FROM ofer14per_acaparams WHERE ofer14idper_aca='.$idperaca;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)==0){
		$sError='No se han definido las fechas del periodo';
		}else{
		$fila=$objDB->sf($tabla);
		$sfechaini=$fila['ofer14fechaini60'];
		$sfechafin=$fila['ofer14fechafinhabilitacion'];
		if (!fecha_esvalida($sfechaini)){
			$sError='Fecha Inicial Errada {'.$sfechaini.'}';
			}
		if (!fecha_esvalida($sfechafin)){
			$sError='Fecha Final Errada {'.$sfechafin.'}';
			}
		}
	if ($sError==''){
		$binserta=true;
		$belimina=false;
		//buscamos el dia inicial.
		//la estrategia va a ser ver si tenemos que insertar o si tenemos que eliminar... vemos que el dia inicial y final existan.
		$sSQL='SELECT ofer19orden FROM ofer19diasperaca WHERE ofer19fecha="'.$sfechaini.'" AND ofer19per_aca='.$idperaca;
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			//se encontro el dia, verificar que sea el primero
			$fila=$objDB->sf($tabla);
			if ($fila['ofer19orden']!=1){
				$belimina=true;
				}else{
				//revisar el dia final
				$sSQL='SELECT ofer19orden FROM ofer19diasperaca WHERE ofer19fecha="'.$sfechafin.'" AND ofer19per_aca='.$idperaca;
				$tabla=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla)>0){
					//todos los dias estan, asi que no hay que insertar.
					$binserta=false;
					$sSQL='DELETE FROM ofer19diasperaca WHERE STR_TO_DATE(ofer19fecha,"%d/%m/%Y")>STR_TO_DATE("'.$sfechafin.'","%d/%m/%Y") AND ofer19per_aca='.$idperaca;
					$result=$objDB->ejecutasql($sSQL);
					}else{
					//no encontro el dia final.
					$belimina=true;
					}
				}
			}else{
			//no se encontro el dia eliminar.
			$belimina=true;
			}
		if ($belimina){
			//eliminamos TODOS los dias ...
			$sSQL='DELETE FROM ofer19diasperaca WHERE ofer19per_aca='.$idperaca;
			$result=$objDB->ejecutasql($sSQL);
			}
		if ($binserta){
			//montamos los dias .
			$orden=2;
			$idiasemana=fecha_diasemana($sfechaini);
			$sinsert='INSERT INTO ofer19diasperaca (ofer19per_aca, ofer19orden, ofer19fecha, ofer19festivo, ofer19numdia, ofer19diasem) VALUES ('.$idperaca.', 1, "'.$sfechaini.'", "N", -2, '.$idiasemana.')';
			$sfecha=fecha_sumardias($sfechaini, 1);
			$idiasemana=fecha_diasemana($sfecha);
			while (!fecha_esmayor($sfecha, $sfechafin)){
				$sFestivo='N';
				$snumdia=-2;
				switch (substr($sfecha,0,5)){
					case '01/01':
					case '01/05':
					case '20/07':
					case '07/08':
					case '12/10':
					case '11/11':
					$sFestivo='S';
					$snumdia=-1;					
					}
				$sinsert=$sinsert.', ('.$idperaca.', '.$orden.', "'.$sfecha.'", "'.$sFestivo.'", '.$snumdia.', '.$idiasemana.')';
				$orden++;
				$sfecha=fecha_sumardias($sfecha, 1);
				$idiasemana++;
				if ($idiasemana>6){$idiasemana=0;}
				}
			$result=$objDB->ejecutasql($sinsert);
			}
		//calculamos el numdia.
		if ($binserta||$belimina){
			//marcamos lo anterior a num dia -2.
			$sSQL='UPDATE ofer19diasperaca SET ofer19numdia=-2 WHERE ofer19numdia>=0 AND ofer19per_aca='.$idperaca;
			$result=$objDB->ejecutasql($sSQL);
			OAI_DiasCalcular($idperaca, $objDB);
			}
		}
	return $sError;
	}
//calcular el orden que le corresponde a los dias.
function OAI_DiasCalcular($idperaca, $objDB){
	$numdia=1;
	$sSQL='SELECT ofer19orden FROM ofer19diasperaca WHERE ofer19per_aca='.$idperaca.' AND ofer19numdia=-2 ORDER BY ofer19orden';
	$tabla=$objDB->ejecutasql($sSQL);
	while ($fila=$objDB->sf($tabla)){
		$sSQL='UPDATE ofer19diasperaca SET ofer19numdia='.$numdia.' WHERE  ofer19per_aca='.$idperaca.' AND ofer19orden='.$fila['ofer19orden'];
		$result=$objDB->ejecutasql($sSQL);
		$numdia++;
		}
	}
// Cerrar la evaluacion por evaluador
function OAI_EvaluacionCerrarEvaluador($idEvaluacion, $objDB, $bDebug=false){
	//Noviembre 20 de 2014 se incluye parametro de paraevaluar
	$sError='';
	$sDebug='';
	$iAprueba=0;
	$iMinimo=100;
	$iTipo=0;
	$sSQL='SELECT TB.ofer27id 
FROM ofer27evalcursoitem AS TB, ofer23itemevaluacion AS T2 
WHERE ofer27idevalcurso='.$idEvaluacion.' AND ofer27paraevaluar="S" AND ofer27respuesta=-1 AND TB.ofer27iditem=T2.ofer23id AND T2.ofer23momento=0';
	$result=$objDB->ejecutasql($sSQL);
	if ($sDebug){$sDebug=$sDebug.fecha_microtiempo().' Revisando respuestas de la evaluacion '.$idEvaluacion.' '.$sSQL.'<br>';}
	$iNoResueltas=$objDB->nf($result);
	if ($iNoResueltas>0){
		$sError='La evaluaci&oacute;n contiene '.$iNoResueltas.' preguntas sin resolver.';
		}
	if ($sError==''){
		//Saber el m�mino para saber si aprueba o no.
		$sSQL='SELECT ofer26minimo, ofer26tipoevaluacion FROM ofer26evaluacioncurso WHERE ofer26id='.$idEvaluacion;
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$iMinimo=$fila['ofer26minimo'];
			$iTipo=$fila['ofer26tipoevaluacion'];
			}else{
			$sError='Evaluaci&oacute;n NO Encontrada Ref {'.$idEvaluacion.'}';
			}
		}
	if ($sError==''){
		//Calcular la nota.
		$iPorc=0;
		$iPeso=0;
		$iPuntaje=0;
		$iResueltas=0;
		$sSQL='SELECT T2.ofer23tiporespuesta, T2.ofer23peso, TB.ofer27respuesta 
FROM ofer27evalcursoitem AS TB, ofer23itemevaluacion AS T2 
WHERE TB.ofer27idevalcurso='.$idEvaluacion.' AND TB.ofer27paraevaluar="S" AND TB.ofer27iditem=T2.ofer23id AND T2.ofer23momento=0';
		$result=$objDB->ejecutasql($sSQL);
		while ($fila=$objDB->sf($result)){
			$iPeso=$iPeso+$fila['ofer23peso'];
			if ($fila['ofer27respuesta']>-1){$iResueltas=$iResueltas+$fila[0];}
			switch ($fila['ofer23tiporespuesta']){
				case 1://aceptada, no acetada.
				if ($fila['ofer27respuesta']==1){
					$iPuntaje=$iPuntaje+$fila['ofer23peso'];
					}
				break;
				case 2://bajo, medio alto
				if ($fila['ofer27respuesta']==1){
					$iPuntaje=$iPuntaje+($fila['ofer23peso']/2);
					}
				if ($fila['ofer27respuesta']==2){
					$iPuntaje=$iPuntaje+($fila['ofer23peso']);
					}
				}
			}
		if ($iPeso==0){
			$iPorc=100;
			}else{
			$iPorc=round(($iPuntaje/$iPeso)*100,2);
			}
		$sHoy=fecha_hoy();
		$sSQLadd='';
		if ($iPorc<$iMinimo){
			$iAprueba=-1;
			$sSQLadd=', ofer26cerradaacreditador="S"';
			//$sError=$iPorc.'<'.$iMinimo;
			}else{
			$iAprueba=1;
			if ($iTipo==1){
				$sSQLadd=', ofer26cerradaacreditador="S"';
				}
			}
		$sSQL='UPDATE ofer26evaluacioncurso SET ofer26numrespuestas='.$iResueltas.', ofer26porcentaje='.$iPorc.', ofer26idactor='.$_SESSION['unad_id_tercero'].', ofer26fecha="'.$sHoy.'", ofer26cerrada="S" '.$sSQLadd.' WHERE ofer26id='.$idEvaluacion;
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError='Falla al intentar cerrar la evaluaci&oacute;n ..<!-- '.$sSQL.' -->';
			$iAprueba=0;
			}
		}
	return array($sError, $iAprueba, $sDebug);
	}
// Cerrar la evaluacion por acreditador
function OAI_EvaluacionCerrarAcreditador($idEvaluacion, $objDB, $bDebug=false){
	list($sError, $iAprueba, $sDebug)=OAI_EvaluacionCerrarAcreditadorV2($idEvaluacion, $_SESSION['unad_id_tercero'], $objDB, $bDebug=false);
	return array($sError, $iAprueba, $sDebug);
	}
function OAI_EvaluacionCerrarAcreditadorV2($idEvaluacion, $idTercero, $objDB, $bDebug=false){
	//Noviembre 20 de 2014 se agrega el parametro paraacreditar
	$sError='';
	$sDebug='';
	$iAprueba=0;
	$iMinimo=100;
	$sSQL='SELECT TB.ofer27id 
FROM ofer27evalcursoitem AS TB, ofer23itemevaluacion AS T2 
WHERE TB.ofer27idevalcurso='.$idEvaluacion.' AND TB.ofer27paraacreditar="S" AND TB.ofer27respacredita=-1 AND TB.ofer27iditem=T2.ofer23id AND T2.ofer23momento=1';
	if ($sDebug){$sDebug=$sDebug.fecha_microtiempo().' Revisando respuestas de la evaluacion '.$idEvaluacion.' '.$sSQL.'<br>';}
	$result=$objDB->ejecutasql($sSQL);
	$iNoResueltas=$objDB->nf($result);
	if ($iNoResueltas>0){
		$sError='La evaluaci&oacute;n contiene '.$iNoResueltas.' preguntas sin resolver.';
		}
	if ($sError==''){
		//Saber el m�mino para saber si aprueba o no.
		$sSQL='SELECT ofer26minimo FROM ofer26evaluacioncurso WHERE ofer26id='.$idEvaluacion;
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$iMinimo=$fila['ofer26minimo'];
			}else{
			$sError='Evaluaci&oacute;n NO Encontrada Ref {'.$idEvaluacion.'}';
			}
		}
	if ($sError==''){
		//Calcular la nota de acreditacion.
		$iPorc=0;
		$iPeso=0;
		$iPuntaje=0;
		$iResueltas=0;
		$sSQL='SELECT T2.ofer23tiporespuesta, T2.ofer23peso, TB.ofer27respacredita 
FROM ofer27evalcursoitem AS TB, ofer23itemevaluacion AS T2 
WHERE TB.ofer27idevalcurso='.$idEvaluacion.' AND TB.ofer27paraacreditar="S" AND TB.ofer27iditem=T2.ofer23id AND T2.ofer23momento=1';
		$result=$objDB->ejecutasql($sSQL);
		while ($fila=$objDB->sf($result)){
			$iPeso=$iPeso+$fila['ofer23peso'];
			if ($fila['ofer27respacredita']>-1){$iResueltas=$iResueltas+$fila[0];}
			switch ($fila['ofer23tiporespuesta']){
				case 1://aceptada, no acetada.
				if ($fila['ofer27respacredita']==1){
					$iPuntaje=$iPuntaje+$fila['ofer23peso'];
					}
				break;
				case 2://bajo, medio alto
				if ($fila['ofer27respacredita']==1){
					$iPuntaje=$iPuntaje+($fila['ofer23peso']/2);
					}
				if ($fila['ofer27respacredita']==2){
					$iPuntaje=$iPuntaje+($fila['ofer23peso']);
					}
				}
			}
		if ($iPeso==0){
			$iPorc=100;
			}else{
			$iPorc=round(($iPuntaje/$iPeso)*100,2);
			}
		$sHoy=fecha_hoy();
		if ($iPorc>=$iMinimo){
			$iAprueba=-1;
			}else{
			$iAprueba=1;
			}
		$sSQL='UPDATE ofer26evaluacioncurso SET ofer26porcacreditador='.$iPorc.', ofer26idacreditador='.$idTercero.', ofer26fechaacredita="'.$sHoy.'", ofer26cerradaacreditador="S" WHERE ofer26id='.$idEvaluacion;
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError='Falla al intentar cerrar la evaluaci&oacute;n ..<!-- '.$sSQL.' -->';
			$iAprueba=0;
			}
		}
	return array($sError, $iAprueba, $sDebug);
	}
// Omitir la evaluacion
function OAI_EvaluacionOmitir($idEvaluacion, $objDB){
	$sError='';
	$sSQL='SELECT ofer26minimo FROM ofer26evaluacioncurso WHERE ofer26id='.$idEvaluacion;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)==0){
		$sError='Evaluaci&oacute;n NO Encontrada Ref {'.$idEvaluacion.'}';
		}
	if ($sError==''){
		//Calcular la nota.
		$sHoy=fecha_hoy();
		$sSQL='UPDATE ofer26evaluacioncurso SET ofer26numrespuestas=0, ofer26porcentaje=0, ofer26idactor=-1, ofer26fecha="'.$sHoy.'", ofer26cerrada="S" WHERE ofer26id='.$idEvaluacion;
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError='Falla al intentar cerrar la evaluaci&oacute;n ..<!-- '.$sSQL.' -->';
			}
		}
	return array($sError);
	}
// Iniciar una evaluacion
function OAI_EvaluacionIniciarV2($idperaca, $idcurso, $idactor, $iTipoEvaluacion, $objDB, $bDebug=false){
	//Noviembre 20 Se incluyen los campos paraevaluar y paraacreditar.
	//Abril 22 de 2015 Se selecciona la rubrica segun sea, acreditaci�n o certificaci�n.
	//Diciembre 12 de 2016 si es tipo evaluacion 1 no se evaluan los cierres de los demas.
	$numpreguntas=0;
	$idevaluacion=0;
	$sError='';
	$sDebug='';
	$iTipoError=0;
	// Ver que no tenga una rubrica ya abierta.
	if ($iTipoEvaluacion==0){
		$sSQL='SELECT ofer26id FROM ofer26evaluacioncurso WHERE ofer26idcurso='.$idcurso.' AND ofer26idper_aca='.$idperaca.' AND ofer26cerradaacreditador<>"S" AND ofer26tipoevaluacion=0';
		$result=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($result)>0){
			$sError='Existe una evaluaci&oacute;n abierta, no se permite iniciar otra {Hasta que el acreditador no cierre las que estan abiertas.}.';
			}
		}else{
		$sSQL='SELECT ofer26id FROM ofer26evaluacioncurso WHERE ofer26idcurso='.$idcurso.' AND ofer26idper_aca='.$idperaca.' AND ofer26cerrada<>"S" AND ofer26tipoevaluacion=1';
		$result=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($result)>0){
			$sError='Existe una autoevaluaci&oacute;n abierta, no se permite iniciar otra.';
			$iTipoError=1;
			}
		}
	//Buscar la rubrica a aplicar.
	$iMinimo=100;
	if ($sError==''){
		//Antes de definir la rubrica toca revisar si el proceso es acreditar o certificar
		$bAcredita=true;
		$sSQL='SELECT ofer08obligaacreditar FROM ofer08oferta WHERE ofer08idcurso='.$idcurso.' AND ofer08idper_aca='.$idperaca.' AND ofer08cead=0';
		$result=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($result)>0){
			$fila=$objDB->sf($result);
			if ($fila['ofer08obligaacreditar']!='S'){$bAcredita=false;}
			}
		$sSQL='SELECT unad40idevaluacion, unad40idrubricacertifica FROM unad40curso WHERE unad40id='.$idcurso.'';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Buscando rubrica '.$sSQL.'<br>';}
		$result=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($result)>0){
			$fila=$objDB->sf($result);
			if ($bAcredita){
				$idevaluacion=$fila['unad40idevaluacion'];
				}else{
				$idevaluacion=$fila['unad40idrubricacertifica'];
				}
			if ($idevaluacion==0){
				if ($bAcredita){
					$sError='No se ha definido la rubrica de evaluaci&oacute;n a aplicar al curso ref{'.$idcurso.'} en el proceso de acreditaci&oacute;n';
					}else{
					$sError='No se ha definido la rubrica de evaluaci&oacute;n a aplicar al curso ref{'.$idcurso.'} en el proceso de certificaci&oacute;n';
					}
				}else{
				//Sacar el minimo porcentaje.
				$sSQL='SELECT ofer24minimo FROM ofer24evaluacion WHERE ofer24id='.$idevaluacion.'';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)>0){
					$fila=$objDB->sf($result);
					$iMinimo=$fila['ofer24minimo'];
					}else{
					$sError='No se encuentra la evaluaci&oacute;n definida para el curso ref{'.$idcurso.' - '.$idevaluacion.'}';
					}
				}
			}else{
			$sError='No se ha encontrado el curso ref {'.$idcurso.'}';
			}
		}
	if ($sError==''){
		$sSQL='SELECT COUNT(ofer25id) FROM ofer25evaluacioncuerpo WHERE ofer25idevaluacion='.$idevaluacion.'';
		$result=$objDB->ejecutasql($sSQL);
		$fila=$objDB->sf($result);
		$numpreguntas=$fila[0];
		}
	if ($sError==''){
		$scampos26='ofer26idcurso, ofer26consec, ofer26id, ofer26idevaluacion, ofer26idper_aca, ofer26fecha, ofer26idactor, ofer26numpreguntas, ofer26numrespuestas, ofer26porcentaje, ofer26cerrada, ofer26fechaacredita, ofer26idacreditador, ofer26porcacreditador, ofer26cerradaacreditador, ofer26minimo, ofer26idevalprevia, ofer26tipoevaluacion';
		$scampos27='ofer27idevalcurso, ofer27iditem, ofer27id, ofer27orden, ofer27respuesta, ofer27retroalimentacion, ofer27respacredita, ofer27retroacredita, ofer27tiporespuesta, ofer27paraevaluar, ofer27paraacreditar';
		$svalores27='';
		//Si es un rubrica de certificacion cerramos el proceso de evaluador y dejamos solo certificador.
		$sRespuestasEvalua=0;
		$sPorcentajeEvalua=0;
		$sCerradaEvalua='N';
		if (!$bAcredita){
			if ($iTipoEvaluacion==0){
				$sRespuestasEvalua=-1;
				$sPorcentajeEvalua=100;
				$sCerradaEvalua='S';
				}
			}
		$orden=1;
		$consec26=tabla_consecutivo('ofer26evaluacioncurso', 'ofer26consec', 'ofer26idcurso='.$idcurso.'', $objDB);
		$id26=tabla_consecutivo('ofer26evaluacioncurso', 'ofer26id', '', $objDB);
		$svalores26=''.$idcurso.', '.$consec26.', '.$id26.', '.$idevaluacion.', '.$idperaca.', "'.fecha_hoy().'", "'.$idactor.'", '.$numpreguntas.', '.$sRespuestasEvalua.', '.$sPorcentajeEvalua.', "'.$sCerradaEvalua.'", "00/00/0000", 0, 0, "N", '.$iMinimo.', 0, '.$iTipoEvaluacion.'';
		$sSQL='INSERT INTO ofer26evaluacioncurso ('.$scampos26.') VALUES ('.$svalores26.');';
		$result=$objDB->ejecutasql($sSQL);
		$id27=tabla_consecutivo('ofer27evalcursoitem', 'ofer27id', '', $objDB);
		$sAdd='';
		if ($iTipoEvaluacion==1){
			$sAdd=' AND ofer23paraevaluar="S"';
			}
		$sSQL='SELECT ofer25iditem, ofer23tiporespuesta, ofer23paraevaluar, ofer23paraacreditar 
FROM ofer25evaluacioncuerpo, ofer23itemevaluacion 
WHERE ofer25idevaluacion='.$idevaluacion.$sAdd.' AND ofer25iditem=ofer23id 
ORDER BY ofer25orden, ofer25id';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Datos a insertar. '.$sSQL.'<br>';}
		$tabla=$objDB->ejecutasql($sSQL);
		while ($fila=$objDB->sf($tabla)){
			if ($svalores27!=''){$svalores27=$svalores27.', ';}
			$svalores27=$svalores27.'('.$id26.', '.$fila['ofer25iditem'].', '.$id27.', '.$orden.', -1, "", -1, "", '.$fila['ofer23tiporespuesta'].', "'.$fila['ofer23paraevaluar'].'", "'.$fila['ofer23paraacreditar'].'")';
			$id27++;
			$orden++;
			}
		if ($svalores27!=''){
			$sSQL='INSERT INTO ofer27evalcursoitem ('.$scampos27.') VALUES '.$svalores27.';';
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Armado de la rubrica '.$sSQL.'<br>';}
			$result=$objDB->ejecutasql($sSQL);
			}
		}
	return array($sError, $iTipoError, $sDebug);
	}
//Hacer una Reevaluacion.
function OAI_ReEvaluar($idEvaluacion, $objDB){
	list($sError, $sDebug)=OAI_ReEvaluarV2($idEvaluacion, $objDB, false);
	return $sError;
	}
function OAI_ReEvaluarV2($idEvaluacion, $objDB, $bDebug=false){
	//Diciembre 12 
	$numpreguntas=0;
	$idPrevia=0;
	$idperaca=0;
	$idcurso=0;
	$sError='';
	$sDebug='';
	$iTipoError=0;
	// Ubicar la rubrica de origen
	$sSQL='SELECT ofer26idcurso, ofer26idper_aca, ofer26idevaluacion FROM ofer26evaluacioncurso WHERE ofer26id='.$idEvaluacion.'';
	$result=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($result)>0){
		$fila=$objDB->sf($result);
		$idperaca=$fila['ofer26idper_aca'];
		$idcurso=$fila['ofer26idcurso'];
		$idPrevia=$fila['ofer26idevaluacion'];
		}else{
		$sError='No se encuentra la evaluaci&oacute;n Ref:'.$idEvaluacion;
		}
	if ($sError==''){
	// Ver que no tenga una rubrica ya abierta.
		$sSQL='SELECT ofer26id FROM ofer26evaluacioncurso WHERE ofer26idcurso='.$idcurso.' AND ofer26idper_aca='.$idperaca.' AND ofer26cerradaacreditador<>"S"';
		$result=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($result)>0){
			$sError='Existe una rubrica abierta, no se permite iniciar otra {Hasta que el acreditador no cierre las que estan abiertas.}.';
			$iTipoError=1;
			}
		}
	$iMinimo=100;
	if ($sError==''){
		//Sacar el minimo porcentaje.
		$sSQL='SELECT ofer24minimo FROM ofer24evaluacion WHERE ofer24id='.$idPrevia;
		$result=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($result)>0){
			$fila=$objDB->sf($result);
			$iMinimo=$fila['ofer24minimo'];
			}else{
			$sError='No se encuentra la evaluaci&oacute;n definida para el curso ref{'.$idcurso.' - '.$idPrevia.'}';
			}
		}
	if ($sError==''){
		$sSQL='SELECT COUNT(TB.ofer25id) AS Total FROM ofer25evaluacioncuerpo AS TB, ofer23itemevaluacion AS T1 WHERE TB.ofer25idevaluacion='.$idPrevia.' AND TB.ofer25iditem=T1.ofer23id AND T1.ofer23paraacreditar="S"';
		$result=$objDB->ejecutasql($sSQL);
		$fila=$objDB->sf($result);
		$numpreguntas=$fila[0];
		}
	if ($sError==''){
		$scampos26='ofer26idcurso, ofer26consec, ofer26id, ofer26idevaluacion, ofer26idper_aca, ofer26fecha, ofer26idactor, ofer26numpreguntas, ofer26numrespuestas, ofer26porcentaje, ofer26cerrada, ofer26fechaacredita, ofer26idacreditador, ofer26porcacreditador, ofer26cerradaacreditador, ofer26minimo, ofer26idevalprevia';
		$scampos27='ofer27idevalcurso, ofer27iditem, ofer27id, ofer27orden, ofer27respuesta, ofer27retroalimentacion, ofer27respacredita, ofer27retroacredita, ofer27tiporespuesta, ofer27paraevaluar, ofer27paraacreditar';
		$svalores27='';
		$orden=1;
		$consec26=tabla_consecutivo('ofer26evaluacioncurso', 'ofer26consec', 'ofer26idcurso='.$idcurso.'', $objDB);
		$id26=tabla_consecutivo('ofer26evaluacioncurso', 'ofer26id', '', $objDB);
		$svalores26=''.$idcurso.', '.$consec26.', '.$id26.', '.$idPrevia.', '.$idperaca.', "00/00/0000", "0", '.$numpreguntas.', 0, 100, "S", "'.date('d/m/Y').'", '.$_SESSION['unad_id_tercero'].', 0, "N", '.$iMinimo.', '.$idEvaluacion.'';
		$sSQL='INSERT INTO ofer26evaluacioncurso ('.$scampos26.') VALUES ('.$svalores26.');';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError='Fallo al insertar '.$sSQL;
			}
		$id27=tabla_consecutivo('ofer27evalcursoitem', 'ofer27id', '', $objDB);
		$sSQL='SELECT ofer25iditem, ofer23tiporespuesta FROM ofer25evaluacioncuerpo, ofer23itemevaluacion WHERE ofer25idevaluacion='.$idPrevia.' AND ofer23paraacreditar="S" AND ofer25iditem=ofer23id ORDER BY ofer25orden, ofer25id';
		$tabla=$objDB->ejecutasql($sSQL);
		while ($fila=$objDB->sf($tabla)){
			//$scampos27='ofer27idevalcurso, ofer27iditem, ofer27id, ofer27orden, ofer27respuesta, ofer27retroalimentacion, ofer27respacredita, ofer27retroacredita, ofer27tiporespuesta, ofer27paraevaluar, ofer27paraacreditar';
			$ofer27respuesta=-1;
			$ofer27retroalimentacion='';
			$ofer27respacredita=-1;
			$ofer27retroacredita='';
			//20 de Nov de 2018 - Se busca la data anterior.
			$sSQL='SELECT ofer27respuesta, ofer27retroalimentacion, ofer27respacredita, ofer27retroacredita FROM ofer27evalcursoitem WHERE ofer27idevalcurso='.$idEvaluacion.' AND ofer27iditem='.$fila['ofer25iditem'].'';
			$tabla27=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla27)>0){
				$fila27=$objDB->sf($tabla27);
				if ($fila27['ofer27respuesta']>0){
					$ofer27respuesta=$fila27['ofer27respuesta'];
					$ofer27retroalimentacion=$fila27['ofer27retroalimentacion'];
					}
				if ($fila27['ofer27respacredita']>0){
					$ofer27respacredita=$fila27['ofer27respacredita'];
					$ofer27retroacredita=$fila27['ofer27retroacredita'];
					}
				}
			if ($svalores27!=''){$svalores27=$svalores27.', ';}
			$svalores27=$svalores27.'('.$id26.', '.$fila['ofer25iditem'].', '.$id27.', '.$orden.', '.$ofer27respuesta.', "'.$ofer27retroalimentacion.'", '.$ofer27respacredita.', "'.$ofer27retroacredita.'", '.$fila['ofer23tiporespuesta'].', "N", "S")';
			$id27++;
			$orden++;
			}
		if ($svalores27!=''){
			$sSQL='INSERT INTO ofer27evalcursoitem ('.$scampos27.') VALUES '.$svalores27.';';
			$result=$objDB->ejecutasql($sSQL);
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Insersion de datos '.$sSQL.'<br>';}
			}
		}
	return array($sError, $sDebug);
	}
// -- Información base de un curso.
function OAI_InfoCurso($idCurso, $objDB){
	$res=NULL;
	$sSQL='SELECT unad40idagenda, unad40diainical, unad40numestudiantes, unad40idnav, unad40numestaula1, unad40tipostandard, unad40idprograma, unad40idescuela FROM unad40curso WHERE unad40id='.$idCurso;
	$tablaCurso=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tablaCurso)>0){
		$res=$objDB->sf($tablaCurso);
		}
	return $res;
	}
// La oferta masiva se saca para integrar la importacion con archivo y la importacion por inicializaci�n de ciclo que basicamente hacen lo mismo
// Octubre 7 de 2014 se incluye el tipo de estandar.
// Marzo 7 de 2015 Se agrega la opci�n de enviar el idEscuela vacio, en cuyo caso lo toma de la tabla 40.
function OAI_OfertaMasiva_V2($idPeraca, $idEscuela, $idCEAD, $sListaCursos, $sListaDirs, $sListaEvals, $sOfertar, $objDB, $sListaParams=''){
	//Noviembre 18 de 2014 se incluye importacion de directores y evaluadores
	//Noviembre 18 de 2015 se agrega la posiblidad de que la lista de cursos venga con dos paramatros mas, el proceso y el metodo matricula.
	//Julio 9 de 2018 se agrega un tercer parametro adicional que es el cohorte.
	$sError='';
	$sErrDatos='';
	$scampos08='ofer08idper_aca, ofer08idcurso, ofer08cead, ofer08id, ofer08idescuela, 
ofer08estadooferta, ofer08idagenda, ofer08diainical, ofer08numestudiantes, ofer08numestaula1, 
ofer08fechaoferta, ofer08fechacancela, ofer08estadocampus, ofer08idnav, ofer08origen, 
ofer08fechasolicrestaurar, ofer08migrados, ofer08fecharestaurado, ofer08idusariorestaura, ofer08fechaaccede, 
ofer08usuarioconfirmaacceso, ofer08fechaaprobado, ofer08idaprueba, ofer08tipostandard, ofer08obligaacreditar, 
ofer08notaacredita, ofer08idnavalista, ofer08fechaestadocampus, ofer08motivocancela, ofer08procesado, 
ofer08metodomatricula, ofer08copiaidusuario, ofer08copiafecha, ofer08copiaruta, ofer08idresponsablepti, 
ofer08incluyelaboratorio, ofer08puntajelaboratorio, ofer08incluyesalida, ofer08puntajesalida, ofer08tablacidusuario, 
ofer08tablacfecha, ofer08tablacruta, ofer08idprograma, ofer08nomprograma, ofer08idcohorte, 
ofer08grupominest, ofer08grupomaxest';
	$scampos11='ofer11per_aca, ofer11idescuela, ofer11idcurso, ofer11idrol, ofer11id, ofer11idtercero, ofer11detalle, ofer11fechaacceso';
	$id08=tabla_consecutivo('ofer08oferta','ofer08id', '', $objDB);
	if ($sListaDirs!=''){
		$id11=tabla_consecutivo('ofer11actores','ofer11id', '', $objDB);
		}
	$svalores08='';
	$iProcesados=0;
	$iAct[1]=0;
	$iAct[2]=0;
	$iActU[1]=0;
	$iActU[2]=0;
	$shoy='00/00/0000';
	$iEstado=0;
	$iSituacion=0;
	if ($sOfertar=='S'){
		$shoy=fecha_hoy();
		$iEstado=1;
		$iSituacion=1;
		}
	$iNumParams=0;
	$DATA=explode(',',$sListaCursos);
	if ($sListaParams!=''){
		$DATA1=explode(',',$sListaParams);
		$iNumParams=count($DATA1);
		}
	$DATA2=explode(',',$sListaDirs);
	$DATA3=explode(',',$sListaEvals);
	$totallin=count($DATA);
	$iNumDirs=count($DATA2);
	$iNumEvals=count($DATA3);
	$aNomPrograma=array();
	//Alistamos las coortes
	$aCohorte=array();
	$sSQL='SELECT ofer52consec, ofer52id FROM ofer52cohortes WHERE ofer52idperaca='.$idPeraca.' ORDER BY ofer52consec';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$aCohorte[$fila['ofer52consec']]=$fila['ofer52id'];
		}
	for ($k=0;$k<=$totallin-1;$k++){
		$base=trim($DATA[$k]);
		$sPasa='';
		$bParaActores=false;
		$idCohorte=0;
		$bConCohorte=false;
		//Ver que no se encuentre ya procesado.
		if (isset($REV[$base])!=0){
			$sPasa='No';
			if ($sErrDatos!=''){$sErrDatos=$sErrDatos.'<br>';}
			$sErrDatos=$sErrDatos.$base.' - Importado previamente (Repetido)';
			}else{
			$REV[$base]='1';
			}
		if ($sPasa==''){
			//Averiguar si el curso esta en la escuela.
			$bEnEscuela=false;
			$sSQL='SELECT unad40idescuela FROM unad40curso WHERE unad40consec='.$base.'';
			$trevisa=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($trevisa)>0){
				$filarev=$objDB->sf($trevisa);
				if ($idEscuela==''){
					$idEscuelaCurso=$filarev['unad40idescuela'];
					$bEnEscuela=true;
					}else{
					if ($idEscuela==$filarev['unad40idescuela']){
						$idEscuelaCurso=$idEscuela;
						$bEnEscuela=true;
						}
					}
				}
			if (!$bEnEscuela){
				$sPasa='No';
				if ($sErrDatos!=''){$sErrDatos=$sErrDatos.'<br>';}
				$sErrDatos=$sErrDatos.$base.' - No encontrado para la escuela';
				}else{
				$bParaActores=true;
				}
			}
		if ($sPasa==''){
			//ver que no este ya ofertado.
			$sSQL='SELECT ofer08estadooferta, ofer08obligaacreditar, ofer08idcohorte, ofer08id, ofer08estadocampus 
FROM ofer08oferta 
WHERE ofer08idcurso='.$base.' AND ofer08idper_aca='.$idPeraca.' AND ofer08cead='.$idCEAD;
			$trevisa=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($trevisa)!=0){
				$bCambiaProceso=false;
				$sPasa='No';
				if ($sErrDatos!=''){$sErrDatos=$sErrDatos.'<br>';}
				$sErrDatos=$sErrDatos.$base.' - Ya se encuentra en la oferta de este periodo';
				//Actualizamos el proceso si es del caso..
				if ($iNumParams>0){
					$filaproceso=$objDB->sf($trevisa);
					$bCambiaProceso=false;
					$sProceso='S';
					$sMensajeCambio='';
					$sDatoCambia='';
					$sProcesoAnterior='';
					switch($filaproceso['ofer08obligaacreditar']){
						case 'S':$sProcesoAnterior='Acreditacion';break;
						case 'N':$sProcesoAnterior='Certificacion';break;
						case 'E':$sProcesoAnterior='Casos especiales';break;
						}
					$sParamsCurso=explode('|', $DATA1[$k]);
					if ($sParamsCurso[0]=='S'){$sProceso='S';$bCambiaProceso=true;$sMensajeCambio=' {Se pasa a Acreditaci&oacute;n}';$sNuevoProceso='Acreditacion';}
					if ($sParamsCurso[0]=='N'){$sProceso='N';$bCambiaProceso=true;$sMensajeCambio=' {Se pasa a Certificaci&oacute;n}';$sNuevoProceso='Certificacion';}
					if ($sParamsCurso[0]=='E'){$sProceso='E';$bCambiaProceso=true;$sMensajeCambio=' {Se pasa a Casos especiales}';$sNuevoProceso='Casos especiales';}
					if ($bCambiaProceso){
						if ($filaproceso['ofer08estadocampus']==10){$bCambiaProceso=false;}
						if ($filaproceso['ofer08estadocampus']==12){$bCambiaProceso=false;}
						}
					if ($bCambiaProceso){
						if ($filaproceso['ofer08obligaacreditar']!=$sProceso){
							$sDatoCambia='ofer08obligaacreditar="'.$sProceso.'"';
							$bCambiaProceso=true;
							$sNota='Cambia el proceso de '.$sProcesoAnterior.' a '.$sNuevoProceso.' por carga masiva';
							f1730_CambiaEstado($filaproceso['ofer08id'], $filaproceso['ofer08estadocampus'], $filaproceso['ofer08estadocampus'], $sNota, $objDB);
							}
						}
					if (isset($sParamsCurso[2])!=0){
						//Se ha indicado el cohorte.
						$iConsecCohorte=$sParamsCurso[2];
						if ($iConsecCohorte!=''){
							if ($iConsecCohorte!=0){
								if (isset($aCohorte[$iConsecCohorte])!=0){
									$idCohorte=$aCohorte[$iConsecCohorte];
									}
								}
							if ($filaproceso['ofer08idcohorte']!=$idCohorte){
								if ($sDatoCambia!=''){$sDatoCambia=$sDatoCambia.', ';}
								$sDatoCambia='ofer08idcohorte='.$idCohorte.'';
								}
							}
						}
					if ($sDatoCambia!=''){
						$sSQL='UPDATE ofer08oferta SET '.$sDatoCambia.' WHERE ofer08id='.$filaproceso['ofer08id'].'';
						$trevisa=$objDB->ejecutasql($sSQL);
						seg_auditar(1707, $_SESSION['unad_id_tercero'], 3, 0, $sDatoCambia.' [Por carga masiva]', $objDB);
						$sErrDatos=$sErrDatos.$sMensajeCambio;
						if ($bCambiaProceso){
							f1730_GestionarDirectores($filaproceso['ofer08id'], $objDB, false, true);
							}
						}
					}
				}
			}
		if ($sPasa==''){
			$sProceso='S';
			$sMetodo=0;
			if ($iNumParams>0){
				$sParamsCurso=explode('|', $DATA1[$k]);
				if ($sParamsCurso[0]=='N'){$sProceso='N';}
				if ($sParamsCurso[0]=='E'){$sProceso='E';}
				if (count($sParamsCurso)>1){
					if ($sParamsCurso[1]==1){$sMetodo=1;}
					if ($sParamsCurso[1]==3){$sMetodo=3;}
					}
				//ver si cambia el cohorte.
				if (count($sParamsCurso)>2){
					//Se ha indicado el cohorte.
					$iConsecCohorte=$sParamsCurso[2];
					if ($iConsecCohorte!=''){
						if ($iConsecCohorte!=0){
							if (isset($aCohorte[$iConsecCohorte])!=0){
								$idCohorte=$aCohorte[$iConsecCohorte];
								}
							}
						}
					}
				//Termina de revisar el coorte.
				}
			//Sacar los datos previos.
			$idagenda=0;
			$idiainical=0;
			$inumestudiantes=50;
			$iNumEstAula1=50;
			$idnav=0;
			$iEstandar=0;
			$ofer08incluyelaboratorio='N';
			$ofer08puntajelaboratorio=0;
			$ofer08incluyesalida='N';
			$ofer08puntajesalida=0;
			$ofer08idprograma=0;
			$sSQL='SELECT unad40idagenda, unad40diainical, unad40numestudiantes, unad40idnav, unad40tipostandard, unad40numestaula1, unad40idprograma, unad40incluyelaboratorio, unad40incluyesalida 
FROM unad40curso 
WHERE unad40id='.$base;
			$tabla=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla)>0){
				$fila=$objDB->sf($tabla);
				$idagenda=(int)$fila['unad40idagenda'];
				$idiainical=(int)$fila['unad40diainical'];
				$inumestudiantes=(int)$fila['unad40numestudiantes'];
				$iNumEstAula1=(int)$fila['unad40numestaula1'];
				$idnav=(int)$fila['unad40idnav'];
				$iEstandar=(int)$fila['unad40tipostandard'];
				$ofer08incluyelaboratorio=$fila['unad40incluyelaboratorio'];
				$ofer08incluyesalida=$fila['unad40incluyesalida'];
				$ofer08idprograma=$fila['unad40idprograma'];
				}
			$ofer08nomprograma='';
			if ($ofer08idprograma!=0){
				if (isset($aNomPrograma[$ofer08idprograma])==0){
					$sSQL='SELECT exte03nombre FROM exte03programa WHERE exte03id='.$ofer08idprograma;
					$tabla=$objDB->ejecutasql($sSQL);
					if ($objDB->nf($tabla)>0){
						$fila=$objDB->sf($tabla);
						$aNomPrograma[$ofer08idprograma]=$fila['exte03nombre'];
						}else{
						$aNomPrograma[$ofer08idprograma]='';
						}
					}
				$ofer08nomprograma=$aNomPrograma[$ofer08idprograma];
				}
			if ($svalores08!=''){$svalores08=$svalores08.', ';}
			$svalores08=$svalores08.'('.$idPeraca.', '.$base.', '.$idCEAD.', '.$id08.', '.$idEscuelaCurso.', 
'.$iEstado.', '.$idagenda.', '.$idiainical.', '.$inumestudiantes.', '.$iNumEstAula1.', 
"'.$shoy.'", "00/00/0000", '.$iSituacion.', '.$idnav.', "", 
"00/00/0000", "", "00/00/0000", 0, "00/00/0000", 
0, "00/00/0000", 0, '.$iEstandar.', "'.$sProceso.'", 
"", 0, "'.$shoy.'", "", 0, 
'.$sMetodo.', 0, "00/00/0000", "", 0, 
"'.$ofer08incluyelaboratorio.'", "'.$ofer08puntajelaboratorio.'", "'.$ofer08incluyesalida.'", "'.$ofer08puntajesalida.'", 0, 
"00/00/0000", "", '.$ofer08idprograma.', "'.$ofer08nomprograma.'", '.$idCohorte.', 
5, 5)';
			$id08++;
			$iProcesados++;
			}
		//Parte incluida en la versi�n 2
		if ($bParaActores){
			//se hace la carga invocando la lib1711 debido a los manejos con ncontens.
			//2 directores, 5 acreditadores.
			$sDocActor='';
			for ($j=1;$j<=2;$j++){
				switch($j){
				case 1:
				if ($iNumDirs>$k){
					$sDocActor=trim($DATA2[$k]);
					}
				$iRol=2;
				break;
				case 2:
				if ($iNumEvals>$k){
					$sDocActor=trim($DATA3[$k]);
					}
				$iRol=5;
				break;
				}
			if ($sDocActor!=''){
				list($respuesta,$id11)=tabla_terceros_info('CC', $sDocActor, $objDB);
				if ($respuesta==''){
					unad11_importar_V2($sDocActor, '', $objDB);
					list($respuesta,$id11)=tabla_terceros_info('CC', $sDocActor, $objDB);
					}
				if ($respuesta==''){
					if ($sErrDatos!=''){$sErrDatos=$sErrDatos.'<br>';}
					$sErrDatos=$sErrDatos.'Documento no registrado como usuario <b>'.$sData[2].'</b>';
					}
				if ($respuesta!=''){
					$id1711=0;
					$sDetalleUsuario='Importado '.$sHoy;
					$sSQL='SELECT ofer11id, ofer11idtercero, ofer11detalle 
FROM ofer11actores 
WHERE ofer11idcurso='.$base.' AND ofer11per_aca='.$idPeraca.' AND ofer11idescuela='.$idEscuela.' AND ofer11idrol='.$iRol.'';
					$tabla=$objDB->ejecutasql($sSQL);
					if ($objDB->nf($tabla)>0){
						$fila=$objDB->sf($tabla);
						if ($fila['ofer11idtercero']!=$id11){
							$id1711=$fila['ofer11id'];
							$sDetalleUsuario=$fila['ofer11detalle'].' - {'.$fila['ofer11idtercero'].'} Actualizado '.$sHoy;
							}else{
							$respuesta='';
							}
						}
					}
				if ($respuesta!=''){
					$valores[1]=$idPeraca;
					$valores[2]=$idEscuela;
					$valores[3]=$base;
					$valores[4]=$iRol;
					$valores[5]=$id1711;
					$valores[6]=$id11;
					$valores[7]=$sDetalleUsuario;
					list($sError, $id1711)=f1711_db_Guardar($valores, $objDB);
					}
				}
				//Fin del for j
				}
			}
		}
	if ($svalores08!=''){
		$sSQL='INSERT INTO ofer08oferta ('.$scampos08.') VALUES '.$svalores08.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError='Error al ejecutar la importacion por favor comuniquese a soporte.campus@unad.edu.co <!-- '.$sSQL.' -->';
			$svalores11='';
			}else{
			$sError='Se ha concluido el proceso de importaci&oacute;n, se han incorporado '.$iProcesados.' registros';
			}
		}
	return array($sError, $sErrDatos);
	}
//Rastrear la actividad de un actor.
function OAI_Rastro($idOferta, $idTercero, $idRol, $idAccion, $sDetalle, $objDB, $bDebug=false){
	$sError='';
	$sDebug='';
	if ($sError==''){
		//
		}
	if ($sError==''){
		$sCampos1746='ofer46idoferta, ofer46consec, ofer46id, ofer46idperaca, ofer46idcurso, ofer46idtercero, ofer46idrolactor, ofer46idestadocurso, ofer46idaccion, ofer46fecha, 
ofer46hora, ofer46minuto, ofer46detalle';
		$sValores1746=''.$idOferta.', '.$DATA['ofer46consec'].', '.$DATA['ofer46id'].', '.$DATA['ofer46idperaca'].', '.$DATA['ofer46idcurso'].', '.$DATA['ofer46idtercero'].', '.$DATA['ofer46idrolactor'].', '.$DATA['ofer46idestadocurso'].', '.$DATA['ofer46idaccion'].', "'.$DATA['ofer46fecha'].'", 
'.$DATA['ofer46hora'].', '.$DATA['ofer46minuto'].', "'.$ofer46detalle.'"';
		$sSQL='INSERT INTO ofer46actividades ('.$sCampos1746.') VALUES ('.$sValores1746.');';
		$sdetalle=$sCampos1746.'['.$sValores1746.']';
		}
	return array($sError, $sDebug);
	}
//Total de estudiantes por curso ofertado. //Mayo 20 de 2015 - Se actualiza solo si es necesario.
function OAI_TotalEstudiantes_Actualizar($id08, $objDB){
	//Mayo 17 de 2016 se agrega actualizacion a la tabla 17
	$iRes=0;
	$sSQL='SELECT ofer08numestudiantes, ofer08numestaula1, ofer08idper_aca, ofer08idcurso FROM ofer08oferta WHERE ofer08id='.$id08;
	$result=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($result)>0){
		$filaest=$objDB->sf($result);
		$iRes=$filaest['ofer08numestaula1'];
		$idPeraca=$filaest['ofer08idper_aca'];
		$idCurso=$filaest['ofer08idcurso'];
		$sSQL='UPDATE ofer17cargaxnav SET ofer17numestudiantes='.$iRes.' WHERE ofer17curso='.$idCurso.' AND ofer17per_aca='.$idPeraca.' AND ofer17numaula=1';
		$result=$objDB->ejecutasql($sSQL);
		$sSQL='SELECT unad48consec, unad48numestudiantes FROM unad48cursoaula WHERE unad48idcurso='.$idCurso.' AND unad48per_aca='.$idPeraca.'';
		$tabla48=$objDB->ejecutasql($sSQL);
		while($filaest=$objDB->sf($tabla48)){
			$iRes=$iRes+$filaest['unad48numestudiantes'];
			$sSQL='UPDATE ofer17cargaxnav SET ofer17numestudiantes='.$filaest['unad48numestudiantes'].' WHERE ofer17curso='.$idCurso.' AND  ofer17per_aca='.$idPeraca.' AND ofer17numaula='.$filaest['unad48consec'];
			$result=$objDB->ejecutasql($sSQL);
			}
		if ($iRes!=$filaest['ofer08numestudiantes']){
			$sSQL='UPDATE ofer08oferta SET ofer08numestudiantes='.$iRes.' WHERE ofer08id='.$id08;
			$result=$objDB->ejecutasql($sSQL);
			}
		}
	return $iRes;
	}
//Abril 22 de 2015 Verificar el id del curso
function OAI_TraerIdCursoMoodle($peraca, $idCurso, $idAula, $id08, $objDB){
	$idMoodle=0;
	$aIden=array('','A','B','C','D','E','F','G','H');
	$sNombre=$idCurso.$aIden[$idAula].'_'.$peraca;
	$sSQL='SELECT courseid FROM edu_courses2013 WHERE shortname="'.$sNombre.'" LIMIT 0,1';
	$tablae=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tablae)>0){
		$filae=$objDB->sf($tablae);
		$idMoodle=$filae['courseid'];
		if (($idAula==1)&&($id08!=0)){
			$sSQL='UPDATE ofer08oferta SET ofer08idcursonav='.$idMoodle.' WHERE ofer08id='.$id08;
			$tablae=$objDB->ejecutasql($sSQL);
			}
		}
	return $idMoodle;
	}
//Mayo 20 de 2015 Traer el numero de estudiantes para un curso.
function OAI_TraerNumEstudiantesCursoMoodle($peraca, $idCurso, $idAula, $objDB){
	$iNumEst=0;
	$aIden=array('','A','B','C','D','E','F','G','H');
	$sNombre=$idCurso.$aIden[$idAula].'_'.$peraca;
	//SELECT COUNT(idnumber) FROM edu_courses2013 WHERE shortname="90008A_220" AND role IN ("Estudiante")
	$sSQL='SELECT COUNT(idnumber) AS total FROM edu_courses2013 WHERE shortname="'.$sNombre.'" AND role IN ("Estudiante")';
	$tablae=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tablae)>0){
		$filae=$objDB->sf($tablae);
		$iNumEst=$filae['total'];
		}
	if ($iNumEst<1){$iNumEst=50;}
	return $iNumEst;
	}
//Revisar los grupos para el usuario.
//Enero 29 de 2014 - Se ajusta la carga de perfiles de los actores toda vez que solo lo hacia para el peraca que estaba en sesion, pero como ahora tenemos multiples peracas activos tiene que cargar para todos.
function OAI_Verificar_Grupos($objDB){
	list($sError, $sDebug)=OAI_Verificar_GruposV2($_SESSION['unad_id_tercero'], $objDB);
	return $sError;
	}
function OAI_Verificar_GruposV2($idTercero, $objDB, $bDebug=false){
	$sError='';
	$sDebug='';
	if (isset($_SESSION['oai_per_aca'])==0){$_SESSION['oai_per_aca']='';}
	if ($_SESSION['oai_per_aca']!=''){
		//si existe un periodo academico.
		//TB.ofer11idescuela, TB.ofer11idcurso, TB.ofer11idrol,
		$sSQL='SELECT T1.ofer10claserol 
FROM ofer11actores AS TB, ofer10rol AS T1, exte02per_aca AS T2 
WHERE TB.ofer11idtercero='.$idTercero.' AND TB.ofer11per_aca=T2.exte02id AND T2.exte02vigente="S" AND TB.ofer11idrol=T1.ofer10id 
GROUP BY T1.ofer10claserol';
		//echo $sSQL;
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' REVISAR ROLES OAI: Roles del usuario '.$sSQL.'.<br>';}
		$troles=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($troles)>0){
			$sSQL='SELECT ofer01perfiladmin, ofer01perfilcoordinador, ofer01perfildecano, ofer01perfildirector, ofer01perfilrevisor, ofer01perfilacreditador 
FROM ofer01params 
WHERE ofer01per_aca='.$_SESSION['oai_per_aca'].'';
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' REVISAR ROLES OAI: Perfiles que se aplicaran '.$sSQL.'.<br>';}
			$tperfiles=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tperfiles)>0){
				$fperfiles=$objDB->sf($tperfiles);
				}else{
				$sError='Se ha modificado el periodo acad&eacute;mico, no es posible procesar la solicitud {Periodo solicitado '.$_SESSION['oai_per_aca'].'}';
				}
			}
		if ($sError==''){
			while ($froles=$objDB->sf($troles)){
				//encontrar el perfil.
				$idperfil=0;
				$scampo='';
				$sNomClaseRol='{'.$froles['ofer10claserol'].'}';
				switch ($froles['ofer10claserol']){
					case 1:$scampo='ofer01perfiladmin';break;
					case 2:$scampo='ofer01perfilcoordinador';break;
					case 3:$scampo='ofer01perfildecano';break;
					case 4:$scampo='ofer01perfilrevisor';$sNomClaseRol='Director de curso';break;
					case 5:$scampo='ofer01perfilacreditador';$sNomClaseRol='Evaluador';break;
					case 8:$scampo='ofer01perfildirector';break;
					}
				if ($scampo!=''){
					$idperfil=$fperfiles[$scampo];
					}
				if ($idperfil!=0){
					if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' REVISAR ROLES OAI: Activando Clase rol '.$froles['ofer10claserol'].' - '.$sNomClaseRol.' perfil '.$idperfil.'.<br>';}
					//incluir en la tabla de perfiles.
					login_activaperfil($idTercero, $idperfil, 'S', $objDB);
					}else{
					if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' REVISAR ROLES OAI: <span class="rojo">No se ha asociado un perfil para la Clase rol '.$froles['ofer10claserol'].' - '.$sNomClaseRol.' '.$scampo.'</span>.<br>';}
					}
				}
			}
		//Coordinadores van sobre la tabla 31
		$idPerfilCoordinador=1702;
		$sSQL='SELECT ofer31idprograma FROM ofer31programacoordinador WHERE ofer31per_aca='.$_SESSION['oai_per_aca'].' AND ofer31idcoordinador='.$idTercero.'';
		$tperfiles=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tperfiles)>0){
			login_activaperfil($idTercero, $idPerfilCoordinador, 'S', $objDB);
			}
		}else{
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' REVISAR ROLES OAI: No hay seleccionado un periodo academico.<br>';}
		}
	return array($sError, $sDebug);
	}
//
function OFER_NumEscuelas($objDB){
	return OFER_NumEscuelasV2($_SESSION['unad_id_tercero'], $objDB);
	}
function OFER_NumEscuelasV2($idTercero, $objDB, $bDebug=false){
	$iNumEscuelas=-1;
	$sListaEscuelas='';
	$iPrimerEscuela='';
	$sDebug='';
	if ($idTercero==0){
		$idTercero=$_SESSION['unad_id_tercero'];
		}
	//Ver si tiene permisos para administrar escuelas...
	if (!seg_revisa_permisoV2(1707, 1701, $idTercero, $objDB)){
		$iNumEscuelas=0;
		$sCondiPeraca='';
		if (isset($_SESSION['oai_per_aca'])!=0){
			$sCondiPeraca='ofer11per_aca='.$_SESSION['oai_per_aca'].' AND ';
			}
		//si no tiene el permiso 1701 hay que comprobar a que escuelas tiene permisos.
		$sSQL='SELECT ofer11idescuela FROM ofer11actores WHERE '.$sCondiPeraca.' ofer11idtercero='.$idTercero.' GROUP BY ofer11idescuela ORDER BY ofer11idescuela';
		$tabla=$objDB->ejecutasql($sSQL);
		while ($fila=$objDB->sf($tabla)){
			$sListaEscuelas=$sListaEscuelas.', '.$fila['ofer11idescuela'];
			$iNumEscuelas++;
			if ($fila['ofer11idescuela']==-1){
				$sListaEscuelas='-1';
				$iNumEscuelas=-1;
				break;
				}else{
				if ($iPrimerEscuela==''){$iPrimerEscuela=$fila['ofer11idescuela'];}
				}
			}
		}
	return array($iNumEscuelas, $sListaEscuelas, $iPrimerEscuela, $sDebug);
	}
//tabla de actores
//Enero 26 de 2018, esta funcion se deprecia.....
function f1711_TablaDetalle($aParametros, $objDB){
	require './app.php';
	$mensajes_1711=$APP->rutacomun.'lg/lg_1711_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1711)){$mensajes_1711=$APP->rutacomun.'lg/lg_1711_es.php';}
	require $mensajes_1711;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$babierta=true;
	$sSQLadd='';
	$sSQLadd1='';
	if (isset($aParametros[99])==0){$aParametros[99]='';}
	if (isset($aParametros[103])==0){$aParametros[103]='';}
	if (isset($aParametros[104])==0){$aParametros[104]=0;}
	if ($aParametros[99]==1){
		$babierta=false;
		}
	if ($aParametros[103]!=''){
		$sSQLadd1=$sSQLadd1.' AND TB.ofer11idcurso='.$aParametros[103];
		}else{
		//ver si forza el curso
		if ($aParametros[104]==1){
			$sSQLadd1=$sSQLadd1.' AND TB.ofer11idcurso=-99';
			}
		}
	if (isset($aParametros[105])==0){$aParametros[105]='';}
	if (isset($aParametros[106])==0){$aParametros[106]='';}
	if (isset($aParametros[107])==0){$aParametros[107]='';}
	if (isset($aParametros[108])==0){$aParametros[108]='';}
	if (isset($aParametros[109])==0){$aParametros[109]='';}
	if ($aParametros[105]!=''){$sSQLadd=$sSQLadd.' AND T6.unad11doc LIKE "%'.$aParametros[105].'%"';}
	if ($aParametros[106]!=''){$sSQLadd1=$sSQLadd1.' AND TB.ofer11idcurso LIKE "%'.$aParametros[106].'%"';}
	if ($aParametros[107]!=''){$sSQLadd=$sSQLadd.' AND T3.mat_descripcion LIKE "%'.$aParametros[107].'%"';}
	if ($aParametros[108]!=''){$sSQLadd1=$sSQLadd1.' AND TB.ofer11idrol='.$aParametros[108].'';}
	if ($aParametros[109]!=''){$sSQLadd1=$sSQLadd1.' AND TB.ofer11idescuela='.$aParametros[109].'';}
	//Octubre 11 de 2014 - Estas consultas son sumamente pesadas por el doble left join en la segunda consulta... la primera esta bien...
	//por lo tanto se separan para que el count lo haga una consulta y luego la de mostrar la tabla la haga otra pero con el limit.
	if ($aParametros[104]==1){
		$sSQL='SELECT T4.ofer10nombre, TB.ofer11id, T6.unad11razonsocial AS C6_nombre, TB.ofer11detalle, TB.ofer11per_aca, TB.ofer11idescuela, TB.ofer11idcurso, TB.ofer11idrol, TB.ofer11idtercero, T6.unad11tipodoc AS C6_td, T6.unad11doc AS C6_doc, T6.unad11telefono, T6.unad11correo, T6.unad11correoinstitucional, T6.unad11correonotifica, T6.unad11correofuncionario, T6.unad11aceptanotificacion, TB.ofer11fecharegistro, T6.unad11mostrarcelular 
FROM ofer11actores AS TB, ofer10rol AS T4, unad11terceros AS T6 
WHERE TB.ofer11per_aca="'.$_SESSION['oai_per_aca'].'" '.$sSQLadd1.' AND TB.ofer11idrol=T4.ofer10id AND TB.ofer11idtercero=T6.unad11id '.$sSQLadd.' 
ORDER BY T6.unad11razonsocial, T4.ofer10nombre';
		$tabladetalle=$objDB->ejecutasql($sSQL);
		$registros=$objDB->nf($tabladetalle);
		if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
		if ($registros>$lineastabla){
			$rbase=($pagina-1)*$lineastabla;
			$limite=' LIMIT '.$rbase.', '.$lineastabla;
			$tabladetalle=$objDB->ejecutasql($sSQL.$limite);
			}
		}else{
		$sSQL='SELECT TB.ofer11id
FROM ofer11actores AS TB, ofer10rol AS T4, unad11terceros AS T6 
WHERE TB.ofer11per_aca="'.$_SESSION['oai_per_aca'].'" '.$sSQLadd1.' AND TB.ofer11idrol=T4.ofer10id AND TB.ofer11idtercero=T6.unad11id '.$sSQLadd.'';
		$tabladetalle=$objDB->ejecutasql($sSQL);
		$registros=$objDB->nf($tabladetalle);
		$sSQL='SELECT T2.exte01nombre, T3.unad40nombre, T4.ofer10nombre, TB.ofer11id, T6.unad11razonsocial AS C6_nombre, TB.ofer11detalle, TB.ofer11per_aca, TB.ofer11idescuela, TB.ofer11idcurso, TB.ofer11idrol, TB.ofer11idtercero, T6.unad11tipodoc AS C6_td, T6.unad11doc AS C6_doc, TB.ofer11idcurso, T6.unad11telefono, T6.unad11correo, T6.unad11correoinstitucional, T6.unad11correonotifica, T6.unad11correofuncionario, T6.unad11aceptanotificacion, TB.ofer11fecharegistro, T6.unad11mostrarcelular
FROM (ofer11actores AS TB LEFT JOIN exte01escuela AS T2 ON (TB.ofer11idescuela=T2.exte01id)) LEFT JOIN unad40curso AS T3 ON (TB.ofer11idcurso=T3.unad40id), ofer10rol AS T4, unad11terceros AS T6 
WHERE TB.ofer11per_aca="'.$_SESSION['oai_per_aca'].'" '.$sSQLadd1.' AND TB.ofer11idrol=T4.ofer10id AND TB.ofer11idtercero=T6.unad11id '.$sSQLadd.' 
ORDER BY T6.unad11razonsocial, T4.ofer10nombre';
		if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
		$rbase=($pagina-1)*$lineastabla;
		$limite=' LIMIT '.$rbase.', '.$lineastabla;
		$tabladetalle=$objDB->ejecutasql($sSQL.$limite);
		}
	$sTitulo='';
	if ($aParametros[104]!=1){
		$sTitulo='
<td><b>'.$ETI['ofer11idescuela'].'</b></td>
<td><b>'.$ETI['ofer11idcurso'].'</b></td>';
		}
	$res='<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr class="fondoazul">'.$sTitulo.'
<td><b>'.$ETI['ofer11idrol'].'</b></td>
<td colspan="4"><b>'.$ETI['ofer11idtercero'].'</b></td>
<td><b>'.$ETI['ofer11fecharegistro'].'</b></td>
<td align="right">
'.html_paginador("paginaf1711", $registros, $lineastabla, $pagina, "paginarf1711()").'
'.html_lpp("lppf1711", $lineastabla, "paginarf1711()").'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='';
		$sSufijo='';
		if (false){
			$sPrefijo='<b>';
			$sSufijo='</b>';
			}
		$sEscuela='';
		if ($aParametros[104]!=1){
			$sEscuela='
<td>'.$sPrefijo.cadena_notildes($filadet['exte01nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['ofer11idcurso'].' '.cadena_notildes($filadet['unad40nombre']).$sSufijo.'</td>';
			}
		//unad11correoinstitucional, T6.unad11correonotifica, T6.unad11aceptanotificacion
		$et_correo='';
		if (correo_VerificarDireccion($filadet['unad11correofuncionario'])){
			$et_correo=$filadet['unad11correofuncionario'];
			}else{
			if (correo_VerificarDireccion($filadet['unad11correoinstitucional'])){
				$et_correo=$filadet['unad11correoinstitucional'];
				}else{
				if ($filadet['unad11aceptanotificacion']=='S'){
					$et_correo=$filadet['unad11correonotifica'];
					}else{
					$et_correo=$filadet['unad11correo'];
					}
				}
			}
		$et_ofer11fecharegistro='';
		if ($filadet['ofer11fecharegistro']!='00/00/0000'){
			$et_ofer11fecharegistro=$filadet['ofer11fecharegistro'];
			}
		$et_unad11telefono='';
		if ($filadet['unad11mostrarcelular']=='S'){
			$et_unad11telefono=$filadet['unad11telefono'];
			}
		$res=$res.'<tr ';
		if(($tlinea%2)==0){$res=$res.'class="resaltetabla"';}
		$tlinea++;
		$res=$res.'>'.$sEscuela.'
<td>'.$sPrefijo.cadena_notildes($filadet['ofer10nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['C6_td'].' '.$filadet['C6_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C6_nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$et_unad11telefono.$sSufijo.'</td>
<td>'.$sPrefijo.$et_correo.$sSufijo.'</td>
<td>'.$sPrefijo.$et_ofer11fecharegistro.$sSufijo.'</td>
<td>';
		if ($babierta){
			$res=$res.'<a href="javascript:cargaridf1711('.$filadet['ofer11id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'</td></tr>';
		}
	$res=$res.'</table>';
	return utf8_encode($res);
	}
function f1712_db_Guardar($valores, $objDB){
	$icodmodulo=1712;
	$bAudita[2]=false;
	$bAudita[3]=false;
	$mensajes_1712='lg/lg_1712_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1712)){$mensajes_1712='lg/lg_1712_es.php';}
	require $mensajes_1712;
	$sError='';
	$objDB->xajax();
	$binserta=false;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$ofer12idoferta=numeros_validar($valores[1]);
	$ofer12consec=numeros_validar($valores[2]);
	$ofer12id=numeros_validar($valores[3], true);
	$ofer12fechaoferta=$valores[4];
	$ofer12fechacancela=$valores[5];
	$ofer12idtercero=numeros_validar($valores[6]);
	if ($ofer12idtercero==0){$sError=$ERR['ofer12idtercero'];}
	if (!fecha_esvalida($ofer12fechacancela)){
		//$ofer12fechacancela='00/00/0000';
		$sError=$ERR['ofer12fechacancela'];
		}
	if (!fecha_esvalida($ofer12fechaoferta)){
		//$ofer12fechaoferta='00/00/0000';
		$sError=$ERR['ofer12fechaoferta'];
		}
	//if ($ofer12id==''){$sError=$ERR['ofer12id'];}//CONSECUTIVO
	//if ($ofer12consec==''){$sError=$ERR['ofer12consec'];}//CONSECUTIVO
	if ($ofer12idoferta==''){$sError=$ERR['ofer12idoferta'];}
	if ($sError==''){
		if ((int)$ofer12id==0){
			if ((int)$ofer12consec==0){
				$ofer12consec=tabla_consecutivo('ofer12ofertahistorico', 'ofer12consec', 'ofer12idoferta='.$ofer12idoferta.'', $objDB);
				if ($ofer12consec==-1){$sError=$objDB->serror;}
				}
			$sSQL='SELECT ofer12idoferta FROM ofer12ofertahistorico WHERE ofer12idoferta='.$ofer12idoferta.' AND ofer12consec='.$ofer12consec.'';
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)!=0){
				$sError=$ERR['existe'];
				}else{
				//if (!seg_revisa_permiso($icodmodulo, 2, $objDB)){$sError=$ERR['2'];}
				}
			if ($sError==''){
				$ofer12id=tabla_consecutivo('ofer12ofertahistorico', 'ofer12id', '', $objDB);
				if ($ofer12id==-1){$sError=$objDB->serror;}
				$binserta=true;
				}
			}else{
			//if (!seg_revisa_permiso($icodmodulo, 3, $objDB)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		if ($binserta){
			$scampos='ofer12idoferta, ofer12consec, ofer12id, ofer12fechaoferta, ofer12fechacancela, ofer12idtercero';
			$svalores=''.$ofer12idoferta.', '.$ofer12consec.', '.$ofer12id.', "'.$ofer12fechaoferta.'", "'.$ofer12fechacancela.'", "'.$ofer12idtercero.'"';
			$sSQL='INSERT INTO ofer12ofertahistorico ('.$scampos.') VALUES ('.$svalores.');';
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError='Error critico al tratar de guardar Historico de oferta, por favor informe al administrador del sistema.<!-- '.$sSQL.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], 2, 0, $sSQL, $objDB);
					}
				}
			}else{
			$scampo1712[1]='ofer12fechaoferta';
			$scampo1712[2]='ofer12fechacancela';
			$scampo1712[3]='ofer12idtercero';
			$svr1712[1]=$ofer12fechaoferta;
			$svr1712[2]=$ofer12fechacancela;
			$svr1712[3]=$ofer12idtercero;
			$inumcampos=3;
			$swhere='ofer12idoferta='.$ofer12idoferta.' AND ofer12consec='.$ofer12consec.'';
			$sSQL='SELECT * FROM ofer12ofertahistorico WHERE '.$swhere;
			$sdatos='';
			$bpasa=false;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$row=$objDB->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($row[$scampo1712[$k]]!=$svr1712[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo1712[$k].'="'.$svr1712[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				$sSQL='UPDATE ofer12ofertahistorico SET '.$sdatos.' WHERE '.$swhere.';';
				$result=$objDB->ejecutasql($sSQL);
				if ($result==false){
					$sError='Error critico al tratar de guardar Historico de oferta, por favor informe al administrador del sistema.<!-- '.$sSQL.' -->';
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
// Agenda de un curso
function f1718_TablaDetalle($aParametros, $objDB){
	list($sTabla, $sDebug)=f1718_TablaDetalleV2($aParametros, $objDB);
	return $sTabla;
	}
function f1718_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1718='lg/lg_1718_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1718)){$mensajes_1718='lg/lg_1718_es.php';}
	require $mensajes_todas;
	require $mensajes_1718;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[103])==0){$aParametros[103]=-1;}
	if (isset($aParametros[104])==0){$aParametros[104]=-1;}
	if ($aParametros[103]==''){$aParametros[103]=-1;}
	if ($aParametros[104]==''){$aParametros[104]=-1;}
	if (isset($aParametros[105])==0){$aParametros[105]=1;}
	$sDebug='';
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$idPeraca=$aParametros[103];
	$idCurso=$aParametros[104];
	$babierta=true;
	//Aqui empieza la busqueda de datos para la integracion con el c2
	$sAula='A';
	switch($aParametros[105]){
		case 2:$sAula='B';break;
		case 3:$sAula='C';break;
		case 4:$sAula='D';break;
		case 5:$sAula='E';break;
		}
	$sNomCurso=''.$aParametros[104].$sAula.'_'.$aParametros[103].'';
	if (isset($APP->dbhostcentral)==0){
		$objDBCentral=$objDB;
		}else{
		$objDBCentral=new clsdbadmin($APP->dbhostcentral, $APP->dbusercentral, $APP->dbpasscentral, $APP->dbnamecentral);
		if ($APP->dbpuertocentral!=''){$objDBCentral->dbPuerto=$APP->dbpuertocentral;}
		if ($bDebug){
			if (!$objDBCentral->conectar()){
				$sDebug=$sDebug.fecha_microtiempo().' Error al intentar conectar con el centralizador: '.$objDBCentral->serror.'<br>';
				}
			}
		}
	$bConError=false;
	$aActividades=array();
	$aNomAct=array();
	$aPesoAct=array();
	$aLlaveAct=array();
	//,'-ENTREGADEACTIVIDAD','ENTREGADEACTIVIDAD','-ENTREGAACTIVIDAD','ENTREGAACTIVIDAD','-ENTREGAACTIVIDADUNIDAD','ENTREGAACTIVIDADUNIDAD'
	$aFinales=array('','-ENTREGADELAACTIVIDAD','ENTREGADELAACTIVIDAD','-DELIVERYOFTHEACTIVITY','DELIVERYOFTHEACTIVITY','-SUBMITACTIVITY','SUBMITACTIVITY');
	$iCombinaciones=6;
	$iActividades=0;
	$sPermitidos='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890-_';
	$sSQL='SELECT actividad_id, itemname, grademax FROM actividad WHERE id_curso="'.$sNomCurso.'" AND itemtype="mod"';
	$tabla=$objDBCentral->ejecutasql($sSQL);
	while($fila=$objDBCentral->sf($tabla)){
		$iActividades++;
		$aActividades[$iActividades]=$fila['actividad_id'];
		$aNomAct[$iActividades]=utf8_encode($fila['itemname']).' [Puntaje: '.formato_numero($fila['grademax'], 0).']';
		$aPesoAct[$iActividades]=$fila['grademax'];
		$sBase1=cadena_Reemplazar($fila['itemname'], '&amp;', '');
		$sBase=strtoupper(cadena_limpiar($sBase1, $sPermitidos));
		$sLlaveAct=$sBase;
		for ($k=1;$k<=$iCombinaciones;$k++){
			$sOpcion=$aFinales[$k];
			$iLargoOpcion=strlen($sOpcion);
			$sFin=(substr($sBase, $iLargoOpcion*(-1)));
			if ($sFin==$sOpcion){
				$sLlaveAct=substr($sBase, 0, strlen($sBase)-$iLargoOpcion);
				$k=$iCombinaciones+1;
				}
			}
		$aLlaveAct[$iActividades]=$sLlaveAct;
		}
	$sOrigenes='1';
	$sSQL='SELECT ofer08incluyelaboratorio, ofer08incluyesalida 
FROM ofer08oferta 
WHERE ofer08idper_aca='.$idPeraca.' AND ofer08idcurso='.$idCurso.' AND ofer08cead=0 ';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		if ($fila['ofer08incluyelaboratorio']=='S'){$sOrigenes=$sOrigenes.',2';}
		if ($fila['ofer08incluyesalida']=='S'){$sOrigenes=$sOrigenes.',3';}
		}
	$sSQLadd='';
	$sSQLadd1='TB.ofer18curso='.$aParametros[104].' AND TB.ofer18per_aca='.$aParametros[103].' AND ';
	//if ($aParametros[1]!=''){$sSQLadd=$sSQLadd.' AND TB.campo2="'.$aParametros[1].'"';}
	//ofer04cursoactividad	ofer04id	ofer04nombre
	if ($sOrigenes=='1'){
		$sSQL='UPDATE ofer18cargaxnavxdia AS TB SET TB.ofer18origennota=1 WHERE '.$sSQLadd1.'TB.ofer18origennota=0';
		$result=$objDB->ejecutasql($sSQL);
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Actualizando Origenes: '.$sSQL.'<br>';}
		}
	$objCombos=new clsHtmlCombos('n');
	$sSQL='SELECT TB.ofer18per_aca, TB.ofer18curso, TB.ofer18numaula, TB.ofer18fase, TB.ofer18unidad, TB.ofer18idactividad, TB.ofer18diaini, TB.ofer18fechainicio, TB.ofer18duracion, TB.ofer18fechacierrre, TB.ofer18diasretro, TB.ofer18fecharetro, T1.ofer04nombre, TB.ofer18peso, T2.ofer02nombre, T3.ofer03nombre, TB.ofer18origennota, TB.ofer18idact_moodle 
FROM ofer18cargaxnavxdia AS TB, ofer04cursoactividad AS T1, ofer02cursofase AS T2, ofer03cursounidad AS T3 
WHERE '.$sSQLadd1.' TB.ofer18numaula=1 AND TB.ofer18idactividad=T1.ofer04id AND TB.ofer18fase=T2.ofer02id AND TB.ofer18unidad=T3.ofer03id '.$sSQLadd.' 
ORDER BY ofer18orden, STR_TO_DATE(TB.ofer18fechainicio, "%d/%m/%Y"), STR_TO_DATE(TB.ofer18fechacierrre, "%d/%m/%Y")';
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 140: '.$sSQL.'<br>';}
	$tabladetalle=$objDB->ejecutasql($sSQL);
	$registros=$objDB->nf($tabladetalle);
	if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
	if ($registros>$lineastabla){
		$rbase=($pagina-1)*$lineastabla;
		$limite=' LIMIT '.$rbase.', '.$lineastabla;
		$tabladetalle=$objDB->ejecutasql($sSQL.$limite);
		}
	$res='<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr class="fondoazul">
<td colspan="2"></td>
<td><b>'.$ETI['ofer18idactividad'].'</b></td>
<td><b>'.$ETI['ofer18peso'].'</b></td>
<td><b>'.$ETI['ofer18fechainicio'].'</b></td>
<td><b>'.$ETI['ofer18fechacierrre'].'</b></td>
<td><b>'.$ETI['ofer18fecharetro'].'</b></td>
<td align="right">
'.html_paginador('paginaf1718', $registros, $lineastabla, $pagina, 'paginarf1718()').'
'.html_lpp('lppf1718', $lineastabla, 'paginarf1718()').'
</td>
</tr>';
	$idFase=-1;
	$idUnidad=-1;
	$tlinea=1;
	$iPeso=0;
	$iPuntajeTotal=0;
	while($filadet=$objDB->sf($tabladetalle)){
		/*
<td></td>
<td><b>'.$ETI['ofer18unidad'].'</b></td>
		*/
		if ($idUnidad!=$filadet['ofer18unidad']){
			$idUnidad=$filadet['ofer18unidad'];
			$res=$res.'<tr class="fondoazul">
<td>'.$ETI['ofer18unidad'].'</td>
<td colspan="7"><b>'.cadena_notildes($filadet['ofer03nombre']).'</b></td>
</tr>';
			}
		if ($idFase!=$filadet['ofer18fase']){
			$idFase=$filadet['ofer18fase'];
			$res=$res.'<tr class="fondoazul">
<td></td>
<td>'.$ETI['ofer18fase'].'</td>
<td colspan="6"><b>'.cadena_notildes($filadet['ofer02nombre']).'</b></td>
</tr>';
			}
		$sPrefijo='';
		$sSufijo='';
		$sClass='';
		$sLink='';
		if (false){
			$sPrefijo='<b>';
			$sSufijo='</b>';
			}
		if(($tlinea%2)==0){$sClass=' class="resaltetabla"';}
		$tlinea++;
		$et_ofer18origennota=$sPrefijo.$filadet['ofer18origennota'].$sSufijo;
		$et_ofer18idact_moodle=$sPrefijo.$filadet['ofer18idact_moodle'].$sSufijo;
		$idOrigen=$filadet['ofer18origennota'];
		$idActividad=$filadet['ofer18idact_moodle'];
		if ($babierta){
			if ($idActividad==''){
				//Asociar la actividad.
				$sBase1=cadena_Reemplazar($filadet['ofer04nombre'], '&amp;', '');
				$sSemilla=strtoupper(cadena_limpiar($sBase1, $sPermitidos));
				for ($k=1;$k<=$iActividades;$k++){
					if ($sSemilla==$aLlaveAct[$k]){
						if ($filadet['ofer18peso']==$aPesoAct[$k]){
							$idActividad=$aActividades[$k];
							$sSQL='UPDATE ofer18cargaxnavxdia AS TB SET TB.ofer18idact_moodle="'.$idActividad.'" WHERE '.$sSQLadd1.'TB.ofer18idactividad='.$filadet['ofer18idactividad'].' AND TB.ofer18idact_moodle=""';
							$result=$objDB->ejecutasql($sSQL);
							if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Actualizando Origenes: '.$sSQL.'<br>';}
							$k=$iActividades+1;
							}
						}
					}
				}
			//, TB.ofer18fase, TB.ofer18unidad
			//$sLink='<a href="javascript:cargadato('.$aParametros[103].', '.$aParametros[104].', '.$aParametros[105].','.$filadet['ofer18fase'].','.$filadet['ofer18unidad'].', '.$filadet['ofer18idactividad'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			if ($sOrigenes=='1'){
				$et_ofer18origennota=$ETI['msg_campus'];
				}else{
				$et_ofer18origennota=html_combo('ofer18origennota_'.$filadet['ofer18idactividad'], 'ofer58id', 'ofer58titulo', 'ofer58origennota', 'ofer58id IN ('.$sOrigenes.')', 'ofer58titulo', $idOrigen, $objDB, 'cuadricula1718(13, '.$filadet['ofer18idactividad'].', this.value)', true, '{'.$ETI['msg_ninguna'].'}', '0');
				}
			$bConVacio=true;
			if ($idActividad!=0){$bConVacio=false;}
			$objCombos->nuevo('ofer18idact_moodle_'.$filadet['ofer18idactividad'], $idActividad, $bConVacio, '{'.$ETI['msg_ninguna'].'}');
			for ($k=1;$k<=$iActividades;$k++){
				$bEntra=false;
				if ($idActividad==0){
					$bEntra=true;
					}else{
					if ($idActividad==$aActividades[$k]){$bEntra=true;}
					}
				if ($bEntra){
					$objCombos->addItem($aActividades[$k], $aNomAct[$k]);
					}
				//$objCombos->addItem($aActividades[$k], $aNomAct[$k].' '.$aLlaveAct[$k]);
				}
			//$objCombos->sAccion='cuadricula1718(14, '.$filadet['ofer18idactividad'].', this.value)';
			$et_ofer18idact_moodle=$objCombos->html('', $objDB);
			//$et_ofer18idact_moodle=html_combo('ofer18idact_moodle_'.$filadet['ofer18idactividad'], 'actividad_id', 'CONCAT(itemname, " ", grademax)', 'actividad', 'id_curso="'.$sNomCurso.'" AND itemtype="mod"', 'itemname', $filadet['ofer18idact_moodle'], $objDBCentral, 'cuadricula1718(14, '.$filadet['ofer18idactividad'].', this.value)', true, '{'.$ETI['msg_ninguna'].'}', '');
			}
		$bEntra=false;
		if ($filadet['ofer18fase']==0){$bEntra=true;}
		if ($filadet['ofer18unidad']==0){$bEntra=true;}
		if ($filadet['ofer18idactividad']==0){$bEntra=true;}
		if ($bDebug){$bEntra=true;}
		if ($bEntra){
			$sLink='<input id="btMenos1718_act" name="btMenos1718_act" type="button" value="Retirar" class="btMiniMenos" onclick="menosuno1718('.$filadet['ofer18fase'].', '.$filadet['ofer18unidad'].', '.$filadet['ofer18idactividad'].');" title="Retirar fila "/> ['.$filadet['ofer18fase'].' - '.$filadet['ofer18unidad'].' - '.$filadet['ofer18idactividad'].']';
			}
		$iPuntajeTotal=$iPuntajeTotal+$filadet['ofer18peso'];
		if ($idPeraca>613){
			if ($idActividad==0){
				$sPrefijo='<span class="rojo">';
				$sSufijo='</span>';
				}
			}
		$res=$res.'<tr'.$sClass.'>
<td colspan="3">'.$sPrefijo.cadena_notildes($filadet['ofer04nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['ofer18peso'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['ofer18fechainicio'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['ofer18fechacierrre'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['ofer18fecharetro'].$sSufijo.'</td>
<td></td>
</tr>';
		if ($idPeraca>613){
			$res=$res.'<tr'.$sClass.'>
<td colspan="6">'.$et_ofer18idact_moodle.'</td>
<td>'.$et_ofer18origennota.'</td>
<td><div id="div_fila'.$filadet['ofer18idactividad'].'"></div></td>
</tr>';
			if ($idActividad==0){
				$sPrefijo='<span class="rojo">';
				$sSufijo='</span>';
				$res=$res.'<tr'.$sClass.'>
<td colspan="8"><div class="rojo">'.$ERR['msg_actividadnoasociada'].'</div></td>
</tr>';
				$bConError=true;
				}
			}
		}
	$res=$res.'<tr>
<td colspan="3"></td>
<td><b>'.$iPuntajeTotal.'</b></td>
<td colspan="4"></td>
</tr>';
	/*
	if ($bConError){
		$res=$res.'<tr'.$sClass.'>
<td colspan="8">'.$ERR['msg_enlacecapacitaciones'].'</td>
</tr>';
		}
	*/
	$res=$res.'</table>';
	if ($bConError){
		$res=''.html_salto().$ERR['msg_enlacecapacitaciones'].html_salto().$res;
		}
	$objDB->liberar($tabladetalle);
	$objDBCentral->CerrarConexion();
	return array(utf8_encode($res), $sDebug);
	}
// Muestra una agenda de un tercero
function f1721_Agenda($aParametros, $objDB){
	$mensajes_1721='lg/lg_1721_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1721)){$mensajes_1721='lg/lg_1721_es.php';}
	require $mensajes_1721;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$babierta=true;
	$objDB->xajax();
	$sSQLadd='';
	if (isset($aParametros[103])==0){$aParametros[103]=$_SESSION['unad_id_tercero'];}
	if (isset($aParametros[104])==0){$aParametros[104]=$_SESSION['oai_per_aca'];}
	//if ((int)$aParametros[0]!=-1){$sSQLadd=$sSQLadd.' AND TB.campo='.$aParametros[0];}
	if ((int)$aParametros[103]==0){$aParametros[103]=-1;}
	if ((int)$aParametros[104]==0){$aParametros[104]=-1;}
	$sSQLadd=$sSQLadd.' AND TB.ofer21idtercero='.$aParametros[103].'';
	$sSQLadd=$sSQLadd.' AND TB.ofer21per_aca='.$aParametros[104].'';
	$sSQL='SELECT T3.unad40nombre, T4.ofer02nombre, T5.ofer03nombre, T6.ofer04nombre, TB.ofer21consec, TB.ofer21id, T9.ofer22nombre, TB.ofer21orden, TB.ofer21fechaini, TB.ofer21fechafin, TB.ofer21fecharetro, TB.ofer21peso, TB.ofer21anotacion, T16.unad11razonsocial AS C16_nombre, TB.ofer21enfirme, TB.ofer21idtercero, T1.unad11tipodoc AS C1_td, T1.unad11doc AS C1_doc, TB.ofer21per_aca, TB.ofer21idcurso, TB.ofer21idfase, TB.ofer21idunidad, TB.ofer21idactividad, TB.ofer21origen, TB.ofer21terceroorigen, T16.unad11tipodoc AS C16_td, T16.unad11doc AS C16_doc 
FROM ofer21agenda AS TB, unad40curso AS T3, ofer02cursofase AS T4, ofer03cursounidad AS T5, ofer04cursoactividad AS T6, ofer22agendaorigenact AS T9, unad11terceros AS T16 
WHERE TB.ofer21idcurso=T3.unad40id AND TB.ofer21idfase=T4.ofer02id AND TB.ofer21idunidad=T5.ofer03id AND TB.ofer21idactividad=T6.ofer04id AND TB.ofer21origen=T9.ofer22id AND TB.ofer21terceroorigen=T16.unad11id '.$sSQLadd.' ORDER BY TB.ofer21orden, TB.ofer21id';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	$registros=$objDB->nf($tabladetalle);
	if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
	if ($registros>$lineastabla){
		$rbase=($pagina-1)*$lineastabla;
		$limite=' LIMIT '.$rbase.', '.$lineastabla;
		$tabladetalle=$objDB->ejecutasql($sSQL.$limite);
		}
	$res='<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr class="fondoazul">
<td><b>'.$ETI['ofer21idcurso'].'</b></td>
<td><b>'.$ETI['ofer21idfase'].'</b></td>
<td><b>'.$ETI['ofer21idunidad'].'</b></td>
<td><b>'.$ETI['ofer21idactividad'].'</b></td>
<td><b>'.$ETI['ofer21consec'].'</b></td>
<td><b>'.$ETI['ofer21origen'].'</b></td>
<td><b>'.$ETI['ofer21orden'].'</b></td>
<td><b>'.$ETI['ofer21fechaini'].'</b></td>
<td><b>'.$ETI['ofer21fechafin'].'</b></td>
<td><b>'.$ETI['ofer21peso'].'</b></td>
<td align="right">
'.html_paginador("paginaagenda", $registros, $lineastabla, $pagina, "paginaragenda()").'
'.html_lpp("lppagenda", $lineastabla, "paginaragenda()").'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='';
		$sSufijo='';
		$sClass='';
		$sLink='';
		if (false){
			$sPrefijo='<b>';
			$sSufijo='</b>';
			}
		if(($tlinea%2)==0){$sClass=' class="resaltetabla"';}
		$tlinea++;
		$et_ofer21fechaini='';
		if ($filadet['ofer21fechaini']!='00/00/0000'){$et_ofer21fechaini=$filadet['ofer21fechaini'];}
		$et_ofer21fechafin='';
		if ($filadet['ofer21fechafin']!='00/00/0000'){$et_ofer21fechafin=$filadet['ofer21fechafin'];}
		$et_ofer21fecharetro='';
		if ($filadet['ofer21fecharetro']!='00/00/0000'){$et_ofer21fecharetro=$filadet['ofer21fecharetro'];}
		if ($babierta){
			$sLink='<a href="javascript:cargaridf1721('."'".$filadet['ofer21id']."'".')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$filadet['unad40nombre'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['ofer02nombre'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['ofer03nombre'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['ofer04nombre'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['ofer21consec'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['ofer22nombre'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['ofer21orden'].$sSufijo.'</td>
<td>'.$sPrefijo.et_ofer21fechaini.$sSufijo.'</td>
<td>'.$sPrefijo.et_ofer21fechafin.$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['ofer21peso'].$sSufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	return utf8_encode($res);
	}

// Guardar o eliminar un item de evaluacion.
function f1725_db_Guardar($valores, $objDB){
	$icodmodulo=1725;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require 'app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1725='lg/lg_1725_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1725)){$mensajes_1725='lg/lg_1725_es.php';}
	require $mensajes_todas;
	require $mensajes_1725;
	$sError='';
	$objDB->xajax();
	$binserta=false;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$ofer25idevaluacion=numeros_validar($valores[1]);
	$ofer25iditem=numeros_validar($valores[2]);
	$ofer25id=numeros_validar($valores[3], true);
	$ofer25orden=numeros_validar($valores[4]);
	//if ($ofer25orden==''){$ofer25orden=0;}
	if ($ofer25orden==''){$sError=$ERR['ofer25orden'];}
	//if ($ofer25id==''){$sError=$ERR['ofer25id'];}//CONSECUTIVO
	if ($ofer25iditem==''){$sError=$ERR['ofer25iditem'];}
	if ($ofer25idevaluacion==''){$sError=$ERR['ofer25idevaluacion'];}
	if ($sError==''){
		if ((int)$ofer25id==0){
			$sSQL='SELECT ofer25idevaluacion FROM ofer25evaluacioncuerpo WHERE ofer25idevaluacion='.$ofer25idevaluacion.' AND ofer25iditem='.$ofer25iditem.'';
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)!=0){
				$sError=$ERR['existe'];
				}else{
				if (!seg_revisa_permiso($icodmodulo, 2, $objDB)){$sError=$ERR['2'];}
				}
			if ($sError==''){
				$ofer25id=tabla_consecutivo('ofer25evaluacioncuerpo', 'ofer25id', '', $objDB);
				if ($ofer25id==-1){$sError=$objDB->serror;}
				$binserta=true;
				}
			}else{
			if (!seg_revisa_permiso($icodmodulo, 3, $objDB)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		if ($binserta){
			$scampos='ofer25idevaluacion, ofer25iditem, ofer25id, ofer25orden';
			$svalores=''.$ofer25idevaluacion.', '.$ofer25iditem.', '.$ofer25id.', '.$ofer25orden.'';
			$sSQL='INSERT INTO ofer25evaluacioncuerpo ('.$scampos.') VALUES ('.$svalores.');';
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError='Error critico al tratar de guardar Items de la evaluacion, por favor informe al administrador del sistema.<!-- '.$sSQL.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], 2, 0, $sSQL, $objDB);
					}
				}
			}else{
			$scampo1725[1]='ofer25orden';
			$svr1725[1]=$ofer25orden;
			$inumcampos=1;
			$swhere='ofer25idevaluacion='.$ofer25idevaluacion.' AND ofer25iditem='.$ofer25iditem.'';
			$sSQL='SELECT * FROM ofer25evaluacioncuerpo WHERE '.$swhere;
			$sdatos='';
			$bpasa=false;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$row=$objDB->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($row[$scampo1725[$k]]!=$svr1725[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo1725[$k].'="'.$svr1725[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				$sSQL='UPDATE ofer25evaluacioncuerpo SET '.$sdatos.' WHERE '.$swhere.';';
				$result=$objDB->ejecutasql($sSQL);
				if ($result==false){
					$sError='Error critico al tratar de guardar Items de la evaluacion, por favor informe al administrador del sistema.<!-- '.$sSQL.' -->';
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
function f1725_db_Eliminar($aParametros, $objDB){
	$icodmodulo=1725;
	$bAudita[4]=false;
	require 'app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1725='lg/lg_1725_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1725)){$mensajes_1725='lg/lg_1725_es.php';}
	require $mensajes_todas;
	require $mensajes_1725;
	$sError='';
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$ofer25idevaluacion=numeros_validar($aParametros[1]);
	$ofer25iditem=numeros_validar($aParametros[2]);
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
		$swhere='ofer25idevaluacion='.$ofer25idevaluacion.' AND ofer25iditem='.$ofer25iditem.'';
		$sSQL='DELETE FROM ofer25evaluacioncuerpo WHERE '.$swhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError='Error critico al tratar de eliminar Items de la evaluacion, por favor informe al administrador del sistema.<!-- '.$sSQL.' -->';
			}else{
				if ($bAudita[4]){
				seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], 4, 0, $sSQL, $objDB);
				}
			}
		}
	return $sError;
	}
function OAI_EstudiantesCicloBase($idPeraca, $idCurso, $idAula, $objDB){
	$res=0;
	//Ver si hay un ciclo base.
	$sSQL='SELECT ofer14ciclobase FROM ofer14per_acaparams WHERE ofer14idper_aca='.$idPeraca.'';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$idPrevio=$fila['ofer14ciclobase'];
		if ($idPrevio!=0){
			if ($idAula==1){
				$sSQL='SELECT ofer08numestaula1 FROM ofer08oferta WHERE ofer08idcurso='.$idCurso.' AND ofer08idper_aca='.$idPrevio.' AND ofer08cead=0';
				$tabla=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla)>0){
					$fila=$objDB->sf($tabla);
					$res=$fila['ofer08numestaula1'];
					}
				}else{
				//Aulas adicionales.
				$sSQL='SELECT unad48numestudiantes FROM unad48cursoaula WHERE unad48idcurso='.$idCurso.' AND unad48per_aca='.$idPrevio.' AND unad48consec='.$idAula.'';
				$tabla=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla)>0){
					$fila=$objDB->sf($tabla);
					$res=$fila['unad48numestudiantes'];
					}
				}
			}
		}
	return $res;
	}
?>