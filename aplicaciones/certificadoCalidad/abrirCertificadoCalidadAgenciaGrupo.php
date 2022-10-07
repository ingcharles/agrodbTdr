<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorCertificadoCalidad.php';

$conexion = new Conexion();
$cca = new ControladorCertificadoCalidad();
$cc = new ControladorCatalogos();

$identificadorUsuario = $_SESSION['usuario'];

$idSolicitud = ($_POST['elementos']==''?$_POST['id']:$_POST['elementos']);

$qCertificadoCalidad = $cca->obtenerSolicitudCertificadoCalidadXGrupoLotes($conexion, $idSolicitud);

$informacionLote = pg_fetch_assoc($cca->obtenerDatosInspeccionAgenciaVerificadora($conexion, $idSolicitud));

?>

<header>
	<h1>Solicitud certificado de calidad</h1>
</header>

	<div id="estado"></div>
	
	<div class="pestania">

<?php 
	
	while ($certificadoCalidad = pg_fetch_assoc($qCertificadoCalidad)){
		
		echo'<fieldset>
				<legend>Solicitud # '.$certificadoCalidad['id_certificado_calidad'].' </legend>
		
				<div data-linea="1">
					<label>Datos exportador </label>
				</div>
			
				<div data-linea="2">
					<label>Identificación: </label> '. $certificadoCalidad['identificador_exportador'].'
				</div>
				
				<div data-linea="3">
					<label>Razón social: </label> '. $certificadoCalidad['razon_social_exportador'].'
				</div>
		
				<hr/>
		
				<div data-linea="4">
					<label>Datos del importador</label>
				</div>
			
				<div data-linea="5">
					<label>Nombre: </label> '. $certificadoCalidad['nombre_importador'].'
				</div>
				
				<div data-linea="6">
					<label>Dirección: </label> '. $certificadoCalidad['direccion_importador'].'
				</div>
				
				<hr/>
				
				<div data-linea="7">
					<label>Datos generales de exportación</label>
				</div>
				
				<div data-linea="8">
					<label>Fecha de embarque: </label> '. date('j/n/Y',strtotime($certificadoCalidad['fecha_embarque'])).'
				</div>
				
				<div data-linea="8">
					<label>Número de viaje: </label> '. $certificadoCalidad['numero_viaje'].'
				</div>
				
				<div data-linea="9">
					<label>País de embarque: </label> '. $certificadoCalidad['nombre_pais_embarque'].'
				</div>
				
				<div data-linea="9">
					<label>Puerto embarque: </label> '. $certificadoCalidad['nombre_puerto_embarque'].'
				</div>
				
				<div data-linea="10">
					<label>Medio de transporte: </label> '. $certificadoCalidad['nombre_medio_transporte'].'
				</div>
				
				<div data-linea="11">
					<label>País de destino: </label> '. $certificadoCalidad['nombre_pais_destino'].'
				</div>
				
				<div data-linea="11">
					<label>Puerto de destino: </label> '. $certificadoCalidad['nombre_puerto_destino'].'
				</div>';
						
				$lugarCertificadoCalidad = $cca->obtenerLugarXGrupoLotes($conexion, $idSolicitud, $certificadoCalidad['id_certificado_calidad']);
	
				$i = 20;
				
				while ($lugarCertificado = pg_fetch_assoc($lugarCertificadoCalidad)){
					
				echo '
						<hr/>
							<div data-linea='.++$i.'>
								<label class="mayusculas">Lugar de inspección '.$lugarCertificado['nombre_area_operacion'].'</label>
							</div>
						<hr/>
					
							<div data-linea='.++$i.'>
								<label>Nombre provincia: </label> '. $lugarCertificado['nombre_provincia'].'
							</div>
					
							<div data-linea='.$i.'>
								<label>Fecha de inspección: </label> '.  date('j/n/Y',strtotime($lugarCertificado['solicitud_fecha_inspeccion'])).'
							</div>
					
						';
				
						$loteCertificadoInspeccion = $cca->obtenerLoteCertificadoCalidad($conexion, $idSolicitud, $lugarCertificado['id_lugar_inspeccion']);
						
						$cantidadRegistros = pg_num_rows($loteCertificadoInspeccion);
						
						$aux = 0;
						while($loteCertificado = pg_fetch_assoc($loteCertificadoInspeccion)){
						
							$aux++;
							echo '
							<div data-linea='.++$i.'>
								<label>Nombre producto: '.$loteCertificado['nombre_producto'].'</label>
							</div>
								
							<div data-linea='.++$i.'>
								<label>Número lote: </label> '. $loteCertificado['numero_lote'].'
							</div>
							
							<div data-linea='.$i.'>
								<label>Valor FOB: </label> '. $loteCertificado['valor_fob'].'
							</div>
							<div data-linea='.++$i.'>
								<label>Peso neto: </label> '. $loteCertificado['peso_neto'] .' '.$loteCertificado['unidad_peso_neto'].'
							</div>
							<div data-linea='.$i.'>
								<label>Peso bruto: </label> '. $loteCertificado['peso_bruto'].' '.$loteCertificado['unidad_peso_bruto'].'
							</div>
							<div data-linea='.++$i.'>
								<label>Variedad: </label> '. $loteCertificado['nombre_variedad_producto'].'
							</div>
							<div data-linea='.$i.'>
								<label>Calidad: </label> '. $loteCertificado['nombre_calidad_producto'].'
							</div>';
						
							if($cantidadRegistros != $aux){
								echo '<hr/>';
							}
						}	
				}
						
				echo'</fieldset>';
		}

?>


</div>

<!-- SECCION DE REVISIÓN DE PRODUCTOS Y ÁREAS PARA IMPORTACION -->

<div class="pestania">	
	<form id="evaluarDocumentosSolicitud" data-rutaAplicacion="certificadoCalidad" data-opcion="guardarInspeccionVerificadora" data-accionEnExito="ACTUALIZAR">
		<input type="hidden" name="inspector" value="<?php echo $identificadorUsuario;?>"/> <!-- INSPECTOR -->
		<input type="hidden" name="idSolicitud" value="<?php echo $idSolicitud;?>"/>
		
		<fieldset>
			<legend>Resultado de inspección</legend>
				
				<div data-linea="1">
					<label>Lote </label>	<?php echo $informacionLote['numero_lote'];?>
				</div>
				
				<div data-linea="1">
					<label>Calidad </label>	<?php echo $informacionLote['nombre_calidad_producto'];?>
				</div>
					
				<div data-linea="2">
					<label>Fecha análisis</label>
						<input type="text" id="fechaAnalisis" name="fechaAnalisis"/>
				</div>
				
				<div data-linea="3">
					<label>Exportador </label><?php echo $informacionLote['razon_social_exportador'];?>
				</div>
				
				<div data-linea="4">
					<label>Comprador </label> <?php echo $informacionLote['nombre_importador'];?>
				</div>
				
				<div data-linea="5">
					<label>Vapor</label>
						<input type="text" id="vapor" name="vapor"/>
				</div>
				
				<div data-linea="6">
					<label>Muestra inspector</label>
						<input type="text" id="muestraInspector" name="muestraInspector"/>
				</div>
				
				<div data-linea="6">
					<label>Contra muestra</label>
						<input type="text" id="contraMuestra" name="contraMuestra"/>
				</div>
		</fieldset>
		
		
		<fieldset>
			<legend>Tipo de inspección</legend>
			
			<div id="dTipoInspeccion">
				<input type="radio" name="tipoInspeccion" id="regular" value="Regular">
				<label for="regular">Regular</label><br/>
				<input type="radio" name="tipoInspeccion" id="puerto" value="Puerto">
				<label for="puerto">Puerto</label><br/>
				<input type="radio" name="tipoInspeccion" id="contraVerificacion" value="Contra verificacion">
				<label for="contraVerificacion">Contra verificación</label><br/>
			</div>
		</fieldset>
		
		<fieldset>
			<legend>Tipo de cacao verificado</legend>
			
			<section>
				<section id="sNacional">
					<div id="tipoCacaoNacional">
						<label>NACIONAL</label><br/>
						
						<input type="radio" name="nTipoCacaoVerificado" id="nOrigen" value="Nacional - Origen">
						<label for="nOrigen">Origen</label><br/>
						<input type="radio" name="nTipoCacaoVerificado" id="nRainforest" value="Nacional - Rainforest">
						<label for="nRainforest">Rainforest</label><br/>
						<input type="radio" name="nTipoCacaoVerificado" id="nOrganico" value="Nacional - Organico">
						<label for="nOrganico">Orgánico</label><br/>
						<input type="radio" name="nTipoCacaoVerificado" id="nComercioJusto" value="Nacional - Comercio justo">
						<label for="nComercioJusto">Comercio justo</label><br/>
					</div>
				</section>
				
				<section id="sCcn51">
				
					<div id="tipoCacaoCCN51">
						<label>CCN51</label><br/>
						
						<input type="radio" name="nTipoCacaoVerificado" id="cOrigen" value="CCN51 - Origen">
						<label for="cOrigen">Origen</label><br/>
						<input type="radio" name="nTipoCacaoVerificado" id="cRainforest" value="CCN51 - Rainforest">
						<label for="cRainforest">Rainforest</label><br/>
						<input type="radio" name="nTipoCacaoVerificado" id="cOrganico" value="CCN51 - Organico">
						<label for="cOrganico">Orgánico</label><br/>
						<input type="radio" name="nTipoCacaoVerificado" id="cComercioJusto" value="CCN51 - Comercio justo">
						<label for="cComercioJusto">Comercio justo</label><br/>
					</div>
				
				</section>
			</section>
			
		</fieldset>
		
		<fieldset>
			<legend>Inspección adicional</legend>
			
			<div data-linea="1">
				<label>Higiene</label>
				<select id="higiene" name="higiene">
					<option value="">Seleccione....</option>
					<option value="SI">SI</option>
					<option value="NO">NO</option>			
				</select>
			</div>
			
			<div data-linea="1">
				<label>Seguridad alimenticia</label>
				<select id="seguridadAlimenticia" name="seguridadAlimenticia">
					<option value="">Seleccione....</option>
					<option value="SI">SI</option>
					<option value="NO">NO</option>			
				</select>
			</div>
			
		</fieldset>
		
		<fieldset>
			<legend>Clasificación de corte</legend>
			
			<div data-linea="1">
					<label>Buena fermentación (%)</label>
						<input type="text" id="buenaFermentacion" name="buenaFermentacion" data-er="^[0-9]+(\.[0-9]{1,2})?$" class="suma"/>
			</div>
			
			<div data-linea="1">
					<label>Ligeramente fermentados (%)</label>
						<input type="text" id="ligeramenteFermentado" name="ligeramenteFermentado" data-er="^[0-9]+(\.[0-9]{1,2})?$" class="suma"/>
			</div>
			
			<div data-linea="2">
					<label>Grano violeta (%)</label>
						<input type="text" id="granoVioleta" name="granoVioleta" data-er="^[0-9]+(\.[0-9]{1,2})?$" class="suma"/>
			</div>
			
			
			<div data-linea="2">
					<label>Grano pizarroso (%)</label>
						<input type="text" id="granoPizarroso" name="granoPizarroso" data-er="^[0-9]+(\.[0-9]{1,2})?$" class="suma"/>
			</div>
			
			<div data-linea="3">
					<label>Mohos (%)</label>
						<input type="text" id="mohos" name="mohos" data-er="^[0-9]+(\.[0-9]{1,2})?$" class="suma"/>
			</div>
			
			<div data-linea="3">
					<label>Dañados por insectos (%)</label>
						<input type="text" id="danioInsecto" name="danioInsecto" data-er="^[0-9]+(\.[0-9]{1,2})?$" class="suma"/>
			</div>
			
			<div data-linea="4">
					<label>Vulnerado (%)</label>
						<input type="text" id="vulnerado" name="vulnerado" data-er="^[0-9]+(\.[0-9]{1,2})?$" class="suma"/>
			</div>
			
			<div data-linea="4">
					<label>TOTAL (%)</label>
					<input type="text" id="total" name="total" readonly="readonly"/>
			</div>
			
		</fieldset>
		
		<fieldset>
			<legend>Defectos</legend>
			
			<div data-linea="1">
					<label>Multiples (%)</label>
					<input type="text" id="multiple" name="multiple" data-er="^[0-9]+(\.[0-9]{1,2})?$"/>
			</div>
			<div data-linea="1">
					<label>Partidos (%)</label>
					<input type="text" id="partido" name="partido" data-er="^[0-9]+(\.[0-9]{1,2})?$"/>
			</div>
			<div data-linea="1">
					<label>Plano - granza (%)</label>
					<input type="text" id="planoGranza" name="planoGranza" data-er="^[0-9]+(\.[0-9]{1,2})?$"/>
			</div>
			
		</fieldset>
		
		<fieldset>
			<legend>Datos adicionales</legend>
			
			<div data-linea="1">
					<label>Impurezas de cacao (%)</label>
					<input type="text" id="impureza" name="impureza" data-er="^[0-9]+(\.[0-9]{1,2})?$"/>
			</div>
			<div data-linea="1">
					<label>Materia extraña (%)</label>
					<input type="text" id="materiaExtrania" name="materiaExtrania" data-er="^[0-9]+(\.[0-9]{1,2})?$"/>
			</div>
			<div data-linea="2">
					<label>Contenido de cacao tipo trinitario (CCN-51)</label>
					<input type="text" id="cacaoTrinitario" name="cacaoTrinitario" data-er="^[0-9]+(\.[0-9]{1,2})?$"/>
			</div>
			
			<div data-linea="3">
					<label>Peso de cacao de 100 pepas</label>
					<input type="text" id="pesoCacao" name="pesoCacao" data-er="^[0-9]+(\.[0-9]{1,2})?$"/>
			</div>
			
			<div data-linea="4">
					<label># Pepas en 100 gramos</label>
					<input type="text" id="numeroPepasCacao" name="numeroPepasCacao" data-er="^[0-9]+(\.[0-9]{1,2})?$"/>
			</div>
			
			<div data-linea="5">
					<label>Humedad</label>
					<input type="text" id="humedad" name="humedad" data-er="^[0-9]+(\.[0-9]{1,2})?$"/>
			</div>
			
			<label>Observaciones</label>
			<div data-linea="6">	
				<textarea id="observacion" name="observacion" rows="5" ></textarea>
			</div>	
			
		</fieldset>
				
		<button type="submit" class="guardar">Enviar resultado</button>		
	</form> 
	
	
</div>    

<script type="text/javascript">

	$(document).ready(function(){
		distribuirLineas();
		construirAnimacion($(".pestania"));	

		$("#fechaAnalisis").datepicker({
		    changeMonth: true,
		    changeYear: true
		});
		  
	});

	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

	
	$("#evaluarDocumentosSolicitud").submit(function(event){
		event.preventDefault();
		chequearCamposInspeccionDocumental(this);
	});

	$(".suma").change(function(event){
		$(".alertaCombo").removeClass("alertaCombo");
		var suma = 0;
		var buenaFermentacion = $("#buenaFermentacion").val();
		var ligeramenteFermentado = $("#ligeramenteFermentado").val();
		var granoVioleta = $("#granoVioleta").val();
		var granoPizarroso = $("#granoPizarroso").val();
		var mohos = $("#mohos").val();
		var danioInsecto = $("#danioInsecto").val();
		var vulnerado = $("#vulnerado").val();
		
		suma = Math.round((Number(buenaFermentacion) + Number(ligeramenteFermentado) + Number(granoVioleta) + Number(granoPizarroso) + Number(mohos) + Number(danioInsecto) + Number(vulnerado))*100)/100;
		if(suma > 100){
			$("#buenaFermentacion").addClass("alertaCombo");
			$("#ligeramenteFermentado").addClass("alertaCombo");
			$("#granoVioleta").addClass("alertaCombo");
			$("#granoPizarroso").addClass("alertaCombo");
			$("#mohos").addClass("alertaCombo");
			$("#danioInsecto").addClass("alertaCombo");
			$("#vulnerado").addClass("alertaCombo");

			$("#total").val(suma);
			
			$("#estado").html("Por favor revisar los campos ingresados, no se permite un valor superior a 100%").addClass("alerta");
			
		}else{
			$("#total").val(suma);
		}
		
	});

	

	function chequearCamposInspeccionDocumental(form){
		
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;
	
		if(!$.trim($("#fechaAnalisis").val())){
			error = true;
			$("#fechaAnalisis").addClass("alertaCombo");
		}

		if(!$.trim($("#vapor").val())){
			error = true;
			$("#vapor").addClass("alertaCombo");
		}

		if(!$.trim($("#muestraInspector").val())){
			error = true;
			$("#muestraInspector").addClass("alertaCombo");
		}

		if(!$.trim($("#contraMuestra").val())){
			error = true;
			$("#contraMuestra").addClass("alertaCombo");
		}

		if($("input:radio[name=tipoInspeccion]:checked").val() == null){
			error = true;
			$("#dTipoInspeccion label").addClass("alertaCombo");
			
		}

		if($("input:radio[name=nTipoCacaoVerificado]:checked").val() == null){
			error = true;
			$("#tipoCacaoNacional label").addClass("alertaCombo");
			$("#tipoCacaoCCN51 label").addClass("alertaCombo");
			
		}
		
		if(!$.trim($("#higiene").val())){
			error = true;
			$("#higiene").addClass("alertaCombo");
		}

		if(!$.trim($("#seguridadAlimenticia").val())){
			error = true;
			$("#seguridadAlimenticia").addClass("alertaCombo");
		}

		if(!$.trim($("#buenaFermentacion").val()) || !esCampoValido("#buenaFermentacion")){
			error = true;
			$("#buenaFermentacion").addClass("alertaCombo");
		}

		if(!$.trim($("#ligeramenteFermentado").val()) || !esCampoValido("#ligeramenteFermentado")){
			error = true;
			$("#ligeramenteFermentado").addClass("alertaCombo");
		}

		if(!$.trim($("#granoVioleta").val()) || !esCampoValido("#granoVioleta")){
			error = true;
			$("#granoVioleta").addClass("alertaCombo");
		}
		
		if(!$.trim($("#granoPizarroso").val()) || !esCampoValido("#granoPizarroso")){
			error = true;
			$("#granoPizarroso").addClass("alertaCombo");
		}

		if(!$.trim($("#mohos").val()) || !esCampoValido("#mohos")){
			error = true;
			$("#mohos").addClass("alertaCombo");
		}
		
		if(!$.trim($("#danioInsecto").val()) || !esCampoValido("#danioInsecto")){
			error = true;
			$("#danioInsecto").addClass("alertaCombo");
		}
		
		if(!$.trim($("#vulnerado").val()) || !esCampoValido("#vulnerado")){
			error = true;
			$("#vulnerado").addClass("alertaCombo");
		}

		if(!$.trim($("#multiple").val()) || !esCampoValido("#multiple")){
			error = true;
			$("#multiple").addClass("alertaCombo");
		}

		if(!$.trim($("#partido").val()) || !esCampoValido("#partido")){
			error = true;
			$("#partido").addClass("alertaCombo");
		}

		if(!$.trim($("#planoGranza").val()) || !esCampoValido("#planoGranza")){
			error = true;
			$("#planoGranza").addClass("alertaCombo");
		}

		if(!$.trim($("#vulnerado").val()) || !esCampoValido("#vulnerado")){
			error = true;
			$("#vulnerado").addClass("alertaCombo");
		}

		if(!$.trim($("#impureza").val()) || !esCampoValido("#impureza")){
			error = true;
			$("#impureza").addClass("alertaCombo");
		}

		if(!$.trim($("#materiaExtrania").val()) || !esCampoValido("#materiaExtrania")){
			error = true;
			$("#materiaExtrania").addClass("alertaCombo");
		}

		if(!$.trim($("#cacaoTrinitario").val()) || !esCampoValido("#cacaoTrinitario")){
			error = true;
			$("#cacaoTrinitario").addClass("alertaCombo");
		}

		if(!$.trim($("#pesoCacao").val()) || !esCampoValido("#pesoCacao")){
			error = true;
			$("#pesoCacao").addClass("alertaCombo");
		}
		
		if(!$.trim($("#numeroPepasCacao").val()) || !esCampoValido("#numeroPepasCacao")){
			error = true;
			$("#numeroPepasCacao").addClass("alertaCombo");
		}

		if(!$.trim($("#humedad").val()) || !esCampoValido("#humedad")){
			error = true;
			$("#humedad").addClass("alertaCombo");
		}
		
		if (error){
			$("#estado").html("Por favor revise la información ingresada.").addClass('alerta');
		}else{
			ejecutarJson(form);
		}
	}
</script>
