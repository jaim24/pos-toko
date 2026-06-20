@extends('layouts.app')
@section('title', 'Transaksi Baru')

@push('head')
<style>
    .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); }
    @media (min-width: 640px) { .product-grid { grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); } }
    @media (min-width: 1024px) { .product-grid { grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); } }
</style>
@endpush

@section('content')

<div class="flex flex-col lg:flex-row flex-1 min-h-0" x-data="posCart()">
    {{-- Left Column: Product Panel --}}
    <section class="flex-1 flex flex-col overflow-hidden min-w-0 min-h-0">
        {{-- Category Filters --}}
        <div class="pt-2 sm:pt-4 lg:pt-6 pb-2 flex gap-2 sm:gap-3 overflow-x-auto shrink-0" @wheel.prevent="$el.scrollLeft += $event.deltaY">
            <button class="whitespace-nowrap px-3 sm:px-5 py-2 sm:py-2.5 rounded-full bg-secondary text-white font-medium text-xs sm:text-sm shadow-sm transition-all shrink-0"
                    @click="filterCategory = ''">
                Semua Produk
            </button>
            @foreach($categories as $cat)
            <button class="whitespace-nowrap px-3 sm:px-5 py-2 sm:py-2.5 rounded-full bg-white border border-outline-variant text-on-surface-variant font-medium text-xs sm:text-sm hover:border-secondary hover:text-secondary transition-all shadow-sm shrink-0"
                    @click="filterCategory = '{{ $cat->id }}'">
                {{ $cat->name }}
            </button>
            @endforeach
        </div>

        {{-- Product Grid --}}
        <div class="flex-1 overflow-y-auto pb-4 sm:pb-6 lg:pb-8 pt-3 sm:pt-4">
            <div class="product-grid gap-3 sm:gap-4 lg:gap-6">
                @foreach($products as $p)
                <div class="bg-white rounded-xl sm:rounded-[20px] p-3 sm:p-4 flex flex-col hover:shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-outline-variant/50 hover:border-secondary/30 transition-all duration-300 cursor-pointer group"
                     x-show="filterCategory === '' || filterCategory === '{{ $p->category_id }}'"
                     @click='addItem({{ $p->id }}, @js($p->name), {{ $p->price }}, {{ $p->stock }})'>
                    <div class="h-24 sm:h-32 lg:h-36 bg-surface-container-low rounded-xl sm:rounded-2xl relative overflow-hidden mb-3 sm:mb-4 flex items-center justify-center">
                        <span class="material-symbols-outlined text-3xl sm:text-5xl text-on-surface-variant/30">inventory_2</span>
                        <div class="absolute top-2 left-2 sm:top-3 sm:left-3 bg-white/90 backdrop-blur px-2 sm:px-3 py-0.5 sm:py-1 rounded-full shadow-sm">
                            <p class="text-[9px] sm:text-[10px] font-bold text-on-surface-variant uppercase tracking-wider">{{ $p->category->name }}</p>
                        </div>
                        <div class="absolute bottom-2 right-2 sm:bottom-3 sm:right-3 bg-white/90 px-2 sm:px-2.5 py-0.5 rounded-full shadow-sm">
                            <p class="text-[9px] sm:text-[10px] text-on-surface-variant font-mono">Stok: {{ $p->stock }}</p>
                        </div>
                    </div>
                    <div class="flex flex-col flex-1 px-0 sm:px-1">
                        <h3 class="font-semibold text-on-surface text-xs sm:text-sm line-clamp-2 mb-1">{{ $p->name }}</h3>
                        <div class="mt-auto flex justify-between items-center">
                            <p class="font-mono text-secondary font-bold text-xs sm:text-base">Rp {{ number_format($p->price, 0, ',', '.') }}</p>
                            <button class="w-8 h-8 sm:w-10 sm:h-10 rounded-full border border-outline-variant flex items-center justify-center text-on-surface-variant group-hover:bg-secondary group-hover:text-white group-hover:border-secondary transition-all shadow-sm group-hover:shadow-md shrink-0"
                                    @click.stop='addItem({{ $p->id }}, @js($p->name), {{ $p->price }}, {{ $p->stock }})'>
                                <span class="material-symbols-outlined text-[16px] sm:text-[20px]">add</span>
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Right Column: Cart --}}
    <section class="w-full lg:w-[400px] flex flex-col bg-white border-t lg:border-l lg:border-t-0 border-outline-variant shadow-[0_-8px_32px_rgba(0,0,0,0.04)] lg:shadow-[-8px_0_32px_rgba(0,0,0,0.03)] z-10 max-h-[40vh] lg:max-h-full" x-show="true">
        <div class="p-4 sm:p-6 border-b border-outline-variant flex items-center justify-between bg-white shrink-0">
            <div>
                <h2 class="font-semibold text-lg sm:text-xl text-on-surface mb-0.5 sm:mb-1">Pesanan</h2>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-secondary"></span>
                    <p class="text-on-surface-variant text-xs font-medium">{{ auth()->user()->name }}</p>
                </div>
            </div>
            <button @click="clearCart()" class="flex items-center gap-1.5 px-3 py-1.5 border border-outline-variant rounded-full text-xs font-medium text-on-surface-variant hover:text-error hover:border-error transition-colors">
                <span class="material-symbols-outlined text-[14px]">delete</span> Clear
            </button>
        </div>

        {{-- Cart Items --}}
        <div class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-2 sm:space-y-3 bg-[#F8FAFC]">
            <template x-for="(item, idx) in items" :key="item.id">
                <div class="flex items-center gap-3 sm:gap-4 bg-white p-2.5 sm:p-3.5 rounded-xl sm:rounded-[20px] border border-outline-variant shadow-sm">
                    <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-xl sm:rounded-2xl bg-surface-container-low overflow-hidden shrink-0 flex items-center justify-center">
                        <span class="material-symbols-outlined text-xl sm:text-2xl text-on-surface-variant/30">inventory_2</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="font-semibold text-on-surface text-xs sm:text-sm truncate mb-0.5" x-text="item.name"></h4>
                        <p class="text-xs text-on-surface-variant font-mono" x-text="formatRp(item.price)"></p>
                    </div>
                    <div class="flex flex-col items-end gap-1.5 sm:gap-2 shrink-0">
                        <button @click="removeItem(idx)" class="text-outline hover:text-error transition-colors">
                            <span class="material-symbols-outlined text-[16px] sm:text-[18px]">close</span>
                        </button>
                        <div class="flex items-center border border-outline-variant rounded-full h-7 sm:h-8 bg-white overflow-hidden shadow-sm">
                            <button @click="decreaseQty(idx)" class="w-7 sm:w-8 h-full flex items-center justify-center text-on-surface-variant hover:bg-surface-container-low hover:text-secondary transition-colors">
                                <span class="material-symbols-outlined text-[14px] sm:text-[16px]">remove</span>
                            </button>
                            <span class="font-mono text-xs sm:text-sm w-5 sm:w-6 text-center font-medium border-x border-outline-variant" x-text="item.qty"></span>
                            <button @click="increaseQty(idx)" class="w-7 sm:w-8 h-full flex items-center justify-center text-on-surface-variant hover:bg-surface-container-low hover:text-secondary transition-colors">
                                <span class="material-symbols-outlined text-[14px] sm:text-[16px]">add</span>
                            </button>
                        </div>
                    </div>
                </div>
            </template>
            <div x-show="items.length === 0" class="flex flex-col items-center justify-center py-12 sm:py-16 text-center">
                <span class="material-symbols-outlined text-4xl sm:text-6xl text-outline-variant mb-3 sm:mb-4">shopping_cart</span>
                <p class="text-on-surface-variant font-medium mb-1 text-sm sm:text-base">Keranjang kosong</p>
                <p class="text-xs text-on-surface-variant">Pilih produk dari katalog</p>
            </div>
        </div>

        {{-- Summary Panel --}}
        <div class="p-4 sm:p-6 bg-white border-t border-outline-variant rounded-t-[24px] sm:rounded-t-[32px] shadow-[0_-8px_32px_rgba(0,0,0,0.04)] relative z-20 shrink-0">
            <div class="space-y-3 mb-4 sm:mb-6">
                <div class="flex justify-between items-center text-sm">
                    <span class="text-on-surface-variant font-medium">Subtotal</span>
                    <span class="font-mono font-medium text-on-surface" x-text="formatRp(subtotal)"></span>
                </div>
                <div class="flex justify-between items-center pt-3 sm:pt-4 border-t border-outline-variant border-dashed mt-2">
                    <span class="font-semibold text-base sm:text-lg text-on-surface">Total</span>
                    <span class="font-mono font-bold text-secondary text-xl sm:text-2xl" x-text="formatRp(subtotal)"></span>
                </div>
            </div>

            <form method="POST" action="{{ route('kasir.transactions.store') }}" @submit.prevent="checkout">
                @csrf
                <div class="space-y-3 sm:space-y-4">
                    {{-- Uang Bayar --}}
                    <div class="relative" x-show="paymentMethod === 'cash'">
                        <label class="absolute -top-2.5 left-4 px-1.5 bg-white text-[10px] font-bold text-secondary uppercase tracking-wider z-10">Uang Bayar</label>
                        <input type="text" inputmode="numeric"
                               x-ref="paidInput"
                               :value="paidDisplay"
                               @input="onPaidInput($event)"
                               class="w-full bg-white border-2 border-outline-variant rounded-[14px] sm:rounded-[16px] px-4 py-3 sm:py-3.5 text-right font-mono text-lg sm:text-xl font-medium focus:ring-2 focus:ring-secondary/20 focus:border-secondary transition-all shadow-sm"
                               placeholder="Rp 0">
                    </div>

                    {{-- Kembalian --}}
                    <div class="flex items-center justify-between bg-secondary/5 px-4 sm:px-5 py-3 sm:py-4 rounded-[14px] sm:rounded-[16px] border border-secondary/20"
                         x-show="paymentMethod === 'cash'">
                        <span class="text-secondary text-xs sm:text-sm font-semibold tracking-wide">Kembalian</span>
                        <span class="font-mono font-bold text-secondary text-lg sm:text-xl" x-text="formatRp(change)"></span>
                    </div>

                    {{-- QRIS Panel --}}
                    <div x-show="paymentMethod === 'qris'" class="bg-[#F8FAFC] rounded-[16px] sm:rounded-[20px] p-4 sm:p-5 border border-outline-variant/30 space-y-3 sm:space-y-4">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-secondary text-xl">qr_code_2</span>
                            <span class="text-xs font-bold text-on-surface uppercase tracking-wider">QRIS</span>
                        </div>
                        {{-- QR Code Dummy --}}
                        <div class="flex justify-center">
                            <div class="w-36 h-36 sm:w-44 sm:h-44 bg-white rounded-2xl border-2 border-outline-variant/30 p-3 flex items-center justify-center relative shadow-sm">
                                {{-- Fake QR grid --}}
                                <svg viewBox="0 0 100 100" class="w-full h-full">
                                    <rect x="10" y="10" width="30" height="30" rx="4" fill="#1A1A1A"/>
                                    <rect x="14" y="14" width="22" height="22" rx="2" fill="white"/>
                                    <rect x="22" y="22" width="6" height="6" fill="#1A1A1A"/>
                                    <rect x="60" y="10" width="30" height="30" rx="4" fill="#1A1A1A"/>
                                    <rect x="64" y="14" width="22" height="22" rx="2" fill="white"/>
                                    <rect x="73" y="22" width="6" height="6" fill="#1A1A1A"/>
                                    <rect x="10" y="60" width="30" height="30" rx="4" fill="#1A1A1A"/>
                                    <rect x="14" y="64" width="22" height="22" rx="2" fill="white"/>
                                    <rect x="22" y="73" width="6" height="6" fill="#1A1A1A"/>
                                    <rect x="15" y="45" width="8" height="8" rx="1" fill="#1A1A1A" opacity="0.8"/>
                                    <rect x="28" y="45" width="8" height="8" rx="1" fill="#1A1A1A" opacity="0.8"/>
                                    <rect x="55" y="45" width="8" height="8" rx="1" fill="#1A1A1A" opacity="0.8"/>
                                    <rect x="68" y="45" width="8" height="8" rx="1" fill="#1A1A1A" opacity="0.8"/>
                                    <rect x="42" y="60" width="8" height="8" rx="1" fill="#1A1A1A" opacity="0.8"/>
                                    <rect x="55" y="68" width="8" height="8" rx="1" fill="#1A1A1A" opacity="0.8"/>
                                    <rect x="15" y="55" width="8" height="8" rx="1" fill="#1A1A1A" opacity="0.8"/>
                                    <rect x="42" y="15" width="8" height="8" rx="1" fill="#1A1A1A" opacity="0.8"/>
                                    <rect x="55" y="28" width="8" height="8" rx="1" fill="#1A1A1A" opacity="0.8"/>
                                    <rect x="30" y="68" width="8" height="8" rx="1" fill="#1A1A1A" opacity="0.8"/>
                                    <rect x="78" y="55" width="8" height="8" rx="1" fill="#1A1A1A" opacity="0.8"/>
                                    <rect x="42" y="78" width="8" height="8" rx="1" fill="#1A1A1A" opacity="0.8"/>
                                    <rect x="78" y="78" width="8" height="8" rx="1" fill="#1A1A1A" opacity="0.8"/>
                                </svg>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="bg-white rounded-full p-1.5 shadow-sm border border-outline-variant/50">
                                        <span class="material-symbols-outlined text-secondary text-xl">qr_code</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- Amount --}}
                        <div class="text-center bg-white rounded-xl p-3 border border-outline-variant/20">
                            <p class="text-[10px] text-on-surface-variant uppercase tracking-wider font-semibold mb-1">Total Pembayaran QRIS</p>
                            <p class="font-mono font-bold text-secondary text-xl sm:text-2xl" x-text="formatRp(subtotal)"></p>
                        </div>
                        {{-- Bank / E-Money logos --}}
                        <div class="flex items-center justify-center gap-2 flex-wrap">
                            <span class="px-2.5 py-1 bg-white rounded-full text-[10px] font-medium text-on-surface-variant border border-outline-variant/30 shadow-sm">BCA</span>
                            <span class="px-2.5 py-1 bg-white rounded-full text-[10px] font-medium text-on-surface-variant border border-outline-variant/30 shadow-sm">Mandiri</span>
                            <span class="px-2.5 py-1 bg-white rounded-full text-[10px] font-medium text-on-surface-variant border border-outline-variant/30 shadow-sm">BNI</span>
                            <span class="px-2.5 py-1 bg-white rounded-full text-[10px] font-medium text-on-surface-variant border border-outline-variant/30 shadow-sm">BRI</span>
                            <span class="px-2.5 py-1 bg-white rounded-full text-[10px] font-medium text-on-surface-variant border border-outline-variant/30 shadow-sm">GoPay</span>
                            <span class="px-2.5 py-1 bg-white rounded-full text-[10px] font-medium text-on-surface-variant border border-outline-variant/30 shadow-sm">OVO</span>
                            <span class="px-2.5 py-1 bg-white rounded-full text-[10px] font-medium text-on-surface-variant border border-outline-variant/30 shadow-sm">DANA</span>
                            <span class="px-2.5 py-1 bg-white rounded-full text-[10px] font-medium text-on-surface-variant border border-outline-variant/30 shadow-sm">ShopeePay</span>
                        </div>
                        <p class="text-center text-[9px] text-on-surface-variant/60">Scan kode QRIS di atas menggunakan aplikasi bank/e-money pilihan Anda</p>
                    </div>

                    <select name="payment_method" x-model="paymentMethod" @change="onPaymentChange()"
                            class="w-full bg-white border border-outline-variant rounded-[14px] sm:rounded-[16px] px-4 py-2.5 sm:py-3 text-xs sm:text-sm focus:ring-2 focus:ring-secondary/20 focus:border-secondary transition-all">
                        <option value="cash">Tunai (Cash)</option>
                        <option value="qris">QRIS</option>
                    </select>

                    <button type="submit"
                            class="w-full bg-secondary hover:bg-[#005236] text-white py-3 sm:py-4 rounded-[14px] sm:rounded-[16px] font-semibold text-base sm:text-lg shadow-lg shadow-secondary/30 flex items-center justify-center gap-2 transition-all active:scale-[0.98]"
                            :disabled="items.length === 0 || (paymentMethod === 'cash' && paidAmount < subtotal)">
                        Proses Bayar
                        <span class="material-symbols-outlined">arrow_forward</span>
                    </button>
                </div>

                {{-- Hidden items data --}}
                <input type="hidden" name="items_json" :value="JSON.stringify(items)">
                <input type="hidden" name="paid_amount" :value="paymentMethod === 'qris' ? subtotal : paidAmount">
            </form>
        </div>
    </section>
</div>

    {{-- Toast Notification --}}
    <div x-show="toast.show" x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-4"
         class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[60] px-5 py-3 rounded-2xl shadow-xl flex items-center gap-3 text-sm font-semibold border backdrop-blur-sm"
         :class="toast.type === 'success' ? 'bg-white text-on-surface border-secondary/30 shadow-secondary/10' : 'bg-white text-on-surface border-error/30 shadow-error/10'">
        <span class="material-symbols-outlined text-xl" x-text="toast.type === 'success' ? 'check_circle' : 'error'" 
              :class="toast.type === 'success' ? 'text-secondary' : 'text-error'"></span>
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
                <div class="flex justify-between text-sm">
                    <span class="text-on-surface-variant">Total</span>
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
        paidRaw: '',
        paymentMethod: 'cash',
        filterCategory: '',
        showReceipt: false,
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
            changeFmt: ''
        },

        get subtotal() {
            return this.items.reduce((sum, i) => sum + (i.price * i.qty), 0);
        },

        get change() {
            return Math.max(0, this.paidAmount - this.subtotal);
        },

        get paidDisplay() {
            if (this.paidAmount > 0) {
                return 'Rp ' + new Intl.NumberFormat('id-ID').format(this.paidAmount);
            }
            return this.paidRaw || '';
        },

        onPaidInput(e) {
            const raw = e.target.value;
            this.paidRaw = raw;
            // Parse: strip non-digit, convert to number
            const clean = raw.replace(/[^\d]/g, '');
            this.paidAmount = clean === '' ? 0 : parseInt(clean, 10);
        },

        onPaymentChange() {
            // QRIS: auto-set paid = subtotal (exact amount)
            if (this.paymentMethod === 'qris') {
                this.paidAmount = this.subtotal;
                this.paidRaw = '';
            } else {
                this.paidAmount = 0;
                this.paidRaw = '';
            }
        },

        addItem(id, name, price, stock) {
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

        clearCart() {
            this.items = [];
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
            if (this.paymentMethod === 'cash' && this.paidAmount < this.subtotal) {
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
            fd.append('paid_amount', this.paymentMethod === 'qris' ? this.subtotal : this.paidAmount);
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
                    this.showToast('success', 'Pembayaran berhasil!');
                    this.showReceipt = true;
                    this.clearCart();
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
            this.clearCart();
            this.paidAmount = 0;
            this.paidRaw = '';
            this.paymentMethod = 'cash';
            this.$refs.paidInput?.focus();
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
