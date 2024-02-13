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
                    $("#ld-neumen").text(tresp + " Files Imported");
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
            Math.floor(result);
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
                    $("#notif").text("File is ready for upload");
                    console.log(Total);
                }
            } else if ("442" === o || "9470" === o) {
                if ("C" !== t[0][0]) {
                    $("#notif").text(
                        "File is not for Zuellig or Metro vendor Upload"
                    );
                    (a.value = ""),
                        (e = ""),
                        $("#upload-asn").val(null),
                        $("#import-asn").prop("disabled", !0),
                        $("#ld-denom").css("visibility", "hidden"),
                        $("#ld-neumen").css("visibility", "hidden"),
                        $("#import-asn").val("Upload");
                    return;
                }

                var drugPattern = /(SOUTH STAR DRUG INC\.)|SOUTH STAR DRUG/;

                let f = t.split("\n"),
                    c = f.map((l) => l.split(","));

                // Modify the content at index 6 for each element in array c
                c.forEach((row) => {
                    if (row.length > 6) {
                        let index6Content = row[6].trim();
                        let match = index6Content.match(drugPattern);

                        if (match) {
                            // If there is a second part (INC.), move it to index 7
                            if (
                                index6Content.toUpperCase().includes(' INC."')
                            ) {
                                let remainingText = index6Content
                                    .substring(match[0].length)
                                    .trim();
                                if (remainingText !== "") {
                                    row[6] = match[0]; // Set index 6 to the matched pattern
                                    row[7] = remainingText; // Set index 7 to the remaining text
                                }
                            }
                        }
                    }
                });

                for (let i = 0; i < c.length; i++) {
                    // Check if the value of the 7th index is ' INC.'
                    if (
                        c[i].length > 7 &&
                        c[i][7] &&
                        c[i][7].toUpperCase() === ' INC."'
                    ) {
                        // Remove it if it is ' INC.'
                        c[i].splice(7, 1);
                    }
                }

                // console.log(c);

                e = JSON.stringify(c, null, 2); // Move this line outside the loop

                let s = c.length;
                // $("#ld-denom").text(s);
                Total = s - 1;

                $("#notif").text("File is ready for upload");
                console.log(Total);
            } else {
                let m = t.replace(/"/g, "");

                if ("I" !== m[0][0]) {
                    $("#notif").text("File not for chosen vendor upload");
                    a.value = "";
                    e = "";
                    $("#upload-asn").val(null);
                    $("#import-asn").prop("disabled", !0);
                    $("#ld-denom").css("visibility", "hidden");
                    $("#ld-neumen").css("visibility", "hidden");
                    $("#import-asn").val("Upload");
                    return;
                }

                var d = /DRUG\d*,?/g,
                    u = t.replace(d, (l) => l.replace(",", ""));
                let v = u.split("\n"),
                    g = v.map((l) => {
                        let a = l.split(",");

                        // Check if index 7 exists and contains "DRUG"
                        if (a[7] && a[7].includes("DRUG")) {
                            let vendorInfo = a[7].match(/(.+?)(\d+)$/);
                            if (vendorInfo) {
                                a[7] = vendorInfo[1];
                                a.splice(8, 0, vendorInfo[2]);
                            }
                        }

                        if (o === "185") {
                            if (a.length > 17) {
                                if (a[5] && a[6]) {
                                    // Join the 5th and 6th elements
                                    a[5] = a[5] + a[6];
                                    // Remove the 6th element
                                    a.splice(6, 1);
                                }
                            }
                        }

                        if (
                            o == "8587" ||
                            o == "10377" ||
                            o == "10003" ||
                            o == "10220"
                        ) {
                            if (a.length > 17) {
                                // Check if indices 7 and 8 exist in the array
                                if (a[7] !== undefined && a[8] !== undefined) {
                                    // Add the value of index 8 to index 7
                                    a[7] += a[8];
                                    // Splice the element at index 8
                                    a.splice(8, 1);
                                }
                            }
                        }

                        if (o === "8088") {
                            if (a[5] && a[5].includes("DRUG")) {
                                // Split the string based on "DRUG"
                                let parts = a[5].split("DRUG");

                                // Update the 5th index with the part before "DRUG"
                                a[5] = parts[0].trim() + " DRUG";

                                // Insert the second part into the 6th index
                                a.splice(6, 0, parts[1].trim());
                            }
                        }

                        return a;
                    });

                console.log(g);
                e = JSON.stringify(g, null, 2);
                let s = g.length;
                // $("#ld-denom").text(s);
                Total = s;
                console.log(Total);

                $("#notif").text("File is ready for upload");
            }
        }),
            n.readAsText(t);
    });
});
