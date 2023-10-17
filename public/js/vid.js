$(document).ready(function () {
    function a() {
        $("#vsetupVendorTable").DataTable({
            ajax: {
                url: "http://localhost.145:8800/api/ssd/asn/vendorid-setup",
                dataSrc: "",
            },
            columns: [
                { data: "v_vname" },
                { data: "v_vid" },
                { data: null, render: e },
            ],
            paging: !0,
            searching: !0,
        });
    }
    function e(a, e, t) {
        var n;
        return `
      <button type="button" class="btn btn-primary" 
        data-toggle="modal" data-target="#vsetupEditVendorModal"
        data-vendorname="${a.v_vname}" data-vendorid="${a.v_vid}">Edit
      </button>
      <button type="button" class="btn btn-danger" 
        data-toggle="modal" data-target="#vsetupDeleteVendorConfirmationModal"
        data-vendorname="${a.v_vname}" data-vendorid="${a.v_vid}">Delete
      </button>`;
    }
    a(), //! // Call the function to initialize the vendor table
        $("#vsetupVendorTable tbody").on(
            "click",
            "button.btn-primary",
            function () {
                var a = $(this).data("vendorname"),
                    e = $(this).data("vendorid");
                $("#vsetupEditVendorName").val(a),
                    $("#vsetupEditVendorID").val(e),
                    $("#vsetupSaveEditVendorButton").val(e);
            }
        ),
        $("#vsetupSaveEditVendorButton").click(function () {
            var e = $("#vsetupEditVendorName").val(),
                t = $("#vsetupEditVendorID").val(),
                n = $(this).attr("value");
            let o = {
                async: !0,
                crossDomain: !0,
                url:
                    "http://localhost:8800/api/ssd/asn/vendorid-setup-update/" +
                    n,
                method: "PUT",
                headers: { Accept: "*/*", "Content-Type": "application/json" },
                processData: !1,
                data: JSON.stringify({ v_vname: e, v_vid: t }),
            };
            $("#vsetupVendorTable").fadeOut(420, function () {
                $.fn.DataTable.isDataTable("#vsetupVendorTable") &&
                    $("#vsetupVendorTable").DataTable().destroy(),
                    a(),
                    $(this).fadeIn(480);
            }),
                $.ajax(o)
                    .done(function (a) {
                        console.log(a);
                    })
                    .fail(function (a, e, t) {
                        console.error(t);
                    })
                    .always(function () {
                        $("#vsetupEditVendorModal").modal("hide");
                    });
        }),
        $("#vsetupDeleteVendorConfirmationModal").on(
            "show.bs.modal",
            function (a) {
                var e = $(a.relatedTarget);
                e.data("vendorname");
                var t = e.data("vendorid");
                $("#vsetupConfirmDeleteVendor").val(t);
            }
        ),
        $(document).on("click", "#vsetupConfirmDeleteVendor", function () {
            var e = $(this).attr("value");
            $("#vsetupDeleteVendorConfirmationModal").modal("hide"),
                $("#vsetupVendorTable").fadeOut(420, function () {
                    $.fn.DataTable.isDataTable("#vsetupVendorTable") &&
                        $("#vsetupVendorTable").DataTable().destroy(),
                        a(),
                        $(this).fadeIn(480);
                }),
                $.ajax({
                    async: !0,
                    crossDomain: !0,
                    url:
                        "http://localhost:8800/api/ssd/asn/vendorid-setup-delete/" +
                        e,
                    method: "GET",
                    headers: { Accept: "*/*" },
                })
                    .done(function (a) {
                        console.log(a);
                    })
                    .fail(function (a) {
                        console.error(a);
                    });
        }),
        $("#vsetupAddVendorButton").click(function () {
            var e = $("#vsetupVendorName").val(),
                t = $("#vsetupVendorID").val();
            let n = {
                async: !0,
                crossDomain: !0,
                url: "http://localhost:8800/api/ssd/asn/vendorid-setup-create",
                method: "POST",
                headers: { Accept: "*/*", "Content-Type": "application/json" },
                processData: !1,
                data: JSON.stringify({ v_vname: e, v_vid: t }),
            };
            $("#vsetupVendorTable").fadeOut(420, function () {
                $.fn.DataTable.isDataTable("#vsetupVendorTable") &&
                    $("#vsetupVendorTable").DataTable().destroy(),
                    a(),
                    $(this).fadeIn(480);
            }),
                $.ajax(n).done(function (a) {
                    console.log(a);
                }),
                $("#vsetupAddVendorModal").modal("hide");
        });
});
