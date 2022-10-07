<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAreas.php';
require_once '../../clases/ControladorVehiculos.php';
require_once '../../clases/ControladorUsuarios.php';
require_once '../../clases/ControladorCatalogos.php';

//Identificador Usuario Administrador o Apoyo de Transportes
$identificadorUsuarioRegistro = $_SESSION['usuario'];

$conexion = new Conexion();
$cu = new ControladorUsuarios();
$cv = new ControladorVehiculos();
$cc = new ControladorCatalogos();
$ca = new ControladorAreas();

//$area = $ca->listarAreas($conexion);
$area = $ca->obtenerAreasDireccionesTecnicas($conexion, "('Planta Central','Oficina Técnica')", "(3,4,1)");
$usuario = $cu->obtenerUsuariosXarea($conexion);
$ciudades = $cc->listarSitiosLocalizacion($conexion,'CANTONES');
$sitios = $cc->listarSitiosLocalizacion($conexion,'SITIOS');


while($fila = pg_fetch_assoc($usuario)){
	$ocupante[]= array(identificador=>$fila['identificador'], apellido=>$fila['apellido'], nombre=>$fila['nombre'], area=>$fila['id_area']);
}


?>

<header>
	<h1>Nueva Movilización</h1>
</header>

<div id="estado"></div>


<form id='nuevoMovilizacion' data-rutaAplicacion='transportes' data-opcion='guardarNuevaMovilizacion' data-destino="detalleItem" data-accionEnExito='ACTUALIZAR'>

	<input type='hidden' id='identificadorUsuarioRegistro' name='identificadorUsuarioRegistro' value="<?php echo $identificadorUsuarioRegistro;?>" />
	
	<fieldset>
		<legend>Información de la Movilización</legend>
		
			<div data-linea="1">
							
				<label>Tipo</label> 
					<select id="tipo" name="tipo" >
						<option value="" selected="selected">Tipo....</option>
						<option value="Orden Movilización" >Orden Movilización</option>
						<option value="Salvaconducto" >Salvaconducto</option>
					</select> 
					
			</div><div data-linea="2">
				
				<label>Motivo Movilización</label>
				 
				<select id="motivoMovilizacion" name="motivoMovilizacion" >
					<option value="" selected="selected">Descripción....</option>
					<?php 
						$actividades = $cc->listarActividadesMovilizacion($conexion, 1);
						
						while($fila = pg_fetch_assoc($actividades)){
							$motivoMovilizacion[] = '<option value="' . $fila['id_actividad_movilizacion'] . '">' . $fila['nombre_actividad'] . '</option>';
						}
					?>					
				</select>
			</div>	
			
			<div data-linea="3">
				<div id="dSubActividades"></div>
			</div>
			
			<input type="hidden" id="idMotivoMovilizacion" name="idMotivoMovilizacion"/> 
			
			<button type="button" onclick="agregarMotivo()" class="mas">Agregar motivo</button>
			
			<table class ="motivo" id="motivo">
			</table>
			
		</fieldset>
		
		
	
	<fieldset>
		<legend>Recorrido</legend>	
		
		
		<div data-linea="3">
			<label>Provincia</label>
				<select id="provincia" name="provincia" >
					<option value="">Provincia....</option>
					<?php 
						$provincias = $cc->listarSitiosLocalizacion($conexion,'PROVINCIAS');
						foreach ($provincias as $provincia){
							echo '<option value="' . $provincia['codigo'] . '">' . $provincia['nombre'] . '</option>';
						}
					?>
				</select> 
				
		</div><div data-linea="3">
	
			<label>Ciudad</label>
				<select id="ciudad" name="ciudad" disabled="disabled">
				</select>
				
		</div><div data-linea="4">
				
			<label>Sitio</label>
				<select id="sitio" name="sitio" disabled="disabled">
				</select>
				
		</div><div data-linea="5">
				
				<div id="opcion">
					<label>Observación</label>
						<input type="text" name="observacion" id="observacion" />
				</div>
				
		</div><div data-linea="6">
		
			<label>Fecha Validez desde</label> 
				<input type="text" name="fechaDesde" id="fechaDesde" required="required" readonly="readonly"/>
				 
		</div><div data-linea="6">
		
			<label>Fecha Validez hasta</label> 
				<input type="text" name="fechaHasta" id="fechaHasta" required="required" readonly="readonly"/>
		
		</div>
			<button type="button" onclick="agregarRuta()" class="mas">Agregar ruta</button>
			
			<table class ="ruta" id="rutas">
			</table>

		</fieldset>
		
		
	<fieldset>
		<legend>Ocupantes</legend>
		
		
		<div data-linea="7">
			<label>Área pertenece</label> 
				<select id="area" name="area">
					<option value="" selected="selected">Área....</option>
					<?php 
						while($fila = pg_fetch_assoc($area)){
								echo '<option value="' . $fila['id_area'] . '" data-categoria="' . $fila['categoria_area'] . '" >' . $fila['nombre'] . '</option>';
							}
					?>
					
				</select>
				
				<input type="hidden" id="categoriaArea" name="categoriaArea" />
		
		</div><div data-linea="7">
		 			
					<div id="dSubOcupante"></div>
				
			 </div><div data-linea="2">
				
				<div id="opcion_ocupante">
						<label>Observación</label>
							<input type="text" name="observacion_ocupante" id="observacion_ocupante" />
				</div>
				</div>
				<button type="button" onclick="agregarOcupante()" class="mas">Agregar funcionario</button>
		
			<table>
				<thead>
					<tr>
						<th colspan="2">Funcionarios seleccionados</th>
					<tr>
				</thead>
				<tbody id="ocupantes">
				</tbody>
			</table>
		
	</fieldset>
	
	<button type="submit" class="guardar">Guardar movilización</button>
	
</form>
<script type="text/javascript">

	var array_ciudad= <?php echo json_encode($ciudades); ?>;
	var array_sitio= <?php echo json_encode($sitios); ?>;
	var array_ocupante= <?php echo json_encode($ocupante); ?>;
	var array_opcionesMotivoMovilizacion = <?php echo json_encode($motivoMovilizacion);?>;

	$("#area").change(function(event){
		$("#categoriaArea").val($('#area option:selected').attr('data-categoria'));
		$("#nuevoMovilizacion").attr('data-opcion', 'combosOcupante');
	    $("#nuevoMovilizacion").attr('data-destino', 'dSubOcupante');
	    abrir($("#nuevoMovilizacion"), event, false); //Se ejecuta ajax
	    
	    //$('#ocupante').html(socupante);
	    //$('#ocupante').removeAttr("disabled");
	 });

	function agregarMotivo(){  
    	var rowCount = $('#motivo tr').length;
    	 
    	if($("#motivoMovilizacion").val()!='' && $("#motivoMovilizacion").val()!=null){
    		if($("#subActividad").val()!=null && $("#subActividad").val()!=''){
	    		if(rowCount < 2){
		    		if($("#motivo #r_"+$("#motivoMovilizacion").val()+$("#subActividad").val()).length==0){
		   				$("#motivo").append("<tr id='r_"+$("#motivoMovilizacion").val()+$("#subActividad").val()+"'><td><button type='button' onclick='quitarMotivo(\"#r_"+$("#motivoMovilizacion").val()+$("#subActividad").val()+"\")' class='menos'>Quitar</button></td><td>"+$("#motivoMovilizacion  option:selected").text()+" - "+$("#subActividad  option:selected").text()+"<input id='descripcion' name='descripcion[]' value='"+$("#motivoMovilizacion option:selected").text()+" - "+$("#subActividad option:selected").text()+"' type='hidden'></td></tr>");
		    		}
	    		}else{
	    			alert('No puede agregar más de 2 motivos en la movilización');
	    		}
    		}else{
    			alert('Por favor seleccione un detalle para la movilización del listado');
    		}
    	}else{
    		$("#estado").html("Por favor seleccione un motivo y una descripción de los listados").addClass("alerta");
    	}
    }

	function quitarMotivo(fila){
		$("#motivo tr").eq($(fila).index()).remove();
	}

    $("#provincia").change(function(){
    	sciudad ='0';
		sciudad = '<option value="">Ciudad...</option>';
	    for(var i=0;i<array_ciudad.length;i++){
		    if ($("#provincia").val()==array_ciudad[i]['padre']){
		    	sciudad += '<option value="'+array_ciudad[i]['codigo']+'">'+array_ciudad[i]['nombre']+'</option>';
			    }
	   		}
	    $('#ciudad').html(sciudad);
	    $("#ciudad").removeAttr("disabled");
	});

    $("#ciudad").change(function(){
		ssitio ='0';
		ssitio = '<option value="">Sitio...</option>';
	    for(var i=0;i<array_sitio.length;i++){
		    if ($("#ciudad").val()==array_sitio[i]['padre']){
		    	ssitio += '<option value="'+array_sitio[i]['codigo']+'">'+array_sitio[i]['nombre']+'</option>';
			    } 
	    	}
	    ssitio += '<option value="Otro">Otro</option>';
	    $('#sitio').html(ssitio);
		$("#sitio").removeAttr("disabled");
	});


    $('#sitio').change(function(){
		if($('#sitio option:selected').attr("value")=="Otro"){
			$("#opcion").show();
		}else{
			$("#opcion").hide();
		}
			 
	});
    
    function agregarRuta(){  
    	var rowCount = $('#rutas tr').length;
    	 
    	if($("#provincia").val()!="" && $("#ciudad").val()!="" && $("#sitio").val()!="" && $("#fechaDesde").val()!="" && $("#fechaHasta").val()!=""){
    		if(rowCount < 5){
	    		if($("#rutas #r_"+$("#sitio").val()+$("#ciudad").val()).length==0){
	   				$("#rutas").append("<tr id='r_"+$("#sitio").val()+$("#ciudad").val()+"'><td><button type='button' onclick='quitarRuta(\"#r_"+$("#sitio").val()+$("#ciudad").val()+"\")' class='menos'>Quitar</button></td><td>"+$("#sitio  option:selected").text()+", <strong>"+$("#ciudad  option:selected").text()+"</strong> (De:<strong> "+$("#fechaDesde").val()+" </strong>Ha:<strong> "+$("#fechaHasta").val()+" </strong>)<input id='sitio_id' name='sitio_id[]' value='"+$("#sitio").val()+"' type='hidden'><input name='sitio_nombre[]' value='"+$("#sitio  option:selected").text()+"' type='hidden'><input name='fechaDe[]' value='"+$("#fechaDesde").val()+"' type='hidden'><input name='fechaHa[]' value='"+$("#fechaHasta").val()+"' type='hidden'><input name='ciudad_nombre[]' value='"+$("#ciudad  option:selected").text()+"' type='hidden'></td></tr>");
	    		}
    		}else{
    			alert('No puede agregar más de 5 rutas en la movilización');
    		}
    	}
    }
    
	
	function quitarRuta(fila){
		$("#rutas tr").eq($(fila).index()).remove();
	}

	function agregarOcupante(){
		var rowCount = $('#ocupantes tr').length;

		if($("#area").val()!=''){
    		if($("#ocupante").val()!=null && $("#ocupante").val()!=''){
				if(rowCount < 6){
					if($("#ocupantes #r_"+$("#ocupante").val()).length==0)
						$("#ocupantes").append("<tr id='r_"+$("#ocupante").val()+"'><td><button type='button' onclick='quitarOcupantes(\"#r_"+$("#ocupante").val()+"\")' class='menos'>Quitar</button></td><td>"+$("#ocupante  option:selected").text()+"<input id='ocupante_id'  name='ocupante_id[]' value='"+$("#ocupante").val()+"' type='hidden'><input name='ocupante_nombre[]' value='"+$("#ocupante  option:selected").text()+"' type='hidden'></td></tr>");
				}else{
					alert('No puede agregar más de 5 ocupantes en la movilización');
				}
    		}else{
    			alert('Por favor seleccione un ocupante del listado');
    		}
		}else{
    		$("#estado").html("Por favor seleccione un área y un ocupante de los listados").addClass("alerta");
    	}
	}
	
	function quitarOcupantes(fila){
		$("#ocupantes tr").eq($(fila).index()).remove();
	}
		
	$("#nuevoMovilizacion").submit(function(event){
		$("#nuevoMovilizacion").attr('data-opcion', 'guardarNuevaMovilizacion');
	    $("#nuevoMovilizacion").attr('data-destino', 'detalleItem');
	    
		event.preventDefault();

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if($("#tipo").val()==""){
			error = true;
			$("#tipo").addClass("alertaCombo");
		}

		if($("#motivoMovilizacion").val()==""){
			error = true;
			$("#motivoMovilizacion").addClass("alertaCombo");
		}

		if($('#sitio_id').length == 0 ){
			error = true;
			$("#estado").html("Por favor ingrese uno o varios sitios").addClass("alerta");
		}

		if($('#ocupante_id').length == 0 ){
			error = true;
			$("#estado").html("Por favor ingrese uno o varios ocupantes").addClass("alerta");
		}
		
		if (!error){
			ejecutarJson(this);
			//abrir($(this),event,false);	
		}
		  
	});

	$(document).ready(function(){
		distribuirLineas();
		$("#opcion").hide();
		$("#opcion_ocupante").hide();
		
		$("#fechaDesde").datepicker({
		      changeMonth: true,
		      changeYear: true
		});
		    
		$("#fechaHasta").datepicker({
		      changeMonth: true,
		      changeYear: true
		});

		for(var i=0; i<array_opcionesMotivoMovilizacion.length; i++){
			 $('#motivoMovilizacion').append(array_opcionesMotivoMovilizacion[i]);
	    }
	});

	$("#motivoMovilizacion").change(function(event){
		$("#idMotivoMovilizacion").val($("#motivoMovilizacion").val());
		
		$("#nuevoMovilizacion").attr('data-opcion', 'combosMovilizacion');
	    $("#nuevoMovilizacion").attr('data-destino', 'dSubActividades');
	    abrir($("#nuevoMovilizacion"), event, false); //Se ejecuta ajax    	
	});

</script>