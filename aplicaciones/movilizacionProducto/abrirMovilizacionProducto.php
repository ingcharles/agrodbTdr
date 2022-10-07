<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorMovilizacionProductos.php';

$conexion = new Conexion();
$cmp = new ControladorMovilizacionProductos();

$idMovilizacion=$_POST['id'];
$qMovilizacion = $cmp->abrirMovilizacionProducto($conexion, $idMovilizacion);
$filaMovilizacion = pg_fetch_assoc($qMovilizacion);
$qDetalleMovilizacion=$cmp->abrirDetalleMovilizacion($conexion, $idMovilizacion);

?>

<header>
	<h1>Registro de Certificado de Movilización</h1>
</header>
<form id='actualizarMovilizacion' data-rutaAplicacion='movilizacionProducto' data-opcion='actualizarMovilizacion' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">	
	<div id="estado"></div>
	<fieldset>
		<legend>Datos Generales</legend>
		<div data-linea="1">
			<label>Tipo Solicitud: </label>	
			<input type="text" id="tipoSolicitud" name="tipoSolicitud" value="<?php echo $filaMovilizacion['tipo_solicitud'];?>" disabled="disabled"/>													  
		</div>
		<div data-linea="2">
			<label>Provincia Emisión: </label>	
			<input type="text" id="provinciaEmision" name="provinciaEmision" value="<?php echo $filaMovilizacion['provincia_emision'];?>" disabled="disabled"/>													  
		</div>
		<div data-linea="3">
			<label>Oficina Emisión: </label>	
			<input type="text" id="oficinaEmision" name="oficinaEmision" value="<?php echo $filaMovilizacion['oficina_emision'];?>" disabled="disabled"/>													  
		</div>
		<div data-linea="4">
			<label>N° Certificado: </label>
			<input type="text" id="numeroCertificado" name="numeroCertificado" value="<?php echo $filaMovilizacion['numero_certificado'];?>" disabled="disabled"/>													  
		</div>
		<div data-linea="5">
		    <label>Fecha Emision: </label> 
			<input type="text" id="fechaRegistro" name="fechaRegistro" value="<?php echo $filaMovilizacion['fecha_registro'] ;?>" disabled="disabled" readonly />
		</div>
		<div data-linea="6">
			<label>Fecha Inicio  de Vigencia: </label> 
			<input type="text" id="fechaInicioVigencia" name="fechaInicioVigencia" value="<?php echo $filaMovilizacion['fecha_inicio_vigencia'];?>" disabled="disabled"/>			
		</div>			
		<div data-linea="7">
			<label>Fecha Fin de Vigencia: </label> 
			<input type="text" id="fechaFinVigencia" name="fechaFinVigencia" value="<?php echo $filaMovilizacion['fecha_fin_vigencia'];?>" disabled="disabled"/>			
		</div>
	 	<div data-linea="8">
			<label>Ver Certificado:</label>
			<?php echo ($filaMovilizacion['ruta_certificado']==''? '<span class="alerta">No ha generado ningún certificado</span>':'<a href="'.$filaMovilizacion['ruta_certificado'].'" target="_blank" class="archivo_cargado" id="archivo_cargado">Clic aquí para ver el Certificado</a>')?>
		</div>
		<div data-linea="9">
			<label>Ver Tickets:</label>
			<?php echo ($filaMovilizacion['ruta_ticket']==''? '<span class="alerta">Este certificado no contiene ticket</span>':'<a href="'.$filaMovilizacion['ruta_ticket'].'" target="_blank" class="archivo_cargado" id="archivo_cargado">Clic aquí para ver Ticket</a>')?>
		</div>
		
	</fieldset>

	<fieldset>
		<legend>Datos Sitio Origen</legend>	
		<div data-linea="1">
			<label>Identificación Operador: </label> 
			<input type="text" id="identificacionOperadorOrigen" name="identificacionOperadorOrigen" value="<?php echo $filaMovilizacion['identificador_operador_origen'];?> " disabled="disabled"/>
		</div> 
		<div data-linea="2">
			<label>Nombre Operador: </label> 
			<input type="text" id="nombreOperadorOrigen" name="nombreOperadorOrigen" value=" <?php echo $filaMovilizacion['nombre_operador_origen'];?>" disabled="disabled"/>
		</div> 
		<div data-linea="3">
			<label>Sitio: </label> 
			<input type="text" id="sitioOrigen" name="sitioOrigen" value="<?php echo $filaMovilizacion['nombre_sitio_origen'];?>" disabled="disabled"/>
		</div> 
		<div data-linea="5">
			<label>Codigo de  Sitio: </label> 
			<input type="text" id="codigoSitioOrigen" name="codigoSitioOrigen" value="<?php echo $filaMovilizacion['codigo_sitio_origen'];?>" disabled="disabled"/>
		</div> 
		<div data-linea="6">
			<label>Provincia: </label> 
			<input type="text" id="provinciaSitioOrigen" name="provinciaSitioOrigen" value="<?php echo $filaMovilizacion['provincia_sitio_origen'];?>" disabled="disabled"/>
		</div> 
		<div data-linea="7">
			<label>Cantón: </label> 
			<input type="text" id="cantonSitioOrigen" name="codigoSitioOrigen" value="<?php echo $filaMovilizacion['canton_sitio_origen'];?>" disabled="disabled"/>
		</div> 
		<div data-linea="8">
			<label>Parroquia: </label> 
			<input type="text" id="parroquiaSitioOrigen" name="parroquiaSitioOrigen" value="<?php echo $filaMovilizacion['parroquia_sitio_origen'];?>" disabled="disabled"/>
		</div> 
	</fieldset>
	
	<fieldset>
		<legend>Datos Sitio Destino</legend>	
		<div data-linea="1">
			<label>Identificación Operador: </label> 
			<input type="text" id="identificacionOperadorDestino" name="identificacionOperadorDestino" value="<?php echo $filaMovilizacion['identificador_operador_destino'];?> " disabled="disabled"/>
		</div> 
		<div data-linea="2">
			<label>Nombre Operador: </label> 
			<input type="text" id="nombreOperadorDestino" name="nombreOperadorDestino" value=" <?php echo $filaMovilizacion['nombre_operador_destino'];?>" disabled="disabled"/>
		</div> 
		<div data-linea="3">
			<label>Sitio: </label> 
			<input type="text" id="sitioDestino" name="sitioDestino" value="<?php echo $filaMovilizacion['nombre_sitio_destino'];?>" disabled="disabled"/>
		</div> 
		<div data-linea="4">
			<label>Codigo de  Sitio: </label> 
			<input type="text" id="codigoSitioDestino" name="codigoSitioDestino" value="<?php echo $filaMovilizacion['codigo_sitio_destino'];?>" disabled="disabled"/>
		</div> 
		<div data-linea="5">
			<label>Provincia: </label> 
			<input type="text" id="provinciaSitioDestino" name="provinciaSitioDestino" value="<?php echo $filaMovilizacion['provincia_sitio_destino'];?>" disabled="disabled"/>
		</div> 
		<div data-linea="6">
			<label>Cantón: </label> 
			<input type="text" id="cantonSitioDestino" name="codigoSitioDestino" value="<?php echo $filaMovilizacion['canton_sitio_destino'];?>" disabled="disabled"/>
		</div> 
		<div data-linea="7">
			<label>Parroquia: </label> 
			<input type="text" id="parroquiaSitioDestino" name="parroquiaSitioDestino" value="<?php echo $filaMovilizacion['parroquia_sitio_destino'];?>" disabled="disabled"/>
		</div> 
	</fieldset>
	
	<fieldset>
		<legend>Datos de Movilización</legend>
			<div data-linea="1">
				<label>Identificación Solicitante: </label> 
				<input type="text" id="identificadorSolicitante" name="identificadorSolicitante" value="<?php echo $filaMovilizacion['identificador_solicitante'];?>" disabled="disabled"/>
			</div>
			<div data-linea="2">
				<label>Nombre Solicitante: </label> 
				<input type="text" id="nombreSolicitante" name="nombreSolicitante" value="<?php echo $filaMovilizacion['nombre_solicitante'];?>" disabled="disabled"/>
			</div>	
			<div data-linea="3">
				<label>Medio de Transporte: </label> 
				<input type="text" id="medioTransporte" name="medioTransporte" value="<?php echo $filaMovilizacion['medio_transporte'];?>" disabled="disabled"/>
			</div>
			<div data-linea="4">
				<label>Placa  de Transporte: </label> 
				<input type="text" id="placaTransporte" name="placaTransporte" value="<?php echo $filaMovilizacion['placa_transporte'];?>" disabled="disabled"/>
			</div>	
			<div data-linea="5">
				<label>Identificación Conductor: </label> 
				<input type="text" id="identificacionConductor" name="identificacionConductor" value="<?php echo $filaMovilizacion['identificador_conductor'];?>" disabled="disabled"/>
			</div>
			<div data-linea="6">
				<label>Nombre Conductor: </label> 
				<input type="text" id="nombreConductor" name="nombreConductor" value="<?php echo $filaMovilizacion['nombre_conductor'];?>" disabled="disabled"/>
			</div>
			<div data-linea="7">
				<label>Observacion: </label> 
				<input type="text" id="observacion" name="observacion" value="<?php echo $filaMovilizacion['observacion'];?>" disabled="disabled"/>
			</div>							
	</fieldset>
	
	<fieldset>
		<legend>Detalle de Productos Movilizados</legend>
		<table id="tablaVacunaAnimal">
			<thead>
				<tr>
					<th title="Operación Origen">Origen</th>
					<th title="Operación Destino">Destino</th>
					<th>Producto</th>
					<th>Cantidad</th>			
					<th>Letras</th>						
					<th title="Unidad Comercial">Unidad</th>
					<th>N° Identificadores</th>
										
				</tr>
			</thead>
			<?php 
		
			while($filaDetalleMovilizacion=pg_fetch_assoc($qDetalleMovilizacion)){
				echo '<tr>
						<td>'.$filaDetalleMovilizacion['operacion_origen'].' </td>
						<td>'.$filaDetalleMovilizacion['operacion_destino'].' </td>
						<td>'.$filaDetalleMovilizacion['producto'].' </td>
						<td>'.$filaDetalleMovilizacion['cantidad'].'</td>
						<td>'.$filaDetalleMovilizacion['letras'].' </td>
						<td >'.$filaDetalleMovilizacion['unidad_comercial'].'</td><td style="width:100%"><div style="width:102%; max-height:120px; overflow:auto">';
				$qDetalleMovilizacionIdentificadores=$cmp->abrirDetalleMovilizacionIdentificadores($conexion, $filaDetalleMovilizacion['id_detalle_movilizacion']);
				while($filaDetalleMovilizacionIdentificadores=pg_fetch_assoc($qDetalleMovilizacionIdentificadores)){
					echo $filaDetalleMovilizacionIdentificadores['identificador'].' <br>';			
				}
                echo '</div></td> </tr>';				
				
			}
		    ?>				
		</table>  			
	</fieldset>
	 <p>
</form>

<script type="text/javascript">
$(document).ready(function(){
	distribuirLineas();
});	
</script>