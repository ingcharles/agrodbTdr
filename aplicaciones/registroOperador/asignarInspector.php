<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorAreas.php';
require_once '../../clases/ControladorUsuarios.php';

$conexion = new Conexion();
$cr = new ControladorRegistroOperador();
$cc = new ControladorCatalogos();
$ca = new ControladorAreas();
$cu = new ControladorUsuarios();

$operacion=explode(",",$_POST['elementos']);
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

	<p>Las <b>operaciones</b> a ser asignadas son: </p>
 
        <?php
			//if(count ($operacion)!=0){
			if($operacion[0]!=null){
				for ($i = 0; $i < count ($operacion); $i++) {
					$qSolicitud = $cr->abrirOperacionRevision($conexion, $operacion[$i]);
					
					echo'
						<fieldset>
							<legend>Operación N° </label>' .$qSolicitud[0]['idSolicitud'].'</legend>
								<div data-linea="1">
									<label>RUC operador: </label>'. $qSolicitud[0]['ruc'] .'<br/>
								</div>
								<div data-linea="2">
									<label>Representante Legal: </label>'. $qSolicitud[0]['nombreRepresentante'] .' '. $qSolicitud[$i]['apellidoRepresentante'] .'<br/>
								</div>
								<div data-linea="3">
									<label>Tipo de operación: </label>'. $qSolicitud[0]['tipoOperacion'] .'<br/>
								</div>
								<div data-linea="3">
									<label>Nombre del producto: </label>'. $qSolicitud[0]['producto'] .'<br/>	
								</div>
								<div data-linea="4">
									<label>Estado de solicitud: </label>'. $qSolicitud[0]['estado'] .'<br/>
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

<form id='asignarInspector' data-rutaAplicacion='registroOperador' data-opcion='guardarNuevoInspector' data-destino="detalleItem">	
	<?php 
		for ($i = 0; $i < count ($operacion); $i++) {
			echo'<input type="hidden" name="id[]" value="'.$operacion[$i].'"/>';
		}
	?>
	
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
					//if(count ($operacion)!=0){
					if($operacion[0]!=null){
						for ($i = 0; $i < count ($operacion); $i++) {	
							$res = $cr->listarInspectoresAsignados($conexion, $operacion[$i]);
														
							while($fila = pg_fetch_assoc($res)){
								
								echo "<tr id='r_".$fila['identificador'].$fila['id_operacion']."'>
										<td> 
											<form id='f_".$fila['identificador'].$fila['id_operacion']."' data-rutaAplicacion='registroOperador' data-opcion='quitarInspector'>
												<button type='submit' class='menos'>Quitar</button>
												<input name='idOperacion' value='".$fila['id_operacion'] ."' type='hidden'>
												<input name='idInspector' value='".$fila['identificador'] ."' type='hidden'>
											</form>
										</td>
										<td>".$fila['id_operacion']."</td>
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
	var array_operacion= <?php echo json_encode($operacion); ?>;
	var array_inspector= <?php echo json_encode($inspector); ?>;

	$(document).ready(function(){
		distribuirLineas();
		if(array_operacion == ''){
			$("#detalleItem").html('<div class="mensajeInicial">Seleccione una o varias operaciones y a continuación presione el botón asignar inspector.</div>');
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
		
		//if ($("#opcion_inspector #r_"+$("#inspector").val()).length==0){
		//	for(var i=0;i<array_operacion.length;i++){
		//       	$("#opcion_inspector").append("<tr id='r_"+$("#inspector").val()+"'><td><form id='f_"+$("#inspector").val()+"' data-rutaAplicacion='registroOperador' data-opcion='quitarInspector'><button type='submit' class='menos'>Quitar</button><input name='idOperacion' value='"+array_operacion[i]+"' type='hidden'><input name='idInspector' value='"+$("#inspector").val()+"' type='hidden'></form></td><td>"+array_operacion[i]+"</td><td>"+$("#inspector  option:selected").text()+"</td></tr>");
	   	//	}
		//}

		//ejecutarJson($(this));
	});

	$("#opcion_inspector").on("submit","form",function(event){
		event.preventDefault();
		ejecutarJson($(this));
		if($("#estado").html()=='El inspector ha sido eliminado satisfactoriamente.' || $("#estado").html()=='Debe asignar la solicitud a un nuevo inspector.'){
			//alert('hola');
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
			
			for(var i=0;i<array_operacion.length;i++){
				if ($("#opcion_inspector #r_"+$("#inspector").val()+array_operacion[i]).length!=0){
			       	error=true;
					break;
		   		}
			}

			if (!error){
				for(var i=0;i<array_operacion.length;i++){
					if ($("#opcion_inspector #r_"+$("#inspector").val()+array_operacion[i]).length==0){
				       	$("#opcion_inspector").append("<tr id='r_"+$("#inspector").val()+array_operacion[i]+"'><td><form id='f_"+$("#inspector").val()+array_operacion[i]+"' data-rutaAplicacion='registroOperador' data-opcion='quitarInspector'><button type='submit' class='menos'>Quitar</button><input name='idOperacion' value='"+array_operacion[i]+"' type='hidden'><input name='idInspector' value='"+$("#inspector").val()+"' type='hidden'></form></td><td>"+array_operacion[i]+"</td><td>"+$("#inspector  option:selected").text()+"</td></tr>");
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