<?php
    require_once 'mapSwitchTable.php';
    require_once 'mapFilterSwitchColumn.php';

    // This code must not be edited. All the map filters must be added/changed in mapFilterSwitchColumn.php

    if ($booleanOp[0] === "OR") {
        // $arrQueries initializes the n SQL queries depending on the number of rows in $tableData (division by 3)
        // ? is a flag to add the filtering conditions afterwards
        $arrQueries = array();
        for ($i = 0; $i < count($tableData) / 3; $i++) {
            $query =
            "SELECT count(id) AS totalResult
            FROM layerTable
            WHERE ST_INTERSECTS(ST_GeomFromText('Polygon((polygonCoordinates))'),
            ST_GeomFromText( CONCAT('POINT(', CONVERT(coord_x, CHAR(20)), ' ', CONVERT(coord_y, CHAR(20)), ')') ))
            AND (?";

            $query = str_replace("polygonCoordinates", pointsArrayToString($pointsArray), $query);
            array_push($arrQueries, $query);
        }

        $i = 0;
        foreach (array_chunk($tableData, 3) as $filter) {
            $cond = switchColumn($filter[1], $filter[2]);
            //echo "COND: " . $cond . "\n";
            $arrQueries[$i] = str_replace("layerTable", switchTable($filter[0]), $arrQueries[$i]); // DB table
            $arrQueries[$i] = substr($arrQueries[$i], 0, -1);   // Remove ? symbol for each query
            $arrQueries[$i] = $arrQueries[$i] . $cond . ")";    // Concatenate first (and only) condition
            $i++; // Before the loop ends, the counter i must be equal to the amount of chunks
        }

        try {
            $servername = "localhost";
            $database = "catastro_full";

            // Get UID and PWD from application-specific files
            $uid = "catastro";
            $pwd = "catastro";

            // Create DB connection
            $conn = new PDO("mysql:host=$servername;dbname=$database;charset=UTF8", $uid, $pwd);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $jsonString = '[';
            foreach ($arrQueries as $query) {
                //echo "FOREACH QUERY: " . $query . "\n";
                $stmt = $conn->prepare($query);
                $stmt->execute();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $jsonString = $jsonString . $row['totalResult'] . ", ";
                }
            }
            unset($query); // Break the reference with the last element
        } // try
        catch(PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        } // catch
        // Free statement and connection resources
        $stmt = null;
        $conn = null;
    } // if ($booleanOp[0] === "OR")

    else { // ($booleanOp[0] === "AND")
        /* Since filters are not sorted by layer, compare the layer name every 3 positions (layer, column, value)
        $arrLayers saves the layer short names (that will be converted to database table names later) */
        $arrLayers = array();
        for ($i = 0; $i < count($tableData); $i+=3) {
            if (!in_array($tableData[$i], $arrLayers))
                array_push($arrLayers, $tableData[$i]);
        }
        //echo "COUNT: " . count($arrLayers) . "\n";

        // $arrQueries initializes the n SQL queries depending on the number of layers in $arrLayers
        // ? is a flag to add the filtering conditions afterwards
        $arrQueries = array();
        for ($i = 0; $i < count($arrLayers); $i++) {
            $query =
            "SELECT count(id) AS totalResult
            FROM layerTable
            WHERE ST_INTERSECTS(ST_GeomFromText('Polygon((polygonCoordinates))'),
            ST_GeomFromText( CONCAT('POINT(', CONVERT(coord_x, CHAR(20)), ' ', CONVERT(coord_y, CHAR(20)), ')') ))
            AND (?";

            $query = str_replace("layerTable", switchTable($arrLayers[$i]), $query); // DB table
            $query = str_replace("polygonCoordinates", pointsArrayToString($pointsArray), $query);
            array_push($arrQueries, $query);
        }

        foreach (array_chunk($tableData, 3) as $filter) {
            $i = array_search($filter[0], $arrLayers);
            $cond = switchColumn($filter[1], $filter[2]);
            //echo "COND: " . $cond . "\n";
            if ($arrQueries[$i][-1] === "?") { // ? symbol is a flag that tells whether a condition has been added
                $arrQueries[$i] = substr($arrQueries[$i], 0, -1);   // Remove ? symbol
                $arrQueries[$i] = $arrQueries[$i] . $cond;          // Concatenate first condition
            }
            else {
                // Concatenate remaining conditions with AND
                $arrQueries[$i] = $arrQueries[$i] . " AND " . $cond;
            }
        }

        for ($i = 0; $i < count($arrQueries); $i++) {
            $arrQueries[$i] = $arrQueries[$i] . ")";
            //echo "QUERY ARRAY IN FOR LOOP: " . $arrQueries[$i] . "\n";
        }

        try {
            $servername = "localhost";
            $database = "catastro_full";

            // Get UID and PWD from application-specific files
            $uid = "catastro";
            $pwd = "catastro";

            // Create DB connection
            $conn = new PDO("mysql:host=$servername;dbname=$database;charset=UTF8", $uid, $pwd);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $jsonString = '[';
            $arrTotals = array();
            foreach ($arrQueries as $query) {
                //echo "FOREACH QUERY: " . $query . "\n";
                $stmt = $conn->prepare($query);
                $stmt->execute();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    array_push($arrTotals, $row['totalResult']);
                }
            }
            unset($query); // Break the reference with the last element

            for ($i = 0; $i < count($tableData); $i+=3) {
                $j = array_search($tableData[$i], $arrLayers);
                $jsonString = $jsonString . $arrTotals[$j] . ", ";
            }
        } // try
        catch(PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        } // catch
        // Free statement and connection resources
        $stmt = null;
        $conn = null;
    } // else ($booleanOp[0] === "OR"), i.e. AND

    $jsonString = substr($jsonString, 0, -2);   // Drop last comma and whitespace
    $jsonString = $jsonString . ']';            // Close JSON string with ']'

    // JSON string basic example. This is how it will look like
    // $jsonString = "[11, 234, 5566, 7890, 0, 0]";

    // Return JSON string through jQuery AJAX call on template_map.php
    echo $jsonString;

?>