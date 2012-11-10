/**
* These are helper functions that call on the PHP functions in database_lib.php,
* which will do the population of the tables & displays by querying the database.
**/

/**
* Add all of the markers to the map
**/
function addAllMarkers(map) {
    $.getJSON('http://anjoola.com/asha/database_lib.php', { action: 'getAllProjects' }, function(data) {
            $.each(data['data'], function(index, value) {
                    getCoordinates(value[0], map);
            });
    });
}

/**
* Adds a marker to the map for a given project.
*/
function getCoordinates(name, map) {
    $.getJSON('http://anjoola.com/asha/database_lib.php', { action: 'getProjectCoordinates', name: name }, function(data) {
            var info = data['data'];
            if (info.length !== 0 && info[0].length !== 0) {
                addMarker(info[0][0], map, info[0][1], info[0][2]);
            }
    });
}

/**
* Loads all of the states from the database. Populates the table.
**/
function loadStates() {
    $.getJSON('http://anjoola.com/asha/database_lib.php', { type: 'state', action: 'displayAll' }, function(data) {
            $.each(data['data'], function(index, value) {
                    addState(value);
            });
    });
}

/**
* Loads all of the focuses from the database. Populates the table.
**/
function loadFocuses() {
    $.getJSON('http://anjoola.com/asha/database_lib.php', { type: 'focus', action: 'displayAll' },
        function(data) {
            $.each(data['data'], function(index, value) {
                    addFocus(value);
            });
        });
}

/**
* Loads all of the chapters from the database. Populates the table.
**/
function loadChapters() {
    $.getJSON('http://anjoola.com/asha/database_lib.php', {
            type: 'chapter',
            action: 'displayAll'
    },
    function(data) {
        $.each(data['data'], function(index, value) {
                addChapter(value);
        });
    });
}

/**
* Loads all of the projects filtered by either state, focus, or chapter and 
* populates the table. Can only filter by one value.
* 
* Parameters: selectedType - the type to filter by.
**/
function getProjects() {
    switch (selectedType) {
    case 'state':
        $.getJSON('http://anjoola.com/asha/database_lib.php', {
                type: 'state',value: selectedState[0],
                action: 'displayProjects'
        },
        function(data) {
            $.each(data['data'], function(index, value) {
                    addProject(value);
            });
        });
        break;
    case 'focus':
        $.getJSON('http://anjoola.com/asha/database_lib.php', {
                type: 'focus', value: selectedFocus[0],
                action: 'displayProjects'
        },
        function(data) {
            $.each(data['data'], function(index, value) {
                    addProject(value);
            });
        });
        break;
    case 'chapter':
        $.getJSON('http://anjoola.com/asha/database_lib.php', {
                type: 'chapter', value: selectedChapter[0],
                action: 'displayProjects' 
        }, function(data) {
            $.each(data['data'], function(index, value) {
                    addProject(value);
            });
        });
        break;
    }
}

/**
* Gets the listing for the project and display it. Does the main information 
* and the focuses separately.
**/
function getListing() {
    console.log(selectedProject);
    var string = "";
    
    if (selectedProject instanceof Array) {
        selectedProject = selectedProject[0];
    }
    
    $.getJSON('http://anjoola.com/asha/database_lib.php', {
            value: selectedProject,
            action: 'displayListing'
    },
    function(data) {
        var json = data['data'];
        string = generateListing(json['id'], json['name'], json['city'],
            json['org_webpage'], json['organization'], json['project_desc'],
            json['purpose']);
    });
    $.getJSON('http://anjoola.com/asha/database_lib.php', {
            value: selectedProject,
    action: 'getFocus' },
    function(data) {
        var json = data['data'];
        generateFocus(string, json['focus1'], json['focus2'], json['focus3']);
    });
}
