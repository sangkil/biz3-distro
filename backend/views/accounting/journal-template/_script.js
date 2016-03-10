/* 
 * Create By Mujib Masyhudi <mujib.masyhudi@gmail.com>
 * Create at {date('now')}
 */

$(':input.auto-coa').autocomplete({
    minLength: 0,
    source: function (request,response){
        var result = [];
        var limit = 10;
        var term = request.term.toLowerCase();
        $.each(masters.coas, function () {
            var coa = this;
            if (coa.name.toLowerCase().indexOf(term) >= 0 || coa.code.toLowerCase().indexOf(term) >= 0) {
                result.push(coa);
                limit--;
                if (limit <= 0) {
                    return false;
                }
            }
        });
        response(result);
    },
    select: function (event, ui) {
        var $this = $(this);
        var $th = $this.closest('tr[data-key]');
        $th.find('[data-field="id"]').val(ui.item.id);
        $th.find('[data-field="code"]').val(ui.item.code);
        $th.find('[data-field="name"]').val(ui.item.name);
        return false;
    }
});

$(':input.auto-coa').each(function () {
    $(this).autocomplete("instance")._renderItem = function (ul, item) {
        return $("<li>")
            .append("<a>" + item.code + "-" + item.name + "</a>")
            .appendTo(ul);
    };
});
