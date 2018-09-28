<?php
    // Unescape the string values in the JSON array
    $pointsArray = stripcslashes($_POST['pPointsArray']); // Polygon coordinates in the map

    // Decode the JSON array
    $pointsArray = json_decode($pointsArray, TRUE);

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

    try {
        $serverName = "CHELSEA\SQLSERVER2016";
        $database = "CATASTRO_DW";

        // Get UID and PWD from application-specific files
        $uid = "catastro";
        $pwd = "catastro";

        // Create DB connection
        $conn = new PDO("sqlsrv:server=$serverName; Database=$database", $uid, $pwd);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        /*
         * References:
         * https://docs.microsoft.com/en-us/sql/relational-databases/spatial/polygon?view=sql-server-2017
         * https://docs.microsoft.com/en-us/sql/t-sql/spatial-geometry/stgeomfromtext-geometry-data-type?
         * view=sql-server-2017
         * https://docs.microsoft.com/en-us/sql/t-sql/spatial-geometry/stisvalid-geometry-data-type?
         * view=sql-server-2017
        */
        $query = "SELECT geometry::STGeomFromText('POLYGON((polygonCoordinates))', 0).STIsValid() AS valid";
        $query = str_replace("polygonCoordinates", pointsArrayToString($pointsArray), $query);

        $count = $conn->query($query)->fetchColumn();

        if ($count == 0) {
            echo "0";   // Not valid
        }
        else {
            echo "1";   // Valid
        }
    } // try
    catch(PDOException $e) {
        echo 'ERROR: ' . $e->getMessage();
    } // catch
    // Free statement and connection resources
    $stmt = null;
    $conn = null;

?>