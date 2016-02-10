/* 
 * Create By Mujib Masyhudi <mujib.masyhudi@gmail.com>
 * Create at {date('now')}
 */

$(function() {
    $('#dcode').autocomplete({
        minLength: 0,
        source: biz.gl_url,
        focus: function(event, ui) {
            $("#dname").val(ui.item.name);
            return false;
        },
        select: function(event, ui) {
            $('#did').val(ui.item.id);
            $('#dcode').val(ui.item.code);
            $('#dname').val(ui.item.name);
            $('#dbalance').val(ui.item.name);
            if (ui.item.normal_balance === 'D') {
                $('#ddebit').focus();
            } else {
                $('#dcredit').focus();
            }
            return false;
        }
    }).autocomplete("instance")._renderItem = function(ul, item) {
        return $("<li>")
                .append("<a>" + item.code + "-" + item.name + "</a>")
                .appendTo(ul);
    };

    $('#dname').autocomplete({
        minLength: 0,
        source: biz.gl_url,
        focus: function(event, ui) {
            $("#dname").val(ui.item.name);
            return false;
        },
        select: function(event, ui) {
            $('#did').val(ui.item.id);
            $('#dcode').val(ui.item.code);
            $('#dname').val(ui.item.name);
            $('#dbalance').val(ui.item.name);
            if (ui.item.normal_balance === 'D') {
                $('#ddebit').focus();
                $('#dcredit').css('background-color', 'whitesmoke');
                $('#ddebit').css('background-color', 'white');
            } else {
                $('#dcredit').focus();
                $('#ddebit').css('background-color', 'whitesmoke');
                $('#dcredit').css('background-color', 'white');
            }
            return false;
        }
    }).autocomplete("instance")._renderItem = function(ul, item) {
        return $("<li>")
                .append("<a>" + item.code + "-" + item.name + "</a>")
                .appendTo(ul);
    };

    $(this).on('click', '.btn-minus', function() {
        var selectedRow = $(this).parents("tr");
        selectedRow.remove();
        return false;
    });


    $(this).on('keydown', '#ddebit, #dcredit', function(e) {
        if (e.keyCode === 13) {
            var dElement = $(this).attr('id');
            if (dElement === 'ddebit') {
                $('#dcredit').val('');
            } else {
                $('#ddebit').val('');
            }
            $('#journal_add').click();
            return false;
        }
    });

    $(this).on('click', '#journal_add', function() {
        var $row = $('#detail-grid').mdmTabularInput('addRow');
        $row.find('span[data-field="coa_code"]').text($('#dcode').val());
        $row.find('span[data-field="coa_name"]').text($('#dname').val());
        $row.find('span[data-field="debit"]').text($('#ddebit').val()).parent('td').css('background-color', '#FAFAFA').css('border-right', '2px solid white');
        $row.find('span[data-field="credit"]').text($('#dcredit').val()).parent('td').css('background-color', '#FAFAFA');

        $row.find(':input[data-field="coa_id"]').val($('#did').val());
        $row.find(':input[data-field="idebit"]').val($('#ddebit').val());
        $row.find(':input[data-field="icredit"]').val($('#dcredit').val());
        $row.find(':input[data-field="btn-minus"]').addClass('btn-minus');
        clearInput();
    });
});

var clearInput = function() {
    $('#dcode').val('');
    $('#dname').val('');
    $('#ddebit').val('');
    $('#dcredit').val('');
    $('#dcode').focus();
};


