<?php
class ControladorAplicacionesPerfiles{

	public function obtenerAplicacionesUsuario ($conexion,$identificador){
		$res = $conexion->ejecutarConsulta("SELECT 
												ar.id_aplicacion,
												ar.cantidad_notificacion,
												ar.mensaje_notificacion, 
												ap.nombre
  											FROM 
												g_programas.aplicaciones_registradas ar, 
												g_programas.aplicaciones ap 
											WHERE 
												ar.id_aplicacion=ap.id_aplicacion and ar.identificador='$identificador' 
											ORDER BY 4;");
		return $res;
	}
		
	public function imprimirLineaAplicacionesUsuario($identificador,$idAplicacion,$nombreAplicacion, $cantidadNotificacion,$mensajeNotificacion){
		return '<tr id="R'. $idAplicacion . '">' .
				'<td>'.$idAplicacion.'</td>' .
				'<td>'.$nombreAplicacion.'</td>' .
				'<td>'.$cantidadNotificacion.'</td>' .
				'<td>'.$mensajeNotificacion.'</td>' .
				'<td>' .
				'<form class="abrir" data-rutaAplicacion="asignarAplicacionPerfil" data-opcion="abrirPerfilUsuario" data-destino="detalleItem" data-accionEnExito="NADA" >' .
				'<input type="hidden" name="identificacionUsuario" value="' . $identificador . '" >' .
				'<input type="hidden" name="idAplicacion" value="' . $idAplicacion . '" >' .
				'<button class="icono" type="submit" ></button>' .
				'</form>' .
				'</td>' .
				'<td>' .
				'<form class="borrar" data-rutaAplicacion="asignarAplicacionPerfil" data-opcion="eliminarAplicacionUsuario">' .
				'<input type="hidden" name="identificacionUsuario" value="' . $identificador . '" >' .
				'<input type="hidden" name="idAplicacion" value="' . $idAplicacion . '" >' .
				'<button type="submit" class="icono"></button>' .
				'</form>' .
				'</td>' .
				'</tr>';
	}
	
	public function obtenerPerfilesUsuario ($conexion,$idAplicacion, $identificador){
		$res = $conexion->ejecutarConsulta("SELECT 
												pr.id_perfil,
												pr.nombre,
												pr.codificacion_perfil
											FROM
												g_usuario.perfiles pr,
												g_usuario.usuarios_perfiles up 
											WHERE
												pr.id_perfil=up.id_perfil and
												pr.id_aplicacion='$idAplicacion' and 
												up.identificador='$identificador'
											ORDER BY 2;");
		return $res;
	}
	
	
	public function imprimirLineaPerfilesUsuario($identificador,$idPerfil,$nombrePerfil){
		return '<tr id="R' . $idPerfil . '">' .
				'<td>'.$idPerfil.'</td>' .
				'<td>'.$nombrePerfil.'</td>' .
				'<td>' .
				'<form class="borrar" data-rutaAplicacion="asignarAplicacionPerfil" data-opcion="eliminarPerfilUsuario">' .
				'<input type="hidden" name="identificacionUsuario" value="' . $identificador . '" >' .
				'<input type="hidden" name="idPerfil" value="' . $idPerfil . '" >' .
				'<button type="submit" class="icono"></button>' .
				'</form>' .
				'</td>' .
				'</tr>';
	}
	
	public function listarAplicaciones ($conexion){
		$res = $conexion->ejecutarConsulta("SELECT 
												*
											FROM 
												g_programas.aplicaciones 
											WHERE 
												estado_aplicacion='activo' 
											ORDER BY 1;");
		return $res;
	}
	
	public function guardarNuevoAplicacionRegistrada ($conexion,$idAplicacion,$identificador,$cantidadNotificacion,$mensajeNotificacion){
		$res = $conexion->ejecutarConsulta("INSERT INTO g_programas.aplicaciones_registradas(
												id_aplicacion, identificador, cantidad_notificacion, mensaje_notificacion)
											VALUES ('$idAplicacion', '$identificador', '$cantidadNotificacion', '$mensajeNotificacion');");
		return $res;
	}
	
	public function eliminarAplicacionRegistrada ($conexion,$idAplicacion,$identificador){
		$res = $conexion->ejecutarConsulta("DELETE FROM 
												g_programas.aplicaciones_registradas
 											WHERE 
												id_aplicacion='$idAplicacion' and 
												identificador='$identificador';");
		return $res;
	}
	
	public function buscarAplicacionUsuario ($conexion,$idAplicacion,$identificador){
		$res = $conexion->ejecutarConsulta("SELECT 
												id_aplicacion
											FROM 
												g_programas.aplicaciones_registradas
											WHERE 
												id_aplicacion='$idAplicacion' and 
												identificador='$identificador';");
		return $res;
	}
	
	public function listarPerfilesXidAplicacion ($conexion, $idAplicacion){
		$res = $conexion->ejecutarConsulta("SELECT 
												id_perfil,
												nombre,
												estado,
												id_aplicacion,
												codificacion_perfil
 											FROM 
												g_usuario.perfiles  
											WHERE
												id_aplicacion=$idAplicacion and estado='1';");
		return $res;
	}
	
	public function buscarPerfilUsuario ($conexion,$idPerfil,$identificador){
		$res = $conexion->ejecutarConsulta("SELECT 
												identificador, id_perfil
									  		FROM 
												g_usuario.usuarios_perfiles 
											WHERE 
												id_perfil='$idPerfil' and  
												identificador='$identificador';");
		return $res;
	}
	
	public function guardarNuevoPefilUsuario ($conexion,$idPerfil,$identificador){
		$res = $conexion->ejecutarConsulta("INSERT INTO g_usuario.usuarios_perfiles(
            									identificador, id_perfil)
  											VALUES ('$identificador',$idPerfil);");
		return $res;
	}
	
	public function eliminarPerfilUsuario ($conexion,$idPerfil,$identificador){
		$res = $conexion->ejecutarConsulta("DELETE FROM 
												g_usuario.usuarios_perfiles
 											WHERE
												identificador='$identificador' and 
												id_perfil='$idPerfil';");
		return $res;
	}
	
	public function buscarPerfilAplicacionesUsuario ($conexion,$idAplicacion,$identificador){
		$res = $conexion->ejecutarConsulta("SELECT 
												upe.identificador,
												upe.id_perfil
											FROM 
												g_usuario.usuarios_perfiles upe,
												g_usuario.perfiles per 
											WHERE 
												upe.id_perfil=per.id_perfil and 
												upe.identificador='$identificador' and 
												per.id_aplicacion=$idAplicacion;");
		return $res;
	}
	
	public function guardarNuevoAplicacion ($conexion,$nombre,$version,$ruta,$descripcion,$color,$codificacion,$estado){
		$res = $conexion->ejecutarConsulta("INSERT INTO g_programas.aplicaciones(
            									nombre, version, ruta, descripcion, color, codificacion_aplicacion, estado_aplicacion)
   											VALUES (
												'$nombre','$version', '$ruta', '$descripcion', '$color', '$codificacion','$estado') RETURNING id_aplicacion;");
		return $res;
	}
	
	public function obtenerDatosAplicacion ($conexion,$idAplicacion){
		$res = $conexion->ejecutarConsulta("SELECT 
												id_aplicacion,
												nombre,
												version,
												ruta,
												descripcion,
												color,
												codificacion_aplicacion, 
										       	estado_aplicacion
										 	FROM 
												g_programas.aplicaciones 
											WHERE 
												id_aplicacion=$idAplicacion;");
		return $res;
	}
	
	public function actualizarAplicacion ($conexion,$idAplicacion,$nombre,$version,$ruta,$descripcion,$color,$codificacion,$estado){
		$res = $conexion->ejecutarConsulta("UPDATE 
												g_programas.aplicaciones
										  	SET  
												nombre='$nombre', version='$version', ruta='$ruta', descripcion='$descripcion', 
										       	color='$color', codificacion_aplicacion='$codificacion', estado_aplicacion='$estado'
											WHERE
												id_aplicacion='$idAplicacion';");
		return $res;
	}
	
	public function buscarOpcionesAplicacion ($conexion,$idAplicacion){
		$res = $conexion->ejecutarConsulta("SELECT 
												*
 											FROM 
												g_programas.opciones 
											WHERE 
												id_aplicacion=$idAplicacion 
											ORDER BY orden;");
		return $res;
	}
	
	public function imprimirLineaOpcionesAplicacion($idOpcion,$nombre, $estilo,$pagina,$orden,$idAplicacion){
		return '<tr id="R' . $idOpcion . '">' .
				'<td>'.$nombre.'</td>' .
				'<td>'.$estilo.'</td>' .
				'<td>'.$pagina.'</td>' .
				'<td>'.$orden.'</td>' .
				'<td>' .
				'<form class="abrir" data-rutaAplicacion="asignarAplicacionPerfil" data-opcion="abrirOpcion" data-destino="detalleItem" data-accionEnExito="NADA" >' .
				'<input type="hidden" name="idAplicacion" value="' . $idAplicacion . '" >' .
				'<input type="hidden" name="idOpcion" value="' . $idOpcion . '" >' .
				'<button class="icono" type="submit" ></button>' .
				'</form>' .
				'</td>' .
				'<td>' .
				'<form class="borrar" data-rutaAplicacion="asignarAplicacionPerfil" data-opcion="eliminarOpcion">' .
				'<input type="hidden" name="idAplicacion" value="' . $idAplicacion . '" >' .
				'<input type="hidden" name="idOpcion" value="' . $idOpcion . '" >' .
				'<button type="submit" class="icono"></button>' .
				'</form>' .
				'</td>' .
				'</tr>';
	}
	
	public function guardarNuevoOpcionAplicacion ($conexion,$idOpcion,$idAplicacion,$nombre,$estilo,$pagina,$orden){
		$res = $conexion->ejecutarConsulta("INSERT INTO g_programas.opciones(
	 											id_opcion,id_aplicacion, nombre_opcion, estilo, pagina, orden)
											VALUES ($idOpcion,
												$idAplicacion,'$nombre','$estilo', '$pagina','$orden') RETURNING id_opcion;");
				return $res;
	}
	
	public function eliminarOpcionAplicacion ($conexion,$idOpcion){
		$res = $conexion->ejecutarConsulta("DELETE FROM 
												g_programas.opciones
											WHERE 
												id_opcion='$idOpcion';");
		return $res;
	}
	
	public function buscarOpcion ($conexion,$idOpcion,$idAplicacion){
		$res = $conexion->ejecutarConsulta("SELECT 
												*
											FROM 
												g_programas.opciones 
											WHERE
												id_opcion='$idOpcion' and
												id_aplicacion='$idAplicacion' ;");
				return $res;
	}
	
	public function buscarAccionesOpcion ($conexion,$idOpcion, $idAplicacion){
		$res = $conexion->ejecutarConsulta("SELECT 
												*
											FROM 
												g_programas.acciones 
											WHERE 
												id_opcion='$idOpcion' and 
												id_aplicacion='$idAplicacion' 
											ORDER BY orden;");
		return $res;
	}
	
	public function imprimirLineaAccionesOpcion($idAccion,$descripcion, $pagina,$estilo, $orden){
		return '<tr id="R' . $idAccion . '">' .
				'<td>'.$estilo.'</td>' .
				'<td>'.$descripcion.'</td>' .
				'<td>'.$pagina.'</td>' .
				'<td>'.$orden.'</td>' .
				'<td>' .
				'<form class="abrir" data-rutaAplicacion="asignarAplicacionPerfil" data-opcion="abrirAccion" data-destino="detalleItem" data-accionEnExito="NADA" >' .
				'<input type="hidden" name="idAccion" value="' . $idAccion . '" >' .
				'<button class="icono" type="submit" ></button>' .
				'</form>' .
				'</td>' .
				'<td>' .
				'<form class="borrar" data-rutaAplicacion="asignarAplicacionPerfil" data-opcion="eliminarAccion">' .
				'<input type="hidden" name="idAccion" value="' . $idAccion . '" >' .
				'<button type="submit" class="icono"></button>' .
				'</form>' .
				'</td>' .
				'</tr>';
	}
	
	public function actualizarOpcion ($conexion,$idOpcion,$nombre,$estilo,$pagina,$orden){
		$res = $conexion->ejecutarConsulta("UPDATE 
												g_programas.opciones
  											SET 
												nombre_opcion='$nombre', estilo='$estilo', pagina='$pagina', orden='$orden'
											WHERE 
												id_opcion='$idOpcion';");
		return $res;
	}
	
	public function guardarNuevoAccion ($conexion,$idAplicacion,$idOpcion,$descripcion,$estilo,$pagina,$orden){
		
		$estilo=$estilo==''?'null':"'$estilo'";
		$pagina=$pagina==''?'null':"'$pagina'";
		$res = $conexion->ejecutarConsulta("INSERT INTO g_programas.acciones(
									            pagina, estilo, id_opcion, descripcion, orden, id_aplicacion)
									    	VALUES (
												$pagina,$estilo, '$idOpcion', '$descripcion', '$orden','$idAplicacion') RETURNING id_accion ;");
		return $res;
	}
	
	public function eliminarAccion ($conexion,$idAccion){
		$res = $conexion->ejecutarConsulta("DELETE FROM 
												g_programas.acciones
											WHERE 
												id_accion='$idAccion';");
		return $res;
	}
	
	public function buscarAccion ($conexion,$idAccion){
		$res = $conexion->ejecutarConsulta("SELECT 
												*
											 FROM 
												g_programas.acciones
											 WHERE
												id_accion='$idAccion';");
		return $res;
	}
	
	public function actualizarAccion ($conexion,$idAccion,$descripcion,$estilo,$pagina,$orden){
		$estilo=$estilo==''?'null':"'$estilo'";
		$pagina=$pagina==''?'null':"'$pagina'";
		$res = $conexion->ejecutarConsulta("UPDATE 
												g_programas.acciones
										   	SET 
												pagina=$pagina, estilo=$estilo, descripcion='$descripcion', orden='$orden'
											WHERE 
												id_accion='$idAccion';");
				return $res;
	}
	
	public function buscarPerfilesAplicacion ($conexion,$idAplicacion){
		$res = $conexion->ejecutarConsulta("SELECT 
												*
											FROM 
												g_usuario.perfiles
											WHERE 
												id_aplicacion=$idAplicacion 
											ORDER BY nombre;");
				return $res;
	}
	
	public function imprimirLineaPerfilesAplicacion($idPerfil,$nombre, $estado,$codificacion,$idAplicacion){
		return '<tr id="R' . $idPerfil . '">' .
				'<td>'.$nombre.'</td>' .
				'<td>'.$estado.'</td>' .
				'<td>'.$codificacion.'</td>' .
				'<td>' .
				'<form class="abrir" data-rutaAplicacion="asignarAplicacionPerfil" data-opcion="abrirPerfil" data-destino="detalleItem" data-accionEnExito="NADA" >' .
				'<input type="hidden" name="idPerfil" value="' . $idPerfil . '" >' .
				'<button class="icono" type="submit" ></button>' .
				'</form>' .
				'</td>' .
				'<td>' .
				'<form class="borrar" data-rutaAplicacion="asignarAplicacionPerfil" data-opcion="eliminarPerfil">' .
				'<input type="hidden" name="idAplicacion" value="' . $idAplicacion . '" >' .
				'<input type="hidden" name="idPerfil" value="' . $idPerfil . '" >' .
				'<button type="submit" class="icono"></button>' .
				'</form>' .
				'</td>' .
				'</tr>';
	}
	
	public function buscarPerfil ($conexion,$idPerfil){
		$res = $conexion->ejecutarConsulta("SELECT 
												*
											FROM 
												g_usuario.perfiles 
											WHERE 
												id_perfil='$idPerfil';");
				return $res;
	}
	
	public function actualizarPerfil ($conexion,$idPerfil,$nombre,$estado,$codificacion){
		$res = $conexion->ejecutarConsulta("UPDATE
												g_usuario.perfiles
										  	SET 
												nombre='$nombre', estado='$estado', codificacion_perfil='$codificacion'
										 	WHERE 
												id_perfil=$idPerfil; ");
		return $res;
	}

	public function buscarAccionesOpcionesXidAplicacion ($conexion, $idAplicacion){
		$res = $conexion->ejecutarConsulta("SELECT 
												op.id_opcion,op.nombre_opcion, ac.id_accion,ac.descripcion 
 											FROM 
												g_programas.opciones op,
												g_programas.acciones ac 
											WHERE 
												op.id_opcion=ac.id_opcion and 
												op.id_aplicacion=$idAplicacion 
											ORDER BY 1 ;");
		return $res;
	}
	
	public function buscarAccionesPerfilesXidAplicacion ($conexion, $idAplicacion,$idPerfil){
		$res = $conexion->ejecutarConsulta("SELECT 
												op.id_opcion,
												op.nombre_opcion,
												ap.id_accion,
												ac.descripcion
  											FROM 
												g_programas.acciones_perfiles ap,
												g_programas.acciones ac,
												g_programas.opciones op  
											WHERE 
 												op.id_opcion=ac.id_opcion and 
												ac.id_accion=ap.id_accion and 
												op.id_aplicacion='$idAplicacion' and
												ap.id_perfil='$idPerfil' 
											ORDER BY 2,3 ;");
		return $res;
	}
	
	public function imprimirLineaAccionesPerfil($idPerfil,$idAccion,$opcion,$accion){
		return '<tr id="R'.$idPerfil.'_'.$idAccion.'">' .
				'<td>'.$opcion.'</td>' .
				'<td>'.$accion.'</td>' .
				'<td style="text-align:center" >' .
				'<form class="borrar" data-rutaAplicacion="asignarAplicacionPerfil" data-opcion="eliminarAccionPerfil">' .
				'<input type="hidden" name="idPerfil" value="' . $idPerfil . '" >' .
				'<input type="hidden" name="idAccion" value="' . $idAccion . '" >' .
				'<button type="submit" class="icono"></button>' .
				'</form>' .
				'</td>' .
				'</tr>';
	}
	
	public function eliminarPerfilAccion ($conexion,$idPerfil,$idAccion){
		$res = $conexion->ejecutarConsulta("DELETE FROM 
												g_programas.acciones_perfiles
											WHERE 
												id_perfil='$idPerfil' and id_accion='$idAccion';");
		return $res;
	}
	
	public function buscarAccionPerfil ($conexion, $idAccion,$idPerfil){
		$res = $conexion->ejecutarConsulta("SELECT 
												id_perfil
											FROM 
												g_programas.acciones_perfiles 
											WHERE 
												id_perfil='$idPerfil' and  id_accion='$idAccion';");
		return $res;
	}
	
	public function guardarNuevoPerfil($conexion,$idAplicacion,$nombre,$estado,$codificacionPerfil ){
		$res = $conexion->ejecutarConsulta("INSERT INTO g_usuario.perfiles(
           										nombre, estado, id_aplicacion, codificacion_perfil)
   											VALUES (
												'$nombre', '$estado', '$idAplicacion', '$codificacionPerfil') RETURNING id_perfil;");
		return $res;
	}
	
	public function guardarNuevoAccionPerfil($conexion,$idPerfil,$idAccion ){
		$res = $conexion->ejecutarConsulta("INSERT INTO	g_programas.acciones_perfiles(
												id_perfil, id_accion)
   											VALUES (
												$idPerfil, $idAccion) RETURNING id_perfil, id_accion;");
		return $res;
	}
	
	public function eliminarPerfil ($conexion,$idPerfil){
		$res = $conexion->ejecutarConsulta("DELETE FROM 
												g_usuario.perfiles
											WHERE 
												id_perfil='$idPerfil';");
		return $res;
	}
	
	public function eliminarAplicacion ($conexion,$idAplicacion){
		$res = $conexion->ejecutarConsulta("DELETE FROM 
												g_programas.aplicaciones
											WHERE 
												id_aplicacion='$idAplicacion';");
		return $res;
	}
	
	public function obtenerSecuencialOpcion ($conexion){
		$res = $conexion->ejecutarConsulta("SELECT MAX(id_opcion)::numeric + 1 as numero
  FROM g_programas.opciones;");
		
		if(pg_fetch_result($res, 0, 'numero') == '')
			$res = 1;
		else
			$res = pg_fetch_result($res, 0, 'numero');
		
		return $res;
		
	}
	
	public function verificarPerfilXusuarioYcodigo($conexion,$identificadorOperador, $codificacion){

		$consulta = "SELECT
					p.nombre, p.codificacion_perfil
			  FROM
					g_usuario.usuarios_perfiles up
					INNER JOIN g_usuario.perfiles p ON up.id_perfil = p.id_perfil					
			  WHERE
					identificador in ('" . $identificadorOperador . "') AND
					p.codificacion_perfil='$codificacion';";

		return  $conexion->ejecutarConsulta($consulta);
	}
	
	
}