<?php

/**
 * Controlador Laboratorios
 *
 * Este archivo controla la lógica del negocio del modelo:  LaboratoriosModelo y  Vistas
 *
 * @author DATASTAR
 * @uses     LaboratoriosControlador
 * @package Laboratorios
 * @subpackage Controladores
 */

namespace Agrodb\Laboratorios\Controladores;

use Agrodb\Laboratorios\Modelos\LaboratoriosLogicaNegocio;
use Agrodb\Laboratorios\Modelos\LaboratoriosModelo;
use Agrodb\Core\Mensajes;
use Agrodb\Core\Constantes;

class LaboratoriosControlador extends FormularioDinamico
{

    private $lNegocioLaboratorios = null;
    private $modeloLaboratorios = null;
    private $accion = null;
    public $camposMuestrasPrevio;
    public $camposAnalisisPrevio;
    private $direccion;
    private $laboratorio;
    private $crearHijo;
    private $col;
    private $cam;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioLaboratorios = new LaboratoriosLogicaNegocio();
        $this->modeloLaboratorios = new LaboratoriosModelo();
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
        require APP . 'Laboratorios/vistas/listaLaboratoriosVista.php';
    }

    /**
     * Despliega la lista con laboratorios para la aprobacion
     */
    public function aprobar()
    {
        require APP . 'Laboratorios/vistas/listaLaboratoriosAprobarVista.php';
    }

    /**
     * Búsqueda por filtro usado en Laboratorios y Aprobacion laboratorios
     */
    public function listarDatos($vistaEditar)
    {
        $direccion = $_POST['direccion'];
        $codigo = $_POST['codigo'];
        $nombre = $_POST['nombre'];
        $arrayParametros = array('direccion' => $direccion, 'codigo' => $codigo, 'nombre' => $nombre);
        $modeloLaboratorios = $this->lNegocioLaboratorios->buscarListaParametros($arrayParametros, 'orden');
        $this->tablaHtmlLaboratorios($modeloLaboratorios, $vistaEditar);
        echo \Zend\Json\Json::encode($this->itemsFiltrados);
        exit();
    }

    /**
     * Despliega la lista con las direcciones de diagnóstico
     */
    public function direcciones()
    {
        $modeloLaboratorios = $this->lNegocioLaboratorios->buscarDirecciones();
        $this->tablaHtmlLaboratorios($modeloLaboratorios, "direcciones");
        require APP . 'Laboratorios/vistas/listaDireccionesVista.php';
    }

    /**
     * Actualizar registros 
     */
    public function direccionesActualizar()
    {
        $modeloLaboratorios = $this->lNegocioLaboratorios->buscarDirecciones();
        $this->tablaHtmlLaboratorios($modeloLaboratorios, "direcciones");
        echo \Zend\Json\Json::encode($this->itemsFiltrados);
        exit();
    }

    /**
     * Muestra la vista con los campos para filtro de configuración de laboratorios
     * La lista se muestra con la llamada ajax de parametrosConfLab
     */
    public function configuracion()
    {
        require APP . 'Laboratorios/vistas/listaConfiguracionLaboratoriosVista.php';
    }

    /**
     * Búsqueda por filtro para Parámetros de Configuración
     */
    public function parametrosConfLab()
    {
        $direccion = $_POST['fDireccion'];
        $laboratorio = $_POST['fLaboratorio'];
        $arrayParametros = array();
        if (!empty($direccion))
        {
            $arrayParametros['fk_id_laboratorio'] = $direccion;
        }
        if (!empty($laboratorio))
        {
            $arrayParametros['id_laboratorio'] = $laboratorio;
        }
        $arrayParametros['nivel'] = 1; //Nivel 1 son laboratorios 
        $modeloLaboratorios = $this->lNegocioLaboratorios->buscarListaLaboratorios($arrayParametros);

        $html = "";
        $vista = "/configuracionLaboratorios";
        foreach ($modeloLaboratorios as $fila)
        {
            $html .= "<tr data-tt-id='" . $fila['id_laboratorio'] . "'>'"
                    . "<td><span class='folder'>" . $fila['nombre'] . "</td>"
                    . "<td>" . $fila['tipo_campo'] . "</td>"
                    . "<td>" . $fila->nivel_acceso . "</td>"
                    . "<td>" . $fila->visible_en . "</td>"
                    . "<td>" . $fila['estado_registro'] . "</td>"
                    . "<td>" . $fila['nivel'] . "</td>"
                    . "<td>" . $fila['orden'] . "</td>"
                    . "<td>" . $fila['orden_ot'] . "</td>"
                    . "<td>" . $fila['data_linea'] . "</td>"
                    . "<td>" . "<button class='bntGrid fas fa-plus' onclick='fn_abrirVistaAgregar(" . $fila['id_laboratorio'] . ")'/>" . "</td>"
                    . "<td>" . "<button class='bntGrid fas fa-search' onclick='fn_vistaPrevia(" . $fila['id_laboratorio'] . ")'/>" . "</td>"
                    . "</tr>";
            $modeloLaboratorios = $this->lNegocioLaboratorios->buscarIdPadre($fila['id_laboratorio']);
            $html .= $this->tablaHtmlConfLabArbol($modeloLaboratorios, "/configuracionLaboratorios");
        }
        echo $html;
        exit();
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo Laboratorio";
        require APP . 'Laboratorios/vistas/formularioLaboratoriosVista.php';
    }

    /**
     * Agrega nuevos campos al formulario de la solicitud
     */
    public function agregarCampos()
    {
        $this->accion = "Configuración de Laboratorio";

        $this->modeloLaboratorios = $this->lNegocioLaboratorios->buscar($_POST['idPadre']);

        require APP . 'Laboratorios/vistas/formularioAgregarCamposLaboratoriosVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevaDireccion()
    {
        $this->accion = "Nueva Dirección de Diagnóstico";
        require APP . 'Laboratorios/vistas/formularioDireccionesVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevaConfiguracion()
    {
        $this->accion = "Nueva Configuración de Laboratorio";
        $this->crearHijo = 0;
        //para buscar los datos del padre y enviar campos predefinidos
        if (isset($_POST['idPadre']))
        {
            $this->crearHijo = 1;
            $idLaboratorio = $_POST['idPadre'];
            $buscaConfLab = $this->lNegocioLaboratorios->buscarConfLab($idLaboratorio);
            $dato = $buscaConfLab->current();
            $this->direccion = $dato['direccion'];
            $this->laboratorio = $dato['laboratorio'];
            $this->modeloLaboratorios = new LaboratoriosModelo();
            $this->modeloLaboratorios->setNivel($dato['nivel'] + 1);
            $this->modeloLaboratorios->setCodigo($dato['codigo']);
        }
        require APP . 'Laboratorios/vistas/formularioConfiguracionLaboratoriosVista.php';
    }

    /**
     * Para mostrar la vista previa
     */
    public function vistaPrevia()
    {
        $idLaboratorio = $_POST['idLaboratorio'];
        $this->camposMuestrasPrevio = $this->camposMuestras($idLaboratorio);
        $this->camposAnalisisPrevio = $this->vistaPreviaCamposAnalisis($idLaboratorio);
        require APP . 'Laboratorios/vistas/vistaPrevia.php';
    }

    /**
     * Para formar el combo tipo arbol de laboratorios
     * @param type $tabla
     * @return type
     */
    public function arbol($tabla)
    {
        $array = array();
        foreach ($tabla as $fila)
        {
            $idLaboratorio = $fila['id_laboratorio'];
            // buscar los registro que tengan el id_padre
            $modeloLaboratorios = $this->lNegocioLaboratorios->buscarIdPadre($idLaboratorio);
            if (count($modeloLaboratorios) > 0)
            { // hay hijos
                $array[] = array("id" => $fila['id_laboratorio'], "text" => strip_tags($fila['nombre']), "children" => self::arbol($modeloLaboratorios));
            } else
            { //no hay hijos
                $array[] = array("id" => $fila['id_laboratorio'], "text" => strip_tags($fila['nombre']));
            }
        }
        return $array;
    }

    /**
     * Método para registrar en la base de datos -Laboratorios
     */
    public function guardar()
    {
        $this->lNegocioLaboratorios->guardar($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Actualiza el orden de los campos 
     */
    public function cambiarOrden()
    {
        $this->lNegocioLaboratorios->guardar($_POST);
    }
    
    

    /**
     * Actualiza el estado del registro
     */
    public function cambiarEstado()
    {
        $this->lNegocioLaboratorios->guardar($_POST);
    }

    /**
     * Actualiza el campo atributos 
     */
    public function configurarGeneral()
    {
        $this->lNegocioLaboratorios->guardar($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Actualiza el campo conf_orden_trabajo 
     */
    public function configurarOT()
    {
        $this->lNegocioLaboratorios->guardar($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Método para registrar en la base de datos -Laboratorios
     * Guardar configuración de laboratorio
     */
    public function guardarConfLab()
    {
        //el nivel debe ser mayor a 1
        if ($_POST['nivel'] <= 1)
        {
            $_POST['nivel'] = 2;
        }
        $this->lNegocioLaboratorios->guardar($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: Laboratorios
     */
    public function editar($vista)
    {
        $idLaboratorio = $_POST["id"];
        $this->accion = "Editar " . $vista;
        $this->modeloLaboratorios = $this->lNegocioLaboratorios->buscar($idLaboratorio);
        require APP . 'Laboratorios/vistas/formulario' . ucfirst($vista) . 'Vista.php';
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: Laboratorios
     * Editar la configuración del formulario de laboratorios
     */
    public function editarConfLab($vista)
    {
        $this->crearHijo = 0;
        $idLaboratorio = $_POST["id"];
        //obtener direccion y laboratorio del registro de la tabla laboratorio
        $this->accion = "Editar Configuración de Laboratorio";
        $buscaConfLab = $this->lNegocioLaboratorios->buscarConfLab($idLaboratorio);
        $dato = $buscaConfLab->current();
        $this->direccion = $dato['direccion'];
        $this->laboratorio = $dato['laboratorio'];
        unset($dato['direccion'], $dato['laboratorio']);
        $this->modeloLaboratorios = new LaboratoriosModelo($dato);
        require APP . 'Laboratorios/vistas/formulario' . ucfirst($vista) . 'Vista.php';
    }

    /**
     * Método para buscar los datos de la configuración del laboratorio tipo arbol segun el laboratorio
     */
    public function buscarNodos($idLaboratorio)
    {
        $buscarConfLab = $this->lNegocioLaboratorios->buscarLista(" fk_id_laboratorio = $idLaboratorio");
        $arbol = $this->arbol($buscarConfLab);
        array_push($arbol, array("id" => "$idLaboratorio", "text" => "NINGUNO"));
        echo json_encode($arbol);
    }

    /**
     * Método para borrar un registro en la base de datos - Laboratorios
     */
    public function borrar()
    {
        $this->lNegocioLaboratorios->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - Laboratorios
     */
    public function tablaHtmlLaboratorios($tabla, $vista = null)
    {
        $contador = 0;
        foreach ($tabla as $fila)
        {
            //Llamamos a la función en la base de datos para configurar los campos necesarios para el laboratorio
            $this->lNegocioLaboratorios->actualizarConfiguracion($fila['id_laboratorio']);

            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_laboratorio'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Laboratorios/Laboratorios"
		  data-opcion="editar/' . $vista . '" 
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['nombre'] . '</b></td>
          <td>' . $fila['codigo'] . '</td>
          <td>' . $fila['estado_registro'] . '</td>
          <td>' . $fila['orden'] . '</td>
          </tr>'
            );
        }
    }

    /**
     * Forma la tabla tipo árbol para la Configuración de LAboratorios
     * @param type $tabla
     * @param type $vista
     * @return string
     */
    public function tablaHtmlConfLabArbol($tabla, $vista = null)
    {
        $html = "";
        foreach ($tabla as $fila)
        {
            $idLaboratorio = $fila['id_laboratorio'];
            // buscar los registro que tengan el id_padre
            $modeloLaboratorios = $this->lNegocioLaboratorios->buscarIdPadre($idLaboratorio);
            if (count($modeloLaboratorios) > 0)
            { // hay hijos
                $html .= "<tr data-tt-id='" . $fila['id_laboratorio'] . "' data-tt-parent-id='" . $fila['fk_id_laboratorio'] . "'"
                        . 'id="' . $fila['id_laboratorio'] . '" class="item"'
                        . "data-rutaAplicacion='" . URL_MVC_FOLDER . "Laboratorios/Laboratorios'"
                        . 'data-opcion="editarConfLab' . $vista . '"'
                        . 'data-destino="detalleItem">'
                        . "<td><span class='folder'>" . strip_tags($fila['nombre']) . "</td>"
                        . "<td>" . $fila['tipo_campo'] . "</td>"
                        . "<td>" . $fila->nivel_acceso . "</td>"
                        . "<td>" . $fila->visible_en . "</td>"
                        . "<td>" . $fila['estado_registro'] . "</td>"
                        . "<td>" . $fila['nivel'] . "</td>"
                        . "<td>" . $fila['orden'] . "</td>"
                        . "<td>" . $fila['data_linea'] . "</td>"
                        . "<td>" . "<button class='bntGrid fas fa-plus' onclick='fn_abrirVistaAgregar(" . $fila['id_laboratorio'] . ")'/>" . "</td>"
                        . "<td></td>"
                        . "</tr>";
                $html .= self::tablaHtmlConfLabArbol($modeloLaboratorios, $vista);
            } else
            { //no hay hijos
                $orden = '<input type="number" id="orden"' . $fila['id_laboratorio'] . ' name="orden"' . $fila['id_laboratorio'] . ' value="' . $fila['orden'] . '" onchange="cambiarOrden(this,' . $fila['id_laboratorio'] . ')"  maxlength="3" min="1" pattern="^[0-9]+" style="width: 50px;"/>';
                $orden_ot = '<input type="number" id="orden_ot"' . $fila['id_laboratorio'] . ' name="orden_ot"' . $fila['id_laboratorio'] . ' value="' . $fila['orden_ot'] . '" onchange="cambiarOrden_ot(this,' . $fila['id_laboratorio'] . ')"  maxlength="3" min="1" pattern="^[0-9]+" style="width: 50px;"/>';
                $valorEstado = '';
                if ($fila['estado_registro'] == 'ACTIVO')
                {
                    $valorEstado = ' checked';
                }
                $estado = '<input type="checkbox" onclick="cambiarEstado(this,' . $fila['id_laboratorio'] . ')"  ' . $valorEstado . ' />';


                $html .= "<tr data-tt-id='" . $fila['id_laboratorio'] . "' data-tt-parent-id='" . $fila['fk_id_laboratorio'] . "'"
                        . 'id="' . $fila['id_laboratorio'] . '" class="item"'
                        . "data-rutaAplicacion='" . URL_MVC_FOLDER . "Laboratorios/Laboratorios'"
                        . 'data-opcion="editarConfLab' . $vista . '"'
                        . 'data-destino="detalleItem">'
                        . "<td><span class='file'>" . strip_tags($fila['nombre']) . "</td>"
                        . "<td>" . $fila['tipo_campo'] . "</td>"
                        . "<td>" . $fila->nivel_acceso . "</td>"
                        . "<td>" . $fila->visible_en . "</td>"
                        . "<td>" . $estado . "</td>"
                        . "<td>" . $fila['nivel'] . "</td>"
                        . "<td>" . $orden . "</td>"
                        . "<td>" . $orden_ot . "</td>"
                        . "<td>" . $fila['data_linea'] . "</td>"
                        . "<td>" . "<button class='bntGrid fas fa-plus' onclick='fn_abrirVistaAgregar(" . $fila['id_laboratorio'] . ")'/>" . "</td>"
                        . "<td></td>"
                        . "</tr>";
            }
        }
        return $html;
    }

    /**
     * Para formar la vista previa de los campos de análisis
     * Solo 
     * @param type $idLaboratorio
     */
    public function vistaPreviaCamposAnalisis($idLaboratorio)
    {
        $this->vistaPreviaCamposAnalisisHtml($idLaboratorio);
        $tablaTipoAnalisis = $this->etiqueta . '<table id="detalleSolicitud">
    <thead>
 ' . $this->col . '
    </thead>
   <tr>' . $this->cam . '</tr>
    </table>';
        echo $tablaTipoAnalisis;
    }

    /**
     * Forma los campos dinámicos para la vista previa
     * @param type $idLaboratorio
     * @throws \Exception
     */
    public function vistaPreviaCamposAnalisisHtml($idLaboratorio)
    {
        if (!isset($idLaboratorio) || $idLaboratorio == null)
        {
            throw new \Exception('Clase: FormularioDinamico. El ID padre del laboratorio no existe o no fue seleccionado');
        }
        $tipo = "ANALISIS";
        $campos = "";
        $columnas = "";
        $lNegocioLaboratorios = new LaboratoriosLogicaNegocio();
        $tabla = $lNegocioLaboratorios->camposLaboratorio($idLaboratorio, 0, $tipo);
        $arrayEtiqueta = array();
        $arrayCampos = array();
        $this->etiqueta;
        foreach ($tabla as $fila)
        {
            $atributos = "";
            $atributos = $fila['atributos'];
            $atributos .= $fila['obligatorio'] === 't' ? 'required' : "";
            switch ($fila['tipo_campo'])
            {
                case "ETIQUETA":
                    $this->etiqueta = "<fieldset><legend>" . $fila['nombre'] . " </legend>";
                    self::vistaPreviaCamposAnalisisHtml($fila['id_laboratorio']);
                    break;
                case "SUBETIQUETA":
                    echo "<fieldset class='fieldsetMuestras'><legend class='legendMuestras'>" . $fila['nombre'] . " </legend>";
                    $this->vistaPreviaCamposAnalisisHtml($fila['id_laboratorio']);
                    break;
                default:
                    $arrayEtiqueta[] = $fila['nombre'];
                    $arrayCampos[] = array('id_laboratorio' => $fila['id_laboratorio'], 'id_servicio' => '2', 'descripcion' => $fila['descripcion'], 'atributos' => $atributos);
                    $columnas .= "<th>" . $fila['nombre'] . "</th>";
                    break;
            }
        }
        //repetir los campos según la cantidad
        $trs = '';
        for ($i = 1; $i <= 5; $i++)
        {
            $campos = '';
            foreach ($arrayCampos as $row)
            {
                $campos .= '<td>';
                $campos .= '<input type="text" id="texto' . $row['id_laboratorio'] . '_' . $row['id_servicio'] . '" name="texto' . $row['id_laboratorio'] . '_' . $row['id_servicio'] . '"  placeholder="' . $row['descripcion'] . '" ' . $row['atributos'] . ' />';
                $campos .= '</td>';
            }
            //formar la fila
            $trs .= '<tr><td>' . 'CODIGO ANALISIS' . '</td>' . $campos . '</tr>';
        }
        if ($columnas != null)
            $this->col = '<tr><th>ANÁLISIS SOLICITADO</th>' . $columnas . '</tr>';
        if ($campos != null)
            $this->cam = $trs;
    }

    /**
     * Funcion para editar el id padre desde la grilla
     */
    public function editarDnD()
    {
        $datos = array('id_laboratorio' => $_POST['idLaboratorio'], 'fk_id_laboratorio' => $_POST['fkIdLaboratorio']);
        $lNegocioLaboratorios = new LaboratoriosLogicaNegocio();
        $lNegocioLaboratorios->guardar($datos);
    }

}
