Dropzone.options.dropzoneForm = {
    init: function () {
        var myDropzone = this;
        $(document).ready(function () {
            $.each(window.preloadImages, function (key, value) { //loop through it
                var mockFile = {name: value.name, size: value.size}; // here we get the file name and size as response
                // Call the default addedfile event handler
                myDropzone.emit("addedfile", mockFile);

                // And optionally show the thumbnail of the file:
                myDropzone.emit("thumbnail", mockFile, mockFile.name);

            });
            $(".dz-remove").on("click", function (e) {
                e.preventDefault();
                e.stopPropagation();

                var imageId = $(this).parent().find(".dz-filename > span").text();
                $.ajax({
                    url: window.deleteUrl,
                    data: {imageId: imageId},
                    type: 'POST',
                    success: function (data) {
                        if (data.NotificationType === "Error") {
                            toastr.error(data.Message);
                        } else {
                            toastr.success(data.Message);
                        }
                    },
                    error: function (data) {
                        toastr.error(data.Message);
                    }
                })

            });
        });
    },
    url: window.uploadUrl,
    addRemoveLinks: true,
    clickable: true,
    dictDefaultMessage: window.dictDefaultMessage,
    dictFallbackMessage: window.dictFallbackMessage,
    dictInvalidFileType: window.dictInvalidFileType,
    dictFileTooBig: window.dictFileTooBig
};
$(document).ready(function () {
    /* Select Categories */
    $(".category-clickable-button").click(function () {
        $(this).toggleClass('btn-primary btn-fill');
        // select the next element, which is the form
        var hiddenEl = $(this).next();
        var currentVal = hiddenEl.val();
        hiddenEl.val(currentVal == 0 ? 1 : 0);
    });

    // select all selected categories
    $("[id^=edit-item-categories-]").each(function () {
        // get the id
        var myRegexp = /(\d+)/g; // Numbers dash Numbers
        var id = myRegexp.exec(this.id)[0];
        if (this.value == 1) {
            $("[data-id=" + id + "]").toggleClass('btn-primary btn-fill');
        }
    });

    $("[id^=submit]").click(function () {
        var form = $(this).parents("form");
        var newLink = form.attr('action').replace('?button=submit-save', '');
        newLink.replace('?button=submit-preview', '');
        newLink.replace('?button=submit-publish', '');
        form.attr('action', newLink + "?button=" + $(this).attr('id')).submit();
    });

    $('#new-price').keydown(function () {
        setTimeout(function () {
            var val = $('#new-price').val();
            $(".suggestion-daily").html('<i>' + i18n.daily_price + '</i>: ' + Math.round(val * 0.01));
            $(".suggestion-weekly").html('<i>' + i18n.weekly_price + '</i>: ' + Math.round(val * 0.03));
            $(".suggestion-monthly").html('<i>' + i18n.monthly_price + '</i>: ' + Math.round(val * 0.06));
        }, 100);
    })
});

$(function () {
    var dr = $(".dropzone");
    dr.sortable({
        items: '.dz-preview',
        cursor: 'move',
        opacity: 0.5,
        containment: '.dropzone',
        distance: 20,
        update: function (event, ui) {
            var order = [];
            $('.dropzone').sortable().find('.dz-preview').each(function (o, i) {
                order[o] = $(i).find('img').attr('alt');
            });
            $.ajax({
                type: 'POST',
                url: window.sortUrl,
                data: {order: order},
                success: function (data) {
                    console.log(data);
                }
            });
        }
    });
    dr.disableSelection();
});

$(function () {
    var timer = setInterval(function () {
        if ($(window).attr('autocomplete-item-create') !== undefined) {
            var autocomplete = $(window).attr('autocomplete-item-create');
            autocomplete.addListener('place_changed', function () {
                var place = autocomplete.getPlace();
                var streetAddress = {};
                $.each(place['address_components'], function () {
                    var address = this;
                    var name = address['long_name'];
                    $.each(address['types'], function () {
                        var type = this;
                        if (type == 'postal_code') {
                            $('#location-form-zip_code').val(name);
                        }
                        if (type == 'locality') {
                            $('#location-form-city').val(name);
                        }
                        if (type == 'route') {
                            streetAddress['route'] = name;
                        }
                        if (type == 'street_number') {
                            streetAddress['street_number'] = name;
                        }
                    });
                });
                if (streetAddress['route'] !== undefined && streetAddress['street_number'] !== undefined) {
                    $('.location-input').val(streetAddress['route'] + ' ' + streetAddress['street_number']);
                }
            });
            clearInterval(timer);
        }
    }, 500);
});