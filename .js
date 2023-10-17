function initializeVendorTable() {
    $("#vsetupVendorTable").DataTable({
        ajax: {
            url: "http://10.91.100.145:8800/api/ssd/asn/vendorid-setup",
            dataSrc: "",
        },
        columns: [
            { data: "v_vname" },
            { data: "v_vid" },
            { data: null, render: renderEditAndDeleteButtons },
        ],
        paging: !0,
        searching: !0,
    });
}
function renderEditAndDeleteButtons(e, i, a) {
    var s;
    return `
      <button type="button" class="btn btn-primary" 
        data-toggle="modal" data-target="#vsetupEditVendorModal"
        data-vendorname="${e.v_vname}" data-vendorid="${e.v_vid}">Edit
      </button>
      <button type="button" class="btn btn-danger" 
        data-toggle="modal" data-target="#vsetupDeleteVendorConfirmationModal"
        data-vendorname="${e.v_vname}" data-vendorid="${e.v_vid}">Delete
      </button>`;
}
$("#leftside-navigation .parent > a").click(function (e) {
    e.preventDefault();
    var i = $("#leftside-navigation ul").not($(this).parents("ul"));
    if (
        (i.slideUp(),
        i.parent().removeClass("open"),
        !$(this).next().is(":visible"))
    ) {
        var a = $(this).next();
        a.slideDown(), a.parent().not(".open").addClass("open");
    }
    e.stopPropagation();
}),
    $("#navAsn").click(function (e) {
        $("#colSetup").removeClass("view-visible").addClass("view-hidden"),
            $("#errorlogs").removeClass("view-visible").addClass("view-hidden"),
            $("#asnView").removeClass("view-hidden").addClass("view-visible"),
            $("#asnExport").removeClass("view-visible").addClass("view-hidden"),
            $("#duplogs").removeClass("view-visible").addClass("view-hidden"),
            $("#asnVid").removeClass("view-visible").addClass("view-hidden");
    }),
    $("#ssd-logo").click(function (e) {
        $("#colSetup").removeClass("view-visible").addClass("view-hidden"),
            $("#errorlogs").removeClass("view-visible").addClass("view-hidden"),
            $("#asnView").removeClass("view-hidden").addClass("view-visible"),
            $("#asnExport").removeClass("view-visible").addClass("view-hidden"),
            $("#duplogs").removeClass("view-visible").addClass("view-hidden"),
            $("#asnVid").removeClass("view-visible").addClass("view-hidden");
    }),
    $("#navExport").click(function (e) {
        $("#colSetup").removeClass("view-visible").addClass("view-hidden"),
            $("#errorlogs").removeClass("view-visible").addClass("view-hidden"),
            $("#asnView").removeClass("view-visible").addClass("view-hidden"),
            $("#asnExport").removeClass("view-hidden").addClass("view-visible"),
            $("#duplogs").removeClass("view-visible").addClass("view-hidden"),
            $("#asnVid").removeClass("view-visible").addClass("view-hidden");
    }),
    $("#navErrlogs").click(function (e) {
        $("#colSetup").removeClass("view-visible").addClass("view-hidden"),
            $("#asnView").removeClass("view-visible").addClass("view-hidden"),
            $("#asnExport").removeClass("view-visible").addClass("view-hidden"),
            $("#errorlogs").removeClass("view-hidden").addClass("view-visible"),
            $("#duplogs").removeClass("view-visible").addClass("view-hidden"),
            $("#asnVid").removeClass("view-visible").addClass("view-hidden");

        // Call the function to fetch and populate error logs
        fetchAndPopulateErrorLogs();
    }),
    $("#navDuplogs").click(function (e) {
        $("#colSetup").removeClass("view-visible").addClass("view-hidden"),
            $("#asnView").removeClass("view-visible").addClass("view-hidden"),
            $("#asnExport").removeClass("view-visible").addClass("view-hidden"),
            $("#errorlogs").removeClass("view-visible").addClass("view-hidden"),
            $("#asnVid").removeClass("view-visible").addClass("view-hidden"),
            $("#duplogs").removeClass("view-hidden").addClass("view-visible");
        // Call the function to fetch and populate duplicate logs
        fetchAndDupLogs();
    }),
    $("#navVid").click(function (e) {
        $("#asnView").removeClass("view-visible").addClass("view-hidden"),
            $("#colSetup").removeClass("view-visible").addClass("view-hidden"),
            $("#asnExport").removeClass("view-visible").addClass("view-hidden"),
            $("#errorlogs").removeClass("view-visible").addClass("view-hidden"),
            $("#duplogs").removeClass("view-visible").addClass("view-hidden"),
            $("#asnVid").removeClass("view-hidden").addClass("view-visible"),
            $("#vsetupVendorTable").DataTable().destroy(),
            initializeVendorTable();
    }), //! // Call the function to initialize the vendor table
    initializeVendorTable();
function fetchAndPopulateErrorLogs() {
    // Fade out the DataTable
    $("#error-log").fadeOut(480, function () {
        let dataTable = $("#error-log").DataTable();

        if ($.fn.DataTable.isDataTable("#error-log")) {
            dataTable.clear().draw();
        } else {
            dataTable = $("#error-log").DataTable({ ordering: false });
        }

        // Your existing code here

        $.ajax({
            async: true,
            crossDomain: true,
            url: "http://10.91.100.145:8800/api/ssd/asn/jda/loaderrlogs",
            method: "GET",
            headers: { Accept: "*/*" },
        })
            .done(function (data) {
                try {
                    let logData = data.map((item) => [
                        item.e_vendor,
                        item.e_time_stamp,
                        item.link,
                    ]);

                    // Add updated data to the table
                    logData.forEach(function (log) {
                        let rowData = [
                            log[0],
                            log[1],
                            '<a href="' +
                                log[2] +
                                '" class="logs-btn" target="_blank">Export</a>',
                        ];
                        dataTable.row.add(rowData);
                    });

                    dataTable.draw();
                } catch (error) {
                    console.error("Error parsing JSON:", error);
                }
            })
            .fail(function (xhr, status, error) {
                console.error("Ajax request failed:", error);
            });

        // Fade in the DataTable
        $(this).fadeIn(480);
    });
}
fetchAndDupLogs();
function fetchAndDupLogs() {
    $("#dup-log").fadeOut(480, function () {
        if ($.fn.DataTable.isDataTable("#dup-log")) {
            // Destroy the existing DataTable
            $("#dup-log").DataTable().destroy();
        }

        // Initialize DataTable with ordering set to false
        let dataTable = $("#dup-log").DataTable({ ordering: false });

        // Your existing code here
        $.ajax({
            async: true,
            crossDomain: true,
            url: "http://10.91.100.145:8800/api/ssd/asn/duplicate-po-load/loadduplogs",
            method: "GET",
            headers: { Accept: "*/*" },
        })
            .done(function (data) {
                try {
                    let logData = data.map((item) => [
                        item.placeholder,
                        item.e_time_stamp,
                        item.link,
                    ]);

                    // Clear existing rows from the table
                    dataTable.clear();

                    // Add updated data to the table
                    logData.forEach(function (log) {
                        let rowData = [
                            log[0],
                            log[1],
                            '<a href="' +
                                log[2] +
                                '" class="logs-btn" target="_blank">Export</a>',
                        ];
                        dataTable.row.add(rowData);
                    });

                    dataTable.draw();
                } catch (error) {
                    console.error("Error parsing JSON:", error);
                }
            })
            .fail(function (xhr, status, error) {
                console.error("Ajax request failed:", error);
            });

        $(this).fadeIn(480);
    });
}
$(document).ready(function () {
    fetchAndDupLogs();
});
