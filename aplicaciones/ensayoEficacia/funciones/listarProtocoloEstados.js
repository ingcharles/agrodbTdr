$(document).ready(function () {
    $("#listadoItems").addClass("comunes");
    
    $("#solicitud div> article").length == 0 ? $("#solicitud").remove() : "";
    $("#pago div> article").length == 0 ? $("#pago").remove() : "";

    $("#verificacion div> article").length == 0 ? $("#verificacion").remove() : "";
    
    $("#verificacionProtocolo div> article").length == 0 ? $("#verificacionProtocolo").remove() : "";
    $("#subsanarProtocolo div> article").length == 0 ? $("#subsanarProtocolo").remove() : "";
    $("#aprobarProtocoloDir div> article").length == 0 ? $("#aprobarProtocoloDir").remove() : "";
    $("#aprobarProtocoloCor div> article").length == 0 ? $("#aprobarProtocoloCor").remove() : "";
    
    $("#elegirOrganismo div> article").length == 0 ? $("#elegirOrganismo").remove() : "";
    $("#inspeccion div> article").length == 0 ? $("#inspeccion").remove() : "";
   
   $("#aprobado div> article").length == 0 ? $("#aprobado").remove() : "";

   $("#modificacion div> article").length == 0 ? $("#modificacion").remove() : "";
   

});