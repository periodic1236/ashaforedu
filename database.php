asdf
<?
echo "<select name=\"carrier\" id=\"carrier\">\n";
    echo "<option value=\"\">All</option>\n";
 // Connect to the database
    mysql_connect("mysql.anjoola.com","anjoola","pokemon");
    mysql_select_db("ashaforedu");
    $result = mysql_query("SELECT DISTINCT name FROM focus ORDER BY name ASC");
    
    // Add each carrier to the drop-down menu
    while($arrayRow = mysql_fetch_assoc($result)) {
        $carrier = $arrayRow["name"];
        if ($carrier == $_POST['name'])
            echo "<option value=\"$carrier\" selected=\"selected\">$carrier</option>\n";
        else
            echo "<option value=\"$carrier\">$carrier</option>\n";
    }
    echo "</select>&nbsp;&nbsp;&nbsp;";
    ?>
