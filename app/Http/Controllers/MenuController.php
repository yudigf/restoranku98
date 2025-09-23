<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

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
   

}
