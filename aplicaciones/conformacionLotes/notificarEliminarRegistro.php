<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorLotes.php';

$conexion = new Conexion();
$cl = new ControladorLotes();
//$idRegistro= $_POST['id'];
//$res = $cl->ObtenerRegistro($conexion,$_POST['id']);
//$filaRegistro = pg_fetch_assoc($res);
//$contratos=explode(",",$_POST['elementos']);
$usuario=$_SESSION['usuario'];
?>

<header>
	<h1>Confirmar Eliminación</h1>
</header>

<div id="estado"></div>
<?php

$registros=explode(",",$_POST['elementos']);

if(count ($registros) <2 ){
	echo "<p>El <b>registro</b> a ser eliminado es: </p>";
} else{
	echo "<p>Los <b>registros</b> a ser eliminados son: </p>";
}
				
		
		for ($i = 0; $i < count ($registros); $i++) {
			
			$res = $cl->ObtenerRegistro($conexion, $registros[$i]);
			$fila = pg_fetch_assoc($res);
			
			echo '<fieldset>
					<legend>Datos del Registro</legend>
						<div>
							 <label>Código Ingreso: </label>' .$fila['codigo_registro'].' <label style="margin-left:50px">Fecha Ingreso:</label> ' .date('Y-m-d',strtotime($fila['fecha_ingreso'])). '<br/>' . 
							'<label>Producto: </label>' .$fila['nombre_producto']. '<br/>' .
							'<label>Nombre del Proveedor: </label>' .$fila['nombre_proveedor'].'<br/>' .
							'<label>Identificación del Proveedor: </label>' .$fila['identificador_proveedor'].'<br/>' .
							'<label>Variedad: </label>' .$fila['variedad']. '<label style="margin-left:100px">Cantidad a registrar: </label>' .$fila['cantidad']. '<br/>' ;							
							
				if($fila['estado']=='2'){
					echo '<br/><label>Nota: </label><span style="color:red;font-weight:bold">No se puede eliminar el registro ya que se encuentra ingresado en un lote.<span>' ;
				}
		  echo '</div>
			   </fieldset>';
		}		
		
		
	?>
	
	<form id="eliminarRegistro" data-rutaAplicacion="conformacionLotes" data-opcion="eliminarProductoProveedor" data-destino="detalleItem" data-accionenexito="actualizar">


			<input type="hidden" name="id" value="<?php echo $_POST['elementos'];?>"/>
			
	 <button id="eliminar" type="submit" class="eliminar" >Eliminar Registro</button>
	
</form>

<style>
.prueba{
width:50% !important;
}

</style>

<script type="text/javascript">

//var df = dateFormat(d,"yyyy,m,d")

$("document").ready(function(){	
	var array_registros= <?php echo json_encode($registros); ?>;
	distribuirLineas();
	construirValidador();
	if(array_registros == '')
		$("#detalleItem").html('<div class="mensajeInicial">Seleccione un registro para ser eliminado.</div>');

	if($("#nEliminar").text()){
		$("#notificarEliminarRegistro").hide();
	}
	
});


$("#eliminarRegistro").submit(function(event){
	event.preventDefault();
	ejecutarJson($(this));
	
	if($("#estado").html()=="Los datos han sido actualizados satisfactoriamente"){
		//alert($("#estado").html());
		$("#_actualizar").click();
	}

});


</script>
