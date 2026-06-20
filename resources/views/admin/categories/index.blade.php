@extends('layouts.app')
@section('title', 'Kategori')

@section('content')
<div class="max-w-[1400px] mx-auto" x-data="categoryManager()">
    {{-- Flash --}}
    @if(session('success'))
    <div class="mb-4 px-4 py-3 bg-secondary/10 border border-secondary/20 text-secondary rounded-2xl text-sm font-semibold flex items-center gap-2">{{ session('success') }}</div>
    @endif
    @if($errors->any())
    <div class="mb-4 px-4 py-3 bg-error/10 border border-error/20 text-error rounded-2xl text-sm font-semibold">{{ $errors->first() }}</div>
    @endif

    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 sm:gap-0 mb-6 sm:mb-8">
        <div>
            <h2 class="text-2xl sm:text-3xl font-bold text-on-surface tracking-tight mb-1 sm:mb-2">Kategori</h2>
            <p class="text-on-surface-variant text-xs sm:text-sm">Kelola kategori produk.</p>
        </div>
        <button @click="openModal()" class="w-full sm:w-auto px-4 sm:px-5 py-2.5 bg-secondary text-white rounded-full text-xs sm:text-sm font-medium flex items-center justify-center gap-2 hover:bg-secondary/90 transition-colors shadow-sm">
            <span class="material-symbols-outlined text-[16px] sm:text-[18px]">add</span> Tambah Kategori
        </button>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-outline-variant/30 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm min-w-[500px]">
                <thead>
                    <tr class="border-b border-outline-variant/20 bg-surface-container-low">
                        <th class="text-left px-3 sm:px-6 py-3 sm:py-4 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">#</th>
                        <th class="text-left px-3 sm:px-6 py-3 sm:py-4 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Nama Kategori</th>
                        <th class="text-left px-3 sm:px-6 py-3 sm:py-4 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Slug</th>
                        <th class="text-center px-3 sm:px-6 py-3 sm:py-4 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Jumlah Produk</th>
                        <th class="text-center px-3 sm:px-6 py-3 sm:py-4 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/10">
                    @forelse($categories as $cat)
                    <tr class="hover:bg-surface-container-low/50 transition-colors">
                        <td class="px-3 sm:px-6 py-3 sm:py-4 text-on-surface-variant font-mono text-xs">{{ $loop->iteration }}</td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4">
                            <p class="font-medium text-on-surface text-xs sm:text-sm">{{ $cat->name }}</p>
                        </td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4 text-on-surface-variant font-mono text-[10px] sm:text-xs">{{ $cat->slug }}</td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4 text-center">
                            <span class="px-2 sm:px-2.5 py-1 rounded-full text-[10px] sm:text-xs font-medium bg-secondary/10 text-secondary">{{ $cat->products_count }}</span>
                        </td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4 text-center">
                            <div class="flex items-center justify-center gap-1 sm:gap-2">
                                <button @click="openModal({{ $cat->id }}, '{{ $cat->name }}')" class="p-1.5 sm:p-2 text-on-surface-variant hover:text-secondary hover:bg-surface-container-low rounded-lg transition-colors" title="Edit">
                                    <span class="material-symbols-outlined text-base sm:text-lg">edit</span>
                                </button>
                                <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" onsubmit="return confirm('Hapus kategori {{ $cat->name }}?')" class="inline">
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
                        <td colspan="5" class="px-3 sm:px-6 py-8 sm:py-12 text-center text-on-surface-variant text-xs sm:text-sm">Belum ada kategori. Tambahkan kategori pertama Anda.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Create / Edit --}}
    <div x-show="show" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-on-surface/60 backdrop-blur-sm" @click.self="show = false">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md border border-outline-variant/20" @click.stop>
            <div class="p-5 sm:p-6 border-b border-outline-variant/20">
                <h3 class="text-lg font-bold text-on-surface" x-text="editing ? 'Edit Kategori' : 'Tambah Kategori'"></h3>
            </div>
            <form :action="editing ? '{{ route('admin.categories.update', '__ID__') }}'.replace('__ID__', editing) : '{{ route('admin.categories.store') }}'" method="POST" class="p-5 sm:p-6 space-y-4">
                @csrf
                <template x-if="editing"><input type="hidden" name="_method" value="PUT"></template>
                <div>
                    <label class="block text-xs font-semibold text-on-surface-variant mb-1.5">Nama Kategori</label>
                    <input type="text" name="name" x-model="name" required maxlength="100"
                           class="w-full border border-outline-variant rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-secondary/20 focus:border-secondary transition-all"
                           placeholder="Contoh: Makanan">
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
function categoryManager() {
    return {
        show: false,
        editing: null,
        name: '',
        openModal(id = null, name = '') {
            this.editing = id;
            this.name = name;
            this.show = true;
        }
    }
}
</script>
@endpush
