var maxNumberOfBoxShapes = 2;
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

var center = ol.proj.transform([-103.716, 19.247], 'EPSG:4326', 'EPSG:3857'); // Colima coordinates

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

// Map cursor is ready to select mouseovered markers after printMarkers(data) adds features to flickrSource
map.addInteraction(selectedMarker);

function showMarkerInfo(innerHTMLData, utm_lon, utm_lat) {
    var markerTable = document.getElementById("map-marker-table");

    markerTable.innerHTML = "";
    markerTable.innerHTML = innerHTMLData;

    // Latitude appears first on GPS coordinates
    var coord_y = parseFloat(utm_lat);  // Original UTM latitude string (from map marker) to float
    var coord_x = parseFloat(utm_lon);  // Original UTM longitude string (from map marker) to float

    // utm2dec() takes longitude first
    var coordArray = utm2dec(coord_x, coord_y, 13, true); // Colima belongs to 13Q or 13N (north) zone
    var dec_lat = coordArray[1];
    var dec_lon = coordArray[0];

    // .toFixed(6) converts a number into a string, keeping only 6 decimals
    dec_lat = dec_lat.toFixed(6);
    dec_lon = dec_lon.toFixed(6);

    // Change UTM coordinates to decimal in the table
    markerTable.rows[0].cells[1].innerHTML = dec_lat;
    markerTable.rows[1].cells[1].innerHTML = dec_lon;

    // Change href link according to the element coordinates
    var googleMapsQuery = "http://maps.google.com/maps?q=" + dec_lat + "," + dec_lon;
    document.getElementById("a-googlemaps").href = googleMapsQuery;

    $('#map-marker-modal').modal('show');
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
                url: "index.php/App_c/getMapSelectedMarker",
                data: "markerData=" + markerData,
                dataType: "json",
                success: function(data) {
                    //console.log("Selected marker table data: " + data);
                    showMarkerInfo(data, coord_x, coord_y);
                },
                error: function() {
                    console.log("Error! Selected marker data could not be retrieved");
                    $('body').css('cursor', 'auto');
                }
            }); // AJAX
        });
    }
});

// When jQuery has retrieved the data, this creates features for each layer element and adds them to the map
function printMarkers(data) {
    // Required variables for each element in the data array
    var feature, coordArray, lon, lat, coordinate, geometry;
    // We need to transform the geometries into the view's projection
    var transform = ol.proj.getTransform('EPSG:4326', 'EPSG:3857');
    // Text notification after loop ends
    var successMessage;

    for(var i = 0; i < data.length; i++) {
        // Create a new feature with the element as the properties
        feature = new ol.Feature(data[i]);
        // Create an appropriate geometry and add it to the feature
        // proj4.js library did not work, so utm2dec(x, y, utmz, north) is used insted
        // Colima belongs to 13Q or 13N (north) zone
        coordArray = utm2dec(parseFloat(data[i].longitude), parseFloat(data[i].latitude), 13, true);
        lon = coordArray[0];
        lat = coordArray[1];
        coordinate = transform([lon, lat]);
        geometry = new ol.geom.Point(coordinate);
        feature.setGeometry(geometry);
        feature.setStyle(flickrStyle(data[i].layer));   // Set map marker depending on the layer
        flickrSource.addFeature(feature);               // Add the feature to the source
    }

    data.length == 1 ? successMessage = "1 marcador trazado en el mapa" : successMessage = data.length + " marcadores trazados en el mapa";
    showToastNotif('Búsqueda completada', successMessage, 'bottom-right', 'success');
    $('body').css('cursor', 'auto');                    // Reset mouse cursor after finishing drawing markers
}

// When jQuery has retrieved the data, this updates the total column in the datatable for each row
// tableData and totalData have the same length because the latter was created from the former in the model
// https://stackoverflow.com/questions/25929347/how-to-redraw-datatable-with-new-data
function printTotals(totalData) {
    var table = $('#myDataTable').DataTable();
    var tableData = table.data();

    for(i = 0; i < tableData.count(); i++) {
        tableData[i].total = totalData[i];      // Update total cell for each row
    }

    table.clear().rows.add(tableData).draw();   // Redraw the table with the same values aside from total column
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

// https://stackoverflow.com/questions/41252613/what-is-the-best-way-to-create-an-array-of-objects-in-javascript
function continueIfQueryIsValid(datatableObj) {
    var tableData = [];
    for(i = 0; i < datatableObj.count(); i++) {
        tableData.push({
            capa: datatableObj[i].capa,
            campo: datatableObj[i].campo,
            valor: datatableObj[i].valor
        });
    }
    tableData = JSON.stringify(tableData);
    //console.log("Table data after JSON:\n" + tableData);

    var pointsArray = getLastFeatureCoord(); // Split all points into individual longitude or latitude
    //console.log("Points array after JSON:\n" + pointsArray);

    var booleanOp = $('input:radio[name=booleanOps]:checked').val();

    $.ajax({
        type: "POST",
        url: "index.php/App_c/getMapTotals",
        data: { tableData:tableData, pointsArray:pointsArray, booleanOp:booleanOp },
        dataType: "json",
        success: function(data) {
            //console.log("Totals: " + data);
            printTotals(data);
        },
        error: function() {
            console.log("Error! Totals could not be retrieved");
            $('body').css('cursor', 'auto');
        }
    }); // AJAX

    $.ajax({
        type: "POST",
        url: "index.php/App_c/getMapMarkers",
        data: { tableData:tableData, pointsArray:pointsArray, booleanOp:booleanOp },
        dataType: "json",
        cache: false,
       /* shows gif loader 
       beforeSend: function(){
            $('#map').hide();
            $('#imagen').show();
        },
        complete: function(){
            $('#imagen').hide();
            $('#map').show();
        },*/
        success: function(data) {
            //console.log("Markers: " + data);
            /* This AJAX call is slower than getMapTotals and requires a lot of time to add the markers
            for each feature, so it resets the mouse cursor at the end of printMarkers(data) */
            if(data.length == 0) {
                showToastNotif('Búsqueda sin resultados', 'No se encontraron elementos dentro del área de influencia', 'bottom-right', 'warning');
                $('body').css('cursor', 'auto'); // Reset mouse cursor if there are no markers to draw
            }
            else
                printMarkers(data);
        },
        error: function() {
            console.log("Error! Markers could not be retrieved");
            $('body').css('cursor', 'auto');
        }
    }); // AJAX
    
    $.ajax({
        type: "POST",
        url: "index.php/App_c/getMapMarkersData",
        data: { tableData:tableData, pointsArray:pointsArray, booleanOp:booleanOp },
        dataType: "json",
        success: function(data) {

                var exportar_b = document.getElementById("exportar-boton");
                var div_t = document.createElement("div");
                var title = document.createElement("h1");
                //var tableData = JSON.stringify(data,null,4);
                myData = data;
                var col = [];
                for (var i = 0; i < myData.length; i++) {
                    for (var key in myData[i]) {
                        if (col.indexOf(key) === -1) {
                            col.push(key);
                        }
                    }
                }
                if(myData.length == 0) {
            
                    div_t.className = "div_t";
                    div_t.setAttribute("align", "center");
                    title.innerHTML = "No hay datos que mostrar";
                    div_t.appendChild(title);

                    var divContainer = document.getElementById("map-table");
                    divContainer.innerHTML = "";
                    //divContainer.appendChild(thead);
                    divContainer.appendChild(div_t);
                    exportar_b.style.visibility = "hidden";
                } else {
              // Create table.
              

                div_t.className = "div_t";
                title.innerHTML = "Tabla de datos";
                div_t.appendChild(title);

                var table = document.createElement("table");
                var header = table.createTHead();

                table.className = "table table-striped table-bordered dataTable no-footer display";
                table.setAttribute("id", "myDataSetTable");
                table.setAttribute("cellspacing", "0");
                table.setAttribute("width", "100%");
    
                header.className = "tbl-blue-th";
                // Create table header row from json
            
                var tr = table.insertRow(-1);                   // Table row
                var tr1 = document.createElement("tr"); 
                table.setAttribute("role", "row");
                for (var i = 0; i < col.length; i++) {
                    var th = document.createElement("th");      // table header
                    th.innerHTML = col[i];
                    tr.appendChild(th);
                    tr1.appendChild(th);
                    header.appendChild(tr1);
                }
        
                // Add the data to de table
                for (var i = 0; i < myData.length; i++) {

                    for (var j = 0; j < col.length; j++) {
                        var tabCell = tr.insertCell(-1);
                            if((myData[i][col[j]]==undefined) || (myData[i][col[j]]=="")){
                            tabCell.innerHTML = "?";
                         
                            }else{
                            tabCell.innerHTML = myData[i][col[j]];
                            
                            }
                    }
                    tr = table.insertRow(-1);
                }
                var rowCount = table.rows.length;
                table.deleteRow(rowCount -1);
                
                // Add the json data to the div container
                var divContainer = document.getElementById("map-table");
                divContainer.innerHTML = "";
                //divContainer.appendChild(thead);
                divContainer.appendChild(div_t);
                divContainer.appendChild(table);
                //document.getElementById("map-table").innerHTML += tableData; 
                $('#myDataSetTable').DataTable({ 
                    "destroy": true, //use for reinitialize datatable
                });
                exportar_b.style.visibility = "visible";
                }
        },
        error: function() {
            console.log("Error! Markers could not be retrieved");
            $('body').css('cursor', 'auto');
            
        }

      
    }); // AJAX

   
     
                  
    
        
   
}                                                                                                                                     