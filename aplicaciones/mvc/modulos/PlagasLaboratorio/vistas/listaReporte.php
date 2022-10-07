<header>
	<nav><?php echo $this->panelBusquedaReporte;?></nav>
</header>

<script>

	var datos=<?php echo json_encode($this->listaCultivos); ?>;
	var datosPlaga=<?php echo json_encode($this->listaPlagas); ?>;

	$(document).ready(function () {
		$("#listadoItems").addClass("comunes");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí una solicitud para revisarla.</div>');
	});

	$(function(){
		var cultivo = datos;

		$("#cultivo").autocomplete({
			source: cultivo,
			minLength : 3,
			select: function(event, ui){
				//event.preventDefault();
				$('#id_cultivo').val(ui.item.idCultivo);
				cargarPlagas();
			},
			change:function(event, ui){
				if (ui.item == null || ui.item == undefined) {
					$('#id_cultivo').val("");
					$('#cultivo').val("");
				}
			}
		});
	});

	$(function(){
		var plaga = datosPlaga;

		$("#plaga").autocomplete({
			source: plaga,
			minLength : 3,
			select: function(event, ui){
				//event.preventDefault();
				$('#id_plaga').val(ui.item.idPlaga);
			},
			change:function(event, ui){
				if (ui.item == null || ui.item == undefined) {
					$('#id_plaga').val("");
					$('#plaga').val("");
				}
			}
		});
	});

	function cargarPlagas () {
		$("#plaga").val("");
		$.post("<?php echo URL ?>PlagasLaboratorio/cultivos/listarPlagas",{
		  		id_cultivo: $("#id_cultivo").val()
        }, function (data) {
        	if(data.mensaje.length != 0){
        		$("#plaga").autocomplete({
        			source: data.mensaje,
        			minLength : 3,
        			select: function(event, ui){
        				//event.preventDefault();
        				$('#id_plaga').val(ui.item.idPlaga);
        			},
        			change:function(event, ui){
                		if (ui.item == null || ui.item == undefined) {
                			$("#id_plaga").val("");
                			$('#plaga').val("");
                		}
        			}
        		});
            }else{
            	mostrarMensaje("No existe plagas registradas.", "FALLO");
			}
    		
        }, 'json');
    };

    $("#exportarExcel").submit(function (event) {
		event.preventDefault();
		var error = false;
		ejecutarJson($("#exportarExcel"));
	});

</script>