<header>
    <h1><?php echo $this->accion; ?></h1>
</header>
<form id = 'formulario' data-rutaAplicacion = '<?php echo URL_MVC_FOLDER; ?>Laboratorios'
      data-opcion = 'UsuarioLaboratorio/guardar' 
      data-destino ="detalleItem"			 
      data-accionEnExito ="NADA" method="post">	
    <fieldset>			
        <legend>Usuario Laboratorio</legend>

        <div data-linea ="1">
            <label for="identificador"> Cédula</label> 
            <input type ="text" id="identificador"   
                   name ="identificador" value="<?php echo $this->modeloUsuarioLaboratorio->getIdentificador(); ?>"
                   placeholder ="Cédula de identidad o pasaporte."
                   required  maxlength="13" />
        </div>

        <div data-linea ="1">  
            <label for="nombreUsuario"> Usuario </label> 
            <input type="text" id="nombreUsuario" name="nombre" value="" style="background: transparent; border: 0"/>
        </div>

        <div data-linea ="2">   
            <label for="perfil"> Perfil </label> 
            <select id="perfil" name="perfil" required>
            </select>
        </div>

        <div data-linea="2">
            <label for="direccion"> Dirección </label> 
            <select id="direccion" name="direccion">
                <option value="">Seleccionar....</option>
                <?php
                echo $this->comboDirecciones($this->modeloUsuarioLaboratorio->getDireccion());
                ?>
            </select>
        </div>

        <div data-linea="3">
            <label>Laboratorios</label> 
            <select id="id_laboratorio" name="id_laboratorio" disabled="disabled">
            </select>
        </div>

        <div data-linea="3">
            <label>Provincia del laboratorio </label> 
            <select id="id_laboratorios_provincia" name="id_laboratorios_provincia" disabled="disabled">
            </select>
        </div>

        <div data-linea="4">
            <label>Provincia(solo recaudador) </label> 
            <select id="provincias" name="provincias" disabled="disabled">
                <option value="">Seleccionar....</option>
                <?php echo $this->comboProvinciasEc(); ?>
            </select>
        </div>

        <div data-linea="4">
            <label>Estado</label> 
            <select name="estado">
                <?php echo $this->combo2Estados($this->modeloUsuarioLaboratorio->getEstado()); ?>
            </select>
        </div>

        <div id="div_permisos" style="display: none">
            <table id="tblPermisos" > 
                <caption>
                    Selecionar los permisos otorgados
                </caption>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Recursos</th>
                        <th>Permiso</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td><input type="hidden" value="" id="verificarMuestra"/>Verificar idoneidad de la muestra</td>
                        <td><input type="checkbox" id="chkverificarMuestra" /></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td><input type="hidden" value="" id="imprimirEtiquetas"/>Imprimir etiquetas</td>
                        <td><input type="checkbox" id="chkimprimirEtiquetas" /></td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td><input type="hidden" value="" id="registrarResultados"/>Registrar resultados</td>
                        <td><input type="checkbox" id="chkregistrarResultados" /></td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td><input type="hidden" value="" id="aprobarInforme"/>Aprobar informe</td>
                        <td><input type="checkbox" id="chkaprobarInforme" /></td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td><input type="hidden" value="" id="firmarInforme"/>Firmar informe</td>
                        <td><input type="checkbox" id="chkfirmarInforme" /></td>
                    </tr>
                    <tr>
                        <td>6</td>
                        <td><input type="hidden" value="" id="enviarInforme"/>Enviar informe</td>
                        <td><input type="checkbox" id="chkenviarInforme" /></td>
                    </tr>
                    <tr>
                        <td>7</td>
                        <td><input type="hidden" value="" id="cronogramaPostregistro"/>Cronograma para post-registro</td>
                        <td><input type="checkbox" id="chkcronogramaPostregistro" /></td>
                    </tr>
                </tbody>
            </table>

            <label for="permisos"> C&oacute;digo Json de permisos </label>
            <div data-linea ="9" style="visibility: <?php echo $this->devVisible(); ?>">
                <textarea id="permisos" name="permisos" 
                          placeholder="Código Json"><?php echo $this->modeloUsuarioLaboratorio->getPermisos(); ?></textarea>
            </div>
        </div>

        <div data-linea ="8">	
            <input type ="hidden" name="id_usuario_laboratorio" id="id_usuario_laboratorio" value ="<?php echo $this->modeloUsuarioLaboratorio->getIdUsuarioLaboratorio() ?>">
            <button type ="submit" class="guardar"> Guardar</button>
        </div>
    </fieldset>
</form >
<script type ="text/javascript">
    $(document).ready(function () {
<?php echo $this->codigoJS ?>
        fn_getPermisosGuardados();
        construirValidador();
        distribuirLineas();
        fn_buscarUsuario();
        //si la dirección existe estamos editando 
        if ($("#direccion").val()) {
            $.post("<?php echo URL ?>Laboratorios/UsuarioLaboratorio/comboLaboratorios/" + $("#direccion").val(), function (data) {
                $("#id_laboratorio").html(data);
                $("#id_laboratorio").removeAttr("disabled");
                $("#id_laboratorio").val(<?php echo $this->modeloUsuarioLaboratorio->getIdLaboratorio() ?>);
                fn_buscarProvincias();
            });
        }
    });

    $("#formulario").submit(function (event) {
        fn_formarCadenaJSON();
        event.preventDefault();
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

    //Cuando seleccionamos una dirección, llenamos el combo de laboratorios
    $("#direccion").change(function () {
        var idDireccion = $(this).val();
        if (idDireccion !== null) {
            $.post("<?php echo URL ?>Laboratorios/UsuarioLaboratorio/comboLaboratorios/" + idDireccion, function (data) {
                $("#id_laboratorio").html(data);
                $("#id_laboratorio").removeAttr("disabled");
            });
        }
    });

    //Cuando seleccionamos un laboratorio, llenamos el combo de servicios
    $("#id_laboratorio").change(function () {
        fn_buscarProvincias();
    });

    function fn_buscarProvincias() {
        var idLaboratorio = $("#id_laboratorio").val();
        //las provincas donde esta el laboratorio
        $.post("<?php echo URL ?>Laboratorios/UsuarioLaboratorio/comboLaboratoriosProvincia/" + idLaboratorio, function (data) {
            $("#id_laboratorios_provincia").html(data);
            $("#id_laboratorios_provincia").removeAttr("disabled");
            $('#id_laboratorios_provincia option[value="<?php echo $this->modeloUsuarioLaboratorio->getIdLaboratoriosProvincia(); ?>"]').prop('selected', true);
        });
    }

    //buscar datos del usuario a otorgar permisos
    function fn_buscarUsuario() {
        if ($("#identificador").val() !== '') {
            $.post("<?php echo URL ?>Laboratorios/UsuarioLaboratorio/buscarUsuarioPerfiles/" + $("#identificador").val(), function (data) {
                $("#nombreUsuario").val(data.nombre);
                $("#perfil").html(data.perfil);
                $('#perfil option[value="<?php echo $this->modeloUsuarioLaboratorio->getPerfil(); ?>"]').prop('selected', true);
                fn_verCampos();
            }, 'json');
        }
    }

    //llam a la funcion para buscar datos del usuario a otorgar permisos
    $("#identificador").change(function () {
        fn_buscarUsuario();
    });

    //deshabilitar opciones segun perfil seleccionado
    $("#perfil").change(function () {
        fn_verCampos();
    });

    //Ver campos segun perfil
    function fn_verCampos() {
        if ($("#perfil").val() === "") {
            $('#direccion option[value=""]').prop('selected', true);
            $("#id_laboratorio").html("");
            $("#direccion").attr("disabled", "disabled");
            $("#provincias").attr("disabled", "disabled");
            $("#provincias").attr("required", false);
            $("#div_permisos").css('display', 'none');
        } else if ($("#perfil").val() === 'Recaudador' | $("#perfil").val() === 'Guardalmacen') {
            $('#direccion option[value=""]').prop('selected', true);
            $("#id_laboratorio").html("");
            $("#direccion").attr("disabled", "disabled");
            if ('<?php echo $this->modeloUsuarioLaboratorio->getIdUsuarioLaboratorio(); ?>' === "") {
                $("#provincias").removeAttr("disabled");
                $("#provincias").attr("required", true);
            }
            $("#div_permisos").css('display', 'none');
        } else {
            $("#direccion").removeAttr("disabled");
            $("#direccion").attr("required", true);
            $("#id_laboratorio").attr("required", true);
            $("#id_laboratorios_provincia").attr("required", true);
            $('#provincias option[value=""]').prop('selected', true);
            $("#provincias").attr("disabled", "disabled");
            $("#div_permisos").css('display', 'block');
        }
    }

    /**
     * Crea el código json para guardar los permisos 
     * @returns {json}
     */
    function fn_formarCadenaJSON() {
        var total = $('#tblPermisos >tbody >tr').length;
        jsonObj = [];
        for (var i = 0; i < total; i++) {
            var nombre = $("#tblPermisos tbody").find("tr").eq(i).find("td").eq(1).find("input").attr("id");
            var permitido = $("#tblPermisos tbody").find("tr").eq(i).find("td").eq(2).find("input").attr("id");
            var permiso = $("#" + permitido).prop("checked") ? 'true' : 'false';
            item = {};
            item ["id"] = nombre;
            item ["permiso"] = permiso;
            jsonObj.push(item);
        }
        $("#permisos").val(JSON.stringify(jsonObj));
    }
    /**
     * Recupera el código jason para configurar los permisos de usuario
     * @returns {json}
     */
    function fn_getPermisosGuardados() {
        var permisos = '<?php echo $this->modeloUsuarioLaboratorio->getPermisos(); ?>';
        if (permisos !== '') {
            var jsonObj = jQuery.parseJSON(permisos);
            $.each(jsonObj, function (key, value) {
                if (value.permiso === 'true') {
                    $("#chk" + value.id).prop("checked", true);
                } else {
                    $("#chk" + value.id).prop("checked", false);
                }
            });
        }
    }
</script>
