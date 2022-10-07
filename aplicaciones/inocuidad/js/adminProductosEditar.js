$.getScript("aplicaciones/inocuidad/js/globals.js",function(){console.log("globals loaded");});

$(document).ready(function() {
    $("#area_id").on("change",function () {
        var selectData = $(this).val();
        if(selectData && selectData.length>0){
            $("#id_tipo_producto").val("");
            $("#id_tipo_producto").prop('disabled', false);
            $("#id_subtipo_producto").val("");
            $("#id_subtipo_producto").prop('disabled', 'disabled');
            $("#producto_id").val("");
            $("#producto_id").prop('disabled', 'disabled');
            //Traemos los tipos de productos del área seleccionada
            refreshOpciones(selectData,$('#id_tipo_producto'),'TIPO_PRODUCTO');
        }
    });
    $("#id_tipo_producto").on("change",function () {
        var selectData = $(this).val();
        if(selectData && selectData.length>0){
            $("#id_subtipo_producto").val("");
            $("#id_subtipo_producto").prop('disabled', false);
            $("#producto_id").val("");
            $("#producto_id").prop('disabled', 'disabled');
            //Traemos los sub-tipos de productos del tipo seleccionado
            refreshOpciones(selectData,$('#id_subtipo_producto'),'SUBTIPO_PRODUCTO');
        }
    });
    $("#id_subtipo_producto").on("change",function () {
        var selectData = $(this).val();
        if(selectData && selectData.length>0){
            $("#producto_id").val("");
            $("#producto_id").prop('disabled', false);
            //Traemos los productos del subtipo seleccionado
            refreshOpciones(selectData,$('#producto_id'),'PRODUCTO_BASE');
        }
    });
    $("#producto_id").on("change",function () {
        var selectData = $(this);
        if(selectData.val() && selectData.val().length>0){
            if($("#nombre_producto").val()!=selectData.find("option:selected").text())
                cuentaProductosInsumos(selectData.val());
            $("#nombre_producto").val(selectData.find("option:selected").text());
        }
    });

    generarInsumos = function (ic_producto_id,producto_id) {
        if(ic_producto_id && Number(ic_producto_id)>0 && producto_id && Number(producto_id)>0){
            var objInsumos = {};
            objInsumos.ic_producto_id = ic_producto_id;
            objInsumos.producto_id = producto_id;
            $.ajax({
                type: "POST",
                url: "./aplicaciones/inocuidad/servicios/ServiceCatalogos.php",
                data: {'catalogo': 'CREAR_PRODUCTO_INSUMO', 'selectData': JSON.stringify(objInsumos)},
                success: function (json) {
                    if(json){
                        var objMensaje = JSON.parse(JSON.parse(json));
                        if(objMensaje.estado=="EXITO")
                            listarProductosInsumos(ic_producto_id);
                        mostrarMensaje(objMensaje.mensaje,objMensaje.estado);
                    }else{
                        mostrarMensaje("Error: Existen problemas al consultar la información","FALLO");
                    }
                }
            });
        }
    };

    listarProductosInsumos = function(ic_producto_id){
        if(ic_producto_id && Number(ic_producto_id)>0){
            $.ajax({
                type: "POST",
                url: "./aplicaciones/inocuidad/servicios/ServiceCatalogos.php",
                data: {'catalogo': 'LISTAR_PRODUCTO_INSUMO_JSON', 'selectData': ic_producto_id},
                success: function (json) {
                    if(json) {
                        cargarValoresTabla("registroValores",JSON.parse(json));
                    }
                }
            });
        }
    };

    cuentaProductosInsumos = function(producto_id){
        console.log("Cuenta Productos: " +producto_id);
        if(producto_id && Number(producto_id)>0){
            $.ajax({
                type: "POST",
                url: "./aplicaciones/inocuidad/servicios/ServiceCatalogos.php",
                data: {'catalogo': 'CUENTA_PRODUCTO_INSUMO', 'selectData': producto_id},
                success: function (json) {
                    if(json) {
                        json = "El producto cuenta con "+json+" insumos";
                        $("#cuenta_producto_insumos").val(json);
                    }
                }
            });
        }
    };

    cargarValoresTabla = function (tabla, strArr) {
        //strArr = strArr.replace(/'/g,"\"");
        var array = strArr;//JSON.parse(strArr);
        for(var i=0;i<array.length;i++){
            console.log(array[i]);
            if(tabla == "muestraRapida")
                buildRowMuestraRapida(array[i]);
            else if(tabla == "registroValores")
                buildRowRegistroValor(array[i]);
        }
    };
    refreshOpciones = function(selectData,element,service,subtable,callback){
        element.find('option').remove();
        if(subtable) {
            //borramos los datos de la tabla, porque no puede tener de programas distintos
            $("#muestraRapida tbody").find('tr').each(function () {
                $(this).remove();
            });
        }
        $.ajax({
            type: "POST",
            url: "./aplicaciones/inocuidad/servicios/ServiceCatalogos.php",
            data: { 'catalogo':service,'selectData':selectData },
            success: function (json) {
                json = JSON.parse(json);
                element.append("<option value=\"\">Seleccione ....</option>");
                for(var i=0; i<json.length; i++){
                    element.append(json[i]);
                }
                if(callback)
                    callback();
            }
        });
    };
    var err_message = "";
    var arrRowObj = [];
    var arrRowObjMuestra = [];
    $("#agregarMuestra").click(function () {
        if(rowIsValid()){
            var objRow={};
            objRow.ic_producto_muestra_rapida_id="";
            objRow.stamped=new Date().getTime();
            objRow.ic_insumo_id=$("#ic_insumo_id").val();
            objRow.insumo = $("#ic_insumo_id option:selected").text();
            objRow.um=$("#um").val();
            objRow.um_name = $("#um option:selected").text();
            objRow.limite_minimo=$("#limite_minimo").val();
            objRow.limite_maximo=$("#limite_maximo").val();
            buildRowMuestraRapida(objRow,true);
        }else{
            alert(err_message);
        }
    });

    rowIsValid = function () {
      var valid = true;
        err_message = "";
        if($("#ic_insumo_id").val().length<=0)
            valid=false;
        if($("#um").val().length<=0)
            valid=false;
        if($("#limite_minimo").val().length<=0)
            valid=false;
        if($("#limite_maximo").val().length<=0)
            valid=false;
        if(!valid)
            err_message = "Todos los datos son obligatorios";
        else{
            if(Number($("#limite_maximo").val())<Number($("#limite_minimo").val())){
                valid=false;
                err_message = "El valor de límite mínimo es mayor al limite máximo";
            }
        }
      return valid;
    };

    buildRowMuestraRapida = function (objRow, inserting) {
        console.log(objRow.ic_producto_muestra_rapida_id);
        var htmlRow="<tr stamped='"+objRow.stamped+"'>" +
            "<td id='"+objRow.ic_insumo_id+"'>"+objRow.insumo+"</td>\n" +
            "<td id='"+objRow.um+"'>"+objRow.um_name+"</td>\n" +
            "<td class='decimal'>"+objRow.limite_minimo+"</td>\n" +
            "<td class='decimal'>"+objRow.limite_maximo+"</td>\n" +
            "<td style='text-align: center;'>" +
            (objRow.ic_producto_muestra_rapida_id? "":"<input class='delete-row' id='delete_record' name='delete_record' type='button' onclick='triggerDeleteRecord(this)' />")+
            "</td>";
            "</tr>";
        if(inserting){
            if($("#ic_insumo_id").val()==""){
                $("#errDialogMsg").html("Los datos son obligatorios");
                $( "#dialog" ).dialog({
                    resizable: false,
                    modal: true,
                    buttons: {
                        Ok: function() {
                            $( this ).dialog( "close" );
                        }}});
                return;
            }else{
                $("#muestraRapida tbody").append(htmlRow);
                arrRowObjMuestra.push(objRow);
                $("#arr_muestra").val(JSON.stringify(arrRowObjMuestra));
                $("#ic_insumo_id").val("");
                $("#um").val("");
                $("#limite_minimo").val("");
                $("#limite_maximo").val("");
            }
        }else{
            $("#muestraRapida tbody").append(htmlRow);
            arrRowObjMuestra.push(objRow);
            $("#arr_muestra").val(JSON.stringify(arrRowObjMuestra));
        }
    };


    buildRowRegistroValor = function (objRow) {
        console.log(objRow.ic_producto_insumo_id);

        var htmlRow="<tr stamped='"+objRow.stamped+"'>" +
            "<td id='"+objRow.ic_insumo_id+"'>"+objRow.insumo+"</td>\n" +
            "<td id='"+objRow.ic_lmr_id+"'>"+objRow.lmr+"</td>\n" +
            "<td>"+objRow.um+"</td>\n" +
            "<td><input type='text' class='decimal' record_id="+objRow.ic_producto_insumo_id+" id='lim_min_"+objRow.ic_producto_insumo_id+"' value='"+objRow.limite_minimo+"' onchange='changeLimite(this,\"limite_minimo\")'/></td>\n" +
            "<td><input type='text' class='decimal' record_id="+objRow.ic_producto_insumo_id+" id='lim_max_"+objRow.ic_producto_insumo_id+"' value='"+objRow.limite_maximo+"' onchange='changeLimite(this,\"limite_maximo\")'/></td>\n" +
            "</tr>";

        $("#registroValores tbody").append(htmlRow);
        arrRowObj.push(objRow);
        $("#arr_insumos").val(JSON.stringify(arrRowObj));
    };

    validarInsumos = function(){
        if($("#muestra_rapida").prop('checked'))
            return arrRowObjMuestra && arrRowObjMuestra.length>0;
        else
            return true;
    };

    cleanRows = function () {
        $("#muestraRapida tbody").find('input[name="delete_record"]').each(function(){
            var rows = $(this).parents("tr");
            for(var i=0;i<rows.length;i++){
                row = rows[i];
                rowid=row.getAttribute("stamped");
                deleteObject(rowid);
            }
            $("#arr_muestra").val(JSON.stringify(arrRowObjMuestra));
            rows.remove();
        });
    };

    changeLimite = function (el,type) {
        var element = $(el);
        var id = element.context.getAttribute("record_id");
        var value = element.val();
        for(var i=0;i<arrRowObj.length;i++){
            if(arrRowObj[i].ic_producto_insumo_id==id){
                if(type=="limite_maximo")
                    arrRowObj[i].limite_maximo=value;
                else
                    arrRowObj[i].limite_minimo=value;
                break;
            }
        }
        $("#arr_insumos").val(JSON.stringify(arrRowObj));
    };

    triggerDeleteRecord = function(el) {
        var rows = $(el).parents("tr");
        for(var i=0;i<rows.length;i++){
            row = rows[i];
            rowid=row.getAttribute("stamped");
            deleteObject(rowid);
        }
        $("#arr_muestra").val(JSON.stringify(arrRowObjMuestra));
        rows.remove();
        console.log(JSON.stringify(arrRowObjMuestra));
    };

    deleteObject = function(rowId){
        for(var i=0;i<arrRowObjMuestra.length;i++){
            if(arrRowObjMuestra[i].stamped == rowId){
                arrRowObjMuestra.splice(i,1);
                break;
            }
        }
    }

});