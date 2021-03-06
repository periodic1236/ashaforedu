/**
* Helper file for displaying the HTML for all of the areas. Gets output from conn-helper.js and
* manipulates it for cooler display.
**/

/** The state that is selected **/
var selectedState = '';
/** The focus that is selected **/
var selectedFocus = '';
/** The chapter that is selected **/
var selectedChapter = '';
/** The type of filter selected, can be state, focus, or chapter **/
var selectedType = '';
/** The project that is selected **/
var selectedProject = '';

/**
* Adds a listing for a state in the output
**/
function addState(name) {
    $('#states-list').append('<li><a href="#">' + name + '</a></li>');
    
    var dom = $('#states-list a[href="#"]:contains("' + name + '")');
    // Handler for when a state is clicked on. Will find projects in that state.
    dom.click(function() {
        selectedState = name;
        selectedType = 'state';
        clearProjects();
        getProjects();
        

        $('#projects-state').text('For ' + name);

        // Focus on the projects tab once a state has been selected
        $('#tabs a[href="#projects"]').tab('show');
    });
}

/**
* Clear the listing for the states
**/
function clearStates() {
    $('#states-list').empty();
}

/**
* Adds a listing for the focuses
**/
function addFocus(name) {
    $('#focuses-list').append('<li><a href="#">' + name + '</a></li>');
    
    var dom = $('#focuses-list a[href="#"]:contains("' + name + '")');
    // Handler for when a focus is selected. Will find projects with that focus.
    dom.click(function() {
        selectedFocus = name;
        selectedType = 'focus';
        clearProjects();
        getProjects();
        // Focus on the projects tab once a focus has been selected
        $('#tabs a[href="#projects"]').tab('show');
    });
}

/**
* Clear the listing for focuses
**/
function clearFocuses() {
    $('#focuses-list').empty();
}

/**
* Add a listing for the chapters
**/
function addChapter(name) {
    $('#chapters-list').append('<li><a href="#">' + name + '</a></li>');
    
    var dom = $('#chapters-list a[href="#"]:contains("' + name + '")');
    // Handler for when a chapter is selected. Will find chapters with that chapter
    dom.click(function() {
        selectedChapter = name;
        selectedType = 'chapter';
        clearProjects();
        getProjects();
        // Focus on the projects tab once a chapter has been selected
        $('#tabs a[href="#projects"]').tab('show');
    });
}

/**
 * Clear all the listings for the chapter
 **/
function clearChapters() {
    $('#chapters-list').empty();
}

/**
 * Add a project to the list of projects
 **/
function addProject(name) {
    $('#projects-list').append('<li><a href="#">' + name + '</a></li>');
    var dom = $('#projects-list a[href="#"]:contains("' + name + '")');
    dom.click(function() {
            selectedProject = name;
            getListing();
            $('#tabs a[href="#details"]').tab('show'); // Select tab by name
    });
}

/**
 * Clear all the projects from the list of projects
 **/
function clearProjects() {
    $('#projects-list').empty();
}

/**
 * Checks to see if a string is valid. If not, don't output it
 **/
function valid(text) {
    return text != "" && text && text != "undefined";
}

/**
* Generate the listing for a specific project
**/
function generateListing(id, name, city, org_webpage, organization, project_desc, purpose) {
    $('#details').empty();
    var string = "";
    
    // Head of the details table
    string += '<table class="table table-bordered table-striped">';
    // Project name and link
    string += '<thead><tr><th>';
    string += '<h4><a href="http://www.ashanet.org/projects/project-view.php?p=' + id + '" target="blank">';
    string += name + '</a></h4>';
    // City
    if (valid(city))
        string += '<h6>' + city + '</h6></th></tr></thead>';

    // Start of details table body
    string += '<tbody><tr><td>';
    // Organization and website
    if (valid(org_webpage) && org_webpage != "http://")
        string += '<b><a href="' + org_webpage + '">' + organization + '</a></b>';
    // Organization purpose
    if (valid(purpose))
        string += ' - ' + purpose;
    string += '</td></tr>';
    // Project description
    if (valid(project_desc))
        string += '<tr><td>' + project_desc;
    else
        string += '<tr><td>There is no data for this project.';
    return string;
}

/**
* Generates the focuses for a specific project
**/
function generateFocus(string, focus1, focus2, focus3) {
    // Makes sure all of them are valid
    if (valid(focus1)) {
        string += '<br><br><b>Focus:</b><ul>';
        string += '<li>' + focus1 + '</a></li>';
    }
    if (valid(focus2))
        string += '<li>' + focus2 + '</a></li>';
    if (valid(focus3))
        string += '<li>' + focus3 + '</a></li>';
    string += '</ul></td></tr></tbody></table>';
    $('#details').append(string);
}
