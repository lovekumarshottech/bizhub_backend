@extends('layouts.admin')

@section('title')
queries
@endsection



@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Queries</h2>
    <table class="table table-bordered yajra-datatable">
        <thead>
            <tr>
                <td>Name</td>
                <td>Subject</td>
                <td>Email</td>
                <td>Message</td>
                <td>Phone Number</td>

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
            ajax: "{{ route('queries') }}",
            columns: [

                {
                    data: 'first_name',
                    name: 'first_name'
                },
                {
                    data: 'subject',
                    name: 'subject'

                },
                {
                    data: 'email',
                    name: 'email',

                },
                {
                    data: 'message',
                    name: 'message'
                },
                {
                    data: 'number',
                    name: 'number'
                }
            ]
        });
    })
</script>
@endsection