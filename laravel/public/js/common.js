$(function () {
    $('#order_rule').on("change", function () {
        const val = $(this).val();
        const url = new URL(location);
        url.searchParams.set("order_rule",val);
        window.location.href = url.toString();
    });
    $('[id^=limit]').on("change", function () {
        const val = $(this).val();
        const url = new URL(location);
        url.searchParams.set("limit",val);
        window.location.href = url.toString();
    });
});