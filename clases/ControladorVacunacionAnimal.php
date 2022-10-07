<?php
class ControladorVacunacionAnimal{

	public function obtenerOperadorVacunador($conexion){
		$opVacunador = $conexion->ejecutarConsulta("SELECT
														a.id_administrador_vacunacion
														, a.identificador_administrador
														, case when o.razon_social = '' then o.identificador ||' - '|| o.nombre_representante ||' '|| o.apellido_representante else o.identificador ||' - '|| o.razon_social end nombre_administrador
														, a.id_especie
														, a.nombre_especie
													FROM 
														g_vacunacion_animal.administrador_vacunacion a
														,g_operadores.operadores o
													WHERE 
														o.identificador = a.identificador_administrador
														and estado = 'activo';");
			
		while ($fila = pg_fetch_assoc($opVacunador)){
			$res[] = array(id_administrador_vacunacion=>$fila['id_administrador_vacunacion']
					,identificador_administrador=>$fila['identificador_administrador']
					,nombre_administrador=>$fila['nombre_administrador']
					,id_especie=>$fila['id_especie']
					,nombre_especie=>$fila['nombre_especie']
			);
		}

		return $res;
	}
	public function listaSeleccionarOpVacunacion($conexion){
		$OperadorVacunacion = $conexion->ejecutarConsulta("SELECT DISTINCT
																o.identificador identificador_administrador
																, case when o.razon_social = '' then o.nombre_representante ||' '|| o.apellido_representante else o.razon_social end nombre_administrador
																, o.provincia
																, o.canton
																, t.nombre nombre_operacion
															FROM g_operadores.operaciones op
																, g_catalogos.tipos_operacion t
																, g_operadores.operadores o
															WHERE
																o.identificador = op.identificador_operador
																and op.id_tipo_operacion = t.id_tipo_operacion
																and t.nombre = 'Operador de vacunación'
																and t.id_area = 'SA'
																ORDER BY o.identificador asc;");

		while ($fila = pg_fetch_assoc($OperadorVacunacion)){
			$res[] = array(identificador_administrador=>$fila['identificador_administrador'],
					nombre_administrador=>$fila['nombre_administrador'],
					provincia=>$fila['provincia'],
					canton=>$fila['canton'],
					nombre_operacion=>$fila['nombre_operacion']
			);
		}

		return $res;
	}

	public function listaSeleccionarPtoDistribucion($conexion, $especie){
		$PtoDistribucion = $conexion->ejecutarConsulta("SELECT DISTINCT o.identificador identificador_distribuidor
				, case when o.razon_social = '' then o.identificador ||' - '|| o.nombre_representante ||' '|| o.apellido_representante else o.identificador ||' - '|| o.razon_social end nombre_distribuidor
				, o.provincia
				, o.canton
				, t.nombre nombre_operacion
				FROM g_operadores.operaciones op
				, g_catalogos.tipos_operacion t
				, g_operadores.operadores o
				, g_catalogos.areas_operacion a
				WHERE o.identificador = op.identificador_operador
				and op.id_tipo_operacion = t.id_tipo_operacion
				and t.id_tipo_operacion = a.id_tipo_operacion
				and t.nombre = 'Distribuidor vacuna oficial'
				and t.id_area = 'SA'
				ORDER BY o.identificador asc
				");

		while ($fila = pg_fetch_assoc($PtoDistribucion)){
			$res[] = array(identificador_distribuidor=>$fila['identificador_distribuidor'],
					nombre_distribuidor=>$fila['nombre_distribuidor'],
					provincia=>$fila['provincia'],
					canton=>$fila['canton'],
					nombre_operacion=>$fila['nombre_operacion']
			);
		}

		return $res;
	}

	public function seleccionarAdministradorVacunador($conexion){
		$vacunador = $conexion->ejecutarConsulta("SELECT o.identificador identificador_vacunador
				, o.nombre_representante || ' ' || apellido_representante nombre_vacunador
				, o.provincia
				, o.canton
				, t.nombre nombre_operacion
				FROM g_operadores.operaciones op
				, g_catalogos.tipos_operacion t
				, g_operadores.operadores o
				WHERE o.identificador = op.identificador_operador
				and op.id_tipo_operacion = t.id_tipo_operacion
				and t.nombre = 'Vacunador oficial'
				and t.id_area = 'SA'
				");

		while ($fila = pg_fetch_assoc($vacunador)){
			$res[] = array(identificador_vacunador=>$fila['identificador_vacunador'],
					nombre_vacunador=>$fila['nombre_vacunador'],
					provincia=>$fila['provincia'],
					canton=>$fila['canton'],
					nombre_operacion=>$fila['nombre_operacion']
			);
		}

		return $res;
	}

	public function seleccionarOperadoresAdministradoresVacunacion($conexion){
		$res = $conexion->ejecutarConsulta("SELECT a.id_administrador_vacunacion
				, case when o.razon_social = '' then o.nombre_representante ||' '|| o.apellido_representante else o.razon_social end nombre_administrador
				, a.id_especie
				, a.nombre_especie
				, a.identificador_administrador
				, a.estado
				FROM g_vacunacion_animal.administrador_vacunacion a,
				g_operadores.operadores o
				WHERE a.identificador_administrador = o.identificador
				ORDER BY a.identificador_administrador asc
				");
		return $res;
	}

	public function seleccionarAdministradorPtoDistribucion($conexion){
		$res = $conexion->ejecutarConsulta("SELECT d.id_administrador_distribuidor
				, case when o.razon_social = '' then o.nombre_representante ||' '|| o.apellido_representante else o.razon_social end nombre_distribuidor
				, a.id_especie
				, a.nombre_especie
				, d.identificador_distribuidor
				, a.estado
				FROM g_vacunacion_animal.administrador_vacunacion a
				, g_vacunacion_animal.administrador_distribuidor d
				, g_operadores.operadores o
				WHERE d.identificador_distribuidor = o.identificador
				and d.id_administrador_vacunacion = a.id_administrador_vacunacion
				");
		return $res;
	}

	public function seleccionarAdministradorVacunadorOficial($conexion){
		$res = $conexion->ejecutarConsulta("SELECT id_administrador_vacunador
				, v.identificador_vacunador
				, o.nombre_representante || ' ' || apellido_representante nombre_vacunador
				, v.estado
				FROM g_vacunacion_animal.administrador_vacunacion a
				, g_vacunacion_animal.administrador_distribuidor d
				, g_vacunacion_animal.administrador_vacunador v
				, g_operadores.operadores o
				WHERE v.identificador_vacunador = o.identificador
				and v.id_administrador_distribuidor = d.id_administrador_distribuidor
				and d.id_administrador_vacunacion = a.id_administrador_vacunacion
				ORDER BY v.identificador_vacunador asc
				");
		return $res;
	}

	public function listaOperadorVacunadorDigitador($conexion){
		$opVacunador = $conexion->ejecutarConsulta("SELECT a.id_administrador_vacunacion
				, a.identificador_administrador
				, case when o.razon_social = '' then o.identificador ||' - '|| o.nombre_representante ||' '|| o.apellido_representante else o.identificador ||' - '|| o.razon_social end nombre_administrador
				, a.id_especie
				, a.nombre_especie
				FROM g_vacunacion_animal.administrador_vacunacion a,
				g_operadores.operadores o
				WHERE o.identificador = a.identificador_administrador
				and a.identificador_administrador not in (SELECT DISTINCT identificador_empresa FROM g_usuario.usuario_administrador_empresas)
				and estado = 'activo'
				");
			
		while ($fila = pg_fetch_assoc($opVacunador)){
			$res[] = array(id_administrador_vacunacion=>$fila['id_administrador_vacunacion']
					,identificador_administrador=>$fila['identificador_administrador']
					,nombre_administrador=>$fila['nombre_administrador']
					,id_especie=>$fila['id_especie']
					,nombre_especie=>$fila['nombre_especie']
			);
		}

		return $res;
	}

	public function seleccionarVacunador($conexion){
		$res = $conexion->ejecutarConsulta("SELECT av.id_administrador_vacunador
				, av.identificador_vacunador
				, o.nombre_representante ||' '|| o.apellido_representante nombre_vacunador
				, av.estado
				FROM g_vacunacion_animal.administrador_vacunador av
				, g_operadores.operadores o
				WHERE av.identificador_vacunador = o.identificador
				");
		return $res;
	}

	public function filtrarAdministradorVacunacion($conexion){
		$operadoresAdministradorVacunacion = $conexion->ejecutarConsulta("SELECT a.id_administrador_vacunacion
				, case when o.razon_social = '' then o.identificador ||' - '|| o.nombre_representante ||' '|| o.apellido_representante else o.identificador ||' - '|| o.razon_social end nombre_administrador
				, a.id_especie
				, a.nombre_especie
				, a.identificador_administrador
				, a.estado
				FROM g_vacunacion_animal.administrador_vacunacion a,
				g_operadores.operadores o
				WHERE a.identificador_administrador = o.identificador
				");

		while ($fila = pg_fetch_assoc($operadoresAdministradorVacunacion)){
			$res[] = array(
					id_administrador_vacunacion=>$fila['id_administrador_vacunacion']
					, nombre_administrador=>$fila['nombre_administrador']
					, id_especie=>$fila['id_especie']
					, nombre_especie=>$fila['nombre_especie']
					, identificador_administrador=>$fila['identificador_administrador']
					, estado=>$fila['estado']
			);
		}
			
		return $res;
	}

	public function obtenerVacunadorOficial($conexion){
		$vacunador = $conexion->ejecutarConsulta("SELECT DISTINCT o.identificador identificador_vacunador
				, o.nombre_representante || ' ' || apellido_representante nombre_vacunador
				, t.nombre nombre_operacion
				FROM g_operadores.operaciones op
				, g_catalogos.tipos_operacion t
				, g_operadores.operadores o
				WHERE o.identificador = op.identificador_operador
				and op.id_tipo_operacion = t.id_tipo_operacion
				and t.nombre = 'Vacunador oficial'
				and t.id_area = 'SA'
				ORDER BY o.identificador asc
				");

		while ($fila = pg_fetch_assoc($vacunador)){
			$res[] = array(identificador_vacunador=>$fila['identificador_vacunador'],
					nombre_vacunador=>$fila['nombre_vacunador'],
					nombre_operacion=>$fila['nombre_operacion']
			);
		}

		return $res;
	}


	public function filtrarPuntoDistribucion($conexion, $id_administrador_vacunacion){
		$res = $conexion->ejecutarConsulta("SELECT DISTINCT d.id_administrador_distribuidor
				, d.id_administrador_vacunacion
				, case when o.razon_social = '' then o.identificador ||' - '|| o.nombre_representante ||' '|| o.apellido_representante else o.identificador ||' - '|| o.razon_social end nombre_distribuidor
				, d.identificador_distribuidor
				FROM g_vacunacion_animal.administrador_vacunacion a
				, g_vacunacion_animal.administrador_distribuidor d
				, g_operadores.operadores o
				WHERE d.identificador_distribuidor = o.identificador
				and d.id_administrador_vacunacion = $id_administrador_vacunacion
				");
			
		return $res;
	}

	public function obtenerAdministradorVacunacion($conexion, $id_administrador_vacunacion){
		$res = $conexion->ejecutarConsulta("SELECT a.id_administrador_vacunacion
				, case when o.razon_social = '' then o.nombre_representante ||' '|| o.apellido_representante else o.razon_social end nombre_administrador
				, a.id_especie
				, a.nombre_especie
				, a.identificador_administrador
				, a.estado
				FROM g_vacunacion_animal.administrador_vacunacion a,
				g_operadores.operadores o
				WHERE a.identificador_administrador = o.identificador
				and a.id_administrador_vacunacion = $id_administrador_vacunacion
				");
		return $res;
	}

	public function obtenerAdministradorPuntoDistribucion($conexion, $id_administrador_distribuidor){
		$res = $conexion->ejecutarConsulta("SELECT d.id_administrador_distribuidor
				, case when o.razon_social = '' then o.nombre_representante ||' '|| o.apellido_representante else o.razon_social end nombre_distribuidor
				, a.id_especie
				, a.nombre_especie
				, d.identificador_distribuidor
				, d.estado
				, a.identificador_administrador
				, case when oa.razon_social = '' then oa.nombre_representante ||' '|| oa.apellido_representante else oa.razon_social end nombre_administrador
				FROM g_vacunacion_animal.administrador_vacunacion a
				, g_vacunacion_animal.administrador_distribuidor d
				, g_operadores.operadores o
				, g_operadores.operadores oa
				WHERE d.identificador_distribuidor = o.identificador
				and d.id_administrador_vacunacion = a.id_administrador_vacunacion
				and a.identificador_administrador = oa.identificador
				and d.id_administrador_distribuidor = $id_administrador_distribuidor
				");
		return $res;
	}

	public function obtenerAdministradorVacunador($conexion, $id_administrador_vacunador){
		$res = $conexion->ejecutarConsulta("SELECT av.id_administrador_vacunador
				, av.identificador_vacunador
				, o.nombre_representante ||' '|| o.apellido_representante nombre_vacunador
				, v.identificador_administrador
				, case when oo.razon_social = '' then oo.nombre_representante ||' '|| oo.apellido_representante else oo.razon_social end nombre_administrador
				, d.identificador_distribuidor
				, case when od.razon_social = '' then od.nombre_representante ||' '|| od.apellido_representante else od.razon_social end nombre_distribuidor
				, v.nombre_especie
				, av.estado
				FROM g_vacunacion_animal.administrador_vacunador av
				, g_operadores.operadores o
				, g_operadores.operadores od
				, g_operadores.operadores oo
				, g_vacunacion_animal.administrador_distribuidor d
				, g_vacunacion_animal.administrador_vacunacion v
				WHERE av.identificador_vacunador = o.identificador
				and d.identificador_distribuidor = od.identificador
				and v.identificador_administrador = oo.identificador
				and av.id_administrador_distribuidor = d.id_administrador_distribuidor
				and d.id_administrador_vacunacion = v.id_administrador_vacunacion
				and av.id_administrador_vacunador = $id_administrador_vacunador
				");
		return $res;
	}

	public function seleccionarFiltroAdministradorVacunador($conexion){
		$res = $conexion->ejecutarConsulta("SELECT o.identificador identificador_vacunador
				, o.nombre_representante || ' ' || apellido_representante nombre_vacunador
				, o.provincia
				, o.canton
				, t.nombre nombre_operacion
				FROM g_operadores.operaciones op
				, g_catalogos.tipos_operacion t
				, g_operadores.operadores o
				WHERE o.identificador = op.identificador_operador
				and op.id_tipo_operacion = t.id_tipo_operacion
				and t.nombre = 'Vacunador oficial'
				and t.id_area = 'SA'
				");

		return $res;
	}

	public function guardarDatosVacunador($conexion, $id_administrador_distribuidor, $identificador_vacunador, $usuario_creacion, $estado)
	{
		$fecha_registro = date('d-m-Y H:i:s');
		$res = $conexion->ejecutarConsulta("INSERT INTO g_vacunacion_animal.administrador_vacunador(
				id_administrador_distribuidor, identificador_vacunador
				, estado, usuario_creacion, fecha_registro)
				values ('$id_administrador_distribuidor','$identificador_vacunador'
				,'$estado','$usuario_creacion', '$fecha_registro')  RETURNING id_administrador_vacunador");
		return $res;
	}

	public function listaAnimales($conexion)
	{
		$res = $conexion->ejecutarConsulta("SELECT
				p.id_producto,
				p.nombre_comun animal
				FROM
				g_catalogos.productos p,
				g_catalogos.subtipo_productos s,
				g_catalogos.tipo_productos c
				WHERE
				p.id_subtipo_producto = s.id_subtipo_producto
				and s.id_tipo_producto = c.id_tipo_producto
				and p.nombre_comun in ('Lechones','Levante','Engorde','Reemplazo','Verracos','Madres','Adulto')
				and c.nombre = 'Cerdos vivos'");
		return $res;
	}

	public function validarCertificadosVacunacion($conexion)
	{
		$fechaActual=date('d-m-Y H:i:s');
		$validarCertificado = $conexion->ejecutarConsulta("SELECT id_control_documento
				, id_especie
				, nombre_especie
				, tipo_documento
				, numero_digitos
				, numeracion_documento
				, serie_inicio
				, serie_fin
				, fecha_registro
				, fecha_caducidad
				FROM g_vacunacion_animal.control_documentos
				WHERE estado = 'activo'
				and fecha_caducidad >= '".$fechaActual."'
				;");

		while ($fila = pg_fetch_assoc($validarCertificado)){
			$res[] = array(id_control_documento=>$fila['id_control_documento']
					, id_especie=>$fila['id_especie']
					, nombre_especie=>$fila['nombre_especie']
					, tipo_documento=>$fila['tipo_documento']
					, numero_digitos=>$fila['numero_digitos']
					, numeracion_documento=>$fila['numeracion_documento']
					, serie_inicio=>$fila['serie_inicio']
					, serie_fin=>$fila['serie_fin']
					, fecha_registro=>$fila['fecha_registro']
					, fecha_caducidad=>$fila['fecha_caducidad']
			);
		}
		return $res;
	}

	public function listaAdministradorEmpresa($conexion, $usuario_responsable){
		$res = $conexion->ejecutarConsulta("SELECT o.identificador
				, o.razon_social empresa
				, a.estado
				FROM g_usuario.usuario_administrador_empresas a
				, g_operadores.operadores o
				WHERE a.identificador_empresa = o.identificador
				and a.identificador = '$usuario_responsable'
				");
		return $res;
	}
		
	public function listaArea($conexion,$idSitio){

		$res = $conexion->ejecutarConsulta("SELECT
				a.id_sitio,
				a.id_area,
				a.nombre_area,
				a.tipo_area
				FROM
				g_operadores.operadores o,
				g_operadores.sitios s,
				g_operadores.areas a
				WHERE
				o.identificador = s.identificador_operador
				and a.id_sitio = s.id_sitio
				and a.tipo_area = 'Lugar de producción'
				and s.id_sitio='$idSitio';");
			
		return $res;
	}

	public function listaAreaNormal($conexion)
	{
		$Lugar = $conexion->ejecutarConsulta("SELECT
				a.id_sitio,
				a.id_area,
				a.nombre_area,
				a.tipo_area
				FROM
				g_operadores.operadores o,
				g_operadores.sitios s,
				g_operadores.areas a
				WHERE
				o.identificador = s.identificador_operador
				and a.id_sitio = s.id_sitio
				and a.tipo_area = 'Lugar de producción'
				;");
		while ($fila = pg_fetch_assoc($Lugar)){
			$res[] = array(id_sitio=>$fila['id_sitio'],
					id_area=>$fila['id_area'],
					nombre_area=>$fila['nombre_area'],
					tipo_area=>$fila['tipo_area']);
		}
		return $res;
	}

	public function listaAreaEmpresa($conexion, $identificador_administrador_empresa){
		$busquedaSitioArea = '';
		switch ($tipoSitio){
			case 1: $busquedaSitioArea = "s.identificador_operador = '".$txtSitio."'"; break;
			case 2: $busquedaSitioArea = "UPPER(o.apellido_representante) like '%".strtoupper($txtSitio)."%'"; break;
			case 3: $busquedaSitioArea = "UPPER(s.nombre_lugar) like '%".strtoupper($txtSitio)."%'"; break;
			case 4: $busquedaSitioArea = "s.id_sitio=$txtSitio"; break;
		}

		$Lugar = $conexion->ejecutarConsulta("SELECT
				a.id_sitio,
				a.id_area,
				a.nombre_area,
				a.tipo_area
				FROM
				g_operadores.operadores o,
				g_operadores.sitios s,
				g_operadores.areas a,
				g_usuario.usuario_administrador_empresas e
				WHERE e.identificador_empresa = o.identificador
				and o.identificador = s.identificador_operador
				and a.id_sitio = s.id_sitio
				and e.identificador = '$identificador_administrador_empresa';");
		while ($fila = pg_fetch_assoc($Lugar)){
			$res[] = array(id_sitio=>$fila['id_sitio'],
					id_area=>$fila['id_area'],
					nombre_area=>$fila['nombre_area'],
					tipo_area=>$fila['tipo_area']);
		}
		return $res;
	}

	public function listaAreaEspecie($conexion, $tipoSitio, $txtSitio, $idEspecie){
		$busquedaSitioArea = '';
		if (($tipoSitio==4) || ($tipoSitio==5)){//Ferias y centro exposición
			$busquedaSitioArea = "UPPER(s.nombre_lugar) like '%".strtoupper($txtSitio)."%'";
			$Lugar = $conexion->ejecutarConsulta("SELECT
					e.id_evento
					, e.id_sitio
					, e.id_area
					, e.id_especie
					, e.nombre_especie
					, s.nombre_lugar nombre_sitio
					, a.nombre_area
					, to_char(e.fecha_evento,'DD/MM/YYYY') fecha_evento
					FROM g_vacunacion_animal.eventos e
					, g_operadores.sitios s
					, g_operadores.areas a
					WHERE s.id_sitio = e.id_sitio
					and a.id_area = e.id_area
					and e.estado = 'activo'
					and e. id_especie = $idEspecie
					and ".$busquedaSitioArea." ;");

		}else{
			switch ($tipoSitio){
				case 1://cedula
					$busquedaSitioArea = "s.identificador_operador = '".$txtSitio."'"; break;
				case 2://apellido
					$busquedaSitioArea = "UPPER(o.apellido_representante) like '%".strtoupper($txtSitio)."%'";break;
				case 3://granjas
					$busquedaSitioArea = "UPPER(s.nombre_lugar) like '%".strtoupper($txtSitio)."%'"; break;
			}
			$Lugar = $conexion->ejecutarConsulta("SELECT
					a.id_sitio,
					a.id_area,
					a.nombre_area,
					a.tipo_area
					FROM
					g_operadores.operadores o,
					g_operadores.sitios s,
					g_operadores.areas a
					WHERE
					o.identificador = s.identificador_operador
					and a.id_sitio = s.id_sitio
					and ".$busquedaSitioArea." ;");
		}
			
		while ($fila = pg_fetch_assoc($Lugar)){
			$res[] = array(id_sitio=>$fila['id_sitio'],
					id_area=>$fila['id_area'],
					nombre_area=>$fila['nombre_area'],
					tipo_area=>$fila['tipo_area']);
		}
		return $res;
	}

	public function listaAreaEspecie1($conexion,$tipoDestino,$identificacion){
		$busquedaSitio = '';
		switch ($tipoDestino){
			case 1: $busquedaSitio = "  t.codigo ='PRO'	and t.id_area ='SA' and a.tipo_area = 'Lugar de producción' and o.identificador ='$identificacion'"; break;
			case 4: $busquedaSitio = "t.codigo ='FAE' and t.id_area ='AI' and a.tipo_area = 'Centro de Faenamiento' "; break;
		}

		$sql = "SELECT DISTINCT a.id_sitio,
				a.id_area,
				a.nombre_area,
				a.tipo_area
				FROM g_operadores.operadores o
				,g_operadores.sitios s
				,g_operadores.areas a
				,g_operadores.operaciones op
				,g_operadores.productos_areas_operacion pao
				,g_catalogos.tipos_operacion t
				WHERE o.identificador = s.identificador_operador
				and o.identificador = op.identificador_operador
				and s.identificador_operador = op.identificador_operador
				and op.id_tipo_operacion = t.id_tipo_operacion
				and s.id_sitio = a.id_sitio
				and pao.id_operacion=op.id_operacion
				and pao.id_area=a.id_area
				and ".$busquedaSitio."
						ORDER BY a.nombre_area asc ;";

		$LugarArea = $conexion->ejecutarConsulta($sql);

		while ($fila = pg_fetch_assoc($LugarArea)){
			$res[] = array(id_sitio=>$fila['id_sitio'],
					id_area=>$fila['id_area'],
					nombre_area=>$fila['nombre_area'],
					tipo_area=>$fila['tipo_area']);
		}
			
		return $res;
	}

	public function listaEspecieCatastro($conexion, $identificador){
		$sql = "SELECT DISTINCT c.id_sitio
		, c.id_area
		, c.id_especie
		, c.nombre_especie
		FROM g_vacunacion_animal.catastros c
		, g_operadores.sitios s
		, g_operadores.areas a
		, g_operadores.operadores o
		WHERE o.identificador = s.identificador_operador
		and s.id_sitio = a.id_sitio
		and c.id_area = a.id_area
		and c.id_sitio = s.id_sitio
		and o.identificador = '$identificador'";

		$EspecieCatastro = $conexion->ejecutarConsulta($sql);
			
		while ($fila = pg_fetch_assoc($EspecieCatastro)){
			$res[] = array(id_sitio=>$fila['id_sitio'],
					id_area=>$fila['id_area'],
					id_especie=>$fila['id_especie'],
					nombre_especie=>$fila['nombre_especie']);
		}
		return $res;
	}

	public function listaProductoCatastro($conexion, $idSitio, $idArea, $idEspecie){

		$productoCatastro = $conexion->ejecutarConsulta("SELECT c.id_producto
				, p.nombre_comun producto
				, c.numero_documento
				, c.total_vacunado total
				FROM g_vacunacion_animal.catastros c,
				g_catalogos.productos p
				WHERE c.id_producto = p.id_producto
				and c.id_concepto_catastro not in (5, 6, 7, 8)
				and c.numero_documento not in ('Ninguno')
				and c.total_vacunado > 0
				and c.id_catastro in (SELECT max(id_catastro) id
				FROM g_vacunacion_animal.catastros c
				WHERE id_sitio = $idSitio
				and id_area = $idArea
				and cantidad_vacunado is not null GROUP BY c.id_producto)
				union
				SELECT c.id_producto
				, p.nombre_comun producto
				, c.numero_documento
				, c.total
				FROM g_vacunacion_animal.catastros c, g_catalogos.productos p
				WHERE c.id_producto = p.id_producto
				and c.numero_documento in ('Ninguno')
				and c.id_catastro in (SELECT max(id_catastro) id
				FROM g_vacunacion_animal.catastros c
				WHERE id_sitio = $idSitio
				and id_area = $idArea
				and cantidad is not null
				and id_producto in (SELECT id_producto FROM g_catalogos.productos_animales
				WHERE id_especie = 6 and tipo_documento = 'ninguno'))
				");

		while ($fila = pg_fetch_assoc($productoCatastro)){
			$res[] = array(id_producto=>$fila['id_producto'],
					producto=>$fila['producto'],
					numero_documento=>$fila['numero_documento'],
					total=>$fila['total']
			);
		}
		return $res;
	}

	public function listaCertificadosVacunacion($conexion, $idSitio, $idArea, $idEspecie){
			
		$res = $conexion->ejecutarConsulta("SELECT DISTINCT numero_documento
				, fecha_vacunacion
				, to_char(fecha_vacunacion,'DD/MM/YYYY') fecha_vacunacion
				FROM g_vacunacion_animal.catastros
				WHERE id_sitio = $idSitio
				and id_area = $idArea
				and id_especie = $idEspecie
				and id_concepto_catastro not in (5, 6, 7, 8, 9, 12)
				ORDER BY numero_documento
				");

		return $res;
	}

	public function listaCertificadosProductoVacunacion($conexion, $idSitio, $idArea, $idEspecie){
		$productoCatastro = $conexion->ejecutarConsulta("SELECT id_producto
				, producto
				, numero_documento
				, total
				, edad_producto
				, to_char(fecha_nacimiento,'DD/MM/YYYY') fecha_nacimiento
				, to_char(fecha_vacunacion_desde,'DD/MM/YYYY') fecha_vacunacion_desde
				, to_char(fecha_vacunacion_hasta + '6 month','DD/MM/YYYY') fecha_vacunacion_hasta
				, id_concepto_catastro
				FROM g_movilizacion_animal.mostrar_certificado_producto($idSitio,$idArea,$idEspecie);
				");

		while ($fila = pg_fetch_assoc($productoCatastro)){
			$res[] = array(
					id_producto=>$fila['id_producto']
					, producto=>$fila['producto']
					, numero_documento=>$fila['numero_documento']
					, total=>$fila['total']
					, edad_producto=>$fila['edad_producto']
					, fecha_nacimiento=>$fila['fecha_nacimiento']
					, fecha_vacunacion_desde=>$fila['fecha_vacunacion_desde']
					, fecha_vacunacion_hasta=>$fila['fecha_vacunacion_hasta']
			);
		}
		return $res;
	}

	public function listaCertificadosProductoMovilizacion($conexion, $idSitio, $idArea, $idEspecie)
	{

		$productoCatastro = $conexion->ejecutarConsulta("SELECT DISTINCT numero_documento
				, to_char(fecha_vacunacion_desde,'DD/MM/YYYY') fecha_vacunacion_desde
				, to_char(fecha_vacunacion_hasta + '6 month','DD/MM/YYYY') fecha_vacunacion_hasta
				FROM g_movilizacion_animal.mostrar_certificado_producto($idSitio,$idArea,$idEspecie)
				ORDER BY numero_documento asc ;");

		while ($fila = pg_fetch_assoc($productoCatastro)){
			$res[] = array(numero_documento=>$fila['numero_documento']
					, fecha_vacunacion_desde=>$fila['fecha_vacunacion_desde']
					, fecha_vacunacion_hasta=>$fila['fecha_vacunacion_hasta']);
		}

		return $res;
	}

	public function listaMovilizacionAnimal($conexion, $id_sitio, $id_area){
		$movilizacion = $conexion->ejecutarConsulta("SELECT m.id_vacuna_movilizacion
				, v.num_certificado
				, m.id_vacuna_animal
				, m.id_sitio
				, (s.nombre_lugar || ' - ' || s.provincia || ' - ' || s.canton)  nombre_sitio
				, m.id_area
				, a.nombre_area
				, m.total_vacunado
				, m.cantidad_movilizado
				, m.total_movilizado
				, to_char(m.fecha_vencimiento,'DD/MM/YYYY') fecha_vencimiento
				, to_char(v.fecha_vacunacion,'DD/MM/YYYY') fecha_vacunacion
				FROM g_vacunacion_animal.vacuna_movilizaciones m,
				g_vacunacion_animal.vacuna_animales v,
				g_operadores.sitios s,
				g_operadores.areas a
				WHERE m.id_vacuna_animal = v.id_vacuna_animal
				and	m.id_sitio = s.id_sitio
				and m.id_area = a.id_area
				and s.id_sitio = a.id_sitio
				and m.id_vacuna_movilizacion in
				(
				SELECT max(id_vacuna_movilizacion) id
				FROM g_vacunacion_animal.vacuna_movilizaciones
				WHERE id_sitio = $id_sitio
				and id_area = $id_area
				and total_movilizado <> 0
				and fecha_vencimiento >= current_date
				GROUP BY id_sitio, id_area, id_vacuna_animal
		)
				");

		while ($fila = pg_fetch_assoc($movilizacion)){
			$res[] = array(
					id_vacuna_movilizacion => $fila['id_vacuna_movilizacion'],
					num_certificado => $fila['num_certificado'],
					id_vacuna_animal => $fila['id_vacuna_animal'],
					id_sitio => $fila['id_sitio'],
					nombre_sitio => $fila['nombre_sitio'],
					id_area => $fila['id_area'],
					nombre_area => $fila['nombre_area'],
					total_vacunado => $fila['total_vacunado'],
					cantidad_movilizado => $fila['cantidad_movilizado'],
					total_movilizado => $fila['total_movilizado'],
					fecha_vencimiento => $fila['fecha_vencimiento'],
					fecha_vacunacion => $fila['fecha_vacunacion']
			);
		}
		return $res;
	}

	public function guardarDatosFiscalizador($conexion, $id_vacuna_animal, $secuencial, $num_fiscalizacion, $usuario_responsable, $observacion, $estado, $fecha_fiscalizacion){
		$fecha_registro = date('d-m-Y H:i:s');
		$res = $conexion->ejecutarConsulta("INSERT INTO g_vacunacion_animal.vacuna_fiscalizaciones(
				id_vacuna_animal, secuencial, num_fiscalizacion, usuario_responsable,
				observacion, estado, fecha_registro, fecha_fiscalizacion)
				values ('$id_vacuna_animal', '$secuencial', '$num_fiscalizacion', '$usuario_responsable','$observacion','$estado',
				'$fecha_registro','$fecha_fiscalizacion')  RETURNING id_vacuna_fiscalizacion");

		return $res;
	}

	public function actualizarDatosFiscalizador($conexion, $id_vacuna_animal)
	{
		$res = $conexion->ejecutarConsulta("UPDATE g_vacunacion_animal.vacuna_animales
				set estado_fiscalizacion = 'fiscalizado'
				WHERE id_vacuna_animal = '".$id_vacuna_animal."'
				;");

		return $res;
	}

	public function  generarCertificadoFiscalizacion($conexion){
		$res = $conexion->ejecutarConsulta("SELECT
				max(secuencial) as numero
				FROM
				g_vacunacion_animal.vacuna_fiscalizaciones");
		return $res;
	}


	public function listaSitioEmpresas($conexion, $usuario_administrador_empresa)
	{
		$SitiosAdministradorEmpresa = $conexion->ejecutarConsulta("SELECT DISTINCT s.id_sitio
				, s.identificador_operador
				, (o.nombre_representante || ' ' || o.apellido_representante) nombres
				, s.nombre_lugar granja
				, s.provincia
				FROM g_operadores.operadores o
				, g_operadores.sitios s
				, g_operadores.areas aa
				, g_operadores.operaciones op
				, g_catalogos.tipos_operacion t
				, g_usuario.usuario_administrador_empresas a
				WHERE a.identificador_empresa = o.identificador
				and o.identificador = s.identificador_operador
				and o.identificador = op.identificador_operador
				and s.identificador_operador = op.identificador_operador
				and op.id_tipo_operacion = t.id_tipo_operacion
				and s.id_sitio = aa.id_sitio
				and t.codigo = 'PRO'
				and t.id_area = 'SA'
				and aa.tipo_area = 'Lugar de producción'
				and a.identificador = '$usuario_administrador_empresa'
				ORDER BY s.nombre_lugar asc;");

		while ($fila = pg_fetch_assoc($SitiosAdministradorEmpresa)){
			$res[] = array(id_sitio=>$fila['id_sitio'],
					identificador_operador=>$fila['identificador_operador'],
					nombres=>$fila['nombres'],
					granja=>$fila['granja'],
					provincia=>$fila['provincia']);
		}
		return $res;
	}

	public function listaSitio($conexion, $tipoSitio, $txtSitio)
	{
		$busqueda0 = '';
		$busqueda1 = '';
		switch ($tipoSitio){
			case 1: $busqueda0 = " and s.identificador_operador = '".$txtSitio."'";break;
			case 2: $busqueda0 = " and UPPER(o.apellido_representante) like '%".strtoupper($txtSitio)."%'";break;
			case 3: $busqueda0 = " and UPPER(s.nombre_lugar) like '%".strtoupper($txtSitio)."%'";break;
		}

		if ($autoservicio=="0") // $autoservicio 0 = usuarios comunes
			$busqueda1 = " and s.identificador_operador not in (SELECT DISTINCT identificador_empresa FROM g_usuario.usuario_administrador_empresas)";
		if ($autoservicio=="1") // $autoservicio 1 = usuarios administradores
			$busqueda1 = " and s.id_sitio in (SELECT DISTINCT r.id_sitio FROM g_usuario.usuario_administrador_empresas e , g_movilizacion_animal.responsable_movilizaciones r WHERE e.identificador = r.identificador_emisor and e.identificador = '".$usuarioAutoservicio."')";

		$res = $conexion->ejecutarConsulta("SELECT DISTINCT s.id_sitio, s.identificador_operador, (o.nombre_representante || ' ' || o.apellido_representante) nombres
				, s.nombre_lugar granja, s.provincia
				FROM g_operadores.operadores o
				, g_operadores.sitios s
				, g_operadores.areas a
				, g_operadores.operaciones op
				, g_catalogos.tipos_operacion t
				WHERE o.identificador = s.identificador_operador
				and o.identificador = op.identificador_operador
				and s.identificador_operador = op.identificador_operador
				and op.id_tipo_operacion = t.id_tipo_operacion
				and s.id_sitio = a.id_sitio
				and t.codigo = 'PRO'
				and t.id_area = 'SA'
				and a.tipo_area = 'Lugar de producción'
				".$busqueda0."
				".$busqueda1."
				ORDER BY s.nombre_lugar asc;");
			
		return $res;
	}

	public function listaSitioMovilizacion($conexion, $tipoSitio, $txtSitio, $identificacion)
	{
		$busquedaSitio = '';
		switch ($tipoSitio){
			case 1: $busquedaSitio = "r.identificador_autoservicio = '".$txtSitio."' and r.identificador_emisor = '".$identificacion."'"; break;
		}

		$res = $conexion->ejecutarConsulta("SELECT DISTINCT s.id_sitio, s.identificador_operador, (o.nombre_representante || ' ' || o.apellido_representante) nombres
				, s.nombre_lugar granja, s.provincia, s.codigo_provincia
				FROM g_operadores.operadores o
				, g_operadores.sitios s
				, g_operadores.areas a
				, g_operadores.operaciones op
				, g_operadores.productos_areas_operacion pao
				, g_catalogos.tipos_operacion t
				, g_movilizacion_animal.responsable_movilizaciones r
				WHERE o.identificador = s.identificador_operador
				and o.identificador = op.identificador_operador
				and op.id_tipo_operacion = t.id_tipo_operacion
				and s.id_sitio = a.id_sitio
				and r.id_sitio = s.id_sitio
				and pao.id_operacion=op.id_operacion
				and pao.id_area=a.id_area
				and r.identificador_autoservicio = s.identificador_operador
				and t.codigo = 'PRO'
				and t.id_area = 'SA'
				and a.tipo_area = 'Lugar de producción'
				and ".$busquedaSitio." ORDER BY s.nombre_lugar asc;");
			
		return $res;
	}

	public function listaSitioAutoservicio($conexion, $tipoLugarDestino, $identificacion){
		$sql = '';
		switch ($tipoLugarDestino){
			case 1://Sitio
				$sql = "SELECT DISTINCT s.id_sitio
						,s.nombre_lugar granja
						,s.provincia 
						,case when o.razon_social = '' then o.nombre_representante ||' '|| o.apellido_representante else o.razon_social end nombres
						, s.codigo_provincia
						FROM g_operadores.operadores o
						, g_operadores.sitios s
						, g_operadores.areas a
						, g_operadores.operaciones op
						,g_operadores.productos_areas_operacion pao
						, g_catalogos.tipos_operacion t
						WHERE o.identificador = s.identificador_operador
						and o.identificador = op.identificador_operador
						and s.identificador_operador = op.identificador_operador
						and op.id_tipo_operacion = t.id_tipo_operacion
						and s.id_sitio = a.id_sitio
						and pao.id_operacion=op.id_operacion
						and pao.id_area=a.id_area
						and t.codigo = 'PRO'
						and t.id_area = 'SA'
						and a.tipo_area = 'Lugar de producción'
						and s.identificador_operador = '".$identificacion."'
								ORDER BY
								provincia,nombres, granja asc ";
				break;

			case 4://Camal
				$sql = "SELECT DISTINCT s.id_sitio
						,s.nombre_lugar granja
						,s.provincia
						,case when o.razon_social = '' then o.nombre_representante ||' '|| o.apellido_representante else o.razon_social end nombres
						,s.codigo_provincia
						FROM
						g_operadores.operadores o
						,g_operadores.sitios s
						,g_operadores.areas a
						,g_operadores.operaciones op
						,g_operadores.productos_areas_operacion pao
						,g_catalogos.tipos_operacion t
						WHERE
						o.identificador = s.identificador_operador
						and o.identificador = op.identificador_operador
						and s.identificador_operador = op.identificador_operador
						and op.id_tipo_operacion = t.id_tipo_operacion
						and s.id_sitio = a.id_sitio
						and pao.id_operacion=op.id_operacion
						and pao.id_area=a.id_area
						and t.codigo ='FAE'
						and t.id_area ='AI'
						and a.tipo_area ='Centro de Faenamiento'
						and op.estado = 'registrado'
						ORDER BY
						provincia,nombres,granja asc";
				break;
		}

		$res = $conexion->ejecutarConsulta($sql);
			
		return $res;
	}

	public function listaSitioEspecieCatastro($conexion, $tipoSitio, $txtSitio, $autoservicio, $usuarioAutoservicio)
	{
		$busqueda0 = '';
		$busqueda1 = '';
		switch ($tipoSitio){
			case 1: $busqueda0 = " and s.identificador_operador = '".$txtSitio."'";break;
			case 2: $busqueda0 = " and UPPER(o.apellido_representante) like '%".strtoupper($txtSitio)."%'";break;
			case 3: $busqueda0 = " and UPPER(s.nombre_lugar) like '%".strtoupper($txtSitio)."%'";break;
		}

		if ($autoservicio=="0") // $autoservicio 0 = usuarios comunes
			$busqueda1 = " and s.identificador_operador not in (SELECT DISTINCT identificador_empresa FROM g_usuario.usuario_administrador_empresas)";
		if ($autoservicio=="1") // $autoservicio 1 = usuarios administradores
			$busqueda1 = " and s.id_sitio in (SELECT DISTINCT r.id_sitio FROM g_usuario.usuario_administrador_empresas e , g_movilizacion_animal.responsable_movilizaciones r WHERE e.identificador = r.identificador_emisor and e.identificador = '".$usuarioAutoservicio."')";

		$res = $conexion->ejecutarConsulta("SELECT DISTINCT s.id_sitio
				, s.identificador_operador
				, (o.nombre_representante || ' ' || o.apellido_representante) nombres
				, s.nombre_lugar granja
				, s.provincia
				FROM g_operadores.operadores o
				, g_operadores.sitios s
				, g_operadores.operaciones op
				, g_catalogos.tipos_operacion t
				WHERE o.identificador = s.identificador_operador
				and o.identificador = op.identificador_operador
				and s.identificador_operador = op.identificador_operador
				and op.id_tipo_operacion = t.id_tipo_operacion
				and t.codigo = 'PRO'
				and t.id_area = 'SA'
				".$busqueda0."
				".$busqueda1."
				ORDER BY s.nombre_lugar asc
				");
		return $res;
	}




	public function listaSitioEspecie($conexion, $tipoSitio, $txtSitio)
	{
		$busquedaSitio = '';
		switch ($tipoSitio){
			case 1: $busquedaSitio = "s.identificador_operador = '".$txtSitio."'"; break;
			case 2: $busquedaSitio = "UPPER(o.apellido_representante) like '%".strtoupper($txtSitio)."%'"; break;
			case 3: $busquedaSitio = "UPPER(s.nombre_lugar) like '%".strtoupper($txtSitio)."%'"; break;
		}

		$sitio = $conexion->ejecutarConsulta("SELECT DISTINCT s.id_sitio
				, s.identificador_operador
				, (o.nombre_representante || ' ' || o.apellido_representante) nombres
				, s.nombre_lugar granja
				, s.provincia
				FROM g_operadores.operadores o
				, g_operadores.sitios s
				, g_operadores.operaciones op
				, g_catalogos.tipos_operacion t
				WHERE o.identificador = s.identificador_operador
				and o.identificador = op.identificador_operador
				and s.identificador_operador = op.identificador_operador
				and op.id_tipo_operacion = t.id_tipo_operacion
				and t.codigo = 'PRO'
				and t.id_area = 'SA'
				and ".$busquedaSitio." ;");
			
		while ($fila = pg_fetch_assoc($sitio)){
			$res[] = array(id_sitio=>$fila['id_sitio']
					, identificador_operador=>$fila['identificador_operador']
					, nombres=>$fila['nombres']
					, granja=>$fila['granja']
					, provincia=>$fila['provincia']);
		}

		return $res;
	}

	public function listaVacunador($conexion, $tipoVdro, $txtVdro)
	{
		$busquedaVdr = '';
		switch ($tipoVdro){
			case 1: $busquedaVdr = "identificador = '".$txtVdro."'"; break;
			case 2: $busquedaVdr = "UPPER(apellido) like '%".strtoupper($txtVdro)."%'"; break;
		}

		$res = $conexion->ejecutarConsulta("SELECT DISTINCT o.identificador
				, (o.nombre_representante ||' '|| o.apellido_representante) nombre_vacunador
				FROM g_vacunacion_animal.administrador_vacunador r,
				g_vacunacion_animal.administrador_distribuidor d,
				g_vacunacion_animal.administrador_vacunacion a,
				g_operadores.operadores o
				WHERE r.id_administrador_distribuidor = d.id_administrador_distribuidor
				and d.id_administrador_vacunacion = a.id_administrador_vacunacion
				and a.id_especie = 6
				and o.identificador = r.identificador_vacunador
				and ".$busquedaVdr." ;");
			
		return $res;
	}

	public function listaVacunadorVacunacion($conexion, $nombre_especie, $identificador_administrador, $identificador_distribuidor)
	{
		$res = $conexion->ejecutarConsulta("SELECT DISTINCT o.identificador
				, (o.nombre_representante ||' '|| o.apellido_representante) nombre_vacunador
				FROM g_vacunacion_animal.administrador_vacunador r,
				g_vacunacion_animal.administrador_distribuidor d,
				g_vacunacion_animal.administrador_vacunacion a,
				g_operadores.operadores o
				WHERE r.id_administrador_distribuidor = d.id_administrador_distribuidor
				and d.id_administrador_vacunacion = a.id_administrador_vacunacion
				and o.identificador = r.identificador_vacunador
				and r.estado = 'activo'
				and a.nombre_especie = '".$nombre_especie."'
				and a.identificador_administrador = '".$identificador_administrador."'
				and d.identificador_distribuidor = '".$identificador_distribuidor."';");
			
		return $res;
	}

	public function listaOperadorSitios($conexion, $Identificacion){
		$cid_opSitios = $this->OperadorSitios($conexion, $Identificacion);
		while ($fila = pg_fetch_assoc($cid_opSitios)){
			$res[] = array(identificador=>$fila['identificador'],nombre_representante=>$fila['nombre_representante'],apellido_representante=>$fila['apellido_representante'], id_sitio=>$fila['id_sitio'], nombre_lugar=>$fila['nombre_lugar'], direccion=>$fila['direccion'], provincia=>$fila['provincia'], canton=>$fila['canton'], parroquia=>$fila['parroquia']);
		}
		return $res;
	}

	public function OperadorSitios($conexion, $Identificacion)
	{
		$res = $conexion->ejecutarConsulta("SELECT
				DISTINCT o.identificador,
				o.nombre_representante,
				o.apellido_representante,
				s.id_sitio,
				s.nombre_lugar,
				s.direccion,
				s.provincia,
				s.canton,
				s.parroquia
				FROM
				g_operadores.operadores o,
				g_operadores.sitios s
				WHERE o.identificador = s.identificador_operador
				and o.identificador = '$Identificacion';");
		return $res;
	}

	public function guardarLugarVacuna($conexion, $id_sitio, $id_vacuna_tipo_animal, $estado)
	{
		$res = $conexion->ejecutarConsulta("INSERT INTO 
				g_vacunacion_animal.vacuna_sitios
				(id_sitio, id_vacuna_tipo_animal, estado)
				values ('$id_sitio', '$id_vacuna_tipo_animal', '$estado') RETURNING id_vacuna_sitio");
		return $res;
	}

	public function guardarDatosVacunacion ($conexion, $id_sitio, $id_area, $id_especie, $nombre_especie,
			$identificador_administrador, $identificador_distribuidor, $identificador_vacunador,
			$id_lote, $id_tipo_vacuna, $num_certificado, $control_areteo,
			$usuario_responsable, $costo_vacuna, $estado_vacunado, $fecha_vacunacion){
		//$fecha_registro = date('d-m-Y H:i:s');
		$datetime = new DateTime();
		$fecha_registro= $datetime->format('d-m-Y H:i:s');
		$fecha_emision=str_replace("/","-",$fecha_vacunacion);
		$fecha_vencimiento1 = strtotime('6 month',strtotime($fecha_emision)) ;
		$fecha_vencimiento = date('d-m-Y H:i:s',$fecha_vencimiento1);
		$identificador_administrador = $identificador_administrador != "" ? "'". $identificador_administrador  ."'" : "null";


		$res = $conexion->ejecutarConsulta("INSERT INTO g_vacunacion_animal.vacuna_animales (id_sitio, id_area, id_especie, nombre_especie,
				identificador_administrador, identificador_distribuidor, identificador_vacunador,
				id_lote, id_tipo_vacuna, num_certificado, control_areteo,
				usuario_responsable, costo_vacuna, estado_vacunacion,
				fecha_registro, fecha_vacunacion, fecha_vencimiento)
				values ('$id_sitio','$id_area','$id_especie', '$nombre_especie'
				,$identificador_administrador, '$identificador_distribuidor', '$identificador_vacunador'
				,'$id_lote', '$id_tipo_vacuna', '$num_certificado', '$control_areteo'
				,'$usuario_responsable','$costo_vacuna','$estado_vacunado'
				,'$fecha_registro','$fecha_vacunacion','$fecha_vencimiento')
				returning id_vacuna_animal");

		return $res;
	}

	public function actualizarNumeroCertificado($conexion, $nombre_especie, $tipo_documento, $numero_documento, $estado)
	{
		//--'vacunacion', fiscalizacion y movilizacion
		$fecha_modificacion = date('d-m-Y H:i:s');
		$res = $conexion->ejecutarConsulta("UPDATE g_vacunacion_animal.serie_documentos
				set estado = '".$estado."'
				, fecha_modificacion = '".$fecha_modificacion."'
				WHERE nombre_especie = '".$nombre_especie."'
				and tipo_documento = '".$tipo_documento."'
				and numero_documento = '".$numero_documento."'
				;");

		return $res;
	}


	// Actualiza datos movilización
	public function actualizaDatosMovilizacion($conexion, $id_vacuna_movilizacion,$num_movilizacion,$id_provincia,$provincia,$id_canton,$canton,$usuario_responsable,$total_movilizado, $cantidad_movilizado, $observacion,$estado,$fecha_movilizacion)
	{
		$fecha_registro = date('d-m-Y H:i:s');

		$res = $conexion->ejecutarConsulta("UPDATE g_vacunacion_animal.vacuna_movilizaciones
				set num_movilizacion='$num_movilizacion'
				, id_provincia='$id_provincia'
				, provincia='$provincia'
				, id_canton='$id_canton'
				, canton='$canton'
				, usuario_responsable='$usuario_responsable'
				, total_movilizado='$total_movilizado'
				, observacion='$observacion'
				, estado='$estado'
				, fecha_registro='$fecha_registro'
				, fecha_movilizacion='$fecha_movilizacion'
				WHERE id_vacuna_movilizacion='$id_vacuna_movilizacion';");
		return $res;
	}

	// Actualizar total de existententes y vacunados
	public function actualizarDatosVacunacionTotales ($conexion, $id_vacuna_animal, $total_existente, $total_vacunado, $costo_vacuna){
		$total_vacuna = ($total_vacunado * $costo_vacuna);
		$res = $conexion->ejecutarConsulta("UPDATE
				g_vacunacion_animal.vacuna_animales
				set
				total_existente = $total_existente,
				total_vacunado = $total_vacunado,
				total_vacuna = 	$total_vacuna
				WHERE
				id_vacuna_animal=$id_vacuna_animal");
		return $res;
	}

	// Guarda datos de la detalle
	public function guardarDetalleVacunacion($conexion, $id_vacuna_animal, $id_producto, $existente, $vacunado, $observacion){
		$res = $conexion->ejecutarConsulta("INSERT INTO g_vacunacion_animal.vacuna_animal_detalles(
				id_vacuna_animal, id_producto, existente, vacunado, observacion)
				values ('$id_vacuna_animal', '$id_producto', '$existente', '$vacunado', '$observacion') RETURNING id_vacuna_animal_detalle");
			
		return $res;
	}

	// lista control areteo
	public function listaControlAreteo($conexion){
		$res = $conexion->ejecutarConsulta("SELECT
				c.id_control_areteo,
				c.id_provincia,
				(SELECT nombre FROM g_catalogos.localizacion l
				WHERE l.categoria = 1 and l.id_localizacion = c.id_provincia) provincia,
				c.id_canton,
				(SELECT nombre FROM g_catalogos.localizacion l
				WHERE l.categoria = 2 and l.id_localizacion = c.id_canton) canton,
				c.observacion,
				to_char(c.fecha_registro,'DD/MM/YYYY') fecha_registro,
				c.fecha_modificacion,
				c.estado
				FROM
				g_vacunacion_animal.control_areteo_animales c ");
		return $res;
	}

	// lista control areteo
	public function FiltrarControlAreteoVacuna($conexion){
		$res = $conexion->ejecutarConsulta("SELECT
				*
				FROM
				g_vacunacion_animal.control_areteo_animales
				WHERE estado = 'activo';");
		return $res;
	}

	//Actualizar el control del areteo
	public function actualizarControlAreteo($conexion, $id_control_areteo, $observacion, $estado, $usuario_modificacion){
		$fecha_modificacion = date('d-m-Y H:i:s');
		$res = $conexion->ejecutarConsulta("UPDATE
				g_vacunacion_animal.control_areteo_animales
				set
				id_control_areteo='".$id_control_areteo."',
				observacion='".$observacion."',
				estado='".$estado."',
				usuario_modificacion='".$usuario_modificacion."',
				fecha_modificacion='".$fecha_modificacion."'
				WHERE
				id_control_areteo = '".$id_control_areteo."';");
		return $res;
	}

	//Guardar el control del areteo
	public function guardarControlAreteo($conexion, $id_vacuna_tipo_animal, $id_provincia, $provincia, $id_canton, $canton, $observacion, $estado, $usuario_creacion)
	{
		$fecha_registro = date('d-m-Y H:i:s');

		$res = $conexion->ejecutarConsulta("INSERT INTO g_vacunacion_animal.control_areteo_animales(
				id_vacuna_tipo_animal, id_provincia, provincia, id_canton,
				canton, observacion, estado, usuario_creacion, fecha_registro)
				values
				('$id_vacuna_tipo_animal','$id_provincia', '$provincia', '$id_canton',
				'$canton', '$observacion','$estado','$usuario_creacion','$fecha_registro') RETURNING id_control_areteo");
		return $res;
	}

	// Funcion para el control del areteo
	public function busquedaControlAreteo($conexion, $id_vacuna_tipo_animal, $id_provincia, $id_canton){
		$res = $conexion->ejecutarConsulta("SELECT *
				FROM
				g_vacunacion_animal.control_areteo_animales
				WHERE
				id_vacuna_tipo_animal = $id_vacuna_tipo_animal
				and id_provincia = $id_provincia
				and id_canton = $id_canton ;");
		return $res;
	}

	// Funcion para el control del areteo
	public function busquedaDetalleMovilizacion($conexion, $id_movilizacion_animal, $id_producto){
		$res = $conexion->ejecutarConsulta("SELECT id_movilizacion_animal
				, id_movilizacion_animal_detalle
				, cantidad
				FROM g_vacunacion_animal.movilizacion_animal_detalles
				WHERE id_movilizacion_animal =  $id_movilizacion_animal
				and id_producto = $id_producto
				;");
		return $res;
	}

	// Funcion para la lista listro control del areteo
	public function listaFiltroControlAreteo($conexion, $id_control_areteo){

		$res = $conexion->ejecutarConsulta("SELECT
				id_control_areteo,
				id_vacuna_tipo_animal,
				id_provincia,
				(SELECT nombre FROM g_catalogos.localizacion WHERE id_localizacion = c.id_provincia) provincia,
				id_canton,
				(SELECT nombre FROM g_catalogos.localizacion WHERE id_localizacion = c.id_canton) canton,
				observacion,
				estado
				FROM
				g_vacunacion_animal.control_areteo_animales c
				WHERE
				id_control_areteo = $id_control_areteo;");
		return $res;
	}

	public function guardarDatosVacunacionAnimalArete($conexion, $id_vacuna_animal, $serie, $fecha_vacunacion, $fecha_vencimiento){
		$res = $conexion->ejecutarConsulta("INSERT INTO g_vacunacion_animal.vacuna_animal_aretes
				(id_vacuna_animal, serie, fecha_vacunacion, fecha_vencimiento)
				VALUES ('$id_vacuna_animal', '$serie', '$fecha_vacunacion', '$fecha_vencimiento')");
			
		return $res;
	}

	public function guardarDatosAlmacen($conexion, $id_provincia, $provincia, $id_canton, $canton, $nombre_almacen, $lugar_almacen, $estado){
		$res = $conexion->ejecutarConsulta("INSERT INTO g_catalogos.almacenes(
				id_provincia, provincia, id_canton, canton, nombre_almacen, lugar_almacen, estado)
				values ('$id_provincia','$provincia','$id_canton','$canton', '$nombre_almacen', '$lugar_almacen', '$estado')  RETURNING id_almacen");
		return $res;
	}

	public function guardarAdministradorVacunacionAnimal($conexion, $id_especie, $nombre_especie, $identificador_administrador, $estado){
		$res = $conexion->ejecutarConsulta("INSERT INTO g_vacunacion_animal.administrador_vacunacion(
				id_especie, nombre_especie, identificador_administrador, estado)
				values ('$id_especie', '$nombre_especie', '$identificador_administrador', '$estado')");
		return $res;
	}

	public function guardarAdministradorDistribuidor($conexion, $id_administrador_vacunacion, $identificador_distribuidor, $estado){
		$res = $conexion->ejecutarConsulta("INSERT INTO g_vacunacion_animal.administrador_distribuidor(
				id_administrador_vacunacion, identificador_distribuidor, estado)
				values ('$id_administrador_vacunacion', '$identificador_distribuidor', '$estado')");
		return $res;
	}

	public function actualizarDatosAlmacen($conexion, $id_almacen, $nombre_almacen, $lugar_almacen, $estado){
		$res = $conexion->ejecutarConsulta("UPDATE g_catalogos.almacenes
				set nombre_almacen = '" .$nombre_almacen. "',
				lugar_almacen = '" .$lugar_almacen. "',
				estado = '$estado'
				WHERE id_almacen = $id_almacen");
		return $res;
	}

	public function listaReporteVacunacionAnimal($conexion, $identificadorEmpresa,$identificadorDistribuidor,$identificadorVacunador,$provincia, $fechaInicio, $fechaFin, $estado){
		$busqueda0 = '';
		$busqueda1 = '';
		$busqueda2 = '';
		$busqueda3 = '';

		if($identificadorEmpresa=="traspatio" || $identificadorEmpresa=="autoservicio" ){
			$busqueda0 = "AND em.tipo='$identificadorEmpresa'";
		}else{
			$busqueda0 = " AND em.id_empresa = '$identificadorEmpresa' ";
			if($identificadorDistribuidor!="TODOS")
				$busqueda0.= " AND v.identificador_distribuidor='$identificadorDistribuidor'";
				
			if($identificadorVacunador!="TODOS")
				$busqueda0.= " AND v.identificador_vacunador='$identificadorVacunador'";
		}

		if($provincia!="TODOS")
			$busqueda3 = " and s.provincia='".$provincia."'";

		if ($estado!="0")
			$busqueda1 = " and v.estado_vacunacion = '".$estado."' ";
			
		if(($fechaInicio!="0") && ($fechaFin!="0")){
			$fechaInicio1 = str_replace("/","-",$fechaInicio);
			$fechaInicio2 = strtotime($fechaInicio1);//la fecha de vencimiento 1 day
			$fechaInicio3 = date('d/m/Y',$fechaInicio2);

			$fechaFin1 = str_replace("/","-",$fechaFin);
			$fechaFin2 =  strtotime ('+1 day',strtotime($fechaFin1));//la fecha de vencimiento 1 day
			$fechaFin3 = date('d/m/Y',$fechaFin2);
			$busqueda2 = " and v.fecha_registro >= '".$fechaInicio3."' and v.fecha_registro <= '".$fechaFin3."' ";
		}

		$res = $conexion->ejecutarConsulta("SELECT 
												'Usuario externo'::text AS tipo_digitador,
												upper(s.nombre_lugar::text) AS nombre_sitio,
												upper(a.nombre_area::text) AS nombre_area,
												s.provincia,
												s.canton,
												s.parroquia,
												v.nombre_especie,
												em.tipo tipo_operadora,
												CASE
												WHEN oa.razon_social::text = ''::text THEN upper((oa.nombre_representante::text || ' '::text) || oa.apellido_representante::text)::character varying::text
												ELSE upper(oa.razon_social::text)
												END AS nombre_administrador,
												CASE
												WHEN od.razon_social::text = ''::text THEN upper((od.nombre_representante::text || ' '::text) || od.apellido_representante::text)::character varying::text
												ELSE upper(od.razon_social::text)
												END AS nombre_distribuidor,
												upper((ov.nombre_representante::text || ' '::text) || ov.apellido_representante::text) AS nombre_vacunador,
												upper((rs.nombre_representante::text || ' '::text) || rs.apellido_representante::text) AS nombre_responsable,
												upper((pp.nombre_representante::text || ' '::text) || pp.apellido_representante::text) AS nombre_propietario,
												tv.nombre_vacuna,
												t.nombre_laboratorio,
												l.numero_lote,
												v.num_certificado,
												v.control_areteo,
												v.total_existente,
												v.total_vacunado,
												v.costo_vacuna,
												v.total_vacuna,
												v.fecha_registro,
												v.fecha_vacunacion,
												v.fecha_vencimiento,
												v.estado_vacunacion,
												v.observacion
											FROM 
												g_vacunacion_animal.vacuna_animales v,
												g_usuario.empresas em,
												g_operadores.operadores oa,
												g_operadores.operadores od,
												g_operadores.operadores ov,
												g_operadores.operadores rs,
												g_operadores.sitios s,
												g_operadores.operadores pp,
												g_operadores.areas a,
												g_catalogos.lotes l,
												g_catalogos.laboratorios t,
												g_catalogos.tipo_vacunas tv,
												g_usuario.usuarios_perfiles up,
												g_usuario.perfiles p
											WHERE 
												v.identificador_administrador=em.identificador and  v.identificador_administrador::text = oa.identificador::text AND v.identificador_distribuidor::text = od.identificador::text
												AND v.identificador_vacunador::text = ov.identificador::text AND v.usuario_responsable::text = rs.identificador::text
												AND s.id_sitio = v.id_sitio AND a.id_area = v.id_area AND s.identificador_operador::text = pp.identificador::text
												AND v.id_lote = l.id_lote AND l.id_laboratorio = t.id_laboratorio AND v.id_tipo_vacuna = tv.id_tipo_vacuna
												AND v.usuario_responsable::text = up.identificador::text AND up.id_perfil = p.id_perfil AND p.id_perfil = 6
												".$busqueda0."
												".$busqueda1."
												".$busqueda2."
												".$busqueda3."
												ORDER BY s.provincia,v.id_vacuna_animal,nombre_sitio asc;");
		return $res;
	}


	public function listaReporteCatastros($conexion,  $idSitio, $fechaInicio, $fechaFin){
		$busqueda2 = '';
		if(($fechaInicio!="0") && ($fechaFin!="0")){
			$fechaInicio1 = str_replace("/","-",$fechaInicio);
			$fechaInicio2 = strtotime ($fechaInicio1);//la fecha de vencimiento 1 day
			$fechaInicio3 = date('d/m/Y',$fechaInicio2);
				
			$fechaFin1 = str_replace("/","-",$fechaFin);
			$fechaFin2 =  strtotime ('+1 day',strtotime($fechaFin1));//la fecha de vencimiento 1 day
			$fechaFin3 = date('d/m/Y',$fechaFin2);

			$busqueda2 = " and fecha_catastro >= '".$fechaInicio3."' and fecha_catastro <= '".$fechaFin3."' ";
		}

		$res = $conexion->ejecutarConsulta("SELECT *
				FROM g_vacunacion_animal.vista_reporte_catastros
				WHERE id_sitio='$idSitio'
				".$busqueda2."
				");
		return $res;
	}



	public function _listaReporteVacunacionAnimal($conexion, $empresa, $autoservicio, $fechaInicio, $fechaFin, $estado){
		$busqueda0 = '';
		$busqueda1 = '';
		$busqueda2 = '';

		if($empresa=="1")
			$busqueda0 = " WHERE identificador_administrador not in (SELECT DISTINCT identificador_empresa FROM g_usuario.usuario_administrador_empresas)";
		else{
			$busqueda0 = " WHERE identificador_administrador = '".$empresa."' ";
		}
			
		if ($estado!="0")
			$busqueda1 = " and estado = '".$estado."' ";
			
		if(($fechaInicio!="0") && ($fechaFin!="0")){
			$fechaInicio1 = str_replace("/","-",$fechaInicio);
			$fechaInicio2 = strtotime ('-1 day',strtotime($fechaInicio1));//la fecha de vencimiento 1 day
			$fechaInicio3 = date('d/m/Y',$fechaInicio2);

			$fechaFin1 = str_replace("/","-",$fechaFin);
			$fechaFin2 = strtotime ('+1 day',strtotime($fechaFin1));//la fecha de vencimiento 1 day
			$fechaFin3 = date('d/m/Y',$fechaFin2);

			$busqueda2 = " and fecha_vacunacion >= '".$fechaInicio3."' and fecha_vacunacion <= '".$fechaFin3."' ";
		}
			
		$res = $conexion->ejecutarConsulta("SELECT *
				FROM g_vacunacion_animal.vista_reporte_vacunacion
				".$busqueda0."
				".$busqueda1."
				".$busqueda2."
				");
		return $res;
	}

	public function listaVacunacionAnimal($conexion, $usuario_responsable, $numeroCertificado, $fechaInicio, $fechaFin){
		$busqueda0 = '';
		$busqueda1 = '';
		$busqueda2 = '';
			
		if(($numeroCertificado=="0") && ($fechaInicio=="0") && ($fechaFin=="0"))
			$busqueda0 = " and v.fecha_vacunacion >= current_date and v.fecha_vacunacion < current_date+1";

		if($numeroCertificado!="0")
			$busqueda1 = " and UPPER(v.num_certificado) like '%".strtoupper($numeroCertificado)."' ";

		if(($fechaInicio!="0") && ($fechaFin!="0")){
			$fechaInicio1= str_replace("/","-",$fechaInicio);
			$fechaInicio2 = strtotime ('-1 day',strtotime($fechaInicio1));//la fecha de vencimiento 1 day
			$fechaInicio3  = date('d/m/Y',$fechaInicio2);
				
			$fechaFin1= str_replace("/","-",$fechaFin);
			$fechaFin2 = strtotime ('+1 day',strtotime($fechaFin1));//la fecha de vencimiento 1 day
			$fechaFin3  = date('d/m/Y',$fechaFin2);
				
			$busqueda2 = " and v.fecha_vacunacion >= '".$fechaInicio3."' and v.fecha_vacunacion <= '".$fechaFin3."' ";
		}
		$res = $conexion->ejecutarConsulta("SELECT v.id_vacuna_animal
				,v.id_sitio
				,v.id_area
				,v.id_especie
				,s.nombre_lugar nombre_sitio
				,a.nombre_area
				,v.nombre_especie
				,v.identificador_administrador
				,v.identificador_distribuidor
				,v.identificador_vacunador
				,case when oa.razon_social = '' then oa.nombre_representante ||' '|| oa.apellido_representante else oa.razon_social end nombre_administrador
				,case when od.razon_social = '' then od.nombre_representante ||' '|| od.apellido_representante else od.razon_social end nombre_distribuidor
				,(ov.nombre_representante ||' '|| ov.apellido_representante) nombre_vacunador
				,v.id_lote
				,v.id_tipo_vacuna
				,v.num_certificado
				,v.control_areteo
				,v.usuario_responsable
				,v.total_existente
				,v.total_vacunado
				,v.costo_vacuna
				,v.total_vacuna
				,to_char(v.fecha_registro,'DD/MM/YYYY') fecha_registro
				,to_char(v.fecha_vacunacion,'DD/MM/YYYY') fecha_vacunacion
				,to_char(v.fecha_vencimiento,'DD/MM/YYYY') fecha_vencimiento
				FROM g_vacunacion_animal.vacuna_animales v
				,g_operadores.operadores oa
				,g_operadores.operadores od
				,g_operadores.operadores ov
				,g_operadores.sitios s
				,g_operadores.areas a
				WHERE v.identificador_administrador = oa.identificador
				and v.identificador_distribuidor = od.identificador
				and v.identificador_vacunador = ov.identificador
				and s.id_sitio = v.id_sitio
				and a.id_area = v.id_area
				and v.usuario_responsable = '".$usuario_responsable."'
				".$busqueda0."
				".$busqueda1."
				".$busqueda2."
				");
		return $res;
	}
	public function listaVacunacionFiscalizacion($conexion, $usuario_responsable, $numeroCertificado, $fechaInicio, $fechaFin, $estado){
		$busqueda0 = '';
		$busqueda1 = '';
		$busqueda2 = '';
		$busqueda3 = '';

		if(($numeroCertificado=="0") && ($fechaInicio=="0") && ($fechaFin=="0"))
			$busqueda0 = " and v.fecha_registro >= current_date and v.fecha_registro < current_date+1";
		if($numeroCertificado!="0")
			$busqueda1 = " and UPPER(v.num_certificado) like '%".strtoupper($numeroCertificado)."' ";
		if(($fechaInicio!="0") && ($fechaFin!="0")){
			$fechaInicio1= str_replace("/","-",$fechaInicio);
			$fechaInicio2 = strtotime ('-1 day',strtotime($fechaInicio1));//la fecha de vencimiento 1 day
			$fechaInicio3  = date('d/m/Y',$fechaInicio2);

			$fechaFin1= str_replace("/","-",$fechaFin);
			$fechaFin2 = strtotime ('+1 day',strtotime($fechaFin1));//la fecha de vencimiento 1 day
			$fechaFin3  = date('d/m/Y',$fechaFin2);

			$busqueda2 = " and v.fecha_registro >= '".$fechaInicio3."' and v.fecha_registro <= '".$fechaFin3."' ";
		}
		if ($estado==2){
			if($busqueda1=='')
				$busqueda3 = " and v.estado_fiscalizacion = 'fiscalizado' ";
		}
		if ($estado==1 || $estado==0){
			if($busqueda1=='')
				$busqueda3 = " and v.estado_fiscalizacion is null ";
		}
		$res = $conexion->ejecutarConsulta("SELECT
				v.id_vacuna_animal
				,v.num_certificado
				,s.nombre_lugar nombre_sitio
				,a.nombre_area
				, (SELECT
				case when p.codificacion_perfil='PFL_USUAR_INT' then (SELECT upper((rsv.nombre::text || ' '::text) || rsv.apellido::text)  nombre_digitador FROM g_uath.ficha_empleado rsv WHERE v.usuario_responsable = rsv.identificador  )
				else (SELECT case when ore.razon_social = '' then ore.nombre_representante ||' '|| ore.apellido_representante else ore.razon_social end nombre_digitador FROM g_operadores.operadores ore WHERE v.usuario_responsable = ore.identificador   ) end nombre_digitador
				FROM g_usuario.perfiles p, g_usuario.usuarios_perfiles up
				WHERE p.id_perfil=up.id_perfil and p.codificacion_perfil in ('PFL_USUAR_INT','PFL_USUAR_EXT') and up.identificador=v.usuario_responsable)
				,to_char(v.fecha_vacunacion,'DD/MM/YYYY') fecha_vacunacion
				FROM g_vacunacion_animal.vacuna_animales v
				,g_operadores.sitios s
				,g_operadores.areas a
				WHERE
				s.id_sitio = v.id_sitio
				and a.id_area = v.id_area
				".$busqueda0."
				".$busqueda1."
				".$busqueda2."
				".$busqueda3."
				");
		return $res;
	}
	public function listaVacunacionAnulacionEmpresa($conexion, $id_usuario_responsable){
		$res = $conexion->ejecutarConsulta("SELECT ae.identificador_empresa FROM g_usuario.usuario_administrador_empresas ae  WHERE ae.identificador_empresa='".$id_usuario_responsable."' or ae.identificador='".$id_usuario_responsable."' ");
		return $res;
	}

	public function listaVacunacionAnulacion($conexion, $idDigitador, $numeroCertificado, $fechaInicio, $fechaFin){
		$busqueda0 = '';
		$busqueda1 = '';
		$busqueda2 = '';

		if(($numeroCertificado=="0") && ($fechaInicio=="0") && ($fechaFin=="0"))
			$busqueda0 = " and v.fecha_registro >= current_date and v.fecha_registro < current_date+1";
		if($numeroCertificado!="0")
			$busqueda1 = " and UPPER(v.num_certificado) like '%".strtoupper($numeroCertificado)."' ";
		if(($fechaInicio!="0") && ($fechaFin!="0")){
			$fechaInicio1= str_replace("/","-",$fechaInicio);
			$fechaInicio2 = strtotime ('-1 day',strtotime($fechaInicio1));//la fecha de vencimiento 1 day
			$fechaInicio3  = date('d/m/Y',$fechaInicio2);

			$fechaFin1= str_replace("/","-",$fechaFin);
			$fechaFin2 = strtotime ('+1 day',strtotime($fechaFin1));//la fecha de vencimiento 1 day
			$fechaFin3  = date('d/m/Y',$fechaFin2);

			$busqueda2 = " and v.fecha_registro >= '".$fechaInicio3."' and v.fecha_registro <= '".$fechaFin3."' ";
		}

		$res = $conexion->ejecutarConsulta("
				SELECT
				v.id_vacuna_animal
				,v.num_certificado
				,s.nombre_lugar nombre_sitio
				,a.nombre_area
				,(SELECT
				case when p.codificacion_perfil='PFL_USUAR_INT' then (SELECT upper((rsv.nombre::text || ' '::text) || rsv.apellido::text)  nombre_digitador FROM g_uath.ficha_empleado rsv WHERE v.usuario_responsable = rsv.identificador  )
				else (SELECT case when ore.razon_social = '' then ore.nombre_representante ||' '|| ore.apellido_representante else ore.razon_social end nombre_digitador FROM g_operadores.operadores ore WHERE v.usuario_responsable = ore.identificador   ) end nombre_digitador
				FROM g_usuario.perfiles p, g_usuario.usuarios_perfiles up
				WHERE p.id_perfil=up.id_perfil and p.codificacion_perfil in ('PFL_USUAR_INT','PFL_USUAR_EXT') and up.identificador=v.usuario_responsable)
				,v.estado_vacunacion
				FROM g_vacunacion_animal.vacuna_animales v
				,g_operadores.sitios s
				,g_operadores.areas a
				WHERE
				s.id_sitio = v.id_sitio
				and a.id_area = v.id_area
				and v.usuario_responsable ='".$idDigitador."'
				".$busqueda0."
				".$busqueda1."
				".$busqueda2."
				");
		return $res;
	}

	public function listaDetalleArete($conexion, $id_vacuna_animal){

		$res = $conexion->ejecutarConsulta("SELECT
				*
				FROM
				g_vacunacion_animal.vacuna_animal_aretes
				WHERE
				id_vacuna_animal = $id_vacuna_animal");
		return $res;
	}


	public function listaFiltroDetalleVacunacion($conexion, $id_vacuna_animal){
		$detalleVacuna = $conexion->ejecutarConsulta("SELECT
				d.id_vacuna_animal_detalle,
				d.id_vacuna_animal,
				d.id_producto,
				(SELECT nombre_comun FROM g_catalogos.productos WHERE id_producto = d.id_producto) nombre_producto,
				d.existente,
				d.vacunado,
				d.observacion
				FROM
				g_vacunacion_animal.vacuna_animal_detalles d
				WHERE
				d.id_vacuna_animal = $id_vacuna_animal");

		while ($fila = pg_fetch_assoc($detalleVacuna)){
			$res[] = array(
					id_vacuna_animal_detalle=>$fila['id_vacuna_animal_detalle'],
					id_vacuna_animal=>$fila['id_vacuna_animal'],
					id_producto=>$fila['id_producto'],
					nombre_producto=>$fila['nombre_producto'],
					existente=>$fila['existente'],
					vacunado=>$fila['vacunado'],
					observacion=>$fila['observacion']
			);
		}
		return $res;
	}


	public function buscarFiscalizacion($conexion, $id_vacuna_animal){
		$res = $conexion->ejecutarConsulta("SELECT
				id_vacuna_fiscalizacion
				, num_fiscalizacion
				, usuario_responsable
				, observacion
				, estado
				, to_char(fecha_fiscalizacion,'DD/MM/YYYY') fecha_fiscalizacion
				FROM
				g_vacunacion_animal.vacuna_fiscalizaciones
				WHERE
				id_vacuna_animal = $id_vacuna_animal");
		return $res;
	}

	public function listaVacunacionAnimalFiltro($conexion, $idVacunaAnimal){

		$res = $conexion->ejecutarConsulta("SELECT v.id_vacuna_animal
				,s.nombre_lugar nombre_sitio
				,a.nombre_area
				,v.nombre_especie
				,v.identificador_vacunador
				,case when oa.razon_social = '' then oa.nombre_representante ||' '|| oa.apellido_representante else oa.razon_social end nombre_administrador
				,case when od.razon_social = '' then od.nombre_representante ||' '|| od.apellido_representante else od.razon_social end nombre_distribuidor
				,case when ov.razon_social = '' then ov.nombre_representante ||' '|| ov.apellido_representante else ov.razon_social end nombre_vacunador
				,od.provincia provincia_distribuidor
				,l.numero_lote
				,t.nombre_laboratorio
				,tv.nombre_vacuna
				,v.num_certificado numero_certificado
				,v.usuario_responsable
				,v.total_existente
				,v.total_vacunado
				,v.costo_vacuna
				,v.total_vacuna
				,to_char(v.fecha_registro,'DD/MM/YYYY') fecha_registro
				,to_char(v.fecha_vacunacion,'DD/MM/YYYY') fecha_vacunacion
				,to_char(v.fecha_vencimiento,'DD/MM/YYYY') fecha_vencimiento
				,v.estado_vacunacion
				FROM g_vacunacion_animal.vacuna_animales v
				,g_operadores.operadores oa
				,g_operadores.operadores od
				,g_operadores.operadores ov
				,g_operadores.sitios s
				,g_operadores.areas a
				,g_catalogos.lotes l
				,g_catalogos.laboratorios t
				,g_catalogos.tipo_vacunas tv
				WHERE v.identificador_administrador = oa.identificador
				and v.identificador_distribuidor = od.identificador
				and v.identificador_vacunador = ov.identificador
				and s.id_sitio = v.id_sitio
				and a.id_area = v.id_area
				and v.id_lote = l.id_lote
				and l.id_laboratorio = t.id_laboratorio
				and v.id_tipo_vacuna = tv.id_tipo_vacuna
				and v.id_vacuna_animal = $idVacunaAnimal
				UNION
				SELECT DISTINCT v.id_vacuna_animal
				,s.nombre_lugar nombre_sitio
				,a.nombre_area
				,v.nombre_especie
				,rsv.identificador identificador_vacunador
				,'' nombre_administrador
				,upper((rsd.nombre::text || ' '::text) || rsd.apellido::text) AS nombre_distribuidor
				,upper((rsv.nombre::text || ' '::text) || rsv.apellido::text) AS nombre_vacunador
				,(SELECT lo.nombre FROM g_catalogos.localizacion lo WHERE lo.id_localizacion=rsd.id_localizacion_provincia)provincia_distribuidor
				,l.numero_lote
				,t.nombre_laboratorio
				,tv.nombre_vacuna
				,v.num_certificado numero_certificado
				,v.usuario_responsable
				,v.total_existente
				,v.total_vacunado
				,v.costo_vacuna
				,v.total_vacuna
				,to_char(v.fecha_registro,'DD/MM/YYYY') fecha_registro
				,to_char(v.fecha_vacunacion,'DD/MM/YYYY') fecha_vacunacion
				,to_char(v.fecha_vencimiento,'DD/MM/YYYY') fecha_vencimiento
				,v.estado_vacunacion
				FROM g_vacunacion_animal.vacuna_animales v,
				g_uath.ficha_empleado rsd,
				g_uath.ficha_empleado rsr,
				g_uath.ficha_empleado rsv,
				g_operadores.sitios s,
				g_operadores.operadores pp,
				g_operadores.areas a,
				g_catalogos.lotes l,
				g_catalogos.laboratorios t,
				g_catalogos.tipo_vacunas tv
				WHERE
				v.identificador_distribuidor::text = rsd.identificador::text
				AND v.identificador_vacunador::text = rsv.identificador::text
				AND v.usuario_responsable::text = rsr.identificador::text
				AND s.id_sitio = v.id_sitio
				AND s.identificador_operador::text = pp.identificador::text AND a.id_area = v.id_area AND v.id_lote = l.id_lote AND l.id_laboratorio = t.id_laboratorio
				AND v.id_tipo_vacuna = tv.id_tipo_vacuna
				and v.id_vacuna_animal =$idVacunaAnimal");
		return $res;
	}

	public function listaBusquedaAlmacen ($conexion, $id_almacen){
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM 
												g_catalogos.almacenes
											WHERE 
												id_almacen = $id_almacen;");
		return $res;
	}

	public function actualizarDatosOperador($conexion, $identificador,$razon,$nombreLegal,$apellidoLegal,$nombreTecnico,$apellidoTecnico,$provincia,$canton,
			$parroquia,$direccion,$telefono1,$telefono2,$celular1,$celular2,$fax,$correo,$registroOrquideas,$registroMadera){
		$res = $conexion->ejecutarConsulta("UPDATE
				g_operadores.operadores
				set
				razon_social='$razon',
				nombre_representante='$nombreLegal',
				apellido_representante='$apellidoLegal',
				nombre_tecnico='$nombreTecnico',
				apellido_tecnico='$apellidoTecnico',
				direccion='$direccion',
				provincia='$provincia',
				canton='$canton',
				parroquia='$parroquia',
				telefono_uno='$telefono1',
				telefono_dos='$telefono2',
				celular_uno='$celular1',
				celular_dos='$celular2',
				fax='$fax',
				correo='$correo',
				registro_orquideas='$registroOrquideas',
				registro_madera='$registroMadera'
				WHERE
				identificador='$identificador';");
		return $res;
	}

	public function numeroSerievalorada($conexion, $tipo_documento, $serie){
		$res = $conexion->ejecutarConsulta("SELECT id_serie_documento
				, id_especie
				, nombre_especie
				, tipo_documento
				, numeracion_documento
				, serie
				, numero_documento
				, estado
				FROM
				g_vacunacion_animal.serie_documentos
				WHERE
				estado in ('ingresado')
				and tipo_documento='".$tipo_documento."'
				and serie='".$serie."' ");
		return $res;
	}
	public function numeroSerievalorada1($conexion, $tipo_documento, $serie){
		$res = $conexion->ejecutarConsulta("SELECT 
												id_serie_documento
												, id_especie
												, nombre_especie
												, tipo_documento
												, numeracion_documento
												, serie
												, numero_documento
												, estado
											FROM
												g_vacunacion_animal.serie_documentos
											WHERE
												tipo_documento='".$tipo_documento."'
												and serie='".$serie."' ");
		return $res;
	}
	//Controlar la serie de la especies valoradas anular
	public function numeroSerievaloradaAnular($conexion, $tipo_documento, $serie){
		$res = $conexion->ejecutarConsulta("SELECT 
												id_serie_documento
												, id_especie
												, nombre_especie
												, tipo_documento
												, numeracion_documento
												, serie
												, numero_documento
												, estado
											FROM
												g_vacunacion_animal.serie_documentos
											WHERE
												tipo_documento='".$tipo_documento."'
												and serie='".$serie."' ");
		return $res;
	}

	//Controlar la serie de la especies valoradas
	public function numeroSerievalorada2($conexion, $tipo_documento, $serie){
		$res = $conexion->ejecutarConsulta("SELECT DISTINCT 
												id_especie
												, nombre_especie
												, tipo_documento
												, numeracion_documento
												, serie
												, numero_documento
												, estado
											FROM
												g_vacunacion_animal.serie_documentos
											WHERE
												estado in ('ingresado')
												and nombre_especie = 'Porcinos'
												and tipo_documento='".$tipo_documento."'
												and serie='".$serie."'");
		return $res;
	}

	public function listaProvinciaDistribuidor($conexion){
		$ProvinciaDistribuidor = $conexion->ejecutarConsulta("SELECT DISTINCT
																	o.provincia
																	, a.identificador_administrador
																FROM g_vacunacion_animal.administrador_distribuidor d
																	, g_operadores.operadores o
																	, g_vacunacion_animal.administrador_vacunacion a
																WHERE o.identificador = d.identificador_distribuidor
																	and a.id_administrador_vacunacion = d.id_administrador_vacunacion
																	ORDER BY o.provincia asc;");
				
		while ($fila = pg_fetch_assoc($ProvinciaDistribuidor)){
			$res[] = array(provincia=>$fila['provincia']
					, identificador_administrador=>$fila['identificador_administrador']
			);
		}

		return $res;
	}

	public function listaCantonDistribuidor($conexion){
		$ProvinciaDistribuidor = $conexion->ejecutarConsulta("SELECT DISTINCT
																	o.provincia
																	, o.canton
																	, a.identificador_administrador
																FROM 
																	g_vacunacion_animal.administrador_distribuidor d
																	, g_operadores.operadores o
																	, g_vacunacion_animal.administrador_vacunacion a
																WHERE
																	o.identificador = d.identificador_distribuidor
																	and a.id_administrador_vacunacion = d.id_administrador_vacunacion
																	ORDER BY o.provincia asc
																	, o.canton asc;");
			
		while ($fila = pg_fetch_assoc($ProvinciaDistribuidor)){
			$res[] = array(provincia => $fila['provincia']
					, canton => $fila['canton']
					, identificador_administrador => $fila['identificador_administrador']
			);
		}

		return $res;
	}

	public function listaPuntoDistribuidor($conexion){
		$PuntoDistribuidor = $conexion->ejecutarConsulta("SELECT DISTINCT 
																o.identificador identificador_distribuidor
																, case when o.razon_social = '' then o.identificador ||' - '|| o.nombre_representante ||' '|| o.apellido_representante else o.identificador ||' - '|| o.razon_social end nombre_distribuidor
																, a.nombre_especie
																, o.provincia
																, a.identificador_administrador
															FROM 
																g_vacunacion_animal.administrador_distribuidor d
																, g_operadores.operadores o
																, g_vacunacion_animal.administrador_vacunacion a
															WHERE 
																o.identificador = d.identificador_distribuidor
																and a.id_administrador_vacunacion = d.id_administrador_vacunacion
																and d.estado = 'activo';");
			
		while ($fila = pg_fetch_assoc($PuntoDistribuidor)){
			$res[] = array(identificador_distribuidor => $fila['identificador_distribuidor']
					, nombre_distribuidor => $fila['nombre_distribuidor']
					, nombre_especie => $fila['nombre_especie']
					, provincia => $fila['provincia']
					, identificador_administrador => $fila['identificador_administrador']
			);
		}

		return $res;
	}

	public function listaLugarControlAreteo($conexion)
	{
		$res = $conexion->ejecutarConsulta("SELECT
												l.id_localizacion,
												l.nombre
											FROM
												g_catalogos.localizacion l,
												g_vacunacion_animal.control_areteo_animales a
											WHERE
												l.id_localizacion = a.id_provincia
											GROUP BY l.id_localizacion, l.nombre
											ORDER BY l.nombre asc;");
		return $res;
	}

	public function listaVacunadores ($conexion){
		$res = $conexion->ejecutarConsulta("SELECT
												id_vacunador
												, tipo_identificacion
												, identificador
												, nombre
												, apellido
												, telefono
												, celular
												, correo
												, to_char(fecha_registro,'DD/MM/YYYY') fecha_registro
												, to_char(fecha_modificacion,'DD/MM/YYYY') fecha_modificacion
												, estado
											FROM
												g_catalogos.vacunadores");
		return $res;
	}

	public function listaOperadorVacunador($conexion, $especie){
		$res = $conexion->ejecutarConsulta("SELECT DISTINCT 
												o.identificador identificador_administrador
												, o.razon_social nombre_administrador
											FROM 
												g_vacunacion_animal.administrador_vacunacion a,
												g_operadores.operadores o
											WHERE 
												o.identificador = a.identificador_administrador
												and a.nombre_especie = 'Porcinos';");
		return $res;
	}

	public function listaCatastro ($conexion, $numeroIdentificacion, $nombreGranja, $provincia,  $identificacionUsuario){
		$busqueda0 = '';
		$busqueda1 = '';
		$busqueda2 = '';
		$busqueda3 = '';

		if(($numeroIdentificacion=="0") && ($nombreGranja=="0") && ($provincia=="0"))
			$busqueda0 = " and id_catastro = 0";
		if ($numeroIdentificacion!="0")
			$busqueda1 = " and s.identificador_operador = '".$numeroIdentificacion."'";
		if ($nombreGranja!="0")
			$busqueda2 = " and UPPER(s.nombre_lugar) like '%".strtoupper($nombreGranja)."%'";
		if ($provincia!="0")
			$busqueda3 = " and s.provincia = '".$provincia."'";

		$res = $conexion->ejecutarConsulta("SELECT DISTINCT c.id_sitio
												, c.id_area
												, c.id_especie
												, s.identificador_operador identificador
												, c.nombre_especie
												, s.nombre_lugar nombre_sitio
												, a.nombre_area
												, s.provincia
											FROM 
												g_vacunacion_animal.catastros c
												, g_operadores.sitios s
												, g_operadores.areas a
											WHERE
												s.id_sitio = c.id_sitio
												and a.id_area = c.id_area
												".$busqueda0."
												".$busqueda1."
												".$busqueda2."
												".$busqueda3."
												ORDER BY nombre_lugar asc;");
		return $res;
	}

	public function listaCatastroAnimal($conexion){
		$res = $conexion->ejecutarConsulta("SELECT c.id_catastro
				, c.id_sitio
				, c.id_area
				, c.id_especie
				, c.nombre_especie
				, s.nombre_lugar nombre_sitio
				, a.nombre_area
				, c.id_concepto_catastro
				, cc.nombre_concepto nombre_concepto_catastro
				, c.edad_producto
				, c.id_producto
				, p.nombre_comun nombre_producto
				, c.coeficiente
				, c.cantidad
				, c.total
				, c.estado
				, c.observacion
				, c.fecha_nacimiento
				, c.fecha_catastro
				FROM g_vacunacion_animal.catastros c
				, g_catalogos.productos p
				, g_operadores.sitios s
				, g_operadores.areas a
				, g_vacunacion_animal.concepto_catastros cc
				WHERE c.id_producto = p.id_producto
				and s.id_sitio = c.id_sitio
				and a.id_area = c.id_area
				and c.id_concepto_catastro = cc.id_concepto_catastro");
		return $res;
	}

	public function catastroAnimal($conexion, $id_sitio, $id_area, $id_especie){
		$Catastro = $conexion->ejecutarConsulta("SELECT DISTINCT 
														c.id_sitio
														, c.id_area
														, c.id_especie
														, c.nombre_especie
														, s.nombre_lugar nombre_sitio
														, a.nombre_area
														, s.identificador_operador ||'.'|| s.codigo_provincia ||''|| s.codigo ||''|| a.codigo ||''|| a.secuencial codigo_catastral
													FROM 
														g_vacunacion_animal.catastros c
														, g_operadores.sitios s
														, g_operadores.areas a
														, g_vacunacion_animal.concepto_catastros cc
													WHERE
														s.id_sitio = c.id_sitio
														and a.id_area = c.id_area
														and c.total > 0
														and c.id_concepto_catastro = cc.id_concepto_catastro
														and c.id_sitio = $id_sitio
														and c.id_area = $id_area
														and c.id_especie = $id_especie;");

		while ($fila = pg_fetch_assoc($Catastro)){
			$res[] = array(id_sitio=>$fila['id_sitio'],
					id_area=>$fila['id_area'],
					id_especie=>$fila['id_especie'],
					nombre_especie=>$fila['nombre_especie'],
					nombre_sitio=>$fila['nombre_sitio'],
					nombre_area=>$fila['nombre_area'],
					id_producto=>$fila['id_producto'],
					nombre_producto=>$fila['nombre_producto'],
					codigo_catastral=>$fila['codigo_catastral']
			);
		}
		return $res;
	}

	// catastro animal existente
	public function catastroAnimalVacunado($conexion, $id_sitio, $id_area, $id_especie){
		$Catastro = $conexion->ejecutarConsulta("SELECT c.numero_documento
													, d.orden
													, p.id_producto
													, p.nombre_comun producto
													, c.id_especie
													, c.nombre_especie
													, c.total_vacunado total_vacunado
												FROM g_vacunacion_animal.catastros c
													, g_catalogos.productos p
													, g_catalogos.productos_animales d
												WHERE c.id_producto = p.id_producto
													and c.id_producto = d.id_producto
													and p.id_producto = d.id_producto
													and c.id_concepto_catastro not in (5, 6, 8)
													and c.numero_documento not in ('Ninguno')
													and c.total_vacunado > 0
													and c.id_catastro in (SELECT max(id_catastro) id
													FROM g_vacunacion_animal.catastros c
													WHERE id_sitio = $id_sitio
													and id_area = $id_area
													and id_especie = $id_especie
													and cantidad_vacunado is not null GROUP BY c.id_producto, c.numero_documento)
													ORDER BY numero_documento asc;");

		while ($fila = pg_fetch_assoc($Catastro)){
			$res[] = array(numero_documento=>$fila['numero_documento']
					, orden=>$fila['orden']
					, id_producto=>$fila['id_producto']
					, producto=>$fila['producto']
					, id_especie=>$fila['id_especie']
					, nombre_especie=>$fila['nombre_especie']
					, total_vacunado=>$fila['total_vacunado']
			);
		}
		return $res;

	}

	public function catastroAnimalEspecifico($conexion, $id_sitio, $id_area, $id_especie){
		$CatastroEspecifico = $conexion->ejecutarConsulta("SELECT d.orden, c.id_especie, c.nombre_especie, c.id_producto, p.nombre_comun producto, c.total, cv.total_vacunado
				FROM g_vacunacion_animal.catastros c,
				g_vacunacion_animal.mostrar_catastro_vacunados ($id_sitio,$id_area, $id_especie) cv
				, g_catalogos.productos p
				, g_catalogos.productos_animales d
				, g_operadores.sitios s
				, g_operadores.areas a
				, g_vacunacion_animal.concepto_catastros cc
				WHERE c.id_producto = p.id_producto
				and cv.id_producto=p.id_producto
				and c.id_producto = d.id_producto
				and p.id_producto = d.id_producto
				and s.id_sitio = c.id_sitio
				and a.id_area = c.id_area
				--and c.total > 0
				and c.id_concepto_catastro = cc.id_concepto_catastro
				and id_catastro in (SELECT max(id_catastro)
				FROM g_vacunacion_animal.catastros c, g_catalogos.productos_animales a
				WHERE c.id_producto = a.id_producto
				and c.id_especie = a.id_especie
				and total is not null
				and c.id_sitio = $id_sitio
				and c.id_area = $id_area
				and c.id_especie = $id_especie
				GROUP BY c.id_producto)
				and d.estado='activo'
				union
				SELECT d.orden, d.id_especie, d.nombre_especie, p.id_producto, p.nombre_comun producto, 0 total, 0 total_vacunado
				FROM g_catalogos.productos p,
				g_vacunacion_animal.mostrar_catastro_vacunados ($id_sitio,$id_area, $id_especie) cv,
				g_catalogos.productos_animales d
				WHERE p.id_producto = d.id_producto
				and cv.id_producto=p.id_producto
				and p.id_producto not in (SELECT c.id_producto FROM g_vacunacion_animal.catastros c
				, g_catalogos.productos p
				, g_catalogos.productos_animales d
				, g_operadores.sitios s
				, g_operadores.areas a
				, g_vacunacion_animal.concepto_catastros cc
				WHERE c.id_producto = p.id_producto
				and c.id_producto = d.id_producto
				and p.id_producto = d.id_producto
				and s.id_sitio = c.id_sitio
				and a.id_area = c.id_area
				--and c.total > 0
				and c.id_concepto_catastro = cc.id_concepto_catastro
				and id_catastro in (SELECT max(id_catastro)
				FROM g_vacunacion_animal.catastros c, g_catalogos.productos_animales a
				WHERE c.id_producto = a.id_producto
				and c.id_especie = a.id_especie
				and total is not null
				and c.id_sitio = $id_sitio
				and c.id_area = $id_area
				and c.id_especie = $id_especie
				GROUP BY c.id_producto))
				and d.estado='activo'
				ORDER BY orden desc;");
			
		while ($fila = pg_fetch_assoc($CatastroEspecifico)){
			$res[] = array(orden=>$fila['orden']
					, id_producto=>$fila['id_producto']
					, producto=>$fila['producto']
					, id_especie=>$fila['id_especie']
					, nombre_especie=>$fila['nombre_especie']
					, total=>$fila['total']
					, total_vacunado=>$fila['total_vacunado']
			);
		}
		return $res;

	}

	public function catastroValorProductoVacunado($conexion, $id_sitio, $id_area, $id_especie){
		$res = $conexion->ejecutarConsulta("SELECT id_catastro
												, id_producto
												, producto
												, total_existencias
												, edad_producto
												, to_char(fecha_nacimiento,'DD/MM/YYYY') fecha_nacimiento
												, orden
												, codigo
											FROM 
												g_vacunacion_animal.mostrar_producto_catastro ($id_sitio, $id_area, $id_especie) WHERE codigo not in ('PORHON','PORLTO')
												ORDER BY orden desc;");
		return $res;
	}

	public function listaConceptoCatastro($conexion){
		$res = $conexion->ejecutarConsulta("SELECT 
												id_concepto_catastro
												, nombre_concepto nombre_concepto_catastro
												, coeficiente
												, estado
											FROM 
												g_vacunacion_animal.concepto_catastros
											WHERE 
												estado = 'activo'
												and id_concepto_catastro not in (3, 4, 8, 9, 10, 11, 12, 14)
				");
			
		return $res;
	}

	public function listaProductosPorEspecie($conexion){
		$ProductoEspecie = $conexion->ejecutarConsulta("SELECT 
															p.id_producto
															, p.nombre_comun nombre_producto
															, pa.id_especie
															, pa.nombre_especie
															, pa.rango_edad_desde
															, pa.rango_edad_hasta
															, pa.rango_edad_promedio
															,pa.codigo
														FROM g_catalogos.productos_animales pa
															, g_catalogos.productos p
														WHERE pa.id_producto = p.id_producto
															AND pa.estado='activo'
															ORDER BY pa.orden desc;");

		while ($fila = pg_fetch_assoc($ProductoEspecie)){
			$res[] = array(id_producto=>$fila['id_producto']
					, nombre_producto=>$fila['nombre_producto']
					, id_especie=>$fila['id_especie']
					, nombre_especie=>$fila['nombre_especie']
					, rango_edad_desde=>$fila['rango_edad_desde']
					, rango_edad_hasta=>$fila['rango_edad_hasta']
					, rango_edad_promedio=>$fila['rango_edad_promedio']
					,codigo=>$fila['codigo']
			);
		}
		return $res;
	}

	public function guardarDatosCatastro($conexion, $id_sitio, $id_area, $id_especie, $nombre_especie, $id_concepto_catastro, $numero_documento
			, $edad_producto, $id_producto, $coeficiente, $cantidad, $total, $estado, $fecha_nacimiento, $fecha_mortalidad, $usuario_reponsable, $numero_documento_referencia)
	{
		$fecha_catastro = date('d-m-Y H:i:s');
		if($fecha_mortalidad==''){
				$res = $conexion->ejecutarConsulta("INSERT INTO g_vacunacion_animal.catastros(
				id_sitio, id_area, id_especie, nombre_especie, id_concepto_catastro, numero_documento
				, edad_producto, id_producto, coeficiente, cantidad
				, total, estado, fecha_nacimiento, fecha_catastro, usuario_reponsable, numero_documento_referencia)
				values ('$id_sitio','$id_area','$id_especie','$nombre_especie','$id_concepto_catastro','$numero_documento'
				,'$edad_producto','$id_producto','$coeficiente','$cantidad'
				,'$total','$estado','$fecha_nacimiento','$fecha_catastro','$usuario_reponsable', '$numero_documento_referencia')");
		}else{
				$res = $conexion->ejecutarConsulta("INSERT INTO g_vacunacion_animal.catastros(
				id_sitio, id_area, id_especie, nombre_especie, id_concepto_catastro, numero_documento
				, edad_producto, id_producto, coeficiente, cantidad
				, total, estado, fecha_nacimiento, fecha_catastro, fecha_mortalidad, usuario_reponsable, numero_documento_referencia)
				values ('$id_sitio','$id_area','$id_especie','$nombre_especie','$id_concepto_catastro','$numero_documento'
				,'$edad_producto','$id_producto','$coeficiente','$cantidad'
				,'$total','$estado','$fecha_nacimiento','$fecha_catastro','$fecha_mortalidad','$usuario_reponsable','$numero_documento_referencia')");
		}
		return $res;
	}

	public function guardarDatosCatastroVacunacion($conexion, $id_sitio, $id_area, $id_especie, $nombre_especie, $id_concepto_catastro, $numero_documento
			, $edad_producto, $id_producto, $coeficiente, $cantidad_vacunado, $total_vacunado, $estado, $fecha_nacimiento, $fecha_vacunacion, $usuario_reponsable, $numero_documento_referencia)
	{
		$fecha_catastro = date('d-m-Y H:i:s');
			
		$res = $conexion->ejecutarConsulta("INSERT INTO g_vacunacion_animal.catastros(
				id_sitio, id_area, id_especie, nombre_especie, id_concepto_catastro, numero_documento
				, edad_producto, id_producto, coeficiente, cantidad_vacunado
				, total_vacunado, estado, fecha_nacimiento,
				fecha_catastro, fecha_vacunacion, usuario_reponsable, numero_documento_referencia)
				values ('$id_sitio','$id_area','$id_especie','$nombre_especie',
				'$id_concepto_catastro','$numero_documento','$edad_producto',
				'$id_producto','$coeficiente','$cantidad_vacunado'
				,'$total_vacunado','$estado','$fecha_nacimiento',
				'$fecha_catastro','$fecha_vacunacion',
				'$usuario_reponsable', '$numero_documento_referencia')");
		return $res;
	}

	public function guardarCatastroVacunacion($conexion, $id_sitio, $id_area, $id_especie, $nombre_especie, $id_concepto_catastro, $numero_documento
			, $edad_producto, $id_producto, $coeficiente, $cantidad_vacunado, $total_vacunado, $estado, $fecha_nacimiento, $fecha_vacunacion, $usuario_reponsable, $fecha_mortalidad)
	{
		$fecha_catastro = date('d-m-Y H:i:s');
		$res = $conexion->ejecutarConsulta("INSERT INTO g_vacunacion_animal.catastros(id_sitio, id_area, id_especie, nombre_especie, id_concepto_catastro, numero_documento
				, edad_producto, id_producto, coeficiente, cantidad_vacunado, total_vacunado, estado, fecha_nacimiento, fecha_catastro, fecha_vacunacion, usuario_reponsable, fecha_mortalidad)
				values ('$id_sitio','$id_area','$id_especie','$nombre_especie','$id_concepto_catastro','$numero_documento'
				,'$edad_producto','$id_producto','$coeficiente','$cantidad_vacunado','$total_vacunado','$estado'
				,'$fecha_nacimiento','$fecha_catastro','$fecha_vacunacion','$usuario_reponsable', '$fecha_mortalidad')");

		return $res;
	}

	public function validarCatastroAnimalVacunados($conexion, $id_sitio, $id_area, $id_especie, $id_producto, $numero_documento){
		$res = $conexion->ejecutarConsulta("SELECT 
												id_catastro
												, total_vacunado total
												, fecha_vacunacion
												, numero_documento
											FROM 
												g_vacunacion_animal.catastros
											WHERE 
												id_sitio = $id_sitio
												and id_area = $id_area
												and id_especie = $id_especie
												and id_producto = $id_producto
												and numero_documento = '$numero_documento'
												and total_vacunado > 0
												ORDER BY id_catastro desc limit 1;");
		return $res;
	}

	public function validarCatastroVacunados($conexion, $id_sitio, $id_area, $id_especie, $id_producto){
		$res = $conexion->ejecutarConsulta("SELECT 
												id_catastro
												, total_vacunado total
												, fecha_vacunacion
												, numero_documento
											FROM 
												g_vacunacion_animal.catastros c
											WHERE
												total_vacunado > 0
												and c.id_catastro in (SELECT max(id_catastro) id
												FROM g_vacunacion_animal.catastros c
												WHERE id_sitio = $id_sitio
												and id_area = $id_area
												and id_especie = $id_especie
												and id_producto = $id_producto
												and cantidad_vacunado is not null GROUP BY c.id_producto, c.numero_documento)");
		return $res;
	}

	public function validarProductoCondicion($conexion, $id_especie, $id_producto){
		$res = $conexion->ejecutarConsulta("SELECT 
												p.id_producto,
												p.nombre_comun animal
											FROM 
												g_catalogos.productos_animales a
												, g_catalogos.productos p
											WHERE 
												p.id_producto = a.id_producto
												and a.id_especie = $id_especie
												and p.id_producto = $id_producto
												and tipo_documento = 'ninguno'");
		return $res;
	}

	public function validarCatastroAnimal($conexion, $id_sitio, $id_area, $id_especie, $id_producto)
	{
		$res = $conexion->ejecutarConsulta("SELECT 
												id_catastro
												,total
											FROM 
												g_vacunacion_animal.catastros
											WHERE 
												id_sitio = $id_sitio
												and id_area = $id_area
												and id_especie = $id_especie
												and id_producto = $id_producto
												and total > 0
												ORDER BY id_catastro desc
												limit 1;");
		return $res;
	}

	public function validarProductoVacunacion($conexion, $id_especie, $id_producto){
		$res = $conexion->ejecutarConsulta("SELECT 
												id_producto FROM g_catalogos.productos_animales
											WHERE 
												id_especie = $id_especie
												and id_producto = $id_producto
												and tipo_documento not in ('ninguno');");
		return $res;
	}

	public function validarCatastroAnimalVacunado($conexion, $id_sitio, $id_area, $id_especie, $id_producto, $numero_documento){

		$res = $conexion->ejecutarConsulta("SELECT 
												id_catastro
												, total_vacunado
											FROM 
												g_vacunacion_animal.catastros
											WHERE 
												id_sitio = $id_sitio
												and id_area = $id_area
												and id_especie = $id_especie
												and id_producto = $id_producto
												and numero_documento = '".$numero_documento."'
												ORDER BY id_catastro desc
												limit 1;");
		return $res;
	}

	public function actualizarDatosAdministradorVacunacion($conexion, $id_administrador_vacunacion, $estado){
		$res = $conexion->ejecutarConsulta("UPDATE 
												g_vacunacion_animal.administrador_vacunacion
											SET
												estado = '$estado'
											WHERE
												id_administrador_vacunacion = $id_administrador_vacunacion");
		return $res;
	}

	public function actualizarDatosDistribuidorVacunacion($conexion, $id_administrador_distribuidor, $estado){
		$res = $conexion->ejecutarConsulta("UPDATE
												g_vacunacion_animal.administrador_distribuidor
											SET 
												estado = '$estado'
											WHERE 
												id_administrador_distribuidor = $id_administrador_distribuidor");
		return $res;
	}

	public function actualizarDatosVacunadorVacunacion($conexion, $id_administrador_vacunador, $estado){
		$res = $conexion->ejecutarConsulta("UPDATE
												g_vacunacion_animal.administrador_vacunador
											SET
												 estado = '$estado'
											WHERE 
												id_administrador_vacunador = $id_administrador_vacunador");
		return $res;
	}

	public function procesoCatastroAutomatico($conexion)
	{
		$res = $conexion->ejecutarConsulta("SELECT
												 to_date(to_char(c.fecha_nacimiento,'dd-mm-yyyy'),'dd-mm-yyyy') fecha
												, current_date fecha_actual
												, c.edad_producto
												, (SELECT (current_date - to_date(to_char(c.fecha_nacimiento,'dd-mm-yyyy'),'dd-mm-yyyy'))) diferencia_dia
												, c.id_producto
												, p.nombre_comun producto
												, c.id_catastro
											FROM 
												g_vacunacion_animal.catastros c
												,g_catalogos.productos p
											WHERE
												c.id_producto = p.id_producto
												ORDER BY c.id_catastro asc");
		return $res;
	}

	public function actualizarCatastroAutomatico($conexion){
		$res = $conexion->ejecutarConsulta("UPDATE 
												g_vacunacion_animal.catastros
												set edad_producto = (SELECT (current_date - to_date(to_char(fecha_nacimiento,'dd-mm-yyyy'),'dd-mm-yyyy')))
											WHERE 
												id_concepto_catastro in (SELECT id_concepto_catastro FROM g_vacunacion_animal.concepto_catastros WHERE nombre_concepto = 'Muerte del animal')");
		return $res;
	}


	public function actualizarCatastroAutomatico1($conexion){
		$res = $conexion->ejecutarConsulta("UPDATE
												g_vacunacion_animal.catastros
											SET 
												edad_producto = (SELECT (current_date - to_date(to_char(fecha_nacimiento,'dd-mm-yyyy'),'dd-mm-yyyy')))
											WHERE 
												id_concepto_catastro in (SELECT id_concepto_catastro FROM g_vacunacion_animal.concepto_catastros WHERE nombre_concepto = 'Muerte del animal')");
		return $res;
	}

	public function listaProductosAnimales($conexion){
		$res = $conexion->ejecutarConsulta("SELECT 
												p.id_producto
												, p.nombre_comun producto
												, pa.id_especie
												, pa.nombre_especie
												, pa.rango_edad_desde
												, pa.rango_edad_hasta
												, pa.rango_edad_promedio
											FROM 
												g_catalogos.productos_animales pa
												, g_catalogos.productos p
											WHERE pa.id_producto = p.id_producto
												and pa.estado = 'activo'");
		return $res;
	}

	public function actualizarEstadoSerieDocumentos($conexion, $numero_documento, $observacion, $estado){
		$fecha_registro = date('d-m-Y H:i:s');
		$res = $conexion->ejecutarConsulta("UPDATE 
												g_vacunacion_animal.serie_documentos
											SET 
												estado = '".$estado."'
												, observacion = '".$observacion."'
												, fecha_modificacion = '".$fecha_registro."'
											WHERE
												tipo_documento = 'vacunacion'
												and numero_documento = '".$numero_documento."'");
		return $res;
	}

	public function actualizarEstadoVacunacion($conexion, $numero_documento, $observacion, $estado, $usuario_anulacion){
		$fecha_registro = date('d-m-Y H:i:s');
		$res = $conexion->ejecutarConsulta("UPDATE g_vacunacion_animal.vacuna_animales
				set estado_vacunacion = '".$estado."'
				, observacion = '".$observacion."'
				, usuario_anulacion = '".$usuario_anulacion."'
				, fecha_anulacion = '".$fecha_registro."'
				WHERE num_certificado = '".$numero_documento."'");
		return $res;
	}

	public function catastroEstadoVacunacion($conexion, $numero_documento)
	{
		$res = $conexion->ejecutarConsulta("DELETE FROM 
												g_vacunacion_animal.catastros
											WHERE 
												numero_documento = '".$numero_documento."'");
		return $res;
	}

	public function buscarAnularVacunacion($conexion, $id_vacuna_animal){
		$res = $conexion->ejecutarConsulta("SELECT 
												id_vacuna_animal
												, num_certificado
												, usuario_anulacion
												, observacion
												, estado_vacunacion
												, to_char(fecha_anulacion,'DD/MM/YYYY') fecha_anulacion
											FROM
												g_vacunacion_animal.vacuna_animales
												WHERE estado_vacunacion = 'anulado'
												and id_vacuna_animal = $id_vacuna_animal");
		return $res;
	}

	public function buscarAnularMovilizados($conexion, $id_vacuna_animal){
		$res = $conexion->ejecutarConsulta("SELECT DISTINCT 
												md.numero_certificado
											FROM 
												g_vacunacion_animal.catastros c
												, g_vacunacion_animal.vacuna_animales v
												, g_movilizacion_animal.movilizacion_animal_detalles md
											WHERE 
												c.numero_documento = v.num_certificado
												and c.numero_documento = md.numero_certificado
												and v.id_vacuna_animal = $id_vacuna_animal");
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
		$res = $conexion->ejecutarConsulta("SELECT DISTINCT 
												s.id_sitio
												, s.identificador_operador
												, (o.nombre_representante || ' ' || o.apellido_representante) nombres
												, s.nombre_lugar granja
												, s.provincia
											FROM 
												g_operadores.operadores o
												, g_operadores.sitios s
												, g_operadores.operaciones op
												, g_catalogos.tipos_operacion t
												, g_operadores.areas a
											WHERE 
												o.identificador = s.identificador_operador
												and o.identificador = op.identificador_operador
												and s.identificador_operador = op.identificador_operador
												and op.id_tipo_operacion = t.id_tipo_operacion
												and s.id_sitio = a.id_sitio
												and t.codigo = 'PRO'
												and t.id_area = 'SA'
												and a.tipo_area = 'Lugar de producción'
												and s.provincia='$nombreProvincia'
												".$busqueda0."
												ORDER BY s.nombre_lugar asc;");
		return $res;
	}




	public function listaSitioDestino($conexion, $tipoLugarDestino, $tipoSitio, $txtSitio)
	{

		$busqueda0 = '';
			
		switch ($tipoSitio){
			case 1: $busqueda0 = " and s.identificador_operador = '".$txtSitio."'";break;
			case 2: $busqueda0 = " and UPPER(o.apellido_representante) like '%".strtoupper($txtSitio)."%'";break;
			case 3: $busqueda0 = " and UPPER(s.nombre_lugar) like '%".strtoupper($txtSitio)."%'";break;
		}
		$sql = '';
		switch ($tipoLugarDestino){
			case 1://Sitio
				$sql = "SELECT DISTINCT s.id_sitio
						, s.identificador_operador
						, (o.nombre_representante || ' ' || o.apellido_representante) nombres
						, s.nombre_lugar sitio
						, s.provincia
						, s.codigo_provincia
						FROM g_operadores.operadores o
						, g_operadores.sitios s
						, g_operadores.operaciones op
						, g_catalogos.tipos_operacion t
						, g_operadores.areas a
						WHERE o.identificador = s.identificador_operador
						and o.identificador = op.identificador_operador
						and s.identificador_operador = op.identificador_operador
						and op.id_tipo_operacion = t.id_tipo_operacion
						and s.id_sitio = a.id_sitio
						and t.codigo = 'PRO'
						and t.id_area = 'SA'
						and a.tipo_area = 'Lugar de producción'
						".$busqueda0."
						ORDER BY s.nombre_lugar asc	";
				break;

			case 4://Camal
				$sql = "SELECT DISTINCT s.id_sitio
						, s.identificador_operador
						, (o.nombre_representante || ' ' || o.apellido_representante) nombres
						, s.nombre_lugar sitio
						, s.provincia
						, s.codigo_provincia
						FROM g_operadores.operadores o
						, g_operadores.sitios s
						, g_operadores.operaciones op
						, g_catalogos.tipos_operacion t
						, g_operadores.areas a
						WHERE o.identificador = s.identificador_operador
						and o.identificador = op.identificador_operador
						and s.identificador_operador = op.identificador_operador
						and op.id_tipo_operacion = t.id_tipo_operacion
						and s.id_sitio = a.id_sitio
						and t.codigo = 'FAE'
						and t.id_area = 'AI'
						and a.tipo_area = 'Centro de Faenamiento'
						".$busqueda0."
								and op.estado='registrado'
								ORDER BY s.nombre_lugar asc
								";
				break;
		}

		$res = $conexion->ejecutarConsulta($sql);
		return $res;
	}


	public function listaAreaDestinoNormal($conexion,$tipoDestino,$idSitio){
		//-- Sitios/Granjas/Predios
		$busquedaSitio = '';
		switch ($tipoDestino){
			case 1: $busquedaSitio = "a.tipo_area = 'Lugar de producción'"; break;
			case 4: $busquedaSitio = "a.tipo_area = 'Centro de Faenamiento'"; break;
		}
		$res=$conexion->ejecutarConsulta( "SELECT
				a.id_sitio,
				a.id_area,
				a.nombre_area,
				a.tipo_area
				FROM
				g_operadores.operadores o,
				g_operadores.sitios s,
				g_operadores.areas a
				WHERE
				o.identificador = s.identificador_operador
				and a.id_sitio = s.id_sitio
				and s.id_sitio=".$idSitio."
				and ".$busquedaSitio.";");
		return $res;
	}

	public function listaReporteSitiosProduccion($conexion, $parroquia, $canton, $provincia, $fechaInicio, $fechaFin){
		$busqueda2 = '';
		if(($fechaInicio!="0") && ($fechaFin!="0")){
			$fechaInicio1= str_replace("/","-",$fechaInicio);
			$fechaInicio2 = strtotime($fechaInicio1);//la fecha de vencimiento 1 day
			$fechaInicio3  = date('d/m/Y',$fechaInicio2);
				
			//	echo $parroquia;

			$fechaFin1= str_replace("/","-",$fechaFin);
			$fechaFin2 = strtotime ($fechaFin1);//la fecha de vencimiento 1 day
			$fechaFin3  = date('d/m/Y',$fechaFin2);
			$busqueda2 = " and va.fecha_vacunacion >= '".$fechaInicio3."' and va.fecha_vacunacion <= '".$fechaFin3."' ";
		}

		$res = $conexion->ejecutarConsulta("SELECT
				max(va.id_vacuna_animal) id_vacuna_animal,
				v1.id_sitio, v1.codigo_catastral, v1.provincia,
				v1.canton, v1.parroquia, v1.codigo_area,
				v1.identificador_operador, v1.representante,
				v1.nombre_sitio, v1.nombre_area, v1.direccion,
				v1.telefono, v1.lugar_referencia,
				va.identificador_vacunador, va.nombre_especie,
				va.num_certificado,va.total_existente,
				va.total_vacunado, va.num_certificado,
				va.fecha_vacunacion
				FROM
				g_vacunacion_animal.vista_reporte_sitios v1,
				g_vacunacion_animal.vacuna_animales va
				WHERE
				v1.id_vacuna_animal is not null and
				va.id_vacuna_animal=v1.id_vacuna_animal and
				v1.parroquia='".$parroquia."' and
				v1.canton='".$canton."' and
				v1.provincia='".$provincia."'
				".$busqueda2."
				GROUP BY
				v1.id_sitio,
				v1.codigo_catastral,
				v1.provincia,
				v1.canton,
				v1.parroquia,
				v1.codigo_area,
				v1.identificador_operador,
				v1.representante,
				v1.nombre_sitio,
				v1.nombre_area,
				v1.direccion,
				v1.telefono,
				v1.lugar_referencia,
				va.identificador_vacunador,
				va.nombre_especie,
				va.num_certificado,
				va.total_existente,
				va.total_vacunado,
				va.num_certificado,
				va.fecha_vacunacion
				UNION
				SELECT
				v1.id_vacuna_animal,
				v1.id_sitio,
				v1.codigo_catastral,
				v1.provincia,
				v1.canton,
				v1.parroquia,
				v1.codigo_area,
				v1.identificador_operador,
				v1.representante,
				v1.nombre_sitio,
				v1.nombre_area,
				v1.direccion,
				v1.telefono,
				v1.lugar_referencia,
				'' identificador_vacunador,
				'' nombre_especie,
				'' num_certificado,
				null total_existente,
				null total_vacunado,
				'' num_certificado,
				date(null) fecha_vacunacion
				FROM
				g_vacunacion_animal.vista_reporte_sitios v1
				WHERE
				v1.id_vacuna_animal is  null and
				v1.parroquia='".$parroquia."'  and
				v1.canton='".$canton."' and
				v1.provincia='".$provincia."'
				ORDER BY representante, nombre_sitio asc;");
		return $res;
	}


	public function listarDistribuidoresXprovincia($conexion, $nombreProvincia, $tipoOperador){

		if($nombreProvincia!='TODOS'){
			if ($tipoOperador=="1")
				$busqueda0 = " and o.provincia='$nombreProvincia' and o.identificador not like '%002'";
			else
				$busqueda0 = " and o.identificador= substring('$tipoOperador' FROM 1 for 12)||replace(substring('$tipoOperador' FROM 13 for 13), substring('$tipoOperador' FROM 13 for 13), '2')  and o.identificador like '%002'";

		}else {
				
			if ($tipoOperador=="1")
				$busqueda0 = " and o.identificador not like '%002'";
			else
				$busqueda0 = " and o.identificador = substring('$tipoOperador' FROM 1 for 12)||replace(substring('$tipoOperador' FROM 13 for 13), substring('$tipoOperador' FROM 13 for 13), '2')  and o.identificador like '%002'";
		}

		$res = $conexion->ejecutarConsulta( "SELECT  DISTINCT
				t.id_tipo_operacion
				, t.nombre nombre_tipo_operacion
				, o.identificador identificador
				, case when o.razon_social = '' then o.nombre_representante ||' '|| o.apellido_representante else o.razon_social end nombre_distribuidor
				,o.provincia
				FROM g_operadores.operadores o
				, g_operadores.operaciones e
				, g_catalogos.tipos_operacion t
				WHERE o.identificador = e.identificador_operador
				and e.id_tipo_operacion = t.id_tipo_operacion
				and t.id_area = 'SA'
				and t.codigo='DIS'
				$busqueda0
				ORDER BY nombre_distribuidor asc  ;");
		return $res;
	}

	public function listarVacunadoresXprovincia($conexion, $nombreProvincia, $tipoOperador){
		if($nombreProvincia!='TODOS'){
			if ($tipoOperador=="1")
				$busqueda0 = "and o.provincia='$nombreProvincia'";
			else
				$busqueda0 = '';
		}

		$res = $conexion->ejecutarConsulta( "SELECT  DISTINCT
				t.id_tipo_operacion
				, t.nombre nombre_tipo_operacion
				, o.identificador identificador
				, case when o.razon_social = '' then o.nombre_representante ||' '|| o.apellido_representante else o.razon_social end nombre_vacunador
				,o.provincia
				FROM g_operadores.operadores o
				, g_operadores.operaciones e
				, g_catalogos.tipos_operacion t
				WHERE
				o.identificador = e.identificador_operador
				and e.id_tipo_operacion = t.id_tipo_operacion
				and t.id_area = 'SA'
				and t.codigo='VAC'
				$busqueda0
				ORDER BY nombre_vacunador asc;");
		return $res;
	}


	public function buscarProvinciaOperador($conexion, $idOperador){
		$res = $conexion->ejecutarConsulta("SELECT
				o.provincia
				FROM
				g_operadores.operadores o
				WHERE
				o.identificador='$idOperador'");
		return $res;
	}

	public function listarLocalizacionUsuarioPlantaCentral($conexion,$provincia,$localizacion,$idOperador){
		$cid = $this-> buscarProvinciaOperador($conexion, $idOperador);
		$fila = pg_fetch_assoc($cid);
		$busqueda1 = '';

		if($localizacion!='Oficina Planta Central'){
			$busqueda1 = " and nombre = '".$fila['provincia']."'";
		}

		$res = $conexion->ejecutarConsulta("SELECT
				*
				FROM
				g_catalogos.localizacion
				WHERE
				categoria = 1
				".$busqueda1."
				order  by 3;");
			
		return $res;
	}

	public function listarSitiosLocalizacionUsuarioPlantaCentral($conexion,$provincia,$localizacion,$idOperador){
		$cid = $this-> listarLocalizacionUsuarioPlantaCentral($conexion, $provincia, $localizacion,$idOperador);
		while ($fila = pg_fetch_assoc($cid)){
			$res[] = array(codigo=>$fila['id_localizacion'],
					nombre=>$fila['nombre'],categoria=>$fila['categoria'],
					padre=>$fila['id_localizacion_padre'],
					latitud=>$fila['latitud'],
					longitud=>$fila['longitud'],
					zona=>$fila['zona']);
		}
		return $res;
	}

	public function listaSitioProvincia($conexion, $provincia){
		$busqueda1 = " and s.provincia = '".$provincia."'";

		$res = $conexion->ejecutarConsulta("SELECT DISTINCT
												s.id_sitio, s.identificador_operador
												, case when o.razon_social = '' then o.nombre_representante ||' '|| o.apellido_representante else o.razon_social end nombres
												, s.nombre_lugar granja, s.provincia
											FROM
												g_operadores.operadores o, g_operadores.sitios s, g_operadores.operaciones op, g_catalogos.tipos_operacion t
											WHERE
												o.identificador = s.identificador_operador
												and o.identificador = op.identificador_operador
												and s.identificador_operador = op.identificador_operador
												and op.id_tipo_operacion = t.id_tipo_operacion
												and t.codigo = 'PRO'
												and t.id_area = 'SA'
												and not s.nombre_lugar =''
												".$busqueda1."
											ORDER BY
												s.provincia , s.nombre_lugar ASC");
		return $res;
	}

	public function listaVacunacionAnimalTodos($conexion, $identificadorOperador, $numeroCertificado, $fechaInicio, $fechaFin){
		$busqueda0 = '';
		$busqueda1 = '';
		$busqueda2 = '';
		$busqueda3 = '';
			
		if(($numeroCertificado=="0") && ($fechaInicio=="0") && ($fechaFin=="0") && ($identificadorOperador=="0"))
			$busqueda0 = " and v.fecha_vacunacion >= current_date and v.fecha_vacunacion < current_date+1";

		if($numeroCertificado!="0")
			$busqueda1 = " and UPPER(v.num_certificado) like '%".strtoupper($numeroCertificado)."' ";

		if(($fechaInicio!="0") && ($fechaFin!="0")){
			$fechaInicio1= str_replace("/","-",$fechaInicio);
			$fechaInicio2 = strtotime ('-1 day',strtotime($fechaInicio1));//la fecha de vencimiento 1 day
			$fechaInicio3  = date('d/m/Y',$fechaInicio2);
			$fechaFin1= str_replace("/","-",$fechaFin);
			$fechaFin2 = strtotime ('+1 day',strtotime($fechaFin1));//la fecha de vencimiento 1 day
			$fechaFin3  = date('d/m/Y',$fechaFin2);
			$busqueda2 = " and v.fecha_vacunacion >= '".$fechaInicio3."' and v.fecha_vacunacion <= '".$fechaFin3."' ";
		}

		if($identificadorOperador!="0")
			$busqueda3 = " and s.identificador_operador = '$identificadorOperador' ";

		$res = $conexion->ejecutarConsulta("SELECT
												v.id_vacuna_animal
												,v.num_certificado
												,s.nombre_lugar nombre_sitio
												,a.nombre_area
												, (SELECT
												case when p.codificacion_perfil='PFL_USUAR_INT' then (SELECT upper((rsv.nombre::text || ' '::text) || rsv.apellido::text)  nombre_digitador FROM g_uath.ficha_empleado rsv WHERE v.usuario_responsable = rsv.identificador  )
												else (SELECT case when ore.razon_social = '' then ore.nombre_representante ||' '|| ore.apellido_representante else ore.razon_social end nombre_digitador FROM g_operadores.operadores ore WHERE v.usuario_responsable = ore.identificador   ) end nombre_digitador
												FROM g_usuario.perfiles p, g_usuario.usuarios_perfiles up
												WHERE p.id_perfil=up.id_perfil and p.codificacion_perfil in ('PFL_USUAR_INT','PFL_USUAR_EXT') and up.identificador=v.usuario_responsable)
												,to_char(v.fecha_vacunacion,'DD/MM/YYYY') fecha_vacunacion
											FROM 
												g_vacunacion_animal.vacuna_animales v
												,g_operadores.sitios s
												,g_operadores.areas a
											WHERE
												 s.id_sitio = v.id_sitio
												and a.id_area = v.id_area
												".$busqueda0."
												".$busqueda1."
												".$busqueda2."
												".$busqueda3.";");
	return $res;
	}

	public function listarVacunadoresOficialesAutoservicio($conexion, $idOperadorVacunacion){
		$query = $conexion->ejecutarConsulta("SELECT
				apv.identificador_administrador, ad.identificador_distribuidor, av.identificador_vacunador, op.nombre_representante || ' ' || op.apellido_representante as nombre_vacunador, apv.id_especie												FROM
				g_vacunacion_animal.administrador_vacunacion apv,
				g_vacunacion_animal.administrador_distribuidor ad,
				g_vacunacion_animal.administrador_vacunador av,
				g_operadores.operadores op
				WHERE
				ad.id_administrador_vacunacion=apv.id_administrador_vacunacion
				and av.id_administrador_distribuidor=ad.id_administrador_distribuidor
				and av.identificador_vacunador=op.identificador
				and apv.identificador_administrador='$idOperadorVacunacion'
				and av.estado='activo'
				ORDER BY
				nombre_vacunador");

		while ($fila = pg_fetch_assoc($query)){
			$res[] = array(identificador_administrador=>$fila['identificador_administrador'],
					identificador_distribuidor=>$fila['identificador_distribuidor'],
					identificador_vacunador=>$fila['identificador_vacunador'],
					nombre_vacunador=>$fila['nombre_vacunador'],
					id_especie=>$fila['id_especie']
			);
		}

		return $res;
	}
	function listarVacunadoresOficiales($conexion, $idDistribuidorVacunacion){
		$res = $conexion->ejecutarConsulta("SELECT
				apv.identificador_administrador, ad.identificador_distribuidor, av.identificador_vacunador, op.nombre_representante || ' ' || op.apellido_representante as nombre_vacunador	, apv.id_especie											FROM
				g_vacunacion_animal.administrador_vacunacion apv,
				g_vacunacion_animal.administrador_distribuidor ad,
				g_vacunacion_animal.administrador_vacunador av,
				g_operadores.operadores op
				WHERE
				ad.id_administrador_vacunacion=apv.id_administrador_vacunacion
				and av.id_administrador_distribuidor=ad.id_administrador_distribuidor
				and av.identificador_vacunador=op.identificador
				and ad.identificador_distribuidor='$idDistribuidorVacunacion'
				and av.estado='activo'
				ORDER BY
				nombre_vacunador");



		return $res;
	}

	public function filtrarSitiosVacunacion($conexion, $identificadorOperador, $nombresOperador,$nombreSitio , $codigoArea){

		$identificadorOperador = $identificadorOperador!="" ? "'" . $identificadorOperador . "'" : "null";
		$nombresOperador = $nombresOperador!="" ? "'" . $nombresOperador . "'" : "null";
		$nombreSitio = $nombreSitio!="" ? "'" . $nombreSitio . "'" : "null";
		$codigoArea = $codigoArea!="" ?  "'" . $codigoArea. "'" : "null";
			
		$res = $conexion->ejecutarConsulta("SELECT
				*
				FROM
				g_vacunacion_animal.mostrar_sitios_filtrados($identificadorOperador,$nombreSitio,$codigoArea,'registrado','SA',$nombresOperador)");
		return $res;
	}

	public function listarAreasXsitios($conexion, $idSitio){
		$res = $conexion->ejecutarConsulta("SELECT
				a.id_area,
				a.nombre_area
				FROM
				g_operadores.areas a
				WHERE
				a.id_sitio='$idSitio'");
		return $res;
	}

	public function filtrarSitiosCatastro($conexion,$identificadorOperador, $nombresOperador,$nombreSitio , $codigoArea){
			
		$identificadorOperador = $identificadorOperador!="" ? "'" . $identificadorOperador . "'" : "null";
		$nombresOperador = $nombresOperador!="" ? "'" . $nombresOperador . "'" : "null";
		$nombreSitio = $nombreSitio!="" ? "'" . $nombreSitio . "'" : "null";
		$codigoArea = $codigoArea!="" ?  "'" . $codigoArea. "'" : "null";

		$res = $conexion->ejecutarConsulta("SELECT
				*
				FROM
				g_vacunacion_animal.sitios_filtrados_catastro($identificadorOperador,$nombreSitio,$codigoArea,'registrado','SA','PRO',$nombresOperador) ");
		return $res;
	}

	public function obtenerTipoUsuario($conexion,$usuario){
		$res = $conexion->ejecutarConsulta("SELECT
				p.codificacion_perfil
				,up.identificador
				FROM
				g_usuario.perfiles p,
				g_usuario.usuarios_perfiles up
				WHERE
				p.id_perfil=up.id_perfil and
				p.codificacion_perfil in ('PFL_USUAR_INT','PFL_USUAR_EXT') and
				up.identificador='$usuario';");
		return $res;
	}

	public function verificarTecnicoAgrocalidad($conexion, $usuario) {
		$res = $conexion->ejecutarConsulta ("SELECT
				fe.identificador , fe.nombre ||' '|| fe.apellido as nombres
				FROM
				g_uath.ficha_empleado fe, g_uath.datos_contrato dc
				WHERE
				fe.identificador=dc.identificador
				and dc.estado=1 and fe.identificador='$usuario'
				ORDER BY nombres asc
				;");
		return $res;
	}

	public function buscarNumeroCertificado($conexion, $tipoDocumento, $serie, $idEspecie){
		$res = $conexion->ejecutarConsulta("SELECT DISTINCT id_especie
				, serie
				, numero_documento
				, estado
				FROM
				g_vacunacion_animal.serie_documentos
				WHERE
				id_especie = '$idEspecie'
				and tipo_documento='".$tipoDocumento."'
				and serie='".$serie."'");
		return $res;
	}

	public function filtrarTecnicoVacunador($conexion, $identificacionVacunador, $nombreVacunador){
		$identificacionVacunador = $identificacionVacunador!="" ? "'" . $identificacionVacunador . "'" : "NULL";
		$nombreVacunador = $nombreVacunador!="" ? "'%" . $nombreVacunador . "%'" : "NULL";
		$res = $conexion->ejecutarConsulta("SELECT
												fe.identificador , fe.nombre ||' '|| fe.apellido as nombres
											FROM
												g_uath.ficha_empleado fe, g_uath.datos_contrato dc
											WHERE
												fe.identificador=dc.identificador
												and dc.estado=1
												and ($identificacionVacunador is NULL or fe.identificador = $identificacionVacunador)
												and ($nombreVacunador is NULL or fe.nombre ||' '|| fe.apellido ilike $nombreVacunador)
											ORDER BY nombres asc
				");
		return $res;
	}


	function buscarEmpresaDigitador($conexion, $digitador){
		$res = $conexion->ejecutarConsulta("SELECT 
												opv.identificador
												,emp.id_empresa 
											FROM 
												g_vacunacion_animal.roles_empleados re
												,g_usuario.empleados em
												,g_usuario.empresas emp
												,g_operadores.operadores opv
											WHERE 
												em.id_empleado=re.id_empleado 
												and em.id_empresa=emp.id_empresa 
												and opv.identificador=emp.identificador
												and re.tipo='digitador externo' 
												and re.estado='activo' and em.identificador='$digitador';");
		return $res;
	}

	function listarVacunadoresEmpresa($conexion, $idEmpresa){
		$res = $conexion->ejecutarConsulta("SELECT DISTINCT
												opv.identificador
												,case when opv.razon_social = '' then opv.nombre_representante ||' '|| opv.apellido_representante else opv.razon_social end nombres
											FROM 
												g_vacunacion_animal.roles_empleados re
												,g_usuario.empleados em
												,g_usuario.empresas emp
												,g_operadores.operadores opv
												,g_operadores.operaciones op
												,g_catalogos.tipos_operacion t
											WHERE
												em.id_empleado=re.id_empleado 
												and em.id_empresa=emp.id_empresa 
												and opv.identificador=em.identificador 
												and opv.identificador = op.identificador_operador
												and op.id_tipo_operacion = t.id_tipo_operacion
												and t.codigo = 'VAC'
												and t.id_area = 'SA'
												and re.tipo='vacunador' 
												and re.estado='activo'
												and emp.id_empresa='$idEmpresa' 
											ORDER BY nombres asc;");
		return $res;
	}

	function listarDistribuidoresEmpresa($conexion, $idEmpresa){
		$res = $conexion->ejecutarConsulta("SELECT DISTINCT
												opv.identificador
												, case when opv.razon_social = '' then opv.nombre_representante ||' '|| opv.apellido_representante else opv.razon_social end nombres
											FROM 
												g_vacunacion_animal.roles_empleados re
												, g_usuario.empleados em, g_usuario.empresas emp
												, g_operadores.operadores opv
												, g_operadores.operaciones op
												, g_catalogos.tipos_operacion t
											WHERE 
												em.id_empleado=re.id_empleado 
												and em.id_empresa=emp.id_empresa 
												and opv.identificador=em.identificador 
												and opv.identificador = op.identificador_operador
												and op.id_tipo_operacion = t.id_tipo_operacion
												and t.codigo = 'DIS'
												and t.id_area = 'SA'
												and re.tipo='distribuidor externo' and re.estado='activo'
												and emp.id_empresa='$idEmpresa' ORDER BY nombres asc;");
		return $res;
	}

	public function listarTecnicosDistribuidores($conexion) {
		$res = $conexion->ejecutarConsulta ("SELECT 
												fe.identificador 
												,upper((fe.nombre::text || ' '::text) || fe.apellido::text) nombres
												,dc.provincia
											FROM 
												g_vacunacion_animal.tecnico_distribuidor td
												, g_uath.ficha_empleado fe
												, g_uath.datos_contrato dc
											WHERE 
												td.identificador=fe.identificador 
												and fe.identificador=dc.identificador
												and dc.estado=1
												and td.estado='activo' ORDER BY fe.nombre asc;");
		return $res;
	}

	public function listarTecnicosVacunadores($conexion) {
		$res = $conexion->ejecutarConsulta ("SELECT
												fe.identificador , upper((fe.nombre::text || ' '::text) || fe.apellido::text) nombres, lo.nombre provincia
											FROM
												g_uath.ficha_empleado fe, g_uath.datos_contrato dc , g_catalogos.localizacion lo
											WHERE
												fe.identificador=dc.identificador
												and fe.id_localizacion_provincia=lo.id_localizacion
												and dc.estado=1
												ORDER BY nombres asc;");
		return $res;
	}


	public function listaReporteVacunacionAnimalUI($conexion,$identificadorDistribuidor,$identificadorVacunador,$provincia, $fechaInicio, $fechaFin, $estado){
		$busqueda0 = '';
		$busqueda1 = '';
		$busqueda2 = '';
		$busqueda3 = '';

		if($identificadorDistribuidor=="TODOS"){
			$busqueda0 = "";
		}else{
			$busqueda0 = " and v.identificador_distribuidor='$identificadorDistribuidor'";
			if($identificadorVacunador=="TODOS"){
				$busqueda0 .= "";
			}else{
				$busqueda0 .= " and v.identificador_vacunador='$identificadorVacunador'";
			}
		}
		
		if($provincia!="TODOS")
			$busqueda3 = " and s.provincia='$provincia'";

		if ($estado!="0")
			$busqueda1 = " and v.estado_vacunacion = '".$estado."' ";

		if(($fechaInicio!="0") && ($fechaFin!="0")){
			$fechaInicio1 = str_replace("/","-",$fechaInicio);
			$fechaInicio2 = strtotime($fechaInicio1);//la fecha de vencimiento 1 day
			$fechaInicio3 = date('d/m/Y',$fechaInicio2);

			$fechaFin1 = str_replace("/","-",$fechaFin);
			$fechaFin2 =  strtotime ('+1 day',strtotime($fechaFin1));//la fecha de vencimiento 1 day
			$fechaFin3 = date('d/m/Y',$fechaFin2);

			$busqueda2 = " and v.fecha_registro >= '".$fechaInicio3."' and v.fecha_registro <= '".$fechaFin3."' ";
		}

		$res = $conexion->ejecutarConsulta("SELECT DISTINCT 
												v.id_vacuna_animal,
												'Usuario interno'::text AS tipo_digitador,
												upper(s.nombre_lugar::text) AS nombre_sitio,
												upper(a.nombre_area::text) AS nombre_area,
												s.provincia,
												s.canton,
												s.parroquia,
												v.nombre_especie,
												upper((rsd.nombre::text || ' '::text) || rsd.apellido::text) AS nombre_distribuidor,
												upper((rsv.nombre::text || ' '::text) || rsv.apellido::text) AS nombre_vacunador,
												upper((rsr.nombre::text || ' '::text) || rsr.apellido::text) AS nombre_responsable,
												upper((pp.nombre_representante::text || ' '::text) || pp.apellido_representante::text) AS nombre_propietario,
												tv.nombre_vacuna,
												t.nombre_laboratorio,
												l.numero_lote,
												v.num_certificado,
												v.total_existente,
												v.total_vacunado,
												v.costo_vacuna,
												v.total_vacuna,
												v.fecha_registro,
												v.fecha_vacunacion,
												v.fecha_vencimiento,
												v.estado_vacunacion AS estado,
												v.observacion
											FROM 
												g_vacunacion_animal.vacuna_animales v,
												g_uath.ficha_empleado rsd,
												g_uath.ficha_empleado rsr,
												g_uath.ficha_empleado rsv,
												g_operadores.sitios s,
												g_operadores.operadores pp,
												g_operadores.areas a,
												g_catalogos.lotes l,
												g_catalogos.laboratorios t,
												g_catalogos.tipo_vacunas tv,
												g_usuario.usuarios_perfiles up,
												g_usuario.perfiles p
											WHERE
												v.identificador_distribuidor::text = rsd.identificador::text
												AND  v.identificador_vacunador::text = rsv.identificador::text
												AND v.usuario_responsable::text = rsr.identificador::text
												AND s.id_sitio = v.id_sitio
												AND s.identificador_operador::text = pp.identificador::text AND a.id_area = v.id_area AND v.id_lote = l.id_lote AND l.id_laboratorio = t.id_laboratorio
												AND v.id_tipo_vacuna = tv.id_tipo_vacuna AND v.usuario_responsable::text = up.identificador::text
												AND up.id_perfil = p.id_perfil AND p.id_perfil = 2
												".$busqueda0."
												".$busqueda1."
												".$busqueda2."
												".$busqueda3."
												ORDER BY  s.provincia, nombre_sitio asc;");
		return $res;
	}

	public function listaEmpresas($conexion) {
		$res = $conexion->ejecutarConsulta ("SELECT 
												em.id_empresa 
												,case when op.razon_social = '' then op.nombre_representante ||' '|| op.apellido_representante else op.razon_social end nombre_empresa
												, em.tipo tipo_empresa
											FROM 
												g_usuario.empresas em
												,g_operadores.operadores op 
											WHERE 
												em.identificador=op.identificador 
												and em.estado='activo'
												ORDER BY nombre_empresa asc;");
		return $res;
	}


	function listaDistribuidoresEmpresas($conexion){
		$res = $conexion->ejecutarConsulta("SELECT DISTINCT
												em.id_empresa
												,opv.identificador
												, case when opv.razon_social = '' then opv.nombre_representante ||' '|| opv.apellido_representante else opv.razon_social end nombres
											FROM 
												g_vacunacion_animal.roles_empleados re
												, g_usuario.empleados em
												, g_usuario.empresas emp
												, g_operadores.operadores opv
												,g_operadores.operaciones op
												, g_catalogos.tipos_operacion t
											WHERE 
												em.id_empleado=re.id_empleado
												and em.id_empresa=emp.id_empresa
												and opv.identificador=em.identificador
												and opv.identificador = op.identificador_operador
												and op.id_tipo_operacion = t.id_tipo_operacion
												and t.codigo = 'DIS'
												and t.id_area = 'SA'
												and re.tipo='distribuidor externo' 
												and re.estado='activo' ORDER BY nombres asc;");

		return $res;
	}

	function listaVacunadoresEmpresa($conexion){
		$res = $conexion->ejecutarConsulta("SELECT DISTINCT
												em.id_empresa
												,opv.identificador
												, case when opv.razon_social = '' then opv.nombre_representante ||' '|| opv.apellido_representante else opv.razon_social end nombres
											FROM 
												g_vacunacion_animal.roles_empleados re
												, g_usuario.empleados em
												, g_usuario.empresas emp
												, g_operadores.operadores opv,g_operadores.operaciones op
												, g_catalogos.tipos_operacion t
											WHERE 
												em.id_empleado=re.id_empleado 
												and em.id_empresa=emp.id_empresa 
												and opv.identificador=em.identificador 
												and opv.identificador = op.identificador_operador
												and op.id_tipo_operacion = t.id_tipo_operacion
												and t.codigo = 'VAC'
												and t.id_area = 'SA'
												and re.tipo='vacunador' 
												and re.estado='activo' ORDER BY nombres asc");
		return $res;
	}

}