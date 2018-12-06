<?php
    require_once 'mapSwitchTable.php';
    require_once 'mapSelectedMarkerSwitchs.php';

    // This code must not be edited. All the map filters must be added/changed in mapSelectedMarkerSwitchs.php

    // Unescape the string values in the JSON array
    $markerData = stripcslashes($_POST['pMarkerData']); // 3-column map marker (coord_x, coord_y and marcador)

    // Decode the JSON array
    $markerData = json_decode($markerData, TRUE);

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
        "SELECT tableColumns
        FROM tableName
        WHERE coord_x = coord_x_ajax AND coord_y = coord_y_ajax LIMIT 1";

        $query = str_replace("tableName", switchTableMarker($markerData["marcador"]), $query); // DB table name
        $query = str_replace("coord_x_ajax", $markerData["coord_x"], $query);                       // Longitude
        $query = str_replace("coord_y_ajax", $markerData["coord_y"], $query);                       // Latitude
        $query = str_replace("tableColumns", switchColumnMarker($markerData["marcador"]), $query);  // DB columns

        //echo "QUERY: " . $query;

        $stmt = $conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $jsonString = '';
        foreach ($row as $col => $value) {
            $jsonString = $jsonString . '<tr> <td style="width: 40%; text-align: right;">' . $col . '</td>' .
                '<td style="width: 60%">' . $value . '</td> </tr>';
        }

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