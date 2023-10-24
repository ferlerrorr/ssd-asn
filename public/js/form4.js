$(document).ready(function () {
    let l = document.querySelector("#vendors");
    function a() {
        "" === $("#upload-asn").val()
            ? $("#import-asn").prop("disabled", !0)
            : $("#import-asn").prop("disabled", !1);
    }
    a(),
        $("#clear").click(function () {
            (e = ""),
                $("#upload-asn").val(null),
                $("#import-asn").prop("disabled", !1),
                $("#import-asn").val("Upload"),
                $("#notif").text(""),
                a();
        }),
        $("#upload-asn").on("change", function () {
            a(), $("#ld-denom").css("visibility", "visible");
        }),
        $("#import_asn").on("submit", function (t) {
            if ((t.preventDefault(), void 0 === e)) {
                console.error("requestBody is not defined");
                return;
            }
            $.ajax({
                url: "http://localhost:8800/api/ssd/asn/upload/" + l.value,
                method: "POST",
                data: e,
                contentType: "application/json",
                cache: !1,
                processData: !1,
                beforeSend: function () {
                    $("#import-asn").prop("disabled", !0),
                        $("#import-asn").val("Uploading...."),
                        $("#clear-asn").prop("disabled", !0),
                        $("#import-asn").addClass("uploading"),
                        $("#ld-neumen").css("visibility", "visible"),
                        loadingProgress();
                },
                error: function (l, e, t) {
                    let o = JSON.stringify(l.message);
                    $("#notif").text(o),
                        $("#notif").css("color", "red"),
                        $("#notif").css("visibility", "visible"),
                        $("#import-ms").val("Upload"),
                        $("#import_asn")[0].reset(),
                        $("#clear-asn").prop("disabled", !1),
                        $("#import-asn").removeClass("uploading"),
                        a();
                },
                success: function (l, e, xhr) {
                    let responseCode = xhr.status;
                    let tresp;
                    console.log(responseCode);
                    if (responseCode == 202) {
                        tresp = l.passed_items;
                    } else {
                        tresp = Total;
                    }
                    console.log(Total);
                    let rlpercent = Math.floor((tresp / Total) * 100);
                    let t = JSON.stringify(l);
                    if (l.passed_items) {
                        delete l.passed_items;
                    }
                    t = JSON.stringify(l).replace(/[{}\[\]]/g, "");
                    t = t.replace(/,/g, ",\n");

                    // Rest of your code
                    $("#import_asn")[0].reset();
                    $("#import-asn").prop("disabled", false);
                    $("#import-asn").val("Upload");
                    $("#notif").text(t);
                    $("#notif").css("visibility", "visible");
                    a();
                    $("#clear-asn").prop("disabled", false);
                    $("#import-asn").removeClass("uploading");
                    stopInterval();
                    $("#ld-neumen").text(tresp);
                    $("#ld-denom").text(rlpercent + "%");
                    console.log(t);
                },
            });
        });

    let e;
    let total = 0;
    let Total;
    let interval;

    function loadingProgress() {
        $("#ld-denom").text(0 + "%");
        $("#ld-neumen").text(0);
        total = Total / 3; // Initialize the total

        if (total % 3 !== 0) {
            total = 1;
        }
        let percent = 0;

        interval = setInterval(function () {
            var result = Total - Total * 0.02;
            let incrementAmount = Math.round(Total * 0.02); // 2% of Total
            total += incrementAmount;
            percent += Math.round((incrementAmount / Total) * 100);
            $("#ld-denom").text(percent + "%");
            $("#ld-neumen").text(total);
            if (total >= Total) {
                clearInterval(interval);
                $("#ld-neumen").text(result);
                $("#ld-denom").text(98 + "%");
            }
        }, 50);
        $("#ld-neumen").data("interval", interval);

        // Return the interval ID
        return interval;
    }

    function stopInterval() {
        if (interval) {
            clearInterval(interval); // Clear the interval from another function
            interval = null; // Reset the interval variable
        }
    }

    // Call the function to start the increment and store the interval ID

    // Call the function to start the increment

    $("#upload-asn").change(function (l) {
        let a = l.target,
            t = a.files[0],
            o = "" + vendors.value;
        if (null == t || !t.type.match(/text.*/)) {
            $("#notif").text("File is not supported for ASN upload"),
                (e = ""),
                $("#upload-asn").val(null),
                $("#import-asn").prop("disabled", !0),
                $("#import-asn").val("Upload");
            return;
        }
        let n = new FileReader();
        (n.onload = function (l) {
            let t = n.result;
            if ("200" === o) {
                t = t.replace(/(H|D|L),/g, '$1",');
                if ("H" !== (t = t.replace(/"/g, ""))[0][0]) {
                    $("#notif").text("File is not for Unilab vendor Upload"),
                        (a.value = ""),
                        (e = ""),
                        $("#upload-asn").val(null),
                        $("#import-asn").prop("disabled", !0),
                        $("#ld-denom").css("visibility", "hidden"),
                        $("#ld-neumen").css("visibility", "hidden"),
                        $("#import-asn").val("Upload");
                    return;
                }
                {
                    let i = t.split("\n"),
                        r = i.map((l) => {
                            let a = l.split(",");
                            return a;
                        });
                    e = JSON.stringify(r, null, 2);
                    let s = 0;
                    for (let p in r) r.hasOwnProperty(p) && s++;
                    // console.log("Total items in jsonData: " + s);
                    // $("#ld-denom").text(s);
                    Total = s;
                    console.log(Total);
                }
            } else if ("442" === o || "9470" === o) {
                if ("C" !== t[0][0]) {
                    $("#notif").text(
                        "File is not for Zuellig or Metro vendor Upload"
                    ),
                        (a.value = ""),
                        (e = ""),
                        $("#upload-asn").val(null),
                        $("#import-asn").prop("disabled", !0),
                        $("#ld-denom").css("visibility", "hidden"),
                        $("#ld-neumen").css("visibility", "hidden"),
                        $("#import-asn").val("Upload");
                    return;
                }
                {
                    var d = /DRUG\d*,?/g,
                        u = t.replace(d, (l) => l.replace(",", ""));
                    let f = u.split("\n"),
                        c = f.map((l) => {
                            let a = l.split(",");
                            return a;
                        });
                    e = JSON.stringify(c, null, 2);
                    let s = c.length;
                    // $("#ld-denom").text(s);
                    Total = s - 1;
                    console.log(Total);
                }
            } else {
                let m = t.replace(/"/g, "");
                if ("I" !== m[0][0]) {
                    $("#notif").text("File not for chosen vendor upload"),
                        (a.value = ""),
                        (e = ""),
                        $("#upload-asn").val(null),
                        $("#import-asn").prop("disabled", !0),
                        $("#ld-denom").css("visibility", "hidden"),
                        $("#ld-neumen").css("visibility", "hidden"),
                        $("#import-asn").val("Upload");
                    return;
                }
                var d = /DRUG\d*,?/g,
                    u = t.replace(d, (l) => l.replace(",", ""));
                let v = u.split("\n"),
                    g = v.map((l) => {
                        let a = l.split(",");
                        return a;
                    });
                e = JSON.stringify(g, null, 2);
                let s = g.length;
                // $("#ld-denom").text(s);
                Total = s;
                console.log(Total);
            }
            $("#notif").text("File is ready for upload");
        }),
            n.readAsText(t);
    });
});
