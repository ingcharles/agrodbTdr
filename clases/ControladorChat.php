<?php

class ControladorChat{
	
	public function listarContactos($conexion,$identificador){
		
		$consulta="SELECT 
						c.id_contacto, c.contacto, f.fotografia, f.nombre ||' '|| f.apellido nombres, c.estado,
						(case when (SELECT fecha
						FROM 
						g_chat.mensaje_chat mc 
						WHERE 
						(mc.identificador_usuario= c.contacto and mc.contacto='$identificador') or (mc.identificador_usuario='$identificador' and mc.contacto= c.contacto)
						ORDER BY
						mc.fecha desc 
						limit 1) is null then null else (SELECT fecha FROM g_chat.mensaje_chat mc 
						WHERE 
						(mc.identificador_usuario= c.contacto and mc.contacto='$identificador') or (mc.identificador_usuario='$identificador' and mc.contacto= c.contacto)
						ORDER BY mc.fecha desc  limit 1) end),
						fecha_mensaje
					FROM 
						g_chat.contactos c, g_uath.ficha_empleado f 
					WHERE 	
						c.identificador_usuario='$identificador' and
						c.contacto= f.identificador and
						f.estado_empleado= 'activo' and c.estado='1' 
					ORDER BY
							4";

		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	}
	
	
	public function getMessages($conexion,$identificadorUsuario, $identificadorContacto, $incremento, $datoIncremento) {
		$messages = array();
		$res = $conexion->ejecutarConsulta("select *, to_char(fecha,'YYYY/MM/DD HH24:MI') fechauno,us.nombre_usuario as usuario from g_chat.mensaje_chat mc inner join g_usuario.usuarios us on us.identificador=mc.identificador_usuario
				where (mc.identificador_usuario='".$identificadorContacto."' and  mc.contacto='".$identificadorUsuario."') or (mc.identificador_usuario='".$identificadorUsuario."' and mc.contacto='".$identificadorContacto."')
				order by mc.id_mensaje_chat desc
				offset $datoIncremento rows
				fetch next $incremento rows only;
				");
		
		while ($row = pg_fetch_assoc($res)){
			$messages[] = array(message=>$row['mensaje'], username=>$row['nombre_usuario'], sent_on=>$row['fechauno'],identificadorUsuario=>$row['identificador_usuario']);
			
		}
		return $messages;
		
	}
	
	public function mostrarConversaciones($conexion,$identificadorUsuario, $identificadorContacto, $incremento, $datoIncremento){
		
		$consulta= "SELECT 
						mc.id_mensaje_chat, mc.mensaje, mc.identificador_usuario,mc.contacto, mc.fecha, to_char(mc.fecha,'YYYY/MM/DD HH24:MI:SS.US') fechauno, us.nombre_usuario as usuario 
					FROM 
						g_chat.mensaje_chat mc inner join g_usuario.usuarios us on us.identificador=mc.identificador_usuario 
					WHERE 
						(mc.identificador_usuario='".$identificadorContacto."' and  mc.contacto='".$identificadorUsuario."') or (mc.identificador_usuario='".$identificadorUsuario."' and mc.contacto='".$identificadorContacto."')	
					ORDER BY mc.fecha desc
					offset $datoIncremento rows
					fetch next $incremento rows only
					;
					";
		
		$res = $conexion->ejecutarConsulta($consulta);
		return $res;
	}
		
	public function enviarMensaje($conexion,$identificadorUsuario, $mensaje,$identificadorContacto,$tipo) {
			
		$mensaje = mb_substr("$mensaje",0,1024,"UTF8");
		if($tipo=='vc'){
		    $campo='contacto';
		} else{
		    $campo='id_grupo';
		}
		
		$consulta="insert into
							g_chat.mensaje_chat (mensaje, identificador_usuario, $campo)
						values
							( $$$mensaje$$,'$identificadorUsuario','$identificadorContacto')
							returning fecha ;";				
		
		$res = $conexion->ejecutarConsulta($consulta);		
		
		if($tipo=='vc'){
		$consulta="UPDATE g_chat.contactos
				   SET fecha_mensaje='".pg_fetch_result($res, 0, 'fecha')."'
				 WHERE identificador_usuario='$identificadorUsuario'
					   and contacto='$identificadorContacto' returning fecha;";
		} else {
		    $consulta="UPDATE g_chat.contactos_grupo
                   SET fecha_mensaje='".pg_fetch_result($res, 0, 'fecha')."'
                 WHERE
                 id_grupo=$identificadorContacto and identificador='$identificadorUsuario';";
		}
		
		$res = $conexion->ejecutarConsulta($consulta);
		return $res;
	}
	
	public function fechaUltimoMensaje($conexion,$identificadorUsuario,$identificadorContacto, $fecha) {	
		
		$consulta="UPDATE g_chat.contactos
				   SET fecha_mensaje='$fecha'
				   WHERE identificador_usuario='$identificadorUsuario'
				   and contacto='$identificadorContacto' returning fecha;";		
		
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	}
	
	public function obtenerMenajes($conexion,$identificadorUsuario,$identificadorContacto,$fecha){
		
		$identificadorContacto= explode("_",$identificadorContacto);	
		
		
		$consulta="SELECT
					*, to_char(fecha,'YYYY/MM/DD HH24:MI') fechauno, us.nombre_usuario as usuario  
					FROM 
					g_chat.mensaje_chat mc inner join g_usuario.usuarios us on us.identificador=mc.identificador_usuario 
					WHERE 
					(mc.identificador_usuario='$identificadorContacto[1]' and mc.contacto='$identificadorUsuario')
					and mc.fecha > '$fecha'
					ORDER BY mc.fecha asc";

		$res = $conexion->ejecutarConsulta($consulta);
		return $res;
	}
	
	public function obtenerMenajesTodos($conexion, $busqueda){
	
		$consulta="SELECT 
						mc.id_mensaje_chat,mc.mensaje, identificador_usuario as usuario,
						to_char(fecha,'YYYY/MM/DD HH24:MI') fechauno, fecha,id_grupo,
						g_uath.cambia_formato_nombre(fe.nombre,fe.apellido) nombre
					FROM 
						g_chat.mensaje_chat mc 
                        inner join g_uath.ficha_empleado fe on mc.identificador_usuario	 = fe.identificador
					WHERE 	
						$busqueda 
						ORDER BY mc.fecha asc;" ;
		
		$res = $conexion->ejecutarConsulta($consulta);
		return $res;
	}
	
	public function buscarUsuarios($conexion,$nombre,$usuario){
		$consulta="SELECT 
						identificador, nombres, coordinacion, direccion, gestion, nombre_puesto, fotografia 
						,CASE 
				            WHEN (( SELECT cc.estado 
				               FROM g_chat.contactos cc 
				              WHERE cc.identificador_usuario = '$usuario'  
				              AND cc.contacto = identificador 
				              AND cc.estado = '1')) IS NULL THEN 'nuevo' ELSE 'amigo' END AS relacion 
				  FROM  
						g_chat.vista_lista_usuarios 
				 WHERE 
						nombres ilike '%$nombre%'
						AND identificador <> '$usuario'";
		
		$res = $conexion->ejecutarConsulta($consulta);
		return $res;
	}
	
	public function crearListaUsuarios($conexion){
		$consulta="SELECT
						*
					FROM
						g_chat.vista_lista_usuarios
					ORDER BY 2";
		$res = $conexion->ejecutarConsulta($consulta);
		return $res;
	}
	
	public function enviarSolicitud($conexion,$usuario,$contacto){
		$consulta="SELECT 
					id_solicitud, estado
				  FROM 
					g_chat.solicitudes
				  WHERE 
					(identificador_usuario = '$contacto' and identificador_solicitud='$usuario')
					and estado=1
					limit 1";
		
		$res = $conexion->ejecutarConsulta($consulta);
		
		
		if (pg_num_rows($res)<=0)
		{
			$consulta="INSERT INTO
			g_chat.solicitudes(
			identificador_usuario, identificador_solicitud, estado)
			VALUES ('$usuario', '$contacto',1) returning id_solicitud;";
			
			$res = $conexion->ejecutarConsulta($consulta);
			
			return 0;
		} else {
			return $res;
		}	
			
	}
	
	public function obtenerSolicitudes($conexion,$usuario){
		$consulta="SELECT 
						id_solicitud, 
						(case when identificador_usuario='$usuario' then identificador_solicitud else identificador_usuario end) as contacto, 
						(select f.nombre ||' '|| f.apellido nombres from g_uath.ficha_empleado f where f.identificador= (case when identificador_usuario='1722551049' then identificador_solicitud else identificador_usuario end)) as nombres,
						(select fotografia from g_uath.ficha_empleado where identificador= (case when identificador_usuario='$usuario' then identificador_solicitud else identificador_usuario end)) as fotografia,
				        estado,
				        (case   when estado=1 then 'pendiente'
								when estado=2 then 'aceptado'
								when estado=3 then 'rechazado' end) as estado_solicitud,
						(case when identificador_usuario='$usuario' then 'enviado' else 'recibido' end) as recepcion
				  FROM 
						g_chat.solicitudes
				  WHERE
						(identificador_usuario='$usuario' or identificador_solicitud='$usuario')
						and estado =1
				  ORDER BY 7 desc,3";
		
		$res= $conexion->ejecutarConsulta($consulta);
		return $res;
	}
	
	public function obtenerSolicitudesRecibidas($conexion,$usuario){
		$consulta="SELECT 
						s.id_solicitud, s.identificador_usuario, s.identificador_solicitud, f.fotografia,
				        s.estado, f.nombre ||' '|| f.apellido nombres
				  FROM 
						g_chat.solicitudes s, g_uath.ficha_empleado f
				  WHERE
						s.identificador_solicitud='$usuario'
						and s.estado = 1
						and s.identificador_usuario = f.identificador
				  ORDER BY 6";		
		
		$res= $conexion->ejecutarConsulta($consulta);
		return $res;
	}
	
	public function obtenerSolicitudesPorEstado($conexion,$usuario,$estado){
		$consulta="SELECT 
						id_solicitud, 
						(case when identificador_usuario='$usuario' then identificador_solicitud else identificador_usuario end) as contacto, 
						(select f.nombre ||' '|| f.apellido nombres from g_uath.ficha_empleado f where f.identificador= (case when identificador_usuario='1722551049' then identificador_solicitud else identificador_usuario end)) as nombres,
						(select fotografia from g_uath.ficha_empleado where identificador= (case when identificador_usuario='1722551049' then identificador_solicitud else identificador_usuario end)) as fotografia,
				        estado,
				        (case   when estado=1 then 'pendiente'
								when estado=2 then 'aceptado'
								when estado=3 then 'rechazado' end) as estado_solicitud,
						(case when identificador_usuario='$usuario' then 'enviado' else 'recibido' end) as recepcion
				  FROM 
						g_chat.solicitudes
				  WHERE
						(identificador_usuario='$usuario' or identificador_solicitud='$usuario')
						and estado =1
						and estado = $estado
				  ORDER BY 7 desc,3";
		
		$res= $conexion->ejecutarConsulta($consulta);
		return $res;
	}
	
	public function cancelarSolicitud($conexion,$usuario,$contacto){
		
		$consulta="UPDATE 
						g_chat.solicitudes
				   SET 
				       estado=9
				 WHERE 
						identificador_usuario='$usuario'
						and identificador_solicitud='$contacto'
						and estado=1;";
	
		$res = $conexion->ejecutarConsulta($consulta);
		return $res;
	}
	
	public function aceptarSolicitud($conexion,$usuario,$contacto){
		
		$consulta="SELECT 
						id_solicitud, identificador_usuario, identificador_solicitud, estado
					  FROM 
						g_chat.solicitudes
					WHERE
						(identificador_usuario = '$contacto' and identificador_solicitud='$usuario')
						and estado=1
						limit 1;";
		
		$res = $conexion->ejecutarConsulta($consulta);		
		
		if (pg_num_rows($res)>0){
		
			$consulta="UPDATE
							g_chat.solicitudes
						SET
							estado=2
						WHERE
							identificador_usuario='$contacto'
							and identificador_solicitud='$usuario'
							and estado=1; ";
			
			$res = $conexion->ejecutarConsulta($consulta);
					
			$consulta="INSERT INTO
								g_chat.contactos(
								identificador_usuario, contacto, fecha, estado)
						VALUES
								('$usuario', '$contacto', now(), 1),('$contacto', '$usuario', now(), 1) returning id_contacto;";
			
			$res = $conexion->ejecutarConsulta($consulta);		
			return $res;
		} else{
			return 0;
		}
	}
	
	public function rechazarSolicitud($conexion,$contacto,$usuario){
		
		$consulta="UPDATE
						g_chat.solicitudes
					SET
						estado=3
					WHERE
					identificador_usuario='$contacto'
						and identificador_solicitud='$usuario'
						and estado=1;";
		
		$res = $conexion->ejecutarConsulta($consulta);
		return $res;
	}
	
	public function guardarGrupo($conexion, $grupo, $identificador){
	    $consulta="INSERT INTO 
                    	g_chat.grupos(
                            nombre_grupo, administrador)
                        VALUES 
                    	('$grupo', '$identificador') returning id_grupo;";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
	public function guardarContactosGrupo($conexion,$detalle){
	    $consulta="INSERT INTO 
                         g_chat.contactos_grupo(
                         id_grupo, identificador)
                    VALUES 
                        $detalle;";
        
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;	    
	}
	
	public function listarGruposCreados($conexion,$identificador){
	    $consulta="SELECT 
                	id_grupo, nombre_grupo, administrador, fecha_creacion
                  FROM 
                	g_chat.grupos
                 WHERE
                	administrador='$identificador'
                    and estado=1
                ORDER BY 2;";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
	public function eliminarGrupo($conexion,$idGrupo){
	    $consulta="UPDATE 
                        g_chat.grupos
                     SET
                        estado=0   	
                     WHERE 
                    	id_grupo=$idGrupo;";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
	public function grupoPerteneciente($conexion,$identificador,$administrador='no'){
	    if($administrador=='si'){
	        $busqueda="and g.administrador !='$identificador'";
	    }
	    $consulta="SELECT 
                    	g.id_grupo, g.nombre_grupo, g.administrador, g.fecha_creacion, g.estado,
                        (select fecha from g_chat.mensaje_chat where id_grupo = g.id_grupo order by fecha desc limit 1) fecha_mensaje, 
	                    (case when cg.fecha_mensaje != null then cg.fecha_mensaje else '0001-01-01 00:00:00' end) fecha
                      FROM 
                    	g_chat.grupos g, g_chat.contactos_grupo cg
                      WHERE
                    	cg.identificador='$identificador'
                    	and g.id_grupo = cg.id_grupo
                        $busqueda
                        ;";
	    
        $consulta="SELECT
                    	g.id_grupo, g.nombre_grupo, g.administrador, g.fecha_creacion, g.estado,
                        (select fecha from g_chat.mensaje_chat where id_grupo = g.id_grupo order by fecha desc limit 1) fecha,
	                    (select fecha_mensaje from g_chat.contactos_grupo where identificador='$identificador' and id_grupo = g.id_grupo ) fecha_mensaje, g.fecha_modificacion
                      FROM
                    	g_chat.grupos g, g_chat.contactos_grupo cg
                      WHERE
                    	cg.identificador='$identificador'
                    	and g.id_grupo = cg.id_grupo
                        and g.estado=1
                        $busqueda
                        ;";
        
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
	public function salirGrupo($conexion,$identificador,$grupo){
	    $consulta="DELETE FROM 
                    	g_chat.contactos_grupo
                     WHERE 
                    	identificador='$identificador'
                    	and id_grupo=$grupo;";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
	public function mostrarConversacionesGrupos($conexion,$identificadorUsuario, $grupo, $incremento, $datoIncremento){
	    
	    $consulta= "SELECT
						mc.id_mensaje_chat, mc.mensaje, mc.identificador_usuario,mc.contacto, mc.fecha, to_char(mc.fecha,'YYYY/MM/DD HH24:MI:SS.US') fechauno, us.nombre_usuario as usuario,
                        g_uath.cambia_formato_nombre(fe.nombre,fe.apellido) nombre
					FROM
						g_chat.mensaje_chat mc inner join g_usuario.usuarios us on us.identificador=mc.identificador_usuario
                        inner join g_uath.ficha_empleado fe on mc.identificador_usuario	 = fe.identificador
					WHERE
						mc.id_grupo=$grupo
					ORDER BY mc.fecha desc
					offset $datoIncremento rows
					fetch next $incremento rows only
					;
					";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
	public function fechaUltimoMensajeGrupo($conexion,$identificadorUsuario,$grupo, $fecha) {
	    
	    $consulta="UPDATE g_chat.contactos_grupo
                   SET fecha_mensaje='$fecha'
                 WHERE 
                 id_grupo=$grupo and identificador='$identificadorUsuario';";
	    
	    $res = $conexion->ejecutarConsulta($consulta);	    
	    return $res;
	}
	
	public function obtenerEmojis($conexion){
	    $consulta="SELECT 
                        id_emoji, nombre, ruta
                  FROM 
                        g_chat.emojis
                  ORDER BY 2;";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;
                  
	}

	public function listarMiembrosGrupoCreado($conexion, $identificador, $grupo){
	    $consulta="SELECT 
                    	cg.id_grupo, cg.identificador, cg.fecha_mensaje, fe.nombre ||' '|| fe.apellido nombres, fe.fotografia
                      FROM 
                    	g_chat.contactos_grupo cg, g_uath.ficha_empleado fe
                      WHERE
                    	cg.id_grupo=$grupo
                    	and cg.identificador != '$identificador'
                    	and fe.identificador = cg.identificador
                    	and fe.estado_empleado = 'activo'
                    ORDER BY 4;";
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
	public function actualizarGrupo($conexion,$grupo, $nombre){
	    $consulta="UPDATE 
                    	g_chat.grupos
                       SET 
                    	nombre_grupo='$nombre', fecha_modificacion=now()
                     WHERE 
                    	id_grupo=$grupo;";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
	public function elmininarContactosGrupos($conexion,$grupo){
	    $consulta="DELETE FROM 
                	g_chat.contactos_grupo
                 WHERE 
                	id_grupo=$grupo;";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;
	}

}

