			
<header>
    <h1><?php echo $this->accion; ?></h1>
</header>			
<form id = 'formulario' data-rutaAplicacion = 'laboratorios'		
      data-opcion = 'personas/guardar' data-destino ="detalleItem"			 
      data-accionEnExito ="NADA" method="post">			
    <fieldset>			<legend>Personas</legend>

        <div data-linea ="2">
            <label for="ci_ruc"> Cédula/RUC </label> 
            <input type ="text" id="ci_ruc"
                   name ="ci_ruc" value="<?php echo $this->modeloPersonas->getCiRuc(); ?>"
                   placeholder ="Número de cédula o ruc para la factura"
                   required  maxlength="512" />
        </div >

        <div data-linea ="3">
            <label for="nombre"> Nombre </label> 
            <input type ="text" id="nombre"
                   name ="nombre" value="<?php echo $this->modeloPersonas->getNombre(); ?>"
                   placeholder ="Nombre del cliente para la factura"
                   required  maxlength="512" />
        </div >

        <div data-linea ="4">
            <label for="direccion"> Dirección </label> 
            <input type ="text" id="direccion"
                   name ="direccion" value="<?php echo $this->modeloPersonas->getDireccion(); ?>"
                   placeholder ="Dirección del cliente a facturar"
                   required  maxlength="512" />
        </div >

        <div data-linea ="5">
            <label for="telefono"> Teléfono </label> 
            <input type ="text" id="telefono"
                   name ="telefono" value="<?php echo $this->modeloPersonas->getTelefono(); ?>"
                   placeholder ="Teléfono del cliente a facturar"
                   required  maxlength="512" />
        </div >

        <div data-linea ="6">
            <label for="email"> E-mail </label> 
            <input type ="text" id="email"
                   name ="email" value="<?php echo $this->modeloPersonas->getEmail(); ?>"
                   placeholder ="E-mail del cliente a facturar"
                   required  maxlength="512" />
        </div >

        <div data-linea ="7">
            <label for="contacto_proforma"> Contacto </label> 
            <input type ="text" id="contacto_proforma"
                   name ="contacto_proforma" value="<?php echo $this->modeloPersonas->getContactoProforma(); ?>"
                   placeholder ="Nombre del contacto de la institución que solicita la proforma"
                   required  maxlength="512" />
        </div >

        <div data-linea ="8">
            <label for="telefono_proforma"> Teléfono </label> 
            <input type ="text" id="telefono_proforma"
                   name ="telefono_proforma" value="<?php echo $this->modeloPersonas->getTelefonoProforma(); ?>"
                   placeholder ="Teléfono/Extensión del contacto de la institución que solicita la proforma"
                   required  maxlength="512" />
        </div >

        <div data-linea ="9">			
            <input type ="hidden" name="id_persona" id="id_persona" value ="<?php echo $this->modeloPersonas->getIdPersona() ?>">
            <button type ="submit" class="btnenviar">Guardar</button>
        </div >
    </fieldset >
</form >
<script type ="text/javascript">
    $(document).ready(function () {
<?php echo $this->codigoJS ?>
        construirValidador();
        distribuirLineas();
    });
    $("#formulario").submit(function (event) {
        event.preventDefault();
        var error = false;
        if (!error) {
            var respuesta = JSON.parse(ejecutarJson($(this)).responseText);
            //Traemos la lista solo si guardo correctamenre
            if (respuesta.estado == 'exito')
            {
                fn_filtrar();
            }
        } else {
            $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
        }
    });
</script>
