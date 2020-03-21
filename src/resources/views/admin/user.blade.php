@extends('admin.layouts.main')
@section('title', 'User')

@section('content')

@includeIf('admin.partials.breadcrumb')

<div class="page-content">

    <div class="page-header">
        <h1>
            User
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                lists
            </small>
        </h1>
    </div><!-- /.page-header -->

    <div class="row">
        <div class="col-xs-12">
            <table id="simple-table" class="table  table-bordered table-hover">
                <thead>
                    <tr>
                        <th width="50" class="center">ID</th>
                        <th class="center">Name</th>
                        <th class="center">Email</th>
                        <th class="center">Role</th>
                        <th width="180" class="center">
                            <i class="ace-icon fa fa-clock-o bigger-110 hidden-480"></i>
                            Create at
                        </th>
                        <th width="120" class="center">History offers</th>

                        @if(auth()->user()->role == 'admin')
                        <th width="100" class="center"></th>
                        @endif
                    </tr>
                </thead>

                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td class="center">{{ $user->id }}</td>
                        <td>
                            @if(auth()->user()->role == 'admin')
                            <a href="{{ route('admin.user.edit', ['id' => $user->id]) }}" class="">{{ $user->name }}</a>
                            @else
                            {{ $user->name }}
                            @endif
                        </td>
                        <td>
                            @if(auth()->user()->role == 'admin')
                            <a href="{{ route('admin.user.edit', ['id' => $user->id]) }}">{{ $user->email }}</a>
                            @else
                            {{ $user->email }}
                            @endif
                        </td>
                        <td class="center">
                            <?php
                                $roles = config('settings.role');
                            ?>
                            <span class="label label-sm {{ ($user->role==$roles[1]) ? 'label-warning' : (($user->role==$roles[2]) ? 'label-info' : '') }}">{{ $user->role }}</span>
                            @if(auth()->user()->id == $user->id)
                            &nbsp;(me)
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($user->create)->format('Y-m-d H:i:s') }}</td>
                        <td class="center">
                            <div class="action-buttons">
                                <a href="{{ route('admin.user.offers', ['id' => $user->id]) }}" class="green bigger-140 show-details-btn" title="Show Details">
                                    <i class="ace-icon fa fa-angle-double-down"></i>
                                    <span class="sr-only">Details</span>
                                </a>
                            </div>
                        </td>

                        @if(auth()->user()->role == 'admin')
                        <td class="center">
                            <div class="hidden-sm hidden-xs btn-group">
                                <a href="{{ route('admin.user.edit', ['id' => $user->id]) }}" class="btn btn-xs btn-info">
                                    <i class="ace-icon fa fa-pencil bigger-120"></i>
                                </a>

                                <!-- <a href="" class="btn btn-xs btn-danger">
                                    <i class="ace-icon fa fa-trash-o bigger-120"></i>
                                </a> -->
                            </div>
                        </td>
                        @endif
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div><!-- /.span -->
    </div><!-- /.row -->

</div><!-- /.page-content -->

@stop