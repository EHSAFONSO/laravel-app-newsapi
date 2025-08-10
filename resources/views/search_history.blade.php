<?php
@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">Histórico de buscas</h2>
    <ul class="bg-white rounded shadow divide-y divide-gray-200">
        @forelse($searches as $search)
            <li class="p-3 flex justify-between items-center">
                <span>{{ $search->title }}</span>
                <span class="text-gray-500 text-sm">{{ $search->created_at->format('d/m/Y H:i') }}</span>
            </li>
        @empty
            <li class="p-3 text-gray-500">Nenhuma busca realizada.</li>
        @endforelse
    </ul>
</div>
@endsection