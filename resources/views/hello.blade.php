@extends('layouts/adminplus')
@section('breadcrumb')
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
        <li><a href="{{ url('/') }}">AdminPlus</a></li>
        <li class="active">Fixed layout</li>
    </ol>
    <!-- // END Breadcrumb -->
@endsection
@section('content')
    @include('includes/content')
@endsection