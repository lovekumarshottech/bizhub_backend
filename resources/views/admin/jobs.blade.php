@extends('layouts.master')

@section('title')
Jobs
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"> Jobs</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead class=" text-primary">
                            <th>
                                Status
                            </th>
                            <th>
                                Posted By
                            </th>
                            <th>
                                Job Title
                            </th>
                            <th>
                                Description
                            </th>
                            <th>
                                Category
                            </th>

                            <th class="text-right">
                                Amount
                            </th>
                        </thead>
                        <tbody>

                            @foreach($services as $service)
                            <tr>
                                <td>
                                    @if($service->status == 0)
                                    <span class="badge badge-info">Active</span>
                                    @elseif($service->status == 1)
                                    <span class="badge badge-success">Competed</span>
                                    @elseif($service->status == 2)
                                    <span class="badge badge-warning">Expired</span>
                                    @elseif($service->status == 3)
                                    <span class="badge badge-success">Hired</span>
                                    @elseif($service->status == 4)
                                    <span class="badge badge-danger">Cancelled</span>

                                    @endif



                                </td>
                                <td>
                                    {{$service->user->first_name}} {{$service->user->last_name}}
                                </td>
                                <td>
                                    {{$service->title}}
                                </td>
                                <td>
                                    {{$service->description}}
                                </td>
                                <td>
                                    {{$service->category->title}}
                                </td>

                                <td class="text-right">
                                    {{$service->amount}}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>



@endsection


@section('scripts')
<!-- <script>
    // function myFunction(val) {
    //     alert(val);
    // }
    $("#myId").on('input', function() {
        alert(Jobs);
    });
</script> -->
@endsection