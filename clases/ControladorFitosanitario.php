<?php

class ControladorFitosanitario
{	
	
	public function abrirExFitoArchivos($conexion, $idExFito){
		$cid = $conexion->ejecutarConsulta("SELECT
												id_fito_exportacion, 
				                                tipo_archivo, 
				    						    ruta_archivo, 
				 							    area,
												id_vue
											FROM
											    g_fito_exportacion.documentos_adjuntos
											WHERE
											    id_fito_exportacion = $idExFito;");
	
		while ($fila = pg_fetch_assoc($cid)){
			$res[] = array(
					id_fito_exportacion=>$fila['id_fito_exportacion'],					
					tipoArchivo=>$fila['tipo_archivo'],
					rutaArchivo=>$fila['ruta_archivo'],
					area=>$fila['area'],
					idVue=>$fila['id_vue']);
		}
	
		return $res;
	}
	
	public function listarFitoExportacionDetalle($conexion, $id_fito_exportacion){
		
			$cidFito = $conexion->ejecutarConsulta("SELECT 
														fd.*,
														o.nombre_representante,
														o.apellido_representante
													FROM
													     g_fito_exportacion.fito_exportaciones_operadores_productos fd,
													     g_operadores.operadores o
													where 
													     fd.id_fito_exportacion = $id_fito_exportacion and
													     fd.identificador_operador = o.identificador ;");
			
			while ($fila = pg_fetch_assoc($cidFito)){
				$res[] = array(
						idFito=>$fila['id_fito_exportacion'],
						identificador=>$fila['identificador_operador'],
						idProducto=>$fila['id_producto'],
						nombreProducto=>$fila['nombre_producto'],
						numeroBultos=>$fila['numero_bultos'],
						unidadBultos=>$fila['unidad_bultos'],
						cantidadProducto=>$fila['cantidad_producto'],
						unidadCantidadProducto=>$fila['unidad_cantidad_producto'],
						permisoMusaceas=>$fila['permiso_musaceas'],
						estado=>$fila['estado'],
						nombreRepresentante=>$fila['nombre_representante'],
						apellidoRepresentante=>$fila['apellido_representante']
				);
			}
				
			return $res;
		}
		
		public function listarFitoExportacionTransito($conexion, $id_fito_exportacion){
		
			$cidFito = $conexion->ejecutarConsulta("SELECT
														*
													FROM
														g_fito_exportacion.fito_exportaciones_transitos
													WHERE
														id_fito_exportacion = $id_fito_exportacion;");
						
					while ($fila = pg_fetch_assoc($cidFito)){
							$res[] = array(
										idPais=>$fila['id_pais_transito'],
										nombrePais=>$fila['nombre_pais_transito'],
										idPuerto=>$fila['id_puerto_transito'],
										nombrePuerto=>$fila['nombre_puerto_transito'],
										tipoTransporte=>$fila['tipo_transporte']);
			}
		
			return $res;
		}
	
	public function listarFitoExportacion($conexion, $idFitosanitarioExportacion){
				
		$res = $conexion->ejecutarConsulta("SELECT
				                               *
										    FROM 
				                               g_fito_exportacion.fito_exportaciones
										    where 
				                               id_fito_exportacion = $idFitosanitarioExportacion");
		return $res;
	}
	
	public function  generarNumeroSolicitud($conexion,$codigo){
			
		$res = $conexion->ejecutarConsulta("SELECT
												MAX(codigo_certificado) as numero
											FROM
												g_fito_exportacion.fito_exportaciones
											WHERE
												codigo_certificado LIKE '$codigo';");
		return $res;
	}
	
	public function listarExportacionesFitoOperador($conexion){
		$res = $conexion->ejecutarConsulta("select fe.*, 
				 (select 
				     count(id_fito_exp_operador_producto) 
                  from 
				     g_fito_exportacion.fito_exportaciones_operadores_productos 
				  where 
				     id_fito_exportacion = fe.id_fito_exportacion) as num_productos 
				  from g_fito_exportacion.fito_exportaciones fe");
	
				return $res;
	}
	
	public function listarPaisesAutorizadosOperador ($conexion){
		$res = $conexion->ejecutarConsulta("select distinct op.id_pais,
												op.nombre_pais 	
											from
												g_operadores.operaciones op,
												g_requisitos.requisitos_comercializacion rc,
												g_requisitos.requisitos_asignados ra
											where
												op.id_tipo_operacion in
													(select
													  id_tipo_operacion
													from 
													  g_catalogos.tipos_operacion 
													where nombre = 'Exportador' and id_area = 'SV') and
											rc.id_producto = op.id_producto and
											rc.id_requisito_comercio = ra.id_requisito_comercio and
											ra.tipo = 'Exportacion' and
											op.estado = 'registrado' 											
											order by 2 asc");	
				return $res;
	}	
	
	public function listarProductosOperador($conexion, $identificador){
		
  		$res = $conexion->ejecutarConsulta("select 
									            p.id_producto,
									            p.nombre_comun,
									            p.partida_arancelaria,
									            p.certificado_semillas,
									            p.licencia_magap,
									            rc.tipo,
									            rc.id_localizacion
									         from
									            g_operadores.operaciones op,
									            g_catalogos.productos p,
									            g_requisitos.requisitos_comercializacion rc
									         where
									            op.id_tipo_operacion in 
									             (select 
									              	id_tipo_operacion 
									              from 
									              	g_catalogos.tipos_operacion 
									              where 
									                nombre like '%Exportador%') and
									            	p.id_producto = op.id_producto and
									            	op.id_producto = rc.id_producto and
									            	op.identificador_operador='$identificador';");
	  return $res;
	}

	public function guardarFitoDocumentos($conexion, $id_fito_exportacion, $tipo_archivo, $ruta_archivo, $area,$idVue = null){
		
		$documento = $this->abrirFitoArchivoIndividual($conexion, $id_fito_exportacion, $tipo_archivo);
		
		if(pg_num_rows($documento)== 0){
		
			$res = $conexion->ejecutarConsulta("INSERT INTO g_fito_exportacion.documentos_adjuntos(
					id_fito_exportacion, tipo_archivo, ruta_archivo, area, id_vue)
					VALUES (
					'$id_fito_exportacion', '$tipo_archivo', '$ruta_archivo', '$area', '$idVue');");
				
			return $res;
		}
	}
	
	public function abrirFitoArchivoIndividual($conexion, $idFitosanitarioExportacion, $tipoArchivo){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_fito_exportacion.documentos_adjuntos
											WHERE
												id_fito_exportacion = $idFitosanitarioExportacion
												and tipo_archivo = '$tipoArchivo';");
	
		return $res;
	}

	public function guardarFitoProductos($conexion, $id_fito_exportacion, $identificador_operador, $id_producto, $nombre_producto, $numero_bultos, $unidad_bultos, $cantidad_producto, $unidad_cantidad_producto,  $permiso_musaceas, $subPartidaProducto=null, $codigoProducto=null) {	
		
		$res = $conexion->ejecutarConsulta("INSERT INTO g_fito_exportacion.fito_exportaciones_operadores_productos(
								            	id_fito_exportacion, identificador_operador,  id_producto, nombre_producto, numero_bultos, unidad_bultos, cantidad_producto, 
								        		unidad_cantidad_producto, permiso_musaceas, subpartida_producto_vue, codigo_producto_vue)
								    		VALUES (
												'$id_fito_exportacion', '$identificador_operador', '$id_producto', '$nombre_producto', 
								            	'$numero_bultos', '$unidad_bultos', '$cantidad_producto', '$unidad_cantidad_producto', 
								            	'$permiso_musaceas', '$subPartidaProducto', '$codigoProducto');");
											
		return $res;
	}
	
	public function guardarFitoTransito($conexion, $id_fito_exportacion, $idPuertoTransito, $nombrePuertoTransito, $idPaisTransito, $nombrePaisTransito, $tipoTransporte) {
	
		$res = $conexion->ejecutarConsulta("INSERT INTO g_fito_exportacion.fito_exportaciones_transitos(
				id_fito_exportacion, id_pais_transito,  nombre_pais_transito, id_puerto_transito, nombre_puerto_transito, tipo_transporte)
				VALUES ('$id_fito_exportacion', $idPaisTransito, '$nombrePaisTransito', $idPuertoTransito,'$nombrePaisTransito', '$tipoTransporte');");
			
		return $res;
	}
	
/*	public function guardarFitoProveedores($conexion, $id_fito_exportacion, $id_proveedor, $estado, $observacion, $ruta_archivo)
	{
		$res = $conexion->ejecutarConsulta("INSERT INTO g_fito_exportacion.fito_exportaciones_proveedores
					(id_fito_exportacion, id_proveedor, estado, observacion, ruta_archivo)
				VALUES (
				     '$id_fito_exportacion', '$id_proveedor', '$estado', '$observacion', '$ruta_archivo');");
					
				return $res;
	}*/
	
	/*public function guardarFitoExportacion($conexion, $nombre_importador, $direccion_importador, $id_pais, $pais_importacion, $identificador_operador, $nombre_embarcador, 
											$nombre_marcas, $id_puerto_destino, $puerto_destino, $id_puerto_embarque, $puerto_embarque, $transporte, $fecha_embarque, 
											$numero_viaje, $tratamiento_realizado, $duracion_tratamiento, $temperatura_tratamiento, $fecha_tratamiento, $quimico_tratamiento, 
											$concentracion_producto, $id_provincia, 
													$provincia, $id_ciudad, $ciudad, $observacion_operador,
													$reporte_inspeccion, $codigo_certificado, $id_vue, 
													$estado)
	{	
		
		$res = $conexion->ejecutarConsulta("INSERT INTO g_fito_exportacion.fito_exportaciones
					(nombre_importador, direccion_importador, id_pais, pais_importacion, identificador_operador, nombre_embarcador, 
					nombre_marcas, id_puerto_destino, puerto_destino,
					id_puerto_embarque, puerto_embarque, transporte, 
					fecha_embarque, numero_viaje, tratamiento_realizado, 
					duracion_tratamiento, temperatura_tratamiento, fecha_tratamiento, 
					quimico_tratamiento, concentracion_producto, id_provincia, 
					provincia, id_ciudad, ciudad, observacion_operador,
					reporte_inspeccion, codigo_certificado, id_vue, 
					estado)
	            VALUES 
				    ('$nombre_importador', '$direccion_importador', '$id_pais', '$pais_importacion', '$identificador_operador', '$nombre_embarcador', 
					'$nombre_marcas', '$id_puerto_destino', '$puerto_destino',
					'$id_puerto_embarque', '$puerto_embarque', '$transporte', 
					'$fecha_embarque', '$numero_viaje', '$tratamiento_realizado', 
					'$duracion_tratamiento', '$temperatura_tratamiento', '$fecha_tratamiento', 
					'$quimico_tratamiento', '$concentracion_producto', '$id_provincia', 
					'$provincia', '$id_ciudad', '$ciudad', '$observacion_operador',
					'$reporte_inspeccion', '$codigo_certificado', '$id_vue', 
					'$estado') RETURNING id_fito_exportacion;");
					
				return $res;
	}*/
	
public function guardarFitoExportacion($conexion, $nombreImportador, 
					                    $direccionImportador, $idPaisDestino, $paisDestino, $nombreAgenciaCarga, 
					                    $nombreMarcas, $idPuertoDestino, $puertoDestino, $idPuertoEmbarque, 
					                    $puertoEmbarque, $transporte, $fechaEmbarque, $numeroViaje, $tratamientoRealizado, 
					                    $duracionTratamiento, $temperaturaTratamiento, $fechaTratamiento, 
					                    $quimicoTratamiento, $concentracionProducto, $idProvincia, $Provincia, 
					                    $observacionOperador, $idPaisEmbarque, $paisEmbarque, 
					                    $idPaisOrigen, $paisOrigen, $numeroReporteInspeccion, $lugarInspeccion ,$unidadTemperatura ,
										$identificadorSolicitante, $productoOrganico, $numeroProductoOrganico, $idVue=null){
		
  $res = $conexion->ejecutarConsulta("INSERT INTO g_fito_exportacion.fito_exportaciones(
								                      nombre_importador, 
								                      direccion_importador, id_pais_destino, pais_destino, nombre_agencia_carga, 
								                      nombre_marcas, id_puerto_destino, puerto_destino, id_puerto_embarque, 
								                      puerto_embarque, transporte, fecha_embarque, numero_viaje, tratamiento_realizado, 
								                      duracion_tratamiento, temperatura_tratamiento, fecha_tratamiento, 
								                      quimico_tratamiento, concentracion_producto, id_provincia, provincia, 
								                      observacion_operador, estado, id_pais_embarque, pais_embarque, 
								                      id_pais_origen, pais_origen,reporte_inspeccion,lugar_inspeccion,unidad_temperatura,
  													  identificador_solicitante,id_vue,producto_organico,numero_producto_organico)
								              VALUES ('$nombreImportador', 
								                      '$direccionImportador', $idPaisDestino, '$paisDestino', '$nombreAgenciaCarga', 
								                      '$nombreMarcas', $idPuertoDestino, '$puertoDestino', $idPuertoEmbarque, 
								                      '$puertoEmbarque', '$transporte', '$fechaEmbarque', '$numeroViaje', '$tratamientoRealizado', 
								                      '$duracionTratamiento', $temperaturaTratamiento, $fechaTratamiento, 
								                      '$quimicoTratamiento', '$concentracionProducto', $idProvincia, '$Provincia', 
								                      '$observacionOperador', 'enviado', $idPaisEmbarque, '$paisEmbarque', 
								                      $idPaisOrigen, '$paisOrigen', '$numeroReporteInspeccion', '$lugarInspeccion','$unidadTemperatura',
  													 '$identificadorSolicitante','$idVue','$productoOrganico','$numeroProductoOrganico') RETURNING id_fito_exportacion;");
             
  return $res;
 }
	
	
	public function listaProveedores($conexion){
		$cidProveedores = $this->Proveedores($conexion);
		while ($fila = pg_fetch_assoc($cidProveedores)){
			$res[] = array(identificador_operador=>$fila['identificador_operador'], razon_social=>$fila['razon_social'], id_pais=>$fila['id_pais']);
		}
		return $res;
	}
	
	public function Proveedores($conexion)
	{
		$res = $conexion->ejecutarConsulta("select distinct 
				                               op.identificador_operador, 
				                               od.razon_social,
				                               op.id_pais 
											from    g_operadores.operaciones op,
												g_requisitos.requisitos_comercializacion rc,
												g_requisitos.requisitos_asignados ra,
												g_operadores.operadores od
											where
												op.id_tipo_operacion in
													(select
													  id_tipo_operacion
													from 
													  g_catalogos.tipos_operacion 
													where nombre = 'Exportador' and id_area = 'SV') and
											rc.id_producto = op.id_producto and
											rc.id_requisito_comercio = ra.id_requisito_comercio and
											ra.tipo = 'Exportacion' and
											op.estado = 'registrado' and 
											op.identificador_operador = od.identificador");
		return $res;
	}
	
	public function listaProductos($conexion){
		$cidProductos = $this->Productos($conexion);
		while ($fila = pg_fetch_assoc($cidProductos)){
			$res[] = array(id_producto=>$fila['id_producto'], nombre_producto=>$fila['nombre_producto'], identificador_operador=>$fila['identificador_operador']);
		}
		return $res;
	}
	
	public function Productos($conexion)
	{	
		$res = $conexion->ejecutarConsulta("select distinct op.id_producto,
											    op.nombre_producto,
												op.identificador_operador 
											from 
				                                g_operadores.operaciones op,
											    g_requisitos.requisitos_comercializacion rc,
												g_requisitos.requisitos_asignados ra
											where
												op.id_tipo_operacion in
													(select
													  id_tipo_operacion
													from 
													  g_catalogos.tipos_operacion 
													where nombre = 'Exportador' and id_area = 'SV') and
												rc.id_producto = op.id_producto and
												rc.id_requisito_comercio = ra.id_requisito_comercio and
												ra.tipo = 'Exportacion' and
												op.estado = 'registrado' ");
		return $res;
	}

	public function listarLocalizacionOrigen($conexion,$categoria, $origen){
	
		$busqueda = '';
		switch ($categoria){
			case 'PAIS': $busqueda = 'categoria = 0'; break;
			case 'PROVINCIAS': $busqueda = 'categoria = 1'; break;
			case 'CANTONES': $busqueda = 'categoria = 2'; break;
			case 'SITIOS': $busqueda = 'categoria = 3'; break;
			case 'PARROQUIAS': $busqueda = 'categoria = 4'; break;
		}
			
		$res = $conexion->ejecutarConsulta("select *
											from g_catalogos.localizacion
											where " . $busqueda ."
				                            and nombre = '".$origen."'");
		return $res;
	}
		
	
////////////////////////////// ASIGNACION DE FITO A INSPECTORES PARA REVISION ///////////////////////////////////
	
	/*filtro de operaciones por provincia por asignar*/
	public function listarFitoRevisionProvinciaRS ($conexion, $estado, $provincia, $medioTransporte){
	
		$res = $conexion->ejecutarConsulta("select
												distinct f.id_fito_exportacion as id_solicitud,
												f.pais_destino as pais,
												f.estado,
												f.id_vue
											from
												g_fito_exportacion.fito_exportaciones f
											where
												f.estado in ('$estado') and
												UPPER(f.provincia) = UPPER('$provincia') and
												transporte = '$medioTransporte'
											order by 1 asc;");
		return $res;
	}

	public function listarFitoAsignadasInspectorRS ($conexion, $estado, $identificadorInspector, $tipoSolicitud, $tipoInspector){
	
		$res = $conexion->ejecutarConsulta("select
												distinct f.id_fito_exportacion as id_solicitud,
												f.pais_destino as pais,
												f.estado,
												f.id_vue
											from
												g_fito_exportacion.fito_exportaciones f,
												g_revision_solicitudes.asignacion_coordinador ac
											where
												f.id_fito_exportacion = ac.id_solicitud and
												ac.tipo_solicitud = '$tipoSolicitud' and
												ac.tipo_inspector = '$tipoInspector' and
												ac.identificador_inspector = '$identificadorInspector' and
												f.estado in ('$estado')
											order by 1 asc;");
		return $res;
	}
	
	////////////////////////////// REVISION DE FITO EN FINANCIERO ///////////////////////////////////
	
	/*filtro de fito por estado*/
	public function listarFitoRevisionFinancieroRS ($conexion, $nombreProvincia, $estado='pago'){
	
		$res = $conexion->ejecutarConsulta("select
												distinct f.id_fito_exportacion as id_solicitud,
												ifp.identificador_operador,
												f.estado,
												f.pais_destino as pais,
												f.id_vue
											from
												g_fito_exportacion.fito_exportaciones f,
												g_fito_exportacion.fito_exportaciones_operadores_productos ifp
											where
												f.id_fito_exportacion = ifp.id_fito_exportacion and
												UPPER(f.provincia) = UPPER('$nombreProvincia') and
												f.estado in ('$estado')
											order by 1 asc;");
		return $res;
	}
	
	public function listarFitosanitarioExportacionFinancieroVerificacion ($conexion, $estado, $nombreProvincia, $tipoSolicitud){
	
		$res = $conexion->ejecutarConsulta("SELECT
												distinct f.id_fito_exportacion as id_solicitud,
												ifp.identificador_operador,
												f.estado,
												'Fitosanitario de exportación' as tipo_certificado,
												f.pais_destino as pais,
												o.razon_social, o.nombre_representante, o.apellido_representante,
												f.id_vue
											FROM
												g_fito_exportacion.fito_exportaciones f,
												g_operadores.operadores o,
												g_fito_exportacion.fito_exportaciones_operadores_productos ifp,
												g_revision_solicitudes.asignacion_inspector ai,
												g_revision_solicitudes.grupos_solicitudes gs,
												g_financiero.orden_pago orp
											WHERE
												f.id_fito_exportacion = ifp.id_fito_exportacion and
												f.id_fito_exportacion = gs.id_solicitud and
												ai.id_grupo = gs.id_grupo and
												ifp.identificador_operador = o.identificador and
												UPPER(f.provincia) = UPPER('$nombreProvincia') and
												f.estado in ('$estado') and
												ai.tipo_solicitud = '$tipoSolicitud' and
												ai.tipo_inspector = 'Financiero' and
												gs.estado != 'Verificación' and
												orp.id_grupo_solicitud = ai.id_grupo and
												orp.estado = 3 and
												orp.tipo_solicitud = '$tipoSolicitud';");
		return $res;
	}
	
	
	////////////////////// EVALUACION DE PRODUCTOS FITO //////////////////////////
	/*OK*/
	public function enviarFito ($conexion, $idFito, $estado){
		$res = $conexion->ejecutarConsulta("update
												g_fito_exportacion.fito_exportaciones
											set
												estado = '$estado'
											where
												id_fito_exportacion = $idFito;");
		return $res;
	}
	
	public function enviarFitosanitarioProductos ($conexion, $idFito, $estado){
		$res = $conexion->ejecutarConsulta("update
												g_fito_exportacion.fito_exportaciones_operadores_productos
											set
												estado = '$estado'
											where
												id_fito_exportacion = $idFito;");
		return $res;
	}
	
	/*OK*/
	public function evaluarProductosFito ($conexion, $idFito, $estado){
		$res = $conexion->ejecutarConsulta("update
												g_fito_exportacion.fito_exportaciones_operadores_productos
											set
												estado = '$estado'
											where
												id_fito_exportacion = $idFito;");
		return $res;
	}
	
	
	//////////////////////////////////////////////////////////////////////////
	
	public function buscarFitoVUE ($conexion, $idVue){
				
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_fito_exportacion.fito_exportaciones
											where
												id_vue = '$idVue';");
		return $res;
	}	
	
	public function actualizarFito($conexion, $idFito, $nombreImportador, $direccionImportador, $idPaisDestino, $paisDestino, $nombreAgenciaCarga, $nombreMarcas, $idPuertoDestino, $puertoDestino,
	         $idPuertoEmbarque,$puertoEmbarque, $transporte, $fechaEmbarque, $numeroViaje, $tratamientoRealizado, $duracionTratamiento, $temperaturaTratamiento,
	         $fechaTratamiento, $quimicoTratamiento, $concentracionProducto, $idProvincia, $provincia, $observacionOperador, $idPaisEmbarque,
	         $paisEmbarque, $idPaisOrigen, $paisOrigen, $numeroReporteInspeccion, $lugarInspeccion, $unidadTemperatura,$identificadorSolicitante,$estado, $productoOrganico, $numeroProductoOrganico){
	  
	  		
		$res = $conexion->ejecutarConsulta("UPDATE
											g_fito_exportacion.fito_exportaciones
										  SET
											nombre_importador='$nombreImportador', direccion_importador='$direccionImportador', id_pais_destino=$idPaisDestino,
											pais_destino='$paisDestino', nombre_agencia_carga='$nombreAgenciaCarga', nombre_marcas='$nombreMarcas',
											id_puerto_destino=$idPuertoDestino, puerto_destino='$puertoDestino', id_puerto_embarque=$idPuertoEmbarque,
											puerto_embarque='$puertoEmbarque', transporte='$transporte', fecha_embarque='$fechaEmbarque',
											numero_viaje='$numeroViaje', tratamiento_realizado='$tratamientoRealizado', duracion_tratamiento='$duracionTratamiento',
											temperatura_tratamiento=$temperaturaTratamiento, fecha_tratamiento=$fechaTratamiento,
											quimico_tratamiento='$quimicoTratamiento', concentracion_producto='$concentracionProducto',
											id_provincia=$idProvincia, provincia='$provincia',
											observacion_operador= '$observacionOperador', id_pais_embarque=$idPaisEmbarque, pais_embarque='$paisEmbarque',
											id_pais_origen=$idPaisOrigen, pais_origen='$paisOrigen', reporte_inspeccion = '$numeroReporteInspeccion',
	  										lugar_inspeccion = '$lugarInspeccion', unidad_temperatura='$unidadTemperatura' ,
	  										identificador_solicitante = '$identificadorSolicitante',estado = '$estado',
	  										producto_organico = '$productoOrganico', numero_producto_organico = '$numeroProductoOrganico'
										  WHERE
											id_fito_exportacion = $idFito;");
	 
	    return $res;
	 }
	 
	 
	 public function modificarFitosanitario($conexion, $idFito, $nombreImportador, $direccionImportador, $idPuertoDestino, $puertoDestino){
	 	 
	 	$res = $conexion->ejecutarConsulta("UPDATE
									 			g_fito_exportacion.fito_exportaciones
									 		SET
									 			nombre_importador='$nombreImportador', direccion_importador='$direccionImportador', 
									 			id_puerto_destino=$idPuertoDestino, puerto_destino='$puertoDestino'
									 		WHERE
									 			id_fito_exportacion = $idFito;");
	 
	 	return $res;
	 }
	
	public function eliminarProductosFito($conexion, $idFito){
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_fito_exportacion.fito_exportaciones_operadores_productos
											WHERE
												id_fito_exportacion = $idFito;");
	
		return $res;
	}
	
	public function eliminarArchivosAdjuntos($conexion, $idFito, $idVue){
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_fito_exportacion.documentos_adjuntos
											WHERE
												id_fito_exportacion = $idFito
												and id_vue = '$idVue';");
	
		return $res;
	}
	
	public function enviarFechaVigenciaFito ($conexion, $idFito){
		$res = $conexion->ejecutarConsulta("update
												g_fito_exportacion.fito_exportaciones
											set
												fecha_inicio = now(),
												fecha_vigencia = now() + interval '90' day
											where
												id_fito_exportacion = $idFito;");
		return $res;
	}
	
	public function asignarDocumentoRequisitosFitosanitario ($conexion, $idFito, $informeRequisitos){
				
		$res = $conexion->ejecutarConsulta("UPDATE
												g_fito_exportacion.fito_exportaciones
											SET
												informe_requisitos = '$informeRequisitos'
											WHERE
												id_fito_exportacion = $idFito;");
		return $res;
	}
	
	public function abrirFitoExportacionReporte ($conexion, $idFito){
		$cid = $conexion->ejecutarConsulta("select
				i.*,
				ip.*,
				p.partida_arancelaria,
				i.estado as estado_exportacion,
				ip.estado as estado_producto,
				ip.observacion as observacion_producto
				from
				g_fito_exportacion.fito_exportaciones i,
				g_fito_exportacion.fito_exportaciones_operadores_productos ip,
				g_catalogos.productos p
				where
				i.id_fito_exportacion = ip.id_fito_exportacion and
				i.id_fito_exportacion = $idFito and
				ip.id_producto = p.id_producto;");
	
				while ($fila = pg_fetch_assoc($cid)){
				$res[] = array(
						idFitosanitario=>$fila['id_fito_exportacion'],
						identificador=>$fila['identificador_operador'],
						nombreEmbarcador=>$fila['nombre_embarcador'],
						paisEmbarque=>$fila['pais_embarque'],
						puertoEmbarque=>$fila['puerto_embarque'],
						puertoDestino=>$fila['puerto_destino'],
						idVue=>$fila['id_vue'],
						tipoCertificado=>$fila['tipo_certificado'],
						observacionImportacion=>$fila['observacion_importacion'],
						fechaInicio=>$fila['fecha_inicio'],
						fechaVigencia=>$fila['fecha_vigencia'],
				
						idProducto=>$fila['id_producto'],
						nombreProducto=>$fila['nombre_producto'],
						estadoProducto=>$fila['estado_producto'],
						observacionProducto=>$fila['observacion_producto'],
				
						nombreImportador=>$fila['nombre_importador'],
						direccionImportador=>$fila['direccion_importador'],
						idPais=>$fila['id_pais_destino'],
						pais=>$fila['pais_destino'],
	
						partidaArancelaria=>$fila['partida_arancelaria']
				);
		}
	
		return $res;
	}
	
	public function abrirFitoAsignacion ($conexion, $idSolicitud){
			
		$res = $conexion->ejecutarConsulta("select
												i.id_fito_exportacion as id_solicitud,
												i.pais_destino as pais,
												i.estado,
												i.nombre_agencia_carga as razon_social
											from
												g_fito_exportacion.fito_exportaciones i
											where
												i.id_fito_exportacion = $idSolicitud");
	
				return $res;
	}
	
	public function abrirFitoProductosAsignacion ($conexion, $idSolicitud){
	
	$cid = $conexion->ejecutarConsulta("select
											nombre_producto
										from
											g_fito_exportacion.fito_exportaciones_operadores_productos
										where
											id_fito_exportacion = $idSolicitud");
	
			while ($fila = pg_fetch_assoc($cid)){
			$prod[] = $fila['nombre_producto'];
	}
	
	$res = implode(', ',$prod);
	
	return $res;
	}
	
	//Reporte consolidado
	public function listarReporteProveedoresProductos($conexion, $idSolicitud){
	
		$cid = $conexion->ejecutarConsulta("select
												op.*,
												o.razon_social
											from
												g_fito_exportacion.fito_exportaciones f,
												g_fito_exportacion.fito_exportaciones_operadores_productos op,
												g_operadores.operadores o
											where
												f.id_fito_exportacion = op.id_fito_exportacion and
												f.id_fito_exportacion = $idSolicitud and
												op.identificador_operador = o.identificador;");
	
		while ($fila = pg_fetch_assoc($cid)){
			$res[] = array(identificadorOperador=>$fila['identificador_operador'],razonSocial=>$fila['razon_social'],
					nombreProducto=>$fila['nombre_producto'], numeroBultos=>$fila['numero_bultos'],
					unidadBultos=>$fila['unidad_bultos']);
		}
	
		return $res;
	}
	
	public function listarReporteTotalProductos($conexion, $idSolicitud){
	
		$cid = $conexion->ejecutarConsulta("select
												op.nombre_producto, sum(op.numero_bultos) as numero_bultos, sum(op.cantidad_producto) as cantidad_producto
											from
												g_fito_exportacion.fito_exportaciones_operadores_productos op
											where
												op.id_fito_exportacion = $idSolicitud
											group by
												op.nombre_producto;");
	
				while ($fila = pg_fetch_assoc($cid)){
				$res[] = array(nombreProducto=>$fila['nombre_producto'], numeroBultos=>$fila['numero_bultos'],
						cantidadProducto=>$fila['cantidad_producto']);
		}
	
		return $res;
	}
	
	public function eliminaTransitoFitosanitario($conexion, $idFitosanitario){
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_fito_exportacion.fito_exportaciones_transitos
											WHERE
												id_fito_exportacion = $idFitosanitario;");
	
		return $res;
	}
	
	public function buscarFitosanitarioTransporte ($conexion, $idFitosanitarioExportacion, $idPais, $idPuerto, $tipoTransporte){
	
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_fito_exportacion.fito_exportaciones_transitos
											where
												id_fito_exportacion = $idFitosanitarioExportacion and
												id_pais_transito = $idPais and
												id_puerto_transito = $idPuerto and
												tipo_transporte = '$tipoTransporte';");
		return $res;
	}
	
	public function buscarFitosanitarioProducto($conexion, $idFitosanitarioExportacion, $idProducto){
	
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_fito_exportacion.fito_exportaciones_operadores_productos
											where
												id_fito_exportacion = $idFitosanitarioExportacion and
												id_producto = $idProducto;");
		return $res;
	}
	
	public function listarProductosFitosanitarios ($conexion, $idFitosanitario){
	
		$res = $conexion->ejecutarConsulta("select
												*	
											from
												g_fito_exportacion.fito_exportaciones_operadores_productos
											where
												id_fito_exportacion = $idFitosanitario");
		
		return $res;
	}
	
	public function  listarFitosanitarioPorEstado($conexion, $estado){
			
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_fito_exportacion.fito_exportaciones
											WHERE
												estado = '$estado';");
		return $res;
	}
	
}