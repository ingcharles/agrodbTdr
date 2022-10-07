<script src="<?php echo URL_RESOURCE ?>js/tinymce/tinymce.min.js"></script>
<header>
    <h1><?php echo $this->accion; ?></h1>
</header>		
<form id = 'formulario' data-rutaAplicacion = '<?php echo URL_MVC_FOLDER; ?>Laboratorios'			 
      data-opcion = 'ArchivoInformeAnalisis/guardar' data-destino ="detalleItem"			 
      data-accionEnExito ="NADA" method="post"   >			
    <fieldset>			
        <legend>Configuración informe</legend>			

        <div data-linea="1">
            <label for="id_informe">Formato informe</label> <select
                id="id_informe" name="id_informe" required>
                    <?php echo $this->comboFormatoInforme($this->modeloArchivoInformeAnalisis->getIdInforme()); ?>
            </select>
        </div>
        <div data-linea="2"> 
            <input type="hidden" name="id_archivo_informe_analisis" id="id_archivo_informe_analisis" value="<?php echo $this->modeloArchivoInformeAnalisis->getIdArchivoInformeAnalisis(); ?>">
            <input type="hidden" name="id_parametros_laboratorio" id="id_parametros_laboratorio" value="<?php echo $this->idParametro; ?>">
            <input type="hidden" name="id_parametros_laboratorio2" id="id_parametros_laboratorio2" value="<?php echo $this->idParametro2; ?>">
            <input type="hidden" name="id_parametros_servicio" id="id_parametros_servicio" value="">
            <button type="button" id="btnConfigurar" class='fas fa-cog'>Opciones</button>
        </div>
        <div id="pnlConfigurar" style="visibility: hidden;width: 100%;top:25px;margin-top: 25px;">
            <fieldset class='fieldsetInterna'>
                <legend class='legendInterna'>Configuración del formato seleccionado</legend>

                <div data-linea="3">

                    <label for="orientacion">Orientación</label> <select
                        id="orientacion" name="orientacion" >

                    </select>

                </div>
                <div data-linea="3">
                    <label for="agrupar_por"> Agrupar por </label> 
                    <select
                        id="agrupar_por" name="agrupar_por" required>
                        <option value="ANALISIS" selected>ANALISIS</option>
                        <option value="MUESTRA">MUESTRA</option>
                    </select>


                </div>
                <div >
                    <label for="textoCertificacion">Texto de acreditación (CABECERA DE INFORME)</label> 
                    <textarea id="textoCertificacion" name="textoCertificacion" cols="70"
                              placeholder="Escribir mensaje" ><?php echo $this->msgAcreditacion; ?></textarea>
                </div>
                
                <div >
                    <label for="textoCertificacion2">Texto de acreditación (PIE DE INFORME)</label> 
                    <textarea id="textoCertificacion2" name="textoCertificacion2" cols="70"
                              placeholder="Escribir mensaje" ><?php echo $this->msgAcreditacion2; ?></textarea>

                    <button type="button" id="btnGuardar" class="fas fa-save">Guardar</button>
                </div>
            </fieldset >
            <fieldset class='fieldsetInterna'>
                <legend class='legendInterna'>Tablas de referencia</legend>

                <div>
                    <label for="id_servicio"> Servicio </label> 
                    <select id="id_servicio" name="id_servicio" >
                    </select>
                </div>

                <div >
                    <label for="tablaReferencia">Contenido de referencia</label> 
                    <textarea id="tablaReferencia" name="tablaReferencia" 
                              placeholder="Copie la tabla desde word " > <?php echo $this->msgTablaReferencia; ?></textarea>
                    <button type="button" id="btnGuardarTabla" class="fas fa-save">Guardar Tabla de referencia</button>
                    <label id="msgtablaReferencia"></label> 
                </div>

            </fieldset >
            <?php
            if (ENVIRONMENT == 'development' || ENVIRONMENT == 'dev')
            {
                echo ' 
            <fieldset class="fieldsetInterna">
                <legend class="legendInterna">Campos de informe seleccionado (visible en Desarrollo)</legend>
                <div data-linea="4">
                    <label for="idSeccion">Sección del informe</label> <select
                        id="idSeccion" name="idSeccion" >
                    </select>
                </div>
                 <div>
                    <label for="camposResultados">Campos de resultado </label> 
                    <select id="camposResultados" name="camposResultados" >
                    </select>
                </div>
                <table id="tablaSeccion">

                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Modificar</th>
                            <th>Estado</th>
                            <th>Orden</th>
                        </tr>
                    </thead>
                    <tbody id="tablaCampos"></tbody>
                </table>

            </fieldset >
        </div>
    </fieldset >';
            }
            ?>



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
                        fn_orientacion();
                        fn_cargarServicios();
                    });
                    //Busca las secciones del informe
                    $("#idSeccion").change(function () {
                        fn_cargarCampos();
                    });
                    //Busca los campos de los resultados de los informes
                    $("#camposResultados").change(function () {
                        fn_cargarCamposResultado();
                    });

                    $("#btnGuardar").click(function () {
                        fn_guardar();
                    });
                });
                function fn_cargarServicios() {
                    $.post("<?php echo URL ?>Laboratorios/ParametrosServicios/comboServiciosSimple/" + <?php echo $this->laboratorioUsuario() ?>, function (data) {
                        $("#id_servicio").html(data);
                    });
                }

                //Para cargar las secciones del informe
                function fn_cargarSecciones() {
                    var idInforme = $("#id_informe").val();
                    if (idInforme !== "") {
                        $.post("<?php echo URL ?>Laboratorios/ArchivoInformeAnalisis/comboSeccionesInforme/" + idInforme, function (data) {
                            $("#idSeccion").html(data);
                        });
                        //campos de resultado
                        $.post("<?php echo URL ?>Laboratorios/ArchivoInformeAnalisis/comboResultadoInforme/" + idInforme, function (data) {
                            $("#camposResultados").html(data);
                        });
                    }
                }
                //Para cargar los campos de la sección seleccionada
                function fn_cargarCampos() {

                    var idSeccion = $("#idSeccion").val();
                    if (idSeccion !== "") {
                        $.post("<?php echo URL ?>Laboratorios/ArchivoInformeAnalisis/camposSeccionInforme/" + idSeccion, function (data) {
                            $("#tablaCampos").html(data);
                        });
                    }
                }

                //Para cargar los campos de la sección seleccionada
                function fn_cargarCamposResultado() {

                    var idSeccion = $("#camposResultados").val();
                    if (idSeccion !== "") {
                        $.post("<?php echo URL ?>Laboratorios/ArchivoInformeAnalisis/camposSeccionInforme/" + idSeccion, function (data) {
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
                //Para cargar combo de orientación
                function fn_orientacion() {
                    var idInforme = $("#id_informe").val();
                    if (idInforme !== "") {
                        $.post("<?php echo URL ?>Laboratorios/ArchivoInformeAnalisis/comboOrientacion/" + idInforme, function (data) {
                            $("#orientacion").html(data);
                        });
                    }
                }
                /**
                 * Guardar la configuración
                 
                 * @returns {undefined} */
                function fn_guardar() {
                    var elementoDestino = "#detalleItem";
                    var url = "<?php echo URL ?>Laboratorios/ArchivoInformeAnalisis/guardarConfiguracion";
                    var data = {
                        id_parametros_laboratorio: $("#id_parametros_laboratorio").val(),
                        id_parametros_laboratorio2: $("#id_parametros_laboratorio2").val(),
                        descripcion: $("#textoCertificacion").val(),
                        descripcion2: $("#textoCertificacion2").val(),
                        id_informe: $("#id_informe").val(),
                        orientacion: $("#orientacion").val(),
                        agrupar_por:$("#agrupar_por").val(),
                        id_archivo_informe_analisis: $("#id_archivo_informe_analisis").val()
                    };
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: data,
                        dataType: "text",
                        contentType: "application/x-www-form-urlencoded; charset=latin1",
                        beforeSend: function () {
                           
                        },
                        success: function (data) {
                            var obj = jQuery.parseJSON(data);
                            mostrarMensaje(obj.mensaje, obj.estado);
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            $(elementoDestino).html(
                                    "<div id='error'>¡Ups!... algo no anda bien.<br />"
                                    + "Se produjo un " + textStatus + " "
                                    + jqXHR.status
                                    + ".<br />Disculpe los inconvenientes causados.</div>");
                        },
                        complete: function () {
                        }
                    });
                }

                /**
                 * Actualiza los campos del informe
                 * @param {type} idInforme
                 * @returns {undefined}
                 */
                function  fn_cambiar(campo, idInforme) {
                    $.post("<?php echo URL ?>Laboratorios/ArchivoInformeAnalisis/actualizarInforme",
                            {
                                id_informe: idInforme,
                                nombre_informe: $(campo).val(),
                            },
                            function (data) {
                            });
                }

                /**
                 * Cambiar de estado en registro
                 * @param {type} campo
                 * @param {type} idInforme
                 * @returns {undefined}
                 */
                function  cambiarEstado(campo, idInforme) {

                    var valor = $(campo).prop("checked") ? 'true' : 'false';
                    var estado = 'ACTIVO';
                    if (valor == 'false')
                    {
                        estado = 'INACTIVO';
                    }
                    $.post("<?php echo URL ?>Laboratorios/Informes/cambiarEstado",
                            {
                                id_informe: idInforme,
                                estado_registro: estado
                            },
                            function (data) {
                            });
                }
                /**
                 * Combo de servicios del laboratorio seleccionado
                 * @type type
                 */
                $("#id_servicio").change(function () {
                    var url = "<?php echo URL ?>Laboratorios/ArchivoInformeAnalisis/buscarTableRef/" + $("#id_servicio").val();
                    $.ajax({
                        type: "POST",
                        url: url,
                        dataType: "text",
                        contentType: "application/x-www-form-urlencoded; charset=latin1",
                        beforeSend: function () {

                        },
                        success: function (datos) {


                            var json = $.parseJSON(datos);
                            tinymce.get("tablaReferencia").setContent(json.contenido);
                            $('#id_parametros_servicio').val(json.id);
                            $('#msgtablaReferencia').html("");
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            $(elementoDestino).html('Error al cargar los campos de resultado')
                        },
                        complete: function () {
                        }
                    });
                });

                /**
                 * Cambia el orden de los campos
                 * @param {type} campo
                 * @param {type} idInforme
                 * @returns {undefined}
                 */
                function  cambiarOrden(campo, idInforme) {

                    $.post("<?php echo URL ?>Laboratorios/Informes/cambiarOrden",
                            {
                                id_informe: idInforme,
                                orden: $(campo).val()
                            },
                            function (data) {

                            });

                }

                $("#btnGuardarTabla").click(function () {

                    $.post("<?php echo URL ?>Laboratorios/ArchivoInformeAnalisis/guardarTableRef",
                            {
                                id_parametros_servicio: $("#id_parametros_servicio").val(),
                                descripcion: tinymce.get("tablaReferencia").getContent(''),
                                id_servicio: $("#id_servicio").val()
                            },
                            function (data) {
                                $('#msgtablaReferencia').html("Tabla de referencia fue guardada con exito");
                            });

                });



            </script>
