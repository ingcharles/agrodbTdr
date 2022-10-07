<?php 

	//header('Location: ../../../../agrodbOut.html');

require_once '../../../clases/Conexion.php';
require_once '../../../clases/ControladorRequisitos.php';
require_once '../../../clases/ControladorCatalogos.php';
require_once '../../../clases/GoogleAnalitica.php';

$conexion = new Conexion();
$cr = new controladorRequisitos();
$cc = new controladorCatalogos();

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<link rel='stylesheet' href='../estilos/estiloapp.css'>
<script src="../../general/funciones/jquery-1.9.1.js"
	type="text/javascript"></script>
<script src="../../general/funciones/agrdbfunc.js"
	type="text/javascript"></script>
<script src="../../general/funciones/jquery.numeric.js"
	type="text/javascript"></script>
<script src="../../general/funciones/jquery-ui-1.10.2.custom.js"
	type="text/javascript"></script>
</head>
<body id="paginabusqueda">

	<section id="busqueda">
		<fieldset>
			<legend>Producto</legend>
			<form id="datosFiltroProducto" data-rutaAplicacion="../../../publico/productos1" data-opcion="accionesRequisito" data-destino="resultadoProducto">		
				
				<table class="magenTabla">
					<tfoot>
						<tr>
							<td>
								<label for = "reqExp">Exportación</label>
							</td>
							<td class="alinearRadio">
								<input  type = "radio" name="tipoRequisito" value="Exportación" id="exportacion" />
							</td>
						</tr>
						<tr>
							<td>
								<label for = "reqImp">Importación</label>
							</td>
							<td class="alinearRadio" >
								<input type = "radio" name="tipoRequisito" value="Importación" id="importacion"/>
							</td>
						</tr>
						<tr>
							<td>
								<label for = "reqTra">Tránsito</label>
							</td>
							<td class="alinearRadio" >
								<input type = "radio" name="tipoRequisito" value="Tránsito" id="transito"/>
							</td>
						</tr>
						<tr>
							<td>
								<label for = "reqNac">Nacional</label>
							</td>
							<td class="alinearRadio">
								<input  type = "radio" name="tipoRequisito" value="Nacional" id="nacional"/>
			     			</td>
						</tr>
					</tfoot>
				</table>
			    <pre></pre>
				<div>
					<label>Área</label>
					<select id="area" name="area">
						<option value="" selected="selected">Seleccione una dirección...</option>
						<option value="SA">Sanidad Animal</option>
						<option value="SV">Sanidad Vegetal</option>
						<option value="IAP">Productos agrícolas</option>
						<option value="IAV">Productos pecuarios</option>
						<option value="IAF">Productos fertilizantes</option>
						<option value="IAPA">Registro de insumos para plantas de autoconsumo</option>
					</select>
				</div>
				<div > 
					<label>Pais: </label>
					<select id="pais" name="pais">
						<option value="">Seleccione...</option>
						<?php 
							$provincias = $cc->listarSitiosLocalizacion($conexion,'PAIS');
							foreach ($provincias as $provincia){
								echo '<option value="'.$provincia['codigo'].'">' . $provincia['nombre'] . '</option>';
							}
						?>
					</select>
				</div>	
				<div>
					<label for = "productoN">Producto</label>
					<input  type = "text" id="productoN" name="productoN" />
				</div>	
				<div>
					<label for = "partidaArancelaria" >Partida Arancelaria</label>
					<input type = "text" id="partidaArancelaria" name="partidaArancelaria" maxlength="10"/>
				</div>

				<div>
					<label id="lUso" >Uso (Plaguicidas)</label>
					<input type = "text" id="uso" name="uso" maxlength="10"/>
				</div>
				
				<div class="acerca">
					<p>Sistema Gestionador Unificado de Información</p>
					<p>Agrocalidad 2013</p>
					<p>Gestión Tecnológica</p>
				</div>
		
				<div >
					<button type="submit" id="buscar" name="buscar" >BUSCAR</button>		
				</div>
		</form>
	</fieldset>
	</section>
	<section id="resultados">
		<div id="resultadoProducto"></div> 
	 	<div id="resultadoProducto2"></div> 
	</section>
	
<section id="areaNotificacion"><div id="estado"></div></section>
</body>

<script type="text/javascript">

$(document).ready(function(event){
	$('#partidaArancelaria').numeric();
	$('#uso').hide();
	$("#lUso").hide();
});

$("input:radio[name=tipoRequisito]").click(function(){
	$("#estado").html('').removeClass("alerta");
	if($(this).val()=='Exportación'){
		$("#estado").html('Producto Exportación.').addClass("info");
	}else if($(this).val()=='Importación'){
		$("#estado").html('Producto Importación.').addClass("info");
	}else if($(this).val()=='Tránsito'){
		$("#estado").html('Producto Tránsito.').addClass("info");
	}else if($(this).val()=='Nacional'){
		$("#estado").html('Producto Nacional.').addClass("info");
	}
});


$("#datosFiltroProducto").submit(function(event){
	$("#estado").html('').removeClass("info");
	event.preventDefault();	
	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;

	if($("#productoN").val().length < 3){	
		error = true;		
		$("#productoN").addClass("alertaCombo");
		$("#estado").html('Por favor ingrese al menos 3 caracteres para buscar las coincidencias de productos.').addClass("alerta");
	}

	if($("#area").val() == "" ){	
		error = true;		
		$("#area").addClass("alertaCombo");
		$("#estado").html('Por favor seleccione el área.').addClass("alerta");
	}

	if($("input:radio[name=tipoRequisito]:checked").val() == null){
		error = true;
		$("#exportacion").addClass("alertaCombo");
		$("#importacion").addClass("alertaCombo");
		$("#nacional").addClass("alertaCombo");
		$("#transito").addClass("alertaCombo");
		$("#estado").html('Por favor seleccione la actividad comercial.').addClass("alerta");
	
	}

	if (!error){
		$("#estado").html('');
		$("#resultadoProducto2").html('');
		 abrir($("#datosFiltroProducto"),event,false);
	}	
});

//Filtro para búsqueda de Productos Plaguicidas por uso
$('#area').change(function(event){
		if($("#area option:selected").val() === "IAP"){
			$("#uso").show();
			$("#lUso").show();
		}else{
			$("#uso").hide();
			$("#lUso").hide();
			$("#uso").val("");
		}
	});
	
</script>
</html>

