function calculate() {
    var rows = $('#detail-grid').mdmTabularInput('getAllRows');
    var total = 0.0;
    $.each(rows, function () {
        var $th = this;
        var isi = $th.find('[data-field="uom_id"] > :selected').data('isi');
        if (!isi) {
            isi = 1;
        }
        total += $th.find('[data-field="price"]').val() * $th.find('[data-field="qty"]').val() * isi;
    });
    $('#sales-value').val(total);
    $('#payment-value').val(total);
}

function calculatePayment() {
    var rows = $('#payment-grid').mdmTabularInput('getAllRows');
    var total = 0.0;
    $.each(rows, function () {
        var $th = this;
        total += 1.0 * $th.find('[data-field="payment_value"]').val();
    });
    $('#payment-total').val(total);
}


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

$('#detail-grid').on('change', function () {
    calculate();
});

$('#detail-grid').on('change', ':input', function () {
    calculate();
});

$('#payment-grid').on('change', function () {
    setTimeout(function (){
        calculatePayment();
    },0);
});

$('#payment-grid').on('change', ':input', function () {
    calculatePayment();
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
        $("#input-product").val('');

        var $row = $('#detail-grid').mdmTabularInput('addRow');
        $row.find(':input[data-field="product_id"]').val(ui.item.id);
        $row.find('span[data-field="product"]').text(ui.item.name);
        $row.find(':input[data-field="qty"]').focus();

        $row.find('[data-field="uom_id"] > option').each(function () {
            var $op = $(this);
            if (ui.item.uoms[$op.val()]) {
                $op.attr('data-isi', ui.item.uoms[$op.val()].isi);
            } else {
                $op.remove();
            }
        });

        return false;
    }
})
    .autocomplete("instance")._renderItem = function (ul, item) {
    return $("<li>")
        .append("<a>" + item.code + "<br>" + item.name + "</a>")
        .appendTo(ul);
};

$('#sales-vendor_name')
    .autocomplete({
        minLength: 1,
        source: biz.vendor_url,
        focus: function (event, ui) {
            $("#sales-vendor_name").val(ui.item.name);
            return false;
        },
        select: function (event, ui) {
            $("#sales-vendor_name").val(ui.item.name);
            $('#sales-vendor_id').val(ui.item.id);
            return false;
        }
    })
    .autocomplete("instance")._renderItem = function (ul, item) {
    return $("<li>")
        .append("<a>" + item.code + "<br>" + item.name + "</a>")
        .appendTo(ul);
};

$('#add-payment').click(function () {
    var $row = $('#payment-grid').mdmTabularInput('addRow');
    $row.find('[data-field="payment_method"]').val($('#inp-payment-method').val());
    $row.find('[data-field="nm_method"]').text($('#inp-payment-method').children(':selected').text());
    $row.find('[data-field="payment_value"]').val($('#inp-payment-value').val());
});