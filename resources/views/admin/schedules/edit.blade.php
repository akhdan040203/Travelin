@extends('admin.layouts.app')
@section('page_title', 'Edit Jadwal')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.schedules.index') }}" class="text-primary-500 text-sm font-semibold hover:text-primary-600">← Kembali</a>
</div>

<form action="{{ route('admin.schedules.update', $schedule) }}" method="POST">
    @csrf @method('PUT')
    @include('admin.schedules._form')
</form>
@endsection
