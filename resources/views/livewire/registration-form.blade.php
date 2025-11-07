<div class="min-h-screen bg-gradient-to-br from-green-50 to-blue-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Pendaftaran Anggota Baru</h1>
            <p class="text-xl text-gray-600">{{ $period->name }}</p>
            @if($period->registration_fee > 0)
            <p class="text-sm text-gray-500 mt-2">Biaya Pendaftaran: Rp {{ number_format($period->registration_fee, 0, ',', '.') }}</p>
            @endif
        </div>

        <!-- Progress Steps -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                @for($i = 1; $i <= $totalSteps; $i++)
                <div class="flex-1 {{ $i < $totalSteps ? 'mr-2' : '' }}">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center font-semibold
                                {{ $currentStep >= $i ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-600' }}">
                                {{ $i }}
                            </div>
                        </div>
                        @if($i < $totalSteps)
                        <div class="flex-1 h-1 mx-2 {{ $currentStep > $i ? 'bg-green-600' : 'bg-gray-200' }}"></div>
                        @endif
                    </div>
                    <div class="text-xs text-center mt-2 font-medium {{ $currentStep >= $i ? 'text-green-600' : 'text-gray-500' }}">
                        @switch($i)
                            @case(1) Data Pribadi @break
                            @case(2) Data Akademik @break
                            @case(3) Kesehatan @break
                            @case(4) Motivasi @break
                            @case(5) Dokumen @break
                        @endswitch
                    </div>
                </div>
                @endfor
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white shadow-xl rounded-lg overflow-hidden">
            <form wire:submit.prevent="submit">
                <div class="p-6 sm:p-8">
                    <!-- Step 1: Personal Data -->
                    @if($currentStep === 1)
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-6">Data Pribadi</h2>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap *</label>
                                <input type="text" wire:model="full_name"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    placeholder="Masukkan nama lengkap">
                                @error('full_name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                                    <input type="email" wire:model="email"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                        placeholder="nama@email.com">
                                    @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon/WA *</label>
                                    <input type="text" wire:model="phone"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                        placeholder="08123456789">
                                    @error('phone') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin *</label>
                                    <select wire:model="gender"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                        <option value="">Pilih</option>
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                    @error('gender') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tempat Lahir</label>
                                    <input type="text" wire:model="birth_place"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                        placeholder="Kota">
                                    @error('birth_place') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                                    <input type="date" wire:model="birth_date"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                    @error('birth_date') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                                <textarea wire:model="address" rows="3"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    placeholder="Alamat lengkap"></textarea>
                                @error('address') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Step 2: Academic Data -->
                    @if($currentStep === 2)
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-6">Data Akademik</h2>

                        <div class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">NIM *</label>
                                    <input type="text" wire:model="nim"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                        placeholder="Nomor Induk Mahasiswa">
                                    @error('nim') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Fakultas *</label>
                                    <input type="text" wire:model="faculty"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                        placeholder="Contoh: Teknik">
                                    @error('faculty') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jurusan/Program Studi *</label>
                                <input type="text" wire:model="major"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    placeholder="Contoh: Teknik Informatika">
                                @error('major') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Masuk</label>
                                    <input type="number" wire:model="enrollment_year"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                        placeholder="{{ date('Y') }}" min="2000" max="2030">
                                    @error('enrollment_year') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">IPK (Indeks Prestasi Kumulatif)</label>
                                    <input type="number" step="0.01" wire:model="gpa"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                        placeholder="3.50" min="0" max="4">
                                    @error('gpa') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Step 3: Health & Emergency Contact -->
                    @if($currentStep === 3)
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-6">Kesehatan & Kontak Darurat</h2>

                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-medium text-gray-800 mb-3">Data Kesehatan</h3>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Golongan Darah</label>
                                        <select wire:model="blood_type"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                            <option value="">Pilih</option>
                                            <option value="A">A</option>
                                            <option value="B">B</option>
                                            <option value="AB">AB</option>
                                            <option value="O">O</option>
                                        </select>
                                        @error('blood_type') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Riwayat Penyakit/Alergi</label>
                                        <textarea wire:model="medical_history" rows="3"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                            placeholder="Sebutkan jika ada riwayat penyakit atau alergi yang perlu kami ketahui"></textarea>
                                        @error('medical_history') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-medium text-gray-800 mb-3">Kontak Darurat *</h3>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kontak Darurat *</label>
                                        <input type="text" wire:model="emergency_contact_name"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                            placeholder="Nama orangtua/wali">
                                        @error('emergency_contact_name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Hubungan *</label>
                                            <select wire:model="emergency_contact_relationship"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                                <option value="">Pilih</option>
                                                <option value="Ayah">Ayah</option>
                                                <option value="Ibu">Ibu</option>
                                                <option value="Saudara">Saudara</option>
                                                <option value="Wali">Wali</option>
                                                <option value="Lainnya">Lainnya</option>
                                            </select>
                                            @error('emergency_contact_relationship') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon *</label>
                                            <input type="text" wire:model="emergency_contact_phone"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                                placeholder="08123456789">
                                            @error('emergency_contact_phone') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Step 4: Experience & Motivation -->
                    @if($currentStep === 4)
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-6">Pengalaman & Motivasi</h2>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Pengalaman Organisasi</label>
                                <textarea wire:model="organization_experience" rows="3"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    placeholder="Sebutkan pengalaman organisasi yang pernah diikuti (opsional)"></textarea>
                                @error('organization_experience') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Pengalaman Alam Terbuka/Outdoor</label>
                                <textarea wire:model="outdoor_experience" rows="3"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    placeholder="Sebutkan pengalaman kegiatan outdoor seperti mendaki, camping, dll (opsional)"></textarea>
                                @error('outdoor_experience') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Motivasi Bergabung *</label>
                                <textarea wire:model="motivation" rows="5"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    placeholder="Ceritakan motivasi dan alasan Anda ingin bergabung dengan Mapala Pagaruyung"></textarea>
                                @error('motivation') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Keahlian/Skills (Opsional)</label>
                                @foreach($skills as $index => $skill)
                                <div class="flex gap-2 mb-2">
                                    <input type="text" wire:model="skills.{{ $index }}.skill"
                                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                        placeholder="Contoh: Fotografi, P3K, Navigasi">
                                    <button type="button" wire:click="removeSkill({{ $index }})"
                                        class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                                        Hapus
                                    </button>
                                </div>
                                @endforeach
                                <button type="button" wire:click="addSkill"
                                    class="mt-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                                    + Tambah Skill
                                </button>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Step 5: Documents -->
                    @if($currentStep === 5)
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-6">Upload Dokumen</h2>

                        <div class="space-y-4">
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                                <p class="text-sm text-yellow-800">
                                    <strong>Catatan:</strong> Ukuran maksimal file: 2MB. Format: JPG, PNG, atau PDF.
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Pas Foto (3x4) *</label>
                                <input type="file" wire:model="photo" accept="image/*"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                                @error('photo') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                <div wire:loading wire:target="photo" class="text-sm text-gray-500 mt-1">Uploading...</div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">KTP *</label>
                                <input type="file" wire:model="ktp" accept="image/*,application/pdf"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                                @error('ktp') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                <div wire:loading wire:target="ktp" class="text-sm text-gray-500 mt-1">Uploading...</div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">KTM (Kartu Tanda Mahasiswa) *</label>
                                <input type="file" wire:model="ktm" accept="image/*,application/pdf"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                                @error('ktm') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                <div wire:loading wire:target="ktm" class="text-sm text-gray-500 mt-1">Uploading...</div>
                            </div>

                            @if($period->registration_fee > 0)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Bukti Pembayaran</label>
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-2">
                                    {!! $period->payment_instructions !!}
                                </div>
                                <input type="file" wire:model="payment_proof" accept="image/*,application/pdf"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                                @error('payment_proof') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                <div wire:loading wire:target="payment_proof" class="text-sm text-gray-500 mt-1">Uploading...</div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Navigation Buttons -->
                <div class="bg-gray-50 px-6 sm:px-8 py-4 flex justify-between items-center border-t border-gray-200">
                    <div>
                        @if($currentStep > 1)
                        <button type="button" wire:click="previousStep"
                            class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium">
                            Kembali
                        </button>
                        @endif
                    </div>

                    <div class="text-sm text-gray-600">
                        Step {{ $currentStep }} dari {{ $totalSteps }}
                    </div>

                    <div>
                        @if($currentStep < $totalSteps)
                        <button type="button" wire:click="nextStep"
                            class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium">
                            Lanjut
                        </button>
                        @else
                        <button type="submit"
                            class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium"
                            wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="submit">Kirim Pendaftaran</span>
                            <span wire:loading wire:target="submit">Mengirim...</span>
                        </button>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        @if(session()->has('error'))
        <div class="mt-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
        @endif
    </div>
</div>
