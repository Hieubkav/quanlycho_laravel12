<?php

namespace App\Livewire\Khaosat;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;

class Dashboard extends Component
{
    public function mount(): void
    {
        // Guarded by auth middleware
    }

    public function startSurvey(): void
    {
        $this->redirect(route('khaosat.create'));
    }

    public function viewHistory(): void
    {
        $this->redirect(route('khaosat.history'));
    }

    public function logout(): void
    {
        Auth::guard('sale')->logout();
        $this->redirect(route('khaosat.login'));
    }

    public function render(): View
    {
        return view('livewire.khaosat.dashboard');
    }
}
