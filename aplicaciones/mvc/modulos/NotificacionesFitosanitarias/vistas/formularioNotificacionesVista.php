<header>
    <h1><?php echo $this->accion; ?></h1>
</header>

<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>NotificacionesFitosanitarias'
      data-opcion='Notificaciones/guardar' data-destino="detalleItem"
      data-accionEnExito="ACTUALIZAR" method="post">
    <fieldset>
        <legend>Detalle Notificación</legend>

        <div data-linea="1">
            <label for="nombre_lista">C&oacute;digo de documento: </label>
            <input type="hidden" id="id_lista_notificacion" name="id_lista_notificacion" value ="<?php echo $_POST['id']; ?>"/>
            <input type="text" id="codigo_documento" name="codigo_documento" value="<?php echo $this->modeloNotificaciones->getCodigoDocumento(); ?>"
                   placeholder="C&oacute;digo &uacute;nico de documento" required maxlength="256" />
        </div>

        <div data-linea="2">
            <label for="id_pais_notifica">País que notifica: </label>
            <select id="id_pais_notifica" name="id_pais_notifica" required>
                <option value="">Seleccionar....</option>
                <?php
                echo $this->comboVariosPaises();
                ?>
            </select>
            <input type="hidden" id="pais_notifica" name="pais_notifica" />       
        </div>

        <div data-linea="3">
            <label for="tipoDocumento"> Tipo de documento: </label> 
            <select
                id="tipoDocumento" name="tipoDocumento" required>
                <option value="">Seleccionar....</option>
                <option value="Adiciones de urgencia">Adiciones de urgencia</option>
                <option value="Adiciones ordinarias">Adiciones ordinarias</option>
                <option value="Correcciones de urgencia">Correcciones de urgencia</option>
                <option value="Correcciones ordinarias">Correcciones ordinarias</option>
                <option value="Notificación de medidas de urgencia">Notificación de medidas de urgencia</option>
                <option value="Notificación ordinaria">Notificación ordinaria</option>
                <option value="Reconocimiento de equivalencia">Reconocimiento de equivalencia</option>

            </select>
        </div> 

        <div data-linea ="4">
            <label for="fechaNotificacion"> Fecha notificaci&oacute;n: </label> 
            <input type ="date" id="fechaNotificacion"
                   name ="fechaNotificacion" 
                   placeholder ="Fecha de depósito" max="<?php echo date("Y-m-d"); ?>"
                   maxlength="10" />
        </div>
        <div data-linea ="5">
            <label for="areaTematica"> Área temática: </label> 
           <?php echo $this->listarAreaTematica($this->modeloNotificaciones->getIdNotificacion());?>
        </div>
    </fieldset>   
    <fieldset>
        <legend>Productos</legend>

        <div data-linea ="5">   
            <label for="producto"> Producto: </label> 
            <input type ="text" id="producto"
                   name ="producto" 
                   placeholder ="Productos"
                   maxlength="256" />
        </div >
    </fieldset>   
    <fieldset>
        <legend>Palabras clave</legend>   
        <div data-linea ="6">   
            <label for="palabraClave"> Palabras claves de la notificaci&oacute;n: </label> 
            <input type ="text" id="palabraClave"
                   name ="palabraClave" 
                   placeholder ="Palabras Claves"
                   maxlength="256" />
        </div >
    </fieldset>
    <fieldset>
        <legend>Descripci&oacute;n</legend>   
        <div data-linea ="7">   
            <label for="descripcion"> Descripci&oacute;n: </label> 
            <input type ="text" id="descripcion"
                   name ="descripcion" 
                   placeholder ="Descripci&oacute;n"
                   maxlength="256" />
        </div >
    </fieldset>
    <fieldset>
        <legend>Paises afectados</legend>
        <div data-linea="8">
            <label for="id_localizacion">Pa&iacute;s: </label>
            <select id="id_localizacion" name="id_localizacion">
                <option value="">Seleccionar....</option>
                <?php
                echo $this->comboVariosPaises();
                ?>
            </select>

        </div>
        <span>
            <button type="button" class="mas" onclick="agregar()"> Agregar</button>
        </span>

        <table width="100%" id="grilla" class="lista" ALIGN="CENTER">
            <thead>
                <tr>
                    <th colspan="7">Pa&iacute;s afectado</th>
                </tr>
                <tr>
                    <th>#</th>
                    <th>Pa&iacute;s</th>
                    <th>Eliminar</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </fieldset>

    <fieldset>
        <legend>Enlace</legend>   
        <div data-linea ="1">   
            <label for="enlace"> Enlace: </label> 
            <input type ="text" id="enlace"
                   name ="enlace" 
                   placeholder ="Enlace"
                   maxlength="256" />
        </div >
    </fieldset>

    <div data-linea="5">
        <button type="submit" class="guardar"> Guardar</button>
    </div>

</form>

<form id='formularioEditar' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>NotificacionesFitosanitarias' data-opcion='Notificaciones/guardar' data-destino="detalleItem" method="post">

    <input type="hidden" id="id_notificacion" name="id_notificacion" value="<?php echo $this->modeloNotificaciones->getIdNotificacion(); ?>" />
    <input type="hidden" id="id_lista_notificacion" name="id_lista_notificacion" value="<?php echo $this->modeloNotificaciones->getIdListaNotificacion(); ?>" />

    <fieldset>
        <legend>Lista Notificaciones</legend>
        <div data-linea="60">
            <label for="anio">Año: </label> 
            <select id="anio" name="anio">
                <option>Seleccione...</option>
                    <?php $this->comboAniosNotificaciones($this->anio); ?>
            </select>
        </div>
        <div data-linea="61">
            <label for="notificacion">Nombre lista: </label> 
            <select id="notificacion" name="notificacion">
                <?php $this->comboListaNotificacionesXAnio($this->anio, $this->idLista); ?>
            </select>
        </div>
    </fieldset>

    <fieldset>
        <legend>Detalle Notificaci&oacute;n</legend>

        <div data-linea="62">
            <label for="codigo_documento">C&oacute;digo documento: </label> <input
                type="text" id="codigo_documento" name="codigo_documento"
                value="<?php echo $this->modeloNotificaciones->getCodigoDocumento(); ?>"
                placeholder="Código de la notificación" required maxlength="32" />
        </div>
        <div data-linea="63">
            <label for="id_pais_notifica_editar">Pa&iacute;s que notifica: </label> <select
                id="id_pais_notifica_editar" name="id_pais_notifica_editar" required>
                <option value="">Seleccionar....</option>
                <?php
                echo $this->comboVariosPaises($this->modeloNotificaciones->getIdPaisNotifica());
                ?>
            </select>
            <input type="hidden" id="pais_notifica_editar" name="pais_notifica_editar" value="<?php echo $this->modeloNotificaciones->getNombrePaisNotifica();?>"/>
        </div>
        <div data-linea="64">
            <label for="tipo_documento">Tipo de documento: </label> <select
                id="tipo_documento" name="tipo_documento" required>
                <option value="">Seleccionar....</option>
                <?php
                echo $this->comboTipoDocumento($this->modeloNotificaciones->getTipoDocumento());
                ?>
            </select>
        </div>
        <div data-linea="65">
            <label for="fecha_notificacion">Fecha notificaci&oacute;n: </label> <input
                type="text" id="fecha_notificacion" name="fecha_notificacion"
                value="<?php echo date('Y-m-d', strtotime($this->modeloNotificaciones->getFechaNotificacion())) ?>" />
        </div>
        <div data-linea ="5">
            <label for="areaTematica"> Área temática: </label> 
           <?php echo $this->listarAreaTematica($this->modeloNotificaciones->getIdNotificacion());?>
        </div>
        <div data-linea="66">
            <label for="producto">Producto: </label> <input type="text"
                                                            id="producto" name="producto"
                                                            value="<?php echo $this->modeloNotificaciones->getProducto(); ?>"
                                                            placeholder="Productos relacionados a la notificación" required
                                                            maxlength="256" />
        </div>
        <div data-linea="67">
            <label for="palabra_clave">Palabras claves de la notificaci&oacute;n:
            </label> <input type="text" id="palabra_clave" name="palabra_clave"
                            value="<?php echo $this->modeloNotificaciones->getPalabraClave(); ?>"
                            placeholder="Palabras claves de la notificación" required
                            maxlength="256" />
        </div>

        <div data-linea="68">
            <label for="descripcion">Descripci&oacute;n: </label> <input
                type="text" id="descripcion" name="descripcion"
                value="<?php echo $this->modeloNotificaciones->getDescripcion(); ?>"
                placeholder="Descripción de la notificación" required
                maxlength="256" />
        </div>

        <div data-linea="69">
            <label for="enlace">Enlace: </label> 
            <input type="text" id="enlace" name="enlace" value="<?php echo $this->modeloNotificaciones->getEnlace(); ?>"
            placeholder="Enlace donde se encuentra el documento de notificación detallado"
            required maxlength="64" />
        </div>

        <div data-linea="70">
            <button type="submit" class="guardar">Guardar</button>
        </div>
    </fieldset>
    <fieldset>
        <legend>Países afectados</legend>
        <div data-linea="71">
            <label for="id_pais_notifica_1">Pa&iacute;ses afectados: </label> 
            <select id="id_pais_notifica_1" name="id_pais_notifica_1" required >
                <option value="">Seleccionar....</option>
                <?php
                echo $this->comboVariosPaises($this->modeloNotificaciones->getIdPaisNotifica());
                ?>
            </select>
        </div>
        <div data-linea="72">
            <button class="mas" id="agregarFilaPais" name="agregarFila">Agregar</button>
        </div>

        <table width="100%" id="grilla" class="lista" align="center">
            <thead>
                <tr>
                    <th colspan="7">Pa&iacute;s afectado</th>
                </tr>
                <tr>
                    <th>#</th>
                    <th>Pa&iacute;s</th>
                    <th>Eliminar</th>
                </tr>
            </thead>
            <tbody id="bodyTbl">
            <th><?php echo $this->datosDetalleFormulario; ?></th>
            </tbody>
        </table>
    </fieldset>
</form>
<script type="text/javascript">
    var bandera = <?php echo json_encode($this->formulario); ?>;
    $(document).ready(function() {
        $("#formulario").hide();
        $("#formularioEditar").hide();
    if(bandera == 'nuevo'){
        $("#formulario").show();
        $("#formularioEditar").hide();
    }else{
        $("#formulario").hide();
        $("#formularioEditar").show();
       
        }
        construirValidador();
        distribuirLineas();
    });
    
    //Cuando seleccionamos un año cambia nombre de lista notificacion
    $("#anio").change(function () {
        if ($(this).val !== "") { 
                  
                $.post("<?php echo URL ?>NotificacionesFitosanitarias/Notificaciones/comboListaNotificacionXAnio/" + $("#anio").val(), function (data) {
                    $("#notificacion").html(data);
                    $('#notificacion option[value="' + notificacion + '"]').prop('selected', true);  
                    });
                $("#notificacion").html(data);
        	}
    });
 
    $("#formularioEditar").submit(function (event) {
        event.preventDefault();
        var error = false;

    if (!error) {
    var respuesta = JSON.parse(ejecutarJson($(this)).responseText);

    if (respuesta.estado == 'exito'){
        //fn_filtrar();
        //abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),"#listadoItems",true);
        }
    } else {
        $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
        }
    });

    $("#agregarFilaPais").click(function(event) { 
    event.preventDefault();

    var codigo = $("#id_pais_notifica_1 option:selected").val();

    if($("#bodyTbl #"+codigo.replace(/ /g,'')).length==0){
        $("#estado").html("");
        $.post("<?php echo URL ?>NotificacionesFitosanitarias/ListaNotificacion/agregarDetalleFormularioNotificaciones", 
        {
        idLocalizacion: $("#id_pais_notifica_1").val(),
        idNotificacion: $("#id_notificacion").val(),
        idNombre: $("#id_pais_notifica_1 option:selected").text()
    },
    function (data) {
        $("#bodyTbl").html(data);
    });			
    }else{
        $("#estado").html("Este país ya está agregado.").addClass('alerta');
        }
    });

    //enumera las filas cuando se agrega y se elimina una fila
    function fn_numerar() {
    var total = $('#grilla >tbody >tr').length;
    for (var i = 1; i <= total; i++) {
        document.getElementById("grilla").rows[i + 1].cells[0].innerText = i;
        }       
    }

    //Funcion que elimina una fila de la lista 
    function fn_eliminarEditar(id) { 
        respuesta = confirm("Desea eliminar");
    if (respuesta) {
        $.post("<?php echo URL ?>NotificacionesFitosanitarias/ListaNotificacion/eliminarDetalleFormularioNotificaciones", 
    {
    idNotificacionProducto: id,
    idNotificacion: $("#id_notificacion").val()
    },
    function (data) {
        $("#bodyTbl").html(data);
        });
        }
    }

    $("#fecha_notificacion").datepicker({ 
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd',
        onSelect: function(dateText, inst) {
        var fecha=new Date($('#fecha_notificacion').datepicker('getDate')); 
        fecha.setDate(fecha.getDate()+180);	 
        }
    });

    $("#anio").change(function () {    	
        if ($("#anio option:selected").val() !== "") {
        fn_cargarNotificaciones();
        }
    });

    function fn_cargarNotificaciones() {
    var anio = $("#anio option:selected").val();

    if (anio !== "") {
        $.post("<?php echo URL ?>NotificacionesFitosanitarias/Notificaciones/comboListaNotificacionXAnio", 
    {
        anio: $("#anio option:selected").val()
        }, function (data) {
        $("#notificacion").html(data);               
        });
    }else{
        $("#notificacion").html("");
        }
    }
    
    $("#id_pais_notifica_editar").change(function () {
    
        if ($("#id_pais_notifica_editar").val() !== "") {
        $("#pais_notifica_editar").val($("#id_pais_notifica_editar option:selected").text());
        }else{
        
            $("#pais_notifica_editar").val("");
        }
    });

    /************* FORMULARIO NUEVO ***************/

    function agregar() { 
        $(".alertaCombo").removeClass("alertaCombo");
        var error = false;
        if(!$.trim($("#id_localizacion").val())){
            error = true;
            $("#id_localizacion").addClass("alertaCombo");
        }
        $(".list_id_localizacion").each(function () { 
        if ($('#id_localizacion').val().trim() === $(this).val()) {
            $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");  
            error = true;  
        }
    });       

    if(!error){
        $("#estado").html("").addClass(""); 
        fn_agregarFila();
        } else{
        mostrarMensaje("Este país ya está agregado.", "FALLO");
            }
    }

    function fn_agregarFila() { 
        idLocalizacion = $("#id_localizacion").val();
        var campoLocalizacion = "<input type='hidden' name='idLocalizacion[]' readonly class='list_id_localizacion' value=" + $("#id_localizacion").val() + ">"+
        "<input type='hidden' name='nombreLocalizacion[]' readonly class='list_nombre_localizacion' value=" + $("#id_localizacion option:selected").text() + ">"; //identificador pais afectado
        cadena = "<tr align='center'>";
        cadena = cadena + "<td></td>"; //numeracion
        cadena = cadena + "<td>" + campoLocalizacion + $("#id_localizacion option:selected").text() + "</td>";
        cadena = cadena + "<td class='borrar'><button type='button' name='eliminar' id='eliminar' class='icono' onClick='fn_eliminar(" + '"grilla"' + ",getIndex(this, " + '"eliminar"' + "))'/></td>";
        cadena = cadena + "</tr>";
        $("#grilla tbody").append(cadena);
        fn_numerar();
    }

    $("#id_pais_notifica").change(function () {
    
        if ($("#id_pais_notifica").val() !== "") {
        $("#pais_notifica").val($("#id_pais_notifica option:selected").text());
        }else{
        
            $("#pais_notifica").val("");
        }
    });

    //Funcion que elimina una fila de la lista 
    function fn_eliminar(nomTabla, indice) { 
        respuesta = confirm("Desea eliminar");
        if (respuesta) {
            $("#" + nomTabla + " tbody").find("tr").eq(indice).remove();
        fn_numerar();
        }
    }

    function getIndex(boton, idElemento) {
        db = document.getElementsByName(idElemento); //Crea un arreglo db con todos los botones eliminar
        ne = db.length; //Cuenta el numero de elementos del arreglo
        for (i = 0; i < ne; i++)
        if (db[i] === boton) //Si el objeto del arreglo es igual al objeto (boton) recibido como parametro devuelve su indice i
        return i;
    }

    $("#formulario").submit(function (event) {
        event.preventDefault();
        var respuesta = JSON.parse(ejecutarJson($(this)).responseText);
    if (respuesta.estado == 'exito'){
        fn_filtrar();
        } else {
                $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
        }
    });
</script>