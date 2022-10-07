<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorRequisitos.php';
	require_once '../../clases/ControladorCatalogos.php';
	require_once '../../clases/ControladorUsuarios.php';
	
	$idRequisito = $_POST['id'];
	
	$conexion = new Conexion();
	$cr = new ControladorRequisitos();	
	$cc = new ControladorCatalogos();
	$cu = new ControladorUsuarios();
	
	$requisito = pg_fetch_assoc($cr->abrirRequisito($conexion, $idRequisito));
	
	
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
					$areaTematica.="IAPA,";
					$areaTematicaDescripcion.= "Registro de insumos para plantas de autoconsumo,";
				break;
			}
	
	
		}
	}
	
	if(!$banderaPerfil)
		$areaTematica="''";
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>
	<header>
		<h1>Detalle de requisito</h1>
	</header>
	<div id="estado"></div>
	
	<table class="soloImpresion">
		<tr>
			<td>
				<form id="actualizarRequisito" data-rutaAplicacion="administracionRequisitos" data-opcion="modificarRequisito" data-accionEnExito = 'ACTUALIZAR'>
					<input type="hidden" id="idRequisito" name="idRequisito" value="<?php echo $requisito['id_requisito'];?>">
					
					<div>
						<button id="modificar" type="button" class="editar">Modificar</button>
						<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
					</div>
					
					<fieldset>
						<legend>Requisitos</legend>
						
						<div data-linea="1">
							<label for="area">Área</label>
								<select id="area" name="area" disabled="disabled">
										<option value="">Seleccione....</option>
										<?php 
						if($areaTematica!="'"."'"){
							$areaTematicas = explode(",", rtrim ( $areaTematica,','));
							$areaTematicaDescripcion = explode(",", rtrim ( $areaTematicaDescripcion,','));
							for($i=0;$i<=count($areaTematicas)-1;$i++){
								echo 	'<option value="'.$areaTematicas[$i].'">'.$areaTematicaDescripcion[$i].'</option>';
							}
						}
						?>
										<!--  <option value="SA">Sanidad Animal</option>
										<option value="SV">Sanidad Vegetal</option>
										<option value="IAP">Inocuidad de los alimentos plaguicidas</option>
										<option value="IAV">Inocuidad de los alimentos veterinarios</option>
								-->
								</select>
						</div>
						
						<div data-linea="1">
							<label for="tipo">Tipo</label>
							<select id="tipo" name="tipo" disabled="disabled">
								<option value="">Seleccione....</option>
								<option value="Importación">Importación</option>
								<option value="Exportación">Exportación</option>
								<option value="Tránsito">Tránsito</option>
								<option value="Movilización">Movilización</option>
							</select>
						</div>
					
						<div data-linea="2">	
							<label>Nombre</label> 
								<input type="text" id="nombreRequisito" name="nombreRequisito" value="<?php echo $requisito['nombre'];?>" disabled="disabled"/>
						</div>
						
						<div data-linea="5">
							<label for="codigo">Código</label>
							<input type="text" name="codigo" id="codigo" value="<?php echo $requisito['codigo'];?>" disabled="disabled"/>
						</div>
						
						<div data-linea="5">
							<label for="documento">Documento</label>
							<input type="text" name="documento" id="documento" value="<?php echo $requisito['ruta_archivo'];?>" disabled="disabled"/>
						</div>
						
					</fieldset>
						
					<fieldset>
							<legend>Requisitos</legend>
				
							<div data-linea="4">	
									<textarea id="detalle" name="detalle"  placeholder="Ej: Requisito de comercialización..." rows="10" disabled="disabled"><?php echo $requisito['detalle'];?></textarea>
							</div>						
						
					</fieldset>
					
					<fieldset>
							<legend>Requisitos en impresión</legend>
				
							<div data-linea="4">	
									<textarea id="detalleImpresion" name="detalleImpresion"  placeholder="Ej: Requisito de comercialización..." rows="10" disabled="disabled"><?php echo $requisito['detalle_impreso'];?></textarea>
							</div>						
						
					</fieldset>
					
				</form>	
			</td>
		</tr>
	</table>
</body>
<script>
	$('document').ready(function(){
		//$('#listadoItems #<?php //echo $idPregunta?>').addClass("abierto");
		distribuirLineas();
		cargarValorDefecto("tipo","<?php echo $requisito['tipo'];?>");
		cargarValorDefecto("area","<?php echo $requisito['id_area'];?>");
	});

	$("#modificar").click(function(){
		$("input").removeAttr("disabled");
		$("textarea").removeAttr("disabled");
		$("select").removeAttr("disabled");
		$("#actualizar").removeAttr("disabled");
		$(this).attr("disabled","disabled");
	});

	/*$('#documento').change(function(event){
		if($("#nombreRequisito").val() != ""){
			subirArchivo('documento',$("#nombreRequisito").val().replace(/ /g,''),'aplicaciones/administracionRequisitos/requisito', 'archivo');
		}else{
			alert("Por favor ingrese el nombre del requisito para subir el documento.");
			$("#documento").val("");
		}
	});*/

	$("#actualizarRequisito").submit(function(event){
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
</html>