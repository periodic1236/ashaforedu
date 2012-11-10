<?php
// Handler for all the types of functions callable from AJAX calls
if (isset($_GET['action']) && !empty($_GET['action'])) {
    $action = $_GET['action'];
    switch ($action) {
    case 'displayAll' : displayAll($_GET['type']); break;
    case 'displayProjects' : displayProjects($_GET['type'], $_GET['value']);
        break;
    case 'displayListing' : displayListing($_GET['value']); break;
    case 'getFocus' : getFocus($_GET['value']); break;
    case 'getAllProjects' : getAllProjects(); break;
    case 'getProjectCoordinates' : getProjectCoordinates($_GET['name']);
        break;
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

/**
* Gets all of the data for all the filters, such as state, focus, and chapter
**/
function displayAll($filterType) {
    openDB();

    // Get the table to retrieve information from
    $tableName = "state";
    switch ($filterType) {
    case "state": $tableName = "state"; break;
    case "focus": $tableName = "focus"; break;
    case "chapter": $tableName = "chapter"; break;
    }
    
    // Get the project names for this filter
    $result = mysql_query("SELECT DISTINCT name FROM " . $tableName .
        " ORDER BY name ASC");

    // Fetch all results into a JSON array for easy use by the AJAX call
    $data = array();
    while(($row = mysql_fetch_row($result))) {
        $data[] = $row;
    }
    echo json_encode(array('data' => $data));
}

/**
 * Displays all projects for a specific filter and value of that filter
 **/
function displayProjects($filterType, $value) {
    openDB();
    $result = "";
  
    // Get the table to retrieve information from with a specific WHERE clause
    $whereClause = "";
    
    switch ($filterType) {
    case "state":
        $whereClause = "state_id = (SELECT id FROM state WHERE state.name = '" .
            $value . "')";
        break;
    case "focus":
        $whereClause = "(SELECT id FROM focus WHERE focus.name = '" . $value .
            "') IN (focus1, focus2, focus3)";
        break;
    case "chapter":
        $whereClause = "current_chapter = (SELECT id FROM chapter WHERE chapter.name = '"
            . $value . "')"; break;
    }

    // Get only the ongoing projects.
    $ongoing_status_id = 1;
    $result = mysql_query("SELECT DISTINCT project.name FROM project WHERE " . $whereClause . " AND status_id = " . $ongoing_status_id);

    // Output into JSON array 
    $data = array();
    while(($row = mysql_fetch_row($result))) {
        $data[] = $row;
    }
    echo json_encode(array('data' => $data));
}

/**
 * Retrieve all of the information needed for the project listing
 */
function displayListing($project) {
    openDB();
    $result = mysql_query("SELECT id, name, city, organization, org_webpage, purpose,
        project_desc FROM project WHERE name = '" . $project . "'");
    $result = mysql_fetch_array($result);
    echo json_encode(array('data' => $result));
}

function getFocus($project) {
    openDB();
    $results = array();
    
    // Focus 1
    $result = mysql_query("SELECT DISTINCT focus.name FROM focus, project WHERE focus.id = project.focus1 AND project.name = '" . $project . "'");
    $result = mysql_fetch_row($result);
    
    $results[] = $result;
    
    // Focus 2
    $result = mysql_query("SELECT DISTINCT focus.name FROM focus, project WHERE focus.id = project.focus2 AND project.name = '" . $project . "'");
    $result = mysql_fetch_row($result);
    
    $results[] = $result;
    
    // Focus 3
    $result = mysql_query("SELECT DISTINCT focus.name FROM focus, project WHERE focus.id = project.focus3 AND project.name = '" . $project . "'");
    $result = mysql_fetch_row($result);
    
    $results[] = $result;
    
    echo json_encode(array('data' => $results));
}

function getAllProjects() {
    openDB();
    $result = "";
    
    // Get only the ongoing projects.
    $ongoing_status_id = 1;
    $result = mysql_query("SELECT DISTINCT project.name FROM project WHERE status_id = " . $ongoing_status_id);
    $data = array();
    
    while(($row = mysql_fetch_row($result))) {
        $data[] = $row;
    }
    
    echo json_encode(array('data' => $data));
}

function getProjectCoordinates($name) {
    openDB();
    $result = "";
    
    // Get only the ongoing projects.
    $ongoing_status_id = 1;
    $result = mysql_query("SELECT project.name, district.latitude, district.longitude FROM project JOIN district ON (project.district_id = district.id) WHERE project.name = '" . $name . "' AND status_id = " . $ongoing_status_id);
    $data = array();
    
    while(($row = mysql_fetch_row($result))) {
        $data[] = $row;
    }
    
    echo json_encode(array('data' => $data));
}
