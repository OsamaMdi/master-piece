document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2 with proper configuration
    $('#colorSelect').select2({
        templateResult: formatColor,
        templateSelection: formatColor,
        width: '100%' // Ensure proper responsive width
    });

    $('#iconSelect').select2({
        templateResult: formatIcon,
        templateSelection: formatIcon,
        width: '100%' // Ensure proper responsive width
    });

    function formatColor(state) {
        if (!state.id) return state.text;
        const color = state.element.value;
        return $(`<span><div style="width:20px; height:20px; background:${color}; display:inline-block; margin-right:8px; border:1px solid #ddd;"></div>${state.text}</span>`);
    }

    function formatIcon(state) {
        if (!state.id) return state.text;
        const icon = state.element.value;
        return $(`<span><i class="${icon} mr-2"></i>${state.text}</span>`);
    }
});
