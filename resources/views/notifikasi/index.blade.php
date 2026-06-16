@extends('layouts.app')
@section('page-title', 'Notifikasi Stok')

@section('content')
<div class="page-header">
    <div class="page-header-title">
        <h2>Notifikasi Stok</h2>
        <p>Alert stok habis, stok menipis, dan penambahan stok</p>
    </div>
</div>

@if($notifikasis->isEmpty())
    <div class="card">
        <div class="card-body text-center py-5 text-muted">
            <i class="bi bi-bell-slash fs-1 d-block mb-3 opacity-25"></i>
            <p class="mb-0">Tidak ada notifikasi stok saat ini.</p>
        </div>
    </div>
@else
<div class="d-flex flex-column gap-2">
    @foreach($notifikasis as $notif)
    @php
        $icon  = match($notif->tipe_notifikasi) { 'stok_habis' => 'bi-x-circle-fill', 'stok_minimal' => 'bi-exclamation-triangle-fill', default => 'bi-plus-circle-fill' };
        $color = match($notif->tipe_notifikasi) { 'stok_habis' => '#ef4444', 'stok_minimal' => '#f59e0b', default => '#10b981' };
        $bg    = match($notif->tipe_notifikasi) { 'stok_habis' => '#fff1f2', 'stok_minimal' => '#fffbeb', default => '#f0fdf4' };
    @endphp
    <div class="card" style="border-left: 4px solid {{ $color }}; background: {{ $bg }}; opacity: {{ $notif->dibaca ? 0.7 : 1 }};">
        <div class="card-body py-3 d-flex align-items-start gap-3">
            <i class="bi {{ $icon }} fs-4 mt-1" style="color: {{ $color }}; flex-shrink:0;"></i>
            <div class="flex-1">
                <div class="fw-semibold" style="font-size:14px;">{{ $notif->pesan }}</div>
                <div class="text-muted" style="font-size:12px; margin-top:3px;">
                    <i class="bi bi-clock me-1"></i>{{ $notif->created_at->locale('id')->diffForHumans() }}
                    @if($notif->barang)
                        &bull; Barang: <strong>{{ $notif->barang->nama }}</strong>
                    @endif
                    @if($notif->dibaca)
                        &bull; <span class="badge badge-secondary">Sudah dibaca</span>
                    @endif
                </div>
            </div>
            <form action="{{ route('notifikasi.destroy', $notif->id) }}" method="POST" onsubmit="return confirm('Hapus notifikasi ini?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-secondary" title="Hapus">
                    <i class="bi bi-trash"></i>
                </button>
            </form>
        </div>
    </div>
    @endforeach
</div>
@endif
@endsection
