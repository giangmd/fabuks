@extends('admin.layouts.main')
@section('title', 'Update user')

@section('content')

@includeIf('admin.partials.breadcrumb')

<div class="page-content">

    <div class="page-header">
        <h1>
            Update user
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                {{ $user->name . ' (' . $user->email . ')' }}
            </small>
        </h1>
    </div><!-- /.page-header -->

    <div class="row">
        <div class="col-xs-6">
            <div class="widget-box">
                <div class="widget-header">
                    <h4 class="widget-title">Update User</h4>
                </div>

                <div class="widget-body">
                    <div class="widget-main no-padding">
                        <form method="POST" action="">
                            @csrf
                            <fieldset>
                                <div class="form-group">
                                    <label for="form-field-1">First name</label>
                                    <input type="text" id="form-field-1" class="form-control" name="first_name" value="{{ $user->first_name }}" required="required">
                                </div>
                                <div class="form-group">
                                    <label for="form-field-2">Last name</label>
                                    <input type="text" id="form-field-2" class="form-control" name="last_name" value="{{ $user->last_name }}" required="required">
                                </div>
                                <div class="form-group">
                                    <label for="form-field-select-1">Role</label>
                                    <select class="form-control" id="form-field-select-1" name="role">
                                        @foreach(config('settings.role') as $role)
                                            @if($role != 'admin')
                                            <option value="{{ $loop->index }}">{{ $role }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </fieldset>

                            <div class="form-actions center">
                                <button type="submit" class="btn btn-sm btn-success">
                                    Submit
                                    <i class="ace-icon fa fa-arrow-right icon-on-right bigger-110"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div><!-- /.span -->
    </div><!-- /.row -->

</div><!-- /.page-content -->

@stop