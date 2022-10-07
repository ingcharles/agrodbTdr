<?php
/**
 * Controlador CertificadoFitosanitario
 *
 * Este archivo controla la lógica del negocio del modelo:  CertificadoFitosanitarioModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2020-08-04
 * @uses    CertificadoFitosanitarioDatosPublicosControlador
 * @package CertificadoFitosanitario
 * @subpackage Controladores
 */
namespace Agrodb\CertificadoFitosanitario\Controladores;

use Agrodb\CertificadoFitosanitario\Modelos\CertificadoFitosanitarioLogicaNegocio;

class CertificadoFitosanitarioDatosPublicosControlador extends BaseControlador
{
        
    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct(false);
        $this->lNegocioCertificadoFitosanitario = new CertificadoFitosanitarioLogicaNegocio();
        set_exception_handler(array($this, 'manejadorExcepciones'));
    }
    
    /**
     *Método para abrir la ventana de búsqueda de certificados fitosanitarios
     */
    public function index()
    {
        require APP . 'CertificadoFitosanitario/vistas/formularioConsultaCertificadoFitosanitarioVista.php';
    }
    
    /**
     *Método para abrir la ventana de búsqueda de certificados fitosanitarios
     */
    public function datosCosultaCertificadoFitosanitario()
    {
        
        $codigoCertificado = $_POST['codigo_certificado'];
        $estadoCertificado = "Aprobado";
        
        $arrayParametros = array('codigo_certificado' => $codigoCertificado,
            'estado_certificado' => $estadoCertificado
        );
        
        $tablaDatosCertificado = "";
        
        $tablaDatosCertificado = '<br><h1>LISTADO</h1><table style="border-collapse: initial;">
				<thead>
  					<tr>
						<th>NÚMERO DE CERTIFICADO / CERTIFICATE NUMBER</th>
						<th>FECHA DE EMISIÓN / DATE OF ISSUE</th>
				    	<th>NOMBRE DE LA EMPRESA / COMPANY NAME</th>
                        <th>PRODUCTO / PRODUCT</th>
                        <th>PAÍS DE DESTINO / DESTINATION COUNTRY</th>
                        <th>CERTIFICADO / CERTIFICATE</th>
                        <th>ANEXO / ANEX</th>
 					</tr>
 				</thead><tbody>';
        
        $datosCertificado = $this->lNegocioCertificadoFitosanitario->buscarCertificadoFitosanitarioPorCodigoCertificadoPorEstado($arrayParametros);
        
        foreach ($datosCertificado as $item){

            list($anexo, $certificado) = explode(", ", $item->ruta_adjunto);
            
            $tablaDatosCertificado .= '<tr>
                                            <td>' . $item->codigo_certificado . '</td>
                                            <td>' . $item->fecha_emision . '</td>
                                            <td>' . $item->nombre_operador . '</td>
                                            <td>' . $item->nombre_producto . '</td>
                                            <td>' . $item->nombre_pais_destino . '</td>
                                            <td><a title="Descargar certificado" href="../../../' . $certificado . '" target="_blank"><img src="../../general/img/logoPdf.png" width="30"/></a></td>
                                            <td><a title="Descargar anexo" href="../../../' . $anexo . '" target="_blank"><img src="../../general/img/logoPdf.png" width="30"/></a></td>';
            $tablaDatosCertificado .= '<tr>';
        }
        
        $tablaDatosCertificado .= '</tbody></table>';
        
        echo $tablaDatosCertificado;
        
        exit();
        
    }
    
}