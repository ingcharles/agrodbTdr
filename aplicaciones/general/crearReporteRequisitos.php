<?php
require_once '../../clases/Conexion.php';
require_once '../../clases/Constantes.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorRequisitos.php';
require_once '../../clases/ControladorImportaciones.php';
require_once '../../clases/ControladorFitosanitario.php';
require_once '../../clases/ControladorZoosanitarioExportacion.php';
require_once '../../clases/ControladorTransitoInternacional.php';
require_once 'fpdf.php';

class PDF extends FPDF
{
	
	public function Header(){
		$img_file = "http://guia.agrocalidad.gob.ec/agrodb/aplicaciones/general/img/fondoCertificado.png";
		$this->Image($img_file, 0, 0, 210, 297, 'PNG', '', '', false, 300, '', false, false, 0);
		$this->Ln(20);
		$this->SetAutoPageBreak(true,55); 
	}
	
	function Body($idSolicitud, $tipo){
		$conexion = new Conexion();
		$cr = new ControladorRequisitos();
		$cc = new ControladorCatalogos();
		$ro = new ControladorRegistroOperador();
		
		$this->SetY(25);
		$this->Cell(50);
		$this->SetFont('Times','B',10);
		
		if($tipo == 'Importación'){
			$ci = new ControladorImportaciones();
			$qRegistro = $ci->abrirImportacionReporte($conexion, $idSolicitud);
			$tipoRequisito = 'Importación';
			$ruta = 'origen';
			
			if ($qRegistro[0]['idArea'] == 'SA'){
				$this->Cell(0,30,utf8_decode('PERMISO ZOOSANITARIO PARA LA IMPORTACIÓN'),0,0);
				$this->Ln(5);
				$this->Cell(60);
				$this->Cell(10,30,utf8_decode('IMPORTATION ZOOSANITARY PERMIT'),0,0);
			}else if ($qRegistro[0]['idArea'] == 'SV'){
				$this->Cell(0,30,utf8_decode('PERMISO FITOSANITARIO PARA LA IMPORTACIÓN'),0,0);
				$this->Ln(5);
				$this->Cell(60);
				$this->Cell(10,30,utf8_decode('IMPORTATION PHITOSANITARY PERMIT'),0,0);
			}else if ($qRegistro[0]['idArea'] == 'IAP'){
				$this->Cell(0,30,utf8_decode('AUTORIZACIÓN PARA IMPORTACIÓN DE PLAGUICIDAS Y AFINES DE USO AGRÍCOLA'),0,0);
				$this->Ln(5);
				$this->Cell(60);
				$this->Cell(10,30,utf8_decode('IMPORTATION AUTHORIZATION OF  PESTICIDES AND ALLIED AGRICULTURAL USE'),0,0);
			}else if ($qRegistro[0]['idArea'] == 'IAV'){
				$this->Cell(0,30,utf8_decode('AUTORIZACIÓN SANITARIA DE PRODUCTOS VETERINARIOS'),0,0);
				$this->Ln(5);
				$this->Cell(60);
				$this->Cell(10,30,utf8_decode('SANITARY AUTHORIZATION OF VETERINARY PRODUCTS'),0,0);
			}else if ($qRegistro[0]['idArea'] == 'IAF'){
				$this->Cell(0,30,utf8_decode('PERMISO PARA LA IMPORTACIÓN DE PRODUCTOS FERTILIZANTES'),0,0);
				$this->Ln(5);
				$this->Cell(60);
				$this->Cell(10,30,utf8_decode('FERTILIZER PRODUCTS IMPORTATION PERMIT'),0,0);
			}else if ($qRegistro[0]['idArea'] == 'IAPA'){
				$this->Cell(0,30,utf8_decode('PERMISO PARA LA IMPORTACIÓN DE PLANTAS DE AUTOCONSUMO'),0,0);
				$this->Ln(5);
				$this->Cell(60);
				$this->Cell(10,30,utf8_decode('PERMIT FOR THE IMPORT OF SELF-CONSUMPTION PLANTS'),0,0);
			}
			
		}
		
		if($tipo == 'Fitosanitario'){
			$cf = new ControladorFitosanitario();
			$qRegistro = $cf->abrirFitoExportacionReporte($conexion, $idSolicitud);
			$tipoRequisito = 'Exportación';
			$ruta = 'destino';
			
			$this->Cell(0,30,utf8_decode('PERMISO FITOSANITARIO PARA LA IMPORTACIÓN'),0,0);
			$this->Ln(5);
			$this->Cell(60);
			$this->Cell(0,10,utf8_decode('IMPORTATION PHITOSANITARY PERMIT'),0,0);
		}
		
		if($tipo == 'Zoosanitario'){
			$cz = new ControladorZoosanitarioExportacion();
			$qRegistro = $cz->abrirZooExportacionReporte($conexion, $idSolicitud);
			$tipoRequisito = 'Exportación';
			$ruta = 'destino';
				
			$this->Cell(0,30,utf8_decode('CERTIFICADO ZOOSANITARIO DE EXPORTACIÓN'),0,0);
			$this->Ln(5);
			$this->Cell(60);
			$this->Cell(0,30,utf8_decode('ZOOSANITARY CERTIFICATE FOR EXPORT'),0,0);
		}
		
		if($tipo == 'TransitoInternacional'){
		    $cti = new ControladorTransitoInternacional();
		    $qRegistro = $cti->abrirTransitoInternacionalReporte($conexion, $idSolicitud);
		    $tipoRequisito = 'Tránsito';
		    $ruta = 'origen';
		    
		    if ($qRegistro[0]['idArea'] == 'SA'){
		        $this->Cell(0,30,utf8_decode('AUTORIZACIÓN ZOOSANITARIA DE TRÁNSITO INTERNACIONAL'),0,0);
		        $this->Ln(5);
		    }else if ($qRegistro[0]['idArea'] == 'SV'){
		        $this->Cell(0,30,utf8_decode('AUTORIZACIÓN FITOSANITARIA DE TRÁNSITO INTERNACIONAL'),0,0);
		        $this->Ln(5);
		    }
		    
		}
		
		$this->Ln(5);
		
		$this->Cell(140);
		
		$this->Cell(0,50,utf8_decode('N°: '.$qRegistro[0]['idVue']),0,0);
		// Salto de línea
		$this->Ln(5);
		
		if($tipo == 'Importación'){//Quitar esta validación si se agregará la fecha de vigencia a todos los certificados
		
		$this->Cell(0,50,utf8_decode('Fecha de inicio de vigencia: '.$qRegistro[0]['fechaInicio']),0,0);
		// Salto de línea
		$this->Ln(5);
		
		$this->Cell(0,50,utf8_decode('Fecha de fin de vigencia: '.$qRegistro[0]['fechaVigencia']),0,0);
		// Salto de línea
		$this->Ln(5);
		}
		
		if($tipo == 'TransitoInternacional'){
		    
		    $this->Cell(0,50,utf8_decode('Fecha de inicio de vigencia: '.date('Y-m-d', strtotime($qRegistro[0]['fechaInicio']))),0,0);
		    // Salto de línea
		    $this->Ln(5);
		    
		    $this->Cell(0,50,utf8_decode('Fecha de fin de vigencia: '.date('Y-m-d', strtotime($qRegistro[0]['fechaVigencia']))),0,0);
		    // Salto de línea
		    $this->Ln(5);
		    
		    $this->Cell(0,50,utf8_decode('Observaciones: '.$qRegistro[0]['observacionesTecnico']),0,0);
		    // Salto de línea
		    $this->Ln(5);
		}

		// Salto de línea
		$this->Ln(30);
		
		if($tipo == 'Fitosanitario'){
			$header = array(utf8_decode('Exportador'), utf8_decode('Razón Social'), 'Nombre del Producto', utf8_decode('Bultos'));
			
			$ancho = array(25, 65, 60, 40);
			$this->SetWidths($ancho);
			
			// Cabeceras
			$this->Row($header);
			
		    // Datos
		    $qProveedores = $cf->listarReporteProveedoresProductos($conexion, $idSolicitud);
		    
		    $totalBultos = 0;
		    
		    foreach($qProveedores as $proveedores){
		    	$totalBultos += $proveedores['numeroBultos'];
		    	
		    	$this->Row(array($proveedores['identificadorOperador'],utf8_decode($proveedores['razonSocial']),utf8_decode($proveedores['nombreProducto']),number_format($proveedores['numeroBultos']) . ' ' .$proveedores['unidadBultos']));
		    }
		    
		    $this->Row(array('','','Total',$totalBultos));
		    
		}else if ($tipo == 'Importación'){
			$seccion = true;
			$tipoProcesados = false;
			
			foreach ($qRegistro as $registro){
				
				if ($seccion){
					$this->SetFillColor(200,220,255);
					$this->SetFont('Times','I',12);
					
					$this->Cell(0,6,utf8_decode('DATOS DEL IMPORTADOR'),0,1,'L',true);
					
					$qOperador = $ro->buscarOperador($conexion, $registro['identificador']);
					$operador = pg_fetch_assoc($qOperador);
					
					$this->SetFont('Times','',10);
					$this->MultiCell(0,5,utf8_decode('Número de identificación del importador: '.$registro['identificador']),0,1);
					$this->MultiCell(0,5,utf8_decode('Nombre del importador: '.$operador['razon_social']),0,1);
					$this->MultiCell(0,5,utf8_decode('Dirección del importador: '.$operador['direccion']),0,1);
					
					$this->Ln(4);
					
					$this->SetFillColor(200,220,255);
					$this->SetFont('Times','I',12);
						
					$this->Cell(0,6,utf8_decode('DATOS DEL EXPORTADOR'),0,1,'L',true);
						
					$this->SetFont('Times','',10);
					$this->MultiCell(0,5,utf8_decode('Nombre del exportador: '.$registro['nombreExportador']),0,1);
					$this->MultiCell(0,5,utf8_decode('Dirección del exportador: '.$registro['direccionExportador']),0,1);
						
					$this->Ln(4);
					
					$this->SetFillColor(200,220,255);
					$this->SetFont('Times','I',12);
					
					$this->Cell(0,6,utf8_decode('DATOS DEL PRODUCTO'),0,1,'L',true);
					
					$res = $cc ->obtenerTipoSubtipoXProductos($conexion, $registro['idProducto']);
					$tipoSubtipo = pg_fetch_assoc($res);
					
					if($tipoSubtipo['id_tipo_producto'] == 10){//10 Productos procesados y secos produccion - 10 pruebas
						$tipoProcesados = true;
					}
					
					$this->SetFont('Times','',10);
					$this->MultiCell(0,5,utf8_decode('País de origen: '.$registro['pais']),0,1);
					$this->MultiCell(0,5,utf8_decode('Medio de transporte: '.$registro['tipoTransporte']),0,1);
					$this->MultiCell(0,5,utf8_decode('Puerto de embarque: '.$registro['puertoEmbarque']),0,1);
					$this->MultiCell(0,5,utf8_decode('Puerto de entrada: '.$registro['puertoDestino']),0,1);
					$this->MultiCell(0,5,utf8_decode('Tipo de producto: '.$tipoSubtipo['nombre_tipo']),0,1);
					$this->MultiCell(0,5,utf8_decode('Subtipo de producto: '.$tipoSubtipo['nombre_subtipo']),0,1);
					
					$this->SetFillColor(200,220,255);
					$this->SetFont('Times','I',12);
					$this->Ln(4);
					
					$this->Cell(0,6,utf8_decode('LISTA DE PRODUCTOS'),0,1,'L',true);
					
					$seccion = false;
				}
				
				$this->Ln(4);
				
				$this->SetFont('Times','',10);
				//Producto
				$this->Cell(0,6,utf8_decode('Nombre del Producto: '.$registro['nombreProducto']),0,1,'L',true);
				
				//Nombre cientifico Producto solo para fitosanitario
				if($registro['idArea'] == 'SV'){
					$this->Cell(0,6,utf8_decode('Nombre científico del Producto: '.$registro['nombreCientifico']),0,1,'L',true);
				}
				
				// Peso neto
				$this->Cell(0,6,utf8_decode('Peso neto: '.$registro['peso'].' KG'),0,1,'L',true);
				// Salto de línea

				// Unidad
				$this->Cell(0,6,utf8_decode('Cantidad de producto: '.$registro['unidad']. ' ' . $registro['unidadMedida']),0,1,'L',true);
				// Salto de línea
				//$this->Ln(4);
					
				//$this->SetFillColor(200,220,255);
				//$this->SetFont('Times','I',12);
					
				//$this->Cell(0,6,utf8_decode('LISTA DE REQUISITOS'),0,1,'L',true);
				
				$qRequisitos = $cr->listarRequisitosProducto($conexion, $registro['idPais'], $registro['idProducto'], $tipoRequisito);
				$this->SetFont('Times','',10);
			
				for ($i=0; $i<count($qRequisitos);$i++){
					$j=$i+1;
					$this->MultiCell(0,5,utf8_decode('R'.$j.'- '.$qRequisitos[$i]['detalleImpreso']),0,1);
					$this->Ln(1);
				}
					
				$j++;
				
				if($qRegistro[0]['idPaisEmbarque'] != $qRegistro[0]['idPais']){
					if($qRegistro[0]['idArea'] == 'SV' && $tipoProcesados == false){
						$res = $cr ->abrirRequisitoXCodigo($conexion, 'RFI-431');
						$requisitoEspecial = pg_fetch_assoc($res);
						
						//Requisito especial pais origen - embarque
						$this->SetFont('Times','',10);
						$this->MultiCell(0,5,utf8_decode('R'.$j.'- '.$requisitoEspecial['detalle_impreso']),0,1);
						$this->Ln(1);
					}
				}
				//$this->Cell(50);
				$this->Ln(5);
			
			}
			
			//Muestra observaciones de inspección
			//if ($registro['idArea'] == 'SA' || $registro['idArea'] == 'SV'){
			$qObservaciones = $ci->abrirRevisionDocumentalImportacionReporte($conexion, $idSolicitud);
				
			
			//$this->Cell(0,50,utf8_decode('Fecha de inicio de vigencia: '.$qRegistro[0]['fechaInicio']),0,0);
				
			$this->SetFont('Times','B',10);
				
			$this->Cell(0,6,utf8_decode('OBSERVACIÓN'),0,1);
				
			//for ($i=0; $i<count($qObservaciones);$i++){
			$this->SetFont('Times','',10);
			$this->MultiCell(0,5,utf8_decode(/*$qObservaciones[$i]['fechaInspeccionDocumental'] .' - '. */($qObservaciones[0]['observacionInspeccionDocumental']==''?'Sin observación':$qObservaciones[0]['observacionInspeccionDocumental'])),0,1);
			//}
			
			//}
			
			
			
		}else if ($tipo == 'TransitoInternacional'){
		    $seccion = true;
		    $tipoProcesados = false;
		    
		    foreach ($qRegistro as $registro){
		        
		        if ($seccion){
		            $this->SetFillColor(200,220,255);
		            $this->SetFont('Times','I',12);
		            
		            $this->Cell(0,6,utf8_decode('1. INFORMACIÓN DEL IMPORTADOR'),0,1,'L',true);
		            
		            
		            $this->SetFont('Times','',10);
		            $this->MultiCell(0,5,utf8_decode('Nombre o Razón Social: '.$registro['razonSocialImportador']),0,1);
		            $this->MultiCell(0,5,utf8_decode('Identificación (RUC/CI): '.$registro['identificadorImportador']),0,1);
		            $this->MultiCell(0,5,utf8_decode('Nombre de Representante legal: '.$registro['representanteLegalImportador']),0,1);
		            $this->MultiCell(0,5,utf8_decode('Dirección domiciliaria: '.$registro['direccionImportador']),0,1);
		            $this->MultiCell(0,5,utf8_decode('Teléfono: '.$registro['telefonoImportador']),0,1);
		            $this->MultiCell(0,5,utf8_decode('Email: '.$registro['emailImportador']),0,1);
		            
		            $this->Ln(4);
		            
		            $this->SetFillColor(200,220,255);
		            $this->SetFont('Times','I',12);
		            
		            $this->Cell(0,6,utf8_decode('2. INFORMACIÓN DE AGENTE ADUANERO / SOLICITANTE'),0,1,'L',true);
		            
		            $this->SetFont('Times','',10);
		            $this->MultiCell(0,5,utf8_decode('Nombre o Razón Social: '.$registro['razonSocialSolicitante']),0,1);
		            $this->MultiCell(0,5,utf8_decode('Identificación (RUC/CI): '.$registro['identificadorSolicitante']),0,1);
		            $this->MultiCell(0,5,utf8_decode('Nombre de Representante legal: '.$registro['representanteLegalSolicitante']),0,1);
		            $this->MultiCell(0,5,utf8_decode('Dirección domiciliaria: '.$registro['direccionSolicitante']),0,1);
		            $this->MultiCell(0,5,utf8_decode('Teléfono: '.$registro['telefonoSolicitante']),0,1);
		            $this->MultiCell(0,5,utf8_decode('Email: '.$registro['emailSolicitante']),0,1);
		            
		            $this->Ln(4);
		            
		            $this->SetFillColor(200,220,255);
		            $this->SetFont('Times','I',12);
		            
		            $this->Cell(0,6,utf8_decode('3. INFORMACIÓN DEL TRÁNSITO INTERNACIONAL'),0,1,'L',true);
		            
		            $this->SetFont('Times','',10);
		            $this->MultiCell(0,5,utf8_decode('País Origen: '.$registro['paisOrigen']),0,1);
		            $this->MultiCell(0,5,utf8_decode('País Procedencia: '.$registro['paisProcedencia']),0,1);
		            $this->MultiCell(0,5,utf8_decode('País Destino: '.$registro['paisDestino']),0,1);
		            $this->MultiCell(0,5,utf8_decode('Punto de Ingreso: '.$registro['puntoIngreso']),0,1);
		            $this->MultiCell(0,5,utf8_decode('Punto de Salida: '.$registro['puntoSalida']),0,1);
		            $this->MultiCell(0,5,utf8_decode('Lugar de ubicación de envío: '.$registro['ubicacionEnvio']),0,1);
		            $this->MultiCell(0,5,utf8_decode('Ruta a seguir: '.$registro['rutaSeguir']),0,1);
		            $this->MultiCell(0,5,utf8_decode('Placas del vehículo: '.$registro['placasVehiculo']),0,1);
		            
		            $this->Ln(4);
		            
		            
		            $this->SetFillColor(200,220,255);
		            $this->SetFont('Times','I',12);
		            
		            $this->Cell(0,6,utf8_decode('4. DATOS DE LAS PLANTAS, PRODUCTOS VEGETALES Y ARTÍCULOS REGLAMENTADOS'),0,1,'L',true);
		            
		            		            
		            $seccion = false;
		        }
		        	        
		        $this->Ln(4);
		        
		        $this->SetFont('Times','',10);
		        //Producto
		        $this->Cell(0,6,utf8_decode('Partida Arancelaria: '.$registro['subpartida_arancelaria']),0,1,'L',true);
		      
		        //Producto
		        $this->Cell(0,6,utf8_decode('Descripción del Producto: '.$registro['nombre_tipo_producto'] . ' / ' . $registro['nombre_subtipo_producto'] . ' / ' . $registro['nombre_producto']),0,1,'L',true);
		        
		        //Cantidad
		        $this->Cell(0,6,utf8_decode('Cantidad: '.$registro['cantidad_producto']. ' ' . $registro['nombre_unidad_cantidad']),0,1,'L',true);
		        // Salto de línea
		        
		        // Peso neto
		        $this->Cell(0,6,utf8_decode('Peso: '.$registro['peso_kilos']. ' ' . $registro['nombre_unidad_peso']),0,1,'L',true);
		        // Salto de línea
		        
		        
		        		        
		        $qRequisitos = $cr->listarRequisitosProducto($conexion, $registro['id_pais_origen'], $registro['id_producto'], $tipoRequisito);
		        $this->SetFont('Times','',10);
		        
		        for ($i=0; $i<count($qRequisitos);$i++){
		            $j=$i+1;
		            $this->MultiCell(0,5,utf8_decode('R'.$j.'- '.$qRequisitos[$i]['detalleImpreso']),0,1);
		            $this->Ln(1);
		        }
		        
		        $j++;
		        
		        
		        $this->Ln(5);
		        
		    }
		    
		    
		    
		    
		    
		    }else{
			foreach ($qRegistro as $registro){
				$this->SetFillColor(200,220,255);
				$this->SetFont('Times','I',12);
				// Título
				$this->Cell(0,6,utf8_decode($registro['nombreProducto']),0,1,'L',true);
				// Salto de línea
				$this->Ln(4);
					
				$qRequisitos = $cr->listarRequisitosProducto($conexion, $registro['idPais'], $registro['idProducto'], $tipoRequisito);
				$this->SetFont('Times','',10);
					
				for ($i=0; $i<count($qRequisitos);$i++){
					$this->MultiCell(0,5,utf8_decode($qRequisitos[$i]['detalleImpreso']),0,1);
				}
					
				$this->Cell(50);
			}
		}
		
		//Nota de referencia a revisión de información en el sistema
		
	}
	
	function Detalle($idSolicitud, $tipo){
		$conexion = new Conexion();
		$cr = new ControladorRequisitos();
	
		$this->SetFont('Times','B',10);
		
		// Movernos a la derecha
		$this->Cell(80);
		// Título
		$this->Cell(35,10,utf8_decode('REPÚBLICA DEL ECUADOR'),0,0,'C');
		// Salto de línea
		$this->Ln(5);
		
		$this->Cell(80);
		$this->Cell(40,10,utf8_decode('MINISTERIO DE AGRICULTURA, GANADERÍA, ACUACULTURA Y PESCA'),0,0,'C');
		// Salto de línea
		$this->Ln(10);
		
		$this->Cell(75);
		$this->Cell(45,15,utf8_decode('AGENCIA DE REGULACIÓN Y CONTROL FITO Y ZOOSANITARIO - AGROCALIDAD'),0,0,'C');
		
		// Salto de línea
		$this->Ln(15);
		$this->Cell(50);
	
		if($tipo == 'Importación'){
			$ci = new ControladorImportaciones();
			$qRegistro = $ci->abrirImportacionReporte($conexion, $idSolicitud);
			$tipoRequisito = 'Importación';
			$ruta = 'origen';
				
				
			$this->Cell(0,10,utf8_decode('PERMISO FITOSANITARIO PARA LA IMPORTACIÓN'),0,0);
			$this->Ln(5);
			$this->Cell(60);
			$this->Cell(0,10,utf8_decode('IMPORTATION FITOSANITARY PERMIT'),0,0);
		}
	
		if($tipo == 'Fitosanitario'){
			$cf = new ControladorFitosanitario();
			$qRegistro = $cf->abrirFitoExportacionReporte($conexion, $idSolicitud);
			$tipoRequisito = 'Exportación';
			$ruta = 'destino';
				
			$this->Cell(0,10,utf8_decode('ANEXO 1 CUADRO DE EXPORTADORES INSPECCIONADOS'),0,0);
			$this->Ln(5);
			$this->Cell(60);
		}
	
		if($tipo == 'Zoosanitario'){
			$cz = new ControladorZoosanitarioExportacion();
			$qRegistro = $cz->abrirZooExportacionReporte($conexion, $idSolicitud);
			$tipoRequisito = 'Exportación';
			$ruta = 'destino';
				
			$this->Cell(0,10,utf8_decode('CERTIFICADO ZOOSANITARIO DE EXPORTACIÓN'),0,0);
			$this->Ln(5);
			$this->Cell(60);
			$this->Cell(0,10,utf8_decode('ZOOSANITARY CERTIFICATE FOR EXPORT'),0,0);
		}
		
		if($tipo == 'TransitoInternacional'){
		    $cti = new ControladorTransitoInternacional();
		    $qRegistro = $cti->abrirTransitoInternacionalReporte($conexion, $idSolicitud);
		    $tipoRequisito = 'Tránsito';
		    $ruta = 'origen';
		    
		    
		    $this->Cell(0,10,utf8_decode('AUTORIZACIÓN FITOSANITARIA DE TRÁNSITO INTERNACIONAL'),0,0);
		    $this->Ln(5);
		    $this->Cell(60);
		    //$this->Cell(0,10,utf8_decode('IMPORTATION FITOSANITARY PERMIT'),0,0);
		}
	
		$this->Ln(5);
		
		$this->Cell(0,30,utf8_decode('Fecha emisión: '.$qRegistro[0]['fechaInicio']),0,0);
	
		
		$this->Ln(1);
		$this->Cell(140);
		$this->Cell(50,30,utf8_decode('N°: '.$qRegistro[0]['idVue']),0,0);
		// Salto de línea
		$this->Ln(5);
	
		// Salto de línea
		$this->Ln(20);
	
		if($tipo == 'Fitosanitario'){
			$header = array(utf8_decode('Identificación'), utf8_decode('Razón Social'), utf8_decode('Nombre del Producto'), utf8_decode('Número /Descripción de bultos'));
				
			$ancho = array(25, 70, 55, 40);
			$this->SetWidths($ancho);
				
			// Cabeceras
			$this->Row($header);
				
			// Datos
			$qProveedores = $cf->listarReporteProveedoresProductos($conexion, $idSolicitud);
	
			$totalBultos = 0;
	
			foreach($qProveedores as $proveedores){
				$totalBultos += $proveedores['numeroBultos'];
			  
				$this->Row(array($proveedores['identificadorOperador'],utf8_decode($proveedores['razonSocial']),utf8_decode($proveedores['nombreProducto']),number_format($proveedores['numeroBultos']) . ' ' .$proveedores['unidadBultos']));
			}
	
			$this->Row(array('','','Total',number_format($totalBultos)));
			
			
			// Salto de línea
			$this->Ln(20);
						
			
			$header = array(utf8_decode(' '), utf8_decode('Número/Descripción de bultos'), utf8_decode('Cantidad producto/Unidad'));
			
			$ancho = array(90, 50, 50);
			$this->SetWidths($ancho);
			
			// Cabeceras
			$this->Row($header);
			
			// Datos
			$qProveedores = $cf->listarReporteTotalProductos($conexion, $idSolicitud);
			
			$totalBultos = 0;
			$totalPiezas = 0;
			
			foreach($qProveedores as $proveedores){
				$totalBultos += $proveedores['numeroBultos'];
				$totalPiezas += $proveedores['cantidadProducto'];
					
				$this->Row(array(utf8_decode($proveedores['nombreProducto']),number_format($proveedores['numeroBultos'])  . ' ' .$proveedores['unidadBultos'], number_format($proveedores['cantidadProducto']) . ' unidades'));
			}
			
			$this->Row(array('Total',number_format($totalBultos) . ' piezas',number_format($totalPiezas) . ' unidades'));
		}else if ($tipo == 'TransitoInterncional'){
		    
		}else{
			foreach ($qRegistro as $registro){
				$this->SetFillColor(200,220,255);
				$this->SetFont('Times','I',12);
				// Título
				$this->Cell(0,6,utf8_decode($registro['nombreProducto']),0,1,'L',true);
				// Salto de línea
				$this->Ln(4);
					
				$qRequisitos = $cr->listarRequisitosProducto($conexion, $registro['idPais'], $registro['idProducto'], $tipoRequisito);
				$this->SetFont('Times','',10);
					
				for ($i=0; $i<count($qRequisitos);$i++){
					$this->MultiCell(0,5,utf8_decode($qRequisitos[$i]['detalleImpreso']),0,1);
				}
					
				$this->Ln(8);
			}
			
			$this->Ln(5);
		}
		
		
	}

	function Footer(){
		
		// Go to 1.5 cm from bottom
		$this->SetY(-45);
		
		$this->SetFont('Times','I',6);
		$this->MultiCell(0,5,utf8_decode('Recuerde que puede revisar sus permisos en línea, accediendo a la página https://ventanillaunica.aduana.gob.ec/vpt_server/vpt_flex/ctft_inqr.html#, ingresando Número de solicitud (Eje:01009991201500000005P) y Número de emisión de certificado (Eje:PLJML43WQX51961).'),0,1);
		
		// Select Arial italic 8
		$this->SetFont('Times','I',8);
		// Print centered page number
		$this->Cell(0,10,utf8_decode('Pág. ').$this->PageNo()."/{nb}",0,0,'C');
	}
	
	function SetWidths($w)
	{
		//Set the array of column widths
		$this->widths=$w;
	}
	
	function SetAligns($a)
	{
		//Set the array of column alignments
		$this->aligns=$a;
	}
	
	function Row($data)
	{
		//Calculate the height of the row
		$nb=0;
		for($i=0;$i<count($data);$i++)
			$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
		$h=5*$nb;
		//Issue a page break first if needed
		$this->CheckPageBreak($h);
		//Draw the cells of the row
		for($i=0;$i<count($data);$i++)
		{
			$w=$this->widths[$i];
			$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
			//Save the current position
			$x=$this->GetX();
			$y=$this->GetY();
			//Draw the border
			$this->Rect($x,$y,$w,$h);
			//Print the text
			$this->MultiCell($w,5,$data[$i],0,$a);
			//Put the position to the right of the cell
			$this->SetXY($x+$w,$y);
		}
		//Go to the next line
		$this->Ln($h);
	}
	
	function CheckPageBreak($h)
	{
		//If the height h would cause an overflow, add a new page immediately
		if($this->GetY()+$h>$this->PageBreakTrigger)
			$this->AddPage($this->CurOrientation);
	}
	
	function NbLines($w,$txt)
	{
		//Computes the number of lines a MultiCell of width w will take
		$cw=&$this->CurrentFont['cw'];
		if($w==0)
			$w=$this->w-$this->rMargin-$this->x;
		$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
		$s=str_replace("\r",'',$txt);
		$nb=strlen($s);
		if($nb>0 and $s[$nb-1]=="\n")
			$nb--;
		$sep=-1;
		$i=0;
		$j=0;
		$l=0;
		$nl=1;
		while($i<$nb)
		{
			$c=$s[$i];
			if($c=="\n")
			{
				$i++;
				$sep=-1;
				$j=$i;
				$l=0;
				$nl++;
				continue;
			}
			if($c==' ')
				$sep=$i;
			$l+=$cw[$c];
			if($l>$wmax)
			{
				if($sep==-1)
				{
					if($i==$j)
						$i++;
				}
				else
					$i=$sep+1;
				$sep=-1;
				$j=$i;
				$l=0;
				$nl++;
			}
			else
				$i++;
		}
		return $nl;
	}
}
?>



