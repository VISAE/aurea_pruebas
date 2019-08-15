<?php
/*
--- � Angel Mauro Avellaneda Barreto - UNAD - 2014 -2017
- Los datos de conexion deben pasarse como parametros al momento de iniciar la clase, esos parametros pueden venir del archivo de configuracion o de una base de datos
--- Octubre 2 de 2014 se empieza el uso de MySQLi en lugar de mysql_coneect
--- Junio 25 de 2016 - Se ajusta la funcion conectar.
--- Diciembre 3 de 2016 - Se agrega el CerrarConexion
*/
function clsdb_warning_handler($errno, $errstr, $errfile, $errline, $errcontext){
	throw new Exception($errstr);
	}
class clsdbadmin{
	var $objMYSQL=NULL;
	var $bconectada=false;
	var $bxajax=false;
	var $dbclave='';
	var $dblink=NULL;
	var $dbmodelo='M';
	var $dbnombre='';
	var $dbservidor='';
	var $dbusuario='';
	var $dbPuerto='';
	var $idsistema=0;//aqui poner el sistema en el que se esta trabajando
	var $serror='';
	var $bUTF8=false;

	function xajax(){
		$this->bxajax=true;
		$this->bconectada=false;
		}
	function conectar(){
	$res=false;
	$this->serror='';
	switch ($this->dbmodelo){
		case 'O':
		if(!($this->dblink=@odbc_connect($this->dbservidor,$this->dbusuario,$this->dbclave))){
			$this->serror='Error conectando al servidor de datos {'.odbc_error().'}';
			}else{
			if (!$this->bxajax){$this->bconectada=true;}
			$res=true;
			}
		break;
		default:
		$bHayServicio=false;
		try {
			$this->objMYSQL=new mysqli();
			$bHayServicio=true;
			}catch (Exception $e){
			$this->serror='Error al intentar conectar el servicio MySQL';
			}
		if ($bHayServicio){
			if ($this->dbPuerto==''){
				@$this->objMYSQL->connect($this->dbservidor, $this->dbusuario, $this->dbclave, $this->dbnombre);
				}else{
				@$this->objMYSQL->connect($this->dbservidor, $this->dbusuario, $this->dbclave, $this->dbnombre, $this->dbPuerto);
				}
			if ($this->objMYSQL->connect_error){
				$this->serror='Error conectando al servidor de datos <br><b>'.$this->objMYSQL->connect_error.'</b>';
				}else{
				$res=true;
				//if ($this->bUTF8){$this->ejecutasql("SET NAMES 'utf8'");}
				}
			}
		if (!$this->bxajax){$this->bconectada=true;}
		}
	return $res;
	}
	function CerrarConexion(){
		switch ($this->dbmodelo){
			case 'O':
			break;
			default:
			if ($this->objMYSQL!=NULL){
				@mysqli_close($this->objMYSQL);
				}
			break;
			}
		}
	function ejecutasql($sentencia){
		$res=false;
		$this->serror='';
		if (trim($sentencia)==''){$this->serror='No se ha definido una consulta a ejecutar';}
		if (!$this->bconectada){$this->conectar();}
		if ($this->serror==''){
			switch ($this->dbmodelo){
				case 'O':
				$res=@odbc_exec($this->dblink, $sentencia);
				if ($res==false){$this->serror=odbc_error($this->dblink).' '.$sentencia;}
				break;
				default:
				//echo $sentencia;
				$res=@$this->objMYSQL->query($sentencia);
				if ($res==false){
					$this->serror='Error al ejecutar sentencia: '.$sentencia.'<br>'.@$this->objMYSQL->error;
					}
				}
			}
		return $res;
		}
	function liberar($tabla){
		switch ($this->dbmodelo){
			case 'O':
			break;
			default:
			@mysqli_free_result($tabla);
			break;
			}
		}
	function nf($tabla){
		$res=0;
		if ($tabla!=false){
			switch ($this->dbmodelo){
				case 'O':
				$res=odbc_num_rows($tabla);
				break;
				default:
				if (is_object($tabla)){
					$res=$tabla->num_rows;
					}
				}
			}
		return $res;
		}
	function sf($tabla){
		$res=false;
		if ($tabla!=false){
			switch ($this->dbmodelo){
				case 'O':
				$res=odbc_fetch_array($tabla);
				break;
				default:
				if (is_object($tabla)){
					$res=$tabla->fetch_array();
					}
				}
			}
		return $res;
		}
//funciones de trabajo sobre la estructura de la base de datos
	function bexistetabla($tablanombre){
	$res=false;
	switch ($this->dbmodelo){
		case 'O':
		break;
		default:
		$sql='SHOW TABLES LIKE "'.$tablanombre.'"';
		$tabla=$this->ejecutasql($sql);
		if ($this->nf($tabla)>0){$res=true;}
		}
	return $res;
	}
//funciones que trabajan sobre campos
	function scomparafecha($scampo,$scomparacion,$sfecha){
		$res='';
		switch ($this->dbmodelo){
			case 'M':
			$res='(STR_TO_DATE('.$scampo.',"%d/%m/%Y")'.$scomparacion.'STR_TO_DATE("'.$sfecha.'","%d/%m/%Y"))';
			}
		return $res;
		}
	function stextoafecha($sfecha){
		$res='';
		switch ($this->dbmodelo){
			case 'M':
			$res='STR_TO_DATE("'.$sfecha.'","%d/%m/%Y")';
			}
		return $res;
		}
	function scampoafecha($scampo){
		$res='';
		switch ($this->dbmodelo){
			case 'M':
			$res='STR_TO_DATE('.$scampo.',"%d/%m/%Y")';
			}
		return $res;
		}

	function __construct($servidor, $usuario, $clave, $db, $modelo='M'){
		$this->dbclave=$clave;
		$this->dbnombre=$db;
		$this->dbmodelo=$modelo;
		$this->dbservidor=$servidor;
		$this->dbusuario=$usuario;
		if ($servidor==''){
			$this->serror='No se ha definido el servidor de base de datos';
			}
		}
	function __destruct(){
		if ($this->objMYSQL!=NULL){
			//$this->objMYSQL->close();
			$this->CerrarConexion();
			unset($this->objMYSQL);
			}
		}
	}
?>