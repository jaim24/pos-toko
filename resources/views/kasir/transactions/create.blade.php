@extends('layouts.app')
@section('title', 'Transaksi Baru')

@push('head')
<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    @keyframes scanGlow { 0%,100% { border-color: transparent; box-shadow: 0 0 0 0 rgba(0,108,73,0.25); } 50% { border-color: rgba(0,108,73,0.5); box-shadow: 0 0 0 6px rgba(0,108,73,0); } }
    .scan-active { animation: scanGlow 1.6s infinite; }
</style>
@endpush

@section('content')

<div class="flex flex-col lg:flex-row flex-1 min-h-0" x-data="posCart()">
    {{-- ===== LEFT: PRODUCT CATALOG ===== --}}
    <div class="flex-1 flex flex-col min-w-0 bg-[#F4F6F9] overflow-hidden">

        {{-- Top bar: search + live total counter --}}
        <div class="shrink-0 px-4 sm:px-6 py-3 flex items-center gap-3 bg-white border-b border-gray-100">
            <div class="relative flex-1 max-w-[520px]">
                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xl">barcode_scanner</span>
                <input type="text" x-model="searchQuery" x-ref="searchInput"
                       placeholder="Scan barcode atau cari produk..."
                       class="w-full bg-gray-50 border-2 border-transparent rounded-xl pl-11 pr-4 py-2.5 text-sm font-medium text-gray-800 placeholder:text-gray-400 focus:bg-white focus:border-green-600/40 focus:ring-0 transition-all scan-active"
                       autofocus>
            </div>
            <div class="hidden sm:flex items-center gap-2 shrink-0 ml-auto" x-show="items.length > 0">
                <span class="text-xs font-bold text-white bg-green-700 px-3 py-1.5 rounded-full" x-text="items.length + ' item'"></span>
                <span class="text-sm font-bold text-gray-900 font-mono" x-text="formatRp(total)"></span>
            </div>
        </div>

        {{-- Category tabs --}}
        <div class="shrink-0 px-4 sm:px-6 py-2.5 flex gap-1.5 overflow-x-auto no-scrollbar bg-white border-b border-gray-100" @wheel.prevent="$el.scrollLeft += $event.deltaY">
            <button class="whitespace-nowrap px-4 py-1.5 rounded-lg text-xs font-semibold transition-all shrink-0"
                    :class="filterCategory === '' ? 'bg-green-700 text-white shadow-sm' : 'bg-gray-50 text-gray-500 hover:bg-gray-100 hover:text-gray-700'"
                    @click="filterCategory = ''">
                Semua
            </button>
            @foreach($categories as $cat)
            <button class="whitespace-nowrap px-4 py-1.5 rounded-lg text-xs font-semibold transition-all shrink-0"
                    :class="filterCategory === '{{ $cat->id }}' ? 'bg-green-700 text-white shadow-sm' : 'bg-gray-50 text-gray-500 hover:bg-gray-100 hover:text-gray-700'"
                    @click="filterCategory = filterCategory === '{{ $cat->id }}' ? '' : '{{ $cat->id }}'">
                {{ $cat->name }}
            </button>
            @endforeach
        </div>

        {{-- Product grid --}}
        <div class="flex-1 overflow-y-auto px-3 sm:px-5 py-3">
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-2.5 sm:gap-3">
                @foreach($products as $p)
                <div class="bg-white rounded-2xl border border-gray-100 hover:border-green-200 hover:shadow-[0_4px_20px_rgba(0,0,0,0.06)] active:scale-[0.97] transition-all duration-150 cursor-pointer group overflow-hidden shrink-0"
                     data-name="{{ strtolower($p->name) }}"
                     data-id="{{ $p->id }}"
                     data-barcode="{{ $p->barcode }}"
                     data-category="{{ $p->category_id }}"
                     x-show="(filterCategory === '' || filterCategory === $el.dataset.category) && (!searchQuery || $el.dataset.name.includes(searchQuery.toLowerCase()) || $el.dataset.id === searchQuery || $el.dataset.barcode === searchQuery)"
                     @click="addItem({{ $p->id }}, {{ json_encode($p->name) }}, {{ $p->price }}, {{ $p->stock }})">
                    {{-- Image area --}}
                    <div class="aspect-[4/3] bg-gray-50 relative flex items-center justify-center overflow-hidden">
                        <span class="material-symbols-outlined text-3xl sm:text-4xl text-gray-200 group-hover:text-gray-300 transition-colors">inventory_2</span>
                        <span class="absolute top-2 right-2 bg-white/90 text-[9px] font-bold text-gray-500 px-2 py-0.5 rounded-full shadow-sm border border-gray-100">{{ $p->category->name }}</span>
                        <span class="absolute bottom-2 left-2 bg-white/90 text-[9px] font-mono text-gray-400 px-1.5 py-0.5 rounded-full border border-gray-100">
                            {{ $p->stock <= 5 ? '⚠ '.$p->stock : $p->stock }}
                        </span>
                        {{-- Quick add button on hover --}}
                        <div class="absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <div class="w-11 h-11 bg-green-600 text-white rounded-full flex items-center justify-center shadow-lg">
                                <span class="material-symbols-outlined text-2xl">add</span>
                            </div>
                        </div>
                    </div>
                    {{-- Info --}}
                    <div class="p-2.5 sm:p-3">
                        <h3 class="text-xs sm:text-[13px] font-semibold text-gray-800 leading-tight line-clamp-2 mb-1.5">{{ $p->name }}</h3>
                        <p class="text-xs sm:text-sm font-bold text-green-700 font-mono tracking-tight">Rp {{ number_format($p->price, 0, ',', '.') }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ===== RIGHT: CART PANEL (Desktop) ===== --}}
    <div class="w-[380px] shrink-0 hidden lg:flex flex-col bg-white border-l border-gray-200 shadow-[-4px_0_24px_rgba(0,0,0,0.04)] z-20 h-full">
        {{-- Cart header --}}
        <div class="shrink-0 px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Pesanan</h2>
                <p class="text-xs text-gray-500 font-medium">{{ auth()->user()->name }} <span class="inline-block w-1.5 h-1.5 rounded-full bg-green-500 ml-1.5"></span></p>
            </div>
            <div class="flex items-center gap-1.5">
                <button @click="holdOrder" :disabled="items.length === 0"
                        class="w-8 h-8 rounded-lg bg-gray-50 hover:bg-amber-50 hover:text-amber-600 disabled:opacity-30 flex items-center justify-center transition-colors text-gray-400"
                        title="Tahan pesanan">
                    <span class="material-symbols-outlined text-[18px]">pause_circle</span>
                </button>
                <button @click="recallOrder" x-show="hasHeldOrder" x-cloak
                        class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 flex items-center justify-center transition-colors"
                        title="Panggil pesanan">
                    <span class="material-symbols-outlined text-[18px]">play_circle</span>
                </button>
                <button @click="clearCart()"
                        class="w-8 h-8 rounded-lg bg-gray-50 hover:bg-red-50 hover:text-red-500 flex items-center justify-center transition-colors text-gray-400"
                        title="Kosongkan">
                    <span class="material-symbols-outlined text-[18px]">delete</span>
                </button>
            </div>
        </div>

        {{-- Cart items --}}
        <div class="flex-1 overflow-y-auto p-3 space-y-2 bg-gray-50/50">
            <template x-for="(item, idx) in items" :key="item.id">
                <div class="flex items-center gap-3 bg-white p-3 rounded-xl border border-gray-100 shadow-sm cart-item-enter">
                    <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-gray-300 text-xl">inventory_2</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-[13px] font-semibold text-gray-800 truncate" x-text="item.name"></h4>
                        <p class="text-[11px] text-gray-500 font-mono mt-0.5">
                            <span x-text="formatRp(item.price)"></span> × <span x-text="item.qty"></span>
                        </p>
                    </div>
                    <div class="flex flex-col items-end gap-1 shrink-0">
                        <button @click="removeItem(idx)" class="text-gray-300 hover:text-red-500 transition-colors">
                            <span class="material-symbols-outlined text-[14px]">close</span>
                        </button>
                        <div class="flex items-center border border-gray-200 rounded-lg h-7 overflow-hidden">
                            <button @click="decreaseQty(idx)" class="w-6 h-full flex items-center justify-center text-xs text-gray-500 hover:bg-gray-100 transition-colors">−</button>
                            <span class="text-xs font-mono font-bold w-6 text-center text-gray-800" x-text="item.qty"></span>
                            <button @click="increaseQty(idx)" class="w-6 h-full flex items-center justify-center text-xs text-gray-500 hover:bg-gray-100 transition-colors">+</button>
                        </div>
                    </div>
                </div>
            </template>
            <div x-show="items.length === 0" class="flex flex-col items-center justify-center py-16 text-center">
                <span class="material-symbols-outlined text-5xl text-gray-200 mb-4">shopping_bag</span>
                <p class="text-gray-400 font-medium text-sm">Keranjang kosong</p>
                <p class="text-gray-300 text-xs mt-1">Tap produk di katalog</p>
            </div>
        </div>

        {{-- Summary + Payment --}}
        <div class="shrink-0 bg-white border-t border-gray-200 p-4 space-y-3">
            {{-- Totals --}}
            <div class="space-y-1">
                <div class="flex justify-between text-[13px]">
                    <span class="text-gray-500">Subtotal</span>
                    <span class="font-mono font-medium text-gray-700" x-text="formatRp(subtotal)"></span>
                </div>
                <div class="flex justify-between text-[13px] text-red-500" x-show="discount > 0">
                    <span>Diskon</span>
                    <span class="font-mono font-medium" x-text="'- ' + formatRp(discount)"></span>
                </div>
                <div class="flex justify-between items-center pt-2 border-t border-gray-200">
                    <span class="text-base font-bold text-gray-900">Total</span>
                    <span class="font-mono font-bold text-xl text-green-700" x-text="formatRp(total)"></span>
                </div>
            </div>

            <form method="POST" action="{{ route('kasir.transactions.store') }}" @submit.prevent="checkout">
                @csrf
                <div class="space-y-2.5">
                    {{-- Discount + Notes in one row --}}
                    <div class="flex gap-2">
                        <div class="flex-1">
                            <input type="text" inputmode="numeric" x-ref="discountInput" @input="onDiscountInput($event)"
                                   class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-right font-mono text-xs focus:ring-1 focus:ring-green-500 focus:border-green-500 transition-all"
                                   placeholder="Diskon (Rp)">
                        </div>
                        <div class="flex-1">
                            <input type="text" x-model="notes"
                                   class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-xs focus:ring-1 focus:ring-green-500 focus:border-green-500 transition-all"
                                   placeholder="Catatan...">
                        </div>
                    </div>

                    {{-- Paid amount (cash) --}}
                    <div x-show="paymentMethod === 'cash'">
                        <input type="text" inputmode="numeric" x-ref="paidInput" @input="onPaidInput($event)"
                               class="w-full bg-white border-2 border-gray-200 focus:border-green-500 rounded-xl px-4 py-3 text-right font-mono text-lg font-bold focus:ring-0 transition-all"
                               placeholder="Rp 0">
                    </div>

                    {{-- Quick cash buttons --}}
                    <div class="flex gap-1.5" x-show="paymentMethod === 'cash'">
                        <button type="button" @click="setQuickCash('pas')"
                                class="flex-1 px-2 py-1.5 rounded-lg bg-gray-100 text-[11px] font-semibold text-gray-600 hover:bg-green-50 hover:text-green-700 transition-colors">Uang Pas</button>
                        <button type="button" @click="setQuickCash(20000)"
                                class="flex-1 px-2 py-1.5 rounded-lg bg-gray-100 text-[11px] font-semibold text-gray-600 hover:bg-green-50 hover:text-green-700 transition-colors">20K</button>
                        <button type="button" @click="setQuickCash(50000)"
                                class="flex-1 px-2 py-1.5 rounded-lg bg-gray-100 text-[11px] font-semibold text-gray-600 hover:bg-green-50 hover:text-green-700 transition-colors">50K</button>
                        <button type="button" @click="setQuickCash(100000)"
                                class="flex-1 px-2 py-1.5 rounded-lg bg-gray-100 text-[11px] font-semibold text-gray-600 hover:bg-green-50 hover:text-green-700 transition-colors">100K</button>
                    </div>

                    {{-- Change --}}
                    <div class="flex justify-between items-center bg-green-50 px-4 py-2.5 rounded-xl border border-green-200"
                         x-show="paymentMethod === 'cash' && paidAmount > 0">
                        <span class="text-sm font-bold text-green-700">Kembalian</span>
                        <span class="font-mono font-bold text-lg text-green-700" x-text="formatRp(change)"></span>
                    </div>

                    {{-- QRIS panel --}}
                    <div x-show="paymentMethod === 'qris'" class="bg-gray-50 rounded-xl p-3 border border-gray-200 space-y-2.5">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-green-700">qr_code_2</span>
                                <span class="text-xs font-bold text-gray-700 uppercase">QRIS</span>
                            </div>
                            <span class="font-mono font-bold text-lg text-green-700" x-text="formatRp(total)"></span>
                        </div>
                        <div class="flex justify-center">
                            <div class="w-24 h-24 bg-white rounded-xl border-2 border-gray-200 p-2 flex items-center justify-center">
                                <svg viewBox="0 0 100 100" class="w-full h-full">
                                    <rect x="10" y="10" width="30" height="30" rx="4" fill="#111"/><rect x="14" y="14" width="22" height="22" rx="2" fill="white"/><rect x="22" y="22" width="6" height="6" fill="#111"/>
                                    <rect x="60" y="10" width="30" height="30" rx="4" fill="#111"/><rect x="64" y="14" width="22" height="22" rx="2" fill="white"/><rect x="73" y="22" width="6" height="6" fill="#111"/>
                                    <rect x="10" y="60" width="30" height="30" rx="4" fill="#111"/><rect x="14" y="64" width="22" height="22" rx="2" fill="white"/><rect x="22" y="73" width="6" height="6" fill="#111"/>
                                    <rect x="15" y="45" width="8" height="8" rx="2" fill="#111" opacity="0.7"/><rect x="28" y="45" width="8" height="8" rx="2" fill="#111" opacity="0.7"/>
                                    <rect x="55" y="45" width="8" height="8" rx="2" fill="#111" opacity="0.7"/><rect x="68" y="45" width="8" height="8" rx="2" fill="#111" opacity="0.7"/>
                                    <rect x="42" y="60" width="8" height="8" rx="2" fill="#111" opacity="0.7"/><rect x="55" y="68" width="8" height="8" rx="2" fill="#111" opacity="0.7"/>
                                    <rect x="15" y="55" width="8" height="8" rx="2" fill="#111" opacity="0.7"/><rect x="42" y="15" width="8" height="8" rx="2" fill="#111" opacity="0.7"/>
                                    <rect x="55" y="28" width="8" height="8" rx="2" fill="#111" opacity="0.7"/><rect x="30" y="68" width="8" height="8" rx="2" fill="#111" opacity="0.7"/>
                                    <rect x="78" y="55" width="8" height="8" rx="2" fill="#111" opacity="0.7"/><rect x="42" y="78" width="8" height="8" rx="2" fill="#111" opacity="0.7"/>
                                    <rect x="78" y="78" width="8" height="8" rx="2" fill="#111" opacity="0.7"/>
                                </svg>
                            </div>
                        </div>
                        <p class="text-center text-[10px] text-gray-400">Scan dengan aplikasi bank / e-money</p>
                    </div>

                    {{-- Payment method selector --}}
                    <div class="flex bg-gray-100 rounded-xl p-1">
                        <button type="button" @click="paymentMethod = 'cash'; onPaymentChange()"
                                :class="paymentMethod === 'cash' ? 'bg-white shadow-sm text-gray-900' : 'text-gray-500'"
                                class="flex-1 py-2 rounded-lg text-xs font-bold transition-all flex items-center justify-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px]">payments</span> Tunai
                        </button>
                        <button type="button" @click="paymentMethod = 'qris'; onPaymentChange()"
                                :class="paymentMethod === 'qris' ? 'bg-white shadow-sm text-gray-900' : 'text-gray-500'"
                                class="flex-1 py-2 rounded-lg text-xs font-bold transition-all flex items-center justify-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px]">qr_code_2</span> QRIS
                        </button>
                    </div>

                    {{-- Pay button --}}
                    <button type="submit"
                            class="w-full bg-green-700 hover:bg-green-800 text-white py-3.5 rounded-xl font-bold text-base shadow-lg shadow-green-700/20 flex items-center justify-center gap-2 transition-all active:scale-[0.98] disabled:opacity-50">
                        Proses Bayar
                        <span class="material-symbols-outlined text-xl">arrow_forward</span>
                    </button>
                </div>

                {{-- Hidden fields --}}
                <input type="hidden" name="items_json" :value="JSON.stringify(items)">
                <input type="hidden" name="paid_amount" :value="paymentMethod === 'qris' ? total : paidAmount">
                <input type="hidden" name="discount" :value="discount">
                <input type="hidden" name="notes" :value="notes">
            </form>
        </div>
    </div>

    {{-- ===== MOBILE: Bottom Cart Bar + Sheet ===== --}}
    <div class="lg:hidden fixed bottom-0 left-0 right-0 z-30 bg-white border-t border-gray-200 shadow-[0_-4px_20px_rgba(0,0,0,0.06)]" x-show="items.length > 0 || showMobileCart">
        {{-- Toggle bar --}}
        <div class="flex items-center justify-between px-4 py-3" @click="showMobileCart = !showMobileCart">
            <div class="flex items-center gap-2">
                <span class="bg-green-700 text-white text-xs font-bold px-2.5 py-1 rounded-full" x-text="items.length + ' item'"></span>
                <span class="font-mono font-bold text-lg text-green-700" x-text="formatRp(total)"></span>
            </div>
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-gray-400 transition-transform" :class="showMobileCart ? 'rotate-180' : ''">expand_less</span>
            </div>
        </div>
        {{-- Expandable cart panel --}}
        <div x-show="showMobileCart" x-collapse class="border-t border-gray-100 max-h-[55vh] overflow-y-auto">
            <div class="p-3 space-y-2 bg-gray-50/50">
                <template x-for="(item, idx) in items" :key="'mob-'+item.id">
                    <div class="flex items-center gap-3 bg-white p-3 rounded-xl border border-gray-100 shadow-sm">
                        <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-gray-300 text-xl">inventory_2</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-[13px] font-semibold text-gray-800 truncate" x-text="item.name"></h4>
                            <p class="text-[11px] text-gray-500 font-mono mt-0.5" x-text="formatRp(item.price) + ' × ' + item.qty"></p>
                        </div>
                        <div class="flex items-center gap-1 shrink-0">
                            <button @click="decreaseQty(idx)" class="w-7 h-7 rounded-full bg-gray-100 flex items-center justify-center text-sm text-gray-500 hover:bg-gray-200">-</button>
                            <span class="text-sm font-bold w-6 text-center" x-text="item.qty"></span>
                            <button @click="increaseQty(idx)" class="w-7 h-7 rounded-full bg-gray-100 flex items-center justify-center text-sm text-gray-500 hover:bg-gray-200">+</button>
                        </div>
                    </div>
                </template>
                <div x-show="items.length === 0" class="text-center py-10 text-gray-400 text-sm">Keranjang kosong</div>
            </div>
            {{-- Mobile payment --}}
            <div class="p-4 bg-white border-t border-gray-200 space-y-3" x-show="items.length > 0">
                <div class="flex gap-2">
                    <input type="text" x-ref="discountInput" @input="onDiscountInput($event)" inputmode="numeric" placeholder="Diskon" class="flex-1 bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-right font-mono text-xs focus:ring-1 focus:ring-green-500 focus:border-green-500">
                    <input type="text" x-model="notes" placeholder="Catatan" class="flex-1 bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-xs focus:ring-1 focus:ring-green-500 focus:border-green-500">
                </div>
                <div x-show="paymentMethod === 'cash'">
                    <input type="text" x-ref="paidInput" @input="onPaidInput($event)" inputmode="numeric" class="w-full bg-white border-2 border-gray-200 focus:border-green-500 rounded-xl px-4 py-3 text-right font-mono text-lg font-bold" placeholder="Rp 0">
                </div>
                <div class="flex gap-1.5" x-show="paymentMethod === 'cash'">
                    <button type="button" @click="setQuickCash('pas')" class="flex-1 py-1.5 rounded-lg bg-gray-100 text-[10px] font-bold">Uang Pas</button>
                    <button type="button" @click="setQuickCash(20000)" class="py-1.5 px-3 rounded-lg bg-gray-100 text-[10px] font-bold">20K</button>
                    <button type="button" @click="setQuickCash(50000)" class="py-1.5 px-3 rounded-lg bg-gray-100 text-[10px] font-bold">50K</button>
                    <button type="button" @click="setQuickCash(100000)" class="py-1.5 px-3 rounded-lg bg-gray-100 text-[10px] font-bold">100K</button>
                </div>
                <div class="flex bg-gray-100 rounded-xl p-1">
                    <button type="button" @click="paymentMethod = 'cash'; onPaymentChange()" :class="paymentMethod === 'cash' ? 'bg-white shadow-sm text-gray-900' : 'text-gray-500'" class="flex-1 py-2 rounded-lg text-xs font-bold">Tunai</button>
                    <button type="button" @click="paymentMethod = 'qris'; onPaymentChange()" :class="paymentMethod === 'qris' ? 'bg-white shadow-sm text-gray-900' : 'text-gray-500'" class="flex-1 py-2 rounded-lg text-xs font-bold">QRIS</button>
                </div>
                <div class="flex justify-between items-center bg-green-50 px-4 py-2.5 rounded-xl border border-green-200" x-show="paymentMethod === 'cash' && paidAmount > 0">
                    <span class="text-sm font-bold text-green-700">Kembali</span>
                    <span class="font-mono font-bold text-lg text-green-700" x-text="formatRp(change)"></span>
                </div>
                <button @click="mobileCheckout()" class="w-full bg-green-700 text-white py-3.5 rounded-xl font-bold text-base">Proses Bayar</button>
            </div>
        </div>
    </div>

    {{-- Mobile toggle cart button (when cart empty/invisible) --}}
    <button @click="showMobileCart = !showMobileCart" class="lg:hidden fixed bottom-5 right-5 z-40 w-14 h-14 bg-green-700 text-white rounded-full shadow-xl flex items-center justify-center" x-show="items.length > 0 && !showMobileCart">
        <span class="material-symbols-outlined text-2xl">shopping_cart</span>
        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center" x-text="items.length"></span>
    </button>
    <div x-show="toast.show" x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-4"
         class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[80] px-5 py-3 rounded-2xl shadow-2xl border flex items-center gap-3 text-sm font-semibold backdrop-blur"
         :class="toast.type === 'success' ? 'bg-white text-gray-800 border-green-300' : 'bg-white text-gray-800 border-red-300'">
        <span class="material-symbols-outlined text-xl" x-text="toast.type === 'success' ? 'check_circle' : 'error'"
              :class="toast.type === 'success' ? 'text-green-600' : 'text-red-500'"></span>
        <span x-text="toast.message"></span>
    </div>

    {{-- Receipt Popup Modal --}}
    <div x-show="showReceipt" x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-on-surface/60 backdrop-blur-sm"
         @click.self="showReceipt = false">
        <div class="bg-white rounded-2xl sm:rounded-3xl shadow-2xl w-full max-w-md max-h-[90vh] overflow-y-auto border border-outline-variant/20"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-90 translate-y-8"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            {{-- Header --}}
            <div class="bg-primary p-5 sm:p-6 rounded-t-2xl sm:rounded-t-3xl text-center text-white relative">
                <button @click="showReceipt = false" class="absolute top-3 right-3 sm:top-4 sm:right-4 w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition-colors">
                    <span class="material-symbols-outlined text-lg">close</span>
                </button>
                <span class="material-symbols-outlined text-3xl sm:text-4xl text-secondary mb-2" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                <h2 class="text-lg sm:text-xl font-bold tracking-tight mt-1">Pembayaran Berhasil</h2>
                <p class="text-on-primary-container text-xs sm:text-sm mt-1" x-text="receipt.invoice"></p>
            </div>

            {{-- Info --}}
            <div class="p-4 sm:p-6 border-b border-outline-variant/20">
                <div class="grid grid-cols-2 gap-3 sm:gap-4 text-xs sm:text-sm">
                    <div>
                        <p class="text-[10px] text-on-surface-variant uppercase tracking-wider font-semibold mb-0.5">Tanggal</p>
                        <p class="font-medium text-on-surface text-xs sm:text-sm" x-text="receipt.date"></p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] text-on-surface-variant uppercase tracking-wider font-semibold mb-0.5">Kasir</p>
                        <p class="font-medium text-on-surface text-xs sm:text-sm" x-text="receipt.kasir"></p>
                    </div>
                    <div>
                        <p class="text-[10px] text-on-surface-variant uppercase tracking-wider font-semibold mb-0.5">Metode</p>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase"
                              :class="receipt.method === 'cash' ? 'bg-surface-container text-on-surface-variant' : 'bg-primary-container/20 text-on-primary-container'"
                              x-text="receipt.methodDisplay"></span>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] text-on-surface-variant uppercase tracking-wider font-semibold mb-0.5">Item</p>
                        <p class="font-medium text-on-surface text-xs sm:text-sm" x-text="receipt.itemCount + ' item'"></p>
                    </div>
                    <div class="col-span-2 mt-1" x-show="receipt.notes">
                        <p class="text-[10px] text-on-surface-variant uppercase tracking-wider font-semibold mb-0.5">Catatan</p>
                        <p class="font-medium text-on-surface text-xs sm:text-sm italic" x-text="receipt.notes"></p>
                    </div>
                </div>
            </div>

            {{-- Items --}}
            <div class="p-4 sm:p-6 border-b border-outline-variant/20">
                <p class="text-[10px] text-on-surface-variant uppercase tracking-wider font-semibold mb-2 sm:mb-3">Rincian</p>
                <div class="space-y-1.5 sm:space-y-2 max-h-[200px] overflow-y-auto">
                    <template x-for="(item, idx) in receipt.items" :key="idx">
                        <div class="flex items-center justify-between text-xs sm:text-sm">
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-on-surface truncate" x-text="item.name"></p>
                                <p class="text-[10px] text-on-surface-variant font-mono" x-text="item.qty + '× Rp ' + item.priceFmt"></p>
                            </div>
                            <p class="font-mono font-medium text-on-surface ml-3 shrink-0" x-text="'Rp ' + item.subtotalFmt"></p>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Totals --}}
            <div class="p-4 sm:p-6 bg-surface-container-low space-y-2 sm:space-y-3">
                <div class="flex justify-between text-sm" x-show="receipt.discountFmt !== '0' && receipt.discountFmt !== ''">
                    <span class="text-on-surface-variant">Diskon</span>
                    <span class="font-mono font-medium text-error" x-text="'- Rp ' + receipt.discountFmt"></span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-on-surface-variant font-bold">Total</span>
                    <span class="font-mono font-bold text-on-surface text-base sm:text-lg" x-text="'Rp ' + receipt.totalFmt"></span>
                </div>
                <div class="flex justify-between text-sm" x-show="receipt.method === 'cash'">
                    <span class="text-on-surface-variant">Bayar</span>
                    <span class="font-mono font-medium text-on-surface" x-text="'Rp ' + receipt.paidFmt"></span>
                </div>
                <div class="flex justify-between text-sm pt-2 sm:pt-3 border-t border-outline-variant/30" x-show="receipt.method === 'cash'">
                    <span class="text-secondary font-semibold">Kembalian</span>
                    <span class="font-mono font-bold text-secondary text-base sm:text-lg" x-text="'Rp ' + receipt.changeFmt"></span>
                </div>
            </div>

            {{-- Footer --}}
            <div class="p-4 sm:p-5 text-center border-t border-outline-variant/20">
                <p class="text-[10px] text-on-surface-variant mb-3">Terima kasih telah berbelanja</p>
                <div class="flex gap-2 sm:gap-3">
                    <button @click="newTransaction()"
                            class="flex-1 px-4 py-2.5 bg-secondary text-white rounded-full text-xs sm:text-sm font-semibold flex items-center justify-center gap-1.5 hover:bg-[#005236] transition-colors shadow-sm">
                        <span class="material-symbols-outlined text-[16px] sm:text-[18px]">add</span> Baru
                    </button>
                    <button @click="printReceipt()"
                            class="flex-1 px-4 py-2.5 bg-white border border-outline-variant text-on-surface rounded-full text-xs sm:text-sm font-semibold hover:bg-surface-container-low transition-colors shadow-sm flex items-center justify-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px] sm:text-[18px]">print</span> Cetak
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@push('scripts')
<script>
function posCart() {
    return {
        items: [],
        paidAmount: 0,
        paymentMethod: 'cash',
        searchQuery: '',
        filterCategory: '',
        discount: 0,
        notes: '',
        hasHeldOrder: false,
        showReceipt: false,
        showMobileCart: false,
        toast: {
            show: false,
            type: 'success',
            message: '',
            timer: null
        },
        receipt: {
            invoice: '',
            date: '',
            kasir: @js(auth()->user()->name ?? 'Guest'),
            method: 'cash',
            methodDisplay: 'Tunai',
            itemCount: 0,
            items: [],
            totalFmt: '',
            paidFmt: '',
            changeFmt: '',
            discountFmt: '',
            notes: ''
        },

        init() {
            if (localStorage.getItem('pos_draft_cart')) {
                this.hasHeldOrder = true;
            }

            let barcodeBuffer = '';
            let barcodeTimeout = null;
            window.addEventListener('keypress', (e) => {
                if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;
                
                if (e.key === 'Enter') {
                    if (barcodeBuffer.length > 0) {
                        this.processBarcode(barcodeBuffer);
                        barcodeBuffer = '';
                    }
                } else {
                    barcodeBuffer += e.key;
                    clearTimeout(barcodeTimeout);
                    barcodeTimeout = setTimeout(() => { barcodeBuffer = ''; }, 100);
                }
            });
        },

        processBarcode(code) {
            const p = {!! json_encode($products->map(fn($p) => ['id' => $p->id, 'name' => $p->name, 'price' => $p->price, 'stock' => $p->stock, 'barcode' => $p->barcode])->values()->all()) !!};
            const found = p.find(x =>
                String(x.id) === code
                || x.barcode === code
                || x.name.toLowerCase().includes(code.toLowerCase())
            );
            if (found) {
                this.addItem(found.id, found.name, found.price, found.stock);
                this.showToast('success', `Added ${found.name}`, 1500);
            } else {
                this.showToast('error', 'Barcode tidak ditemukan!', 2000);
            }
        },

        beep() {
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.type = 'sine';
                osc.frequency.value = 800;
                gain.gain.setValueAtTime(0.05, ctx.currentTime);
                osc.start();
                osc.stop(ctx.currentTime + 0.1);
            } catch(e) {}
        },

        get subtotal() {
            return this.items.reduce((sum, i) => sum + (i.price * i.qty), 0);
        },

        get total() {
            return Math.max(0, this.subtotal - this.discount);
        },

        get change() {
            return Math.max(0, this.paidAmount - this.total);
        },

        onDiscountInput(e) {
            let raw = e.target.value;
            const clean = raw.replace(/[^\d]/g, '');
            this.discount = clean === '' ? 0 : parseInt(clean, 10);
            if (this.discount > 0) {
                e.target.value = new Intl.NumberFormat('id-ID').format(this.discount);
            } else {
                e.target.value = '';
            }
        },

        setQuickCash(amount) {
            if (amount === 'pas') {
                this.paidAmount = this.total;
            } else {
                this.paidAmount = amount;
            }
            if (this.$refs.paidInput) {
                this.$refs.paidInput.value = 'Rp ' + new Intl.NumberFormat('id-ID').format(this.paidAmount);
            }
        },

        holdOrder() {
            if (this.items.length === 0) return;
            const draft = { items: this.items, discount: this.discount, notes: this.notes };
            localStorage.setItem('pos_draft_cart', JSON.stringify(draft));
            this.hasHeldOrder = true;
            this.clearCart(true);
            this.showToast('success', 'Pesanan disimpan sementara!');
        },

        recallOrder() {
            const draftStr = localStorage.getItem('pos_draft_cart');
            if (draftStr) {
                const draft = JSON.parse(draftStr);
                this.items = draft.items || [];
                this.discount = draft.discount || 0;
                this.notes = draft.notes || '';
                
                if (this.$refs.discountInput) {
                    this.$refs.discountInput.value = this.discount > 0 ? new Intl.NumberFormat('id-ID').format(this.discount) : '';
                }
                localStorage.removeItem('pos_draft_cart');
                this.hasHeldOrder = false;
                this.showToast('success', 'Pesanan dipanggil kembali!');
            }
        },

        onPaidInput(e) {
            let raw = e.target.value;
            // Remove Rp prefix and formatting if present
            raw = raw.replace(/^Rp\s*/, '');
            const clean = raw.replace(/[^\d]/g, '');
            this.paidAmount = clean === '' ? 0 : parseInt(clean, 10);
            
            // Format instantly
            if (this.paidAmount > 0) {
                e.target.value = 'Rp ' + new Intl.NumberFormat('id-ID').format(this.paidAmount);
            } else {
                e.target.value = '';
            }
        },

        onPaymentChange() {
            if (this.paymentMethod === 'qris') {
                this.paidAmount = this.total;
            } else {
                this.paidAmount = 0;
                if (this.$refs.paidInput) {
                    this.$refs.paidInput.value = '';
                }
            }
        },

        addItem(id, name, price, stock) {
            this.beep();
            const existing = this.items.find(i => i.id === id);
            if (existing) {
                if (existing.qty < stock) existing.qty++;
                else this.showToast('error', 'Stok tidak cukup!', 2000);
            } else {
                this.items.push({ id, name, price, qty: 1, maxStock: stock });
            }
        },

        removeItem(idx) {
            this.items.splice(idx, 1);
        },

        increaseQty(idx) {
            if (this.items[idx].qty < this.items[idx].maxStock) this.items[idx].qty++;
        },

        decreaseQty(idx) {
            if (this.items[idx].qty > 1) this.items[idx].qty--;
            else this.items.splice(idx, 1);
        },

        clearCart(force = false) {
            if (!force && this.items.length > 0) {
                if (!confirm('Hapus semua barang dari keranjang?')) return;
            }
            this.items = [];
            this.discount = 0;
            this.notes = '';
            if (this.$refs.discountInput) this.$refs.discountInput.value = '';
        },

        formatRp(n) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(n);
        },

        showToast(type, message, duration = 3000) {
            if (this.toast.timer) clearTimeout(this.toast.timer);
            this.toast.show = true;
            this.toast.type = type;
            this.toast.message = message;
            this.toast.timer = setTimeout(() => {
                this.toast.show = false;
            }, duration);
        },

        checkout(e) {
            if (this.items.length === 0) {
                this.showToast('error', 'Keranjang kosong!');
                return;
            }
            if (this.paymentMethod === 'cash' && this.paidAmount < this.total) {
                this.showToast('error', 'Uang bayar kurang dari total!');
                return;
            }

            const btn = e.target.querySelector('button[type="submit"]');
            const orig = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="material-symbols-outlined animate-spin text-[18px]">progress_activity</span> Processing...';

            const fd = new FormData();
            fd.append('_token', '{{ csrf_token() }}');
            fd.append('items_json', JSON.stringify(this.items));
            fd.append('paid_amount', this.paymentMethod === 'qris' ? this.total : this.paidAmount);
            fd.append('discount', this.discount);
            fd.append('notes', this.notes);
            fd.append('payment_method', this.paymentMethod);

            fetch('{{ route('kasir.transactions.store') }}', {
                method: 'POST',
                headers: { 'Accept': 'application/json' },
                body: fd
            })
            .then(r => r.json())
            .then(data => {
                btn.innerHTML = orig;
                btn.disabled = false;
                if (data.success) {
                    const tx = data.transaction;
                    this.receipt.invoice = tx.invoice_number;
                    this.receipt.date = tx.date;
                    this.receipt.kasir = tx.kasir;
                    this.receipt.method = tx.payment_method;
                    this.receipt.methodDisplay = tx.payment_method === 'cash' ? 'Tunai' : 'QRIS';
                    this.receipt.itemCount = tx.items ? tx.items.length : 0;
                    this.receipt.items = (tx.items || []).map(i => ({
                        name: i.product_name,
                        qty: i.quantity,
                        priceFmt: i.price_fmt,
                        subtotalFmt: i.subtotal_fmt
                    }));
                    this.receipt.totalFmt = tx.total_fmt;
                    this.receipt.paidFmt = tx.paid_fmt;
                    this.receipt.changeFmt = tx.change_fmt;
                    this.receipt.discountFmt = tx.discount_fmt;
                    this.receipt.notes = tx.notes;
                    this.showToast('success', 'Pembayaran berhasil!');
                    this.showReceipt = true;
                    this.clearCart(true);
                } else {
                    this.showToast('error', data.message || 'Gagal memproses transaksi.');
                }
            })
            .catch(err => {
                btn.innerHTML = orig;
                btn.disabled = false;
                this.showToast('error', 'Gagal terhubung ke server.');
            });
        },

        newTransaction() {
            this.showReceipt = false;
            this.showMobileCart = false;
            this.clearCart(true);
            this.paidAmount = 0;
            this.paymentMethod = 'cash';
            if (this.$refs.paidInput) {
                this.$refs.paidInput.value = '';
            }
            this.$refs.paidInput?.focus();
        },

        mobileCheckout() {
            // Trigger the main form submit for checkout
            const form = this.$el.querySelector('form[action*="transaksi"]');
            if (form) {
                form.querySelector('button[type="submit"]').click();
            }
        },

        printReceipt() {
            const content = document.querySelector('[x-show="showReceipt"] > div').cloneNode(true);
            const closeBtn = content.querySelector('button');
            if (closeBtn) closeBtn.remove();
            const btns = content.querySelector('.flex.gap-2');
            if (btns && btns.parentElement) btns.remove();

            const w = window.open('', '_blank', 'width=340,height=640');
            w.document.write(`
                <!DOCTYPE html>
                <html><head><title>Struk</title>
                <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
                <script src="https://cdn.tailwindcss.com"><\/script>
                <style>body{font-family:system-ui,-apple-system,sans-serif;margin:0;padding:16px;}</style>
                </head><body>${content.outerHTML}</body></html>
            `);
            w.document.close();
            setTimeout(() => w.print(), 400);
        }
    };
}
</script>
@endpush
