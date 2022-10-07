<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';




$conexion = new Conexion();
$cr = new ControladorRegistroOperador();

$opcion = htmlspecialchars ($_POST['opcion'],ENT_NOQUOTES,'UTF-8');
$codigoProveedor = htmlspecialchars ($_POST['codproveedor'],ENT_NOQUOTES,'UTF-8');
$comboSitio =  htmlspecialchars ($_POST['sitio'],ENT_NOQUOTES,'UTF-8');
$comboAreaProveedor = htmlspecialchars ($_POST['area'],ENT_NOQUOTES,'UTF-8');
$comboProducto = htmlspecialchars ($_POST['producto'],ENT_NOQUOTES,'UTF-8');
$formulario =  htmlspecialchars ($_POST['formulario'],ENT_NOQUOTES,'UTF-8');
$identificadorOperador =  htmlspecialchars ($_POST['identificador'],ENT_NOQUOTES,'UTF-8');
$comboAreaOperador = htmlspecialchars ($_POST['areaOperador'],ENT_NOQUOTES,'UTF-8');


switch ($opcion){
	case 'sitio':
		
		$sitios = $cr->listarSitios($conexion, $codigoProveedor);
			
			echo '
			<label>Sitio</label>
				<select id="sitio" name="sitio" style="width:85%;">
				<option value="">Seleccione....</option>';
			while ($fila = pg_fetch_assoc($sitios)){
				echo '<option value="'.$fila['id_sitio'].'">'.$fila['nombre_lugar'].'</option>';
			}
			
			echo 	'	</select>';
		break;
		
		case 'area':
		
			$areas = $cr->listarAreaOperador($conexion, $comboSitio);
				
			echo '
			<label>Area</label>
				<select id="area" name="area" style="width:85%;">
				<option value="">Seleccione....</option>';
			while ($fila = pg_fetch_assoc($areas)){
				echo '<option value="'.$fila['id_area'].'">'.$fila['nombre_area'].'</option>';
			}
				
			echo 	'	</select>';
		break;
		
		case 'producto':
		
			$productos = $cr->listarProductoArea($conexion, $comboAreaProveedor);
		
			echo '
			<label>Producto</label>
				<select id="producto" name="producto" style="width:88%;">
				<option value="">Seleccione....</option>';
			while ($fila = pg_fetch_assoc($productos)){
				echo '<option value="'.$fila['id_producto'].'">'.$fila['nombre_comun'].'</option>';
			}
		
			echo 	'	</select>';
			break;
			
		case 'areaOperador':
			
				$areaOperador = $cr->listarAreasOperadorXProducto($conexion, $identificadorOperador, $comboProducto, 'registrado');
			
				echo '
				<label>Área operador</label>
					<select id="areaOperador" name="areaOperador" style="width:83%;">
					<option value="">Seleccione....</option>';
					while ($fila = pg_fetch_assoc($areaOperador)){
						echo '<option value="'.$fila['id_area'].'">'.$fila['nombre_lugar'].' - '.$fila['nombre_area'].'</option>';
					}
			
				echo 	'	</select>';
			break;
			
		case 'operacionProveedor':
					
				$operacionProveedor = $cr->listarOperacionXIdentificadorAreaProducto($conexion, $codigoProveedor, $comboAreaProveedor, $comboProducto, 'registrado');
					
				echo '
				<label>Operación</label>
					<select id="operacionProveedor" name="operacionProveedor" style="width:87%;">
					<option value="">Seleccione....</option>';
				while ($fila = pg_fetch_assoc($operacionProveedor)){
					echo '<option value="'.$fila['id_tipo_operacion'].'">'.$fila['nombre'].'</option>';
				}
					
				echo 	'	</select>';
			break;
			
		case 'operacionOperador':
					
				$operacionOperador = $cr->listarOperacionXIdentificadorAreaProducto($conexion, $identificadorOperador, $comboAreaOperador, $comboProducto, 'registrado');
					
				echo '
				<label>Operación</label>
					<select id="operacionOperador" name="operacionOperador" style="width:87%;">
					<option value="">Seleccione....</option>';
				while ($fila = pg_fetch_assoc($operacionOperador)){
					echo '<option value="'.$fila['id_tipo_operacion'].'">'.$fila['nombre'].'</option>';
				}
					
				echo 	'	</select>';
				break;
			
	default:
		echo 'Tipo desconocido';
}


?>

<script type="text/javascript">

var formulario= <?php echo json_encode($formulario); ?>;

	if(formulario == 'datosRegistro'){

		$("#sitio").change(function(event){
	 		$("#datosRegistro").attr('data-destino','dArea');
	 		$("#datosRegistro").attr('data-opcion','opcionesTrazabilidad');
	 		$("#opcion").val('area');
	 	 	abrir($("#datosRegistro"),event,false);
		 });

		$("#area").change(function(event){
	 		$("#datosRegistro").attr('data-destino','dProducto');
	 		$("#datosRegistro").attr('data-opcion','opcionesTrazabilidad');
	 		$("#opcion").val('producto');
	 	 	abrir($("#datosRegistro"),event,false);
		 });

		
		
	}else{

		$("#sitio").change(function(event){
			
	 		$("#nuevoIngreso").attr('data-destino','dArea');
	 		$("#opcion").val('area');
	 		$("#nombreSitioProveedor").val($('#sitio option:selected').text());
	 	 	abrir($("#nuevoIngreso"),event,false);
		 });

		$("#area").change(function(event){
	 		$("#nuevoIngreso").attr('data-destino','dProducto');
	 		$("#opcion").val('producto');
	 		$("#nombreAreaProveedor").val($('#area option:selected').text());
	 	 	abrir($("#nuevoIngreso"),event,false);
		 });

		$("#producto").change(function(event){
			$("#nuevoIngreso").attr('data-destino','dOperacionProveedor');
	 		$("#opcion").val('operacionProveedor');
	 		$("#nombreProducto").val($('#producto option:selected').text());
	 	 	abrir($("#nuevoIngreso"),event,false);
		});

		$("#operacionProveedor").change(function(event){
			$("#nuevoIngreso").attr('data-destino','dAreaOperador');
	 		$("#opcion").val('areaOperador');
	 		$("#nombreOperacionProveedor").val($('#operacionProveedor option:selected').text());
	 	 	abrir($("#nuevoIngreso"),event,false);
		});

		$("#areaOperador").change(function(event){
			$("#nuevoIngreso").attr('data-destino','dOperacionOperador');
	 		$("#opcion").val('operacionOperador');
	 		$("#nombreAreaOperador").val($('#areaOperador option:selected').text());
	 	 	abrir($("#nuevoIngreso"),event,false);
		});

		$("#operacionOperador").change(function(event){
	 		$("#nombreOperacionOperador").val($('#operacionOperador option:selected').text());
		});
		
	}

	
	 
</script>