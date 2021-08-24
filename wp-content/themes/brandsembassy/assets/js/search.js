// Open search/filter popup
$('.filter-fields').click(function () {
    onLoadFilterPopup($(this).data('taxonomy'));
});

// Close search/filter popup
$('.popup-close').click(function () {
    $('.popup').removeClass('popup-active');
    $('body').removeClass('popup-visible');
    $('#childs div').remove();
});

// Get html after open popup for every taxonomy new data
function onLoadFilterPopup(taxonomy) {
    $.post({
        url: search.url,
        dataType: 'json',
        data: {
            action: 'open_popup',
            taxonomy: taxonomy,
            ids: getURLParameters(taxonomy + '_id')
        },
        success: function(data) {
            var parent = data.data.parent;

            $('#headerParentTerm')
                .text(parent.name)
                .append($('<span>', {
                    class: 'category-search--counter',
                    text: parent.count
                }));

            $('#headerChildTerm').text(parent.childName);
            $('#searchResult').html(parent.html);

            // Add selected data to filter column
            var filterResult = data.data.filterResult;
            $('#searchTerms').html(filterResult.html);
            $('#findParent').text(filterResult.parentCount).next('span').text(parent.name);
            $('#findChild').text(filterResult.childCount).next('span').text(parent.childName);

            $('#searchField').data('taxonomy', taxonomy);

            // check filtered terms
            checkExistingTerms();

            $('.popup-search').addClass('popup-active');
            $('body').addClass('popup-visible');
        }
    });
}

// Reset filter
$('.filter-reset').click(function () {
    var term = $(this).data('reset-taxonomy');
    var url = window.location.search;

    var regexp = new RegExp(term + "_id%5B%5D=\\d+&?", "g");
    var clearSearch = url.replace(regexp, '');
    var newUrl = window.location.origin + window.location.pathname + clearSearch;

    window.location.href = newUrl;
});

// Selected term ids (childs)
var checkedTermIds = [];

// If filter is enabled, add filtered term ids
function checkExistingTerms() {
    var existingFilterIds = [];

    $('.category-childrens--item input').each(function (index, el) {
        existingFilterIds.push($(el).val());
    });

    $('.category-find--childrens').each(function (index, el) {
        existingFilterIds.push($(el).data('search-parent-id'));
    });

    updateCheckedTermIds(existingFilterIds, 'add');
};

// Send ajax request and append childs terms on second column
function getAndAppendChildrens(taxonomy, parentId, parentName, checked = false, deleteFromResult = false, append = true) {
    parentId = typeof parentId === 'string' ? parseInt(parentId) : parentId;

    var data = {
        action: 'search',
        parent_id: parentId,
        taxonomy: taxonomy
    };

    $.post({
        url: search.url,
        data: data,
        success: function(data) {
            var html = $('<div>', {class: 'category-search--terms'});

            $(data).each(function(index, el) {
                if (checked) {
                    addTermToFilter(parentId, parentName, el.term_id, el.name, taxonomy);
                }

                if (deleteFromResult)  {
                    removeTermFromFilter(el.term_id)
                }

                if (append) {
                    appendChildren(html, taxonomy, el.term_id, el.name, checked, parentId, parentName);

                    $('#childs').html(html);
                    $('#childsAmount').html(data.length);
                }
            });
        }
    });
}

function appendChildren(htmlWrapper, taxonomy, termId, termName, checked = false, parentId = false, parentName = false) {
    var item = $('<div>', {class: 'search-term'}).appendTo(htmlWrapper);
    var args = {
        type: 'checkbox',
        name: taxonomy,
        checked: checked ? true : isChecked(termId),
        id: termId,
        class: 'search-term--input',
        value: termId,
        'data-taxonomy': taxonomy,
    };

    if (parentId) {
        args['data-parent-id'] = parentId;
        args['data-parent-name'] = parentName;
    }

    $('<input>', args).appendTo(item);

    $('<label>', {
        for: termId ,
        class: 'search-term--label',
        text: termName
    }).appendTo(item);

    return item;
}

// Get and append child items
$(document).on('click', '#searchResult div.search-term-parent', function(e) {
    // Next condition need for stop bubbling event after click on child input
    // stopPropagation for parent
    if ($(this).children('.search-term--input').is(':checked')) {
        return;
    }
    if (e.target.tagName !== 'DIV') {
        return;
    }

    var input = $(this).find('input');
    var parentId = input.val();
    var parentName = input.next('label').text();
    var taxonomy = input.data('taxonomy');

    getAndAppendChildrens(taxonomy, parentId, parentName);
});

// Get and append child items and select all
$(document).on('click', '#searchResult input.search-term--input', function(e) {
    var input = $(this);
    var parentId = input.val();
    var parentName = input.next('label').text();
    var taxonomy = input.data('taxonomy');

    if (this.checked) {
        getAndAppendChildrens(taxonomy, parentId, parentName, true);
    } else {
        // after uncheck parent need to delete from filter parent and all childs
        // uncheck all childs in 2th column
        getAndAppendChildrens(taxonomy, parentId, parentName, false, true);
    }
});


// Get child items and select all after search
$(document).on('click', '#parents input.search-term--input', function(e) {
    var input = $(this);
    var parentId = input.val();
    var parentName = input.next('label').text();
    var taxonomy = input.data('taxonomy');

    if (this.checked) {
        getAndAppendChildrens(taxonomy, parentId, parentName, true, false, false);
    } else {
        getAndAppendChildrens(taxonomy, parentId, parentName, false, true, false);
    }
});

var childsWrapper;
$('body').on('click', '#childs input[type="checkbox"]', function(e) {
    var target = $(e.target);
    var parentId = target.data('parent-id');
    var termId = parseInt(target.val());
    var parentName = target.data('parent-name');
    var termName = target.next('label').text();
    var taxonomy = target.data('taxonomy');

    if (this.checked) {
        addTermToFilter(parentId, parentName, termId, termName, taxonomy);
    } else {
        removeTermFromFilter(termId);
    }
});


// Add or remove ids from global termIds array
// @param ids array | Id's for action
// @param action string | May be 'add' or 'remove'
// @return array | Updates checkedTermIds
function updateCheckedTermIds(ids, action) {
    $.each(ids, function (index, id) {
        id = parseInt(id);

        if (action === 'add') {
            if ($.inArray(id, checkedTermIds) === -1) {
                checkedTermIds.push(id);

                $('#' + id).prop('checked', true);
            }
        } else if (action === 'remove') {
            if ($.inArray(id, checkedTermIds) !== -1) {
                checkedTermIds.splice(checkedTermIds.indexOf(id), 1);

                $('#' + id).prop('checked', false);
            }
        }
    });

    return checkedTermIds;
}

// Add checked terms to filter 3th column
// @param inputName string | Search input name for ajax request
function addTermToFilter(parentId, parentName, termId, termName, inputName) {
    termId = typeof termId === 'string' ? parseInt(termId) : termId;

    if ($.inArray(termId, checkedTermIds) !== -1) {
        return;
    }

    var searchParent = $('div').find('[data-search-parent-id=' + parentId + ']');
    var item;
    if (! searchParent.length) {
        var termWrapper = $('<div>', {
            class: 'category-find--term'
        }).appendTo('#searchTerms');

        var parent = $('<div>', {
            class: 'category-find--parent',
            text: parentName
        }).appendTo(termWrapper);

        childsWrapper = $('<div>', {
            class: 'category-find--childrens',
            'data-search-parent-id': parentId
        }).insertAfter(parent);
    }

    // update childsWrapper after activate checkbox
    childsWrapper = $('div').find('[data-search-parent-id=' + parentId + ']');

    item = $('<div>', {
        class: 'category-childrens--item',
    }).appendTo(childsWrapper);

    $('<span>', {text: termName}).appendTo(item);
    $('<input>', {
        type: 'hidden',
        name: inputName + '_id[]',
        value: termId
    }).appendTo(item);

    updateCheckedTermIds([termId, parentId], 'add');
}

// Remove checked terms from filter 3th column
// remove parent if term is last in list
// uncheck checkbox in all columns
function removeTermFromFilter(termId) {
    var uncheckedItem = $('.category-childrens--item :input[value='+ termId +']').closest('.category-childrens--item');
    var parentTermBlock = uncheckedItem.closest('.category-find--childrens');
    var termChildrens = parentTermBlock.children('div');

    if (termChildrens.length == 1) {
        var parentId = parentTermBlock.data('search-parent-id');
        $('#' + parentId).prop('checked', false);
        uncheckedItem.closest('.category-find--term').remove();

        updateCheckedTermIds([parentId, termId], 'remove');
    } else {
        // remove item from selected column
        uncheckedItem.remove();
    }

    // remove hidden input for search
    $('#searchInputs input[value="'+ termId +'"]').remove();

    updateCheckedTermIds([termId], 'remove');
}

// Show count selected parents and childrens categories
$("#searchTerms").bind("DOMSubtreeModified", function() {
    $('#findParent').text($('#searchTerms .category-find--parent').length);
    $('#findChild').text($('#searchTerms .category-find--childrens .category-childrens--item').length);
});

// Get html wrapper with count founded elements after search input
function getHtmlWrapperForFilterItems(item, itemsResultName, id) {
    var searchResultHtml = $('<div>', {
        id: id,
        class: 'category-search-result--items'
    });

    var itemsCount = 0;
    if ($.type(item) !== "undefined") {
        itemsCount = item.length;

        $('<span>', {
            class: 'category-search-result--title',
            text: itemsCount + ' ' + itemsResultName
        }).appendTo(searchResultHtml);
    }

    return {
        html: searchResultHtml,
        count: itemsCount
    };
}

// Search field
var searchColumns, searchColumnsHeader;
var searchState = false;
var request = null;
var minLength = 2;
var inputLength;
$('#searchField').on('input', function (e) {
    var searchText = $(this).val();
    var target = $(e.target);

    inputLength = searchText.length;

    // Detach columns with checkboxes and save columns state
    if (! searchState) {
        searchColumnsHeader = $('#filterColumnsHeader').clone(true, true);
        searchColumns = $('#filterColumns').clone(true, true);

        searchState = true;
    }

    // send request if input chars more then minLength
    if (inputLength >= minLength) {
        // block multiple request if user input text
        if (request != null) request.abort();

        var taxonomy = target.data('taxonomy');
        var data = {
            action: 'search',
            s: searchText,
            taxonomy: taxonomy
        };

        var searchName = {};
        switch (taxonomy) {
            case 'industries':
                searchName.parent = 'индустрии';
                searchName.child = 'отрасли';
            break;
            case 'locations':
                searchName.parent = 'страны';
                searchName.child = 'города';
            break;
            case 'patterns':
                searchName.parent = 'патерны';
                searchName.child = 'мегатренды';
            break;
            default:
                searchName.parent = searchName.child = 'результат';
            break;
        }

        request = $.post({
            url: search.url,
            data: data,
            success: function (data) {
                if (inputLength < minLength) {
                    returnColumns(false);

                    return false;
                }

                var parentItems = getHtmlWrapperForFilterItems(data['parent'], searchName.parent, 'parents');
                $(data['parent']).each(function (index, el) {
                    appendChildren(parentItems.html, taxonomy, el.term_id, el.name);
                });

                var childItems = getHtmlWrapperForFilterItems(data['child'], searchName.child, 'childs');
                $(data['child']).each(function (index, el) {
                    appendChildren(childItems.html, taxonomy, el.term_id, el.name, false, el.parent, el.parent_name);
                });

                $('#filterColumns')
                    .html(parentItems.html)
                    .append(childItems.html)
                    .removeClass('row')
                    .addClass('category-search--terms flex-row');

                var searchResultHeaderContainer = $('<div>', {class: 'col-md-6'});
                var searchResultHeaderTitle = $('<div>', {class: 'category-search--title'}).appendTo(searchResultHeaderContainer);
                var searchResultHeaderCount = $('<span>', {
                    class: 'category-search--text',
                    text: 'Результаты поиска'
                }).appendTo(searchResultHeaderTitle);

                $('<span>', {
                    class: 'category-search--counter',
                    text: parentItems.count + childItems.count
                }).appendTo(searchResultHeaderCount);

                var clearSearchBlock = $('<div>', {class: 'col-md-6'});

                $('<button>', {
                    id: 'clearSearch',
                    type: 'button',
                    class: 'category-search--clear-button',
                    text: 'Сбросить поиск',
                }).appendTo(clearSearchBlock);

                $('#filterColumnsHeader').html(searchResultHeaderContainer).append(clearSearchBlock);
            }
        });
    } else {
        if (searchState) {
            returnColumns(false);

            return false;
        }
    }
});

// Insert detached columns after click clear search button
$('body').on('click', '#clearSearch', returnColumns);

// Get columns state before detach and added checked terms
function returnColumns(clearInput = true) {
    if (clearInput) {
        $('#searchField').val('');
    }

    $('#filterColumnsHeader').replaceWith(searchColumnsHeader);
    $('#filterColumns').replaceWith(searchColumns).removeClass('flex-row').addClass('row');

    $.each(checkedTermIds, function (index, el) {
        $('#' + el).prop('checked', true);
    });

    searchState = false;
}

// Verify if element isset in global termIds array
function isChecked(id) {
    var isChecked = false;

    if ($.inArray(id, checkedTermIds) !== -1) {
        isChecked = true;
    }

    return isChecked;
}

// Load more posts with ajax
var page = 2;
var postsCount = parseInt($('.posts-result--counter').text());
$('#loadMore').click(function () {
    var data = {
        action: 'load_more_posts',
        type: $(this).data('type'),
        page: page
    };

    $.each($('#searchInputs input'), function(i, el) {
        var taxonomy = $(el).attr('name').replace('[]', '');
        var id = $(el).val();

        if (data[taxonomy] === undefined) {
            data[taxonomy] = [];
        }

        data[taxonomy].push(id);
    });

    $.get(search.url, data, function(data) {
        $('.posts .row').append(data);

        if (postsCount === $('.card-item').length) {
            $('#loadMore').hide();
        }

        page++;
    });
});

// Mobile search
if ($(window).width() < 767) {
    $(document).on('click', '.search-term-parent', function () {
        if(!$(this).is(':checked')) {
            var currentTerm = $(this).children('.search-term--input').val();
            $(this).parent('#searchResult').css('display', 'none');
            $('#childs').addClass('childs-active');
            $('#filterColumnsHeader .category-search--title').css('display', 'flex');
        }
    });
    $(document).on('click', '#filterColumnsHeader', function () {
        $('#searchResult').css('display', 'block');
        $('#childs').removeClass('childs-active');
        $('#filterColumnsHeader .category-search--title').css('display', 'none');
    });
}