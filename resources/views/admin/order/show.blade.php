@extends('admin.layouts.master')
@section('title', 'Detail Pesanan')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/admin/extensions/simple-datatables/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/compiled/css/table-datatable.css') }}">
@endsection

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Detail Pesanan</h3>
                    <p class="text-subtitle text-muted">Informasi Detail Pesanan yang Terdaftar</p>
                </div>
                {{-- <div class="col-12 col-md-6 order-md-2 order-first">
                    <a href="{{ route('roles.create') }}" class="btn btn-primary float-start float-lg-end">
                        <i class="bi bi-plus"></i>
                        Tambah Role
                    </a>
                </div> --}}
            </div>
        </div>
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <h4>Kode Pesanan {{ $order->order_code }}</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nama Customer:</strong> {{ $order->user->fullname }}</p>
                            <p><strong>Total Pembelian:</strong> {{ 'Rp.' . number_format($order->grandtotal, 0, ',', '.') }}</p>
                            <p><strong>Status:</strong> 
                                <span class="badge {{ 
                                    $order->status == 'settlement' ? 'bg-success' : 
                                    ($order->status == 'pending' ? 'bg-warning' : 
                                    ($order->status == 'cooked' ? 'bg-info' : 'bg-danger')) 
                                }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </p>
                            <p><strong>No. Meja:</strong> {{ $order->table_number }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Metode Pembayaran:</strong> {{ ucfirst($order->payment_method) }}</p>
                            <p><strong>Catatan:</strong> {{ $order->note ?? 'Tidak ada catatan' }}</p>
                            <p><strong>Dibuat Pada:</strong> {{ $order->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

        </section>

        <section class="section">
            <div class="card">
                <div class="card-header">
                    <h4>Detail Menu yang dipesan</h4>
                </div>
                <div class="card-body">
                  
                    <table class="table table-striped" id="table1">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Gambar</th>
                                <th>Nama Menu</th>
                                <th>Jumlah</th>
                                <th>Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orderItems as $orderItem)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <img src="{{ asset('img_item_upload/'. $orderItem->item->img) }}" width="60" class="img-fluid rounded-top" alt="" onerror="this.onerror=null;this.src='{{ $orderItem->item->img }}';">
                                    </td>
                                    <td>{{ $orderItem->item->name }}</td>
                                    <td>{{ $orderItem->quantity }}</td>
                                    <td>{{ 'Rp.'. number_format($orderItem->price, 0, ',','.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                            <tr>
                                <th colspan="4" class="text-end">Total Harga:</th>
                                <th>{{ 'Rp.' . number_format($order->subtotal, 0, ',', '.') }}</th>
                            </tr>
                            <tr>
                                <th colspan="4" class="text-end">Pajak</th>
                                <th>{{ 'Rp.' . number_format($order->tax, 0, ',', '.') }}</th>
                            </tr>
                            <tr>
                                <th colspan="4" class="text-end">Grand Total:</th>
                                <th>{{ 'Rp.' . number_format($order->grandtotal, 0, ',', '.') }}</th>
                    </table>
                </div>
            </div>
        </section>

    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/admin/extensions/simple-datatables/umd/simple-datatables.js') }}"></script>
    <script src="{{ asset('assets/admin/static/js/pages/simple-datatables.js') }}"></script>
@endsection