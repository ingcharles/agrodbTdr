<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorSeguimientoCuarentenario.php';

$conexion = new Conexion();
$csc = new ControladorSeguimientoCuarentenario();

$usuario = $_SESSION['usuario'];
$provincia=$_SESSION['nombreProvincia'];
$idDestinacionAduanera=$_POST['id'];

$qDestinacionAduanera = $csc->abrirDatosSADDA($conexion, $idDestinacionAduanera);
$resultadoSeguimientos=$csc->listarSeguimientoSADDA($conexion,$idDestinacionAduanera);
if(pg_num_rows($resultadoSeguimientos)!=0){
	$datosSeguimiento=pg_fetch_assoc($resultadoSeguimientos);
	$resultadoDetalleSeguimientos=$csc->listarDetalleSeguimientoSADDA($conexion,$datosSeguimiento['id_seguimiento_cuarentenario_sa']);
}

?>

<header>
	<h1>Nuevo Seguimiento</h1>
</header>
	<form id='nuevoSeguimientoCuarentenario' data-rutaAplicacion='seguimientoCuarentenario' data-opcion="guardarSeguimientoCuarentenarioSA"  >
		<input type="hidden" id="idDestinacionAduanera" name="idDestinacionAduanera" value="<?php echo $idDestinacionAduanera;	?>"/>
		<input type="hidden" id="idSeguimientoCuarentenarioSA" name="idSeguimientoCuarentenarioSA" value="<?php echo $datosSeguimiento['id_seguimiento_cuarentenario_sa'];	?>"/>
		<input type="hidden" id="contador" name="contador" value="0" />
		<input type="hidden" id="usuario" name="usuario" value="<?php echo $usuario; ?>"/>
		<input type="hidden" id="nombreProvincia" name="nombreProvincia" value="<?php echo $provincia;	?>"/>
		<input type="hidden" id="opcion" name="opcion" value="nuevo" />
		<input type="hidden" id="idDetalleSeguimientoCuarentenarioSa" name="idDetalleSeguimientoCuarentenarioSa" value="" />
		<input type="hidden" id="estadoSeguimiento" name="estadoSeguimiento" value="<?php echo $datosSeguimiento['estado']; ?>"/>
		<input type="hidden" id="cantidadProductoDisponible" name="cantidadProductoDisponible" value="<?php echo $qDestinacionAduanera[0]['cantidad'] ?>"/>
		<fieldset>
			<legend>Ubicación y Datos Generales del Predio Habilitado de Cuarentena</legend>
			<div data-linea="1">
				<label>Propietario: </label> 
				<input type="text" id="propietario" name="propietario" value="<?php echo $qDestinacionAduanera[0]['propietario'];	?>" disabled  />
			</div>
			
			<div data-linea="2">
				<label>Veterinario Autorizado: </label> 
				<input type="text" id="veterinarioAutorizado" name="veterinarioAutorizado" value="<?php echo $qDestinacionAduanera[0]['veterinarioAutorizado'];	?>" disabled />
			
			</div>
			
			<div data-linea="3">
				<label>Nombre del Predio: </label> 
				<input type="text" id="nombrePredio" name="nombrePredio" value="<?php echo $qDestinacionAduanera[0]['nombreSitio'];	?>" disabled  />
			</div>
			
			<div data-linea="3">
				<label>Provincia </label> 
				<input type="text" id="provincia" name="provincia" value="<?php echo $qDestinacionAduanera[0]['provincia'];	?>" disabled />
			</div>
			
			<div data-linea="4">
				<label>Cantón: </label> 
				<input type="text" id="canton" name="canton" value="<?php echo $qDestinacionAduanera[0]['canton'];	?>" disabled  />
			
			</div>
			
			<div data-linea="4">
				<label>Parroquia: </label> 
				<input type="text" id="parroquia" name="parroquia" value="<?php echo $qDestinacionAduanera[0]['parroquia'];	?>" disabled  />
			</div>
			
			<div data-linea="5">
				<label>Dirección: </label>
				<input type="text" id="direccion" name="direccion" maxlength="5" value="<?php echo $qDestinacionAduanera[0]['direccion']; ?>" disabled />
			</div>
			
			<div data-linea="5">
				<label>Fecha de Elaboración: </label>
				<input type="text" id="fechaElaboracion" name="fechaElaboracion" readOnly value="<?php echo $datosSeguimiento['fecha_elaboracion'];?>"  />
			</div>
		
			<fieldset >
				<legend >Coordenadas UTM</legend>
				
				<div data-linea="6" >
					<label>X:</label>
					<input type="text" id="coordenadaX" name="coordenadaX" maxlength="9" value="<?php echo $datosSeguimiento['coordenada_x']; ?>" data-er="^[0-9.-]+$" />
				</div>
				
				<div data-linea="6">
					<label>Y: </label> 
					<input type="text" id="coordenadaY" name="coordenadaY" maxlength="10" value="<?php echo $datosSeguimiento['coordenada_y']; ?>" data-er="^[0-9.-]+$"/>
				</div>
				
				<div data-linea="6">
					<label>Zona: </label>
					<input type="text" id="coordenadaZ" name="coordenadaZ" maxlength="3" value="<?php echo $datosSeguimiento['coordenada_z']; ?>" data-er="^[A-Za-z0-9]+$"  />
				</div>
				
			</fieldset>
		</fieldset>
		<fieldset>
			<legend>Acta de Inicio de Cuarentena</legend>
			<div data-linea="1">
				<label>Producto: </label>
				<input type="text" id="producto" name="producto"  value="<?php echo $qDestinacionAduanera[0]['nombreProducto'];	?>" disabled/>
			</div>
			<div data-linea="1">
				<label>Cantidad: </label>
				<input type="text" id="cantidad" name="cantidad" maxlength="4" value="<?php echo $qDestinacionAduanera[0]['cantidad'];	?>" disabled />
			</div>
			<div data-linea="2">
				<label>País de Orígen: </label>
				<input type="text" id="paisOrigen" name="paisOrigen" value="<?php echo $qDestinacionAduanera[0]['paisOrigen'];	?>" disabled />
			</div>
			<div data-linea="2" id="vistaFechaIngreso">
				<label>Fecha de Ingreso Ecuador: </label>
				<input type="text" id="fechaIngresoEcuador" name="fechaIngresoEcuador" readOnly value="<?php echo $qDestinacionAduanera[0]['fechaIngresoEcuador'];	?>"/>
			</div>
			<div data-linea="3">
				<label>Lote: </label>
				<input type="text" id="lote" name="lote" maxlength="8" value="<?php echo $datosSeguimiento['lote'];	?>" data-er="^[A-Za-z0-9]+$"/>
			</div>
			<div data-linea="3">
				<label>CZPM-M: </label>
				<input type="text" id="csmt" name="csmt" maxlength="13" value="<?php echo $datosSeguimiento['csmt']; ?>" data-er="^[A-Za-z0-9-]+$"/>
			</div>
			
			<div data-linea="4" id="vistaLabelInicioCuarentena">
				<label>Adjuntar Inicio de Cuarentena:</label> 
			</div>
		
			<div data-linea="4" id="vistaAIC">
				<label>A.I.C: </label>
				<input type="text" id="aic" name="aic" maxlength="8" value="<?php echo $datosSeguimiento['aic'];	?>" onKeyPress='ValidaSoloNumeros()' data-er="^[0-9]+$" />
			</div>
			
			<div data-linea="5" id="vistaAdjuntoInicioCuarentena">
				<label>Acta Inicio de Cuarentena: </label> <?php echo ($datosSeguimiento['ruta_inicio_cuarentena'] == ''? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$datosSeguimiento['ruta_inicio_cuarentena'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Descargar</a>')?>
			</div>
			<div data-linea="6" id="vistaInicioCuarentena">
				<input type="hidden" class="rutaArchivo" id="archivoInicioCuarentena"  name="archivoInicioCuarentena"  value="<?php echo $datosSeguimiento['ruta_inicio_cuarentena'];?>" />
				<input type="file" class="archivo"  id="informeInicioCuarentena"  name="informe" accept="application/pdf"/>
				<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
				<button type="button" class="subirArchivoInicioCuarentena adjunto" data-rutaCarga="aplicaciones/seguimientoCuarentenario/archivosInicioCuarentena" >Subir archivo</button>
			</div>
		</fieldset>
	
		<fieldset id="vistaSeguimientoAbierto" >
			<legend>Nuevo Seguimiento</legend>
				
			<div data-linea="1">
				<label>Sanos: </label> 
				<input type="text" id="sanos" name="sanos" value="0" readOnly onkeypress='ValidaSoloNumeros()' placeholder="Ej: 2" maxlength="5" data-er="^[0-9]+$" />
			</div>
			
			<div data-linea="1">
				<label>Enfermos: </label>
				<input type="text" id="enfermos" name="enfermos" value="0" readOnly onkeypress='ValidaSoloNumeros()' placeholder="Ej: 2" maxlength="5" data-er="^[0-9]+$" />
			</div>
			
			<div data-linea="1">
				<label>Muertos: </label>
				<input type="text" id="muertos" name="muertos" value="0" readOnly onkeypress='ValidaSoloNumeros()' placeholder="Ej: 2" maxlength="5" data-er="^[0-9]+$" />
			</div>
			
			<div data-linea="1">
				<label>Total: </label>
				<input type="text" id="total" name="total" value="0" readOnly onkeypress='ValidaSoloNumeros()' placeholder="Ej: 2" maxlength="5" data-er="^[0-9]+$" />
			</div>
		
			<table id="tablaItemsSeguimientos">
				<thead>
					<tr>
						<th>Identificación</th>
						<th>Cantidad</th>
						<th>Sexo</th>
						<th>Edad</th>
						<th>Sintomatología</th>	
						<th>Observaciones</th>
					</tr>
				</thead>
				<tbody id="tablaItemsDetalle" ></tbody>
			</table>
			<button type="button" style="" id="agregarFila" name="agregarFila" class="mas"></button>
			<hr/>
			
			<div data-linea="2">
				<label>Resultado Inspección: </label>
				<select id="resultadoInspeccion" name="resultadoInspeccion">
					<option value="">Seleccione...</option>
					<option value="Continuar cuarentena pos entrada">Continuar cuarentena pos entrada</option>
					<option value="Finalizar cuarentena pos entrada">Finalizar cuarentena pos entrada</option>
					<option value="Sacrificio sanitario">Sacrificio sanitario</option>
				</select>
			</div>
			
			<div data-linea="3" id="vistaSacrificioSanitario">
				<label>Adjuntar Acta Sacrificio Sanitario: </label>
				<input type="hidden" class="rutaArchivo" id="archivoSacrificioSanitario" name="archivoSacrificioSanitario" value="" />
				<input type="file" class="archivo" id="informeSacrificioSanitario"  name="informe" accept="application/pdf"/>
				<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
				<button type="button" class="subirArchivoSacrificioSanitario adjunto" data-rutaCarga="aplicaciones/seguimientoCuarentenario/archivosSacrificioSanitario" >Subir archivo</button>
			</div>
		</fieldset>
	</form>
	
	<div data-linea="3" style="width:100%; text-align: center;">
		<button  id="btnGuardar"  name="btnGuardar" class="guardar">Guardar</button>
	</div>
	
	<fieldset>
		<legend>Seguimientos Guardados</legend>
		<table id="seguimientoGuardados" width="100%">
			<thead>
				<tr>
					<th>#Seguimiento</th>
					<th>Fecha</th>
					<th>Total Mercancía Pecuario</th>
					<th>R.Inspección</th>
					<th>Abrir</th>
				</tr>
			</thead>
			<?php 
				$contador=1;
				while ($detalleSeguimiento = pg_fetch_assoc($resultadoDetalleSeguimientos)){
					echo $csc->imprimirLineaSeguimientosCuarentenariosSA($detalleSeguimiento['id_detalle_seguimientos_cuarentenarios_sa'],$contador++, $detalleSeguimiento['fecha_registro'], $detalleSeguimiento['cantidad_total_seguimiento'],$detalleSeguimiento['resultado_inspeccion']);
					$cantidadProductoDisponible = $detalleSeguimiento['cantidad_total_seguimiento'];
				}
			?>
		</table>
	</fieldset>

	<form id='nuevoSeguimientoCierre' data-rutaAplicacion='seguimientoCuarentenario'  data-opcion="guardarCierreCuarentenarioSA" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
	<input type="hidden" id="idDestinacionAduanera" name="idDestinacionAduanera" value="<?php echo $idDestinacionAduanera;	?>"/>
	<input type="hidden" id="usuarioCierre" name="usuarioCierre" value="<?php echo $usuario; ?>"/>
	<fieldset>
			<legend>Cierre Cuarentenario</legend>
			
			<div data-linea="1">
				<label>Fecha de Cierre: </label>
				<input type="text" id="fechaCierre" name="fechaCierre" readonly="readonly" value="<?php echo $datosSeguimiento['fecha_cierre'];?>" />
			</div>
			<div data-linea="2" id="vistaAdjuntoInformeLaboratorio">
				<label>Informe de Laboratorio: </label> <?php echo ($datosSeguimiento['ruta_informe_laboratorio'] == ''? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$datosSeguimiento['ruta_informe_laboratorio'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Descargar</a>')?>
			</div>
			<div data-linea="3" id="vistaInformeLaboratorio">
				<label>Adjuntar Informe de Laboratorio (opcional): </label>
				<input type="hidden" class="rutaArchivo" id="archivoInformeLaboratorio"  name="archivoInformeLaboratorio"  value="<?php echo $datosSeguimiento['ruta_informe_laboratorio'];?>" />
				<input type="file" class="archivo"  id="informeInformeLaboratorio"  name="informe" accept="application/pdf"/>
				<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
				<button type="button" class="subirArchivoInformeLaboratorio adjunto" data-rutaCarga="aplicaciones/seguimientoCuarentenario/archivosInformeLaboratorio" >Subir archivo</button>
			</div>
			
			<div data-linea="4" id="vistaAdjuntoLevantamientoCuarentena">
				<label>Acta Levantamiento de Cuarentena: </label> <?php echo ($datosSeguimiento['ruta_levantamiento_cuarentena'] == ''? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$datosSeguimiento['ruta_levantamiento_cuarentena'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Descargar</a>')?>
			</div>
			
			<div data-linea="5" id="vistaLevantamientoCuarentena">
				<label>Adjuntar Levantamiento de Cuarentena: </label>
				<input type="hidden" class="rutaArchivo" id="archivoLevantamientoCuarentena"  name="archivoLevantamientoCuarentena"  value="<?php echo $datosSeguimiento['ruta_levantamiento_cuarentena'];?>" />
				<input type="file" class="archivo"  id="informeLevantamientoCuarentena"  name="informe" accept="application/pdf"/>
				<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
				<button type="button" class="subirArchivoLevantamientoCuarentena adjunto" data-rutaCarga="aplicaciones/seguimientoCuarentenario/archivosLevantamientoCuarentena" >Subir archivo</button>
			</div>
	</fieldset>
	<button type="submit" id="btnCierre"  name="btnCierre" class="guardar" >Cierre</button>	
	</form>
<script type="text/javascript">
var estadoSeguimiento = <?php echo json_encode($datosSeguimiento['estado']);?>;
var identificadorOperador = <?php echo json_encode($qDestinacionAduanera[0]['identificadorOperador']);?>;
var tamanioArchivo = <?php echo json_encode(ini_get('upload_max_filesize'));?>;
var cantidadProductoDisponible = <?php echo json_encode($cantidadProductoDisponible);?>;

	$(document).ready(function(){
		if(estadoSeguimiento=='cerrado'){
			$("#vistaAdjuntoInformeLaboratorio").show();
			$("#vistaAdjuntoLevantamientoCuarentena").show();
			$("#vistaInformeLaboratorio").hide();
			$("#vistaLevantamientoCuarentena").hide();
			$(".icono").toggleClass('icono iconoInactivo');
			$("#fechaCierre").attr('disabled','disabled');
			$("#btnCierre").attr('disabled','disabled');
		}else{
			$("#vistaAdjuntoInformeLaboratorio").hide();
			$("#vistaAdjuntoLevantamientoCuarentena").hide();
			$("#vistaInformeLaboratorio").show();
			$("#vistaLevantamientoCuarentena").show();
		}
		
		$("#fechaElaboracion").datepicker({
		      changeMonth: true,
		      changeYear: true,
		      maxDate: "0"
		}).datepicker("setDate", new Date());

		$("#fechaIngresoEcuador").datepicker({
		      changeMonth: true,
		      changeYear: true,
		      maxDate: "0"
		});	

		$("#fechaCierre").datepicker({
		      changeMonth: true,
		      changeYear: true,
		      maxDate:"0"
		});
		
		acciones("#nuevoSeguimientoCuarentenario","#seguimientoGuardados",null,null, new exitoIngresoSeguimiento());
		if($("#idSeguimientoCuarentenarioSA").val()!=''){
			desabilitarCamposCabecera();
			$("#vistaLabelInicioCuarentena").hide();
			$("#vistaInicioCuarentena").hide();
		}else{
			$("#vistaAdjuntoInicioCuarentena").hide();
		}

		$('#vistaSacrificioSanitario').hide();

		if($("#opcion").val()=='nuevo'){
			var valorSeguimiento=$("#seguimientoGuardados tbody tr:last-child .resutadoInspeccion").html();

			if(valorSeguimiento=='Finalizar cuarentena pos entrada'){
				$("#btnGuardar,#agregarFila").attr('disabled',true);
			}
		}
		distribuirLineas();
		if(cantidadProductoDisponible){
			$("#cantidadProductoDisponible").val(cantidadProductoDisponible);
		}
		
	});

	function exitoIngresoSeguimiento(){
		this.ejecutar = function(msg){
	
			if($("#opcion").val()=='modificar'){
				$("#opcion").val('nuevo');
				$("#btnGuardar").html('Guardar');
				$("#agregarFila").attr('disabled',false);
				$("#btnCierre").attr('disabled',false);
				$("#vistaSeguimientoAbierto legend").html("Nuevo Seguimiento");
				var valorSeguimiento=$("#seguimientoGuardados tbody tr:last-child .resutadoInspeccion").html();

				if(valorSeguimiento!='Sacrificio sanitario'){
					$("#archivoSacrificioSanitario").val('');
				}
				
				$("#resultadoInspeccion option[value='Finalizar cuarentena pos entrada']").prop("disabled",false);
				mostrarMensaje("El registro ha sido actualizado","EXITO");
				var idDetalle = msg.idDetalle;
				$("#seguimientoGuardados tbody tr[id='R"+idDetalle+"']").remove();
			}else{
				mostrarMensaje("Nuevo registro agregado","EXITO");
			}
			
			var fila = msg.mensaje;
			$("#seguimientoGuardados").append(fila);
			
			$("#seguimientoGuardados tbody tr").each(function(){
				$(this).find("#ultimoRegistro").val('no');
			});
			
			$("#seguimientoGuardados tbody tr:last-child #ultimoRegistro").val('si');
			if ($('#seguimientoGuardados tbody tr').length==1){
				$("#seguimientoGuardados tbody tr #ultimoRegistro").val('si');
				desabilitarCamposCabecera();
			}
			
			$("#nuevoSeguimientoCuarentenario #archivoSacrificioSanitario").parent().find(".archivo").removeClass('verde');
			$("#nuevoSeguimientoCuarentenario #archivoSacrificioSanitario").parent().find(".estadoCarga").html("En espera de archivo... (Tamaño máximo "+ tamanioArchivo +"B)");
			$("#nuevoSeguimientoCuarentenario fieldset input[id='sanos'],[id='enfermos'],[id='muertos'],[id='total']").val(0);
			$("#nuevoSeguimientoCuarentenario fieldset input[id='archivoSacrificioSanitario'],[id='resultadoInspeccion']").val("");
			$("#nuevoSeguimientoCuarentenario fieldset select[id='resultadoInspeccion']").val("");
			$("#nuevoSeguimientoCuarentenario fieldset #tablaItemsSeguimientos tbody").html('');
		};
	}

	function desabilitarCamposCabecera(){
			$("#fechaElaboracion").attr('disabled',true);
			$("#coordenadaX").attr('disabled',true);
			$("#coordenadaY").attr('disabled',true);
			$("#coordenadaZ").attr('disabled',true);
			$("#lote").attr('disabled',true);
			$("#csmt").attr('disabled',true);
			$("#aic").attr('disabled',true);
			$("#informeInicioCuarentena").attr('disabled',true);
			$("#fechaElaboracion").attr('disabled',true);
			$("#fechaIngresoEcuador").attr('disabled',true);
			$("#vistaAIC").attr('data-linea','5');
			$("#vistaAdjuntoInicioCuarentena").attr('data-linea','7');
	}

	$("#nuevoSeguimientoCierre").submit(function(event){
		event.preventDefault();	
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;
		
		if ($("#fechaCierre").val()==""){
			 error = true;	
			 $("#fechaCierre").addClass("alertaCombo");
			 mostrarMensaje("Por favor ingrese la fecha de cierre cuarentenario","FALLO");
				
		}
		
		if ($("#archivoLevantamientoCuarentena").val()==""){
			 error = true;	
			 $("#informeLevantamientoCuarentena").addClass("alertaCombo");
			 mostrarMensaje("Por favor adjunte el archivo del levantamiento de cuarentena","FALLO");
		}

		if($("#opcion").val()=='nuevo'){
			var valorSeguimiento=$("#seguimientoGuardados tbody tr:last-child .resutadoInspeccion").html();
			if(valorSeguimiento!='Finalizar cuarentena pos entrada'){
				error = true;	
				$("#seguimientoGuardados tbody tr:last-child").addClass("alertaCombo");
				mostrarMensaje("Para realizar el cierre cuarentenario es necesario que el ultimo registro del seguimiento sea ( Finalizar cuarentena pos entrada )","FALLO");
			}
		}
		if(!error){
			ejecutarJson(this);
		}
		
	});
	
	$("#btnGuardar").click(function(event){
			event.preventDefault();	
			$(".alertaCombo").removeClass("alertaCombo");
			var error = false;

			if ($('#tablaItemsSeguimientos tbody tr').length==0){
				 error = true;	
				 $("#tablaItemsSeguimientos tbody").addClass("alertaCombo");
				 $("#estado").html('Para guardar el seguimientos es necesario agregar registros de los productos.').addClass("alerta");
			}

			if ($("#resultadoInspeccion").val()==""){
				 error = true;	
				 $("#resultadoInspeccion").addClass("alertaCombo");
				 $("#estado").html('Por favor seleccione el resultado de inspección.').addClass("alerta");
			}
			
			$('#tablaItemsSeguimientos tbody tr').each(function(){
				if($(this).find('select[id="dDuracion"] option:selected').val()==""){
					error = true;
					$(this).find('select[id="dDuracion"]').addClass("alertaCombo");
					mostrarMensaje("Por favor seleccione la opción para la edad del producto","FALLO");
				}
				
				if($(this).find('input[id="dEdad"]').val()==""){
					error = true;
					$(this).find('input[id="dEdad"]').addClass("alertaCombo");
					mostrarMensaje("Por favor ingrese el número en la edad del producto","FALLO");
				}
				
				if($(this).find('input[id="dCantidad"]').val()==0){
					error = true;
					$(this).find('input[id="dCantidad"]').addClass("alertaCombo");
					mostrarMensaje("Por favor ingrese una cantidad diferente de 0","FALLO");
				}

				if($(this).find('input[id="dIdentificacion"]').val()==0){
					error = true;
					$(this).find('input[id="dIdentificacion"]').addClass("alertaCombo");
					mostrarMensaje("Por favor ingrese la identificación del producto","FALLO");
				}
				
			});
			
			
			if($('#resultadoInspeccion option:selected').text()=='Sacrificio sanitario'){
				if ($("#archivoSacrificioSanitario").val()==""){
					 error = true;	
					 $("#informeSacrificioSanitario").addClass("alertaCombo");
					 $("#estado").html('Por favor adjunte el archivo del sacrificio sanitario.').addClass("alerta");
				}
			}
			
			if ($("#archivoInicioCuarentena").val()==""){
				 error = true;	
				 $("#informeInicioCuarentena").addClass("alertaCombo");
				 $("#estado").html('Por favor adjunte el archivo del inicio de cuarentena.').addClass("alerta");
			}
			
			if($("#fechaIngresoEcuador").val() == '' || !esCampoValido("#fechaIngresoEcuador")){	
				error = true;		
				$("#fechaIngresoEcuador").addClass("alertaCombo");
				$("#estado").html('Por favor ingrese el número de seguimientos planificados.').addClass("alerta");
			}

			if($("#aic").val() ==''  || !esCampoValido("#aic")){	
				error = true;		
				$("#aic").addClass("alertaCombo");
				$("#estado").html('Por favor revise o ingrese el número de A.I.C').addClass("alerta");
			}

			if($("#csmt").val() =='' || !esCampoValido("#csmt")){	
				error = true;		
				$("#csmt").addClass("alertaCombo");
				$("#estado").html('Por favor revise o ingrese  el número de CSMT').addClass("alerta");
			}

			if($("#coordenadaZ").val() ==''  || !esCampoValido("#coordenadaZ")){	
				error = true;		
				$("#coordenadaZ").addClass("alertaCombo");
				$("#estado").html('Por favor ingrese la coordenada UTM en Z').addClass("alerta");
			}

			if($("#coordenadaY").val() ==''  || !esCampoValido("#coordenadaY")){	
				error = true;		
				$("#coordenadaY").addClass("alertaCombo");
				$("#estado").html('Por favor ingrese la coordenada UTM en Y').addClass("alerta");
			}

			if($("#coordenadaX").val() ==''  || !esCampoValido("#coordenadaX")){	
				error = true;		
				$("#coordenadaX").addClass("alertaCombo");
				$("#estado").html('Por favor ingrese la coordenada UTM en X').addClass("alerta");
			}
			
			if($("#fechaElaboracion").val() ==''  || !esCampoValido("#fechaElaboracion")){	
				error = true;		
				$("#fechaElaboracion").addClass("alertaCombo");
				$("#estado").html('Por favor ingrese la fecha de elaboración.').addClass("alerta");
			}

			if (!error) {
				cantidadTotal = parseInt($("#sanos").val()) + parseInt($("#enfermos").val()) + parseInt($("#muertos").val());
				if(cantidadTotal != $("#cantidadProductoDisponible").val()){
					error = true;
					$("#sanos").addClass("alertaCombo");
					$("#enfermos").addClass("alertaCombo");
					$("#muertos").addClass("alertaCombo");
					mostrarMensaje("La cantidad total del producto ingresado en el seguimiento debe ser igual a la cantidad del toal de mercancía pecuaria disponible ","FALLO");
				}
			}

			if (!error) {
				var numItems=$("#seguimientoGuardados tbody tr:last-child .contador").html();
				if(isNaN(numItems)){
					numItems=0;
				}else{
					if($("#opcion").val()=='modificar'){
						numItems=parseInt(numItems);
					}else{
						numItems=parseInt(numItems)+1;
					}
				}
				$("#contador").val(numItems);
				$("#cantidadProductoDisponible").val($("#total").val());
				
			    $("#nuevoSeguimientoCuarentenario").submit();
			} else {
			    return false;
			}
	});
	
	function ValidaSoloNumeros() {
		if ((event.keyCode < 48) || (event.keyCode > 57))		 
		event.returnValue = false;
	}
	
	$('button.subirArchivoInicioCuarentena, button.subirArchivoInformeLaboratorio ,button.subirArchivoLevantamientoCuarentena').click(function (event) {

		numero = Math.floor(Math.random()*100000000);	
	    var boton = $(this);
        var archivo = boton.parent().find(".archivo");
        var rutaArchivo = boton.parent().find(".rutaArchivo");
        var extension = archivo.val().split('.');
     
        var estado = boton.parent().find(".estadoCarga");

        if (extension[extension.length - 1].toUpperCase() == 'PDF') {
	        	
        		subirArchivo(
    	                archivo
    	                , identificadorOperador+'_'+numero
    	                , boton.attr("data-rutaCarga")
    	                , rutaArchivo
    	                , new carga(estado, archivo, boton)
    	              
    	            );
	            
        } else {
            estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
            archivo.val("");
        }
    });

	$('button.subirArchivoSacrificioSanitario').click(function (event) {

		numero = Math.floor(Math.random()*100000000);	
		
        var boton = $(this);
        var archivo = boton.parent().find(".archivo");
        var rutaArchivo = boton.parent().find(".rutaArchivo");
        var extension = archivo.val().split('.');
        var estado = boton.parent().find(".estadoCarga");

        if (extension[extension.length - 1].toUpperCase() == 'PDF') {
	        	
        		subirArchivo(
    	                archivo
    	                , identificadorOperador+'_'+numero
    	                , boton.attr("data-rutaCarga")
    	                , rutaArchivo
    	                , new carga(estado, archivo, $("#vacio"))
    	                
    	            );
	            
        } else {
            estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
            archivo.val("");
        }
    });
    
	var contador=0;
	$("#agregarFila").on("click",function(event){
		$(".alertaCombo").removeClass("alertaCombo");
	 	var error = false;
				
		if($("#opcion").val()=='nuevo'){
			var valorSeguimiento=$("#seguimientoGuardados tbody tr:last-child .resutadoInspeccion").html();
			if(valorSeguimiento=='Finalizar cuarentena pos entrada'){
				error = true;	
				 $("#seguimientoGuardados tbody tr:last-child .resutadoInspeccion").addClass("alertaCombo");
				 mostrarMensaje("No es posible realizar más seguimiento por el último resultado fue ( Finalizar cuarentena pos entrada )","FALLO");
			}	
		}else{
			 error = true;
			 mostrarMensaje("No es posible agregar más filas cuando se está modificando un registro","FALLO");
				
		}
			
		$('#tablaItemsSeguimientos tbody tr #dCantidad').each(function(){
			if($(this).val()==0 || $(this).val()==''){
				error = true;
				mostrarMensaje("No es posible agregar otro fila hasta que la anterior sea llenada","FALLO");
			}
		});

		if(parseInt($('#total').val())>=parseInt($('#cantidadProductoDisponible').val())){
			error = true;
			mostrarMensaje("No es posible agregar mas fila porque se ha completado la cantidad total de productos","FALLO");
		}

	   	if (!error){
	   		contador=contador+1;
	    	$("#tablaItemsSeguimientos tbody").append("<tr id='r_"+contador+"'><td><input type='text' name='dIdentificacion[]' id='dIdentificacion' style='width: 70%;'></td>"+
		    	'<td><input type="text" id="dCantidad" name="dCantidad[]" value="0" style="width: 50%;" onkeypress="ValidaSoloNumeros()" placeholder="Ej: 2" maxlength="7" data-er="^[0-9]+$"></td>'+
	            '<td><select id="dSexo" name="dSexo[]" style="width:90%">'+
				'<option value="N/A">N/A</option>'+
				'<option value="Macho">Macho</option>'+
				'<option value="Hembra">Hembra</option>'+
	        	'</select></td>'+
				'<td><input type="text" id="dEdad" name="dEdad[]" style="width:16%" onkeypress="ValidaSoloNumeros()" placeholder="Ej: 2" maxlength="4" data-er="^[0-9]+$">'+
				'<select id="dDuracion" name="dDuracion[]" style="width:40%">'+
				'<option value="">Seleccione...</option>'+
				'</select></td>'+
	        	'<td><select id="dSintomatologia" name="dSintomatologia[]" style="width:90%">'+
	        	'<option value="Ninguna">Ninguna</option>'+
				'<option value="Síndrome respiratorio">Síndrome respiratorio</option>'+
				'<option value="Síndrome digestivo">Síndrome digestivo</option>'+
				'<option value="Síndrome reproductivo">Síndrome reproductivo</option>'+
				'<option value="Síndrome neurológico">Síndrome neurológico</option>'+
				'<option value="Muertos">Muertos</option>'+
				'<option value="Otros">Otros</option>'+
				'</select></td>'+
				'<td><input type="text" id="dObservacion" name="dObservacion[]" style="width: 70%;"></td>'+
			'</tr>');
		}	
	});
	
	$("#tablaItemsSeguimientos").on("change","#dCantidad, #dSintomatologia",function (event){
		sumarCantidades(this);
	});

	$("#seguimientoGuardados tbody tr:last-child").on("click",function (event){
		$(this).find("#ultimoRegistro").val('si');
	});
	
	function sumarCantidades(campoCantidad){
		
		var valorNoSuma=parseInt($(campoCantidad).parents('tr').find('#dCantidad').val());
		var valorSintomatologia=$(campoCantidad).parents('tr').find('#dSintomatologia option:selected').val();

		if($(campoCantidad).parents('tr').find('#dCantidad').val()=='' || valorNoSuma>parseInt($('#cantidadProductoDisponible').val())){
			$(campoCantidad).parents('tr').find('#dCantidad').val(0);
			valorNoSuma=0;
		}
		var sumaSanos=0;
		var sumaEnfermos=0;
		var sumaMuertos=0;
		var sumaTotal=0;
    	$('#tablaItemsSeguimientos tbody tr').each(function(){
	 		var cantidad=parseInt($(this).find('input[id="dCantidad"]').val());
	 		var sintomatologia=$(this).find('select[id="dSintomatologia"] option:selected').val();

	 		if(sintomatologia=='Ninguna'){
		 		sumaSanos+=cantidad;
	 		}else if(sintomatologia=='Síndrome respiratorio' || sintomatologia=='Síndrome digestivo' || sintomatologia=='Síndrome reproductivo' || sintomatologia=='Síndrome neurológico' || sintomatologia=='Otros'){
	 			sumaEnfermos+=cantidad;
	 		}else if(sintomatologia=='Muertos'){
	 			sumaMuertos+=cantidad;
	 		}
	 	}); 

    	sumaTotal=sumaSanos+sumaEnfermos;

    	if(sumaTotal>parseInt($('#cantidadProductoDisponible').val())){
	       	if(valorSintomatologia=='Ninguna'){
				sumaSanos-=valorNoSuma;
	 		}else if(valorSintomatologia=='Síndrome respiratorio' || valorSintomatologia=='Síndrome digestivo' || valorSintomatologia=='Síndrome reproductivo' || valorSintomatologia=='Síndrome neurológico' || valorSintomatologia=='Otros'){
	 			sumaEnfermos-=valorNoSuma;
	 		}else if(valorSintomatologia=='Muertos'){
	 			sumaMuertos-=valorNoSuma;
	 		}
	 		sumaTotal-=valorNoSuma;
    		$(campoCantidad).parents('tr').find('#dCantidad').val(0);
    		mostrarMensaje("No se puede sobrepasar la cantidad total de producto disponible","FALLO");
    	}else{
    		mostrarMensaje("","EXITO");
    	}

    	$('#sanos').val(sumaSanos);
		$('#enfermos').val(sumaEnfermos);
		$('#muertos').val(sumaMuertos);
		$('#total').val(sumaTotal);
 	}

	$('#resultadoInspeccion').change(function(event) {
		if($('#resultadoInspeccion option:selected').text()=='Sacrificio sanitario'){
			$('#vistaSacrificioSanitario').show();
		}else{
			$('#archivoSacrificioSanitario').val("");
			$('#vistaSacrificioSanitario').hide();
		}
	});

	$("#tablaItemsSeguimientos").on("change"," #dEdad",function (event){
		var valorSelect=$(this).siblings();
		if($(this).val() == 0){
			valorSelect.html("");
			valorSelect.append("<option value=''>Seleccione...</option>");
			valorSelect.append("<option value='Días'>Días</option>");
			valorSelect.append("<option value='Meses'>Meses</option>");
			valorSelect.append("<option value='Años'>Años</option>");
		}else if($(this).val() == 1){
			valorSelect.html("");
			valorSelect.append("<option value=''>Seleccione...</option>");
			valorSelect.append("<option value='Día'>Día</option>");
			valorSelect.append("<option value='Mes'>Mes</option>");
			valorSelect.append("<option value='Año'>Año</option>");
		}else if($(this).val() > 1){
			valorSelect.html("");
			valorSelect.append("<option value=''>Seleccione...</option>");
			valorSelect.append("<option value='Días'>Días</option>");
			valorSelect.append("<option value='Meses'>Meses</option>");
			valorSelect.append("<option value='Años'>Años</option>");
		}
	});
</script>