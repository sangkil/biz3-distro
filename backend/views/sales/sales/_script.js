var pc = yii.biz.prop('price_category') + '';
$("#input-product").focus();

function getPrice(id) {
    if (masters.products[id] && masters.products[id].prices) {
        var prices = masters.products[id].prices;
        return prices[pc] ? prices[pc] : (prices['1'] ? prices['1'] : 0);
    }
    return 0;
}

function searchProductByCode(code) {
    if (masters.barcodes[code] && masters.products[masters.barcodes[code]]) {
        selectProduct(masters.products[masters.barcodes[code]]);
    }
}

function selectProduct(item) {
    $("#input-product").val('');
    var $product_found = false;

    //check product is exist?    
    var $dgrid = $('#detail-grid');
    var $dgrid_row = $('#detail-grid > tr');
    var $a = '';

    $dgrid_row.each(function () {
        var $product_id = $(this).find(':input[data-field="product_id"]').val();
        if (parseInt($product_id) === parseInt(item.id)) {
            var $new_qty = parseInt($(this).find(':input[data-field="qty"]').val(), 10) + 1;
            $(this).find(':input[data-field="qty"]').val($new_qty).focus().trigger({type: 'keypress', which: 13});
            $product_found = true;
            return false;
        }
    });

    if (!$product_found) {
        var $new_grid = $dgrid.mdmTabularInput('addRow');
        var itemPrice = getPrice(item.id);

        $new_grid.find(':input[data-field="product_id"]').val(item.id);
        $new_grid.find('span[data-field="product"]').text(item.name);
        $new_grid.find(':input[data-field="qty"]').val(1).focus();
        $new_grid.find(':input[data-field="price"]').val(itemPrice);
        $new_grid.find('[data-field="uom_id"] > option').each(function () {
            var $op = $(this);
            if (item.uoms[$op.val()]) {
                $op.attr('data-isi', item.uoms[$op.val()].isi);
            } else {
                $op.remove();
            }
        });
    }
}

$('#detail-grid').on('keypress', ':input', function (e) {
    if (e.which == 13) {
        $("#input-product").focus();
        calculate();
        return false;
    }
    if (e.which == 113) {
        $('#btn-payment-add').click();
        return false;
    }
});

$('#input-product').on('keydown', function (e) {
    if (e.which == 113) {
        $('#btn-payment-add').click();
        return false;
    }
});

$('#detail-grid').on('blur', ':input[data-field="qty"]', function () {
    var $row = $(this).closest('#detail-grid > tr');
    var itemPrice = $row.find(':input[data-field="price"]').val();
    var isi = $row.find('[data-field="uom_id"] > :selected').data('isi');
    $row.find('span[data-field="totalLine"]').text(itemPrice * $row.find(':input[data-field="qty"]').val() * isi);
});

$('#detail-grid').on('change', ':input[data-field="uom_id"]', function () {
    var $row = $(this).closest('#detail-grid > tr');
    var itemPrice = $row.find(':input[data-field="price"]').val();
    var isi = $row.find('[data-field="uom_id"] > :selected').data('isi');
    $row.find('span[data-field="totalLine"]').text(itemPrice * $row.find(':input[data-field="qty"]').val() * isi);
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

$('#input-product').change(function () {
    searchProductByCode(this.value);
});

$('#input-product').autocomplete({
    minLength: 2,
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
    open: function (event, ui) {
        var li = $('.ui-autocomplete > li');
        if (li.length === 1) {
            $.each(li, function () {
                $(this).click();
                return false;
            });
        }
    },
    focus: function (event, ui) {
        $("#input-product").val(ui.item.name);
        return false;
    },
    select: function (event, ui) {
        selectProduct(ui.item);
        return false;
    }
}).autocomplete("instance")._renderItem = function (ul, item) {
    return $("<li>")
        .append("<a>" + item.code + "<br>" + item.name + "</a>")
        .appendTo(ul);
};

$('#sales-vendor_name')
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

$('#detail-grid').on('change', function () {
    calculate();
});

$('#detail-grid').on('change', ':input', function () {
    calculate();
});

$('#btn-payment-add').on('click', function () {
    $('#payment-grid').removeClass('hidden');

    var $row = $('#payment-grid-dtl').mdmTabularInput('addRow');
    $row.find('[data-field="method"]').val($('#inp-payment-method').val());
    $row.find('[data-field="t-method"]').text($('#inp-payment-method > :selected').text());
    $row.find('[data-field="value"]').val($('#inp-payment-value').val());
});

$('#payment-grid-dtl').on('change', function () {
    setTimeout(function () {
        calculatePayment();
    }, 100);
});

function calculatePayment() {
    var total_paid = 0.0;
    $('#payment-grid-dtl > tr').each(function () {
        var $row = $(this);
        total_paid += $row.find('[data-field="value"]').val() * 1;
    });
    $('#payment-value').val(total_paid);
    var total_sales = $('#sales-value').val() * 1;
    if (total_sales > total_paid || total_paid == 0) {
        $('#inp-payment-value').val(total_sales - total_paid);
        $('#payment-completion').addClass('hidden');
        $('#payment-input-panel').removeClass('hidden');
    } else {
        $('#inp-payment-value').val('');
        $('#payment-completion').removeClass('hidden');
        $('#payment-input-panel').addClass('hidden');
        if (confirm('complete?')) {
            $('#submit-btn').click();
        }
    }
}

function calculate() {
    var total_sales = 0.0;
    var total_qty = 0.0;
    $('#detail-grid > tr').each(function () {
        var $th = $(this);
        var isi = $th.find('[data-field="uom_id"] > :selected').data('isi');
        if (!isi) {
            isi = 1;
        }
        total_sales += $th.find('[data-field="price"]').val() * $th.find('[data-field="qty"]').val() * isi;
        total_qty += $th.find('[data-field="qty"]').val() * isi;
    });
    $('#sales-value').val(total_sales);
    $('#sales-value-text').text('Rp' + total_sales);
    $('#sales-qty').val(total_qty);
    $('#sales-qty-text').text(total_qty + ' items');

    if (total_sales > 0) {
        $('#payment-form').removeClass('hidden');
    }

    var total_paid = $('#payment-value').val() * 1;
    if (total_sales > total_paid || total_paid == 0) {
        $('#inp-payment-value').val(total_sales - total_paid);
        $('#payment-completion').addClass('hidden');
        $('#payment-input-panel').removeClass('hidden');
    } else {
        $('#inp-payment-value').val('');
        $('#payment-completion').removeClass('hidden');
        $('#payment-input-panel').addClass('hidden');
    }
}
