var chatBoxes = new Array();
var ElementosClick = new Array();
var audioSolicitud = new Audio('aplicaciones/agroChat/sound/notificacion1.ogg');
var audioMensaje = new Audio('aplicaciones/agroChat/sound/wet.ogg');
var jsonUsuarios;
var jsonContactos;
var jsonSolicitudes = {};
var jsonContactosAgregados = [];
var jsonContactosAgregadosOriginal = [];


$("#notificacionMensaje").click(function (e){		
	$(".mainChat").show();
	setTimeout(restructureChatBoxes,0);
});


$("#chatCerrar").click(function(e){		
	$(".mainChat").hide();
	setTimeout(restructureChatBoxes,0);
});

$("#abrirContactos").click(function(e){
	e.preventDefault;
	$(".solicitudesCuerpoChat").hide();
	$(".solicitudesRecibidasCuerpoChat").hide();
	$(".contactosCuerpoChat").show();
	botonActivo(this);
});	

$("#abrirEnviarSolicitudesitudes").click(function(e){
	e.preventDefault;
	$(".solicitudesRecibidasCuerpoChat").hide();
	$(".contactosCuerpoChat").hide();
	$(".solicitudesCuerpoChat").show();
	setTimeout(function(){ $("#nombreContactoNuevo").focus(); }, 300);
	botonActivo(this);
});

$("#abrirSolicitudes").click(function(e){
	e.preventDefault;	
	$(".solicitudesRecibidasCuerpoChat").show();
	$(".contactosCuerpoChat").hide();
	$(".solicitudesCuerpoChat").hide();	
	botonActivo(this);
});

$("#abrirGrupoChat").click(function(e){
	e.preventDefault;
	$(".solicitudesRecibidasCuerpoChat").hide();
	$(".contactosCuerpoChat").hide();
	$(".solicitudesCuerpoChat").hide();
	$(".gruposCuerpoChat").show();
	//setTimeout(function(){ $("#nombreContactoNuevo").focus(); }, 300);
	botonActivo(this);
});

function botonActivo(e){	
	$(".botonChatActivo").removeClass("botonChatActivo");
	$(e).addClass('botonChatActivo');
}

$("#nombreContactoNuevo").keyup(function(e){
	e.preventDefault;
	usuario = usuarioIdentificadorChat;
	contacto = $("#nombreContactoNuevo").val();
    var usuarioContacto;
	var listaUsuarios = buscarUsuario(contacto);
	var usuarioContacto;
	var pie;
	$('#listaContactosNuevoChat').empty();

    for (var lista in listaUsuarios){
    	
    	if(listaUsuarios[lista].fotografia!=''){
			foto=listaUsuarios[lista].fotografia;
		} else{
			foto='aplicaciones/agroChat/img/user2.png';
		}

		if(usuario!=listaUsuarios[lista].identificador){

			if(listaUsuarios[lista].relacion=="1"){
				pie='<label class="relacionUsuario">Amigos</label>';
			} else{					
				pie='<a class="enviarSolicitudLink" id="btn_' +listaUsuarios[lista].identificador+ '" onclick="enviarSolicitud('+"'"+'btn_' +listaUsuarios[lista].identificador+"'"+')" >Enviar solicitud</a>';				
			}

			if(listaUsuarios[lista].estado_solicitud=="pendiente"){
				if(listaUsuarios[lista].recepcion=="enviado"){					
					pie='<label class="relacionUsuario">Solicitud enviada</label> <a class="cancelarSolicitudLink"  onclick="cancelarSolicitud('+"'"+ listaUsuarios[lista].identificador +"'"+ ')">Cancelar</a>';
				}	else{						
					pie='<a class="enviarSolicitudLink"  title="Aceptar solicitud" onclick="aceptarSolicitud('+"'"+listaUsuarios[lista].identificador +"'"+ ')">Aceptar Solicitud</a> <a class="cancelarSolicitudLink" title="Rechazar solicitud" onclick="rechazarSolicitud('+ "'"+ listaUsuarios[lista].identificador +"'"+ ')">Rechazar </a>';
				}
			}

			usuarioContacto = '<li id="lictn_'+ listaUsuarios[lista].identificador + '" onmouseover="quitarNotificacion(this)" >'+
			'<div class="contenedorContactoNuevo" id="ctn_'+ listaUsuarios[lista].identificador + '" >'+
			
					'<div class="fotoUsuarioChatNuevo" ><img src=" '+foto+' " class="imgUsuarioChatNuevo"> </div>'+
					'<div class="contenedorUsuarioDatosNuevo">'+							
						'<div class="nombreUsuarioNuevo" >'+listaUsuarios[lista].nombres+'</div>'+
						'<div class="contenedorEnviarSolicitud" id="nmu_'+listaUsuarios[lista].identificador+'" >'+pie+'</div>'+
					'</div>'+					
			
			'</div></li>';
	    	$("#listaContactosNuevoChat").append(usuarioContacto);
	   }
    }    
    cortarCadena(20,'.nombreUsuarioNuevo');	
});


function quitarNotificacion(e){		
	$(e).removeClass("notificacion");
}
	
function configurarSonido(e){
	var id = "#"+$(e).siblings().attr("id");
	var valor;
		
	if ($(id).is(":checked")){		
		valor=true;		
	} else{
		valor=false;	
	}	

	$.ajax({
		url:'aplicaciones/agroChat/configurarSonido.php',
		method: 'post',	
	    data: {usuario: usuarioIdentificadorChat, opcion: valor},	    
	    //cache: false,
    	async:   true,
	    success: function(msg){		   
	    },
	    error: function(jqXHR, textStatus, errorThrown){
	    	$("#cargando").delay("slow").fadeOut();
	    	mostrarMensaje("ERR: " + textStatus + ", " +errorThrown,"FALLO");
	    }
	});
}
		
function cargarSonidos(){
	
	$.ajax({
		url:'aplicaciones/agroChat/cargarSonido.php',
		method: 'post',	
	    data: {usuario: usuarioIdentificadorChat},
	    dataType: "json",
    	async:   true,
	    success: function(data){
		    var resultado=$.trim(data.mensaje);		    
		   if(resultado=="false"){			   
			   $("#chatSonido").prop("checked",true);			  
		   } else {			   
			   $("#chatSonido").prop("checked",false);			   
		   }
	    },
	    error: function(jqXHR, textStatus, errorThrown){
	    	$("#cargando").delay("slow").fadeOut();
	    	mostrarMensaje("ERR: " + textStatus + ", " +errorThrown,"FALLO");
	    }
	});
}


function buscarUsuario(usuario){
	var str ;
	var contacto;
	var igual;		
	var listaUsuariosContactos = [];
	/*var existe=false;*/
    
	if(usuario.length >= 3){
		for (var i=0 ; i < jsonUsuarios.length ; i++){
			str = usuario;
			contacto = str.toUpperCase();	
			
			nombreSeparado = contacto.split(' ');
		
			var coincidencia=0;
			for(var j =0; j < nombreSeparado.length ; j++){	
				igual = jsonUsuarios[i]["nombres"].toUpperCase().indexOf(nombreSeparado[j]);					
				if(igual>=0){    			
					coincidencia+=1;
				} 				
			}
			
			if(coincidencia==nombreSeparado.length){
				listaUsuariosContactos.push(jsonUsuarios[i]);
			}			
			
	    }
		
	    if($.trim(contacto)==""){
	    	listaUsuariosContactos = [];
	    }        
	    return listaUsuariosContactos;
	}
}

		
function cargarUsuarios() {
	var url= "aplicaciones/agroChat/usuarios.json";				
    jsonUsuarios={};
    $.ajax({
    	  dataType: "json",
    	  url: url,  
    	  async: false,      	
    	  cache: false, 
    	  success:  function(msg){	
    	  	jsonUsuarios=msg;
    	  }
    });

	var identificadorUsuario = usuarioIdentificadorChat;
	
    $.ajax({
    url: 'aplicaciones/agroChat/obtenerContactos.php',
    method: 'post',
    data: {usuario: identificadorUsuario},
    dataType: "json",
    async: false,
    success: function(msg){
	    	if(msg.estado=="exito"){	   
	    		jsonContactos=msg.mensaje;	    		
	    	}else{
	    		jsonContactos="vacio";
	    	}			  
	    },
	    error: function(jqXHR, textStatus, errorThrown){
	    	jsonContactos="vacio";
	    	mostrarMensaje("ERR: " + textStatus + ", " +errorThrown,"FALLO");
	    }
	});    

    if (jsonContactos  != 'vacio'){
	    jsonContactos.forEach(function(contactos){        
	    	 jsonUsuarios.forEach(function(usuarios){    	
	        	 if(contactos.contacto ==  usuarios.identificador){            	
	            	 usuarios.relacion="1";
	        	 }
	         });
	    	
		});   
	}


   jsonSolicitudes={};      

   $.ajax({
    url: 'aplicaciones/agroChat/obtenerSolicitudes.php',
    method: 'post',
    data: {usuario: identificadorUsuario},
    dataType: "json",
    async: false,
    success: function(msg){
	    	if(msg.estado=="exito"){
	    		jsonSolicitudes=msg.mensaje;   			    	
	    	}			  
	    },
	    error: function(jqXHR, textStatus, errorThrown){			    
	    	mostrarMensaje("ERR: " + textStatus + ", " +errorThrown,"FALLO");
	    }
   	});   
   
   if(jsonSolicitudes.length > 0){   
       jsonSolicitudes.forEach(function(solicitudes){
           jsonUsuarios.forEach(function(usuarios){
               if(solicitudes.contacto ==  usuarios.identificador){
	           	 usuarios.estado_solicitud=solicitudes.estado_solicitud;
	           	 usuarios.recepcion = solicitudes.recepcion;
       	       }
    	   });
       	
   	   });
   }

}

function buscarContacto(usuario){
	var str ;
	var contacto;
	var igual;		
	var listaUsuariosContactos = [];
	var listaUsuarios =jsonContactosAgregados;
	if(usuario.length >= 3){
		$('#listaContactosGrupoChat').empty();
		for (var i=0 ; i < jsonContactosAgregados.length ; i++){
			str = usuario;
			contacto = str.toUpperCase();	
			
			nombreSeparado = contacto.split(' ');
		
			var coincidencia=0;
			for(var j =0; j < nombreSeparado.length ; j++){	
				igual = jsonContactosAgregados[i]["nombres"].toUpperCase().indexOf(nombreSeparado[j]);					
				if(igual>=0){    			
					coincidencia+=1;
				} 				
			}
			
			if(coincidencia==nombreSeparado.length){
				listaUsuariosContactos.push(jsonContactosAgregados[i]);
			}			
			
	    }
		
	    if($.trim(contacto)==""){
	    	listaUsuariosContactos = [];
	    }        
	    return listaUsuariosContactos;
	} else{
		$('#listaContactosGrupoChat').empty();
		for (var lista in listaUsuarios){    	
	    	$("#listaContactosGrupoChat").append(listaUsuarios[lista].contenido);
	    } 
	}
}

$("#buscarContactoGrupoChat").keyup(function (e){
	e.preventDefault;		
	usuario = usuarioIdentificadorChat;
	contacto = $("#buscarContactoGrupoChat").val();
    var usuarioContacto;
	var listaUsuarios =buscarContacto(contacto);
	var usuarioContacto;
	var pie;
    for (var lista in listaUsuarios){    	
    	$("#listaContactosGrupoChat").append(listaUsuarios[lista].contenido);
    }    
    cortarCadena(20,'.nombreUsuarioNuevo');	
});

function mostrarGrupoChat(event, grupo=''){
	event.stopPropagation();
	event.preventDefault();
	if(grupo==''){
		$(".contenedorTextosGrupoChat").show();
		$("#cancelarGrupoChat").show();
		$("#crearGrupoChat").show();
		cargarContactosGrupoChat();
	} else{
	
		$(".contenedorTextosGrupoChat").hide();
		$(".contenedorContactosAgregadosGrupoChat").html('').hide();
		$("#cancelarGrupoChat").hide();
		$("#crearGrupoChat").hide();
		$("#listaContactosGrupoChat").empty();	
		$("#buscarContactoGrupoChat").val("");
		$("#nombreGrupoChat").val('');
		jsonContactosAgregados = [];
		jsonContactosAgregadosOriginal = [];
		
		$(".contenedorTextosGrupoChat").show();
		$("#nombreGrupoChat").val($("#"+grupo + " .contenedorUsuarioDatosNuevo").find(".nombreGrupos").html());
		$("#cancelarGrupoChat").show();
		$("#nuevoGrupoChat").hide();
		$("#actualizarGrupoChat").show();
		$("#eliminarGrupoChat").show();
		$("#eliminarGrupoChat").attr("onclick","prepararEliminarGrupo('"+grupo+"')");
		$("#grupoChatEditar").html(grupo);
		cargarContactosGrupoChat();
		cargarContactosGrupoChatEditar(grupo);
	}
}

function cargarContactosGrupoChat(){
	$.ajax({
	  	url: 'aplicaciones/agroChat/cargarContactosGrupoChat.php',
	    method: 'post',
	    data: {usuario: usuarioIdentificadorChat},
	    dataType: "json",
	    cache: false,
    	async:   true,
	    success: function(msg) {
	    	if(msg.estado=="exito"){	    		
	    		if($("#listaContactosGrupoChat li").length < msg.mensaje.length  ){
	    			$("#listaContactosGrupoChat").empty();		    				    			
		    		$(msg.mensaje).each(function(i){
    		    	var contatChat = this.contacto;
    		    		$("#listaContactosGrupoChat").append(this.contenido);
    		    		$("#nmu_"+contatChat).html('<label class="relacionUsuario">Amigos</label>');		
    		    		
    		    		item = {};
    		    		item ["contenido"] = this.contenido;
    		    		item ["contacto"] = this.contacto;
    		    		item ["nombres"] = this.nombres;
    		    		
    		    		jsonContactosAgregados.push(item);
    		    		jsonContactosAgregadosOriginal.push(item);
		  			 
		    		});   	
	    		}		    	
	    	}  		    	
	    }
   });	
}

function cargarContactosGrupoChatEditar(id){
	grupoid= id.split('_',2);	 
	$.ajax({
	  	url: 'aplicaciones/agroChat/cargarContactosMiembrosGrupoChat.php',
	    method: 'post',
	    data: {usuario: usuarioIdentificadorChat, grupo: grupoid[1]},
	    dataType: "json",
	    cache: false,
    	async:   false,
	    success: function(msg) {	    	 
	    	if(msg.estado=="exito"){	 			
	    		$(msg.mensaje).each(function(i){
		    		agregarContactosGrupoChat("ctg_"+this.contacto, this.foto, this.nombres );
	    		});   	
	    		    	
	    	}  		    	
	    }
   });	
}

function cancelarGrupoChat(){	
	$(".contenedorTextosGrupoChat").hide();
	$(".contenedorContactosAgregadosGrupoChat").html('').hide();
	$("#cancelarGrupoChat").hide();
	$("#crearGrupoChat").hide();
	$("#actualizarGrupoChat").hide();
	$("#eliminarGrupoChat").hide();
	$("#nuevoGrupoChat").show();
	$("#listaContactosGrupoChat").empty();	
	$("#buscarContactoGrupoChat").val("");
	$("#nombreGrupoChat").val('');
	$(".contenedorConfirmacionEliminar").remove();
	jsonContactosAgregados = [];
	jsonContactosAgregadosOriginal = [];
}



function agregarContactosGrupoChat(id,foto,nombre){
	
	var usuario = id.split('_',2);
	var contacto = nombre.split(' ',1);
	
	if($("#cta_"+usuario[1]).length==0){	
	
	var contenido = '<div class="contenedorContactoSeleccionado" id="cta_'+usuario[1]+'" onclick="quitarContactosGrupoChat('+"'"+'cta_'+usuario[1]+"'"+')">'+
							'<div class="fotoUsuarioGrupoChat">'+
						'<img src="'+foto+'" class="imgUsuarioChat">'+
					'</div>'+
					'<div class="nombreUsuarioGrupoChat">'+
					contacto+
					'</div>'+
					'</div>';
	$(".contenedorContactosAgregadosGrupoChat").show();
	$(".contenedorContactosAgregadosGrupoChat").append(contenido);	
	$("#ctg_"+usuario[1]).append('<span class="contactoAgregadoGrupo"></span>');		
	
	for (var lista in jsonContactosAgregados){  
		if(jsonContactosAgregados[lista].contacto == usuario[1]) {
			corte = jsonContactosAgregados[lista].contenido.indexOf("</li>");
			cadena = jsonContactosAgregados[lista].contenido.substring(0, corte-20);
			jsonContactosAgregados[lista].contenido=cadena+'<span class="contactoAgregadoGrupo"></span></div></li>';			
		}
    }  
	
	} else{
		$("#cta_"+usuario[1]).remove();
		$("#ctg_"+usuario[1]+" .contactoAgregadoGrupo").remove();
		
		for (var lista in jsonContactosAgregados){  
			if(jsonContactosAgregados[lista].contacto == usuario[1]) {
				corte = jsonContactosAgregados[lista].contenido.indexOf('<span class="contactoAgregadoGrupo"></span>');
				cadena = jsonContactosAgregados[lista].contenido.substring(0, corte);
				jsonContactosAgregados[lista].contenido=cadena+'</div></li>';			
			}
	    }
	}
	
}

function quitarContactosGrupoChat(id){	
	var usuario = id.split('_',2);
	$("#cta_"+usuario[1]).remove();
	$("#ctg_"+usuario[1]+" .contactoAgregadoGrupo").remove();
	
	for (var lista in jsonContactosAgregados){  
		if(jsonContactosAgregados[lista].contacto == usuario[1]) {
			corte = jsonContactosAgregados[lista].contenido.indexOf('<span class="contactoAgregadoGrupo"></span>');
			cadena = jsonContactosAgregados[lista].contenido.substring(0, corte);			
			jsonContactosAgregados[lista].contenido=cadena+'</div></li>';			
		}
    }
}

function guardarGrupo(){
	var data = new Array();
	var cadenaCreados
	$(".contenedorContactoSeleccionado").each(function(){
		var usuario = $(this).attr("id").split('_',2);		
		data.push({identificadorUsuario : usuario[1]});
	});
	
	if($(".contenedorContactoSeleccionado").length > 0 && $("#nombreGrupoChat").val()!=''){
	
		$.ajax({
		  	url: 'aplicaciones/agroChat/guardarGrupo.php',
		    method: 'post',	
		    data: {data:JSON.stringify(data), grupo: $("#nombreGrupoChat").val(), usuario: usuarioIdentificadorChat},
		    dataType: "json",
		    cache: false,
	    	async:   false,
		    success: function(msg) {  	
		    	if(msg.estado=='exito'){
		    	
			    	$(msg.mensaje).each(function(i){
			    		
			    	cadenaCreados = '<li class="itemLista" id="lsgrp_'+this.grupo+'" onmouseleave="cancelarEliminarGrupoAutomatico('+"'"+'lsgrp_'+this.grupo+"'"+','+"'"+$("#nombreGrupoChat").val()+"'"+')">'	+
			    	
			    	'<div class="contenedorContactoNuevo" id="ctgrp_'+this.grupo+'" onclick="verMiembrosGrupo('+"'"+'ctgrp_'+this.grupo+"'"+')">'+
							'<div class="fotoUsuarioChatNuevo">'+
								'<img src=" aplicaciones/agroChat/img/user2.png" class="imgUsuarioChatNuevo">'+
							'</div>'+
							'<div class="contenedorUsuarioDatosNuevo">'+
								'<div class="nombreGrupos">'+$("#nombreGrupoChat").val()+'</div>'+
								'<div class="contenedorEnviarSolicitud">'+
									'<a class="accionesGrupoChatExistente" id="grp_'+this.grupo+'" onclick="javascript:return mostrarGrupoChat(event,'+"'ctgrp_"+this.grupo+"'"+')">Editar</a>'+
								'</div>'+
							'</div>'+
						'</div>'+
					'</li>';  		    
			    	
			    	}); 
			    	
			    	$("#listaGruposCreados").append(cadenaCreados);
			    	cancelarGrupoChat();
			    	cargarGrupos();
		    	}
		    }
		});
	} else{
		alert("Debe ingresar un nombre de grupo y seleccionar por lo menos un contacto para crear el grupo");
	}
}

function cargarGrupos(){
	
	var bandera = false;
	var lista ;
	$.ajax({
	  	url: 'aplicaciones/agroChat/cargarGrupos.php',
	    method: 'post',
	    data: {usuario: usuarioIdentificadorChat, tipo:'todos'},
	    dataType: "json",
	    cache: false,
    	async:   true,
	    success: function(msg) {
	    	   
	    	if(msg.estado=="exito"){
	    		lista = $("#listaGruposChat li").length ;
    			if($("#listaGruposChat li").length != msg.mensaje.length ){
	    			bandera = true;		    		
		    		$("#listaGruposChat").empty();		    				    			
		    		$(msg.mensaje).each(function(i){	    		    		
				    	var contatChat = this.contacto;
			    		$("#listaGruposChat").append(this.contenido);		  			 
		    		});   		    	
	    		}  
    			
    			$(msg.mensaje).each(function(i){    				
    				var nombreGrupo = this.nombre;
    		    	var contatChat = this.contacto;
    		    	
    	    		$("#listaGruposChat li").each(function(i){
    	    			var item = $(this).children().attr("id").split("_",2);    	    			
    	    			if(item[1] == contatChat){
    	    				if($(this).children().find(".nombreUsuarioChat").text()!=nombreGrupo){
    	    					$(this).children().find(".nombreUsuarioChat").html(nombreGrupo);
    	    					$(this).children().attr("name",nombreGrupo);
    	    					$("#ctgrp_"+contatChat + " .contenedorUsuarioDatosNuevo .nombreGrupos").html(nombreGrupo);
    	    					$("#vcg_"+contatChat).children().find(".cabeceraNombre").html(nombreGrupo);
    	    				}
    	    			}
    	    			
    	    		});
        		});
	        } 
	    },
	    complete: function(msg){	    	
	    	if(bandera){
	    		
	    		$.ajax({
	    		  	url: 'aplicaciones/agroChat/cargarGrupos.php',
	    		    method: 'post',
	    		    data: {usuario: usuarioIdentificadorChat, tipo:'perteneciente'},
	    		    dataType: "json",
	    		    cache: false,
	    	    	async:   false,
	    		    success: function(msg) {	    		    	
	    		    	if(msg.estado=="exito"){	    		    		
	    		    		if(lista < msg.mensaje.length || lista > msg.mensaje.length  ){	
	    		    			$("#listaGruposMiembro").empty();	    		    			
	    			    		$(msg.mensaje).each(function(i){	    		    		
	    	    		    	var contatChat = this.contacto;
	    	    		    		$("#listaGruposMiembro").append(this.contenido);
	    			    		});  		    	
	    		    		}
	    		        }  	
	    		    }
	    	    });
	    	}
	    }
   });
	
	
	
}

function prepararEliminarGrupo(id){
	var grupo = $("#"+id).attr("id").split('_',2);	
	var nombre = $("#"+id).parent().prev().text();

	$(".contenedorAccionesGrupoChat").append('<div class="contenedorConfirmacionEliminar">'+
			'<div class="nombreGrupos">Seguro de eliminar grupo?</div>'+
			'<div class="contenedorEnviarSolicitud">'+
			' <a class="accionesGrupoChat" id="'+"'"+id+"'"+'" onclick="eliminarGrupo('+"'"+id+"'"+')">Si</a>'+
			'<a class="accionesGrupoChat cancelar" onclick="cancelarGrupoChat()">No</a>'+
			'</div>'+
			'</div>');	
}


function prepararSalirGrupo(event,id){	
	event.stopPropagation();
	event.preventDefault();
	
	var grupo = $("#"+id).attr("id").split('_',2);
	var nombre = $("#"+id).parent().prev().text();
	$("#"+id).parent().prev().html("Seguro de dejar grupo?");
	$("#"+id).parent().html('<a class="accionesGrupoChatExistente" id="'+id+'" onclick="salirGrupo('+"'"+id+"'"+')">Si</a>'+
							'<a class="accionesGrupoChatExistente cancelar" onclick="javascript:return cancelarSalirGrupo(event,'+"'"+id+"','"+nombre+"'"+')">No</a>');
}

function eliminarGrupo(id){
	var grupo = $("#"+id).attr("id").split('_',2);
	
	$.ajax({
	  	url: 'aplicaciones/agroChat/eliminarGrupo.php',
	    method: 'post',	
	    data: {grupo: grupo[1]},
	    dataType: "json",
	    cache: false,
		async:   false,
	    success: function(msg) {	
	    	if(msg.estado=='exito'){
	    		$(".contenedorAccionesGrupoChat .contenedorConfirmacionEliminar").html('<span class="textoInformacionChat">Grupo Eliminado</span>');
		    	setTimeout(function() {
		    		$("#ctgrp_"+grupo[1]).hide("slow", function(){ 
		    			$("#ctgrp_"+grupo[1]).closest('li').remove(); 
		    		});
		    	}, 1300);		    	
		    		    		
		    	setTimeout(function() {
		    		$(".contenedorAccionesGrupoChat .contenedorConfirmacionEliminar").hide("slow", function(){ $(".contenedorAccionesGrupoChat .contenedorConfirmacionEliminar").remove(); });
		    	}, 1300);
		    	
		    	$(".contenedorTextosGrupoChat").hide();
		    	$(".contenedorContactosAgregadosGrupoChat").html('').hide();
		    	$(".cuerpoListaContactosGrupoChat ul").html('');
		    	
		    	cargarGrupos();
	    	}
	    },error: function(jqXHR, textStatus, errorThrown){
	    	mostrarMensaje("ERR: " + textStatus + ", " +errorThrown,"FALLO");
	    }
	});
}

function salirGrupo(id){
	var grupo = $("#"+id).attr("id").split('_',2);	
	$.ajax({
	  	url: 'aplicaciones/agroChat/salirGrupo.php',
	    method: 'post',	
	    data: {grupo: grupo[1], usuario: usuarioIdentificadorChat},
	    dataType: "json",
	    cache: false,
		async:   false,
	    success: function(msg) {	
	    	if(msg.estado=='exito'){
	    		$("#"+id).parent().html('<span class="relacionUsuario">Grupo Eliminado</span>');	    		
		    	setTimeout(function() {
		    		$("#ctgrpm_"+grupo[1]).hide("slow", function(){ $("#ctgrpm_"+grupo[1]).closest('li').remove(); });
		    	}, 1300);
		    	cargarGrupos();
	    	}
	    },error: function(jqXHR, textStatus, errorThrown){
	    	mostrarMensaje("ERR: " + textStatus + ", " +errorThrown,"FALLO");
	    }
	});
}

function cancelarEliminarGrupo(id,nombre){
	var grupo = $("#"+id).attr("id").split('_',2);
	$("#"+id).parent().html('<a class="accionesGrupoChatExistente" id="'+id+'" onclick="prepararEliminarGrupo('+"'"+id+"'"+')">Eliminiar grupo</a>');
	$("#"+id).parent().prev().html(nombre);
}

function cancelarEliminarGrupoAutomatico(id,nombre) {
	var grupo = id.split("_",2);
	if($("#"+id).find(".cancelar").length>0){
		cancelarEliminarGrupo("grp_"+grupo[1],nombre);
	}
}

function cancelarSalirGrupo(event,id,nombre){
	event.stopPropagation();
	event.preventDefault();
	
	var grupo = $("#"+id).attr("id").split('_',2);
	$("#"+id).parent().html('<a class="accionesGrupoChatExistente" id="'+id+'" onclick="javascript:return prepararSalirGrupo(event,'+"'"+id+"'"+')">Salir del grupo</a>');
	$("#"+id).parent().prev().html(nombre);
}

function cancelarSalirGrupoAutomatico(id,nombre){
	var grupo = id.split("_",2);
	if($("#"+id).find(".cancelar").length>0){
		cancelarSalirGrupo("grpm_"+grupo[1],nombre);
	}
}

function verMiembrosGrupo(id){
	var grupo = id.split('_',2);
	if(grupo[0]=='ctgrp'){
		if($("#lsgrp_"+grupo[1] + " ul").length ==0){
			$.ajax({
			  	url: 'aplicaciones/agroChat/cargarContactosMiembrosGrupoChat.php',
			    method: 'post',	
			    data: {grupo: grupo[1], usuario: usuarioIdentificadorChat},
			    dataType: "json",
			    cache: false,
				async:   false,
			    success: function(msg) {	
			    	if(msg.estado=='exito'){
			    		$("#listaGruposCreados li").find("ul").remove();
			    		
			    		$("#lsgrp_"+grupo[1]).append("<ul></ul>")
			    		$(msg.mensaje).each(function(){  		    			
			    			$("#lsgrp_"+grupo[1] + " ul").append(this.contenido);		    			
		    			}); 
			    	}
			    },error: function(jqXHR, textStatus, errorThrown){
			    	mostrarMensaje("ERR: " + textStatus + ", " +errorThrown,"FALLO");
			    }
			});
		} else{
			$("#lsgrp_"+grupo[1] +" ul").remove();
		}
	} else{
		if($("#lsgrpm_"+grupo[1] + " ul").length ==0){
			$.ajax({
			  	url: 'aplicaciones/agroChat/cargarContactosMiembrosGrupoChat.php',
			    method: 'post',	
			    data: {grupo: grupo[1], usuario: usuarioIdentificadorChat},
			    dataType: "json",
			    cache: false,
				async:   false,
			    success: function(msg) {	
			    	if(msg.estado=='exito'){
			    		$("#listaGruposMiembro li").find("ul").remove();
			    		
			    		$("#lsgrpm_"+grupo[1]).append("<ul></ul>")
			    		$(msg.mensaje).each(function(){  		    			
			    			$("#lsgrpm_"+grupo[1] + " ul").append(this.contenido);		    			
		    			}); 
			    	}
			    },error: function(jqXHR, textStatus, errorThrown){
			    	mostrarMensaje("ERR: " + textStatus + ", " +errorThrown,"FALLO");
			    }
			});
		} else{
			$("#lsgrpm_"+grupo[1] +" ul").remove();
		}
	}
	cortarCadena(16,'.nombreUsuarioMiembroChat');
}

function actualizarGrupo(){
	var data = new Array();
	var cadenaCreados
	var grupo = $("#grupoChatEditar").text().split("_",2);
	
	$(".contenedorContactoSeleccionado").each(function(){
		var usuario = $(this).attr("id").split('_',2);		
		data.push({identificadorUsuario : usuario[1]});
	});
	
	if($(".contenedorContactoSeleccionado").length > 0 && $("#nombreGrupoChat").val()!=''){
	
		$.ajax({
		  	url: 'aplicaciones/agroChat/actualizarGrupo.php',
		    method: 'post',	
		    data: {data:JSON.stringify(data), grupo: $("#nombreGrupoChat").val(), usuario: usuarioIdentificadorChat, idgrupo:grupo[1]},
		    dataType: "json",
		    cache: false,
	    	async:   false,
		    success: function(msg) {  	
		    	if(msg.estado=='exito'){
		    	
			    	$(msg.mensaje).each(function(i){
			    		
			    	cadenaCreados = '<li class="itemLista" id="lsgrp_'+this.grupo+'" onmouseleave="cancelarEliminarGrupoAutomatico('+"'"+'lsgrp_'+this.grupo+"'"+','+"'"+$("#nombreGrupoChat").val()+"'"+')">'	+
						'<div class="contenedorContactoNuevo" id="ctgrp_'+this.grupo+'">'+
							'<div class="fotoUsuarioChatNuevo">'+
								'<img src=" aplicaciones/agroChat/img/user2.png" class="imgUsuarioChatNuevo">'+
							'</div>'+
							'<div class="contenedorUsuarioDatosNuevo">'+
								'<div class="nombreGrupos">'+$("#nombreGrupoChat").val()+'</div>'+
								'<div class="contenedorEnviarSolicitud">'+									
									'<a class="accionesGrupoChatExistente" id="grp_'+this.grupo+'" onclick="javascript:return mostrarGrupoChat(event,'+"'ctgrp_"+this.grupo+"'"+')">Editar</a>'+
								'</div>'+
							'</div>'+
						'</div>'+
					'</li>';  		    
			    	
			    	}); 
			    	
			    	cancelarGrupoChat();
			    	cargarGrupos();
		    	}
		    }
		});
	} else{
		alert("Debe ingresar un nombre de grupo y seleccionar por lo menos un contacto para crear el grupo");
	}
}

function enviarSolicitud(e){
    
    var elemento;    
	var divContacto = $("#"+e).parent().attr("id");       
    var contactoIdentificador = divContacto.split("_");

	$.ajax({
	    url: 'aplicaciones/agroChat/enviarSolicitud.php',
	    method: 'post',
	    data: {usuario: usuarioIdentificadorChat , contacto: contactoIdentificador[1]},
	    dataType: "json",
	    success: function(msg){				 
	    	if(msg.estado=="exito"){
	    		 $("#"+divContacto).html('<label class="relacionUsuario">Solicitud enviada</label> <a class="cancelarSolicitudLink" onclick="cancelarSolicitud('+"'"+ contactoIdentificador[1] +"'"+ ')" >Cancelar</a>');		    		    		 
	    		 cargarUsuarios();		    		 
	    	} else{			    		
	    		$("#nmu_"+contactoIdentificador[1]).html('<a class="enviarSolicitudLink"  title="Aceptar solicitud" onclick="aceptarSolicitud('+"'"+contactoIdentificador[1]+"'"+')">Aceptar Solicitud</a> <a class="cancelarSolicitudLink"  title="Rechazar solicitud" onclick="rechazarSolicitud('+"'"+contactoIdentificador[1]+"'"+')">Rechazar</a>');
	    		$("#lictn_"+contactoIdentificador[1]).append('<div class="informacionChat" ><span>Ups.. solicitud ya recibida</span><a onclick="cerrarInfo(this,'+"'"+contactoIdentificador[1]+"'"+')" class="cerrarInfo">x</a></div>');
	    		cargarUsuarios();
	    		$("#ctn_"+contactoIdentificador[1]).hide();
	    	}				  
	    }
	    
    });   
}

function cancelarSolicitud(contacto){
	$.ajax({
	    url: 'aplicaciones/agroChat/cancelarSolicitud.php',
	    method: 'post',
	    data: {usuario: usuarioIdentificadorChat , contacto: contacto},
	    dataType: "json",
	    success: function(msg){

	    	cargarUsuarios();
				 
	    	if(msg.estado=="exito"){
	    		 $("#nmu_"+contacto).html('<a class="enviarSolicitudLink" id="btn_' +contacto+ '" onclick="enviarSolicitud('+"'"+'btn_' +contacto+"'"+')" >Enviar solicitud</a>');
	    		 cargarUsuarios();
		    	
	    	} else{
	    		$("#"+divContacto).html('<label class="relacionUsuario">' + msg.mensaje +'</label>');
	    	}
			  
	    }
	    
    });        
}

function aceptarSolicitud(contacto){
	
	$.ajax({
	    url: 'aplicaciones/agroChat/aceptarSolicitud.php',
	    method: 'post',
	    data: {usuario: usuarioIdentificadorChat , contacto: contacto},
	    dataType: "json",
	    async: false,
	    success: function(msg){
				 		
	    	if(msg.estado=="exito"){
	    		if($("#nmu_"+contacto).length > 0){
	    			$("#nmu_"+contacto).html('<label class="relacionUsuario">Amigos</label>');
		    	} 

		    	if($("#sru_"+contacto).length > 0){

		    		$("#sru_"+contacto).html('<label class="relacionUsuario">Solicitud Aceptada</label>');
		    		setTimeout(function() { 							
						$("#csn_"+contacto).hide("slow", function(){ $("#csn_"+contacto).closest('li').remove();listarSolicitudes();notificacionGeneral();});
					}, 1300);				    	
		    	}
		    	
	    	} else if(msg.estado=="cancelada"){	    			
	    		if($("#sru_"+contacto).length > 0){
	    			$("#ctn_"+contacto).hide();
	    			$("#nmu_"+contacto).html('<a class="enviarSolicitudLink" id="btn_' +contacto+ '" onclick="enviarSolicitud('+"'"+'btn_' +contacto+"'"+')" >Enviar solicitud</a>');
		    		$("#lictn_"+contacto).append('<div class="informacionChat" id="informacionChat" ><span>Ups.. la solicitud fue cancelada</span><a onclick="cerrarInfo(this,'+"'"+contacto+"'"+')" class="cerrarInfo">x</a></div>');
		    		
		    		$("#csn_"+contacto).remove();
	    			$("#sru_"+contacto).html('<a class="enviarSolicitudLink" id="btn_' +contacto+ '" onclick="enviarSolicitud('+"'"+'btn_' +contacto+"'"+')" >Enviar solicitud</a>');
		    		$("#listn_"+contacto).append('<div class="informacionChat" id="informacionChat"><span>Ups.. la solicitud fue cancelada</span><a onclick="cerrarInfo(this,'+"'"+contacto+"'"+')" class="cerrarInfo">x</a></div>');
	    		}		    		   	
	    	}	    	
	    	cargarUsuarios();
    		cargarContactos();	    		 
	    }
	    
    });    
    
}

function rechazarSolicitud(contacto){
	$.ajax({
	    url: 'aplicaciones/agroChat/rechazarSolicitud.php',
	    method: 'post',
	    data: {usuario: usuarioIdentificadorChat , contacto: contacto},
	    dataType: "json",
	    success: function(msg){
				 
	    	if(msg.estado=="exito"){
		    	if($("#nmu_"+contacto).length > 0){
	    			$("#nmu_"+contacto).html('<a class="enviarSolicitudLink" id="btn_' +contacto+ '" onclick="enviarSolicitud('+"'"+'btn_' +contacto+"'"+')" >Enviar solicitud</a>');
		    	}
		    	
		    	if($("#sru_"+contacto).length > 0){
		    		$("#sru_"+contacto).html('<label class="relacionUsuario">Solicitud Rechazada</label>');

		    		setTimeout(function() {
						$("#csn_"+contacto).hide("slow", function(){ $("#csn_"+contacto).closest('li').remove();listarSolicitudes();notificacionGeneral(); });
					}, 1300);
		    	}
		    	
	    		 cargarUsuarios();
	    		 cargarContactos();	    		 
		    	
	    	} else{
	    		if($("#nmu_"+contacto).length > 0){
	    			$("#nmu_"+contacto).html('<label class="relacionUsuario">' + msg.mensaje +'</label>');
	    		}
	    		
	    		
	    		if($("#sru_"+contacto).length > 0){
	    			$("#sru_"+contacto).html('<label class="relacionUsuario">' + msg.mensaje +'</label>');
	    		}
	    		
	    	}
			  
	    }
	    
    });    
}

function cargarSolicitudes(){
	$.ajax({
	  	url: 'aplicaciones/agroChat/cargarSolicitudes.php',
		    method: 'post',
		    data: {usuario: usuarioIdentificadorChat},
		    dataType: "json",
		    cache: false,
	    	async:   true,
		    success: function(msg) {
		    
		    	if(msg.estado=="exito"){	    		
		    		var cantidad = $("#listaSolicitudesChat li").length;
    		    	$(msg.mensaje).each(function(i){
	    		    	var contatChat = this.contacto;
	    		    	if ($("#csn_"+contatChat).length <= 0){
	    		    		$("#listn_"+contatChat).remove();
	    		    		$("#listaSolicitudesChat").append(this.contenido);	    		    		
	    		    		// $("#infoctn_"+contatChat).hide();	    		    		
	    		    		//$("#abrirSolicitudes").addClass("notificacionAlerta");
	    		    		cargarUsuarios();	    		    		
	    		    		listarSolicitudes();	    		    	
	    		    		if( !$("#chatSonido").is(":checked")){
								audioSolicitud.play();
								playing = true;
	    		    		}
	    		    		listarSolicitudes();
	    		    		notificacionGeneral();
	    		    	}
	    		    	$("#ctn_"+contatChat).show();		    		    		 
    		    		$("#lictn_"+contatChat+ " .informacionChat").remove();
		    			$("#nmu_"+contatChat).html('<a class="enviarSolicitudLink"  title="Aceptar solicitud" onclick="aceptarSolicitud('+"'"+contatChat+"'"+')">Aceptar Solicitud</a> <a class="cancelarSolicitudLink"  title="Rechazar solicitud" onclick="rechazarSolicitud('+"'"+contatChat+"'"+')">Rechazar</a>');    		    		
		    			
    		    	});
    		    	
    		    	if (cantidad > msg.mensaje.length){
    		    		$("#listaSolicitudesChat li").each(function(i){
    		    			var id = $(this).attr("id");
    		    			var existe = false;
    		    			var usuario = $(this).attr("id").split("_");;
    		    			$(msg.mensaje).each(function(){    		    			        
    		    			    var idUsuario = "listn_"+this.contacto;
    		    			    if(idUsuario==id){
    		    			    	existe=true;
    		    			    }
    		    			}); 
    		    			
    		    			if (!existe){
    		    				$("#"+id).remove();    		    					
    			    			$("#nmu_"+usuario[1]).html('<a class="enviarSolicitudLink" id="btn_' +usuario[1]+ '" onclick="enviarSolicitud('+"'"+'btn_' +usuario[1]+"'"+')" >Enviar solicitud</a>');
    			    			$("#ctn_"+usuario[1]).show();
    			    			$("#lictn_"+usuario[1]+ " .informacionChat").remove();
    			    			listarSolicitudes();
    			    			notificacionGeneral();
    		    			}
    		    			
    		    		});
    		    	}    		    	
    		    	cortarCadena(20,'.nombreUsuarioNuevo');
		    	}  			    	
		    }
   });
	
}	



function cargarContactos(){
	$.ajax({
	  	url: 'aplicaciones/agroChat/cargarContactos.php',
	    method: 'post',
	    data: {usuario: usuarioIdentificadorChat, cantidad: $("#listaContactosChat li").length},
	    dataType: "json",
	    cache: false,
    	async:   true,
	    success: function(msg) {	    	    
	    	if(msg.estado=="exito"){
	    		if($("#listaContactosChat li").length < msg.mensaje.length  ){
	    			$("#listaContactosChat").empty();		    				    			
		    		$(msg.mensaje).each(function(i){	    		    		
		    			var contatChat = this.contacto;	    		    	
    		    		$("#listaContactosChat").append(this.contenido);
    		    		$("#nmu_"+contatChat).html('<label class="relacionUsuario">Amigos</label>');
    		    		cargarUsuarios();
		    		});   		    		    		
		    	
	    		}
	        }  	
	    }
   });
	
}



function cerrarInfo(e,contacto){
	$("#lictn_"+contacto + " .informacionChat").remove();
	$("#listn_"+contacto + " .informacionChat").remove();
	//$(e).closest("div").remove();
	$("#ctn_"+contacto).show();
	$("#listn_"+contacto).remove();
	listarSolicitudes();
}


	
function comprobarRelacion(contacto){		
	$("#"+contacto).css({
	    cursor: "auto",
	    height: "50"	    
	  });	
}

function abrirChat(usuario,grupo){
	
	var contacto=usuario;
	var n;		
	var usuarioContacto =usuario.split("_",2);		
	var pestanias = $('div.mainMensajes').size();
	var fecha = $("#"+usuario).children("span.fechaMensajes").text();
	var idv;
	var idvs;
	var idc;
	var idcs;
	var ido='';
	var cpc='';
	
	if(grupo==null){
		idv='#vc_';
		idvs='vc_';
		idc='#ctc_';
		idcs='ctc_';
		ido='ctc';
		cpc='vcm_';
	} else{
		idv='#vcg_';
		idvs='vcg_';
		idc='#ctcg_';
		idcs='ctcg_';
		ido='ctcg';
		cpc='vcmg_';
	}
		
	if(usuarioContacto[0] ==ido){
		contacto = usuarioContacto[1];
		n= $("div[id='"+usuario+"']").attr('name');
	} else{
		contacto = usuario;
		n= $("div[id='"+idcs+usuario+"']").attr('name');
	}      
	
	if(pestanias<=4){
		
			if ($(idv+contacto).length > 0) {
				
				if(usuarioContacto[0] ==ido){
					
					$(".cabeceraActiva").removeClass('cabeceraActiva');
					$(idv+contacto+" div.cabeceraMensajes").removeClass('nuevoMensaje');					
					$(idv+contacto+" div.cabeceraMensajes").addClass('cabeceraActiva');					
					$(idv+contacto+" .pieMensajes .textoMensajes").focus();
					
					if($("#"+usuario).parent().hasClass("notificacion")){						
						actualizarUltimoMensaje(usuarioContacto[1],fecha,idv);		
						$(idc+contacto).parent().removeClass("notificacion");
						$(idc+usuario +" .fechaUltimoMensaje").html(fecha);
						listarMensajes();
						notificacionGeneral();					
					}
				
				} else{			
					
					if(!$(idv+contacto+" .pieMensajes .textoMensajes").is(":focus")){						
						$(idv+contacto+" div.cabeceraMensajes").addClass('nuevoMensaje');					
						$(idc+contacto).parent().removeClass("notificacion");
						listarMensajes();					
						
						if( !$("#chatSonido").is(":checked")){						
							audioMensaje.play();
				            playing = true;
						}
						
					} else{
						
						if(usuarioContacto[0]!='ctc' || suarioContacto[0]!='ctcg'){							
							fecha = $(idv+usuario).children("div").children("span.fechaMensajes").text();
						}
						
						if(fecha!=''){
							actualizarUltimoMensaje(usuario,fecha,idv);
							$(idc+usuario +" .fechaUltimoMensaje").html(fecha);
						}
					}
				}
				
				
				
			} else{	
				
				$("#contenedorChatVentanas").append(
						'<div class="mainMensajes" id="'+idvs+contacto+'" >' + 
							'<div class="cabeceraMensajes" onClick="capturarClick(3)">'+
								'<div class="cabeceraNombre">'+n+ '</div><div class="cerrarMensajes" onClick="cerrarChat(this)">x</div>'+
							'</div>'+
							'<div class="cuerpoMensajes" onClick="capturarClick(1)" id="'+cpc+contacto+'" onscroll="inicioScroll(this,'+"'"+cpc+contacto+"'"+')"><div class="imgLoadMensajes"><img class="loader" src="aplicaciones/agroChat/img/15.gif"></div><table  class="tablaChat"></table></div>'+
							'<div class="pieMensajes" onClick="capturarClick(1)" >'+
								'<div contenteditable="true" tabindex="0" onClick="capturarClick(1)" class="textoMensajes" placeholder="Escribir mensaje..." onkeydown="javascript:return enviarMensaje(event,this);" onkeyup="tamanioCaja(this)"></div>'+
								'<div class="enviarEmoji" onclick="javascript:return seleccionarEmoji(event,this)"> </div>'+
								'<span class="increMensajes" style="display:none;">0</span><span class="fechaMensajes" style="display:none;"></span>'+
							'</div>'+
							'<span class="ultimoMensaje">0</span>'+
						'</div>'
				 );
				
				listarMensajes();
				comprobarMensajes();
				
				var user2= idvs+ contacto;
				var user= idvs+ contacto;
				
				chatBoxes.push(user);	
				restructureChatBoxes();				
				
				if(usuarioContacto[0] ==ido){
					
					$(".cabeceraActiva").removeClass('cabeceraActiva');
					$(idv+contacto+" div.cabeceraMensajes").removeClass('nuevoMensaje');
					$(idv+contacto+" div.cabeceraMensajes").addClass('cabeceraActiva');
					$(idv+contacto+" .pieMensajes .textoMensajes").focus();
					listarMensajes();
					notificacionGeneral();
					if(fecha!=''){
						
						if($("#"+usuario).parent().hasClass("notificacion")){						
							actualizarUltimoMensaje(usuarioContacto[1],fecha,idv);		
							$(idc+contacto).parent().removeClass("notificacion");
							listarMensajes();
							notificacionGeneral();						
						}
					}
					
				} else{
					
					$(idv+contacto+" div.cabeceraMensajes").addClass('nuevoMensaje');
					if( !$("#chatSonido").is(":checked")){
						audioMensaje.play();
						playing = true;
					}
				}
				
				funcionScroll("#"+user);
			}

		cortarCadena(20,'.cabeceraNombre');
	
	} else{
		alert("Solo puede tener 5 conversaciones abiertas simultaneamente");
	}
}

function seleccionarEmoji(event,e){
	event.stopPropagation();
	event.preventDefault();

	var padre = $(e).parent().parent().attr("id");	
	var display = $("#"+padre +" .pieMensajes .enviarEmoji .contenedorEmoji").css("display");

	if ($("#"+padre +" .pieMensajes .enviarEmoji .contenedorEmoji .emojiVista").length <= 0 ){

		$.ajax({
	    url: 'aplicaciones/agroChat/obtenerEmojis.php',
	    method: 'post',	   
	    data: {tipo:'lista'},
	    dataType: "json",
	    success: function(msg){				 
	    	if(msg.estado=="exito"){	
	    		$("#"+padre +" .pieMensajes .enviarEmoji").append('<div class="contenedorEmojiPrincipal" onclick="javascript:return cancelarEvento(event)"><div class="contenedorEmoji"></div></div>');
	    		
	    		$(msg.mensaje).each(function(i){
	    			$("#"+padre +" .pieMensajes .enviarEmoji .contenedorEmojiPrincipal .contenedorEmoji").append(this.contenido);
	    		});    		
	    		
	    	}
	    }	    
    });
	}
}

function cancelarEvento(event){	
	event.stopPropagation();
	event.preventDefault();
}


function colocarEmoji(e){
	var ruta = $(e).css('background-image');
	var padre = $(e).closest('.mainMensajes').attr("id");
	var nombre = $(e).attr('name');
	var n ;	
	ruta = ruta.replace('url(','').replace(')','').replace(/\"/gi, "");  
	var finalCadena = ruta.length;
	n = ruta.indexOf('agrodb') + 7;	
	ruta = ruta.slice(n);
	$("#"+padre +" .pieMensajes .textoMensajes").append('<img class="emoji" name="'+nombre+'" src="'+ruta+'" />');
}

window.onclick = function(event) {
	$(".enviarEmoji").html('');
}

function obtenerNuevosMensajes() {

	var usuario= usuarioIdentificadorChat;
	var data = new Array();
	var grupos = new Array();
	$("#listaContactosChat li").each(function(){
		contactosChat=[];
		fechaContactosChat=[];			
	 	var chatContacto = $(this).children("div").attr("id");
	 	var contacto =chatContacto.split("_");	
	 	var fechaChatContacto = $(this).children("div").children("span.fechaMensajes").text();
	 	contactosChat.push(contacto[1]);
	 	fechaContactosChat.push(fechaChatContacto);	   		
   		data.push({identificadorUsuario : usuario, identificadorContacto : contacto[1], fecha : fechaChatContacto });	    		
	});	
	
	$("#listaGruposChat li").each(function(){
		grupoChat=[];
		fechaGrupoChat=[];			
	 	var chatGrupo = $(this).children("div").attr("id");
	 	var grupo =chatGrupo.split("_");	
	 	var fechaChatgrupo = $(this).children("div").children("span.fechaMensajes").text();	 	
	 	grupoChat.push(grupo[1]);
	 	fechaGrupoChat.push(fechaChatgrupo);
	 	grupos.push({grupo : grupo[1], fecha : fechaChatgrupo });	    		
	});	
			
	$.ajax({
	  	url: 'aplicaciones/agroChat/obtenerMensajes.php',
		    method: 'post',	
		    data: {data:JSON.stringify(data),grupo:JSON.stringify(grupos),usuario:usuario},
		    dataType: "json",
		    cache: false,
	    	async:   true,
		    success: function(msg) { 
		    	if(msg.estado=="exito"){			    		
    		    	$(msg.mensaje).each(function(i){
	    		    	
	    		    	var contatChat;
	    		    	var ventana=0;
	    		    	var idv;
	    		    	var idvs;
	    		    	var idc;
	    		    	var idcs;
	    		    	var ido='';
	    		    	
	    		    	if(this.grupo==null){
	    		    		idv='#vc_'; // id del div contenedor de las ventanas de chat de contactos
	    		    		idvs='vc_';
	    		    		idc='#ctc_'; // id del div contenedor de los contactos agregados de cada usuario
	    		    		idcs='ctc_';
	    		    		ido='ctc';
	    		    		contatChat = this.contacto;	    		    		
	    		    	} else{
	    		    		idv='#vcg_'; // id del div contenedor de las ventanas de chat de los grupos
	    		    		idvs='vcg_';
	    		    		idc='#ctcg_'; // id del div contenedor de los grupos a los que pertenece cada usuario
	    		    		idcs='ctcg_';
	    		    		ido='ctcg';
	    		    		contatChat = this.grupo;
	    		    	}	    		    	
	    		    	
	    		    	if ($(idv+contatChat).length > 0) {
		    		    	ventana=1;
	    		    	}
	    		    	
	    		    	$(idc+contatChat +" .fechaMensajes").html(this.fecha);
	    		    	$(idv+contatChat+' .pieMensajes .fechaMensajes').html(this.fecha);
	    		    	
	    		    	abrirChat(contatChat,this.grupo);
	    		    	
	    		    	if(ventana==1){	    		    		
		    		    	if ($(idv+contatChat).length > 0) {
		    		    		
		    		    		$(idv+contatChat+ ' .cuerpoMensajes .tablaChat').append(this.contenido);
				  			    $(idv+contatChat+ ' .pieMensajes .fechaMensajes').html(this.fecha);
				  			    $(idv+contatChat+ ' div.cuerpoMensajes ').scrollTop($(idv+contatChat+' div.cuerpoMensajes').prop("scrollHeight"));
				  			    
					  			if( !$("#chatSonido").is(":checked")){
					  				audioMensaje.play();
							        playing = true;
								}
					  			  
					  			if( !$(idv+contatChat+ ' .pieMensajes .textoMensajes').is(':focus')){					  				
					  				$(idc+contatChat).parent().addClass("notificacion");
									listarMensajes();
					  			} else{
					  				$(idc+contatChat).parent().removeClass("notificacion");
									listarMensajes();
					  			}
							
		    		    	}
	    		    	}
	    		    	    		    	    		  				  			

    		    	});
		    	
		    	}
		    }
		   
   });
}

function actualizarUltimoMensaje(contacto,fecha,tipo){	
	
	$.ajax({
	  	url: 'aplicaciones/agroChat/actualizarUltimoMensaje.php',
		    method: 'post',
		    data: {usuario: usuarioIdentificadorChat, contacto: contacto, fecha: fecha, tipo: tipo},
		    dataType: "json",
		    cache: false,
	    	async:   false,
		    success: function(msg) {		    	    
		    	if(msg.estado=="exito"){
			    	
		        }  	
	    }
   });
	
}

function cerrarChat(v){
	var idChat = $(v).parent().parent().attr('id');

	$("#"+idChat).remove();
	$("#"+idChat +" div.pieMensajes .increMensajes").html('0');	
	var index = chatBoxes.indexOf(idChat);
	if (index > -1) {
		 chatBoxes.splice(index, 1);
    }	
	restructureChatBoxes();
}


function restructureChatBoxes() {	
	
	align = 0;
	var espacio=0;
	var width=0;
	if($(".mainChat").css('display')=='none'){
		espacio=15;			
	}else{
		espacio=240;
	}

	for (x in chatBoxes) {			
		chatboxtitle = chatBoxes[x];
		//if ($("#chatbox_"+chatboxtitle).css('display') != 'none') {
			if (align == 0) {										
				$("#"+chatboxtitle).css('right', espacio+'px');
			} else {					
				width = (align)*(212+7)+espacio;
				$("#"+chatboxtitle).css('right', width+'px');
			}
			align++;
		//}		
	}		
}

function capturarClick(v){
	if(v==1){
		document.onclick = activarChat;		
	}else if(v==2){
		document.onclick = desactivarChat;		
	}else if(v==3){
		document.onclick = minimizarChat;		
	}
}
	
function activarChat(e){	
	var elemento;
	var vChat;
	if (e == null) {			
		elemento = event.srcElement;
	} else {			
		elemento = e.target;
	}		
	
	clase=elemento.className;	
	
	if(clase=="textoMensajes"){			
		vChat=$(elemento).parent().parent().attr('id');
	} else if(clase=="msgUsuario" || clase=="msgContacto"){
		vChat=$(elemento).parents(".mainMensajes").attr('id');
	} else if(clase=="cuerpoMensajes"){
		vChat=$(elemento).parent().attr('id');
	} else if(clase=="pieMensajes"){
		vChat=$(elemento).parent().attr('id');
	} else{			
		vChat=$(elemento).parents(".mainMensajes").attr('id');
	}
	
	var usuario = vChat.split("_");
	var idc;
	var tipo;
	if (usuario[0]=='vc'){
		idc='#ctc_';
		tipo='#vc_';
	} else{
		idc='#ctcg_';		
	}
	var fecha = $(idc+usuario[1]).children("span.fechaMensajes").text();
	
	if(!$("#"+vChat + " div:first-child").hasClass("cabeceraActiva")){			
		$("#"+vChat+" div.cabeceraMensajes").removeClass('nuevoMensaje');		
		$(".cabeceraActiva").removeClass('cabeceraActiva');
		$("#"+vChat+" div.cabeceraMensajes").addClass('cabeceraActiva');
		$("#"+vChat+" .pieMensajes .textoMensajes:first-child").focus();
		
		if($(idc+usuario[1]).parent().hasClass("notificacion")){
			actualizarUltimoMensaje(usuario[1],fecha,tipo);
			$(idc+usuario[1] +" .fechaUltimoMensaje").html(fecha);
			$(idc+usuario[1]).parent().removeClass("notificacion");			
			listarMensajes();
			notificacionGeneral();
		}
		
	} else{			
		if(clase=="msgUsuario" || clase=="msgContacto"){
			
		} else{
			$("#"+vChat+" div.cabeceraMensajes").removeClass('nuevoMensaje');		
			$(".cabeceraActiva").removeClass('cabeceraActiva');
			$("#"+vChat+" div.cabeceraMensajes").addClass('cabeceraActiva');
			$("#"+vChat+" .pieMensajes .textoMensajes:first-child").focus();			
						
			if($(idc+usuario[1]).parent().hasClass("notificacion")){			
				actualizarUltimoMensaje(usuario[1],fecha,tipo);
				$(idc+usuario[1] +" .fechaUltimoMensaje").html(fecha);
				$(idc+usuario[1]).parent().removeClass("notificacion");				
				listarMensajes();
				notificacionGeneral();					
			}
			
		}
		
	}
	
}

function desactivarChat(e) {
	var elemento;
	if (e == null) {			
		elemento = event.srcElement;
	} else {
		elemento = e.target;		
	}		
	
	clase=elemento.className;		
	if(clase!="textoMensajes" && clase!="cuerpoMensajes" && clase!="cabeceraNombre" && clase!="cabeceraMensajes cabeceraActiva" && clase!="cabeceraNombre cabeceraActiva" && 
	   clase!="nombreUsuarioChat" && clase!="cerrarMensajes" && clase!="contenedorContactoChat" && clase!="imgUsuarioChat" && clase!="enLinea"){
		$(".cabeceraActiva").removeClass('cabeceraActiva');
	}
}

function minimizarChat(e){	
	var elemento;
	var vChat;
	if (e == null) {			
		elemento = event.srcElement;
	} else {			
		elemento = e.target;
	}	
	clase=elemento.className;	
	if(clase=="cabeceraNombre cabeceraActiva" || clase=="cabeceraNombre"){
		vChat=$(elemento).parent().parent().attr('id');
		$(".cabeceraActiva").removeClass('cabeceraActiva');
		$(".nuevoMensaje").removeClass('nuevoMensaje');
	} else if(clase=="cabeceraMensajes cabeceraActiva" || clase=="cabeceraMensajes" ){
		vChat=$(elemento).parent().attr('id');	
		$(".cabeceraActiva").removeClass('cabeceraActiva');
		$(".nuevoMensaje").removeClass('nuevoMensaje');
	}
	$("#"+vChat+" div.cuerpoMensajes").slideToggle();
	$("#"+vChat+" div.pieMensajes").slideToggle();
	$("#"+vChat+" div.cabeceraMensajes").addClass('cabeceraActiva');
	$("#"+vChat+" .pieMensajes textarea:first-child").focus();
	
	if(vChat!=undefined){
		var usuario = vChat.split("_");
		var fecha = $("#ctc_"+usuario[1]).children("span.fechaMensajes").text();
		if($("#ctc_"+usuario[1]).parent().hasClass("notificacion")){		
			actualizarUltimoMensaje(usuario[1],fecha);
			$("#ctc_"+usuario[1] +" .fechaUltimoMensaje").html(fecha);
			$("#ctc_"+usuario[1]).parent().removeClass("notificacion");		
			listarMensajes();
			notificacionGeneral();					
		}
	}
}


function enviarMensaje(event,chatboxtextarea) {	
	
	var textoMensaje = $(chatboxtextarea).text();
	idPadre=$(chatboxtextarea).parent().parent().attr("id");
	usuario = usuarioIdentificadorChat;
	var idv = idPadre.split('_');	
	
	if(event.keyCode == 13 && event.shiftKey == 0)  {
		mensaje = $(chatboxtextarea).html();
		mensaje = mensaje.replace(/^\s+|\s+$/g,"");		
		$(chatboxtextarea).css('height','28px');
		$("#" +idPadre+ " div.pieMensajes").css('height','29px');		
		$(chatboxtextarea).focus();
		$(chatboxtextarea).attr('rows', '1');
		
		if (mensaje != '') {
			
		    $.ajax({
			    url: 'aplicaciones/agroChat/enviarMensaje.php',
			    method: 'post',
			    dataType: "json",
			    data: {mensaje: mensaje, usuario: usuario, contacto: idPadre, tipo: idv[0]},
			    success: function(data){			    	
			    	if(data.estado="exito"){
			    		cade = data.mensaje;			    		
			    		$("#" +idPadre+ " div.cuerpoMensajes .tablaChat").append('<tr style="width:205px;"> <td style="width:205px;"><div class="msgUsuario">'+data.mensaje+'</div></td></tr>');
			    		$("#" +idPadre+ " div.cuerpoMensajes ").scrollTop($("#" +idPadre+" div.cuerpoMensajes").prop("scrollHeight"));
						$("#" +idPadre+ " div.pieMensajes .fechaMensajes ").html(data.fecha);
						$(chatboxtextarea).html('');
			    	}	
			    }
		    });
		}
		return false;
	} else if(event.keyCode == 8 || event.keyCode == 46){
		$(chatboxtextarea).css('height','28px');
		$("#" +idPadre+ " div.pieMensajes").css('height','29px');	
	}
			
	cuerpo=$("#"+idPadre+" div:nth-child(2)");
	pie=$("#"+idPadre+" div:nth-child(3)");
	
	var tamanioCaja = chatboxtextarea.clientHeight;
	var tamanioMaximo = 53;		
	if (tamanioMaximo > tamanioCaja) {		
		tamanioCaja = Math.max(chatboxtextarea.scrollHeight, tamanioCaja);
		if (tamanioMaximo)			
			tamanioCaja = Math.min(tamanioMaximo, tamanioCaja);			
		if (tamanioCaja > chatboxtextarea.clientHeight){
			$(chatboxtextarea).css('height',tamanioCaja+5+'px');
			$(pie).css('height',tamanioCaja+10 +'px');	
		}
	} else {
		$(chatboxtextarea).css('overflow-y','auto');
	}	 
}

function tamanioCaja(chatboxtextarea){
	var textoMensaje = $(chatboxtextarea).text();
	idPadre=$(chatboxtextarea).parent().parent().attr("id");
	cuerpo=$("#"+idPadre+" div:nth-child(2)");
	pie=$("#"+idPadre+" div:nth-child(3)");
	
	var tamanioCaja = chatboxtextarea.clientHeight;
	var tamanioMaximo = 53;		
	if (tamanioMaximo > tamanioCaja) {		
		tamanioCaja = Math.max(chatboxtextarea.scrollHeight, tamanioCaja);
		if (tamanioMaximo)			
			tamanioCaja = Math.min(tamanioMaximo, tamanioCaja);			
		if (tamanioCaja > chatboxtextarea.clientHeight){
			$(chatboxtextarea).css('height',tamanioCaja+5+'px');
			$(pie).css('height',tamanioCaja+10 +'px');	
		}
	} else {
		$(chatboxtextarea).css('overflow-y','auto');
	}	 
}

function inicioScroll(e,elemento){
	var id= elemento.split('_',2);
	
	var index;
	if(id[0]=='vcm'){
		index='#vc_';
		
	} else{
		index='#vcg_';
	}
	
	if($(index+id[1] + " .ultimoMensaje").html()=="0"){		
		if ($(e).scrollTop()== 0) {
			padre=$(e).attr('id');			
			$('#'+padre+ ' .imgLoadMensajes').show();			
	
			setTimeout(function() { 
				funcionScroll($(e).attr('id'));
			}, 400);
		}
	}
}


function scroll(e){
	if ($(e).scrollTop()== 0) {
		padre=$(e).attr('id');			
		$('#'+padre+ ' .imgLoadMensajes').show();			

		setTimeout(function() { 
			funcionScroll($(e).attr('id'));
		}, 400);
	}
}

identificadorUsuario=0;
identificadorContacto=0;
incremento = 8;
		
function funcionScroll(e){

	var usuario =e.split("_");
	identificadorContacto=usuario[1];
	identificadorUsuario= usuarioIdentificadorChat;
	var datoIncremento =0;
	var numeroMensaje=0;
	var indice;
	var indiceS;
	var cuerpo;
	
	if(usuario[0]=='#vc' || usuario[0]=='vcm'){
		indice='#vc_';
		indiceS='#vc';
		cuerpo= '#vcm_';
		cuerpoS= 'vcm';
	} else{
		indice='#vcg_';
		indiceS='#vcg';
		cuerpo= '#vcmg_';
		cuerpoS= 'vcmg';
	}
	
	if(usuario[0]=='#vc' || usuario[0]=='#vcg'){
		datoIncremento=$(e+ " div.pieMensajes .increMensajes").html();
	} else{
		datoIncremento=$(indice+usuario[1]+ " div.pieMensajes .increMensajes").html();
	}
	
	
	var data = new Array();
	var $formulario = $(this);
	
	data.push({
		name : 'incremento',
		value : incremento
	}, {
		name : 'datoIncremento',
		value : datoIncremento
	}, {
		name : 'identificadorUsuario',
		value : identificadorUsuario
	},{
		name : 'identificadorContacto',
		value : identificadorContacto
	}
	,{
		name : 'tipo',
		value : indice
	}
	);		
	
	if($(indice+identificadorContacto + " .ultimoMensaje").html()=="0"){
	
		resultado = $.ajax({
		    url: "aplicaciones/agroChat/cargarDatosConversaciones.php",
		    type: "post",
		    data: data,
		    dataType: "json",
		    async:   false,
		    beforeSend: function(){
		    	$("#estado").html('').removeClass();    		   		    	
			},    			
		    success: function(msg){
		    	if(msg.estado=="exito"){
			    	$(msg.mensaje).each(function(i){
			    		if(usuario[0]==indiceS){
	    		    		$(e+" div.cuerpoMensajes .tablaChat").prepend(this.contenido);
	    		    		$(e+" div.cuerpoMensajes ").scrollTop($(e+" div.cuerpoMensajes").prop("scrollHeight"));
	    		    		if(numeroMensaje==0){
	    		    			$(e+" div.pieMensajes .fechaMensajes").html(this.fecha);
	    		    		}
	    		    		$("#ctc_"+e +".fechaMensajes").html(this.fecha);
	    		    		numeroMensaje+=1;
			    		}
			    		else{
			    			$("#"+e +" .tablaChat").prepend(this.contenido);
			    		}
	    			});    		
		    	} else if(msg.estado=="vacio"){		    		
		    		if(usuario[0]==cuerpoS){		    			
		    			$(indice+identificadorContacto +" .ultimoMensaje").html('vacio');
		    		}
		    	}
		   },
		    error: function(jqXHR, textStatus, errorThrown){
		    	$("#cargando").delay("slow").fadeOut();
		    	mostrarMensaje("ERR: " + textStatus + ", " +errorThrown,"FALLO");
		    },
	        complete: function(){
		        if(usuario[0]==indiceS){
	        		datoIncremento= parseInt($(e+ " div.pieMensajes .increMensajes").html());
		        }else{
		        	datoIncremento= parseInt($(indice+usuario[1]+" div.pieMensajes .increMensajes").html());
		        }
	        	datoIncremento = datoIncremento +8;   	        	
	
	        	if(usuario[0]==indiceS){
	        		$(e+ " div.pieMensajes .increMensajes").html(datoIncremento);    	        		
	        	} else{
	        		$(indice+usuario[1]+" div.pieMensajes .increMensajes").html(datoIncremento);	        		
	        		if($(cuerpo+usuario[1]).scrollTop()== 0) {        	        		
	        			$(cuerpo+usuario[1]).scrollTop(25);   					    				
					}	    	        	
	        	}
	        	$(indice+usuario[1]+" div.cuerpoMensajes .imgLoadMensajes").hide();
	        	$("#cargando").delay("slow").fadeOut();        
	        				
	        }
		});		
	} else{
		$(indice+usuario[1]+" div.cuerpoMensajes .imgLoadMensajes").hide();		
	}
}

function cortarCadena(valor,clase) {
	$(clase).each(function() {
	var maximo = valor;
	var str = $(this).text();
	var patt = new RegExp(/[,;.:!()&\s$]/g);
	if (str.length > maximo) {
		if(patt.test(str[maximo-1])) {
	        $(this).text(str.substr(0, maximo-1) + "...");
	    } else {
	        $(this).text(str.substr(0, maximo) + "...");     
	    }
	} 
	});
}

function listarSolicitudes(){
	var cantidad = $("#listaSolicitudesChat li").length;
	$("#abrirSolicitudes").html('<span class="notificacionIconos"><strong>'+cantidad+'</strong>');
	if(cantidad==0){
		$("#abrirSolicitudes").html("");
	}
}

function listarMensajes(){
	var cantidad = $("#listaContactosChat li.notificacion").length;
	var cantidadG = $("#listaGruposChat li.notificacion").length;
	total=cantidad+cantidadG;
	$("#abrirContactos").html('<span class="notificacionIconos"><strong>'+total+'</strong>');
	if(cantidadG==0 && cantidad==0){
		$("#abrirContactos").html("");		
	}
}

function comprobarMensajes(){
	$("#listaContactosChat li").each(function(i){
		var id = $(this).attr("id");
		var mensajeEnviado = $(this).children("div").children("span.fechaMensajes").html().slice(0, -3);
		var mensajeVisto = $(this).children("div").children("span.fechaUltimoMensaje").html();

		if(mensajeEnviado !=''){
			var fechaVisto = new Date(Date.parse(mensajeVisto));
			var fechaEnviado = new Date(Date.parse(mensajeEnviado));
			
			if(mensajeVisto !=''){				
				if(fechaVisto < fechaEnviado){					
					$("#"+id).addClass("notificacion");
					listarMensajes();
					notificacionGeneral();
				}
			} else{
				$("#"+id).addClass("notificacion");
				listarMensajes();
				notificacionGeneral();
			}
		}
	});	
	
	$("#listaGruposChat li").each(function(i){
		var id = $(this).attr("id");
		var mensajeEnviado = $(this).children("div").children("span.fechaMensajes").html().slice(0, -3);
		var mensajeVisto = $(this).children("div").children("span.fechaUltimoMensaje").html();

		if(mensajeEnviado !=''){
			var fechaVisto = new Date(Date.parse(mensajeVisto));
			var fechaEnviado = new Date(Date.parse(mensajeEnviado));
			
			if(mensajeVisto !=''){				
				if(fechaVisto < fechaEnviado){					
					$("#"+id).addClass("notificacion");
					listarMensajes();
					notificacionGeneral();
				}
			} else{
				$("#"+id).addClass("notificacion");
				listarMensajes();
				notificacionGeneral();
			}
		}
	});	
}

function notificacionGeneral(){
	if($("#abrirContactos").children("span").length > 0 || $("#abrirSolicitudes").children("span").length > 0){		
		$("#notificacionMensaje").addClass("notificacionAlerta");
		$("#notificacionMensaje").html('<span class="notificacionGeneral"></span>');
	} else{
		$("#notificacionMensaje").removeClass("notificacionAlerta");
		$("#notificacionMensaje").html('');
	}
}