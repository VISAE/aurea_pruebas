<?php
/*
--- © Angel Mauro Avellaneda Barreto - Ideas - 2016 ---
--- Este archivo se usa para controlar la forma como se gestionan los mensajes de error.
--- Se debe usar de esta manera cuando el proveedor de hosting no permite modificar el php.ini
*/
$err_level = error_reporting(E_ALL);
//$err_level = error_reporting(E_ALL ^ E_WARNING);
error_reporting($err_level);
ini_set("display_errors", 1);
?>