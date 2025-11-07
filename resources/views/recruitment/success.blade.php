@extends('layouts.guest')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 to-blue-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        <div class="bg-white shadow-xl rounded-lg overflow-hidden">
            <!-- Success Icon -->
            <div class="bg-green-600 px-6 py-8 text-center">
                <svg class="mx-auto h-16 w-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <h1 class="mt-4 text-2xl font-bold text-white">Pendaftaran Berhasil!</h1>
            </div>

            <!-- Content -->
            <div class="px-6 py-8">
                <div class="text-center mb-6">
                    <p class="text-gray-700 mb-2">No. Pendaftaran Anda:</p>
                    <div class="bg-gray-100 rounded-lg px-4 py-3 mb-4">
                        <p class="text-2xl font-bold text-green-600">{{ $registration_number }}</p>
                    </div>
                    <p class="text-sm text-gray-600">Simpan nomor pendaftaran ini untuk keperluan tracking status</p>
                </div>

                <div class="space-y-4 text-sm text-gray-700">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h3 class="font-semibold text-blue-900 mb-2">Langkah Selanjutnya:</h3>
                        <ol class="list-decimal list-inside space-y-1 text-blue-800">
                            <li>Tim kami akan memverifikasi dokumen Anda</li>
                            <li>Anda akan dihubungi via email/WhatsApp untuk informasi seleksi</li>
                            <li>Pantau email Anda secara berkala</li>
                        </ol>
                    </div>

                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <h3 class="font-semibold text-yellow-900 mb-2">Penting:</h3>
                        <ul class="list-disc list-inside space-y-1 text-yellow-800">
                            <li>Proses verifikasi membutuhkan 1-3 hari kerja</li>
                            <li>Pastikan nomor telepon Anda aktif</li>
                            <li>Cek email (termasuk folder spam)</li>
                        </ul>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <p class="text-sm text-gray-600 text-center mb-2">Pertanyaan? Hubungi:</p>
                    <div class="text-center space-y-1 text-sm">
                        <p class="text-gray-700"><strong>WhatsApp:</strong> 0812-3456-7890</p>
                        <p class="text-gray-700"><strong>Email:</strong> recruitment@mapalapagaruyung.org</p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-8 space-y-3">
                    <a href="{{ route('home') }}"
                        class="block w-full text-center px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium">
                        Kembali ke Beranda
                    </a>
                    <button onclick="window.print()"
                        class="block w-full text-center px-4 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium">
                        Cetak Bukti Pendaftaran
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
