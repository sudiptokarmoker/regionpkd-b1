@extends('layouts.app')

@section('page_css')
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('css/multi-form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/star-rating.min.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/krajee-fa/theme.css') }}" media="all" type="text/css"/>
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
        <div class="col-sm-12">
            <div class="card-box table-responsive">
                <h4 class="header-title"><b>User List</b></h4>
                <div class="p20">
                    <table class="table table-bordered data-table" id="datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
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
        $(function() {
            //$.fn.dataTable.ext.errMode = 'throw';
            var table = $('.data-table').DataTable({
                processing: true, 
                serverSide: true,
                ajax: "{{ route('grid.render.test') }}",
                order: [], 
                columns: [
                    {data: 'id', name: 'id'}, 
                    {data: 'name', name: 'name'}, 
                    {data: 'email', name: 'email'}, 
                ]
            });
        });
    </script>
@endsection

