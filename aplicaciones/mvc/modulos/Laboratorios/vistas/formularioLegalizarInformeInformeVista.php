<script src="<?php echo URL_RESOURCE ?>js/tinymce/tinymce.min.js"></script>		
<header>
    <h1><?php echo $this->accion; ?></h1>
</header>		
<form id = 'formulario' data-rutaAplicacion = '<?php echo URL_MVC_FOLDER; ?>Laboratorios'			 
      data-opcion = 'BandejaInformes/legalizarInforme' data-destino ="detalleItem"			 
      data-accionEnExito ="NADA" method="post" lang="" >			
    <fieldset>			
        <legend>Información de la firma electrónica</legend>			

        <div data-linea ="1">	
            
            <span class="label label-default" ><?php echo $this->mensajeFirmar; ?></span>
               
            
             </div >
       
             <hr>
        <div data-linea ="2">			
            <input type ="hidden" name="id_archivo_informe_analisis" id="id_archivo_informe_analisis" value ="<?php echo $this->modeloArchivoInformeAnalisis->getIdArchivoInformeAnalisis() ?>">			
           
            <button type ="submit" class="btnenviar" <?php echo $this->estdoFirma ?>>Firmar informe </button>
        </div >
    </fieldset >
</form>
<script type ="text/javascript">
    $(document).ready(function () {
<?php echo $this->codigoJS ?>
        distribuirLineas();
    $("#formulario").submit(function (event) {
        event.preventDefault();
        var error = false;
       if (!error) {
          
             abrir($(this), event, false);
            fn_filtrar();
            
        } else {
            $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
        }
    });
     });
</script>
