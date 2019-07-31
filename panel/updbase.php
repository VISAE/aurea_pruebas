<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2014 - 2016 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
*/
error_reporting(E_ALL);
set_time_limit(0);
require './app.php';
if (isset($APP->dbhost)==0){
	echo 'No se ha definido el servidor de base de datos';
	die();
	}
require $APP->rutacomun.'libs/clsdbadmin.php';
$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
$versionejecutable=67;
$procesos=0;
$suspende=0;
$iError=0;
$u01="INSERT INTO unad01sistema (unad01id, unad01nombre, unad01descripcion, unad01publico, unad01instalado, unad01mayor, unad01menor, unad01correccion) VALUES ";
$u03="INSERT INTO unad03permisos (unad03id, unad03nombre) VALUES ";
$u04="INSERT INTO unad04modulopermisos (unad04idmodulo, unad04idpermiso, unad04vigente) VALUES ";
$u05="INSERT INTO unad05perfiles (unad05id, unad05nombre) VALUES ";
$u06="INSERT INTO unad06perfilmodpermiso (unad06idperfil, unad06idmodulo, unad06idpermiso, unad06vigente) VALUES ";
$u08="INSERT INTO unad08grupomenu (unad08id, unad08nombre, unad08pagina, unad08titulo, unad08nombre_en, unad08nombre_pt) VALUES ";
$u09="INSERT INTO unad09modulomenu (unad09idmodulo, unad09consec, unad09nombre, unad09pagina, unad09grupo, unad09orden, unad09movil, unad09nombre_en, unad09nombre_pt) VALUES ";
$u22="INSERT INTO unad22combos (unad22idmodulo, unad22consec, unad22codopcion, unad22nombre, unad22orden, unad22activa) VALUES ";
echo 'Iniciando proceso de revision de la base de datos [DB : '.$APP->dbname.']';
$sql='SHOW TABLES LIKE "unad00config";';
$result=$objdb->ejecutasql($sql);
$cant=$objdb->nf($result);
if ($cant<1){
	$sql="CREATE TABLE unad00config (unad00codigo varchar(30), unad00nombre varchar(50), unad00valor varchar (20), PRIMARY KEY (unad00codigo));";
	$result=$objdb->ejecutasql($sql);
	$sql="INSERT INTO unad00config (unad00codigo, unad00nombre, unad00valor) VALUES('dbversion','Version de la Base de Datos', 0);";
	$result=$objdb->ejecutasql($sql);
	$dbversion=0;
	}else{ 
	$sql="SELECT unad00valor FROM unad00config WHERE unad00codigo='dbversion';";
	$result=$objdb->ejecutasql($sql);
	$row=$objdb->sf($result);
	$dbversion=$row['unad00valor'];
	$bbloquea=false;
	if ($dbversion>1000){$bbloquea=true;}
	if ($bbloquea){
		echo '<br>Debe ejecutar el script que corresponda a la version {'.$dbversion.'}...';
		die();		
		}
	}
echo "<br>Version Actual de la base de datos ".$dbversion;
while ($dbversion<$versionejecutable){
	$sql='';
if (($dbversion>0)&&($dbversion<101)){
    if ($dbversion==1){$sql="CREATE TABLE unad11terceros (unad11tipodoc varchar(2) NOT NULL, unad11doc varchar(13) NOT NULL, unad11id int NULL DEFAULT 0, unad11pais varchar(3) NULL, unad11usuario varchar(20) NULL, unad11dv varchar(2) NULL, unad11nombre1 varchar(30) NULL, unad11nombre2 varchar(30) NULL, unad11apellido1 varchar(30) NULL, unad11apellido2 varchar(30) NULL, unad11genero varchar(1) NULL, unad11fechanace varchar(10) NULL, unad11rh varchar(3) NULL, unad11ecivil varchar(1) NULL, unad11razonsocial varchar(100) NULL, unad11direccion varchar(100) NULL, unad11telefono varchar(30) NULL, unad11correo varchar(50) NULL, unad11sitioweb varchar(50) NULL, unad11nacionalidad varchar(3) NULL, unad11deptoorigen varchar(5) NULL, unad11ciudadorigen varchar(8) NULL, unad11deptodoc varchar(5) NULL, unad11ciudaddoc varchar(8) NULL, unad11clave varchar(50) NULL, unad11idmoodle int NULL DEFAULT 0)";}
    if ($dbversion==2){$sql="ALTER TABLE unad11terceros ADD PRIMARY KEY(unad11id)";}
    if ($dbversion==3){$sql="ALTER TABLE unad11terceros ADD UNIQUE INDEX unad11terceros_id (unad11doc, unad11tipodoc)";}
    if ($dbversion==4){$sql="INSERT INTO unad11terceros (unad11tipodoc, unad11doc, unad11id, unad11pais, unad11usuario, unad11dv, unad11nombre1, unad11nombre2, unad11apellido1, unad11apellido2, unad11genero, unad11fechanace, unad11rh, unad11ecivil, unad11razonsocial, unad11direccion, unad11telefono, unad11correo, unad11sitioweb, unad11nacionalidad, unad11deptoorigen, unad11ciudadorigen, unad11deptodoc, unad11ciudaddoc, unad11clave, unad11idmoodle) VALUES ('CC', '2', 2, '057', 'admin', '0', '', '', '', '', 'N', '00/00/0000', 'N', 'N', 'Administrador MOODLE', 'NA', '', 'admin@unad.edu.co', 'www.unad.edu.co', '057', '05701', '05701001', '05701', '05701001', '', 2)";}
	if ($dbversion==5){$sql="CREATE TABLE unad01sistema (unad01id int NOT NULL, unad01nombre varchar(50) NULL, unad01descripcion varchar(250) NULL, unad01publico varchar(1) NULL, unad01instalado varchar(1) NULL, unad01mayor int NULL DEFAULT 0, unad01menor int NULL DEFAULT 0, unad01correccion int NULL DEFAULT 0)";}
	if ($dbversion==6){$sql="ALTER TABLE unad01sistema ADD PRIMARY KEY(unad01id)";}
	if ($dbversion==7){$sql=$u01."(1, 'Panel', 'Panel de DESARROLLO AUREA', 'N', 'S', 1, 0, 0)";}
    if ($dbversion==8){$sql="CREATE TABLE unad02modulos (unad02id int NOT NULL, unad02nombre varchar(100) NULL, unad02idsistema int NULL DEFAULT 0)";}
    if ($dbversion==9){$sql="ALTER TABLE unad02modulos ADD PRIMARY KEY(unad02id)";}
    if ($dbversion==10){$sql="CREATE TABLE unad03permisos (unad03id int NOT NULL, unad03nombre varchar(100) NULL)";}
    if ($dbversion==11){$sql="ALTER TABLE unad03permisos ADD PRIMARY KEY(unad03id)";}
	if ($dbversion==12){$sql=$u03."(1, 'Consultar'), (2, 'Guardar'), (3,'Modificar'), (4,'Eliminar'), (5,'Imprimir')";}
    if ($dbversion==13){$sql="CREATE TABLE unad04modulopermisos (unad04idmodulo int NOT NULL, unad04idpermiso int NOT NULL, unad04vigente varchar(1) NULL)";}
    if ($dbversion==14){$sql="ALTER TABLE unad04modulopermisos ADD PRIMARY KEY(unad04idmodulo, unad04idpermiso)";}
    if ($dbversion==15){$sql="CREATE TABLE unad05perfiles (unad05id int NOT NULL, unad05nombre varchar(100) NULL)";}
    if ($dbversion==16){$sql="ALTER TABLE unad05perfiles ADD PRIMARY KEY(unad05id)";}
	if ($dbversion==17){$sql="INSERT INTO unad05perfiles (unad05id, unad05nombre) VALUES (1,'Super Administrador')";}
    if ($dbversion==18){$sql="CREATE TABLE unad06perfilmodpermiso (unad06idperfil int NOT NULL, unad06idmodulo int NOT NULL, unad06idpermiso int NOT NULL, unad06vigente varchar(1) NULL)";}
    if ($dbversion==19){$sql="ALTER TABLE unad06perfilmodpermiso ADD PRIMARY KEY(unad06idperfil, unad06idmodulo, unad06idpermiso)";}
    if ($dbversion==20){$sql="CREATE TABLE unad07usuarios (unad07idperfil int NOT NULL, unad07idtercero int NOT NULL, unad07vigente varchar(1) NULL)";}
    if ($dbversion==21){$sql="ALTER TABLE unad07usuarios ADD PRIMARY KEY (unad07idperfil, unad07idtercero)";}
    if ($dbversion==22){$sql="CREATE TABLE unad08grupomenu (unad08id int NOT NULL, unad08nombre varchar(50) NULL, unad08pagina varchar(50) NULL, unad08titulo varchar(100) NULL, unad08nombre_en varchar(50) NULL, unad08nombre_pt varchar(50) NULL)";}
    if ($dbversion==23){$sql="ALTER TABLE unad08grupomenu ADD PRIMARY KEY(unad08id)";}
	if ($dbversion==24){$sql=$u08."(0, 'General', 'gm.php?id=0', 'General', 'General', 'Geral'), (1, 'Administrar', 'gm.php?id=1', 'Administrar', 'Administrate', 'Administrar'), (2, 'Configurar', 'gm.php?id=2', 'Configurar', 'Configure', 'Configura')";}
    if ($dbversion==25){$sql="CREATE TABLE unad09modulomenu (unad09idmodulo int NOT NULL, unad09consec int NOT NULL, unad09nombre varchar(50) NULL, unad09pagina varchar(50) NULL, unad09grupo int NULL DEFAULT 0, unad09orden int NULL DEFAULT 0, unad09movil varchar(1) NULL, unad09nombre_en varchar(50) NULL, unad09nombre_pt varchar(50) NULL)";}
    if ($dbversion==26){$sql="ALTER TABLE unad09modulomenu ADD PRIMARY KEY(unad09idmodulo, unad09consec)";}
	if ($dbversion==27){$sql="ALTER TABLE unad11terceros ADD unad11idncontents int NOT NULL DEFAULT 0, ADD unad11iddatateca int NULL DEFAULT 0, ADD unad11idcampus int NULL DEFAULT 0, ADD unad11claveapps varchar(50) NULL DEFAULT '', ADD unad11fechaclaveapps varchar(10) NULL DEFAULT '00/00/0000', ADD unad11fechatablero varchar(10) NULL DEFAULT '00/00/0000', ADD unad11bloqueado varchar(1) NULL DEFAULT 'N', ADD unad11modf int NULL DEFAULT 0, ADD unad11modm int NULL DEFAULT 0, ADD unad11aceptanotificacion varchar(1) NULL DEFAULT 'P', ADD unad11correonotifica varchar(50) NULL DEFAULT '', ADD unad11correoinstitucional varchar(50) NULL DEFAULT '', ADD unad11encuestafecha int NULL DEFAULT 0, ADD unad11encuestaminuto int NULL DEFAULT 0, ADD unad11latgrados int NULL DEFAULT 0, ADD unad11latdecimas varchar(10) NULL DEFAULT '', ADD unad11longrados int NULL DEFAULT 0, ADD unad11longdecimas varchar(10) NULL DEFAULT '', ADD unad11skype varchar(50) NULL DEFAULT '', ADD unad11mostrarcelular varchar(1) NULL DEFAULT 'N', ADD unad11mostrarcorreo varchar(1) NULL DEFAULT 'N', ADD unad11mostrarskype varchar(1) NULL DEFAULT 'N', ADD unad11fechaterminos int NULL DEFAULT 0, ADD unad11minutotablero int NULL DEFAULT 0, ADD unad11fechacrea int NULL DEFAULT 0, ADD unad11mincrea int NULL DEFAULT 0, ADD unad11noubicar int NULL DEFAULT 0, ADD unad11idtablero int NULL DEFAULT 0, ADD unad11fechaconfmail int NULL DEFAULT 0, ADD unad11rolunad int NULL DEFAULT -1, ADD unad11exluirdobleaut varchar(1) NULL DEFAULT 'N', ADD unad11correonotificanuevo varchar(50) NULL DEFAULT '', ADD unad11idzona int NULL DEFAULT 0, ADD unad11idcead int NULL DEFAULT 0, ADD unad11idprograma int NULL DEFAULT 0, ADD unad11presentacion Text NULL, ADD unad11fechaactualiza int NULL DEFAULT 0, ADD unad11idescuela int NULL DEFAULT 0, ADD unad11fechaclave int NULL DEFAULT 0, ADD unad11fechaultingreso int NULL DEFAULT 0";}
    if ($dbversion==28){$sql="agregamodulo|111|1|Terceros|1|2|3";}
    if ($dbversion==29){$sql=$u09."(111, 1, 'Terceros', 'unadterceros.php', 1, 111, 'S', '', '')";}
	if ($dbversion==30){$sql="INSERT INTO unad07usuarios (unad07idperfil, unad07idtercero, unad07vigente) VALUES (1, 2, 'S');";}
    if ($dbversion==31){$sql="CREATE TABLE unad22combos (unad22idmodulo int NOT NULL, unad22consec int NOT NULL, unad22codopcion varchar(10) NULL, unad22nombre varchar(100) NULL, unad22orden int NULL DEFAULT 0, unad22activa varchar(1) NULL)";}
    if ($dbversion==32){$sql="ALTER TABLE unad22combos ADD PRIMARY KEY(unad22idmodulo, unad22consec, unad22codopcion)";}
    if ($dbversion==33){$sql=$u22."(111, 1, 'M','Masculino', 1, 'S'), (111, 1, 'F','Femenino', 2, 'S')";}
    if ($dbversion==34){$sql=$u22."(111, 2, 'O+','O+', 1, 'S'), (111, 2, 'A+','A+', 2, 'S'), (111, 2, 'B+','B+', 3, 'S'), (111, 2, 'AB+','AB+', 4, 'S'), (111, 2, 'O-','O-', 5, 'S'), (111, 2, 'A-','A-', 6, 'S'), (111, 2, 'B-','B-', 7, 'S'), (111, 2, 'AB-','AB-', 8, 'S')";}
    if ($dbversion==35){$sql="agregamodulo|107|1|Usuarios|1|2|3|4";}
    if ($dbversion==36){$sql=$u09."(107, 1, 'Usuarios', 'unadusuarios.php', 1, 107, 'S', 'Users', 'Usuarios')";}
    if ($dbversion==37){$sql="CREATE TABLE unad21estadocivil (unad21codigo varchar(1) NOT NULL, unad21id int NULL DEFAULT 0, unad21nombre varchar(50) NULL, unad21predet varchar(1) NULL, unad21orden int NULL DEFAULT 0)";}
    if ($dbversion==38){$sql="ALTER TABLE unad21estadocivil ADD PRIMARY KEY(unad21id)";}
    if ($dbversion==39){$sql="ALTER TABLE unad21estadocivil ADD UNIQUE INDEX unad21estadocivil_id (unad21codigo)";}
	if ($dbversion==40){$sql="INSERT INTO unad21estadocivil(unad21codigo, unad21id, unad21nombre, unad21predet, unad21orden) VALUES ('S', 1, 'Soltero/a', 'S', 1), ('C', 2, 'Casado/a', 'N', 2), ('U', 3, 'Union Libre', 'N', 3), ('D', 4, 'Divorciado/a', 'N', 4), ('E', 5, 'Separado/a', 'N', 5), ('V', 6, 'Viudo/a', 'N', 6)";}
	if ($dbversion==41){$sql="CREATE TABLE unad18pais(unad18codigo varchar(3) NOT NULL, unad18nombre varchar(50) NULL)";}
	if ($dbversion==42){$sql="ALTER TABLE unad18pais ADD PRIMARY KEY(unad18codigo)";}
	if ($dbversion==43){$sql="INSERT INTO unad18pais(unad18codigo, unad18nombre) VALUES ('001', 'Estados Unidos de America'), ('057', 'Colombia')";}
    if ($dbversion==44){$sql="CREATE TABLE unad19depto (unad19codigo varchar(5) NOT NULL, unad19nombre varchar(50) NULL, unad19codpais varchar(3) NULL)";}
	if ($dbversion==45){$sql="ALTER TABLE unad19depto ADD PRIMARY KEY(unad19codigo)";}
	if ($dbversion==46){$sql="INSERT INTO unad19depto(unad19codigo, unad19nombre, unad19codpais) VALUES ('05705', 'ANTIOQUIA', '057'), ('05708', 'ATLANTICO', '057'), ('05711', 'BOGOTA D.C.', '057'), ('05714', 'CARTAGENA D.E.', '057'), ('05715', 'BOYACA', '057'), ('05717', 'CALDAS', '057'), ('05718', 'CAQUETA', '057'), ('05719', 'CAUCA', '057'), ('05720', 'CESAR', '057'), ('05723', 'CORDOVA', '057'), ('05725', 'CUNDINAMARCA', '057'), ('05727', 'CHOCO', '057'), ('05741', 'HUILA', '057'), ('05744', 'LA GUAJIRA', '057'), ('05747', 'MAGDALENA', '057'), ('05748', 'SANTAMARTA D.E', '057'), ('05750', 'META', '057'), ('05752', 'NARINO', '057'), ('05754', 'NORTE DE SANTANDER', '057'), ('05763', 'QUINDIO', '057'), ('05766', 'RISARALDA', '057'), ('05768', 'SANTANDER', '057'), ('05770', 'SUCRE', '057'), ('05773', 'TOLIMA', '057'), ('05776', 'VALLE', '057'), ('05781', 'ARAUCA', '057'), ('05785', 'CASANARE', '057'), ('05786', 'PUTUMAYO', '057'), ('05788', 'SAN ANDRES', '057'), ('05791', 'AMAZONAS', '057'), ('05794', 'GUAINIA', '057'), ('05795', 'GUAVIARE', '057'), ('05797', 'VAUPES', '057')";}
    if ($dbversion==47){$sql="CREATE TABLE unad20ciudad (unad20codigo varchar(8) NOT NULL, unad20nombre varchar(50) NULL, unad20codpais varchar(3) NULL, unad20coddepto varchar(5) NULL)";}
	if ($dbversion==48){$sql="ALTER TABLE unad20ciudad ADD PRIMARY KEY(unad20codigo)";}
	if ($dbversion==49){$sql="INSERT INTO unad20ciudad(unad20codigo, unad20nombre, unad20coddepto, unad20codpais) VALUES 
('05705001', 'MEDELLIN', '05705', '057'), 
('05708001', 'BARRANQUILLA (Distrito)', '05708', '057'), 
('05711001', 'BOGOTA D.C.', '05711', '057'), 
('05714001', 'Cartagena DE', '05714', '057'), 
('05715001', 'TUNJA', '05715', '057'), 
('05719001', 'POPAYAN', '05719', '057'), 
('05720001', 'VALLEDUPAR', '05720', '057'), 
('05723001', 'MONTERIA', '05723', '057'), 
('05725899', 'ZIPAQUIRA', '05725', '057'), 
('05727001', 'QUIBDO', '05727', '057'), 
('05741001', 'NEIVA', '05741', '057'), 
('05744001', 'RIOHACHA', '05744', '057'), 
('05750001', 'VILLAVICENCIO', '05750', '057'), 
('05750006', 'ACACIAS', '05750', '057'), 
('05752001', 'PASTO', '05752', '057'), 
('05754001', 'CUCUTA', '05754', '057'), 
('05763001', 'ARMENIA', '05763', '057'), 
('05766001', 'PEREIRA', '05766', '057'), 
('05768001', 'BUCARAMANGA', '05768', '057'), 
('05770001', 'SINCELEJO', '05770', '057'), 
('05773001', 'IBAGUE', '05773', '057')";}
    if ($dbversion==50){$sql="CREATE TABLE unad23zona (unad23codigo int NOT NULL, unad23id int NULL DEFAULT 0, unad23nombre varchar(100) NULL)";}
    if ($dbversion==51){$sql="ALTER TABLE unad23zona ADD PRIMARY KEY(unad23id)";}
    if ($dbversion==52){$sql="ALTER TABLE unad23zona ADD UNIQUE INDEX unad23zona_id (unad23codigo)";}
    if ($dbversion==53){$sql="CREATE TABLE unad24sede (unad24codigo int NOT NULL, unad24id int NULL DEFAULT 0, unad24idzona int NULL DEFAULT 0, unad24nombre varchar(100) NULL, unad24codpais varchar(3) NULL, unas24coddepto varchar(5) NULL, unad24codciudad varchar(8) NULL, unad24idclase int NULL DEFAULT 0, unad24prefijoconsec varchar(20) NULL)";}
    if ($dbversion==54){$sql="ALTER TABLE unad24sede ADD PRIMARY KEY(unad24id)";}
    if ($dbversion==55){$sql="ALTER TABLE unad24sede ADD UNIQUE INDEX unad24sede_id (unad24codigo)";}
    if ($dbversion==56){$sql="CREATE TABLE exte01escuela (exte01id int NOT NULL, exte01nombre varchar(100) NULL)";}
    if ($dbversion==57){$sql="ALTER TABLE exte01escuela ADD PRIMARY KEY(exte01id)";}
	if ($dbversion==58){$sql="CREATE TABLE core09programa (core09codigo int NOT NULL, core09id int NULL DEFAULT 0, core09nombre varchar(250) NULL, core09idescuela int NULL DEFAULT 0, core09iddirector int NULL DEFAULT 0, core09idversionactual int NULL DEFAULT 0, core09activo varchar(1) NULL DEFAULT 'S', core09idtipocaracterizacion int NULL DEFAULT 0)";}
	if ($dbversion==59){$sql="ALTER TABLE core09programa ADD PRIMARY KEY(core09id)";}
	if ($dbversion==60){$sql="ALTER TABLE core09programa ADD UNIQUE INDEX core09programa_id(core09codigo)";}
	if ($dbversion==61){$sql="ALTER TABLE unad05perfiles ADD unad05aplicativo int NULL DEFAULT 0, ADD unad05reservado varchar(1) NULL DEFAULT 'N', ADD unad05delegable varchar(1) NULL DEFAULT 'N'";}
    if ($dbversion==62){$sql="ALTER TABLE unad07usuarios ADD unad07fechavence varchar(10) NULL DEFAULT '00/00/0000'";}
	if ($dbversion==63){$sql="ALTER TABLE unad07usuarios ADD  INDEX unad07usuarios_tercero(unad07idtercero)";}
    if ($dbversion==64){$sql="agregamodulo|105|1|Perfiles|1|2|3|4|5|6";}
    if ($dbversion==65){$sql=$u09."(105, 1, 'Perfiles', 'unadperfil.php', 1, 105, 'S', '', '')";}
    if ($dbversion==66){$sql="agregamodulo|106|1|Permisos por perfil|1|2|3|4";}
	}
if (($dbversion>100)&&($dbversion<201)){
	}
	echo "<br>".$sql;
	switch (substr($sql,0,10)){
		case "versionado":
			$sper=explode("|",$sql);
			$stemp="UPDATE unad01sistema SET unad01mayor=".$sper[2].", unad01menor=".$sper[3].", unad01correccion=".$sper[4]." WHERE unad01id=".$sper[1];
			$result=$objdb->ejecutasql($stemp);
		break;
		case "agregamodu":
			$sper=explode("|",$sql);
			$stemp="INSERT INTO unad02modulos (unad02id, unad02nombre, unad02idsistema) VALUES (".$sper[1].", '".$sper[3]."', ".$sper[2].")";
			$result=$objdb->ejecutasql($stemp);
			echo " .";
			for ($k=4;$k<count($sper);$k++){
				$stemp=$u04."(".$sper[1].", ".$sper[$k].", 'S')";
				$result=$objdb->ejecutasql($stemp);
				echo " .";
				$stemp=$u06."(1, ".$sper[1].", ".$sper[$k].", 'S')";
				$result=$objdb->ejecutasql($stemp);
				echo ".";
				}
			break;
		case "crearmodul":
			$sper=explode("|",$sql);
			$stemp="INSERT INTO unad02modulos (unad02id, unad02nombre, unad02idsistema) VALUES (".$sper[1].", '".$sper[3]."', ".$sper[2].")";
			$result=$objdb->ejecutasql($stemp);
			echo " .";
			for ($k=4;$k<count($sper);$k++){
				$stemp=$u04."(".$sper[1].", ".$sper[$k].", 'S')";
				$result=$objdb->ejecutasql($stemp);
				echo " .";
				}
			break;
		case "modulogrup":
			$sper=explode("|",$sql);
			for ($k=3;$k<count($sper);$k++){
				$stemp=$u06."(".$sper[2].", ".$sper[1].", ".$sper[$k].", 'S')";
				$result=$objdb->ejecutasql($stemp);
				echo ".";
				}
			break;
		case "DROP TABLE":
			$nomtabla=substr($sql,11);
			$sqlb='SHOW TABLES LIKE "'.$nomtabla.'"';
			$result=$objdb->ejecutasql($sqlb);
			if ($objdb->nf($result)==0){
				echo '<br>La tabla '.$nomtabla.' no existe.';
				}else{
				$result=$objdb->ejecutasql($sql);
				}
			break;
		case "mod_cod_ca":
			$sper=explode("|",$sql);
			$stemp="UPDATE unad02modulos SET unad02id=".$sper[2]." WHERE unad02id=".$sper[1].";";
			$result=$objdb->ejecutasql($stemp);
			echo " .";
			$stemp="UPDATE unad04modulopermisos SET unad04idmodulo=".$sper[2]." WHERE unad04idmodulo=".$sper[1].";";
			$result=$objdb->ejecutasql($stemp);
			echo " .";
			$stemp="UPDATE unad06perfilmodpermiso SET unad06idmodulo=".$sper[2]." WHERE unad06idmodulo=".$sper[1].";";
			$result=$objdb->ejecutasql($stemp);
			echo " .";
			$stemp="UPDATE unad09modulomenu SET unad09idmodulo=".$sper[2]." WHERE unad09idmodulo=".$sper[1].";";
			$result=$objdb->ejecutasql($stemp);
			echo " .";
			break;
		case "mod_quitar":
			$sper=explode("|",$sql);
			$stemp="DELETE FROM unad02modulos WHERE unad02id=".$sper[1].";";
			$result=$objdb->ejecutasql($stemp);
			echo " .";
			$stemp="DELETE FROM unad04modulopermisos WHERE unad04idmodulo=".$sper[1].";";
			$result=$objdb->ejecutasql($stemp);
			echo " .";
			$stemp="DELETE FROM unad06perfilmodpermiso WHERE unad06idmodulo=".$sper[1].";";
			$result=$objdb->ejecutasql($stemp);
			echo " .";
			$stemp="DELETE FROM unad09modulomenu WHERE unad09idmodulo=".$sper[1].";";
			$result=$objdb->ejecutasql($stemp);
			echo " .";
			break;
		case "":
			break;
		default:
		$result=$objdb->ejecutasql($sql);
		if ($result==false){
			echo '<br><font color="#FF0000"><b>Error </b>'.$objdb->serror.'</font>';
			$iError++;
			$suspende=1;
			}
		}//fin del switch
	$sql="UPDATE unad00config SET unad00valor=".($dbversion+1)." WHERE unad00codigo='dbversion';";
	$result=$objdb->ejecutasql($sql);
	$dbversion++;
	$procesos++;
	if ($procesos>14){
		$suspende=1;
		break;
		}
	}//termina de ejecutar sentencia por sentenca.
echo '<br>Base de Datos Actualizada '.$dbversion.';'; 
if($suspende==1){
?><br>
<form id="form1" name="form1" method="post" action="">
El Proceso A&uacute;n No Ha Concluido
<input type="submit" name="Submit" value="Continuar" />
</form>
<?php
if ($iError==0){
?>
<script language="javascript">
function recargar(){
	form1.submit();
	}
setInterval ("recargar();", 1000); 
</script>
<?php 
		}//fin de si no hay errores...
	}
?>
