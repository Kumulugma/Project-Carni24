jQuery(document).ready(function ($) {
    $('.nav-tab-wrapper a').click(function (evt) {
        evt.preventDefault();
        $('.nav-tab-wrapper a').removeClass('nav-tab-active');
        $(this).addClass('nav-tab-active');
        $('.tab-content').hide();
        $($(this).attr('href')).show();
    });

    $('#newRobotsTXT').click(function () {

        var button = $(this);
        button.html('Generowanie');

        $.ajax({
            url: "/wp-admin/admin-ajax.php",
            method: 'POST',
            data: {
                action: "newRobotsTXT"
            },
            success: function (response) {
                $('#robotsText').html(response.text);
                $('#robotsDate').html(response.date);
                button.html('Wygeneruj ponownie');

            },
            error: function (xhr, status, error) {
                console.log("Błąd zapisu pliku robots.txt");
            }
        });
    });

    $('#newSitemap').click(function () {

        var button = $(this);
        button.html('Generowanie');
        $.ajax({
            url: "/wp-admin/admin-ajax.php",
            method: 'POST',
            data: {
                action: "newSitemap"
            },
            success: function (response) {
                $('#sitemapText').html(response.text);
                $('#sitemapDate').html(response.date);
                button.html('Wygeneruj ponownie');

            },
            error: function (xhr, status, error) {
                console.log("Błąd zapisu pliku robots.txt");
            }
        });
    });
    $('#activeTypes').click(function () {

        var button = $(this);
        button.html('Zapisywanie');
        var table = $(this).closest("table"); // znajdujemy najbliższą tabelę dla przycisku wyzwalacza
        var checkboxes = table.find("input[type=checkbox]"); // znajdujemy formularze checkbox w tabeli
        var values = [];

        checkboxes.each(function () { // iterujemy po formularzach checkbox
            var checkbox = $(this);
            if (checkbox.prop("checked")) { // jeśli checkbox jest zaznaczony, dodajemy jego wartość do tablicy
                values.push(checkbox.val());
            }
        });

        console.log(values);

        $.ajax({
            url: "/wp-admin/admin-ajax.php",
            method: 'POST',
            data: {
                action: "activeTypes",
                postTypes: values
            },
            success: function (response) {
                button.html('Zapisz');
            },
            error: function (xhr, status, error) {
                console.log("Błąd zapisu aktywnych typów wpisów");
            }
        });
    });

});