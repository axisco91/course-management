<?php

namespace App\Http\Livewire;

use App\Models\Profitability;
use App\Models\Registration;
use Carbon\Carbon;
use Livewire\Component;

class Home extends Component
{

    public $total_registrations, $registrations_count, $years, $year, $total_benefits, $benefits_per_month, $expenses_per_month;

    public function render()
    {
        $this->years = range(Carbon::now()->year, '2022');
        return view('livewire.home.view');
    }

    public function mount(){
        $this->year = Carbon::now()->year;
        $this->total_registrations = Registration::totalRegistrations();
        $this->registrations_count = $this->countRegistrations();
        $this->getProfitabilitiesPerMonth();
    }

    public function countRegistrations(){
        $data = [];
        $now = Carbon::now();
        $cont = 1;
        $date = Carbon::parse($now->year.'-01-01');
        while($cont <= $now->month){
            $date->addMonth();
            $start = Carbon::parse($now->year.'-'.$date->month.'-01')->toDateString();
            $limit = Carbon::parse($now->year.'-'.$date->month.'-01')->endOfMonth()->toDateString();

            $registrations = Registration::countRegistrations($start, $limit);
            $data[] = $registrations;
            $cont++;
        }
       return $data;
    }

    public function getProfitabilitiesPerMonth(){
        $this->total_benefits = Profitability::getProfitabilityYear($this->year);
        $this->benefits_per_month = Profitability::getBenefitsPerMonth($this->year);
        $this->expenses_per_month = Profitability::getExpensesPerMonth($this->year);
    }

}
