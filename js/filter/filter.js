// Fill 2nd combobox (search columns) depending on the layer selected on the 1st combobox
$("#cbCapas").on("change", function () {
    document.getElementById("divValores").innerHTML = null;
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
    $('body').css('cursor', 'wait');

    $.ajax({
        type: "get",
        url: "index.php/App_c/getCampos",
        data: { capa:capa },
        dataType: "json",
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
            $('#cbCampos').focus();
            $('#cbCampos').selectpicker('toggle');
            $('body').css('cursor', 'auto');
        },
        error: function() {
            console.log("Error! switchSelectCapa() failed. Search columns could not be retrieved");
            $('body').css('cursor', 'auto');
        }
    }); // AJAX
}

function switchSelectCampo() {
    var cbCapas = document.getElementById("cbCapas");
    var capa = cbCapas.options[cbCapas.selectedIndex].value;
    var cbCampos = document.getElementById("cbCampos");
    var campo = cbCampos.options[cbCampos.selectedIndex].value;
    var divValores = document.getElementById("divValores");
    $('body').css('cursor', 'wait');

    if(campo == "NOMBRE") {
        var my_class, my_placeholder, my_maxlength;
        switch(capa) {
        case "BANCOS":
            my_class = "vAlfanumerico";
            my_placeholder = "Ingrese el banco a buscar..";
            my_maxlength = 30;
            break;
        case "HOTELES":
            my_class = "vAlfanumerico";
            my_placeholder = "Ingrese el hotel a buscar..";
            my_maxlength = 30;
            break;
        }
        divValores.innerHTML = null;
        divValores.innerHTML = '<input type="text" style="width: 90%;" class="form-control ' + my_class + '" id="inputValor" name="inputValor" placeholder="' + my_placeholder + '" maxlength="' + my_maxlength + '">';
        $('#inputValor').focus();
        $('body').css('cursor', 'auto');
    } // if (campo == "NOMBRE")
    else {
        $.ajax({
            type: "get",
            url: "index.php/App_c/getValores",
            data: { capa:capa, campo:campo },
            dataType: "json",
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
                $('#cbValores').focus();
                $('#cbValores').selectpicker('toggle');
                $('body').css('cursor', 'auto');
            },
            error: function() {
                console.log("Error! switchSelectCampo() failed. Values could not be retrieved");
                $('body').css('cursor', 'auto');
            }
        }); // AJAX
    } // else (campo == "NOMBRE")
}

// Convert to uppercase, remove multiple whitespaces and trim inputs on focus out
$(document).on("focusout", "input[type=text]", function() {
    this.value = this.value.toUpperCase().replace(/\s{2,}/g, " ").trim();
});
$(document).on("keypress", ".vLetras", function(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    return validarLetras(charCode);
});
$(document).on("keypress", ".vNumeros", function(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    return validarNumeros(charCode);
});
$(document).on("keypress", ".vAlfanumerico", function(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    return validarAlfanumerico(charCode);
});

// Allow/block keys depending on the input class
function validarLetras(charCode) {
    return !(charCode > 31 && (charCode < 65 || charCode > 90) && (charCode < 97 || charCode > 122)
        && charCode != 32 && (charCode <= 192 || charCode >= 255));
}

function validarNumeros(charCode) {
    return !(charCode > 31 && (charCode < 48 || charCode > 57) && charCode != 32
        && (charCode <= 192 || charCode >= 255));
}

function validarAlfanumerico(charCode) {
    return (validarLetras(charCode) || validarNumeros(charCode));
}