<link rel='stylesheet'
      href='<?php echo URL_RESOURCE ?>estilos/bootstrap.min.css'>
<script
src="<?php echo URL_RESOURCE ?>js/bootstrap.min.js" type="text/javascript"></script>
<div class="modal" tabindex="-1" role="dialog" id="modalSolicitudLaboratorio">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Seleccionar el Laboratorio que solicita para crear la solicitud</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Laboratorio - Provincia</label>
                        <div class="col-lg-6">
                            <select id="id_laboratorios_provincia" class="form-control" required>
                                <option value="">Seleccionar....</option>
                                <?php
                                echo $this->comboUsuarioLaboratoriosPrincipal();
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" id="modal-btn-si">Continuar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $("#modal-btn-si").click(function () {
        $("#formulario #id_laboratorios_provincia").val($(this).val());
        if ($("#id_laboratorios_provincia").val() === "") {
            mostrarMensaje("Seleccione el Laboratorio.", "FALLO");
        } else {
            $('#modalSolicitudLaboratorio').modal('hide');
            fn_agregar();
        }
    });
</script>