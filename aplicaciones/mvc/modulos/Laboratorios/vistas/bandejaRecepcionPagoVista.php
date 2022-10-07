<header>
    <h1>Registrar pago y Activar orden de trabajo</h1>
</header>
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Laboratorios'
      data-opcion='Pagos/guardar' data-destino="detalleItem"
      data-accionEnExito="NADA" method="post">
   
    <fieldset id="rpagos">
        <legend>Registrar pagos</legend>
        <div data-linea ="1">
            <label for="banco">Nombre del banco</label> 
            <select id="banco" name ="banco">
                <option value="">Seleccionar....</option>
                <?php
                echo $this->comboEntidadesBancarias();
                ?>
            </select>    
        </div >  
        <div data-linea="1">
            <label>Cuenta bancaria</label> 
            <select id="cuentaBancaria" name="cuentaBancaria" disabled="disabled">
            </select>
        </div>

        <div data-linea ="2">
            <label for="numeroDeposito"> N&uacute;mero dep&oacute;sito </label> 
            <input type ="text" id="numeroDeposito"
                   name ="numeroDeposito" value="<?php echo $this->modeloPagos->getNumeroDeposito(); ?>"
                   placeholder ="Número de depósto"
                   maxlength="32" />
        </div >

        <div data-linea ="2">
            <label for="fechaDeposito"> Fecha dep&oacute;sito </label> 
            <input type ="date" id="fechaDeposito"
                   name ="fechaDeposito" value="<?php echo $this->modeloPagos->getFechaDeposito(); ?>"
                   placeholder ="Fecha de depósito" max="<?php echo date("Y-m-d"); ?>"
                   maxlength="10" />
        </div >

        <div data-linea ="2">
            <label for="valorDepositado"> Valor dep&oacute;sito </label> 
            <input type ="text" id="valorDepositado" onkeypress="return filterFloat(event, this);"
                   name ="valorDepositado" value="<?php echo $this->modeloPagos->getValorDepositado(); ?>" 
                   step="0.01" value="0.00" placeholder="0.00" min="0.01" lang="en"
                   placeholder ="Valor de depósito" maxlength="10" />
        </div >

        <div data-linea ="3">
            <label for="totalSolicitud">Total Solicitud</label> 
            <input type ="text" id="totalSolicitud"
                   name ="totalSolicitud" style="background: #E2E2E2;" readonly value="<?php echo $this->totalPago; ?>"/>
        </div >

        <div data-linea ="8">			
            <input type ="hidden" name="id_pagos" id="id_pagos" value ="<?php echo $this->modeloPagos->getIdPagos() ?>">
            <input type ="hidden" id="idSolicitud" name ="idSolicitud" value="<?php echo $this->idSolicitud; ?>"/>
            <input type ="hidden" id="idLaboratorio" name ="idLaboratorio" value="<?php echo $this->idLaboratorio; ?>"/>
        </div >
        <span>
            <button type="button" class="mas" onclick="agregar()"> Agregar</button>
        </span>
        <table width="100%" id="grilla" class="lista" ALIGN="CENTER">
            <thead>
                <tr>
                    <th colspan="7">Pagos realizados</th>
                </tr>
                <tr>
                    <th>#</th>
                    <th>Entidad bancaria</th>
                    <th>Cuenta bancaria</th>
                    <th>Número dep&oacute;sito</th>
                    <th>Fecha dep&oacute;sito</th>
                    <th>Valor dep&oacute;sito</th>
                    <th>Eliminar</th>
                </tr>
            </thead>
            <tbody>
                <!-- codigo -->      
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" style="text-align: right">Total:</td>
                    <td id="total" style="text-align: right"></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
        <button type="submit" class="guardar"> Registrar pago</button>
        <input type ="hidden" id="idOrdenTrabajo" name ="idOrdenTrabajo" value="<?php echo $this->idOrdenTrabajo; ?>"/>
    </fieldset>
    
<fieldset id="ordenesT">
    <legend>&Oacute;rdenes de trabajo</legend>

    <table id="tablaOrdenTrabajo">
        <thead>
            <tr>
                <th>#</th>
                <th title="C&oacute;digo de la orden de trabajo, se genera la activar la orden">C&oacute;digo de Orden de Trabajo</th>
                <th title="Laboratorio">Laboratorio</th>
                <th title="Fecha en que se activa la orden">Fecha de inicio</th>
                <th title="Estado de la orden de trabajo">Estado</th>
                <th title="Costo de la orden">Costo en Orden</th>
                <th title="Descargar la orden de trabajo">Descargar</th>
            </tr>
        </thead>
        <tbody>
            
        </tbody>
    </table>
</fieldset>
</form >

<!-- Código javascript -->

<script type="text/javascript">
    $(document).ready(function () {
<?php echo $this->codigoJS ?>
 $("#ordenesT").css('visibility', 'hidden');
        distribuirLineas();
    });

    //solo permitir números (xx.xx)
    function filterFloat(evt, input) {
        // Backspace = 8, Enter = 13, ‘0′ = 48, ‘9′ = 57, ‘.’ = 46, ‘-’ = 43
        var key = window.Event ? evt.which : evt.keyCode;
        var chark = String.fromCharCode(key);
        var tempValue = input.value + chark;
        if (key >= 48 && key <= 57) {
            if (filter(tempValue) === false) {
                return false;
            } else {
                return true;
            }
        } else {
            if (key == 8 || key == 13 || key == 0) {
                return true;
            } else if (key == 46) {
                if (filter(tempValue) === false) {
                    return false;
                } else {
                    return true;
                }
            } else {
                return false;
            }
        }
    }
    function filter(__val__) {
        var preg = /^([0-9]+\.?[0-9]{0,2})$/;
        if (preg.test(__val__) === true) {
            return true;
        } else {
            return false;
        }
    }

    //Cuando seleccionamos un banco, llenamos el combo de cuentas bancarias
    $("#banco").change(function () {
        var idBanco = $(this).val();
        $.post("<?php echo URL ?>Laboratorios/BandejaRecepcion/comboCuentasBancarias/" + idBanco, function (data) {
            $("#cuentaBancaria").html(data);
            $("#cuentaBancaria").removeAttr("disabled");
        });
    });

    // Función llamada al Agregar item
    function agregar() {
        // verificar nulos
        if ($('#banco').val().trim() === "") {
            $('#banco').addClass("alertaCombo");
            mostrarMensaje("Seleccione Entidad bancaria", "FALLO");
            return false;
        }
        if ($('#cuentaBancaria').val().trim() === "") {
            $('#cuentaBancaria').addClass("alertaCombo");
            mostrarMensaje("Seleccione Cuenta bancaria", "FALLO");
            return false;
        }
        if ($('#numeroDeposito').val().trim() === "") {
            $('#numeroDeposito').addClass("alertaCombo");
            mostrarMensaje("Ingrese el número de comprobante", "FALLO");
            $('#numeroDeposito').focus();
            return false;
        }
        if ($('#fechaDeposito').val().trim() === "") {
            $('#fechaDeposito').addClass("alertaCombo");
            mostrarMensaje("Ingrese la fecha del depósito", "FALLO");
            $('#fechaDeposito').focus();
            return false;
        }

        if (isNaN(parseFloat($('#valorDepositado').val()))) {
            $('#valorDepositado').addClass("alertaCombo");
            mostrarMensaje("Ingrese un valor del depósito válido", "FALLO");
            $('#fechaDeposito').focus();
            return false;
        }

        if (parseFloat($('#valorDepositado').val()) < 0) {
            $('#valorDepositado').addClass("alertaCombo");
            mostrarMensaje("Ingrese un valor del depósito mayor a 0", "FALLO");
            $('#valorDepositado').focus();
            return false;
        }

        //verificar que el depósito no esté en la tabla
        var continuar = 1;
        $(".list_deposito").each(function () {
            if ($('#numeroDeposito').val().trim() === $(this).val()) {
                continuar = 0;
                mostrarMensaje("Este depósito ya está agregado", "FALLO");
                return false;
            }
        });

        if (continuar === 1) {
            //verificar que el depósito del banco seleccionado no esté registrado en el sistema
            $.post("<?php echo URL ?>Laboratorios/BandejaRecepcion/verficarDeposito",
                    {
                        banco: $("#banco").val(),
                        num_deposito: $("#numeroDeposito").val()
                    },
            function (data) {
                if (data.estado === 'ERROR') {
                    mostrarMensaje(data.mensaje, "FALLO");
                } else {
                    fn_agregarFila();
                }
            }, 'json');
        }
    }

    //Función que agrega una fila en la lista
    function fn_agregarFila() {
        idBanco = $("#banco").val();
        var campoBanco = "<input type='hidden' name='idBanco[]' readonly class='list_id_banco' value=" + $("#banco").val() + ">"; //identificador banco
        var campoCuenta = "<input type='hidden' name='idCuenta[]' readonly class='list_id_cuenta' value=" + $("#cuentaBancaria").val() + ">"; //identificador banco
        cadena = "<tr ALIGN='CENTER'>";
        cadena = cadena + "<td></td>"; //numeracion
        cadena = cadena + "<td>" + campoBanco + $("#banco option:selected").text() + "</td>";
        cadena = cadena + "<td>" + campoCuenta + $("#cuentaBancaria option:selected").text() + "</td>";
        cadena = cadena + "<td><input type='text' name='deposito[]' readonly class='list_deposito' style='background:transparent;border:0;text-align:right;width:120px' value=" + $("#numeroDeposito").val() + "></td>";
        cadena = cadena + "<td><input type='text' name='fecha[]' readonly style='background:transparent;border:0;text-align:right;width:100px' value=" + $("#fechaDeposito").val() + "></td>";
        var valorDepositado = parseFloat($("#valorDepositado").val()).toFixed(2);
        cadena = cadena + "<td><input type='text' name='valor[]' class='list_valor_deposito' style='background:transparent;border:0;text-align:right;width:100px' readonly value=" + valorDepositado + "></td>";
        // Se llama al metodo fn_eliminar enviando como argumento el index que se obtiene con el metodo getIndex()
        cadena = cadena + "<td class='borrar'><button type='button' name='eliminar' id='eliminar' class='icono' onClick='fn_eliminar(" + '"grilla"' + ",getIndex(this, " + '"eliminar"' + "))'/></td>";
        cadena = cadena + "</tr>";
        $("#grilla tbody").append(cadena);
        $("#numeroDeposito").val("");
        $("#fechaDeposito").val("");
        $("#valorDepositado").val("");
        $("#banco").val("");
        $("#cuentaBancaria").empty();
        fn_numerar();
    }
    //Funcion que elimina una fila de la lista 
    function fn_eliminar(nomTabla, indice) {
        respuesta = confirm("Desea eliminar");
        if (respuesta) {
            $("#" + nomTabla + " tbody").find("tr").eq(indice).remove();
            fn_numerar();
        }
    }
    //--------------------------------------------------------
    function getIndex(boton, idElemento) {
        db = document.getElementsByName(idElemento); //Crea un arreglo db con todos los botones eliminar
        ne = db.length; //Cuenta el numero de elementos del arreglo
        for (i = 0; i < ne; i++)
            if (db[i] === boton) //Si el objeto del arreglo es igual al objeto (boton) recibido como parametro devuelve su indice i
                return i;
    }

    //enumera las filas cuando se agrega y se elimina una fila
    function fn_numerar() {
        var total = $('#grilla >tbody >tr').length;
        for (var i = 1; i <= total; i++) {
            document.getElementById("grilla").rows[i + 1].cells[0].innerText = i;
        }
        fn_sumarDepositos();
    }

    // para suma los depositos
    function fn_sumarDepositos() {
        var total = 0;
        $(".list_valor_deposito").each(function () {
            total = parseFloat(total) + parseFloat($(this).val());
        });
        $("#total").html(total.toFixed(2));
    }

    $("#formulario").submit(function (event) {
        event.preventDefault();
        var totalItems = $("#grilla tbody").find("tr").length;
        if (totalItems === 0) {
            mostrarMensaje("Registre los pagos.", "FALLO");
        } else if (parseFloat($("#total").text()) !== parseFloat($("#totalSolicitud").val())) { //verificar que la suma de los depositos no sea menor que lo que debe pagar
            mostrarMensaje("El total depositado debe ser igual al que debe pagar.", "FALLO");
        } else {
            var error = false;
            if (!error) {
                var respuesta = JSON.parse(ejecutarJson($(this)).responseText);
                //Traemos la lista solo si guardo correctamenre
                if (respuesta.estado === 'exito')
                {
                    $("#fEstado").val('');
                    $("#fCodigo").val('<?php echo $this->modeloSolicitudes->getCodigo() ?>');
                    fn_filtrar();
                     
                     fn_actualizar_ot();
                }
            } else {
                $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
            }
        }
    });
    
    //Actualizar panel derecho
     function  fn_actualizar_ot(){
     var url = "<?php echo URL ?>Laboratorios/BandejaRecepcion/verOrdenesTrabajoJson";
     var itemsOt = new Array();
    var data = {
            id: <?php echo $this->idSolicitud ?>
        };   
    $.ajax({
            type: "POST",
            url: url,
            data: data,
            dataType: "text",
            contentType: "application/x-www-form-urlencoded; charset=latin1",
            beforeSend: function () {
            $("#tablaOrdenTrabajo tbody").html("");
            $("#ordenesT").css('visibility', 'visible');
            $("#rpagos").remove();
            
            },
            success: function (json) {
              itemsOt  = $.parseJSON(json);
           
	
	for(var contador = 0;contador<=itemsOt.length;contador++)
		$("#tablaOrdenTrabajo tbody").append(itemsOt[contador]);       
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $('estado').html('Error al cargar los campos de resultado')
            },
            complete: function () {

            }
        });
    }
</script>