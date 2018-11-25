// Fill 2nd combobox (search columns) depending on the layer selected on the 1st combobox
$("#cbCapas").on("change", function () {
    document.getElementById("divValores").innerHTML = '<label class="nav-link"><span class="menu-title">Seleccione un campo..</span><i class="fas fa-fw fa-filter"></i></label>';
    switchSelectCapa();
});

// Fill 3rd combobox (values) depending on the column-to-search selected on the 2nd combobox
$(document).on("change", "#cbCampos", function() {
    switchSelectCampo();
});

function switchSelectCapa() {
    var cbCapas = document.getElementById("cbCapas");
    var capa = cbCapas.options[cbCapas.selectedIndex].value;
    var divCampos = document.getElementById("divCampos");
    $("body").css('cursor', 'wait');

    $.ajax({
        type: "get",
        url: "index.php/Map_c/getCampos",
        data: { capa:capa },
        dataType: 'json',
        success: function(data) {
            // e.g. 'BANCOS' turns to 'Capa: Bancos' in the dropdown-header
            var header = "Capa: " + capa.charAt(0) + capa.substring(1).toLowerCase();
            var campos = "";
            $(data).each(function(k, v) {
                campos += '<option value="' + v.campo + '">' + v.campo + '</option>';
            });
            divCampos.innerHTML = null;
            divCampos.innerHTML = '<select class="selectpicker show-tick" id="cbCampos" name="cbCampos" title="Campo a filtrar.."><optgroup label="' + header + '">'+ campos + '</select>';
            $('#cbCampos').selectpicker({
                style: 'btn-info',
                size: 10
            });
            $('#cbCapas').trigger('mouseleave');
            $('#cbCampos').focus();
            $('#cbCampos').selectpicker('toggle');
            /*$('#cbCampos').parent().addClass('open'); // This way the searchbox isn't focused
            $('#cbCampos').selectpicker('refresh');*/
            $("body").css('cursor', 'auto');
        },
        error: function() {
            console.log("Error! switchSelectCapa() failed. Search columns could not be retrieved");
            $("body").css('cursor', 'auto');
        }
    }); // AJAX
}

function switchSelectCampo() {
    var cbCapas = document.getElementById("cbCapas");
    var capa = cbCapas.options[cbCapas.selectedIndex].value;
    var cbCampos = document.getElementById("cbCampos");
    var campo = cbCampos.options[cbCampos.selectedIndex].value;
    var divValores = document.getElementById("divValores");
    $("body").css('cursor', 'wait');

    if(campo == "NOMBRE") {
        var my_class, my_placeholder, my_maxlength;
        switch(capa) {
        case "BANCOS":
            my_class = "vAlfanumerico";
            my_placeholder = "Ingrese el banco a buscar";
            my_maxlength = 30;
            break;
        case "HOTELES":
            my_class = "vAlfanumerico";
            my_placeholder = "Ingrese el hotel a buscar";
            my_maxlength = 30;
            break;
        }
        divValores.innerHTML = null;
        divValores.innerHTML = '<input type="text" class="form-control ' + my_class + '" id="inputValor" name="inputValor" placeholder="' + my_placeholder + '" maxlength="' + my_maxlength + '">';
        $('#cbCampos').trigger('mouseleave');
        $('#inputValor').focus();
        $("body").css('cursor', 'auto');
    } // if (campo == "NOMBRE")
    else {
        $.ajax({
            type: "get",
            url: "index.php/Map_c/getValores",
            data: { capa:capa, campo:campo },
            dataType: 'json',
            success: function(data) {
                // e.g. 'CONDICIÓN FÍSICA' turns to 'Campo: Condición física' in the dropdown-header
                var header = "Campo: " + campo.charAt(0) + campo.substring(1).toLowerCase();
                var valores = "";
                $(data).each(function(k, v) {
                    valores += '<option value="' + v.valor + '">' + v.valor + '</option>';
                });
                divValores.innerHTML = null;
                divValores.innerHTML = '<select class="selectpicker show-tick" id="cbValores" name="cbValores" title="Valor a filtrar.." data-dropup-auto="false" data-live-search="true" data-live-search-placeholder="Buscar valor.." data-live-search-style="contains"><optgroup label="' + header + '">'+ valores + '</select>';
                $('#cbValores').selectpicker({
                    style: 'btn-info',
                    size: 6
                });
                $('#cbCampos').trigger('mouseleave');
                $('#cbValores').focus();
                $('#cbValores').selectpicker('toggle');
                $("body").css('cursor', 'auto');
            },
            error: function() {
                console.log("Error! switchSelectCampo() failed. Values could not be retrieved");
                $("body").css('cursor', 'auto');
            }
        }); // AJAX
    } // else (campo == "NOMBRE")
}