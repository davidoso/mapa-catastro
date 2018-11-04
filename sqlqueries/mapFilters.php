<?php
    require_once 'mapSwitchTable.php';
    require_once 'mapFilterSwitchColumn.php';

    // This code must not be edited. All the map filters must be added/changed in mapFilterSwitchColumn.php

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
        "SELECT coord_x, coord_y, lower(tr('layerColumn', 'ÁÉÍÓÚ ', 'AEIOU_')) AS layer
        FROM layerTable
        WHERE ST_INTERSECTS(ST_GeomFromText('Polygon((polygonCoordinates))'),
        ST_GeomFromText( CONCAT('POINT(', CONVERT(coord_x, CHAR(20)), ' ', CONVERT(coord_y, CHAR(20)), ')') ))
        AND (?";

        $query = str_replace("layerColumn", $arrLayers[$i], $query);                // Map marker id
        $query = str_replace("layerTable", switchTable("capa", $arrLayers[$i]), $query);    // DB table name
        $query = str_replace("polygonCoordinates", pointsArrayToString($pointsArray), $query);
        array_push($arrQueries, $query);
    }

    foreach (array_chunk($tableData, 3) as $filter) {
        $i = array_search($filter[0], $arrLayers);
        $cond = switchColumn($filter[1], $filter[2]);
        //echo "COND: " . $cond . "\n";
        if ($arrQueries[$i][-1] === "?") { // ? symbol is a flag that tells whether a condition has been added
            $arrQueries[$i] = substr($arrQueries[$i], 0, -1); //Remove ? symbol
            $arrQueries[$i] = $arrQueries[$i] . $cond; // Concatenate first condition
        }
        else {
            // Concatenate remaining conditions with OR (basic example)
            // $arrQueries[$i] = $arrQueries[$i] . ' OR ' . $cond;
            // Concatenate remaining conditions with either OR or AND depending on the selected radio button
            $arrQueries[$i] = $arrQueries[$i] . ' ' . $booleanOp[0] . ' ' . $cond;
        }
    }

    for ($i = 0; $i < count($arrQueries); $i++) {
        $arrQueries[$i] = $arrQueries[$i] . ")";
        //echo "QUERY ARRAY IN FOR: " . $arrQueries[$i] . "\n";
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

        $jsonString = '{ "items": [';
        foreach ($arrQueries as $query) {
            //echo "FOREACH QUERY: " . $query . "\n";
            $stmt = $conn->prepare($query);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                //echo "\long: " . $row['coord_x'] . "\nlat: " . $row['coord_y'];
                $jsonString = $jsonString . '{ "longitude" : "' . $row['coord_x'] .
                    '", "latitude" : "' . $row['coord_y'] . '", "layer" : "' . $row['layer'] . '" }, ';
            }
        }
        unset($query); // Break the reference with the last element

        if ($jsonString === '{ "items": [') {
            $jsonString = "EMPTY QUERY";
        }
        else {
            $jsonString = substr($jsonString, 0, -2);   // Drop last comma and whitespace
            $jsonString = $jsonString . '] }';          // Close "items" array with ']' and JSON string '}'
        }

        // JSON string basic example. This is how it will look like
        /*$jsonString = '{
            "items": [
                {   "longitude" : "635950.170698504",
                    "latitude" : "2128429.0452",
                    "layer" : "BANCOS"
                },
                {   "longitude" : "633004.021067216",
                    "latitude" : "2126786.67243626",
                    "layer" : "HOTELES"
                }
            ]}
        ';*/

        // Return JSON string through jQuery AJAX call on template_map.php
        echo $jsonString;
    } // try
    catch(PDOException $e) {
        echo 'ERROR: ' . $e->getMessage();
    } // catch
    // Free statement and connection resources
    $stmt = null;
    $conn = null;

?>