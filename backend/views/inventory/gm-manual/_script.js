$(document).ready(function () {
    var total_val = 0.0;
    $('#detail-grid > tr').each(function () {
        var $row = $(this).closest('#detail-grid > tr');
        var $itemCost = $row.find(':input[data-field="cogs"]').val();
        var $product_id = $row.find(':input[data-field="product_id"]').val();

        $row.find('[data-field="uom_id"] > option').each(function () {
            var $op = $(this);
            if (masters.products[$product_id].uoms[$op.val()]) {
                $op.attr('data-isi', masters.products[$product_id].uoms[$op.val()].isi);
            } else {
                $op.remove();
            }
        });
        total_val += $itemCost * $row.find(':input[data-field="qty"]').val() * $row.find('select > :selected').data('isi');
    });
    $('#total').text(total_val);
});

function getCost(id) {
    if (masters.products[id] && masters.products[id].cost) {
        var price = masters.products[id].cost;
        return price;
    }
    return 0;
}

function selectProduct(item) {
    $("#input-product").val('');

    var $row = $('#detail-grid').mdmTabularInput('addRow');
    var $itemCost = getCost(item.id);
    $row.find(':input[data-field="product_id"]').val(item.id);
    $row.find('span[data-field="product"]').text(item.name);
    $row.find(':input[data-field="qty"]').focus();

    $row.find(':input[data-field="cogs"]').val($itemCost);

    $row.find('[data-field="uom_id"] > option').each(function () {
        var $op = $(this);
        if (item.uoms[$op.val()]) {
            $op.attr('data-isi', item.uoms[$op.val()].isi);
        } else {
            $op.remove();
        }
    });
}

$('#input-product').autocomplete({
    minLength: 1,
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
}).autocomplete("instance")._renderItem = function (ul, item) {
    return $("<li>")
        .append("<a>" + item.code + "<br>" + item.name + "</a>")
        .appendTo(ul);
};

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

$('#listPO').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var modal = $(this);
    var title = button.data('title');
    modal.find('.modal-title').html(title);
    modal.find('.modal-body').html('<i class=\"fa fa-spinner fa-spin\"></i>&nbsp;&nbsp;Loading ...');
});

$('#detail-grid').on('blur', ':input[data-field="qty"]', function () {
    var $row = $(this).closest('#detail-grid > tr');
    var itemCost = $row.find(':input[data-field="cogs"]').val();
    var $isi = $row.find('select > :selected').data('isi');

    $row.find('span[data-field="totalLine"]').text(itemCost * $row.find(':input[data-field="qty"]').val() * $isi);
});

$('#detail-grid').on('change', ':input[data-field="uom_id"]', function () {
    var $row = $(this).closest('#detail-grid > tr');
    var itemCost = $row.find(':input[data-field="cogs"]').val();
    var $isi = $row.find('select > :selected').data('isi');

    $row.find('span[data-field="totalLine"]').text(itemCost * $row.find(':input[data-field="qty"]').val() * $isi);
});

$('#detail-grid').on('keypress', ':input', function (e) {
    if (e.which == 13) {
        $("#input-product").focus();
        return false;
    }
});

function calculate() {
    var total_val = 0.0;
    $('#detail-grid > tr').each(function () {
        var $row = $(this).closest('#detail-grid > tr');
        var $itemCost = $row.find(':input[data-field="cogs"]').val();

        total_val += $itemCost * $row.find(':input[data-field="qty"]').val() * $row.find('select > :selected').data('isi');
    });
    $('#total').text(total_val);
}

$('#detail-grid').on('change', function () {
    calculate();
});

$('#detail-grid').on('change', ':input', function () {
    calculate();
});