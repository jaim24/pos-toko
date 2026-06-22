@extends('layouts.app')
@section('title', 'Produk')

@section('content')
<div class="max-w-[1400px] mx-auto" x-data="productManager()">
    {{-- Flash --}}
    @if(session('success'))
    <div class="mb-4 px-4 py-3 bg-secondary/10 border border-secondary/20 text-secondary rounded-2xl text-sm font-semibold flex items-center gap-2">{{ session('success') }}</div>
    @endif
    @if($errors->any())
    <div class="mb-4 px-4 py-3 bg-error/10 border border-error/20 text-error rounded-2xl text-sm font-semibold">{{ $errors->first() }}</div>
    @endif

    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 sm:gap-0 mb-6 sm:mb-8">
        <div>
            <h2 class="text-2xl sm:text-3xl font-bold text-on-surface tracking-tight mb-1 sm:mb-2">Produk</h2>
            <p class="text-on-surface-variant text-xs sm:text-sm">Kelola semua produk di toko Anda.</p>
        </div>
        <button @click="openModal()" class="w-full sm:w-auto px-4 sm:px-5 py-2.5 bg-secondary text-white rounded-full text-xs sm:text-sm font-medium flex items-center justify-center gap-2 hover:bg-secondary/90 transition-colors shadow-sm">
            <span class="material-symbols-outlined text-[16px] sm:text-[18px]">add</span> Tambah Produk
        </button>
    </div>

    {{-- Category Filter --}}
    <div class="flex gap-2 sm:gap-3 mb-5 sm:mb-6 overflow-x-auto pb-1">
        <button @click="filterCategory = ''" class="whitespace-nowrap px-4 sm:px-5 py-2 sm:py-2.5 rounded-full bg-secondary text-white font-medium text-xs sm:text-sm shadow-sm transition-all shrink-0">
            Semua Produk
        </button>
        @foreach($categories as $cat)
        <button @click="filterCategory = '{{ $cat->id }}'" class="whitespace-nowrap px-4 sm:px-5 py-2 sm:py-2.5 rounded-full bg-white border border-outline-variant text-on-surface-variant font-medium text-xs sm:text-sm hover:border-secondary hover:text-secondary transition-all shadow-sm shrink-0">
            {{ $cat->name }}
        </button>
        @endforeach
    </div>

    {{-- Products Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-outline-variant/30 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm min-w-[700px]">
                <thead>
                    <tr class="border-b border-outline-variant/20 bg-surface-container-low">
                        <th class="text-left px-3 sm:px-6 py-3 sm:py-4 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Produk</th>
                        <th class="text-left px-3 sm:px-6 py-3 sm:py-4 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Barcode</th>
                        <th class="text-left px-3 sm:px-6 py-3 sm:py-4 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Kategori</th>
                        <th class="text-right px-3 sm:px-6 py-3 sm:py-4 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Harga</th>
                        <th class="text-center px-3 sm:px-6 py-3 sm:py-4 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Stok</th>
                        <th class="text-center px-3 sm:px-6 py-3 sm:py-4 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Status</th>
                        <th class="text-center px-3 sm:px-6 py-3 sm:py-4 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/10">
                    @forelse($products as $p)
                    <tr class="hover:bg-surface-container-low/50 transition-colors"
                        x-show="filterCategory === '' || filterCategory === '{{ $p->category_id }}'">
                        <td class="px-3 sm:px-6 py-3 sm:py-4">
                            <div class="flex items-center gap-2 sm:gap-3">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-surface-container rounded-lg flex items-center justify-center shrink-0">
                                    @if($p->image)
                                    <img src="{{ asset('storage/' . $p->image) }}" class="w-full h-full object-cover rounded-lg">
                                    @else
                                    <span class="material-symbols-outlined text-on-surface-variant text-lg sm:text-xl">inventory_2</span>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-medium text-on-surface text-xs sm:text-sm">{{ $p->name }}</p>
                                    <p class="text-[10px] sm:text-xs text-on-surface-variant font-mono">{{ $p->slug }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4 font-mono text-[10px] sm:text-xs text-on-surface-variant">{{ $p->barcode ?? '—' }}</td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4">
                            <span class="px-2 sm:px-2.5 py-1 rounded-full text-[10px] sm:text-xs font-medium bg-surface-container text-on-surface-variant">{{ $p->category->name }}</span>
                        </td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4 text-right font-mono text-xs sm:text-sm text-on-surface">Rp {{ number_format($p->price, 0, ',', '.') }}</td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4 text-center">
                            <span class="px-2 sm:px-2.5 py-1 rounded-full text-[10px] sm:text-xs font-mono font-medium {{ $p->isLowStock() ? 'bg-error/10 text-error' : 'bg-secondary/10 text-secondary' }}">
                                {{ $p->stock }}
                            </span>
                        </td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4 text-center">
                            <span class="px-2 sm:px-2.5 py-1 rounded-full text-[10px] sm:text-xs font-medium {{ $p->is_active ? 'bg-secondary/10 text-secondary' : 'bg-surface-container-highest text-on-surface-variant' }}">
                                {{ $p->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4 text-center">
                            <div class="flex items-center justify-center gap-1 sm:gap-2">
                                <button @click="openModal({{ $p->id }}, {{ $p->category_id }}, '{{ $p->name }}', {{ $p->price }}, {{ $p->stock }}, '{{ $p->description }}', {{ $p->is_active ? 'true' : 'false' }}, '{{ $p->barcode }}')"
                                        class="p-1.5 sm:p-2 text-on-surface-variant hover:text-secondary hover:bg-surface-container-low rounded-lg transition-colors" title="Edit">
                                    <span class="material-symbols-outlined text-base sm:text-lg">edit</span>
                                </button>
                                <form action="{{ route('admin.products.destroy', $p) }}" method="POST" onsubmit="return confirm('Hapus produk {{ $p->name }}?')" class="inline">
                                    @csrf @method('DELETE')
                                    <button class="p-1.5 sm:p-2 text-on-surface-variant hover:text-error hover:bg-error/10 rounded-lg transition-colors" title="Hapus">
                                        <span class="material-symbols-outlined text-base sm:text-lg">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-3 sm:px-6 py-8 sm:py-12 text-center text-on-surface-variant text-xs sm:text-sm">Belum ada produk. Tambahkan produk pertama Anda.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Create / Edit --}}
    <div x-show="show" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-on-surface/60 backdrop-blur-sm" @click.self="show = false">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto border border-outline-variant/20" @click.stop>
            <div class="p-5 sm:p-6 border-b border-outline-variant/20">
                <h3 class="text-lg font-bold text-on-surface" x-text="editing ? 'Edit Produk' : 'Tambah Produk'"></h3>
            </div>
            <form :action="editing ? '{{ route('admin.products.update', '__ID__') }}'.replace('__ID__', editing) : '{{ route('admin.products.store') }}'" method="POST" enctype="multipart/form-data" class="p-5 sm:p-6 space-y-4">
                @csrf
                <template x-if="editing"><input type="hidden" name="_method" value="PUT"></template>
                <div>
                    <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">Kategori</label>
                    <select name="category_id" x-model="category_id" required
                            class="w-full border border-outline-variant rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-secondary/20 focus:border-secondary transition-all">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">Nama Produk</label>
                    <input type="text" name="name" x-model="name" required maxlength="150"
                           class="w-full border border-outline-variant rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-secondary/20 focus:border-secondary transition-all"
                           placeholder="Contoh: Nasi Goreng">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">Barcode</label>
                    <div class="flex gap-2">
                        <input type="text" name="barcode" x-model="barcode" maxlength="50"
                               class="flex-1 border border-outline-variant rounded-xl px-4 py-2.5 text-sm font-mono focus:ring-2 focus:ring-secondary/20 focus:border-secondary transition-all"
                               placeholder="Scan / ketik barcode">
                        <button type="button" @click="scanBarcode()"
                                class="px-4 py-2.5 bg-surface-container border border-outline-variant rounded-xl text-xs font-medium text-on-surface-variant hover:bg-secondary/10 hover:text-secondary transition-colors flex items-center gap-1.5 shrink-0">
                            <span class="material-symbols-outlined text-[16px]">barcode_scanner</span> Scan
                        </button>
                    </div>
                    <p class="text-[10px] text-on-surface-variant mt-1">Kosongkan untuk auto-generate barcode EAN-13.</p>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">Deskripsi</label>
                    <textarea name="description" x-model="description" rows="2"
                              class="w-full border border-outline-variant rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-secondary/20 focus:border-secondary transition-all"
                              placeholder="Deskripsi produk (opsional)"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">Harga (Rp)</label>
                        <input type="number" name="price" x-model="price" required min="1"
                               class="w-full border border-outline-variant rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-secondary/20 focus:border-secondary transition-all font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">Stok</label>
                        <input type="number" name="stock" x-model="stock" required min="0"
                               class="w-full border border-outline-variant rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-secondary/20 focus:border-secondary transition-all font-mono">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">Gambar</label>
                    <input type="file" name="image" accept="image/*"
                           class="w-full text-sm file:mr-3 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-surface-container file:text-on-surface hover:file:bg-surface-container-high transition-all">
                    <p class="text-[10px] text-on-surface-variant mt-1">Biarkan kosong jika tidak ingin mengganti gambar. Max 2MB. Format: JPG, PNG, WebP.</p>
                </div>
                <div class="flex items-center gap-2">
                    <input type="hidden" name="is_active" :value="is_active ? '1' : '0'">
                    <button type="button" @click="is_active = !is_active"
                            :class="is_active ? 'bg-secondary' : 'bg-surface-container-highest'"
                            class="relative w-10 h-6 rounded-full transition-colors duration-200">
                        <span :class="is_active ? 'translate-x-5' : 'translate-x-1'"
                              class="absolute top-0.5 left-0 w-5 h-5 bg-white rounded-full shadow transition-transform duration-200"></span>
                    </button>
                    <span class="text-xs font-medium text-on-surface-variant" x-text="is_active ? 'Aktif' : 'Nonaktif'"></span>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" @click="show = false" class="flex-1 px-4 py-2.5 bg-white border border-outline-variant text-on-surface rounded-full text-sm font-semibold hover:bg-surface-container-low transition-colors">Batal</button>
                    <button type="submit" class="flex-1 px-4 py-2.5 bg-secondary text-white rounded-full text-sm font-semibold hover:bg-[#005236] transition-colors shadow-sm" x-text="editing ? 'Simpan' : 'Tambah'"></button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@push('scripts')
<script>
function productManager() {
    return {
        show: false,
        editing: null,
        filterCategory: '',
        category_id: '',
        name: '',
        barcode: '',
        description: '',
        price: 0,
        stock: 0,
        is_active: true,
        openModal(id = null, catId = '', name = '', price = 0, stock = 0, desc = '', active = true, barcode = '') {
            this.editing = id;
            this.category_id = catId;
            this.name = name;
            this.barcode = barcode;
            this.description = desc;
            this.price = price;
            this.stock = stock;
            this.is_active = active;
            this.show = true;
            this.$nextTick(() => {
                const inp = this.$el.querySelector('input[name="name"]');
                if (inp) inp.focus();
            });
        },
        scanBarcode() {
            const code = prompt('Scan / masukkan kode barcode:');
            if (code && code.trim()) {
                this.barcode = code.trim();
            }
        }
    }
}
</script>
@endpush
