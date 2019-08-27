<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
*/
/** Archivo evenregistro.php.
* Modulo 1902 even02evento.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @param debug=1 (Opcional), bandera para indicar si se generan datos de depuración
* @date Tuesday, August 27, 2019
*/
if (file_exists('./err_control.php')){require './err_control.php';}
$bDebug=false;
$sDebug='';
if (isset($_REQUEST['deb_doc'])!=0){
	$_REQUEST['debug']=1;
	}
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
if (($bPeticionXAJAX)&&($_SESSION['unad_id_tercero']==0)){
	// viene por xajax.
	$xajax=new xajax();
	$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
	$xajax->register(XAJAX_FUNCTION,'sesion_abandona_V2');
	$xajax->processRequest();
	die();
	}
$grupo_id=1;//Necesita ajustarlo...
$iCodModulo=1902;
$audita[1]=false;
$audita[2]=true;
$audita[3]=true;
$audita[4]=true;
$audita[5]=false;
// -- Se cargan los archivos de idioma
$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
$mensajes_1902='lg/lg_1902_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_1902)){$mensajes_1902='lg/lg_1902_es.php';}
require $mensajes_todas;
require $mensajes_1902;
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
$bOtroUsuario=false;
if (isset($_REQUEST['deb_doc'])!=0){
	if (seg_revisa_permiso($iCodModulo, 1707, $objDB)){
		$sSQL='SELECT unad11id, unad11razonsocial FROM unad11terceros WHERE unad11doc="'.$_REQUEST['deb_doc'].'"';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$idTercero=$fila['unad11id'];
			$bOtroUsuario=true;
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Se verifica la ventana de trabajo para el usuario '.$fila['unad11razonsocial'].'.<br>';}
			}else{
			$sError='No se ha encontrado el documento &quot;'.$_REQUEST['deb_doc'].'&quot;';
			$_REQUEST['deb_doc']='';
			}
		}else{
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' No cuenta con permiso de ingreso como otro usuario Modulo '.$iCodModulo.' Permiso.<br>';}
		$_REQUEST['deb_doc']='';
		}
	}else{
	$_REQUEST['deb_doc']='';
	}
if (!seg_revisa_permiso($iCodModulo, 1, $objDB)){
	header('Location:nopermiso.php');
	die();
	}
if (!$bPeticionXAJAX){
	if (noticias_pendientes($objDB)){
		$objDB->CerrarConexion();
		header('Location:noticia.php?ret=evenregistro.php');
		die();
		}
	}
//PROCESOS DE LA PAGINA
$mensajes_1903='lg/lg_1903_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_1903)){$mensajes_1903='lg/lg_1903_es.php';}
$mensajes_1904='lg/lg_1904_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_1904)){$mensajes_1904='lg/lg_1904_es.php';}
$mensajes_1905='lg/lg_1905_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_1905)){$mensajes_1905='lg/lg_1905_es.php';}
require $mensajes_1903;
require $mensajes_1904;
require $mensajes_1905;
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso'])==0){
	$_REQUEST['paso']=-1;
	if ($audita[1]){seg_auditaingreso($iCodModulo, $_SESSION['unad_id_tercero'], $objDB);}
	}
// -- 1902 even02evento
require 'lib1902.php';
// -- 1903 Cursos
require 'lib1903.php';
// -- 1904 Participantes
require 'lib1904.php';
// -- 1905 Noticias
require 'lib1905.php';
$xajax=new xajax();
$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
$xajax->register(XAJAX_FUNCTION,'unad11_Mostrar_v2');
$xajax->register(XAJAX_FUNCTION,'unad11_TraerXid');
$xajax->register(XAJAX_FUNCTION,'f1902_Comboeven02categoria');
$xajax->register(XAJAX_FUNCTION,'f1902_Comboeven02idcead');
$xajax->register(XAJAX_FUNCTION,'f1902_Busqueda_even02idcertificado');
$xajax->register(XAJAX_FUNCTION,'f1902_Busqueda_even02idrubrica');
$xajax->register(XAJAX_FUNCTION,'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION,'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION,'f1902_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f1902_ExisteDato');
$xajax->register(XAJAX_FUNCTION,'f1902_Busquedas');
$xajax->register(XAJAX_FUNCTION,'f1902_HtmlBusqueda');
$xajax->register(XAJAX_FUNCTION,'f1903_Busqueda_even03idcurso');
$xajax->register(XAJAX_FUNCTION,'f1903_Guardar');
$xajax->register(XAJAX_FUNCTION,'f1903_Traer');
$xajax->register(XAJAX_FUNCTION,'f1903_Eliminar');
$xajax->register(XAJAX_FUNCTION,'f1903_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f1903_PintarLlaves');
$xajax->register(XAJAX_FUNCTION,'f1904_Guardar');
$xajax->register(XAJAX_FUNCTION,'f1904_Traer');
$xajax->register(XAJAX_FUNCTION,'f1904_Eliminar');
$xajax->register(XAJAX_FUNCTION,'f1904_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f1904_PintarLlaves');
$xajax->register(XAJAX_FUNCTION,'f1905_Guardar');
$xajax->register(XAJAX_FUNCTION,'f1905_Traer');
$xajax->register(XAJAX_FUNCTION,'f1905_Eliminar');
$xajax->register(XAJAX_FUNCTION,'f1905_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f1905_PintarLlaves');
$xajax->register(XAJAX_FUNCTION,'f1903_Comboeven02idzona');
$xajax->register(XAJAX_FUNCTION,'f1903_Comboeven02idcead');
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
// -- Se inicializan las variables, primero las que controlan la visualización de la página.
if (isset($_REQUEST['iscroll'])==0){$_REQUEST['iscroll']=0;}
if (isset($_REQUEST['paginaf1902'])==0){$_REQUEST['paginaf1902']=1;}
if (isset($_REQUEST['lppf1902'])==0){$_REQUEST['lppf1902']=20;}
if (isset($_REQUEST['boculta1902'])==0){$_REQUEST['boculta1902']=0;}
if (isset($_REQUEST['paginaf1903'])==0){$_REQUEST['paginaf1903']=1;}
if (isset($_REQUEST['lppf1903'])==0){$_REQUEST['lppf1903']=20;}
if (isset($_REQUEST['boculta1903'])==0){$_REQUEST['boculta1903']=0;}
if (isset($_REQUEST['paginaf1904'])==0){$_REQUEST['paginaf1904']=1;}
if (isset($_REQUEST['lppf1904'])==0){$_REQUEST['lppf1904']=20;}
if (isset($_REQUEST['boculta1904'])==0){$_REQUEST['boculta1904']=0;}
if (isset($_REQUEST['paginaf1905'])==0){$_REQUEST['paginaf1905']=1;}
if (isset($_REQUEST['lppf1905'])==0){$_REQUEST['lppf1905']=20;}
if (isset($_REQUEST['boculta1905'])==0){$_REQUEST['boculta1905']=0;}
// -- Inicializar variables de datos.
if (isset($_REQUEST['even02consec'])==0){$_REQUEST['even02consec']='';}
if (isset($_REQUEST['even02consec_nuevo'])==0){$_REQUEST['even02consec_nuevo']='';}
if (isset($_REQUEST['even02id'])==0){$_REQUEST['even02id']='';}
if (isset($_REQUEST['even02tipo'])==0){$_REQUEST['even02tipo']='';}
if (isset($_REQUEST['even02categoria'])==0){$_REQUEST['even02categoria']='';}
if (isset($_REQUEST['even02estado'])==0){$_REQUEST['even02estado']=0;}
if (isset($_REQUEST['even02publicado'])==0){$_REQUEST['even02publicado']='';}
if (isset($_REQUEST['even02nombre'])==0){$_REQUEST['even02nombre']='';}
if (isset($_REQUEST['even02idzona'])==0){$_REQUEST['even02idzona']='';}
if (isset($_REQUEST['even02idcead'])==0){$_REQUEST['even02idcead']='';}
if (isset($_REQUEST['even02peraca'])==0){$_REQUEST['even02peraca']='';}
if (isset($_REQUEST['even02lugar'])==0){$_REQUEST['even02lugar']='';}
if (isset($_REQUEST['even02inifecha'])==0){$_REQUEST['even02inifecha']='';}//{fecha_hoy();}
if (isset($_REQUEST['even02inihora'])==0){$_REQUEST['even02inihora']=fecha_hora();}
if (isset($_REQUEST['even02iniminuto'])==0){$_REQUEST['even02iniminuto']=fecha_minuto();}
if (isset($_REQUEST['even02finfecha'])==0){$_REQUEST['even02finfecha']='';}//{fecha_hoy();}
if (isset($_REQUEST['even02finhora'])==0){$_REQUEST['even02finhora']=fecha_hora();}
if (isset($_REQUEST['even02finminuto'])==0){$_REQUEST['even02finminuto']=fecha_minuto();}
if (isset($_REQUEST['even02idorganizador'])==0){$_REQUEST['even02idorganizador']=0;}// {$_SESSION['unad_id_tercero'];}
if (isset($_REQUEST['even02idorganizador_td'])==0){$_REQUEST['even02idorganizador_td']=$APP->tipo_doc;}
if (isset($_REQUEST['even02idorganizador_doc'])==0){$_REQUEST['even02idorganizador_doc']='';}
if (isset($_REQUEST['even02contacto'])==0){$_REQUEST['even02contacto']='';}
if (isset($_REQUEST['even02insfechaini'])==0){$_REQUEST['even02insfechaini']='';}//{fecha_hoy();}
if (isset($_REQUEST['even02insfechafin'])==0){$_REQUEST['even02insfechafin']='';}//{fecha_hoy();}
if (isset($_REQUEST['even02idcertificado'])==0){$_REQUEST['even02idcertificado']='';}
if (isset($_REQUEST['even02idcertificado_cod'])==0){$_REQUEST['even02idcertificado_cod']='';}
$even02idcertificado_nombre='';
if (isset($_REQUEST['even02idrubrica'])==0){$_REQUEST['even02idrubrica']='';}
if (isset($_REQUEST['even02idrubrica_cod'])==0){$_REQUEST['even02idrubrica_cod']='';}
$even02idrubrica_nombre='';
if (isset($_REQUEST['even02detalle'])==0){$_REQUEST['even02detalle']='';}
if ((int)$_REQUEST['paso']>0){
	//Cursos
	if (isset($_REQUEST['even03idcurso'])==0){$_REQUEST['even03idcurso']='';}
	if (isset($_REQUEST['even03idcurso_cod'])==0){$_REQUEST['even03idcurso_cod']='';}
	$even03idcurso_nombre='';
	if (isset($_REQUEST['even03id'])==0){$_REQUEST['even03id']='';}
	if (isset($_REQUEST['even03vigente'])==0){$_REQUEST['even03vigente']='S';}
	//Participantes
	if (isset($_REQUEST['even04idparticipante'])==0){$_REQUEST['even04idparticipante']=0;}//{$_SESSION['unad_id_tercero'];}
	if (isset($_REQUEST['even04idparticipante_td'])==0){$_REQUEST['even04idparticipante_td']=$APP->tipo_doc;}
	if (isset($_REQUEST['even04idparticipante_doc'])==0){$_REQUEST['even04idparticipante_doc']='';}
	if (isset($_REQUEST['even04id'])==0){$_REQUEST['even04id']='';}
	if (isset($_REQUEST['even04institucion'])==0){$_REQUEST['even04institucion']='';}
	if (isset($_REQUEST['even04cargo'])==0){$_REQUEST['even04cargo']='';}
	if (isset($_REQUEST['even04correo'])==0){$_REQUEST['even04correo']='';}
	if (isset($_REQUEST['even04telefono'])==0){$_REQUEST['even04telefono']='';}
	if (isset($_REQUEST['even04estadoasistencia'])==0){$_REQUEST['even04estadoasistencia']='';}
	//Noticias
	if (isset($_REQUEST['even05consec'])==0){$_REQUEST['even05consec']='';}
	if (isset($_REQUEST['even05id'])==0){$_REQUEST['even05id']='';}
	if (isset($_REQUEST['even05fecha'])==0){$_REQUEST['even05fecha']='';}//{fecha_hoy();}
	if (isset($_REQUEST['even05publicar'])==0){$_REQUEST['even05publicar']='';}
	if (isset($_REQUEST['even05idtercero'])==0){$_REQUEST['even05idtercero']=0;}//{$_SESSION['unad_id_tercero'];}
	if (isset($_REQUEST['even05idtercero_td'])==0){$_REQUEST['even05idtercero_td']=$APP->tipo_doc;}
	if (isset($_REQUEST['even05idtercero_doc'])==0){$_REQUEST['even05idtercero_doc']='';}
	if (isset($_REQUEST['even05noticia'])==0){$_REQUEST['even05noticia']='';}
	}
// Espacio para inicializar otras variables
if (isset($_REQUEST['csv_separa'])==0){$_REQUEST['csv_separa']=',';}
if (isset($_REQUEST['bnombre'])==0){$_REQUEST['bnombre']='';}
//if (isset($_REQUEST['blistar'])==0){$_REQUEST['blistar']='';}
if ((int)$_REQUEST['paso']>0){
	//Cursos
	if (isset($_REQUEST['bnombre1903'])==0){$_REQUEST['bnombre1903']='';}
	//if (isset($_REQUEST['blistar1903'])==0){$_REQUEST['blistar1903']='';}
	//Participantes
	if (isset($_REQUEST['bnombre1904'])==0){$_REQUEST['bnombre1904']='';}
	//if (isset($_REQUEST['blistar1904'])==0){$_REQUEST['blistar1904']='';}
	//Noticias
	if (isset($_REQUEST['bnombre1905'])==0){$_REQUEST['bnombre1905']='';}
	//if (isset($_REQUEST['blistar1905'])==0){$_REQUEST['blistar1905']='';}
	}
//Si Modifica o Elimina Cargar los campos
if (($_REQUEST['paso']==1)||($_REQUEST['paso']==3)){
	$_REQUEST['even02idorganizador_td']=$APP->tipo_doc;
	$_REQUEST['even02idorganizador_doc']='';
	$_REQUEST['even02idcertificado_cod']='';
	$_REQUEST['even02idrubrica_cod']='';
	if ($_REQUEST['paso']==1){
		$sSQLcondi='even02consec='.$_REQUEST['even02consec'].'';
		}else{
		$sSQLcondi='even02id='.$_REQUEST['even02id'].'';
		}
	$sSQL='SELECT * FROM even02evento WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$_REQUEST['even02consec']=$fila['even02consec'];
		$_REQUEST['even02id']=$fila['even02id'];
		$_REQUEST['even02tipo']=$fila['even02tipo'];
		$_REQUEST['even02categoria']=$fila['even02categoria'];
		$_REQUEST['even02estado']=$fila['even02estado'];
		$_REQUEST['even02publicado']=$fila['even02publicado'];
		$_REQUEST['even02nombre']=$fila['even02nombre'];
		$_REQUEST['even02idzona']=$fila['even02idzona'];
		$_REQUEST['even02idcead']=$fila['even02idcead'];
		$_REQUEST['even02peraca']=$fila['even02peraca'];
		$_REQUEST['even02lugar']=$fila['even02lugar'];
		$_REQUEST['even02inifecha']=$fila['even02inifecha'];
		$_REQUEST['even02inihora']=$fila['even02inihora'];
		$_REQUEST['even02iniminuto']=$fila['even02iniminuto'];
		$_REQUEST['even02finfecha']=$fila['even02finfecha'];
		$_REQUEST['even02finhora']=$fila['even02finhora'];
		$_REQUEST['even02finminuto']=$fila['even02finminuto'];
		$_REQUEST['even02idorganizador']=$fila['even02idorganizador'];
		$_REQUEST['even02contacto']=$fila['even02contacto'];
		$_REQUEST['even02insfechaini']=$fila['even02insfechaini'];
		$_REQUEST['even02insfechafin']=$fila['even02insfechafin'];
		$_REQUEST['even02idcertificado']=$fila['even02idcertificado'];
		$_REQUEST['even02idrubrica']=$fila['even02idrubrica'];
		$_REQUEST['even02detalle']=$fila['even02detalle'];
		$sSQL='SELECT even06consec, even06titulo FROM even06certificados WHERE even06id='.$_REQUEST['even02idcertificado'];
		$tabladet=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabladet)>0){
			$filadet=$objDB->sf($tabladet);
			$_REQUEST['even02idcertificado_cod']=$filadet['even06consec'];
			$even02idcertificado_nombre=$filadet['even06titulo'];
			}
		$sSQL='SELECT ,  FROM  WHERE ='.$_REQUEST['even02idrubrica'];
		$tabladet=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabladet)>0){
			$filadet=$objDB->sf($tabladet);
			$_REQUEST['even02idrubrica_cod']=$_REQUEST['even02idrubrica'];
			$even02idrubrica_nombre=$filadet[''];
			}
		$bcargo=true;
		$_REQUEST['paso']=2;
		$_REQUEST['boculta1902']=0;
		$bLimpiaHijos=true;
		}else{
		$_REQUEST['paso']=0;
		}
	}
//Insertar o modificar un elemento
if (($_REQUEST['paso']==10)||($_REQUEST['paso']==12)){
	$bMueveScroll=true;
	list($_REQUEST, $sError, $iTipoError, $sDebugGuardar)=f1902_db_GuardarV2($_REQUEST, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugGuardar;
	if ($sError==''){
		$sError='<b>'.$ETI['msg_itemguardado'].'</b>';
		$iTipoError=1;
		}
	}
// Cambio de consecutivo.
if ($_REQUEST['paso']==93){
	$_REQUEST['paso']=2;
	$_REQUEST['even02consec_nuevo']=numeros_validar($_REQUEST['even02consec_nuevo']);
	if ($_REQUEST['even02consec_nuevo']==''){$sError=$ERR['even02consec'];}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){
			$sError=$ERR['8'];
			}
		}
	if ($sError==''){
		//Ver que el consecutivo no exista.
		$sSQL='SELECT even02id FROM even02evento WHERE even02consec='.$_REQUEST['even02consec_nuevo'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sError='El consecutivo '.$_REQUEST['even02consec_nuevo'].' ya existe';
			}
		}
	if ($sError==''){
		//Aplicar el cambio.
		$sSQL='UPDATE even02evento SET even02consec='.$_REQUEST['even02consec_nuevo'].' WHERE even02id='.$_REQUEST['even02id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		$sDetalle='Cambia el consecutivo de '.$_REQUEST['even02consec'].' a '.$_REQUEST['even02consec_nuevo'].'';
		$_REQUEST['even02consec']=$_REQUEST['even02consec_nuevo'];
		$_REQUEST['even02consec_nuevo']='';
		seg_auditar($iCodModulo, $_SESSION['u_idtercero'], 8, $_REQUEST['even02id'], $sDetalle, $objDB);
		$sError='<b>Se ha aplicado el cambio de consecutivo.</b>';
		$iTipoError=1;
		}else{
		$iSector=93;
		}
	}
//Eliminar un elemento
if ($_REQUEST['paso']==13){
	$_REQUEST['paso']=2;
	list($sError, $iTipoError, $sDebugElimina)=f1902_db_Eliminar($_REQUEST['even02id'], $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	if ($sError==''){
		$_REQUEST['paso']=-1;
		$sError=$ETI['msg_itemeliminado'];
		$iTipoError=1;
		}
	}
//limpiar la pantalla
if ($_REQUEST['paso']==-1){
	$_REQUEST['even02consec']='';
	$_REQUEST['even02consec_nuevo']='';
	$_REQUEST['even02id']='';
	$_REQUEST['even02tipo']='';
	$_REQUEST['even02categoria']='';
	$_REQUEST['even02estado']=0;
	$_REQUEST['even02publicado']='';
	$_REQUEST['even02nombre']='';
	$_REQUEST['even02idzona']='';
	$_REQUEST['even02idcead']='';
	$_REQUEST['even02peraca']='';
	$_REQUEST['even02lugar']='';
	$_REQUEST['even02inifecha']='';//fecha_hoy();
	$_REQUEST['even02inihora']=fecha_hora();
	$_REQUEST['even02iniminuto']=fecha_minuto();
	$_REQUEST['even02finfecha']='';//fecha_hoy();
	$_REQUEST['even02finhora']=fecha_hora();
	$_REQUEST['even02finminuto']=fecha_minuto();
	$_REQUEST['even02idorganizador']=0;//$_SESSION['unad_id_tercero'];
	$_REQUEST['even02idorganizador_td']=$APP->tipo_doc;
	$_REQUEST['even02idorganizador_doc']='';
	$_REQUEST['even02contacto']='';
	$_REQUEST['even02insfechaini']='';//fecha_hoy();
	$_REQUEST['even02insfechafin']='';//fecha_hoy();
	$_REQUEST['even02idcertificado']=0;
	$_REQUEST['even02idcertificado_cod']='';
	$_REQUEST['even02idrubrica']=0;
	$_REQUEST['even02idrubrica_cod']='';
	$_REQUEST['even02detalle']='';
	$_REQUEST['paso']=0;
	}
if ($bLimpiaHijos){
	$_REQUEST['even03idevento']='';
	$_REQUEST['even03idcurso']=0;
	$_REQUEST['even03idcurso_cod']='';
	$_REQUEST['even03id']='';
	$_REQUEST['even03vigente']='S';
	$_REQUEST['even04idevento']='';
	$_REQUEST['even04idparticipante']=0;//$_SESSION['unad_id_tercero'];
	$_REQUEST['even04idparticipante_td']=$APP->tipo_doc;
	$_REQUEST['even04idparticipante_doc']='';
	$_REQUEST['even04id']='';
	$_REQUEST['even04institucion']='';
	$_REQUEST['even04cargo']='';
	$_REQUEST['even04correo']='';
	$_REQUEST['even04telefono']='';
	$_REQUEST['even04estadoasistencia']='';
	$_REQUEST['even05idevento']='';
	$_REQUEST['even05consec']='';
	$_REQUEST['even05id']='';
	$_REQUEST['even05fecha']=fecha_hoy();
	$_REQUEST['even05publicar']='';
	$_REQUEST['even05idtercero']=$_SESSION['unad_id_tercero'];
	$_REQUEST['even05idtercero_td']=$APP->tipo_doc;
	$_REQUEST['even05idtercero_doc']='';
	$_REQUEST['even05noticia']='';
	}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
//DATOS PARA COMPLETAR EL FORMULARIO
//Crear los controles que requieran llamado a base de datos
$objCombos=new clsHtmlCombos();
$objTercero=new clsHtmlTercero();
$html_even02categoria=f1902_HTMLComboV2_even02categoria($objDB, $objCombos, $_REQUEST['even02categoria'], $_REQUEST['even02tipo']);
list($even02estado_nombre, $sErrorDet)=tabla_campoxid('even14estadoevento','even14nombre','even14id',$_REQUEST['even02estado'],'{'.$ETI['msg_sindato'].'}', $objDB);
$html_even02estado=html_oculto('even02estado', $_REQUEST['even02estado'], $even02estado_nombre);
$objCombos->nuevo('even02publicado', $_REQUEST['even02publicado'], false);
$objCombos->sino();
$html_even02publicado=$objCombos->html('', $objDB);
$objCombos->nuevo('even02idzona', $_REQUEST['even02idzona'], true, '{'.$ETI['msg_seleccione'].'}');
$html_even02idzona=f1903_HTMLComboV2_even02idzona($objDB, $objCombos, $_REQUEST['even02idzona']);
$html_even02idcead=f1903_HTMLComboV2_even02idcead($objDB, $objCombos, $_REQUEST['even02idcead'], $_REQUEST['even02idzona']);
$objCombos->nuevo('even02peraca', $_REQUEST['even02peraca'], true, '{'.$ETI['msg_seleccione'].'}');
$sSQL='SELECT exte02id AS id, exte02nombre AS nombre FROM exte02per_aca ORDER BY exte02nombre';
$html_even02peraca=$objCombos->html($sSQL, $objDB);
list($even02idorganizador_rs, $_REQUEST['even02idorganizador'], $_REQUEST['even02idorganizador_td'], $_REQUEST['even02idorganizador_doc'])=html_tercero($_REQUEST['even02idorganizador_td'], $_REQUEST['even02idorganizador_doc'], $_REQUEST['even02idorganizador'], 0, $objDB);
list($_REQUEST['even02idcertificado'], $even02idcertificado_nombre, $sDebugBusca)=f1902_Busqueda_db_even02idcertificado($_REQUEST['even02idcertificado_cod'], $objDB, $bDebug);
$sDebug=$sDebug.$sDebugBusca;
list($_REQUEST['even02idrubrica'], $even02idrubrica_nombre, $sDebugBusca)=f1902_Busqueda_db_even02idrubrica($_REQUEST['even02idrubrica_cod'], $objDB, $bDebug);
$sDebug=$sDebug.$sDebugBusca;
if ((int)$_REQUEST['paso']==0){
	$objCombos->nuevo('even02tipo', $_REQUEST['even02tipo'], true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='carga_combo_even02categoria();';
	$sSQL='SELECT even01id AS id, even01nombre AS nombre FROM even01tipoevento ORDER BY even01nombre';
	$html_even02tipo=$objCombos->html($sSQL, $objDB);
	}else{
	list($even02tipo_nombre, $sErrorDet)=tabla_campoxid('even01tipoevento','even01nombre','even01id',$_REQUEST['even02tipo'],'{'.$ETI['msg_sindato'].'}', $objDB);
	$html_even02tipo=html_oculto('even02tipo', $_REQUEST['even02tipo'], $even02tipo_nombre);
	list($_REQUEST['even03idcurso'], $even03idcurso_nombre, $sDebugBusca)=f1903_Busqueda_db_even03idcurso($_REQUEST['even03idcurso_cod'], $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugBusca;
	list($even04idparticipante_rs, $_REQUEST['even04idparticipante'], $_REQUEST['even04idparticipante_td'], $_REQUEST['even04idparticipante_doc'])=html_tercero($_REQUEST['even04idparticipante_td'], $_REQUEST['even04idparticipante_doc'], $_REQUEST['even04idparticipante'], 0, $objDB);
	$objCombos->nuevo('even04estadoasistencia', $_REQUEST['even04estadoasistencia'], true, '{'.$ETI['msg_seleccione'].'}');
	$sSQL='SELECT even13id AS id, even13nombre AS nombre FROM even13estadoasistencia ORDER BY even13nombre';
	$html_even04estadoasistencia=$objCombos->html($sSQL, $objDB);
	$objCombos->nuevo('even05publicar', $_REQUEST['even05publicar'], false);
	$objCombos->sino();
	$html_even05publicar=$objCombos->html('', $objDB);
	list($even05idtercero_rs, $_REQUEST['even05idtercero'], $_REQUEST['even05idtercero_td'], $_REQUEST['even05idtercero_doc'])=html_tercero($_REQUEST['even05idtercero_td'], $_REQUEST['even05idtercero_doc'], $_REQUEST['even05idtercero'], 0, $objDB);
	}
//Alistar datos adicionales
$id_rpt=0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
/*
$objCombos->nuevo('blistar', $_REQUEST['blistar'], true, '{'.$ETI['msg_todos'].'}');
$objCombos->sAccion='paginarf1902()';
$html_blistar=$objCombos->html('', $objDB);
//$html_blistar=$objCombos->comboSistema(1902, 1, $objDB, 'paginarf1902()');
$objCombos->nuevo('blistar1903', $_REQUEST['blistar1903'], true, '{'.$ETI['msg_todos'].'}');
$html_blistar1903=$objCombos->comboSistema(1903, 1, $objDB, 'paginarf1903()');
$objCombos->nuevo('blistar1904', $_REQUEST['blistar1904'], true, '{'.$ETI['msg_todos'].'}');
$html_blistar1904=$objCombos->comboSistema(1904, 1, $objDB, 'paginarf1904()');
$objCombos->nuevo('blistar1905', $_REQUEST['blistar1905'], true, '{'.$ETI['msg_todos'].'}');
$html_blistar1905=$objCombos->comboSistema(1905, 1, $objDB, 'paginarf1905()');
*/
//Permisos adicionales
$seg_5=0;
$seg_6=0;
$seg_8=0;
if (seg_revisa_permiso($iCodModulo, 6, $objDB)){$seg_6=1;}
if ($seg_6==1){}
if (false){
	$objCombos->nuevo('csv_separa', $_REQUEST['csv_separa'], false);
	$objCombos->addItem(',', $ETI['msg_coma']);
	$objCombos->addItem(';', $ETI['msg_puntoycoma']);
	$csv_separa='<label class="Label90">'.$ETI['msg_separador'].'</label><label class="Label130">'.$objCombos->html('', $objDB).'</label>';
	}else{
	$csv_separa='<input id="csv_separa" name="csv_separa" type="hidden" value="," />';
	}
$iNumFormatosImprime=0;
$iModeloReporte=1902;
$html_iFormatoImprime='<input id="iformatoimprime" name="iformatoimprime" type="hidden" value="0" />
';
if ($_REQUEST['paso']>0){
	if (seg_revisa_permiso($iCodModulo, 5, $objDB)){
		$seg_5=1;
		}
	if (seg_revisa_permiso($iCodModulo, 8, $objDB)){$seg_8=1;}
	}
//Cargar las tablas de datos
$aParametros[0]='';//$_REQUEST['p1_1902'];
$aParametros[101]=$_REQUEST['paginaf1902'];
$aParametros[102]=$_REQUEST['lppf1902'];
//$aParametros[103]=$_REQUEST['bnombre'];
//$aParametros[104]=$_REQUEST['blistar'];
list($sTabla1902, $sDebugTabla)=f1902_TablaDetalleV2($aParametros, $objDB, $bDebug);
$sDebug=$sDebug.$sDebugTabla;
$sTabla1903='';
$sTabla1904='';
$sTabla1905='';
if ($_REQUEST['paso']!=0){
	//Cursos
	$aParametros1903[0]=$_REQUEST['even02id'];
	$aParametros1903[101]=$_REQUEST['paginaf1903'];
	$aParametros1903[102]=$_REQUEST['lppf1903'];
	//$aParametros1903[103]=$_REQUEST['bnombre1903'];
	//$aParametros1903[104]=$_REQUEST['blistar1903'];
	list($sTabla1903, $sDebugTabla)=f1903_TablaDetalleV2($aParametros1903, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	//Participantes
	$aParametros1904[0]=$_REQUEST['even02id'];
	$aParametros1904[101]=$_REQUEST['paginaf1904'];
	$aParametros1904[102]=$_REQUEST['lppf1904'];
	//$aParametros1904[103]=$_REQUEST['bnombre1904'];
	//$aParametros1904[104]=$_REQUEST['blistar1904'];
	list($sTabla1904, $sDebugTabla)=f1904_TablaDetalleV2($aParametros1904, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	//Noticias
	$aParametros1905[0]=$_REQUEST['even02id'];
	$aParametros1905[101]=$_REQUEST['paginaf1905'];
	$aParametros1905[102]=$_REQUEST['lppf1905'];
	//$aParametros1905[103]=$_REQUEST['bnombre1905'];
	//$aParametros1905[104]=$_REQUEST['blistar1905'];
	list($sTabla1905, $sDebugTabla)=f1905_TablaDetalleV2($aParametros1905, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	}
list($et_menu, $sDebugM)=html_menuV2($APP->idsistema, $objDB, $iPiel, $bDebug, $idTercero);
$sDebug=$sDebug.$sDebugM;
$objDB->CerrarConexion();
//FORMA
require $APP->rutacomun.'unad_forma_v2.php';
forma_cabeceraV3($xajax, $ETI['titulo_1902']);
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
	document.getElementById('div_sector93').style.display='none';
	document.getElementById('div_sector95').style.display='none';
	document.getElementById('div_sector96').style.display='none';
	document.getElementById('div_sector97').style.display='none';
	document.getElementById('div_sector98').style.display='none';
	document.getElementById('div_sector'+codigo).style.display='block';
	var sEst='none';
	if (codigo==1){sEst='block';}
	document.getElementById('cmdGuardarf').style.display=sEst;
	}
function ter_retorna(){
	var sRetorna=window.document.frmedita.div96v2.value;
	if (sRetorna!=''){
		var idcampo=window.document.frmedita.div96campo.value;
		var illave=window.document.frmedita.div96llave.value;
		var did=document.getElementById(idcampo);
		var dtd=document.getElementById(idcampo+'_td');
		var ddoc=document.getElementById(idcampo+'_doc');
		dtd.value=window.document.frmedita.div96v1.value;
		ddoc.value=sRetorna;
		did.value=window.document.frmedita.div96v3.value;
		ter_muestra(idcampo, illave);
		}
	MensajeAlarmaV2('', 0);
	retornacontrol();
	}
function ter_muestra(idcampo, illave){
	var params=new Array();
	params[1]=document.getElementById(idcampo+'_doc').value;
	if (params[1]!=''){
		params[0]=document.getElementById(idcampo+'_td').value;
		params[2]=idcampo;
		params[3]='div_'+idcampo;
		if (illave==1){params[4]='RevisaLlave';}
		//if (illave==1){params[5]='FuncionCuandoNoEsta';}
		xajax_unad11_Mostrar_v2(params);
		}else{
		document.getElementById(idcampo).value=0;
		document.getElementById('div_'+idcampo).innerHTML='&nbsp;';
		//FuncionCuandoNoHayNada
		}
	}
function ter_traerxid(idcampo, vrcampo){
	var params=new Array();
	params[0]=vrcampo;
	params[1]=idcampo;
	if (params[0]!=0){
		xajax_unad11_TraerXid(params);
		}
	}
function imprimelista(){
	if (window.document.frmedita.seg_6.value==1){
		window.document.frmlista.consulta.value=window.document.frmedita.consulta_1902.value;
		window.document.frmlista.titulos.value=window.document.frmedita.titulos_1902.value;
		window.document.frmlista.nombrearchivo.value='Eventos';
		window.document.frmlista.submit();
		}else{
		window.alert("<?php echo $ERR['6']; ?>");
		}
	}
function asignarvariables(){
	//window.document.frmimpp.v3.value=window.document.frmedita.bnombre.value;
	//window.document.frmimpp.v4.value=window.document.frmedita.bcodigo.value;
	//window.document.frmimpp.separa.value=window.document.frmedita.csv_separa.value.trim();
	}
function imprimeexcel(){
	if (window.document.frmedita.seg_6.value==1){
		asignarvariables();
		window.document.frmimpp.action='e1902.php';
		window.document.frmimpp.submit();
		}else{
		window.alert("<?php echo $ERR['6']; ?>");
		}
	}
function imprimep(){
	if (window.document.frmedita.seg_5.value==1){
		asignarvariables();
		window.document.frmimpp.action='p1902.php';
		window.document.frmimpp.submit();
<?php
if ($iNumFormatosImprime>0){
?>
		expandesector(1);
<?php
	}
?>
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
	datos[1]=window.document.frmedita.even02consec.value;
	if ((datos[1]!='')){
		xajax_f1902_ExisteDato(datos);
		}
	}
function cargadato(llave1){
	window.document.frmedita.even02consec.value=String(llave1);
	window.document.frmedita.paso.value=1;
	window.document.frmedita.submit();
	}
function cargaridf1902(llave1){
	window.document.frmedita.even02id.value=String(llave1);
	window.document.frmedita.paso.value=3;
	window.document.frmedita.submit();
	}
function carga_combo_even02categoria(){
	var params=new Array();
	params[0]=window.document.frmedita.even02tipo.value;
	xajax_f1902_Comboeven02categoria(params);
	}
function carga_combo_even02idcead(){
	var params=new Array();
	params[0]=window.document.frmedita.even02idzona.value;
	xajax_f1903_Comboeven02idcead(params);
	}
function cod_even02idcertificado(){
	var dcod=window.document.frmedita.even02idcertificado_cod.value.trim();
	window.document.frmedita.even02idcertificado.value=0;
	if (dcod!=''){
		var params=new Array();
		params[0]=dcod;
		params[1]='even02idcertificado';
		params[2]='div_even02idcertificado';
		params[9]=window.document.frmedita.debug.value;
		xajax_f1902_Busqueda_even02idcertificado(params);
		}else{
		document.getElementById('div_even02idcertificado').innerHTML='';
		}
	}
function cod_even02idrubrica(){
	var dcod=window.document.frmedita.even02idrubrica_cod.value.trim();
	window.document.frmedita.even02idrubrica.value=0;
	if (dcod!=''){
		var params=new Array();
		params[0]=dcod;
		params[1]='even02idrubrica';
		params[2]='div_even02idrubrica';
		params[9]=window.document.frmedita.debug.value;
		xajax_f1902_Busqueda_even02idrubrica(params);
		}else{
		document.getElementById('div_even02idrubrica').innerHTML='';
		}
	}
function paginarf1902(){
	var params=new Array();
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf1902.value;
	params[102]=window.document.frmedita.lppf1902.value;
	//params[103]=window.document.frmedita.bnombre.value;
	//params[104]=window.document.frmedita.blistar.value;
	//document.getElementById('div_f1902detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf1902" name="paginaf1902" type="hidden" value="'+params[101]+'" /><input id="lppf1902" name="lppf1902" type="hidden" value="'+params[102]+'" />';
	xajax_f1902_HtmlTabla(params);
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
	document.getElementById("even02consec").focus();
	}
function buscarV2016(sCampo){
	window.document.frmedita.iscroll.value=window.pageYOffset;
	expandesector(98);
	window.document.frmedita.scampobusca.value=sCampo;
	var params=new Array();
	params[1]=sCampo;
	//params[2]=window.document.frmedita.iagno.value;
	//params[3]=window.document.frmedita.itipo.value;
	xajax_f1902_Busquedas(params);
	}
function retornacontrol(){
	expandesector(1);
	window.scrollTo(0, window.document.frmedita.iscroll.value);
	}
function Devuelve(sValor){
	var sCampo=window.document.frmedita.scampobusca.value;
	if (sCampo=='even02idorganizador'){
		ter_traerxid('even02idorganizador', sValor);
		}
	if (sCampo=='even02idcertificado'){
		window.document.frmedita.even02idcertificado_cod.value=sValor;
		cod_even02idcertificado();
		}
	if (sCampo=='even02idrubrica'){
		window.document.frmedita.even02idrubrica_cod.value=sValor;
		cod_even02idrubrica();
		}
	if (sCampo=='even03idcurso'){
		window.document.frmedita.even03idcurso_cod.value=sValor;
		cod_even03idcurso();
		}
	if (sCampo=='even04idparticipante'){
		ter_traerxid('even04idparticipante', sValor);
		}
	if (sCampo=='even05idtercero'){
		ter_traerxid('even05idtercero', sValor);
		}
	retornacontrol();
	}
function mantener_sesion(){xajax_sesion_mantenerV4();}
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
function mod_consec(){
	if (confirm("Esta seguro de cambiar el consecutivo?")){
		expandesector(98);
		window.document.frmedita.paso.value=93;
		window.document.frmedita.submit();
		}
	}
function carga_combo_even02idzona(){
    var params=new Array();
    params[0]=window.document.frmedita.even02idzona.value;
    xajax_f1903_Comboeven02idzona(params);
}
// -->
</script>
<?php
if ($_REQUEST['paso']!=0){
?>
<script language="javascript" src="jsi/js1903.js"></script>
<script language="javascript" src="jsi/js1904.js"></script>
<script language="javascript" src="jsi/js1905.js"></script>
<?php
	}
?>
<?php
if ($_REQUEST['paso']!=0){
?>
<form id="frmimpp" name="frmimpp" method="post" action="p1902.php" target="_blank">
<input id="r" name="r" type="hidden" value="1902" />
<input id="id1902" name="id1902" type="hidden" value="<?php echo $_REQUEST['even02id']; ?>" />
<input id="v3" name="v3" type="hidden" value="" />
<input id="v4" name="v4" type="hidden" value="" />
<input id="v5" name="v5" type="hidden" value="" />
<input id="iformato94" name="iformato94" type="hidden" value="0" />
<input id="separa" name="separa" type="hidden" value="," />
<input id="rdebug" name="rdebug" type="hidden" value="<?php echo $_REQUEST['debug']; ?>"/>
<input id="clave" name="clave" type="hidden" value="" />
</form>
<?php
	}
?>
<form id="frmlista" name="frmlista" method="post" action="listados.php" target="_blank">
<input id="titulos" name="titulos" type="hidden" value="" />
<input id="consulta" name="consulta" type="hidden" value="" />
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
if ($_REQUEST['paso']==2){
?>
<input id="cmdEliminar" name="cmdEliminar" type="button" class="btUpEliminar" onclick="eliminadato();" title="<?php echo $ETI['bt_eliminar']; ?>" value="<?php echo $ETI['bt_eliminar']; ?>"/>
<?php
	}
$bHayImprimir=false;
$sScript='imprimelista()';
$sClaseBoton='btEnviarExcel';
if ($seg_6==1){$bHayImprimir=true;}
if ($_REQUEST['paso']!=0){
	if ($seg_5==1){
		//$bHayImprimir=true;
		//$sScript='imprimep()';
		//if ($iNumFormatosImprime>0){
			//$sScript='expandesector(94)';
			//}
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
<?php
?>
<input id="cmdGuardar" name="cmdGuardar" type="button" class="btUpGuardar" onclick="enviaguardar();" title="<?php echo $ETI['bt_guardar']; ?>" value="<?php echo $ETI['bt_guardar']; ?>"/>
<?php
if (false){
?>
<input id="cmdAnular" name="cmdAnular" type="button" class="btSupAnular" onclick="expandesector(2);" title="<?php echo $ETI['bt_anular']; ?>" value="<?php echo $ETI['bt_anular']; ?>"/>
<?php
	}
?>
</div>
<div class="titulosI">
<?php
echo '<h2>'.$ETI['titulo_1902'].'</h2>';
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
<input id="boculta1902" name="boculta1902" type="hidden" value="<?php echo $_REQUEST['boculta1902']; ?>" />
<label class="Label30">
<input id="btexpande1902" name="btexpande1902" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(1902,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta1902']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge1902" name="btrecoge1902" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(1902,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta1902']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div id="div_p1902" style="display:<?php if ($_REQUEST['boculta1902']==0){echo 'block'; }else{echo 'none';} ?>;">
<?php
	}
//Mostrar formulario para editar
?>
<label class="Label90">
<?php
echo $ETI['even02consec'];
?>
</label>
<label class="Label90">
<?php
if ($_REQUEST['paso']!=2){
?>
<input id="even02consec" name="even02consec" type="text" value="<?php echo $_REQUEST['even02consec']; ?>" onchange="RevisaLlave()" class="cuatro"/>
<?php
	}else{
	echo html_oculto('even02consec', $_REQUEST['even02consec']);
	}
?>
</label>
<?php
/*
if ($seg_8==1){
	$objForma=new clsHtmlForma($iPiel);
	echo $objForma->htmlBotonSolo('cmdCambiaConsec', 'btMiniActualizar', 'expandesector(93);', $ETI['bt_cambiar'], 30);
	echo '<label class="Label30">&nbsp;</label>';
	}
*/
?>
<label class="Label60">
<?php
echo $ETI['even02id'];
?>
</label>
<label class="Label60">
<?php
	echo html_oculto('even02id', $_REQUEST['even02id']);
?>
</label>
<label class="Label130">
<?php
echo $ETI['even02tipo'];
?>
</label>
<label>
<?php
echo $html_even02tipo;
?>
</label>
<label class="Label130">
<?php
echo $ETI['even02categoria'];
?>
</label>
<label>
<div id="div_even02categoria">
<?php
echo $html_even02categoria;
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['even02estado'];
?>
</label>
<label>
<div id="div_even02estado">
<?php
echo $html_even02estado;
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['even02publicado'];
?>
</label>
<label class="Label130">
<?php
echo $html_even02publicado;
?>
</label>
<label class="L">
<?php
echo $ETI['even02nombre'];
?>

<input id="even02nombre" name="even02nombre" type="text" value="<?php echo $_REQUEST['even02nombre']; ?>" maxlength="100" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['even02nombre']; ?>"/>
</label>
<label class="Label130">
<?php
echo $ETI['even02idzona'];
?>
</label>
<label>
<?php
echo $html_even02idzona;
?>
</label>
<label class="Label130">
<?php
echo $ETI['even02idcead'];
?>
</label>
<label>
<div id="div_even02idcead">
<?php
echo $html_even02idcead;
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['even02peraca'];
?>
</label>
<label>
<?php
echo $html_even02peraca;
?>
</label>
<label class="L">
<?php
echo $ETI['even02lugar'];
?>

<input id="even02lugar" name="even02lugar" type="text" value="<?php echo $_REQUEST['even02lugar']; ?>" maxlength="250" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['even02lugar']; ?>"/>
</label>
<label class="Label130">
<?php
echo $ETI['even02inifecha'];
?>
</label>
<div class="Campo220">
<?php
echo html_fecha('even02inifecha', $_REQUEST['even02inifecha']);//$bvacio=false,$accion=",$iagnoini=0,$iagnofin=0
?>
</div>
<!--
<label class="Label30">
<input id="beven02inifecha_hoy" name="beven02inifecha_hoy" type="button" value="Hoy" class="btMiniHoy" onclick="fecha_asignar('even02inifecha','<?php echo fecha_hoy(); ?>')" title="<?php echo $ETI['bt_hoy']; ?>"/>
</label>
-->
<label class="Label130">
<?php
echo $ETI['even02inihora'];
?>
</label>
<div class="campo_HoraMin" id="div_even02inihora">
<?php
echo html_HoraMin('even02inihora', $_REQUEST['even02inihora'], 'even02iniminuto', $_REQUEST['even02iniminuto']);
?>
</div>
<label class="Label130">
<?php
echo $ETI['even02finfecha'];
?>
</label>
<div class="Campo220">
<?php
echo html_fecha('even02finfecha', $_REQUEST['even02finfecha']);//$bvacio=false,$accion=",$iagnoini=0,$iagnofin=0
?>
</div>
<!--
<label class="Label30">
<input id="beven02finfecha_hoy" name="beven02finfecha_hoy" type="button" value="Hoy" class="btMiniHoy" onclick="fecha_asignar('even02finfecha','<?php echo fecha_hoy(); ?>')" title="<?php echo $ETI['bt_hoy']; ?>"/>
</label>
-->
<label class="Label130">
<?php
echo $ETI['even02finhora'];
?>
</label>
<div class="campo_HoraMin" id="div_even02finhora">
<?php
echo html_HoraMin('even02finhora', $_REQUEST['even02finhora'], 'even02finminuto', $_REQUEST['even02finminuto']);
?>
</div>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['even02idorganizador'];
?>
</label>
<div class="salto1px"></div>
<input id="even02idorganizador" name="even02idorganizador" type="hidden" value="<?php echo $_REQUEST['even02idorganizador']; ?>"/>
<div id="div_even02idorganizador_llaves">
<?php
$bOculto=false;
echo html_DivTerceroV2('even02idorganizador', $_REQUEST['even02idorganizador_td'], $_REQUEST['even02idorganizador_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_even02idorganizador" class="L"><?php echo $even02idorganizador_rs; ?></div>
<div class="salto1px"></div>
</div>
<label class="L">
<?php
echo $ETI['even02contacto'];
?>

<input id="even02contacto" name="even02contacto" type="text" value="<?php echo $_REQUEST['even02contacto']; ?>" maxlength="250" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['even02contacto']; ?>"/>
</label>
<label class="Label130">
<?php
echo $ETI['even02insfechaini'];
?>
</label>
<div class="Campo220">
<?php
echo html_fecha('even02insfechaini', $_REQUEST['even02insfechaini']);//$bvacio=false,$accion=",$iagnoini=0,$iagnofin=0
?>
</div>
<!--
<label class="Label30">
<input id="beven02insfechaini_hoy" name="beven02insfechaini_hoy" type="button" value="Hoy" class="btMiniHoy" onclick="fecha_asignar('even02insfechaini','<?php echo fecha_hoy(); ?>')" title="<?php echo $ETI['bt_hoy']; ?>"/>
</label>
-->
<label class="Label130">
<?php
echo $ETI['even02insfechafin'];
?>
</label>
<div class="Campo220">
<?php
echo html_fecha('even02insfechafin', $_REQUEST['even02insfechafin']);//$bvacio=false,$accion=",$iagnoini=0,$iagnofin=0
?>
</div>
<!--
<label class="Label30">
<input id="beven02insfechafin_hoy" name="beven02insfechafin_hoy" type="button" value="Hoy" class="btMiniHoy" onclick="fecha_asignar('even02insfechafin','<?php echo fecha_hoy(); ?>')" title="<?php echo $ETI['bt_hoy']; ?>"/>
</label>
-->
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['even02idcertificado'];
?>
</label>
<div class="salto1px"></div>
<input id="even02idcertificado" name="even02idcertificado" type="hidden" value="<?php echo $_REQUEST['even02idcertificado']; ?>"/>
<label class="Label200">
<input id="even02idcertificado_cod" name="even02idcertificado_cod" type="text" value="<?php echo $_REQUEST['even02idcertificado_cod']; ?>" class="veinte" onchange="cod_even02idcertificado()" maxlength="50" placeholder="<?php echo $ETI['ing_campo'].$ETI['even02idcertificado']; ?>"/>
</label>
<label class="Label30">
<input id="beven02idcertificado" name="beven02idcertificado" type="button" value="<?php echo $ETI['bt_buscar']; ?>" class="btMiniBuscar" onclick="buscarV2016('even02idcertificado')" title="<?php echo $ETI['bt_buscar']; ?>"/>
</label>
<div class="salto1px"></div>
<div id="div_even02idcertificado" class="L"><?php echo $even02idcertificado_nombre; ?></div>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['even02idrubrica'];
?>
</label>
<div class="salto1px"></div>
<input id="even02idrubrica" name="even02idrubrica" type="hidden" value="<?php echo $_REQUEST['even02idrubrica']; ?>"/>
<label class="Label200">
<input id="even02idrubrica_cod" name="even02idrubrica_cod" type="text" value="<?php echo $_REQUEST['even02idrubrica_cod']; ?>" class="veinte" onchange="cod_even02idrubrica()" maxlength="50" placeholder="<?php echo $ETI['ing_campo'].$ETI['even02idrubrica']; ?>"/>
</label>
<label class="Label30">
<input id="beven02idrubrica" name="beven02idrubrica" type="button" value="<?php echo $ETI['bt_buscar']; ?>" class="btMiniBuscar" onclick="buscarV2016('even02idrubrica')" title="<?php echo $ETI['bt_buscar']; ?>"/>
</label>
<div class="salto1px"></div>
<div id="div_even02idrubrica" class="L"><?php echo $even02idrubrica_nombre; ?></div>
<div class="salto1px"></div>
</div>
<label class="txtAreaS">
<?php
echo $ETI['even02detalle'];
?>
<textarea id="even02detalle" name="even02detalle" placeholder="<?php echo $ETI['ing_campo'].$ETI['even02detalle']; ?>"><?php echo $_REQUEST['even02detalle']; ?></textarea>
</label>
<?php
// -- Inicia Grupo campos 1903 Cursos
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_1903'];
?>
</label>
<input id="boculta1903" name="boculta1903" type="hidden" value="<?php echo $_REQUEST['boculta1903']; ?>" />
<?php
if ($_REQUEST['paso']==2){
	//if ($bCondicion){
?>
<div class="ir_derecha" style="width:62px;">
<!--
<label class="Label30">
<input id="btexcel1903" name="btexcel1903" type="button" value="Exportar" class="btMiniExcel" onclick="imprime1903();" title="Exportar"/>
</label>
-->
<label class="Label30">
<input id="btexpande1903" name="btexpande1903" type="button" value="Expandir" class="btMiniExpandir" onclick="expandepanel(1903,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta1903']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge1903" name="btrecoge1903" type="button" value="Recoger" class="btMiniRecoger" onclick="expandepanel(1903,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta1903']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div class="salto1px"></div>
<div id="div_p1903" style="display:<?php if ($_REQUEST['boculta1903']==0){echo 'block'; }else{echo 'none';} ?>;">
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['even03idcurso'];
?>
</label>
<div class="salto1px"></div>
<input id="even03idcurso" name="even03idcurso" type="hidden" value="<?php echo $_REQUEST['even03idcurso']; ?>"/>
<label class="Label200"><div id="div_even03idcurso_cod">
<?php
$sEstiloBoton='block';
if ((int)$_REQUEST['even03id']==0){
?>
<input id="even03idcurso_cod" name="even03idcurso_cod" type="text" value="<?php echo $_REQUEST['even03idcurso_cod']; ?>" class="veinte" onchange="cod_even03idcurso()" maxlength="50" placeholder="<?php echo $ETI['ing_campo'].$ETI['even03idcurso']; ?>"/>
<?php
	}else{
	$sEstiloBoton='none';
	echo html_oculto('even03idcurso_cod', $_REQUEST['even03idcurso_cod']);
	}
?>
</div></label>
<label class="Label30">
<input id="beven03idcurso" name="beven03idcurso" type="button" value="<?php echo $ETI['bt_buscar']; ?>" class="btMiniBuscar" onclick="buscarV2016('even03idcurso')" title="<?php echo $ETI['bt_buscar']; ?>" style="display:<?php echo $sEstiloBoton; ?>"/>
</label>
<div class="salto1px"></div>
<div id="div_even03idcurso" class="L"><?php echo $even03idcurso_nombre; ?></div>
<div class="salto1px"></div>
</div>
<label class="Label60">
<?php
echo $ETI['even03id'];
?>
</label>
<label class="Label60"><div id="div_even03id">
<?php
	echo html_oculto('even03id', $_REQUEST['even03id']);
?>
</div></label>
<label class="Label130">
<?php
echo $ETI['even03vigente'];
?>
</label>
<label>
<input id="even03vigente" name="even03vigente" type="text" value="<?php echo $_REQUEST['even03vigente']; ?>" maxlength="1" placeholder="<?php echo $ETI['ing_campo'].$ETI['even03vigente']; ?>"/>
</label>
<div class="salto1px"></div>
<label class="Label130">&nbsp;</label>
<label class="Label30">
<input id="bguarda1903" name="bguarda1903" type="button" value="Guardar" class="btMiniGuardar" onclick="guardaf1903()" title="<?php echo $ETI['bt_mini_guardar_1903']; ?>"/>
</label>
<label class="Label30">
<input id="blimpia1903" name="blimpia1903" type="button" value="Limpiar" class="btMiniLimpiar" onclick="limpiaf1903()" title="<?php echo $ETI['bt_mini_limpiar_1903']; ?>"/>
</label>
<label class="Label30">
<input id="belimina1903" name="belimina1903" type="button" value="Eliminar" class="btMiniEliminar" onclick="eliminaf1903()" title="<?php echo $ETI['bt_mini_eliminar_1903']; ?>" style="display:<?php if ((int)$_REQUEST['even03id']!=0){echo 'block';}else{echo 'none';} ?>;"/>
</label>
<?php
//Este es el cierre del div_p1903
?>
<div class="salto1px"></div>
</div>
<?php
		//} //Termina el segundo bloque  condicional - bloque editar.
?>
<div class="salto1px"></div>
<?php
if (false){
?>
<div class="ir_derecha">
<label class="Label130">
<?php
echo $ETI['msg_nombre'];
?>
</label>
<label>
<input id="bnombre1903" name="bnombre1903" type="text" value="<?php echo $_REQUEST['bnombre1903']; ?>" onchange="paginarf1903()"/>
</label>
<label class="Label130">
<?php
echo $ETI['msg_Listar'];
?>
</label>
<label>
<?php
echo $html_blistar1903;
?>
</label>
<div class="salto1px"></div>
</div>
<?php
	}
?>
<div id="div_f1903detalle">
<?php
echo $sTabla1903;
?>
</div>
<?php
	}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 1903 Cursos
?>
<?php
// -- Inicia Grupo campos 1904 Participantes
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_1904'];
?>
</label>
<input id="boculta1904" name="boculta1904" type="hidden" value="<?php echo $_REQUEST['boculta1904']; ?>" />
<?php
if ($_REQUEST['paso']==2){
	//if ($bCondicion){
?>
<div class="ir_derecha" style="width:62px;">
<!--
<label class="Label30">
<input id="btexcel1904" name="btexcel1904" type="button" value="Exportar" class="btMiniExcel" onclick="imprime1904();" title="Exportar"/>
</label>
-->
<label class="Label30">
<input id="btexpande1904" name="btexpande1904" type="button" value="Expandir" class="btMiniExpandir" onclick="expandepanel(1904,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta1904']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge1904" name="btrecoge1904" type="button" value="Recoger" class="btMiniRecoger" onclick="expandepanel(1904,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta1904']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div class="salto1px"></div>
<div id="div_p1904" style="display:<?php if ($_REQUEST['boculta1904']==0){echo 'block'; }else{echo 'none';} ?>;">
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['even04idparticipante'];
?>
</label>
<div class="salto1px"></div>
<input id="even04idparticipante" name="even04idparticipante" type="hidden" value="<?php echo $_REQUEST['even04idparticipante']; ?>"/>
<div id="div_even04idparticipante_llaves">
<?php
$bOculto=true;
if ((int)$_REQUEST['even04id']==0){$bOculto=false;}
echo html_DivTerceroV2('even04idparticipante', $_REQUEST['even04idparticipante_td'], $_REQUEST['even04idparticipante_doc'], $bOculto, 1, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_even04idparticipante" class="L"><?php echo $even04idparticipante_rs; ?></div>
<div class="salto1px"></div>
</div>
<label class="Label60">
<?php
echo $ETI['even04id'];
?>
</label>
<label class="Label60"><div id="div_even04id">
<?php
	echo html_oculto('even04id', $_REQUEST['even04id']);
?>
</div></label>
<label class="L">
<?php
echo $ETI['even04institucion'];
?>

<input id="even04institucion" name="even04institucion" type="text" value="<?php echo $_REQUEST['even04institucion']; ?>" maxlength="250" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['even04institucion']; ?>"/>
</label>
<label class="L">
<?php
echo $ETI['even04cargo'];
?>

<input id="even04cargo" name="even04cargo" type="text" value="<?php echo $_REQUEST['even04cargo']; ?>" maxlength="100" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['even04cargo']; ?>"/>
</label>
<label class="L">
<?php
echo $ETI['even04correo'];
?>

<input id="even04correo" name="even04correo" type="text" value="<?php echo $_REQUEST['even04correo']; ?>" maxlength="100" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['even04correo']; ?>"/>
</label>
<label class="L">
<?php
echo $ETI['even04telefono'];
?>

<input id="even04telefono" name="even04telefono" type="text" value="<?php echo $_REQUEST['even04telefono']; ?>" maxlength="100" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['even04telefono']; ?>"/>
</label>
<label class="Label130">
<?php
echo $ETI['even04estadoasistencia'];
?>
</label>
<label>
<?php
echo $html_even04estadoasistencia;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">&nbsp;</label>
<label class="Label30">
<input id="bguarda1904" name="bguarda1904" type="button" value="Guardar" class="btMiniGuardar" onclick="guardaf1904()" title="<?php echo $ETI['bt_mini_guardar_1904']; ?>"/>
</label>
<label class="Label30">
<input id="blimpia1904" name="blimpia1904" type="button" value="Limpiar" class="btMiniLimpiar" onclick="limpiaf1904()" title="<?php echo $ETI['bt_mini_limpiar_1904']; ?>"/>
</label>
<label class="Label30">
<input id="belimina1904" name="belimina1904" type="button" value="Eliminar" class="btMiniEliminar" onclick="eliminaf1904()" title="<?php echo $ETI['bt_mini_eliminar_1904']; ?>" style="display:<?php if ((int)$_REQUEST['even04id']!=0){echo 'block';}else{echo 'none';} ?>;"/>
</label>
<?php
//Este es el cierre del div_p1904
?>
<div class="salto1px"></div>
</div>
<?php
		//} //Termina el segundo bloque  condicional - bloque editar.
?>
<div class="salto1px"></div>
<?php
if (false){
?>
<div class="ir_derecha">
<label class="Label130">
<?php
echo $ETI['msg_nombre'];
?>
</label>
<label>
<input id="bnombre1904" name="bnombre1904" type="text" value="<?php echo $_REQUEST['bnombre1904']; ?>" onchange="paginarf1904()"/>
</label>
<label class="Label130">
<?php
echo $ETI['msg_Listar'];
?>
</label>
<label>
<?php
echo $html_blistar1904;
?>
</label>
<div class="salto1px"></div>
</div>
<?php
	}
?>
<div id="div_f1904detalle">
<?php
echo $sTabla1904;
?>
</div>
<?php
	}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 1904 Participantes
?>
<?php
// -- Inicia Grupo campos 1905 Noticias
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_1905'];
?>
</label>
<input id="boculta1905" name="boculta1905" type="hidden" value="<?php echo $_REQUEST['boculta1905']; ?>" />
<?php
if ($_REQUEST['paso']==2){
	//if ($bCondicion){
?>
<div class="ir_derecha" style="width:62px;">
<!--
<label class="Label30">
<input id="btexcel1905" name="btexcel1905" type="button" value="Exportar" class="btMiniExcel" onclick="imprime1905();" title="Exportar"/>
</label>
-->
<label class="Label30">
<input id="btexpande1905" name="btexpande1905" type="button" value="Expandir" class="btMiniExpandir" onclick="expandepanel(1905,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta1905']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge1905" name="btrecoge1905" type="button" value="Recoger" class="btMiniRecoger" onclick="expandepanel(1905,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta1905']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div class="salto1px"></div>
<div id="div_p1905" style="display:<?php if ($_REQUEST['boculta1905']==0){echo 'block'; }else{echo 'none';} ?>;">
<label class="Label90">
<?php
echo $ETI['even05consec'];
?>
</label>
<label class="Label90"><div id="div_even05consec">
<?php
if ((int)$_REQUEST['even05id']==0){
?>
<input id="even05consec" name="even05consec" type="text" value="<?php echo $_REQUEST['even05consec']; ?>" onchange="revisaf1905()" class="cuatro"/>
<?php
	}else{
	echo html_oculto('even05consec', $_REQUEST['even05consec']);
	}
?>
</div></label>
<label class="Label60">
<?php
echo $ETI['even05id'];
?>
</label>
<label class="Label60"><div id="div_even05id">
<?php
	echo html_oculto('even05id', $_REQUEST['even05id']);
?>
</div></label>
<label class="Label130">
<?php
echo $ETI['even05fecha'];
?>
</label>
<div class="Campo220" id="div_even05fecha">
<?php
echo html_oculto('even05fecha', $_REQUEST['even05fecha']);//, formato_fechalarga($_REQUEST['even05fecha']));
?>
</div>
<label class="Label130">
<?php
echo $ETI['even05publicar'];
?>
</label>
<label class="Label130">
<?php
echo $html_even05publicar;
?>
</label>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['even05idtercero'];
?>
</label>
<div class="salto1px"></div>
<input id="even05idtercero" name="even05idtercero" type="hidden" value="<?php echo $_REQUEST['even05idtercero']; ?>"/>
<div id="div_even05idtercero_llaves">
<?php
$bOculto=true;
echo html_DivTerceroV2('even05idtercero', $_REQUEST['even05idtercero_td'], $_REQUEST['even05idtercero_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_even05idtercero" class="L"><?php echo $even05idtercero_rs; ?></div>
<div class="salto1px"></div>
</div>
<label class="txtAreaS">
<?php
echo $ETI['even05noticia'];
?>
<textarea id="even05noticia" name="even05noticia" placeholder="<?php echo $ETI['ing_campo'].$ETI['even05noticia']; ?>"><?php echo $_REQUEST['even05noticia']; ?></textarea>
</label>
<div class="salto1px"></div>
<label class="Label130">&nbsp;</label>
<label class="Label30">
<input id="bguarda1905" name="bguarda1905" type="button" value="Guardar" class="btMiniGuardar" onclick="guardaf1905()" title="<?php echo $ETI['bt_mini_guardar_1905']; ?>"/>
</label>
<label class="Label30">
<input id="blimpia1905" name="blimpia1905" type="button" value="Limpiar" class="btMiniLimpiar" onclick="limpiaf1905()" title="<?php echo $ETI['bt_mini_limpiar_1905']; ?>"/>
</label>
<label class="Label30">
<input id="belimina1905" name="belimina1905" type="button" value="Eliminar" class="btMiniEliminar" onclick="eliminaf1905()" title="<?php echo $ETI['bt_mini_eliminar_1905']; ?>" style="display:<?php if ((int)$_REQUEST['even05id']!=0){echo 'block';}else{echo 'none';} ?>;"/>
</label>
<?php
//Este es el cierre del div_p1905
?>
<div class="salto1px"></div>
</div>
<?php
		//} //Termina el segundo bloque  condicional - bloque editar.
?>
<div class="salto1px"></div>
<?php
if (false){
?>
<div class="ir_derecha">
<label class="Label130">
<?php
echo $ETI['msg_nombre'];
?>
</label>
<label>
<input id="bnombre1905" name="bnombre1905" type="text" value="<?php echo $_REQUEST['bnombre1905']; ?>" onchange="paginarf1905()"/>
</label>
<label class="Label130">
<?php
echo $ETI['msg_Listar'];
?>
</label>
<label>
<?php
echo $html_blistar1905;
?>
</label>
<div class="salto1px"></div>
</div>
<?php
	}
?>
<div id="div_f1905detalle">
<?php
echo $sTabla1905;
?>
</div>
<?php
	}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 1905 Noticias
?>
<?php
if (false){
	//Ejemplo de boton de ayuda
	//echo html_BotonAyuda('NombreCampo');
	//echo html_DivAyudaLocal('NombreCampo');
	}
if ($bconexpande){
	//Este es el cierre del div_p1902
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
<?php
if (false){
?>
<div class="ir_derecha">
<label class="Label90">
<?php
echo $ETI['msg_bnombre'];
?>
</label>
<label>
<input id="bnombre" name="bnombre" type="text" value="<?php echo $_REQUEST['bnombre']; ?>" onchange="paginarf1902()" autocomplete="off"/>
</label>
<label class="Label90">
<?php
echo $ETI['msg_blistar'];
?>
</label>
<label class="Label130">
<?php
echo $html_blistar;
?>
</label>
</div>
<div class="salto1px"></div>
<?php
	}
?>
<?php
echo ' '.$csv_separa;
?>
<div id="div_f1902detalle">
<?php
echo $sTabla1902;
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
echo '<h2>'.$ETI['titulo_sector2'].'</h2>';
?>
</div>
</div>
<div id="cargaForm">
<div id="area">
</div><!-- /div_area -->
</div><!-- /DIV_cargaForm -->
</div><!-- /DIV_Sector2 -->


<div id="div_sector93" style="display:none">
<?php
$objForma=new clsHtmlForma($iPiel);
$objForma->addBoton('cmdAyuda93', 'btSupAyuda', 'muestraayuda('.$iCodModulo.');', $ETI['bt_ayuda']);
$objForma->addBoton('cmdVolverSec93', 'btSupVolver', 'expandesector(1);', $ETI['bt_volver']);
echo $objForma->htmlTitulo(''.$ETI['titulo_sector93'].'', $iCodModulo);
echo $objForma->htmlInicioMarco();
?>
<label class="Label160">
<?php
echo $ETI['msg_even02consec'];
?>
</label>
<label class="Label90">
<?php
echo '<b>'.$_REQUEST['even02consec'].'</b>';
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['msg_even02consec_nuevo'];
// onchange="RevisaConsec()"
?>
</label>
<label class="Label90">
<input id="even02consec_nuevo" name="even02consec_nuevo" type="text" value="<?php echo $_REQUEST['even02consec_nuevo']; ?>" class="cuatro"/>
</label>
<div class="salto1px"></div>
<label class="Label160">&nbsp;</label>
<?php
echo $objForma->htmlBotonSolo('cmdCambiaConsecFinal', 'botonProceso', 'mod_consec();', $ETI['bt_cambiar'], 130);
echo $objForma->htmlFinMarco();
?>
</div><!-- /DIV_Sector93 -->


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
<input id="titulo_1902" name="titulo_1902" type="hidden" value="<?php echo $ETI['titulo_1902']; ?>" />
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


<div id="div_sector97" style="display:none">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda97" name="cmdAyuda97" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
<input id="cmdVolverSec97" name="cmdVolverSec97" type="button" class="btSupVolver" onclick="retornacontrol();" title="<?php echo $ETI['bt_volver']; ?>" value="<?php echo $ETI['bt_volver']; ?>"/>
</div>
<div class="titulosI" id="div_97titulo">
<?php
echo '<h2>'.$ETI['titulo_1902'].'</h2>';
?>
</div>
</div>
<div id="cargaForm">
<div id="area">
<div id="div_97params"></div>
<div class="salto1px"></div>
<div id="div_97tabla"></div>
</div><!-- /div_area -->
</div><!-- /DIV_cargaForm -->
</div><!-- /DIV_Sector97 -->


<div id="div_sector98" style="display:none">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda98" name="cmdAyuda98" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
</div>
<div class="titulosI">
<?php
echo '<h2>'.$ETI['titulo_1902'].'</h2>';
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
<?php
?>
<input id="cmdGuardarf" name="cmdGuardarf" type="button" class="btSoloGuardar" onClick="enviaguardar();" value="<?php echo $ETI['bt_guardar']; ?>"/>
<?php
?>
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
-->
</script>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/jquery.autocomplete.css" type="text/css"/>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/jquery.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/jquery.autocomplete.js"></script>
<script language="javascript" src="ac_1902.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas.js?ver=8"></script>
<?php
forma_piedepagina();
?>