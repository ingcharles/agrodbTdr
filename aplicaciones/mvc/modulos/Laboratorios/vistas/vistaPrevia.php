<link rel='stylesheet' href='<?php echo URL_RESOURCE ?>estilos/fSelect.css'>
<link rel='stylesheet' href='<?php echo URL_MVC_MODULO ?>Laboratorios/vistas/estilos/estiloSolicitudes.css'>

<header>
    <h1><?php echo $this->accion; ?></h1>
</header>

<form>
    <div id="div_form_muestra" style="width: 100%;">
        <?php echo $this->camposMuestrasPrevio; ?>
    </div>
    <div id="div_form_analisis" style="width: 100%;">
        <?php echo $this->camposAnalisisPrevio; ?>
    </div>
</form>
<script type="text/javascript">
    $('.checklist').fSelect();
    distribuirLineas();
    <?php echo $this->codigoJS; ?>
</script>
