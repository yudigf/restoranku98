<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{
    public function index (Request $request)
    {
        $tableNumber = $request->query('meja');
        if ($tableNumber){
            Session::put('tableNumber', $tableNumber);
        }

        $items = Item::where('is_active', 1)->orderBy('name', 'asc')->get();

        return view('customer.menu', compact('items', 'tableNumber'));
    }

    // Cart/Keranjang
    public function cart()
    {
        $cart = Session::get('cart');
        return view('customer.cart',compact('cart'));
    }

    public function addToCart(Request $request)
    {
        $menuId = $request->input('menu_id');
        $menu = Item::find($menuId);

        if (!$menu) {
            return response()->json([
                'status' => 'error',
                'message' => 'Menu tidak Ditemukan'
            ]);
        }

        $cart = Session::get('cart');

        if (isset($cart[$menuId])) {
            $cart[$menuId]['qty'] += 1;
        } else {
            $cart[$menuId] = [
                'id' => $menu->id,
                'name' => $menu->name,
                'price' => $menu->price,
                'qty' => 1,
                'img' => $menu->img
            ];
        }

        Session::put('cart', $cart);

        return response()->json([
            'status' => 'success',
            // 'message' => 'Berhasil menambahkan ke keranjang',
            'message' => 'Berhasil menambahkan ' . $menu->name . ' ke keranjang',
            'cart' => $cart
        ]);
    }

    public function updateCart(Request $request)
    {
        $itemId = $request->input('id');
        $newQty = $request->input('qty');

        if ($newQty < 0) {
            return response()->json([ 'success' => 'false' ]);
        }

        $cart = Session::get('cart');
        if(isset($cart[$itemId])) {
          $cart[$itemId]['qty'] = $newQty;
          Session::put('cart', $cart);
          Session::flash('success', 'Jumlah Item Berhasil Diperbarui');

          return response()->json([ 'success' => 'true' ]);
        }

        return response()->json([ 'success' => 'false' ]);
    }

    public function removeCart(Request $request)
    {
        $itemId = request()->input('id');
        $cart = Session::get('cart');

        if(isset($cart[$itemId])) {
          unset($cart[$itemId]);
          Session::put('cart', $cart);
          Session::flash('success', 'Item Berhasil Dihapus dari Keranjang');

          return response()->json([ 'success' => 'true' ]);
        }
    }

    public function clearCart()
    {
        Session::forget('cart');
        return redirect()->route('cart')->with('success', 'Keranjang berhasil dikosongkan');
    }
   
    // Checkout
    public function checkout()
    {
        $cart = Session::get('cart');
       
        if(empty($cart)) {
            return redirect()->route('menu')->with('error', 'Keranjang kosong, silakan pilih menu terlebih dahulu.');
        }

        $tableNumber = Session::get('tableNumber');

        return view('customer.checkout', compact('cart', 'tableNumber'));
    }

    public function storeOrder(Request $request)
    {
        $cart = Session::get('cart');
        $tableNumber = Session::get('tableNumber');

        if(empty($cart)) {
            return redirect()->route('cart')->with('error', 'Keranjang masih kosong, silakan pilih menu terlebih dahulu.');
        }

        $validator = Validator::make($request->all(), [
            'fullname' => 'required|string|max:225',
            'phone' => 'required|string|max:15',
        ]);

        if ($validator->fails()) {
            return redirect()->route('checkout')->withErrors($validator);
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['qty'];
        }

        $totalAmount = 0;
        foreach ($cart as $item) {
            $totalAmount += $item['price'] * $item['qty'];

            $itemDetails[] = [
                'id' => $item['id'],
                'name' => $item['name'],
                'price' => (int) $item['price'] + (int) $item['price'] * 0.1, // + 10% pajak
                'quantity' => $item['qty'],
                'name' => substr($item['name'], 0, 50), // max 50 karakter
            ];
        }

        $user = User::firstOrCreate([
            'fullname' => $request->input('fullname'),
            'phone' => $request->input('phone'),
            'role_id' => 4 // Pelanggan
        ]);

        $order = Order::create([
            'order_code' => 'ORD-'.$tableNumber.'-'. time(),
            'user_id' => $user->id,
            'subtotal' => $totalAmount,
            'tax' => $totalAmount * 0.1, // 10% pajak
            'grandtotal' => $totalAmount + ($totalAmount * 0.1), // + 10% pajak
            'status' => 'pending',
            'table_number' => $tableNumber,
            'payment_method' => $request->payment_method,
            'note' => $request->note,
        ]);

        foreach ($cart as $itemId =>$item) {
           OrderItem::create([
                'order_id' => $order->id,
                'item_id' => $item['id'],
                'quantity' => $item['qty'],
                'price' => $item['price'] * $item['qty'],
                'tax' => 0.1 * $item['price'] * $item['qty'],
                'total_price' => ($item['price'] * $item['qty']) + (0.1 * $item['price'] * $item['qty']) // harga + pajak x qty
           ]);
        }

        Session::forget('cart');

        return redirect()->route('checkout.success', ['orderId' => $order->order_code])->with('success', 'Pesanan berhasil dibuat. Terima kasih telah memesan!');


    }

    public function checkoutSuccess($orderId) 
    {
        $order = Order::where('order_code', $orderId)->first();

        if (!$order){
            return redirect()->route('menu')->with('error', 'Pesanan Tidak Ditemukan');
        }

        $orderItems = OrderItem::where('order_id', $order->id)->get();

        if ($order->payment_method == 'qris') {
            $order->status = 'settlement';
            $order->save();
        }

        return view('customer.success', compact('order', 'orderItems'));
    }
}
