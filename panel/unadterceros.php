<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2014 - 2018 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- © Saul Alexander Hernandez Albarracin - UNAD - 2019 ---
--- saul.hernandez@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 0.4.0 jueves, 06 de febrero de 2014
--- Modelo Version 0.8.0 miércoles, 19 de marzo de 2014
--- Modelo Versión 1.2.2 martes, 22 de julio de 2014
--- Modelo Versión 2.9.1 jueves, 30 de julio de 2015
--- Modelo Versión 2.19.7c viernes, 09 de febrero de 2018
--- Modelo Versión 2.21.0 jueves, 14 de junio de 2018
--- Modelo Versión 2.22.6 jueves, 15 de noviembre de 2018
*/
if (file_exists('./err_control.php')){require './err_control.php';}
$bDebug=false;
$sDebug='';
if (isset($_REQUEST['debug'])!=0){
	if ($_REQUEST['debug']==1){$bDebug=true;}
	}else{
	$_REQUEST['debug']=0;
	}
if ($bDebug){
	$iSegIni=microtime(true);
	$iSegundos=floor($iSegIni);
	$sMili=floor(($iSegIni-$iSegundos)*1000);
	if ($sMili<100){if ($sMili<10){$sMili=':00'.$sMili;}else{$sMili=':0'.$sMili;}}else{$sMili=':'.$sMili;}
	$sDebug=$sDebug.''.date('H:i:s').$sMili.' Inicia pagina <br>';
	}
if (!file_exists('./app.php')){
	echo '<b>Error N 1 de instalaci&oacute;n</b><br>No se ha establecido un archivo de configuraci&oacute;n, por favor comuniquese con el administrador del sistema.';
	die();
	}
$bCerrado=false;
mb_internal_encoding('UTF-8');
require './app.php';
require $APP->rutacomun.'unad_sesion.php';
if (isset($APP->https)==0){$APP->https=0;}
if ($APP->https==2){
	$bObliga=false;
	if (isset($_SERVER['HTTPS'])==0){
		$bObliga=true;
		}else{
		if ($_SERVER['HTTPS']!='on'){$bObliga=true;}
		}
	if ($bObliga){
		$pageURL='https://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
		header('Location:'.$pageURL);
		die();
		}
	}
//if (!file_exists('./opts.php')){require './opts.php';if ($OPT->opcion==1){$bOpcion=true;}}
$bPeticionXAJAX=false;
if ($_SERVER['REQUEST_METHOD']=='POST'){if (isset($_POST['xjxfun'])){$bPeticionXAJAX=true;}}
if (!$bPeticionXAJAX){$_SESSION['u_ultimominuto']=(date('W')*1440)+(date('H')*60)+date('i');}
require $APP->rutacomun.'unad_todas.php';
require $APP->rutacomun.'libs/clsdbadmin.php';
require $APP->rutacomun.'unad_librerias.php';
require $APP->rutacomun.'libhtml.php';
require $APP->rutacomun.'xajax/xajax_core/xajax.inc.php';
require $APP->rutacomun.'unad_xajax.php';
require $APP->rutacomun.'libaurea.php';
require $APP->rutacomun.'unad_login.php';
require $APP->rutacomun.'libdatos.php';
require $APP->rutacomun.'lib2202comun.php';
if (($bPeticionXAJAX)&&($_SESSION['unad_id_tercero']==0)){
	// viene por xajax.
	$xajax=new xajax();
	$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
	$xajax->register(XAJAX_FUNCTION,'sesion_abandona_V2');
	$xajax->processRequest();
	die();
	}
$bEnSesion=false;
if ((int)$_SESSION['unad_id_tercero']!=0){$bEnSesion=true;}
if (!$bEnSesion){
	header('Location:index.php');
	die();
	}
$grupo_id=0;
$iCodModulo=111;
$audita[1]=false;
$audita[2]=true;
$audita[3]=true;
$audita[4]=true;
$audita[5]=false;
$idTercero=$_SESSION['unad_id_tercero'];
// -- Se cargan los archivos de idioma
$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
$mensajes_111=$APP->rutacomun.'lg/lg_111_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_111)){$mensajes_111=$APP->rutacomun.'lg/lg_111_es.php';}
require $mensajes_todas;
require $mensajes_111;
$xajax=NULL;
$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
if (isset($APP->piel)==0){$APP->piel=1;}
$iPiel=$APP->piel;
$iPiel=1; //Piel 2018.
if ($bDebug){
	$sDebug=$sDebug.''.fecha_microtiempo().' Probando conexi&oacute;n con la base de datos <b>'.$APP->dbname.'</b> en <b>'.$APP->dbhost.'</b><br>';
	}
if (!$objDB->Conectar()){
	$bCerrado=true;
	if ($bDebug){
		$sDebug=$sDebug.''.fecha_microtiempo().' Error al intentar conectar con la base de datos <b>'.$objDB->serror.'</b><br>';
		}
	}
$idTercero=$_SESSION['unad_id_tercero'];
if (!seg_revisa_permiso($iCodModulo, 1, $objDB)){
	header('Location:nopermiso.php');
	die();
	}
if (!$bPeticionXAJAX){
	if (noticias_pendientes($objDB)){
		$objDB->CerrarConexion();
		header('Location:noticia.php?ret=unadterceros.php');
		die();
		}
	}
//PROCESOS DE LA PAGINA
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso'])==0){
	$_REQUEST['paso']=-1;
	if ($audita[1]){seg_auditaingreso($iCodModulo, $_SESSION['unad_id_tercero'], $objDB);}
	}
// -- 111 unad11terceros
require $APP->rutacomun.'lib111.php';
$xajax=new xajax();
$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
$xajax->register(XAJAX_FUNCTION,'formatear_moneda');
$xajax->register(XAJAX_FUNCTION,'Cargar_unad11deptoorigen');
$xajax->register(XAJAX_FUNCTION,'Cargar_unad11ciudadorigen');
$xajax->register(XAJAX_FUNCTION,'Cargar_unad11deptodoc');
$xajax->register(XAJAX_FUNCTION,'Cargar_unad11ciudaddoc');
$xajax->register(XAJAX_FUNCTION,'f111_Combounad11idcead');
$xajax->register(XAJAX_FUNCTION,'f111_Combounad11idprograma');
$xajax->register(XAJAX_FUNCTION,'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION,'sesion_mantener');
$xajax->register(XAJAX_FUNCTION,'sesion_retomar');
$xajax->register(XAJAX_FUNCTION,'f111_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f111_ExisteDato');
$xajax->register(XAJAX_FUNCTION,'upd_dv');
$xajax->register(XAJAX_FUNCTION,'f2202_Combobprograma');
$xajax->register(XAJAX_FUNCTION,'f2202_Combobcead');
$xajax->processRequest();
if ($bPeticionXAJAX){
	die(); // Esto hace que las llamadas por xajax terminen aquí.
	}
$bcargo=false;
$sError='';
$sErrorCerrando='';
$iTipoError=0;
$bLimpiaHijos=false;
$bMueveScroll=false;
$iSector=1;
$bEnviarMailConfirma=false;
// -- Se inicializan las variables, primero las que controlan la visualización de la página.
if (isset($_REQUEST['iscroll'])==0){$_REQUEST['iscroll']=0;}
if (isset($_REQUEST['paginaf111'])==0){$_REQUEST['paginaf111']=1;}
if (isset($_REQUEST['lppf111'])==0){$_REQUEST['lppf111']=20;}
if (isset($_REQUEST['boculta111'])==0){$_REQUEST['boculta111']=0;}
// -- Inicializar variables de datos.
if (isset($_REQUEST['unad11tipodoc'])==0){$_REQUEST['unad11tipodoc']=$APP->tipo_doc;}
if (isset($_REQUEST['unad11doc'])==0){$_REQUEST['unad11doc']='';}
if (isset($_REQUEST['unad11id'])==0){$_REQUEST['unad11id']='';}
if (isset($_REQUEST['unad11pais'])==0){$_REQUEST['unad11pais']=$_SESSION['unad_pais'];}
if (isset($_REQUEST['unad11usuario'])==0){$_REQUEST['unad11usuario']='';}
if (isset($_REQUEST['unad11dv'])==0){$_REQUEST['unad11dv']='';}
if (isset($_REQUEST['unad11nombre1'])==0){$_REQUEST['unad11nombre1']='';}
if (isset($_REQUEST['unad11nombre2'])==0){$_REQUEST['unad11nombre2']='';}
if (isset($_REQUEST['unad11apellido1'])==0){$_REQUEST['unad11apellido1']='';}
if (isset($_REQUEST['unad11apellido2'])==0){$_REQUEST['unad11apellido2']='';}
if (isset($_REQUEST['unad11genero'])==0){$_REQUEST['unad11genero']='';}
if (isset($_REQUEST['unad11fechanace'])==0){$_REQUEST['unad11fechanace']='';}//{fecha_hoy();}
if (isset($_REQUEST['unad11rh'])==0){$_REQUEST['unad11rh']='';}
if (isset($_REQUEST['unad11ecivil'])==0){$_REQUEST['unad11ecivil']='';}
if (isset($_REQUEST['unad11razonsocial'])==0){$_REQUEST['unad11razonsocial']='';}
if (isset($_REQUEST['unad11direccion'])==0){$_REQUEST['unad11direccion']='';}
if (isset($_REQUEST['unad11telefono'])==0){$_REQUEST['unad11telefono']='';}
if (isset($_REQUEST['unad11correo'])==0){$_REQUEST['unad11correo']='';}
if (isset($_REQUEST['unad11sitioweb'])==0){$_REQUEST['unad11sitioweb']='';}
if (isset($_REQUEST['unad11nacionalidad'])==0){$_REQUEST['unad11nacionalidad']=$_SESSION['unad_pais'];}
if (isset($_REQUEST['unad11deptoorigen'])==0){$_REQUEST['unad11deptoorigen']='';}
if (isset($_REQUEST['unad11ciudadorigen'])==0){$_REQUEST['unad11ciudadorigen']='';}
if (isset($_REQUEST['unad11deptodoc'])==0){$_REQUEST['unad11deptodoc']='';}
if (isset($_REQUEST['unad11ciudaddoc'])==0){$_REQUEST['unad11ciudaddoc']='';}
if (isset($_REQUEST['unad11clave'])==0){$_REQUEST['unad11clave']='';}
if (isset($_REQUEST['unad11idmoodle'])==0){$_REQUEST['unad11idmoodle']='';}
if (isset($_REQUEST['unad11idncontents'])==0){$_REQUEST['unad11idncontents']=0;}
if (isset($_REQUEST['unad11iddatateca'])==0){$_REQUEST['unad11iddatateca']='';}
if (isset($_REQUEST['unad11idcampus'])==0){$_REQUEST['unad11idcampus']='';}
if (isset($_REQUEST['unad11claveapps'])==0){$_REQUEST['unad11claveapps']='';}
if (isset($_REQUEST['unad11fechaclaveapps'])==0){$_REQUEST['unad11fechaclaveapps']='';}//{fecha_hoy();}
if (isset($_REQUEST['unad11fechatablero'])==0){$_REQUEST['unad11fechatablero']='';}//{fecha_hoy();}
if (isset($_REQUEST['unad11bloqueado'])==0){$_REQUEST['unad11bloqueado']='';}
if (isset($_REQUEST['unad11aceptanotificacion'])==0){$_REQUEST['unad11aceptanotificacion']='P';}
if (isset($_REQUEST['unad11correonotifica'])==0){$_REQUEST['unad11correonotifica']='';}
if (isset($_REQUEST['unad11correoinstitucional'])==0){$_REQUEST['unad11correoinstitucional']='';}
if (isset($_REQUEST['unad11encuestafecha'])==0){$_REQUEST['unad11encuestafecha']=0;}
if (isset($_REQUEST['unad11encuestaminuto'])==0){$_REQUEST['unad11encuestaminuto']=0;}
if (isset($_REQUEST['unad11latgrados'])==0){$_REQUEST['unad11latgrados']='';}
if (isset($_REQUEST['unad11latdecimas'])==0){$_REQUEST['unad11latdecimas']='';}
if (isset($_REQUEST['unad11longrados'])==0){$_REQUEST['unad11longrados']='';}
if (isset($_REQUEST['unad11longdecimas'])==0){$_REQUEST['unad11longdecimas']='';}
if (isset($_REQUEST['unad11skype'])==0){$_REQUEST['unad11skype']='';}
if (isset($_REQUEST['unad11mostrarcelular'])==0){$_REQUEST['unad11mostrarcelular']='N';}
if (isset($_REQUEST['unad11mostrarcorreo'])==0){$_REQUEST['unad11mostrarcorreo']='N';}
if (isset($_REQUEST['unad11mostrarskype'])==0){$_REQUEST['unad11mostrarskype']='N';}
if (isset($_REQUEST['unad11fechaterminos'])==0){$_REQUEST['unad11fechaterminos']='';}
if (isset($_REQUEST['unad11minutotablero'])==0){$_REQUEST['unad11minutotablero']='';}
if (isset($_REQUEST['unad11noubicar'])==0){$_REQUEST['unad11noubicar']=0;}
if (isset($_REQUEST['unad11idtablero'])==0){$_REQUEST['unad11idtablero']=0;}
if (isset($_REQUEST['unad11fechaconfmail'])==0){$_REQUEST['unad11fechaconfmail']=0;}
if (isset($_REQUEST['unad11rolunad'])==0){$_REQUEST['unad11rolunad']=-1;}
if (isset($_REQUEST['unad11exluirdobleaut'])==0){$_REQUEST['unad11exluirdobleaut']='N';}
if (isset($_REQUEST['unad11idzona'])==0){$_REQUEST['unad11idzona']='';}
if (isset($_REQUEST['unad11idcead'])==0){$_REQUEST['unad11idcead']='';}
if (isset($_REQUEST['unad11idescuela'])==0){$_REQUEST['unad11idescuela']='';}
if (isset($_REQUEST['unad11idprograma'])==0){$_REQUEST['unad11idprograma']='';}
if (isset($_REQUEST['unad11presentacion'])==0){$_REQUEST['unad11presentacion']='';}
if (isset($_REQUEST['unad11fechaclave'])==0){$_REQUEST['unad11fechaclave']='';}//{fecha_hoy();}
if (isset($_REQUEST['unad11fechaultingreso'])==0){$_REQUEST['unad11fechaultingreso']='';}//{fecha_hoy();}
if (isset($_REQUEST['unad11correofuncionario'])==0){$_REQUEST['unad11correofuncionario']='';}
// Espacio para inicializar otras variables
if (isset($_REQUEST['csv_separa'])==0){$_REQUEST['csv_separa']=',';}
if (isset($_REQUEST['bdoc'])==0){$_REQUEST['bdoc']='';}
if (isset($_REQUEST['bnombre'])==0){$_REQUEST['bnombre']='';}
if (isset($_REQUEST['busuario'])==0){$_REQUEST['busuario']='';}
if (isset($_REQUEST['bcorreo'])==0){$_REQUEST['bcorreo']='';}
if (isset($_REQUEST['bcampo'])==0){$_REQUEST['bcampo']='';}
if (isset($_REQUEST['bzona'])==0){$_REQUEST['bzona']='';}
if (isset($_REQUEST['bcead'])==0){$_REQUEST['bcead']='';}
if (isset($_REQUEST['bescuela'])==0){$_REQUEST['bescuela']='';}
if (isset($_REQUEST['bprograma'])==0){$_REQUEST['bprograma']='';}
if (isset($_REQUEST['bconvenio'])==0){$_REQUEST['bconvenio']='';}
if (isset($_REQUEST['badicional'])==0){$_REQUEST['badicional']='';}
//if (isset($_REQUEST['bmatricula'])==0){$_REQUEST['bmatricula']='';}
if (isset($_REQUEST['bdesde'])==0){$_REQUEST['bdesde']='0';}
if (isset($_REQUEST['bhasta'])==0){$_REQUEST['bhasta']='0';}
//Si Modifica o Elimina Cargar los campos
if (($_REQUEST['paso']==1)||($_REQUEST['paso']==3)){
	if ($_REQUEST['paso']==1){
		$sSQLcondi='unad11doc="'.$_REQUEST['unad11doc'].'" AND unad11tipodoc="'.$_REQUEST['unad11tipodoc'].'"';
		}else{
		$sSQLcondi='unad11id='.$_REQUEST['unad11id'].'';
		}
	$sSQL='SELECT * FROM unad11terceros WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)==0){
		//Si no se encuentra y viene por documento habria que importarlo...
		if ($_REQUEST['paso']==1){
			unad11_importar_V2($_REQUEST['unad11doc'], '', $objDB);
			$tabla=$objDB->ejecutasql($sSQL);
			}
		}
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$_REQUEST['unad11tipodoc']=$fila['unad11tipodoc'];
		$_REQUEST['unad11doc']=$fila['unad11doc'];
		$_REQUEST['unad11id']=$fila['unad11id'];
		$_REQUEST['unad11pais']=$fila['unad11pais'];
		$_REQUEST['unad11usuario']=$fila['unad11usuario'];
		$_REQUEST['unad11dv']=$fila['unad11dv'];
		$_REQUEST['unad11nombre1']=$fila['unad11nombre1'];
		$_REQUEST['unad11nombre2']=$fila['unad11nombre2'];
		$_REQUEST['unad11apellido1']=$fila['unad11apellido1'];
		$_REQUEST['unad11apellido2']=$fila['unad11apellido2'];
		$_REQUEST['unad11genero']=$fila['unad11genero'];
		$_REQUEST['unad11fechanace']=$fila['unad11fechanace'];
		$_REQUEST['unad11rh']=$fila['unad11rh'];
		$_REQUEST['unad11ecivil']=$fila['unad11ecivil'];
		$_REQUEST['unad11razonsocial']=$fila['unad11razonsocial'];
		$_REQUEST['unad11direccion']=$fila['unad11direccion'];
		$_REQUEST['unad11telefono']=$fila['unad11telefono'];
		$_REQUEST['unad11correo']=$fila['unad11correo'];
		$_REQUEST['unad11sitioweb']=$fila['unad11sitioweb'];
		$_REQUEST['unad11nacionalidad']=$fila['unad11nacionalidad'];
		$_REQUEST['unad11deptoorigen']=$fila['unad11deptoorigen'];
		$_REQUEST['unad11ciudadorigen']=$fila['unad11ciudadorigen'];
		$_REQUEST['unad11deptodoc']=$fila['unad11deptodoc'];
		$_REQUEST['unad11ciudaddoc']=$fila['unad11ciudaddoc'];
		$_REQUEST['unad11clave']=$fila['unad11clave'];
		$_REQUEST['unad11idmoodle']=$fila['unad11idmoodle'];
		$_REQUEST['unad11idncontents']=$fila['unad11idncontents'];
		$_REQUEST['unad11iddatateca']=$fila['unad11iddatateca'];
		$_REQUEST['unad11idcampus']=$fila['unad11idcampus'];
		$_REQUEST['unad11claveapps']=$fila['unad11claveapps'];
		$_REQUEST['unad11fechaclaveapps']=$fila['unad11fechaclaveapps'];
		$_REQUEST['unad11fechatablero']=$fila['unad11fechatablero'];
		$_REQUEST['unad11bloqueado']=$fila['unad11bloqueado'];
		$_REQUEST['unad11aceptanotificacion']=$fila['unad11aceptanotificacion'];
		$_REQUEST['unad11correonotifica']=$fila['unad11correonotifica'];
		$_REQUEST['unad11correoinstitucional']=$fila['unad11correoinstitucional'];
		$_REQUEST['unad11encuestafecha']=$fila['unad11encuestafecha'];
		$_REQUEST['unad11encuestaminuto']=$fila['unad11encuestaminuto'];
		$_REQUEST['unad11latgrados']=$fila['unad11latgrados'];
		$_REQUEST['unad11latdecimas']=$fila['unad11latdecimas'];
		$_REQUEST['unad11longrados']=$fila['unad11longrados'];
		$_REQUEST['unad11longdecimas']=$fila['unad11longdecimas'];
		$_REQUEST['unad11skype']=$fila['unad11skype'];
		$_REQUEST['unad11mostrarcelular']=$fila['unad11mostrarcelular'];
		$_REQUEST['unad11mostrarcorreo']=$fila['unad11mostrarcorreo'];
		$_REQUEST['unad11mostrarskype']=$fila['unad11mostrarskype'];
		$_REQUEST['unad11fechaterminos']=$fila['unad11fechaterminos'];
		$_REQUEST['unad11minutotablero']=$fila['unad11minutotablero'];
		$_REQUEST['unad11noubicar']=$fila['unad11noubicar'];
		$_REQUEST['unad11idtablero']=$fila['unad11idtablero'];
		$_REQUEST['unad11fechaconfmail']=$fila['unad11fechaconfmail'];
		$_REQUEST['unad11rolunad']=$fila['unad11rolunad'];
		$_REQUEST['unad11exluirdobleaut']=$fila['unad11exluirdobleaut'];
		$_REQUEST['unad11idzona']=$fila['unad11idzona'];
		$_REQUEST['unad11idcead']=$fila['unad11idcead'];
		$_REQUEST['unad11idescuela']=$fila['unad11idescuela'];
		$_REQUEST['unad11idprograma']=$fila['unad11idprograma'];
		$_REQUEST['unad11presentacion']=str_replace('<br />', '', $fila['unad11presentacion']);
		$_REQUEST['unad11presentacion']=str_replace('<br/>', '', $_REQUEST['unad11presentacion']);
		$_REQUEST['unad11presentacion']=str_replace('<br>', '', $_REQUEST['unad11presentacion']);
		$_REQUEST['unad11fechaclave']=$fila['unad11fechaclave'];
		$_REQUEST['unad11fechaultingreso']=$fila['unad11fechaultingreso'];
		$_REQUEST['unad11correofuncionario']=$fila['unad11correofuncionario'];
		$bcargo=true;
		$_REQUEST['paso']=2;
		$_REQUEST['boculta111']=0;
		$bLimpiaHijos=true;
		}else{
		$_REQUEST['paso']=0;
		}
	}
//Insertar o modificar un elemento
if (($_REQUEST['paso']==10)||($_REQUEST['paso']==12)){
	$bMueveScroll=true;
	list($_REQUEST, $sError, $iTipoError, $sDebugGuardar)=f111_db_GuardarV2($_REQUEST, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugGuardar;
	if ($sError==''){
		$sError='<b>'.$ETI['msg_itemguardado'].'</b>';
		$iTipoError=1;
		}
	}
//Eliminar un elemento
if ($_REQUEST['paso']==13){
	$_REQUEST['paso']=2;
	$_REQUEST['unad11tipodoc']=htmlspecialchars(trim($_REQUEST['unad11tipodoc']));
	$_REQUEST['unad11doc']=htmlspecialchars(trim($_REQUEST['unad11doc']));
	$_REQUEST['unad11id']=numeros_validar($_REQUEST['unad11id']);
	$sError=$ERR['4'];
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sWhere='unad11id='.$_REQUEST['unad11id'].'';
		//$sWhere='unad11tipodoc="'.$_REQUEST['unad11tipodoc'].'" AND unad11doc="'.$_REQUEST['unad11doc'].'"';
		$sSQL='DELETE FROM unad11terceros WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sSQL.' -->';
			}else{
			if ($audita[4]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $_REQUEST['unad11id'], $sWhere, $objDB);}
			$_REQUEST['paso']=-1;
			$sError=$ETI['msg_itemeliminado'];
			$iTipoError=1;
			}
		}
	}
//limpiar la pantalla
if ($_REQUEST['paso']==-1){
	$_REQUEST['unad11tipodoc']=$APP->tipo_doc;
	$_REQUEST['unad11doc']='';
	$_REQUEST['unad11id']='';
	$_REQUEST['unad11pais']=$_SESSION['unad_pais'];
	$_REQUEST['unad11usuario']='';
	$_REQUEST['unad11dv']='';
	$_REQUEST['unad11nombre1']='';
	$_REQUEST['unad11nombre2']='';
	$_REQUEST['unad11apellido1']='';
	$_REQUEST['unad11apellido2']='';
	$_REQUEST['unad11genero']='';
	$_REQUEST['unad11fechanace']='';//fecha_hoy();
	$_REQUEST['unad11rh']='';
	$_REQUEST['unad11ecivil']='';
	$_REQUEST['unad11razonsocial']='';
	$_REQUEST['unad11direccion']='';
	$_REQUEST['unad11telefono']='';
	$_REQUEST['unad11correo']='';
	$_REQUEST['unad11sitioweb']='';
	$_REQUEST['unad11nacionalidad']=$_SESSION['unad_pais'];
	$_REQUEST['unad11deptoorigen']='';
	$_REQUEST['unad11ciudadorigen']='';
	$_REQUEST['unad11deptodoc']='';
	$_REQUEST['unad11ciudaddoc']='';
	$_REQUEST['unad11clave']='';
	$_REQUEST['unad11idmoodle']='';
	$_REQUEST['unad11idncontents']='';
	$_REQUEST['unad11iddatateca']='';
	$_REQUEST['unad11idcampus']='';
	$_REQUEST['unad11claveapps']='';
	$_REQUEST['unad11fechaclaveapps']='';
	$_REQUEST['unad11fechatablero']='';
	$_REQUEST['unad11bloqueado']='';
	$_REQUEST['unad11aceptanotificacion']='P';
	$_REQUEST['unad11correonotifica']='';
	$_REQUEST['unad11correoinstitucional']='';
	$_REQUEST['unad11encuestafecha']=0;
	$_REQUEST['unad11encuestaminuto']=0;
	$_REQUEST['unad11latgrados']='';
	$_REQUEST['unad11latdecimas']='';
	$_REQUEST['unad11longrados']='';
	$_REQUEST['unad11longdecimas']='';
	$_REQUEST['unad11skype']='';
	$_REQUEST['unad11mostrarcelular']='N';
	$_REQUEST['unad11mostrarcorreo']='N';
	$_REQUEST['unad11mostrarskype']='N';
	$_REQUEST['unad11fechaterminos']='';
	$_REQUEST['unad11minutotablero']='';
	$_REQUEST['unad11noubicar']=0;
	$_REQUEST['unad11idtablero']=0;
	$_REQUEST['unad11fechaconfmail']=0;
	$_REQUEST['unad11rolunad']=-1;
	$_REQUEST['unad11exluirdobleaut']='N';
	$_REQUEST['unad11idzona']='';
	$_REQUEST['unad11idcead']='';
	$_REQUEST['unad11idescuela']='';
	$_REQUEST['unad11idprograma']='';
	$_REQUEST['unad11presentacion']='';
	$_REQUEST['unad11fechaclave']='';//fecha_hoy();
	$_REQUEST['unad11fechaultingreso']='';//fecha_hoy();
	$_REQUEST['unad11correofuncionario']='';
	$_REQUEST['paso']=0;
	}
//Enviar el codigo de acceso.
if ($_REQUEST['paso']==22){
	$_REQUEST['paso']=2;
	$bMueveScroll=true;
	list($aure01codigo, $sError, $sDebug)=AUREA_IniciarLogin($_REQUEST['unad11id'], $objDB, '', 2, $bDebug);
	if ($sError==''){
		$sError='Se ha enviado un codigo verificaci&oacute;n al correo del usuario.';
		$iTipoError=1;
		require $APP->rutacomun.'libs/cls1504.php';
		$objBitacora=new clsT1504();
		$objBitacora->nuevo(4);
		$objBitacora->bita04idsolicita=$_REQUEST['unad11id'];
		list($sErrorB, $iTipoErrorB, $idAccion, $sDebugB)=$objBitacora->guardar($objDB, $bDebug);
		if ($iTipoErrorB==0){
			$sError=$sError.'<br>ERROR AL GENERAR LA BITACORA: '.$sErrorB;
			}else{
			$sError=$sError.'<br>Se ha generado la bitacora '.$objBitacora->bita04consec;
			}
		}
	}
//Enviar un mail de confirmacion.
if ($bEnviarMailConfirma){
	$_REQUEST['paso']=0;
	list($sCodigo, $sError)=AUREA_ConfirmarCorreoNotifica($idTercero, $objDB);
	if ($sError==''){
		$sError='Se ha enviado un codigo de confirmaci&oacute;n a su correo de notificaciones.';
		$iTipoError=2;
		$iSector=2;
		}
	}
if ($bLimpiaHijos){
	}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
//DATOS PARA COMPLETAR EL FORMULARIO
//Crear los controles que requieran llamado a base de datos
$objCombos=new clsHtmlCombos('n');
$unad11bloqueado_nombre=$ETI['msg_nobloqueado'];
if ($_REQUEST['unad11bloqueado']=='S'){$unad11bloqueado_nombre=$ETI['msg_bloqueado'];}
$html_unad11genero=html_combo('unad11genero', 'unad22codopcion', 'unad22nombre', 'unad22combos', 'unad22idmodulo=111 AND unad22consec=1 AND unad22activa="S"', 'unad22orden', $_REQUEST['unad11genero'], $objDB, '', true, '{'.$ETI['msg_na'].'}', '');
$html_unad11rh=html_combo('unad11rh', 'unad22codopcion', 'unad22nombre', 'unad22combos', 'unad22idmodulo=111 AND unad22consec=2 AND unad22activa="S"', 'unad22orden', $_REQUEST['unad11rh'], $objDB, '', true, '{'.$ETI['msg_na'].'}', '');
$html_unad11ecivil=html_combo('unad11ecivil', 'unad21codigo', 'unad21nombre', 'unad21estadocivil', '', 'unad21orden', $_REQUEST['unad11ecivil'], $objDB, '', true, '{'.$ETI['msg_na'].'}', '');
$html_unad11nacionalidad=html_combo('unad11nacionalidad', 'unad18codigo', 'unad18nombre', 'unad18pais', '', 'unad18nombre', $_REQUEST['unad11nacionalidad'], $objDB, 'carga_combo_unad11deptoorigen();', true, '{'.$ETI['msg_seleccione'].'}', '');
$html_unad11deptoorigen=html_combo_unad11deptoorigen($objDB, $_REQUEST['unad11deptoorigen'], $_REQUEST['unad11nacionalidad']);
$html_unad11deptodoc=html_combo_unad11deptodoc($objDB, $_REQUEST['unad11deptodoc'], $_REQUEST['unad11pais']);
$html_unad11ciudadorigen=html_combo_unad11ciudadorigen($objDB, $_REQUEST['unad11ciudadorigen'], $_REQUEST['unad11deptoorigen']);
$html_unad11ciudaddoc=html_combo_unad11ciudaddoc($objDB, $_REQUEST['unad11ciudaddoc'], $_REQUEST['unad11deptodoc']);
$html_unad11bloqueado=html_oculto('unad11bloqueado', $_REQUEST['unad11bloqueado'], $unad11bloqueado_nombre);
$html_unad11aceptanotificacion=html_sino('unad11aceptanotificacion', $_REQUEST['unad11aceptanotificacion'], true, 'Pendiente', 'P');
$objCombos->nuevo('unad11mostrarcelular', $_REQUEST['unad11mostrarcelular'], false);
$objCombos->sino();
$html_unad11mostrarcelular=$objCombos->html('', $objDB);
$objCombos->nuevo('unad11mostrarcorreo', $_REQUEST['unad11mostrarcorreo'], false);
$objCombos->sino();
$html_unad11mostrarcorreo=$objCombos->html('', $objDB);
$objCombos->nuevo('unad11mostrarskype', $_REQUEST['unad11mostrarskype'], false);
$objCombos->sino();
$html_unad11mostrarskype=$objCombos->html('', $objDB);
if (false){
	$objCombos->nuevo('unad11noubicar', $_REQUEST['unad11noubicar'], false, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->addItem('0', 'No');
	$objCombos->addItem('1', 'Si');
	$html_unad11noubicar=$objCombos->html('', $objDB);
	}else{
	$eti_unad11noubicar=$ETI['no'];
	if ($_REQUEST['unad11noubicar']==1){$eti_unad11noubicar=$ETI['si'];}
	$html_unad11noubicar=html_oculto('unad11noubicar', $_REQUEST['unad11noubicar'], $eti_unad11noubicar);
	}
$objCombos->nuevo('unad11rolunad', $_REQUEST['unad11rolunad'], false, '{'.$ETI['msg_seleccione'].'}');
$objCombos->addItem('-1', 'Sin definir');
$objCombos->addItem('0', 'Estudiante');
$objCombos->addItem('1', 'Contratista');
$objCombos->addItem('2', 'Personal de planta');
$objCombos->addItem('3', 'Egresado');
$html_unad11rolunad=$objCombos->html('', $objDB);
$objCombos->nuevo('unad11exluirdobleaut', $_REQUEST['unad11exluirdobleaut'], false);
$objCombos->sino();
$html_unad11exluirdobleaut=$objCombos->html('', $objDB);
$objCombos->nuevo('unad11idzona', $_REQUEST['unad11idzona'], true, '{'.$ETI['msg_ninguna'].'}', 0);
$objCombos->sAccion='carga_combo_unad11idcead();';
$html_unad11idzona=$objCombos->html('SELECT unad23id AS id, unad23nombre AS nombre FROM unad23zona ORDER BY unad23nombre', $objDB);
$html_unad11idcead=f111_HTMLComboV2_unad11idcead($objDB, $objCombos, $_REQUEST['unad11idcead'], $_REQUEST['unad11idzona']);
$objCombos->nuevo('unad11idescuela', $_REQUEST['unad11idescuela'], true, '{'.$ETI['msg_ninguna'].'}', 0);
$objCombos->sAccion='carga_combo_unad11idprograma();';
$html_unad11idescuela=$objCombos->html('SELECT core12id AS id, core12nombre AS nombre FROM core12escuela ORDER BY core12nombre', $objDB);
$html_unad11idprograma=f111_HTMLComboV2_unad11idprograma($objDB, $objCombos, $_REQUEST['unad11idprograma'], $_REQUEST['unad11idescuela']);
if ((int)$_REQUEST['paso']==0){
	$html_unad11pais=html_combo('unad11pais', 'unad18codigo', 'unad18nombre', 'unad18pais', '', 'unad18nombre', $_REQUEST['unad11pais'], $objDB, 'cambiapagina()', true, '{'.$ETI['msg_seleccione'].'}', '');
	}else{

	list($unad11pais_nombre, $sErrorDet)=tabla_campoxid('unad18pais','unad18nombre','unad18codigo',$_REQUEST['unad11pais'],'{'.$ETI['msg_sindato'].'}', $objDB);
	$html_unad11pais=html_oculto('unad11pais', $_REQUEST['unad11pais'], $unad11pais_nombre);
	}
//Alistar datos adicionales
$objCombos->nuevo('bcampo', $_REQUEST['bcampo'], true, 'Todos los correos');
$objCombos->addItem('1', 'Correo personal');
$objCombos->addItem('2', 'Correo notificaciones');
$objCombos->addItem('3', 'Correo institucional');
$objCombos->addItem('4', 'Correo funcionario');
$objCombos->sAccion='paginarf111()';
$html_bcampo=$objCombos->html('', $objDB);
$objCombos->nuevo('badicional', $_REQUEST['badicional'], true, '{Todos}');
$objCombos->addItem('1', 'Correos confirmados');
$objCombos->addItem('2', 'Correos SIN confirmar');
$objCombos->sAccion='paginarf111()';
$html_badicional=$objCombos->html('', $objDB);
//$objCombos->nuevo('bmatricula', $_REQUEST['bmatricula'], true, '{Todos}');
$objCombos->sAccion='paginarf111()';
$sSQL=f146_ConsultaCombo(2216, $objDB);
//$html_bmatricula=$objCombos->html($sSQL, $objDB);
$objCombos->nuevo('bzona', $_REQUEST['bzona'], true, '{'.$ETI['msg_todas'].'}');
$objCombos->sAccion='carga_combo_bcead()';
$sSQL='SELECT unad23id AS id, unad23nombre AS nombre FROM unad23zona WHERE unad23conestudiantes="S" ORDER BY unad23nombre';
$html_bzona=$objCombos->html($sSQL, $objDB);
$html_bcead=f2202_HTMLComboV2_bcead($objDB, $objCombos, $_REQUEST['bcead'], $_REQUEST['bzona']);
$objCombos->nuevo('bescuela', $_REQUEST['bescuela'], true, '{'.$ETI['msg_todas'].'}');
$objCombos->sAccion='carga_combo_bprograma()';
$sSQL='SELECT core12id AS id, core12nombre AS nombre FROM core12escuela WHERE core12tieneestudiantes="S" AND core12id>0 ORDER BY core12nombre';
$html_bescuela=$objCombos->html($sSQL, $objDB);
$html_bprograma=f2202_HTMLComboV2_bprograma($objDB, $objCombos, $_REQUEST['bprograma'], $_REQUEST['bescuela'], 0);
$objCombos->nuevo('bconvenio', $_REQUEST['bconvenio'], true, '{'.$ETI['msg_todos'].'}');
$objCombos->sAccion='paginarf111()';
$sSQL='SELECT core50id AS id, core50nombre AS nombre FROM core50convenios ORDER BY core50estado DESC, core50nombre';
$html_bconvenio=$objCombos->html($sSQL, $objDB);

$id_rpt=0;
//Permisos adicionales
$seg_5=0;
$seg_6=0;
$seg_111=0;
if (seg_revisa_permiso($iCodModulo, 6, $objDB)){$seg_6=1;}
if ($seg_6==1){}
if (true){
	$objCombos->nuevo('csv_separa', $_REQUEST['csv_separa'], false);
	$objCombos->addItem(',', $ETI['msg_coma']);
	$objCombos->addItem(';', $ETI['msg_puntoycoma']);
	$csv_separa='<label class="Label130">'.$ETI['msg_separador'].'</label><label class="Label130">'.$objCombos->html('', $objDB).'</label>';
	}else{
	$csv_separa='<input id="csv_separa" name="csv_separa" type="hidden" value="," />';
	}
$iNumFormatosImprime=0;
$iModeloReporte=111;
$html_iFormatoImprime='<input id="iformatoimprime" name="iformatoimprime" type="hidden" value="0" />
';
$sCorreoMensajes='';
if ($_REQUEST['paso']>0){
	if (seg_revisa_permiso($iCodModulo, 5, $objDB)){$seg_5=1;}
	if (seg_revisa_permiso($iCodModulo, 111, $objDB)){
		$seg_111=1;
		list($sCorreoMensajes, $sErrorN, $sDebugM)=AUREA_CorreoNotifica($_REQUEST['unad11id'], $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugM;
		$sError=$sError.$sErrorN;
		}
	list($sCorreoUsuario, $sErrorC, $sDebugC, $sCorreoInstitucional)=AUREA_CorreoPrimario($_REQUEST['unad11id'], $objDB, $bDebug);
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Correo principal: <b>'.$sCorreoUsuario.'</b>, Correo Institucional: <b>'.$sCorreoInstitucional.'</b><br>';}
	}
//Cargar las tablas de datos
$aParametros[0]='';//$_REQUEST['p1_111'];
$aParametros[101]=$_REQUEST['paginaf111'];
$aParametros[102]=$_REQUEST['lppf111'];
$aParametros[103]=$_REQUEST['bdoc'];
$aParametros[104]=$_REQUEST['bnombre'];
$aParametros[105]=$_REQUEST['busuario'];
$aParametros[106]=$_REQUEST['bcorreo'];
$aParametros[107]=$_REQUEST['bcampo'];
$aParametros[108]=$_REQUEST['badicional'];
//$aParametros[109]=$_REQUEST['bmatricula'];
$aParametros[110]=$_REQUEST['bconvenio'];
$aParametros[111]=$_REQUEST['bdesde'];
$aParametros[112]=$_REQUEST['bhasta'];

$aParametros[113]=$_REQUEST['bzona']; //109
$aParametros[114]=$_REQUEST['bcead'];// 110
$aParametros[115]=$_REQUEST['bescuela'];//111
$aParametros[116]=$_REQUEST['bprograma'];//112
list($sTabla111, $sDebugTabla)=f111_TablaDetalleV2($aParametros, $objDB, $bDebug);
$sDebug=$sDebug.$sDebugTabla;
list($et_menu, $sDebugM)=html_menuV2($APP->idsistema, $objDB, $iPiel, $bDebug);
$objDB->CerrarConexion();
//FORMA
require $APP->rutacomun.'unad_forma_v2.php';
forma_cabeceraV3($xajax, $ETI['titulo_111']);
echo $et_menu;
forma_mitad();
if (false){
?>
<link rel="stylesheet" href="../ulib/css/criticalPath.css" type="text/css"/>
<link rel="stylesheet" href="../ulib/css/principal.css" type="text/css"/>
<link rel="stylesheet" href="../ulib/unad_estilos2018.css" type="text/css"/>
<?php
	}
?>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/jquery-3.3.1.min.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/popper.min.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/bootstrap.min.js"></script>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/bootstrap.min.css" type="text/css"/>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>css/criticalPath.css" type="text/css"/>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>css/principal.css" type="text/css"/>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>unad_estilos2018.css" type="text/css"/>
<?php
?>
<script language="javascript">
<!--
function limpiapagina(){
	expandesector(98);
	window.document.frmedita.paso.value=-1;
	window.document.frmedita.submit();
	}
function enviaguardar(){
	window.document.frmedita.iscroll.value=window.pageYOffset;
	expandesector(98);
	var dpaso=window.document.frmedita.paso;
	if (dpaso.value==0){
		dpaso.value=10;
		}else{
		dpaso.value=12;
		}
	window.document.frmedita.submit();
	}
function cambiapagina(){
	expandesector(98);
	window.document.frmedita.submit();
	}
function cambiapaginaV2(){
	expandesector(98);
	window.document.frmedita.paso.value=1;
	window.document.frmedita.submit();
	}
function expandepanel(codigo,estado,valor){
	var objdiv= document.getElementById('div_p'+codigo);
	var objban= document.getElementById('boculta'+codigo);
	var otroestado='none';
	if (estado=='none'){otroestado='block';}
	objdiv.style.display=estado;
	objban.value=valor;
	verboton('btrecoge'+codigo,estado);
	verboton('btexpande'+codigo,otroestado);
	}
function verboton(idboton,estado){
	var objbt=document.getElementById(idboton);
	objbt.style.display=estado;
	}
function expandesector(codigo){
	document.getElementById('div_sector1').style.display='none';
	document.getElementById('div_sector2').style.display='none';
	document.getElementById('div_sector95').style.display='none';
	document.getElementById('div_sector96').style.display='none';
	document.getElementById('div_sector98').style.display='none';
	document.getElementById('div_sector'+codigo).style.display='block';
	var sEst='none';
	if (codigo==1){sEst='block';}
	document.getElementById('cmdGuardarf').style.display=sEst;
	}
function imprimelista(){
		window.document.frmlista.consulta.value=window.document.frmedita.consulta_111.value;
		window.document.frmlista.titulos.value=window.document.frmedita.titulos_111.value;
		window.document.frmlista.csv_separa.value=window.document.frmedita.csv_separa.value;
		window.document.frmlista.nombrearchivo.value='Terceros';
		window.document.frmlista.submit();
	}
function asignarvariables(){
	window.document.frmimpp.v3.value=window.document.frmedita.bdoc.value;
	window.document.frmimpp.v4.value=window.document.frmedita.bnombre.value;
	window.document.frmimpp.v5.value=window.document.frmedita.busuario.value;
	window.document.frmimpp.v6.value=window.document.frmedita.bcorreo.value;
	window.document.frmimpp.v7.value=window.document.frmedita.bcampo.value;
	window.document.frmimpp.v8.value=window.document.frmedita.badicional.value;
//	window.document.frmimpp.v9.value=window.document.frmedita.bmatricula.value;
	window.document.frmimpp.v10.value=window.document.frmedita.bconvenio.value;
	window.document.frmimpp.v11.value=window.document.frmedita.bdesde.value;
	window.document.frmimpp.v12.value=window.document.frmedita.bhasta.value;
	window.document.frmimpp.v13.value=window.document.frmedita.bzona.value;
	window.document.frmimpp.v14.value=window.document.frmedita.bcead.value;
	window.document.frmimpp.v15.value=window.document.frmedita.bescuela.value;
	window.document.frmimpp.v16.value=window.document.frmedita.bprograma.value;
	window.document.frmimpp.separa.value=window.document.frmedita.csv_separa.value.trim();
	}
function imprimeexcel(){
	if (window.document.frmedita.seg_6.value==1){
		asignarvariables();
		window.document.frmimpp.action='t111.php';
		window.document.frmimpp.submit();
		}else{
		window.alert("<?php echo $ERR['6']; ?>");
		}
	}
function imprimep(){
	if (window.document.frmedita.seg_5.value==1){
		asignarvariables();
		window.document.frmimpp.action='<?php echo $APP->rutacomun; ?>p111.php';
		window.document.frmimpp.submit();
		}else{
		window.alert("<?php echo $ERR['5']; ?>");
		}
	}
function verrpt(){
	window.document.frmimprime.submit();
	}
function eliminadato(){
	if (confirm("<?php echo $ETI['confirma_eliminar']; ?>?")){
		expandesector(98);
		window.document.frmedita.paso.value=13;
		window.document.frmedita.submit();
		}
	}
function RevisaLlave(){
	var datos= new Array();
	datos[1]=window.document.frmedita.unad11tipodoc.value;
	datos[2]=window.document.frmedita.unad11doc.value;
	datos[9]=window.document.frmedita.debug.value;
	if ((datos[1]!='')&&(datos[2]!='')){
		xajax_f111_ExisteDato(datos);
		}
	ajustaforma();
	}
function cargadato(llave1, llave2){
	window.document.frmedita.unad11tipodoc.value=String(llave1);
	window.document.frmedita.unad11doc.value=String(llave2);
	window.document.frmedita.paso.value=1;
	window.document.frmedita.submit();
	}
function cargaridf111(llave1){
	window.document.frmedita.unad11id.value=String(llave1);
	window.document.frmedita.paso.value=3;
	window.document.frmedita.submit();
	}
function carga_combo_unad11deptoorigen(){
	var params=new Array();
	params[0]=window.document.frmedita.unad11nacionalidad.value;
	xajax_Cargar_unad11deptoorigen(params);
	params[0]='';
	xajax_Cargar_unad11ciudadorigen(params);
	}
function carga_combo_unad11ciudadorigen(){
	var params=new Array();
	params[0]=window.document.frmedita.unad11deptoorigen.value;
	xajax_Cargar_unad11ciudadorigen(params);
	}
function carga_combo_unad11deptodoc(){
	var params=new Array();
	params[0]=window.document.frmedita.unad11pais.value;
	xajax_Cargar_unad11deptodoc(params);
	params[0]='';
	xajax_cargar_unad11ciudaddoc(params);
	}
function carga_combo_unad11ciudaddoc(){
	var params=new Array();
	params[0]=window.document.frmedita.unad11deptodoc.value;
	xajax_Cargar_unad11ciudaddoc(params);
	}
function carga_combo_unad11idcead(){
	var params=new Array();
	params[0]=window.document.frmedita.unad11idzona.value;
	xajax_f111_Combounad11idcead(params);
	}
function carga_combo_unad11idprograma(){
	var params=new Array();
	params[0]=window.document.frmedita.unad11idescuela.value;
	xajax_f111_Combounad11idprograma(params);
	}
function paginarf111(){
	var params=new Array();
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf111.value;
	params[102]=window.document.frmedita.lppf111.value;
	params[103]=window.document.frmedita.bdoc.value;
	params[104]=window.document.frmedita.bnombre.value;
	params[105]=window.document.frmedita.busuario.value;
	params[106]=window.document.frmedita.bcorreo.value;
	params[107]=window.document.frmedita.bcampo.value;
	params[108]=window.document.frmedita.badicional.value;
	//params[109]=window.document.frmedita.bmatricula.value;
	params[110]=window.document.frmedita.bconvenio.value;
	params[111]=window.document.frmedita.bdesde.value;
	params[112]=window.document.frmedita.bhasta.value;
        params[113]=window.document.frmedita.bzona.value;
	params[114]=window.document.frmedita.bcead.value;
	params[115]=window.document.frmedita.bescuela.value;
	params[116]=window.document.frmedita.bprograma.value;
	//document.getElementById('div_f111detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf111" name="paginaf111" type="hidden" value="'+params[101]+'" /><input id="lppf111" name="lppf111" type="hidden" value="'+params[102]+'" />';
	xajax_f111_HtmlTabla(params);
	}
function revfoco(objeto){
	setTimeout(function(){objeto.focus();},10);
	}
function siguienteobjeto(){}
document.onkeydown=function(e){
	if (document.all){
		if (event.keyCode==13){event.keyCode=9;}
		}else{
		if (e.which==13){siguienteobjeto();}
		}
	}
function objinicial(){
	document.getElementById("unad11tipodoc").focus();
	}
function retornacontrol(){
	expandesector(1);
	window.scrollTo(0, window.document.frmedita.iscroll.value);
	}
function mantener_sesion(){xajax_sesion_mantener();}
setInterval ('xajax_sesion_abandona_V2();', 60000);
function AyudaLocal(sCampo){
	var divAyuda=document.getElementById('div_ayuda_'+sCampo);
	if (typeof divAyuda==='undefined'){
		}else{
		verboton('cmdAyuda_'+sCampo, 'none');
		var sMensaje='Lo que quiera decir.';
		//if (sCampo=='sNombreCampo'){sMensaje='Mensaje para otro campo.';}
		divAyuda.innerHTML=sMensaje;
		divAyuda.style.display='block';
		}
	}
function cierraDiv96(ref){
	var sRetorna=window.document.frmedita.div96v2.value;
	MensajeAlarmaV2('', 0);
	retornacontrol();
	}
function ajustaforma(){
	var dtd=document.getElementById('unad11tipodoc');
	var divnom = document.getElementById('divnombres');
	var divrs = document.getElementById('divrazon');
	var sestado='block';
	var sestado2='none';
		if (dtd.value=="NI"){
			sestado='none';
			sestado2='block';
			}
		divnom.style.display=sestado;
		divrs.style.display=sestado2;
	}
function actualizadv(){
	var params=new Array()
	params[0]=window.document.frmedita.unad11id.value;
	xajax_upd_dv(params);
	}
function enviarmailpwd(){
	window.document.frmedita.iscroll.value=window.pageYOffset;
	expandesector(98);
	window.document.frmedita.paso.value=22;
	window.document.frmedita.submit();
	}
function carga_combo_bprograma(){
	var params=new Array();
	params[0]=window.document.frmedita.bescuela.value;
	params[1]=1;
	xajax_f2202_Combobprograma(params);
	}
function carga_combo_bcead(){
	var params=new Array();
	params[0]=window.document.frmedita.bzona.value;
	xajax_f2202_Combobcead(params);
	}
function paginarf2202(){
	paginarf111();
	}
function carga_combo_bversion(){
	paginarf111();
	}		
// -->
</script>
<?php
?>
<form id="frmimpp" name="frmimpp" method="post" action="e111.php" target="_blank">
<input id="r" name="r" type="hidden" value="111" />
<input id="id111" name="id111" type="hidden" value="<?php echo $_REQUEST['unad11id']; ?>" />
<input id="v3" name="v3" type="hidden" value="" />
<input id="v4" name="v4" type="hidden" value="" />
<input id="v5" name="v5" type="hidden" value="" />
<input id="v6" name="v6" type="hidden" value="" />
<input id="v7" name="v7" type="hidden" value="" />
<input id="v8" name="v8" type="hidden" value="" />
<input id="v9" name="v9" type="hidden" value="" />
<input id="v10" name="v10" type="hidden" value="" />
<input id="v11" name="v11" type="hidden" value="" />
<input id="v12" name="v12" type="hidden" value="" />
<input id="v13" name="v13" type="hidden" value="" />
<input id="v14" name="v14" type="hidden" value="" />
<input id="v15" name="v15" type="hidden" value="" />
<input id="v16" name="v16" type="hidden" value="" />
<input id="iformato94" name="iformato94" type="hidden" value="0" />
<input id="separa" name="separa" type="hidden" value="," />
<input id="rdebug" name="rdebug" type="hidden" value="<?php echo $_REQUEST['debug']; ?>"/>
<input id="clave" name="clave" type="hidden" value="" />
</form>
<?php
?>
<form id="frmlista" name="frmlista" method="post" action="listados_csv.php" target="_blank">
<input id="titulos" name="titulos" type="hidden" value="" />
<input id="consulta" name="consulta" type="hidden" value="" />
<input id="csv_separa" name="csv_separa" type="hidden" value="" />
<input id="nombrearchivo" name="nombrearchivo" type="hidden" value="" />
</form>
<div id="interna">
<form id="frmedita" name="frmedita" method="post" action="" autocomplete="off">
<input id="bNoAutocompletar" name="bNoAutocompletar" type="password" value="" style="display:none;"/>
<input id="paso" name="paso" type="hidden" value="<?php echo $_REQUEST['paso']; ?>" />
<input id="shoy" name="shoy" type="hidden" value="<?php echo fecha_hoy(); ?>" />
<input id="ihoy" name="ihoy" type="hidden" value="<?php echo fecha_DiaMod(); ?>" />
<input id="shora" name="shora" type="hidden" value="<?php echo fecha_hora(); ?>" />
<input id="stipodoc" name="stipodoc" type="hidden" value="<?php echo $APP->tipo_doc; ?>" />
<input id="idusuario" name="idusuario" type="hidden" value="<?php echo $_SESSION['unad_id_tercero']; ?>" />
<input id="seg_5" name="seg_5" type="hidden" value="<?php echo $seg_5; ?>" />
<input id="seg_6" name="seg_6" type="hidden" value="<?php echo $seg_6; ?>" />
<div id="div_sector1">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda" name="cmdAyuda" type="button" class="btUpAyuda" onclick="muestraayuda(<?php echo $APP->idsistema.', '.$iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
<?php
$bHayImprimir=false;
$sScript='imprimeexcel()';
$sClaseBoton='btEnviarExcel';
if ($seg_6==1){$bHayImprimir=true;}
if ($_REQUEST['paso']!=0){
	if ($seg_5==1){
		//$bHayImprimir=true;
		//$sScript='imprimep()';
		//$sClaseBoton='btEnviarPDF'; //btUpPrint
		//if ($id_rpt!=0){$sScript='verrpt()';}
		}
	}
if ($bHayImprimir){
?>
<input id="cmdImprimir" name="cmdImprimir" type="button" class="<?php echo $sClaseBoton; ?>" onclick="<?php echo $sScript; ?>" title="<?php echo $ETI['bt_imprimir']; ?>" value="<?php echo $ETI['bt_imprimir']; ?>"/>
<?php
	}
?>
<input id="cmdLimpiar" name="cmdLimpiar" type="button" class="btUpLimpiar" onclick="limpiapagina();" title="<?php echo $ETI['bt_limpiar']; ?>" value="<?php echo $ETI['bt_limpiar']; ?>"/>
<input id="cmdGuardar" name="cmdGuardar" type="button" class="btUpGuardar" onclick="enviaguardar();" title="<?php echo $ETI['bt_guardar']; ?>" value="<?php echo $ETI['bt_guardar']; ?>"/>
</div>
<div class="titulosI">
<?php
echo '<h2>'.$ETI['titulo_111'].'</h2>';
?>
</div>
</div>
<div class="areaform">
<div class="areatrabajo">
<?php
//Div para ocultar
$bconexpande=true;
if ($bconexpande){
?>
<div class="ir_derecha" style="width:62px;">
<input id="boculta111" name="boculta111" type="hidden" value="<?php echo $_REQUEST['boculta111']; ?>" />
<label class="Label30">
<input id="btexpande111" name="btexpande111" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(111,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta111']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge111" name="btrecoge111" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(111,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta111']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div id="div_p111" style="display:<?php if ($_REQUEST['boculta111']==0){echo 'block'; }else{echo 'none';} ?>;">
<?php
	}
//Mostrar formulario para editar
?>
<label class="Label130">
<?php
echo $ETI['unad11doc'];
?>
</label>
<label class="Label90">
<?php
if ($_REQUEST['paso']!=2){
	echo html_tipodoc('unad11tipodoc', $_REQUEST['unad11tipodoc'], false, 'RevisaLlave()');
	}else{
	echo html_oculto('unad11tipodoc', $_REQUEST['unad11tipodoc']);
	}
?>
</label>
<label class="Label220">
<?php
if ($_REQUEST['paso']!=2){
?>
<input id="unad11doc" name="unad11doc" type="text" value="<?php echo $_REQUEST['unad11doc']; ?>" maxlength="13" onchange="RevisaLlave()" class="veinte"/>
<?php
	}else{
	echo html_oculto('unad11doc', $_REQUEST['unad11doc']);
	}
?>
</label>
<label class="Label30">
<?php
echo $ETI['unad11dv'];
?>
</label>
<label class="Label30"><div id="div_unad11dv">
<?php
echo html_oculto('unad11dv', $_REQUEST['unad11dv']);
?>
</div></label>
<?php
$bcondv=false;
if ($_REQUEST['paso']==2){
	switch($_REQUEST['unad11tipodoc']){
		case 'CC':
		case 'NI':
		case 'TI';
		$bcondv=true;
		}
	}
if ($bcondv){
?>
<label class="Label30">
<input type="button" id="brevisadv" name="brevisadv" value="<?php echo $ETI['upd_dv']; ?>" class="btMiniActualizar" onclick="actualizadv()" title="<?php echo $ETI['upd_dv']; ?>"/>
</label>
<?php
	}
?>
<label class="Label60">
<?php
echo $ETI['unad11id'];
?>
</label>
<label class="Label60">
<?php
	echo html_oculto('unad11id', $_REQUEST['unad11id']);
?>
</label>
<label class="Label90">
<?php
echo $ETI['unad11usuario'];
?>
</label>
<label class="Label130"><div id="div_unad11usuario">
<?php
echo html_oculto('unad11usuario', $_REQUEST['unad11usuario']);
?>
</div></label>
<label class="Label130">
<?php
echo $html_unad11bloqueado;
?>
</label>
<div class="salto1px"></div>
<div id="divnombres">
<label class="Label130">
<?php
echo $ETI['unad11nombre1'];
?>
</label>
<label>
<input id="unad11nombre1" name="unad11nombre1" type="text" value="<?php echo $_REQUEST['unad11nombre1']; ?>" maxlength="30" placeholder="<?php echo $ETI['ing_campo'].$ETI['unad11nombre1']; ?>"/>
</label>
<label class="Label160">
<?php
echo $ETI['unad11nombre2'];
?>
</label>
<label>
<input id="unad11nombre2" name="unad11nombre2" type="text" value="<?php echo $_REQUEST['unad11nombre2']; ?>" maxlength="30" placeholder="<?php echo $ETI['ing_campo'].$ETI['unad11nombre2']; ?>"/>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['unad11apellido1'];
?>
</label>
<label>
<input id="unad11apellido1" name="unad11apellido1" type="text" value="<?php echo $_REQUEST['unad11apellido1']; ?>" maxlength="30" placeholder="<?php echo $ETI['ing_campo'].$ETI['unad11apellido1']; ?>"/>
</label>
<label class="Label160">
<?php
echo $ETI['unad11apellido2'];
?>
</label>
<label>
<input id="unad11apellido2" name="unad11apellido2" type="text" value="<?php echo $_REQUEST['unad11apellido2']; ?>" maxlength="30" placeholder="<?php echo $ETI['ing_campo'].$ETI['unad11apellido2']; ?>"/>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['unad11genero'];
?>
</label>
<label class="Label130">
<?php
echo $html_unad11genero;
?>
</label>
<label class="Label160">
<?php
echo $ETI['unad11fechanace'];
?>
</label>
<label class="Label250">
<div>
<?php
echo html_fecha('unad11fechanace', $_REQUEST['unad11fechanace'], true, '', 1900, date('Y'));
?>
</div>
</label>
<label class="Label60">
<?php
echo $ETI['unad11rh'];
?>
</label>
<label class="Label130">
<?php
echo $html_unad11rh;
?>
</label>
<label class="Label130">
<?php
echo $ETI['unad11ecivil'];
?>
</label>
<label class="Label130">
<?php
echo $html_unad11ecivil;
?>
</label>
</div>
<div id="divrazon">
<label class="L">
<?php
echo $ETI['unad11razonsocial'];
?>

<input id="unad11razonsocial" name="unad11razonsocial" type="text" value="<?php echo $_REQUEST['unad11razonsocial']; ?>" maxlength="100" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['unad11razonsocial']; ?>"/>
</label>
</div>
<label class="L">
<?php
echo $ETI['unad11direccion'];
?>

<input id="unad11direccion" name="unad11direccion" type="text" value="<?php echo $_REQUEST['unad11direccion']; ?>" maxlength="100" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['unad11direccion']; ?>"/>
</label>
<label class="Label130">
<?php
echo $ETI['unad11telefono'];
?>
</label>
<label class="Label220">
<input id="unad11telefono" name="unad11telefono" type="text" value="<?php echo $_REQUEST['unad11telefono']; ?>" maxlength="30" placeholder="<?php echo $ETI['ing_campo'].$ETI['unad11telefono']; ?>"/>
</label>
<label class="Label60">
<?php
echo $ETI['unad11correo'];
?>
</label>
<label class="Label350">
<input name="unad11correo" type="text" id="unad11correo" value="<?php echo $_REQUEST['unad11correo']; ?>" maxlength="50" class="Label350"/>
</label>
<label class="L">
<?php
echo $ETI['unad11sitioweb'];
?>
<input name="unad11sitioweb" type="text" id="unad11sitioweb" value="<?php echo $_REQUEST['unad11sitioweb']; ?>" maxlength="50" class="L"/>
</label>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="Label130">
<?php
echo $ETI['unad11nacionalidad'];
?>
</label>
<label>
<?php
echo $html_unad11nacionalidad;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['unad11deptoorigen'];
?>
</label>
<label><div id="div_unad11deptoorigen">
<?php
echo $html_unad11deptoorigen;
?>
</div></label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['unad11ciudadorigen'];
?>
</label>
<label><div id="div_unad11ciudadorigen">
<?php
echo $html_unad11ciudadorigen;
?>
</div></label>
<div class="salto1px"></div>
</div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
Lugar de residencia
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['unad11pais'];
?>
</label>
<label>
<?php
echo $html_unad11pais;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['unad11deptodoc'];
?>
</label>
<label><div id="div_unad11deptodoc">
<?php
echo $html_unad11deptodoc;
?>
</div></label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['unad11ciudaddoc'];
?>
</label>
<label><div id="div_unad11ciudaddoc">
<?php
echo $html_unad11ciudaddoc;
?>
</div></label>
<div class="salto1px"></div>
</div>

<div class="salto1px"></div>
<div class="GrupoCampos">
<div class="salto5px"></div>
<label class="Label90">
<?php
echo $ETI['unad11idmoodle'];
?>
</label>
<label class="Label130">
<input id="unad11idmoodle" name="unad11idmoodle" type="text" value="<?php echo $_REQUEST['unad11idmoodle']; ?>" class="diez" maxlength="10"/>
</label>
<label class="Label90">
<?php
echo $ETI['unad11idncontents'];
?>
</label>
<label class="Label130">
<input id="unad11idncontents" name="unad11idncontents" type="text" value="<?php echo $_REQUEST['unad11idncontents']; ?>" class="diez" maxlength="10"/>
</label>
<label class="Label90">
<?php
echo $ETI['unad11iddatateca'];
?>
</label>
<label class="Label130">
<input id="unad11iddatateca" name="unad11iddatateca" type="text" value="<?php echo $_REQUEST['unad11iddatateca']; ?>" class="diez" maxlength="10"/>
</label>
<label class="Label90">
<?php
echo $ETI['unad11idcampus'];
?>
</label>
<label class="Label130">
<input id="unad11idcampus" name="unad11idcampus" type="text" value="<?php echo $_REQUEST['unad11idcampus']; ?>" class="diez" maxlength="10"/>
</label>
<div class="salto1px"></div>
</div>

<div class="salto1px"></div>
<div class="GrupoCampos">
<?php
echo '<b>'.$ETI['msg_notificaciones'].'</b>';
?>
<div class="salto1px"></div>
<label class="Label200">
<?php
echo $ETI['unad11aceptanotificacion'];
?>
</label>
<label class="Label130">
<?php
echo $html_unad11aceptanotificacion;
?>
</label>
<div class="salto1px"></div>
<label class="L">
<?php
echo $ETI['unad11correonotifica'];
?>
<?php
$bBloqueado=false;
if ($_REQUEST['unad11fechaconfmail']!=0){
	$bBloqueado=true;
	if ($seg_111==1){$bBloqueado=false;}
	}
if ($bBloqueado){
	echo html_oculto('unad11correonotifica', $_REQUEST['unad11correonotifica']);
	}else{
?>
<input id="unad11correonotifica" name="unad11correonotifica" type="text" value="<?php echo $_REQUEST['unad11correonotifica']; ?>" maxlength="50" placeholder="<?php echo $ETI['ing_campo'].$ETI['unad11correonotifica']; ?>" class="L"/>
<?php
	}
?>
</label>
<label class="Label300">
<?php
echo $ETI['unad11fechaconfmail'];
?>
</label>
<label class="Label130"><div id="div_unad11fechaconfmail">
<?php
echo html_oculto('unad11fechaconfmail', $_REQUEST['unad11fechaconfmail']);
?>
</div></label>
<label class="L">
<?php
echo $ETI['unad11correoinstitucional'];
?>
<input id="unad11correoinstitucional" name="unad11correoinstitucional" type="text" value="<?php echo $_REQUEST['unad11correoinstitucional']; ?>" maxlength="50" placeholder="usuario@unadvirtual.edu.co" class="L"/>
</label>
<label class="L">
<?php
echo $ETI['unad11correofuncionario'];
?>
<input id="unad11correofuncionario" name="unad11correofuncionario" type="text" value="<?php echo $_REQUEST['unad11correofuncionario']; ?>" maxlength="50" placeholder="nombre.apellido@unad.edu.co" class="L"/>
</label>
<?php
if ($seg_111==1){
?>
<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
Para que el usuario pueda restaurar su clave se enviar&aacute; un mensaje de restablecimiento al correo <b><?php echo $sCorreoMensajes; ?></b>
<div class="salto1px"></div>
<label class="Label160">&nbsp;</label>
<label class="Labe220">
<input id="cmdIngresa2" name="cmdIngresa2" type="button" value="Contrase&ntilde;a"  onClick="enviarmailpwd();" class="BotonAzul" title="Enviar mensaje de restauraci&oacute;n de contrase&ntilde;a"/>
</label>
<div class="salto1px"></div>
</div>
<?php
	}
?>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['unad11skype'];
?>
</label>
<label>
<input id="unad11skype" name="unad11skype" type="text" value="<?php echo $_REQUEST['unad11skype']; ?>" maxlength="50" placeholder="<?php echo $ETI['ing_campo'].$ETI['unad11skype']; ?>"/>
</label>
<label class="Label130">
<?php
echo $ETI['unad11mostrarcelular'];
?>
</label>
<label class="Label60">
<?php
echo $html_unad11mostrarcelular;
?>
</label>
<label class="Label130">
<?php
echo $ETI['unad11mostrarcorreo'];
?>
</label>
<label class="Label60">
<?php
echo $html_unad11mostrarcorreo;
?>
</label>
<label class="Label130">
<?php
echo $ETI['unad11mostrarskype'];
?>
</label>
<label class="Label60">
<?php
echo $html_unad11mostrarskype;
?>
</label>
<div class="salto1px"></div>
</div>

<div class="salto1px"></div>
<div class="GrupoCampos">
<?php
echo '<b>'.$ETI['msg_autentica'].'</b>';
?>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['unad11rolunad'];
?>
</label>
<label>
<div id="div_unad11rolunad">
<?php
echo $html_unad11rolunad;
?>
</div>
</label>
<label class="Label250">
<?php
echo $ETI['unad11exluirdobleaut'];
?>
</label>
<label class="Label130">
<?php
echo $html_unad11exluirdobleaut;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['unad11idzona'];
?>
</label>
<label class="Label600">
<?php
echo $html_unad11idzona;
?>
</label>
<label class="Label90">
<?php
echo $ETI['unad11idcead'];
?>
</label>
<label>
<div id="div_unad11idcead">
<?php
echo $html_unad11idcead;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['unad11idescuela'];
?>
</label>
<label class="Label600">
<?php
echo $html_unad11idescuela;
?>
</label>
<label class="Label90">
<?php
echo $ETI['unad11idprograma'];
?>
</label>
<label>
<div id="div_unad11idprograma">
<?php
echo $html_unad11idprograma;
?>
</div>
</label>
<div class="salto1px"></div>
</div>

<div class="salto1px"></div>
<div class="GrupoCampos">
<?php
echo '<b>'.$ETI['msg_geolocaliza'].'</b>';
?>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['unad11latgrados'];
?>
</label>
<label class="Label130">
<input id="unad11latgrados" name="unad11latgrados" type="text" value="<?php echo $_REQUEST['unad11latgrados']; ?>" class="diez" maxlength="10" placeholder="<?php echo $ETI['ing_vr']; ?>"/>
</label>
<label>
<input id="unad11latdecimas" name="unad11latdecimas" type="text" value="<?php echo $_REQUEST['unad11latdecimas']; ?>" maxlength="10" placeholder="<?php echo $ETI['ing_campo'].$ETI['unad11latdecimas']; ?>"/>
</label>
<label class="Label90">
<?php
echo $ETI['unad11longrados'];
?>
</label>
<label class="Label130">
<input id="unad11longrados" name="unad11longrados" type="text" value="<?php echo $_REQUEST['unad11longrados']; ?>" class="diez" maxlength="10" placeholder="<?php echo $ETI['ing_vr']; ?>"/>
</label>
<label>
<input id="unad11longdecimas" name="unad11longdecimas" type="text" value="<?php echo $_REQUEST['unad11longdecimas']; ?>" maxlength="10" placeholder="<?php echo $ETI['ing_campo'].$ETI['unad11longdecimas']; ?>"/>
</label>
<div class="salto1px"></div>
<label class="Label200">
<?php
echo $ETI['unad11noubicar'];
?>
</label>
<label class="Label60">
<?php
echo $html_unad11noubicar;
?>
</label>
<div class="salto1px"></div>
</div>

<div class="salto1px"></div>
<div class="GrupoCampos">
<?php
echo '<b>'.$ETI['msg_informativo'].'</b>';
?>
<div class="salto1px"></div>
<label class="txtAreaM">
<?php
echo $ETI['unad11presentacion'];
?>
<textarea id="unad11presentacion" name="unad11presentacion" placeholder="<?php echo $ETI['ing_campo'].$ETI['unad11presentacion']; ?>" disabled="disabled"><?php echo $_REQUEST['unad11presentacion']; ?></textarea>
</label>
<div class="salto1px"></div>
<label class="Label200">
<?php
echo $ETI['unad11fechaclave'];
?>
</label>
<label class="Label130">
<?php
echo html_oculto('unad11fechaclave', $_REQUEST['unad11fechaclave'], fecha_desdenumero($_REQUEST['unad11fechaclave'], '&nbsp;'));
?>
</label>
<label class="Label200">
<?php
echo $ETI['unad11fechaultingreso'];
?>
</label>
<label class="Label130">
<?php
echo html_oculto('unad11fechaultingreso', $_REQUEST['unad11fechaultingreso'], fecha_desdenumero($_REQUEST['unad11fechaultingreso'], '&nbsp;'));
?>
</label>

<div class="salto1px"></div>
<label class="Label200">
<?php
echo $ETI['unad11fechaclaveapps'];
?>
</label>
<label class="Label130">
<?php
echo html_oculto("unad11fechaclaveapps", $_REQUEST['unad11fechaclaveapps']);//, formato_fechalarga($_REQUEST['unad11fechaclaveapps']));
?>
</label>
<label class="Label130">
<?php
echo $ETI['unad11fechatablero'];
?>
</label>
<label class="Label130">
<?php
echo html_oculto("unad11fechatablero", $_REQUEST['unad11fechatablero']);//, formato_fechalarga($_REQUEST['unad11fechatablero']));
?>
</label>
<label class="Label130"><div id="div_unad11minutotablero">
<?php
echo html_oculto('unad11minutotablero', $_REQUEST['unad11minutotablero'], html_TablaHoraMinDesdeNumero($_REQUEST['unad11minutotablero']));
?>
</div></label>
<label class="Label130">
<?php
echo $ETI['unad11idtablero'];
?>
</label>
<label class="Label130"><div id="div_unad11idtablero">
<?php
echo html_oculto('unad11idtablero', $_REQUEST['unad11idtablero']);
?>
</div></label>
<div class="salto1px"></div>
<label class="Label300">
<?php
echo $ETI['unad11encuestafecha'];
?>
</label>
<label class="Label130">
<?php
echo html_oculto('unad11encuestafecha', $_REQUEST['unad11encuestafecha'], fecha_desdenumero($_REQUEST['unad11encuestafecha'], '&nbsp;'));
?>
</label>
<label class="Label130"><div id="div_unad11encuestaminuto">
<?php
$et_unad11encuestaminuto='&nbsp;';
if ($_REQUEST['unad11encuestaminuto']!=0){$et_unad11encuestaminuto=html_TablaHoraMinDesdeNumero($_REQUEST['unad11encuestaminuto']);}
echo html_oculto('unad11encuestaminuto', $_REQUEST['unad11encuestaminuto'], $et_unad11encuestaminuto);
?>
</div></label>
<label class="Label250">
<?php
echo $ETI['unad11fechaterminos'];
?>
</label>
<label class="Label130">
<?php
echo html_oculto('unad11fechaterminos', $_REQUEST['unad11fechaterminos'], fecha_desdenumero($_REQUEST['unad11fechaterminos'], '&nbsp;'));
?>
</label>
<div class="salto1px"></div>
</div>

<?php
if (false){
	//Ejemplo de boton de ayuda
	//echo html_BotonAyuda('NombreCampo');
	//echo html_DivAyudaLocal('NombreCampo');
	}
if ($bconexpande){
	//Este es el cierre del div_p111
?>
<div class="salto1px"></div>
</div>
<?php
	}
//Mostrar el contenido de la tabla
?>
</div><!-- CIERRA EL DIV areatrabajo -->
</div><!-- CIERRA EL DIV areaform -->
<div class="areaform">
<div class="areatitulo">
<?php
echo '<h3>'.$ETI['bloque1'].'</h3>';
?>
</div>
<div class="areatrabajo">
<div class="ir_derecha">
<label class="Label130">
Documento
</label>
<label class="Label220">
<input name="bdoc" type="text" id="bdoc" value="<?php echo $_REQUEST['bdoc']; ?>" onchange="paginarf111()" autocomplete="off"/>
</label>
<label class="Label90">
Nombre
</label>
<label class="Label220">
<input id="bnombre" name="bnombre" type="text" value="<?php echo $_REQUEST['bnombre']; ?>" onchange="paginarf111()" autocomplete="off"/>
</label>
<label class="Label90">
Usuario
</label>
<label class="Label200">
<input name="busuario" type="text" id="busuario" value="<?php echo $_REQUEST['busuario']; ?>" onchange="paginarf111()" autocomplete="off"/>
</label>
<div class="salto1px"></div>
<label class="Label130">
Correo
</label>
<label class="Label220">
<input id="bcorreo" name="bcorreo" type="text" value="<?php echo $_REQUEST['bcorreo']; ?>" onchange="paginarf111()" autocomplete="off"/>
</label>
<label class="Label200">
<?php
echo $html_bcampo;
?>
</label>
<label class="Label130">
Verificaciones
</label>
<label class="Label200">
<?php
echo $html_badicional;
?>
</label>

<div class="salto1px"></div>
<label class="Label130">
Escuela
</label>
<label class="Label600">
<?php
echo $html_bescuela;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
Programa
</label>
<label class="Label130">
<div id="div_bprograma">
<?php
echo $html_bprograma;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">
Zona
</label>
<label class="Label350">
<?php
echo $html_bzona;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
CEAD
</label>
<label class="Label130">
<div id="div_bcead">
<?php
echo $html_bcead;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">
Convenio
</label>
<label class="Label500">
<?php
echo $html_bconvenio;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
Ingreso desde
</label>
<label class="Label250">
<?php
echo html_FechaEnNumero('bdesde', $_REQUEST['bdesde'], true, 'paginarf111();', 1900, date('Y'));
?>
</label>
<label class="Label130">
Hasta
</label>
<label class="Label250">
<?php
echo html_FechaEnNumero('bhasta', $_REQUEST['bhasta'], true, 'paginarf111();', 1900, date('Y'));
?>
</label>
<div class="salto1px"></div>
<?php
echo ' '.$csv_separa;
?>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<div id="div_f111detalle">
<?php
echo $sTabla111;
?>
</div>
</div><!-- /div_areatrabajo -->
</div><!-- /DIV_areaform -->
</div><!-- /DIV_Sector1 -->


<div id="div_sector2" style="display:none">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda2" name="cmdAyuda2" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
<input id="cmdVolverSec2" name="cmdVolverSec2" type="button" class="btSupVolver" onclick="expandesector(1);" title="<?php echo $ETI['bt_volver']; ?>" value="<?php echo $ETI['bt_volver']; ?>"/>
</div>
<div class="titulosI">
<?php
echo '<h2>'.$ETI['titulo_sectorcambiaclave'].'</h2>';
?>
</div>
</div>
<div id="cargaForm">
<div id="area">
Para que el usuario pueda restaurar su clave se enviar&aacute; un mensaje de restablecimiento al correo <b><?php echo $sCorreoMensajes; ?></b>
<div class="salto1px"></div>
<label class="Label160">&nbsp;</label>
<label class="Labe220">
<input id="cmdEnviaClave" name="cmdEnviaClave" type="button" value="Contrase&ntilde;a"  onClick="enviarmailpwd();" class="BotonAzul" title="Enviar mensaje de restauraci&oacute;n de contrase&ntilde;a"/>
</label>
</div><!-- /div_area -->
</div><!-- /DIV_cargaForm -->
</div><!-- /DIV_Sector2 -->


<div id="div_sector95" style="display:none">
<div id="cargaForm">
<div id="div_95cuerpo"></div>
</div><!-- /DIV_cargaForm -->
</div><!-- /DIV_Sector95 -->


<div id="div_sector96" style="display:none">
<input id="div96v1" name="div96v1" type="hidden" value="" />
<input id="div96v2" name="div96v2" type="hidden" value="" />
<input id="div96v3" name="div96v3" type="hidden" value="" />
<input id="div96campo" name="div96campo" type="hidden" value="" />
<input id="div96llave" name="div96llave" type="hidden" value="" />
<input id="titulo_111" name="titulo_111" type="hidden" value="<?php echo $ETI['titulo_111']; ?>" />
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda96" name="cmdAyuda96" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
</div>
<div class="titulosI" id="div_96titulo"></div>
</div>
<div id="cargaForm">
<div id="div_96cuerpo"></div>
</div><!-- /DIV_cargaForm -->
</div><!-- /DIV_Sector96 -->


<div id="div_sector98" style="display:none">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda98" name="cmdAyuda98" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
</div>
<div class="titulosI">
<?php
echo '<h2>'.$ETI['titulo_111'].'</h2>';
?>
</div>
</div>
<div id="cargaForm">
<div id="area">
<div class="MarquesinaMedia">
<?php
echo $ETI['msg_espere'];
?>
</div><!-- /Termina la marquesina -->
</div><!-- /div_area -->
</div><!-- /DIV_cargaForm -->
</div><!-- /DIV_Sector98 -->


<?php
if ($sDebug!=''){
	$iSegFin=microtime(true);
	$iSegundos=$iSegFin-$iSegIni;
	echo '<div class="salto1px"></div><div class="GrupoCampos" id="div_debug">'.$sDebug.fecha_microtiempo().' Tiempo total del proceso: <b>'.$iSegundos.'</b> Segundos'.'<div class="salto1px"></div></div>';
	}
?>
<input id="scampobusca" name="scampobusca" type="hidden" value=""/>
<input id="iscroll" name="iscroll" type="hidden" value="<?php echo $_REQUEST['iscroll']; ?>"/>
<input id="itipoerror" name="itipoerror" type="hidden" value="<?php echo $iTipoError; ?>"/>
<input id="debug" name="debug" type="hidden" value="<?php echo $_REQUEST['debug']; ?>"/>
</form>
</div><!-- /DIV_interna -->
<div class="flotante">
<input id="cmdGuardarf" name="cmdGuardarf" type="button" class="btSoloGuardar" onClick="enviaguardar();" value="<?php echo $ETI['bt_guardar']; ?>"/>
</div>
<?php
echo html_DivAlarmaV2($sError, $iTipoError);
	//El script que cambia el sector que se muestra
?>

<script language="javascript">
<!--
<?php
if ($iSector!=1){
	echo 'setTimeout(function(){expandesector('.$iSector.');}, 10);
';
	}
if ($bMueveScroll){
	echo 'setTimeout(function(){retornacontrol();}, 2);
';
	}
?>
ajustaforma();
-->
</script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas.js?ver=8"></script>
<?php
forma_piedepagina();
?>