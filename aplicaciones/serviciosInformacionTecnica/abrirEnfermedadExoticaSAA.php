<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorCatalogos.php';
	require_once '../../clases/ControladorServiciosInformacionTecnica.php';
	
	$conexion = new Conexion();
	$cc = new ControladorCatalogos();
	$csit = new ControladorServiciosInformacionTecnica();
	
	$idEnfermedadExotica=$_POST['id'];
	$qEnfermedad=$csit->abrirEnfermedadExotica($conexion, $idEnfermedadExotica);
	$enfermedad= pg_fetch_assoc($qEnfermedad);
	
	$tipoProducto=$csit->buscarTipoProductoEnfermedad($conexion, $enfermedad['id_enfermedad']);
	$qSubTipoProducto=$csit->buscarSubTipoProductoEnfermedad($conexion, $enfermedad['id_enfermedad']);
	while($fila = pg_fetch_assoc($qSubTipoProducto)){
		$subtipoProducto[]= array(idsubtipoProducto=>$fila['id_subtipo_producto'], nombre=>$fila['nombre'], idTipoProducto=>$fila['id_tipo_producto']);
	}
	
	$usuarioResponsable=$_SESSION['usuario'];
	
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>
	<header>
		<h1>Modificar Enfermedad Exótica</h1>
	</header>
	<div id="estado"></div>
	<div class="pestania">
	<form id="nuevoEnfermedadesExoticas" data-rutaAplicacion="serviciosInformacionTecnica" data-opcion="actualizarEnfermedadExoticaSAA">
		<input type="hidden" id="usuarioResponsable" name="usuarioResponsable" value="<?php echo $usuarioResponsable;?>" />
		<input type="hidden" id="idEnfermedadExotica" name="idEnfermedadExotica" value="<?php echo $idEnfermedadExotica;?>" /> 
		<input type="hidden" id="nombreEnfermedad" name="nombreEnfermedad" value="<?php echo $enfermedad['nombre_enfermedad'];?>" /> 
		
		<fieldset>
			<legend>Enfermedades Exóticas Reportadas y Vigencia</legend>
				<div data-linea="1" >
					<label>Enfermedad: </label>
					<select id="enfermedad" name="enfermedad"  disabled="disabled">
						<option value="">Seleccione...</option>
						<?php 
							$qEnfermedad=$cc->listarEnfermedadesAnimales($conexion);
							while($fila=pg_fetch_assoc($qEnfermedad)){
								echo '<option value="'.$fila['id_enfermedad'].'">' . $fila['nombre'] . '</option>';
							}
						?>
					</select>
				</div>
				<div data-linea="2">
					<label>Inicio Vigencia:</label> 
						<input type="text" id="inicioVigencia" name="inicioVigencia" readonly value="<?php echo $enfermedad['inicio_vigencia'];?>"  disabled="disabled" /> 
				</div>
				<div data-linea="2">
					<label>Fin Vigencia:</label> 
						<input type="text" id="finVigencia" name="finVigencia" readonly value="<?php echo $enfermedad['fin_vigencia'];?>"  disabled="disabled" /> 
				</div>
				<div data-linea="3">
					<label>Observaciones:</label> 
				</div>
				<div data-linea="4">
					<textarea rows="4" cols="50" id="observacion" name="observacion" maxlength="512" disabled="disabled" ><?php echo $enfermedad['observacion'];?></textarea>
				</div>
				<div data-linea="5" >
					<label>Activo: </label>
					<select id="estadoEnfermedad" name="estadoEnfermedad"  disabled="disabled" >
					<option value="activo">Activo</option>
					<option value="inactivo">Inactivo</option>
					</select>
				</div>
				<p>
					<button id="modificar" type="button" class="editar">Modificar</button>
					<button id="actualizar" type="submit" class="guardar" disabled="disabled">Guardar</button>
				</p>
		</fieldset>
	</form>
	<fieldset>
		<legend>Localización</legend>
		<form id="nuevoEnfermedadesLocalizacion" data-rutaAplicacion="serviciosInformacionTecnica" data-opcion="guardarEnfermedadExoticaLocalizacionSAA" data-destino="detalleItem">
			<input type="hidden" id="usuarioResponsable" name="usuarioResponsable" value="<?php echo $usuarioResponsable;?>" />
			<input type="hidden" id="idEnfermedadExotica" name="idEnfermedadExotica" value="<?php echo $idEnfermedadExotica;?>" /> 
			<input type="hidden" id="opcionL" name="opcionL" />
			<input type="hidden" id="nombreZona" name="nombreZona"  />
			<input type="hidden" id="nombrePais" name="nombrePais"  />
			<div data-linea="1">
				<label>Zona de Origen: </label>
				<select name="zona" id="zona" >
					<option value="">Seleccione...</option>
					<?php
					$qListarZonas=$cc->listarZonas($conexion);
					while($fila=pg_fetch_assoc($qListarZonas)){
						echo '<option value="'.$fila['id_zona'].'">'. $fila['nombre'] . '</option>';
					}
					?>
				</select>
			</div>
			<div data-linea="2" id="resultadoPais">
				<label>País: </label>
				<select id="pais" name="pais"  >
				<option value="">Seleccione...</option>
				</select>
			</div>
			<div data-linea="3">
				<button type="submit" id="agregarDetalleLocalizacion" class="mas" >Agregar Pais</button>
			</div>
		</form>
		<table id="detalleLocalizacion" style="width:100%"  class="tablaMatriz">
		<?php 
			$qProducto=$csit->listaEnfermedadExoticaLocalizacion($conexion, $idEnfermedadExotica);
			while ($fila = pg_fetch_assoc($qProducto)){
				echo $csit->imprimirLineaEnfermedadesExoticasLocalizacion($fila['id_enfermedad_localizacion'], $fila['nombre_zona'],$fila['nombre_pais'],$usuarioResponsable);
			}
		?>
		</table>
	</fieldset>	
	<fieldset>
		<legend>Requerimientos de Revisión/Ingreso</legend>
		<form id="nuevoEnfermedadesRequerimiento" data-rutaAplicacion="serviciosInformacionTecnica" data-opcion="guardarEnfermedadExoticaSAA" data-destino="detalleItem">
			<input type="hidden" id="usuarioResponsable" name="usuarioResponsable" value="<?php echo $usuarioResponsable;?>" />
			<input type="hidden" id="idEnfermedadExotica" name="idEnfermedadExotica" value="<?php echo $idEnfermedadExotica;?>" /> 
			<input type="hidden" id="nombreTipoRequerimiento" name="nombreTipoRequerimiento"  />
			<input type="hidden" id="nombreElementoRevision" name="nombreElementoRevision"  />
			<input type="hidden" id=opcionR name="opcionR"  />
			<div data-linea="1" >
				<label>Tipo: </label>
				<select name="tipoRequerimiento" id="tipoRequerimiento"	>
					<option value="">Seleccione...</option>
					<?php
						$qLocalizacion=$cc->listarRequerimientoRevisionIngreso($conexion);
						while($fila=pg_fetch_assoc($qLocalizacion)){
							echo '<option value="'.$fila['id_requerimiento'].'">'. $fila['nombre'] . '</option>';
						}
					?>
				</select>
			</div>
			<div data-linea="2" id="resultadoElemento" >
				<label>Requerimiento: </label>
				<select id="elementoRevision" name="elementoRevision"  >
				<option value="">Seleccione...</option>
				</select>
			</div>
			<div data-linea="3">
				<button type="submit" id="agregarDetalleRequerimiento" class="mas" >Agregar Requerimiento</button>
			</div>
		</form>
		<table id="detalleRequerimiento" style="width:100%" class="tablaMatriz">
		<?php 
			$qRequerimiento=$csit->listaEnfermedadExoticaRequerimiento($conexion, $idEnfermedadExotica);
			while ($fila = pg_fetch_assoc($qRequerimiento)){
				echo $csit->imprimirLineaEnfermedadesExoticasRequerimiento($fila['id_enfermedad_requerimiento'], $fila['nombre_requerimiento'], $fila['nombre_elemento_revision'],$usuarioResponsable);
			}
		?>
		</table>
	</fieldset>	
	</div>
	<div class="pestania"  >
	<form id="nuevoEnfermedadesProducto" data-rutaAplicacion="serviciosInformacionTecnica" >
		<input type="hidden" id="idEnfermedadC" name="idEnfermedadC" value="<?php echo $enfermedad['id_enfermedad'];?>">
		<input type="hidden" id="idEnfermedadExotica" name="idEnfermedadExotica" value="<?php echo $idEnfermedadExotica;?>">
		<input type="hidden" id="usuarioResponsable" name="usuarioResponsable" value="<?php echo $usuarioResponsable;?>" />
		<input type="hidden" id="usuarioResponsable" name="usuarioResponsable" value="<?php echo $usuarioResponsable;?>" />
		
		<input type="hidden" id="opcionP" name="opcionP"  />
		
		<fieldset>
			<legend>Selección de Productos</legend>
			<div data-linea="1">			
				<label>Tipo de producto: </label> 
				<select id="tipoProducto" name="tipoProducto" >
					<option value="">Seleccione...</option>
					<?php 
						while ($fila = pg_fetch_assoc($tipoProducto)){
							$opcionesTipoProducto[] =  '<option value="'.$fila['id_tipo_producto']. '" data-grupo="'. $fila['id_area'] . '">'. $fila['nombre'] .'</option>';
						}
					?>
				</select>				
			</div>
			<div data-linea="2">			
				<label>Subtipo de Producto: </label>
				<select id="subtipoProducto" name="subtipoProducto" >
					<option value="">Seleccione...</option>
				</select>
			</div>	
			<div data-linea="3">
				<div id="dProducto"></div>			
			</div>	
			<div data-linea="4">
				<button type="submit" id="agregarDetalleProducto" class="mas" >Agregar</button>
			</div>
		</fieldset>
	</form>
	<fieldset>
		<legend>Productos Agregados</legend>
		<table id="detalleProducto" style="width:100%"  class="tablaMatriz">
		<thead>
		<tr>
			<th>Producto</th>
			<th>N° Partida</th>
			<th>Estado</th>
			<th>Eliminar</th>
		</tr>
		</thead>
		<?php 
			$qEnfermedad=$csit->listaEnfermedadExoticaProducto($conexion, $idEnfermedadExotica);
			while ($fila = pg_fetch_assoc($qEnfermedad)){
				echo $csit->imprimirLineaEnfermedadExoticaProducto($fila['id_enfermedad_producto'], $fila['nombre_producto'],$fila['estado'],$usuarioResponsable,$idEnfermedadExotica,$fila['partida_arancelaria']);
			}
		?>
		</table>
	</fieldset>	
	</div>
</body>

<script type="text/javascript">
		
	var array_opcionesTipoProducto = <?php echo json_encode($opcionesTipoProducto);?>;
	var array_subTipoProducto = <?php echo json_encode($subtipoProducto);?>;

	$(document).ready(function(){
		distribuirLineas();
		construirValidador();
		cargarValorDefecto("enfermedad","<?php echo $enfermedad['id_enfermedad'];?>");
		cargarValorDefecto("estadoEnfermedad","<?php echo $enfermedad['estado'];?>");
		acciones("#nuevoEnfermedadesLocalizacion","#detalleLocalizacion",null,null,new exitoIngresoo(),null,null,new validarInputs());
		acciones("#nuevoEnfermedadesRequerimiento","#detalleRequerimiento",null,null,new exitoIngresooRequerimiento(),null,null,new validarInputsRequerimiento());
		accionesProducto("#nuevoEnfermedadesProducto","#detalleProducto");
		for(var i=0; i<array_opcionesTipoProducto.length; i++){
			$('#tipoProducto').append(array_opcionesTipoProducto[i]);
  		}
		construirAnimacion($(".pestania"));	
	});

	$("#tipoProducto").change(function(){
		$("#estado").html("").removeClass("alerta");
		$(".alertaCombo").removeClass("alertaCombo");
		if($("#tipoProducto").val()==''){
			$("#tipoProducto").addClass("alertaCombo");
			$("#estado").html("Por favor seleccione un tipo de producto.").addClass("alerta");
			$("#subtipoProducto").html('<option value="">Seleccione...</option>');
		}else{
			subTipo = '<option value="">Seleccione...</option>';
			for(var i=0; i<array_subTipoProducto.length; i++){
		    	if (array_subTipoProducto[i]['idTipoProducto'] == $("#tipoProducto").val())
		    		subTipo += '<option value="'+array_subTipoProducto[i]['idsubtipoProducto']+'">'+array_subTipoProducto[i]['nombre']+'</option>';
			}
			$('#subtipoProducto').html(subTipo);
		}
	});

	$("#subtipoProducto").change(function(event){
		$("#estado").html("").removeClass("alerta");
		$(".alertaCombo").removeClass("alertaCombo");
 		$("#nuevoEnfermedadesProducto").attr('data-destino','dProducto');
 		$("#nuevoEnfermedadesProducto").attr('data-opcion', 'combosServicios');
 		$("#opcionP").val('enfermedadProducto');
 		if($("#subtipoProducto").val() == ''){
 			$("#subtipoProducto").addClass("alertaCombo");
			$("#estado").html("Por favor seleccione un subtipo de producto.").addClass("alerta");
		}else{	 
			event.stopImmediatePropagation();	 	 	
 	 		abrir($("#nuevoEnfermedadesProducto"),event,false);
 	 		$("#nuevoEnfermedadesProducto").removeAttr('data-destino');
	 		$("#nuevoEnfermedadesProducto").attr('data-opcion', 'guardarEnfermedadExoticaProductoSAA');
		}
	 });
	 
	function validarInputs() {
		var msj;
		this.ejecutar = function () {
			var error = false;
	        $(".alertaCombo").removeClass("alertaCombo");
	        if ($("#pais").val()==""){
				error = true;
			    $("#pais").addClass("alertaCombo");
			    msj='Por favor seleccione el pais.';
			}
	        if ($("#zona").val()==""){
			   error = true;
		       $("#zona").addClass("alertaCombo");
		       msj='Por favor seleccione la zona.';
			}
			return !error;
	    };

	    this.mensajeError = function () {
	    	mostrarMensaje(msj, "FALLO");
	    }
	}

	function exitoIngresoo(){
		this.ejecutar = function(msg){
			mostrarMensaje("Nuevo registro agregado","EXITO");
			var fila = msg.mensaje;
			$("#detalleLocalizacion").append(fila);	
			$("#nuevoEnfermedadesLocalizacion" + " fieldset input:not(:hidden,[data-resetear='no'])").val('');
			$("#nuevoEnfermedadesLocalizacion fieldset textarea").val('');
		};
	}

	$("#inicioVigencia").datepicker({
	      changeMonth: true,
	      changeYear: true,
	      maxDate:"0"
	});

	$("#finVigencia").datepicker({
	      changeMonth: true,
	      changeYear: true
	});

	$("#agregarDetalleLocalizacion").click(function(event){
		$('#nuevoEnfermedadesLocalizacion').attr('data-destino','detalleItem');
		$('#nuevoEnfermedadesLocalizacion').attr('data-opcion','guardarEnfermedadExoticaLocalizacionSAA');
	});

	$("#zona").change(function(event){
		if($("#zona").val()!=0){
			$('#nombreZona').val($('#zona option:selected').text());
			$('#nuevoEnfermedadesLocalizacion').attr('data-destino','resultadoPais');
			$('#nuevoEnfermedadesLocalizacion').attr('data-opcion','combosServicios');
			$('#opcionL').val('listaPaises');
			abrir($("#nuevoEnfermedadesLocalizacion"),event,false); 
		}
	 });

	$("#modificar").click(function(){
		$("#nuevoEnfermedadesExoticas input").removeAttr("disabled");
		$("#nuevoEnfermedadesExoticas select").removeAttr("disabled");
		$("#nuevoEnfermedadesExoticas textarea").removeAttr("disabled");
		$("#actualizar").removeAttr("disabled");
		$(this).attr("disabled",true);
	});

	$("#tipoRequerimiento").change(function(event){
		if($("#tipoRequerimiento").val()!=0){
			$('#nombreTipoRequerimiento').val($('#tipoRequerimiento option:selected').text());
			$('#nuevoEnfermedadesRequerimiento').attr('data-destino','resultadoElemento');
			$('#nuevoEnfermedadesRequerimiento').attr('data-opcion','combosServicios');
			$('#opcionR').val('listaElementos');
			abrir($("#nuevoEnfermedadesRequerimiento"),event,false); 
		}
	 });


	function accionesProducto(nuevo,seccion,bajar,subir,ingreso,borrado,activo, previo){
		
		nuevo = (nuevo == null)? "#nuevoRegistro":nuevo; //formulario de nuevo registro
		seccion = (seccion == null)? "#registros":seccion; //tabla en donde añadir los nuevos registros
		ingreso = (ingreso == null)? new exitoIngreso():ingreso;
		borrado = (borrado == null)? new exitoBorrado():borrado;
		activo = (activo == null)? new exitoActivo():activo;
			
		previo = (previo == null) ? new validar() : previo;
		function validar() {
			var msj;
		    this.ejecutar = function () {
		        var error = false;
		        $(".alertaCombo").removeClass("alertaCombo");
		        if ($("#subtipoProducto").val()==""){
					   error = true;
				       $("#subtipoProducto").addClass("alertaCombo");
				       msj='Por favor seleccione el subtipo de producto.';
					}
		        if ($("#tipoProducto").val()==""){
				   error = true;
			       $("#tipoProducto").addClass("alertaCombo");
			       msj='Por favor seleccione el tipo de producto.';
				}
				return !error;
			};
			    this.mensajeError = function () {
			    	mostrarMensaje(msj, "FALLO");
		    }
		}

		$(nuevo).submit(function(event){
			event.preventDefault();
			if(previo.ejecutar()){
				ejecutarJson($(this), ingreso);
				actualizarBotonesOrdenamiento();
			} else {
				previo.mensajeError();
			}
		});

		$(seccion).on("submit","form.borrar",function(event){
			event.preventDefault();
			ejecutarJson($(this),borrado);
			actualizarBotonesOrdenamiento();
		});

		$(seccion).on("submit","form.abrir",function(event){
			abrir($(this),event,false);
		});

		var m;
		$(seccion).on("submit","form.activo",function(event){
			event.preventDefault();
			if($(seccion + " tbody tr td form.activo").length==1){
    			m=confirm("Al desactivar el unico producto activo, se desactivar todo el registro ¿Desea desactivar el producto?");
   				if (!m) {
   			    	$("#estado").html("¡Haz denegado el mensaje, el estado del producto no ha cambiado!").addClass('alerta');
   			    	$("form.activo #cambioEstado").val('no');
   	    		}else{
    		    	$("form.activo #estadoRequisito").val('inactivo');
    		    	$("form.activo #cambioEstado").val('si');
    		    	ejecutarJson($(this),activo);
   		    	}
   			}else{
   				$("form.activo #estadoRequisito").val('inactivo');
   				$("form.activo #cambioEstado").val('no');
   				ejecutarJson($(this),activo);
   			}
		});
			
		$(seccion).on("submit","form.inactivo",function(event){
			event.preventDefault();
			$("form.inactivo #estadoRequisito").val('activo');
			$("form.inactivo #cambioEstado").val('no');
			ejecutarJson($(this),activo);
		});

		$("#actualizarRegistro").submit(function(event){
			event.preventDefault();
			ejecutarJson($(this),null);
		});

		$("#regresar").submit(function(event){
			abrir($(this),event,false);
		});

		function exitoIngreso(){
			this.ejecutar = function(msg){
				mostrarMensaje("Nuevo registro agregado","EXITO");
				var fila = msg.mensaje;
				$(seccion).append(fila);	
				$(nuevo + " fieldset input:not(:hidden,[data-resetear='no'])").val('');
				$(nuevo + " fieldset textarea").val('');
			};
		}

		function exitoBorrado(){
			this.ejecutar = function(msg){
			var registro = " #R";
            if(typeof msg.registro != "undefined") {
                registro = " " + msg.registro;
            }
			$(seccion + registro + msg.mensaje).fadeOut("fast",function(){
			        $(this).remove();
			    });
				mostrarMensaje("Elemento borrado","EXITO");
			};
		}
		
		function exitoActivo(){
			this.ejecutar = function(msg){	
    			if ($(seccion + " #R" + msg.mensaje +" form.activo").length!=0){
					if($(seccion + " form.activo").length==1){
    					if (!m) {
		    				mostrarMensaje("¡Haz denegado el mensaje los datos no han sido guardado!","FALLO");
	    		    	}else{
		    				$(seccion + " #R" + msg.mensaje +" form.activo").addClass('inactivo');
		    				$(seccion + " #R" + msg.mensaje +" form.inactivo").removeClass('activo');
		    				$estado = 'inactivo';
		    				mostrarMensaje("Elemento "+$estado,"EXITO");
		    				$('#_actualizarSubListadoItems').click();
    		    		}
    				}else{
    					$(seccion + " #R" + msg.mensaje +" form.activo").addClass('inactivo');
	    				$(seccion + " #R" + msg.mensaje +" form.inactivo").removeClass('activo');
	    				$estado = 'inactivo';
	    				mostrarMensaje("Elemento "+$estado,"EXITO");
    				}
    		    }else{
    		    	$(seccion + " #R" + msg.mensaje +" form.inactivo").addClass('activo');
    		    	$(seccion + " #R" + msg.mensaje +" form.activo").removeClass('inactivo');
    		    	$estado = 'activo';
    		    	mostrarMensaje("Elemento "+$estado,"EXITO");
    		    }	
			};
		}
	}


	$("#agregarDetalleRequerimiento").click(function(event){
		
		$('#nuevoEnfermedadesRequerimiento').attr('data-destino','detalleItem');
		$('#nuevoEnfermedadesRequerimiento').attr('data-opcion','guardarEnfermedadExoticaRequerimientoSAA');
	});

	function validarInputsRequerimiento() {
		var msj;
	    this.ejecutar = function () {
	        var error = false;
	        $(".alertaCombo").removeClass("alertaCombo");
	        if ($("#tipoRequerimiento").val()==""){
				   error = true;
			       $("#tipoRequerimiento").addClass("alertaCombo");
			       msj='Por favor seleccione el tipo de requerimiento.';
				}
	        if ($("#elementoRevision").val()==""){
			   error = true;
		       $("#elementoRevision").addClass("alertaCombo");
		       msj='Por favor seleccione un elemento del requerimiento.';
			}
			return !error;
	    };
	
	    this.mensajeError = function () {
	    	mostrarMensaje(msj, "FALLO");
	    }
	}

	function exitoIngresooRequerimiento(){
		this.ejecutar = function(msg){
			mostrarMensaje("Nuevo registro agregado","EXITO");
			var fila = msg.mensaje;
			$("#detalleRequerimiento").append(fila);	
			$("#nuevoEnfermedadesRequerimiento" + " fieldset input:not(:hidden,[data-resetear='no'])").val('');
			$("#nuevoEnfermedadesRequerimiento fieldset textarea").val('');
		};
	}


	$("#enfermedad").change(function(event){
		if($("#enfermedad").val()!=0){
			$('#nombreEnfermedad').val($('#enfermedad option:selected').text());
		}
	 });
	

	$("#nuevoEnfermedadesExoticas").submit(function(event){
		event.preventDefault();
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if ($("#estadoEnfermedad").val()==""){
			error = true;
			$("#estadoEnfermedad").addClass("alertaCombo");
		  	$("#estado").html('Por favor seleccione el estado.').addClass("alerta");
		}
		
		if ($("#inicioFin").val()==""){
			error = true;
			$("#inicioFin").addClass("alertaCombo");
		  	$("#estado").html('Por favor seleccione la fecha fin vigencia.').addClass("alerta");
		}
		
		if ($("#inicioVigencia").val()==""){
			error = true;
			$("#inicioVigencia").addClass("alertaCombo");
		  	$("#estado").html('Por favor seleccione la fecha inicio vigencia.').addClass("alerta");
		}
		
		if ($("#enfermedad").val()==""){
			error = true;
			$("#enfermedad").addClass("alertaCombo");
		  	$("#estado").html('Por favor seleccione la enfermedad.').addClass("alerta");
		}
	
		if (!error){
			ejecutarJson("#nuevoEnfermedadesExoticas");
		}
	});
</script>