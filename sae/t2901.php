<?php
/*
/** Archivo para reportes tipo csv 2901.
* Aquí se genera un archivo tipo csv con la siguiente estructura (indicar estructura).
* © Omar Augusto Bautista Mora - UNAD - 2019 ---
* @author Omar Augusto Bautista Mora - omar.bautista@unad.edu.co
* @date Monday, Noviembre 18, 2019
*/
error_reporting(E_ALL);
ini_set("display_errors", 1);

if (file_exists('./err_control.php')){require './err_control.php';}
if (!file_exists('./app.php')){
    echo '<b>Error N 1 de instalaci&oacute;n</b><br>No se ha establecido un archivo de configuraci&oacute;n, por favor comuniquese con el administrador del sistema.';
    die();
    }
mb_internal_encoding('UTF-8');
require './app.php';
require $APP->rutacomun.'unad_todas.php';
require $APP->rutacomun.'libs/clsdbadmin.php';
require $APP->rutacomun.'unad_librerias.php';
require $APP->rutacomun.'libs/clsplanos.php';
if ($_SESSION['unad_id_tercero']==0){
    die();
    }
$_SESSION['u_ultimominuto']=iminutoavance();
$sError='';
$iReporte=0;
$bEntra=false;
$bDebug=false;
if (isset($_REQUEST['r'])!=0){$iReporte=numeros_validar($_REQUEST['r']);}
if (isset($_REQUEST['clave'])==0){$_REQUEST['clave']='';}
if (isset($_REQUEST['rdebug'])==0){$_REQUEST['rdebug']=0;}
$bdesdet='';
$bhastat='';
$sSQL='';
$sSQLadd='';
$sSQLadd1='';
$sDescripReporte='';
if (isset($_REQUEST['bdesdee'])!=0){$bdesdet=numeros_validar($_REQUEST['bdesdee']);}
if (isset($_REQUEST['bhastae'])!=0){$bhastat=numeros_validar($_REQUEST['bhastae']);}
$bEntra=true;
if ($iReporte==2901){$bEntra=true;}
if ($sError!=''){$bEntra=false;}
if ($bEntra){
    if ($_REQUEST['rdebug']==1){$bDebug=true;}
    $cSepara=',';
    $cEvita=';';
    $cComplementa='.';
    if (isset($_REQUEST['separa'])!=0){
        if ($_REQUEST['separa']==';'){
            $cSepara=';';
            $cEvita=',';
            }
        }
    $objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
    if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
    //Fecha Registro Desde
    if ($bdesdet!=0){
        $sSQLadd=$sSQLadd.' AND TB.plab01fechareg >= "'.$bdesdet.'" ';
        $sDescripReporte=$sDescripReporte.' Fecha de Registro Desde: '.fecha_desdenumero($bdesdet);
        }
    //Fecha Registro Hasta
    if ($bhastat!=0){
        $sSQLadd=$sSQLadd.' AND TB.plab01fechareg <= "'.$bhastat.'" ';
        $sDescripReporte=$sDescripReporte.' Hasta: '.fecha_desdenumero($bhastat);
        }
    $mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
    if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
    $mensajes_2901='lg/lg_2901_'.$_SESSION['unad_idioma'].'.php';
    if (!file_exists($mensajes_2901)){$mensajes_2901='lg/lg_2901_es.php';}
    require $mensajes_todas;
    require $mensajes_2901;
    $sPath=dirname(__FILE__);
    $sSeparador=archivos_separador($sPath);
    $sPath=archivos_rutaservidor($sPath,$sSeparador);
    $sNombrePlano='t2901.csv';
    $sTituloRpt='Lista de hojas de vida';
    $sNombrePlanoFinal=$sTituloRpt.'.csv';
    $objplano=new clsPlanos($sPath.$sNombrePlano);
    $sDato='UNIVERSIDAD NACIONAL ABIERTA Y A DISTANCIA - UNAD';
    $objplano->AdicionarLinea($sDato);
    $sDato=$sDescripReporte;
    $objplano->AdicionarLinea($sDato);
    $sDato='';
    $objplano->AdicionarLinea($sDato);
    /* Alistar los arreglos para las tablas hijas */
    $aplab01emprbolsempleo=array();
    $aplab01ultprof=array();
    $aplab01aspsal=array();
    $aplab01cargo=array();
    $aplab01industria=array();
    $aplab01sector=array();
    $aplab01nivingles=array();
    $sTitulo1='Titulo 1';
    $sTitulo2='';
    for ($l=1;$l<=20;$l++){
        $sTitulo1=$sTitulo1.$cSepara;
        }
    $sBloque1=''.'Empresa bolsa de empleo'.$cSepara.'documento tercero'.$cSepara.'nombre'.$cSepara.'fecha de registro'.$cSepara
        .'telefono principal'.$cSepara.'telefono oficina'.$cSepara.'telefono movil'.$cSepara.'correo'.$cSepara
        .'ultima profesion'.$cSepara.'aspiracion salarial'.$cSepara.'nombre empresa ultima experiencia'.$cSepara.'cargo'.$cSepara
        .'industria'.$cSepara.'sector'.$cSepara.'fecha inicio experiencia'.$cSepara.'fecha fin experiencia'.$cSepara
        .'nivel de ingles'.$cSepara.'fecha actualizacion hv'.$cSepara.'numero de postulaciones'.$cSepara.'condicion';
    $sTitulos2='Titulo 2';
    for ($l=1;$l<=5;$l++){
        $sTitulo2=$sTitulo2.$cSepara;
        }
    //$objplano->AdicionarLinea($sTitulo1.$sTitulo2);
    $objplano->AdicionarLinea($sBloque1);
    $sSQL='SELECT TB.plab01emprbolsempleo, T1.unad11tipodoc, T1.unad11doc, T1.unad11razonsocial, TB.plab01fechareg, 
TB.plab01telprin, TB.plab01telofic, TB.plab01telmov, TB.plab01correo, TB.plab01ultprof, TB.plab01aspsal, TB.plab01nomemprultexp, 
TB.plab01cargo, TB.plab01industria, TB.plab01sector, TB.plab01fechainiexp, TB.plab01fechafinexp, TB.plab01nivingles, 
TB.plab01fechaacthv, TB.plab01numpostula, TB.plab01condicion 
FROM plab01hv AS TB, unad11terceros AS T1
WHERE TB.plab01idtercero=T1.unad11id'.$sSQLadd.' 
ORDER BY TB.plab01fechareg';
    if ($bDebug){$objplano->adlinea($sSQL);}
    $tabla=$objDB->ejecutasql($sSQL);
    while ($fila=$objDB->sf($tabla)){
        $lin_plab01emprbolsempleo=$cSepara;
        $lin_unad11doc=$cSepara;
        $lin_unad11razonsocial=$cSepara;
        $lin_plab01fechareg=$cSepara;
        $lin_plab01telprin=$cSepara;
        $lin_plab01telofic=$cSepara;
        $lin_plab01telmov=$cSepara;
        $lin_plab01correo=$cSepara;
        $lin_plab01ultprof=$cSepara;
        $lin_plab01aspsal=$cSepara;
        $lin_plab01nomemprultexp=$cSepara;
        $lin_plab01cargo=$cSepara;
        $lin_plab01industria=$cSepara;
        $lin_plab01sector=$cSepara;
        $lin_plab01fechainiexp=$cSepara;
        $lin_plab01fechafinexp=$cSepara;
        $lin_plab01nivingles=$cSepara;
        $lin_plab01fechaacthv=$cSepara;
        $lin_plab01numpostula=$cSepara;
        $lin_plab01condicion=$cSepara;
        $i_plab01emprbolsempleo=$fila['plab01emprbolsempleo'];
        if (isset($aplab01emprbolsempleo[$i_plab01emprbolsempleo])==0){
            $sSQL='SELECT T1.unad11razonsocial 
FROM plab08emprbolsempleo AS TB, unad11terceros AS T1 
WHERE TB.plab08idtercero=T1.unad11id AND TB.plab08id='.$i_plab01emprbolsempleo.'';
            $tabla11=$objDB->ejecutasql($sSQL);
            if ($objDB->nf($tabla11)>0){
                $fila11=$objDB->sf($tabla11);
                $aplab01emprbolsempleo[$i_plab01emprbolsempleo]=$fila11['unad11razonsocial'];
                }else{
                $aplab01emprbolsempleo[$i_plab01emprbolsempleo]='';
                }
            }
        $lin_plab01emprbolsempleo=$aplab01emprbolsempleo[$i_plab01emprbolsempleo];
        $lin_unad11doc=$cSepara.$fila['unad11tipodoc'].' '.$fila['unad11doc'];
        $lin_unad11razonsocial=$cSepara.$fila['unad11razonsocial'];
        $lin_plab01fechareg=$cSepara.fecha_desdenumero($fila['plab01fechareg']);
        $lin_plab01telprin=$cSepara.$fila['plab01telprin'];
        $lin_plab01telofic=$cSepara.$fila['plab01telofic'];
        $lin_plab01telmov=$cSepara.$fila['plab01telmov'];
        $lin_plab01correo=$cSepara.$fila['plab01correo'];
        $i_plab01ultprof=$fila['plab01ultprof'];
        if (isset($aplab01ultprof[$i_plab01ultprof])==0){
            $sSQL='SELECT plab02nombre FROM plab02prof WHERE plab02id='.$i_plab01ultprof.'';
            $tablap=$objDB->ejecutasql($sSQL);
            if ($objDB->nf($tablap)>0){
                $filap=$objDB->sf($tablap);
                $aplab01ultprof[$i_plab01ultprof]=str_replace($cSepara, $cComplementa, $filap['plab02nombre']);
                }else{
                $aplab01ultprof[$i_plab01ultprof]='';
                }
            }
        $lin_plab01ultprof=$cSepara.$aplab01ultprof[$i_plab01ultprof];
        $i_plab01aspsal=$fila['plab01aspsal'];
        if (isset($aplab01aspsal[$i_plab01aspsal])==0){
            $sSQL='SELECT plab16nombre FROM plab16aspsala WHERE plab16id='.$i_plab01aspsal.'';
            $tablap=$objDB->ejecutasql($sSQL);
            if ($objDB->nf($tablap)>0){
                $filap=$objDB->sf($tablap);
                $aplab01aspsal[$i_plab01aspsal]=str_replace($cSepara, $cComplementa, $filap['plab16nombre']);
                }else{
                $aplab01aspsal[$i_plab01aspsal]='';
                }
            }
        $lin_plab01aspsal=$cSepara.$aplab01aspsal[$i_plab01aspsal];
        $lin_plab01nomemprultexp=$cSepara.str_replace($cSepara, $cComplementa, $fila['plab01nomemprultexp']);
        $i_plab01cargo=$fila['plab01cargo'];
        if (isset($aplab01cargo[$i_plab01cargo])==0){
            $sSQL='SELECT plab04nombre FROM plab04cargo WHERE plab04id='.$i_plab01cargo.'';
            $tablap=$objDB->ejecutasql($sSQL);
            if ($objDB->nf($tablap)>0){
                $filap=$objDB->sf($tablap);
                $aplab01cargo[$i_plab01cargo]=str_replace($cSepara, $cComplementa, $filap['plab04nombre']);
                }else{
                $aplab01cargo[$i_plab01cargo]='';
                }
            }
        $lin_plab01cargo=$cSepara.$aplab01cargo[$i_plab01cargo];
        $i_plab01industria=$fila['plab01industria'];
        if (isset($aplab01industria[$i_plab01industria])==0){
            $sSQL='SELECT plab05nombre FROM plab05industria WHERE plab05id='.$i_plab01industria.'';
            $tablap=$objDB->ejecutasql($sSQL);
            if ($objDB->nf($tablap)>0){
                $filap=$objDB->sf($tablap);
                $aplab01industria[$i_plab01industria]=str_replace($cSepara, $cComplementa, $filap['plab05nombre']);
                }else{
                $aplab01industria[$i_plab01industria]='';
                }
            }
        $lin_plab01industria=$cSepara.$aplab01industria[$i_plab01industria];
        $i_plab01sector=$fila['plab01sector'];
        if (isset($aplab01sector[$i_plab01sector])==0){
            $sSQL='SELECT plab06nombre FROM plab06sector WHERE plab06id='.$i_plab01sector.'';
            $tablap=$objDB->ejecutasql($sSQL);
            if ($objDB->nf($tablap)>0){
                $filap=$objDB->sf($tablap);
                $aplab01sector[$i_plab01sector]=str_replace($cSepara, $cComplementa, $filap['plab06nombre']);
                }else{
                $aplab01sector[$i_plab01sector]='';
                }
            }
        $lin_plab01sector=$cSepara.$aplab01sector[$i_plab01sector];
        $lin_plab01fechainiexp=$cSepara.fecha_desdenumero($fila['plab01fechainiexp']);
        $lin_plab01fechafinexp=$cSepara.fecha_desdenumero($fila['plab01fechafinexp']);
        $i_plab01nivingles=$fila['plab01nivingles'];
        if (isset($aplab01nivingles[$i_plab01nivingles])==0){
            $sSQL='SELECT plab07nombre FROM plab07nivingles WHERE plab07id='.$i_plab01nivingles.'';
            $tablap=$objDB->ejecutasql($sSQL);
            if ($objDB->nf($tablap)>0){
                $filap=$objDB->sf($tablap);
                $aplab01nivingles[$i_plab01nivingles]=str_replace($cSepara, $cComplementa, $filap['plab07nombre']);
                }else{
                $aplab01nivingles[$i_plab01nivingles]='';
                }
            }
        $lin_plab01nivingles=$cSepara.$aplab01nivingles[$i_plab01nivingles];
        $lin_plab01fechaacthv=$cSepara.fecha_desdenumero($fila['plab01fechaacthv']);
        $lin_plab01numpostula=$cSepara.$fila['plab01numpostula'];
        if ($fila['plab01condicion']==0){
            $lin_plab01condicion=$cSepara.'Vigente';
            }else{
            $lin_plab01condicion=$cSepara.'No Vigente';
            }
        $sBloque1=''.$lin_plab01emprbolsempleo.$lin_unad11doc.$lin_unad11razonsocial.$lin_plab01fechareg.$lin_plab01telprin
            .$lin_plab01telofic.$lin_plab01telmov.$lin_plab01correo.$lin_plab01ultprof.$lin_plab01aspsal.$lin_plab01nomemprultexp
            .$lin_plab01cargo.$lin_plab01industria.$lin_plab01sector.$lin_plab01fechainiexp.$lin_plab01fechafinexp
            .$lin_plab01nivingles.$lin_plab01fechaacthv.$lin_plab01numpostula.$lin_plab01condicion;
        $objplano->AdicionarLinea($sBloque1);
        }
    $objDB->CerrarConexion();
    $objplano->Generar();
    header('Content-Description: File Transfer');
    header('Content-Type: text/csv');
    header('Content-Length: '.filesize($sPath.$sNombrePlano));
    header('Content-Disposition: attachment; filename='.basename($sNombrePlanoFinal));
    readfile($sPath.$sNombrePlano);
    }
?>