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
                    data: { imageId: imageId},
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
    clickable: true
};
$(document).ready(function () {
    /* Select Categories */
    $(".category-clickable-button").click(function () {
        $(this).toggleClass('btn-primary btn-fill');
        // select the next element, which is the form
        var hiddenEl =  $(this).next();
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

    $("[id^=submit]").click(function(){
        var form=$(this).parents("form");
        form.attr('action', form.attr('action')+"?button="+$(this).attr('id')).submit();
    });

    $('#new-price').keydown(function(){
        setTimeout(function(){
            var val = $('#new-price').val();
            $(".suggestion-daily").html('<i>Daily price</i>: '+Math.round(val*0.02));
            $(".suggestion-weekly").html('<i>Weekly price</i>: '+Math.round(val*0.04));
            $(".suggestion-monthly").html('<i>Monthly price</i>: '+Math.round(val*0.05));
        },100);
    })
});

$(function() {
    var dr = $(".dropzone");
    dr.sortable({
        items:'.dz-preview',
        cursor: 'move',
        opacity: 0.5,
        containment: '.dropzone',
        distance: 20,
        update: function(event, ui) {
            var order = [];
            $('.dropzone').sortable().find('.dz-preview').each(function(o,i){
                order[o] = $(i).find('img').attr('alt');
            });
            $.ajax({
                type: 'POST',
                url: window.sortUrl,
                data: {order : order},
                success: function(data){
                    console.log(data);
                }
            });
        }
    });
    dr.disableSelection();
});