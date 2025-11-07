<?php

namespace App\Livewire;

use App\Models\Applicant;
use App\Models\RecruitmentPeriod;
use Livewire\Component;
use Livewire\WithFileUploads;

class RegistrationForm extends Component
{
    use WithFileUploads;

    // Recruitment Period
    public ?RecruitmentPeriod $period = null;

    // Personal Data
    public $full_name = '';
    public $email = '';
    public $phone = '';
    public $birth_date = '';
    public $birth_place = '';
    public $gender = '';
    public $address = '';

    // Academic Data
    public $nim = '';
    public $major = '';
    public $faculty = '';
    public $enrollment_year = '';
    public $gpa = '';

    // Health Data
    public $blood_type = '';
    public $medical_history = '';

    // Emergency Contact
    public $emergency_contact_name = '';
    public $emergency_contact_relationship = '';
    public $emergency_contact_phone = '';

    // Experience & Motivation
    public $organization_experience = '';
    public $outdoor_experience = '';
    public $motivation = '';
    public $skills = [];

    // Documents
    public $photo;
    public $ktp;
    public $ktm;
    public $form_document;
    public $payment_proof;

    // UI State
    public $currentStep = 1;
    public $totalSteps = 5;

    protected $rules = [
        'full_name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'required|string|max:20',
        'birth_date' => 'nullable|date',
        'birth_place' => 'nullable|string|max:255',
        'gender' => 'required|in:L,P',
        'address' => 'nullable|string',

        'nim' => 'required|string|max:50',
        'major' => 'required|string|max:255',
        'faculty' => 'required|string|max:255',
        'enrollment_year' => 'nullable|integer|min:2000|max:' . 2030,
        'gpa' => 'nullable|numeric|min:0|max:4',

        'blood_type' => 'nullable|string|max:5',
        'medical_history' => 'nullable|string',

        'emergency_contact_name' => 'required|string|max:255',
        'emergency_contact_relationship' => 'required|string',
        'emergency_contact_phone' => 'required|string|max:20',

        'motivation' => 'required|string',

        'photo' => 'required|image|max:2048',
        'ktp' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        'ktm' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        'payment_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
    ];

    public function mount()
    {
        // Get active recruitment period
        $this->period = RecruitmentPeriod::open()->first();

        if (!$this->period) {
            abort(404, 'Tidak ada periode recruitment yang sedang dibuka.');
        }

        // Check if max applicants reached
        if ($this->period->hasReachedMaxApplicants()) {
            abort(403, 'Maaf, kuota pendaftar sudah penuh.');
        }

        // Initialize current year for enrollment
        $this->enrollment_year = date('Y');
    }

    public function nextStep()
    {
        $this->validateCurrentStep();

        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    protected function validateCurrentStep()
    {
        $validationRules = match($this->currentStep) {
            1 => [
                'full_name' => $this->rules['full_name'],
                'email' => $this->rules['email'],
                'phone' => $this->rules['phone'],
                'birth_date' => $this->rules['birth_date'],
                'birth_place' => $this->rules['birth_place'],
                'gender' => $this->rules['gender'],
                'address' => $this->rules['address'],
            ],
            2 => [
                'nim' => $this->rules['nim'],
                'major' => $this->rules['major'],
                'faculty' => $this->rules['faculty'],
                'enrollment_year' => $this->rules['enrollment_year'],
                'gpa' => $this->rules['gpa'],
            ],
            3 => [
                'blood_type' => $this->rules['blood_type'],
                'medical_history' => $this->rules['medical_history'],
                'emergency_contact_name' => $this->rules['emergency_contact_name'],
                'emergency_contact_relationship' => $this->rules['emergency_contact_relationship'],
                'emergency_contact_phone' => $this->rules['emergency_contact_phone'],
            ],
            4 => [
                'motivation' => $this->rules['motivation'],
            ],
            5 => [
                'photo' => $this->rules['photo'],
                'ktp' => $this->rules['ktp'],
                'ktm' => $this->rules['ktm'],
            ],
            default => []
        };

        $this->validate($validationRules);
    }

    public function addSkill()
    {
        $this->skills[] = ['skill' => ''];
    }

    public function removeSkill($index)
    {
        unset($this->skills[$index]);
        $this->skills = array_values($this->skills);
    }

    public function submit()
    {
        // Validate all fields
        $this->validate();

        try {
            // Generate registration number
            $prefix = $this->period->metadata['registration_prefix'] ?? 'MAP-' . date('Y') . '-';
            $lastApplicant = Applicant::where('recruitment_period_id', $this->period->id)
                ->orderBy('id', 'desc')
                ->first();

            $number = $lastApplicant
                ? intval(substr($lastApplicant->registration_number, -4)) + 1
                : 1;

            $registrationNumber = $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);

            // Create applicant
            $applicant = Applicant::create([
                'recruitment_period_id' => $this->period->id,
                'registration_number' => $registrationNumber,
                'full_name' => $this->full_name,
                'email' => $this->email,
                'phone' => $this->phone,
                'birth_date' => $this->birth_date,
                'birth_place' => $this->birth_place,
                'gender' => $this->gender,
                'address' => $this->address,
                'nim' => $this->nim,
                'major' => $this->major,
                'faculty' => $this->faculty,
                'enrollment_year' => $this->enrollment_year,
                'gpa' => $this->gpa,
                'blood_type' => $this->blood_type,
                'medical_history' => $this->medical_history,
                'emergency_contact' => [
                    'name' => $this->emergency_contact_name,
                    'relationship' => $this->emergency_contact_relationship,
                    'phone' => $this->emergency_contact_phone,
                ],
                'organization_experience' => $this->organization_experience,
                'outdoor_experience' => $this->outdoor_experience,
                'motivation' => $this->motivation,
                'skills' => array_filter($this->skills, fn($skill) => !empty($skill['skill'])),
                'status' => 'registered',
            ]);

            // Upload documents
            if ($this->photo) {
                $applicant->addMedia($this->photo->getRealPath())
                    ->usingFileName($this->photo->getClientOriginalName())
                    ->toMediaCollection('photo');
            }

            if ($this->ktp) {
                $applicant->addMedia($this->ktp->getRealPath())
                    ->usingFileName($this->ktp->getClientOriginalName())
                    ->toMediaCollection('ktp');
            }

            if ($this->ktm) {
                $applicant->addMedia($this->ktm->getRealPath())
                    ->usingFileName($this->ktm->getClientOriginalName())
                    ->toMediaCollection('ktm');
            }

            if ($this->payment_proof) {
                $applicant->addMedia($this->payment_proof->getRealPath())
                    ->usingFileName($this->payment_proof->getClientOriginalName())
                    ->toMediaCollection('payment_proof');
            }

            // Redirect to success page
            return redirect()->route('recruitment.success', ['registration_number' => $registrationNumber]);

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
            \Log::error('Registration error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.registration-form')->layout('layouts.guest');
    }
}
