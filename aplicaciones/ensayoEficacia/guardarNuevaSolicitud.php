<?php
session_start();
require_once '../../clases/Conexion.php';

require_once '../../clases/ControladorEnsayoEficacia.php';


try{
	$conexion = new Conexion();
	$ce = new ControladorEnsayoEficacia();
	//miro si solicitud ya existe
	$datoProtocolo=array();
	$yaExiste=false;
	$identificador= $_SESSION['usuario'];
	try{
		$idProtocolo=$_POST['id_protocolo'];
		if($idProtocolo==null || $idProtocolo=='_nuevo' || $idProtocolo=='0'){
			$datoProtocolo['identificador'] = $identificador;
		}
		else{
			$datoProtocolo['id_protocolo'] = $idProtocolo;
		}
	}catch(Exception $e){}

	
	$datoProtocolo['direccion_referencia'] = htmlspecialchars ($_POST['dirReferencia'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['ci_representante_legal'] = htmlspecialchars ($_POST['ciLegal'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['email_representante_legal'] = htmlspecialchars ($_POST['correoLegal'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['normativa'] = htmlspecialchars ($_POST['normativa'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['motivo'] = htmlspecialchars ($_POST['motivo'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['cultivo_menor'] = htmlspecialchars ($_POST['boolModalidad'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['cultivo_menor']=$ce->normalizarBoolean($datoProtocolo['cultivo_menor']);
	$datoProtocolo['ci_tecnico_reconocido'] = htmlspecialchars ($_POST['ciTecnico'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['cultivo'] = htmlspecialchars ($_POST['cultivoNomCien'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['cultivo_comun'] = htmlspecialchars ($_POST['cultivoNomComun'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['uso'] = htmlspecialchars ($_POST['subTipoProducto'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['complejo_fungico'] = htmlspecialchars ($_POST['boolFungico'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['complejo_fungico']=$ce->normalizarBoolean($datoProtocolo['complejo_fungico']);
	if($datoProtocolo['cultivo_comun']==null)
		$datoProtocolo['cultivo_comun']=$datoProtocolo['cultivo'];
	$datoProtocolo['nivel']=intval($_POST['nivel']);

	try {


		$res=$ce -> guardarProtocolo($conexion,$datoProtocolo);

		if($res['tipo']=="insert"){
			$idProtocolo = $res['resultado'][0]['id_protocolo'];
			
		}
		
		
	} catch (Exception $ex){		}
} catch (Exception $ex) {}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />

</head>
<body>

	<header>
		<h1>Guardando solicitud de protocolo</h1>
	</header>

	<div id="estado"></div>

	<form id="frmGuardar" data-rutaaplicacion="ensayoEficacia" data-opcion="abrirSolicitudProtocolo" data-destino='detalleitem' data-accionenexito="ACTUALIZAR">
		<input type="hidden" id="id" name="id" value="<?php echo $idProtocolo;?>" />
		
		<fieldset>
			<legend>Importante...</legend>
			<label>La solicitud ha sido creada con el codigo temporal :</label>
			<input value="<?php echo $idProtocolo.' '; ?>" disabled="disabled" />
			<label>
				tiene 15 días para completar el formulario y enviarlo
			</label>

		</fieldset>

		<button id="btnGuardar" type="button" class="guardar">Regresar</button>
	</form>

</body>



<script type="text/javascript">

	var id_protocolo= <?php echo json_encode($idProtocolo); ?>;

	$(document).ready(function(){

});

$('#btnGuardar').click(function (event) {
	event.preventDefault();

	var form=$(this).parent();

	form.attr('data-opcion', 'abrirSolicitudProtocolo');
	form.attr('data-destino', 'detalleItem');

	$("#estado").html("");
	abrir(form, event, true); //Se ejecuta ajax, busqueda de sitios
	abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"), null, true);
});



</script>

</html>
