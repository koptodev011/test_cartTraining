@extends('layouts.layout')

@section('content')
<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
    <base href="../../../">
    <title></title>
    <meta name="description"
        content="The most advanced Bootstrap Admin Theme on Themeforest trusted by 94,000 beginners and professionals. Multi-demo, Dark Mode, RTL support and complete React, Angular, Vue &amp; Laravel versions. Grab your copy now and get life-time updates for free." />
    <meta name="keywords"
        content="Metronic, bootstrap, bootstrap 5, Angular, VueJs, React, Laravel, admin themes, web design, figma, web development, free templates, free admin themes, bootstrap theme, bootstrap template, bootstrap dashboard, bootstrap dak mode, bootstrap button, bootstrap datepicker, bootstrap timepicker, fullcalendar, datatables, flaticon" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta charset="utf-8" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title"
        content="Metronic - Bootstrap 5 HTML, VueJS, React, Angular &amp; Laravel Admin Dashboard Theme" />
    <meta property="og:url" content="https://keenthemes.com/metronic" />
    <meta property="og:site_name" content="Keenthemes | Metronic" />
    <link rel="canonical" href="https://preview.keenthemes.com/metronic8" />
    <link rel="shortcut icon" href="assets/media/logos/favicon.ico" />
    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Page Vendor Stylesheets(used by this page)-->
    <link href="assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
    <!--end::Page Vendor Stylesheets-->
    <!--begin::Global Stylesheets Bundle(used by all pages)-->
    <link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/style.bundle.css" rel="stylesheet" type="text/css" />

</head>

<body>

    @section('content')


    <div class="d-flex justify-content-end align-items-center d-none" data-kt-user-table-toolbar="selected">
        <div class="fw-bolder me-5">
            <span class="me-2" data-kt-user-table-select="selected_count"></span>Selected
        </div>
        <button type="button" class="btn btn-danger" data-kt-user-table-select="delete_selected">Delete
            Selected</button>
    </div>
    <!--end::Group actions-->
    <!--begin::Modal - Adjust Balance-->
    <div class="modal fade" id="kt_modal_export_users" tabindex="-1" aria-hidden="true">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <!--begin::Modal content-->
            <div class="modal-content">
                <!--begin::Modal header-->
                <div class="modal-header">
                    <!--begin::Modal title-->
                    <h2 class="fw-bolder">Export Users</h2>
                    <!--end::Modal title-->
                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-users-modal-action="close">
                        <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                        <span class="svg-icon svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                    transform="rotate(-45 6 17.3137)" fill="black" />
                                <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)"
                                    fill="black" />
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                    </div>
                    <!--end::Close-->
                </div>
                <!--end::Modal header-->
                <!--begin::Modal body-->
                <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                    <!--begin::Form-->
                    <form id="kt_modal_export_users_form" class="form" action="#">
                        <!--begin::Input group-->
                        <div class="fv-row mb-10">
                            <!--begin::Label-->
                            <label class="fs-6 fw-bold form-label mb-2">Select Roles:</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <select name="role" data-control="select2" data-placeholder="Select a role"
                                data-hide-search="true" class="form-select form-select-solid fw-bolder">
                                <option></option>
                                <option value="Student">Student</option>
                                <option value="Analyst">Analyst</option>
                                <option value="Developer">Developer</option>
                                <option value="Support">Support</option>
                                <option value="Trial">Trial</option>
                            </select>
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="fv-row mb-10">
                            <!--begin::Label-->
                            <label class="required fs-6 fw-bold form-label mb-2">Select Export
                                Format:</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <select name="format" data-control="select2" data-placeholder="Select a format"
                                data-hide-search="true" class="form-select form-select-solid fw-bolder">
                                <option></option>
                                <option value="excel">Excel</option>
                                <option value="pdf">PDF</option>
                                <option value="cvs">CVS</option>
                                <option value="zip">ZIP</option>
                            </select>
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Actions-->
                        <div class="text-center">
                            <button type="reset" class="btn btn-light me-3"
                                data-kt-users-modal-action="cancel">Discard</button>
                            <button type="submit" class="btn btn-primary" data-kt-users-modal-action="submit">
                                <span class="indicator-label">Submit</span>
                                <span class="indicator-progress">Please wait...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>





    <div class="container mt-8">
        <div class="row justify-content-center">
            <div class="col-lg-15">
                <!-- Increased width to col-lg-8 -->
                <div class="card">
                    <div class="card-header" style="margin-top: 20px;">
                        <!-- Added margin-top -->
                        <h1>Add User</h1>
                    </div>
                    <div class="card-body">
                        <div class="modal-body scroll-y mx-8 mx-xl-25 my-7">
                            <!--begin::Form-->
                            <form method="post" action="{{ route('updatevideodetails', ['id' => $videodetails->id]) }}" enctype="multipart/form-data">

                            
                            @csrf
                                            <div class="d-flex flex-column scroll-y me-n7 pe-7"
                                                id="kt_modal_add_user_scroll" data-kt-scroll="true"
                                                data-kt-scroll-activate="{default: false, lg: true}"
                                                data-kt-scroll-max-height="auto"
                                                data-kt-scroll-dependencies="#kt_modal_add_user_header"
                                                data-kt-scroll-wrappers="#kt_modal_add_user_scroll"
                                                data-kt-scroll-offset="300px">

                                                <div class="fv-row mb-7">
                                                    <label class="d-block fw-bold fs-6 mb-5">Video Upload</label>
                                                    <div class="image-input image-input-outline"
                                                        data-kt-image-input="true">
                                                        <div class="image-input-wrapper w-250px h-150px"
                                                            style="background-image: url(assets/media/placeholders/blank.png);"
                                                            id="video-preview">
                                                            <video id="video-player" width="100%" height="100%" controls
                                                                style="object-fit: cover;"></video>
                                                        </div>
                                                        <label
                                                            class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                                            data-kt-image-input-action="change" data-bs-toggle="tooltip"
                                                            title="Upload video">
                                                            <i class="bi bi-camera fs-7"></i>
                                                            <input type="file" name="video" accept=".mp4, .ogg, .webm"
                                                                onchange="previewVideo(event)" />
                                                            <input type="hidden" name="video_remove" />
                                                        </label>
                                                        <span
                                                            class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                                            data-kt-image-input-action="cancel" data-bs-toggle="tooltip"
                                                            title="Cancel upload">
                                                            <i class="bi bi-x fs-2"></i>
                                                        </span>
                                                        <span
                                                            class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                                            data-kt-image-input-action="remove" data-bs-toggle="tooltip"
                                                            title="Remove video">
                                                            <i class="bi bi-x fs-2"></i>
                                                        </span>
                                                    </div>
                                                    <div class="form-text">Allowed file types: mp4, ogg, webm.</div>
                                                </div>
                                                <div class="fv-row mb-7">
                                                    <label class="required fw-bold fs-6 mb-2">Video title</label>
                                                    <input type="text" name="title"
                                                        class="form-control form-control-solid mb-3 mb-lg-0"  value="{{ $videodetails->title }}"  />
                                                </div>

                                                <div class="fv-row mb-7">
                                                    <label class="required fw-bold fs-6 mb-2">Video Display Day</label>
                                                    <input type="number" name="day" value="{{ $videodetails->day }}" 
                                                        class="form-control form-control-solid mb-3 mb-lg-0" />
                                                </div>
                                                <div class="fv-row mb-7">
                                                    <label class="required fw-bold fs-6 mb-2">Role</label>
                                                    <select name="role" value="{{ $videodetails->role }}" 
                                                        class="form-select form-select-solid mb-3 mb-lg-0">
                                                        <option value="1">Student</option>
                                                        <option value="2">Trainer</option>
                                                        <option value="3">Receptionist</option>
                                                    </select>
                                                </div>



                                                <div class="text-center pt-15">
                                                    <button type="reset" class="btn btn-light me-3"
                                                        data-kt-users-modal-action="cancel">Discard</button>


                                                    <button type="submit" class="btn btn-primary">
                                                        <!-- <button type="submit" class="btn btn-primary"
                                                        data-kt-users-modal-action="submit"> -->

                                                        <span class="indicator-label">Submit</span>

                                                        <span class="indicator-progress">Please wait...
                                                            <span
                                                                class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                        </span>
                                                    </button>
                                                </div>
                            </form>
                            <!--end::Form-->
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

    </div>

































</body>


<script>
    function previewVideo(event) {
    var input = event.target;
    var preview = document.getElementById('video-player');

    var file = input.files[0];
    var reader = new FileReader();

    reader.onload = function(e) {
        preview.src = e.target.result;
    };

    if (file) {
        reader.readAsDataURL(file);
    }


}
</script>



</html>
@endsection