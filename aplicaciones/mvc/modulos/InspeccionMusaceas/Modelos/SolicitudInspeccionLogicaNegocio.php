<?php
 /**
 * Lógica del negocio de SolicitudInspeccionModelo
 *
 * Este archivo se complementa con el archivo SolicitudInspeccionControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-09-18
 * @uses    SolicitudInspeccionLogicaNegocio
 * @package InspeccionMusaceas
 * @subpackage Modelos
 */
  namespace Agrodb\InspeccionMusaceas\Modelos;
  
class SolicitudInspeccionLogicaNegocio implements IModelo 
{

	 private $modeloSolicitudInspeccion = null;
	 private $modeloTemporalProductores = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloSolicitudInspeccion = new SolicitudInspeccionModelo();
	 $this->modeloTemporalProductores = new TemporalProductoresModelo();
	 
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new SolicitudInspeccionModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdSolicitudInspeccion() != null && $tablaModelo->getIdSolicitudInspeccion() > 0) {
		return $this->modeloSolicitudInspeccion->actualizar($datosBd, $tablaModelo->getIdSolicitudInspeccion());
		} else {
		unset($datosBd["id_solicitud_inspeccion"]);
		return $this->modeloSolicitudInspeccion->guardar($datosBd);
	}
	}
	/**
	 * Guarda el registro actual y detalle
	 * @param array $datos
	 * @return int
	 */
	public function guardarSolicitud(Array $datos)
	{
	    try{
	        $this->modeloSolicitudInspeccion = new SolicitudInspeccionModelo();
	        $proceso = $this->modeloSolicitudInspeccion->getAdapter()
	        ->getDriver()
	        ->getConnection();
	        if (! $proceso->beginTransaction()){
	            throw new \Exception('No se pudo iniciar la transacción: Guardar solicitud y detalle');
	        }
	        
    	    $paisDestino=$puertoEmbarque='';
    	    $combo = $this->obtenerPaises();
    	    foreach ($combo as $fila) {
    	        if($fila['id_localizacion']==$datos['pais_destino']){
    	            $paisDestino=$fila['nombre'];
    	        }
    	    }
    	    $combo = $this->obtenerPuertos(66);
    	   
    	    foreach ($combo as $fila) {
    	        if($fila['id_puerto']==$datos['puerto_embarque']){
    	            $puertoEmbarque=$fila['nombre_puerto'];
    	        }
    	    }
    	    
    	    $idPuertoEmbarque = $datos['puerto_embarque'];
    	    $datos['pais_destino']=$paisDestino;
    	    $datos['puerto_embarque']=$puertoEmbarque;  
    	    //**
    	    $lnegocioTemporalProductore = new TemporalProductoresLogicaNegocio();
    	    $idTem = explode('.',$datos['numCajas'][0]);
    	    $this->modeloTemporalProductores = $lnegocioTemporalProductore->buscar($idTem[0]);  
    	    $codProvincia =  $provincia="";
    	    if($datos['lugar_inspeccion']=='Puerto'){
    	        $combo = $this->obtenerProvincia($idPuertoEmbarque);
    	        foreach ($combo as $fila) {
    	                $codProvincia=$fila['codigo_vue'];
    	                $provincia = $fila["provincia"];
    	        }
    	    }else{
    	        $codProvincia =  $this->modeloTemporalProductores->getCodProvincia();
    	        $provincia = $this->modeloTemporalProductores->getProvincia();
    	    }
    	    
    	    $secuencial = $this->obtenerSecuencialSolicitud();
    	    $secuencialSolicitud = str_pad($secuencial->current()->numero, 5, "0", STR_PAD_LEFT);
    	    $codigoSolicitud = date("my").'-'.$codProvincia.'-'.$secuencialSolicitud;
    	    $datos['codigo_solicitud']=$codigoSolicitud;
    	    $datos['identificador']=$_SESSION['usuario'];
    	    $datos['identificador_registro']=$_SESSION['usuario'];
    	    $datos['provincia'] = $provincia;
	        $tablaModelo = new SolicitudInspeccionModelo($datos);
	        $datosBd = $tablaModelo->getPrepararDatos();
	        unset($datosBd["id_solicitud_inspeccion"]);
	        $idSolicitud = $this->modeloSolicitudInspeccion->guardar($datosBd);
	        if (!$idSolicitud)
	        {
	            throw new \Exception('No se registo los datos en la tabla solicitud inspeccion');
	        }
	        //*************guadar detalle de solicitud*************
	        if(isset($datos['numCajas'])){
	            $lnegocioDetalleSolicitudInspeccion = new DetalleSolicitudInspeccionLogicaNegocio();
	            foreach ($datos['numCajas'] as $item) {
	                
	                $valores = explode('.', $item);
	                $this->modeloTemporalProductores = $lnegocioTemporalProductore->buscar($valores[0]);                
	                $datos = array(
	                    'razon_social' => $this->modeloTemporalProductores->getRazonSocial(),
	                    'area' => $this->modeloTemporalProductores->getNombreArea(),
	                    'num_cajas' => $valores[1],
	                    'id_solicitud_inspeccion' => $idSolicitud,
	                    'provincia' => $this->modeloTemporalProductores->getProvincia(),
	                    'codigo_area' => $this->modeloTemporalProductores->getCodigoArea(),
	                    'codigo_mag' => $this->modeloTemporalProductores->getCodMag(),
	                    'identificador_operador' => $this->modeloTemporalProductores->getIdentificadorOperador()
	                );
	                $statement = $this->modeloSolicitudInspeccion->getAdapter()
	                ->getDriver()
	                ->createStatement();
	                $sqlInsertar = $this->modeloSolicitudInspeccion->guardarSql('detalle_solicitud_inspeccion', $this->modeloSolicitudInspeccion->getEsquema());
	                $sqlInsertar->columns($lnegocioDetalleSolicitudInspeccion->columnas());
	                $sqlInsertar->values($datos, $sqlInsertar::VALUES_MERGE);
	                $sqlInsertar->prepareStatement($this->modeloSolicitudInspeccion->getAdapter(), $statement);
	                $statement->execute();
	            }
	        }else{
	            throw new \Exception('No existe detalle de productores..!!');
	        }
	        
	        $proceso->commit();
	        return true;
	    }catch (\Exception $ex){
	        $proceso->rollback();
	        throw new \Exception($ex->getMessage());
	        echo $ex;
	        return false;
	    }
	}
	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloSolicitudInspeccion->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return SolicitudInspeccionModelo
	*/
	public function buscar($id)
	{
		return $this->modeloSolicitudInspeccion->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloSolicitudInspeccion->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloSolicitudInspeccion->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarSolicitudInspeccion()
	{
	$consulta = "SELECT * FROM ".$this->modeloSolicitudInspeccion->getEsquema().". solicitud_inspeccion";
		 return $this->modeloSolicitudInspeccion->ejecutarSqlNativo($consulta);
	}
	
	/**
	 * obtener informacion del operador
	 * @param string $identificador
	 */
	public function obtenerOperador($identificador) {
	    
	    $consulta = "
                        SELECT row_to_json (operador)
                        FROM (
                            SELECT
                                o1.* ,
                                (
                                    SELECT array_to_json(array_agg(row_to_json(operaciones_n2)))
                                    FROM (
                                            select
                                                distinct on(topc2.id_area, topc2.nombre) topc2.*
                                            from
                                                g_operadores.operadores opr2
                                                , g_operadores.operaciones opc2
                                                , g_catalogos.tipos_operacion topc2
                                            where
                                                opr2.identificador = opc2.identificador_operador
                                                and opc2.id_tipo_operacion = topc2.id_tipo_operacion
                                                and opr2.identificador = o1.identificador
                                            order by
                                                topc2.id_area, topc2.nombre ) operaciones_n2
                                ) operaciones
                            FROM
                                g_operadores.operadores o1
                            WHERE
                                o1.identificador = '$identificador'
                        ) as operador";
	        
	        return $this->modeloSolicitudInspeccion->ejecutarSqlNativo($consulta);
	}
	
	
	public function obtenerCorreoOperador($identificador) {
	    $consulta = "
                   SELECT 
                          correo,
                          razon_social
                        FROM
                            g_operadores.operadores o1
                        WHERE
                            o1.identificador = '$identificador';";
	    return $this->modeloSolicitudInspeccion->ejecutarSqlNativo($consulta);
	}
	
	/**
	 * Obtener informacion de los productores
	 */
	public function listarProductores($arrayParametros){
	    $busqueda = '';
	    if (array_key_exists('codigo_transaccional', $arrayParametros)) {
	        $busqueda = "and upper(a.codigo_transaccional) = upper('" . $arrayParametros['codigo_transaccional']."')";
	    }elseif(array_key_exists('id_area', $arrayParametros)){
	        $busqueda = "and a.id_area = " . $arrayParametros['id_area'];
	    }else{
	        $busqueda = "and o.identificador = '" . $arrayParametros['identificador']."' and s.codigo_provincia||s.codigo||a.codigo||secuencial = '" . $arrayParametros['codigoArea'] ."'";
	    }
	   $consulta = "
                    select distinct  a.id_area,
		               o.identificador, o.razon_social, s.provincia, a.nombre_area, a.id_sitio,a.codigo_transaccional,
					   o.identificador||'.'||s.codigo_provincia||s.codigo||a.codigo||a.secuencial as codigo_area, s.codigo_provincia as cod_provincia
                	from
                		g_operadores.operadores o
                		INNER JOIN g_operadores.sitios s ON o.identificador = s.identificador_operador
                		INNER JOIN g_operadores.areas a ON s.id_sitio = a.id_sitio
                		INNER JOIN g_operadores.productos_areas_operacion pao ON a.id_area = pao.id_area
                		INNER JOIN g_operadores.operaciones op ON pao.id_operacion = op.id_operacion
                		INNER JOIN g_catalogos.tipos_operacion top ON op.id_tipo_operacion = top.id_tipo_operacion
                	where
                		op.estado in ('registrado','registradoObservacion')
                		and top.codigo in ('PRB','ACO','CON')
                		and top.id_area = 'SV'
                		". $busqueda ."
                	ORDER BY 1 
                    ";
	  return $this->modeloSolicitudInspeccion->ejecutarSqlNativo($consulta);
	}
	//**********obtener los paises*****************************************
	public function obtenerPaises(){
	    $consulta = "SELECT
                            id_localizacion, nombre
                     FROM
                            g_catalogos.localizacion
	                 WHERE
                            categoria=0
	                 ORDER BY nombre ASC ;";
	    $resultado = $this->modeloSolicitudInspeccion->ejecutarSqlNativo($consulta);
	    return $resultado;
	}
	//***********obtener puertos*********************************************
	public function obtenerPuertos($idPais){
	    $consulta = "SELECT
                        nombre_puerto, id_puerto, id_pais, id_provincia, nombre_provincia
                     FROM
                        g_catalogos.puertos
	                 WHERE
                        id_pais=".$idPais." and id_provincia is not NULL
                     ORDER BY 1;";
	    $resultado = $this->modeloSolicitudInspeccion->ejecutarSqlNativo($consulta);
	    return $resultado;
	    
	}
	//***********obtener provincias*********************************************
	public function obtenerProvincia($idPuerto){
	    $consulta = "SELECT
                        nombre as provincia, substring(codigo_vue, 2, 2) as codigo_vue
                     FROM
                        g_catalogos.puertos p INNER JOIN g_catalogos.localizacion l on p.id_provincia = l.id_localizacion
	                 WHERE
                        id_puerto=".$idPuerto." and p.id_provincia is not NULL
                     ORDER BY 1;";
	    $resultado = $this->modeloSolicitudInspeccion->ejecutarSqlNativo($consulta);
	    return $resultado;
	}
	//****************sql para obtener los requisitos de exportacion***********
	public function obtenerRequisitos($arrayParametros){
	    $consulta = "select
			ra.orden,
			ra.tipo,
			r.detalle,
			r.detalle_impreso
		from
			g_requisitos.requisitos_asignados ra inner join 
			g_requisitos.requisitos r on r.id_requisito = ra.requisito inner join
	        g_requisitos.requisitos_comercializacion rc on rc.id_requisito_comercio = ra.id_requisito_comercio inner join
            g_catalogos.productos pr on pr.id_producto=rc.id_producto inner join
            g_catalogos.subtipo_productos st on pr.id_subtipo_producto=st.id_subtipo_producto inner join
	        g_catalogos.tipo_productos tp on st.id_tipo_producto=tp.id_tipo_producto 
		where
			ra.estado = 'activo' and
			r.tipo ='Exportación' and
			r.estado = 1 and
			quitar_caracteres_especiales_sin_espacio(pr.nombre_comun) = '".$arrayParametros['producto']."' and
			st.nombre = 'Fruta' and                    
			rc.id_localizacion = ".$arrayParametros['idPais']."
			and rc.tipo='SV' 
	  order by
			ra.orden";
	    $resultado = $this->modeloSolicitudInspeccion->ejecutarSqlNativo($consulta);
	    return $resultado;
	}
	//**************funcion para quitar las tildes*********************
	public function quitar_tildes($cadena) {
	    $no_permitidas= array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹");
	    $permitidas= array ("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E");
	    $texto = str_replace($no_permitidas, $permitidas ,$cadena);
	    return $texto;
	}
	
	/**
	 * Ejecuta una consulta(SQL) personalizada para obtener el secuencial
	 *
	 * @return array
	 */
	public function obtenerSecuencialSolicitud(){
	    $consulta = "SELECT
						COALESCE(count(*)+1::numeric, 0) AS numero
					FROM
						g_inspeccion_musaceas.solicitud_inspeccion;";
	    $resultado = $this->modeloSolicitudInspeccion->ejecutarSqlNativo($consulta);
	    return $resultado;
	}
	//******************obtener el la razon de operador**************
	public function obtenerRazonSocial($identificador){
	   $consulta = "SELECT
                       case when o.razon_social = '' then o.nombre_representante ||' '|| o.apellido_representante else o.razon_social end razon_social,
                       case when o.nombre_tecnico = '' then o.apellido_tecnico ||' '|| o.nombre_tecnico else o.apellido_representante ||' '|| o.nombre_representante  end tecnico,
                       o.provincia
					FROM
						g_operadores.operadores o
                    WHERE 
                        identificador='".$identificador."';";
	    $resultado = $this->modeloSolicitudInspeccion->ejecutarSqlNativo($consulta);
	    return $resultado;
	}
	//**********************obtener el mes
	public function nombreMes($mes){
	    $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
	    return $meses[$mes-1];
	}
	//****************busque por parametros*******
	public function buscarPorParametros($arrayParametros){
	    $busqueda='true';
	    $bandera=true;
	    if(isset($arrayParametros['numeroSolicitud'])){
    	    if($arrayParametros['numeroSolicitud'] != ''){
    	    	$bandera=false;
    	        $busqueda = "upper(codigo_solicitud)=upper('".$arrayParametros['numeroSolicitud']."')";
    	    }
	    }
	    if(isset($arrayParametros['fecha'])){
    	     if($arrayParametros['fecha'] != ''){
    	     	$bandera=false;
    	        $busqueda .= " and fecha_creacion::date='".$arrayParametros['fecha']."'";
    	    }
	    }
	    if(isset($arrayParametros['estadoSolicitud'])){
	        if($arrayParametros['estadoSolicitud'] != ''){
	            $busqueda .=" and estado IN ('".$arrayParametros['estadoSolicitud']."')";
	        }
	    }
	    if(isset($arrayParametros['identificador'])){
	        if($arrayParametros['identificador'] != ''){
	        $busqueda .= " and identificador='".$arrayParametros['identificador']."'";
	        }
	    }
	    if(isset($arrayParametros['provincia'])){
	        if($arrayParametros['provincia'] != ''){ 
	            $busqueda .= " and trim(unaccent(upper(provincia))) ILIKE trim(unaccent(upper('".$arrayParametros['provincia']."')))";
	        }
	    }
	    if(isset($arrayParametros['lugar_inspeccion'])){
	        if($arrayParametros['lugar_inspeccion'] != ''){
	            $busqueda .= " and lugar_inspeccion='".$arrayParametros['lugar_inspeccion']."'";
	        }
	    }
	    if(isset($arrayParametros['identificador_inspeccion_externa'])){
	    	if($arrayParametros['identificador_inspeccion_externa'] != ''){
	    		$busqueda .= " and identificador_inspeccion_externa='".$arrayParametros['identificador_inspeccion_externa']."'";
	    	}
	    }
	    if(isset($arrayParametros['inspeccion_externa'])){
	    		$busqueda .= " and (identificador_inspeccion_externa = '' or identificador_inspeccion_externa is null)";
	    }
	    if(isset($arrayParametros['tiempo_busqueda'])){
	    	if($bandera){
	    		$busqueda .= " and fecha_creacion::date between (now()-'".$arrayParametros['tiempo_busqueda']." day'::interval) and  now()";
	    	}
	    }
	  $consulta = "SELECT
						*
					FROM
						g_inspeccion_musaceas.solicitud_inspeccion
                    WHERE
                        ".$busqueda." order by 1 desc;";
	    
	    $resultado = $this->modeloSolicitudInspeccion->ejecutarSqlNativo($consulta);
	    return $resultado;
	    
	}
	/**
	 * Ejecuta una consulta(SQL) personalizada para obtener solicitudes de inspeccion
	 *
	 * @return array
	 */
	public function obtenerSolicitudes(){
	    $consulta = "
					SELECT
							id_solicitud_inspeccion, fecha_creacion, identificador
  					FROM
							g_inspeccion_musaceas.solicitud_inspeccion
					WHERE
							(SELECT now()) > (fecha_creacion+'60 day'::interval) and
							estado in ('Enviada') ;";
	    
	    $resultado = $this->modeloSolicitudInspeccion->ejecutarSqlNativo($consulta);
	    return $resultado;
	}
	/**
	 * VERIFICAR TIPO DE PERFIL DEL OPERADOR
	 */
	public function verificarPerfil($identificador,$perfil=null){
	    $busqueda='';
	    if($perfil!= ''){
	        $busqueda = "and p.codificacion_perfil = '".$perfil."'";
	    }
	    $sql = "SELECT
					p.nombre, p.codificacion_perfil
			  FROM
					g_usuario.usuarios_perfiles up
					INNER JOIN g_usuario.perfiles p ON up.id_perfil = p.id_perfil
					INNER JOIN g_programas.aplicaciones ap ON ap.id_aplicacion = p.id_aplicacion
			  WHERE
					identificador in ('" . $identificador . "') AND
					ap.codificacion_aplicacion='PRG_INSP_MUS' ".$busqueda." order by 1;";
	    return $this->modeloSolicitudInspeccion->ejecutarSqlNativo($sql);
	}
	
	/**
	 * Notificar envío de emails
	 *
	 */
	public function notificarEmail($arrayEmail)
	{
		$asunto = 'Convocatoria a inspección fitosanitaria en el punto único PUI - AGROCALIDAD';
		//$familiaLetra = "font-family:Text Me One,Segoe UI, Tahoma, Helvetica, freesans, sans-serif";
		$familiaLetra = "";
		
		$cuerpoMensaje = '<table><tbody>
			<tr><td colspan="5" style=" padding-top:20px; font-size:14px;color:#2a2a2a;">Estimados Srs. '. $arrayEmail['nombreExportador'].'</td></tr>
            <tr><td colspan="5" style=" padding-top:30px; font-size:14px;">Por medio de la presente se comunica que el día de hoy '.$arrayEmail['fecha'].', será inspeccionada en el Punto único de Inspección de Agrocalidad '.$arrayEmail['puerto'].' la carga remitida en su solicitud. <br></td></tr>
		    <tr><td colspan="5" style=" padding-top:30px; font-size:14px;">Por consiguiente, la exportadora deberá contar con el representante autorizado por ellos, el mismo que consta en la Solicitud de Inspección de musáceas Nro. '.$arrayEmail['codigo_solicitud'].' y personas que realicen el re empaque de las cajas inspeccionadas por los técnicos de Agrocalidad, además tomarán todos los recaudos logísticos para que la inspección fitosanitaria se lleve con normalidad. <br></td></tr>
			<tr><td colspan="5" style=" padding-top:30px; font-size:14px;">Los productores seleccionados para la inspección en el Puerto son: </td></tr>
		    '. $arrayEmail['listaProductores'].'
			<tr><td colspan="5" style=" padding-top:30px; font-size:14px;">Técnico a cargo:  <h4>'.$arrayEmail['tecnicoCargo'].'</h4></td></tr>
			<tr><td colspan="5" style=" padding-top:30px; font-size:14px;">¡Saludos cordiales!  </td></tr>
            <tr><td colspan="5" style=" padding-top:30px; font-size:14px;"><h3>“Señor exportador y productor recuerda certificarte cumple con la Resolución 138- AGROCALIDAD”  </h3></td></tr>
			</tbody></table>';
		
		if (count($arrayEmail['correo']) > 0)
		{
			$datosCorreo = array(
				'asunto' => $asunto,
				'cuerpo' => $cuerpoMensaje,
				'codigo_modulo' => "PRG_INSP_MUS",
				'tabla_modulo' => "g_inspeccion_musaceas.notificar_solicitud",
				'id_solicitud_tabla' => $arrayEmail['id_notificar_solicitud'],
				'estado' => 'Por enviar'
			);
			$modeloCorreos = new \Agrodb\Correos\Modelos\CorreosModelo();
			$idCorreo = $modeloCorreos->guardar($datosCorreo);
			
			//Guardar correo del destino
			$destino = new \Agrodb\Correos\Modelos\DestinatariosLogicaNegocio();
			foreach ($arrayEmail['correo'] as $val)
			{
				$datosDestino = array('id_correo' => $idCorreo, 'destinatario_correo' => $val);
				$destino->guardar($datosDestino);
			}
		}
	}
	
	public function obtenerTecnicoInspectorExterno(){
		$consulta = "
					select 
						distinct o.identificador, 
                        case when rtrim(o.razon_social) != '' then o.razon_social else o.apellido_tecnico ||' '|| o.nombre_tecnico end operador
					from g_operadores.operadores o inner join g_usuario.usuarios_perfiles up on o.identificador = up.identificador 
	                				and id_perfil= (SELECT id_perfil FROM g_usuario.perfiles WHERE codificacion_perfil ='PFL_IEA_MUS')
	               		inner join g_operadores.operaciones op on op.identificador_operador = o.identificador
	  					inner join g_catalogos.tipos_operacion tope on  tope.codigo ='IEA' and tope.id_tipo_operacion = op.id_tipo_operacion 
	  				where op.estado = 'registrado' order by 1 asc;";
		
		$resultado = $this->modeloSolicitudInspeccion->ejecutarSqlNativo($consulta);
		return $resultado;
	}
	public function obtenerDatosTecnico($identificador){
		$sql = "select
    					f.apellido||' '||f.nombre as tecnico
    			from
    					g_usuario.usuarios u left join
    					g_uath.ficha_empleado f on (u.identificador=f.identificador)
    			where
    					u.identificador='$identificador'
    					and f.estado_empleado = 'activo';";
		return $this->modeloSolicitudInspeccion->ejecutarSqlNativo($sql);
	}
}
