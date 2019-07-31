<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2014 ---
--- Modelo Version 1.0.0 jueves, 10 de abril de 2014
*/
class clsPlanos{
	var $sNombre='';
	var $sSeparador=',';
	var $iLineas=0;
	var $aCuerpo=array();
	function AdicionarLinea($slinea){
		$this->iLineas++;
		$this->aCuerpo[$this->iLineas]=$slinea;
		}
	function Generar(){
		$sError='';
		$bEntra=true;
		if (!is_writable($this->sNombre)){
			$res=fopen($this->sNombre, 'w');
			fclose($res);
			chmod($this->sNombre, 0777);
			if (!is_writable($this->sNombre)){
				$bEntra=false;
				$sError='No es posible abrir el archivo '.$this->sNombre;
				}
			}
		if ($bEntra){
			$ln=chr(13).chr(10);
			$res=fopen($this->sNombre, 'w');
			for ($j=1;$j<=$this->iLineas;$j++){
				fwrite($res,$this->aCuerpo[$j].$ln);
				}
			fclose($res);
			}
		return $sError;
		}
	function Leer(){
		$filas=file($this->sNombre);
		$this->iLineas=0;
		$i=0;
		while(isset($filas[$i])!=0){
			if (trim($filas[$i])!=''){
				$this->iLineas++;
				$this->aCuerpo[$this->iLineas]=$filas[$i];
				}
			$i++;
			}
		}
	function __construct($sNombreArchivo){
		$this->sNombre=$sNombreArchivo;
		}
	function __destruct(){
		}
	}
?>
