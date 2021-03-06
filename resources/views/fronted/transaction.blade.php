@extends('fronted.layouts.app')
@section('title', 'Transaction ')
@section('content')
@section('transaction', 'active')
    <div class="transaction">
        <div class="card mb-3 pb-0">
            <div class="card-body p-2">
                <h6 >
                    <i class="fa fa-filter"></i>
                    Filter
                </h6>
                <div class="row">
                    <div class="col-6">
                        <div class="input-group my-2">
                            <div class="input-group-prepend">
                                <label class="input-group-text p-1">Date</label>
                            </div>
                            <input type="text" class="form-control date" value="{{ request()->date ?? date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="input-group my-2">
                            <div class="input-group-prepend">
                                <label class="input-group-text p-1">Type</label>
                            </div>
                            <select class="custom-select type">
                                <option value="">All</option>
                                <option value="1" @if (request()->type == 1) selected @endif>Income</option>
                                <option value="2" @if (request()->type == 2) selected @endif>Expense</option>

                            </select>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <h6>Transactions</h6>
        <div class="infinite-scroll">
            @foreach ($transactions as $transaction)
                <a href="{{ url("/transaction/$transaction->trx_id") }}">
                    <div class="card mb-3">
                        <div class="card-body p-2">
                            <div class="d-flex justify-content-between">
                                <h6 class="mb-1"><span>Trx ID : </span>{{ $transaction->trx_id }}</h6>
                                <p class="mb-1 @if ($transaction->type == 1) text-success
                                @elseif($transaction->type == 2) text-danger @endif">
                                    @if ($transaction->type == 1)
                                        +
                                    @elseif ($transaction->type==2)
                                        -
                                    @endif
                                    {{ $transaction->amount }} <small>MMK</small>
                                </p>
                            </div>
                            <p class="mb-1 text-muted">
                                @if ($transaction->type == 1)
                                    From -
                                @elseif($transaction->type == 2)
                                    To -
                                @endif
                                {{ $transaction->source ? $transaction->source->name : '-' }}
                            </p>
                            <p class="text-muted mb-1">
                                {{ $transaction->created_at }}
                            </p>
                        </div>
                    </div>
                </a>
            @endforeach
            {{ $transactions->links() }}
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $('.type').on('change', function() {
            var type = $('.type').val()
            history.pushState(null, '', `?type=${type}`)
            window.location.reload()
        })
        //Date Range Picker
        $('.date').daterangepicker({
            "singleDatePicker": true,
            "autoApply": true,
            "locale": {
                "format": "YYYY-MM-DD",
            },
        });

        $('.date').on('apply.daterangepicker', function(ev, picker) {
            var date = $('.date').val()
            var type = $('.type').val()
            history.pushState(null, '', `?date=${date}&type=${type}`)
            window.location.reload()
        });
        //###############jscroll################################
        $('ul.pagination').hide();
        $('.infinite-scroll').jscroll({
            autoTrigger: true,
            loadingHtml: '<div class="text-center"><img src="{{ asset('img/loading.png') }}" alt="Loading..." style="width:30px;"/></div>',
            padding: 0,
            nextSelector: '.pagination li.active + li a',
            contentSelector: 'div.infinite-scroll',
            callback: function() {
                $('ul.pagination').remove();
            }
        });

    </script>
@endsection
