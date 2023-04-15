<?php

namespace App\Http\Livewire;

use App\Exports\BillingsExport;
use App\Models\Advisor;
use App\Models\Company;
use App\Models\Course;
use App\Models\CourseStatus;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Billing;

class Billings extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $course_id, $company_id, $number_students, $billing, $bonus, $total_training_activity, $expenses, $only_organizing_entity, $salary_costs, $payment_id, $communication_start_date, $communication_end_date, $invoiced, $billing_number, $billing_date, $collection_date, $bonus_status, $company_bonus, $observation, $is_bonus, $group, $charged, $collaborator_id;
    public $courses, $companies, $payments, $tab = 'info', $students, $search_student_name, $search_surname, $advisors, $advisor_id, $collaborators;
    public $course_search = -1, $company_search = -1, $student_search = -1, $is_bonus_search = -1, $status_search = -1, $course_statuses, $course_name, $company_name, $beginning, $end, $invoiced_search = -1, $charged_search = -1;
    protected $listeners = [
        'destroy' => 'destroy'
    ];

    public function render()
    {
        $keyWord = '%'.$this->keyWord .'%';
        $billings = Billing::getBillings($keyWord, $this->course_search, $this->company_search, $this->student_search, $this->is_bonus_search, $this->status_search, $this->invoiced_search, $this->charged_search);

        if ($this->selected_id){
            $search_student_name = '%'.$this->search_student_name.'%';
            $search_surname = '%'.$this->search_surname.'%';
            $this->students = Student::getBilledStudent($this->selected_id, $search_student_name, $search_surname);
        }

        return view('livewire.billings.list', [
            'billings' => $billings,
        ]);
    }

    public function mount(){
        $this-> courses = Course::all();
        $this-> companies = Company::all();
        $this-> payments = Payment::all();
        $this-> students = Student::all();
        $this-> advisors = Advisor::all();
        $this->course_statuses = CourseStatus::all();
        $this->collaborators = User::where('has_commission', 1)->get();
    }

    public function destroy($id)
    {
        if ($id) {
            Registration::eliminateBill($id);
            $value = Billing::destroy($id);
            $this->dispatchBrowserEvent('eliminated', ['value' => $value]);
        }
    }

    public function general($id){
        $record = Billing::findOrFail($id);

        $this->selected_id = $id;
        $this->course_id = $record-> course_id;
        $this->company_id = $record-> company_id;
        $this->number_students = $record-> number_students;
        $this->billing = $record-> billing;
        $this->bonus = $record-> bonus;
        $this->total_training_activity = $record-> total_training_activity;
        $this->expenses = $record-> expenses;
        $this->only_organizing_entity = $record-> only_organizing_entity;
        $this->salary_costs = $record-> salary_costs;
        $this->payment_id = $record-> payment_id;
        $this->communication_start_date = $record-> communication_start_date;
        $this->communication_end_date = $record-> communication_end_date;
        $this->invoiced = $record-> invoiced;
        $this->billing_number = $record-> billing_number;
        $this->billing_date = $record-> billing_date;
        $this->collection_date = $record-> collection_date;
        $this->bonus_status = $record-> bonus_status;
        $this->company_bonus = $record-> company_bonus;
        $this->observation = $record-> observation;
        $this->is_bonus = $record-> is_bonus;
        $this->advisor_id = $record->advisor_id;
        $this->charged = $record->charged== 1 ? $record-> charged : null;
        $this->collaborator_id = $record->collaborator_id;

        $course = Course::find($this->course_id);
        $this->group = $course->group;
        $this->course_name = $course->name;
        $company = Company::find($record->company_id);

        $this->company_name = $company->name;
        $this->beginning = Carbon::parse($course->beginning)->format('d/m/Y');
        $this->end = Carbon::parse($course->end)->format('d/m/Y');
    }

    public function downloadExcel(){
        return (new BillingsExport($this->course_search, $this->company_search, $this->student_search, $this->is_bonus_search))->download('facturas.xlsx');
    }
}
