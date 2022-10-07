<?php

 /**
 * Controlador Base
 *
 * Este archivo contiene métodos comunes para todos los controladores 
 *
 * @property  AGROCALIDAD
 * @author    Carlos Anchundia
 * @date      2019-09-09
 * @uses      BaseControlador
 * @package   NotificacionesFitosanitarias
 * @subpackage Controladores
 */
namespace Agrodb\NotificacionesFitosanitarias\Controladores;

session_start();
use Agrodb\Programas\Modelos\AccionesLogicaNegocio;
use Agrodb\Core\Comun;
use Agrodb\Core\Mensajes;
use Agrodb\Core\Constantes;
 
 
class BaseControlador extends Comun
{
	public $itemsFiltrados = array();
	public $codigoJS = null;

	/**
	* Constructor
	*/
	function __construct() {
		parent::usuarioActivo();
		//Si se requiere agregar código concatenar la nueva cadena con  ejemplo $this->codigoJS.=alert('hola');
		$this->codigoJS = \Agrodb\Core\Mensajes::limpiar();
	}
	public function crearTabla() {
		$tabla = "//No existen datos para mostrar...";
		if (count($this->itemsFiltrados) > 0) {
			$tabla = '$(document).ready(function() {
			construirPaginacion($("#paginacion"),' . json_encode($this->itemsFiltrados, JSON_UNESCAPED_UNICODE) . ');
			$("#listadoItems").removeClass("comunes");
			});';
		}

	return $tabla;
	}
        
        function articleComun($arrayParametros, $opt){ 
		switch ($opt) {

			case 1:
				$contenido = '<article
								id="' . $arrayParametros['idLista'] . '"
								class="item"
								data-rutaAplicacion="' . $arrayParametros['rutaAplicacion'] . '"
								data-opcion="' . $arrayParametros['opcion'] . '"
								ondragstart="drag(event)"
								draggable="true"
								data-destino="' . $arrayParametros['destino'] . '"
								<span><small> ' . $arrayParametros['texto1'] . ' </small></span>
					 			<span class="ordinal">' . $arrayParametros['contador'] . '</span>
								<aside ></aside>
								</article>';
			break;
                }

		return $contenido;
	}
        
        public function crearAccionBotonesCF($arrayParametros){ 
            $botones = "";
            $ruta = 'detalleItem';
            $contador = 0;
            $estilo = '_nuevoCf';
            $botones =  '<a href="#" id="' . $arrayParametros['id_lista_notificacion'] . '" class="' . $estilo . '" data-destino="' . $ruta . '" data-opcion="Notificaciones/nuevo" data-rutaAplicacion="mvc/NotificacionesFitosanitarias">Nuevo</a>';
            $botones .= '<a href="#" id="_actualizar" class="_actualizar" data-destino="' . $ruta . '" data-opcion="" data-rutaAplicacion="mvc/NotificacionesFitosanitarias">Actualizar</a>';
            $botones .= '<a href="#" id="_seleccionar" class="_seleccionar" data-destino="' . $ruta . '" data-opcion="" data-rutaAplicacion="mvc/NotificacionesFitosanitarias">'
                        .'<div id="cantidadItemsSeleccionados">0</div>'.'Seleccionar'.'</a>';
            $botones .= '<a href="#" id="' . $arrayParametros['id_lista_notificacion'] . '"  class="_cargaMasiva" data-destino="' . $ruta . '" data-opcion="listaNotificacion/cargaNotificacion" data-rutaAplicacion="mvc/NotificacionesFitosanitarias">Carga Masiva</a>';
            return $botones . '<div id="estado"></div>';
               
	}
        
        
        public function crearAccionBotonesLN($arrayParametros){ 
            $botones = "";
            $ruta = 'detalleItem';
            $contador = 0;
//            $estilo = '_nuevoCf';
//            $botones =  '<a href="#" id="' . $arrayParametros['id_lista_notificacion'] . '" class="' . $estilo . '" data-destino="' . $ruta . '" data-opcion="listaNotificacion/nuevaNotificacion" data-rutaAplicacion="mvc/NotificacionesFitosanitarias">Nuevo</a>';
            $botones .= '<a href="#" id="_actualizar" class="_actualizar" data-destino="' . $ruta . '" data-opcion="" data-rutaAplicacion="mvc/NotificacionesFitosanitarias">Actualizar</a>';
            $botones .= '<a href="#" id="_seleccionar" class="_seleccionar" data-destino="' . $ruta . '" data-opcion="" data-rutaAplicacion="mvc/NotificacionesFitosanitarias">'
                .'<div id="cantidadItemsSeleccionados">0</div>'.'Seleccionar'.'</a>';
            
            
            return $botones . '<div id="estado"></div>';
               
	}
        
        public function comboTipoDocumento($opcion = null)
    {
        $combo = "";
        
        if ($opcion == "Adiciones de urgencia") {
            $combo .= '<option value="Todos">Todos</option>';
            $combo .= '<option value="Adiciones de urgencia" selected="selected">Adiciones de urgencia</option>';
            $combo .= '<option value="Adiciones ordinarias">Adiciones ordinarias</option>';
            $combo .= '<option value="Correcciones de urgencia">Correcciones de urgencia</option>';
            $combo .= '<option value="Correcciones ordinarias">Correcciones ordinarias</option>';
            $combo .= '<option value="Notificación de medidas de urgencia">Notificación de medidas de urgencia</option>';
            $combo .= '<option value="Notificación ordinaria">Notificación ordinaria</option>';
            $combo .= '<option value="Reconocimiento de equivalencia">Reconocimiento de equivalencia</option>';
        } else if ($opcion == "Adiciones ordinarias") {
            $combo .= '<option value="Todos">Todos</option>';
            $combo .= '<option value="Adiciones de urgencia">Adiciones de urgencia</option>';
            $combo .= '<option value="Adiciones ordinarias" selected="selected">Adiciones ordinarias</option>';
            $combo .= '<option value="Correcciones de urgencia">Correcciones de urgencia</option>';
            $combo .= '<option value="Correcciones ordinarias">Correcciones ordinarias</option>';
            $combo .= '<option value="Notificación de medidas de urgencia">Notificación de medidas de urgencia</option>';
            $combo .= '<option value="Notificación ordinaria">Notificación ordinaria</option>';
            $combo .= '<option value="Reconocimiento de equivalencia">Reconocimiento de equivalencia</option>';
        } else if ($opcion == "Correcciones de urgencia") {
            $combo .= '<option value="Todos">Todos</option>';
            $combo .= '<option value="Adiciones de urgencia">Adiciones de urgencia</option>';
            $combo .= '<option value="Adiciones ordinarias">Adiciones ordinarias</option>';
            $combo .= '<option value="Correcciones de urgencia" selected="selected">Correcciones de urgencia</option>';
            $combo .= '<option value="Correcciones ordinarias">Correcciones ordinarias</option>';
            $combo .= '<option value="Notificación de medidas de urgencia">Notificación de medidas de urgencia</option>';
            $combo .= '<option value="Notificación ordinaria">Notificación ordinaria</option>';
            $combo .= '<option value="Reconocimiento de equivalencia">Reconocimiento de equivalencia</option>';
        } else if ($opcion == "Correcciones ordinarias") {
            $combo .= '<option value="Todos">Todos</option>';
            $combo .= '<option value="Adiciones de urgencia">Adiciones de urgencia</option>';
            $combo .= '<option value="Adiciones ordinarias">Adiciones ordinarias</option>';
            $combo .= '<option value="Correcciones de urgencia">Correcciones de urgencia</option>';
            $combo .= '<option value="Correcciones ordinarias" selected="selected">Correcciones ordinarias</option>';
            $combo .= '<option value="Notificación de medidas de urgencia">Notificación de medidas de urgencia</option>';
            $combo .= '<option value="Notificación ordinaria">Notificación ordinaria</option>';
            $combo .= '<option value="Reconocimiento de equivalencia">Reconocimiento de equivalencia</option>';
        } else if ($opcion == "Notificación de medidas de urgencia") {
            $combo .= '<option value="Todos">Todos</option>';
            $combo .= '<option value="Adiciones de urgencia">Adiciones de urgencia</option>';
            $combo .= '<option value="Adiciones ordinarias">Adiciones ordinarias</option>';
            $combo .= '<option value="Correcciones de urgencia">Correcciones de urgencia</option>';
            $combo .= '<option value="Correcciones ordinarias">Correcciones ordinarias</option>';
            $combo .= '<option value="Notificación de medidas de urgencia" selected="selected">Notificación de medidas de urgencia</option>';
            $combo .= '<option value="Notificación ordinaria">Notificación ordinaria</option>';
            $combo .= '<option value="Reconocimiento de equivalencia">Reconocimiento de equivalencia</option>';
        } else if ($opcion == "Notificación ordinaria") {
            $combo .= '<option value="Todos">Todos</option>';
            $combo .= '<option value="Adiciones de urgencia">Adiciones de urgencia</option>';
            $combo .= '<option value="Adiciones ordinarias">Adiciones ordinarias</option>';
            $combo .= '<option value="Correcciones de urgencia">Correcciones de urgencia</option>';
            $combo .= '<option value="Correcciones ordinarias">Correcciones ordinarias</option>';
            $combo .= '<option value="Notificación de medidas de urgencia">Notificación de medidas de urgencia</option>';
            $combo .= '<option value="Notificación ordinaria" selected="selected">Notificación ordinaria</option>';
            $combo .= '<option value="Reconocimiento de equivalencia">Reconocimiento de equivalencia</option>';
        } else if ($opcion == "Reconocimiento de equivalencia") {
            $combo .= '<option value="Todos">Todos</option>';
            $combo .= '<option value="Adiciones de urgencia">Adiciones de urgencia</option>';
            $combo .= '<option value="Adiciones ordinarias">Adiciones ordinarias</option>';
            $combo .= '<option value="Correcciones de urgencia">Correcciones de urgencia</option>';
            $combo .= '<option value="Correcciones ordinarias">Correcciones ordinarias</option>';
            $combo .= '<option value="Notificación de medidas de urgencia">Notificación de medidas de urgencia</option>';
            $combo .= '<option value="Notificación ordinaria">Notificación ordinaria</option>';
            $combo .= '<option value="Reconocimiento de equivalencia" selected="selected">Reconocimiento de equivalencia</option>';
        } else if ($opcion == "Área temática") {
            $combo .= '<option value="">Seleccione...</option>';
            $combo .= '<option value="Sanidad Animal">Sanidad Animal</option>';
            $combo .= '<option value="Sanidad Vegetal">Sanidad Vegetal</option>';
            $combo .= '<option value="Inocuidad de los alimentos">Inocuidad de los alimentos</option>';
            $combo .= '<option value="Registro de Insumos Agropecuarios">Registro de Insumos Agropecuarios</option>';
            $combo .= '<option value="Laboratorios">Laboratorios</option>';
       } else {
            $combo .= '<option value="Todos" selected="selected">Todos</option>';
            $combo .= '<option value="Adiciones de urgencia">Adiciones de urgencia</option>';
            $combo .= '<option value="Adiciones ordinarias">Adiciones ordinarias</option>';
            $combo .= '<option value="Correcciones de urgencia">Correcciones de urgencia</option>';
            $combo .= '<option value="Correcciones ordinarias">Correcciones ordinarias</option>';
            $combo .= '<option value="Notificación de medidas de urgencia">Notificación de medidas de urgencia</option>';
            $combo .= '<option value="Notificación ordinaria">Notificación ordinaria</option>';
            $combo .= '<option value="Reconocimiento de equivalencia">Reconocimiento de equivalencia</option>';
        }
        
        return $combo;
    }
    /**
     * 
     */
     
    public function comboEstados($opcion = null)
    {
        $combo = "";
        $arrayDatos  = array('Respondido','No respondido','Vigente','Todos');
        $combo .= '<option value="">Seleccione...</option>';
        foreach ($arrayDatos as $value) {
            if($opcion == $value){
                $combo .= '<option value="'.$value.'" selected="selected">'.$value.'</option>';
            }else{
                $combo .= '<option value="'.$value.'">'.$value.'</option>';
            }
        }
        return $combo;
    }
    
    public function comboNumeros($maximo,$valor=null){
    	
    	$combo = '<option value="">Seleccionar....</option>';
    	for ($i=1; $i<=$maximo; $i++ ){
    		if($valor == $i){
    			$combo .= '<option value="' . $i . '" selected>' . $i. '</option>';
    		}else{
    			$combo .= '<option value="' . $i . '" >' . $i. '</option>';
    		}
    	}
    	return $combo;
    }
}
