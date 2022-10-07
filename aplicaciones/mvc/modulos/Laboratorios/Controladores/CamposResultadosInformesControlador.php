<?php

/**
 * Controlador CamposResultadosInformes
 *
 * Este archivo controla la lógica del negocio del modelo:  CamposResultadosInformesModelo y  Vistas
 *
 * @author DATASTAR
 * @uses     CamposResultadosInformesControlador
 * @package Laboratorios
 * @subpackage Controladores
 */

namespace Agrodb\Laboratorios\Controladores;

use Agrodb\Laboratorios\Modelos\CamposResultadosInformesLogicaNegocio;
use Agrodb\Laboratorios\Modelos\CamposResultadosInformesModelo;
use Agrodb\Laboratorios\Modelos\ServiciosLogicaNegocio;
use Agrodb\Core\Mensajes;
use Agrodb\Core\Constantes;

class CamposResultadosInformesControlador extends FormularioDinamicoResultados
{

    private $lNegocioCamposResultadosInformes = null;
    private $modeloCamposResultadosInformes = null;
    private $lNCamposResultadosInformes = null;
    private $accion = null;
    private $camposResultado = null;
    private $idCampoRaiz = null;
    private $lNegocioServicios = null;
    private $idServicio;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioCamposResultadosInformes = new CamposResultadosInformesLogicaNegocio();
        $this->modeloCamposResultadosInformes = new CamposResultadosInformesModelo();
        $this->lNCamposResultadosInformes = new CamposResultadosInformesLogicaNegocio();
        $this->lNegocioServicios = new ServiciosLogicaNegocio();
        set_exception_handler(array($this, 'manejadorExcepciones'));
    }

    /**
     * Método de inicio del controlador
     */
    public function index()
    {
        require APP . 'Laboratorios/vistas/listaCamposResultadosInformesVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        if (isset($_POST['idPadre']))
        {
            $idCampo = $_POST['idPadre'];
            $resultado = $this->lNegocioCamposResultadosInformes->buscar($idCampo);
            $this->modeloCamposResultadosInformes->setNivel($resultado->getNivel() + 1);
            $this->modeloCamposResultadosInformes->setIdDireccion($resultado->getIdDireccion());
            $this->modeloCamposResultadosInformes->setIdLaboratorio($resultado->getIdLaboratorio());
            $this->modeloCamposResultadosInformes->setIdServicio($resultado->getIdServicio());
            $this->modeloCamposResultadosInformes->setFkIdCamposResultadosInf($resultado->getIdCamposResultadosInf());
        }

        $this->accion = "Nuevo Campos Resultados Informes";
        require APP . 'Laboratorios/vistas/formularioCamposResultadosInformesVista.php';
    }

    /**
     * Despliega el formulario para agregar un nuevo campo
     */
    public function agregar()
    {
        if (isset($_POST['idPadre']))
        {
            $idCampo = $_POST['idPadre'];
            $resultado = $this->lNegocioCamposResultadosInformes->buscar($idCampo);
            $this->modeloCamposResultadosInformes->setNivel($resultado->getNivel() + 1);
            $this->modeloCamposResultadosInformes->setIdDireccion($resultado->getIdDireccion());
            $this->modeloCamposResultadosInformes->setIdLaboratorio($resultado->getIdLaboratorio());
            $this->modeloCamposResultadosInformes->setIdServicio($resultado->getIdServicio());
            $this->modeloCamposResultadosInformes->setFkIdCamposResultadosInf($resultado->getIdCamposResultadosInf());
        }

        $this->accion = "Nuevo Campos Resultados Informes";
        require APP . 'Laboratorios/vistas/formularioAgregarCamposRIVista.php';
    }

    /**
     * Presenta una vista previa de los campos configurados
     */
    public function vistaPrevia()
    {
        $this->camposResultado = $this->camposResultadosVistaPrevia($_POST['idServicio']);
        $this->accion = "Nuevo Resultado Análisis";
        require APP . 'Laboratorios/vistas/vistaPreviaCamposResultasdosVista.php';
    }

    /**
     * Copia un formulario de un servicion a otro similar
     */
    public function copiar()
    {
        $this->idCampoRaiz = $_POST['idCampoRaiz'];
        $this->camposResultado = $this->camposResultadosVistaPrevia($_POST['idServicio']);
        $this->accion = "Copiar formulario";
        require APP . 'Laboratorios/vistas/copiarCamposResultasdosVista.php';
    }

    /**
     * Guarda la copia de los campos de resultado
     */
    public function guardarCopia()
    {
        $this->lNegocioCamposResultadosInformes->guardarCopia($_POST);
    }

    /**
     * Método para registrar en la base de datos -CamposResultadosInformes
     */
    public function guardar()
    {
        
        $this->lNegocioCamposResultadosInformes->guardar($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: CamposResultadosInformes
     */
    public function editar()
    {
        $this->accion = "Editar Campos Resultados Informes";
        $this->modeloCamposResultadosInformes = $this->lNegocioCamposResultadosInformes->buscar($_POST["id"]);
        require APP . 'Laboratorios/vistas/formularioCamposResultadosInformesVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - CamposResultadosInformes
     */
    public function borrar()
    {
        $this->lNegocioCamposResultadosInformes->borrar($_POST['elementos']);
    }

    /**
     * Retorna el dato del campo padre tipo json
     * @param type $idServicio
     */
    public function buscarCamposPadre($idServicio)
    {
        $resultado = $this->lNegocioCamposResultadosInformes->buscarLista(" id_servicio = $idServicio and fk_id_campos_resultados_inf IS null and estado_registro='ACTIVO'");
        $arbol = $this->arbol($resultado);
        array_push($arbol, array("id" => "0", "text" => "NINGUNO"));
        echo json_encode($arbol);
    }

    /**
     * Funcion para editar el id padre desde la grilla
     */
    public function editarDnD()
    {

        $idCamposResultadosInf = explode('-', $_POST['idCamposResultadosInf']);
        $fkIdCamposResultadosInf = explode('-', $_POST['fkIdCamposResultadosInf']);

        $datos = array('id_campos_resultados_inf' => $idCamposResultadosInf[1], 'fk_id_campos_resultados_inf' => $fkIdCamposResultadosInf[1], 'nivel' => 1);
        $this->lNegocioCamposResultadosInformes->guardar($datos);
    }

    /**
     * Búsqueda por filtro
     */
    public function listarDatos()
    {
        $direccion = $_POST['fDireccion'];
        $laboratorio = $_POST['fLaboratorio'];
        $arrayParametros = array();
        if (!empty($direccion))
            $arrayParametros['idDireccion'] = $direccion;
        if (!empty($laboratorio))
            $arrayParametros['idLaboratorio'] = $laboratorio;

        $buscarServicios = $this->lNegocioCamposResultadosInformes->buscarServicios($arrayParametros);

        $html = "";
        $vista = "/camposResultadosInformes";
        foreach ($buscarServicios as $fila)
        {
            $this->idServicio = $fila->id_servicio;
            $html.="<tr data-tt-id='" . $fila->id_servicio . "-'>"
                    . "<td>" . $fila->f_path_nom_servicio . "</td>"
                    . "<td></td>"
                    . "<td></td>"
                    . "<td></td>"
                    . "<td></td>"
                    . "<td></td>"
                    . "<td></td>"
                    . "<td></td>"
                    . "</tr>";
            $resultado = $this->lNegocioCamposResultadosInformes->buscarLista(" id_servicio = {$fila->id_servicio} AND fk_id_campos_resultados_inf IS null");
            $html.= $this->tablaHtmlCamposArbol($resultado, $vista);
        }
        echo $html;
        exit();
    }

    /**
     * Crea una tabla con datos de campos de forma recursiva
     * @param type $tabla
     * @param type $vista
     * @return string
     */
    public function tablaHtmlCamposArbol($tabla, $vista = null)
    {
        $html = "";
        foreach ($tabla as $fila)
        {
            $idCampo = $fila->id_campos_resultados_inf;
            $campoVistaPrevia = "";
            $campoCopiar = "";
            $classEditar = "item";
            if ($fila->nivel == 0)
            {
                $classEditar = "";
                $campoVistaPrevia = "<button class='bntGrid fas fa-search' onclick='fn_vistaPrevia(" . $fila->id_servicio . ")'/>";
                $campoCopiar = "<button class='bntGrid far fa-clone' onclick='fn_copiar(" . $fila->id_servicio . "," . $fila->id_campos_resultados_inf . ")'/>";
                $this->lNegocioCamposResultadosInformes->mantenimientoArbolCampos($idCampo);
            }
            // buscar los registro que tengan el id_padre
            $resultado = $this->lNegocioCamposResultadosInformes->buscarIdPadre($idCampo);
            if (count($resultado) > 0)
            { // hay hijos
                $html.="<tr data-tt-id='" . $fila->id_servicio . "-" . $fila->id_campos_resultados_inf . "' data-tt-parent-id='" . $fila->id_servicio . "-" . $fila->fk_id_campos_resultados_inf . "'"
                        . 'id="' . $fila->id_campos_resultados_inf . '" class="item"'
                        . "data-rutaAplicacion='" . URL_MVC_FOLDER . "Laboratorios/camposResultadosInformes'"
                        . 'data-opcion="editar' . $vista . '"'
                        . 'data-destino="detalleItem">'
                        . "<td><span class='folder'>" . $fila->nombre . "</td>"
                        . "<td>" . $fila->tipo_campo . "</td>"
                        . "<td>" . $fila->estado_registro . "</td>"
                        . "<td>" . $fila->nivel . "</td>"
                        . "<td>" . $fila->orden . "</td>"
                        . "<td>" . "<button class='bntGrid fas fa-plus' onclick='fn_abrirVistaAgregar(" . $fila->id_campos_resultados_inf . ")'/>" . "</td>"
                        . "<td>" . $campoVistaPrevia . "</td>"
                        . "<td>" . $campoCopiar . "</td>"
                        . "</tr>";
                $html.=self::tablaHtmlCamposArbol($resultado, $vista);
            } else
            { //no hay hijos
                $html.="<tr data-tt-id='" . $fila->id_servicio . "-" . $fila->id_campos_resultados_inf . "' data-tt-parent-id='" . $fila->id_servicio . "-" . $fila->fk_id_campos_resultados_inf . "'"
                        . 'id="' . $fila->id_campos_resultados_inf . '" class="item"'
                        . "data-rutaAplicacion='" . URL_MVC_FOLDER . "Laboratorios/camposResultadosInformes'"
                        . 'data-opcion="editar' . $vista . '"'
                        . 'data-destino="detalleItem">'
                        . "<td><span class='file'>" . $fila->nombre . "</td>"
                        . "<td>" . $fila->tipo_campo . "</td>"
                        . "<td>" . $fila->estado_registro . "</td>"
                        . "<td>" . $fila->nivel . "</td>"
                        . "<td>" . $fila->orden . "</td>"
                        . "<td>" . "<button class='bntGrid fas fa-plus' onclick='fn_abrirVistaAgregar(" . $fila->id_campos_resultados_inf . ")'/>" . "</td>"
                        . "<td></td>"
                        . "<td></td>"
                        . "</tr>";
            }
        }
        return $html;
    }

    /**
     * Construye array tipo árbol de los campos
     * @param type $tabla
     * @return type
     */
    public function arbol($tabla)
    {
        $array = array();
        foreach ($tabla as $fila)
        {
            $idServicio = $fila['id_campos_resultados_inf'];
            // buscar los registro que tengan el id_padre
            $resultado = $this->lNegocioCamposResultadosInformes->buscarIdPadre($idServicio);
            if (count($resultado) > 0)
            { // hay hijos
                $array[] = array("id" => $fila['id_campos_resultados_inf'], "text" => strip_tags($fila['nombre']), "children" => self::arbol($resultado));
            } else
            { //no hay hijos
                $array[] = array("id" => $fila['id_campos_resultados_inf'], "text" => strip_tags($fila['nombre']));
            }
        }
        return $array;
    }

    /**
     * Construye el código HTML para desplegar la lista de - CamposResultadosInformes
     */
    public function tablaHtmlCamposResultadosInformes($tabla)
    {
        $contador = 0;
        foreach ($tabla as $fila)
        {
            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_campos_resultados_inf'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Laboratorios/CamposResultadosInformes"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_campos_resultados_inf'] . '</b></td>
                  <td>' . $fila['id_servicio'] . '</td>
                  <td>' . $fila['fk_id_campos_resultados_inf'] . '</td>
                  <td>' . $fila['id_laboratorio'] . '</td>
                  </tr>');
        }
    }

    /**
     * Método para buscar los datos del servicio tipo arbol segun el laboratorio
     */
    public function buscarServiciosPadre($idLaboratorio)
    {
        $modeloServicios = $this->lNegocioServicios->buscarLista(" id_laboratorio = $idLaboratorio and fk_id_servicio IS null");
        $arbol = $this->arbolServicios($modeloServicios);
        echo json_encode($arbol);
    }

    /**
     * Retornar los servicios tipo árbol
     * @param type $tabla
     * @return type
     */
    public function arbolServicios($tabla)
    {
        $array = array();
        foreach ($tabla as $fila)
        {
            $idServicio = $fila['id_servicio'];
            // buscar los registro que tengan el id_padre
            $modeloServicios = $this->lNegocioServicios->buscarIdPadre($idServicio);
            if (count($modeloServicios) > 0)
            { // hay hijos
                $array[] = array("id" => $fila['id_servicio'], "text" => $fila['nombre'], "children" => self::arbolServicios($modeloServicios));
            } else
            { //no hay hijos
                $array[] = array("id" => $fila['id_servicio'], "text" => $fila['nombre']);
            }
        }
        return $array;
    }

}
