$(document).ready(function () {
    $("#error-log").DataTable({ ordering: !1 }),
        $.ajax({
            async: !0,
            crossDomain: !0,
            url: "http://localhost:8800/api/ssd/asn/jda/loaderrlogs",
            method: "GET",
            headers: { Accept: "*/*" },
        }).done(function (a) {
            let r;
            try {
                let o = (r = a).map((a) => [
                        a.e_vendor,
                        a.e_time_stamp,
                        a.link,
                    ]),
                    e = $("#error-log").DataTable();
                o.forEach(function (a) {
                    let r = [
                        a[0],
                        a[1],
                        '<a href="' +
                            a[2] +
                            '" class="logs-btn" target="_blank">Export</a>',
                    ];
                    e.row.add(r).draw();
                });
            } catch (t) {
                console.error("Error parsing JSON:", t);
            }
        });
});
