<header>
    <h1>Datos de la muestra</h1>
</header>
<fieldset>
    <legend>Datos de la muestra</legend>
<?php echo $this->modeloRecepcionMuestras->getObservacionAnalisis(); ?>
    <div class="form-horizontal">
        <div class="form-group">
            <label class="col-lg-4 control-label">Observaci&oacute;n recepci&oacute;n</label>
            <div class="col-lg-6">
                <textarea readonly cols="50" style="background-color: #E4E4E4;"><?php echo $this->modeloRecepcionMuestras->getObservacionRecepcion(); ?></textarea>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-4 control-label">Observaci&oacute;n verificaci&oacute;n</label>
            <div class="col-lg-6">
                <textarea readonly style="background-color: #E4E4E4"><?php echo $this->modeloRecepcionMuestras->getObservacionVerificacion(); ?></textarea>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-4 control-label">Observaci&oacute;n an&aacute;lisis</label>
            <div class="col-lg-6">
                <textarea readonly style="background-color: #E4E4E4"><?php echo $this->modeloRecepcionMuestras->getObservacionAnalisis(); ?></textarea>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-4 control-label">Observaci&oacute;n validaci&oacute;n</label>
            <div class="col-lg-6">
                <textarea readonly style="background-color: #E4E4E4"><?php echo $this->modeloRecepcionMuestras->getObservacionAprobacion(); ?></textarea>
            </div>
        </div>
    </div>
    <table class="table table-bordered">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Caracteristica</th>
      <th scope="col">Descripción</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row">1</th>
      <td>Código de usuario</td>
      <td><?php echo $this->modeloRecepcionMuestras->getCodigoUsuMuestra(); ?></td>
    </tr>
    <tr>
      <th scope="row">2</th>
      <td>Código de laboratorio</td>
      <td><?php echo $this->modeloRecepcionMuestras->getCodigoLabMuestra(); ?></td>     
    </tr>
    <tr>
      <th scope="row">3</th>
      <td >Fecha inicio de análisis</td>
      <td><?php echo $this->modeloRecepcionMuestras->getFechaInicioAnalisis(); ?></td>    
    </tr>
    <tr>
      <th scope="row">4</th>
      <td >Fecha Fin de análisis</td>
      <td><?php echo $this->modeloRecepcionMuestras->getFechaFinAnalisis(); ?></td>    
    </tr>
     <tr>
      <th scope="row">4</th>
      <td >Estado actual</td>
      <td><?php echo $this->modeloRecepcionMuestras->getEstadoActual(); ?></td>    
    </tr>
  </tbody>
</table>
</fieldset>
