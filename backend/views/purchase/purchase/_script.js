
$('#input-product').autocomplete({
    minLength: 0,
    source: biz.product_url,
    focus: function (event, ui) {
        $("#input-product").val(ui.item.name);
        return false;
    },
    select: function (event, ui) {
        $("#input-product").val('');

        var $row = $('#detail-grid').mdmTabularInput('addRow');
        $row.find(':input[data-field="product_id"]').val(ui.item.id);
        $row.find('span[data-field="product"]').text(ui.item.name);
        $row.find(':input[data-field="qty"]').focus();
        
        return false;
    }
})
    .autocomplete("instance")._renderItem = function (ul, item) {
    return $("<li>")
        .append("<a>" + item.code + "<br>" + item.name + "</a>")
        .appendTo(ul);
};

$('#purchase-vendor_name')
    .autocomplete({
    minLength: 1,
    source: biz.vendor_url,
    focus: function (event, ui) {
        $("#purchase-vendor_name").val(ui.item.name);
        return false;
    },
    select: function (event, ui) {
        $("#purchase-vendor_name").val(ui.item.name);
        $('#purchase-vendor_id').val(ui.item.id);
        return false;
    }
})
    .autocomplete("instance")._renderItem = function (ul, item) {
    return $("<li>")
        .append("<a>" + item.code + "<br>" + item.name + "</a>")
        .appendTo(ul);
};