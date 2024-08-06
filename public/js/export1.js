$(document).ready(function () {
    $("#exportcsv").change(function (r) {
        var o = $(this).val();
        if (o.length > 0) {
            var t = (o = "x\n" + o).split("\n").map(function (e) {
                return e.split(",");
            });
            let n = t.map(function (e) {
                return e.map(function (e) {
                    return e.replace(/['"]+/g, "");
                });
            });
            (e = n), console.log(e);
        }
    });
    let e = [];
    $("#export-asn").on("click", function (r) {
        if ((r.preventDefault(), void 0 === e)) {
            console.error("exportBody is not defined");
            return;
        }
        $.ajax({
            url: "http://10.91.100.145:8800/api/ssd/asn/export",
            method: "POST",
            data: e,
            processData: !1,
            xhrFields: { responseType: "blob" },
            beforeSend: function () {
                $("#export-asn").prop("disabled", !0),
                    $("#export-asn").val("Exporting..."),
                    $("#export-asn").addClass("uploading");
            },
            success: function (e, r, o) {
                if (200 === o.status) {
                    var t = window.URL.createObjectURL(e),
                        n = document.createElement("a");
                    (n.style.display = "none"),
                        (n.href = t),
                        (n.download = "MSSASN1.csv"),
                        document.body.appendChild(n),
                        n.click(),
                        document.body.removeChild(n),
                        window.URL.revokeObjectURL(t),
                        $("#export-asn").prop("disabled", !1),
                        $("#export-asn").val("Export"),
                        $("#export-asn").removeClass("uploading"),
                        $("#exportcsv").val(""),
                        $("#export-notif").css("color", "green"),
                        $("#export-notif").css("display", "block"),
                        $("#export-notif").text("Export Successful.");
                } else console.error("Network response was not ok");
            },
            error: function (e, r, o) {
                console.error("Error exporting data:", o),
                    $("#export-notif").css("color", "red"),
                    $("#export-notif").css("display", "block"),
                    $("#export-notif").text("Error exporting data:", o);
            },
        });
    });
});
