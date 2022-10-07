<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorAreas.php';
require_once '../../clases/ControladorUsuarios.php';
require_once '../../clases/ControladorImportaciones.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$ca = new ControladorAreas();
$cu = new ControladorUsuarios();
$ci = new ControladorImportaciones();

$importacion = explode(",",$_POST['elementos']);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>

<header>
	<h1>Asignar técnico para inspección</h1>
</header>

<div id="estado"></div>

	<p>Las <b>importaciones</b> a ser asignadas son: </p>
 
        <?php
			if($importacion[0]!=null){
				for ($i = 0; $i < count ($importacion); $i++) {
					$qImportacion = $ci->abrirImportacionInspeccion($conexion, $importacion[$i]);
					
					echo'
						<fieldset>
							<legend>Importación N° </label>' .$qImportacion[0]['idImportacion'].'</legend>
								<div data-linea="1">
									<label>RUC operador: </label>'. $qImportacion[0]['razonSocial'] .'<br/>
								</div>
								<div data-linea="2">
									<label>Representante Legal: </label>'. $qImportacion[0]['nombreRepresentante'] .' '. $qImportacion[$i]['apellidoRepresentante'] .'<br/>
								</div>
								<div data-linea="3">
									<label>Tipo de certificado: </label>'. $qImportacion[0]['tipoCertificado'] .'<br/>
								</div>
								<div data-linea="4">
									<label>País de origen: </label>'. $qImportacion[0]['paisExportacion'] .'<br/>	
								</div>
								<div data-linea="4">
									<label>Estado de solicitud: </label>'. $qImportacion[0]['estado'] .'<br/>
								</div>
						</fieldset>';
				}	
			}
		?>

<?php
	$area = $cu->obtenerAreaUsuario($conexion, $_SESSION['usuario']);
	$usuario = $cu->obtenerUsuariosXarea($conexion);
	
	while($fila = pg_fetch_assoc($usuario)){
		$inspector[]= array(identificador=>$fila['identificador'], apellido=>$fila['apellido'], nombre=>$fila['nombre'], area=>$fila['id_area']);
	}
?>

<form id='asignarInspector' data-rutaAplicacion='importaciones' data-opcion='guardarNuevoInspector' data-destino="detalleItem">	
	<?php 
		for ($i = 0; $i < count ($importacion); $i++) {
			echo'<input type="hidden" name="id[]" value="'.$importacion[$i].'"/>';
		}
	?>
	<input type="hidden" name="tipoSolicitud" value="Importación"/>
	<input type="hidden" name="tipoInspector" value="Técnico"/>
	
	<fieldset>
		<legend>Inspectores</legend>
		<input type="hidden" id="idCoordinador" name="idCoordinador" value="<?php echo $_SESSION['usuario'];?>" />
		<div data-linea="5">
			<label>Área pertenece</label> 
				<select id="area" name="area">
					<option value="" selected="selected">Área....</option>
					<?php 
						while($fila = pg_fetch_assoc($area)){
								echo '<option value="' . $fila['id_area'] . '">' . $fila['nombre'] . '</option>';
							}
					?>
				</select>
		
		</div><div data-linea="5">	
			<label>Inspector</label>
				<select id="inspector" name="inspector" disabled="disabled">
				</select>
		 </div>
		
	<button id="detalle" type="submit" class="guardar" >Agregar funcionario</button>
	</fieldset>
</form>

<fieldset>	
	<legend>Inspectores asignados</legend>
		<table>
			<thead>
				<tr>
					<th></th>
					<th># Operación</th>
					<th colspan="2">Inspectores asignados</th>
				<tr>
			</thead>
			<tbody id="opcion_inspector">
				<?php
					//if(count ($importacion)!=0){
					if($importacion[0]!=null){
						for ($i = 0; $i < count ($importacion); $i++) {	
							$res = $ci->listarInspectoresAsignados($conexion, $importacion[$i], 'Importación', 'Técnico');
														
							while($fila = pg_fetch_assoc($res)){
								
								echo "<tr id='r_".$fila['identificador_inspector'].$fila['id_solicitud']."'>
										<td> 
											<form id='f_".$fila['identificador_inspector'].$fila['id_solicitud']."' data-rutaAplicacion='importaciones' data-opcion='quitarInspector'>
												<button type='submit' class='menos'>Quitar</button>
												<input name='tipoSolicitud' value='Importación' type='hidden'>
												<input name='tipoInspector' value='Técnico' type='hidden'>
												<input name='idOperacion' value='".$fila['id_solicitud'] ."' type='hidden'>
												<input name='idInspector' value='".$fila['identificador_inspector'] ."' type='hidden'>
											</form>
										</td>
										<td>".$fila['id_solicitud']."</td>
										<td>".$fila['apellido'].", ".$fila['nombre']."</td>
									</tr>";
							}
						} 
					}  
				?>
			</tbody>
			
		</table>
	</fieldset>
</body>

<script type="text/javascript">
	var array_importacion= <?php echo json_encode($importacion); ?>;
	var array_inspector= <?php echo json_encode($inspector); ?>;

	$(document).ready(function(){
		distribuirLineas();
		if(array_importacion == ''){
			$("#detalleItem").html('<div class="mensajeInicial">Seleccione una o varias importaciones y a continuación presione el botón asignar inspector.</div>');
		}
		construirValidador();
	});
	
	$("#area").change(function(){
		sinspector ='0';
		sinspector = '<option value="">Apellido, Nombre...</option>';
	    for(var i=0;i<array_inspector.length;i++){
		    if ($("#area").val()==array_inspector[i]['area']){
		    	sinspector += '<option value="'+array_inspector[i]['identificador']+'">'+array_inspector[i]['apellido']+', '+array_inspector[i]['nombre']+'</option>';
			    }
	   		}
	    $('#inspector').html(sinspector);
	    $('#inspector').removeAttr("disabled");
	 });
	
	$("#asignarInspector").submit(function(event){
		event.preventDefault();

		chequearCamposInspector(this);
	});

	$("#opcion_inspector").on("submit","form",function(event){
		event.preventDefault();
		ejecutarJson($(this));
		if($("#estado").html()=='El inspector ha sido eliminado satisfactoriamente.' || $("#estado").html()=='Debe asignar la solicitud a un nuevo inspector.'){
			var texto=$(this).attr('id').substring(2);
			texto=texto.replace(/ /g,'');
			texto="#r_"+texto;
			$("#opcion_inspector tr").eq($(texto).index()).remove();
		}	
	});

	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

	function chequearCamposInspector(form){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#area").val()) || !esCampoValido("#area")){
			error = true;
			$("#area").addClass("alertaCombo");
		}

		if(!$.trim($("#inspector").val()) || !esCampoValido("#inspector")){
			error = true;
			$("#inspector").addClass("alertaCombo");
		}

		if (error == true){
			$("#estado").html("Por favor ingrese la información solicitada.").addClass('alerta');
		}else{
			$("#estado").html("").removeClass('alerta');
			
			for(var i=0;i<array_importacion.length;i++){
				if ($("#opcion_inspector #r_"+$("#inspector").val()+array_importacion[i]).length!=0){
			       	error=true;
					break;
		   		}
			}

			if (!error){
				for(var i=0;i<array_importacion.length;i++){
					if ($("#opcion_inspector #r_"+$("#inspector").val()+array_importacion[i]).length==0){
				       	$("#opcion_inspector").append("<tr id='r_"+$("#inspector").val()+array_importacion[i]+"'><td><form id='f_"+$("#inspector").val()+array_importacion[i]+"' data-rutaAplicacion='importaciones' data-opcion='quitarInspector'><button type='submit' class='menos'>Quitar</button><input name='tipoInspector' value='Técnico' type='hidden'><input name='tipoSolicitud' value='Importación' type='hidden'><input name='idOperacion' value='"+array_importacion[i]+"' type='hidden'><input name='idInspector' value='"+$("#inspector").val()+"' type='hidden'></form></td><td>"+array_importacion[i]+"</td><td>"+$("#inspector  option:selected").text()+"</td></tr>");
			   		}
				}
				ejecutarJson(form);
			}else{
				$("#estado").html("El inspector ya está asignado para realizar una inspección.").addClass('alerta');
			}
		}
	}
</script>

</html>