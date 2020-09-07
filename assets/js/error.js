$(document).ready(function() {
    if (getUrlParameter("e") === "ver")
        $(".err").text("URL already verified!");
    else if (getUrlParameter("e") === "vid")
        $(".err").text("Invalid ID!");
    else if (getUrlParameter("e") === "db")
        $(".err").text("Error occured during connection to the database!");
    else if (getUrlParameter("e") === "url")
        $(".err").text("Invalid URL!");
    else if (getUrlParameter("e") === "cooldown")
        $(".err").text("You already registered a domain in 5 minutes. Wait a while.");
    else
        location.href = "/index.html";
});

function home() {
    location.href = "/index.html";
}

function getUrlParameter(name) {
    name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
    var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
    var results = regex.exec(location.search);
    return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
};