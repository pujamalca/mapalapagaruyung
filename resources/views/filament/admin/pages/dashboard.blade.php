<x-filament-panels::page>
    {{-- Welcome section --}}
    <div class="mb-6">
        <div class="rounded-lg bg-gradient-to-r from-green-600 to-teal-600 p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold">Selamat Datang, {{ auth()->user()->name }}!</h2>
                    <p class="mt-2 text-green-100">
                        Dashboard Admin Mapala Pagaruyung
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-green-100">{{ now()->isoFormat('dddd, D MMMM YYYY') }}</p>
                    <p class="text-lg font-semibold">{{ now()->format('H:i') }} WIB</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Widgets --}}
    <x-filament-widgets::widgets
        :widgets="$this->getWidgets()"
        :columns="$this->getColumns()"
    />
</x-filament-panels::page>
