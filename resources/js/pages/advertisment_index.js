window.$ = window.jQuery = require('jquery');

$(".close-modal").click(function() {
    $(".modal").each(function(index,element){

        if(!$(element).hasClass('hidden')) {
            $(element).addClass('hidden')
        }
    });
});


$(document).on('click','.delete-advertisment',function(event){
    event.preventDefault();
    $('#delete-modal').removeClass('hidden');
    $('#modal-title').text($(this).attr('title'));
    $('#delete-modal form').attr('action',$(this).attr('href'));
});





