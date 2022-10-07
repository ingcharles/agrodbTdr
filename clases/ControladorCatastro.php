<?php 

class ControladorCatastro{
	//private $conexion = new Conexion();

	/****************************************************/
	/*			Bancos		 							*/
	/****************************************************/

	public function obtenerDatosBanco ($conexion,$identificador){
		$res = $conexion->ejecutarConsulta("select
				*
				from
				g_uath.datos_bancarios dc
				where
				dc.identificador='" . $identificador . "';");
		return $res;
	}


	private function numRegistroDatosBanco($conexion,$identificador){
		$sqlScript="select
				count(*) numero_registros
				from
				g_uath.datos_bancarios dc
				where
				dc.identificador='" . $identificador . "';"	;


		$res = $conexion->ejecutarConsulta($sqlScript);
		return $res;

	}

	public function actualizarDatosBanco($conexion, $identificador, $institucion, $tipo_cuenta, $numero_cuenta){
		$sqlScript="";
		$res=$this->numRegistroDatosBanco($conexion, $identificador);
		$banco = pg_fetch_assoc($res);

		if($banco['numero_registros']==0){
			$sqlScript="insert into
			g_uath.datos_bancarios
			(institucion,
			tipo_cuenta,
			numero_cuenta,
			identificador,
			fecha_modificacion)
			values
			(
			'$institucion',
			'$tipo_cuenta',
			'$numero_cuenta',
			'$identificador',
			now()
			);";

			$res = $conexion->ejecutarConsulta($sqlScript);
			return $res;

		}
		else {

			$sqlScript="update
			g_uath.datos_bancarios
			set
			institucion='$institucion',
			tipo_cuenta='$tipo_cuenta',
			numero_cuenta='$numero_cuenta',
			fecha_modificacion=now()
			where
			identificador='$identificador';";

			$res = $conexion->ejecutarConsulta($sqlScript);
			return $res;

		}
	}



	/****************************************************/
	/*			       Contratos		 				*/
	/****************************************************/


	public function obtenerGrupoOcupacional ($conexion){

		$res = $conexion->ejecutarConsulta("SELECT
				*
				FROM
				g_catalogos.grupo_ocupacional
				WHERE
				estado = 1
				ORDER BY
				nombre_grupo asc");

		return $res;
	}

	public function obtenerRemuneraciones ($conexion){

		$sqlScript="SELECT
				distinct remuneracion
				FROM
				g_catalogos.grupo_ocupacional
				ORDER BY
				remuneracion";

		$res = $conexion->ejecutarConsulta($sqlScript);
		return $res;
	}


	public function sacarReporteContratosFiltrados($conexion,$regimen_laboral,$tipo_contrato,$presupuesto,$fuente,
			$puesto,$grupo,$remuneracion,$provincia,$canton,
			$oficina,$direccion,$coordinacion,$anio,$mes,$estado,$anio_fin,$mes_fin){

		$regimen_laboral = $regimen_laboral!="" ? "'" . $regimen_laboral . "'" : "null";
		$tipo_contrato = $tipo_contrato!="" ? "'" . $tipo_contrato . "'" : "null";
		$presupuesto = $presupuesto!="" ? "'" . $presupuesto . "'" : "null";
		$fuente = $fuente!="" ? "'" . $fuente . "'" : "null";
		$puesto = $puesto!="" ? "'" . $puesto . "'" : "null";
		$grupo = $grupo!="" ? "'" . $grupo . "'" : "null";
		$remuneracion = $remuneracion!="" ? "'" . $remuneracion . "'" : "null";
		$provincia = $provincia!="" ? "'" . $provincia . "'" : "null";
		$canton = $canton!="" ? "'" . $canton . "'" : "null";
		$oficina = $oficina!="" ? "'" . $oficina . "'" : "null";
		$direccion = $direccion!="" ? "'" . $direccion . "'" : "null";
		$coordinacion = $coordinacion!="" ? "'" . $coordinacion . "'" : "null";
		$anio = $anio!="" ? "'" . $anio . "'" : "null";
		$mes = $mes!="" ? "'" . $mes . "'" : "null";
		$estado = $estado!="" ? "'" . $estado . "'" : "null";
		$anio_fin = $anio_fin!="" ? "'" . $anio_fin . "'" : "null";
		$mes_fin = $mes_fin!="" ? "'" . $mes_fin . "'" : "null";


		$sqlScript="SELECT *
				FROM
				g_uath.reporte_contratos_usuarios(".$regimen_laboral.",".$tipo_contrato.",".$presupuesto.",".$fuente.",".
				$puesto.",".$grupo.",".$remuneracion.",".$provincia.",".$canton.",".
				$oficina.",".$direccion.",".$coordinacion.",".$anio.",".$mes.",".$estado.",".$anio_fin.",".$mes_fin.")
						order by provincia,direccion,apellido;";

		$res = $conexion->ejecutarConsulta($sqlScript);

		return $res;
	}

	public function obtenerDatosPuesto($conexion){

		$res = $conexion->ejecutarConsulta("select
				*
				from
				g_catalogos.puestos
				where
				estado = 1
				order by
				2;");

		return $res;
	}

	public function actualizarFechasContratos($conexion, $idEmpleado){

		$sqlScript="update
		g_uath.datos_contrato
		set
		contabilizar_dias='false',
		fecha_modificacion=now()
		where
		identificador='$idEmpleado'";
			
		$res = $conexion->ejecutarConsulta($sqlScript);
			
		return $res;
	}



	public function actualizarSeleccionFecha($conexion, $idContratos,$idEmpleado){
		$this->actualizarFechasContratos($conexion, $idEmpleado);
		if($idContratos!=""){
			$contrato='(';
			foreach ($idContratos as $idContrato){
				$contrato.=$idContrato.',';
			}
			$contrato=substr($contrato,0,-1).')';
			$sqlScript="update
			g_uath.datos_contrato
			set
			contabilizar_dias='true',
			fecha_modificacion=now()
			where id_datos_contrato in $contrato";

			$res = $conexion->ejecutarConsulta($sqlScript);
		}
		return $res;
	}

	public function verificarFechaContrato($conexion,$identidicador){

		$sqlScript="SELECT (fecha_inicio, fecha_fin) OVERLAPS (DATE '2014-12-01', DATE '2014-12-31') as continuidad,fecha_inicio, fecha_fin
		from g_uath.datos_contrato as t1
		where t1.identificador='$identidicador' and
		t1.tipo_contrato='Contrato Servicios Ocacionales' and presupuesto='Presupuesto general' and (fuente=1 or fuente=2)
		order by t1.fecha_inicio desc;";
			
		$res = $conexion->ejecutarConsulta($sqlScript);
		return $res;
	}

	public function obtenerDatosPresupuesto($conexion){

		$sqlScript="select	*
				from g_catalogos.presupuestos;";
		$res = $conexion->ejecutarConsulta($sqlScript);
		return $res;
	}


	public function obtenerDatosContrato ($conexion, $idContrato){

		 $sqlScript="select
						*
					from
						g_uath.vista_contrato_empleado dc
					where
						dc.id_datos_contrato='$idContrato'
					order by 
						fecha_inicio desc;";

			
		$res = $conexion->ejecutarConsulta($sqlScript);
		
		return $res;
	}

	public function obtenerDatosContratoUsuario ($conexion, $identificador, $opcion){

		switch ($opcion){
			case 'Total': $busqueda = 'estado IN (1,2,3)'; break;
			case 'Parcial': $busqueda = 'estado IN (1,2) '; break;
		}

		$sqlScript="select
				*
				from
				g_uath.datos_contrato
				where
				" . $busqueda ."
				and identificador='$identificador'
				order by fecha_fin desc;";

			
		$res = $conexion->ejecutarConsulta($sqlScript);
		return $res;
	}


	public function obtenerContratosXUsuario ($conexion, $identificador, $nombre, $apellido){

		$identificador = $identificador!="" ? "'" . $identificador . "'" : "null";
		$nombre = $nombre!="" ? "'%" . strtoupper($nombre) . "%'" : "null";
		$apellido = $apellido!="" ? "'" . strtoupper($apellido) . "%'" : "null";

		$res = $conexion->ejecutarConsulta("select
												dc.*, 
												f.id_area 
											from
												g_uath.mostrar_datos_contratos_usuarios(".$identificador.",".$nombre.",".$apellido.") dc,
												g_estructura.funcionarios f
											where
												dc.identificador = f.identificador
												order by apellido,nombre,fecha_fin desc;");
		
		return $res;
	}
	

	public function obtenerContratosXUsuarioExterno ($conexion, $identificador, $nombre, $apellido){
	
		$identificador = $identificador!="" ? "'" . $identificador . "'" : "null";
		$nombre = $nombre!="" ? "'%" . strtoupper($nombre) . "%'" : "null";
		$apellido = $apellido!="" ? "'" . strtoupper($apellido) . "%'" : "null";
	
		$res = $conexion->ejecutarConsulta("select
												dc.*
											from
												g_uath.mostrar_datos_contratos_usuarios(".$identificador.",".$nombre.",".$apellido.") dc
											order by
												apellido,
												nombre,
												fecha_fin desc;");
	
		return $res;
	}
	
	public function actualizarDatosContrato($conexion, $id_datos_contrato, $identificador, $tipoContrato, $numeroContrato, $fechaInicio, $fechaFin, $observacion,
											$archivoContrato, $localizacion, $regimenLaboral,$numeroNotaria,$lugarNotaria, $fechaDeclaracion, $partidaPresupuestaria, 
											$grupoOcupacional, $nombrePuesto, $presupuesto, $remuneracion, $fuente, $grado, $provincia, $canton, $direccion, $coordinacion, $id_gestion, $gestion, 
											$partidaIndividual, $idOficina, $nombreOficina, $estado,$terminacion_laboral,$calificacion,$escala_calificacion,$fecha_salida, $provinciaNotaria,$cantonNotaria,
	                                        $rol, $informacionPuesto,$pluriempleo,$fechaIngresoSectorPublico,$impedimento){
			
		$archivo=($archivoContrato!="")?"'$archivoContrato'":"''";
		$fechaFin = $fechaFin!="" ? "'" . $fechaFin . "'" : "null";
		$fecha_salida= $fecha_salida!="" ? "'" . $fecha_salida . "'" : "null";
		$fechaDeclaracion= $fechaDeclaracion!="" ? "'" . $fechaDeclaracion . "'" : "NULL";
		$calificacion= $calificacion!="" ? "'" . $calificacion . "'" : "null";
		$numeroNotaria= $numeroNotaria!="" ? "'" . $numeroNotaria . "'" : "null";
		$sqlScript="";
			
		$sqlScript="update
						g_uath.datos_contrato
					set
						tipo_contrato='$tipoContrato',
						numero_contrato='$numeroContrato',
						fecha_inicio='$fechaInicio',
						fecha_fin=$fechaFin,
						observacion='$observacion',
						regimen_laboral='$regimenLaboral',
						numero_notaria=$numeroNotaria,
						lugar_notaria='$lugarNotaria',
						fecha_declaracion=$fechaDeclaracion,
						partida_presupuestaria='$partidaPresupuestaria',
						grupo_ocupacional = '$grupoOcupacional',
						nombre_puesto = '$nombrePuesto',
						presupuesto = '$presupuesto',
						remuneracion = $remuneracion,
						fuente = '$fuente',
						grado = '$grado',
						fecha_modificacion=now(),
						provincia = '$provincia',
						canton = '$canton',
						direccion = '$direccion',
						coordinacion = '$coordinacion',
						id_gestion = '$id_gestion',
						gestion = '$gestion',
						partida_individual = '$partidaIndividual',
						id_oficina = $idOficina,
						oficina = '$nombreOficina',
						estado = $estado,
						motivo_terminacion_laboral = '$terminacion_laboral',
						nota = $calificacion,
						escala_calificacion = '$escala_calificacion',
						fecha_salida=$fecha_salida,
						archivo_contrato=$archivo,
                        provincia_notaria='$provinciaNotaria',
                        canton_notaria='$cantonNotaria', 
		                rol='$rol', 
		                informacion_puesto='$informacionPuesto', 
		                pluriempleo='$pluriempleo', 
		                fecha_ingreso_sector_publico='$fechaIngresoSectorPublico',
                        impedimento='$impedimento'
					where
						id_datos_contrato=$id_datos_contrato;";
        
		$res = $conexion->ejecutarConsultaLOGS($sqlScript);								

		return $res;

	}

	public function crearDatosContrato($conexion, $identificador, $tipoContrato, $numeroContrato, $fechaInicio, $fechaFin, $observacion,$archivoContrato, $regimenLaboral,
			$numeroNotaria,$lugarNotaria, $fechaDeclaracion, $grupoOcupacional, $nombrePuesto, $partidaPresupuestaria, $presupuesto,
	    $remuneracion, $fuente ,$grado,$partidaIndividual,$nombreProvincia,$nombreCanton,$idOficna, $nombreOficina, $coordinacion, $direccion, $gestion,
	    $idGestion, $estado,$fecha_salida,$nombreProvinciaNotario,$nombreCantonNotario,$rol,$informacionPuesto,$pluriempleo,$fechaIngresoSectorPublico,$impedimento){

		$fechaFin = $fechaFin!="" ? "'" . $fechaFin . "'" : "NULL";
		$fechaSalida = $fechaSalida!="" ? "'" . $fechaSalida . "'" : "NULL";
		
		if($fechaDeclaracion==''){
			
			$sqlScript="insert into
							g_uath.datos_contrato
							(identificador, tipo_contrato, numero_contrato, fecha_inicio, fecha_fin,
							observacion, archivo_contrato, regimen_laboral, numero_notaria, lugar_notaria,
							estado, grupo_ocupacional, nombre_puesto, partida_presupuestaria,
							presupuesto, remuneracion, fuente, grado, partida_individual,
							provincia, canton, id_oficina, oficina, coordinacion, direccion, gestion, 
                            id_gestion, fecha_salida, provincia_notaria, canton_notaria,rol,informacion_puesto,pluriempleo,fecha_ingreso_sector_publico,impedimento)
						values
							('$identificador',  '$tipoContrato',  '$numeroContrato', '$fechaInicio', $fechaFin,
							'$observacion', '$archivoContrato',	'$regimenLaboral', $numeroNotaria, '$lugarNotaria',
							$estado, '$grupoOcupacional', '$nombrePuesto', '$partidaPresupuestaria',
							'$presupuesto', $remuneracion, '$fuente', '$grado', '$partidaIndividual',
							'$nombreProvincia', '$nombreCanton', $idOficna, '$nombreOficina', '$coordinacion', '$direccion', '$gestion', 
                            '$idGestion', $fechaSalida,'$nombreProvinciaNotario', '$nombreCantonNotario','$rol','$informacionPuesto','$pluriempleo','$fechaIngresoSectorPublico','$impedimento');";

		}else{
			$sqlScript="insert into
							g_uath.datos_contrato
							(identificador, tipo_contrato, numero_contrato, fecha_inicio, fecha_fin,
							observacion, archivo_contrato, regimen_laboral, numero_notaria, lugar_notaria,
							fecha_declaracion, estado, grupo_ocupacional, nombre_puesto, partida_presupuestaria,
							presupuesto, remuneracion, fuente, grado, partida_individual,
							provincia, canton, id_oficina, oficina, coordinacion, direccion, gestion, 
                            id_gestion, fecha_salida, provincia_notaria, canton_notaria,rol,informacion_puesto,pluriempleo,fecha_ingreso_sector_publico,impedimento)
						values
							('$identificador',  '$tipoContrato',  '$numeroContrato', '$fechaInicio',$fechaFin,
							'$observacion', '$archivoContrato',	'$regimenLaboral', $numeroNotaria, '$lugarNotaria',
							'$fechaDeclaracion', $estado, '$grupoOcupacional', '$nombrePuesto', '$partidaPresupuestaria',
							'$presupuesto', $remuneracion, '$fuente', '$grado', '$partidaIndividual',
							'$nombreProvincia', '$nombreCanton', $idOficna, '$nombreOficina', '$coordinacion', '$direccion', '$gestion', 
                            '$idGestion', $fechaSalida,'$nombreProvinciaNotario', '$nombreCantonNotario','$rol','$informacionPuesto','$pluriempleo','$fechaIngresoSectorPublico','$impedimento');";
		}
				
		$res = $conexion->ejecutarConsultaLOGS($sqlScript);

		return $res;

	}


	public function eliminarContrato($conexion, $idContrato)
	{

		$sqlScript="delete from
		g_uath.datos_contrato
		where id_datos_contrato=$idContrato";

		$res = $conexion->ejecutarConsulta($sqlScript);
		$row=pg_fetch_row($res);
		////$firephp->warn($res->lastInsertId());

		return $res;
	}

	public function enviarEstadoFuncionario($conexion, $identificador, $estado = 'inactivo'){

		$sqlScript="UPDATE 
						g_uath.ficha_empleado
					SET 
						estado_empleado='$estado'
					WHERE
						identificador='$identificador'";

		$res = $conexion->ejecutarConsulta($sqlScript);

		return $res;
	}

	/****************************************************/
	/*			Familiares y Contactos					*/
	/****************************************************/


	public function obtenerDatosFamiliares($conexion,$identificador, $id=NULL){
			
		if($id==NULL)
		{
			$sqlScript="select
			*
			from 	g_uath.familiares_empleado
			where
			identificador='$identificador'
			order by posee_discapacidad desc;";

		}
		else
		{
			$sqlScript="select
					*
					from 	g_uath.familiares_empleado dc
					where
					dc.identificador='" . $identificador . "' AND
							dc.identificador_familiar='".$id."';";
		}

		$res = $conexion->ejecutarConsulta($sqlScript);
		return $res;
	}

	public function actualizarDatosFamiliares($conexion, $identificador, $nombre, $apellido, $relacion, $nacimiento, $edad, $calle_principal, $numero,$calle_secundaria,$referencia, $telefono, $celular,
	    $telefono_oficina, $extension, $identificador_familiar,$posee_discapacidad,$carnet_conadis_familiar,$contacto_emergencia,$nivel_instruccion){

		$sqlScript="update
		g_uath.familiares_empleado
		set
		nombre='$nombre',
		apellido='$apellido',
		relacion='$relacion',
		fecha_nacimiento='$nacimiento',
		edad='$edad',
		calle_principal='$calle_principal',
		numero='$numero',
		calle_secundaria='$calle_secundaria',
		referencia='$referencia',
		telefono='$telefono',
		celular='$celular',
		telefono_oficina='$telefono_oficina',
		extension='$extension',
		numero_carnet_conadis='$carnet_conadis_familiar',
		posee_discapacidad='$posee_discapacidad',
		contacto_emergencia='$contacto_emergencia',
		fecha_modificacion=now(),
        nivel_instruccion = '$nivel_instruccion'
		where identificador_familiar='$identificador_familiar'";
			
		$res = $conexion->ejecutarConsulta($sqlScript);
		return $res;

	}


	public function crearDatosFamiliares($conexion, $identificador_familiar, $identificador, $nombre, $apellido, $relacion, $nacimiento, $edad, $calle_principal, $numero,$calle_secundaria,$referencia,
	    $telefono, $celular, $telefono_oficina, $extension,$representante_discapacitado,$carnet_conadis_familiar,$contacto_emergencia,$tipo_documento,$nivel_instruccion ){

		$sqlScript="insert into
		g_uath.familiares_empleado
		(	identificador_familiar,
		nombre,
		apellido,
		relacion,
		fecha_nacimiento,
		edad,
		calle_principal,
		numero,
		calle_secundaria,
		referencia,
		telefono,
		celular,
		telefono_oficina,
		extension,
		fecha_modificacion,
		identificador,
		posee_discapacidad,
		numero_carnet_conadis,
		contacto_emergencia,
        tipo_documento,
        nivel_instruccion
		)
		values
		(	'$identificador_familiar',
		'$nombre',
		'$apellido',
		'$relacion',
		'$nacimiento',
		'$edad',
		'$calle_principal',
		'$numero',
		'$calle_secundaria',
		'$referencia',
		'$telefono',
		'$celular',
		'$telefono_oficina',
		'$extension',
		now(),
		'$identificador',
		'$representante_discapacitado',
		'$carnet_conadis_familiar',
		'$contacto_emergencia',
        '$tipo_documento',
        '$nivel_instruccion'
		);";

		$res = $conexion->ejecutarConsulta($sqlScript);
		if($res!=false){
			return $res;
		}
		else{
			return false;
		}

	}


	public function eliminarDatosFamiliares($conexion, $identificador_familiar)
	{

		$sqlScript="delete from
		g_uath.familiares_empleado
		where identificador_familiar='$identificador_familiar'";

		$res = $conexion->ejecutarConsulta($sqlScript);
		$row=pg_fetch_row($res);
		////$firephp->warn($res->lastInsertId());

		return $res;
	}

	/****************************************************/
	/*			Discapacidades 							*/
	/****************************************************/


	public function listaDescapacidadEx($conexion, $identificador_familiar){


		$sqlScript="SELECT
		dis_enf.id_discapacidad_enfermedad,
		dis_enf.descripcion
		FROM
		g_catalogos.discapacidad_enfermedad as dis_enf
		WHERE
		dis_enf.id_discapacidad_enfermedad not in
		(
		select rel_dis.id_discapacidad_enfermedad
		from g_uath.enfermedades_familiares as rel_dis
		where rel_dis.identificador_familiar='$identificador_familiar'
		)";


		$res = $conexion->ejecutarConsulta($sqlScript);
		return $res;
	}


	public function crearDiscapacidad($conexion, $identificador_familiar, $usuario, $id_discapacidad_enfermedad, $porcentaje, $carnet, $certificado_enfermedad){


		$sqlScript="INSERT  INTO g_uath.enfermedades_familiares
		(	identificador,
		identificador_familiar,
		porcentaje_discapacidad,
		carnet,
		id_discapacidad_enfermedad,
		certificado_enfermedad
		)
		VALUES
		(
		'$usuario',
		'$identificador_familiar',
		'$porcentaje',
		'$carnet',
		'$id_discapacidad_enfermedad',
		'$certificado_enfermedad'
		)";


		$res = $conexion->ejecutarConsulta($sqlScript);
		return $res;
	}


	public function actualizarDiscapacidad($conexion, $id_relacion_discapacidad, $porcentaje, $carnet, $certificado_enfermedad){
		$archivo=($certificado_enfermedad!="")?"certificado_enfermedad='$certificado_enfermedad',":"";

		$sqlScript="UPDATE
		g_uath.enfermedades_familiares
		SET
		$archivo
		porcentaje_discapacidad='$porcentaje',
		carnet='$carnet'
		WHERE id_enfermedades_familiares='$id_relacion_discapacidad'";


		$res = $conexion->ejecutarConsulta($sqlScript);
		return $res;
	}


	public function eliminarDiscapacidad($conexion, $id_relacion_discapacidad){

		$sqlScript="delete from
		g_uath.enfermedades_familiares as rel_dis
		where id_enfermedades_familiares='$id_relacion_discapacidad'";

		$res = $conexion->ejecutarConsulta($sqlScript);
		$row=pg_fetch_row($res);


		return $res;
	}

	public function obtenerDiscapacidad($conexion, $identificador=NULL){
			
		if($identificador==NULL)
		{
			$sqlScript="select
					*
					from 	g_catalogos.discapacidad_enfermedad";
		}
		else {

			$sqlScript="SELECT
			g_de.id_discapacidad_enfermedad,
			g_de.tipo,
			g_de.descripcion
			FROM
			g_catalogos.discapacidad_enfermedad g_de,
			g_uath.enfermedades_familiares g_rde
			WHERE
			g_de.id_discapacidad_enfermedad = g_rde.id_discapacidad_enfermedad AND
			g_rde.identificador_familiar='$identificador';";


		}

			
		$res = $conexion->ejecutarConsulta($sqlScript);
		return $res;
	}



	public function obtenerListaDiscapacidad($conexion, $identificador=NULL, $id_rel_enfermedad=NULL){
		if($id_rel_enfermedad==NULL)
		{

			$sqlScript="SELECT
			rel_dis_enf.id_discapacidad_enfermedad,
			dis_enf.descripcion,
			rel_dis_enf.porcentaje_discapacidad,
			rel_dis_enf.carnet,
			rel_dis_enf.certificado_enfermedad,
			rel_dis_enf.id_enfermedades_familiares
			FROM
			g_catalogos.discapacidad_enfermedad as dis_enf,
			g_uath.enfermedades_familiares as rel_dis_enf
			WHERE
			dis_enf.id_discapacidad_enfermedad = rel_dis_enf.id_discapacidad_enfermedad AND
			rel_dis_enf.identificador_familiar='$identificador';";


		}
		else
		{

			$sqlScript="SELECT
			rel_dis_enf.id_discapacidad_enfermedad,
			dis_enf.descripcion,
			rel_dis_enf.porcentaje_discapacidad,
			rel_dis_enf.carnet,
			rel_dis_enf.certificado_enfermedad,
			rel_dis_enf.id_enfermedades_familiares
			FROM
			g_catalogos.discapacidad_enfermedad as dis_enf,
			g_uath.enfermedades_familiares as rel_dis_enf
			WHERE
			dis_enf.id_discapacidad_enfermedad = rel_dis_enf.id_discapacidad_enfermedad AND
			rel_dis_enf.id_enfermedades_familiares='$id_rel_enfermedad';";

		}
			
		$res = $conexion->ejecutarConsulta($sqlScript);
		return $res;
	}


	/****************************************************/
	/*			Ubicación								*/
	/****************************************************/



	/*public function obtenerLocalizacion($conexion){

	$sqlScript="SELECT
	localizacion.id_localizacion,
	localizacion.nombre
	FROM
	g_catalogos.localizacion
	WHERE
	localizacion.id_localizacion_padre = 66;";





	$res = $conexion->ejecutarConsulta($sqlScript);
	return $res;
	}
	*/

	public function obtenerDatosEstado ($conexion,$localizacion){
		
		$localizacion = explode('-', $localizacion);

		if ($localizacion[1] == 'dirección'){
			$busqueda = "'upper(direccion)=upper(''$localizacion[0]'')'";

		}else{
			$busqueda = "'upper(provincia)=upper(''$localizacion[0]'')'";
		}
		
		
		$sqlScript="Select * from g_uath.caducidad_contrato($busqueda)";
		
		$res = $conexion->ejecutarConsulta($sqlScript);
		
		return $res;
	}

	public function obtenerUbicacionElegida ($conexion){


		$sqlScript="SELECT distinct
				localizacion.localizacion,
				localizacion.nombre
				FROM
				g_uath.datos_contrato,
				g_catalogos.localizacion
				WHERE
				datos_contrato.id_localizacion = localizacion.id_localizacion;";



			
		$res = $conexion->ejecutarConsulta($sqlScript);
		return $res;
	}
	/****************************************************/
	/*			Datos Académicos						*/
	/****************************************************/


	public function obtenerDatosAcadémicos($conexion,$identificador, $id=NULL){
			
		if($id!=NULL && $identificador!=NULL)
		{
			$sqlScript="select
			*
			from 	g_uath.datos_academicos da
			where
			da.identificador='$identificador' and estado='Aceptado';";
		}

		else if($id==NULL)
		{
			$sqlScript="select
			*
			from 	g_uath.datos_academicos da
			where
			da.identificador='$identificador';";
		}
		else {
			$sqlScript="select
			*
			from 	g_uath.datos_academicos da
			where
			da.identificador='$id' and (estado='Ingresado' or estado='Modificado');";
		}
			
		$res = $conexion->ejecutarConsulta($sqlScript);
		return $res;
	}

	public function obtenerCursoAcademico($conexion,$identificador, $id){
			

	 $sqlScript="select
		*
		from 	g_uath.datos_academicos da
		where
		da.id_datos_academicos='$id'";

			
		$res = $conexion->ejecutarConsulta($sqlScript);
		return $res;
	}

	public function obtenerDatosCapacitacion($conexion,$identificador, $id=NULL){
			
		if($id==NULL)
		{
			$sqlScript="select
			*
			from 	g_uath.datos_capacitacion da
			where
			da.identificador='$identificador';";
		}
		else {
			$sqlScript="select
			*
			from 	g_uath.datos_capacitacion da
			where
			da.id_datos_capacitacion='$id';";
		}
			
		$res = $conexion->ejecutarConsulta($sqlScript);
		return $res;
	}

	public function obtenerCapacitaciones($conexion,$identificador,$apellido,$nombre){
		$busqueda="";
		if($identificador!=''){
			$busqueda.=" and dc.identificador='".$identificador."'";
		}
		if($apellido!=''){
			$busqueda.=" and UPPER(fe.apellido) like '%".strtoupper($apellido)."%'";
		}
		if($nombre!=''){
			$busqueda.=" and UPPER(fe.nombre) like '%".strtoupper($nombre)."%'";
		}
				
		$res = $conexion->ejecutarConsulta("select 
												distinct dc.identificador,
												fe.nombre, fe.apellido,
												f.id_area
											from  
												g_uath.datos_capacitacion dc , 
												g_uath.ficha_empleado fe,
												g_estructura.funcionarios f
											where 
												f.identificador = dc.identificador and
												(dc.identificador=fe.identificador) and 
												(dc.estado='Ingresado' OR dc.estado='Modificado') ".$busqueda.";");
		return $res;
	}


	public function obtenerDatosAcadémicosAdmin($conexion,$identificador,$apellido,$nombre){
		$busqueda="";
		if($identificador!=''){
			$busqueda.=" and da.identificador='".$identificador."'";
		}
		if($apellido!=''){
			$busqueda.=" and UPPER(fe.apellido) like '%".strtoupper($apellido)."%'";
		}
		if($nombre!=''){
			$busqueda.=" and UPPER(fe.nombre) like '%".strtoupper($nombre)."%'";
		}
			
		$res = $conexion->ejecutarConsulta("select 
												distinct da.identificador,fe.nombre, fe.apellido, f.id_area
											from  		
												g_uath.datos_academicos da , 
												g_uath.ficha_empleado fe,
												g_estructura.funcionarios f
											where 
												da.identificador = f.identificador and
												(da.identificador=fe.identificador) and 
												(da.estado='Ingresado' OR da.estado='Modificado') ".$busqueda.";");
		return $res;
	}

	public function verificaDatosAcademicosAdmin($conexion, $id_datos_academicos, $estado, $observaciones)
	{
			
		$archivo=($archivo!="")?"archivo_academico='$archivo',":"";
		$sqlScript="update
		g_uath.datos_academicos
		set
		estado='$estado',
		observaciones_rrhh='$observaciones',
		fecha_modificacion=now()
		where id_datos_academicos=$id_datos_academicos";
			
		$res = $conexion->ejecutarConsulta($sqlScript);
		$row=pg_fetch_row($res);
		////$firephp->warn($res->lastInsertId());
			
		return $res;
	}

	public function crearDatosAcademicos($conexion, $identificador, $nivel_instruccion, $num_certificado, $institucion, $anios_estudio, $carrera, $titulo, $pais, $archivo, $estado,$egresado){


		$sqlScript="insert into g_uath.datos_academicos
		(	identificador,
		nivel_instruccion,
		num_certificado,
		institucion,
		anios_estudio,
		carrera,
		titulo,
		pais,
		archivo_academico,
		estado,
		fecha_modificacion,
        egresado

		)
		values
		(	'$identificador',
		'$nivel_instruccion',
		'$num_certificado',
		'$institucion',
		$anios_estudio,
		'$carrera',
		'$titulo',
		'$pais',
		'$archivo',
		'$estado',
		now(),
        '$egresado'

		);";

		$res = $conexion->ejecutarConsulta($sqlScript);
		$row=pg_fetch_row($res);

		return $res;
			
	}


	public function crearDatosCapacitacion($conexion, $identificador, $titulo, $institucion, $pais, $archivo,$estado, $horas,$observaciones,$fecha_inicio,$fecha_fin,$auspiciante,$tipoCertificado){

		$sqlScript="insert into g_uath.datos_capacitacion(identificador, titulo_capacitacion, institucion, pais, archivo_capacitacion, estado, horas,
															observaciones, fecha_inicio, fecha_fin, fecha_modificacion,auspiciante, tipo_certificado)
													values('$identificador', '$titulo', '$institucion', '$pais', '$archivo', '$estado', $horas, '$observaciones',
															'$fecha_inicio', '$fecha_fin', now(),'$auspiciante','$tipoCertificado');";

		$res = $conexion->ejecutarConsulta($sqlScript);

		return $res;			
	}


	public function actualizarDatosAcademicos($conexion, $id_datos_academicos, $nivel_instruccion, $num_certificado, $institucion, $anios_estudio, $carrera, $titulo, $pais, $archivo, $estado,$egresado){
			
		$sqlScript="update
		g_uath.datos_academicos
		set
		nivel_instruccion='$nivel_instruccion',
		num_certificado='$num_certificado',
		institucion='$institucion',
		anios_estudio=$anios_estudio,
		carrera='$carrera',
		titulo='$titulo',
		pais='$pais',
		archivo_academico='$archivo',
		estado='$estado',
		fecha_modificacion=now(),
        egresado = '$egresado'

		where id_datos_academicos='$id_datos_academicos'";
			
		$res = $conexion->ejecutarConsulta($sqlScript);
		$row=pg_fetch_row($res);
		////$firephp->warn($res->lastInsertId());
			
		return $res;

	}

	public function actualizarDatosCapacitacion($conexion,$id_datos_capacitacion, $identificador, $titulo, $institucion, $pais, $archivo,$estado, $horas,$fecha_inicio,$fecha_fin,$auspiciante,$tipoCertificado)
	{
			
		//$archivo=($archivo!="")?"archivo_academico='$archivo',":"";
		$sqlScript="update
		g_uath.datos_capacitacion
		set
		identificador='$identificador',
		titulo_capacitacion='$titulo',
		institucion='$institucion',
		pais='$pais',
		archivo_capacitacion='$archivo',
		estado='$estado',
		horas=$horas,
		fecha_inicio='$fecha_inicio',
		fecha_fin='$fecha_fin',
		fecha_modificacion=now(),
        auspiciante = '$auspiciante',
        tipo_certificado = '$tipoCertificado'
		where id_datos_capacitacion=$id_datos_capacitacion";

		$res = $conexion->ejecutarConsulta($sqlScript);
		$row=pg_fetch_row($res);
		////$firephp->warn($res->lastInsertId());
			
		return $res;

	}


	public function eliminarDatosAcademicos($conexion, $id_datos_academicos)
	{

		$sqlScript="delete from
		g_uath.datos_academicos
		where id_datos_academicos='$id_datos_academicos' and estado<>'Aceptado'";

		$res = $conexion->ejecutarConsulta($sqlScript);
		$row=pg_fetch_row($res);
		////$firephp->warn($res->lastInsertId());

		return $res;
	}

	public function eliminarDatosCapacitacion($conexion, $id_datos_capacitacion)
	{

		$sqlScript="delete from
		g_uath.datos_capacitacion
		where id_datos_capacitacion='$id_datos_capacitacion' and estado<>'Aceptado'";

		$res = $conexion->ejecutarConsulta($sqlScript);
		$row=pg_fetch_row($res);
		////$firephp->warn($res->lastInsertId());

		return $res;
	}

	/****************************************************/
	/*			Experiencia Laboral						*/
	/****************************************************/

	public function obtenerExperienciaLaboral($conexion,$identificador, $id=NULL){
			
		if($id=='Aceptado'){
			$sqlScript="select
			*
			from 	g_uath.experiencia_laboral el
			where
			el.identificador='$identificador' and el.estado='Aceptado' order by el.fecha_ingreso desc,el.fecha_salida desc;";

		}
		else if($id==NULL)
		{
			$sqlScript="select
			*
			from 	g_uath.experiencia_laboral el
			where
			el.identificador='$identificador' order by el.fecha_ingreso desc,el.fecha_salida desc;";
		}
		else {
			$sqlScript="select
			*
			from 	g_uath.experiencia_laboral el
			where
			el.identificador='$id' and (el.estado='Ingresado' or el.estado='Modificado') order by el.fecha_ingreso desc,el.fecha_salida desc;";
		}
			
		$res = $conexion->ejecutarConsulta($sqlScript);
		return $res;
	}

	public function obtenerExperienciaLaboralAdmin($conexion,$identificador,$apellido,$nombre){
		$busqueda="";
		if($identificador!=''){
			$busqueda.=" and el.identificador='".$identificador."'";
		}
		if($apellido!=''){
			$busqueda.=" and UPPER(fe.apellido) like '%".strtoupper($apellido)."%'";
		}
		if($nombre!=''){
			$busqueda.=" and UPPER(fe.nombre) like '%".strtoupper($nombre)."%'";
		}
			
		$res = $conexion->ejecutarConsulta("select	
												distinct el.identificador,
												fe.nombre, fe.apellido,
												f.id_area
											from 	
												g_uath.experiencia_laboral el, 
												g_uath.ficha_empleado fe,
												g_estructura.funcionarios f
											where
												f.identificador = el.identificador and
												(el.identificador=fe.identificador) and 
												(el.estado='Ingresado' OR el.estado='Modificado') ".$busqueda.";");
		return $res;
	}


	public function modificarExperienciaLaboral($conexion,$id){
			
		$sqlScript="select
		*
		from 	g_uath.experiencia_laboral el
		where
		el.id_experiencia_laboral='$id';";

			
		$res = $conexion->ejecutarConsulta($sqlScript);
		return $res;
	}


	public function crearExperienciaLaboral($conexion, $identificador, $tipo_institucion, $institucion, $unidad_administrativa, $puesto, $fecha_ingreso, $fecha_salida, $archivo_experiencia, $motivo_salida, $estado, $motivo_ingreso ){

		$fecha_salida = $fecha_salida!="" ? "'" . $fecha_salida . "'" : "NULL";
		$sqlScript="insert into
		g_uath.experiencia_laboral
		(	identificador,
		tipo_institucion,
		institucion,
		unidad_administrativa,
		puesto,
		fecha_ingreso,
		fecha_salida,
		motivo_salida,
		archivo_experiencia,
		estado,
		fecha_modificacion,
        motivo_ingreso


		)
		values
		(	'$identificador',
		'$tipo_institucion',
		'$institucion',
		'$unidad_administrativa',
		'$puesto',
		'$fecha_ingreso',
		$fecha_salida,
		'$motivo_salida',
		'$archivo_experiencia',
		'$estado',
		now(),
        '$motivo_ingreso'
		);";
			
		$res = $conexion->ejecutarConsulta($sqlScript);
		return $res;
			
	}

	public function actualizarExperienciaLaboral($conexion, $id_experiencia_laboral, $tipo_institucion, $institucion, $unidad_administrativa, $puesto, $fecha_ingreso, $fecha_salida, $archivo_experiencia, $motivo_salida, $estado,$motivo_ingreso)
	{
		$archivo=($archivo_experiencia!="")?"archivo_experiencia='$archivo_experiencia',":"";
		$fecha_salida = $fecha_salida!="" ? "'" . $fecha_salida . "'" : "NULL";
		$sqlScript="update
		g_uath.experiencia_laboral
		set
		tipo_institucion='$tipo_institucion',
		institucion='$institucion',
		unidad_administrativa='$unidad_administrativa',
		puesto='$puesto',
		fecha_ingreso='$fecha_ingreso',
		fecha_salida=$fecha_salida,
		motivo_salida='$motivo_salida',
		estado='$estado',".
		$archivo."
		fecha_modificacion=now(),
        motivo_ingreso = '$motivo_ingreso'
		where id_experiencia_laboral='$id_experiencia_laboral'";
			
		$res = $conexion->ejecutarConsulta($sqlScript);
		return $res;

	}

	public function verificaExperienciaLaboralAdmin($conexion, $id_experiencia_laboral, $estado, $observaciones)
	{
		$archivo=($archivo_experiencia!="")?"archivo_experiencia='$archivo_experiencia',":"";
			
		$sqlScript="update
		g_uath.experiencia_laboral
		set
		estado='$estado',
		observaciones_rrhh='$observaciones',
		fecha_modificacion=now()
		where id_experiencia_laboral='$id_experiencia_laboral'";
			
		$res = $conexion->ejecutarConsulta($sqlScript);
		$row=pg_fetch_row($res);
		////$firephp->warn($res->lastInsertId());
			
		return $res;

	}

	public function verificaCapacitacionAdmin($conexion, $id_datos_capacitacion, $estado, $observaciones)
	{
		$sqlScript="update
		g_uath.datos_capacitacion
		set
		estado='$estado',
		observaciones='$observaciones',
		fecha_modificacion=now()
		where id_datos_capacitacion='$id_datos_capacitacion'";

		$res = $conexion->ejecutarConsulta($sqlScript);
		$row=pg_fetch_row($res);
		////$firephp->warn($res->lastInsertId());

		return $res;

	}


	public function eliminarExperienciaLaboral($conexion, $id_experiencia_laboral)
	{

		$sqlScript="delete from
		g_uath.experiencia_laboral
		where id_experiencia_laboral='$id_experiencia_laboral' and estado<>'Aceptado'";

		$res = $conexion->ejecutarConsulta($sqlScript);
		$row=pg_fetch_row($res);
		////$firephp->warn($res->lastInsertId());

		return $res;
	}



	public function buscarContratosFechas($conexion, $numeroContrato, $fechaInicio, $fechaFin)
	{
		$fechaFin = $fechaFin!="" ? "'" . $fechaFin . "'" : "NULL";
		$sqlScript="SELECT
		*
		FROM
		g_uath.datos_contrato
		WHERE
		numero_contrato='$numeroContrato'
		and fecha_inicio = '$fechaInicio'
		and fecha_fin = $fechaFin;";

		$res = $conexion->ejecutarConsulta($sqlScript);

		return $res;
	}


	public function cambiarEstadoContrato($conexion, $identificador)
	{

		$sqlScript="UPDATE
		g_uath.datos_contrato
		SET
		estado = 2
		WHERE
		identificador ='$identificador'
		and estado = 1 ;";

		$res = $conexion->ejecutarConsulta($sqlScript);

		return $res;
	}

	public function finalizarContrato($conexion, $idContrato, $observacion)
	{

		$sqlScript="UPDATE
		g_uath.datos_contrato
		SET
		estado = 3, --finalizado
		observacion = '$observacion'
		WHERE
		id_datos_contrato = $idContrato ;";

		$res = $conexion->ejecutarConsulta($sqlScript);

		return $res;
	}

	public function listarEspecies ($conexion){

		$cid = $conexion->ejecutarConsulta("select
				*
				from
				g_catalogos.especies
				where
				estado = 'activo'
				--estado = 1
				order by 2;");

		while ($fila = pg_fetch_assoc($cid)){
			$res[] = array(codigo=>$fila['id_especies'],nombre=>$fila['nombre'],estado=>$fila['estado']);
		}

		return $res;
	}



	public function listaFichaEmpleados($conexion,$identificador,$apellido,$nombre,$estado=null){

		$identificador = $identificador!="" ? "'" . $identificador . "'" : "null";
		$nombre = $nombre!="" ? "'%" . strtoupper($nombre) . "%'" : "null";
		$apellido = $apellido!="" ? "'" . strtoupper($apellido) . "%'" : "null";
		$estado = $estado!="" ? "'" . strtoupper($estado) . "'" : "null";

		$res = $conexion->ejecutarConsulta("SELECT
												fe.*,
												f.id_area
											FROM
												g_uath.mostrar_ficha_empleado(".$identificador.",".$nombre.",".$apellido.",".$estado.") fe
                                            INNER JOIN g_uath.datos_contrato dc ON dc.identificador = fe.identificador                                            
                                            LEFT JOIN g_estructura.funcionarios f ON fe.identificador = f.identificador
                                            WHERE dc.estado = 1
                                            ORDER BY 3;");
											
		return $res;
	}

	public function listaEmpleadosCapacitacion($conexion,$identificador,$apellido,$nombre, $titulo_capacitacion,$fecha_inicio,$fecha_fin){

		$identificador = $identificador!="" ? "'" . $identificador . "'" : "null";
		$nombre = $nombre!="" ? "'%" . strtoupper($nombre) . "%'" : "null";
		$apellido = $apellido!="" ? "'" . strtoupper($apellido) . "%'" : "null";
		$titulo_capacitacion = $titulo_capacitacion!="" ? "'%" . strtoupper($titulo_capacitacion) . "%'" : "null";
		$fecha_inicio = $fecha_inicio!="" ? "'" . $fecha_inicio . "'" : "null";
		$fecha_fin = $fecha_fin!="" ? "'" . $fecha_fin . "'" : "null";

		$res = $conexion->ejecutarConsulta("Select 
												c.*, 
												f.id_area
											from
												g_uath.mostrar_capacitacion_empleado(".$identificador.",".$nombre.",".$apellido.",".$titulo_capacitacion.",".$fecha_inicio.",".$fecha_fin.") c,
												g_estructura.funcionarios f
											where
												c.identificador = f.identificador order by 2;");

		return $res;
	}

	public function listaCapacitacionFuncionario($conexion,$identificador,$filtro){

		$identificador = $identificador!="" ? "'" . $identificador . "'" : "null";
		$busqueda="fe.identificador = dca.identificador and fe.identificador=".$identificador."";

		switch ($filtro){
			case 'TOTAL': $busqueda = $busqueda.''; break;
			case 'IDENTIFICADOR': $busqueda = $busqueda." and (dca.estado='Ingresado' OR dca.estado='Modificado')"; break;
			case 'APROBADOS': $busqueda = $busqueda." and (dca.estado='Aceptado')"; break;

		}


		$sqlScript="SELECT fe.identificador,
				fe.nombre,
				fe.apellido,
				dca.id_datos_capacitacion,
				dca.titulo_capacitacion,
				dca.pais,
				dca.institucion,
				dca.horas,
				dca.fecha_inicio AS fecha_inicio_capacitacion,
				dca.fecha_fin AS fecha_fin_capacitacion,
				dca.archivo_capacitacion,
				dca.estado,
				dca.observaciones

				FROM g_uath.ficha_empleado fe,
				g_uath.datos_capacitacion dca
				where
				" . $busqueda ." order by dca.fecha_inicio desc;";

		$res = $conexion->ejecutarConsulta($sqlScript);
		 
		return $res;
	}

	public function crearFichaEmpleado($conexion,$identificador,$apellido,$nombre,$tipo_documento,$nacionalidad,$genero,$estado_civil,$cedula_militar,$fecha_nacimiento,$edad,
			$tipo_sangre,$identificacion_etnica,$nacionalidad_indigena,$fotografia,$extension,$domicilio,$convencional,$celular,$mail_personal,$mail_institucional,
			$discapacidad_empleado,$carnet_conadis_empleado,$representante_discapacitado,$carnet_conadis_familiar,$provincia,$canton,$parroquia,
			$enfermedad_catastrofica,$nombre_enfermedad_catastrofica, $ruta_perfil_publico, $rutaQrPerfilPublico){
		$sqlScript="Insert into
		g_uath.ficha_empleado(identificador,
		apellido,nombre,
		tipo_documento,
		nacionalidad,
		genero,
		estado_civil,
		cedula_militar,
		fecha_nacimiento,
		edad,
		tipo_sangre,
		identificacion_etnica,
		nacionalidad_indigena,
		fotografia,extension_magap,
		domicilio,
		convencional,
		celular,
		mail_personal,
		mail_institucional,
		tiene_discapacidad,
		carnet_conadis_empleado,
		representante_familiar_discapacidad,
		carnet_conadis_familiar,
		id_localizacion_provincia,
		id_localizacion_canton,
		id_localizacion_parroquia,
		fecha_modificacion,
		estado_empleado,
		tiene_enfermedad_catastrofica,
		nombre_enfermedad_catastrofica,
        ruta_perfil_publico,
        ruta_qr_perfil_publico)
		values('$identificador',
		'$apellido','$nombre',
		'$tipo_documento',
		'$nacionalidad',
		'$genero',
		'$estado_civil',
		'$cedula_militar',
		'$fecha_nacimiento',
		'$edad',
		'$tipo_sangre',
		'$identificacion_etnica',
		'$nacionalidad_indigena',
		'$fotografia',
		'$extension',
		'$domicilio',
		'$convencional',
		'$celular',
		'$mail_personal',
		'$mail_institucional',
		'$discapacidad_empleado',
		'$carnet_conadis_empleado',
		'$representante_discapacitado',
		'$carnet_conadis_familiar',
		'$provincia',
		'$canton',
		'$parroquia',
		'now()','activo',
		'$enfermedad_catastrofica',
		'$nombre_enfermedad_catastrofica',
        '$ruta_perfil_publico',
        '$rutaQrPerfilPublico');";

		$res = $conexion->ejecutarConsulta($sqlScript);

		return $res;


	}

	public function actualizarFichaEmpleado($conexion,$identificador,$apellido,$nombre,$tipo_documento,$nacionalidad,$genero,$estado_civil,$cedula_militar,$fecha_nacimiento,$edad,
			$tipo_sangre,$identificacion_etnica,$nacionalidad_indigena,$fotografia,$extension,$domicilio,$convencional,$celular,$mail_personal,$mail_institucional,
			$discapacidad_empleado,$carnet_conadis_empleado,$representante_discapacitado,$carnet_conadis_familiar,$provincia,$canton,$parroquia,
			$enfermedad_catastrofica,$nombre_enfermedad_catastrofica, $ruta_perfil_publico, $rutaQrPerfilPublico){
		$sqlScript="Update
		g_uath.ficha_empleado
		set
		apellido='$apellido',
		nombre='$nombre',
		tipo_documento='$tipo_documento',
		nacionalidad='$nacionalidad',
		genero='$genero',
		estado_civil='$estado_civil',
		cedula_militar='$cedula_militar',
		fecha_nacimiento='$fecha_nacimiento',
		edad='$edad',
		tipo_sangre='$tipo_sangre',
		identificacion_etnica='$identificacion_etnica',
		nacionalidad_indigena='$nacionalidad_indigena',
		extension_magap='$extension',
		domicilio='$domicilio',
		convencional='$convencional',
		celular='$celular',
		mail_personal='$mail_personal',
		mail_institucional='$mail_institucional',
		id_localizacion_provincia='$provincia',
		id_localizacion_canton='$canton',
		id_localizacion_parroquia='$parroquia',
		tiene_discapacidad='$discapacidad_empleado',
		carnet_conadis_empleado='$carnet_conadis_empleado',
		representante_familiar_discapacidad='$representante_discapacitado',
		carnet_conadis_familiar='$carnet_conadis_familiar',
		tiene_enfermedad_catastrofica='$enfermedad_catastrofica',
		nombre_enfermedad_catastrofica='$nombre_enfermedad_catastrofica',
        ruta_perfil_publico = '$ruta_perfil_publico',
        ruta_qr_perfil_publico = '$rutaQrPerfilPublico',
		fecha_modificacion='now()'
		where
		identificador='$identificador';";
			
		$res = $conexion->ejecutarConsulta($sqlScript);

		return $res;
	}

	public function obtenerPuestoXArea($conexion, $idArea){

		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_catalogos.puestos
											where
												estado = 1 and
												id_area = '$idArea'
											order by
												nombre_puesto;");

		return $res;
	}

	public function obtenerPuestoXClasificacion($conexion, $clasificacion){

		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_catalogos.puestos
											where
												estado = 1 and
												clasificacion = '$clasificacion'
											order by
												nombre_puesto;");

		return $res;
	}

	public function obtenerGrupoOcupacionalXRegimenLaboral ($conexion, $idRegimenLaboral){

		$res = $conexion->ejecutarConsulta("SELECT
				*
				FROM
				g_catalogos.grupo_ocupacional
				WHERE
				estado = 1 and
				id_regimen_laboral = $idRegimenLaboral
				ORDER BY
				nombre_grupo asc");

		return $res;
	}
	
	public function reporteContratosConsolidado($conexion,$regimen_laboral,$provincia,$canton,$oficina,$coordinacion,$direccion,$gestion,$estado, $fechaInicio, $fechaFin){
	
		$regimen_laboral = $regimen_laboral!="" ? "'" . $regimen_laboral . "'" : "null";
		$provincia = $provincia!="" ? "'" . $provincia . "'" : "null";
		$canton = $canton!="" ? "'" . $canton . "'" : "null";
		$oficina = $oficina!="" ? "'" . $oficina . "'" : "null";
		$coordinacion = $coordinacion!="" ? "'" . $coordinacion . "'" : "null";
		$direccion = $direccion!="" ? "'" . $direccion . "'" : "null";
		$gestion = $gestion!="" ? "'" . $gestion . "'" : "null";
		$estado = $estado!="" ? "'" . $estado . "'" : "null";
		$fechaInicio = $fechaInicio!="" ? "'" . $fechaInicio . "'" : "null";
		$fechaFin = $fechaFin!="" ? "'" . $fechaFin . "'" : "null";
	    
	    $sql="SELECT
												*
											FROM
												g_uath.reporte_contratos(".$regimen_laboral.",".$provincia.",".$canton.",".
				$oficina.",".$coordinacion.",".$direccion.",".$gestion.",".$estado.",".$fechaInicio.",".$fechaFin.")
											ORDER BY
												provincia, coordinacion, direccion, gestion, apellido, nombre, fecha_inicio, fecha_fin;";
		$res = $conexion->ejecutarConsulta($sql);
	
		return $res;
	}
	
	public function reporteFuncionarioProfesionConsolidado($conexion,$regimen_laboral,$provincia,$canton,$oficina,$coordinacion,$direccion,
			$gestion,$nivelInstruccion, $titulo, $carrera, $estado){
	
		$regimen_laboral = $regimen_laboral!="" ? "'" . $regimen_laboral . "'" : "null";
		$provincia = $provincia!="" ? "'" . $provincia . "'" : "null";
		$canton = $canton!="" ? "'" . $canton . "'" : "null";
		$oficina = $oficina!="" ? "'" . $oficina . "'" : "null";
		$coordinacion = $coordinacion!="" ? "'" . $coordinacion . "'" : "null";
		$direccion = $direccion!="" ? "'" . $direccion . "'" : "null";
		$gestion = $gestion!="" ? "'" . $gestion . "'" : "null";
		$nivelInstruccion = $nivelInstruccion!="" ? "'" . $nivelInstruccion . "'" : "null";
		$titulo = $titulo!="" ? "'" . $titulo . "'" : "null";
		$carrera = $carrera!="" ? "'" . $carrera . "'" : "null";
		$estado = $estado!="" ? "'" . $estado . "'" : "null";
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_uath.reporte_profesiones(".$regimen_laboral.",".$provincia.",".$coordinacion.",".$direccion.",".$gestion.",
												".$nivelInstruccion.",".$titulo.",".$carrera.",".$estado.")
											ORDER BY
												provincia, coordinacion, direccion, gestion, apellido, nombre, nivel_instruccion, titulo, carrera, institucion;");
	
		return $res;
	}
	
	public function reporteFuncionarioConsolidado($conexion,$regimen_laboral,$provincia,$canton,$oficina,$coordinacion,$direccion,$gestion,
			$estadoCivil, $genero, $identificacionEtnica, $discapacidad){
	
		$regimen_laboral = $regimen_laboral!="" ? "'" . $regimen_laboral . "'" : "null";
		$provincia = $provincia!="" ? "'" . $provincia . "'" : "null";
		$canton = $canton!="" ? "'" . $canton . "'" : "null";
		$oficina = $oficina!="" ? "'" . $oficina . "'" : "null";
		$coordinacion = $coordinacion!="" ? "'" . $coordinacion . "'" : "null";
		$direccion = $direccion!="" ? "'" . $direccion . "'" : "null";
		$gestion = $gestion!="" ? "'" . $gestion . "'" : "null";
		$estadoCivil = $estadoCivil!="" ? "'" . $estadoCivil . "'" : "null";
		$genero = $genero!="" ? "'" . $genero . "'" : "null";
		$identificacionEtnica = $identificacionEtnica!="" ? "'" . $identificacionEtnica . "'" : "null";
		$discapacidad = $discapacidad!="" ? "'" . $discapacidad . "'" : "null";
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_uath.reporte_funcionarios(".$regimen_laboral.",".$provincia.",".$canton.",".$oficina.",".$coordinacion.",".$direccion.",".$gestion.",
												".$estadoCivil.",".$genero.",".$identificacionEtnica.",".$discapacidad.")
											ORDER BY
												provincia, coordinacion, direccion, gestion, apellido, nombre, estado_civil, genero, identificacion_etnica;");
	
		return $res;
	}
	
	public function inactivarTodosContratos($conexion, $identificador, $observacion, $estado){
			
		$sqlScript="UPDATE
						g_uath.datos_contrato
					SET
						estado = $estado,
						observacion = '$observacion'
					WHERE
						identificador = '$identificador'
						and estado = 1;";
	
		$res = $conexion->ejecutarConsulta($sqlScript);
	
		return $res;
	}
	
	public function obtenerResultadosEvaluacionDesempenioAnual ($conexion, $identificador){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_uath.datos_evaluacion_desempenio_anual
											WHERE
												estado = 1 and
												identificador = '$identificador'
											ORDER BY
												anio asc");
	
				return $res;
	}
	
	
	public function obtenerInformacionFuncionarioContratoActivo ($conexion, $identificador){
	
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_uath.ficha_empleado fe,
												g_uath.datos_contrato dc
											where
												fe.identificador = dc.identificador and
												fe.identificador = '$identificador' and
												dc.estado = 1");
	
				return $res;
	}
	
	public function buscarFuncionario ($conexion, $usuario){
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_estructura.funcionarios
											WHERE
												identificador = '$usuario';");
		return $res;
	}
	
	public function guardarFuncionario ($conexion, $idGestion, $usuario,$administrador,$activo,$idProvincia,$idCanton,$idOficina,$estado){
		$res = $conexion->ejecutarConsultaLOGS("INSERT INTO 
													g_estructura.funcionarios(id_area, identificador, administrador, activo, id_provincia, id_canton, id_oficina, estado)
											VALUES('$idGestion', '$usuario',$administrador,$activo,$idProvincia,$idCanton,$idOficina,$estado);");
		return $res;
	}
	
	public function actualizarFuncionario($conexion, $idGestion, $usuario,$administrador,$activo,$idProvincia,$idCanton,$idOficina,$estado){
	
		$res = $conexion->ejecutarConsultaLOGS("UPDATE
												g_estructura.funcionarios
											SET
												id_area = '$idGestion',
												administrador = $administrador,
												activo = $activo,
												id_provincia = $idProvincia,
												id_canton = $idCanton,
												id_oficina = $idOficina,
												estado=$estado
											WHERE
												identificador='$usuario';");
	
		return $res;
	
	}
	
	public function enviarEstadoEstructuraFuncionario($conexion, $identificador, $estado){
	
		$sqlScript="UPDATE
						g_estructura.funcionarios
					SET
						estado=$estado
					WHERE
						identificador='$identificador'";
	
		$res = $conexion->ejecutarConsulta($sqlScript);
	
		return $res;
	}
//--------------------------inactivar minutos disponibles--------------------------------------------------	
	public function inactivarMinutosDisponibles($conexion, $identificador, $estado){
	
		$sqlScript="UPDATE
							g_vacaciones.minutos_disponibles_funcionarios
		            SET
							activo=$estado
					WHERE
							identificador='$identificador'";
	
		$res = $conexion->ejecutarConsulta($sqlScript);
	
		return $res;
	}
//--------------------------inactivar tiempo disponible funcionarios nueva tabla------------------------------------------------
	public function inactivarTiempoDisponibles($conexion, $identificador, $estado){
		
		$sqlScript="UPDATE
							g_vacaciones.tiempo_disponible_funcionarios
		            SET
							activo=$estado
					WHERE
							identificador='$identificador'";
		
		$res = $conexion->ejecutarConsulta($sqlScript);
		
		return $res;
	}
//-------------------------------obtener funcionarios de estructura-------------------------------------------------------------------------
	
	public function filtroObtenerFuncionarios($conexion, $identificador, $apellido, $nombre, $responsable, $area){
		
		$busqueda = '';
		$busque='are.id_area = fu.id_area and';
		if($identificador != ''){
			$busqueda = "and fu.identificador IN ('$identificador')";
		}
	
		if($apellido != ''){
			$busqueda .= " and fu.identificador IN (SELECT identificador FROM g_uath.ficha_empleado WHERE upper(apellido) like upper('%$apellido%'))";
		}
	
		if($nombre != ''){
			$busqueda .= " and fu.identificador IN (SELECT identificador FROM g_uath.ficha_empleado WHERE upper(nombre) like upper('%$nombre%'))";
		}
			
		if($responsable!=''){
			$busque='are.id_area = res.id_area and res.identificador=fu.identificador and res.responsable = true and
						res.activo=1 and';
			$tablas=',g_estructura.responsables res';
			//$busqueda .= ' and fu.identificador IN (SELECT identificador FROM g_estructura.responsables WHERE responsable = true and estado=1)';
		}
	
		if($area != ''){						
			$areaProceso =$this->buscarDivisionEstruc($conexion, $area);
			while($fila = pg_fetch_assoc($areaProceso)){
				if(strcmp($fila['clasificacion'], 'Oficina Técnica')==0){
					$areaProceso2 =$this->buscarDivisionEstruc($conexion, $fila['id_area']);
					while($fila2 = pg_fetch_assoc($areaProceso2))
					$areaSubproceso .= "'".$fila2['id_area']."',";
				}else
				$areaSubproceso .= "'".$fila['id_area']."',";
			}
			$areaSubproceso = "(".rtrim($areaSubproceso,',').")";
			if($responsable!='')	
				$busqueda .= ' and fu.identificador IN (SELECT identificador FROM g_estructura.responsables WHERE id_area IN '.$areaSubproceso.')';
			else 
				$busqueda .= ' and fu.identificador IN (SELECT identificador FROM g_estructura.funcionarios WHERE id_area IN '.$areaSubproceso.')';
		}
		
	  $sql = "SELECT
							fu.identificador,
							are.id_area as area,
							are.id_area_padre as padre,
							fe.apellido ||' '||fe.nombre as nombre,
							are.nombre as nombrearea
					FROM
							g_estructura.funcionarios fu,
							g_estructura.area are,
							g_uath.ficha_empleado fe
							".$tablas."
					WHERE
							fu.identificador = fe.identificador and 
							".$busque."						
							fe.estado_empleado='activo'
							" .$busqueda." order by 2;";
		
		//$res = LOGUATH::LOGS($conexion->conectar, $sql);
		
		$res = $conexion->ejecutarConsulta($sql);
		return $res;
	
	}
	//-------------------------------obtener funcionarios de estructura-------------------------------------------------------------------------
	
	public function filtroObtenerFuncionariosSubrogaciones($conexion, $identificador, $apellido, $nombre, $responsable, $area){
	
		$busqueda = 'and fu.identificador_subrogador = fe.identificador';
		$busque='are.id_area = fu.area and';
		if($identificador != ''){
			$busqueda = "and fu.identificador IN ('$identificador') and fu.identificador = fe.identificador and";
		}
	
		if($apellido != ''){
			$busqueda .= " and fu.identificador_subrogador IN (SELECT identificador FROM g_uath.ficha_empleado WHERE upper(apellido) like upper('%$apellido%'))";
		}
	
		if($nombre != ''){
			$busqueda .= " and fu.identificador_subrogador IN (SELECT identificador FROM g_uath.ficha_empleado WHERE upper(nombre) like upper('%$nombre%'))";
		}
			
		if($responsable!=''){
			$busque='are.id_area = res.id_area and res.identificador=fu.identificador_subrogador and res.responsable = true and
			res.activo=1 and';
			$tablas=',g_estructura.responsables res';
			//$busqueda .= ' and fu.identificador IN (SELECT identificador FROM g_estructura.responsables WHERE responsable = true and estado=1)';
		}	
		
		$sql = "SELECT
							fu.identificador,
							fu.identificador_subrogador,
							are.id_area as area,
							are.id_area_padre as padre,
							fe.apellido ||' '||fe.nombre as nombre,
							are.nombre as nombrearea,
							fu.fecha_inicio,
							fu.fecha_fin
						FROM
							g_subrogacion.responsable fu,
					
							g_estructura.area are,
							g_uath.ficha_empleado fe
							".$tablas."
						WHERE
						
							fu.area = '$area' and 
							".$busque."
							fe.estado_empleado='activo'
							" .$busqueda." order by 2";
	
		//$res = LOGUATH::LOGS($conexion->conectar, $sql);
	
		$res = $conexion->ejecutarConsulta($sql);
		return $res;
	
	}
	//--------------------------verificar responsable-------------------------------------------------------------------------------
	
	public function verificarResponsable($conexion, $identificador, $area ,$estado='SI'){
		
		if($estado != ''){
			$busque='and res.responsable = true and res.activo=1 ';
		}
		
		if($area != ''){
			$busque.="and res.id_area='".$area."'";
		}
		
		$sql="  SELECT
						*
				FROM
						g_estructura.responsables res
	
				WHERE
						res.identificador='".$identificador ."'
						".$busque." ";
		$res = $conexion->ejecutarConsulta($sql);
		return $res;	
	}

	
	public function verificarResponsablePuesto($conexion, $identificador, $area){
	
		$sql="	SELECT
					*
				FROM
					g_estructura.responsables_puestos pu		
				WHERE
					pu.identificador='".$identificador ."' and
					pu.puesto = true and
					pu.id_area='".$area."' ";
				$res = $conexion->ejecutarConsulta($sql);
		return $res;
	}
	//-------------------------------------------------------------------------------------------------------------------------------------------
	public function buscarDivisionEstruc($conexion, $areaPadre){
	   $sql="select
										*
								from
										g_estructura.area
								where
										id_area_padre = '$areaPadre' and estado=1
											
							UNION
								
								select
										*
								from
										g_estructura.area
								where
										id_area = '$areaPadre' and estado=1
								order by
								id_area asc;";
		 $res = $conexion->ejecutarConsulta($sql);
	
				return $res;
	}
	//-------------------------------------------------------------------------------------------------------------------------------------------

	public function filtroObtenerFuncionario($conexion, $identificador, $estado, $apellido, $nombre, $area, $tipoReporte){
	
		$busqueda = '';
		$parametros = '';
		$agrupacion = '';
		$orden = '';
	
		if($tipoReporte != 'unico'){
			$parametros = "mdf.*, fe.nombre, fe.apellido";
			$orden = "ORDER BY 1,2";
		}else{
			$parametros = "sum(minutos_disponibles) as minutos_disponibles, fe.nombre, fe.apellido, mdf.identificador";
			$agrupacion = "GROUP BY fe.nombre, fe.apellido, mdf.identificador";
		}
	
		if($identificador != ''){
			$busqueda = "and mdf.identificador IN ('$identificador')";
		}
	
		if($apellido != ''){
			$busqueda .= " and mdf.identificador IN (SELECT identificador FROM g_uath.ficha_empleado WHERE upper(apellido) like upper('%$apellido%'))";
		}
	
		if($nombre != ''){
			$busqueda .= " and mdf.identificador IN (SELECT identificador FROM g_uath.ficha_empleado WHERE upper(nombre) like upper('%$nombre%'))";
		}
	
		if($area != ''){
				
			if($area == 'DE'){
					
				$areaSubproceso = "'".$area."',";
					
			}else{
				//$ca = new ControladorAreas();
				//$areaProceso = $ca->buscarDivisionEstructura($conexion, $area);
				$areaProceso = $conexion->ejecutarConsulta("select
																*
															from
																g_estructura.area
															where
																id_area_padre = '$area'
																
															UNION
																
															select
																*
															from
																g_estructura.area
															where
																id_area = '$area'
															order by
																id_area asc;");
					
				while($fila = pg_fetch_assoc($areaProceso)){
					$areaSubproceso .= "'".$fila['id_area']."',";
				}
			}
				
			$areaSubproceso = "(".rtrim($areaSubproceso,',').")";
				
			$busqueda .= ' and mdf.identificador IN (SELECT identificador FROM g_estructura.funcionarios WHERE id_area IN '.$areaSubproceso.')';
				
		}
	
		if($estado != '')
			$estadosql= "and mdf.activo = '$estado'";
		else $estadosql='';
	
	
		$res = $conexion->ejecutarConsulta("SELECT
				".$parametros."
				FROM
				g_vacaciones.minutos_disponibles_funcionarios mdf,
				g_uath.ficha_empleado fe
				WHERE
				mdf.identificador = fe.identificador
				".$estadosql."
				" .$busqueda."
				".$agrupacion."
				".$orden."");
	
		return $res;
	}
//-------------------------------------------------------------------------------------------------------------------	
	public function inactivarActivarResponsables($conexion, $area,$estado,$responsable,$activo,$identificador,$prioridad=NULL){
					$paramet=''; 
					if($prioridad != ''){
						$paramet=', prioridad='.$prioridad;
					    if($prioridad == 3)$paramet.= ', fecha_fin=now()';
					    if($prioridad == 1 or $prioridad == 2)$paramet.= ', fecha_inicio=now()';
					}	
					
					$parametros='';
					if($area != ''){
						$parametros="id_area='$area'";
						if($identificador != ''){
							$parametros.=" and identificador='$identificador'";
						}
					}
					
					if($identificador != ''){	    
						$parametros="identificador='$identificador'";	
						if($area != ''){
							$parametros.=" and id_area='$area'";
						}
					}
					
						
				$sql="UPDATE
						g_estructura.responsables
					SET
						estado=$estado, responsable=$responsable, activo=$activo 
						".$paramet."
					WHERE
						".$parametros." " ;
		
		$res = $conexion->ejecutarConsultaLOGS($sql);		
		return $res;						
	}
//---------------------------------------------------------------------------------------------------------------------
	public function verificarExisteResponsable($conexion, $area,$identificador){
		
		$sql="SELECT
		   			*
		   	  FROM 
		   	     g_estructura.responsables
        	  WHERE
		         identificador= '".$identificador."' and 
				 id_area= '".$area."' " ;
	
		$res = $conexion->ejecutarConsulta($sql);
		return $res;
	}
//---------------------------------------------------------------------------------------------------------------------
	public function crearResponsable($conexion, $area,$identificador,$prioridad,$reponsable,$activo,$estado){
	
		$sql="INSERT INTO 
		       			g_estructura.responsables(
               			id_area, identificador, prioridad, responsable, activo, estado)
              VALUES 
              			('$area','$identificador',$prioridad, $reponsable, $activo, $estado) ";
	
		$res = $conexion->ejecutarConsultaLOGS($sql);
		return $res;
	}
	
//---------------------------------------------------------------------------------------------------------------------
	//---------------------------------------------------------------------------------------------------------------------
	public function asignarPerfilResponsable($conexion, $identificador,$perfil){
	
		$sql="INSERT INTO
						g_usuario.usuarios_perfiles(identificador, id_perfil)
				 VALUES
						('$identificador',$perfil) ";
	
		$res = $conexion->ejecutarConsultaLOGS($sql);
		return $res;
	}
	
	//---------------------------------------------------------------------------------------------------------------------
	public function asignarAplicacionResponsable($conexion, $identificador,$idAplicacion,$mensaje){
	
		$sql="INSERT INTO 
					g_programas.aplicaciones_registradas(id_aplicacion, identificador, cantidad_notificacion, mensaje_notificacion)
    		  VALUES 
    		  		($idAplicacion, '".$identificador."', 0, '".$mensaje."');";
	
		$res = $conexion->ejecutarConsultaLOGS($sql);
		return $res;
	}
	//---------------------------------------------------------------------------------------------------------------------
	
	//------------------------------------registrar perfiles de funcionario en subrogacion------------------------------------
	public function asignarPerfilSubrogacion($conexion, $idResponsable,$perfil){
	
		$sql="INSERT INTO
					g_subrogacion.perfil (id_responsable, id_perfil)
				VALUES
				   ($idResponsable,$perfil) ";
	
		$res = $conexion->ejecutarConsultaLOGS($sql);
		return $res;
	}
	
	//---------------------------------------registrar aplicaciones de funcionario  en subrogacion------------------------------
	public function asignarAplicacionSubrogacion($conexion, $idAplicacion, $idResponsable){
	
		$sql="INSERT INTO
					g_subrogacion.aplicacion (id_responsable, id_aplicacion)
					VALUES
					($idResponsable, $idAplicacion);";
	
		$res = $conexion->ejecutarConsultaLOGS($sql);
		return $res;
	}
	//---------------------------------------------------------------------------------------------------------------------
	
	public function verificarPermisosResponsable($conexion, $perfil,$identificador){
	
		$sql="SELECT
		   			*
		   	  FROM 
		   	     g_usuario.usuarios_perfiles
        	  WHERE
		         identificador= '".$identificador."' and 
				 id_perfil= $perfil " ;
	
		$res = $conexion->ejecutarConsulta($sql);
		return $res;
	}	
	
	//---------------------------------------------------------------------------------------------------------------------
	public function asignarResponsable($conexion, $identificador,$identificadorSubrogar,$fechaIni, $fechaFin, $area, $estado ){
	 
		$sql="INSERT INTO 
					g_subrogacion.responsable
							(identificador, identificador_subrogador, fecha_inicio, 
            				fecha_fin, area, estado, fecha_creacion)
    		   VALUES ('".$identificador."', '".$identificadorSubrogar."','".$fechaIni."','".$fechaFin."', 
          			  '".$area."','".$estado."', now() ) RETURNING id_responsable; " ;
	
		$res = $conexion->ejecutarConsultaLOGS($sql);
		return $res;
	}
	//---------------------------------------------------------------------------------------------------------------------

	public function devolverPerfilesNuevos($conexion, $identificador,$identificadorSubrogar){
		
		$sql="SELECT 
					id_perfil
			  FROM 
			  		g_usuario.usuarios_perfiles 
			  where identificador='".$identificador."' and id_perfil not in
					(SELECT id_perfil FROM g_usuario.usuarios_perfiles where identificador='".$identificadorSubrogar."')";
		
		$res = $conexion->ejecutarConsulta($sql);
		return $res;		
	}
	//---------------------------------------------------------------------------------------------------------------------
	public function devolverAplicacionesNuevas($conexion, $identificador,$identificadorSubrogar){
	
		$sql="SELECT 
					id_aplicacion, 
					mensaje_notificacion
  			  FROM 
  			  		g_programas.aplicaciones_registradas 
  			  where identificador='".$identificador."' and id_aplicacion not in 
 					 (SELECT id_aplicacion FROM g_programas.aplicaciones_registradas where identificador='".$identificadorSubrogar."')";
	
		$res = $conexion->ejecutarConsulta($sql);
		return $res;
	}
	//--------------------------------obtener datos subrogacion---------------------------------------------
	public function obtenerSubrogacionesFuncionarios($conexion, $idArea,$identificador,$estado){
		
		$busqueda="estado not in ('inactivo')";
		if($estado != '')
			$busqueda = "and estado IN ('$estado')";
			
		if($identificador != '')
			$busqueda .= "and identificador_subrogador IN ('$identificador')";

		if($idArea != '')
			$busqueda .= "and area ='".$idArea."'";
		
		 $sql="SELECT
					 *
  			  FROM 
					g_subrogacion.responsable
		      WHERE
					".$busqueda."; ";
	
		$res = $conexion->ejecutarConsulta($sql);
		return $res;
	}
	//---------------------------------------------------------------------------------------------------------------------
	
	public function actualizarResponsables($conexion, $identificador,$identificadorSubrogar,$fechaIni, $fechaFin, $area, $estado, $idResponsable){
	
		$consult='';
		if($identificadorSubrogar != ''){
			$consult='identificador_subrogador='.$identificadorSubrogar.',';
			
		}
		
		$sql="UPDATE 
					g_subrogacion.responsable
   			  SET 
   			  		identificador='".$identificador."', 
   			  		$consult
       				fecha_inicio='".$fechaIni."', 
       				fecha_fin='".$fechaFin."', 
       				estado='".$estado."'
 			  WHERE 
					id_responsable=$idResponsable;";
	
		$res = $conexion->ejecutarConsultaLOGS($sql);
		return $res;
	}
	//-------------------------------------------------------------------------------------------------------------------
	public function actualizarResponsablesEstado($conexion, $estado, $idResponsable){
	
		$sql="UPDATE
					g_subrogacion.responsable
			 SET
					estado='".$estado."'
			WHERE
					id_responsable=$idResponsable;";
		$res = $conexion->ejecutarConsultaLOGS($sql);
		return $res;
	}
	//-------------------------------------------------------------------------------------------------------------------	
	
	public function elminarPerfilSubrogar($conexion, $idResponsable){
	
		$sql="DELETE FROM 
					g_subrogacion.perfil
			 WHERE
					id_responsable=$idResponsable;";
	
		$res = $conexion->ejecutarConsultaLOGS($sql);
		return $res;
	}
	//-------------------------------------------------------------------------------------------------------------------
	public function eliminarAplicacionSubrogar($conexion, $idResponsable){
		$sql="DELETE FROM
					g_subrogacion.aplicacion
			  WHERE
					id_responsable=$idResponsable;";
		
		$res = $conexion->ejecutarConsultaLOGS($sql);
		return $res;
	}
	//-------------------------------------------------------------------------------------------------------------------
	
	//---------------------------------------------------------------------------------------------------------------------
	
	public function devolverPerfilSubrogar($conexion, $idResponsable){
	
		$sql="SELECT
					id_perfil
			  FROM
					g_subrogacion.perfil
			  where 
		            id_responsable=$idResponsable";
	
		$res = $conexion->ejecutarConsulta($sql);
		return $res;
	}
	//---------------------------------------------------------------------------------------------------------------------
	public function devolverAplicacionSubrogar($conexion, $idResponsable){
	
		$sql="SELECT
					*
			  FROM
					g_subrogacion.aplicacion
			  where 
		            id_responsable=$idResponsable";	
		$res = $conexion->ejecutarConsulta($sql);
		return $res;
	}
	//-------------------------------------------------------------------------------------------------------------------
	
	public function elminarPerfilUsuario($conexion, $idPerfil, $identificador){
	
		$sql="DELETE FROM
					g_usuario.usuarios_perfiles
	  		  WHERE
					id_perfil=$idPerfil and
					identificador='$identificador';";
	
		$res = $conexion->ejecutarConsultaLOGS($sql);
		return $res;
	}
	//-------------------------------------------------------------------------------------------------------------------
	public function eliminarAplicacionUsuario($conexion, $idAplicacion, $identificador){
	$sql="DELETE FROM
					g_programas.aplicaciones_registradas
		  WHERE
					id_aplicacion=$idAplicacion and
					identificador='$identificador';";
	
		$res = $conexion->ejecutarConsultaLOGS($sql);
		return $res;
	}
	//-------------------------------------------------------------------------------------------------------------------
	public function obtenerFechasContratoUsuario($conexion, $identificador){
			
		$sql="SELECT  
					fecha_inicio, fecha_fin
			  FROM 
			  		g_uath.datos_contrato
			  WHERE 
					identificador='$identificador' 
			  ORDER BY 1;";
	
		$res = $conexion->ejecutarConsultaLOGS($sql);
		return $res;
	}
	//-------------------------------------------------------------------------------------------------------------------
	
	public function guardarArchivoExcel($conexion, $mesRol,$anio,$rutaArchivo ){
	
		$sql="INSERT INTO
						g_uath.excel_rol_pago
						(mes_rol, anio, ruta_archivo, fecha_creacion)
				VALUES 
						('".$mesRol."', '".$anio."','".$rutaArchivo."', now() ) RETURNING id_excel_pago; " ;	
		$res = $conexion->ejecutarConsulta($sql);
		return $res;
	}
	
	//---------------------------------------------------------------------------------------------------------------------
	public function obtenerRolPagos($conexion, $identificador,$tipoConsul,$idFucionarioRol=NULL, $anio=NULL){
		
		if($tipoConsul == 'SI')$consult='distinct anio';
		else $consult='pa.id_excel_rol,pa.nombre_archivo, pa.ruta_archivo';
		$idFucionarioRol = $idFucionarioRol != "" ? "'" .  $idFucionarioRol  . "'" : "NULL";
		$identificador = $identificador != "" ? "'" .  $identificador  . "'" : "NULL";
		$anio = $anio != "" ? "'" .  $anio  . "'" : "NULL";
		
		$sql="SELECT
					$consult
			  FROM
					g_uath.funcionario_rol_pagos pa,
					g_uath.excel_rol_pago rol
			  WHERE
			        ($identificador is NULL or  pa.identificador = $identificador) and 
			        ($idFucionarioRol is NULL or  id_funcionario_rol_pago = $idFucionarioRol) and
			        ($anio is NULL or  anio = $anio) and
			        rol.id_excel_rol = pa.id_excel_rol
			 order by 1 desc limit 24 " ;	
		$res = $conexion->ejecutarConsulta($sql);
		return $res;
	}
	//---------------------------------------------------------------------------------------------------------------------
	public function guardarNuevoExcelRolPagos ($conexion, $mes, $ano,$rutaArchivoExcel,$nombreArchivoExcel,$area){
		$sql="INSERT INTO g_uath.excel_rol_pago(
		mes_rol, anio, ruta_archivo,nombre_archivo, fecha_creacion,clasificacion)
		VALUES ( '$mes', '$ano', '$rutaArchivoExcel','$nombreArchivoExcel', now(),'$area') RETURNING id_excel_rol;";
		$res = $conexion->ejecutarConsulta($sql);
		return $res;
	}	
//-------------------------------------------------------------------------------------------------------------------------------	
	public function guardarNuevoRolPagos ($conexion, $idExcelRol, $identificador,$rutaArchivoPdf,$nombreArchivoPdf,$area){
		$sql="
				INSERT INTO g_uath.funcionario_rol_pagos(
				id_excel_rol, identificador, ruta_archivo, nombre_archivo, fecha_creacion, clasificacion,estado_mail)
				VALUES ($idExcelRol, '$identificador', '$rutaArchivoPdf','$nombreArchivoPdf', now(),'$area',true) RETURNING id_funcionario_rol_pago;";
		$res = $conexion->ejecutarConsulta($sql);
		return $res;
	}
//----------------------------Obtener listado de servidores----------------------------------------------------------------------------------------------	
	public function obtenerListadoRolPagos ($conexion, $estadoMail){
		$sql="
			SELECT 
					identificador, ruta_archivo, nombre_archivo, id_funcionario_rol_pago
  			FROM 
  					g_uath.funcionario_rol_pagos
  			WHERE 
					estado_mail= $estadoMail limit 10;";
			$res = $conexion->ejecutarConsulta($sql);
		return $res;
	}
//----------------------------------------------------------------------------------------------------------------------------	
	public function actualizarEstadoMailRolPagos ($conexion, $idFuncionario){
		$sql="
				UPDATE 
						g_uath.funcionario_rol_pagos
  				SET 
  						estado_mail=FALSE
 				WHERE 
						id_funcionario_rol_pago=$idFuncionario;";
		$res = $conexion->ejecutarConsulta($sql);
		return $res;
	}
//----------------------------------------------------------------------------------------------------------------------------
	public function buscarExcelRolPagos ($conexion, $mes, $ano, $area, $idExcelRol=null){
		$numer=4;
		$area= $area != "" ? "'" .  $area  . "'" : "NULL";
		$mes = $mes != "" ? "'" .  $mes  . "'" : "NULL";
		$ano = $ano != "" ? "'" .  $ano  . "'" : "NULL";
		$idExcelRol = $idExcelRol != "" ? "'" .  $idExcelRol  . "'" : "NULL";
		$consulta="mes_rol,
		anio,
		ruta_archivo,
		id_excel_rol,
		nombre_archivo";
		if(($mes=="NULL") && ($ano=="NULL") && ($idExcelRol=="NULL")){
			$busqueda = " limit 24";
		}

		$res = $conexion->ejecutarConsulta("SELECT 
													$consulta
											FROM 
													g_uath.excel_rol_pago 
											where   ($area is NULL or  clasificacion = $area) and 
													($mes is NULL or  mes_rol = $mes) and 
													($ano is NULL or  anio = $ano) and 
													($idExcelRol is NULL or  id_excel_rol = $idExcelRol) 
													order by $numer DESC ".$busqueda." ;");
		return $res;
	}
	
	public function obtenerRolPagosXmesExcel ($conexion, $idExcelRol){
	
		$res = $conexion->ejecutarConsulta("
				SELECT 
						frp.identificador, 
						frp.ruta_archivo ruta_archivo_pdf,
						frp.id_funcionario_rol_pago,
						erp.mes_rol, 
						erp.anio, 
						Upper(fe.nombre ||' '||fe.apellido) as nombre_completo
						
				FROM 
						g_uath.funcionario_rol_pagos frp, 
						g_uath.excel_rol_pago erp,
				        g_uath.ficha_empleado fe 
				where 
						erp.id_excel_rol=frp.id_excel_rol and 
						erp.id_excel_rol='$idExcelRol' and
				        fe.identificador=frp.identificador order by fe.nombre asc;
				;");
		return $res;
	}
 public function  obtenerDatosUsuarioAgrocalidad($conexion,$identificador){
	
		$res = $conexion->ejecutarConsulta("SELECT
				identificador,
				Upper(nombre ||' '||apellido) as nombre_completo,
				mail_personal,
				mail_institucional,
				fecha_nacimiento
				FROM
				g_uath.ficha_empleado
				WHERE
				identificador='$identificador';");
	
		return $res;
	}
	
//--------------------------------------------------------------------------------------	
	public function  obtenerFuncionariosEvaluacionIndividual($conexion){
	
		$res = $conexion->ejecutarConsulta("select 
 			distinct identificador_evaluador, 
 			g_uath.cambia_formato_nombre(fe.nombre,fe.apellido) AS nombres_completos 
 		from 
 			g_evaluacion_desempenio.aplicantes_individual a,
 			g_uath.ficha_empleado fe
 		where 
 			id_evaluacion = 3 and 
 			estado = true and
 			fe.identificador = a.identificador_evaluador order by 2;");
	
		return $res;
	}	
	//--------------------------------------------------------------------------------------
	public function  obtenerFuncionariosEvaluacion($conexion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												distinct identificador_evaluador,
												--a.*,
												g_uath.cambia_formato_nombre(fe.nombre,fe.apellido) AS nombres_completos
										FROM
												g_evaluacion_desempenio.aplicantes a,
												g_evaluacion_desempenio.tipos_evaluaciones te,
												g_evaluacion_desempenio.evaluaciones e,
												g_uath.ficha_empleado fe
										WHERE
												--identificador_evaluador = '1709544959' and
												e.id_evaluacion =  3 and
												a.id_tipo_evaluacion = te.id_tipo_evaluacion and
												te.id_evaluacion = e.id_evaluacion and
												a.estado in (true) and
												te.estado = 1 and
												e.estado = 1 and
												fe.identificador = a.identificador_evaluador;");
	
		return $res;
	}
//---------------------------------------------------------------------------------------
	public function filtroObtenerEncargo($conexion, $identificador, $apellido, $nombre, $estado, $area,$distinct,$selector,$puesto=NULL,$areaGrupo=NULL){
		
		try {
			
		$consul2='pue.identificador_responsable';
		$consul3=', ruta_subrogacion, pue.id_permiso_empleado';
		if($distinct!=''){$distinct='distinct'; $consul3='';}		
		if($selector != ''){
			$consul1='pue.identificador_encargado, 
					  pue.fecha_ini, 
				      pue.fecha_fin, 
					  pue.nombre_puesto_encargado, ';
			$consul2='pue.identificador_encargado';
			
		}
		$busqueda = '';
		$busque='are.id_area = pue.id_area and';
		if($identificador != ''){
			$busqueda = "and pue.identificador_responsable IN ('$identificador')";
		}	
		if($estado != ''){
			$busqueda .= " and pue.estado IN ('$estado')";
		}
		if($apellido != ''){
			$busqueda .= " and fu.identificador IN (SELECT identificador FROM g_uath.ficha_empleado WHERE upper(apellido) like upper('%$apellido%'))";
		}	
		if($nombre != ''){
			$busqueda .= " and fu.identificador IN (SELECT identificador FROM g_uath.ficha_empleado WHERE upper(nombre) like upper('%$nombre%'))";
		}
		if($puesto != ''){
			$busqueda .= " and upper(pue.nombre_puesto) like upper('$puesto%')";
		}		
	
		if($area != ''){
			
			$areaSubproceso .= "'".$area."',";
			$areaSubproceso = "(".rtrim($areaSubproceso,',').")";
				if($distinct!='')
					$busqueda .= ' and pue.identificador_responsable IN (SELECT identificador FROM g_estructura.responsables WHERE id_area IN '.$areaSubproceso.') ';
				else
					$busqueda .= ' and pue.identificador_responsable IN (SELECT identificador FROM g_estructura.funcionarios WHERE id_area IN '.$areaSubproceso.')';
		}
		
		if($areaGrupo != ''){
			$areaProceso =$this->buscarDivisionEstruc($conexion, $areaGrupo);
			while($fila = pg_fetch_assoc($areaProceso)){
				if(strcmp($fila['clasificacion'], 'Oficina Técnica')==0){
					$areaProceso2 =$this->buscarDivisionEstruc($conexion, $fila['id_area']);
					while($fila2 = pg_fetch_assoc($areaProceso2))
						$areaSubproceso .= "'".$fila2['id_area']."',";
				}else
					$areaSubproceso .= "'".$fila['id_area']."',";
			}
			$areaSubproceso = "(".rtrim($areaSubproceso,',').")";
			if($distinct!='')
				$busqueda .= ' and pue.identificador_responsable IN (SELECT identificador FROM g_estructura.responsables WHERE id_area IN '.$areaSubproceso.')';
			else
				$busqueda .= ' and pue.identificador_responsable IN (SELECT identificador FROM g_estructura.funcionarios WHERE id_area IN '.$areaSubproceso.')';
		}
		
		
	   $sql = "SELECT 
							$distinct pue.identificador_responsable, 
							".$consul1."
							are.id_area as area, 
							are.id_area_padre as padre, 
							fe.apellido ||' '||fe.nombre as nombre, 
							are.nombre as nombrearea,
						    pue.designacion,
						    pue.nombre_puesto						    
						    ".$consul3."
						FROM 
							
							g_estructura.funcionarios fu, 
							g_estructura.area are, 
							g_uath.ficha_empleado fe,
							g_subrogacion.responsables_puestos pue 
							
						WHERE 
							fu.identificador = fe.identificador and 
							".$busque."
							".$consul2."=fu.identificador
							" .$busqueda." order by 2;";
			
		$res = $conexion->ejecutarConsulta($sql);
		return $res;
		} catch (Exception $e) {
			
			$conexion->ejecutarLogsTryCatch($e);
		}
	}
//---------------------------------------------------------------------------------------
	public function filtroObtenerDatosFuncionario($conexion, $identificador){
		
		$sql="
			SELECT 
					identificador, 
					nombre, 
					apellido, 
					domicilio, 
					convencional,
				    celular, 
				    mail_personal, 
				    mail_institucional,
				    tipo_empleado
  			FROM 
					g_uath.ficha_empleado 
			WHERE 
					identificador='$identificador';";
		$res = $conexion->ejecutarConsulta($sql);
		return $res;
	}
	//---------------------------------------------------------------------------------------
	public function filtroObtenerNombreArea($conexion, $idArea){
		$sql="
				SELECT
						nombre
				FROM
						g_estructura.area are
				WHERE
						id_area='$idArea';";
		$res = $conexion->ejecutarConsulta($sql);
		return $res;
	}	
//----------------------------------------------------------------------------------------------
	public function obtenerFechasResponsables($conexion,$idArea, $identificador){
	
		$res = $conexion->ejecutarConsulta("
				SELECT 
				       fecha_inicio, fecha_fin
  				FROM 
					   g_estructura.responsables
				where
					   id_area = '$idArea' and  
					   identificador='$identificador';");
		return $res;
	}
	
	//-------------------------------------------------------------------------------------
	public function obtenerFechasResponsablesPuestos($conexion,$idArea, $identificador){
	
		$res = $conexion->ejecutarConsulta("
				SELECT
				fecha_inicio, fecha_fin
				FROM
				g_estructura.responsables_puestos
				where
				id_area = '$idArea' and
				identificador='$identificador';");	
		return $res;
	}
	
	//--------------------------------------------------------------------------------------
	
	public function obtenerResponsablesRRHH($conexion, $nombre, $apellido, $identificador,$idRegistro,$estado=NULL){
		
		$identificador = $identificador!="" ? "'" . $identificador . "'" : "null";
		$nombre = $nombre!="" ? "'%" . strtoupper($nombre) . "%'" : "null";
		$apellido = $apellido!="" ? "'" . strtoupper($apellido) . "%'" : "null";
		$idRegistro = $idRegistro!="" ? "'" . $idRegistro . "'" : "null";
		$estado = $estado!="" ? "'" . $estado . "'" : "null";
		
		$res = $conexion->ejecutarConsulta("
										SELECT 
												ec.*,
       											fe.apellido ||' '||fe.nombre as servidor,
												a.nombre as zona
 									 	FROM 
												g_uath.encargo_recursos_humanos ec,
				                                g_uath.ficha_empleado fe,
												g_estructura.area a
										where 
												($nombre is NULL or  fe.nombre = $nombre) and 
												($apellido is NULL or  fe.apellido = $apellido) and 
												($identificador is NULL or fe.identificador = $identificador) and 
												($idRegistro is NULL or ec.id_encargo_recursos_humanos = $idRegistro) and 
												($estado is NULL or ec.estado = $estado) and		 
												fe.identificador= ec.identificador and
												a.id_area = ec.zona_area
										order by 3;
				
											");
		return $res;
	}
	//--------------------------------------------------------------------------------------
	public function actualizarEstadoResponsableRRHH ($conexion, $identificador, $estado){
				$sql="
					UPDATE 
							g_uath.encargo_recursos_humanos
		   			SET 
		   					estado='$estado'
					WHERE 	
							identificador='$identificador';";
				
				$res = $conexion->ejecutarConsulta($sql);
		return $res;
	}
	//-------------------------------------------------------------------------------------------------------------------------------
	public function guardarNuevoResponsableRRHH ($conexion, $identificador,$idarea){
		$sql="
			INSERT INTO 
				g_uath.encargo_recursos_humanos(identificador, zona_area, estado)
    		VALUES ('$identificador','$idarea', 'activo');";
		
		$res = $conexion->ejecutarConsulta($sql);
		return $res;
	}
	//---------------------------------------------------------------------------------------------------------
	public function obtenerFechaNacimiento($conexion,$dia,$mes){
	
		$sql="
		SELECT	
				EXTRACT(year FROM age(fecha_nacimiento)) as nuevaedad,
				fecha_nacimiento as fecha,
				identificador,
				edad
		FROM
				g_uath.ficha_empleado
		WHERE
				EXTRACT(DAY FROM fecha_nacimiento) = $dia and
				EXTRACT(MONTH FROM fecha_nacimiento) = $mes;";
			
		$res = $conexion->ejecutarConsulta($sql);
		return $res;
	
	}
	//--------------------------------------------------------------------------------------------------------
	public function actualizarDatosFichaEmpleado($conexion, $identificador, $edad){
		$sql="UPDATE
					g_uath.ficha_empleado
			  SET
					edad=$edad
			  WHERE identificador='$identificador';";
		$res = $conexion->ejecutarConsulta($sql);
		return $res;
	}
	//--------------------------------------------------------------------------------------------------------
	
	public function devolverPuestosSubrogantes($conexion){
		$sql="SELECT  
      				 distinct nombre_puesto
  			  FROM 
					g_subrogacion.responsables_puestos 
			  order by 1;";
		$res = $conexion->ejecutarConsulta($sql);
		return $res;
		
	}
	//--------------------------------------------------------------------------------------------------------	
	public function obtenerDatosHistorialLaboralIess ($conexion,$identificador,$estado,$id=NULL){
	
		$id = $id!="" ? "'" . $id . "'" : "null";
		$estado = $estado!="" ? "'" . $estado . "'" : "null";
		
		$res = $conexion->ejecutarConsulta("select
				*, cast(fecha_creacion as date) as fecha 
				from
				g_uath.datos_historial_laboral_iess
				where
				identificador='$identificador' and
				(($estado) is NULL or estado in ($estado)) and
				($id is NULL or id_datos_historial_laboral = $id) order by 1;");
		return $res;
	}
	
	public function guardarHistorialLaboralIess ($conexion, $identificador, $rutaArchivo){
		$res = $conexion->ejecutarConsulta("INSERT INTO g_uath.datos_historial_laboral_iess(
				identificador, ruta_historial_laboral)
				VALUES ('$identificador', '$rutaArchivo');");
		return $res;
	}
	public function modificarHistorialLaboralIess ($conexion, $id=NULL, $rutaArchivo, $observacion,$estado,$fecha_creacion,$identificador=NULL){
		
		$id = $id!="" ? "'" . $id . "'" : "null";
		$identificador = $identificador!="" ? "'" . $identificador . "'" : "null";
		
		$parametros='';
		if($rutaArchivo != ''){
			$parametros.=" ruta_historial_laboral='$rutaArchivo',"; 
		}
		if($fecha_creacion != ''){
			$parametros.=" fecha_creacion=now() ,";
		}
		if($observacion != ''){
			$parametros.=" observacion='$observacion',";
		}
		
		$res = $conexion->ejecutarConsulta("
					UPDATE 
						g_uath.datos_historial_laboral_iess
				    SET 
				        $parametros
						estado='$estado' 
				    WHERE 
						($id is NULL or id_datos_historial_laboral = $id) and
				        ($identificador is NULL or identificador = $identificador);");
		return $res;
	}
	public function obtenerHistorialLaboral($conexion,$identificador,$apellido,$nombre){
		$busqueda="";
		if($identificador!=''){
			$busqueda.=" and hl.identificador='".$identificador."'";
		}
		if($apellido!=''){
			$busqueda.=" and UPPER(fe.apellido) like '%".strtoupper($apellido)."%'";
		}
		if($nombre!=''){
			$busqueda.=" and UPPER(fe.nombre) like '%".strtoupper($nombre)."%'";
		}
	
		$res = $conexion->ejecutarConsulta("select
												distinct hl.identificador,
												fe.nombre, fe.apellido,
												f.id_area
											from
												g_uath.datos_historial_laboral_iess hl ,
												g_uath.ficha_empleado fe,
												g_estructura.funcionarios f
											where
												f.identificador = hl.identificador and
												(hl.identificador=fe.identificador) and
												(hl.estado='Ingresado' OR hl.estado='Modificado') ".$busqueda.";");
		return $res;
	}
	
	//-----------------------------------------------declaracion juramentada---------------------------------------------------------
	public function obtenerDatosDeclaracionJuramentada($conexion,$identificador,$estado,$id=NULL){
	
		$id = $id!="" ? "'" . $id . "'" : "null";
		$estado = $estado!="" ? "'" . $estado . "'" : "null";
	
		$res = $conexion->ejecutarConsulta("select
				*, cast(fecha_creacion as date) as fecha
				from
				g_uath.datos_declaracion_juramentada
				where
				identificador='$identificador' and
				(($estado) is NULL or estado in ($estado)) and
				($id is NULL or id_datos_declaracion_juramentada = $id) order by 1;");
		return $res;
	}
	
	public function guardarDeclaracionJuramentada ($conexion, $identificador, $rutaArchivo,$fechaDeclaracion){
		$res = $conexion->ejecutarConsulta("INSERT INTO g_uath.datos_declaracion_juramentada(
				identificador, ruta_declaracion_juramentada,fecha_declaracion)
				VALUES ('$identificador', '$rutaArchivo','$fechaDeclaracion');");
		return $res;
	}
	public function modificarDeclaracionJuramentada ($conexion, $id=NULL, $rutaArchivo, $observacion,$estado,$fecha_creacion,$identificador=NULL,$fechaDeclaracion=NULL){
	
		$id = $id!="" ? "'" . $id . "'" : "null";
		$identificador = $identificador!="" ? "'" . $identificador . "'" : "null";
	
		$parametros='';
		if($rutaArchivo != ''){
			$parametros.=" ruta_declaracion_juramentada='$rutaArchivo',";
		}
		if($fecha_creacion != ''){
		$parametros.=" fecha_creacion=now() ,";
		}
		if($observacion != ''){
		$parametros.=" observacion='$observacion',";
		}
		if($fechaDeclaracion != ''){
			$parametros.=" fecha_declaracion='$fechaDeclaracion',";
		}
	
		$res = $conexion->ejecutarConsulta("
				UPDATE
				g_uath.datos_declaracion_juramentada
				SET
				$parametros
				estado='$estado'
				WHERE
				($id is NULL or id_datos_declaracion_juramentada = $id) and
				($identificador is NULL or identificador = $identificador);");
				return $res;
	}
	public function obtenerDeclaracionJuramentada($conexion,$identificador,$apellido,$nombre){
	$busqueda="";
	if($identificador!=''){
	$busqueda.=" and dj.identificador='".$identificador."'";
	}
	if($apellido!=''){
			$busqueda.=" and UPPER(fe.apellido) like '%".strtoupper($apellido)."%'";
	}
	if($nombre!=''){
			$busqueda.=" and UPPER(fe.nombre) like '%".strtoupper($nombre)."%'";
	}
	
	$res = $conexion->ejecutarConsulta("select
								distinct dj.identificador,
								fe.nombre, fe.apellido,
								f.id_area
							from
								g_uath.datos_declaracion_juramentada dj ,
								g_uath.ficha_empleado fe,
								g_estructura.funcionarios f
							where
								f.identificador = dj.identificador and
								(dj.identificador=fe.identificador) and
								(dj.estado='Ingresado' OR dj.estado='Modificado') ".$busqueda.";");
		return $res;
	}
	
	//--------------------------------obtener datos subrogacion de puestos---------------------------------------------
	public function obtenerSubrogacionesFuncionariosPermisos($conexion, $idArea,$identificador,$estado,$fechaFin){
	
		$busqueda="rp.estado <> 'creado'";
		if($estado != ''){
			$busqueda = "rp.estado IN ('$estado')";
		}
			
		if($identificador != ''){
			$busqueda .= "and rp.identificador_responsable IN ('$identificador')";
		}
	
		if($idArea != ''){
			$busqueda .= "and rp.id_area ='".$idArea."'";
		}
		
		if($fechaFin != ''){
			$busqueda .= " and to_char(rp.fecha_fin,'YYYY-MM-DD') >= to_char(now(),'YYYY-MM-DD')";
		}
		$sql="SELECT
					 *
  			  FROM
					g_subrogacion.responsables_puestos rp 
			  WHERE ".$busqueda." 
				and rp.id_permiso_empleado in ( select id_permiso_empleado from g_vacaciones.permiso_empleado 
				where id_permiso_empleado = rp.id_permiso_empleado and estado in ('Aprobado','InformeGenerado') and minutos_utilizados >= 960 ) 
				and rp.identificador_responsable in (select identificador from g_estructura.responsables where 
				identificador = rp.identificador_responsable and responsable = true and activo=1 ) order by 6 ASC; ";
	
		$res = $conexion->ejecutarConsulta($sql); 
		return $res;
	}
	//----------------------------devolver funcionarios-----------------------------------------------------------------------------------------
	public function reporteFuncionarioXFecha($conexion,$identificador,$apellido,$nombre,$provincia,$modalidad){
	        
	    $identificador = $identificador!="" ? "'" . $identificador . "'" : "null";
	    $apellido = $apellido!="" ? "'%" . $apellido . "%'" : "null";
	    $nombre = $nombre!="" ? "'%" . $nombre . "%'" : "null";
	    $provincia = $provincia!="" ? "'" . $provincia . "'" : "null";
	    $modalidad = $modalidad!="" ? "'" . $modalidad . "'" : "null";
           $res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_uath.reporte_funcionarios_fecha(".$identificador.",".$apellido.",".$nombre.",".$provincia.",".$modalidad.")
											ORDER BY
												provincia, tipo_contrato, apellido, nombre;");
	        
	        return $res;
	}
	//---------------------------devolver fecha inicial---------------------------------------------------------------------------------------
	public function devolverFechaInicial($conexion,$identificador) {

	    $res = $conexion->ejecutarConsulta("SELECT
												descripcion, fecha_inicial
											FROM
												g_uath.devolver_fecha_inicial('".$identificador."')
											;");
	    
	    return $res;
	}
	
	public function obtenerDatosPerfilPublicoPorIdentificador($conexion, $identificador) {
	    
	    $consulta = "SELECT
                    	dc.identificador
                    	, UPPER(fe.apellido || ' ' || fe.nombre) AS nombre
                    	, UPPER(dc.nombre_puesto) AS cargo
                    	, UPPER(dc.provincia) AS provincia
                    	, UPPER(canton) AS canton
                    	, UPPER(dc.direccion) AS direccion
                    	, COALESCE(fe.mail_institucional, fe.mail_personal) AS mail
                        , fe.fotografia
                        , fe.ruta_perfil_publico
                        , fe.ruta_qr_perfil_publico 
                    FROM
                    	g_uath.datos_contrato dc
                    	INNER JOIN g_uath.ficha_empleado fe ON fe.identificador = dc.identificador
                    WHERE
                    	dc.identificador =  '" . $identificador . "'
                    	and dc.estado = 1;";
	    
	    return $res = $conexion->ejecutarConsulta($consulta);
	}
	
}