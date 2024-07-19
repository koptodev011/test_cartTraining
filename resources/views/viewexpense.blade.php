<!DOCTYPE html>
<html lang="en">

<head>
    <base href="../../../">
    <title>Metronic - Admin Theme</title>
    <meta name="description" content="Metronic - Bootstrap Admin Theme" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta charset="utf-8" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="Metronic - Admin Theme" />
    <meta property="og:url" content="https://example.com/metronic" />
    <meta property="og:site_name" content="Metronic" />
    <link rel="canonical" href="https://example.com/metronic" />
    <link rel="shortcut icon" href="assets/media/logos/favicon.ico" />
    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <!-- Page Vendor Stylesheets -->
    <link href="assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
    <!-- Global Stylesheets Bundle -->
    <link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
</head>

<body id="kt_body"
    class="header-fixed header-tablet-and-mobile-fixed toolbar-enabled toolbar-fixed aside-enabled aside-fixed"
    style="--kt-toolbar-height:55px;--kt-toolbar-height-tablet-and-mobile:55px">

    @extends('layouts.layout')
    <!-- Main content -->
    <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
        <!-- Header -->
        <div id="kt_header" class="header align-items-stretch">
        </div>

        <!-- Content container -->
        <div class="container">
            <!-- Content goes here -->
            <div class="content">
                <div class="flex-lg-row-fluid ms-lg-10">
                    <div class="card card-flush mb-6 mb-xl-9">
                        <!-- Card header -->
                        <div class="card-header pt-5">
                            <div class="card-title">
                                <h2 class="d-flex align-items-center">Users Assigned</h2>
                            </div>
                            <div class="card-toolbar">
                                <div class="d-flex align-items-center position-relative my-1"
                                    data-kt-view-roles-table-toolbar="base">
                                    <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2"
                                                rx="1" transform="rotate(45 17.0365 15.1223)" fill="black" />
                                            <path
                                                d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                                fill="black" />
                                        </svg>
                                    </span>
                                    <input type="text" data-kt-roles-table-filter="search"
                                        class="form-control form-control-solid w-250px ps-15"
                                        placeholder="Search Users" />
                                </div>
                                <div class="d-flex justify-content-end align-items-center d-none"
                                    data-kt-view-roles-table-toolbar="selected">
                                    <div class="fw-bolder me-5">
                                        <span class="me-2"
                                            data-kt-view-roles-table-select="selected_count"></span>Selected
                                    </div>
                                    <button type="button" class="btn btn-danger"
                                        data-kt-view-roles-table-select="delete_selected">Delete Selected</button>
                                </div>
                            </div>
                        </div>
                        <!-- Card body -->
                        <div class="card-body pt-0">
                            <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0" id="kt_roles_view_table">
                                <thead>
                                    <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                        <th class="w-10px pe-2">
                                            <div
                                                class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                                <input class="form-check-input" type="checkbox" data-kt-check="true"
                                                    data-kt-check-target="#kt_roles_view_table .form-check-input"
                                                    value="1" />
                                            </div>
                                        </th>
                                        <th class="min-w-50px">Sr No</th>
                                        <th class="min-w-200px">Expense Title</th>
                                        <th class="min-w-200px">Expense Date</th>
                                        <th class="min-w-250px">Amount</th>
                                        <th class="min-w-125px">Expense Recipt</th>
                                    </tr>
                                </thead>
                                <tbody class="fw-bold text-gray-600">
                                    @foreach($expenses as $expense)
                                    <tr>
                                        <td>
                                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                <input class="form-check-input" type="checkbox" value="1" />
                                            </div>
                                        </td>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $expense->expense_title }}</td>
                                        <td>{{ $expense->created_at->format('d M Y') }}</td>
                                        <td>{{ $expense->expense_amount }}</td>
                                        <td>
                                            <!-- Button to trigger modal -->
                                            <a class="btn btn-sm btn-light btn-active-light-primary"
                                                onclick="showReceipt('{{ asset($expense->expense_recept) }}')">Receipt</a>
                                        </td>
                                    </tr>



                                    @endforeach
                                </tbody>
                            </table>
							
                            <div id="receiptModal" class="modal fade" tabindex="-1" role="dialog">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Receipt Image</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- Image tag with id receiptImage to display the image -->
                                            <img id="receiptImage" src="" class="img-fluid" alt="Receipt Image">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Scripts -->
    <script src="assets/js/scripts.bundle.js"></script>
    <script>
    function showReceipt(imageUrl) {
        // Update the src attribute of the receiptImage element
        document.getElementById('receiptImage').src = imageUrl;
        // Show the modal
        $('#receiptModal').modal('show');
    }
    </script>
</body>

</html>