@extends('admin.layouts.app')
@section('page_title', 'Edit Destinasi')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.destinations.index') }}" class="text-primary-500 text-sm font-semibold hover:text-primary-600">← Kembali</a>
</div>

<form action="{{ route('admin.destinations.update', $destination) }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')
    @include('admin.destinations._form')
</form>
@endsection
