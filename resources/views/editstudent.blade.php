@extends('layouts.layout')
@section('content')
<!DOCTYPE html>
<html lang="en">

<head>
    <style>
    .image-input-wrapper {
        position: relative;
    }

    .image-input-wrapper .btn,
    .image-input-wrapper .btn-icon {
        position: absolute;
        bottom: 10px;
        right: 10px;
    }

    .image-input-wrapper .form-text {
        margin-top: 10px;
    }

    .modal-body {
        min-height: 400px;
    }
    </style>
</head>

<body>
    @section('content')
    @include('sweetalert::alert')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-16">
                <div class="card">
                    <div class="card-header" style="margin-top: 20px;">
                        <h1>Edit Student</h1>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('updatestudent', ['id' => $student->id]) }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-md-11 text-center">
                                    <label class="required d-block fw-bold fs-6 mb-4">Avatar</label>
                                    <div class="image-input image-input-outline d-inline-block position-relative"
                                        data-kt-image-input="true">
                                        <div class="image-input-wrapper w-200px h-200px rounded-circle border border-gray-300"
                                            style="background-image: url({{ asset($student->profile_photo_path) }});">
                                        </div>
                                        <label
                                            class="btn btn-icon btn-circle btn-active-color-primary position-absolute translate-middle start-100"
                                            style="bottom: 0; right: 0; transform: translate(50%, 50%);"
                                            data-kt-image-input-action="change" data-bs-toggle="tooltip"
                                            title="Change avatar">
                                            <i class="bi bi-pencil-fill fs-7"></i>
                                            <input type="file" name="profile_photo" accept=".png, .jpg, .jpeg" />
                                            <input type="hidden" name="avatar_remove" />
                                        </label>
                                        <span
                                            class="btn btn-icon btn-circle btn-active-color-primary position-absolute translate-middle start-0"
                                            style="bottom: 0; left: 0; transform: translate(-50%, 50%);"
                                            data-kt-image-input-action="cancel" data-bs-toggle="tooltip"
                                            title="Cancel avatar">
                                            <i class="bi bi-x fs-5"></i>
                                        </span>
                                        <span
                                            class="btn btn-icon btn-circle btn-active-color-primary position-absolute translate-middle start-0"
                                            style="top: 0; right: 0; transform: translate(50%, -50%);"
                                            data-kt-image-input-action="remove" data-bs-toggle="tooltip"
                                            title="Remove avatar">
                                            <i class="bi bi-x fs-2"></i>
                                        </span>
                                    </div>
                                    <div class="form-text">Allowed file types: png, jpg, jpeg.</div>
                                </div>
                                <div class="mb-3">
                                    <label class="required fw-bold fs-6 mb-2">Full Name</label>
                                    <input type="text" name="user_name" class="form-control form-control-solid"
                                        value="{{ $student->name }}" />
                                </div>
                                <div class="mb-3">
                                    <label class="required fw-bold fs-6 mb-2">Email</label>
                                    <input type="email" name="user_email" class="form-control form-control-solid"
                                        value="{{ $student->email }}" />
                                </div>
                                <div class="mb-3">
                                    <label class="required fw-bold fs-6 mb-2">Address</label>
                                    <input type="text" name="user_address" class="form-control form-control-solid"
                                        value="{{ $student->address }}" />
                                </div>
                                <div class="mb-3">
                                    <label class="required fw-bold fs-6 mb-2">Phone</label>
                                    <input type="number" name="user_phone" class="form-control form-control-solid"
                                        value="{{ $student->phone }}" />
                                </div>
                                <div class="mb-3">
                                    <label class="required fw-bold fs-6 mb-2">Shift</label>
                                    <select name="user_shift" class="form-select form-select-solid">
                                        <option value="1">8:00 To 12:00</option>
                                        <option value="2">1:00 To 4:00</option>
                                    </select>
                                </div>
                                <div class="text-center mt-4">
                                    <button type="reset" class="btn btn-light me-3"
                                        data-kt-users-modal-action="cancel">Discard</button>
                                    <button type="submit" class="btn btn-primary">
                                        <span class="indicator-label">Submit</span>
                                        <span class="indicator-progress d-none">Please wait... <span
                                                class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
@endsection