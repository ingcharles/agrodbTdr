<?php

/**
 * @see http://php.net/manual/es/language.oop5.constants.php
 * @see http://php.net/manual/es/language.oop5.anonymous.php
 */

namespace Agrodb\Core;

class Constantes
{
	
	/**
     * Nombre de la institucion
     * */
    const AGROCALIDAD_SIGNIFICADO = 'AGENCIA DE REGULACIÓN Y CONTROL FITO Y ZOOSANITARIO - AGROCALIDAD';

    /**
     * Varios
     */
    const FILTRO_USADO_PARA = 'COD_LABORATORIOS';   //Código para filtrar las direcciones de diagnóstico del sistema GUIA
    const IVA = 12; //XX%
    const LAB_LN_TUMBACO = 259;                     //Id de g_catalogos.localizacion
    
    //Usado para campos dinámicos
    public static $opciones = array('TEXTO' => 'text', 'ENTERO' => 'number', 'FECHA' => 'date');
    public static function tipo_html($key){
        return self::$opciones[$key];
    }
    
    /**
     * Filtro para unidades de medidas de g_catalogos.unidades_medidas
     * clasificacion puede se array o string, ejm: 'clasificacion' => array('DP_COMP','DOSIS_IA') o 'clasificacion' => 'DP_COMP'
     * @var type 
     */
    const FILTRO_UNIDADES_MEDIDAS = array('clasificacion' => array('DP_COMP','DOSIS_IA'));

    /**
     * Botones
     */
    const BOTON_GUARDAR = 'Guardar';
    const BOTON_ELIMINAR = 'Eliminar';
    const BOTON_BUSCAR = 'Buscar';

    /**
     * Mensajes comunes
     */
    const GUARDADO_CON_EXITO = 'Guardado con éxito';
    const INFORME_MODIFICADO_CON_EXITO = 'Informe modificado con éxito';
    const GUARDADO_CON_EXITO_RECEPCION = 'Guardado las muestras con éxito. Pendiente de activación de la orden de trabajo';
    const ACTIVADA_CON_EXITO_RECEPCION = 'La orden de trabajo fue activada con éxito y fue enviada a la bandeja del Responsable Técnico';
    const COPIADO_CON_EXITO = 'Copiado con éxito';
    const SOLICITUD_ENVIADA = 'Solicitud enviada con éxito';
    const INFORME_CREADO_CON_EXITO = 'Informe creado con éxito';
    const INFORME_ENVIADO_CON_EXITO = 'Informe eviado con éxito';
    const ERROR_ENVIAR_INFORME = 'Error al enviar el informe';
    const ELIMINADO_CON_EXITO = 'Eliminado con éxito';
    const ANULADO_CON_EXITO = 'Anulado con éxito';
    const ERROR_AL_GUARDAR = 'Error al guardar el registro';
	const ERROR_DUPLICADO = 'Ya existen datos registrados, no se puede duplicar la información';
	const ERROR_CANTIDAD_ACEPTADA = 'Ya se ha ingresado el número máximo de elementos permitidos.';
	const ERROR_PARTIDA_DIFERENTE = 'La partida arancelaria es distinta a las ingresadas, debe ingresar el mismo valor para continuar.';
    const CAMPO_VACIO = 'No informa';
    const ENTERO_VACIO = null;
    const FECHA_VACIO = null;
    const DECIMAL_VACIO = null;
    const ERROR_TIEMPO_RESPUESTA = 'No existe el tiempo estimado o no cumple la condición. No se puede agregar el servicio.';
    const ERROR_CAMPO_RESPUESTA = 'No exiten campos de resultados comfigurados para este servicio';
    const ERROR_CREAR_ORDEN_TRABAJO = "Error al generar la Orden de trabajo";
    const ERROR_CREAR_INFORME = "Error al generar el informe";
    const ERROR_CREAR_ETIQUETAS = "Error al generar las etiquetas";
    const INF_PERMISO_LABORATORIO = "Este laboratorio no tiene permiso para la acción";
    const ERROR_CREAR_BD = "Error al generar el archvo excel";
    const ERROR_GENERAR_PROFORMA = "Error al generar la proforma";
    const NO_FORMULARIO_RESULTADO = "No existe campos configurados para el formulario de resultados de análisis";
	const ARCHIVO_VACIO = "El archivo se encuentra vacío";
    const ARCHIVO_MAL_CONSTRUIDO = "El archivo contiene infomación fuera del rango establecido";
	const AREA_VACIA = "Todos los campos de las áreas deben estar completos.";

    /**
     * PROFORMA
     */
    const PROFORMA_CODIGO ="PGC/LA/03-FO01";
    const PROFORMA_REVISION ="Rev. 4";
    
    /**
     * Estados de un registro
     */
    const ESTADO_ACTIVO = 'ACTIVO';
    const ESTADO_INACTIVO = 'DESACTIVADO';
    const ESTADO_PENDIENTE = 'PENDIENTE DE ACTIVACIÓN';

    /**
     * Notificación para activar firma electrónica
     */
    const EMAIL_FE_ASUNTO = 'Registrar contraseña de firma electrónica';
    const EMAIL_FE_MENSAJE = 'Se ha registrado una Firma Electrónica asociada a esta cuenta de correo electrónico en el sistema GUIA módulo de Laboratorios, se requiere que ingrese al siguiente enlace para actualizar su clave personal de la firma y activar la cuenta. Para más información se puede comunicar con el administrador del sistema GUIA.';
    const EMAIL_FE_VACIO = 'El usuario de la firma electrónica no dispone de un correo electrónico necesario para registrar su clave de la firma electrónica.';
    const MENSAJE_ACTIVACION = "ACTIVACIÓN REALIZADA CON ÉXITO";
    const MENSAJE_FIRMAR = "<H2>VERIFIQUE! LA SIGUIENTE INFORMACIÓN:</H2> <b>Propietario de la firma:</b> %usuario% <br> <b>Estado:</b> %estado%";
    const ERROR_MENSAJE_FIRMAR = "La firma no se encuentra activa: ";
    const RAZON_FIRMA = "Informe de resultado de Laboratorio";
    const ERROR_FIRMA_REGISTRADA = "Error: Ya existe una firma registrada con esta cédula";
    const ERROR_FIRMA_INFORME = "Error: Al firmar el informe";
    const INFORME_FIRMADO_EXITO = "Informe firmado con éxito";
    /**
     * Enviar notificaciones al cliente de forma manual. Asunto y mensaje pone el que envía
     */
    const EMAIL_NCM_VACIO = 'El usuario no dispone de un correo electrónico.';

    /**
     * Notificación sobre muestras no idoneas
     */
    const EMAIL_MNI_ASUNTO = 'Muestra no idónea';
    const EMAIL_MNI_MENSAJE = 'Se ha reportado una muestra no idónea.';
    const EMAIL_MNI_VACIO = 'El usuario no dispone de un correo electrónico.';

    /**
     * Notificación sobre envío de informes
     */
    const EMAIL_INF_ASUNTO = 'Informe';
    const EMAIL_INF_MENSAJE = 'Se envía informe de análisis.';
    const EMAIL_INF_VACIO = 'El usuario no dispone de un correo electrónico.';
    
    /**
     * Notificación sobre derivación de órdenes de trabajo
     */
    const EMAIL_DOT_ASUNTO = 'Derivación de orden de trabajo';
    const EMAIL_DOT_MENSAJE = 'La orden de trabajo se ha derivado a otro Laboratorio.';
    const EMAIL_DOT_VACIO = '';
    
    /**
     * Notificación sobre derivación de órdenes de trabajo
     */
    const EMAIL_CAN_ASUNTO = 'Confirmación de Análisis';
    const EMAIL_CAN_MENSAJE = 'Se requiere de una Confirmación de Análisis.';
    const EMAIL_CAN_VACIO = '';

	/**
     * Notificación sobre denuncia cliente
     */
    const EMAIL_CONF_DEN_ASUNTO = 'Confirmación Denuncia AGRO Móvil';
    const EMAIL_RECP_DEN_ASUNTO = 'Recepción Denuncia AGRO Móvil';
    const CORREO_DESTINATARIO_PLANIFICACION = 'veronica.rivadeneira@agrocalidad.gob.ec';
    const EMAIL_DEN_MENSAJE = '<H2>Estimado Usuario,</H2> <b>AGROCALIDAD</b> agradece su <b>DENUNCIA</b> enviada a través de la aplicación “AGRO Móvil”. <br/><br/>Con su aporte ayuda a mejorar el estatus Fito y Zoosanitario del país.';
    const EMAIL_DEN_VACIO = 'El usuario no dispone de un correo electrónico.';

    /**
     * Datos para enviar enviar/finalizar la solicitud
     * FC = tabla financiero_cabecera
     */
    const TIPO_SOLICITUD_FC = 'LABORATORIOS';
    const ESTADO_FC = 'Por atender';
    const TABLA_MODULO_FC = 'g_laboratorios.solicitudes';

	/**
     * Envío de orden de pago de recarga de saldos
     */
    const EMAIL_CONF_REC_ASUNTO = 'Confirmación Generación de Orden de Recarga de Saldo';

    /*     * *** Exception ***********
     * 
     * EN ESTE BLOQUE PONER LOS MENSAJES DE EXEPCIONES 
     */
//const SESSION_CERRRADA = "No existe una sesión de usuario activa";
    const ERROR_CADENA_CARACTERES = "El dato ingresado no puede contener caracteres especiales ";
    const ERROR_CADENA_EMAIL = "El formato del correo electrónico es incorrecto ";
    const ERROR_CADENA_DECIMAL = "El dato ingresado no es un número decimal ";
    const ERROR_CADENA_ENTERO = "El dato ingresado no es un número entero ";
    const ERROR_CADENA_BOOLEAN = "El dato ingresado no es un Boolean ";
    const ERROR_CADENA_FECHA = "El dato ingresado no es una fecha valida ";
    const CAMPO_OBLIGATORIO = "Campo obligatorio : ";
    const CAMPO_LONGITUD_INCORRECTO = "El dato ingresado tiene una longitud incorrecta. Máximo: ";
    const ERROR_MENU = 'Error: Mal configurado las opciones de menú y sus botones. No existe un método en el controlador para esta acción ';
    const ERROR_USUARIO_INACTIVO = 'Error: La sesión del usuario a finalizado ';
    const CAMPO_DINAMICO_OBLIGATORIO = "Campo creado dinámicamente es obligatorio: ";
    const ERROR_INFORME_VACIO = 'Error: Informe esta vacio ';
    /**
     * Exception - Base de datos
     */
    const ERROR_GUARDAR = "Ocurrió un error al guardar el nuevo registro";
    const ERROR_ACTUALIZAR = "Ocurrió un error al actualizar el registro";
    const ERROR_ELIMINAR = "Ocurrió un error al eliminar registro";
    const ERROR_SELECCIONAR = "Ocurrió un error al realizar la consulta";
    const ERROR_CONEXION = "Ocurrió un error al conectar la base de datos";
    const ERROR_EJECUCION_SQL = 'Error: Al ejecutar la sentencia SQL';
    const CONSULTA_VACIA = 'No existe información para mostrar';
    const ESQUEMA_LABORATORIOS = 'g_laboratorios';
    const ESQUEMA_REACTIVOS = 'g_reactivos';

    /**
     * REACTIVOS ESPECIFICOS
     */
    const EXISTE_CERTIFICADO_REACTIVO = 'Existe un certificado actual para el reactivo:';
    const NO_EXISTE_RECETA = ' No existe un procedimiento para calcular y registrar el uso de reactivo.';
    const DESCUENTO_EXITOSO = 'Reactivo {REACTIVO}, descontado {CANTIDAD}';
    const NO_EXISTE_EN_LABORATORIO = 'El reactivo {REACTIVO} no se encuentra en el Laboratorio';
    
    /**
     * Registrar pagos
     */
    const VALIDAR_TOTAL_PAGO = "Error al registrar el Pago. El total depositado debe ser igual a que lo que debe pagar";
    const ERROR_TRANSACCION_REGISTRADA = "El número de depósito del banco seleccionado ya se encuentra registrado en el sistema.";
    const REQUIERE_PAGO = "Se requiere el registro del pago.";

    /**
     * Varios
     */
    const CONTENEDOR_CAMPOS_RESULTADOS = "Campos";
    const INF_USUARIO_SOLICITUD = "Este usuario debe estar habilitado para usar el Sistema de Laboratorios.";
    const INF_USUARIO_LABORATORIO = "Este usuario no tiene asignado un Laboratorio. Informar al administrador del sistema.";
    const INF_NO_MEMO = "No existe el documento oficio de exoneración.";
    const INF_NO_ANALISIS = "No existe análisis registrados en esta solicitud.";
    const EXISTE_ADJUNTO_SOLICITUD = "Existe un adjunto para este parámetro.";
    const NO_EXISTE_ADJUNTO_SOLICITUD = "No existen archivos adjuntos de esta solicitud";
    const NO_PERMISO_MULTIUSUARIO = "No tiene permiso para editar esta solicitud. Comun&iacute;quese con el Responsable T&eacute;cnico.";
    const INF_EXISTE_DETALLE_SOLICITUD = "El servicio ya est&aacute; registrado en la solicitud";
    const INF_USUARIO_FINANCIERO = "Verificar que el usuario se encuentre habilitado como recaudador en el módulo financiero.";
	const INF_OT_RT = "Seleccione una acción en la barra superior: Informes/Generar etiquetas/Muestras Almacenadas";
    /**
     * Casos especiales
     */
    const SER_MARBETES = "SERMAR";    //Servicio de primer nivel Marbetes
    const SER_FA_EXCEL = "SERFAEXCEL";     //Servicios de Fiabre Aftosa que deben subirse el excel de muestras
    const LAB_INGREDIENTE_ACTIVO = "INGACT";     //Laboratorios que tienen ingredientes activos
    const SER_PREDETERMINADO = "SERPRE"; //Servicios que se agregan por defecto, ejm: DETERMINACION DE DENSIDAD
    const SER_ENTO_PNMMF = "SERPNMMF";   //Caso especial para Entomologia, aplica cuando selecciona el servicio Frutos PNMMF.
    
	/**
     * Movilización Vegetal
     */
    const ERROR_VIGENCIA = 'Error: La movilización ya no se encuentra vigente para fiscalizar.';
	
    /**
     * Codificación de módulos
     */
    const SEGUIMIENTO_DOCUMENTAL = "PRG_SEG_DOC";
	
	/**
     * Web Services Certificado Fitosanitario
     */
    const ERROR_WSFITOSANITARIO = 'Error: La configuración seleccionada ya se encuentra registrada.';
	
	
	/**
     * Tipo de Solicitud
     */
    public static function perfil()
    {
        return new class() extends Constantes {

        public $recaudador = "Recaudador";
        public $rt = "Responsable Técnico";
        public $analista = "Analista";
        };
    }

    /**
     * Tipo de Solicitud
     */
    public static function tipo_SO()
    {
        return new class() extends Constantes {

        public $ENVIADA_CLIENTE = "ENVIADA POR EL CLIENTE";
        public $MULTIUSUARIO = "MULTIUSUARIO";
        public $MODULO_EXTERNO = "MODULO EXTERNO";
        public $CONFIRMACION = "CONFIRMACION";
        public $MUESTRA_CIEGA = "MUESTRA CIEGA";
        public $DERIVACION = "DERIVACION";
        public $POSTREGISTRO = "POSTREGISTRO";
        };
    }

    /**
     * Estado de Solicitud
     */
    public static function estado_SO()
    {
        return new class() extends Constantes {

        public $REGISTRADA = "REGISTRADA";
        public $ENVIADA = "ENVIADA";
        public $RECIBIDA = "RECIBIDA";
        public $EN_PROCESO = "EN PROCESO";
        public $FINALIZADA = "FINALIZADA";
        };
    }

    /**
     * Estado de orden de trabajo
     */
    public static function estado_OT()
    {
        return new class() extends Constantes {

        public $REGISTRADA = "REGISTRADA";
        public $ACTIVA = "ACTIVA";
        public $EN_PROCESO = "EN PROCESO";
        public $EN_ANALISIS = "EN ANALISIS";
        public $EN_APROBACION = "EN APROBACION";
        public $FIRMADO = "FIRMADO";
        public $FINALIZADA = "FINALIZADA";
        };
    }

    /**
     * Estado de la muestra
     */
    public static function estado_MU()
    {
        return new class() extends Constantes {

        public $REGISTRADA = "REGISTRADA";
        public $RECIBIDA = "RECIBIDA";
        public $IDONEA = "IDONEA";
        public $ANALIZADA = "ANALIZADA";
        public $NO_APROBADO = "NO APROBADO";
        public $ALMACENADA = "ALMACENADA";
        public $DESECHADA = "DESECHADA";
        };
    }

    /**
     * Códigos del tipo de informe
     */
    public static function tipo_informe()
    {
        return new class() extends Constantes {

        public $PRINCIPAL = "PRINCIPAL";
        public $ALCANCE = "ALCANCE";
        public $SUSTITUTO = "SUSTITUTO";

        };
    }

    /**
     * Estado del informe
     */
    public static function estado_informe()
    {
        return new class() extends Constantes {

        public $ACTIVO = "ACTIVO";
        public $APROBADO = "APROBADO";
        public $FIRMADO = "FIRMADO";
        public $ENVIADO = "ENVIADO";
        public $ANULADO = "ANULADO";

        };
    }

    public static function tipo_parametro()
    {
        return new class() extends Constantes {
        //Este código se concatena con el codigo del laboratorio
        public $ETIQUETA_MUESTRA = "_FETIQMUE";
        public $MENSAJE_ACREDITACION = "MACREDITACION";
        public $MENSAJE_ACREDITACION_PIE = "PIEACREDITACION";
         public $TABLA_REFERENCIA = "REFERENCIA";
         public $CODIGO_REFERENCIA = "TABLAREF";


        };
    }

    /**
     * Código del tipo de usuario
     */
    public static function tipo_usuario()
    {
        return new class() extends Constantes {

        public $INTERNO = "INTERNO";
        public $EXTERNO = "EXTERNO";
        public $TODOS = "TODOS";

        };
    }

    public static function tipo_laboratorio()
    {
        return new class() extends Constantes {

        public $PRINCIPAL = "LN";
        public $REGIONAL = "LR";
        public $LDRAPIDO = "LDR";

        };
    }

    /**
     * Tipo Usuario Solicitud usado en tabla usuarios_solicitud
     */
    public static function tipo_US()
    {
        return new class() extends Constantes {

        public $PRINCIPAL = "PRINCIPAL";
        public $RESPALDO = "RESPALDO";
        };
    }

    public static function catalogos_lab()
    {
        return new class() extends Constantes {
        public $COD_ENVIO_INFORME = "ENVIOINF"; //dirección de sanidad animal, d de s vegetal
        };
    }

    public static function catalogos_rea()
    {
        return new class() extends Constantes {

        public $COD_ESTADO = "ESTADOREA";   //sólido, líquido, gel
        public $COD_BAJA = "CODBAJA";   //desperdicio técnico, análisis de muestra, análisis extra
        };
    }

    /**
     * Códigos del estado de acta de baja del reactivo
     */
    public static function estado_ABR()
    {
        return new class() extends Constantes {

        public $APROBADA = "APROBADA";
        public $NO_APROBADA = "NO APROBADA";
        public $RETORNADA = "RETORNADA";
        };
    }

    public static function tipo_identificacion()
    {
        return new class() extends Constantes {

        public $RUC = "04";
        public $CEDULA = "05";
        };
    }

    /**
     * Cadenas iguales al que se registra en la g_laboratorios.laboratorios.atributos
     */
    public static function permisos_laboratorio()
    {
        return new class() extends Constantes {

        public $DERIVACION = "derivacion";
        public $CONFIRMACION = "confirmacion";
        public $IDONEA_EN_PROCESO = "idoneaEnProceso";
        };
    }
    
    public static function tipo_reporte_xls()
    {
        return new class() extends Constantes {

        public $BASEDATOS = "BASEDATOS";
        public $REACTIVOS = "REACTIVOS";
        };
    }

    /**
     * Códigos del estado de acta de baja del reactivo
     */
    public static function estado_SOLREA()
    {
        return new class() extends Constantes {

        public $ACTIVO = "ACTIVO";
        public $SOLICITADO = "SOLICITADO";
        public $EN_PROCESO = "EN PROCESO";
        public $INGRESADO = "INGRESADO";
        };
    }
	
	/**
     * Cadenas de ambiente de desarrollo
     **/
    
    const RUTA_SERVIDOR_OPT =  'C:/xampp/htdocs';
    const RUTA_APLICACION = 'agrodbTdr';
	const RUTA_DOMINIO = 'localhost'; // 1 pruebas http://181.112.155.173 // 2 produccion https://guia.agrocalidad.gob.ec
    
    /*
	 *Constante para la validación de tipo de inspectoe en módulo de centros de faenamiento
     * */
    public static function tipo_inspector()
    {
        return new class() extends Constantes {
            public $AUXILIAR = "Auxiliar de inspección";
            public $AVES = "Veterinario autorizado de aves";
			public $AVESOFICIAL = "Veterinario oficial de aves";
            public $MAYORES = "Veterinario autorizado de especies mayores";
			public $MAYORESOFICIAL = "Veterinario oficial de especies mayores";
        };
    }
	
	 /*
     *constante para definir el tipo de titulo que se utilizara en certificador laboral
     */
    public static function certificadoLaboral()
    {
        return new class() extends Constantes {
            public $TITULO = "Ing.";
        };
    }
	
	/*
    *constante para definir el número de cédula del recaudador establecido por el área financiera para la generación de ordenes de pago
    */
    const IDENTIFICADOR_RECAUDADOR = '0602483786';


     /*
    *constante para definir el código del item del tarifario para pago automático
    */
    const ITEM_TARIFARIO_RECARGA_SALDO = '10.00.000';
	
	 /*
     * Constante de procesos automaticos para entrada y salida de inforamcion
     * */
    const PRO_MSG = '<br/> ';
    const IN_MSG = '<br/> >>> ';
}
