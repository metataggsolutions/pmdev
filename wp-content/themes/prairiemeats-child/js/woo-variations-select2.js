jQuery(document).ready(function(e) {
    e.each(e(".single-product select[data-attribute_name], .single-product .wc-pao-addon-select"), function(t, n) {
        e(n).select2({
            minimumResultsForSearch: -1,
            dropdownParent: e(n).closest(".summary.entry-summary")
        })
    })
});