<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profitability extends Model
{
	use HasFactory;

    public $timestamps = true;

    protected $table = 'profitabilities';

    protected $fillable = ['course_id','company_id','student_id','price','license','teacher','management','nebrija_title','discount','collaborator_commission','advisor_commission','total','benefits','observations'];

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
    public function student()
    {
        return $this->hasOne('App\Models\Student', 'id', 'student_id');
    }

    public function getProfitabilities($keyWord){
        $profitabilities = Profitability::select('profitabilities.*', 'companies.name as company_name', 'courses.name as course_name',
            'students.name as student_name', 'students.surname as student_surname')
            ->leftjoin('companies', 'companies.id', '=', 'profitabilities.company_id')
            ->leftjoin('courses', 'courses.id', '=', 'profitabilities.course_id')
            ->leftjoin('students', 'students.id', '=', 'profitabilities.student_id')
            ->orWhere('courses.name', 'LIKE', $keyWord)
            ->orWhere('companies.name', 'LIKE', $keyWord)
            ->orWhere('students.name', 'LIKE', $keyWord)
            ->orWhere('students.surname', 'LIKE', $keyWord)
            ->orWhere('profitabilities.price', 'LIKE', $keyWord)
            ->orWhere('license', 'LIKE', $keyWord)
            ->orWhere('teacher', 'LIKE', $keyWord)
            ->orWhere('management', 'LIKE', $keyWord)
            ->orWhere('nebrija_title', 'LIKE', $keyWord)
            ->orWhere('discount', 'LIKE', $keyWord)
            ->orWhere('collaborator_commission', 'LIKE', $keyWord)
            ->orWhere('advisor_commission', 'LIKE', $keyWord)
            ->orWhere('total', 'LIKE', $keyWord)
            ->orWhere('benefits', 'LIKE', $keyWord)
            ->orWhere('observations', 'LIKE', $keyWord)
            ->paginate(10);
        return $profitabilities;
    }

    public function createProfitability($data){
        $profitability = Profitability::create([
            'course_id' =>$data['course_id'],
            'company_id' => $data['company_id'],
            'student_id' => $data['id'],
            'price' => $data['price'],
            'total' => $data['price'],
        ]);
        return $profitability;
    }

    public function updateProfitability($id, $data){
        $profitability = Profitability::find($id);
        $profitability->update([
            'price' => $data['price'],
            'license' => $data['license'],
            'teacher' => $data['teacher'],
            'management' => $data['management'],
            'nebrija_title' => $data['nebrija_title'],
            'discount' => $data['discount'],
            'collaborator_commission' => $data['collaborator_commission'],
            'advisor_commission' => $data['advisor_commission'],
            'total' => $data['total'],
            'benefits' => $data['benefits'],
            'observations' => $data['observations']
        ]);
        return $profitability;
    }

}
