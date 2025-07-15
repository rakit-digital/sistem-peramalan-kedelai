@if ($errors->any())
    <div class="bg-lighterror text-error px-4 py-3 rounded relative mb-6" role="alert">
        <p class="font-bold">Oops! Harap periksa kembali isian Anda:</p>
        <ul class="list-disc pl-5 mt-2 text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Form Tanggal --}}
<div class="mb-4 sm:mb-6">
    <label for="date" class="block text-sm font-medium mb-2 text-dark">
        Tanggal Pencatatan <span class="text-error">*</span>
    </label>
    <input type="date" id="date" name="date" 
           value="{{ old('date', $isEdit ? $stock->date->format('Y-m-d') : now()->format('Y-m-d')) }}" 
           class="form-control w-full text-sm @error('date') border-error @enderror" 
           required 
           {{-- Jika sedang edit, buat tanggal tidak bisa diubah untuk menjaga integritas --}}
           {{ $isEdit ? 'readonly' : '' }}>
    @error('date')
        <p class="text-error text-xs sm:text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

{{-- Grid untuk Stok, Pembelian, dan Penggunaan --}}
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
    {{-- Stok Awal --}}
    <div class="mb-3 sm:mb-4 sm:col-span-2">
        <label for="opening_stock" class="block text-sm font-medium mb-2 text-dark">
            Stok Awal (kg)
        </label>
        <input type="number" step="0.01" id="opening_stock" 
               value="{{ $isEdit ? $openingStock : $openingStockForNewEntry }}" 
               class="form-control w-full text-sm bg-lightgray" disabled>
        <p class="text-xs text-bodytext mt-1">Stok awal dihitung otomatis dari stok akhir hari sebelumnya.</p>
    </div>

    {{-- Pembelian --}}
    <div class="mb-3 sm:mb-4">
        <label for="purchase_kg" class="block text-sm font-medium mb-2 text-dark">
            Pembelian (kg)
        </label>
        <input type="number" id="purchase_kg" name="purchase_kg" min="0" step="0.01"
               value="{{ old('purchase_kg', $isEdit ? $stock->purchase_kg : 0) }}" 
               class="form-control w-full text-sm @error('purchase_kg') border-error @enderror" required>
        @error('purchase_kg')
            <p class="text-error text-xs sm:text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>
    
    {{-- Penggunaan --}}
    <div class="mb-3 sm:mb-4">
        <label for="usage_kg" class="block text-sm font-medium mb-2 text-dark">
            Penggunaan (kg) <span class="text-error">*</span>
        </label>
        <input type="number" id="usage_kg" name="usage_kg" min="0" step="0.01"
               value="{{ old('usage_kg', $isEdit ? $stock->usage_kg : 0) }}" 
               class="form-control w-full text-sm @error('usage_kg') border-error @enderror" required>
        @error('usage_kg')
            <p class="text-error text-xs sm:text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

{{-- Catatan --}}
<div class="mb-4 sm:mb-6">
    <label for="notes" class="block text-sm font-medium mb-2 text-dark">
        Catatan (Opsional)
    </label>
    <textarea id="notes" name="notes" rows="3" class="form-control w-full text-sm @error('notes') border-error @enderror">{{ old('notes', $isEdit ? $stock->notes : '') }}</textarea>
    @error('notes')
        <p class="text-error text-xs sm:text-sm mt-1">{{ $message }}</p>
    @enderror
</div>