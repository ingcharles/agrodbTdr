<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEnsayoEficacia.php';
require_once '../../clases/ControladorDossierPlaguicida.php';

$idUsuario= $_SESSION['usuario'];
$id_solicitud = $_POST['id_solicitud'];

$conexion = new Conexion();

$ce = new ControladorEnsayoEficacia();
$cg=new ControladorDossierPlaguicida();

$dato['identificador'] = $idUsuario;
$dato['tipo_modificacion'] = htmlspecialchars ($_POST['tipo_modificacion'],ENT_NOQUOTES,'UTF-8');
$dato['registro'] = htmlspecialchars ($_POST['registro'],ENT_NOQUOTES,'UTF-8');

$dato['nivel']=intval($_POST['nivel']);


if($id_solicitud==null || $id_solicitud=='_nuevo'){

	try{

		$res=$cg->guardarModificaciones($conexion,$dato);
		if($res['tipo']=="insert"){
			$id_solicitud = $res['resultado'][0]['id_modificacion'];
		}
	}catch(Exception $e){}

}


?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />

</head>
<body>

	<header>
		<h1>Guardando solicitud de modificación de registro</h1>
	</header>

	<div id="estado"></div>

	<form id="frmGuardar" data-rutaaplicacion="dossierPlaguicida" data-opcion="abrirSolicitudModificacion" data-destino='detalleitem' data-accionenexito="ACTUALIZAR">
		<input type="hidden" id="id" name="id" value="<?php echo $id_solicitud;?>" />
		<input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>" />
		<fieldset>
			<legend>Importante...</legend>
			<label>La solicitud ha sido creada con el codigo temporal :</label>
			<input value="<?php echo $id_solicitud.' '; ?>" disabled="disabled" />
			<label>
				tiene 15 días para completar el formulario y enviarlo
			</label>

		</fieldset>

		<button id="btnGuardar" type="button" class="guardar">Regresar</button>
	</form>

</body>



<script type="text/javascript">

	var $id_solicitud= <?php echo json_encode($id_solicitud); ?>;

	$(document).ready(function(){

});

$('#btnGuardar').click(function (event) {
	event.preventDefault();

	var form=$(this).parent();

	form.attr('data-opcion', 'abrirSolicitudModificacion');
	form.attr('data-destino', 'detalleItem');
	
	$("#estado").html("");

	abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"), null, true);
	abrir(form, event, true);
	
});



</script>

</html>
