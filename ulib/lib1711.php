<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2014 - 2015 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 1.2.0 martes, 08 de julio de 2014
--- Octubre 11 de 2014 - Se centraliza la carga de actores para poder manejar la matricula en ncontents...
--- Modelo Versión 2.7.9 jueves, 04 de junio de 2015
--- 1711 ofer11actores
*/
class clsNcontents_V1{
var $objDBNC=NULL;
function ConectarDB(){
	require 'app.php';
	if (isset($APP->dbhostncontents)==0){
		$APP->dbhostncontents='';
		$APP->dbuserncontents=''; 
		$APP->dbpassncontents='';
		$APP->dbnamencontents='';
		}
	if ($APP->dbhostncontents==''){
		$this->objDBNC=NULL;
		}else{
		$this->objDBNC=new clsdbadmin($APP->dbhostncontents, $APP->dbuserncontents, $APP->dbpassncontents, $APP->dbnamencontents);
		if ($APP->dbpuertoncontents!=''){$objDBNC->dbPuerto=$APP->dbpuertoncontents;}
		}
	}
function bExiste($iDocumento, $iCurso, $iGrupo, $iRol, $iPerAca){
	$res=false;
	$iPerAca=87;
	if ($this->objDBNC==NULL){
		$this->ConectarDB();
		}
	if ($this->objDBNC!=NULL){
		$sql='SELECT idnumber FROM edu_enrollment WHERE idnumber='.$iDocumento.' AND cod_curso='.$iCurso.' AND grupo='.$iGrupo.' AND role='.$iRol.' AND peraca='.$iPerAca.'';
		$tabla=$this->objDBNC->ejecutasql($sql);
		if ($this->objDBNC->nf($tabla)>0){
			$res=true;
			}
		}
	return $res;
	}

function iRolExiste($iDocumento, $iCurso, $iPerAca){
	$res=-1;
	$iPerAca=87;
	if ($this->objDBNC==NULL){
		$this->ConectarDB();
		}
	if ($this->objDBNC!=NULL){
		$sql='SELECT role FROM edu_enrollment WHERE idnumber='.$iDocumento.' AND cod_curso='.$iCurso.' AND peraca='.$iPerAca.'';
		$tabla=$this->objDBNC->ejecutasql($sql);
		if ($this->objDBNC->nf($tabla)>0){
			$fila=$this->objDBNC->sf($tabla);
			$res=$fila['role'];
			}
		}
	return $res;
	}

function Matricular($iDocumento, $iCurso, $iGrupo, $iRol, $iPerAca, $idTercero, $objDB){
	$sError='';
	$iPerAca=87;
	if ($iCurso==0){$sError='NoCurso';}
	if ($sError==''){
		if ($this->bExiste($iDocumento, $iCurso, $iGrupo, $iRol, $iPerAca)){
			$sError='..';
			}
		}
	if ($sError==''){
		$idRolExiste=$this->iRolExiste($iDocumento, $iCurso, $iPerAca);
		if ($idRolExiste!=-1){
			//Ya tiene un rol, tenemos es que actualizar el rol....
			// -- Mirar si tiene muchos roles en OAI... tomar el rol superior... (3)
			switch ($iRol){
				case 3://Es un director de curso, tiene prevalencia y listo.
				if ($idRolExiste!=3){
					$sql='UPDATE edu_enrollment SET role=3 WHERE idnumber='.$iDocumento.' AND cod_curso='.$iCurso.' AND peraca='.$iPerAca.'';
					$resultado=$this->objDBNC->ejecutasql($sql);
					}
				break;
				case 6://Es un ACREDITAR de curso, tiene prevalencia excepto sobre directores.
				$bPasa=true;
				if ($idRolExiste==3){$bPasa=false;}
				if ($idRolExiste==6){$bPasa=false;}
				if (!$bPasa){
					//Solo faltaria comprobar si en OAI aun tiene rol de director o de acreditador
					}
				if ($bPasa){
					$sql='UPDATE edu_enrollment SET role=6 WHERE idnumber='.$iDocumento.' AND cod_curso='.$iCurso.' AND peraca='.$iPerAca.'';
					$resultado=$this->objDBNC->ejecutasql($sql);
					}
				break;
				default:
				$bPasa=true;
				if ($idRolExiste==3){$bPasa=false;}
				if ($idRolExiste==6){$bPasa=false;}
				if (!$bPasa){
					//Solo faltaria comprobar si en OAI aun tiene rol de director o de acreditador
					}
				if ($bPasa){
					$sql='UPDATE edu_enrollment SET role='.$iRol.' WHERE idnumber='.$iDocumento.' AND cod_curso='.$iCurso.' AND peraca='.$iPerAca.'';
					$resultado=$this->objDBNC->ejecutasql($sql);
					}				
				}
			$sError='..';
			}
		}
	if ($sError==''){
		//Aqui entra si no tiene un rol previo.
		if ($this->objDBNC!=NULL){
			$sql='INSERT INTO edu_enrollment (idnumber, cod_curso, grupo, role, peraca) VALUES ('.$iDocumento.', '.$iCurso.', '.$iGrupo.', '.$iRol.', '.$iPerAca.')';
			$result=$this->objDBNC->ejecutasql($sql);
			if ($result==false){$sError=$sql;}
			}
		}
	return $sError;
	}
function DesMatricular($iDocumento, $iCurso, $iGrupo, $iRol, $iPerAca){
	$sError='';
	$iPerAca=87;
	if ($iCurso==0){$sError='NoCurso';}
	if ($sError==''){
		if (!$this->bExiste($iDocumento, $iCurso, $iGrupo, $iRol, $iPerAca)){
			$sError='..';
			}
		}
	if ($sError==''){
		$sql='DELETE FROM edu_enrollment WHERE idnumber='.$iDocumento.' AND cod_curso='.$iCurso.' AND grupo='.$iGrupo.' AND role='.$iRol.' AND peraca='.$iPerAca.'';
		$this->objDBNC->ejecutasql($sql);
		}
	}
	}
//Septiembre 3 de 2015 - Se cambia la matricula, ya no se hace sobre ncontens sino que se hace sobre el tablero con origen = 17
class clsMatricula{
function bExisteV2($iPerAca, $idNav, $idTercero, $iCurso, $iGrupo, $iRol, $objDB){
	$res=false;
	$iPerAca=87;
	$iGrupo=1;
	$sql='SELECT TB.unad47activo, TB.unad47retirado FROM unad47tablero AS TB WHERE TB.unad47peraca='.$iPerAca.' AND TB.unad47idnav='.$idNav.' AND TB.unad47idrol='.$iRol.' AND TB.unad47idtercero='.$idTercero.' AND TB.unad47idcurso='.$iCurso.' AND TB.unad47numaula=1';
	$tabla=$objDB->ejecutasql($sql);
	if ($objDB->nf($tabla)>0){
		$res=true;
		}
	return $res;
	}
function Matricular_V2($iCurso, $iGrupo, $iRol, $iPerAca, $idTercero, $objDB){
	$sError='';
	/*
	$iPerAca=87;
	$idNav=21;
	//Entonces... rol director... con rol 3 y los demas con rol 4
	$iRol87=4;
	if ($iRol==3){$iRol87=3;}
	if ($iCurso==0){$sError='NoCurso';}
	if ($sError==''){
		if ($this->bExisteV2($iPerAca, $idNav, $idTercero, $iCurso, $iGrupo, $iRol87, $objDB)){
			$sql='UPDATE unad47tablero SET unad47activo="S" unad47retirado="N" WHERE unad47peraca='.$iPerAca.' AND unad47idnav='.$idNav.' AND unad47idrol='.$iRol87.' AND unad47idtercero='.$idTercero.' AND unad47idcurso='.$iCurso.' AND TB.unad47numaula=1';
			$result=$objDB->ejecutasql($sql);
			$sError='..';
			}
		}
	if ($sError==''){
		//Aqui entra si no tiene un rol previo.
		$sHoy=fecha_hoy();
		$iHora=fecha_hora();
		$iMinuto=fecha_minuto();
		$scampos='unad47peraca, unad47idnav, unad47idrol, unad47idtercero, unad47idcurso, unad47numaula, unad47activo, unad47retirado, unad47idgrupo, unad47idcead, unad47idceadasiste, unad47idmoodle, unad47fechaini, unad47horaini, unad47minini, unad47banderaimportacion, unad47fechamod, unad47horamod, unad47minmod, unad47origen';
		$svalores=''.$iPerAca.', '.$idNav.', '.$iRol87.', '.$idTercero.', '.$iCurso.', 1, "S", "N", 1, 0, 0, 0, "'.$sHoy.'", '.$iHora.', '.$iMinuto.', "N", "'.$sHoy.'", '.$iHora.', '.$iMinuto.', 17';
		$sql='INSERT INTO unad47tablero ('.$scampos.') VALUES ('.$svalores.');';
		$result=$objDB->ejecutasql($sql);
		if ($result==false){$sError=$sql;}
		}
	*/
	return $sError;
	}
function DesMatricular_V2($idTercero, $iCurso, $iGrupo, $iRol, $iPerAca, $objDB){
	$sError='';
	/*
	$idNav=21;
	$iPerAca=87;
	$iRol87=4;
	if ($iRol==3){$iRol87=3;}
	if ($iCurso==0){$sError='NoCurso';}
	if ($sError==''){
		if ($this->bExisteV2($iPerAca, $idNav, $idTercero, $iCurso, $iGrupo, $iRol87, $objDB)){
			$sql='UPDATE unad47tablero SET unad47activo="N" unad47retirado="S" WHERE unad47peraca='.$iPerAca.' AND unad47idnav='.$idNav.' AND unad47idrol='.$iRol87.' AND unad47idtercero='.$idTercero.' AND unad47idcurso='.$iCurso.' AND TB.unad47numaula=1';
			$result=$objDB->ejecutasql($sql);
			$sError='..';
			}
		}
	*/
	return $sError;
	}
function TipoRol($idRol, $objDB){
	$res=0;
	$sql='SELECT ofer10claserol FROM ofer10rol WHERE ofer10id='.$idRol.'';
	$tabla11=$objDB->ejecutasql($sql);
	if ($objDB->nf($tabla11)>0){
		$fila11=$objDB->sf($tabla11);
		$res=$fila11['ofer10claserol'];
		}
	return $res;
	}
function DesMatricular_V3($iPerAca, $idEscuela, $iCurso, $idTercero, $iRol, $objDB){
	$sError='';
	//Los tiporol 4 no se gestionan aqui.
	$sListaCursos='-99';
	$idOferta=0;
	$ofer38idrol=11;
	$ofer38grupo=0;
	$iTipoRol=$this->TipoRol($idRol, $objDB);
	switch($iTipoRol){
		case 1:
		case 2:
		case 3:
		//Roles de nivel escuela
		$sql='SELECT ofer08idcurso FROM ofer08oferta WHERE ofer08idper_aca='.$iPerAca.' AND ofer08idescuela='.$idEscuela.' AND ofer08cead=0 AND ofer08estadooferta=1';
		$tabla08=$objDB->ejecutasql($sql);
		while ($fila08=$objDB->sf($tabla08)){
			$sListaCursos=$sListaCursos.','.$fila08['ofer08idcurso'];
			}
		break;
		case 5:
		case 8:
		//Roles de nivel curso
		$sListaCursos=$iCurso;
		break;
		}
	$sql='SELECT ofer08id FROM ofer08oferta WHERE ofer08idper_aca='.$iPerAca.' AND ofer08idcurso IN ('.$sListaCursos.') AND ofer08cead=0';
	$tabla08=$objDB->ejecutasql($sql);
	while ($fila08=$objDB->sf($tabla08)){
		}
	return $sError;
	}
function Matricular_V3($iPerAca, $idEscuela, $iCurso, $idTercero, $idRol, $objDB, $bDebug=false){
	$sError='';
	$sDebug='';
	if ($bDebug){
		}else{
		//$sDebug=$sDebug.''.fecha_microtiempo().' - Debug apagado.<br>';
		}
	//La idea es que los tiporol 4 no se gestionan aqui, sino en la lib1730 por estado, se separa el manejo, uno es por curso y otro por escuela.
	$bConDirectores=false;
	$idOferta=0;
	$sListaCursos='-99';
	//Agosto 11 de 2017 - Se cambia el rol de 10 a 11...
	$ofer38idrol=10;
	//$ofer38idrol2=11;
	$ofer38grupo=0;
	$ofer38usuario=$_SESSION['unad_id_tercero'];
	$ofer38fechamat=fecha_hoy();
	$ofer38horamat=fecha_hora();
	$ofer38minmat=fecha_minuto();
	$scampos38='ofer38idoferta, ofer38idtercero, ofer38idrol, ofer38grupo, ofer38origenmatricula, ofer38id, ofer38activo, ofer38detalle, ofer38usuario, ofer38fechamat, ofer38horamat, ofer38minmat';
	$iTipoRol=$this->TipoRol($idRol, $objDB);
	switch($iTipoRol){
		case 1:
		case 2:
		case 3:
		//Roles de nivel escuela
		if ($iCurso==0){
			$sql='SELECT ofer08idcurso FROM ofer08oferta WHERE ofer08idper_aca='.$iPerAca.' AND ofer08idescuela='.$idEscuela.' AND ofer08cead=0 AND ofer08estadooferta=1';
			$tabla08=$objDB->ejecutasql($sql);
			while ($fila08=$objDB->sf($tabla08)){
				$sListaCursos=$sListaCursos.','.$fila08['ofer08idcurso'];
				$bConDirectores=true;
				}
			if ($bDebug){$sDebug=$sDebug.''.fecha_microtiempo().' - Se hace la matricula por escuela.<br>';}
			}else{
			$sListaCursos=$iCurso;
			}
		break;
		case 5:
		case 8:
		//Roles de nivel curso
		$sListaCursos=$iCurso;
		if ($bDebug){$sDebug=$sDebug.''.fecha_microtiempo().' - Se hace la matricula por curso {'.$iCurso.'}.<br>';}
		break;
		default:
		$sql='SELECT ofer08id, ofer08idcurso FROM ofer08oferta WHERE ofer08idper_aca='.$iPerAca.' AND ofer08idcurso='.$iCurso.' AND ofer08cead=0 AND ofer08estadooferta=1';
		$tabla08=$objDB->ejecutasql($sql);
		if ($objDB->nf($tabla08)>0){
			$fila08=$objDB->sf($tabla08);
			$idOferta=$fila08['ofer08id'];
			$bConDirectores=true;
			}
		if ($bDebug){$sDebug=$sDebug.''.fecha_microtiempo().' MATRICULA - Este rol no se matricula por aca.. {'.$iTipoRol.'}.<br>';}
		break;
		}
	$sql='SELECT ofer08id, ofer08idcurso FROM ofer08oferta WHERE ofer08idper_aca='.$iPerAca.' AND ofer08idcurso IN ('.$sListaCursos.') AND ofer08cead=0 AND ofer08estadooferta=1';
	$tabla08=$objDB->ejecutasql($sql);
	while ($fila08=$objDB->sf($tabla08)){
		$idOferta=$fila08['ofer08id'];
		if ($bDebug){$sDebug=$sDebug.''.fecha_microtiempo().' - MATRICULA - Procesando curso {'.$fila08['ofer08idcurso'].'}.<br>';}
		$sql='SELECT ofer38id, ofer38activo FROM ofer38matricula WHERE ofer38idtercero='.$idTercero.' AND ofer38idoferta='.$idOferta.' AND ofer38idrol='.$ofer38idrol.' AND ofer38grupo='.$ofer38grupo.' AND ofer38origenmatricula=17';
		$tabla38=$objDB->ejecutasql($sql);
		if ($objDB->nf($tabla38)==0){
			$ofer38id=tabla_consecutivo('ofer38matricula', 'ofer38id', '', $objDB);
			$svalores=''.$idOferta.', '.$idTercero.', '.$ofer38idrol.', '.$ofer38grupo.', 17, '.$ofer38id.', "S", "MATRICULA DESDE OAI - ACTORES", "'.$ofer38usuario.'", "'.$ofer38fechamat.'", '.$ofer38horamat.', '.$ofer38minmat.'';
			$sql='INSERT INTO ofer38matricula ('.$scampos38.') VALUES ('.$svalores.');';
			$result=$objDB->ejecutasql($sql);
			if ($result==false){
				}
			}else{
			$fila38=$objDB->sf($tabla38);
			if ($fila38['ofer38activo']!='S'){
				$sql='UPDATE ofer38matricula SET ofer38activo="S" WHERE ofer38id='.$fila38['ofer38id'].'';
				$result=$objDB->ejecutasql($sql);
				}
			}
		}
	if ($bConDirectores){}
	if (true){
		if ($bDebug){$sDebug=$sDebug.''.fecha_microtiempo().' MATRICULA - Se invoca la llamada al gestor de directores.. {'.$idOferta.'}.<br>';}
		if (!function_exists('f1730_GestionarDirectores')){
			require '../oai/lib1730.php';
			}
		list($sDebugDir)=f1730_GestionarDirectores($idOferta, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugDir;
		//Dic 3 de 2018 Se vuelve a habilitar el envio de cursos al repositorio al momento de guardar en actores.
		list($sError, $sDebugRep)=f1730_CargarCursoARepositorio($iPerAca, $iCurso, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugRep;
		}
	return array($sError, $sDebug);
	}
	//Fin de la clase.
	}
function f1711_DocXId($idTercero, $objDB){
	$iDocumento=0;
	$sql='SELECT unad11doc FROM unad11terceros WHERE unad11id='.$idTercero;
	$tabla=$objDB->ejecutasql($sql);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$iDocumento=$fila['unad11doc'];
		}
	return $iDocumento;
	}
function f1711_RolMoodle($idRolOrigen, $objDB){
	$iRol=0;
	$sql='SELECT T1.ofer09equivalmoodle FROM ofer10rol AS TB, ofer09claserol AS T1 WHERE TB.ofer10id='.$idRolOrigen.' AND TB.ofer10claserol=T1.ofer09id';
	$tabla=$objDB->ejecutasql($sql);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$iRol=$fila['ofer09equivalmoodle'];
		}
	return $iRol;
	}
//Funciones estandar de la libreria
function f1711_db_Guardar($valores, $objDB, $bDebug=false){
	$icodmodulo=1711;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1711='lg/lg_1711_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1711)){$mensajes_1711='lg/lg_1711_es.php';}
	require $mensajes_todas;
	require $mensajes_1711;
	$sError='';
	$sDebug='';
	$binserta=false;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$ofer11per_aca=numeros_validar($valores[1]);
	$ofer11idescuela=numeros_validar($valores[2]);
	$ofer11idcurso=numeros_validar($valores[3]);
	$ofer11idrol=numeros_validar($valores[4]);
	$ofer11id=numeros_validar($valores[5]);
	$ofer11idtercero=numeros_validar($valores[6]);
	$ofer11detalle=htmlspecialchars($valores[7]);
	//$ofer11fechaacceso=htmlspecialchars($valores[8]);
	//if ($ofer11fechaacceso==''){$sError=$ERR['ofer11fechaacceso'];}
	//if ($ofer11detalle==''){$sError=$ERR['ofer11detalle'];}
	if ($ofer11idtercero==0){$sError=$ERR['ofer11idtercero'];}
	//if ($ofer11id==''){$sError=$ERR['ofer11id'];}//CONSECUTIVO
	if ($ofer11idrol==''){$sError=$ERR['ofer11idrol'];}
	if ($ofer11idcurso==''){$sError=$ERR['ofer11idcurso'];}
	if ($ofer11idescuela==''){$sError=$ERR['ofer11idescuela'];}
	if ($ofer11per_aca==''){$sError=$ERR['ofer11per_aca'];}
	if ($sError==''){
		if ((int)$ofer11id==0){
			$sql='SELECT ofer11per_aca FROM ofer11actores WHERE ofer11per_aca='.$ofer11per_aca.' AND ofer11idescuela='.$ofer11idescuela.' AND ofer11idcurso='.$ofer11idcurso.' AND ofer11idrol='.$ofer11idrol.'';
			$result=$objDB->ejecutasql($sql);
			if ($objDB->nf($result)!=0){
				$sError=$ERR['existe'];
				}else{
				if (!seg_revisa_permiso($icodmodulo, 2, $objDB)){$sError=$ERR['2'];}
				}
			if ($sError==''){
				$ofer11id=tabla_consecutivo('ofer11actores', 'ofer11id', '', $objDB);
				if ($ofer11id==-1){$sError=$objDB->serror;}
				$binserta=true;
				}
			}else{
			if (!seg_revisa_permiso($icodmodulo, 3, $objDB)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		//Sacar el rol y el documento para manejar ncontest.
		//$iDocumento=f1711_DocXId($ofer11idtercero, $objDB);
		$iRol=f1711_RolMoodle($ofer11idrol, $objDB);
		//Si el campo ofer11detalle permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$ofer11detalle=str_replace('"', '\"', $ofer11detalle);
		$ofer11detalle=str_replace('&quot;', '\"', $ofer11detalle);
		if ($binserta){
			$ofer11fecharegistro=fecha_hoy();
			$scampos='ofer11per_aca, ofer11idescuela, ofer11idcurso, ofer11idrol, ofer11id, ofer11idtercero, ofer11detalle, ofer11fechaacceso, ofer11fecharegistro';
			$svalores=''.$ofer11per_aca.', '.$ofer11idescuela.', '.$ofer11idcurso.', '.$ofer11idrol.', '.$ofer11id.', "'.$ofer11idtercero.'", "'.$ofer11detalle.'", "00/00/0000", "'.$ofer11fecharegistro.'"';
			$sql='INSERT INTO ofer11actores ('.$scampos.') VALUES ('.$svalores.');';
			$result=$objDB->ejecutasql($sql);
			if ($result==false){
				$sError='Error critico al tratar de guardar Actores, por favor informe al administrador del sistema.<!-- '.$sql.' -->';
				}else{
				//$sError=$sql;
				if ($bAudita[2]){
					seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], 2, 0, $sql, $objDB);
					}
				//hacer la insersión en la tabla 38 (antes era en el ncontents. )
				$objN=new clsMatricula();
				if ($ofer11idcurso>0){
					//$iPerAca, $idNav, $idTercero, $iCurso, $iGrupo, $iRol87, $objDB
					//$sError=$objN->Matricular_V2($ofer11idcurso, 1, $iRol, $ofer11per_aca, $ofer11idtercero, $objDB);
					}
				list($sError, $sDebugMatricula)=$objN->Matricular_V3($ofer11per_aca, $ofer11idescuela, $ofer11idcurso, $ofer11idtercero, $ofer11idrol, $objDB, $bDebug);
				if ($sError=='..'){$sError='';}
				if ($sError!=''){
				//$sError='Intente matricular '.$iDocumento.' '.$ofer11idcurso.' '.$iRol.' '.$ofer11per_aca;
					$sError='Se ha insertado el actor, pero no fue posible matricularlo en ncontens, '.$sError;
					}
				}
			}else{
			$scampo1711[1]='ofer11idtercero';
			$scampo1711[2]='ofer11detalle';
			$svr1711[1]=$ofer11idtercero;
			$svr1711[2]=$ofer11detalle;
			$inumcampos=2;
			$sWhere='ofer11per_aca='.$ofer11per_aca.' AND ofer11idescuela='.$ofer11idescuela.' AND ofer11idcurso='.$ofer11idcurso.' AND ofer11idrol='.$ofer11idrol.'';
			$sql='SELECT * FROM ofer11actores WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$bActualizaMatricula=false;
			$result=$objDB->ejecutasql($sql);
			if ($objDB->nf($result)>0){
				$filaorigen=$objDB->sf($result);
				//Ver si se cambio el tercero.
				if ($ofer11idtercero!=$filaorigen['ofer11idtercero']){
					$bActualizaMatricula=true;
					}
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo1711[$k]]!=$svr1711[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo1711[$k].'="'.$svr1711[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				$sql='UPDATE ofer11actores SET '.$sdatos.' WHERE '.$sWhere.';';
				$result=$objDB->ejecutasql($sql);
				if ($result==false){
					$sError='Error critico al tratar de guardar Actores, por favor informe al administrador del sistema.<!-- '.$sql.' -->';
					}else{
					if ($bAudita[3]){
						seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], 3, 0, $sql, $objDB);
						}
					if ($bActualizaMatricula){
						$objN=new clsMatricula();
						$idPrevio=$filaorigen['ofer11idtercero'];
						if ($ofer11idcurso>0){//Solo si viene por curso...
							//$idDocPrevio=f1711_DocXId($filaorigen['ofer11idtercero'], $objDB);
							//$objN->DesMatricular_V2($idDocPrevio, $ofer11idcurso, 1, $iRol, $ofer11per_aca, $objDB);
							//$objN->Matricular_V2($iDocumento, $ofer11idcurso, 1, $iRol, $ofer11per_aca, $ofer11idtercero, $objDB);
							}
						if ($bDebug){$sDebug=$sDebug.''.fecha_microtiempo().' - Intenta matricular la modificacion a la 38...<br>';}
						list($sError, $sDebugMatricula)=$objN->Matricular_V3($ofer11per_aca, $ofer11idescuela, $ofer11idcurso, $ofer11idtercero, $ofer11idrol, $objDB, $bDebug);
						$sDebug=$sDebug.$sDebugMatricula;
						}
					}
				}
			}
		}
	return array($sError, $ofer11id, $sDebug);
	}
function f1711_db_Eliminar($params, $objDB){
	$icodmodulo=1711;
	$bAudita[4]=true;
	require 'app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1711='lg/lg_1711_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1711)){$mensajes_1711='lg/lg_1711_es.php';}
	require $mensajes_todas;
	require $mensajes_1711;
	$sError='';
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$ofer11id=numeros_validar($params[0]);
	$ofer11per_aca=numeros_validar($params[1]);
	$ofer11idescuela=numeros_validar($params[2]);
	$ofer11idcurso=numeros_validar($params[3]);
	$ofer11idrol=numeros_validar($params[4]);
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
		$sWhere='ofer11id='.$ofer11id.'';
		if ($ofer11idcurso!=0){
			$sql='SELECT * FROM ofer11actores WHERE '.$sWhere;
			$tabla=$objDB->ejecutasql($sql);
			if ($objDB->nf($tabla)>0){
				$fila=$objDB->sf($tabla);
				//$idDocPrevio=f1711_DocXId($fila['ofer11idtercero'], $objDB);
				$iRol=f1711_RolMoodle($ofer11idrol, $objDB);
				$objN=new clsMatricula();
				//$objN->DesMatricular_V2($idDocPrevio, $ofer11idcurso, 1, $iRol, $ofer11per_aca, $objDB);
				//list($sError, $sDebugMatricula)=$objN->Matricular_V3($ofer11per_aca, $ofer11idescuela, $ofer11idcurso, $ofer11idtercero, $ofer11idrol, $objDB, $bDebug);
				//$sDebug=$sDebug.$sDebugMatricula;
				}
			}
		$sql='DELETE FROM ofer11actores WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sql);
		if ($result==false){
			$sError='Error critico al tratar de eliminar Actores, por favor informe al administrador del sistema.<!-- '.$sql.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], 4, $ofer11id, $sql, $objDB);
				}
			}
		}
	return $sError;
	}
function f1711_TablaDetalleV0($params, $objDB){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1711=$APP->rutacomun.'lg/lg_1711_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1711)){$mensajes_1711=$APP->rutacomun.'lg/lg_1711_es.php';}
	require $mensajes_todas;
	require $mensajes_1711;
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	if (isset($params[101])==0){$params[101]=1;}
	if (isset($params[102])==0){$params[102]=20;}
	$pagina=$params[101];
	$lineastabla=$params[102];
	$babierta=true;
	$sqladd='';
	if (isset($params[103])==0){$params[103]='';}
	if (isset($params[104])==0){$params[104]=0;}
	if ($params[103]!=''){
		$sqladd=$sqladd.' AND TB.ofer11idcurso='.$params[103];
		}else{
		//ver si forza el curso
		if ($params[104]==1){
			$sqladd=$sqladd.' AND TB.ofer11idcurso=-99';
			}
		}
	if (isset($params[105])==0){$params[105]='';}
	if (isset($params[106])==0){$params[106]='';}
	if (isset($params[107])==0){$params[107]='';}
	if (isset($params[108])==0){$params[108]='';}
	if (isset($params[109])==0){$params[109]='';}
	if ($params[105]!=''){$sqladd=$sqladd.' AND T6.unad11doc LIKE "%'.$params[105].'%"';}
	if ($params[106]!=''){$sqladd=$sqladd.' AND TB.ofer11idcurso LIKE "%'.$params[106].'%"';}
	if ($params[107]!=''){$sqladd=$sqladd.' AND T3.mat_descripcion LIKE "%'.$params[107].'%"';}
	if ($params[108]!=''){$sqladd=$sqladd.' AND TB.ofer11idrol='.$params[108].'';}
	if ($params[109]!=''){$sqladd=$sqladd.' AND TB.ofer11idescuela='.$params[109].'';}
	//Octubre 11 de 2014 - Estas consultas son sumamente pesadas por el doble left join en la segunda consulta... la primera esta bien...
	//por lo tanto se separan para que el count lo haga una consulta y luego la de mostrar la tabla la haga otra pero con el limit.
	if ($params[104]==1){
		$sTitulos='Per_aca, Curso, Rol, Tipo Doc, Documento, Actor, Telefono, Correo, Fecha de acceso, Detalle';
		$sql='SELECT TB.ofer11per_aca, T4.ofer10nombre, T6.unad11tipodoc AS C6_td, T6.unad11doc AS C6_doc, T6.unad11razonsocial AS C6_nombre, T6.unad11telefono, T6.unad11correo, TB.ofer11fechaacceso, TB.ofer11detalle, TB.ofer11idescuela, TB.ofer11idcurso, TB.ofer11idrol, TB.ofer11idtercero, TB.ofer11id, T6.unad11aceptanotificacion, T6.unad11correonotifica, T6.unad11correoinstitucional, T6.unad11correofuncionario 
FROM ofer11actores AS TB, ofer10rol AS T4, unad11terceros AS T6 
WHERE TB.ofer11per_aca="'.$_SESSION['oai_per_aca'].'" AND TB.ofer11idrol=T4.ofer10id AND TB.ofer11idtercero=T6.unad11id '.$sqladd.' 
ORDER BY TB.ofer11per_aca DESC, T6.unad11razonsocial, T4.ofer10nombre';
		$tabladetalle=$objDB->ejecutasql($sql);
		$registros=$objDB->nf($tabladetalle);
		if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
		if ($registros>$lineastabla){
			$rbase=($pagina-1)*$lineastabla;
			$limite=' LIMIT '.$rbase.', '.$lineastabla;
			$tabladetalle=$objDB->ejecutasql($sql.$limite);
			}
		}else{
		$sql='SELECT TB.ofer11id
FROM ofer11actores AS TB, ofer10rol AS T4, unad11terceros AS T6 
WHERE TB.ofer11per_aca="'.$_SESSION['oai_per_aca'].'" AND TB.ofer11idrol=T4.ofer10id AND TB.ofer11idtercero=T6.unad11id '.$sqladd.'';
		$tabladetalle=$objDB->ejecutasql($sql);
		$registros=$objDB->nf($tabladetalle);
		$limite='';
		if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
		if ($registros>$lineastabla){
			$rbase=($pagina-1)*$lineastabla;
			$limite=' LIMIT '.$rbase.', '.$lineastabla;
			}
		$sTitulos='Per_aca, Escuela,CodCurso,Curso, Rol, Tipo Doc, Documento, Actor, Telefono, Correo, Fecha de acceso, Detalle';
		$sql='SELECT TB.ofer11per_aca, T2.exte01nombre, TB.ofer11idcurso, T3.unad40nombre, T4.ofer10nombre, T6.unad11tipodoc AS C6_td, T6.unad11doc AS C6_doc, T6.unad11razonsocial AS C6_nombre, T6.unad11telefono, T6.unad11correo, TB.ofer11fechaacceso, TB.ofer11detalle, TB.ofer11idescuela, TB.ofer11idrol, TB.ofer11idtercero, TB.ofer11idcurso, TB.ofer11id, T6.unad11aceptanotificacion, T6.unad11correonotifica, T6.unad11correoinstitucional, T6.unad11correofuncionario
FROM (ofer11actores AS TB LEFT JOIN exte01escuela AS T2 ON (TB.ofer11idescuela=T2.exte01id)) LEFT JOIN unad40curso AS T3 ON (TB.ofer11idcurso=T3.unad40id), ofer10rol AS T4, unad11terceros AS T6 
WHERE TB.ofer11per_aca="'.$_SESSION['oai_per_aca'].'" AND TB.ofer11idrol=T4.ofer10id AND TB.ofer11idtercero=T6.unad11id '.$sqladd.' 
ORDER BY T6.unad11razonsocial, T4.ofer10nombre';
		$tabladetalle=$objDB->ejecutasql($sql.$limite);
		}
	$sqllista=str_replace("'","|",$sql);
	$sqllista=str_replace('"',"|",$sqllista);
	$sErrConsulta='<input id="consulta_1711" name="consulta_1711" type="hidden" value="'.$sqllista.'"/>
<input id="titulos_1711" name="titulos_1711" type="hidden" value="'.$sTitulos.'"/>';
	$sTitulo='';
	if ($params[104]!=1){
		$sTitulo='
<td><b>'.$ETI['ofer11idescuela'].'</b></td>
<td><b>'.$ETI['ofer11idcurso'].'</b></td>';
		}
	$res=$sErrConsulta.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr class="fondoazul">'.$sTitulo.'
<td><b>'.$ETI['ofer11idrol'].'</b></td>
<td colspan="4"><b>'.$ETI['ofer11idtercero'].'</b></td>
<td align="right">
'.html_paginador("paginaf1711", $registros, $lineastabla, $pagina, "paginarf1711()").'
'.html_lpp("lppf1711", $lineastabla, "paginarf1711()").'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sprefijo='';
		$ssufijo='';
		if (false){
			$sprefijo='<b>';
			$ssufijo='</b>';
			}
		$sEscuela='';
		if ($params[104]!=1){
			$sEscuela='
<td>'.$sprefijo.cadena_notildes($filadet['exte01nombre']).$ssufijo.'</td>
<td>'.$sprefijo.$filadet['ofer11idcurso'].' '.cadena_notildes($filadet['unad40nombre']).$ssufijo.'</td>';
			}
		//, T6.unad11aceptanotificacion, T6.unad11correonotifica, T6.unad11correoinstitucional
		$et_correo=$filadet['unad11correo'];
		if (correo_VerificarDireccion($filadet['unad11correofuncionario'])){
			$et_correo=$filadet['unad11correofuncionario'];
			}else{
			if (correo_VerificarDireccion($filadet['unad11correoinstitucional'])){
				$et_correo=$filadet['unad11correoinstitucional'];
				}else{
				if ($filadet['unad11aceptanotificacion']=='S'){
					$et_correo=$filadet['unad11correonotifica'];
					}
				}
			}
		/*
		if (cadena_contiene($filadet['unad11correoinstitucional'], '@unad.edu.co')){
			$et_correo=$filadet['unad11correoinstitucional'];
			}else{
			if ($filadet['unad11aceptanotificacion']=='S'){
				$et_correo=$filadet['unad11correonotifica'];
				}
			}
		*/
		$res=$res.'<tr ';
		if(($tlinea%2)==0){$res=$res.'class="resaltetabla"';}
		$tlinea++;
		$res=$res.'>'.$sEscuela.'
<td>'.$sprefijo.cadena_notildes($filadet['ofer10nombre']).$ssufijo.'</td>
<td>'.$sprefijo.$filadet['C6_td'].' '.$filadet['C6_doc'].$ssufijo.'</td>
<td>'.$sprefijo.cadena_notildes($filadet['C6_nombre']).$ssufijo.'</td>
<td>'.$sprefijo.$filadet['unad11telefono'].$ssufijo.'</td>
<td>'.$sprefijo.$et_correo.$ssufijo.'</td>
<td>';
		if ($babierta){
			$res=$res.'<a href="javascript:cargaridf1711('."'".$filadet['ofer11id']."'".')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'</td></tr>';
		}
	$res=$res.'</table>';
	return utf8_encode($res);
	}
function TraerBusqueda_db_ofer11idcurso($sCodigo, $objDB){
	$sRespuesta='';
	$id=0;
	$sCodigo=trim($sCodigo);
	if ($sCodigo!=''){
		$sql='SELECT unad40id, unad40nombre, unad40id FROM unad40curso WHERE unad40id="'.$sCodigo.'"';
		$res=$objDB->ejecutasql($sql);
		if ($objDB->nf($res)!=0){
			$fila=$objDB->sf($res);
			$sRespuesta='<b>'.$fila['unad40id'].' '.$fila['unad40nombre'].'</b>';
			$id=$fila['unad40id'];
			}
		if ($sRespuesta==''){
			$sRespuesta='<span class="rojo">{'.$sCodigo.' No encontrado}</span>';
			}
		}
	return array($id, $sRespuesta);
	}
function TraerBusqueda_ofer11idcurso($params){
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$respuesta='';
	$scodigo=$params[0];
	$bxajax=true;
	if (isset($params[3])!=0){if ($params[3]==1){$bxajax=false;}}
	$id=0;
	if ($scodigo!=''){
		require 'app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		list($id, $respuesta)=TraerBusqueda_db_ofer11idcurso($scodigo, $objDB);
		}
	$objid=$params[1];
	$sdiv=$params[2];
	$objResponse=new xajaxResponse();
	$objResponse->assign($sdiv, 'innerHTML', $respuesta);
	$objResponse->assign($objid, 'value', $id);
	if ((int)$id>0){
		$objResponse->call('RevisaLlave');
		}
	return $objResponse;
	}

function f1711_TablaDetalleDirector($params, $objDB){
	require 'app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1711=$APP->rutacomun.'lg/lg_1711_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1711)){$mensajes_1711=$APP->rutacomun.'lg/lg_1711_es.php';}
	require $mensajes_todas;
	require $mensajes_1711;
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	if (isset($params[100])==''){$params[100]='';}
	if (isset($params[101])==0){$params[101]=1;}
	if (isset($params[102])==0){$params[102]=20;}
	$pagina=$params[101];
	$lineastabla=$params[102];
	$params[100]=numeros_validar($params[100]);
	if ($params[100]==''){$params[100]=-999;}
	$babierta=false;
	$sqladd='';
		$sListaCursos='-99';
		$sTitulos='Per_aca, Curso, Rol, Tipo Doc, Documento, Actor, Telefono, Correo, Fecha de acceso, Detalle';
		$sql='SELECT TB.ofer11per_aca, T4.ofer10nombre, TB.ofer11fechaacceso, TB.ofer11detalle, TB.ofer11idcurso, TB.ofer11id, T5.unad40nombre 
FROM ofer11actores AS TB, ofer10rol AS T4, unad40curso AS T5 
WHERE TB.ofer11idtercero='.$params[100].' AND TB.ofer11idrol=T4.ofer10id AND TB.ofer11idcurso=T5.unad40id '.$sqladd.' AND T4.ofer10claserol IN (1,2,3,4)
ORDER BY TB.ofer11per_aca DESC, T4.ofer10nombre';
		$tabladetalle=$objDB->ejecutasql($sql);
		$registros=$objDB->nf($tabladetalle);
		if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
		if ($registros>$lineastabla){
			$rbase=($pagina-1)*$lineastabla;
			$limite=' LIMIT '.$rbase.', '.$lineastabla;
			$tabladetalle=$objDB->ejecutasql($sql.$limite);
			}
	$sqllista=str_replace("'","|",$sql);
	$sqllista=str_replace('"',"|",$sqllista);
	$sErrConsulta='<input id="consulta_1711" name="consulta_1711" type="hidden" value="'.$sqllista.'"/>
<input id="titulos_1711" name="titulos_1711" type="hidden" value="'.$sTitulos.'"/>';
	$res=$sErrConsulta.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr class="fondoazul">
<td><b>'.$ETI['ofer11per_aca'].'</b></td>
<td colspan="2"><b>'.$ETI['ofer11idcurso'].'</b></td>
<td><b>'.$ETI['ofer11idrol'].'</b></td>
<td align="right">
'.html_paginador("paginaf1711", $registros, $lineastabla, $pagina, "paginarf1711()").'
'.html_lpp("lppf1711", $lineastabla, "paginarf1711()").'
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
		if ($babierta){
			$sLink='<a href="javascript:cargaridf1711('."'".$filadet['ofer11id']."'".')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sprefijo.$filadet['ofer11per_aca'].$ssufijo.'</td>
<td>'.$sprefijo.$filadet['ofer11idcurso'].$ssufijo.'</td>
<td>'.$sprefijo.cadena_notildes($filadet['unad40nombre']).$ssufijo.'</td>
<td>'.$sprefijo.cadena_notildes($filadet['ofer10nombre']).$ssufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	return utf8_encode($res);
	}
function f1711_HtmlTablaDirector($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	require 'app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$babierta=true;
	$sDetalle=f1711_TablaDetalleDirector($params, $objDB);
	$objResponse=new xajaxResponse();
	$objResponse->assign("div_f1711detalle","innerHTML",$sDetalle);
	return $objResponse;
	}

function f1711_GestionarMatriculaCurso($id08, $objDB, $bDebug=false){
	$sError='';
	$iDirectores=0;
	$iOtros=0;
	$sDebug='';
	$sql='UPDATE ofer38matricula AS TB SET TB.ofer38activo="N" WHERE TB.ofer38idoferta='.$id08.' AND TB.ofer38origenmatricula=17';
	$result=$objDB->ejecutasql($sql);
	//Traer el peraca y el curso.
	$sql='SELECT ofer08idper_aca, ofer08idcurso FROM ofer08oferta AS T8 WHERE T8.ofer08id='.$id08.'';
	$tabla=$objDB->ejecutasql($sql);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$idperaca=$fila['ofer08idper_aca'];
		$idCurso=$fila['ofer08idcurso'];
		}else{
		$sError='No se ha encontrado la oferta '.$id08.'';
		}
	if ($sError==''){
		$objN=new clsMatricula();
		//Matricularlos a todos.
		$sql='SELECT ofer11idescuela, ofer11idtercero, ofer11idrol FROM ofer11actores WHERE ofer11per_aca='.$idperaca.' AND ofer11idcurso='.$idCurso.'';
		$tablabase=$objDB->ejecutasql($sql);
		while ($filabase=$objDB->sf($tablabase)){
			list($sError, $sDebugMatricula)=$objN->Matricular_V3($idperaca, $filabase['ofer11idescuela'], $idCurso, $filabase['ofer11idtercero'], $filabase['ofer11idrol'], $objDB, $bDebug);
			$sDebug=$sDebug.$sDebugMatricula;
			}
		//Termina si no hay error.
		}
	return array($sError, $iDirectores, $iOtros, $sDebug);
	}
?>
