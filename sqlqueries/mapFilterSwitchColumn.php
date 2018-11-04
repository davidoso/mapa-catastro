<?php
    function switchColumn($column, $value) {
        switch($column) {

            /* VALORES DE COLUMNAS CON NOMBRES FIJOS, COMUNES O CON MISMO NOMBRE EN EL FRONTEND Y LA BD */
            case "(SIN FILTROS)":
                return "1 = 1";
                break;
            case "NOMBRE":
                return "nombre like '%" . $value . "%'";
                break;

            /* VALORES DE CAMPOS DE CATÁLOGOS */
            case "MATERIAL":
                return "id_material = " . getCatalogValue("material", $value);
                break;
            case "CONDICIÓN FÍSICA":
                return "id_cond_fisica = " . getCatalogValue("cond_fisica", $value);
                break;

             /* VALORES DE CAMPOS CON FORMATOS ESPECÍFICOS O NULOS PRESENTADOS CON UN ALIAS  */
            case "FUENTE": // Fuente secundaria de una luminaria
                if ($value === "SIN FUENTE") {
                    return "f_secundaria IS NULL";
                }
                else {
                    return "f_secundaria = '" . $value . "'";
                }
                break;

            /* CUALQUIER OTRA COLUMNA EN ctrl_nombre_columnas */
            default:
                return getNormalColumn($column) . " = '" . $value . "'";
        }
    }

    function getCatalogValue($columnBaseName, $value) {
        try {
            $servername = "localhost";
            $database = "catastro_full";

            // Get UID and PWD from application-specific files
            $uid = "catastro";
            $pwd = "catastro";

            // Create DB connection
            $conn = new PDO("mysql:host=$servername;dbname=$database;charset=UTF8", $uid, $pwd);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $id = "id_" . $columnBaseName;
            $table = "ct_" . $columnBaseName;

            $query =
            "SELECT $id FROM $table WHERE $columnBaseName = :pvalue";

            $stmt = $conn->prepare($query);
            $stmt->bindParam(':pvalue', $value, PDO::PARAM_STR);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Free statement and connection resources
            $stmt = null;
            $conn = null;

            return $row[$id];
        } // try
        catch(PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        } // catch
    }

    function getNormalColumn($column) {
        try {
            $servername = "localhost";
            $database = "catastro_full";

            // Get UID and PWD from application-specific files
            $uid = "catastro";
            $pwd = "catastro";

            // Create DB connection
            $conn = new PDO("mysql:host=$servername;dbname=$database;charset=UTF8", $uid, $pwd);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $query =
            "SELECT columna_bd FROM ctrl_nombre_columnas WHERE columna_frontend = :columna_frontend";

            $stmt = $conn->prepare($query);
            $stmt->bindParam(':columna_frontend', $column, PDO::PARAM_STR);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Free statement and connection resources
            $stmt = null;
            $conn = null;

            return $row['columna_bd'];
        } // try
        catch(PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        } // catch
    }

    function pointsArrayToString($pointsArray) {
        $polygonCoordinates = "";
        for ($i = 0; $i < count($pointsArray); $i++) {
            if (($i + 1) % 2 == 0) {
                $polygonCoordinates = $polygonCoordinates . $pointsArray[$i] . ', ';
            }
            else {
                $polygonCoordinates = $polygonCoordinates . $pointsArray[$i] . ' ';
            }
        }
        return substr($polygonCoordinates, 0, -2); // Drop last comma and whitespace
    }

    // Unescape the string values in the JSON arrays
    $tableData = stripcslashes($_POST['pTableData']);           // 3-column table filters (layer, field and value)
    $booleanOp = stripcslashes($_POST['pBooleanOp']);           // OR or AND operator to join WHERE clauses
    $pointsArray = stripcslashes($_POST['pPointsArray']);       // Polygon coordinates in the map

    // Decode the JSON arrays
    $tableData = json_decode($tableData, TRUE);
    $booleanOp = json_decode($booleanOp, TRUE);
    $pointsArray = json_decode($pointsArray, TRUE);

?>