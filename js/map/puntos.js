var flickrSource = new ol.source.Vector();


////////////////////////////////Formato del vector en puntos////////////////////////////////////////////////////
var getText = function (feature, resolution) {
    var type = 'shorten';
    var maxResolution = '1200';
    var text = feature.get('title');
    //text = ''; //Marcadores vacíos
    text = text.trunc(12); //Texto recortado
    //text = stringDivider(text, 16, '\n'); //Dividido por palabras
    return text;
};
var createTextStyle = function (feature, resolution) {
    var align = 'center';
    var baseline = 'middle';
    var size = '12px';
    var offsetX = 0;
    var offsetY = 0;
    var weight = 'normal';
    var rotation = 0;
    var font = weight + ' ' + size + ' ' + 'Arial';
    var fillColor = '#aa3300';
    var outlineColor = '#ffffff';
    var outlineWidth = 3;

    return new ol.style.Text({
        textAlign: align,
        textBaseline: baseline,
        font: font,
        text: getText(feature, resolution),
        fill: new ol.style.Fill({
            color: fillColor
        }),
        stroke: new ol.style.Stroke({
            color: outlineColor,
            width: outlineWidth
        }),
        offsetX: offsetX,
        offsetY: offsetY,
        rotation: rotation
    });
};

function pointStyleFunction(feature, resolution) {
    return new ol.style.Style({
        image: new ol.style.Circle({
            radius: 10,
            fill: new ol.style.Fill({
                color: 'rgba(255, 0, 0, 0.1)'
            }),
            stroke: new ol.style.Stroke({
                color: 'red',
                width: 1
            })
        }),
        text: createTextStyle(feature, resolution)
    });
}

function selectPointStyleFunction(feature, resolution) {
    return new ol.style.Style({
        image: new ol.style.Circle({
            radius: 20,
            fill: new ol.style.Fill({
                color: 'rgba(255, 0, 0, 0.1)'
            }),
            stroke: new ol.style.Stroke({
                color: 'red',
                width: 1
            })
        }),
        text: createTextStyle(feature, resolution)
    });
}


////////////////////////////////////////////////////////////////////////////////////

var flickrLayer = new ol.layer.Vector({
    source: flickrSource,
    style: pointStyleFunction
});

var layer = new ol.layer.Tile({
    source: new ol.source.OSM()
});

var center = ol.proj.transform([0, 0], 'EPSG:4326', 'EPSG:3857');

var view = new ol.View({
    center: center,
    zoom: 2
});

var map = new ol.Map({
    target: 'map',
    layers: [layer, flickrLayer],
    view: view
});

function photoContent(feature) {
    var content = $('#photo-template').html();
    var keys = ['author', 'author_id', 'date_taken', 'latitude', 'longitude', 'link', 'url', 'tags', 'title'];
    for (var i = 0; i < keys.length; i++) {
        var key = keys[i];
        var value = feature.get(key);
        content = content.replace('{' + key + '}', value);
    }
    return content;
}

// Create a Select interaction and add it to the map
var select = new ol.interaction.Select({
    layers: [flickrLayer],
    style: selectPointStyleFunction
});
map.addInteraction(select);

// Use the features Collection to detect when a feature is selected,
// The collection will emit the add event
var selectedFeatures = select.getFeatures();

selectedFeatures.on('add', function (event) {
    var feature = event.target.item(0);
    var content = photoContent(feature);
    // Put the url of the feature into the photo-info div
    $('#photo-info').html(content);
});

// When a feature is removed, clear the photo-info div
selectedFeatures.on('remove', function (event) {
    $('#photo-info').empty();
});

function successHandler(data) {
    var transform = ol.proj.getTransform('EPSG:4326', 'EPSG:3857');
    data.items.forEach(function (item) {
        var feature = new ol.Feature(item);
        feature.set('url', item.media.m);
        var coordinate = transform([parseFloat(item.longitude), parseFloat(item.latitude)]);
        var geometry = new ol.geom.Point(coordinate);
        feature.setGeometry(geometry);
        flickrSource.addFeature(feature);
    });
}

$.ajax({
    url: 'flickr_data.json',
    dataType: 'jsonp',
    jsonpCallback: 'jsonFlickrFeed',
    success: successHandler
});


/**
 * @param {number} n The max number of characters to keep.
 * @return {string} Truncated string.
 */
String.prototype.trunc = String.prototype.trunc ||
    function (n) {
        return this.length > n ? this.substr(0, n - 1) + '...' : this.substr(0);
    };


// http://stackoverflow.com/questions/14484787/wrap-text-in-javascript
function stringDivider(str, width, spaceReplacer) {
    if (str.length > width) {
        var p = width;
        while (p > 0 && (str[p] != ' ' && str[p] != '-')) {
            p--;
        }
        if (p > 0) {
            var left;
            if (str.substring(p, p + 1) == '-') {
                left = str.substring(0, p + 1);
            } else {
                left = str.substring(0, p);
            }
            var right = str.substring(p + 1);
            return left + spaceReplacer + stringDivider(right, width, spaceReplacer);
        }
    }
    return str;
}