<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorFitosanitarioExportacion.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$cr = new ControladorRegistroOperador();

$cfe = new ControladorFitosanitarioExportacion();

$idFitosanitarioExportacion = $_POST['id'];
$identificadorUsuario = $_SESSION['usuario'];

$qCabeceraFitosanitarioExportacion = $cfe->obtenerCabeceraFitosanitarioExportacion($conexion, $idFitosanitarioExportacion);
$cabeceraFitosanitarioExportacion = pg_fetch_assoc($qCabeceraFitosanitarioExportacion);

$exportadoresFitosanitarioExportacion = $cfe->obtenerExportadoresFitosanitarioExportacion($conexion, $idFitosanitarioExportacion);

$qDocumentos = $cfe->obtenerArchivosAdjuntosFitosanitarioExportacion($conexion, $idFitosanitarioExportacion);

$qTransitoFitosanitarioExportacion = $cfe -> obtenerTransitoFitosanitarioExportacion($conexion, $idFitosanitarioExportacion);

?>

<header>
	<h1>Solicitud fitosanitaria de exportación</h1>
</header>
	
<div id="estado"></div>

<div class="pestania">

	<?php 
		if($cabeceraFitosanitarioExportacion['estado_anterior'] == 'inspeccion'){
			echo '<fieldset>
					<legend>Información general.</legend>
						<div data-linea="1">
							<label>Estado anterior: </label> Solicitud proviene de un estado anterior de <span class="alerta">'.$cabeceraFitosanitarioExportacion['estado_anterior'].'</span>
						</div>
			
						<div data-linea="2">
							<label>Observación: </label> '. $cabeceraFitosanitarioExportacion['observacion'].'
						</div>
				</fieldset>';
		}
	
	?>
	
	<fieldset>
			<legend>Certificado fitosanitario de exportación # <?php echo $cabeceraFitosanitarioExportacion['id_vue']; ?></legend>
						
			<div data-linea="1">
				<label>Idioma: </label> <?php echo $cabeceraFitosanitarioExportacion['codigo_idioma']; ?> 
			</div>
			<div data-linea="1">
				<label>Identificador solicitante: </label> <?php echo $cabeceraFitosanitarioExportacion['numero_identificacion_solicitante']; ?> 
			</div>
			
			<div data-linea="2">
				<label>Razón social: </label> <?php echo $cabeceraFitosanitarioExportacion['razon_social_solicitante']; ?> 
			</div>
			
			<div data-linea="3">
				<label>Dirección: </label> <?php echo $cabeceraFitosanitarioExportacion['direccion_solicitante']; ?>
			</div>
			
			<div data-linea="4">
				<label>Teléfono: </label> <?php echo $cabeceraFitosanitarioExportacion['telefono_solicitante']; ?> 
			</div>
			
			<div data-linea="4">
				<label>E-mail: </label> <?php echo $cabeceraFitosanitarioExportacion['correo_electronico_solicitante']?> 
			</div>
			
			<hr/>
			
			<div data-linea="5">
				<label>Nombre importador: </label> <?php echo $cabeceraFitosanitarioExportacion['nombre_importador']?> 
			</div>
			
			<div data-linea="6">
				<label>Dirección importador: </label> <?php echo $cabeceraFitosanitarioExportacion['direccion_importador']?> 
			</div>
			
			<hr/>
			
			<div data-linea="7">
				<label>Producto orgánico: </label> <?php echo $cabeceraFitosanitarioExportacion['producto_organico']?> 
			</div>
			
			<?php 
				if($cabeceraFitosanitarioExportacion['producto_organico'] == 'SI'){
					echo '<div data-linea="7">
								<label>Certificado #: </label> '. $cabeceraFitosanitarioExportacion['certificado_organico'].' 
						  </div>';
				}
			?>
			
			<div data-linea="8">
				<label>Número de Bultos: </label> <?php echo $cabeceraFitosanitarioExportacion['numero_bultos'].' '.$cabeceraFitosanitarioExportacion['unidad_bultos']?> 
			</div>
			
			<div data-linea="8">
				<label>País de origen: </label> <?php echo $cabeceraFitosanitarioExportacion['nombre_pais_origen']?> 
			</div>
			
			<?php 
				if($cabeceraFitosanitarioExportacion['identificacion_agencia_carga'] != ''){
					echo '<div data-linea="9">
								<label>Identificador agencia carga: </label> '. $cabeceraFitosanitarioExportacion['identificacion_agencia_carga'].' 
						  </div>
						  <div data-linea="10">
								<label>Nombre agencia carga: </label> '. $cabeceraFitosanitarioExportacion['nombre_agencia_carga'].'
						  </div>
						';
				}
			?>
			
			<div data-linea="11">
				<label>País destino: </label> <?php echo $cabeceraFitosanitarioExportacion['nombre_pais_destino']?> 
			</div>
			
			<div data-linea="12">
				<label>Puerto destino: </label> <?php echo $cabeceraFitosanitarioExportacion['nombre_puerto_destino']?> 
			</div>
			
			<div data-linea="13">
				<label>País embarque: </label> <?php echo $cabeceraFitosanitarioExportacion['nombre_pais_embarque']?> 
			</div>
			
			<div data-linea="13">
				<label>Fecha embarque: </label> <?php echo date('j/n/Y (G:i)',strtotime($cabeceraFitosanitarioExportacion['fecha_embarque']))?> 
			</div>
			
			<div data-linea="14">
				<label>Puerto embarque: </label> <?php echo $cabeceraFitosanitarioExportacion['nombre_puerto_embarque'] ?> 
			</div>
			
			<div data-linea="14">
				<label>Medío transporte: </label> <?php echo $cabeceraFitosanitarioExportacion['nombre_medio_transporte'] ?> 
			</div>
			
			<div data-linea="15">
				<label>Nombre marca: </label> <?php echo $cabeceraFitosanitarioExportacion['nombre_marca'] ?> 
			</div>
			
			<div data-linea="15">
				<label>Número de viaje: </label> <?php echo $cabeceraFitosanitarioExportacion['numero_viaje'] ?> 
			</div>
			
			<?php 
				if($cabeceraFitosanitarioExportacion['informacion_adicional'] != ''){
					echo '<div data-linea="16">
								<label>Información adicional: </label> '. $cabeceraFitosanitarioExportacion['informacion_adicional'] .'
						  </div>';
				}
			
				if($cabeceraFitosanitarioExportacion['descuento'] == 'SI'){
					echo '<div data-linea="17">
								<label>Posee descuento: </label> '. $cabeceraFitosanitarioExportacion['descuento'] .' 
						  </div>
						  <div data-linea="18">
								<label>Motivo descuento: </label> '. $cabeceraFitosanitarioExportacion['motivo_descuento'] .'
						  </div>
							';
				}
			
				if($cabeceraFitosanitarioExportacion['uso_ciudad_transito'] == 'SI'){
					echo '<div data-linea="16">
								<label>Posee transito: </label> '. $cabeceraFitosanitarioExportacion['uso_ciudad_transito'] .' 
						  </div>';
				}
			?>
			
	</fieldset>
	
	
		
		<?php 
			//foreach ($exportadoresFitosanitarioExportacion as $exportadores){
			while ($exportador = pg_fetch_assoc($exportadoresFitosanitarioExportacion)){
		
				echo '<fieldset>
				  	<legend>Datos del exportador </legend>';
					
					$datosExportador = pg_fetch_assoc($cr->listarOperadoresEmpresa($conexion, $exportador['numero_identificacion_exportador']));
					
					echo '<div data-linea="1">
								<label>Identificador del exportador: </label> '. $exportador['numero_identificacion_exportador'] .' 
							</div>
							<div data-linea="2">
								<label>Razón social: </label> '. $datosExportador['nombre_operador'] .' 
						  </div><hr/>';
					
					$productosFitosanitarioExportacion = $cfe->obtenerProductosFitosanitarioExportacion($conexion, $idFitosanitarioExportacion, $exportador['id_fitosanitario_exportador']);
					
					$i=2;
					while ($producto = pg_fetch_assoc($productosFitosanitarioExportacion)){

						$qProductoTipoSubtipo = $cc->obtenerTipoSubtipoXProductos($conexion, $producto['id_producto']);
						$productoTipoSubtipo = pg_fetch_assoc($qProductoTipoSubtipo);
						
						$datosProducto = pg_fetch_assoc($cc->ObtenerProductoPorId($conexion, $producto['id_producto']));

						echo '<div data-linea="'.++$i.'">
									<label>Tipo producto: </label> '. $productoTipoSubtipo['nombre_tipo'] .' 
						  	  </div>
							  <div data-linea="'.++$i.'">
									<label>Subtipo producto: </label> '. $productoTipoSubtipo['nombre_subtipo'] .' 
						  	  </div>
							  <div data-linea="'.++$i.'">
									<label>Nombre producto: </label> '. $datosProducto['nombre_comun'] .' 
						  	  </div>
							  <div data-linea="'.++$i.'">
									<label>Partida arancelaria: </label> '. $datosProducto['partida_arancelaria'] .' 
						  	  </div>
							  <div data-linea="'.++$i.'">
									<label>Cantidad cobro: </label> '. $producto['cantidad_cobro'].' '.$producto['unidad_cobro'].' 
						  	  </div>
							  <div data-linea="'.$i.'">
									<label>Cantidad peso neto: </label> '. $producto['cantidad_peso_neto'].' '.$producto['unidad_peso_neto'].' 
						  	  </div>
							  <div data-linea="'.++$i.'">
									<label>Cantidad peso bruto: </label> '. $producto['cantidad_peso_bruto'].' '.$producto['unidad_peso_bruto'].' 
						  	  </div>
							  <div data-linea="'.$i.'">
									<label>Cantidad comercial: </label> '. $producto['cantidad_comercial'].' '.$producto['unidad_cantidad_comercial'].' 
						  	  </div>';
						
						if($producto['descripcion_tipo_tratamiento'] != ''){
							echo '<div data-linea="'.++$i.'">
									<label>Tipo tratamiento: </label> '. $producto['descripcion_tipo_tratamiento'].' 
						  	  </div>';
						}
						
						if($producto['descripcion_nombre_tratamiento'] != ''){
							echo '<div data-linea="'.++$i.'">
									<label>Nombre tratamiento: </label> '. $producto['descripcion_nombre_tratamiento'].'
						  	  </div>';
						}
						
						if($producto['duracion_tratamiento'] != ''){
							echo '<div data-linea="'.++$i.'">
									<label>Duración tratamiento: </label> '. $producto['duracion_tratamiento'].' '.$producto['unidad_tratamiento'].'
						  	  </div>';
						}
						
						if($producto['temperatura_tratamiento'] != ''){
							echo '<div data-linea="'.$i.'">
									<label>Temperatura tratamiento: </label> '. $producto['temperatura_tratamiento'].' '.$producto['unidad_temperatura_tratamiento'].'
						  	  </div>';
						}
						
						if($producto['concentracion_producto_quimico'] != ''){
							echo '<div data-linea="'.++$i.'">
									<label>Concentración: </label> '. $producto['concentracion_producto_quimico'].' 
						  	  </div>';
						}
						
						if($producto['fecha_tratamiento'] != ''){
							echo '<div data-linea="'.$i.'">
									<label>Fecha tratamiento: </label> '. date('j/n/Y (G:i)',strtotime($producto['fecha_tratamiento'])).'
						  	  </div>';
						}
						
						if($producto['producto_quimico'] != ''){
							echo '<div data-linea="'.++$i.'">
									<label>Producto quimico: </label> '. $producto['producto_quimico'].'
						  	  </div>';
						}
						
						if($producto['informacion_adicional'] != ''){
							echo '<div data-linea="'.++$i.'">
									<label>Información adicional: </label> '. $producto['informacion_adicional'].'
						  	  </div>';
						}
						
						$areasFitosanitarioExportacion = $cfe->obtenerAreasFitosanitarioExportacion($conexion, $idFitosanitarioExportacion, $exportador['id_fitosanitario_exportador'], $producto['id_fitosanitario_producto']);
						
						$codigoArea = '';
						while ($area = pg_fetch_assoc($areasFitosanitarioExportacion)){
							$codigoArea .= $area['codigo_area_agrocalidad_unibanano'].', ';
						}
						
						$codigoArea = rtrim($codigoArea, ', ');
						
						echo '<div data-linea="'.++$i.'">
								<label>Código área: </label> '. $codigoArea.'
					  	  </div><hr/>';
					
						
						
					}
					
					
			echo '</fieldset>';
		
		}
		
		//Transito
		if(count($qTransitoFitosanitarioExportacion)>0){
			$j=1;
		
			echo'<div>
				<fieldset>
					<legend>Detalle de Tránsito</legend>
		
								<table>
									<tr>
										<td><label>#</label></td>
										<td><label>País</label></td>
										<td><label>Puerto</label></td>
										<td><label>Medio de transporte</label></td>
									</tr>';
		
		
			foreach ($qTransitoFitosanitarioExportacion as $transito){
				echo '<tr>
						<td>'.$j.'</td>
						<td>'.$transito['nombrePais'].'</td>
						<td>'.$transito['nombrePuerto'].'</td>
						<td>'.$transito['tipoTransporte'].'</td>
				 </tr>';
				$j++;
			}
		
			echo '</table>
			</fieldset>
			</div>';
		}
			 
	//IMPRESION DE DOCUMENTOS
	$i=1;
	if(count($qDocumentos)>0){
		
		echo'<div id="documentos" >
					<fieldset>
						<legend>Documentos adjuntos</legend>
				
								<table>
									<tr>
										<td><label>#</label></td>
										<td><label>Nombre</label></td>
										<td><label>Enlace</label></td>
									</tr>';


		foreach ($qDocumentos as $documento){
					echo '<tr>
						  	<td>'.$i.'</td>
							<td>'.$documento['tipoArchivo'].'</td>
							<td>
								<form id="f_'.$i.'" action="aplicaciones/general/accederDocumentoFTP.php" method="post" enctype="multipart/form-data" target="_blank">
									<input name="rutaArchivo" value="'.$documento['rutaArchivo'].'" type="hidden">
									<input name="nombreArchivo" value="'.$documento['tipoArchivo'].'.pdf" type="hidden">
									<input name="idVue" value="'.$documento['idVue'].'" type="hidden">
									<button type="submit" name="boton">Descargar</button>
								</form>
							</td>
						 </tr>';
						$i++;
		}

		echo '</table>
			</fieldset>
			</div>';

	}
		
	?>	
</div>


<div class="pestania">	
	
	<form id="evaluarSolicitud" data-rutaAplicacion="revisionFormularios" data-opcion="evaluarElementosSolicitud" data-accionEnExito="ACTUALIZAR">
		<input type="hidden" id="inspector" name="inspector" value="<?php echo $identificadorUsuario;?>"/> <!-- INSPECTOR -->
		<input type="hidden" id="idSolicitud" name="idSolicitud" value="<?php echo$idFitosanitarioExportacion;?>"/>
		<input type="hidden" name="tipoSolicitud" value="FitosanitarioExportacion"/>
		<input type="hidden" name="tipoInspector" value="Técnico"/>
		<input type="hidden" name="identificadorOperador" value=""/> <!-- USUARIO OPERADOR -->
		<input type="hidden" id="idVue" name="idVue" value="<?php echo $cabeceraFitosanitarioExportacion['id_vue'];?>"/>
		<input type="hidden" name="tipoElemento" value="FitosanitarioExportacion"/>
		
		<fieldset id="subirInforme">
			<legend>Informe de revisión</legend>
				<div>
					<input type="file" class="archivo" name="informe" id="informe" accept="application/pdf"/>
					<input type="hidden" class="rutaArchivo" name="archivo" id="archivo" value="0"/>
					<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
					<button type="button" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/fitosanitarioExportacion/informeInspeccion" >Subir archivo</button>
				</div>
		</fieldset>
		
		<fieldset>
			<legend>Resultado de inspección</legend>						
				<div data-linea="6">
					<label>Resultado</label>
						<select id="resultado" name="resultado">
							<option value="">Seleccione....</option>
							<option value="pago">Aprobado inspección</option>
							<option value="subsanacion">Subsanación</option>
							<option value="rechazado">No aprobado</option>
						</select>
				</div>		
				<div data-linea="2">
					<label>Observaciones</label>
						<input type="text" id="observacion" name="observacion"/>
				</div>
		</fieldset>
		
		<button type="submit" class="guardar">Enviar resultado</button>			
	</form> 
</div>

<script type="text/javascript">

	$(document).ready(function(){
		distribuirLineas();
		construirAnimacion($(".pestania"));	
	});

	$("#evaluarSolicitud").submit(function(event){
		event.preventDefault();
		chequearCamposInspeccion(this);
	});

	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

	function chequearCamposInspeccion(form){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#resultado").val()) || !esCampoValido("#resultado")){
			error = true;
			$("#resultado").addClass("alertaCombo");
		}

		if($("#resultado").val()=='rechazado' || $("#resultado").val()=='subsanacion'){
			if(!$.trim($("#observacion").val()) || !esCampoValido("#observacion")){
				error = true;
				$("#observacion").addClass("alertaCombo");
			}
		}

		if($("#archivo").val() == 0){
			error = true;
			$("#informe").addClass("alertaCombo");
		}
		
		if (error){
			$("#estado").html("Por favor revise la información ingresada.").addClass('alerta');
		}else{
			ejecutarJson(form);
		}
	}
	
	$('button.subirArchivo').click(function (event) {
        var boton = $(this);
        var archivo = boton.parent().find(".archivo");
        var rutaArchivo = boton.parent().find(".rutaArchivo");
        var extension = archivo.val().split('.');
        var estado = boton.parent().find(".estadoCarga");

        if (extension[extension.length - 1].toUpperCase() == 'PDF') {

            subirArchivo(
                archivo
                , $('#inspector').val()+"_"+ $('#idSolicitud').val()+"_"+$('#idVue').val()
                , boton.attr("data-rutaCarga")
                , rutaArchivo
                , new carga(estado, archivo, boton)
            );
        } else {
            estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
            archivo.val("0");
        }
    });
	
</script>
