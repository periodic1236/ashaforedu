function displayDetails(id) {
    var projectName = "Project Name";
    var city = "Lucknow";
    var organization = "Organization";
    var orgURL = "http://";
    var purpose = "To make donuts";
    var orgDesc = "Make things!";
    var desc = "Very long description here! Blah blah asdf";
    var focus1 = "focus 1";
    var focus2 = "focus 2";
    var focus3 = "focus 3";
    
   
   // Head of the details table header
    document.write("<table class=\"table table-bordered table-striped\">\n");
    // Project name and link
    document.write("<thead><tr><th>");
    document.write("<h4><a href=\"http://www.ashanet.org/projects/project-view.php?p=" + id + "\">");
    document.write(projectName + "</a></h4>");
    // City
    document.write("<h6>" + city + "</h6></th></tr></thead>\n");
    // Start of details table body
    document.write("<tbody>\n");
    // Organization and website
    document.write("<tr><td><b><a href=\"" + orgURL + "\">" + organization + "</a></b>");
    document.write(" - " + orgDesc + "</td></tr>");
    // Project description
    document.write("<tr><td>" + desc + "<br><br>");
    // Focus
    document.write("<b>Focus:</b><ul>");
    if (focus1 != "")
        document.write("<li>" + focus1 + "</li>");
    if (focus2 != "")
        document.write("<li>" + focus2 + "</li>");
    if (focus3 != "")
        document.write("<li>" + focus3 + "</li>");
    document.write("</ul></td></tr>");
    document.write("</tbody>\n");
    
    document.write("</table>");
   /* name
    city
    chapter (int)
    focus 1, 2, 3
    orgnanization <website>, description, purpose
    short project description
 
    comments
    click for more info
}*/
}
