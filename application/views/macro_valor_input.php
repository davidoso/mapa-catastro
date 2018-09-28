<?php
    $input =
        '\'<input type="text" class="form-control rclass" id="inputFiltro" name="inputFiltro" placeholder="rplaceholder" maxlength="rmaxlength" required>\';';
    $input = str_replace("rclass", $rclass, $input);
    $input = str_replace("rplaceholder", $rplaceholder, $input);
    $input = str_replace("rmaxlength", $rmaxlength, $input);

    echo $input;
?>