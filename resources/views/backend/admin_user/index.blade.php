@extends('backend.layouts.app')
@section('title', 'Admin User Managment')
@section('admin-user-index', 'mm-active')
@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-user icon-gradient bg-mean-fruit">
                    </i>
                </div>
                <div> Admin Users List </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="card">
            <div class="card-body">
                <table class="table table-striped Datatable table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>

                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('.Datatable').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": "/admin/admin-user/datatable/ssd",
                columns: [{
                    data: "name",
                    name: "name"
                }, {
                    data: "email",
                    name: "email"
                }, {
                    data: "phone",
                    name: "phone"
                }]
            });
        });

    </script>
@stop
