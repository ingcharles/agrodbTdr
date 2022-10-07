<?php

    class ControladorFormularios
    {

        public function guardarFormulario($conexion, $codigoFormulario, $nombreFormulario, $descripcionFormulario, $identificador)
        {
            $res = $conexion->ejecutarConsulta("insert into
												g_inspeccion.formularios(nombre, descripcion, codigo, identificador)
										 	values
												('$nombreFormulario','$descripcionFormulario','$codigoFormulario', '$identificador') 
											returning
												id_formulario;");
            return $res;
        }

        public function abrirFormulario($conexion, $idFormulario)
        {
            $res = $conexion->ejecutarConsulta("select
												*
											from
												g_inspeccion.formularios f
											where
												id_formulario = $idFormulario;");
            return $res;
        }

        public function actualizarFormulario($conexion, $formulario, $nombreFormulario, $codigoFormulario, $descripcionFormulario)
        {
            $res = $conexion->ejecutarConsulta("update
												g_inspeccion.formularios
											set
												nombre = '$nombreFormulario',
												descripcion = '$descripcionFormulario',
												codigo = '$codigoFormulario'
											where
												id_formulario = $formulario;");
            return $res;
        }

        public function ingresarNuevaCategoria($conexion, $formulario, $categoria)
        {
            $res = $conexion->ejecutarConsulta("insert into
												g_inspeccion.categorias(id_formulario,nombre)
											values
												($formulario,'$categoria')
											returning
												id_categoria;");
            return $res;
        }

        public function eliminarCategoria($conexion, $categoria)
        {
            $res = $conexion->ejecutarConsulta("delete from
												g_inspeccion.categorias
											where
												id_categoria = $categoria;");
            return $res;

        }

        public function cargarCategorias($conexion, $formulario)
        {
            $res = $conexion->ejecutarConsulta("select
												*
											from 
												g_inspeccion.categorias c
											where
												id_formulario = $formulario
											order by
												orden");
            return $res;
        }

        public function listarFormularios($conexion)
        {
            $res = $conexion->ejecutarConsulta("select
												*
										 	from
												g_inspeccion.formularios f
											order by
												nombre;");
            return $res;
        }

        public function imprimirLineaCategoria($idCategoria, $nombreCategoria, $idFormulario)
        {
            return '<tr id="R' . $idCategoria . '">' .
            '<td width="100%">' .
            $nombreCategoria .
            '</td>' .
            '<td>' .
            '<form class="bajar" data-rutaAplicacion="general" data-opcion="modificarOrdenamiento" >' .
            '<input type="hidden" name="idRegistro" value="' . $idCategoria . '" >' .
            '<input type="hidden" name="accion" value="BAJAR" >' .
            '<input type="hidden" name="tabla" value="categorias" >' .
            '<button class="icono"></button>' .
            '</form>' .
            '</td>' .
            '<td>' .
            '<form class="subir" data-rutaAplicacion="general" data-opcion="modificarOrdenamiento" >' .
            '<input type="hidden" name="idRegistro" value="' . $idCategoria . '" >' .
            '<input type="hidden" name="accion" value="SUBIR" >' .
            '<input type="hidden" name="tabla" value="categorias" >' .
            '<button class="icono"></button>' .
            '</form>' .
            '</td>' .
            '<td>' .
            '<form class="abrir" data-rutaAplicacion="formularios" data-opcion="abrirCategoria" data-destino="detalleItem" data-accionEnExito="NADA" >' .
            '<input type="hidden" name="categoria" value="' . $idCategoria . '" >' .
            '<button class="icono" type="submit" ></button>' .
            '</form>' .
            '</td>' .
            '<td>' .
            '<form class="borrar" data-rutaAplicacion="formularios" data-opcion="eliminarCategoria">' .
            '<input type="hidden" name="categoria" value="' . $idCategoria . '" >' .
            '<button type="submit" class="icono"></button>' .
            '</form>' .
            '</td>' .
            '</tr>';
        }

        public function imprimirLineaFormulario($idFormularioAsociado, $nombreFormulario)
        {
            return '<tr id="R' . $idFormularioAsociado . '">' .
            '<td width="100%">' .
            $nombreFormulario .
            '</td>' .
            '<td>' .
            '<form class="borrar" data-rutaAplicacion="formularios" data-opcion="eliminarAsignacion">' .
            '<input type="hidden" name="formulario" value="' . $idFormularioAsociado . '" >' .
            '<button type="submit" class="icono"></button>' .
            '</form>' .
            '</td>' .
            '</tr>';
        }

        public function abrirCategoria($conexion, $idCategoria)
        {
            $res = $conexion->ejecutarConsulta("select
												*
											from
												g_inspeccion.categorias c
											where
												id_categoria = $idCategoria;");
            return $res;
        }

        public function actualizarCategoria($conexion, $categoria, $nombre)
        {
            $res = $conexion->ejecutarConsulta("update
												g_inspeccion.categorias
											set
												nombre = '$nombre'
											where
												id_categoria = $categoria;");
            return $res;
        }

        public function cargarPreguntas($conexion, $categoria)
        {
            $res = $conexion->ejecutarConsulta("select
												*
											from
												g_inspeccion.preguntas p
											where
												id_categoria = $categoria
											order by
												orden");
            return $res;
        }

        /*public function guardarPregunta($conexion, $tipoPregunta, $pregunta){
            $res = $conexion->ejecutarConsulta("insert into
                                                    g_inspeccion.preguntas(tipo_pregunta,pregunta)
                                                values
                                                    ('$tipoPregunta','$pregunta')
                                                returning
                                                    id_pregunta;");
                    return $res;
        }*/

        public function ingresarNuevaPregunta($conexion, $formulario, $categoria, $pregunta, $tipoPregunta, $ayuda)
        {
            $res = $conexion->ejecutarConsulta("insert into
												g_inspeccion.preguntas(id_formulario,id_categoria,nombre,tipo_pregunta,ayuda)
											values
												($formulario, $categoria, '$pregunta', $tipoPregunta, '$ayuda')
											returning
												id_pregunta;");
            return $res;
        }

        public function eliminarPregunta($conexion, $pregunta)
        {
            $res = $conexion->ejecutarConsulta("delete from
												g_inspeccion.preguntas
											where
												id_pregunta = $pregunta;");
            return $res;

        }

        public function imprimirLineaPregunta($idPregunta, $nombrePregunta, $idCategoria)
        {
            return '<tr id="R' . $idPregunta . '">' .
            '<td width="100%">' .
            $nombrePregunta .
            '</td>' .
            '<td>' .
            '<form class="bajar" data-rutaAplicacion="general" data-opcion="modificarOrdenamiento" >' .
            '<input type="hidden" name="idRegistro" value="' . $idPregunta . '" >' .
            '<input type="hidden" name="accion" value="BAJAR" >' .
            '<input type="hidden" name="tabla" value="preguntas" >' .
            '<button class="icono"></button>' .
            '</form>' .
            '</td>' .
            '<td>' .
            '<form class="subir" data-rutaAplicacion="general" data-opcion="modificarOrdenamiento" >' .
            '<input type="hidden" name="idRegistro" value="' . $idPregunta . '" >' .
            '<input type="hidden" name="accion" value="SUBIR" >' .
            '<input type="hidden" name="tabla" value="preguntas" >' .
            '<button class="icono"></button>' .
            '</form>' .
            '</td>' .
            '<td>' .
            '<form class="abrir" data-rutaAplicacion="formularios" data-opcion="abrirPregunta" data-destino="detalleItem" data-accionEnExito="NADA" >' .
            '<input type="hidden" name="pregunta" value="' . $idPregunta . '" >' .
            '<button class="icono" type="submit" ></button>' .
            '</form>' .
            '</td>' .
            '<td>' .
            '<form class="borrar" data-rutaAplicacion="formularios" data-opcion="eliminarPregunta">' .
            '<input type="hidden" name="pregunta" value="' . $idPregunta . '" >' .
            '<button type="submit" class="icono"></button>' .
            '</form>' .
            '</td>' .
            '</tr>';
        }

        public function listarCategorias($conexion, $idFormulario)
        {
            $res = $conexion->ejecutarConsulta("select *
											from g_inspeccion.categorias
											where id_formulario=$idFormulario
											order by orden;");
            return $res;
        }

        public function listarPreguntas($conexion, $idFormulario)
        {
            $res = $conexion->ejecutarConsulta("select *
										from g_inspeccion.preguntas
										where id_formulario=$idFormulario
										order by
										orden;");
            return $res;
        }

        public function listarOpciones($conexion, $idFormulario)
        {
            $res = $conexion->ejecutarConsulta("select *
				from g_inspeccion.opciones
				where id_formulario = $idFormulario
				order by
				orden;");
            return $res;
        }

        public function abrirPregunta($conexion, $idPregunta)
        {
            $res = $conexion->ejecutarConsulta("select
												*
											from
												g_inspeccion.preguntas p
											where
												id_pregunta = $idPregunta;");
            return $res;
        }

        public function actualizarPregunta($conexion, $pregunta, $nombre, $ayuda)
        {
            $res = $conexion->ejecutarConsulta("update
				g_inspeccion.preguntas
				set
					nombre = '$nombre',
					ayuda = '$ayuda'
				where
					id_pregunta = $pregunta;");
            return $res;
        }


        public function cargarOpciones($conexion, $pregunta)
        {
            $res = $conexion->ejecutarConsulta("select
												*
											from
												g_inspeccion.opciones o
											where
												id_pregunta = $pregunta
											order by
												opcion");
            return $res;
        }

        public function ingresarNuevaOpcion($conexion, $formulario, $categoria, $pregunta, $opcion, $ponderacion)
        {
            $res = $conexion->ejecutarConsulta("insert into
								g_inspeccion.opciones(id_formulario,id_categoria,id_pregunta,opcion,ponderacion)
							values
								($formulario, $categoria, $pregunta, '$opcion',$ponderacion)
							returning
								id_opcion;");
            return $res;
        }

        public function abrirOpcion($conexion, $idOpcion)
        {
            $res = $conexion->ejecutarConsulta("select
				*
				from
				g_inspeccion.opciones o
				where
				id_opcion = $idOpcion;");
            return $res;
        }

        public function actualizarOpcion($conexion, $idOpcion, $opcion)
        {
            $res = $conexion->ejecutarConsulta("update
				g_inspeccion.opciones
				set
				opcion = '$opcion'
				where
				id_opcion = $idOpcion;");
            return $res;
        }

        public function imprimirLineaOpcion($idOpcion, $nombreOpcion, $ponderacion)
        {
            return '<tr id="R' . $idOpcion . '">' .
            '<td width="100%">' .
            $nombreOpcion .
            ' (' .
            $ponderacion .
            ')</td>' .
            /*'<td>' .
            '<form class="bajar" data-rutaAplicacion="general" data-opcion="modificarOrdenamiento" >' .
            '<input type="hidden" name="idRegistro" value="' . $idOpcion . '" >' .
            '<input type="hidden" name="accion" value="BAJAR" >' .
            '<input type="hidden" name="tabla" value="opciones" >' .
            '<button class="icono"></button>' .
            '</form>' .
            '</td>' .
            '<td>' .
            '<form class="subir" data-rutaAplicacion="general" data-opcion="modificarOrdenamiento" >' .
            '<input type="hidden" name="idRegistro" value="' . $idOpcion . '" >' .
            '<input type="hidden" name="accion" value="SUBIR" >' .
            '<input type="hidden" name="tabla" value="opciones" >' .
            '<button class="icono"></button>' .
            '</form>' .
            '</td>' .*/
            '<td>' .
            '<form class="abrir" data-rutaAplicacion="formularios" data-opcion="abrirOpcion" data-destino="detalleItem" data-accionEnExito="NADA" >' .
            '<input type="hidden" name="opcion" value="' . $idOpcion . '" >' .
            '<button class="icono" type="submit" ></button>' .
            '</form>' .
            '</td>' .
            '<td>' .
            '<form class="borrar" data-rutaAplicacion="formularios" data-opcion="eliminarOpcion">' .
            '<input type="hidden" name="opcion" value="' . $idOpcion . '" >' .
            '<button type="submit" class="icono"></button>' .
            '</form>' .
            '</td>' .
            '</tr>';
        }

        public function eliminarOpcion($conexion, $opcion)
        {
            $res = $conexion->ejecutarConsulta("delete from
				g_inspeccion.opciones
				where
				id_opcion = $opcion;");
            return $res;
        }


        public function obtenerFormulariosAsociados($conexion, $idOperacion)
        {
            $res = $conexion->ejecutarConsulta("
            select
                *
            from
                g_catalogos.tipos_operacion t
            where
                t.id_tipo_operacion = $idOperacion
            order by
                t.nombre");
            return $res;
        }

        public function obtenerFormulariosDisponibles($conexion, $idOperacion)
        {
            $res = $conexion->ejecutarConsulta("
            select
                *
            from
                g_inspeccion.formularios
            where
                id_formulario not in
                    (
                        select
                            distinct id_formulario
                        from
                            g_inspeccion.formularios_asociados
                        where
                            id_tipo_operacion = $idOperacion
		            )
		    order by
		        nombre;");
            return $res;
        }

        /**************************** JSON *********************************/
        //TODO: revisar si esta funcion se va a utilizar
        public function jsonListarOperaciones($conexion, $operador, $provincia)
        {
            /*$res = $conexion->ejecutarConsulta("
			select row_to_json(operaciones)
			from (
				select array_to_json(array_agg(row_to_json(listado)))
				from (
					select
						op.identificador_operador,
						op.id_operacion,
						op.id_tipo_operacion,
						t_o.nombre,
						t_o.codigo,
						t_o.id_area,
						op.estado,
						op.observacion,
						op.nombre_pais,
						op.nombre_producto,
						to_char(op.fecha_creacion, 'dd-mm-yyyy') as fecha,
						(
						    select array_to_json(array_agg(row_to_json(l_a)))
						    from (
						        select
						            a.id_area,
						            a.nombre_area,
						            a.tipo_area,
						            --FALTA CODIGO DE AREA
						            pao.id_producto_area_operacion
						        from
						            g_operadores.productos_areas_operacion pao,
						            g_operadores.areas a
						        where
						            a.id_area = pao.id_area and
						            pao.id_operacion = op.id_operacion
						    ) l_a) as areas
					from
						g_operadores.operaciones op
						,g_catalogos.tipos_operacion t_o
						--,g_inspeccion.formularios f
						--,g_inspeccion.productos_asociados pa
					where
						op.identificador_operador = '$operador'
						and t_o.id_tipo_operacion = op.id_tipo_operacion
						--and op.id_tipo_operacion = pa.id_tipo_operacion
						--and op.id_producto = pa.id_producto
						--and f.id_formulario = pa.id_formulario
					order by
						op.estado
				) as listado
			) as operaciones;");*/

            $res = $conexion->ejecutarConsulta("
                select row_to_json(operaciones)
                from (
                    select array_to_json(array_agg(row_to_json(listado)))
                    from (

                        select
                            distinct op.identificador_operador,
                            ac.id_asignacion_coordinador,
                            ac.identificador_inspector as inspector_asignado,
                            ac.estado as estado_asignacion,
                            to_char(ac.fecha_asignacion, 'dd-mm-yyyy') as fecha_asignacion,
                            op.id_operacion,
                            op.id_tipo_operacion,
                            t_o.nombre as nombre_operacion,
                            t_o.codigo as codigo_operacion,
                            t_o.id_area as area_tecnica,
                            op.estado as estado_operacion,
                            op.observacion,
                            op.nombre_pais,
                            op.nombre_producto,
                            to_char(op.fecha_creacion, 'dd-mm-yyyy') as fecha,
                            (o.identificador || '.' || s.codigo_provincia || s.id_sitio) codigo_sitio
                        from
                             g_operadores.operadores o
                            ,g_operadores.operaciones op left join

                            (
                                select *
                                from g_revision_solicitudes.asignacion_coordinador
                                where estado = 'En curso' and tipo_solicitud = 'Operadores' and tipo_inspector = 'Técnico'
                            ) ac on (op.id_operacion = ac.id_solicitud)
                            ,g_operadores.productos_areas_operacion pao
                            ,g_operadores.areas a
                            ,g_operadores.sitios s
                            ,g_catalogos.tipos_operacion t_o


                        where
                            op.identificador_operador = '$operador'
                            and t_o.id_tipo_operacion = op.id_tipo_operacion
                            and o.identificador = op.identificador_operador
                            and op.id_operacion = pao.id_operacion
                            and pao.id_area = a.id_area
                            and a.id_sitio = s.id_sitio
                            and s.provincia = '$provincia'
                            and op.estado not in ('pago', 'temporal', 'rechazado', 'eliminado')
                        order by
                            op.estado
                            , ac.estado
                            , 15
                    ) as listado
                ) as operaciones;");
            $json = pg_fetch_assoc($res);
            return json_decode($json[row_to_json], true);
        }

        /**V2**/
        public function jsonObtenerOperadoresPorProvincia($conexion, $provincia, $area, $canton)
        {
            $res = $conexion->ejecutarConsulta("
                            select row_to_json(operadores)
                            from (
                                select array_to_json(array_agg(row_to_json(listado)))
                                from (
                                    select
                                        distinct--o.*
                                        o.identificador
                                        ,o.razon_social
                                        ,concat_ws(', ', o.apellido_representante, o.nombre_representante) as representante
                                        ,concat_ws(', ', o.apellido_tecnico, o.nombre_tecnico) as tecnico
                                        ,o.direccion
                                        ,concat_ws(', ', o.parroquia, o.canton, o.provincia) as localizacion
                                        ,concat_ws('; ', o.telefono_uno, o.telefono_dos) as telefonos
                                        ,concat_ws('; ', o.celular_uno, o.celular_dos) as celulares
                                        ,o.fax
                                        ,o.correo
                                        ------------------------------------
                                        ,o.id_saniflores
                                        ,o.gs1
                                        ,o.registro_orquideas
                                        ,o.registro_madera
                                    from
                                        g_operadores.operadores o
                                        ,g_operadores.operaciones op
                                        ,g_operadores.productos_areas_operacion pao
                                        ,g_operadores.areas a
                                        ,g_operadores.sitios s
                                        ,g_catalogos.tipos_operacion tope
                                    where
                                        o.identificador = op.identificador_operador
                                        and op.id_operacion = pao.id_operacion
                                        and pao.id_area = a.id_area
                                        and a.id_sitio = s.id_sitio
                                        and op.id_tipo_operacion = tope.id_tipo_operacion
                                        and tope.id_area = '$area'
                                        and upper(s.provincia) = upper('$provincia')
            							and upper(s.canton) IN $canton
            							and op.id_tipo_operacion not in (28,29,30,31,32,33,38,39)
										and op.estado in ('registrado','registradoObservacion','inspeccion')
                                    order by
                                        2
                        ) as listado)
                    as operadores;");
            $json = pg_fetch_assoc($res);
            return json_decode($json[row_to_json], true);
        }


        public function jsonListarFormulariosDisponibles($conexion, $operaciones)
        {
            /*echo "select row_to_json(formularios)
                            from (
                                    select array_to_json(array_agg(row_to_json(listado)))
                                        from (
                                            select
                                                f.*
                                            from
                                                g_inspeccion.formularios f,
                                                g_inspeccion.formularios_asociados fa
                                            where
                                                fa.id_formulario = f.id_formulario
                                                and fa.id_tipo_operacion in (".substr($operaciones, 0, -1).")
                                            order by
                                                f.nombre
                                    ) as listado
                    ) as formularios;";*/

            $res = $conexion->ejecutarConsulta("
				select row_to_json(formularios)
						from (
								select array_to_json(array_agg(row_to_json(listado)))
									from (
										select
											f.*
										from 
											g_inspeccion.formularios f,
											g_inspeccion.formularios_asociados fa
										where
											fa.id_formulario = f.id_formulario
											and fa.id_tipo_operacion in ($operaciones)
										order by
											f.nombre
								) as listado
				) as formularios;");
            $json = pg_fetch_assoc($res);
            return json_decode($json[row_to_json], true);
        }

        public function jsonListarFormularios($conexion)
        {
            $res = $conexion->ejecutarConsulta("
				select row_to_json(formularios)
						from (
								select array_to_json(array_agg(row_to_json(listado)))
									from (
										select
											distinct f.*
										from
											g_inspeccion.formularios f,
											g_inspeccion.formularios_asociados fa
										where
										    f.id_formulario = fa.id_formulario
										order by
											f.nombre
								) as listado
				) as formularios;");
            $json = pg_fetch_assoc($res);
            return json_decode($json[row_to_json], true);
        }

        public function jsonListarFormulariosPorOperacion($conexion){
            $res = $conexion->ejecutarConsulta("
				select row_to_json(formularios)
						from (
								select array_to_json(array_agg(row_to_json(listado)))
									from (
										select
											fa.id_formulario,
											fa.id_tipo_operacion
										from
											g_inspeccion.formularios_asociados fa
								) as listado
				) as formularios;");
            $json = pg_fetch_assoc($res);
            return json_decode($json[row_to_json], true);
        }

       /* public function jsonListarPreguntasPorCategoria($conexion){
            $res = $conexion->ejecutarConsulta("
				select row_to_json(formularios)
						from (
								select array_to_json(array_agg(row_to_json(listado)))
									from (
										select
											pre.*
										from
											g_inspeccion.preguntas fa
								) as listado
				) as formularios;");
            $json = pg_fetch_assoc($res);
            return json_decode($json[row_to_json], true);
        }*/

        public function jsonListarCategoriasPorFormulario($conexion)
        {
            $res = $conexion->ejecutarConsulta("
				select row_to_json(categorias)
						from (
								select array_to_json(array_agg(row_to_json(listado)))
									from (
										select
                                           distinct c.*
                                        from
                                            g_inspeccion.categorias c,
                                            g_inspeccion.formularios_asociados fa
                                        where
                                            c.id_formulario = fa.id_formulario
                                        order by
                                            c.id_formulario,
                                            c.orden
								) as listado
				) as categorias;");
            $json = pg_fetch_assoc($res);
            return json_decode($json[row_to_json], true);
        }

        public function jsonListarPreguntasPorCategoria($conexion)
        {
            $res = $conexion->ejecutarConsulta("
				select row_to_json(preguntas)
						from (
								select array_to_json(array_agg(row_to_json(listado)))
									from (
										select
                                            distinct p.*
                                        from
                                            g_inspeccion.formularios_asociados fa,
                                            g_inspeccion.preguntas p
                                        where
                                            p.id_formulario = fa.id_formulario
                                        order by
                                            p.id_formulario,
                                            p.id_categoria,
                                            p.orden
								) as listado
				) as preguntas;");
            $json = pg_fetch_assoc($res);
            return json_decode($json[row_to_json], true);
        }

        public function jsonListarOpcionesPorPregunta($conexion)
        {
            $res = $conexion->ejecutarConsulta("
				select row_to_json(opciones)
						from (
								select array_to_json(array_agg(row_to_json(listado)))
									from (
										select
                                            distinct o.*
                                        from
                                            g_inspeccion.formularios_asociados fa,
                                            g_inspeccion.opciones o
                                        where
                                            o.id_formulario = fa.id_formulario
                                        order by
                                            o.id_formulario,
                                            o.id_categoria,
                                            o.id_pregunta,
                                            o.orden
								) as listado
				) as opciones;");
            $json = pg_fetch_assoc($res);
            return json_decode($json[row_to_json], true);
        }

        public function cargarFormularios($conexion, $idOperacion)
        {
            $res = $conexion->ejecutarConsulta("
            select
                *
            from
                g_inspeccion.formularios_asociados fa,
                g_inspeccion.formularios f
            where
                fa.id_tipo_operacion = $idOperacion
                and fa.id_formulario = f.id_formulario
            order by
                f.nombre;");
            return $res;
        }

        public function asignarFormulario($conexion, $operacion, $formulario)
        {
            $res = $conexion->ejecutarConsulta("insert into
												g_inspeccion.formularios_asociados(id_tipo_operacion, id_formulario)
										 	values
												($operacion, $formulario)
											returning
												id_formulario_asociado;");
            return $res;
        }

        public function eliminarFormularioAsociado($conexion, $formulario_asociado)
        {
            $res = $conexion->ejecutarConsulta("delete from g_inspeccion.formularios_asociados
                                                where id_formulario_asociado = $formulario_asociado;");
            return $res;
        }


    }