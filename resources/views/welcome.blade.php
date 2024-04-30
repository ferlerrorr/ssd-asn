<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/x-icon" href="{{URL::asset('img/fav.png') }}">
		<script src="{{URL::asset('js/jquery.js') }}"></script>
		<script src="{{URL::asset('js/datables.js') }}"></script>
		<script src='https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js' defer></script>
		<script src="https://kit.fontawesome.com/a5b3b870d7.js" crossorigin="anonymous" defer></script>
		<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" defer></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" defer></script>


        <title>Advance Shipping Notice</title>
    </head>
    <body style="display: flex; flex-direction:row;">
        <link rel="stylesheet" type="text/css" href="{{URL::asset('css/data-table.css')}}">
		<link rel="stylesheet" type="text/css" href="{{URL::asset('css/form3.css') }}">
		<link rel="stylesheet" type="text/css" href="{{URL::asset('css/sidenav.css')}}">
	<div class="sidenav">
		<link rel="stylesheet"style="display: none;">

		<div class="logo-container">
			<img class="ssd-logo to-home" id="ssd-logo" src="{{URL::asset('img/ssd-logo-white.png') }}"alt="ssd-logo" style="width: 250px; height:44px;">
		</div>
		<aside class="sidebar">
			<div id="leftside-navigation">
				<ul class="level-0">
					<li class="parent" style="border-bottom: 1.2px solid rgba(255, 255, 255, 0.09);">
						<a href="#"><span>ASN</span><i class="arrow fa fa-angle-right"></i></a>
						<ul class="level-1">
							<li class="parent" id="link">
								<a href="#" id="navAsn"><span>Import ASN</span>
								</a>

							</li>
							<li class="parent" id="link">
								<a href="#" id="navExport"><span>Export ASN</span>
								</a>

							</li>
						</ul>
					</li>
					<li class="parent" style="border-bottom: 1.2px solid rgba(255, 255, 255, 0.09);">
						<a href="#"><span>Vendor Maintenance</span><i class="arrow fa fa-angle-right"></i></a>
						<ul class="level-1">
							<li class="parent" id="link">
								<a href="#" id="navVid"><span>ID Setup</span>
								</a>

							</li>
							<li class="parent" id="link">
								<a href="#" id="navcolSetup"><span>Column Setup</span>
								</a>

							</li>

						</ul>
					</li>
					<li class="parent" id="link">
						<a href="#" id="navDuplogs"><span>Duplicate PO</span>
						</a>

					</li>

					<li class="parent" id="link">
						<a href="#" id="navErrlogs"><span>Error Logs</span></a>
					</li>
				</ul>
			</div>
		</aside>
		<script src="{{URL::asset('js/sidenav.js') }}"></script>
	</div>

	<!-- partial:Page ASN.html -->
	<div class="page-content ASN " id="asnView">

		<div class="view-header">
			<div class="form-container">

				<div class="vnd-cont"><select name="vendors" id="vendors" form="vendors">
				</select><div class="ld-count-cont" id="ld-count-cont"><p class="form-stats">Status</p><p id="ld-neumen"></p><p id="ld-denom"></p></div></div>

				<form method="post" id="import_asn" enctype="multipart/form-data">
					<input type="file" id="upload-asn" class="custom-file-input up" name="import_asn">
					<input type="submit" name="import-asn" id="import-asn" class="btn btn-primary-asn up"
						value="Upload" />
					<input type="button" class="btn btn-clear-asn up" value="Clear" id="clear" />
				</form>
			</div>

			<h1>
				Advance Shipping Notice
			</h1>
			<script src="{{URL::asset('js/formv13.js') }}"></script>
            <script src="{{URL::asset('js/vendor.js') }}"></script>
		</div>



		<!-- partial:data-table.partial.html -->
		<div class="table-reponsive box">
			<textarea class="dataiframe" id="notif"></textarea>
		</div>
		<!-- partial:data-table.partial.html -->
	</div>
	<!-- partial:Page ASN.html -->



	<!-- partial:Page Export.html -->
	<div class="page-content Export" id="asnExport">
		<div class=" view-header">
			<h1>
				Export
			</h1>
            <script src="{{URL::asset('js/export1.js') }}"></script>
		</div>
		<!-- partial:data-table.partial.html -->
		<div class="table-reponsive box" style="display: flex; flex-direction: column; gap:17px; padding-top:32px; ">
			<textarea class="dataiframe" id="exportcsv"
				style="width: 30vw; height: 35vh;  border: 1px solid #ced4daee;"></textarea>
			<div style="display: flex;
			gap: 20px;
			align-items: baseline;"><input type="submit" name="export-asn" id="export-asn" class="btn btn-primary-asn up" value="Export" style="width: 8em
				; height: 2.9em;" />
			<p style="margin: 8px 0 0 0;
			letter-spacing: 2px;
			font-weight: 600; display:none; " id="export-notif">Notification</p></div>

		</div>
		<!-- partial:data-table.partial.html -->
	</div>
	<!-- partial:Page Export.html -->

	<!-- partial:Page Error logs .html -->
	<div class="page-content Errorlogs" id="errorlogs" ">
		<div class=" view-header">
		<h1>
			Error logs
		</h1>
        <script src="{{URL::asset('js/errorlogs.js') }}"></script>
	</div>
	<div style="margin: 40px 75px 40px 75px;">
		<table id="error-log" class="table table-striped table-bordered">

			<thead class="table-header">
				<tr>
					<th>Vendor</th>
					<th>Datetime </th>
					<th>Download Logs</th>
				</tr>
			</thead>

			<tbody class="table-body" id="tb-body">


				<!--
					Data - Tr and Td Dyanamically Generated
				-->

			</tbody>
		</table>
	</div>
	</div>
	<!-- partial:Page Export.html -->


<!-- partial:Page dup  logs .html -->
<div class="page-content Duplogs" id="duplogs" ">
	<div class=" view-header">
	<h1>
		Duplicate logs
	</h1>
	{{-- <script src="{{URL::asset('js/errorlogs.js') }}"></script> --}}
</div>
<div style="margin: 40px 75px 40px 75px;">
	<table id="dup-log" class="table table-striped table-bordered">

		<thead class="table-header">
			<tr>
				<th>Duplicate PO</th>
				<th>Datetime</th>
				<th>Download Duplicate PO</th>
			</tr>
		</thead>

		<tbody class="table-body" id="tb-body">


			<!--
				Data - Tr and Td Dyanamically Generated
			-->

		</tbody>
	</table>
</div>
</div>
<!-- partial:Page Export.html -->









	<!-- partial:Page Vid.html -->
	<div class="page-content asnVid" id="asnVid"
		style="overflow-y: auto; flex-direction: column;  align-items: center;">
		<div class=" view-header">
			<!-- <link rel="stylesheet" href="vid\vid.css"> -->
			<h1>
				Vendor Id Setup
			</h1>
		</div>
		<div class="table-cont">
			<!-- Add Vendor Button -->
			<button type="button" class="btn btn-primary addvid" data-toggle="modal"
				data-target="#vsetupAddVendorModal">Add
				Vendor</button>

			<table id="vsetupVendorTable" class="table table-bordered" ">
		</div>
		<thead>
			<tr>
				<th>Vendor Name</th>
				<th>Vendor ID</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody>
			<!-- Data will be loaded here via JavaScript -->
		</tbody>
		</table>
	</div>

	<!-- Add Vendor Modal -->
	<div class=" modal fade" id="vsetupAddVendorModal" tabindex="-1" role="dialog"
				aria-labelledby="vsetupAddVendorModalLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="vsetupAddVendorModalLabel">Add Vendor</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<!-- Add Vendor form content here -->
							<form id="vsetupAddVendorForm">
								<div class="form-group input-container">
									<label for="vsetupVendorName">Vendor Name</label>
									<input type="text" class="form-control" id="vsetupVendorName"
										name="vsetupVendorName" placeholder="Enter Vendor Name">
								</div>
								<div class="form-group input-container">
									<label for="vsetupVendorID">Vendor ID</label>
									<input type="text" class="form-control" id="vsetupVendorID" name="vsetupVendorID"
										placeholder="Enter Vendor ID">
								</div>
							</form>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							<button type="button" class="btn btn-primary" id="vsetupAddVendorButton">Add</button>
						</div>
					</div>
				</div>
		</div>

		<!-- Edit Vendor Modal -->
		<div class="modal fade" id="vsetupEditVendorModal" tabindex="-1" role="dialog"
			aria-labelledby="vsetupEditVendorModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="vsetupEditVendorModalLabel">Edit Vendor</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<!-- Edit Vendor form content here -->
						<form id="vsetupEditVendorForm">
							<div class="form-group input-container">
								<label for="vsetupEditVendorName">Vendor Name</label>
								<input type="text" class="form-control" id="vsetupEditVendorName"
									name="vsetupEditVendorName" placeholder="Enter Vendor Name">
							</div>
							<div class="form-group input-container">
								<label for="vsetupEditVendorID">Vendor ID</label>
								<input type="text" class="form-control" id="vsetupEditVendorID"
									name="vsetupEditVendorID" placeholder="Enter Vendor ID">
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary" id="vsetupSaveEditVendorButton"
							data-vendorid="">Save
							Changes</button>
					</div>
				</div>
			</div>
		</div>

		<!-- Delete Vendor Confirmation Modal -->
		<div class="modal fade" id="vsetupDeleteVendorConfirmationModal" tabindex="-1" role="dialog"
			aria-labelledby="vsetupDeleteVendorConfirmationModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="vsetupDeleteVendorConfirmationModalLabel">Confirm Deletion</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body delete-msg">
						Are you sure you want to delete this vendor?
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
						<!-- Modify the vsetupConfirmDeleteVendor button to include data-vendorid -->
						<button type="button" class="btn btn-danger" id="vsetupConfirmDeleteVendor"
							data-vendorid="">Delete</button>
					</div>
				</div>
			</div>
		</div>
		<script src="{{URL::asset('js/vid.js') }}"></script>
	</div>
	<!--! partial:Page Vid.html -->


	<!-- !partial:Page Colsetup.html -->
	<!-- ? Universal Modals Headers-->
	<!-- todo Tab 1 Content Headers -->
	<div class="page-content colSetup" id="colSetup">

		<div class="container mt-4 colSetup-Header">
			<h1>
				Vendor Column Setup
			</h1>
			<ul class=" colSetup nav nav-tabs" id="myTabs" role="tablist">
				<li class=" colSetupnav-item">
					<a class="colSetup nav-link active" id="tab1-tab" data-toggle="tab" href="#tab1" role="tab"
						aria-controls="tab1" aria-selected="true">Header</a>
				</li>
				<li class="colSetup nav-item">
					<a class=" colSetup nav-link" id="tab2-tab" data-toggle="tab" href="#tab2" role="tab"
						aria-controls="tab2" aria-selected="false">Detailed</a>
				</li>
				<li class="colSetup nav-item">
					<a class="colSetup nav-link" id="tab3-tab" data-toggle="tab" href="#tab3" role="tab"
						aria-controls="tab3" aria-selected="false">Lots</a>
				</li>
			</ul>

			<!-- Tab panes -->
			<div class="colSetup tab-content mt-4 dt-colSetup">
				<!-- Tab 1 Content -->
				<div class="colSetup tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="tab1-tab">
					<button class="colSetup btn btn-primary addcol" data-toggle="modal"
						data-target="#colSetupheaders-addModal" id="headerAddModalBtn">Add Header</button>
					<table class="colSetup table table-bordered" id="table1">
						<thead>
							<tr>
								<th>Vendor Name</th>
								<th>File Type</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>

						</tbody>
					</table>
				</div>

				<!-- ? Universal Modals Headers-->
				<div class="colSetup modal fade" id="colSetupheaders-addModal" tabindex="-1" role="dialog"
					aria-labelledby="addModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="addModalLabel">Add Vendor Header</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<div class="input-container">
									<label for="headersAddVendors">Vendors</label>
									<select name="headersVendors" class="form-control " id="headersAddVendors">
									</select>
								</div>
								<div class="input-container">
									<label for="headersAddFileType">File Type</label>
									<select name="headersAddFileType" class="form-control " id="headersAddFileType">
										<option value=""></option>
										<option value="csv_file">CSV File</option>
										<option value="text_file">Text File</option>
									</select>
								</div>
								<div class="input-container">
									<label for="headersAddInvNo">InvNo</label>
									<input type="text" class="form-control" name="headersAddInvNo" id="headersAddInvNo">
								</div>
								<div class="input-container">
									<label for="headersAddInvDate">InvDate</label>
									<input type="text" class="form-control" name="headersAddInvDate"
										id="headersAddInvDate">
								</div>
								<div class="input-container">
									<label for="headersAddInvAmt">InvAmt</label>
									<input type="text" class="form-control" name="headersAddInvAmt"
										id="headersAddInvAmt">
								</div>
								<div class="input-container">
									<label for="headersAddDiscAmt">DiscAmt</label>
									<input type="text" class="form-control" name="headersAddDiscAmt"
										id="headersAddDiscAmt">
								</div>
								<div class="input-container">
									<label for="headersAddStkFlag">StkFlag</label>
									<input type="text" class="form-control" name="headersAddStkFlag"
										id="headersAddStkFlag">
								</div>

								<div class="input-container">
									<label for="headersAddVendorID">VendorID</label>
									<input type="text" class="form-control" name="headersAddVendorID"
										id="headersAddVendorID">
								</div>

								<div class="input-container">
									<label for="headersAddVendorName">VendorName</label>
									<input type="text" class="form-control" name="headersAddVendorName"
										id="headersAddVendorName">
								</div>

								<div class="input-container" style="margin-bottom: 0;">
									<label for="headersAddPORef">PORef</label>
									<input type="text" class="form-control" name="headersAddPORef" id="headersAddPORef">
								</div>
								<div class="input-container">
									<label for="headersAddSupCode">SupCode</label>
									<input type="text" class="form-control" id="headersAddSupCode">
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="headerAddSaveButton">Save Vendor
									Header</button>
							</div>
						</div>
					</div>
				</div>


				<div class="colSetup modal fade" id="colSetupheaders-editModal" tabindex="-1" role="dialog"
					aria-labelledby="editModalLabel" aria-hidden="true">
					<script src="{{URL::asset('js/colSetup.js') }}"></script>
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="editModalLabel">Header Update Column Setup</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<div class="input-container">
									<label for="headersEditVendorName">Vendor</label>
									<input type="text" class="form-control" id="headersEditName">
								</div>
								<div class="input-container">
									<label for="headersEditFileType">File Type</label>
									<select name="headersEditFileType" class="form-control " id="headersEditFileType">
										<option value=""></option>
										<option value="csv_file">CSV File</option>
										<option value="text_file">Text File</option>
									</select>
								</div>
								<div class="input-container">
									<label for="headersEditInvNo">InvNo</label>
									<input type="text" class="form-control" id="headersEditInvNo"
										name="headersEditInvNo">
								</div>
								<div class=" input-container">
									<label for="headersEditInvDate">InvDate</label>
									<input type="text" class="form-control" id="headersEditInvDate"
										name="headersEditInvDate">
								</div>
								<div class="input-container">
									<label for="headersEditInvAmt">InvAmt</label>
									<input type="text" class="form-control" id="headersEditInvAmt"
										name="headersEditInvAmt">
								</div>
								<div class="input-container">
									<label for="headersEditDiscAmt">DiscAmt</label>
									<input type="text" class="form-control" id="headersEditDiscAmt"
										name="headersEditDiscAmt">
								</div>
								<div class="input-container">
									<label for="headersEditStkFlag">StkFlag</label>
									<input type="text" class="form-control" id="headersEditStkFlag"
										name="headersEditStkFlag">
								</div>

								<div class="input-container">
									<label for="headersEditVendorID">VendorID</label>
									<input type="text" class="form-control" id="headersEditVendorID"
										name="headersEditVendorID">
								</div>

								<div class="input-container">
									<label for="headersEditVendorName">VendorName</label>
									<input type="text" class="form-control" id="headersEditVendorName"
										name="headersEditVendorName">
								</div>
								<div class="input-container" style="margin-bottom:0;">
									<label for="headersEditPORef">PORef</label>
									<input type="text" class="form-control" id="headersEditPORef"
										name="headersEditPORef">
								</div>
								<div class="input-container">
									<label for="headersSupCode">SupCode</label>
									<input type="text" class="form-control" id="headersSupCode" name="headersSupCode">
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
									<button type="button" class="btn btn-primary" id="headerEditSaveUpdateButton">Save
										Changes</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="colSetup modal fade" id="colSetupheaders-deleteModal" tabindex="-1" role="dialog"
					aria-labelledby="deleteModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="deleteModalLabel">Delete Entry</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body delete-msg">
								Are you sure you want to delete this entry?
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
								<button type="button" class="btn btn-danger"
									id="headersConfirmDeleteVendor">Delete</button>
							</div>
						</div>
					</div>
				</div>
				<!-- ? Universal Modals Headers-->
				<!-- todo Tab 1 Content Headers -->


				<!-- todo Tab 2 Content Details -->
				<div class="colSetup tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab2-tab">
					<button class=" colSetup btn btn-primary mb-2 addcol" data-toggle="modal"
						data-target="#colSetupdetails-addModal" id="detailAddModalBtn">Add
						Entry</button>
					<table class=" colSetup table table-bordered" id="table2">
						<thead>
							<tr>
								<th>Vendor Name</th>
								<th>File Type</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							<!-- <tr>
								<td>Data A1</td>
								<td>Data A2</td>
								<td>
									<button class=" colSetup btn btn-success detailseditbtn" data-toggle="modal"
										data-target="#colSetupdetails-editModal">Edit</button>
									<button class="colSetup btn btn-danger detailsdeletebtn" data-toggle="modal"
										data-target="#colSetupdetails-deleteModal">Delete</button>
								</td>
							</tr> -->

						</tbody>
					</table>
				</div>
				<!-- ? Universal Modals Details-->
				<div class="colSetup modal fade" id="colSetupdetails-addModal" tabindex="-1" role="dialog"
					aria-labelledby="addModalLabel" aria-hidden="true" style="top: -18px;">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="addModalLabel">Add Entry</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<div class="input-container">
									<label for="detailsAddVendors">Vendors</label>
									<select name="detailsVendors" class="form-control " id="detailsAddVendors">
									</select>
								</div>
								<div class="input-container">
									<label for="detailsAddFileType">File Type</label>
									<select name="detailsAddFileType" class="form-control " id="detailsAddFileType"
										name="detailsAddFileType">
										<option value=""></option>
										<option value="csv_file">CSV File</option>
										<option value="text_file">Text File</option>
									</select>
								</div>
								<div class="input-container">
									<label for="detailsAddPrefix">Prefix</label>
									<input type="text" class="form-control" id="detailsAddPrefix"
										name="detailsAddPrefix">
								</div>
								<div class="input-container">
									<label for="detailsAddInvNo">InvNo</label>
									<input type="text" class="form-control" id="detailsAddInvNo" name="detailsAddInvNo">
								</div>
								<div class="input-container">
									<label for="detailsAddItemcode">Itemcode</label>
									<input type="text" class="form-control" id="detailsAddItemcode"
										name="detailsAddItemcode">
								</div>
								<div class="input-container">
									<label for="detailsAddItemName">ItemName</label>
									<input type="text" class="form-control" id="detailsAddItemName"
										name="detailsAddItemName">
								</div>
								<div class="input-container">
									<label for="detailsAddConvFact2">ConvFact2</label>
									<input type="text" class="form-control" id="detailsAddConvFact2"
										name="detailsAddConvFact2">
								</div>
								<div class="input-container">
									<label for="detailsAddUOM">UOM</label>
									<input type=" text" class="form-control" id="detailsAddUOM" name="detailsAddUOM">
								</div>
								<div class="input-container">
									<label for="detailsAddUnitCost">UnitCost</label>
									<input type="text" class="form-control" id="detailsAddUnitCost"
										name="detailsAddUnitCost">
								</div>
								<div class="input-container">
									<label for="detailsAddQtyShip">QtyShip</label>
									<input type="text" class="form-control" id="detailsAddQtyShip"
										name="detailsAddQtyShip">
								</div>
								<div class="input-container">
									<label for="detailsAddQtyFree">QtyFree</label>
									<input type="text" class="form-control" id="detailsAddQtyFree"
										name="detailsAddQtyFree">
								</div>
								<div class="input-container">
									<label for="detailsAddGrossAmt">GrossAmt</label>
									<input type="text" class="form-control" id="detailsAddGrossAmt"
										name="detailsAddGrossAmt">
								</div>
								<div class="input-container">
									<label for="detailsAddPldAmt">PldAmt</label>
									<input type="text" class="form-control" id="detailsAddPldAmt"
										name="detailsAddPldAmt">
								</div>
								<div class="input-container" style="margin-bottom:0;">
									<label for="detailsAddNetAmt">NetAmt</label>
									<input type="text" class="form-control" id="detailsAddNetAmt"
										name="detailsAddNetAmt">
								</div>
								<div class="input-container">
									<label for="detailsAddSupCode">SupCode</label>
									<input type="text" class="form-control" id="detailsAddSupCode"
										name="detailsAddSupCode">
								</div>

							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="detailAddSaveButton">Add</button>
							</div>
						</div>
					</div>
				</div>

				<div class="colSetup modal fade" id="colSetupdetails-editModal" tabindex="-1" role="dialog"
					aria-labelledby="editModalLabel" aria-hidden="true" style="top: -18px;">
					<div class=" modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="editModalLabel">Detailed Update Column Setup</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<div class="input-container">
									<label for="detailsEditVendorName">Vendor</label>
									<input type="text" class="form-control" id="detailsEditVendorName"
										name="detailsEditVendorName">
								</div>
								<div class="input-container">
									<label for="detailsEditFileType">File Type</label>
									<select name="detailsEditFileType" class="form-control " id="detailsEditFileType">
										<option value=""></option>
										<option value="csv_file">CSV File</option>
										<option value="text_file">Text File</option>
									</select>
								</div>
								<div class="input-container">
									<label for="detailsEditPrefix">Prefix</label>
									<input type="text" class="form-control" id="detailsEditPrefix"
										name="detailsEditPrefix">
								</div>
								<div class="input-container">
									<label for="detailsEditInvNo">InvNo</label>
									<input type="text" class="form-control" id="detailsEditInvNo"
										name="detailsEditInvNo">
								</div>
								<div class="input-container">
									<label for="detailsEditItemcode">Itemcode</label>
									<input type="text" class="form-control" id="detailsEditItemcode"
										name="detailsEditItemcode">
								</div>
								<div class="input-container">
									<label for="detailsEditItemName">ItemName</label>
									<input type="text" class="form-control" id="detailsEditItemName"
										name="detailsEditItemName">
								</div>
								<div class="input-container">
									<label for="detailsEditConvFact2">ConvFact2</label>
									<input type="text" class="form-control" id="detailsEditConvFact2"
										name="detailsEditConvFact2">
								</div>
								<div class="input-container">
									<label for="detailsEditUOM">UOM</label>
									<input type="text" class="form-control" id="detailsEditUOM" name="detailsEditUOM">
								</div>
								<div class="input-container">
									<label for="detailsEditUnitCost">UnitCost</label>
									<input type="text" class="form-control" id="detailsEditUnitCost"
										name="detailsEditUnitCost">
								</div>
								<div class="input-container">
									<label for="detailsEditQtyShip">QtyShip</label>
									<input type="text" class="form-control" id="detailsEditQtyShip"
										name="detailsEditQtyShip">
								</div>
								<div class="input-container">
									<label for="detailsEditQtyFree">QtyFree</label>
									<input type="text" class="form-control" id="detailsEditQtyFree"
										name="detailsEditQtyFree">
								</div>
								<div class="input-container">
									<label for="detailsEditGrossAmt">GrossAmt</label>
									<input type="text" class="form-control" id="detailsEditGrossAmt"
										name="detailsEditGrossAmt">
								</div>
								<div class="input-container">
									<label for="detailsEditPldAmt">PldAmt</label>
									<input type="text" class="form-control" id="detailsEditPldAmt"
										name="detailsEditPldAmt">
								</div>
								<div class="input-container" style="margin-bottom:0;">
									<label for="detailsEditNetAmt">NetAmt</label>
									<input type="text" class="form-control" id="detailsEditNetAmt"
										name="detailsEditNetAmt">
								</div>
								<div class="input-container">
									<label for="detailsEditSupCode">SupCode</label>
									<input type="text" class="form-control" id="detailsEditSupCode"
										name="detailsEditSupCode">
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
									<button type="button" class="btn btn-primary" id="detailsEditSaveUpdateButton">Save
										Changes</button>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="colSetup modal fade" id="colSetupdetails-deleteModal" tabindex="-1" role="dialog"
					aria-labelledby="deleteModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="deleteModalLabel">Delete Entry</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body delete-msg">
								Are you sure you want to delete this entry?
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
								<button type="button" class="btn btn-danger"
									id="detailsConfirmDeleteVendor">Delete</button>
							</div>
						</div>
					</div>
				</div>
				<!-- ? Universal Modals Details-->
				<!-- todo Tab 2 Content Details -->


				<!-- Todo Tab 3 Content lots -->
				<div class="colSetup tab-pane fade" id="tab3" role="tabpanel" aria-labelledby="tab3-tab">
					<button class="colSetup btn btn-primary mb-2 addcol" data-toggle="modal"
						data-target="#colSetuplots-addModal">Add
						Entry</button>
					<table class=" colSetup table table-bordered" id="table3">
						<thead>
							<tr>
								<th>Vendor Name</th>
								<th>File Type</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>

						</tbody>
					</table>
				</div>
			</div>
		</div>

		<!-- Universal Modals Lots-->
		<div class="colSetup modal fade" id="colSetuplots-addModal" tabindex="-1" role="dialog"
			aria-labelledby="addModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="addModalLabel">Add Entry</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="input-container">
							<label for="lotsAddVendors">Vendors</label>
							<select name="lotsAddVendors" class="form-control " id="lotsAddVendors">
							</select>
						</div>
						<div class="input-container">
							<label for="lotsAddFileType">File Type</label>
							<select name="lotsAddFileType" class="form-control " id="lotsAddFileType">
								<option value=""></option>
								<option value="csv_file">CSV File</option>
								<option value="text_file">Text File</option>
							</select>
						</div>
						<div class="input-container">
							<label for="lotsAddInvNo">InvNo</label>
							<input type="text" class="form-control" id="lotsAddInvNo" name="lotsAddInvNo">
						</div>
						<div class="input-container">
							<label for="lotsAddItemcode">Itemcode</label>
							<input type="text" class="form-control" id="lotsAddItemcode" name="lotsAddItemcode">
						</div>
						<div class="input-container">
							<label for="lotsAddLotNo">LotNo</label>
							<input type="text" class="form-control" id="lotsAddLotNo" name="lotsAddLotNo">
						</div>
						<div class="input-container">
							<label for="lotsAddExpiryMM">ExpiryMM</label>
							<input type="text" class="form-control" id="lotsAddExpiryMM" name="lotsAddExpiryMM">
						</div>
						<div class="input-container">
							<label for="lotsAddExpiryDD">ExpiryDD</label>
							<input type="text" class="form-control" id="lotsAddExpiryDD" name="lotsAddExpiryDD">
						</div>
						<div class="input-container">
							<label for="lotsAddExpiryYYYY">ExpiryYYYY</label>
							<input type="text" class="form-control" id="lotsAddExpiryYYYY" name="lotsAddExpiryYYYY">
						</div>
						<div class="input-container" style="
						margin-bottom:0;">
							<label for="lotsAddQty">lotsAddQty</label>
							<input type="text" class="form-control" id="lotsAddQty" name="lotsAddQty">
						</div>
						<div class="input-container">
							<label for="lotsAddSupCode">SupCode</label>
							<input type="text" class="form-control" id="lotsAddSupCode" name="lotsAddSupCode">
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary" id="lotsAddSaveButton">Add</button>
					</div>
				</div>
			</div>
		</div>

		<div class="colSetup modal fade" id="colSetuplots-editModal" tabindex="-1" role="dialog"
			aria-labelledby="editModalLabel" aria-hidden="true">
			<div class=" modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="editModalLabel">Lots Update Column Setup</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="input-container">
							<label for="lotsEditVendorName">Vendor</label>
							<input type="text" class="form-control" id="lotsEditVendorName" name="lotsEditVendorName">
						</div>
						<div class="input-container">
							<label for="lotsEditFileType">File Type</label>
							<select name="lotsEditFileType" class="form-control " id="lotsEditFileType">
								<option value=""></option>
								<option value="csv_file">CSV File</option>
								<option value="text_file">Text File</option>
							</select>
						</div>
						<div class="input-container">
							<label for="lotsEditInvNo">InvNo</label>
							<input type="text" class="form-control" id="lotsEditInvNo" name="lotsEditInvNo">
						</div>
						<div class="input-container">
							<label for="lotsEditItemcode">Itemcode</label>
							<input type="text" class="form-control" id="lotsEditItemcode" name="lotsEditItemcode">
						</div>
						<div class="input-container">
							<label for="lotsEditLotNo">LotNo</label>
							<input type="text" class="form-control" id="lotsEditLotNo" name="lotsEditLotNo">
						</div>
						<div class="input-container">
							<label for="lotsEditExpiryMM">ExpiryMM</label>
							<input type="text" class="form-control" id="lotsEditExpiryMM" name="lotsEditExpiryMM">
						</div>
						<div class="input-container">
							<label for="lotsEditExpiryDD">ExpiryDD</label>
							<input type="text" class="form-control" id="lotsEditExpiryDD" name="lotsEditExpiryDD">
						</div>
						<div class="input-container">
							<label for="lotsEditExpiryYYYY">ExpiryYYYY</label>
							<input type="text" class="form-control" id="lotsEditExpiryYYYY" name="lotsEditExpiryYYYY">
						</div>
						<div class="input-container">
							<label for="lotsEditQty">lotsEditQty</label>
							<input type="text" class="form-control" id="lotsEditQty" name="lotsEditQty">
						</div>
						<div class="input-container">
							<label for="lotsEditSupCode">SupCode</label>
							<input type="text" class="form-control" id="lotsEditSupCode" name="lotsEditSupCode">
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							<button type="button" class="btn btn-primary" id="lotsEditSaveUpdateButton">Save
								Changes</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="colSetup modal fade" id="colSetuplots-deleteModal" tabindex="-1" role="dialog"
			aria-labelledby="deleteModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="deleteModalLabel">Delete Entry</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body delete-msg">
						Are you sure you want to delete this entry?
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
						<button type="button" class="btn btn-danger" id="lotsConfirmDeleteVendor">Delete</button>
					</div>
				</div>
			</div>
		</div>
		<!-- Todo Tab 3 Content lots -->
    </body>
	{{-- <script type="text/javascript">
		(function(c,l,a,r,i,t,y){
			c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
			t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
			y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
		})(window, document, "clarity", "script", "j0m5rtds4l");
	</script> --}}
</html>
