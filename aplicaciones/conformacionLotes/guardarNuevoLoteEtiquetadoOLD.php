<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorLotes.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/Constantes.php';
require_once '../general/fpdf.php';
require_once '../general/phpqrcode/qrlib.php';
require_once '../../clases/ControladorAdministrarCaracteristicas.php';

$conexion = new Conexion();
$cl = new ControladorLotes();
$cc = new ControladorCatalogos();
$cac = new controladorAdministrarCaracteristicas();

define('RUTA_MODULO', 'aplicaciones/conformacionLotes/');
define('RUTA_PNG_TEMP',RUTA_SERVIDOR_OPT.'/'.RUTA_APLICACION.'/'.RUTA_MODULO.'/temp/');


$nrLote = $_POST['numeroLote'];
$codigoLote = $_POST['loteCodigo'];
$fechaEtiquetado = $_POST['fechaEtiqueta'];
$cantidad = $_POST['cantidad'];
$peso = $_POST['peso'];
$nroEtiqueta = $_POST['nroEtiqueta'];
$tipoX = $_POST['tipo'];
$tipox=htmlspecialchars ($_POST['tipo'],ENT_NOQUOTES,'UTF-8');
$pais = $_POST['pais'];
$tipoProducto = $_POST['tipoProducto'];
$producto = $_POST['producto'];
$exportador = $_POST['exportador'];
$variedadX = $_POST['variedad'];
$opcion= $_POST['opcion'];
$idLote= $_POST['idLote'];
$operador= $_POST['usuario'];
$nOperador= htmlspecialchars ($_POST['exportador'],ENT_NOQUOTES,'UTF-8');
$nombreOperador= $_POST['exportador'];
$idProducto = $_POST['idProducto'];
$tamanioEtiqueta = $_POST['tamanioEtiqueta'];
$lote=$codigoLote.'  -  '.$nrLote;
switch ($opcion){
	case 'guardarEtiqueta':		
		try {
			
			$rutaArchivo="etiquetas/".$operador."-".$nrLote.".pdf";
			$idEtiqueta=password_hash($operador."-".$Lote,PASSWORD_DEFAULT);
			$conexion->ejecutarConsulta("begin;");
			$resultadoIdEtiquetado=$cl->guardarLoteEtiquetado($conexion,$idLote,$nrLote,$codigoLote,$fechaEtiquetado,$cantidad,$peso,$nroEtiqueta,$operador,RUTA_MODULO.$rutaArchivo,$idEtiqueta);
			$cl->estadoLote($conexion,$idLote);
			
			$idEtiquetado= pg_fetch_row($resultadoIdEtiquetado);

			if (!file_exists(RUTA_PNG_TEMP))
			mkdir(RUTA_PNG_TEMP, 0777,true);

			function eliminarTemporales($dir) {
				$files = array_diff(scandir($dir), array('.','..'));
				foreach ($files as $file) {
					(is_dir("$dir/$file")) ? eliminarTemporales("$dir/$file") : unlink("$dir/$file");
				}
				//ELIMINAR DIRECTORIO return rmdir($dir);
			}
			
			$res=$cc->ObtenerProductoPorId($conexion,$idProducto);
			$nombreProducto= pg_fetch_assoc($res);
			
			$string=$nombreProducto['nombre_comun'];
			
			$plantilla=$cl->obtenerPlantillaxID($conexion, $tamanioEtiqueta);
			
			$plantillaProducto= pg_fetch_assoc($plantilla);
			
			/*****************************///////////////////// >>>>>>>>>>>>INICIO PLANTILLAS
			if($plantillaProducto['plantilla']=='P1'){
			    
			    $modulo=pg_fetch_assoc($cac->obtenerModulo($conexion, 'PRG_CONFO_LOTE'));
			    
			    ///// caracteristica variedad ///
			   
			    $formulario=pg_fetch_assoc($cac->obtenerFormularioXidModulo($conexion, "nuevoProductoProveedor",$modulo['id_aplicacion']));
			    
			  
			    if($formulario>0){
			        $caracteristica=pg_fetch_assoc($cac->obtenerCaracteristicaXnombreYformulario($conexion, 'Variedad', $idProducto, $formulario['id_formulario']));
			        if($caracteristica>0){
			            $registro=pg_fetch_assoc($cl->obtenerRegistroUnicoDeLote($conexion, $idLote));			            
			            $cac->estructurarTabla($conexion, 'v_caracteristica', 'g_trazabilidad.registro', 'id_registro');			            
			            $variedadFila=pg_fetch_assoc($cac->obtenerItemCaracteristicaXnombreYregistro($conexion,'id_registro', 'Variedad', $registro['id_registro'], 'v_caracteristica', $formulario['id_formulario']));
			        }
			    }
			    			    
			    
			    if($variedadFila>0){
			        $variedad=$variedadFila['nombre'].' - ';
			    } else{
			        if($variedad!=""){
			            $variedad.=' - ';
			        } 
			    }			    
		        ///////////////// fin  /////////////////
		        
			    //////////// caracteristica tipo /////////
			    
			    
			    $formulario=pg_fetch_assoc($cac->obtenerFormularioXidModulo($conexion, "nuevoLote",$modulo['id_aplicacion']));
			    
			    if($formulario>0){
			        $caracteristica=pg_fetch_assoc($cac->obtenerCaracteristicaXnombreYformulario($conexion, 'Tipo', $idProducto, $formulario['id_formulario']));
			        if($caracteristica>0){			           
			            $cac->estructurarTabla($conexion, 'v_caracteristica_tipo', 'g_trazabilidad.lotes', 'id_lote');			
			            $tipoFila=pg_fetch_assoc($cac->obtenerItemCaracteristicaXnombreYregistro($conexion,'id_lote', 'Tipo', $idLote, 'v_caracteristica_tipo', $formulario['id_formulario']));
			        }
			    }
			    
			    if($tipoFila>0){
			        $tipo=$tipoFila['nombre'].' - ' . $tipoFila['descripcion'];
			    } 	
			    
			    //////////////// fin //////////////
			    			    
			    $errorCorrectionLevel = 'L';
			    $matrixPointSize = 4;		    
			 			    
			    $infoQr='Exportador:'.$operador .'-'. $nOperador ."\n".
			 			    'Producto:'.$producto .'-'. $tipoProducto .' - '. $variedad .$tipo ."\n".
			 			    'Peso:'.$peso .'kg.'. "\n".
			 			    'Destino:'.$pais ."\n".
			 			    'Lote:'.$lote ."\n".
			 			    'Identificación Etiqueta:'.$idEtiqueta
			 			    ;
			 			    
			 			    
			 			    $rutaImagenQr='temp/'.$nrLote.$i.'.png';
			 			    $filename = RUTA_PNG_TEMP.$nrLote.$i.'.png';
			 			    QRcode::png($infoQr, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
			 			    //$pdf->Image($rutaImagenQr , $pdf->SetY(10.5) ,$pdf->SetX(0.7), 29, 29,'PNG');
			 			    
			 			    //FIN GENERACION QR
			 			    
			 			    if($plantillaProducto['hoja']=='etiqueta'){
			 			        
			 			        $pdf = new FPDF('L','mm',array(100,50));
			 			        $pdf->SetAutoPageBreak(true,0);
			 			        $pdf->SetFont('Arial','',8);
			 			        $fila=0;
			 			    
			 			    
    			 			    for($i=0;$i<=$nroEtiqueta-1;$i++){
    			 			        $pdf->AddPage();
    			 			        $pdf->Image('img/EtiquetaTrazabilidad.png' , $pdf->SetY($fila) ,$pdf->SetX(0), 100 , 50,'PNG');    			 			        
    			 			        $pdf->Image($rutaImagenQr , $pdf->SetY(10.5) ,$pdf->SetX(0.7), 29, 29,'PNG');
    			 			        
    			 			        $fila+=18;
    			 			        $pdf->SetXY(31,$fila);
    			 			        $pdf->MultiCell(80,3,$operador.' - '.substr(utf8_decode($nombreOperador),0,26),0,'L',0 );
    			 			        
    			 			        $fila+=9;
    			 			        $pdf->SetXY(31,$fila);
    			 			        $pdf->MultiCell(100,2,utf8_decode($producto).' - '.utf8_decode($tipoProducto),0,'L',0 );
    			 			        
    			 			        $fila+=5;
    			 			        $pdf->SetXY(31,$fila);
    			 			        //$pdf->MultiCell(100,2,$variedad.' - '. $tipo,0,'L',0 );
    			 			        $pdf->MultiCell(100,2,$variedad. substr(utf8_decode($tipo),0,39), 0,'L',0 );
    			 			        
    			 			        $fila+=5;
    			 			        $pdf->SetXY(39,$fila);
    			 			        $pdf->MultiCell(30,2,$peso."kg",0,'L',0 );
    			 			        
    			 			        $pdf->SetXY(63,$fila);
    			 			        $pdf->MultiCell(38,3,substr(utf8_decode($pais),0,50),0,'L',0 );
    			 			        
    			 			        $fila+=8;
    			 			        $pdf->SetXY(31,$fila);
    			 			        $pdf->MultiCell(100,3,utf8_decode($lote),0,'L',0 );
    			 			        
    			 			        $fila=0;
    			 			    }
    			 			    
    			 			    $pdf->Output($rutaArchivo,'F');
			 			    } else{
			 			        if($plantillaProducto['orientacion']=='v'){
			 			                
			 			                $pdf = new FPDF('P','mm','A4');
			 			                
			 			                $pdf->SetTopMargin(80);
			 			                $pdf->SetAutoPageBreak(true,0);
			 			                $pdf->SetFont('Arial','',8);
			 			                $fila=10;			 			                			 			                
			 			               
			 			                $contador=0;
			 			                $nCajas=1;
			 			                
			 			                while ($contador<$nroEtiqueta){
			 			                    $pdf->AddPage();
			 			                    for($i=0;$i<=$plantillaProducto['cantidad']-1;$i++){
			 			                        if($contador<$nroEtiqueta){
			 			                            $pdf->SetFillColor(135,229,159);
			 			                            $pdf->Image('img/EtiquetaTrazabilidad.png' , $pdf->SetY($fila) ,$pdf->SetX(12), 100 , 50,'PNG');
			 			                            $pdf->Image($rutaImagenQr , $pdf->SetY($fila + 10.5) ,$pdf->SetX(12.7), 29, 29,'PNG');			 			                         
			 			                            
			 			                            $fila+=18;
			 			                            $pdf->SetXY(43,$fila);
			 			                            $pdf->MultiCell(80,3,$operador.' - '.substr(utf8_decode($nombreOperador),0,26),0,'L',0 );
			 			                            
			 			                            $fila+=9;
			 			                            $pdf->SetXY(43,$fila);
			 			                            $pdf->MultiCell(100,2,utf8_decode($producto).' - '.utf8_decode($tipoProducto),0,'L',0 );
			 			                            
			 			                            $fila+=5;
			 			                            $pdf->SetXY(43,$fila);
			 			                            //$pdf->MultiCell(100,2,$variedad.' - '. $tipo,0,'L',0 );
			 			                            $pdf->MultiCell(100,2,$variedad.substr(utf8_decode($tipo),0,39), 0,'L',0 );
			 			                            
			 			                            $fila+=5;
			 			                            $pdf->SetXY(51,$fila);
			 			                            $pdf->MultiCell(30,2,$peso."kg",0,'L',0 );
			 			                            
			 			                            $pdf->SetXY(75,$fila);
			 			                            $pdf->MultiCell(38,3,substr(utf8_decode($pais),0,50),0,'L',0 );
			 			                            
			 			                            $fila+=8;
			 			                            $pdf->SetXY(43,$fila);
			 			                            $pdf->MultiCell(100,3,utf8_decode($lote),0,'L',0 );		
			 			                            
			 			                            $fila+=8;
			 			                            $contador+=1;
			 			                            $nCajas+=1;
			 			                        }
			 			                    }
			 			                    $fila=10;
			 			                    
		 			                }	 			            
			 			       	 			               
			 			        } else{
			 			            
			 			            $pdf = new FPDF('L','mm','A4');
			 			            
			 			            $pdf->SetTopMargin(80);
			 			            $pdf->SetAutoPageBreak(true,0);
			 			            $pdf->SetFont('Arial','',8);
			 			           
			 			            
			 			            $contador=0;
			 			            $nCajas=1;
			 			            
			 			            while ($contador<$nroEtiqueta){
			 			                $pdf->AddPage();
			 			                
			 			                $lim = $plantillaProducto['cantidad']/2;
			 			                $lim = ceil($lim);	 
			 			                
			 			                $fila=10;
			 			                $posiX=43;
			 			                
			 			                $posiIm=12;
			 			                $posiQr=12.7;
			 			                
			 			                $limitePosiX=0;
			 			                
			 			                $limite=0;
			 			                
			 			                for($i=0;$i<$lim;$i++){
			 			                    
			 			                    $limitePosiX+=1;
			 			                    
			 			                    for($j=0;$j<2;$j++){
			 			                    
    			 			                    if($contador<$nroEtiqueta){   			 			                     
    			 			                       
    			 			                        $limite+=1;
    			 			                        
    			 			                        if($limite<=$plantillaProducto['cantidad']){
    			 			                            
        			 			                        if($limitePosiX==1){
        			 			                          $fila=10;
        			 			                        } else if($limitePosiX==2){
        			 			                          $fila=67;
        			 			                        } else{
        		 			                              $fila=124;
        			 			                        }
        			 			                        
        			 			                        $pdf->SetFillColor(135,229,159);
        			 			                        $pdf->Image('img/EtiquetaTrazabilidad.png' , $pdf->SetY($fila) ,$pdf->SetX($posiIm), 100 , 50,'PNG');
        			 			                        $pdf->Image($rutaImagenQr , $pdf->SetY($fila + 10.5) ,$pdf->SetX($posiQr), 29, 29,'PNG');
        			 			                        
        			 			                        $fila+=18;
        			 			                        $pdf->SetXY($posiX,$fila);
        			 			                        $pdf->MultiCell(80,3,$operador.' - '.substr(utf8_decode($nombreOperador),0,26),0,'L',0 );
        			 			                        
        			 			                        $fila+=9;
        			 			                        $pdf->SetXY($posiX,$fila);
        			 			                        $pdf->MultiCell(100,2,utf8_decode($producto).' - '.utf8_decode($tipoProducto),0,'L',0 );
        			 			                        
        			 			                        $fila+=5;
        			 			                        $pdf->SetXY($posiX,$fila);			 			                        
        			 			                        $pdf->MultiCell(100,2,$variedad.substr(utf8_decode($tipo),0,39), 0,'L',0 );
        			 			                        
        			 			                        $fila+=5;
        			 			                        $pdf->SetXY($posiX+8,$fila);
        			 			                        $pdf->MultiCell(30,2,$peso."kg",0,'L',0 );
        			 			                        
        			 			                        $pdf->SetXY($posiX+32,$fila);
        			 			                        $pdf->MultiCell(38,3,substr(utf8_decode($pais),0,50),0,'L',0 );
        			 			                        
        			 			                        $fila+=8;
        			 			                        $pdf->SetXY($posiX,$fila);
        			 			                        $pdf->MultiCell(100,3,utf8_decode($lote),0,'L',0 );    			 			                     
        			 			                        
        			 			                        $contador+=1;
        			 			                        $nCajas+=1;
        			 			                        
        			 			                        $posiX+=110;
        			 			                        $posiIm+=110;
        			 			                        $posiQr+=110;
    			 			                        
    			 			                        } else{
    			 			                            $limite=1;
    			 			                        }
    			 			                    }    			 			                    
    			 			                }
    			 			                
    			 			                $posiX=43;
    			 			                $posiIm=12;
    			 			                $posiQr=12.7;
			 			                
			 			                }
			 			               
			 			            }
			 			        }
			 			    
			 			    $pdf->Output($rutaArchivo,'F');
			    
			         }
			         
			}else{
			    
			    $errorCorrectionLevel = 'L';
			    $matrixPointSize = 4;
			    
			    $res=$cl->areasXlote($conexion,$idLote);
			    $areas= pg_fetch_assoc($res);
			    
			    $resul=$cl->proveedoresXlote($conexion, $idLote);
			    $proveedores= pg_fetch_assoc($resul);
			    
			    $datosOperador= $operador.'.'.$areas['area_operador'].'-'.$nombreOperador;
			    //$datosProveedor= $proveedores['identificador_proveedor'].'.'.$areas['area_proveedor'].'-'.$proveedores['nombre_proveedor'];
			    $datosProveedor= $proveedores['nombre_proveedor'];
			    
			    //INICIO GENERACION QR
			    
			    //echo password_hash("rasmuslerdorf", PASSWORD_DEFAULT)."\n";
			    
			    $infoQr='Lote Envío:'.$nrLote."\n".
			 			    'Acopiador:'.$datosOperador."\n".
			 			    'Proveedor: '.$datosProveedor."\n".
			 			    'Producto:'.$producto ."\n".
			 			    'Lote Producción:'.$codigoLote."\n".
			 			    'Fecha de Empaque:'.$fechaEtiquetado."\n".
			 			    'Destino:'.$pais ."\n".
			 			    'Identificación Etiqueta:'.$idEtiqueta
			 			    ;
			 			    
			 			    
			 			    $rutaImagenQr='temp/'.$nrLote.$i.'.png';
			 			    $filename = RUTA_PNG_TEMP.$nrLote.$i.'.png';
			 			    QRcode::png($infoQr, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
			 			    //$pdf->Image($rutaImagenQr , $pdf->SetY(10.5) ,$pdf->SetX(0.7), 29, 29,'PNG');
			 			    
			 			    //FIN GENERACION QR			 			    
			 			
			 			    
			 			   if($plantillaProducto['hoja']=='A4'){
			 			       
			 			       if($plantillaProducto['orientacion']=='v'){
			 			       
			 			        $pdf = new FPDF('P','mm','A4');
			 			        
			 			        $pdf->SetTopMargin(80);
			 			        $pdf->SetAutoPageBreak(true,0);
			 			        $pdf->SetFont('Arial','',8);
			 			        $fila=10;
			 			        $posiQr=0;
			 			        
			 			        $linea=0;
			 			        $contador=0;
			 			        $nCajas=1;
			 			        
    			 			        while ($contador<$nroEtiqueta){
    			 			            $pdf->AddPage();
    			 			            for($i=0;$i<=$plantillaProducto['cantidad']-1;$i++){
    			 			                if($contador<$nroEtiqueta){
    			 			                    $pdf->SetFillColor(135,229,159);
    			 			                    $pdf->Image('img/EtiquetaTrazabilidadPitahaya.png' , $pdf->SetY($fila) ,$pdf->SetX(12), 100 , 50,'PNG');
    			 			                    $pdf->Image($rutaImagenQr , $pdf->SetY($fila + 10.5) ,$pdf->SetX(12.7), 29, 29,'PNG');
    			 			                    
    			 			                    $pdf->SetFont('Arial','',7);
    			 			                    $fila+=12;
    			 			                    $pdf->SetXY(88,$fila);
    			 			                    $pdf->MultiCell(80,3,$nrLote,0,'L',0 );
    			 			                    
    			 			                    $fila+=5;
    			 			                    $pdf->SetXY(42,$fila);    			 			                    
    			 			                    $pdf->MultiCell(70,2.8,substr(utf8_decode($datosOperador),0,100),0,'L',0 );
    			 			                    
    			 			                    $fila+=9;
    			 			                    $pdf->SetXY(42,$fila);
    			 			                    $pdf->MultiCell(70,2.8,substr(utf8_decode($datosProveedor),0,100),0,'L',0 );
    			 			                    
    			 			                    
    			 			                    $fila+=9;
    			 			                    $pdf->SetXY(42,$fila);
    			 			                    $pdf->MultiCell(30,2,substr(utf8_decode($producto),0,21),0,'L',0 );
    			 			                    
    			 			                    $pdf->SetXY(74,$fila);
    			 			                    $pdf->MultiCell(100,2,$fechaEtiquetado,0,'L',0 );
    			 			                    
    			 			                    $fila+=3;
    			 			                    $pdf->SetXY(55,$fila);
    			 			                    $pdf->MultiCell(55,2.7,substr(utf8_decode($pais),0,70),0,'L',0 );
    			 			                    
    			 			                    $fila+=7;
    			 			                    $pdf->SetXY(67,$fila);
    			 			                    $pdf->MultiCell(40,1,utf8_decode($codigoLote),0,'L',0 );
    			 			                    
    			 			                    $fila+=2;
    			 			                    $pdf->SetXY(86,$fila);
    			 			                    
    			 			                    $pdf->MultiCell(50,2,$nCajas.'-'.$nroEtiqueta,0,'L',0 );
    			 			                    
    			 			                    
    			 			                    $fila+=8;
    			 			                    $contador+=1;
    			 			                    $nCajas+=1;
    			 			                }
    			 			            }
    			 			            $fila=10;
    			 			            $posiQr+=10;
    			 			        }
    			 			        
			 			       } else{
			 			           
			 			           $pdf = new FPDF('L','mm','A4');
			 			           
			 			           $pdf->SetTopMargin(80);
			 			           $pdf->SetAutoPageBreak(true,0);
			 			           $pdf->SetFont('Arial','',8);
			 			           
			 			           
			 			           $contador=0;
			 			           $nCajas=1;
			 			           
			 			           while ($contador<$nroEtiqueta){
			 			               $pdf->AddPage();
			 			               
			 			               $lim = $plantillaProducto['cantidad']/2;
			 			               $lim = ceil($lim);
			 			               
			 			               $fila=10;
			 			               $posiX=42;
			 			               
			 			               $posiIm=12;
			 			               $posiQr=12.7;
			 			               
			 			               $limitePosiX=0;
			 			               
			 			               $limite=0;
			 			               
			 			               for($i=0;$i<$lim;$i++){
			 			                   
			 			                   $limitePosiX+=1;
			 			                   
			 			                   for($j=0;$j<2;$j++){
			 			                       
			 			                       if($contador<$nroEtiqueta){
			 			                           
			 			                           $limite+=1;
			 			                           
			 			                           if($limite<=$plantillaProducto['cantidad']){
			 			                               
			 			                               if($limitePosiX==1){
			 			                                   $fila=10;
			 			                               } else if($limitePosiX==2){
			 			                                   $fila=67;
			 			                               } else{
			 			                                   $fila=124;
			 			                               }	
			 			                               
			 			                               $pdf->Image('img/EtiquetaTrazabilidadPitahaya.png' , $pdf->SetY($fila) ,$pdf->SetX($posiIm), 100 , 50,'PNG');
			 			                               $pdf->Image($rutaImagenQr , $pdf->SetY($fila + 10.5) ,$pdf->SetX($posiQr), 29, 29,'PNG');
			 			                               
			 			                               $pdf->SetFont('Arial','',7);
			 			                               $fila+=12;
			 			                               $pdf->SetXY($posiX+46,$fila);
			 			                               $pdf->MultiCell(80,3,$nrLote,0,'L',0 );
			 			                               
			 			                               $fila+=5;
			 			                               $pdf->SetXY($posiX,$fila);
			 			                               $pdf->MultiCell(70,2.8,substr(utf8_decode($datosOperador),0,100),0,'L',0 );
			 			                               
			 			                               $fila+=9;
			 			                               $pdf->SetXY($posiX,$fila);
			 			                               $pdf->MultiCell(70,2.8,substr(utf8_decode($datosProveedor),0,100),0,'L',0 );
			 			                               
			 			                               
			 			                               $fila+=9;
			 			                               $pdf->SetXY($posiX,$fila);
			 			                               $pdf->MultiCell(30,2,substr(utf8_decode($producto),0,21),0,'L',0 );
			 			                               
			 			                               $pdf->SetXY($posiX+32,$fila);
			 			                               $pdf->MultiCell(100,2,$fechaEtiquetado,0,'L',0 );
			 			                               
			 			                               $fila+=3;
			 			                               $pdf->SetXY($posiX+13,$fila);
			 			                               $pdf->MultiCell(55,2.7,substr(utf8_decode($pais),0,70),0,'L',0 );
			 			                               
			 			                               $fila+=7;
			 			                               $pdf->SetXY($posiX+25,$fila);
			 			                               $pdf->MultiCell(40,1,utf8_decode($codigoLote),0,'L',0 );
			 			                               
			 			                               $fila+=2;
			 			                               $pdf->SetXY($posiX+44,$fila);			 			                               
			 			                               $pdf->MultiCell(50,2,$nCajas.'-'.$nroEtiqueta,0,'L',0 );
			 			                               
			 			                               $contador+=1;
			 			                               $nCajas+=1;
			 			                               
			 			                               $posiX+=110;
			 			                               $posiIm+=110;
			 			                               $posiQr+=110;
			 			                               
			 			                           } else{
			 			                               $limite=1;
			 			                           }
			 			                       }
			 			                   }
			 			                   
			 			                   $posiX=43;
			 			                   $posiIm=12;
			 			                   $posiQr=12.7;
			 			                   
			 			               }
			 			               
			 			           }
			 			       }
			 			        
		 			        } else if($plantillaProducto['hoja']=='etiqueta'){
			 			        
			 			        $pdf = new FPDF('L','mm',array(100,50));
			 			        $pdf->SetAutoPageBreak(true,0);
			 			        $pdf->SetFont('Arial','',8);
			 			        $fila=0;
			 			        $nCajas=1;	 	
			 			        
			 			        for($i=0;$i<=$nroEtiqueta-1;$i++){			 			            
			 			            
			 			            ////////////////////////
			 			            
			 			            $pdf->AddPage();
			 			            $pdf->Image('img/EtiquetaTrazabilidadPitahaya.png' , $pdf->SetY($fila) ,$pdf->SetX(0), 100 , 50,'PNG');
			 			            $pdf->Image($rutaImagenQr , $pdf->SetY(10.5) ,$pdf->SetX(0.7), 29, 29,'PNG');
			 			            
			 			            $pdf->SetFont('Arial','',7);
			 			            $fila+=12;
			 			            $pdf->SetXY(77,$fila);
			 			            $pdf->MultiCell(80,3,$nrLote,0,'L',0 );
			 			            
			 			            $fila+=5;
			 			            $pdf->SetXY(31,$fila);			 			            
			 			            $pdf->MultiCell(70,2.8,substr(utf8_decode($datosOperador),0,100),0,'L',0 );
			 			            
			 			            $fila+=9;
			 			            $pdf->SetXY(31,$fila);			 			            
			 			            $pdf->MultiCell(70,2.8,substr(utf8_decode($datosProveedor),0,100),0,'L',0 );
			 			            
			 			            
			 			            $fila+=9;
			 			            $pdf->SetXY(31,$fila);
			 			            $pdf->MultiCell(30,2,substr(utf8_decode($producto),0,21),0,'L',0 );
			 			            
			 			            $pdf->SetXY(62,$fila);
			 			            $pdf->MultiCell(100,2,$fechaEtiquetado,0,'L',0 );
			 			            
			 			            $fila+=3;
			 			            $pdf->SetXY(43,$fila);
			 			            $pdf->MultiCell(55,2.7,substr(utf8_decode($pais),0,70),0,'L',0 );
			 			            
			 			            $fila+=7;
			 			            $pdf->SetXY(57,$fila);
			 			            $pdf->MultiCell(40,1,utf8_decode($codigoLote),0,'L',0 );
			 			            
			 			            $fila+=2;
			 			            $pdf->SetXY(73,$fila);
			 			            
			 			            $pdf->MultiCell(50,2,$nCajas.'-'.$nroEtiqueta,0,'L',0 );
			 			            
			 			            $fila=0;
			 			            $nCajas+=1;
			 			        }
			 			    }
			 			    
			 			    $pdf->Output($rutaArchivo,'F');
			    
			}
			
			
			
			/**********************///><<<< FIN PLANTILLAS					
			
			//$pdf->AutoPrint();			

			echo '<embed id="visor" src="'.RUTA_MODULO.$rutaArchivo.'" width="550" height="620">';

			eliminarTemporales('temp');

		} catch(Exception $e) {
			echo $e->getMessage();
			echo "<script>console.log('" . preg_replace( "/\r|\n/", " ", $conexion->mensajeError) . "'); </script>";
		}

		$conexion->ejecutarConsulta("commit;");

		break;

}

?>

<script type="text/javascript">
    $('document').ready(function() {        
    	abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"), null, true);        
    });
</script>