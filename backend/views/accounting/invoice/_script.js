$(function() {
    $('#input-product').autocomplete({
        minLength: 0,
        source: biz.product_url,
        focus: function(event, ui) {
            $("#input-product").val(ui.item.name);
            return false;
        },
        select: function(event, ui) {
            $("#input-product").val('');

            var $row = $('#detail-grid').mdmTabularInput('addRow');
            $row.find(':input[data-field="item_type"]').val(10);
            $row.find(':input[data-field="item_id"]').val(ui.item.id);
            $row.find('span[data-field="item"]').text(ui.item.name);
            $row.find(':input[data-field="qty"]').focus();

            return false;
        }
    }).autocomplete("instance")._renderItem = function(ul, item) {
        return $("<li>")
                .append("<a>" + item.code + "<br>" + item.name + "</a>")
                .appendTo(ul);
    };

    $('#invoice-vendor_name').autocomplete({
        minLength: 1,
        source: biz.vendor_url,
        focus: function(event, ui) {
            $("#invoice-vendor_name").val(ui.item.name);
            return false;
        },
        select: function(event, ui) {
            $("#invoice-vendor_name").val(ui.item.name);
            $('#invoice-vendor_id').val(ui.item.id);
            return false;
        }
    }).autocomplete("instance")._renderItem = function(ul, item) {
        return $("<li>")
                .append("<a>" + item.code + "<br>" + item.name + "</a>")
                .appendTo(ul);
    };

    $(document).on('keydown', ':input[data-field="qty"]', function(e) {
        var $tr = $(this).closest('tr');
        if (e.keyCode === 13) {
            $tr.find('[data-field="item_value"]').focus();
            return false;
        }
    });

    $(document).on('keydown', ':input[data-field="item_value"]', function(e) {
        var $tr = $(this).closest('tr');
        if (e.keyCode === 13) {
//            var $dqty = $tr.find('[data-field="qty"]').val();
//            var $dval = $(this).val();
//
//            $tr.find('[data-field="line_total"]').text($dqty * $dval);
            $('#input-product').focus();
            return false;
        }
    });

    $(document).on('change', ':input[data-field="item_value"],:input[data-field="qty"]', function(e) {
        var $tr = $(this).closest('tr');
        var $dqty = $tr.find('[data-field="qty"]').val();
        var $dval = $tr.find('[data-field="item_value"]').val();
        $tr.find('[data-field="line_total"]').text($dqty * $dval);
        
        countTotal();
        return false;
    });
});

var countTotal = function() {
    var $tot=0;
    $(document).find('[data-field="qty"]').each(function(index){
        var $tr = $(this).closest('tr');
        $tot += parseInt($tr.find('[data-field="line_total"]').text());
    });
    $('#invoice-value').val($tot);
};
