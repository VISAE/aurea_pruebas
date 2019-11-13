<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2014 - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Inicia Martes, 4 de diciembre de 2018
--- Esta página se encarga de mantener actualizado los script de las bases de datos.
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
$versionejecutable=2818;
$procesos=0;
$suspende=0;
$error=0;
echo 'Iniciando proceso de revision de la base de datos [DB : '.$APP->dbname.']';
$sql='SHOW TABLES LIKE "unad00config";';
$result=$objdb->ejecutasql($sql);
$cant=$objdb->nf($result);
if ($cant<1){
	echo '<br>Debe ejecutar el script inicial';
	die();		
	}else{
	$sql="SELECT unad00valor FROM unad00config WHERE unad00codigo='dbversion';";
	$result=$objdb->ejecutasql($sql);
	$row=$objdb->sf($result);
	$dbversion=$row['unad00valor'];
	$bbloquea=false;
	if ($dbversion<2000){$bbloquea=true;}
	if ($dbversion>3000){$bbloquea=true;}
	if ($bbloquea){
		echo '<br>Debe ejecutar el script que corresponda a la version {'.$dbversion.'}...';
		die();		
		}
	}
echo "<br>Version Actual de la base de datos ".$dbversion;
if (true){
	$u01="INSERT INTO unad01sistema (unad01id, unad01nombre, unad01descripcion, unad01publico, unad01instalado, unad01mayor, unad01menor, unad01correccion) VALUES ";
	$u03="INSERT INTO unad03permisos (unad03id, unad03nombre) VALUES ";
	$u04="INSERT INTO unad04modulopermisos (unad04idmodulo, unad04idpermiso, unad04vigente) VALUES ";
	$u05="INSERT INTO unad05perfiles (unad05id, unad05nombre) VALUES ";
	$u06="INSERT INTO unad06perfilmodpermiso (unad06idperfil, unad06idmodulo, unad06idpermiso, unad06vigente) VALUES ";
	$u08="INSERT INTO unad08grupomenu (unad08id, unad08nombre, unad08pagina, unad08titulo, unad08nombre_en, unad08nombre_pt) VALUES ";
	$u09="INSERT INTO unad09modulomenu (unad09idmodulo, unad09consec, unad09nombre, unad09pagina, unad09grupo, unad09orden, unad09movil, unad09nombre_en, unad09nombre_pt) VALUES ";
	$u22="INSERT INTO unad22combos (unad22idmodulo, unad22consec, unad22codopcion, unad22nombre, unad22orden, unad22activa) VALUES ";
	$u60='INSERT INTO unad60preferencias (unad60idmodulo, unad60codigo, unad60nombre, unad60tipo) VALUES ';
	$unad70='INSERT INTO unad70bloqueoelimina (unad70idtabla, unad70idtablabloquea, unad70origennomtabla, unad70origenidtabla, unad70origencamporev, unad70mensaje, unad70etiqueta) VALUES ';
	}
while ($dbversion<$versionejecutable){
$sql='';
if (($dbversion>2000)&&($dbversion<2101)){
	if ($dbversion==2001){$sql="agregamodulo|1728|17|Indicadores de avance|1|2|3|4|5|6|8";}
	if ($dbversion==2002){$sql=$u09."(1728, 1, 'Indicadores de avance', 'oferrptindicadores.php', 11, 1728, 'S', '', '')";}
	if ($dbversion==2003){$sql="agregamodulo|1403|14|Conversaciones|1|2|3|4|5|6|8";}
	if ($dbversion==2004){$sql=$u09."(1403, 1, 'Conversaciones', 'maidconversacion.php', 1401, 1403, 'S', '', '')";}
	if ($dbversion==2005){$sql=$u08."(1401, 'Comunicaciones', 'gm.php?id=1401', 'Comunicaciones', 'Communications', 'comunicações')";}
	if ($dbversion==2006){$sql="CREATE TABLE maid06terceroperaca (maid06idtercero int NOT NULL, maid06idperaca int NOT NULL, maid06id int NULL DEFAULT 0, maid06numhilos int NULL DEFAULT 0)";}
	if ($dbversion==2007){$sql="ALTER TABLE maid06terceroperaca ADD PRIMARY KEY(maid06id)";}
	if ($dbversion==2008){$sql="ALTER TABLE maid06terceroperaca ADD UNIQUE INDEX maid06terceroperaca_id(maid06idtercero, maid06idperaca)";}
	if ($dbversion==2009){$sql="agregamodulo|1729|17|Masivos|1|3";}
	if ($dbversion==2010){$sql=$u09."(1729, 1, 'Masivos', 'oferprocesos.php', 7, 1729, 'S', '', '')";}
	if ($dbversion==2011){$sql="ALTER TABLE unad88opciones ADD unad88conencuestas varchar(1) NULL DEFAULT 'S', ADD unad88conmaidi varchar(1) NULL DEFAULT 'S', ADD unad88connotas varchar(1) NULL DEFAULT 'S'";}
	if ($dbversion==2012){$sql="CREATE TABLE ofer59estrategiaaprende (ofer59consec int NOT NULL, ofer59id int NULL DEFAULT 0, ofer59activa varchar(1) NULL, ofer59nombre varchar(50) NULL, ofer59idescuela int NULL DEFAULT 0)";}
	if ($dbversion==2013){$sql="ALTER TABLE ofer59estrategiaaprende ADD PRIMARY KEY(ofer59id)";}
	if ($dbversion==2014){$sql="ALTER TABLE ofer59estrategiaaprende ADD UNIQUE INDEX ofer59estrategiaaprende_id(ofer59consec)";}
	if ($dbversion==2015){$sql="agregamodulo|1759|17|Estrategias de aprendizaje|1|2|3|4|5|6|8";}
	if ($dbversion==2016){$sql=$u09."(1759, 1, 'Estrategias de aprendizaje', 'oferestrategia.php', 2, 1759, 'S', '', '')";}
	if ($dbversion==2017){$sql="agregamodulo|2490|24|Estrategias de aprendizaje|1|2|3|4|5|6|8";}
	if ($dbversion==2018){$sql=$u09."(2490, 1, 'Estrategias de aprendizaje', 'oferestrategia.php', 2, 1759, 'S', '', '')";}
	if ($dbversion==2019){$sql="ALTER TABLE ofer08oferta ADD ofer08razonvence int NULL DEFAULT 0, ADD ofer08idestrategiaaprende int NULL DEFAULT 0, ADD ofer08numdevoluciones int NULL DEFAULT 0, ADD ofer08numajustes int NULL DEFAULT 0, ADD ofer08entregaescuela int NULL DEFAULT 0";}
	if ($dbversion==2020){$sql="INSERT INTO ofer59estrategiaaprende (ofer59consec, ofer59id, ofer59activa, ofer59nombre, ofer59idescuela) VALUES (0, 0, 'N', '{Ninguna}', 0)";}
	if ($dbversion==2021){$sql="CREATE TABLE ofer60razonvence (ofer60consec int NOT NULL, ofer60id int NULL DEFAULT 0, ofer60activa varchar(1) NULL, ofer60nombre varchar(50) NULL)";}
	if ($dbversion==2022){$sql="ALTER TABLE ofer60razonvence ADD PRIMARY KEY(ofer60id)";}
	if ($dbversion==2023){$sql="ALTER TABLE ofer60razonvence ADD UNIQUE INDEX ofer60razonvence_id(ofer60consec)";}
	if ($dbversion==2024){$sql="agregamodulo|1760|17|Razones por las que vencen los cursos|1|2|3|4|5|6|8";}
	if ($dbversion==2025){$sql=$u09."(1760, 1, 'Razones por las que vencen los cursos', 'oferrazonvence.php', 2, 1760, 'S', '', '')";}
	if ($dbversion==2026){$sql="INSERT INTO ofer60razonvence (ofer60consec, ofer60id, ofer60activa, ofer60nombre) VALUES (0, 0, 'S', '{Ninguna}')";}
	if ($dbversion==2027){$sql="INSERT INTO core12escuela (core12codigo, core12id, core12nombre, core12iddecano, core12idadministrador, core12tieneestudiantes) VALUES (0, 0, '{Ninguna}', 0, 0, 'N')";}
	if ($dbversion==2028){$sql="agregamodulo|2391|23|Perfiles [Caracterización]|1";}
	if ($dbversion==2029){$sql=$u09."(2391, 1, 'Perfiles', 'unadperfil.php', 2, 2391, 'S', '', '')";}
	if ($dbversion==2030){$sql="agregamodulo|1791|17|Perfiles [Oferta Académica]|1";}
	if ($dbversion==2031){$sql=$u09."(1791, 1, 'Perfiles', 'unadperfil.php', 2, 1791, 'S', '', '')";}
	if ($dbversion==2032){$sql="agregamodulo|1792|17|Usuarios [Oferta Académica]|1";}
	if ($dbversion==2033){$sql=$u09."(1792, 1, 'Usuarios', 'unadusuarios.php', 1, 1792, 'S', '', '')";}
	if ($dbversion==2034){$sql="agregamodulo|1584|15|Estado de los contenedores|1";}
	if ($dbversion==2035){$sql=$u09."(1584, 1, 'Estado de los contenedores', 'unadrptestadocont.php', 301, 1584, 'S', '', '')";}
	if ($dbversion==2036){$sql=$u03."(1708, 'Administrar Centros'), (1709, 'Administrar Programas'), (1710, 'Administrar Zonas')";}
	if ($dbversion==2037){$sql="CREATE TABLE cara21lidereszona (cara21idlider int NOT NULL, cara21id int NULL DEFAULT 0, cara21activo varchar(1) NULL, cara21idzona int NULL DEFAULT 0)";}
	if ($dbversion==2038){$sql="ALTER TABLE cara21lidereszona ADD PRIMARY KEY(cara21id)";}
	if ($dbversion==2039){$sql="ALTER TABLE cara21lidereszona ADD UNIQUE INDEX cara21lidereszona_id(cara21idlider)";}
	if ($dbversion==2040){$sql="agregamodulo|2321|23|Lideres zonales|1|2|3|4|5|6|8";}
	if ($dbversion==2041){$sql=$u09."(2321, 1, 'Lideres zonales', 'caraliderzona.php', 1, 2321, 'S', '', '')";}
	if ($dbversion==2042){$sql="ALTER TABLE unad23zona ADD unad23conestudiantes varchar(1) NULL DEFAULT 'S'";}
	if ($dbversion==2043){$sql="ALTER TABLE cara00config ADD cara00idperfillider int NULL DEFAULT 0";}
	if ($dbversion==2044){$sql="agregamodulo|2349|23|Necesidades especiales|1|5|6";}
	if ($dbversion==2045){$sql=$u09."(2349, 1, 'Necesidades especiales', 'caraespeciales.php', 11, 2349, 'S', '', '')";}
	if ($dbversion==2046){$sql="ALTER TABLE ofer08oferta ADD ofer08sal_formanota int NULL DEFAULT 0, ADD ofer08sal_numjornadas int NULL DEFAULT 1, ADD ofer08sal_porc1 Decimal(15,2) NULL DEFAULT 100, ADD ofer08sal_porc2 Decimal(15,2) NULL DEFAULT 0, ADD ofer08sal_porc3 Decimal(15,2) NULL DEFAULT 0, ADD ofer08sal_porc4 Decimal(15,2) NULL DEFAULT 0";}
	if ($dbversion==2047){$sql="agregamodulo|2139|21|Disperción de notas|1|3|6";}
	if ($dbversion==2048){$sql=$u09."(2139, 1, 'Disperción de notas', 'oildispersion.php', 2103, 2139, 'S', '', '')";}
	if ($dbversion==2049){$sql="agregamodulo|2307|23|Panel caracterización|1|1707";}
	if ($dbversion==2050){$sql="ALTER TABLE unad11terceros ADD unad11necesidadesp Text NULL";}
	if ($dbversion==2051){$sql="CREATE TABLE cara22gruposfactores (cara22id int NOT NULL, cara22nombre varchar(50) NULL)";}
	if ($dbversion==2052){$sql="ALTER TABLE cara22gruposfactores ADD PRIMARY KEY(cara22id)";}
	if ($dbversion==2053){$sql="INSERT INTO cara22gruposfactores (cara22id, cara22nombre) VALUES (1, 'Personales'), (2, 'Psicosociales'), (3, 'Institucionales'), (4, 'Académicos'), (9, 'Sin información')";}
	if ($dbversion==2054){$sql="ALTER TABLE core09programa ADD cara09codsnies varchar(50) NULL DEFAULT ''";}
	if ($dbversion==2055){$sql="CREATE TABLE core21lineaprof (core21idprograma int NOT NULL, core21consec int NOT NULL, core21id int NULL DEFAULT 0, core21vigente varchar(1) NULL, core21nombre varchar(100) NULL, core21descripcion Text NULL)";}
	if ($dbversion==2056){$sql="ALTER TABLE core21lineaprof ADD PRIMARY KEY(core21id)";}
	if ($dbversion==2057){$sql="ALTER TABLE core21lineaprof ADD UNIQUE INDEX core21lineaprof_id(core21idprograma, core21consec)";}
	if ($dbversion==2058){$sql="ALTER TABLE core21lineaprof ADD INDEX core21lineaprof_padre(core21idprograma)";}
	if ($dbversion==2059){$sql="agregamodulo|2221|22|Lineas de profundización|1|2|3|4|5|6|8";}
	if ($dbversion==2060){$sql="INSERT INTO core21lineaprof (core21idprograma, core21consec, core21id, core21vigente, core21nombre, core21descripcion) VALUES (0, 0, 0, 'N', 'Ninguna', '')";}
	if ($dbversion==2061){$sql='DROP TABLE core11plandeestudio';}
	if ($dbversion==2062){$sql="CREATE TABLE core11plandeestudio (core11idversionprograma int NOT NULL, core11idcurso int NOT NULL, core11id int NULL DEFAULT 0, core11idlineaprof int NULL DEFAULT 0, core11idprograma int NULL DEFAULT 0, core11tiporegistro int NULL DEFAULT 0, core11obligarorio int NULL DEFAULT 0, core11numcreditos int NULL DEFAULT 0, core11nivelaplica int NULL DEFAULT 0, core11idprerequisito int NULL DEFAULT 0, core11idcorrequisito int NULL DEFAULT 0, core11fechaingresa int NULL DEFAULT 0, core11areaconocimiento int NULL DEFAULT 0, core11componeteconoce int NULL DEFAULT 0, core11ofertaperacacorto varchar(1) NULL, core11homologable varchar(1) NULL, core11habilitable varchar(1) NULL, core11porsuficiencia varchar(1) NULL, core11notaaprobatoria Decimal(15,2) NULL DEFAULT 3)";}
	if ($dbversion==2063){$sql="ALTER TABLE core11plandeestudio ADD PRIMARY KEY(core11id)";}
	if ($dbversion==2064){$sql="ALTER TABLE core11plandeestudio ADD UNIQUE INDEX core11plandeestudio_id(core11idversionprograma, core11idcurso)";}
	if ($dbversion==2065){$sql="ALTER TABLE core11plandeestudio ADD INDEX core11plandeestudio_padre(core11idversionprograma)";}
	if ($dbversion==2066){$sql="agregamodulo|2291|22|Perfiles [CORE]|1";}
	if ($dbversion==2067){$sql=$u09."(2291, 1, 'Perfiles', 'unadperfil.php', 2, 2291, 'S', '', 'Usuarios')";}
	if ($dbversion==2068){$sql="agregamodulo|2292|22|Usuarios [CORE]|1";}
	if ($dbversion==2069){$sql=$u09."(2292, 1, 'Usuarios', 'unadusuarios.php', 1, 2292, 'S', '', '')";}
	if ($dbversion==2070){$sql="ALTER TABLE unae06procesonc ADD unae06idsistema int NOT NULL DEFAULT 17";}
	if ($dbversion==2071){$sql="ALTER TABLE cara01encuesta ADD cara01modoaginacion int NULL DEFAULT 0";}	
	if ($dbversion==2072){$sql="ALTER TABLE ofer05agenda ADD ofer05idtipoagenda int NULL DEFAULT 0";}
	if ($dbversion==2073){$sql="CREATE TABLE ofer61tipoagenda (ofer61id int NOT NULL, ofer61nombre varchar(50) NULL)";}
	if ($dbversion==2074){$sql="ALTER TABLE ofer61tipoagenda ADD PRIMARY KEY(ofer61id)";}
	if ($dbversion==2075){$sql="INSERT INTO ofer61tipoagenda (ofer61id, ofer61nombre) VALUES (0, 'Estandar'), (1, 'Actividades al 100'), (2, 'Cualitativa')";}
	if ($dbversion==2076){$sql="ALTER TABLE ofer03cursounidad CHANGE ofer03nombre ofer03nombre VARCHAR(150)";}
	if ($dbversion==2077){$sql="CREATE TABLE core22nivelprograma (core22id int NOT NULL, core22nombre varchar(50) NULL)";}
	if ($dbversion==2078){$sql="ALTER TABLE core22nivelprograma ADD PRIMARY KEY(core22id)";}
	if ($dbversion==2079){$sql="INSERT INTO core22nivelprograma (core22id, core22nombre) VALUES (0, '{Sin Definir}'), (1, 'Bachillerato'), (2, 'Pregrado'), (3, 'PostGrado')";}
	if ($dbversion==2080){$sql="ALTER TABLE core09programa ADD cara09nivelformacion int NULL DEFAULT 0";}
	if ($dbversion==2081){$sql="agregamodulo|2223|22|Aprobación de versiones de programa|1|3|5|6";}
	if ($dbversion==2082){$sql=$u09."(2223, 1, 'Aprobación de versiones de programa', 'coreversionaprueba.php', 2, 2223, 'S', '', '')";}
	if ($dbversion==2083){$sql="CREATE TABLE core00params (core00id int NOT NULL, core00idperfildecano int NULL DEFAULT 0, core00idperfiladminescuela int NULL DEFAULT 0, core00idperfilliderprog int NULL DEFAULT 0, core00idperfiltutor int NULL DEFAULT 0)";}
	if ($dbversion==2084){$sql="ALTER TABLE core00params ADD PRIMARY KEY(core00id)";}
	if ($dbversion==2085){$sql="agregamodulo|2200|22|Parámetros|1|3";}
	if ($dbversion==2086){$sql=$u09."(2200, 1, 'Parámetros', 'coreparams.php', 2, 2299, 'S', '', '')";}
	if ($dbversion==2087){$sql="INSERT INTO core00params (core00id, core00idperfildecano, core00idperfiladminescuela, core00idperfilliderprog, core00idperfiltutor) VALUES (1, 0, 0, 0, 0)";}
	if ($dbversion==2088){$sql="ALTER TABLE core11plandeestudio ADD core11idorigencontprog int NULL DEFAULT 0, ADD core11idarchivocontprog int NULL DEFAULT 0";}
	if ($dbversion==2089){$sql="CREATE TABLE core25histestadoverprog (core25idversionprograma int NOT NULL, core25consec int NOT NULL, core25id int NULL DEFAULT 0, core25idestadoprevio VARCHAR(1) NULL DEFAULT '', core25idestadodestino VARCHAR(1) NULL DEFAULT '', core25fecha int NULL DEFAULT 0, core25idusuario int NULL DEFAULT 0, core25anotacion Text NULL)";}
	if ($dbversion==2090){$sql="ALTER TABLE core25histestadoverprog ADD PRIMARY KEY(core25id)";}
	if ($dbversion==2091){$sql="ALTER TABLE core25histestadoverprog ADD UNIQUE INDEX core25histestadoverprog_id(core25idversionprograma, core25consec)";}
	if ($dbversion==2092){$sql="ALTER TABLE core25histestadoverprog ADD INDEX core25histestadoverprog_padre(core25idversionprograma)";}
	if ($dbversion==2093){$sql="agregamodulo|2225|22|Historico de cambios de estado|1|2|3|4|5|6|8";}
	if ($dbversion==2094){$sql="CREATE TABLE core24estadoverprog (core24id varchar(1) NOT NULL, core24nombre varchar(50) NULL)";}
	if ($dbversion==2095){$sql="ALTER TABLE core24estadoverprog ADD PRIMARY KEY(core24id)";}
	if ($dbversion==2096){$sql="INSERT INTO core24estadoverprog (core24id, core24nombre) VALUES ('N', 'En Alistamiento'), ('R', 'En Revisión'), ('S', 'Vigente'), ('X', 'Vencido')";}
	if ($dbversion==2097){$sql="ALTER TABLE core01estprograma ADD core01avanceplanest Decimal(15,2) NULL DEFAULT 0";}
	if ($dbversion==2098){$sql="ALTER TABLE core10programaversion ADD core10numcredelecgenerales int NULL DEFAULT 0, ADD core10numcredelecescuela int NULL DEFAULT 0, ADD core10numcredelecprograma int NULL DEFAULT 0";}
	if ($dbversion==2099){$sql="UPDATE core10programaversion SET core10numcredelecprograma=core10numcredelectivos";}
	if ($dbversion==2100){$sql="ALTER TABLE core10programaversion ADD core10numcredeleccomplem int NULL DEFAULT 0";}
	}
if (($dbversion>2100)&&($dbversion<2201)){
	if ($dbversion==2102){$sql="UPDATE core13tiporegistroprog SET core13nombre='Electivo D. Específico' WHERE core13id=2";}
	if ($dbversion==2103){$sql="CREATE TABLE core26espejos (core26idzona int NOT NULL, core26idescuela int NOT NULL, core26idtipoespejo int NOT NULL, core26idtercero int NOT NULL, core26id int NULL DEFAULT 0, core26vigente varchar(1) NULL)";}
	if ($dbversion==2104){$sql="ALTER TABLE core26espejos ADD PRIMARY KEY(core26id)";}
	if ($dbversion==2105){$sql="ALTER TABLE core26espejos ADD UNIQUE INDEX core26espejos_id(core26idzona, core26idescuela, core26idtipoespejo, core26idtercero)";}
	if ($dbversion==2103){$sql="CREATE TABLE core26espejos (core26idzona int NOT NULL, core26idtipoespejo int NOT NULL, core26idtercero int NOT NULL, core26id int NULL DEFAULT 0, core26vigente varchar(1) NULL)";}
	if ($dbversion==2104){$sql="ALTER TABLE core26espejos ADD PRIMARY KEY(core26id)";}
	if ($dbversion==2105){$sql="ALTER TABLE core26espejos ADD UNIQUE INDEX core26espejos_id(core26idzona, core26idtipoespejo, core26idtercero)";}
	if ($dbversion==2106){$sql="agregamodulo|2226|22|Espejos zonales|1|2|3|4|5|6|8";}
	if ($dbversion==2107){$sql=$u09."(2226, 1, 'Espejos zonales', 'coreespejos.php', 1, 2226, 'S', '', '')";}
	if ($dbversion==2108){$sql="CREATE TABLE core27tipoespejo (core27consec int NOT NULL, core27id int NULL DEFAULT 0, core27vigente varchar(1) NULL, core27nombre varchar(100) NULL, core27idperfil int NULL DEFAULT 0)";}
	if ($dbversion==2109){$sql="ALTER TABLE core27tipoespejo ADD PRIMARY KEY(core27id)";}
	if ($dbversion==2110){$sql="ALTER TABLE core27tipoespejo ADD UNIQUE INDEX core27tipoespejo_id(core27consec)";}
	if ($dbversion==2111){$sql="agregamodulo|2227|22|Tipos de espejos|1|2|3|4|5|6|8";}
	if ($dbversion==2112){$sql=$u09."(2227, 1, 'Tipos de espejos', 'coretipoespejo.php', 2, 2227, 'S', '', '')";}
	if ($dbversion==2113){$sql="CREATE TABLE core28electivos (core28idperaca int NOT NULL, core28idescuela int NULL DEFAULT 0, core28idcurso int NULL DEFAULT 0, core28id int NULL DEFAULT 0, core28ofertado varchar(1) NULL)";}
	if ($dbversion==2114){$sql="ALTER TABLE core28electivos ADD PRIMARY KEY(core28id)";}
	if ($dbversion==2115){$sql="ALTER TABLE core28electivos ADD UNIQUE INDEX core28electivos_id(core28idperaca, core28idescuela, core28idcurso)";}
	if ($dbversion==2116){$sql="agregamodulo|2228|22|Electivos básicos comúnes|1|2|3|4|5|6|8|12";}
	if ($dbversion==2117){$sql=$u09."(2228, 1, 'Electivos básicos comúnes', 'coreelectivobc.php', 1, 2228, 'S', '', '')";}
	if ($dbversion==2118){$sql="agregamodulo|2229|22|Electivos disciplinares comúnes|1|2|3|4|5|6|8|12";}
	if ($dbversion==2119){$sql=$u09."(2229, 1, 'Electivos disciplinares comúnes', 'coreelectivodi.php', 1, 2229, 'S', '', '')";}
	if ($dbversion==2120){$sql="CREATE TABLE core22gradohistorialest (core22idestprograma int NOT NULL, core22consec int NOT NULL, core22id int NULL DEFAULT 0, core22idestadoorigen int NULL DEFAULT 0, core22idestadodestino int NULL DEFAULT 0, core22fecha int NULL DEFAULT 0, core22anotacion Text NULL)";}
	if ($dbversion==2121){$sql="ALTER TABLE core22gradohistorialest ADD PRIMARY KEY(core22id)";}
	if ($dbversion==2122){$sql="ALTER TABLE core22gradohistorialest ADD UNIQUE INDEX core22gradohistorialest_id(core22idestprograma, core22consec)";}
	if ($dbversion==2123){$sql="ALTER TABLE core22gradohistorialest ADD INDEX core22gradohistorialest_padre(core22idestprograma)";}
	if ($dbversion==2124){$sql="agregamodulo|2222|22|Estudiante - Cambios de estado|1";}
	if ($dbversion==2125){$sql="CREATE TABLE core23gradohistorialactor (core23idestprograma int NOT NULL, core23consec int NOT NULL, core23id int NULL DEFAULT 0, core23idactor int NULL DEFAULT 0, core23idtercerosale int NULL DEFAULT 0, core23idterceroentra int NULL DEFAULT 0, core23fecha int NULL DEFAULT 0)";}
	if ($dbversion==2126){$sql="ALTER TABLE core23gradohistorialactor ADD PRIMARY KEY(core23id)";}
	if ($dbversion==2127){$sql="ALTER TABLE core23gradohistorialactor ADD UNIQUE INDEX core23gradohistorialactor_id(core23idestprograma, core23consec)";}
	if ($dbversion==2128){$sql="ALTER TABLE core23gradohistorialactor ADD INDEX core23gradohistorialactor_padre(core23idestprograma)";}
	if ($dbversion==2129){$sql="agregamodulo|2224|22|Estudiante - Cambios de actores|1";}
	if ($dbversion==2130){$sql="ALTER TABLE core01estprograma ADD core01idrevision int NULL DEFAULT 0, ADD core01fecharevision int NULL DEFAULT 0, ADD core01gradoestado int NULL DEFAULT 0, ADD core01gradoidopcion int NULL DEFAULT 0, ADD core01gradofechainscripcion int NULL DEFAULT 0, ADD core01gradoidorigenprop int NULL DEFAULT 0, ADD core01gradoidarchivoprop int NULL DEFAULT 0, ADD core01gradoidrevisor int NULL DEFAULT 0, ADD core01gradodirector int NULL DEFAULT 0, ADD core01gradojurado1 int NULL DEFAULT 0, ADD core01gradojurado2 int NULL DEFAULT 0, ADD core01gradojurado3 int NULL DEFAULT 0, ADD core01gradoidorigenacta int NULL DEFAULT 0, ADD core01gradoidarchivoacta int NULL DEFAULT 0, ADD core01gradonotadocumento Decimal(15,2) NULL DEFAULT 0, ADD core01gradonotasustenta Decimal(15,2) NULL DEFAULT 0, ADD core01gradonotaproyecto Decimal(15,2) NULL DEFAULT 0, ADD core01gradonotafinal Decimal(15,2) NULL DEFAULT 0, ADD core01gradofecha int NULL DEFAULT 0, ADD core01gradonumacta int NULL DEFAULT 0, ADD core01gradonumfolio int NULL DEFAULT 0, ADD core01gradocodigoverifica varchar(50) NULL DEFAULT ''";}
	if ($dbversion==2131){$sql=$u08."(2203, 'Grados', 'gm.php?id=2203', 'Grados', 'Degrees', 'Graus')";}
	if ($dbversion==2132){$sql="agregamodulo|2230|22|Asignación de revisor|1|3|5|6";}
	if ($dbversion==2133){$sql="CREATE TABLE core31gradoactor (core31id int NOT NULL, core31nombre varchar(100) NULL, core31idperfil int NULL DEFAULT 0)";}
	if ($dbversion==2134){$sql="ALTER TABLE core31gradoactor ADD PRIMARY KEY(core31id)";}
	if ($dbversion==2135){$sql="INSERT INTO core31gradoactor (core31id, core31nombre, core31idperfil) VALUES (1, 'Revisor', 0), (2, 'Director', 0), (3, 'Jurado 1', 0), (4, 'Jurado 2', 0), (5, 'Jurado 3', 0)";}
	if ($dbversion==2136){$sql="agregamodulo|2231|22|Tipos de actores en grados|1|3|5|6";}
	if ($dbversion==2137){$sql=$u09."(2231, 1, 'Tipos de actores en grados', 'coreactorgrado.php', 2, 2231, 'S', '', '')";}
	if ($dbversion==2138){$sql="CREATE TABLE core32gradoestado (core32id int NOT NULL, core32nombre varchar(100) NULL)";}
	if ($dbversion==2139){$sql="ALTER TABLE core32gradoestado ADD PRIMARY KEY(core32id)";}
	if ($dbversion==2140){$sql="INSERT INTO core32gradoestado (core32id, core32nombre) VALUES (0, 'Cerrado'), (1, 'Para propuesta'), (2, 'Propuesta enviada'), (3, 'Propuesta rechazada por revisor'), (4, 'Propuesta enviada a comité'), (5, 'Rechazada por comité'), (6, 'Aprobada por comité'), (7, 'Avalada por director'), (8, 'Jurados asignados'), (9, 'En revision por jurados'), (10, 'Ajustes del documento a sustentar'), (11, 'Aprobada para sustentar'), (12, 'Programada para sustentar'), (13, 'En evaluación de sustestación'), (14, 'Aprobada para grado')";}
	if ($dbversion==2141){$sql=$u09."(2230, 1, 'Asignación de revisor', 'coreactarevisor.php', 2203, 2230, 'S', '', '')";}
	if ($dbversion==2142){$sql="agregamodulo|2233|22|Aval de la propuesta inicial|1|3|5|6";}
	if ($dbversion==2143){$sql=$u09."(2233, 1, 'Aval de la propuesta inicial', 'coreactaaval.php', 2203, 2233, 'S', '', '')";}
	if ($dbversion==2144){$sql="agregamodulo|2234|22|Asigación de director|1|3|5|6";}
	if ($dbversion==2145){$sql=$u09."(2234, 1, 'Asigación de director', 'coreactadirector.php', 2203, 2234, 'S', '', '')";}
	if ($dbversion==2146){$sql="agregamodulo|2235|22|Aval de documento final|1|3|5|6";}
	if ($dbversion==2147){$sql=$u09."(2235, 1, 'Aval de documento final', 'coreactadocfin.php', 2203, 2235, 'S', '', '')";}
	if ($dbversion==2148){$sql="agregamodulo|2236|22|Asignación de jurados|1|3|5|6";}
	if ($dbversion==2149){$sql=$u09."(2236, 1, 'Asignación de jurados', 'coreactajurados.php', 2203, 2236, 'S', '', '')";}
	if ($dbversion==2150){$sql="agregamodulo|2237|22|Evaluación de la opción de grado|1|3|5|6";}
	if ($dbversion==2151){$sql=$u09."(2237, 1, 'Evaluación de la opción de grado', 'coreactaevalua.php', 2203, 2237, 'S', '', '')";}
	if ($dbversion==2152){$sql="CREATE TABLE core38opciongrado (core38id int NOT NULL, core38nombre varchar(100) NULL, core38avancenecesario Decimal(15,2) NULL DEFAULT 0, core38vigente varchar(1) NULL, core38nivelacademico int NULL DEFAULT 0)";}
	if ($dbversion==2153){$sql="ALTER TABLE core38opciongrado ADD PRIMARY KEY(core38id)";}
	if ($dbversion==2154){$sql="INSERT INTO core38opciongrado (core38id, core38nombre, core38avancenecesario, core38vigente, core38nivelacademico) VALUES (1, 'Proyecto aplicado', 75, 'S', 2), (2, 'Proyecto de investigación', 75, 'S', 2), (3, 'Monografía', 75, 'S', 2), (4, 'Diplomado de profuncización', 90, 'S', 2), (5, 'Creditos de postgrado', 90, 'S', 2), (6, 'Pasantia', 90, 'S', 2), (7, 'Creación de obra', 90, 'S', 2), (11, 'Proyecto aplicado', 40, 'S', 3), (12, 'Proyecto de investigación', 40, 'S', 3), (13, 'Monografía', 40, 'S', 3)";}
	if ($dbversion==2155){$sql="ALTER TABLE bita01tiposolicitud ADD bita01ambito int NULL DEFAULT 0, ADD bita01idgrupotrabajo int NULL DEFAULT 0";}
	if ($dbversion==2160){$sql="agregamodulo|1526|15|Tipos de solicitud - Perfiles|1|2|3|4|5|6|8";}
	if ($dbversion==2161){$sql="CREATE TABLE bita27equipotrabajo (bita27consec int NOT NULL, bita27id int NULL DEFAULT 0, bita27nombre varchar(100) NULL, bita27idlider int NULL DEFAULT 0, bita27idperfil int NULL DEFAULT 0)";}
	if ($dbversion==2162){$sql="ALTER TABLE bita27equipotrabajo ADD PRIMARY KEY(bita27id)";}
	if ($dbversion==2163){$sql="ALTER TABLE bita27equipotrabajo ADD UNIQUE INDEX bita27equipotrabajo_id(bita27consec)";}
	if ($dbversion==2164){$sql="agregamodulo|1527|15|Equipos de trabajo|1|2|3|4|5|6|8";}
	if ($dbversion==2165){$sql=$u09."(1527, 1, 'Equipos de trabajo', 'bitaequipotrabajo.php', 1, 1527, 'S', '', '')";}
	if ($dbversion==2166){$sql="CREATE TABLE bita28eqipoparte (bita28idequipotrab int NOT NULL, bita28idtercero int NULL DEFAULT 0, bita28id int NULL DEFAULT 0, bita28activo varchar(1) NULL, bita28fechaingreso int NULL DEFAULT 0, bita28fechasalida int NULL DEFAULT 0)";}
	if ($dbversion==2167){$sql="ALTER TABLE bita28eqipoparte ADD PRIMARY KEY(bita28id)";}
	if ($dbversion==2168){$sql="ALTER TABLE bita28eqipoparte ADD UNIQUE INDEX bita28eqipoparte_id(bita28idequipotrab, bita28idtercero)";}
	if ($dbversion==2169){$sql="ALTER TABLE bita28eqipoparte ADD INDEX bita28eqipoparte_padre(bita28idequipotrab)";}
	if ($dbversion==2170){$sql="agregamodulo|1528|15|Equipos de trabajo - Integrantes|1|2|3|4|5|6|8";}
	if ($dbversion==2171){$sql="ALTER TABLE bita04bitacora ADD bita04idequipotrabajo int NULL DEFAULT 0, ADD bita04idsupervisor int NULL DEFAULT 0, ADD bita04infocomplemento Text NULL, ADD bita04mailrespusta varchar(50) NULL DEFAULT ''";}
	if ($dbversion==2172){$sql="ALTER TABLE bita05bitacoranota ADD bita05visiblesolicitante varchar(1) NULL DEFAULT 'N', ADD bita05idorigen int NULL DEFAULT 0, ADD bita05idarchivo int NULL DEFAULT 0";}
	if ($dbversion==2173){$sql="CREATE TABLE bita29bitahistestado (bita29idbitacora int NOT NULL, bita29consec int NOT NULL, bita29id int NULL DEFAULT 0, bita29idestadoorigen int NULL DEFAULT 0, bita29idestadofin int NULL DEFAULT 0, bita29idusuario int NULL DEFAULT 0, bita29fecha int NULL DEFAULT 0, bita29hora int NULL DEFAULT 0)";}
	if ($dbversion==2174){$sql="ALTER TABLE bita29bitahistestado ADD PRIMARY KEY(bita29id)";}
	if ($dbversion==2175){$sql="ALTER TABLE bita29bitahistestado ADD UNIQUE INDEX bita29bitahistestado_id(bita29idbitacora, bita29consec)";}
	if ($dbversion==2176){$sql="ALTER TABLE bita29bitahistestado ADD INDEX bita29bitahistestado_padre(bita29idbitacora)";}
	if ($dbversion==2177){$sql="agregamodulo|1529|15|Bitacora - Cambios de estado|1";}
	if ($dbversion==2178){$sql="ALTER TABLE bita27equipotrabajo ADD bita27correogrupo varchar(50) NULL";}
	if ($dbversion==2179){$sql="DROP TABLE bita26tiposolperfil";}
	if ($dbversion==2180){$sql="ALTER TABLE bita06tema ADD bita06ambito int NULL DEFAULT 0, ADD bita06idgrupotrabajo int NULL DEFAULT 0";}
	if ($dbversion==2181){$sql="mod_quitar|1526";}
	if ($dbversion==2182){$sql="CREATE TABLE bita26temaperfil (bita26idtema int NOT NULL, bita26idperfil int NOT NULL, bita26id int NULL DEFAULT 0, bita26activo varchar(1) NULL)";}
	if ($dbversion==2183){$sql="ALTER TABLE bita26temaperfil ADD PRIMARY KEY(bita26id)";}
	if ($dbversion==2184){$sql="ALTER TABLE bita26temaperfil ADD UNIQUE INDEX bita26temaperfil_id(bita26idtema, bita26idperfil)";}
	if ($dbversion==2185){$sql="ALTER TABLE bita26temaperfil ADD INDEX bita26temaperfil_padre(bita26idtema)";}
	if ($dbversion==2186){$sql="agregamodulo|1526|15|Temas de solicitud - Perfiles|1|2|3|4|5|6|8";}
	if ($dbversion==2187){$sql="INSERT INTO bita27equipotrabajo (bita27consec, bita27id, bita27nombre, bita27idlider, bita27idperfil) VALUES (0, 0, '{Ninguno}', 0, 0)";}
	if ($dbversion==2188){$sql="ALTER TABLE bita04bitacora ADD bita04idzona int NULL DEFAULT 0, ADD bita04idcentro int NULL DEFAULT 0, ADD bita04iddependencia int NULL DEFAULT 0, ADD bita04fechavence int NULL DEFAULT 0, ADD bita04minutovence int NULL DEFAULT 0, ADD bita04diasrespuesta int NULL DEFAULT 0, ADD bita04minutosrespuesta int NULL DEFAULT 0";}
	if ($dbversion==2189){$sql="CREATE TABLE bita30bitahistrespon (bita30idbitacora int NOT NULL, bita30consec int NOT NULL, bita30id int NULL DEFAULT 0, bita30idresponsable int NULL DEFAULT 0, bita30fechafin int NULL DEFAULT 0, bita30horafin int NULL DEFAULT 0, bita30nota Text NULL)";}
	if ($dbversion==2190){$sql="ALTER TABLE bita30bitahistrespon ADD PRIMARY KEY(bita30id)";}
	if ($dbversion==2191){$sql="ALTER TABLE bita30bitahistrespon ADD UNIQUE INDEX bita30bitahistrespon_id(bita30idbitacora, bita30consec)";}
	if ($dbversion==2192){$sql="ALTER TABLE bita30bitahistrespon ADD INDEX bita30bitahistrespon_padre(bita30idbitacora)";}
	if ($dbversion==2193){$sql="agregamodulo|1530|15|Bitacora - Cambios de responsable|1";}
	if ($dbversion==2194){$sql="ALTER TABLE core01estprograma ADD core01numcredelecgenerales int NULL DEFAULT 0, ADD core01numcredelecescuela int NULL DEFAULT 0, ADD core01numcredelecprograma int NULL DEFAULT 0, ADD core01numcredeleccomplem int NULL DEFAULT 0, ADD core01numcredelecgeneralesaprob int NULL DEFAULT 0, ADD core01numcredelecescuelaaprob int NULL DEFAULT 0, ADD core01numcredelecprogramaaaprob int NULL DEFAULT 0, ADD core01numcredeleccomplemaprob int NULL DEFAULT 0";}
	if ($dbversion==2195){$sql="ALTER TABLE core13tiporegistroprog ADD core13fijo int NULL DEFAULT 1, ADD core13orden int NULL DEFAULT 0";}
	if ($dbversion==2196){$sql="DELETE FROM core13tiporegistroprog";}
	if ($dbversion==2197){$sql="INSERT INTO core13tiporegistroprog (core13id, core13nombre, core13fijo, core13orden) VALUES (0, 'Básico', 1, 1), (1, 'Específico', 1, 2), (2, 'Electivo Disciplinar Específico', 1, 5), (3, 'Requisito de grado', 1, 7), (4, 'Electivo Disciplinar Complementario', 1, 6), (5, 'Electivo Básico Común', 0, 3), (6, 'Electivo Disciplinar Común', 0, 4)";}
	if ($dbversion==2198){$sql="INSERT INTO unad00config (unad00codigo, unad00nombre, unad00valor) VALUES ('cron_laboratorio', 'Fecha del CRON DE Laboratorio', 0)";}
	if ($dbversion==2199){$sql="INSERT INTO core13tiporegistroprog (core13id, core13nombre, core13fijo, core13orden) VALUES (7, 'Curso Libre', 0, 8), (8, 'No Categorizado', 0, 9), (9, 'Opcion de grado', 0, 10)";}
	if ($dbversion==2200){$sql="CREATE TABLE unae13enrevision (unae13idia int NOT NULL, unae13idtercero int NOT NULL, unae13id int NULL DEFAULT 0, unae13estado int NULL DEFAULT 0, unae13idrevisa int NULL DEFAULT 0, unae13fecharevisa int NULL DEFAULT 0, unae13dictamen Text NULL, unae13origen varchar(100) NULL, unae13comunes int NULL DEFAULT 0, unad13iporigen varchar(50) NULL)";}
	}
if (($dbversion>2200)&&($dbversion<2301)){
	if ($dbversion==2201){$sql="ALTER TABLE unae13enrevision ADD PRIMARY KEY(unae13id)";}
	if ($dbversion==2202){$sql="ALTER TABLE unae13enrevision ADD UNIQUE INDEX unae13enrevision_id(unae13idia, unae13idtercero)";}
	if ($dbversion==2203){$sql="agregamodulo|213|1|Revisión de accesos|1|3|5|6";}
	if ($dbversion==2204){$sql=$u09."(213, 1, 'Revisión de accesos', 'unadrevacceso.php', 6, 213, 'S', '', '')";}
	if ($dbversion==2205){$sql=$unad70."(2252,2250,'core50convenios','core50id','core50tipoconvenio','El dato esta incluido en Convenios', '')";}
	if ($dbversion==2206){$sql="CREATE TABLE core50convenios (core50consec int NOT NULL, core50id int NULL DEFAULT 0, core50estado varchar(1) NULL, core50tipoconvenio int NULL DEFAULT 0, core50nombre varchar(250) NULL, core50fechainico int NULL DEFAULT 0, core50fechafinal int NULL DEFAULT 0, core50idaliado1 int NULL DEFAULT 0, core50numcontrato1 varchar(50) NULL, core50porccargamatri1 Decimal(15,2) NULL DEFAULT 0, core50valorpagomatri1 Decimal(15,2) NULL DEFAULT 0, core50idaliado2 int NULL DEFAULT 0, core50porccargamatri2 Decimal(15,2) NULL DEFAULT 0, core50idaliado3 int NULL DEFAULT 0, core50porccargamatri3 Decimal(15,2) NULL DEFAULT 0, core50porcunad Decimal(15,2) NULL DEFAULT 0, core50porcestudiante Decimal(15,2) NULL DEFAULT 0, core50porcdescuento Decimal(15,2) NULL DEFAULT 0, core50limitadogeografico varchar(1) NULL, core50idsupervisor int NULL DEFAULT 0, core50vigilarpromedio int NULL DEFAULT 0, core50notaminima Decimal(15,2) NULL DEFAULT 0, core50descripcioncorta Text NULL)";}
	if ($dbversion==2207){$sql="ALTER TABLE core50convenios ADD PRIMARY KEY(core50id)";}
	if ($dbversion==2208){$sql="ALTER TABLE core50convenios ADD UNIQUE INDEX core50convenios_id(core50consec)";}
	if ($dbversion==2209){$sql="agregamodulo|2250|22|Convenios|1|2|3|4|5|6|8";}
	if ($dbversion==2210){$sql=$u09."(2250, 1, 'Convenios', 'coreconvenio.php', 2201, 2250, 'S', '', '')";}
	if ($dbversion==2211){$sql="CREATE TABLE core51convenioest (core51idconvenio int NOT NULL, core51idtercero int NOT NULL, core51id int NULL DEFAULT 0, core51fechaingreso int NULL DEFAULT 0, core51activo varchar(1) NULL, core51fecharetiro int NULL DEFAULT 0, core51idprograma int NULL DEFAULT 0, core51peracainicio int NULL DEFAULT 0, core51idmatricula1 int NULL DEFAULT 0, core51vraliado1mat1 Decimal(15,2) NULL DEFAULT 0, core51vraliado2mat1 Decimal(15,2) NULL DEFAULT 0, core51vraliado3mat1 Decimal(15,2) NULL DEFAULT 0, core51estudiantemat1 Decimal(15,2) NULL DEFAULT 0, core51idmatricula2 int NULL DEFAULT 0, core51vraliado1mat2 Decimal(15,2) NULL DEFAULT 0, core51vraliado2mat2 Decimal(15,2) NULL DEFAULT 0, core51vraliado3mat2 Decimal(15,2) NULL DEFAULT 0, core51estudiantemat2 Decimal(15,2) NULL DEFAULT 0, core51idmatricula3 int NULL DEFAULT 0, core51vraliado1mat3 Decimal(15,2) NULL DEFAULT 0, core51vraliado2mat3 Decimal(15,2) NULL DEFAULT 0, core51vraliado3mat3 Decimal(15,2) NULL DEFAULT 0, core51estudiantemat3 Decimal(15,2) NULL DEFAULT 0, core51idmatricula4 int NULL DEFAULT 0, core51vraliado1mat4 Decimal(15,2) NULL DEFAULT 0, core51vraliado2mat4 Decimal(15,2) NULL DEFAULT 0, core51vraliado3mat4 Decimal(15,2) NULL DEFAULT 0, core51estudiantemat4 Decimal(15,2) NULL DEFAULT 0, core51idmatricula5 int NULL DEFAULT 0, core51vraliado1mat5 Decimal(15,2) NULL DEFAULT 0, core51vraliado2mat5 Decimal(15,2) NULL DEFAULT 0, core51vraliado3mat5 Decimal(15,2) NULL DEFAULT 0, core51estudiantemat5 Decimal(15,2) NULL DEFAULT 0, core51idmatricula6 int NULL DEFAULT 0, core51vraliado1mat6 Decimal(15,2) NULL DEFAULT 0, core51vraliado2mat6 Decimal(15,2) NULL DEFAULT 0, core51vraliado3mat6 Decimal(15,2) NULL DEFAULT 0, core51estudiantemat6 Decimal(15,2) NULL DEFAULT 0, core51idmatricula7 int NULL DEFAULT 0, core51vraliado1mat7 Decimal(15,2) NULL DEFAULT 0, core51vraliado2mat7 Decimal(15,2) NULL DEFAULT 0, core51vraliado3mat7 Decimal(15,2) NULL DEFAULT 0, core51estudiantemat7 Decimal(15,2) NULL DEFAULT 0, core51idmatricula8 int NULL DEFAULT 0, core51vraliado1mat8 Decimal(15,2) NULL DEFAULT 0, core51vraliado2mat8 Decimal(15,2) NULL DEFAULT 0, core51vraliado3mat8 Decimal(15,2) NULL DEFAULT 0, core51estudiantemat8 Decimal(15,2) NULL DEFAULT 0, core51idmatricula9 int NULL DEFAULT 0, core51vraliado1mat9 Decimal(15,2) NULL DEFAULT 0, core51vraliado2mat9 Decimal(15,2) NULL DEFAULT 0, core51vraliado3mat9 Decimal(15,2) NULL DEFAULT 0, core51estudiantemat9 Decimal(15,2) NULL DEFAULT 0, core51idmatricula10 int NULL DEFAULT 0, core51vraliado1mat10 Decimal(15,2) NULL DEFAULT 0, core51vraliado2mat10 Decimal(15,2) NULL DEFAULT 0, core51vraliado3mat10 Decimal(15,2) NULL DEFAULT 0, core51estudiantemat10 Decimal(15,2) NULL DEFAULT 0, core51idmatricula11 int NULL DEFAULT 0, core51vraliado1mat11 Decimal(15,2) NULL DEFAULT 0, core51vraliado2mat11 Decimal(15,2) NULL DEFAULT 0, core51vraliado3mat11 Decimal(15,2) NULL DEFAULT 0, core51estudiantemat11 Decimal(15,2) NULL DEFAULT 0, core51idmatricula12 int NULL DEFAULT 0, core51vraliado1mat12 Decimal(15,2) NULL DEFAULT 0, core51vraliado2mat12 Decimal(15,2) NULL DEFAULT 0, core51vraliado3mat12 Decimal(15,2) NULL DEFAULT 0, core51estudiantemat12 Decimal(15,2) NULL DEFAULT 0)";}
	if ($dbversion==2212){$sql="ALTER TABLE core51convenioest ADD PRIMARY KEY(core51id)";}
	if ($dbversion==2213){$sql="ALTER TABLE core51convenioest ADD UNIQUE INDEX core51convenioest_id(core51idconvenio, core51idtercero)";}
	if ($dbversion==2214){$sql="ALTER TABLE core51convenioest ADD INDEX core51convenioest_padre(core51idconvenio)";}
	if ($dbversion==2215){$sql="agregamodulo|2251|22|Convenios - Beneficiarios|1|2|3|4|5|6|8";}
	if ($dbversion==2216){$sql="CREATE TABLE core52tipoconvenio (core52consec int NOT NULL, core52id int NULL DEFAULT 0, core52vigente varchar(1) NULL, core52orden int NULL DEFAULT 0, core52nombre varchar(200) NULL)";}
	if ($dbversion==2217){$sql="ALTER TABLE core52tipoconvenio ADD PRIMARY KEY(core52id)";}
	if ($dbversion==2218){$sql="ALTER TABLE core52tipoconvenio ADD UNIQUE INDEX core52tipoconvenio_id(core52consec)";}
	if ($dbversion==2219){$sql="agregamodulo|2252|22|Tipos de convenios|1|2|3|4|5|6|8";}
	if ($dbversion==2220){$sql=$u09."(2252, 1, 'Tipos de convenios', 'coretipoconvenio.php', 2, 2252, 'S', '', '')";}
	if ($dbversion==2221){$sql="agregamodulo|2451|24|Actividades por estudiante|1|3|4|5|6";}
	if ($dbversion==2222){$sql=$u09."(2451, 1, 'Actividades por estudiante', 'cecarevestudiante.php', 11, 2451, 'S', '', '')";}
	if ($dbversion==2223){$sql="agregamodulo|2404|24|Revisión de integración|1|3|5|6";}
	if ($dbversion==2224){$sql=$u09."(2404, 1, 'Revisión de integración', 'cecarevintegra.php', 7, 2404, 'S', '', '')";}
	if ($dbversion==2225){$sql="ALTER TABLE unad63bloqueo ADD unad63idconvenio int NULL DEFAULT 0";}
	if ($dbversion==2226){$sql="ALTER TABLE ofer08oferta ADD ofer08idacomanamento int NULL DEFAULT 0";}
	if ($dbversion==2227){$sql="agregamodulo|2240|22|Acompañamiento tutorial|1|3|5|6";}
	if ($dbversion==2228){$sql=$u09."(2240, 1, 'Acompañamiento tutorial', 'coredirectores.php', 2201, 2240, 'S', '', '')";}
	if ($dbversion==2229){$sql="ALTER TABLE core00params ADD ceca00idperfildirector int NULL DEFAULT 0, ADD ceca00idperfiltutor int NULL DEFAULT 0";}
	if ($dbversion==2230){$sql="agregamodulo|2405|24|Parametros|1|3";}
	if ($dbversion==2231){$sql=$u09."(2405, 1, 'Parametros', 'cecaparams.php', 2, 2405, 'S', '', '')";}
	if ($dbversion==2232){$sql="ALTER TABLE core22nivelprograma ADD core22orden int NULL DEFAULT 0, ADD core22grupo int NULL DEFAULT 0";}
	if ($dbversion==2233){$sql="DELETE FROM core22nivelprograma";}
	if ($dbversion==2234){$sql="INSERT INTO core22nivelprograma (core22id, core22nombre, core22orden, core22grupo) VALUES (0, '{Sin Definir}', 0, 0), (1, 'Bachillerato', 1, 1), (4, 'Tecnología', 2, 2), (2, 'Profesional', 3, 2), (3, 'Especialización', 4, 3), (5, 'Maestria', 5, 3), (6, 'Doctorado', 6, 3), (7, 'PostDoctorado', 7, 3)";}
	if ($dbversion==2235){$sql="CREATE TABLE core29estadogrupo (core29id int NOT NULL, core29nombre varchar(50) NULL)";}
	if ($dbversion==2236){$sql="ALTER TABLE core29estadogrupo ADD PRIMARY KEY(core29id)";}
	if ($dbversion==2237){$sql="INSERT INTO core29estadogrupo (core29id, core29nombre) VALUES (0, 'Sin definir'), (1, 'Solicitando ingreso'), (2, 'Aprobado'), (7, 'Lider del grupo')";}
	if ($dbversion==2238){$sql="agregamodulo|2406|24|Seguimiento académico|1|5|6|12|1701|1707";}
	if ($dbversion==2239){$sql=$u09."(2406, 1, 'Seguimiento académico', 'cecaseguimiento.php', 2202, 2406, 'S', '', '')";}
	if ($dbversion==2240){$sql="ALTER TABLE core16actamatricula ADD core16estado int NULL DEFAULT 0";}
	if ($dbversion==2241){$sql=$u08."(1512, 'Documentación', 'gm.php?id=1512', 'Documentación', 'Documentation', 'Documentação')";}
	if ($dbversion==2242){$sql="CREATE TABLE aure51bitacora (aure51idsistema int NOT NULL, aure51consec int NOT NULL, aure51id int NULL DEFAULT 0, aure51estado varchar(1) NULL, aure51fecha int NULL DEFAULT 0, aure51horaini int NULL DEFAULT 0, aure51minini int NULL DEFAULT 0, aure51horafin int NULL DEFAULT 0, aure51minfin int NULL DEFAULT 0, aure51lugar varchar(250) NULL, aure51actividades Text NULL, aure51objetivo Text NULL, aure51resultado Text NULL)";}
	if ($dbversion==2243){$sql="ALTER TABLE aure51bitacora ADD PRIMARY KEY(aure51id)";}
	if ($dbversion==2244){$sql="ALTER TABLE aure51bitacora ADD UNIQUE INDEX aure51bitacora_id(aure51idsistema, aure51consec)";}
	if ($dbversion==2245){$sql="agregamodulo|251|2|Bitacora de desarrollo|1|2|3|4|5|6|8";}
	if ($dbversion==2246){$sql=$u09."(251, 1, 'Bitacora de desarrollo', 'aurebitacora.php', 1512, 251, 'S', '', '')";}
	if ($dbversion==2247){$sql="CREATE TABLE aure52bitaparticipa (aure52idbitacora int NOT NULL, aure52idtercero int NOT NULL, aure52id int NULL DEFAULT 0, aure52activo varchar(1) NULL)";}
	if ($dbversion==2248){$sql="ALTER TABLE aure52bitaparticipa ADD PRIMARY KEY(aure52id)";}
	if ($dbversion==2249){$sql="ALTER TABLE aure52bitaparticipa ADD UNIQUE INDEX aure52bitaparticipa_id(aure52idbitacora, aure52idtercero)";}
	if ($dbversion==2250){$sql="agregamodulo|252|2|BD - Participantes|1|2|3|4|5|6|8";}
	if ($dbversion==2251){$sql="CREATE TABLE aure53historias (aure53idbitacora int NOT NULL, aure53consec int NOT NULL, aure53id int NULL DEFAULT 0, aure53idsistema int NULL DEFAULT 0, aure53titulo varchar(250) NULL, aure53usuarioshist Text NULL, aure53prioridad int NULL DEFAULT 0, aure53riesgo int NULL DEFAULT 0, aure53semanaest int NULL DEFAULT 0, aure53diasest int NULL DEFAULT 0, aure53iteracionasig int NULL DEFAULT 0, aure53idresponsable int NULL DEFAULT 0, aure53descripcion Text NULL, aure53observaciones Text NULL)";}
	if ($dbversion==2252){$sql="ALTER TABLE aure53historias ADD PRIMARY KEY(aure53id)";}
	if ($dbversion==2253){$sql="ALTER TABLE aure53historias ADD UNIQUE INDEX aure53historias_id(aure53idbitacora, aure53consec)";}
	if ($dbversion==2254){$sql="agregamodulo|253|2|BD - Historias de usuario|1|2|3|4|5|6|8";}
	if ($dbversion==2255){$sql="CREATE TABLE aure54tarea (aure54idbitacora int NOT NULL, aure54idhistoria int NOT NULL, aure54consec int NOT NULL, aure54id int NULL DEFAULT 0, aure54idsistema int NULL DEFAULT 0, aure54estado varchar(1) NULL, aure54idtipotarea int NULL DEFAULT 0, aure54semanasest int NULL DEFAULT 0, aure54diasest int NULL DEFAULT 0, aure54fechainicio int NULL DEFAULT 0, aure54fechafinal int NULL DEFAULT 0, aure54idresponsable int NULL DEFAULT 0, aure54descripcion Text NULL)";}
	if ($dbversion==2256){$sql="ALTER TABLE aure54tarea ADD PRIMARY KEY(aure54id)";}
	if ($dbversion==2257){$sql="ALTER TABLE aure54tarea ADD UNIQUE INDEX aure54tarea_id(aure54idbitacora, aure54idhistoria, aure54consec)";}
	if ($dbversion==2258){$sql="agregamodulo|254|2|BD - Tareas de ingeniería|1|2|3|4|5|6|8";}
	if ($dbversion==2259){$sql="CREATE TABLE aure56pruebas (aure54idbitacora int NOT NULL, aure56idhistoria int NOT NULL, aure56consec int NOT NULL, aure56id int NULL DEFAULT 0, aure56idsistema int NULL DEFAULT 0, aure56condiciones Text NULL, aure56pasos Text NULL, aure56resultadoesp Text NULL, aure56evaluacion varchar(1) NULL)";}
	if ($dbversion==2260){$sql="ALTER TABLE aure56pruebas ADD PRIMARY KEY(aure56id)";}
	if ($dbversion==2261){$sql="ALTER TABLE aure56pruebas ADD UNIQUE INDEX aure56pruebas_id(aure54idbitacora, aure56idhistoria, aure56consec)";}
	if ($dbversion==2262){$sql="agregamodulo|254|2|BD - Pruebas de aceptación|1|2|3|4|5|6|8";}
	if ($dbversion==2263){$sql="CREATE TABLE aure55tarjetas (aure55idsistemas int NOT NULL, aure55consec int NOT NULL, aure55id int NULL DEFAULT 0, aure55fecha int NULL DEFAULT 0, aure55estado varchar(1) NULL, aure55nombreclase varchar(200) NULL, aure55responsabilidades Text NULL, aure55colaboradores Text NULL)";}
	if ($dbversion==2264){$sql="ALTER TABLE aure55tarjetas ADD PRIMARY KEY(aure55id)";}
	if ($dbversion==2265){$sql="ALTER TABLE aure55tarjetas ADD UNIQUE INDEX aure55tarjetas_id(aure55idsistemas, aure55consec)";}
	if ($dbversion==2266){$sql="agregamodulo|255|2|Tarjetas CRC|1|2|3|4|5|6|8";}
	if ($dbversion==2267){$sql=$u09."(255, 1, 'Tarjetas CRC', 'aureacrc.php', 1512, 255, 'S', '', '')";}
	if ($dbversion==2268){$sql="agregamodulo|2241|22|Contenidos analíticos|1";}
	if ($dbversion==2269){$sql=$u09."(2241, 1, 'Contenidos analíticos', 'corecontenidos.php', 1, 2241, 'S', '', '')";}
	if ($dbversion==2270){$sql="CREATE TABLE core42contenidos (core42idcurso int NOT NULL, core42consec int NOT NULL, core42id int NULL DEFAULT 0, core42fecha int NULL DEFAULT 0, core42idarchivogdd int NULL DEFAULT 0, core42idorigen int NULL DEFAULT 0, core42idarchivo int NULL DEFAULT 0)";}
	if ($dbversion==2271){$sql="ALTER TABLE core42contenidos ADD PRIMARY KEY(core42id)";}
	if ($dbversion==2272){$sql="ALTER TABLE core42contenidos ADD UNIQUE INDEX core42contenidos_id(core42idcurso, core42consec)";}
	if ($dbversion==2273){$sql="ALTER TABLE core42contenidos ADD INDEX core42contenidos_padre(core42idcurso)";}
	if ($dbversion==2274){$sql="agregamodulo|2242|22|Contenidos analíticos - Versiones|1|2|3|4|5|6|8";}
	if ($dbversion==2275){$sql="ALTER TABLE core11plandeestudio ADD core11idcontenido int NULL DEFAULT 0";}
	if ($dbversion==2276){$sql="ALTER TABLE core12escuela ADD core12idrespcursocomun int NULL DEFAULT 0";}
	if ($dbversion==2277){$sql=$u01."(26, 'GD', 'Gestión Documental', 'S', 'S', 1, 0, 0)";}
	if ($dbversion==2278){$sql=$u08."(2601, 'Documentos', 'gm.php?id=2601', 'Documentos', 'Documents', 'Documentos')";}
	if ($dbversion==2279){$sql="CREATE TABLE gedo01formatipo (gedo01id int NOT NULL, gedo01nombre varchar(50) NULL)";}
	if ($dbversion==2280){$sql="ALTER TABLE gedo01formatipo ADD PRIMARY KEY(gedo01id)";}
	if ($dbversion==2281){$sql="INSERT INTO gedo01formatipo (gedo01id, gedo01nombre) VALUES (0, 'Único'), (1, 'Periódico'), (2, 'Académico')";}
	if ($dbversion==2282){$sql="CREATE TABLE gedo02tipodoc (gedo02consec int NOT NULL, gedo02id int NULL DEFAULT 0, gedo02nombre varchar(100) NULL, gedo02formatipo int NULL DEFAULT 0, gedo02activo varchar(1) NULL, gedo02tienevencimiento varchar(1) NULL, gedo02validezagnos int NULL DEFAULT 0, gedo02proveedor int NULL DEFAULT 0)";}
	if ($dbversion==2283){$sql="ALTER TABLE gedo02tipodoc ADD PRIMARY KEY(gedo02id)";}
	if ($dbversion==2284){$sql="ALTER TABLE gedo02tipodoc ADD UNIQUE INDEX gedo02tipodoc_id(gedo02consec)";}
	if ($dbversion==2285){$sql="agregamodulo|2602|26|Tipos de documentos|1|2|3|4|5|6|8";}
	if ($dbversion==2286){$sql=$u09."(2602, 1, 'Tipos de documentos', 'gedotipodoc.php', 2, 2602, 'S', '', '')";}
	if ($dbversion==2287){$sql="CREATE TABLE gedo03proveedores (gedo03id int NOT NULL, gedo03nombre varchar(50) NULL)";}
	if ($dbversion==2288){$sql="ALTER TABLE gedo03proveedores ADD PRIMARY KEY(gedo03id)";}
	if ($dbversion==2289){$sql="INSERT INTO gedo03proveedores (gedo03id, gedo03nombre) VALUES (0, 'Oficinas generadoras'), (1, 'Estudiantes'), (2, 'Externos')";}
	if ($dbversion==2290){$sql="CREATE TABLE gedo04trdserie (gedo04codigo varchar(50) NOT NULL, gedo04id int NULL DEFAULT 0, gedo04activa varchar(1) NULL, gedo04orden int NULL DEFAULT 0, gedo04nombre varchar(200) NULL)";}
	if ($dbversion==2291){$sql="ALTER TABLE gedo04trdserie ADD PRIMARY KEY(gedo04id)";}
	if ($dbversion==2292){$sql="ALTER TABLE gedo04trdserie ADD UNIQUE INDEX gedo04trdserie_id(gedo04codigo)";}
	if ($dbversion==2293){$sql="agregamodulo|2604|26|Series documentales|1|2|3|4|5|6|8";}
	if ($dbversion==2294){$sql=$u09."(2604, 1, 'Series documentales', 'gedoserie.php', 1, 2604, 'S', '', '')";}
	if ($dbversion==2295){$sql="CREATE TABLE gedo05trdsubserie (gedo05idserie int NOT NULL, gedo05codigo varchar(50) NOT NULL, gedo05id int NULL DEFAULT 0, gedo05activa varchar(1) NULL, gedo05orden int NULL DEFAULT 0, gedo05nombre varchar(200) NULL)";}
	if ($dbversion==2296){$sql="ALTER TABLE gedo05trdsubserie ADD PRIMARY KEY(gedo05id)";}
	if ($dbversion==2297){$sql="ALTER TABLE gedo05trdsubserie ADD UNIQUE INDEX gedo05trdsubserie_id(gedo05idserie, gedo05codigo)";}
	if ($dbversion==2298){$sql="ALTER TABLE gedo05trdsubserie ADD INDEX gedo05trdsubserie_padre(gedo05idserie)";}
	if ($dbversion==2299){$sql="agregamodulo|2605|26|Subseries documentales|1|2|3|4|5|6|8";}
	if ($dbversion==2300){$sql="CREATE TABLE gedo06fondos (gedo06consec int NOT NULL, gedo06id int NULL DEFAULT 0, gedo06activo varchar(1) NULL, gedo06nombre varchar(100) NULL)";}
	}
if (($dbversion>2300)&&($dbversion<2401)){
	if ($dbversion==2301){$sql="ALTER TABLE gedo06fondos ADD PRIMARY KEY(gedo06id)";}
	if ($dbversion==2302){$sql="ALTER TABLE gedo06fondos ADD UNIQUE INDEX gedo06fondos_id(gedo06consec)";}
	if ($dbversion==2303){$sql="agregamodulo|2606|26|Fondos documentales|1|2|3|4|5|6|8";}
	if ($dbversion==2304){$sql=$u09."(2606, 1, 'Fondos documentales', 'gedofondo.php', 2, 2606, 'S', '', '')";}
	if ($dbversion==2305){$sql="CREATE TABLE gedo07seccion (gedo07idfondo int NOT NULL, gedo07consec int NOT NULL, gedo07id int NULL DEFAULT 0, gedo07activa varchar(1) NULL, gedo07nombre varchar(200) NULL, gedo07idresponsable int NULL DEFAULT 0)";}
	if ($dbversion==2306){$sql="ALTER TABLE gedo07seccion ADD PRIMARY KEY(gedo07id)";}
	if ($dbversion==2307){$sql="ALTER TABLE gedo07seccion ADD UNIQUE INDEX gedo07seccion_id(gedo07idfondo, gedo07consec)";}
	if ($dbversion==2308){$sql="ALTER TABLE gedo07seccion ADD INDEX gedo07seccion_padre(gedo07idfondo)";}
	if ($dbversion==2309){$sql="agregamodulo|2607|26|Secciones documentales|1|2|3|4|5|6|8";}
	if ($dbversion==2310){$sql=$u09."(2607, 1, 'Secciones documentales', 'gedosecciondoc.php', 2, 2607, 'S', '', '')";}
	if ($dbversion==2311){$sql="CREATE TABLE gedo08subseccion (gedo08idseccion int NOT NULL, gedo08consec int NOT NULL, gedo08id int NULL DEFAULT 0, gedo08activa varchar(1) NULL, gedo08nombre varchar(200) NULL, gedo08idresponsable int NULL DEFAULT 0, gedo08idfondo int NULL DEFAULT 0)";}
	if ($dbversion==2312){$sql="ALTER TABLE gedo08subseccion ADD PRIMARY KEY(gedo08id)";}
	if ($dbversion==2313){$sql="ALTER TABLE gedo08subseccion ADD UNIQUE INDEX gedo08subseccion_id(gedo08idseccion, gedo08consec)";}
	if ($dbversion==2314){$sql="ALTER TABLE gedo08subseccion ADD INDEX gedo08subseccion_padre(gedo08idseccion)";}
	if ($dbversion==2315){$sql="agregamodulo|2608|26|Subsecciones documentales|1|2|3|4|5|6|8";}
	if ($dbversion==2316){$sql=$u09."(2608, 1, 'Subsecciones documentales', 'gedosubsecciondoc.php', 2, 2608, 'S', '', '')";}
	if ($dbversion==2317){$sql="CREATE TABLE gedo09oficina (gedo09idsubseccion int NOT NULL, gedo09consec int NOT NULL, gedo09id int NULL DEFAULT 0, gedo09activa varchar(1) NULL, gedo09nombre varchar(200) NULL, gedo09idresponsable int NULL DEFAULT 0, gedo09idfondo int NULL DEFAULT 0, gedo09idseccion int NULL DEFAULT 0)";}
	if ($dbversion==2318){$sql="ALTER TABLE gedo09oficina ADD PRIMARY KEY(gedo09id)";}
	if ($dbversion==2319){$sql="ALTER TABLE gedo09oficina ADD UNIQUE INDEX gedo09oficina_id(gedo09idsubseccion, gedo09consec)";}
	if ($dbversion==2320){$sql="ALTER TABLE gedo09oficina ADD INDEX gedo09oficina_padre(gedo09idsubseccion)";}
	if ($dbversion==2321){$sql="agregamodulo|2609|26|Oficinas generadoras|1|2|3|4|5|6|8";}
	if ($dbversion==2322){$sql=$unad70."(2606,2610,'gedo10tipologia','gedo10id','gedo10idfondo','El dato esta incluido en Tipologias', '')";}
	if ($dbversion==2323){$sql=$unad70."(2607,2610,'gedo10tipologia','gedo10id','gedo10idseccion','El dato esta incluido en Tipologias', '')";}
	if ($dbversion==2324){$sql=$unad70."(2608,2610,'gedo10tipologia','gedo10id','gedo10idsubseccion','El dato esta incluido en Tipologias', '')";}
	if ($dbversion==2325){$sql=$unad70."(2609,2610,'gedo10tipologia','gedo10id','gedo10idoficina','El dato esta incluido en Tipologias', '')";}
	if ($dbversion==2326){$sql=$unad70."(2602,2610,'gedo10tipologia','gedo10id','gedo10iddocumento','El dato esta incluido en Tipologias', '')";}
	if ($dbversion==2327){$sql=$unad70."(2604,2610,'gedo10tipologia','gedo10id','gedo10trdserie','El dato esta incluido en Tipologias', '')";}
	if ($dbversion==2328){$sql=$unad70."(2605,2610,'gedo10tipologia','gedo10id','gedo10trdsubserie','El dato esta incluido en Tipologias', '')";}
	if ($dbversion==2329){$sql=$unad70."(2611,2610,'gedo10tipologia','gedo10id','gedo10trddisposicionfin','El dato esta incluido en Tipologias', '')";}
	if ($dbversion==2330){$sql="CREATE TABLE gedo10tipologia (gedo10agno int NOT NULL, gedo10idfondo int NOT NULL, gedo10idseccion int NOT NULL, gedo10idsubseccion int NOT NULL, gedo10idoficina int NOT NULL, gedo10iddocumento int NOT NULL, gedo10id int NULL DEFAULT 0, gedo10incluirtrd varchar(1) NULL, gedo10trdserie int NULL DEFAULT 0, gedo10trdsubserie int NULL DEFAULT 0, gedo10trdgestiontiempo int NULL DEFAULT 0, gedo10trdconservatiempo int NULL DEFAULT 0, gedo10trddisposicionfin int NULL DEFAULT 0)";}
	if ($dbversion==2331){$sql="ALTER TABLE gedo10tipologia ADD PRIMARY KEY(gedo10id)";}
	if ($dbversion==2332){$sql="ALTER TABLE gedo10tipologia ADD UNIQUE INDEX gedo10tipologia_id(gedo10agno, gedo10idfondo, gedo10idseccion, gedo10idsubseccion, gedo10idoficina, gedo10iddocumento)";}
	if ($dbversion==2333){$sql="agregamodulo|2610|26|Tipologias|1|2|3|4|5|6|8";}
	if ($dbversion==2334){$sql=$u09."(2610, 1, 'Tipologias', 'gedotipologia.php', 1, 2610, 'S', '', '')";}
	if ($dbversion==2335){$sql="CREATE TABLE gedo11disposicionfin (gedo11consec int NOT NULL, gedo11id int NULL DEFAULT 0, gedo11activa varchar(1) NULL, gedo11orden int NULL DEFAULT 0, gedo11nombre varchar(100) NULL)";}
	if ($dbversion==2336){$sql="ALTER TABLE gedo11disposicionfin ADD PRIMARY KEY(gedo11id)";}
	if ($dbversion==2337){$sql="ALTER TABLE gedo11disposicionfin ADD UNIQUE INDEX gedo11disposicionfin_id(gedo11consec)";}
	if ($dbversion==2338){$sql="agregamodulo|2611|26|Disposición final|1|2|3|4|5|6|8";}
	if ($dbversion==2339){$sql=$u09."(2611, 1, 'Disposición final', 'gedodispfin.php', 2, 2611, 'S', '', '')";}
	if ($dbversion==2340){$sql=$unad70."(2604,2612,'gedo12tipoexpediente','gedo12id','gedo12idserie','El dato esta incluido en Tipos de expedientes', '')";}
	if ($dbversion==2341){$sql=$unad70."(2605,2612,'gedo12tipoexpediente','gedo12id','gedo12idsubserie','El dato esta incluido en Tipos de expedientes', '')";}
	if ($dbversion==2342){$sql="CREATE TABLE gedo12tipoexpediente (gedo12consec int NOT NULL, gedo12id int NULL DEFAULT 0, gedo12activa varchar(1) NULL, gedo13personal varchar(1) NULL, gedo12nombre varchar(100) NULL, gedo12idserie int NULL DEFAULT 0, gedo12idsubserie int NULL DEFAULT 0, gedo12nivelacceso int NULL DEFAULT 0)";}
	if ($dbversion==2343){$sql="ALTER TABLE gedo12tipoexpediente ADD PRIMARY KEY(gedo12id)";}
	if ($dbversion==2344){$sql="ALTER TABLE gedo12tipoexpediente ADD UNIQUE INDEX gedo12tipoexpediente_id(gedo12consec)";}
	if ($dbversion==2345){$sql="agregamodulo|2612|26|Tipos de expedientes|1|2|3|4|5|6|8";}
	if ($dbversion==2346){$sql=$u09."(2612, 1, 'Tipos de expedientes', 'gedotipoexp.php', 2, 2612, 'S', '', '')";}
	if ($dbversion==2347){$sql=$unad70."(2602,2613,'gedo13tipoexpdoc','gedo13id','gedo13idtipodoc','El dato esta incluido en Documentos del expediente', '')";}
	if ($dbversion==2348){$sql="CREATE TABLE gedo13tipoexpdoc (gedo13idtipoexp int NOT NULL, gedo13consec int NOT NULL, gedo13id int NULL DEFAULT 0, gedo13idtipodoc int NULL DEFAULT 0, gedo13orden int NULL DEFAULT 0, gedo13formadoc int NULL DEFAULT 0)";}
	if ($dbversion==2349){$sql="ALTER TABLE gedo13tipoexpdoc ADD PRIMARY KEY(gedo13id)";}
	if ($dbversion==2350){$sql="ALTER TABLE gedo13tipoexpdoc ADD UNIQUE INDEX gedo13tipoexpdoc_id(gedo13idtipoexp, gedo13consec)";}
	if ($dbversion==2351){$sql="ALTER TABLE gedo13tipoexpdoc ADD INDEX gedo13tipoexpdoc_padre(gedo13idtipoexp)";}
	if ($dbversion==2352){$sql="agregamodulo|2613|26|Tipos de expediente - Documentos|1|2|3|4|5|6|8";}
	if ($dbversion==2353){$sql=$unad70."(2606,2614,'gedo14expediente','gedo14id','gedo14idfondo','El dato esta incluido en Expedientes', '')";}
	if ($dbversion==2354){$sql=$unad70."(2607,2614,'gedo14expediente','gedo14id','gedo14idseccion','El dato esta incluido en Expedientes', '')";}
	if ($dbversion==2355){$sql=$unad70."(2608,2614,'gedo14expediente','gedo14id','gedo14idsubseccion','El dato esta incluido en Expedientes', '')";}
	if ($dbversion==2356){$sql=$unad70."(2609,2614,'gedo14expediente','gedo14id','gedo14idoficina','El dato esta incluido en Expedientes', '')";}
	if ($dbversion==2357){$sql="CREATE TABLE gedo14expediente (gedo14tipoexpediente int NOT NULL, gedo14consec int NOT NULL, gedo14id int NULL DEFAULT 0, gedo14idtercero int NULL DEFAULT 0, gedo14activo varchar(1) NULL, gedo14idfondo int NULL DEFAULT 0, gedo14idseccion int NULL DEFAULT 0, gedo14idsubseccion int NULL DEFAULT 0, gedo14idoficina int NULL DEFAULT 0, gedo14idcajainicial int NULL DEFAULT 0, gedo14idcarpetainicial int NULL DEFAULT 0, gedo14fechainicial int NULL DEFAULT 0, gedo14fechafinal int NULL DEFAULT 0, gedo14numfolios int NULL DEFAULT 0)";}
	if ($dbversion==2358){$sql="ALTER TABLE gedo14expediente ADD PRIMARY KEY(gedo14id)";}
	if ($dbversion==2359){$sql="ALTER TABLE gedo14expediente ADD UNIQUE INDEX gedo14expediente_id(gedo14tipoexpediente, gedo14consec)";}
	if ($dbversion==2360){$sql="agregamodulo|2614|26|Expedientes|1|2|3|4|5|6|8";}
	if ($dbversion==2361){$sql=$u09."(2614, 1, 'Expedientes', 'gedoexpediente.php', 2601, 2614, 'S', '', '')";}
	if ($dbversion==2362){$sql=$unad70."(2602,2615,'gedo15expdoc','gedo15id','gedo15idtipodoc','El dato esta incluido en Documentos', '')";}
	if ($dbversion==2363){$sql=$unad70."(2606,2615,'gedo15expdoc','gedo15id','gedo15idfondo','El dato esta incluido en Documentos', '')";}
	if ($dbversion==2364){$sql=$unad70."(2607,2615,'gedo15expdoc','gedo15id','gedo15idseccion','El dato esta incluido en Documentos', '')";}
	if ($dbversion==2365){$sql=$unad70."(2608,2615,'gedo15expdoc','gedo15id','gedo15idsubseccion','El dato esta incluido en Documentos', '')";}
	if ($dbversion==2366){$sql=$unad70."(2609,2615,'gedo15expdoc','gedo15id','gedo15idoficina','El dato esta incluido en Documentos', '')";}
	if ($dbversion==2367){$sql="CREATE TABLE gedo15expdoc (gedo15idexpediente int NOT NULL, gedo15consec int NOT NULL, gedo15id int NULL DEFAULT 0, gedo15idtipodoc int NULL DEFAULT 0, gedo15iddocumento int NULL DEFAULT 0, gedo15idfondo int NULL DEFAULT 0, gedo15idseccion int NULL DEFAULT 0, gedo15idsubseccion int NULL DEFAULT 0, gedo15idoficina int NULL DEFAULT 0, gedo15fisico varchar(1) NULL, gedo15idcarpeta int NULL DEFAULT 0, gedo15idfolioinicial int NULL DEFAULT 0, gedo15numfolios int NULL DEFAULT 0)";}
	if ($dbversion==2368){$sql="ALTER TABLE gedo15expdoc ADD PRIMARY KEY(gedo15id)";}
	if ($dbversion==2369){$sql="ALTER TABLE gedo15expdoc ADD UNIQUE INDEX gedo15expdoc_id(gedo15idexpediente, gedo15consec)";}
	if ($dbversion==2370){$sql="ALTER TABLE gedo15expdoc ADD INDEX gedo15expdoc_padre(gedo15idexpediente)";}
	if ($dbversion==2371){$sql="agregamodulo|2615|26|Expedientes - Documentos|1|2|3|4|5|6|8";}
	if ($dbversion==2372){$sql=$unad70."(2602,2616,'gedo16documentos','gedo16id','gedo16idtipodoc','El dato esta incluido en Repositorio documental', '')";}
	if ($dbversion==2373){$sql=$unad70."(2606,2616,'gedo16documentos','gedo16id','gedo16idfondo','El dato esta incluido en Repositorio documental', '')";}
	if ($dbversion==2374){$sql=$unad70."(2607,2616,'gedo16documentos','gedo16id','gedo16idseccion','El dato esta incluido en Repositorio documental', '')";}
	if ($dbversion==2375){$sql=$unad70."(2608,2616,'gedo16documentos','gedo16id','gedo16idsubseccion','El dato esta incluido en Repositorio documental', '')";}
	if ($dbversion==2376){$sql=$unad70."(2609,2616,'gedo16documentos','gedo16id','gedo16idoficina','El dato esta incluido en Repositorio documental', '')";}
	if ($dbversion==2377){$sql=$unad70."(2617,2616,'gedo16documentos','gedo16id','gedo16mediorecepcion','El dato esta incluido en Repositorio documental', '')";}
	if ($dbversion==2378){$sql="CREATE TABLE gedo16documentos (gedo16consec int NOT NULL, gedo16id int NULL DEFAULT 0, gedo16idtipodoc int NULL DEFAULT 0, gedo16idtercero int NULL DEFAULT 0, gedo16idfondo int NULL DEFAULT 0, gedo16idseccion int NULL DEFAULT 0, gedo16idsubseccion int NULL DEFAULT 0, gedo16idoficina int NULL DEFAULT 0, gedo16fechasubido int NULL DEFAULT 0, gedo16mediorecepcion int NULL DEFAULT 0, gedo16fechadoc int NULL DEFAULT 0, gedo16fechavence int NULL DEFAULT 0, gedo16numfolios int NULL DEFAULT 0, gedo16observacion Text NULL, gedo16idorigen int NULL DEFAULT 0, gedo16idarchivo int NULL DEFAULT 0)";}
	if ($dbversion==2379){$sql="ALTER TABLE gedo16documentos ADD PRIMARY KEY(gedo16id)";}
	if ($dbversion==2380){$sql="ALTER TABLE gedo16documentos ADD UNIQUE INDEX gedo16documentos_id(gedo16consec)";}
	if ($dbversion==2381){$sql="agregamodulo|2616|26|Repositorio documental|1|2|3|4|5|6|8";}
	if ($dbversion==2382){$sql=$u09."(2616, 1, 'Repositorio documental', 'gedorepositorio.php', 2601, 2616, 'S', '', '')";}
	if ($dbversion==2383){$sql="CREATE TABLE gedo17mediorecepcion (gedo17consec int NOT NULL, gedo17id int NULL DEFAULT 0, gedo17activo varchar(1) NULL, gedo17nombre varchar(100) NULL)";}
	if ($dbversion==2384){$sql="ALTER TABLE gedo17mediorecepcion ADD PRIMARY KEY(gedo17id)";}
	if ($dbversion==2385){$sql="ALTER TABLE gedo17mediorecepcion ADD UNIQUE INDEX gedo17mediorecepcion_id(gedo17consec)";}
	if ($dbversion==2386){$sql="agregamodulo|2617|26|Medios de recepción|1|2|3|4|5|6|8";}
	if ($dbversion==2387){$sql=$u09."(2617, 1, 'Medios de recepción', 'gedomediorecep.php', 2, 2617, 'S', '', '')";}
	if ($dbversion==2388){$sql="CREATE TABLE gedo18nivelacceso (gedo18id int NOT NULL, gedo18nombre varchar(50) NULL)";}
	if ($dbversion==2389){$sql="ALTER TABLE gedo18nivelacceso ADD PRIMARY KEY(gedo18id)";}
	if ($dbversion==2390){$sql="INSERT INTO gedo18nivelacceso (gedo18id, gedo18nombre) VALUES (0, 'Público'), (1, 'Controlado'), (2, 'Reservado')";}
	if ($dbversion==2391){$sql="ALTER TABLE olab10horarios ADD olab10idlaboratorio int NULL DEFAULT 0";}
	if ($dbversion==2392){$sql="UPDATE olab08oferta AS TB, olab10horarios AS T10 SET T10.olab10idlaboratorio=TB.olab08idlaboratorio WHERE TB.olab08id=T10.olab10idoferta";}
	if ($dbversion==2393){$sql="INSERT INTO olab01laboratorios (olab01consec, olab01id, olab01propio, olab01activo, olab01nombre, olab01idzona, olab01idsede, olab01idconvenio, olab01detalle, olab01tipooferta) VALUES (0, 0, 'S', 'N', 'Ninguno', 0, 0, 0, '', -1)";}
	//2394 a 2396 Quedan libres
	if ($dbversion==2397){$sql="agregamodulo|2407|24|Modificación de calificaciones|1|2|3|4|5|6|8";}
	if ($dbversion==2398){$sql=$u09."(2407, 1, 'Modificación de calificaciones', 'cecamodcalifica.php', 2202, 2407, 'S', '', '')";}
	//2399 A 2402 Quedan libres
	}
if (($dbversion>2400)&&($dbversion<2501)){
	if ($dbversion==2402){$sql="agregamodulo|2408|24|Estadistica de calificaciones|1|5|6";}
	if ($dbversion==2403){$sql=$u09."(2408, 1, 'Estadistica de calificaciones', 'cecarptestadistica.php', 11, 2408, 'S', '', '')";}
	if ($dbversion==2404){$sql="CREATE TABLE ceca09estadomod (ceca09id int NOT NULL, ceca09nombre varchar(100) NULL)";}
	if ($dbversion==2405){$sql="ALTER TABLE ceca09estadomod ADD PRIMARY KEY(ceca09id)";}
	if ($dbversion==2406){$sql="INSERT INTO ceca09estadomod (ceca09id, ceca09nombre) VALUES (0, 'Solicitada'), (1, 'Aprobada'), (3, 'Rechazada'), (5, 'Anulada'), (7, 'Aplicada')";}
	if ($dbversion==2407){$sql="CREATE TABLE ceca10tiporegestad (ceca10id int NOT NULL, ceca10nombre varchar(100) NULL)";}
	if ($dbversion==2408){$sql="ALTER TABLE ceca10tiporegestad ADD PRIMARY KEY(ceca10id)";}
	if ($dbversion==2409){$sql="INSERT INTO ceca10tiporegestad (ceca10id, ceca10nombre) VALUES (1, 'Totalizado por curso'), (2, 'Por curso tutor'), (3, 'Por curso zona'), (4, 'Por curso centro'), (5, 'Por curso escuela'), (6, 'Por curso programa')";}
	if ($dbversion==2410){$sql="agregamodulo|2140|21|Reporte de matricula|1|5|6";}
	if ($dbversion==2411){$sql=$u09."(2140, 1, 'Reporte de matricula', 'oilrptmatricula.php', 11, 2140, 'S', '', '')";}
	if ($dbversion==2412){$sql="ALTER TABLE core12escuela ADD core12sigla varchar(20) NULL DEFAULT ''";}
	if ($dbversion==2413){$sql=$u01."(27, 'GRADOS', 'Gestión de Grados', 'S', 'S', 1, 0, 0)";}
	if ($dbversion==2414){$sql=$u08."(2701, 'Grados', 'gm.php?id=2701', 'Grados', 'Degrees', 'Graus')";}
	if ($dbversion==2415){$sql="UPDATE unad08grupomenu SET unad08nombre='Trabajos de Grado', unad08titulo='Trabajos de Grado' WHERE unad08id=2203";}
	if ($dbversion==2416){$sql="UPDATE core32gradoestado SET core32nombre='Opcion de grado aprobada' WHERE core32id=14";}
	if ($dbversion==2417){$sql="INSERT INTO core32gradoestado (core32id, core32nombre) VALUES (15, 'RAI Completo'), (16, 'Postulado para grado'), (17, 'En Verificación Administriva'), (18, 'Recido de Pago de Derechos Generado'), (19, 'En Aprobación de Grado'), (20, 'En Elaboración de Titulo'), (21, 'Graduado')";}
	if ($dbversion==2418){$sql="CREATE TABLE grad01cohortes (grad01agno int NOT NULL, grad01tipocohorte int NOT NULL, grad01consec int NOT NULL, grad01id int NULL DEFAULT 0, grad01estadocohorte int NULL DEFAULT 0, grad01fechaapruebaescuela int NULL DEFAULT 0, grad01fechapagorecibo int NULL DEFAULT 0, grad01fechacarguedocumentos int NULL DEFAULT 0, grad01fechaapruebacentro int NULL DEFAULT 0, grad01fechaapruebagrado int NULL DEFAULT 0, grad01fechagrado int NULL DEFAULT 0, grad01infopostulado Text NULL, grad01cantsolicitudes int NULL DEFAULT 0, grad01cantgraduados int NULL DEFAULT 0, grad01nombre varchar(100) NULL)";}
	if ($dbversion==2419){$sql="ALTER TABLE grad01cohortes ADD PRIMARY KEY(grad01id)";}
	if ($dbversion==2420){$sql="ALTER TABLE grad01cohortes ADD UNIQUE INDEX grad01cohortes_id(grad01agno, grad01tipocohorte, grad01consec)";}
	if ($dbversion==2421){$sql="agregamodulo|2701|27|Cohortes|1|2|3|4|5|6|8";}
	if ($dbversion==2422){$sql=$u09."(2701, 1, 'Cohortes', 'gradcohorte.php', 1, 2701, 'S', '', '')";}
	if ($dbversion==2423){$sql="CREATE TABLE grad02tipocohorte (grad02id int NOT NULL, grad02nombre varchar(100) NULL)";}
	if ($dbversion==2424){$sql="ALTER TABLE grad02tipocohorte ADD PRIMARY KEY(grad02id)";}
	if ($dbversion==2425){$sql="INSERT INTO grad02tipocohorte (grad02id, grad02nombre) VALUES (0, 'Ordinario'), (1, 'Extraordinario'), (2, 'Ventanilla'), (3, 'Especial')";}
	if ($dbversion==2426){$sql=$unad70."(2701,2702,'core01estprograma','core01id','core01gradoidcohorte','El dato esta incluido en Postulados', '')";}
	if ($dbversion==2427){$sql="ALTER TABLE core01estprograma ADD core01gradourldocfinal varchar(200) NULL DEFAULT '', ADD core01gradoraiidaprueba int NULL DEFAULT 0, ADD core01gradoraifechaaprueba int NULL DEFAULT 0, ADD core01gradoidcohorte int NULL DEFAULT 0";}
	if ($dbversion==2428){$sql="agregamodulo|2702|27|Postulados|1|3|5|6";}
	if ($dbversion==2429){$sql=$u09."(2702, 1, 'Postulados', 'gradpostulado.php', 2201, 2702, 'S', '', '')";}
	if ($dbversion==2430){$sql=$unad70."(2701,2703,'grad03devoluciones','grad03id','grad03idcohorte','El dato esta incluido en Devoluciones', '')";}
	if ($dbversion==2431){$sql=$unad70."(2704,2703,'grad03devoluciones','grad03id','grad03motivodevolucion','El dato esta incluido en Devoluciones', '')";}
	if ($dbversion==2432){$sql="CREATE TABLE grad03devoluciones (grad03idestprograma int NOT NULL, grad03idcohorte int NOT NULL, grad03consec int NULL DEFAULT 0, grad03id int NULL DEFAULT 0, grad03fechadevolucion int NULL DEFAULT 0, grad03motivodevolucion int NULL DEFAULT 0, grad03detalle Text NULL, grad03idusuario int NULL DEFAULT 0)";}
	if ($dbversion==2433){$sql="ALTER TABLE grad03devoluciones ADD PRIMARY KEY(grad03id)";}
	if ($dbversion==2434){$sql="ALTER TABLE grad03devoluciones ADD UNIQUE INDEX grad03devoluciones_id(grad03idestprograma, grad03idcohorte)";}
	if ($dbversion==2435){$sql="ALTER TABLE grad03devoluciones ADD INDEX grad03devoluciones_padre(grad03idestprograma)";}
	if ($dbversion==2436){$sql="agregamodulo|2703|27|Devoluciones|1|2|3|4|5|6|8";}
	if ($dbversion==2437){$sql="CREATE TABLE grad04motivodev (grad04consec int NOT NULL, grad04id int NULL DEFAULT 0, grad04activo varchar(1) NULL, grad04orden int NULL DEFAULT 0, grad04nombre varchar(100) NULL)";}
	if ($dbversion==2438){$sql="ALTER TABLE grad04motivodev ADD PRIMARY KEY(grad04id)";}
	if ($dbversion==2439){$sql="ALTER TABLE grad04motivodev ADD UNIQUE INDEX grad04motivodev_id(grad04consec)";}
	if ($dbversion==2440){$sql="agregamodulo|2704|27|Motivos de devolución|1|2|3|4|5|6|8";}
	if ($dbversion==2441){$sql=$u09."(2704, 1, 'Motivos de devolución', 'gradmotivodev.php', 2, 2704, 'S', '', '')";}
	if ($dbversion==2442){$sql="CREATE TABLE grad05estadocohorte (grad05id int NOT NULL, grad05nombre varchar(100) NULL)";}
	if ($dbversion==2443){$sql="ALTER TABLE grad05estadocohorte ADD PRIMARY KEY(grad05id)";}
	if ($dbversion==2444){$sql="INSERT INTO grad05estadocohorte (grad05id, grad05nombre) VALUES (0, 'Cerrada'), (1, 'Disponible'), (4, 'En desarrollo'), (7, 'Concluida')";}
	if ($dbversion==2445){$sql=$unad70."(2238,2706,'core01estprograma','core01id','core01gradoidopcion','El dato esta incluido en Graduados', '')";}
	if ($dbversion==2456){$sql=$unad70."(2701,2706,'core01estprograma','core01id','core01gradoidcohorte','El dato esta incluido en Graduados', '')";}
	if ($dbversion==2447){$sql="agregamodulo|2706|27|Graduados|1|3|5|6|8";}
	if ($dbversion==2448){$sql=$u09."(2706, 1, 'Graduados', 'gradgraduados.php', 2201, 2706, 'S', '', '')";}
	if ($dbversion==2449){$sql="UPDATE unad02modulos SET unad02idsistema=27 WHERE unad02id IN (2230, 2231, 2233, 2234, 2235, 2236, 2237, 2238, 2239)";}
	if ($dbversion==2450){$sql="agregamodulo|2791|27|Perfiles|1";}
	if ($dbversion==2451){$sql=$u09."(2791, 1, 'Perfiles', 'unadperfil.php', 2, 2791, 'S', '', 'Usuarios')";}
	if ($dbversion==2452){$sql="agregamodulo|2792|27|Usuarios|1";}
	if ($dbversion==2453){$sql=$u09."(2792, 1, 'Usuarios', 'unadusuarios.php', 1, 2792, 'S', '', '')";}
	if ($dbversion==2454){$sql="ALTER TABLE grad01cohortes ADD grad01finapruebaescuela int NULL DEFAULT 0, ADD grad01finpagorecibo int NULL DEFAULT 0, ADD grad01fincarguedocumentos int NULL DEFAULT 0, ADD grad01finapruebacentro int NULL DEFAULT 0, ADD grad01finapruebagrado int NULL DEFAULT 0";}
	if ($dbversion==2455){$sql="ALTER TABLE ofer08oferta ADD ofer08estadodiseno int NULL DEFAULT 0, ADD ofer08iddecano int NULL DEFAULT 0, ADD ofer08idsecretarioacad int NULL DEFAULT 0, ADD ofer08idliderprograma int NULL DEFAULT 0";}
	if ($dbversion==2456){$sql="CREATE TABLE core39unidadproductora (unad39consec int NOT NULL, unad39id int NULL DEFAULT 0, unad39activa varchar(1) NULL, unad39nombre varchar(100) NULL, unad39iddirector int NULL DEFAULT 0, unad39idadministrador int NULL DEFAULT 0)";}
	if ($dbversion==2457){$sql="ALTER TABLE core39unidadproductora ADD PRIMARY KEY(unad39id)";}
	if ($dbversion==2458){$sql="ALTER TABLE core39unidadproductora ADD UNIQUE INDEX core39unidadproductora_id(unad39consec)";}
	if ($dbversion==2459){$sql="agregamodulo|2239|22|Unidades productoras|1|2|3|4|5|6|8";}
	if ($dbversion==2460){$sql=$u09."(2239, 1, 'Unidades responsables de contenidos académicos', 'coreunidadprod.php', 2, 2239, 'S', '', '')";}
	if ($dbversion==2461){$sql="INSERT INTO core39unidadproductora (unad39consec, unad39id, unad39activa, unad39nombre, unad39iddirector, unad39idadministrador) VALUES (0, 0, 'N', '{Ninguna}', 0, 0)";}
	if ($dbversion==2462){$sql="ALTER TABLE unad40curso ADD unad40unidadprod int NULL DEFAULT 0, ADD unad40ofertaperacacorto varchar(1) NULL DEFAULT 'D', ADD unad40homologable varchar(1) NULL DEFAULT 'D', ADD unad40habilitable varchar(1) NULL DEFAULT 'D', ADD unad40porsuficiencia varchar(1) NULL DEFAULT 'D'";}
	if ($dbversion==2463){$sql="ALTER TABLE unad11terceros ADD unad11carariesgo Text NULL";}
	if ($dbversion==2464){$sql="ALTER TABLE unad40curso ADD unad40modocalifica int NULL DEFAULT 0";}
	if ($dbversion==2465){$sql="ALTER TABLE core00params ADD core00idperfilcursoscomunes int NULL DEFAULT 0";}
	if ($dbversion==2466){$sql="CREATE TABLE core43modocalifica (core43id int NOT NULL, core43nombre varchar(100) NULL)";}
	if ($dbversion==2467){$sql="ALTER TABLE core43modocalifica ADD PRIMARY KEY(core43id)";}
	if ($dbversion==2468){$sql="INSERT INTO core43modocalifica (core43id, core43nombre) VALUES (0, 'Puntaje (500)'), (1, 'Aprobado - No aprobado'), (2, 'Por logros'), (3, 'Nota de Practica')";}
	if ($dbversion==2469){$sql="CREATE TABLE unae14logcron (unae14fecha int NOT NULL, unae14consec int NOT NULL, unae14id int NULL DEFAULT 0, unae14minuto int NULL DEFAULT 0, unae14minutofin int NULL DEFAULT 0)";}
	if ($dbversion==2470){$sql="ALTER TABLE unae14logcron ADD PRIMARY KEY(unae14id)";}
	if ($dbversion==2471){$sql="ALTER TABLE unae14logcron ADD UNIQUE INDEX unae14logcron_id(unae14fecha, unae14consec)";}
	if ($dbversion==2472){$sql="agregamodulo|214|1|CRON - Registro de actividades|1|5|6";}
	if ($dbversion==2473){$sql=$u09."(214, 1, 'Registro de actividades recurrentes', 'unadlogcron.php', 1501, 214, 'S', '', '')";}
	if ($dbversion==2474){$sql="CREATE TABLE unae15cronregistro (unae15idlogcron int NOT NULL, unae15consec int NOT NULL, unae15id int NULL DEFAULT 0, unae15idaccion int NULL DEFAULT 0, unae15detalle Text NULL, unae15minuto int NULL DEFAULT 0)";}
	if ($dbversion==2475){$sql="ALTER TABLE unae15cronregistro ADD PRIMARY KEY(unae15id)";}
	if ($dbversion==2476){$sql="ALTER TABLE unae15cronregistro ADD UNIQUE INDEX unae15cronregistro_id(unae15idlogcron, unae15consec)";}
	if ($dbversion==2477){$sql="ALTER TABLE unae15cronregistro ADD INDEX unae15cronregistro_padre(unae15idlogcron)";}
	if ($dbversion==2478){$sql="agregamodulo|215|1|CRON - Actividades ejecutadas|1|5|6";}
	if ($dbversion==2479){$sql="CREATE TABLE unae16cronaccion (unae16id int NOT NULL, unae16accion varchar(100) NULL)";}
	if ($dbversion==2480){$sql="ALTER TABLE unae16cronaccion ADD PRIMARY KEY(unae16id)";}
	if ($dbversion==2481){$sql="INSERT INTO unae16cronaccion (unae16id, unae16accion) VALUES (1, 'Base de datos para archivos'), (1708, 'OAI - Vencimiento de cursos'), (1709, 'OAI - No conformidades'), (2216, 'CORE - Procesar la matrícula'), (2217, 'CORE - Carga de agendas'), (2401, 'CARACTERIZACION - Cargar encuestas'), (2108, 'OIL - Envío de recordatorios')";}
	if ($dbversion==2482){$sql="ALTER TABLE exte02per_aca ADD exte02solicitante int NULL DEFAULT 0, ADD exte02ofertacursos varchar(1) NULL, ADD exte02ofertafechatope int NULL DEFAULT 0, ADD exte02oferfechatopecancela int NULL DEFAULT 0, ADD exte02fechainimatricula int NULL DEFAULT 0, ADD exte02fechafinmatricula int NULL DEFAULT 0, ADD exte02fechadevolderpec int NULL DEFAULT 0, ADD exte02fechainducciones int NULL DEFAULT 0, ADD exte02fechafinactiniciales int NULL DEFAULT 0, ADD exte02fechatopeoil int NULL DEFAULT 0, ADD exte02fechafinactfinales int NULL DEFAULT 0, ADD exte02fechatopecalifhabilita int NULL DEFAULT 0, ADD exte02fechatopeevaldocente int NULL DEFAULT 0, ADD exte02idciclo int NULL DEFAULT 0";}
	if ($dbversion==2483){$sql="CREATE TABLE unae17cicloacadem (unae17consec int NOT NULL, unae17id int NULL DEFAULT 0, unae17orden int NULL DEFAULT 0, unae17nombre varchar(100) NULL, unae17fechaini int NULL DEFAULT 0, unae17fechafin int NULL DEFAULT 0, unae17numestnuevos int NULL DEFAULT 0, unae17numgraduados int NULL DEFAULT 0)";}
	if ($dbversion==2484){$sql="ALTER TABLE unae17cicloacadem ADD PRIMARY KEY(unae17id)";}
	if ($dbversion==2485){$sql="ALTER TABLE unae17cicloacadem ADD UNIQUE INDEX unae17cicloacadem_id(unae17consec)";}
	if ($dbversion==2486){$sql="agregamodulo|217|1|Ciclos académicos|1|2|3|4|5|6|8";}
	if ($dbversion==2487){$sql=$u09."(217, 1, 'Ciclos académicos', 'unadcicloacad.php', 2, 217, 'S', '', '')";}
	if ($dbversion==2488){$sql="ALTER TABLE core01estprograma ADD INDEX core01estprograma_estadogrado(core01gradoestado)";}
	if ($dbversion==2489){$sql="ALTER TABLE core01estprograma ADD core01gradonumlibroactas int NULL DEFAULT 0, ADD core01gradonumdiploma int NULL DEFAULT 0, ADD core01gradonumlibrodiplomas int NULL DEFAULT 0, ADD core01origenregistro int NULL DEFAULT 0, ADD core01fechaimportacion int NULL DEFAULT 0, ADD core01idimporta int NULL DEFAULT 0, ADD core01gradotitulodiploma varchar(100) NULL DEFAULT '', ADD core01gradotituloopcion varchar(200) NULL DEFAULT ''";}
	if ($dbversion==2490){$sql="agregamodulo|2707|27|Importar datos|1|2";}
	if ($dbversion==2491){$sql=$u09."(2707, 1, 'Importar datos', 'gradimportar.php', 7, 2707, 'S', '', '')";}
	if ($dbversion==2492){$sql="ALTER TABLE core09programa ADD core09codgrados int NULL DEFAULT 0, ADD core09titulohombres varchar(100) NULL DEFAULT '', ADD core09titulomujeres varchar(100) NULL DEFAULT ''";}
	if ($dbversion==2493){$sql="INSERT INTO core22nivelprograma (core22id, core22nombre, core22orden, core22grupo) VALUES (8, 'Formación Para El Trabajo', 1, 1)";}
	if ($dbversion==2494){$sql="agregamodulo|2191|21|Perfiles|1";}
	if ($dbversion==2495){$sql=$u09."(2191, 1, 'Perfiles', 'unadperfil.php', 2, 2191, 'S', '', 'Usuarios')";}
	if ($dbversion==2496){$sql="agregamodulo|2192|21|Usuarios|1";}
	if ($dbversion==2497){$sql=$u09."(2192, 1, 'Usuarios', 'unadusuarios.php', 1, 2192, 'S', '', '')";}
	if ($dbversion==2498){$sql="ALTER TABLE core11plandeestudio ADD core11idprerequisito2 int NULL DEFAULT 0, ADD core11idprerequisito3 int NULL DEFAULT 0";}
	if ($dbversion==2499){$sql="ALTER TABLE unad11terceros ADD unad11idioma varchar(5) NULL DEFAULT 'es', ADD unad11formapresenta int NULL DEFAULT 0, ADD unad11formaclave int NULL DEFAULT 0, ADD unad11tester int NULL DEFAULT 0";}
	if ($dbversion==2500){$sql="agregamodulo|298|1|Usuarios especiales|1|3";}
	}
if (($dbversion>2500)&&($dbversion<2601)){
	if ($dbversion==2501){$sql=$u09."(298, 1, 'Usuarios especiales', 'unadusuariosesp.php', 1501, 298, 'S', '', '')";}
	if ($dbversion==2502){$sql="ALTER TABLE exte02per_aca ADD exte02fechatopetablero int NULL DEFAULT 0";}
	if ($dbversion==2503){$sql="ALTER TABLE core01estprograma ADD core01contciclo1 int NULL DEFAULT 0, ADD core01contciclo2 int NULL DEFAULT 0, ADD core01contciclo3 int NULL DEFAULT 0, ADD core01contciclo4 int NULL DEFAULT 0, ADD core01contciclo5 int NULL DEFAULT 0, ADD core01contciclo6 int NULL DEFAULT 0, ADD core01contciclo7 int NULL DEFAULT 0, ADD core01contciclo8 int NULL DEFAULT 0, ADD core01contciclo9 int NULL DEFAULT 0, ADD core01contciclo10 int NULL DEFAULT 0, ADD core01contciclo11 int NULL DEFAULT 0, ADD core01contciclo12 int NULL DEFAULT 0, ADD core01contciclo13 int NULL DEFAULT 0, ADD core01contciclo14 int NULL DEFAULT 0, ADD core01contciclo15 int NULL DEFAULT 0, ADD core01contciclo16 int NULL DEFAULT 0, ADD core01contciclo17 int NULL DEFAULT 0, ADD core01contciclo18 int NULL DEFAULT 0, ADD core01contciclo19 int NULL DEFAULT 0, ADD core01contciclo20 int NULL DEFAULT 0, ADD core01contmat1 int NULL DEFAULT 0, ADD core01contmat2 int NULL DEFAULT 0, ADD core01contmat3 int NULL DEFAULT 0, ADD core01contmat4 int NULL DEFAULT 0, ADD core01contmat5 int NULL DEFAULT 0, ADD core01contmat6 int NULL DEFAULT 0, ADD core01contmat7 int NULL DEFAULT 0, ADD core01contmat8 int NULL DEFAULT 0, ADD core01contmat9 int NULL DEFAULT 0, ADD core01contmat10 int NULL DEFAULT 0, ADD core01contmat11 int NULL DEFAULT 0, ADD core01contmat12 int NULL DEFAULT 0, ADD core01contmat13 int NULL DEFAULT 0, ADD core01contmat14 int NULL DEFAULT 0, ADD core01contmat15 int NULL DEFAULT 0, ADD core01contmat16 int NULL DEFAULT 0, ADD core01contmat17 int NULL DEFAULT 0, ADD core01contmat18 int NULL DEFAULT 0, ADD core01contmat19 int NULL DEFAULT 0, ADD core01contmat20 int NULL DEFAULT 0, ADD core01contaprob1 int NULL DEFAULT 0, ADD core01contaprob2 int NULL DEFAULT 0, ADD core01contaprob3 int NULL DEFAULT 0, ADD core01contaprob4 int NULL DEFAULT 0, ADD core01contaprob5 int NULL DEFAULT 0, ADD core01contaprob6 int NULL DEFAULT 0, ADD core01contaprob7 int NULL DEFAULT 0, ADD core01contaprob8 int NULL DEFAULT 0, ADD core01contaprob9 int NULL DEFAULT 0, ADD core01contaprob10 int NULL DEFAULT 0, ADD core01contaprob11 int NULL DEFAULT 0, ADD core01contaprob12 int NULL DEFAULT 0, ADD core01contaprob13 int NULL DEFAULT 0, ADD core01contaprob14 int NULL DEFAULT 0, ADD core01contaprob15 int NULL DEFAULT 0, ADD core01contaprob16 int NULL DEFAULT 0, ADD core01contaprob17 int NULL DEFAULT 0, ADD core01contaprob18 int NULL DEFAULT 0, ADD core01contaprob19 int NULL DEFAULT 0, ADD core01contaprob20 int NULL DEFAULT 0, ADD core01contestado int NULL DEFAULT 0";}
	if ($dbversion==2504){$sql="ALTER TABLE core42contenidos ADD core42titulo varchar(50) NULL DEFAULT ''";}
	if ($dbversion==2505){$sql="UPDATE core42contenidos SET core42titulo=CONCAT(core42idcurso, ' ', core42fecha)";}
	if ($dbversion==2506){$sql=$u01."(28, 'ANALITICA', 'Analítica', 'S', 'S', 1, 0, 0)";}
	if ($dbversion==2507){$sql=$u08."(2801, 'Analítica', 'gm.php?id=2801', 'Analítica', '', '')";}
	if ($dbversion==2508){$sql="agregamodulo|2801|28|Seguimiento de acciones individuales|1|5|6";}
	if ($dbversion==2509){$sql=$u09."(2801, 1, 'Seguimiento de acciones individuales', 'unadrptrastros.php', 2801, 2801, 'S', '', 'Acceso a Campus')";}
	if ($dbversion==2510){$sql="agregamodulo|2802|28|Acceso a Campus|1|5|6";}
	if ($dbversion==2511){$sql=$u09."(2802, 1, 'Acceso a Campus', 'unadrptaccesoc.php', 2801, 2802, 'S', '', '')";}
	if ($dbversion==2512){$sql=$u08."(2802, 'Comportamiento', 'gm.php?id=2802', 'Comportamiento', '', '')";}
	if ($dbversion==2513){$sql="agregamodulo|2803|28|Mortalidad|1|5|6";}
	if ($dbversion==2514){$sql=$u09."(2803, 1, 'Mortalidad', 'unadrptmortalidad.php', 2802, 2803, 'S', '', '')";}
	if ($dbversion==2515){$sql="agregamodulo|2708|27|Envio de carnets|1|5|6";}
	if ($dbversion==2516){$sql=$u09."(2708, 1, 'Envio de carnets', 'gradenviocarnet.php', 7, 2708, 'S', '', '')";}
	if ($dbversion==2517){$sql="CREATE TABLE grad93opciones (grad93id int NOT NULL, grad93idsmtp int NULL DEFAULT 0)";}
	if ($dbversion==2518){$sql="ALTER TABLE grad93opciones ADD PRIMARY KEY(grad93id)";}
	if ($dbversion==2519){$sql="INSERT INTO grad93opciones (grad93id, grad93idsmtp) VALUES (1, 0)";}
	if ($dbversion==2520){$sql="agregamodulo|2793|27|Opciones|1|3";}
	if ($dbversion==2521){$sql=$u09."(2793, 1, 'Opciones', 'gradopciones.php', 2, 2793, 'S', '', '')";}
	if ($dbversion==2522){$sql="CREATE TABLE ceca11motivosrecalifica (ceca11consec int NOT NULL, ceca11id int NULL DEFAULT 0, ceca11activa varchar(1) NULL, ceca11nombre varchar(200) NULL, ceca11requiereautoriza varchar(1) NULL, ceca11porcentro varchar(1) NULL, ceca11requiereevidencia varchar(1) NULL, ceca11porestudiante varchar(1) NULL, ceca11porconsejeria varchar(1) NULL)";}
	if ($dbversion==2523){$sql="ALTER TABLE ceca11motivosrecalifica ADD PRIMARY KEY(ceca11id)";}
	if ($dbversion==2524){$sql="ALTER TABLE ceca11motivosrecalifica ADD UNIQUE INDEX ceca11motivosrecalifica_id(ceca11consec)";}
	if ($dbversion==2525){$sql="agregamodulo|2411|24|Motivos de recalificación|1|2|3|4|5|6|8";}
	if ($dbversion==2526){$sql=$u09."(2411, 1, 'Motivos de recalificación', 'cecamotivorecalifica.php', 2, 2411, 'S', '', '')";}
	if ($dbversion==2527){$sql="CREATE TABLE ceca12motivocentro (ceca12idmotivorec int NOT NULL, ceca12idcentro int NOT NULL, ceca12id int NULL DEFAULT 0, ceca12activo varchar(1) NULL)";}
	if ($dbversion==2528){$sql="ALTER TABLE ceca12motivocentro ADD PRIMARY KEY(ceca12id)";}
	if ($dbversion==2529){$sql="ALTER TABLE ceca12motivocentro ADD UNIQUE INDEX ceca12motivocentro_id(ceca12idmotivorec, ceca12idcentro)";}
	if ($dbversion==2530){$sql="agregamodulo|2412|24|Motivo Recalif - Centros|1|2|3|4|5|6";}
	if ($dbversion==2531){$sql="DROP TABLE ceca07modcalifica";}
	if ($dbversion==2532){$sql="ALTER TABLE ceca12motivocentro ADD INDEX ceca12motivocentro_padre(ceca12idmotivorec)";}
	if ($dbversion==2533){$sql="ALTER TABLE ceca11motivosrecalifica ADD ceca11general varchar(1) NULL DEFAULT 'N', ADD ceca11desdecampus varchar(1) NULL DEFAULT 'N'";}
	if ($dbversion==2534){$sql="CREATE TABLE ceca07solicitudrecal (ceca07consec int NOT NULL, ceca07serie int NOT NULL, ceca07id int NULL DEFAULT 0, ceca07estado int NULL DEFAULT 0, ceca07idestudiante int NULL DEFAULT 0, ceca07idperaca int NULL DEFAULT 0, ceca07idcurso int NULL DEFAULT 0, ceca07idactividad int NULL DEFAULT 0, ceca07idgrupo int NULL DEFAULT 0, ceca07idtutor int NULL DEFAULT 0, ceca07presentada int NULL DEFAULT 0, ceca07notaactual int NULL DEFAULT 0, ceca07idsolicita int NULL DEFAULT 0, ceca07idrolsolicita int NULL DEFAULT 0, ceca07fechaactualiza int NULL DEFAULT 0, ceca07minutoactualiza int NULL DEFAULT 0, ceca07notafinal int NULL DEFAULT 0, ceca07motivorechazo varchar(250) NULL)";}
	if ($dbversion==2535){$sql="ALTER TABLE ceca07solicitudrecal ADD PRIMARY KEY(ceca07id)";}
	if ($dbversion==2536){$sql="ALTER TABLE ceca07solicitudrecal ADD UNIQUE INDEX ceca07solicitudrecal_id(ceca07consec, ceca07serie)";}
	if ($dbversion==2537){$sql="CREATE TABLE ceca13rolsolicita (ceca13id int NOT NULL, ceca13nombre varchar(50) NULL)";}
	if ($dbversion==2538){$sql="ALTER TABLE ceca13rolsolicita ADD PRIMARY KEY(ceca13id)";}
	if ($dbversion==2539){$sql="CREATE TABLE ceca14estadorecal (ceca14id int NOT NULL, ceca14nombre varchar(50) NULL)";}
	if ($dbversion==2540){$sql="ALTER TABLE ceca14estadorecal ADD PRIMARY KEY(ceca14id)";}
	if ($dbversion==2541){$sql="INSERT INTO ceca13rolsolicita (ceca13id, ceca13nombre) VALUES (0, 'Estudiante'), (1, 'Tutor'), (2, 'Consejeria'), (3, 'Soporte Campus')";}
	if ($dbversion==2542){$sql="INSERT INTO ceca14estadorecal (ceca14id, ceca14nombre) VALUES (0, 'En solicitud'), (1, 'Aprobada'), (7, 'Nota Actualizada'), (9, 'Rechazada')";}
	if ($dbversion==2543){$sql=$unad70."(2411,2407,'ceca07solicitudrecal','ceca07id','ceca07idmotivosol','El dato esta incluido en Solicitudes de recalificacion', '')";}
	if ($dbversion==2544){$sql="ALTER TABLE ceca07solicitudrecal ADD ceca07fechasol int NULL DEFAULT 0, ADD ceca07idmotivosol int NULL DEFAULT 0, ADD ceca07detallesol Text NULL, ADD ceca07idorigenevid int NULL DEFAULT 0, ADD ceca07idarchvioevid int NULL DEFAULT 0, ADD ceca07detallecambio Text NULL";}
	if ($dbversion==2545){$sql="INSERT INTO unad46tipoperiodo (unad46id, unad46nombre) VALUES (2, 'Practicas'), (3, 'Convenios'), (4, 'Continuada'), (5, 'Permanente')";}
	if ($dbversion==2546){$sql="ALTER TABLE unad40curso DROP INDEX unad40curso_id";}
	if ($dbversion==2547){$sql="ALTER TABLE unad40curso ADD unad40fuente int NOT NULL DEFAULT 0, ADD unad40titulo varchar(50) NULL DEFAULT ''";}
	if ($dbversion==2548){$sql="ALTER TABLE unad40curso ADD UNIQUE INDEX unad40curso_id(unad40fuente, unad40consec)";}
	if ($dbversion==2549){$sql="UPDATE unad40curso SET unad40titulo=unad40consec";}
	if ($dbversion==2550){$sql="INSERT INTO ceca03estadoactividad (ceca03id, ceca03nombre) VALUES (8, 'Reportada')";}
	if ($dbversion==2551){$sql="DROP TABLE ceca08estadisticacurso";}
	if ($dbversion==2552){$sql="CREATE TABLE ceca08estadisticacurso (ceca08idperaca int NOT NULL, ceca08idcurso int NOT NULL, ceca08tiporegistro int NOT NULL, ceca08idtutor int NOT NULL, ceca08idzona int NOT NULL, ceca08idcentro int NOT NULL, ceca08idescuela int NOT NULL, ceca08idprograma int NOT NULL, ceca08sexo varchar(1) NOT NULL, ceca08edad int NOT NULL, ceca08id int NULL DEFAULT 0, ceca08numestudiantes int NULL DEFAULT 0, ceca08numresagados int NULL DEFAULT 0, ceca08numreprobados int NULL DEFAULT 0, ceca08numinasistentes int NULL DEFAULT 0, ceca08promedio75 Decimal(15,2) NULL DEFAULT 0, ceca08promedio25 Decimal(15,2) NULL DEFAULT 0, ceca08promediototal Decimal(15,2) NULL DEFAULT 0, ceca08puntaje75 int NULL DEFAULT 0, ceca08puntaje25 int NULL DEFAULT 0, ceca08suma75 int NULL DEFAULT 0, ceca08suma25 int NULL DEFAULT 0)";}
	if ($dbversion==2553){$sql="ALTER TABLE ceca08estadisticacurso ADD PRIMARY KEY(ceca08id)";}
	if ($dbversion==2554){$sql="ALTER TABLE ceca08estadisticacurso ADD UNIQUE INDEX ceca08estadisticacurso_id(ceca08idperaca, ceca08idcurso, ceca08tiporegistro, ceca08idtutor, ceca08idzona, ceca08idcentro, ceca08idescuela, ceca08idprograma, ceca08sexo, ceca08edad)";}
	if ($dbversion==2555){$sql="agregamodulo|2415|24|Resumen ejecutivo|1|5|6";}
	if ($dbversion==2556){$sql=$u09."(2415, 1, 'Resumen ejecutivo', 'cecaejecutivo.php', 11, 2415, 'S', '', '')";}
	if ($dbversion==2557){$sql="INSERT INTO core09programa(core09codigo, core09id, core09nombre, core09idescuela, core09iddirector, core09idversionactual, core09activo, core09idtipocaracterizacion, cara09codsnies, cara09nivelformacion, core09codgrados) VALUES (0, 0, '{Ninguno}', 0, 0, 0, 'N', 0, '', 0, 0)";}
	if ($dbversion==2558){$sql="INSERT INTO unad46tipoperiodo (unad46id, unad46nombre) VALUES (6, 'Formador de formadores'), (7, 'UNAD English'), (8, 'Uso Administrativo')";}
	if ($dbversion==2559){$sql="agregamodulo|2416|24|Actividades por tutor|1|5|6";}
	if ($dbversion==2560){$sql=$u09."(2416, 1, 'Actividades por tutor', 'cecarptactivtutor.php', 11, 2416, 'S', '', '')";}
	if ($dbversion==2561){$sql="ALTER TABLE ceca07solicitudrecal ADD ceca07fechasol int NULL DEFAULT 0, ADD ceca07idmotivosol int NULL DEFAULT 0";}
	if ($dbversion==2562){$sql="agregamodulo|2417|24|Calificaciones pendientes (Rpt)|1|5|6|12|1701|1710";}
	if ($dbversion==2563){$sql=$u09."(2417, 1, 'Calificaciones pendientes', 'cecarptpendientes.php', 11, 2417, 'S', '', '')";}
	if ($dbversion==2564){$sql="CREATE TABLE unae18rangoedad (unae18consec int NOT NULL, unae18id int NULL DEFAULT 0, unae18estado varchar(1) NULL, unae18titulo varchar(100) NULL)";}
	if ($dbversion==2565){$sql="ALTER TABLE unae18rangoedad ADD PRIMARY KEY(unae18id)";}
	if ($dbversion==2566){$sql="ALTER TABLE unae18rangoedad ADD UNIQUE INDEX unae18rangoedad_id(unae18consec)";}
	if ($dbversion==2567){$sql="agregamodulo|218|1|Rangos de edad|1|2|3|4|5|6|8|17";}
	if ($dbversion==2568){$sql=$u09."(218, 1, 'Rangos de edad', 'unadrangoedad.php', 2, 218, 'S', '', '')";}
	if ($dbversion==2569){$sql="CREATE TABLE unae19rango (unae19idrangoedad int NOT NULL, unae19consec int NOT NULL, unae19id int NULL DEFAULT 0, unae19titulo varchar(100) NULL, unae19base int NULL DEFAULT 0, unae19techo int NULL DEFAULT 0)";}
	if ($dbversion==2570){$sql="ALTER TABLE unae19rango ADD PRIMARY KEY(unae19id)";}
	if ($dbversion==2571){$sql="ALTER TABLE unae19rango ADD UNIQUE INDEX unae19rango_id(unae19idrangoedad, unae19consec)";}
	if ($dbversion==2572){$sql="ALTER TABLE unae19rango ADD INDEX unae19rango_padre(unae19idrangoedad)";}
	if ($dbversion==2573){$sql="agregamodulo|219|1|Rangos de edad - Rangos|1|2|3|4|5|6|8";}
	if ($dbversion==2574){$sql="CREATE TABLE unae20rangosdist (unae20idrangoedad int NOT NULL, unae20edad int NOT NULL, unae20id int NULL DEFAULT 0, unae20idrango int NULL DEFAULT 0)";}
	if ($dbversion==2575){$sql="ALTER TABLE unae20rangosdist ADD PRIMARY KEY(unae20id)";}
	if ($dbversion==2576){$sql="ALTER TABLE unae20rangosdist ADD UNIQUE INDEX unae20rangosdist_id(unae20idrangoedad, unae20edad)";}
	if ($dbversion==2577){$sql="ALTER TABLE unae20rangosdist ADD INDEX unae20rangosdist_padre(unae20idrangoedad)";}
	if ($dbversion==2578){$sql="INSERT INTO unae19rango (unae19idrangoedad, unae19consec, unae19id, unae19titulo, unae19base, unae19techo) VALUES (0, 0, 0, 'Ninguno', 0, 0)";}
	if ($dbversion==2579){$sql="CREATE TABLE bita54tlproyecto (bita54consec int NOT NULL, bita54id int NULL DEFAULT 0, bita54titulo varchar(200) NULL, bita54descripcion Text NULL, bita54estado int NULL DEFAULT 0, bita54idpropietario int NULL DEFAULT 0, bita54idunidadmedia int NULL DEFAULT 0)";}
	if ($dbversion==2580){$sql="ALTER TABLE bita54tlproyecto ADD PRIMARY KEY(bita54id)";}
	if ($dbversion==2581){$sql="ALTER TABLE bita54tlproyecto ADD UNIQUE INDEX bita54tlproyecto_id(bita54consec)";}
	if ($dbversion==2582){$sql="agregamodulo|1554|15|Lineas de tiempo|1|2|3|4|5|6|8";}
	if ($dbversion==2583){$sql=$u09."(1554, 1, 'Lineas de tiempo', 'bitalineatiempo.php', 1506, 1554, 'S', '', '')";}
	if ($dbversion==2584){$sql="CREATE TABLE bita55lineas (bita55idproyecto int NOT NULL, bita55consec int NOT NULL, bita55id int NULL DEFAULT 0, bita55titulo varchar(200) NULL, bita55descripcion Text NULL, bita55agnoini int NULL DEFAULT 0, bita55agnofin int NULL DEFAULT 0)";}
	if ($dbversion==2585){$sql="ALTER TABLE bita55lineas ADD PRIMARY KEY(bita55id)";}
	if ($dbversion==2586){$sql="ALTER TABLE bita55lineas ADD UNIQUE INDEX bita55lineas_id(bita55idproyecto, bita55consec)";}
	if ($dbversion==2587){$sql="ALTER TABLE bita55lineas ADD INDEX bita55lineas_padre(bita55idproyecto)";}
	if ($dbversion==2588){$sql="agregamodulo|1555|15|Lineas de tiempo - Lineas|1|2|3|4|5|6|8";}
	if ($dbversion==2589){$sql="CREATE TABLE bita56tlevento (bita56idproyecto int NOT NULL, bita56idlinea int NOT NULL, bita56consec int NOT NULL, bita56id int NULL DEFAULT 0, bita56tipoevento int NULL DEFAULT 0, bita56titulo varchar(200) NULL, bita56descripcion Text NULL, bita56fechainicial int NULL DEFAULT 0, bita56fechafinal int NULL DEFAULT 0, bita56idorigen int NULL DEFAULT 0, bita56idarchivo int NULL DEFAULT 0)";}
	if ($dbversion==2590){$sql="ALTER TABLE bita56tlevento ADD PRIMARY KEY(bita56id)";}
	if ($dbversion==2591){$sql="ALTER TABLE bita56tlevento ADD UNIQUE INDEX bita56tlevento_id(bita56idproyecto, bita56idlinea, bita56consec)";}
	if ($dbversion==2592){$sql="ALTER TABLE bita56tlevento ADD INDEX bita56tlevento_padre(bita56idproyecto)";}
	if ($dbversion==2593){$sql="agregamodulo|1556|15|Lineas de tiempo - Eventos|1|2|3|4|5|6|8";}
	if ($dbversion==2594){$sql="CREATE TABLE bita57tltipoevento (bita57id int NOT NULL, bita57nombre varchar(50) NULL)";}
	if ($dbversion==2595){$sql="ALTER TABLE bita57tltipoevento ADD PRIMARY KEY(bita57id)";}
	if ($dbversion==2596){$sql="INSERT INTO bita57tltipoevento (bita57id, bita57nombre) VALUES (0, 'Evento'), (1, 'Lapso de Tiempo'), (2, 'Evento Global')";}
	if ($dbversion==2597){$sql="ALTER TABLE core16actamatricula ADD core16aplazados varchar(250) NULL DEFAULT '', ADD core16cancelados varchar(250) NULL DEFAULT '', ADD core16fechamatricula int NULL DEFAULT 0, ADD core16paraseguimiento int NULL DEFAULT 0";}
	if ($dbversion==2598){$sql="CREATE TABLE core17origenmatricula (core17id int NOT NULL, core17nombre varchar(50) NULL)";}
	if ($dbversion==2599){$sql="ALTER TABLE core17origenmatricula ADD PRIMARY KEY(core17id)";}
	if ($dbversion==2600){$sql="INSERT INTO core17origenmatricula (core17id, core17nombre) VALUES (0, 'Manual'), (1, 'WebService')";}
	}
if (($dbversion>2600)&&($dbversion<2701)){
	if ($dbversion==2601){$sql="CREATE TABLE core30estadomatricula (core30id int NOT NULL, core30nombre varchar(50) NULL)";}
	if ($dbversion==2602){$sql="ALTER TABLE core30estadomatricula ADD PRIMARY KEY(core30id)";}
	if ($dbversion==2603){$sql="INSERT INTO core30estadomatricula (core30id, core30nombre) VALUES (0, 'En Elaboración'), (7, 'Completa'), (9, 'Cancelada')";}
	if ($dbversion==2604){$sql=$unad70."(2242,2211,'core11plandeestudio','core11id','core11idcontenido','El dato esta incluido en Plan de estudios', '')";}
	if ($dbversion==2605){$sql="DROP TABLE even01tipoevento";}
	if ($dbversion==2606){$sql="CREATE TABLE even01tipoevento (even01consec int NOT NULL, even01id int NULL DEFAULT 0, even01nombre varchar(100) NULL)";}
	//2607 queda libre
	if ($dbversion==2608){$sql="agregamodulo|1901|19|Tipos de enventos|1|2|3|4|5|6";}
	if ($dbversion==2609){$sql=$u09."(1901, 1, 'Tipos de enventos', 'eventipoevento.php', 2, 1901, 'S', '', '')";}
	if ($dbversion==2610){$sql="CREATE TABLE even41categoria (even41idtipoevento int NOT NULL, even41consec int NOT NULL, even41id int NULL DEFAULT 0, even41activo varchar(1) NULL, even41titulo varchar(100) NULL)";}
	if ($dbversion==2611){$sql="ALTER TABLE even41categoria ADD PRIMARY KEY(even41id)";}
	if ($dbversion==2612){$sql="ALTER TABLE even41categoria ADD UNIQUE INDEX even41categoria_id(even41idtipoevento, even41consec)";}
	if ($dbversion==2613){$sql="ALTER TABLE even41categoria ADD INDEX even41categoria_padre(even41idtipoevento)";}
	if ($dbversion==2614){$sql="agregamodulo|1941|19|Tipos de enventos - Categorias|1|2|3|4|5|6|8";}
	if ($dbversion==2615){$sql="ALTER TABLE even02evento ADD even02categoria int NULL DEFAULT 0";}
	if ($dbversion==2616){$sql="INSERT INTO even41categoria (even41idtipoevento, even41consec, even41id, even41activo, even41titulo) VALUES (0, 0, 0, 'N', 'Ninguno')";}
	if ($dbversion==2617){$sql="ALTER TABLE even01tipoevento DROP PRIMARY KEY";}
	if ($dbversion==2618){$sql="ALTER TABLE even01tipoevento ADD PRIMARY KEY(even01id)";}
	if ($dbversion==2619){$sql="ALTER TABLE even01tipoevento ADD UNIQUE INDEX even01tipoevento_id(even01consec)";}
	if ($dbversion==2620){$sql="ALTER TABLE core16actamatricula ADD core16tipomatricula int NULL DEFAULT 0, ADD core16idconvenio int NULL DEFAULT 0";}
	if ($dbversion==2621){$sql="CREATE TABLE core08tipomatricula (core08id int NOT NULL, core08nombre varchar(50) NULL)";}
	if ($dbversion==2622){$sql="ALTER TABLE core08tipomatricula ADD PRIMARY KEY(core08id)";}
	if ($dbversion==2623){$sql="INSERT INTO core08tipomatricula (core08id, core08nombre) VALUES (0, 'Campus'), (1, 'Externo')";}
	if ($dbversion==2624){$sql=$unad70."(2250,2216,'core16actamatricula','core16id','core16idconvenio','El dato esta incluido en Actas de matricula', '')";}
	if ($dbversion==2625){$sql="UPDATE core29estadogrupo SET core29nombre='Asignado' WHERE core29id=0";}
	if ($dbversion==2626){$sql="agregamodulo|2204|22|Matricula|2|3|4";}
	if ($dbversion==2627){$sql="CREATE TABLE unae21dominiosrestr (unae21consec int NOT NULL, unae21id int NULL DEFAULT 0, unae21dominio varchar(100) NULL, unae21motivo Text NULL)";}
	if ($dbversion==2628){$sql="ALTER TABLE unae21dominiosrestr ADD PRIMARY KEY(unae21id)";}
	if ($dbversion==2629){$sql="ALTER TABLE unae21dominiosrestr ADD UNIQUE INDEX unae21dominiosrestr_id(unae21consec)";}
	if ($dbversion==2630){$sql="agregamodulo|221|1|Dominios restringidos|1|2|3|4|5|6";}
	if ($dbversion==2631){$sql=$u09."(221, 1, 'Dominios restringidos', 'unaddominiorest.php', 6, 221, 'S', '', '')";}
	if ($dbversion==2632){$sql="ALTER TABLE even02evento ADD even02formainscripcion int NULL DEFAULT 0";}
	if ($dbversion==2633){$sql=$u08."(2302, 'Acompañamiento', 'gm.php?id=2302', 'Acompañamiento', 'Accompaniment', 'Acompanhamento'), (3, 'Datos básicos', 'gm.php?id=3', 'Basic data', '', 'Dados básicos')";}
	if ($dbversion==2634){$sql="ALTER TABLE cara01encuesta ADD cara01numacompanamentos int NULL DEFAULT 0";}
	if ($dbversion==2635){$sql=$unad70."(2301,2323,'cara23acompanamento','cara23id','cara23idencuesta','El dato esta incluido en Acompanamiento', '')";}
	if ($dbversion==2636){$sql=$unad70."(2324,2323,'cara23acompanamento','cara23id','cara23catedra_avance','El dato esta incluido en Acompanamiento', '')";}
	if ($dbversion==2637){$sql="CREATE TABLE cara23acompanamento (cara23idencuesta int NOT NULL, cara23consec int NOT NULL, cara23id int NULL DEFAULT 0, cara23idtercero int NULL DEFAULT 0, cara23idtipo int NULL DEFAULT 0, cara23estado int NULL DEFAULT 0, cara23asisteinduccion int NULL DEFAULT 0, cara23asisteinmersioncv int NULL DEFAULT 0, cara23catedra_skype int NULL DEFAULT 0, cara23catedra_bler1 int NULL DEFAULT 0, cara23catedra_bler2 int NULL DEFAULT 0, cara23catedra_webconf int NULL DEFAULT 0, cara23catedra_avance int NULL DEFAULT 0, cara23catedra_criterio int NULL DEFAULT 0, cara23catedra_acciones Text NULL, cara23catedra_resultados Text NULL, cara23catedra_segprev Text NULL, cara23cursos_total int NULL DEFAULT 0, cara23cursos_siningre int NULL DEFAULT 0, cara23cursos_porcing Decimal(15,2) NULL DEFAULT 0, cara23cursos_menor200 int NULL DEFAULT 0, cara23cursos_porcperdida Decimal(15,2) NULL DEFAULT 0, cara23cursos_criterio int NULL DEFAULT 0, cara23cursos_otros Text NULL, cara23cursos_accionlider Text NULL, cara23aler_sociodem Text NULL, cara23aler_psico Text NULL, cara23aler_academ Text NULL, cara23aler_econom Text NULL, cara23aler_externo Text NULL, cara23aler_interno Text NULL, cara23aler_nivel Text NULL, cara23aler_criterio int NULL DEFAULT 0, cara23comp_digital int NULL DEFAULT 0, cara23comp_cuanti int NULL DEFAULT 0, cara23comp_lectora int NULL DEFAULT 0, cara23comp_ingles int NULL DEFAULT 0, cara23comp_criterio int NULL DEFAULT 0, cara23nivela_digital Text NULL, cara23nivela_cuanti Text NULL, cara23nivela_lecto Text NULL, cara23nivela_ingles Text NULL, cara23nivela_exito Text NULL, cara23contacto_efectivo int NULL DEFAULT 0, cara23contacto_forma Text NULL, cara23contacto_observa Text NULL, cara23contacto_novedad Text NULL, cara23factorriesgo int NULL DEFAULT 0, cara23zonal_retro Text NULL, cara23zonal_fecha int NULL DEFAULT 0, cara23zonal_idlider int NULL DEFAULT 0)";}
	if ($dbversion==2638){$sql="ALTER TABLE cara23acompanamento ADD PRIMARY KEY(cara23id)";}
	if ($dbversion==2639){$sql="ALTER TABLE cara23acompanamento ADD UNIQUE INDEX cara23acompanamento_id(cara23idencuesta, cara23consec)";}
	if ($dbversion==2640){$sql="agregamodulo|2323|23|Acompañamiento|1|2|3|4|5|6|8";}
	if ($dbversion==2641){$sql=$u09."(2323, 1, 'Acompañamiento', 'caraacompana.php', 2302, 2323, 'S', '', '')";}
	if ($dbversion==2642){$sql="CREATE TABLE cara24avancecatedra (cara24consec int NOT NULL, cara24id int NULL DEFAULT 0, cara24orden int NULL DEFAULT 0, cara24jornada int NULL DEFAULT 0, cara24activa varchar(1) NULL, cara24titulo varchar(100) NULL, cara24vrriesgo int NULL DEFAULT 0)";}
	if ($dbversion==2643){$sql="ALTER TABLE cara24avancecatedra ADD PRIMARY KEY(cara24id)";}
	if ($dbversion==2644){$sql="ALTER TABLE cara24avancecatedra ADD UNIQUE INDEX cara24avancecatedra_id(cara24consec)";}
	if ($dbversion==2645){$sql="agregamodulo|2324|23|Avance Catedra Unadista|1|2|3|4|5|6|8";}
	if ($dbversion==2646){$sql=$u09."(2324, 1, 'Avance Catedra Unadista', 'caraavancecat.php', 2, 2324, 'S', '', '')";}
	if ($dbversion==2647){$sql="INSERT INTO cara24avancecatedra (cara24consec, cara24id, cara24orden, cara24jornada, cara24activa, cara24titulo, cara24vrriesgo) VALUES (0, 0, 0, 0, 'N', '{Sin Definir}', 0)";}
	if ($dbversion==2648){$sql="agregamodulo|2452|24|Avance por programa|1|5|6|12";}
	if ($dbversion==2649){$sql=$u09."(2452, 1, 'Avance por programa', 'cecaavanceprog.php', 11, 2452, 'S', '', '')";}
	if ($dbversion==2650){$sql="ALTER TABLE core16actamatricula ADD core16edad int NULL DEFAULT 0";}
	if ($dbversion==2651){$sql="CREATE TABLE cara25accionescat (cara25consec int NOT NULL, cara25id int NULL DEFAULT 0, cara25orden int NULL DEFAULT 0, cara25activa varchar(1) NULL, cara25titulo varchar(100) NULL)";}
	if ($dbversion==2652){$sql="ALTER TABLE cara25accionescat ADD PRIMARY KEY(cara25id)";}
	if ($dbversion==2653){$sql="ALTER TABLE cara25accionescat ADD UNIQUE INDEX cara25accionescat_id(cara25consec)";}
	if ($dbversion==2654){$sql="agregamodulo|2325|23|Acciones catedra|1|2|3|4|5|6|8";}
	if ($dbversion==2655){$sql=$u09."(2325, 1, 'Acciones catedra', 'caraaccionescat.php', 3, 2325, 'S', '', '')";}
	if ($dbversion==2656){$sql="CREATE TABLE cara26resultcat (cara26consec int NOT NULL, cara26id int NULL DEFAULT 0, cara26orden int NULL DEFAULT 0, cara26activa varchar(1) NULL, cara26titulo varchar(100) NULL)";}
	if ($dbversion==2657){$sql="ALTER TABLE cara26resultcat ADD PRIMARY KEY(cara26id)";}
	if ($dbversion==2658){$sql="ALTER TABLE cara26resultcat ADD UNIQUE INDEX cara26resultcat_id(cara26consec)";}
	if ($dbversion==2659){$sql="agregamodulo|2326|23|Resultados catedra|1|2|3|4|5|6|8";}
	if ($dbversion==2660){$sql=$u09."(2326, 1, 'Resultados catedra', 'cararesultcat.php', 3, 2326, 'S', '', '')";}
	if ($dbversion==2661){$sql="CREATE TABLE cara27mediocont (cara27consec int NOT NULL, cara27id int NULL DEFAULT 0, cara27orden int NULL DEFAULT 0, cara27activa varchar(1) NULL, cara27titulo varchar(100) NULL)";}
	if ($dbversion==2662){$sql="ALTER TABLE cara27mediocont ADD PRIMARY KEY(cara27id)";}
	if ($dbversion==2663){$sql="ALTER TABLE cara27mediocont ADD UNIQUE INDEX cara27mediocont_id(cara27consec)";}
	if ($dbversion==2664){$sql="agregamodulo|2327|23|Medios de contacto|1|2|3|4|5|6|8";}
	if ($dbversion==2665){$sql=$u09."(2327, 1, 'Medios de contacto', 'caramediocont.php', 3, 2327, 'S', '', '')";}
	if ($dbversion==2666){$sql="INSERT INTO cara27mediocont (cara27consec, cara27id, cara27orden, cara27activa, cara27titulo) VALUES (0, 0, 0, 'N', 'No se pudo contactar')";}
	if ($dbversion==2667){$sql="ALTER TABLE cara25accionescat ADD cara25puntaje int NULL DEFAULT 0";}
	if ($dbversion==2668){$sql="CREATE TABLE cara28actividades (cara28consec int NOT NULL, cara28id int NULL DEFAULT 0, cara28tipoactividad int NULL DEFAULT 0, cara28estado int NULL DEFAULT 0, cara28fecha int NULL DEFAULT 0, cara28horaini int NULL DEFAULT 0, cara28minini int NULL DEFAULT 0, cara28horafin int NULL DEFAULT 0, cara28minfin int NULL DEFAULT 0, cara28idresponsable int NULL DEFAULT 0, cara28idzona int NULL DEFAULT 0, cara28idcentro int NULL DEFAULT 0, cara28lugar varchar(200) NULL, cara28detalle Text NULL)";}
	if ($dbversion==2669){$sql="ALTER TABLE cara28actividades ADD PRIMARY KEY(cara28id)";}
	if ($dbversion==2670){$sql="ALTER TABLE cara28actividades ADD UNIQUE INDEX cara28actividades_id(cara28consec)";}
	if ($dbversion==2671){$sql="agregamodulo|2328|23|Actividades acompañamiento|1|2|3|4|5|6|8|17";}
	if ($dbversion==2672){$sql=$u09."(2328, 1, 'Actividades de acompañamiento', 'caraactividadacomp.php', 2302, 2328, 'S', '', '')";}
	if ($dbversion==2673){$sql="CREATE TABLE cara29actividadasiste (cara29idactividad int NOT NULL, cara29idtercero int NOT NULL, cara29id int NULL DEFAULT 0, cara29estado int NULL DEFAULT 0)";}
	if ($dbversion==2674){$sql="ALTER TABLE cara29actividadasiste ADD PRIMARY KEY(cara29id)";}
	if ($dbversion==2675){$sql="ALTER TABLE cara29actividadasiste ADD UNIQUE INDEX cara29actividadasiste_id(cara29idactividad, cara29idtercero)";}
	if ($dbversion==2676){$sql="ALTER TABLE cara29actividadasiste ADD INDEX cara29actividadasiste_padre(cara29idactividad)";}
	if ($dbversion==2677){$sql="agregamodulo|2329|23|Actividades acompa -Asistentes|1|2|3|4|5|6|8";}
	if ($dbversion==2678){$sql="CREATE TABLE cara30tipoactividad (cara30id int NOT NULL, cara30nombre varchar(100) NULL, cara30activa int NULL DEFAULT 1)";}
	if ($dbversion==2679){$sql="ALTER TABLE cara30tipoactividad ADD PRIMARY KEY(cara30id)";}
	if ($dbversion==2680){$sql="INSERT INTO cara30tipoactividad (cara30id, cara30nombre, cara30activa) VALUES (1, 'Inducción General', 1), (2, 'Inducción Campus Virtual', 1), (3, 'Refuerzo Competencias Digitales', 1), (4, 'Refuerzo Competencias Cuantitativas', 1), (5, 'Refuerzo Competencias Lectoras', 1), (6, 'Refuerzo Competencias Ingles', 1), (7, 'Camino al Exito', 1)";}
	if ($dbversion==2681){$sql="CREATE TABLE cara31estadoasiste (cara31id int NOT NULL, cara31nombre varchar(100) NULL)";}
	if ($dbversion==2682){$sql="ALTER TABLE cara31estadoasiste ADD PRIMARY KEY(cara31id)";}
	if ($dbversion==2683){$sql="INSERT INTO cara31estadoasiste (cara31id, cara31nombre) VALUES (0, 'Invitado'), (1, 'Inscrito'), (7, 'Asistente')";}
	if ($dbversion==2684){$sql="CREATE TABLE cara32estadoactividad (cara32id int NOT NULL, cara32nombre varchar(100) NULL)";}
	if ($dbversion==2685){$sql="ALTER TABLE cara32estadoactividad ADD PRIMARY KEY(cara32id)";}
	if ($dbversion==2686){$sql="INSERT INTO cara32estadoactividad (cara32id, cara32nombre) VALUES (0, 'Borrador'), (1, 'Vigente'), (3, 'Aplicada')";}
	if ($dbversion==2687){$sql="ALTER TABLE even02evento ADD even02modalidad int NULL DEFAULT 0, ADD even02url varchar(250) NULL DEFAULT ''";}
	if ($dbversion==2688){$sql="ALTER TABLE cara23acompanamento ADD cara23aplaza int NULL DEFAULT 0, ADD cara23seretira int NULL DEFAULT 0";}
	if ($dbversion==2689){$sql="agregamodulo|1761|17|Ajustar oferentes|1|3";}
	if ($dbversion==2690){$sql=$u09."(1761, 1, 'Ajustar oferentes', 'oferevprograma.php', 7, 1761, 'S', '', '')";}
	if ($dbversion==2691){$sql="ALTER TABLE cara01encuesta ADD cara01idperiodoacompana int NULL DEFAULT 0, ADD cara01fechacierreacom int NULL DEFAULT 0, ADD cara01formaacomp int NULL DEFAULT 0";}
	if ($dbversion==2692){$sql="ALTER TABLE cara28actividades ADD cara28formato int NULL DEFAULT 0";}
	if ($dbversion==2693){$sql="CREATE TABLE olab41tipopractica (olab41consec int NOT NULL, olab41id int NULL DEFAULT 0, olab41activa varchar(1) NULL, olab41titulo varchar(200) NULL, olab41escuela int NULL DEFAULT 0, olab41numpracticas int NULL DEFAULT 0, oalb41porcplanestudios int NULL DEFAULT 0, olab41nivelrequerido int NULL DEFAULT 0, olab41edadminima int NULL DEFAULT 0)";}
	if ($dbversion==2694){$sql="ALTER TABLE olab41tipopractica ADD PRIMARY KEY(olab41id)";}
	if ($dbversion==2695){$sql="ALTER TABLE olab41tipopractica ADD UNIQUE INDEX olab41tipopractica_id(olab41consec)";}
	if ($dbversion==2696){$sql="agregamodulo|2141|21|Tipos de practicas|1|2|3|4|5|6|8";}
	if ($dbversion==2697){$sql=$u09."(2141, 1, 'Tipos de practicas', 'olabtipopractica.php', 2, 2141, 'S', '', '')";}
	if ($dbversion==2698){$sql=$unad70."(2602,2142,'olab42tipopracdoc','olab42id','olab42idtipodocgd','El dato esta incluido en Documentacion requerida', '')";}
	if ($dbversion==2699){$sql="CREATE TABLE olab42tipopracdoc (olab42idtipopractica int NOT NULL, olab42consec int NOT NULL, olab42id int NULL DEFAULT 0, olab42activo varchar(1) NULL, olab42nombredoc varchar(100) NULL, olab42unicavez varchar(1) NULL, olab42controlvence varchar(1) NULL, olab42idtipodocgd int NULL DEFAULT 0)";}
	if ($dbversion==2700){$sql="ALTER TABLE olab42tipopracdoc ADD PRIMARY KEY(olab42id)";}
	}
if (($dbversion>2700)&&($dbversion<2801)){
	if ($dbversion==2701){$sql="ALTER TABLE olab42tipopracdoc ADD UNIQUE INDEX olab42tipopracdoc_id(olab42idtipopractica, olab42consec)";}
	if ($dbversion==2702){$sql="ALTER TABLE olab42tipopracdoc ADD INDEX olab42tipopracdoc_padre(olab42idtipopractica)";}
	if ($dbversion==2703){$sql="agregamodulo|2142|21|Tipos practica - Documentos|1|2|3|4|5|6";}
	if ($dbversion==2704){$sql=$u08."(2104, 'Prácticas', 'gm.php?id=2104', 'Prácticas', 'Practices', 'Prácticas')";}
	if ($dbversion==2705){$sql="agregamodulo|2143|21|Candidatos|1|3|5|6";}
	if ($dbversion==2706){$sql=$u09."(2143, 1, 'Candidatos', 'olabcandidatoprac.php', 2104, 2143, 'S', '', '')";}
	if ($dbversion==2707){$sql="CREATE TABLE olab43estadopractica (olab43id int NOT NULL, olab43nombre varchar(50) NULL)";}
	if ($dbversion==2708){$sql="ALTER TABLE olab43estadopractica ADD PRIMARY KEY(olab43id)";}
	if ($dbversion==2709){$sql="INSERT INTO olab43estadopractica (olab43id, olab43nombre) VALUES (-1, 'No Aplica'), (0, 'Pendiente'), (1, 'Candidato'), (3, 'Autorizada'), (5, 'En ejecuci&oacute;n'), (7, 'Finalizada')";}
	if ($dbversion==2710){$sql="agregamodulo|2144|21|Practicantes|1|3|5|6";}
	if ($dbversion==2711){$sql=$u09."(2144, 1, 'Practicantes', 'olabpracticante.php', 2104, 2144, 'S', '', '')";}
	if ($dbversion==2712){$sql="ALTER TABLE core01estprograma ADD core01estadopractica int NULL DEFAULT -1";}
	if ($dbversion==2713){$sql="agregamodulo|2147|21|Escenarios de practica|1|2|3|4|5|6|8";}
	if ($dbversion==2714){$sql=$u09."(2147, 1, 'Escenarios de practica', 'olabescpractica.php', 2104, 2147, 'S', '', '')";}
	if ($dbversion==2715){$sql="agregamodulo|2148|21|Convenios de practica|1|2|3|4|5|6|8";}
	if ($dbversion==2716){$sql=$u09."(2148, 1, 'Convenios de practica', 'olabconvpractica.php', 2104, 2148, 'S', '', '')";}
	if ($dbversion==2717){$sql="CREATE TABLE olab50jornadas (olab50idescenario int NOT NULL, olab50numjornada int NOT NULL, olab50id int NULL DEFAULT 0, olab50idiasem int NULL DEFAULT 0, olab50fecha int NULL DEFAULT 0, olab50estado int NULL DEFAULT 0, olab50detalle Text NULL)";}
	if ($dbversion==2718){$sql="ALTER TABLE olab50jornadas ADD PRIMARY KEY(olab50idescenario, olab50numjornada)";}
	if ($dbversion==2719){$sql="ALTER TABLE olab50jornadas ADD INDEX olab50jornadas_padre(olab50idescenario)";}
	if ($dbversion==2720){$sql="agregamodulo|2150|21|Jornadas|1|2|3|4|5|6";}
	if ($dbversion==2721){$sql="CREATE TABLE olab49asistencia (olab49idcupo int NOT NULL, olab49id int NULL DEFAULT 0, olab49idescenario int NULL DEFAULT 0, olab49idestudiante int NULL DEFAULT 0, olab49numjornadas int NULL DEFAULT 0, olab49j01 int NULL DEFAULT 0, olab49j02 int NULL DEFAULT 0, olab49j03 int NULL DEFAULT 0, olab49j04 int NULL DEFAULT 0, olab49j05 int NULL DEFAULT 0, olab49j06 int NULL DEFAULT 0, olab49j07 int NULL DEFAULT 0, olab49j08 int NULL DEFAULT 0, olab49j09 int NULL DEFAULT 0, olab49j10 int NULL DEFAULT 0, olab49j11 int NULL DEFAULT 0, olab49j12 int NULL DEFAULT 0, olab49j13 int NULL DEFAULT 0, olab49j14 int NULL DEFAULT 0, olab49j15 int NULL DEFAULT 0, olab49j16 int NULL DEFAULT 0, olab49j17 int NULL DEFAULT 0, olab49j18 int NULL DEFAULT 0, olab49j19 int NULL DEFAULT 0, olab49j20 int NULL DEFAULT 0, olab49j21 int NULL DEFAULT 0, olab49j22 int NULL DEFAULT 0, olab49j23 int NULL DEFAULT 0, olab49j24 int NULL DEFAULT 0, olab49j25 int NULL DEFAULT 0, olab49j26 int NULL DEFAULT 0, olab49j27 int NULL DEFAULT 0, olab49j28 int NULL DEFAULT 0, olab49j29 int NULL DEFAULT 0, olab49j30 int NULL DEFAULT 0, olab49j31 int NULL DEFAULT 0, olab49j32 int NULL DEFAULT 0, olab49j33 int NULL DEFAULT 0, olab49j34 int NULL DEFAULT 0, olab49j35 int NULL DEFAULT 0, olab49j36 int NULL DEFAULT 0, olab49j37 int NULL DEFAULT 0, olab49j38 int NULL DEFAULT 0, olab49j39 int NULL DEFAULT 0, olab49j40 int NULL DEFAULT 0, olab49j41 int NULL DEFAULT 0, olab49j42 int NULL DEFAULT 0, olab49j43 int NULL DEFAULT 0, olab49j44 int NULL DEFAULT 0, olab49j45 int NULL DEFAULT 0, olab49j46 int NULL DEFAULT 0, olab49j47 int NULL DEFAULT 0, olab49j48 int NULL DEFAULT 0, olab49j49 int NULL DEFAULT 0, olab49j50 int NULL DEFAULT 0)";}
	if ($dbversion==2722){$sql="ALTER TABLE olab49asistencia ADD PRIMARY KEY(olab49id)";}
	if ($dbversion==2723){$sql="ALTER TABLE olab49asistencia ADD UNIQUE INDEX olab49asistencia_id(olab49idcupo)";}
	if ($dbversion==2724){$sql="ALTER TABLE olab49asistencia ADD INDEX olab49asistencia_padre(olab49idcupo)";}
	if ($dbversion==2725){$sql="agregamodulo|2149|21|Control de asistencia|1|2|3|4|5|6|8";}
	if ($dbversion==2726){$sql="ALTER TABLE core09programa ADD core09idtipopractica int NULL DEFAULT 0";}
	if ($dbversion==2727){$sql=$unad70."(2908,2901,'plab01hv','plab01id','plab01emprbolsempleo','El dato esta incluido en hoja de vida', '')";}
	if ($dbversion==2728){$sql=$unad70."(2902,2901,'plab01hv','plab01id','plab01ultprof','El dato esta incluido en hoja de vida', '')";}
	if ($dbversion==2729){$sql=$unad70."(2903,2901,'plab01hv','plab01id','plab01aspsal','El dato esta incluido en hoja de vida', '')";}
	if ($dbversion==2730){$sql=$unad70."(2904,2901,'plab01hv','plab01id','plab01cargo','El dato esta incluido en hoja de vida', '')";}
	if ($dbversion==2731){$sql=$unad70."(2905,2901,'plab01hv','plab01id','plab01industria','El dato esta incluido en hoja de vida', '')";}
	if ($dbversion==2732){$sql=$unad70."(2506,2901,'plab01hv','plab01id','plab01sector','El dato esta incluido en hoja de vida', '')";}
	if ($dbversion==2733){$sql=$unad70."(2907,2901,'plab01hv','plab01id','plab01nivingles','El dato esta incluido en hoja de vida', '')";}
	if ($dbversion==2734){$sql="CREATE TABLE plab01hv (plab01emprbolsempleo int NOT NULL, plab01idtercero int NOT NULL, plab01id int NULL DEFAULT 0, plab01fechareg int NULL DEFAULT 0, plab01telprin varchar(30) NULL, plab01telofic varchar(30) NULL, plab01telmov varchar(30) NULL, plab01correo varchar(50) NULL, plab01ultprof int NULL DEFAULT 0, plab01aspsal int NULL DEFAULT 0, plab01nomemprultexp varchar(250) NULL, plab01cargo int NULL DEFAULT 0, plab01industria int NULL DEFAULT 0, plab01sector int NULL DEFAULT 0, plab01fechainiexp int NULL DEFAULT 0, plab01fechafinexp int NULL DEFAULT 0, plab01nivingles int NULL DEFAULT 0, plab01fechaacthv int NULL DEFAULT 0, plab01numpostula int NULL DEFAULT 0)";}
	if ($dbversion==2735){$sql="ALTER TABLE plab01hv ADD PRIMARY KEY(plab01id)";}
	if ($dbversion==2736){$sql="ALTER TABLE plab01hv ADD UNIQUE INDEX plab01hv_id(plab01emprbolsempleo, plab01idtercero)";}
	if ($dbversion==2737){$sql="agregamodulo|2901|29|Hojas de vida|1|2|3|4|5|6";}
	if ($dbversion==2738){$sql=$u09."(2901, 1, 'Hojas de vida', 'porlabhv.php', 2901, 2901, 'S', '', '')";}
	if ($dbversion==2739){$sql=$unad70."(2209,2902,'plab02prof','plab02id','plab02programa','El dato esta incluido en profesiones', '')";}
	if ($dbversion==2740){$sql="CREATE TABLE plab02prof (plab02consecutivo int NOT NULL, plab02id int NULL DEFAULT 0, plab02nombre varchar(250) NULL, plab02programa int NULL DEFAULT 0)";}
	if ($dbversion==2741){$sql="ALTER TABLE plab02prof ADD PRIMARY KEY(plab02id)";}
	if ($dbversion==2742){$sql="ALTER TABLE plab02prof ADD UNIQUE INDEX plab02prof_id(plab02consecutivo)";}
	if ($dbversion==2743){$sql="agregamodulo|2902|29|Profesiones|1|2|3|4|5|6|8";}
	if ($dbversion==2744){$sql=$u09."(2902, 1, 'Profesiones', 'plabprofesiones.php', 2, 2902, 'S', '', '')";}
	if ($dbversion==2745){$sql="CREATE TABLE plab04cargo (plab04consecutivo int NOT NULL, plab04id int NULL DEFAULT 0, plab04nombre varchar(250) NULL)";}
	if ($dbversion==2746){$sql="ALTER TABLE plab04cargo ADD PRIMARY KEY(plab04id)";}
	if ($dbversion==2747){$sql="ALTER TABLE plab04cargo ADD UNIQUE INDEX plab04cargo_id(plab04consecutivo)";}
	if ($dbversion==2748){$sql="agregamodulo|2904|29|Cargos|1|2|3|4|5|6|8";}
	if ($dbversion==2749){$sql=$u09."(2904, 1, 'Cargos', 'plabcargos.php', 2, 2904, 'S', '', '')";}
	if ($dbversion==2750){$sql="CREATE TABLE plab05industria (plab05consecutivo int NOT NULL, plab05id int NULL DEFAULT 0, plab05nombre varchar(250) NULL)";}
	if ($dbversion==2751){$sql="ALTER TABLE plab05industria ADD PRIMARY KEY(plab05id)";}
	if ($dbversion==2752){$sql="ALTER TABLE plab05industria ADD UNIQUE INDEX plab05industria_id(plab05consecutivo)";}
	if ($dbversion==2753){$sql="agregamodulo|2905|29|Industrias|1|2|3|4|5|6|8";}
	if ($dbversion==2754){$sql=$u09."(2905, 1, 'Industrias', 'plabindustria.php', 2, 2905, 'S', '', '')";}
	if ($dbversion==2755){$sql="CREATE TABLE plab06sector (plab06consecutivo int NOT NULL, plab06id int NULL DEFAULT 0, plab06nombre varchar(250) NULL)";}
	if ($dbversion==2756){$sql="ALTER TABLE plab06sector ADD PRIMARY KEY(plab06id)";}
	if ($dbversion==2757){$sql="ALTER TABLE plab06sector ADD UNIQUE INDEX plab06sector_id(plab06consecutivo)";}
	if ($dbversion==2758){$sql="agregamodulo|2906|29|Sectores|1|2|3|4|5|6|8";}
	if ($dbversion==2759){$sql=$u09."(2906, 1, 'Sectores', 'plabsector.php', 2, 2906, 'S', '', '')";}
	if ($dbversion==2760){$sql="CREATE TABLE plab07nivingles (plab07consecutivo int NOT NULL, plab07id int NULL DEFAULT 0, plab07nombre varchar(50) NULL)";}
	if ($dbversion==2761){$sql="ALTER TABLE plab07nivingles ADD PRIMARY KEY(plab07id)";}
	if ($dbversion==2762){$sql="ALTER TABLE plab07nivingles ADD UNIQUE INDEX plab07nivingles_id(plab07consecutivo)";}
	if ($dbversion==2763){$sql="agregamodulo|2907|29|Niveles de ingles|1|2|3|4|5|6|8";}
	if ($dbversion==2764){$sql=$u09."(2907, 1, 'Niveles de ingles', 'plabnivingles.php', 2, 2907, 'S', '', '')";}
	if ($dbversion==2765){$sql="CREATE TABLE plab08emprbolsempleo (plab08consecutivo int NOT NULL, plab08id int NULL DEFAULT 0, plab08nombre varchar(50) NULL, plab08activo varchar(1) NULL, plab08fechainicontr int NULL DEFAULT 0, plab08fechafincontr int NULL DEFAULT 0)";}
	if ($dbversion==2766){$sql="ALTER TABLE plab08emprbolsempleo ADD PRIMARY KEY(plab08id)";}
	if ($dbversion==2767){$sql="ALTER TABLE plab08emprbolsempleo ADD UNIQUE INDEX plab08emprbolsempleo_id(plab08consecutivo)";}
	if ($dbversion==2768){$sql="agregamodulo|2908|29|Bolsas de empleo|1|2|3|4|5|6|8";}
	if ($dbversion==2769){$sql=$u09."(2908, 1, 'Bolsas de empleo', 'plabemprbolsemple.php', 1, 2908, 'S', '', '')";}
	if ($dbversion==2770){$sql="CREATE TABLE plab03rangsala (plab03idbolsa int NOT NULL, plab03consecutivo int NOT NULL, plab03id int NULL DEFAULT 0, plab03activo varchar(1) NULL, plab03nombre varchar(250) NULL)";}
	if ($dbversion==2771){$sql="ALTER TABLE plab03rangsala ADD PRIMARY KEY(plab03id)";}
	if ($dbversion==2772){$sql="ALTER TABLE plab03rangsala ADD UNIQUE INDEX plab03rangsala_id(plab03idbolsa, plab03consecutivo)";}
	if ($dbversion==2773){$sql="ALTER TABLE plab03rangsala ADD INDEX plab03rangsala_padre(plab03idbolsa)";}
	if ($dbversion==2774){$sql="agregamodulo|2903|29|Bolsa Empleo - Rango salarial|1|2|3|4|5|6|8";}
	if ($dbversion==2775){$sql=$u01."(29, 'SAE', 'Servicios a Egresados', 'S', 'S', 1, 0, 0)";}
	if ($dbversion==2776){$sql=$unad70."(2905,2909,'plab09empresa','plab09id','plab09industria','El dato esta incluido en empresa ofertante', '')";}
	if ($dbversion==2777){$sql=$unad70."(2906,2909,'plab09empresa','plab09id','plab09sector','El dato esta incluido en empresa ofertante', '')";}
	if ($dbversion==2778){$sql="CREATE TABLE plab09empresa (plab01idtercero int NOT NULL, plab09id int NULL DEFAULT 0, plab09industria int NULL DEFAULT 0, plab09sector int NULL DEFAULT 0, plab09contnombre Text NULL, plab09contcorreo Text NULL, plab09numoferpub int NULL DEFAULT 0)";}
	if ($dbversion==2779){$sql="ALTER TABLE plab09empresa ADD PRIMARY KEY(plab09id)";}
	if ($dbversion==2780){$sql="ALTER TABLE plab09empresa ADD UNIQUE INDEX plab09empresa_id(plab01idtercero)";}
	if ($dbversion==2781){$sql="agregamodulo|2909|29|Empresas ofertantes|1|2|3|4|5|6";}
	if ($dbversion==2782){$sql=$u09."(2909, 1, 'Empresa ofertantes', 'plabempresa.php', 1, 2909, 'S', '', '')";}
	if ($dbversion==2783){$sql=$unad70."(2908,2910,'plab10oferta','plab10id','plab10emprbolsempleo','El dato esta incluido en ofertas de empleo', '')";}
	if ($dbversion==2784){$sql=$unad70."(2909,2910,'plab10oferta','plab10id','plab10empresa','El dato esta incluido en ofertas de empleo', '')";}
	if ($dbversion==2785){$sql=$u08."(2901, 'Portal Laboral', 'gm.php?id=2901', 'Portal Laboral', 'Labor Portal', 'Portal do Trabalho')";}
	if ($dbversion==2786){$sql=$unad70."(2901,2914,'plab14aplicaofer','plab14id','plab14hv','El dato esta incluido en aplicacion a oferta', '')";}
	if ($dbversion==2787){$sql=$unad70."(2911,2910,'plab10oferta','plab10id','plab10tipocont','El dato esta incluido en ofertas de empleo', '')";}
	if ($dbversion==2788){$sql=$unad70."(2912,2910,'plab10oferta','plab10id','plab10estaprob','El dato esta incluido en ofertas de empleo', '')";}
	if ($dbversion==2789){$sql=$unad70."(2903,2910,'plab10oferta','plab10id','plab10rangsala','El dato esta incluido en ofertas de empleo', '')";}
	if ($dbversion==2790){$sql=$unad70."(2913,2910,'plab10oferta','plab10id','plab10segmento','El dato esta incluido en ofertas de empleo', '')";}
	if ($dbversion==2791){$sql=$unad70."(2902,2910,'plab10oferta','plab10id','plab10profesion','El dato esta incluido en ofertas de empleo', '')";}
	if ($dbversion==2792){$sql="CREATE TABLE plab10oferta (plab10emprbolsempleo int NOT NULL, plab10consecutivo int NOT NULL, plab10id int NULL DEFAULT 0, plab10refoferta varchar(100) NULL, plab10empresa int NULL DEFAULT 0, plab10titofer varchar(150) NULL, plab10ubicacion Text NULL, plab10ubipais varchar(3) NULL, plab10ubidep varchar(5) NULL, plab10ubiciu varchar(8) NULL, plab10fechapub int NULL DEFAULT 0, plab10tipocont int NULL DEFAULT 0, plab10estaprob int NULL DEFAULT 0, plab10rangsala int NULL DEFAULT 0, plab10segmento int NULL DEFAULT 0, plab10totalapli int NULL DEFAULT 0, plab10numvac int NULL DEFAULT 0, plab10profesion int NULL DEFAULT 0)";}
	if ($dbversion==2793){$sql="ALTER TABLE plab10oferta ADD PRIMARY KEY(plab10id)";}
	if ($dbversion==2794){$sql="ALTER TABLE plab10oferta ADD UNIQUE INDEX plab10oferta_id(plab10emprbolsempleo, plab10consecutivo)";}
	if ($dbversion==2795){$sql="agregamodulo|2910|29|Ofertas de empleo|1|2|3|4|5|6|8";}
	if ($dbversion==2796){$sql=$u09."(2910, 1, 'Ofertas de empleo', 'plaboferta.php', 2901, 2910, 'S', '', '')";}
	if ($dbversion==2798){$sql="CREATE TABLE plab14aplicaofer (plab14oferta int NOT NULL, plab14hv int NOT NULL, plab14id int NULL DEFAULT 0, plab14fechaaplica int NULL DEFAULT 0)";}
	if ($dbversion==2799){$sql="ALTER TABLE plab14aplicaofer ADD PRIMARY KEY(plab14id)";}
	if ($dbversion==2800){$sql="ALTER TABLE plab14aplicaofer ADD UNIQUE INDEX plab14aplicaofer_id(plab14oferta, plab14hv)";}
	}
if (($dbversion>2800)&&($dbversion<2901)){
	if ($dbversion==2801){$sql="ALTER TABLE plab14aplicaofer ADD INDEX plab14aplicaofer_padre(plab14oferta)";}
	if ($dbversion==2802){$sql="agregamodulo|2914|29|Ofertas de empleo - aplicaciones|1|2|3|4|5|6|8";}
	if ($dbversion==2803){$sql="CREATE TABLE plab11tipocont (plab11consecutivo int NOT NULL, plab11id int NULL DEFAULT 0, plab11nombre varchar(50) NULL)";}
	if ($dbversion==2804){$sql="ALTER TABLE plab11tipocont ADD PRIMARY KEY(plab11id)";}
	if ($dbversion==2805){$sql="ALTER TABLE plab11tipocont ADD UNIQUE INDEX plab11tipocont_id(plab11consecutivo)";}
	if ($dbversion==2806){$sql="agregamodulo|2911|29|Tipos de contratos|1|2|3|4|5|6|8";}
	if ($dbversion==2807){$sql=$u09."(2911, 1, 'Tipos de contratos', 'plabtipocont.php', 2, 2911, 'S', '', '')";}
	if ($dbversion==2808){$sql="CREATE TABLE plab12estaprob (plab12consecutivo int NOT NULL, plab12id int NULL DEFAULT 0, plab12nombre varchar(50) NULL)";}
	if ($dbversion==2809){$sql="ALTER TABLE plab12estaprob ADD PRIMARY KEY(plab12id)";}
	if ($dbversion==2810){$sql="ALTER TABLE plab12estaprob ADD UNIQUE INDEX plab12estaprob_id(plab12consecutivo)";}
	if ($dbversion==2811){$sql="agregamodulo|2912|29|Estados de aprobación|1|2|3|4|5|6|8";}
	if ($dbversion==2812){$sql=$u09."(2912, 1, 'Estados de aprobación', 'plabestaprob.php', 2, 2912, 'S', '', '')";}
	if ($dbversion==2813){$sql="CREATE TABLE plab13segmento (plab13consecutivo int NOT NULL, plab13id int NULL DEFAULT 0, plab13nombre varchar(50) NULL)";}
	if ($dbversion==2814){$sql="ALTER TABLE plab13segmento ADD PRIMARY KEY(plab13id)";}
	if ($dbversion==2815){$sql="ALTER TABLE plab13segmento ADD UNIQUE INDEX plab13segmento_id(plab13consecutivo)";}
	if ($dbversion==2816){$sql="agregamodulo|2913|29|Segmentación|1|2|3|4|5|6|8";}
	if ($dbversion==2817){$sql=$u09."(2913, 1, 'Segmentación', 'plabsegmento.php', 2, 2913, 'S', '', '')";}
	}
if (($dbversion>2900)&&($dbversion<3001)){
	if ($dbversion==2899){$sql="INSERT INTO unae16cronaccion (unae16id, unae16accion) VALUES (9999, 'Proceso terminado'), (2218, 'CORE - Totalizar periodos académicos'), (2109, 'OIL - Envío de alertas a estudiantes')";}
	}
	//utf8_encode(
	//if ($dbversion==494){$sql=$u03."(1702, 'Ofertar Curso'), (1703, 'Cancelar Oferta'), (1704, 'Carga Masiva de Oferta')";}
	//if ($dbversion==510){$sql=$u04."(1716, 1711, 'S'), (1716, 1712, 'S'), (1716, 1713, 'S')";}
	//$u22="INSERT INTO unad22combos (unad22idmodulo, unad22consec, unad22codopcion, unad22nombre, unad22orden, unad22activa) VALUES ";
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
			$error++;
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
?>
<br>Base de Datos Actualizada <?php echo $dbversion; ?>;
<?php if($suspende==1){?><br>
<form id="form1" name="form1" method="post" action="">
  El Proceso A&uacute;n No Ha Concluido
<?php
if (false){//$notablas
?>
<input name="notablas" type="hidden" id="notablas" value="1" />
<?php
	}
?>
  <input type="submit" name="Submit" value="Continuar" />
</form>
<?php
if ($error==0){
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