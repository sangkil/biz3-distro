$('#input-product').change(function(){
    var val = $(this).val();
    
    var $row = $('#detail-grid').mdmTabularInput('addRow');
    $row.find(':input[data-field="product_id"]').val(val);
    $row.find('span[data-field="product"]').text(val);    
});