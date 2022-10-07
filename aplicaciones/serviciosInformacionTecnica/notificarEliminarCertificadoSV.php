<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorServiciosInformacionTecnica.php';

$conexion = new Conexion();
$controladorInformacion = new ControladorServiciosInformacionTecnica();
?>

<header>
	<h1>Confirmar Eliminación</h1>
</header>

<div id="estado"></div>
<?php

$registros=explode(",",$_POST['elementos']);

if(count ($registros) <2 ){
	echo "<p>El <b>certificado</b> a ser eliminado es: </p>";
} else{
	echo "<p>Los <b>certificados</b> a ser eliminados son: </p>";
}
		
			
$respuesta = $controladorInformacion->obtenerCertificadoXId($conexion, $_POST['elementos']);
while($fila = pg_fetch_assoc($respuesta)){

    echo '<fieldset>
    		<legend>Datos del Certificado</legend>
    			<div>
    				 <label>País: </label>' .$fila['nombre'].'<br/>' . 
    				'<label>Certificado: </label>' .$fila['certificado']. '<br/>' .
    				'<label>Fecha de Ingreso: </label>' .date('Y-m-d',strtotime($fila['fecha_ingreso'])).'<br/>' ;										
    	
    echo '</div>
       </fieldset>';
}		
		
		
	?>
	
	<form id="eliminarRegistro" data-rutaAplicacion="serviciosInformacionTecnica" data-opcion="eliminarCertificadoSV" data-destino="detalleItem" data-accionenexito="actualizar">


			<input type="hidden" name="id" value="<?php echo $_POST['elementos'];?>"/>
			
	 <button id="eliminar" type="submit" class="eliminar" >Eliminar</button>
	
</form>

<style>
.prueba{
width:50% !important;
}

</style>

<script type="text/javascript">

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
		$("#_actualizar").click();
	}

});


</script>
