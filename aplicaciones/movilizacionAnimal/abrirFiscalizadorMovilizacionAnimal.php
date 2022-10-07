<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorMovilizacionAnimal.php';
$conexion = new Conexion();
$ma = new ControladorMovilizacionAnimal();

$qMovilizacion = $ma->listaFiscalizacionMovilizacionAnimalFiltro($conexion, $_POST['id']);
$resultadoMovilizacion = pg_fetch_assoc($qMovilizacion);
$qDetalleMovilizacion = $ma-> listaFiscalizacionDetalleMovilizacionAnimalFiltro($conexion,$resultadoMovilizacion['id_movilizacion_animal']);

$banderaFiscalizacion = 0;
$qBandera = $ma-> abrirFiscalizacionMovilizacionAnimal($conexion, $_POST['id']);
if(pg_num_rows($qBandera)!=0){
	$banderaFiscalizacion = 1;
	$ResultadoFiscalizacionMovilizacion = pg_fetch_assoc($qBandera);
}

?>
<header>
	<h1>Resgistro de fiscalización</h1>
</header>
<form id='nuevoFiscalizacionMoviliacionAnimal' data-rutaAplicacion='movilizacionAnimal' data-opcion='guardarNuevoFiscalizadorMovilizacionAnimal' data-accionEnExito="ACTUALIZAR">
<input type="hidden" name="id_movilizacion_animal" value="<?php echo $_POST['id'];?>" />	
 


<div id='fiscalizacion'>
    <fieldset>
		<legend>Información de la movilización</legend>
			<div data-linea="1">
				<label>N° Certificado</label>	
				<input type="text" id="nombreEspecieValorada" name="nombreEspecieValorada" value="<?php echo $resultadoMovilizacion['numero_certificado'];?>" disabled="disabled"/>													  
			</div>	
			<div data-linea="1">
				<label>Lugar emisión</label> 
				<input type="text" id="lugarEmision" name="lugarEmision" value="<?php echo $resultadoMovilizacion['lugar_emision'];?>" disabled="disabled"/>
			</div>
			<div data-linea="2">
			<label>Sitio origen</label> 
				<input type="text" id="sitioOrigen" name="sitioOrigen" value="<?php echo $resultadoMovilizacion['sitio_origen'];?>" disabled="disabled"/>
			</div>			
			<div data-linea="2">
			    <label>Sitio destino</label> 
				<input type="text" id="sitioDestino" name="sitioDestino" value="<?php echo $resultadoMovilizacion['sitio_destino'];?>" disabled="disabled"/>
			</div>			
			<div data-linea="3">
			    <label>Área origen</label> 
				<input type="text" id="areaOrigen" name="areaOrigen" value="<?php echo $resultadoMovilizacion['area_origen'];?>" disabled="disabled"/>
			</div>
			<div data-linea="3">
				<label>Área destino</label> 
				<input type="text" id="areaDestino" name="areaDestino" value="<?php echo $resultadoMovilizacion['area_destino'];?>" disabled="disabled"/>			
			</div>
			<div data-linea="4">
			    <label>Medio transporte</label> 
				<input type="text" id="medioTransporte" name="medioTransporte" value="<?php echo $resultadoMovilizacion['medio_transporte'];?>" disabled="disabled"/>
			</div>
			<div data-linea="4">
				<label>Placa</label> 
				<input type="text" id="placa" name="placa" value="<?php echo $resultadoMovilizacion['placa'];?>" disabled="disabled"/>			
			</div>					
			<div data-linea="5">
			    <label>Descripción</label> 
				<input type="text" id="descripcionVehiculo" name="descripcionVehiculo" value="<?php echo $resultadoMovilizacion['descripcion_transporte'];?>" disabled="disabled"/>
			</div>
			<div data-linea="5">
				<label>Fecha registro</label> 
				<input type="text" id="fechaRegistro" name="fechaRegistro" value="<?php echo $resultadoMovilizacion['fecha_registro'];?>" disabled="disabled"/>			
			</div>
			<div data-linea="6">
				<label>Fecha desde</label> 
				<input type="text" id="fechaDesde" name="fechaDesde" value="<?php echo $resultadoMovilizacion['fecha_movilizacion_desde'];?>" disabled="disabled"/>			
			</div>
			<div data-linea="6">
			    <label>Fecha hasta</label> 
				<input type="text" id="fechaHasta" name="fechaHasta" value="<?php echo $resultadoMovilizacion['fecha_movilizacion_hasta'];?>" disabled="disabled"/>
			</div>
			<div data-linea="7">
				<label>No. Animales</label> 
				<input type="text" id="totalAnimales" name="totalAnimales" value="<?php echo $resultadoMovilizacion['total'];?>" disabled="disabled"/>			
			</div>
			<div data-linea="7">
				<label>Estado</label> 
				<input type="text" id="estadoMovilizacion" name="estadomovilizacion" value="<?php echo $resultadoMovilizacion['estado'];?>" disabled="disabled"/>			
			</div>
							
	</fieldset>
	
	<fieldset>
		<legend>Detalle animales movilizados</legend>
		<table id="tablaVacunaAnimal">
				<thead>
					<tr>
						<th>#</th>
						<th>Especie</th>
						<th>Producto</th>
						<th>Cantidad</th>									
						<th>No.Certificado</th>
						<th>F.Vigencia</th>					
					</tr>
				</thead>
					<?php 
					$i=1;
						while ($detalleMovilizacion = pg_fetch_assoc($qDetalleMovilizacion)){
						echo  '<tr>
		                           <td>'.$i.'</th>
								   <td>'.$detalleMovilizacion['nombre_especie'].' </td>
								   <td>'.$detalleMovilizacion['producto'].'</td>
								   <td align="center">'.$detalleMovilizacion['cantidad_producto'].'</td>
								   <td>'.$detalleMovilizacion['numero_certificado_vacunacion'].'</td>
								   <td>'.$detalleMovilizacion['fecha_certificado'].'</td>
		                       </tr>';				
						$i++;
					}	
				    ?>				
			  </table>	
	</fieldset>
</div>
<div id='fiscalizacion_vista'>	
	<fieldset>
		<legend>Información de la fiscalización </legend>		
		<div data-linea="10">
			<label>N° de fiscalización : </label>
			<input type="text" id="numeroFiscalizacion" name="numeroFiscalizacion" value="<?php echo $ResultadoFiscalizacionMovilizacion['numero_certificado'];?>" disabled="disabled"/>			
		</div>
		<div data-linea="11">
			<label>Fecha fiscalización : </label>
			<input type="text" id="fechaFiscalizacion" name="fechaFiscalizacion" value="<?php echo $ResultadoFiscalizacionMovilizacion['fecha_fiscalizacion'];?>" disabled="disabled"/>			
		</div>
		<div data-linea="11">
			<label>Respuesta :</label>
			<input type="text" id="estadoRespuestaFiscalizcion" name="estadoRespuestaFiscalizcion" value="<?php echo $ResultadoFiscalizacionMovilizacion['estado_fiscalizacion'];?>" disabled="disabled"/>						
		</div>
		<div data-linea="12">	
			<label>Observación :</label> 					
		</div>
		<div data-linea="13">			   	
		    <textarea id="observacionFiscalizacion" name="observacionFiscalizacion" rows="3" cols="100" disabled="disabled"><?php echo $ResultadoFiscalizacionMovilizacion['observacion_fiscalizacion'];?></textarea>	    				
		</div>				
	</fieldset>   	
</div>
<div id='fiscalizacion_nuevo'>
	<fieldset>
		<legend>Nueva fiscalización</legend>
	    <div data-linea="10">			
			<label>Fecha fiscalización :</label>				
			<input type="text" id="fechaNuevaFiscalizacion" name="fechaNuevaFiscalizacion" />
		</div>		
	    <table>													
		<tr>
			<td>
				<label>Respuesta :</label>
			</td>									
			<td>
				<input type="radio" name="estadoNuevaFiscalizacion" value="positivo" id="estados1" >Positivo
				<input type="radio" name="estadoNuevaFiscalizacion" value="negativo"  id="estados2" >Negativo
			</td>		
		</tr>
	    </table>		
		<div data-linea="11">	
			<label>Observación :</label> 					
		</div>
		<div data-linea="12">			   	
		    <textarea id="observacionNuevaFiscalizacion" name="observacionNuevaFiscalizacion" placeholder="Ej: Observacion" rows="3" cols="100" maxlength="256"></textarea>		   	    			
		</div>				
	</fieldset>   	
		<button  type="submit" name="btn_guardar" >Guardar fiscalización</button>		
</div>	

<script type="text/javascript">
		
	var banderaFiscalizacionMovilizacion = <?php echo json_encode($banderaFiscalizacion); ?>;
	
	$(document).ready(function(){
		distribuirLineas();
	
		if(banderaFiscalizacionMovilizacion == 1){
			$("#fiscalizacion_vista").show();
			$("#fiscalizacion_nuevo").hide();			
		}else{
			$("#fiscalizacion_vista").hide();
			$("#fiscalizacion_nuevo").show();			
		}
			
		$("#fechaNuevaFiscalizacion").datepicker({
		      changeMonth: true,
		      changeYear: true
		});
				
	});

	$("#nuevoFiscalizacionMoviliacionAnimal").submit(function(event){

		event.preventDefault();
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;
		
		if($("input:radio[name=estadoNuevaFiscalizacion]:checked").val() == null){
			error = true;
			$("#estados1").addClass("alertaCombo");
			$("#estados2").addClass("alertaCombo");	
		}
		
		if($("#fechaNuevaFiscalizacion").val()==0){
			error = true;
			$("#fechaNuevaFiscalizacion").addClass("alertaCombo");
		}

		if($("#observacionNuevaFiscalizacion").val()==0){
			error = true;
			$("#observacionNuevaFiscalizacion").addClass("alertaCombo");
		}
				
		if (error){
			$("#estado").html("Por favor llene todos los campos.").addClass('alerta');	
		}else{             
			ejecutarJson($("#nuevoFiscalizacionMoviliacionAnimal"));   
		}

	});
	
</script>


