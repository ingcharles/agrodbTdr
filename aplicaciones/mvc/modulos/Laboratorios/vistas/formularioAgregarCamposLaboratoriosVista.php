<header>
    <h1><?php echo $this->accion; ?></h1>
</header>
<script src="<?php echo URL_RESOURCE ?>js/tinymce/tinymce.min.js"></script>

<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Laboratorios'
      data-opcion='Laboratorios/guardar' data-destino="detalleItem"
      data-accionEnExito="NADA" method="post">
    <fieldset>
        <legend>Agregar campos</legend>

        <div data-linea="1">
            <label for="nombre"> Nombre </label> 
            <input type="text" id="nombre"
                   name="nombre" class="nombre"
                   value=""
                   placeholder="Nombre de la dirección, laboratorio o su variable de configuración"
                   required maxlength="128" />
        </div>

        <div data-linea="2">
            <label for="descripcion"> Descripción </label> 
            <input type="text"
                   id="descripcion" name="descripcion"
                   value=""
                   placeholder="Información complementaria de cada variable, que puede servir como ayuda en las pantallas de usuario"
                   maxlength="512" />
        </div>

        <div data-linea="3">
            <label for="tipo_campo"> Tipo de campo </label> 
            <select
                id="tipo_campo" name="tipo_campo" required>
                <option value="">Seleccionar....</option>
                <option value="CHECK">CHECK</option>
                <option value="COMBOBOX">COMBOBOX</option>
                <option value="ENTERO">ENTERO</option>
                <option value="ETIQUETA">ETIQUETA</option>
                <option value="FECHA">FECHA</option>
                <option value="CHECKLIST">CHECKLIST</option>
                <option value="BOOLEANO">BOOLEANO</option>
                <option value="RADIOBUTTON">RADIOBUTTON</option>
                <option value="SUBETIQUETA">SUBETIQUETA</option>
                <option value="TEXTO">TEXTO</option>
                <option value="TEXTAREA">TEXTAREA</option>
                <option value="BOTON">BOTON</option>
                <option value="PROVINCIA">PROVINCIA</option>
                <option value="CANTON">CANTON</option>
                <option value="PARROQUIA">PARROQUIA</option>
                <option value="CRONOGRAMA">CRONOGRAMA POST-REGISTRO</option>
            </select>
        </div>

        <div data-linea="3">
            <label for="ultimo_nivel"> Último nivel </label> <select
                id="ultimo_nivel" name="ultimo_nivel" required>
                <option value="">Seleccionar....</option>
                <?php
                echo $this->crearComboSINO($this->modeloLaboratorios->getUltimoNivel());
                ?>
            </select>
        </div>

        <div data-linea="3">
            <label for="obligatorio"> Obligatorio </label> <select
                id="obligatorio" name="obligatorio" required>
                <option value="">Seleccionar....</option>
                <?php
                echo $this->crearComboSINO($this->modeloLaboratorios->getObligatorio());
                ?>
            </select>
        </div>

        <div data-linea="5">
            <label for="nivel_acceso"> Nivel de acceso </label>
            <select
                id="nivel_acceso" name="nivel_acceso" required>
                <option value="">Seleccionar....</option>
                <?php
                echo $this->comboNivelAcceso($this->modeloLaboratorios->getNivelAcceso());
                ?>
            </select>
        </div>

        <div data-linea="5">
            <label for="visible_en"> Donde es visible </label> <select
                id="visible_en" name="visible_en" required>
                <option value="">Seleccionar...</option>
                <option value="T">En todo</option>
                <option value="F">Solo formulario</option> 
                <option value="OT">Solo orden de trabajo</option> <!-- OT - Solo en el formulario de la solicitud para generar la orden de trabajo. -->
                <option value="N">Ninguno</option>
            </select>
        </div>
        
        <div data-linea="6">
            <label>Estado</label> 
            <select id="estado_registro" name="estado_registro" >

                <?php echo $this->combo2Estados($this->modeloLaboratorios->getEstadoRegistro()); ?>
            </select>
        </div>

        <div data-linea="6">
            <label for="orden"> Orden </label> 
            <input type="number" id="orden" name="orden"
                   value="<?php echo $this->modeloLaboratorios->getOrden(); ?>"
                   placeholder="Orden que se debe presentar en la pantalla" required/>
        </div>

        <div data-linea="7">
            <label for="data_linea"> Fila a desplegar </label> 
            <input type="number" id="data_linea" name="data_linea"
                   value="<?php echo $this->modeloLaboratorios->getDataLinea(); ?>"
                   placeholder="Indica como agrupar los elementos en el formulario, parte del core del sistema GUIA"/>
        </div>
<div data-linea="7">
            <label for="orientacion">Orientaci&oacute;n</label> 
            <select id="orientacion" name="orientacion" required>
                <option value="">Seleccionar....</option>
                <?php
                echo $this->comboDespliegue($this->modeloLaboratorios->getOrientacion());
                ?>
            </select>
        </div>
        <input type="hidden" name="nivel" id="nivel" value="2" />
        <input type="hidden" id="codigo" name="codigo" value="<?php echo $this->modeloLaboratorios->getCodigo(); ?>"/>
        <input type="hidden" id="fk_id_laboratorio" name="fk_id_laboratorio" value="<?php echo $this->modeloLaboratorios->getIdLaboratorio(); ?>"/>
        <div data-linea="8">
            <input type="hidden" name="id_laboratorio" id="id_laboratorio" value="">
            <button type="submit" class="guardar"> Guardar</button>
        </div>
        
      
    </fieldset>
</form>
<script type="text/javascript">
    $(document).ready(function () {
        <?php echo $this->codigoJS ?>
        
        distribuirLineas();
      
         


       
        $("#formulario").submit(function (event) {
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
    });

   

</script>
