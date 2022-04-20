<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>


    <link href="/assets/css/now-ui-dashboard.css" rel="stylesheet" />
    <link href="/assets/demo/demo.css" rel="stylesheet" />
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="/assets/css/now-ui-dashboard.css?v=1.5.0" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/a8558eb723.js" crossorigin="anonymous"></script>
    <style>
        * {
            margin: 0;
            padding: 0;

        }

        div {
            font-size: 22px;
            text-align: start;
        }

        img:hover {
            width: 160px;
            height: 160px;
        }
    </style>


</head>

<body>
    <div>

        <div class="main-div">
            <div class="container">

                <div class="row justify-content-center">
                    <div class="col-6 col-sm-4">Title</div>
                    <div class="col-1"> :</div>
                    <div class="col-6 col-sm-4">{{$dispute->title}}</div>

                    <div class="w-100 d-none d-md-block">
                        <hr class="w-100">
                    </div>

                    <div class="col-6 col-sm-4">Description</div>
                    <div class="col-1"> :</div>
                    <div class="col-6 col-sm-4">{{$dispute->description}}</div>

                    <div class="w-100 d-none d-md-block">
                        <hr class="w-100">
                    </div>

                    <div class="col-6 col-sm-4">Filled Against</div>
                    <div class="col-1"> :</div>
                    <div class="col-6 col-sm-4">{{$dispute->service->user->first_name}} {{$dispute->service->user->last_name}}</div>

                    <div class="w-100 d-none d-md-block">
                        <hr class="w-100">
                    </div>

                    <div class="col-6 col-sm-4">Filled By</div>
                    <div class="col-1"> :</div>
                    <div class="col-6 col-sm-4">{{$dispute->application->user->first_name}} {{$dispute->application->user->last_name}}</div>

                    <div class="w-100 d-none d-md-block">
                        <hr class="w-100">
                    </div>

                    <div class="col-6 col-sm-4">Created At</div>
                    <div class="col-1"> :</div>
                    <div class="col-6 col-sm-4">{{$dispute->created_at}} </div>

                    <div class="w-100 d-none d-md-block">
                        <hr class="w-100">
                    </div>

                    <div class="col-6 col-sm-4">Status</div>
                    <div class="col-1"> :</div>
                    <div class="col-6 col-sm-4">@if($dispute->status == 0)
                        Active
                        @elseif($dispute->status == 1)
                        Closed
                        @endif
                    </div>

                    <div class="w-100 d-none d-md-block">
                        <hr class="w-100">
                    </div>

                    <div class="col-6 col-sm-4">Deal Amount</div>
                    <div class="col-1"> :</div>
                    <div class="col-6 col-sm-4">{{$dispute->application->amount}} </div>

                    <div class="w-100 d-none d-md-block">
                        <hr class="w-100">
                    </div>
                    @if($dispute->status == 0)
                    <a href="{{route('payout', $dispute->service->id)}}" class="btn bg-warning col-6 col-sm-4">Payout</a>
                    <a href="{{route('refund', $dispute->service->id)}}" class="btn bg-danger col-6 col-sm-4">Refund</a>
                    @endif

                    <div class="w-100 d-none d-md-block">
                        <hr class="w-100">
                    </div>

                    @foreach($dispute->images as $image)
                    <div>
                        <a href="/storage/uploads/support/{{$image->file_name}}" target="_blank">
                            <img style="margin: 10px;" src="/storage/uploads/support/{{$image->file_name}}" alt="{{$image->path}}" width="150px" height="150px">
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</body>

</html>