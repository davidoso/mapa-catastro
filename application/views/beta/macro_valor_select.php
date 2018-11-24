<?php
    $select =
        '\'<select class="form-control" id="cbFiltros" name="cbFiltros" required>' .
        '<option value="">< Seleccionar ></option>';

    foreach($dbarray as $field)
        foreach($field as $option) {
            $select = $select . '<option value="' . mb_strtoupper($option) . '">' . mb_strtoupper($option) . '</option>';
        }

    $select = $select . '</select>\';';
    echo $select;
    echo 'document.getElementById("cbFiltros").focus();' // Poner foco en 3er combobox para ingresar valor
?>