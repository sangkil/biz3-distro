/* 
 * Create By Mujib Masyhudi <mujib.masyhudi@gmail.com>
 * Create at {date('now')}
 */

$(function() {
    $('#dname').autocomplete({
        minLength: 0,
        source: biz.tmplate_url,
        focus: function(event, ui) {
            $("#dname").val(ui.item.name);
            return false;
        },
        select: function(event, ui) {
            $('#did').val(ui.item.id);
            $('#dname').val(ui.item.name);
            $('#damount').focus();
            return false;
        }
    }).autocomplete("instance")._renderItem = function(ul, item) {
        return $("<li>")
                .append("<a>" + item.name + "</a>")
                .appendTo(ul);
    };

    $(this).on('click', '.btn-minus', function() {
        var selectedRow = $(this).parents("tr");
        selectedRow.remove();
        return false;
    });

    $(this).on('click', '#journal_add', function() {
        var $newRow = $('#table-templates tbody').find('.row-template').clone().removeClass('row-template');
        $newRow.show();
        $newRow.addClass('row-input');
        $newRow.find('td[data-field="iid"]').text($('#dname').val());

        $('<input>').attr({
            name: 'glTemplate[][iid]',
            type: 'hidden',
            value: $('#did').val(),
            class: 'iid'
        }).appendTo($newRow.find('td[data-field="iid"]'));

        $('<input>').attr({
            name: '',
            type: 'text',
            value: $('#damount').val(),
            class: 'iamount form-control'
        }).appendTo($newRow.find('td[data-field="iamount"]'));

        $newRow.find(':input[data-field="iamount"]').val($('#damount').val());

        $newRow.appendTo('#table-templates tbody');
        doSerialize('table-templates');
        clearInput();
        return false;
    });
});

var clearInput = function() {
    $('#did').val('');
    $('#dname').val('');
    $('#damount').val('');
    $('#dname').focus();
};

var doSerialize = function(dcomp_id) {
    var tbl = $('#' + dcomp_id + ' tbody');
    tbl.find('.row-input').each(function(index) {
        //$(this).find('td:nth-child(1)').html(index + 1);
        $(this).find('.iid').attr('name', 'glTemplate[' + index + '][id]');
        $(this).find('.iamount').attr('name', 'glTemplate[' + index + '][amount]');
    });
};


