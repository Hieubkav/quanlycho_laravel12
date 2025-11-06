<?php

namespace App\Livewire\Khaosat;

use App\Models\Sale;
use App\Models\Survey;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;

class History extends Component
{
    public function back(): void
    {
        $this->redirect(route('khaosat'));
    }

    public function createNew(): void
    {
        $this->redirect(route('khaosat.create'));
    }

    public function editSurvey($surveyId): void
    {
        $this->redirect(route('khaosat.edit', $surveyId));
    }

    public function deleteSurvey($surveyId): void
    {
        $survey = Survey::findOrFail($surveyId);

        // Check if user owns this survey
        if (auth('sale')->id() !== $survey->sale_id) {
            session()->flash('error', 'Bạn không có quyền xóa khảo sát này.');

            return;
        }

        $survey->delete();
        session()->flash('message', 'Khảo sát đã được xóa thành công.');
    }

    public function getSurveysProperty()
    {
        /** @var Sale $sale */
        $sale = Auth::guard('sale')->user();

        return Survey::where('sale_id', $sale->id)
            ->with(['market', 'surveyItems.product'])
            ->orderBy('survey_day', 'desc')
            ->get();
    }

    public function render(): View
    {
        return view('livewire.khaosat.history');
    }
}
