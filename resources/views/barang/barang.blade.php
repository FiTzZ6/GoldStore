@extends('layouts.app') {{-- Ganti dengan layout Anda jika berbeda --}}

@section('content')
<div style="margin-left:10px;margin-right:20px;">

    @if (session('insert'))
        <div class="alert alert-success alert-st-one" role="alert" id="alertsuccess">
            <i class="fa fa-check edu-checked-pro admin-check-pro admin-check-pro-none" aria-hidden="true"></i>
            <p class="message-mg-rt message-alert-none"><strong>Added! </strong> {{ session('insert') }}</p>
        </div>
    @elseif (session('msgdelete'))
        <div class="alert alert-danger alert-mg-b alert-st-four" role="alert" id="alertsuccess">
            <i class="fa fa-window-close edu-danger-error admin-check-pro" aria-hidden="true"></i>
            <i class="fa fa-times edu-danger-error admin-check-pro" aria-hidden="true"></i>
            <p class="message-mg-rt"><strong>Deleted! </strong> {{ session('msgdelete') }}</p>
        </div>
    @elseif (session('update'))
        <div class="alert alert-info alert-st-two" role="alert">
            <i class="fa fa-info-circle edu-inform admin-check-pro admin-check-pro-none" aria-hidden="true"></i>
            <p class="message-mg-rt message-alert-none"><strong>Updated! </strong> {{ session('update') }}</p>
        </div>
    @endif

    <div class="col-lg-12" style="margin-bottom: 20px;">
        <form id="form-filter">
            <div class="col-lg-1">
                <label>FILTER </label>
            </div>

            @if ($sesstoko === 'HLD')
                <div class="col-lg-2">
                    <input type="date" name="tglinput" id="tglinput" class="form-control-sm" placeholder="Tanggal Input">
                </div>
            @endif

            <div class="col-lg-2">
                {!! $form_cabang !!}
            </div>

            <div class="col-lg-2">
                <select id="baki" name="baki" class="chosen-select">
                    <option value="">Semua Baki</option>
                    @foreach ($baki as $bk)
                        <option value="{{ $bk->kdbaki }}">{{ $bk->kdbaki }} - {{ $bk->namabaki }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-2">
                <select id="supplier" name="supplier" class="chosen-select">
                    <option value="">Semua Supplier</option>
                    @foreach ($supplier as $sup)
                        <option value="{{ $sup->kdsupplier }}">{{ $sup->kdsupplier }} - {{ $sup->namasupplier }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-1">
                <button type="button" id="btn-filter" class="btn btn-primary">Filter</button>
            </div>
        </form>
    </div>

    <div class="sparkline13-list">
        <div class="sparkline13-hd">
            <div class="main-sparkline13-hd">
                <div class="sparkline13-graph" style="margin-top:10px;">
                    <div class="datatable-dashv1-list custom-datatable-overright">
                        <table id="table_id" class="display compact" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Nama Barang</th>
                                    <th>Barcode</th>
                                    <th>KD Baki</th>
                                    <th>Berat</th>
                                    <th>Kadar</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Data akan dimuat dengan AJAX atau @foreach --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
