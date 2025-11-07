@extends('layouts.app')

@section('title', 'Profil Saya - Mapala Pagaruyung')

@section('content')
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold">Profil Saya</h1>
                <a href="{{ route('member.dashboard') }}"
                   class="px-6 py-3 bg-white text-blue-700 rounded-lg font-semibold hover:bg-blue-50 transition-colors">
                    Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>

    <div class="bg-gray-50 py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <div class="grid md:grid-cols-3 gap-8">
                    {{-- Profile Card --}}
                    <div class="md:col-span-1">
                        <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-200 text-center">
                            <div class="w-32 h-32 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white text-5xl font-bold mx-auto mb-4">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-1">{{ $user->name }}</h2>
                            <p class="text-gray-600 mb-4">{{ $user->getRoleNames()->first() }}</p>

                            @if($user->cohort)
                                <div class="mb-2">
                                    <span class="inline-block px-4 py-2 bg-blue-100 text-blue-700 rounded-lg font-semibold">
                                        {{ $user->cohort->name }}
                                    </span>
                                </div>
                            @endif

                            @if($user->division)
                                <div class="text-sm text-gray-600">
                                    Divisi: {{ $user->division->name }}
                                </div>
                            @endif

                            <div class="mt-6">
                                <a href="{{ route('member.profile.edit') }}"
                                   class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Edit Profil
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Profile Details --}}
                    <div class="md:col-span-2 space-y-6">
                        {{-- Personal Info --}}
                        <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-200">
                            <h3 class="text-xl font-bold text-gray-900 mb-4">Informasi Pribadi</h3>
                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-sm font-semibold text-gray-600">Email</label>
                                    <p class="text-gray-900">{{ $user->email }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-semibold text-gray-600">Username</label>
                                    <p class="text-gray-900">{{ $user->username ?? '-' }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-semibold text-gray-600">No. Telepon</label>
                                    <p class="text-gray-900">{{ $user->phone ?? '-' }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-semibold text-gray-600">Golongan Darah</label>
                                    <p class="text-gray-900">{{ $user->blood_type ?? '-' }}</p>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="text-sm font-semibold text-gray-600">Alamat</label>
                                    <p class="text-gray-900">{{ $user->address ?? '-' }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Emergency Contact --}}
                        @if($user->emergency_contact_name)
                            <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-200">
                                <h3 class="text-xl font-bold text-gray-900 mb-4">Kontak Darurat</h3>
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-sm font-semibold text-gray-600">Nama</label>
                                        <p class="text-gray-900">{{ $user->emergency_contact_name }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-semibold text-gray-600">No. Telepon</label>
                                        <p class="text-gray-900">{{ $user->emergency_contact_phone ?? '-' }}</p>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="text-sm font-semibold text-gray-600">Hubungan</label>
                                        <p class="text-gray-900">{{ $user->emergency_contact_relation ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Medical Info --}}
                        @if($user->allergies || $user->medical_conditions)
                            <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-200">
                                <h3 class="text-xl font-bold text-gray-900 mb-4">Informasi Medis</h3>
                                <div class="space-y-4">
                                    @if($user->allergies)
                                        <div>
                                            <label class="text-sm font-semibold text-gray-600">Alergi</label>
                                            <p class="text-gray-900">{{ $user->allergies }}</p>
                                        </div>
                                    @endif
                                    @if($user->medical_conditions)
                                        <div>
                                            <label class="text-sm font-semibold text-gray-600">Kondisi Medis</label>
                                            <p class="text-gray-900">{{ $user->medical_conditions }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        {{-- Bio --}}
                        @if($user->bio)
                            <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-200">
                                <h3 class="text-xl font-bold text-gray-900 mb-4">Bio</h3>
                                <p class="text-gray-700 leading-relaxed">{{ $user->bio }}</p>
                            </div>
                        @endif

                        {{-- Member Info --}}
                        <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-200">
                            <h3 class="text-xl font-bold text-gray-900 mb-4">Informasi Keanggotaan</h3>
                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-sm font-semibold text-gray-600">Status</label>
                                    <p class="text-gray-900">
                                        <span class="inline-block px-3 py-1 rounded-full text-sm {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                            {{ $user->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                        </span>
                                    </p>
                                </div>
                                <div>
                                    <label class="text-sm font-semibold text-gray-600">Bergabung Sejak</label>
                                    <p class="text-gray-900">{{ $user->created_at->format('d M Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
