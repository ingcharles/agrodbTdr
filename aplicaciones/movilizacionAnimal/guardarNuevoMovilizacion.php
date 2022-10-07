<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacunacionAnimal.php';

$conexion = new Conexion();
$vdr = new ControladorVacunacionAnimal();
$contador = 0;
$opcion = htmlspecialchars ($_POST['opcion'],ENT_NOQUOTES,'UTF-8');

print_r($_POST);

if($opcion==1){// Busqueda de los sitios por especie
	$tipoSitio = htmlspecialchars ($_POST['tipoBusquedaSitio'],ENT_NOQUOTES,'UTF-8');
	$varSitio = htmlspecialchars ($_POST['txtSitioBusqueda'],ENT_NOQUOTES,'UTF-8');

	$sitios = $vdr->listaSitioEspecie($conexion,$tipoSitio, $varSitio);
	$areas = $vdr->listaArea($conexion,$tipoSitio, $varSitio);
	echo '<label>Nombre del sitio: </label>';
	echo '<select id="cmbSitio" name="cmbSitio">';
	echo '<option value="">Seleccione sitio....</option>';
	while ($fila = pg_fetch_assoc($sitios)){
		echo '<option value="'. $fila['id_sitio'].'">'.$fila['identificador_operador'].' - '.$fila['granja'].' - '.$fila['provincia'].' </option>';
	}
	echo '</select>';
}
if($opcion==10)
{
	$idVacunaAnimal =  $_POST['id_vacuna_animal'];
	$disponibleMovilizar = $_POST['disponible_movilizar'];
	$cantidadMovilizar = $_POST['cantidad_movilizar'];
	
	for($i=0; $i<count($idVacunaAnimal); $i++){
		if($cantidadMovilizar[$i]=='')
			$cantidadMovilizar[$i] = 0;
			
		if($cantidadMovilizar[$i]>0){	
			$valorDisponible = (int)$disponibleMovilizar[$i] - (int)$cantidadMovilizar[$i];
			
			$datos = array(
					'id_vacuna_animal' => $idVacunaAnimal[$i],
					'num_movilizacion' => 1,
				    'id_provincia' => htmlspecialchars ($_POST['provincia'],ENT_NOQUOTES,'UTF-8'), 
				    'provincia' => htmlspecialchars ($_POST['nombreProvincia'],ENT_NOQUOTES,'UTF-8'),
				    'id_canton' => htmlspecialchars ($_POST['canton'],ENT_NOQUOTES,'UTF-8'),
				    'canton' => htmlspecialchars ($_POST['nombreCanton'],ENT_NOQUOTES,'UTF-8'),
					'usuario_reponsable' => htmlspecialchars ($_POST['usuario_responsable'],ENT_NOQUOTES,'UTF-8'),
					'cantidad_movilizado' => $cantidadMovilizar[$i],
					'total_movilizado' => $valorDisponible,
					'observacion' => htmlspecialchars ($_POST['observacion'],ENT_NOQUOTES,'UTF-8'),				
					'fecha_movilizacion' => htmlspecialchars ($_POST['fecha_movilizacion'],ENT_NOQUOTES,'UTF-8'),
					'estado' => 'Movilizado'
					);
			
			$dMovilizador = $vdr->guardarTransaccionMovilizacion($conexion, $datos['id_vacuna_animal'], $datos['num_movilizacion'], $datos['id_provincia'], $datos['provincia'], 
					$datos['id_canton'], $datos['canton'], $datos['usuario_reponsable'], $datos['cantidad_movilizado'], $datos['total_movilizado'], 
					$datos['observacion'], $datos['estado'], $datos['fecha_movilizacion']);
			
		}
	}
	$conexion->desconectar();			
}
	
?>
</body>
	<script type="text/javascript">
		//abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),null,true);
	</script>
</html>






