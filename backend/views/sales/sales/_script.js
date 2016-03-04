
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
        $row.find(':input[data-field="price"]').val(ui.item.prices[0].price);
        $row.find(':input[data-field="qty"]').focus();

        $row.find(':input[data-field="qty"]').on('blur', function () {
            $row.find('span[data-field="totalLine"]').text(ui.item.prices[0].price * $row.find(':input[data-field="qty"]').val());
            return false;
        });

        $row.find(":input").on('keypress', function (e) {
            if (e.which == 13) {
                $("#input-product").focus();
                calculate();
                return false;
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

$("form").submit(function (event) {
    event.preventDefault();
});

$('#detail-grid').on('change', function () {
    calculate();
});

$('#detail-grid').on('change', ':input', function () {
    calculate();
});

$('#payment-add').on('click', function () {
    $('#payment-grid').removeClass('hidden');
    
    alert('show completion while payment-value >= sales-value');
    $('#payment-completion').removeClass('hidden');
});



function calculate() {
    var rows = $('#detail-grid').mdmTabularInput('getAllRows');
    var total_val = 0.0;
    var total_qty = 0.0;
    $.each(rows, function () {
        var $th = this;
        var isi = $th.find('[data-field="uom_id"] > :selected').data('isi');
        if (!isi) {
            isi = 1;
        }
        total_val += $th.find('[data-field="price"]').val() * $th.find('[data-field="qty"]').val() * isi;
        total_qty += $th.find('[data-field="qty"]').val() * isi;
    });
    $('#sales-value').val(total_val);
    $('#sales-value-text').text('Rp' + total_val);
    $('#sales-qty').val(total_qty);
    $('#sales-qty-text').text(total_qty + ' items');
    $('#payment-value').val(total_val);

    if (total_val > 0) {
        $('#payment-form').removeClass('hidden');
        $('#payment-items-value').val(total_val);
    }
}