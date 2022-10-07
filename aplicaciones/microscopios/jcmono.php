<?php 

//$url = 'http://192.168.10.32:554/stream';
//$url = 'http://192.168.10.32';

//$fp = fopen($url, 'r');

//$meta_data = stream_get_meta_data($fp);

?>
<!DOCTYPE html >
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>NetCam Client - Olympus Soft Imaging Solutions GmbH</title>
<!-- style type="text/css">
/* demo page styles */
body {
	background: #eee;
	margin: 0;
	padding: 0;
}

/* main menu styles */
#nav {
	display: block;
	width: 1024px;
	margin: 0px auto;
	padding: 0;
	background: #335599 url(../images/bg.png) repeat-x 0 -210px;
	font-weight: bold;
	color: #e7e5e5;
	text-decoration: none;
	padding: 8px 20px;
	border-radius: 10px; /*some css3*/
	-moz-border-radius: 10px;
	-webkit-border-radius: 10px;
	box-shadow: 0 2px 2px rgba(0, 0, 0, .5);
	-moz-box-shadow: 0 2px 2px rgba(0, 0, 0, .5);
	-webkit-box-shadow: 0 2px 2px rgba(0, 0, 0, .5);
}

#nav li {
	margin: 10px;
	float: center;
	position: relative;
	list-style: none;
}

#nav a {
	font-weight: bold;
	color: #e7e5e5;
	text-decoration: none;
	display: block;
	padding: 8px 20px;
	border-radius: 10px; /*some css3*/
	-moz-border-radius: 10px;
	-webkit-border-radius: 10px;
	text-shadow: 0 2px 2px rgba(0, 0, 0, .7);
}

/* selected menu element */
#nav .current a,#nav li:hover>a {
	background: #7788aa url(../images/bg.png) repeat-x 0 -20px;
	color: #000;
	border-top: 1px solid #f8f8f8;
	box-shadow: 0 2px 2px rgba(0, 0, 0, .7); /*some css3*/
	-moz-box-shadow: 0 2px 2px rgba(0, 0, 0, .7);
	-webkit-box-shadow: 0 2px 2px rgba(0, 0, 0, .7);
	text-shadow: 0 2px 2px rgba(255, 255, 255, 0.7);
}

/* sublevels */
#nav ul li:hover a,#nav li:hover li a {
	background: none;
	border: none;
	color: #000;
}

#nav ul li a:hover {
	background: #335599 url(../images/bg.png) repeat-x 0 -100px;
	color: #fff;
	border-radius: 10px; /*some css3*/
	-moz-border-radius: 10px;
	-webkit-border-radius: 10px;
	text-shadow: 0 2px 2px rgba(0, 0, 0, 0.7);
}

#nav ul li:first-child>a {
	-moz-border-radius-topleft: 10px; /*some css3*/
	-moz-border-radius-topright: 10px;
	-webkit-border-top-left-radius: 10px;
	-webkit-border-top-right-radius: 10px;
}

#nav ul li:last-child>a {
	-moz-border-radius-bottomleft: 10px; /*some css3*/
	-moz-border-radius-bottomright: 10px;
	-webkit-border-bottom-left-radius: 10px;
	-webkit-border-bottom-right-radius: 10px;
}

/* drop down */
#nav li:hover>ul {
	opacity: 1;
	visibility: visible;
}

#nav ul {
	opacity: 0;
	visibility: hidden;
	padding: 0;
	width: 175px;
	position: absolute;
	background: #aabbcc url(../images/bg.png) repeat-x 0 0;
	border: 1px solid #7788aa;
	border-radius: 10px; /*some css3*/
	-moz-border-radius: 10px;
	-webkit-border-radius: 10px;
	box-shadow: 0 2px 2px rgba(0, 0, 0, .5);
	-moz-box-shadow: 0 2px 2px rgba(0, 0, 0, .5);
	-webkit-box-shadow: 0 2px 2px rgba(0, 0, 0, .5);
	-moz-transition: opacity .25s linear, visibility .1s linear .1s;
	-webkit-transition: opacity .25s linear, visibility .1s linear .1s;
	-o-transition: opacity .25s linear, visibility .1s linear .1s;
	transition: opacity .25s linear, visibility .1s linear .1s;
}

#nav ul li {
	float: none;
	margin: 0;
}

#nav ul a {
	font-weight: normal;
	text-shadow: 0 2px 2px rgba(255, 255, 255, 0.7);
}

#nav ul ul {
	left: 160px;
	top: 0px;
}
</style-->
<script type="text/javascript">
mustDownload=false;
function getCookie(c_name)
{
var i,x,y,ARRcookies=document.cookie.split(";");
for (i=0;i<ARRcookies.length;i++)
  {
  x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
  y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
  x=x.replace(/^\s+|\s+$/g,"");
  if (x==c_name)
    {
    return unescape(y);
    }
  }
}

function setCookie(c_name,value,exdays)
{
var exdate=new Date();
exdate.setDate(exdate.getDate() + exdays);
var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
var cookie = c_name + "=" + c_value;
document.cookie= cookie;
}

function GetDoNotAskAgain()
{
	var value = getCookie("DoNotAskAgain")
	if (value == null)
		return false;
	return (value==1);
}
function SetDoNotAskAgain()
{
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
  </script>
<script>
function initMessageDialog()
{
	OSISShow('vlcMessage');
}
function closeDialog()
{
	OSISHide('vlcMessage');
}
function Reconnect()
{
  document.vlc.playlist.stop();
  document.vlc.playlist.play();
}
function checkvlcVersion()
{
	// return true is the vlc verdsion is good
	var vlcVersion = document.getElementById("vlc").VersionInfo;
	if (vlcVersion == "2.0.5 Twoflower")
		return true;
	return false;
}
</script>
<script type="text/javascript">

var streamVideo= <?php echo json_encode($fp); ?>;

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
                                            
        function getInternetExplorerVersion()
        // Returns the version of Windows Internet Explorer or a -1
        // (indicating the use of another browser).
        {
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
			vlcContainer.innerHTML = "Download <a href='ftp://ftp.olympus-sis.com/pub/download/viewer/vlc-2.0.5-Netcam.exe'>NetCam Viewer</a>";
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
          
			vlcContainer.innerHTML = "Download <a href='ftp://ftp.olympus-sis.com/pub/download/viewer/vlc-2.0.5-Netcam.exe'>NetCam Viewer</a>";
			mustDownload=true;
			return false;                                                   
        }
        
        /////////////////////////////////////
        // initialisation
        function init(){
			// first close the dialog
			closeDialog();
            var vlcContainer = document.getElementById(playerDst);
            var arrStreams = ['http','http://181.112.155.169:554/stream']; 
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
			if ((mustDownload != true) && (checkvlcVersion() != true) && (GetDoNotAskAgain() != true))
				initMessageDialog();
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
				vlcContainer.innerHTML = "Browser not supported";                  
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
            var html = "<p align=center> <table width=500> <tr><td align=center width=40%>Video Size: <select id=\"videoSize\" onchange=\"setVideoSize(this)\">";
            html    += "<option value=\"0.5\">50%</option>";
            html    += "<option value=\"1\" selected>100%</option>";
            html    += "<option value=\"2\">200%</option>";
            html    += "<option value=\"full\">Full screen</option>";
            html    += "</select></td>&nbsp;";
            html    += "<td align=center width=20%><input type='button' id=\"vlc_connect\" ";
			html	+= "onclick='Reconnect()' value='Connect'></td>"; 
            html    += "<td align=center width=20%><input type='button' id=\"vlc_disconnect\" ";
			html	+= "onclick='document.vlc.playlist.stop()' value='Disconnect'><td>";
			html    += "</tr></table>";
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

<!-- style type="text/css">
body {
	font-family: Verdana, Arial, Helvetica, sans-serif;
}

td {
	vertical-align: middle;
	text-align: center;
}

a:link {
	color: #646464;
	text-decoration: none;
}

a:hover {
	color: #08107B;
	text-decoration: none;
}

a:visited {
	color: #646464;
	text-decoration: none;
}

a:visited:hover {
	color: #08107B;
	text-decoration: none;
}

a:active {
	color: #646464;
	text-decoration: none;
}

div {
	padding: 0px;
	margin: 0px;
	text-align: center;
}

.header {
	font-size: 30px;
}
</style-->

</head>
<body onload="init()">
	<div id="vlcMessage">
		<ul id="nav">
			You are not using the recommended version of vlc(2.0.5).
			<br> do you want to keep this version? (keeping this version could
			lead to display problems)
			<li><a href="javascript:closeDialog()"
				title="You will maybe have some problems to display the images">Yes</a>
			</li>
			<li><a
				href="ftp://ftp.olympus-sis.com/pub/download/viewer/vlc-2.0.5-Netcam.exe"
				title="You will be prompt to download the recommended version of Videolan VLC">No</a>
			</li>
			<li><a href="javascript:SetDoNotAskAgain()"
				title="You will not be asked again">Do not ask again</a></li>
		</ul>
	</div>
	<table border="0" width="95%">
		<tr>
			<td class="header">NetCam Client</td>
		</tr>
		<tr>
			<td>
				<div id="status">&nbsp;</div>
			</td>
		</tr>
		<tr>
			<td>
				<div id="vlcContainerPlayer"></div>
			</td>
		</tr>
		<tr>
			<td>
				<div id="vlcContainerControl"></div>
			</td>
		</tr>
		<tr>
			<td class="footer">&nbsp;</td>
		</tr>

	</table>

	<NOSCRIPT>
		<CENTER>
			<br> <br> <br> <br> <br> <br> <br> <br> Java scripting is required
			for playback of the NetCam video. Please activate Java scripting in
			the security settings of your browser.
		</CENTER>
	</NOSCRIPT>
	<p align="right">
	
	
	<form action="help.html"
		target="NetCam Client - Olympus Soft Imaging Solutions GmbH">
		<input type="submit" value="  Help  ">
	</form>
	</p>
</body>
</html>
