$(function () {
   $(document).on('click', '.repeater-items .repeater-item-remove', function () {
       $(this).closest('.repeater-item').remove();
   })
});