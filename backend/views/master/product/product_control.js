/* 
 * Create By Mujib Masyhudi <mujib.masyhudi@gmail.com>
 * Create at {date('now')}
 */

$(function() {
    $(this).on('click', '#uom_add', function() {
        var inputRow = $(this).parents("tr");
        var iUom = inputRow.find('.uom').val();
        var dUom = inputRow.find('.uom option:selected').text();
        var dIsi = inputRow.find('.isi').val();

        if (!validateUom(inputRow)) {
            return false;
        }

        var newRow = $('#uomTable tbody').find('.uom_template').clone().removeAttr('style').removeClass('uom_template');
        newRow.addClass('rowUom');
        newRow.find('.uom_code').html(dUom).end();
        $('<input>').attr({
            type: 'hidden',
            id: 'foo',
            name: 'foo[]',
            value: iUom
        }).appendTo(newRow);

        newRow.find('.uom_isi').html(dIsi).end();
        newRow.prependTo('#uomTable tbody');
        $('#uom_record').append('<br>Add ' + dUom + ' to product');

        doSerialize();
        return false;
    });

    //remove selected list            
    $(this).on('click', '.uom_remove', function() {
        var selectedRow = $(this).parents("tr");
        var dUom = selectedRow.find('td:nth-child(2)').html();
        $('#uom_record').append('<br>Remove ' + dUom + ' from product');
        selectedRow.remove();
        doSerialize();
        return false;
    });
});

var doSerialize = function() {
    $('#uomTable tbody').find('.rowUom').each(function(index) {
        $(this).find('td:nth-child(1)').html(index + 1);
//                $(this).find('.idpro').attr('name', 'DoDetail[' + index + '][id_product]');
//                $(this).find('.dqty').attr('name', 'DoDetail[' + index + '][qty]');
//                $(this).find('.duom').attr('name', 'DoDetail[' + index + '][id_uom]');
    });
};

var validateUom = function(drow) {
    var id_uom = drow.find('.uom').val();
    if (id_uom === '') {
        $('#uom_record').append('<br>Error add uom');
        return false;
    }
    return true;
};


