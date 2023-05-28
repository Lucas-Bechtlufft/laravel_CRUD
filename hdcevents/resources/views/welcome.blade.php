@extends('layouts.main')



@section('title', 'HDC Events')

@section('content')

    <h1>Algum Título</h1>
    <img src="img/banner.jpg" alt="Banner">
    @if (10 > 5)
        <p>A condiçao é verdadeira</p>
    @endif

    <p>{{ $nome }}</p>

    @if ($nome == 'Ronaldo')
        <p>O nome é Ronaldo</p>
    @else
        <p>O nome não é pedro e sim {{ $nome }} e ele tem {{ $idade }} anos e trabalha como
            {{ $profissao }}</p>
    @endif

    @for ($i = 0; $i < count($arr); $i++)
        <p>{{ $arr[$i] }} - {{ $i }}</p>
        @if ($i == 2)
            <p>o i é = 2</p>
        @endif
    @endfor

    @foreach ($nomes as $nome)
        <p>{{ $nome }} - {{ $loop->index }} </p>
    @endforeach


@endsection
