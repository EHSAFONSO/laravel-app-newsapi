<?php
<!-- resources/views/news_search.blade.php -->
<form method="GET" action="{{ route('news.search') }}">
    <input type="text" name="title" placeholder="Digite o título da notícia" required>
    <button type="submit">Buscar</button>
</form>

@if(isset($news))
    <h2>Resultado:</h2>
    <div>{{ $news->content ?? 'Notícia não encontrada.' }}</div>
@endif