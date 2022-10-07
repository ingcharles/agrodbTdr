<?php

require_once 'Conexion.php';

class ControladorWebServices{

	private $conexionVUE;
	private $conexion;

	public function __construct($tipo){
		switch ($tipo){
			case 'GUIA':
				$this->conexion = new Conexion();
			break;
			case 'VUE':
				//$this->conexionVUE = new Conexion('192.168.200.8','5432','Solicitudes_Dev','vue_gateway','vue_gateway');
				$this->conexionVUE = new Conexion('192.168.200.9','5432','Solicitudes_Dev','postgres','postgres');
				//$this->conexionVUE = new Conexion('192.168.1.175','5432','Solicitudes_Dev','postgres','postgres');
			break;
		}
		

	}
	
	public function buscarProductosSensiblesCultivares(){

		$importacion = array();
		$datosImportacionCabecera = array();
		$datosImportacionDetalle = array();
		$documentoImportacion = array();
		$datosImportacionCabeceraR = array();
		
		$importacion = $this->consultaPermisosImportacionCabeceraSensiblesCultivares();

					
		if(count($importacion)!= 0){


			foreach ($importacion as $cabeceraImportacion){
					
				$datosImportacionCabecera = array(generales =>array(req_city_nm => $cabeceraImportacion['req_city_nm'], afr_prst_cd => $cabeceraImportacion['afr_prst_cd']),
						cabecera=>array(req_no => $cabeceraImportacion['req_no'], req_type_cd=>$cabeceraImportacion['req_type_cd'], req_de => $cabeceraImportacion['req_de'], impr_nm => $cabeceraImportacion['impr_nm'] , impr_idt_no => $cabeceraImportacion['impr_idt_no']
								, expr_nm => $cabeceraImportacion['expr_nm'], cutom_rgm_cd => $cabeceraImportacion['cutom_rgm_cd'], org_ntn_nm => $cabeceraImportacion['org_ntn_nm']
								, org_ntn_cd => $cabeceraImportacion['org_ntn_cd'], spm_ntn_nm => $cabeceraImportacion['spm_ntn_nm'], spm_ntn_cd => $cabeceraImportacion['spm_ntn_cd']
								, trsp_via_nm => $cabeceraImportacion['trsp_via_nm'], trsp_way_cd => $cabeceraImportacion['trsp_way_cd'], spm_port_nm => $cabeceraImportacion['spm_port_nm']
								, ptet_nm => $cabeceraImportacion['ptet_nm'], prdt_type_cd => $cabeceraImportacion['prdt_type_cd'], dclr_em => $cabeceraImportacion['dclr_em'], impr_em => $cabeceraImportacion['impr_em']), detalleProducto => array(),
						documentosAdjuntos => array());
				
				$datosProductoImportacion = $this->consultaPermisosImportacionProductosSensiblesCultivares($cabeceraImportacion['req_no']);
				
				$datosImportacionDetalle = array();

				foreach ($datosProductoImportacion as $producto){
					$datosImportacionDetalle[] = array(hc => $producto['hc'], prdt_cd => $producto['prdt_cd'], prdt_nm => $producto['prdt_nm'], prdt_qt => $producto['prdt_qt']
							, prdt_mes => $producto['prdt_mes'], prdt_nwt => $producto['prdt_nwt'], prdt_nwt_ut => $producto['prdt_nwt_ut']
							, fobv_val => $producto['fobv_val'], cif_val => $producto['cif_val'], prdt_rgs_no=>$producto['prdt_rgs_no']);
				
					$datosImportacionCabecera = array_replace($datosImportacionCabecera, array('detalleProducto'=>$datosImportacionDetalle));
				
				}
				
				$datosDocumentoImportacion = $this->consultaPermisosImportacionDocumentosSensiblesCultivares($cabeceraImportacion['req_no']);
				
				$documentoImportacion = array();
				
				foreach ($datosDocumentoImportacion as $documento){
					
					$documentoImportacion[]  = array(nombreArchivo => $documento['nombre'], rutaArchivo => $documento['ruta']);
					
					$datosImportacionCabecera = array_replace($datosImportacionCabecera, array('documentosAdjuntos'=>$documentoImportacion));
					
				}
				
				$datosImportacionCabeceraR[] = array(importacion =>$datosImportacionCabecera);
					
			}			

		}
		
		return json_encode($datosImportacionCabeceraR);

	}
	
	
	public function consultaPermisosImportacionCabeceraSensiblesCultivares(){
		
		$importacionCabecera = array();
		
		$datos = $this->conexionVUE->ejecutarConsulta("SELECT
															coalesce(req_city_nm, 'ND') req_city_nm,
															coalesce(afr_prst_cd, 'ND') afr_prst_cd,
															coalesce(imc.req_no, 'ND') req_no,
															coalesce(date(req_de), date(current_date)) req_de,
															coalesce(impr_nm, 'ND') impr_nm,
															coalesce(impr_idt_no,'ND') impr_idt_no,
															coalesce(expr_nm,'ND') expr_nm,
															coalesce(cutom_rgm_cd, 'ND') cutom_rgm_cd,
															coalesce(org_ntn_nm, 'ND') org_ntn_nm,
															coalesce(org_ntn_cd, 'ND') org_ntn_cd,
															coalesce(spm_ntn_nm, 'ND') spm_ntn_nm,
															coalesce(spm_ntn_cd , 'ND') spm_ntn_cd,
															coalesce(trsp_via_nm, 'ND') trsp_via_nm,
															coalesce(trsp_way_cd, 'ND') trsp_way_cd,
															coalesce(spm_port_nm, 'ND') spm_port_nm,
															coalesce(ptet_nm,'ND') ptet_nm,
															coalesce(prdt_type_cd, 'ND') prdt_type_cd,
															coalesce(req_type_cd, 'ND') req_type_cd,
															coalesce(dclr_em, 'ND') dclr_em,
															coalesce(impr_em, 'ND') impr_em 
														FROM
															vue_gateway.tn_agr_002 imc,
															vue_gateway.tn_eld_edoc_last_stat ls
														WHERE
															imc.req_no = ls.req_no and
															imc.req_type_cd in ('0001','0002')
															and ((afr_prst_cd not in ('110','610','620','410','320','120','310','130','420') and ntfc_cfm_cd in ('11')) or (afr_prst_cd='210' and ntfc_cfm_cd='22'))");
		
		while ($fila = pg_fetch_assoc($datos)){
			$importacionCabecera[] = array(
					req_city_nm=>$fila['req_city_nm'],
					afr_prst_cd=>$fila['afr_prst_cd'],
					req_no=>$fila['req_no'],
					req_de=>$fila['req_de'],
					impr_nm=>$fila['impr_nm'],
					impr_idt_no=>$fila['impr_idt_no'],
					expr_nm=>$fila['expr_nm'],
					cutom_rgm_cd=>$fila['cutom_rgm_cd'],
					org_ntn_nm=>$fila['org_ntn_nm'],
					org_ntn_cd=>$fila['org_ntn_cd'],
					spm_ntn_nm=>$fila['spm_ntn_nm'],
					spm_ntn_cd=>$fila['spm_ntn_cd'],
					trsp_via_nm=>$fila['trsp_via_nm'],
					trsp_way_cd=>$fila['trsp_way_cd'],
					spm_port_nm=>$fila['spm_port_nm'],
					ptet_nm=>$fila['ptet_nm'],
					prdt_type_cd=>$fila['prdt_type_cd'],
					req_type_cd=>$fila['req_type_cd'],
					dclr_em => $fila['dclr_em'],
					impr_em => $fila['impr_em']
			);
		}
		
		return $importacionCabecera;	
		
	}
	
	public function consultaPermisosImportacionProductosSensiblesCultivares($idVue){
		
		$importacionProducto = array();
		
		$datos = $this->conexionVUE->ejecutarConsulta("SELECT
															coalesce(hc, 'ND') hc,
															coalesce(prdt_cd, 'ND') prdt_cd,
															coalesce(prdt_nm, 'ND') prdt_nm,
															coalesce(prdt_qt, 0) prdt_qt,
															coalesce(prdt_mes, 'ND') prdt_mes,
															coalesce(prdt_nwt, 0)prdt_nwt,
															coalesce(prdt_nwt_ut, 'ND') prdt_nwt_ut,
															coalesce(fobv_val, 0) fobv_val,
															coalesce(cif_val, 0) cif_val,
															CASE WHEN prdt_rgs_no is null THEN 'ND' WHEN prdt_rgs_no = '' THEN 'ND' ELSE prdt_rgs_no END prdt_rgs_no
														FROM
															vue_gateway.tn_agr_002_pd
														WHERE 
															req_no = '$idVue'");
		
		while ($fila = pg_fetch_assoc($datos)){
			$importacionProducto[] = array(
					hc=>$fila['hc'],
					prdt_cd=>$fila['prdt_cd'],
					prdt_nm=>$fila['prdt_nm'],
					prdt_qt=>$fila['prdt_qt'],
					prdt_mes=>$fila['prdt_mes'],
					prdt_nwt=>$fila['prdt_nwt'],
					prdt_nwt_ut=>$fila['prdt_nwt_ut'],
					fobv_val=>$fila['fobv_val'],
					cif_val=>$fila['cif_val'],
					prdt_rgs_no =>$fila['prdt_rgs_no']
			);
		}
		
		return $importacionProducto;
		
		
	}
	
	public function consultaPermisosImportacionDocumentosSensiblesCultivares($idVue){
		
		$documentoImportacion = array();
		
		$documentosAdjuntos = $this->conexionVUE->ejecutarConsulta("SELECT
																		subquery.FL_ID,
																		subquery.FL_NM,
																		subquery. ATCH_DCM_CTG_NM as nombre,
																		subquery.PRCS_SN,
																		subquery.RGS_DT,
																		subquery.RCSD_EDOC_AFR_CD,
																		FL.FL_PATH as ruta
																	FROM
																		(SELECT A.FL_ID, A.FL_NM , A. ATCH_DCM_CTG_NM, A.PRCS_SN, A.RGS_DT, B.RCSD_EDOC_AFR_CD
																				FROM vue_gateway.TA_IPT_EDOC_PRCS_INF B
																				INNER JOIN vue_gateway.TA_IPT_DOCB_FL_INF A
																				ON A.PRCS_SN = B.PRCS_SN
																				AND A.ORGZ_CD = B.ORGZ_CD AND A.FL_TYPE_CD = '003' AND B.RCSD_EDOC_AFR_ID = '$idVue'
																				AND A.PRCS_SN IN ( SELECT MAX(PRCS_SN) FROM vue_gateway.TA_IPT_EDOC_PRCS_INF
																						WHERE RCSD_EDOC_AFR_ID = '$idVue' )) subquery
																		INNER JOIN
																		vue_gateway.ta_cmm_fl FL
																		ON FL.FL_ID = subquery.FL_ID;");
		
		while($documento = pg_fetch_assoc($documentosAdjuntos)){
			
			//$ruta= explode('DEV/', $documento['ruta']);
			$ruta= explode('PROD/', $documento['ruta']);
		
			$documentoImportacion[] = array(nombre => $documento['nombre'], 
											ruta => 'ftp://192.168.1.7/'.$ruta[1]);
				
		}
		
		return $documentoImportacion;		
		
	}
	
	public function buscarPartidasArancelariasRoce($identificadorOperador, $estado = 'registrado'){
		
		$partidas = array();
				
		$res = $this->conexion->ejecutarConsulta("SELECT 
														distinct subpartida_producto_vue
													FROM 
														g_operadores.operaciones
													WHERE
														identificador_operador = '$identificadorOperador' 
														and id_vue != ''
														and estado = '$estado'
														and id_tipo_operacion in (28,30,32,38)
													ORDER BY 
														1;");
						
		while ($fila = pg_fetch_assoc($res)){
			$partidas[] = array(
					partidas_vue=>$fila['subpartida_producto_vue']
			);
		}
		
		//$partidas = array(partidas =>array($partidas));
				
		return json_encode($partidas);
		
	}
	
}

?>