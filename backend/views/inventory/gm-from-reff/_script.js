
$('#detail-grid').on('blur change', ':input[data-field="qty"]', function () {
    var $row = $(this).closest('#detail-grid > tr');
    var itemCost = $row.find(':input[data-field="cogs"]').val();
    var isi = $row.find(':input[data-field="isi"]').val();
    var qty = $(this).val();
    isi = isi ? isi : 1;

    $row.find('span[data-field="totalLine"]').text(itemCost * qty * isi);
});