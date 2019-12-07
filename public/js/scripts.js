$(document).ready(function($) {
    $(".table-row").click(function() {
        window.document.location = $(this).data("href");
    });
});
$(document).ready(function($) {
    $(".table-cell").click(function() {
        window.document.location = $(this).data("href");
    });
});
