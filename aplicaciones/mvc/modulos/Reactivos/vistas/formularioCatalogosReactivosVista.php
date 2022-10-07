<header>
    <h1><?php echo $this->accion; ?></h1>
</header>
<script src="<?php echo URL_RESOURCE ?>js/tinymce/tinymce.min.js"></script>
<form id = 'formulario' data-rutaAplicacion = '<?php echo URL_MVC_FOLDER; ?>Reactivos'			
      data-opcion = 'CatalogosReactivos/guardar' data-destino ="detalleItem" data-accionEnExito ="NADA" method="post">
    <fieldset>			<legend>Catalogos</legend>

        <div data-linea="1" >
            <label for="fk_id_catalogos"> Cat&aacute;logo padre </label> 
            <select class="easyui-combotree" name="fk_id_catalogos" id="fk_id_catalogos"/>
        </div>

        <div data-linea ="2">
            <label for="nombre"> Nombre </label> 
            <input type ="text" id="nombre"
                   name ="nombre" value="<?php echo $this->modeloCatalogos->getNombre(); ?>"
                   placeholder ="Nombre del catálogo"
                   required  maxlength="128" />
        </div>

        <div data-linea ="2">
            <label for="nombre"> C&oacute;digo </label> 
            <input type ="text" id="codigo"
                   name ="codigo" value="<?php echo $this->modeloCatalogos->getCodigo(); ?>"
                   placeholder ="Código del catálogo"
                   maxlength="16" />
        </div>

        <label for="descripcion"> Descripci&oacute;n </label> 
        <div data-linea ="3">
            <textarea rows="4" cols="50" id="descripcion"
                      name ="descripcion"
                      placeholder="Descripción del catálogo"><?php echo $this->modeloCatalogos->getDescripcion(); ?></textarea>
        </div>

        <div data-linea ="4">
            <label for="orden"> Orden </label> 
            <input type ="number" id="orden"
                   name ="orden" value="<?php echo $this->modeloCatalogos->getOrden(); ?>"
                   placeholder ="Orden del catálogo"
                   required maxlength="3" min="1"/>
        </div>

        <div data-linea ="4">
            <label>Estado</label> 
            <select name="estado">
                <?php echo $this->combo2Estados($this->modeloCatalogos->getEstado()); ?>
            </select>
        </div>

        <div data-linea ="5">			
            <input type ="hidden" name="id_catalogos" id="id_catalogos" value ="<?php echo $this->modeloCatalogos->getIdCatalogos() ?>">
            <button type ="submit" class="guardar"> Guardar</button>
        </div>
    </fieldset >
</form >
<script type ="text/javascript">
    $(document).ready(function () {
<?php echo $this->codigoJS; ?>
        construirValidador();
        distribuirLineas();

        //carga el arbol en combo de informes padres
        $('#fk_id_catalogos').combotree({
            data:<?php echo $this->cmbCatalogos; ?>,
            editable: true
        });
        $('#fk_id_catalogos').combotree('setValue', <?php echo $this->modeloCatalogos->getFkIdCatalogos(); ?>);
    });
    $("#formulario").submit(function (event) {
        event.preventDefault();
        var val = $('#fk_id_catalogos').combotree('getValue');
        idExpandir = val;
        var error = false;
        if (!error) {
            var respuesta = JSON.parse(ejecutarJson($(this)).responseText);
            //Traemos la lista solo si guardo correctamenre
            if(respuesta.estado == 'exito')
            {
            fn_filtrar();
            }

        } else {
            $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
        }
    });
</script>
