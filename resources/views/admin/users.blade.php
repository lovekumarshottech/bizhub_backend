@extends('layouts.master')

@section('title')
Users
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
                                Name
                            </th>
                            <th>
                                Email
                            </th>

                            <th>
                                Phone Number
                            </th>
                            <th>
                                Active
                            </th>


                        </thead>
                        <tbody>

                            @foreach($users as $user)
                            <tr>
                                <td>
                                    {{$user->first_name}} {{$user->last_name}}
                                </td>
                                <td>
                                    {{$user->email}}
                                </td>
                                <td>
                                    {{$user->phone}}
                                </td>
                                <td>
                                    @if($user->is_active == 1)
                                    <span class="badge badge-success">Active</span>
                                    @elseif($user->is_active == 0)
                                    <span class="badge badge-danger">Blocked</span>
                                    @endif
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