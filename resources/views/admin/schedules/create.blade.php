@extends('admin.layouts.app')
@section('page_title', 'Tambah Jadwal')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.schedules.index') }}" class="text-primary-500 text-sm font-semibold hover:text-primary-600">← Kembali</a>
</div>

<form action="{{ route('admin.schedules.store') }}" method="POST">
    @csrf
    @include('admin.schedules._form')
</form>
@endsection
