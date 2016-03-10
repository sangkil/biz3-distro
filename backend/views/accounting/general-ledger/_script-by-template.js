/* 
 * Create By Mujib Masyhudi <mujib.masyhudi@gmail.com>
 * Create at {date('now')}
 */

$('#inp-template').autocomplete({
    minLength: 0,
    source: biz.tmplate_url,
    focus: function (event, ui) {
        $("#inp-template").val(ui.item.name);
        return false;
    },
    select: function (event, ui) {
        $('#inp-template-id').val(ui.item.id);
        $('#inp-template').val(ui.item.name);
        $('#inp-amount').focus();
        return false;
    }
}).autocomplete("instance")._renderItem = function (ul, item) {
    return $("<li>")
        .append("<a>" + item.name + "</a>")
        .appendTo(ul);
};

$('#add-template').click(function () {
    var $row = $('#template-grid').mdmTabularInput('addRow');
    $row.find('[data-field="id"]').val($('#inp-template-id').val());
    $row.find('[data-field="name"]').text($('#inp-template').val());
    $row.find('[data-field="amount"]').val($('#inp-amount').val());

    $('#inp-template-id').val('');
    $('#inp-template').val('').focus();
    $('#inp-amount').val('');
});
