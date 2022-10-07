<div class="modal" tabindex="-1" role="dialog" id="modalConfirmacionAgregarAnalisis">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirmar</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>No existe el tiempo estimado. ¿Desea agregar de todos modos?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" id="modal-btn-si-ca">Si</button>
        <button type="button" class="btn btn-primary" id="modal-btn-no-ca">No</button>
      </div>
    </div>
  </div>
</div>

<script>
    $("#modal-btn-si-ca").click(function () {
        tiempoRespuesta = 8;
        fn_agregarFila();
        $('#modalConfirmacionAgregarAnalisis').modal('hide');
    });
    $("#modal-btn-no-ca").click(function () {
        $('#modalConfirmacionAgregarAnalisis').modal('hide');
    });
</script>