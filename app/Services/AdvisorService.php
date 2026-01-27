<?php

namespace App\Services;

use App\Helpers\GeneralHelpers;
use App\Mail\SendAdvisorUser;
use App\Models\Advisor;
use App\Models\Company;
use App\Models\MainCompany;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class AdvisorService
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Función para crear una asesoría
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return Advisor::create([
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
            'active' => $data['active'],
            'main_company_id' => isset($data['main_company_id']) ?? null,
        ]);
    }

    /**
     * Función para editar una asesoría
     */
    public function update(Advisor $advisor, array $data) {
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

    /**
     * Convertir asesoría
     * @param Advisor $advisor
     * @param $id
     * @return mixed
     */
    public function convertAdvisor($id) {
        $record = Company::find($id);
        return Advisor::create([
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
            'main_company_id' => $record['main_company_id']
        ]);
    }

    /**
     * Modificar asesoría desde empresa
     * @param Advisor $advisor
     * @param array $data
     * @return Advisor
     */
    public function updateAdvisorCompany(Advisor $advisor, array $data){
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

    public function advisorUser(Advisor $advisor){
        try {
            if (!$advisor->user_id) {
                DB::beginTransaction();

                $email = explode(';', $advisor->email)[0] ?? $advisor->email;

                $role = Role::where('name', 'Asesoría')->first();

                $password = Str::random(8);

                $data = [
                    'name' => 'Asesoría',
                    'surname' => $advisor->name,
                    'username' => $advisor->nif,
                    'email' =>$email,
                    'password' => Hash::make($password),
                    'has_commission' => 1,
                    'commission' => $advisor->commission,
                    'active' => 1,
                    'teacher_id' => null,
                    'roles' => $role->id,
                    'advisor_id' => $advisor->id,
                    'default_password' => $password,
                ];

                $user = $this->userService->create($data);

                $advisor->update([
                    'user_id' => $user->id,
                ]);

                $username = GeneralHelpers::generalSettingValue( 'email');
                $emailPassword = GeneralHelpers::generalSettingValue( 'password');

                config([
                    'mail.mailers.smtp.username' => $username,
                    'mail.mailers.smtp.password' => $emailPassword,
                ]);

                Mail::mailer('smtp')
                    ->to($email)
                    ->send(new SendAdvisorUser($advisor->nif, $password));

                db::commit();
                return $advisor;
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function sendEmail(Advisor $advisor, $mainCompanyId){
        try {
            $user = User::find($advisor->user_id);

            if (!$user) {
                $this->advisorUser($advisor);
            }

            $username = GeneralHelpers::generalSettingValue( 'email');
            $emailPassword = GeneralHelpers::generalSettingValue( 'password');

            config([
                'mail.mailers.smtp.username' => $username,
                'mail.mailers.smtp.password' => $emailPassword,
            ]);

            $mainCompany = MainCompany::find($mainCompanyId);

            if ($mainCompany) {
                Mail::mailer('smtp')
                    ->to($user->email)
                    ->send(new SendAdvisorUser($user->username, $user->default_password, $mainCompany->url));
            }

            return $advisor;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
