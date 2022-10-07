<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacunacionAnimal.php';
require_once '../../clases/ControladorMovilizacionAnimal.php';

$contador = 0;
$itemsFiltro[] = array();

$conexion = new Conexion();
$cvm = new ControladorVacunacionAnimal();
$cm = new ControladorMovilizacionAnimal();

$qMovilizacionAnimal = $cm->listaMovilizacionFiltro($conexion, $_POST['id']);
$caberaMovilizacion = pg_fetch_assoc($qMovilizacionAnimal);

$qDetalleMovilizacionAnimal = $cm-> listaFiltroDetalleMovilizacionAnimal($conexion,$caberaMovilizacion['id_movilizacion_animal']);
$ruta_numero_certificado = $caberaMovilizacion['ruta_numero_certificado'];

$qTicked = $cm-> listaTicked($conexion, $caberaMovilizacion['id_sitio_destino'], $caberaMovilizacion['id_area_destino'], $caberaMovilizacion['id_especie']);

$var;
$var1;
while($fila = pg_fetch_assoc($qTicked)){
	$var = $fila['numero_ticket'];
	if ($var1 =='')
		$var1 = $var;
	else
		$var1 = $var1 .'; '. $var;
}

?>
<style type="text/css">
#tablaVacunaAnimal td, #tablaVacunaAnimal th 
{
font-size:1em;
border:1px solid rgba(0,0,0,.1);
padding:3px 7px 2px 7px;
}
</style>
<header>
	<h1>Registro movilización</h1>
</header>
<form id='nuevoVacunacionAnimal' data-rutaAplicacion='movilizacionAnimal' data-opcion='actualizarVacunacion' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
<div id="visualizar">	
	<fieldset>
		<legend>Información </legend>
			<div data-linea="1">
				<label>N°Certificado</label>	
				<input type="text" id="certificadoMovilizacion" name="certificadoMovilizacion" value="<?php echo $caberaMovilizacion['numero_certificado'];?>" disabled="disabled"/>													  
			</div>
			<div data-linea="1">
				<label>Especie</label>	
				<input type="text" id="nombreEspecie" name="nombreEspecie" value="<?php echo $qDetalleMovilizacionAnimal[0]['nombre_especie'];?>" disabled="disabled"/> 													  
			</div>	
			<div data-linea="2">
				<label>Lugar emisión</label> 
				<input type="text" id="lugarEmision" name="lugarEmision" value="<?php echo $caberaMovilizacion['lugar_emision'];?>" disabled="disabled"/>
			</div>
			<div data-linea="2">
				<label>Fecha registro</label> 
				<input type="text" id="fechaMovilizacion" name="fechaMovilizacion" value="<?php echo $caberaMovilizacion['fecha_movilizacion'];?>" disabled="disabled"/>
			</div>
			<div data-linea="3">
				<label>Fecha autorizada desde</label> 
				<input type="text" id="fechaValidesDesde" name="fechaValidesDesde" value="<?php echo $caberaMovilizacion['fecha_movilizacion_desde'];?>" disabled="disabled"/>
			</div>	
			<div data-linea="3">
				<label>Fecha autorizada hasta</label> 
				<input type="text" id="fechaValidesHasta" name="fechaValidesHasta" value="<?php echo $caberaMovilizacion['fecha_movilizacion_hasta'];?>" disabled="disabled"/>
			</div>							
			<div data-linea="4">
				<label>Sitio de origen</label> 
				<input type="text" id="nombreSitioOrigen" name="nombreSitioOrigen" value="<?php echo $caberaMovilizacion['nombre_sitio_origen'];?>" disabled="disabled"/>
			</div>
			<div data-linea="4">
				<label>Area de origen</label> 
				<input type="text" id="nombreAreaOrigen" name="nombreAreaOrigen" value="<?php echo $caberaMovilizacion['nombre_area_origen'];?>" disabled="disabled"/>
			</div>				
			<div data-linea="5">
				<label>Sitio de destino</label> 
				<input type="text" id="nombreSitioDestino" name="nombreSitioDestino" value="<?php echo $caberaMovilizacion['nombre_sitio_destino'];?>" disabled="disabled"/>
			</div>
			<div data-linea="5">
				<label>Area de destino</label> 
				<input type="text" id="nombreAreaDestino" name="nombreAreaDestino" value="<?php echo $caberaMovilizacion['nombre_area_destino'];?>" disabled="disabled"/>
			</div>			
			<div data-linea="6">
				<label>CI: Autorizado</label> 
				<input type="text" id="identificacion_autorizado" name="identificacion_autorizado" value="<?php echo $caberaMovilizacion['identificador_autorizado'];?>" disabled="disabled"/>
			</div>			   			  
			<div data-linea="6">
				<label>Autorizado</label> 
				<input type="text" id="nombre_autorizado" name="nombre_autorizado" value="<?php echo $caberaMovilizacion['nombre_autorizado'];?>" disabled="disabled"/>
			</div>									   			 
			<div data-linea='8'>
				<label>Medio transporte</label>
				<input type='text' id='medioTransporte' name='medioTransporte' value="<?php echo $caberaMovilizacion["medio_transporte"];?>" disabled='disabled'/>
			</div>
			<?php
				if($caberaMovilizacion['medio_transporte']=='Terrestre'){
					echo  "<div data-linea='9'>
						   <label>Placa</label>
						   <input type='text' id='placa' name='placa' value='".$caberaMovilizacion["placa"]."' disabled='disabled'/>";
					echo  "</div>";
					echo  "<div data-linea='9'>
						   <label>CI: Conductor</label>
						   <input type='text' id='identificacion_conductor' name='identificacion_conductor' value='".$caberaMovilizacion["identificacion_conductor"]."' disabled='disabled'/>";
					echo  "</div>";
				} 				
			?>					
			<div data-linea="10">
				<label>Descripción transporte</label>
				<input type="text" id="descripcionTransporte" name="descripcionTransporte" value="<?php echo $caberaMovilizacion['descripcion_transporte'];?>" disabled="disabled"/>
			</div>												
	</fieldset>
	<fieldset>
		<legend>Detalle animales movilización</legend>
		<table id="tablaVacunaAnimal">
				<thead>
					<tr>
						<th>#</th>
						<th>Producto</th>
						<th>Cantidad</th>									
						<th>No.Certificado</th>					
						<th>Fecha vigencia</th>
					</tr>
				</thead>
					<?php
						$i=1;					
						foreach ($qDetalleMovilizacionAnimal as $detalleMovilizacion){
							echo  '<tr>
			                           <td>'.$i.'</th>
									   <td>'.$detalleMovilizacion['nombre_producto'].' </td>
									   <td>'.$detalleMovilizacion['cantidad'].'</td>
									   <td>'.$detalleMovilizacion['numero_certificado'].'</td>
									   <td>'.$detalleMovilizacion['fecha_certificado'].'</td>
			                       </tr>';				
							$i++;
						}					
				    ?>				
			  </table>			
	</fieldset>	
	<?php 
	 if ($var1!=''){
	 	echo "<fieldset>
				<legend>Detalle Ticket</legend>
		    		<div data-linea='15'>
		   				<label>Serie Ticket</label>		    		    			
					</div>	
					<div data-linea='16'>	   		
						<textarea name='ticket' rows='5' cols='100' disabled='disabled'>".$var1."</textarea>		    				
					</div>	
				</fieldset>";
	 }
	?>
</div>
<div id="imprimir">
	<a href="<?php echo $ruta_numero_certificado?>" target="visor" id="enlace" style="text-decoration: none;" >Imprimir CSMA</a>
	<iframe id = "visor" name="visor" width="550" height="500" src="about:blank"></iframe>
</div>
</form>

<script type="text/javascript">
	$(document).ready(function(){
		distribuirLineas();
	});
	
	$("#enlace").click(function() {
		$('#visualizar').hide();
	    url = $(this).attr("href");
	    window.open(url, 'visor');
	    return false;
	});
</script>