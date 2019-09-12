<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2016 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 2.15.7 jueves, 01 de septiembre de 2016
--- Se basa en la unad80foro Foro
*/
class clsForo{
var $unad80idproceso=0;
var $unad80idref=0;
var $bPuedeEditar=false;
var $sSeparador='<div class="salto1px"></div>';
var $sContenedorTextoP1='<label class="txtAreaS">';
var $sContenedorTextoP2='</label>';
var $sContenedorBotonP1='<label class="Label30">';
var $sContenedorBotonP2='</label>';
var $sCajaEspacio='<label class="Label130"></label>';
function campoedita($idPadre, $idTercero){
	$sId='_'.$this->unad80idproceso.'_'.$idPadre.'';
	$sRes=$this->sContenedorTextoP1.'<textarea id="nota'.$sId.'" name="nota'.$sId.'" placeholder="Iniciar un comentario"></textarea>
'.$this->sContenedorTextoP2.$this->sSeparador;
	$sRes=$sRes.$this->sCajaEspacio.$this->sContenedorBotonP1.'<input id="bguarda'.$sId.'" name="bguarda'.$sId.'" type="button" value="Guardar" class="btMiniGuardar" onclick="foro_comentar('.$this->unad80idproceso.', '.$this->unad80idref.', '.$idPadre.', '.$idTercero.')" title="Guardar Comentario"/>'.$this->sContenedorBotonP2;
	return $sRes;
	}
function html($idTercero, $objdb){
	$sRes='<div class="ir_derecha" style="width:32px;">
<label class="Label30">
<input id="btactuliza'.$this->unad80idproceso.'" name="btactualiza'.$this->unad80idproceso.'" type="button" value="Actualizar" class="btMiniActualizar" onclick="foro_actualizar('.$this->unad80idproceso.', '.$this->unad80idref.', '.$idTercero.')" title="Actualizar" />
</label>
</div>';
	if ($this->unad80idproceso==1708){
		//Es la oferta y por tanto hay que mostrar la info del curso.
		//Sacamos la info del curso.
		$idCurso=0;
		$sql='SELECT ofer08idcurso FROM ofer08oferta WHERE ofer08id='.$this->unad80idref.'';
		$tabla=$objdb->ejecutasql($sql);
		if ($objdb->nf($tabla)>0){
			$fila=$objdb->sf($tabla);
			$idCurso=$fila['ofer08idcurso'];
			}
		if ($idCurso!=0){
			$sql='SELECT ofer43cuerpo FROM ofer43cursoforo WHERE ofer43idcurso='.$idCurso.' AND ofer43publicado="S" ORDER BY ofer43orden, ofer43consec';
			$tabla=$objdb->ejecutasql($sql);
			while($fila=$objdb->sf($tabla)){
				$sRes=$sRes.''.cadena_notildes($fila['ofer43cuerpo']).$this->sSeparador;
				}
			//Los anexos...
			$sql='SELECT TB.ofer44consec, TB.ofer44titulo, TB.ofer44idorigen, TB.ofer44idarchivo 
FROM ofer44cursoanexo AS TB 
WHERE TB.ofer44idcurso='.$idCurso.' AND TB.ofer44publicado="S"
ORDER BY TB.ofer44consec';
			$tabla=$objdb->ejecutasql($sql);
			if ($objdb->nf($tabla)>0){
				$sRes=$sRes.'<b>ANEXOS</b>'.$this->sSeparador;
				}
			while($fila=$objdb->sf($tabla)){
				$et_ofer44idarchivo=html_lnkarchivo((int)$fila['ofer44idorigen'], (int)$fila['ofer44idarchivo']);
				$sRes=$sRes.''.cadena_notildes($fila['ofer44titulo']).' '.$et_ofer44idarchivo.$this->sSeparador;
				}
			}
		}
	$sql='SELECT TB.unad80consec, TB.unad80id, TB.unad80mensaje, TB.unad80ifecha, TB.unad80hora, TB.unad80minuto, TB.unad80usuario, T11.unad11razonsocial 
FROM unad80foro AS TB, unad11terceros AS T11
WHERE TB.unad80idref='.$this->unad80idref.' AND TB.unad80idproceso='.$this->unad80idproceso.' AND TB.unad80usuario=T11.unad11id
ORDER BY TB.unad80consec';
	$tabla=$objdb->ejecutasql($sql);
	while ($fila=$objdb->sf($tabla)){
		if ($fila['unad80usuario']==$idTercero){
			$sTercero='YO';
			}else{
			$sTercero=cadena_notildes($fila['unad11razonsocial']);
			}
		$sFecha=fecha_desdenumero($fila['unad80ifecha']).' '.formato_horaminuto($fila['unad80hora'], $fila['unad80minuto']);
		$sRes=$sRes.$sFecha.' <b>'.$sTercero.'</b> '.cadena_notildes($fila['unad80mensaje']).$this->sSeparador;
		}
	if ($this->bPuedeEditar){
		$sRes=$sRes.$this->campoedita(0, $idTercero);
		}
	$sRes=$sRes.'';
	return array($sRes);
	}
function nuevo(){
	}
function __construct($unad80idproceso, $unad80idref, $bPuedeEditar=false){
	$this->unad80idproceso=$unad80idproceso;
	$this->unad80idref=$unad80idref;
	$this->bPuedeEditar=$bPuedeEditar;
	$this->nuevo();
	}
}
?>