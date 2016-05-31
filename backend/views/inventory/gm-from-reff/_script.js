
$('#detail-grid').on('blur change', ':input[data-field="qty"]', function () {
    var $row = $(this).closest('#detail-grid > tr');
    var itemCost = $row.find(':input[data-field="cogs"]').val();
    var isi = $row.find(':input[data-field="isi"]').val();
    var qty = $(this).val();
    isi = isi ? isi : 1;

    $row.find('span[data-field="totalLine"]').text(itemCost * qty * isi);
});

$('#goodsmovement-vendor_name').autocomplete({
    minLength: 1,
    source: function (request, response) {
        var result = [];
        var limit = 10;
        var term = request.term.toLowerCase();
        $.each(masters.vendors, function () {
            var vendor = this;
            if (vendor.name.toLowerCase().indexOf(term) >= 0 || vendor.code.toLowerCase().indexOf(term) >= 0) {
                result.push(vendor);
                limit--;
                if (limit <= 0) {
                    return false;
                }
            }
        });
        response(result);
    },
    focus: function (event, ui) {
        $("#goodsmovement-vendor_name").val(ui.item.name);
        return false;
    },
    select: function (event, ui) {
        $("#goodsmovement-vendor_name").val(ui.item.name);
        $('#goodsmovement-vendor_id').val(ui.item.id);
        return false;
    }
})
        .autocomplete("instance")._renderItem = function (ul, item) {
    return $("<li>")
            .append("<a>" + item.code + "<br>" + item.name + "</a>")
            .appendTo(ul);
};