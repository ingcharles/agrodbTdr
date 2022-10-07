<?php
/**
 * Controlador PuertosDestino
 *
 * Este archivo controla la lógica del negocio del modelo:  PuertosDestinoModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2020-08-04
 * @uses    PuertosDestinoControlador
 * @package CertificadoFitosanitario
 * @subpackage Controladores
 */
namespace Agrodb\CertificadoFitosanitario\Controladores;

use Agrodb\CertificadoFitosanitario\Modelos\PuertosDestinoLogicaNegocio;
use Agrodb\CertificadoFitosanitario\Modelos\PuertosDestinoModelo;
use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class PuertosDestinoControlador extends BaseControlador
{

    private $lNegocioPuertosDestino = null;
    private $modeloPuertosDestino = null;
    private $accion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioPuertosDestino = new PuertosDestinoLogicaNegocio();
        $this->modeloPuertosDestino = new PuertosDestinoModelo();
        set_exception_handler(array(
            $this,
            'manejadorExcepciones'
        ));
    }

    /**
     * Método de inicio del controlador
     */
    public function index()
    {
        $modeloPuertosDestino = $this->lNegocioPuertosDestino->buscarPuertosDestino();
        $this->tablaHtmlPuertosDestino($modeloPuertosDestino);
        require APP . 'CertificadoFitosanitario/vistas/listaPuertosDestinoVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo PuertosDestino";
        require APP . 'CertificadoFitosanitario/vistas/formularioPuertosDestinoVista.php';
    }

    /**
     * Método para registrar en la base de datos -PuertosDestino
     */
    public function guardar()
    {
        $idCertificadoFitosanitario = $_POST['id_certificado_fitosanitario_puertos_destino'];
        $nombrePaisDestino = $_POST['nombre_pais_destino'];
        $idPuertoPaisDestino = $_POST['id_puerto_pais_destino'];
        $nombrePuertoPaisDestino = $_POST['nombre_puerto_pais_destino'];
        
        $validacion = "Fallo";
        $resultado = "El puerto de destino ya ha sido registrado.";
        
        $arrayParametros = array(
            'id_certificado_fitosanitario' => $idCertificadoFitosanitario,
            'id_puerto_pais_destino' => $idPuertoPaisDestino,
            'nombre_puerto_pais_destino' => $nombrePuertoPaisDestino
        );

        $verificarPuertoPaisDestino = $this->lNegocioPuertosDestino->obtenerPuertosPaisDestino($arrayParametros);

        if (! isset($verificarPuertoPaisDestino->current()->id_puerto_destino)) {

            $validacion = "Exito";
            $resultado = "";

            $datosPuertoPaisDestino = $this->lNegocioPuertosDestino->guardar($arrayParametros);

            $filaPuertoPaisDestino = $this->generarFilaPuertoPaisDestino($datosPuertoPaisDestino, $nombrePaisDestino);

            echo json_encode(array(
                'validacion' => $validacion,
                'resultado' => $resultado,
                'filaPuertoPaisDestino' => $filaPuertoPaisDestino
            ));
        } else {
            echo json_encode(array(
                'validacion' => $validacion,
                'resultado' => $resultado
            ));
        }
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: PuertosDestino
     */
    public function editar()
    {
        $this->accion = "Editar PuertosDestino";
        $this->modeloPuertosDestino = $this->lNegocioPuertosDestino->buscar($_POST["id"]);
        require APP . 'CertificadoFitosanitario/vistas/formularioPuertosDestinoVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - PuertosDestino
     */
    public function borrar()
    {
        $this->lNegocioPuertosDestino->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - PuertosDestino
     */
    public function tablaHtmlPuertosDestino($tabla)
    {
        {
            $contador = 0;
            foreach ($tabla as $fila) {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila['id_puerto_destino'] . '"
                class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'CertificadoFitosanitario\puertosdestino"
                data-opcion="editar" ondragstart="drag(event)" draggable="true"
                data-destino="detalleItem">
                <td>' . ++ $contador . '</td>
                <td style="white - space:nowrap; "><b>' . $fila['id_puerto_destino'] . '</b></td>
	 
                <td>' . $fila['id_certificado_fitosanitario'] . '</td>
                <td>' . $fila['id_puerto_pais_destino'] . '</td>
			
                <td>' . $fila['nombre_puerto_pais_destino'] . '</td>
                </tr>'
                );
            }
        }
    }

    /**
     * Método para listar puertos de destino del certificado fitosanitario
     */
    public function generarFilaPuertoPaisDestino($idPuertoPaisDestino, $nombrePaisDestino)
    {
        $filaPuertoPaisDestino = $this->lNegocioPuertosDestino->buscar($idPuertoPaisDestino);

        $idPuertoPaisDestino = $filaPuertoPaisDestino->getIdPuertoDestino();
        $nombrePuertoPaisDestino = $filaPuertoPaisDestino->getNombrePuertoPaisDestino();

        $this->listaDetalles = '
                        <tr id="' . $idPuertoPaisDestino . '">
                            <td>' . ($nombrePaisDestino != '' ? $nombrePaisDestino : '') . '</td>
                            <td>' . ($nombrePuertoPaisDestino != '' ? $nombrePuertoPaisDestino : '') . '</td>
                            <td class="borrar"><button type="button" name="eliminar" class="icono" onclick="fn_eliminarDetallePuertosDestino(' . $idPuertoPaisDestino . '); return false;"/></td>
                        </tr>';

        return $this->listaDetalles;
    }

}
