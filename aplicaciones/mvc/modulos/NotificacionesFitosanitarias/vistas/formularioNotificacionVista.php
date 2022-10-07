<header>
    <h1><?php echo $this->accion; ?></h1>
</header>

<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>NotificacionesFitosanitarias'
      data-opcion='Notificaciones/guardar' data-destino="detalleItem"
      data-accionEnExito="ACTUALIZAR" method="post">
     <fieldset>
        <legend>Detalle</legend>
        
        <div data-linea="1">
            <label for="nombre_lista">C&oacute;digo. de documento: </label>
            <input type="hidden" id="id_lista_notificacion" name="id_lista_notificacion" value ="<?php echo $_POST['id'] ;?>"/>
            <input type="text" id="codigo_documento" name="codigo_documento" value="<?php echo $this->modeloNotificaciones->getCodigoDocumento();?>"
                placeholder="C&oacute;digo &uacute;nico de documento" required maxlength="256" />
	</div>
        
        <div data-linea="2">
			<label for="id_pais_notifica">País que notifica: </label>
			<select id="id_pais_notifica" name="id_pais_notifica" required>
				<option value="">Seleccionar....</option>
                            <?php 
                               echo $this->comboPaises($this->modeloNotificaciones->getIdPaisNotifica());
                            ?>
            </select>
            <input type="hidden" id="pais_notifica" name="pais_notifica" />       
	</div>
        
        <div data-linea="3">
			<label for="tipoDocumento"> Tipo de documento: </label> 
                 <select
                    id="tipoDocumento" name="tipoDocumento" required>
                    <option value="">Seleccionar....</option>
                    <option value="Ordinarias">Ordinarias</option>
                    <option value="Medidas de urgencia">Medidas de urgencia</option>
                    <option value="Adiciones de urgencia">Adiciones de urgencia</option>
                    <option value="Correciones ordinarias">Correciones ordinarias</option>
                    <option value="Correciones de urgencia">Correciones de urgencia</option>
                    <option value="Suplemento de traducción">Suplemento de traducción</option>
                    <option value="Reconocimiento de equivalencia">Reconocimiento de equivalencia</option>
                    
                </select>
                        
                                        			
	</div> 
        
        <div data-linea ="4">
            <label for="fechaNotificacion"> Fecha notificaci&oacute;n: </label> 
            <input type ="date" id="fechaNotificacion"
                   name ="fechaNotificacion" value="<?php echo $this->modeloNotificaciones->getFechaNotificacion(); ?>"
                   placeholder ="Fecha de depósito" max="<?php echo date("Y-m-d"); ?>"
                   maxlength="10" />
        </div>
     </fieldset>   
     <fieldset>
        <legend>Productos</legend>
        
        <div data-linea ="1">   
            <label for="producto"> Producto: </label> 
            <input type ="text" id="producto"
                   name ="producto" value="<?php echo $this->modeloNotificaciones->getProducto(); ?>"
                   placeholder ="Productos"
                   maxlength="256" />
        </div >
     </fieldset>   
     <fieldset>
        <legend>Palabras clave</legend>   
        <div data-linea ="1">   
            <label for="palabraClave"> Palabras claves de la notificaci&oacute;n: </label> 
            <input type ="text" id="palabraClave"
                   name ="palabraClave" value="<?php echo $this->modeloNotificaciones->getPalabraClave(); ?>"
                   placeholder ="Palabras Claves"
                   maxlength="256" />
        </div >
     </fieldset>
    <fieldset>
        <legend>Descripci&oacute;n</legend>   
        <div data-linea ="1">   
            <label for="descripcion"> Descripci&oacute;n: </label> 
            <input type ="text" id="descripcion"
                   name ="descripcion" value="<?php echo $this->modeloNotificaciones->getDescripcion(); ?>"
                   placeholder ="Descripci&oacute;n"
                   maxlength="256" />
        </div >
     </fieldset>
    <fieldset>
		<legend>Paises afectados</legend>
		<div data-linea="1">
			<label for="id_localizacion">País: </label>
			<select id="id_localizacion" name="id_localizacion">
				<option value="">Seleccionar....</option>
                            <?php 
                                echo $this->comboPaises($this->modeloNotificacionPorPaisAfectado->getIdLocalizacion());
                            ?>
                        </select>
			
                </div>
		<span>
                    <button type="button" class="mas" onclick="agregar()"> Agregar</button>
                </span>
                
                <table width="100%" id="grilla" class="lista" ALIGN="CENTER">
                    <thead>
                        <tr>
                            <th colspan="7">Pa&iacute;s afectado</th>
                        </tr>
                        <tr>
                            <th>#</th>
                            <th>Pa&iacute;s</th>
                            <th>Eliminar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- codigo -->      
                    </tbody>
                </table>
       </fieldset>
    
    <fieldset>
        <legend>Enlace</legend>   
        <div data-linea ="1">   
            <label for="enlace"> Enlace: </label> 
            <input type ="text" id="enlace"
                   name ="enlace" value="<?php echo $this->modeloNotificaciones->getEnlace(); ?>"
                   placeholder ="Enlace"
                   maxlength="256" />
        </div >
     </fieldset>
      
        <div data-linea="5">
    <button type="submit" class="guardar"> Guardar</button>
        </div>
    
</form>
<script type="text/javascript">
    $(document).ready(function () {
        construirValidador();
        distribuirLineas();
    });

    $("#id_pais_notifica").change(function () {
        if ($("#id_pais_notifica option:selected").val() !== "") {
            $("#pais_notifica").val($("#id_pais_notifica option:selected").text());
        }else{
        	$("#pais_notifica").val("");
        }
    });

    $("#formulario").submit(function (event) {
        event.preventDefault();
        
        var respuesta = JSON.parse(ejecutarJson($(this)).responseText);
	       	if (respuesta.estado == 'exito'){
	       		fn_filtrar();
	        } else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
    });
    
    function agregar() { 
    	$(".alertaCombo").removeClass("alertaCombo");
    	var error = false;
    	if(!$.trim($("#id_localizacion").val())){
			error = true;
			$("#id_localizacion").addClass("alertaCombo");
		}
         $(".list_id_localizacion").each(function () {
            if ($('#id_localizacion').val().trim() === $(this).val()) {
             $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");  
             error = true;  
            }
        });       

        if(!error){
            fn_agregarFila();
            $("#estado").html("").addClass("alerta");  
                }
        else{
            mostrarMensaje("Este país ya está agregado.", "FALLO");
        }
   }
    
    function fn_agregarFila() { 
        idLocalizacion = $("#id_localizacion").val();
        var campoLocalizacion = "<input type='hidden' name='idLocalizacion[]' readonly class='list_id_localizacion' value=" + $("#id_localizacion").val() + ">"+
        "<input type='hidden' name='nombreLocalizacion[]' readonly class='list_nombre_localizacion' value=" + $("#id_localizacion option:selected").text() + ">"; //identificador pais afectado
        cadena = "<tr ALIGN='CENTER'>";
        cadena = cadena + "<td></td>"; //numeracion
        cadena = cadena + "<td>" + campoLocalizacion + $("#id_localizacion option:selected").text() + "</td>";
       
        cadena = cadena + "<td class='borrar'><button type='button' name='eliminar' id='eliminar' class='icono' onClick='fn_eliminar(" + '"grilla"' + ",getIndex(this, " + '"eliminar"' + "))'/></td>";
        cadena = cadena + "</tr>";
        $("#grilla tbody").append(cadena);
        fn_numerar();
    }
    
    //enumera las filas cuando se agrega y se elimina una fila
    function fn_numerar() {
        var total = $('#grilla >tbody >tr').length;
        for (var i = 1; i <= total; i++) {
            document.getElementById("grilla").rows[i + 1].cells[0].innerText = i;
        }
       
    }
   
    //Funcion que elimina una fila de la lista 
    function fn_eliminar(nomTabla, indice) { 
        respuesta = confirm("Desea eliminar");
        if (respuesta) {
            $("#" + nomTabla + " tbody").find("tr").eq(indice).remove();
            fn_numerar();
        }
    }
    
    function getIndex(boton, idElemento) {
        db = document.getElementsByName(idElemento); //Crea un arreglo db con todos los botones eliminar
        ne = db.length; //Cuenta el numero de elementos del arreglo
        for (i = 0; i < ne; i++)
            if (db[i] === boton) //Si el objeto del arreglo es igual al objeto (boton) recibido como parametro devuelve su indice i
                return i;
    }
 
</script>