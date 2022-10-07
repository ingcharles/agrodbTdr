<?php
session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorCatalogos.php';
	require_once '../../clases/ControladorUsuarios.php';
	require_once '../../clases/ControladorEventoSanitario.php';
	
	$conexion = new Conexion();
	$cc = new ControladorCatalogos();
	$cu = new ControladorUsuarios();
	$cpco = new ControladorEventoSanitario();
	$listaCatalogos = new ControladorEventoSanitario();
	
	$ruta = 'seguimientoEventosSanitarios';
	
	$idEventoSanitario = $_POST['id'];
	
	$eventoSanitario = pg_fetch_assoc($cpco->abrirEventoSanitario($conexion, $idEventoSanitario));
	
	/*grid*/
	$tipoExplotacionConsulta  = $cpco->listarTiposExplotaciones($conexion, $idEventoSanitario);
	$tipoExplotacionAvesConsulta  = $cpco->listarTiposExplotacionesAves($conexion, $idEventoSanitario);
	$cronologiaConsulta  = $cpco->listarCronologias($conexion, $idEventoSanitario);
	$especieAnimalAfectadaConsulta  = $cpco->listarEspecieAnimalAfactada($conexion, $idEventoSanitario);
	$vacunacionAftosaConsulta  = $cpco->listarVacunacionAftosa($conexion, $idEventoSanitario);
	$vacunacionAnimalConsulta  = $cpco->listarVacunaciones($conexion, $idEventoSanitario);
	$vacunacionAvesConsulta  = $cpco->listarVacunacionesAves($conexion, $idEventoSanitario);
	$procedimientoAvesConsulta  = $cpco->listarProcedimientosAves($conexion, $idEventoSanitario);
	
	//Primera Visita
	$numMuestraPrimeraVisita = pg_num_rows($cpco->listarMuestrasPorVisita($conexion, $idEventoSanitario, 'Visita 0001'));
	$origenProbable = pg_fetch_assoc($cpco->abrirMedidaSanitaria($conexion, $idEventoSanitario, 'Visita 0001'));
	$laboratorios = $cpco->abrirCatalogoLaboratorios($conexion);
	$laboratorios1 = $cpco->abrirCatalogoLaboratorios($conexion);
	
	if($numMuestraPrimeraVisita != 0){
		$muestraPrimeraVisita = pg_fetch_assoc($cpco->listarMuestrasPorVisita($conexion, $idEventoSanitario, 'Visita 0001'));
	}
	
	/*grid*/
	$muestraConsulta  = $cpco->listarMuestrasDetalle($conexion, $idEventoSanitario);
	$origenAnimalConsulta  = $cpco->listarOrigenes($conexion, $idEventoSanitario);
	$poblacionesConsulta  = $cpco->listarPoblaciones($conexion, $idEventoSanitario);
	$poblacionesAvesConsulta  = $cpco->listarPoblacionesAves($conexion, $idEventoSanitario);
	$ingresosConsulta  = $cpco->listarIngresos($conexion, $idEventoSanitario);
	$egresosConsulta = $cpco->listarEgresos($conexion, $idEventoSanitario);
	$movimientosAvesConsulta  = $cpco->listarMovimientosAves($conexion, $idEventoSanitario);
	$origenesMedidasConsulta = $cpco->listarOrigenMedida($conexion, $idEventoSanitario);
	
	//Laboratorio
	$resultadoLaboratorio = $cpco->abrirResultadoLaboratorio($conexion, $idEventoSanitario);
	
	//Visita Cierre
	$cronologiasFinal  = $cpco->listarCronologiasFinales($conexion, $idEventoSanitario);
	$diagnosticosConsulta  = $cpco->listarDiagnosticos($conexion, $idEventoSanitario);
	$poblacionesFinalesConsulta   = $cpco->listarPoblacionesFinales($conexion, $idEventoSanitario);
	$poblacionesFinalesAvesConsulta  = $cpco->listarPoblacionesFinalesAves($conexion, $idEventoSanitario);
	$vacunacionFinalesConsulta = $cpco->listarVacunacionFinales($conexion, $idEventoSanitario);

?>

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
		<link href="estilos/estiloapp.css" rel="stylesheet"></link>
	</head>
	
	<body>
		<!-- Informacion general -->
<div class="pestania">
	<h2>Información General</h2>
	
	<div id="informacion">
		<fieldset>
			<legend>Información General</legend>

			<div data-linea="1">
				<label id="lNumero">Número:</label>
				<?php echo $eventoSanitario['numero_formulario'];?> 
			</div>
		
			<div data-linea="1">
				<label id="lFecha">Fecha:</label>
				<?php echo $eventoSanitario['fecha'];?> 
			</div>
		
			<div data-linea="2">
				<label id="lOrigenNotificacion">Origen de la Notificación:</label>
				<?php echo $eventoSanitario['nombre_origen'];?> 
			</div>
		
			<div data-linea="3">
				<label id="lCanalNotificacion">Canal de la Notificación:</label>
				<?php echo $eventoSanitario['nombre_canal'];?> 
			</div>
		</fieldset>

		<fieldset>
			<legend>Información de la finca</legend>
		
			<div data-linea="4">
				<label id="lNombre">Nombre del propietario:</label>
				<?php echo $eventoSanitario['nombre_propietario'];?> 
			</div>
				
			<div data-linea="5">
				<label id="lCedula">Número de Cedula:</label>
				<?php echo $eventoSanitario['cedula_propietario'];?> 
			</div>
			
			<div data-linea="5">
				<label id="lTelefono">Teléfono:</label>
				<?php echo $eventoSanitario['telefono_propietario'];?> 
			</div>
		
			<div data-linea="6">
				<label id="lCelular">Celular:</label>
				<?php echo $eventoSanitario['celular_propietario'];?> 
			</div>
		
			<div data-linea="6">
				<label id="lCorreoElectronico">Correo Electrónico:</label>
				<?php echo $eventoSanitario['correo_electronico_propietario'];?> 
			</div>
			
			<div data-linea="7">
				<label id="lNombrePredio">Nombre del Predio:</label>
				<?php echo $eventoSanitario['nombre_predio'];?> 
			</div>
		
			<div data-linea="8">
				<label id="lExtencionPredio">Extención del Predio:</label>
				<?php echo $eventoSanitario['extencion_predio'];?> 
			</div>
		
			<div data-linea="9">
				<label id="lUnidadMedida">Unidad Medida:</label>
				<?php echo $eventoSanitario['medida'];?> 
			</div>
		
			<div data-linea="10">
				<label id = "lOtroPredio">Tiene otro predio:</label>
				<?php echo $eventoSanitario['otros_predios'];?> 
			</div>
		
			<div data-linea="10">
				<label id = "lNumeroPredios">Número  de Predios:</label>
				<?php echo $eventoSanitario['numero_predios'];?>
			</div>
		
			<div data-linea="11">
				<label id = "lBioseguridad">Tiene medidas de Bioseguridad:</label>
				<?php echo $eventoSanitario['bioseg'];?>
			</div>
		</fieldset>

		<fieldset>
			<legend>Ubicación del Predio</legend>

			<div data-linea="12">
				<label id="lProvincia">Provincia</label>
				<?php echo $eventoSanitario['provincia'];?> 
			</div>
			
			<div data-linea="12">
				<label id="lCanton">Cantón</label>
				<?php echo $eventoSanitario['canton'];?> 
			</div>
			
			<div data-linea="13">	
				<label id="lParroquia">Parroquia</label>
				<?php echo $eventoSanitario['parroquia'];?> 
			</div>
				
			<div data-linea="13">
				<label id="lOficina">Oficina:</label>
				<?php echo $eventoSanitario['oficina'];?> 
			</div>
			
			<div data-linea="14">
				<label id="lSitio">Sitio:</label>
				<?php echo $eventoSanitario['sitio_predio'];?> 
			</div>
			
			<div data-linea="14">
				<label id="lSemana">Semana:</label>
				<?php echo $eventoSanitario['semana'];?> 
			</div>
			
		</fieldset>
		
		<fieldset>
			<legend>Coordenadas</legend>
			<div data-linea="15">
				<label id="lUtm_x">UTM X:</label>
				<?php echo $eventoSanitario['utm_x'];?> 
			</div>
			
			<div data-linea="15">
				<label id="lUtm_y">UTM Y:</label>
				<?php echo $eventoSanitario['utm_y'];?> 
			</div>
			
			<div data-linea="15">
				<label id="lUtm_z">UTM Z:</label>
				<?php echo $eventoSanitario['utm_z'];?> 
			</div>
			
			<div data-linea="15">
				<label id="lZona">Huso/Zona:</label>
				<?php echo $eventoSanitario['huso_zona'];?> 
			</div>			
		</fieldset>
		
	</div>

	<fieldset id="adjuntosInforme">
			<legend>Mapa</legend>
			<div data-linea="32">
				<label>Mapa:</label>
				<?php echo ($eventoSanitario['ruta_mapa']==''? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$eventoSanitario['ruta_mapa'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Mapa cargado</a>')?>
			</div>
	</fieldset>
</div>

<div class="pestania">
	<h2>Identificación de la explotación</h2>
	
	<fieldset id="detalleExplotacionConsultaFS">
			<legend>Explotaciones registradas</legend>
			<table id="detalleExplotacion">
				<thead>
					<tr>
						<th width="15%">Especie</th>
						<th width="15%">Tipo explotación</th>
					</tr>
				</thead>
				<?php 
					while ($tipoExplotacion = pg_fetch_assoc($tipoExplotacionConsulta)){
						echo $cpco->imprimirLineaTipoExplotacionConsulta(	$tipoExplotacion['id_explotacion_registrada'], 
																			$tipoExplotacion['id_evento_sanitario'],
																			$tipoExplotacion['especie'], 
																			$tipoExplotacion['tipo_explotacion'],  
																			$ruta);
					}
				?>
			</table>
		</fieldset>
</div>


<!--Notificacion y cronologia -->
<div class="pestania">
	<h2>Cronología </h2>
	
	<fieldset id="detalleCronologiaConsultaFS">
		<legend>Cronología</legend>
		<table id="detalleCronologia">
			<thead>
				<tr>
					<th width="15%">Tipo</th>
					<th width="15%">Fecha</th>
				</tr>
			</thead>
			<?php 
				while ($cronologiaGC = pg_fetch_assoc($cronologiaConsulta)){
					echo $cpco->imprimirLineaCronologiaConsulta(	$cronologiaGC['id_cronologia'], 
																	$cronologiaGC['id_evento_sanitario'],
																	$cronologiaGC['nombre_tipo_cronologia'], 
																	$cronologiaGC['fecha_cronologia'], 
																	$cronologiaGC['hora_cronologia'], 
																	$ruta);
				}
			?>
		</table>
	</fieldset>
</div>

<!--Explotaciones -->
<div class="pestania">
	<h2>Especie animal afectada</h2>

		<fieldset id="detalleEspecieAfectadaConsultaFS">
		<legend>Especie Afectada</legend>
		<table id="detalleEspecieAfectada">
			<thead>
				<tr>
					<th width="15%">Especie</th>
					<th width="15%">Especificación</th>
				</tr>
			</thead>
			<?php 
				while ($especie = pg_fetch_assoc($especieAnimalAfectadaConsulta)){
					echo $cpco->imprimirLineaEspecieAfectadaConsulta(	$especie['id_especie_afectada'], 
																		$especie['id_evento_sanitario'],
																		$especie['nombre_especie_afectada'], 
																		$especie['especificacion_especie_afectada'], 
																		$ruta);
				}
			?>
		</table>
	</fieldset>
</div>

<!--Vacunacion -->
<div class="pestania">
	<h2>Vacunación</h2>
	
	<fieldset id="detalleVacunacionAftosaConsultaFS">
		<legend>Vacunación</legend>
		<table id="detalleVacunacionAftosa">
			<thead>
				<tr>
					<th width="15%">Tipo Vacunación</th>
					<th width="15%">Enfermedad</th>
					<th width="15%">Fecha</th>
					<th width="15%">Lote</th>
					<th width="15%">Número certificado</th>
					<th width="15%">Laboratorio</th>
					<th width="15%">Observaciones</th>
				</tr>
			</thead>
			<?php 
				while ($vacunacion = pg_fetch_assoc($vacunacionAftosaConsulta)){
					echo $cpco->imprimirLineaVacunacionAftosaConsulta(	$vacunacion['id_vacunacion_aftosa'], 
																		$vacunacion['id_evento_sanitario'],
																		$vacunacion['nombre_tipo_vacunacion_aftosa'], 
																		$vacunacion['fecha_vacunacion_aftosa'], 
																		$vacunacion['lote_vacunacion_aftosa'], 
																		$vacunacion['numero_certificado_vacunacion_aftosa'], 
																		$vacunacion['nombre_laboratorio_vacunacion_aftosa'], 
																		$ruta,
																		$vacunacion['enfermedad'],
																		$vacunacion['observaciones']);
				}
			?>
		</table>
	</fieldset>
</div>

<div class="pestania">

<h2>Origen de los animales enfermos</h2>

	<fieldset id="detalleOrigenConsultaFS">
		<legend>Origen Animales</legend>
		<table id="detalleOrigenAnimales">
			<thead>
				<tr>
					<th width="15%">Visita</th>
					<th width="15%">Origen de los animales enfermos</th>
					<th width="15%">País</th>
					<th width="15%">Provincia</th>
					<th width="15%">Cantón</th>
					<th width="15%">Fecha</th>
				</tr>
			</thead>
			<?php 
				while ($origen = pg_fetch_assoc($origenAnimalConsulta)){
					echo $cpco->imprimirLineaOrigenConsulta(	$origen['id_origen_animales'],
																$origen['id_evento_sanitario'],
																$origen['nombre_origen'], 
																$origen['nombre_pais'], 
																$origen['nombre_provincia'],
																$origen['canton'],
																$origen['fecha_origen'],  
																$ruta,
																$origen['numero_visita']);
				}
			?>
		</table>
	</fieldset>
</div>

<!--Procedimiento terapeutico para aves, sintomatologia, lesiones, 1er animal enfermo, sindrome -->
<div class="pestania">
	
	<h2>Sintomatología, lesiones, 1er animal enfermo, síndrome</h2>
	
		<fieldset>
			<legend>Sintomatología</legend>
			<div data-linea="1">
				<label>Sintomatología:</label>
				<?php echo $eventoSanitario['sintomatologia'];?>
			</div>
		</fieldset>
		
		<fieldset>
			<legend>Leciones en la necropsia</legend>
			<div data-linea="2">
				<label>Leciones en la necropsia:</label>
				<?php echo $eventoSanitario['leciones_necropsia'];?>
			</div>
		</fieldset>
		
		<fieldset>
			<legend>1er Animal enfermo</legend>
			<div data-linea="3">
				<label>Especie:</label>
				<?php echo $eventoSanitario['nombre_especie_primer_animal'];?>
			</div>
			
			<div data-linea="4">
				<label>Edad en meses:</label>
				<?php echo $eventoSanitario['edad_primer_animal'];?>
			</div>
			
			<div data-linea="6">
				<label>Ingresado?:</label>
					<?php echo $eventoSanitario['ingresado_primer_animal'];?>
			</div>
		</fieldset>
		
		<fieldset>
			<legend>Sindrome presuntivo</legend>
			<div data-linea="7">
				<label>Sindrome presuntivo:</label>
				<?php echo $eventoSanitario['sindrome_presuntivo'];?>
			</div>
		</fieldset>
</div>


<!-- PRIMERA VISITA -->

<!--Colecta de material - Visitas - población-->
<div class="pestania">
	<h2>Colecta de material</h2>
	
	<fieldset id="detalleMuestraConsultaFS">
		<legend>información muestras</legend>
		<table>
			<thead>
				<tr>
					<th width="15%">Num Visita</th>
					<th width="15%">Especie</th>
					<th width="15%">Prueba Laboratorio Solicitada</th>
					<th width="15%">Tipo muestra</th>
					<th width="15%">Número de muestras</th>
					<th width="15%">Fecha colecta muestra</th>
					<th width="15%">Fecha envio muestra</th>
				</tr>
			</thead>
			<?php 
				while ($muestraGC = pg_fetch_assoc($muestraConsulta)){
					echo $cpco->imprimirLineaMuestraConsulta(	$muestraGC['id_detalle_muestra'],
																$muestraGC['id_muestra'], 
																$muestraGC['id_evento_sanitario'],
																$muestraGC['especie_muestra'], 
																$muestraGC['prueba_muestra'],  
																$muestraGC['tipo_muestra'], 
																$muestraGC['numero_muestras'], 
																$muestraGC['fecha_colecta_muestra'], 
																$muestraGC['fecha_envio_muestra'],
																$ruta,
																$muestraGC['numero_visita']);
				}
			?>
		</table>
	</fieldset>
</div>
<div class="pestania">

	<h2>Población animal existente</h2>
	
	<fieldset id="detallePoblacionConsultaFS">
		<legend>Población animal, existente, enferma y muerta</legend>
		<table id="detallePoblacion">
			<thead>
				<tr>
					<th width="15%">Visita</th>
					<th width="15%">Especie</th>
					<th width="15%">Categoria</th>
					<th width="15%">Existentes</th>
					<th width="15%">Enfermos</th>
					<th width="15%">Muertos</th>
					<th width="15%">Sacrificados</th>
					<th width="15%">Enfermos sin vacunar</th>
					<th width="15%">Total sin vacunar</th>					
				</tr>
			</thead>
			<?php 
				while ($poblacion = pg_fetch_assoc($poblacionesConsulta)){
					echo $cpco->imprimirLineaPoblacionConsulta(	$poblacion['id_poblacion_animales'], 
																$poblacion['id_evento_sanitario'],
																$poblacion['numero_visita'], 
																$poblacion['nombre_especie_poblacion'], 
																$poblacion['tipo_especie_poblacion'],
																$poblacion['existentes'], 
																$poblacion['enfermos'],														
																$poblacion['muertos'], 
																$poblacion['sacrificados'],		
																$poblacion['total_sin_vacunar'],														
																$poblacion['enfermos_sin_vacunas'],														
																$ruta);
				}
			?>
		</table>
	</fieldset>

	
		<fieldset>
			<legend>Predios vecinos afectados</legend>
			
			<div data-linea="1">
				<label>Existen predios vecinos afectados:</label>
					<?php echo $eventoSanitario['otros_predios_afectados']; ?>
			</div>
			
			<div data-linea="8">
				<label>Cuántos:</label>
				<?php echo ($eventoSanitario['otros_predios_afectados']=='Si'?$eventoSanitario['numero_predios']:0) ; ?>
			</div>
			
		
		</fieldset>
	
</div>

<!--movimiento de animales -->
<div class="pestania">
	<h2>Movimiento de animales</h2>

		<fieldset>
			<legend>Movimiento</legend>
		
			<div data-linea="1">
				<label>Hubo ingreso de animales y/o vehiculizantes de enfermedad en 30 días antes del inicio:</label>
			<?php echo ($eventoSanitario['movimiento_animal']==1?'Ingresos':($eventoSanitario['movimiento_animal']==2?'Egresos':($eventoSanitario['movimiento_animal']==3?'Ingresos y Egresos':('No Movilizados')))); ?>
					
			</div>
		
		</fieldset>
				
		<fieldset id="detalleIngresosConsultaFS">
			<legend>información Ingresos de animales</legend>
			<table id="detalleIngresos">
				<thead>
					<tr>
						<th width="15%">Visita</th>
						<th width="15%">Provincia</th>
						<th width="15%">Cantón</th>
						<th width="15%">Parroquia</th>
						<th width="15%">Especie</th>
						<th width="15%">Propietario</th>
						<th width="15%">Finca - feria, etc.</th>
						<th width="15%">Fecha</th>
						<th width="15%">Num Animales Ingreso</th>
					</tr>
				</thead>
				
				<?php 
					while ($ingreso = pg_fetch_assoc($ingresosConsulta)){
						echo $cpco->imprimirLineaIngresosConsulta(	$ingreso['id_ingreso'], 
																	$ingreso['id_evento_sanitario'],
																	$ingreso['numero_visita'],
																	$ingreso['nombre_provincia'], 
																	$ingreso['nombre_canton'], 
																	$ingreso['nombre_parroquia'], 
																	$ingreso['nombre_especie'], 
																	$ingreso['propietario_movimiento'], 
																	$ingreso['finca_movimiento'], 
																	$ingreso['fecha_movimiento'], 
																	$ruta,
																	$ingreso['numero_animales']);
					}
				?>
			</table>
		</fieldset>

				
		<fieldset id="detalleEgresosConsultaFS">
			<legend>información Egresos de animales</legend>
			<table id="detalleEgresos">
				<thead>
					<tr>
						<th width="15%">Visita</th>
						<th width="15%">Provincia</th>
						<th width="15%">Cantón</th>
						<th width="15%">Parroquia</th>
						<th width="15%">Especie</th>
						<th width="15%">Propietario</th>
						<th width="15%">Finca - feria, etc.</th>
						<th width="15%">Fecha</th>
						<th width="15%">Num Animales Egreso</th>
					</tr>
				</thead>
				<?php 
					while ($egreso = pg_fetch_assoc($egresosConsulta)){
						echo $cpco->imprimirLineaEgresosConsulta(	$egreso['id_egreso'], 
																	$egreso['id_evento_sanitario'],
																	$egreso['numero_visita'],
																	$egreso['nombre_provincia'], 
																	$egreso['nombre_canton'], 
																	$egreso['nombre_parroquia'], 
																	$egreso['nombre_especie'], 
																	$egreso['propietario_movimiento'], 
																	$egreso['finca_movimiento'], 
																	$egreso['fecha_movimiento'], 
																	$ruta,
																	$egreso['numero_animales']);
					}
				?>
			</table>
		</fieldset>
		
</div>

<!--Origenes, Medidas, fotografias, mapa, observaciones -->
<div class="pestania">
	<h2>Orígenes, Medidas, fotografías, mapa, observaciones</h2>
	
	<fieldset id="detalleOrigenesMedidasConsultaFS">
		<legend>Origen problable de la enfermedad</legend>
		<table id="detalleOrigenesMedidas">
			<thead>
				<tr>
					<th width="15%">Visita</th>
					<th width="15%">Origen probable enfermedad</th>
					<th width="15%">Cuarentena predio</th>
					<th width="15%">Num. Acta</th>
					<th width="15%">Medidas sanitarias implementadas</th>
					<th width="15%">Observaciones</th>
					<th width="15%">Acta inicio cuarentena, mapa, fotos</th>
				</tr>
			</thead>
			<?php 
				while ($origenMedida = pg_fetch_assoc($origenesMedidasConsulta)){
					echo $cpco->imprimirLineaOrigenMedidaConsulta	(	$origenMedida['id_medida_sanitaria'], 
																		$origenMedida['numero_visita'],
																		$origenMedida['origen_enfermedad'],
																		$origenMedida['cuarentena_predio'],
																		$origenMedida['numero_acta'], 
																		$origenMedida['medidas_sanitarias'], 
																		$origenMedida['observaciones'], 
																		$origenMedida['ruta_mapa_medidas'], 
																		$origenMedida['ruta_fotos'], 
																		$ruta);
				}
			?>
		</table>
	</fieldset>
						
</div>

<!-- Laboratorio -->

<div class="pestania">

	<h2>Resultados de Pruebas de Laboratorio</h2>
	
	<?php 
		if(pg_num_rows($resultadoLaboratorio) > 0){	
			while($resultado = pg_fetch_assoc($resultadoLaboratorio)){
				echo "
				<fieldset>
					<legend>Resultado del Proceso ".$resultado['num_inspeccion'] ."</legend>
					
					<div data-linea='53'>
						<label>Resultado: </label>". $resultado['resultado_analisis']."				 					
					</div>
					
					<div data-linea='54'>
						<label>Observaciones: </label>". $resultado['observaciones']."
					</div>
						
					<div data-linea='12'>
						<label>Informe de Laboratorio: </label>";
						echo ($resultado['archivo_informe']==''? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$resultado['archivo_informe'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Informe cargado</a>');
					echo "</div>";
				
				$resultadoLaboratorioDetalle = $cpco->abrirResultadoLaboratorioDetalle($conexion, $idEventoSanitario, $resultado['id_resultado_laboratorio']);
				
				echo "	<table id='detallePruebaLaboratorio'>
							<thead id='barraTitulo'>
								<tr id='titulo'>
								    <th width='15%'># Muestras</th>
									<th width='15%'># Positivos</th>
								    <th width='15%'>Fecha Informe</th>
								    <th width='15%'>Enfermedad</th>
								    <th width='15%'>Prueba de Laboratorio</th>
									<th width='15%'>Resultado Análisis</th>
								</tr>
							</thead>";
						
				while ($resultadoDetalle = pg_fetch_assoc($resultadoLaboratorioDetalle)){
					echo "<tr>
							<td width='30%'>" .
							$resultadoDetalle['cantidad_muestras'].
							"</td>
							<td width='30%'>" .
							$resultadoDetalle['num_positivos'].
							"</td>
							<td width='30%'>" .
							$resultadoDetalle['fecha_muestra'].
							"</td>
							<td width='30%'>" .
							$resultadoDetalle['enfermedad'].
							"</td>
							<td width='30%'>" .
							$resultadoDetalle['prueba_laboratorio'].
							"</td>
							<td width='30%'>" .
							$resultadoDetalle['resultado'].
							"</td>
							</tr>";
				}
							
				echo "		</table>
				</fieldset>";
			}
		}else{
			echo "<fieldset>
					<legend>Resultado del Proceso ".$numVisita ."</legend>
						<label>No se disponen de resultados de laboratorio para esta visita.</label>
					</fieldset>";
		}
	?>
</div>

<div class="pestania">
<h2>Datos Finales</h2>
	
	<fieldset id="detalleCronologiaFinalConsultaFS">
		<legend>Cronología</legend>
		<table id="detalleCronologiaFinal">
			<thead>
				<tr>
					<th width="15%">Tipo</th>
					<th width="15%">Fecha</th>
				</tr>
			</thead>
			<?php 
				while ($cronologia = pg_fetch_assoc($cronologiasFinal)){
					echo $cpco->imprimirLineaCronologiaFinalConsulta(	$cronologia['id_cronologia_final'], 
																		$cronologia['id_evento_sanitario'],
																		$cronologia['nombre_tipo_cronologia_final'], 
																		$cronologia['fecha_cronologia_final'], 
																		$ruta);
				}
			?>
		</table>
	</fieldset>
	
	<fieldset id="detalleDiagnosticoFinalConsultaFS">
		<legend>Diagnostico Definitivo</legend>
		<table id="detalleDiagnosticoFinal">
			<thead>
				<tr>
					<th width="15%">Diagnóstico</th>
					<th width="15%">Enfermedad</th>
					<th width="15%">Descripción</th>
				</tr>
			</thead>
			<?php 
				while ($diagnostico = pg_fetch_assoc($diagnosticosConsulta)){
					echo $cpco->imprimirLineaDiagnosticoFinalConsulta(	$diagnostico['id_diagnosticos_final'], 
																		$diagnostico['id_evento_sanitario'],
																		$diagnostico['nombre_diagnostico_final'],
																		$diagnostico['enfermedad'],
																		$diagnostico['descricion_diagnostico_final'], 
																		$ruta);
				}
			?>
		</table>
	</fieldset>
</div>


<div class="pestania">
<h2>Datos Finales</h2>

	<fieldset id="detallePoblacionFinalConsultaFS">
		<legend>Población animal existente, enferma  al cierre del episodio</legend>
		<table id="detallePoblacionFinal">
			<thead>
				<tr>
					<th width="15%">Especie</th>
					<th width="15%">Categoria</th>
					<th width="15%">Existentes</th>
					<th width="15%">Enfermos</th>
					<th width="15%">Muertos</th>
					<th width="15%">Sacrificados</th>
					<th width="15%">Matados y eliminados</th>
				</tr>
			</thead>
			<?php 
				while ($poblacion = pg_fetch_assoc($poblacionesFinalesConsulta)){
					echo $cpco->imprimirLineaPoblacionFinalConsulta(	$poblacion['id_poblacion_final'], 
																		$poblacion['id_evento_sanitario'],
																		$poblacion['nombre_especie_poblacion_final'], 
																		$poblacion['nombre_categoria_poblacion_final'], 
																		$poblacion['existentes_poblacion_final'], 
																		$poblacion['enfermos_poblacion_final'], 
																		$poblacion['muertos_poblacion_final'], 
																		$poblacion['sacrificados_poblacion_final'], 
																		$poblacion['matados_eliminados_poblacion_final'], 
																		$ruta);
				}
			?>
		</table>
	</fieldset>
</div>

<div class="pestania">
	<h2>Datos Finales</h2>
	
		<fieldset>
			<legend>Vacunación</legend>
			
				<div data-linea="6">
					<label>Se vacuno al cierre:</label>
						<?php echo $eventoSanitario['vacunacion_final']; ?>
				</div>
		</fieldset>
	
		<fieldset id="detalleVacunacionFinalConsultaFS">
			<legend>Población animal existente, enferma  al cierre del episodio</legend>
			<table id="detalleVacunacionFinal">
				<thead>
					<tr>
						<th width="15%">Tipo</th>
						<th width="15%">Dosis Aplicadas</th>
						<th width="15%">Predios vacunados</th>
						<th width="15%">Laboratorios</th>
						<th width="15%">Lote</th>
					</tr>
				</thead>
				<?php 
					while ($vacuna = pg_fetch_assoc($vacunacionFinalesConsulta)){
						echo $cpco->imprimirLineaVacunacionFinalConsulta(	$vacuna['id_vacunacion_final'], 
																			$vacuna['id_evento_sanitario'],
																			$vacuna['nombre_tipo_vacunacion_final'], 
																			$vacuna['dosis_aplicada_vacunacion_final'], 
																			$vacuna['predios_vacunacion_final'], 
																			$vacuna['nombre_laboratorios_vacunacion_final'], 
																			$vacuna['lote_vacunacion_final'], 
																			$ruta);
					}
				?>
			</table>
		</fieldset>
</div>

<div class="pestania">
	<h2>Datos Finales</h2>

	<fieldset>
		<legend>Documentos Finales</legend>
		
			<div data-linea="32">
				<label>Acta levantamiento de cuarentena:</label>
				<?php echo ($eventoSanitario['ruta_acta_final']==''? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$eventoSanitario['ruta_acta_final'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Acta cargada</a>')?>
			</div>
			
			<div data-linea="33">
				<label>Cierre del evento sanitario:</label>
				<?php echo ($eventoSanitario['ruta_informe_cierre']==''? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$eventoSanitario['ruta_informe_cierre'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Documento cargado</a>')?>
			</div>
	</fieldset>
	
	<fieldset>
		<legend>Conclusión</legend>
		
			<div data-linea="6">
				<label>Conclusión:</label>
					<?php echo $eventoSanitario['conclusion_final']; ?>
			</div>
	</fieldset>
	
</div>
	</body>
</html>