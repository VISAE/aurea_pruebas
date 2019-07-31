<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2014 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
*/
if (isset($bDebug)==0){
	$bDebug=false;
	$sDebug='';
	}
if (isset($APP)==0){$APP=stdclass();}
if (isset($APP->idserver)==0){$APP->idserver=0;}
if (isset($_SESSION['unad_id_moodle'])==0){$_SESSION['unad_id_moodle']=0;}
if (isset($_SESSION['USER'])!=0){
	if (isset($_SESSION['USER']->id)!=0){
		if ($_SESSION['unad_id_moodle']!=(int)$_SESSION['USER']->id){
			$_SESSION['unad_id_tercero']=-1;
			}
		}
	}
if ((int)$_SESSION['unad_id_tercero']<1){
	if (isset($objDB)==0){$objDB=$objdb;}
	$bEntra=false;
	if (isset($_SESSION['USER'])!=0){
		if (isset($_SESSION['USER']->id)!=0){$bEntra=true;}
		}
	if ($bEntra){
		$idunad=-1;
		$idmoodle=(int)$_SESSION['USER']->id;
		$_SESSION['unad_id_moodle']=$idmoodle;
		if ($idmoodle!=0){
			$sCampoServer='unad11idmoodle';
			switch($APP->idserver){
				case 1: //ncontents
				$sCampoServer='unad11idncontents';
				break;
				case 2: //datateca
				$sCampoServer='unad11iddatateca';
				break;
				case 3: //campus
				$sCampoServer='unad11idcampus';
				break;
				}
			$sql='SELECT unad11id FROM unad11terceros WHERE '.$sCampoServer.'='.$idmoodle;
			$tabla=$objDB->ejecutasql($sql);
			if ($bDebug){$sDebug=$sDebug.''.fecha_microtiempo().' Buscando el tercero en la base de datos '.$sql.'<br>';}
			if ($objDB->nf($tabla)>0){
				$fila=$objDB->sf($tabla);
				$idunad=$fila['unad11id'];
				if ($bDebug){$sDebug=$sDebug.''.fecha_microtiempo().' Se ha cargado el tercero '.$idunad.'<br>';}
				}else{
				if ($bDebug){$sDebug=$sDebug.''.fecha_microtiempo().' No se ha encontrado el tercero en la base de datos '.$objDB->serror.'<br>				<br>Servidor: '.$APP->dbhost.' Usuario '.$APP->dbuser.' Clave '.$APP->dbpass.' Nombre '.$APP->dbname.' Puerto '.$APP->dbpuerto.'<br>';}
				}
			}
		//ver si esta en otro servidor y no encuentra el id
		if ($idunad==-1){
			$sDocMoodle='';
			if (isset($_SESSION['USER'])!=0){
				if (isset($_SESSION['USER']->idnumber)!=0){
					$sDocMoodle=$_SESSION['USER']->idnumber;
					}
				}
			if ($sDocMoodle!=''){
				$sql='SELECT unad11id FROM unad11terceros WHERE unad11doc="'.$sDocMoodle.'"';
				$tabla=$objDB->ejecutasql($sql);
				if ($objDB->nf($tabla)>0){
					//Se encuentra, actualizar el id del servidor.
					$fila=$objDB->sf($tabla);
					$idunad=$fila['unad11id'];
					$sql='UPDATE unad11terceros SET '.$sCampoServer.'='.$idmoodle.' WHERE unad11id='.$idunad;
					$result=$objDB->ejecutasql($sql);
					}
				}
			}
		$_SESSION['unad_id_tercero']=$idunad;
		}
	}
//echo $prev.$_SESSION['unad_id_tercero'].'--';
?>