<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Contact as ContactModel;

use Illuminate\Support\Facades\Mail;
use App\Mail\AdminContactMail;
use App\Mail\UserContactAckMail;
use App\Models\Setting;

class Contact extends Component
{
    public $name;
    public $email;
    public $message;

    protected $rules = [
        'name' => 'required|min:3',
        'email' => 'required|email',
        'message' => 'required|min:10',
    ];

    public function submit()
    {
        $this->validate();

        // Save to database
        $contact = ContactModel::create([
            'name' => $this->name,
            'email' => $this->email,
            'message' => $this->message,
        ]);

        // Get admin email from settings
        $system = Setting::first();
        $adminEmail = $system ? $system->email : 'admin@example.com';

        // Send email to Admin
        try {
            Mail::to($adminEmail)->send(new AdminContactMail($contact));
        } catch (\Exception $e) {
            // Log error or ignore if mail fails
        }

        // Send acknowledgement email to User
        try {
            Mail::to($this->email)->send(new UserContactAckMail($contact));
        } catch (\Exception $e) {
            // Log error or ignore
        }
        
        session()->flash('message', 'Thank you for contacting us! We will get back to you soon.');

        $this->reset();
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.contact');
    }
}
