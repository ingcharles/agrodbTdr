 <?php
	
	require_once 'Constantes.php';
	use JasperPHP\JasperPHP;
	
	$constg = new Constantes();

	class ControladorReportes{
		
		public function __construct($destinoUrl = true){
			if($destinoUrl){
				require_once '../../vendor/cossou/jasperphp/src/JasperPHP/JasperPHP.php';
			}else{
				require_once '../../../vendor/cossou/jasperphp/src/JasperPHP/JasperPHP.php';
			}
		}

		public function generarReporteJasper($reporteJasper, $parametros, $conn, $salidaReporte, $tipoReporte = null){
			$constgi = new Constantes();

			switch ($tipoReporte) {
				case 'logoRecortado':
					$parametros['parametrosReporte'] += array('rutaLogo' => $constgi::RUTA_SERVIDOR_OPT . '/' . $constgi::RUTA_APLICACION . '/aplicaciones/general/img/encabezadoRecortado.jpg');
				break;

				case 'defecto':
					$parametros['parametrosReporte'] += array('rutaLogo' => $constgi::RUTA_SERVIDOR_OPT . '/' . $constgi::RUTA_APLICACION . '/aplicaciones/general/img/encabezadoJPG.jpg');
				break;

				case 'facturacion':
					$parametros['parametrosReporte'] += array('fondoCertificado' => $constgi::RUTA_SERVIDOR_OPT . '/' . $constgi::RUTA_APLICACION . '/aplicaciones/general/img/fondoCertificado.png');
				break;

				case 'cuarentena':
					$parametros['parametrosReporte'] += array('fondoCertificado' => $constgi::RUTA_SERVIDOR_OPT . '/' . $constgi::RUTA_APLICACION . '/aplicaciones/general/img/fondoCertificado.png');
				break;

				case 'sanidadAnimal':
				case 'sanidadVegetal':
					$parametros['parametrosReporte'] += array('fondoCertificado' => $constgi::RUTA_SERVIDOR_OPT . '/' . $constgi::RUTA_APLICACION . '/aplicaciones/general/img/fondoCertificado.png');
				break;

				case 'centroPecuarioExportacion':
					$parametros['parametrosReporte'] += array('fondoCertificado' => $constgi::RUTA_SERVIDOR_OPT . '/' . $constgi::RUTA_APLICACION . '/aplicaciones/general/img/fondoCertificado.png');
				break;

				case 'centroMercanciaPecuaria':
					$parametros['parametrosReporte'] += array('fondoCertificado' => $constgi::RUTA_SERVIDOR_OPT . '/' . $constgi::RUTA_APLICACION . '/aplicaciones/general/img/fondoCertificado.png');
				break;

				case 'centroReproduccionAnimal':
					$parametros['parametrosReporte'] += array('fondoCertificado' => $constgi::RUTA_SERVIDOR_OPT . '/' . $constgi::RUTA_APLICACION . '/aplicaciones/general/img/fondoCertificado.png');
				break;

				case 'logoMovilizacion':											   
					$parametros['parametrosReporte'] += array('logoSeguridadCSM' => $constgi::RUTA_SERVIDOR_OPT . '/' . $constgi::RUTA_APLICACION . '/aplicaciones/general/img/logoSeguridadCSM.png');
					$parametros['parametrosReporte'] += array('fondoCertificado'=> $constgi::RUTA_SERVIDOR_OPT . '/' . $constgi::RUTA_APLICACION . '/aplicaciones/general/img/fondoCertificado.png');
				break;
				
				case 'logoMovilizacionTicket':
					$parametros['parametrosReporte'] += array('logoSeguridadTicket' => $constgi::RUTA_SERVIDOR_OPT . '/' . $constgi::RUTA_APLICACION . '/aplicaciones/general/img/logoSeguridadTicket.gif');
				break;

				case 'accionPersonal':
					$parametros['parametrosReporte'] += array('rutaLogo' => $constgi::RUTA_SERVIDOR_OPT . '/' . $constgi::RUTA_APLICACION . '/aplicaciones/general/img/encabezadoAccionPersonal.jpg');
				break;

				case 'operadorLeche':
					$parametros['parametrosReporte'] += array('fondoCertificado' => $constgi::RUTA_SERVIDOR_OPT . '/' . $constgi::RUTA_APLICACION . '/aplicaciones/general/img/fondoCertificado.png');
				break;
				case 'transporteCarnicos':
					$parametros['parametrosReporte'] += array('fondoCertificado' => $constgi::RUTA_SERVIDOR_OPT . '/' . $constgi::RUTA_APLICACION . '/aplicaciones/general/img/fondoCertificado.png');
				break;

				case 'industriaLactea':
					$parametros['parametrosReporte'] += array('fondoCertificado' => $constgi::RUTA_SERVIDOR_OPT . '/' . $constgi::RUTA_APLICACION . '/aplicaciones/general/img/fondoCertificado.png');
				break;

				case 'laboratorio':
					$parametros['parametrosReporte'] += array(
																'fondoCertificado' => $constgi::RUTA_SERVIDOR_OPT . '/' . $constgi::RUTA_APLICACION . '/aplicaciones/general/img/fondoCertificado.png');
				break;

				case 'faenador':
					$parametros['parametrosReporte'] += array('fondoCertificado' => $constgi::RUTA_SERVIDOR_OPT . '/' . $constgi::RUTA_APLICACION . '/aplicaciones/general/img/fondoCertificadoHorizontal.png');
				break;

				case 'ria':
					$parametros['parametrosReporte'] += array('fondoCertificado' => $constgi::RUTA_SERVIDOR_OPT . '/' . $constgi::RUTA_APLICACION . '/aplicaciones/general/img/fondoCertificado.png');
				break;

				case 'mercanciasSinValorComercialExportacion':
					$parametros['parametrosReporte'] += array('fondoCertificado' => $constgi::RUTA_SERVIDOR_OPT . '/' . $constgi::RUTA_APLICACION . '/aplicaciones/general/img/fondoCertificado.png');
				break;

				case 'organicos':
					$parametros['parametrosReporte'] += array(
                                            					'fondoCertificado' => $constgi::RUTA_SERVIDOR_OPT . '/' . $constgi::RUTA_APLICACION . '/aplicaciones/general/img/fondoCertificado.png',
																'selloOrganico' => $constgi::RUTA_SERVIDOR_OPT . '/' . $constgi::RUTA_APLICACION . '/aplicaciones/general/img/selloOrganico.png'
															);
				break;
				case 'organicosHorizontal':
					$parametros['parametrosReporte'] += array(
					                                            'fondoCertificadoHorizontal' => $constgi::RUTA_SERVIDOR_OPT . '/' . $constgi::RUTA_APLICACION . '/aplicaciones/general/img/fondoCertificadoHorizontal.png',
																'selloOrganico' => $constgi::RUTA_SERVIDOR_OPT . '/' . $constgi::RUTA_APLICACION . '/aplicaciones/general/img/selloOrganico.png'
															);
				break;

				case 'CertificacionBPA':
					$parametros['parametrosReporte'] += array('rutaFondo' => $constgi::RUTA_SERVIDOR_OPT . '/' . $constgi::RUTA_APLICACION . '/aplicaciones/general/img/fondoCertificado.png');
				break;

				case 'CertificadoVeterinarioFertilizantePlaguicida':
					$parametros['parametrosReporte'] += array(
																'rutaFondo' => $constgi::RUTA_SERVIDOR_OPT . '/' . $constgi::RUTA_APLICACION . '/aplicaciones/general/img/fondoCertificado.png'
															);
				break;

				case 'transporteAnimalesVivos':
					$parametros['parametrosReporte'] += array(
																'fondoCertificado' => $constgi::RUTA_SERVIDOR_OPT . '/' . $constgi::RUTA_APLICACION . '/aplicaciones/general/img/fondoCertificado.png',
																'selloSeguridad' => $constgi::RUTA_SERVIDOR_OPT . '/' . $constgi::RUTA_APLICACION . '/aplicaciones/general/img/selloSeguridad.png'
															);
				break;
				
				case 'protocolos':
					$parametros['parametrosReporte'] += array(
					'fondoCertificado' => $constgi::RUTA_SERVIDOR_OPT . '/' . $constgi::RUTA_APLICACION . '/aplicaciones/general/img/fondoCertificado.png'
						);
					break;
				
				case 'organizacionEcuestre':
				    $parametros['parametrosReporte'] += array(
                                    				            'rutaFondo' => $constgi::RUTA_SERVIDOR_OPT . '/' . $constgi::RUTA_APLICACION . '/aplicaciones/general/img/fondoCertificado.png'
                                    				        );
				break;
				
				case 'TransitoInternacional':
				    $parametros['parametrosReporte'] += array('rutaFondo' => $constgi::RUTA_SERVIDOR_OPT . '/' . $constgi::RUTA_APLICACION . '/aplicaciones/general/img/fondoCertificado.png');
				break;

				case 'general':
					$parametros['parametrosReporte'] += array('fondoCertificado' => $constgi::RUTA_SERVIDOR_OPT . '/' . $constgi::RUTA_APLICACION . '/aplicaciones/general/img/fondoCertificado.png');
				break;
				
				case 'perfilPublico':
				    $parametros['parametrosReporte'] += array('logoPerfilPublico' => $constgi::RUTA_SERVIDOR_OPT . '/' . $constgi::RUTA_APLICACION . '/aplicaciones/general/img/logoPerfilPublico.png',
                                                                'logoPiePerfilPublico' => $constgi::RUTA_SERVIDOR_OPT . '/' . $constgi::RUTA_APLICACION . '/aplicaciones/general/img/logoPiePerfilPublico.png'
				                                                );
				break;
			}

			$jasperPhp = new JasperPHP();

			$parametros['parametrosReporte'] += array('REPORT_LOCALE' => 'es_ES');
			$parametros += array('tipoSalidaReporte' => 'pdf');

			$conexionBase = array(
				'driver' => 'postgres',
				'username' => $conn->getUsuario(),
				'host' => $conn->getServidor(),
				'database' => $conn->getBaseDatos(),
				'password' => $conn->getClave(),
				'port' => $conn->getPuerto());
			
			//$rutaSalidaReporte = trim($constgi::RUTA_SERVIDOR_OPT . '/' . $constgi::RUTA_APLICACION . '/' . $salidaReporte, '.pdf');str_replace('.pdf', '', $cadena)
			$rutaSalidaReporte = str_replace('.pdf', '', $constgi::RUTA_SERVIDOR_OPT . '/' . $constgi::RUTA_APLICACION . '/' . $salidaReporte);																													  

			$jasperPhp->process(
				$constgi::RUTA_SERVIDOR_OPT . '/' . $constgi::RUTA_APLICACION . '/' . $reporteJasper, 
				$rutaSalidaReporte, 
				$parametros['tipoSalidaReporte'], 
				$parametros['parametrosReporte'], 
				$conexionBase
			)->execute();

			// Depuración de errores
			/*exec($jasperPhp->output().' 2>&1', $output);
			print_r($output);*/

			return true;
		}
	}
	?>
