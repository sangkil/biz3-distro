yii.biz = (function ($) {

    var pub = {
        prop: function (a, b) {
            if (arguments.length === 0) {
                return pub.prop;
            } else if (typeof a === 'object') {
                $.each(a, function (k, v) {
                    pub.prop[k] = v;
                });
            } else if (arguments.length === 1) {
                return pub.prop[a];
            } else {
                pub.prop[a] = b;
            }
        },
        init: function () {
            $('#select-branch').change(function () {
                var url = $(this).closest('form').prop('action');
                $.post(url, {branch: $(this).val()}, function () {
                    if (pub.prop('reloadOnBranchChange')) {
                        window.location.reload();
                    }
                }).fail(function (j,msg) {
                    alert(msg);
                });
            });
        },
    };

    return pub;
})(jQuery);