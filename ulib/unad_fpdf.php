<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2014 ---
--- Viernes, 7 de Marzo de 2014
--- Miercoles, 12 de Marzo de 2014 Se hacen los test a reportes de segundo nivel.
--- Martes, 18 de Marzo de 2014 Se hacen ajustan varias opciones en cuanto a resolucion de parametros en el encabezado y resolucion de consultas anidadas.
--- Martes, 25 de Marzo de 2014 Se ajusta los campos tipo multicelda para que no causen un salto de linea cuando el siguiente elemento va a la derecha, lo que se hace es devolverlo el cursor al punto original y luego pintar un cuadro de texto vacio en el area que ocupa el campo multicelda..
--- Libreria para generación de reportes version 3
*/
function unad_fpdf_crear($rpt_id, $PARAM=NULL, $objdb){
	// -- Orden de ejecución: La idea es que procuremos no enredarnos con los sql, porque vamos a tener varios bloques,
	// -- de manera tal que la idea es tercerizar todo a la clase clsPDF, lo unico que tenemos que precargar va a ser 
	// -- la información que el reporte no puede cargar definitivamente (la de configuracion de pagina).
	class clsPDF extends FPDF{
		//Bloque de variables, disponible para todo momento.
		// -- La idea es que cada los bloque se mantengan asi: 
		// -- VARS['t12']=la fila de la tabla 12, 
		// -- VARS['rpt']=variables del reporte, 
		// -- DATOS['n0']=registro 0 {Entorno} lo cual permite ser tomado como variables
		// -- DATOS['n1'][numsec]=registro 1 {Nivel 1}{Secuencia} lo cual permite ser tomado como variables
		// -- DATOS['n2'][numsec]=registro 2 {Nivel 2}{Secuencia} lo cual permite ser tomado como variables
		// -- $this->DATOS['n'.$nivel][$secuencia][$iRegistro]
		var $VARS=array();
		var $objdb=NULL;
		var $objdbDATOS=NULL;
		var $sModeloDatos='M';
		var $ITEM=array();
		var $CONSULTA=array();
		var $DATOS=array();
		var $serror='';
		var $smes=array('','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
		var $xPrevia=0;
		var $yPrevia=0;
		var $nada=0;
		function AddError($sdetalle){
			if ($this->serror!=''){$this->serror=$this->serror.'<br>';}
			$this->serror=$this->serror.$sdetalle;
			}
		// -- Funciones para resolver SQL
		// -- Resuelve los parametros que seran accedidos mediante el uso de @
		function ResolverParametro($nombre){
			$res='';
			if (isset($this->VARS['rpt'][$nombre])!=0){$res=$this->VARS['rpt'][$nombre];}
			if (($res=='')&&(isset($this->DATOS['n0'])!=0)){
				if (is_array($this->DATOS['n0'])){
					if (isset($this->DATOS['n0'][$nombre])!=0){$res=$this->DATOS['n0'][$nombre];}
					}
				}
			if ($res==''){
				switch($nombre){
					case 'idusuario':$res=$_SESSION['unad_id_tercero'];break;
					case 'fechaimpresion':$res=date("j")." de ".$this->smes[date("n")]." de ".date("Y");break;
					case 'ent_tipodoc':
					case 'ent_doc':
					case 'ent_nombre':
					case 'ent_direccion':
					case 'ent_telefono':
					case 'ent_email':
					case 'ent_regimen':
					case 'ent_tipo':
					case 'ent_naturaleza':
						break;
					}
				}
			if ($res==''){$this->AddError('No se encuentra la variable '.$nombre);}
			return $res;
			}
		function ResolverValorParametro($nombre, $nivel, $secuencia, $padres){
			$res='';
			if ($nivel>0){
				//@@@ falta
				// -- RECORRER LAS CONSULTAS INMEDIATAMENTE SUPERIORES EN BUSCA DEL DATO
				for ($z=$nivel-1;$z>=0;$z--){
					$datos=NULL;
					if ($z==0){
						$datos=$this->DATOS['n0'];
						}else{
						if (isset($this->DATOS['n'.$z]['s'.$secuencia]['r'.$padres[$z]])!=0){
							$datos=$this->DATOS['n'.$z]['s'.$secuencia]['r'.$padres[$z]];
							}
						}
					if ($datos!=NULL){
						if (isset($datos[$nombre])!=0){
							$res=$datos[$nombre];
							break;
							}
						}
					}
				if ($res==''){$this->AddError('No se encuentra la variable de datos '.$nombre);}
				}else{
				$this->AddError('No se permiten variables de datos en la consulta de entorno '.$nombre);
				}
			return $res;
			}
		function ResolverSQL($origen, $nivel, $secuencia, $padres){
			list($s1,$s2,$s3)=cadena_partir($origen,'|','|');
			$res=$s1;
			$s1='';
			while ($s2!=''){
				$s2=trim($s2);
				$marca=substr(trim($s2),0,1);
				$variable=substr(trim($s2),1);
				switch ($marca){
					case '&':
					$res=$res.$this->ResolverValorParametro($variable, $nivel, $secuencia, $padres);
					break;
					default:
					$res=$res.$this->ResolverParametro($variable);
					}
				list($s1,$s2,$s3)=cadena_partir($s3,'|','|');
				$res=$res.$s1;
				}
			return $res;
			}
		function ResolverValor($base, $tabla, $fila){
			//if ($res==''){$this->AddError('No se encuentra la variable de datos '.$nombre);}
			list($s1,$s2,$s3)=cadena_partir($base,'|','|');
			$res=$s1;
			$s1='';
			while ($s2!=''){
				$s2=trim($s2);
				$marca=substr(trim($s2),0,1);
				$variable=substr(trim($s2),1);
				switch ($marca){
					case '&':
					switch ($variable){
						case 'nl':
						$res=$res.$fila;
						break;
						default:
						if (isset($tabla[$variable])!=0){
							$res=$res.$tabla[$variable];
							}else{
							$res=$res.$variable;
							}
						}
					break;
					default:
					$res=$res.$this->ResolverParametro($variable);
					}
				list($s1,$s2,$s3)=cadena_partir($s3,'|','|');
				$res=$res.$s1;
				}
			return $res;
			}
		// Intérprete de HTML
		function WriteHTML($html){
			$html = str_replace("\n",' ',$html);
			$a = preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
			foreach($a as $i=>$e){
				if($i%2==0){
					// Text
					if($this->HREF){
						$this->PutLink($this->HREF,$e);
						}else{
						$this->Write(5,$e);
						}
					}else{
					// Etiqueta
					if($e[0]=='/'){
						$this->CloseTag(strtoupper(substr($e,1)));
						}else{
						// Extraer atributos
						$a2 = explode(' ',$e);
						$tag = strtoupper(array_shift($a2));
						$attr = array();
						foreach($a2 as $v){
						if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
						$attr[strtoupper($a3[1])] = $a3[2];
						}
					$this->OpenTag($tag,$attr);
					}
				}
			}
		}
		function OpenTag($tag, $attr){
		// Etiqueta de apertura
		if($tag=='B' || $tag=='I' || $tag=='U')
		$this->SetStyle($tag,true);
		if($tag=='A')
		$this->HREF = $attr['HREF'];
		if($tag=='BR')
		$this->Ln(5);
		}
		function CloseTag($tag){
		// Etiqueta de cierre
		if($tag=='B' || $tag=='I' || $tag=='U')
		$this->SetStyle($tag,false);
		if($tag=='A')
		$this->HREF = '';
		}
		function SetStyle($tag, $enable){
		// Modificar estilo y escoger la fuente correspondiente
		$this->$tag += ($enable ? 1 : -1);
		$style = '';
		foreach(array('B', 'I', 'U') as $s){
			if($this->$s>0)
			$style .= $s;
			}
		$this->SetFont('',$style);
		}
		function PutLink($URL, $txt){
		// Escribir un hiper-enlace
		$this->SetTextColor(0,0,255);
		$this->SetStyle('U',true);
		$this->Write(5,$txt,$URL);
		$this->SetStyle('U',false);
		$this->SetTextColor(0);
		}
		
		function PintarBloque($nivel, $secuencia ,$bloque, $padres){
			// -- Lo niveles son 0 {entorno - (No aplica la secuencia.)}, entorno, bloques 1 {Encabezado} y 2 {Pie de pagina} 
			// -- Numeros mayores a 0 son la consulta, pero se ejecutan en secuencia, (primero la secuencia 1 luego la 2 y sucesivamente)
			// -- Primer paso, cargar los items que seran pintados. Se mantienen en memoria para no tener que estarlos consultando...
			// -- Una vez definidos los ITEM cargar las consultas, y resolverlas.
			// CARGAMOS LOS DATOS
			//SI EL BLOQUE TIENE ALGO PARA MOSTRAR SEGUIMOS.
			if (($bloque==1)||($bloque==2)){
				$nivel=0;
				$secuencia=0;
				}
			// -- Primer paso, cargar los items que seran pintados. Se mantienen en memoria para no tener que estarlos consultando...
			if (isset($this->ITEM[$nivel][$secuencia][$bloque]['cant'])==0){
				$sqladd='';
				$iTotalItems=0;
				if ($nivel>0){
					$sqladd=' AND unad13nivel='.$nivel.' AND unad13secuencia='.$secuencia;
					}
				$sql='SELECT unad13tipo, unad13izquierda, unad13arriba, unad13ancho, unad13alto, unad13valor, unad13bordes, unad13linea, unad13alineacion, unad13relleno, unad13orden, unad13fuente, unad13tipofuente, unad13tamfuente, unad13colorfuente, unad13colorlinea, unad13colorfondo, unad13formato 
FROM unad13reporteitem 
WHERE unad13idreporte='.$this->VARS['t12']['unad12id'].' AND unad13bloque='.$bloque.$sqladd.' 
ORDER BY unad13orden, unad13consec';
				$titems=$this->objdb->ejecutasql($sql);
				if ($titems==false){echo $sql;}
				while ($fitem=$this->objdb->sf($titems)){
					$iTotalItems++;
					$this->ITEM[$nivel][$secuencia][$bloque]['item'.$iTotalItems]=$fitem;
					}
				$this->ITEM[$nivel][$secuencia][$bloque]['cant']=$iTotalItems;
				}
			// -- Una vez definidos los ITEM cargar las consultas, y resolverlas.
			if (isset($this->CONSULTA[$nivel][$secuencia])==0){
				$sqladd='';
				if ($nivel>0){
					$sqladd=' AND unad15secuencia='.$secuencia;
					}
				$sql='SELECT unad15numcampos, unad15etiquetas, unad15anchos, unad15campos, unad15tablas, unad15condicion, unad15agrupar, unad15orden, unad15teniendo, unad15entabla, unad15conlineas, unad15conetiquetas, unad15forzarnuevapag 
FROM unad15reporteconsulta 
WHERE unad15idreporte='.$this->VARS['t12']['unad12id'].' AND unad15idnivel='.$nivel.$sqladd;
				$this->CONSULTA[$nivel][$secuencia]='';
				$tconsulta=$this->objdb->ejecutasql($sql);
				if ($tconsulta==false){echo $sql;}
				if ($this->objdb->nf($tconsulta)>0){
					$fconsulta=$this->objdb->sf($tconsulta);
					$this->CONSULTA[$nivel][$secuencia]=$fconsulta;
					if ($fconsulta['unad15entabla']=='S'){
						if ($nivel>0){
							$this->ITEM[$nivel][$secuencia][0]['cant']=$fconsulta['unad15numcampos'];
							}
						}
					}
				}
			//CARGAMOS LOS DATOS
			if (is_array($this->CONSULTA[$nivel][$secuencia])){
				$D=$this->CONSULTA[$nivel][$secuencia];
					$sql='SELECT '.trim($D['unad15campos']).' FROM '.trim($D['unad15tablas']);
					if ($D['unad15condicion']!=''){$sql=$sql.' WHERE '.$D['unad15condicion'];}
					if ($D['unad15agrupar']!=''){$sql=$sql.' GROUP BY '.$D['unad15agrupar'];}
					if ($D['unad15teniendo']!=''){$sql=$sql.' HAVING '.$D['unad15teniendo'];}
					if ($D['unad15orden']!=''){$sql=$sql.' ORDER BY '.$D['unad15orden'];}
				$sql=$this->ResolverSQL($sql, $nivel, $secuencia, $padres);
				$tdatos=$this->objdbDATOS->ejecutasql($sql);
				if ($tdatos==false){
					$this->AddError('No fue posible resolver {Secuencia '.$secuencia.', Nivel '.$nivel.'}'.$sql);
					}
				$iRegistro=0;
				while ($fdatos=$this->objdbDATOS->sf($tdatos)){
					$iRegistro++;
					if ($nivel==0){
						$this->DATOS['n0']=$fdatos;
						break;
						}else{
						$this->DATOS['n'.$nivel]['s'.$secuencia]['r'.$iRegistro]=$fdatos;
						}
					}
				if ($nivel!=0){
					$this->DATOS['n'.$nivel]['s'.$secuencia]['lineas']=$iRegistro;
					}
				}
			//SI EL BLOQUE TIENE ALGO PARA MOSTRAR SEGUIMOS.
			if ($this->ITEM[$nivel][$secuencia][$bloque]['cant']>0){
				$iVeces=1;
				if ($bloque==0){
					$iVeces=$this->DATOS['n'.$nivel]['s'.$secuencia]['lineas'];
					}
				$final=$this->ITEM[$nivel][$secuencia][$bloque]['cant'];
				$fconsulta=$this->CONSULTA[$nivel][$secuencia];
				//SI LOS DATOS ESTAN EN UNA TABLA INICIAR LOS PARAMETROS DE LA TABLA
				if (($nivel!=0)&&($bloque==0)&&($fconsulta['unad15entabla']=='S')){
					$iBordeTabla=1;
					if ($fconsulta['unad15conlineas']!='S'){$iBordeTabla=0;}
					$sEtiquetas=explode(",",$fconsulta['unad15etiquetas']);
					$sAnchos=explode(",",$fconsulta['unad15anchos']);
					//Iniciar las variables para pintar la tabla.
					for ($i=1;$i<=$final;$i++){
						$icab[$i]='';
						$ian[$i]=40;
						if (isset($sEtiquetas[$i-1])!=0){$icab[$i]=$sEtiquetas[$i-1];}
						if (isset($sAnchos[$i-1])!=0){
							$ian[$i]=(int)$sAnchos[$i-1];
							if ($ian[$i]==0){$ian[$i]=40;}
							}
						}
					}
				//PINTAR LOS REGISTROS
				$iBaseTabla=$final;
				for ($k=1;$k<=$iVeces;$k++){
					$bSaltoLinea=true;
					$fdato=NULL;
					if ($bloque==0){
						if ($k==1){
							//Si esta en una tabla pintar los encabezados...
							if (($nivel!=0)&&($fconsulta['unad15entabla']=='S')){
								if ($fconsulta['unad15conetiquetas']=='S'){
									for ($i=1;$i<=$final;$i++){
										$this->Cell($ian[$i], 5, $icab[$i], $iBordeTabla);
										}
									$this->Ln();
									}
								}
							}
						if ($nivel==0){
							$fdato=$this->DATOS['n0'];
							}else{
							$fdato=$this->DATOS['n'.$nivel]['s'.$secuencia]['r'.$k];
							}
						if (($nivel!=0)&&($fconsulta['unad15entabla']=='S')){
							//PINTAMOS LA LINEA DE LA TABLA
							for ($i=1;$i<=$iBaseTabla;$i++){
								$sindice=$i-1;
								//Si el origen de datos es ODBC no permite acceder medienta indice, toca mediante nombre de campo.
								switch ($this->sModeloDatos){
									case 'O':
									$sindice=trim($icab[$i]);
									}
								$iAncho=20;
								if (isset($ian[$i])!=0){$iAncho=$ian[$i];}
								$sdato='';
								if (isset($fdato[$sindice])!=0){$sdato=$fdato[$sindice];}
								$this->Cell($iAncho, 5, utf8_decode($sdato), $iBordeTabla);
								}
							//Si es en tabla, como ya estan pintados dejamos item final =0;
							$final=0;
							}
						}else{
						//A los demas bloques les enviamos el entorno
						if (isset($this->DATOS['n0'])!=0){$fdato=$this->DATOS['n0'];}
						}
					for ($y=1;$y<=$final;$y++){
						$bSaltoLinea=false;
						$fitem=$this->ITEM[$nivel][$secuencia][$bloque]['item'.$y];
						switch ($fitem['unad13tipo']){
							case 0://Caja de texto basica
							case 1://multitexto
							case 3:// imagen .. Es posible que la ruta se resuelva en un campo.
							case 5://HTML
							$svalor=$this->ResolverValor($fitem['unad13valor'], $fdato, $k);
							//Formatear el item
							switch ($fitem['unad13formato']){
								case 1://numero
								$svalor=floor($svalor);
								break;
								case 2://moneda
								$svalor=formato_moneda($svalor,2);
								}
							}
						// -- Pintar el item
						switch ($fitem['unad13tipo']){
							case 0://Caja de texto basica
							case 1://Multicelda
							case 5://HTML
							if (($fitem['unad13izquierda']!=0)or($fitem['unad13arriba']!=0)){
								$this->SetY($fitem['unad13arriba']);
								$this->Cell($fitem['unad13izquierda']);
								}
							if ($fitem['unad13fuente']!=''){
								$this->SetFont($fitem['unad13fuente'],$fitem['unad13tipofuente'],$fitem['unad13tamfuente']);
								}else{
								$this->SetFont($this->VARS['t12']['unad12fuente'], '', $this->VARS['t12']['unad12tamfuente']);
								}
							$color=resolvercolor($fitem['unad13colorfuente']);
							$this->SetTextColor($color[0],$color[1],$color[2]);
							if ($fitem['unad13bordes']=='S'){
								$color=resolvercolor($fitem['unad13colorlinea']);
								$this->SetDrawColor($color[0],$color[1],$color[2]);
								}
							if ($fitem['unad13relleno']=='S'){
								$color=resolvercolor($fitem['unad13colorfondo']);
								$this->SetFillColor($color[0],$color[1],$color[2]);
								}
							$ibordes=0;
							if ($fitem['unad13bordes']=='S'){$ibordes=1;}
							switch ($fitem['unad13tipo']){
								case 0://Texto
								$irelleno=0;
								if ($fitem['unad13relleno']=='S'){$irelleno=1;}
								$this->Cell($fitem['unad13ancho'], $fitem['unad13alto'], utf8_decode($svalor), $ibordes, $fitem['unad13linea'], $fitem['unad13alineacion'], $irelleno);
								$bSaltoLinea=true;
								break;
								case 1://Multicelda
								$salinea="J";
								if ($fitem['unad13alineacion']!="L"){$salinea=$fitem['unad13alineacion'];}
								//OJO SI NO ES EL ULTIMO DEVOLVER EL CURSOR
								if ($y!=$final){
									$this->xPrevia=$this->GetX();
									$this->yPrevia=$this->GetY();
									}
								$this->MultiCell($fitem['unad13ancho'],$fitem['unad13alto'],utf8_decode($svalor),$ibordes,$salinea);
								//OJO SI NO ES EL ULTIMO DEVOLVER EL CURSOR
								if ($y!=$final){
									//solo si va a la derecha
									if ($fitem['unad13orden']==0){
										$yActual=$this->GetY();
										$this->SetX($this->xPrevia);
										$this->SetY($this->yPrevia);
										$this->Cell($fitem['unad13ancho'], $yActual-$this->yPrevia, '',0);
										$bSaltoLinea=true;
										}
									}
								break;
								case 5://HTML
								$this->WriteHTML(utf8_decode($svalor));
								}
							break;
							case 2:// Salto de pagina
							$this->AddPage();
							break;
							case 3:// Imagen.
							$img_izq=NULL;
							$img_sup=NULL;
							if ($fitem['unad13izquierda']>=0){$img_izq=$fitem['unad13izquierda'];}
							if ($fitem['unad13arriba']>=0){$img_sup=$fitem['unad13arriba'];}
							$sRuta=$svalor;
							if (is_file($sRuta)){
								$this->Image($sRuta, $img_izq, $img_sup, $fitem['unad13ancho']);
								$bSaltoLinea=true;
								}else{
								$iAncho=$fitem['unad13ancho'];
								if ($iAncho<20){$iAncho=20;}
								$this->Cell($iAncho, 5, $sRuta, 1);
								}
							break;
							case 4:// Salto de linea
							$this->Ln();
							break;
							}
						// Fin de pintar cada uno de los item de un registro.
						}
					if ($bSaltoLinea){
						$this->Ln();
						}
					// SI EL BLOQUE TIENE BLOQUES HIJOS, LO INVOCAMOS.
					if ($bloque==0){
						$NuevoPadre=$padres;
						$NuevoPadre[$nivel]=$k;
						//pintar los bloques HIJOS
						$NuevoNivel=$nivel+1;
						$sql='SELECT unad15idnivel FROM unad15reporteconsulta WHERE unad15idreporte='.$this->VARS['t12']['unad12id'].' AND unad15idnivel='.$NuevoNivel.' AND unad15secuencia='.$secuencia.' ORDER BY unad15idnivel';
						//echo $sql.'<br>';
						$tbloques=$this->objdb->ejecutasql($sql);
						if ($this->objdb->nf($tbloques)>0){
							$this->nada++;
							//echo $this->nada.' '.$nivel.' '.$secuencia.'<br>';
							//$this->sentradas=$this->sentradas.' '.$NuevoNivel.' '.$secuencia.' '.$k.' '.$sql.'<br>';
							$this->PintarBloque($NuevoNivel, $secuencia,3, $NuevoPadre);
							$this->PintarBloque($NuevoNivel, $secuencia,0, $NuevoPadre);
							$this->PintarBloque($NuevoNivel, $secuencia,4, $NuevoPadre);
							}
						}
					//Fin de pintado de 1 registro.
					}
				// Fin de si hay items para pintar.
				}
			//FIN DE PINTAR EL BLOQUE
			}
		//Cabecera de página
		function Header(){
			$this->SetFont($this->VARS['t12']['unad12fuente'], '', $this->VARS['t12']['unad12tamfuente']);
			$this->SetY($this->VARS['t12']['unad12bsup']);
			$this->PintarBloque(0,0,1, NULL);
			$this->SetY($this->VARS['t12']['unad12altoencab']);
			}
		//Pie de página
		function Footer(){
			$this->SetFont($this->VARS['t12']['unad12fuente'], '', $this->VARS['t12']['unad12tamfuente']);
			$this->SetY($this->VARS['t12']['unad12altopie']*(-1));
			$this->PintarBloque(0,0,2, NULL);
			if ($this->VARS['t12']['unad12vernumpag']=='S'){
				$this->SetY(-10);
				$this->SetFont('Arial','I',8);
				$this->Cell(0,10,'Página '.$this->PageNo().' de {nb}',0,0,'R');
				}
			if ($this->VARS['t12']['unad12verfirma']=='S'){
				$this->SetY(-3);
				$this->SetFont('Arial','',5);
				$this->Cell(0,3,$this->VARS['firma'],0,0,'R');
				}
			}
		function ArmarPDF(){
			$padres=array();
			$this->SetFont($this->VARS['t12']['unad12fuente'], '', $this->VARS['t12']['unad12tamfuente']);
			$this->SetTextColor(0,0,0);
			$this->SetFillColor(0,0,0);
			$this->SetDrawColor(0,0,0);
			//pintar los bloques HIJOS
			$sql='SELECT unad15secuencia FROM unad15reporteconsulta WHERE unad15idreporte='.$this->VARS['t12']['unad12id'].' AND unad15idnivel=1 ORDER BY unad15secuencia';
			$tbloques=$this->objdb->ejecutasql($sql);
			while ($fbloque=$this->objdb->sf($tbloques)){
				$idSecuencia=$fbloque['unad15secuencia'];
				$this->PintarBloque(1, $idSecuencia, 3, $padres);
				$this->PintarBloque(1, $idSecuencia, 0, $padres);
				$this->PintarBloque(1, $idSecuencia, 4, $padres);
				}
			//fin de armar el pdf
			}
		}
	$objpdf=NULL;
	$serror='';
	if (isset($_SESSION['unad_id_tercero'])==0){
		$serror='Origen de datos errado';
		}else{
		if ($_SESSION['unad_id_tercero']==0){
			$serror='Origen de datos errado.';
			}else{
			//$_SESSION['u_ultimominuto']=iminutoavance();
			}
		}
	if ($objdb==NULL){
		$serror='No se ha definido un origen de datos';
		}
	if ($serror==''){
		//cargamos la info de configuracion de pagina
		$sql='SELECT * FROM unad12reportes WHERE unad12id='.$rpt_id;
		$treporte=$objdb->ejecutasql($sql);
		if ($objdb->nf($treporte)>0){
			$filarpt=$objdb->sf($treporte);
			}else{
			$serror='No se ha encontrado el reporte Ref:'.$rpt_id;
			}
		//fin de cargar la info de pagina.
		}
	// -- Definimos el Origen de datos
	if ($serror==''){
		if ($filarpt['unad12dborigen']!=0){
			$sql='SELECT unad27server, unad27db, unad27usuario, unad27pwd, unad27modelo FROM unad27dbalterna WHERE unad27id='.$filarpt['unad12dborigen'];
			$tdatos=$objdb->ejecutasql($sql);
			if ($objdb->numfilas($tdatos)!=0){
				$fdatos=$objdb->siguientefila($tdatos);
				switch ($fdatos['unad27modelo']){
					case 'O':
						$this->sModeloDatos='O';
					case 'M':
					$objdbDATOS=new clsdbad_v2($fdatos['unad27server'], $fdatos['unad27usuario'], $fdatos['unad27pwd'], $fdatos['unad27db'], $fdatos['unad27modelo']);
					break;
					default:
						$serror='No se reconoce el modelo de datos {'.$fdatos['unad27modelo'].'}';
					}
				}else{
				$serror='El reporte usa un origen de datos desconocido {Ref '.$rpt_id.' - Origen '.$filarpt['unad12dborigen'].'}';
				}
			}
		}
	// -- Empezamos la generacion del reporte
	if ($serror==''){
		$sfirma='http://www.unad.edu.co';
		$sql='SELECT unad00valor FROM unad00config WHERE unad00codigo="rptfirma"';
		$tabla=$objdb->ejecutasql($sql);
		if ($objdb->nf($tabla)>0){
			$fila=$objdb->sf($tabla);
			if (trim($fila['unad00valor'])!=''){
				$sfirma=trim($fila['unad00valor']);
				}
			}
		switch ($filarpt['unad12tampagina']){
			case 'Letter':
			case 'Legal':
			$objpdf=new clsPDF($filarpt['unad12orientacion'],'mm',$filarpt['unad12tampagina']);
			break;
			default:
			eval('$tam='.$filarpt['unad12tampagina'].';');
			$objpdf=new clsPDF($filarpt['unad12orientacion'],'mm',$tam);
			}
		$objpdf->objdb=$objdb;
		if ($filarpt['unad12dborigen']!=0){
			$objpdf->objdbDATOS=$objdbDATOS;
			}else{
			$objpdf->objdbDATOS=$objdb;
			}
		$objpdf->VARS['firma']=$sfirma;
		$objpdf->VARS['t12']=$filarpt;
		$objpdf->VARS['rpt']=$PARAM;
		$objpdf->AliasNbPages();
		$objpdf->SetTopMargin($filarpt['unad12bsup']);
		$objpdf->SetLeftMargin($filarpt['unad12bizq']);
		$objpdf->SetRightMargin($filarpt['unad12bder']);
		$objpdf->SetFont($filarpt['unad12fuente'],'',$filarpt['unad12tamfuente']);
		$objpdf->AddPage();
		$objpdf->ArmarPDF();
		$serror=$objpdf->serror;
		}
	return array($objpdf,$serror);
	}
?>