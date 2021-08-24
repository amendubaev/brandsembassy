$('.search-categories--text').click(function() {
    $(this).parent().toggleClass('search-categories--active');
});

var firstSelected = null;
var placeholderText = $('.search-categories--text').text();
$('.search-list--checkbox').click(function (e) {
    
    var checkedValues = $('.search-list--checkbox:checked').map(function() {
        return $(this);
    }).get();
    var valuesLength = checkedValues.length;
    var currentClicked = $(e.currentTarget);
    
    if (!firstSelected) {
        firstSelected = currentClicked;
    }
    
    if (currentClicked.is(firstSelected) && !firstSelected.is(':checked')) {
        firstSelected = checkedValues[0];
    }
    
    if (valuesLength) {
        placeholderText = firstSelected.siblings('label').text();
        $('.search-categories--text').text(placeholderText);
        
        if (valuesLength > 1) {
            $('.search-categories--text').text(`${placeholderText}, +${valuesLength - 1}`);
        }
    } else {
        $('.search-categories--text').text(placeholderText);
        firstSelected = null;
    }
});