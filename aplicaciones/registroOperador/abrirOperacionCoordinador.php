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

//$area = $ca->listarAreas($conexion);
$area = $cu->obtenerAreaUsuario($conexion, $_SESSION['usuario']);
$usuario = $cu->obtenerUsuariosXarea($conexion);

while($fila = pg_fetch_assoc($usuario)){
	$inspector[]= array(identificador=>$fila['identificador'], apellido=>$fila['apellido'], nombre=>$fila['nombre'], area=>$fila['id_area']);
}

$qSolicitud = $cr->abrirOperacionRevision($conexion, $_POST['id']);

?>

<pre><?php //print_r ($_SESSION);?></pre>
<header>
	<h1>Asignar técnico para revisión</h1>
</header>
<div id="estado"></div>
	
	<fieldset>
			<legend>Registro de Operador</legend>
			<div data-linea="1">
				<label>Tipo de operación: </label> <?php echo $qSolicitud[0]['tipoOperacion']; ?> <br/>
			</div>
			<?php 
   	 			if($qSolicitud[0]['nombrePais'] != ''){
    			 	echo '<div data-linea="1">
      				<label>País: </label>' .  $qSolicitud[0]['nombrePais'] .'</div>';
			    }
			  ?>
			<div data-linea="2">
				<label>Nombre del producto: </label> <?php echo	$qSolicitud[0]['producto'];	?> <br/>	
			</div>
			<div data-linea="3">
				<label>Estado de solicitud: </label> <?php echo $qSolicitud[0]['estado']; ?> <br/>
			</div>
	</fieldset>

	<?php 
		$numeroAreaProduccion=1;
		foreach ($qSolicitud as $solicitud){
			echo '
			<fieldset>
				<legend>Área de Producción ' . $numeroAreaProduccion . '</legend>					
					<div data-linea="3">
						<label>Nombre del sitio: </label> ' . $solicitud['nombreSitio'] . ' <br/>
					</div>
					<div data-linea="4">
						<label>Nombre del área: </label> ' . $solicitud['nombreArea'] . ' <br/>
					</div>
					<div data-linea="4">
						<label>Tipo de área: </label> ' . $solicitud['tipoArea'] . ' <br/>
					</div>
					<div data-linea="5">
						<label>Provincia: </label> ' . $solicitud['provincia'] . ' <br/>
					</div>
					<div data-linea="5">
						<label>Cantón: </label> ' . $solicitud['canton'] . ' <br/>
					</div>
					<div data-linea="6">
						<label>Parroquia: </label> ' . $solicitud['parroquia'] . ' <br/>
					</div>
					<div data-linea="7">
						<label>Dirección: </label> ' . $solicitud['direccionSitio'] . ' <br/>
					</div>
					<div data-linea="8">
						<label>Referencia: </label> ' . $solicitud['referencia'] . ' <br/>
					</div>
					<div data-linea="9">
						<label>Superficie utilizada: </label> ' . $solicitud['superficieArea'] . ' <br/>
					</div>
					<div data-linea="9">			
						<label>Croquis: </label> <a href="'. $solicitud['croquis'].'" target="_blank">Descargar croquis</a> <br/>
					</div>
			</fieldset>';
			$numeroAreaProduccion++;
		}
		
		$numeroAreaProduccion=1;
		$res = $cr->listarProveedoresOperador($conexion, $_POST['id']);
											
		while($fila = pg_fetch_assoc($res)){
			$proveedor = ($fila['codigo_proveedor']=='')?'Extranjero':$fila['codigo_proveedor'];
			echo "<fieldset>
					<legend>Proveedor ". $numeroAreaProduccion ."</legend>
						<div data-linea='12'>
				  			<label>Proveedor: </label> " . $proveedor . " <br/>
							<label>Producto: </label> " . $fila['nombre_comun'] . " <br/>
							<label>País: </label> " . $fila['nombre'] . " <br/>
						</div>
				</fieldset>
			";
			$numeroAreaProduccion++;
		}
	?>

<form id='asignarInspector' data-rutaAplicacion='registroOperador' data-opcion='guardarNuevoInspector' data-destino="detalleItem">	
	<fieldset>
		<legend>Inspectores</legend>
		<div data-linea="12">
			<label>Área pertenece</label> 
				<select id="area" name="area">
					<option value="" selected="selected">Área....</option>
					<?php 
						while($fila = pg_fetch_assoc($area)){
								echo '<option value="' . $fila['id_area'] . '">' . $fila['nombre'] . '</option>';
							}
					?>
				</select>
		
		</div><div data-linea="12">	
			<label>Inspector</label>
				<select id="inspector" name="inspector" disabled="disabled">
				</select>
		 </div>
				
		<div id="opcion_inspector"></div>
		
		<button type="button" onclick="agregarInspector()" class="mas">Agregar funcionario</button>
		
		<table>
			<thead>
				<tr>
					<th colspan="2">Inspectores asignados</th>
				<tr>
			</thead>
			<tbody id="inspectores">
			</tbody>
		</table>
	</fieldset>	
</form>	
<script type="text/javascript">					
	var array_inspector= <?php echo json_encode($inspector); ?>;
	
	$(document).ready(function(){
		distribuirLineas();
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
	
	function agregarInspector(){
		if($("#inspectores #r_"+$("#inspector").val()).length==0)
			$("#inspectores").append("<tr id='r_"+$("#inspector").val()+"'><td><button type='button' onclick='quitarInspectores(\"#r_"+$("#inspector").val()+"\")' class='menos'>Quitar</button></td><td>"+$("#inspector  option:selected").text()+"<input name='inspector_id[]' value='"+$("#inspector").val()+"' type='hidden'><input name='inspector_nombre[]' value='"+$("#inspector  option:selected").text()+"' type='hidden'></td></tr>");
	}
	
	function quitarInspectores(fila){
		$("#inspectores tr").eq($(fila).index()).remove();
	}
	
	$("#asignarInspector").submit(function(event){
			event.preventDefault();
			ejecutarJson($(this));
	});
</script>