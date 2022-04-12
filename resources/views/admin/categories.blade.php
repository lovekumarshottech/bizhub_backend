@extends('layouts.master')

@section('title')
Categories
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"> Categories</h4>

                <a href="{{ route('add-category-view') }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus"></i>
                    Add Category
                </a>

            </div>
            <!-- Danger modal on click visible-->

            <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalCenterTitle">Delete Category</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            You can't undo this
                            <div class="card text-white bg-danger mt-3 p-2">
                                This is a warning - bad things may happen
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary">
                                <ng-container i18n>Yes</ng-container>
                            </button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal">
                                <ng-container i18n>Close</ng-container>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- <div id="modal" class="modal-dialog d-none justify-content-center " style="position : absolute; z-index: 9999; align-items: center;  left: 30%  ">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"></h4>
                    </div>
                    <div class="modal-body">
                        You can't undo this
                        <div class="card text-white bg-danger mt-3 p-2">
                            This is a warning - bad things may happen
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" (click)="onConfirm()">
                            <ng-container i18n>Yes</ng-container>
                        </button>
                        <button type="button" class="btn btn-secondary" (click)="close()">
                            <ng-container i18n>Close</ng-container>
                        </button>
                    </div>
                </div>
            </div> -->

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead class=" text-primary">
                            <th>
                                Title
                            </th>
                            <th>
                                Status
                            </th>

                        </thead>
                        <tbody>

                            @foreach($categories as $category)
                            <tr>
                                <td>
                                    @if($category->is_active == 1)
                                    <span class="badge badge-success">Active</span>
                                    @else
                                    <span class="badge badge-danger">Blocked</span>
                                    @endif

                                </td>
                                <td>
                                    {{$category->title}}
                                </td>
                                <td>
                                    <a href="{{ route('add-category-view', ['id' => $category->id]) }}" class="btn btn-success btn-sm">
                                        <i class="fa fa-edit"></i>
                                        Edit
                                    </a>

                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#exampleModalCenter">
                                        <i class="fa fa-trash"></i>
                                        Delete
                                    </button>


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
<script>
    // function close() {
    //     // document.getElementById('modal').style.visibility = 'hidden';
    //     document.getElementById('modal').style.display = 'none';

    // }

    // function open() {


    //     // document.getElementById('modal').style.visibility = 'visible';
    //     document.getElementById('modal').style.display = 'flex';

    // }
    //visible and hide function

    function onDelete(id) {
        close();
    }
</script>

@endsection