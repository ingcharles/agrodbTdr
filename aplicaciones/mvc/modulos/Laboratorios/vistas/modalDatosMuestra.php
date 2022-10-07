<div class="modal" tabindex="-1" role="dialog" id="modalDatosMuestra">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Datos de la Muestra</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Observaci&oacute;n recepci&oacute;n</label>
                        <div class="col-lg-6">
                            <textarea readonly><?php echo $this->modeloRecepcionMuestras->getObservacionRecepcion(); ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Observaci&oacute;n verificaci&oacute;n</label>
                        <div class="col-lg-6">
                            <textarea readonly><?php echo $this->modeloRecepcionMuestras->getObservacionVerificacion(); ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Observaci&oacute;n an&aacute;lisis</label>
                        <div class="col-lg-6">
                            <textarea readonly><?php echo $this->modeloRecepcionMuestras->getObservacionAnalisis(); ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Observaci&oacute;n validaci&oacute;n</label>
                        <div class="col-lg-6">
                            <textarea readonly><?php echo $this->modeloRecepcionMuestras->getObservacionAprobacion(); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>