
$('#input-invoice').autocomplete({
    minLength: 0,
    source: function(req,callback){
        jQuery.get(biz.invoice_url,{
            term:req.term,
            type: $('#payment-type').val(),
            vendor: $('#payment-vendor_id').val()
        },function(data){
            callback(data);
        });
    },
    focus: function (event, ui) {
        $("#input-invoice").val(ui.item.name);
        return false;
    },
    select: function (event, ui) {
        $("#input-invoice").val('');

        var $row = $('#detail-grid').mdmTabularInput('addRow');
        $row.find(':input[data-field="invoice_id"]').val(ui.item.id);
        $row.find('span[data-field="invoice"]').text(ui.item.number);
        $row.find('span[data-field="sisa"]').text(ui.item.sisa);
        $row.find(':input[data-field="value"]').val(ui.item.sisa).focus();
        
        return false;
    }
})
    .autocomplete("instance")._renderItem = function (ul, item) {
    return $("<li>")
        .append("<a>" + item.number + "<br>" + item.sisa + "</a>")
        .appendTo(ul);
};