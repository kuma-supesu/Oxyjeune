const $newLinkLi = $('<li></li>');
const $container = $("ul.date");
var $data;

jQuery(document).ready(function() {
    let $collectionHolder = $container;
    $collectionHolder.append($newLinkLi);
    $collectionHolder.data('index', $collectionHolder.find(':input').length);
    let $collectionHeure = $container;
    $collectionHeure.append($newLinkLi);
    $collectionHeure.data('index', $collectionHeure.find(':input').length);

    $('[name="__planning__"]').on('click', function() {
        addTagForm($collectionHolder, $newLinkLi);
        $('[name="__heures__"]').on('click', function(event) {
            var $data = $(this).attr('data');
            addHeure($collectionHeure, $newLinkLi, $data);
            event.stopImmediatePropagation();
        });
    });
});

function addTagForm($collectionHolder, $newLinkLi) {
    let prototype = $collectionHolder.data('prototype');
    let index = $collectionHolder.data('index');
    let newForm = prototype.replace(/__journee__/g, index);
    $collectionHolder.data('index', index + 1);
    let $newFormLi = $('<ul id="date_' + index + '" class="date row"></ul>').append(newForm);
    $newFormLi.append('<button type="button" name="__heures__"  data="' + index + '" class="btn btn-secondary">Ajouter une plage horaire</button>');
    $newFormLi.append('<button type="button" class="remove-tag btn btn-danger">Supprimer la date</button>');
    $newLinkLi.before($newFormLi);
    $('.remove-tag').click(function () {
        $(this).parent().remove();
        return false;
    });
    $('.btn').click(function () {
        return false;
    });
}

function addHeure($collectionHeure, $newLinkLi, $data) {
    let prototype = $collectionHeure.data('prototype-h');
    let index = $collectionHeure.data('index');
    let newForm = prototype.replace(/__name__/g, index).replace(/__journee__/g, $data);
    $collectionHeure.data('index', index + 1);
    $('ul#date_' + $data).append(newForm);
    $('#planning_journees_' + $data + '_heures_' + index).append('<button type="button" class="remove-tag btn btn-warning">Supprimer la plage horaire</button>');
    $('.remove-tag').click(function() {
        $(this).parent().remove();
        return false;
    });
    $('.btn').click(function () {
        return false;
    });
}