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



$controlAnulacion = 0;
if($caberaMovilizacion['estado']=='activo'){
 $controlAnulacion = 1; 
}
else{
 $controlAnulacion = 0;
 $tipoAnulacion = '';
 $fechaAnulacion = '';
 $estadoAnulacion = 'Certificado anulado';
 $motivoAnulacion = '';
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
	<h1>Resgistro movilización</h1>
</header>
<form id='abrirAnularMovilizacion' data-rutaAplicacion='movilizacionAnimal' data-opcion='guardarAnularMovilizacion' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
<input type="hidden" name="id_movilizacion" value="<?php echo $_POST['id'];?>" />	
<input type="hidden" id="usuario_responsable" name="usuario_responsable" value="<?php echo $_SESSION['usuario'];?>" />  
<input type="hidden" id="numero_documento" name="numero_documento" value="<?php echo $caberaMovilizacion['numero_certificado'];?>" />

<div id='anular_vista'>	
	<fieldset>
		<legend>Información de la anulación del certificado de movilización </legend>						
		<div data-linea="11">
			<label>Certificado movilización:</label>
			<input type="text" id="numero_certificado_vista" name="numero_certificado_vista" value="<?php echo $caberaMovilizacion['numero_certificado'];?>" disabled="disabled"/>			
		</div>
		<div data-linea="11">			
			<label>Fecha anulación</label>				
			<input type="text" id="fecha_anulacion_vista" name="fecha_anulacion_vista"  value="<?php echo $caberaMovilizacion['fecha_anulacion'];?> "disabled="disabled"/>
		</div>
		<div data-linea="12">
			<label>Estado certificado : </label>
			<input type="text" id="estado_vista" name="estado_vista" value="<?php echo $estadoAnulacion;?>" disabled="disabled"/>			
		</div>		
		<div data-linea="12">	
			<label>Motivo anulación :</label> 					
		</div>
		<div data-linea="13">			   	
		    <input id="motivo_anulacion_vista" name="motivo_anulacion_vista"  disabled="disabled" value="<?php echo $caberaMovilizacion['observacion'];?>" />	    				
		
		</div>				
	</fieldset>   	
</div>
<div id='anular_nuevo'>
	<button id="btn_guardar" type="button" name="btn_guardar">Guardar Anulación de certificados de movilización</button>
	<input type="hidden" id="opcion" name="opcion" value="0">
	<fieldset>
		<legend>Anular del certificado de movilización</legend>		   
		<div data-linea="1">			
			<label>Tipo anulación</label>				
			<select id="cmbTipoAnulacion" name="cmbTipoAnulacion">
				<option value="0">Seleccione...</option>					
				<option value="anulado">Certificado anulado</option>
				<!--  <option value="cambiado">Cambio de certificado de movilización</option> -->									
			</select>
		</div>				
		<div id='certificado1' data-linea="2">
			<label>N°.Certificado de movilización</label>
			<input type="text" id="cambio_numero_certificado" name="cambio_numero_certificado" value="<?php echo $caberaMovilizacion['numero_certificado'];?>" disabled="disabled"/>			
		</div>
		<div id='certificado2' data-linea="3">
			<label>Certificado de movilización</label> 
			<input type="text" id="txtNumeroCertificado" name="txtNumeroCertificado" />
		</div>
		<div id='certificado3' data-linea="4">
			<button type="button" id="btn_certificado_movilizacion" name="btn_certificado_movilizacion">Buscar número certificado</button>
		</div>
		<div data-linea="5" id="res_certificado_movilizacion">
		</div>
			
		<div data-linea="10">	
			<label>Motivo anulación :</label> 					
		</div>
		<div data-linea="11">			   	
		   	<select id="observacion_anular" name="observacion_anular">
				<option value="0">Seleccione...</option>
				<option value="Error digitación">Error digitación</option>					
				<option value="Irregularidades presentadas">Irregularidades presentadas	</option>
				
			</select>	</div>							
	</fieldset>
</div>
<div id='visualizar'>	
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


</form>

<script type="text/javascript">
	
	var Control_Anulacion = <?php echo json_encode($controlAnulacion); ?>;

	$(document).ready(function(){
		distribuirLineas();				
		if(Control_Anulacion == 1){
			$("#anular_vista").hide();
			$("#anular_nuevo").show();			
		}else{
			$("#anular_vista").show();
			$("#anular_nuevo").hide();			
		}
		$("#certificado1").hide();		
		$("#certificado2").hide();
		$("#certificado3").hide();
				
		$("#fecha_anulacion").datepicker({
		      changeMonth: true,
		      changeYear: true
		});
		$("#observacion_anular").attr('disabled','disabled');;
		
	});

	$("#cmbTipoAnulacion").change(function(){ 
		if ($("#cmbTipoAnulacion").val() !='0'){	       
	    	if ($("#cmbTipoAnulacion").val() =='anulado'){
	    		$("#certificado1").hide();		
	    		$("#certificado2").hide();
	    		$("#certificado3").hide();
	    	}
	    	if ($("#cmbTipoAnulacion").val() =='cambiado'){
	    		$("#certificado1").show();		
	    		$("#certificado2").show();
	    		$("#certificado3").show();
	    	}
	    	$('#observacion_anular').removeAttr("disabled");
	    	
		}
	});

	//Para guardar la fiscalización
	//abrirAnularMovilizacion  == guardarAnularMovilizacion
	$("#btn_guardar").click(function(event){
		 $('#abrirAnularMovilizacion').attr('data-opcion','guardarAnularMovilizacion');
		 $('#abrirAnularMovilizacion').attr('data-destino','res_anular');
	     event.preventDefault();
	     $('#opcion').val('2');		
		 abrir($("#abrirAnularMovilizacion"),event,false); //Se ejecuta ajax, busqueda de sitio	
		 abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),null,true);			 		 	
	});
		
</script>