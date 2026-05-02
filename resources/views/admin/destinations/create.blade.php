@extends('admin.layouts.app')
@section('page_title', 'Tambah Destinasi')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.destinations.index') }}" class="text-primary-500 text-sm font-semibold hover:text-primary-600">← Kembali</a>
</div>

<form action="{{ route('admin.destinations.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @include('admin.destinations._form')
</form>
@endsection
