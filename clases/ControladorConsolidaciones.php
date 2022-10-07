<?php

class ControladorConsolidaciones{
	
	public function obtenerEmpresasSinacoi($conexion, $inicio, $fin){
	 
		$sql="SELECT 
					* 
			FROM 
					sch_sinacoi.empresa 
			WHERE 
					emp_proceso = 'NO'
					and emp_id between $inicio and $fin
					and emp_razon_anterior is null
			LIMIT 2000;";
			
			echo $sql;
	
		$res = $conexion->ejecutarConsulta($sql);
		
		return $res;
	}
	
	public function actualizarEmpresasSinacoi($conexion, $ruc, $razonSocial, $idEmpresa, $nombreAntiguo){
		
		$razonSocial = str_replace("'", "''", $razonSocial);
		$nombreAntiguo = str_replace("'", "''", $nombreAntiguo);
	
		$sql="UPDATE 
				sch_sinacoi.empresa 
			  SET  
				emp_ruc = '$ruc',
				emp_nombre = '$razonSocial',
				emp_razon_social = '$razonSocial',
				emp_razon_anterior = '$nombreAntiguo',
				emp_proceso = 'SI'
			WHERE 
				emp_id = '$idEmpresa';";
	
		$res = $conexion->ejecutarConsulta($sql);
	
		return $res;
	}
	
	public function actualizarEmpresasSinacoiProcesada($conexion, $idEmpresa, $tipoProceso){
	
		$sql="UPDATE
				sch_sinacoi.empresa
			SET
				emp_proceso = '$tipoProceso'
			WHERE
				emp_id = '$idEmpresa';";
	
		$res = $conexion->ejecutarConsulta($sql);
	
		return $res;
	}
	
	public function obtenerEmpresasSinacoiSoloVacio($conexion, $inicio, $fin){
	
		$sql="SELECT  
					emp_id, emp_ruc, emp_nombre, emp_razon_social 
				FROM 
					sch_sinacoi.empresa 
				WHERE 
					(emp_ruc is null OR emp_ruc = '') AND emp_proceso is null
					and emp_id between $inicio and $fin
				LIMIT 220;";
	
		$res = $conexion->ejecutarConsulta($sql);
	
		return $res;
	}
	
	public function obtenerTramitesPersona($conexion, $empresa){
		
		$sql = "SELECT * FROM sch_sinacoi.tramites_persona WHERE emp_id = '$empresa'";
		
		$res = $conexion->ejecutarConsulta($sql);
		
		return $res;
	}
	
	public function obtenerBoletasPrimeras($conexion, $empresa){
	
		$sql = "SELECT * FROM sch_sinacoi.boletas_primeras WHERE emp_id = '$empresa'";
	
		$res = $conexion->ejecutarConsulta($sql);
	
		return $res;
	}
	
	public function obtenerEmpeladoSinacoi($conexion, $empleado){
	
		$sql = "SELECT * FROM sch_sinacoi.empleado WHERE empl_id = '$empleado'";
	
		$res = $conexion->ejecutarConsulta($sql);
	
		return $res;
	}
	
	public function obtenerEmpeladoSaite($conexion, $empleado){
	
		$sql = "SELECT * FROM contratos.c_empleado WHERE identificacion = '$empleado'";
			
		$res = $conexion->ejecutarConsulta($sql);
	
		return $res;
	}
	
	public function obtenerContratoSaite($conexion, $empleado){
	
		$sql = "SELECT distinct c_institucion_id FROM contratos.c_contrato WHERE c_empleado_id = '$empleado'";
			
		$res = $conexion->ejecutarConsulta($sql);
			
		return $res;
	}
	
	public function obtenerEmpresaSaite($conexion, $empresa){
	
		$sql = "SELECT * FROM contratos.c_institucion WHERE c_institucion_id = '$empresa'";
	
		$res = $conexion->ejecutarConsulta($sql);
	
		return $res;
	}
	
	public function obtenerEmpresaSaiteRazonSocial($conexion, $empresa){
	
		$sql = "SELECT razon_social, identificacion FROM contratos.c_institucion WHERE razon_social ilike '%$empresa%'";
			
		$res = $conexion->ejecutarConsulta($sql);
	
		return $res;
	}
	
	public function similarity($str1, $str2) {
		$len1 = strlen($str1);
		$len2 = strlen($str2);
	
		$max = max($len1, $len2);
		$similarity = $i = $j = 0;
	
		while (($i < $len1) && isset($str2[$j])) {
			if ($str1[$i] == $str2[$j]) {
				$similarity++;
				$i++;
				$j++;
			} elseif ($len1 < $len2) {
				$len1++;
				$j++;
			} elseif ($len1 > $len2) {
				$i++;
				$len1--;
			} else {
				$i++;
				$j++;
			}
		}
	
		return round($similarity / $max, 2);
	}
	
	public function eliminar_tildes($cadena){
	
		//Codificamos la cadena en formato utf8 en caso de que nos de errores
		//$cadena = utf8_encode($cadena);
	
		//Ahora reemplazamos las letras
		$cadena = str_replace(
				array('á', 'à', 'Á', 'À', 'é', 'è', 'É', 'È', 'í', 'ì', 'Í', 'Ì', 'ó', 'ò', 'Ó', 'Ò', 'ú', 'ù', 'Ú', 'Ù', 'ñ', 'Ñ', '&'),
				array('a', 'a', 'A', 'A', 'e', 'e', 'E', 'E', 'i', 'i', 'I', 'I', 'o', 'o', 'O', 'O', 'u', 'u', 'U', 'U', 'n', 'N', ''),
				$cadena
		);
			
		return $cadena;
	}
	
	public function obtenerEmpresasSinacoiSinIgualar($conexion, $inicio, $fin){
	    
	    $sql="SELECT
					emp_id, emp_ruc, emp_nombre, emp_razon_social
				FROM
					sch_sinacoi.empresa
				WHERE
					emp_proceso = 'NO' and emp_ruc is null
					and emp_id between $inicio and $fin
				LIMIT 220;";
	    
	    $res = $conexion->ejecutarConsulta($sql);
	    
	    return $res;
	}
	
	
	//-----------------------------------------------------------------------------------------------------------
	
	public function obtenerEmpresaSaitePorIdentificador($conexion, $identificador){
	    
	    $sql = "SELECT
                    identificacion, razon_social, direccion, telefono1, correo
                FROM
                    contratos.c_institucion i LEFT JOIN contratos.c_direccion d ON i.c_institucion_id = d.c_institucion_id
                WHERE
                    identificacion = '$identificador';";
	    
	    $res = $conexion->ejecutarConsulta($sql);
	    
	    return $res;
	}
	
	public function obtenerRegistrosProcesoIgualar($conexion){
	    
	    $sql = "SELECT
                    identificacion
                FROM
                    contratos.c_institucion i
                WHERE
                    validacion = 'SI' LIMIT 1;";
	    
	    $res = $conexion->ejecutarConsulta($sql);
	    
	    return $res;
	    
	}
	
	public function empresaSGI($conexion, $identificador, $razonSocial, $direccion, $correo, $telefono){
	    
	    $fechaCreacion = $this->fecha_aleatoria();
	    $estadoAuditoria = $this->aleatorio('estado');
	    $reglamento = $this->aleatorio('estado');
	    $comite = $this->aleatorio('estado');
	    $artesano = $this->aleatorio('estado');
	    $empresaPublica = $this->aleatorio('estado');
	    $obligadoContabilidad = $this->aleatorio('estado');
	    $localizacion = $this->aleatorio('localizacion');
	    
	    $sql = "INSERT INTO public.sgi_empresa(aud_fecha_creacion, aud_fecha_modificacion, emp_ruc, emp_razon_social, 
                                                aud_estado, emp_direccion, emp_mail, emp_telefono, emp_reglamento, emp_comite, emp_num_sucursales, 
                                                emp_anio_fiscal, emp_procesada, emp_artesano, emp_empresa_publica, emp_obligado_contabilidad, loc_id)
                VALUES ('$fechaCreacion','$fechaCreacion', '$identificador', '$razonSocial', '$estadoAuditoria', '$direccion', '$correo', '$telefono'
                        , '$reglamento', '$comite', '0', 2016, '0', '$artesano','$empresaPublica','$obligadoContabilidad', '$localizacion');";
	    
	    echo $sql.'</br>';
	    
	   // $res = $conexion->ejecutarConsulta($sql);
	    
	    return $res;
	    
	}
	
	public function empresaPlanificacionSGI($conexion, $identificador, $numero){
	    
        $fechaCreacion = $this->fecha_aleatoria();
        $identificadorPer = $this->aleatorio('identificador');
        $identificadorPerC = $this->aleatorio('identificador');
        $fechaPlanificacion = $this->fecha_aleatoria();
        $fechaPlanificacionInicio =  $this->fecha_aleatoria();
        $localizacion = $this->aleatorio('localizacion');
        
	    $sql = "INSERT INTO public.sgi_planificacion(aud_fecha_creacion, aud_fecha_modificacion, emp_ruc, rie_fecha_ejecucion, 
                                                aud_estado, per_identificacion_d, det_id_d, cat_id_d, per_identificacion_c, det_id_c, 
                                                cat_id_c, det_id, cat_id, pla_fecha_programada_i, pla_fecha_programada_f, 
                                                pla_fecha_aprobada_i, pla_fecha_aprobada_f, pla_fecha_inicio, pla_fecha_fin, 
                                                pla_grupo_horario, loc_id_d, loc_id_c, loc_id_i, cre_id, pla_expediente) 
                VALUES('$fechaCreacion', '$fechaCreacion', '$identificador', '$fechaCreacion', 
                        'false', '$identificadorPer', 4,2,'$identificadorPerC',5,2,5,6,'$fechaPlanificacion','$fechaPlanificacion',
                        '$fechaPlanificacion','$fechaPlanificacion','$fechaPlanificacionInicio','$fechaPlanificacionInicio',0,
                        '$localizacion','$localizacion','$localizacion', $numero, 'MDT-2017-0-$numero') ;";
	    
	    echo $sql.'</br>';
	    	    
	    //$res = $conexion->ejecutarConsulta($sql);
	    
	    return $res;
	    
	}
	
	public function actualizarExpedientePlanificacion($conexion, $numeroPlanificacion){
	    
	    $sql = "UPDATE 
                    public.sgi_planificacion
                SET 
                    plan_expediente = 'MDT-2017-0-'.$numeroPlanificacion
                WHERE 
                    cre_id = $numeroPlanificacion;";
	    
	    $res = $conexion->ejecutarConsulta($sql);
	    
	    return $res;
	    
	}
	
	public function fecha_aleatoria($formato = "Y-m-d H:i:s", $limiteInferior = "2016-01-01", $limiteSuperior = "2018-01-10"){
	    // Convertimos la fecha como cadena a milisegundos
	    $milisegundosLimiteInferior = strtotime($limiteInferior);
	    $milisegundosLimiteSuperior = strtotime($limiteSuperior);
	    
	    // Buscamos un número aleatorio entre esas dos fechas
	    $milisegundosAleatorios = mt_rand($milisegundosLimiteInferior, $milisegundosLimiteSuperior);
	    
	    // Regresamos la fecha con el formato especificado y los milisegundos aleatorios
	    return date($formato, $milisegundosAleatorios);
	}
	
	public function aleatorio($tipo){
	    
	    switch ($tipo){
	        case "identificador":
	            $identificador = array('1307535748','1712614286','0916560279', '1001699857', '1715776942','0802105809','0905819298');
	            shuffle($identificador);
	            $valor = $identificador[0];
	            
	        break;	
	        
	        case "localizacion":
	            
	            $localizacion = array(40, 217, 30, 26, 248, 250, 119, 47, 109, 51, 108, 129, 195, 106, 171, 120, 227, 247, 138, 
	                                   242, 80, 190, 179, 54, 47, 103, 115, 148, 236, 208, 110, 175, 211, 46, 99, 48, 28, 83, 36, 
	                                   94, 174, 204, 151, 77, 125, 140, 162, 30, 119, 153, 200, 95, 73, 40, 56, 122, 182, 53, 62, 
	                                   222, 123, 214, 92, 180, 127, 101, 44, 82, 58, 26, 213, 189, 137, 117, 185, 233, 111, 237, 
	                                   168, 136, 226, 86, 218, 196, 91, 70, 45, 238, 27, 225, 199, 230, 155, 60, 149, 93, 105, 97, 
	                                   152, 75, 205, 124, 197, 229, 187, 245, 114, 181, 43, 191, 220, 156, 166, 249, 194, 126, 228, 
	                                   39, 132, 112, 131, 201, 61, 96, 67, 178, 87, 169, 241, 66, 142, 224, 147, 221, 89, 160, 121, 50, 
	                                   33, 210, 161, 108, 157, 154, 57, 109, 51, 215, 239, 65, 35, 31, 52, 76, 69, 37, 173, 85, 34, 
	                                   81, 219, 32, 188, 79, 42, 164, 90, 134, 59, 139, 146, 78, 143, 192, 98, 193, 133, 150, 100, 
	                                   243, 167, 250, 158, 113, 116, 172, 63, 183, 184, 170, 55, 235, 68, 248, 84, 118, 145, 244, 88, 
	                                   130, 38, 128, 240, 223, 207, 198, 104, 74, 231, 217, 163, 102, 212, 71, 246, 159, 72, 41, 216, 
	                                   186, 234, 177, 202, 141, 144);
	            
	            shuffle($localizacion);
	            $valor = $localizacion[0];
	        break;
	        
	        case "estado":
	            $estado = array('true','false');
	            shuffle($estado);
	            $valor = $estado[0];
	        break;
	        
	    }
	    
	    return $valor;	   
	    
	}
	

}
?>
