<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastroProducto.php';

$conexion = new Conexion();
$cp = new ControladorCatastroProducto();

$idCatastro = $_POST['id'];
$qCatastro=$cp->abrirCatatroIndividualProducto($conexion, $idCatastro);
$filaCatastro = pg_fetch_assoc($qCatastro);

$qCantidadDetalleCatastro=$cp->cantidadDetalleCatastro($conexion, $idCatastro);
$filaCantidadDetalleCatastro = pg_fetch_assoc($qCantidadDetalleCatastro);

?>
<header>
	<h1>Dar de Baja Catastro</h1>
	
		<form>
		<div id="estado"></div>
		</form>
<fieldset >	
	<legend>Detalle de Productos Catastrado</legend>
		
	  	<div data-linea="1" >			
			<label>Nombre del Sitio: </label>
			<input type="text" id="nombreSitio" name="nombreSitio" value="<?php echo $filaCatastro['nombre_sitio']; ?>" disabled="disabled" />
		</div>
		
		<div data-linea="1" >			
			<label>Nombre del Área: </label>
			<input type="text" id="nombreArea" name="nombreArea" value="<?php echo $filaCatastro['nombre_area']; ?>" disabled="disabled" />
		</div>
		
		<div data-linea="2" >			
			<label>Fecha de Registro: </label>
			<input type="text" id="fechaRegistro" name="fechaRegistro" value="<?php echo $filaCatastro['fecha_registro']; ?>" disabled="disabled" />
		</div>
		
		<div data-linea="2" >			
			<label>Especie: </label>
			<input type="text" id="nombreEspecie" name="nombreEspecie" value="<?php echo $filaCatastro['nombre_especie']; ?>" disabled="disabled" />
		</div>
		
		<div data-linea="3" >			
			<label>Producto: </label>
			<input type="text" id="nombreProducto" name="nombreProducto" value="<?php echo $filaCatastro['nombre_producto']; ?>" disabled="disabled" />
		</div>
		
		<div data-linea="3" >			
				<label>Operación: </label>
				<input type="text" id="nombreOperacion" name="nombreOperacion" value="<?php echo $filaCatastro['nombre_operacion']; ?>" disabled="disabled" />
		</div>
		
		<div data-linea="4" >			
			<label>Fecha de Nacimiento: </label>
			<input type="text" id="fechaNacimiento" name="fechaNacimiento" value="<?php echo $filaCatastro['fecha_nacimiento']; ?>" disabled="disabled" />
		</div>
			
		<div data-linea="4" >			
			<label>Cantidad: </label>
			<input type="text" id="cantidad" name="cantidad" value="<?php echo $filaCantidadDetalleCatastro['cantidad']; ?>" disabled="disabled" />
		</div>

		<div data-linea="5" >			
			<label>Unidad Comercial: </label>
			<input type="text" id="unidadComercial" name="unidadComercial" value="<?php echo $filaCatastro['nombre_unidad_comercial']; ?>" disabled="disabled" />
		</div>
		
		<div data-linea="5" >			
			<label>N° Lote: </label>
			<input type="text" id="unidadComercial" name="unidadComercial" value="<?php echo $filaCatastro['numero_lote']; ?>" disabled="disabled" />
		</div>
				
		<div data-linea="6" >			
			<label>Peso: </label>
			<input type="text" id="peso" name="peso" value="<?php echo $filaCatastro['peso']; ?>" disabled="disabled" />
		</div>
		
		<div data-linea="6" >			
			<label>Unidad Peso: </label>
			<input type="text" id="unidadMedidaPeso" name="unidadMedidaPeso" value="<?php echo $filaCatastro['unidad_medida_peso']; ?>" disabled="disabled" />
		</div>
		
		<div data-linea="7" id="fechaAP">			
			<label>Fecha Actualización Producto: </label>
			<input type="text" id="fechaEtapaActualizada" name="fechaEtapaActualizada" value="<?php echo $filaCatastro['fecha_modificacion_etapa']; ?>" disabled="disabled" />
		</div>
					
</fieldset>

<form id='abrirDetalleCatastro' data-rutaAplicacion='catastroProducto' data-accionEnExito='ACTUALIZAR' >
	<input type="hidden" id="opcion" name="opcion" value="">
	<input type="hidden" id="idCatastro" name="idCatastro" value="<?php echo  $idCatastro; ?>">
	
<fieldset>
	<legend>Motivos para Dar de Baja</legend>
		
		<div data-linea="1">
				<label>Motivo: </label> 
				<select id="conceptoCatastro" name="conceptoCatastro">
					<option value="0">Seleccione...</option>
					<?php
						$qConceptoCatastro = $cp-> listaConceptoCatastroDarDeBaja($conexion,'dar de baja');
						while ($fila = pg_fetch_assoc($qConceptoCatastro)){
							//if($fila['codigo']=='MUER' || $fila['codigo']=='DESA' || $fila['codigo']=='AUTO' || $fila['codigo']=='SASA')
					    	echo '<option value="' . $fila['id_concepto_catastro'] . '">' . $fila['nombre_concepto'] . '</option>';
					
							}
					?>						
				</select>	
		</div>
		
		<div data-linea="2">
				<label>Seleccione el método para dar de baja: </label> 
					<input type="radio" name="grupo"  value="individual" id="individual"> Individual
					<input type="radio" name="grupo"  value="rango" id="rango"> Rango
				
		</div>

		<div data-linea="3">
		<table id="listadoCatastro">
  			<thead>
				<tr>
					<th>N° Reg.</th>							
					<th>N° Identificador</th>	
					<th>Dar de Baja</th>
				<tr>
			</thead> 
			<tbody id="catastro">
			</tbody>
		</table>
		</div>
		
		<div data-linea="4" id="catastroRango"></div>
		<div data-linea="4" id="catastroRango1"></div>
		<div data-linea="5" id="darBaja" style="text-align:center" ><button type="submit" >Dar de baja</button></div>	 
</fieldset>
</form>
</header>
<script type="text/javascript">
var id_area = <?php echo json_encode($filaCatastro['id_area']); ?>;

$(document).ready(function(event){
	acciones(null,"#catastro");
	$('#listadoCatastro').hide();
	$('#catastroRango').hide();
	$('#catastroRango1').hide();
	$('#darBaja').hide();
	 
	if(id_area!='SA')
	$("#fechaAP").hide(); 
	
	 distribuirLineas();
});

$("input:radio[name=grupo]").change(function(event){	
	
	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;

	if($("#conceptoCatastro").val()==0 ){	
		 error = true;		
		$("#conceptoCatastro").addClass("alertaCombo");
		$("#estado").html('Por favor seleccione el motivo por el cual va a dar de baja.').addClass("alerta");
	}

	if (!error){
		if($("input:radio[name=grupo]:checked").val() == 'individual'){		
			
			$('#listadoCatastro').show();
			$('#catastroRango').hide();
			$('#catastroRango1').hide();
			$('#darBaja').hide();
			$('#abrirDetalleCatastro').attr('data-destino','catastro');
			$('#abrirDetalleCatastro').attr('data-opcion','accionesCatastro');
		    $('#opcion').val('listaDetalleCatastro');		
			abrir($("#abrirDetalleCatastro"),event,false); 
		}else{
			$('#listadoCatastro').hide();
			$('#catastroRango').show();
			$('#catastroRango1').show();
			$('#darBaja').show();
		
			$('#abrirDetalleCatastro').attr('data-destino','catastroRango');
			$('#abrirDetalleCatastro').attr('data-opcion','accionesCatastro');
			$('#opcion').val('listaDetalleRango');		
			abrir($("#abrirDetalleCatastro"),event,false);
			
			$('#abrirDetalleCatastro').attr('data-destino','catastroRango1');
			$('#abrirDetalleCatastro').attr('data-opcion','accionesCatastro');
			$('#opcion').val('listaDetalleRango1');		
			abrir($("#abrirDetalleCatastro"),event,false);
				
		}	
	}
});

$("#conceptoCatastro").change(function(event){
	$('#listadoCatastro').hide();
	$('#catastroRango').hide();
	$('#catastroRango1').hide();
	$('#darBaja').hide();
	$("input:radio[name=grupo]").attr('checked',false);
	
	if($("#conceptoCatastro").val()!=0)
	$("#estado").html("").removeClass('alerta');	
});	

$("#abrirDetalleCatastro").submit(function(event){
	event.preventDefault();	
	$(".alertaCombo").removeClass("alertaCombo");
 	var error =false;
	
	var formato1InicioRango = $("#inicioRango").val().split("-");
	var formato1FinRango = $("#finRango").val().split("-");

	var formato2InicioRango = $("#inicioRango").val().split("EC");
	var formato2FinRango = $("#finRango").val().split("EC");

	if(formato1InicioRango.length == 3 && formato1InicioRango[0] == formato1FinRango[0] && formato1InicioRango[1] == formato1FinRango[1] && parseInt(formato1InicioRango[2]) > parseInt(formato1FinRango[2])){
		error = true;		
		$("#inicioRango").addClass("alertaCombo");
		$("#finRango").addClass("alertaCombo");
		$("#estado").html('Por favor seleccione un rango correcto.').addClass("alerta");

	}else if($("#inicioRango").val().substring(0, 2) == $("#finRango").val().substring(0, 2) && parseInt(formato2InicioRango[1]) > parseInt(formato2FinRango[1]) ){
 		error = true;		
		$("#inicioRango").addClass("alertaCombo");
		$("#finRango").addClass("alertaCombo");
		$("#estado").html('Por favor seleccione un rango correcto.').addClass("alerta");

	}else if(parseInt($("#inicioRango").val()) > parseInt($("#finRango").val())){
 		error = true;		
		$("#inicioRango").addClass("alertaCombo");
		$("#finRango").addClass("alertaCombo");
		$("#estado").html('Por favor seleccione un rango correcto.').addClass("alerta");
 		
	}
	
	if($("#finRango").val()==0 ){	
		 error = true;		
		$("#finRango").addClass("alertaCombo");
		$("#estado").html('Por favor ingrese el fin del rango.').addClass("alerta");		
	}

	if($("#inicioRango").val()==0 ){	
		 error = true;		
		$("#inicioRango").addClass("alertaCombo");
		$("#estado").html('Por favor ingrese el inicio del rango.').addClass("alerta");
	}
	
	if (!error){
		$("#abrirDetalleCatastro").attr('data-destino', 'detalleItem');
        $("#abrirDetalleCatastro").attr('data-opcion', 'darBajaDetalleCatastroRango'); 
		ejecutarJson("#abrirDetalleCatastro");

		//abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),"#listadoItems",true);;
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un registro para revisarlo.</div>');								
		
	}	
});
</script>