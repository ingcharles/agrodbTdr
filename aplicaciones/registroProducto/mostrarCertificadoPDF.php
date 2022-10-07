<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRequisitos.php';

$cr = new ControladorRequisitos();
$conexion = new Conexion();

$idProducto = $_POST['idProducto'];
$areaProducto = $_POST['idArea'];
?>

<body>

<form id="regresar" data-rutaAplicacion="registroProducto" data-opcion="abrirProducto<?php echo ($areaProducto=='IAP'?'Plaguicida':'');?>" data-destino="detalleItem">
	<input type="hidden" name="idProducto" value="<?php echo $idProducto;?>"/>
	<input type="hidden" name="areaProducto" value="<?php echo $areaProducto;?>"/>
	<button type="submit">Regresar a Producto</button>
</form>

<?php
    $res = $cr->obtenerCertificado($conexion, $idProducto);
	$certificado = pg_fetch_assoc($res);
?>

<embed id="visor" src="<?php echo  $certificado['ruta_certificado']; ?>" width="540" height="550">
</body>

<script>
$("#regresar").submit(function(event){
	event.preventDefault();
	abrir($("#regresar"),event,false);
})

</script>