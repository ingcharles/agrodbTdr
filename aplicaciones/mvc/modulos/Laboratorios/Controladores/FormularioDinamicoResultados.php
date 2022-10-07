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

use Agrodb\Laboratorios\Modelos\CamposResultadosInformesLogicaNegocio;
use Agrodb\Laboratorios\Modelos\ResultadoAnalisisLogicaNegocio;
use Agrodb\Core\Constantes;

class FormularioDinamicoResultados extends BaseControlador
{

    private $lNCamposResultadosInformes = null;
    private $lNResultadoAnalisis = null;
    public $etiqueta;
    public $nColumnasResultado = 0;
    private $idServicio;
    private $idServicioNivel0;
    private $idRecepcionMuestras;
    private $vistaPrevia = false;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNCamposResultadosInformes = new CamposResultadosInformesLogicaNegocio();
        $this->lNResultadoAnalisis = new ResultadoAnalisisLogicaNegocio();
    }

    /**
     * Para desplegar los campos de DATOS DE LA MUESTRA
     * @param type $idPadre id_laboratorio
     * @param type $idMuestra
     */
    public function camposResultados($idServicio, $idRecepcionMuestras, $rama = null)
    {
        $this->idServicio = $idServicio;
        $this->idServicioNivel0 = explode(',', $rama)[0];
        $this->idRecepcionMuestras = $idRecepcionMuestras;
        $campos = $this->getCamposResultados();
        return $campos;
    }

    /**
     * Para desplegar los campos de DATOS DE LA MUESTRA
     * @param type $idPadre id_laboratorio
     * @param type $idMuestra
     */
    public function camposResultadosVistaPrevia($idServicio)
    {
        $this->idServicio = $idServicio;
        $this->vistaPrevia = TRUE;
        $campos = $this->getCamposResultados();
        return $campos;
    }

    /**
     * Funcion recursiva para formar los campos de DATOS DE LA MUESTRA
     * @param type $idPadre
     * @param type $disenio Tipo de diseño V->Vertical
     * @return type
     */
    public function getCamposResultados($idPadre = null)
    {
        $html = "";
        $atributos = "";
        $ncampos = 0;

        if ($this->vistaPrevia)
        {
            $tabla = $this->lNCamposResultadosInformes->camposPorServicioVistaPrevia($this->idServicio, $idPadre);
        } else
        {
            $tabla = $this->lNCamposResultadosInformes->camposPorServicio($this->idServicioNivel0, $this->idRecepcionMuestras, $idPadre);
        }

        foreach ($tabla as $fila)
        {
            $requerido = "";
            $idResultadoAnalisis = "";
            $resultadoAnalisis = "";
            if ($fila->obligatorio == 'SI')
            {
                $atributos .= 'required';
                $requerido = "O_";
            }
            if (isset($fila->id_resultado_analisis))
            {
                $idResultadoAnalisis = $fila->id_resultado_analisis;
                $resultadoAnalisis = $fila->resultado_analisis;
            }

            switch ($fila->tipo_campo)
            {
                case "ETIQUETA":
                    $html .= "<fieldset class='fieldsetInterna'><legend class='legendInterna'>" . $fila->nombre . " </legend>";
                    $html .= self::getCamposResultados($fila->id_campos_resultados_inf);
                    $html .= "</fieldset>";
                    break;
                case "SUBETIQUETA":
                    $html .= "<fieldset class='fieldsetMuestras'><legend class='legendMuestras'>" . $fila->nombre . " </legend>";
                    $html .= self::getCamposResultados($fila->id_campos_resultados_inf);
                    $html .= "</fieldset>";
                    break;
                case 'CHECKLIST':
                    $nombre = "{$requerido}r_lista-{$this->idRecepcionMuestras}-{$fila->id_campos_resultados_inf}-{$idResultadoAnalisis}[]";
                    $html .= '<div class="form-group" >';
                    $html .= '<label class="col-lg-4 control-label">' . $fila->nombre . "</label>";
                    $html .= '<div class="col-lg-6">';
                    $html .= '<select multiple="multiple" class="form-control"  name="' . $nombre . '">';
                    $html .= self::getCamposResultados($fila->id_campos_resultados_inf);
                    $html .= "</select>";
                    $html .= "</div>";
                    $html .= "</div>";
                    break;
                case 'COMBOBOX':
                    $nombre = "{$requerido}r_lista-{$this->idRecepcionMuestras}-{$fila->id_campos_resultados_inf}-{$idResultadoAnalisis}[]";
                    $html .= '<div class="form-group" >';
                    $html .= '<label class="col-lg-4 control-label">' . $fila->nombre . "</label>";
                    $html .= '<div class="col-lg-6">';
                    $html .= '<select class="form-control"  name="' . $nombre . '">';
                    $html .= self::getCamposResultados($fila->id_campos_resultados_inf);
                    $html .= "</select>\n";
                    $html .= "</div>";
                    $html .= "</div>";
                    break;
                case 'BOOLEANO':
                    $selected = '';
                    if ($resultadoAnalisis !== NULL)
                    {
                        $selected = 'selected';
                    }
                    $html .= '<option value="' . $fila->id_campos_resultados_inf . '-' . $idResultadoAnalisis . '" ' . $selected . '>' . $fila->nombre . '</option>';
                    $html .= self::getCamposResultados($fila->id_campos_resultados_inf);
                    break;
                case 'ENTERO' :
                    $nombre = "{$requerido}r_texto-{$this->idRecepcionMuestras}-{$fila->id_campos_resultados_inf}-{$idResultadoAnalisis}";
                    $html .= '<div class="form-group">';
                    $html .= '<label class="col-lg-4 control-label">' . $fila->nombre . '</label>';
                    $html .= '<div class="col-lg-6">';
                    $html .= '<input type="number" class="form-control" name="' . $nombre . '" value="' . $resultadoAnalisis . '" placeholder="' . $fila->descripcion . '" ' . $atributos . ' />';
                    $html .= "</div>";
                    $html .= "</div>";
                    $html .= self::getCamposResultados($fila->id_campos_resultados_inf);
                    break;
                case 'TEXTO' :
                    $nombre = "{$requerido}r_texto-{$this->idRecepcionMuestras}-{$fila->id_campos_resultados_inf}-{$idResultadoAnalisis}";
                    $html .= '<div class="form-group">';
                    $html .= '<label class="col-lg-4 control-label">' . $fila->nombre . '</label>';
                    $html .= '<div class="col-lg-6">';
                    $html .= '<input type="text" class="form-control" name="' . $nombre . '" value="' . $resultadoAnalisis . '" placeholder="' . $fila->descripcion . '" ' . $atributos . ' />';
                    $html .= "</div>";
                    $html .= "</div>";
                    $html .= self::getCamposResultados($fila->id_campos_resultados_inf);
                    break;
                default :
                    $html .= self::getCamposResultados($fila->id_campos_resultados_inf);
                    break;
            }
        }
        //Para saber cuanto es el máximo de columnas se debe tener en la lista de muestras
        if ($ncampos > $this->nColumnasResultado)
        {
            $this->nColumnasResultado = $ncampos;
        }
        return $html;
    }

    /**
     * Formar los campos para nuevo/editar resultado tipo VERTICAL
     * @param type $idServicio
     * @param type $idRecepcionMuestras
     * @param type $numResultado
     * @return string
     */
    public function camposParaResultado($idServicio, $idRecepcionMuestras, $numResultado = null)
    {
        if ($numResultado == null)
        {
            $arrayParametros = array(
                'estado_registro' => 'ACTIVO',
                'id_servicio' => $idServicio,
                'nivel' => 1    //nivel 1 contiene los campos a llenar
            );
            $buscaCamposResultado = $this->lNCamposResultadosInformes->buscarLista($arrayParametros, 'orden');
        } else
        {
            $buscaCamposResultado = $this->lNCamposResultadosInformes->buscarCamposParaResultado($idServicio, $idRecepcionMuestras, $numResultado);
        }

        $ncampos = 0;
        $html2 = "";
        $row = 0;
        foreach ($buscaCamposResultado as $fila)
        {
            $lbl = "";
            $atributos = "";
            $requerido = "";
            $clase = "class_" . $idServicio . "_" . $ncampos;
            if ($fila['obligatorio'] == 'SI')
            {
                $atributos .= 'required';
                $requerido = "O_";
                $lbl = " *";
            }

            $idResultadoAnalisis = null;
            $valor = '';
            if (isset($fila->id_resultado_analisis))
            {
                $idResultadoAnalisis = $fila->id_resultado_analisis;
                $valor = $fila->resultado_analisis;
            }

            switch ($fila->tipo_campo)
            {
                case 'CHECKLIST':
                    $nombre = "{$requerido}r_lista-{$idRecepcionMuestras}-{$fila->id_campos_resultados_inf}-$idResultadoAnalisis";
                    $html = '<div class="form-group col-md-6">';
                    $html .= '<label class="col-lg-6 control-label">' . $fila->nombre . $lbl . '</label>';
                    $html .= '<div class="col-lg-6">';
                    $html .= '<select multiple="multiple" class="form-control ' . $clase . '" name="' . $nombre . '[]" id="' . $nombre . '" ' . $atributos . '>';
                    $combo = $this->comboVertical($fila->id_campos_resultados_inf, $idRecepcionMuestras, $numResultado);
                    $html .= $combo;
                    $html .= "</select>";
                    $html .= "</div>";
                    $html .= "</div>";
                    $ncampos++;
                    break;
                case 'COMBOBOX':
                    $nombre = "{$requerido}r_lista-{$idRecepcionMuestras}-{$fila->id_campos_resultados_inf}-$idResultadoAnalisis";
                    $html = '<div class="form-group col-md-6">';
                    $html .= '<label class="col-lg-6 control-label">' . $fila->nombre . $lbl . '</label>';
                    $html .= '<div class="col-lg-6">';
                    $html .= '<select class="form-control ' . $clase . '" name="' . $nombre . '[]" id="' . $nombre . '" ' . $atributos . '>';
                    $html .= '<option value="">Seleccione...</option>';
                    $combo = $this->comboVertical($fila->id_campos_resultados_inf, $idRecepcionMuestras, $numResultado);
                    $html .= $combo;
                    $html .= "</select>";
                    $html .= "</div>";
                    $html .= "</div>";
                    $ncampos++;
                    break;
                case 'TEXTAREA':
                    if ($numResultado !== null)
                    {
                        $res = $this->buscarResultado($fila->id_campos_resultados_inf, $idRecepcionMuestras, $numResultado);
                        $idResultadoAnalisis = $res[0];
                        $valor = $res[1];
                    } else
                    {
                        $valor = $fila->valor_defecto;
                    }
                    $nombre = "{$requerido}r_texto-{$idRecepcionMuestras}-{$fila->id_campos_resultados_inf}-$idResultadoAnalisis";
                    $html = '<div class="form-group col-md-6">';
                    $html .= '<label class="col-lg-6 control-label">' . $fila->nombre . $lbl . '</label>';
                    $html .= '<div class="col-lg-6">';
                    $html .= '<textarea class="form-control ' . $clase . '" name="' . $nombre . '" id="' . $nombre . '" ' . $atributos . ' max="256">';
                    $html .= $valor;
                    $html .= "</textarea>";
                    $html .= "</div>";
                    $html .= "</div>";
                    $ncampos++;
                    break;
                case 'DECIMAL':
                    if ($numResultado !== null)
                    {
                        $res = $this->buscarResultado($fila->id_campos_resultados_inf, $idRecepcionMuestras, $numResultado);
                        $idResultadoAnalisis = $res[0];
                        $valor = $res[1];
                    } else
                    {
                        $valor = $fila->valor_defecto;
                    }
                    $nombre = "{$requerido}r_texto-{$idRecepcionMuestras}-{$fila->id_campos_resultados_inf}-$idResultadoAnalisis";
                    $html = '<div class="form-group col-md-6">';
                    $html .= '<label class="col-lg-6 control-label">' . $fila->nombre . $lbl . '</label>';
                    $html .= '<div class="col-lg-6">';
                    $html .= '<input type="number" name="' . $nombre . '" id="' . $nombre . '" value="' . $valor . '" placeholder="' . $fila['nombre'] . '" ' . $atributos . ' class="form-control ' . $clase . '" max="256" step="0.01" value="0.00" placeholder="0.00" min="0.01" lang="en"/>';
                    $html .= "</div>";
                    $html .= "</div>";
                    $ncampos++;
                    break;
                case ($fila->tipo_campo == 'TEXTO' || $fila->tipo_campo == 'ENTERO' || $fila->tipo_campo == 'FECHA') :
                    if ($numResultado !== null)     //si existe un resultado
                    {
                        $res = $this->buscarResultado($fila->id_campos_resultados_inf, $idRecepcionMuestras, $numResultado);
                        $idResultadoAnalisis = $res[0];
                        $valor = $res[1];
                    } else
                    {
                        $valor = $fila->valor_defecto;
                    }
                    $tipoHtml = Constantes::tipo_html($fila->tipo_campo);
                    $nombre = "{$requerido}r_texto-{$idRecepcionMuestras}-{$fila->id_campos_resultados_inf}-$idResultadoAnalisis";
                    $html = '<div class="form-group col-md-6">';
                    $html .= '<label class="col-lg-6 control-label">' . $fila->nombre . $lbl . '</label>';
                    $html .= '<div class="col-lg-6">';
                    $html .= '<input type="' . $tipoHtml . '" name="' . $nombre . '" id="' . $nombre . '" value="' . $valor . '" placeholder="' . $fila['nombre'] . '" ' . $atributos . ' class="form-control ' . $clase . '" max="256"/>';
                    $html .= "</div>";
                    $html .= "</div>";
                    $ncampos++;
                    break;
                default :
                    break;
            }
            if ($row == 0)
            {
                $html2.= "<div class='row'>$html";
                $row = 1;
            } else
            {
                $html2.= "$html</div>";
                $row = 0;
            }
        }
        return $html2;
    }

    /**
     * 
     * @param type $idCamposResultadosInf
     * @param type $numResultado
     * @return type
     */
    public function buscarResultado($idCamposResultadosInf, $idRecepcionMuestras, $numResultado)
    {
        $arrayParametros = array(
            'id_campos_resultados_inf' => $idCamposResultadosInf,
            'id_recepcion_muestras' => $idRecepcionMuestras,
            'num_resultado' => $numResultado);
        $buscarResultado = $this->lNResultadoAnalisis->buscarLista($arrayParametros);
        $fila = $buscarResultado->current();
        return array($fila->id_resultado_analisis, $fila->resultado_analisis);
    }

    /**
     * Para HORIZONTAL
     * @param type $idPadre
     * @return type
     */
    public function formarCampos($idRecepcionMuestras, $idServicio, $camposJSON, $numResultado)
    {
        $titulos = array();
        $html = "";
        $ncampos = 0;
        $data = json_decode($camposJSON, TRUE);
        foreach ($data as $fila)
        {
            if ($fila['estado_registro'] == 'ACTIVO')
            {
                $atributos = "";
                $requerido = "";
                $clase = "class_" . $idServicio . "_" . $ncampos;
                if ($fila['obligatorio'] == 'SI')
                {
                    $atributos .= 'required';
                    $requerido = "O_";
                }
                $idResultadoAnalisis = $fila['id_resultado'];
                if ($fila['id_resultado'] == 0)
                {
                    $idResultadoAnalisis = null;
                }
                switch ($fila['tipo_campo'])
                {
                    case 'CHECKLIST':
                        $nombre = "{$requerido}r_lista-{$idRecepcionMuestras}-{$fila['id_campos']}-$idResultadoAnalisis-$numResultado";
                        $ncampos++;
                        $html .= '<td>';
                        $html .= '<select multiple="multiple" class="checklist ' . $clase . '" name="' . $nombre . '[]" id="' . $nombre . '" ' . $atributos . '>';
                        $combo = $this->combo($fila['opciones']);
                        $html .= $combo;
                        $html .= "</select>";
                        $html .= "</td>";
                        $titulos[$ncampos] = $fila['nombre'] . "<br>"
                                . "<select class='' onchange=fn_repetirCombo(this,'$clase')>"
                                . "$combo</select>";
                        $ncampos++;
                        break;
                    case 'COMBOBOX':
                        $nombre = "{$requerido}r_lista-{$idRecepcionMuestras}-{$fila['id_campos']}-$idResultadoAnalisis-$numResultado";
                        $ncampos++;
                        $html .= '<td>';
                        $html .= '<select class="' . $clase . '" name="' . $nombre . '[]" id="' . $nombre . '" ' . $atributos . '>';
                        $html .= '<option value="">Seleccione...</option>';
                        $combo = $this->combo($fila['opciones']);
                        $html .= $combo;
                        $html .= "</select>";
                        $html .= "</td>";
                        $titulos[$ncampos] = $fila['nombre'] . "<br>"
                                . "<select onchange=fn_repetir(this,'$clase')>"
                                . "$combo</select>";
                        $ncampos++;
                        break;
                    case 'TEXTAREA':
                        $nombre = "{$requerido}r_texto-{$idRecepcionMuestras}-{$fila['id_campos']}-$idResultadoAnalisis-$numResultado";
                        $html .= '<td>';
                        $html .= '<textarea class="' . $clase . '" name="' . $nombre . '" id="' . $nombre . '" ' . $atributos . '>';
                        $html .= $fila['valor'];
                        $html .= "</textarea>";
                        $html .= "</td>";
                        $titulos[$ncampos] = $fila['nombre'] . "<br><input type='text' onkeyup=fn_repetir(this,'$clase')>";
                        $ncampos++;
                        break;
                    case ($fila['tipo_campo'] == 'TEXTO' || $fila['tipo_campo'] == 'ENTERO' || $fila['tipo_campo'] == 'FECHA') :
                        $tipoHtml = Constantes::tipo_html($fila['tipo_campo']);
                        $nombre = "{$requerido}r_texto-{$idRecepcionMuestras}-{$fila['id_campos']}-$idResultadoAnalisis-$numResultado";
                        $html .= '<td>';
                        $html .= '<input type="' . $tipoHtml . '" name="' . $nombre . '" value="' . $fila['valor'] . '" placeholder="' . $fila['nombre'] . '" ' . $atributos . ' class="' . $clase . '"/>';
                        $html .= "</td>";
                        if ($fila['tipo_campo'] == 'FECHA')
                        {
                            $titulos[$ncampos] = $fila['nombre'] . "<br><input type='$tipoHtml' onkeyup=fn_repetir(this,'$clase') onchange=fn_repetir(this,'$clase')>";
                        } else
                        {
                            $titulos[$ncampos] = $fila['nombre'] . "<br><input type='$tipoHtml' onkeyup=fn_repetir(this,'$clase')>";
                        }
                        $ncampos++;
                        break;
                    default :
                        break;
                }
            }
        }
        return array($html, $titulos);
    }

    /**
     * Construye combo html para el campo específico
     * @param type $idPadre
     * @param type $idRecepcionMuestras
     * @return string
     */
    public function combo($opciones)
    {
        $opt = '';
        foreach ($opciones as $fila)
        {
            $selected = "";
            if ($fila['valor'] == 'check')
            {
                $selected = 'selected';
            }
            $opt .= '<option data-id="' . $fila['id_campos'] . '" value="' . $fila['id_campos'] . '" ' . $selected . '>' . $fila['nombre'] . '</option>';
        }
        return $opt;
    }

    /**
     * Retorna las opciones de cecklist o combobox
     * @param type $idCamposResultadosInf
     * @param type $idRecepcionMuestra
     * @param type $numResultado
     * @return string
     */
    public function comboVertical($idCamposResultadosInf, $idRecepcionMuestra, $numResultado)
    {
        $datos = '';
        $tabla = $this->lNCamposResultadosInformes->camposResultadosInfomes($idCamposResultadosInf, $idRecepcionMuestra, $numResultado);
        foreach ($tabla as $fila)
        {
            $selected = '';
            if ($fila->resultado_analisis !== NULL)
            {
                $selected = 'selected';
            }
            $datos .= '<option data-id="' . $fila->id_campos_resultados_inf . '" value="' . $fila->id_campos_resultados_inf . '" ' . $selected . '>' . $fila->nombre . '</option>';
        }
        return $datos;
    }

    /**
     * Transforma una cadena con el formato adecuado para ponerlo en un formulario.
     *
     * @param String $cadena
     * @return String
     */
    protected function etiqueta($cadena)
    {
        $mayusculas = 'ÁÉÍÓÚÑ:';
        $minusculas = 'áéíóúñ ';
        $cadena = strtr($cadena, $mayusculas, $minusculas);
        $cadena = str_replace("&#10003;", " ", $cadena); // Este código fue puesto para poner un check en los reportes
        $cadena = strtolower(strip_tags($cadena));
        return ucfirst($cadena);
    }

}
