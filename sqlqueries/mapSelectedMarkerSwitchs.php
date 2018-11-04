<?php
    function switchTableMarker($marcador) {
        /* CATÁLOGOS A UNIR CON LA TABLA DE LA CAPA DADO QUE ÉSTA TRAE LA LLAVE FORÁNEA PERO NO EL NOMBRE */
        $cond_fisica =
            "JOIN ct_cond_fisica CF on T.id_cond_fisica = CF.id_cond_fisica";
        $cond_fisica_and_material =
            "JOIN ct_cond_fisica CF on T.id_cond_fisica = CF.id_cond_fisica
            JOIN ct_material CM on T.id_material = CM.id_material";

        switch($marcador) {

            /* CAPAS QUE TIENEN LLAVES FORÁNEAS A CATÁLOGOS */
            case "postes":
            case "luminarias":
            case "panteon_municipal":
                return switchTable("marcador", $marcador) . " T " . $cond_fisica_and_material;
                break;
            case "telefonos_publicos":
                return switchTable("marcador", $marcador) . " T " . $cond_fisica;
                break;

            /* CUALQUIER OTRA CAPA SIN CATÁLOGOS */
            default:
                return switchTable("marcador", $marcador);
        }
    }

    function switchColumnMarker($marcador) {
        /* LAS COORDENADAS APARECEN EN CUALQUIER CAPA */
        $coordinates =
            "coord_y AS 'LATITUD', coord_x AS 'LONGITUD', "; // Latitude appears first on GPS coordinates

        /* ALIAS DE VALORES DE CATÁLOGOS */
        $cond_fisica =
            "CF.cond_fisica AS 'CONDICIÓN FÍSICA'";
        $cond_fisica_and_material =
            "CM.material AS 'MATERIAL', CF.cond_fisica AS 'CONDICIÓN FÍSICA'";

        switch($marcador) {

            /* ALIAS DE VALORES PARA CAPAS DE LA CARPETA: GENERALES */
            case "bancos":
                return $coordinates . "nombre AS 'BANCO', tipo AS 'SERVICIO'";
                break;
            case "hoteles":
                return $coordinates . "nombre AS 'HOTEL'";
                break;
            case "telefonos_publicos":
                return $coordinates . "empresa_responsable AS 'EMPRESA', tipo AS 'TIPO', funciona AS 'FUNCIONA (SI/NO)', modalidad AS 'MODALIDAD', " . $cond_fisica;
                break;
            case "postes":
                return $coordinates . "empresa_responsable AS 'EMPRESA', f_primaria AS 'FUENTE PRIMARIA', f_secundaria AS 'FUENTE SECUNDARIA', " . $cond_fisica_and_material;
                break;
            case "luminarias":
                return $coordinates . "f_primaria AS 'FUENTE PRIMARIA', f_secundaria AS 'FUENTE SECUNDARIA', tipo AS 'TIPO DE POSTE', " . $cond_fisica_and_material . ", calle AS 'CALLE', colonia AS 'COLONIA'";
                break;

            /* ALIAS DE VALORES PARA CAPAS DE LA CARPETA: INAH */
            case "monumentos_historicos":
                return $coordinates . "clave_catastral AS 'CLAVE CATASTRAL', ficha AS 'FICHA', epoca AS 'ÉPOCA', genero_arquitectonico AS 'GÉNERO ARQUITECTÓNICO', ubicacion AS 'UBICACIÓN'";
                break;

            /* ALIAS DE VALORES PARA CAPAS DE LA CARPETA: REGISTRO CIVIL */
            case "panteon_municipal":
                return $coordinates . "clave_catastral AS 'CLAVE CATASTRAL', num_manzana AS 'NO. DE MANZANA', num_lote AS 'NO. DE LOTE', seccion AS 'NO. DE SECCIÓN', seccion_calle AS 'NO. DE SECCIÓN EN CALLE', calle AS 'NO. DE CALLE', numero AS 'NO.', capacidad AS 'CAPACIDAD', " . $cond_fisica_and_material . ", observaciones AS 'OBSERVACIONES'";
                break;
        }
    }

?>