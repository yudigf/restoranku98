@extends('customer.layouts.master')

@section('title', 'Pesanan Berhasil')

@section('content')

<div class="container-fluid py-5 d-flex justify-content-center align-items-center">
    <div class="receipt border p-4 bg-white shadow" style="width: 450px; margin-top: 5rem;">
        <h5 class="text-center mb-2">Pesanan Berhasil Dibuat</h5>
        @if ($order->payment_method == 'tunai' && $order->status == 'pending' )
            <p class="text-center"> <span class="badge bg-danger">Menunggu Pembayaran</span></p>
        @elseif ($order->payment_method == 'qris' && $order->payment_method == 'pending')
            <p class="text-center"> <span class="badge bg-success">Menunggu Konfirmasi Pembayaran</span></p>
        @else
        <p class="text-center"> <span class="badge bg-success">Pembayaran Berhasil, Pesanan segera di proses</span></p>
        @endif
        <hr>
        <h4 class="fw-bold text-center">Kode Bayar: <br> <span class="text-primary">{{ $order->order_code }}</h4>
        <hr>
        <h5 class="mb-3 text-center">Detail Pesanan</h5>
        <table class="table table-borderless">
            <tbody>
                @foreach ($orderItems as $orderItem )
                    <tr>
                        <td>{{ Str::limit($orderItem->item->name, 25)}} ({{ $orderItem->quantity }})</td>
                        <td class="text-end">{{ 'Rp'. number_format($orderItem->price, 0, ',','.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <table class="table table-borderless">
            <tbody>
                <tr>
                    <td>Subtotal</td>
                    <td class="text-end fw-bold">{{ 'Rp'. number_format($order->subtotal, 0, ',','.') }}</td>
                </tr>
                <tr>
                    <td>Pajak (10%)</td>
                    <td class="text-end fw-bold">{{ 'Rp'. number_format($order->tax, 0, ',','.') }}</td>
                </tr>
                <tr>
                    <td>Total</td>
                    <td class="text-end fw-bold">{{ 'Rp'. number_format($order->grandtotal, 0, ',','.') }}</td>
                </tr>
            </tbody>
        </table>

        @if ($order->payment_method == 'tunai') 
            <p class="small text-center">Silakan Bayar di Kasir </p>
            <p class="small text-center">Terima Kasih</p>
        @elseif ($order->payment_method == 'qris') 
            <p class="small text-center">Pembayaran Sukses, Silakan di Tunggu </p>
            <p class="small text-center">Terima Kasih</p>
        @endif
        <hr>
        <a href="{{ route('menu') }}" class="btn btn-primary w-100">Kembali ke Menu</a>
    </div>
</div>

@endsection

