<?php

session_start();


	require_once '../../clases/ControladorRegistroOperador.php';
	require_once '../../clases/ControladorRequisitos.php';
	require_once '../../clases/ControladorAuditoria.php';
	require_once '../../clases/ControladorCatalogos.php';

	require_once '../../clases/ControladorEnsayoEficacia.php';
	require_once '../../clases/ControladorDossierFertilizante.php';

	require_once '../ensayoEficacia/clases/Hoja.php';
	require_once '../ensayoEficacia/clases/PdfStandar.php';
	require_once '../ensayoEficacia/clases/PdfCertificado.php';


class GeneradorCertificadosFertilizante{

	    
	public function generarSolicitudRegistro($conexion,$DocumentoLegal,$id_solicitud){
        ob_start();
        $mensaje=array();
        $mensaje['mensaje'] = 'Error generando documento';
        $mensaje['estado'] = 'NO';
        
        $esBorrador=true;
        if($DocumentoLegal==='SI')
            $esBorrador=false;
            
            
            $ce = new ControladorEnsayoEficacia();
            $cr = new ControladorRegistroOperador();
            //$cc = new ControladorCatalogos();
            $cf=new ControladorDossierFertilizante();
            
            $datos=array();
            $operador=array();
            
            
            if($id_solicitud!=null && $id_solicitud!='_nuevo'){
                
                $datos=$cf->obtenerSolicitud($conexion, $id_solicitud);
                $identificador=$datos['identificador'];						//El duenio del documento
                
                $fabricantes=$cf->obtenerFabricantesDossier($conexion, $id_solicitud);
                
                
                $res = $cr->buscarOperador($conexion, $identificador);
                $operador = pg_fetch_assoc($res);
                $fileName='DFS_'.$identificador."_".$id_solicitud.'.pdf';
                
            }
            else{
                
                return $mensaje;
            }
            
            
            //************************************************** INICIO ***********************************************************
            
            $margen_superior=40;
            $margen_inferior=15;
            $margen_izquierdo=20;
            $margen_derecho=17;
            
            
            $doc=new PdfStandar('P','mm','A4',true,'UTF-8');
            
            if($esBorrador)
                $doc->PonerBorrador(true);
                else
                    $doc->PonerBorrador(false);
                    
                    
                    $doc->SetLineWidth(0.1);
                    
                    
                    $doc->SetMargins($margen_izquierdo, $margen_superior, $margen_derecho);
                    $doc->SetAutoPageBreak(TRUE, $margen_inferior);
                    $doc->setImageScale(PDF_IMAGE_SCALE_RATIO);
                    
                    $doc->AddPage();
                    
                    $xfull=$doc->getPageWidth()-$margen_izquierdo-$margen_derecho;
                    
                    
                    //****************************** INICIA *************************************
                    $doc->setY($margen_superior);
                    
                    $doc->SetTextColor();
                    $doc->SetFont('times', 'B', 12);
                    
                    
                    $doc->Cell($xfull,9,"SOLICITUD DE REGISTRO",0,1,'C',false,0,0,true,'C','C');
                    
                    $doc->SetFont('times', '', 10);
                    $y=$doc->GetY();
                    $date=new DateTime();
                    $sdate=$date->format('Y-m-d');
                    $doc->Cell($xfull,5,'Lugar y fecha: Quito, '.$sdate,0,1,'');
                    $doc->Ln();
                    $doc->Cell($xfull,5,'Señor(a): Director(a) ejecutiv(o/a)',0,1,'');
                    $doc->Cell($xfull,5,'(ANC)',0,1,'');
                    $doc->Ln();
                    $strTitulo='<style></style>';
                    $strTitulo=$strTitulo.'<div style="text-align:justify">';
                    $strTitulo=$strTitulo.'El suscrito '.$operador['razon_social'].' con dirección '.$operador['direccion'].', '.$operador['parroquia'].', '.$operador['canton'].', '.$operador['provincia'].', telefono: '.$operador['telefono_uno'];
                    
                    
                    $strTitulo=$strTitulo.', en cumplimiento a lo dispuesto por las normas nacionales, solicito el Registro del producto: ';
                    $strTitulo=$strTitulo.'"'.$datos['producto_nombre'].'"';
                    
                    $strTitulo=$strTitulo.'</div>';
                    $x=$margen_izquierdo;
                    $y=$doc->GetY();
                    $doc->writeHTMLCell($xfull,5,$x,$y,$strTitulo,0,1);
                    
                    $doc->Cell($xfull,5,'Al efecto, consigno la siguiente información y el expediente que anexo:',0,1,'');
                    
                    
                    $str='';
                    
                    $str=	$str.'<ol type="a">';
                    
                    $str=$str.'<li><p>ACTIVIDAD DEL SOLICITANTE : <br/>';
                    $items = $ce->obtenerOperacionesDelOperador($conexion,$identificador,'IAF');
                    $stri='';
                    foreach ($items as $item){
                        $stri=$stri.', '.$item['operacion'];
                    }
                    $stri=substr($stri,2);
                    $str=$str.$stri.'</p></li>';
                    
                    $str=$str.'<li><p>DIRECCION DE LAS INSTALACIONES : <br/>';
                    $str=$str.$operador['direccion'].', '.$operador['parroquia'].', '.$operador['canton'].', '.$operador['provincia'].', '.$operador['telefono_uno'].', '.$operador['correo'];
                    $str=$str.'</p></li>';
                    
                    
                    $str=$str.'<li><p>NOMBRE Y DIRECCIÓN DE LA (S) EMPRESA(S) FABRICANTE(S) O FORMULADORA(S): <br/>';
                    $stri='';
                    foreach ($fabricantes as $item){
                        $stri=$stri.$item['empresa'].', '.$item['direccion'].'. ';
                    }
                    
                    $str=$str.$stri;
                    $str=$str.'</p></li>';
                    
                    $str=$str.'<li><p>NOMBRE DEL PRODUCTO : <br/>';
                    
                    $str=$str.$datos['producto_nombre'];
                    $str=$str.'</p></li>';
                    
                    $str=$str.'<li><p>NOMBRE DEL INGREDIENTE ACTIVO: <br/>';
                    $stri='';
                    
                    $items=$cf->obtenerComposicionProducto($conexion,$id_solicitud);
                    $ingrediente_activo= array_column($items,'nombre');
                    $ingrediente_activo=array_values( $ingrediente_activo);
                    $ingrediente_activo=join(', ', $ingrediente_activo);
                    
                    $str=$str.$ingrediente_activo;
                    $str=$str.'</p></li>';
                    $str=$str.'<li><p>PAÍS(ES) DE ORIGEN: <br/>';
                    
                    $items=$cf->obtenerFabricantesDossier($conexion,$id_solicitud);
                    $ingredientes_paises= array_column($items,'pais');
                    $ingredientes_paises=array_values( $ingredientes_paises);
                    $ingredientes_paises=array_unique($ingredientes_paises);
                    $ingredientes_paises=join(', ', $ingredientes_paises);
                    $str=$str.$ingredientes_paises;
                    $str=$str.'</p></li>';
                    
                    $str=$str.'<li><p>USO(S) PROPUESTO(S): <br/>';
                    
                   
						  $items=$ce->obtenerPlagas($conexion,'IAF');
                    $usos='';
                    foreach($items as $item){
                        if($item['codigo']==$datos['uso'])
                            $usos=$item['nombre'];
                    }
                    $str=$str.$usos;
                    $str=$str.'</p></li>';
                    
                    $str=$str.'<li><p>TIPO Y CODIGO DE FORMULACIÓN: <br/>';
                    
                    $str=$str.'N/A';
                    $str=$str.'</p></li>';
                    
                    $str=$str.'<li><p>PAÍS(ES) DE PROCEDENCIA: <br/>';
                    
                    $str=$str.'N/A';
                    $str=$str.'</p></li>';
                    
                    $str=	$str.'</ol>';
                    $y=$doc->GetY();
                    $doc->writeHTMLCell($xfull,5,$x,$y,$str,0,1);
                    $y=$doc->GetY();
                    $y=$y+20;
                    
                    $xm=$xfull/4;
                    
                    $doc->SetAbsXY($xm,$y);
                    $doc->Cell($xm,5,'Firma del Solicitante','T',0,'C');
                    $doc->SetAbsX(2.5*$xm);
                    $doc->Cell($xm,5,'Firma del Asesor Técnico','T',0,'C');
                    
                    //******************************* FIN DE LA EDICION ****************************************************************************************
                    $paths=$ce->obtenerRutaAnexos($conexion,'dossierFertilizante');
                    $doc->Output($paths['rutaFisica'].'/'.$fileName, 'F');
                    $mensaje['datos'] = $paths['ruta'].'/'.$fileName;
                    
                    
                    ob_end_clean();
                    
                    $mensaje['mensaje'] = 'Archivo generado';
                    $mensaje['estado'] = 'exito';
                    
                    return $mensaje;
                    
    }
   	    
	public function generarCertificado($conexion,$id_solicitud,$firmante,$firmanteCargo){
		ob_start();
		$mensaje=array();
		$mensaje['mensaje'] = 'Error generando documento';
		$mensaje['estado'] = 'NO';

		$identificador='';

		$ce = new ControladorEnsayoEficacia();
		$co = new ControladorRegistroOperador();

		$cf=new ControladorDossierFertilizante();

		$datos=array();
		$operador=array();
		$fabricantes=array();


		if($id_solicitud!=null && $id_solicitud!='_nuevo'){

			$datos=$cf->obtenerSolicitud($conexion, $id_solicitud);
			$identificador=$datos['identificador'];						//El duenio del documento

			//busca los datos del operador
			$res = $co->buscarOperador($conexion, $datos['identificador']);
			$operador = pg_fetch_assoc($res);


			$fabricantes=$cf->obtenerFabricantesDossier($conexion,$id_solicitud);

		}



		$fileName='CDF_'.$identificador."_".$id_solicitud.'.pdf';



		//************************************************** INICIO ***********************************************************

		$margen_superior=50;
		$margen_inferior=15;
		$margen_izquierdo=20;
		$margen_derecho=17;

		$x=$margen_izquierdo;

		$doc=new PdfCertificado('P','mm','A4',true,'UTF-8');

		$tipoLetra='times';

		//******************************************* FIRMA *************************************************************************
		$datosCertificado=$ce->obtenerDatosCertificado();
		$doc->setSignature($datosCertificado['rutaCertificado'], $datosCertificado['rutaCertificado'], $datosCertificado['password'], '',1, $datosCertificado['info']);

		$doc->SetLineWidth(0.1);

		$doc->SetMargins($margen_izquierdo, $margen_superior, $margen_derecho);
		$doc->SetAutoPageBreak(TRUE, $margen_inferior);


		$doc->AddPage();

		$doc->SetFont($tipoLetra, '', 9);

		$xfull=$doc->getPageWidth()-$margen_izquierdo-$margen_derecho;

		$hoja=new Hoja();

		//****************************** INICIA *************************************
		$doc->SetTextColor();
		$doc->SetFont('times', 'B', 9);

		
		$y=$doc->GetY();
		$doc->MultiCell($xfull*3/4,12,"CERTIFICADO DE REGISTRO NACIONAL DE FERTILIZANTES, ENMIENDAS DE SUELO Y PRODUCTOS AFINES DE USO AGRÍCOLA",0,'C',false,1,$x +$xfull/8,'',true,0,false,true,0,'M');

		$doc->SetFont($tipoLetra, '', 8);

		$doc->Cell($xfull,2,"",0,1,'C',false,0,0,true,'C','C');


		//******************************************************************
		$y=$doc->GetY();
		$items=$cf->obtenerTipoProducto($conexion,'IAF');
		$tipoProducto='';
		foreach($items as $item){
			if($item['id_tipo_producto']==$datos['id_tipo_producto']){
				$tipoProducto=$item['nombre'];
			}
		}

		$textoHtml='<b>Tipo del producto: </b><u>'. $tipoProducto.'</u>';
		$doc->writeHTMLCell(0,5,$margen_izquierdo,$y,$textoHtml,0,1,false,true,'L');

		$y=$doc->GetY();
		$items=$cf->obtenerClasificaciones($conexion,$id_solicitud);
		$clasificacion=array();
		foreach($items as $item){
			$clasificacion[]=$item['nombre'];
		}
		$clasificacion=join(', ',$clasificacion);
		$textoHtml='<b>Clasificación del producto: </b><u>'. $clasificacion.'</u>';
		$doc->writeHTMLCell(0,5,$margen_izquierdo,$y,$textoHtml,0,1,false,true,'L');


		$y=$doc->GetY();
		$textoHtml='<b>NOMBRE COMERCIAL: </b><u>'. strtoupper($datos['producto_nombre']).'</u>';
		$doc->writeHTMLCell(0,5,$margen_izquierdo,$y,$textoHtml,0,1,false,true,'L');

		$y=$doc->GetY();

		$str='';
		$f=array();

		$composiciones=$cf->obtenerComposicionProducto($conexion,$id_solicitud);
		$h=array();
		foreach($composiciones as $clave=>$valor){
			$h[$valor['nombre']]=$valor['cantidad'].' '.$valor['codigo'];
		}
		$f[]=array('INGREDIENTE ACTIVO',$h);

		$itemHeader=array('CONCENTRACIÓN');
		$hoja->escribirTabla($str,$numero++,1,'COMPOSICIÓN DECLARADA:', $f,$itemHeader);
		$doc->writeHTMLCell($xfull,5,$x,$y,$str,0,1);

		$str='';
		$y=$doc->GetY();
		$empresas=array();
		foreach($fabricantes as $key=>$valor){

			$empresas[]=$valor['empresa'].' - '.strtoupper($valor['pais']);
		}

		$hoja->escribirParrafoLibre($str,'FABRICANTE(S)', $empresas);
		$doc->writeHTMLCell($xfull,5,$x,$y,$str,0,1);

		$y=$doc->GetY();
		
		$items=$ce->obtenerPlagas($conexion,'IAF');
		$usos='';
		foreach($items as $item){
			if($item['codigo']==$datos['uso'])
				$usos=$item['nombre'];
		}
		$textoHtml='<b>Uso específico: </b><u>'. $usos.'</u>';
		$doc->writeHTMLCell(0,5,$margen_izquierdo,$y,$textoHtml,0,1,false,true,'L');

		$y=$doc->GetY();
		$prefijo='RIA-FT';
		$datos['id_certificado']=$ce->obtenerRegistro($conexion,'dossierFertilizante',$prefijo);
		$textoHtml='<b>No Registro de producto: <u>'.$datos['id_certificado'] .'</u></b>';
		$doc->writeHTMLCell(0,5,$margen_izquierdo,$y,$textoHtml,0,1,false,true,'L');

		$y=$doc->GetY();
		$textoHtml='<b>TITULAR DE REGISTRO: </b><u>'. $operador['razon_social'].'</u>';
		$doc->writeHTMLCell(0,5,$margen_izquierdo,$y,$textoHtml,0,1,false,true,'L');

      $date=new DateTime();
      $sdate=$date->format('Y-m-d');
      $datos['fecha_inscripcion']= $date;
      $y=$doc->GetY();
		$textoHtml='<b>FECHA DE EMISIÓN: </b><u>'. $sdate.'</u>';
		$doc->writeHTMLCell(0,5,$margen_izquierdo,$y,$textoHtml,0,1,false,true,'L');

		$y=$doc->GetY();
		$y=$y+10;

		$xm=$xfull/3;

		$doc->SetAbsXY($xm,$y);

		$doc->writeHTMLCell(0,5,$margen_izquierdo,$y,'<i><u>Documento firmado electrónicamente</u></i>',0,1,false,true,'C');

		$doc->Cell($xfull,5,strtoupper($firmante),0,1,'C');
		$doc->Cell($xfull,5,strtoupper( $firmanteCargo),0,1,'C');
		$doc->Cell($xfull,5,"AGENCIA DE REGULACIÓN Y CONTROL FITO Y ZOOSANITARIO – AGROCALIDAD",0,1,'C');

		$y=$doc->GetY();
		$y=$y+10;

		$paths=$ce->obtenerRutaAnexos($conexion,'dossierFertilizante');
		$rutaQR=$paths['rutaUrl'].'/'.$fileName;
		$style = array(
		 'border' => 2,
		 'vpadding' => 'auto',
		 'hpadding' => 'auto',
		 'fgcolor' => array(0,0,0),
		 'bgcolor' => false, //array(255,255,255)
		 'module_width' => 1, // width of a single module in points
		 'module_height' => 1 // height of a single module in points
		);
		$doc->write2DBarcode($rutaQR, 'QRCODE,H', 20, $y, 50, 50, $style, 'N');

		// area para validar firma
		$doc->Image('../ensayoEficacia/img/logo_agrocalidad.gif', 100, $y, 50, 50, 'GIF');
		$doc->setSignatureAppearance(100, $y, 50, 50);



		$doc->Output($paths['rutaFisica'].'/'.$fileName, 'F');

		$mensaje['datos'] = $paths['ruta'].'/'.$fileName;



		ob_end_clean();

		$mensaje['mensaje'] = 'Archivo generado';
		$mensaje['estado'] = 'exito';
		$mensaje['id_certificado']=$datos['id_certificado'];

		return $mensaje;
	}

	public function generarPuntosMinimos($conexion,$id_solicitud,$firmante,$firmanteCargo,$noCertificado=''){
		ob_start();
		$ce = new ControladorEnsayoEficacia();
		$co = new ControladorRegistroOperador();
		$cr=new ControladorRequisitos();

		$cf=new ControladorDossierFertilizante();

		$identificador='';
		$datos=array();

		if($id_solicitud!=null && $id_solicitud!='_nuevo'){

			$datos=$cf->obtenerSolicitud($conexion, $id_solicitud);

			$etiqueta=$cf->obtenerEtiquetaSolicitud($conexion, $id_solicitud);
			$identificador=$datos['identificador'];						//El duenio del documento

			//busca los datos del operador
			$res = $co->buscarOperador($conexion, $datos['identificador']);
			$operador = pg_fetch_assoc($res);

			$formuladores=$cf->obtenerFabricantesDossier($conexion,$id_solicitud);
		}


		$fileName='DF_EPM_'.$identificador."_".$id_solicitud.'.pdf';

		//************************************************** INICIO ***********************************************************

		$margen_superior=50;
		$margen_inferior=15;
		$margen_izquierdo=20;
		$margen_derecho=17;

		$doc=new PdfCertificado('P','mm','A4',true,'UTF-8');


		//******************************************* FIRMA *************************************************************************
		$datosCertificado=$ce->obtenerDatosCertificado();

		$doc->setSignature($datosCertificado['rutaCertificado'], $datosCertificado['rutaCertificado'], $datosCertificado['password'], '',1, $datosCertificado['info']);
		//******************************************* FIN FIRMA *********************************************************************

		$doc->SetLineWidth(0.1);

		$doc->SetMargins($margen_izquierdo, $margen_superior, $margen_derecho);
		$doc->SetAutoPageBreak(TRUE, $margen_inferior);


		$doc->AddPage();

		$xfull=$doc->getPageWidth()-$margen_izquierdo-$margen_derecho;

		$hoja=new Hoja();
		$tipoLetra='times';

		//****************************** INICIA *************************************
		$doc->SetTextColor();

		$doc->SetFont('times', 'B', 9);

		
		$doc->Cell($xfull,9,"PUNTOS MÍNIMOS DE LA ETIQUETA",0,1,'C',false,0,0,true,'C','C');

		$doc->SetFont($tipoLetra, '', 8);

		$doc->Cell($xfull,5,"",0,1,'C',false,0,0,true,'C','C');

		//******************************************************************
		$y=$doc->GetY();
		$textoHtml='<b>Nombre del Producto: </b><u>'. strtoupper($datos['producto_nombre']).'</u>';
		$doc->writeHTMLCell(0,5,$margen_izquierdo,$y,$textoHtml,0,1,false,true,'L');

		$y=$doc->GetY();
		$items=$cf->obtenerTipoProducto($conexion,'IAF');
		$tipoProducto='';
		foreach($items as $item){
			if($item['id_tipo_producto']==$datos['id_tipo_producto']){
				$tipoProducto=$item['nombre'];
			}
		}

		$textoHtml='<b>Tipo del producto: </b><u>'. $tipoProducto.'</u>';
		$doc->writeHTMLCell(0,5,$margen_izquierdo,$y,$textoHtml,0,1,false,true,'L');

		$y=$doc->GetY();
		$x=$margen_izquierdo;
		$str='';
		$f=array();

		$composiciones=$cf->obtenerComposicionProducto($conexion,$id_solicitud);
		$h=array();
		foreach($composiciones as $clave=>$valor){
			$h[$valor['nombre']]=$valor['cantidad'].' '.$valor['codigo'];
		}
		$f[]=array('INGREDIENTE ACTIVO',$h);

		$itemHeader=array('CONCENTRACIÓN');
		$hoja->escribirTabla($str,$numero++,1,'Composición:', $f,$itemHeader);
		$doc->writeHTMLCell($xfull,5,$x,$y,$str,0,1);

		$y=$doc->GetY();

		$textoHtml='<b>Número de Registro: <u>'.$noCertificado.'</u></b>';

		$doc->writeHTMLCell(0,5,$margen_izquierdo,$y,$textoHtml,0,1,false,true,'L');

		//**********************************************************************************************************************************
		$y=$doc->GetY();
		$textoHtml='<b>Titular del registro: </b><u>'. $operador['razon_social'].'</u>';
		$doc->writeHTMLCell(0,5,$margen_izquierdo,$y,$textoHtml,0,1,false,true,'L');

		$str='';
		$empresas=array();
		foreach($formuladores as $key=>$valor){
			$empresas[]=$valor['empresa'].' - '.strtoupper($valor['pais']);
		}

		$hoja->escribirParrafoLibre($str,'FORMULADOR(ES):', $empresas);
		$y=$doc->GetY();
		$doc->writeHTMLCell(0,5,$margen_izquierdo,$y,$str,0,1,false,true,'L');

		//**********************************************************************************************************************************
		$y=$doc->GetY();
		$y=$y+4;
		$str="<b>LEA CUIDADOSAMENTE ESTA ETIQUETA ANTES DE USAR ESTE PRODUCTO</b>";
		$doc->writeHTMLCell($xfull,5,$x,$y,$str,0,1,false,true,'C');

		$y=$doc->GetY();
		$str="<b>MANTÉNGASE BAJO LLAVE FUERA DEL ALCANCE DE LOS NIÑOS</b>";
		$doc->writeHTMLCell($xfull,5,$x,$y,$str,0,1,false,true,'C');
		$y=$doc->GetY();
		$str="<b>Precauciones de uso y aplicación:</b>";
		$doc->writeHTMLCell($xfull,5,$x,$y,$str,0,1);
		$y=$doc->GetY();

		$y=$doc->GetY();
		$str=$etiqueta['precaucion_uso'];
		$doc->writeHTMLCell($xfull,5,$x,$y,$str,0,1);

		$y=$doc->GetY();
		$str='';
		$hoja->escribirParrafoLibre($str,'Medidas relativas a la seguridad:',array($etiqueta['medidas_seguridad']));
		$doc->writeHTMLCell($xfull,5,$x,$y,$str,0,1);

		$y=$doc->GetY();
		$y=$y+4;
		$str="<b>EL MAL USO PUEDE CAUSAR DAÑOS A LA SALUD Y AL AMBIENTE</b>";
		$doc->writeHTMLCell($xfull,5,$x,$y,$str,0,1,false,true,'C');

		$y=$doc->GetY();
		$str='';
		$hoja->escribirParrafoLibre($str,'Almacenamiento y manejo del producto:',array($etiqueta['almacen_manejo']));
		$doc->writeHTMLCell($xfull,5,$x,$y,$str,0,1);

		$y=$doc->GetY();
		$str='';
		$hoja->escribirParrafoLibre($str,'Medidas relativas a primeros auxilios:',array($etiqueta['medidas_auxilio']));
		$doc->writeHTMLCell($xfull,5,$x,$y,$str,0,1);

		$y=$doc->GetY();
		$str='';
		$hoja->escribirParrafoLibre($str,'Nota para el médico tratante:',array($etiqueta['nota_medico']));
		$doc->writeHTMLCell($xfull,5,$x,$y,$str,0,1);

		$y=$doc->GetY();
		$y=$y+4;
		$str="<b>".$etiqueta['rotulo_veneno']."</b>";
		$doc->writeHTMLCell($xfull,5,$x,$y,$str,0,1,false,true,'C');

		$y=$doc->GetY();
		$str='';
		$hoja->escribirParrafoLibre($str,'Medidas relativas para la disposición de envases vacíos:',array($etiqueta['medidas_envases']));
		$doc->writeHTMLCell($xfull,5,$x,$y,$str,0,1);

		$y=$doc->GetY();
		$str='';
		$hoja->escribirParrafoLibre($str,'Medidas relativas para la protección del ambiente:',array($etiqueta['medidas_ambiente']));
		$doc->writeHTMLCell($xfull,5,$x,$y,$str,0,1);

		$y=$doc->GetY();
		$str='';
		$hoja->escribirParrafoLibre($str,'Instrucciones de uso y manejo:',array($etiqueta['instruccion_uso']));
		$doc->writeHTMLCell($xfull,5,$x,$y,$str,0,1);

		$y=$doc->GetY();
		$str='';
		$hoja->escribirParrafoLibre($str,'Modo de empleo:',array($etiqueta['modo_empleo']));
		$doc->writeHTMLCell($xfull,5,$x,$y,$str,0,1);

		//******************************************************************************
		$y=$doc->GetY();
		$y=$y+4;
		$str="<b>CONSULTE CON UN INGENIERO AGRÓNOMO</b>";
		$doc->writeHTMLCell($xfull,5,$x,$y,$str,0,1,false,true,'C');

		//verifico si tiene cultivos
		$items=$cf->obtenerCultivos($conexion,$id_solicitud);
		if(sizeof($items)>0){

			$itemHeader=array();
			$itemHeader[]=array('titulo'=>'CULTIVO','ancho'=>25);
			$itemHeader[]=array('titulo'=>'DOSIS','ancho'=>25);
			$itemHeader[]=array('titulo'=>'EPOCA DE APLICACIÓN','ancho'=>25);
			$itemHeader[]=array('titulo'=>'FRECUENCIA DE APLICACIÓN','ancho'=>25);

			$cultivos=$ce->obtenerProductosXSubTipo($conexion,'CULTIVOS');

			$items=array();
			$cultivosDossier=$cf->obtenerCultivos($conexion,$id_solicitud);
			foreach($cultivosDossier as $item){
				$cultivo = array_filter($cultivos, function ($elemento) use ($item) { return trim((strtolower( $elemento['id_producto'])) == $item['id_cultivo']); } );
				$cultivo=current($cultivo);
				$items[]=$cultivo['nombre_comun'].'(<i>'.$cultivo['nombre_cientifico'].'</i>)';
			}
			$cultivo=join(', ',$items);
			$items=array();

			$unidades=$cf->obtenerUnidadesMedidaComposicion($conexion);
			$unidad= array_filter($unidades, function ($elemento) use ($datos) { return trim((strtolower( $elemento['id_unidad_medida'])) == $datos['unidad_dosis']); } );
			$unidad=current($unidad);
			$dosis=$datos['dosis'].' '.$unidad['codigo'];
			$items[]=array($cultivo,$dosis,$datos['epoca_aplicacion'],$datos['frecuencia_aplicacion']);
			$textoHtml='';
			$hoja->escribirTabla4($textoHtml,'','','INSTRUCCIONES DE USO',$items,$itemHeader);
			$y=$doc->GetY();
			$doc->writeHTMLCell(0,5,$margen_izquierdo,$y,$textoHtml,0,1,false,true,'L');

		}

		$y=$doc->GetY();
		$str='';
		$hoja->escribirParrafoLibre($str,'PERÍODO DE REINGRESO:',array($etiqueta['periodo_reingreso']));
		$doc->writeHTMLCell($xfull,5,$x,$y,$str,0,1);

		$y=$doc->GetY();
		$str='';
		$hoja->escribirParrafoLibre($str,'FITOTOXICIDAD:',array($etiqueta['fitoxicidad']));
		$doc->writeHTMLCell($xfull,5,$x,$y,$str,0,1);

		$y=$doc->GetY();
		$str='';
		$hoja->escribirParrafoLibre($str,'COMPATIBILIDAD:',array($etiqueta['compatibilidad']));
		$doc->writeHTMLCell($xfull,5,$x,$y,$str,0,1);

		$y=$doc->GetY();
		$str='';
		$hoja->escribirParrafoLibre($str,'RESPONSABILIDAD:',array($etiqueta['responsabilidad']));
		$doc->writeHTMLCell($xfull,5,$x,$y,$str,0,1);

		$y=$doc->GetY();
		$str='';
		$items=$cr->listarCategoriaToxicologica($conexion,'IAP');
      $categoria='';
      while ($item = pg_fetch_assoc($items)){
         if(intval($item['id_categoria_toxicologica'])==intval($datos['id_categoria_toxicologica'])){
            $categoria=$item['categoria_toxicologica'];
            break;
         }
      }
		$hoja->escribirParrafoLibre($str,'CATEGORÍA TOXICOLÓGICA:',array($categoria));
		$doc->writeHTMLCell($xfull,5,$x,$y,$str,0,1);



		$y=$doc->GetY();
		$y=$y+10;

		$xm=$xfull/3;

		$doc->SetAbsXY($xm,$y);

		$doc->writeHTMLCell(0,5,$margen_izquierdo,$y,'<i><u>Documento firmado electrónicamente</u></i>',0,1,false,true,'C');

		$doc->Cell($xfull,5,strtoupper($firmante),0,1,'C');
		$doc->Cell($xfull,5,strtoupper( $firmanteCargo),0,1,'C');
		$doc->Cell($xfull,5,"AGENCIA DE REGULACIÓN Y CONTROL FITO Y ZOOSANITARIO – AGROCALIDAD",0,1,'C');

		$y=$doc->GetY();
		$yMax=$doc->getPageHeight()-$margen_inferior;
		if($y+55>$yMax)
			$doc->AddPage();
		$doc->Cell($xfull,2,"",0,1,'C',false,0,0,true,'C','C');
		$y=$doc->GetY();

		$paths=$ce->obtenerRutaAnexos($conexion,'dossierFertilizante');
		$rutaQR=$paths['rutaUrl'].'/'.$fileName;
		$style = array(
		 'border' => 2,
		 'vpadding' => 'auto',
		 'hpadding' => 'auto',
		 'fgcolor' => array(0,0,0),
		 'bgcolor' => false, //array(255,255,255)
		 'module_width' => 1, // width of a single module in points
		 'module_height' => 1 // height of a single module in points
		);

		$doc->write2DBarcode($rutaQR, 'QRCODE,H', 20, $y, 50, 50, $style, 'N');

		// area para validar firma
		$doc->Image('../ensayoEficacia/img/logo_agrocalidad.gif', 100, $y, 50, 50, 'GIF');
		$doc->setSignatureAppearance(100, $y, 50, 50);



		$doc->Output($paths['rutaFisica'].'/'.$fileName, 'F');

		$mensaje['datos'] = $paths['ruta'].'/'.$fileName;

		ob_end_clean();

		$mensaje['mensaje'] = 'Archivo generado';
		$mensaje['estado'] = 'exito';

		return $mensaje;

	}
		
	public function subirProducto($conexion,$idSolicitud,$idCertificado,$fechaRegistro,$tipo_aplicacion,$usuario,$usuarioDatos){
		ob_start();
		$ce = new ControladorEnsayoEficacia();
		$cr = new ControladorRequisitos();
		$cc = new ControladorCatalogos();
		
		$ca = new ControladorAuditoria();
		$cf=new ControladorDossierFertilizante();
			
		$dossier=$cf->obtenerSolicitud($conexion,$idSolicitud);
		$subTipoProducto=array();
		//verifico que no se repita el nombre
		$registro=$dossier['clon_registro_madre'];
		
		$partidaArancelaria='0000000000';
		$subTipoProducto=array();
		
		$unidadMedida='KG';
		$idCategoriaToxicologica=0;
		$CategoriaToxicologica='';
		$idFormulacion=0;
		

		$esClon=false;
		if(($dossier['objetivo']=='DF_PCL') && ($registro!=null) && (strlen(trim($registro))>0)){
			$subTipoProducto=$ce->obtenerSubTipoXregristo($conexion,$registro,null);
			$partidaArancelaria=$subTipoProducto['partida_arancelaria'];
			$esClon=true;
		}
		else{
			$subTipoProducto=pg_fetch_assoc($cr->abrirSubtipoProducto($conexion,$dossier['id_subtipo_producto']));
			
		}
		$nombreProducto=$dossier['producto_nombre'];		
		$empresa=$dossier['identificador'];
		$declaracionVenta='';
		$observaciones='';
			
			$idSubtipoProducto=$subTipoProducto['id_subtipo_producto'];
			$producto = $cc->buscarProductoXNombre($conexion,$idSubtipoProducto,$nombreProducto);
			
			//verifico que no se repita el registro
			$numeroTotalRegistro = pg_num_rows($cr->buscarNombreRegistroProducto($conexion,$idCertificado));
			if(pg_num_rows($producto) == 0){
				
				if($numeroTotalRegistro == 0) {
					$mensajesAuditoria=array();
					if($esClon){
						//registra un clon			

						if($partidaArancelaria != '' || $partidaArancelaria != 0){
							$qCodigoProducto = $cc->obtenerCodigoProducto($conexion, $partidaArancelaria);
							$codigoProducto = str_pad(pg_fetch_result($qCodigoProducto, 0, 'codigo'), 4, "0", STR_PAD_LEFT);
						}else{
							$codigoProducto = 0;
						}
						$nombreCientifico='';
						
						$idProducto = pg_fetch_result($cr->guardarNuevoProducto($conexion, $nombreProducto, $nombreCientifico, $codigoProducto,  $partidaArancelaria, $idSubtipoProducto, $subTipoProducto['ruta'], $subTipoProducto['unidad_medida'], 'NO', 'NO', $usuario),0,'id_producto');					
						$mensajesAuditoria[]='ha creado el producto CLON con id '.$idProducto.' de nombre '.$nombreProducto;

						//Busca al producto madre
						$idProductoMadre=$subTipoProducto['id_producto'];
						$productoInocuidad=pg_fetch_assoc( $cr->buscarProductoInocuidad($conexion,$idProductoMadre));
						$idCategoriaToxicologica=$productoInocuidad['id_categoria_toxicologica'];
						$CategoriaToxicologica=$productoInocuidad['categoria_toxicologica'];
						
						$idFormulacion=$productoInocuidad['id_formulacion'];
						$nombreFormulacion=$productoInocuidad['formulacion'];
						
						$dosis=$productoInocuidad['dosis'];
						$unidadMedidaDosis=$productoInocuidad['unidad_dosis'];
						$periodoCarencia=$productoInocuidad['periodo_carencia_retiro'];
						$periodoReingreso=$productoInocuidad['periodo_reingreso'];
						$observaciones=$productoInocuidad['observacion'];
						$declaracionVenta=$productoInocuidad['declaracion_venta'];

						if($fechaRegistro == '') $fechaRegistro = $fechaActual = date('Y-m-d');
						
						$cr->guardarProductoInocuidad($conexion, $idProducto, $idFormulacion,$nombreFormulacion , $idCertificado, $dosis, $unidadMedidaDosis, $periodoCarencia, $periodoReingreso, $observaciones,$idCategoriaToxicologica,$CategoriaToxicologica,$fechaRegistro,$empresa,$declaracionVenta);
						$mensajesAuditoria[]='ha creado el producto CLON inocuidad con id '.$idProducto.' con registro '.$idCertificado;

						//sube los ingredientes activos				
						$vector=$cr->listarComposicionProductosInocuidad($conexion,$idProductoMadre);

						while ($item = pg_fetch_assoc($vector)){
							$idIngredienteActivo=$item['id_ingrediente_activo'];
							if(pg_num_rows($cr->buscarComposicion($conexion, $idProducto, $idIngredienteActivo))==0){
								
								$cr -> guardarProductoInocuidadTMP($conexion, $idProducto);
								$cr -> guardarComposicion($conexion, $idProducto, $idIngredienteActivo,$item['ingrediente_activo'],$item['concentracion'],$item['unidad_medida']);	
								$mensajesAuditoria[]='ha asociado el producto CLON con id '.$idProducto.' con la concentracion '.$item['ingrediente_activo'].' '.$item['concentracion'].$item['unidad_medida'];								
							}
						}
						
						//sube las presentaciones
						$vector=$cr->listarCodigoInocuidad($conexion,$idProductoMadre);
						while ($item = pg_fetch_assoc($vector)){
							$qSubcodigo = $cc->obtenerCodigoInocuidad($conexion, $idProducto);
							$subcodigo = str_pad(pg_fetch_result($qSubcodigo, 0, 'codigo'), 4, "0", STR_PAD_LEFT);
							$presentacion=$item['presentacion'];
							$unidad=$item['unidad_medida'];							
							if(pg_num_rows($cr->buscarCodigoInocuidad($conexion, $idProducto, $presentacion,$unidad))==0){								
								$cr -> guardarProductoInocuidadTMP($conexion, $idProducto);
								$cr -> guardarNuevoCodigoInocuidad($conexion, $idProducto,$subcodigo, $presentacion, $unidad);							
								$mensajesAuditoria[]='ha asociado al producto CLON con id '.$idProducto.' la presentación '.$presentacion;									
							}						
						}

						//sube los codigos complementarios y suplementarios	
						$vector=$cr->listarCodigoComplementarioSuplementario($conexion,$idProductoMadre);	
						while ($item = pg_fetch_assoc($vector)){
							$codigoComplementario=$item['codigo_complementario'];
							$codigoSuplementario=$item['codigo_suplementario'];
							if(pg_num_rows($cr->buscarCodigoComplementarioSuplementario($conexion, $idProducto, $codigoComplementario, $codigoSuplementario))==0){
								$cr -> guardarNuevoCodigoComplementarioSuplementario($conexion, $idProducto, $codigoComplementario, $codigoSuplementario);									
								$mensajesAuditoria[]='ha asociado al producto CLON con id '.$idProducto.' el codigo complementaio '.$codigoComplementario.' y el codigo suplementario '.$codigoSuplementario;								
							}										
						}

						//sube los fabricantes
						$vector=$cr->listarFabricanteFormulador($conexion,$idProductoMadre);	
						while ($item = pg_fetch_assoc($vector)){
							$formulador=$item['nombre'];
							$idPaisOrigen=$item['id_pais_origen'];
							$nombrePaisFabricante=$item['pais_origen'];							
							if(pg_num_rows($cr->buscarPaisformuladorFabricante($conexion,$formulador, $idPaisOrigen, $idProducto))==0){								
								$cr -> guardarProductoInocuidadTMP($conexion, $idProducto);
								$codigos = $cr -> guardarNuevoFabricanteFormulador($conexion, $idProducto,$formulador,$idPaisOrigen, $nombrePaisFabricante);	
								$mensajesAuditoria[]='ha asociado al producto CLON con id '.$idProducto.' al fabricante '.$formulador;															
							}												
						}

						//Sube los usos
						$vector=$cr->listarUsos($conexion,$idProductoMadre);	
						while ($uso = pg_fetch_assoc($vector)){
							$idUso=$uso['id_uso'];
							$nombreUso=$uso['nombre_uso'];
							$idCultivo=$uso['id_aplicacion_producto'];
							if(pg_num_rows($cr->buscarUsoProducto($conexion, $idProducto,$idUso, $idCultivo))==0){
								$cr ->guardarNuevoUso($conexion, $idProducto, $idUso,$idCultivo);
								$mensajesAuditoria[]='ha asociado al producto CLON con id '.$idProducto.' el uso '.$nombreUso.' al cultivo '.$uso['nombre_comun'];											
							}							
							
						}
						
					}
					else{
						//Registra con dossier
						$archivo=$dossier['ruta_dossier'];
						$etiqueta=$cf->obtenerEtiquetaSolicitud($conexion,$idSolicitud);
						$idCategoriaToxicologica=$etiqueta['id_categoria_toxicologica'];
						$CategoriaToxicologica=pg_fetch_result($ce->obtenerCategoriaToxicologica($conexion,$idCategoriaToxicologica),0,'categoria_toxicologica');
						//$informeFinal=$ce->obtenerInformeFinalPorExpediente($conexion,$dossier['id_informe_final']);
						$nombreFormulacion='';
						$idFormulacion=$dossier['id_formulacion'];
						try{
							$nombreFormulacion=pg_fetch_result($ce->obtenerFormulacionActual($conexion,$idFormulacion),0,'formulacion');
						}catch(Exception $e){}
						$cultivos=$cf->obtenerCultivos($conexion,$idSolicitud);
						$enCultivos=array_column($cultivos, 'nombre_comun');
						$enCultivos=join(',',$enCultivos);
						$enCultivos='('.$enCultivos.')';
						
						$dosis=$dossier['dosis'].' para '.$enCultivos;
						
						$unidadMedidaDosis=pg_fetch_result($cc->obtenerUnidadMedida($conexion,trim($dossier['unidad_dosis'])),0,'codigo');
						
						$periodoReingreso=trim($etiqueta['periodo_reingreso']);
						$periodoCarencia=trim($dossier['carencia']);

						if($partidaArancelaria != '' || $partidaArancelaria != 0){
							$qCodigoProducto = $cc->obtenerCodigoProducto($conexion, $partidaArancelaria);
							$codigoProducto = str_pad(pg_fetch_result($qCodigoProducto, 0, 'codigo'), 4, "0", STR_PAD_LEFT);
						}else{
							$codigoProducto = 0;
						}
						$nombreCientifico='';
						
						//limita la extención de los campos del modulo de registros
						$periodoReingreso=$this->limitarCaracteres($periodoReingreso,2046);	//limita a periodo de reingreso a 2046
						$periodoCarencia=$this->limitarCaracteres($periodoCarencia,1022);	//limita a periodo de reingreso a 1022
						$dosis=$this->limitarCaracteres($dosis,510);	//limita a periodo de reingreso a 510
						$observaciones =$this->limitarCaracteres($observaciones,1022);	//limita observaciones a 1022

						//$idProducto = pg_fetch_result($cr->guardarNuevoProducto($conexion, $nombreProducto, $nombreCientifico, $codigoProducto,  $partidaArancelaria, $idSubtipoProducto, $archivo, $unidadMedida),0,'id_producto');
						$idProducto = pg_fetch_result($cr->guardarNuevoProducto($conexion, $nombreProducto, $nombreCientifico, $codigoProducto,  $partidaArancelaria, $idSubtipoProducto, $archivo, $unidadMedida, 'NO', 'NO', $usuario),0,'id_producto');
						
						$mensajesAuditoria[]='ha creado el producto con id '.$idProducto.' de nombre '.$nombreProducto;
						if ($idCategoriaToxicologica == '') $idCategoriaToxicologica = 0;
						if ($idFormulacion == '') $idFormulacion = 0;
						if($fechaRegistro == '') $fechaRegistro = $fechaActual = date('Y-m-d');
						
						$cr->guardarProductoInocuidad($conexion, $idProducto, $idFormulacion,$nombreFormulacion , $idCertificado, $dosis, $unidadMedidaDosis, $periodoCarencia, $periodoReingreso, $observaciones,$idCategoriaToxicologica,$CategoriaToxicologica,$fechaRegistro,$empresa,$declaracionVenta);
						$mensajesAuditoria[]='ha creado el producto inocuidad con id '.$idProducto.' con registro '.$idCertificado;
						
						//sube los ingredientes activos	
						$vector=$cf->obtenerComposicionProducto($conexion,$idSolicitud);
						//$vector=$cf->obtenerIngredientesSolicitud($conexion,$idSolicitud);
						if(count($vector)>0){
							$ingredientes=array();
							foreach($vector as $item){
								$idIngredienteActivo=$item['elemento'];
								if(pg_num_rows($cr->buscarComposicion($conexion, $idProducto, $idIngredienteActivo))==0){
									
									$cr -> guardarProductoInocuidadTMP($conexion, $idProducto);
									$cr -> guardarComposicion($conexion, $idProducto, $idIngredienteActivo,$item['nombre'],$item['cantidad'],$item['codigo']);
									$ingredientes[]=$item['nombre'];	
									
									$mensajesAuditoria[]='ha asociado el producto con id '.$idProducto.' con la concentracion '.$item['nombre'].' '.$item['cantidad'].$item['codigo'];
									
								}
							}
							/*Comentado ya que campo no es necesario EJAR
							 * $ingredienteActivo=join(' + ',$ingredientes);
							$cr -> actualizarComposicionProducto($conexion,$idProducto,$ingredienteActivo);*/ 
						}

						//sube las presentaciones
						$qSubcodigo = $cc->obtenerCodigoInocuidad($conexion, $idProducto);
						$subcodigo = str_pad(pg_fetch_result($qSubcodigo, 0, 'codigo'), 4, "0", STR_PAD_LEFT);
						$presentacion=$dossier['cantidad'];
						$unidad=pg_fetch_result($cc->obtenerUnidadMedida($conexion,trim($dossier['unidad'])),0,'codigo');
								
						if(pg_num_rows($cr->buscarCodigoInocuidad($conexion, $idProducto, $presentacion,$unidad))==0){
									
							$cr -> guardarProductoInocuidadTMP($conexion, $idProducto);
							$cr -> guardarNuevoCodigoInocuidad($conexion, $idProducto,$subcodigo, $presentacion, $unidad);
							//$codigos[]=array('codigo_complementario'=>str_pad($item['codigo_complementario'], 4, '0', STR_PAD_LEFT),'codigo_suplementario'=>str_pad($item['codigo_suplementario'], 4, '0', STR_PAD_LEFT));

							$mensajesAuditoria[]='ha asociado al producto con id '.$idProducto.' la presentación '.$presentacion.' '.$unidad;
									
						}

						//sube los fabricantes
						$vector=$cf->obtenerFabricantesDossier($conexion,$idSolicitud);							
						if(count($vector)>0){
							foreach($vector as $item){
								$formulador=$item['empresa'];
								$idPaisOrigen=$item['id_pais'];
								$nombrePaisFabricante=$item['pais'];
								
								if(pg_num_rows($cr->buscarPaisformuladorFabricante($conexion,$formulador, $idPaisOrigen, $idProducto))==0){
									
									$cr -> guardarProductoInocuidadTMP($conexion, $idProducto);
									$codigos = $cr -> guardarNuevoFabricanteFormulador($conexion, $idProducto,$formulador,$idPaisOrigen, $nombrePaisFabricante);	
									$mensajesAuditoria[]='ha asociado al producto con id '.$idProducto.' al fabricante '.$formulador;
									
								}
								
							}						
						}


						//Sube los usos
						if(count($cultivos)>0){
							$idUso=$dossier['uso'];
							$nombreUso=pg_fetch_result($cr->abrirUsoInocuidad($conexion,$idUso),0,'nombre_uso');							
							foreach($cultivos as $cultivo){
								$idCultivo=$cultivo['id_cultivo'];
								if(pg_num_rows($cr->buscarUsoProducto($conexion, $idProducto,$idUso, $idCultivo))==0){
									$cr ->guardarNuevoUso($conexion, $idProducto, $idUso,$idCultivo);
									$mensajesAuditoria[]='ha asociado al producto con id '.$idProducto.' el uso '.$nombreUso.' al cultivo '.$cultivo['nombre_comun'];
									
								}							
							}
						}
						
					}
					
					/*AUDITORIA*/
					$qTransaccion = $ca -> buscarTransaccion($conexion, $idProducto, $tipo_aplicacion);
					$transaccion = pg_fetch_assoc($qTransaccion);
					
					if($transaccion['id_transaccion'] == ''){
						$qLog = $ca -> guardarLog($conexion,$tipo_aplicacion);
						$qTransaccion = $ca ->guardarTransaccion($conexion, $idProducto, pg_fetch_result($qLog, 0, 'id_log'));
					}
					foreach($mensajesAuditoria as $auditoria){							
						$ca ->guardarInsert($conexion, pg_fetch_result($qTransaccion, 0, 'id_transaccion'),$usuario,'El usuario <b>' . $usuarioDatos . '</b> '.$auditoria);											
					}
					/*FIN AUDITORIA*/
					

				}				
			
		}
		ob_end_clean();
	}

	public function limitarCaracteres($textoOriginal,$largoMaximo){
		$nuevoTexto=trim($textoOriginal);
		if(strlen($nuevoTexto)>$largoMaximo){
			$nuevoTexto=substr($nuevoTexto,0,$largoMaximo);
		}
		return $nuevoTexto;
	}
	

}


?>


