<?php
 /**
 * Controlador Vigilanciaf02
 *
 * Este archivo controla la lógica del negocio del modelo:  Vigilanciaf02Modelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2021/03/10
 * @uses    RestWsVigilanciaControlador
 * @package AplicacionMovilInternos
 * @subpackage Controladores
 */
 namespace Agrodb\AplicacionMovilInternos\Controladores;
 use Agrodb\FormulariosInspeccion\Modelos\Vigilanciaf02LogicaNegocio;
 
class RestWsVigilanciaControlador extends BaseControlador 
{

	private $lNegocioVigilanciaf02 = null;
	
	/**
	 * Constructor
	 */
	function __construct(){
		$this->lNegocioVigilanciaf02 = new Vigilanciaf02LogicaNegocio();
		
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}		

	/**
	 * Guardar registros de vigilancia fitosanitaria
	 */
	public function guardarVigilancia(){


		$vigilancia = (array) json_decode(file_get_contents('php://input'));

		$arrayContenedor = [];
		$arrayOrdenes = [];
		
		foreach($vigilancia["detalle"] as $registro){

			$campos = array(
				"id" => $registro->id,
				"id_tablet" => $registro->idTablet,
				"codigo_provincia" => $registro->codigoProvincia,
				"nombre_provincia" => $registro->nombreProvincia,
				"codigo_canton" => $registro->codigoCanton,
				"nombre_canton" => $registro->nombreCanton,
				"codigo_parroquia" => $registro->codigoParroquia,
				"nombre_parroquia" => $registro->nombreParroquia,
				"nombre_propietario_finca" => $registro->nombrePropietarioFinca,
				"localidad_via" => $registro->localidadOvia,
				"coordenada_x" => $registro->coordenadaX,
				"coordenada_y" => $registro->coordenadaY,
				"coordenada_z" => $registro->coordenadaZ,
				"denuncia_fitosanitaria" => $registro->denuncia,
				"nombre_denunciante" => $registro->nombreDenunciante,
				"telefono_denunciante" => $registro->telefonoDenunciante,
				"direccion_denunciante" => $registro->direccionDenunciante,
				"correo_electronico_denunciante" => $registro->correoDenunciante,
				"especie_vegetal" => $registro->especie,
				"cantidad_total" => $registro->cantidadTotal,
				"cantidad_vigilada" => $registro->cantidadVigilancia,
				"unidad" => $registro->unidad,
				"sitio_operacion" => $registro->sitioOperacion,
				"condicion_produccion" => $registro->condicionProduccion,
				"etapa_cultivo" => $registro->etapaCultivo,
				"actividad" => $registro->actividad,
				"manejo_sitio_operacion" => $registro->manejoSitioOperacion,
				"ausencia_plaga" => $registro->presenciaPlagas,
				"plaga_diagnostico_visual_prediagnostico" => $registro->plagaPrediagnostico,
				"cantidad_afectada" => $registro->cantidadAfectada,
				"porcentaje_incidencia" => $registro->incidencia,
				"porcentaje_severidad" => $registro->severidad,
				"tipo_plaga" => $registro->tipoPlaga,
				"fase_desarrollo_plaga" => $registro->fasePlaga,
				"organo_afectado" => $registro->organoAfectado,
				"distribucion_plaga" => $registro->distribucionPlaga,
				"poblacion" => $registro->poblacion,
				"diagnostico_visual" => $registro->diagnosticoVisual,
				"descripcion_sintomas_p" => $registro->descripcionSintoma,
				"envio_muestra" => $registro->envioMuestra,
				"observaciones" => $registro->observaciones,
				"fecha_inspeccion" => $registro->fechaInspeccion,
				"usuario_id" => $registro->usuarioId,
				"usuario" => $registro->usuario,
				"tablet_id" => $registro->tabletId,
				"tablet_version_base" => $registro->tabletBase,
				"foto" => $registro->imagen,
				"longitud_imagen" => $registro->longitudImagen,
				"latitud_imagen" => $registro->latitudImagen,
				"altura_imagen" => $registro->alturaImagen,
			);

			$arrayContenedor[] = $campos;
			
			foreach($vigilancia["ordenes"] as $orden){
				if ($orden->idVigilancia == $registro->id){
					$camposLaboratorio = array(
						'id_vigilancia' => $orden->idVigilancia,
						'id_padre' => "",
						'id_tablet' => $registro->idTablet,
						'analisis' => $orden->analisisSolicitado,
						'codigo_muestra' => $orden->codigoMuestra,
						'conservacion' => $orden->conservacion,
						'tipo_muestra' => $orden->tipoMuestra,
					);

					$arrayOrdenes[] = $camposLaboratorio;
				}
			}
		}

		$id = $this->lNegocioVigilanciaf02->guardarVigilanciaTryCatch($arrayContenedor, $arrayOrdenes);

	}

}
