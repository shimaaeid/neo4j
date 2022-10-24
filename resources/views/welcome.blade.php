<!doctype html>
<html>
<head>
    <title>Laravel Form Validation!</title>

    <!-- load bootstrap -->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <style>
        body    { padding-bottom:40px; padding-top:40px; }
    </style>
</head>
<body class="container">

<div class="row">
    <div class="col-sm-8 col-sm-offset-2">

        <div class="page-header">
            <h1><span class="glyphicon glyphicon-flash"></span> Register! </h1>
        </div>

        @if($errors->count())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
        </div>
        @endif

        <!-- FORM STARTS HERE -->
        <form method="POST" action="{{ route('store') }}" novalidate>
            @csrf
            @method('POST')

            <div class="form-group @if ($errors->has('name')) has-error @endif">
                <label for="name">Name</label>
                <input type="text" id="name" class="form-control" name="name" placeholder="Enter your name" value="{{ Request::old('name') }}">
                @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
            </div>

            <div class="form-group @if ($errors->has('subject')) has-error @endif">
                <label for="subject">Subject</label>
                <input type="text" id="subject" class="form-control" name="subject" placeholder="Enter your subject id" value="{{ Request::old('subject') }}">
                @if ($errors->has('subject')) <p class="help-block">{{ $errors->first('subject') }}</p> @endif
            </div>

            <div class="form-group @if ($errors->has('date')) has-error @endif">
                <label for="date">Date</label>
                <input type="text" id="date" class="form-control" name="date" value="{{  now()}}">
                @if ($errors->has('date')) <p class="help-block">{{ $errors->first('date') }}</p> @endif
            </div>

            <button type="submit" class="btn btn-success">Submit</button>

        </form>

    </div>
</div>

</body>
</html>
