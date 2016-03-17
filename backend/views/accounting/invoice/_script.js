
$(function () {
    acomplt('input-product');
    acomplt('invoice-vendor_name');

    $(document).on('keydown', ':input[data-field="qty"]', function (e) {
        var $tr = $(this).closest('tr');
        if (e.keyCode === 13) {
            $tr.find('[data-field="item_value"]').focus();
            return false;
        }
    });
    $(document).on('keydown', ':input[data-field="item_value"]', function (e) {
        var $tr = $(this).closest('tr');
        if (e.keyCode === 13) {
            $('#input-product').focus();
            return false;
        }
    });
    $(document).on('change', ':input[data-field="item_value"],:input[data-field="qty"]', function (e) {
        var $tr = $(this).closest('tr');
        var $dqty = $tr.find('[data-field="qty"]').val();
        var $dval = $tr.find('[data-field="item_value"]').val();
        $tr.find('[data-field="line_total"]').text($dqty * $dval);
        countTotal();
        return false;
    });
    $(document).on('click', '.searchtype', function () {
        var $shold = $(this).data('placehold');
        var $sval = $(this).data('value');
        var $compnt = $(this).closest('div').parent('div').find('[data-field="item_search"]'); //$('#input-product');

        switch ($sval) {
            case 10:
                $compnt.attr('id', 'input-product');
                break;
            case 20:
                $compnt.attr('id', 'input-gmovement');
                break;
            case 30:
                $compnt.attr('id', 'input-sales');
                break;
            default:
                alert('nothing..');
        }

        $compnt.attr("placeholder", $shold);
        acomplt($compnt.attr('id'));
        $compnt.focus();
        //return false;
    });
});
var countTotal = function () {
    var $tot = 0;
    $(document).find('[data-field="qty"]').each(function (index) {
        var $tr = $(this).closest('tr');
        $tot += parseInt($tr.find('[data-field="line_total"]').text());
    });
    $('#invoice-value').val($tot);
};

function getCost(source, id) {
    if (source[id] && source[id].cost) {
        var cost = source[id].cost;
        return cost;
    }
    return 0;
}

var acomplt = function ($input_id) {
    var dselect = null;
    var dsource = masters.products;
    switch ($input_id) {
        case 'input-product':
            dsource = function (request, response) {
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
            };
            break;
        case 'input-gmovement':
            dsource = [];
            break;
        case 'input-sales':
            dsource = [];
            break;
        case 'invoice-vendor_name':
            dsource = function (request, response) {
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
            };
            dselect = function (event, ui) {
                $('#invoice-vendor_id').val(ui.item.id);
                return false;
            };
            break;
    }

    $('#' + $input_id).autocomplete({
        minLength: 0,
        source: dsource,
        focus: function (event, ui) {
            $('#' + $input_id).val(ui.item.name);
            return false;
        },
        select: (dselect !== null) ? dselect : function (event, ui) {
            $('#' + $input_id).val('');
            var $row = $('#detail-grid').mdmTabularInput('addRow');
            $row.find(':input[data-field="item_type"]').val(10);
            $row.find(':input[data-field="item_id"]').val(ui.item.id);
            $row.find('span[data-field="item"]').text(ui.item.name);
            $row.find(':input[data-field="item_value"]').val(getCost(masters.products, ui.item.id));
            $row.find(':input[data-field="qty"]').focus();

            return false;
        }
    }).autocomplete("instance")._renderItem = function (ul, item) {
        return $("<li>")
            .append('<a>' + item.code + '&nbsp;' + item.name + '</a>')
            .appendTo(ul);
    };
};