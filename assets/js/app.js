/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.css');

// Flatpickr
const flatpickr = require("flatpickr");
flatpickr('#reservation_form_reservationDate', {
    allowInput: true,
    enableTime: true,
    dateFormat: "Y-m-d H:i",
    minDate: "today",
    "disable": [
        function(date) {
            // return true to disable
            return (date.getDay() === 2 || date.getDay() === 7);
        }
    ],
    "locale": {
        "firstDayOfWeek": 1 // start week on Monday
    }
});

// JQuery
var $ = require('jquery');


var $collectionHolder;

// setup an "add a ticket" link
var $addTicketButton = $('<button type="button" class="add_ticket_link btn fullwidth btn-primary">Ajouter un ticket</button>');
var $newLinkLi = $('<span background-primary></span>').append($addTicketButton);

$(document).ready(function() {
    console.log("coucou");
    // Get the ul that holds the collection of tags
    $collectionHolder = $('div.tickets');

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
var $newFormLi = $('<li class="box"></li>').append(newForm);
$newLinkLi.before($newFormLi);
}

var stripe = Stripe('pk_test_KK2i0mkrYlpFPfBlE2n6CYEL');
var elements = stripe.elements();

var card = elements.create('card');
card.mount('#card-element');

var promise = stripe.createToken(card);
promise.then(function(result) {
    // result.token is the card token.
});

