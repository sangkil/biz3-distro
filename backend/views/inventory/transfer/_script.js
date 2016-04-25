function selectProduct(item) {
    $("#input-product").val('');

    var $row = $('#detail-grid').mdmTabularInput('addRow');
    var itemPrice = item.price;

    $row.find(':input[data-field="product_id"]').val(item.id);
    $row.find('span[data-field="product"]').text(item.name);
    $row.find(':input[data-field="qty"]').focus();

    $row.find(':input[data-field="price"]').val(itemPrice);

    $row.find('[data-field="uom_id"] > option').each(function () {
        var $op = $(this);
        if (item.uoms[$op.val()]) {
            $op.attr('data-isi', item.uoms[$op.val()].isi);
        } else {
            $op.remove();
        }
    });
}

$('#detail-grid').on('keypress', ':input', function (e) {
    if (e.which == 13) {
        $("#input-product").focus();
        calculate();
        return false;
    }
});

$('#detail-grid').on('blur', ':input[data-field="qty"]', function () {
    var $row = $(this).closest('#detail-grid > tr');
    var itemPrice = $row.find(':input[data-field="price"]').val();
    $row.find('span[data-field="totalLine"]').text(itemPrice * $row.find(':input[data-field="qty"]').val());
});

$('#detail-grid').on('initRow', function (e, $row) {
    var id = $row.find('[data-field="product_id"]').val() + '';
    var product = masters.products[id];
    if (product) {
        $row.find('[data-field="uom_id"] > option').each(function () {
            var $op = $(this);
            if (product.uoms[$op.val()]) {
                $op.attr('data-isi', product.uoms[$op.val()].isi);
            } else {
                $op.remove();
            }
        });
    }
});

$('#input-product').autocomplete({
    minLength: 0,
    source: function (request, response) {
        var result = [];
        var limit = 10;
        var term = request.term.toLowerCase();
        $.each(masters.products, function () {
            var product = this;
            if (product.name.toLowerCase().indexOf(term) >= 0 || product.code.toLowerCase().indexOf(term) >= 0) {
                result.push(product);
                limit--;
                if (limit <= 0) {
                    return false;
                }
            }
        });
        response(result);
    },
    focus: function (event, ui) {
        $("#input-product").val(ui.item.name);
        return false;
    },
    select: function (event, ui) {
        selectProduct(ui.item);
        return false;
    }
})
    .autocomplete("instance")._renderItem = function (ul, item) {
    return $("<li>")
        .append("<a>" + item.code + "<br>" + item.name + "</a>")
        .appendTo(ul);
};

$('#transfer-vendor_name')
    .autocomplete({
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
            $("#transfer-vendor_name").val(ui.item.name);
            return false;
        },
        select: function (event, ui) {
            $("#transfer-vendor_name").val(ui.item.name);
            $('#transfer-vendor_id').val(ui.item.id);
            return false;
        }
    })
    .autocomplete("instance")._renderItem = function (ul, item) {
    return $("<li>")
        .append("<a>" + item.code + "<br>" + item.name + "</a>")
        .appendTo(ul);
};

$('#detail-grid').on('change', function () {
    calculate();
});

$('#detail-grid').on('change', ':input', function () {
    calculate();
});

function calculate() {
    var total_transfer = 0.0;
    var total_qty = 0.0;
    $('#detail-grid > tr').each(function () {
        var $th = $(this);
        var isi = $th.find('[data-field="uom_id"] > :selected').data('isi');
        if (!isi) {
            isi = 1;
        }
        total_transfer += $th.find('[data-field="price"]').val() * $th.find('[data-field="qty"]').val() * isi;
        total_qty += $th.find('[data-field="qty"]').val() * isi;
    });
    $('#transfer-value').val(total_transfer);
    $('#transfer-value-text').text('Rp' + total_transfer);
    $('#transfer-qty').val(total_qty);
    $('#transfer-qty-text').text(total_qty + ' items');
}