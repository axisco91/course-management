<?php

namespace App\Console\Commands;

use App\Models\Advisor;
use App\Models\AdvisorCommission;
use App\Models\Bill;
use App\Models\Center;
use App\Models\Certification;
use App\Models\CertificationElement;
use App\Models\Chore;
use App\Models\CommissionType;
use App\Models\Company;
use App\Models\CompanyIncidence;
use App\Models\CompanyObservation;
use App\Models\Course;
use App\Models\Credit;
use App\Models\Document;
use App\Models\DocumentStudent;
use App\Models\DocumentType;
use App\Models\ExamTutorial;
use App\Models\ExcludedDay;
use App\Models\ExcludedDaysProvince;
use App\Models\Liquidation;
use App\Models\Module;
use App\Models\PotentialCompany;
use App\Models\PotentialCompanyObservation;
use App\Models\PotentialStudent;
use App\Models\Profitability;
use App\Models\Provider;
use App\Models\Registration;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Tracing;
use App\Models\TracingCommunication;
use App\Models\TrainingAction;
use App\Models\TrainingContract;
use App\Models\TrainingContractBill;
use App\Models\TrainingContractBonus;
use App\Models\TrainingContractElement;
use App\Models\TrainingContractFestival;
use App\Models\TrainingContractIncidence;
use App\Models\TrainingContractSeries;
use App\Models\TrainingContractsExcludedDay;
use App\Models\TrainingUnit;
use App\Models\TrainingUnitsModule;
use App\Models\User;
use App\Models\UserCommission;
use App\Models\UserCommissionType;
use App\Models\WebPlatform;
use Illuminate\Console\Command;

class updateCompanyId extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'updateCompanyId';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to update course status';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $advisorCommissions = AdvisorCommission::all();

        foreach ($advisorCommissions as $advisorCommission) {
            $advisorCommission->update([
                'main_company_id' => 1
            ]);
        }

        $advisors = Advisor::all();

        foreach ($advisors as $advisor) {
            $advisor->update([
                'main_company_id' => 1
            ]);
        }

        $billings = Bill::all();

        foreach ($billings as $billing) {
            $billing->update([
                'main_company_id' => 1
            ]);
        }

        $centers = Center::all();

        foreach ($centers as $center) {
            $center->update([
                'main_company_id' => 1
            ]);
        }

        $certificationElements = CertificationElement::all();

        foreach ($certificationElements as $certificationElement) {
            $certificationElement->update([
                'main_company_id' => 1
            ]);
        }

        $certifications = Certification::all();

        foreach ($certifications as $certification) {
            $certification->update([
                'main_company_id' => 1
            ]);
        }

        $chores = Chore::all();

        foreach ($chores as $chore) {
            $chore->update([
                'main_company_id' => 1
            ]);
        }

        $commissionTypes = CommissionType::all();

        foreach ($commissionTypes as $commissionType) {
            $commissionType->update([
                'main_company_id' => 1
            ]);
        }

        $companies = Company::all();

        foreach ($companies as $company) {
            $company->update([
                'main_company_id' => 1
            ]);
        }

        $companyIncidences = CompanyIncidence::all();

        foreach ($companyIncidences as $companyIncidence) {
            $companyIncidence->update([
                'main_company_id' => 1
            ]);
        }

        $companyObservations = CompanyObservation::all();

        foreach ($companyObservations as $companyObservation) {
            $companyObservation->update([
                'main_company_id' => 1
            ]);
        }

        $courses = Course::all();

        foreach ($courses as $course) {
            $course->update([
                'main_company_id' => 1
            ]);
        }

        $credits = Credit::all();

        foreach ($credits as $credit) {
            $credit->update([
                'main_company_id' => 1
            ]);
        }

        $documentStudents = DocumentStudent::all();
        foreach ($documentStudents as $documentStudent) {
            $documentStudent->update([
                'main_company_id' => 1
            ]);
        }

        $documentTypes = DocumentType::all();
        foreach ($documentTypes as $documentType) {
            $documentType->update([
                'main_company_id' => 1
            ]);
        }

        $documents = Document::all();
        foreach ($documents as $document) {
            $document->update([
                'main_company_id' => 1
            ]);
        }

        $exams = ExamTutorial::all();
        foreach ($exams as $exam) {
            $exam->update([
                'main_company_id' => 1
            ]);
        }

       /* $excludedDays = ExcludedDay::all();
        foreach ($excludedDays as $excludedDay) {
            $excludedDay->update([
                'main_company_id' => 1
            ]);
        }*/

       /* $excludedDayProvinces = ExcludedDaysProvince::all();
        foreach ($excludedDayProvinces as $excludedDayProvince) {
            $excludedDayProvince->update([
                'main_company_id' => 1
            ]);
        }*/

        $liquidations = Liquidation::all();
        foreach ($liquidations as $liquidation) {
            $liquidation->update([
                'main_company_id' => 1
            ]);
        }

        $modules = Module::all();
        foreach ($modules as $module) {
            $module->update([
                'main_company_id' => 1
            ]);
        }

        $potentialCompanies = PotentialCompany::all();
        foreach ($potentialCompanies as $potentialCompany) {
            $potentialCompany->update([
                'main_company_id' => 1
            ]);
        }

        $potentialCompanyObservations = PotentialCompanyObservation::all();
        foreach ($potentialCompanyObservations as $potentialCompanyObservation) {
            $potentialCompanyObservation->update([
                'main_company_id' => 1
            ]);
        }

        $potentialStudents = PotentialStudent::all();
        foreach ($potentialStudents as $potentialStudent) {
            $potentialStudent->update([
                'main_company_id' => 1
            ]);
        }

        $profits = Profitability::all();
        foreach ($profits as $profit) {
            $profit->update([
                'main_company_id' => 1
            ]);
        }

        $providers = Provider::all();
        foreach ($providers as $provider) {
            $provider->update([
                'main_company_id' => 1
            ]);
        }

        $registrations = Registration::all();
        foreach ($registrations as $registration) {
            $registration->update([
                'main_company_id' => 1
            ]);
        }

        $students = Student::all();
        foreach ($students as $student) {
            $student->update([
                'main_company_id' => 1
            ]);
        }

        $teachers = Teacher::all();
        foreach ($teachers as $teacher) {
            $teacher->update([
                'main_company_id' => 1
            ]);
        }

        $tracingsCommunications = TracingCommunication::all();
        foreach ($tracingsCommunications as $tracingCommunication) {
            $tracingCommunication->update([
                'main_company_id' => 1
            ]);
        }

        $tracings = Tracing::all();
        foreach ($tracings as $tracing) {
            $tracing->update([
                'main_company_id' => 1
            ]);
        }

        $tracingsActions = TrainingAction::all();
        foreach ($tracingsActions as $tracingAction) {
            $tracingAction->update([
                'main_company_id' => 1
            ]);
        }

        $tracingsContractBills = TrainingContractBill::all();
        foreach ($tracingsContractBills as $tracingContractBill) {
            $tracingContractBill->update([
                'main_company_id' => 1
            ]);
        }

        $trainingContractBonuses = TrainingContractBonus::all();
        foreach ($trainingContractBonuses as $trainingContractBonus) {
            $trainingContractBonus->update([
                'main_company_id' => 1
            ]);
        }

        $trainingContractElements = TrainingContractElement::all();
        foreach ($trainingContractElements as $trainingContractElement) {
            $trainingContractElement->update([
                'main_company_id' => 1
            ]);
        }

        $trainingContractFestivals = TrainingContractFestival::all();
        foreach ($trainingContractFestivals as $trainingContractFestival) {
            $trainingContractFestival->update([
                'main_company_id' => 1
            ]);
        }

        $trainingContractIncidences = TrainingContractIncidence::all();
        foreach ($trainingContractIncidences as $trainingContractIncidence) {
            $trainingContractIncidence->update([
                'main_company_id' => 1
            ]);
        }

        $trainingContractSeries = TrainingContractSeries::all();
        foreach ($trainingContractSeries as $trainingContractSerie) {
            $trainingContractSerie->update([
                'main_company_id' => 1
            ]);
        }

        $trainingContracts = TrainingContract::all();
        foreach ($trainingContracts as $trainingContract) {
            $trainingContract->update([
                'main_company_id' => 1
            ]);
        }

        $trainingContractExcludedDays = TrainingContractsExcludedDay::all();
        foreach ($trainingContractExcludedDays as $trainingContractExcludedDay) {
            $trainingContractExcludedDay->update([
                'main_company_id' => 1
            ]);
        }

        $trainingUnits = TrainingUnit::all();
        foreach ($trainingUnits as $trainingUnit) {
            $trainingUnit->update([
                'main_company_id' => 1
            ]);
        }

        $trainingUnitModules = TrainingUnitsModule::all();
        foreach ($trainingUnitModules as $trainingUnitModule) {
            $trainingUnitModule->update([
                'main_company_id' => 1
            ]);
        }

        $userCommissionTypes = UserCommissionType::all();
        foreach ($userCommissionTypes as $userCommissionType) {
            $userCommissionType->update([
                'main_company_id' => 1
            ]);
        }

        $userCommissions = UserCommission::all();
        foreach ($userCommissions as $userCommission) {
            $userCommission->update([
                'main_company_id' => 1
            ]);
        }

        $users = User::all();
        foreach ($users as $user) {
            $user->update([
                'main_company_id' => 1
            ]);
        }

        $webPlatforms = WebPlatform::all();
        foreach ($webPlatforms as $webPlatform) {
            $webPlatform->update([
                'main_company_id' => 1
            ]);
        }

        return 0;
    }
}
