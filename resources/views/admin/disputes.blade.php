@extends('layouts.admin')

@section('title')
Disputes
@endsection



@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Disputes</h2>
    <table class="table table-bordered yajra-datatable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Description</th>
                <th>Filled By</th>
                <th>Against</th>
                <th>Status</th>
                <th>Action</th>

            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>
@endsection


@section('scripts')

<script type="text/javascript">
    $(function() {
        var table = $('.yajra-datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('disputes') }}",
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'title',
                    name: 'title'
                },
                {
                    data: 'description',
                    name: 'description'

                },
                {
                    data: 'application.user.first_name',
                    render: function(data, type, row) {
                        return row.application.user.first_name + ' ' + row.application.user.last_name;
                    },
                    name: 'Filled By',

                },
                
                {
                    data: 'service.user.first_name',
                    name: 'Against',
                    render: function(data, type, row) {
                        return row.service.user.first_name + ' ' + row.service.user.last_name;
                    }
                },

                {
                    data: 'status',
                    name: 'status',
                    render: function(data, type, row) {
                        if (row.status == 0) {
                            return '<span class="badge badge-success">Active</span>';
                        } else if (row.status == 1) {
                            return '<span class="badge badge-danger">Closed</span>';
                        }
                    }
                },

                {
                    data: 'action',
                    name: 'action',
                    orderable: true,
                    searchable: true
                }
            ]
        });
    })
</script>
@endsection