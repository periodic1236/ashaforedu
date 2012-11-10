<!DOCTYPE html>
<html>
<head>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDnJnLN0ZGWeifcxjGxaXNQ5Td8ipRe5QQ&sensor=false&region=IN">
</script>

<script>
    function getCoords(town, country) {
        var geocoder = new google.maps.Geocoder();
        var address = town + ', ' + country;

        if (geocoder) {
            geocoder.geocode({'address' : address}, function(results, status) {
                if (status === google.maps.GeocoderStatus.OK) {
                    var coordinates = results[0].geometry.location;
                    $.post('http://anjoola.com/asha/geocode.php', { town: town, latitude: coordinates['Ya'], longitude: coordinates['Za'] }, function(data) {
                        console.log(town, coordinates['Ya'], coordinates['Za']);
                    });
                } else {
                    console.log('Geocoding failed: ' + status);
                }
            });
        }
    }
</script>

</head>
<body>
</body>
</html>

<?php

if (isset($_POST['town']) && !empty($_POST['town']) && isset($_POST['latitude']) && !empty($_POST['latitude']) && isset($_POST['longitude']) && !empty($_POST['longitude'])) {
    $town = $_POST['town'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];

    updateCoords($town, $latitude, $longitude);
} else {
    $towns = getTowns();

    $start = 500;
    $inc = 100;

    foreach ($towns as $key => $town) {
        $town_name = $town[0];
        $country = $town[1];
        echo "<script>getCoords('$town_name', '$country');</script>\n";
        if ($key < $start or $key > $inc - 1) {    
            sleep(60);
            $start += $inc;
        }
    }
}

/**
 * Opens a database connection.
 */
function openDB() {
    // TODO change to your own database
    mysql_connect("mysql.anjoola.com","anjoola","pokemon");
    mysql_select_db("ashaforedu");
}                                                  

function getTowns() {
    openDB();
    $result = mysql_query("SELECT DISTINCT name, 'India' FROM town");

    $data = array();
    
    while(($row = mysql_fetch_row($result))) {
        $data[] = $row;
    }
    
    return $data;
}

function updateCoords($town_name, $latitude, $longitude) {
    openDB();
    $result = mysql_query("UPDATE town SET latitude = $latitude, longitude = $longitude WHERE name = '$town_name';");
}
