/* 
 * Create By Mujib Masyhudi <mujib.masyhudi@gmail.com>
 * Create at {date('now')}
 */

$(function() {
    $(this).on('click', '#bcode_add', function() {
        var inputRow = $(this).parents("tr");
        var iBarcode = inputRow.find('.tbarcode');
//        if (!validateUom(inputRow)) {
//            return false;
//        }

        var newRow = $('#bcodeTable tbody').find('.bcode_template').clone().removeAttr('style').removeClass('bcode_template');
        newRow.addClass('rowBcode');
        newRow.find('.bcode_barcode').html(iBarcode.val()).end();

        $('<input>').attr({
            type: 'hidden',
            value: iBarcode.val(),
            class: 'barcode'
        }).appendTo(newRow);

        newRow.prependTo('#bcodeTable tbody');
        $('#bcode_record').append('<br>Add barcode ' + iBarcode.val() + ' to product');
        iBarcode.val('');

        doSerialize('bcodeTable');
        return false;
    });
    
    //remove selected list            
    $(this).on('click', '.bcode_remove', function() {
        var selectedRow = $(this).parents("tr");
        var dBcode = selectedRow.find('td:nth-child(2)').html();
        $('#bcode_record').append('<br>Remove barcode ' + dBcode + ' from product');
        selectedRow.remove();
        doSerialize('bcodeTable');
        return false;
    });
    
    $(this).on('click', '#uom_add', function() {
        var inputRow = $(this).parents("tr");
        var iUom = inputRow.find('.uom');
        var dUom = inputRow.find('.uom option:selected').text();
        var dIsi = inputRow.find('.isi');

        if (!validateUom(inputRow)) {
            return false;
        }

        var newRow = $('#uomTable tbody').find('.uom_template').clone().removeAttr('style').removeClass('uom_template');
        newRow.addClass('rowUom');
        newRow.find('.uom_code').html(dUom).end();

        $('<input>').attr({
            type: 'hidden',
            value: iUom.val(),
            class: 'id_uom'
        }).appendTo(newRow);

        $('<input>').attr({
            type: 'hidden',
            value: dIsi.val(),
            class: 'isi'
        }).appendTo(newRow);

        newRow.find('.uom_isi').html(dIsi.val()).end();
        newRow.prependTo('#uomTable tbody');
        $('#uom_record').append('<br>Add ' + dUom + ' to product, isi =' + dIsi.val());
        iUom.val('');
        dIsi.val('');

        doSerialize('uomTable');
        return false;
    });

    //remove selected list            
    $(this).on('click', '.uom_remove', function() {
        var selectedRow = $(this).parents("tr");
        var dUom = selectedRow.find('td:nth-child(2)').html();
        $('#uom_record').append('<br>Remove ' + dUom + ' from product');
        selectedRow.remove();
        doSerialize('uomTable');
        return false;
    });
});

var doSerialize = function(dcomp_id) {
    var tbl = $('#' + dcomp_id + ' tbody');
    if (dcomp_id === 'uomTable') {
        tbl.find('.rowUom').each(function(index) {
            $(this).find('td:nth-child(1)').html(index + 1);
            $(this).find('.id_uom').attr('name', 'prodUom[' + index + '][id_uom]');
            $(this).find('.isi').attr('name', 'prodUom[' + index + '][isi]');
        });
    } else if (dcomp_id === 'bcodeTable') {
        tbl.find('.rowBcode').each(function(index) {
            $(this).find('td:nth-child(1)').html(index + 1);
            $(this).find('.barcode').attr('name', 'prodBcode[' + index + '][barcode]');
        });
    }
};

var validateUom = function(drow) {
    var id_uom = drow.find('.uom').val();
    var isi = drow.find('.isi').val();
    if (id_uom === '') {
        alert('No uom selected');
        $('#uom_record').append('<br>Error: no uom selected ');
        return false;
    }
    if (isi === '') {
        alert('Isi can\'t null');
        $('#uom_record').append('<br>Error: Isi can\'t null');
        return false;
    }
    return true;
};


