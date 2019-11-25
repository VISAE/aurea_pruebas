<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 2.22.3 viernes, 24 de agosto de 2018
--- unae03noconforme No conformidades
*/
class clsT203{
var $iCodModulo=203;
var $bAuditaInsertar=true;
var $bAuditaModificar=true;
var $bAuditaEliminar=true;
var $unae03idproceso=0;
var $unae03consec='';
var $unae03id='';
var $unae03peraca=0;
var $unae03curso=0;
var $unae03escuela=0;
var $unae03idresponsable=0;
var $unae03estado=0;
var $unae03fechagenera=0;
var $unae03fecharesponde=0;
var $unae03respuesta='';
var $unae03idjustificacion=0;
var $unae03idautoriza=0;
var $unae03fechaautoriza=0;
var $items207=0;
var $a207=array();
var $sError='';
var $iTipoError=0;
var $sDebug='';
function dato_unae03idautoriza($unae03idautoriza, $unae03idautoriza_td, $unae03idautoriza_doc, $objDB, $sPrevio='El tercero Autoriza '){
	$sError='';
	if ($sError==''){$sError=tabla_terceros_existe($unae03idautoriza_td, $unae03idautoriza_doc, $objDB, $sPrevio);}
	if ($sError==''){
		list($sError, $sInfo)=tercero_Bloqueado($unae03idautoriza, $objDB);
		if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
		}
	if ($sError==''){
		$this->unae03idautoriza=$unae03idautoriza;
		}
	}
function dato_unae03idresponsable($unae03idresponsable, $unae03idresponsable_td, $unae03idresponsable_doc, $objDB, $sPrevio='El tercero Responsable '){
	$sError='';
	if ($sError==''){$sError=tabla_terceros_existe($unae03idresponsable_td, $unae03idresponsable_doc, $objDB, $sPrevio);}
	if ($sError==''){
		list($sError, $sInfo)=tercero_Bloqueado($unae03idresponsable, $objDB);
		if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
		}
	if ($sError==''){
		$this->unae03idresponsable=$unae03idresponsable;
		}
	}
function guardar($objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_203=$APP->rutacomun.'lg/lg_203_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_203)){$mensajes_203=$APP->rutacomun.'lg/lg_203_es.php';}
	require $mensajes_todas;
	require $mensajes_203;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	
	/* -- Seccion para validar los posibles causales de error. */
	//if ($this->unae03respuesta==''){$sError=$ERR['unae03respuesta'];}
	if ($this->unae03fecharesponde==0){
		//$this->unae03fecharesponde=0;
		//$sError=$ERR['unae03fecharesponde'];
		}
	if ($this->unae03fechagenera==0){
		$this->unae03fechagenera=fecha_DiaMod();
		//$sError=$ERR['unae03fechagenera'];
		}
	if ($this->unae03estado==''){$sError=$ERR['unae03estado'];}
	if ($this->unae03idresponsable==''){$sError=$ERR['unae03idresponsable'];}
	if ($this->unae03escuela==''){$sError=$ERR['unae03escuela'];}
	if ($this->unae03curso==''){$sError=$ERR['unae03curso'];}
	if ($this->unae03peraca==''){$sError=$ERR['unae03peraca'];}
	if ($this->unae03idproceso==''){$sError=$ERR['unae03idproceso'];}
	$idAccion=3;
	if ($sError==''){
		if ($this->unae03id==''){
			$idAccion=2;
			if ($this->unae03consec==''){
				$this->unae03consec=tabla_consecutivo('unae03noconforme', 'unae03consec', 'unae03idproceso='.$this->unae03idproceso.'', $objDB);
				if ($this->unae03consec==-1){$sError=$objDB->serror;}
				}else{
				if (!seg_revisa_permiso($this->iCodModulo, 8, $objDB)){
					$sError=$ERR['8'];
					$this->unae03consec='';
					}
				}
			if ($sError==''){
				$sSQL='SELECT unae03idproceso FROM unae03noconforme WHERE unae03idproceso='.$this->unae03idproceso.' AND unae03consec='.$this->unae03consec.'';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($this->iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
					}
				}
			}else{
			if (!seg_revisa_permiso($this->iCodModulo, 3, $objDB)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		if ($idAccion==2){
			/* Preparar el Id, Si no lo hay se quita la comprobación. */
			$this->unae03id=tabla_consecutivo('unae03noconforme','unae03id', '', $objDB);
			if ($this->unae03id==-1){$sError=$objDB->serror;}
			}
		}
	if ($sError==''){
		if (get_magic_quotes_gpc()==1){$this->unae03respuesta=stripslashes($this->unae03respuesta);}
		/* Si el campo unae03respuesta permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea: */
		//$unae03respuesta=addslashes($this->unae03respuesta);
		$unae03respuesta=str_replace('"', '\"', $this->unae03respuesta);
		$bpasa=false;
		if ($idAccion==2){
			//$this->unae03fechagenera=0; /* fecha_DiaMod(); */
			//$this->unae03fecharesponde=0; /* fecha_DiaMod(); */
			$sCampos203='unae03idproceso, unae03consec, unae03id, unae03peraca, unae03curso, unae03escuela, unae03idresponsable, unae03estado, unae03fechagenera, unae03fecharesponde, 
unae03respuesta, unae03idjustificacion, unae03idautoriza, unae03fechaautoriza';
			$sValores203=''.$this->unae03idproceso.', '.$this->unae03consec.', '.$this->unae03id.', '.$this->unae03peraca.', '.$this->unae03curso.', '.$this->unae03escuela.', '.$this->unae03idresponsable.', '.$this->unae03estado.', "'.$this->unae03fechagenera.'", "'.$this->unae03fecharesponde.'", 
"'.$unae03respuesta.'", '.$this->unae03idjustificacion.', '.$this->unae03idautoriza.', "'.$this->unae03fechaautoriza.'"';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO unae03noconforme ('.$sCampos203.') VALUES ('.utf8_encode($sValores203).');';
				$sdetalle=$sCampos203.'['.utf8_encode($sValores203).']';
				}else{
				$sSQL='INSERT INTO unae03noconforme ('.$sCampos203.') VALUES ('.$sValores203.');';
				$sdetalle=$sCampos203.'['.$sValores203.']';
				}
			$bpasa=true;
			}else{
			$scampo[1]='unae03peraca';
			$scampo[2]='unae03curso';
			$scampo[3]='unae03escuela';
			$scampo[4]='unae03idresponsable';
			$scampo[5]='unae03estado';
			$scampo[6]='unae03fechagenera';
			$scampo[7]='unae03fecharesponde';
			$scampo[8]='unae03respuesta';
			$scampo[9]='unae03idjustificacion';
			$scampo[10]='unae03fechaautoriza';
			$sdato[1]=$this->unae03peraca;
			$sdato[2]=$this->unae03curso;
			$sdato[3]=$this->unae03escuela;
			$sdato[4]=$this->unae03idresponsable;
			$sdato[5]=$this->unae03estado;
			$sdato[6]=$this->unae03fechagenera;
			$sdato[7]=$this->unae03fecharesponde;
			$sdato[8]=$unae03respuesta;
			$sdato[9]=$this->unae03idjustificacion;
			$sdato[10]=$this->unae03fechaautoriza;
			$numcmod=10;
			$sWhere='unae03id='.$this->unae03id.'';
			$sSQL='SELECT * FROM unae03noconforme WHERE '.$sWhere;
			$sdatos='';
			$bPrimera=true;
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){$sDebug=$sDebug.fecha_microtiempo().' FALLA LECTURA DE DATOS [203]: '.$sSQL.'<br>';}
			if ($objDB->nf($result)>0){
				$filabase=$objDB->sf($result);
				if ($bDebug&&$bPrimera){
					for ($k=1;$k<=$numcmod;$k++){
						if (isset($filabase[$scampo[$k]])==0){
							$sDebug=$sDebug.fecha_microtiempo().' FALLA CODIGO: Falta el campo '.$k.' '.$scampo[$k].'<br>';
							}
						}
					$bPrimera=false;
					}
				$bsepara=false;
				for ($k=1;$k<=$numcmod;$k++){
					if ($filabase[$scampo[$k]]!=$sdato[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo[$k].'="'.$sdato[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sdetalle=utf8_encode($sdatos).'['.$sWhere.']';
					$sSQL='UPDATE unae03noconforme SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE unae03noconforme SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				}
			}
		if ($bpasa){
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [203] ..<!-- '.$sSQL.' -->';
				if ($idAccion==2){$this->unae03id='';}
				}else{
				$iTipoError=1;
				if ($idAccion==2){
					$bAudita=$this->bAuditaInsertar;
					$sError=$ETI['msg_itemguardado'];
					}else{
					$bAudita=$this->bAuditaModificar;
					$sError=$ETI['msg_itemmodificado'];
					}
				if ($bAudita){seg_auditar($this->iCodModulo, $_SESSION['unad_id_tercero'], $idAccion, $this->unae03id, $sdetalle, $objDB);}
				}
			}
		}
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Esto es un debug<br>';}
	return array($sError, $iTipoError, $idAccion, $sDebug);
	}
function guardar2($objDB, $bDebug=false){
	//Esta es para ejecutarlo con el cron, por lo tanto no puede tener variables de sesion.
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$idAccion=3;
	if (true){
	/* -- Seccion para validar los posibles causales de error. */
	//if ($this->unae03respuesta==''){$sError=$ERR['unae03respuesta'];}
	if ($this->unae03fecharesponde==0){
		//$this->unae03fecharesponde=0;
		//$sError=$ERR['unae03fecharesponde'];
		}
	if ($this->unae03fechagenera==0){
		$this->unae03fechagenera=fecha_DiaMod();
		//$sError=$ERR['unae03fechagenera'];
		}
	if ($this->unae03estado==''){$this->unae03estado=0;}
	if ($this->unae03idresponsable==''){$this->unae03idresponsable=0;}
	if ($this->unae03escuela==''){$this->unae03escuela=0;}
	if ($this->unae03curso==''){$this->unae03curso=0;}
	if ($this->unae03peraca==''){$this->unae03peraca=0;}
	if ($this->unae03idproceso==''){$sError='No se ha definido el proceso';}
	
	if ($sError==''){
		if ($this->unae03id==''){
			$idAccion=2;
			if ($this->unae03consec==''){
				$this->unae03consec=tabla_consecutivo('unae03noconforme', 'unae03consec', 'unae03idproceso='.$this->unae03idproceso.'', $objDB);
				if ($this->unae03consec==-1){$sError=$objDB->serror;}
				}
			if ($sError==''){
				$sSQL='SELECT unae03idproceso FROM unae03noconforme WHERE unae03idproceso='.$this->unae03idproceso.' AND unae03consec='.$this->unae03consec.'';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)!=0){
					$sError='El consecutivo ya existe.';
					}
				}
			}
		}
	if ($sError==''){
		if ($idAccion==2){
			/* Preparar el Id, Si no lo hay se quita la comprobación. */
			$this->unae03id=tabla_consecutivo('unae03noconforme','unae03id', '', $objDB);
			if ($this->unae03id==-1){$sError=$objDB->serror;}
			}
		}
	if ($sError==''){
		//$sError='paramos';
		}
	if ($sError==''){
		if (get_magic_quotes_gpc()==1){$this->unae03respuesta=stripslashes($this->unae03respuesta);}
		/* Si el campo unae03respuesta permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea: */
		//$unae03respuesta=addslashes($this->unae03respuesta);
		$unae03respuesta=str_replace('"', '\"', $this->unae03respuesta);
		$bpasa=false;
		if ($idAccion==2){
			$sCampos203='unae03idproceso, unae03consec, unae03id, unae03peraca, unae03curso, unae03escuela, unae03idresponsable, unae03estado, unae03fechagenera, unae03fecharesponde, 
unae03respuesta, unae03idjustificacion, unae03idautoriza, unae03fechaautoriza';
			$sValores203=''.$this->unae03idproceso.', '.$this->unae03consec.', '.$this->unae03id.', '.$this->unae03peraca.', '.$this->unae03curso.', '.$this->unae03escuela.', '.$this->unae03idresponsable.', '.$this->unae03estado.', "'.$this->unae03fechagenera.'", "'.$this->unae03fecharesponde.'", 
"'.$unae03respuesta.'", '.$this->unae03idjustificacion.', '.$this->unae03idautoriza.', "'.$this->unae03fechaautoriza.'"';
			$sSQL='INSERT INTO unae03noconforme ('.$sCampos203.') VALUES ('.$sValores203.');';
			$sdetalle=$sCampos203.'['.$sValores203.']';
			$bpasa=true;
			}else{
			$scampo[1]='unae03peraca';
			$scampo[2]='unae03curso';
			$scampo[3]='unae03escuela';
			$scampo[4]='unae03idresponsable';
			$scampo[5]='unae03estado';
			$scampo[6]='unae03fechagenera';
			$scampo[7]='unae03fecharesponde';
			$scampo[8]='unae03respuesta';
			$sdato[1]=$this->unae03peraca;
			$sdato[2]=$this->unae03curso;
			$sdato[3]=$this->unae03escuela;
			$sdato[4]=$this->unae03idresponsable;
			$sdato[5]=$this->unae03estado;
			$sdato[6]=$this->unae03fechagenera;
			$sdato[7]=$this->unae03fecharesponde;
			$sdato[8]=$unae03respuesta;
			$numcmod=8;
			$sWhere='unae03id='.$this->unae03id.'';
			$sSQL='SELECT * FROM unae03noconforme WHERE '.$sWhere;
			$sdatos='';
			$bPrimera=true;
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){$sDebug=$sDebug.fecha_microtiempo().' FALLA LECTURA DE DATOS [203]: '.$sSQL.'<br>';}
			if ($objDB->nf($result)>0){
				$filabase=$objDB->sf($result);
				if ($bDebug&&$bPrimera){
					for ($k=1;$k<=$numcmod;$k++){
						if (isset($filabase[$scampo[$k]])==0){
							$sDebug=$sDebug.fecha_microtiempo().' FALLA CODIGO: Falta el campo '.$k.' '.$scampo[$k].'<br>';
							}
						}
					$bPrimera=false;
					}
				$bsepara=false;
				for ($k=1;$k<=$numcmod;$k++){
					if ($filabase[$scampo[$k]]!=$sdato[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo[$k].'="'.$sdato[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				$sSQL='UPDATE unae03noconforme SET '.$sdatos.' WHERE '.$sWhere.';';
				}
			}
		if ($bpasa){
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError='Falla al guardar [203] ..<!-- '.$sSQL.' -->';
				if ($idAccion==2){$this->unae03id='';}
				}else{
				$iTipoError=1;
				}
			}
		}
		//Termina si no hace nada.
		}
	$this->sError=$sError;
	$this->iTipoError=$iTipoError;
	$this->sDebug=$sDebug;
	//return array($sError, $iTipoError, $idAccion, $sDebug);
	}
function nuevo($idProceso){
	$this->unae03idproceso=$idProceso;
	$this->unae03consec='';
	$this->unae03id='';
	$this->unae03peraca=0;
	$this->unae03curso=0;
	$this->unae03escuela=0;
	$this->unae03idresponsable=0;
	$this->unae03estado=0;
	$this->unae03fechagenera=fecha_DiaMod();
	$this->unae03fecharesponde=0;
	$this->unae03respuesta='';
	$this->unae03idjustificacion=0;
	$this->unae03idautoriza=0;
	$this->unae03fechaautoriza=0;
	$this->sError='';
	$this->iTipoError=0;
	$this->sDebug='';
	}
function traerxid($unae03id, $objDB){
	$sSQLcondi='unae03id='.$unae03id.'';
	$sSQL='SELECT * FROM unae03noconforme WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$this->unae03idproceso=$fila['unae03idproceso'];
		$this->unae03consec=$fila['unae03consec'];
		$this->unae03id=$fila['unae03id'];
		$this->unae03peraca=$fila['unae03peraca'];
		$this->unae03curso=$fila['unae03curso'];
		$this->unae03escuela=$fila['unae03escuela'];
		$this->unae03idresponsable=$fila['unae03idresponsable'];
		$this->unae03estado=$fila['unae03estado'];
		$this->unae03fechagenera=$fila['unae03fechagenera'];
		$this->unae03fecharesponde=$fila['unae03fecharesponde'];
		$this->unae03respuesta=$fila['unae03respuesta'];
		$this->unae03idjustificacion=$fila['unae03idjustificacion'];
		$this->unae03idautoriza=$fila['unae03idautoriza'];
		$this->unae03fechaautoriza=$fila['unae03fechaautoriza'];
		}
	}
function __construct($idProceso){
	$this->nuevo($idProceso);
	}
/* Fin de la clase */
}
?>