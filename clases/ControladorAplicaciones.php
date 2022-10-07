<?php

class ControladorAplicaciones{
	
	private $rutaAplicacion;
	private $opcionAbrir;
	private $tamanoMaximoDeDescripcion;
    private $descripcionPredeterminada;
	
	
	public function __construct($rutaAplicacion = null,$opcionAbrir = null, $destino = null, $tamanoMaximoDeDescripcion = 45, $descripcionPredeterminada = 'Sin asunto'){
		$this->rutaAplicacion = $rutaAplicacion;
		$this->opcionAbrir = $opcionAbrir;
		$this->destino = $destino == null ? 'detalleItem' : $destino;
		$this->tamanoMaximoDeDescripcion = $tamanoMaximoDeDescripcion;
        $this->descripcionPredeterminada = $descripcionPredeterminada;
		//parent::verificarSesion();
	}
	
	public function imprimirArticulo($idArticulo,$contador,$texto,$nota, $extraClases = '', $extraEstilo = '', $dataRutaAplicacion = null, $paginaDeAbrir = '', $nombreAplicacion=''){
		
	  $pagina = ($paginaDeAbrir == '')? $this->opcionAbrir: $paginaDeAbrir;
	  $dataRutaAplicacion = ($dataRutaAplicacion)? $dataRutaAplicacion: $this->rutaAplicacion;
	  if ($this->tamanoMaximoDeDescripcion != 0) {
			$texto = (strlen($texto) > $this->tamanoMaximoDeDescripcion) ? (mb_substr($texto, 0, $this->tamanoMaximoDeDescripcion, 'UTF-8') . '...') : ((strlen($texto) > 0) ? $texto : $this->descripcionPredeterminada);
	  }
	  
	  return '<article id="'.$idArticulo.'"
	      class="item'. $extraClases .'"
	      data-rutaAplicacion="' . $dataRutaAplicacion . '"
	      data-opcion="' . $pagina . '"
	      ondragstart="drag(event)"
	      draggable="true"
	      data-destino="' . $this->destino . '"
	      data-nombreAplicacion="' .  $nombreAplicacion . '"  
	      style = "' . $extraEstilo . '"
	      >
	     <span class="ordinal">'.$contador.'</span>
	     <span>'.$texto.'</span>
	     <aside>'.$nota.'</aside>
	    </article>';
	 }
	
	public function imprimirMenuDeOpciones($conexion,$idAplicacion,$idUsuario){
		$opciones = '';
		$res = $this->obtenerOpcionesAplicacion($conexion, $idAplicacion,$idUsuario);
		while($fila = pg_fetch_assoc($res)){
			$opciones .= '<a
				href="#" id="' . $fila['estilo'] . '"
				data-destino="areaTrabajo #listadoItems"
				data-rutaAplicacion="' .$this->rutaAplicacion. '"
				data-idOpcion="' .$fila['id_opcion']. '"
				data-opcion="' . $fila['pagina'] . '">' . $fila['nombre_opcion'] . '</a>';
		}
		return $opciones;
	}
	
	public function imprimirMenuDeAcciones($conexion,$idOpcion,$idUsuario){
	  $navegacion = '<nav>';
	  $res = $this->obtenerAccionesPermitidas($conexion, $idOpcion, $idUsuario);
	  while($fila = pg_fetch_assoc($res)){
	   if( $fila['descripcion']!='TODO')
	   $navegacion .= ('<a href="#"
	      id="' . $fila['estilo'] . '"
	      data-destino="detalleItem"
	      data-opcion="' . $fila['pagina'] . '"
	      data-rutaAplicacion="' . $fila['ruta'] . '"
	      >'.(($fila['estilo']=='_seleccionar')?'<div id="cantidadItemsSeleccionados">0</div>':''). $fila['descripcion'] . '</a>');
	  
	  }
	  return $navegacion . '</nav>';
	 }
	
	
	public function obtenerAplicacionesRegistradas ($conexion,$identificador){
				
		$res = $conexion->ejecutarConsulta("select a.id_aplicacion,
													a.nombre,
													a.ruta,
													a.descripcion,
													a.color,
													ar.id_aplicacion, 
													ar.cantidad_notificacion,
													ar.mensaje_notificacion
										 	from 
													g_programas.aplicaciones a,
													g_programas.aplicaciones_registradas ar
											where 
													ar.id_aplicacion = a.id_aplicacion and
													a.estado_aplicacion = 'activo' and
													ar.identificador = '" . $identificador . "'
											order by
													2;");
		return $res;
	}
	
	public function obtenerOpcionesAplicacion ($conexion,$idAplicacion,$idUsuario){
				
		$res = $conexion->ejecutarConsulta("select distinct o.id_opcion,
													o.nombre_opcion,
													o.estilo,
													o.pagina,
													o.orden,
													o.id_flujo,
                                                    o.nivel,
                                                    o.id_padre,
													o.ruta_mvc
										 from g_programas.opciones o,
											g_programas.acciones a,
											g_programas.acciones_perfiles ap,
											g_usuario.usuarios_perfiles up
										where o.id_aplicacion = " . $idAplicacion . " and
												up.identificador = '" . $idUsuario . "' and
												up.id_perfil = ap.id_perfil and
												ap.id_accion = a.id_accion and 
												a.id_opcion = o.id_opcion and
												o.estado_opcion = 'activo'
										order by
											o.orden;");
		
		$res1 = $conexion->ejecutarConsulta("INSERT INTO g_auditoria.ingreso_aplicacion(identificador, id_acceso, tipo_acceso) VALUES ('$idUsuario','$idAplicacion','id_aplicacion');");
		
		return $res;
	}
	
	public function obtenerAccionesPermitidas($conexion,$idOpcion,$idUsuario){
			
		$res = $conexion->ejecutarConsulta("select a.id_accion,
													a.pagina,
													a.estilo,
													a.descripcion,
													apl.ruta
										 from g_programas.acciones a,
											g_programas.acciones_perfiles ap,
											g_usuario.usuarios_perfiles up,
											g_programas.aplicaciones apl
										where up.identificador = '" . $idUsuario . "' and
												up.id_perfil = ap.id_perfil and
												ap.id_accion = a.id_accion and 
												a.id_aplicacion = apl.id_aplicacion and
												a.id_opcion = " . $idOpcion . "
										order by a.orden;");
		
		$res1 = $conexion->ejecutarConsulta("INSERT INTO g_auditoria.ingreso_aplicacion(identificador, id_acceso, tipo_acceso) VALUES ('$idUsuario','$idOpcion','id_opcion');");
		
		return $res;
	}
	
	public function obtenerPerfil($conexion,$idUsuario){
		$res = $conexion->ejecutarConsulta("SELECT
											  perfiles.nombre
											FROM
											  g_usuario.perfiles,
											  g_usuario.usuarios_perfiles
											WHERE
											  perfiles.id_perfil = usuarios_perfiles.id_perfil AND
											  usuarios_perfiles.identificador='".$idUsuario."'");
		return $res;
	}
	
	public function obtenerIdAplicacion ($conexion,$aplicacion){
		
		$res = $conexion->ejecutarConsulta("select 
													*
											 from 
													g_programas.aplicaciones
											where 
													codificacion_aplicacion = '$aplicacion';");
		return $res;
	}
	
	
	public function guardarAplicacionPerfil ($conexion,$aplicacion,$usuario,$cantidad,$notificacion){
	
				
		$res = $conexion->ejecutarConsulta("INSERT INTO 
												g_programas.aplicaciones_registradas
											VALUES ($aplicacion,'$usuario',$cantidad,'$notificacion');");
				return $res;
	}
	
	public function obtenerAplicacionPerfil ($conexion,$aplicacion,$usuario){
		
		$res = $conexion->ejecutarConsulta("SELECT 
												* 
											FROM
												g_programas.aplicaciones_registradas
											WHERE 
												identificador='$usuario' AND
												id_aplicacion = $aplicacion;");
		return $res;
	}
	

	///FUNCIONES AGREGADAS
	public function guardarGestionAplicacion ($conexion,$idAplicacion,$identificador,$cantidad,$notificacion){
	
	
		$res = $conexion->ejecutarConsulta("INSERT INTO g_programas.aplicaciones_registradas  
			 SELECT   $idAplicacion,'$identificador',$cantidad,'$notificacion'   
			  WHERE NOT EXISTS (SELECT id_aplicacion FROM  g_programas.aplicaciones_registradas WHERE identificador = '$identificador' 
			  and id_aplicacion='$idAplicacion' );");
				return $res;
	}
	
	
	public function obtenerIdPerfil($conexion,$codigo){
	
		$res = $conexion->ejecutarConsulta("SELECT
				id_perfil
				FROM
				g_usuario.perfiles
				WHERE
				codificacion_perfil = '$codigo';");
		return $res;
	}
	
	public function guardarGestionPerfil ($conexion,$identificador,$idPerfil){
	
	
		$res = $conexion->ejecutarConsulta("INSERT INTO g_usuario.usuarios_perfiles
				SELECT   '$identificador',$idPerfil
				WHERE NOT EXISTS (SELECT id_perfil FROM  g_usuario.usuarios_perfiles WHERE identificador = '$identificador'
				and id_perfil='$idPerfil' );");
				return $res;
	}
	
	public function eliminarAplicacion($conexion,$identificador,$idAplicacion) {
		
		$consulta="DELETE FROM  g_programas.aplicaciones_registradas 
	                WHERE identificador = '$identificador' and id_aplicacion = '$idAplicacion';";
		
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
		
	}
	
	public function obtenerPerfilFuncionario($conexion, $identificador){
	    
	    $consulta = "SELECT
                        p.codificacion_perfil
					FROM
                        g_usuario.perfiles p
					INNER JOIN g_usuario.usuarios_perfiles up ON up.id_perfil = p.id_perfil
					WHERE
                        up.identificador='" . $identificador . "'
                        AND p.codificacion_perfil IN ('PFL_USUAR_INT', 'PFL_USUAR_CIV_PR');";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
}