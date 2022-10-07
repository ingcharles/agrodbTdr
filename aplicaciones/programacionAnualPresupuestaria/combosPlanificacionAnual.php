<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAreas.php';
require_once '../../clases/ControladorUsuarios.php';
require_once '../../clases/ControladorProgramacionPresupuestaria.php';
require_once '../../clases/ControladorEstructuraFuncionarios.php';

$conexion = new Conexion();
$ca = new ControladorAreas();
$cu = new ControladorUsuarios();
$cpp = new ControladorProgramacionPresupuestaria();
$cef = new ControladorEstructuraFuncionarios();

$fecha = getdate();
$anio = $fecha['year'];

$opcion = htmlspecialchars ($_POST['opcion'],ENT_NOQUOTES,'UTF-8');

$idObjetivoEstrategico = htmlspecialchars ($_POST['idObjetivoEstrategico'],ENT_NOQUOTES,'UTF-8');
$idAreaN2 = htmlspecialchars ($_POST['areaN2'],ENT_NOQUOTES,'UTF-8');
$idObjetivoEspecifico = htmlspecialchars ($_POST['idObjetivoEspecifico'],ENT_NOQUOTES,'UTF-8');
$idAreaN4 = htmlspecialchars ($_POST['areaN4'],ENT_NOQUOTES,'UTF-8');
$idAreaN4FiltroRevision = htmlspecialchars ($_POST['areaN4FiltroRevision'],ENT_NOQUOTES,'UTF-8');
$idAreaN4Reporte = htmlspecialchars ($_POST['areaN4Reporte'],ENT_NOQUOTES,'UTF-8');
$idAreaN4ReportePresupuesto = htmlspecialchars ($_POST['areaN4ReportePresupuesto'],ENT_NOQUOTES,'UTF-8');
$idObjetivoOperativo = htmlspecialchars ($_POST['idObjetivoOperativo'],ENT_NOQUOTES,'UTF-8');
$idAreaGestion = htmlspecialchars ($_POST['gestion'],ENT_NOQUOTES,'UTF-8');
$idAreaGestionRevision = htmlspecialchars ($_POST['gestionFiltroRevision'],ENT_NOQUOTES,'UTF-8');
$idAreaGestionReporte = htmlspecialchars ($_POST['gestionReporte'],ENT_NOQUOTES,'UTF-8');
$tipo = htmlspecialchars ($_POST['tipo'],ENT_NOQUOTES,'UTF-8');
$tipoReporte = htmlspecialchars ($_POST['tipoReporte'],ENT_NOQUOTES,'UTF-8');
$idProcesoProyecto = htmlspecialchars ($_POST['procesoProyecto'],ENT_NOQUOTES,'UTF-8');
$idProcesoProyectoReporte = htmlspecialchars ($_POST['procesoProyectoReporte'],ENT_NOQUOTES,'UTF-8');
$idComponente = htmlspecialchars ($_POST['componente'],ENT_NOQUOTES,'UTF-8');
$idComponenteReporte = htmlspecialchars ($_POST['componenteReporte'],ENT_NOQUOTES,'UTF-8');
$idTipoCompra = htmlspecialchars ($_POST['tipoCompra'],ENT_NOQUOTES,'UTF-8');
//Reporte PAC
$idProgramaPAC = htmlspecialchars ($_POST['programasReporte'],ENT_NOQUOTES,'UTF-8');
$idProyectoPAC = htmlspecialchars ($_POST['proyectoReporte'],ENT_NOQUOTES,'UTF-8');

switch ($opcion){

	case 'objetivoEspecifico':
		$objetivoEspecifico = $cpp->listarObjetivoEspecificoXArea($conexion, $idObjetivoEstrategico, $idAreaN2, $anio);
	
		echo '<label>Objetivo Específico</label>
				<select id="objetivoEspecifico" name="objetivoEspecifico" required>
					<option value="">Seleccione....</option>';
					while ($fila = pg_fetch_assoc($objetivoEspecifico)){
						echo '<option value="'.$fila['id_objetivo_especifico'].'">'.$fila['nombre'].'</option>';
					}
		echo '</select>
			
			<input type="hidden" id="idObjetivoEspecifico" name="idObjetivoEspecifico" />
			<input type="hidden" id="nombreObjetivoEspecifico" name="nombreObjetivoEspecifico" />';
	break;
		
		
	case 'n4':	
		
		$areasN4 = $ca->buscarEstructuraPlantaCentralProvinciasXCategoria($conexion, '(4)');
		
		echo '<label id="lAreaN4">N4 - Dirección/Dirección Distrital</label>
				<select id="areaN4" name="areaN4" required>
					<option value="">Seleccione....</option>';
					while($fila = pg_fetch_assoc($areasN4)){
						if($fila['id_area_padre'] == $idAreaN2){
							echo '<option value="' . $fila['id_area'] . '">' . $fila['nombre'].' </option>';
						}
						if($fila['id_area'] == $idAreaN2){
							echo '<option value="' . $fila['id_area'] . '">' . $fila['nombre'].' </option>';
						}
					}
		echo '</select>
		
			<input type="hidden" id="nombreAreaN4" name="nombreAreaN4" />';
						
	break;	
		

	case 'objetivoOperativo':
		$objetivoOperativo = $cpp->listarObjetivoOperativoXArea($conexion, $idObjetivoEspecifico, $idAreaN4, $anio);
	
		echo '<label>Objetivo Operativo</label>
				<select id="objetivoOperativo" name="objetivoOperativo" required>
					<option value="">Seleccione....</option>';
					while ($fila = pg_fetch_assoc($objetivoOperativo)){
						echo '<option value="'.$fila['id_objetivo_operativo'].'">'.$fila['nombre'].'</option>';
					}
		echo '</select>
			
			<input type="hidden" id="idObjetivoOperativo" name="idObjetivoOperativo" />
			<input type="hidden" id="nombreObjetivoOperativo" name="nombreObjetivoOperativo" />';	
		
	break;
	
	case 'gestion':
	
		$areasGestion = $ca->buscarDivisionEstructura($conexion, $idAreaN4);
	
		echo '<label id="lAreaGestion">Gestión/Unidad</label>
				<select id="gestion" name="gestion" required>
					<option value="">Seleccione....</option>';
					while($fila = pg_fetch_assoc($areasGestion)){
						echo '<option value="' . $fila['id_area'] . '">' . $fila['nombre'].' </option>';
					}
		echo '</select>
	
			<input type="hidden" id="idGestion" name="idGestion" />
			<input type="hidden" id="nombreGestion" name="nombreGestion" />';
		
	break;
	
	
	case 'tipo':
		
		echo '<label id="lTipo">Tipo:</label>
				<select id=tipo name="tipo" required="required">
					<option value="">Seleccione....</option>
					<option value="Proceso">Proceso</option>
					<option value="Proyecto Gasto Corriente">Proyecto Gasto Corriente</option>
					<option value="Proyecto Inversion">Proyecto Inversion</option>
				</select>';
	
	break;
		
			
	case 'procesoProyecto':
		$procesoProyecto = $cpp->listarProcesoProyectoXGestionYTipo($conexion, $idObjetivoOperativo, $idAreaGestion, $tipo, $anio);
	
		echo '<label id="lProcesoProyecto">Proceso/Proyecto:</label>
				<select id="procesoProyecto" name="procesoProyecto" required>
					<option value="">Seleccione....</option>';
					while($fila = pg_fetch_assoc($procesoProyecto)){
						echo '<option value="' . $fila['id_proceso_proyecto'] . '" data-producto-final="' . $fila['producto_final'] . '">' . $fila['nombre'].' </option>';
					}
		echo '</select>
	
			<input type="hidden" id="idProcesoProyecto" name="idProcesoProyecto" />
			<input type="hidden" id="nombreProcesoProyecto" name="nombreProcesoProyecto" />';
	break;
	
	case 'componente':
		$componente = $cpp->listarComponente($conexion, $idProcesoProyecto, $anio);
	
		echo '<label id="lComponente">Componente:</label>
				<select id="componente" name="componente" required>
					<option value="">Seleccione....</option>';
					while($fila = pg_fetch_assoc($componente)){
						echo '<option value="' . $fila['id_componente'] . '" >' . $fila['nombre'].' </option>';
					}
		echo '</select>
			
			<input type="hidden" id="idComponente" name="idComponente" />
			<input type="hidden" id="nombreComponente" name="nombreComponente" />';

	break;
	
	case 'actividad':
		$actividad = $cpp->listarActividad($conexion, $idComponente, $anio);
	
		echo '<label id="lActividad">Actividad:</label>
		<select id="actividad" name="actividad" required>
		<option value="">Seleccione....</option>';
		while($fila = pg_fetch_assoc($actividad)){
			echo '<option value="' . $fila['id_actividad'] . '" >' . $fila['nombre'].' </option>';
		}
		echo '</select>
			
		<input type="hidden" id="idActividad" name="idActividad" />
		<input type="hidden" id="nombreActividad" name="nombreActividad" />';
	
		break;
	
	case 'responsable':
	    $responsable = $cef->obtenerUsuariosEstructuraXUnidadPrincial($conexion, $idAreaN2);
	
		echo '<label id="lResponsable">Responsable:</label>
				<select id="responsable" name="responsable" required>
					<option value="">Seleccione....</option>';
					while($fila = pg_fetch_assoc($responsable)){
						echo '<option value="' . $fila['identificador'] . '" >' . strtoupper($fila['apellido']) .' '. strtoupper($fila['nombre']).' </option>';
					}
				
		echo '</select>
			
			<input type="hidden" id="idResponsable" name="idResponsable" />
			<input type="hidden" id="nombreResponsable" name="nombreResponsable" />';
	
		break;
		
	case 'procedimientoSugerido':
		$procedimientoSugerido = $cpp->listarProcedimientoSugerido($conexion, $idTipoCompra);
		
		echo '<label id="lProcedimientoSugerido">Procedimiento Sugerido:</label>
				<select id="procedimientoSugerido" name="procedimientoSugerido" required>
					<option value="">Seleccione....</option>';
					while($fila = pg_fetch_assoc($procedimientoSugerido)){
						echo '<option value="' . $fila['id_procedimiento_sugerido'] . '" >' . $fila['nombre'].' </option>';
					}
		
		echo '</select>
				
			<input type="hidden" id="idProcedimientoSugerido" name="idProcedimientoSugerido" />
			<input type="hidden" id="nombreProcedimientoSugerido" name="nombreProcedimientoSugerido" />';
		
	break;
	
	//Revisión
	case 'n4FiltroRevision':
	
		$areasN4 = $ca->buscarEstructuraPlantaCentralProvinciasXCategoria($conexion, '(4)');
	
		echo '<label id="lAreaN4">N4 - Dirección/Dirección Distrital:</label>
				<select id="areaN4FiltroRevision" name="areaN4FiltroRevision" required>
					<option value="">Seleccione....</option>';
					while($fila = pg_fetch_assoc($areasN4)){
						if($fila['id_area_padre'] == $idAreaN2){
							echo '<option value="' . $fila['id_area'] . '">' . $fila['nombre'].' </option>';
						}
						if($fila['id_area'] == $idAreaN2){
							echo '<option value="' . $fila['id_area'] . '">' . $fila['nombre'].' </option>';
						}
					}
		echo '</select>
	
		<input type="hidden" id="nombreAreaN4" name="nombreAreaN4" />';
	
	break;

	case 'gestionFiltroRevision':
	
		$areasGestion = $ca->buscarDivisionEstructura($conexion, $idAreaN4FiltroRevision);
	
		echo '<label id="lAreaGestion">Gestión/Unidad:</label>
				<select id="gestionFiltroRevision" name="gestionFiltroRevision" required>
					<option value="">Seleccione....</option>';
					while($fila = pg_fetch_assoc($areasGestion)){
						echo '<option value="' . $fila['id_area'] . '">' . $fila['nombre'].' </option>';
					}
				echo '</select>
	
		<input type="hidden" id="idGestion" name="idGestion" />
		<input type="hidden" id="nombreGestion" name="nombreGestion" />';
	
		break;
	
	
	case 'tipoFiltroRevision':
	
		echo '<label id="lTipo">Tipo:</label>
				<select id=tipoFiltroRevision name="tipoFiltroRevision" required="required">
					<option value="">Seleccione....</option>
					<option value="Proceso">Proceso</option>
					<option value="Proyecto Gasto Corriente">Proyecto Gasto Corriente</option>
					<option value="Proyecto Inversion">Proyecto Inversion</option>
				</select>';
	
	break;
	
	//Reportes
	case 'objetivoEspecificoReporte':
		$objetivoEspecifico = $cpp->listarObjetivoEspecificoXArea($conexion, $idObjetivoEstrategico, $idAreaN2, $anio);
	
		echo '<select id="objetivoEspecificoReporte" name="objetivoEspecificoReporte" required>
				<option value="">Seleccione....</option>
				<option value="">Todos</option>';
				while ($fila = pg_fetch_assoc($objetivoEspecifico)){
					echo '<option value="'.$fila['id_objetivo_especifico'].'">'.$fila['nombre'].'</option>';
				}
		echo '</select>
			
				<input type="hidden" id="idObjetivoEspecifico" name="idObjetivoEspecifico" />
				<input type="hidden" id="nombreObjetivoEspecifico" name="nombreObjetivoEspecifico" />';
	break;
	
	case 'n4Reporte':
	
		$areasN4 = $ca->buscarEstructuraPlantaCentralProvinciasXCategoria($conexion, '(4)');
	
		echo '<select id="areaN4Reporte" name="areaN4Reporte" required>
				<option value="">Seleccione....</option>
				<option value="">Todos</option>';
				while($fila = pg_fetch_assoc($areasN4)){
					if($fila['id_area_padre'] == $idAreaN2){
						echo '<option value="' . $fila['id_area'] . '">' . $fila['nombre'].' </option>';
					}
					if($fila['id_area'] == $idAreaN2){
						echo '<option value="' . $fila['id_area'] . '">' . $fila['nombre'].' </option>';
					}
				}
		echo '</select>
			
			<input type="hidden" id="nombreAreaN4" name="nombreAreaN4" />';
	break;
	
	case 'objetivoOperativoReporte':
			$objetivoOperativo = $cpp->listarObjetivoOperativoXArea($conexion, $idObjetivoEspecifico, $idAreaN4Reporte, $anio);
		
			echo '<select id="objetivoOperativoReporte" name="objetivoOperativoReporte" required>
					<option value="">Seleccione....</option>
					<option value="">Todos</option>';
					while ($fila = pg_fetch_assoc($objetivoOperativo)){
						echo '<option value="'.$fila['id_objetivo_operativo'].'">'.$fila['nombre'].'</option>';
					}
			echo '</select>
				
				<input type="hidden" id="idObjetivoOperativo" name="idObjetivoOperativo" />
				<input type="hidden" id="nombreObjetivoOperativo" name="nombreObjetivoOperativo" />';
		
	break;
	
	case 'gestionReporte':
	
		$areasGestion = $ca->buscarDivisionEstructura($conexion, $idAreaN4Reporte);
	
		echo '<select id="gestionReporte" name="gestionReporte" required>
					<option value="">Seleccione....</option>
					<option value="">Todos</option>';
					while($fila = pg_fetch_assoc($areasGestion)){
						echo '<option value="' . $fila['id_area'] . '">' . $fila['nombre'].' </option>';
					}
		echo '</select>
	
				<input type="hidden" id="idGestion" name="idGestion" />
				<input type="hidden" id="nombreGestion" name="nombreGestion" />';
	
	break;
	
	case 'procesoProyectoReporte':
		$procesoProyecto = $cpp->listarProcesoProyectoXGestionYTipoReporte($conexion, $idAreaGestionReporte, $tipoReporte, $anio);
	
		echo '<select id="procesoProyectoReporte" name="procesoProyectoReporte" required>
				<option value="">Seleccione....</option>
				<option value="">Todos</option>';
				while($fila = pg_fetch_assoc($procesoProyecto)){
					echo '<option value="' . $fila['id_proceso_proyecto'] . '" data-producto-final="' . $fila['producto_final'] . '">' . $fila['nombre'].' </option>';
				}
		echo '</select>
	
				<input type="hidden" id="idProcesoProyecto" name="idProcesoProyecto" />
				<input type="hidden" id="nombreProcesoProyecto" name="nombreProcesoProyecto" />';
	break;

	case 'componenteReporte':
		$componente = $cpp->listarComponente($conexion, $idProcesoProyectoReporte, $anio);
	
		echo '<select id="componenteReporte" name="componenteReporte" required>
				<option value="">Seleccione....</option>
				<option value="">Todos</option>';
				while($fila = pg_fetch_assoc($componente)){
					echo '<option value="' . $fila['id_componente'] . '" >' . $fila['nombre'].' </option>';
				}
		echo '</select>
			
			<input type="hidden" id="idComponente" name="idComponente" />
			<input type="hidden" id="nombreComponente" name="nombreComponente" />';
	
	break;
		
	case 'actividadReporte':
		$actividad = $cpp->listarActividad($conexion, $idComponenteReporte, $anio);
	
		echo '<select id="actividad" name="actividad" required>
				<option value="">Seleccione....</option>
				<option value="">Todos</option>';
				while($fila = pg_fetch_assoc($actividad)){
					echo '<option value="' . $fila['id_actividad'] . '" >' . $fila['nombre'].' </option>';
				}
		echo '</select>
			
			<input type="hidden" id="idActividad" name="idActividad" />
			<input type="hidden" id="nombreActividad" name="nombreActividad" />';
	
	break;
		
	case 'n4ReportePresupuesto':
	
		$areasN4 = $ca->buscarEstructuraPlantaCentralProvinciasXCategoria($conexion, '(4)');
	
		echo '<select id="areaN4ReportePresupuesto" name="areaN4ReportePresupuesto" required>
				<option value="">Seleccione....</option>
				<option value="">Todos</option>';
				while($fila = pg_fetch_assoc($areasN4)){
					if($fila['id_area_padre'] == $idAreaN2){
						echo '<option value="' . $fila['id_area'] . '">' . $fila['nombre'].' </option>';
					}
					if($fila['id_area'] == $idAreaN2){
						echo '<option value="' . $fila['id_area'] . '">' . $fila['nombre'].' </option>';
					}
				}
		echo '</select>
			
				<input type="hidden" id="nombreAreaN4" name="nombreAreaN4" />';
	break;
	
	case 'gestionReportePresupuesto':
	
		$areasGestion = $ca->buscarDivisionEstructura($conexion, $idAreaN4ReportePresupuesto);
	
		echo '<select id="gestionReporte" name="gestionReporte" required>
				<option value="">Seleccione....</option>
				<option value="">Todos</option>';
				while($fila = pg_fetch_assoc($areasGestion)){
					echo '<option value="' . $fila['id_area'] . '">' . $fila['nombre'].' </option>';
				}
				echo '</select>
	
			<input type="hidden" id="idGestion" name="idGestion" />
			<input type="hidden" id="nombreGestion" name="nombreGestion" />';
	
		break;
		
	//PAC
		case 'proyectoReportePAC':
			$proyectoPAC = $cpp->listarCodigoProyecto($conexion, $idProgramaPAC);
		
			echo '<select id="proyectoReporte" name="proyectoReporte" required>
					<option value="">Seleccione....</option>
					<option value="">Todos</option>';
					while($fila = pg_fetch_assoc($proyectoPAC)){
						echo '<option value="' . $fila['id_codigo_proyecto'] . '" data-codigo-proyecto="' . $fila['codigo_proyecto'] . '">' . $fila['nombre'].' </option>';
					}
			echo '</select>
		
			<input type="hidden" id="idProyectoPAC" name="idProyectoPAC" />
			<input type="hidden" id="codigoProyectoPAC" name="codigoProyectoPAC" />
			<input type="hidden" id="nombreProyectoPAC" name="nombreProyecto" />';
		break;
		
		case 'actividadReportePAC':
			$actividadPAC = $cpp->listarCodigoActividad($conexion, $idProyectoPAC);
		
			echo '<select id="actividadReporte" name="actividadReporte" required>
				<option value="">Seleccione....</option>
				<option value="">Todos</option>';
				while($fila = pg_fetch_assoc($actividadPAC)){
					echo '<option value="' . $fila['id_codigo_actividad'] . '" data-codigo-actividad="' . $fila['codigo_actividad'] . '">' . $fila['nombre'].' </option>';
				}
			echo '</select>
		
			<input type="hidden" id="idActividadPAC" name="idActividadPAC" />
			<input type="hidden" id="codigoActividadPAC" name="codigoActividadPAC" />
			<input type="hidden" id="nombreActividadPAC" name="nombreActividadPAC" />';
		break;
		
	default:
		echo 'Tipo desconocido';
}


?>

<script type="text/javascript">

	$(document).ready(function(){
		distribuirLineas();	
	});	

	$("#objetivoEspecifico").change(function(event){
		$("#idObjetivoEspecifico").val($("#objetivoEspecifico option:selected").val());
		$("#nombreObjetivoEspecifico").val($("#objetivoEspecifico option:selected").text());

		$("#nuevaPlanificacionAnual").attr('data-opcion', 'combosPlanificacionAnual');
	    $("#nuevaPlanificacionAnual").attr('data-destino', 'dN4');
	    $("#opcion").val('n4');
	    
 	 	abrir($("#nuevaPlanificacionAnual"),event,false);
	 });

	$("#areaN4").change(function(event){
		$("#nombreAreaN4").val($("#areaN4 option:selected").text());

		$("#nuevaPlanificacionAnual").attr('data-opcion', 'combosPlanificacionAnual');
	    $("#nuevaPlanificacionAnual").attr('data-destino', 'dObjetivoOperativo');
	    $("#opcion").val('objetivoOperativo');
	    
 	 	abrir($("#nuevaPlanificacionAnual"),event,false);
	 });
	 
	$("#objetivoOperativo").change(function(event){
		$("#idObjetivoOperativo").val($("#objetivoOperativo option:selected").val());
		$("#nombreObjetivoOperativo").val($("#objetivoOperativo option:selected").text());

		$("#nuevaPlanificacionAnual").attr('data-opcion', 'combosPlanificacionAnual');
	    $("#nuevaPlanificacionAnual").attr('data-destino', 'dGestion');
	    $("#opcion").val('gestion');
	    
 	 	abrir($("#nuevaPlanificacionAnual"),event,false);
	 });

	$("#gestion").change(function(event){
		$("#idGestion").val($("#gestion option:selected").val());
		$("#nombreGestion").val($("#gestion option:selected").text());

		$("#nuevaPlanificacionAnual").attr('data-opcion', 'combosPlanificacionAnual');
	    $("#nuevaPlanificacionAnual").attr('data-destino', 'dTipo');
	    $("#opcion").val('tipo');
	    
 	 	abrir($("#nuevaPlanificacionAnual"),event,false);
	 });
	 
	$("#tipo").change(function(event){
		$("#nuevaPlanificacionAnual").attr('data-opcion', 'combosPlanificacionAnual');
	    $("#nuevaPlanificacionAnual").attr('data-destino', 'dProcesoProyecto');
	    $("#opcion").val('procesoProyecto');
	    
 	 	abrir($("#nuevaPlanificacionAnual"),event,false);
	 });

	$("#procesoProyecto").change(function(event){
		$("#lProductoFinal").show();
		$("#productoFinal").show();
		
		$("#idProcesoProyecto").val($("#procesoProyecto option:selected").val());
		$("#nombreProcesoProyecto").val($("#procesoProyecto option:selected").text());
		$("#productoFinal").val($("#procesoProyecto option:selected").attr('data-producto-final'));

		$("#nuevaPlanificacionAnual").attr('data-opcion', 'combosPlanificacionAnual');
	    $("#nuevaPlanificacionAnual").attr('data-destino', 'dComponente');
	    $("#opcion").val('componente');
	    
 	 	abrir($("#nuevaPlanificacionAnual"),event,false);
	 });

	$("#componente").change(function(event){
		$("#idComponente").val($("#componente option:selected").val());
		$("#nombreComponente").val($("#componente option:selected").text());

		$("#nuevaPlanificacionAnual").attr('data-opcion', 'combosPlanificacionAnual');
	    $("#nuevaPlanificacionAnual").attr('data-destino', 'dActividad');
	    $("#opcion").val('actividad');
	    
 	 	abrir($("#nuevaPlanificacionAnual"),event,false);
	 });

	$("#actividad").change(function(event){
		$("#lProvincia").show();
		$("#provincia").show();
		$("#lCantidadUsuarios").show();
		$("#cantidadUsuarios").show();
		$("#lPoblacionObjetivo").show();
		$("#poblacionObjetivo").show();
		$("#lMedioVerificacion").show();
		$("#medioVerificacion").show();
		
		$("#idActividad").val($("#actividad option:selected").val());
		$("#nombreActividad").val($("#actividad option:selected").text());

		$("#nuevaPlanificacionAnual").attr('data-opcion', 'combosPlanificacionAnual');
	    $("#nuevaPlanificacionAnual").attr('data-destino', 'dResponsable');
	    $("#opcion").val('responsable');
	    
 	 	abrir($("#nuevaPlanificacionAnual"),event,false);
	 });

	$("#responsable").change(function(event){
		$("#idResponsable").val($("#responsable option:selected").val());
		$("#nombreResponsable").val($("#responsable option:selected").text());
	 });

	$("#procedimientoSugerido").change(function(event){
		$("#idProcedimientoSugerido").val($("#procedimientoSugerido option:selected").val());
		$("#nombreProcedimientoSugerido").val($("#procedimientoSugerido option:selected").text());

		$("#nuevoPresupuesto").attr('data-opcion', 'guardarPresupuesto');
	    $("#nuevoPresupuesto").attr('data-destino', 'detalleItem');

	    $("#modificarPresupuesto").attr('data-opcion', 'modificarPresupuesto');
	    $("#modifcarPresupuesto").attr('data-destino', 'detalleItem');
	 });

	//Revision
	$("#areaN4FiltroRevision").change(function(event){
		$("#nombreAreaN4").val($("#areaN4FiltroRevision option:selected").text());

		$("#filtrarPlanificacionAnual").attr('data-opcion', 'combosPlanificacionAnual');
	    $("#filtrarPlanificacionAnual").attr('data-destino', 'dGestion');
	    $("#opcion").val('gestionFiltroRevision');
	    
 	 	abrir($("#filtrarPlanificacionAnual"),event,false);
	 });

	$("#gestionFiltroRevision").change(function(event){
		$("#idGestion").val($("#gestionFiltroRevision option:selected").val());
		$("#nombreGestion").val($("#gestionFiltroRevision option:selected").text());

		$("#filtrarPlanificacionAnual").attr('data-opcion', 'combosPlanificacionAnual');
	    $("#filtrarPlanificacionAnual").attr('data-destino', 'dTipo');
	    $("#opcion").val('tipoFiltroRevision');
	    
 	 	abrir($("#filtrarPlanificacionAnual"),event,false);
	 });
	 
	$("#tipoFiltroRevision").change(function(event){
		$("#filtrarPlanificacionAnual").attr('data-opcion', 'listaPlanificacionAnualFiltrada');
	    $("#filtrarPlanificacionAnual").attr('data-destino', 'contenedor');
	 });

	//Reporte
	$("#objetivoEspecificoReporte").change(function(event){
		$("#idObjetivoEspecifico").val($("#objetivoEspecificoReporte option:selected").val());
		$("#nombreObjetivoEspecifico").val($("#objetivoEspecificoReporte option:selected").text());

		$("#filtrar").attr('data-opcion', 'combosPlanificacionAnual');
	    $("#filtrar").attr('data-destino', 'dN4Reporte');
	    $("#opcion").val('n4Reporte');
	    
	    if($("#objetivoEspecificoReporte option:selected").val() != ''){
 	 		abrir($("#filtrar"),event,false);
	    }
	 });

	$("#areaN4Reporte").change(function(event){
		$("#nombreAreaN4").val($("#areaN4Reporte option:selected").text());

		$("#filtrar").attr('data-opcion', 'combosPlanificacionAnual');
	    $("#filtrar").attr('data-destino', 'dObjetivoOperativoReporte');
	    $("#opcion").val('objetivoOperativoReporte');
	    
	    if($("#areaN4Reporte option:selected").val() != ''){
 	 		abrir($("#filtrar"),event,false);
	    }
	 });

	$("#objetivoOperativoReporte").change(function(event){
		$("#idObjetivoOperativo").val($("#objetivoOperativoReporte option:selected").val());
		$("#nombreObjetivoOperativo").val($("#objetivoOperativoReporte option:selected").text());

		$("#filtrar").attr('data-opcion', 'combosPlanificacionAnual');
	    $("#filtrar").attr('data-destino', 'dGestionReporte');
	    $("#opcion").val('gestionReporte');
	    
	    if($("#gestionReporte option:selected").val() != ''){
 	 		abrir($("#filtrar"),event,false);
	    }
	 });

	$("#gestionReporte").change(function(event){
		$("#idGestion").val($("#gestionReporte option:selected").val());
		$("#nombreGestion").val($("#gestionReporte option:selected").text());

		$("#filtrar").attr('data-opcion', 'combosPlanificacionAnual');
	    $("#filtrar").attr('data-destino', 'dProcesoProyectoReporte');
	    $("#opcion").val('procesoProyectoReporte');
	    
	    if($("#gestionReporte option:selected").val() != ''){
 	 		abrir($("#filtrar"),event,false);
	    }
	 });

	$("#procesoProyectoReporte").change(function(event){
		$("#idProcesoProyecto").val($("#procesoProyectoReporte option:selected").val());
		$("#nombreProcesoProyecto").val($("#procesoProyectoReporte option:selected").text());

		$("#filtrar").attr('data-opcion', 'combosPlanificacionAnual');
	    $("#filtrar").attr('data-destino', 'dComponenteReporte');
	    $("#opcion").val('componenteReporte');
	    
	    if($("#procesoProyectoReporte option:selected").val() != ''){
 	 		abrir($("#filtrar"),event,false);
	    }
	 });

	$("#componenteReporte").change(function(event){
		$("#idComponente").val($("#componenteReporte option:selected").val());
		$("#nombreComponente").val($("#componenteReporte option:selected").text());

		$("#filtrar").attr('data-opcion', 'combosPlanificacionAnual');
	    $("#filtrar").attr('data-destino', 'dActividadReporte');
	    $("#opcion").val('actividadReporte');
	    
	    if($("#componenteReporte option:selected").val() != ''){
 	 		abrir($("#filtrar"),event,false);
	    }
	 });

	$("#actividadReporte").change(function(event){
		$("#idActividad").val($("#actividadReporte option:selected").val());
		$("#nombreActividad").val($("#actividadReporte option:selected").text());
	 });

	 //Presupuesto
	 $("#areaN4ReportePresupuesto").change(function(event){
		$("#nombreAreaN4").val($("#areaN4ReportePresupuesto option:selected").text());

		$("#filtrar").attr('data-opcion', 'combosPlanificacionAnual');
	    $("#filtrar").attr('data-destino', 'dGestionReporte');
	    $("#opcion").val('gestionReportePresupuesto');

	    if($("#areaN4ReportePresupuesto option:selected").val() != ''){
 	 		abrir($("#filtrar"),event,false);
	    }
	 });

	 //Reporte PAC
	 $("#proyectoReporte").change(function (event) {
		$("#idProyectoPAC").val($("#proyectoReporte option:selected").val());
		$("#codigoProyectoPAC").val($("#proyectoReporte option:selected").attr('data-codigo-proyecto'));
		$("#nombreProyectoPAC").val($("#proyectoReporte option:selected").text());

		$("#filtrar").attr('data-opcion', 'combosPlanificacionAnual');
	    $("#filtrar").attr('data-destino', 'dActividadReporte');
	    $("#opcion").val('actividadReportePAC');
	    		
		if($("#proyectoReporte option:selected").val() != ''){
			abrir($("#filtrar"), event, false); //Se ejecuta ajax
		}
	});

	 $("#actividadReporte").change(function (event) {
		$("#idActividadPAC").val($("#actividadReporte option:selected").val());
		$("#codigoActividadPAC").val($("#actividadReporte option:selected").attr('data-codigo-actividad'));
		$("#nombreActividadPAC").val($("#actividadReporte option:selected").text());
	});
</script>