<?php

namespace App\Livewire\Khaosat;

use App\Models\Sale;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Livewire\Component;

class Login extends Component
{
    public $email = '';

    public $password = '';

    public $remember = false;

    protected function rules(): array
    {
        return [
            'email' => 'required|string',
            'password' => 'required|string',
        ];
    }

    public function mount(): void
    {
        if (Auth::guard('sale')->check()) {
            $this->redirect('/khaosat');
        }
    }

    public function login(): void
    {
        $this->validate();

        $sale = Sale::where('active', true)
            ->where(function ($query) {
                $query->where('email', $this->email)
                    ->orWhere('phone', $this->email);
            })
            ->first();

        if ($sale && Hash::check($this->password, $sale->password)) {
            Auth::guard('sale')->login($sale, $this->remember);
            session()->forget('url.intended');

            $this->email = '';
            $this->password = '';
            $this->remember = false;

            $this->redirect('/khaosat');

            return;
        }

        $this->addError('email', 'Thong tin dang nhap khong chinh xac.');
    }

    public function render(): View
    {
        return view('livewire.khaosat.login');
    }
}
