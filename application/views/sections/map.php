<script>
    var flickrSource = new ol.source.Vector();
    var boxSource = new ol.source.Vector();

    // https://stackoverflow.com/questions/24315801/how-to-add-markers-with-openlayers-3
    // https://mapicons.mapsmarker.com
    function flickrStyle(layer) {
            var style = new ol.style.Style({
            image: new ol.style.Icon(({
            anchor: [1.2, 1.2],
            anchorXUnits: 'fraction',
            anchorYUnits: 'fraction',
            src: 'images/mapMarkers/' + layer + '.png'
            }))
        });
        return [style];
    }

    var flickrLayer = new ol.layer.Vector({
        id: 'mapMarkers',
        source: flickrSource
    });

    var boxLayer = new ol.layer.Vector({
        id: 'mapBoxShape',
        source: boxSource
    });

    var layer = new ol.layer.Tile({
        source: new ol.source.OSM()
    });

    var center = ol.proj.transform([-103.724040, 19.245244], 'EPSG:4326', 'EPSG:3857'); // Colima coordinates

    var view = new ol.View({
        center: center,
        zoom: 14
    });

    var coordFormat = 'Coordenadas (GD): {y}, {x}';

    // https://openlayers.org/en/latest/examples/mouse-position.html
    // https://openlayers.org/en/latest/doc/faq.html#why-is-the-order-of-a-coordinate-lon-lat-and-not-lat-lon-
    var mousePositionControl = new ol.control.MousePosition({
        // coordinateFormat: ol.coordinate.createStringXY(6), // 6-decimal precision, shows longitude first
        coordinateFormat: function(coord) {
                return ol.coordinate.format(coord, coordFormat, 4); // 4-decimal precision
            },
        projection: 'EPSG:4326',
        // Comment the following two lines to have the mouse position be placed within the map
        className: 'custom-mouse-position',
        target: document.getElementById('mouse-position'),
        undefinedHTML: 'Para un click individual, pulse <code>Alt+Click</code> / <code>Ctrl+Click</code>'
    });

    // https://openlayersbook.github.io/ch08-interacting-with-your-map/example-01.html
    // https://openlayers.org/en/latest/examples/select-features.html
    // Cursor styles: https://openlayers.org/en/latest/apidoc/ol.events.condition.html
    var selectedMarker = new ol.interaction.Select({
        condition: ol.events.condition.singleClick, // pointerMove means hover; singleClick is not a normal click
        toggleCondition: ol.events.condition.shiftKeyOnly,
        layers: function (layer) {
            return layer.get('id') == 'mapMarkers';
        }
    });

    var map = new ol.Map({
        controls: ol.control.defaults().extend([
            new ol.control.FullScreen(),
            new ol.control.ScaleLine(),
            new ol.control.ZoomSlider()
        ]).extend([mousePositionControl]),
        target: 'map',
        layers: [layer, flickrLayer, boxLayer],
        view: view
    });

    // Feature shape depending on option selected (square, box or polygon)
    var featureSelected = document.getElementById("cbShapes");

    // Global box shape so we can remove it later
    var draw;

    /*
     * https://openlayers.org/en/latest/examples/draw-shapes.html
     * https://openlayers.org/en/latest/examples/draw-features.html
     * https://openlayers.org/en/latest/examples/custom-controls.html
     * https://stackoverflow.com/questions/38713008
     * how-to-get-the-coordinates-of-a-drawn-box-in-openlayers/38713686
    */
    // Set box shape properties after drawn
    function addInteraction() {
        var featureValue = featureSelected.value;

        if(featureValue !== 'None') {
            if(featureValue === 'Polygon') {
                draw = new ol.interaction.Draw({
                    source: boxSource,
                    type: featureValue
                });
                map.addInteraction(draw);
            } // Polygon
            else {
                var geometryFunction;

                if(featureValue === 'Box') {
                    geometryFunction = ol.interaction.Draw.createBox();
                } // Box
                else {
                    geometryFunction = ol.interaction.Draw.createRegularPolygon(4);
                } // Square

                featureValue = 'Circle'; // Both box and square shape are created by using type: 'Circle'

                draw = new ol.interaction.Draw({
                    source: boxSource,
                    type: featureValue,
                    geometryFunction: geometryFunction
                });
                map.addInteraction(draw);
            } // Box or square

            draw.on('drawend', function(e) {
                if(boxSource.getFeatures().length == maxNumberOfBoxShapes)
                    boxSource.clear();
            });
        } // if(featureValue !== 'None')
    }

    // Remove feature in case the user selects another option before finishing closing the square, box or polygon
    featureSelected.onchange = function() {
        map.removeInteraction(draw);
        addInteraction();
    };

    // Map cursor is always ready to draw box shapes when map is clicked
    addInteraction();

    // Map cursor is ready to select mouseovered markers after successHandler(data) adds features to flickrSource
    map.addInteraction(selectedMarker);

    function showMarkerInfo(innerHTMLData) {
        var tableM = document.getElementById("tblMarkerInfo");

        tableM.innerHTML = "";
        tableM.innerHTML = innerHTMLData;

        // Latitude appears first on GPS coordinates
        var coord_y = parseFloat(tableM.rows[0].cells[1].innerHTML); // coord_x (UTM) to decimal latitude
        var coord_x = parseFloat(tableM.rows[1].cells[1].innerHTML); // coord_y (UTM) to decimal longitude

        // utm2dec() takes longitude first
        var coordArray = utm2dec(coord_x, coord_y, 13, true); // Colima belongs to 13Q or 13N (north) zone
        var lat = coordArray[1];
        var lon = coordArray[0];

        // .toFixed(6) converts a number into a string, keeping only 6 decimals
        lat = lat.toFixed(6);
        lon = lon.toFixed(6);

        // Change UTM coordinates to decimal in the table
        tableM.rows[0].cells[1].innerHTML = lat;
        tableM.rows[1].cells[1].innerHTML = lon;

        // Change href link according to the element coordinates
        var googleMapsQuery = "http://maps.google.com/maps?q=" + lat + "," + lon;
        document.getElementById("aGoogleMaps").href = googleMapsQuery;

        $("#myModalMapMarker").modal("show");
    }

    /*
     * https://gis.stackexchange.com/questions/166506/openlayers-3-select-interaction-style-function
     * https://stackoverflow.com/questions/675231/how-do-i-access-properties-of-a-javascript-object
     * if-i-dont-know-the-names
    */
    selectedMarker.on('select', function(e) {
        //console.log("Event selected (1/0): " + e.selected); // Tell whether a marker has been mouseovered
        var selected = e.selected;
        var featureValue = featureSelected.value;
        var lastBox;

        if(selected.length) {
            if(featureValue !== 'None') {
                maxNumberOfBoxShapes = -1;          // Disable boxSource.clear() on draw.on('drawend')
                draw.finishDrawing();               // Disable box shape after the user clicks the map marker
                maxNumberOfBoxShapes = 2;           // Restore boxSource.clear() after the shape was canceled

                lastBox = boxSource.getFeatures()[boxSource.getFeatures().length - 1];
                boxSource.removeFeature(lastBox);   // Delete any trace of the shape canceled
            }

            selected.forEach(function(feature) {
                //console.info(feature);
                //console.log("Map marker UTM Longitude: " + feature["values_"]["longitude"]);
                //console.log("Map marker UTM Latitude: " + feature["values_"]["latitude"]);

                var coord_x = feature["values_"]["longitude"];
                var coord_y = feature["values_"]["latitude"];
                var marcador = feature["values_"]["layer"];

                var markerData = {"coord_x": coord_x, "coord_y": coord_y, "marcador": marcador};
                markerData = JSON.stringify(markerData);

                $.ajax({
                    type: "POST",
                    url: "sqlqueries/mapSelectedMarker.php",
                    data: "pMarkerData=" + markerData,
                    success: function(result) {
                        //console.log(result);
                        showMarkerInfo(result);
                    }
                }); // AJAX
            });
        }
    });

    // When jQuery has loaded the data, we can create features for each element in a layer
    function successHandler(data) {
        var data = JSON.parse(data);
        // We need to transform the geometries into the view's projection
        var transform = ol.proj.getTransform('EPSG:4326', 'EPSG:3857');
        // Loop over the items in the response
        data.items.forEach(function(item) {
            // Create a new feature with the item as the properties
            var feature = new ol.Feature(item);
            // Create an appropriate geometry and add it to the feature
            // proj4.js library did not work, so utm2dec(x, y, utmz, north) is used insted
            /*var lon = UTMtoDecimal([parseFloat(item.longitude), parseFloat(item.latitude)], "lon");
            var lat = UTMtoDecimal([parseFloat(item.longitude), parseFloat(item.latitude)], "lat");
            var coordinate = transform([lon, lat]);*/

            //console.log("UTM Longitude: " + parseFloat(item.longitude));
            //console.log("UTM Latitude: " + parseFloat(item.latitude));
            // Colima belongs to 13Q or 13N zone (north)
            var coordArray = utm2dec(parseFloat(item.longitude), parseFloat(item.latitude), 13, true);
            //console.log("DD Longitude:  " + coordArray[0]);
            //console.log("DD Latitude:  " + coordArray[1]);

            var lon = coordArray[0];
            var lat = coordArray[1];
            var coordinate = transform([lon, lat]);
            //console.log("Coordinate (x,y): " + coordinate);

            var geometry = new ol.geom.Point(coordinate);
            feature.setGeometry(geometry);
            feature.setStyle(flickrStyle(item.layer));  // Set map marker depending on the layer
            flickrSource.addFeature(feature);           // Add the feature to the source
        });

        $('body').css('cursor', 'auto');                // Reset mouse cursor after finishing drawing markers
    }

    function printTotals(data) {
        var data = JSON.parse(data);

        var tableF = document.getElementById("tblFiltros");
        var tableT = document.getElementById("tblTotales");
        var row, height, capaRowIndex;
        var dataIndex = 0;

        tableT.innerHTML = "";

        // Insert empty rows in tblTotales
        for(var i = 0; i < tableF.rows.length; i++) {
            tableT.insertRow(i);
        }

        // Add total numbers in cells in tblTotales
        for(var i = 1; i <= addedFiltersCounter; i++) {
            if(document.getElementById("capa" + i)) {
                capaRowIndex = document.getElementById("capa" + i).parentNode.rowIndex;
                row = tableT.rows[capaRowIndex - 1];
                row.insertCell(0);
                row.cells[0].innerHTML = data[dataIndex];
                dataIndex++;
                // Another way:
                //tableT.rows[i].cells[0].innerHTML = data[i];
            }
        }

        // Resize row height in tblTotales
        for(var i = 0; i < tableF.rows.length; i++) {
            height = $(tableF.rows[i]).height();
            row = tableT.rows[i];
            row.setAttribute("height", height);
        }
    }

    function validQuery() {
        if(document.getElementById("tblFiltros").rows.length == 0) {
            // 3 líneas necesarias para adaptar el mensaje de alerta
            $("#myModalMapWarnings").modal("show");
            $("#h3ModalMapWarning").text("No se han agregado filtros de búsqueda");
            $("#messageModalMapWarning").text("Por favor, seleccione al menos una capa y agregue un filtro (opcional) para buscar los elementos que cumplen esa condición");
            return false;
        }
        else {
            if(boxSource.getFeatures().length == 0) {
                // 3 líneas necesarias para adaptar el mensaje de alerta
                $("#myModalMapWarnings").modal("show");
                $("#h3ModalMapWarning").text("No se ha encontrado un área de influencia");
                $("#messageModalMapWarning").text("Por favor, trace una figura en el mapa para delimitar el área de influencia donde se aplicarán los filtros de búsqueda");
                return false;
            }
            return true;
        }
    }

    // https://github.com/openlayers/openlayers/issues/2500
    // https://epsg.io/transform#s_srs=3857&t_srs=4326
    function getLastFeatureCoord() {
        var lastBox = boxSource.getFeatures()[boxSource.getFeatures().length - 1];
        var vertices = lastBox.getGeometry().getCoordinates();
        var pointsArray = [];
        var currentVertex;

        vertices = vertices + "";       // Convert geometry to string to handle the n points
        vertices = vertices.split(","); // Split feature coordinates into individual longitude and latitude

        for(var i = 0; i < vertices.length; i+=2) {
            currentVertex = [];
            currentVertex.push(parseFloat(vertices[i]));
            currentVertex.push(parseFloat(vertices[i+1]));
            pointDG = ol.proj.transform(currentVertex, 'EPSG:3857', 'EPSG:4326');   // 3857 to DG
            pointUTM = dec2utm(pointDG[0], pointDG[1]);                             // DG to UTM
            pointsArray.push(pointUTM[0]);          // Point UTM longitude
            pointsArray.push(pointUTM[1]);          // Point UTM latitude
        }

        //var totalPoints = pointsArray.length;
        //alert(totalPoints + " UTM Points (vertices) Array BEFORE JSON: " + pointsArray);

        return JSON.stringify(pointsArray);         // At least 8 points (3 vertices)
    }

    function continueIfPolygonISValid() {
        var tableData = [];
        for(var i = 1; i <= addedFiltersCounter; i++) {
            if(document.getElementById("capa" + i)) {
                tableData.push(document.getElementById("capa" + i).innerText);
                tableData.push(document.getElementById("campo" + i).innerText);
                tableData.push(document.getElementById("filtro" + i).innerText);
            }
        }
        tableData = JSON.stringify(tableData);
        //alert("Table Array after JSON:\n" + tableData);

        var booleanOp = [ $('input:radio[name=booleanOps]:checked').val() ];
        booleanOp = JSON.stringify(booleanOp);
        //console.log("Selected radio button:" + booleanOp);

        var pointsArray = getLastFeatureCoord(); // Split all points into individual longitude or latitude

        $.ajax({
            type: "POST",
            url: "sqlqueries/mapFilters.php",
            data: { pTableData:tableData, pBooleanOp:booleanOp, pPointsArray:pointsArray },
            success: function(result) {
                //console.log(result);
                /* This AJAX call is slower than the other, so it resets the mouse cursor at the end
                Update1: The function still needs more time to add the markers for each feature, so the
                cursor is reset at the end of successHandler(result) */
                //$('body').css('cursor', 'auto');
                if(result != "EMPTY QUERY")
                    successHandler(result);
                else
                    $('body').css('cursor', 'auto'); // Reset mouse cursor if there are no markers to draw
            }
        }); // AJAX

        $.ajax({
            type: "POST",
            url: "sqlqueries/mapTotals.php",
            data: { pTableData:tableData, pBooleanOp:booleanOp, pPointsArray:pointsArray },
            success: function(result) {
                //console.log(result);
                printTotals(result);
            }
        }); // AJAX
    }

    $(document).ready(function() {
        /*$("#btnQuery").click(function() {
            if(validQuery()) {
                var pointsArray = getLastFeatureCoord(); // Split all points into individual longitude or latitude
                flickrSource.clear();   // Delete UTM2DEC points (map markers) added during the previous query
                if(selectedMarker) {    // Delete last selected map marker in case it exists
                    selectedMarker.getFeatures().clear();
                }
                $('body').css('cursor', 'wait'); // Call mapFilters.php and mapTotals.php
                continueIfPolygonISValid();

            } // if(validQuery())
        }); // $("#btnQuery").click()*/

        // Close all FAQ accordion tabs when the modal is close
        $("#myModalHelp").on("hidden.bs.modal", function () {
            $('.panel-collapse.in').collapse('hide');
        });
    }); // $(document).ready()
</script>