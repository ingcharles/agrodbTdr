<header>
    <h1><?php echo $this->accion; ?></h1>
</header>			
<form id = 'formulario' data-rutaAplicacion = '<?php echo URL_MVC_FOLDER; ?>Laboratorios' data-opcion = 'FirmasElectronicas/guardar' 
      data-destino ="detalleItem" data-accionEnExito ="NADA" method="post">
    <fieldset>
        <legend>Firmas Electr&oacute;nicas</legend>

        <div data-linea ="2">
            <label for="identificador"> Identificador </label> 
            <input type ="number" id="identificador"
                   name ="identificador" value="<?php echo $this->modeloFirmasElectronicas->getIdentificador(); ?>"
                   placeholder ="Cedula de identidad o pasaporte."
                   required  maxlength="13"/>
        </div>     

        <div data-linea="5">
            <div id="mensajeFirma" ></div>
            <input type="hidden" class="rutaArchivo" name="rutaArchivo" value="0" />
            <input type="file" class="archivo"
                   accept="application/excel" />
            <div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
        </div>

        <div data-linea ="6">			
            <input type ="hidden" name="id_firma_electronica" id="id_firma_electronica" 
                   value ="<?php echo $this->modeloFirmasElectronicas->getIdFirmaElectronica() ?>">
        </div>
        <button type="button" id="btnSubirArchivo" onclick="fn_subirArchivo()" class="subirArchivo adjunto"
                data-rutaCarga="<?php echo URL_DIR_LAB_FIRMAS ?>">Subir firma</button>
    </fieldset>
</form >
<script type ="text/javascript">

    $(document).ready(function () {
        construirValidador();
        distribuirLineas();
<?php echo $this->codigoJS; ?>
    });

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

    function fn_subirArchivo() {

        nombre_archivo = "<?php echo 'firma' . (md5(time())); ?>";

        var boton = $("#btnSubirArchivo");
        var archivo = boton.parent().find(".archivo");
        var rutaArchivo = boton.parent().find(".rutaArchivo");
        var extension = archivo.val().split('.');
        var estado = boton.parent().find(".estadoCarga");
        subirArchivo(
                archivo
                , nombre_archivo
                , boton.attr("data-rutaCarga")
                , rutaArchivo
                , new carga(estado, archivo, boton)
                );
        //Enviamos el nombre del archivo para procesar el registro en la base de datos
        $.post("<?php echo URL ?>Laboratorios/FirmasElectronicas/guardar",
                {
                    id_firma_electronica: $("#id_firma_electronica").val(),
                    ruta: nombre_archivo + "." + extension[extension.length - 1],
                    identificador: $("#identificador").val(),
                    estado: $("#estado_registro").val()
                },
        function (data) {
            $("#mensajeFirma").html(data);
            setTimeout(
                    function () {

                        abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"), "#listadoItems", true);
                    }, 1000);
        });

    }

    function carga(estado, archivo, boton) {
        this.esperar = function (msg) {
            estado.html("Cargando el archivo...");
            archivo.addClass("amarillo");
        };

        this.exito = function (msg) {
            estado.html("El archivo ha sido cargado.");
            archivo.removeClass("amarillo");
            archivo.addClass("verde");
            boton.attr("disabled", "disabled");
            $("#nuevoDocumento :submit").removeAttr("disabled");
        };

        this.error = function (msg) {
            estado.html(msg);
            archivo.removeClass("amarillo");
            archivo.addClass("rojo");
        };
    }
</script>
