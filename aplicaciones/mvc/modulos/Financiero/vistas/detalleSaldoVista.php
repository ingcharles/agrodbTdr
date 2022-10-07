<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<?php
$identificadorOperador = $this->identificadorOperador;
$fechaInicio = $this->fechaInicio;
$fechaFin = $this->fechaFin;
?>

<?php echo $this->datosUsuario; ?>
<?php echo $this->datosFacturaConSaldo; ?>


<script type="text/javascript">
	var identificador = '';
	var fechaInicio = '';
	var fechaFin = '';
	var offset = 0;
	var contador = 20;
	var banderaRecarga = true;

	$(document).ready(function() {
		distribuirLineas();
		identificador = <?php echo json_encode($identificadorOperador); ?>;
		fechaInicio = <?php echo json_encode($fechaInicio); ?>;
		fechaFin = <?php echo json_encode($fechaFin); ?>;

	});

	$("#detalleItem").scroll(function(event) {
		if (banderaRecarga) {
			if ($("#detalleItem").scrollTop() + $("#detalleItem").innerHeight() >= $("#detalleItem")[0].scrollHeight - 0.1) {
				event.preventDefault();
				event.stopImmediatePropagation();
				offset += 30;
				$.post("<?php echo URL ?>Financiero/Saldos/cargarDetalleSaldos", {
					identificador: identificador,
					fechaInicio: fechaInicio,
					fechaFin: fechaFin,
					offset: offset,
					contador: contador
				}, function(data) {
					if (data.estado == 'EXITO') {
						if (data.contenido == '') {
							$("#detalleSaldos").append(data.contenido);
						} else {
							banderaRecarga = false;
						}
					}
				}, 'json');

			}
		}
	});
</script>