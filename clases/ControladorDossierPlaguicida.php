<?php
class ControladorDossierPlaguicida{
	//**************************** Funciones de la Solicitud **************************************************************************
	public function listarSolicitudesOperador ($conexion, $identificador){
		$sql="select   pr.id_solicitud,pr.producto_nombre,pr.fecha_solicitud,pr.estado,
			(select tf.fecha_inicio from g_ensayo_eficacia.tramites t left join g_ensayo_eficacia.tramites_flujos tf on tf.id_tramite=t.id_tramite 
			where t.id_documento=pr.id_solicitud and t.tipo_documento='DG' and (tf.pendiente is null or tf.pendiente!='N') order by tf.id_tramite_flujo DESC LIMIT 1) ,
			(select tf.fecha_fin from g_ensayo_eficacia.tramites t left join g_ensayo_eficacia.tramites_flujos tf on tf.id_tramite=t.id_tramite 
			where t.id_documento=pr.id_solicitud and t.tipo_documento='DG' and (tf.pendiente is null or tf.pendiente!='N') order by tf.id_tramite_flujo DESC LIMIT 1) 
			from g_dossier_plaguicida.solicitudes pr where pr.identificador = '$identificador';";
		$res = $conexion->ejecutarConsulta($sql);
		
		return $res;
	}
	public function guardarSolicitud($conexion,$datos){
		$sql="";
		$tieneItems=false;
		$valorChequeado="";
		$tipo="";
		if(in_array('id_solicitud', array_keys($datos)))
		{
			//actualizar
			$tipo="update";
			$iden=$datos['id_solicitud'];
			$sql="UPDATE  g_dossier_plaguicida.solicitudes
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
			$sql="INSERT INTO g_dossier_plaguicida.solicitudes(";
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
	public function obtenerSolicitud ($conexion, $idSolicitud){
		$query = $conexion->ejecutarConsulta("select
			pr.*,o.razon_social
			from
			g_dossier_plaguicida.solicitudes pr
			inner join g_operadores.operadores o on pr.identificador=o.identificador
			where
			pr.id_solicitud = $idSolicitud;");
		return pg_fetch_assoc($query);
	}

	public function eliminarSolicitud($conexion, $idSolicitud){
		//borrar
		$sql="DELETE FROM g_dossier_plaguicida.solicitudes WHERE id_solicitud in ($idSolicitud);";
		$conexion->ejecutarConsulta($sql);
		
	}
	
	
	//**************************** FABRICANES **************************************************************************
	public function actualizarFabricante($conexion,$id_solicitud,$tipo_fabricante,$nombre,$id_pais,$direccion,$representante_legal,$correo,$telefono,$carta){
		//verifico si ya existe
		$esNuevo=false;
		$sql="select * from g_dossier_plaguicida.solicitudes_fabricantes WHERE id_solicitud=$id_solicitud AND tipo_fabricante='$tipo_fabricante' AND nombre='$nombre';";
		$res = $conexion->ejecutarConsulta($sql);
		if(pg_num_rows($res)<1){
			$sql="INSERT INTO g_dossier_plaguicida.solicitudes_fabricantes (id_solicitud,tipo_fabricante,nombre,id_pais,direccion,representante_legal,correo,telefono,carta)
VALUES ($id_solicitud,'$tipo_fabricante','$nombre',$id_pais,'$direccion','$representante_legal','$correo','$telefono','$carta')
RETURNING id_solicitud_fabricante;";
			$esNuevo=true;
		}else{
			$sql="UPDATE g_dossier_plaguicida.solicitudes_fabricantes set  id_pais=$id_pais,direccion='$direccion',representante_legal='$representante_legal',correo='$correo',telefono='$telefono',
carta='$carta'
WHERE id_solicitud=$id_solicitud AND tipo_fabricante='$tipo_fabricante' AND nombre='$nombre';";
		}
		$resa = $conexion->ejecutarConsulta($sql);
		$resultado=array();
		if($esNuevo==true){
			$resultado['tipo']='insert';
			$resultado['id_solicitud_fabricante']=pg_fetch_result($resa, 0, 0);
		}
		else{
			$resultado['tipo']='update';
			$resultado['id_solicitud_fabricante']=pg_fetch_result($res, 0, 'id_solicitud_fabricante');
		}
		return $resultado;
	}

	public function obtenerFabricante($conexion,$id_solicitud_fabricante){
		$res=array();
		$query = $conexion->ejecutarConsulta("select f.*, l.nombre as pais
from g_dossier_plaguicida.solicitudes_fabricantes f
left join g_catalogos.localizacion l on f.id_pais=l.id_localizacion
where f.id_solicitud_fabricante=$id_solicitud_fabricante;");
		while ($fila = pg_fetch_assoc($query)){
			$res[] = $fila;
		}
		if(sizeof($res)>0){
			$res=$res[0];
		}
		return $res;
	}
	public function obtenerFabricantes($conexion,$id_solicitud,$tipo_fabricante){
		$res=array();
		$query = $conexion->ejecutarConsulta("select f.*, l.nombre as pais
			from g_dossier_plaguicida.solicitudes_fabricantes f
			left join g_catalogos.localizacion l on f.id_pais=l.id_localizacion
			where f.id_solicitud=$id_solicitud AND f.tipo_fabricante='$tipo_fabricante';");
		while ($fila = pg_fetch_assoc($query)){
			$fila['manufacturadores']=$this->obtenerManufacturadores($conexion,$fila['id_solicitud_fabricante']);
			//coloca los manufacturadores
			$res[] = $fila;
		}
		return $res;
	}
	
	public function agregarFabricante($conexion,$id_solicitud,$tipo_fabricante,$identificador,$id_pais,$direccion,$representante_legal,$correo,$telefono){
		$res=array();
		//verifico si ya existe
		$sql="select * from g_dossier_plaguicida.solicitudes_fabricantes WHERE id_solicitud=$id_solicitud AND tipo_fabricante='$tipo_fabricante' AND identificador='$identificador';";
		$res = $conexion->ejecutarConsulta($sql);
		if(pg_num_rows($res)<1){
			$sql="INSERT INTO g_dossier_plaguicida.solicitudes_fabricantes (id_solicitud,tipo_fabricante,identificador,id_pais,direccion,representante_legal,correo,telefono)
VALUES ($id_solicitud,'$tipo_fabricante','$identificador',$id_pais,'$direccion','$representante_legal','$correo','$telefono')
RETURNING id_solicitud_fabricante;";
		}else{
			$sql="UPDATE g_dossier_plaguicida.solicitudes_fabricantes set  id_pais=$id_pais,direccion='$direccion',representante_legal='$representante_legal',correo='$correo',telefono='$telefono'
WHERE id_solicitud=$id_solicitud AND tipo_fabricante='$tipo_fabricante' AND identificador='$identificador';";
		}
		$res = $conexion->ejecutarConsulta($sql);
		//recupera los fabricantes
		$res=$this->obtenerFabricantes($conexion,$id_solicitud,$tipo_fabricante);
		return $res;
	}
	public function eliminarFabricante($conexion, $id_solicitud_fabricante){
		//borrar
		$sql="DELETE FROM g_dossier_plaguicida.solicitudes_fabricantes WHERE id_solicitud_fabricante=$id_solicitud_fabricante;";
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
	//**********************************************	 MANUFACTURADORES ************************************************************
	public function obtenerManufacturadores($conexion,$id_solicitud_fabricante){
		$res=array();
		$query = $conexion->ejecutarConsulta("select f.*, l.nombre as pais
			from g_dossier_plaguicida.solicitudes_manufacturadores f
			left join g_catalogos.localizacion l on f.id_pais=l.id_localizacion
			where f.id_solicitud_fabricante=$id_solicitud_fabricante;");
		while ($fila = pg_fetch_assoc($query)){
			$res[] = $fila;
		}
		return $res;
	}
	
	public function obtenerManufacturador($conexion,$id_solicitud_manufacturador){
		$res=array();
		$query = $conexion->ejecutarConsulta("select f.*, l.nombre as pais
			from g_dossier_plaguicida.solicitudes_manufacturadores f
			left join g_catalogos.localizacion l on f.id_pais=l.id_localizacion
			where f.id_solicitud_manufacturador=$id_solicitud_manufacturador;");
		while ($fila = pg_fetch_assoc($query)){
			$res = $fila;
		}
		return $res;
	}
	

	public function agregarManufacturador($conexion,$id_solicitud_fabricante,$nombre,$id_pais,$direccion,$representante_legal,$correo,$telefono){
		$res=array();
		//verifico si ya existe
		$esNuevo=false;
		$sql="select * from g_dossier_plaguicida.solicitudes_manufacturadores WHERE id_solicitud_fabricante=$id_solicitud_fabricante AND nombre='$nombre' AND id_pais=$id_pais;";
		$res = $conexion->ejecutarConsulta($sql);
		if(pg_num_rows($res)<1){
			$esNuevo=true;
			$sql="INSERT INTO g_dossier_plaguicida.solicitudes_manufacturadores (id_solicitud_fabricante,nombre,id_pais,direccion,representante_legal,correo,telefono)
VALUES ($id_solicitud_fabricante,'$nombre',$id_pais,'$direccion','$representante_legal','$correo','$telefono')
RETURNING id_solicitud_manufacturador;";
		}else{
			$sql="UPDATE g_dossier_plaguicida.solicitudes_manufacturadores set  direccion='$direccion',representante_legal='$representante_legal',correo='$correo',telefono='$telefono'
WHERE id_solicitud_fabricante=$id_solicitud_fabricante AND nombre='$nombre' AND id_pais=$id_pais;";
		}
		$resa = $conexion->ejecutarConsulta($sql);
		$resultado=array();
		if($esNuevo==true){
			$resultado['tipo']='insert';
			$resultado['id_solicitud_manufacturador']=pg_fetch_result($resa, 0, 0);
		}
		else{
			$resultado['tipo']='update';
			$resultado['id_solicitud_manufacturador']=pg_fetch_result($res, 0, 'id_solicitud_manufacturador');
		}
		return $resultado;
	}
	public function eliminarManufacturador($conexion, $id_solicitud_manufacturador){
		//borrar
		$sql="DELETE FROM g_dossier_plaguicida.solicitudes_manufacturadores WHERE id_solicitud_manufacturador=$id_solicitud_manufacturador;";
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
	
	public function imprimirLineaFabricante($item){
		$fila='<tr id="R' . $item['id_solicitud_fabricante'] . '">' .
			'<td width="30%">' .
					$item['nombre'] .' - '.$item['pais'].
			'</td>' .
			'<td width="30%">' .
					$item['direccion'].
			'</td>' .
			'<td width="20%">' .
					$item['representante_legal'].
			'</td>' .
			'<td width="15%">' .
					$item['correo'].
			'</td>' .
			'<td width="5%">' .
					$item['telefono'].
			'</td>' .

			'<td>' .
			'<form class="abrir" data-rutaAplicacion="dossierPlaguicida" data-opcion="abrirFabricante" data-destino="detalleItem" data-accionEnExito="NADA" >' .
			'<input type="hidden" id="id_solicitud_fabricante" name="id_solicitud_fabricante" value="' . $item['id_solicitud_fabricante'] . '" >' .
			'<button class="icono obsFabricantes obsFormuladores" type="submit" ></button>' .
			'</form>' .
			'</td>' .
			'<td>' .
			'<form class="borrar" data-rutaAplicacion="dossierPlaguicida" data-opcion="guardarFabricante">' .
			'<input type="hidden" id="id_solicitud_fabricante" name="id_solicitud_fabricante" value="' . $item['id_solicitud_fabricante'] . '" >' .
			'<input type="hidden" id="paso_opcion" name="paso_opcion" value="borrar" />'.
			'<button type="submit" class="icono obsFabricantes obsFormuladores"></button>' .
			'</form>' .
			'</td>' .
			'</tr>';
		return $fila;
	}
	
	public function imprimirLineaManufacturador($item){
		$fila='<tr id="R' . $item['id_solicitud_manufacturador'] . '">' .
			'<td width="30%">' .
					$item['nombre'] .' - '.$item['pais'].
			'</td>' .
			'<td width="30%">' .
					$item['direccion'].
			'</td>' .
			'<td width="20%">' .
					$item['representante_legal'].
			'</td>' .
			'<td width="15%">' .
					$item['correo'].
			'</td>' .
			'<td width="5%">' .
					$item['telefono'].
			'</td>' .

			'<td>' .
			'<form class="abrir" data-rutaAplicacion="dossierPlaguicida" data-opcion="abrirFabricanteItem" data-destino="detalleItem" data-accionEnExito="NADA" >' .
			'<input type="hidden" id="id_solicitud_manufacturador" name="id_solicitud_manufacturador" value="' . $item['id_solicitud_manufacturador'] . '" >' .
			'<button class="icono" type="submit" ></button>' .
			'</form>' .
			'</td>' .
			'<td>' .
			'<form class="borrar" data-rutaAplicacion="dossierPlaguicida" data-opcion="guardarFabricante">' .
			'<input type="hidden" id="id_solicitud_manufacturador" name="id_solicitud_manufacturador" value="' . $item['id_solicitud_manufacturador'] . '" >' .
			'<input type="hidden" id="paso_opcion" name="paso_opcion" value="borrarManufacturador" />'.
			'<button type="submit" class="icono"></button>' .
			'</form>' .
			'</td>' .
			'</tr>';
		return $fila;
	}
	//**********************************************	 INGREDIENTES ACTIVOS ************************************************************
	public function obtenerIngredienteSolicitudDeclarado ($conexion, $id_solicitud,$id_ingrediente_activo){
		$query = $conexion->ejecutarConsulta("select pr.* from g_dossier_plaguicida.solicitudes_ia pr where pr.id_solicitud = $id_solicitud AND id_ingrediente_activo=$id_ingrediente_activo;");
		$res=array();
		while ($fila = pg_fetch_assoc($query)){
			$res = $fila;
		}
		return $res;
	}
	public function obtenerIngredienteSolicitudProtocolo ($conexion, $id_solicitud,$id_protocolo_ia){
		$query = $conexion->ejecutarConsulta("select pr.* from g_dossier_plaguicida.solicitudes_ia pr where pr.id_solicitud = $id_solicitud AND id_protocolo_ia=$id_protocolo_ia;");
		$res=array();
		while ($fila = pg_fetch_assoc($query)){
			$res = $fila;
		}
		return $res;
	}
	public function obtenerIngredientesSolicitud ($conexion, $id_solicitud){
		$query = $conexion->ejecutarConsulta("select pr.*,ia.ingrediente_activo,pia.concentracion,cu.codigo as unidad,cu.codigo,ia.grupo_quimico
			from g_dossier_plaguicida.solicitudes_ia pr
			left join g_ensayo_eficacia.protocolo_ia pia on pr.id_protocolo_ia=pia.id_protocolo_ia
			left join g_catalogos.unidades_medidas cu on pia.id_unidad=cu.id_unidad_medida
			left join g_catalogos.ingrediente_activo_inocuidad ia on pr.id_ingrediente_activo=ia.id_ingrediente_activo
			where pr.id_solicitud = $id_solicitud;");
		$res=array();
		while ($fila = pg_fetch_assoc($query)){
			$res[] = $fila;
		}
		return $res;
	}
	public function guardarIngredientesSolicitud($conexion,$datos){
		$sql="";
		$tieneItems=false;
		$valorChequeado="";
		$tipo="";
		if(in_array('id_solicitud_ia', array_keys($datos)))
		{
			//actualizar
			$tipo="update";
			$iden=$datos['id_solicitud_ia'];
			$sql="UPDATE  g_dossier_plaguicida.solicitudes_ia
set ";
			foreach ($datos as $id => $valor)
			{
				if($id=='id_solicitud_ia')
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
id_solicitud_ia=$iden;";
		}
		else
		{
			//insertar
			$tipo="insert";
			$sql="INSERT INTO g_dossier_plaguicida.solicitudes_ia(";
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
			$sql.=" RETURNING id_solicitud_ia;";
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
	public function eliminarIngredientesSolicitud($conexion, $id_solicitud_ia){
		//borrar
		$sql="DELETE FROM g_dossier_plaguicida.solicitudes_ia WHERE id_solicitud_ia=$id_solicitud_ia;";
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
	//******************************************** PRESENTACIONES **************************************************
	public function agregarPresentacion($conexion,$id_solicitud,$presentacion_tipo,$cantidad,$id_unidad_medida,$partida_arancelaria,$codigo_complementario,$codigo_suplementario){
		//verifico si ya existe
		$esNuevo=false;
		$sql="select * from g_dossier_plaguicida.solicitudes_presentaciones WHERE id_solicitud=$id_solicitud AND presentacion_tipo='$presentacion_tipo' AND cantidad=$cantidad;";
		$res = $conexion->ejecutarConsulta($sql);
		if(pg_num_rows($res)<1){
			$sql="INSERT INTO g_dossier_plaguicida.solicitudes_presentaciones (id_solicitud,presentacion_tipo,cantidad,id_unidad_medida,partida_arancelaria,codigo_complementario,codigo_suplementario)
VALUES ($id_solicitud,'$presentacion_tipo',$cantidad,$id_unidad_medida,'$partida_arancelaria','$codigo_complementario','$codigo_suplementario')
RETURNING id_solicitud_presentacion;";
			$esNuevo=true;
		}else{
			$sql="UPDATE g_dossier_plaguicida.solicitudes_presentaciones set  id_unidad_medida=$id_unidad_medida,
partida_arancelaria='$partida_arancelaria',codigo_complementario='$codigo_complementario',codigo_suplementario='$codigo_suplementario'
WHERE id_solicitud=$id_solicitud AND presentacion_tipo='$presentacion_tipo' AND cantidad=$cantidad;";
		}
		$resa = $conexion->ejecutarConsulta($sql);
		if($esNuevo==true){
			return pg_fetch_result($resa, 0, 0);
		}
		else{
			return pg_fetch_result($res, 0, 'id_solicitud_presentacion');
		}
		
	}
	public function obtenerPresentaciones($conexion, $id_solicitud){
		$query = $conexion->ejecutarConsulta("select pr.* ,u.codigo, u.nombre as unidad_medida, c.nombre as presentacion_nombre from g_dossier_plaguicida.solicitudes_presentaciones pr
			left join g_catalogos.unidades_medidas u on pr.id_unidad_medida=u.id_unidad_medida
			left join g_catalogos.catalogo_ef c on c.codigo=pr.presentacion_tipo
			where pr.id_solicitud = $id_solicitud;");
		$res=array();
		while ($fila = pg_fetch_assoc($query)){
			$res[] = $fila;
		}
		return $res;
	}
	public function eliminarPresentacion($conexion, $id_solicitud_presentacion){
		//borrar
		$sql="DELETE FROM g_dossier_plaguicida.solicitudes_presentaciones WHERE id_solicitud_presentacion=$id_solicitud_presentacion;";
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
	//*****************************  INGREDIENTES ACTIVOS, DETALLES  ****************************************************
	public function obtenerAditivosToxicologicos($conexion,$id_solicitud){
		$res=array();
		$query = $conexion->ejecutarConsulta("select at.*,um.codigo,um.nombre as nombre_medida
from g_dossier_plaguicida.solicitudes_aditivos at
left join g_catalogos.unidades_medidas um on at.id_unidad=um.id_unidad_medida
where at.id_solicitud=$id_solicitud;");
		while ($fila = pg_fetch_assoc($query)){
			$res[] = $fila;
		}
		return $res;
	}
	public function agregarAditivoToxicologico($conexion,$id_solicitud,$nombre,$cantidad,$id_unidad){
		$res=array();
		//verifico si ya existe
		$sql="select * from g_dossier_plaguicida.solicitudes_aditivos WHERE id_solicitud=$id_solicitud AND nombre='$nombre';";
		$res = $conexion->ejecutarConsulta($sql);
		if(pg_num_rows($res)<1){
			$sql="INSERT INTO g_dossier_plaguicida.solicitudes_aditivos (id_solicitud,nombre,cantidad,id_unidad)
VALUES ($id_solicitud,'$nombre',$cantidad,$id_unidad)
RETURNING id_solicitud_aditivo;";
		}else{
			$sql="UPDATE g_dossier_plaguicida.solicitudes_aditivos set  cantidad=$cantidad,id_unidad=$id_unidad
WHERE id_solicitud=$id_solicitud AND nombre='$nombre';";
		}
		$res = $conexion->ejecutarConsulta($sql);
		return $res;
	}
	public function eliminarAditivoToxicologico($conexion, $id_solicitud_aditivo){
		//borrar
		$sql="DELETE FROM g_dossier_plaguicida.solicitudes_aditivos WHERE id_solicitud_aditivo=$id_solicitud_aditivo;";
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
	public function imprimirAditivoToxicologico($id_solicitud,$id_solicitud_aditivo, $nombre,$cantidad, $unidad){
		return '<tr id="R_IA_AD_' . $id_solicitud_aditivo.'">' .
		'<td>' .$nombre.'</td>'.
		'<td>' .$cantidad.'</td>'.
		'<td>' .strtolower( $unidad).'</td>'.
		'<td>' .
		'<form class="borrar" data-rutaAplicacion="dossierPlaguicida" data-opcion="borrarAditivoToxicologico">' .
		'<input type="hidden" id="id_solicitud" name="id_solicitud" value="' . $id_solicitud . '" >' .
		'<input type="hidden" id="id_solicitud_aditivo" name="id_solicitud_aditivo" value="' . $id_solicitud_aditivo . '" >' .
		'<button type="button" class="icono btnBorrarAditivoToxicologico obsAditivosToxicos"></button>' .
		'</form>' .
		'</td>' .
		'</tr>';
	}
	public function imprimirAditivosToxicologicos($conexion,$id_solicitud){
		$items=$this->obtenerAditivosToxicologicos($conexion,$id_solicitud);
		$str='';
		foreach($items as $value){
			$str=$str.$this->imprimirAditivoToxicologico($value['id_solicitud'],$value['id_solicitud_aditivo'],$value['nombre'],$value['cantidad'],$value['nombre_medida']);
		}
		return $str;
	}
	//*****************************  ANEXOS  ****************************************************
	public function obtenerArchivosAnexos($conexion,$id_solicitud){
		$res=array();
		$query = $conexion->ejecutarConsulta("
select ax.* ,ce.nombre as tipo_anexo from g_dossier_plaguicida.solicitud_anexos ax
left join g_catalogos.catalogo_ef_ex ce on ce.codigo=ax.tipo
where id_solicitud=$id_solicitud;");
		while ($fila = pg_fetch_assoc($query)){
			$res[] = $fila;
		}
		return $res;
	}
	public function imprimirArchivosAnexos($conexion,$id_solicitud){
		$archivos=$this->obtenerArchivosAnexos($conexion,$id_solicitud);
		$fila='';
		foreach($archivos as $key=>$value){
			$fila=$fila.
'<tr>'.
'<td>'.$value['tipo_anexo'].'</td>'.
'<td><a href="'.$value['path'].'" target="_blank">'.$value['referencia'].'</a></td>'.
'<td>'.
'<form id="borrarAnexoDG" class="borrar" data-rutaAplicacion="dossierPlaguicida" data-opcion=""  >'.
'<input type="hidden" id="id_solicitud_anexo" name="id_solicitud_anexo" value="'.$value['id_solicitud_anexo'].'" >'.
'<button type="button" class="icono btnBorraFilaArchivoAnexo obsArchivosAnexos"></button>'.
'</form>'.
'</td>'.
'</tr>';
		}
		return $fila;
	}
	public function agregarArchivoAnexo($conexion, $id_solicitud,$archivo,$referencia,$fase,$usuario,$tipo){
		$sql="select ax.* from g_dossier_plaguicida.solicitud_anexos ax where id_solicitud=$id_solicitud and lower(trim(referencia))=lower(trim('$referencia'));";
		$resultado=array();
		$tipoAccion='insert';
		$res = $conexion->ejecutarConsulta($sql);
		if(pg_num_rows($res)<1){
			$sql="INSERT INTO g_dossier_plaguicida.solicitud_anexos (id_solicitud,path,referencia,fase,usuario,tipo)
VALUES ($id_solicitud,'$archivo','$referencia','$fase','$usuario','$tipo')
RETURNING id_solicitud_anexo;";
		}else{
			$sql="UPDATE g_dossier_plaguicida.solicitud_anexos set  path='$archivo',fase='$fase',usuario='$usuario',tipo='$tipo'
WHERE id_solicitud=$id_solicitud and lower(trim(referencia))=lower(trim('$referencia'));";
			$tipoAccion='update';
		}
		$res = $conexion->ejecutarConsulta($sql);
		$filasTemp=array();
		while ($fila = pg_fetch_assoc($res)){
			$filasTemp[] = $fila;
		}
		$resultado=array();
		$resultado['tipo']=$tipoAccion;
		$resultado['resultado']=	$filasTemp;
		return $resultado;
	}
	public function eliminarArchivoAnexo($conexion, $id_solicitud_anexo){
		//borrar
		$sql="DELETE FROM g_dossier_plaguicida.solicitud_anexos WHERE id_solicitud_anexo=$id_solicitud_anexo;";
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
	//***************************** MODIFICACIONES ***************************************************
	public function listarModificacionesOperador ($conexion, $identificador){
		$res = $conexion->ejecutarConsulta("select
pr.*
from
g_dossier_plaguicida.modificaciones pr
where
pr.identificador = '$identificador';");
		return $res;
	}
	public function guardarModificaciones($conexion,$datos){
		$sql="";
		$tieneItems=false;
		$valorChequeado="";
		$tipo="";
		if(in_array('id_modificacion', array_keys($datos)))
		{
			//actualizar
			$tipo="update";
			$iden=$datos['id_modificacion'];
			$sql="UPDATE  g_dossier_plaguicida.modificaciones
set ";
			foreach ($datos as $id => $valor)
			{
				if($id=='id_modificacion')
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
id_modificacion=$iden;";
		}
		else
		{
			//insertar
			$tipo="insert";
			$sql="INSERT INTO g_dossier_plaguicida.modificaciones(";
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
			$sql.=" RETURNING id_modificacion;";
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
	public function obtenerModificacion ($conexion, $id_modificacion){
		$query = $conexion->ejecutarConsulta("select
pr.*
from
g_dossier_plaguicida.modificaciones pr
where
pr.id_modificacion = $id_modificacion;");
		return pg_fetch_assoc($query);
	}
	public function eliminarModificacion($conexion, $id_modificaciones){
		//borrar
		$sql="DELETE FROM g_dossier_plaguicida.modificaciones WHERE id_modificacion in ($id_modificaciones);";
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
	//**************************** MODIFICACIONES FABRICANES **************************************************************************
	public function actualizarFabricanteModificacion($conexion,$id_modificacion,$tipo_fabricante,$nombre,$id_pais,$direccion,$representante_legal,$correo,$telefono,$carta){
		//verifico si ya existe
		$esNuevo=false;
		$sql="select * from g_dossier_plaguicida.modificaciones_fabricantes WHERE id_modificacion=$id_modificacion AND tipo_fabricante='$tipo_fabricante' AND nombre='$nombre';";
		$res = $conexion->ejecutarConsulta($sql);
		if(pg_num_rows($res)<1){
			$sql="INSERT INTO g_dossier_plaguicida.modificaciones_fabricantes (id_modificacion,tipo_fabricante,nombre,id_pais,direccion,representante_legal,correo,telefono,carta)
VALUES ($id_modificacion,'$tipo_fabricante','$nombre',$id_pais,'$direccion','$representante_legal','$correo','$telefono','$carta')
RETURNING id_modificacion_fabricante;";
			$esNuevo=true;
		}else{
			$sql="UPDATE g_dossier_plaguicida.modificaciones_fabricantes set  id_pais=$id_pais,direccion='$direccion',representante_legal='$representante_legal',correo='$correo',telefono='$telefono',
carta='$carta'
WHERE id_modificacion=$id_modificacion AND tipo_fabricante='$tipo_fabricante' AND nombre='$nombre';";
		}
		$resa = $conexion->ejecutarConsulta($sql);
		if($esNuevo==true){
			return pg_fetch_result($resa, 0, 0);
		}
		else{
			return pg_fetch_result($res, 0, 'id_modificacion_fabricante');
		}
	}
	public function obtenerFabricanteModificacion($conexion,$id_modificacion_fabricante){
		$res=array();
		$query = $conexion->ejecutarConsulta("select f.*, l.nombre as pais
from g_dossier_plaguicida.modificaciones_fabricantes f
left join g_catalogos.localizacion l on f.id_pais=l.id_localizacion
where f.id_modificacion_fabricante=$id_modificacion_fabricante;");
		while ($fila = pg_fetch_assoc($query)){
			$res[] = $fila;
		}
		if(sizeof($res)>0){
			$res=$res[0];
		}
		return $res;
	}
	public function obtenerFabricantesModificacion($conexion,$id_modificacion,$tipo_fabricante){
		$res=array();
		$query = $conexion->ejecutarConsulta("select f.*, l.nombre as pais
from g_dossier_plaguicida.modificaciones_fabricantes f
left join g_catalogos.localizacion l on f.id_pais=l.id_localizacion
where f.id_modificacion=$id_modificacion AND f.tipo_fabricante='$tipo_fabricante';");
		while ($fila = pg_fetch_assoc($query)){
			$fila['manufacturadores']=$this->obtenerManufacturadoresModificacion($conexion,$fila['id_modificacion_fabricante']);
			//coloca los manufacturadores
			$res[] = $fila;
		}
		return $res;
	}
	public function agregarFabricanteModificacion($conexion,$id_modificacion,$tipo_fabricante,$identificador,$id_pais,$direccion,$representante_legal,$correo,$telefono){
		$res=array();
		//verifico si ya existe
		$sql="select * from g_dossier_plaguicida.modificaciones_fabricantes WHERE id_modificacion=$id_modificacion AND tipo_fabricante='$tipo_fabricante' AND identificador='$identificador';";
		$res = $conexion->ejecutarConsulta($sql);
		if(pg_num_rows($res)<1){
			$sql="INSERT INTO g_dossier_plaguicida.modificaciones_fabricantes (id_modificacion,tipo_fabricante,identificador,id_pais,direccion,representante_legal,correo,telefono)
VALUES ($id_modificacion,'$tipo_fabricante','$identificador',$id_pais,'$direccion','$representante_legal','$correo','$telefono')
RETURNING id_modificacion_fabricante;";
		}else{
			$sql="UPDATE g_dossier_plaguicida.modificaciones_fabricantes set  id_pais=$id_pais,direccion='$direccion',representante_legal='$representante_legal',correo='$correo',telefono='$telefono'
WHERE id_modificacion=$id_modificacion AND tipo_fabricante='$tipo_fabricante' AND identificador='$identificador';";
		}
		$res = $conexion->ejecutarConsulta($sql);
		//recupera los fabricantes
		$res=$this->obtenerFabricantesModificacion($conexion,$id_modificacion,$tipo_fabricante);
		return $res;
	}
	public function eliminarFabricanteModificacion($conexion, $id_modificacion_fabricante){
		//borrar
		$sql="DELETE FROM g_dossier_plaguicida.modificaciones_fabricantes WHERE id_modificacion_fabricante=$id_modificacion_fabricante;";
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
	//**********************************************	MODIFICACIONES MANUFACTURADORES ************************************************************
	public function obtenerManufacturadoresmodificacion($conexion,$id_modificacion_fabricante){
		$res=array();
		$query = $conexion->ejecutarConsulta("select f.*, l.nombre as pais
from g_dossier_plaguicida.modificaciones_manufacturadores f
left join g_catalogos.localizacion l on f.id_pais=l.id_localizacion
where f.id_modificacion_fabricante=$id_modificacion_fabricante;");
		while ($fila = pg_fetch_assoc($query)){
			$res[] = $fila;
		}
		return $res;
	}
	public function agregarManufacturadormodificacion($conexion,$id_modificacion_fabricante,$nombre,$id_pais,$direccion,$representante_legal,$correo,$telefono){
		$res=array();
		//verifico si ya existe
		$sql="select * from g_dossier_plaguicida.modificaciones_manufacturadores WHERE id_modificacion_fabricante=$id_modificacion_fabricante AND nombre='$nombre' AND id_pais=$id_pais;";
		$res = $conexion->ejecutarConsulta($sql);
		if(pg_num_rows($res)<1){
			$sql="INSERT INTO g_dossier_plaguicida.modificaciones_manufacturadores (id_modificacion_fabricante,nombre,id_pais,direccion,representante_legal,correo,telefono)
VALUES ($id_modificacion_fabricante,'$nombre',$id_pais,'$direccion','$representante_legal','$correo','$telefono')
RETURNING id_modificacion_manufacturador;";
		}else{
			$sql="UPDATE g_dossier_plaguicida.modificaciones_manufacturadores set  direccion='$direccion',representante_legal='$representante_legal',correo='$correo',telefono='$telefono'
WHERE id_modificacion_fabricante=$id_modificacion_fabricante AND nombre='$nombre' AND id_pais=$id_pais;";
		}
		$res = $conexion->ejecutarConsulta($sql);
		//recupera los fabricantes
		$res=$this->obtenerManufacturadores($conexion,$id_modificacion_fabricante);
		return $res;
	}
	public function eliminarManufacturadorModificacion($conexion, $id_modificacion_manufacturador){
		//borrar
		$sql="DELETE FROM g_dossier_plaguicida.modificaciones_manufacturadores WHERE id_modificacion_manufacturador=$id_modificacion_manufacturador;";
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
			$sql="UPDATE  g_dossier_plaguicida.etiquetas
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
			$sql="INSERT INTO g_dossier_plaguicida.etiquetas(";
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
			g_dossier_plaguicida.etiquetas pr
			where
			pr.id_solicitud = $idSolicitud;");
		return pg_fetch_assoc($query);
	}
	public function eliminarEtiquetaSolicitud($conexion, $idSolicitud){
		//borrar
		$sql="DELETE FROM g_dossier_plaguicida.etiquetas WHERE id_solicitud in ($idSolicitud);";
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
	//*********************************** FLUJOS ****************************************************************************************

	public function obtenerFlujosDeTramitesSolicitudDG($conexion,$identificador,$id_fase,$idSolicitud,$esta_procesado='N'){
			$sql="select t.id_tramite,t.tipo_documento,t.id_documento,t.id_division,p.identificador as operador,p.estado,o.razon_social,p.producto_nombre,p.producto_nombre as nombre,p.id_expediente,p.id_certificado,tf.*
			from g_ensayo_eficacia.tramites_flujos tf
			left join g_ensayo_eficacia.tramites t on tf.id_tramite=t.id_tramite
			left join g_dossier_plaguicida.solicitudes p on t.id_documento=p.id_solicitud
			left join g_operadores.operadores o on p.identificador=o.identificador
			left join g_ensayo_eficacia.flujo_documentos fd on tf.id_flujo_documento=fd.id_flujo_documento
			WHERE t.tipo_documento='DG' AND fd.id_fase=$id_fase AND tf.identificador ='$identificador' AND tf.pendiente!='$esta_procesado' AND p.id_solicitud=$idSolicitud;";
			return $conexion->ejecutarConsulta($sql);

		}
	
	public function obtenerFlujosDeTramitesAsignarDossierDG($conexion,$identificador=null,$id_fase,$perfil=null,$esta_procesado='N'){
		$sql="select t.tipo_documento,t.id_documento,t.id_division,p.identificador as operador,p.estado,o.razon_social,p.producto_nombre,p.producto_nombre as nombre,p.id_expediente,tf.*
from g_ensayo_eficacia.tramites_flujos tf
left join g_ensayo_eficacia.tramites t on tf.id_tramite=t.id_tramite
left join g_dossier_plaguicida.solicitudes p on t.id_documento=p.id_solicitud
left join g_operadores.operadores o on p.identificador=o.identificador
left join g_ensayo_eficacia.flujo_documentos fd on tf.id_flujo_documento=fd.id_flujo_documento
WHERE t.tipo_documento='DG' AND fd.id_fase=$id_fase  AND tf.pendiente!='$esta_procesado' ";
		if($identificador!=null)
			$sql=$sql."  AND tf.identificador='$identificador' ";
		if($perfil!=null)
			$sql=$sql."  AND tf.identificador='$perfil' ";
		$sql=$sql.";";
		return $conexion->ejecutarConsulta($sql);
	}
	public function obtenerTramiteFlujoDG($conexion,$id_tramite_flujo){
		$sql="select tf.*,p.identificador as operador from g_ensayo_eficacia.tramites_flujos tf
left join g_ensayo_eficacia.tramites t on t.id_tramite=tf.id_tramite
left join g_dossier_plaguicida.solicitudes p on t.id_documento=p.id_solicitud
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
	public function obtenerFlujosDeTramitesParaAsingnarDG($conexion,$identificador,$id_fase,$esta_procesado='N'){
		$sql="select t.tipo_documento,t.id_documento,t.id_division,p.identificador as operador,p.estado,o.razon_social,p.producto_nombre,p.producto_nombre as nombre,p.id_expediente,tf.*
from g_ensayo_eficacia.tramites_flujos tf
left join g_ensayo_eficacia.tramites t on tf.id_tramite=t.id_tramite
left join g_dossier_plaguicida.solicitudes p on t.id_documento=p.id_solicitud
left join g_operadores.operadores o on p.identificador=o.identificador
left join g_ensayo_eficacia.flujo_documentos fd on tf.id_flujo_documento=fd.id_flujo_documento
inner join g_estructura.funcionarios fr on tf.identificador=fr.identificador
WHERE fr.activo=1 AND fr.identificador='$identificador' AND  fd.id_fase=$id_fase  AND tf.pendiente!='$esta_procesado';";
		return $conexion->ejecutarConsulta($sql);
	}
	public function obtenerSolicitudEtiquetaXevaluar($conexion,$identificador,$id_fase,$esta_procesado='T',$estadoEtiqueta='aprobarEtiqueta'){
		$sql="select t.tipo_documento,t.id_documento,t.id_division,p.identificador as operador,p.estado,o.razon_social,p.producto_nombre,p.producto_nombre as nombre,p.id_expediente,tf.*,
e.ruta,p.mae_comentario,p.mae_ruta,p.salud_comentario,p.salud_ruta
from g_ensayo_eficacia.tramites_flujos tf
left join g_ensayo_eficacia.tramites t on tf.id_tramite=t.id_tramite
left join g_dossier_plaguicida.solicitudes p on t.id_documento=p.id_solicitud
left join g_operadores.operadores o on p.identificador=o.identificador
left join g_ensayo_eficacia.flujo_documentos fd on tf.id_flujo_documento=fd.id_flujo_documento
inner join g_estructura.funcionarios fr on tf.ejecutor=fr.identificador
inner join g_dossier_plaguicida.etiquetas e on e.id_solicitud=p.id_solicitud
WHERE t.tipo_documento='DG' and fr.activo=1 AND fr.identificador='$identificador' AND  fd.id_fase=$id_fase  AND tf.pendiente='$esta_procesado' and e.estado='$estadoEtiqueta';";
		return $conexion->ejecutarConsulta($sql);
	}
	public function obtenerSolicitudParaOrganismosExternos($conexion,$noStatus='A',$noPendiente='N'){
		$sql="select t.tipo_documento,t.id_documento,t.id_division,p.identificador as operador,p.estado,o.razon_social,p.producto_nombre,p.producto_nombre as nombre,p.id_expediente,tf.*,
p.mae_comentario,p.mae_ruta,p.mae_estado,p.salud_comentario,p.salud_ruta,p.salud_estado
from g_ensayo_eficacia.tramites_flujos tf
left join g_ensayo_eficacia.tramites t on tf.id_tramite=t.id_tramite
left join g_dossier_plaguicida.solicitudes p on t.id_documento=p.id_solicitud
left join g_operadores.operadores o on p.identificador=o.identificador
left join g_ensayo_eficacia.flujo_documentos fd on tf.id_flujo_documento=fd.id_flujo_documento
WHERE t.tipo_documento='DG'   AND  fd.id_fase between 4 and 9  AND t.status!='$noStatus' and tf.pendiente!='$noPendiente' ;";
		return $conexion->ejecutarConsulta($sql);
	}
	public function obtenerFlujosDeTramitesDelOperadorDG($conexion,$identificador,$id_fase,$esta_procesado='N'){
		$sql="select t.tipo_documento,t.id_documento,t.id_division,p.identificador as operador,p.estado,o.razon_social,p.producto_nombre,p.producto_nombre as nombre,p.id_expediente,tf.*
from g_ensayo_eficacia.tramites_flujos tf
left join g_ensayo_eficacia.tramites t on tf.id_tramite=t.id_tramite
left join g_dossier_plaguicida.solicitudes p on t.id_documento=p.id_solicitud
left join g_operadores.operadores o on p.identificador=o.identificador
left join g_ensayo_eficacia.flujo_documentos fd on tf.id_flujo_documento=fd.id_flujo_documento
WHERE t.tipo_documento='DG' and fd.id_fase=$id_fase  AND tf.identificador ='$identificador' AND tf.pendiente='$esta_procesado';";
		return $conexion->ejecutarConsulta($sql);
	}
	//**************************************** GENERACION DE ENCABEZADO DE CORREOS *******************************************
	public function redactarNotificacionEmailPG($conexion,$id_tramite, $fecha,$asunto){
		$sql="select t.*,p.identificador as operador,p.producto_nombre,p.id_expediente,o.correo, o.razon_social,o.nombre_representante,o.apellido_representante
from g_ensayo_eficacia.tramites t
left join g_dossier_plaguicida.solicitudes p on t.id_documento=p.id_solicitud
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
		$s.='Información de la solicitud: ';
		$s.='<p/>';
		$s.='Fecha :'.$fecha;
		$s.='<br/>';
		$s.='No. de Solicitud:'.$res['id_expediente'];
		$s.='<br/>';
		$s.='Asunto: '.$asunto.' - '.$res['producto_nombre'];
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

	//********************************* REPORTES **********************************************

	public function obtenerRegistrosPlaguicidas($conexion,$fechaDesde=null,$fechaHasta=null){
		$sql="select p.id_solicitud,p.identificador,p.id_expediente,p.id_certificado,p.estado,p.producto_nombre as nombre_producto,
			o.razon_social,'' as sitio,cef.nombre as provincia,'' as subtipo_producto,p.fecha_solicitud as fecha_inicio,p.fecha_inscripcion as fecha_registro
			from g_dossier_plaguicida.solicitudes p
			left join g_operadores.operadores o on p.identificador=o.identificador			
			left join g_ensayo_eficacia.tramites t on t.id_documento=p.id_solicitud and t.tipo_documento='DG'
			left join g_catalogos.catalogo_ef cef on cef.codigo=t.id_division and cef.clase='DIVISION'
			order by p.fecha_solicitud;";

		return $conexion->ejecutarConsulta($sql);
		
	}
	
	public function obtenerMatrizServicioPlaguicidas($conexion,$fechaDesde=null,$fechaHasta=null){
		 
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
			from g_dossier_plaguicida.solicitudes p
			left join g_operadores.operadores o on p.identificador=o.identificador
			
			
			left join g_catalogos.catalogo_ef ce on ce.codigo=p.motivo
			left join g_ensayo_eficacia.tramites tt on tt.id_documento=p.id_solicitud and tt.tipo_documento='DG'
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
												 g_dossier_plaguicida.solicitudes
											where
												estado = '$estado';");
	    return $res;
	    
	}
	
	//FIN EJAR
	//Fin controlador
}
