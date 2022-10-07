<?php 	
$ipMicroscopio = $_POST['id'];

switch ($ipMicroscopio){
	case '181.112.155.162':
		$puerto = '554';
		$titulo = 'TUMBACO';
		break;
	case '181.112.155.164':
		$puerto = '8080';
		$titulo = 'GUAYAQUIL';
		break;
	case '181.112.155.169':
		$puerto = '554';
		$titulo = 'CUENCA';
		break;
	case '181.112.155.174':
		$puerto = '554';
		$titulo = 'LOJA';
		break;
	default:
		echo 'desconocido';
}
?>

<!DOCTYPE html >
<html xmlns="http://www.w3.org/1999/xhtml">

<header>
	<h1>
		Microscopio
		<?php echo $titulo;?>
	</h1>
</header>

<div id="vlcMessage">
	<ul id="nav">
		Usted no esta utilizando la versión recomendada de vlc (2.0.5).
		<br> ¿Desea mantener esta versión? Mantener esta versión podria causar problemas en la visualización.
		<li><a href="javascript:closeDialog()"
			title="You will maybe have some problems to display the images">Si</a>
		</li>
		<li><a
			href="ftp://ftp.olympus-sis.com/pub/download/viewer/vlc-2.0.5-Netcam.exe"
			title="You will be prompt to download the recommended version of Videolan VLC">No</a>
		</li>
		<li><a href="javascript:SetDoNotAskAgain()"
			title="You will not be asked again">No preguntar de nuevo</a></li>
	</ul>
</div>

<div id="status">&nbsp;</div>
<div id="vlcContainerPlayer"></div>
<div id="vlcContainerControl"></div>

<td class="footer">&nbsp;</td>

<NOSCRIPT>
	<CENTER>
		<br> Java scripting is required for playback of the NetCam video.
		Please activate Java scripting in the security settings of your
		browser.
	</CENTER>
</NOSCRIPT>
<p align="right"></p>

<script type="text/javascript">

	var ip_microscopio = <?php echo json_encode($ipMicroscopio); ?>;
	var puerto = <?php echo json_encode($puerto); ?>;
	
	var BROWSER_MSIE      = 1;
	var BROWSER_FIREFOX   = 2;        
	var SOURCE_WIDTH      = 500; 
	var SOURCE_HEIGHT     = 400; 
	var HTTP_CACHING      = 200;
	var NO_STREAM_MESSAGE = "No stream available!";
	var NETCAM_EXE_NAME   = "vlc-NetCam.exe";
	var STR_SOURCE_WIDTH      = "500px"; 
	var STR_SOURCE_HEIGHT     = "400px"; 
	         
	var playerDst       = "vlcContainerPlayer";
	var controlDst      = "vlcContainerControl";
	var browser         = 0;
	var isVlcInstalled  = false;    

	mustDownload=false;

	$("document").ready(function(){	
		iniciarVisor();
		//document.vlc.playlist.stop();
  		//document.vlc.playlist.play();
	});

	function getCookie(c_name){
		var i,x,y,ARRcookies=document.cookie.split(";");
		for (i=0;i<ARRcookies.length;i++){
	  		x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
	  		y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
	  		x=x.replace(/^\s+|\s+$/g,"");
	  if (x==c_name){
	    return unescape(y);
	    }
	  }
	}

	function setCookie(c_name,value,exdays){
		var exdate=new Date();
		exdate.setDate(exdate.getDate() + exdays);
		var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
		var cookie = c_name + "=" + c_value;
		document.cookie= cookie;
	}

	function GetDoNotAskAgain(){
		var value = getCookie("DoNotAskAgain");
		if (value == null)
			return false;
		return (value==1);
	}
	
	function SetDoNotAskAgain(){
    	setCookie("DoNotAskAgain",1,180);
		closeDialog();
	}

	function OSISShow(itemID){ 
	  var element = document.getElementById(itemID);
      element.style.display = 'block'; 
  	}
  	
	function OSISHide(itemID){ 
		var element = document.getElementById(itemID);
      	element.style.display = 'none'; 
  	}
 
	function initMessageDialog(){
		OSISShow('vlcMessage');
	}

	function closeDialog(){
		OSISHide('vlcMessage');
	}
	
	function Reconnect(){
  		document.vlc.playlist.stop();
  		document.vlc.playlist.play();
	}
	
	function checkvlcVersion(){
	// return true is the vlc verdsion is good
	var vlcVersion = document.getElementById("vlc").VersionInfo;
	if (vlcVersion == "2.0.5 Twoflower")
		return true;
	return false;
	}
                                            
    function getInternetExplorerVersion() {
        // Returns the version of Windows Internet Explorer or a -1
        // (indicating the use of another browser).       
		var rv = -1;
		if (navigator.appName == 'Microsoft Internet Explorer'){
			var ua = navigator.userAgent;
			var re  = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");
			if (re.exec(ua) != null)
			  rv = parseFloat( RegExp.$1 );
		  }else if (navigator.appName == 'Netscape'){
				var ua = navigator.userAgent;
				var re  = new RegExp("Trident/.*rv:([0-9]{1,}[\.0-9]{0,})");
			if (re.exec(ua) != null)
			  	rv = parseFloat( RegExp.$1 );
		  }
		  return rv;
        }                                         
        //////////////////////////////
        // browser check
        function browserCheck(){
            var ua = navigator.userAgent.toLowerCase();                        
            var ieversion =  getInternetExplorerVersion();                 
            
            if (ua.indexOf("firefox") != -1) {                           
               browser = BROWSER_FIREFOX;               
               return true;
            }else if (ieversion >= 6.0){                
                browser = BROWSER_MSIE;                          
                return true;
            }
            
            // browser not supported
            browser = 0;
            return false;            
        }
        
        ///////////////////////////////////
        // check vlc firefox plugin
        function vlcCheckFirefox(){                                                                               
			if(navigator.mimeTypes['application/x-vlc-plugin'])
				if(navigator.mimeTypes['application/x-vlc-plugin'].enabledPlugin)
					return true;
          
			var vlcContainer  = document.getElementById(playerDst);
			vlcContainer.innerHTML = "Descargar <a href='ftp://ftp.olympus-sis.com/pub/download/viewer/vlc-2.0.5-Netcam.exe'>NetCam Visor</a>";
			mustDownload=true;
                            
            return false;            
        }
		
        ///////////////////////////////////////
        // check vlc msie plugin
        function vlcCheckMsie(){
			var vlcContainer = document.getElementById(playerDst);
          
			if (document.getElementById("vlc"))
				if ( document.getElementById("vlc").VersionInfo != null)
					return true;
          
			vlcContainer.innerHTML = "Descargar <a href='ftp://ftp.olympus-sis.com/pub/download/viewer/vlc-2.0.5-Netcam.exe'>NetCam Visor</a>";
			mustDownload=true;
			return false;                                                   
        }
        
        /////////////////////////////////////
        // initialisation
        function iniciarVisor(){
			// first close the dialog
			closeDialog();
            var vlcContainer = document.getElementById(playerDst);
            var arrStreams = ['http','http://'+ip_microscopio+':'+puerto+'/stream'];

            //alert(arrStreams);
 
            var html = "";                        
            
            if(arrStreams.length > 2){
                // add streams
                for(i=0; i < arrStreams.length; i++){                
                    html += "<a href=\"javascript:\" onclick=\"loadPlayer('" + arrStreams[i+1] + 
                                "')\">" + arrStreams[i] + "</a><br/>";                               
                    i++;                    
                }          
               
                // change content in vlcContainer
                vlcContainer.innerHTML = html;
            }else if(arrStreams.length == 2){
                // only one stream available
                loadPlayer(arrStreams[1]);
            }else{
                // no stream available
                vlcContainer.innerHTML = NO_STREAM_MESSAGE;
            }     
			// show the message dialog if needed
			 if(browserCheck()){ 
				if ((mustDownload != true) && (checkvlcVersion() != true) && (GetDoNotAskAgain() != true))
				initMessageDialog();
			}
        }
        
        /////////////////////////////////////
        // load vlc player
        function loadPlayer(mrl){  
      
            var vlcContainer  = document.getElementById(playerDst);
            var vlcControl = document.getElementById(controlDst);
            
            if(browserCheck()){                
                vlcContainer.innerHTML = "loading ...";                
                
                if(browser == BROWSER_MSIE){ // Microsoft Internet Explorer
                    // create vlc object and check if vlc is available                             
                    vlcContainer.innerHTML  = getHtmlForMSIE(mrl);
                    if(vlcCheckMsie()){                    
	                    // create vlc control                                                             
						vlcControl.innerHTML = getPlayerControl(); 
						  
	                    // set width and height
	                    var vlcPlayer = document.getElementById("vlc");                                        
	                    vlcPlayer.style.width = STR_SOURCE_WIDTH;
	                    vlcPlayer.style.height = STR_SOURCE_HEIGHT;                    
	                    vlcPlayer.style.border = "solid gray 2px";
	                    var text = ":http-caching=" + HTTP_CACHING;
	                    var options = new Array(text);
	                    vlcPlayer.playlist.clear();
	                    vlcPlayer.playlist.add(mrl, null, options );                      
	                    vlcPlayer.playlist.play();
                   }                                                                                                
                }else if(browser == BROWSER_FIREFOX){ // Firefox
                    if(vlcCheckFirefox()){                  
						// create embedded vlc and control  
						vlcContainer.innerHTML = getHtmlForFirefox(mrl);   
	                    vlcControl.innerHTML = getPlayerControl();                    
	                    
	                    // set width and height
	                    var vlcPlayer = document.getElementById("vlc");                                        
	                    vlcPlayer.style.width = STR_SOURCE_WIDTH;
	                    vlcPlayer.style.height = STR_SOURCE_HEIGHT;
	                    vlcPlayer.style.border = "solid gray 2px";  
	                    var text = ":http-caching=" + HTTP_CACHING;
	                    var options = new Array(text);
	                    vlcPlayer.playlist.clear();
	                    vlcPlayer.playlist.add(mrl, null, options );                      
	                    vlcPlayer.playlist.play();
                     }
                }   
                
                vlcContainer.border = 1;         
            }
            else{
				vlcContainer.innerHTML = "Navegador no soportado.";                  
            }       
                         
        }
                
        //////////////////////////////////////////
        // html for firefox
        function getHtmlForFirefox(mrl){
            var html = "";
            
            html += "<embed type=\"application/x-vlc-plugin\" ";
            html += "name=\"vlc\" id=\"vlc\" style=\"\" version='VideoLAN.VLCPlugin.2'";
            html += "width=\"" + SOURCE_WIDTH + "\" height=\"" + SOURCE_HEIGHT + "\" allowfullscreen= \"yes\" autoplay=\"yes\"";
            html += "loop=\"yes\" controls=\"false\" toolbar=\"false\" />"; 
                                    
            return html;
        }
        
        ////////////////////////////////////
        // html for msie
        function getHtmlForMSIE(mrl){                            
            html = "";
            
            html += "<object ";                                   
            html += "classid='clsid:9BE31822-FDAD-461B-AD51-BE1D1C159921'";                     
            html += "id='vlc' name='vlc' version='VideoLAN.VLCPlugin.2'";       
            html += "style='' ";              
            html += "events='true'> ";            
            html += "<param name='ShowDisplay' value='true' />";
            html += "<param name='AutoLoop' value='true' />";
            html += "<param name='toolbar' value='false' />";
            html += "<param name='AutoPlay' value='true' />";            
            html += "<param name='allowfullscreen' value='true' />";            
            html += "</object>";                                         
            return html;
        }
        
        ////////////////////////////////////
        // html for vlc controller
        function getPlayerControl(){
            var html = "<p align=center> Tamaño imagen video: <select id=\"videoSize\" onchange=\"setVideoSize(this)\">";
            html    += "<option value=\"0.5\">50%</option>";
            html    += "<option value=\"1\" selected>100%</option>";
            html    += "<option value=\"2\">200%</option>";
            html    += "<option value=\"full\">Full screen</option>";
            html    += "</select>";
            html    += "</p>";
            html    += "</p>";
            
            return html;
        }
        
        /////////////////////////////////// 
        // set video size
        function setVideoSize(select){
            var selectedValue = select.options[select.options.selectedIndex].value;                        
                                    
            if (selectedValue == "full"){
				if (document.vlc.video.fullscreen != true)
				document.vlc.video.toggleFullscreen();   // fullscreen
            }else{
				if (document.vlc.video.fullscreen == true)
				document.vlc.video.toggleFullscreen();   // not fullscreen
                 // default resolution multiplicated with zoom factor                
                document.vlc.width   = SOURCE_WIDTH * selectedValue+"px";
                document.vlc.height  = SOURCE_HEIGHT * selectedValue+"px";
				
                // adjust css values
                document.vlc.style.width   = SOURCE_WIDTH * selectedValue+"px";
                document.vlc.style.height  = SOURCE_HEIGHT * selectedValue+"px";
            }                                        
        }                                    
    </script>

</html>
