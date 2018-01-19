<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pedido;

class CarrinhoController extends Controller
{
    function __construct() {
        $this->middleware('auth');
    }
    
    public function index() {
        
        $pedidos = Pedido::where([
            'status' => 'RE',
            'user_id' => Auth::id()
        ])->get();
        
        dd([
            $pedidos, 
            $pedidos[0]->pedido_produtos,
            $pedidos[0]->pedido_produtos[0]->produto
        ]);
        
        return view('carrinho.index', compact('pedidos'));
    }
}
