<?php
class ControladorServiciosInformacionTecnica{

	public function abrirRequerimientoRevisionIngreso($conexion,$idRequerimiento){
		$res = $conexion->ejecutarConsulta("SELECT 
												id_requerimiento, nombre, descripcion, estado, to_char(fecha_registro,'YYYY-MM-DD') fecha_registro
											FROM 
												g_catalogos.requerimiento_revision_ingreso 
											WHERE 
												id_requerimiento=$idRequerimiento;");
		return $res;
	}
	
	public function imprimirLineaRequerimientoElemento($idRequerimientoElemento,$nombre,$descripcion,$usuarioResponsable){
		return '<tr id="R' . $idRequerimientoElemento . '">' .
					'<td width="47%">'.$nombre.'</td>' .
					'<td width="47%">'.$descripcion.'</td>' .
					'<td>' .
					'<form class="borrar" data-rutaAplicacion="serviciosInformacionTecnica" data-opcion="eliminarRequerimientoElementosSAA">' .
					'<input type="hidden" name="usuarioResponsable" value="' . $usuarioResponsable . '" >' .
					'<input type="hidden" name="idRequerimientoElemento" value="' . $idRequerimientoElemento . '" >' .
					'<button type="submit" class="icono" ></button>' .
					'</form>' .
					'</td>' .
				'</tr>';
	}
	
	public function listaRequerimientoElemento($conexion,$idRequerimiento){
		$res = $conexion->ejecutarConsulta("SELECT 
												id_requerimiento_elemento, id_requerimiento, nombre, descripcion, estado, fecha_registro
											FROM 
												g_catalogos.requerimiento_elementos 
											WHERE 
												id_requerimiento='$idRequerimiento' and estado='activo';");
		return $res;
	}
	
	public function actualizarZonasPaises($conexion,$idZona,$usuarioResponsable){
		$conexion->ejecutarConsulta("UPDATE 
										g_catalogos.zonas
									   SET  
										estado='eliminado', fecha_registro='now()', usuario_responsable='$usuarioResponsable'
									 WHERE id_zona='$idZona' and estado!='eliminado';");

		$conexion->ejecutarConsulta("UPDATE
										g_catalogos.paises_zonas
									SET 
										estado='eliminado', fecha_registro='now()', usuario_responsable='$usuarioResponsable'
									WHERE 
										id_zona='$idZona' and estado!='eliminado';");
	}
	
	public function actualizarEstadoRequerimientoElemento($conexion,$idRequerimientoElemento,$usuarioResponsable){
		$conexion->ejecutarConsulta("UPDATE 
										g_catalogos.requerimiento_elementos
									SET
										estado='eliminado', fecha_registro='now()', usuario_responsable='$usuarioResponsable'
									WHERE 
										id_requerimiento_elemento='$idRequerimientoElemento';");
	}
	
	
	public function actualizarRequerimientoElemento($conexion,$idRequerimiento,$nombre,$descripcion,$usuarioResponsable){
		$conexion->ejecutarConsulta("UPDATE 
										g_catalogos.requerimiento_revision_ingreso
   									SET 
										nombre='$nombre', descripcion='$descripcion', fecha_registro='now()', usuario_responsable='$usuarioResponsable'
 									WHERE 
										id_requerimiento='$idRequerimiento';");
	}
	
	public function actualizarRequerimiento($conexion,$idRequerimiento,$usuarioResponsable){
		$conexion->ejecutarConsulta("UPDATE 
										g_catalogos.requerimiento_revision_ingreso
									SET 
										estado='eliminado', fecha_registro='now()', usuario_responsable='$usuarioResponsable'
									WHERE 
										id_requerimiento='$idRequerimiento' and estado!='eliminado';");
		
		$conexion->ejecutarConsulta("UPDATE
										g_catalogos.requerimiento_elementos
									SET
										estado='eliminado', fecha_registro='now()', usuario_responsable='$usuarioResponsable'
									WHERE
										id_requerimiento='$idRequerimiento' and estado!='eliminado';");
		
	}
		
	public function abrirEnfermedadAnimal($conexion,$idEnfermedad){
		$res = $conexion->ejecutarConsulta("SELECT 
												id_enfermedad, nombre, descripcion,observacion, estado, to_char(fecha_registro,'YYYY-MM-DD') fecha_registro
											FROM 
												g_catalogos.enfermedades_animales 
											WHERE
												id_enfermedad=$idEnfermedad;");
		return $res;
	}
	
	public function listaEnfermedadProducto($conexion,$idEnfermedad){
		$res = $conexion->ejecutarConsulta("SELECT 
												id_enfermedad_producto, id_producto, id_subtipo_producto, id_tipo_producto, estado, fecha_registro, id_enfermedad
 											FROM 
												g_catalogos.enfermedad_animal_producto
											WHERE 
												id_enfermedad='$idEnfermedad' and estado='activo';");
		return $res;
	}
	
	public function listaEnfermedadNombreProducto($conexion,$idEnfermedad){
		$res = $conexion->ejecutarConsulta("SELECT 
												eap.id_enfermedad_producto, eap.id_producto, eap.id_subtipo_producto, eap.id_tipo_producto,
												eap.estado, eap.fecha_registro, eap.id_enfermedad, pr.nombre_comun nombre_producto,pr.partida_arancelaria
											FROM 
												g_catalogos.enfermedad_animal_producto eap , g_catalogos.productos pr
											WHERE 
												eap.id_producto=pr.id_producto and id_enfermedad='$idEnfermedad' and eap.estado='activo';");
		return $res;
	}
	
	public function imprimirLineaEnfermedadProducto($idEnfermedadProducto,$nombre,$usuarioResponsable,$partidaArancelaria){
		return '<tr id="R' . $idEnfermedadProducto . '">' .
				'<td width="60%">'.$nombre.'</td>' .
				'<td>'.$partidaArancelaria.'</td>' .
				'<td>' .
				'<form class="borrar" data-rutaAplicacion="serviciosInformacionTecnica" data-opcion="eliminarEnfermedadProductoSAA">' .
				'<input type="hidden" name="usuarioResponsable" value="' . $usuarioResponsable . '" >' .
				'<input type="hidden" name="idEnfermedadProducto" value="' . $idEnfermedadProducto . '" >' .
				'<center><button type="submit" class="icono"></button></center>' .
				'</form>' .
				'</td>' .
				'</tr>';
	}
	
	public function actualizarEstadoEnfermedadAnimal($conexion,$idEnfermedad,$usuarioResponsable){
		$conexion->ejecutarConsulta("UPDATE 
										g_catalogos.enfermedades_animales
									SET
										estado='eliminado', fecha_registro='now()', usuario_responsable='$usuarioResponsable'
									WHERE 
										id_enfermedad='$idEnfermedad' and estado!='eliminado';");
		
		$conexion->ejecutarConsulta("UPDATE 
										g_catalogos.enfermedad_animal_producto
									SET
										estado='eliminado', fecha_registro='now()', usuario_responsable='$usuarioResponsable'
									WHERE 
										id_enfermedad='$idEnfermedad' and estado!='eliminado';");
	}
	
	public function actualizarEstadoEnfermedadExotica($conexion,$idEnfermedadExotica,$usuarioResponsable){
		$conexion->ejecutarConsulta("UPDATE 
										g_servicios_informacion_tecnica.enfermedades_exoticas
									SET
										estado='eliminado', fecha_registro='now()', usuario_responsable='$usuarioResponsable'
									WHERE
										id_enfermedad_exotica='$idEnfermedadExotica' and estado!='eliminado';");
	
		$conexion->ejecutarConsulta("UPDATE
										g_servicios_informacion_tecnica.enfermedades_localizacion
									SET
										estado='eliminado', fecha_registro='now()', usuario_responsable='$usuarioResponsable'
									WHERE
										id_enfermedad_exotica='$idEnfermedadExotica' and estado!='eliminado';");
		
		$conexion->ejecutarConsulta("UPDATE
										g_servicios_informacion_tecnica.enfermedades_producto
									SET
										estado='eliminado', fecha_registro='now()', usuario_responsable='$usuarioResponsable'
									WHERE
										id_enfermedad_exotica='$idEnfermedadExotica' and estado!='eliminado';");
		
		$conexion->ejecutarConsulta("UPDATE
										g_servicios_informacion_tecnica.enfermedades_requerimiento
									SET
										estado='eliminado', fecha_registro='now()', usuario_responsable='$usuarioResponsable'
									WHERE
										id_enfermedad_exotica='$idEnfermedadExotica' and estado!='eliminado';");
	}
	
	public function actualizarEstadoEnfermedadProducto($conexion,$idEnfermedadProducto,$usuarioResponsable){
		$conexion->ejecutarConsulta("UPDATE 
										g_catalogos.enfermedad_animal_producto
									SET
										estado='eliminado', fecha_registro='now()', usuario_responsable='$usuarioResponsable'
									WHERE 
										id_enfermedad_producto='$idEnfermedadProducto';");
	}
	
	public function actualizarEnfermedadAnimal($conexion,$idEnfermedad,$nombre,$descripcion,$observacion,$usuarioResponsable){
		$conexion->ejecutarConsulta("UPDATE 
										g_catalogos.enfermedades_animales
									SET 
										nombre='$nombre', descripcion='$descripcion', observacion='$observacion', fecha_registro='now()', usuario_responsable='$usuarioResponsable'
									WHERE 
										id_enfermedad='$idEnfermedad';");
	}
	
	public function buscarRegistroEnfermedadProducto($conexion,$idEnfermedad,$idProducto){
		$res = $conexion->ejecutarConsulta("SELECT 
												eap.id_enfermedad_producto, eap.id_producto, eap.id_subtipo_producto, eap.id_tipo_producto,
												eap.estado, eap.fecha_registro, eap.id_enfermedad, pr.nombre_comun nombre_producto
											FROM 
												g_catalogos.enfermedad_animal_producto eap , g_catalogos.productos pr
											WHERE 
												eap.id_producto=pr.id_producto and eap.id_enfermedad='$idEnfermedad' and eap.id_producto  in $idProducto and   eap.estado='activo';;");
		return $res;
	}
	
	public function abrirZona($conexion,$idZona){
		$res = $conexion->ejecutarConsulta("SELECT 
												id_zona, nombre, estado, fecha_registro
										  	FROM 
												g_catalogos.zonas 
											WHERE 
												id_zona=$idZona;");
		return $res;
	}
	
	public function actualizarZona($conexion,$idZona,$nombre,$usuarioResponsable){
		$conexion->ejecutarConsulta("UPDATE 
										g_catalogos.zonas
   									SET 
										nombre='$nombre', fecha_registro='now()',usuario_responsable='$usuarioResponsable'
									 WHERE 
										id_zona='$idZona';");
	}
	
	public function listaPaisesZonas($conexion,$idZona){
		$res = $conexion->ejecutarConsulta("SELECT 
												id_pais_zona, id_zona, nombre, estado, fecha_registro, id_pais
  											FROM 
												g_catalogos.paises_zonas 
											WHERE 
												id_zona=$idZona and	 estado='activo' order by 3;");
		return $res;
	}
	
	public function imprimirLineaPaisesZona($idZonaPais,$nombre,$usuarioResponsable){
		return '<tr id="R' . $idZonaPais . '">' .
				'<td width="100%">'.$nombre.'</td>' .
				'<td>' .
				'<form class="borrar" data-rutaAplicacion="serviciosInformacionTecnica" data-opcion="eliminarZonasPaisesSAA">' .
				'<input type="hidden" name="usuarioResponsable" value="' . $usuarioResponsable . '" >' .
				'<input type="hidden" name="idZonaPais" value="' . $idZonaPais . '" >' .
				'<button type="submit" class="icono" ></button>' .
				'</form>' .
				'</td>' .
				'</tr>';
	}
	
	public function buscarRegistroPaisesZonas($conexion,$idZona,$idPais){
		$res = $conexion->ejecutarConsulta("SELECT 
												id_pais_zona, id_zona, nombre, estado, fecha_registro, id_pais
											FROM 
												g_catalogos.paises_zonas 
											WHERE 
												id_zona='$idZona' and id_pais='$idPais' and estado='activo';");
		return $res;
	}
	
	public function actualizarEstadoZonasPaises($conexion,$idZonaPais,$usuarioResponsable){
		$conexion->ejecutarConsulta("UPDATE 
										g_catalogos.paises_zonas
								   	SET  
										estado='eliminado', fecha_registro='now()', usuario_responsable='$usuarioResponsable'
								 	WHERE 
										id_pais_zona='$idZonaPais';");
	}
	
	public function listarRequerimientoElemento($conexion,$idRequerimiento){
		$res = $conexion->ejecutarConsulta("SELECT 
												id_requerimiento_elemento, id_requerimiento, nombre, descripcion, estado, fecha_registro
											FROM 
												g_catalogos.requerimiento_elementos 
											WHERE id_requerimiento='$idRequerimiento' and estado='activo' ORDER BY 2;");
		return $res;
	}
	
	public function guardarEnfermedadesExoticas($conexion,$idEnfermedad,$nombreEnfermedad,$inicioVigencia,$finVigencia,$observacion,$usuarioResponsable){
		$res = $conexion->ejecutarConsulta("INSERT INTO g_servicios_informacion_tecnica.enfermedades_exoticas(
           										id_enfermedad, nombre_enfermedad, inicio_vigencia, fin_vigencia, observacion, estado, fecha_registro,usuario_responsable)
   											VALUES 
												($idEnfermedad, '$nombreEnfermedad', '$inicioVigencia','$finVigencia','$observacion' ,'activo',now(),'$usuarioResponsable') RETURNING id_enfermedad_exotica;");
		return $res;
	}
	
	public function guardarEnfermedadesLocalizacion($conexion,$idZona, $nombreZona, $idPais, $nombrePais,$idEnfermedadExotica,$usuarioResponsable){
		$res = $conexion->ejecutarConsulta("INSERT INTO g_servicios_informacion_tecnica.enfermedades_localizacion(
             									id_zona, nombre_zona, id_pais, nombre_pais, estado, fecha_registro,id_enfermedad_exotica,usuario_responsable)
    										VALUES 
												($idZona, '$nombreZona', $idPais, '$nombrePais','activo',now(),$idEnfermedadExotica,'$usuarioResponsable');");
		return $res;
	}
	
	public function guardarEnfermedadesRequerimiento($conexion,$idRequerimiento,$nombreRequerimiento, $idRequerimientoElemento, $nombreElementoRevision,$idEnfermedadExotica,$usuarioResponsable){
		$res = $conexion->ejecutarConsulta("INSERT INTO g_servicios_informacion_tecnica.enfermedades_requerimiento(
            									id_requerimiento, nombre_requerimiento, id_requerimiento_elemento, nombre_elemento_revision, estado, fecha_registro, id_enfermedad_exotica,usuario_responsable)
    										VALUES 
												($idRequerimiento,'$nombreRequerimiento', $idRequerimientoElemento, '$nombreElementoRevision', 'activo', now(), $idEnfermedadExotica,'$usuarioResponsable') RETURNING id_enfermedad_requerimiento;");
		return $res;
	}
	
	public function abrirEnfermedadExotica($conexion,$idEnfermedad){
		$res = $conexion->ejecutarConsulta("SELECT 
												id_enfermedad_exotica, id_enfermedad, nombre_enfermedad, inicio_vigencia, fin_vigencia, observacion, estado, fecha_registro
  											FROM 
												g_servicios_informacion_tecnica.enfermedades_exoticas where id_enfermedad_exotica=$idEnfermedad;");
		return $res;
	}
	
	public function listarEnfermedadExotica($conexion){
		$res = $conexion->ejecutarConsulta("SELECT 
												id_enfermedad_exotica, id_enfermedad, nombre_enfermedad, inicio_vigencia, fin_vigencia, observacion, estado, fecha_registro
  											FROM 
												g_servicios_informacion_tecnica.enfermedades_exoticas;");
		return $res;
	}

	public function guardarEnfermedadesProductos($conexion,$idProducto,$nombreProducto,$idSubTipoProducto,$idTipoProducto,$idEnfermedadExotica,$usuarioResponsable,$partidaArancelaria){
		$res = $conexion->ejecutarConsulta("INSERT INTO g_servicios_informacion_tecnica.enfermedades_producto(
									            id_producto, nombre_producto, id_subtipo_producto, id_tipo_producto, estado, fecha_registro, id_enfermedad_exotica,usuario_responsable,partida_arancelaria)
									    	VALUES 
												($idProducto, '$nombreProducto', $idSubTipoProducto, $idTipoProducto,'activo',now(),$idEnfermedadExotica,$usuarioResponsable,'$partidaArancelaria') RETURNING id_enfermedad_producto ;");
		return $res;
	}
	
	public function listaEnfermedadExoticaLocalizacion($conexion,$idEnfermedadExotica){
		$res = $conexion->ejecutarConsulta("SELECT 
												id_enfermedad_localizacion, id_zona, nombre_zona, id_pais, nombre_pais, estado, fecha_registro, id_enfermedad_exotica
  											FROM 
												g_servicios_informacion_tecnica.enfermedades_localizacion
											WHERE 
												id_enfermedad_exotica='$idEnfermedadExotica' and estado='activo';");
		return $res;
	}
	
	public function imprimirLineaEnfermedadesExoticasLocalizacion($idEnfermedadLocalizacion,$nombreZona,$nombrePais,$usuarioResponsable){
		return '<tr id="R' . $idEnfermedadLocalizacion . '">' .
				'<td width="100%">'.$nombreZona.' - '.$nombrePais.'</td>' .
				'<td>' .
				'<form class="borrar" data-rutaAplicacion="serviciosInformacionTecnica" data-opcion="eliminarEnfermedadExoticaLocalizacionSAA">' .
				'<input type="hidden" name="usuarioResponsable" value="' . $usuarioResponsable . '" >' .
				'<input type="hidden" name="idEnfermedadLocalizacion" value="' . $idEnfermedadLocalizacion . '" >' .
				'<button type="submit" class="icono" ></button>' .
				'</form>' .
				'</td>' .
				'</tr>';
	}
	
	public function listarEnfermedadesAnimalesProductoXid($conexion, $idEnfermedad){
		$res = $conexion->ejecutarConsulta("SELECT 
												id_enfermedad_producto, id_producto, id_subtipo_producto, id_tipo_producto,estado, fecha_registro, id_enfermedad
											FROM 
												g_catalogos.enfermedad_animal_producto
											WHERE 
												id_enfermedad='$idEnfermedad'  and estado='activo';");
		return $res;
	}
	
	public function listaEnfermedadExoticaRequerimiento($conexion,$idEnfermedadExotica){
		$res = $conexion->ejecutarConsulta("SELECT 
												id_enfermedad_requerimiento, id_requerimiento, nombre_requerimiento, id_requerimiento_elemento, nombre_elemento_revision, estado, fecha_registro, id_enfermedad_exotica
											FROM 
												g_servicios_informacion_tecnica.enfermedades_requerimiento
											WHERE 
												id_enfermedad_exotica='$idEnfermedadExotica' and estado='activo';");
		return $res;
	}
	
	public function imprimirLineaEnfermedadesExoticasRequerimiento($idEnfermedadRequerimiento,$nombreTipo,$nombreRequerimiento,$usuarioResponsable){
		return '<tr id="R' . $idEnfermedadRequerimiento . '">' .
					'<td width="100%">'.$nombreTipo.' - '.$nombreRequerimiento.'</td>' .
					'<td>' .
						'<form class="borrar" data-rutaAplicacion="serviciosInformacionTecnica" data-opcion="eliminarEnfermedadExoticaRequerimientoSAA">' .
							'<input type="hidden" name="usuarioResponsable" value="' . $usuarioResponsable . '" >' .
							'<input type="hidden" name="idEnfermedadRequerimiento" value="' . $idEnfermedadRequerimiento . '" >' .
							'<button type="submit" class="icono" ></button>' .
						'</form>' .
					'</td>' .
				'</tr>';
	}
	
	public function actualizarEstadoEnfermedadLocalizacion($conexion,$idEnfermedadLocalizacion,$usuarioResponsable){
		$conexion->ejecutarConsulta("UPDATE 
										g_servicios_informacion_tecnica.enfermedades_localizacion
								  	SET  
										estado='eliminado', fecha_registro='now()', usuario_responsable='$usuarioResponsable'
									 WHERE id_enfermedad_localizacion='$idEnfermedadLocalizacion';");
		return $res;
	}
	
	public function actualizarEstadoEnfermedadRequerimiento($conexion,$idEnfermedadRequerimiento,$usuarioResponsable){
		$conexion->ejecutarConsulta("UPDATE 
										g_servicios_informacion_tecnica.enfermedades_requerimiento
								   	SET 
										estado='eliminado', fecha_registro='now()', usuario_responsable='$usuarioResponsable'
									WHERE 
										id_enfermedad_requerimiento='$idEnfermedadRequerimiento';");
		return $res;
	}
	
	public function buscarTipoProductoEnfermedad($conexion,$idEnfermedad){
		$res = $conexion->ejecutarConsulta("SELECT DISTINCT 
												tp.id_tipo_producto, tp.nombre
       										FROM 
												g_catalogos.enfermedad_animal_producto eap, g_catalogos.tipo_productos tp 
											WHERE  eap.id_tipo_producto=tp.id_tipo_producto and  eap.id_enfermedad='$idEnfermedad' and eap.estado='activo';");
		return $res;
	}
	
	public function buscarSubTipoProductoEnfermedad($conexion,$idEnfermedad){
		$res = $conexion->ejecutarConsulta("SELECT DISTINCT 
												stp.id_tipo_producto, stp.nombre, stp.id_subtipo_producto
											FROM 
												g_catalogos.enfermedad_animal_producto eap, g_catalogos.subtipo_productos stp 
											WHERE  eap.id_subtipo_producto=stp.id_subtipo_producto and  eap.id_enfermedad='$idEnfermedad' and eap.estado='activo';");
				return $res;
	}

	public function buscarProductoEnfermedad($conexion,$idEnfermedad, $idSubTipoProducto){
		$res = $conexion->ejecutarConsulta("SELECT
												p.id_producto, p.nombre_comun
											FROM 
												g_catalogos.enfermedad_animal_producto eap, g_catalogos.productos p 
											WHERE	  
												eap.id_producto=p.id_producto and eap.id_enfermedad='$idEnfermedad' and	p.id_subtipo_producto='$idSubTipoProducto' and eap.estado='activo';");
		return $res;
	}
	
	public function buscarRegistroEnfermedadExoticaProducto($conexion,$idEnfermedadExotica,$idProducto){
		$res = $conexion->ejecutarConsulta("SELECT 
												ep.id_enfermedad_producto, ep.id_producto, ep.id_subtipo_producto, ep.id_tipo_producto,
												ep.estado, ep.fecha_registro, eap.id_enfermedad, pr.nombre_comun nombre_producto
											FROM 
												g_servicios_informacion_tecnica.enfermedades_producto ep ,g_servicios_informacion_tecnica.enfermedades_exoticas eap , g_catalogos.productos pr
											WHERE 
												ep.id_enfermedad_exotica=eap.id_enfermedad_exotica and ep.id_producto=pr.id_producto and
												eap.id_enfermedad_exotica='$idEnfermedadExotica' and ep.id_producto  in $idProducto and  ep.estado in ('activo','inactivo');");
		return $res;
	}

	public function imprimirLineaEnfermedadExoticaProducto($idEnfermedadProducto,$nombre,$estado,$usuarioResponsable,$idEnfermedadExotica,$partidaArancelaria){
		return '<tr id="R' . $idEnfermedadProducto . '">' .
					'<td width="50%">'.$nombre.'</td>' .
					'<td >'.$partidaArancelaria.'</td>' .
					'<td>' .
					'<form class="'.$estado.'" data-rutaAplicacion="serviciosInformacionTecnica" data-opcion="actualizarEstadoEnfermedadExoticaProducto">' .
					'<input type="hidden" name="usuarioResponsable" value="' . $usuarioResponsable . '" >' .
					'<input type="hidden" id="estadoRequisito" name="estadoRequisito">' .
					'<input type="hidden" id="cambioEstado" name="cambioEstado">' .
					'<input type="hidden" name="idEnfermedadExotica" value="' . $idEnfermedadExotica . '" >' .
					'<input type="hidden" name="idEnfermedadProducto" value="' . $idEnfermedadProducto . '" >' .
					'<center><button type="submit" class="icono"></button></center>' .
					'</form>' .
					'</td>' .
					'<td>'.
					'<form class="borrar" data-rutaAplicacion="serviciosInformacionTecnica" data-opcion="eliminarEnfermedadExoticaProductoSAA">' .
					'<input type="hidden" name="usuarioResponsable" value="' . $usuarioResponsable . '" >' .
					'<input type="hidden" name="idEnfermedadProducto" value="' . $idEnfermedadProducto . '" >' .
					'<center><button type="submit" class="icono" ></button></center>' .
					'</form>' .
					'</td>' .
				'</tr>';
	}
	
	public function listaEnfermedadExoticaProducto($conexion,$idEnfermedadExotica){
		$res = $conexion->ejecutarConsulta("SELECT 
												id_enfermedad_producto, id_producto, nombre_producto, id_subtipo_producto, id_tipo_producto, estado, fecha_registro, id_enfermedad_exotica,partida_arancelaria
											FROM 
												g_servicios_informacion_tecnica.enfermedades_producto 
											WHERE id_enfermedad_exotica='$idEnfermedadExotica' and estado not in('eliminado');");
		return $res;
	}
	
	public function actualizarEstadoEnfermedadExoticaProducto($conexion,$idEnfermedadProducto,$usuarioResponsable){
		$conexion->ejecutarConsulta("UPDATE 
										g_servicios_informacion_tecnica.enfermedades_producto
								   	SET 
										estado='eliminado', fecha_registro='now()', usuario_responsable='$usuarioResponsable'
									WHERE 
										id_enfermedad_producto='$idEnfermedadProducto';");
	}
	
	public function actualizarEstadoEnfermedadExoticaProductoSAA($conexion,$idEnfermedadProducto,$estado,$usuarioResponsable){
		$conexion->ejecutarConsulta("UPDATE 
												g_servicios_informacion_tecnica.enfermedades_producto
											SET 
												estado='$estado', fecha_registro='now()', usuario_responsable='$usuarioResponsable'
											WHERE 
												id_enfermedad_producto='$idEnfermedadProducto';");
	}
	
	public function actualizarCambioEstadoEnfermedadExoticaSinProducto($conexion,$idEnfermedadExotica,$usuarioResponsable){
		$conexion->ejecutarConsulta("UPDATE
				g_servicios_informacion_tecnica.enfermedades_exoticas
				SET
				estado='inactivo', fecha_registro='now()', usuario_responsable='$usuarioResponsable'
				WHERE
				id_enfermedad_exotica='$idEnfermedadExotica' ;");
		
		$conexion->ejecutarConsulta("UPDATE
				g_servicios_informacion_tecnica.enfermedades_producto
				SET
				estado='inactivo', fecha_registro='now()', usuario_responsable='$usuarioResponsable'
				WHERE
				id_enfermedad_exotica='$idEnfermedadExotica' and estado not in ('eliminado','inactivo');");
				
	}
	
	public function listarFiltroEnfermedadExotica($conexion, $zona, $pais, $producto, $partida,$estado,$fechaInicio, $fechaFin,$filtro){
		$zona = $zona!="" ?  $zona  : 'NULL';
		$pais = $pais!="" ?  $pais  : 'NULL';
		$producto = $producto!="" ?  "'%" .$producto. "%'" : "NULL";
		$partida = $partida!="" ?  "'%" .$partida. "%'"   : "NULL";
		$estado = $estado!="" ?  "'" .$estado. "'"   : "NULL";
		$fechaInicio = $fechaInicio != "" ? "'" . $fechaInicio . "'" : "NULL";
		$fechaFin = $fechaFin != "" ? "'" . $fechaFin . "'" : "NULL";
		
		if($partida!='NULL' || $producto!='NULL' ){
			$busquedaFrom=" ,g_servicios_informacion_tecnica.enfermedades_producto ep,g_catalogos.productos p";
			$busquedaWhere=" ex.id_enfermedad_exotica=ep.id_enfermedad_exotica and p.id_producto=ep.id_producto and ($producto is NULL or ep.nombre_producto ilike $producto) and ($partida is NULL or p.partida_arancelaria like $partida) and ";
		}
		
		if($filtro==null)
			$busqueda = " limit 0";

		$res = $conexion->ejecutarConsulta("SELECT DISTINCT 
												ex.id_enfermedad_exotica,  ex.nombre_enfermedad, ex.inicio_vigencia, ex.fin_vigencia, ex.observacion, ex.estado,
												array_to_string(ARRAY(SELECT ell.nombre_pais
												 						FROM g_servicios_informacion_tecnica.enfermedades_localizacion ell 
																		WHERE  ex.id_enfermedad_exotica=ell.id_enfermedad_exotica and ell.estado='activo' ),', ') as paises,

												array_to_string(ARRAY(SELECT enp.nombre_producto
												 						FROM g_servicios_informacion_tecnica.enfermedades_producto enp 
																		WHERE ex.id_enfermedad_exotica=enp.id_enfermedad_exotica and enp.estado='activo'  ),', ') as productos
  											FROM 
												g_servicios_informacion_tecnica.enfermedades_exoticas ex, g_servicios_informacion_tecnica.enfermedades_localizacion el
												".$busquedaFrom."
										  	WHERE
										 		".$busquedaWhere."
										      	ex.id_enfermedad_exotica=el.id_enfermedad_exotica and el.estado='activo' AND 
												(($zona is NULL or el.id_zona = $zona ) or 
										    	($pais is NULL or el.id_pais = $pais)) and ($estado is NULL or ex.estado=$estado) and 
												($fechaInicio is NULL or ex.inicio_vigencia >=$fechaInicio) and
												($fechaFin is NULL or ex.fin_vigencia >=$fechaFin) and
												(($fechaInicio is NULL or $fechaFin is NULL) or ((ex.inicio_vigencia between $fechaInicio and $fechaFin) or (ex.fin_vigencia between $fechaInicio and $fechaFin)) ) 
												".$busqueda.";");
				return $res;
	}
	public function listarFiltroEnfermedadExoticaSAA($conexion, $zona, $pais, $producto, $partida,$estado,$fechaInicio, $fechaFin,$filtro){
		$zona = $zona!="" ?  $zona  : 'NULL';
		$pais = $pais!="" ?  $pais  : 'NULL';
		$producto = $producto!="" ?  "'%" .$producto. "%'" : "NULL";
		$partida = $partida!="" ?  "'%" .$partida. "%'"   : "NULL";
		$estado = $estado!="" ?  "'" .$estado. "'"   : "NULL";
		$fechaInicio = $fechaInicio != "" ? "'" . $fechaInicio . "'" : "NULL";
		$fechaFin = $fechaFin != "" ? "'" . $fechaFin . "'" : "NULL";
	
		if($partida!='NULL' || $producto!='NULL' ){
			$busquedaFrom=" ,g_servicios_informacion_tecnica.enfermedades_producto ep,g_catalogos.productos p";
			$busquedaWhere=" ex.id_enfermedad_exotica=ep.id_enfermedad_exotica and p.id_producto=ep.id_producto and ($producto is NULL or ep.nombre_producto ilike $producto) and ($partida is NULL or p.partida_arancelaria like $partida) and ";
		}
		
		if($filtro==null)
			$busqueda = " limit 0";
	
	   $res = $conexion->ejecutarConsulta("SELECT DISTINCT 
	   											ex.id_enfermedad_exotica,  ex.nombre_enfermedad, ex.inicio_vigencia, ex.fin_vigencia, ex.observacion, ex.estado,
												array_to_string(ARRAY(SELECT ell.nombre_pais
										 							FROM g_servicios_informacion_tecnica.enfermedades_localizacion ell 
	   																WHERE ex.id_enfermedad_exotica=ell.id_enfermedad_exotica and ell.estado='activo' ),', ') as paises,
												array_to_string(ARRAY(SELECT enp.nombre_producto
																	FROM g_servicios_informacion_tecnica.enfermedades_producto enp 
	   																WHERE ex.id_enfermedad_exotica=enp.id_enfermedad_exotica  ),', ') as productos
											FROM 
	   											g_servicios_informacion_tecnica.enfermedades_exoticas ex, g_servicios_informacion_tecnica.enfermedades_localizacion el
												".$busquedaFrom."
										   	WHERE
										   		".$busquedaWhere."
											   	ex.id_enfermedad_exotica=el.id_enfermedad_exotica and el.estado='activo' AND
											   	(($zona is NULL or el.id_zona = $zona ) or
											   	($pais is NULL or el.id_pais = $pais)) and ($estado is NULL or ex.estado=$estado) and
											   	($fechaInicio is NULL or ex.inicio_vigencia >=$fechaInicio) and
											   	($fechaFin is NULL or ex.fin_vigencia >=$fechaFin) and
											   	(($fechaInicio is NULL or $fechaFin is NULL) or ((ex.inicio_vigencia between $fechaInicio and $fechaFin) or (ex.fin_vigencia between $fechaInicio and $fechaFin)) )
												".$busqueda.";");
	   return $res;
	}
	
	public function abrirFiltroEnfermedadExotica($conexion, $idEnfermedadExotica){
	   $res = $conexion->ejecutarConsulta("SELECT DISTINCT 
	   											ex.id_enfermedad_exotica,  ex.nombre_enfermedad, ex.inicio_vigencia,ex.fin_vigencia, ex.observacion, ex.estado,
												array_to_string(ARRAY(SELECT ell.nombre_pais
							 											FROM g_servicios_informacion_tecnica.enfermedades_localizacion ell 
	   																	WHERE  ex.id_enfermedad_exotica=ell.id_enfermedad_exotica and ell.estado='activo' ),', ') as paises,
												array_to_string(ARRAY(SELECT distinct elll.nombre_zona
											 							FROM g_servicios_informacion_tecnica.enfermedades_localizacion elll 
	   																	WHERE ex.id_enfermedad_exotica=elll.id_enfermedad_exotica and elll.estado='activo' ),', ') as zonas,
							  					array_to_string(ARRAY(SELECT enp.nombre_producto
												  						FROM g_servicios_informacion_tecnica.enfermedades_producto enp 
	   																	WHERE ex.id_enfermedad_exotica=enp.id_enfermedad_exotica and enp.estado='activo'  ),', ') as productos
 											FROM 
	   											g_servicios_informacion_tecnica.enfermedades_exoticas ex, g_servicios_informacion_tecnica.enfermedades_localizacion el
											WHERE
												ex.id_enfermedad_exotica=el.id_enfermedad_exotica and el.estado='activo' AND
												ex.id_enfermedad_exotica='$idEnfermedadExotica';");
	   return $res;
	}
	
	public function abrirFiltroEnfermedadExoticaTipoProducto($conexion, $idEnfermedadExotica){
		$res = $conexion->ejecutarConsulta("SELECT DISTINCT 
												tp.id_tipo_producto, tp.nombre
											FROM
												g_servicios_informacion_tecnica.enfermedades_exoticas ex,  g_servicios_informacion_tecnica.enfermedades_producto enp ,g_catalogos.tipo_productos tp
											WHERE
												ex.id_enfermedad_exotica=enp.id_enfermedad_exotica and enp.id_tipo_producto=tp.id_tipo_producto and enp.estado='activo' and 
												ex.id_enfermedad_exotica='$idEnfermedadExotica';");
				return $res;
	}
	
	public function abrirFiltroEnfermedadExoticaSubTipoProducto($conexion, $idEnfermedadExotica, $tipoProducto){
		$res = $conexion->ejecutarConsulta("SELECT DISTINCT 
												stp.id_subtipo_producto, stp.nombre
											FROM
												g_servicios_informacion_tecnica.enfermedades_exoticas ex,  g_servicios_informacion_tecnica.enfermedades_producto enp ,g_catalogos.subtipo_productos stp
											WHERE
												ex.id_enfermedad_exotica=enp.id_enfermedad_exotica and enp.id_subtipo_producto=stp.id_subtipo_producto and enp.estado='activo' and
												ex.id_enfermedad_exotica='$idEnfermedadExotica' and stp.id_tipo_producto='$tipoProducto';");
		return $res;
	}
	
	public function abrirFiltroEnfermedadExoticaProducto($conexion, $idEnfermedadExotica, $subTipoProducto){
		$res = $conexion->ejecutarConsulta("SELECT DISTINCT
												p.id_producto, p.nombre_comun nombre, p.partida_arancelaria
											FROM
												g_servicios_informacion_tecnica.enfermedades_exoticas ex,  g_servicios_informacion_tecnica.enfermedades_producto enp ,g_catalogos.productos p
											WHERE
												ex.id_enfermedad_exotica=enp.id_enfermedad_exotica and enp.id_producto=p.id_producto and enp.estado='activo' and
												ex.id_enfermedad_exotica='$idEnfermedadExotica' and p.id_subtipo_producto='$subTipoProducto';");
		return $res;
	}
	
	public function buscarEnfermedadLocalizacionPaisesZonas($conexion,$idZona,$idPais,$idEnfermedadExotica){
		$res = $conexion->ejecutarConsulta("SELECT 
												id_enfermedad_localizacion, id_zona, nombre_zona, id_pais, nombre_pais, estado, fecha_registro, id_enfermedad_exotica
 											FROM 
												g_servicios_informacion_tecnica.enfermedades_localizacion 
											WHERE 
												id_zona='$idZona' and id_pais='$idPais' and id_enfermedad_exotica='$idEnfermedadExotica' and estado='activo';");
	
		return $res;
	}
	
	public function buscarEnfermedadRequerimientoTipoRevision($conexion,$idRequerimiento,$idRequerimientoElemento,$idEnfermedadExotica){
		$res = $conexion->ejecutarConsulta("SELECT 
												id_enfermedad_requerimiento, id_requerimiento, nombre_requerimiento, id_requerimiento_elemento, nombre_elemento_revision, estado, fecha_registro, id_enfermedad_exotica
 											FROM 
												g_servicios_informacion_tecnica.enfermedades_requerimiento 
											WHERE 
												id_requerimiento='$idRequerimiento' and id_requerimiento_elemento='$idRequerimientoElemento' and id_enfermedad_exotica='$idEnfermedadExotica' and estado='activo';");
	
		return $res;
	}
	
	public function actualizarEnfermedadExotica($conexion,$idEnfermedadExotica,$nombreEnfermedad,$inicioVigencia,$finVigencia,$observacion,$estado,$usuarioResponsable){
		$conexion->ejecutarConsulta("UPDATE 
										g_servicios_informacion_tecnica.enfermedades_exoticas
   									SET 
										nombre_enfermedad='$nombreEnfermedad', inicio_vigencia='$inicioVigencia', fin_vigencia='$finVigencia', observacion='$observacion', estado='$estado', fecha_registro='now()', usuario_responsable='$usuarioResponsable'
									WHERE 
										id_enfermedad_exotica='$idEnfermedadExotica';");
	}
	
	public function consultarEnfermedadesExoticasFinVigencia($conexion, $estado){
		$res = $conexion->ejecutarConsulta("SELECT 
												id_enfermedad_exotica, nombre_enfermedad, usuario_responsable, fin_vigencia
   											FROM 
												g_servicios_informacion_tecnica.enfermedades_exoticas
											WHERE
												estado='".$estado."' and fin_vigencia<=current_date;");
		return $res;
	}	
	
	
	////// Sanidad vegetal admisitracion de certificados
	
	public function guardarCertificado($conexion,$idItem, $idLocalizacion,$fecha, $ruta){
	   
	    $consulta="INSERT INTO
                            g_servicios_informacion_tecnica.certificados(id_item, id_localizacion, fecha_ingreso, ruta_archivo, estado)
                    VALUES ($idItem, $idLocalizacion, '$fecha', '$ruta',1) returning id_certificado_cabecera;";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
	public function guardarFirmasDetalle($conexion,$detalle){	    
	    $consulta="INSERT INTO 
                            g_servicios_informacion_tecnica.firmas_autorizadas(identificador, cargo, nombre_funcionario, ruta_archivo, 
                            estado, fecha, id_certificado_cabecera)
                     VALUES $detalle returning id_firma;";	   
	    $res = $conexion->ejecutarConsulta($consulta);
	   
	    return $res;
	}	
	
	public function guardarFirmasDetalleHistorial($conexion,$detalle){
	    $consulta="INSERT INTO
                            g_servicios_informacion_tecnica.historial_cambios_firmas(id_firma, id_certificado_cabecera, identificador, cargo, nombre_funcionario, ruta_archivo, estado, fecha)
                     VALUES $detalle;";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
	public function listarCertificados($conexion){
	    
	    $consulta="SELECT 
                	   c.id_certificado_cabecera, c.id_item ,i.nombre, fecha_ingreso, c.estado
                    FROM 
                	   g_servicios_informacion_tecnica.certificados c, g_administracion_catalogos.items_catalogo i
                    WHERE
                    	c.estado=1
                    	and c.id_item= i.id_item;";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
	public function listarCertificadosXPais($conexion, $pais = NULL, $localizacion = NULL){
	        
	    $columna = "c.id_certificado_cabecera, c.id_item ,i.nombre, fecha_ingreso, c.estado, l.nombre pais, l.id_localizacion";
	    
	    if ($pais!=null){
	        $busqueda=" and c.id_localizacion=$pais";    
	    }
	    
	    if($localizacion != null){
	        $columna = "distinct l.nombre pais, l.id_localizacion";
	        $busqueda = "order by l.id_localizacion";
	    }
	    
	    
	    $consulta="SELECT
                        $columna    
                    FROM 
                	   g_servicios_informacion_tecnica.certificados c, g_administracion_catalogos.items_catalogo i, g_catalogos.localizacion l
                    WHERE
                    	c.estado=1
                    	and c.id_item= i.id_item
                        and l.id_localizacion = c.id_localizacion
                        $busqueda;";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
	public function abrirCertificado($conexion,$idCertificado){
	    
	    $consulta="SELECT
                        id_certificado_cabecera, id_item, fecha_ingreso
                    FROM
                        g_servicios_informacion_tecnica.certificados
                    WHERE
                        id_certificado_cabezera in ($idCertificado);";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
	public function obtenerCertificadoXId($conexion,$idCertificado){
	    $consulta="SELECT
                        ce.id_certificado_cabecera, ce.id_item, ce.id_localizacion, lo.nombre, ce.fecha_ingreso, ce.ruta_archivo, ce.estado, i.nombre certificado
                   FROM 
                        g_servicios_informacion_tecnica.certificados ce, g_catalogos.localizacion lo, g_administracion_catalogos.items_catalogo i
                  WHERE
                	   ce.id_certificado_cabecera in ($idCertificado)
                	   and ce.id_localizacion = lo.id_localizacion
                       and ce.id_item= i.id_item;";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
	public function listarFirmas($conexion,$idCertificado){
	    $consulta="SELECT 
                        id_firma, identificador, cargo, nombre_funcionario, ruta_archivo, (case when estado=1 then'Activa' else 'Inactiva' end) estado
                    FROM 
	                   g_servicios_informacion_tecnica.firmas_autorizadas
                   WHERE
                	   id_certificado_cabecera=$idCertificado
                 ORDER BY
                        1;";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
	public function listarFirmasHistorial($conexion,$idCertificado){
	    $consulta="SELECT
                        id_firma, identificador, cargo, nombre_funcionario, ruta_archivo, (case when estado=1 then'Activa' else 'Inactiva' end) estado, fecha
                    FROM
	                   g_servicios_informacion_tecnica.historial_cambios_firmas
                   WHERE
                	   id_certificado_cabecera=$idCertificado
                ORDER BY
                        7 desc;";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
	public function actualizarCertificado($conexion,$idCertificado,$ruta){
	    $consulta="UPDATE 
                    	g_servicios_informacion_tecnica.certificados
                       SET 
                           ruta_archivo='$ruta'
                     WHERE 
                    	id_certificado_cabecera='$idCertificado';";
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;	    
	}
	
	public function actualizarFirmas($conexion,$detalle){
	    $consulta="UPDATE 
                        g_servicios_informacion_tecnica.firmas_autorizadas as t1 set 
                        identificador = t2.identificador,
                        cargo = t2.cargo,
                        nombre_funcionario = t2.nombre_funcionario,
                        ruta_archivo = t2.ruta_archivo,
                        estado = t2.estado
                    FROM
                        (values $detalle ) as t2
                        (id_firma, identificador, cargo, nombre_funcionario,ruta_archivo,estado)
                    WHERE 
                        t2.id_firma = t1.id_firma;";
      
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
	public function eliminarCertificados($conexion,$detalle){
	    $consulta="UPDATE
                        g_servicios_informacion_tecnica.certificados as t1 set                        
                        estado = t2.estado
                    FROM
                        (values $detalle ) as t2
                        (id_certificado_cabecera,estado)
                    WHERE
                        t2.id_certificado_cabecera = t1.id_certificado_cabecera;";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
	public function obtenerCertificadoPorTipoYPais($conexion,$tipo,$pais){	   
	    
	    $consulta="SELECT
                        c.id_certificado_cabecera, c.id_item ,c.id_localizacion, l.nombre pais
                    FROM
                	   g_servicios_informacion_tecnica.certificados c, g_catalogos.localizacion l
                    WHERE
                    	c.estado=1
                        and l.id_localizacion = c.id_localizacion
                    	and c.id_item= $tipo
                        and c.id_localizacion = $pais
                        ;";
                        
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
	}
}
?>
