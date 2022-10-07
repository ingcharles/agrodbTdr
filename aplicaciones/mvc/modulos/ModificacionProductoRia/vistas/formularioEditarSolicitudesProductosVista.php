<header>
    <h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario'
      data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>ModificacionProductoRia'
      data-opcion='SolicitudesProductos/guardar' data-destino="detalleItem"
      data-accionEnExito="ACTUALIZAR" method="post">

    <?php echo $this->datosGenerales; ?>
    <?php echo $this->datosRevision; ?>

</form>
<?php echo $this->pestania; ?>


<script type="text/javascript">
    $(document).ready(function () {
        construirValidador();
        distribuirLineas();
        construirAnimacion($(".pestania"));
    });

    $('button.subirArchivo').click(function (event) {
        var boton = $(this);
        var tipo_archivo = boton.parent().find(".rutaArchivo").attr("id");
        var nombre_archivo = tipo_archivo + "<?php echo '_' . (md5(time())); ?>";
        var archivo = boton.parent().find(".archivo");
        var rutaArchivo = boton.parent().find(".rutaArchivo");
        var extension = archivo.val().split('.');
        var estado = boton.parent().find(".estadoCarga");

        if (extension[extension.length - 1].toUpperCase() == 'PDF') {
            subirArchivo(
                archivo
                , nombre_archivo
                , boton.attr("data-rutaCarga")
                , rutaArchivo
                , new carga(estado, archivo, boton)
            );
        } else {
            estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
            archivo.val("0");
        }
    });

    ////////////////////////////////////////////////////////
    /////-----------Categorias toxicologicas------------////
    ////////////////////////////////////////////////////////

    //Funcion para agregar fila de categorias toxicologicas
    $("#agregarCategoriaToxicologica").click(function (event) {
        event.preventDefault();
        mostrarMensaje("", "");
        $(".alertaCombo").removeClass("alertaCombo");
        var error = false;

        var tipomodificacion = $("#agregarCategoriaToxicologica").attr("data-tipomodificacion");

        $('#fCategoriaToxicologica .validacion').each(function (i, obj) {
            if (!$.trim($(this).val())) {
                error = true;
                $(this).addClass("alertaCombo");
            }
        });

        if (!$.trim($("#r" + tipomodificacion).val()) || $("#r" + tipomodificacion).val() == 0) {
            error = true;
            $("#v" + tipomodificacion).addClass("alertaCombo");
        }

        if (!error) {

            $("#estado").html("").removeClass('alerta');

            $.post("<?php echo URL ?>ModificacionProductoRia/DetalleSolicitudesProductos/guardarDetalleSolicitud",
                {
                    id_categoria_toxicologica: $("#id_categoria_toxicologica").val(),
                    categoria_toxicologica: $("#id_categoria_toxicologica option:selected").text(),
                    codigo_tipo_modificacion: tipomodificacion,
                    id_detalle_solicitud_producto: $("#id_detalle_solicitud_producto" + tipomodificacion).val(),
                    tiempo_atencion: $("#id_categoria_toxicologica").attr("data-tiempoatencion"),
                    ruta_documento_respaldo: $("#rmodificarCategoriaToxicologica").val(),
                },
                function (data) {
                    if (data.validacion === 'Fallo') {
                        mostrarMensaje(data.resultado, "FALLO");
                    } else {
                        $("#tCategoriaToxicologica tbody").append(data.filaCategoriaToxicologica);
                    }
                }, 'json');
        }

    });

    //Funcion que elimina una fila del detalle de categoria toxicologica
    function fn_eliminarCategoriaToxicologica(idCategoriaToxicologica) {

        $("#estado").html("").removeClass('alerta');

        $.post("<?php echo URL ?>ModificacionProductoRia/CategoriasToxicologicas/borrar",
            {
                elementos: idCategoriaToxicologica
            },
            function (data) {
                $("#fila" + idCategoriaToxicologica).remove();
            });
    }

    ////////////////////////////////////////////////////////
    /////-------Fin Categorias toxicologicas------------////
    ////////////////////////////////////////////////////////


    ////////////////////////////////////////////////////////
    /////---------------Periodos Reingreso--------------////
    ////////////////////////////////////////////////////////

    //Funcion para agregar fila de periodo de reingreso
    $("#agregarPeriodoReingreso").click(function (event) {
        event.preventDefault();
        mostrarMensaje("", "");
        $(".alertaCombo").removeClass("alertaCombo");
        var error = false;
		
        var tipomodificacion = $("#agregarPeriodoReingreso").attr("data-tipomodificacion");

        $('#fPeriodoReingreso .validacion').each(function (i, obj) {
            if (!$.trim($(this).val())) {
                error = true;
                $(this).addClass("alertaCombo");
            }
        });

        if (!$.trim($("#r" + tipomodificacion).val()) || $("#r" + tipomodificacion).val() == 0) {
            error = true;
            $("#v" + tipomodificacion).addClass("alertaCombo");
        }

        if (!error) {

            $("#estado").html("").removeClass('alerta');
            
            $.post("<?php echo URL ?>ModificacionProductoRia/DetalleSolicitudesProductos/guardarDetalleSolicitud",
                {

                    periodo_reingreso: $("#periodo_reingreso").val(),
					codigo_tipo_modificacion: tipomodificacion,
                    id_detalle_solicitud_producto: $("#id_detalle_solicitud_producto" + tipomodificacion).val(),
                    tiempo_atencion: $("#periodo_reingreso").attr("data-tiempoatencion"),
                    ruta_documento_respaldo: $("#rmodificarPeriodoReingreso").val()
                },
                function (data) {
                    if (data.validacion === 'Fallo') {
                        mostrarMensaje(data.resultado, "FALLO");
                    } else {
                        $("#tPeriodoReingreso tbody").append(data.filaPeriodoReingreso);
                    }
                }, 'json');

        } else {
            $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
        }
    });

    //Funcion que elimina una fila del detalle de periodo reingreso
    function fn_eliminarPeriodoReingreso(idPeriodoReingreso) {

        $("#estado").html("").removeClass('alerta');

        $.post("<?php echo URL ?>ModificacionProductoRia/PeriodosReingresos/borrar",
            {
                elementos: idPeriodoReingreso
            },
            function (data) {
                $("#fila" + idPeriodoReingreso).remove();
            });
    }

    ////////////////////////////////////////////////////////
    /////------------Fin Periodos Reingreso-------------////
    ////////////////////////////////////////////////////////
	
	
	////////////////////////////////////////////////////////
    ////////---------------Vidas Utiles--------------////////
    ////////////////////////////////////////////////////////

    //Funcion para agregar fila de vidas utiles
    $("#agregarVidaUtil").click(function (event) {
        event.preventDefault();
        mostrarMensaje("", "");
        $(".alertaCombo").removeClass("alertaCombo");
        var error = false;
        
        var tipomodificacion = $("#agregarVidaUtil").attr("data-tipomodificacion");

        $('#fVidaUtil .validacion').each(function (i, obj) {
            if (!$.trim($(this).val())) {
                error = true;
                $(this).addClass("alertaCombo");
            }
        });

        if (!$.trim($("#r" + tipomodificacion).val()) || $("#r" + tipomodificacion).val() == 0) {
            error = true;
            $("#v" + tipomodificacion).addClass("alertaCombo");
        }

        if (!error) {

            $("#estado").html("").removeClass('alerta');
            
            $.post("<?php echo URL ?>ModificacionProductoRia/DetalleSolicitudesProductos/guardarDetalleSolicitud",
                {

                    vida_util: $("#vida_util").val(),
                    codigo_tipo_modificacion: tipomodificacion,
                    id_detalle_solicitud_producto: $("#id_detalle_solicitud_producto" + tipomodificacion).val(),
                    tiempo_atencion: $("#vida_util").attr("data-tiempoatencion"),
                    ruta_documento_respaldo: $("#rmodificarVidaUtil").val()

                },
                function (data) {
                    if (data.validacion === 'Fallo') {
                        mostrarMensaje(data.resultado, "FALLO");
                    } else {
                        $("#tVidaUtil tbody").append(data.filaVidaUtil);
                    }
                }, 'json');

        } else {
            $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
        }
    });

    //Funcion que elimina una fila de vida util
    function fn_eliminarVidaUtil(idVidaUtil) {

        $("#estado").html("").removeClass('alerta');

        $.post("<?php echo URL ?>ModificacionProductoRia/VidasUtiles/borrar",
            {
                elementos: idVidaUtil
            },
            function (data) {
                $("#fila" + idVidaUtil).remove();
            });
    }

    ////////////////////////////////////////////////////////
    /////--------------Fin Vidas Uiles------------------////
    ////////////////////////////////////////////////////////


    ////////////////////////////////////////////////////////
    ////////---------Estados Registros--------------////////
    ////////////////////////////////////////////////////////

    //Funcion para agregar fila de estado registro
    $("#agregarEstadoRegistro").click(function (event) {
        event.preventDefault();
        mostrarMensaje("", "");
        $(".alertaCombo").removeClass("alertaCombo");
        var error = false;
        
        var tipomodificacion = $("#agregarEstadoRegistro").attr("data-tipomodificacion");

        $('#fEstadoRegistro .validacion').each(function (i, obj) {
            if (!$.trim($(this).val())) {
                error = true;
                $(this).addClass("alertaCombo");
            }
        });

        if (!$.trim($("#r" + tipomodificacion).val()) || $("#r" + tipomodificacion).val() == 0) {
            error = true;
            $("#v" + tipomodificacion).addClass("alertaCombo");
        }
        
        if(!$("#validacion_cancela_registro").is(':checked')) {
    		error = true;
    		$("#validacion_cancela_registro").addClass("alertaCombo");
		}

        if (!error) {

            $("#estado").html("").removeClass('alerta');

            $.post("<?php echo URL ?>ModificacionProductoRia/DetalleSolicitudesProductos/guardarDetalleSolicitud",
                {

                    estado: $("#estado_producto").val(),
                    estado_valor: $("#estado_producto option:selected").text(),
                    validacion_cancela_registro : $("#validacion_cancela_registro").val(),
                    codigo_tipo_modificacion: tipomodificacion,
                    id_detalle_solicitud_producto: $("#id_detalle_solicitud_producto" + tipomodificacion).val(),
                    tiempo_atencion: $("#estado_producto").attr("data-tiempoatencion"),
                    ruta_documento_respaldo: $("#rmodificarEstadoRegistro").val()

                },
                function (data) {
                    if (data.validacion === 'Fallo') {
                        mostrarMensaje(data.resultado, "FALLO");
                    } else {
                        $("#tEstadoRegistro tbody").append(data.filaEstadoRegistro);
                    }
                }, 'json');

        } else {
            $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
        }
    });

    //Funcion que elimina una fila de estado registro
    function fn_eliminarEstadoRegistro(idEstadoRegistro) {

        $("#estado").html("").removeClass('alerta');

        $.post("<?php echo URL ?>ModificacionProductoRia/EstadosRegistros/borrar",
            {
                elementos: idEstadoRegistro
            },
            function (data) {
                $("#fila" + idEstadoRegistro).remove();
            });
    }

    ////////////////////////////////////////////////////////
    /////----------Fin Estados Registros----------------////
    ////////////////////////////////////////////////////////


    ////////////////////////////////////////////////////////
    /////----------Vía Administracion Dosis-------------////
    ////////////////////////////////////////////////////////

    //Funcion para agregar fila de via de administracion dosis
    $("#agregarViaAdministracionDosis").click(function (event) {
        event.preventDefault();
        mostrarMensaje("", "");
        $(".alertaCombo").removeClass("alertaCombo");
        var error = false;
        
        var tipomodificacion = $("#agregarViaAdministracionDosis").attr("data-tipomodificacion");

        $('#fViaAdministracionDosis .validacion').each(function (i, obj) {
            if (!$.trim($(this).val())) {
                error = true;
                $(this).addClass("alertaCombo");
            }
        });

        if (!$.trim($("#r" + tipomodificacion).val()) || $("#r" + tipomodificacion).val() == 0) {
            error = true;
            $("#v" + tipomodificacion).addClass("alertaCombo");
        }

        if (!error) {

            $("#estado").html("").removeClass('alerta');

            $.post("<?php echo URL ?>ModificacionProductoRia/DetalleSolicitudesProductos/guardarDetalleSolicitud",
                {

                    dosis: $("#dosis").val(),
                    unidad_dosis: $("#unidad_dosis").val(),
                    codigo_tipo_modificacion: tipomodificacion,
                    id_detalle_solicitud_producto: $("#id_detalle_solicitud_producto" + tipomodificacion).val(),
                    tiempo_atencion: $("#dosis").attr("data-tiempoatencion"),
                    ruta_documento_respaldo: $("#rmodificarViaAdmimistracionDosis").val()

                },
                function (data) {
                    if (data.validacion === 'Fallo') {
                        mostrarMensaje(data.resultado, "FALLO");
                    } else {
                        $("#tViaAdministracionDosis tbody").append(data.filaViaAdministracionDosis);
                    }
                }, 'json');

        } else {
            $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
        }
    });

    //Funcion que elimina una fila del detalle de via de administracion dosis
    function fn_eliminarViaAdministracionDosis(idViaAdministracionDosis) {

        $("#estado").html("").removeClass('alerta');

        $.post("<?php echo URL ?>ModificacionProductoRia/ViasAdministracionesDosis/borrar",
            {
                elementos: idViaAdministracionDosis
            },
            function (data) {
                $("#fila" + idViaAdministracionDosis).remove();
            });
    }

    ////////////////////////////////////////////////////////
    /////---------Fin Via Administracion Dosis----------////
    ////////////////////////////////////////////////////////


    ////////////////////////////////////////////////////////
    /////-----------------Periodos Retiro---------------////
    ////////////////////////////////////////////////////////

    //Funcion para agregar fila de periodo retiro
    $("#agregarPeriodoRetiro").click(function (event) {
        event.preventDefault();
        mostrarMensaje("", "");
        $(".alertaCombo").removeClass("alertaCombo");
        var error = false;
        
        var tipomodificacion = $("#agregarPeriodoRetiro").attr("data-tipomodificacion");

        $('#fPeriodoRetiro .validacion').each(function (i, obj) {
            if (!$.trim($(this).val())) {
                error = true;
                $(this).addClass("alertaCombo");
            }
        });

        if (!$.trim($("#r" + tipomodificacion).val()) || $("#r" + tipomodificacion).val() == 0) {
            error = true;
            $("#v" + tipomodificacion).addClass("alertaCombo");
        }

        if (!error) {

            $("#estado").html("").removeClass('alerta');

            $.post("<?php echo URL ?>ModificacionProductoRia/DetalleSolicitudesProductos/guardarDetalleSolicitud",
                {

                    periodo_retiro: $("#periodo_retiro").val(),
                    codigo_tipo_modificacion: tipomodificacion,
                    id_detalle_solicitud_producto: $("#id_detalle_solicitud_producto" + tipomodificacion).val(),
                    tiempo_atencion: $("#periodo_retiro").attr("data-tiempoatencion"),
                    ruta_documento_respaldo: $("#rmodificarPeriodoRetiro").val()

                },
                function (data) {
                    if (data.validacion === 'Fallo') {
                        mostrarMensaje(data.resultado, "FALLO");
                    } else {
                        $("#tPeriodoRetiro tbody").append(data.filaPeriodoRetiro);
                    }
                }, 'json');

        } else {
            $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
        }
    });

    //Funcion que elimina una fila del detalle de periodo retiro
    function fn_eliminarPeriodoRetiro(idPeriodoRetiro) {

        $("#estado").html("").removeClass('alerta');

        $.post("<?php echo URL ?>ModificacionProductoRia/PeriodosRetiros/borrar",
            {
                elementos: idPeriodoRetiro
            },
            function (data) {
                $("#fila" + idPeriodoRetiro).remove();
            });
    }

    ////////////////////////////////////////////////////////
    /////-------------Fin Periodos Retiro---------------////
    ////////////////////////////////////////////////////////


    ////////////////////////////////////////////////////////
    /////--------------Nombres Comerciales--------------////
    ////////////////////////////////////////////////////////

    //Funcion para agregar fila de nombre comercial
    $("#agregarNombreComercial").click(function (event) {
        event.preventDefault();
        mostrarMensaje("", "");
        $(".alertaCombo").removeClass("alertaCombo");
        var error = false;
        
        var tipomodificacion = $("#agregarNombreComercial").attr("data-tipomodificacion");

        $('#fNombreComercial .validacion').each(function (i, obj) {
            if (!$.trim($(this).val())) {
                error = true;
                $(this).addClass("alertaCombo");
            }
        });

        if (!$.trim($("#r" + tipomodificacion).val()) || $("#r" + tipomodificacion).val() == 0) {
            error = true;
            $("#v" + tipomodificacion).addClass("alertaCombo");
        }

        if (!error) {

            $("#estado").html("").removeClass('alerta');

            $.post("<?php echo URL ?>ModificacionProductoRia/DetalleSolicitudesProductos/guardarDetalleSolicitud",
                {

                    nombre_comercial: $("#nombre_comercial").val(),
                    codigo_tipo_modificacion: tipomodificacion,
                    id_detalle_solicitud_producto: $("#id_detalle_solicitud_producto" + tipomodificacion).val(),
                    tiempo_atencion: $("#nombre_comercial").attr("data-tiempoatencion"),
                    ruta_documento_respaldo: $("#rmodificarNombreComercial").val()

                },
                function (data) {
                    if (data.validacion === 'Fallo') {
                        mostrarMensaje(data.resultado, "FALLO");
                    } else {
                        $("#tNombreComercial tbody").append(data.filaNombreComercial);
                    }
                }, 'json');

        } else {
            $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
        }
    });

    //Funcion que elimina una fila del nombre comercial
    function fn_eliminarNombreComercial(idNombreComercial) {

        $("#estado").html("").removeClass('alerta');

        $.post("<?php echo URL ?>ModificacionProductoRia/NombresComerciales/borrar",
            {
                elementos: idNombreComercial
            },
            function (data) {
                $("#fila" + idNombreComercial).remove();
            });
    }

    ////////////////////////////////////////////////////////
    /////------------Fin Nombre Comercial---------------////
    ////////////////////////////////////////////////////////
    

    ////////////////////////////////////////////////////////
    /////------------Adicion presentacion---------------////
    ////////////////////////////////////////////////////////

    //Funcion para agregar fila de adicion de presentacion
    $("#agregarAdicionPresentacion").click(function (event) {
        event.preventDefault();
        mostrarMensaje("", "");
        $(".alertaCombo").removeClass("alertaCombo");
        var error = false;
        
        var tipomodificacion = $("#agregarAdicionPresentacion").attr("data-tipomodificacion");

        $('#fAdicionPresentacion .validacion').each(function (i, obj) {
            if (!$.trim($(this).val())) {
                error = true;
                $(this).addClass("alertaCombo");
            }
        });

        if (!$.trim($("#r" + tipomodificacion).val()) || $("#r" + tipomodificacion).val() == 0) {
            error = true;
            $("#v" + tipomodificacion).addClass("alertaCombo");
        }

        if (!error) {

            $("#estado").html("").removeClass('alerta');

            $.post("<?php echo URL ?>ModificacionProductoRia/DetalleSolicitudesProductos/guardarDetalleSolicitud",
                {
					id_producto: $("#id_producto").val(),
                    presentacion: $("#presentacion").val(),
                    unidad_medida: $("#unidad_medida").val(),
                    codigo_tipo_modificacion: tipomodificacion,
                    id_detalle_solicitud_producto: $("#id_detalle_solicitud_producto" + tipomodificacion).val(),
                    tiempo_atencion: $("#presentacion").attr("data-tiempoatencion"),
                    ruta_documento_respaldo: $("#rmodificarAdicionPresentacion").val()

                },
                function (data) {
                    if (data.validacion === 'Fallo') {
                        mostrarMensaje(data.resultado, "FALLO");
                    } else {
                        $("#tAdicionPresentacion tbody").append(data.filaAdicionPresentacion);
                    }
                }, 'json');

        } else {
            $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
        }
    });

    //Funcion que elimina una fila del detalle de adicion presentacion
    function fn_eliminarAdicionPresentacion(idAdicionPresentacion) {

		$("#estado").html("").removeClass('alerta');

        $.post("<?php echo URL ?>ModificacionProductoRia/AdicionesPresentaciones/borrar",
        {
            elementos: idAdicionPresentacion
        },
        function (data) {
            $("#fila" + idAdicionPresentacion).remove();
        });
    }
    
    //Funcion que cambia el estado de una adicion presentacion
    function fn_cambiarEstadoAdicionPresentacion (idTablaOrigen) {

	var tabla = idTablaOrigen.toString().replace('.','A');
    var tipomodificacion = $("#agregarAdicionPresentacion").attr("data-tipomodificacion");
	
	$.post("<?php echo URL ?>ModificacionProductoRia/AdicionesPresentaciones/guardarEstadoAdicionPresentacion",
            {
                id_detalle_solicitud_producto: $("#id_detalle_solicitud_producto" + tipomodificacion).val(),
				id_tabla_origen : tabla
            },
            function (data) {
                if (data.estado === 'FALLO') {
                    mostrarMensaje(data.resultado, "FALLO");
                } else {
                    mostrarMensaje(data.resultado, "EXITO");
                    if($("#fila" + tabla +" td.activo").length!=0){
                        $("#fila" + tabla +" td.activo").addClass('inactivo');
                        $("#fila" + tabla +" td.inactivo").removeClass('activo');
                    }else{
                        $("#fila" + tabla +" td.inactivo").addClass('activo');
                        $("#fila" + tabla +" td.activo").removeClass('inactivo');
                    }
                }
            }, 'json');
    }

    ////////////////////////////////////////////////////////
    /////---------Fin adicion presentacion----------////
    ////////////////////////////////////////////////////////
	

	////////////////////////////////////////////////////////
    /////--------Adicion presentacion agricola----------////
    ////////////////////////////////////////////////////////
    
    $("#id_partida_arancelaria_plaguicida").change(function (event) {
        mostrarMensaje("", "EXITO");
        if ($("#id_area").val() != '') {
            $.post("<?php echo URL ?>Catalogos/CodigosCompSupl/obtenerCodigosSuplemetariosComplementariosPorIdPartida",
                {
                    id_partida_arancelaria: $("#id_partida_arancelaria_plaguicida").val()
                }, function (data) {
                    if (data.estado === 'EXITO') {
                        $("#id_codigo_complementario_suplementario").html(data.comboComplementarioSuplementario);
                    }
                }, 'json');
        } else {
            mostrarMensaje("Por favor seleccione un valor", "FALLO");
        }
    });
    
    //Funcion para agregar fila de adicion de presentacion plaguicia
    $("#agregarAdicionPresentacionPlaguicida").click(function (event) {
        event.preventDefault();
        mostrarMensaje("", "");
        $(".alertaCombo").removeClass("alertaCombo");
        var error = false;
        
        var tipomodificacion = $("#agregarAdicionPresentacionPlaguicida").attr("data-tipomodificacion");

        $('#fAdicionPresentacion .validacion').each(function (i, obj) {
            if (!$.trim($(this).val())) {
                error = true;
                $(this).addClass("alertaCombo");
            }
        });

        if (!$.trim($("#r" + tipomodificacion).val()) || $("#r" + tipomodificacion).val() == 0) {
            error = true;
            $("#v" + tipomodificacion).addClass("alertaCombo");
        }

        if (!error) {

            $("#estado").html("").removeClass('alerta');

            $.post("<?php echo URL ?>ModificacionProductoRia/DetalleSolicitudesProductos/guardarDetalleSolicitud",
                {
					id_producto : $("#id_producto_plaguicida").val(),
					id_partida_arancelaria : $("#id_partida_arancelaria_plaguicida").val(),
					partida_arancelaria : $("#id_partida_arancelaria_plaguicida option:selected").text(),
					codigo_producto :  $("#id_partida_arancelaria_plaguicida option:selected").attr("data-codigoproducto"),
					id_codigo_complementario_suplementario : $("#id_codigo_complementario_suplementario").val(),
                    codigo_complementario : $("#id_codigo_complementario_suplementario option:selected").attr("data-codigocomplementario"),
                    codigo_suplementario : $("#id_codigo_complementario_suplementario option:selected").attr("data-codigosuplementario"),
                    presentacion: $("#presentacion_plaguicida").val(),
                    id_unidad_medida: $("#unidad_medida_plaguicida option:selected").attr("data-idunidadmedida"),
                    unidad_medida : $("#unidad_medida_plaguicida").val(),
                    codigo_tipo_modificacion: tipomodificacion,
                    id_detalle_solicitud_producto: $("#id_detalle_solicitud_producto" + tipomodificacion).val(),
                    tiempo_atencion: $("#presentacion_plaguicida").attr("data-tiempoatencion"),
                    ruta_documento_respaldo: $("#rmodificarAdicionPresentacionPlaguicida").val()

                },
                function (data) {
                    if (data.validacion === 'Fallo') {
                        mostrarMensaje(data.resultado, "FALLO");
                    } else {
                        $("#tAdicionPresentacionPlaguicida tbody").append(data.filaAdicionPresentacionPlaguicida);
                    }
                }, 'json');

        } else {
            $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
        }
    });    
    
    //Funcion que elimina una fila del detalle de adicion presentacion plaguicida
    function fn_eliminarAdicionPresentacionPlaguicida(idAdicionPresentacion) {

		$("#estado").html("").removeClass('alerta');

        $.post("<?php echo URL ?>ModificacionProductoRia/AdicionesPresentacionesPlaguicidas/borrar",
        {
            elementos: idAdicionPresentacion
        },
        function (data) {
            $("#fila" + idAdicionPresentacion).remove();
        });
    }
    
    //Funcion que cambia el estado de una adicion presentacion plaguicida
    function fn_cambiarEstadoAdicionPresentacionPlaguicida (idTablaOrigen) {
    
    var tipomodificacion = $("#agregarAdicionPresentacionPlaguicida").attr("data-tipomodificacion");
	
	$.post("<?php echo URL ?>ModificacionProductoRia/AdicionesPresentacionesPlaguicidas/guardarEstadoAdicionPresentacion",
            {
                id_detalle_solicitud_producto: $("#id_detalle_solicitud_producto" + tipomodificacion).val(),
				id_tabla_origen : idTablaOrigen
            },
            function (data) {
                if (data.estado === 'FALLO') {
                    mostrarMensaje(data.resultado, "FALLO");
                } else {
                    mostrarMensaje(data.resultado, "EXITO");
                    if($("#fila" + idTablaOrigen +" td.activo").length!=0){
                        $("#fila" + idTablaOrigen +" td.activo").addClass('inactivo');
                        $("#fila" + idTablaOrigen +" td.inactivo").removeClass('activo');
                    }else{
                        $("#fila" + idTablaOrigen +" td.inactivo").addClass('activo');
                        $("#fila" + idTablaOrigen +" td.activo").removeClass('inactivo');
                    }
                }
            }, 'json');
    }
    
    ////////////////////////////////////////////////////////
    /////-----Fin adicion presentacion agricola---------////
    ////////////////////////////////////////////////////////

    ////////////////////////////////////////////////////////
    /////---------------Titular producto----------------////
    ////////////////////////////////////////////////////////

    $("#identificador_operador").change(function (event) {
        mostrarMensaje("", "EXITO");
        if ($("#identificador_operador").val() != '') {
            $.post("<?php echo URL ?>ModificacionProductoRia/TitularesProductos/obtenerOperador",
                {
                    identificador_operador: $("#identificador_operador").val()

                }, function (data) {
                    $("#res_cliente").html(data.registroOperador);
                    if (data.estado === 'FALLO') {
                        $("#identificador_operador").val('');
                    }
                    distribuirLineas();
                }, 'json');
        } else {
            mostrarMensaje("Por favor seleccione un valor", "FALLO");
        }
    });

    $("#agregarTitularidadProducto").click(function (event) {
        event.preventDefault();
        mostrarMensaje("", "");
        $(".alertaCombo").removeClass("alertaCombo");
        var error = false;

        var tipomodificacion = $("#agregarTitularidadProducto").attr("data-tipomodificacion");

        $('#fTitularidadProducto .validacion').each(function (i, obj) {
            if (!$.trim($(this).val())) {
                error = true;
                $(this).addClass("alertaCombo");
            }
        });

        if (!error) {

            $("#estado").html("").removeClass('alerta');

            $.post("<?php echo URL ?>ModificacionProductoRia/DetalleSolicitudesProductos/guardarDetalleSolicitud",
                {
                    identificador_operador: $("#identificador_operador").val(),
                    razon_social: $("#razon_social").val(),
                    codigo_tipo_modificacion: tipomodificacion,
                    id_detalle_solicitud_producto: $("#id_detalle_solicitud_producto" + tipomodificacion).val(),
                    tiempo_atencion: $("#identificador_operador").attr("data-tiempoatencion"),
                    ruta_documento_respaldo: $("#rmodificarTitularidadProducto").val()
                },
                function (data) {
                    if (data.validacion === 'Fallo') {
                        mostrarMensaje(data.resultado, "FALLO");
                    } else {
                        mostrarMensaje(data.resultado, "EXITO");
                        $("#tTitularidadProducto tbody").append(data.filaTitularidadProducto);
                    }
                }, 'json');

        } else {
            $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
        }
    });

    //Funcion que elimina una fila del detalle de titularidad producto
    function fn_eliminarTitularidadProducto(idTitularidadProducto) {

        $("#estado").html("").removeClass('alerta');

        $.post("<?php echo URL ?>ModificacionProductoRia/TitularesProductos/borrar",
            {
                elementos: idTitularidadProducto
            },
            function (data) {
                $("#fila" + idTitularidadProducto).remove();
            });
    }

    ////////////////////////////////////////////////////////
    /////------------Fin Titular producto---------------////
    ////////////////////////////////////////////////////////

    ////////////////////////////////////////////////////////
    /////-------------Fabricante/Formulador-------------////
    ////////////////////////////////////////////////////////

    $("#id_pais_origen").change(function (event) {
        mostrarMensaje("", "EXITO");
        if ($("#id_pais_origen").val() !== '') {
            $("#nombre_pais_origen").val($("#id_pais_origen option:selected").text());
        } else {
            mostrarMensaje("Por favor seleccione un valor", "FALLO");
        }
    });

    $("#agregarFabricanteFormulador").click(function (event) {
        event.preventDefault();
        mostrarMensaje("", "");
        $(".alertaCombo").removeClass("alertaCombo");
        var error = false;

        var tipomodificacion = $("#agregarFabricanteFormulador").attr("data-tipomodificacion");

        $('#fFabricanteFormuladorProducto .validacion').each(function (i, obj) {
            if (!$.trim($(this).val())) {
                error = true;
                $(this).addClass("alertaCombo");
            }
        });

        if (!error) {

            $("#estado").html("").removeClass('alerta');

            $.post("<?php echo URL ?>ModificacionProductoRia/DetalleSolicitudesProductos/guardarDetalleSolicitud",
                {
                    tipo: $("#tipo").val(),
                    nombre: $("#nombre_fabricante_formulador").val(),
                    id_pais_origen: $("#id_pais_origen").val(),
                    nombre_pais_origen: $("#nombre_pais_origen").val(),
                    codigo_tipo_modificacion: tipomodificacion,
                    id_detalle_solicitud_producto: $("#id_detalle_solicitud_producto" + tipomodificacion).val(),
                    tiempo_atencion: $("#nombre_fabricante_formulador").attr("data-tiempoatencion"),
                    ruta_documento_respaldo: $("#rmodificarFabricanteFormulador").val()
                },
                function (data) {
                    if (data.validacion === 'Fallo') {
                        mostrarMensaje(data.resultado, "FALLO");
                    } else {
                        mostrarMensaje(data.resultado, "EXITO");
                        $("#tFabricanteFormuladorProducto tbody").append(data.filaFabricanteFormuladorProducto);
                    }
                }, 'json');

        } else {
            $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
        }
    });

    //Funcion que elimina una fila del detalle de titularidad producto
    function fn_eliminarFabricanteFormulador(idFabricanteFormulador) {

        $("#estado").html("").removeClass('alerta');

        $.post("<?php echo URL ?>ModificacionProductoRia/FabricantesFormuladores/borrar",
            {
                elementos: idFabricanteFormulador
            },
            function (data) {
                $("#fila" + idFabricanteFormulador).remove();
            });
    }

    //Funcion que elimina una fila del detalle de titularidad producto
    function fn_cambiarEstadoFabricanteFormulador(idTablaOrigen) {

        var tipomodificacion = $("#agregarFabricanteFormulador").attr("data-tipomodificacion");

        $.post("<?php echo URL ?>ModificacionProductoRia/FabricantesFormuladores/guardarEstadoFabricanteFormulador",
            {
                id_detalle_solicitud_producto: $("#id_detalle_solicitud_producto" + tipomodificacion).val(),
                id_tabla_origen: idTablaOrigen
            },
            function (data) {
                if (data.estado === 'FALLO') {
                    mostrarMensaje(data.resultado, "FALLO");
                } else {
                    mostrarMensaje(data.resultado, "EXITO");
                    if ($("#fila" + idTablaOrigen + " td.activo").length != 0) {
                        $("#fila" + idTablaOrigen + " td.activo").addClass('inactivo');
                        $("#fila" + idTablaOrigen + " td.inactivo").removeClass('activo');
                    } else {
                        $("#fila" + idTablaOrigen + " td.inactivo").addClass('activo');
                        $("#fila" + idTablaOrigen + " td.activo").removeClass('inactivo');
                    }
                }
            }, 'json');
    }

    ////////////////////////////////////////////////////////
    /////----------Fin Fabricante/Formulador------------////
    ////////////////////////////////////////////////////////

    ////////////////////////////////////////////////////////
    /////----------------------Uso----------------------////
    ////////////////////////////////////////////////////////

    $("#id_cultivo").change(function (event) {
        mostrarMensaje("", "EXITO");
        if ($("#id_cultivo option:selected").val() !== '') {
            $("#nombre_cultivo").val($("#id_cultivo option:selected").attr('data-nombre_comun'));
            $("#nombre_cientifico_cultivo").val($("#id_cultivo option:selected").text());
        } else {
            $("#nombre_cultivo").val("");
            $("#nombre_cientifico_cultivo").val("");
            mostrarMensaje("Por favor seleccione un valor", "FALLO");
        }
    });

    $("#id_plaga").change(function (event) {
        mostrarMensaje("", "EXITO");
        if ($("#id_plaga option:selected").val() !== '') {
            $("#nombre_plaga").val($("#id_plaga option:selected").attr('data-nombre_comun'));
            $("#nombre_cientifico_plaga").val($("#id_plaga option:selected").text());
        } else {
            $("#nombre_plaga").val("");
            $("#nombre_cientifico_plaga").val("");
            mostrarMensaje("Por favor seleccione un valor", "FALLO");
        }
    });

    $('#aplicado_a').change(function(event){
        if($("#aplicado_a option:selected").val() == "Especie"){
            $(".UsoEspecie").show();
            $(".UsoInstalacion").hide();
            $(".UsoProducto").hide();
            $("#instalacion").removeClass('validacion');
            $("#id_especie").addClass('validacion');
        }else if($("#aplicado_a option:selected").val() == "Instalacion"){
            $(".UsoEspecie").hide();
            $(".UsoInstalacion").show();
            $(".UsoProducto").hide();
            $("#instalacion").addClass('validacion');
            $("#id_especie").removeClass('validacion');
            $("#instalacion_producto").removeClass('validacion');
        }else if($("#aplicado_a option:selected").val() == "Producto"){
            $(".UsoEspecie").hide();
            $(".UsoInstalacion").hide();
            $(".UsoProducto").show();
            $("#instalacion_producto").addClass('validacion');
            $("#instalacion").removeClass('validacion');
        }else{
            $(".UsoEspecie").hide();
            $(".UsoInstalacion").hide();
            $(".UsoProducto").hide();
        }

        $("#id_especie").val('');
        $("#nombre_especie").val('');
        $("#instalacion").val('');
        $("#id_uso_producto").val('');
        $("#nombre_especie_tipo").val('');
        $("#nombre_especie").val('');
        $("#instalacion_producto").val('');
        distribuirLineas();
    });

    $("#id_uso_producto").change(function (event) {
        mostrarMensaje("", "EXITO");
        if ($("#id_uso_producto option:selected").val() !== '') {
            $("#nombre_uso").val($("#id_uso_producto option:selected").text());
        } else {
            $("#nombre_uso").val("");
            mostrarMensaje("Por favor seleccione un valor", "FALLO");
        }
    });

    $("#id_especie").change(function (event) {
        mostrarMensaje("", "EXITO");
        if ($("#id_especie option:selected").val() !== '') {
            $("#nombre_especie_tipo").val($("#id_especie option:selected").text());
        } else {
            $("#nombre_especie_tipo").val("");
            mostrarMensaje("Por favor seleccione un valor", "FALLO");
        }
    });

    $("#agregarUso").click(function (event) {
        event.preventDefault();
        mostrarMensaje("", "");
        $(".alertaCombo").removeClass("alertaCombo");
        var error = false;

        var tipomodificacion = $("#agregarUso").attr("data-tipomodificacion");

        $('#fUsoProducto .validacion').each(function (i, obj) {
            if (!$.trim($(this).val())) {
                error = true;
                $(this).addClass("alertaCombo");
            }
        });

        if (!error) {

            $("#estado").html("").removeClass('alerta');

            $.post("<?php echo URL ?>ModificacionProductoRia/DetalleSolicitudesProductos/guardarDetalleSolicitud",
                {
                    id_cultivo: $("#id_cultivo").val(),
                    nombre_cultivo: $("#nombre_cultivo").val(),
                    nombre_cientifico_cultivo: $("#nombre_cientifico_cultivo").val(),
                    id_plaga: $("#id_plaga").val(),
                    nombre_plaga: $("#nombre_plaga").val(),
                    nombre_cientifico_plaga: $("#nombre_cientifico_plaga").val(),
                    dosis: $("#dosis").val(),
                    unidad_dosis: $("#unidad_dosis").val(),
                    periodo_carencia: $("#periodo_carencia").val(),
                    gasto_agua: $("#gasto_agua").val(),
                    unidad_gasto_agua: $("#unidad_gasto_agua").val(),
                    codigo_tipo_modificacion: tipomodificacion,
                    id_detalle_solicitud_producto: $("#id_detalle_solicitud_producto" + tipomodificacion).val(),
                    tiempo_atencion: $("#tiempo_atencion").val(),
                    ruta_documento_respaldo: $("#rmodificarUso").val(),
                    id_area: $("#id_area").val(),
                    filas: 0,
                    id_uso_producto: $("#id_uso_producto").val(),
                    nombre_uso: $("#nombre_uso").val(),
                    id_especie: $("#id_especie").val(),
                    nombre_especie_tipo: $("#nombre_especie_tipo").val(),
                    nombre_especie: $("#nombre_especie").val(),
                    aplicado_a: $("#aplicado_a").val(),
                    instalacion: $("#instalacion").val(),
                    instalacion_producto: $("#instalacion_producto").val()
                },

                function (data) {
                    if (data.validacion === 'Fallo') {
                        mostrarMensaje(data.resultado, "FALLO");
                    } else {
                        mostrarMensaje(data.resultado, "EXITO");
                        $("#tUsoProducto tbody").append(data.filaUsoProducto);
                    }
                }, 'json');
        } else {
            $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
        }
    });

     //Funcion que elimina una fila del detalle de usos
     function fn_eliminarUso(idUso) {

         $("#estado").html("").removeClass('alerta');

         $.post("<?php echo URL ?>ModificacionProductoRia/Usos/borrar",
            {
                elementos: idUso
            },
            function (data) {
                $("#fila" + idUso).remove();
            });
    }

    //Funcion que inactiva un registro
    function fn_cambiarEstadoUso(idTablaOrigen) {

        var tipomodificacion = $("#agregarUso").attr("data-tipomodificacion");

        $.post("<?php echo URL ?>ModificacionProductoRia/Usos/guardarEstadoUso",
            {
                id_detalle_solicitud_producto: $("#id_detalle_solicitud_producto" + tipomodificacion).val(),
                id_tabla_origen: idTablaOrigen,
                id_area: $("#id_area").val()
            },
            function (data) {
                if (data.estado === 'FALLO') {
                    mostrarMensaje(data.resultado, "FALLO");
                } else {
                    mostrarMensaje(data.resultado, "EXITO");
                    if($("#fila" + idTablaOrigen +" td.activo").length!=0){
                        $("#fila" + idTablaOrigen +" td.activo").addClass('inactivo');
                        $("#fila" + idTablaOrigen +" td.inactivo").removeClass('activo');
                    }else{
                        $("#fila" + idTablaOrigen +" td.inactivo").addClass('activo');
                        $("#fila" + idTablaOrigen +" td.activo").removeClass('inactivo');
                    }
                }
            }, 'json');
    }

    ////////////////////////////////////////////////////////
    /////---------------------Fin Uso-------------------////
    ////////////////////////////////////////////////////////

    ////////////////////////////////////////////////////////
    /////-------------------Manufacturador--------------////
    ////////////////////////////////////////////////////////

    $("#id_pais_origen").change(function (event) {
        mostrarMensaje("", "EXITO");
        if ($("#id_pais_origen").val() !== '') {
            $("#pais_origen").val($("#id_pais_origen option:selected").text());
        } else {
            mostrarMensaje("Por favor seleccione un valor", "FALLO");
        }
    });

    $("#id_fabricante_formulador").change(function (event) {
        mostrarMensaje("", "EXITO");
        if ($("#id_fabricante_formulador").val() !== '') {
            $("#fabricante_formulador").val($("#id_fabricante_formulador option:selected").text());
        } else {
            mostrarMensaje("Por favor seleccione un valor", "FALLO");
        }
    });

    $("#agregarManufacturador").click(function (event) {
        event.preventDefault();
        mostrarMensaje("", "");
        $(".alertaCombo").removeClass("alertaCombo");
        var error = false;

        var tipomodificacion = $("#agregarManufacturador").attr("data-tipomodificacion");

        $('#fManufacturadorProducto .validacion').each(function (i, obj) {
            if (!$.trim($(this).val())) {
                error = true;
                $(this).addClass("alertaCombo");
            }
        });

        if (!error) {

            $("#estado").html("").removeClass('alerta');

            $.post("<?php echo URL ?>ModificacionProductoRia/DetalleSolicitudesProductos/guardarDetalleSolicitud",
                {
                    id_fabricante_formulador: $("#id_fabricante_formulador").val(),
                    fabricante_formulador: $("#fabricante_formulador").val(),
                    manufacturador: $("#manufacturador").val(),
                    id_pais_origen: $("#id_pais_origen").val(),
                    pais_origen: $("#pais_origen").val(),
                    codigo_tipo_modificacion: tipomodificacion,
                    id_detalle_solicitud_producto: $("#id_detalle_solicitud_producto" + tipomodificacion).val(),
                    tiempo_atencion: $("#manufacturador").attr("data-tiempoatencion"),
                    ruta_documento_respaldo: $("#rmodificarManufacturador").val()
                },
                function (data) {
                    if (data.validacion === 'Fallo') {
                        mostrarMensaje(data.resultado, "FALLO");
                    } else {
                        mostrarMensaje(data.resultado, "EXITO");
                        $("#tManufacturadorProducto tbody").append(data.filaManufacturadorProducto);
                    }
                }, 'json');

        } else {
            $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
        }
    });

    //Funcion que elimina una fila del detalle de titularidad producto
    function fn_eliminarManufacturador(idManufacturador) {

        $("#estado").html("").removeClass('alerta');

        $.post("<?php echo URL ?>ModificacionProductoRia/Manufacturadores/borrar",
            {
                elementos: idManufacturador
            },
            function (data) {
                $("#fila" + idManufacturador).remove();
            });
    }

    function fn_cambiarEstadoManufacturador(idTablaOrigen) {

        var tipomodificacion = $("#agregarManufacturador").attr("data-tipomodificacion");

        $.post("<?php echo URL ?>ModificacionProductoRia/Manufacturadores/guardarEstadoManufacturador",
            {
                id_detalle_solicitud_producto: $("#id_detalle_solicitud_producto" + tipomodificacion).val(),
                id_tabla_origen: idTablaOrigen
            },
            function (data) {
                if (data.estado === 'FALLO') {
                    mostrarMensaje(data.resultado, "FALLO");
                } else {
                    mostrarMensaje(data.resultado, "EXITO");
                    if ($("#fila" + idTablaOrigen + " td.activo").length != 0) {
                        $("#fila" + idTablaOrigen + " td.activo").addClass('inactivo');
                        $("#fila" + idTablaOrigen + " td.inactivo").removeClass('activo');
                    } else {
                        $("#fila" + idTablaOrigen + " td.inactivo").addClass('activo');
                        $("#fila" + idTablaOrigen + " td.activo").removeClass('inactivo');
                    }
                }
            }, 'json');
    }

    ////////////////////////////////////////////////////////
    /////----------------Fin Manufacturador-------------////
    ////////////////////////////////////////////////////////

    ////////////////////////////////////////////////////////
    /////------------------Concentracion----------------////
    ////////////////////////////////////////////////////////

    $("#id_tipo_componente").change(function (event) {
        mostrarMensaje("", "EXITO");
        if ($("#id_tipo_componente").val() !== '') {
            $("#tipo_componente").val($("#id_tipo_componente option:selected").text());
        } else {
            mostrarMensaje("Por favor seleccione un valor", "FALLO");
        }
    });

    $("#id_ingrediente_activo").change(function (event) {
        mostrarMensaje("", "EXITO");
        if ($("#id_ingrediente_activo").val() !== '') {
            $("#ingrediente_activo").val($("#id_ingrediente_activo option:selected").text());
        } else {
            mostrarMensaje("Por favor seleccione un valor", "FALLO");
        }
    });

    $("#agregarComposicion").click(function (event) {
        event.preventDefault();
        mostrarMensaje("", "");
        $(".alertaCombo").removeClass("alertaCombo");
        var error = false;

        var tipomodificacion = $("#agregarComposicion").attr("data-tipomodificacion");

        $('#fComposicionProducto .validacion').each(function (i, obj) {
            if (!$.trim($(this).val())) {
                error = true;
                $(this).addClass("alertaCombo");
            }
        });

        if (!error) {

            $("#estado").html("").removeClass('alerta');

            $.post("<?php echo URL ?>ModificacionProductoRia/DetalleSolicitudesProductos/guardarDetalleSolicitud",
                {
                    id_ingrediente_activo: $("#id_ingrediente_activo").val(),
                    ingrediente_activo: $("#ingrediente_activo").val(),
                    id_tipo_componente: $("#id_tipo_componente").val(),
                    tipo_componente: $("#tipo_componente").val(),
                    concentracion: $("#concentracion").val(),
                    unidad_medida: $("#unidad_medida").val(),
                    codigo_tipo_modificacion: tipomodificacion,
                    id_detalle_solicitud_producto: $("#id_detalle_solicitud_producto" + tipomodificacion).val(),
                    tiempo_atencion: $("#concentracion").attr("data-tiempoatencion"),
                    ruta_documento_respaldo: $("#rmodificarFabricanteFormulador").val()
                },
                function (data) {
                    if (data.validacion === 'Fallo') {
                        mostrarMensaje(data.resultado, "FALLO");
                    } else {
                        mostrarMensaje(data.resultado, "EXITO");
                        $("#tComposicionProducto tbody").append(data.filaComposicionProducto);
                    }
                }, 'json');

        } else {
            $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
        }
    });

    //Funcion que elimina una fila del detalle de composicion producto
    function fn_eliminarComposicion(idComposicion) {

        $("#estado").html("").removeClass('alerta');

        $.post("<?php echo URL ?>ModificacionProductoRia/Composiciones/borrar",
            {
                elementos: idComposicion
            },
            function (data) {
                $("#fila" + idComposicion).remove();
            });
    }

    //Funcion que elimina una fila del detalle de titularidad producto
    function fn_cambiarEstadoComposicion(idTablaOrigen) {

        var tipomodificacion = $("#agregarComposicion").attr("data-tipomodificacion");

        $.post("<?php echo URL ?>ModificacionProductoRia/Composiciones/guardarEstadoComposicion",
            {
                id_detalle_solicitud_producto: $("#id_detalle_solicitud_producto" + tipomodificacion).val(),
                id_tabla_origen: idTablaOrigen
            },
            function (data) {
                if (data.estado === 'FALLO') {
                    mostrarMensaje(data.resultado, "FALLO");
                } else {
                    mostrarMensaje(data.resultado, "EXITO");
                    if ($("#fila" + idTablaOrigen + " td.activo").length != 0) {
                        $("#fila" + idTablaOrigen + " td.activo").addClass('inactivo');
                        $("#fila" + idTablaOrigen + " td.inactivo").removeClass('activo');
                    } else {
                        $("#fila" + idTablaOrigen + " td.inactivo").addClass('activo');
                        $("#fila" + idTablaOrigen + " td.activo").removeClass('inactivo');
                    }
                }
            }, 'json');
    }

    ////////////////////////////////////////////////////////
    /////---------------- Fin Concentracion ------------////
    ////////////////////////////////////////////////////////

    ////////////////////////////////////////////////////////
    /////---------------------Etiquetas-----------------////
    ////////////////////////////////////////////////////////

    $("#agregarEtiqueta").click(function (event) {
        event.preventDefault();
        mostrarMensaje("", "");
        $(".alertaCombo").removeClass("alertaCombo");
        var error = false;

        var tipomodificacion = $("#agregarEtiqueta").attr("data-tipomodificacion");

        $('#fEtiquetaProducto .validacion').each(function (i, obj) {
            if (!$.trim($(this).val())) {
                error = true;
                $(this).addClass("alertaCombo");
            }
        });

        if (!error) {

            $("#estado").html("").removeClass('alerta');

            $.post("<?php echo URL ?>ModificacionProductoRia/DetalleSolicitudesProductos/guardarDetalleSolicitud",
                {
                    ruta_etiqueta_producto: $("#rmodificarEtiquetaRutaEtiqueta").val(),
                    id_solicitud_producto: $("#id_solicitud_producto").val(),
                    codigo_tipo_modificacion: tipomodificacion,
                    id_detalle_solicitud_producto: $("#id_detalle_solicitud_producto" + tipomodificacion).val(),
                    tiempo_atencion: $("#id_solicitud_producto").attr("data-tiempoatencion"),
                    ruta_documento_respaldo: $("#rmodificarEtiqueta").val()
                },
                function (data) {
                    if (data.validacion === 'Fallo') {
                        mostrarMensaje(data.resultado, "FALLO");
                    } else {
                        mostrarMensaje(data.resultado, "EXITO");
                        $("#tEtiquetaProducto tbody").append(data.filaEtiqueta);
                    }
                }, 'json');

        } else {
            $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
        }
    });

    //Funcion que elimina una fila del detalle de composicion producto
    function fn_eliminarEtiqueta(idSolicitudProducto) {

        $("#estado").html("").removeClass('alerta');

        $.post("<?php echo URL ?>ModificacionProductoRia/SolicitudesProductos/eliminarEtiqueta",
            {
                id_solicitud_producto: idSolicitudProducto
            },
            function (data) {
                $("#fila" + idSolicitudProducto).remove();
            });
    }

    ////////////////////////////////////////////////////////
    /////----------------- Fin Etiquetas ---------------////
    ////////////////////////////////////////////////////////

    ////////////////////////////////////////////////////////
    /////--------------Denominacion de venta---'--------////
    ////////////////////////////////////////////////////////

    $("#id_declaracion_venta").change(function (event) {
        mostrarMensaje("", "EXITO");
        if ($("#id_declaracion_venta").val() !== '') {
            $("#declaracion_venta").val($("#id_declaracion_venta option:selected").text());
        } else {
            mostrarMensaje("Por favor seleccione un valor", "FALLO");
        }
    });


    $("#agregarDenominacionVenta").click(function (event) {
        event.preventDefault();
        mostrarMensaje("", "");
        $(".alertaCombo").removeClass("alertaCombo");
        var error = false;

        var tipomodificacion = $("#agregarDenominacionVenta").attr("data-tipomodificacion");

        $('#fDenominacionVentaProducto .validacion').each(function (i, obj) {
            if (!$.trim($(this).val())) {
                error = true;
                $(this).addClass("alertaCombo");
            }
        });
        
        if (!$.trim($("#r" + tipomodificacion).val()) || $("#r" + tipomodificacion).val() == 0) {
            error = true;
            $("#v" + tipomodificacion).addClass("alertaCombo");
        }

        if (!error) {

            $("#estado").html("").removeClass('alerta');

            $.post("<?php echo URL ?>ModificacionProductoRia/DetalleSolicitudesProductos/guardarDetalleSolicitud",
                {
                    id_declaracion_venta: $("#id_declaracion_venta").val(),
                    declaracion_venta: $("#declaracion_venta").val(),
                    codigo_tipo_modificacion: tipomodificacion,
                    id_detalle_solicitud_producto: $("#id_detalle_solicitud_producto" + tipomodificacion).val(),
                    tiempo_atencion: $("#id_declaracion_venta").attr("data-tiempoatencion"),
                    ruta_documento_respaldo: $("#rmodificarManufacturador").val()
                },
                function (data) {
                    if (data.validacion === 'Fallo') {
                        mostrarMensaje(data.resultado, "FALLO");
                    } else {
                        mostrarMensaje(data.resultado, "EXITO");
                        $("#tDenominacionVentaProducto tbody").append(data.filaDenominacionVentaProducto);
                    }
                }, 'json');

        } else {
            $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
        }
    });

    //Funcion que elimina una fila del detalle de titularidad producto
    function fn_eliminarDenominacionVenta(idDenominacionVenta) {

        $("#estado").html("").removeClass('alerta');

        $.post("<?php echo URL ?>ModificacionProductoRia/DenominacionesVentas/borrar",
            {
                elementos: idDenominacionVenta
            },
            function (data) {
                $("#fila" + idDenominacionVenta).remove();
            });
    }

    ////////////////////////////////////////////////////////
    /////-----------Fin Denominacion de venta-----------////
    ////////////////////////////////////////////////////////


    $("#finalizarSolicitud").submit(function (event) {
        event.preventDefault();
        mostrarMensaje("", "");
        $(".alertaCombo").removeClass("alertaCombo");
        var error = false;

        if (!error) {
            var respuesta = JSON.parse(ejecutarJson($(this)).responseText);
        } else {
            $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
        }
    });

</script>
