<?php

class ControladorUsuarios{
	
	public function verificarUsuario($conexion, $identificador, $perfil=null){
		
		$consulta = '';
	
		if($perfil) {
			   $consulta = "select
			    				u.clave, u.nombre_usuario, u.estado, up.id_perfil, u.codigo_temporal, u.nombre_usuario
			  				 from
			   					 g_usuario.usuarios u,
			   					 g_usuario.usuarios_perfiles up,
		                         g_usuario.perfiles p
						   where
							    u.identificador = '$identificador' and
							    u.identificador = up.identificador and
                                up.id_perfil = p.id_perfil and
                                p.codificacion_perfil = '$perfil';"; //TODO: Usar columna nueva de código
		} else {
			$consulta = "select
							 u.clave, u.nombre_usuario, u.estado, u.codigo_temporal
						 from
							    g_usuario.usuarios u
						  where
							    u.identificador='$identificador';";
		}
			  
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	}
	
	public function obtenerNombresUsuario ($conexion,$identificador){
		$res = $conexion->ejecutarConsulta("select 
													u.nombre_usuario, f.fotografia, f.nombre, f.apellido, f.tipo_empleado, u.validacion_sri
           									from 
													g_usuario.usuarios u left join 
													g_uath.ficha_empleado f on (u.identificador=f.identificador) 
											where 
													u.identificador='$identificador'
													and f.estado_empleado = 'activo';");
		return $res;
	}
	
	public function obtenerUsuariosActivos ($conexion,$lista){
		$res = $conexion->ejecutarConsulta("select u.identificador, 
													u.nombre_usuario, 
													fe.nombre,
													fe.apellido
											from g_usuario.usuarios u, 
													g_uath.ficha_empleado fe
											where u.estado=1 and
													u.identificador = fe.identificador and
													u.identificador not in(".$lista.")
											order by 4,2;");
		return $res;
	}
	
	public function actualizarUsuario($conexion,$identificador,$alias,$clave){
		$res = $conexion->ejecutarConsulta("
											update
												g_usuario.usuarios
											set 
												nombre_usuario = '$alias',
												clave = $clave
											where
												identificador = '$identificador'");
		return $res;
	}
	
		public function obtenerUsuariosXarea ($conexion){
		$res = $conexion->ejecutarConsulta("select  fu.id_area,
													fe.nombre,
													fe.apellido,
													fe.identificador
											from 	g_usuario.usuarios u,
													g_estructura.funcionarios fu,
													g_uath.ficha_empleado fe
											where 	fe.identificador = fu.identificador and
													u.identificador = fe.identificador and
													u.estado=1
													order by 3,2;");
		return $res;
	}
	
	public function obtenerProvinciaUsuario($conexion, $identificador) {
		$res = $conexion->ejecutarConsulta("select 
												l.id_localizacion,
												l.nombre,
												l.codigo
											from
												g_estructura.funcionarios f,
												g_catalogos.localizacion l
											where
												f.id_oficina = l.id_localizacion
												and f.estado = 1
												and f.identificador = '$identificador';");
	
		return $res;
	
	}
	
	public function obtenerPermisoUsuario($conexion, $identificador) {
		$res = $conexion->ejecutarConsulta(" select administrador, identificador
											 from
													g_estructura.funcionarios
											 where
													identificador = '$identificador';");
	
		return $res;
	
	}
	
	public function obtenerAreaUsuario($conexion, $identificador) {
		
		$res = $conexion->ejecutarConsulta("select
												a.id_area,
												a.id_area_padre,		
												a.nombre,
												a.clasificacion,
												a.categoria_area
											from
												g_estructura.funcionarios f,
												g_estructura.area a
											where
												f.id_area = a.id_area and
												f.identificador = '$identificador';");
	
		return $res;
	
	}
	
	public function obtenerAreaUsuarioIE($conexion, $identificador) {
	    
	    $res = $conexion->ejecutarConsulta("select
												a.id_area,
												a.id_area_padre,
												a.nombre,
												a.clasificacion,
												a.categoria_area
											from
												g_uath.datos_contrato dc,
												g_estructura.area a
											where
												dc.id_gestion = a.id_area and
												dc.identificador = '$identificador' and
                                                dc.estado = 1;");
	    
	    return $res;
	    
	}	
	
	public function activarCuenta($conexion,$identificador,$clave){
		$res = $conexion->ejecutarConsulta("
											update
												g_usuario.usuarios
											set
												estado = '1'
											where
												identificador = '$identificador'
												and clave='$clave'");
		return $res;
	}
	
	public function desactivarCuenta($conexion,$identificador){
		$res = $conexion->ejecutarConsulta("
											update
												g_usuario.usuarios
											set
												estado = '3',
                                                observacion_usuario = 'Su cuenta ha sido bloqueada, cantidad de 5 intentos superada en al ingreso del sistema.'
											where
												identificador = '$identificador'");
		return $res;
	}
	
	public function BuscarAutoridadInstitucion($conexion){
		$res = $conexion->ejecutarConsulta("SELECT
													g_uath.cambia_formato_nombre(fe.nombre,fe.apellido) AS nombres_completos
											FROM
												g_estructura.responsables r,
												g_uath.ficha_empleado fe
											WHERE
												r.identificador = fe.identificador
												and r.id_area = 'DE'
												and responsable = 'true'");
		return $res;
	}
	
	/*public function obtenerUsuariosPorProvincia ($conexion, $nombreProvincia){
		$res = $conexion->ejecutarConsulta("select distinct
												fe.nombre,
												fe.apellido,
												fe.identificador
											from 	g_usuario.usuarios u,
												g_uath.datos_contrato dc,
												g_uath.ficha_empleado fe
											where 	dc.identificador = fe.identificador and
												UPPER(dc.provincia) = UPPER('$nombreProvincia') and
												dc.estado=1
												order by 2,1;");
		return $res;
	}*/
	
	public function obtenerProvincia($conexion, $identificador) {
		$res = $conexion->ejecutarConsulta("select
												l.id_localizacion,
												l.nombre
											from
												g_estructura.funcionarios f,
												g_catalogos.localizacion l
											where
												f.id_provincia = l.id_localizacion and
												l.categoria = 1 and
												f.identificador = '$identificador';");
	
		return $res;
	}
	
/*	public function obtenerInspectoresPorProvincia ($conexion, $nombreProvincia, $tipoInspector){
		$res = $conexion->ejecutarConsulta("select distinct
												fe.identificador,
												fe.nombre,
												fe.apellido
												
											from
												g_usuario.usuarios u,
												g_uath.datos_contrato dc,
												g_uath.ficha_empleado fe,
												g_usuario.usuarios_perfiles up
											where
												dc.identificador = fe.identificador and
												UPPER(dc.provincia) = UPPER('$nombreProvincia') and
												dc.estado=1 and
												dc.identificador = up.identificador and
												up.id_perfil = (
												select
													id_perfil
												from
													g_usuario.perfiles
												where
													nombre = 'Inspector $tipoInspector') order by 2,1;");
				return $res;
	}
	*/
	
	public function obtenerUsuariosXareaPerfil ($conexion, $area, $perfil){
				
		$res = $conexion->ejecutarConsulta("select 
												distinct(fe.identificador),
												fe.nombre,
												fe.apellido,
												fu.id_area
											from 	
												g_usuario.usuarios u,
												g_usuario.usuarios_perfiles up,
												g_estructura.funcionarios fu,
												g_uath.ficha_empleado fe
											where 	
												fe.identificador = fu.identificador and
												u.identificador = fe.identificador and
												u.estado=1 and
												fu.id_area in $area and
												u.identificador = up.identificador and
												up.id_perfil = (SELECT 
																	id_perfil 
																FROM
																	g_usuario.perfiles
																WHERE codificacion_perfil  = '$perfil')
											order by 3,2;");
		return $res;
	}
	
	public function obtenerUsuariosPorCodigoPerfil ($conexion, $perfil){
		
		$res = $conexion->ejecutarConsulta("select
												distinct(fe.identificador),
												fe.nombre,
												fe.apellido
											from
												g_usuario.usuarios u,
												g_usuario.usuarios_perfiles up,
												g_uath.ficha_empleado fe
											where
												u.identificador = up.identificador and												
												u.identificador = fe.identificador and
												u.estado=1 and
												up.id_perfil = (SELECT
																	id_perfil
																FROM
																	g_usuario.perfiles
																WHERE codificacion_perfil  = '$perfil')
											order by 3,2;");
		return $res;
	}
	
	public function obtenerUsuariosPorCodigoPerfilProvinciaContrato ($conexion, $perfil, $provincia){
		
		$res = $conexion->ejecutarConsulta("select
												distinct(fe.identificador),
												fe.nombre,
												fe.apellido
											from
												g_usuario.usuarios u,
												g_usuario.usuarios_perfiles up,
												g_uath.ficha_empleado fe,
												g_uath.datos_contrato c
											where
												u.identificador = up.identificador and
												u.identificador = fe.identificador and
												fe.identificador = c.identificador and 
												c.estado = 1 and 
												u.estado=1 and
												upper(c.provincia) IN $provincia and 
												up.id_perfil = (SELECT
																	id_perfil
																FROM
																	g_usuario.perfiles
																WHERE codificacion_perfil  = '$perfil')
											order by 3,2;");
		return $res;
	}
	
	public function crearUsuario($conexion, $ruc, $clave){
		
		$res = $conexion->ejecutarConsulta("INSERT INTO
												g_usuario.usuarios
											VALUES ('$ruc','$ruc','$clave', 3);");
		
		return $res;
	}
	
	public function crearPerfilUsuario($conexion, $ruc, $nombrePerfil){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO
				g_usuario.usuarios_perfiles(identificador, id_perfil)
				SELECT identificador, id_perfil FROM g_usuario.usuarios u ,g_usuario.perfiles p WHERE u.identificador = '$ruc' and p.nombre= '$nombrePerfil';");
			
		return $res;
	}
	
	public function buscarPerfilUsuario($conexion, $identificador, $nombrePerfil) {
		$res = $conexion->ejecutarConsulta("SELECT 
												p.id_perfil
											FROM
												g_usuario.perfiles p,
												g_usuario.usuarios_perfiles up,
												g_usuario.usuarios u
											WHERE
												u.identificador = up.identificador and
												up.id_perfil = p.id_perfil and
												u.identificador = '$identificador' and
												p.nombre = '$nombrePerfil';");
	
		return $res;
	
	}
	
	public function buscarPerfilUsuarioXCodigo($conexion, $identificador, $codigoPerfil) {
		$res = $conexion->ejecutarConsulta("SELECT
												p.id_perfil
											FROM
												g_usuario.perfiles p,
												g_usuario.usuarios_perfiles up,
												g_usuario.usuarios u
											WHERE
												u.identificador = up.identificador and
												up.id_perfil = p.id_perfil and
												u.identificador = '$identificador' and
												p.codificacion_perfil = '$codigoPerfil';");
		
		return $res;
		
	}
	
	public function buscarResponsableArea($conexion, $idArea){
		$res = $conexion->ejecutarConsulta("SELECT
												g_uath.cambia_formato_nombre(fe.nombre,fe.apellido) AS nombres_completos
											FROM
												g_estructura.responsables r,
												g_uath.ficha_empleado fe
											WHERE
												r.identificador = fe.identificador
												and r.id_area = '$idArea'
												and r.estado = 1;");
		return $res;
	}
	
	public function generarCodigoAcceso($longitud){
		$cadena="[^A-Z0-9]";
		return substr(preg_replace ($cadena, "", md5(rand())) .
				preg_replace ($cadena, "", md5(rand())) .
				preg_replace ($cadena, "", md5(rand())),
				0, $longitud);
	}
	
	public function actualizarCodigoTemporal($conexion,$identificador,$codigo,$ipUsuario){
	    		
	    $res = $conexion->ejecutarConsulta("
											UPDATE
												g_usuario.usuarios
											SET
												codigo_temporal = md5('$codigo'),
												fecha_solicitud_codigo = now(),
												ip_modificacion_clave = '$ipUsuario'
											WHERE
												identificador = '$identificador';");
		return $res;
	}
	
	public function verificarCodigoTemporal($conexion,$identificador,$codigo){
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM 
												g_usuario.usuarios
											WHERE
												md5(identificador) = '$identificador' and
												codigo_temporal = md5('$codigo');");
		return $res;
	}
	
	public function resetearClaveUsuario($conexion,$identificador,$clave, $codigo,$ipUsuario){
		$res = $conexion->ejecutarConsulta("
											UPDATE
												g_usuario.usuarios
											SET
												codigo_temporal = 'reseteado',
												clave = md5('$clave'),
												intento = 0,
												fecha_modificacion_clave = now(),
												ip_modificacion_clave = '$ipUsuario'
											WHERE
												md5(identificador) = '$identificador'
												and codigo_temporal = md5('$codigo')");
		return $res;
	}
	
	public function actualizarIntentosCambioClave($conexion,$identificador){
		
		$res = $conexion->ejecutarConsulta("UPDATE
												g_usuario.usuarios
											SET
												intento = (SELECT intento FROM g_usuario.usuarios WHERE md5(identificador) = '$identificador')+1
											WHERE
												md5(identificador) = '$identificador'
											RETURNING intento");
		return $res;
	}
	
	public function buscarUsuarioCifrado($conexion,$identificador){
		
		$res = $conexion->ejecutarConsulta("SELECT 
												*
											FROM
												g_usuario.usuarios
											WHERE
												md5(identificador) = '$identificador';");
				return $res;
	}
	
	public function desactivarCuentaCifrado($conexion,$identificador){
		$res = $conexion->ejecutarConsulta("
											UPDATE
												g_usuario.usuarios
											SET
												estado = '3',
                                                observacion_usuario = 'Su cuenta ha sido bloqueada, cantidad de 3 intentos superada en el reseteo de la clave.'
											WHERE
												md5(identificador) = '$identificador'
				");
		return $res;
	}
	
	function obtenerIPUsuario(){
	
		if (isset($_SERVER["HTTP_CLIENT_IP"])){
			return $_SERVER["HTTP_CLIENT_IP"];
		}elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
			return $_SERVER["HTTP_X_FORWARDED_FOR"];
		}elseif (isset($_SERVER["HTTP_X_FORWARDED"])){
			return $_SERVER["HTTP_X_FORWARDED"];
		}elseif (isset($_SERVER["HTTP_FORWARDED_FOR"])){
			return $_SERVER["HTTP_FORWARDED_FOR"];
		}elseif (isset($_SERVER["HTTP_FORWARDED"])){
			return $_SERVER["HTTP_FORWARDED"];
		}else{
			return $_SERVER["REMOTE_ADDR"];
		}
	}
	
	public function obtenerAccesoUsuario($conexion, $identificador, $aplicacion ) {
		$res = $conexion->ejecutarConsulta(" select
												nf.*
											from
												g_estructura.nivel_funciones nf, g_programas.aplicaciones a
											where
												nf.codificacion_aplicacion = a.codificacion_aplicacion and
												nf.identificador = '$identificador' and
												a.id_aplicacion = $aplicacion;");
	
		return $res;
	
	}
	
	public function obtenerUsuariosValidar ($conexion){
	
		$res = $conexion->ejecutarConsulta("SELECT
								        		*
								        	FROM
								        		g_usuario.usuarios
								        	WHERE
								        		validacion_sri is null
											LIMIT 25;");
	
		return $res;
	}
	
	public function actualizarUsuariosValidar ($conexion,$identificador,$resultado){
	
		$res = $conexion->ejecutarConsulta("UPDATE
								        			g_usuario.usuarios
								        	SET
								        			validacion_sri = '$resultado'
								        	WHERE
								        			identificador = '$identificador';");
	
		return $res;
	}
	
	public function  obtenerCumpleniosAgrocalidad($conexion){
		
		$res = $conexion->ejecutarConsulta("SELECT
												identificador,
												Upper(nombre ||' '||apellido) as nombre_completo,
												mail_personal,
												mail_institucional,
												fecha_nacimiento
											FROM
												g_uath.ficha_empleado
											WHERE
												date_part('day', fecha_nacimiento) = date_part('day', now())
												and date_part('month', fecha_nacimiento) = date_part('month', now())
												and estado_empleado = 'activo'");
		
		return $res;
	}
	
	public function jsonListarUsuariosPorProvincia($conexion, $provincia){
		$res = $conexion->ejecutarConsulta("
											select row_to_json(usuarios)
												from (
													select array_to_json(array_agg(row_to_json(listado)))
													from (select
														u.identificador,
														u.clave,
														u.nombre_usuario,
														fe.nombre,
														fe.apellido,
														'INTERNO'::character varying as tipo_usuario
													from
														g_usuario.usuarios u,
														g_uath.ficha_empleado fe,
														g_uath.datos_contrato dc
													where
														u.identificador = fe.identificador
														and fe.identificador = dc.identificador
														and dc.estado = 1
														and upper(dc.provincia) = upper('$provincia')
													UNION
														select
															'CANTONES' as identificador,
															 nombre as clave,
															'NOMBRE_USUARIO' as nombre_usuario,
															'NOMBRE' as nombre,
															'APELLIDO' as apellido,
														    'INTERNO'::character varying as tipo_usuario
														from
															g_catalogos.localizacion
														where
															categoria = 2
															and id_localizacion_padre = (SELECT id_localizacion FROM  g_catalogos.localizacion WHERE categoria = 1 and UPPER(nombre) = UPPER('$provincia'))
													UNION
																SELECT
																	u.identificador,
																	u.clave,
																	u.nombre_usuario,
																	o.razon_social,
																	o.nombre_representante,
																	'EXTERNO'::character varying as tipo_usuario
																FROM
																	g_usuario.usuarios u 
																	INNER JOIN g_operadores.operadores o ON u.identificador = o.identificador
																	INNER JOIN g_operadores.sitios s ON o.identificador = s.identificador_operador
																	INNER JOIN g_operadores.areas a ON s.id_sitio = a.id_sitio
																	INNER JOIN g_operadores.productos_areas_operacion pao ON a.id_area = pao.id_area 
																	INNER JOIN g_operadores.operaciones op ON pao.id_operacion = op.id_operacion 
																	INNER JOIN g_catalogos.tipos_operacion top ON op.id_tipo_operacion = top.id_tipo_operacion
																WHERE
																	top.codigo IN ('VFT','CFT','CET')
																	and top.id_area = 'SV'
																	and op.estado = 'registrado'
																	and UPPER(s.provincia) = UPPER('$provincia')
													order by
												1
											) as listado)
											as usuarios;");
				$json = pg_fetch_assoc($res);
				return json_decode($json[row_to_json],true);
	}
	
	public function obtenerIdPerfilXAplicacion ($conexion,$aplicacion,$codificacionPerfil){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_usuario.perfiles
											WHERE
												id_aplicacion = '$aplicacion' and
												codificacion_perfil = '$codificacionPerfil';");
		return $res;
	}
	
	public function obtenerPerfilUsuario ($conexion,$perfil,$usuario){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_usuario.usuarios_perfiles
											WHERE
												identificador='$usuario' AND
												id_perfil = $perfil;");
		return $res;
	}
	
	public function guardarPerfil ($conexion,$perfil,$usuario){
		
		$res = $conexion->ejecutarConsulta("INSERT INTO g_usuario.usuarios_perfiles(id_perfil, identificador)												
											VALUES ($perfil,'$usuario');");
		return $res;
	}
	
	public function guardarUsuarioSistema($conexion, $numero, $nombre, $apellido, $tipo='Interno', $mailInstitucional, $mailPersonal)
	{
		$conexion->ejecutarConsulta('begin;');
		try {
			$res = $conexion->ejecutarConsultaLOGS("INSERT INTO
													g_usuario.usuarios
													(identificador, nombre_usuario, clave, estado, intento)
												VALUES ('$numero',
														'$numero',
														md5('$numero'),
														1,
														0);");


			$res = $conexion->ejecutarConsultaLOGS("INSERT INTO
													g_uath.ficha_empleado
												  	(identificador,nombre,apellido, tipo_empleado, mail_institucional, mail_personal)
												VALUES ('$numero',
														'$nombre',
														'$apellido',
														'$tipo',
                                                        '$mailInstitucional',
														'$mailPersonal');");
			
			if($tipo == "Interno"){
				
				$res = $conexion->ejecutarConsultaLOGS("INSERT INTO
						g_usuario.usuarios_perfiles
						(identificador, id_perfil)
						VALUES ('$numero',2),('$numero',73);");
				
				$res = $conexion->ejecutarConsultaLOGS("INSERT INTO
						g_programas.aplicaciones_registradas
						(id_aplicacion, identificador, cantidad_notificacion, mensaje_notificacion)
						VALUES (4,'$numero',0,'notificaciones'),
						(5,'$numero',0,'notificaciones'),
						(19,'$numero',0,'notificaciones'),
						(31,'$numero',0,'notificaciones'),
						(35,'$numero',0,'notificaciones'),
						(52,'$numero',0,'notificaciones'),
						(67,'$numero',0,'notificaciones')
						RETURNING identificador;");
				
			}else{
				
				//TODO: Cambiar perfil dependiendo de la base de datos (Usuario civil, Expediente digital).
				
				$res = $conexion->ejecutarConsulta("INSERT INTO
						g_usuario.usuarios_perfiles
						(identificador, id_perfil)
						VALUES ('$numero',103),('$numero',73);");
				
				$res = $conexion->ejecutarConsulta("INSERT INTO
						g_programas.aplicaciones_registradas
						(id_aplicacion, identificador, cantidad_notificacion, mensaje_notificacion)
						VALUES (35,'$numero',0,'notificaciones')
						RETURNING identificador;");
				
			}
			
			$conexion->ejecutarConsulta('commit;');
		} catch(Exception $e) {
			$conexion->ejecutarConsulta('rollback;');
            throw $e;
		}

		return $res;
	}

	public function buscarUsuarioSistema($conexion, $identificador){

		$res = $conexion->ejecutarConsulta("SELECT
												u.*, fe.*, u.estado AS estado_usuario_sistema

											FROM
												g_usuario.usuarios u, g_uath.ficha_empleado fe

											WHERE
												u.identificador = '$identificador'
												AND u.identificador = fe.identificador;");


		return $res;
	}

	public function modificarEstadoUsuarioSistema($conexion, $identificador, $estado){

		$res = $conexion->ejecutarConsulta("
											UPDATE
												g_usuario.usuarios
											SET
												estado = $estado
											WHERE
												identificador = '$identificador';");

		return $res;
	}
	
	public function listarUsuariosXArea ($conexion, $idArea){
		
		$res = $conexion->ejecutarConsulta("select
												fe.identificador,
												fe.apellido,
												fe.nombre,
												fu.id_area
											FROM
												g_usuario.usuarios u,
												g_estructura.funcionarios fu,
												g_uath.ficha_empleado fe
											WHERE
												fe.identificador = fu.identificador and
												u.identificador = fe.identificador and
												u.estado=1 and
												fu.id_area = '$idArea'
												ORDER BY
												fe.apellido asc;");
				return $res;
	}
	
	public function eliminarPerfilUsuario ($conexion,$identificador,$idPerfil){
		
		$res = $conexion->ejecutarConsulta("DELETE FROM 
												g_usuario.usuarios_perfiles
 											WHERE 
												identificador='$identificador' 
												and id_perfil='$idPerfil';");
		return $res;
	}
	
	public function eliminarAplicacionUsuario ($conexion,$identificador,$idAplicacion){
		
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_programas.aplicaciones_registradas
 											WHERE 
												identificador='$identificador'
												and id_aplicacion='$idAplicacion';");
		return $res;
	}
	
	public function verificarClaveUsuarioIdentificador($conexion, $identificador){
	    
	    $res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_usuario.usuarios u
											WHERE
												u.identificador = '" . $identificador . "'
												and u.clave = md5('".$identificador."')");
	    
	    return $res;
	}	
	
	public function crearPerfilUsuarioXIdPerfil($conexion, $identificador, $idPerfil){

	    $res = $conexion->ejecutarConsulta("INSERT INTO
												g_usuario.usuarios_perfiles
                                            SELECT '$identificador', $idPerfil  
                                            WHERE NOT EXISTS (SELECT 
                                                                    id_perfil 
                                                                FROM  
                                                                    g_usuario.usuarios_perfiles 
                                                                WHERE 
                                                                    identificador = '$identificador'
				                                                    and id_perfil = $idPerfil);");
	    return $res;
	}
	
	public function separarNombreCedula($nombre){
		
		$cadena = explode(" ", $nombre);
		$cantidadCadena = count($cadena);
		
		print_r($cadena);
		
		$datos = array();
		
		switch($cantidadCadena) {
			case 2:
				$nombre = $cadena[1];
				$apellido = $cadena[0];
			break;
			case 3:
				$nombre = $cadena[1].' '.$cadena[2];
				$apellido = $cadena[0];
			break;
			case 4:
				$nombre = $cadena[2].' '.$cadena[3];
				$apellido = $cadena[0].' '.$cadena[1];
			break;
			case 5:
				$nombre = $cadena[2].' '.$cadena[3].' '.$cadena[4];
				$apellido = $cadena[0].' '.$cadena[1];
			break;
			case 6:
				$nombre = $cadena[4].' '.$cadena[5];
				$apellido = $cadena[0].' '.$cadena[1].' '.$cadena[2].' '.$cadena[3];
			break;
			default:
				$nombre = $cadena[2].' '.$cadena[3];
				$apellido = $cadena[0].' '.$cadena[1];
		}
		
		$datos = array('nombre'=> $nombre, 'apellido'=> $apellido);
		
		return $datos;
	}
	
	public function obtenerTipoUsuario($conexion, $identificador){
	    
	    $consulta = "SELECT
						p.codificacion_perfil
						,up.identificador
					FROM
						g_usuario.perfiles p,
						g_usuario.usuarios_perfiles up
					WHERE
						p.id_perfil = up.id_perfil and
						p.codificacion_perfil in ('PFL_USUAR_INT','PFL_USUAR_EXT','PFL_USUAR_CIV_PR') and
						up.identificador = '" . $identificador . "';";
	    
	    return $conexion->ejecutarConsulta($consulta);
	    
	}
	
	public function obtenerDatosEmpleado($conexion, $identificador) {
	    
	    $consulta = "SELECT
						l1.id_localizacion,
						l1.nombre AS nombre_localizacion,
						l1.codigo AS codigo_localizacion,
                        l2.nombre AS nombre_provincia,
                        l2.id_localizacion AS id_provincia                        
					FROM
						g_uath.datos_contrato dc
					INNER JOIN g_catalogos.localizacion l1 ON dc.id_oficina = l1.id_localizacion
                    INNER JOIN g_catalogos.localizacion l2 ON UPPER(UNACCENT(dc.provincia)) = UPPER(UNACCENT(l2.nombre)) and l2.categoria = 1
					WHERE
	                    dc.estado = 1
						and dc.identificador = '$identificador';";
	    
	    return $conexion->ejecutarConsulta($consulta);
	    
	}
	
}