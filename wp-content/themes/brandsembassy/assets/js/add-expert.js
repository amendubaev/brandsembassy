$('#select-industries').on('select2:select', function(e) {
    var optionId = e.params.data.id;
    var optionText = e.params.data.text;
    $('.branches-custom').append('<div class="field-row field-row--branches" data-industry="' + optionId + '"><div class="field-row--title">Отрасли для ' + optionText + '</div><select class="field-selectable--added" required name="industries[' + optionId + '][]" data-parent="' + optionId + '" multiple="multiple"></div>');
    $('.field-row[data-industry="' + optionId + '"] .field-selectable--added').select2({
        tags: true
    });

    var $options = $('#select-branches > option[data-parent="' + optionId + '"').clone();
    $('.field-row[data-industry="' + optionId + '"] .field-selectable--added').append($options);
});

$('#select-industries').on('select2:unselect', function(e) {
    var optionId = e.params.data.id;
    $('.field-row--branches[data-industry="' + optionId + '"]').remove();
    $('.field-row[data-industry="' + optionId + '"] .field-selectable--added').select2('destroy');
});

$('.photo-type[name="photo_type"]').change(function () {
    if ($(this).val() == 'file') {
        $('input[name="photo_file"]').prop('required', true).show();
        $('input[name="photo_link"]').prop('required', false).hide();
    } else {
        $('input[name="photo_file"]').prop('required', false).hide();
        $('input[name="photo_link"]').prop('required', true).show();
    }
});

$('#select-countries').on('select2:select', function(e) {
    var optionId = e.params.data.id;
    var optionText = e.params.data.text;
    $('.cities-custom').append('<div class="field-row field-row--cities" data-country="' + optionId + '"><div class="field-row--title">Города для ' + optionText + '</div><select class="field-selectable--added" required name="countries[' + optionId + '][]" data-country="' + optionId + '" multiple="multiple"></div>');
    $('.field-row[data-country="' + optionId + '"] .field-selectable--added').select2({
        tags: true
    });

    var $options = $('#select-cities > option[data-country="' + optionId + '"').clone();
    $('.field-row[data-country="' + optionId + '"] .field-selectable--added').append($options);
});

$('#select-countries').on('select2:unselect', function(e) {
    var optionId = e.params.data.id;
    $('.field-row--cities[data-country="' + optionId + '"], .field-row--locations[data-country="' + optionId + '"]').remove();
    $('.field-row[data-country="' + optionId + '"] .field-selectable--added').select2('destroy');
});

$(document).on('select2:select', '.cities-custom', function (e) {
    var country = e.currentTarget.lastChild.dataset.country;
    var optionId = e.params.data.id;
    var optionText = e.params.data.text;
    $('.locations-custom').append('<div class="field-row field-row--locations" data-country="' + country + '" data-city="' + optionId + '"><div class="field-row--title">Локации для ' + optionText + '</div><select class="field-selectable--added" required name="locations[' + country + '][' + optionId + '][]" data-city="' + optionId + '" multiple="multiple"></div>');
    $('.field-row[data-city="' + optionId + '"] .field-selectable--added').select2({
        tags: true
    });

    var $options = $('#select-locations > option[data-city="' + optionId + '"').clone();
    $('.field-row[data-city="' + optionId + '"] .field-selectable--added').append($options);
});

$(document).on('select2:unselect', '.cities-custom', function(e) {
    var optionId = e.params.data.id;
    $('.field-row--locations[data-city="' + optionId + '"]').remove();
    $('.field-row[data-city="' + optionId + '"] .field-selectable--added').select2('destroy');
});