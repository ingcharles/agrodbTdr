
<script type="text/javascript">
	$(document).ready(function() {
		$(".alertaCombo").removeClass("alertaCombo");
		var msg = <?php echo json_encode ($this->msg); ?>;
		$("#detalleItem").html('<div class="mensajeInicial"><span>'+msg+'.</span></div>');
	  });
</script>
