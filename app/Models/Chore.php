<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chore extends Model
{
	use HasFactory;

    public $timestamps = true;

    protected $fillable = ['course_id','company_id','student_id','membership_tab_status','membership_tab_date','economic_proposal_status','economic_proposal_date','student_tab_status','student_tab_date','welcome_guid_status','welcome_guid_date','registration_status','registration_date','diploma_status','diploma_status_date','start_communication_status','start_communication_date','close_communication_status','close_communication_date','invoiced_status','invoiced_date','bonus_sent_status','bonus_sent_date', 'quarter_date_sent', 'half_date_sent', 'three_quarters_date_sent', 'final_date_sent'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function company()
    {
        return $this->hasOne('App\Models\Company', 'id', 'company_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function course()
    {
        return $this->hasOne('App\Models\Course', 'id', 'course_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function registrations()
    {
        return $this->hasMany('App\Models\Registration', 'chore_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function student()
    {
        return $this->hasOne('App\Models\Student', 'id', 'student_id');
    }

    public function getChores($keyWord, $course_search, $company_search, $student_search){
        $chores = Chore::select('chores.*', 'courses.name as course', 'companies.name as company', 'students.name as student_name', 'students.surname as student_surname')
            ->leftjoin('courses', 'courses.id', '=', 'chores.course_id')
            ->leftjoin('companies', 'companies.id', '=', 'chores.company_id')
            ->leftjoin('students', 'students.id', '=', 'chores.student_id');

        if ($course_search != -1){
            $chores = $chores->where('courses.id', $course_search);
        }
        if ($company_search != -1){
            $chores = $chores->where('companies.id', $company_search);
        }
        if ($student_search != -1){
            $chores = $chores->where('students.id', 'LIKE', $student_search);
        }
        $chores = $chores->where(function ($query) use ($keyWord) {
            $query->orWhere('membership_tab_status', 'LIKE', $keyWord)
                ->orWhere('membership_tab_date', 'LIKE', $keyWord)
                ->orWhere('economic_proposal_status', 'LIKE', $keyWord)
                ->orWhere('economic_proposal_date', 'LIKE', $keyWord)
                ->orWhere('student_tab_status', 'LIKE', $keyWord)
                ->orWhere('student_tab_date', 'LIKE', $keyWord)
                ->orWhere('welcome_guid_status', 'LIKE', $keyWord)
                ->orWhere('welcome_guid_date', 'LIKE', $keyWord)
                ->orWhere('registration_status', 'LIKE', $keyWord)
                ->orWhere('registration_date', 'LIKE', $keyWord)
                ->orWhere('diploma_status', 'LIKE', $keyWord)
                ->orWhere('diploma_status_date', 'LIKE', $keyWord)
                ->orWhere('start_communication_status', 'LIKE', $keyWord)
                ->orWhere('start_communication_date', 'LIKE', $keyWord)
                ->orWhere('close_communication_status', 'LIKE', $keyWord)
                ->orWhere('close_communication_date', 'LIKE', $keyWord)
                ->orWhere('invoiced_status', 'LIKE', $keyWord)
                ->orWhere('invoiced_date', 'LIKE', $keyWord)
                ->orWhere('bonus_sent_status', 'LIKE', $keyWord)
                ->orWhere('bonus_sent_date', 'LIKE', $keyWord);
        })->orderBy('courses.beginning', 'desc')
            ->paginate(10);

        foreach ($chores as $chore){
            $chore['student'] = $chore['student_name'].' '.$chore['student_surname'];
        }

        return $chores;
    }

    public function createChore($data){
        $chore = Chore::create([
            'course_id' => $data['course_id'],
            'company_id' => $data['company_id'],
            'student_id' => $data['student_id']
        ]);
        return $chore;
    }

    public function updateChore($id, $data){
        if ($id) {
            $chore = Chore::find($id);
            $registration = Registration::where('chore_id', $id)->first();

        if ($data['membership_tab_status'] == 0){
            $data['membership_tab_date'] = null;
        } else if ($data['membership_tab_status'] != 0 &&  $data['membership_tab_date'] == null) {
            $data['membership_tab_date'] = Carbon::now()->toDateString();
        }
        if ($data['economic_proposal_status'] == 0){
            $data['economic_proposal_date'] = null;
        } else if ($data['economic_proposal_status'] != 0 && $data['economic_proposal_date'] == null){
            $data['economic_proposal_date'] = Carbon::now()->toDateString();
        }
        if ($data['student_tab_status ']== 0){
            $data['student_tab_date'] = null;
        } else if ($data['student_tab_status'] != 0 && $data['student_tab_date'] == null){
            $data['student_tab_date'] = Carbon::now()->toDateString();
        }
        if ($data['welcome_guid_status'] == 0){
            $data['welcome_guid_date']= null;
        } else if ($data['welcome_guid_status'] != 0 && $data['welcome_guid_date'] == null){
            $data['welcome_guid_date'] = Carbon::now()->toDateString();
        }
        if ($data['registration_status'] == 0){
            $data['registration_date'] = null;
        } else if ($data['registration_status'] != 0 && $data['registration_date'] == null){
            $data['registration_date'] = Carbon::now()->toDateString();
        }
        if ($data['diploma_status'] == 0){
            $data['diploma_status_date'] = null;
        } else if ($data['diploma_status'] != 0 && $data['diploma_status_date'] == null){
            $data['diploma_status_date'] = Carbon::now()->toDateString();
        }
        if ($data['start_communication_status'] == 0){
            $data['start_communication_date'] = null;
        } else if ($data['start_communication_status'] != 0 && $data['start_communication_date'] == null){
            $data['start_communication_date'] = Carbon::now()->toDateString();
            if ($registration->billing_id){
                Billing::updateStartCommunicationDate($registration->billing_id, Carbon::now()->toDateString(), $data['start_communication_date']);
            }
        }
        if ($data['close_communication_status'] == 0){
            $data['close_communication_date'] = null;
        } else if ($data['close_communication_status'] != 0 && $data['close_communication_date'] == null){
            $data['close_communication_date'] = Carbon::now()->toDateString();
            if ($registration->billing_id){
                Billing::updateCloseCommunicationDate($registration->billing_id, Carbon::now()->toDateString(), $data['start_communication_date']);
            }
        }
        if ($data['invoiced_status'] == 0){
            $data['invoiced_date'] = null;
        } else if ($data['invoiced_status'] != 0 && $data['invoiced_date'] == null){
            $data['invoiced_date'] = Carbon::now()->toDateString();
        }
        if ($data['bonus_sent_status'] == 0){
            $data['bonus_sent_date'] = null;
        } else if ($data['bonus_sent_status'] != 0 && $data['bonus_sent_date'] == null){
            $data['bonus_sent_date'] = Carbon::now()->toDateString();
        }

            $chore->update([
                'membership_tab_status' => $data[' membership_tab_status'],
                'membership_tab_date' => $data[' membership_tab_date'],
                'economic_proposal_status' => $data[' economic_proposal_status'],
                'economic_proposal_date' => $data[' economic_proposal_date'],
                'student_tab_status' => $data[' student_tab_status'],
                'student_tab_date' => $data[' student_tab_date'],
                'welcome_guid_status' => $data[' welcome_guid_status'],
                'welcome_guid_date' => $data[' welcome_guid_date'],
                'registration_status' => $data[' registration_status'],
                'registration_date' => $data[' registration_date'],
                'diploma_status' => $data[' diploma_status'],
                'diploma_status_date' => $data[' diploma_status_date'],
                'start_communication_status' => $data[' start_communication_status'],
                'start_communication_date' => $data[' start_communication_date'],
                'close_communication_status' => $data[' close_communication_status'],
                'close_communication_date' => $data[' close_communication_date'],
                'invoiced_status' => $data[' invoiced_status'],
                'invoiced_date' => $data[' invoiced_date'],
                'bonus_sent_status' => $data[' bonus_sent_status'],
                'bonus_sent_date' => $data[' bonus_sent_date']
            ]);

            return $chore;
        }
    }

    public function billingDateChore($id, $date){
        $registrations = Registration::billingRegistration($id);
        foreach ($registrations as $registration){
            $chore = Chore::find($registration->chore_id);
        }

        return true;
    }

    public function updateCommunicationStartDate($id, $date,$status){
        $chore = Chore::find($id);
        $chore = $chore->update([
            'start_communication_date' => $date,
            'start_communication_status' => $status
        ]);
    }

    public function updateCommunicationEndDate($id, $date,$status){
        $chore = Chore::find($id);
        $chore = $chore->update([
            'close_communication_date' => $date,
            'close_communication_status' => $status
        ]);
    }

}
