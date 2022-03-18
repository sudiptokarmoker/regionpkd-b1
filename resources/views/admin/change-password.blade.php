@extends('layouts.app')

@section('page_css')
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
<link rel="stylesheet" href="{{ asset('css/multi-form.css') }}">
<link rel="stylesheet" href="{{ asset('css/star-rating.min.css') }}">
<link rel="stylesheet" href="{{ asset('themes/krajee-fa/theme.css') }}" media="all" type="text/css" />
<link href="{{ asset('assets/css/dataTables.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">User Lists Panel</h3>
    </div>
    <div class="section-body" id="app">
        <div class="row">
            <div class="offset-4 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row my-2">
                            <div class="col-12">
                                @if($errors->any())
                                {!! implode('', $errors->all('<div class="alert alert-danger">:message</div>')) !!}
                                @endif
                            </div>
                        </div>
                        @include('dashboard.partial.alert.message')
                        @include('dashboard.partial.alert.error')
                    </div>
                </div>
            </div>
        </div>
        <!-- body content append here -->
        <div class="row">
            <!-- new data table start -->
            <div id="changePasswordModal" class="" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Change User Name and Password</h5>
                        </div>
                        <form method="POST" action="{{ route('user.info.update') }}" id='changePasswordForm'>
                            <div class="modal-body">
                                @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                                <div class="alert alert-danger d-none" id=""></div>
                                <input type="hidden" name="is_active" value="1">
                                <input type="hidden" name="id" value="{{ $loadUser->id }}">
                                @csrf
                                <div class="row">
                                    <div class="form-group col-sm-12">
                                            <label>Name:</label><span class="required"></span><span class="required">*</span>
                                            <div class="input-group">
                                                <input class="form-control input-group__addon" id="txtName" type="text" name="name" required value="{{ $loadUser->name }}"/>
                                                <div class="input-group-append input-group__icon">
                                                    <span class="input-group-text changeType">
                                                        <i class="icon-ban icons"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    <div class="form-group col-sm-12">
                                        <label>New Password:</label><span class="required confirm-pwd"></span><span class="required">*</span>
                                        <div class="input-group">
                                            <input class="form-control input-group__addon" id="pfNewPassword" type="password" name="password" required>
                                            <div class="input-group-append input-group__icon">
                                                <span class="input-group-text changeType">
                                                    <i class="icon-ban icons"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-12">
                                        <label>Confirm Password:</label><span class="required confirm-pwd"></span><span class="required">*</span>
                                        <div class="input-group">
                                            <input class="form-control input-group__addon" id="pfNewConfirmPassword" type="password" name="password_confirmation" required>
                                            <div class="input-group-append input-group__icon">
                                                <span class="input-group-text changeType">
                                                    <i class="icon-ban icons"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary" id="btnPrPasswordEditSave" data-loading-text="<span class='spinner-border spinner-border-sm'></span> Processing...">Save</button>
                                    <button type="button" class="btn btn-light ml-1" data-dismiss="modal">Cancel
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- new data table end -->
        </div>
        <!-- end of body content -->
    </div>
</section>
@endsection

@section('page_js')
<script src="{{ asset('assets/js/multi-form.js') }}"></script>
<script src="{{ asset('assets/js/star-rating.min.js') }}"></script>
<script src="{{ asset('themes/krajee-fa/theme.js') }}" type="text/javascript"></script>

<script src="{{ asset('assets/js/dataTables.js') }}"></script>
<script src="{{ asset('assets/js/datatables.button.js') }}"></script>

<script>
    $("#opinion-form").multiStepForm({}).navigateTo(0);

</script>
@endsection
