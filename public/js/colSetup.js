$(document).ready(function () {
    var e,
        t,
        a,
        l = {},
        d = $("#table1").DataTable({
            paging: !0,
            pageLength: 10,
            searching: !0,
        }),
        n = $("#table2").DataTable({
            paging: !0,
            pageLength: 10,
            searching: !0,
        }),
        o = $("#table3").DataTable({
            paging: !0,
            pageLength: 10,
            searching: !0,
        }); //!Vendor ID
    //!-------------------------------------------------------------------------------------------------------------------------------------------->
    function i() {
        let e = $("#headersAddVendors"),
            t = $("#detailsAddVendors"),
            a = $("#lotsAddVendors");
        e.empty(),
            t.empty(),
            a.empty(),
            $.ajax({
                url: "http://localhost:8800/api/ssd/asn/vendorid-setup",
                type: "GET",
                success: function (l) {
                    let d = document.createDocumentFragment();
                    l.forEach(function (e) {
                        let t = new Option(e.v_vname, e.v_vname);
                        d.appendChild(t);
                    }),
                        e.append(d.cloneNode(!0)),
                        t.append(d.cloneNode(!0)),
                        a.append(d.cloneNode(!0));
                },
                error: function (e) {
                    console.error("Error fetching data:", e);
                },
            });
    } //!Vendor ID
    //!-------------------------------------------------------------------------------------------------------------------------------------------->
    //!Lots
    function s() {
        $.ajax({
            url: "http://localhost:8800/api/ssd/asn/vendorlots-setup",
            method: "GET",
            success: function (t) {
                (e = t), o.clear().draw();
                var a = t.map(function (e) {
                    var t = e.L_vendor,
                        a = e.L_file_type,
                        l = e.L_vid;
                    return [
                        t,
                        a,
                        '<button class="colSetup btn btn-success lotseditbtn" data-toggle="modal" data-target="#colSetuplots-editModal" data-vendorid="' +
                            l +
                            '">Edit</button> <button class="colSetup btn btn-danger lotsdelete" data-toggle="modal" data-target="#colSetuplots-deleteModal" data-vendorid="' +
                            l +
                            '">Delete</button>',
                    ];
                });
                o.rows.add(a).draw();
            },
            error: function (e) {
                console.error("Error fetching data:", e);
            },
        });
    } //!Lots
    //!-------------------------------------------------------------------------------------------------------------------------------------------->
    //!Details
    function r() {
        $.ajax({
            url: "http://localhost:8800/api/ssd/asn/vendordetail-setup",
            method: "GET",
            success: function (e) {
                (t = e), n.clear().draw();
                var a = e.map(function (e) {
                    var t = e.D_vendor,
                        a = e.D_file_type,
                        l = e.D_vid;
                    return [
                        t,
                        a,
                        '<button class="colSetup btn btn-success detailseditbtn" data-toggle="modal"data-target="#colSetupdetails-editModal" data-vendorid="' +
                            l +
                            '">Edit</button> <button class="colSetup btn btn-danger detailsdeletebtn" data-toggle="modal" data-target="#colSetupdetails-deleteModal" data-vendorid="' +
                            l +
                            '">Delete</button>',
                    ];
                });
                n.rows.add(a).draw();
            },
            error: function (e) {
                console.error("Error fetching data:", e);
            },
        });
    }
    function v(e, t) {
        $("#detailsEditVendorName").val(e.D_vendor),
            $("#detailsEditFileType").val(e.D_file_type),
            $("#detailsEditPrefix").val(e.D_Prefix),
            $("#detailsEditInvNo").val(e.D_InvNo),
            $("#detailsEditItemcode").val(e.D_ItemCode),
            $("#detailsEditItemName").val(e.D_ItemName),
            $("#detailsEditConvFact2").val(e.D_ConvFact2),
            $("#detailsEditUOM").val(e.D_UOM),
            $("#detailsEditUnitCost").val(e.D_UnitCost),
            $("#detailsEditQtyShip").val(e.D_QtyShip),
            $("#detailsEditQtyFree").val(e.D_QtyFree),
            $("#detailsEditGrossAmt").val(e.D_GrossAmt),
            $("#detailsEditPldAmt").val(e.D_PldAmt),
            $("#detailsEditNetAmt").val(e.D_NetAmt),
            $("#detailsEditSupCode").val(e.D_SupCode),
            $("#detailsEditSaveUpdateButton").val(t),
            $("#colSetupdetails-editModal").modal("show");
    } //!Details
    //!-------------------------------------------------------------------------------------------------------------------------------------------->
    //!Headers
    function u() {
        $.ajax({
            url: "http://localhost:8800/api/ssd/asn/vendorhead-setup",
            method: "GET",
            success: function (e) {
                (a = e), d.clear().draw();
                for (var t = 0; t < e.length; t++) {
                    var l = e[t].H_vendor,
                        n = e[t].H_file_type,
                        o = e[t].H_vid,
                        i =
                            '<button class=" colSetup btn btn-success headerseditbtn" data-toggle="modal" data-target="#colSetupheaders-editModal" data-vendorid="' +
                            o +
                            '">Edit</button>',
                        s =
                            '<button class="colSetup btn btn-danger headersdeletebtn" data-toggle="modal" data-target="#colSetupheaders-deleteModal" data-vendorid="' +
                            o +
                            '">Delete</button>';
                    d.row.add([l, n, i + " " + s]).draw(!1);
                }
            },
            error: function (e) {
                console.error("Error fetching data:", e);
            },
        });
    }
    $("#navcolSetup").click(function (e) {
        $("#asnView").removeClass("view-visible").addClass("view-hidden"),
            $("#asnExport").removeClass("view-visible").addClass("view-hidden"),
            $("#errorlogs").removeClass("view-visible").addClass("view-hidden"),
            $("#asnVid").removeClass("view-visible").addClass("view-hidden"),
            $("#duplogs").removeClass("view-visible").addClass("view-hidden"),
            $("#colSetup").removeClass("view-hidden").addClass("view-visible"),
            $("#duplogs").removeClass("view-visible").addClass("view-hidden"),
            u(),
            r(),
            s();
    }),
        u(),
        r(),
        s(),
        i(),
        $("#tab1-tab").click(function () {
            u();
        }),
        $("#tab2-tab").click(function () {
            r();
        }),
        $("#tab3-tab").click(function () {
            s();
        }),
        $("#table3").on("click", ".lotseditbtn", function () {
            var t = $(this).data("vendorid"),
                a = e.find(function (e) {
                    return e.L_vid == t;
                });
            a &&
                ($("#lotsEditVendorName").val(a.L_vendor),
                $("#lotsEditFileType").val(a.L_file_type),
                $("#lotsEditInvNo").val(a.L_InvNo),
                $("#lotsEditItemcode").val(a.L_ItemCode),
                $("#lotsEditLotNo").val(a.L_LotNo),
                $("#lotsEditExpiryMM").val(a.L_ExpiryMM),
                $("#lotsEditExpiryDD").val(a.L_ExpiryDD),
                $("#lotsEditExpiryYYYY").val(a.L_ExpiryYYYY),
                $("#lotsEditQty").val(a.L_Qty),
                $("#lotsEditSupCode").val(a.L_SupCode),
                $("#colSetuplots-editModal").modal("show")),
                $("#lotsEditSaveUpdateButton").val(t);
        }),
        $("#table3").on("click", ".lotsdelete", function () {
            var e = $(this).attr("data-vendorid");
            $("#lotsConfirmDeleteVendor").val(e),
                $("#colSetuplots-deleteModal").modal("show");
        }),
        $("#lotsAddSaveButton").click(function () {
            var e = $("#lotsAddVendors").val() || null,
                t = $("#lotsAddFileType").val() || null,
                a = $("#lotsAddInvNo").val() || null,
                l = $("#lotsAddItemcode").val() || null,
                d = $("#lotsAddLotNo").val() || null,
                n = $("#lotsAddExpiryMM").val() || null,
                o = $("#lotsAddExpiryDD").val() || null,
                i = $("#lotsAddExpiryYYYY").val() || null,
                r = $("#lotsAddQty").val() || null,
                v = $("#lotsAddSupCode").val() || null;
            let u = {
                async: !0,
                crossDomain: !0,
                url: "http://localhost:8800/api/ssd/asn/vendorlots-setup-create",
                method: "POST",
                headers: { Accept: "*/*", "Content-Type": "application/json" },
                processData: !1,
                data: JSON.stringify({
                    L_vendor: e,
                    L_file_type: t,
                    L_InvNo: a,
                    L_ItemCode: l,
                    L_LotNo: d,
                    L_ExpiryMM: n,
                    L_ExpiryDD: o,
                    L_ExpiryYYYY: i,
                    L_Qty: r,
                    L_SupCode: v,
                }),
            };
            $.ajax(u)
                .done(function (e) {
                    console.log(e);
                })
                .fail(function (e, t, a) {
                    console.error(a);
                })
                .always(function () {
                    s(),
                        $("#table3").fadeIn(480, function () {}),
                        $("#colSetuplots-addModal").modal("hide");
                });
        }),
        $("#lotsEditSaveUpdateButton").click(function () {
            var e = $(this).attr("value"),
                t = $("#lotsEditVendorName").val() || null,
                a = $("#lotsEditFileType").val() || null,
                l = $("#lotsEditInvNo").val() || null,
                d = $("#lotsEditItemcode").val() || null,
                n = $("#lotsEditLotNo").val() || null,
                o = $("#lotsEditExpiryMM").val() || null,
                i = $("#lotsEditExpiryDD").val() || null,
                r = $("#lotsEditExpiryYYYY").val() || null,
                v = $("#lotsEditQty").val() || null,
                u = $("#lotsEditSupCode").val() || null;
            let c = {
                async: !0,
                crossDomain: !0,
                url:
                    "http://localhost:8800/api/ssd/asn/vendorlots-setup-update/" +
                    e,
                method: "PUT",
                headers: { Accept: "*/*", "Content-Type": "application/json" },
                processData: !1,
                data: JSON.stringify({
                    L_vendor: t,
                    L_file_type: a,
                    L_InvNo: l,
                    L_ItemCode: d,
                    L_LotNo: n,
                    L_ExpiryMM: o,
                    L_ExpiryDD: i,
                    L_ExpiryYYYY: r,
                    L_Qty: v,
                    L_SupCode: u,
                }),
            };
            $.ajax(c)
                .done(function (e) {
                    console.log(e);
                })
                .fail(function (e, t, a) {
                    console.error(a);
                })
                .always(function () {
                    s(),
                        $("#table3").fadeIn(480, function () {}),
                        $("#colSetuplots-editModal").modal("hide");
                });
        }),
        $(document).on("click", "#lotsConfirmDeleteVendor", function () {
            var e = $(this).attr("value"),
                t = $("#table3").DataTable(),
                a = t.page(),
                l = t.page.len(),
                d = t.search();
            $("#colSetuplots-deleteModal").modal("hide"),
                $("#table3").fadeOut(420, function () {
                    $.fn.DataTable.isDataTable("#table3") &&
                        $("#table3").DataTable().destroy(),
                        s();
                    var e = $("#table3").DataTable();
                    e.page(a).draw(!1),
                        e.page.len(l).draw(),
                        e.search(d).draw(),
                        $(this).fadeIn(480);
                }),
                $.ajax({
                    async: !0,
                    crossDomain: !0,
                    url:
                        "http://localhost:8800/api/ssd/asn/vendorlots-setup-delete/" +
                        e,
                    method: "GET",
                    headers: { Accept: "*/*" },
                })
                    .done(function (e) {
                        console.log(e);
                    })
                    .fail(function (e, t, a) {
                        console.error(a);
                    });
        }), //! // Function to fetch data from the endpoint and update the table
        $("#table2").on("click", ".detailseditbtn", function () {
            var e = $(this).data("vendorid");
            if (l[e]) {
                var a = l[e];
                v(a);
            } else {
                var a = t.find(function (t) {
                    return t.D_vid == e;
                });
                a && ((l[e] = a), v(a, e));
            }
        }),
        $("#table2").on("click", ".detailseditbtn", function () {
            var e = $(this).data("vendorid"),
                a = t.find(function (t) {
                    return t.D_vid == e;
                });
            a &&
                ($("#detailsEditVendorName").val(a.D_vendor),
                $("#detailsEditFileType").val(a.D_file_type),
                $("#detailsEditPrefix").val(a.D_Prefix),
                $("#detailsEditInvNo").val(a.D_InvNo),
                $("#detailsEditItemcode").val(a.D_ItemCode),
                $("#detailsEditItemName").val(a.D_ItemName),
                $("#detailsEditConvFact2").val(a.D_ConvFact2),
                $("#detailsEditUOM").val(a.D_UOM),
                $("#detailsEditUnitCost").val(a.D_UnitCost),
                $("#detailsEditQtyShip").val(a.D_QtyShip),
                $("#detailsEditQtyFree").val(a.D_QtyFree),
                $("#detailsEditGrossAmt").val(a.D_GrossAmt),
                $("#detailsEditPldAmt").val(a.D_PldAmt),
                $("#detailsEditNetAmt").val(a.D_NetAmt),
                $("#detailsEditSupCode").val(a.D_SupCode),
                $("#detailsEditSaveUpdateButton").val(e),
                $("#colSetupdetails-editModal").modal("show"));
        }),
        $("#detailsEditSaveUpdateButton").click(function () {
            var e = $(this).attr("value"),
                t = $("#detailsEditVendorName").val() || null,
                a = $("#detailsEditFileType").val() || null,
                l = $("#detailsEditPrefix").val() || null,
                d = $("#detailsEditInvNo").val() || null,
                n = $("#detailsEditItemcode").val() || null,
                o = $("#detailsEditItemName").val() || null,
                i = $("#detailsEditConvFact2").val() || null,
                s = $("#detailsEditUOM").val() || null,
                v = $("#detailsEditUnitCost").val() || null,
                u = $("#detailsEditQtyShip").val() || null,
                c = $("#detailsEditQtyFree").val() || null,
                p = $("#detailsEditGrossAmt").val() || null,
                h = $("#detailsEditPldAmt").val() || null,
                m = $("#detailsEditNetAmt").val() || null,
                f = $("#detailsEditSupCode").val() || null;
            let D = {
                async: !0,
                crossDomain: !0,
                url:
                    "http://localhost:8800/api/ssd/asn/vendordetail-setup-update/" +
                    e,
                method: "PUT",
                headers: { Accept: "*/*", "Content-Type": "application/json" },
                processData: !1,
                data: JSON.stringify({
                    D_vendor: t,
                    D_file_type: a,
                    D_Prefix: l,
                    D_InvNo: d,
                    D_ItemCode: n,
                    D_ItemName: o,
                    D_ConvFact2: i,
                    D_UOM: s,
                    D_UnitCost: v,
                    D_QtyShip: u,
                    D_QtyFree: c,
                    D_GrossAmt: p,
                    D_PldAmt: h,
                    D_NetAmt: m,
                    D_SupCode: f,
                }),
            };
            $.ajax(D)
                .done(function (e) {
                    console.log(e);
                })
                .fail(function (e, t, a) {
                    console.error(a);
                })
                .always(function () {
                    $("#colSetupdetails-editModal").modal("hide"), r();
                });
        }),
        $("#table2").on("click", ".detailsdeletebtn", function () {
            var e = $(this).attr("data-vendorid");
            $("#detailsConfirmDeleteVendor").val(e),
                $("#colSetupdetails-deleteModal").modal("show");
        }),
        $("#detailAddSaveButton").click(function () {
            var e = $("#detailsAddVendors").val() || null,
                t = $("#detailsAddFileType").val() || null,
                a = $("#detailsAddPrefix").val() || null,
                l = $("#detailsAddInvNo").val() || null,
                d = $("#detailsAddItemcode").val() || null,
                n = $("#detailsAddItemName").val() || null,
                o = $("#detailsAddConvFact2").val() || null,
                i = $("#detailsAddUOM").val() || null,
                s = $("#detailsAddUnitCost").val() || null,
                v = $("#detailsAddQtyShip").val() || null,
                u = $("#detailsAddQtyFree").val() || null,
                c = $("#detailsAddGrossAmt").val() || null,
                p = $("#detailsAddPldAmt").val() || null,
                h = $("#detailsAddNetAmt").val() || null,
                m = $("#detailsAddSupCode").val() || null;
            let f = {
                async: !0,
                crossDomain: !0,
                url: "http://localhost:8800/api/ssd/asn/vendordetail-setup-create",
                method: "POST",
                headers: { Accept: "*/*", "Content-Type": "application/json" },
                processData: !1,
                data: JSON.stringify({
                    D_vendor: e,
                    D_file_type: t,
                    D_Prefix: a,
                    D_InvNo: l,
                    D_ItemCode: d,
                    D_ItemName: n,
                    D_ConvFact2: o,
                    D_UOM: i,
                    D_UnitCost: s,
                    D_QtyShip: v,
                    D_QtyFree: u,
                    D_GrossAmt: c,
                    D_PldAmt: p,
                    D_NetAmt: h,
                    D_SupCode: m,
                }),
            };
            $.ajax(f)
                .done(function (e) {
                    console.log(e),
                        r(),
                        $("#colSetupdetails-addModal").modal("hide");
                })
                .fail(function (e, t, a) {
                    console.error(a);
                });
        }),
        $(document).on("click", "#detailsConfirmDeleteVendor", function () {
            var e = $(this).attr("value"),
                t = $("#table2").DataTable(),
                a = t.page(),
                l = t.page.len(),
                d = t.search();
            $("#colSetupdetails-deleteModal").modal("hide"),
                $("#table2").fadeOut(420, function () {
                    $.fn.DataTable.isDataTable("#table2") &&
                        $("#table2").DataTable().destroy(),
                        r();
                    var e = $("#table2").DataTable();
                    e.page(a).draw(!1),
                        e.page.len(l).draw(),
                        e.search(d).draw(),
                        $(this).fadeIn(480);
                }),
                $.ajax({
                    async: !0,
                    crossDomain: !0,
                    url:
                        "http://10.91.100.145:8800/api/ssd/asn/vendordetail-setup-delete/" +
                        e,
                    method: "GET",
                    headers: { Accept: "*/*" },
                })
                    .done(function (e) {
                        console.log(e);
                    })
                    .fail(function (e, t, a) {
                        console.error(a);
                    });
        }),
        $("#detailAddModalBtn").on("click", function (e) {
            i(),
                $("#detailsAddVendors").val(null),
                $("#detailsAddFileType").val(null),
                $("#detailsAddPrefix").val(null),
                $("#detailsAddInvNo").val(null),
                $("#detailsAddItemcode").val(null),
                $("#detailsAddItemName").val(null),
                $("#detailsAddConvFact2").val(null),
                $("#detailsAddUOM").val(null),
                $("#detailsAddUnitCost").val(null),
                $("#detailsAddQtyShip").val(null),
                $("#detailsAddQtyFree").val(null),
                $("#detailsAddGrossAmt").val(null),
                $("#detailsAddPldAmt").val(null),
                $("#detailsAddNetAmt").val(null),
                $("#detailsAddSupCode").val(null);
        }),
        $("#table1").on("click", ".headerseditbtn", function () {
            var e = $(this).data("vendorid"),
                t = a.find(function (t) {
                    return t.H_vid == e;
                });
            t &&
                ($("#headersEditName").val(t.H_vendor),
                $("#headersEditFileType").val(t.H_file_type),
                $("#headersEditInvNo").val(t.H_InvNo),
                $("#headersEditInvDate").val(t.H_InvDate),
                $("#headersEditInvAmt").val(t.H_InvAmt),
                $("#headersEditDiscAmt").val(t.H_DiscAmt),
                $("#headersEditStkFlag").val(t.H_StkFlag),
                $("#headersEditVendorID").val(t.H_VendorID),
                $("#headersEditVendorName").val(t.H_VendorName),
                $("#headersEditPORef").val(t.H_PORef),
                $("#headersSupCode").val(t.H_SupCode),
                $("#colSetupheaders-editModal").modal("show"),
                $("#headerEditSaveUpdateButton").val(e),
                console.log(t.H_vendor));
        }),
        $("#headerEditSaveUpdateButton").click(function () {
            var e = $(this).attr("value"),
                t = $("#headersEditName").val() || null,
                a = $("#headersEditFileType").val() || null,
                l = $("#headersEditInvNo").val() || null,
                d = $("#headersEditInvDate").val() || null,
                n = $("#headersEditInvAmt").val() || null,
                o = $("#headersEditDiscAmt").val() || null,
                i = $("#headersEditStkFlag").val() || null,
                s = $("#headersEditVendorID").val() || null,
                r = $("#headersEditVendorName").val() || null,
                v = $("#headersEditPORef").val() || null,
                c = $("#headersSupCode").val() || null;
            let p = {
                async: !0,
                crossDomain: !0,
                url:
                    "http://localhost:8800/api/ssd/asn/vendorhead-setup-update/" +
                    e,
                method: "PUT",
                headers: { Accept: "*/*", "Content-Type": "application/json" },
                processData: !1,
                data: JSON.stringify({
                    H_vendor: t,
                    H_file_type: a,
                    H_InvNo: l,
                    H_InvDate: d,
                    H_InvAmt: n,
                    H_DiscAmt: o,
                    H_StkFlag: i,
                    H_VendorID: s,
                    H_VendorName: r,
                    H_PORef: v,
                    H_SupCode: c,
                }),
            };
            $.ajax(p)
                .done(function (e) {
                    console.log(e);
                })
                .fail(function (e, t, a) {
                    console.error(a);
                })
                .always(function () {
                    u(),
                        $("#table1").fadeIn(480, function () {}),
                        $("#colSetupheaders-editModal").modal("hide");
                });
        }),
        $("#headerAddSaveButton").click(function () {
            var e = $("#headersAddVendors").val() || null,
                t = $("#headersAddFileType").val() || null,
                a = $("#headersAddInvNo").val() || null,
                l = $("#headersAddInvDate").val() || null,
                d = $("#headersAddInvAmt").val() || null,
                n = $("#headersAddDiscAmt").val() || null,
                o = $("#headersAddStkFlag").val() || null,
                i = $("#headersAddVendorID").val() || null,
                s = $("#headersAddVendorName").val() || null,
                r = $("#headersAddPORef").val() || null,
                v = $("#headersAddSupCode").val() || null;
            let c = {
                async: !0,
                crossDomain: !0,
                url: "http://localhost:8800/api/ssd/asn/vendorhead-setup-create",
                method: "POST",
                headers: { Accept: "*/*", "Content-Type": "application/json" },
                processData: !1,
                data: JSON.stringify({
                    H_vendor: e,
                    H_file_type: t,
                    H_InvNo: a,
                    H_InvDate: l,
                    H_InvAmt: d,
                    H_DiscAmt: n,
                    H_StkFlag: o,
                    H_VendorID: i,
                    H_VendorName: s,
                    H_PORef: r,
                    H_SupCode: v,
                }),
            };
            $.ajax(c)
                .done(function (e) {
                    console.log(e);
                })
                .fail(function (e, t, a) {
                    console.error(a);
                })
                .always(function () {
                    u(),
                        $("#table1").fadeIn(480, function () {}),
                        $("#colSetupheaders-addModal").modal("hide");
                });
        }),
        $("#colSetupheaders-deleteModal").on("show.bs.modal", function (e) {
            var t = $(e.relatedTarget).data("vendorid");
            $("#headersConfirmDeleteVendor").val(t);
        }),
        $(document).on("click", "#headersConfirmDeleteVendor", function () {
            var e = $(this).attr("value"),
                t = $("#table1").DataTable(),
                a = t.page(),
                l = t.page.len(),
                d = t.search();
            $("#colSetupheaders-deleteModal").modal("hide"),
                $("#table1").fadeOut(420, function () {
                    $.fn.DataTable.isDataTable("#table1") &&
                        $("#table1").DataTable().destroy(),
                        u();
                    var e = $("#table1").DataTable();
                    e.page(a).draw(!1),
                        e.page.len(l).draw(),
                        e.search(d).draw(),
                        $(this).fadeIn(480);
                }),
                $.ajax({
                    async: !0,
                    crossDomain: !0,
                    url:
                        "http://localhost:8800/api/ssd/asn/vendorhead-setup-delete/" +
                        e,
                    method: "GET",
                    headers: { Accept: "*/*" },
                })
                    .done(function (e) {
                        console.log(e);
                    })
                    .fail(function (e, t, a) {
                        console.error(a);
                    });
        }),
        $("#headerAddModalBtn").on("click", function (e) {
            i(),
                $("#headersAddVendors").val(null),
                $("#headersAddFileType").val(null),
                $("#headersAddInvNo").val(null),
                $("#headersAddInvDate").val(null),
                $("#headersAddInvAmt").val(null),
                $("#headersAddDiscAmt").val(null),
                $("#headersAddStkFlag").val(null),
                $("#headersAddVendorID").val(null),
                $("#headersAddVendorName").val(null),
                $("#headersAddPORef").val(null),
                $("#headersAddSupCode").val(null);
        }); //!Headers
    //!-------------------------------------------------------------------------------------------------------------------------------------------->
});
