@extends('layout')
@section('pagina_titulo', $registro->nome )

@section('pagina_conteudo')

<div class="container">
    <div class="row">
        <h3>{{ $registro->nome }}</h3>
        <div class="divider"></div>
        <div class="section col s12 m6 l4">
            <div class="card small">
                <img class="col s12 m12 l12 materialboxed" data-caption="{{ $registro->nome }}" src="{{ $registro->imagem }}" alt="{{ $registro->nome }}" title="{{ $registro->nome }}">
            </div>
        </div>
        <div class="section col s12 m6 l6">
            <h4 class="left col l6"> R$ {{ number_format($registro->valor, 2, ',', '.') }} </h4>
            
        </div>
        <div class="section col s12 m6 l6">
            {!! $registro->descricao !!}
        </div>
    </div>
</div>
@endsection