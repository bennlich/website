<?php
$root = "..";
require_once("$root/base.php");

pageHeader("Documentation - Integration with OpenLayers");
?>

<h2>1. Link OpenLayers and OSM Buildings files</h2>

<p>Your HTML head section should look like this.</p>

<pre class="code">
&lt;head&gt;
	&lt;script src=&quot;http://www.openlayers.org/api/OpenLayers.js&quot;&gt;&lt;/script&gt;
	&lt;script src=&quot;dist/Openlayers.Layer.Buildings.js&quot;&gt;&lt;/script&gt;
&lt;/head&gt;
</pre>

<h2>2. Initialize the map engine and add a map tile layer</h2>

<p>Position is set to Berlin at zoom level 17.</p>

<pre class="code">
var map = new OpenLayers.Map('map');
map.addControl(new OpenLayers.Control.LayerSwitcher());

var osm = new OpenLayers.Layer.OSM();
map.addLayer(osm);

map.setCenter(
    new OpenLayers.LonLat(13.37570, 52.52020)
        .transform(
            new OpenLayers.Projection('EPSG:4326'),
            map.getProjectionObject()
        ),
    17
);
</pre>

<h2>3.a Add the buildings layer, database</h2>

<p>Attach this to the map as well.<br>
Here we are using a database + PHP backend which has to be hosted on the same server.
Otherwise you are running into cross origin security issues. Adapt the url parameter to your needs.<br>
Right now, the library's backend provides sample data for cities Berlin and Frankfurt, Germany.
For your city of choice, you need to convert OSM data yourself.</p>

<pre class="code">
map.addLayer(new OpenLayers.Layer.Buildings({ url: 'server/?w={w}&n={n}&e={e}&s={s}&z={z}' }));
</pre>

<h2>3.b Add the buildings layer, GeoJSON</h2>

<p>As a very popular alternative, you could add <a href="http://www.geojson.org/geojson-spec.html">GeoJSON</a> data.<br>
Just pass a data object to the engine, it doesn't need to be real buildings.
Ensure it's coordinates are set as lon/lat[/alt] and the properties section is set as shown below.</p>

<pre class="code">
var osmb = new OpenLayers.Layer.Buildings();
map.addLayer(osmb);

var data = {
    "type": "FeatureCollection",
    "features": [{
        "type": "Feature",
        "geometry": {
            "type": "Polygon",
            "coordinates": [[
                [13.37356, 52.52064],
                [13.37350, 52.51971],
                [13.37664, 52.51973],
                [13.37594, 52.52062],
                [13.37356, 52.52064]
            ]]
        },
        "properties": {
            "wallColor": "rgb(255,0,0)",
            "height": 500
        }
    }]
};

osmb.geoJSON(data);
</pre>

<script>
var pre = document.querySelectorAll('.code');
for (var i = 0, il = pre.length; i < il; i++) {
    hljs.highlightBlock(pre[i]);
    pre[i].onclick = function (e) {
        this.className += ' large';
        e.cancelBubble = true;
    };
}

document.onclick = function () {
    for (var i = 0, il = pre.length; i < il; i++) {
        pre[i].className = pre[i].className.replace(/ large/g, '');
    }
};
</script>

<?php pageFooter()?>