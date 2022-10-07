<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>
<?php	
//ini_set( 'display_errors', '1' );
//ini_set("error_reporting",E_ALL);  
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorComplementos.php';
	
	define('IN_MSG','<br/> >>> ');
//----------------------------------------------------------------------------------------------
	//   generar claves 
	
	echo IN_MSG.'-------Encriptar - Desencriptar claves----<br><br>';
	
	$cc = new ControladorComplementos();
	
	if(empty($_POST['cedula']) ){
	echo '<form id="encriptar" action="encriptar.php" method="post" autocomplete="off">';
	echo 'usuario: <input type="text" id="cedula" name="cedula" required>';
	echo '<br>clave:   <input type="text" id="clave" name="clave">';	
	echo IN_MSG.'<br>';
	echo 'Clave encriptada: <input type="text" id="clavecifrada" name ="clavecifrada" size="60"><br>';
	echo '<br><br>';	
	echo '<input type="submit">';
	
	
	echo '</form>';
	}else {
		echo '<form id="encriptar" action="encriptar.php" method="post">';
	
		echo '********Parametros Ingresados******';
		echo IN_MSG.'<br>';
		$id=rtrim($_POST['cedula']);
		//$id=$_POST['cedula'];
		$scr = crc32($id);
		$key = hash('sha256', $scr);
		echo 'cedula: '.$id.'<br>';
		   
		if(!empty($_POST['clave'])){
		    $valor=trim($_POST['clave']);
		    echo 'clave: '.$valor;
		    echo IN_MSG.'<br>';
		    echo '</br>********encriptacion de la clave******<br>';
		    $contra = $cc->encriptarClave($valor,$key);
		    echo '!!...no copiar el espacio al final..!!<br>';
		    echo IN_MSG.'<br>';
		    echo rtrim($contra);
		    echo IN_MSG.'<br>';
		}else echo IN_MSG.'Sin clave para encriptar.<br>';
		
		if(!empty($_POST['clavecifrada'])){
		    echo '<br><br></br>********descencriptar la clave******</br>';
		    echo IN_MSG.'<br>';
		    $quitara=$_POST['clavecifrada'];
		    echo $quitara;
		    echo IN_MSG.'<br>';
		    $result=$cc->desencriptarClave($quitara, $key);
		    echo $result;
		}

		echo '<br><br>';
		echo '<input type="submit" value="Refrescar">';
		echo '</form>';
	}
	
//-----------------------------------------------------------------------------------------------

?>
</body>
</html>