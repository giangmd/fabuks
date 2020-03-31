@extends('admin.layouts.main')
@section('title', 'History Offers')

@section('content')

@includeIf('admin.partials.breadcrumb')

<div class="page-content">

    <div class="page-header">
        <h1>
            History Offers
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                {{ $user->name . ' (' . $user->email . ')' }}
            </small>
        </h1>
    </div><!-- /.page-header -->

    <div class="row">
        <div class="col-xs-12">
            <table id="simple-table" class="table  table-bordered table-hover">
                <thead>
                    <tr>
                        <th width="50" class="center">#</th>
                        <th class="center">From</th>
                        <th class="center">To</th>
                        <th class="center">Amount</th>
                        <th class="center">Price order</th>
                        <th class="center">Price success</th>
                        <th width="180" class="center">
                            <i class="ace-icon fa fa-clock-o bigger-110 hidden-480"></i>
                            Offer at
                        </th>
                        <th width="120" class="center">Status</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($user->tradeHistory as $trade)
                    <tr>
                        <td class="center">{{ $loop->index + 1 }}</td>
                        <td>{{ $trade->from }}</td>
                        <td>{{ $trade->to }}</td>
                        <td>{{ $trade->amount }}</td>
                        <td>{{ $trade->price_order }}</td>
                        <td>{{ $trade->price_done }}</td>
                        <td>{{ \Carbon\Carbon::parse($trade->create)->format('Y-m-d H:i:s') }}</td>
                        <td class="center">
                            {!! ($trade->status == 0) ? '<span class="label label-warning">Pendding</span>' : '<span class="label label-success">Success</span>' !!}
                        </td>
                    </tr>
                    @endforeach

                    <tr>
                        @if(!empty($balanceTotal))
                        <td colspan="8">Total Balance (not include pendding offers): <strong>{{ $balanceTotal->balance }}</strong>{{ config('settings.fabuk_unit') }}</td>
                        @endif
                    </tr>

                </tbody>
            </table>
        </div><!-- /.span -->
    </div><!-- /.row -->

</div><!-- /.page-content -->

@stop