<?php
session_start();

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorMovilizacionProductos.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$cmp = new ControladorMovilizacionProductos();

$identificadorUsuario=$_SESSION['usuario'];

$filaTipoUsuario=pg_fetch_assoc($cmp->obtenerTipoUsuario($conexion, $identificadorUsuario));

$fecha=date('Y/m/d');

$banderaSolicitante = false;
$identificadorSolicitante = "";

switch ($filaTipoUsuario['codificacion_perfil']){
    
    case 'PFL_USUAR_EXT':
        
        $qOperacionesEmpresaUsuario = $cmp->obtenerOperacionesEmpresaUsuario($conexion, $identificadorUsuario, "('digitadorMovilizacion')", "('PROSA', 'COMSA', 'OPISA', 'FERSA')");
        
        if(pg_num_rows($qOperacionesEmpresaUsuario) > 0){
            
            $operacionesEmpresaUsuario = pg_fetch_assoc($qOperacionesEmpresaUsuario);
            $identificadorEmpresa = $operacionesEmpresaUsuario['identificador_empresa'];
            
            //echo "<br/> Es empleado de movilizacion-> moviliza de la empresa<br/>";
            $banderaSolicitante = true;
            $identificadorSolicitante = $identificadorEmpresa;
            
        }else{
            
            $qOperacionesUsuario = $cmp->obtenerOperacionesUsuario($conexion, $identificadorUsuario, "('PRO', 'COM', 'OPI', 'FER')");
            
            if(pg_num_rows($qOperacionesUsuario) > 0){
                //echo "<br/> Es operador-> moviliza sus propios cerdos<br/>";
                $banderaSolicitante = true;
                $identificadorSolicitante = $identificadorUsuario;
            }
            
        }
        
    break;
        
}

$qUnidadComercial = $cc->obtenerIdUnidadMedida($conexion, 'U');
$unidadComercial = pg_fetch_assoc($qUnidadComercial);

?>

<header>
	<h1>Nuevo Certificado de Movilización</h1>
</header>
<div id="mensajeCargando"></div>
<div id="estado"></div>
<form id='nuevoMovilizacionProductos' data-rutaAplicacion='movilizacionProducto' data-accionEnExito="ACTUALIZAR">
	
	<input type="hidden" id="tipoUsuarioIE" name="tipoUsuarioIE" value="<?php echo $filaTipoUsuario['codificacion_perfil'];?>" /> 
	<input type="hidden" id="opcion" name="opcion" value="0" /> 
	<input type="hidden" id="identificadorResponsable" name="identificadorResponsable" value="<?php echo $identificadorUsuario;?>" />
	<input type="hidden" id="operacionOrigen" name="operacionOrigen" value="0" />
	<input type="hidden" id="operacionDestino" name="operacionDestino" value="0" />
	<input type="hidden" id="operacionDestinoCodigoArea" name="operacionDestinoCodigoArea" value="null" />
	<input type="hidden" id="unidadComercial" name="unidadComercial" value="<?php echo $unidadComercial['id_unidad_medida'] ?>" />
	
	<input type="hidden" id="codigoProvinciaOrigen" name="codigoProvinciaOrigen" value="" />
	<input type="hidden" id="identificacionOperadorOrigen" name="identificacionOperadorOrigen" value="" />
	<input type="hidden" id="codigoProvinciaDestino" name="codigoProvinciaDestino" value="" />
	<input type="hidden" id="identificacionOperadorDestino" name="identificacionOperadorDestino" value="" />
	<input type="hidden" id="nombreProvinciaEmision" name="nombreProvinciaEmision" value="" /> 
	
	<input type="hidden" id="gNombreProducto" name="gNombreProducto" value="" />
	<input type="hidden" id="gNombreOperacion" name="gNombreOperacion" value="" />
	<input type="hidden" id="gIdentificadorProducto" name="gIdentificadorProducto" value="" />
	<input type="hidden" id="gProducto" name="gProducto" value="" />
	<input type="hidden" id="gAreaProducto" name="gAreaProducto" value="" />
	<input type="hidden" id="gIdCatastro" name="gIdCatastro" value="" />
	
	<input type="hidden" id="banderaDobleGuia" name="banderaDobleGuia" value="" />
	<input type="hidden" id="banderaTicket" name="banderaTicket" value="" />
	
	<input type="hidden" id="tipoMovilizacion" name="tipoMovilizacion" value="" />
	
	<input type="hidden" id="tipoSolicitud" name="tipoSolicitud" value="CERTIFICADO SANITARIO PARA LA MOVILIZACIÓN TERRESTRE DE ANIMALES, PRODUCTOS Y SUBPRODUCTOS DE ORIGEN ANIMAL (CSMI)" />
	
	<fieldset id="datosGenerales">
		<legend>Datos Generales</legend>
			
		<div data-linea="1" id="resultadoProvinciasEmision">
			<label>Provincia Emisión: </label> <select id="provinciaEmision" name="provinciaEmision">
				<option value="">Seleccione...</option>
				<?php 
					$provincias = $cc->listarSitiosLocalizacion($conexion,'PROVINCIAS');
					foreach ($provincias as $provincia)
						echo '<option data-nombre="'.$provincia['nombre'].'" value="'.$provincia['codigoProvincia'].'">' . $provincia['nombre'] . '</option>';
				?>
			</select>
		</div>
		<div data-linea="2" id="resultadoOficinasProvincias">
			<label>Oficina Emisión: </label> 
			<select id="oficinaEmision" name="oficinaEmision">
				<option value="">Seleccione...</option>
			</select>
		</div>
	</fieldset>	
	
	<fieldset>
		<legend>Datos Origen</legend>
		<div data-linea="1">
			<label>Provincia: </label> 
			<select id="provinciaOrigen" name="provinciaOrigen">
			<?php 
				$provincias = $cc->listarSitiosLocalizacion($conexion,'PROVINCIAS');
				if($provinciaOrigen)
					echo '<option value="'.$provinciaOrigen.'">' . $provinciaOrigen . '</option>';
				else
					echo '<option value="0">Seleccione...</option>';

				foreach ($provincias as $provincia){
					if($provinciaOrigen!=$provincia['nombre'])
						echo '<option value="'.$provincia['nombre'].'">' . $provincia['nombre'] . '</option>';
				}
			?>
			</select>
		</div>
		<div data-linea="2">
			<label>N° Identificación: </label>
			<input type="text"	id="identificadorOperadorOrigen" name="identificadorOperadorOrigen"	placeholder="Ej: 9999999999" maxlength="13" <?php if ($banderaSolicitante){ ?> value="<?php echo $identificadorSolicitante; ?>" readonly="readonly" <?php }?> />
		</div>
		<div data-linea="2">
			<label>Nombre del Sitio: </label>
			<input type="text" id="nombreSitioOrigen" name="nombreSitioOrigen" placeholder="Ej: San Carlos" maxlength="250" />
		</div>
		<div data-linea="3" style="text-align: center">
			<button type="button" id="buscarSitioOrigen" name="buscarSitioOrigen">Buscar</button>
		</div>
		<hr/>
		<div data-linea="4" id="resultadoSitiosOrigen">
			<label>Sitio Origen: </label> <select id="sitioOrigen" name="sitioOrigen">
				<option value="0">Seleccione...</option>
			</select>
		</div>
		<div data-linea="4" id="resultadoAreasOperacionesOrigen">
			<label>Área Origen: </label>
			<select id="areaOrigen" name="areaOrigen">
				<option value="0">Seleccione...</option>
			</select>
		</div>
	</fieldset>
	
	<fieldset>
		<legend>Datos Destino</legend>
		<div data-linea="1">
		</div>	
		<div data-linea="2">
			<label>Provincia: </label> <select id="provinciaDestino" name="provinciaDestino">
				<option value="0">Seleccione...</option>
				<?php
					foreach ($provincias as $provincia)
						echo '<option value="'.$provincia['nombre'].'">' . $provincia['nombre'] . '</option>';
				?>
			</select>
		</div>
		<hr/>
		<div data-linea="3">
			<label>Tipo Destino: </label>			
		</div>	
		<div data-linea="3">
			<label>Operador</label>
			<input type="radio" name="tipoDestino" value="operador" checked="checked"/>
		</div>	
		<div data-linea="3">
			<label>Matadero</label>
			<input type="radio" name="tipoDestino" value="matadero" />
		</div>
		<div data-linea="3">
			<label>Feria</label>
			<input type="radio" name="tipoDestino" value="feria" />
		</div>	<div data-linea="3">
			<label>Evento</label>
			<input type="radio" name="tipoDestino" value="evento" />
		</div>		
		<hr/>
		<div data-linea="4">
			<label>N° Identificación: </label>
			<input type="text"	id="identificadorOperadorDestino" name="identificadorOperadorDestino" placeholder="Ej: 9999999999" maxlength="13" />
		</div>
		<div data-linea="4">
			<label>Nombre del Sitio: </label>
			<input type="text"	id="nombreSitioDestino" name="nombreSitioDestino" placeholder="Ej: San José" maxlength="250" />
		</div>
		<div data-linea="5" style="text-align: center">
			<button type="button" id="buscarSitioDestino" name="buscarSitioDestino">Buscar</button>
		</div>
		<hr />
		<div data-linea="6" id="resultadoSitiosDestino">
			<label>Sitio Destino: </label>
			<select id="sitioDestino" name="sitioDestino">
				<option value="0">Seleccione...</option>
			</select>
		</div>
		<div data-linea="6" id="resultadoAreasOperacionesDestino">
			<label>Área Destino: </label>
			<select id="areaDestino" name="areaDestino">
				<option value="0">Seleccione...</option>
			</select>
		</div>
		<hr/>
		<div data-linea="8" id="grupoModoMovilizarLote1">
			<label>Movilizar por: </label>			
		</div>	
		<div data-linea="8" id="grupoModoMovilizarLote2">
			<label>Cantidad</label>
			<input type="radio" name="modoMovilizarLote" value="cantidad" />
		</div>	
		<div data-linea="8" id="grupoModoMovilizarLote3">
			<label>Arete identificador</label>
			<input type="radio" name="modoMovilizarLote" value="areteIdentificador" />
		</div>
		<hr/>
		<div data-linea="9" id="resultadoLote">
		</div>
		<div data-linea="10" id="resultadoIdentificador">
		</div>
		<div data-linea="11"  id="cantidadLote">
			<label>Existentes en Lote: </label>
			<input type="text"	id="cantidadExistentePorLote" name="cantidadExistentePorLote" value="" disabled="disabled" />
		</div>
		<div data-linea="11" id="cantidadMovilizar">
			<label>Cantidad a Movilizar: </label>
			<input type="number"	id="cantidad" name="cantidad" onkeypress='ValidaSoloNumeros()' placeholder="Ej: 3" maxlength="4" data-er="^[0-9]+$" min="1" onpaste="return false" />
		</div>		
		<hr/>		
		<div data-linea="12">
			<button type="button" id="agregarDetalleMovilizacion" name="agregarDetalleMovilizacion" class="mas">Agregar</button>
		</div>
		<div data-linea="13">
			<table id="tablaDetalles" style="width: 100%;">
				<thead>
					<tr>
						<th>N°</th>						
						<th>Registros de movilización</th>
						<th>Identificadores</th>
						<th>Opción</th>
					</tr>
				</thead>
				<tbody id="tablaDetalleMovilizacion"></tbody>
			</table>
		</div>
	</fieldset>
	
		<fieldset>
		<legend>Datos de Movilización</legend>
		<div data-linea="1">
			<label>El solicitante del certificado no es el propietario de los productos: </label>
			<input type="checkbox" id="solicitante" name="solicitante" style="vertical-align: middle;" />
		</div>
		<div data-linea="2">
			<label>Identificación Solicitante: </label>
			<input type="text"	name="identificadorSolicitante" id="identificadorSolicitante"	placeholder="Ej: 9999999999" maxlength="13" disabled />
		</div>
		<div data-linea="2">
			<label>Nombre Solicitante: </label>
			<input type="text"	name="nombreSolicitante" id="nombreSolicitante"	placeholder="Ej: Roberto Ruiz" maxlength="255" readonly="readonly"  disabled />
		</div>
		<hr/>
		<div data-linea="3">
			<label>Medio de Transporte o Vehículo de Alquiler: </label> 
			<input type="checkbox" id="validarMedioTransporte" name="validarMedioTransporte" style="vertical-align: middle;" />
		</div>
		<div data-linea="3">
			<label>Medio Transporte: </label> <select id="medioTransporte"	name="medioTransporte" disabled >
				<option value="0">Seleccione...</option>
				<?php	
					$resultadoMedioTransporte = $cc->listarMediosTrasporte($conexion);
					while ($fila = pg_fetch_assoc($resultadoMedioTransporte)){
						echo '<option value="'.$fila['id_medios_transporte'].'">' . $fila['tipo'] . '</option>';
					}
				?>
			</select>
		</div>		
		<div data-linea="4">
			<label>Placa Transporte: </label>
			<input type="text" name="placaTransporte" id="placaTransporte" maxlength="8" placeholder="Ej: ZZZ-9999"	onBlur="this.value=this.value.toUpperCase();" disabled />
		</div>
		<div data-linea="4" id="resultadoPlacaTransporte">
			<label>Nombre Propietario: </label>
			<input type="text" name="nombrePropietario" id="nombrePropietario" placeholder="Ej: Carlos Pérez" maxlength="100" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü ]+$" disabled />
		</div>
		<div data-linea="5">
			<label>Identificación Conductor: </label>
			<input type="text" name="identificadorConductor" id="identificadorConductor" placeholder="Ej: 9999999999" maxlength="13" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü ]+$" disabled />
		</div>
		<div data-linea="5" id="resultadoConductor">
			<label>Nombre Conductor: </label>
			<input type="text"	name="nombreConductor" id="nombreConductor"	placeholder="Ej: David Morán" maxlength="100" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü ]+$" disabled />
		</div>
		<hr/>
		<div data-linea="6">
			<label>Fecha de Movilización: </label> 
			<input type="text"	name="fechaInicioMovilizacion" id="fechaInicioMovilizacion"	placeholder="12/12/2016" maxlength="10"	data-inputmask="'mask': '99/99/9999'" readonly="readonly" />
		</div>
		<div data-linea="6">
			<label>Hora de Movilización: </label>
			<input id="horaMovilizacion" name="horaMovilizacion" type="time" placeholder="10:30" data-er="^([0-9]|0[0-9]|1?[0-9]|2[0-3]):[0-5]?[0-9]$" />
		</div>
		
		<div data-linea="7">
			<label>Observación: </label>
			<input id="observacion"	name="observacion" type="text" maxlength="512" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü ]+$"/>
		</div>
	</fieldset>
	
	<button type="submit" id="btnGuardar" name="btnGuardar" class='guardar'>Guardar</button>
	
</form>

<script type="text/javascript">

var fecha= <?php echo json_encode($fecha); ?>;
var identificadorDueno= <?php echo json_encode($identificadorEmisor); ?>;
var tipoUsuario= <?php echo json_encode($filaTipoUsuario['codificacion_perfil']); ?>;

var array_eliminados = [];	

$(document).ready(function(){
	distribuirLineas();
	construirValidador();	
		
	if($('input:radio[name=tipoDestino]:checked').val() == "operador"){  
		$("#banderaDobleGuia").val("NO");
    	$("#banderaTicket").val("NO");
	}
	
	/*$("#agregarDetalleMovilizacion").hide();
	$("#tablaDetalles").hide();
	$("#cantidadMovilizar").hide();
	$("#cantidadLote").hide();
	$("#resultadoLote").hide();
	$("#grupoModoMovilizarLote1").hide();
	$("#grupoModoMovilizarLote2").hide();
	$("#grupoModoMovilizarLote3").hide();*/

	reestablecerCamposIniciales();
	
	var sumaDia=0;
	if($("#tipoUsuarioIE").val()=='PFL_USUAR_INT'){
		$("#resultadoProvinciasEmision").show();
		$("#resultadoOficinasProvincias").show();
		sumaDia=sumaDia-1;	
	}else{
		$("#resultadoProvinciasEmision").hide();
		$("#resultadoOficinasProvincias").hide();
	}

	fecha=new Date(fecha);
	var fechaFormateadaInicio = new Date(new Date(fecha).setDate(fecha.getDate()+(sumaDia)));
	var fechaDiaInicio=("0" + (fechaFormateadaInicio.getDate())).slice(-2);
	var fechaMesInicio=("0" + (fechaFormateadaInicio.getMonth() + 1)).slice(-2);
	var fechaAnioInicio=fechaFormateadaInicio.getFullYear();
	fechaInicio=fechaDiaInicio+'-'+fechaMesInicio+'-'+fechaAnioInicio;

	var fechaFormateadaFin = new Date(new Date(fecha).setDate(fecha.getDate()+5));

	var fechaDiaFin=("0" + (fechaFormateadaFin.getDate())).slice(-2);
	var fechaMesFin=("0" + (fechaFormateadaFin.getMonth() + 1)).slice(-2);
	var fechaAnioFin=fechaFormateadaFin.getFullYear();
	fechaFin=fechaDiaFin+'-'+fechaMesFin+'-'+fechaAnioFin;
	
	$("#fechaInicioMovilizacion").datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: 'dd-mm-yy',
		minDate:fechaInicio,
        maxDate:fechaFin
    });
	
	if(tipoUsuario=='PFL_USUAR_INT'){
		$("#resultadoProvinciasEmision").show();
		$("#resultadoOficinasProvincias").show();
		$("#datosGenerales").show();
	}else{
		$("#resultadoProvinciasEmision").hide();
		$("#resultadoOficinasProvincias").hide();
		$("#datosGenerales").hide();
	}

	$("#identificadorSolicitante").autocomplete({
        minLength: 5,
        source: function( request, response ) {
            $.ajax({
                url: "aplicaciones/movilizacionProducto/consultarSolicitante.php",
                dataType: "json",
                data: { searchText: request.term, maxResults: 10 },
                success: function( data ) {        
                	if(data!=""){
                		response( $.map( data, function( item ) { 
                        return {label: item.value+' - '+item.label,
                                value: item.value,
                                busqueda: item.label
                   				};   
                		  }));
                        $('#estado').html("");
                        
                	}else{
	                	$('#estado').html("Usuario no registrado").addClass("alerta");
	                	$('#nombreSolicitante').val("");
	                	response( $.map( data, function( item ) {
	 	                	return null;  
	 	            	}));
	                 }
                }
            });
        },
        select: function (event, ui) {
	        $( "#medioTransporte" ).focus();
            $('#identificadorSolicitante').val(ui.item.value);
            $('#nombreSolicitante').val(ui.item.busqueda);
       		return false;
        },
    });
});

$("#buscarSitioOrigen").click(function(event){
		
	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;
	
	$("#sitioOrigen").html("");
	$("#sitioOrigen").append("<option value='0'>Seleccione...</option>");
	$("#areaOrigen").html("");
	$("#areaOrigen").append("<option value='0'>Seleccione...</option>");
	$("#sitioDestino").html("");
	$("#sitioDestino").append("<option value='0'>Seleccione...</option>");
	$("#areaDestino").html("");
	$("#areaDestino").append("<option value='0'>Seleccione...</option>");

	/*$("#agregarDetalleMovilizacion").hide();
	$("#tablaDetalles").hide();
	$("#cantidadMovilizar").hide();
	$("#cantidadLote").hide();
	$("#resultadoLote").hide();
	$("#grupoModoMovilizarLote1").hide();
	$("#grupoModoMovilizarLote2").hide();
	$("#grupoModoMovilizarLote3").hide();*/
	//$("#tablaDetalleMovilizacion tr").remove();
	
	quitarTablaDetalleMovilizacion();

	reestablecerCamposIniciales();	
	
	if($("#identificadorOperadorOrigen").val() == "" && $("#nombreSitioOrigen").val()==""){	
		error = true;		
		$("#identificadorOperadorOrigen").addClass("alertaCombo");
		$("#nombreSitioOrigen").addClass("alertaCombo");
		$("#estado").html("Por favor ingrese al menos un campo para realizar la búsqueda").addClass('alerta');
	}
	
	if($("#provinciaOrigen").val()==0 ){	
		 error = true;		
		$("#provinciaOrigen").addClass("alertaCombo");
		$("#estado").html("Por favor seleccione la provincia origen").addClass('alerta');
	}

	if (!error){	 
		$("#estado").html("");
		$('#nuevoMovilizacionProductos').attr('data-destino','resultadoSitiosOrigen');
		$('#nuevoMovilizacionProductos').attr('data-opcion','accionesMovilizacionProducto');
	     $('#opcion').val('listaSitiosOrigen');		
		 abrir($("#nuevoMovilizacionProductos"),event,false);
	}		 		
});

function ValidaSoloNumeros() {
	 if ((event.keyCode < 48) || (event.keyCode > 57))
	  event.returnValue = false;
}

$("#provinciaEmision").change(function(event){
	$("#nombreProvinciaEmision").val($('#provinciaEmision option:selected').attr('data-nombre'));
	 $('#nuevoMovilizacionProductos').attr('data-destino','resultadoOficinasProvincias');
	 $('#nuevoMovilizacionProductos').attr('data-opcion','accionesMovilizacionProducto');	 
    $('#opcion').val('listaOficinasProvincias');
    event.stopImmediatePropagation();			
	 abrir($("#nuevoMovilizacionProductos"),event,false); 
	 $("#provinciaEmision").removeClass("alertaCombo");
});

$("input:radio[name=tipoDestino]").change(function(event){

	ocultarCampos();
	quitarTablaDetalleMovilizacion();
	reestablecerCamposIniciales();

	$("#sitioDestino").html("");
	$("#sitioDestino").append("<option value='0'>Seleccione...</option>");
	$("#areaDestino").html("");
	$("#areaDestino").append("<option value='0'>Seleccione...</option>");

  	if($('input:radio[name=tipoDestino]:checked').val() == "operador"){  
    	$("#banderaTicket").val("NO");
    	$("#banderaDobleGuia").val("NO");        	
    }else if($('input:radio[name=tipoDestino]:checked').val() == "feria"){
    	$("#banderaTicket").val("SI");
    	$("#banderaDobleGuia").val("NO");
    }else if($('input:radio[name=tipoDestino]:checked').val() == "matadero"){
    	$("#banderaDobleGuia").val("NO");
    	$("#banderaTicket").val("NO");
    }else if($('input:radio[name=tipoDestino]:checked').val() == "evento"){
       	$("#banderaDobleGuia").val("SI");
    	$("#banderaTicket").val("NO");
    }
});

$("#buscarSitioDestino").click(function(event){
	
	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;

	//ocultarCampos();
	//quitarTablaDetalleMovilizacion();
	//reestablecerCamposIniciales();	

	$("#areaDestino").html("");
	$("#areaDestino").append('<option value="0">Seleccione...</option>');
	
	if($("#sitioOrigen").val() == 0 ){
		 error = true;		
		$("#sitioOrigen").addClass("alertaCombo");
	}

	if($("#areaOrigen").val() == 0 ){
		 error = true;		
		$("#areaOrigen").addClass("alertaCombo");
	}
	
	if($('input:radio[name=tipoDestino]:checked').val() == "operador"){  
		$("#banderaTicket").val("NO");    
		if($("#identificadorOperadorDestino").val() == "" && $("#nombreSitioDestino").val()==""){	
			error = true;		
			$("#identificadorOperadorDestino").addClass("alertaCombo");
			$("#nombreSitioDestino").addClass("alertaCombo");
		}
	}

	if($("#provinciaDestino").val() == 0 ){	
		 error = true;		
		$("#provinciaDestino").addClass("alertaCombo");
	}

	if (!error){
		$("#estado").html("");
		$('#nuevoMovilizacionProductos').attr('data-destino','resultadoSitiosDestino');
		$('#nuevoMovilizacionProductos').attr('data-opcion','accionesMovilizacionProducto');
		$('#opcion').val('listaSitiosDestino');		
		abrir($("#nuevoMovilizacionProductos"),event,false);	
	}else{
		$("#estado").html("Por favor llene los campos obligatorios").addClass('alerta');
	}			 		
});

$("#agregarDetalleMovilizacion").click(function(event){

	$(".alertaCombo").removeClass("alertaCombo");
 	var error = false;

 	if ($("#areaDestino").val() === undefined || $("#areaDestino").val() === "undefined"){
		error = true;
	    $("#areaDestino").addClass("alertaCombo");
	    $("#estado").html('Por favor seleccione nuevamente el área destino').addClass("alerta");
	}
	
 	if ($("#areaDestino").val() ==  0){
		error = true;
	    $("#areaDestino").addClass("alertaCombo");
	    $("#estado").html('Por favor seleccione el área destino').addClass("alerta");
	}

  	if ($("#areaOrigen").val() ==  0){
		error = true;
	    $("#areaOrigen").addClass("alertaCombo");
	    $("#estado").html('Por favor seleccione el área origen').addClass("alerta");
	}

  	if (jQuery.inArray($("#gIdentificadorProducto").val(), array_productos_movilizar) != -1 || $("#gIdentificadorProducto").val() == ""){
  		error = true;
	    $("#identificadorProductoAutocompletar").addClass("alertaCombo");
	    $("#estado").html('El identificador no existe').addClass("alerta");
  	}

  	var codigoValidar = $("#gIdentificadorProducto").val();
  	
  	if($("#tablaDetalleMovilizacion #r_"+codigoValidar).length != 0){
  		error = true;
  		$("#estado").html("No se puede ingresar el mismo identificador.").addClass('alerta');
	}

	$('#tablaDetalleMovilizacion tr').each(function (event) {
		if($(this).find('input[name="hSitioOrigen"]').val() != $("#sitioOrigen").val()){
			error = true;	
    		$("#estado").html('Solo puede escoger un sitio de origen.').addClass("alerta");
		}

		if($(this).find('input[name="hSitioDestino"]').val() != $("#sitioDestino").val()){
			error = true;	
    		$("#estado").html('Solo puede escoger un sitio de destino.').addClass("alerta");
		}
    });

	/*if($('input:radio[name=modoMovilizarLote]:checked').val() == "areteIdentificador"){ 
		$('#tablaDetalleMovilizacion tr').each(function (event) {			
			if($(this).find('input[name="hNumeroLote"]').val() != $("#lotesProducto").val()){
				error = true;	
				$("#estado").html('Solo puede escoger un lote por movilización').addClass("alerta");
			}
		});
	}*/ 

  	if (!error){

		$(".alertaCombo").removeClass("alertaCombo");
 		var error = false;
  		
    	var codigo = $("#gIdentificadorProducto").val();
    	
    	$("#identificadorProductoAutocompletar").val("");
    	$('#sitioOrigen option:not(:selected)').attr('disabled',true);
		$('#sitioDestino option:not(:selected)').attr('disabled',true);
		
		//$('#lotesProducto option:not(:selected)').attr('disabled',true);

    	agregarArray(array_productos_movilizar, $("#gIdentificadorProducto").val());
    	
    	if($("#tablaDetalleMovilizacion #r_"+codigo).length == 0){
    	
		$("#tablaDetalleMovilizacion").append("<tr id='r_" + codigo + "'>" + "<td>" + $(this).next('label').text() + "</td>" +
				"<td>" + $("#gNombreOperacion").val() + " - (" +				
				"<input name='hIdAreaDestino[]' value='" + $("#areaDestino option:selected").val() + "' type='hidden'>" +
				$("#areaOrigen option:selected").text() + ") " +
				"<input type='hidden' name='hIdentificadoresValidar[]' value='" + codigo+"'>" + $("#gNombreProducto").val() +
				"<input type='hidden' name='hSitioOrigen' value='" + $("#sitioOrigen option:selected").val() + "' disabled='disabled'>" +
				"<input type='hidden' name='hSitioDestino' value='" + $("#sitioDestino option:selected").val() + "' disabled='disabled'>" +
				"<input type='hidden' name='hNumeroLote' value='" + $("#lotesProducto option:selected").val() + "' disabled='disabled'><td>" + 
				$("#gIdentificadorProducto").val() + 
				"</td><td align='center' class='borrar'><button type='button' onclick='quitarDetalleMovilizacion(\"#r_"+codigo+"\")' class='icono'></button></td></tr>");
		}
		
		enumerar();
		
	}	
});

function quitarDetalleMovilizacion(fila){
	
	var codigo = fila.split('_')[1];

   	eliminarArray(array_eliminados, codigo);
	    	   
	$("#estado").html("").removeClass('alerta');
	$("#tablaDetalleMovilizacion tr").eq($(fila).index()).remove();

	$("#gProducto").val("");
	$("#gNombreProducto").val("");
	$("#gIdentificadorProducto").val("");

	if($('#tablaDetalleMovilizacion tr').length == 0) {	
	   	$('#sitioOrigen option:not(:selected)').attr('disabled',false);
		$('#sitioDestino option:not(:selected)').attr('disabled',false);
		$('#lotesProducto option:not(:selected)').attr('disabled',false);
	}

	enumerar()
	
} 

function agregarArray(arr,item){

	var registro;

 	arr.forEach(function(key, index){

		if(item == key.value){	

			array_eliminados.push(key);
			registro = index;
		}
		
 	});
 
    if (registro !== -1) {
        arr.splice( registro, 1);
    }

}

function eliminarArray(arr, item){
	
	var registro;

 	arr.forEach(function(key, index){
 		   
		if(item == key.value){
			
			array_productos_movilizar.push(key);
					
			registro = index;
			
		}		
		
 	});
 
	if (registro !== -1) {					 
        arr.splice(registro, 1);
    }

}

$("#validarMedioTransporte").click(function(event){	
	if($("#validarMedioTransporte").is(":checked") == true){
		cargarValorDefecto("medioTransporte","3");		
		$("#medioTransporte").attr('disabled', false);
		$("#placaTransporte").attr('disabled', false);
		$("#identificadorConductor").attr('disabled', false);
		$("#nombreConductor").attr('disabled', false);
		$("#nombrePropietario").attr('disabled', false);
	}else{
		$("#medioTransporte").val("");
		$("#medioTransporte").attr('disabled', true);
		$("#placaTransporte").attr('disabled', true);
		$("#identificadorConductor").attr('disabled', true);
		$("#nombreConductor").attr('disabled', true);
		$("#nombrePropietario").attr('disabled', true);
		$("#placaTransporte").val("");
		$("#identificadorConductor").val("");
		$("#nombrePropietario").val("");
		$("#nombreConductor").val("");	}
	
});

$("#placaTransporte").change(function(event){	
	event.preventDefault();
	event.stopImmediatePropagation();
	$('#nuevoMovilizacionProductos').attr('data-destino','resultadoPlacaTransporte');
	$('#nuevoMovilizacionProductos').attr('data-opcion','accionesMovilizacionProducto');	 
	$('#opcion').val('placaTransporte');	
	abrir($("#nuevoMovilizacionProductos"),event,false);	
});

$("#identificadorConductor").change(function(event){	
	event.preventDefault();
	event.stopImmediatePropagation();
	$('#nuevoMovilizacionProductos').attr('data-destino','resultadoConductor');
	$('#nuevoMovilizacionProductos').attr('data-opcion','accionesMovilizacionProducto');	 
	$('#opcion').val('nombreConductor');	
	abrir($("#nuevoMovilizacionProductos"),event,false);	
});

$("#nuevoMovilizacionProductos").submit(function(event){
	event.preventDefault();
	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;

	if ($("#areaDestino").val() ==  0){
		error = true;
	    $("#areaDestino").addClass("alertaCombo");
	    $("#estado").html('Por favor seleccione el área destino').addClass("alerta");
	}
	
  	if ($("#areaOrigen").val() ==  0){
		error = true;
	    $("#areaOrigen").addClass("alertaCombo");
	    $("#estado").html('Por favor seleccione el área origen').addClass("alerta");
	}

	if($("#tipoMovilizacion").val() != 'lote'){
	
    	if ($("#tablaDetalleMovilizacion >tr").length == 0){
    		 error = true;	
    		 $("#estado").html('Por favor ingrese al menos un detalle de productos a movilizar.').addClass("alerta");
    	} 

	}

	if($("#tipoMovilizacion").val() == 'lote'){

		if(parseInt($("#cantidad").val()) > parseInt($("#cantidadExistentePorLote").val())){
    		 error = true;	
    		 $("#cantidad").addClass("alertaCombo");
    		 $("#estado").html('La cantidad de productos a movilizar no puede ser mayor a la cantidad de productos existentes por lote.').addClass("alerta");
    	} 

		if($.trim($("#cantidad").val()) == '' || $("#cantidad").val() <= 0){
			error = true;
       		$("#cantidad").addClass("alertaCombo");	
			$("#estado").html('Por favor registre una cantidad a movilizar valida.').addClass("alerta");
   		} 	

	}
				
	if ($("#horaMovilizacion").val() == ""){
		   error = true;
	       $("#horaMovilizacion").addClass("alertaCombo");
	       $("#estado").html('Por favor digite la hora de movilización.').addClass("alerta");
	}

	if ($("#fechaInicioMovilizacion").val() == ""){
		   error = true;
	       $("#fechaInicioMovilizacion").addClass("alertaCombo");
	       $("#estado").html('Por favor seleccione la fecha de movilización.').addClass("alerta");
	}

	if ($("#observacion").val() != ""){
		if(!esCampoValido("#observacion")){
			error = true;
			$("#observacion").addClass("alertaCombo");
			$("#estado").html('Por favor revise el formato de la observación.').addClass("alerta");
		}
	}
	
	if($("#validarMedioTransporte").is(":checked") == true){
	
		if(!esCampoValido("#nombreConductor")){
			error = true;
			$("#nombreConductor").addClass("alertaCombo");
			$("#estado").html('Por favor revise el formato del nombre del conductor.').addClass("alerta");
		}
		
		if ($("#nombreConductor").val() == ""){
			   error = true;
		       $("#nombreConductor").addClass("alertaCombo");
		       $("#estado").html('Por favor digite el nombre del conductor.').addClass("alerta");
		}
	
		if(!esCampoValido("#identificadorConductor")){
			error = true;
			$("#identificadorConductor").addClass("alertaCombo");
			$("#estado").html('Por favor revise el formato de la identificación del conductor.').addClass("alerta");
		}

		if(!esCampoValido("#nombrePropietario")){
			error = true;
			$("#nombrePropietario").addClass("alertaCombo");
			$("#estado").html('Por favor revise el formato del nombre del propietario.').addClass("alerta");
		}	

		if($("#nombrePropietario").val() == ""){
			error = true;
			$("#nombrePropietario").addClass("alertaCombo");
			$("#estado").html('Por favor revise el formato del nombre del propietario.').addClass("alerta");
		}		
		
		if ($("#identificadorConductor").val()==""){
			   error = true;
		       $("#identificadorConductor").addClass("alertaCombo");
		       $("#estado").html('Por favor digite la identificación del conductor.').addClass("alerta");
		}

		if ($("#placaTransporte").val() == ""){
			   error = true;
		       $("#placaTransporte").addClass("alertaCombo");
		       $("#estado").html('Por favor digite la placa del transporte.').addClass("alerta");
		}
		
		if ($("#medioTransporte").val() == 0){
			   error = true;
		       $("#medioTransporte").addClass("alertaCombo");
		       $("#estado").html('Por favor seleccione medio de transporte.').addClass("alerta");
		} 

	}
		
	if($("#solicitante").is(":checked") == true){
		if ($("#identificadorSolicitante").val() == ""){
			   error = true;
		       $("#identificadorSolicitante").addClass("alertaCombo");
		       $("#estado").html('Usuario no registrado').addClass("alerta");
		}

		if ($("#nombreSolicitante").val() == ""){
			   error = true;
		       $("#nombreSolicitante").addClass("alertaCombo");
		       $("#estado").html('Usuario no registrado').addClass("alerta");
		}
	}
		
	if($("#sitioDestino").val() == 0){	
		error = true;		
		$("#sitioDestino").addClass("alertaCombo");
		$("#estado").html('Por favor seleccione el sitio destino').addClass("alerta");
	}
	
	if($("#sitioOrigen").val() == 0){	
		error = true;		
		$("#sitioOrigen").addClass("alertaCombo");
		$("#estado").html('Por favor seleccione el sitio origen').addClass("alerta");
	}

	if($("#tipoUsuarioIE").val() == 'PFL_USUAR_INT'){
		if($("#oficinaEmision").val() == ""){	
			error = true;		
			$("#oficinaEmision").addClass("alertaCombo");
			$("#estado").html('Por favor seleccione la oficina de emisión').addClass("alerta");
		}
		
		if($("#provinciaEmision").val() == ""){	
			error = true;		
			$("#provinciaEmision").addClass("alertaCombo");
			$("#estado").html('Por favor seleccione la provincia de emisión').addClass("alerta");
		}
	}
		
	if($("#tipoSolicitud").val() == 0){	
		error = true;		
		$("#tipoSolicitud").addClass("alertaCombo");
		$("#estado").html('Por favor seleccione el tipo de solicitud').addClass("alerta");
	}

	if($("#identificadorResponsable").val() == ""){
		error = true;
		$("#estado").html("Su sesión expiró, por favor ingrese nuevamente al sistema.").addClass('alerta');
	}

	$('#tablaDetalleMovilizacion tr').each(function (event) {
		if($(this).find('input[name="hSitioOrigen"]').val() != $("#sitioOrigen").val()){
			error = true;	
    		$("#estado").html('Solo puede escoger un sitio de origen.').addClass("alerta");
		}

		if($(this).find('input[name="hSitioDestino"]').val() != $("#sitioDestino").val()){
			error = true;	
    		$("#estado").html('Solo puede escoger un sitio de destino.').addClass("alerta");
		}
	});
	
	/*if($('input:radio[name=modoMovilizarLote]:checked').val() == "areteIdentificador"){ 
		$('#tablaDetalleMovilizacion tr').each(function (event) {			
			if($(this).find('input[name="hNumeroLote"]').val() != $("#lotesProducto").val()){
				error = true;	
				$("#estado").html('Solo puede escoger un lote por movilización').addClass("alerta");
			}
		});
	}*/

	if (!error){
		$('#nuevoMovilizacionProductos').attr('data-opcion','guardarNuevoMovilizacionProducto');    
		$('#nuevoMovilizacionProductos').attr('data-destino','detalleItem');
		ejecutarJsonMovilizacion("#nuevoMovilizacionProductos");
	}	
});


$("#solicitante").change(function(event){
	if($("#solicitante").is(":checked") == true){
		$("#identificadorSolicitante").attr("disabled", false);
		$("#nombreSolicitante").attr("disabled", false);
	}else{
		$("#identificadorSolicitante").attr("disabled", true);
		$("#identificadorSolicitante").val("");
		$("#nombreSolicitante").attr("disabled", true);
		$("#nombreSolicitante").val("");
	}
});

if($("#solicitante").is(":checked") == true){
	if ($("#identificadorSolicitante").val() == ""){
		   error = true;
	       $("#identificadorSolicitante").addClass("alertaCombo");
	       $("#estado").html('Usuario no registrado').addClass("alerta");
	}

	if ($("#nombreSolicitante").val() == ""){
		   error = true;
	       $("#nombreSolicitante").addClass("alertaCombo");
	       $("#estado").html('Usuario no registrado').addClass("alerta");
	}
}

function ejecutarJsonMovilizacion(form,metodoExito,metodoFallo){
	var $botones = $(form).find("button[type='submit']"),
    serializedData = $(form).serialize(),
    url = "aplicaciones/"+$(form).attr("data-rutaAplicacion")+"/"+$(form).attr("data-opcion")+".php";

	$botones.attr("disabled", "disabled");
    var resultado = $.ajax({
	    url: url,
	    type: "post",
	    data: serializedData,
	    dataType: "json",
	    async:   true,
	    beforeSend: function(){
	    	$("#mensajeCargando").html("<div id='cargando'>Cargando...</div>").fadeIn();
	    	var valorHeight=((parseInt($('#tablaDetalleMovilizacion >tr').length)*80)+1520);
	    	$("#cargando").css({"height": valorHeight+"px"});
	    	$("#estado").removeClass();
	    },
	    success: function(msg){
	    	if(msg.estado=="exito"){
			    	abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"), null, true);
			    	$("#detalleItem").html("<embed id='visor' src='"+msg.rutaCertificado+"' width='550' height='620'>");								
	    		if(metodoExito!=null){
	    			metodoExito.ejecutar(msg);
	    		} else {
	    			mostrarMensaje(msg.mensaje, "EXITO");
	    		}
	    		
	    	} else {
	    		if(metodoFallo!=null){
	    			metodoFallo.ejecutar(msg);
	    		} else {
	    			mostrarMensaje(msg.mensaje, "FALLO");
					if(typeof msg.error != "undefined"){
                        console.log(msg.error);
                    }
	    		}
	    	}
	   },
	   error: function(jqXHR, textStatus, errorThrown){
		   $("#cargando").delay("slow").fadeOut();
	    	mostrarMensaje("ERR: " + textStatus + ", " +errorThrown, "FALLO");
	    },
        complete: function(){
        	$("#cargando").delay("slow").fadeOut();
           $botones.removeAttr("disabled");
        }
	});
};


function quitarTablaDetalleMovilizacion(){

	$("#gProducto").val("");
	$("#gNombreProducto").val("");
	$("#gIdentificadorProducto").val("");
	
	$('#tablaDetalleMovilizacion tr').each(function (event) {
		var codigo = $(this).find('td').find('input[name="hIdentificadoresValidar"]').val();

		if(array_eliminados.length){
			array_eliminados.length = 0;
		}

		$("#estado").html("").removeClass('alerta');
		$("#tablaDetalleMovilizacion tr").eq($("#r_"+codigo).index()).remove();
	});

}

function reestablecerCamposIniciales(){	
	
	ocultarCampos();

	if(array_eliminados.length){
		array_eliminados.length = 0;
	}

};

function ocultarCampos(){
	
	$("#agregarDetalleMovilizacion").hide();
	$("#tablaDetalles").hide();	
	$("#cantidadMovilizar").hide();	
	$("#cantidad").val("");
	$("#cantidadLote").hide();
	$("#cantidadExistentePorLote").val("");
	$("#resultadoLote").hide();
	$("#resultadoIdentificador").hide();
	$("#grupoModoMovilizarLote1").hide();
	$("#grupoModoMovilizarLote2").hide();
	$("#grupoModoMovilizarLote3").hide();

}

function enumerar() {
	var tabla = document.getElementById('tablaDetalleMovilizacion');
	con = 0;
	$("#tablaDetalleMovilizacion tr").each(function(row) {
		con += 1;
		$(this).find('td').eq(0).html(con);
	});
}

</script>