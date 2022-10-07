<?php
session_start();

require_once '../../clases/ControladorRegistroOperador.php';

require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorRequisitos.php';
require_once '../../clases/ControladorAuditoria.php';

require_once '../../clases/ControladorEnsayoEficacia.php';
require_once '../../clases/ControladorDossierPecuario.php';

require_once '../ensayoEficacia/clases/Hoja.php';
require_once '../ensayoEficacia/clases/PdfStandar.php';
require_once '../ensayoEficacia/clases/PdfCertificado.php';

require_once '../ensayoEficacia/clases/Conversiones.php';

class GeneradorDocumentoPDF{
    
    public function generarCertificado($conexion,$id_solicitud,$firmante,$firmanteCargo){
        ob_start();
        $mensaje=array();
        $mensaje['mensaje'] = 'Error generando documento';
        $mensaje['estado'] = 'NO';
        
              
        
   $fileName='CDP_'.$firmante."_".$id_solicitud.'.pdf';
        
        //************************************************** INICIO ***********************************************************
        
        $margen_superior=40;
        $margen_inferior=15;
        $margen_izquierdo=20;
        $margen_derecho=17;
        
     //  header('Content-type: application/pdf');
      
        //$doc=new TCPDF('P','mm','A4',true,'UTF-8');
        
        $doc=new PdfCertificado('P','mm','A4',true,'UTF-8');
  
        $tipoLetra='times';
        
        //******************************************* FIRMA *************************************************************************
        $datosCertificado=$this->obtenerDatosCertificado();
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
        
        $doc->Cell($xfull,9,"CERTIFICADO DE REGISTRO DE PRODUCTO DE USO VETERINARIO",0,1,'C',false,0,0,true,'C','C');
        
        $doc->SetFont($tipoLetra, '', 8);
        
        
               
        $doc->Cell($xfull,5,"",0,1,'C',false,0,0,true,'C','C');
        
        
        $datos['id_certificado']='xx';
        ///******************************************************************
        
        
        $str='<table cellspacing="0" cellpadding="1" border="0">';
        $hh=array();
        $hh[]=array('NOMBRE : '=>'<b><span style="font-size:14px">'. strtoupper( $datos['nombre']).'</span></b>');
        $hh[]=array('NÚMERO DE REGISTRO : '=>'<b><span style="font-size:14px">'.$datos['id_certificado'].'</span></b>');
        $hh[]=array('CLASIFICACIÓN : '=>$puntoClasificacion);
        
        $codificacionUsos='22';

        $h=trim($h,',');
        
        $hh[]=array('INDICACIONES DE USO : '=>$h);
        
        $h='';
       
        $hh[]=array('FORMA FARMACEÚTICA : '=>$h);
        
        $dosisProducto='aaa';
        
        $h='';
        $f=array();
        foreach($dosisProducto as $key=>$valor){
            if(!array_key_exists($valor['id_especie'],$f))
                $f[$valor['id_especie']]=$valor['especie'];
        }
        $h=implode(',',array_values($f));
        $hh[]=array('ESPECIE(S) DE DESTINO : '=>$h);
        
        $periodosRetirosSolicitud='period';
        $h='';
        $f=array();
        foreach($periodosRetirosSolicitud as $key=>$valor){
            $f[]=$valor['especie'].': '.$valor['consumible'].' '.$valor['tiempo'].' '.$valor['unidad'];
            
        }
        $h=implode(', ',$f);
        if($h!='')
            $hh[]=array('PERIODOS DE RETIRO : '=>$h);
            
            $items='catalogos';
            
            $hh[]=array('PERIODO DE VIDA UTIL : '=>$datos['validez'].' '.$items['nombre']);
            
            $presentacionesSolicitud='soliciri';
            $h='';
            foreach($presentacionesSolicitud as $key=>$presentacion){
                $h=$h.', '.$presentacion['presentacion'].' '.$presentacion['cantidad'].' '.$presentacion['unidad'];
            }
            $h=trim($h,',');
            $hh[]=array('PRESENTACIONES COMERCIALES  Y TIPO DE ENVASE : '=>$h);
            
            
            $items='catalofo';
            $hh[]=array('DECLARACIÓN DE VENTA : '=>$items['nombre']);
            
            $hh[]=array('COMPOSICIÓN DECLARADA : '=>'VER HOJA SIGUIENTE');
            
            $empresas=array();
            foreach($fabricantes as $key=>$valor){
                $empresas[]=$valor['empresa'].' - '.strtoupper($valor['pais']);
                
            }
            
            $etiqueta=true;
            foreach($empresas as $empresa){
                if($etiqueta){
                    $hh[]=array('FABRICATE(S) : '=>$empresa);
                    $etiqueta=false;
                }
                else{
                    $hh[]=array(' '=>$empresa);
                }
            }
            
            $hh[]=array('TITULAR DE REGISTRO EN EL ECUADOR : '=>$operador['razon_social']);
            $fecha=new DateTime();
            
            
            
            $hh[]=array('FECHA DE INSCRIPCION DEL PRODUCTO : '=>$fecha->format("Y-m-d"));
            
            if($esModificacion){
                $hh[]=array('FECHA ÚLTIMA MODIFICACIÓN : '=>'');
            }
            
            $hoja->escribirItemsTablaTitulo($str,$hh);
            $str=$str.'</table>';
            
            
            $y=$doc->GetY();
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
            $y=$y+10;
            
        //   $paths=$ce->obtenerRutaAnexos($conexion,'dossierPecuario');
            $rutaQR= 'prueba de ingreso de informacion <br> datos de prueba';
            $style = array(
                'border' => 2,
                'vpadding' => 'auto',
                'hpadding' => 'auto',
                'fgcolor' => array(0,0,0),
                'bgcolor' => false,
                'module_width' => 1,
                'module_height' => 1
            );
            $doc->write2DBarcode($rutaQR, 'QRCODE,H',20, $y, 40, 40, $style, 'N');
            
            // area para validar firma
            $doc->Image('../ensayoEficacia/img/logo_agrocalidad.gif', 80, $y, 40, 40, 'GIF');
            $doc->setSignatureAppearance(80, $y, 40, 40);
            
            $str='';
            $doc->AddPage();
            $f=array();
            $composiciones='sss';
            $gruposIa='';
            foreach($gruposIa as $key=>$grupo){
                $h=array();
                foreach($composiciones as $clave=>$valor){
                    if($valor['grupo']==$grupo['codigo']){
                        $h[$valor['ingrediente_activo']]=$valor['cantidad'].' '.$valor['codigo'];
                    }
                }
                $f[]=array($grupo['nombre'],$h);
            }
            $itemHeader=array('Cantidad');
            $f_composicion=$f;
            $unidadesMedida='sss';//$ce->obtenerUnidadesMedida($conexion,'DP_COMP');
            $cadaUnidad='';
            foreach($unidadesMedida as $key=>$valor){
                if($valor['id_unidad_medida']==$datos['producto_unidad']){
                    $cadaUnidad=$valor['codigo'];
                    break;
                }
            }
            $cada='Cada '.$datos['producto_cantidad'].' '. $cadaUnidad.' contine:';
         
            $y=$doc->GetY();
            $y=$y+10;
            $doc->writeHTMLCell($xfull,5,$x,$y,$str,0,1);
            
            $str='';
            $y=$doc->GetY();
            $y=$y+10;
            $doc->writeHTMLCell(0,5,$margen_izquierdo,$y,'<i><u>Documento firmado electrónicamente</u></i>',0,1,false,true,'C');
            $doc->Cell($xfull,5,strtoupper($firmante),0,1,'C');
            $doc->Cell($xfull,5,strtoupper( $firmanteCargo),0,1,'C');
            $doc->Cell($xfull,5,"AGENCIA DE REGULACIÓN Y CONTROL FITO Y ZOOSANITARIO – AGROCALIDAD",0,1,'C');
            
            
            
            //******************************* FIN DE LA EDICION ****************************************************************************************
            
            $doc->Output('/var/www/html/SVNguia/aplicaciones/uath/firma/prueba.pdf','F');
            
            
            
           // $ce->constrirRutas($mensaje,$paths,$fileName);
            
            
            ob_end_clean();
            
            $mensaje['mensaje'] = 'Archivo generado';
            $mensaje['estado'] = 'exito';
            $mensaje['id_certificado']=$datos['id_certificado'];
            
            return $mensaje;
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
    
       
    public function limitarCaracteres($textoOriginal,$largoMaximo){
        $nuevoTexto=trim($textoOriginal);
        if(strlen($nuevoTexto)>$largoMaximo){
            $nuevoTexto=substr($nuevoTexto,0,$largoMaximo);
        }
        return $nuevoTexto;
    }
    
    private $esBorrador=false;
    public function PonerBorrador($esBorrador=false){
        $this->esBorrador=$esBorrador;
    }
    
    
    public function AddPage($orientation='', $format='', $keepmargins=false, $tocpage=false){
        parent::AddPage();
        if($this->esBorrador){
            
        }
    }
    
    public function Header() {
        $this->setJPEGQuality(90);
        $bMargin = $this->getBreakMargin();
        $auto_page_break = $this->AutoPageBreak;
        $this->SetAutoPageBreak(false, 0);	//Deshabilita auto pagina
        $img_file = '../ensayoEficacia/img/fondo_certificado.png';
        $this->Image($img_file, 0, 0, 209, 296, 'PNG', '', '', false, 300, '', false, false, 0);
        $this->SetAutoPageBreak($auto_page_break, $bMargin);	//Habilita auto pagina
        $this->setPageMark();											//pone marca de agua
        
    }
    
    public function Footer() {
        $this->SetY(-17);
        $this->SetFont(PDF_FONT_NAME_MAIN, 'I', 8);
        $this->Cell(0, 10, 'Página: '.$this->getAliasNumPage().' de '.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
    
}

?>


