<?php
session_start();
if (isset($_SESSION['unad_id_tercero'])==0){$_SESSION['unad_id_tercero']=0;}
if ($_SESSION['unad_id_tercero']==0){
	//Ver si se debe iniciar la sesion..
	require './app.php';
	$pageURL='../index.php?app='.$APP->idsistema;
	header('Location:'.$pageURL);
	}
?>