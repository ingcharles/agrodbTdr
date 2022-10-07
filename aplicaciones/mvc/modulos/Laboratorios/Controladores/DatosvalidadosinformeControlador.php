<?php

/**
 * Controlador Datosvalidadosinforme
 *
 * Este archivo controla la lógica del negocio del modelo:  DatosvalidadosinformeModelo y  Vistas
 *
 * @author DATASTAR
 * @uses     DatosvalidadosinformeControlador
 * @package Laboratorios
 * @subpackage Controladores
 */

namespace Agrodb\Laboratorios\Controladores;

use Agrodb\Laboratorios\Modelos\DatosvalidadosinformeLogicaNegocio;
use Agrodb\Laboratorios\Modelos\DatosvalidadosinformeModelo;
use Agrodb\Laboratorios\Modelos\ParametrosLaboratoriosLogicaNegocio;
use Agrodb\Laboratorios\Modelos\ParametrosLaboratoriosModelo;
use Agrodb\Laboratorios\Modelos\ArchivoInformeAnalisisLogicaNegocio;
use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class DatosvalidadosinformeControlador extends BaseControlador
{

    private $lNegocioDatosvalidadosinforme = null;
    private $modeloDatosvalidadosinforme = null;
    private $modeloParametrosLaboratorios = null;
    private $accion = null;
    public $datosInforme = "";
    private $listaClientes;
    private $accionModificar;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioDatosvalidadosinforme = new DatosvalidadosinformeLogicaNegocio();
        $this->modeloDatosvalidadosinforme = new DatosvalidadosinformeModelo();
        $this->modeloParametrosLaboratorios = new ParametrosLaboratoriosModelo();
        set_exception_handler(array($this, 'manejadorExcepciones'));
    }

    /**
     * Método de inicio del controlador
     */
    public function index()
    {
        require APP . 'Laboratorios/vistas/listaDatosValidadosInformeVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo Datosvalidadosinforme";
        require APP . 'Laboratorios/vistas/formularioDatosValidadosInformeVista.php';
    }

    /**
     * Método para registrar en la base de datos -Datosvalidadosinforme
     */
    public function guardar()
    {
        $this->lNegocioDatosvalidadosinforme->guardar($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    public function actualizar()
    {
        $this->lNegocioDatosvalidadosinforme->actualizar($_POST);
        Mensajes::exito(Constantes::INFORME_MODIFICADO_CON_EXITO);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: Datosvalidadosinforme
     */
    public function editar()
    {
        $this->accion = "Editar Datosvalidadosinforme";
        $this->modeloDatosvalidadosinforme = $this->lNegocioDatosvalidadosinforme->buscar($_POST["id"]);
        require APP . 'Laboratorios/vistas/formularioDatosValidadosInformeVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - Datosvalidadosinforme
     */
    public function borrar()
    {
        $this->lNegocioDatosvalidadosinforme->borrar($_POST['elementos']);
    }

    /**
     * Trae los informes del laboratorio aprobados
     */
    public function comboInformes()
    {
        $this->accionModificar = $_POST['id'];
        require APP . 'Laboratorios/vistas/formularioDatosValidadosInformeVista.php';
    }

    /**
     * Para cargar los informes segun el laboratorio_provincia
     * @param type $idLaboratoriosProvincia
     */
    public function cargarInformes($idLaboratoriosProvincia)
    {
        $informe = new ArchivoInformeAnalisisLogicaNegocio();
        $buscaDatos = $informe->buscarClientesInforme($idLaboratoriosProvincia, Constantes::estado_OT()->EN_APROBACION);
        $array = array();
        foreach ($buscaDatos as $fila)
        {
            $array[] = array("id" => $fila->id_informe_analisis, "text" => $fila->nombre_informe);
        }
        echo json_encode($array);
    }

    /**
     * Para contruir html de etiquetas
     */
    public function etiquetas()
    {
        $tipo = $_POST['tipo'];
        $idOrden = $_POST['idOrden'];
        $this->accion = "Campos disponibles para generar las etiquetas";
        $lNEtiquetas = new DatosvalidadosinformeLogicaNegocio();
        $resultado = $lNEtiquetas->buscarCamposEtiquetas($idOrden, $tipo);
        $this->tablaHtmlCamposEtiquetas($resultado);
    }

    /**
     * Construye la tabla html de etiquetas
     * @param type $tabla
     */
    public function tablaHtmlCamposEtiquetas($tabla)
    {
        //buscamos el formato gusradado en parámetros laboratorios
        $parametros = new ParametrosLaboratoriosLogicaNegocio();

        $datosParametros = $parametros->buscarLista(array("id_laboratorio" => $this->laboratorioUsuario(), "codigo" => "FP_FETIQMUE", "estado" => "ACTIVO"));
        $camposOcultos = "";
        $idParametro = "";


        foreach ($datosParametros as $parametro)
        {
            $cClientes = $parametro->valor_aux1;
            $cGeneral = $parametro->valor_aux2;
            $cEspecifico = $parametro->valor_aux3;
            $idParametro = $parametro->id_parametros_laboratorio;
        }
        //Si no existe el parámetro creamos los campos vacios

        $contador = 0;
        foreach ($tabla as $fila)
        {
            $idOrden = $fila->id_orden_trabajo;
            $filaEtiqueta = $fila->etiqueta;
            $checked = '<div class="checkbox"><label><input type="checkbox" id="' . $fila->codigo . '" name="' . $fila->codigo . '" onclick="fn_seleccionar()">' . $fila->etiqueta . '</label></div>';
            if ($fila->codigo == "cdigodemuestrala")
            {
                $checked = '<div class="checkbox"><label><input type="checkbox" id="' . $fila->codigo . '" name="' . $fila->codigo . '"    checked onclick="fn_seleccionar()">' . $fila->etiqueta . '</label></div>';
            }

            $this->datosInforme .= '<tr>
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $checked . '</b></td> 
                   </tr>';
        }

        echo $this->datosInforme . '<tr><td><input type ="hidden" id="idOrderTrabajo" name ="idOrderTrabajo" value=\'' . $idOrden . '\'/>     </td>' .
        '<td><input type ="hidden" id="id_parametros_laboratorio" name ="id_parametros_laboratorio" value=\'' . $idParametro . '\'/> </td></tr>';
    }

    /**
     * Construye el código HTML para desplegar la lista de - Datosvalidadosinforme
     */
    public function tablaHtmlDatosvalidadosinforme($tabla)
    {
        $contador = 0;
        $mensaje = "";
        foreach ($tabla as $fila)
        {
            $id = $fila->id_datos_validados_informe;
            $checked = '<input type="checkbox"  id="estado_inf[' . $id . ']" name="estado_inf[' . $id . ']"  value="INACTIVO" />';

            if ($fila->estado_inf != "ACTIVO")
            {
                $checked = '<div style="background-color: #f3310c;text-align: center;"><input type="checkbox"  id="estado_inf[' . $id . ']" name="estado_inf[' . $id . ']"  value="ACTIVO"  /></div>';
                $mensaje = '<td colspan="7"> <div style="background-color: #f3310c;text-align: center;">
            <label class="form-check-label" for="defaultCheck1">Campo deshabilitado para el informe</label>
        </div></td> ';
            }
            $this->datosInforme .= '<tr>
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['etiqueta'] . '</b></td>'
                    . '<td><input type="text" id="etiqueta[' . $id . ']" name="etiqueta[' . $id . ']"  /></td>
                   <td>' . $fila->valor . '</td>
                  <td><input type="text" id="valor[' . $id . ']" name="valor[' . $id . ']" /></td>
                  <td><input type="text" id="orden[' . $id . ']" name="orden[' . $id . ']" placeholder="' . $fila->orden . '" size="3"/></td>
                  <td>' . $checked . '</td>
                   </tr>';
        }

        $this->datosInforme .= $mensaje;
    }

    public function camposSeccionInforme($codigoSeccion, $idInformeAnalisis)
    {
        if ($codigoSeccion == 'ANALISIS')
        {
            //Se puede modificar unicamente los datos de la OT
            $filtro = array("id_informe_analisis = " . $idInformeAnalisis . " AND visible_inf=1 AND (tipo like '%OTANALISIS%' OR tipo like '%MRANALISIS%') ORDER BY orden_informe ASC");
             
        } else
        {
            $filtro = array("id_informe_analisis = " . $idInformeAnalisis . " AND visible_inf=1 AND tipo like '%".$codigoSeccion."%' ORDER BY orden_informe ASC");
        }

        $datos = $this->lNegocioDatosvalidadosinforme->buscarLista($filtro);
        $opcionesHtml = "";
        $html = "";
        foreach ($datos as $fila)
        {
            $valorEstado = '';
            if ($fila['estado_inf'] == 'ACTIVO')
            {
                $valorEstado = ' checked';
            }
            $estado = '<input type="checkbox" id="estado_inf"' . $fila['id_datos_validados_informe'] . ' name="estado_inf"' . $fila['id_datos_validados_informe'] . '  onclick="cambiarEstado(this,' . $fila['id_datos_validados_informe'] . ')" ' . $valorEstado . ' />';

            $html .= "<tr >'"
                    . "<td>" . $fila['etiqueta'] . "</td>"
                    . "<td> <input type=\"text\" id=\"campoInforme" . $fila['id_datos_validados_informe'] . "\" name=\"" . $fila['id_datos_validados_informe'] . "\" value=\"" . $fila['valor'] . "\" onblur='fn_cambiar(this," . $fila['id_datos_validados_informe'] . ")' size='80'/></td>"
                    . "</tr>";
        }
        echo $html;
    }

}
