@extends('layouts.master')

@section('title')
Disputes
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"> Users</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead class=" text-primary">
                            <th>
                                Dispute Title
                            </th>
                            <th>
                                Dispute Description
                            </th>

                            <th>
                                Dispute Status
                            </th>
                            <th>
                                Filled Against
                            </th>
                            <th>
                                Filled By
                            </th>
                            <th>
                                Dispute Date
                            </th>
                        </thead>
                        <tbody>
                            @foreach($disputes as $dispute)
                            <tr>
                                <td>
                                    {{$dispute->title}}
                                </td>
                                <td>
                                    {{$dispute->description}}
                                </td>

                                <td>
                                    @if($dispute->status == 0)
                                    <span class="badge badge-danger">Active</span>
                                    @elseif($dispute->status == 1)
                                    <span class="badge badge-success">Closed</span>
                                    @endif
                                </td>
                                <td>
                                    {{$dispute->service->user->first_name}} {{$dispute->service->user->last_name}}
                                </td>
                                <td>
                                    {{$dispute->application->user->first_name}} {{$dispute->application->user->last_name}}
                                </td>
                                <td>
                                    {{$dispute->created_at}}
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

@endsection