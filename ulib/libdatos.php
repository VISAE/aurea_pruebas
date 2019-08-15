<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2016 - 2018 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- jueves, 7 de julio de 2016
--- El proposito de esta libreria es unificar el manejo de datos comunes.... quitandoselo a la unad_librerias.
*/
function debug_Cronometrado($sMensaje, $iSegIni){
	$iSegFin=microtime(true);
	$iSegundos=$iSegFin-$iSegIni;
	$sDato=fecha_microtiempo().'[Tiempo: '.$iSegundos.'] '.$sMensaje.'<br>';
	return array($sDato, $iSegFin);
	}
function f107_VerificarPerfiles($idTercero, $idPeraca, $objDB, $bDebug=false){
	//Esta funcion aplica para todos los ajustes los grupos.
	// PERFILES DEL OAI.
	$sError='';
	$sDebug='';
	if (true){
	$sql='SELECT T1.ofer10claserol 
FROM ofer11actores AS TB, ofer10rol AS T1, exte02per_aca AS T2 
WHERE TB.ofer11idtercero='.$idTercero.' AND TB.ofer11per_aca=T2.exte02id AND T2.exte02vigente="S" AND TB.ofer11idrol=ofer10id 
GROUP BY T1.ofer10claserol';
	$troles=$objDB->ejecutasql($sql);
	if ($objDB->nf($troles)>0){
		if ($idPeraca==''){
			$sCondi='ofer01id=1';
			}else{
			$sCondi='ofer01per_aca='.$idPeraca.'';
			}
		$sql='SELECT ofer01perfiladmin, ofer01perfilcoordinador, ofer01perfildecano, ofer01perfildirector, ofer01perfilrevisor, ofer01perfilacreditador, ofer01per_aca 
FROM ofer01params 
WHERE '.$sCondi;
		$tperfiles=$objDB->ejecutasql($sql);
		if ($objDB->nf($tperfiles)>0){
			$fperfiles=$objDB->sf($tperfiles);
			$idPeraca=$fperfiles['ofer01per_aca'];
			}else{
			$sError='Se ha modificado el periodo acad&eacute;mico, no es posible procesar la solicitud {Periodo solicitado '.$idPeraca.'}';
			}
		if ($sError==''){
			while ($froles=$objDB->sf($troles)){
				//encontrar el perfil.
				$idperfil=0;
				$scampo='';
				switch ($froles['ofer10claserol']){
					case 1:$scampo='ofer01perfiladmin';break;
					case 2:$scampo='ofer01perfilcoordinador';break;
					case 3:$scampo='ofer01perfildecano';break;
					case 4:$scampo='ofer01perfilrevisor';break;
					case 5:$scampo='ofer01perfilacreditador';break;
					case 8:$scampo='ofer01perfildirector';break;
					}
				if ($scampo!=''){
					$idperfil=$fperfiles[$scampo];
					}
				if ($idperfil!=0){
					//incluir en la tabla de perfiles.
					login_activaperfil($idTercero, $idperfil, "S", $objDB);
					}
				}
			}
		}
	if ($idPeraca!=''){
		//Coordinadores van sobre la tabla 31
		$idPerfilCoordinador=1702;
		$sql='SELECT ofer31idprograma 
FROM ofer31programacoordinador 
WHERE ofer31per_aca='.$idPeraca.' AND ofer31idcoordinador='.$idTercero.'';
		$tperfiles=$objDB->ejecutasql($sql);
		if ($objDB->nf($tperfiles)>0){
			login_activaperfil($idTercero, $idPerfilCoordinador, 'S', $objDB);
			}
		}
		}
	//Perfiles Core.
	if (true){
		//Miramos que perfiles son reservados - no los mandamos a bloquear sino que los cargamos para tenerlos.
		$aPerfil=array();
		$idPerfil=array();
		$idLinea=array();
		$sCondi=array();
		$iPerfil=0;
		$sSQL='SELECT unad05id, unad05nombre FROM unad05perfiles WHERE unad05aplicativo=22 AND unad05reservado="S"';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Perfiles reservados de CORE '.$sSQL.'<br>';}
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			$iPerfil++;
			$aPerfil[$iPerfil]['id']=$fila['unad05id'];
			$aPerfil[$iPerfil]['estado']=0;
			}
		//Ya tiene todos los perfiles ahora habilitarlos segun corresponda.
		//Decano y Administrador.
		$idPerfil[1]=0;
		$idPerfil[2]=0;
		$idPerfil[3]=0;
		$idPerfil[4]=0;
		$idPerfil[5]=0;
		$idLinea[1]=0;
		$idLinea[2]=0;
		$idLinea[3]=0;
		$idLinea[4]=0;
		$idLinea[5]=0;
		$sCondi=array();
		$sCondi[1]='SELECT core12id FROM core12escuela WHERE core12iddecano='.$idTercero.'';
		$sCondi[2]='SELECT core12id FROM core12escuela WHERE core12idadministrador='.$idTercero.'';
		$sCondi[3]='SELECT core09id FROM core09programa WHERE core09iddirector='.$idTercero.'';
		$sCondi[4]='SELECT core19id FROM core19tutores WHERE core19idtercero='.$idTercero.' AND core19activo="S"';
		$sCondi[5]='SELECT core12id FROM core12escuela WHERE core12idrespcursocomun='.$idTercero.'';
		$sSQL='SELECT core00idperfildecano, core00idperfiladminescuela, core00idperfilliderprog, core00idperfiltutor, core00idperfilcursoscomunes FROM core00params WHERE core00id=1';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Configuraci&oacute;n de perfiles de CORE '.$sSQL.'<br>';}
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$idPerfil[1]=$fila['core00idperfildecano'];
			$idPerfil[2]=$fila['core00idperfiladminescuela'];
			$idPerfil[3]=$fila['core00idperfilliderprog'];
			$idPerfil[4]=$fila['core00idperfiltutor'];
			$idPerfil[5]=$fila['core00idperfilcursoscomunes'];
			}
		for ($j=1;$j<=5;$j++){
			if ($idPerfil[$j]!=0){
				//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consultando perfil '.$j.' en CORE<br>';}
				//Ubicar la linea en que el perfil coincide.
				for ($k=1;$k<=$iPerfil;$k++){
					//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Comparando '.$aPerfil[$k]['id'].' a '.$idPerfil[$j].' en CORE<br>';}
					if ($aPerfil[$k]['id']==$idPerfil[$j]){
						$idLinea[$j]=$k;
						if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Aplicando perfil '.$j.' en CORE<br>';}
						$k=$iPerfil+1;
						}
					}
				}
			if ($idLinea[$j]!=0){
				//Si hay perfil marcado asi que miramos si cumple el condicional..
				$sSQL=$sCondi[$j];
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Revisando aplicabilidad <b>'.$sSQL.'</b> en CORE<br>';}
				$tabla=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla)>0){
					$aPerfil[$idLinea[$j]]['estado']=1;
					if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' <b>Activando perfil '.$idPerfil[$j].'</b> en CORE<br>';}
					}
				}
			}
		// Ahora si marcar los perfiles.
		for ($k=1;$k<=$iPerfil;$k++){
			$bExistePerfil=false;
			$sEstado='N';
			$idPerfil=$aPerfil[$k]['id'];
			if ($aPerfil[$k]['estado']==1){$bExistePerfil=true;}
			if ($bExistePerfil){$sEstado='S';}
			login_activaperfil($idTercero, $idPerfil, $sEstado, $objDB);
			}
		}
	//Perfiles del Centralizador de calificaciones.
	if (true){
		//Miramos que perfiles son reservados - no los mandamos a bloquear sino que los cargamos para tenerlos.
		$aPerfil=array();
		$idPerfil=array();
		$idLinea=array();
		$sCondi=array();
		$iPerfil=0;
		$sSQL='SELECT unad05id, unad05nombre FROM unad05perfiles WHERE unad05aplicativo=24 AND unad05reservado="S"';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Perfiles reservados de C2 '.$sSQL.'<br>';}
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			$iPerfil++;
			$aPerfil[$iPerfil]['id']=$fila['unad05id'];
			$aPerfil[$iPerfil]['estado']=0;
			}
		//Ya tiene todos los perfiles ahora habilitarlos segun corresponda.
		//Decano y Administrador.
		$idPerfil[1]=0;
		$idPerfil[2]=0;
		$idLinea[1]=0;
		$idLinea[2]=0;
		$sCondi=array();
		$sCondi[1]='SELECT ofer08id FROM ofer08oferta WHERE ofer08idacomanamento='.$idTercero.' AND ofer08estadooferta=1';
		$sCondi[2]='SELECT core19id FROM core19tutores WHERE core19idtercero='.$idTercero.' AND core19activo="S"';
		$sSQL='SELECT ceca00idperfildirector, ceca00idperfiltutor FROM core00params WHERE core00id=1';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$idPerfil[1]=$fila['ceca00idperfildirector'];
			$idPerfil[2]=$fila['ceca00idperfiltutor'];
			}
		for ($j=1;$j<=2;$j++){
			if ($idPerfil[$j]!=0){
				//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consultando perfil '.$j.' en C2<br>';}
				//Ubicar la linea en que el perfil coincide.
				for ($k=1;$k<=$iPerfil;$k++){
					//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Comparando '.$aPerfil[$k]['id'].' a '.$idPerfil[$j].' en C2<br>';}
					if ($aPerfil[$k]['id']==$idPerfil[$j]){
						$idLinea[$j]=$k;
						if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Aplicando perfil '.$j.' en C2<br>';}
						$k=$iPerfil+1;
						}
					}
				}
			if ($idLinea[$j]!=0){
				//Si hay perfil marcado asi que miramos si cumple el condicional..
				$sSQL=$sCondi[$j];
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Revisando aplicabilidad <b>'.$sSQL.'</b> en C2<br>';}
				$tabla=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla)>0){
					$aPerfil[$idLinea[$j]]['estado']=1;
					if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' <b>Activando perfil '.$idPerfil[$j].'</b> en C2<br>';}
					}
				}
			}
		// Ahora si marcar los perfiles.
		for ($k=1;$k<=$iPerfil;$k++){
			$bExistePerfil=false;
			$sEstado='N';
			$idPerfil=$aPerfil[$k]['id'];
			if ($aPerfil[$k]['estado']==1){$bExistePerfil=true;}
			if ($bExistePerfil){$sEstado='S';}
			login_activaperfil($idTercero, $idPerfil, $sEstado, $objDB);
			}
		}
	//Perfiles Caracterizacion.
	if (true){
		//Miramos que perfiles son reservados - no los mandamos a bloquear sino que los cargamos para tenerlos.
		$aPerfil=array();
		$iPerfil=0;
		$sSQL='SELECT unad05id, unad05nombre FROM unad05perfiles WHERE unad05aplicativo=23 AND unad05reservado="S"';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Perfiles reservados de CARACTERIZACION '.$sSQL.'<br>';}
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			$iPerfil++;
			$aPerfil[$iPerfil]['id']=$fila['unad05id'];
			$aPerfil[$iPerfil]['estado']=0;
			}
		//Ya tiene todos los perfiles ahora habilitarlos segun corresponda.
		//Consejeros.
		$idLinea=0;
		$idPerfil=0;
		$idPerfilLider=0;
		$idLineaLider=0;
		$sSQL='SELECT cara00idperfilconsejero, cara00idperfillider FROM cara00config WHERE cara00id=1';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$idPerfil=$fila['cara00idperfilconsejero'];
			$idPerfilLider=$fila['cara00idperfillider'];
			}
		if ($idPerfil!=0){
			//Ubicar la linea para consejero.
			for ($k=1;$k<=$iPerfil;$k++){
				if ($aPerfil[$k]['id']==$idPerfil){
					$idLinea=$k;
					$k=$iPerfil+1;
					}
				}
			}
		if ($idPerfilLider!=0){
			//Ubicar la linea para lider de programa..
			for ($k=1;$k<=$iPerfil;$k++){
				if ($aPerfil[$k]['id']==$idPerfilLider){
					$idLineaLider=$k;
					$k=$iPerfil+1;
					}
				}
			}
		if ($idLinea!=0){
			//Si hay perfil marcado asi que miramos si es consejero.
			$sSQL='SELECT cara13id FROM cara13consejeros WHERE cara13idconsejero='.$idTercero.' AND cara13activo="S"';
			$tabla=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla)>0){
				$aPerfil[$idLinea]['estado']=1;
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Activando perfil de Consejero en CARACTERIZACION perfil '.$idPerfil.'<br>';}
				}
			}
		if ($idLineaLider!=0){
			//Si hay perfil marcado para lider zonal, marcarlo.
			$sSQL='SELECT cara21id FROM cara21lidereszona WHERE cara21idlider='.$idTercero.' AND cara21activo="S"';
			$tabla=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla)>0){
				$aPerfil[$idLineaLider]['estado']=1;
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Activando perfil de Lider Zonal en CARACTERIZACION perfil '.$idPerfilLider.'<br>';}
				}
			}
		// Ahora si marcar los perfiles.
		for ($k=1;$k<=$iPerfil;$k++){
			$idPerfil=$aPerfil[$k]['id'];
			$sEstado='N';
			if ($aPerfil[$k]['estado']==1){$sEstado='S';}
			login_activaperfil($idTercero, $idPerfil, $sEstado, $objDB);
			}
		}
	return array($sError, $sDebug);
	}
function f123_CentrosZona($idZona, $objDB, $bDebug=false){
	$sRes='-99';
	$sDebug='';
	$sSQL='SELECT unad24id FROM unad24sede WHERE unad24idzona='.$idZona.'';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$sRes=$sRes.','.$fila['unad24id'];
		}
	return array($sRes, $sDebug);
	}
function f146_ConsultaCombo($sWhere='', $objDB=NULL){
	switch ($sWhere){
		case 2100:
		//Solo los peracas donde se oferten laboratorios.
		$sWhere='ext02ofertalaboratorios="S"';
		break;
		case 2216:
		//Solo los peracas donde haya matricula
		$sIds='-99';
		$sSQL='SELECT core16peraca FROM core16actamatricula GROUP BY core16peraca';
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			$sIds=$sIds.','.$fila['core16peraca'];
			}
		$sWhere='exte02id IN ('.$sIds.')';
		break;
		case 2301:
		//Solo los peracas donde haya encuestas.
		$sIds='-99';
		$sSQL='SELECT cara01idperaca FROM cara01encuesta GROUP BY cara01idperaca';
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			$sIds=$sIds.','.$fila['cara01idperaca'];
			}
		$sWhere='exte02id IN ('.$sIds.')';
		break;
		case 2492:
		//Solo los peracas donde es profesor.
		$sIds='-99';
		$sSQL='SELECT cara01idperaca FROM cara01encuesta GROUP BY cara01idperaca';
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			$sIds=$sIds.','.$fila['cara01idperaca'];
			}
		$sWhere='exte02id IN ('.$sIds.')';
		break;
		}
	if ($sWhere!=''){$sWhere='WHERE '.$sWhere;}
	return 'SELECT exte02id AS id, CONCAT(CASE exte02vigente WHEN "S" THEN "" ELSE "[" END, exte02nombre," {",exte02id,"} ",CASE exte02vigente WHEN "S" THEN "" ELSE " - INACTIVO]" END) AS nombre FROM exte02per_aca '.$sWhere.' ORDER BY exte02vigente DESC, exte02id DESC';
	}
function f146_Contenedor($idPeraca, $objDB){
	$iRes=0;
	$sSQL='SELECT exte02contgrupos FROM exte02per_aca WHERE exte02id='.$idPeraca.'';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$iRes=$fila['exte02contgrupos'];
		}
	return $iRes;
	}
function f190_TraerUbicacionV2($sIp, $objDB, $bDebug=false){
	$sError='';
	$sDebug='';
	$latGrados=0;
	$latDecimas='';
	$lonGrados=0;
	$lonDecimas='';
	$iFecha=0;
	$iAgno=fecha_agno();
	for ($k=$iAgno;$k>=2016;$k--){
		$sNomTabla='unad71sesion'.$k;
		if ($k==2016){$sNomTabla='unad71sesion';}
		if ($objDB->bexistetabla($sNomTabla)){
			$sql='SELECT TB.unad71fechaini, TB.unad71latgrados, TB.unad71latdecimas, TB.unad71longrados, TB.unad71longdecimas FROM '.$sNomTabla.' AS TB WHERE TB.unad71iporigen="'.$sIp.'" AND TB.unad71latdecimas<>"" AND TB.unad71proximidad<100 ORDER BY TB.unad71proximidad LIMIT 0, 2';
			$tabla=$objDB->ejecutasql($sql);
			if ($objDB->nf($tabla)>0){
				$fila=$objDB->sf($tabla);
				$latGrados=$fila['unad71latgrados'];
				$latDecimas=$fila['unad71latdecimas'];
				$lonGrados=$fila['unad71longrados'];
				$lonDecimas=$fila['unad71longdecimas'];
				$iFecha=$fila['unad71fechaini'];
				$k=2000;
				}
			}
		}
	return array($sError, $latGrados, $latDecimas, $lonGrados, $lonDecimas, $iFecha, $sDebug);
	}
function f190_AddIpV2($sIp, $objDB, $id90=0, $bDebug=false, $bConUbicacion=false){
	$bRes=false;
	$sDebug='';
	$sql='SELECT unad90id FROM unad90controlip WHERE unad90ip="'.$sIp.'"';
	$tabla90=$objDB->ejecutasql($sql);
	if ($objDB->nf($tabla90)==0){
		$latGrados=0;
		$latDecimas='';
		$lonGrados=0;
		$lonDecimas='';
		$iFecha=0;
		if ($id90==0){
			$id90=tabla_consecutivo('unad90controlip','unad90id', '', $objDB);
			}
		if ($bConUbicacion){
			list($sError, $latGrados, $latDecimas, $lonGrados, $lonDecimas, $iFecha, $sDebugG)=f190_TraerUbicacionV2($sIp, $objDB, $bDebug);
			}
		$sCampos190='unad90ip, unad90id, unad90accion, unad90latgrados, unad90latdecimas, unad90longrados, unad90longdecimas, unad90detalle, unad90fechageo';
		$sValores190='"'.$sIp.'", '.$id90.', 0, '.$latGrados.', "'.$latDecimas.'", '.$lonGrados.', "'.$lonDecimas.'", "", "'.$iFecha.'"';
		$sql='INSERT INTO unad90controlip ('.$sCampos190.') VALUES ('.$sValores190.');';
		$result=$objDB->ejecutasql($sql);
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Insertando '.$sql.'<br>';}
		$bRes=true;
		}
	return array($bRes, $sDebug);
	}
function f1011_BloqueTercero($idTercero, $objDB){
	$iRes=0;
	$sError='';
	$sSQL='SELECT unad11idtablero FROM unad11terceros WHERE unad11id='.$idTercero.'';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$iRes=$fila['unad11idtablero'];
		if ($iRes==0){
			$idBloque=1;
			$iTopeBloque=40000;
			//No se le ha asignado un bloque....
			//Buscamos cual es el bloque que sigue... y luego cuandos estudiantes por bloque.
			$sSQL='SELECT unad00valor FROM unad00config WHERE unad00codigo="cont11_actual"';
			$tabla=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla)>0){
				$fila=$objDB->sf($tabla);
				$idBloque=$fila['unad00valor'];
				$iTopeBloque=f00_Leer('cont11_cupo', $objDB, $iTopeBloque);
				}else{
				$sSQL='INSERT INTO unad00config (unad00codigo, unad00nombre, unad00valor) VALUES ("cont11_actual", "Contenedor de terceros", 1), ("cont11_cupo", "Cupo por contenedor de terceros", 40000)';
				$tabla=$objDB->ejecutasql($sSQL);
				}
			//Verificamos que el tope no se supere...
			$iUsosBloque=0;
			$sSQL='SELECT COUNT(unad11id) AS Total FROM unad11terceros WHERE unad11idtablero='.$idBloque.'';
			$tabla=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla)>0){
				$fila=$objDB->sf($tabla);
				$iUsosBloque=$fila['Total'];
				}
			if ($iUsosBloque<$iTopeBloque){
				$iRes=$idBloque;
				}else{
				//Se lleno esto... hay que agregar otro...
				$iRes=$idBloque+1;
				$sSQL='UPDATE unad00config SET unad00valor='.$iRes.' WHERE unad00codigo="cont11_actual"';
				$tabla=$objDB->ejecutasql($sSQL);
				}
			if ($iRes!=0){
				//Simplemente marcar este tercero en el bloque..
				$sSQL='UPDATE unad11terceros SET unad11idtablero='.$iRes.' WHERE unad11id='.$idTercero.'';
				$tabla=$objDB->ejecutasql($sSQL);
				}
			//Termina cuando el usuario no estaba asignado a un contenedor.
			}
		if ($iRes!=0){
			//Si no existe el contenedor crearlo. (Son varias tablas....)
			$sTabla='core03plandeestudios_'.$iRes;
			$bIniciarContenedor=!$objDB->bexistetabla($sTabla);
			if ($bIniciarContenedor){
				$sSQL="CREATE TABLE ".$sTabla." (core03idestprograma int NOT NULL, core03idcurso int NOT NULL, core03id int NULL DEFAULT 0, core03idtercero int NULL DEFAULT 0, core03idprograma int NULL DEFAULT 0, core03itipocurso int NULL DEFAULT 0, core03obligatorio int NULL DEFAULT 0, core03numcreditos int NULL DEFAULT 0, core03nivelcurso int NULL DEFAULT 0, core03peracaaprueba int NULL DEFAULT 0, core03nota75 Decimal(15,2) NULL DEFAULT 0, core03fechanota75 int NULL DEFAULT 0, core03idusuarionota75 int NULL DEFAULT 0, core03nota25 Decimal(15,2) NULL DEFAULT 0, core03fechanota25 int NULL DEFAULT 0, core03idusuarionota25 int NULL DEFAULT 0, core03notahomologa Decimal(15,2) NULL DEFAULT 0, core03fechanotahomologa int NULL DEFAULT 0, core03idusuarionotahomo int NULL DEFAULT 0, core03detallehomologa Text NULL, core03fechainclusion int NULL DEFAULT 0, core03notafinal Decimal(15,2) NULL DEFAULT 0, core03formanota int NULL DEFAULT 0, core03estado int NULL DEFAULT 0)";
				$bResultado=$objDB->ejecutasql($sSQL);
				if ($bResultado==false){
					$sError='No ha sido posible iniciar la creaci&oacute;n del contenedor '.$iRes.', Por favor informe al administrador del sistema';
					$iRes=0;
					}
				}else{
				//Ya existe la tabla.
				}
			if ($bIniciarContenedor){
				$sSQL="ALTER TABLE ".$sTabla." ADD PRIMARY KEY(core03id)";
				$bResultado=$objDB->ejecutasql($sSQL);
				$sSQL="ALTER TABLE ".$sTabla." ADD UNIQUE INDEX core03plandeestudios_id(core03idcurso, core03idestprograma)";
				$bResultado=$objDB->ejecutasql($sSQL);
				$sSQL="ALTER TABLE ".$sTabla." ADD INDEX core03plandeestudios_padre(core03idestprograma)";
				$bResultado=$objDB->ejecutasql($sSQL);
				$sTabla='core04matricula_'.$iRes;
				$sSQL="CREATE TABLE ".$sTabla." (core04peraca int NOT NULL, core04tercero int NOT NULL, core04idcurso int NOT NULL, 
core04id int NULL DEFAULT 0, core04idaula int NULL DEFAULT 0, core04idrol int NULL DEFAULT 0, 
core04idnav int NULL DEFAULT 0, core04idgrupo int NULL DEFAULT 0, core04estadoengrupo int NULL DEFAULT 0, 
core04fechamatricula int NULL DEFAULT 0, core04origenmatricula int NULL DEFAULT 0, core04idcead int NULL DEFAULT 0, 
core04tienenota int NULL DEFAULT 0, core04idagenda int NULL DEFAULT 0, core04nota75 Decimal(15,2) NULL DEFAULT 0, 
core04fechanota75 int NULL DEFAULT 0, core04idusuarionota75 int NULL DEFAULT 0, core04nota25 Decimal(15,2) NULL DEFAULT 0, 
core04fechanota25 int NULL DEFAULT 0, core04idusuarionota25 int NULL DEFAULT 0, core04notahabilita Decimal(15,2) NULL DEFAULT 0, 
core04fechanotahabilita int NULL DEFAULT 0, core04idusuarionotahab int NULL DEFAULT 0, core04notasupletorio Decimal(15,2) NULL DEFAULT 0, 
core04fechanotasupletorio int NULL DEFAULT 0, core04idusuarionotasup int NULL DEFAULT 0, core04notafinal Decimal(15,2) NULL DEFAULT 0, 
core04estado int NULL DEFAULT 0, core04aplicoagenda int NULL DEFAULT 0, core04idprograma int NULL DEFAULT 0, 
core04nuevo int NULL DEFAULT 0, core04cursoequivalente int NULL DEFAULT 0, core04idmoodle int NULL DEFAULT 0, 
core04fechaultacceso int NULL DEFAULT 0, core04minultacceso int NULL DEFAULT 0, core04idtutor int NULL DEFAULT 0, 
core04est_aprob Decimal(15,2) NULL DEFAULT 0, core04est_nivel int NULL DEFAULT 0, core04est_75presenta int NULL DEFAULT 0, 
core04est_75cero int NULL DEFAULT 0, core04est_75noaprobado int NULL DEFAULT 0, core04est_75aprobado int NULL DEFAULT 0, 
core04est_25presenta int NULL DEFAULT 0, core04est_25cero int NULL DEFAULT 0, core04est_25noaprobado int NULL DEFAULT 0, 
core04est_25aprobado int NULL DEFAULT 0, core04est_100presenta int NULL DEFAULT 0, core04est_100cero int NULL DEFAULT 0, 
core04est_100noaprobado int NULL DEFAULT 0, core04est_100aprobado int NULL DEFAULT 0, core04idevaldocente int NULL DEFAULT 0, 
core04idregevaldoc int NULL DEFAULT 0, core04fechaexporta int NULL DEFAULT 0, core04minexporta int NULL DEFAULT 0, 
core04calificado int NULL DEFAULT 0)";
				$bResultado=$objDB->ejecutasql($sSQL);
				$sSQL="ALTER TABLE ".$sTabla." ADD PRIMARY KEY(core04id)";
				$bResultado=$objDB->ejecutasql($sSQL);
				$sSQL="ALTER TABLE ".$sTabla." ADD UNIQUE INDEX core04matricula_id(core04idcurso, core04tercero, core04peraca)";
				$bResultado=$objDB->ejecutasql($sSQL);
				$sSQL="ALTER TABLE ".$sTabla." ADD INDEX core04matricula_tutor(core04idtutor)";
				$bResultado=$objDB->ejecutasql($sSQL);
				$sTabla='core05actividades_'.$iRes;
				$sSQL="CREATE TABLE ".$sTabla." (core05idmatricula int NOT NULL, core05idfase int NOT NULL, core05idunidad int NOT NULL, core05idactividad int NOT NULL, core05id int NULL DEFAULT 0, core05peraca int NULL DEFAULT 0, core05tercero int NULL DEFAULT 0, core05idcurso int NULL DEFAULT 0, core05idaula int NULL DEFAULT 0, core05idgrupo int NULL DEFAULT 0, core05idnav int NULL DEFAULT 0, core05fechaapertura int NULL DEFAULT 0, core05fechacierre int NULL DEFAULT 0, core05fecharetro int NULL DEFAULT 0, core05idtutor int NULL DEFAULT 0, core05tipoactividad int NULL DEFAULT 0, core05puntaje75 int NULL DEFAULT 0, core05puntaje25 int NULL DEFAULT 0, core05nota Decimal(15,2) NULL DEFAULT 0, core05fechanota int NULL DEFAULT 0, core05acumula75 int NULL DEFAULT 0, core05acumula25 int NULL DEFAULT 0, core05estado int NULL DEFAULT 0, core05retroalimentacion Text NULL, core05rezagado int NULL DEFAULT 0, core05calificado int NULL DEFAULT 0, core05idcupolab int NULL DEFAULT 0)";
				$bResultado=$objDB->ejecutasql($sSQL);
				$sSQL="ALTER TABLE ".$sTabla." ADD PRIMARY KEY(core05id)";
				$bResultado=$objDB->ejecutasql($sSQL);
				$sSQL="ALTER TABLE ".$sTabla." ADD UNIQUE INDEX core05actividades_id(core05idactividad, core05idunidad, core05idfase, core05idmatricula)";
				$bResultado=$objDB->ejecutasql($sSQL);
				$sSQL="ALTER TABLE ".$sTabla." ADD INDEX core05actividades_padre(core05idmatricula)";
				$bResultado=$objDB->ejecutasql($sSQL);
				$sSQL="ALTER TABLE ".$sTabla." ADD INDEX core05actividades_tercero(core05tercero)";
				$bResultado=$objDB->ejecutasql($sSQL);
				$sSQL="ALTER TABLE ".$sTabla." ADD INDEX core05actividades_curso(core05idcurso)";
				$bResultado=$objDB->ejecutasql($sSQL);
				$sSQL="ALTER TABLE ".$sTabla." ADD INDEX core05actividades_grupo(core05idgrupo)";
				$bResultado=$objDB->ejecutasql($sSQL);
				$sSQL="ALTER TABLE ".$sTabla." ADD INDEX core05actividades_estado(core05estado)";
				$bResultado=$objDB->ejecutasql($sSQL);
				}
			}
		}else{
		$sError='No se ha encontrado el tercero Ref '.$idTercero.'';
		}
	return array($iRes, $sError);
	}
function f1527_EsLider($idTercero, $objDB, $bDebug=false){
	$bRes=false;
	$sDebug='';
	$sSQL='SELECT bita27id FROM bita27equipotrabajo WHERE bita27idlider='.$idTercero.'';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$bRes=true;
		}
	return array($bRes, $sDebug);
	}
function f2200_ImportarTutores($idPeraca, $objDB, $bDebug=false){
	/*
	Este proceso revisa que haya datos en las tablas core19 y core20 que son las tablas de detallar informacion para los tutores.
	*/
	$sError='';
	$sDebug='';
	//Saber que contenedor de grupos tiene el peraca.
	$idContenedor=f146_Contenedor($idPeraca, $objDB);
	if ($idContenedor==0){
		$sError='No se ha definido un contenedor para los grupos del periodo '.$idPeraca;
		}
	if ($sError==''){
		/*
		SELECT TR.ins_estudiante, T1.cur_materia, T1.grupo, T1.cur_docente 
FROM registro AS TR, cursos_periodos AS T1 
WHERE TR.ano=611 AND TR.ins_novedad=79
AND TR.ins_curso=T1.consecutivo AND T1.cur_edificio<>99
		*/
		$iHoy=fecha_DiaMod();
		$core20id=tabla_consecutivo('core20asignacion','core20id', '', $objDB);
		$sSQL='SELECT core06idtutor, core06idcurso  
FROM core06grupos_'.$idContenedor.'
WHERE core06peraca='.$idPeraca.' AND core06idtutor<>0
GROUP BY core06idtutor, core06idcurso
ORDER BY core06idtutor';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Listado de carga a verificar '.$sSQL.' <br>';}
		$tabla=$objDB->ejecutasql($sSQL);
		$idTutor=-1;
		while($fila=$objDB->sf($tabla)){
			if ($idTutor!=$fila['core06idtutor']){
				$idTutor=$fila['core06idtutor'];
				if ($idTutor!=0){
					//Revisamos que el tutor exista.
					$sSQL='SELECT core19idtercero FROM core19tutores WHERE core19idtercero='.$idTutor.'';
					$tabla19=$objDB->ejecutasql($sSQL);
					if ($objDB->nf($tabla19)==0){
						//Lo insertamos porque no esta.
						$sSQL='INSERT INTO core19tutores (core19idtercero, core19id, core19activo, core19idzona, core19idsede, core19formavincula) VALUES ('.$idTutor.', '.$idTutor.', "S", 0, 0, 0)';
						$tabla19=$objDB->ejecutasql($sSQL);
						if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Insertando tutor '.$sSQL.' <br>';}
						}
					}
				}
			if ($idTutor!=0){
				//El tutor si existe... ahora a revisar que la carga este.
				$sSQL='SELECT core20id FROM core20asignacion WHERE core20idtutor='.$idTutor.' AND core20idperaca='.$idPeraca.' AND core20idcurso='.$fila['core06idcurso'].'';
				$tabla20=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla20)==0){
					//Lo insertamos porque no esta.
					$sSQL='INSERT INTO core20asignacion (core20idtutor, core20idperaca, core20idcurso, core20id, core20numestudiantes, core20fechaingregistro, core20fechaactualiza) VALUES ('.$idTutor.', '.$idPeraca.', '.$fila['core06idcurso'].', '.$core20id.', 0, '.$iHoy.', '.$iHoy.')';
					$tabla20=$objDB->ejecutasql($sSQL);
					if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Insertando carga '.$sSQL.' <br>';}
					$core20id++;
					}
				}
			}
		//Una vez alimentados los grupos, se debe actualizar la tabla de oferta.
		$objDBRyC=TraerDBRyC();
		if ($objDBRyC==NULL){
			//Armar la data del los grupos.
			$sSQL='SELECT ofer08id, ofer08idcurso FROM ofer08oferta WHERE ofer08idper_aca='.$idPeraca.' AND ofer08idacomanamento=0';
			$tabla=$objDB->ejecutasql($sSQL);
			while ($fila=$objDB->sf($tabla)){
				$sSQL='SELECT core06iddirector FROM core06grupos_'.$idContenedor.' WHERE core06peraca='.$idPeraca.' AND core06idcurso='.$fila['ofer08idcurso'].' AND core06iddirector>0 ORDER BY core06consec LIMIT 0, 1';
				$tabla06=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla06)>0){
					$fila06=$objDB->sf($tabla06);
					$sSQL='UPDATE ofer08oferta SET ofer08idacomanamento='.$fila06['core06iddirector'].' WHERE ofer08id='.$fila['ofer08id'].'';
					$result=$objDB->ejecutasql($sSQL);
					}else{
					//Es posible que el director venga en el grupo 0
					$sSQL='SELECT idnumber FROM sw_edu_enrollment_final WHERE cod_curso="'.$fila['ofer08idcurso'].'A_'.$idPeraca.'" AND role=3 LIMIT 0, 1';
					$tabla06=$objDB->ejecutasql($sSQL);
					if ($objDB->nf($tabla06)>0){
						$fila06=$objDB->sf($tabla06);
						$idTercero=0;
						$sSQL='SELECT unad11id FROM unad11terceros WHERE unad11doc="'.$fila06['idnumber'].'"';
						$tabla11=$objDB->ejecutasql($sSQL);
						if ($objDB->nf($tabla11)>0){
							$fila11=$objDB->sf($tabla11);
							$idTercero=$fila11['unad11id'];
							}
						if ($idTercero!=0){
							$sSQL='UPDATE ofer08oferta SET ofer08idacomanamento='.$idTercero.' WHERE ofer08id='.$fila['ofer08id'].'';
							$result=$objDB->ejecutasql($sSQL);
							}
						}
					}
				}
			}else{
			//Armamos el acompa;amiento desde ryc
			$sSQL='SELECT codigo_curso, documento FROM direccion_academica WHERE peraca='.$idPeraca.' AND id_rol=3';
			$tablad=$objDBRyC->ejecutasql($sSQL);
			while($filad=$objDBRyC->sf($tablad)){
				$sDoc=$filad['documento'];
				$sCurso=$filad['codigo_curso'];
				$idDirector=0;
				$sSQL='SELECT unad11id FROM unad11terceros WHERE unad11doc="'.$sDoc.'" AND unad11tipodoc="CC"';
				$tabla11=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla11)==0){
					unad11_importar_V2($sDoc, '', $objDB);
					$tabla11=$objDB->ejecutasql($sSQL);
					}
				if ($objDB->nf($tabla11)>0){
					$fila11=$objDB->sf($tabla11);
					$idDirector=$fila11['unad11id'];
					}
				//Actualizar la oferta de ser necesario.
				$sSQL='SELECT ofer08id, ofer08idacomanamento FROM ofer08oferta WHERE ofer08idper_aca='.$idPeraca.' AND ofer08idcurso='.$sCurso.'';
				$tabla=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla)>0){
					$fila=$objDB->sf($tabla);
					if ($fila['ofer08idacomanamento']!=$idDirector){
						$sSQL='UPDATE ofer08oferta SET ofer08idacomanamento='.$idDirector.' WHERE ofer08id='.$fila['ofer08id'].'';
						$result=$objDB->sf($tabla);
						}
					}else{
					//@@@@ Poseemos problemas un curso que no esta ofertado...
					}
				}
			}
		}
	if ($sError==''){
		}
	return array($sError, $sDebug);
	}
function f2205_ArmarAgendaCursoEstudiante($idPeraca, $idTercero, $idCurso, $objDB, $idContTercero=0, $bDebug=false){
	$sError='';
	$sErrCurso='';
	$sDebug='';
	$sCampos05='core05idmatricula, core05idfase, core05idunidad, core05idactividad, core05id, 
core05peraca, core05tercero, core05idcurso, core05idaula, core05idgrupo, 
core05idnav, core05fechaapertura, core05fechacierre, core05fecharetro, core05idtutor, 
core05tipoactividad, core05puntaje75, core05puntaje25, core05nota, core05fechanota, 
core05acumula75, core05acumula25, core05retroalimentacion, core05estado, core05rezagado';
	$sValores05='';
	if ($idContTercero==0){
		list($idContTercero, $sError)=f1011_BloqueTercero($idTercero, $objDB);
		}
	if ($sError==''){
		$iHoy=fecha_DiaMod();
		$sTabla04='core04matricula_'.$idContTercero;
		$sTabla05='core05actividades_'.$idContTercero;
		$core05id=tabla_consecutivo($sTabla05, 'core05id', '', $objDB);
		$sSQL4='SELECT TB.core04id, TB.core04idaula, TB.core04idagenda, TB.core04cursoequivalente, TB.core04idgrupo, TB.core04idtutor, TB.core04idnav, TB.core04idmoodle 
FROM '.$sTabla04.' AS TB 
WHERE TB.core04tercero='.$idTercero.' AND TB.core04peraca='.$idPeraca.' AND TB.core04idcurso='.$idCurso.' AND TB.core04aplicoagenda=0';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Matricula del curso. '.$sSQL4.'<br>';}
		$tabla04=$objDB->ejecutasql($sSQL4);
		while($fila04=$objDB->sf($tabla04)){
			$sCodRevisa=$idCurso;
			if ($fila04['core04cursoequivalente']!=0){$sCodRevisa=$fila04['core04cursoequivalente'];}
			if ($fila04['core04idagenda']==0){
				//Encontrar la agenda que le corresponda.
				$ofer08idagenda=0;
				$ofer08idnav=0;
				$core04idmoodle=0;
				$sSQL='SELECT ofer08estadooferta, ofer08estadocampus, ofer08idagenda, ofer08idnav, ofer08idcursonav, ofer08idescuela FROM ofer08oferta WHERE ofer08idcurso="'.$sCodRevisa.'" AND ofer08idper_aca='.$idPeraca.' AND ofer08cead=0';
				$tabla08=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla08)>0){
					$fila=$objDB->sf($tabla08);
					if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Cargando oferta del curso '.$sCodRevisa.'<br>';}
					if ($fila['ofer08estadooferta']==1){
						switch($fila['ofer08estadocampus']){
							case 10:
							case 12:
							$ofer08idagenda=$fila['ofer08idagenda'];
							$ofer08idnav=$fila['ofer08idnav'];
							$core04idmoodle=$fila['ofer08idcursonav'];
							break;
							default:
							$sErrCurso='Estado oferta: En Certificaci&oacute;n';
							break;
							}
						}else{
						$sErrCurso='Estado oferta: Cancelado';
						}
					}
				if ($ofer08idagenda!=0){
					$sSQL='UPDATE '.$sTabla04.' SET core04idagenda='.$ofer08idagenda.', core04idnav='.$ofer08idnav.', core04idmoodle='.$core04idmoodle.' WHERE core04id='.$fila04['core04id'].'';
					$result=$objDB->ejecutasql($sSQL);
					}
				}else{
				$ofer08idagenda=$fila04['core04idagenda'];
				$ofer08idnav=$fila04['core04idnav'];
				$core04idmoodle=$fila04['core04idmoodle'];
				}
			if ($sErrCurso==''){
				$idGrupo=$fila04['core04idgrupo'];
				//Llega con id de agenda. tenemos que saber el aula.... para saber el aula debe tener grupo...
				if ($idGrupo==0){
					$sErrCurso='Sin grupo';
					}
				}
			if ($sErrCurso==''){
				//Traer la agenda
				$sValores05='';
				$sSQL='SELECT TB.ofer18fase, TB.ofer18unidad, TB.ofer18idactividad, 
CONCAT(SUBSTR(TB.ofer18fechainicio, 7, 4), SUBSTR(TB.ofer18fechainicio, 4, 2), SUBSTR(TB.ofer18fechainicio, 1, 2)) AS FechaIni, 
CONCAT(SUBSTR(TB.ofer18fechacierrre, 7, 4), SUBSTR(TB.ofer18fechacierrre, 4, 2), SUBSTR(TB.ofer18fechacierrre, 1, 2)) AS FechaCierre, 
CONCAT(SUBSTR(TB.ofer18fecharetro, 7, 4), SUBSTR(TB.ofer18fecharetro, 4, 2), SUBSTR(TB.ofer18fecharetro, 1, 2)) AS FechaRetro, 
TB.ofer18peso, TB.ofer18detalle, T4.ofer04idtipoactividad
FROM ofer18cargaxnavxdia AS TB, ofer04cursoactividad AS T4 
WHERE TB.ofer18curso='.$sCodRevisa.' AND TB.ofer18per_aca='.$idPeraca.' AND TB.ofer18numaula='.$fila04['core04idaula'].' AND TB.ofer18idactividad=T4.ofer04id';
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consultando agenda a insertar '.$sSQL.'<br>';}
				$tabla05=$objDB->ejecutasql($sSQL);
				while($fila05=$objDB->sf($tabla05)){
					$core05tipoactividad=$fila05['ofer04idtipoactividad'];
					if ($sValores05!=''){$sValores05=$sValores05.', ';}
					$sValores05=$sValores05.'('.$fila04['core04id'].', '.$fila05['ofer18fase'].', '.$fila05['ofer18unidad'].', '.$fila05['ofer18idactividad'].', '.$core05id.', 
'.$idPeraca.', '.$idTercero.', '.$idCurso.', '.$fila04['core04idaula'].', '.$fila04['core04idgrupo'].', 
'.$ofer08idnav.', '.$fila05['FechaIni'].', '.$fila05['FechaCierre'].', '.$fila05['FechaRetro'].', '.$fila04['core04idtutor'].', 
'.$core05tipoactividad.', 0, 0, 0, 0, 
0, 0, "", 0, 0)';
					$core05id++;
					}
				if ($sValores05!=''){
					$sSQL='INSERT INTO '.$sTabla05.'('.$sCampos05.') VALUES '.$sValores05.'';
					$result=$objDB->ejecutasql($sSQL);
					if ($result==false){
						$sErrCurso='Falla al intentar guardar la agenda';
						if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Error insertando agenda <b>'.$sSQL.'</b><br>';}
						}else{
						//Actualizar la fila04
						$sSQL='UPDATE '.$sTabla04.' SET core04aplicoagenda='.$iHoy.' WHERE core04id='.$fila04['core04id'].'';
						$result=$objDB->ejecutasql($sSQL);
						}
					}
				}
			}
		}
	return array($sError, $sErrCurso, $sDebug);
	}
function f2206_InfoGrupo($idPeraca, $idGrupo, $objDB, $idContenedor=0, $bDebug=false){
	$sRes='{'.$idGrupo.'}';
	$sDebug='';
	if ($idContenedor==0){
		$idContenedor=f146_Contenedor($idPeraca, $objDB);
		}
	if ($idContenedor!=0){
		$sSQL='SELECT TB.core06consec, TB.core06idtutor, T11.unad11razonsocial, T11.unad11correofuncionario, T11.unad11correoinstitucional 
FROM core06grupos_'.$idContenedor.' AS TB, unad11terceros AS T11 
WHERE TB.core06id='.$idGrupo.' AND TB.core06idtutor=T11.unad11id';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$sMail=$fila['unad11correofuncionario'];
			$sRes='<b>'.$fila['core06consec'].'</b> Tutor: <b>'.cadena_notildes($fila['unad11razonsocial']).' '.$sMail.'</b>';
			}
		}
	return array($sRes, $sDebug);
	}
function f2206_TutorDirectorDesdeRyC($idPeraca, $idCurso, $idGrupo, $objDB, $objDBRyC, $bDebug=false){
	$sDebug='';
	$idTutor=0;
	$sSQL='SELECT cur_docente FROM cursos_periodos AS T1 WHERE T1.peraca='.$idPeraca.' AND T1.cur_materia='.$idCurso.' AND T1.grupo='.$idGrupo.' AND T1.estado="A" AND T1.cur_edificio<>99';
	$tablad=$objDBRyC->ejecutasql($sSQL);
	if ($objDBRyC->nf($tablad)>0){
		$filad=$objDBRyC->sf($tablad);
		$sDoc=$filad['cur_docente'];
		$sSQL='SELECT unad11id FROM unad11terceros WHERE unad11doc="'.$sDoc.'" AND unad11tipodoc="CC"';
		$tabla11=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla11)==0){
			unad11_importar_V2($sDoc, '', $objDB);
			$tabla11=$objDB->ejecutasql($sSQL);
			}
		if ($objDB->nf($tabla11)>0){
			$fila11=$objDB->sf($tabla11);
			$idTutor=$fila11['unad11id'];
			}
		}
	$idDirector=0;
	$sSQL='SELECT documento FROM direccion_academica WHERE peraca='.$idPeraca.' AND codigo_curso="'.$idCurso.'" AND id_rol=3';
	$tablad=$objDBRyC->ejecutasql($sSQL);
	if ($objDBRyC->nf($tablad)>0){
		$filad=$objDBRyC->sf($tablad);
		$sDoc=$filad['documento'];
		$sSQL='SELECT unad11id FROM unad11terceros WHERE unad11doc="'.$sDoc.'" AND unad11tipodoc="CC"';
		$tabla11=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla11)==0){
			unad11_importar_V2($sDoc, '', $objDB);
			$tabla11=$objDB->ejecutasql($sSQL);
			}
		if ($objDB->nf($tabla11)>0){
			$fila11=$objDB->sf($tabla11);
			$idDirector=$fila11['unad11id'];
			}
		}
	return array($idTutor, $idDirector, $sDebug);
	}
function f2206_CrearGrupo($idPeraca, $idCurso, $idGrupo, $params, $objDB, $objDBRyC, $idContPeraca=0, $bDebug=false){
	$sError='';
	$sDebug='';
	$core06grupoidforma=0;
	$core06grupominest=5;
	$core06grupomaxest=5;
	$core06fechatopearmado=0;
	$core06idtutor=0;
	$core06iddirector=0;
	$core06idestudiantelider=0;
	$core06numinscritos=0;
	$core06codigogrupo='';
	$core06estado=1;
	$core06idcead=0;
	if ($idContPeraca==0){
		$idContPeraca=f146_Contenedor($idPeraca, $objDB);
		}
	$idAula=1;
	if ($idGrupo>700){$idAula=2;}
	if ($idGrupo>1400){$idAula=3;}
	if ($idGrupo>2100){$idAula=4;}
	if ($idGrupo>2800){$idAula=5;}
	$id06aula=$idAula;
	if ($objDBRyC==NULL){
		$sSQL='SELECT core96idtercero, core96role FROM core96tmp WHERE core96curso='.$idCurso.' AND core96peraca='.$idPeraca.' AND core96grupo='.$idGrupo.' AND core96role IN (3, 4)';
		$tablab=$objDB->ejecutasql($sSQL);
		while($filab=$objDB->sf($tablab)){
			if ($filab['core96role']==4){
				$core06idtutor=$filab['core96idtercero'];
				}else{
				$core06iddirector=$filab['core96idtercero'];
				}
			}
		if ($core06idtutor==0){$core06idtutor=$core06iddirector;}
		}else{
		//Traer el tutor y el director de ryc
		list($core06idtutor, $core06iddirector, $sDebug)=f2206_TutorDirectorDesdeRyC($idPeraca, $idCurso, $idGrupo, $objDB, $objDBRyC, $bDebug);
		}
	$sTabla06='core06grupos_'.$idContPeraca;
	$id06=tabla_consecutivo($sTabla06, 'core06id', '', $objDB);
	$sSQL='INSERT INTO '.$sTabla06.' (core06peraca, core06idcurso, core06consec, core06id, core06idaula, core06grupoidforma, core06grupominest, core06grupomaxest, core06fechatopearmado, core06idtutor, core06iddirector, core06idestudiantelider, core06numinscritos, core06codigogrupo, core06estado, core06idcead) VALUES ('.$idPeraca.', '.$idCurso.', '.$idGrupo.', '.$id06.', '.$idAula.', '.$core06grupoidforma.', '.$core06grupominest.', '.$core06grupomaxest.', '.$core06fechatopearmado.', '.$core06idtutor.', '.$core06iddirector.', '.$core06idestudiantelider.', '.$core06numinscritos.', "'.$core06codigogrupo.'", '.$core06estado.', '.$core06idcead.')';
	$result=$objDB->ejecutasql($sSQL);
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Se crea el grupo '.$sSQL.'<br>';}
	return array($sError, $sDebug);
	}
function f2206_IgualarTutores($idPeraca, $objDB, $idContPeraca=0, $bDebug=false){
	$sError='';
	$sDebug='';
	if ($idContPeraca==0){
		$idContPeraca=f146_Contenedor($idPeraca, $objDB);
		}
	if ($idContPeraca==0){
		$sError='No se ha definido un contenedor para el periodo '.$idPeraca.'';
		}
	if ($sError==''){
		$sSQL='SHOW TABLES LIKE "core04%"';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Total Periodo: Lista de contenedores: '.$sSQL.'<br>';}
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			$iContenedor=substr($fila[0], 16);
			$sSQL='UPDATE core06grupos_'.$idContPeraca.' AS T6, core04matricula_'.$iContenedor.' AS T4
SET T4.core04idaula=T6.core06idaula, T4.core04idtutor=T6.core06idtutor 
WHERE T6.core06peraca='.$idPeraca.' AND T6.core06id=T4.core04idgrupo AND T4.core04peraca='.$idPeraca.'
AND ((T6.core06idaula<>T4.core04idaula) OR (T6.core06idtutor<>T4.core04idtutor))';
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Actualizando aulas y tutores en contenedor '.$iContenedor.': '.$sSQL.'<br>';}
			$result=$objDB->ejecutasql($sSQL);
			$sSQL='UPDATE core04matricula_'.$iContenedor.' AS T4, core05actividades_'.$iContenedor.' AS T5
SET T5.core05idgrupo=T4.core04idgrupo, T5.core05idaula=T4.core04idaula, T5.core05idtutor=T4.core04idtutor
WHERE T4.core04peraca='.$idPeraca.'
AND T4.core04id=T5.core05idmatricula 
AND ((T4.core04idgrupo<>T5.core05idgrupo) OR (T4.core04idaula<>T5.core05idaula) OR (T4.core04idtutor<>T5.core05idtutor))';
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Actualizando actividades en contenedor '.$iContenedor.': '.$sSQL.'<br>';}
			$result=$objDB->ejecutasql($sSQL);
			}
		}
	return array($sError, $sDebug);
	}
function f2207_TotalMatricula($idPeraca, $idPrograma, $idCead, $sCurso, $objDB, $bDebug=false){
	$sDebug='';
	$sSQLadd1='';
	if ($idPrograma!=''){$sSQLadd1=$sSQLadd1.'TB.core07idprograma='.$idPrograma.' AND ';}
	if ($idCead!=''){$sSQLadd1=$sSQLadd1.'TB.core07idcead='.$idCead.' AND ';}
	if ($sCurso!=''){$sSQLadd1=$sSQLadd1.'TB.core07idcurso='.$sCurso.' AND ';}
	$sSQL='SELECT SUM(TB.core07numestudiantes) AS Ant, SUM(TB.core07numnuevos) AS Nuevo 
FROM core07matriculaest AS TB 
WHERE '.$sSQLadd1.' TB.core07idperaca='.$idPeraca.'';
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta Totales: '.$sSQL.'<br>';}
	$tabla=$objDB->ejecutasql($sSQL);
	$fila=$objDB->sf($tabla);
	$iTotalAntiguos=$fila['Ant'];
	$iTotalNuevos=$fila['Nuevo'];
	return array($iTotalAntiguos, $iTotalNuevos, $sDebug);
	}
function f2211_ResumenCreditos($core10id, $objDB){
	//Por los cambios en el dise;o se cambia el orden en que se devuelven los parametros.
	list($iNumCredBasicos, $iNumCredEspecificos, $iNumCredElectivoBComun, $iNumCredElectivoDComun, $iNumCredElectivosDEsp, $iNumCredElectivoComp, $iNumCredRequisitos)=f2211_ResumenCreditosV2($core10id, $objDB);
	return array($iNumCredBasicos, $iNumCredEspecificos, $iNumCredElectivosDEsp, $iNumCredRequisitos, $iNumCredElectivoComp, $iNumCredElectivoBComun, $iNumCredElectivoDComun);
	}
function f2211_ResumenCreditosV2($core10id, $objDB){
	$iNumCredBasicos=0;
	$iNumCredEspecificos=0;
	$iNumCredElectivoBComun=0;
	$iNumCredElectivoDComun=0;
	$iNumCredElectivosDEsp=0;
	$iNumCredElectivoComp=0;
	$iNumCredRequisitos=0;
	$sSQL='SELECT core11tiporegistro, SUM(core11numcreditos) AS Creditos, COUNT(core11id) AS Cursos FROM core11plandeestudio WHERE core11idversionprograma='.$core10id.' GROUP BY core11tiporegistro';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		switch($fila['core11tiporegistro']){
			case 0:$iNumCredBasicos=$fila['Creditos'];break;
			case 1:$iNumCredEspecificos=$fila['Creditos'];break;
			case 5:$iNumCredElectivoBComun=$fila['Creditos'];break;
			case 6:$iNumCredElectivoDComun=$fila['Creditos'];break;
			case 2:$iNumCredElectivosDEsp=$fila['Creditos'];break;
			case 4:$iNumCredElectivoComp=$fila['Creditos'];break;
			case 3:$iNumCredRequisitos=$fila['Creditos'];break;
			}
		}
	return array($iNumCredBasicos, $iNumCredEspecificos, $iNumCredElectivoBComun, $iNumCredElectivoDComun, $iNumCredElectivosDEsp, $iNumCredElectivoComp, $iNumCredRequisitos);
	}
function f2300_EsConsejero($idTercero, $objDB, $bDebug=false){
	$bEsConsejero=false;
	$sIdCentro='';
	$sDebug='';
	$sSQL='SELECT cara01idcead FROM cara13consejeros WHERE cara13idconsejero='.$idTercero.' AND cara13activo="S" GROUP BY cara01idcead';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$bEsConsejero=true;
		if ($sIdCentro!=''){$sIdCentro=$sIdCentro.',';}
		$sIdCentro=$sIdCentro.$fila['cara01idcead'];
		}
	return array($bEsConsejero, $sIdCentro, $sDebug);
	}
function f2300_ZonasTercero($idTercero, $objDB, $bDebug=false){
	$idPrimera='';
	$sIdZona='-99';
	$sDebug='';
	$sSQL='SELECT cara21idzona FROM cara21lidereszona WHERE cara21idlider='.$idTercero.' AND cara21activo="S"';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$sIdZona=$sIdZona.','.$fila['cara21idzona'];
		if ($idPrimera==''){$idPrimera=$fila['cara21idzona'];}
		}
	return array($sIdZona, $idPrimera, $sDebug);
	}
function f2402_CargarActividades($idTercero, $idPeraca, $idCurso, $objDB, $bForzar=false, $bDebug=false){
	$sError='';
	$sDebug='';
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Iniciando revision de actividades<br>';}
	$idContPeraca=f146_Contenedor($idPeraca, $objDB);
	//Saber si la tabla existe.
	if ($idContPeraca!=0){
		$sNomTabla='ceca02actividadtutor_'.$idContPeraca.'';
		$sSQL='SHOW TABLES LIKE "'.$sNomTabla.'"';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Revisando que exista el contenedor '.$sSQL.'<br>';}
		if ($objDB->nf($tabla)==0){
			$sSQL='CREATE TABLE '.$sNomTabla.' (ceca02idtutor int NOT NULL, ceca02idperaca int NOT NULL, ceca02idcurso int NOT NULL, ceca02idactividad int NOT NULL, ceca02id int NULL DEFAULT 0, ceca02idestado int NULL DEFAULT 0, ceca02fechainicio int NULL DEFAULT 0, ceca02fechacierrre int NULL DEFAULT 0, ceca02fecharetro int NULL DEFAULT 0, ceca02peso int NULL DEFAULT 0, ceca02numgrupos int NULL DEFAULT 0, ceca02numest int NULL DEFAULT 0, ceca02momentoest int NULL DEFAULT 0, ceca02idcierra int NULL DEFAULT 0, ceca02fechacierre int NULL DEFAULT 0, ceca02mincierra int NULL DEFAULT 0)';
			$tabla=$objDB->ejecutasql($sSQL);
			$sSQL='ALTER TABLE '.$sNomTabla.' ADD PRIMARY KEY(ceca02id)';
			$tabla=$objDB->ejecutasql($sSQL);
			$sSQL='ALTER TABLE '.$sNomTabla.' ADD UNIQUE INDEX ceca02actividadtutor_id(ceca02idtutor, ceca02idperaca, ceca02idcurso, ceca02idactividad)';
			$tabla=$objDB->ejecutasql($sSQL);
			}
		}else{
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' No se ha establecido un contenedor para el periodo '.$idPeraca.'<br>';}
		}
	//Ver si existen datos del tutor-curso
	$bEntra=false;
	if ($idContPeraca!=0){
		if ($bForzar){
			$bEntra=true;
			}else{
			if ($idCurso!=''){
				$sSQL='SELECT ceca02id FROM '.$sNomTabla.' WHERE ceca02idtutor='.$idTercero.' AND ceca02idperaca='.$idPeraca.' AND ceca02idcurso='.$idCurso.' LIMIT 0, 1';
				$tabla=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla)==0){
					$bEntra=true;
					}
				}
			}
		}
	if ($bEntra){
		//Alistar el id.
		$sTablaGrupos='core06grupos_'.$idContPeraca;
		$ceca02id=tabla_consecutivo($sNomTabla, 'ceca02id', '', $objDB);
		$sCampos05='INSERT INTO '.$sNomTabla.' (ceca02idtutor, ceca02idperaca, ceca02idcurso, ceca02idactividad, ceca02id, ceca02idestado, ceca02fechainicio, ceca02fechacierrre, ceca02fecharetro, ceca02peso, ceca02numgrupos, ceca02numest, ceca02momentoest) VALUES ';
		// Buscar la agenda de ese curso y duplicarsela al tutor.
		$ceca02numgrupos=0;
		$ceca02numest=0;
		$bPrimera=true;
		$sSQL5='';
		$sSQL='SELECT ofer18idactividad, ofer18fechainicio, ofer18fechacierrre, ofer18fecharetro, ofer18peso, ofer18unidad FROM ofer18cargaxnavxdia WHERE ofer18curso='.$idCurso.' AND ofer18per_aca='.$idPeraca.' AND ofer18numaula=1';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta Actividades agenda: '.$sSQL.'<br>';}
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			$ceca02momentoest=0;
			$sSQL='SELECT ofer03idmomento FROM ofer03cursounidad WHERE ofer03id='.$fila['ofer18unidad'].'';
			$tabla03=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla03)>0){
				$fila03=$objDB->sf($tabla03);
				$ceca02momentoest=$fila03['ofer03idmomento'];
				}
			if ($bPrimera){
				//Contar la cantidad de grupos .
				$sIds06='-99';
				$sSQL='SELECT core06id FROM '.$sTablaGrupos.' WHERE core06peraca='.$idPeraca.' AND core06idcurso='.$idCurso.' AND core06idtutor='.$idTercero.'';
				$tabla03=$objDB->ejecutasql($sSQL);
				$ceca02numgrupos=$objDB->nf($tabla03);
				// Ahora contamos los estudiantes.
				$sSQL='SHOW TABLES LIKE "core05actividades%"';
				$sSQLBase='';
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Total Periodo: Lista de contenedores: '.$sSQL.'<br>';}
				$tablac=$objDB->ejecutasql($sSQL);
				while($filac=$objDB->sf($tablac)){
					if ($sSQLBase!=''){$sSQLBase=$sSQLBase.' UNION ';}
					$sSQLBase=$sSQLBase.'SELECT TB.core05id 
FROM '.$filac[0].' AS TB, '.$sTablaGrupos.' AS T6 
WHERE TB.core05idtutor='.$idTercero.' AND TB.core05idcurso='.$idCurso.' AND TB.core05peraca='.$idPeraca.' AND TB.core05idactividad='.$fila['ofer18idactividad'].' AND TB.core05idgrupo=T6.core06id';
					}
				if ($sSQLBase!=''){
					if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta de total estudiantes '.$sSQLBase.'<br>';}
					$tabla03=$objDB->ejecutasql($sSQLBase);
					$ceca02numest=$objDB->nf($tabla03);
					}
				$bPrimera=false;
				}
			//Termina de hacer el conteo
			$bInserta=true;
			if ($bForzar){
				//Si forza es posible que ya exista la data..
				$sSQL='SELECT ceca02id, ceca02numgrupos, ceca02numest, ceca02momentoest FROM '.$sNomTabla.' WHERE ceca02idtutor='.$idTercero.' AND ceca02idperaca='.$idPeraca.' AND ceca02idcurso='.$idCurso.' AND ceca02idactividad='.$fila['ofer18idactividad'].'';
				$tabla03=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla03)>0){
					$fila03=$objDB->sf($tabla03);
					$bInserta=false;
					$sdatos='';
					$scampo[1]='ceca02numgrupos';
					$scampo[2]='ceca02numest';
					$scampo[3]='ceca02momentoest';
					$sdato[1]=$ceca02numgrupos;
					$sdato[2]=$ceca02numest;
					$sdato[3]=$ceca02momentoest;
					$numcmod=3;
					for ($k=1;$k<=$numcmod;$k++){
						if ($fila03[$scampo[$k]]!=$sdato[$k]){
							if ($sdatos!=''){$sdatos=$sdatos.', ';}
							$sdatos=$sdatos.$scampo[$k].'="'.$sdato[$k].'"';
							}
						}
					if ($sdatos!=''){
						$sSQL='UPDATE '.$sNomTabla.' SET '.$sdatos.' WHERE ceca02id='.$fila03['ceca02id'].'';
						if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Actualizando actividad '.$sSQL.'<br>';}
						$result=$objDB->ejecutasql($sSQL);
						}
					}
				}
			if ($bInserta){
				$ceca02fechainicio=fecha_EnNumero($fila['ofer18fechainicio']);
				$ceca02fechacierrre=fecha_EnNumero($fila['ofer18fechacierrre']);
				$ceca02fecharetro=fecha_EnNumero($fila['ofer18fecharetro']);
				if ($sSQL5!=''){$sSQL5=$sSQL5.',';}
				$sSQL5=$sSQL5.'('.$idTercero.', '.$idPeraca.', '.$idCurso.', '.$fila['ofer18idactividad'].', '.$ceca02id.', 0, '.$ceca02fechainicio.', '.$ceca02fechacierrre.', '.$ceca02fecharetro.', '.$fila['ofer18peso'].', '.$ceca02numgrupos.', '.$ceca02numest.', '.$ceca02momentoest.')';
				$ceca02id++;
				}
			}
		if ($sSQL5!=''){
			$sSQL=$sCampos05.$sSQL5;
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta Actividades del tutor: '.$sSQL.'<br>';}
			$tabla=$objDB->ejecutasql($sSQL);
			}
		}
	return array($sError, $sDebug);
	}
function f2406_TotalizarGrupo($idPeraca, $idGrupo, $idContenedor, $objDB, $bDebug=false){
	$sError='';
	$sDebug='';
	$iTotal=0;
	if ($idContenedor==0){
		$idContenedor=f146_Contenedor($idPeraca, $objDB);
		}
	if ($idContenedor!=0){
		$sSQL='SHOW TABLES LIKE "core04%"';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Total Periodo: Lista de contenedores: '.$sSQL.'<br>';}
		$tablac=$objDB->ejecutasql($sSQL);
		while($filac=$objDB->sf($tablac)){
			$iContenedor=substr($filac[0], 16);
			$sSQL='SELECT TB.core04id 
FROM core04matricula_'.$iContenedor.' AS TB 
WHERE TB.core04idgrupo='.$idGrupo.' AND TB.core04peraca='.$idPeraca.'';
			$tabla=$objDB->ejecutasql($sSQL);
			$iTotal=$iTotal+$objDB->nf($tabla);
			}
		//Actualizar el dato al grupo.
		$sSQL='UPDATE core06grupos_'.$idContenedor.' SET core06numinscritos='.$iTotal.' WHERE core06id='.$idGrupo.'';
		$tabla=$objDB->ejecutasql($sSQL);
		}else{
		$sError='No se ha definido un contenedor para el periodo '.$idPeraca.'';
		}
	return array($iTotal, $sError, $sDebug);
	}
function f2491_OrigenNotaActividad($idPeraca, $idCurso, $idActividad, $objDB, $bDebug=false){
	$iOrigenNota=1;
	$sDebug='';
	//Ubicar le origen de la nota
	$sSQL='SELECT TB.ofer18origennota FROM ofer18cargaxnavxdia AS TB WHERE TB.ofer18per_aca='.$idPeraca.' AND TB.ofer18curso='.$idCurso.' AND TB.ofer18numaula=1 AND TB.ofer18idactividad='.$idActividad.'';
	$tabla18=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla18)>0){
		$fila18=$objDB->sf($tabla18);
		$iOrigenNota=$fila18['ofer18origennota'];
		if ($iOrigenNota==0){
			$iOrigenNota=1;
			}
		}
	//Termina de ubicar la nota.
	return array($iOrigenNota, $sDebug);
	}

function TraerDBRyC(){
	list($objRyC, $sDebug)=TraerDBRyCV2();
	return $objRyC;
	}
function TraerDBRyCV2($bDebug=false){
	$objRyC=NULL;
	$sDebug='';
	$sDirBase=__DIR__.'/';
	require $sDirBase.'app.php';
	if (isset($APP->dbhostryc)!=0){
		$objRyC=new clsdbadmin($APP->dbhostryc, $APP->dbuserryc, $APP->dbpassryc, $APP->dbnameryc);
		if ($APP->dbpuertoryc!=''){$objRyC->dbPuerto=$APP->dbpuertoryc;}
		if ($bDebug){
			if (!$objRyC->Conectar()){
				$sDebug=$sDebug.''.fecha_microtiempo().' Error al intentar conectar con la base de datos <b>'.$objRyC->serror.'</b><br>';
				}
			}
		}else{
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' No existen parametros de conexion con RyC ['.$sDirBase.'app.php]<br>';}
		}
	return array($objRyC, $sDebug);
	}
function TraerDBGrados($bDebug=false){
	$objGrados=NULL;
	$sDebug='';
	$sDirBase=__DIR__.'/';
	require $sDirBase.'app.php';
	if (isset($APP->dbhostgrados)!=0){
		$objGrados=new clsdbadmin($APP->dbhostgrados, $APP->dbusergrados, $APP->dbpassgrados, $APP->dbnamegrados);
		if ($APP->dbpuertogrados!=''){$objGrados->dbPuerto=$APP->dbpuertogrados;}
		if ($bDebug){
			if (!$objGrados->Conectar()){
				$sDebug=$sDebug.''.fecha_microtiempo().' Error al intentar conectar con la base de datos <b>'.$objGrados->serror.'</b><br>';
				}
			}
		}else{
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' No existen parametros de conexion con Grados ['.$sDirBase.'app.php]<br>';}
		}
	return array($objGrados, $sDebug);
	}
?>
