$(function () {
    $("input[name='file']").on("change", function () {
        if ($(this).val().length > 0) {
            $("#import-to").slideDown();
        } else {
            $("#import-to").slideUp();
        }
    });
    $("select[name='import_to']").on("change", function () {

        if ($(this).val() == "blog") {
            $("#default-category").slideUp();
        } else {
            $("#default-category").slideDown();

        }
    });
    $("select[name='language']").change(function () {
        filterParentPages();
    });

    $("select[name='menu']").change(function () {
        filterParentPages();
    });
    $("input[name='file']").trigger("change");
    $("select[name='import_to']").trigger("change");
    filterParentPages();
});

function filterParentPages() {
    var url = $("#main-form").data("parent-pages-url");
    var data = {
        mlang: $("select[name='language']").val(),
        mmenu: $("select[name='menu']").val(),
        mparent: $("select[name='parent_id']").val(),
        csrf_token: $("input[name='csrf_token']").val()
    };
    $.post(url, data, function (text, status) {
        $("select[name='parent_id']").html(text);
    });
}