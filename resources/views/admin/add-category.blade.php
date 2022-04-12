<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Categories</title>

    <!-- CSS Files -->


    <link href="../assets/css/now-ui-dashboard.css" rel="stylesheet" />
    <link href="../assets/demo/demo.css" rel="stylesheet" />
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="../assets/css/now-ui-dashboard.css?v=1.5.0" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/a8558eb723.js" crossorigin="anonymous"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
    </style>


</head>

<body>
    <div>

        <div class="main-div">

            <!-- form method if $category post then method="post" else method="get" -->

            @if(!isset($category))
            <form id="myForm" method="post" action="{{ url('/admin/add-category')}}">
                @else
                <form id="myForm" method="PUT" action="{{ url('/admin/edit-category')}}">

                    <input type="hidden" name="id" value="{{ $category->id }}">
                    @endif

                    @csrf
                    <div class="form-group flexible">
                        <label style=" height: 13; flex: 15%; margin-top: 5px;" for="title">Category Title</label>
                        <input required style="flex: 50%;" type="text" class="form-control text-field-hover" id="title" placeholder="Enter Category Name" name="title" value="{{$category->title??''}}">
                        <!-- <div style="flex: 30%;"></div> -->

                    </div>
                    <div class="form-group flexible">
                        <label style="flex: 15%; margin-top: 5px;" for="description">Category Description</label>
                        <input style="flex: 50%; height: 200px;" type="text" class="form-control text-field-hover" id="description" placeholder="Enter Category Description" name="description" value="{{$category->description??''}}">
                        <!-- <div style=" flex: 30%;">
                </div> -->

                    </div>

                    <div class="form-group flexible">
                        <label style="flex: 15%; margin-top: 5px;" for="is_active">Category Status</label>
                        <select style="flex: 50%; " class="form-select  selector" name="is_active" id="is_active" value="{{$category->is_active??''}}">
                            @if(!isset($category))
                            <option value="" disabled selected>Select Status</option>
                            @endif
                            <option value=" 1">Active</option>
                            <option value="0">Blocked</option>
                        </select>
                        <!-- <div style="flex: 30%;"></div> -->
                    </div>

                    <div style="height: 30px;"></div>
                    <div class="flexible">
                        <div style="flex: 17%;"></div>

                        <input style="flex: 50%;" type="submit" class="btn btn-primary btn-sm full-width-button" value="@if(isset($category)) Edit Category @else Add Category @endif">
                    </div>
                </form>
        </div>
    </div>

</body>

</html>