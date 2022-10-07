<link rel='stylesheet'
      href='<?php echo URL_RESOURCE ?>estilos/bootstrap.min.css'>
<script
src="<?php echo URL_MVC_MODULO ?>Laboratorios/vistas/js/laboratoriosjs.js" type="text/javascript"></script>
<header>
    <h1>Muestras de la Orden de Trabajo</h1>
</header>
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Laboratorios'
      data-opcion='' data-destino="detalleItem"
      data-accionEnExito="ACTUALIZAR" method="post">
    <fieldset>
        <legend>Muestras de la Orden de Trabajo</legend>
        <table id="muestras">
            <thead>
                <tr>
                    <th>#</th>
                    <th title="Opci&oacute;n para ver datos de la muestra en ventana modal">Datos</br>Muestra</th>
                    <th title="C&oacute;digo de campo de la muestra">C&oacute;digo de campo</th>
                    <th title="Nombre del an&aacute;lisis">An&aacute;lisis</th>
                    <th title="Para seleccionar si la muestra es id&oacute;nea o no">Muestra Id&oacute;nea?</th>
                    <th title="Fecha de inicio del an&aacute;lisis">Fecha inicio An&aacute;lisis</th>
                    <th title="">Observaci&oacute;n recepci&oacute;n</th>
                    <th title="">Observaci&oacute;n verificac&oacute;n</th>
                    <th title="">Observaci&oacute;n an&aacute;lisis</th>
                    <th title="">Observaci&oacute;n validac&oacute;n</th>
                    <th title="Estado de la muestra">Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($this->itemsMuestras as $muestra)
                {
                    echo $muestra[0];
                }
                ?>
            </tbody>
        </table>
    </fieldset>
</form>

<!-- Código javascript -->
<script type="text/javascript">
<?php echo $this->codigoJS ?>
    distribuirLineas();
</script>
