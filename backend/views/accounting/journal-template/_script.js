/* 
 * Create By Mujib Masyhudi <mujib.masyhudi@gmail.com>
 * Create at {date('now')}
 */

$(function() {
    $('#dcode').autocomplete({
        minLength: 0,
        source: biz.coa_url,
        select: function(event, ui) {
            $('#did').val(ui.item.id);
            $('#dcode').val(ui.item.code);
            $('#dname').val(ui.item.name);
            $('#dbalance').focus();            
            return false;
        }
    }).autocomplete("instance")._renderItem = function(ul, item) {
        return $("<li>")
                .append("<a>" + item.code + "-" + item.name + "</a>")
                .appendTo(ul);
    };

    $('#dname').autocomplete({
        minLength: 0,
        source: biz.coa_url,
        select: function(event, ui) {
            $('#did').val(ui.item.id);
            $('#dcode').val(ui.item.code);
            $('#dname').val(ui.item.name);
            $('#dbalance').focus();            
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
        $row.find(':input[data-field="coa_id"]').val($('#did').val());      
        $row.find('span[data-field="ddk"]').text($('#dbalance').val());      
        $row.find(':input[data-field="idk"]').val($('#dbalance').val());
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


