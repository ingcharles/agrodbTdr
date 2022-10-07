			
<header>
    <h1><?php echo $this->accion; ?></h1>
</header>			<form id = 'formulario' data-rutaAplicacion = '<?php echo URL_MVC_FOLDER; ?>Laboratorios'			 data-opcion = 'auditorialablog/guardar' data-destino ="detalleItem"			 data-accionEnExito ="ACTUALIZAR" method="post">			<fieldset>			<legend>AuditoriaLabLog</legend>			
        <div data-linea ="1">
            <label for="log_id"> Identificación de la tabla </label> 
            <input type ="text" id="log_id"
                   name ="log_id" value="<?php echo $this->modeloAuditoriaLabLog->getLogId(); ?>"
                   placeholder ="Identificación de la tabla"
                   required  maxlength="512" />
        </div >

        <div data-linea ="2">
            <label for="log_relid"> OID es un identificador unico de cada objeto (llamese tabla,columna, tipo de dato etc) </label> 
            <input type ="text" id="log_relid"
                   name ="log_relid" value="<?php echo $this->modeloAuditoriaLabLog->getLogRelid(); ?>"
                   placeholder ="OID es un identificador unico de cada objeto (llamese tabla,columna, tipo de dato etc)"
                   required  maxlength="512" />
        </div >

        <div data-linea ="3">
            <label for="log_schema"> Nombre del esquema </label> 
            <input type ="text" id="log_schema"
                   name ="log_schema" value="<?php echo $this->modeloAuditoriaLabLog->getLogSchema(); ?>"
                   placeholder ="Nombre del esquema"
                   required  maxlength="512" />
        </div >

        <div data-linea ="4">
            <label for="log_table"> Nombre de la tabla que se realizó la operación </label> 
            <input type ="text" id="log_table"
                   name ="log_table" value="<?php echo $this->modeloAuditoriaLabLog->getLogTable(); ?>"
                   placeholder ="Nombre de la tabla que se realizó la operación"
                   required  maxlength="512" />
        </div >

        <div data-linea ="5">
            <label for="log_session_user"> Nombre del usuario de la base de datos </label> 
            <input type ="text" id="log_session_user"
                   name ="log_session_user" value="<?php echo $this->modeloAuditoriaLabLog->getLogSessionUser(); ?>"
                   placeholder ="Nombre del usuario de la base de datos"
                   required  maxlength="512" />
        </div >

        <div data-linea ="6">
            <label for="log_when"> Fecha cuando ocurre el evento </label> 
            <input type ="text" id="log_when"
                   name ="log_when" value="<?php echo $this->modeloAuditoriaLabLog->getLogWhen(); ?>"
                   placeholder ="Fecha cuando ocurre el evento"
                   required  maxlength="512" />
        </div >

        <div data-linea ="7">
            <label for="log_client_addr"> Ip del cliente de base de datos </label> 
            <input type ="text" id="log_client_addr"
                   name ="log_client_addr" value="<?php echo $this->modeloAuditoriaLabLog->getLogClientAddr(); ?>"
                   placeholder ="Ip del cliente de base de datos"
                   required  maxlength="512" />
        </div >

        <div data-linea ="8">
            <label for="log_operation"> Tipo de operación </label> 
            <input type ="text" id="log_operation"
                   name ="log_operation" value="<?php echo $this->modeloAuditoriaLabLog->getLogOperation(); ?>"
                   placeholder ="Tipo de operación"
                   required  maxlength="512" />
        </div >

        <label for="log_old_values"> Valores antiguos </label> 
        <div data-linea ="9">
            <textarea id="log_old_values" name="log_old_values" 
                      placeholder="Todos los valores nuevos"><?php echo $this->modeloAuditoriaLabLog->getLogOldValues(); ?></textarea>
        </div >

        <label for="log_new_values"> Valores nuevos </label> 
        <div data-linea ="10">
            <textarea id="log_new_values" name="log_new_values" 
                      placeholder="Todos los valores nuevos"><?php echo $this->modeloAuditoriaLabLog->getLogNewValues(); ?></textarea>
        </div >

        <label for="log_old_all"> Todos los valores antiguos </label> 
        <div data-linea ="11">
            <textarea id="log_old_all" name="log_old_all" 
                      placeholder="Todos los valores antiguos"><?php echo $this->modeloAuditoriaLabLog->getLogOldAll(); ?></textarea>
        </div >

        <label for="log_new_all"> Todos los valores nuevos </label> 
        <div data-linea ="12">
            <textarea id="log_new_all" name="log_new_all" 
                      placeholder="Todos los valores nuevos"><?php echo $this->modeloAuditoriaLabLog->getLogNewAll(); ?></textarea>
        </div >
    </fieldset>
</form >
<script type ="text/javascript">
    $(document).ready(function () {
        construirValidador();
        distribuirLineas();
    });
    $("#formulario").submit(function (event) {
        event.preventDefault();
        var error = false;
        if (!error) {
            var respuesta = JSON.parse(ejecutarJson($(this)).responseText);
            //Traemos la lista solo si guardo correctamenre
            if(respuesta.estado == 'exito')
            {
            //fn_filtrar();
            }

        } else {
            $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
        }
    });
</script>
