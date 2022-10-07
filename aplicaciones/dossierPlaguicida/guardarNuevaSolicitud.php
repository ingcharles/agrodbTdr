<?php
session_start();

require_once '../../clases/Conexion.php';
require_once '../ensayoEficacia/clases/Transaccion.php';

require_once '../../clases/ControladorEnsayoEficacia.php';
require_once '../../clases/ControladorDossierPlaguicida.php';

$idUsuario= $_SESSION['usuario'];
$id_solicitud = $_POST['id_solicitud'];

$id_flujo = $_POST['id_flujo'];

$conexion = new Transaccion();

$ce = new ControladorEnsayoEficacia();
$cg=new ControladorDossierPlaguicida();

$dato['identificador'] = $idUsuario;
$dato['normativa'] = htmlspecialchars ($_POST['normativa'],ENT_NOQUOTES,'UTF-8');
$dato['motivo'] = htmlspecialchars ($_POST['motivo'],ENT_NOQUOTES,'UTF-8');
$dato['es_clon'] = $ce->normalizarBoolean($_POST['es_clon']);
$dato['clon_registro_madre'] = htmlspecialchars ($_POST['clon_registro_madre'],ENT_NOQUOTES,'UTF-8');
$dato['protocolo'] = htmlspecialchars ($_POST['protocolo'],ENT_NOQUOTES,'UTF-8');

$dato['id_categoria_toxicologica'] = intval ($_POST['id_categoria_toxicologica']);


$dato['nivel']=intval($_POST['nivel']);


if($id_solicitud==null || $id_solicitud=='_nuevo'){

	try{

		//Reserva uso del ensayo de eficacia
		$conexion->Begin();
		if(($dato['es_clon']=='0') && ($dato['protocolo']!=null)){
			$items=$ce->obtenerProtocoloDesdeExpediente($conexion,$dato['protocolo']);
			$datoProtocolo['id_protocolo']=$items['id_protocolo'];
			$datoProtocolo['estado_dossier']='P';
			$ce->guardarProtocolo($conexion,$datoProtocolo);
		}
		$res=$cg->guardarSolicitud($conexion,$dato);
		$conexion->Commit();
		if($res['tipo']=="insert"){
			$id_solicitud = $res['resultado'][0]['id_solicitud'];
		}
	}catch(Exception $e){
		$conexion->Rollback();
	}

}


?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />

</head>
<body>

	<header>
		<h1>Guardando solicitud de dossier pecuario</h1>
	</header>

	<div id="estado"></div>

   <form id="frmGuardar" data-rutaaplicacion="dossierPlaguicida" data-opcion="abrirSolicitudDossier" data-destino='detalleitem' data-accionenexito="ACTUALIZAR">
      <input type="hidden" id="id" name="id" value="<?php echo $id_solicitud;?>" />
      <input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>" />
		<input type="hidden" id="idFlujo" name="idFlujo" value="<?php echo $id_flujo;?>" />
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

	form.attr('data-opcion', 'abrirSolicitudDossier');
	form.attr('data-destino', 'detalleItem');
	
	$("#estado").html("");

	abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"), null, true);
	abrir(form, event, true);
	
});



</script>

</html>


