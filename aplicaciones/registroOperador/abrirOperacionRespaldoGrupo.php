<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorVigenciaDocumentos.php';

$conexion = new Conexion();
$cr = new ControladorRegistroOperador();
$cc = new ControladorCatalogos();
$cvd = new ControladorVigenciaDocumentos();

$operaciones = ($_POST['elementos']==''?$_POST['id']:$_POST['elementos']);
$idGrupoOperaciones = explode(",",($_POST['elementos']==''?$_POST['id']:$_POST['elementos']));

$identificadorInspctor =  $_SESSION['usuario'];

$qOperadorSitio = $cr->obtenerOperadorSitioInspeccion($conexion,$operaciones);
$operadorSitio = pg_fetch_assoc($qOperadorSitio);


///////////////////////////////////////////////////////////

$fecha1= date('Y-m-d - H-i-s');
$fecha = str_replace(' ', '', $fecha1);

$qOperacion=$cr->abrirOperacionXid($conexion, $operaciones);
$operacion = pg_fetch_assoc($qOperacion);

$idOperadorTipoOperacion = $operacion['id_operador_tipo_operacion'];
$idHistoricoOperacion = $operacion['id_historial_operacion'];

$idVigenciaDocumento = $operacion['id_vigencia_documento'];

$qHistorialOperacion = $cr->obtenerMaximoIdentificadorHistoricoOperacion($conexion, $idOperadorTipoOperacion);
$historialOperacion = pg_fetch_assoc($qHistorialOperacion);

$productos = $cr->obtenerProductosPorIdOperadorTipoOperacionHistorico($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], $operacion['estado'], $idVigenciaDocumento);//TODO:CAMBIADO
$contador = 0;

$qRepresentante = $cr->consultarDatosRepresentanteTecnicoPorOperadorTipoOperacionHistorico($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion']);

$qVigenciaDocumento = $cvd->obtenerVigenciaDeclaradaPorIdVigenciaXEtapaVigencia($conexion, $idVigenciaDocumento, 'cargarRespaldo');//TODO:Verificar

$bandera = false;
$validacionSubtipoProducto = array();
$formularioLaboratorio = '';
$formularioRespuestaLaboratorio = '';


$idflujoOPeracion = pg_fetch_assoc($cr->obtenerIdFlujoXOperacion($conexion, $operaciones));
$idFlujoActual = pg_fetch_assoc($cr->obtenerEstadoActualFlujoOperacion($conexion, $idflujoOPeracion['id_flujo_operacion'], 'cargarRespaldo'));

if($idFlujoActual['estado_alterno']!= ''){
	$subsanacion = '<option value="'.$idFlujoActual['estado_alterno'].'">Subsanación</option>';
}

$qcodigoTipoOperacion= $cc->obtenerCodigoTipoOperacion($conexion, $operaciones);
$opcionArea=  pg_fetch_result($qcodigoTipoOperacion, 0, 'codigo');
$idArea=  pg_fetch_result($qcodigoTipoOperacion, 0, 'id_area');

switch ($idArea){

	
	
	case 'LT':
		
		switch ($opcionArea){
			case 'LDI':
			case 'LDA':
			case 'LDE':
				
				$bandeLaboratorios=true;
				
				$formularioLaboratorio = '
					<fieldset>
						<legend>Análisis acreditados</legend>
						<div data-linea="1">
							<table style="width: 100%">
								<thead>
									<tr>
										<th>Matriz</th>
										<th>Parámetro</th>
										<th>Método</th>
										<th>Rango</th>
									</tr>
								</thead>
								<tbody>';
									$listaParametros = $cr->obtenerProductosLaboratorios($conexion, $idOperadorTipoOperacion, $idHistoricoOperacion);
									while ($fila = pg_fetch_assoc($listaParametros)){
										$formularioLaboratorio .= '<tr>' .
											'<td>'.$fila['nombre_comun'].'</td>'.
											'<td>'.$fila['nombre_parametro'].'</td>'.
											'<td>'.$fila['nombre_metodo'].'</td>'.
											'<td>'.$fila['descripcion_rango'].'</td>'.
											'</tr>';
									}
				$formularioLaboratorio .=
								'</tbody>
							</table>
						</div>
					</fieldset>';
				
				$formularioRespuestaLaboratorio = '<div data-linea="10">
                			<label>Fecha de firma del convenio </label>
                			<input	type="text" id="fechaConvenio" name="fechaConvenio" readonly="readonly"/>
                		</div>
						<div data-linea="11">
                			<label>Código:</label>
                			<input	type="text" id="codigoLaboratorio" name="codigoLaboratorio"/>
                		</div>';
			break;
		}
	break;
}

?>

<header>
	<h1>Solicitud Operador</h1>
</header>
<div id="estado"></div>


	<fieldset>
		<legend>Datos operador</legend>
		<div data-linea="1">
			<label>Número de identificación: </label> <?php echo $operadorSitio['identificador']; ?> <br />
		</div>

		<div data-linea="2">
			<label>Razón social: </label> <?php echo $operadorSitio['nombre_operador']; ?> 
		</div>

		<hr/>

		<div data-linea="4">
			<label>Nombre sitio: </label> <?php echo $operadorSitio['nombre_lugar']; ?> 
		</div>
		
		<div data-linea="5">
			<label>Provincia: </label> <?php echo $operadorSitio['provincia']; ?> 
		</div>

		<div data-linea="5">
			<label>Canton: </label> <?php echo $operadorSitio['canton']; ?> <br />
		</div>

		<div data-linea="5">
			<label>Parroquia: </label> <?php echo $operadorSitio['parroquia']; ?> <br />
		</div>

		<div data-linea="6">
			<label>Dirección: </label> <?php echo $operadorSitio['direccion']; ?> <br />
		</div>
		
	</fieldset>

	<form id="evaluarSolicitud" data-rutaAplicacion="revisionFormularios" data-opcion="evaluarRespaldoSolicitud" data-accionEnExito="ACTUALIZAR">
		<input type="hidden" name="inspector" value="<?php echo $identificadorInspctor;?>"/> <!-- INSPECTOR -->
		<input type="hidden" name="idSolicitud" value="<?php echo $operaciones;?>"/>
		<input type="hidden" name="tipoSolicitud" value="Operadores"/>
		<input type="hidden" name="tipoInspector" value="Técnico"/>
		<input type="hidden" name="identificadorOperador" value="<?php echo $operadorSitio['identificador'];?>"/>
		<input type="hidden" name="tipoElemento" value="Área"/>
		<input type="hidden" name="idOperadorTipoOperacion" value="<?php echo $idOperadorTipoOperacion;?>"/>
		<input type="hidden" name="idHistoricoOperacion" value="<?php echo $idHistoricoOperacion;?>"/>
		<input type="hidden" name="codigoProvinciaSitio" value="<?php echo $operadorSitio['codigo_provincia'];?>"/>
		<input type="hidden" name="idSitio" value="<?php echo $operadorSitio['id_sitio'];?>"/>
		<input type="hidden" name="provinciaSitio" value="<?php echo $operadorSitio['provincia'];?>"/>

	<fieldset>
		<legend>Operación, área</legend>
	
	<?php 
	$contador = 40;	
	foreach ($idGrupoOperaciones as $solicitud){
		$registros = array();
		$qAreasOperador = $cr->obtenerOperadorOperacionAreaInspeccion($conexion, $solicitud);
		
		while($areaOperacion = pg_fetch_assoc($qAreasOperador)){
			$registros[] = array('nombreArea' => $areaOperacion['nombre_area'], 'tipoArea' => $areaOperacion['tipo_area'], 'nombreOperacion' => $areaOperacion['nombre_operacion'], 
								'idArea' => $areaOperacion['id_area'], 'superficieUtilizada' => $areaOperacion['superficie_utilizada'], 'idOperacion' => $areaOperacion['id_operacion']);
		}
		
		$qDocumentosAdjuntos = $cr->obtenerDocumentosAdjuntoXoperacion($conexion, $solicitud);
		$documentoAdjunto = (pg_num_rows($qDocumentosAdjuntos)!= 0 ? true : false);
		
		echo ($contador!=40?'<hr>':'');
		
		echo'
		<div data-linea="'.$contador.'">
			<label>Tipo operación: </label> ' . $registros[0]['nombreOperacion'] . '
		</div>';
		
		echo '<div data-linea="'.++$contador.'">
			<label>Nombre área: </label>';

		foreach ($registros as $areas){
			//Información de tamaño de áreas
			$qUnidadMedida = $cc->obtenerUnidadMedidaAreas($conexion, $areas['idArea']);
			$unidadMedida = pg_fetch_result($qUnidadMedida, 0, 'unidad_medida');
			
			echo $areas['nombreArea'].' ('. $areas['superficieUtilizada'].' '.$unidadMedida .')</label>
						<input type="hidden" name="idAreas[]" value="'.$areas['idArea'].'"/>
						<input type="hidden" name="idOperaciones[]" value="'.$areas['idOperacion'].'"/>
				</div>';			
		}
			
		if($bandera == false){
		
			if($documentoAdjunto){
		
				echo '<div data-linea="'.++$contador.'">
							<label>Documentos adjuntos: </label></div>';
				
				while ($documento = pg_fetch_assoc($qDocumentosAdjuntos)){
					echo '<div data-linea="'.++$contador.'"><label>'.$documento['titulo'].'.-  </label><a href="'.$documento['ruta_documento'].'">'.$documento['descripcion'].'</a></div>';
				}
			}
		
		}	
			
		$contador++;
	}	
	
	?>
	
	</fieldset>
	
	<?php 
		if(pg_num_rows($qRepresentante) != 0){

			echo '<fieldset>
					<legend>Representante técnico</legend>
						<table style="width: 100%">
							<thead>
								<tr>
									<th>Identificación</th>
									<th>Nombre</th>
									<th>Título</th>
									<th>Área</th>
									<th>Nro. Registro Senescyt</th>
								</tr>
							</thead>
							<tbody>';

			while ($fila = pg_fetch_assoc($qRepresentante)) {

				echo '<tr>
						<td>'.$fila['identificacion_representante'].'</td>
						<td>'.$fila['nombre_representante'].'</td>
						<td>'.$fila['titulo_academico'].'</td>
						<td>'.($fila['id_area_representante'] =='SA'? 'Sanidad Animal': ($fila['id_area_representante'] =='SV'? 'Sanidad Vegetal': ($fila['id_area_representante'] =='IAV'? 'Pecuarios': ($fila['id_area_representante'] =='IAP'? 'Agrícolas': ($fila['id_area_representante'] =='IAF'? 'Fertilizantes': 'N/A'))))).'</td>
						<td>'.$fila['numero_registro_titulo'].'</td>
					</tr>';
			}

			echo '</tbody>
				</table>
			</fieldset>';
		}
	?>

	<?php 
	$contadoProducto = 0;
	if((pg_num_rows($productos)!= 0) && (!$bandeLaboratorios)){?>
	<fieldset id="datosProducto">
		<legend>Productos</legend>
				
		<table style="width: 100%">
			<thead>
				<tr>
					<th>#</th>
					<th>Tipo producto</th>
					<th>Subtipo producto</th>
					<th>Producto</th>
				</tr>
			</thead>
			<tbody>
			<?php
				while ($fila = pg_fetch_assoc($productos)){
					$validacionSubtipoProducto[] = $fila['codificacion_subtipo_producto'];
					echo '<tr><td>'.++$contadoProducto.'</td><td>'.$fila['nombre_tipo'].'</td><td>'.$fila['nombre_subtipo'].'</td><td>'.$fila['nombre_comun'].'</td></tr>';
				}
				
				$validacionSubtipoProducto = array_unique($validacionSubtipoProducto);
				
			?>
			</tbody>
		</table>
		
		<input type='hidden' name='validacionSubtipoProducto' value='<?php echo serialize($validacionSubtipoProducto);?>'/>
		
	</fieldset>
	<?php }?>	
	
	<?php 
	echo $formularioLaboratorio;
	?>
	
	<fieldset>
		<legend>Documento de resplado</legend>
		
		<div data-linea="20">
			<input type="file" id="informe" class="archivo" name="informe" accept="application/pdf"/>
			<input type="hidden" id="rutaArchivo" class="rutaArchivo" name="archivo" value="0"/>
			<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
			<button type="button" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/registroOperador/laboratorios/convenio" >Subir archivo</button>
			<input type="hidden" id="fecha" name="fecha" value="<?php echo $fecha;?> "/>
		</div>

	</fieldset>
		
	<fieldset>
		<legend>Resultado de Revisión</legend>
				
			<div data-linea="1">
				<label>Resultado</label>
					<select id="resultado" name="resultado">
						<option value="">Seleccione....</option>
						<?php 
							while($vigenciaDocumento = pg_fetch_assoc($qVigenciaDocumento)){//TODO:CAMBIADO

									if($vigenciaDocumento['valor_tiempo_vigencia_declarada'] == 1){
										switch ($vigenciaDocumento['tipo_tiempo_vigencia_declarada']){
									
											case 'anio':
												$tipoTiempo = 'año';
												break;
									
											case 'mes':
												$tipoTiempo = 'mes';
												break;
									
											case 'dia':
												$tipoTiempo = 'día';
												break;
									
										}
									}elseif($vigenciaDocumento['valor_tiempo_vigencia_declarada'] > 1){
										switch ($vigenciaDocumento['tipo_tiempo_vigencia_declarada']){
												
											case 'anio':
												$tipoTiempo = 'años';
												break;
									
											case 'mes':
												$tipoTiempo = 'meses';
												break;
									
											case 'dia':
												$tipoTiempo = 'días';
												break;
									
										}
									}
														
								echo '<option value="'.$vigenciaDocumento['id_vigencia_declarada'].'" data-resultado ="registrado">Aprobado por '.$vigenciaDocumento['valor_tiempo_vigencia_declarada'].' '.$tipoTiempo.'</option>';
							}
						
							if(pg_num_rows($qVigenciaDocumento) == 0){?>
								<option value="registrado" data-resultado ="registrado">Registrado</option>
							<?php }?>
								<option value="noHabilitado">No habilitado</option>
								<?php echo $subsanacion;?>
					</select>
			</div>
			<?php echo $formularioRespuestaLaboratorio;?>
			
			<div data-linea="4">
				<label>Observaciones</label>
					<input type="text" id="observacion" name="observacion" maxlength="500"/>
			</div>
	</fieldset>
		
		<button type="submit" class="guardar">Enviar resultado</button>
	</form>	
	

<script type="text/javascript">

var idArea = <?php echo json_encode($idArea); ?>;
var opcionArea = <?php echo json_encode($opcionArea); ?>;

	$(document).ready(function(){
		distribuirLineas();
		construirValidador();

		$("#fechaConvenio").datepicker({
		      changeMonth: true,
		      changeYear: true,
		      minDate: "-1M",
		      maxDate: "+2M"
		});
	});

	$("#evaluarSolicitud").submit(function(event){
		event.preventDefault();
		chequearCamposInspeccion(this);
	});
	
    $('button.subirArchivo').click(function (event) {
    	numero = Math.floor(Math.random()*100000000);
        var boton = $(this);
        var archivo = boton.parent().find(".archivo");
        var rutaArchivo = boton.parent().find(".rutaArchivo");
        var extension = archivo.val().split('.');
        var estado = boton.parent().find(".estadoCarga");

        numero = Math.floor(Math.random()*100000000);	

        if (extension[extension.length - 1].toUpperCase() == 'PDF') {

            subirArchivo(
                archivo
                , $("[name=idSolicitud]").val()+'_'+numero
                , boton.attr("data-rutaCarga")
                , rutaArchivo
                , new carga(estado, archivo, boton)
            );
        } else {
            estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
            archivo.val("");
        }
    });

	
	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

	function chequearCamposInspeccion(form){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;
	
		if($("#resultado").val() == 'noHabilitado' || $("#resultado").val() == 'subsanacion'){
			if(!$.trim($("#observacion").val()) || !esCampoValido("#observacion")){
				error = true;
				$("#observacion").addClass("alertaCombo");
			}
		}else{
			if(!$.trim($("#fechaConvenio").val())){
				error = true;
				$("#fechaConvenio").addClass("alertaCombo");
			}

			if(!$.trim($("#codigoLaboratorio").val())){
				error = true;
				$("#codigoLaboratorio").addClass("alertaCombo");
			}

			if(!$.trim($("#codigoLaboratorio").val())){
				error = true;
				$("#codigoLaboratorio").addClass("alertaCombo");
			}

			if($("#rutaArchivo").val() == '0'){
				error = true;
				$("#informe").addClass("alertaCombo");
			}
		}
		
		if (error){
			$("#estado").html("Por favor revise la información ingresada.").addClass('alerta');
		}else{
			ejecutarJson(form);
		}
	}

</script>

