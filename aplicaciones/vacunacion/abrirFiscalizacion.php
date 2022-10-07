<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacunacion.php';

$conexion = new Conexion();
$va = new ControladorVacunacion();

$idVacunacion=$_POST['id'];
$qVacunacion = $va->abrirVacunacion($conexion, $idVacunacion);
$filaVacuna = pg_fetch_assoc($qVacunacion);

$qDetalleVacunacion=$va->abrirDetalleVacunacion($conexion, $idVacunacion);
$controlFiscalizacion = 0;
	if(pg_num_rows($va->abrirFiscalizacion($conexion, $idVacunacion))!=0){
		$controlFiscalizacion = 1;
		$fiscalizacion = pg_fetch_assoc($va->abrirFiscalizacion($conexion, $idVacunacion));
	}
?>
<form id='guardarFiscalizacionVacunacion' data-rutaAplicacion='vacunacion' data-opcion='guardarNuevoFiscalizacion' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
	<div id="estado"></div>
		<input type="hidden" id="usuarioResponsable" name="usuarioResponsable" value="<?php echo $_SESSION['usuario'];?>">
		<input type="hidden" id="opcion" name="opcion" >
		<input type="hidden" name="idVacunacion" value="<?php echo $idVacunacion;?>" />	
	<div id='fiscalizacionRealizada'>
		<header>
			<h1>Registro fiscalización</h1>
		</header>	
		<fieldset>
			<legend>Información de la fiscalización </legend>		
			<div data-linea="10">
				<label>N° de fiscalización : </label>
				<input type="text" id="numeroFiscalizacionH" name="numeroFiscalizacionH" value="<?php echo 'N°'.str_pad($fiscalizacion['numero_fiscalizacion'], 9, "0", STR_PAD_LEFT );?>" disabled="disabled"/>			
			</div>
			<div data-linea="11">
				<label>Fecha fiscalización : </label>
				<input type="text" id="fechaFiscalizacionH" name="fechaFiscalizacionH" value="<?php echo $fiscalizacion['fecha_fiscalizacion'];?>" disabled="disabled"/>			
			</div>
			<div data-linea="11">
				<label>Respuesta :</label>
				<input type="text" id="respuestaFiscalizacion" name="respuestaFiscalizacion" value="<?php echo $fiscalizacion['estado'];?>" disabled="disabled"/>						
			</div>
			<div data-linea="12">
				<label>Identificador comerciante :</label>
				<input type="text" id="identificadorComercianteAbrir" name="identificadorComercianteAbrir" value="<?php echo $fiscalizacion['identificador_comerciante'];?>" disabled="disabled"/>						
			</div>
			<div data-linea="13">	
				<label>Observación :</label> 					
			</div>
			<div data-linea="14">			   	
			    <textarea id="observacionFiscalizacion" name="observacionFiscalizacion" rows="3" cols="100" disabled="disabled"><?php echo $fiscalizacion['observacion'];?></textarea>	    				
			</div>				
		</fieldset>   	
	</div>
	<div id='fiscalizacionNuevo'>
		<header>
			<h1>Nuevo registro fiscalización</h1>
		</header>
		<fieldset>
			<legend>Nueva fiscalización</legend>
		    <div data-linea="1">			
				<label>Fecha fiscalización: </label>				
				<input type="text" id="fechaFiscalizacion" name="fechaFiscalizacion" readonly />
			</div>					
		 	<div data-linea="2">
				<label>Fiscalización a comerciante: </label>
					<input type="checkbox" name="comerciante" id="comerciante" value="comerciante" >
		 	</div>
		 	<div data-linea="2">
				<label>Identificador comerciante :</label>
				<input type="text" id="identificadorComerciante" name="identificadorComerciante" disabled="disabled"/>						
			</div>			
			<div data-linea="3" id="resultadoOperador">
			</div>
			<div data-linea="4">
				<label>Respuesta: </label>
					<input type="radio" name="estadoFiscalizacion" value="positivo" id="estadoPositivo" >Positivo
					<input type="radio" name="estadoFiscalizacion" value="negativo"  id="estadoNegativo" >Negativo
		 	</div>
			<div data-linea="5">	
				<label>Observación :</label> 					
			</div>
			<div data-linea="6">			   	
			    <textarea id="observacion" name="observacion" placeholder="Ej: Observacion" rows="3" cols="100" maxlength="256"></textarea>		   	    			
			</div>	
		</fieldset> 
		<p data-linea="7" style="text-align: center">
			<button id="guardarFiscalizacion" type="submit" name="guardarFiscalizacion" >Guardar fiscalización</button>	
		</p>			
	</div>
	<div>
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
				<label>Identificación Operador: </label> 
				<input type="text" id="identificacionOperador" name="identificacionOperador" value="<?php echo $filaVacuna['identificador_operador'];?>" disabled="disabled"/>
			</div> 
			<div data-linea="2">
				<label>Nombre Operador: </label> 
				<input type="text" id="nombreOperador" name="nombreOperador" value="<?php echo $filaVacuna['nombre_operador'];?>" disabled="disabled"/>
			</div>
			<div data-linea="3">
				<label>Nombre del Sitio: </label> 
				<input type="text" id="nombreSitio" name="nombreSitio" value="<?php echo $filaVacuna['nombre_sitio'];?>" disabled="disabled"/>
			</div> 
			<div data-linea="3">
				<label>Operador Vacunación: </label> 
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
				<label>Tipo Vacuna: </label>
					<input type="text" id="tipoVacuna" name="tipoVacuna" value="<?php echo $filaVacuna['nombre_vacuna'];?>" disabled="disabled"/>
			</div>
			<div data-linea="5">
				<label>Laboratorio: </label>
					<input type="text" id="laboratorio" name="laboratorio" value="<?php echo $filaVacuna['nombre_laboratorio'];?>" disabled="disabled"/>
			</div>
			<div data-linea="6">
				<label>Lote Vacuna: </label>
					<input type="text" id="loteVacuna" name="loteVacuna" value="<?php echo $filaVacuna['numero_lote'];?>" disabled="disabled"/>
			</div>		
			<div data-linea="6">
			    <label>Fecha de Vacunación: </label> 
				<input type="text" id="fechaVacunacion" name="fechaVacunacion" value="<?php echo date("d/m/Y", strtotime($filaVacuna['fecha_vacunacion'])) ;?>" disabled="disabled" readonly />
			</div>
			<div data-linea="7">
				<label>Fecha de Vencimiento: </label> 
				<input type="text" id="fechaVencimiento" name="fechaVencimiento" value="<?php echo $filaVacuna['fecha_vencimiento'];?>" disabled="disabled"/>			
			</div>			
			<div data-linea="7">
				<label>Fecha de Registro: </label> 
				<input type="text" id="fechaRegistro" name="fechaRegistro" value="<?php echo $filaVacuna['fecha_registro'];?>" disabled="disabled"/>			
			</div>
			<div data-linea="8">
			    <label>Cantidad Vacunada: </label> 
				<input type="text" id="totalVacuna" name="totalVacuna" value="<?php echo $filaVacuna['total_vacunado'];?>" disabled="disabled"/>
			</div>	
			<div data-linea="8">
			    <label>Costo de Vacuna: </label> 
				<input type="text" id="costoVacuna" name="costoVacuna" value="<?php echo $filaVacuna['costo_vacuna'];?>" disabled="disabled"/>
			</div>							
			<div data-linea="9">
			    <label>Total Costo Vacuna: </label> 
				<input type="text" id="totalVacuna" name="totalVacuna" value="<?php echo $filaVacuna['costo_total_vacuna'];?>" disabled="disabled"/>
			</div>	
			<div data-linea="9">
			    <label>Estado: </label> 
				<input type="text" id="estadoCertificado" name="estadoCertificado" value="<?php echo $filaVacuna['estado'];?>" disabled="disabled"/>
			</div>						
		</fieldset>
		<fieldset>
			<legend>Detalle de Productos Vacunados</legend>
			<table id="tablaVacunaAnimal">
				<thead>
					<tr>
						<th>Operación</th>
						<th>Área</th>
						<th>Producto</th>
						<th>Cantidad</th>									
						<th title="Número de lote">No.Lote</th>
						<th title="Unidad comercial">U.Comercial</th>
						<th>Identificador Producto</th>
											
					</tr>
				</thead>
				<tbody>
						<?php 
						$i=1;
						$qDetalleVacunacionD=$va->abrirDetalleVacunacion($conexion, $idVacunacion);
						while($filaDetalleVacuna=pg_fetch_assoc($qDetalleVacunacionD)){
							echo  '<tr>
									   <td>'.$filaDetalleVacuna['tipo_operacion'].' </td>
									   <td>'.$filaDetalleVacuna['area'].' </td>
									   <td>'.$filaDetalleVacuna['producto_subtipo'].'-'.$filaDetalleVacuna['producto'].' </td>
									   <td>'.$filaDetalleVacuna['cantidad'].'</td>
									   <td>'.$filaDetalleVacuna['numero_lote'].'</td>
									   <td>'.$filaDetalleVacuna['unidad_comercial'].'</td><td>';
							$qDetalleVacunacionIdentificadores=$va->abrirDetalleVacunacionIdentificadores($conexion, $filaDetalleVacuna['id_detalle_vacunacion']);
							while($filaDetalleVacunaIdentificadores=pg_fetch_assoc($qDetalleVacunacionIdentificadores)){
								echo $filaDetalleVacunaIdentificadores['identificador'].' <br>';
							}
							echo '</td> </tr>';				
							$i++;
						}	
					    ?>
				</tbody>				
			</table>	
		</fieldset>
	</div>	
</form>		

<script type="text/javascript">
	var controlFiscalizacion = <?php echo json_encode($controlFiscalizacion); ?>;
	$(document).ready(function(){
		distribuirLineas();

		if(controlFiscalizacion == 1){
			$("#fiscalizacionRealizada").show();
			$("#fiscalizacionNuevo").hide();			
		}else{
			$("#fiscalizacionRealizada").hide();
			$("#fiscalizacionNuevo").show();			
		}

		$("#fechaFiscalizacion").datepicker({
		      changeMonth: true,
		      changeYear: true,
		      maxDate: "0"
		});
	});

	$("#comerciante").change(function(event){  	
    	if($("#comerciante").prop('checked')) {       		
    		$("#identificadorComerciante").prop("disabled", false);	 
    		$("#resultadoOperador").show;		
    	}else{    
    		$("#identificadorComerciante").prop("disabled", true);
    		$("#identificadorComerciante").val("");
    		$("#resultadoOperador").hide();
        }
	});

	$("#identificadorComerciante").change(function(event){
    	if($("#identificadorComerciante").val()!= ""){
    		event.preventDefault();	
    		event.stopImmediatePropagation();	
        	$('#guardarFiscalizacionVacunacion').attr('data-destino','resultadoOperador');
        	$('#guardarFiscalizacionVacunacion').attr('data-opcion','accionesVacunacion');
    	    $('#opcion').val('buscarOperador');
    	    abrir($("#guardarFiscalizacionVacunacion"),event,false);
    	    $('#guardarFiscalizacionVacunacion').attr('data-opcion','guardarNuevoFiscalizacion');
        }
	});
	
	$("#guardarFiscalizacionVacunacion").submit(function(event){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;
		event.preventDefault();

		if($("#observacion").val()==""){
			error = true;
			$("#observacion").addClass("alertaCombo");
			$("#estado").html('Por favor ingrese la observación.').addClass("alerta");
		}

		if($("input:radio[name=estadoFiscalizacion]:checked").val() == null){
			error = true;
			$("#estadoPositivo").addClass("alertaCombo");
			$("#estadoNegativo").addClass("alertaCombo");
			$("#estado").html('Por favor seleccione la respuesta.').addClass("alerta");
		}

		if($("#fechaFiscalizacion").val()==""){
			error = true;
			$("#fechaFiscalizacion").addClass("alertaCombo");
			$("#estado").html('Por favor seleccione la fecha de fiscalización.').addClass("alerta");
		}

		if($("#validadorComerciante").val()=="incorrecto"){
			error = true;
			$("#identificadorComerciante").addClass("alertaCombo");
			$("#estado").html('Por favor seleccione un comerciante registrado.').addClass("alerta");
		}

		if($("#comerciante").prop('checked')) {
			if($("#identificadorComerciante").val()==""){
				error = true;
				$("#identificadorComerciante").addClass("alertaCombo");
				$("#estado").html('Por favor seleccione un comerciante.').addClass("alerta");
			}
		}
		
		if (!error){
			ejecutarJson($(this));	
		}	
	});
</script>