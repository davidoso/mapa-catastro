<?php
    function switchTable($layer) {
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
            "SELECT nombre_tabla FROM ctrl_select_capas WHERE capa = :layer";

            $stmt = $conn->prepare($query);
            $stmt->bindParam(':layer', $layer, PDO::PARAM_STR);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Free statement and connection resources
            $stmt = null;
            $conn = null;

            return $row['nombre_tabla'];
        } // try
        catch(PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        } // catch
    }

?>