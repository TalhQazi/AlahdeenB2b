$('#cancel-btn, .close-popup-modal').on('click', function () {
    this.closest('.popup-modal').classList.add('hidden');
    $(this).closest('.popup-modal').find('form').trigger('reset');
});
