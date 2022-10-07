<?php
/**
 * Controlador DetalleMovilizacion
 *
 * Este archivo controla la lógica del negocio del modelo:  DetalleMovilizacionModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2019-09-02
 * @uses    DetalleMovilizacionControlador
 * @package MovilizacionVegetal
 * @subpackage Controladores
 */
namespace Agrodb\MovilizacionVegetal\Controladores;

use Agrodb\MovilizacionVegetal\Modelos\DetalleMovilizacionLogicaNegocio;
use Agrodb\MovilizacionVegetal\Modelos\DetalleMovilizacionModelo;

class DetalleMovilizacionControlador extends BaseControlador
{

    private $lNegocioDetalleMovilizacion = null;
    private $modeloDetalleMovilizacion = null;

    private $accion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioDetalleMovilizacion = new DetalleMovilizacionLogicaNegocio();
        $this->modeloDetalleMovilizacion = new DetalleMovilizacionModelo();
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
        $modeloDetalleMovilizacion = $this->lNegocioDetalleMovilizacion->buscarDetalleMovilizacion();
        $this->tablaHtmlDetalleMovilizacion($modeloDetalleMovilizacion);
        require APP . 'MovilizacionVegetal/vistas/listaDetalleMovilizacionVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo DetalleMovilizacion";
        require APP . 'MovilizacionVegetal/vistas/formularioDetalleMovilizacionVista.php';
    }

    /**
     * Método para registrar en la base de datos -DetalleMovilizacion
     */
    public function guardar()
    {
        $this->lNegocioDetalleMovilizacion->guardar($_POST);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: DetalleMovilizacion
     */
    public function editar()
    {
        $this->accion = "Editar DetalleMovilizacion";
        $this->modeloDetalleMovilizacion = $this->lNegocioDetalleMovilizacion->buscar($_POST["id"]);
        require APP . 'MovilizacionVegetal/vistas/formularioDetalleMovilizacionVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - DetalleMovilizacion
     */
    public function borrar()
    {
        $this->lNegocioDetalleMovilizacion->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - DetalleMovilizacion
     */
    public function tablaHtmlDetalleMovilizacion($tabla)
    {
        {
            $contador = 0;
            foreach ($tabla as $fila) {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila['id_detalle_movilizacion'] . '"
                    		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'MovilizacionVegetal\detallemovilizacion"
                    		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
                    		  data-destino="detalleItem">
                    		  <td>' . ++ $contador . '</td>
                    	<td style="white - space:nowrap; "><b>' . $fila['id_detalle_movilizacion'] . '</b></td>
                        <td>' . $fila['id_movilizacion'] . '</td>
                        <td>' . $fila['id_area_origen'] . '</td>
                        <td>' . $fila['id_area_destino'] . '</td>
                    </tr>'
                );
            }
        }
    }
    
    /**
     * Método para listar los seguimientos registrados
     */
    public function construirDetalleMovilizacion($idMovilizacion)
    {
        $listaDestalles = $this->lNegocioDetalleMovilizacion->buscarDetalleXMovilizacion($idMovilizacion);
        
        $this->listaDetalles = '<table style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>NºReg</th>
                                                <th>Origen</th>
                                                <th>Destino</th>
                                                <th>Subtipo Producto</th>
                                                <th>Producto</th>
                                                <th>Cantidad</th>
                                                <th>Unidad</th>                                                
                                            </tr>
                                        </thead>';
        
        $i=1;
        
        foreach ($listaDestalles as $fila) {         
            
            $this->listaDetalles .=
            '<tr>
                        <td>' . $i++. '</td>
                        <td>' . ($fila['area_origen'] != '' ? $fila['area_origen'] : ''). '</td>
                        <td>' . ($fila['area_destino'] != '' ? $fila['area_destino'] : '') . '</td>
                        <td>' . ($fila['subtipo_producto'] != '' ? $fila['subtipo_producto'] : ''). '</td>
                        <td>' . ($fila['producto'] != '' ? $fila['producto'] : '') . '</td>
                        <td>' . ($fila['cantidad'] != '' ? $fila['cantidad'] : '') . '</td>
                        <td>' . ($fila['unidad'] != '' ? $fila['unidad'] : '') . '</td>
                    </tr>';
        }
        
        $this->listaDetalles .= '</table>';
        
        echo $this->listaDetalles;
    }
    
    /**
     * Método para listar los seguimientos registrados
     */
    public function construirDetalleMovilizacionEditable($idMovilizacion)
    {
        $listaDestalles = $this->lNegocioDetalleMovilizacion->buscarDetalleXMovilizacion($idMovilizacion);
        
        $this->listaDetalles = '';
        $i=1;
        
        foreach ($listaDestalles as $fila) {
            
            $this->listaDetalles .=
            '         
                    <tr id="'.$fila['id_detalle_movilizacion'].'">
                        <td>' . $i. '</td>
                        <td>' . ($fila['area_origen'] != '' ? $fila['area_origen'] : ''). '<input type="hidden" id="dtxtIdRegistro" name="dtxtIdRegistro[]" value="'.$fila['id_detalle_movilizacion'].'"></td>
                        <td>' . ($fila['area_destino'] != '' ? $fila['area_destino'] : '') . '</td>
                        <td>' . ($fila['subtipo_producto'] != '' ? $fila['subtipo_producto'] : ''). '</td>
                        <td>' . ($fila['producto'] != '' ? $fila['producto'] : '') . ' </td>
                        <td><input type="number" id="cantidadN'.$i.'" name="dtxtCantidad[]" data-er="^[0-9]+$" onChange="fn_validarCantidad('.$i.')" value="'.$fila['cantidad'].'" ><input type="hidden" id="cantidadO'.$i++.'" name="dtxtCantidadO[]" value="'.$fila['cantidad'].'"></td>
                        <td>' . ($fila['unidad'] != '' ? $fila['unidad'] : '') . '</td>
                    </tr>';
        }
        
        echo $this->listaDetalles;
    }
}
