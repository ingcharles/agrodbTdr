<?php

/**
 * Librería para generar los formularios dinámicamente
 * http://itsolutionstuff.com/post/bootstrap-jquery-multiple-select-with-checkboxes-example-using-bootstrap-multiselectjs-pluginexample.html
 *
 * @author DATASTAR
 * @uses     FormularioDinamico
 * @package Laboratorios
 * @subpackage Controladores
 */

namespace Agrodb\Laboratorios\Controladores;

use Agrodb\Laboratorios\Modelos\LaboratoriosLogicaNegocio;
use Agrodb\Laboratorios\Modelos\ParametrosServiciosLogicaNegocio;
use Agrodb\Laboratorios\Modelos\ServiciosLogicaNegocio;
use Agrodb\Laboratorios\Modelos\TipoAnalisisLogicaNegocio;
use Agrodb\Laboratorios\Modelos\ArchivosAdjuntosLogicaNegocio;
use Agrodb\Core\Mensajes;
use Agrodb\Core\Constantes;

class FormularioDinamico extends BaseControlador
{

    private $lNegocioLaboratorios = null;
    private $columnas;  //html de columnas del tipo de análisis según formulario dinámico
    private $campos;    //html de campos del tipo de análisis según formulario dinámico
    public $etiqueta;   //html de etiquetas del tipo de análisis según formulario dinámico
    private $codigo_analisis;       //código del análisis
    private $codigoJSDinamico = ""; //código js si existe
    private $arrayCampos = array();  //array de campos encontrados
    private $columnasCampos = "";    //para el titulo de las columnas
    private $idLaboratorio;
    private $arrayColumnasCampos = array();
    private $agregarServicios = 0;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Para desplegar los campos de DATOS DE LA MUESTRA
     * @param type $idPadre id_laboratorio
     * @param type $idMuestra
     */
    public function camposMuestras($idPadre, $idMuestra = null)
    {
        echo $this->getCamposMuestras($idPadre, $idMuestra);
    }

    /**
     * Funcion recursiva para formar los campos de DATOS DE LA MUESTRA
     * @param type $idPadre
     * @param type $idMuestra
     * @return type
     */
    public function getCamposMuestras($idPadre, $idMuestra = null)
    {
        $html = "";
        //buscar los hijos
        $tipo = "MUESTRA";
        $this->lNegocioLaboratorios = new LaboratoriosLogicaNegocio();
        $tabla = $this->lNegocioLaboratorios->camposLaboratorio($idPadre, $tipo, $idMuestra, $this->usuarioInterno);
        foreach ($tabla as $fila)
        {
            if ($fila->nivel_acceso > 0 & !$this->usuarioInterno)
            {
                //si es externo 
            } else
            {
                //si es aplicado para todos (interno/externo)
                if (!empty($fila['codigo_ejecutable']) && $fila['codigo_ejecutable'] != null)
                {
                    $this->codigoJSDinamico .=$fila['codigo_ejecutable'];
                }

                //pregunto si tiene dependientes, es decir si existen registros con el fk_id_laboratorio el id_laboratorio
                $tabla2 = $this->lNegocioLaboratorios->camposLaboratorio($fila['id_laboratorio'], $tipo, $idMuestra, $this->usuarioInterno);
                $tiene_dependientes = count($tabla2);
                if ($tiene_dependientes > 0)
                {
                    $lbl = ($fila['obligatorio'] == 'SI') ? ' *' : "";
                    switch ($fila['tipo_campo'])
                    {
                        case "ETIQUETA":
                            $html.= "<fieldset><legend>" . $fila['nombre'] . " </legend>";
                            $html.= self::getCamposMuestras($fila['id_laboratorio'], $idMuestra);
                            $html.= "</fieldset>";
                            //Agregamos código js fuera den campos fieldset
                            $html.= $this->codigoJSDinamico;
                            $this->codigoJSDinamico = "";
                            break;
                        case "SUBETIQUETA":
                            if ($fila['visible_en'] != 'N')
                            {
                                $html.= "<fieldset class='fieldsetInterna'><legend class='legendInterna'>" . $fila['nombre'] . " </legend>";
                            } else
                            {
                                $html.= "<fieldset class='fieldsetInterna'><legend class='legendInterna'></legend>";
                            }

                            $html.= self::getCamposMuestras($fila['id_laboratorio'], $idMuestra);
                            $html.= "</fieldset>";
                            //Agregamos código js fuera den campos fieldset
                            $html.= $this->codigoJSDinamico;
                            $this->codigoJSDinamico = "";
                            break;
                        case 'CHECKLIST':
                            $atributos = ($fila['obligatorio'] == 'SI') ? 'required' : "";
                            $html.= '<div data-linea="' . (int) $fila['data_linea'] . '" >';
                            if ($fila['visible_en'] != 'N')
                            {
                                $html.= "<label>" . $fila['nombre'] . $lbl . "</label>";
                            }
                            $html.= '<select id="m_lista' . $fila['id_laboratorio'] . '" multiple="multiple" class="checklist" name="m_lista' . $fila['id_laboratorio'] . '[]" ' . $atributos . '>';
                            $html.= self::getCamposMuestras($fila['id_laboratorio'], $idMuestra);
                            $html.= "</select>\n";
                            $html.= "</div>";
                            $this->codigoJSDinamico = str_replace("#this", "#m_lista" . $fila['id_laboratorio'], $this->codigoJSDinamico);
                            break;
                        case 'COMBOBOX':
                            $atributos = ($fila['obligatorio'] == 'SI') ? 'required' : "";
                            $html.= '<div data-linea="' . (int) $fila['data_linea'] . '" >';
                            $html.= "<label>" . $fila['nombre'] . $lbl . "</label>";
                            $html.= '<select id="m_lista' . $fila['id_laboratorio'] . '" class="checklist" name="m_lista' . $fila['id_laboratorio'] . '[]" ' . $atributos . '>';
                            $html.= '<option value="">Seleccione...</option>';
                            $html.= self::getCamposMuestras($fila['id_laboratorio'], $idMuestra);
                            $html.= "</select>\n";
                            $html.= "</div>";
                            //Configuramos el código Jquery para un combobox
                            $this->codigoJSDinamico = str_replace("#this", "#m_lista" . $fila['id_laboratorio'], $this->codigoJSDinamico);
                            break;
                        default :
                            $html.= self::getCamposMuestras($fila['id_laboratorio'], $idMuestra);
                            break;
                    }
                } else
                { //no tiene dependientes, es ultimo nivel
                    $html.= $this->obtener_tipo($fila);
                }
            }
        }
        return $html;
    }

    /**
     * Guarda en varible el código js
     */
    public function codigoJSDinamico()
    {
        echo $this->codigoJSDinamico;
    }

    /**
     * Retorna html del tipo de campo, por lo general es para el último nivel
     * @param type $fila
     * @return string
     */
    function obtener_tipo($fila)
    {
        $tipo = $fila['tipo_campo'];    //tipo de campo
        $html = "";
        $lbl = ($fila['obligatorio'] == 'SI') ? ' *' : "";

        //formar los atributos
        $atributos = ($fila['obligatorio'] == 'SI') ? ' required ' : "";
        $attrs = explode(';', $fila->atributos);
        foreach ($attrs as $attr)
        {
            $pos = strpos($attr, 'max=hoy');
            if ($pos !== false)
            {
                $atributos.= " max = " . date('Y-m-d');
            } else
            {
                $atributos.= $attr;
            }
        }

        switch ($tipo)
        {
            case 'BOOLEANO':
                $selected = '';
                if ($fila['valor_usuario'] == "check")
                {
                    $selected = 'selected';
                }
                $html.= '<option value="' . $fila['id_laboratorio'] . '" ' . $selected . '>' . $fila['nombre'] . '</option>';
                break;
            case 'FECHA':

                $html.= '<div data-linea=' . (int) $fila['data_linea'] . '>';
                $html.= "<label>" . $fila['nombre'] . $lbl . "</label>";
                $html.= '<input type="date" id="m_texto' . $fila['id_laboratorio'] . '" name="m_texto' . $fila['id_laboratorio'] . '" value="' . $fila['valor_usuario'] . '" placeholder="' . $fila['descripcion'] . '" ' . $atributos . ' maxlength="10"/>';
                $html.= "</div>";
                //Configuramos en código Jquery para un campo de texto
                $this->codigoJSDinamico = str_replace("#this", "#m_texto" . $fila['id_laboratorio'], $this->codigoJSDinamico);
                break;
            case 'ENTERO':
                $html.= '<div data-linea=' . (int) $fila['data_linea'] . '>';
                $html.= "<label>" . $fila['nombre'] . $lbl . "</label>";
                $html.= '<input type="number" id="m_texto' . $fila['id_laboratorio'] . '" name="m_texto' . $fila['id_laboratorio'] . '" value="' . $fila['valor_usuario'] . '" placeholder="' . $fila['descripcion'] . '" ' . $atributos . ' maxlength="6" min="0"/>';
                $html.= "</div>";
                //Configuramos en código Jquery para un campo de texto
                $this->codigoJSDinamico = str_replace("#this", "#m_texto" . $fila['id_laboratorio'], $this->codigoJSDinamico);
                break;
            case 'CHECK':
                $html.= '<div data-linea=' . (int) $fila['data_linea'] . '>';
                $html.= "<label>" . $fila['nombre'] . $lbl . "</label>";
                $html.= '<input type="checkbox" id="m_texto' . $fila['id_laboratorio'] . '" name="m_texto' . $fila['id_laboratorio'] . '" value="' . $fila['valor_usuario'] . '" ' . $atributos . ' maxlength="6"/>';
                $html.= "</div>";
                //Configuramos en código Jquery para un campo de texto
                $this->codigoJSDinamico = str_replace("#this", "#m_texto" . $fila['id_laboratorio'], $this->codigoJSDinamico);
                break;
            case 'TEXTAREA':
                $html.= '<div data-linea=' . (int) $fila['data_linea'] . '>';
                $html.= "<label>" . $fila['nombre'] . $lbl . "</label>";
                $html.= "<textarea rows='2' cols='50' id='m_texto{$fila['id_laboratorio']}' name='m_texto{$fila['id_laboratorio']}' placeholder='' $atributos maxlength='256'>{$fila['valor_usuario']}</textarea>";
                $html.= "</div>";
                //Configuramos en código Jquery para un campo de texto
                $this->codigoJSDinamico = str_replace("#this", "#m_texto" . $fila['id_laboratorio'], $this->codigoJSDinamico);
                break;
            default :
                $html.= '<div data-linea=' . (int) $fila['data_linea'] . '>';
                $html.= "<label>" . $fila['nombre'] . $lbl . "</label>";
                $html.= '<input type="text" id="m_texto' . $fila['id_laboratorio'] . '" name="m_texto' . $fila['id_laboratorio'] . '" value="' . $fila['valor_usuario'] . '" placeholder="' . $fila['descripcion'] . '" ' . $atributos . $fila->atributos . ' maxlength="256"/>';
                $html.= "</div>";
                //Configuramos en código Jquery para un campo de texto
                $this->codigoJSDinamico = str_replace("#this", "#m_texto" . $fila['id_laboratorio'], $this->codigoJSDinamico);
                break;
        }
        return $html;
    }

    public function agregarServicios($idSolicitud, $idLaboratorio)
    {
        $this->agregarServicios = 1;
        //busco el tipo de solicitud
        $lNSolicitudes = new \Agrodb\Laboratorios\Modelos\SolicitudesLogicaNegocio();
        $buscaSolicitud = $lNSolicitudes->buscar($idSolicitud);
        $tipoSolicitud = $buscaSolicitud->getTipoSolicitud();
        $this->camposAnalisis($idLaboratorio, $tipoSolicitud);
    }

    /**
     * Retorna la tabla html con los campos para la IDENTIFICACION DE LAS MUESTRAS
     * @param type $idLaboratorio
     * @param type $tipoSolicitud
     */
    public function camposAnalisis($idLaboratorio, $tipoSolicitud = null)
    {
        $this->idLaboratorio = $idLaboratorio;
        $idSolicitud = $_POST['idSolicitud'];
        $servicios = $_POST['servicios'];   //String de servicios seleccioanados de ultimo nivel
        $filasTabla = "";
        $analisisSolicitados = "";
        // Verificar si fue seleccionado el tipo de análisis
        // Buscamos en la base de datos las información del tipo de análisis
        // segun lo que selecciono
        $lNServicios = new ServiciosLogicaNegocio();
        $arrayServicios = array_filter(explode(',', $servicios));
        $buscarServicio = $lNServicios->buscarLista(array('id_servicio' => $arrayServicios));

        $cantidades = array_filter(explode(',', $_POST['cantidades']));
        $arrayCantidades = array();
        foreach ($cantidades as $row)
        {
            $d = explode('-', $row);
            $arrayCantidades[$d[0]] = array('id' => $d[0], 'cantidad' => $d[1]);
        }

        $html = "";
        if (!empty($servicios))
        {
            $i = 1;
            foreach ($buscarServicio as $fila)
            {
                $this->arrayCampos = array();   //enserar por cada servicio
                //cantidad del analisis
                $cant = $arrayCantidades[$fila->id_servicio]['cantidad'];

                $this->codigo_analisis = "No informa";
                if ($fila->codigo_analisis != "")
                {
                    $this->codigo_analisis = $fila->codigo_analisis;
                }

                //Crear html para TIPO DE ANALISIS SOLICITADO
                //Enviamos el id del laboratorio y el id del servicio
                $this->camposAnalisisHtml($idLaboratorio, $fila['id_servicio'], $cant, $idSolicitud, $tipoSolicitud);
                $this->formarFilasCampos($fila['id_servicio'], $cant, $idSolicitud);

                //html para tabla de información de análisis
                $analisisSolicitados .= "<tr><td>" . $this->codigo_analisis . "</td>";
                $analisisSolicitados .= "<td>" . $fila->rama_nombre . " </td>";
                $analisisSolicitados .= "<td>" . $fila->parametro . "</td>";
                $analisisSolicitados .= "<td>" . $fila->metodo . "</td></tr>";

                $filasTabla.=$this->campos;
                $i ++;
            }

            $tablaTipoAnalisisSolicitado = $this->etiqueta . '<table id="detalleSolicitud">
                    <thead>
                    ' . $this->columnas . '
                    </thead>
                    <tr>' . $filasTabla . '</tr>
                </table>';
            $html = $tablaTipoAnalisisSolicitado;
            // Tipo de análisis detallado
            if (!empty($analisisSolicitados))
            {
                $html.= "<hr/>";
                $html.= "<table>";
                $html.= "<thead>";
                $html.= "<tr><th>CÓDIGO</th><th>ANÁLISIS</th><th>PARÁMETRO</th><th>MÉTODO</th></tr>";
                $html.= "</thead>";
                $html.= $analisisSolicitados;
                $html.= "</table>";
            }
        }
        echo $html;
    }

    /**
     * Para formar las filas de campos por analisis
     * @param type $idServicio
     * @param type $cantidad
     * @param type $idSolicitud
     */
    public function formarFilasCampos($idServicio, $cantidad, $idSolicitud)
    {
        //repetir los campos según la cantidad
        $trs = '';
        for ($i = 1; $i <= $cantidad; $i++)
        {
            $campos = '';
            $codigoUsuMuestra = '';
            //formar los campos si existe
            foreach ($this->arrayCampos as $row)
            {
                $valor_usuario = "";
                //obtener el valor del usuario de la tabla tipo_analisis
                $idTipoAnalisis = "";
                if ($idSolicitud != null)
                {
                    $lnTipoAnalisis = new TipoAnalisisLogicaNegocio();
                    $buscaTipoAnalisis = $lnTipoAnalisis->tipoAnalisis($idSolicitud, $row['id_servicio'], $row['id_laboratorio'], $i);
                    $valor_usuario = "";

                    if ($buscaTipoAnalisis->count() > 0)
                    {
                        $fila = $buscaTipoAnalisis->current();
                        $valor_usuario = $fila->valor_usuario;
                        $codigoUsuMuestra = $fila->codigo_usu_muestra;
                        $idTipoAnalisis = $fila->id_tipo_analisis;
                    }
                }

                //si el servicio es un predeteminado no se visualiza, por tanto no poner atributo requerido       
                if ($this->casoEspecialServicio($idServicio, Constantes::SER_PREDETERMINADO))
                {
                    $atributos = '';
                } else
                {
                    $atributos = $row['atributos'];
                }
                $campos.="<td>";
                if ($this->agregarServicios == 1)
                {
                    $campos.= $valor_usuario;
                } else
                {
                    //              laboratorio                     servicio                num_mues     tipo analsisi si existe
                    $nombreCampo = $row['id_laboratorio'] . '_' . $row['id_servicio'] . '_' . $i . '_' . $idTipoAnalisis;
                    switch ($row['tipo_campo'])
                    {
                        case "COMBOBOX":
                            $campos.= '<select id="a_texto' . $nombreCampo . '" name="a_texto' . $nombreCampo . '" ' . $atributos . ' class="' . $row['clase'] . '"> ' . $this->comboDinamico($row['id_laboratorio'], $valor_usuario) . '</select>';
                            break;
                        case "FECHA":
                            $campos.= '<input type="date" id="a_texto' . $nombreCampo . '" name="a_texto' . $nombreCampo . '"  placeholder="' . $row['descripcion'] . '" ' . $atributos . ' value="' . $valor_usuario . '" class="' . $row['clase'] . '"/>';
                            break;
                        case "ENTERO":
                            $campos.= '<input type="number" id="a_texto' . $nombreCampo . '" name="a_texto' . $nombreCampo . '"  placeholder="' . $row['descripcion'] . '" ' . $atributos . ' value="' . $valor_usuario . '" class="' . $row['clase'] . '"/>';
                            break;
                        case 'DECIMAL':
                            $campos.= '<input type="number" id="a_texto' . $nombreCampo . '" name="a_texto' . $nombreCampo . '"  placeholder="' . $row['descripcion'] . '" ' . $atributos . ' value="' . $valor_usuario . '" step="0.01" value="0.00" placeholder="0.00" min="0.01" lang="en" class="' . $row['clase'] . '"/>';
                            break;
                        case "PROVINCIA":
                            $campos.= '<select id="a_texto' . $nombreCampo . '" name="a_texto' . $nombreCampo . '"> ' . $this->provincias($valor_usuario) . '</select>';
                            break;
                        case "CRONOGRAMA":
                            $campos.= '<select id="a_texto' . $nombreCampo . '" name="a_texto' . $nombreCampo . '"> ' . $this->comboCronograma($row['id_laboratorio'], $row['rama']) . '</select>';
                            break;
                        case "OCULTO":
                            $campos.= '<input type="hidden" id="a_texto' . $nombreCampo . '" name="a_texto' . $nombreCampo . '"  placeholder="' . $row['descripcion'] . '" ' . $atributos . ' value="AUXILIAR" class="' . $row['clase'] . '"/>';
                            break;
                        default:
                            $campos.= '<input type="text" id="a_texto' . $nombreCampo . '" name="a_texto' . $nombreCampo . '"  placeholder="' . $row['descripcion'] . '" ' . $atributos . ' value="' . $valor_usuario . '" class="' . $row['clase'] . '"/>';
                            break;
                    }
                }
                $campos.='</td>';
            }

            //formar la fila
            $tas_display = '';
            $tas_requerido = '';
            if ($this->casoEspecialServicio($idServicio, Constantes::SER_PREDETERMINADO))
            {
                $tas_display = 'display: none;';
            } else
            {
                $tas_requerido = 'required';
            }

            //campo codigo_usu_muestra
            if ($this->agregarServicios == 1)
            {
                $codigoUsuMuestra = "<td>$codigoUsuMuestra</td>";
            } else
            {
                $res = $this->atributosCodigoCampoMuestra($this->usuarioInterno, $this->idLaboratorio);
                if ($res['visible'] == 'false')
                {
                    $codigoUsuMuestra = '<td style="display: none;">'
                            . '<input type="text" id="codigo_usu_muestra" name="codigo_usu_muestra_' . $idServicio . '[]"  placeholder="" value="' . $codigoUsuMuestra . '" maxlength="32" style="text-transform:uppercase;"/>'
                            . '</td>';
                } else
                {
                    $codigoUsuMuestra = '<td><input type="text" id="codigo_usu_muestra" name="codigo_usu_muestra_' . $idServicio . '[]" class="verificar_' . $idServicio . '" onblur="fn_verificar(this,' . $idServicio . ')" placeholder="" value="' . $codigoUsuMuestra . '" maxlength="32" ' . $tas_requerido . ' style="text-transform:uppercase;"/></td>';
                }
            }
            if ($this->agregarServicios == 1)
            {
                $campos.='<td style="text-align: center"><input type="checkbox" name="muestras[]" value="' . $fila->codigo_usu_muestra . '" class="clsSleccionarMuestras"/></td>';
            }

            $trs.= "<tr style='$tas_display'>"
                    . "<td>$this->codigo_analisis</td>"
                    . $codigoUsuMuestra
                    . $campos
                    . "</tr>";
        }

        $this->formarEtiquetas();

        $res = $this->atributosCodigoCampoMuestra($this->usuarioInterno, $this->idLaboratorio);
        if ($res['visible'] == 'false')
        {
            $attrC = "<th style = display:none>{$res['etiqueta']}</th>";
        } else
        {
            $attrC = "<th>{$res['etiqueta']}</th>";
        }

        //titulo columnas TIPO DE ANÁLISIS SOLICITADO
        if ($this->agregarServicios == 1)
        {
            $this->columnas = '<tr><th>ANÁLISIS SOLICITADO</th>' . $attrC . $this->columnasCampos
                    . '<th>Seleccionar Muestra</br><input type="checkbox" id="chkSeleccionarMuestras" value="" onclick="fn_seleccionarMuestras()"/></th></tr>';
        } else
        {
            $this->columnas = '<tr><th>ANÁLISIS SOLICITADO</th>' . $attrC . $this->columnasCampos . '</tr>';
        }
        //cuerpo tabla TIPO DE ANÁLISIS SOLICITADO
        $this->campos = $trs;
    }

    /**
     * Formulario dinámico para formar TIPO DE ANALISIS SOLICITADO
     * @param type $idPadre id_laboratorio
     * @param type $idServicio
     * @param type $cantidad
     * @throws \Exception
     */
    public function camposAnalisisHtml($idPadre, $idServicio, $cantidad, $idSolicitud = null, $tipoSolicitud = null)
    {
        if (!isset($idPadre) || $idPadre == null)
        {
            throw new \Exception('Clase: FormularioDinamico. El ID padre del laboratorio no existe o no fue seleccionado');
        }
        $tipo = "ANALISIS";
        $this->columnasCampos = "";
        $this->lNegocioLaboratorios = new LaboratoriosLogicaNegocio();
        $tabla = $this->lNegocioLaboratorios->camposLaboratorio($idPadre, $tipo, null, $this->usuarioInterno);

        $this->etiqueta;
        foreach ($tabla as $fila)
        {
            if ($fila->nivel_acceso > 0 & !$this->usuarioInterno)   //si es externo 
            {
                
            } else if ($fila->nivel_acceso == 1)    //unicamente usuario interno
            {
                //si es FRUTOS y es diferente de cargueras
                if ($this->casoEspecialServicio($idServicio, Constantes::SER_ENTO_PNMMF) & $fila->visible_en !== 'CA')
                {
                    $this->formarArrayCampos($fila, $idServicio, $cantidad, $idSolicitud, $tipoSolicitud);
                }
                if (!$this->casoEspecialServicio($idServicio, Constantes::SER_ENTO_PNMMF) & $tipoSolicitud == Constantes::tipo_SO()->MULTIUSUARIO & $fila->visible_en == 'CA')
                {
                    $this->formarArrayCampos($fila, $idServicio, $cantidad, $idSolicitud, $tipoSolicitud);
                }
                if ($fila->tipo_campo == 'CRONOGRAMA')
                {
                    $this->formarArrayCampos($fila, $idServicio, $cantidad, $idSolicitud, $tipoSolicitud);
                }
            } else  //si es aplicado para todos (interno/externo)
            {
                if ($fila->visible_en !== 'CA')
                {
                    $this->formarArrayCampos($fila, $idServicio, $cantidad, $idSolicitud, $tipoSolicitud);
                }
            }
        }
    }

    /**
     * 
     * @param type $fila
     * @param type $idServicio
     * @param type $cantidad
     * @param type $idSolicitud
     * @param type $tipoSolicitud
     */
    public function formarArrayCampos($fila, $idServicio, $cantidad, $idSolicitud, $tipoSolicitud)
    {
        $atributos = $fila['obligatorio'] === 'SI' ? 'required' : "";
        switch ($fila['tipo_campo'])
        {
            case "ETIQUETA":    //ejm: TIPO DE ANÁLISIS SOLICITADO
                $this->etiqueta = "<fieldset><legend>" . $fila['nombre'] . " </legend>";
                self::camposAnalisisHtml($fila['id_laboratorio'], $idServicio, $cantidad, $idSolicitud, $tipoSolicitud);
                break;
            case "SUBETIQUETA":
                echo "<fieldset class='fieldsetInterna'><legend class='legendInterna'>" . $fila['nombre'] . " </legend>";
                $this->camposAnalisisHtml($fila['id_laboratorio'], $idServicio, $cantidad, $idSolicitud, $tipoSolicitud);
                break;
            default:    //Tipo de campo. Ejm: TEXTO (Pre-diagnóstico, Descripción de sintomatología/daños)
                $clase = "class_" . $fila->id_laboratorio;
                $this->arrayCampos[] = array(
                    'id_laboratorio' => $fila['id_laboratorio'],
                    'id_servicio' => $idServicio,
                    'descripcion' => $fila['descripcion'],
                    'atributos' => $atributos,
                    'valor_usuario' => $fila['valor_usuario'],
                    'tipo_campo' => $fila['tipo_campo'],
                    'obligatorio' => $fila['obligatorio'],
                    'rama' => $fila['rama'],
                    'clase' => $clase);
                $lbl = '';
                if ($fila['obligatorio'] === 'SI')
                {
                    $lbl = '*';
                }
                $this->arrayColumnasCampos[$fila->id_laboratorio] = array(
                    'tipo_campo' => $fila['tipo_campo'],
                    'etiqueta' => $fila->nombre,
                    'obligatorio' => $fila['obligatorio'],
                );
                break;
        }
    }

    /**
     * Funcion para formar los encabezados de la columnas de tipo de analisis
     */
    public function formarEtiquetas()
    {
        foreach ($this->arrayColumnasCampos as $claveIdLaboratorio => $fila)
        {
            if ($this->agregarServicios == 1)
            {
                $this->columnasCampos .= "<th>{$fila['etiqueta']}</th>";
            } else
            {
                $lbl = '';
                if ($fila['obligatorio'] === 'SI')
                {
                    $lbl = '*';
                }
                $clase = "class_" . $claveIdLaboratorio;
                if ($fila['tipo_campo'] == 'OCULTO')
                {
                    $this->columnasCampos .= "<th style='display: none'>{$fila['etiqueta']}</th>";
                } else
                {
                    if ($fila['tipo_campo'] === 'COMBOBOX')
                    {
                        $this->columnasCampos .= "<th>{$fila['etiqueta']} $lbl<br><select onchange=fn_repetir(this,'$clase')>"
                                . $this->comboDinamico($claveIdLaboratorio) . "</select></th>";
                    } else
                    {
                        $this->columnasCampos .= "<th>{$fila['etiqueta']} $lbl<br><input type='text' onkeyup=fn_repetir(this,'$clase')></th>";
                    }
                }
            }
        }
    }

    /**
     * Crea un combo para la seccion tipo de análisis
     * @param type $idPadre
     * @return string
     */
    public function comboDinamico($idPadre, $valorUsuario = null)
    {
        $combo = $this->lNegocioLaboratorios->buscarItemCombo($idPadre);

        $opcionesHtml = "";

        $opcionesHtml .= '<option value="">Seleccionar....</option>';
        foreach ($combo as $item)
        {
            $opcionesHtml.= "<option data-id=\"$item->nombre\" value=\"$item->nombre\"";
            if ($valorUsuario == $item->nombre)
            {
                $opcionesHtml.= " selected";
            }
            $opcionesHtml.= ">$item->nombre</option>";
        }
        return $opcionesHtml;
    }

    /**
     * Llena un combo con provincias
     * @param type $provincia
     * @return type
     */
    public function provincias($provincia)
    {
        $opcionesHtml = $this->comboProvinciasEc($provincia);

        return $opcionesHtml;
    }

    /**
     * Crea un combro con los ingredientes activos del cuerdo al cronograma de postregistro
     * @param type $idPadre
     * @param type $rama
     * @return type
     */
    public function comboCronograma($idPadre, $rama)
    {
        $idLaboratorio = explode(',', $rama);
        $opcionesHtml = $this->comboCronogramaPostregistro($idLaboratorio[1]);
        return $opcionesHtml;
    }

    /**
     * Crea los campos de archivo que requieren en cada servicio
     *
     * @param Integer $idServicio
     */
    public function parametrosServicio()
    {
        $array = array();
        $idServiciosNuevo = $_POST['servicios'];
        $idServiciosGuardados = isset($_POST['servicios_guardados']) ? $_POST['servicios_guardados'] : array();
        $idDetalleSolicitud = $_POST['id_detalle_solicitud'];
        if ($idServiciosNuevo != "")
        {
            $array = array_unique(array_filter(explode(',', $idServiciosNuevo)));
        }
        $servicios = array_merge($array, $idServiciosGuardados);
        if (isset($_POST['servicios']))
        {
            $this->lNegocioParametros = new ParametrosServiciosLogicaNegocio();
            //Buscar los servicios que tienen parametros
            $tablaServicios = $this->lNegocioParametros->buscarServicioParametros($servicios, $idDetalleSolicitud);
            $html = '';
            foreach ($tablaServicios as $filaServicio)
            {
                $tabla = $this->lNegocioParametros->buscarParametrosPorServicio($filaServicio->id_servicio, $idDetalleSolicitud);
                $html.= "<fieldset>";
                $html.= "<legend>$filaServicio->rama_nombre</legend>";
                foreach ($tabla as $fila)
                {
                    $atributos = ($fila['obligatorio'] == 'SI') ? 'required' : "";
                    $lbl = ($fila['obligatorio'] == 'SI') ? ' *' : "";
                    $nombre = $fila['id_laboratorio'] . '_' . $fila['id_servicio'] . '_' . $fila['id_parametros_servicio'];
                    $archivo = "";
                    switch ($fila['tipo_campo'])
                    {
                        case "ARCHIVO":
                            //si existe el archivo adjunto del parametros
                            if (!empty($fila->id_archivos_adjuntos))
                            {
                                $ruta = URL_DIR_FILES . '/' . $fila->nombre_archivo;
                                //Creamos el código HTML para imprimir la opción de descarga del certificado y poder borrar para subir uno nuevo
                                $archivo = '<div id="div_' . $fila->id_archivos_adjuntos . '" class="row" >
                                <div class="col-xs-6 col-md-4">' . Constantes::EXISTE_ADJUNTO_SOLICITUD . '</div>
                                <div class="col-xs-6 col-md-4">' . $this->descargaPdf($ruta) . '</div>
                                <div class="col-xs-6 col-md-4"><button  type="button" onclick="fn_eliminarParametroArchivo(' . $fila->id_archivos_adjuntos . ')">' . Constantes::BOTON_ELIMINAR . '</button></div>
                            </div>';
                            }

                            //forma la ruta del archivo: raiz/codigo_lab/anio
                            $ruta = URL_DIR_FILES . '/' . $fila['codigo_laboratorio'] . '/' . date("Y");
                            $subruta = $fila['codigo_laboratorio'] . '/' . date("Y") . '/';
                            $html.= "<fieldset>";
                            $html.= '<div data-linea="' . $fila['id_parametros_servicio'] . '">';
                            $html.= '<label for="file">' . $fila['nombre_parametro'] . $lbl . '</label>';
                            $html.= '<input type="file" class="archivo" accept="application/pdf" ' . $atributos . '/>';
                            $html.= '<div class="estadoCarga">En espera de archivo... (Tama&nacute;o m&aacute;ximo' . ini_get('upload_max_filesize') . 'B)</div>';
                            $html.= '<label >' . $fila['descripcion'] . '</label>';
                            $html.= '<button type="button" id="btnSubirArchivo' . $nombre . '" onclick="fn_subirArchivo(' . $fila['id_laboratorio'] . ',' . $fila['id_servicio'] . ',' . $fila['id_parametros_servicio'] . ",'" . $fila['codigo_laboratorio'] . "'" . ')" class="subirArchivo adjunto" data-rutaCarga="' . $ruta . '">Subir archivo</button>';
                            $html.= '<input type="hidden" id="p_archi' . $nombre . '" name="p_archi' . $nombre . '" value="" ' . $atributos . ' class="clsParametroServicio"/>';
                            $html.= '<input type="hidden" id="subruta' . $nombre . '" name="subruta' . $nombre . '" value="' . $subruta . '"/>';
                            $html.= '</div>';
                            $html.= $archivo;
                            $html.= "</fieldset>";
                            break;
                    }
                }
                $html.= "</fieldset>";
            }
            echo $html;
        }
    }

    public function eliminarParametroArchivo($idArchivosAdjuntos)
    {
        $mensaje = array();
        $lNArchivosAdjuntos = new ArchivosAdjuntosLogicaNegocio();
        $lNArchivosAdjuntos->borrar($idArchivosAdjuntos);
        $mensaje['estado'] = 'EXITO';
        $mensaje['mensaje'] = 'Eliminado con exito';
        echo json_encode($mensaje);
    }

    /**
     * Transforma una cadena con el formato adecuado para ponerlo en un formulario.
     *
     * @param String $cadena
     * @return String
     */
//    protected function etiqueta($cadena)
//    {
//        return strip_tags($cadena);
//    }
}
