<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorTrazabilidad.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorRegistroOperador.php';


$conexion = new Conexion();
$ct = new ControladorTrazabilidad();
$cc = new ControladorCatalogos();
$cr = new ControladorRegistroOperador();

$identificador = $_SESSION['usuario'];


$proveedores = $cr->listarNombresProveedoresOperador($conexion, $identificador);

$unidades = $cc->listarUnidadesMedida($conexion);

$bultos = $cc->ListarBultos($conexion);

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>

<body>
	<header>
		<h1>Reporte Registro Ingresos</h1>
	</header>
	<form id="datosRegistro" data-rutaAplicacion="trazabilidad" data-opcion="listaDetalleIngresoReporte" data-destino="tabla">

	<input type="hidden" id="identificador" name="identificador" value="<?php echo $identificador;?>"/>
	<input type="hidden" id="opcion" name="opcion"/>
	<input type="hidden" id="formulario" name="formulario" value="datosRegistro"/>
	
	
		<table class="filtro">
			<tr>
				<td>
					<div id="estado"></div>
					<fieldset>
						<legend>Datos generales de ingreso</legend>

							<div data-linea="1">

								<label>Código Proveedor</label> 
									<select id="codproveedor" name="codproveedor">
										<option value="" selected="selected">Código Proveedor....</option>
											<?php 
											while ($fila = pg_fetch_assoc($proveedores)){
														echo '<option value="' . $fila['codigo_proveedor'] . '" data-razonSocial="'.$fila['razon_social'].'">'. $fila['codigo_proveedor'] .'</option>';
													}
											?>
									</select>
					
								</div>
								
								<div data-linea="1">
									<label>Proveedor</label> 
										<input type="text" id="proveedor" name="proveedor" disabled="disabled"/>
								</div>						
		
								<div data-linea="2">
									<div id="dSitio"></div>
								</div>
						
								<div data-linea="2">
						
									<div id="dArea"></div>
						
								</div>
						
								<div data-linea="3">
									<div id="dProducto"></div>
								</div>

						<div data-linea="4">

							<label>Fecha Inicio</label> 
								<input type="text" name="fi" id="fechaInicio" />
						</div>

						<div data-linea="4">

							<label>Fecha Fin</label> 
								<input type="text" name="ff" id="fechaFin" />

						</div>
						<div data-linea="5">
							<button>Buscar</button>
						</div>
					</fieldset>
				</td>
			</tr>
		</table>
	</form>
	<div id="tabla"></div>
</body>
<script type="text/javascript">



	
var array_sitio= <?php echo json_encode($sitiosProv); ?>;
var array_proveedorDetalle= <?php echo json_encode($proveedorDatos); ?>;


$("#codproveedor").change(function(event){

		$("#proveedor").val($('#codproveedor option:selected').attr('data-razonSocial'));
		$("#datosRegistro").attr('data-opcion','opcionesTrazabilidad');
		$("#datosRegistro").attr('data-destino','dSitio');
		$("#opcion").val('sitio');
			
		abrir($("#datosRegistro"),event,false); //Se ejecuta ajax, busqueda de puertos
    
 });
		
	
	$("#datosRegistro").submit(function(e){
		e.preventDefault();
		
		$("#datosRegistro").attr('data-opcion','listaDetalleIngresoReporte');
 		$("#datosRegistro").attr('data-destino','tabla');
		
		abrir($(this),e,false);
	});


	
	$(document).ready(function(){
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');
		$("#fechaInicio").datepicker({
		      changeMonth: true,
		      changeYear: true
		    });
		$("#fechaFin").datepicker({
		      changeMonth: true,
		      changeYear: true
		    });
		distribuirLineas();
		});


</script>
</html>
