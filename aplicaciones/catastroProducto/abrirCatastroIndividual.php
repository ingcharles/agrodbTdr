<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastroProducto.php';
$conexion = new Conexion();
$cp = new ControladorCatastroProducto();

$idCatastro = $_POST['id'];
$banderaModificar = true;

$qCatastro = $cp->abrirCatatroIndividualProducto($conexion, $idCatastro);
$filaCatastro = pg_fetch_assoc($qCatastro);

$qCantidadDetalleCatastro=$cp->cantidadDetalleCatastro($conexion, $idCatastro);
$filaCantidadDetalleCatastro = pg_fetch_assoc($qCantidadDetalleCatastro);

$identificadorUsuario = $_SESSION['usuario'];
$filaTipoUsuario = pg_fetch_assoc($cp->obtenerTipoUsuario($conexion, $identificadorUsuario));

switch ($filaTipoUsuario['codificacion_perfil']){
    
    case 'PFL_USUAR_EXT':
        
        $qOperacionesEmpresaUsuario = $cp->obtenerOperacionesEmpresaUsuario($conexion, $identificadorUsuario, "('digitadorVacunacion')", "('OCC')");
        
        if(pg_num_rows($qOperacionesEmpresaUsuario) > 0){
            
            $qOperadorModificacion = $cp->buscarOperadorModificacionIdentificador($conexion, $identificadorUsuario);
            $operadorModificacion = pg_fetch_assoc($qOperadorModificacion);
            
            if($operadorModificacion['habilitar_modificacion_identificador'] == "NO"){
                $banderaModificar = false;
            }
            
        }else{
            $qOperacionesUsuario = $cp->obtenerOperacionesUsuario($conexion, $identificadorUsuario, "( 'OCC')");            
            if(pg_num_rows($qOperacionesUsuario) > 0){
                
                $qOperadorModificacion = $cp->buscarOperadorModificacionIdentificador($conexion, $identificadorUsuario);
                $operadorModificacion = pg_fetch_assoc($qOperadorModificacion);
                
                if($operadorModificacion['habilitar_modificacion_identificador'] == "NO"){
                    $banderaModificar = false;
                }
                
            }
        }
    
    break;
        
    
}


?>
<div id="estado"></div>
<header>
	<h1>Administrar Catastro</h1>
<form id='nuevoCatastro'  data-rutaAplicacion='catastroProducto'  data-accionEnExito="ACTUALIZAR">
	<p>
		<button id="modificar" type="button" class="editar">Modificar</button>
		<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
	</p>
	
	<input type="hidden" id="idCatastro" name="idCatastro"	value="<?php echo $idCatastro;?>" />
	<input type="hidden" id="codigoEspecie" name="codigoEspecie" value="<?php echo $filaCatastro['codigo_especie'];?>" />
	<input type="hidden" id="opcion" name="opcion" value="" />
	
	<fieldset >	
		<legend>Detalle de Productos Catastrados</legend>
		
			<div data-linea="1" >			
				<label>Identificación Operador: </label>
				<input type="text" id="identificacionOperador" name="identificacionOperador" value="<?php echo $filaCatastro['identificador_operador']; ?>" disabled="disabled" />
			</div>
			<div data-linea="1" >			
				<label>Nombre Operador: </label>
				<input type="text" id="nombreOperador" name="nombreOperador" value="<?php echo $filaCatastro['nombre_operador']; ?>" disabled="disabled" />
			</div>
		  	<div data-linea="2" >			
				<label>Nombre del Sitio: </label>
				<input type="text" id="nombreSitio" name="nombreSitio" value="<?php echo $filaCatastro['nombre_sitio']; ?>" disabled="disabled" />
			</div>
			
			<div data-linea="2" >			
				<label>Nombre del Área: </label>
				<input type="text" id="nombreArea" name="nombreArea" value="<?php echo $filaCatastro['nombre_area']; ?>" disabled="disabled" />
			</div>
			
			<div data-linea="3" >			
				<label>Fecha de Registro: </label>
				<input type="text" id="fechaRegistro" name="fechaRegistro" value="<?php echo $filaCatastro['fecha_registro']; ?>" disabled="disabled" />
			</div>
			
			<div data-linea="3" >			
				<label>Especie: </label>
				<input type="text" id="nombreEspecie" name="nombreEspecie" value="<?php echo $filaCatastro['nombre_especie']; ?>" disabled="disabled" />
			</div>
			
			<div data-linea="4" >			
				<label>Producto: </label>
				<input type="text" id="nombreProducto" name="nombreProducto" value="<?php echo $filaCatastro['nombre_producto']; ?>" disabled="disabled" />
			</div>
			
			<div data-linea="4" >			
				<label>Operación: </label>
				<input type="text" id="nombreOperacion" name="nombreOperacion" value="<?php echo $filaCatastro['nombre_operacion']; ?>" disabled="disabled" />
			</div>
			
			<div data-linea="5" >			
				<label>Fecha de Nacimiento: </label>
				<input type="text" id="fechaNacimiento" name="fechaNacimiento" value="<?php echo $filaCatastro['fecha_nacimiento']; ?>" disabled="disabled" />
			</div>

			<div data-linea="5" >			
				<label>Cantidad: </label>
				<input type="text" id="cantidad" name="cantidad" value="<?php echo $filaCantidadDetalleCatastro['cantidad']; ?>" disabled="disabled" />
			</div>
	
			<div data-linea="6" >			
				<label>Unidad Comercial: </label>
				<input type="text" id="unidadComercial" name="unidadComercial" value="<?php echo $filaCatastro['nombre_unidad_comercial']; ?>" disabled="disabled" />
			</div>
			
			<div data-linea="6" >			
				<label>N° Lote: </label>
				<input type="text" id="numeroLote" name="numeroLote" value="<?php echo $filaCatastro['numero_lote']; ?>" disabled="disabled" />
			</div>
			
			<div data-linea="7" >			
				<label>Unidad Peso: </label>
				<input type="text" id="unidadMedidaPeso" name="unidadMedidaPeso" value="<?php echo $filaCatastro['unidad_medida_peso']; ?>" disabled="disabled" />
			</div>
			
			<div data-linea="7" id="fechaAP">			
				<label>Fecha Actualización Producto: </label>
				<input type="text" id="fechaEtapaActualizada" name="fechaEtapaActualizada" value="<?php echo $filaCatastro['fecha_modificacion_etapa']; ?>" disabled="disabled" />
			</div>
	</fieldset>

	<fieldset>
		<legend>Detalle de Identificadores</legend>	

		<div data-linea="1">
		<label>Catastro por cantidad:</label>		
		<input type="checkbox" id="porCantidad" name="porCantidad" disabled="disabled" />
		</div>
		<hr/>
		<div data-linea="2">
		<label>Cantidad:</label>
		</div>
		<div data-linea="2">
		<input type="number" id="cantidadCatastro" onkeypress="ValidaSoloNumeros()" name="cantidadCatastro" value="1" disabled="disabled" maxlength="4" data-er="^[0-9]+$" min="1" onpaste="return false" />
		</div>
		<div data-linea="2">
		<label>Identificador inicial:</label>
		</div>
		<div data-linea="2">
		<input type="text" id="identificadorInicial" name="identificadorInicial" placeholder="Ej: EC0044444" disabled="disabled" />
		</div>
		<div data-linea="3" id="resultadoIdentificadoresCantidadCatastro">
		</div>
		<hr/>
			<table>
				<tbody id="detalleIdentificadoresCatastro">
				<?php
					$qDetalleCatastro=$cp->abrirDetalleCatatroIndividualProducto($conexion, $idCatastro);
					$contador=1;
					$contadorDos=1;
					while ($fila = pg_fetch_assoc($qDetalleCatastro)){
						echo '<tr>' .
								'<td>' .$contador++. '</td>'.
								'<td><input type="hidden" id="identificadorAntiguo" name="identificadorAntiguo[]" value="'.$fila['identificador_producto'].'"  >' .$fila['identificador_producto'] . '</td>';
						
						if(pg_fetch_row($cp->abrirDetalleCatatroIndividualIdentificadorProducto($conexion, $fila['id_detalle_catastro']))!=0){
							$contadorDos++;						
							echo '<td><input type="hidden" id="idDetalleCatastro" name="idDetalleCatastro[]" value="'.$fila['id_detalle_catastro'].'"  ><input type="text" class="identificador" id="identificador" name="identificador[]"  maxlength="11" ></td>';
						}
							echo '</tr>';
					}
				?>		
					
				</tbody>
				<thead>
					<tr>
						<th>N° Reg.</th>
						<th>N° Identificador</th>
						<?php
						if($contadorDos>1){
							echo '<th class="identificador">Actualizar Identificador</th>';
						}
					?>	
					<tr>
				</thead>
			</table>
	</fieldset>
</form>
</header>
<script type="text/javascript">

var idArea = <?php echo json_encode($filaCatastro['id_area']); ?>;
var banderaModificar = <?php echo json_encode($banderaModificar); ?>;

$(document).ready(function(event){
	if(idArea!='SA')	
	$("#fechaAP").hide();
	distribuirLineas();
	$(".identificador").each(function(){
		$(this).hide();
	});
});


function soloNumeros(e) { 
	var key = window.Event ? e.which : e.keyCode;
	return ((key >= 48 && key <= 57) || (key==8)) ; 
}

$("#nuevoCatastro").submit(function(event){
	event.preventDefault();
	
	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;

	if( $("#numeroLote").val()=='0'){
		error = true;
		$("#numeroLote").addClass("alertaCombo");
	}

	if($("#porCantidad").prop('checked')) { 
			
		var contadorLlenos = 0;
		var banderaLlenos = false;

		$(".identificador").each(function(){
			if($(this).val() != ''){
				contadorLlenos += 1;
			}
		}); 

		if($("#cantidadCatastro").val() == 0){
			error = true;
			$("#cantidadCatastro").addClass("alertaCombo");
		}

		if(contadorLlenos != $("#cantidadCatastro").val()){			
			error = true;
			banderaLlenos = true;
			mensajeLlenos = "La serie de " + $("#cantidadCatastro").val() + " identificadores no se encuentra completa";	
		}

	}
	
	if (error){
		$("#estado").html("Por favor revise la información ingresada." + (banderaLlenos) ? mensajeLlenos : "").addClass('alerta');

		if(banderaLlenos){
			$("#estado").html((banderaLlenos) ? mensajeLlenos : "").addClass('alerta');
		}

	}else{
		$("#estado").html("").removeClass('alerta');
		$("#unidadMedidaPeso").removeAttr("disabled");
		$("#nuevoCatastro").attr('data-destino', 'detalleItem');
        $("#nuevoCatastro").attr('data-opcion', 'actualizarCatastro');
		ejecutarJson("#nuevoCatastro");

		$("#unidadMedidaPeso").attr("disabled","disabled");
	}

});

$("#modificar").click(function(event){

	var banderaCantidad = <?php echo json_encode($contadorDos); ?>;
	
	$("#numeroLote").removeAttr("disabled");
	$("#actualizar").removeAttr("disabled");	

	if(banderaModificar){	
    	$(".identificador").each(function(){
    		$(this).show();
    	});
    
    	if(banderaCantidad > 1){
    		$("#porCantidad").prop("disabled", false);
    	}
	}
	
});

$("#porCantidad").change(function(event){

	$("#estado").html("").removeClass('alerta');
	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;

	if( $("#porCantidad").prop("checked") ) {
		$("#cantidadCatastro").prop("disabled", false);
		$("#identificadorInicial").prop("disabled", false);		
		$(".identificador").each(function(){
			$(this).val("");
		});
	}else{
		$("#cantidadCatastro").val("1");	
		$("#identificadorInicial").val("");		
		$("#cantidadCatastro").prop("disabled", true);
		$("#identificadorInicial").prop("disabled", true);	
		$("#agregarIdentificadoresCatastro").hide();
		$(".identificador").each(function(){
			$(this).val("");
		});
	}

});

$("#identificadorInicial").change(function(event){
	
	event.preventDefault();
	event.stopImmediatePropagation();

	$("#agregarIdentificadoresCatastro").show();

	$(".identificador").each(function(){
		$(this).val("");
	});

	if(!$.trim($("#identificadorInicial").val()) || $("#identificadorInicial").val() != ""){
		$('#nuevoCatastro').attr('data-destino','resultadoIdentificadoresCantidadCatastro');	
		$('#nuevoCatastro').attr('data-opcion','accionesCatastro');
		$('#opcion').val('identificadoresCantidadCatastro');			
		abrir($("#nuevoCatastro"),event,false);
	}

}); 

$("#cantidadCatastro").change(function(event){

	$("#estado").html("").removeClass('alerta');
	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;
	
	$("#identificadorInicial").val("");
	$("#agregarIdentificadoresCatastro").hide();
	$(".identificador").each(function(){
		$(this).val("");
	});

}); 

function ValidaSoloNumeros() {
	if ((event.keyCode < 48) || (event.keyCode > 57))
	event.returnValue = false;
}

</script>