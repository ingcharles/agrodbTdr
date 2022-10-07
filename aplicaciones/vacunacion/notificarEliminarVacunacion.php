<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacunacion.php';

$conexion = new Conexion();
$va = new ControladorVacunacion();
set_time_limit(1000);
$idVacunacion=$_POST['elementos'];
$qVacunacion = $va->abrirVacunacion($conexion, $idVacunacion);
$filaVacuna = pg_fetch_assoc($qVacunacion);
$qDetalleVacunacion=$va->abrirDetalleVacunacion($conexion, $idVacunacion);
$datosDetalleVacunacion=pg_fetch_all($qDetalleVacunacion);
$banderaTransacion=false;
$banderaEstadoVigente=false;

foreach ($datosDetalleVacunacion as $filaDetalle){
	$detalleIdentificadores.='<tr>
			<td>'.$filaDetalle['tipo_operacion'].' </td>
			<td>'.$filaDetalle['area'].' </td>
			<td>'.$filaDetalle['producto_subtipo'].'-'.$filaDetalle['producto'].' </td>
			<td>'.$filaDetalle['cantidad'].'</td>
			<td>'.$filaDetalle['numero_lote'].'</td>
			<td>'.$filaDetalle['unidad_comercial'].'</td><td>';
	$qDetalleIdentificadores=$va->abrirDetalleVacunacionIdentificadores($conexion, $filaDetalle['id_detalle_vacunacion']);
	while($filaIdentificadores=pg_fetch_assoc($qDetalleIdentificadores)){
		if($filaDetalle['cantidad']<=5){
			$qResultadoMovilizacion=$va->verificarFechaRegistroIdentificadorMovilizacion($conexion, $filaIdentificadores['identificador']);
			if(pg_num_rows($qResultadoMovilizacion)>0){
				$fechaRegistro=pg_fetch_result($qResultadoMovilizacion, 0, 'fecha_registro');
				if($filaVacuna['fecha_vacunacion']<=$fechaRegistro && $fechaRegistro<=$filaVacuna['fecha_vencimiento']){
					$banderaTransacion=true;
				}
			}
		}
		$detalleIdentificadores.=$filaIdentificadores['identificador'].'<br>';
	}
	echo '</td></tr>';
	if($filaDetalle['cantidad']>5){
		$qResultadoMovilizacion=$va->verificarFechaRegistroIdentificadorVacunacionMovilizacion($conexion, $filaDetalle['id_detalle_vacunacion']);
		if(pg_num_rows($qResultadoMovilizacion)>0){
			$fechaRegistro=pg_fetch_result($qResultadoMovilizacion, 0, 'fecha_registro');
			if($filaVacuna['fecha_vacunacion']<=$fechaRegistro && $fechaRegistro<=$filaVacuna['fecha_vencimiento']){
				$banderaTransacion=true;
			}
		}
	}

}

if($filaVacuna['estado']!='vigente')
	$banderaEstadoVigente=true;

?>
<form id='NotificarEliminarVacunacion' data-rutaAplicacion='vacunacion' data-opcion='guardarNuevoEliminarVacunacion' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
	<div id="estado"></div>
	<input type="hidden" name="idVacunacion" value="<?php echo $idVacunacion;?>" />	
	<input type="hidden" name="idEspecie" value="<?php echo $filaVacuna['id_especie'];?>" />	
	<input type="hidden" name="numeroCertificadoVacunacion" value="<?php echo $filaVacuna['numero_certificado'];?>" />	

	<div id='vistaCertificadoTransacciones'>
		<header>
			<h1>Registro de vacunación</h1>
		</header>
		<fieldset>
		
			<legend>Información del estado del certificado de vacunación</legend>
			<div data-line="1" >
				<label>Estado: </label> <?php echo $filaVacuna['estado'];?>
			</div>	
			<div data-line="2" >
				<label >Motivo: </label> El certificado no puede ser eliminado porque se han movilizado productos existentes del certificado.
			</div>	
		</fieldset>  	
	</div>
	<div id='vistaNuevaEliminacion'>
		<header>
			<h1>Nueva eliminación de vacunación</h1>
		</header>
		<?php if($banderaEstadoVigente){?>
			<fieldset>
			<legend>Información del estado del certificado de vacunación</legend>
			
				<div data-line="1" >
					<label>Estado: </label> <?php echo $filaVacuna['estado'];?>
				</div>	
				<div data-line="2" >
					<label >Motivo: </label> El certificado no puede ser eliminado por el estado en el que se encuentra.
				</div>
			</fieldset>
		<?php }else{?>
			<p style="text-align: center">
				<button type="submit" id="guardarEliminarVacunacion" name="guardarEliminarVacunacion" >Eliminar vacunación</button>
			</p>
		<?php }?>
	</div>
	    <fieldset>
			<legend>Datos Certificado de Vacunación</legend>
			<div data-linea="1">
				<label>Especie: </label>	
				<input type="text" id="nombreEspecie" name="nombreEspecie" value="<?php echo $filaVacuna['nombre_especie'];?>" disabled="disabled"/>													  
			</div>	
			<div data-linea="1">
				<label>N° Certificado: </label>
				<input type="text" id="numeroCertificado" name="numeroCertificado" value="<?php echo $filaVacuna['numero_certificado'];?>" disabled="disabled"/>													  
			</div>
			<div data-linea="2">
				<label>Identificación operador: </label> 
				<input type="text" id="identificacionOperador" name="identificacionOperador" value="<?php echo $filaVacuna['identificador_operador'];?>" disabled="disabled"/>
			</div> 
			<div data-linea="2">
				<label>Nombre operador: </label> 
				<input type="text" id="nombreOperador" name="nombreOperador" value="<?php echo $filaVacuna['nombre_operador'];?>" disabled="disabled"/>
			</div>
			<div data-linea="3">
				<label>Nombre del sitio: </label> 
				<input type="text" id="nombreSitio" name="nombreSitio" value="<?php echo $filaVacuna['nombre_sitio'];?>" disabled="disabled"/>
			</div> 
			<div data-linea="3">
				<label>Operador vacunación: </label> 
				<input type="text" id="operadorVacunacion" name="operadorVacunacion" value="<?php echo $filaVacuna['nombre_administrador'];?>" disabled="disabled"/>
			</div>
			<div data-linea="4" >
				<label>Vacunador: </label> 
					<input type="text" id="vacunador" name="vacunador" value="<?php echo $filaVacuna['nombre_vacunador'];?>" disabled="disabled"/>
			</div>
			<div data-linea="4">
				<label>Distribuidor: </label> 
					<input type="text" id="distribuidor" name="distribuidor" value="<?php echo $filaVacuna['nombre_distribuidor'];?>" disabled="disabled"/>
			</div>
			<div data-linea="5">
				<label>Tipo vacuna: </label>
					<input type="text" id="tipoVacuna" name="tipoVacuna" value="<?php echo $filaVacuna['nombre_vacuna'];?>" disabled="disabled"/>
			</div>
			<div data-linea="5">
				<label>Laboratorio: </label>
					<input type="text" id="laboratorio" name="laboratorio" value="<?php echo $filaVacuna['nombre_laboratorio'];?>" disabled="disabled"/>
			</div>
			<div data-linea="6">
				<label>Lote vacuna: </label>
					<input type="text" id="loteVacuna" name="loteVacuna" value="<?php echo $filaVacuna['numero_lote'];?>" disabled="disabled"/>
			</div>		
			<div data-linea="6">
			    <label>Fecha de vacunación: </label> 
				<input type="text" id="fechaVacunacion" name="fechaVacunacion" value="<?php echo date("d/m/Y", strtotime($filaVacuna['fecha_vacunacion'])) ;?>" disabled="disabled" readonly />
			</div>
			<div data-linea="7">
				<label>Fecha de vencimiento: </label> 
				<input type="text" id="fechaVencimiento" name="fechaVencimiento" value="<?php echo $filaVacuna['fecha_vencimiento'];?>" disabled="disabled"/>			
			</div>			
			<div data-linea="7">
				<label>Fecha de registro: </label> 
				<input type="text" id="fechaRegistro" name="fechaRegistro" value="<?php echo $filaVacuna['fecha_registro'];?>" disabled="disabled"/>			
			</div>
			<div data-linea="8">
			    <label>Cantidad vacunada: </label> 
				<input type="text" id="totalVacuna" name="totalVacuna" value="<?php echo $filaVacuna['total_vacunado'];?>" disabled="disabled"/>
			</div>	
			<div data-linea="8">
			    <label>Costo de vacuna: </label> 
				<input type="text" id="costoVacuna" name="costoVacuna" value="<?php echo $filaVacuna['costo_vacuna'];?>" disabled="disabled"/>
			</div>							
			<div data-linea="9">
			    <label>Total costo vacuna: </label> 
				<input type="text" id="totalVacuna" name="totalVacuna" value="<?php echo $filaVacuna['costo_total_vacuna'];?>" disabled="disabled"/>
			</div>	
			<div data-linea="9">
			    <label>Estado certificado: </label> 
				<input type="text" id="estadoVacuna" name="estadoVacuna" value="<?php echo $filaVacuna['estado'];?>" disabled="disabled"/>
			</div>						
		</fieldset>
		<fieldset>
			<legend>Detalle de Productos Vacunados</legend>
			<table id="tablaVacunaAnimal" style="width:100%">
				<thead>
					<tr>
						<th>Operación</th>
						<th>Área</th>
						<th>Producto</th>
						<th>Cantidad</th>									
						<th title="Número de lote">N° Lote</th>
						<th title="Unidad comercial">U.Comercial</th>
						<th>Identificador Producto</th>				
					</tr>
				</thead>
					<?php 
						echo $detalleIdentificadores;
				    ?>				
		      </table>	
		</fieldset>
</form>		
<script type="text/javascript">
	var controlEliminacionVacunacion = <?php echo json_encode($banderaTransacion); ?>;
	$(document).ready(function(){
		distribuirLineas();

		
		if(<?php echo json_encode($idVacunacion); ?> == '')
			$("#detalleItem").html('<div class="mensajeInicial">Seleccione un registro de vacunación a eliminar.</div>');
	
		if(controlEliminacionVacunacion){
			$("#vistaCertificadoTransacciones").show();
			$("#vistaNuevaEliminacion").hide();
		}else{
			$("#vistaCertificadoTransacciones").hide();
			$("#vistaNuevaEliminacion").show();
		}
	});
	
	$("#NotificarEliminarVacunacion").submit(function(event){
		event.preventDefault();
		ejecutarJson($(this));	
		
	});
</script>
