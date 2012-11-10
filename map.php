<!DOCTYPE html>

<html>
<?php include_once("database_lib.php"); ?>
<head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />

    <!-- Bootstrap -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="bootstrap/css/bootstrap-responsive.css" rel="stylesheet" media="screen">

    <style type="text/css">
        html {
            height: 100%
        }

        body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        #map_canvas {
            height: 100%;
        }
    </style>

    <script type="text/javascript" src="js/load-details.js"></script>
    <script type="text/javascript" src="js/map-helper.js"></script>
    <script type="text/javascript" src="js/data-helper.js"></script>
    <script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
    <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>

    <script type="text/javascript">
        var selectedState = '';

        function initialize() {
            var defaultBounds = new google.maps.LatLngBounds(new google.maps.LatLng(36.2679, 67.8718), new google.maps.LatLng(5.28, 97.8718));

            var mapOptions = {
                bounds: defaultBounds,
                center: new google.maps.LatLng(23, 81),
                zoom: 5,
                minZoom: 5,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                streetViewControl: false,
            }

            var map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
            
            google.maps.event.addListener(map, 'click', function(event) {
                getState(event.latLng);
            });

            addMarker(1, map, 21.7679, 78.8718);

            $('#tabs a:first').tab('show'); // Select first tab

            $('#options a:first').tab('show'); // Select first pill

            $('#options a').click(function() {
                $(this).tab('show'); // Select this pill
            });
        }

        function loadScript() {
            var script = document.createElement("script");
            script.type = "text/javascript";
            script.src = "http://maps.googleapis.com/maps/api/js?key=AIzaSyDnJnLN0ZGWeifcxjGxaXNQ5Td8ipRe5QQ&sensor=false&region=IN&callback=initialize";
            document.body.appendChild(script);
        }

        window.onload = loadScript;
    </script>
</head>
<body>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span4">
                <ul id="tabs" class="nav nav-tabs">
                    <li><a href="#overview" data-toggle="tab">Overview</a></li>
                    <li><a href="#projects" data-toggle="tab" >Projects</a></li>
                    <li><a href="#details" data-toggle="tab">Details</a></li>
                </ul>

                <div id="tabsContent" class="tab-content">
                    <div class="tab-pane fade active in" id="overview">
                    <ul id="options" class="nav nav-pills">
                        <li><a href="#states" data-toggle="tab">States</a></li>
                        <li><a href="#focuses" data-toggle="tab">Focus Areas</a></li>
                        <li><a href="#chapters" data-toggle="tab">Chapters</a></li>
                    </ul>

                        <div id="optionsContent" class="tab-content">
                            <div class="tab-pane fade active in" id="states">
                                <ul id="states-list" class="nav nav-pills nav-stacked">
                                    <?php
displayAll(0);
?>       
                                </ul>
                            </div>

                            <div class="tab-pane fade active in" id="focuses">
                                <ul id="focuses-list" class="nav nav-pills nav-stacked">
                                    <?php
displayAll(1);
?>  
                                </ul>
                            </div>

                            <div class="tab-pane fade active in" id="chapters">
                                <ul id="chapters-list" class="nav nav-pills nav-stacked">
                                   <?php
displayAll(2);
?>  
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade active in" id="projects">
                        <ul id="projects-list" class="nav nav-pills nav-stacked">
                            <?php displayList(0, 3); ?>
                        </ul>
                    </div>

                    <div class="tab-pane fade active in" id="details">
                        <?php displayProject(1); ?>
                    </div>
                </div>
            </div>
            <div class="span8">
                <!-- <div id="map_canvas" style="width: 100%; height: 100%"></div> -->
                <div id="map_canvas" style="width: 100%; height:800px;"></div>
            </div>
        </div>
    </div>
</body>
</html>
