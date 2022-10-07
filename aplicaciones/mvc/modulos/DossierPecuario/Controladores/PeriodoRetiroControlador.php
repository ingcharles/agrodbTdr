<?php
/**
 * Controlador PeriodoRetiro
 *
 * Este archivo controla la lógica del negocio del modelo:  PeriodoRetiroModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2021-07-21
 * @uses    PeriodoRetiroControlador
 * @package DossierPecuario
 * @subpackage Controladores
 */
namespace Agrodb\DossierPecuario\Controladores;

use Agrodb\DossierPecuario\Modelos\PeriodoRetiroLogicaNegocio;
use Agrodb\DossierPecuario\Modelos\PeriodoRetiroModelo;

use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class PeriodoRetiroControlador extends BaseControlador
{

    private $lNegocioPeriodoRetiro = null;
    private $modeloPeriodoRetiro = null;

    private $accion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioPeriodoRetiro = new PeriodoRetiroLogicaNegocio();
        $this->modeloPeriodoRetiro = new PeriodoRetiroModelo();
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
        $modeloPeriodoRetiro = $this->lNegocioPeriodoRetiro->buscarPeriodoRetiro();
        $this->tablaHtmlPeriodoRetiro($modeloPeriodoRetiro);
        require APP . 'DossierPecuario/vistas/listaPeriodoRetiroVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo PeriodoRetiro";
        require APP . 'DossierPecuario/vistas/formularioPeriodoRetiroVista.php';
    }

    /**
     * Método para registrar en la base de datos -PeriodoRetiro
     */
    public function guardar()
    {
		$this->lNegocioPeriodoRetiro->guardar($_POST);
		Mensajes::exito(Constantes::GUARDADO_CON_EXITO);

    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: PeriodoRetiro
     */
    public function editar()
    {
        $this->accion = "Editar PeriodoRetiro";
        $this->modeloPeriodoRetiro = $this->lNegocioPeriodoRetiro->buscar($_POST["id"]);
        require APP . 'DossierPecuario/vistas/formularioPeriodoRetiroVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - PeriodoRetiro
     */
    public function borrar()
    {
        $this->lNegocioPeriodoRetiro->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - PeriodoRetiro
     */
    public function tablaHtmlPeriodoRetiro($tabla)
    {
        $contador = 0;
        foreach ($tabla as $fila) {
            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_periodo_retiro'] . '"
            		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'DossierPecuario\periodoretiro"
            		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
            		  data-destino="detalleItem">
            		<td>' . ++ $contador . '</td>
            		<td style="white - space:nowrap; "><b>' . $fila['id_periodo_retiro'] . '</b></td>
                    <td>' . $fila['id_solicitud'] . '</td>
                    <td>' . $fila['fecha_solicitud'] . '</td>
                    <td>' . $fila['id_especie'] . '</td>
                </tr>'
            );
        }
    }
    
    /**
     * Método para listar los periodos de retiro
     */
    public function construirDetallePeriodoRetiro()
    {
        $idSolicitud = $_POST['idSolicitud'];
        $fase = $_POST['fase'];
        
        $listaDetalles = $this->lNegocioPeriodoRetiro->obtenerInformacionPeriodoRetiro($idSolicitud);
        
        $i=1;
        
        $this->listaDetalles = '<table>';
        
        foreach ($listaDetalles as $fila) {
            
            $this->listaDetalles .='
                        <tr>
                            <td>' . $i++. '</td>
                            <td>' . ($fila['especie'] != '' ? $fila['especie'] : '').'</td>
                            <td>' . ($fila['nombre_especie'] != '' ? $fila['nombre_especie'] : '').'</td>
                            <td>' . ($fila['producto_consumo'] != '' ? $fila['producto_consumo'] : '').'</td>
                            <td>' . ($fila['tiempo_retiro'] != '' ? $fila['tiempo_retiro'] . ' ' .($fila['nombre_unidad_tiempo'] != '' ? $fila['nombre_unidad_tiempo'] : ''): '').'</td>
                            <td class="borrar"><button type="button" name="eliminar" id="eliminar" class="icono" '. ($fase!='editar'?'style="display:none"':'') . 'onclick="fn_eliminarDetallePeriodoRetiro(' . $fila['id_periodo_retiro'] . '); return false;"/></td>
                        </tr>';
        }
        
        $this->listaDetalles .= '</table>';
        
        echo $this->listaDetalles;
    }
}