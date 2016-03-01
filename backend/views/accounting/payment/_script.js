
$('#input-invoice').autocomplete({
    minLength: 0,
    source: function (req, callback) {
        jQuery.get(biz.invoice_url, {
            term: req.term,
            type: $('#payment-type').val(),
            vendor: $('#payment-vendor_id').val()
        }, function (data) {
            callback(data);
        });
    },
    focus: function (event, ui) {
        $("#input-invoice").val(ui.item.name);
        return false;
    },
    select: function (event, ui) {
        $("#input-invoice").val('');

        if ($('#payment-type').val() == '') {
            $('#payment-type').val(ui.item.type);
        }
        if ($('#payment-vendor_id').val() == '') {
            $('#payment-vendor_id').val(ui.item.vendor_id);
            jQuery.get(biz.vendor_url, {id: ui.item.vendor_id}, function (data) {
                if (data[0]) {
                    $('#payment-vendor_name').val(data[0].name);
                }
            });
        }
        var sisa = ui.item.value - ui.item.paid;
        var $row = $('#detail-grid').mdmTabularInput('addRow');
        $row.find(':input[data-field="invoice_id"]').val(ui.item.id);
        $row.find('span[data-field="invoice"]').text(ui.item.number);
        $row.find('span[data-field="sisa"]').text(sisa);
        $row.find(':input[data-field="value"]').val(sisa).focus();

        return false;
    }
})
    .autocomplete("instance")._renderItem = function (ul, item) {
    return $("<li>")
        .append("<a>" + item.number + "<br>Sisa: " + (item.value - item.paid) + "</a>")
        .appendTo(ul);
};

$('#payment-vendor_name')
    .autocomplete({
        minLength: 1,
        source: biz.vendor_url,
        focus: function (event, ui) {
            $("#payment-vendor_name").val(ui.item.name);
            return false;
        },
        select: function (event, ui) {
            $("#payment-vendor_name").val(ui.item.name);
            $('#payment-vendor_id').val(ui.item.id);
            return false;
        }
    })
    .autocomplete("instance")._renderItem = function (ul, item) {
    return $("<li>")
        .append("<a>" + item.code + "<br>" + item.name + "</a>")
        .appendTo(ul);
};
