<?php

class ControladorMovilizacionAnimal{
	// Funciones para el listado autorizado
public function listaTipoAutorizado($conexion){
		$tipoAutorizado = $conexion->ejecutarConsulta("select id_tipo_autorizado, nombre_autorizado, tipo_movilizacion, estado
											from g_catalogos.tipo_autorizados
											where estado = 'activo'
											");
									while ($fila = pg_fetch_assoc($tipoAutorizado)){
										$res[] = array(
												id_tipo_autorizado=>$fila['id_tipo_autorizado'],
												nombre_autorizado=>$fila['nombre_autorizado'],
												tipo_movilizacion=>$fila['tipo_movilizacion'],
												estado=>$fila['estado']
										);
									}
		return $res;
	}	
	
	
	
	public function listaAutorizado($conexion, $tipoAutorizado, $tipoVdro, $txtVdro){
		$busquedaVdr = '';
	
		//propietario
		if($tipoAutorizado==1){
			switch ($tipoVdro){
				case 1: $busquedaVdr = "o.identificador = '".$txtVdro."'"; break;
				case 2: $busquedaVdr = "UPPER(o.apellido_representante) like '%".strtoupper($txtVdro)."%'"; break;
			}
				
			$res = $conexion->ejecutarConsulta("select distinct o.identificador
												, (o.nombre_representante ||' '|| o.apellido_representante) nombre_autorizado
												from g_operadores.operadores o,
												g_operadores.operaciones r,
												g_catalogos.tipos_operacion t
												where o.identificador = r.identificador_operador
												and r.id_tipo_operacion = t.id_tipo_operacion
												and t.id_area = 'SA'
												and t.nombre = 'Productor'
					                            and ".$busquedaVdr." ;");
		}
		//autorizado
		if($tipoAutorizado==2){
			switch ($tipoVdro){
				case 1: $busquedaVdr = "identificador_propietario = '".$txtVdro."'"; break;
				case 2: $busquedaVdr = "UPPER(o.apellido_representante) like '%".strtoupper($txtVdro)."%'"; break;
			}
			$res = $conexion->ejecutarConsulta("select distinct identificador_propietario identificador
												, nombre_autorizado
												from g_movilizacion_animal.autorizar_movilizaciones
												where estado = 'activo'
					                            and ".$busquedaVdr." ;");
		}
			
		return $res;
	}
	
	
	//lugares de emision de movilizacion
	public function lugarEmisionMovilizacionAnimal($conexion, $identificacion){		
		$res = $conexion->ejecutarConsulta("select r.id_responsable_movilizacion
											, r.id_tipo_lugar_emision
											, r.identificador_emisor
											, r.identificador_autoservicio
											, (o.nombre_representante || ' ' || o.apellido_representante) nombre_emisor
											, r.nombre_emisor_movilizacion
											, r.nombre_lugar_emision
											, r.id_provincia
											, r.provincia
											, r.id_canton
											, r.canton
											, r.id_parroquia
											, r.parroquia 													
											from g_movilizacion_animal.responsable_movilizaciones r
											, g_operadores.operadores o
											where o.identificador = r.identificador_emisor
											and r.estado = 'activo'
											and r.identificador_emisor = '$identificacion';");		
		return $res;	
	}
		
	//lugares de emision de movilizacion autoservicio
	public function lugarEmisionEmpresa($conexion, $identificacion){
		$res = $conexion->ejecutarConsulta("select distinct r.id_tipo_lugar_emision
											, r.identificador_emisor
											, r.identificador_autoservicio
											, (o.nombre_representante || ' ' || o.apellido_representante) nombre_emisor
											, r.nombre_emisor_movilizacion
											, r.nombre_lugar_emision
											, r.id_provincia
											, r.provincia
											from g_movilizacion_animal.responsable_movilizaciones r
											, g_operadores.operadores o
											where o.identificador = r.identificador_emisor
											and r.estado = 'activo'
											and r.identificador_emisor = '$identificacion';");
		return $res;
	}
	
	//lugares de emision de movilizacion normal
	public function lugarEmisionFuncionario($conexion, $identificacion){
		$res = $conexion->ejecutarConsulta("select  r.id_tipo_lugar_emision
											, r.identificador_emisor
											, r.identificador_autoservicio
											--, (o.nombre_representante || ' ' || o.apellido_representante) nombre_emisor
											, r.nombre_emisor_movilizacion
											, r.nombre_lugar_emision
											, r.id_provincia
											, r.provincia
											from g_movilizacion_animal.responsable_movilizaciones r
											--, g_operadores.operadores o
											where --o.identificador = r.identificador_emisor
											--and 
											id_tipo_lugar_emision='1'
											 and r.estado = 'activo'
				and r.identificador_emisor = '$identificacion';");
		return $res;
	}
	
	
	//tipo de movilización si es de origen o si es destino
	public function tipoMovilizacionAnimal($conexion, $origenMovilizacion){
		$busqueda = '';
		switch ($origenMovilizacion){
			case 'origen': $busqueda = "lugar_origen = 'origen'"; break;
			case 'destino': $busqueda = "lugar_destino = 'destino'"; break;
		}
		
		$res = $conexion->ejecutarConsulta("select id_tipo_movilizacion_animales
											, lugar_movilizacion_animal
											, lugar_origen
											, lugar_destino
											, estado
											from g_catalogos.tipo_movilizacion_animales
											where estado = 'activo' 
											and ".$busqueda.";");
		return $res;
	}
	
	// lista de Autorizados por terceros
	
	//Guardar movilización animal
	public function guardarMovilizacionAnimal($conexion, $numero_certificado, $id_tipo_autorizado,$identificador_autorizado,$lugar_emision
				,$id_tipo_movilizacion_origen,$id_sitio_origen,$id_area_origen,$id_tipo_movilizacion_destino,$id_sitio_destino
				,$id_area_destino,$medio_transporte,$placa,$identificacion_conductor,$descripcion_transporte,$usuario_empresa,$usuario_responsable,$cantidad,$costo
				,$total,$estado,$observacion,$ruta_numero_certificado,$hora,$fecha_movilizacion_desde, $fecha_movilizacion_hasta,  $codigo_provincia_origen,$codigo_provincia_destino,$secuencia_certificado_movilizacion)
	{
	
		
		$fecha_registro = date('Y-m-d H:i:s', (strtotime ("-1 Hours")));
	
	
		$res = $conexion->ejecutarConsulta("insert into g_movilizacion_animal.movilizacion_animales(
				numero_certificado, id_tipo_autorizado, identificador_autorizado, lugar_emision
				, id_tipo_movilizacion_origen, id_sitio_origen, id_area_origen, id_tipo_movilizacion_destino, id_sitio_destino
				, id_area_destino, medio_transporte, placa, identificacion_conductor
				, descripcion_transporte, usuario_empresa, usuario_responsable, cantidad, costo, total
				, estado, observacion, ruta_numero_certificado, fecha_registro, hora, fecha_movilizacion_desde, fecha_movilizacion_hasta,codigo_provincia_origen, 
       codigo_provincia_destino, serie_certificado_movilizacion) 
				values ('$numero_certificado','$id_tipo_autorizado','$identificador_autorizado','$lugar_emision'
				,'$id_tipo_movilizacion_origen','$id_sitio_origen','$id_area_origen','$id_tipo_movilizacion_destino','$id_sitio_destino'
				,'$id_area_destino','$medio_transporte','$placa','$identificacion_conductor'
				,'$descripcion_transporte','$usuario_empresa','$usuario_responsable','$cantidad','$costo','$total'
				,'$estado','$observacion','$ruta_numero_certificado','$fecha_registro','$hora','$fecha_movilizacion_desde', '$fecha_movilizacion_hasta','$codigo_provincia_origen','$codigo_provincia_destino','$secuencia_certificado_movilizacion')
				RETURNING id_movilizacion_animal");
		return $res;			
	}

	//Guardar movilización animal detalle
	public function guardarMovilizacionAnimalDetalle($conexion, $id_movilizacion_animal, $id_especie, $nombre_especie
	, $id_producto, $cantidad, $costo, $total, $observacion, $numero_certificado, $fecha_certificado)
	
	
	{
		
		if($numero_certificado == 'Ninguno'){		
			$res = $conexion->ejecutarConsulta("insert into g_movilizacion_animal.movilizacion_animal_detalles(
						id_movilizacion_animal, id_especie, nombre_especie
						, id_producto, cantidad, costo, total
						, observacion, numero_certificado)
						values ('$id_movilizacion_animal', '$id_especie', '$nombre_especie'
						, '$id_producto', $cantidad, $costo, $total, '$observacion','$numero_certificado')
						RETURNING id_movilizacion_animal_detalle");
						return $res;
		}
		else
		{			
			$res = $conexion->ejecutarConsulta("insert into g_movilizacion_animal.movilizacion_animal_detalles(
						id_movilizacion_animal, id_especie, nombre_especie
						, id_producto, cantidad, costo, total
						, observacion, numero_certificado, fecha_certificado)
						values ('$id_movilizacion_animal', '$id_especie', '$nombre_especie'
						, '$id_producto', $cantidad, $costo, $total
						, '$observacion', '$numero_certificado', '$fecha_certificado')
						RETURNING id_movilizacion_animal_detalle");
				return $res;
		}
	}
	
	// Función para actualizar el la cantidad detalle movilizacion - ticket
	public function actualizarDetalleMovilizacionTicket($conexion, $id_movilizacion_animal_detalle, $cantidad){
									$res = $conexion->ejecutarConsulta("update g_movilizacion_animals.movilizacion_animal_detalles
																			set cantidad = $cantidad
																		where id_movilizacion_animal_detalle = $id_movilizacion_animal_detalle");
		return $res;
	}

	//Actualiza el estado del evento de Movilización ticked
	public function actualizarEstadoEventoMovilizacion($conexion, $numero_ticket){
	
		$res = $conexion->ejecutarConsulta("update g_movilizacion_animal.evento_tickets
				set estado='procesado'
				where movimiento_ticket = 'destino'
				and numero_ticket = '$numero_ticket'");
		return $res;
	}
	
	//Actualiza el evento de Movilización
	public function actualizarEventoMovilizacion($conexion, $id_sitio, $id_area, $id_especie, $ingreso, $salida, $total){
	
		$res = $conexion->ejecutarConsulta("update g_movilizacion_animal.eventos
											set ingreso=$ingreso
											, salida=$salida
											, total=$total
											where
											id_sitio=$id_sitio
											and id_area=$id_area
											and id_especie=$id_especie
											and estado = 'activo'");
				return $res;
	}
	
	// Función para validar movilización, evento, ticket
	public function validarEventoTicketMovilizacion($conexion, $numero_ticket){
		$res = $conexion->ejecutarConsulta("select
											a.id_sitio_origen
											, a.id_area_origen
											, m.id_especie
											, m.nombre_especie
											, m.id_producto
											, m.cantidad
											, m.total
											, m.observacion
											, m.numero_certificado
											, m.fecha_certificado
											from g_movilizacion_animal.evento_tickets e,
											g_movilizacion_animal.movilizacion_animal_detalles m,
											g_movilizacion_animal.movilizacion_animales a
											where e.id_movilizacion_animal_detalle = m.id_movilizacion_animal_detalle
											and m.id_movilizacion_animal = a.id_movilizacion_animal
											and e.numero_ticket = '$numero_ticket';");
		return $res;
	}
	
	// Función para validar el evento movilización
	public function validarEventoMovilizacion($conexion, $id_sitio, $id_area, $id_especie){
		$res = $conexion->ejecutarConsulta("select id_evento, ingreso, salida, total
											from g_movilizacion_animal.eventos
											where
											id_sitio = $id_sitio
											and id_area = $id_area
											and id_especie = $id_especie
											and estado = 'activo'
											and ingreso is not null
											and salida is not null
											and total is not null
											order by id_evento desc
											limit 1;");
				return $res;
	}
	
	//Generar el numero de Ticket="TICKET-0000001"
	public function  generarNumeroTicked($conexion,$codigo){
		$res = $conexion->ejecutarConsulta("select max(numero_ticket) numero
											from g_movilizacion_animal.evento_tickets;");
		return $res;
	}
	
	//Guardar evento de Movilizacion
	public function guardarEventoMovilizacion($conexion, $id_sitio, $id_area, $id_especie, $nombre_especie, $id_secuencial, $id_movilizacion_animal, $id_movilizacion_animal_detalle
			,$movimiento_ticket, $numero_ticket, $estado)
	{
		$fecha_registro = date('d-m-Y H:i:s');
	
		$res = $conexion->ejecutarConsulta("insert into g_movilizacion_animal.evento_tickets(
				id_sitio, id_area, id_especie, nombre_especie,
				id_secuencial, id_movilizacion_animal, id_movilizacion_animal_detalle,
				movimiento_ticket, numero_ticket, fecha_registro, estado)
				values
				('$id_sitio','$id_area','$id_especie','$nombre_especie'
				,'$id_secuencial','$id_movilizacion_animal', '$id_movilizacion_animal_detalle'
				,'$movimiento_ticket','$numero_ticket','$fecha_registro','$estado')");
	
		return $res;
	}
	
	// lista de Ticked que va a feria
	public function listaTicked($conexion, $idSitio, $idArea, $idEspecie)
	{
		$res = $conexion->ejecutarConsulta("select numero_ticket
											from g_movilizacion_animal.evento_tickets
											where estado = 'creado' and movimiento_ticket = 'destino'
											and id_sitio = $idSitio
											and id_area = $idArea
											order by numero_ticket
											");
				return $res;
	}	
	
	// Funciones evento hacia la feria
	public function listaEventoMovilizacion($conexion){
		$eventoMovilizacion = $conexion->ejecutarConsulta("select
																e.id_evento
																, e.id_sitio
																, e.id_area
																, e.id_especie
																, e.nombre_especie
																, s.nombre_lugar nombre_sitio
																, a.nombre_area
																, to_char(e.fecha_evento,'DD/MM/YYYY') fecha_evento
														   from g_movilizacion_animal.eventos e
																, g_operadores.sitios s
																, g_operadores.areas a
														   where s.id_sitio = e.id_sitio
																and a.id_area = e.id_area
																and e.estado = 'activo'
														   ");
	
		while ($fila = pg_fetch_assoc($eventoMovilizacion)){
			$res[] = array(
					id_evento=>$fila['id_evento']
					, id_sitio=>$fila['id_sitio']
					, id_area=>$fila['id_area']
					, id_especie=>$fila['id_especie']
					, nombre_especie=>$fila['nombre_especie']
					, nombre_sitio=>$fila['nombre_sitio']
					, nombre_area=>$fila['nombre_area']
					, fecha_evento=>$fila['fecha_evento']
			);
		}
		return $res;
	
	}
	
	//Filtro $id_movilizacion_animal
	public function listaMovilizacionFiltro($conexion, $id_movilizacion_animal){
		$res = $conexion->ejecutarConsulta("select m.id_movilizacion_animal
											, m.numero_certificado
											, m.identificador_autorizado
											, (au.nombre_representante ||' '|| au.apellido_representante) nombre_autorizado
											, m.lugar_emision
											, m.id_sitio_origen
											, m.id_area_origen
											, m.id_sitio_destino
											, m.id_area_destino
											, so.nombre_lugar nombre_sitio_origen
											, sd.nombre_lugar nombre_sitio_destino
											, ao.nombre_area nombre_area_origen
											, ad.nombre_area nombre_area_destino
											, m.medio_transporte
											, m.placa
											, m.identificacion_conductor
											, m.descripcion_transporte
											, m.usuario_responsable
											, m.cantidad
											, m.total
											, m.estado
											, m.observacion
				                            , m.ruta_numero_certificado
											, to_char(m.fecha_registro,'DD/MM/YYYY') fecha_movilizacion
											, to_char(m.fecha_movilizacion_desde,'DD/MM/YYYY HH24:MI') fecha_movilizacion_desde
											, to_char(m.fecha_movilizacion_hasta,'DD/MM/YYYY HH24:MI') fecha_movilizacion_hasta
											, to_char(m.fecha_anulacion,'DD/MM/YYYY HH24:MI') fecha_anulacion
											from g_movilizacion_animal.movilizacion_animales m
											, g_operadores.sitios so
											, g_operadores.sitios sd
											, g_operadores.areas ao
											, g_operadores.areas ad
											, g_operadores.operadores au
											where m.id_sitio_origen = so.id_sitio
											and m.id_sitio_destino = sd.id_sitio
											and m.id_area_origen = ao.id_area
											and m.id_area_destino = ad.id_area
											and m.identificador_autorizado = au.identificador -- and m.estado = 'activo'											
											and m.id_movilizacion_animal = $id_movilizacion_animal
											;");
	
				return $res;
	}
	
	//Filtro $numero_certificado
	public function listaMovilizacionFiltroCertificado($conexion, $numero_certificado)
	{			
		$res = $conexion->ejecutarConsulta("select m.id_movilizacion_animal
											, m.numero_certificado
											, m.identificador_autorizado
											, (au.nombre_representante ||' '|| au.apellido_representante) nombre_autorizado
											, m.lugar_emision
											, m.id_sitio_origen
											, m.id_area_origen
											, m.id_sitio_destino
											, m.id_area_destino
											, so.nombre_lugar nombre_sitio_origen
											, sd.nombre_lugar nombre_sitio_destino
											, ao.nombre_area nombre_area_origen
											, ad.nombre_area nombre_area_destino
											, m.medio_transporte
											, m.placa
											, m.identificacion_conductor
											, m.descripcion_transporte
											, m.usuario_responsable
											, m.cantidad
											, m.total
											, m.estado
											, m.observacion
											, m.ruta_numero_certificado
											, to_char(m.fecha_registro,'DD/MM/YYYY') fecha_movilizacion
											, to_char(m.fecha_movilizacion_desde,'DD/MM/YYYY HH24:MI') fecha_movilizacion_desde
											, to_char(m.fecha_movilizacion_hasta,'DD/MM/YYYY HH24:MI') fecha_movilizacion_hasta
											from g_movilizacion_animal.movilizacion_animales m
											, g_operadores.sitios so
											, g_operadores.sitios sd
											, g_operadores.areas ao
											, g_operadores.areas ad
											, g_operadores.operadores au
											where m.id_sitio_origen = so.id_sitio
											and m.id_sitio_destino = sd.id_sitio
											and m.id_area_origen = ao.id_area
											and m.id_area_destino = ad.id_area
											and m.identificador_autorizado = au.identificador
											and m.estado = 'activo'
											and m.numero_certificado = '".$numero_certificado."' 
											;");									 
		return $res;
				
	}
	
	public function listaMovilizacion($conexion, $usuario_responsable){
		$res = $conexion->ejecutarConsulta("select m.id_movilizacion_animal
												, m.numero_certificado
												, m.identificador_autorizado
												, (au.nombre_representante ||' '|| au.apellido_representante) nombre_autorizado
												, m.lugar_emision
												, m.id_sitio_origen
												, m.id_area_origen
												, m.id_sitio_destino
												, m.id_area_destino
												, so.nombre_lugar nombre_sitio_origen
												, sd.nombre_lugar nombre_sitio_destino
												, ao.nombre_area nombre_area_origen
												, ad.nombre_area nombre_area_destino
												, m.medio_transporte
												, m.descripcion_transporte
												, m.usuario_responsable
												, m.cantidad
												, m.total
												, m.estado
												, m.observacion
												, to_char(m.fecha_registro,'DD/MM/YYYY') fecha_registro
												, to_char(m.fecha_movilizacion_desde,'DD/MM/YYYY') fecha_movilizacion_desde
												, to_char(m.fecha_movilizacion_hasta,'DD/MM/YYYY') fecha_movilizacion_hasta
											from g_movilizacion_animal.movilizacion_animales m
												, g_operadores.sitios so
												, g_operadores.sitios sd
												, g_operadores.areas ao
												, g_operadores.areas ad
												, g_operadores.operadores au
											where m.id_sitio_origen = so.id_sitio
												and m.id_sitio_destino = sd.id_sitio
												and m.id_area_origen = ao.id_area
												and m.id_area_destino = ad.id_area
												and m.identificador_autorizado = au.identificador
												and m.estado = 'activo'
												and m.usuario_responsable = '".$usuario_responsable."'
											order by 1 asc
											;");
	
		return $res;
	}
public function listaMovilizacionAnulacionEmpresa($conexion, $id_usuario_responsable){	
		$res = $conexion->ejecutarConsulta("select ae.identificador_empresa from g_usuario.usuario_administrador_empresas ae  where ae.identificador_empresa='".$id_usuario_responsable."' or ae.identificador='".$id_usuario_responsable."' ");
		return $res;
	}
	
	public function listaFiltroAnulacion($conexion, $identificadorEmpresa, $numeroCertificado, $fechaInicio, $fechaFin){
		$busqueda0 = '';
		$busqueda1 = '';
		$busqueda2 = '';
		//$busqueda3 = '';
	
		if(($numeroCertificado=="0") && ($fechaInicio=="0") && ($fechaFin=="0"))
			$busqueda0 = " and m.fecha_registro >= current_date and m.fecha_registro < current_date+1";
		if($numeroCertificado!="0")
			$busqueda1 = " and UPPER(m.numero_certificado) like '%".strtoupper($numeroCertificado)."' ";
		if(($fechaInicio!="0") && ($fechaFin!="0")){
			$fechaInicio1 = str_replace("/","-",$fechaInicio);
			$fechaInicio2 = strtotime ('-1 day',strtotime($fechaInicio1));//la fecha de vencimiento 1 day
			$fechaInicio3 = date('d/m/Y',$fechaInicio2);
	
			$fechaFin1 = str_replace("/","-",$fechaFin);
			$fechaFin2 = strtotime ('+1 day',strtotime($fechaFin1));//la fecha de vencimiento 1 day
			$fechaFin3 = date('d/m/Y',$fechaFin2);
	
			$busqueda2 = " and m.fecha_registro >= '".$fechaInicio3."' and m.fecha_registro <= '".$fechaFin3."' ";
		}
		
		$res = $conexion->ejecutarConsulta("select m.id_movilizacion_animal
												, m.numero_certificado
												, m.identificador_autorizado
												, (au.nombre_representante ||' '|| au.apellido_representante) nombre_autorizado
												,m.usuario_empresa
												, m.lugar_emision
												, m.id_sitio_origen
												, m.id_area_origen
												, m.id_sitio_destino
												, m.id_area_destino
												, so.nombre_lugar nombre_sitio_origen
												, sd.nombre_lugar nombre_sitio_destino
												, ao.nombre_area nombre_area_origen
												, ad.nombre_area nombre_area_destino
												, m.medio_transporte
												, m.descripcion_transporte
												, m.usuario_responsable
												, m.cantidad
												, m.total
												, m.estado
												, m.observacion
												, to_char(m.fecha_registro,'DD/MM/YYYY') fecha_registro
												, to_char(m.fecha_movilizacion_desde,'DD/MM/YYYY') fecha_movilizacion_desde
												, to_char(m.fecha_movilizacion_hasta,'DD/MM/YYYY') fecha_movilizacion_hasta
											from g_movilizacion_animal.movilizacion_animales m
												, g_operadores.sitios so
												, g_operadores.sitios sd
												, g_operadores.areas ao
												, g_operadores.areas ad
												, g_operadores.operadores au
											where m.id_sitio_origen = so.id_sitio
												and m.id_sitio_destino = sd.id_sitio
												and m.id_area_origen = ao.id_area
												and m.id_area_destino = ad.id_area
												and m.identificador_autorizado = au.identificador
												and m.usuario_empresa in ('$identificadorEmpresa')													
												".$busqueda0."
												".$busqueda1."
				                                ".$busqueda2."												
											order by 1 asc
											");
		return $res;
	}
	
	public function listaFiltroDetalleMovilizacionAnimal($conexion, $id_movilizacion_animal){	
		$detalleMovilizacion = $conexion->ejecutarConsulta("select dm.id_movilizacion_animal_detalle
												, dm.id_especie
												, dm.nombre_especie
												, dm.id_producto
												, p.nombre_comun nombre_producto
												, dm.cantidad
												, dm.total
												, dm.observacion
												, dm.numero_certificado
												, to_char(dm.fecha_certificado,'DD/MM/YYYY') fecha_certificado
												from g_movilizacion_animal.movilizacion_animal_detalles dm
												, g_catalogos.productos p
												where dm.id_producto = p.id_producto
												and dm.id_movilizacion_animal = $id_movilizacion_animal");
	
					while ($fila = pg_fetch_assoc($detalleMovilizacion)){
						$res[] = array(
								id_movilizacion_animal_detalle=>$fila['id_movilizacion_animal_detalle'],
								id_especie=>$fila['id_especie'],
								nombre_especie=>$fila['nombre_especie'],
								id_producto=>$fila['id_producto'],
								nombre_producto=>$fila['nombre_producto'],
								cantidad=>$fila['cantidad'],
								total=>$fila['total'],
								observacion=>$fila['observacion'],
								numero_certificado=>$fila['numero_certificado'],
								fecha_certificado=>$fila['fecha_certificado']
						);
					}
		return $res;
	}

	//lista de responsables de movilización animal
	public function listaResponsablesMovilizacionAnimal($conexion)
	{
		$res = $conexion->ejecutarConsulta("select rm.id_responsable_movilizacion
												,rm.id_tipo_lugar_emision
												,rm.identificador_emisor
												,rm.nombre_emisor_movilizacion
												,rm.nombre_lugar_emision
												,rm.id_provincia
												,rm.provincia
												,rm.id_canton
												,rm.canton
												,rm.id_parroquia
												,rm.parroquia
												,rm.estado
											from g_movilizacion_animal.responsable_movilizaciones rm
												,g_catalogos.tipo_lugar_emisiones te
											where rm.id_tipo_lugar_emision = te.id_tipo_lugar_emision
											order by rm.nombre_emisor_movilizacion asc ");
				return $res;
	}
	
	//lista inicio evento de movilización animal -->ojo
	public function listaInicioEventoMovilizacion($conexion)
	{
		$res = $conexion->ejecutarConsulta("select distinct e.id_sitio
											, e.id_area
											, e.id_evento_ticket
											, (o.razon_social) nombre_razon_social
											, (o.nombre_representante ||' '|| o.apellido_representante) nombre_representante
											, s.nombre_lugar
											, a.nombre_area
											from g_movilizacion_animal.evento_tickets e
											, g_operadores.sitios s
											, g_operadores.areas a
											, g_operadores.operadores o
											where e.id_sitio = s.id_sitio
											and e.id_area = a.id_area
											--and e.id_evento_ticket = o.identificador
											");
		return $res;
	}
	
	//lista de autorizado de movilización animal
	public function listaAutorizadoMovilizacionAnimal($conexion)
	{
		$res = $conexion->ejecutarConsulta("select m.id_autorizar_movilizacion
											, s.id_sitio
											, a.id_area
											, s.nombre_lugar sitio
											, a.nombre_area area
											, m.identificador_propietario
											, m.identificador_autorizado
											, case when op.razon_social is null or op.razon_social = '' then op.nombre_representante ||' '|| op.apellido_representante else op.razon_social end nombre_propietario
											, case when oa.razon_social is null or oa.razon_social = '' then oa.nombre_representante ||' '|| oa.apellido_representante else oa.razon_social end nombre_autorizado
											, m.observacion
											, to_char(m.fecha_autorizacion,'DD/MM/YYYY') fecha_autorizacion
											, m.estado
											from g_movilizacion_animal.autorizar_movilizaciones m
											, g_operadores.sitios s
											, g_operadores.areas a
											, g_operadores.operadores op
											, g_operadores.operadores oa
											where m.id_sitio = s.id_sitio
											and m.id_area = a.id_area
											and m.identificador_propietario = op.identificador
											and m.identificador_autorizado = oa.identificador				
										  ");
		return $res;
	}
	
	//lista de tipo de responsables de movilización animal Autoservicio
	public function listaTipoResponsablesMovilizacionAnimal($conexion)
	{
		$res = $conexion->ejecutarConsulta("select * 
												from g_catalogos.tipo_lugar_emisiones
											where estado = 'activo' ");
		return $res;
	}
	
	//filtrar el responsables de movilización animal
	public function seleccionarResponsablesMovilizacionAnimal($conexion, $tipoEmisor, $tipo, $valor)
	{		
		$query = '';	
		if($tipoEmisor==0){//
		
			$query = "select identificador from g_uath.ficha_empleado
			          where nombre=''";
			
			
		}
			//echo $tipoEmisor, $tipo, $valor;
		if($tipoEmisor==1){//funcionarios de agrocalidad
			$busqueda1="";
			$busqueda2="";
			$query = "select identificador
					    , nombre ||' '|| apellido nombres
					    , '' provincia
						, '' canton
						, '' parroquia
						, '' autoservicio
					  from g_uath.ficha_empleado
			          where nombre is not null ";
			switch ($tipo){
				case 1://identificacion
					$busqueda1 = " and identificador = '".$valor."' order by nombres asc"; break;
				case 2://apellidos
					$busqueda2 = " and upper(apellido) like '%".strtoupper($valor)."%' order by nombres asc";break;
			}
			$query = $query.' '.$busqueda1.' '.$busqueda2;
		}
		
		if($tipoEmisor==2){//pto de distribución
			$busqueda1="";
			$busqueda2="";
			$query = "select distinct d.identificador_distribuidor identificador
					  	, case when o.razon_social = '' then o.nombre_representante ||' '|| o.apellido_representante else o.razon_social end nombres
						, o.provincia
						, o.canton
						, o.parroquia
						, '' autoservicio 					  	
					  from g_vacunacion_animal.administrador_vacunacion a
					  	, g_vacunacion_animal.administrador_distribuidor d
					  	, g_operadores.operadores o
					  where d.identificador_distribuidor = o.identificador
					  	and d.id_administrador_vacunacion = a.id_administrador_vacunacion
						and d.estado = 'activo'";
			switch ($tipo){
				case 1://identificacion
					$busqueda1 = " and d.identificador_distribuidor = '".$valor."'"; break;
				case 2://apellidos
					$busqueda2 = " and upper(o.apellido_representante) like '%".strtoupper($valor)."%'";break;
			}
			//echo $query;
			$query = $query.' '.$busqueda1.' '.$busqueda2;
		}
		if($tipoEmisor==3){
	//ojo no vale esta consulta corregir consulta
			$query = "select distinct d.identificador_distribuidor identificador
					  	
					  from 
					  	g_vacunacion_animal.administrador_distribuidor d
					  	
					 where d.identificador_distribuidor='0'";
		
			//echo $query;
			$query = $query;
		}
		if($tipoEmisor==4){
			//ojo no vale esta consulta corregir consulta
			$query = "select distinct d.identificador_distribuidor identificador
					  from
					  	g_vacunacion_animal.administrador_distribuidor d
			
					 where d.identificador_distribuidor='0'";
			
			//echo $query;
			$query = $query;
		}
		if($tipoEmisor==5){//autoservicio
			$busqueda1="";
			$busqueda2="";
			$query = "select distinct oa.identificador 
						, case when oa.razon_social is null or oa.razon_social = '' then oa.nombre_representante ||' '|| oa.apellido_representante else oa.razon_social end nombres
						, lv.id_localizacion id_provincia
						, lv.nombre provincia 
						, lc.id_localizacion id_canton
						, lc.nombre canton
						, 1502 id_parroquia--, lp.id_localizacion id_parroquia
						, 'IÑAQUITO' parroquia--, lp.nombre id_parroquia
						, oe.identificador identificador_autoservicio
						, oe.razon_social autoservicio 
					  from g_usuario.usuario_administrador_empresas e
						, g_operadores.operadores oa
						, g_operadores.operadores oe
						, g_vacunacion_animal.administrador_vacunacion a
						, g_catalogos.localizacion lv
						, g_catalogos.localizacion lc
						--, g_catalogos.localizacion lp
					  where oa.identificador = e.identificador
						and oe.identificador = e.identificador_empresa
						and upper(lv.nombre) =  upper(oe.provincia) and lv.categoria = 1 -- provincia
						and upper(lc.nombre) =  upper(oe.canton) and lc.categoria = 2 -- canton
						--and upper(lp.nombre) =  upper(oe.parroquia) and lp.categoria = 4 -- parroquia
						and a.identificador_administrador = oe.identificador";
			switch ($tipo){
				case 1://identificacion
					$busqueda1 = " and e.identificador = '".$valor."'"; break;
				case 2://apellidos
					$busqueda2 = " and upper(oe.razon_social) like '%".strtoupper($valor)."%'";break;
			}
			$query = $query.' '.$busqueda1.' '.$busqueda2;
		}
		//echo $query;		
		$res = $conexion->ejecutarConsulta($query);
		return $res;
				
	}
	
	public function listarLocalizacionLugarEmision($conexion, $categoria){
		$busqueda = '';
		$query = '';
		if($categoria=='CANTONES'){
			$query = 'select distinct cc.id_localizacion id_codigo
					  	, cc.nombre
						, cc.id_localizacion_padre
					  from g_catalogos.localizacion cc
						,g_catalogos.localizacion c
					  where cc.id_localizacion = c.id_localizacion_padre
						and c.categoria = 3						
					  order by cc.nombre asc';
		}
		if($categoria=='SITIOS'){//coordinaciones
			$query = 'select id_localizacion id_codigo
						,nombre 
						,id_localizacion_padre
					  from g_catalogos.localizacion
					  where categoria = 3'; 
		}
		if($categoria=='PARROQUIAS'){//provincias
			$query = 'select id_localizacion id_codigo
					    ,nombre 
					    ,id_localizacion_padre
					  from g_catalogos.localizacion
					  where categoria = 4';
		}		
		
		$catalogo = $conexion->ejecutarConsulta($query);			
		while ($fila = pg_fetch_assoc($catalogo)){
			$res[] = array(codigo=>$fila['id_codigo'],
					nombre=>$fila['nombre'],
					padre=>$fila['id_localizacion_padre']				
			);
		}
	
		return $res;
	}
	
	public function listarSitiosLocalizacion($conexion,$tipo){
		$cid = $this->listarLocalizacion($conexion, $tipo);
		while ($fila = pg_fetch_assoc($cid)){
			$res[] = array(codigo=>$fila['id_localizacion'],nombre=>$fila['nombre'],categoria=>$fila['categoria'],padre=>$fila['id_localizacion_padre'],latitud=>$fila['latitud'],longitud=>$fila['longitud'],zona=>$fila['zona']);
		}
		return $res;
	}
	
	public function guardarResponsableMovilizacion($conexion, $id_tipo_lugar_emision, $identificador_emisor, $identificador_autoservicio, $nombre_emisor_movilizacion, $nombre_lugar_emision
			, $id_provincia, $provincia, $id_canton, $canton, $id_parroquia, $parroquia, $id_sitio, $usuario_responsable, $estado){		
		
		$fecha_registro = date('d-m-Y H:i:s');	
		
		/*insert into g_movilizacion_animal.responsable_movilizaciones(
				id_tipo_lugar_emision, identificador_emisor, identificador_autoservicio,
				nombre_emisor_movilizacion, nombre_lugar_emision, id_provincia,
				provincia, id_canton, canton, id_parroquia, parroquia, id_sitio, usuario_responsable, fecha_registro, estado)
				values ('$id_tipo_lugar_emision','$identificador_emisor','$identificador_autoservicio','$nombre_emisor_movilizacion','$nombre_lugar_emision'
						,'$id_provincia','$provincia','$id_canton','$canton'
						,'$id_parroquia','$parroquia',
						'$id_sitio',
						'$usuario_responsable',
						'$fecha_registro','$estado')*/
			
		$res = $conexion->ejecutarConsulta("insert into g_movilizacion_animal.responsable_movilizaciones(
												id_tipo_lugar_emision, identificador_emisor, identificador_autoservicio,
												nombre_emisor_movilizacion, nombre_lugar_emision, id_provincia, 
												provincia, id_canton, canton, id_parroquia, parroquia, id_sitio, usuario_responsable, fecha_registro, estado)
											select
												'$id_tipo_lugar_emision','$identificador_emisor','$identificador_autoservicio','$nombre_emisor_movilizacion','$nombre_lugar_emision'
												,'$id_provincia','$provincia','$id_canton','$canton','$id_parroquia','$parroquia','$id_sitio','$usuario_responsable','$fecha_registro','$estado'
											where not exists (select identificador_emisor from g_movilizacion_animal.responsable_movilizaciones where identificador_emisor='$identificador_emisor' and estado='activo');");
		
		return $res;
	}
	
	public function actualizaDatosAlmacen($conexion, $idResponsableMovilizacion,$estado)
	{
	
		$res = $conexion->ejecutarConsulta("update g_movilizacion_animal.responsable_movilizaciones
				set estado='".$estado."'
				where id_responsable_movilizacion='".$idResponsableMovilizacion."';");
		
		return $res;
		
		ejecutarConsulta("update g_vacunacion_animal.serie_documentos
											   set estado = '".$estado."'
											       , fecha_modificacion = '".$fecha_modificacion."'
											where tipo_documento = '".$tipo_documento."'
												and numero_documento = '".$numero_documento."';");
	}
	
	//Función para buscar el listado responsable de emision de movilización
	public function listaResponsableEmisionMovilizacion ($conexion, $id_responsable_movilizacion){			
		$res = $conexion->ejecutarConsulta("select rm.id_responsable_movilizacion
												, rm.id_tipo_lugar_emision 
												, te.nombre_lugar_emision nombre_tipo_lugar_emision
												, rm.identificador_emisor
												, rm.nombre_emisor_movilizacion
												, rm.nombre_lugar_emision
												, rm.id_provincia
												, rm.provincia
												, rm.id_canton
												, rm.canton
												, rm.id_parroquia
												, rm.parroquia
												, rm.estado
												, rm.id_sitio
												, (select nombre_lugar from g_operadores.sitios where id_sitio = rm.id_sitio) sitio
											from g_movilizacion_animal.responsable_movilizaciones rm
												, g_catalogos.tipo_lugar_emisiones te
											where rm.id_tipo_lugar_emision = te.id_tipo_lugar_emision
												and rm.id_responsable_movilizacion = $id_responsable_movilizacion;");
		return $res;
	}
	
	//Función para buscar evento de movilización
	public function listaBusquedaInicioEventoMovilizacion ($conexion){//, $id_autorizaado_movilizacion){
		$res = $conexion->ejecutarConsulta("select distinct e.id_sitio
											, e.id_area
											, e.identificador_evento
											, (o.razon_social) nombre_razon_social
											, (o.nombre_representante ||' '|| o.apellido_representante) nombre_representante
											, s.nombre_lugar nombre_sitio
											, a.nombre_area nombre_area
											from g_movilizacion_animal.eventos e
											, g_operadores.sitios s
											, g_operadores.areas a
											, g_operadores.operadores o
											where e.id_sitio = s.id_sitio
											and e.id_area = a.id_area
											and e.identificador_evento = o.identificador");
		return $res;
	}
	
	//Función para buscar el listado responsable de emision de movilización
	public function listaAutorizadoTramitarMovilizacion ($conexion, $id_autorizaado_movilizacion){
		$res = $conexion->ejecutarConsulta("select m.id_autorizar_movilizacion
											, s.id_sitio
											, a.id_area
											, s.nombre_lugar sitio
											, a.nombre_area area
											, m.identificador_propietario
											, m.identificador_autorizado
											, case when op.razon_social is null or op.razon_social = '' then op.nombre_representante ||' '|| op.apellido_representante else op.razon_social end nombre_propietario
											, case when oa.razon_social is null or oa.razon_social = '' then oa.nombre_representante ||' '|| oa.apellido_representante else oa.razon_social end nombre_autorizado
											, m.observacion
											, to_char(m.fecha_autorizacion,'DD/MM/YYYY') fecha_autorizacion
											, m.estado
											from g_movilizacion_animal.autorizar_movilizaciones m
											, g_operadores.sitios s
											, g_operadores.areas a
											, g_operadores.operadores op
											, g_operadores.operadores oa
											where m.id_sitio = s.id_sitio
											and m.id_area = a.id_area
											and m.identificador_propietario = op.identificador
											and m.identificador_autorizado = oa.identificador				
											and m.id_autorizar_movilizacion = $id_autorizaado_movilizacion;");
		return $res;
	}
	
	public function listaAutorizados($conexion, $tipo, $busqueda){
		$busquedaSitio = '';
		switch ($tipo){
			case 1: $busquedaSitio = "identificador = '".$busqueda."'"; break;
			case 2: $busquedaSitio = "UPPER(apellido_representante) like '%".strtoupper($busqueda)."%'"; break;
		}
		
		$res = $conexion->ejecutarConsulta("select identificador
											, case when razon_social is null
											then nombre_representante ||' '|| apellido_representante else razon_social end nombre_autorizado
											from g_operadores.operadores
											where ".$busquedaSitio." ;");
			
		return $res;
	}
	
	public function listaEventosAutorizados($conexion, $tipo, $busqueda){
		$busquedaSitio = '';
		switch ($tipo){
			case 1: $busquedaSitio = "identificador = '".$busqueda."'"; break;
			case 2: $busquedaSitio = "UPPER(apellido_representante) like '%".strtoupper($busqueda)."%'"; break;
		}
	
		$res = $conexion->ejecutarConsulta("select distinct o.identificador identificador_feria
												, case when o.razon_social is null or o.razon_social = '' then o.nombre_representante ||' '|| o.apellido_representante else o.razon_social end nombre_feria
												, o.provincia
												, o.canton
												, t.nombre nombre_operacion 
											from g_operadores.operaciones op
												, g_catalogos.tipos_operacion t
												, g_operadores.operadores o
											where o.identificador = op.identificador_operador
												and op.id_tipo_operacion = t.id_tipo_operacion
												and t.nombre = 'Feria de comercialización animal'
												and t.id_area = 'SA'
											    and ".$busquedaSitio." ;");
			
		return $res;
	}
	
	public function listaSitioArea($conexion, $tipoSitio, $txtSitio)
	{
		$busquedaSitio = '';
		switch ($tipoSitio){
			case 1: $busquedaSitio = "s.identificador_operador = '".$txtSitio."'"; break;
			case 2: $busquedaSitio = "UPPER(o.apellido_representante) like '%".strtoupper($txtSitio)."%'"; break;
			case 3: $busquedaSitio = "UPPER(s.nombre_lugar) like '%".strtoupper($txtSitio)."%'"; break;
		}
		
		$res = $conexion->ejecutarConsulta("select distinct s.id_sitio, a.id_area, s.identificador_operador, (o.nombre_representante || ' ' || o.apellido_representante) nombres
											, s.nombre_lugar granja, a.nombre_area, s.provincia
											from g_operadores.operadores o
											, g_operadores.sitios s
											, g_operadores.areas a
											, g_operadores.operaciones op
											, g_catalogos.tipos_operacion t
											where o.identificador = s.identificador_operador
											and o.identificador = op.identificador_operador
											and s.identificador_operador = op.identificador_operador
											and s.id_sitio = a.id_sitio
											and op.id_tipo_operacion = t.id_tipo_operacion
											and t.nombre = 'Productor'
											and t.id_area = 'SA'											
			                                and ".$busquedaSitio." ;");
			
		return $res;
	}

	public function guardarAutorizacionMovilizacion($conexion, $id_sitio, $id_area, $identificador_propietario, $identificador_autorizado
			, $observacion, $fecha_autorizacion, $estado){		
		
		$fecha_registro = date('d-m-Y H:i:s');
		
		$res = $conexion->ejecutarConsulta("insert into g_movilizacion_animal.autorizar_movilizaciones(id_sitio, id_area, identificador_propietario,
				identificador_autorizado, observacion, fecha_autorizacion, fecha_registro, estado) 
				values ('$id_sitio','$id_area','$identificador_propietario','$identificador_autorizado'
				,'$observacion','$fecha_autorizacion','$fecha_registro','$estado')");		
		//id_autorizar_movilizacion		
		return $res;
	}
	
	public function guardarInicioEventoMovilizacion($conexion, $id_sitio, $id_area, $identificador_evento, $nombre_evento, $fecha_inicio_evento, $fecha_fin_evento 
			, $usuario_reponsable, $estado){
	
		$fecha_registro = date('d-m-Y H:i:s');
		$ingreso = 0;
		$salida = 0;
		$total = 0;
	
		$res = $conexion->ejecutarConsulta("insert into g_movilizacion_animal.eventos(id_sitio, id_area, identificador_evento, nombre_evento
				, fecha_inicio_evento, fecha_fin_evento, fecha_registro, usuario_reponsable, estado, ingreso, salida, total)    
				values ('$id_sitio','$id_area','$identificador_evento','$nombre_evento', '$fecha_inicio_evento','$fecha_fin_evento'
				,'$fecha_registro','$usuario_reponsable','$estado','$ingreso','$salida','$total')");

		return $res;
	}
	
	public function catastroEstadoMovilizacion($conexion, $numero_documento)
	{
		$res = $conexion->ejecutarConsulta("delete from g_vacunacion_animal.catastros
											where numero_documento = '".$numero_documento."'");
		return $res;
	}
	
	public function catastroEstadoVacunacion($conexion, $numero_documento)
	{
		$res = $conexion->ejecutarConsulta("delete from g_vacunacion_animal.catastros
											where numero_documento_referencia = '".$numero_documento."'");
		return $res;
	}
	
	public function listaReporteMovilizacionAnimal($conexion, $empresa, $fechaInicio, $fechaFin, $estado){		
		$busqueda0 = '';
		$busqueda1 = '';
		$busqueda2 = '';
		$busqueda3 = '';
	
		//if ($provincia!='TODOS')
			//$busqueda3=" and provincia_origen = '".$provincia."' ";
			
		if($empresa=="1")
			$busqueda0 = " where usuario_empresa  not in (select distinct identificador_empresa from g_usuario.usuario_administrador_empresas)";
		else{	
			$busqueda0 = " where usuario_empresa = '$empresa' ";
		}
				
		if ($estado!="0")			
			$busqueda1 = " and estado = '".$estado."' ";
				
		if(($fechaInicio!="0") && ($fechaFin!="0")){
			$fechaInicio1 = str_replace("/","-",$fechaInicio);
			$fechaInicio2 = strtotime($fechaInicio1);//la fecha de vencimiento 1 day
			$fechaInicio3 = date('d/m/Y',$fechaInicio2);
		
			$fechaFin1 = str_replace("/","-",$fechaFin);
			//$fechaFin2 = strtotime($fechaFin1);//la fecha de vencimiento 1 day
			$fechaFin2 = strtotime ('+1 day',strtotime($fechaFin1));//la fecha de vencimiento 1 day
			$fechaFin3 = date('d/m/Y',$fechaFin2);
		
			$busqueda2 = " and fecha_movilizacion_desde >= '".$fechaInicio3."' and fecha_movilizacion_desde <= '".$fechaFin3."' ";
		}
	
													
		$res = $conexion->ejecutarConsulta("select *
											from g_movilizacion_animal.vista_reporte_movilizacion
											".$busqueda0."
											".$busqueda1."
				                            ".$busqueda2."
											order by id_movilizacion_animal asc;");
		return $res;
	}
	
	public function actualizarNumeroCertificadoMovilizacion($conexion, $tipo_documento, $numero_documento, $estado)
	{
		//--'vacunacion', fiscalizacion y movilizacion
		$fecha_modificacion = date('d-m-Y H:i:s');
		$res = $conexion->ejecutarConsulta("update g_vacunacion_animal.serie_documentos
											   set estado = '".$estado."'
											       , fecha_modificacion = '".$fecha_modificacion."'
											where tipo_documento = '".$tipo_documento."'
												and numero_documento = '".$numero_documento."'
											;");
	
		return $res;
	}	
	
	//Paso 1.- Cambia estado => g_vacunacion_animal.serie_documentos
	public function actualizarEstadoSerieDocumentos($conexion, $numero_documento, $observacion, $estado)
	{
		$fecha_registro = date('d-m-Y H:i:s');
		$res = $conexion->ejecutarConsulta("update g_vacunacion_animal.serie_documentos
											   set estado = '".$estado."' 
											       , observacion = '".$observacion."'
											       , fecha_modificacion = '".$fecha_registro."'
											where tipo_documento = 'movilizacion'
											and numero_documento = '".$numero_documento."'");
						
		return $res;					
	}
	
	//Paso 2.- Cambia estado => g_movilizacion_animal.movilizacion_animales
	public function actualizarEstadoMovilizacion($conexion, $numero_documento, $observacion, $estado, $usuario_anulacion)
	{		
		$fecha_registro = date('d-m-Y H:i:s');
		$res = $conexion->ejecutarConsulta("update g_movilizacion_animal.movilizacion_animales
											   set estado = '".$estado."' 
											       , observacion = '".$observacion."'
											       , usuario_anulacion = '".$usuario_anulacion."'
											       , fecha_anulacion = '".$fecha_registro."'
											where numero_certificado = '".$numero_documento."'");
	
		return $res;
	}
	
	
	public function guardarGeneradorCertificados($conexion, $id_especie,$nombre_especie, $tipo_documento,
												$numeracion_documento,$serie,$numero_documento,$estado){
	$fecha_registro = date('Y-m-d H:i:s.uO');
	
	$res = $conexion->ejecutarConsulta("insert into g_vacunacion_animal.serie_documentos(
											id_especie
											,nombre_especie
											,tipo_documento
											,numeracion_documento
											,serie
											,numero_documento
											, estado
											,fecha_registro)
										select
											'$id_especie','$nombre_especie','$tipo_documento'
											,'$numeracion_documento'
											,'$serie'
											,'$numero_documento'
											,'$estado'
											,'$fecha_registro'  
										 WHERE NOT EXISTS 
										(SELECT serie FROM  g_vacunacion_animal.serie_documentos WHERE serie = '$serie')");
			
	return $res;
	}
	
	//lista inicio generar certificados de movilizacion -->ojo
	public function listaActivarCertificadosMovilizacion($conexion)
	{
		$res = $conexion->ejecutarConsulta("SELECT 
											    id_serie_documento
												,id_especie, nombre_especie
												,tipo_documento 
										        ,numeracion_documento
												,serie
												,numero_documento
												,estado
												,observacion
												,to_char(fecha_registro,'DD/MM/YYYY') fecha_registro
												,to_char(fecha_modificacion,'DD/MM/YYYY') fecha_modificacion
 											 FROM 
												g_vacunacion_animal.serie_documentos
												where estado in ('ingresado','anulado','inactivo')
											    order by id_serie_documento desc limit 599 ;");
		return $res;
	}

	
	public function filtroActivarCertificadosMovilizacion ($conexion, $id_serie_documento){
		$res = $conexion->ejecutarConsulta("SELECT 
											    id_serie_documento
												,id_especie, nombre_especie
												,tipo_documento 
										        ,numeracion_documento
												,serie
												,numero_documento
												,estado
												,observacion
												,to_char(fecha_registro,'DD/MM/YYYY') fecha_registro
												,to_char(fecha_modificacion,'DD/MM/YYYY') fecha_modificacion
												
 											 FROM 
												g_vacunacion_animal.serie_documentos
											  where id_serie_documento=$id_serie_documento;");
		return $res;
	}
	
	public function actualizaCertificadoMovilizacion($conexion, $id_serie_documento,$estado)
	{
		$fecha_modificacion = date('Y-m-d H:i:s.uO');
		$res = $conexion->ejecutarConsulta("update g_vacunacion_animal.serie_documentos
				set estado='".$estado."', fecha_modificacion='".$fecha_modificacion."'
				where id_serie_documento='".$id_serie_documento."';");
	
		return $res;
	
		
	}
	
	public function listaMovilizacionA($conexion, $numero_certificado)
	{
		
		$res = $conexion->ejecutarConsulta("select * from g_movilizacion_animal.movilizacion_animales where numero_certificado='".$numero_certificado."' ");
	
		return $res;
	
	
	}
	
	
	/*public function PerfilUsuario($conexion, $usuario)
	{
		$res = $conexion->ejecutarConsulta("
				select 
					sum((CASE id_perfil
					--,41,42,43,44,45,46,49,50,51,52,53,54,55)
					WHEN   40 THEN 1
					WHEN   41 THEN 2
					WHEN   42 THEN 3
					WHEN   43 THEN 4
					WHEN   44 THEN 5
					WHEN   45 THEN 6
					WHEN   46 THEN 7
					WHEN   49 THEN 8
					WHEN   50 THEN 9
					WHEN   51 THEN 10
					WHEN   52 THEN 11
					WHEN   53 THEN 12
					WHEN   54 THEN 13
					WHEN   55 THEN 14
					--WHEN id_perfil = 41 THEN 5
					ELSE 0 END ) )as central
					
				
		 from 
					g_usuario.usuarios_perfiles up
					where up.identificador='".$usuario."';
					
					--group by id_perfil
				
				
				 ");
	
		return $res;
	
	
	}
	*/
	/*public function listarLocalizacionn($conexion,$provincia,$usuario){
	
		$busqueda1 = '';
		if($usuario!='central'){
		$busqueda1 = " and nombre = '".$provincia."' ";
		}
		$res = $conexion->ejecutarConsulta("select *
											from g_catalogos.localizacion
											where categoria = 1 
				" .$busqueda1 ."
											order  by 3;");
	
			
		return $res;
	}
	
	public function listarSitiosLocalizacionn($conexion,$tipo,$usuario){
		$cid = $this-> listarLocalizacionn($conexion, $tipo, $usuario);
		while ($fila = pg_fetch_assoc($cid)){
			$res[] = array(codigo=>$fila['id_localizacion'],nombre=>$fila['nombre'],categoria=>$fila['categoria'],padre=>$fila['id_localizacion_padre'],latitud=>$fila['latitud'],longitud=>$fila['longitud'],zona=>$fila['zona']);
		}
		return $res;
	}*/
	

	/*public function listaOperadoresEmpresaM($conexion, $provincia, $identificador){
		//$busqueda1 = '';
	
		//if ($identificador!="1709164949") // $autoservicio 1 = usuarios administradores empresa
		//	$busqueda1 = " and o.provincia = '".$provincia."' ";
	
		//if ($autoservicio=="2") // $autoservicio 1 = usuarios administradores empresa
		//	$busqueda1 = " and a.identificador= '".$provincia."'";
		//// $autoservicio 2 = usuarios administradores agrocalidad
	
		$empresa = $conexion->ejecutarConsulta("select distinct o.identificador identificador_empresa
												, o.razon_social empresa, provincia, a.estado
										from g_usuario.usuario_administrador_empresas a	, g_operadores.operadores o
										where a.identificador_empresa = o.identificador	and a.estado='2'
										");
							
		while ($fila = pg_fetch_assoc($empresa)){
			$res[] = array(identificador_empresa=>$fila['identificador_empresa']
					, identificador_usuario=>$fila['identificador_usuario']
					, empresa=>$fila['empresa']
					, estado=>$fila['estado']
					, provincia=>$fila['provincia']
			);
		}
	
		return $res;
	}*/
	
		
	//funciones agregadas 
	
	
	//selecciona la provincia del usuario y tecnico de agrocalidad
	/*public function seleccionarProvinciaUsuarioMovilizacion($conexion, $usuario ){
		$res = $conexion->ejecutarConsulta("select 	distinct
												 o.provincia provincias,us.identificador
											from 
												g_operadores.operadores o
												,g_usuario.usuarios us
											where
												o.identificador='$usuario'
											 	and us.identificador='$usuario'
											union
											select distinct
												cl.nombre,
												fe.identificador
											from
												g_uath.ficha_empleado fe,
												g_catalogos.localizacion cl
											where
												fe.identificador='$usuario'
												and cl.id_localizacion=fe.id_localizacion_provincia ;");
		return $res;
	}*/
	
	//lugares de emision de movilizacion normal
	public function lugarEmisionFuncionarioProvincia($conexion, $provincia){
		$res = $conexion->ejecutarConsulta("select distinct
				s.id_sitio,
				s.identificador_operador identificador_emisor,
				(o.nombre_representante || ' ' || o.apellido_representante) nombre_emisor_movilizacion,
				s.nombre_lugar nombre_lugar_emision,
				--s.nombre_lugar granja,
				--a.nombre_area,
				s.provincia
				from g_operadores.operadores o
				, g_operadores.sitios s
				, g_operadores.areas a
				, g_operadores.operaciones op
				, g_catalogos.tipos_operacion t
				where o.identificador = s.identificador_operador
				and o.identificador = op.identificador_operador
				and s.identificador_operador = op.identificador_operador
				and s.id_sitio = a.id_sitio
				and op.id_tipo_operacion = t.id_tipo_operacion
				and t.nombre = 'Productor'
				and t.id_area = 'SA'
				and a.tipo_area = 'Lugar de producción'
				and s.identificador_operador not in (select distinct identificador_empresa from g_usuario.usuario_administrador_empresas)
	
				and o.provincia = '$provincia';");
		return $res;
	}
	
	// lista sitios de vacunacion movilizacion normal
	public function listaAreaNormal($conexion, $idSitio)
	{
		$Lugar = $conexion->ejecutarConsulta("select
												a.id_sitio,
												a.id_area,
												a.nombre_area,
												a.tipo_area
											from
												--g_operadores.operadores o,
												---g_operadores.sitios s,
												g_operadores.areas a
											where
				                                --o.identificador = s.identificador_operador
												--and a.id_sitio = s.id_sitio
												--and 
												a.tipo_area = 'Lugar de producción' and a.id_sitio=$idSitio;");
		while ($fila = pg_fetch_assoc($Lugar)){
			$res[] = array(id_sitio=>$fila['id_sitio'],
					id_area=>$fila['id_area'],
					nombre_area=>$fila['nombre_area'],
					tipo_area=>$fila['tipo_area']);
		}
		return $res;
	}
	
	
	public function listaSitioNormal($conexion, $tipoSitio, $txtSitio, $nombreProvincia)
	{
		$busqueda0 = '';
		$busqueda1 = '';
		switch ($tipoSitio){
			case 1: $busqueda0 = " and s.identificador_operador = '".$txtSitio."'";break;
			case 2: $busqueda0 = " and UPPER(o.apellido_representante) like '%".strtoupper($txtSitio)."%'";break;
			case 3: $busqueda0 = " and UPPER(s.nombre_lugar) like '%".strtoupper($txtSitio)."%'";break;
		}
		$res = $conexion->ejecutarConsulta("select distinct s.id_sitio
				, s.identificador_operador
				, (o.nombre_representante || ' ' || o.apellido_representante) nombres
				, s.nombre_lugar granja
				, s.provincia
				, s.codigo_provincia
				from g_operadores.operadores o
				, g_operadores.sitios s
				, g_operadores.operaciones op
				, g_catalogos.tipos_operacion t
				where o.identificador = s.identificador_operador
				and o.identificador = op.identificador_operador
				and s.identificador_operador = op.identificador_operador
				and op.id_tipo_operacion = t.id_tipo_operacion
				and t.nombre = 'Productor'
				and t.id_area = 'SA'
				and s.provincia='$nombreProvincia'
				".$busqueda0."
											order by s.nombre_lugar asc
											");
		return $res;
	}
	
	
	
	public function AutorizadosMovilizacion($conexion, $idSitio){
		$autorizado = $conexion->ejecutarConsulta("SELECT am.id_autorizar_movilizacion, am.id_sitio, am.id_area, am.identificador_propietario,
				am.identificador_autorizado, am.nombre_autorizado, am.fecha_autorizacion,
				am.fecha_registro, am.fecha_modificacion, am.observacion, am.estado, (o.nombre_representante ||' '|| o.apellido_representante) representante
				FROM g_movilizacion_animal.autorizar_movilizaciones am, g_operadores.operadores o
				where am.identificador_autorizado=o.identificador and am.estado='activo'   and am.id_sitio=$idSitio
					
				");
	
		while ($fila = pg_fetch_assoc($autorizado)){
		$res[] = array(identificador_autorizado=>$fila['id_autorizar_movilizacion']
				, identificador_propietario=>$fila['identificador_propietario']
									, identificador_autorizado=>$fila['identificador_autorizado']
									, id_sitio=>$fila['id_sitio']
												, representante=>$fila['representante']
							);
		}
	
													
		return $res;
	}
	
	
	public function OperadorSitios($conexion, $idSitio)
	{
		$res = $conexion->ejecutarConsulta("select
				distinct o.identificador,
				o.nombre_representante || ' ' ||			o.apellido_representante representante
				--,
				--s.id_sitio,
				--s.nombre_lugar,
				--s.direccion,
				--s.provincia,
				--s.canton,
				--s.parroquia
				from
				g_operadores.operadores o,
				g_operadores.sitios s
				where o.identificador = s.identificador_operador
				and s.id_sitio = '$idSitio';");
				return $res;
	}
	
	
	
	
	public function listaEspecieCatastroNormal($conexion, $idSitio){
		//-- Espacio no catastrado
		$sql = "select distinct c.id_sitio
		, c.id_area
		, c.id_especie
		, c.nombre_especie
		from g_vacunacion_animal.catastros c
		, g_operadores.sitios s
		, g_operadores.areas a
		, g_operadores.operadores o
		where o.identificador = s.identificador_operador
		and s.id_sitio = a.id_sitio
		and c.id_area = a.id_area
		and c.id_sitio = s.id_sitio
		and s.id_sitio = '$idSitio'";
	
		$EspecieCatastro = $conexion->ejecutarConsulta($sql);
			
		while ($fila = pg_fetch_assoc($EspecieCatastro)){
			$res[] = array(id_sitio=>$fila['id_sitio'],
					id_area=>$fila['id_area'],
					id_especie=>$fila['id_especie'],
					nombre_especie=>$fila['nombre_especie']);
		}
		return $res;
	}
	
	
	
	
	
	
	public function listaOperadoresEmpresas($conexion){
	
		$empresa = $conexion->ejecutarConsulta("select distinct o.identificador identificador_empresa
												, o.razon_social empresa, provincia, a.estado
										from g_usuario.usuario_administrador_empresas a	, g_operadores.operadores o
										where a.identificador_empresa = o.identificador	and a.estado='1'
										");
			
		while ($fila = pg_fetch_assoc($empresa)){
			$res[] = array(identificador_empresa=>$fila['identificador_empresa']
					, identificador_usuario=>$fila['identificador_usuario']
					, empresa=>$fila['empresa']
					, estado=>$fila['estado']
					, provincia=>$fila['provincia']
			);
		}
	
		return $res;
	}
	
	
	//FUNCIONES AGREGADAS PARA MOVILIZACION
	
	public function lugarEmisionMovilizacionAnimalNormal($conexion, $identificacion){
		$res = $conexion->ejecutarConsulta("select distinct r.id_tipo_lugar_emision
				, r.identificador_emisor
				, r.identificador_autoservicio
				--, (o.nombre_representante || ' ' || o.apellido_representante) nombre_emisor
				, r.nombre_emisor_movilizacion
				, r.nombre_lugar_emision
				, r.id_provincia
				, r.provincia
				from g_movilizacion_animal.responsable_movilizaciones r
				--, g_operadores.operadores o
				where 
				--o.identificador = r.identificador_emisor
				--and 
				r.estado = 'activo'
				and r.identificador_emisor = '$identificacion';");
		return $res;
	}
	
	public function AutogenerarNumerosCertificadosMovilizacion($conexion, $idProvinciaOrigen,$idProvinciaDestino){
	
		//$fechaActual=date('d-m-Y H:i:s');
		$fechaActual= date('dm').substr(date('Y'), 2);
		$res = $conexion->ejecutarConsulta("SELECT
												MAX(CAST(serie_certificado_movilizacion as  numeric(4))) valor
												
											FROM
												g_movilizacion_animal.movilizacion_animales m
											where
												m.codigo_provincia_origen='$idProvinciaOrigen' and m.codigo_provincia_destino='$idProvinciaDestino' and to_char(fecha_registro,'DDMMYY')='$fechaActual';");
											return $res;
	}
	
	//Guardar movilización animal
	public function guardarMovilizacionAnimalNormal($conexion, $numero_certificado, $id_tipo_autorizado,$identificador_autorizado,$lugar_emision
			,$id_tipo_movilizacion_origen,$id_sitio_origen,$id_area_origen,$id_tipo_movilizacion_destino,$id_sitio_destino
			,$id_area_destino,$medio_transporte,$placa,$identificacion_conductor,$descripcion_transporte,$usuario_empresa,$usuario_responsable,$cantidad,$costo
			,$total,$estado,$observacion,$ruta_numero_certificado,$hora,$fecha_movilizacion_desde, $fecha_movilizacion_hasta, $codigo_provincia_origen,$codigo_provincia_destino,$secuencia_certificado_movilizacion)
	{
		
	
		$fecha_registro = date('d-m-Y H:i:s');
	
		$res = $conexion->ejecutarConsulta("insert into g_movilizacion_animal.movilizacion_animales(
				numero_certificado, id_tipo_autorizado, identificador_autorizado, lugar_emision
				, id_tipo_movilizacion_origen, id_sitio_origen, id_area_origen, id_tipo_movilizacion_destino, id_sitio_destino
				, id_area_destino, medio_transporte, placa, identificacion_conductor
				, descripcion_transporte, usuario_empresa, usuario_responsable, cantidad, costo, total
				, estado, observacion, ruta_numero_certificado, fecha_registro, hora, fecha_movilizacion_desde, fecha_movilizacion_hasta,codigo_provincia_origen, 
       codigo_provincia_destino, serie_certificado_movilizacion)
				values ('$numero_certificado','$id_tipo_autorizado','$identificador_autorizado','$lugar_emision'
				,'$id_tipo_movilizacion_origen','$id_sitio_origen','$id_area_origen','$id_tipo_movilizacion_destino','$id_sitio_destino'
				,'$id_area_destino','$medio_transporte','$placa','$identificacion_conductor'
				,'$descripcion_transporte','$usuario_empresa','$usuario_responsable','$cantidad','$costo','$total'
				,'$estado','$observacion','$ruta_numero_certificado','$fecha_registro','$hora','$fecha_movilizacion_desde', '$fecha_movilizacion_hasta','$codigo_provincia_origen','$codigo_provincia_destino','$secuencia_certificado_movilizacion')
				RETURNING id_movilizacion_animal");
		return $res;
	}
	
	//Guardar movilización animal detalle
	public function guardarMovilizacionAnimalDetalleNormal($conexion, $id_movilizacion_animal, $id_especie, $nombre_especie
			, $id_producto, $cantidad, $costo, $total, $observacion, $numero_certificado, $fecha_certificado, $serie_aretes)
	
	
	{
	
		if($numero_certificado == 'Ninguno'){
			$res = $conexion->ejecutarConsulta("insert into g_movilizacion_animal.movilizacion_animal_detalles(
					id_movilizacion_animal, id_especie, nombre_especie
					, id_producto, cantidad, costo, total
					, observacion, numero_certificado)
					values ('$id_movilizacion_animal', '$id_especie', '$nombre_especie'
					, '$id_producto', $cantidad, $costo, $total, '$observacion','$numero_certificado')
					RETURNING id_movilizacion_animal_detalle");
			return $res;
		}
		else
		{
			$res = $conexion->ejecutarConsulta("insert into g_movilizacion_animal.movilizacion_animal_detalles(
					id_movilizacion_animal, id_especie, nombre_especie
					, id_producto, cantidad, costo, total
					, observacion, numero_certificado, fecha_certificado, serie_arete_animal)
					values ('$id_movilizacion_animal', '$id_especie', '$nombre_especie'
					, '$id_producto', $cantidad, $costo, $total
					, '$observacion', '$numero_certificado', '$fecha_certificado', '$serie_aretes')
					RETURNING id_movilizacion_animal_detalle");
			return $res;
		}
	}
	
	public function listarSerieAretesCertificado($conexion, $numeroCertificado){
		
		$res = $conexion->ejecutarConsulta("SELECT
												vaa.id_vacuna_animal_arete ,vaa.serie
											FROM 
												g_vacunacion_animal.vacuna_animales AS va
											INNER JOIN g_vacunacion_animal.vacuna_animal_aretes as vaa ON (va.id_vacuna_animal = vaa.id_vacuna_animal)
											WHERE 
												va.num_certificado='$numeroCertificado'");

		return $res;
	}
	
	
	//FUNCIONES PARA LA FISCALIZACION MOVILIZACION ANIMAL
	public function listaFiscalizacionMovilizacionAnimal($conexion, $usuario_responsable, $numeroCertificado, $fechaInicio, $fechaFin, $estado){
		$busqueda0 = '';
		$busqueda1 = '';
		$busqueda2 = '';
		$busqueda3 = '';
	
		if(($numeroCertificado=="0") && ($fechaInicio=="0") && ($fechaFin=="0"))
			$busqueda0 = " and ma.fecha_registro >= current_date and ma.fecha_registro < current_date+1";
		if($numeroCertificado!="0")
			$busqueda1 = " and UPPER(ma.numero_certificado) like '%".strtoupper($numeroCertificado)."' ";
		if(($fechaInicio!="0") && ($fechaFin!="0")){
			$fechaInicio1= str_replace("/","-",$fechaInicio);
			$fechaInicio2 = strtotime($fechaInicio1);
			$fechaInicio3  = date('d/m/Y',$fechaInicio2);
	
			$fechaFin1= str_replace("/","-",$fechaFin);
			$fechaFin2 = strtotime ('+1 day',strtotime($fechaFin1));
			$fechaFin3  = date('d/m/Y',$fechaFin2);
			$busqueda2 = " and ma.fecha_registro >= '".$fechaInicio3."' and ma.fecha_registro <= '".$fechaFin3."' ";
		}
		if ($estado==2){
			if($busqueda1=='')
				$busqueda3 = " and ma.estado_fiscalizacion = 'fiscalizado' ";
		}
		if ($estado==1 || $estado==0){
			if($busqueda1=='')
				$busqueda3 = " and ma.estado_fiscalizacion is null ";
		}
		$res = $conexion->ejecutarConsulta("SELECT 
												ma.id_movilizacion_animal
												,ma.numero_certificado
												,ma.lugar_emision 
												,s.nombre_lugar sitio_origen
												,ss.nombre_lugar sitio_destino
												,to_char(ma.fecha_registro,'DD/MM/YYYY') fecha_registro
											FROM 
												g_movilizacion_animal.movilizacion_animales ma
												,g_operadores.operadores oa
												,g_operadores.sitios s
												,g_operadores.areas a
												,g_operadores.sitios ss
												,g_operadores.areas aa
											WHERE
												ma.identificador_autorizado=oa.identificador
												and ma.id_sitio_origen=s.id_sitio
												and ma.id_area_origen=a.id_area
												and ma.id_sitio_destino=ss.id_sitio
												and ma.id_area_destino=aa.id_area
												".$busqueda0."
												".$busqueda1."
				                                ".$busqueda2."
												".$busqueda3."
											");
		return $res;
	}

	
	public function listaFiscalizacionMovilizacionAnimalFiltro($conexion, $idMovilizacion){
		$res = $conexion->ejecutarConsulta("SELECT
												 ma.id_movilizacion_animal
												,ma.numero_certificado
												,ma.lugar_emision
												,s.nombre_lugar sitio_origen
												,a.nombre_area area_origen
												,ss.nombre_lugar sitio_destino
												,aa.nombre_area area_destino
												,case when oa.razon_social = '' then oa.nombre_representante ||' '|| oa.apellido_representante else oa.razon_social end nombre_autorizado
												,(case ma.id_tipo_autorizado when  '1' then  'Propietario' when '2' then 'Autorizado' end)
												,ma.medio_transporte
												,ma.placa
												,ma.descripcion_transporte
												,to_char(ma.fecha_registro,'DD/MM/YYYY') fecha_registro
												,to_char(ma.fecha_movilizacion_desde,'DD/MM/YYYY') fecha_movilizacion_desde
												,to_char(ma.fecha_movilizacion_hasta,'DD/MM/YYYY') fecha_movilizacion_hasta
												,ma.total
												,ma.estado
											FROM
												g_movilizacion_animal.movilizacion_animales ma
											    INNER JOIN g_operadores.sitios s ON ma.id_sitio_origen = s.id_sitio
											    INNER JOIN g_operadores.sitios ss ON ma.id_sitio_destino = ss.id_sitio
											    INNER JOIN g_operadores.areas a ON ma.id_area_origen = a.id_area
											    INNER JOIN g_operadores.areas aa ON ma.id_area_destino = aa.id_area
											    INNER JOIN g_operadores.operadores oa ON ma.identificador_autorizado=oa.identificador
											WHERE
												 ma.id_movilizacion_animal='$idMovilizacion' ");
		return $res;
	}
	
	public function listaFiscalizacionDetalleMovilizacionAnimalFiltro($conexion, $idMovilizacion){
		$res = $conexion->ejecutarConsulta("SELECT				
											    case when dm.fecha_certificado is not null then to_char(dm.fecha_certificado,'DD/MM/YYYY') else '' end fecha_certificado,
											    dm.nombre_especie nombre_especie,
											    p.nombre_comun producto,
											    dm.cantidad cantidad_producto,
											    dm.numero_certificado AS numero_certificado_vacunacion--,
											    --dm.serie_arete_animal as aretes
											FROM
												g_movilizacion_animal.movilizacion_animales m
											    INNER JOIN g_movilizacion_animal.movilizacion_animal_detalles dm ON m.id_movilizacion_animal = dm.id_movilizacion_animal
											    INNER JOIN g_catalogos.productos p ON dm.id_producto = p.id_producto
											WHERE
												m.id_movilizacion_animal ='$idMovilizacion' ");
		return $res;
	}

	public function abrirFiscalizacionMovilizacionAnimal($conexion, $idMovilizacion){
		$res = $conexion->ejecutarConsulta("select
												id_movilizacion_fiscalizacion
												, secuencial_fiscalizacion || to_char(fecha_registro_fiscalizacion,'DDMMYY') numero_certificado
												, usuario_responsable_fiscalizacion
												, observacion_fiscalizacion
												, estado_fiscalizacion
												, to_char(fecha_fiscalizacion,'DD/MM/YYYY') fecha_fiscalizacion
											from
												g_movilizacion_animal.movilizacion_animales_fiscalizaciones
											where
												id_movilizacion_animal = $idMovilizacion");
				return $res;
	}
	
	public function  generarCertificadoFiscalizacionMovilizacionAnimal($conexion){
		$fechaActual= date('dm').substr(date('Y'), 2);
		$res = $conexion->ejecutarConsulta("select
												MAX(CAST(secuencial_fiscalizacion as  numeric(6))) numero
											from
												g_movilizacion_animal.movilizacion_animales_fiscalizaciones
											where  
												to_char(fecha_registro_fiscalizacion,'DDMMYY')='$fechaActual'");
		return $res;
	}
	
	public function guardarDatosFiscalizadormovilizacionAnimal($conexion, $idMovilizacion, $secuencial, $usuarioResponsable, $observacion, $estado, $fechaFiscalizacion)
	{
		$fechaRegistroFiscalizacion = date('d-m-Y H:i:s');
		$res = $conexion->ejecutarConsulta("insert into 
												g_movilizacion_animal.movilizacion_animales_fiscalizaciones(id_movilizacion_animal, secuencial_fiscalizacion,  
												usuario_responsable_fiscalizacion,observacion_fiscalizacion, estado_fiscalizacion, fecha_fiscalizacion, fecha_registro_fiscalizacion)
											values ('$idMovilizacion', '$secuencial', '$usuarioResponsable','$observacion','$estado','$fechaFiscalizacion',
												'$fechaRegistroFiscalizacion')  RETURNING id_movilizacion_fiscalizacion");
	
		return $res;
	}
	
	public function actualizarEstadoFiscalizadorMovilizacionAnimal($conexion, $idMovilizacion)
	{
		$res = $conexion->ejecutarConsulta("update 
												g_movilizacion_animal.movilizacion_animales
											set 
												estado_fiscalizacion = 'fiscalizado'
											where
												id_movilizacion_animal = '$idMovilizacion';");
		return $res;
	}
}//fin de la clase
?>