<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Advisor extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name','company_id','irpf','commission','contact_1','contact_2','contact_3', 'nif', 'company_type_id', 'company_activity_id', 'email', 'telephone', 'legal_representative', 'dni_legal_representative', 'cnae_id', 'iban', 'sepa', 'b2b', 'address', 'post_code', 'population_id', 'province_id', 'population', 'active', 'collaborator_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function companies()
    {
        return $this->hasMany('App\Models\Company', 'advisor_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function company()
    {
        return $this->hasOne('App\Models\Company', 'id', 'company_id');
    }

    public static function getAdvisors(){
        $advisors = Advisor::select('advisors.*', 'company_types.name as type', 'company_activities.name as activity', 'cnaes.name as cnae',
            'provinces.name as province', 'advisors.id as value', 'advisors.name as label', 'companies.quote as quote', 'companies.average_template as average_template', 'users.id as collaborator_id',
            DB::raw("CONCAT(users.name,' ',users.surname) as collaborator"))
            ->leftjoin('company_types', 'company_types.id', '=', 'advisors.company_type_id')
            ->leftjoin('company_activities', 'company_activities.id', '=', 'advisors.company_activity_id')
            ->leftjoin('cnaes', 'cnaes.id', '=', 'advisors.cnae_id')
            ->leftjoin('provinces', 'provinces.id', '=', 'advisors.province_id')
            ->leftjoin('companies', 'companies.id', '=', 'advisors.company_id')
            ->leftjoin('users', 'users.id', '=', 'advisors.collaborator_id')
            ->orderBy('advisors.name', 'desc')
            ->get();
        foreach ($advisors as $advisor) {
            $company = Company::where('advisor_id', $advisor->id)->first();
            if ($company) {
                $advisor['used'] = true;
            } else {
                $advisor['used'] = false;
            }
            $company_info = Company::select('companies.*', 'advisors.name as advisor')
                ->leftjoin('advisors', 'advisors.id', '=', 'companies.advisor_id')
                ->where('companies.id', $advisor->company_id)->first();
            $advisor['advisor_id'] = $company_info->advisor_id;
            $advisor['advisor'] = $company_info->advisor;
        }
        return $advisors;
    }

    public static function getAdvisor($id){
        $advisor = Advisor::select('advisors.*', 'company_types.name as type', 'company_activities.name as activity', 'cnaes.name as cnae',
            'provinces.name as province', 'advisors.id as value', 'advisors.name as label', 'companies.quote as quote', 'companies.average_template as average_template', 'users.id as collaborator_id',
            DB::raw("CONCAT(users.name,' ',users.surname) as collaborator"))
            ->leftjoin('company_types', 'company_types.id', '=', 'advisors.company_type_id')
            ->leftjoin('company_activities', 'company_activities.id', '=', 'advisors.company_activity_id')
            ->leftjoin('cnaes', 'cnaes.id', '=', 'advisors.cnae_id')
            ->leftjoin('provinces', 'provinces.id', '=', 'advisors.province_id')->orderBy('advisors.name', 'desc')
            ->leftjoin('companies', 'companies.id', '=', 'advisors.company_id')
            ->leftjoin('users', 'users.id', '=', 'advisors.collaborator_id')
            ->where('advisors.id', $id)
            ->first();
        $company = Company::where('advisor_id', $advisor->id)->first();
        if ($company) {
            $advisor['used'] = true;
        } else {
            $advisor['used'] = false;
        }
        $company_info = Company::select('companies.*', 'advisors.name as advisor')
            ->leftjoin('advisors', 'advisors.id', '=', 'companies.advisor_id')
            ->where('companies.id', $advisor->company_id)->first();
        $advisor['advisor_id'] = $company_info->advisor_id;
        $advisor['advisor'] = $company_info->advisor;
        return $advisor;
    }

    public static function createAdvisor($data){
        $advisor = Advisor::create([
            'name' => $data['name'],
            'company_id' => $data['company_id'],
            'irpf' => $data['irpf'],
            'commission' => $data['commission'],
            'contact_1' => $data['contact_1'],
            'contact_2' => $data['contact_2'],
            'contact_3' => $data['contact_3'],
            'nif' => $data['nif'],
            'company_type_id' => $data['company_type_id'],
            'company_activity_id' => $data['company_activity_id'],
            'email' => $data['email'],
            'telephone' => $data['telephone'],
            'legal_representative' => $data['legal_representative'],
            'dni_legal_representative' => $data['dni_legal_representative'],
            'cnae_id' => $data['cnae_id'],
            'iban' => $data['iban'],
            'sepa' => $data['sepa'],
            'b2b' => $data['b2b'],
            'address' => $data['address'],
            'post_code' => $data['post_code'],
            'province_id' => $data['province_id'],
            'population' => $data['population'],
            'collaborator_id' => $data['collaborator_id'],
            'active' => $data['active']
        ]);

        return $advisor;
    }

    public static function updateAdvisor($id, $data){
        $advisor = Advisor::find($id);
        $advisor->update([
            'name' => $data['name'],
            'company_id' => $data['company_id'],
            'irpf' => $data['irpf'],
            'commission' => $data['commission'],
            'contact_1' => $data['contact_1'],
            'contact_2' => $data['contact_2'],
            'contact_3' => $data['contact_3'],
            'nif' => $data['nif'],
            'company_type_id' => $data['company_type_id'],
            'company_activity_id' => $data['company_activity_id'],
            'email' => $data['email'],
            'telephone' => $data['telephone'],
            'legal_representative' => $data['legal_representative'],
            'dni_legal_representative' => $data['dni_legal_representative'],
            'cnae_id' => $data['cnae_id'],
            'iban' => $data['iban'],
            'sepa' => $data['sepa'],
            'b2b' => $data['b2b'],
            'address' => $data['address'],
            'post_code' => $data['post_code'],
            'province_id' => $data['province_id'],
            'population' => $data['population'],
            'collaborator_id' => $data['collaborator_id'],
            'active' => $data['active']
        ]);

        return $advisor;
    }

    public static function updateAdvisorCompany($id, $data){
        $advisor = Advisor::find($id);
        $advisor->update([
            'name' => $data['name'],
            'company_id' => $data['company_id'],
            'nif' => $data['nif'],
            'company_type_id' => $data['company_type_id'],
            'company_activity_id' => $data['company_activity_id'],
            'email' => $data['email'],
            'telephone' => $data['telephone'],
            'legal_representative' => $data['legal_representative'],
            'dni_legal_representative' => $data['dni_legal_representative'],
            'cnae_id' => $data['cnae_id'],
            'iban' => $data['iban'],
            'sepa' => $data['sepa'],
            'b2b' => $data['b2b'],
            'address' => $data['address'],
            'post_code' => $data['post_code'],
            'province_id' => $data['province_id'],
            'population' => $data['population'],
            'active' => $data['active'],
        ]);
        return $advisor;
    }

    public static function convertAdvisor($id){
        if ($id) {
            $record = Company::find($id);
            $advisor = Advisor::create([
                'name' => $record['name'],
                'company_id' => $id,
                'nif' => $record['nif'],
                'company_type_id' => $record['company_type_id'],
                'company_activity_id' => $record['company_activity_id'],
                'email' => $record['email'],
                'telephone' => $record['telephone'],
                'legal_representative' => $record['legal_representative'],
                'dni_legal_representative' => $record['dni_legal_representative'],
                'cnae_id' => $record['cnae_id'],
                'iban' => $record['iban'],
                'sepa' => $record['sepa'],
                'b2b' => $record['b2b'],
                'address' => $record['address'],
                'post_code' => $record['post_code'],
                'province_id' => $record['province_id'],
                'population' => $record['population'],
                'active' => $record['active'],
            ]);
            return $advisor;
        }
    }

    public static function findNif($nif, $id = null){
        $advisor = Advisor::where('nif', $nif);
        if ($id){
            $advisor = $advisor->where('id', '!=', $id);
        }
        $advisor = $advisor->first();
        return $advisor;
    }

    public static function getAdvisorCSV($name = null, $nif = null, $type = null, $activity = null, $province = null){
        $advisors = Advisor::select('advisors.*', 'company_types.name as type', 'company_activities.name as activity', 'cnaes.name as cnae',
            'provinces.name as province', 'advisors.id as value', 'advisors.name as label', 'companies.quote as quote', 'companies.average_template as average_template', 'users.id as collaborator_id',
            DB::raw("CONCAT(users.name,' ',users.surname) as collaborator"))
            ->leftjoin('company_types', 'company_types.id', '=', 'advisors.company_type_id')
            ->leftjoin('company_activities', 'company_activities.id', '=', 'advisors.company_activity_id')
            ->leftjoin('cnaes', 'cnaes.id', '=', 'advisors.cnae_id')
            ->leftjoin('provinces', 'provinces.id', '=', 'advisors.province_id')->orderBy('advisors.name', 'desc')
            ->leftjoin('companies', 'companies.id', '=', 'advisors.company_id')
            ->leftjoin('users', 'users.id', '=', 'advisors.collaborator_id');

        if ($name) {
            $advisors = $advisors->where('advisors.name', 'like', '%'.$name.'%');
        }
        if ($nif) {
            $advisors = $advisors->where('advisors.nif', 'like', '%'.$nif.'&');
        }
        if ($type) {
            $advisors = $advisors->where('company_types.name', 'like', '%'.$type.'%');
        }
        if ($activity) {
            $advisors = $advisors->where('company_activities.name', 'like', '%'.$activity.'%');
        }
        if ($province) {
            $advisors = $advisors->where('provinces.name', 'like', '%'.$province.'%');
        }

        $advisors = $advisors->orderBy('advisors.name', 'desc')->get();

        $data = [];
        foreach ($advisors as $advisor) {
            $status = $advisor['potential'] == 1 ? 'Potential' : ($advisor['active'] == 0 ? 'Inactivo' : 'Active');
            $element = [
                'Nombre' => $advisor['name'],
                'CIF' => $advisor['nif'],
                'Tipo empresa' => $advisor['type'],
                'Actividad empresa' => $advisor['activity'],
                'Correo' => $advisor['email'],
                'Teléfono' => $advisor['telephone'],
                'Representante legal' => $advisor['legal_representative'],
                'Dni representante legal' => $advisor['dni_legal_representative'],
                'IRPF' => $advisor['irpf'],
                'Commisiones' => $advisor['commission'],
                'Contacto 1' => $advisor['contact_1'],
                'Contacto 2' => $advisor['contact_2'],
                'Contacto 3' => $advisor['contact_3'],
                'C. cotización' => $advisor['quote'],
                'Colaborador' => $advisor['collaborator'],
                'CNAE' => $advisor['cnae'],
                'Plantilla media' => $advisor['average_template'],
                'Iban' => $advisor['iban'],
                'Sepa' => $advisor['sepa'],
                'B2B' => $advisor['b2b'],
                'Dirección' => $advisor['address'],
                'Código postal' => $advisor['post_code'],
                'Provincia' => $advisor['province'],
                'Población' => $advisor['population'],
                'Asesoría' => $advisor['advisor'],
                'Estado' => $status
            ];
            $data[] = $element;
        }
        return $data;
    }

}
