/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.css');

// commonjs
const flatpickr = require("flatpickr");
flatpickr('#reservation_form_reservationDate', {});

var $ = require('jquery');

console.log('Hello Webpack Encore! Edit me in assets/js/app.js');

var $collectionHolder;

// setup an "add a ticket" link
var $addTicketButton = $('<button type="button" class="add_ticket_link btn">Ajouter un ticket</button>');
var $newLinkLi = $('<li></li>').append($addTicketButton);

$(document).ready(function() {
    // Get the ul that holds the collection of tags
    $collectionHolder = $('ul.tickets');

    // add the "add a tag" anchor and li to the tags ul
    $collectionHolder.append($newLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find(':input').length);

    $addTicketButton.on('click', function(e) {
        // add a new tag form (see next code block)
        addTicketForm($collectionHolder, $newLinkLi);
    });
});

    function addTicketForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');

    // get the new index
    var index = $collectionHolder.data('index');

    var newForm = prototype;
   
    newForm = newForm.replace(/__name__/g, index);
    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormLi = $('<li></li>').append(newForm);
    $newLinkLi.before($newFormLi);
}
