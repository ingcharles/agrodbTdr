<?php

require_once 'Constantes.php';

class ControladorEnsayoEficacia{

	
	//****************************** OPERACIONES DEL OPERADOR *********************************


	public function testAccesoPermitido($conexion,$identificador,$id_area,$claseOperacionesHabilitadas){

		$operacionesValidas = $this->listarElementosCatalogo($conexion,$claseOperacionesHabilitadas);
		$operacionesDelOperador=$this->obtenerOperacionesDelOperador ($conexion,$identificador,$id_area);
		$testOperacion=false;
		foreach($operacionesDelOperador as $fila){
			foreach($operacionesValidas as $valor){
				if($fila['codigo']==$valor['nombre']){
					$testOperacion=true;
					break;
				}

			}
		}
		return $testOperacion;
	}

	public function obtenerOperacionesDelOperador ($conexion,$identificador,$id_area,$estado='registrado'){
		$res=array();
		$cid = $conexion->ejecutarConsulta("select DISTINCT op.identificador,op.razon_social, op.direccion,op.nombre_representante,
				op.apellido_representante,op.correo,op.telefono_uno  as telefono
				,tos.id_tipo_operacion,tos.nombre as operacion,tos.codigo
				from g_operadores.operadores op
				inner join g_operadores.operaciones os on os.identificador_operador=op.identificador
				inner join g_catalogos.tipos_operacion tos on os.id_tipo_operacion=tos.id_tipo_operacion
				where tos.estado=1 AND os.estado='$estado' AND op.identificador='$identificador' AND  tos.id_area='$id_area';");
		while ($fila = pg_fetch_assoc($cid)){
			$res[] = $fila;
		}
		return $res;

	}

	

	public function obtenerOperadoresConOperacionesEnEstado ($conexion,$idAreas,$inCodigosTiposOperaciones,$inEstadosOperaciones){
		$res=array();
		$sql="select DISTINCT op.identificador,op.razon_social
				from g_operadores.operadores op
				inner join g_operadores.operaciones os on os.identificador_operador=op.identificador
				inner join g_catalogos.tipos_operacion tos on os.id_tipo_operacion=tos.id_tipo_operacion
				where tos.estado=1  AND tos.id_area in ('$idAreas') and tos.codigo $inCodigosTiposOperaciones and	os.estado $inEstadosOperaciones;";
		$cid = $conexion->ejecutarConsulta($sql);
		while ($fila = pg_fetch_assoc($cid)){
			$res[] = $fila;
		}
		return $res;

	}
	

	//Recupera todos los protocolos por operador
	public function listarProtocolosOperador ($conexion, $identificador){
		$sql="select   pr.id_protocolo,pr.plaguicida_nombre,pr.fecha_solicitud,pr.estado,
			(select tf.fecha_inicio from g_ensayo_eficacia.tramites t left join g_ensayo_eficacia.tramites_flujos tf on tf.id_tramite=t.id_tramite 
			where t.id_documento=pr.id_protocolo and t.tipo_documento='EP' and (tf.pendiente is null or tf.pendiente!='N') order by tf.id_tramite_flujo DESC LIMIT 1) ,
			(select tf.fecha_fin from g_ensayo_eficacia.tramites t left join g_ensayo_eficacia.tramites_flujos tf on tf.id_tramite=t.id_tramite 
			where t.id_documento=pr.id_protocolo and t.tipo_documento='EP' and (tf.pendiente is null or tf.pendiente!='N') order by tf.id_tramite_flujo DESC LIMIT 1) 
			from g_ensayo_eficacia.protocolos pr where pr.identificador = '$identificador';";
				
		$query = $conexion->ejecutarConsulta($sql);
		return $query;
	}

	//************************ manejo de perfiles ********************************************************
	public function obtenerPerfiles($conexion,$identificador){
		$query = $conexion->ejecutarConsulta("SELECT
											  perfiles.*
											FROM
											  g_usuario.perfiles,
											  g_usuario.usuarios_perfiles
											WHERE
											  perfiles.id_perfil = usuarios_perfiles.id_perfil AND
											  usuarios_perfiles.identificador='".$identificador."'");

		$res=array();

		while ($fila = pg_fetch_assoc($query)){
			$res[] = $fila;
		}
		return $res;
	}


	public function obtenerOperadoresPorPerfil($conexion,$codificacion_perfil,$estado=null,$idAplicacion=null){
		$sql="SELECT  o.* FROM g_operadores.operadores o inner join g_usuario.usuarios_perfiles up on o.identificador=up.identificador
			inner join g_usuario.perfiles p on p.id_perfil=up.id_perfil
			WHERE p.codificacion_perfil='$codificacion_perfil'";
		if($idAplicacion!=null){
			$sql=$sql." AND p.id_aplicacion=$idAplicacion";
		}
		if($estado!=null){
			$sql=$sql." AND p.estado=$estado";
		}
		$sql=$sql.";";

		$query = $conexion->ejecutarConsulta($sql);
		

		$res=array();

		while ($fila = pg_fetch_assoc($query)){
			$res[] = $fila;
		}
		return $res;
	}

	//Recupera protocolo según id
	public function obtenerProtocolo ($conexion, $idProtocolo){
		$query = $conexion->ejecutarConsulta("select pr.*,tc.nombres as tecnico_reconocido, c.nombre as uso_propuesto from g_ensayo_eficacia.protocolos pr
			left join g_ensayo_eficacia.tecnicos_reconocidos tc on pr.ci_tecnico_reconocido=tc.identificador
			left join g_catalogos.subtipo_productos c on c.codificacion_subtipo_producto=pr.uso
			where pr.id_protocolo = $idProtocolo;");

		return pg_fetch_assoc($query,0);
	}

	public function obtenerProtocoloDesdeExpediente ($conexion, $id_expediente){
		$query = $conexion->ejecutarConsulta("select pr.*,tc.nombres as tecnico_reconocido, c.nombre as uso_propuesto from g_ensayo_eficacia.protocolos pr
			left join g_ensayo_eficacia.tecnicos_reconocidos tc on pr.ci_tecnico_reconocido=tc.identificador
			left join g_catalogos.subtipo_productos c on c.codificacion_subtipo_producto=pr.uso
			where pr.id_expediente = '$id_expediente';");

		return pg_fetch_assoc($query);
	}


	public function obtenerProtocoloZonas ($conexion, $idProtocolo){
		$query = $conexion->ejecutarConsulta("select	pr.*,l.nombre as provincia_nombre,c.nombre as canton_nombre,p.nombre as parroquia_nombre from g_ensayo_eficacia.protocolo_zonas pr
			left join g_catalogos.localizacion l on pr.provincia=l.id_localizacion
			left join g_catalogos.localizacion c on pr.canton=c.id_localizacion
			left join g_catalogos.localizacion p on pr.parroquia=p.id_localizacion
			where pr.id_protocolo = $idProtocolo and provincia>0;");

		$res=array();

		while ($fila = pg_fetch_assoc($query)){
			$res[] = $fila;
		}
		return $res;
	}

	public function obtenerProtocoloZonasNoAsignado($conexion, $idProtocolo){
		$zonas=$this->obtenerProtocoloZonas($conexion, $idProtocolo);
		$sin_asignar=array();
		foreach($zonas as $key=>$value){
			$id_protocolo_zona=$value['id_protocolo_zona'];
			$query=$conexion->ejecutarConsulta("select * from g_ensayo_eficacia.tramites t where t.id_documento=$id_protocolo_zona AND  t.tipo_documento='IF';");
			if(pg_num_rows($query)<=0)
				$sin_asignar[]=$value;
		}
		return $sin_asignar;
	}

	public function obtenerProtocoloDesdeInformes ($conexion, $id_protocolo_zona){
		$query = $conexion->ejecutarConsulta("select pr.* from g_ensayo_eficacia.informes t
			inner join g_ensayo_eficacia.protocolo_zonas pz on pz.id_protocolo_zona=t.id_protocolo_zona
			inner join g_ensayo_eficacia.protocolos pr on pr.id_protocolo=pz.id_protocolo
			where t.id_protocolo_zona = $id_protocolo_zona;");

		return pg_fetch_assoc($query);
	}

	public function obtenerProtocoloDesdeInforme ($conexion, $id_informe){
		$query = $conexion->ejecutarConsulta("select pr.*,tc.nombres as tecnico_reconocido, c.nombre as uso_propuesto from g_ensayo_eficacia.tramites t
			inner join g_ensayo_eficacia.informes inf on t.id_documento=inf.id_informe and t.tipo_documento='IF'
			inner join g_ensayo_eficacia.protocolo_zonas pz on pz.id_protocolo_zona=inf.id_protocolo_zona
			inner join g_ensayo_eficacia.protocolos pr on pr.id_protocolo=pz.id_protocolo

			left join g_ensayo_eficacia.tecnicos_reconocidos tc on pr.ci_tecnico_reconocido=tc.identificador
			left join g_catalogos.subtipo_productos c on c.codificacion_subtipo_producto=pr.uso

			where t.id_documento = $id_informe;");

		return pg_fetch_assoc($query);
	}


	//*******************************************  CATALOGOS **************************************************************************


	/**
	 * Recupera los elementos de la tabla g_catalogos.catalogo_ef [codigo,nombre] según la clase
	 * @param mixed $conexion
	 * @param mixed La clase del catálogo
	 * @return array[] con los elementos del catálogo
	 */
	public function listarElementosCatalogo ($conexion,$clase){
		$res=array();
		$cid = $conexion->ejecutarConsulta("select
												*
											from
												g_catalogos.catalogo_ef
											where
												clase = '$clase';");

		while ($fila = pg_fetch_assoc($cid)){
			$res[] = $fila;
		}
		return $res;

	}

	public function obtenerItemDelCatalogo ($conexion,$clase,$codigo){
		$res=array();
		$res = $conexion->ejecutarConsulta("select * from g_catalogos.catalogo_ef where clase = '$clase' AND codigo='$codigo';");

		return pg_fetch_assoc($res,0);

	}

	public function guardarItemDelCatalogo ($conexion,$clase,$codigo,$nombre){
		$res=array();
		$res = $conexion->ejecutarConsulta("select * from g_catalogos.catalogo_ef where clase = '$clase' AND codigo='$codigo';");
		$sql='';
		$tipo='';
		if(pg_num_rows($res)>0){
			$tipo="update";
			$sql="UPDATE g_catalogos.catalogo_ef   SET  nombre='$nombre'
				WHERE clase='$clase' AND codigo='$codigo';";
		}
		else{
			$tipo="insert";
			$sql="INSERT INTO g_catalogos.catalogo_ef(codigo, clase, nombre)
				VALUES ('$codigo', '$clase', '$nombre');";
		}

		$res = $conexion->ejecutarConsulta($sql);
		$resultado=array();
		$resultado['tipo']=$tipo;
		$resultado['resultado']=	$codigo;
		return $resultado;

	}

	public function eliminarItemDelCatalogo($conexion,$codigo){
		$sql="DELETE FROM g_catalogos.catalogo_ef WHERE codigo = '$codigo';";

		$resultado = $conexion->ejecutarConsulta($sql);
		$filas = pg_affected_rows($resultado);
		if($filas>0)
			return true;
		else
			return false;

	}


	//Recupera los elementos de la tabla g_catalogos.catalogo_ef_ex [codigo,nombre,nombre2,nombre3] según la clase
	public function listarElementosCatalogoEx ($conexion,$clase){
		$res=array();
		$cid = $conexion->ejecutarConsulta("select
												*
											from
												g_catalogos.catalogo_ef_ex
											where
												clase = '$clase';");

		while ($fila = pg_fetch_assoc($cid)){
			$res[] = array('codigo'=>$fila['codigo'],'nombre'=>$fila['nombre'],'nombre2'=>$fila['nombre2'],'nombre3'=>$fila['nombre3']);
		}
		return $res;

	}

	public function obtenerItemDelCatalogoEx ($conexion,$clase,$codigo){
		$res=array();
		$res = $conexion->ejecutarConsulta("select * from g_catalogos.catalogo_ef_ex where clase = '$clase' AND codigo='$codigo';");
		return pg_fetch_assoc($res,0);
	}

	public function guardarItemDelCatalogoEx ($conexion,$clase,$codigo,$nombre,$nombre2,$nombre3){
		$res=array();
		$res = $conexion->ejecutarConsulta("select * from g_catalogos.catalogo_ef_ex where clase = '$clase' AND codigo='$codigo';");
		$sql='';
		if(pg_num_rows($res)>0){
			$sql="UPDATE g_catalogos.catalogo_ef_ex   SET  nombre='$nombre', nombre2='$nombre2', nombre3='$nombre3'
				WHERE clase='$clase' AND codigo='$codigo';";
		}
		else{
			$sql="INSERT INTO g_catalogos.catalogo_ef_ex(codigo, clase, nombre,nombre2,nombre3)
				VALUES ('$codigo', '$clase', '$nombre', '$nombre2', '$nombre3');";
		}

		$res = $conexion->ejecutarConsulta($sql);
		return $res;
	}

	public function eliminarItemDelCatalogoEx($conexion,$codigo){
		$sql="DELETE FROM g_catalogos.catalogo_ef_ex WHERE codigo = '$codigo';";

		$resultado = $conexion->ejecutarConsulta($sql);
		$filas = pg_affected_rows($resultado);
		if($filas>0)
			return true;
		else
			return false;

	}

	public function obtenerFormulacionActual($conexion,$idFormulacion){
		$sql="select * from g_catalogos.formulacion where id_formulacion=$idFormulacion;";
		$res = $conexion->ejecutarConsulta($sql);
		return $res;
	}

	public function obtenerCategoriaToxicologica($conexion,$idCategoriaToxicologica){
		$sql="select * from g_catalogos.categoria_toxicologica where id_categoria_toxicologica=$idCategoriaToxicologica;";
		$res = $conexion->ejecutarConsulta($sql);
		return $res;
	}

	//***************************************INGREDIENTE ACTIVO *************************

	public function obtenerIA ($conexion,$area=null,$tipo=null){
		$res=array();
		$conArea="";
		if($area!=null)
			$conArea="ia.id_area='".$area."'";
		$where="";

		if($conArea=="" && $where=="")
			$where='';
		else if($conArea!="" && $where!="")
			$where="where ".$where." and ".$conArea;
		else
			$where="where ".$where.$conArea;

		$query = $conexion->ejecutarConsulta("
			select ia.id_ingrediente_activo, ia.ingrediente_activo,ia.grupo_quimico,ia.ingrediente_quimico,ia.formula_quimica
			from g_catalogos.ingrediente_activo_inocuidad ia ".$where." order by ia.ingrediente_activo;"

		);
		while ($fila = pg_fetch_assoc($query)){
			$res[] = array('codigo'=>$fila['id_ingrediente_activo'],'nombre'=>$fila['ingrediente_activo'],'quimico'=>$fila['grupo_quimico'],'nombre_quimico'=>$fila['ingrediente_quimico'],'formula_quimica'=>$fila['formula_quimica'] );
		}
		return $res;
	}


	//Retorna lista de los tecnicos reconocidos por la ANC
	public function obtenerTecnicosReconocidos ($conexion){
		$res=array();

		$query = $conexion->ejecutarConsulta("
			select * from g_ensayo_eficacia.tecnicos_reconocidos
		");
		while ($fila = pg_fetch_assoc($query)){
			$res[] = array('identificador'=>$fila['identificador'],'nombres'=>$fila['nombres'],'empresa'=>$fila['empresa']);
		}
		return $res;

	}

	//*****************************  TIPOS Y SUBTIPOS DE PRODUCTOS *************************************

	public function obtenerSubTipoXregristo($conexion,$numeroRegistro,$likeCodigoSubTipoProducto='RIA-%'){
		$sql="select pr.id_subtipo_producto,pr.nombre,p.partida_arancelaria,pr.codificacion_subtipo_producto,p.ruta,p.unidad_medida,p.id_producto	from g_catalogos.productos p
			inner join g_catalogos.productos_inocuidad pi on p.id_producto=pi.id_producto
			inner join g_catalogos.subtipo_productos pr on p.id_subtipo_producto=pr.id_subtipo_producto
			inner join g_catalogos.tipo_productos tp on pr.id_tipo_producto=tp.id_tipo_producto
			where pr.estado=1 and tp.estado=1 AND pi.numero_registro='$numeroRegistro'";
		if($likeCodigoSubTipoProducto!=null){
			$sql=$sql." and pr.codificacion_subtipo_producto like '$likeCodigoSubTipoProducto'";
		}
		$sql=$sql." order by pr.nombre;";
		$query=$conexion->ejecutarConsulta($sql);
		return pg_fetch_assoc($query);
	}

	public function obtenerSubTipoXprotocolo($conexion,$registroProtocolo,$likeCodigoSubTipoProducto='RIA-%'){
		$sql="select pr.id_subtipo_producto,pr.nombre	from g_ensayo_eficacia.protocolos p
			inner join g_catalogos.subtipo_productos pr on p.uso=pr.codificacion_subtipo_producto
			inner join g_catalogos.tipo_productos tp on pr.id_tipo_producto=tp.id_tipo_producto
			where pr.estado=1 and tp.estado=1 AND p.id_expediente='$registroProtocolo' and pr.codificacion_subtipo_producto like '$likeCodigoSubTipoProducto' order by pr.nombre;";
		$query=$conexion->ejecutarConsulta($sql);
		return pg_fetch_assoc($query);
	}

	public function obtenerSubTipoProductoDeclarado($conexion,$idSubTipoProducto,$likeSubtipo='RIA-%'){
		$sql="select	pr.*	from g_catalogos.subtipo_productos pr
			inner join g_catalogos.tipo_productos tp on pr.id_tipo_producto=tp.id_tipo_producto
			where pr.estado=1 and tp.estado=1 AND pr.id_subtipo_producto=$idSubTipoProducto and pr.codificacion_subtipo_producto like '$likeSubtipo' order by pr.nombre";
		$query=$conexion->ejecutarConsulta($sql);
		$resultado=array();
		while ($fila = pg_fetch_assoc($query)){
			$resultado[] = $fila;
		}
		return $resultado;
	}

	/**
	 * Obtiene los subtipos de producto referente al area que cumplan en codigoTipoProducto
	 * @param mixed $conexion
	 * @param string Ejempo IAP, IAV
	 * @param string El codigo del tipo de producto a buscar, puede ser del tipo: TIPO_PLAGUICIDA, PRD_CULTIVO_IAP%, PRD_%,...
	 * @return array[]
	 */
	public function obtenerSubTiposProductos($conexion,$area,$codigoTipoProducto){

		$sql="select	pr.*	from g_catalogos.subtipo_productos pr
			inner join g_catalogos.tipo_productos tp on pr.id_tipo_producto=tp.id_tipo_producto
			where pr.estado=1 and tp.estado=1 and tp.id_area='$area' and tp.codificacion_tipo_producto like '$codigoTipoProducto' order by pr.nombre;";
		$query=$conexion->ejecutarConsulta($sql);
		$res=array();
		while ($fila = pg_fetch_assoc($query)){
			$res[] = $fila;
		}
		return $res;
	}

	/**
	 * Obtiene los subtipos de producto referente al area que cumplan en codigo del SubTipoProducto
	 * @param mixed $conexion
	 * @param string El codigo del subtipo a buscar, puede ser del tipo: CULTIVOS, RIA-%, CUL%,...
	 * @return array[]
	 */
	public function obtenerSubTiposXcodigo($conexion,$codigoSubTipoProducto){

		$sql="select	pr.*	from g_catalogos.subtipo_productos pr
			inner join g_catalogos.tipo_productos tp on pr.id_tipo_producto=tp.id_tipo_producto
			where pr.estado=1 and tp.estado=1 and pr.codificacion_subtipo_producto like '$codigoSubTipoProducto' order by pr.nombre;";
		$query=$conexion->ejecutarConsulta($sql);
		$res=array();
		while ($fila = pg_fetch_assoc($query)){
			$res[] = $fila;
		}
		return $res;
	}


	/**
	 * Obtiene los productos del operador según No. de registro y del subtipo
	 * @param mixed $conexion
	 * @param string El no de registro
	 * @param string El subtipo de producto
	 * @return array[]
	 */
	public function obtenerProductoXregistroSubtipo ($conexion, $noRegistro,$subTipoProducto){
		$res=array();

		$query = $conexion->ejecutarConsulta("
			select pi.numero_registro, p.id_producto,p.nombre_comun,p.nombre_cientifico,pi.dosis, pi.unidad_dosis,f.id_formulacion,f.formulacion,
			f.norma,f.sigla,
			pi.composicion,pi.ingrediente_activo
			from g_catalogos.productos_inocuidad pi
			inner join g_catalogos.productos p on pi.id_producto=p.id_producto
			left join g_catalogos.formulacion f on pi.id_formulacion=f.id_formulacion
            left join g_catalogos.subtipo_productos sp on sp.id_subtipo_producto=p.id_subtipo_producto 
			where sp.codificacion_subtipo_producto='$subTipoProducto' AND UPPER(RTRIM(pi.numero_registro)) = UPPER(RTRIM('$noRegistro'));
		");
		while ($fila = pg_fetch_assoc($query)){
			$res[] = $fila;
		}
		return $res;

	}
	
	public function obtenerProductoXregistro ($conexion, $noRegistro,$likeCodigoSubTipo=null){
		$sql="select pi.numero_registro, p.id_producto,p.nombre_comun,p.nombre_cientifico,p.id_subtipo_producto,pi.dosis, pi.unidad_dosis,f.id_formulacion,f.formulacion,
			f.norma,f.sigla,
			pi.composicion,pi.ingrediente_activo
			from g_catalogos.productos_inocuidad pi
			inner join g_catalogos.productos p on pi.id_producto=p.id_producto
			left join g_catalogos.formulacion f on pi.id_formulacion=f.id_formulacion
			inner join g_catalogos.subtipo_productos sp on sp.id_subtipo_producto=p.id_subtipo_producto
			inner join g_catalogos.tipo_productos tp on sp.id_tipo_producto=tp.id_tipo_producto
			where sp.estado=1 and tp.estado=1 AND UPPER(RTRIM(pi.numero_registro)) = UPPER(RTRIM('$noRegistro')) ";
		if($likeCodigoSubTipo==null){
			$sql=$sql.";";
		}
		else{
			$sql=$sql." AND sp.codificacion_subtipo_producto like '$likeCodigoSubTipo';";
		}

		

		$res=array();
		$query = $conexion->ejecutarConsulta($sql);
	    while ($fila = pg_fetch_assoc($query)){
	        $res[] = $fila;
	    }
	    return $res;
	    
	} 


	public function obtenerIaXregistro ($conexion, $noRegistro,$likeCodigoSubTipo=null){
		$sql="select pi.id_producto,pi.formulacion,ci.id_ingrediente_activo,ia.ingrediente_activo ,ci.concentracion,ci.unidad_medida,ci.unidad_medida as codigo,ia.formula_quimica,ia.grupo_quimico
			from g_catalogos.composicion_inocuidad ci inner join g_catalogos.ingrediente_activo_inocuidad ia			
			on ci.id_ingrediente_activo=ia.id_ingrediente_activo inner join g_catalogos.productos_inocuidad pi on ci.id_producto= pi.id_producto
			inner join g_catalogos.productos p on p.id_producto=pi.id_producto
			inner join g_catalogos.subtipo_productos sp on sp.id_subtipo_producto=p.id_subtipo_producto
			inner join g_catalogos.tipo_productos tp on sp.id_tipo_producto=tp.id_tipo_producto
			where sp.estado=1 and tp.estado=1 AND  UPPER(RTRIM(pi.numero_registro)) = UPPER(RTRIM('$noRegistro')) ";
		if($likeCodigoSubTipo==null){
			$sql=$sql.";";
		}
		else{
			$sql=$sql." AND sp.codificacion_subtipo_producto like '$likeCodigoSubTipo';";
		}

		
		$res=array();
		$query = $conexion->ejecutarConsulta($sql);
		while ($fila = pg_fetch_assoc($query)){
			$res[] = $fila;
		}
		return $res;

	}

	public function obtenerFabricantesXregistro ($conexion, $noRegistro,$likeCodigoSubTipo=null){
		$sql="select ff.* from g_catalogos.fabricante_formulador ff inner join g_catalogos.productos_inocuidad pi on ff.id_producto= pi.id_producto
			inner join g_catalogos.productos p on pi.id_producto=p.id_producto
			inner join g_catalogos.subtipo_productos sp on sp.id_subtipo_producto=p.id_subtipo_producto
			inner join g_catalogos.tipo_productos tp on sp.id_tipo_producto=tp.id_tipo_producto
			where sp.estado=1 and tp.estado=1 AND UPPER(RTRIM(pi.numero_registro)) = UPPER(RTRIM('$noRegistro')) ";
		if($likeCodigoSubTipo==null){
			$sql=$sql.";";
		}
		else{
			$sql=$sql." AND sp.codificacion_subtipo_producto like '$likeCodigoSubTipo';";
		}

		$res=array();
		$query = $conexion->ejecutarConsulta($sql);

		while ($fila = pg_fetch_assoc($query)){
			$res[] = $fila;
		}
		return $res;

	}

	public function obtenerProductoRegistrado($conexion, $noRegistro){
		$arr=array();
		$arr['producto'] = $this->obtenerProductoXregistro ($conexion, $noRegistro,'RIA-%');
		$arr['composicion']=array();
		$arr['fabricantes']=array();
		if(sizeof($arr['producto'])>0){
			//si hay mas de un producto encontrado, restringe al primer producto que sea al por menor
			if(sizeof($arr['producto'])>1){
				foreach($arr['producto'] as $clave=>$producto){
					$items=$this->obtenerSubTipoProductoDeclarado($conexion,$producto['id_subtipo_producto'],'RIA-%');
					if(count($items)==0){
						if(count($arr['producto'])>1){
							//elimina los productos que no pertenecen a los subtipos RIA
							unset($arr['producto'][$clave]);
						}						
					}
				}
				//elimina aquellos productos duplicados que pertenecen a los tipos declarados
				foreach($arr['producto'] as $clave=>$producto){
					if(count($arr['producto'])>1){
							unset($arr['producto'][$clave]);
						}	
				}

			}
			
			
			$arr['composicion'] = $this->obtenerIaXregistro ($conexion, $noRegistro,'RIA-%');
			$arr['fabricantes'] = $this->obtenerFabricantesXregistro ($conexion, $noRegistro,'RIA-%');
		}
		return $arr;
	}

	public function obtenerProductoRegistradoSubtipo($conexion, $noRegistro,$subTipoProducto){
		$arr=array();
		$arr['producto'] = $this->obtenerProductoXregistroSubtipo ($conexion, $noRegistro,$subTipoProducto);
		$arr['composicion']=array();
		$arr['fabricantes']=array();
		if(sizeof($arr['producto'])>0){
			$arr['composicion'] = $this->obtenerIaXregistro ($conexion, $noRegistro);
			$arr['fabricantes'] = $this->obtenerFabricantesXregistro ($conexion, $noRegistro);
		}
		return $arr;
	}


	//Retorna los productos menores
	public function obtenerProductosMenores ($conexion){
		$res=array();

		$query = $conexion->ejecutarConsulta("
			select p.id_producto,p.id_subtipo_producto,p.nombre_comun,p.nombre_cientifico,pi.numero_registro
			from g_catalogos.productos_inocuidad pi inner join g_catalogos.productos p on pi.id_producto=p.id_producto
			inner join g_catalogos.subtipo_productos sp on sp.id_subtipo_producto=p.id_subtipo_producto
			where sp.codificacion_subtipo_producto='CULTIVOS' and LOWER(pi.numero_registro)='cm' order by nombre_comun;
		");
		while ($fila = pg_fetch_assoc($query)){
			$res[] = $fila;
		}
		return $res;

	}

	
	public function obtenerProductosXSubTipo($conexion, $codigoSubTipo){
		$res=array();
		$query = $conexion->ejecutarConsulta("select p.id_producto,p.nombre_cientifico,p.nombre_comun ,pi.numero_registro
			from g_catalogos.productos_inocuidad pi inner join g_catalogos.productos p on pi.id_producto=p.id_producto
			inner join g_catalogos.subtipo_productos sp on sp.id_subtipo_producto=p.id_subtipo_producto
			where sp.codificacion_subtipo_producto='$codigoSubTipo' order by nombre_comun	;");
		while ($fila = pg_fetch_assoc($query)){
			$res[] = $fila;
		}
		return $res;
	}

	public function obtenerProductosRegistrados ($conexion,$id_operador,$codigosTiposProductos="'TIPO_PLAGUICIDA','TIPO_AFINES'"){
		$res=array();
		$query = $conexion->ejecutarConsulta("
			select distinct regexp_replace(replace(trim(from pi.numero_registro),' ',''), E'[\\n\\r\\f\\t\\u000B\\u0085\\u2028\\u2029]+', '', 'g' ) as numero_registro,
			p.nombre_comun,p.nombre_cientifico,p.partida_arancelaria,p.codigo_producto,p.subcodigo_producto,
			sp.nombre as subtipo,pi.id_producto,pi.composicion,pi.id_formulacion,pi.dosis,pi.unidad_dosis,pi.id_categoria_toxicologica,pi.ingrediente_activo
			from g_catalogos.productos_inocuidad pi
			inner join g_catalogos.productos p on pi.id_producto=p.id_producto
			inner join g_catalogos.subtipo_productos sp on p.id_subtipo_producto= sp.id_subtipo_producto
			where pi.id_operador='$id_operador'
			AND sp.id_tipo_producto in (select id_tipo_producto from g_catalogos.tipo_productos where codificacion_tipo_producto in ($codigosTiposProductos))
			AND p.estado=1
			AND numero_registro is not null AND length(trim('.' from trim(' ' from numero_registro)) )>0 order by numero_registro asc;");
		while ($fila = pg_fetch_assoc($query)){
			$key=strtoupper($fila['numero_registro']);
			if(array_key_exists($key,$res)){
				if(strpos($fila['subtipo'],'AL POR MAYOR')!==false)
					continue;
			}
			$res[$key] = $fila;
		}
		return $res;
	}

	public function obtenerProductosMatrizRegistrados ($conexion,$id_operador){
		//Obtendo la lista
		$res=$this->obtenerProductosRegistrados($conexion,$id_operador);
		//localiza los producos matriz
		$matriz=array();
		foreach($res as $key=>$value){
			$parte=explode('-',$key);
			$count=sizeof($parte);
			if($count>1){
				if(strpos($parte[$count-1],'CL')!==false){		//es un clon
					//busca el producto matriz
					unset($parte[$count-1]);
					$k=implode('-',$parte);
					//busca si existe el producto matriz
					if(!array_key_exists($k,$res)){				//Si no hay producto matriz
						//verifica que el producto no este ya ingresado
						if(!array_key_exists($k,$matriz))
							$matriz[$k]=$value;
					}
				}
				else{
					//verifica que el producto no este ya ingresado
					if(!array_key_exists($key,$matriz))
						$matriz[$key]=$value;
				}
			}

		}

		//cuenta el numero de clones que tiene el producto matriz
		foreach($matriz as $key=>$value){
			$num_clones = array_filter(array_keys($res), function ($element) use ($key)  { return ( ($element!=$key) && (strpos($element, $key) ===0 )); } );
			$matriz[$key]['clones']=sizeof($num_clones);
		}
		return $matriz;
	}

	public function obtenerClonesRegistrados($conexion,$id_operador,$codigosTiposProductos="'TIPO_PLAGUICIDA','TIPO_AFINES'"){
		$res=array();
		$query = $conexion->ejecutarConsulta("
			select distinct regexp_replace(replace(trim(from pi.numero_registro),' ',''), E'[\\n\\r\\f\\t\\u000B\\u0085\\u2028\\u2029]+', '', 'g' ) as numero_registro,
			p.nombre_comun,p.nombre_cientifico,p.partida_arancelaria,p.codigo_producto,p.subcodigo_producto,
			sp.nombre as subtipo,pi.id_producto,pi.composicion,pi.id_formulacion,pi.dosis,pi.unidad_dosis,pi.id_categoria_toxicologica,pi.ingrediente_activo
			from g_catalogos.productos_inocuidad pi
			inner join g_catalogos.productos p on pi.id_producto=p.id_producto
			inner join g_catalogos.subtipo_productos sp on p.id_subtipo_producto= sp.id_subtipo_producto
			where pi.id_operador='$id_operador' AND sp.id_tipo_producto in (select id_tipo_producto from g_catalogos.tipo_productos where codificacion_tipo_producto in ($codigosTiposProductos))
			AND p.estado=1
			AND numero_registro is not null AND length(trim('.' from trim(' ' from numero_registro)) )>0
			AND  numero_registro like '%/NA-CL%'
			order by numero_registro asc;");
		while ($fila = pg_fetch_assoc($query)){
			$res[] = $fila;
		}
		return $res;


	}

	//*******************************protocolo *********************


	public function guardarProtocolo($conexion,$datos){
		$sql="";
		$tieneItems=false;
		$valorChequeado="";
		$tipo="";
		if(in_array('id_protocolo', array_keys($datos)))
		{
			//actualizar
			$tipo="update";
			$iden=$datos['id_protocolo'];
			$sql="UPDATE  g_ensayo_eficacia.protocolos
				set ";
				foreach ($datos as $id => $valor)
				{
					if($id=='id_protocolo')
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
						id_protocolo=$iden;";
		}
		else
		{
			//insertar
			$tipo="insert";
			$sql="INSERT INTO g_ensayo_eficacia.protocolos(";
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
			$sql.=" RETURNING id_protocolo;";
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

	public function eliminarProtocolo($conexion,$id_protocolo){
		$sql="DELETE FROM g_ensayo_eficacia.protocolos WHERE id_protocolo in ($id_protocolo);";

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


	//************************************* PLAGAS DEL PROTOCOLO *************************************************

	public function obtenerPlagas ($conexion,$idArea,$clasificacion=null){
		$sql="SELECT * FROM g_catalogos.usos WHERE id_area = '$idArea'";
		if($clasificacion!=null){
			$sql=$sql." AND clasificacion='$clasificacion'";
		}
		$sql=$sql.' ORDER BY nombre_uso;';
		$respuesta=array();

		$query = $conexion->ejecutarConsulta($sql);
		while ($fila = pg_fetch_assoc($query)){
			$respuesta[] = array('codigo'=>$fila['id_uso'],'nombre'=>$fila['nombre_uso'],'nombre2'=>$fila['nombre_comun_uso']);
		}
		return $respuesta;

	}

	public function obtenerPlagasProtocolo($conexion, $idProtocolo){
		$res=array();
		$sql="select pn.id_uso as codigo,pn.nombre_uso as nombre, pl.complejo_fungico,pl.uso,pn.nombre_comun_uso as nombre2,
			(SELECT c.nombre_uso FROM g_catalogos.usos c where c.id_area='IAP' AND c.clasificacion='CF' and c.id_uso=p.plaga_codigo_comun LIMIT 1) as nombre_fungico,
			p.* from g_ensayo_eficacia.protocolo_plagas  p
			inner join g_ensayo_eficacia.protocolos pl on pl.id_protocolo=p.id_protocolo
			left join g_catalogos.usos pn on p.plaga_codigo=pn.id_uso
			where pn.id_area='IAP' AND p.id_protocolo=$idProtocolo ;";

		$query = $conexion->ejecutarConsulta($sql);
		while ($fila = pg_fetch_assoc($query)){
			$res[] = $fila;
		}
		return $res;
	}

	public function guardarPlagaProtocolo($conexion,$datos){
		$sql="";
		$tieneItems=false;
		$valorChequeado="";
		$tipo="";

		//verifico si ya existe
		$boolExiste=false;
		$p='0';
		$plaga='';
		if(in_array('id_protocolo', array_keys($datos)))
			$p=$datos['id_protocolo'];
		if(in_array('plaga_codigo', array_keys($datos)))
			$plaga=$datos['plaga_codigo'];
		if($plaga!='' && $p!='0'){
			$sql="select * from g_ensayo_eficacia.protocolo_plagas where id_protocolo=$p and plaga_codigo='$plaga';";
			$res = $conexion->ejecutarConsulta($sql);
			if(pg_num_rows($res)>0)
				$boolExiste=true;
		}

		if($boolExiste || in_array('id_protocolo_plagas', array_keys($datos)))
		{
			//actualizar
			$tipo="update";
			$iden=$datos['id_protocolo_plagas'];
			$sql="UPDATE  g_ensayo_eficacia.protocolo_plagas
				set ";
			foreach ($datos as $id => $valor)
			{
				if($id=='id_protocolo_plagas')
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
						id_protocolo_plagas=$iden;";
		}
		else{
			//insertar
			$tipo="insert";
			$sql="INSERT INTO g_ensayo_eficacia.protocolo_plagas(";
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
			$sql.=" RETURNING id_protocolo_plagas;";
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


	public function borrarPlagaDeclaradaProtocolo($conexion,$id_protocolo_plagas){

		//borrar
		$resultado=array();
		$resultado['tipo']='';

			$sql="DELETE FROM g_ensayo_eficacia.protocolo_plagas WHERE id_protocolo_plagas=$id_protocolo_plagas;";
			$res = $conexion->ejecutarConsulta($sql);
			$filasTemp=array();
			while ($fila = pg_fetch_assoc($res)){
				$filasTemp[] = $fila;
			}
			$resultado['tipo']='delete';
			$resultado['resultado']=	$filasTemp;


		return $resultado;
	}

	public function borrarPlagaProtocolo($conexion,$idProtocolo){

		//borrar
		$resultado=array();
		$resultado['tipo']='';

			$sql="DELETE FROM g_ensayo_eficacia.protocolo_plagas WHERE id_protocolo=$idProtocolo;";
			$res = $conexion->ejecutarConsulta($sql);
			$filasTemp=array();
			while ($fila = pg_fetch_assoc($res)){
				$filasTemp[] = $fila;
			}
			$resultado['tipo']='delete';
			$resultado['resultado']=	$filasTemp;


		return $resultado;
	}


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

	public function chequearStringNulo($str){
		if($str==NULL || $str=='0')
			return '';
		return $str;
	}

	//************************************* Informes finales *************************************

	public function guardarInformeFinal($conexion,$datos){
		$sql="";
		$tieneItems=false;
		$valorChequeado="";
		$tipo="";
		if(in_array('id_informe', array_keys($datos)))
		{
			//actualizar
			$tipo="update";
			$iden=$datos['id_informe'];
			$sql="UPDATE  g_ensayo_eficacia.informes
				set ";
				foreach ($datos as $id => $valor)
				{
					if($id=='id_informe')
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
						id_informe=$iden
						RETURNING id_informe;";
		}
		else
		{
			//insertar
			$tipo="insert";
			$sql="INSERT INTO g_ensayo_eficacia.informes(";
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
			$sql.=" RETURNING id_informe;";
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

	public function obtenerInformesFinales($conexion,$id_protocolo,$tipo=null){

		$sql="select i.* from g_ensayo_eficacia.informes i
			inner join g_ensayo_eficacia.protocolo_zonas z on i.id_protocolo_zona=z.id_protocolo_zona
			where z.provincia>0 AND z.id_protocolo=$id_protocolo";
		if($tipo==null)
			$sql=$sql.';';
		else
			$sql=$sql." AND tipo='$tipo';";
		$res=array();
		$query = $conexion->ejecutarConsulta($sql);
		while ($fila = pg_fetch_assoc($query)){
			$res[] = $fila;
		}

		return $res;
	}

	public function obtenerInformesFinalesDelGrupo($conexion,$id_informe,$estado=null){
		$sql="select i.* from g_ensayo_eficacia.informes i
			inner join g_ensayo_eficacia.protocolo_zonas z on i.id_protocolo_zona=z.id_protocolo_zona
			where z.provincia>0 and z.id_protocolo in (select zn.id_protocolo from g_ensayo_eficacia.informes fi inner join g_ensayo_eficacia.protocolo_zonas zn on fi.id_protocolo_zona=zn.id_protocolo_zona where fi.id_informe=$id_informe )";
		if($estado!=null){
			$sql=$sql." AND i.estado='$estado'";
		}
		$sql=$sql.";";
		$respuesta=array();
		$query = $conexion->ejecutarConsulta($sql);
		while ($fila = pg_fetch_assoc($query)){
			$respuesta[] = $fila;
		}
		return $respuesta;
	}

	public function verificarInformesFinalesConEstado($conexion,$id_informe_miembro,$estado){
		$items=$this->obtenerInformesFinalesDelGrupo($conexion,$id_informe_miembro,null);
		$conEstado=true;
		foreach($items as $item){
			if($item['estado']!=$estado){
				$conEstado=false;
				break;
			}
		}
		return $conEstado;
	}

	public function obtenerInformeFinal($conexion,$id_protocolo_zona,$tipo=null){

		$sql="select i.* from g_ensayo_eficacia.informes i
			where id_protocolo_zona=$id_protocolo_zona";
		if($tipo==null)
			$sql=$sql.';';
		else
			$sql=$sql." AND tipo='$tipo';";
		$query = $conexion->ejecutarConsulta($sql);
		return pg_fetch_assoc($query);

	}

	public function obtenerInformeFinalEnsayo($conexion,$id_informe){

		$sql="select i.*,l.nombre as provincia,c.nombre as canton,p.nombre as parroquia from g_ensayo_eficacia.informes i
			inner join g_ensayo_eficacia.protocolo_zonas pr on pr.id_protocolo_zona=i.id_protocolo_zona
			left join g_catalogos.localizacion l on pr.provincia=l.id_localizacion
			left join g_catalogos.localizacion c on pr.canton=c.id_localizacion
			left join g_catalogos.localizacion p on pr.parroquia=p.id_localizacion
			where i.id_informe=$id_informe";

		$query = $conexion->ejecutarConsulta($sql);
		return pg_fetch_assoc($query);
	}

	public function obtenerInformeFinalPorExpediente($conexion,$id_expediente){

		$sql="select i.*,l.nombre as provincia,c.nombre as canton,p.nombre as parroquia from g_ensayo_eficacia.informes i
			inner join g_ensayo_eficacia.protocolo_zonas pr on pr.id_protocolo_zona=i.id_protocolo_zona
			left join g_catalogos.localizacion l on pr.provincia=l.id_localizacion
			left join g_catalogos.localizacion c on pr.canton=c.id_localizacion
			left join g_catalogos.localizacion p on pr.parroquia=p.id_localizacion
			where i.id_expediente='$id_expediente';";

		$query = $conexion->ejecutarConsulta($sql);
		return pg_fetch_assoc($query);
	}


	public function obtenerInformesFinalesDelRegistro($conexion,$id_registro){

		$sql="select * from g_ensayo_eficacia.informes f
			inner join g_ensayo_eficacia.protocolo_zonas pz on pz.id_protocolo_zona=f.id_protocolo_zona
			inner join g_ensayo_eficacia.protocolos p on p.id_protocolo=pz.id_protocolo
			inner join g_dossier_plaguicida.solicitudes s on s.protocolo=p.id_expediente
			where s.registro_producto='$id_registro';";

		$query = $conexion->ejecutarConsulta($sql);

		$res=array();
		while ($fila = pg_fetch_assoc($query)){
		   $res[] = $fila;
		}

		return $res;
	}

	public function guardarMatrizEficacia($conexion,$idIinforme,$idTratamiento,$idEvaluacion,$valor){
		$sql="select * from g_ensayo_eficacia.informes_calculos 
			where id_informe=$idIinforme and id_tratamiento=$idTratamiento and id_evaluacion=$idEvaluacion;";
		$respuesta=array();
		$respuesta = $conexion->ejecutarConsulta($sql);
		$sql='';
		if(pg_num_rows($respuesta)>0){
			$sql="UPDATE g_ensayo_eficacia.informes_calculos   SET  valor=$valor
				WHERE id_informe=$idIinforme and id_tratamiento=$idTratamiento and id_evaluacion=$idEvaluacion;";
		}
		else{
			$sql="INSERT INTO g_ensayo_eficacia.informes_calculos(id_informe, id_tratamiento, id_evaluacion,valor)
				VALUES ($idIinforme, $idTratamiento,$idEvaluacion,$valor);";
		}

		$respuesta = $conexion->ejecutarConsulta($sql);
		return $respuesta;
	}

	public function obtenerMatrizEficacia($conexion,$idProtocolo,$idInforme=null){
		$sql="select * from g_ensayo_eficacia.protocolo_dosis pd where pd.id_protocolo=$idProtocolo";
		$query = $conexion->ejecutarConsulta($sql);
		$tratamientos=array();
		while ($fila = pg_fetch_assoc($query)){
		   $tratamientos[$fila['codigo']] = $fila;
		}
		$sql="select * from g_ensayo_eficacia.evaluaciones ev where ev.id_protocolo=$idProtocolo";
		$query = $conexion->ejecutarConsulta($sql);
		$evaluaciones=array();
		while ($fila = pg_fetch_assoc($query)){
		   $evaluaciones[$fila['nombre']] = $fila;
		}
		$datosEvaluacion=array();
		if($idInforme!=null){
			$sql="select * from g_ensayo_eficacia.informes_calculos ic where ic.id_informe=$idInforme";
			$query = $conexion->ejecutarConsulta($sql);
			$datosEvaluacion=array();
			while ($fila = pg_fetch_assoc($query)){
				$datosEvaluacion[] = $fila;
			}
		}
		$sql='<table class="eficaciaStandar">
			<thead>
				<tr>';
		$sql=$sql.'<th>Tratamientos</th>';
		foreach($evaluaciones as $columna=>$fila){
			$sql=$sql.'<th>'.$columna.'</th>';	
		}
		
		$sql=$sql.'</tr>
			</thead>
			<tbody>';
		foreach($tratamientos as $fila=>$valorFila){
			$sql=$sql.'<tr>';
			$sql=$sql.'<td><label>T'.$fila.'</label></td>';
			foreach($evaluaciones as $columna=>$valorColumna){
				$idEvaluacion='evaluacion_'.$valorFila['id_protocolo_dosis'].'_'.$valorColumna['id_evaluacion'];
				$valorEvaluacion='';
				if($idInforme!=null){
					$valorEvaluacion='';
					foreach ($datosEvaluacion as $fila) {
						 if (($fila['id_tratamiento'] === $valorFila['id_protocolo_dosis']) && ($fila['id_evaluacion'] === $valorColumna['id_evaluacion'])) {
							  $valorEvaluacion=$fila['valor'];
							  break;
						 }
					}
				}
				$sql=$sql.'<td><input id="'.$idEvaluacion.'" name="'.$idEvaluacion.'" value="'.$valorEvaluacion.'" class="obsEficacia valor-numerico" type="number" min="0" max="99999999" step="0.01" /></td>';
			}			
			$sql=$sql.'</tr>';	
		}
		
		$sql=$sql.'</tbody>
		</table>';

		return $sql;
	}

	
	public function obtenerMatrizEficaciaDatos($conexion,$idProtocolo,$idInforme=null){
		$sql="select * from g_ensayo_eficacia.protocolo_dosis pd where pd.id_protocolo=$idProtocolo";
		$query = $conexion->ejecutarConsulta($sql);
		$tratamientos=array();
		while ($fila = pg_fetch_assoc($query)){
		   $tratamientos[$fila['codigo']] = $fila;
		}
		$sql="select * from g_ensayo_eficacia.evaluaciones ev where ev.id_protocolo=$idProtocolo";
		$query = $conexion->ejecutarConsulta($sql);
		$evaluaciones=array();
		while ($fila = pg_fetch_assoc($query)){
		   $evaluaciones[$fila['nombre']] = $fila;
		}
		$datosEvaluacion=array();
		if($idInforme!=null){
			$sql="select * from g_ensayo_eficacia.informes_calculos ic where ic.id_informe=$idInforme";
			$query = $conexion->ejecutarConsulta($sql);
			$datosEvaluacion=array();
			while ($fila = pg_fetch_assoc($query)){
				$datosEvaluacion[] = $fila;
			}
		}

		$datosItems=array();
		foreach($tratamientos as $fila=>$valorFila){
			$datosFila=array();
			$index=1;
			$datosFila[0]='T'.$fila;
			foreach($evaluaciones as $columna=>$valorColumna){
				if($idInforme!=null){
					$valorEvaluacion='';
					foreach ($datosEvaluacion as $fila) {
						 if (($fila['id_tratamiento'] === $valorFila['id_protocolo_dosis']) && ($fila['id_evaluacion'] === $valorColumna['id_evaluacion'])) {
							  $valorEvaluacion=$fila['valor'];
							  break;
						 }
					}
					$datosFila[$index++]=$valorEvaluacion;
				}
			}
			$datosItems[]=$datosFila;
		}

		$respuesta=array();
		$encabezado=array_keys($evaluaciones);
		array_unshift($encabezado,"Tratamientos");
		$respuesta['encabezado']=$encabezado;
		$respuesta['items']=$datosItems;
		

		return $respuesta;
	}

	public function obtenerMatrizEficaciaEvaluacion($conexion,$idProtocolo,$tipoEvaluacion,$idInforme=null){
		$sql="select * from g_ensayo_eficacia.protocolo_dosis pd where pd.id_protocolo=$idProtocolo";
		$query = $conexion->ejecutarConsulta($sql);
		$tratamientos=array();
		while ($fila = pg_fetch_assoc($query)){
		   $tratamientos[$fila['codigo']] = $fila;
		}
		$sql="select * from g_ensayo_eficacia.evaluaciones ev where ev.id_protocolo=$idProtocolo";
		$query = $conexion->ejecutarConsulta($sql);
		$evaluaciones=array();
		while ($fila = pg_fetch_assoc($query)){
		   $evaluaciones[$fila['nombre']] = $fila;
		}

		$filaTestigo=max( array_keys( $tratamientos ));
		$testigo= $tratamientos[$filaTestigo]['id_protocolo_dosis'];
		$columnaPivot=reset($evaluaciones)['nombre'];
		$pivot=$evaluaciones[$columnaPivot]['id_evaluacion'];

		$datosEvaluacion=array();
		if($idInforme!=null){
			$sql="select * from g_ensayo_eficacia.informes_calculos ic where ic.id_informe=$idInforme";
			$query = $conexion->ejecutarConsulta($sql);
			$datosEvaluacion=array();
			while ($fila = pg_fetch_assoc($query)){
				$datosEvaluacion[] = $fila;
			}
		}

		$datosItems=array();
		$calculosItems=array();
		foreach($tratamientos as $fila=>$valorFila){
			
			$valorAcumulado=array();

			$datosFila=array();
			$calculosFila=array();
			$index=1;
			$datosFila[0]='T'.$fila;
			$calculosFila[0]='T'.$fila;
			
			foreach($evaluaciones as $columna=>$valorColumna){
				if($idInforme!=null){
					$valorMatriz='';
					
					foreach ($datosEvaluacion as $filaEvaluacion) {
						 if (($filaEvaluacion['id_tratamiento'] === $valorFila['id_protocolo_dosis']) && ($filaEvaluacion['id_evaluacion'] === $valorColumna['id_evaluacion'])) {
							  $valorMatriz=$filaEvaluacion['valor'];
							  break;
						 }
					}
					$datosFila[$index]=$valorMatriz;
					//calculos
					if($filaTestigo==$fila)
						continue;
					$valorEvaluacion='';
					if($columna!=$columnaPivot){
						$dato = array_filter($datosEvaluacion, function ($elemento) use ($valorFila,$valorColumna) { return (($elemento['id_tratamiento'] == $valorFila['id_protocolo_dosis'])&&($elemento['id_evaluacion'] == $valorColumna['id_evaluacion'])); } );
						$datoBase = array_filter($datosEvaluacion, function ($elemento) use ($testigo,$valorColumna) { return (($elemento['id_tratamiento'] == $testigo)&&($elemento['id_evaluacion'] == $valorColumna['id_evaluacion'])); } );
					
						$dato=reset($dato);
						$datoBase=reset($datoBase);
						if($tipoEvaluacion=='VEE_ABB'){	
							if(($datoBase['valor']!=null) && ($datoBase['valor']!=0)){							
								$valorEvaluacion=100*(($datoBase['valor']-$dato['valor'])/$datoBase['valor']);
								$valorAcumulado[$valorColumna['id_evaluacion']]=$valorEvaluacion;
								$valorEvaluacion=number_format($valorEvaluacion,2); 
							}
						}
						else if($tipoEvaluacion=='VEE_HYT'){
							$unoDato = array_filter($datosEvaluacion, function ($elemento) use ($valorFila,$pivot) { return (($elemento['id_tratamiento'] == $valorFila['id_protocolo_dosis'])&&($elemento['id_evaluacion'] == $pivot)); } );
							$unoDatoBase = array_filter($datosEvaluacion, function ($elemento) use ($testigo,$pivot) { return (($elemento['id_tratamiento'] == $testigo)&&($elemento['id_evaluacion'] == $pivot)); } );
							$unoDato=reset($unoDato);
							$unoDatoBase=reset($unoDatoBase);
							if(($datoBase['valor']!=null) && ($datoBase['valor']!=0) && ($unoDato['valor']!=null) && ($unoDato['valor']!=0))	{
								$valorEvaluacion=100*(1-(($dato['valor']/$datoBase['valor'])*($unoDatoBase['valor']/$unoDato['valor'])));
								$valorAcumulado[$valorColumna['id_evaluacion']]=$valorEvaluacion;
								$valorEvaluacion=number_format($valorEvaluacion,2); 
							}
						}
				
					
					}
					$calculosFila[$index]=$valorEvaluacion;

					$index++;
				}
			}
			$datosItems[]=$datosFila;
			//coloca los promedios
			if($filaTestigo!=$fila){
				$acculador=array_sum($valorAcumulado);
				$acculador=$acculador/(sizeof($evaluaciones)-1);
				$acculador=number_format($acculador,2);
				$calculosFila[$index]=$acculador;
				$calculosItems[]=$calculosFila;
			}
						
			
		}

		$respuesta=array();
		$encabezado=array_keys($evaluaciones);
		array_unshift($encabezado,"Tratamientos");
		$respuesta['encabezado']=$encabezado;
		$respuesta['items']=$datosItems;
		$respuesta['calculos']=$calculosItems;

		return $respuesta;
	}

	public function obtenerMatrizEvaluacionEficacia($conexion,$idProtocolo,$tipoEvaluacion,$idInforme=null){
		$sql="select * from g_ensayo_eficacia.protocolo_dosis pd where pd.id_protocolo=$idProtocolo;";
		$query = $conexion->ejecutarConsulta($sql);
		$tratamientos=array();
		while ($fila = pg_fetch_assoc($query)){
		   $tratamientos[$fila['codigo']] = $fila;
		}
		$sql="select * from g_ensayo_eficacia.evaluaciones ev where ev.id_protocolo=$idProtocolo;";
		$query = $conexion->ejecutarConsulta($sql);
		$evaluaciones=array();
		while ($fila = pg_fetch_assoc($query)){
		   $evaluaciones[$fila['nombre']] = $fila;
		}
		$filaTestigo=max( array_keys( $tratamientos ));
		$testigo= $tratamientos[$filaTestigo]['id_protocolo_dosis'];
		$columnaPivot=reset($evaluaciones)['nombre'];
		$pivot=$evaluaciones[$columnaPivot]['id_evaluacion'];
		$datosEvaluacion=array();
		
		if($idInforme==null)
			return '';
		else{
			$sql="select * from g_ensayo_eficacia.informes_calculos ic where ic.id_informe=$idInforme;";
			$query = $conexion->ejecutarConsulta($sql);
			$datosEvaluacion=array();
			while ($fila = pg_fetch_assoc($query)){
				$datosEvaluacion[] = $fila;
			}
			
		}
		$valorAcumulado=array();
		$sql='<thead>
				<tr>';
		$sql=$sql.'<th>Tratamientos</th>';
		foreach($evaluaciones as $columna=>$fila){
			$sql=$sql.'<th>'.$columna.'</th>';	
		}
		$sql=$sql.'<th>Promedio</th>';	
		
		$sql=$sql.'</tr>
			</thead>
			<tbody>';
		foreach($tratamientos as $fila=>$valorFila){
			if($filaTestigo==$fila)
				continue;
			$valorAcumulado=array();
			$sql=$sql.'<tr>';
			$sql=$sql.'<td><label>T'.$fila.'</label></td>';
			foreach($evaluaciones as $columna=>$valorColumna){
				$idEvaluacion='estadistica_'.$valorFila['id_protocolo_dosis'].'_'.$valorColumna['id_evaluacion'];
				$valorEvaluacion='';
				if($columna!=$columnaPivot){
					$dato = array_filter($datosEvaluacion, function ($elemento) use ($valorFila,$valorColumna) { return (($elemento['id_tratamiento'] == $valorFila['id_protocolo_dosis'])&&($elemento['id_evaluacion'] == $valorColumna['id_evaluacion'])); } );
					$datoBase = array_filter($datosEvaluacion, function ($elemento) use ($testigo,$valorColumna) { return (($elemento['id_tratamiento'] == $testigo)&&($elemento['id_evaluacion'] == $valorColumna['id_evaluacion'])); } );
					
					$dato=reset($dato);
					$datoBase=reset($datoBase);
					if($tipoEvaluacion=='VEE_ABB'){	
						if(($datoBase['valor']!=null) && ($datoBase['valor']!=0)){							
							$valorEvaluacion=100*(($datoBase['valor']-$dato['valor'])/$datoBase['valor']);
							$valorAcumulado[$valorColumna['id_evaluacion']]=$valorEvaluacion;
							$valorEvaluacion=number_format($valorEvaluacion,2); 
						}
					}
					else if($tipoEvaluacion=='VEE_HYT'){
						$unoDato = array_filter($datosEvaluacion, function ($elemento) use ($valorFila,$pivot) { return (($elemento['id_tratamiento'] == $valorFila['id_protocolo_dosis'])&&($elemento['id_evaluacion'] == $pivot)); } );
						$unoDatoBase = array_filter($datosEvaluacion, function ($elemento) use ($testigo,$pivot) { return (($elemento['id_tratamiento'] == $testigo)&&($elemento['id_evaluacion'] == $pivot)); } );
						$unoDato=reset($unoDato);
						$unoDatoBase=reset($unoDatoBase);
						if(($datoBase['valor']!=null) && ($datoBase['valor']!=0) && ($unoDato['valor']!=null) && ($unoDato['valor']!=0))	{
							$valorEvaluacion=100*(1-(($dato['valor']/$datoBase['valor'])*($unoDatoBase['valor']/$unoDato['valor'])));
							$valorAcumulado[$valorColumna['id_evaluacion']]=$valorEvaluacion;
							$valorEvaluacion=number_format($valorEvaluacion,2); 
						}
					}
				
					
				}
				$sql=$sql.'<td><input id="'.$idEvaluacion.'" name="'.$idEvaluacion.'" value="'.$valorEvaluacion.'" type="text" disabled="disabled" /></td>';
			}
			//coloca los promedios
			$acculador=array_sum($valorAcumulado);
			$acculador=$acculador/(sizeof($evaluaciones)-1);
			$acculador=number_format($acculador,2);
			$sql=$sql.'<td><input id="cal_'.$valorFila['id_protocolo_dosis'].'" name="cal_'.$valorFila['id_protocolo_dosis'].'" value="'.$acculador.'" type="text" disabled="disabled" /></td>';
			$sql=$sql.'</tr>';	
		}
		
		$sql=$sql.'</tbody>';

		return $sql;
	}


	//*********************************** Ubicaciones geograficas***********************************

	public function guardarUbicacionGeografica($conexion,$datos){
		$sql="";
		$tieneItems=false;
		$valorChequeado="";
		$tipo="";

			//insertar
			$tipo="insert";
			$sql="INSERT INTO g_ensayo_eficacia.protocolo_zonas(";
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
			$sql.=" RETURNING id_protocolo;";

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


	public function borrarUbicacionGeografica($conexion,$idProtocolo){

		//borrar
		$sql="DELETE FROM g_ensayo_eficacia.protocolo_zonas WHERE id_protocolo=$idProtocolo;";
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



	public function obtenerUnidadesMedida($conexion,$idClasificacion){

		$sql="SELECT * FROM g_catalogos.unidades_medidas ORDER BY nombre;";
		if($idClasificacion!=null){
			$sql="SELECT * FROM g_catalogos.unidades_medidas WHERE ";
			$arr=explode(',',$idClasificacion);
			$sqlMin='';
			foreach($arr as $item){
				if($sqlMin=='')
					$sqlMin=" clasificacion LIKE '%$item%'";
				else
					$sqlMin=$sqlMin." OR clasificacion LIKE '%$item%'";
			}

			$sql=$sql.$sqlMin." ORDER BY nombre;";

		}
		$res=array();
		$query = $conexion->ejecutarConsulta($sql);
		while ($fila = pg_fetch_assoc($query)){
			$res[] = $fila;
		}

		return $res;
	}


	//*********** INGREDIENTES ACTIVOS *********

	public function obtenerIngredienteActivo($conexion,$id_ingrediente_activo){
		$query = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.ingrediente_activo_inocuidad
											WHERE
												id_ingrediente_activo =  $id_ingrediente_activo;");

		return pg_fetch_assoc($query);
	}


	public function obtenerIngredientesActivos($conexion,$id_protocolo){
		$query = $conexion->ejecutarConsulta("
				select pi.*, ia.ingrediente_activo,ia.ingrediente_quimico,ia.cas,um.codigo,um.nombre, ia.grupo_quimico,ia.formula_quimica from g_ensayo_eficacia.protocolo_ia pi
				inner join g_catalogos.ingrediente_activo_inocuidad ia on pi.id_ingrediente_activo=ia.id_ingrediente_activo
				inner join g_catalogos.unidades_medidas um on pi.id_unidad=um.id_unidad_medida
				where pi.id_protocolo=$id_protocolo;");
		$res=array();
		while ($fila = pg_fetch_assoc($query)){
			$res[] = $fila;
		}

		return $res;
	}

	public function agregarIngredienteActivo($conexion,$id_protocolo,$codigo,$concentracion,$unidad){
		$res=array();

			//verifico si ya existe
			$sql="select * from g_ensayo_eficacia.protocolo_ia WHERE id_protocolo=$id_protocolo AND id_ingrediente_activo=$codigo;";
			$res = $conexion->ejecutarConsulta($sql);
			if(pg_num_rows($res)<1){
				$sql="INSERT INTO g_ensayo_eficacia.protocolo_ia (id_protocolo,id_ingrediente_activo,concentracion,id_unidad)
				VALUES ($id_protocolo,$codigo,$concentracion,$unidad);";
			}else{
				$sql="UPDATE  g_ensayo_eficacia.protocolo_ia set concentracion=$concentracion,id_unidad=$unidad
					WHERE id_protocolo=$id_protocolo AND id_ingrediente_activo=$codigo;";
			}
			$res = $conexion->ejecutarConsulta($sql);

			$res=$this->obtenerIngredientesActivos($conexion,$id_protocolo);

			return $res;


	}


	public function eliminarIngredientesActivosProtocolo($conexion,$idProtocolo){

		//borrar
		$sql="DELETE FROM g_ensayo_eficacia.protocolo_ia WHERE id_protocolo=$idProtocolo;";
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

	public function contieneParaquat($conexion,$id_protocolo){
		$query = $conexion->ejecutarConsulta("
				select distinct ia.ingrediente_activo from g_ensayo_eficacia.protocolo_ia pi
				inner join g_catalogos.ingrediente_activo_inocuidad ia on pi.id_ingrediente_activo=ia.id_ingrediente_activo
				where pi.id_protocolo=$id_protocolo;");
		$respuesta=false;
		while ($fila = pg_fetch_assoc($query)){
			$posicion_coincidencia = stripos($fila['ingrediente_activo'], 'paraquat');
			if ($posicion_coincidencia !== false) {

            $respuesta=true;
				break;
			}

		}

		return $respuesta;
	}

	public function contieneParaquatProducto($conexion,$noRegistro){
		$ingredientes=$this->obtenerIaXregistro($conexion,$noRegistro);
		$respuesta=false;
		foreach ($ingredientes as $fila){
			$posicion_coincidencia = stripos($fila['ingrediente_activo'], 'paraquat');
			if ($posicion_coincidencia !== false) {

            $respuesta=true;
				break;
			}

		}

		return $respuesta;
	}

	//*********** Formulaciones **********************

	public function obtenerFormulaciones($conexion,$idVigencia='SI'){
		$sql="SELECT * FROM g_catalogos.formulacion;";
		if($idVigencia!=null){
			$sql="SELECT * FROM g_catalogos.formulacion WHERE	vigencia='$idVigencia';";
		}
		$res=array();
		$query = $conexion->ejecutarConsulta($sql);
		while ($fila = pg_fetch_assoc($query)){
			$res[] = $fila;
		}

		return $res;
	}

	public function obtenerFormulacion($conexion,$id_protocolo){
		$sql="SELECT f.* FROM g_catalogos.formulacion f
			inner join g_ensayo_eficacia.protocolos p on f.id_formulacion=CAST(coalesce(p.plaguicida_formulacion, '0') AS integer)
			WHERE	p.id_protocolo=$id_protocolo;";

			$query = $conexion->ejecutarConsulta($sql);
			if(pg_num_rows($query)>0)
				return pg_fetch_assoc($query);
			else
				return null;

	}
	//************ Tratamientos ************************

	public function obtenerTratamientos($conexion,$id_protocolo){
		$sql="SELECT * FROM g_ensayo_eficacia.protocolo_dosis WHERE	id_protocolo=$id_protocolo;";
		$res=array();
		$query = $conexion->ejecutarConsulta($sql);
		while ($fila = pg_fetch_assoc($query)){
			$res[trim($fila['codigo'])] = $fila['dosis'];
		}

		return $res;
	}

	public function obtenerTratamientosDosis($conexion,$id_protocolo){
		$sql="SELECT * FROM g_ensayo_eficacia.protocolo_dosis WHERE	id_protocolo=$id_protocolo;";
		$res=array();
		$query = $conexion->ejecutarConsulta($sql);
		while ($fila = pg_fetch_assoc($query)){
			$res[]=$fila;
		}

		return $res;
	}

	public function guardarTratamientos($conexion,$id_protocolo,$codigo,$dosis){
		$sql="INSERT INTO g_ensayo_eficacia.protocolo_dosis(id_protocolo,codigo,dosis) VALUES($id_protocolo,'$codigo',$dosis)";
		$sql.=" RETURNING id_protocolo_dosis;";

		$query = $conexion->ejecutarConsulta($sql);
		$filasTemp=array();
		while ($fila = pg_fetch_assoc($query)){
			$filasTemp[] = $fila;
		}

		$resultado=array();
		$resultado['tipo']='insert';
		$resultado['resultado']=	$filasTemp;
		return $resultado;
	}
	public function borrarTratamientos($conexion,$id_protocolo){
		$sql="DELETE FROM g_ensayo_eficacia.protocolo_dosis WHERE id_protocolo=$id_protocolo;";
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

	//*********************** Evaluacion de plagas  *************************************

	public function obtenerEvaluacionesPlagas($conexion,$id_protocolo){
		//recupero las evaluaciones
		$sql="SELECT * FROM g_ensayo_eficacia.evaluaciones WHERE	id_protocolo=$id_protocolo ORDER BY nombre;";
		$res=array();
		$query = $conexion->ejecutarConsulta($sql);
		while ($fila = pg_fetch_assoc($query)){
			$res[] = $fila;
		}

		return $res;
	}


	public function guardarEvaluacionesPlagas($conexion,$datos){
		$sql="";
		$tieneItems=false;
		$valorChequeado="";
		$tipo="";

		//insertar
		$tipo="insert";
		$sql="INSERT INTO g_ensayo_eficacia.evaluaciones(";
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
		$sql.=" RETURNING id_evaluacion;";

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


	public function borrarEvaluacionesPlagas($conexion,$idProtocolo){

		//borrar
		$sql="DELETE FROM g_ensayo_eficacia.evaluaciones WHERE id_protocolo=$idProtocolo;";
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


	//*****************************  ANEXOS  ****************************************************


	public function listarArchivosAnexos($conexion,$idProtocolo,$tipo_documento='EP'){
		$res=array();

		$query = $conexion->ejecutarConsulta("
			select ax.*,c.codigo,c.nombre,c.nombre2 from g_ensayo_eficacia.protocolo_anexos ax
			left join g_catalogos.catalogo_ef_ex c on ax.tipo=c.codigo where id_protocolo=$idProtocolo and tipo_documento='$tipo_documento' order by c.nombre2;");

		while ($fila = pg_fetch_assoc($query)){
			$res[] = $fila;
		}

		return $res;
	}

	public function buscarArchivoAnexo($conexion,$idProtocolo,$referencia,$fase,$usuario,$tipo_documento='EP'){
		$res=array();

		$query = $conexion->ejecutarConsulta("
			select * from g_ensayo_eficacia.protocolo_anexos where id_protocolo=$idProtocolo AND lower(trim(referencia))=lower(trim('$referencia')) AND usuario='$usuario' AND fase='$fase' and tipo_documento='$tipo_documento';");

		while ($fila = pg_fetch_assoc($query)){
			$res[] = $fila;
		}

		return $res;
	}

	public function agregarArchivoAnexo($conexion, $idProtocolo,$archivo,$referencia,$fase,$usuario,$tipo,$tipo_documento='EP'){
		$sql="select * from g_ensayo_eficacia.protocolo_anexos where id_protocolo=$idProtocolo AND lower(trim(referencia))=lower(trim('$referencia')) AND usuario='$usuario' AND fase='$fase' AND tipo='$tipo'  and tipo_documento='$tipo_documento'";
		$query= $conexion->ejecutarConsulta($sql);
		$resultado=array();
		if(pg_num_rows($query)>0){	//actualiza
			$resultado['tipo']='update';
			$sql="UPDATE g_ensayo_eficacia.protocolo_anexos
				SET  path='$archivo'
				WHERE id_protocolo=$idProtocolo AND lower(trim(referencia))=lower(trim('$referencia')) AND usuario='$usuario' AND fase='$fase' AND tipo='$tipo' AND tipo_documento='$tipo_documento'";
			$sql.=" RETURNING id_protocolo_anexos;";
		}
		else{
			$resultado['tipo']='insert';
			$sql="INSERT INTO g_ensayo_eficacia.protocolo_anexos (id_protocolo,path,referencia,fase,usuario,tipo,tipo_documento)
				VALUES ($idProtocolo,'$archivo','$referencia','$fase','$usuario','$tipo','$tipo_documento')";
			$sql.=" RETURNING id_protocolo_anexos;";
		}
		$res = $conexion->ejecutarConsulta($sql);
		$filasTemp=array();
		while ($fila = pg_fetch_assoc($res)){
			$filasTemp[] = $fila;
		}



		$resultado['resultado']=	$filasTemp;
		return $resultado;

	}



	public function eliminarArchivoAnexo($conexion, $idProtocolo,$archivo,$tipo_documento='EP'){
		//borrar
		$sql="DELETE FROM g_ensayo_eficacia.protocolo_anexos WHERE id_protocolo=$idProtocolo AND lower(trim(path))=lower(trim('$archivo')) and tipo_documento='$tipo_documento';";
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

	public function borrarArchivoAnexo($conexion, $id_protocolo_anexos){
		//borrar
		$sql="DELETE FROM g_ensayo_eficacia.protocolo_anexos WHERE id_protocolo_anexos=$id_protocolo_anexos;";
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

	//*****************************  ANEXOS  ****************************************************

	public function obtenerIaDelProtocolo($conexion,$id_protocolo){
		//recupero las evaluaciones
		$sql="select e.*,ia.ingrediente_activo,ia.ingrediente_quimico,ia.formula_quimica,ia.grupo_quimico,um.codigo from g_ensayo_eficacia.protocolo_ia e
			left join g_catalogos.ingrediente_activo_inocuidad ia on e.id_ingrediente_activo=ia.id_ingrediente_activo
			left join g_catalogos.unidades_medidas um on e.id_unidad=um.id_unidad_medida
			where id_protocolo=$id_protocolo;";
		$res=array();
		$query = $conexion->ejecutarConsulta($sql);
		while ($fila = pg_fetch_assoc($query)){
			$res[] = $fila;
		}

		return $res;
	}

	//***************************************************** FLUJOS DE OPERACIONES ***********************************




	public function obtenerFlujo($conexion,$id_flujo,$estado){
	   $sql="select * from g_ensayo_eficacia.flujo_fases where id_flujo=$id_flujo and estado='$estado';";
	   $res=array();

	      $query = $conexion->ejecutarConsulta($sql);
	      while ($fila = pg_fetch_assoc($query)){
	         $res[] = $fila;
	      }
	      if(sizeof($res)>0)
	         $res=$res[0];

	   return $res;
	}


	public function obtenerFlujosDelDocumento($conexion,$id_flujo){
		$sql="select fd.*,fo.estado,fs.estado as estado_siguiente from g_ensayo_eficacia.flujo_documentos fd
			left join g_ensayo_eficacia.flujo_fases fo on fd.id_flujo=fo.id_flujo and fd.id_fase=fo.id_fase
			left join g_ensayo_eficacia.flujo_fases fs on fd.id_flujo=fs.id_flujo and fd.id_fase_siguiente=fs.id_fase
			where fd.id_flujo=$id_flujo;";
		$res=array();
		$query = $conexion->ejecutarConsulta($sql);
		while ($fila = pg_fetch_assoc($query)){
			$res[] = $fila;
		}

		return $res;
	}

	public function obtenerFormatoDocumento($conexion,$tipo_documento){
		//recupero las evaluaciones
		$sql="select * from g_ensayo_eficacia.enlaces where tipo_documento='$tipo_documento';";
		$res=array();
		$query = $conexion->ejecutarConsulta($sql);
		while ($fila = pg_fetch_assoc($query)){
			$res[] = $fila;
		}

		return $res;
	}

	public function obtenerFormatoDelElemeno($conexion,$tipo_documento,$elemento){
		//recupero las evaluaciones
		$sql="select * from g_ensayo_eficacia.enlaces where tipo_documento='$tipo_documento' and elemento='$elemento';";
		$res=array();
		$query = $conexion->ejecutarConsulta($sql);
		while ($fila = pg_fetch_assoc($query)){
			$res[] = $fila;
		}
		if(sizeof($res)>0)
			$res=$res[0];

		return $res;
	}


	//***************************************************** TRAMITES DE LOS DOCUMENTOS ***********************************

	public function obtenerTramiteEE($conexion,$id_tramite_flujo){
		//recupero las evaluaciones
		$sql="select tf.*,p.identificador as operador from g_ensayo_eficacia.tramites_flujos tf
			left join g_ensayo_eficacia.tramites t on t.id_tramite=tf.id_tramite
			left join g_ensayo_eficacia.protocolos p on t.id_documento=p.id_protocolo
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

	public function obtenerTramiteInformeEE($conexion,$id_tramite_flujo){
		//recupero las evaluaciones
		$sql="select tf.*,p.identificador as operador,p.id_protocolo from g_ensayo_eficacia.tramites_flujos tf
			left join g_ensayo_eficacia.tramites t on t.id_tramite=tf.id_tramite
			left join g_ensayo_eficacia.informes pi on t.id_documento=pi.id_informe
			left join g_ensayo_eficacia.protocolo_zonas pz on pi.id_protocolo_zona=pz.id_protocolo_zona
			left join g_ensayo_eficacia.protocolos p on pz.id_protocolo=p.id_protocolo
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

	/*Retorna el numero de tramite
	 */
	public function guardarTramiteDelDocumento($conexion,$tipo_documento,$id_documento,$identificador,$fecha_inicio,$fecha_fin,$status,$id_division=''){
		$res=array();
		$sql="select * from g_ensayo_eficacia.tramites where tipo_documento='$tipo_documento' and id_documento=$id_documento;";
		$res = $conexion->ejecutarConsulta($sql);

		if(pg_num_rows($res)>0)
		{
			$sql="UPDATE g_ensayo_eficacia.tramites set identificador='$identificador',fecha_inicio='$fecha_inicio',fecha_fin='$fecha_fin',status='$status',id_division='$id_division'
						WHERE tipo_documento='$tipo_documento' and id_documento=$id_documento;";
			$conexion->ejecutarConsulta($sql);
			$res=pg_fetch_assoc($res,0);
		}
		else{
			$sql="INSERT INTO g_ensayo_eficacia.tramites (tipo_documento,id_documento,identificador,fecha_inicio,fecha_fin,status,id_division)
				VALUES ('$tipo_documento',$id_documento,'$identificador','$fecha_inicio','$fecha_fin','$status','$id_division')
				RETURNING id_tramite;";
			$res = $conexion->ejecutarConsulta($sql);
			$res=pg_fetch_assoc($res,0);
		}
		$res=$res['id_tramite'];
		return $res;
	}

	public function guardarFlujoDelTramite($conexion,$id_tramite_flujo, $id_tramite, $id_flujo_documento,$identificador,$remitente, $ejecutor , $pendiente ,  $fecha_inicio,  $fecha_fin,$observacion='',$plazo=0, $archivo=null){
		$res=array();
		if($id_tramite_flujo==null || $id_tramite_flujo<1){
			$sql="INSERT INTO g_ensayo_eficacia.tramites_flujos (id_tramite, id_flujo_documento,identificador,remitente, ejecutor , pendiente ,  fecha_inicio,  fecha_fin,observacion,plazo, ruta_archivo)
				VALUES ($id_tramite, $id_flujo_documento,'$identificador','$remitente', '$ejecutor' , '$pendiente' ,  '$fecha_inicio',  '$fecha_fin','$observacion',$plazo, '$archivo')
				RETURNING id_tramite_flujo;";
			$res = $conexion->ejecutarConsulta($sql);
			$res=pg_fetch_assoc($res,0);
		}
		else{
			$sql="UPDATE g_ensayo_eficacia.tramites_flujos set id_tramite=$id_tramite, id_flujo_documento=$id_flujo_documento,identificador='$identificador',
			remitente='$remitente', ejecutor='$ejecutor' , pendiente='$pendiente' ,  fecha_inicio='$fecha_inicio',  fecha_fin='$fecha_fin',observacion='$observacion',plazo=$plazo
			WHERE id_tramite_flujo=$id_tramite_flujo;";
			$conexion->ejecutarConsulta($sql);
			$res=array('id_tramite_flujo'=>$id_tramite_flujo);
		}


		$res=$res['id_tramite_flujo'];
		return $res;
	}

	public function actualizarTramiteEstado($conexion,$id_tramite,$observacion=null,$pendiente,$fecha_subsanacion){
		$res=array();

		if($observacion==null)
			$sql="UPDATE g_ensayo_eficacia.tramites set status='$pendiente',fecha_fin='$fecha_subsanacion' WHERE id_tramite=$id_tramite;";
		else
			$sql="UPDATE g_ensayo_eficacia.tramites set observacion='$observacion',status='$pendiente',fecha_fin='$fecha_subsanacion' WHERE id_tramite=$id_tramite;";

		$res = $conexion->ejecutarConsulta($sql);
		return $res;
	}

	public function actualizarTramiteIdentificador($conexion,$id_tramite,$identificador){
		$res=array();
		$sql="UPDATE g_ensayo_eficacia.tramites set identificador='$identificador' WHERE id_tramite=$id_tramite;";
		$res = $conexion->ejecutarConsulta($sql);
		return $res;
	}

	public function actualizarTramiteFlujoEstado($conexion,$id_tramite_flujo,$pendiente,$fecha_subsanacion,$observacion=null,$retraso=null,$decision=null,$identificadorEvaluador=null){
		$res=array();
		$sql="UPDATE g_ensayo_eficacia.tramites_flujos set pendiente='$pendiente',fecha_fin='$fecha_subsanacion'";
		if($observacion!=null){
			$sql=$sql.",observacion='$observacion'";
		}
		if($retraso!=null){
			$sql=$sql.",retraso='$retraso'";
		}
		if($decision!=null){
			$sql=$sql.",decision='$decision'";
		}
		if($identificadorEvaluador!=null){
			$sql=$sql.",perfil_identificador='$identificadorEvaluador'";
		}
		$sql=$sql." WHERE id_tramite_flujo=$id_tramite_flujo;";

		$res = $conexion->ejecutarConsulta($sql);
		return $res;
	}


	public function reasignarTecnicoTramite($conexion,$id_tramite,$tecnico_asignado){
		$sql="UPDATE g_ensayo_eficacia.tramites set tecnico_asignado='$tecnico_asignado'
						WHERE id_tramite=$id_tramite;";
		$conexion->ejecutarConsulta($sql);

	}
	
	public function reasignarTecnicoTramiteFlujo($conexion,$id_tramite_flujo,$tecnico_asignado){

		$sql="UPDATE g_ensayo_eficacia.tramites_flujos
				set identificador='$tecnico_asignado'
				set ejecutor='$tecnico_asignado'

				WHERE id_tramite_flujo=$id_tramite_flujo;";
		$conexion->ejecutarConsulta($sql);

	}



	public function obtenerFlujosDeTramitesEE($conexion,$id_fase,$identificador,$esta_procesado='N'){
	   $sql="select t.id_tramite,t.tipo_documento,t.id_documento,t.id_division,p.identificador as operador,p.estado,o.razon_social,p.plaguicida_nombre,p.id_expediente,tf.*
			from g_ensayo_eficacia.tramites_flujos tf
			left join g_ensayo_eficacia.tramites t on tf.id_tramite=t.id_tramite
			left join g_ensayo_eficacia.protocolos p on t.id_documento=p.id_protocolo
			left join g_operadores.operadores o on p.identificador=o.identificador
			left join g_ensayo_eficacia.flujo_documentos fd on tf.id_flujo_documento=fd.id_flujo_documento
			WHERE fd.id_fase=$id_fase AND tf.identificador='$identificador' AND tf.pendiente!='$esta_procesado';";
		return $conexion->ejecutarConsulta($sql);

	}

	//busca los tramites pendientes del protocolo en la fase
	public function obtenerFlujosDeTramitesProtocoloEE($conexion,$id_fase,$identificador,$id_protocolo,$esta_procesado='N'){
	   $sql="select t.id_tramite,t.tipo_documento,t.id_documento,t.id_division,p.identificador as operador,p.estado,o.razon_social,p.plaguicida_nombre,p.id_expediente,tf.*
			from g_ensayo_eficacia.tramites_flujos tf
			left join g_ensayo_eficacia.tramites t on tf.id_tramite=t.id_tramite
			left join g_ensayo_eficacia.protocolos p on t.id_documento=p.id_protocolo
			left join g_operadores.operadores o on p.identificador=o.identificador
			left join g_ensayo_eficacia.flujo_documentos fd on tf.id_flujo_documento=fd.id_flujo_documento
			WHERE fd.id_fase=$id_fase AND tf.identificador='$identificador' AND tf.pendiente!='$esta_procesado' AND p.id_protocolo=$id_protocolo;";
		return $conexion->ejecutarConsulta($sql);

	}
	
	public function obtenerFlujosDeTramitesAsignarEE($conexion,$identificador,$id_fase,$estadoSolicitud,$perfil,$esta_procesado='N',$incluirTipos='',$tipoProductos=null){
		$sql="select t.tipo_documento,t.id_documento,t.id_division,p.identificador as operador,p.estado,o.razon_social,p.plaguicida_nombre,p.id_expediente,p.cultivo_menor,
			tf.*
			from g_ensayo_eficacia.tramites_flujos tf
			left join g_ensayo_eficacia.tramites t on tf.id_tramite=t.id_tramite
			left join g_ensayo_eficacia.protocolos p on t.id_documento=p.id_protocolo
			left join g_operadores.operadores o on p.identificador=o.identificador
			left join g_ensayo_eficacia.flujo_documentos fd on tf.id_flujo_documento=fd.id_flujo_documento
			inner join g_ensayo_eficacia.divisiones_provincias dp on dp.id_division=t.id_division
			inner join g_estructura.funcionarios fr on dp.id_provincia=fr.id_provincia
			inner join g_usuario.usuarios_perfiles upf on upf.identificador=fr.identificador
			inner join g_usuario.perfiles pf on pf.id_perfil=upf.id_perfil
			WHERE fr.activo=1 AND fr.identificador='$identificador' AND  fd.id_fase=$id_fase  AND tf.pendiente!='$esta_procesado'
			AND p.estado='$estadoSolicitud' AND pf.codificacion_perfil='$perfil'";
		if($tipoProductos!=null)
			$sql=$sql." AND p.id_subtipo_producto ".$incluirTipos." in (".$tipoProductos.")";
		$sql=$sql.";";
		return $conexion->ejecutarConsulta($sql);

	}

	public function obtenerFlujoDeTramiteEE($conexion,$id_tramite_flujo){
	   $sql="select tf.* , fe.nombre,fe.apellido
			from g_ensayo_eficacia.tramites_flujos tf
			left join g_uath.ficha_empleado fe on tf.remitente=fe.identificador
			WHERE tf.id_tramite_flujo=$id_tramite_flujo;";
	   $res=array();
	   $query = $conexion->ejecutarConsulta($sql);

		$res=pg_fetch_assoc($query,0);
	   return $res;
	}

	public function obtenerTramiteDesdeFlujoTramiteEE($conexion,$id_tramite_flujo){
	   $sql="select tf.fecha_fin as fecha_final,t.*
			from g_ensayo_eficacia.tramites_flujos tf
			inner join g_ensayo_eficacia.tramites t on tf.id_tramite=t.id_tramite
			WHERE tf.id_tramite_flujo=$id_tramite_flujo;";
	   $res=array();
	   $query = $conexion->ejecutarConsulta($sql);

		$res=pg_fetch_assoc($query,0);
	   return $res;
	}

	public function obtenerProtocolosXestado($conexion,$identificador,$inEstado="'aprobado','rechazado'"){
	   $sql="select p.id_protocolo as id_documento,p.estado ,p.fecha_aprobacion,o.razon_social,p.plaguicida_nombre,p.id_expediente,'EP' as tipo from g_ensayo_eficacia.protocolos p
			left join g_operadores.operadores o on p.identificador=o.identificador
			where p.estado in ($inEstado)";
		if($identificador==null)
			$sql=$sql.';';
		else
			$sql=$sql." AND p.identificador='$identificador';";
		return $conexion->ejecutarConsulta($sql);

	}

	public function obtenerInformesFinalesXestado($conexion,$identificador,$inEstado="'aprobado','rechazado'"){
	   $sql="select inf.id_informe as id_documento,inf.estado,inf.fecha_aprobacion,o.razon_social,p.plaguicida_nombre,inf.id_expediente, 'IF' as tipo from g_ensayo_eficacia.informes inf
			left join g_ensayo_eficacia.protocolo_zonas pz on inf.id_protocolo_zona = pz.id_protocolo_zona
			left join g_ensayo_eficacia.protocolos p on pz.id_protocolo=p.id_protocolo
			left join g_operadores.operadores o on p.identificador=o.identificador
			where inf.estado in ($inEstado)";
		if($identificador==null)
			$sql=$sql.';';
		else
			$sql=$sql." AND p.identificador='$identificador';";
		return $conexion->ejecutarConsulta($sql);

	}

	//******************************  TRAMITES DE INFORMES FINALES ***********************************

	public function obtenerFlujosInformesFinalesAsignarEE($conexion,$identificador,$id_fase,$estadoSolicitud,$perfil,$esta_procesado='N',$tieneExpediente=false){
		$sql="select t.tipo_documento,t.id_documento,t.id_division,p.identificador as operador,inf.estado,o.razon_social,p.plaguicida_nombre,inf.id_expediente,p.cultivo_menor,
			tf.*
			from g_ensayo_eficacia.tramites_flujos tf
			left join g_ensayo_eficacia.tramites t on tf.id_tramite=t.id_tramite
			left join g_ensayo_eficacia.informes inf on t.id_documento=inf.id_informe
			left join g_ensayo_eficacia.protocolo_zonas pz on inf.id_protocolo_zona = pz.id_protocolo_zona
			left join g_ensayo_eficacia.protocolos p on pz.id_protocolo=p.id_protocolo
			left join g_operadores.operadores o on p.identificador=o.identificador
			left join g_ensayo_eficacia.flujo_documentos fd on tf.id_flujo_documento=fd.id_flujo_documento
			inner join g_ensayo_eficacia.divisiones_provincias dp on dp.id_division=t.id_division
			inner join g_estructura.funcionarios fr on dp.id_provincia=fr.id_provincia
			inner join g_usuario.usuarios_perfiles upf on upf.identificador=fr.identificador
			inner join g_usuario.perfiles pf on pf.id_perfil=upf.id_perfil
			WHERE fr.activo=1 AND fr.identificador='$identificador' AND  fd.id_fase=$id_fase  AND tf.pendiente!='$esta_procesado'
			AND inf.estado='$estadoSolicitud' AND pf.codificacion_perfil='$perfil'";
		if($tieneExpediente)
			$sql=$sql." AND inf.id_expediente is not null;";
		else
			$sql=$sql.";";
		return $conexion->ejecutarConsulta($sql);

	}


	public function obteneCorreoPorPerfil($conexion,$perfil,$aplicacion){
		$sql="select up.identificador,fe.mail_institucional from g_usuario.usuarios_perfiles up 
				inner join g_usuario.perfiles p on up.id_perfil=p.id_perfil
				inner join g_programas.aplicaciones_registradas ar on ar.identificador=up.identificador
				inner join g_programas.aplicaciones a on a.id_aplicacion=ar.id_aplicacion
				inner join g_uath.ficha_empleado fe on fe.identificador=up.identificador
				where p.codificacion_perfil='$perfil' and a.codificacion_aplicacion='$aplicacion' ;";
		$resultado=array();
		$query = $conexion->ejecutarConsulta($sql);
		while ($fila = pg_fetch_assoc($query)){
			$resultado[] = $fila;
		}

		return $resultado;
	}


	//******************************  OBSERVACIONES A LOS TRAMITES ************************
	public function agregarObservacionAlTramite($conexion,$id_tramite_flujo,$id_enlace,$observacion,$revision,$pendiente,$selector=null){
		$res=array();

			//verifico si ya existe
		$sql="select * from g_ensayo_eficacia.tramites_observaciones WHERE id_tramite_flujo=$id_tramite_flujo AND id_enlace=$id_enlace";
		if($selector!=null){
			$sql=$sql." AND selector=$selector";
		}
		$sql.';';
		$res = $conexion->ejecutarConsulta($sql);
		if(pg_num_rows($res)<1){
			if($selector==null)
				$selector=0;
			$sql="INSERT INTO g_ensayo_eficacia.tramites_observaciones (id_tramite_flujo,id_enlace,observacion,revision,pendiente,selector)
			VALUES ($id_tramite_flujo,$id_enlace,'$observacion',$revision,'$pendiente',$selector)
			RETURNING id_tramite_observacion;";
		}else{
			$sql="UPDATE  g_ensayo_eficacia.tramites_observaciones set observacion='$observacion',revision=$revision,pendiente='$pendiente'
					WHERE id_tramite_flujo=$id_tramite_flujo and id_enlace=$id_enlace";
			if($selector!=null){
				$sql=$sql." AND selector=$selector";
			}
			$sql.';';
		}
		$res = $conexion->ejecutarConsulta($sql);
		//recupera los fabricantes
		$res=$this->obtenerObservacionesDelTramite($conexion,$id_tramite_flujo);

		return $res;
	}

	public function obtenerObservacionesDelTramite($conexion,$id_tramite_flujo){
		//recupero las evaluaciones
		$sql="select * from g_ensayo_eficacia.tramites_observaciones where id_tramite_flujo=$id_tramite_flujo;";
		$res=array();
		$query = $conexion->ejecutarConsulta($sql);
		while ($fila = pg_fetch_assoc($query)){
			$res[] = $fila;
		}

		return $res;
	}

	public function obtenerObservacionDelTramite($conexion,$id_tramite_flujo,$id_enlace,$selector=null){
		//recupero las evaluaciones
		$sql="select * from g_ensayo_eficacia.tramites_observaciones where id_tramite_flujo=$id_tramite_flujo and id_enlace=$id_enlace";
		if($selector!=null){
			$sql=$sql." AND selector=$selector";
		}
		$sql=$sql.';';
		$res=array();
		$query = $conexion->ejecutarConsulta($sql);
		while ($fila = pg_fetch_assoc($query)){
			$res[] = $fila;
		}
		if(sizeof($res)>0)
		{
			$revision=-1;
			foreach($res as $key=>$valor){
				if($revision<$valor['revision']){
					$revision=$valor['revision'];
					$res=$valor;
				}
			}
		}
		return $res;
	}

	public function obtenerObservacionesDelDocumento($conexion,$id_documento,$tipo_documento='EP',$pendiene=null,$selector=null){
		//recupero las evaluaciones
		$sql="select o.*,e.tipo_documento,e.punto,e.campo,e.elemento,e.elemento_tipo,e.ver from g_ensayo_eficacia.tramites_observaciones o
			left join g_ensayo_eficacia.enlaces e on o.id_enlace=e.id_enlace
			inner join g_ensayo_eficacia.tramites_flujos tf on o.id_tramite_flujo=tf.id_tramite_flujo
			inner join g_ensayo_eficacia.tramites t on t.id_tramite=tf.id_tramite
			where t.id_documento=$id_documento AND t.tipo_documento='$tipo_documento'";
		if($selector!=null){
			$sql=$sql." AND o.selector=$selector";
		}
		if($pendiene==null)
			$sql=$sql.';';
		else
			$sql=$sql." and o.pendiente='$pendiene';";
		$res=array();
		$query = $conexion->ejecutarConsulta($sql);
		while ($fila = pg_fetch_assoc($query)){
			$res[] = $fila;
		}

		return $res;
	}


	public function obtenerObservacionesDelDocumentoPorEnlace($conexion,$id_documento,$tipo_documento,$id_enlace,$pendiene='S',$selector=null){
	   //recupero las evaluaciones
	   $sql="select o.*,e.tipo_documento,e.punto,e.campo,e.elemento,e.elemento_tipo from g_ensayo_eficacia.tramites_observaciones o
	      left join g_ensayo_eficacia.enlaces e on o.id_enlace=e.id_enlace
	      inner join g_ensayo_eficacia.tramites_flujos tf on o.id_tramite_flujo=tf.id_tramite_flujo
			inner join g_ensayo_eficacia.tramites t on t.id_tramite=tf.id_tramite
	      where t.id_documento=$id_documento AND e.id_enlace=$id_enlace and t.tipo_documento='$tipo_documento' and o.pendiente='$pendiene'";
		if($selector!=null){
			$sql=$sql." AND o.selector=$selector";
		}
		$sql=$sql.';';
	   $res=array();
	   $query = $conexion->ejecutarConsulta($sql);
	   while ($fila = pg_fetch_assoc($query)){
	      $res[] = $fila;
	   }

	   return $res;
	}

	public function actualizarObservacionTramiteEstado($conexion,$id_tramite_observacion,$pendiente){
		$res=array();
		$sql="UPDATE g_ensayo_eficacia.tramites_observaciones set pendiente='$pendiente'
						WHERE id_tramite_observacion=$id_tramite_observacion;";

		$res = $conexion->ejecutarConsulta($sql);
		return $res;
	}
	
	public function obtenerObservacionesDelFlujo($conexion,$tipoDocumento,$numeroDocumento,$perfil=null){
	    
	    $sql="select tf.id_tramite_flujo,tf.observacion from g_ensayo_eficacia.tramites_flujos tf
	   inner join g_ensayo_eficacia.tramites t on t.id_tramite=tf.id_tramite
	   where t.tipo_documento='$tipoDocumento' and t.id_documento=$numeroDocumento and tf.observacion is not null and tf.observacion!='' ";
	    if($perfil!=null){
	        $sql=$sql." and tf.identificador='$perfil'";
	    }
	    
	    $sql=$sql." order by tf.id_tramite_flujo;";
	    $resultado=array();
	    $query = $conexion->ejecutarConsulta($sql);
	    while ($fila = pg_fetch_assoc($query)){
	        $resultado[] = $fila;
	    }
	    
	    return $resultado;
	}



	//***************************************ASIGNACION DE TRAMITES *********************************************

	public function consultarTramites($conexion,$estado=null,$fechaInicio=null,$tipo_documento){
		$sql="select c.nombre as documento, cp.nombre as division,";
		if($tipo_documento=='EP')
			$sql=$sql."(select  ef.id_expediente from g_ensayo_eficacia.protocolos ef where ef.id_protocolo=t.id_documento limit 1) as expediente,";
		else if($tipo_documento=='DP')
			$sql=$sql."(select  dp.id_expediente from g_dossier_pecuario.solicitudes dp where dp.id_solicitud=t.id_documento limit 1) as expediente,";
		else if($tipo_documento=='DG')
			$sql=$sql."(select  dg.id_expediente from g_dossier_plaguicida.solicitudes dg where dg.id_solicitud=t.id_documento limit 1) as expediente,";
		$sql=$sql."case
			when t.status='N' then 'Completado'
			when t.status='O' then 'Observado'
			when t.status='I' then 'Observación interna'
			else
			'Pendiente'
			end as estado,
			 t.* from g_ensayo_eficacia.tramites t
			left join g_catalogos.catalogo_ef c on c.codigo=t.tipo_documento and  c.clase='TRAMITES'
			left join g_catalogos.catalogo_ef cp on cp.codigo=t.id_division and  cp.clase='DIVISION'";
		$whereAdisional=array();
		if($estado!=null)
			$whereAdisional[]=" t.status='$estado'";
		if($fechaInicio!=null)
			$whereAdisional[]=" t.fecha_inicio>='$fechaInicio'";
		if(sizeof($whereAdisional)>0)
			$sql=$sql.implode(' AND ',$whereAdisional).';';
		else
			$sql=$sql.';';
		$query = $conexion->ejecutarConsulta($sql);
		$res=array();
		while ($fila = pg_fetch_assoc($query)){
			$res[] = $fila;
		}
		return $res;

	}
	
	public function obtenerDivisionZonal($conexion,$identificador){
		$sql="select id_division from g_estructura.funcionarios fr
			 inner join g_ensayo_eficacia.divisiones_provincias dp on dp.id_provincia= fr.id_provincia
			 where fr.identificador='$identificador'";
		$query = $conexion->ejecutarConsulta($sql);
		if(pg_num_rows($query)>0){
			$filaTemp=pg_fetch_assoc($query,0);
			return $filaTemp['id_division'];
		}
		else
			return '';
	}

	public function obtenerDivisionDesdeZonaProtocolo($conexion,$protocolo_zonas){
		$sql="select distinct dp.id_division from g_ensayo_eficacia.protocolo_zonas pz inner join g_ensayo_eficacia.divisiones_provincias dp on pz.provincia=dp.id_provincia
				where id_protocolo_zona=$protocolo_zonas";
		$query = $conexion->ejecutarConsulta($sql);
		if(pg_num_rows($query)>0){
			$filaTemp=pg_fetch_assoc($query,0);
			return $filaTemp['id_division'];
		}
		else
			return '';
	}

	public function obtenerFaseDelFlujo($conexion,$id_flujo,$estado){
	   $res='0';
	   $sql="select * from g_ensayo_eficacia.flujo_fases where id_flujo=$id_flujo and estado='$estado';";
	   $query = $conexion->ejecutarConsulta($sql);

	      $r=pg_fetch_assoc($query,0);
	      $res=$r['id_fase'];

	   return $res;
	}

	public function obtenerAnalistas($conexion,$perfil,$division=null){
		$res=array();
		$sql="select f.*,p.id_perfil,p.nombre as perfil,concat_ws(' ', e.nombre, e.apellido) as nombre_apellido from g_estructura.funcionarios f
		left join g_ensayo_eficacia.divisiones_provincias dp on f.id_provincia=dp.id_provincia
		inner join g_usuario.usuarios_perfiles up on f.identificador=up.identificador
		inner join g_usuario.perfiles p on up.id_perfil = p.id_perfil
		inner join g_uath.ficha_empleado e on e.identificador=f.identificador
		where f.estado=1 AND f.activo=1 and p.estado=1 AND  p.codificacion_perfil='$perfil' ";
		if($division==null)
			$sql=$sql.";";
		else
			$sql=$sql." AND dp.id_division='$division' ;";
		$query = $conexion->ejecutarConsulta($sql);
		while ($fila = pg_fetch_assoc($query)){
			$res[] = $fila;
		}
		return $res;
	}

	/*
	 *retorna vector con las divisiones en el campo ["id_division"]
	 */
	public function identificarDivisionesDeAtencionProtocolo($conexion,$id_protocolo){
		$res=array();
		$sql="select distinct dp.id_division from g_ensayo_eficacia.protocolo_zonas z
			left join g_ensayo_eficacia.divisiones_provincias dp on z.provincia=dp.id_provincia
			where z.provincia!=0 AND z.id_protocolo=$id_protocolo";
		$query = $conexion->ejecutarConsulta($sql);
		while ($fila = pg_fetch_assoc($query)){
			$res[] = $fila;
		}
		return $res;
	}

	public function asignarTramiteADivision($conexion,$id_protocolo){

		//obtengo las divisiones involucradas
		$divisiones=$this->identificarDivisionesDeAtencionProtocolo($conexion,$id_protocolo);
		$division=$divisiones[0]['id_division'];
		if(count($divisiones)>1){
			//examina si entre ellas estan Pichincha
			$encontrado=array_filter($divisiones,function($item) {
				return $item['id_division']  == 'DIV_PICH';
			});
			//Si no ecuentra Pichincha busca Guayas
			if(sizeof($encontrado)<1){
				$encontrado=array_filter($divisiones,function($item) {
					return $item['id_division'] == 'DIV_GUAY';
				});
			}
			//Si no ecuntra ninguna pone la primera
			if(sizeof($encontrado)<1){
				$encontrado[]=$divisiones[0];
			}

			
			$division=current($encontrado)['id_division'];
			
		}
		return $division;

	}

	/**
	 * Obtiene los flujos por los que pasa el trámite
	 * @param mixed $conexion 
	 * @param mixed $estado Nombre del estado del documento
	 * @param mixed $inPendiente El status del flujo a recuperar pude ser varios Ejem: "'N'"
	 * @param mixed $idTramite El número del trámite a recuperar los flujos
	 * @return array[]
	 */
	public function obtenerFlujosDelTramite($conexion,$estado,$inPendiente,$idTramite){
		$sql="select  tf.* from g_ensayo_eficacia.tramites_flujos tf 
			inner join g_ensayo_eficacia.tramites t on t.id_tramite=tf.id_tramite
			inner join g_ensayo_eficacia.flujo_documentos fd on fd.id_flujo_documento=tf.id_flujo_documento
			inner join g_ensayo_eficacia.flujo_fases ff on ff.id_flujo=fd.id_flujo and ff.id_fase=fd.id_fase
			where ff.estado='$estado' and tf.pendiente in ($inPendiente) and t.id_tramite=$idTramite;";
		$respuesta=array();
		$query = $conexion->ejecutarConsulta($sql);
		while ($fila = pg_fetch_assoc($query)){
			$respuesta[] = $fila;
		}
		return $respuesta;
	}

	public function terminarTramiteFlujo($conexion,$id_tramite,$id_tramite_flujo,$observacion,$retraso=null,$codigoTerminacion='C',$fechaSubsanacion=null){
		//cancela el trámite
		if($fechaSubsanacion==null){
			$fechaSubsanacion=(new DateTime())->format('Y-m-d');
		}

		$this->actualizarTramiteEstado($conexion,$id_tramite,$observacion,$codigoTerminacion,$fechaSubsanacion);
		//cancela el flujo	
		$this->actualizarTramiteFlujoEstado($conexion,$id_tramite_flujo,'N',$fechaSubsanacion,$observacion,$retraso);	//Cierra la fase del tramite
			
	}



	//******************************* TRAMITES DE ORGANISMOS DE INSPECCION *****************************

	public function obtenerInformesFinalesPorEstado($conexion,$identificador,$id_fase,$estado,$perfil,$esta_procesado='N'){
		$sql="select t.tipo_documento,t.id_documento,t.id_division,p.identificador as operador,p.estado,o.razon_social,p.plaguicida_nombre,p.id_expediente,p.cultivo_menor,pz.provincia,inf.id_expediente as inf_id_expediente,inf.ruta_informe_inspeccion,
			tf.*
			from g_ensayo_eficacia.tramites_flujos tf
			left join g_ensayo_eficacia.tramites t on tf.id_tramite=t.id_tramite
			left join g_ensayo_eficacia.informes pi on pi.id_informe=t.id_documento
			left join g_ensayo_eficacia.protocolo_zonas pz on pz.id_protocolo_zona=pi.id_protocolo_zona
			left join g_ensayo_eficacia.protocolos p on pz.id_protocolo=p.id_protocolo
			left join g_operadores.operadores o on p.identificador=o.identificador
			left join g_ensayo_eficacia.flujo_documentos fd on tf.id_flujo_documento=fd.id_flujo_documento

			left join g_ensayo_eficacia.informes inf on inf.id_protocolo_zona=pz.id_protocolo_zona

			inner join g_estructura.funcionarios fr on tf.identificador=fr.identificador
			inner join g_usuario.usuarios_perfiles upf on upf.identificador=fr.identificador
			inner join g_usuario.perfiles pf on pf.id_perfil=upf.id_perfil
			WHERE fr.activo=1 AND fr.identificador='$identificador' AND  fd.id_fase=$id_fase  AND tf.pendiente!='$esta_procesado'
			AND p.estado='$estado' AND pf.codificacion_perfil='$perfil';";

		$query = $conexion->ejecutarConsulta($sql);
		return $query;

	}

	public function obtenerInformesFinalesDeOrganismosPorEstado($conexion,$identificador,$id_fase,$estado,$perfil,$esta_procesado='N'){
		$sql="select t.tipo_documento,t.id_documento,t.id_division,p.identificador as operador,p.estado,o.razon_social,p.plaguicida_nombre,p.id_expediente,p.cultivo_menor,pz.provincia,inf.id_expediente as inf_id_expediente,inf.ruta_informe_inspeccion,
			tf.*
			from g_ensayo_eficacia.tramites_flujos tf
			left join g_ensayo_eficacia.tramites t on tf.id_tramite=t.id_tramite
			left join g_ensayo_eficacia.informes pi on pi.id_informe=t.id_documento
			left join g_ensayo_eficacia.protocolo_zonas pz on pz.id_protocolo_zona=pi.id_protocolo_zona
			left join g_ensayo_eficacia.protocolos p on pz.id_protocolo=p.id_protocolo
			left join g_operadores.operadores o on p.identificador=o.identificador
			left join g_ensayo_eficacia.flujo_documentos fd on tf.id_flujo_documento=fd.id_flujo_documento

			left join g_ensayo_eficacia.informes inf on inf.id_protocolo_zona=pz.id_protocolo_zona

			inner join g_operadores.operadores fr on tf.identificador=fr.identificador
			inner join g_usuario.usuarios_perfiles upf on upf.identificador=fr.identificador
			inner join g_usuario.perfiles pf on pf.id_perfil=upf.id_perfil
			WHERE  fr.identificador='$identificador' AND  fd.id_fase=$id_fase  AND tf.pendiente!='$esta_procesado'
			AND p.estado='$estado' AND pf.codificacion_perfil='$perfil';";

		$query = $conexion->ejecutarConsulta($sql);
		return $query;

	}

	public function obtenerInformesFinalesDelOperador($conexion,$identificador,$esta_procesado='N'){
		$sql="select t.tipo_documento,t.id_documento,t.id_division,p.identificador as operador,pi.estado,o.razon_social,p.plaguicida_nombre,p.id_expediente,p.cultivo_menor,pz.provincia,inf.id_expediente as inf_id_expediente,
			tf.*
			from g_ensayo_eficacia.tramites_flujos tf
			left join g_ensayo_eficacia.tramites t on tf.id_tramite=t.id_tramite
			left join g_ensayo_eficacia.informes pi on pi.id_informe=t.id_documento
			left join g_ensayo_eficacia.protocolo_zonas pz on pz.id_protocolo_zona=pi.id_protocolo_zona
			left join g_ensayo_eficacia.protocolos p on pz.id_protocolo=p.id_protocolo
			left join g_operadores.operadores o on p.identificador=o.identificador
			left join g_ensayo_eficacia.flujo_documentos fd on tf.id_flujo_documento=fd.id_flujo_documento
			left join g_ensayo_eficacia.informes inf on inf.id_protocolo_zona=pz.id_protocolo_zona
			WHERE t.tipo_documento='IF' AND  p.identificador='$identificador'  AND tf.pendiente!='$esta_procesado';";

		$query = $conexion->ejecutarConsulta($sql);
		return $query;

	}


	//***************************************ASIGNACION DE FLUJOS *********************************************
	public function obtenerTitulo($conexion,$tipo_documento){
		$sql="select * from g_ensayo_eficacia.declaraciones where tipo_documento='$tipo_documento';";
		$res=array();
		$query = $conexion->ejecutarConsulta($sql);
		while ($fila = pg_fetch_assoc($query)){
			$res[] = $fila;
		}
		if(sizeof($res)>0){
			$res=$res[0];
		}

		return $res;
	}

	//**************************************** GENERACION DE ENCABEZADO DE CORREOS *******************************************



	public function redactarNotificacion($conexion,$id_tramite, $fecha,$asunto){
		$sql="select t.*,p.identificador as operador,p.plaguicida_nombre as nombre_producto,p.id_expediente,p.email_representante_legal, o.razon_social,o.nombre_representante,o.apellido_representante
			from g_ensayo_eficacia.tramites t
			left join g_ensayo_eficacia.protocolos p on t.id_documento=p.id_protocolo
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
		$s.='Fecha: '.$fecha;
		$s.='<br/>';
		$s.='No. de expediente: '.$res['id_expediente'];
		$s.='<br/>';
		$s.='Producto: '.$res['nombre_producto'];
		$s.='<br/>';
		$s.='Asunto: '.$asunto;
		$s.='<p/>';
		$s.='Saludos cordiales,';
		$s.='<p/>';
		$s.='Soporte GUIA.';
		$s.='<p/>';
		$s.='Nota: Este mensaje fue enviado automáticamente por el sistema, por favor no lo responda.';
		return $s;

	}

	public function redactarNotificacionDesdeInformes($conexion,$id_tramite, $fecha,$asunto,$mostrarProtocolo=false){
		$sql="select inf.id_expediente as inf_expediente,t.*,p.identificador as operador,p.plaguicida_nombre as nombre_producto,p.id_expediente,p.email_representante_legal, o.razon_social,o.nombre_representante,o.apellido_representante
			from g_ensayo_eficacia.tramites t
			inner join g_ensayo_eficacia.informes inf on inf.id_informe=t.id_documento
			inner join g_ensayo_eficacia.protocolo_zonas pz on pz.id_protocolo_zona=inf.id_protocolo_zona
			left join g_ensayo_eficacia.protocolos p on pz.id_protocolo=p.id_protocolo
			left join g_operadores.operadores o on p.identificador=o.identificador
			WHERE id_tramite=$id_tramite;";
		$respuesta=array();
		$query = $conexion->ejecutarConsulta($sql);
		while ($fila = pg_fetch_assoc($query)){
			$respuesta[] = $fila;
		}
		if(sizeof($respuesta)>0)
			$respuesta=$respuesta[0];
		$nombreDestino=$respuesta['nombre_representante'].' '.$respuesta['apellido_representante'];
		$empresaDestino=$respuesta['razon_social'];
		if($mostrarProtocolo)
			$expediente=$respuesta['id_expediente'];
		else
			$expediente=$respuesta['inf_expediente'];
		$producto=$respuesta['apellido_representante'];

		return $this->obtenerFormatoNotificacion($nombreDestino,$fecha,$expediente,$producto,$asunto,$empresaDestino);

	}

	public function obtenerFormatoNotificacion($nombreDestino,$fecha,$expediente,$producto,$asunto,$empresaDestino=null){

		$s='<h2>Sistema GUIA</h2>';
		$s.='<p/>';
		$s.='<p/>';
		$s.='Estimado(a) Cliente: <br/><br/>';
		$s.='<p/>';
		$s.='Sr(a). <i>'. $nombreDestino.'</i>' ;
		$s.='<p/>';
		if($empresaDestino!=null){
			$s.='Representante Legal de <i>'.$empresaDestino.'</i>';
			$s.='<p/>';
		}
		$s.='Ha recibido una actualización a su solicitud en el sistema, por favor revise el estado de la misma en  <a href="https://guia.agrocalidad.gob.ec/agrodb/ingreso.php">https://guia.agrocalidad.gob.ec/agrodb/ingreso.php</a>';
		$s.='<p/>';
		$s.='Información de la solicitud:';
		$s.='<p/>';
		$s.='Fecha: '.$fecha;
		$s.='<br/>';
		$s.='No. de expediente: '.$expediente;
		$s.='<br/>';
		$s.='Producto: '.$producto;
		$s.='<br/>';
		$s.='Asunto: '.$asunto;
		$s.='<p/>';
		$s.='Saludos cordiales,';
		$s.='<p/>';
		$s.='Soporte GUIA.';
		$s.='<p/>';
		$s.='Nota: Este mensaje fue enviado automáticamente por el sistema, por favor no lo responda.';
		return $s;

	}

	//**************************************** CODIFICACION *******************************************

	public function obtenerSecuencialEEProtocolo($conexion,$anio){
		$count=0;

			$secuencial= $this->obtenerSecuencialExpediente($conexion,'g_ensayo_eficacia.protocolos','id_expediente','8','4','13','4',$anio);
			$count=intval($secuencial['secuencial']);
			$count++;
			$sec=str_pad($count, 4, "0", STR_PAD_LEFT);
			return "RIA-EE-".$sec."-".$anio;

	}

	public function obtenerSecuencialEEInforme($conexion,$anio){
		$count=0;

		$secuencial= $this->obtenerSecuencialExpediente($conexion,'g_ensayo_eficacia.informes','id_expediente','8','4','13','4',$anio);
		$count=intval($secuencial['secuencial']);
		$count++;
		$sec=str_pad($count, 4, "0", STR_PAD_LEFT);
		return "RIA-IF-".$sec."-".$anio;

	}


	public function obtenerSecuencialExpediente($conexion,$tabla,$columnaExpediente,$secuencialPosicion,$secuencialLargo,$anioPosicion,$anioLargo,$anio){
		$tipo=array();
		$sql="select max(contador) as secuencial from
			(select $columnaExpediente, CAST(coalesce(substring($columnaExpediente,$secuencialPosicion,$secuencialLargo),'0') AS integer) as contador,
			CAST(coalesce(substring($columnaExpediente,$anioPosicion,$anioLargo),'0') AS integer) as anio
			from $tabla) t
			where t.anio=$anio;";
		$res = $conexion->ejecutarConsulta($sql);

		while ($fila = pg_fetch_assoc($res)){
			$tipo = $fila;
		}
		return $tipo;

	}

	public function obtenerExpedienteDossierPecuario($conexion,$anio){
		$count=0;

			$secuencial= $this->obtenerSecuencialExpediente($conexion,'g_dossier_pecuario.solicitudes','id_expediente','8','4','13','4',$anio);
			$count=intval($secuencial['secuencial']);
			$count++;
			$sec=str_pad($count, 4, "0", STR_PAD_LEFT);
			return "RIP-ET-".$sec."-".$anio;

	}

	public function obtenerExpedienteDossierPlaguicida($conexion,$anio){
		$count=0;

		$secuencial= $this->obtenerSecuencialExpediente($conexion,'g_dossier_plaguicida.solicitudes','id_expediente','8','4','13','4',$anio);
		$count=intval($secuencial['secuencial']);
		$count++;
		$sec=str_pad($count, 4, "0", STR_PAD_LEFT);
		return "RIA-ET-".$sec."-".$anio;

	}
	public function obtenerExpedienteDossierFertilizante($conexion,$anio){
		$count=0;

		$secuencial= $this->obtenerSecuencialExpediente($conexion,'g_dossier_fertilizante.solicitudes','id_expediente','8','4','13','4',$anio);
		$count=intval($secuencial['secuencial']);
		$count++;
		$sec=str_pad($count, 4, "0", STR_PAD_LEFT);
		return "RIA-FE-".$sec."-".$anio;

	}




	//**************************************** GESTION DE PROTOCOLOS *******************************************

	

	public function listarProtocolosAprobados($conexion,$identificador,$motivo){
		$sql="select (select count(distinct i.id_informe) from g_ensayo_eficacia.informes i
			inner join g_ensayo_eficacia.protocolo_zonas z on i.id_protocolo_zona=z.id_protocolo_zona
			inner join g_ensayo_eficacia.protocolos e on z.id_protocolo=e.id_protocolo
			where e.id_protocolo=p.id_protocolo AND e.estado='aprobado' AND i.estado='aprobado') as numero_informes,
			p.id_protocolo,p.id_expediente,p.plaguicida_nombre,p.estado_dossier from g_ensayo_eficacia.protocolos p
			where estado='aprobado' AND identificador='$identificador' and motivo='$motivo';";
		$res=array();
		$query = $conexion->ejecutarConsulta($sql);
		while ($fila = pg_fetch_assoc($query)){
			$res[] = $fila;
		}
		return $res;
	}

	//*************************************************** UTILIDADES **************************************
	public function verificarVectorLleno($vector,$campos){
		$estaLleno=true;
		$camposTest = explode(',', $campos);
		foreach ($camposTest as $campo) {
			if($vector[$campo]==''){
				$estaLleno=false;
				break;
			}
		}
		return $estaLleno;
	}

	public function obtenerUrlAplicacion(){
		$constg = new Constantes();
	    
	    $temporal=$constg::RUTA_APLICACION;	
		$temporal= (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/'.$temporal;
		return $temporal;
	}

	public function obtenerRutaAnexos($conexion,$modulo){
		//Ruta de archivos
		$fecha=new DateTime();

		//busca el path del moldulo
		$pathModulo='aplicaciones/'.$modulo;
		$pt=realpath('./../../'.$pathModulo);
		$pathAnexo='/anexos/'.$fecha->format('Y').'/'.$fecha->format('m').'/'.$fecha->format('d');
		$carpeta=$pt.$pathAnexo;
		$pathAnexo=$pathModulo.$pathAnexo;

		if (!file_exists($carpeta)) {
			mkdir($carpeta, 0777, true);
		}
		$paths=array();
		$paths['ruta']=$pathAnexo;
		$paths['rutaFisica']=$carpeta;
		
		$miUrl=$this->obtenerUrlAplicacion();
		$paths['rutaUrl']=$miUrl.'/'.$pathAnexo;
		return $paths;
	}

	public function constrirRutas(&$destino,$rutas,$fileName){
		$destino['datos'] = $rutas['ruta'].'/'.$fileName;
		$destino['rutaFisica'] = $rutas['rutaFisica'].'/'.$fileName;
		$destino['rutaUrl'] = $rutas['rutaUrl'].'/'.$fileName;
	}
	//************************ INFORMACION DEL CERTIFICADO ********************************************

	/**
	 * Genera el numero de certificado
	 * @param $conexion (Conexion) objeto apuntador a la BD.
	 * @param $modulo (string) el nombre del módulo para el que se va ha generar el certificado (dossierPlaguicidas, dossierFertilizantes, dossierPecuario).
	 * @param $codigoSubtipo (string) El código asignado por AGROCALIDAD según el SubTipo de producto, definido en la tabla de Catalogos g_catalogos.catalogo_ef_ex.
	 * @return string
	 */
	public function obtenerRegistro($conexion,$modulo,$codigoSubtipo){
		$count=0;
		$prefijo='';
		if($modulo=='dossierPecuario'){
			$prefijo=$codigoSubtipo;
			$secuencial= $this->generarRegistroCertificado($conexion,'g_dossier_pecuario.solicitudes','id_certificado',$prefijo);
			$count=intval($secuencial['secuencial']);
			$count++;

		}
		else if($modulo=='dossierPlaguicida'){
			$prefijo=$codigoSubtipo;
			$secuencial= $this->generarRegistroCertificado($conexion,'g_dossier_plaguicida.solicitudes','id_certificado',$prefijo);
			$count=intval($secuencial['secuencial']);
			$count++;

		}
		else if($modulo=='dossierFertilizante'){
			$prefijo=$codigoSubtipo;
			$secuencial= $this->generarRegistroCertificado($conexion,'g_dossier_fertilizante.solicitudes','id_certificado',$prefijo);
			$count=intval($secuencial['secuencial']);
			$count++;

		}
		return $prefijo."-".$count;

	}

	public function generarRegistroCertificado($conexion,$tabla,$columnaCertificado,$codigoSubtipo){
		$sql="select COALESCE(max(contador),0) as secuencial from
			(select $columnaCertificado, CAST(coalesce(substring($columnaCertificado,length($columnaCertificado)-strpos(reverse($columnaCertificado),'-')+2,strpos(reverse($columnaCertificado),'-')-1),'0') AS integer) as contador
			from $tabla where substring($columnaCertificado,1,length($columnaCertificado)-strpos(reverse($columnaCertificado),reverse('$codigoSubtipo'))+1)='$codigoSubtipo') t
			";
		$res = $conexion->ejecutarConsulta($sql);

		return pg_fetch_assoc($res,0);
	}

	public function obtenerRegistroNuevoClon($conexion,$noRegistroMadre){
		$count=0;
		$prefijo='';
		$longuitud=strlen($noRegistroMadre);
		if($longuitud>0){
			$bloques=explode('-',$noRegistroMadre);
			$prefijo=trim($bloques[count($bloques)-1]);
			if(substr($prefijo,0,2)=='CL')
			{
				unset($bloques[count($bloques)-1]);

				$prefijo=substr($noRegistroMadre,$longuitud-1,1);
				if(is_integer($prefijo)){
					$count=intval($prefijo);
					$count++;
					$prefijo=join('-',$bloques).'-CL'.$count;
				}else{
					$prefijo=$noRegistroMadre.'1';
				}
			}
			else{
				$prefijo=$noRegistroMadre.'-CL1';
			}
		}
		else{
			$prefijo= '';
		}
		return $prefijo;
	}
	//************************** Datos firma electrónica  ******************************************

	public function obtenerDatosCertificado(){
		$rutaCertificado='aplicaciones';
		$rutaCertificado=realpath('./../../'.$rutaCertificado);
		$rutaCertificado=$rutaCertificado.'/ensayoEficacia/cert/';

		$certificate = 'file://'.$rutaCertificado.'rita_pamela_ruales_piedra.crt';
		$info = array(
			 'Name' => 'AGROCALIDAD',
			 'Location' => 'Quito-Ecuador',
			 'Reason' => 'CERTIFICADO DE REGISTRO DE INSUMOS',
			 'ContactInfo' => 'http://www.agrocalidad.gob.ec',
			 );
		$datos=array();
		$datos['rutaCertificado']=$certificate;
		$datos['info']=$info;
		$datos['password']='Pameruales29';
		return $datos;
	}

	public function generarTituloDelEnsayo($conexion,$id_protocolo,$esInformeFinal=false){
		$textoHtml='';
		$datos=$this->obtenerProtocolo($conexion,$id_protocolo);

		$str=array();
		if($esInformeFinal)
			$str[]='Informe final del estudio de la evaluación de la eficacia del producto ';
		else
			$str[]='Evaluación de la eficacia del producto ';
		$ingredientes=array();
		$listaIngredientes=array();
		$formulacion=array();
		if($datos['motivo']=='MOT_REG'){
			$ingredientes=$this->obtenerIngredientesActivos($conexion,$id_protocolo);
			foreach($ingredientes as $item){
				$listaIngredientes[]=$item['ingrediente_activo'].' '.$item['concentracion'].' '.$item['codigo'];
			}
			$formulacion=$this->obtenerFormulacion($conexion,$id_protocolo);
		}
		else{
			$datosProducto=$this->obtenerProductoRegistrado($conexion, $datos['plaguicida_registro']);

			foreach($datosProducto['composicion'] as $item){
				$listaIngredientes[]=$item['ingrediente_activo'].' '.$item['concentracion'].' '.$item['unidad_medida'];
			}
			$formulacion=$datosProducto['producto'][0];

		}

		$ingredientes=join(' + ',$listaIngredientes);
		if($formulacion['sigla']!=null)
			$ingredientes=$ingredientes.', '.$formulacion['sigla'];
		$str[]=$datos['plaguicida_nombre'].' (<i>'.$ingredientes.'</i>)';// 37 (40.2)
		$str[]=', como ';
		$str[]=$datos['uso_propuesto'];	//21,
		if($datos['cp_tiene']=="t"){
			if($datos['uso']=='RIA-COAD'){
				$coadyuvante=$this->obtenerProductoRegistrado($conexion,$datos['cp_registro'])['producto'];
				if(count($coadyuvante)>0){
					$coadyuvante=current($coadyuvante);
					$str[]=', en mezcla con el producto (<i>'.$coadyuvante['nombre_comun'].'</i>)';
				}
			}
		}
		$plagas=$this->obtenerPlagasProtocolo($conexion,$id_protocolo);
		if(sizeof($plagas)>0){
			$str[]=' para el control de ';
			$listaPlagas=array();
			foreach($plagas as $item){
				$comun=$item['nombre2'];
				if(($datos['uso']=='RIA-F') && ($datos['complejo_fungico']=='t'))
					$comun=$item['nombre_fungico'];
				$listaPlagas[]=$comun.' (<i>'.$item['nombre'].'</i>)';
			}
			$str[]=join(', ',$listaPlagas);	// 23.2
		}
		$str[]=' en el cultivo de ';
		$sql="select * from g_catalogos.productos where id_producto=".$datos['cultivo'].';';
		$query = $conexion->ejecutarConsulta($sql);
		$producto=pg_fetch_assoc($query,0);

		$str[]=$producto['nombre_comun'].' (<i>'.$producto['nombre_cientifico'].'</i>)';	// 20 (19)';

		$textoHtml=join(' ',$str);

		return $textoHtml;
	}

	//************************ FUNCIONARIOS **********************************
	public function obtenerFuncionarioXarea($conexion,$area='CGRIA'){
		$sql="SELECT fu.identificador,a.nombre as cargo,fe.nombre,fe.apellido from g_estructura.funcionarios fu
		inner join  g_estructura.area a on fu.id_area=a.id_area
		inner join g_uath.ficha_empleado fe on fe.identificador=fu.identificador
        inner join g_estructura.responsables r on fu.identificador = r.identificador
        where a.id_area='$area' and fu.activo=1 and fu.estado=1 and r.responsable = true and r.activo = 1 and r.estado = 1";
		$query = $conexion->ejecutarConsulta($sql);
		$respuesta=array();
		while ($fila = pg_fetch_assoc($query)){
			$respuesta[] = $fila;
		}

		return $respuesta;
	}

	//********************************* Manejo de catalogos standar **********************************************

	public function obtenerTablaStandar($conexion,$tabla,$clave=null,$datosClave=null){

		$sql="SELECT * FROM ".$tabla;
		if(($clave==null) || ($datosClave==null))
			$sql=$sql.";";
		else
			$sql=$sql." WHERE ".$clave." in ($datosClave);";
		$res = $conexion->ejecutarConsulta($sql);
		$respuesta=array();
		while ($fila = pg_fetch_assoc($res)){
			$respuesta[] = $fila;
		}
		return $respuesta;
	}

	public function guardarTablaStandar($conexion,$tabla,$clave,$datos){
		$sql="";
		$tieneItems=false;
		$valorChequeado="";
		$tipo="";
		if(in_array($clave, array_keys($datos)))
		{
			//actualizar
			$tipo="update";
			$iden=$datos[$clave];
			$sql="UPDATE  ".$tabla." set ";
			foreach ($datos as $id => $valor)
			{
				if($id==$clave)
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
			$sql.="	WHERE ".$clave."=$iden;";
		}
		else
		{
			//insertar
			$tipo="insert";
			$sql="INSERT INTO ".$tabla."(";
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
			$sql.=" RETURNING ".$clave.";";
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

	public function eliminarTablaStandar($conexion,$tabla,$clave,$datosClave){
		$sql="DELETE FROM ".$tabla." WHERE ".$clave." in ($datosClave);";

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

	public function obtenerEsquema($conexion,$esquema,$tabla){
		$sql="SELECT * FROM information_schema.columns where table_schema = '$esquema' and table_name='$tabla'";
		$res = $conexion->ejecutarConsulta($sql);
		$respuesta=array();
		while ($fila = pg_fetch_assoc($res)){
			$respuesta[] = $fila;
		}
		return $respuesta;
	}

	public function imprimirLineaTablaStandar($nombre,$tipo,$tabla,$camposFijos,$clase,$item,$elementos=null){
		$texto='';
		foreach($camposFijos as $valor){
			$texto=$texto.' '.$item[$valor];
		}
		$texto=trim($texto);

		$fila='<tr id="R' . $item[$clase] . '">' .
		'<td width="100%">' .
		$texto.
		'</td>' .
		'<td>' .
		'<form class="abrir" data-rutaAplicacion="ensayoEficacia" data-opcion="abrirCatalogoStandarItem" data-destino="detalleItem" data-accionEnExito="NADA" >' .
		'<input type="hidden" id="claveValor" name="claveValor" value="' . $item[$clase] . '" >' .
		'<input type="hidden" name="clave" value="' . $clase . '" >' .
		'<input type="hidden" id="tabla" name="tabla" value="' . $tabla . '" >' .
		'<input type="hidden" id="tipo" name="tipo" value="'.$tipo.'" />'.
		'<input type="hidden" id="nombre" name="nombre" value="'.$nombre.'" />'.
		'<input type="hidden" id="elementos" name="elementos" value="'.$elementos.'" />'.
		'<button class="icono" type="submit" ></button>' .
		'</form>' .
		'</td>' .
		'<td>' .
		'<form class="borrar" data-rutaAplicacion="ensayoEficacia" data-opcion="guardarCatalogoOpciones">' .
		'<input type="hidden" id="claveValor" name="claveValor" value="' . $item[$clase] . '" >' .
		'<input type="hidden" name="clave" value="' . $clase . '" >' .
		'<input type="hidden" id="tabla" name="tabla" value="' .$tabla . '" >' .
		'<input type="hidden" id="tipo" name="tipo" value="'.$tipo.'" />'.
		'<input type="hidden" id="paso_catalogo" name="paso_catalogo" value="D1" />'.

		'<button type="submit" class="icono"></button>' .
		'</form>' .
		'</td>' .
		'</tr>';


		return $fila;
	}

	public function imprimirLineaCatalogo($nombre,$tipo,$clase,$item){
		$fila='<tr id="R' . $item['codigo'] . '">' .
				'<td width="100%">' .
				$item['nombre'] .
				'</td>' .
				'<td>' .
				'<form class="abrir" data-rutaAplicacion="ensayoEficacia" data-opcion="abrirCatalogoItem" data-destino="detalleItem" data-accionEnExito="NADA" >' .
				'<input type="hidden" name="clase" value="' . $clase . '" >' .
				'<input type="hidden" id="codigo" name="codigo" value="' . $item['codigo'] . '" >' .
				'<input type="hidden" id="tipo" name="tipo" value="'.$tipo.'" />'.
				'<input type="hidden" id="nombre" name="nombre" value="'.$nombre.'" />'.

				'<button class="icono" type="submit" ></button>' .
				'</form>' .
				'</td>' .
				'<td>' .
				'<form class="borrar" data-rutaAplicacion="ensayoEficacia" data-opcion="guardarCatalogoOpciones">' .
				'<input type="hidden" id="codigo" name="codigo" value="' . $item['codigo'] . '" >' .
				'<input type="hidden" id="tipo" name="tipo" value="'.$tipo.'" />'.
				'<input type="hidden" id="paso_catalogo" name="paso_catalogo" value="E1" />'.

				'<button type="submit" class="icono"></button>' .
				'</form>' .
				'</td>' .
				'</tr>';


		return $fila;
	}

	//****************** MODIFICACIONES  *******************************

	public function obtenerProtocolosParaModificar($conexion,$identificador){
		
		//obtengo los que estan por seleccionar el organismo de inspección y en inspección caso de cultivos menores
		$sql="select distinct p.identificador, p.id_protocolo, p.estado,inf.id_informe,inf.tipo,p.identificador as operador,p.estado,o.razon_social,p.plaguicida_nombre,p.id_expediente from g_ensayo_eficacia.protocolos p 
			inner join g_ensayo_eficacia.protocolo_zonas pz on pz.id_protocolo = p.id_protocolo
			left join g_ensayo_eficacia.informes inf on inf.id_protocolo_zona = pz.id_protocolo_zona
			left join g_operadores.operadores o on p.identificador=o.identificador
			where p.estado in ('inspeccion','elegirOrganismo') and p.identificador='$identificador'
			order by id_protocolo;";
		$respuesta = $conexion->ejecutarConsulta($sql);
		$inspecciones=array();
		while ($fila = pg_fetch_assoc($respuesta)){
			$inspecciones[] = $fila;
		}

		$protocolos=array();
		foreach($inspecciones as $fila){
			if(array_key_exists($fila['id_protocolo'],$protocolos)){
				if(($fila['tipo']==null)&&($protocolos[$fila['id_protocolo']]['tipo']==null)){
					//si los 2 protocolos no han sido notificados la instalación el protocolo es apto de modificación
				}
				else{
					unset($protocolos[$fila['id_protocolo']]);	//el protocolo es retirado para no ser modificado
				}
				
			}
			else{
				$protocolos[$fila['id_protocolo']]=$fila;
			}
		}

		return $protocolos;

	}

	public function obtenerTramitesFlujosModificacion($conexion,$idProtocolo){
		$sql="select * from g_ensayo_eficacia.tramites_flujos tf
			where tf.pendiente!='N' AND tf.id_tramite in (select tt.id_tramite from g_ensayo_eficacia.tramites tt where tt.id_documento=$idProtocolo and tt.tipo_documento='EP')";
		return $conexion->ejecutarConsulta($sql);
		
	}

	public function verificarProtocoloEstado($conexion,$idProtocolo){
		$sql="select p.id_protocolo,p.id_expediente,p.es_modificacion from g_ensayo_eficacia.protocolos p where p.id_protocolo=$idProtocolo;";
		$respuesta=$conexion->ejecutarConsulta($sql);
		if(pg_num_rows($respuesta)>0)
			return pg_fetch_assoc($respuesta,0);
		else
			return array();
		
	}
	
	//***************************************** ACTUALIZACIONES REGISTRO DE PRODUCTO *************************************
	
	public function actualizarDosis($conexion,$idRegistroProducto,$dosis,$unidadDosisCodigo,$fechaModificacion,$subTipoProducto){
	    $producto=$this->obtenerProductoXregistroSubtipo($conexion, $idRegistroProducto,$subTipoProducto);
	    $idProducto=$producto['id_producto'];
	    
	    $sql="UPDATE g_catalogos.productos_inocuidad
            SET  dosis='$dosis', 
            unidad_dosis='$unidadDosisCodigo',
            fecha_modificacion='$fechaModificacion'
            WHERE id_producto=$idProducto;";
	    $conexion->ejecutarConsulta($sql);
	    
	            
	}

	//********************************* REPORTES **********************************************

	public function obtenerRegistrosEnsayos($conexion,$fechaDesde=null,$fechaHasta=null){
		$sql="select p.id_protocolo as id_solicitud,p.identificador,p.id_expediente,p.plaguicida_registro as id_certificado,p.estado,p.plaguicida_nombre as nombre_producto,
			o.razon_social,'' as sitio,cef.nombre as provincia,sp.nombre as subtipo_producto,p.fecha_solicitud as fecha_inicio,p.fecha_aprobacion as fecha_registro
			from g_ensayo_eficacia.protocolos p
			left join g_operadores.operadores o on p.identificador=o.identificador			
			left join g_catalogos.subtipo_productos sp on sp.codificacion_subtipo_producto=p.uso
			left join g_ensayo_eficacia.tramites t on t.id_documento=p.id_protocolo and t.tipo_documento='EP'
			left join g_catalogos.catalogo_ef cef on cef.codigo=t.id_division and cef.clase='DIVISION'
			order by p.fecha_solicitud;";

		return $conexion->ejecutarConsulta($sql);
		
	}

	public function obtenerRegistrosInformes($conexion,$fechaDesde=null,$fechaHasta=null){
		$sql="select i.id_informe as id_solicitud,p.identificador,i.id_expediente,p.plaguicida_registro as id_certificado,i.estado,p.plaguicida_nombre as nombre_producto,
			o.razon_social,'' as sitio,cef.nombre as provincia,sp.nombre as subtipo_producto,i.fecha_solicitud as fecha_inicio,i.fecha_aprobacion as fecha_registro
			from g_ensayo_eficacia.informes i
			inner join g_ensayo_eficacia.protocolo_zonas z on i.id_protocolo_zona=z.id_protocolo_zona
			inner join g_ensayo_eficacia.protocolos p on z.id_protocolo=p.id_protocolo
			left join g_operadores.operadores o on p.identificador=o.identificador			
			left join g_catalogos.subtipo_productos sp on sp.codificacion_subtipo_producto=p.uso
			left join g_ensayo_eficacia.tramites t on t.id_documento=i.id_informe and t.tipo_documento='IF'
			left join g_catalogos.catalogo_ef cef on cef.codigo=t.id_division and cef.clase='DIVISION'
			order by p.fecha_solicitud;";

		return $conexion->ejecutarConsulta($sql);
		
	}

	public function obtenerMatrizServicio($conexion,$fechaDesde=null,$fechaHasta=null){
		 
		$sql="select to_char(100*(2-(ss.tiempo/ss.plazo)), '9999') as eficiencia, cast(ss.tiempo as integer) as tiempo_real, ss.* from (
				select distinct
            tf.id_tramite_flujo,tf.identificador,tf.remitente,tf.ejecutor,tf.decision,p.identificador as operador,tf.observacion,
            p.id_protocolo,p.id_expediente,'' as id_certificado,p.estado,p.plaguicida_nombre as nombre_producto, p.fecha_solicitud,
			
			o.razon_social,sp.nombre as subtipo_producto,sp.codificacion_subtipo_producto,tf.fecha_inicio , tf.fecha_fin,
			ce.nombre,tf.id_tramite,tf.id_flujo_documento,tf.identificador as tecnico,tf.fecha_inicio as fecha_tecnico,
			cast((select count(id_tramite_observacion) from g_ensayo_eficacia.tramites_observaciones where id_tramite_flujo=tf.id_tramite_flujo) as integer) as numero_observaciones,
			
			EXTRACT(DAY FROM age(date(tf.fecha_fin),date(tf.fecha_inicio) )) as tiempo,
			fd.plazo as plazos,plazo_n,plazo_condicion,plazo_a,case when tf.plazo =0 then (case when fd.plazo=0 then 1 else fd.plazo end) else tf.plazo end ,tf.retraso,(fe.nombre || fe.apellido) as nombres_tecnico
			,tf.perfil_identificador,(fed.nombre || fed.apellido) as nombres_evaluador
			from g_ensayo_eficacia.protocolos p
			left join g_operadores.operadores o on p.identificador=o.identificador
			
			left join g_catalogos.subtipo_productos sp on sp.codificacion_subtipo_producto=p.uso
			left join g_catalogos.catalogo_ef ce on ce.codigo=p.motivo
			left join g_ensayo_eficacia.tramites tt on tt.id_documento=p.id_protocolo and tt.tipo_documento='EP'
			left join g_ensayo_eficacia.tramites_flujos tf on tf.id_tramite=tt.id_tramite
			left join g_ensayo_eficacia.flujo_documentos fd on fd.id_flujo_documento=tf.id_flujo_documento
			left join g_ensayo_eficacia.flujo_fases ff on ff.id_flujo=fd.id_flujo and ff.id_fase=fd.id_fase
			left join g_uath.ficha_empleado fe on fe.identificador=tf.identificador
			left join g_uath.ficha_empleado fed on fed.identificador=tf.perfil_identificador
			where ff.estado not in ('solicitud') and fd.tipo_documento='EP' 

			order by tf.id_tramite_flujo) as ss;";
	    
	    return $conexion->ejecutarConsulta($sql);
	    
	}

	public function obtenerMatrizServicioInformes($conexion,$fechaDesde=null,$fechaHasta=null){
		 
		$sql="select to_char(100*(2-(ss.tiempo/ss.plazo)), '9999') as eficiencia, cast(ss.tiempo as integer) as tiempo_real, ss.* from (
				select distinct
            tf.id_tramite_flujo,tf.identificador,tf.remitente,tf.ejecutor,tf.decision,p.identificador as operador,tf.observacion,
            p.id_protocolo,i.id_expediente,'' as id_certificado,i.estado,p.plaguicida_nombre as nombre_producto, i.fecha_solicitud,
			
			o.razon_social,sp.nombre as subtipo_producto,sp.codificacion_subtipo_producto,tf.fecha_inicio , tf.fecha_fin,
			ce.nombre,tf.id_tramite,tf.id_flujo_documento,tf.identificador as tecnico,tf.fecha_inicio as fecha_tecnico,
			cast((select count(id_tramite_observacion) from g_ensayo_eficacia.tramites_observaciones where id_tramite_flujo=tf.id_tramite_flujo) as integer) as numero_observaciones,
			
			EXTRACT(DAY FROM age(date(tf.fecha_fin),date(tf.fecha_inicio) )) as tiempo,
			fd.plazo as plazos,plazo_n,plazo_condicion,plazo_a,case when tf.plazo =0 then (case when fd.plazo=0 then 1 else fd.plazo end) else tf.plazo end ,tf.retraso,(fe.nombre || fe.apellido) as nombres_tecnico
			,tf.perfil_identificador,(fed.nombre || fed.apellido) as nombres_evaluador
			from g_ensayo_eficacia.informes i
			inner join g_ensayo_eficacia.protocolo_zonas z on i.id_protocolo_zona=z.id_protocolo_zona
			inner join g_ensayo_eficacia.protocolos p on z.id_protocolo=p.id_protocolo
			left join g_operadores.operadores o on p.identificador=o.identificador
			
			left join g_catalogos.subtipo_productos sp on sp.codificacion_subtipo_producto=p.uso
			left join g_catalogos.catalogo_ef ce on ce.codigo=p.motivo
			left join g_ensayo_eficacia.tramites tt on tt.id_documento=i.id_informe and tt.tipo_documento='IF'
			left join g_ensayo_eficacia.tramites_flujos tf on tf.id_tramite=tt.id_tramite
			left join g_ensayo_eficacia.flujo_documentos fd on fd.id_flujo_documento=tf.id_flujo_documento
			left join g_ensayo_eficacia.flujo_fases ff on ff.id_flujo=fd.id_flujo and ff.id_fase=fd.id_fase
			left join g_uath.ficha_empleado fe on fe.identificador=tf.identificador
			left join g_uath.ficha_empleado fed on fed.identificador=tf.perfil_identificador
			where ff.estado not in ('solicitud') and fd.tipo_documento='IF' 

			order by tf.id_tramite_flujo) as ss;";
	    
	    return $conexion->ejecutarConsulta($sql);
	    
	}
	
	//INICIO EJAR
	
	public function listarSolicitudesPorEstadoProvincia ($conexion, $estado, $provincia){
	    
	    $res = $conexion->ejecutarConsulta("select distinct
                                            	pr.id_protocolo as id_solicitud,
                                            	id_expediente as numero_solicitud,
                                            	identificador as identificador_operador,
                                            	pr.fecha_creacion as fecha_registro,
                                            	pr.estado
                                            from 
                                            	g_ensayo_eficacia.protocolos pr,	
                                            	g_ensayo_eficacia.protocolo_zonas z,
                                            	g_catalogos.localizacion l
                                            where 
                                            	pr.id_protocolo = z.id_protocolo and
                                            	l.id_localizacion = z.provincia and
                                            	z.provincia!=0 and
                                                upper(l.nombre) = upper('$provincia') and
                                                pr.estado = '$estado';");
	    return $res;
	    
	}
	
	//FIN EJAR
	
}