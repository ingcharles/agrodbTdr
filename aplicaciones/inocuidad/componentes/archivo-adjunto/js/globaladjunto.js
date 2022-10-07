$(document).ready(function() {
    $( "#file_dialog" ).dialog({
        autoOpen: false,
        height: 550,
        width: 650,
        modal: true,
        title: "Gestor de Archivos",
        show: {
            effect: "blind",
            duration: 500
        },
        open: function( event, ui ) {
            setTimeout(function(){ distribuirLineas(); }, 200);
        },
        close: function(event, ui)
        {
            $(this).dialog('destroy').remove();
        }
    });
});