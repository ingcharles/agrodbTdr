<?php

/**
 * Controlador Informes
 *
 * Este archivo controla la lógica del negocio del modelo:  InformesModelo y  Vistas
 *
 * @author DATASTAR
 * @uses     InformesControlador
 * @package Laboratorios
 * @subpackage Controladores
 */

namespace Agrodb\Laboratorios\Controladores;

use Agrodb\Laboratorios\Modelos\InformesLogicaNegocio;
use Agrodb\Laboratorios\Modelos\InformesModelo;
use Agrodb\Laboratorios\Modelos\CamposResultadosInformesLogicaNegocio;
use Agrodb\Core\Mensajes;
use Agrodb\Core\Constantes;

class InformesControlador extends BaseControlador
{

    private $lNegocioCamposResultadosInformes = null;
    private $lNegocioLaboratorios = null;
    private $lNegocioInformes = null;
    private $lNegocioParametrosServicios = null;
    private $modeloInformes = null;
    private $accion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioCamposResultadosInformes = new CamposResultadosInformesLogicaNegocio();
        $this->lNegocioLaboratorios = new \Agrodb\Laboratorios\Modelos\LaboratoriosLogicaNegocio();
        $this->lNegocioParametrosServicios = new \Agrodb\Laboratorios\Modelos\ParametrosServiciosLogicaNegocio();
        $this->lNegocioInformes = new InformesLogicaNegocio();
        $this->modeloInformes = new InformesModelo();
        set_exception_handler(array($this, 'manejadorExcepciones'));
    }

    /**
     * Método de inicio del controlador
     */
    public function index()
    {
        require APP . 'Laboratorios/vistas/listaInformesVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo Informe";
        require APP . 'Laboratorios/vistas/devFormularioInformesVista.php';
    }

    /**
     * Carga el combo de informes tipo árbol
     * @param type $idLaboratorios
     */
    public function cargarInformes($idLaboratorios)
    {
        $modeloInformes = $this->lNegocioInformes->buscarInformes($idLaboratorios);
        $arbol = $this->arbol($modeloInformes);
        echo json_encode($arbol);
        exit();
    }

    /**
     * Carga el combo de informes tipo árbol
     * @param type $idLaboratorios
     */
    public function cargarCamposOT($idLaboratorios)
    {
        $modeloCampos = $this->lNegocioLaboratorios->buscarIdPadre($idLaboratorios);
        $arbolCampos = $this->arbolCamposOT($modeloCampos);
        echo json_encode($arbolCampos);
        exit();
    }

    /**
     * Carga el combo de informes tipo árbol
     * @param type $idLaboratorios
     */
    public function cargarCamposRE($idLaboratorios)
    {

        $modeloCamposRE = $this->lNegocioCamposResultadosInformes->comboCamposResultado($idLaboratorios);
        $arbolCamposRE = $this->arbolCamposRE($modeloCamposRE);
        echo json_encode($arbolCamposRE);
        exit();
    }

    /**
     * Crea un arbol para ser desplegadoen un combo 
     * @param type $tabla
     * @return type
     */
    public function arbol($tabla)
    {
        $array = array();
        foreach ($tabla as $fila)
        {
            $idInforme = $fila['id_informe'];
            // buscar los registro que tengan el id_padre
            $modeloInforme = $this->lNegocioInformes->buscarIdPadre($idInforme);
            if (count($modeloInforme) > 0)
            { // hay hijos
                $array[] = array("id" => $fila['id_informe'], "text" => strip_tags($fila['nombre_informe']), "children" => self::arbol($modeloInforme));
            } else
            { //no hay hijos
                $array[] = array("id" => $fila['id_informe'], "text" => strip_tags($fila['nombre_informe']));
            }
        }
        return $array;
    }

    /**
     * Crea un arbol para ser desplegado en un combo 
     * @param type $tabla
     * @return type
     */
    public function arbolCamposOT($tabla)
    {
        $array = array();
        foreach ($tabla as $fila)
        {
            $idLaboratorio = $fila['id_laboratorio'];
            // buscar los registro que tengan el id_padre
            $modeloLaboratorio = $this->lNegocioLaboratorios->buscarIdPadre($idLaboratorio);
            if (count($modeloLaboratorio) > 0)
            { // hay hijos
                $array[] = array("id" => $fila['id_laboratorio'], "text" => strip_tags($fila['nombre']), "children" => self::arbolCamposOT($modeloLaboratorio));
            } else
            { //no hay hijos
                $array[] = array("id" => $fila['id_laboratorio'], "text" => strip_tags($fila['nombre']));
            }
        }
        return $array;
    }

    /**
     * Crea un arbol para ser desplegadoen un combo 
     * @param type $tabla
     * @return type
     */
    public function arbolCamposRE($tabla)
    {
        $array = array();
        foreach ($tabla as $fila)
        {
            $idLaboratorio = $fila['id_campos_resultados_inf'];
            // buscar los registro que tengan el id_padre
            $modeloCampos = $this->lNegocioCamposResultadosInformes->buscarIdPadre($idLaboratorio);
            if (count($modeloCampos) > 0)
            { // hay hijos
                $array[] = array("id" => $fila['id_campos_resultados_inf'], "text" => strip_tags($fila['nombre']), "children" => self::arbolCamposRE($modeloCampos));
            } else
            { //no hay hijos
                $array[] = array("id" => $fila['id_campos_resultados_inf'], "text" => strip_tags($fila['nombre']));
            }
        }
        return $array;
    }

    /**
     * Método para registrar en la base de datos -Informes
     */
    public function guardar()
    {
        $this->lNegocioInformes->guardar($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Actualiza el orden de los campos 
     */
    public function cambiarOrden()
    {

        $this->lNegocioInformes->guardar($_POST);
    }

    /**
     * Actualiza el estado del registro
     */
    public function cambiarEstado()
    {

        $this->lNegocioInformes->guardar($_POST);
    }

    /**
     * Crea una copia de forma autómatica del laboratorio seleccionado
     */
    public function guardarCopia()
    {
        $this->lNegocioInformes->guardarCopia($_POST);
        Constantes::COPIADO_CON_EXITO;
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: Informes
     */
    public function editar()
    {
        $this->accion = "Editar Informes";
        $this->modeloInformes = $this->lNegocioInformes->buscar($_POST["id"]);

        require APP . 'Laboratorios/vistas/devFormularioInformesVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - Informes
     */
    public function borrar()
    {
        $this->lNegocioInformes->borrar($_POST['elementos']);
    }

    /**
     * Búsqueda por filtro para Parámetros de Configuración
     */
    public function listarDatos()
    {
        $direccion = $_POST['fDireccion'];
        $laboratorio = $_POST['fLaboratorio'];
        $arrayParametros = array();
        if (!empty($direccion))
            $arrayParametros['fk_id_laboratorio'] = $direccion;
        if (!empty($laboratorio))
            $arrayParametros['id_laboratorio'] = $laboratorio;
        $arrayParametros['nivel'] = 1;
        $modeloLaboratorios = $this->lNegocioLaboratorios->buscarLista($arrayParametros);

        $html = "";
        foreach ($modeloLaboratorios as $fila)
        {
            $html.="<tr data-tt-id='" . $fila['id_laboratorio'] . "-'>"
                    . "<td>" . $fila['nombre'] . "</td>"
                    . "<td>" . $fila['estado_registro'] . "</td>"
                    . "<td></td>"
                    . "<td></td>"
                    . "<td></td>"
                    . "<td>" . "<button class='bntGrid fas fa-search' onclick='fn_vistaPrevia(" . $fila['id_laboratorio'] . ")'/>" . "</td>"
                    . "</tr>";
            $modeloInformes = $this->lNegocioInformes->buscarLista(" fk_id_laboratorio = {$fila['id_laboratorio']} and fk_id_informe IS null");
            $html.= $this->tablaHtmlInformesArbol($modeloInformes, "/informes");
        }
        echo $html;
        exit();
    }

    /**
     * Construye tabla html tipo árbol
     * @param type $tabla
     * @param type $vista
     * @return string
     */
    public function tablaHtmlInformesArbol($tabla, $vista = null)
    {
        $html = "";
        foreach ($tabla as $fila)
        {
            $idInforme = $fila['id_informe'];
            if ($fila['nivel'] == 0)
            {
                $this->lNegocioInformes->mantenimientoArbol($fila['id_informe']);
            }
            //Creamoa el botón para copiar un informe
            $campoCopiar = "<button class='bntGrid far fa-clone' onclick='fn_copiar(" . $fila['id_informe'] . "," . $fila['id_direccion'] . "," . $fila['id_laboratorio'] . ")'/>";
            $btnEstado = "<button class=\"bntGrid fas fa-times\" onclick=\"fn_cambiar_estado(" . $fila['id_informe'] . ",'ACTIVO')\"/>";
            if ($fila['estado_registro'] == 'ACTIVO')
            {
                $btnEstado = "<button class=\"bntGrid fas fa-check\" onclick=\"fn_cambiar_estado(" . $fila['id_informe'] . ",'INACTIVO')\"/>";
            }
            // buscar los registro informes que tengan el id_padre
            $modeloInformes = $this->lNegocioInformes->buscarIdPadre($idInforme);
            if (count($modeloInformes) > 0)
            { // hay hijos
                $dato = "{$fila['id_direccion']}-{$fila['fk_id_laboratorio']}-{$fila['id_informe']}";
                $html.="<tr data-tt-id='" . $fila['fk_id_laboratorio'] . "-" . $fila['id_informe'] . "' data-tt-parent-id='" . $fila['fk_id_laboratorio'] . "-" . $fila['fk_id_informe'] . "'"
                        . 'id="' . $fila['id_informe'] . '" class="item"'
                        . "data-rutaAplicacion='" . URL_MVC_FOLDER . "Laboratorios/Informes'"
                        . 'data-opcion="editar"'
                        . 'data-destino="detalleItem">'
                        . "<td><span class='folder'>" . $fila['nombre_informe'] . "</td>"
                        . "<td>" . $btnEstado . "</td>"
                        . "<td>" . $fila['nivel'] . "</td>"
                        . "<td>" . $fila['orden'] . "</td>"
                        . "<td>" . '<button class="bntGrid fas fa-plus" onclick="fn_abrirVistaAgregar(' . "'" . $dato . "'" . ')"/>' . "</td>"
                        . "<td>" . $campoCopiar . "</td>"
                        . "</tr>";
                $html.=self::tablaHtmlInformesArbol($modeloInformes, $vista);
            } else
            { //no hay hijos
                $orden = '<input type="number" id="orden"' . $fila['id_informe'] . ' name="orden"' . $fila['id_informe'] . ' value="' . $fila['orden'] . '" onchange="cambiarOrden(this,' . $fila['id_informe'] . ')"  maxlength="3" min="1" pattern="^[0-9]+" style="width: 50px;"/>';
                $valorEstado = '';
                if ($fila['estado_registro'] == 'ACTIVO')
                {
                    $valorEstado = ' checked';
                }
                $estado = '<input type="checkbox" onclick="cambiarEstado(this,' . $fila['id_informe'] . ')" '.$valorEstado.' />';

                $dato = "{$fila['id_direccion']}-{$fila['fk_id_laboratorio']}-{$fila['id_informe']}";
                $html.="<tr data-tt-id='" . $fila['id_laboratorio'] . "-" . $fila['id_informe'] . "' data-tt-parent-id='" . $fila['fk_id_laboratorio'] . "-" . $fila['fk_id_informe'] . "'"
                        . 'id="' . $fila['id_informe'] . '" class="item"'
                        . "data-rutaAplicacion='" . URL_MVC_FOLDER . "Laboratorios/Informes'"
                        . 'data-opcion="editar"'
                        . 'data-destino="detalleItem">'
                        . "<td><span class='file'>" . $fila['nombre_informe'] . "</td>"
                        . "<td>" . $estado . "</td>"
                        . "<td>" . $fila['nivel'] . "</td>"
                        . "<td>" . $orden . "</td>"
                        . "<td>" . '<button class="bntGrid fas fa-plus" onclick="fn_abrirVistaAgregar(' . "'" . $dato . "'" . ')"/>' . "</td>"
                        . "<td></td>"
                        . "</tr>";
            }
        }
        return $html;
    }

    /**
     * Deshabilita a ciertos de elementos HTML dependiendo del tipo de campo.
     * @param type $tipoCampo
     * @return string
     */
    function visibility($tipoCampo)
    {
        $respuesta = "visible";
        if ($tipoCampo == "CAMPO")
        {
            $respuesta = "hidden";
        }
        return $respuesta;
    }

    /**
     * Funcion para editar el id padre desde la grilla
     */
    public function editarDnD()
    {
        $idInforme = explode('-', $_POST['idInforme']);
        $fkIdInforme = explode('-', $_POST['fkIdInforme']);
        $datos = array('id_informe' => $idInforme[1], 'fk_id_informe' => $fkIdInforme[1]);
        $lNegocioInforme = new InformesLogicaNegocio();
        $lNegocioInforme->guardar($datos);
    }

}
