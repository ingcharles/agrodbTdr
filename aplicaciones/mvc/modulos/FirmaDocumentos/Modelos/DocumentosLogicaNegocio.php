<?php
/**
 * Lógica del negocio de DocumentosModelo
 *
 * Este archivo se complementa con el archivo DocumentosControlador.
 *
 * @author AGROCALIDAD
 * @date    2022-01-14
 * @uses DocumentosLogicaNegocio
 * @package FirmaDocumentos
 * @subpackage Modelos
 */
namespace Agrodb\FirmaDocumentos\Modelos;

use Agrodb\FirmaDocumentos\Modelos\IModelo;
use TCPDI;
use Agrodb\Core\Comun;
use Agrodb\Core\Constantes;

class DocumentosLogicaNegocio implements IModelo{

	private $modeloDocumentos = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloDocumentos = new DocumentosModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		
		$tablaModelo = new DocumentosModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdDocumento() != null && $tablaModelo->getIdDocumento() > 0){
			return $this->modeloDocumentos->actualizar($datosBd, $tablaModelo->getIdDocumento());
		}else{
			unset($datosBd["id_documento"]);
			$idDocumento = $this->modeloDocumentos->guardar($datosBd);
		}
		
		$resultado = array(
			'estado' => 'EXITO',
			'mensaje' => 'Datos almacenados con exito'
		);
		
		if($datos['proceso_firmado'] == 'SI'){
			$documentoFirma = $this->buscarDatosFirmante($idDocumento);
			
			$datos['nombre_firmante'] = $documentoFirma->current()->nombre_firmante;
			$datos['localizacion'] = $documentoFirma->current()->localizacion;
			$datos['telefono'] = $documentoFirma->current()->telefono;
			$datos['cargo'] = $documentoFirma->current()->cargo;
			$datos['id_documento'] = $idDocumento;
						
			$resultado = $this->firmarDocumento($datos);
		}
		
		return $resultado;
	}

	/**
	 * Borra el registro actual
	 *
	 * @param
	 *        	string Where|array $where
	 * @return int
	 */
	public function borrar($id){
		$this->modeloDocumentos->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return DocumentosModelo
	 */
	public function buscar($id){
		return $this->modeloDocumentos->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloDocumentos->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloDocumentos->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarDocumentos(){
		$consulta = "SELECT * FROM " . $this->modeloDocumentos->getEsquema() . ". documentos";
		return $this->modeloDocumentos->ejecutarSqlNativo($consulta);
	}
	
	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarDatosFirmante($idDocumento){
	    $consulta = "SELECT
						d.nombre_firmante
						, d.localizacion
						, d.telefono
						, rc.cargo
					FROM
						g_firma_documentos.documentos d
					INNER JOIN g_catalogos.responsables_certificados rc ON rc.identificador = d.identificador
						WHERE
					id_documento = " . $idDocumento . ";";
	    return $this->modeloDocumentos->ejecutarSqlNativo($consulta);
	}
	
	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarDocumentosPorAtender($parametros){
	    
	    $estado = $parametros['estado'];
	    
	    $consulta = "SELECT
						d.id_documento
						, d.archivo_entrada
						, d.archivo_salida
						, d.identificador
						, d.nombre_firmante
						, d.localizacion
						, d.razon_documento
						, d.telefono
						, rc.cargo
					FROM
						g_firma_documentos.documentos d
					LEFT JOIN g_catalogos.responsables_certificados rc ON rc.identificador = d.identificador
						WHERE
					d.estado = '" . $estado . "';";
	    return $this->modeloDocumentos->ejecutarSqlNativo($consulta);
	}
	
	/**
	 *Obtener registros a ser firmados
	 *
	 * @return DocumentosModelo
	 */
	public function buscarDocumentosPorFirmar(){
		
		echo Constantes::IN_MSG .'Obtención de registros de documentos por atender para el proceso de firma\n';
		
		$parametros = array(
			'estado' => 'Por atender'
		);
		
		$registroPorFirmar = $this->buscarDocumentosPorAtender($parametros);
		
		foreach ($registroPorFirmar as $documento) {
			
			echo Constantes::IN_MSG .'Firma de registro con id de documento '.$documento->id_documento.'</b>\n';
			
			$datos = array(
				'id_documento' => $documento->id_documento,
				'archivo_entrada' => $documento->archivo_entrada,
				'archivo_salida' => $documento->archivo_salida,
				'identificador' => $documento->identificador,
				'nombre_firmante' => $documento->nombre_firmante,
				'localizacion' => $documento->localizacion,
				'razon_documento' => $documento->razon_documento,
				'telefono' => $documento->telefono,
			    'cargo' => $documento->cargo
			);
			
			echo Constantes::IN_MSG .'Inicio de proceso de firmado '.$documento->id_documento.'</b>\n';
			
			$this->firmarDocumento($datos);
			
			echo Constantes::IN_MSG .'Fin de proceso de firmado '.$documento->id_documento.'</b>\n';
		}
	}
	
	/**
	 * Proceso de firamdo dedocumentos.
	 *
	 * @return DocumentosModelo
	 */
	public function firmarDocumento($datos){
		
		$lNegocioIdentificador = new FirmantesLogicaNegocio();
		$comun = new Comun();
		
		$resultado = array(
			'estado' => 'EXITO',
			'mensaje' => 'Documento firmado con exito.'
		);
		
		$fechaFirma = date("Y-m-d H:i:s");
		
		$style = array(
			'border' => 0,
			'vpadding' => 'auto',
			'hpadding' => 'auto',
			'fgcolor' => array(
				0,
				0,
				0
			),
			'bgcolor' => false,
			'module_width' => 1,
			'module_height' => 1
		);
				
		if (file_exists('file://'.$datos['archivo_entrada'])) {
		    
		    $pdf = new PDF();
		    
		    $pageCount = $pdf->setSourceFile($datos['archivo_entrada']);
		    
		    for ($i = 1; $i <= $pageCount; $i++) {
		        $tplIdx = $pdf->importPage($i);
		        $size = $pdf->getTemplatesize($tplIdx);
		        $orientation = ($size['w'] > $size['h']) ? 'L' : 'P';
		        $pdf->AddPage($orientation);
		        $pdf->useTemplate($tplIdx);
		    }
		    
		    $datosFirmante = $lNegocioIdentificador->buscar($datos['identificador']);
		    
		    if($datosFirmante->getFechaCaducidadCertificado() > $fechaFirma){
		        
		        $inforamcionFirmate = array(
		            'Name' => $datos['nombre_firmante'],
		            'Location' => $datos['localizacion'],
		            'Reason' => $datos['razon_documento'],
		            'ContactInfo' =>  $datos['telefono']
		        );
		        
		        $claveFirmante = $comun->desencriptarClave($datos['identificador'], $datosFirmante->getClave());
		        
		        $pdf->setSignature('file://'.$datosFirmante->getRutaArchivo(), 'file://'.$datosFirmante->getRutaArchivo(),$claveFirmante, '', 1, $inforamcionFirmate,'A');
		        
		        $datosCodigoQR = 'FIRMADO POR: '.$datos['nombre_firmante'].'
    			RAZON:'.$datos['razon_documento'].'
    			LOCALIZACION: '.$datos['localizacion'].'
    			FECHA FIRMADO:' .$fechaFirma ;
		        
		        $razonDocumento = $datos['razon_documento'];
		        
		        if($orientation == 'P'){
		            
		            switch ($razonDocumento){
		                case 'Certificación fitosanitaria de exportación.':
		                    $margen_izquierdo = 104;
		                    $margenInferior = 263;
		                    break;
		                    
		                default:
		                    
		                    $margen_izquierdo = 74;
		                    $margenInferior = 251;
		            }
		            
		        }else{
		            
		            switch ($razonDocumento){
		                case 'Certificación fitosanitaria de exportación.':
		                    $margen_izquierdo = 136;
		                    $margenInferior = 176;
		                    break;
		                    
		                default:
		                    
		                    $margen_izquierdo = 118;
		                    $margenInferior = 168;
		            }
		            
		        }
		        
		        $pdf->write2DBarcode($datosCodigoQR, 'QRCODE,Q', $margen_izquierdo +1, $margenInferior -10, 22, 22, $style, 'N');
		        $pdf->SetFont('dejavusans', '', 7);
		        $pdf->writeHTMLCell(0, '', $margen_izquierdo + 23, $margenInferior - 8, 'Firmado electrónicamente por:', 0, 0, 0, true, 'L', true);
		        $pdf->Ln();
		        $pdf->SetFont('dejavusans', 'B', 6);
		        
		        $pdf->writeHTMLCell(45, '', $margen_izquierdo + 23, '', $datos['nombre_firmante'], 0, 0, 0, true, 'L', true);
		        $pdf->setSignatureAppearance($margen_izquierdo + 1, $margenInferior - 10, 22, 22);
		        
		        $pdf->SetFont('dejavusans', 'B', 8);
		        $pdf->Ln(20);
		        $pdf->writeHTMLCell(0, 0, '', '', strtoupper($datos['cargo']), 0, 1, 0, true, 'C', true);
		        
		        $pdf->Output($datos['archivo_salida'], 'F');
		        
		        $datos = array(
		            'estado' => 'Atendida',
		            'fecha_firmado' => $fechaFirma,
		            'id_documento' => $datos['id_documento']
		        );
		        
		        $this->guardar($datos);
		        
		    }else{
		        
		        $datos = array(
		            'estado' => 'PfxCaducado',
		            'id_documento' => $datos['id_documento']
		        );
		        
		        $this->guardar($datos);
		        
		        $resultado = array(
		            'estado' => 'FALLO',
		            'mensaje' => 'Pfx de funcionario caducado.'
		        );
		        
		    }
		    
		} else {
		    
		    $datos = array(
		        'estado' => 'W',
		        'fecha_firmado' => $fechaFirma,
		        'id_documento' => $datos['id_documento']
		    );
		    
		    $this->guardar($datos);
		    
		}
			
		return $resultado;
	}
}

class PDF extends TCPDI
{
	
	// Page header
	public function Header()
	{
		$bMargin = $this->getBreakMargin();
		$auto_page_break = $this->AutoPageBreak;
		$this->SetAutoPageBreak(false, 0);
		$this->SetAutoPageBreak($auto_page_break, $bMargin);
	}
}
