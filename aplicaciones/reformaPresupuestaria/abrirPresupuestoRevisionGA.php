<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorAreas.php';
	require_once '../../clases/ControladorReformaPresupuestaria.php';
	
	$fecha = getdate();
	$anio = $fecha['year'];
	
	$identificador=$_SESSION['usuario'];
	
	if($identificador==''){
		$usuario=0;
	}else{
		$usuario=1;
		$idAreaFuncionario = $_SESSION['idArea'];
		$nombreProvinciaFuncionario = $_SESSION['nombreProvincia'];
	}//$usuario=0;
	
	$conexion = new Conexion();
	$ca = new ControladorAreas();
	$crp = new ControladorReformaPresupuestaria();
	
	$idPlanificacionAnual = $_POST['idPlanificacionAnual'];
	$idPresupuesto = $_POST['idPresupuesto'];
	
	$presupuesto = pg_fetch_assoc($crp->abrirPresupuestoTemporal($conexion, $idPresupuesto));
	
	$estadoPresupuesto = $presupuesto['estado'];
	
	if($presupuesto['revisado'] == true){
		$estadoRevision =1;
	}else{
		if($presupuesto['estado'] == 'enviadoRevisorDGPGE'){
			$estadoRevision =1;
		}else if($presupuesto['estado'] == 'enviadoRevisorGA'){
			$estadoRevision =0;
		}else if($presupuesto['estado'] == 'enviadoRevisorGF'){
			$estadoRevision =1;
		}else{
			$estadoRevision = 0;
		}
	}
?>

	<header>
		<h1>Presupuesto Reformado</h1>
	</header>

	<div id="estado"></div>
	
	<form id="regresar" data-rutaAplicacion="reformaPresupuestaria" data-opcion="abrirReformaPlanificacionAnualRevisionGA" data-destino="detalleItem">
		<input type="hidden" name="id" value="<?php echo $idPlanificacionAnual;?>"/>
		<button class="regresar">Regresar a Planificación Anual</button>
	</form>
	
	<table class="soloImpresion">
		<tr>
			<td>
				<fieldset>
					<legend>Presupuestos Reformados</legend>
					
					<div data-linea="1">
						<label>Ejercicio:</label><?php echo $presupuesto['ejercicio'];?>
					</div>
					
					<div data-linea="1">
						<label>Entidad:</label><?php echo $presupuesto['entidad'];?>
					</div>
					
					<div data-linea="2">
						<label>Unidad Ejecutora:</label><?php echo $presupuesto['unidad_ejecutora'];?>
					</div>
					
					<div data-linea="2">
						<label>Unidad Desconcentrada:</label><?php echo $presupuesto['unidad_desconcentrada'];?>
					</div>
					
					<div data-linea="3">
						<label>Programa:</label> <?php echo $presupuesto['programa'];?>
					</div>
					
					<div data-linea="3">
						<label>Subprograma:</label> <?php echo $presupuesto['subprograma'];?>
					</div>
					
					<div data-linea="4">
						<label>Proyecto:</label> <?php echo $presupuesto['codigo_proyecto'];?>
					</div>
					
					<div data-linea="4">
						<label>Actividad:</label> <?php echo $presupuesto['codigo_actividad'];?>
					</div>
					
					<div data-linea="5">
						<label>Obra:</label> <?php echo $presupuesto['obra'];?>
					</div>
					
					<div data-linea="5">
						<label>Geográfico:</label> <?php echo $presupuesto['geografico'];?>
					</div>
					
					<div data-linea="6">
						<label>Renglón:</label><?php echo $presupuesto['renglon'];?>
					</div>
					
					<div data-linea="6">
						<label>Renglón Auxiliar:</label> <?php echo $presupuesto['renglon_auxiliar'];?>
						<input type='hidden' id='renglonAuxiliar' name='renglonAuxiliar' value="<?php echo $presupuesto['renglon_auxiliar'];?>"/>
					</div>
			
					<div data-linea="7">
						<label>Fuente:</label> <?php echo $presupuesto['fuente'];?>
						<input type='hidden' id='fuente' name='fuente' value="<?php echo $presupuesto['fuente'];?>"/>
					</div>
					
					<div data-linea="7">
						<label>Organismo:</label> <?php echo $presupuesto['organismo'];?>
						<input type='hidden' id='organismo' name='organismo' value="<?php echo $presupuesto['organismo'];?>"/>
					</div>
					
					<div data-linea="8">
						<label>Correlativo:</label> <?php echo $presupuesto['correlativo'];?>
						<input type='hidden' id='correlativo' name='correlativo' value="<?php echo $presupuesto['correlativo'];?>"/>
					</div>
					
					<div data-linea="8">
						<label>CPC:</label><?php echo $presupuesto['cpc'];?>
					</div>
					
					<div data-linea="9">
						<label>Tipo de Compra:</label><?php echo $presupuesto['tipo_compra'];?>
					</div>
					
					<div data-linea="9">
						<label>Procedimiento Sugerido:</label><?php echo $presupuesto['procedimiento_sugerido'];?>
					</div>
					
					<div data-linea="10">
						<label>Detalle Actividad:</label> <?php echo $presupuesto['nombre_actividad'];?>
					</div>
					
					<div data-linea="11">
						<label>Detalle del Gasto:</label><?php echo $presupuesto['detalle_gasto'];?>
					</div>
					
					<div data-linea="12">
						<label>Cantidad Anual:</label><?php echo $presupuesto['cantidad_anual'];?>
					</div>
					
					<div data-linea="12">
						<label>Unidad de Medida:</label><?php echo $presupuesto['unidad_medida'];?>
					</div>
					
					<div data-linea="13">
						<label>Costo (sin IVA):</label><?php echo $presupuesto['costo'];?>
					</div>
					
					<div data-linea="13">
						<label>IVA:</label><?php echo $presupuesto['iva'];?>
					</div>
					
					<div data-linea="23">
						<label>Costo (con IVA):</label><?php echo $presupuesto['costo_iva'];?>
					</div>
					
					<div data-linea="23">
						<label>Cuatrimestre:</label><?php echo $presupuesto['cuatrimestre'];?>
					</div>
					
					<div data-linea="14">
						<label>Tipo de Producto:</label><?php echo $presupuesto['tipo_producto'];?>
					</div>
					
					<div data-linea="14">
						<label>Catálogo Electrónico:</label><?php echo $presupuesto['catalogo_electronico'];?>
					</div>
					
					<div data-linea="15">
						<label>Fondos BID:</label><?php echo $presupuesto['fondos_bid'];?>
					</div>
					
					<div data-linea="15">
						<label>Operación BID:</label><?php echo $presupuesto['operacion_bid'];?>
					</div>
					
					<div data-linea="16">
						<label>Proyecto BID:</label><?php echo $presupuesto['proyecto_bid'];?>
					</div>
					
					<div data-linea="16">
						<label>Tipo de Régimen:</label><?php echo $presupuesto['tipo_regimen'];?>
					</div>
					
					<div data-linea="17">
						<label>Presupuesto:</label><?php echo $presupuesto['tipo_presupuesto'];?>
					</div>
					
					<div data-linea="17">
						<label>Agregar al Pac:</label><?php echo $presupuesto['agregar_pac'];?>
					</div>
					
					<div data-linea="18" id="observacionRevisionPr">
						<label>Observaciones del Revisor:</label> <?php echo $presupuesto['observaciones_revision'];?>
					</div>
					
					<div data-linea="19" id="observacionAprobacionPr">
						<label>Observaciones del Aprobador:</label> <?php echo $presupuesto['observaciones_aprobacion'];?>
					</div>
											
				</fieldset>
				
				<div id="revision">
					<form id="revisarPresupuesto" data-rutaAplicacion="reformaPresupuestaria" data-opcion="revisarPresupuestoGA" data-destino="detalleItem">
						<input type='hidden' id='identificador' name='identificador' value="<?php echo $presupuesto['identificador'];?>" />
						<input type='hidden' id='identificadorRevisor' name='identificadorRevisor' value="<?php echo $identificador;?>" />
						<input type='hidden' id='idPlanificacionAnual' name='idPlanificacionAnual' value="<?php echo $idPlanificacionAnual;?>" />
						<input type='hidden' id='idPresupuesto' name='idPresupuesto' value="<?php echo $idPresupuesto;?>" />
						<input type='hidden' id='detalleGasto' name='detalleGasto' value = "<?php echo $presupuesto['detalle_gasto'];?>"/>
						<input type='hidden' id='cantidadAnual' name='cantidadAnual' value="<?php echo $presupuesto['cantidad_anual']; ?>"/>
						<input type='hidden' id='idUnidadMedida' name='idUnidadMedida' value="<?php echo $presupuesto['id_unidad_medida']; ?>"/>
						<input type='hidden' id='nombreUnidadMedida' name='nombreUnidadMedida' value="<?php echo $presupuesto['unidad_medida']; ?>" />
						<input type='hidden' id='costo' name='costo' value="<?php echo $presupuesto['costo']; ?>" />
						<input type='hidden' id='iva' name='iva' value="<?php echo $presupuesto['iva']; ?>"/>
						<input type='hidden' id='costoIva' name='costoIva' value="<?php echo $presupuesto['costo_iva']; ?>"/>
						<input type='hidden' id='idCuatrimestre' name='idCuatrimestre' value="<?php echo $presupuesto['cuatrimestre']; ?>"/>
						<input type='hidden' id='nombreCuatrimestre' name='nombreCuatrimestre' value="<?php echo $presupuesto['cuatrimestre']; ?>" />
						
						<fieldset>
							<legend>Revisión</legend>
							
							<div data-linea="30">
								<label>Estado:</label>
								<select id=estadoRevision name="estadoRevision" required="required">
									<option value="">Seleccione....</option>
									<option value="revisadoGA">Revisado</option>
									<option value="rechazado">Rechazado</option>
								</select>
							</div>
							
							<div data-linea="31">
								<label>Observaciones:</label>
								<input type="text" id="observaciones" name="observaciones" maxlength="1024" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"/>
							</div>
							
						</fieldset>
					
						<div data-linea="32">
							<button id="actualizar" type="submit" class="guardar" >Actualizar</button>
						</div>
					</form>
				
				</div>
			</td>
		</tr>
	</table>
	
<script type="text/javascript">
var estadoPresupuesto = <?php echo json_encode($estadoPresupuesto); ?>;
var estadoRevision = <?php echo json_encode($estadoRevision); ?>;
								
	$('document').ready(function(){
		acciones("#nuevaActividad","#detalleActividad");
		distribuirLineas();

		if(estadoRevision == 1){
			$("#revision").hide();
		}else{
			$("#revision").show();
		}
	});

	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}
	
	$("#revisarPresupuesto").submit(function(event){
		
		event.preventDefault();

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#estadoRevision").val())){
			error = true;
			$("#estadoRevision").addClass("alertaCombo");
		}

		if(!$.trim($("#observaciones").val()) || !esCampoValido("#observaciones")){
			error = true;
			$("#observaciones").addClass("alertaCombo");
		}
		
		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			ejecutarJson($(this));
		}
	});
</script>