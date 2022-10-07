<?php
class ControladorSeguridadOcupacional{

	public function listaManejoMaterialesPeligrosos($conexion, $laboratorio, $nombreProducto,$numeroUn, $numeroCas,$opcion){
		$busqueda = '';
		$laboratorio = $laboratorio!="" ? "'" . $laboratorio . "'" : "NULL";
		$nombreProducto = $nombreProducto!="" ? "'%" . $nombreProducto . "%'" : "NULL";
		$numeroUn = $numeroUn!="" ? "'" . $numeroUn . "'" : "NULL";
		$numeroCas = $numeroCas!="" ? "'" . $numeroCas . "'"  : "NULL";
		
		if($laboratorio=="NULL" && $nombreProducto=="NULL" && $numeroUn=="NULL" && $numeroCas=="NULL"){
			$busqueda = "limit 0";
			if($opcion=='porLaboratorio')
				$busqueda = "limit 50";
		}

		$res = $conexion->ejecutarConsulta("SELECT 
												mmp.id_manejo_material_peligroso, 
												mp.nombre_material_peligroso, 
												mp.numero_un_material_peligroso,
												mp.numero_cas_material_peligroso,
												mp.ruta_msds_material_peligroso,
												gmp.numero_guia_material_peligroso,
												gmp.nombre_guia_material_peligroso,
												gmp.ruta_guia_material_peligroso,
												lmp.nombre_laboratorio
											FROM 
												g_seguridad_ocupacional.manejo_materiales_peligrosos mmp, 
												g_catalogos.guias_materiales_peligrosos gmp,
												g_catalogos.materiales_peligrosos  mp,
												g_catalogos.laboratorios_materiales_peligrosos lmp
											WHERE 
												mmp.id_material_peligroso=mp.id_material_peligroso and 
												lmp.id_laboratorio=mmp.id_laboratorio_material_peligroso and
												mp.id_guia_material_peligroso=gmp.id_guia_material_peligroso and
												($laboratorio is NULL or lmp.id_laboratorio = $laboratorio) and 
												($nombreProducto is NULL or quitar_caracteres_especiales(mp.nombre_material_peligroso) ilike $nombreProducto) and 
												($numeroUn is NULL or mp.numero_un_material_peligroso = $numeroUn) and
												($numeroCas is NULL or mp.numero_cas_material_peligroso = $numeroCas)
											ORDER BY 1 DESC
												".$busqueda.";");
		return $res;
	}
		
	public function abrirManejoMaterialesPeligrosos($conexion, $idManejoMaterialPeligroso){
		$res = $conexion->ejecutarConsulta("SELECT				
												mmp.id_manejo_material_peligroso,
												mp.id_material_peligroso,	
												mp.nombre_material_peligroso,
												mp.numero_un_material_peligroso,
												mp.numero_cas_material_peligroso,
												mp.descripcion_material_peligroso,
												mp.ruta_msds_material_peligroso,
												gmp.numero_guia_material_peligroso,
												gmp.nombre_guia_material_peligroso,
												gmp.ruta_guia_material_peligroso,
												lmp.nombre_laboratorio,
												lmpp.nombre_laboratorio nombre_coordinacion
											FROM
												g_seguridad_ocupacional.manejo_materiales_peligrosos mmp,
												g_catalogos.guias_materiales_peligrosos gmp,
												g_catalogos.materiales_peligrosos  mp,
												g_catalogos.laboratorios_materiales_peligrosos lmp,
												g_catalogos.laboratorios_materiales_peligrosos lmpp
				
											WHERE
												mmp.id_material_peligroso=mp.id_material_peligroso and
												lmp.id_laboratorio=mmp.id_laboratorio_material_peligroso and
												mp.id_guia_material_peligroso=gmp.id_guia_material_peligroso and
												lmp.id_laboratorio_padre=lmpp.id_laboratorio and 
												mmp.id_manejo_material_peligroso='$idManejoMaterialPeligroso';");
		return $res;
	}
	
	public function abrirClasificacionRiesgoXMaterialPeligroso($conexion, $idMaterialPeligroso){
		$res = $conexion->ejecutarConsulta("SELECT
												crmp.*,mppc.id_material_peligroso_clasificacion_riesgo
											FROM
												g_catalogos.materiales_peligrosos_clasificacion_riesgos mppc,
												g_catalogos.clasificacion_riesgos_materiales_peligrosos crmp
											WHERE
												mppc.id_clasificacion_riesgo_material_peligroso=crmp.id_clasificacion_riesgo_material_peligroso and
												mppc.id_material_peligroso='$idMaterialPeligroso';");
		return $res;
	}

	public function guardarManejoMaterialPeligroso($conexion, $idMaterialPeligroso,$idLaboratorioMaterialPeligroso,$usuarioReponsable){
		$res = $conexion->ejecutarConsulta("INSERT INTO 
												g_seguridad_ocupacional.manejo_materiales_peligrosos(
           										id_material_peligroso, 
           							 			id_laboratorio_material_peligroso,
												usuario_responsable )
											VALUES
												($idMaterialPeligroso,$idLaboratorioMaterialPeligroso,'$usuarioReponsable') ;");
		return $res;
	}
	
	public function buscarManejoMaterialPeligroso($conexion, $idMaterialPeligroso,$idLaboratorioMaterialPeligroso){
		$res = $conexion->ejecutarConsulta("SELECT 
												id_manejo_material_peligroso
											FROM 
												g_seguridad_ocupacional.manejo_materiales_peligrosos 
											WHERE 
												id_material_peligroso=$idMaterialPeligroso and  
												id_laboratorio_material_peligroso=$idLaboratorioMaterialPeligroso;");
		return $res;
	}
	
	public function imprimirManejoMaterialPeligroso($conexion, $idMaterialPeligroso=null,$idLaboratorioMaterialPeligroso=null){
		$idMaterialPeligroso = $idMaterialPeligroso!="" ? "'" . $idMaterialPeligroso . "'" : "NULL";
		$idLaboratorioMaterialPeligroso = $idLaboratorioMaterialPeligroso!="" ? "'" . $idLaboratorioMaterialPeligroso . "'" : "NULL";
	
		$res = $conexion->ejecutarConsulta("SELECT
												lmp.nombre_laboratorio nombre_laboratorio,
												lmpp.nombre_laboratorio nombre_coordinacion_laboratorio,
												mp.nombre_material_peligroso,
												mp.numero_un_material_peligroso,
												mp.numero_cas_material_peligroso,
												gmp.nombre_guia_material_peligroso,
												gmp.numero_guia_material_peligroso
											FROM
												g_seguridad_ocupacional.manejo_materiales_peligrosos  mmp,g_catalogos.materiales_peligrosos mp,
												g_catalogos.laboratorios_materiales_peligrosos lmp, g_catalogos.guias_materiales_peligrosos gmp,
												g_catalogos.laboratorios_materiales_peligrosos lmpp
											WHERE
												mmp.id_material_peligroso=mp.id_material_peligroso and
												mmp.id_laboratorio_material_peligroso=lmp.id_laboratorio and
												lmp.id_laboratorio_padre=lmpp.id_laboratorio and 
												mp.id_guia_material_peligroso=gmp.id_guia_material_peligroso and 
											($idMaterialPeligroso is NULL or mmp.id_material_peligroso = $idMaterialPeligroso) and
												($idLaboratorioMaterialPeligroso is NULL or mmp.id_laboratorio_material_peligroso = $idLaboratorioMaterialPeligroso);");
		return $res;
	}


	
	public function imprimirMaterialPeligroso($conexion){
		$res = $conexion->ejecutarConsulta("SELECT
												nombre_material_peligroso,
												numero_un_material_peligroso,
										       	numero_cas_material_peligroso, 
										        nombre_guia_material_peligroso,
												numero_guia_material_peligroso
  											FROM 
												g_catalogos.materiales_peligrosos mp,
												g_catalogos.guias_materiales_peligrosos gmp
											WHERE 
												mp.id_guia_material_peligroso=gmp.id_guia_material_peligroso
											ORDER BY 1 ASC ;");
		return $res;
	}
	
	public function buscarMaterialPeligroso ($conexion,$idMaterialPeligroso,$nombreProducto=null,$numeroUn=null, $numeroCas=null){
		$idMaterialPeligroso = $idMaterialPeligroso!="" ? "'" . $idMaterialPeligroso . "'" : "NULL";
		$nombreProducto = $nombreProducto!="" ? "'%" . $nombreProducto . "%'" : "NULL";
		$numeroUn = $numeroUn!="" ? "'" . $numeroUn . "'" : "NULL";
		$numeroCas = $numeroCas!="" ? "'" . $numeroCas . "'"  : "NULL";
		
		$res = $conexion->ejecutarConsulta("SELECT 
												id_material_peligroso,
												nombre_material_peligroso,
												numero_un_material_peligroso,
       											numero_cas_material_peligroso,
												ruta_msds_material_peligroso,
       											descripcion_material_peligroso,
												id_guia_material_peligroso
											FROM 
												g_catalogos.materiales_peligrosos
											WHERE 
												($idMaterialPeligroso is NULL or id_material_peligroso = $idMaterialPeligroso) and
												($nombreProducto is NULL or quitar_caracteres_especiales(nombre_material_peligroso) ilike $nombreProducto) and 
												($numeroUn is NULL or numero_un_material_peligroso = $numeroUn) and
												($numeroCas is NULL or numero_cas_material_peligroso = $numeroCas)
											ORDER BY 2 ASC ;");
	
		return $res;
	}
	
	public function buscarMaterialPeligrosoExiste ($conexion,$nombreProducto){
	
			$res = $conexion->ejecutarConsulta("SELECT
													id_material_peligroso
												FROM
													g_catalogos.materiales_peligrosos
												WHERE
													quitar_caracteres_especiales(nombre_material_peligroso) = '$nombreProducto' ;");
					return $res;
	}
	
	public function actualizarMaterialPeligroso ($conexion,$idMaterialPeligroso,$nombreMaterialPeligroso,$numeroUnMaterialPeligroso,$numeroCasMaterialPeligroso, $rutaMsdsMaterialPeligroso,$descripcionMaterialPeligroso,$idGuiaMaterialPeligroso){
		$numeroUnMaterialPeligroso=$numeroUnMaterialPeligroso!=""?$numeroUnMaterialPeligroso:'null';
		
		$res = $conexion->ejecutarConsulta("UPDATE 
												g_catalogos.materiales_peligrosos
											SET 
												nombre_material_peligroso='$nombreMaterialPeligroso', 
												numero_un_material_peligroso=$numeroUnMaterialPeligroso,
												numero_cas_material_peligroso='$numeroCasMaterialPeligroso',
												ruta_msds_material_peligroso='$rutaMsdsMaterialPeligroso',
												descripcion_material_peligroso='$descripcionMaterialPeligroso',
												id_guia_material_peligroso=$idGuiaMaterialPeligroso
											WHERE 
												id_material_peligroso=$idMaterialPeligroso;");
		return $res;
	}
	
	public function guardarMaterialPeligroso($conexion, $nombreMaterialPeligroso,$numeroUnMaterialPeligroso,$numeroCasMaterialPeligroso, $rutaMsdsMaterialPeligroso,$descripcionMaterialPeligroso,$idGuiaMaterialPeligroso){
		$numeroUnMaterialPeligroso=$numeroUnMaterialPeligroso!=""?$numeroUnMaterialPeligroso:'null';
		
		$res = $conexion->ejecutarConsulta("INSERT INTO 
												g_catalogos.materiales_peligrosos(
												nombre_material_peligroso,
												numero_un_material_peligroso,
												numero_cas_material_peligroso,
												ruta_msds_material_peligroso,
												descripcion_material_peligroso,
												id_guia_material_peligroso)
											VALUES
												('$nombreMaterialPeligroso',$numeroUnMaterialPeligroso,'$numeroCasMaterialPeligroso','$rutaMsdsMaterialPeligroso','$descripcionMaterialPeligroso',$idGuiaMaterialPeligroso) RETURNING id_material_peligroso ;");
		return $res;
	}
	
	public function guardarGuiaMaterialPeligroso($conexion, $numeroGuiaMaterialPeligroso,$nombreGuiaMaterialPeligroso, $rutaGuiaMaterialPeligroso){
		$res = $conexion->ejecutarConsulta("INSERT INTO 
												g_catalogos.guias_materiales_peligrosos(
	 											numero_guia_material_peligroso,
												nombre_guia_material_peligroso,
												ruta_guia_material_peligroso)
											VALUES
												($numeroGuiaMaterialPeligroso,'$nombreGuiaMaterialPeligroso','$rutaGuiaMaterialPeligroso') ;");
		return $res;
	}
	
	public function buscarGuiaMaterialPeligroso ($conexion,$idGuiaMaterialPeligroso,$numeroGuiaMaterialPeligroso=null,$nombreGuiaMaterialPeligroso=null){
		
		$idGuiaMaterialPeligroso = $idGuiaMaterialPeligroso!="" ? "'" . $idGuiaMaterialPeligroso . "'" : "NULL";
		$nombreGuiaMaterialPeligroso = $nombreGuiaMaterialPeligroso!="" ? "'%" . $nombreGuiaMaterialPeligroso . "%'" : "NULL";
		$numeroGuiaMaterialPeligroso = $numeroGuiaMaterialPeligroso!="" ? "'" . $numeroGuiaMaterialPeligroso . "'" : "NULL";

		$res = $conexion->ejecutarConsulta("SELECT
												id_guia_material_peligroso,
												numero_guia_material_peligroso,
												nombre_guia_material_peligroso, 
      											ruta_guia_material_peligroso
  											FROM 
												g_catalogos.guias_materiales_peligrosos
											WHERE
												($idGuiaMaterialPeligroso is NULL or id_guia_material_peligroso=$idGuiaMaterialPeligroso) and 
												($numeroGuiaMaterialPeligroso is NULL or numero_guia_material_peligroso= $numeroGuiaMaterialPeligroso) and 
												($nombreGuiaMaterialPeligroso is NULL or nombre_guia_material_peligroso ilike $nombreGuiaMaterialPeligroso) 
											ORDER BY 2 ASC;");
	
		return $res;
	}
	
	public function buscarClasificacionRiesgoMaterialPeligroso ($conexion,$idClasificacionRiesgoMaterialPeligroso,$nombreClasificacionRiesgoMaterialPeligroso=null ){
	
		$idClasificacionRiesgoMaterialPeligroso = $idClasificacionRiesgoMaterialPeligroso!="" ? "'" . $idClasificacionRiesgoMaterialPeligroso . "'" : "NULL";
		$nombreClasificacionRiesgoMaterialPeligroso = $nombreClasificacionRiesgoMaterialPeligroso!="" ? "'%" . $nombreClasificacionRiesgoMaterialPeligroso . "%'" : "NULL";
		
		$res = $conexion->ejecutarConsulta("SELECT 
												id_clasificacion_riesgo_material_peligroso,
												nombre_clasificacion_riesgo_material_peligroso, 
       											ruta_img_clasificacion_riesgo_material_peligroso
  											FROM 
												g_catalogos.clasificacion_riesgos_materiales_peligrosos where 
												($idClasificacionRiesgoMaterialPeligroso is NULL or id_clasificacion_riesgo_material_peligroso=$idClasificacionRiesgoMaterialPeligroso) and 
												($nombreClasificacionRiesgoMaterialPeligroso is NULL or quitar_caracteres_especiales(nombre_clasificacion_riesgo_material_peligroso) ilike $nombreClasificacionRiesgoMaterialPeligroso) 
											ORDER BY 2 ASC;");
	
			return $res;
	}
	

	public function buscarClasificacionRiesgoMaterialPeligrosoGuardar ($conexion,$nombreClasificacionRiesgoMaterialPeligroso ){

		$res = $conexion->ejecutarConsulta("SELECT
												id_clasificacion_riesgo_material_peligroso,
												nombre_clasificacion_riesgo_material_peligroso,
												ruta_img_clasificacion_riesgo_material_peligroso
											FROM
												g_catalogos.clasificacion_riesgos_materiales_peligrosos 
											WHERE
											    nombre_clasificacion_riesgo_material_peligroso='$nombreClasificacionRiesgoMaterialPeligroso';");
		return $res;
	}
	
	
	public function imprimirLineaMaterialPeligrosoClasificacionRiesgo($idClasificacionRiesgo, $nombreClasificacionRiesgo, $rutaClasificacionRiesgo){
		return '<tr id="R' . $idClasificacionRiesgo . '">' .
				'<td><img src='.$rutaClasificacionRiesgo.' style="no-repeat; width: 70px;"/></td>'.
				'<td width="100%">'.$nombreClasificacionRiesgo.'</td>' .
				'<td>'.
				'<form  class="borrar" data-rutaAplicacion="seguridadOcupacional" data-opcion="eliminarMaterialPeligrosoClasificacionRiesgo">' .
				'<input type="hidden" name="idMaterialPeligrosoClasificacionRiesgo" value="' . $idClasificacionRiesgo . '" >' .
				'<button type="submit" class="icono"></button>' .
				'</form>' .
				'</td>' .
				'</tr>';
	}
	
	public function eliminarMaterialPeligrosoClasificacionRiesgo($conexion, $idMaterialPeligrosoClasificacionRiesgo){
		$res = $conexion->ejecutarConsulta("DELETE FROM 
												g_catalogos.materiales_peligrosos_clasificacion_riesgos
 											WHERE 
												id_material_peligroso_clasificacion_riesgo= $idMaterialPeligrosoClasificacionRiesgo;");
		return $res;
	}
	
	public function guardarMaterialPeligrosoClasificacionRiesgo($conexion, $idMaterialPeligroso,$idClasificacionRiesgoMaterialPeligroso){
		$res = $conexion->ejecutarConsulta("INSERT INTO 
												g_catalogos.materiales_peligrosos_clasificacion_riesgos(
	 											id_material_peligroso,
												id_clasificacion_riesgo_material_peligroso)
											VALUES
												($idMaterialPeligroso,$idClasificacionRiesgoMaterialPeligroso) RETURNING id_material_peligroso_clasificacion_riesgo ;");
		return $res;
	}
	
	public function buscarMaterialPeligrosoClasificacionRiesgo($conexion, $idMaterialPeligroso,$idClasificacionRiesgoMaterialPeligroso){
		$res = $conexion->ejecutarConsulta("SELECT
												crmp.*,mppc.id_material_peligroso_clasificacion_riesgo
											FROM
												g_catalogos.materiales_peligrosos_clasificacion_riesgos mppc,
												g_catalogos.clasificacion_riesgos_materiales_peligrosos crmp
											WHERE
												mppc.id_clasificacion_riesgo_material_peligroso=crmp.id_clasificacion_riesgo_material_peligroso and
												mppc.id_material_peligroso='$idMaterialPeligroso' and 
												crmp.id_clasificacion_riesgo_material_peligroso=$idClasificacionRiesgoMaterialPeligroso;");
		return $res;
	}
	
	public function actualizarGuiaMaterialPeligroso ($conexion,$idGuiaMaterialPeligroso,$numeroGuiaMaterialPeligroso,$nombreGuiaMaterialPeligroso,$rutaGuiaMaterialPeligroso){
		$res = $conexion->ejecutarConsulta("UPDATE 
												g_catalogos.guias_materiales_peligrosos
											SET  
												numero_guia_material_peligroso=$numeroGuiaMaterialPeligroso,
												nombre_guia_material_peligroso='$nombreGuiaMaterialPeligroso', 
												ruta_guia_material_peligroso='$rutaGuiaMaterialPeligroso'
											WHERE 
												id_guia_material_peligroso=$idGuiaMaterialPeligroso;");
		return $res;
	}
	
	public function guardarClasificacionRiesgoMaterialPeligroso($conexion, $nombreClasificacionRiesgoMaterialPeligroso, $rutaClasificacionRiesgoMaterialPeligroso){
		$res = $conexion->ejecutarConsulta("INSERT INTO 
												g_catalogos.clasificacion_riesgos_materiales_peligrosos(
             									nombre_clasificacion_riesgo_material_peligroso, 
           										ruta_img_clasificacion_riesgo_material_peligroso)
											VALUES
												('$nombreClasificacionRiesgoMaterialPeligroso','$rutaClasificacionRiesgoMaterialPeligroso') ;");
		return $res;
	}
	
	public function actualizarClasificacionRiesgoMaterialPeligroso($conexion,$idClasificacionRiesgoMaterialPeligroso ,$nombreClasificacionRiesgoMaterialPeligroso, $rutaClasificacionRiesgoMaterialPeligroso){
		$res = $conexion->ejecutarConsulta("UPDATE 
												g_catalogos.clasificacion_riesgos_materiales_peligrosos
											SET 
												nombre_clasificacion_riesgo_material_peligroso='$nombreClasificacionRiesgoMaterialPeligroso',
												ruta_img_clasificacion_riesgo_material_peligroso='$rutaClasificacionRiesgoMaterialPeligroso'
											WHERE NOT EXISTS (SELECT crmp.id_clasificacion_riesgo_material_peligroso FROM g_catalogos.clasificacion_riesgos_materiales_peligrosos crmp
												WHERE crmp.nombre_clasificacion_riesgo_material_peligroso='$nombreClasificacionRiesgoMaterialPeligroso' and
													crmp.ruta_img_clasificacion_riesgo_material_peligroso='$rutaClasificacionRiesgoMaterialPeligroso')
											and id_clasificacion_riesgo_material_peligroso=$idClasificacionRiesgoMaterialPeligroso;");
		return $res;
	}
	
	public function buscarLaboratorioMaterialPeligroso ($conexion,$idLaboratorioMaterialPeligroso=null,$nombreLaboratorioMaterialPeligroso=null){
		
		$idLaboratorioMaterialPeligroso = $idLaboratorioMaterialPeligroso!="" ? "'" . $idLaboratorioMaterialPeligroso . "'" : "NULL";
		$nombreLaboratorioMaterialPeligroso = $nombreLaboratorioMaterialPeligroso!="" ? "'%" . $nombreLaboratorioMaterialPeligroso . "%'" : "NULL";
	
		$res = $conexion->ejecutarConsulta("SELECT 
												id_laboratorio,
												nombre_laboratorio,
												id_laboratorio_padre
											FROM 
												g_catalogos.laboratorios_materiales_peligrosos
											WHERE	
												($idLaboratorioMaterialPeligroso is NULL or id_laboratorio = $idLaboratorioMaterialPeligroso) and
												($nombreLaboratorioMaterialPeligroso is NULL or quitar_caracteres_especiales(nombre_laboratorio) ilike $nombreLaboratorioMaterialPeligroso) and
												id_laboratorio_padre is null 
											ORDER BY 2 ASC ;");
		return $res;
	}	
	
	public function buscarSubTipoLaboratorioMaterialPeligroso ($conexion,$idLaboratorioMaterialPeligroso,$nombreLaboratorioMaterialPeligroso=null){
	
		$idLaboratorioMaterialPeligroso = $idLaboratorioMaterialPeligroso!="" ? "'" . $idLaboratorioMaterialPeligroso . "'" : "NULL";
		$nombreLaboratorioMaterialPeligroso = $nombreLaboratorioMaterialPeligroso!="" ? "'%" . $nombreLaboratorioMaterialPeligroso . "%'" : "NULL";
	
		$res = $conexion->ejecutarConsulta("SELECT
												id_laboratorio,
												nombre_laboratorio,
												id_laboratorio_padre
											FROM
												g_catalogos.laboratorios_materiales_peligrosos
											WHERE
												($idLaboratorioMaterialPeligroso is NULL or id_laboratorio = $idLaboratorioMaterialPeligroso) and
												($nombreLaboratorioMaterialPeligroso is NULL or quitar_caracteres_especiales(nombre_laboratorio) ilike $nombreLaboratorioMaterialPeligroso)
											ORDER BY 2 ASC ;");
				return $res;
	}
	
	public function guardarLaboratorioMaterialPeligroso($conexion, $nombreLaboratorioMaterialPeligroso){
		$res = $conexion->ejecutarConsulta("INSERT INTO 
												g_catalogos.laboratorios_materiales_peligrosos(nombre_laboratorio)
											VALUES
												('$nombreLaboratorioMaterialPeligroso')RETURNING 
												id_laboratorio ;");
		return $res;
	}
	
	public function actualizarLaboratorioMaterialPeligroso($conexion,$idLaboratorioMaterialPeligroso ,$nombreLaboratorioMaterialPeligroso){

		$res = $conexion->ejecutarConsulta("UPDATE 
												g_catalogos.laboratorios_materiales_peligrosos
										  	SET  
												nombre_laboratorio='$nombreLaboratorioMaterialPeligroso'
												WHERE NOT EXISTS (SELECT lmp.id_laboratorio FROM g_catalogos.laboratorios_materiales_peligrosos lmp
														WHERE lmp.nombre_laboratorio='$nombreLaboratorioMaterialPeligroso' )
												and id_laboratorio=$idLaboratorioMaterialPeligroso;");
		return $res;
	}
	
	public function imprimirLineaSubtipoLaboratorio($idSubtipoLaboratorio, $nombreSubtipo, $idLaboratorio,  $ruta){
		return '<tr id="R' . $idSubtipoLaboratorio . '">' .
				'<td width="100%">' .
				$nombreSubtipo .
				'</td>' .
				'<td>' .
				'<form class="abrir" data-rutaAplicacion="'.$ruta.'" data-opcion="abrirSubTipoLaboratorioMaterialPeligroso" data-destino="detalleItem" data-accionEnExito="NADA" >' .
				'<input type="hidden" name="idSubtipoLaboratorio" value="' . $idSubtipoLaboratorio . '" >' .
				'<input type="hidden" name="idLaboratorio" value="' . $idLaboratorio . '" >' .
				'<button class="icono" type="submit" ></button>' .
				'</form>' .
				'</td>' .
				
				'</tr>';
	}
	
	public function listarSubtipoLaboratorio ($conexion, $idLaboratorio){
		$res = $conexion->ejecutarConsulta("SELECT 
												id_laboratorio,
												nombre_laboratorio,
												id_laboratorio_padre
  											FROM 
												g_catalogos.laboratorios_materiales_peligrosos
											WHERE 
												id_laboratorio_padre='$idLaboratorio'
											ORDER BY 2;");
				return $res;
	}
	
	public function buscarSubtipoLaboratorioXNombre ($conexion, $idLaboratorio,$nombreLaboratorio){
		

		$res = $conexion->ejecutarConsulta("SELECT 
												id_laboratorio,
												nombre_laboratorio,
												id_laboratorio_padre
											FROM 
												g_catalogos.laboratorios_materiales_peligrosos 
											WHERE 
												id_laboratorio_padre='$idLaboratorio' and 
												quitar_caracteres_especiales(nombre_laboratorio)
												ILIKE quitar_caracteres_especiales('%$nombreLaboratorio%');");
				return $res;
	}
	public function guardarSubTipoLaboratorioMaterialPeligroso($conexion, $nombreLaboratorioMaterialPeligroso,$idLaboratorio){
		
		$res = $conexion->ejecutarConsulta("INSERT INTO
												g_catalogos.laboratorios_materiales_peligrosos(nombre_laboratorio,id_laboratorio_padre)
											VALUES
												('$nombreLaboratorioMaterialPeligroso',$idLaboratorio)RETURNING
												id_laboratorio ;");
		return $res;
	}
	
	public function listaSubTipoLaboratoriosMaterialesPeligrosos ($conexion){
	
		$laboratorio = $conexion->ejecutarConsulta("SELECT
														id_laboratorio_padre,
														id_laboratorio,
														nombre_laboratorio
													FROM
														g_catalogos.laboratorios_materiales_peligrosos
													ORDER BY 2 asc;");
		
		
		while ($fila = pg_fetch_assoc($laboratorio)){
			$res[] = array(
					idLaboratorio=>$fila['id_laboratorio'],
					idLaboratorioPadre=>$fila['id_laboratorio_padre'],
					nombreLaboratorio=>$fila['nombre_laboratorio']);
		}
		
		return $res;
		
	}
}