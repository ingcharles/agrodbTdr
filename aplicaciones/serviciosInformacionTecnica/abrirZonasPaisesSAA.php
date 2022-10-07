<?php 	session_start();	require_once '../../clases/Conexion.php';	require_once '../../clases/ControladorCatalogos.php';	require_once '../../clases/ControladorServiciosInformacionTecnica.php';		$conexion = new Conexion();;	$cc = new ControladorCatalogos();	$csit = new ControladorServiciosInformacionTecnica();	$idZona=$_POST['id'];	$qZona=$csit->abrirZona($conexion, $idZona);	$zona= pg_fetch_assoc($qZona);	$paises = $cc->listarSitiosLocalizacion($conexion,'PAIS');	$usuarioResponsable=$_SESSION['usuario'];?><!DOCTYPE html><html><head><meta charset="utf-8"></head><body>	<header>		<h1>Modificar Zonas</h1>	</header>	<form id="nuevoZona" data-rutaAplicacion="serviciosInformacionTecnica" data-opcion="actualizarZonaSAA">		<input type="hidden" id="idZona" name="idZona" value="<?php echo $idZona;?>" /> 		<input type="hidden" id="usuarioResponsable" name="usuarioResponsable" value="<?php echo $usuarioResponsable;?>" />		<div id="estado"></div>		<fieldset>			<legend>Información de Zonas</legend>					<div data-linea="1">					<label>Nombre:</label> 					<input type="text" id="nombreZona" name="nombreZona" value="<?php echo $zona['nombre'];?>"  maxlength="512" disabled="disabled"/> 				</div>				<p>					<button id="modificar" type="button" class="editar">Modificar</button>					<button id="actualizar" type="submit" class="guardar" disabled="disabled">Guardar</button>				</p>		</fieldset>	</form>	<form id="nuevoRegistro" data-rutaAplicacion="serviciosInformacionTecnica" data-opcion="guardarZonasPaisesSAA" >		<input type="hidden" id="idZona" name="idZona" value="<?php echo $idZona;?>">		<input type="hidden" id="usuarioResponsable" name="usuarioResponsable" value="<?php echo $usuarioResponsable;?>" />		<input type="hidden" id="opcion" name="opcion"  />		<fieldset>			<legend>Asignación de Países</legend>			<div data-linea="1">							<label>Nombre: </label> 				<select id="pais" name="pais" >				</select>							</div>			<div data-linea="4">				<button type="submit" id="agregarDetalle" class="mas" >Agregar</button>			</div>		</fieldset>	</form>			<fieldset>		<legend>Países Agregados</legend>		<table id="camposDetalle" style="width:100%"  class="tablaMatriz">		<thead>		<tr>			<th>Nombre</th>			<th>Eliminar</th>		</tr>		</thead>		<?php 			$qZona=$csit->listaPaisesZonas($conexion, $idZona);			while ($fila = pg_fetch_assoc($qZona)){				echo $csit->imprimirLineaPaisesZona($fila['id_pais_zona'], $fila['nombre'],$usuarioResponsable);
			}
		?>		</table>	</fieldset>	</body><script type="text/javascript">	var array_paises = <?php echo json_encode($paises);?>; 	$(document).ready(function(){		distribuirLineas();		spais='0';		spais = '<option value="">Seleccione...</option>';	    for(var i=0;i<array_paises.length;i++){	    	spais += '<option value="'+array_paises[i]['codigo']+'">'+array_paises[i]['nombre']+'</option>';   		}	    $('#pais').html(spais);	});		acciones("#nuevoRegistro","#camposDetalle",null,null,new exitoIngresoo(),null,null,new validarInputs());	$("#tipoProducto").change(function(event){		$("#estado").html("").removeClass("alerta");		$(".alertaCombo").removeClass("alertaCombo");		if($("#tipoProducto").val() == ''){			$("#tipoProducto").addClass("alertaCombo");			$("#estado").html("Por favor seleccione un tipo de producto.").addClass("alerta");		}   	});		function validarInputs() {		var msj;		this.ejecutar = function () {			var error = false;	        $(".alertaCombo").removeClass("alertaCombo");	        if ($("#pais").val()==""){			   error = true;		       $("#pais").addClass("alertaCombo");		       msj='Por favor seleccione un pais.';			}			return !error;	    };	    this.mensajeError = function () {	    	mostrarMensaje(msj, "FALLO");	    } 	}	function exitoIngresoo(){		this.ejecutar = function(msg){			mostrarMensaje("Nuevo registro agregado","EXITO");			var fila = msg.mensaje;			$("#camposDetalle").append(fila);				$("#nuevoRegistro" + " fieldset input:not(:hidden,[data-resetear='no'])").val('');			$("#nuevoRegistro fieldset textarea").val('');		};	}		$("#modificar").click(function(){		$("#nuevoZona input").removeAttr("disabled");		$("#actualizar").removeAttr("disabled");		$(this).attr("disabled",true);	});		function validaSoloNumeros() {		 if ((event.keyCode < 48) || (event.keyCode > 57))		  event.returnValue = false;	}		$("#nuevoZona").submit(function(event){		event.preventDefault();		$(".alertaCombo").removeClass("alertaCombo");		var error = false;		if(!$.trim($("#nombreZona").val())){			error = true;			$("#nombreZona").addClass("alertaCombo");			$("#estado").html('Por favor ingrese el nombre de la zona').addClass("alerta");		}		if (!error){			ejecutarJson("#nuevoZona");					}	});</script></html>					