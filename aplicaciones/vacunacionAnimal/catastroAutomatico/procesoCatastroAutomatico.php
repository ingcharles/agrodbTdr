<?php
// Realiza el catastro de las especie de animales
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacunacionAnimal.php';

$conexion = new Conexion();
$vdr = new ControladorVacunacionAnimal();

print_r($_POST);
//$opcion = htmlspecialchars ($_POST['opcion'],ENT_NOQUOTES,'UTF-8');
/*
 16619;'Lechones';1-30 días
16620;'Levante';31-70 días
16621;'Engorde';71-150 días
16622;'Reemplazo';151-210 días
16623;'Verracos';221-3800 días
16624;'Madres';221-3800 días
*/
$edadCatastro = $vdr->procesoCatastroAutomatico($conexion);
// Actualización de edades
$UPedadCatastro = $vdr->actualizarCatastroAutomatico($conexion);


// Actualización de categoria de edades
while ($fila = pg_fetch_assoc($edadCatastro)){	
	//listaProductosAnimales
	//Cambio de categoria--lechon no se toma en cuenta	
	$edad_producto = $fila['edad_producto'];
	if($fila['producto']=='Levante'){
		if($edad_producto >= $fila['rango_edad_desde'] && $edad_producto <= $fila['rango_edad_hasta']){
			//actualiza categoria
			
		}
	}
	if($fila['producto']=='Engorde'){
	
	}
	if($fila['producto']=='Reemplazo'){
	
	}
	if($fila['producto']=='Verracos'){
	
	}
	if($fila['producto']=='Madres'){
	
	}	
}
$conexion->desconectar();
?>
