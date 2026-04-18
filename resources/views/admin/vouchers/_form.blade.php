@php $voucher = $voucher ?? null; @endphp

<div class="grid grid-2 gap-3">
    <div class="form-group"><label class="form-label">Kode Voucher *</label><input type="text" name="code" class="form-input" value="{{ old('code', $voucher?->code) }}" required style="text-transform:uppercase;">@error('code')<p class="form-error">{{ $message }}</p>@enderror</div>
    <div class="form-group"><label class="form-label">Deskripsi</label><input type="text" name="description" class="form-input" value="{{ old('description', $voucher?->description) }}"></div>
</div>
<div class="grid grid-3 gap-3">
    <div class="form-group"><label class="form-label">Tipe *</label><select name="type" class="form-select" required><option value="percentage" {{ old('type', $voucher?->type) === 'percentage' ? 'selected' : '' }}>Persentase (%)</option><option value="fixed" {{ old('type', $voucher?->type) === 'fixed' ? 'selected' : '' }}>Potongan Tetap (Rp)</option></select></div>
    <div class="form-group"><label class="form-label">Nilai *</label><input type="number" name="value" class="form-input" value="{{ old('value', $voucher?->value) }}" required min="1">@error('value')<p class="form-error">{{ $message }}</p>@enderror</div>
    <div class="form-group"><label class="form-label">Target *</label><select name="target" class="form-select" required><option value="all" {{ old('target', $voucher?->target) === 'all' ? 'selected' : '' }}>Semua</option><option value="ticket" {{ old('target', $voucher?->target) === 'ticket' ? 'selected' : '' }}>Tiket</option><option value="fnb" {{ old('target', $voucher?->target) === 'fnb' ? 'selected' : '' }}>F&B</option></select></div>
</div>
<div class="grid grid-2 gap-3">
    <div class="form-group"><label class="form-label">Kuota *</label><input type="number" name="quota" class="form-input" value="{{ old('quota', $voucher?->quota) }}" required min="1">@error('quota')<p class="form-error">{{ $message }}</p>@enderror</div>
    <div class="form-group"><label class="form-label">Min. Pembelian (Rp)</label><input type="number" name="min_purchase" class="form-input" value="{{ old('min_purchase', $voucher?->min_purchase ?? 0) }}" min="0"></div>
</div>
<div class="grid grid-2 gap-3">
    <div class="form-group"><label class="form-label">Berlaku Dari *</label><input type="date" name="valid_from" class="form-input" value="{{ old('valid_from', $voucher?->valid_from?->format('Y-m-d')) }}" required>@error('valid_from')<p class="form-error">{{ $message }}</p>@enderror</div>
    <div class="form-group"><label class="form-label">Berlaku Sampai *</label><input type="date" name="valid_until" class="form-input" value="{{ old('valid_until', $voucher?->valid_until?->format('Y-m-d')) }}" required>@error('valid_until')<p class="form-error">{{ $message }}</p>@enderror</div>
</div>
@if($voucher)
<div class="form-group"><label class="form-checkbox"><input type="checkbox" name="is_active" value="1" {{ $voucher->is_active ? 'checked' : '' }}> Aktif</label></div>
@endif
