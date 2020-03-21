@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <chart-component :currenies-data="{{ json_encode(config('settings.currenies')) }}"></chart-component>
        </div>
    </div>

    <div class="row justify-content-center mt-5">
        <div class="col-md-10">
            <exchange-component :currenies-data="{{ json_encode(config('settings.currenies')) }}" :fabuk-data="{{ json_encode(config('settings.fabuk_symbool')) }}"></exchange-component>
        </div>
    </div>
</div>
@endsection
