@extends('layouts.admin')

@section('title')
Applications
@endsection



@section('content')


<div class="container mt-5">
    <h2 class="">Applications</h2>


    <table class="table table-bordered yajra-datatable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Description</th>
                <th>Offered Amount</th>
                <th>Status</th>
                <th>applied user</th>
            </tr>

        </thead>
        <tbody></tbody>
    </table>
</div>
@endsection


@section('scripts')

<script type="text/javascript">
    $(function() {
        var id = '{{$id}}';
        let url = "{{ route('applications', ':id') }}";
        url = url.replace(':id', id);



        var table = $('.yajra-datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: url,
            columns: [{
                    data: 'id',
                    name: 'id',
                },
                {
                    data: 'description',
                    name: 'description',
                },
                {
                    data: 'amount',
                    name: 'offered_amount',
                },
                {
                    data: 'status',
                    name: 'status',
                    render: function(data, type, row) {
                        if (data == 0) {
                            return '<span class="badge badge-warning">Pending</span>';
                        } else if (data == 1) {
                            return '<span class="badge badge-success">Completed</span>';
                        } else if (data == 2) {
                            return '<span class="badge badge-success">Hired</span>';
                        } else if (data == 3) {
                            return '<span class="badge badge-danger">Rejected</span>';
                        } else if (data == 4) {
                            return '<span class="badge badge-danger">Cancelled</span>';
                        }
                    },

                },
                {
                    data: 'user.first_name',
                    name: 'user.first_name',
                    render: function(data, type, row) {
                        return row.user.first_name + ' ' + row.user.last_name;
                    }
                },


            ],
        });

    })
</script>
@endsection