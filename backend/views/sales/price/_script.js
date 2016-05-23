$(document).ready(function () {
    $('#price-product_name').autocomplete({
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
            $("#price-product_name").val(ui.item.name);
            return false;
        },
        select: function (event, ui) {
            selectProduct(ui.item);
            return false;
        },
        search: function(){
            $("#price-product_id").val('');
        }
    }).autocomplete("instance")._renderItem = function (ul, item) {
        return $("<li>")
                .append("<a>" + item.code + "<br>" + item.name + "</a>")
                .appendTo(ul);
    };
});

function selectProduct(item) {
     $("#price-product_id").val(item.id);
}
