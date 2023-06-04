<?php

namespace App\Models;

use App\Helpers\GeneralHelpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Profitability extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $table = 'profitabilities';

    protected $fillable = ['course_id','company_id','student_id','price','license','teacher','management','nebrija_title','discount','collaborator_commission','advisor_commission','total','benefits','observations', 'advisor_percentage', 'collaborator_percentage', 'number_students'];

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
        return $this->hasMany('App\Models\Registration', 'profitability_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function students()
    {
        return $this->hasOne('App\Models\Student', 'id', 'student_id');
    }

    public static function getProfitabilities(){
        $profitabilities = Profitability::select('profitabilities.*',
            'companies.name as company_name',
            DB::raw("CONCAT(training_actions.formative_action,' / ', courses.group, ' ', training_actions.name) as course"),
            'courses.beginning as beginning',
            'students.name as student_name',
            'students.surname as student_surname',
            DB::raw("YEAR(courses.beginning) as year"),
            DB::raw("CONCAT(students.name,' ',students.surname) as student"))
            ->leftjoin('companies', 'companies.id', '=', 'profitabilities.company_id')
            ->leftjoin('courses', 'courses.id', '=', 'profitabilities.course_id')
            ->leftjoin('students', 'students.id', '=', 'profitabilities.student_id')
            ->leftjoin('training_actions', 'training_actions.id', '=', 'courses.training_action_id')
            ->leftjoin('course_statuses', 'course_statuses.id', '=', 'courses.course_status_id')
            ->orderBy('courses.beginning', 'desc')
            ->get();
        return $profitabilities;
    }

    public static function getProfitability($id){
        $profitability = Profitability::select('profitabilities.*',
            'companies.name as company_name',
            DB::raw("CONCAT(training_actions.formative_action,' / ', courses.group, ' ', training_actions.name) as course"),
            'courses.beginning as beginning',
            'students.name as student_name',
            'students.surname as student_surname',
            DB::raw("YEAR(courses.beginning) as year"),
            DB::raw("CONCAT(students.name,' ',students.surname) as student"))
            ->leftjoin('companies', 'companies.id', '=', 'profitabilities.company_id')
            ->leftjoin('courses', 'courses.id', '=', 'profitabilities.course_id')
            ->leftjoin('students', 'students.id', '=', 'profitabilities.student_id')
            ->leftjoin('training_actions', 'training_actions.id', '=', 'courses.training_action_id')
            ->leftjoin('course_statuses', 'course_statuses.id', '=', 'courses.course_status_id')
            ->where('profitabilities.id', $id)
            ->first();
        return $profitability;
    }

    public static function createProfitability($data){
        $company = Company::find($data['company_id']);
        if ($data['is_bonus']){
            $profitability = Profitability::select('profitabilities.*')->leftjoin('registrations', 'registrations.profitability_id', '=', 'profitabilities.id')
                ->where('profitabilities.course_id', $data['course_id'])
                ->where('profitabilities.company_id', $data['company_id'])
                ->where('registrations.is_bonus', $data['is_bonus'])->first();
            if ($profitability){
                $advisor_commission = null;
                $collaborator_commission = null;
                $price = $profitability->price + GeneralHelpers::convertComa($data['price']);
                if ($profitability['advisor_percentage'] && $data['price']){
                    $advisor_commission = ($profitability['advisor_percentage'] / 100) * $price;
                }
                if ($profitability['collaborator_percentage'] && $data['price']){
                    $collaborator_commission = ($profitability['advisor_percentage'] / 100) * $price;
                }
                $prices = Profitability::getCalculateBenefits(GeneralHelpers::convertComa($price),
                    GeneralHelpers::convertComa($profitability['teacher']),
                    GeneralHelpers::convertComa($profitability['management']),
                    GeneralHelpers::convertComa($profitability['nebrija_title']),
                    GeneralHelpers::convertComa($profitability['discount']),
                    $collaborator_commission, $advisor_commission);
                $profitability->update([
                    'price' => $price,
                    'advisor_percentage' => $data['advisor_percentage'],
                    'collaborator_percentage' => $data['collaborator_percentage'],
                    'advisor_commission ' => $advisor_commission,
                    'collaborator_commission ' => $collaborator_commission,
                    'total' => $prices['total_cost'],
                    'benefits' => $prices['benefits'],
                    'number_students' => $profitability['number_students']-1
                ]);
            } else{
                $advisor_commission = null;
                $collaborator_commission = null;
                if ($data['advisor_percentage'] && $data['price']){
                    $advisor_commission = ($data['advisor_percentage'] / 100) * $data['price'];
                }
                if ($data['collaborator_percentage'] && $data['price']){
                    $collaborator_commission = ($data['advisor_percentage'] / 100) * $data['price'];
                }
                $profitability = Profitability::create([
                    'course_id' =>$data['course_id'],
                    'company_id' => $data['company_id'],
                    'price' => $data['price'],
                    'advisor_percentage' => $data['advisor_percentage'],
                    'collaborator_percentage' => $data['collaborator_percentage'],
                    'advisor_commission ' => $advisor_commission,
                    'collaborator_commission ' => $collaborator_commission,
                    'number_students' => 1
                ]);
            }
        } else{
            $advisor_commission = null;
            $collaborator_commission = null;
            if ($data['advisor_percentage'] && $data['price']){
                $advisor_commission = ($data['advisor_percentage'] / 100) * $data['price'];
            }
            if ($data['collaborator_percentage'] && $data['price']){
                $collaborator_commission = ($data['advisor_percentage'] / 100) * $data['price'];
            }
            $profitability = Profitability::create([
                'course_id' =>$data['course_id'],
                'company_id' => $data['company_id'],
                'student_id' => $data['student_id'],
                'price' => $data['price'],
                'advisor_percentage' => $data['advisor_percentage'],
                'collaborator_percentage' => $data['collaborator_percentage'],
                'advisor_commission ' => $advisor_commission,
                'collaborator_commission ' => $collaborator_commission,
                'number_students' => 1
            ]);
        }
        return $profitability;
    }

    public static function updateProfitability($id, $data){
        $advisor_commission = 0;
        $collaborator_commission = 0;
        if ($data['advisor_percentage'] && $data['price']){
            $advisor_commission = ($data['advisor_percentage'] / 100) * $data['price'];
        }
        if ($data['collaborator_percentage'] && $data['price']){
            $collaborator_commission = ($data['collaborator_percentage'] / 100) * $data['price'];
        }
        $profitability = Profitability::find($id);
        $prices = Profitability::getCalculateBenefits(GeneralHelpers::convertComa($data['price']), GeneralHelpers::convertComa($data['teacher']),
            GeneralHelpers::convertComa($data['management']), GeneralHelpers::convertComa($data['nebrija_title']), GeneralHelpers::convertComa($data['discount']), $collaborator_commission, $advisor_commission);
        $profitability->update([
            'price' => GeneralHelpers::convertComa($data['price']),
            'license' => $data['license'],
            'teacher' => GeneralHelpers::convertComa($data['teacher']),
            'management' => GeneralHelpers::convertComa($data['management']),
            'nebrija_title' => GeneralHelpers::convertComa($data['nebrija_title']),
            'discount' => GeneralHelpers::convertComa($data['discount']),
            'collaborator_commission' => GeneralHelpers::convertComa($collaborator_commission),
            'advisor_commission' => GeneralHelpers::convertComa($advisor_commission),
            'advisor_percentage' => $data['advisor_percentage'] ? $data['advisor_percentage'] : 0,
            'collaborator_percentage' => $data['collaborator_percentage'] ? $data['collaborator_percentage'] : 0,
            'total' => $prices['total_cost'],
            'benefits' => $prices['benefits'],
            'observations' => $data['observations']
        ]);
        return $profitability;
    }

    public static function getProfitabilityYear($year){
        $profitabilities = Profitability::whereYear('created_at', $year)->get();
        $total = 0;
        foreach ($profitabilities as $profitability){
            $total += $profitability['benefits'];
        }
        return $total;
    }

    public static function getBenefitsPerMonth($year){
        $total_months = [];
        for($i = 1; $i <= 12; $i++) {
            $total = 0;
            $profitabilities = Profitability::whereYear('created_at', $year)
                ->whereMonth('created_at', $i)->get();
            foreach ($profitabilities as $profitability){
                $total += $profitability['benefits'];
            }
            $total_months[] = $total;
        }
        return $total_months;
    }

    public static function getExpensesPerMonth($year){
        $total_months = [];
        for($i = 1; $i <= 12; $i++) {
            $total = 0;
            $profitabilities = Profitability::whereYear('created_at', $year)
                ->whereMonth('created_at', $i)->get();
            foreach ($profitabilities as $profitability){
                $total += $profitability['total'];
            }
            $total_months[] = -$total;
        }
        return $total_months;
    }

    public static function getCalculateBenefits($price, $teacher, $management, $nebrija_title, $discount, $collaborator_commission, $advisor_commission){
        $total_cost = $teacher + $management + $nebrija_title + $collaborator_commission + $advisor_commission;
        $benefits = $price - $teacher - $management - $nebrija_title - $discount - $collaborator_commission - $advisor_commission;

        return [
            'total_cost' => $total_cost,
            'benefits' => $benefits
        ];
    }

    public static function getProfitCSV($course = null, $company = null, $status = null){
        $profits = Profitability::select('profitabilities.*',
            'companies.name as company',
            DB::raw("CONCAT(training_actions.formative_action,' / ', courses.group, ' ', training_actions.name) as course"),
            'courses.beginning as beginning',
            'students.name as student_name',
            'students.surname as student_surname',
            DB::raw("CONCAT(profitabilities.price, ' €')"),
            DB::raw("YEAR(courses.beginning) as year"),
            DB::raw("CONCAT(students.name,' ',students.surname) as student"),
            DB::raw("CONCAT(teacher, ' €')"),
            DB::raw("CONCAT(management, ' €')"),
            DB::raw("CONCAT(nebrija_title, ' €')"),
            DB::raw("CONCAT(discount, ' €')"),
            DB::raw("CONCAT(collaborator_commission, ' €')"),
            DB::raw("CONCAT(advisor_commission, ' €')"),
            DB::raw("CONCAT(total, ' €')"),
            DB::raw('CONCAT(benefits, " €")'),
            DB::raw("CONCAT(ROUND((benefits*benefits)/profitabilities.price, 2), ' €') as rentabilidad"))
            ->leftjoin('companies', 'companies.id', '=', 'profitabilities.company_id')
            ->leftjoin('courses', 'courses.id', '=', 'profitabilities.course_id')
            ->leftjoin('students', 'students.id', '=', 'profitabilities.student_id')
            ->leftjoin('training_actions', 'training_actions.id', '=', 'courses.training_action_id')
            ->leftjoin('course_statuses', 'course_statuses.id', '=', 'courses.course_status_id');

        if ($course) {
            $profits = $profits->where('courses.name', 'like', '%'.$course.'%');
        }
        if ($company) {
            $profits = $profits->where('companies.name', 'like', '%'.$company.'&');
        }
        if ($status) {
            $profits = $profits->where('course_statuses.name', 'like', '%'.$status.'%');
        }
        $profits = $profits->orderBy('courses.beginning', 'desc')->get();

        $data = [];
        foreach ($profits as $profit) {
            $element = [
                'Curso' => $profit['course'],
                'Año' => $profit['year'],
                'Empresa' => $profit['company'],
                'Alumnos' => $profit['student'],
                'Precio' => $profit['price'],
                'Licencia' => $profit['license'],
                'Docente' => $profit['teacher'],
                'Gestión' => $profit['management'],
                'Titulo Nebrija' => $profit['nebrija_title'],
                'Descuento' => $profit['discount'],
                'Comisión Colaborador' => $profit['collaborator_commission'],
                'Comisión Asesoría' => $profit['advisor_commission'],
                'Total' => $profit['total'],
                'Beneficio' => $profit['benefits'],
                'Rentabilidad' => $profit['rentabilidad']
            ];
            $data[] = $element;
        }
        return $data;
    }

}
