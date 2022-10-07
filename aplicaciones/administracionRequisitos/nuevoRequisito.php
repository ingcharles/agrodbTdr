<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorRequisitos.php';
	require_once '../../clases/ControladorCatalogos.php';
	require_once '../../clases/ControladorUsuarios.php';
	
	$conexion = new Conexion();
	$cr = new ControladorRequisitos();	
	$cu = new ControladorUsuarios();
	
	
	$arrayPerfil=array('PFL_SANID_ANIMA','PFL_SANID_VEGET','PFL_LABORATORIO','PFL_INOCU_ALIME','PFL_INSUM_PLAGU','PFL_INSUM_VETER','PFL_INSUM_PRO_AU');
	$banderaPerfil=false;
	foreach ($arrayPerfil as $codificacionPerfil ){
	
		$qVerificarUsuario=$cu->verificarUsuario($conexion, $_SESSION['usuario'],$codificacionPerfil);
		if(pg_num_rows($qVerificarUsuario)>0){
			$banderaPerfil=true;
			switch ($codificacionPerfil){
				case 'PFL_SANID_ANIMA':
					$areaTematica.= "SA,";
					$areaTematicaDescripcion.= "Sanidad Animal,";
				break;
	
				case 'PFL_SANID_VEGET':
					$areaTematica.="SV,";
					$areaTematicaDescripcion.= "Sanidad Vegetal,";
				break;
	
				case 'PFL_LABORATORIO':
					$areaTematica.="LT,";
					$areaTematicaDescripcion.= "Laboratorios,";
				break;
					
				case 'PFL_INOCU_ALIME':
					$areaTematica.="AI,";
					$areaTematicaDescripcion.= "Inocuidad de alimentos,";
				break;
	
				case 'PFL_INSUM_PLAGU':
					$areaTematica.="IAP,";
					$areaTematicaDescripcion.= "Registro de insumos agrícolas,";
					
					break;
	
				case 'PFL_INSUM_VETER':
					$areaTematica.="IAV,";
					$areaTematicaDescripcion.= "Registro de insumos pecuarios,";
					break;
					
				case 'PFL_INSUM_FERTIL':
						$areaTematica.="IAF,";
						$areaTematicaDescripcion.= "Registro de insumos fertilizantes,";
					break;

				case 'PFL_INSUM_PRO_AU':
					$areaTematica.="'IAPA',";
					$areaTematicaDescripcion.= "Registro de insumos para plantas de autoconsumo,";
				break;
			}
				
				
		}
	}
	
	if(!$banderaPerfil)
		$areaTematica="";
	
	//echo $areaTematica.'!='."'"."'";
	
	
?>


	<header>
		<h1>Nuevo requisito</h1>
	</header>

	<div id="estado"></div>
		<form id="nuevoRequisito" data-rutaAplicacion="administracionRequisitos" data-opcion="guardarNuevoRequisito" data-accionEnExito = 'ACTUALIZAR'>
					
			<fieldset>
				<legend>Requisitos</legend>	
				
				<div data-linea="1">
					<label for="area">Área</label>
						<select id="area" name="area">
						<option value="" selected="selected">Seleccione una dirección...</option>
						<?php 
						if($areaTematica!=''){
							$areaTematicas = explode(",", rtrim ( $areaTematica,','));
							$areaTematicaDescripcion = explode(",", rtrim ( $areaTematicaDescripcion,','));
							for($i=0;$i<=count($areaTematicas)-1;$i++){
								echo 	'<option value="'.$areaTematicas[$i].'">'.$areaTematicaDescripcion[$i].'</option>';
							}
						}
						?>
						</select>
				</div>
				
				<div data-linea="1">
					<label for="tipo">Tipo</label>
					<select id="tipo" name="tipo">
						<option value="">Seleccione....</option>
						<option value="Importación">Importación</option>
						<option value="Exportación">Exportación</option>
						<option value="Tránsito">Tránsito</option>
						<option value="Movilización">Movilización</option>
					</select>
				</div>
				
				<div data-linea="2">	
					<label>Nombre</label> 
						<input type="text" id="nombreRequisito" name="nombreRequisito" placeholder="Ej: Requisito" />
				</div>
				
				<div data-linea="7">
					<label>Código</label>
					<input type="text" name="codigo" id="codigo"/>
				</div>
				
				<div data-linea="7">
					<label>Documento asosciado</label>
					<input type="text" name="documento" id="documento"/>
				</div>
				
				</fieldset>
				
				<fieldset>
					<legend>Detalle</legend>
				
				
				<div data-linea="4">	
						<textarea id="detalle" name="detalle" placeholder="Ej: Requisito de comercialización..." rows="10"></textarea>
				</div>
				
				</fieldset>
				
				<fieldset>
					<legend>Detalle en impresión</legend>
				
				<div data-linea="6">	
						<textarea  id="detalleImpresion" name="detalleImpresion" placeholder="Ej: Requisito de comercialización..." rows="10"></textarea>
				</div>
				
			</fieldset>
			
			<div>
				<button type="submit" class="guardar">Guardar</button>
			</div>
			
		</form>

<script>
	$('document').ready(function(){
		acciones();
		distribuirLineas();
	});
	
	$("#nuevoRequisito").submit(function(event){

		event.preventDefault();

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if($("#tipo").val()==""){
			error = true;
			$("#tipo").addClass("alertaCombo");
		}

		if($("#area").val()==""){
			error = true;
			$("#area").addClass("alertaCombo");
		}

		/*if($.trim($("#detalleImpresion").val())==""){
			error = true;
			$("#detalleImpresion").addClass("alertaCombo");
		}*/

		if($.trim($("#nombreRequisito").val())==""){
			error = true;
			$("#nombreRequisito").addClass("alertaCombo");
		}

		if (!error){
			ejecutarJson($(this));
		}else{
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
		
		
	});
</script>
