<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Pedido;
use App\Produto;
use App\PedidoProduto;

class CarrinhoController extends Controller
{
    function __construct()
    {
        // obriga estar logado;
        $this->middleware('auth');
    }

    public function index()
    {

        $pedidos = Pedido::where([
            'status'  => 'RE',
            'user_id' => Auth::id()
            ])->get();

        return view('carrinho.index', compact('pedidos'));
    }
    
    public function adicionar() {
        
        $this->middleware('VerifyCsrToken');
        
        $req = Request();
        $idproduto = $req->input('id');
        
        $produto = Produto::find($idproduto);
        if( empty($produto->id)) {
            $req->session()->flash('mensagem-falha', 'Produto não encontado em nossa loja!');
            return redirect()->route('carrinho.index');
        }
        
        $idusuario = Auth::id();
        
        $idpedido = Pedido::consultaId([
            'user_id' => $idusuario,
            'status' => 'RE' //Reservada
        ]);
        
        if ( empty($idpedido)) {
            $pedido_novo = Pedido::create([
                'user_id' => $idusuario,
                'status' => 'RE'
            ]);
            
            $idpedido = $pedido_novo->id;
        }
        
        PedidoProduto::create([
            'pedido_id' => $idpedido,
            'produto_id' => $idproduto,
            'valor' => $produto->valor,
            'status' => 'RE'
        ]);
        
        $req->session()->flash('mensagem-sucesso', 'Produto adicionado ao carrinho com sucesso!');
        
        return redirect()->route('carrinho.index');
    }
    
    public function remover() {
        
        $this->middleware('VerifyCsrfToken');

        $req = Request();
        $idpedido = $req->input('pedido_id');
        $idproduto = $req->input('produto_id');
        $remove_apenas_item = (boolean)$req->input('item');
        $idusuario = Auth::id();
        
        $idpedido = Pedido::consultaId([
            'id' => $idpedido,
            'user_id' => $idusuario,
            'status' => 'RE'
        ]);
        
        if(empty($idpedido)) {
            $req->session()->Flash('mensagem-falha', 'Pedido não encontrado!');
            return redirect()->route('carrinho.index');
        }
        
        $where_produto = [
            'pedido_id' => $idpedido,
            'produto_id' => $idproduto
        ];
        
        $produto = PedidoProduto::where($where_produto)->orderBy('id', 'desc')->first();
        if( empty($produto->id) ) {
            $req->session()->flash('mensagem-falha', 'Produto não encontrado no carrinho!');
            return redirect()->route('carrinho.index');
        }
        
        if($remove_apenas_item) {
            $where_produto['id'] = $produto->id;
        }
        PedidoProduto::where($where_produto)->delete();
        
        $check_pedido = PedidoProduto::where([
            'pedido_id' => $produto->pedido_id
        ])->exists();
        
        if( !check_pedido ) {
            Pedido::where([
                'pedido_id' => $pedido->pedido_id
            ])->delete();
        }
        
        $req->session()->flash('mensagem-sucesso', 'Produto removido do carrinho com sucesso!');
        
        return redirect()->route('carrinho.index');
    }
    
}
