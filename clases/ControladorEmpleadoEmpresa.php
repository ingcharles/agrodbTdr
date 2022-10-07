<?php

class ControladorEmpleadoEmpresa{

        function listaEmpleadoEmpresa($conexion,$identificacionEmpleado,$nombresEmpleado,$apellidoEmpleado,$operadorVacunacion){
        	$identificacionEmpleado  = $identificacionEmpleado!="" ? "'" . $identificacionEmpleado . "'" : "NULL";
        	$nombresEmpleado = $nombresEmpleado!="" ? "'%" . $nombresEmpleado . "%'" : "NULL";
        	$apellidoEmpleado = $apellidoEmpleado!="" ? "'%" . $apellidoEmpleado . "%'" : "NULL";
        	$res = $conexion->ejecutarConsulta("SELECT
        											em.id_empleado
								        			,case when ope.razon_social = '' then ope.nombre_representante ||' '|| ope.apellido_representante else ope.razon_social end operador_vacunacion
								        			,case when op.razon_social = '' then op.nombre_representante ||' '|| op.apellido_representante else op.razon_social end empleado
								        			,em.estado
								        		FROM
								        			g_usuario.empleados em 
													,g_operadores.operadores op
													,g_operadores.operadores ope
													,g_usuario.empresas emp
								        		WHERE
													em.identificador=op.identificador
								        			and emp.id_empresa=em.id_empresa
								        			and emp.identificador=ope.identificador
								        			and ($identificacionEmpleado is NULL or em.identificador = $identificacionEmpleado)
								        			and ($nombresEmpleado is NULL or op.nombre_representante ilike $nombresEmpleado)
								        			and ($apellidoEmpleado is NULL or  op.apellido_representante ilike $apellidoEmpleado)
        											and emp.identificador = '".$operadorVacunacion."'
								        		ORDER BY 1 DESC;");
        
        			return $res;
        }
        
        public function obtenerEmpleadoEmpresa($conexion, $identificadorEmpleado,$nombresEmpleado){
        	$identificadorEmpleado = $identificadorEmpleado!="" ? "'" . $identificadorEmpleado . "'" : "NULL";
        	$nombresEmpleado = $nombresEmpleado!="" ? "'%" . $nombresEmpleado  . "%'" : "NULL";
        	$res = $conexion->ejecutarConsulta("SELECT
								        			opv.identificador
								        			,case when opv.razon_social = '' then opv.nombre_representante ||' '|| opv.apellido_representante else opv.razon_social end nombres
								        		FROM
								        			g_operadores.operadores opv
        										WHERE
								        			($identificadorEmpleado is NULL or opv.identificador = $identificadorEmpleado)
        											and ($nombresEmpleado is NULL or case when opv.razon_social = '' then coalesce(opv.nombre_representante ||' '|| opv.apellido_representante ) else opv.razon_social end ilike $nombresEmpleado)
        										ORDER BY nombres ASC;");
		return $res;
        }
        
        public function obtenerOperadorEmpresa($conexion, $identificadorEmpresa, $operacion){
          
        	$res = $conexion->ejecutarConsulta("SELECT DISTINCT
											        s.identificador_operador,	
											        o.razon_social
											    FROM
											        g_operadores.operaciones s,
											        g_catalogos.tipos_operacion t,
											        g_operadores.operadores o,
											        g_catalogos.productos p,
											        g_operadores.productos_areas_operacion sa,
											        g_operadores.sitios si,
											        g_operadores.areas a
        										WHERE
        											s.id_tipo_operacion = t.id_tipo_operacion and
											        s.identificador_operador = o.identificador and
											        s.id_operacion = sa.id_operacion and
											        s.id_producto = p.id_producto and
											        sa.id_area = a.id_area and
											        a.id_sitio = si.id_sitio and
											        t.codigo||''|| t.id_area in $operacion and
											        s.estado in ('registrado', 'porCaducar') and
											        s.identificador_operador='".$identificadorEmpresa."'
											      ORDER BY 2 ASC;");
        	return $res;
        }
        

        public function obtenerEmpresa($conexion, $empresa){
        	$res = $conexion->ejecutarConsulta("SELECT id_empresa
       											FROM
        											g_usuario.empresas
        										WHERE
													identificador='".$empresa."' ;");
        	return $res;
        }
        
        public function abrirEmpleado($conexion, $empleado){
            $res = $conexion->ejecutarConsulta("SELECT
								        			em.id_empleado
        											,ope.identificador identificador_operador_vacunacion
								        			,op.identificador identificador_empleado
								        			,case when ope.razon_social = '' then ope.nombre_representante ||' '|| ope.apellido_representante else ope.razon_social end operador_vacunacion
								        			,case when op.razon_social = '' then op.nombre_representante ||' '|| op.apellido_representante else op.razon_social end empleado
								        			,em.estado
								        		FROM
								        			g_usuario.empleados em 
													,g_operadores.operadores op
													,g_operadores.operadores ope
													,g_usuario.empresas emp
											    WHERE
													em.identificador=op.identificador 
													and emp.id_empresa=em.id_empresa 
													and emp.identificador=ope.identificador 
													and em.id_empleado='".$empleado."'; ");
        	return $res;
        }
		
        
        	
        public function actualizarEmpleadoEmpresa($conexion, $empleado, $estado){;
        	$res = $conexion->ejecutarConsulta("UPDATE
								        			g_usuario.empleados
								        		SET
								        			estado = '$estado'
												WHERE
													id_empleado = '$empleado' ;");
        	return $res;
        }
        
        public function abrirRolEmpleado($conexion, $idRolEmpleado){
        	$res = $conexion->ejecutarConsulta("SELECT
        			re.id_rol_empleado
        			,ope.identificador identificador_operador_vacunacion
        			,op.identificador identificador_empleado
        			,case when ope.razon_social = '' then ope.nombre_representante ||' '|| ope.apellido_representante else ope.razon_social end operador_vacunacion
        			,case when op.razon_social = '' then op.nombre_representante ||' '|| op.apellido_representante else op.razon_social end empleado
        			,re.tipo
        			,re.estado
        			,re.usuario_modificacion
        			,re.fecha_modificacion
        			FROM
        			g_usuario.empleados em , g_usuario.roles_empleados re, g_operadores.operadores op, g_operadores.operadores ope, g_usuario.empresas emp
        			WHERE
        			em.id_empleado=re.id_empleado and em.identificador=op.identificador and emp.id_empresa=em.id_empresa and emp.identificador=ope.identificador and re.id_rol_empleado='$idRolEmpleado';");
        	return $res;
        }
        
        public function actualizarRolEmpleado($conexion, $idRolEmpleado, $estado,$usuarioModificacion){
        	$res = $conexion->ejecutarConsulta("UPDATE
								        			g_usuario.roles_empleados
								        		SET
								        			estado = '$estado',
								        			usuario_modificacion='$usuarioModificacion',
								        			fecha_modificacion=now()
												WHERE
													id_empleado = '$idRolEmpleado' ;");
        	return $res;
        }
        
        public function verificarEmpleadoEmpresa($conexion,  $empleado){
        	$res = $conexion->ejecutarConsulta("SELECT
												em.id_empleado, em.estado
											FROM
												g_usuario.empleados em
											WHERE
												em.identificador='".$empleado."'
        										--and em.estado='activo' 
            ;");
        	return $res;
        }
        
        public function guardarEmpleadoEmpresa($conexion,$empresa, $empleado, $estado){
        	
        	$res = $conexion->ejecutarConsulta("INSERT INTO
							        				g_usuario.empleados(id_empresa, identificador, estado)
							        			VALUES
							        			('$empresa', '$empleado','$estado') RETURNING id_empleado;");
			return $res;
        }
        
        public function guardarEmpresa($conexion,$empresa){
        	$res = $conexion->ejecutarConsulta("INSERT INTO
        											g_usuario.empresas(identificador, estado)
        										VALUES
        										('$empresa','activo') RETURNING id_empresa ;");
        	return $res;
        }
        
        public function guardarNuevoRolEmpleado($conexion, $idEmpleado, $rol, $estado){
        	$res = $conexion->ejecutarConsulta("INSERT INTO
        											g_usuario.roles_empleados(id_empleado, tipo, estado)
        										VALUES
        										('$idEmpleado', '$rol', '$estado');");
        	return $res;
        }
     
        public function obtenerIdentificadorEmpresaEmpleado($conexion, $idEmpleado){
        	$res = $conexion->ejecutarConsulta("SELECT
								        			emp.identificador identificador_empresa
								        			,em.identificador identificador_empleado
								        		FROM
								        			g_usuario.empleados em ,  g_usuario.empresas emp
								        		WHERE
								        			emp.id_empresa=em.id_empresa and em.id_empleado='$idEmpleado';");
        	return $res;
        }
        
        public function obtenerIdentificadorEmpresa($conexion, $idEmpresa){
        	$res = $conexion->ejecutarConsulta("SELECT identificador identificador_empresa
       											FROM
        											g_usuario.empresas
        										WHERE
													id_empresa='".$idEmpresa."' ;");
        	return $res;
        }
        
        
        public function obtenerDatosEmpleadoPorIdentificadorEmpleado($conexion, $identificadorEmpleado){;
            $res = $conexion->ejecutarConsulta("SELECT
                                                        id_empleado
                                                        , id_empresa
                                                        , identificador
                                                        , estado
                                                    FROM
                                                        g_usuario.empleados
                                                    WHERE
                                                        identificador = '$identificadorEmpleado';");
            return $res;
        }
        
        public function eliminarRolesEmpleadoPorIdEmpleado($conexion, $idEmpleado){;
            $res = $conexion->ejecutarConsulta("DELETE 
                                            FROM 
                                                g_usuario.roles_empleados
                                            WHERE 
                                                id_empleado = '$idEmpleado' ;");
            return $res;
        }
        
        public function actualizarEmpresaPorIdentificadorEmpleado($conexion, $idEmpresa, $estado, $identificadorEmpleado){;
            $res = $conexion->ejecutarConsulta("UPDATE
    								        			g_usuario.empleados
    								        		SET
                                                        id_empresa = '$idEmpresa'
    								        			, estado = '$estado'
    												WHERE
    													identificador = '$identificadorEmpleado' ;");
            return $res;
        }

        public function obtenerOperadorEmpresaOperacion($conexion, $identificadorEmpresa, $operacion){
            $res = $conexion->ejecutarConsulta("SELECT DISTINCT
											        s.identificador_operador,
											        o.razon_social,
                                                    t.codigo || t.id_area operacion
											    FROM
											        g_operadores.operaciones s,
											        g_catalogos.tipos_operacion t,
											        g_operadores.operadores o,
											        g_catalogos.productos p,
											        g_operadores.productos_areas_operacion sa,
											        g_operadores.sitios si,
											        g_operadores.areas a
        										WHERE
        											s.id_tipo_operacion = t.id_tipo_operacion and
											        s.identificador_operador = o.identificador and
											        s.id_operacion = sa.id_operacion and
											        s.id_producto = p.id_producto and
											        sa.id_area = a.id_area and
											        a.id_sitio = si.id_sitio and
											        t.codigo||''|| t.id_area in $operacion and
											        s.estado in ('registrado', 'porCaducar') and
											        s.identificador_operador='".$identificadorEmpresa."'
											      ORDER BY 2 ASC;");
            return $res;
        }
}
?>