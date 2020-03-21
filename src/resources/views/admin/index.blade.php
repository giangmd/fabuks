@extends('admin.layouts.main')
@section('title', 'Admin Dashboard')

@section('content')

@includeIf('admin.partials.breadcrumb')

<div class="page-content">

    <div class="page-header">
        <h1>
            Dashboard
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                overview
            </small>
        </h1>
    </div><!-- /.page-header -->

</div><!-- /.page-content -->

@stop