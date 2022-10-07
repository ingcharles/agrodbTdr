<?php
/**
 * Controlador para reportes de pdf
 * @date    2019-07-20
 *
 */

namespace Agrodb\HistoriasClinicas\Modelos;

class ReportesPdfLogicaNegocio
    {
        
        private $modeloReportesPdf = null;
        
        /**
         * Constructor
         *
         * @retorna void
         */
        public function __construct()
        {
            $this->modeloReportesPdf = new ReportesPdfModelo();
        }
        
        public function generarCertificado($arrayDatos)
        {
            ob_start();
            // ************************************************** INICIO ***********************************************************
            $margen_superior = 28;
            $margen_inferior = 30;
            $margen_izquierdo = 10;
            $margen_derecho = 10;
            
            
            $doc = new ReportesPdfModelo('P', 'mm', 'A4', true, 'UTF-8');
            $tipoLetra = 'times';
            $width = $doc->getPageWidth() -$margen_izquierdo- $margen_derecho;
            $doc->SetLineWidth(0.1);
            $doc->setCellHeightRatio(1.5);
            $doc->SetMargins($margen_izquierdo, $margen_superior, $margen_derecho);
            $doc->SetAutoPageBreak(TRUE, $margen_inferior);
            $doc->SetFont($tipoLetra, '', 9);
            $doc->AddPage();
            
            $doc->SetTextColor();
            $doc->SetFont($tipoLetra, 'B', 13);
            $y = $doc->GetY();
            $doc->writeHTMLCell(0, 0, $margen_izquierdo, $y, '<u>' . strtoupper($arrayDatos['titulo']) . '</u>', '', 1, 0, true, 'C', true);
            $doc->SetFont($tipoLetra, '', 12);
            
            $posiX = $doc->getPageWidth() - $margen_derecho;
            $doc->textoHorizontal('C','B','Fecha', $posiX-60, $y+10, $tipoLetra, 12, 30, 0,0,30,1);
            $doc->textoHorizontal('C','',$arrayDatos['fecha'], $posiX-30, $y+10, $tipoLetra, 12, 30, 0,0,30);
            $y = $doc->GetY();
            $doc->textoHorizontal('L','B','Nombre del paciente:', $margen_izquierdo, $y, $tipoLetra, 12, 50, 0,0,30,1);
            $doc->textoHorizontal('L','',$arrayDatos['funcionario'], $margen_izquierdo+50, $y, $tipoLetra, 12, $width-50, 0,0,50);
            $y = $doc->GetY();
            $doc->textoHorizontal('L','B','Edad (años):', $margen_izquierdo, $y, $tipoLetra, 12, 30, 0,0,30,1);
            $doc->textoHorizontal('L','',$arrayDatos['edad'], $margen_izquierdo+30, $y, $tipoLetra, 12, 20, 0,0,30);
            $doc->textoHorizontal('R','B','Género:', $margen_izquierdo+50, $y, $tipoLetra, 12, 30, 0,0,30,1);
            $doc->textoHorizontal('L','',$arrayDatos['genero'], $margen_izquierdo+80, $y, $tipoLetra, 12, 40, 0,0,30);
            $doc->textoHorizontal('R','B','Identificación:', $margen_izquierdo+120, $y, $tipoLetra, 12, 30, 0,0,30,1);
            $doc->textoHorizontal('L','',$arrayDatos['identificador'], $margen_izquierdo+150, $y, $tipoLetra, 12, $doc->getPageWidth()-($margen_derecho+$margen_izquierdo+150), 0,0,30);
            $y = $doc->GetY();
            $doc->textoHorizontal('L','B','Cargo que ocupa:', $margen_izquierdo, $y, $tipoLetra, 12, 50, 0,0,30,1);
            $doc->textoHorizontal('L','',$arrayDatos['nombre_puesto'], $margen_izquierdo+50, $y, $tipoLetra, 12, $width-50, 0,0,72);
            $y = $doc->GetY();
            $doc->textoHorizontal('L','B','Lateralidad:', $margen_izquierdo, $y, $tipoLetra, 12, 30, 0,0,30,1);
            $doc->textoHorizontal('L','',$arrayDatos['lateralidad'], $margen_izquierdo+30, $y, $tipoLetra, 12, $width-30, 0,0,30);
   //*************************************************************************************************************************
            if($arrayDatos['descripcion_certificado'] != 'Informe Médico'){
                if($arrayDatos['descripcion_certificado'] == 'Certificado de Aptitud de Egreso'){
                $y = $doc->GetY();
                $doc->textoHorizontal('L','B','Fecha Ingreso:', $margen_izquierdo, $y, $tipoLetra, 12, 30, 0,0,30,1);
                $doc->textoHorizontal('L','',$arrayDatos['fecha_inicial'], $margen_izquierdo+30, $y, $tipoLetra, 12, 70, 0,0,30);
                $doc->textoHorizontal('R','B','Fecha Salida: ', $margen_izquierdo+100, $y, $tipoLetra, 12, 30, 0,0,30,1);
                $doc->textoHorizontal('L','',$arrayDatos['fecha_salida'], $margen_izquierdo+130, $y, $tipoLetra, 12,$doc->getPageWidth()-($margen_derecho+$margen_izquierdo+130), 0,0,30);
                }
                $y = $doc->GetY();
                $doc->textoHorizontal('C','B','EPICRISIS', $margen_izquierdo, $y, $tipoLetra, 12, $width, 0,0,30,1);
                $y = $baseY= $doc->GetY();
               
                $anchoTb =60;
                $xIni = $doc->getPageWidth() - $anchoTb*3;
                $datosCabecera = array('Enfermedad general','Estado','Observaciones');
                $doc->crearFila('C','B',$datosCabecera, $xIni/2, $y+5, $tipoLetra, 10, $anchoTb, 0,0,30,4);
                $y = $doc->GetY();
                $tinta=2;
                foreach ($arrayDatos['epicrisis'] as $value) {
                    $doc->crearFila('L','',$value, $xIni/2, $y, $tipoLetra, 9, $anchoTb, 0,0,30,$tinta,1);
                    $y = $doc->GetY();
                    
                    if($y > 207){
                        $doc->AddPage();
                    }
                    if($tinta == 2){
                        $tinta = 3;
                    }else{
                        $tinta = 2;
                    }
                }
                $doc->ln();
                $y = $doc->GetY();
                $doc->crearTabla($margen_izquierdo, $baseY, $width, $y-$baseY);
                
                $y = $doc->GetY();
                $doc->textoHorizontal('C','B','OBSERVACIONES', $margen_izquierdo, $y, $tipoLetra, 12, $width, 0,0,30,1);
                $y= $baseY=$doc->GetY();
                $doc->crearObservaciones('L', 'B', 'TIPO DE RESTRICCIONES O LIMITACIONES:', $margen_izquierdo+2, $y+5, $tipoLetra, 10, $width-2);
                $doc->crearObservaciones('J', '', $arrayDatos['tipo_restriccion_limitacion'], $margen_izquierdo+2, $y+10, $tipoLetra, 9, $width-2);
                $y= $doc->GetY();
                $doc->crearObservaciones('L', 'B', 'RECOMENDACIONES:', $margen_izquierdo+2, $y+5, $tipoLetra, 10, $width-2);
                $doc->crearObservaciones('J', '', $arrayDatos['recomendacion'], $margen_izquierdo+2, $y+10, $tipoLetra, 9, $width-2);
                $y= $doc->GetY();
                $doc->crearObservaciones('L', 'B', 'OBSERVACIONES:', $margen_izquierdo+2, $y+5, $tipoLetra, 10, $width-2);
                $doc->crearObservaciones('J', '', $arrayDatos['observacion'], $margen_izquierdo+2, $y+10, $tipoLetra, 9, $width-2);
                $doc->ln();
                $y= $doc->GetY();
                if($y <= 207){
                    $y=190;
                }
                $doc->crearApto('B', $margen_izquierdo, $y, $tipoLetra, 12,$width,$arrayDatos['descripcion_concepto']);
                $doc->ln();
                $y= $doc->GetY();
                if($y <= 207){
                    $y=207;
                }
                $doc->crearTabla($margen_izquierdo, $baseY, $width, $y-$baseY);
            }else{
                $y = $baseY= $doc->GetY();
                $doc->textoHorizontal('C','B','ANÁLISIS', $margen_izquierdo, $y, $tipoLetra, 12, $width, 0,0,30,1);
                $doc->crearObservaciones('J', '', rtrim($arrayDatos['analisis']), $margen_izquierdo+2, $y+10, $tipoLetra, 10, $width-2);
                $doc->ln();
                $y= $doc->GetY();
                if($y < ($doc->getPageHeight()/2-40)){
                    $y = $doc->getPageHeight()/2-40;
                }
                // $doc->crearTabla($margen_izquierdo, $baseY, $width, $y-$baseY);
                // $y = $baseY= $doc->GetY();
                $doc->textoHorizontal('C','B','RECOMENDACIONES', $margen_izquierdo, $y, $tipoLetra, 12, $width, 0,0,30,1);
                $doc->crearObservaciones('J', '', rtrim($arrayDatos['recomendaciones']), $margen_izquierdo+2, $y+10, $tipoLetra, 10, $width-2);
                $doc->ln();
                $y= $doc->GetY();
                if($y <= 207){
                    $y=207;
                }
                $doc->crearTabla($margen_izquierdo, $baseY, $width, $y-$baseY);
                
            }
            //********************************************************************************************************************************
            $y = $doc->GetY();
            if($y <= 207){
            $y=$doc->getPageHeight()-28*2-34;
            $doc->firma($margen_izquierdo, $y, $width/2, 28, $arrayDatos, $tipoLetra );
            }else{
                $doc->AddPage();
                $y = $doc->GetY()+5;
                $doc->firma($margen_izquierdo, $y, $width/2, 28, $arrayDatos, $tipoLetra );
                
            }
            // ******************************* FIN DE LA EDICION ****************************************************************************************
            $doc->Output(HIST_CLI_URL_TCPDF .$arrayDatos['rutaArchivo'], 'F');
            ob_end_clean();
            return true;
        }
        //**************************generar receta***************************************************************
        public function generarRecetaMedica($arrayDatos)
        {
            ob_start();
            // ************************************************** INICIO ***********************************************************
            $margen_superior = 28;
            $margen_inferior = 30;
            $margen_izquierdo = 10;
            $margen_derecho = 10;
            
            $doc = new ReportesPdfRecetaModelo('L', 'mm', 'A4', true, 'UTF-8');
            $tipoLetra = 'times';
            $doc->SetLineWidth(0.1);
            $doc->setCellHeightRatio(1.5);
            $doc->SetMargins($margen_izquierdo, $margen_superior, $margen_derecho);
            $doc->SetAutoPageBreak(TRUE, $margen_inferior);
            $doc->SetFont($tipoLetra, '', 9);
            $doc->AddPage();
            
            $doc->SetTextColor();
            $doc->SetFont($tipoLetra, '', 12);
            $y = $doc->GetY();
            $xMedio = $margen_izquierdo+($doc->getPageWidth()/2)-66;
            $xLimite = $doc->getPageWidth()-$margen_derecho-66;
            $doc->writeHTMLCell(50, 0, $xMedio, $y+10,  $doc->fecha($arrayDatos['ciudad'], 1, $arrayDatos['fecha']) , '', 1, 0, true, 'R', true);
            $doc->writeHTMLCell(50, 0, $xLimite, $y+10, $doc->fecha($arrayDatos['ciudad'], 1, $arrayDatos['fecha']) , '', 1, 0, true, 'R', true);
            $doc->writeHTMLCell(150, 0, $xMedio-60, $y+20, '<strong>Nombre: </strong>'.$arrayDatos['paciente'] , '', 1, 0, true, 'L', true);
            $doc->writeHTMLCell(150, 0, $xLimite-60, $y+20, '<strong>Nombre: </strong>' .$arrayDatos['paciente'], '', 1, 0, true, 'L', true);
            $doc->writeHTMLCell(100, 0, $xMedio-60, $y+30, '<strong>Cédula: </strong>'.$arrayDatos['cedula'] , '', 1, 0, true, 'L', true);
            $doc->writeHTMLCell(100, 0, $xMedio-60, $y+40, 'Rp.', '', 1, 0, true, 'L', true);
            $doc->writeHTMLCell(100, 0, $xLimite-60, $y+35, 'Ind.', '', 1, 0, true, 'L', true);
            
            $y = $doc->GetY();
            $txtInfo = array(
                'medicamento' => 'Medicamento',
                'forma' => 'Forma farmacéutica',
                'concentracion' => 'Concentración'
                
            );
            $doc->SetFont($tipoLetra, 'B', 9);
            $doc->crearTablaConsulta($xMedio-60, $y+10, 35, $txtInfo,1);
            $y =$y2 =  $doc->GetY();
            $doc->SetFont($tipoLetra, '', 9);
            foreach ($arrayDatos['medicamento'] as $value) {
                $txtInfo = array(
                    'medicamento' => $value[0],
                    'forma' => $value[1],
                    'concentracion' => $value[2]
                );
                $doc->crearTablaConsulta($xMedio-60, $y, 35, $txtInfo);
                $y = $doc->GetY();
            }
            
            $y=$y2-5;
            foreach ($arrayDatos['medicamento'] as $value) {
                $txtInfo = array(
                    'medicamento' => $value[0],
                    'indicaciones' => $value[3]
                );
                $doc->crearTablaIndicacion($xMedio+70, $y, 90, $txtInfo);
                $y = $doc->GetY();
            }
            
            $txtFirma = array(
                'indetificador_medico' => $arrayDatos['cedula_medico'],
                'nombre_medico' => $arrayDatos['nombre_medico'],
                'cargo_medico' => 'Médico Ocupacional AGROCALIDAD'
            );
            $y = $doc->getPageHeight()-60;
            $doc->firma($margen_izquierdo, $y, 150, $txtFirma, $tipoLetra);
            $doc->firma($xMedio+50, $y, 150, $txtFirma, $tipoLetra);
            // ******************************* FIN DE LA EDICION ******************************
            $doc->Output(HIST_CLI_URL_TCPDF .$arrayDatos['rutaArchivo'], 'F');
            ob_end_clean();
            return true;
        }
        
        //**************************generar certificado medico***********************************
        public function generarCertificadoMedico($arrayDatos)
        {
            ob_start();
            // ************************************************** INICIO ************************
            $margen_superior = 28;
            $margen_inferior = 30;
            $margen_izquierdo = 22;
            $margen_derecho = 22;
            
            
            $doc = new ReportesPdfCertificadoModelo('P', 'mm', 'A4', true, 'UTF-8');
            $tipoLetra = 'times';
            $doc->SetLineWidth(0.1);
            $doc->setCellHeightRatio(1.5);
            $doc->SetMargins($margen_izquierdo, $margen_superior, $margen_derecho);
            $doc->SetAutoPageBreak(TRUE, $margen_inferior);
            $doc->SetFont($tipoLetra, '', 9);
            $doc->AddPage();
            $texto = 'Certifico que $funcionario, portador del número de cédula $identificador, acude a consulta médica con diagnóstico de $enfermedad -- $codigo, por lo cual requiere reposo médico de $dias (días), contados a partir del $fechaDesde hasta $fechaHasta.


<br><br><br>Es todo lo que puedo testificar en honor a la verdad. ';
            
            $html = $texto;
            $html = str_replace('$funcionario', $arrayDatos['paciente'], $html);
            $html = str_replace('$identificador', $arrayDatos['cedula'], $html);
            $html = str_replace('$enfermedad', $arrayDatos['descripcion_cie'], $html);
            $html = str_replace('$codigo', $arrayDatos['cie'], $html);
            $html = str_replace('$dias', $arrayDatos['dias'], $html);
            $html = str_replace('$fechaDesde', $arrayDatos['fecha_desde'], $html);
            $html = str_replace('$fechaHasta', $arrayDatos['fecha_hasta'], $html);
            
            $doc->SetTextColor();
            $doc->SetFont($tipoLetra, '', 12);
            $y = $doc->GetY();
            $xLimite = $doc->getPageWidth()-$margen_derecho-50;
            $ancho = $doc->getPageWidth()-$margen_derecho-$margen_izquierdo;
            $doc->writeHTMLCell(50, 0, $xLimite, $y+10, $doc->fecha($arrayDatos['ciudad'], 1, $arrayDatos['fecha']) , '', 1, 0, true, 'R', true);
            $doc->writeHTMLCell(100, 0, $doc->getPageWidth()/2-50, $y+32, '<strong>CERTIFICADO MÉDICO</strong>' , '', 1, 0, true, 'C', true);
            $doc->writeHTMLCell($ancho, 0, $margen_izquierdo, $y+50, $html, '', 1, 0, true, 'J', true);
            
            $txtFirma = array(
                'indetificador_medico' => $arrayDatos['cedula_medico'],
                'nombre_medico' => $arrayDatos['nombre_medico'],
                'cargo_medico' => 'Médico Ocupacional AGROCALIDAD'
            );
            $y = $doc->getPageHeight()-80;
            $doc->firma($doc->getPageWidth()/2-75, $y, 150, $txtFirma, $tipoLetra);
            // ******************************* FIN DE LA EDICION ********************************************************************************
            $doc->Output(HIST_CLI_URL_TCPDF .$arrayDatos['rutaArchivo'], 'F');
            ob_end_clean();
            return true;
        }
        
        //************************************************************************************************************
        
}