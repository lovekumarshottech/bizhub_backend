@extends('layouts.admin')

@section('title')
Jobs
@endsection



@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Jobs</h2>
    <table class="table table-bordered yajra-datatable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Amount</th>
                <th>Posted By</th>
                <th>Category</th>
                <th>Description</th>
                <th>Status</th>
                <th>Applied Users</th>
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
            ajax: "{{ route('jobs') }}",
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'title',
                    name: 'title'
                },
                {
                    data: 'amount',
                    name: 'amount'
                },
                {
                    data: 'user.first_name',
                    name: 'user.first_name',
                    render: function(data, type, row) {
                        return row.user.first_name + ' ' + row.user.last_name;
                    }
                },
                {
                    data: 'category.title',
                    name: 'category'
                },
                {
                    data: 'description',
                    name: 'description'
                },
                {
                    data: 'status',
                    name: 'status',
                    render: function(data, type, row) {
                        if (row.status == 0) {
                            return '<span class="badge badge-success">Active</span>';
                        } else if (row.status == 1) {
                            return '<span class="badge badge-success">Completed</span>';
                        } else if (row.status == 2) {
                            return '<span class="badge badge-warning">Expired</span>';
                        } else if (row.status == 3) {
                            return '<span class="badge badge-warning">Hired</span>';
                        } else if (row.status == 4) {
                            return '<span class="badge badge-danger">Cancelled</span>';
                        }
                    }
                },
                {
                    'data': 'action',
                    'name': 'action',
                    'orderable': false,
                    'searchable': false
                }


            ]
        });
    })
</script>
@endsection