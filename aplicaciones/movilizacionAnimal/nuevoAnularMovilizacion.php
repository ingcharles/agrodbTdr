<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorMovilizacionAnimal.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$cm = new ControladorMovilizacionAnimal();

?>
<header>
	<h1>Anular movilización</h1>
</header>
<form id='nuevoAnularMovilizacion' data-rutaAplicacion='movilizacionAnimal' data-opcion='guardarAnularMovilizacion' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
<input type="hidden" id="usuario_responsable" name="usuario_responsable" value="<?php echo $_SESSION['usuario'];?>" />
<input type="hidden" id="opcion" name="opcion" value="0">
<input type="hidden" id="serieCertificadoMovilizacion" name="serieCertificadoMovilizacion" value="0" />	
<div id="estado"></div>
<div id="busqueda">
	<fieldset id="numeroCertificado" name="numeroCertificado">
		<legend>Ingresar el certificado sanitario de movilización interno (CSMI)</legend>
			<div data-linea="1">
			<label>Certificado de movilización :</label> 
			<input type="text" id="txtNumeroCertificado" name="txtNumeroCertificado" />
		</div>
		<div data-linea="1">
			<button type="button" id="btn_certificado_movilizacion" name="btn_certificado_movilizacion">Buscar número certificado</button>
		</div>
		<div data-linea="2" id="res_certificado_movilizacion">
		</div>
    </fieldset>
</div>	
<div id="consulta">
	<fieldset id="consultaCertificado" name="consultaCertificado">
		<legend>Información del certificado sanitario de movilización interno (CSMI)</legend>
		<div data-linea="1" id="res_datos_certificado">
		</div>

    </fieldset>
</div>	       
<div id="visualizar">	
	<fieldset>
		<legend>Información</legend>
			
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
<div id="anular">	    
    <fieldset id="anularCertificado" name="anularCertificado">
		<legend>Motivo anulación del certificado de movilización (CSMI)</legend>								
		<div data-linea="1">			   	
		    <textarea id="motivoAnulacion" name="motivoAnulacion" rows="3" cols="100"></textarea>	    				
		</div>			
	 </fieldset>	
</div>		
<button id="btn_guardar" type="button" name="btn_guardar">Anular certificado movilización</button>

</form>

<script type="text/javascript">			
var array_canton= <?php echo json_encode($cantones); ?>;
var array_parroquia= <?php echo json_encode($parroquias); ?>;
var array_coordinacion= <?php echo json_encode($coordinaciones); ?>;

    $(document).ready(function(){			
		distribuirLineas();	
		$("#funcionario_emisor").hide();
		$("#otro_emisor").hide();	
	});

    $("#btn_certificado_movilizacion").click(function(event){				
		 var h1 = ($('#txtNumeroCertificado').val());
		 h=("0000000000" + h1).slice (-9);
		 valorCadena = h.length;			
		 if (valorCadena < 6)
	 		alert('Error en el número de certificado de movilización');

		 //alert(h);
		 //alert(valorCadena);
	 		
		 $('#serieCertificadoMovilizacion').val(h);			 
		 $('#nuevoAnularMovilizacion').attr('data-opcion','guardarAnularMovilizacion');
		 $('#nuevoAnularMovilizacion').attr('data-destino','res_certificado_movilizacion');
		 $('#opcion').val('1');		
		 abrir($("#nuevoAnularMovilizacion"),event,false); //Se ejecuta ajax, busqueda de sitio	
								 			 						 		
	});
	
    $("#cmbCertificadoMovilizacion").change(function(event){
    	
        	alert('saludos amigos');
        	/*
    		 $('#nuevoAnularMovilizacion').attr('data-opcion','guardarAnularMovilizacion');	
			 $('#nuevoAnularMovilizacion').attr('data-destino','res_datos_certificado');		 
		     $('#opcion').val('2');		
			 abrir($("#nuevoAnularMovilizacion"),event,false); //Se ejecuta ajax, busqueda de vacunador
			 //$('#txtBusquedaVacunador').val('');
			 */
    	          					 				
     });    
	
    //eventos de los botones   
    $("#btn_guardar").click(function(event){
		 event.preventDefault();
		 $('#nuevoResponsableMovilizacion').attr('data-opcion','guardarResponsableMovilizacion');
		 $('#nuevoResponsableMovilizacion').attr('data-destino','res_guardar');
	     $('#opcion').val('10');		     	
		 abrir($("#nuevoResponsableMovilizacion"),event,false); //Se ejecuta ajax, busqueda de sitio
		 abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),null,true);			 		 			 		 	
	});
	
	function chequearCamposGuardar(form){
		$("#estado").html("").addClass('correcto');
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false; 
        
		if(!$.trim($("#especie").val())){
			error = true;
			$("#especie").addClass("alertaCombo");
		}		
		
		if (!error){
			return true;		
		}else{			
			$("#estado").html("Por favor revise el formato de la información ingresada").addClass('alerta');
			return false;
		}
		
	}
	
</script>