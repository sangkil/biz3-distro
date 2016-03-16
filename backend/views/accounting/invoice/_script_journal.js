/* 
 * Create By Mujib Masyhudi <mujib.masyhudi@gmail.com>
 * Create at {date('now')}
 */

$(function () {
    $('#dname').autocomplete({
        minLength: 0,
        source: biz.gl_url,
        focus: function (event, ui) {
            $("#dname").val(ui.item.name);
            return false;
        },
        select: function (event, ui) {
            var $row = $('#detail-grid-journal').mdmTabularInput('addRow');
            $row.find('span[data-field="coa_code"]').text(ui.item.code);
            $row.find('span[data-field="coa_name"]').text(ui.item.name);

            $row.find(':input[data-field="coa_id"]').val(ui.item.id);
            $row.find(':input[data-field="btn-minus"]').addClass('btn-minus');
            $row.find(':input[data-field="idebit"]').focus();

            clearInput();
            return false;
        }
    }).autocomplete("instance")._renderItem = function (ul, item) {
        return $("<li>")
            .append("<a>" + item.code + "-" + item.name + "</a>")
            .appendTo(ul);
    };

    $(this).on('click', '.btn-minus', function () {
        var selectedRow = $(this).parents("tr");
        selectedRow.remove();
        return false;
    });
});

$('#detail-grid-journal').on('keydown', ':input[data-field="idebit"]', function (e) {
    if (e.keyCode === 13) {
        var $row = $(this).closest('#detail-grid-journal > tr');        
        $('#dname').focus();
        return false;
    }
});

var clearInput = function () {
    $('#dname').val('');
};




