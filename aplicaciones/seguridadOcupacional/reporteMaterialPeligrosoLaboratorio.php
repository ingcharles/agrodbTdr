<?php 
session_start();
require_once '../../clases/Conexion.php';

require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorSeguridadOcupacional.php';

$conexion = new Conexion();

$cc = new ControladorCatalogos();
$so = new ControladorSeguridadOcupacional();
$laboratorios=$so->listaSubTipoLaboratoriosMaterialesPeligrosos($conexion);


?>
<header>
<h1>Reporte Material Peligroso Laboratorio</h1>
	<nav>
		<form id="filtrarMaterialPeligrosoLaboratorio" data-rutaAplicacion='seguridadOcupacional' action="aplicaciones/seguridadOcupacional/reporteImprimirMaterialPeligrosoLaboratorio.php" target="_self" method="post">
			<table class="filtro" style='width:100%;'>
				<tbody>
				
					<tr>
						<th colspan="4">Reporte material peligroso laboratorio</th>					
					</tr>
					
					<tr>
						<td style='text-align: left;'>Coordinación laboratorio:</td>
						<td colspan="3">
							<select id="coordinacionLaboratorio" name="coordinacionLaboratorio" style='width:99%;' >
								<option value="" >Seleccione...</option>
								<?php 
									$laboratorios = $cc->listaLaboratoriosMaterialesPeligrosos($conexion);
									while($fila=pg_fetch_assoc($laboratorios)){
										echo '<option value="' . $fila['id_laboratorio'] . '">' . $fila['nombre_laboratorio'] . '</option>';
									}
								?>
							</select> 			
						</td>
					</tr>					
					
					<tr>
						<td style='text-align: left;'>Nombre laboratorio:</td>
						<td>
							<select id="laboratorio" name="laboratorio" style="width:99%">
							<option value="">Seleccione...</option>
							</select> 				
						</td>
					</tr>
				
					<tr>
						<td style='text-align: left;'>Químico:</td>
						<td colspan="3">
							<select id="materialPeligroso" name="materialPeligroso" style='width:99%;' >
								<option value="" >Todos...</option>
								<?php 
									$materialPeligroso = $cc->listaMaterialesPeligrosos($conexion);
									while($fila=pg_fetch_assoc($materialPeligroso)){
										echo '<option value="' . $fila['id_material_peligroso'] . '">' . $fila['nombre_material_peligroso'] . '</option>';
									}
								?>
							</select> 			
						</td>
					</tr>
					
					<tr>
						<td colspan="4" style='text-align:center'><button type="submit" class="guardar" >Generar Reporte Excel</button></td>
					</tr>
					<tr>
						<td colspan="4" style='text-align:center' id="mensajeError"></td>
					</tr>
								
				</tbody>
			</table>
		</form>	
	</nav>
</header>
<script>

	$(document).ready(function(){
		distribuirLineas();
	});

	$("#coordinacionLaboratorio").change(function(){	
		
		if($("#coordinacionLaboratorio").val() != 0){
			
			sLaboratorio='';
			sLaboratorio = '<option value="">Seleccione...</option>';
			for(var i=0;i<array_laboratorio.length;i++){
				if ($("#coordinacionLaboratorio").val()==array_laboratorio[i]['idLaboratorioPadre'])	   
					sLaboratorio += '<option value="'+array_laboratorio[i]['idLaboratorio']+'"> '+ array_laboratorio[i]['nombreLaboratorio']+'</option>';
			}	   		    
			$('#laboratorio').html(sLaboratorio);
			$("#laboratorio").removeAttr("disabled");	  			
		}		 
	});


	
	$("#filtrarMaterialPeligrosoLaboratorio").submit(function(event){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#coordinacionLaboratorio").val())){
			error = true;
			$("#coordinacionLaboratorio").addClass("alertaCombo");
		}
		
		if(!$.trim($("#laboratorio").val()) ){
			error = true;
			$("#laboratorio").addClass("alertaCombo");
		}
		
		if (error){
			event.preventDefault();
			$("#mensajeError").html("Ingresar información en campos obligatorios.").addClass('alerta');
		}else{
			$("#mensajeError").html("");
			ejecutarJson(form);     
		}
		 	
	});
</script>