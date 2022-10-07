<?php

class ControladorDossierFertilizante{


	//**************************** Funciones de la Solicitud **************************************************************************
	public function obtenerFlujoEquivalente($conexion,$codigoAplicacion){
		$sql="select * from g_programas.aplicaciones where codificacion_aplicacion='$codigoAplicacion'";
		$resultado = $conexion->ejecutarConsulta($sql);
		if(pg_num_rows($resultado)>0)
		{
			$fila=pg_fetch_assoc($resultado,0);
			return $fila['id_aplicacion'];
		}
		else
			return 0;
	}

	public function listarSolicitudesOperador ($conexion, $identificador){
		$sql="select   pr.id_solicitud,pr.producto_nombre,pr.fecha_solicitud,pr.estado,
			(select tf.fecha_inicio from g_ensayo_eficacia.tramites t left join g_ensayo_eficacia.tramites_flujos tf on tf.id_tramite=t.id_tramite 
			where t.id_documento=pr.id_solicitud and t.tipo_documento='DF' and (tf.pendiente is null or tf.pendiente!='N') order by tf.id_tramite_flujo DESC LIMIT 1) ,
			(select tf.fecha_fin from g_ensayo_eficacia.tramites t left join g_ensayo_eficacia.tramites_flujos tf on tf.id_tramite=t.id_tramite 
			where t.id_documento=pr.id_solicitud and t.tipo_documento='DF' and (tf.pendiente is null or tf.pendiente!='N') order by tf.id_tramite_flujo DESC LIMIT 1) 
			from g_dossier_fertilizante.solicitudes pr where pr.identificador = '$identificador';";
		$res = $conexion->ejecutarConsulta($sql);
		
		return $res;		
	}

	public function guardarSolicitud($conexion,$datos){
		$sql="";
		$ya=false;
		$sss="";
		$tipo="";
		if(in_array('id_solicitud', array_keys($datos)))
		{
			//actualizar
			$tipo="update";
			$iden=$datos['id_solicitud'];
			$sql="UPDATE  g_dossier_fertilizante.solicitudes
				set ";
			foreach ($datos as $id => $valor)
			{
				if($id=='id_solicitud')
					continue;

				if($ya)
					$sql .=",".$id;
				else{
					$sql .=$id;
					$ya=true;
				}
				$sss=$this->valorCorrecto($valor);
				$sql.="=".$sss;


			}
			$sql.="	WHERE
						id_solicitud=$iden;";
		}
		else
		{
			//insertar
			$tipo="insert";
			$sql="INSERT INTO g_dossier_fertilizante.solicitudes(";
			$sqlValues=") VALUES (";
			foreach ($datos as $id => $valor) {
				$sss=$this->valorCorrecto($valor);
				if($ya){
					$sql .=",".$id;
					$sqlValues.=",".$sss;
				}
				else{
					$ya=true;
					$sql .=$id;
					$sqlValues.=$sss;
				}

			}

			$sql.=$sqlValues.")";
			$sql.=" RETURNING id_solicitud;";
		}

		$res = $conexion->ejecutarConsulta($sql);
		$re=array();
		while ($fila = pg_fetch_assoc($res)){
			$re[] = $fila;
		}

		$ar=array();
		$ar['tipo']=$tipo;
		$ar['resultado']=	$re;
		return $ar;
	}

	public function obtenerSolicitud ($conexion, $idSolicitud){
		$sql="select pr.*,o.razon_social, rt.nombre as motivo	from g_dossier_fertilizante.solicitudes pr
				inner join g_operadores.operadores o on pr.identificador=o.identificador
				left join g_catalogos.catalogo_ef rt on rt.codigo=pr.objetivo
				where	pr.id_solicitud = $idSolicitud;";
		$query = $conexion->ejecutarConsulta($sql);

		return pg_fetch_assoc($query);
	}

	public function eliminarSolicitud($conexion, $idSolicitud){
		//borrar
		$sql="DELETE FROM g_dossier_fertilizante.solicitudes WHERE id_solicitud=$idSolicitud;";
		$conexion->ejecutarConsulta($sql);		
	}

	//**************************** Funciones de operadores ****************************************************************************


	//retorna datos del operador con los sitios y areas registados
	public function obtenerOperadorConSitiosAreas($conexion,$identificador){
		$res=array();
		try{
			$query = $conexion->ejecutarConsulta("select op.razon_social, op.identificador from g_operadores.operadores op
				where op.identificador='$identificador';");
			while ($fila = pg_fetch_assoc($query)){
				$res[] = $fila;
			}
			if(sizeof($res)>0){
				$res=$res[0];
				$res['sitios']=$this->obtenerSitiosAreas($conexion, $identificador);

			}
		}catch(Exception $e){}

		return $res;

	}
	//**************************** Funciones para representantes técnicos *************************************************************
	public function obtenerRepresentantesTecnicosSitio($conexion,$tipoProducto,$subTipoProducto,$sitio){
		$res=array();
		try{
			$query = $conexion->ejecutarConsulta("Select dt.* from g_operadores.detalle_representantes_tecnicos dt
				inner join g_operadores.representantes_tecnicos rt on dt.id_representante_tecnico=rt.id_representante_tecnico
				inner join g_operadores.operadores_tipo_operaciones oo on oo.id_operador_tipo_operacion=rt.id_operador_tipo_operacion
				where oo.id_sitio=$sitio and dt.id_tipo_producto=$tipoProducto and dt.id_subtipo_producto=$subTipoProducto;");
			while ($fila = pg_fetch_assoc($query)){
				$res[] = $fila;
			}
		}catch(Exception $e){}

		return $res;

	}

	public function obtenerRepresentantesTecnicos($conexion,$area){
		$res=array();
		try{
			$query = $conexion->ejecutarConsulta("Select dt.*,ao.* from g_operadores.detalle_representantes_tecnicos dt
				inner join g_operadores.representantes_tecnicos rt on dt.id_representante_tecnico=rt.id_representante_tecnico
				inner join g_operadores.productos_areas_operacion ao on rt.id_operacion=ao.id_operacion
				where ao.id_area=$area;");
			while ($fila = pg_fetch_assoc($query)){
				$res[] = $fila;
			}
		}catch(Exception $e){}

		return $res;

	}

	public function obtenerRepresentanteTecnico($conexion,$area,$identificacion_representante){
		$res=array();
		try{
			$query = $conexion->ejecutarConsulta("Select dt.*,ao.* from g_operadores.detalle_representantes_tecnicos dt
				inner join g_operadores.representantes_tecnicos rt on dt.id_representante_tecnico=rt.id_representante_tecnico
				inner join g_operadores.productos_areas_operacion ao on rt.id_operacion=ao.id_operacion
				where ao.id_area=$area AND dt.identificacion_representante='$identificacion_representante';");
			while ($fila = pg_fetch_assoc($query)){
				$res[] = $fila;
			}
			if(sizeof($res)>0)
				$res=$res[0];
		}catch(Exception $e){}

		return $res;

	}

	//***************************** FABRICANTES EXTRANJEROS ***********************************************************************
	
	public function obtenerFabricantesDossier($conexion,$id_solicitud){
		$res=array();
		try{
			$query = $conexion->ejecutarConsulta("select sf.*,l.nombre as pais from g_dossier_fertilizante.solicitud_fabricantes sf left join g_catalogos.localizacion l on sf.id_pais=l.id_localizacion
				where sf.id_solicitud=$id_solicitud;");
			while ($fila = pg_fetch_assoc($query)){
				$res[] = $fila;
			}

		}catch(Exception $e){}

		return $res;

	}


	public function agregarFabricanteDossier($conexion,$id_solicitud,$identificador,$id_sitio,$tipo,$direccion,$empresa,$id_pais){
		$res=array();
		try{
			//verifico si ya existe
			$sql="select * from g_dossier_fertilizante.solicitud_fabricantes WHERE id_solicitud=$id_solicitud AND identificador='$identificador';";
			$res = $conexion->ejecutarConsulta($sql);
			if(pg_num_rows($res)<1){
				$sql="INSERT INTO g_dossier_fertilizante.solicitud_fabricantes (id_solicitud,identificador,id_sitio,tipo,direccion,empresa,id_pais)
				VALUES ($id_solicitud,'$identificador',$id_sitio,'$tipo','$direccion','$empresa',$id_pais)
				RETURNING id_solicitud;";
			}else{
				$sql="UPDATE  g_dossier_fertilizante.solicitud_fabricantes set id_sitio=$id_sitio,tipo='$tipo',direccion='$direccion',empresa='$empresa',id_pais=$id_pais
					WHERE id_solicitud=$id_solicitud AND identificador='$identificador';";
			}
			$res = $conexion->ejecutarConsulta($sql);
			//recupera los fabricantes
			$res=$this->obtenerFabricantesDossier($conexion,$id_solicitud);
		}
		catch(Exception $ex){

		}
		return $res;
	}

	public function eliminarFabricante($conexion, $id_solicitud_fabricante){
		//borrar
		$sql="DELETE FROM g_dossier_fertilizante.solicitud_fabricantes WHERE id_solicitud_fabricante=$id_solicitud_fabricante;";
		$res = $conexion->ejecutarConsulta($sql);
		$re=array();
		while ($fila = pg_fetch_assoc($res)){
			$re[] = $fila;
		}

		$ar=array();
		$ar['tipo']='delete';
		$ar['resultado']=	$re;
		return $ar;

	}


	//***************************** Operaciones con catalogos *********************************



	public function obtenerTipoProducto ($conexion, $idArea){
		$res=array();
		try{
			$query = $conexion->ejecutarConsulta("select
												pr.*
											from
												g_catalogos.tipo_productos pr
											where pr.estado=1 AND pr.id_area = '$idArea';");
			while ($fila = pg_fetch_assoc($query)){
				$res[] = $fila;
			}
		}catch(Exception $e){}

		return $res;
	}

	public function obtenerSubTiposProducto ($conexion, $idArea){
		$sql="select sp.* from g_catalogos.subtipo_productos sp
			inner join g_catalogos.tipo_productos pr on sp.id_tipo_producto=pr.id_tipo_producto
			where sp.estado=1 AND pr.estado=1 AND pr.id_area = '$idArea'";
		$res=array();
		$query = $conexion->ejecutarConsulta($sql);
		while ($fila = pg_fetch_assoc($query)){
			$res[] = $fila;
		}

		return $res;
	}


	

	public function obtenerSitiosAreas($conexion, $operador){

		$res=array();
		try{
			$query = $conexion->ejecutarConsulta("select id_sitio ,nombre_lugar,parroquia,direccion, superficie_total,identificador_operador,
				referencia, estado,telefono,canton,provincia from g_operadores.sitios where identificador_operador='$operador';");					
			while ($fila = pg_fetch_assoc($query)){

				//llena las areas del sitio
				$q = $conexion->ejecutarConsulta("select a.* from g_operadores.areas a inner join g_operadores.sitios s on a.id_sitio=s.id_sitio where s.id_sitio=".$fila['id_sitio'].";");
				$areas=array();
				while ($area = pg_fetch_assoc($q)){
					//agrega el representante técnico del area
					$area['representates_tecnicos']=$this->obtenerRepresentantesTecnicos($conexion,$area['id_area']);
					$areas[]=$area;
				}
				$fila['areas']=$areas;
				$res[]=$fila;

			}
		}catch(Exception $e){}

		return $res;
	}


	//************************* FORMULACIONES  **********************************************************************
	public function obtenerFormulacionesPorArea ($conexion,$area,$likeSigla='DF_%'){
		$sql="select *,sigla as codigo,formulacion as nombre from g_catalogos.formulacion where id_area='$area'";
		if($likeSigla!=null){
			$sql=$sql." AND sigla like '$likeSigla';";
		}
		$res=array();
		$query = $conexion->ejecutarConsulta($sql);
		while ($fila = pg_fetch_assoc($query)){
			$res[] = $fila;
		}
	
		return $res;
	}

	//******************************** VALIDACION DE NOMBRES *****************************************************************

	public function obtenerProductosPorAreaTematica ($conexion,$areaTematica){
		$res=array();
		try{
			$query = $conexion->ejecutarConsulta("select * from g_catalogos.productos where id_subtipo_producto in
				(select id_subtipo_producto from g_catalogos.subtipo_productos where id_tipo_producto in
				(select id_tipo_producto from g_catalogos.tipo_productos where id_area='$areaTematica'));");
			while ($fila = pg_fetch_assoc($query)){
				$res[] = $fila;
			}
		}catch(Exception $e){}

		return $res;
	}

	public function validarNombreProductosPorAreaTematica ($conexion,$areaTematica,$nombreTest){
		$res=array();
		try{
			$query = $conexion->ejecutarConsulta("select nombre_comun from g_catalogos.productos where id_subtipo_producto in
				(select id_subtipo_producto from g_catalogos.subtipo_productos where id_tipo_producto in
				(select id_tipo_producto from g_catalogos.tipo_productos where id_area='$areaTematica')) AND lower(nombre_comun) LIKE lower('".$nombreTest."%');");
			while ($fila = pg_fetch_assoc($query)){
				$res[] = $fila;
			}
		}catch(Exception $e){}

		return $res;
	}
	//*************************************** COMPOSICION DEL PRODUCTO ****************************************************

   public function obtenerComposicionProducto($conexion,$id_solicitud){
		$sql="select sc.*,c.ingrediente_activo as nombre,um.codigo from g_dossier_fertilizante.solicitud_composiciones sc
				left join g_catalogos.ingrediente_activo_inocuidad c on c.id_ingrediente_activo=sc.elemento
				left join g_catalogos.unidades_medidas um on um.id_unidad_medida=sc.id_unidad_medida
				where sc.id_solicitud=$id_solicitud";
		$res=array();
		$query = $conexion->ejecutarConsulta($sql);
		while ($fila = pg_fetch_assoc($query)){
			$res[] = $fila;
		}

		return $res;

	}


	public function agregarComposicionProducto ($conexion,$id_solicitud,$id_elemento,$valor,$id_unidad){
		$res=array();
		try{
			//verifico si ya existe
			$sql="select * from g_dossier_fertilizante.solicitud_composiciones WHERE id_solicitud=$id_solicitud AND elemento=$id_elemento;";
			$res = $conexion->ejecutarConsulta($sql);
			if(pg_num_rows($res)<1){
				$sql="INSERT INTO g_dossier_fertilizante.solicitud_composiciones (id_solicitud,elemento,cantidad,id_unidad_medida)
				VALUES ($id_solicitud,$id_elemento,'$valor',$id_unidad)
				RETURNING id_solicitud_composicion;";
			}else{
			   $sql="UPDATE  g_dossier_fertilizante.solicitud_composiciones set cantidad='$valor',id_unidad_medida=$id_unidad
			      WHERE id_solicitud=$id_solicitud AND elemento=$id_elemento;";
			}
			$res = $conexion->ejecutarConsulta($sql);
			//recupera los fabricantes
			$res=$this->obtenerComposicionProducto($conexion,$id_solicitud);
		}
		catch(Exception $ex){

		}
		return $res;
	}

	public function eliminarComposicionProducto($conexion, $id_solicitud_composicion){
		//borrar
		$sql="DELETE FROM g_dossier_fertilizante.solicitud_composiciones WHERE id_solicitud_composicion=$id_solicitud_composicion;";
		$res = $conexion->ejecutarConsulta($sql);
		$re=array();
		while ($fila = pg_fetch_assoc($res)){
			$re[] = $fila;
		}

		$ar=array();
		$ar['tipo']='delete';
		$ar['resultado']=	$re;
		return $ar;

	}

		//*****************************  ANEXOS  ****************************************************


		public function listarArchivosAnexos($conexion,$id_solicitud){
			$res=array();

			$query = $conexion->ejecutarConsulta("
			select sa.*,c.nombre from g_dossier_fertilizante.solicitud_anexos sa
			left join g_catalogos.catalogo_ef_ex c on c.codigo=sa.tipo where id_solicitud=$id_solicitud;");

			while ($fila = pg_fetch_assoc($query)){
				$res[] = $fila;
			}

			return $res;
		}

		public function listarArchivosAnexo($conexion,$id_solicitud_anexos){
			$res=array();

			$query = $conexion->ejecutarConsulta("select sa.*,c.nombre  from g_dossier_fertilizante.solicitud_anexos sa
				left join g_catalogos.catalogo_ef_ex c on c.codigo=sa.tipo where id_solicitud_anexos=$id_solicitud_anexos;");

			while ($fila = pg_fetch_assoc($query)){
				$res = $fila;
			}

			return $res;
		}

		public function buscarArchivoAnexo($conexion,$id_solicitud,$referencia,$fase,$usuario){
			$res=array();

			$query = $conexion->ejecutarConsulta("
			select * from g_dossier_fertilizante.solicitud_anexos where id_solicitud=$id_solicitud AND lower(trim(referencia))=lower(trim('$referencia') AND usuario='$usuario' AND fase='$fase';");

			while ($fila = pg_fetch_assoc($query)){
				$res[] = $fila;
			}

			return $res;
		}

		public function agregarArchivoAnexo($conexion, $id_solicitud,$archivo,$referencia,$fase,$usuario,$tipo){
			$sql="select sa.* from g_dossier_fertilizante.solicitud_anexos sa where id_solicitud=$id_solicitud and sa.tipo='$tipo';";
			$tipoSql='';
			$res = $conexion->ejecutarConsulta($sql);
			if(pg_num_rows($res)>0){
				$tipoSql='update';
				$sql="UPDATE g_dossier_fertilizante.solicitud_anexos
				SET  path='$archivo'
				WHERE id_solicitud=$id_solicitud AND tipo='$tipo';";
			}
			else{
				$tipoSql='insert';
				$sql="INSERT INTO g_dossier_fertilizante.solicitud_anexos (id_solicitud,referencia,path,fase,usuario,tipo)
				VALUES ($id_solicitud,'$referencia','$archivo','$fase','$usuario','$tipo')";
				$sql.=" RETURNING id_solicitud_anexos;";
			}

			$res = $conexion->ejecutarConsulta($sql);
			$items=array();
			while ($fila = pg_fetch_assoc($res)){
				$items[] = $fila;
			}

			$resultado=array();
			$resultado['tipo']=$tipoSql;
			$resultado['resultado']=	$items;
			return $resultado;

		}

		public function eliminarArchivoAnexo($conexion, $id_solicitud_anexos){
			//borrar
			$sql="DELETE FROM g_dossier_fertilizante.solicitud_anexos WHERE id_solicitud_anexos=$id_solicitud_anexos);";
			$res = $conexion->ejecutarConsulta($sql);
			$re=array();
			while ($fila = pg_fetch_assoc($res)){
				$re[] = $fila;
			}

			$ar=array();
			$ar['tipo']='delete';
			$ar['resultado']=	$re;
			return $ar;

		}

		
	//*****************************  FLUJOS  ****************************************************


	public function obtenerFlujosDossier($conexion,$id_tipo){
		$res=array();
		try{
			$query = $conexion->ejecutarConsulta("select fd.*,fo.estado,fp.estado as siguiente from g_dossier_fertilizante.flujos_operaciones fd
				left join g_operadores.flujos_operaciones fo on fd.id_flujo=fo.id_flujo and fd.id_fase=fo.id_fase
				left join g_operadores.flujos_operaciones fp on fd.id_flujo=fp.id_flujo and fd.id_fase_siguiente=fp.id_fase
				where id_tipo=$id_tipo;");
			while ($fila = pg_fetch_assoc($query)){
				$res[] = $fila;
			}
		}catch(Exception $e){}
		return $res;
	}

	public function obtenerFlujoSiguiente($conexion,$id_tipo,$id_fase,$condicion){
		$res=array();
		try{
			$query = $conexion->ejecutarConsulta("select fd.*,fo.estado,fp.estado as siguiente from g_dossier_fertilizante.flujos_operaciones fd
				left join g_operadores.flujos_operaciones fo on fd.id_flujo=fo.id_flujo and fd.id_fase=fo.id_fase
				left join g_operadores.flujos_operaciones fp on fd.id_flujo=fp.id_flujo and fd.id_fase_siguiente=fp.id_fase
				where fd.id_tipo=$id_tipo and fd.id_fase=$id_fase AND id_condicion='$condicion' ;");
			while ($fila = pg_fetch_assoc($query)){
				$res[] = $fila;
			}
		}catch(Exception $e){}
		return $res;
	}



	//****************************** COMUNES *********************************************************

	public function valorCorrecto($valor){
		if($valor==null)
		{
			if($valor==0)
				return "0";
			else
				return "''";
		}
		$sql="";
		switch(gettype($valor)){
			case "string":
				$sql ="'".$valor."'";
				break;
			default:
				$sql .=$valor;
				break;
		}
		return $sql;
	}

	public function normalizarBoolean($valor){
		if($valor=="SI")
			return '1';
		else
			return '0';
	}

   public function obtenerUnidadesMedidaComposicion($conexion){
	   $res=array();
	   try{
	      $sql="select * from g_catalogos.unidades_medidas";	//where clasificacion is null
	      $sql=$sql." order by nombre;";
	      $query = $conexion->ejecutarConsulta($sql);
	      while ($fila = pg_fetch_assoc($query)){
	         $res[] = $fila;
	      }
	   }catch(Exception $e){}

	   return $res;
	}

//********************** TIPOS DE CLASIFICACIONES ******************************

		  public function obtenerClasificaciones($conexion,$id_solicitud){
			  $res=array();

			  $query = $conexion->ejecutarConsulta("select sc.*,c.nombre,c.nombre2 as codigo,sp.nombre as sub_tipo_producto from g_dossier_fertilizante.solicitud_clasificaciones sc
						left join g_catalogos.catalogo_ef_ex c on c.codigo=sc.clasificacion
						left join g_catalogos.subtipo_productos sp on sp.id_subtipo_producto=sc.id_subtipo_producto
						where sc.id_solicitud=$id_solicitud;");
				  while ($fila = pg_fetch_assoc($query)){
					  $res[] = $fila;
				  }


			  return $res;

		  }

		  public function obtenerClasificacion($conexion,$id_solicitud_clasificacion){
			  $res=array();

			  $query = $conexion->ejecutarConsulta("select sc.*,c.nombre,c.nombre2 as codigo,sp.nombre as sub_tipo_producto from g_dossier_fertilizante.solicitud_clasificaciones sc
						left join g_catalogos.catalogo_ef_ex c on c.codigo=sc.clasificacion
						left join g_catalogos.subtipo_productos sp on sp.id_subtipo_producto=sc.id_subtipo_producto
						where sc.id_solicitud_clasificacion=$id_solicitud_clasificacion;");
			  while ($fila = pg_fetch_assoc($query)){
				  $res = $fila;
			  }

			  return $res;

		  }


		  public function agregarClasificacion($conexion,$id_solicitud,$clasificacion,$sub_tipo_producto){
			  $respuesta=array();
			  $id_solicitud_clasificacion=0;
			  $tipo='';
				  //verifico si ya existe
				  $sql="select * from g_dossier_fertilizante.solicitud_clasificaciones WHERE id_solicitud=$id_solicitud AND clasificacion='$clasificacion';";
				  $res = $conexion->ejecutarConsulta($sql);
				  if(pg_num_rows($res)<1){
					  $tipo='insert';
					  $sql="INSERT INTO g_dossier_fertilizante.solicitud_clasificaciones (id_solicitud,clasificacion,id_subtipo_producto)
							VALUES ($id_solicitud,'$clasificacion',$sub_tipo_producto)
							RETURNING id_solicitud_clasificacion;";
				  }
				  else{
					  $tipo='update';
					  $items=pg_fetch_assoc($res,0);
					  $id_solicitud_clasificacion=$items['id_solicitud_clasificacion'];
				  }
				  $res = $conexion->ejecutarConsulta($sql);
				  if($tipo=='insert'){
					  $items=pg_fetch_assoc($res,0);
					  $id_solicitud_clasificacion=$items['id_solicitud_clasificacion'];
				  }
				  $respuesta['tipo']=$tipo;
				  $respuesta['resultado']=	$this->obtenerClasificacion($conexion,$id_solicitud_clasificacion);

				  return $respuesta;
		  }

		  public function eliminarClasificacion($conexion, $id_solicitud_clasificacion){
			  //borrar
			  $sql="DELETE FROM g_dossier_fertilizante.solicitud_clasificaciones WHERE id_solicitud_clasificacion=$id_solicitud_clasificacion;";
			  $res = $conexion->ejecutarConsulta($sql);
			  $re=array();
			  while ($fila = pg_fetch_assoc($res)){
				  $re[] = $fila;
			  }

			  $ar=array();
			  $ar['tipo']='delete';
			  $ar['resultado']=	$re;
			  return $ar;

		  }


		  public function imprimirLineaTipoClasificacion($id_solicitud_clasificacion,$nombre,$codigo=null,$subTipoProducto=null){

			  $fila='<tr id="R' . $id_solicitud_clasificacion . '">' .
			  '<td width="5%">' .
			  $codigo.
			  '</td>' .
			  '<td width="55%">' .
			  $nombre.
			  '</td>' .
			  '<td width="40%">' .
			  $subTipoProducto.
			  '</td>' .
			  '<td>' .
			  '<form class="borrar" data-rutaAplicacion="dossierFertilizante" data-opcion="atenderActualizaciones">' .
			  '<input type="hidden" id="id_solicitud_clasificacion" name="id_solicitud_clasificacion" value="' . $id_solicitud_clasificacion . '" >' .

			  '<input type="hidden" id="opcionActualizar" name="opcionActualizar" value="eliminarTipoClasificacion" />'.

			  '<button type="submit" class="icono verTipoClasificacion"></button>' .
			  '</form>' .
			  '</td>' .
			  '</tr>';


			  return $fila;
		  }

		  //********************** CULTIVOS ******************************

		  public function obtenerCultivos($conexion,$id_solicitud){
			  $res=array();

			  $query = $conexion->ejecutarConsulta("select sc.*,c.nombre_cientifico,c.nombre_comun from g_dossier_fertilizante.solicitud_cultivos sc
						left join g_catalogos.productos c on c.id_producto=sc.id_cultivo
						where sc.id_solicitud=$id_solicitud;");
			  while ($fila = pg_fetch_assoc($query)){
				  $res[] = $fila;
			  }


			  return $res;

		  }

		  public function obtenerCultivo($conexion,$id_solicitud_cultivo){
			  $res=array();

			  $query = $conexion->ejecutarConsulta("select sc.*,c.nombre_cientifico,c.nombre_comun from g_dossier_fertilizante.solicitud_cultivos sc
						left join g_catalogos.productos c on c.id_producto=sc.id_cultivo
						where sc.id_solicitud_cultivo=$id_solicitud_cultivo;");
			  while ($fila = pg_fetch_assoc($query)){
				  $res = $fila;
			  }

			  return $res;

		  }


		  public function agregarCultivo($conexion,$id_solicitud,$id_cultivo){
			  $respuesta=array();
			  $id_solicitud_cultivo=0;
			  $tipo='';
			  //verifico si ya existe
			  $sql="select * from g_dossier_fertilizante.solicitud_cultivos WHERE id_solicitud=$id_solicitud AND id_cultivo=$id_cultivo;";
			  $res = $conexion->ejecutarConsulta($sql);
			  if(pg_num_rows($res)<1){
				  $tipo='insert';
				  $sql="INSERT INTO g_dossier_fertilizante.solicitud_cultivos (id_solicitud,id_cultivo)
							VALUES ($id_solicitud,$id_cultivo)
							RETURNING id_solicitud_cultivo;";
			  }
			  else{
				  $tipo='update';
				  $items=pg_fetch_assoc($res,0);
				  $id_solicitud_cultivo=$items['id_solicitud_cultivo'];
			  }
			  $res = $conexion->ejecutarConsulta($sql);
			  if($tipo=='insert'){
				  $items=pg_fetch_assoc($res,0);
				  $id_solicitud_cultivo=$items['id_solicitud_cultivo'];
			  }
			  $respuesta['tipo']=$tipo;
			  $respuesta['resultado']=	$this->obtenerCultivo($conexion,$id_solicitud_cultivo);

			  return $respuesta;
		  }

		  public function eliminarCultivo($conexion, $id_solicitud_cultivo){
			  //borrar
			  $sql="DELETE FROM g_dossier_fertilizante.solicitud_cultivos WHERE id_solicitud_cultivo=$id_solicitud_cultivo;";
			  $res = $conexion->ejecutarConsulta($sql);
			  $re=array();
			  while ($fila = pg_fetch_assoc($res)){
				  $re[] = $fila;
			  }

			  $ar=array();
			  $ar['tipo']='delete';
			  $ar['resultado']=	$re;
			  return $ar;

		  }

		  public function imprimirLineaCultivo($id_solicitud_cultivo,$nombre){

			  $fila='<tr id="R' . $id_solicitud_cultivo . '">' .
			  '<td width="100%">' .
			  $nombre.
			  '</td>' .

			  '<td>' .
			  '<form class="borrar" data-rutaAplicacion="dossierFertilizante" data-opcion="atenderActualizaciones">' .
			  '<input type="hidden" id="id_solicitud_cultivo" name="id_solicitud_cultivo" value="' . $id_solicitud_cultivo . '" >' .

			  '<input type="hidden" id="opcionActualizar" name="opcionActualizar" value="eliminarCultivo" />'.

			  '<button type="submit" class="icono verObsCultivos"></button>' .
			  '</form>' .
			  '</td>' .
			  '</tr>';


			  return $fila;
		  }

//*************************************** FLUJOS ********************************************************
		  public function obtenerFlujosDeTramitesSolicitudDF($conexion,$identificador,$id_fase,$idSolicitud,$esta_procesado='N'){
				$sql="select t.id_tramite,t.tipo_documento,t.id_documento,t.id_division,p.identificador as operador,p.estado,o.razon_social,p.producto_nombre,p.producto_nombre as nombre,p.id_expediente,p.id_certificado,tf.*
				from g_ensayo_eficacia.tramites_flujos tf
				left join g_ensayo_eficacia.tramites t on tf.id_tramite=t.id_tramite
				left join g_dossier_fertilizante.solicitudes p on t.id_documento=p.id_solicitud
				left join g_operadores.operadores o on p.identificador=o.identificador
				left join g_ensayo_eficacia.flujo_documentos fd on tf.id_flujo_documento=fd.id_flujo_documento
				WHERE t.tipo_documento='DF' AND fd.id_fase=$id_fase AND tf.identificador ='$identificador' AND tf.pendiente!='$esta_procesado' AND p.id_solicitud=$idSolicitud;";
				return $conexion->ejecutarConsulta($sql);

			}

		  public function obtenerFlujosDeTramitesAsignarDossierDF($conexion,$identificador=null,$id_fase,$estadoSolicitud,$perfil=null,$esta_procesado='N'){

			  $sql="select t.tipo_documento,t.id_documento,t.id_division,p.identificador as operador,p.estado,o.razon_social,p.producto_nombre,p.producto_nombre as nombre,p.id_expediente,tf.*
			from g_ensayo_eficacia.tramites_flujos tf
			left join g_ensayo_eficacia.tramites t on tf.id_tramite=t.id_tramite
			left join g_dossier_fertilizante.solicitudes p on t.id_documento=p.id_solicitud
			left join g_operadores.operadores o on p.identificador=o.identificador
			left join g_ensayo_eficacia.flujo_documentos fd on tf.id_flujo_documento=fd.id_flujo_documento
			WHERE t.tipo_documento='DF' AND fd.id_fase=$id_fase  AND tf.pendiente!='$esta_procesado' ";

			  if($identificador!=null)
				  $sql=$sql."  AND tf.identificador='$identificador' ";
			  if($perfil!=null)
				  $sql=$sql."  AND tf.identificador='$perfil' ";

			  $sql=$sql.";";
			  return $conexion->ejecutarConsulta($sql);

		  }

		  public function obtenerFlujosDeTramitesParaAsingnarDF($conexion,$identificador,$id_fase,$esta_procesado='N'){

			  $sql="select t.tipo_documento,t.id_documento,t.id_division,p.identificador as operador,p.estado,o.razon_social,p.producto_nombre,p.producto_nombre as nombre,p.id_expediente,tf.*
			from g_ensayo_eficacia.tramites_flujos tf
			left join g_ensayo_eficacia.tramites t on tf.id_tramite=t.id_tramite
			left join g_dossier_fertilizante.solicitudes p on t.id_documento=p.id_solicitud
			left join g_operadores.operadores o on p.identificador=o.identificador
			left join g_ensayo_eficacia.flujo_documentos fd on tf.id_flujo_documento=fd.id_flujo_documento

				inner join g_estructura.funcionarios fr on tf.identificador=fr.identificador
			WHERE fr.activo=1 AND fr.identificador='$identificador' AND  fd.id_fase=$id_fase  AND tf.pendiente!='$esta_procesado';";
			  return $conexion->ejecutarConsulta($sql);
		  }

		  public function obtenerTramiteFlujoDF($conexion,$id_tramite_flujo){
			  $sql="select tf.*,p.identificador as operador from g_ensayo_eficacia.tramites_flujos tf
			left join g_ensayo_eficacia.tramites t on t.id_tramite=tf.id_tramite
			left join g_dossier_fertilizante.solicitudes p on t.id_documento=p.id_solicitud
			WHERE id_tramite_flujo=$id_tramite_flujo;";
			  $res=array();
			  $query = $conexion->ejecutarConsulta($sql);
			  while ($fila = pg_fetch_assoc($query)){
				  $res[] = $fila;
			  }
			  if(sizeof($res)>0)
				  $res=$res[0];

			  return $res;
		  }


		  public function obtenerFlujosDeTramitesDelOperadorDF($conexion,$identificador,$id_fase,$esta_procesado='N'){
			  $sql="select t.tipo_documento,t.id_documento,t.id_division,p.identificador as operador,p.estado,o.razon_social,p.producto_nombre,p.producto_nombre as nombre,p.id_expediente,tf.*
				from g_ensayo_eficacia.tramites_flujos tf
				left join g_ensayo_eficacia.tramites t on tf.id_tramite=t.id_tramite
				left join g_dossier_fertilizante.solicitudes p on t.id_documento=p.id_solicitud
				left join g_operadores.operadores o on p.identificador=o.identificador
				left join g_ensayo_eficacia.flujo_documentos fd on tf.id_flujo_documento=fd.id_flujo_documento
				WHERE t.tipo_documento='DF' and fd.id_fase=$id_fase  AND tf.identificador ='$identificador' AND tf.pendiente='$esta_procesado';";
			  return $conexion->ejecutarConsulta($sql);

		  }



//********************************* NOTIFICACIONES *****************************************************

		  public function redactarNotificacionEmailPF($conexion,$id_tramite, $fecha,$asunto){
			  $sql="select t.*,p.identificador as operador,p.producto_nombre,p.id_expediente,o.correo, o.razon_social,o.nombre_representante,o.apellido_representante
			from g_ensayo_eficacia.tramites t
			left join g_dossier_fertilizante.solicitudes p on t.id_documento=p.id_solicitud
			left join g_operadores.operadores o on p.identificador=o.identificador
			WHERE id_tramite=$id_tramite;";
			  $res=array();
			  $query = $conexion->ejecutarConsulta($sql);
			  while ($fila = pg_fetch_assoc($query)){
				  $res[] = $fila;
			  }
			  if(sizeof($res)>0)
				  $res=$res[0];

			  $s='<h2>Sistema GUIA</h2>';
			  $s.='<p/>';
			  $s.='<p/>';
			  $s.='Estimado(a) Cliente: <br/><br/>';
			  $s.='<p/>';
			  $s.='Sr(a). <i>'. $res['nombre_representante'].' '.$res['apellido_representante'].'</i>' ;
			  $s.='<p/>';
			  $s.='Representante Legal de <i>'.$res['razon_social'].'</i>';
			  $s.='<p/>';
			  $s.='Ha recibido una actualización a su solicitud en el sistema, por favor revise el estado de la misma en  <a href="https://guia.agrocalidad.gob.ec/agrodb/ingreso.php">https://guia.agrocalidad.gob.ec/agrodb/ingreso.php</a>';
			  $s.='<p/>';
			  $s.='Información de la solicitud:';
			  $s.='<p/>';
			  $s.='Fecha:'.$fecha;
			  $s.='<br/>';
			  $s.='No. de Solicitud:'.$res['id_expediente'];
			  $s.='<br/>';
			  $s.='Asuno:'.$asunto.' - '.$res['producto_nombre'];
			  $s.='<p/>';
			  $s.='Saludos cordiales,';
			  $s.='<p/>';
			  $s.='Soporte GUIA.';
			  $s.='<p/>';
			  $s.='Nota: Este mensaje fue enviado automáticamente por el sistema, por favor no lo responda.';
			  $retornar=array();
			  $retornar['datos']=$res;
			  $retornar['mensaje']=$s;
			  return $retornar;

		  }


//******************************** ETIQUETATAS ************************************


		  public function guardarEtiqueta($conexion,$datos){
			  $sql="";
			  $tieneItems=false;
			  $valorChequeado="";
			  $tipo="";
			  $estaCreada=$this->obtenerEtiquetaSolicitud($conexion,$datos['id_solicitud']);

			  
			  if($estaCreada!=null)
			  {
				  //actualizar
				  $tipo="update";
				  $iden=$datos['id_solicitud'];
				  $sql="UPDATE  g_dossier_fertilizante.etiquetas
				set ";
				  foreach ($datos as $id => $valor)
				  {
					  if($id=='id_solicitud')
						  continue;

					  if($tieneItems)
						  $sql .=",".$id;
					  else{
						  $sql .=$id;
						  $tieneItems=true;
					  }
					  $valorChequeado=$this->valorCorrecto($valor);
					  $sql.="=".$valorChequeado;


				  }
				  $sql.="	WHERE
						id_solicitud=$iden;";
			  }
			  else
			  {
				  //insertar
				  $tipo="insert";
				  $sql="INSERT INTO g_dossier_fertilizante.etiquetas(";
				  $sqlValues=") VALUES (";
				  foreach ($datos as $id => $valor) {
					  $valorChequeado=$this->valorCorrecto($valor);
					  if($tieneItems){
						  $sql .=",".$id;
						  $sqlValues.=",".$valorChequeado;
					  }
					  else{
						  $tieneItems=true;
						  $sql .=$id;
						  $sqlValues.=$valorChequeado;
					  }

				  }

				  $sql.=$sqlValues.")";
				  $sql.=" RETURNING id_solicitud;";
			  }

			  $res = $conexion->ejecutarConsulta($sql);
			  $filasTemp=array();
			  while ($fila = pg_fetch_assoc($res)){
				  $filasTemp[] = $fila;
			  }

			  $resultado=array();
			  $resultado['tipo']=$tipo;
			  $resultado['resultado']=	$filasTemp;
			  return $resultado;
		  }

		  public function obtenerEtiquetaSolicitud ($conexion, $idSolicitud){
			  $query = $conexion->ejecutarConsulta("select
												pr.*
											from
												g_dossier_fertilizante.etiquetas pr
											where
												pr.id_solicitud = $idSolicitud;");

			  return pg_fetch_assoc($query);
		  }

		  public function eliminarEtiquetaSolicitud($conexion, $idSolicitud){
			  //borrar
			  $sql="DELETE FROM g_dossier_fertilizante.etiquetas WHERE id_solicitud in ($idSolicitud);";
			  $res = $conexion->ejecutarConsulta($sql);
			  $filasTemp=array();
			  while ($fila = pg_fetch_assoc($res)){
				  $filasTemp[] = $fila;
			  }

			  $resultado=array();
			  $resultado['tipo']='delete';
			  $resultado['resultado']=	$filasTemp;
			  return $resultado;

		  }

		  public function obtenerSolicitudEtiquetaXevaluar($conexion,$identificador,$id_fase,$esta_procesado='T',$estadoEtiqueta='aprobarEtiqueta'){

			  $sql="select t.tipo_documento,t.id_documento,t.id_division,p.identificador as operador,p.estado,o.razon_social,p.producto_nombre,p.producto_nombre as nombre,p.id_expediente,tf.*,
			e.ruta,p.mae_comentario,p.mae_ruta,p.salud_comentario,p.salud_ruta
			from g_ensayo_eficacia.tramites_flujos tf
			left join g_ensayo_eficacia.tramites t on tf.id_tramite=t.id_tramite
			left join g_dossier_fertilizante.solicitudes p on t.id_documento=p.id_solicitud
			left join g_operadores.operadores o on p.identificador=o.identificador
			left join g_ensayo_eficacia.flujo_documentos fd on tf.id_flujo_documento=fd.id_flujo_documento

			inner join g_estructura.funcionarios fr on tf.ejecutor=fr.identificador
			inner join g_dossier_fertilizante.etiquetas e on e.id_solicitud=p.id_solicitud
			WHERE t.tipo_documento='DF' and fr.activo=1 AND fr.identificador='$identificador' AND  fd.id_fase=$id_fase  AND tf.pendiente='$esta_procesado' and e.estado='$estadoEtiqueta';";
			  return $conexion->ejecutarConsulta($sql);
		  }

		  public function obtenerSolicitudParaOrganismosExternos($conexion,$noStatus='A',$noPendiente='N'){

			  $sql="select t.tipo_documento,t.id_documento,t.id_division,p.identificador as operador,p.estado,o.razon_social,p.producto_nombre,p.producto_nombre as nombre,p.id_expediente,tf.*,
			p.mae_comentario,p.mae_ruta,p.mae_estado,p.salud_comentario,p.salud_ruta,p.salud_estado
			from g_ensayo_eficacia.tramites_flujos tf
			left join g_ensayo_eficacia.tramites t on tf.id_tramite=t.id_tramite
			left join g_dossier_fertilizante.solicitudes p on t.id_documento=p.id_solicitud
			left join g_operadores.operadores o on p.identificador=o.identificador
			left join g_ensayo_eficacia.flujo_documentos fd on tf.id_flujo_documento=fd.id_flujo_documento
			WHERE t.tipo_documento='DF'   AND  fd.id_fase between 4 and 9  AND t.status!='$noStatus' and tf.pendiente!='$noPendiente' ;";
			  return $conexion->ejecutarConsulta($sql);
		  }


	//********************************* REPORTES **********************************************

	public function obtenerRegistrosFertilizantes($conexion,$fechaDesde=null,$fechaHasta=null){
		$sql="select p.id_solicitud,p.identificador,p.id_expediente,p.id_certificado,p.estado,p.producto_nombre as nombre_producto,
			o.razon_social,'' as sitio,cef.nombre as provincia,'' as subtipo_producto,p.fecha_solicitud as fecha_inicio,p.fecha_inscripcion as fecha_registro
			from g_dossier_fertilizante.solicitudes p
			left join g_operadores.operadores o on p.identificador=o.identificador			
			left join g_ensayo_eficacia.tramites t on t.id_documento=p.id_solicitud and t.tipo_documento='DF'
			left join g_catalogos.catalogo_ef cef on cef.codigo=t.id_division and cef.clase='DIVISION'
			order by p.fecha_solicitud;";

		return $conexion->ejecutarConsulta($sql);
		
	}
	
	public function obtenerMatrizServicioFertilizantes($conexion,$fechaDesde=null,$fechaHasta=null){
		 
		$sql="select to_char(100*(2-(ss.tiempo/ss.plazo)), '9999') as eficiencia, cast(ss.tiempo as integer) as tiempo_real, ss.* from (
				select distinct
            tf.id_tramite_flujo,tf.identificador,tf.remitente,tf.ejecutor,tf.decision,p.identificador as operador,tf.observacion,
            p.id_solicitud,p.id_expediente,p.id_certificado,p.estado,p.producto_nombre as nombre_producto, p.fecha_solicitud,
			
			o.razon_social,'' as provincia,'' as subtipo_producto,'' as codificacion_subtipo_producto,tf.fecha_inicio , tf.fecha_fin,
			ce.nombre,tf.id_tramite,tf.id_flujo_documento,tf.identificador as tecnico,tf.fecha_inicio as fecha_tecnico,
			cast((select count(id_tramite_observacion) from g_ensayo_eficacia.tramites_observaciones where id_tramite_flujo=tf.id_tramite_flujo) as integer) as numero_observaciones,
			
			EXTRACT(DAY FROM age(date(tf.fecha_fin),date(tf.fecha_inicio) )) as tiempo,
			fd.plazo as plazos,plazo_n,plazo_condicion,plazo_a,case when tf.plazo =0 then (case when fd.plazo=0 then 1 else fd.plazo end) else tf.plazo end ,tf.retraso,(fe.nombre || fe.apellido) as nombres_tecnico
			,tf.perfil_identificador,(fed.nombre || fed.apellido) as nombres_evaluador
			from g_dossier_fertilizante.solicitudes p
			left join g_operadores.operadores o on p.identificador=o.identificador
			
			
			left join g_catalogos.catalogo_ef ce on ce.codigo=p.objetivo
			left join g_ensayo_eficacia.tramites tt on tt.id_documento=p.id_solicitud and tt.tipo_documento='DF'
			left join g_ensayo_eficacia.tramites_flujos tf on tf.id_tramite=tt.id_tramite
			left join g_ensayo_eficacia.flujo_documentos fd on fd.id_flujo_documento=tf.id_flujo_documento
			left join g_ensayo_eficacia.flujo_fases ff on ff.id_flujo=fd.id_flujo and ff.id_fase=fd.id_fase
			left join g_uath.ficha_empleado fe on fe.identificador=tf.identificador
			left join g_uath.ficha_empleado fed on fed.identificador=tf.perfil_identificador
			where ff.estado not in ('solicitud') and fd.tipo_documento='DG' 

			order by tf.id_tramite_flujo) as ss;";
	    
	    return $conexion->ejecutarConsulta($sql);
	    
	}
	
	 //INICIO EJAR
		  
	  public function listarSolicitudesPorEstadoProvincia ($conexion, $estado, $provincia){
		  
		  $res = $conexion->ejecutarConsulta("select
												id_solicitud,
												id_expediente as numero_solicitud,
												identificador as identificador_operador,
												fecha_solicitud as fecha_registro,
												estado
											from
												g_dossier_fertilizante.solicitudes
											where
												estado = '$estado';");
		  return $res;
		  
	  }
		  
	//FIN EJAR
	
	
	//Fin controlador
}