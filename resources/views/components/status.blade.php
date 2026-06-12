@props(['status', 'diasAtraso' => 0])

@php
    $statusClass = match ($status) {
        'Pago' => 'status-paid',
        'Atrasado' => 'status-overdue',
        'Cancelado' => 'status-canceled',
        default => 'status-pending',
    };
@endphp
<span class="status-label {{ $statusClass }}">
    {{ $status }}
    @if($status === 'Atrasado' && $diasAtraso > 0)
        · {{ $diasAtraso }} {{ $diasAtraso === 1 ? 'dia' : 'dias' }}
    @endif
</span>
