<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2014 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Sabado, 25 de octubre de 2014
--- Jueves 10 de Septiembre de 2015 - Se agrega el rol de diseñador.
*/
define('OIL_idRolDirector', 4);

function OIL_AgregarDirector($idTercero, $idPeraca, $objdb){
	$sql='SELECT olab17id FROM olab17actores WHERE olab17idactor='.$idTercero.' AND olab17idrol='.OIL_idRolDirector.'';
	$tabla=$objdb->ejecutasql($sql);
	if ($objdb->nf($tabla)>0){
		$fila=$objdb->sf($tabla);
		$sql='UPDATE olab17actores SET olab17activo="S" WHERE olab17id='.$fila['olab17id'];
		$result=$objdb->ejecutasql($sql);
		}else{
		$olab17id=tabla_consecutivo('olab17actores','olab17id', '', $objdb);
		$scampos='olab17idactor, olab17idrol, olab17id, olab17activo, olab17detalle, olab17zona';
		$svalores=''.$idTercero.', '.OIL_idRolDirector.', '.$olab17id.', "S", "Agregado desde el tablero", 0';
		$sql='INSERT INTO olab17actores ('.$scampos.') VALUES ('.$svalores.');';
		$result=$objdb->ejecutasql($sql);
		}
	Perfiles_OIL($idTercero, $objdb);
	}
function TABLERO_InterpretarNombre($sNombre){
	$sIdCurso='';
	$sIdAula=1;
	$sIdPerAca='';
	$iMomento=0;
	switch ($sNombre){
		case 'Bachillerato':
		$sIdCurso=118;
		$sIdAula=1;
		$sIdPerAca=87;
		break;
		case 'entrenamiento2x_2016':
		$sIdCurso=270;
		$sIdAula=1;
		$sIdPerAca=87;
		break;
		default:
	$largo=strlen($sNombre);
	for ($k=0; $k<$largo;$k++){
		$una=substr($sNombre,$k,1);
		switch($iMomento){
			case 0:
			switch($una){
				case '0':
				case '1':
				case '2':
				case '3':
				case '4':
				case '5':
				case '6':
				case '7':
				case '8':
				case '9':
				$sIdCurso=$sIdCurso.$una;
				break;
				case 'B':
				$sIdAula=2;
				$iMomento=1;
				break;
				case 'C':
				$sIdAula=3;
				$iMomento=1;
				break;
				case 'D':
				$sIdAula=4;
				$iMomento=1;
				break;
				case 'E':
				$sIdAula=5;
				$iMomento=1;
				break;
				case 'H':
				$sIdAula=8;
				$iMomento=1;
				break;
				case 'R':
				$sIdAula=19;
				$iMomento=1;
				break;
				case 'S':
				$sIdAula=20;
				$iMomento=1;
				break;
				case '-':
				case '_':
				$iMomento=2;
				break;
				default:
				$iMomento=1;
				}
			break;
			case 1:
			switch ($una){
				case '-':
				case '_':
				$iMomento=2;
				}
			break;
			case 2:
			switch($una){
				case '0':
				case '1':
				case '2':
				case '3':
				case '4':
				case '5':
				case '6':
				case '7':
				case '8':
				case '9':
				$sIdPerAca=$sIdPerAca.$una;
				}
			break;
			}
		}	
		}
	return array($sIdCurso, $sIdAula, $sIdPerAca);
	}
// Marzo 10 de 2015 Se agrega fecha de actualizacion del tablero en la tabla unad11
function TABLERO_Armar2013($sDocumento, $idTercero, $objdb, $debug=false, $bDebug2=false){
	require './app.php';
	$sError='';
	$sDebug='';
	$sInfoDebug='';
	$sCorreoInstitucional='';
	if ($bDebug2){$sDebug=$sDebug.fecha_microtiempo().' TABLERO: Iniciando la actualizacion<br>';}
	//Actualizar la clave apps para quienes no la tengan.
	$sql='SELECT unad11claveapps, unad11doc, unad11correoinstitucional FROM unad11terceros WHERE unad11id='.$idTercero.'';
	$res=$objdb->ejecutasql($sql);
	if ($objdb->nf($res)>0){
		$filaf=$objdb->sf($res);
		$sDocumento=$filaf['unad11doc'];
		$sCorreoInstitucional=$filaf['unad11correoinstitucional'];
		if ($filaf['unad11claveapps']==''){
			$sql='UPDATE unad11terceros SET unad11claveapps="'.md5($filaf['unad11doc']).'" WHERE unad11id='.$idTercero;
			$res=$objdb->ejecutasql($sql);
			if ($debug){
				$sInfoDebug=$sInfoDebug.'Actualizando Clave para apps <br>';
				}
			}
		//Verificamos el coreo institucional...
		if (!cadena_contiene($sCorreoInstitucional, '@unad.edu.co')){
			//Le solicitamos a congresos que lo actualce pero mediante webservice.
			//$cliente=new nusoap_client('http://66.165.175.223/apps/panel/wlib.php',false);
			//$sRutaWS='http://129.144.61.35/apps/panel/wlib.php';
			list($sRutaCongresos, $sDebugS)=ws_RutaCongresos($objdb, $debug);
			$sRutaWS='http://'.$sRutaCongresos.'/panel/wlib.php';
			if ($bDebug2){$sDebug=$sDebug.fecha_microtiempo().' TABLERO: Invocando WebService para actualizar el correo institucional, Ruta Servicio: '.$sRutaWS.'<br>';}
			$cliente=new nusoap_client($sRutaWS,false);
			$res=$cliente->call('actualizarCorreo',array('sDocumento'=>$sDocumento,'bForzar'=>'true'));
			if (false){
			if ($res['sError']==''){
				if ($debug){
					$sInfoDebug=$sInfoDebug.'Error al actualizar el correo institucional '.$res['sError'].'<br>';
					}
				}
				}
			}
		}else{
		if ($debug){
			$sInfoDebug=$sInfoDebug.'Tercero no encontrado<br>';
			}
		}
	$scampos47='unad47peraca, unad47idnav, unad47idrol, unad47idtercero, unad47idcurso, unad47numaula, unad47activo, unad47retirado, unad47idgrupo, unad47idcead, unad47idceadasiste, unad47idmoodle, unad47fechaini, unad47horaini, unad47minini, unad47banderaimportacion, unad47fechamod, unad47horamod, unad47minmod';
	$svalores47='';
	$PerAcaRaiz=220;
	$sHoy=fecha_hoy();
	$sHora=fecha_hora();
	$sMinuto=fecha_minuto();
	//Retirarlo de todos los cursos.
	//Agosto 1 de 2015 se agrega la verificación de origen.
	$sql='UPDATE unad47tablero SET unad47activo="N" 
WHERE unad47idtercero='.$idTercero.' AND unad47idcead=0 AND unad47origen=0';
	$res=$objdb->ejecutasql($sql);
	if ($bDebug2){$sDebug=$sDebug.fecha_microtiempo().' TABLERO: Actualizo todos los cursos a NO ACTIVOS<br>';}
	//Inactivarlo como director. en el OIL
	$sql='UPDATE olab17actores SET olab17activo="N" WHERE olab17idactor='.$idTercero.' AND olab17idrol='.OIL_idRolDirector.'';
	$res=$objdb->ejecutasql($sql);
	if ($bDebug2){$sDebug=$sDebug.fecha_microtiempo().' TABLERO: Se inactiva como director en el OIL<br>';}
	//Buscar la info en el tablero anterior.
	$sql='SELECT courseid, role, campus, shortname FROM edu_courses2013 WHERE idnumber="'.$sDocumento.'"';//campus LIKE "campus%" AND 
	$iOrigen=0;
	$bHayOrigen=false;
	if ($iOrigen!=0){
		if (isset($APP->dbhostTablero)!=0){
			if ($debug){
				$sInfoDebug=$sInfoDebug.' Intentando conectar con la base de datos tablero <br>';
				}
			if ($bDebug2){$sDebug=$sDebug.fecha_microtiempo().' TABLERO: Iniciando conexion con la db tablero que es <b>'.$APP->dbnameTablero.'</b> en <b>'.$APP->dbhostTablero.'</b><br>';}
			$objOrigen=new clsdbadmin($APP->dbhostTablero, $APP->dbuserTablero, $APP->dbpassTablero, $APP->dbnameTablero);
			if ($APP->dbpuertoTablero!=''){$objOrigen->dbPuerto=$APP->dbpuertoTablero;}
			if ($objOrigen->conectar()){
				//$objOrigen->ejecutasql('SET NAMES utf8');
				if ($bDebug2){$sDebug=$sDebug.fecha_microtiempo().' TABLERO: Iniciando conexion exitosa<br>';}
				$bHayOrigen=true;
				if ($debug){
					$sInfoDebug=$sInfoDebug.' Conectado con la base de datos tablero <br>';
					}
				}else{
				if ($bDebug2){$sDebug=$sDebug.fecha_microtiempo().' TABLERO: FALLA AL CONECTARSE CON EL TABLERO.<br>';}
				}
			}
		}
	if (!$bHayOrigen){
		$objOrigen=$objdb;
		}
	if ($bDebug2){$sDebug=$sDebug.fecha_microtiempo().' TABLERO: Iniciando carga de cursos :'.$sql.'.<br>';}
	$tablaf=$objOrigen->ejecutasql($sql);
	if ($bDebug2){$sDebug=$sDebug.fecha_microtiempo().' TABLERO: Carga de cursos completa.<br>';}
	if ($debug){
		$sInfoDebug=$sInfoDebug.'Cursos encontrados para '.$idTercero.' = '.$objOrigen->nf($tablaf).'<br>';
		}
	while ($filaf=$objOrigen->sf($tablaf)){
		$idNav=0;
		$idRol=5;
		$idGrupo=1;
		$idCead=0;
		$idCeadAsiste=0;
		$idPeraca=0;
		switch($filaf['campus']){
			case 'campus01':
			case 'campus01_20151':
			$idNav=1;break;
			case 'campus02':
			case 'campus02_20151':$idNav=2;break;
			case 'campus03':
			case 'campus03_20151':$idNav=3;break;
			case 'campus04':
			case 'campus04_20151':
			case 'campus04_20152':
			$idNav=4;break;
			case 'campus05':
			case 'campus05_20151':
			case 'campus05_20152':
			$idNav=5;break;
			case 'campus06':
			case 'campus06_20152':
			$idNav=6;break;
			case 'campus07':
			case 'campus07_20151':$idNav=7;break;
			case 'campus08':
			case 'campus08_20151':$idNav=8;break;
			case 'campus09':
			case 'campus09_20151':$idNav=9;break;
			case 'campus10':
			case 'campus10_20151':
			case 'campus10_20152':
			$idNav=10;break;
			case 'campus11':
			case 'campus11_20151':
			case 'campus11_20152':
			$idNav=11;break;
			case 'campus12':
			case 'campus12_20151':
			case 'campus12_20152':
			$idNav=12;break;
			case 'campus13':
			case 'campus13_20142':
			case 'campus13_20151':
			case 'campus13_20153':
			$idNav=13;break;
			case 'campus14':
			case 'campus14_20142':
			case 'campus14_20151':
			case 'campus14_20152':
			$idNav=14;break;
			case 'campus15':
			case 'campus15_20142':
			case 'campus15_20151':$idNav=15;break;
			case 'campus16':
			case 'campus16_20142':
			case 'campus16_20151':$idNav=16;break;
			case 'campus17':
			case 'campus17_20142':
			case 'campus17_20151':
			case 'campus17_20152':
			$idNav=17;break;
			case 'campus18':
			case 'campus18_20142':
			case 'campus18_20151':
			case 'campus18_20152':
				$idNav=18;
				break;
			case 'campus19':
			case 'campus19_20142':
			case 'campus19_20151':$idNav=19;break;
			case 'campus20':
			case 'campus20_20151':$idNav=20;break;
			case 'ECBTI01':$idNav=22;break;
			case 'ECEDU01':$idNav=23;break;
			case 'ECAPMA01':$idNav=24;break;
			case 'ECACEN01':$idNav=25;break;
			case 'ECSAH01':$idNav=26;break;
			case 'ECISALUD01':$idNav=27;break;
			case 'VISAE01':$idNav=36;break; 
			case 'INVIL01':$idNav=37;break;
			case 'INVIL02':$idNav=38;break;
			case 'INVIL03':$idNav=39;break;
			case 'ECBTI02':$idNav=40;break;
			case 'ECSAH02':$idNav=41;break;
			case 'inter0801_20151':$idNav=43;break;
			case 'inter0802_20151':$idNav=44;break;
			case 'Diplomados':$idNav=52;break;
			case 'campus_demo':$idNav=55;break;
			default:
			$sql='SELECT unad39id FROM unad39nav WHERE unad39nombre="'.$filaf['campus'].'"';
			$tablanav=$objdb->ejecutasql($sql);
			if ($objdb->nf($tablanav)){
				$filanav=$objdb->sf($tablanav);
				$idNav=$filanav['unad39id'];
				}else{
				//El nav puede estar en un alias.
				list($idCurso, $idAula, $idPeraca)=TABLERO_InterpretarNombre($filaf['shortname']);
				$sql='SELECT unad54idnav FROM unad54navperaca WHERE unad54alias="'.$filaf['campus'].'" AND unad54peraca='.$idPeraca;
				$tablanav=$objdb->ejecutasql($sql);
				if ($objdb->nf($tablanav)>0){
					$filanav=$objdb->sf($tablanav);
					$idNav=$filanav['unad54idnav'];
					}else{
					if ($debug){
						$sInfoDebug=$sInfoDebug.'No se encuentra el nav para '.$filaf['campus'].' Peraca '.$idPeraca.'<br>';
						}
					}
				}
			break;
			}
		if ($idNav!=0){
			list($idCurso, $idAula, $idPeraca)=TABLERO_InterpretarNombre($filaf['shortname']);
			$bEsCursoEstandar=true;
			if ((int)$idPeraca==0){$bEsCursoEstandar=false;}
			if ($idCurso==''){$bEsCursoEstandar=false;}
			if (!$bEsCursoEstandar){
				//no se puede interpretar el nombre porque no es estandar.
				$idNav=0;
				if ($debug){
					$sInfoDebug=$sInfoDebug.$filaf['shortname'].' = No es un curso estandar se procede a buscar por equivalencias.<br>';
					}
				}else{
				if ($debug){
					$sInfoDebug=$sInfoDebug.$filaf['shortname'].' =&gt; Curso:'.$idCurso.' Aula:'.$idAula.' PerAca:'.$idPeraca.'<br>';
					}
				}
			}
		if ((int)$idNav==0){
			$idCurso='';
			$idAula=1;
			$idPeraca=87;
			$bEspecial=false;
			$sql='SELECT unad49idnav, unad49idcurso, unad49idperaca FROM unad49equivaltablero WHERE unad49nomcampus="'.trim($filaf['campus']).'" AND unad49nomcurso="'.trim($filaf['shortname']).'"';
			$tablaesp=$objdb->ejecutasql($sql);
			if ($objdb->nf($tablaesp)>0){
				$filaesp=$objdb->sf($tablaesp);
				$idNav=$filaesp['unad49idnav'];
				$idCurso=$filaesp['unad49idcurso'];
				$idPeraca=$filaesp['unad49idperaca'];
				$bEspecial=true;
				if (($idNav==1)&&($idCurso==101)&&($idPeraca==87)){
					//Esta combinación se utiliza para informar que no esta actualizado
					$idCurso='';
					}
				}else{
				if ($debug){
					$sInfoDebug=$sInfoDebug.'No existe equivalencia para curso "'.$filaf['shortname'].'" campus "'.$filaf['campus'].'"<br>';
					}
				//Enviar el registro a equivalencias para que sea actualizado
				$id49=tabla_consecutivo('unad49equivaltablero','unad49id', '', $objdb);
				$scampos49='unad49nomcampus, unad49nomcurso, unad49id, unad49idnav, unad49idcurso, unad49idperaca';
				$svalores49='"'.$filaf['campus'].'", "'.$filaf['shortname'].'", '.$id49.', 1, 101, 87';
				$sql='INSERT INTO unad49equivaltablero ('.$scampos49.') VALUES ('.$svalores49.');';
				$res=$objdb->ejecutasql($sql);
				if ($res==false){
					if ($debug){
						$sInfoDebug=$sInfoDebug.'falla al ejecutar '.$sql.'<br>';
						}
					}
				}
			}
		if ($debug){
			$sInfoDebug=$sInfoDebug.'Procesando '.$filaf['shortname'].' = Curso '.$idCurso.' Aula '.$idAula.' Peraca '.$idPeraca.'<br>';
			}
		$bConLaboratorio=false;
		if ($idCurso!=''){
			$sql='SELECT unad40incluyelaboratorio FROM unad40curso WHERE unad40id='.$idCurso;
			$tablaf2=$objdb->ejecutasql($sql);
			if ($objdb->nf($tablaf2)==0){
				if ($debug){
					$sInfoDebug=$sInfoDebug.'<span class="rojo">El curso '.$idCurso.' no pudo ser comprobado</span><br>';
					}
				$idCurso='';
				}else{
				$fila40=$objdb->sf($tablaf2);
				if ($fila40['unad40incluyelaboratorio']=='S'){$bConLaboratorio=true;}
				}
			}
		if ($idCurso!=''){
			//Traer el rol.
			switch(trim($filaf['role'])){
				case 'Admin':
				case 'Administrator':
				$idRol=1;
				break;
				case 'Decano':
				$idRol=19;
				break;
				case 'Director de Curso':
				case 'Director de curso':
				$idRol=3;
				break;
				case 'Profesor sin permiso de edición':
				case 'Tutor':
				case 'Tutor de curso':
				$idRol=4;
				break;
				case '':
				case '5':
				case 'Estudiante':
				case 'student':
				break;
				case 'Invitado':
				$idRol=6;
				break;
				case 'Coordinador':
				$idRol=10;
				break;
				case 'Acreditador':
				case 'Evaluador':
				$idRol=11;
				break;
				case 'Admin PTI - VIMMEP':
				case 'Admin VIMEP-PTI':
				case 'Administrador PTI-VIMMEP':
				$idRol=12;
				break;
				case 'Editor':
				$idRol=14;
				break;
				case 'Auditor':
				case 'Auditor de Servicios':
				$idRol=16;
				break;
				case 'Diseñador':
				$idRol=17;
				break;
				case 'E-monitor':
				$idRol=18;
				break;
				case 'Lider':
				$idRol=21;
				break;
				case 'Lider Acreditación':
				case 'Lider Acreditacion':
				$idRol=22;
				break;
				case 'Observador':
				$idRol=9;
				break;
				case 'Secretaria':
				$idRol=20;
				break;
				default:
				$bExisteRol=false;
				if (substr(trim($filaf['role']),0,4)=='Dise'){
					$idRol=17;
					$bExisteRol=true;
					}
				if (substr(trim($filaf['role']),0,14)=='Lider Acredita'){
					$idRol=22;
					$bExisteRol=true;
					}
				if (!$bExisteRol){
					if ($debug){
						$sInfoDebug=$sInfoDebug.'<span class="rojo">No se encuentra informaci&oacute;n del rol {'.cadena_notildes($filaf['role']).'} para el curso '.$idCurso.'</span><br>';
						}
					$idCurso='';
					}
				}
			}
		if ($idCurso!=''){
			if ($idNav==0){
				if ($debug){
					$sInfoDebug=$sInfoDebug.'<span class="rojo">El curso '.$idCurso.' no tiene un NAV asginado</span><br>';
					}
				$idCurso='';
				}
			}
		if ($idCurso!=''){
			if ($idPeraca==''){
				$idPeraca=$PerAcaRaiz;
				}else{
				$sql='SELECT exte02nombre, exte02vigente FROM exte02per_aca WHERE exte02id='.$idPeraca;
				$tablaf2=$objdb->ejecutasql($sql);
				if ($objdb->nf($tablaf2)==0){
					if ($debug){
						$sInfoDebug=$sInfoDebug.'<span class="rojo">El peraca '.$idPeraca.' no ha sido importado</span><br>';
						}
					$idPeraca='';
					}else{
					if ($idPeraca!=87){
						$filaper=$objdb->sf($tablaf2);
						if ($filaper['exte02vigente']!='S'){
							if ($debug){
								$sInfoDebug=$sInfoDebug.'<span class="rojo">El peraca '.$idPeraca.' esta inactivo</span><br>';
								}
							$idPeraca='';
							}
						}
					}
				}
			if ($idPeraca!=''){
				//tenemos PerAca y Curso.
				$sql='SELECT unad47peraca FROM unad47tablero 
WHERE unad47idtercero='.$idTercero.' AND unad47peraca='.$idPeraca.' AND unad47idnav='.$idNav.' AND unad47idrol='.$idRol.' AND unad47idcurso='.$idCurso.' AND unad47numaula='.$idAula.'';
				$res=$objdb->ejecutasql($sql);
				if ($objdb->nf($res)==0){
					$svalores47=''.$idPeraca.', '.$idNav.', '.$idRol.', '.$idTercero.', '.$idCurso.', '.$idAula.', "S", "N", '.$idGrupo.', '.$idCead.', '.$idCeadAsiste.', "'.$filaf['courseid'].'", "'.$sHoy.'", '.$sHora.', '.$sMinuto.', "S", "'.$sHoy.'", '.$sHora.', '.$sMinuto.'';
					$sql='INSERT INTO unad47tablero ('.$scampos47.') VALUES ('.$svalores47.');';
					}else{
					$sql='UPDATE unad47tablero SET unad47activo="S", unad47retirado="N", unad47idmoodle="'.$filaf['courseid'].'" 
WHERE unad47idtercero='.$idTercero.' AND unad47peraca='.$idPeraca.' AND unad47idnav='.$idNav.' AND unad47idrol='.$idRol.' AND unad47idcurso='.$idCurso.' AND unad47numaula='.$idAula.'';
					}
				$res=$objdb->ejecutasql($sql);					
				if ($debug){
					if ($res==false){
						$sInfoDebug=$sInfoDebug.'<span class="rojo">Error '.$objdb->serror.' -- '.$sql.'</span><br>';
						}
					}
				//Termina si tenemos curso y peraca.
				//Mayo 26 de 2016 a los rol 3 (director de curso los matriculamos en el oai y como director de curso
				if ($idRol==3){
					if ($bConLaboratorio){
						//Se les agrega el rol 4 en el oai. lo cual les activa el perfil de directores.
						OIL_AgregarDirector($idTercero, $idPeraca, $objdb);
						}
					}
				//Fin de matricular...
				}
			}else{
			if ($debug){
				$sInfoDebug=$sInfoDebug.'<span class="rojo">No se ha encontrado el curso '.$filaf['shortname'].' en el campus (NAV) '.$filaf['campus'].'</span><br>';
				}
			}
		//Fin del recorrido por cada fila.
		}
	if ($bHayOrigen){
		$objOrigen->CerrarConexion();
		}
	//Los cursos que no se activaron es porque fueron retirados.
	$sql='UPDATE unad47tablero SET unad47retirado="S",unad47fechamod="'.$sHoy.'", unad47horamod='.$sHora.', unad47minmod='.$sMinuto.' 
WHERE unad47idtercero='.$idTercero.' AND unad47idcead=0 AND unad47activo="N" AND unad47retirado="N" AND unad47origen=0';
	$res=$objdb->ejecutasql($sql);
	//Actualizar la fecha de actualización del tablero.
	$iMinAhora=fecha_MinutoMod();
	$sql='UPDATE unad11terceros SET unad11fechatablero="'.$sHoy.'", unad11minutotablero='.$iMinAhora.' WHERE unad11id='.$idTercero.'';
	$res=$objdb->ejecutasql($sql);
	return array($sError, $sInfoDebug, $sDebug);
	}
function ws_RutaCongresos($objdb, $bDebug=false){
	$sRes='';
	$sDebug='129.191.26.113/apps';
	$sql='SELECT unad88rutacongresos FROM unad88opciones WHERE unad88id=1';
	$tabla=$objdb->ejecutasql($sql);
	if ($objdb->nf($tabla)>0){
		$fila=$objdb->sf($tabla);
		if ($fila['unad88rutacongresos']!=''){
			$sRes=$fila['unad88rutacongresos'];
			}
		}
	return array($sRes, $sDebug);
	}
?>