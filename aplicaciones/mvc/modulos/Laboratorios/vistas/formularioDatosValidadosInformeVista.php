<script src="<?php echo URL_RESOURCE ?>js/tinymce/tinymce.min.js"></script>
<header>
    <h1><?php echo $this->accion; ?></h1>
</header>		
<form id = 'formulario' data-rutaAplicacion = '<?php echo URL_MVC_FOLDER; ?>Laboratorios'			 
      data-opcion = 'ArchivoInformeAnalisis/guardar' data-destino ="detalleItem"			 
      data-accionEnExito ="NADA" method="post"   >			
    <fieldset>			
        <legend>Modificar informe</legend>			
        <fieldset class='fieldsetInterna'>
            <legend class='legendInterna'>Modificar el estado del informe ( Estado actual: <?php echo $this->modeloArchivoInformeAnalisis->getEstadoInforme(); ?>)</legend>
            <div data-linea="1">
                <label for="estado_registro"> Estado </label> 
                <select
                    id="estado_informe" name="estado_informe" required>
                    <option value="">Seleccionar....</option>
                    <option value="ACTIVO">ACTIVO</option>
                    <option value="CERRADO">CERRADO</option>
                </select>


            </div>
            <div data-linea="1">
                <button type="button" id="btnEstado" class='fas fa-cog'>Cambiar Estado</button>   
            </div>
        </fieldset >
        <div data-linea="2"> 
            <input type="hidden" name="id_archivo_informe_analisis" id="id_archivo_informe_analisis" value="<?php echo $this->modeloArchivoInformeAnalisis->getIdArchivoInformeAnalisis(); ?>">
            <input type="hidden" name="id_informe_analisis" id="id_informe_analisis" value="<?php echo $this->modeloArchivoInformeAnalisis->getIdInformeAnalisis(); ?>">
            <input type="hidden" name="id_parametros_laboratorio" id="id_parametros_laboratorio" value="<?php echo $this->idParametro; ?>">
            <input type="hidden" name="id_parametros_servicio" id="id_parametros_servicio" value="">
            <button type="button" id="btnConfigurar" class='fas fa-cog'> Modificar campos del informe</button>
        </div>
        <div id="pnlConfigurar" style="visibility: hidden;width: 100%;top:25px;margin-top: 25px;">
            <fieldset class="fieldsetInterna">
                <legend class="legendInterna">Campos de Orden de Trabajo</legend>
                <div data-linea="4">
                    <label for="idSeccion">Sección del informe</label> <select
                        id="idSeccion" name="idSeccion" >
                    </select>
                </div>
                <table id="tablaSeccion">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Modificar contenido</th>

                        </tr>
                    </thead>
                    <tbody id="tablaCampos"></tbody>
                </table>
            </fieldset >
        </div>
    </fieldset >
</form>
<script type ="text/javascript">
    $(document).ready(function () {
        tinymce.init({
            selector: '#tablaReferencia',
            language: 'es',
            height: 80,
            menubar: false,
            plugins: [
                'advlist lists link image link table'
            ],
            toolbar: 'insert | formatselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link | table'
        });
<?php echo $this->codigoJS ?>
        distribuirLineas();
        $("#btnConfigurar").click(function () {
            $("#pnlConfigurar").css("visibility", "visible");
            fn_cargarSecciones();
        });
        //Busca las secciones del informe
        $("#idSeccion").change(function () {
            fn_cargarCampos();
        });

    });

    //Para cargar las secciones del informe
    function fn_cargarSecciones() {
        var idInforme = $("#id_informe").val();
        if (idInforme !== "") {
            $.post("<?php echo URL ?>Laboratorios/ArchivoInformeAnalisis/comboCodigoInforme/" + <?php echo $this->idInforme; ?>, function (data) {
                $("#idSeccion").html(data);
            });

        }
    }
    //Para cargar los campos de la sección seleccionada
    function fn_cargarCampos() {

        var codigoSeccion = $("#idSeccion").val();
        var idInformeAnalisis = $("#id_informe_analisis").val();

        if (idSeccion !== "") {
            $.post("<?php echo URL ?>Laboratorios/Datosvalidadosinforme/camposSeccionInforme/" + codigoSeccion + "/" + idInformeAnalisis, function (data) {
                $("#tablaCampos").html(data);
            });
        }
    }

    function fn_cargarCamposActualizar(idSeccion) {
        if (idSeccion !== "") {
            $.post("<?php echo URL ?>Laboratorios/ArchivoInformeAnalisis/camposSeccionInforme/" + idSeccion, function (data) {
                $("#tablaCampos").html(data);
            });
        }
    }

    /**
     * Actualiza los campos del informe
     * @param {type} idInforme
     * @returns {undefined}
     */
    function  fn_cambiar(campo, idcampo) {
        $.post("<?php echo URL ?>Laboratorios/Datosvalidadosinforme/actualizar",
                {
                    id_datos_validados_informe: idcampo,
                    valor: $(campo).val(),
                },
                function (data) {
                    var obj = jQuery.parseJSON(data);
                    mostrarMensaje(obj.mensaje, obj.estado);
                });
    }


    $("#btnEstado").click(function () {

        if ($("#estado_informe").val() == '') {
            mostrarMensaje("Debe seleccionar el estado del informe a modificar","fallo");
            return false;
        }
        $.post("<?php echo URL ?>Laboratorios/ArchivoInformeAnalisis/guardar",
                {
                    id_archivo_informe_analisis: $("#id_archivo_informe_analisis").val(),
                    estado_informe: $("#estado_informe").val()
                },
                function (data) {
                    var obj = jQuery.parseJSON(data);
                    mostrarMensaje(obj.mensaje, obj.estado);
                    fn_filtrar();
                });
    });
</script>
