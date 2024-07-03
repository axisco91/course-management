<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\TrainingActionRequests;
use App\Mail\PotentialPrivateStudent as PotentialPrivateEmail;
use App\Mail\SignDocument;
use App\Models\Course;
use App\Models\Document;
use App\Models\DocumentStudent;
use App\Models\Student;
use App\Models\TrainingAction;
use App\Models\User;
use App\Services\DocumentService;
use App\Services\DocumentStudentService;
use App\Services\TrainingActionService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Mockery\Exception;
use Barryvdh\DomPDF\Facade\Pdf;
use setasign\Fpdi\Fpdi;
use Illuminate\Support\Facades\Log;
use App\Models\TrainingContract;
use App\Models\Occupation;
use App\Models\Company;
use App\Models\CompanyActivity;
use App\Models\LevelStudy;
use App\Models\Province;
use App\Models\TrainingContractElement;
use App\Models\WebPlatform;
use App\Models\ApplicableAgreement;
use App\Models\AgreementType;
use App\Models\TrainingContractBonus;
use App\Models\CompanyType;
use App\Models\TrainingContractSeries;
use App\Models\TrainingContractBill;
use ZipArchive;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class DocumentStudentController extends BaseController
{
    private $documentStudentService;

    public function __construct(DocumentStudentService $documentStudentService)
    {
        $this->documentStudentService = $documentStudentService;
    }

    public function index(Request $request) {
        try {
            $contractId = $request->training_contract_id;
            $documents = Document::select('documents.*', 'document_students.signed as signed', 'document_students.date_signed as date_signed', 'document_students.key as student_key')
                ->leftjoin('document_students', function ($join) use ($contractId) {
                    $join->on('document_students.document_id', '=', 'documents.id')
                        ->where('document_students.training_contract_id', '=', $contractId);
                })
                ->join('document_types', 'document_types.id', '=', 'documents.document_type_id')
                ->where('document_types.name', 'Contratos')
                ->get();
            return $documents;
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function store(TrainingActionRequests $request){
        try {
            $data = $request->all();
            $element = $this->trainingActionService->create($data);
            $trainingAction = TrainingAction::trainingAction()
                ->where('training_actions.id', $element->id)
                ->first();
            $course = Course::where('training_action_id', $trainingAction->id)->first();
            if ($course) {
                $trainingAction['used'] = true;
            } else {
                $trainingAction['used'] = false;
            }
            return response()->json([
                'status' => 200,
                'training_action' => $trainingAction
            ]);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update($id, TrainingActionRequests $request){
        try {
            $data = $request->all();
            $trainingAction = TrainingAction::find($id);
            $element = $this->trainingActionService->update($trainingAction, $data);
            $trainingAction = TrainingAction::trainingAction()
                ->where('training_actions.id', $element->id)
                ->first();
            $course = Course::where('training_action_id', $trainingAction->id)->first();
            if ($course) {
                $trainingAction['used'] = true;
            } else {
                $trainingAction['used'] = false;
            }
            return response()->json([
                'status' => 200,
                'training_action' => $trainingAction
            ]);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function show($id){
        $trainingAction = TrainingAction::trainingAction()
            ->where('training_actions.id', $id)
            ->first();
        $course = Course::where('training_action_id', $trainingAction->id)->first();
        if ($course) {
            $trainingAction['used'] = true;
        } else {
            $trainingAction['used'] = false;
        }
        if ($trainingAction) {
            return response()->json([
                'status' => 200,
                'training_action' => $trainingAction
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Acción Formativa no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                TrainingAction::destroy($id);
                return response()->json([
                    'status' => 200
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 400,
                    'message' => $e->getMessage()
                ]);
            }
        }
    }

    public function send(Request $request) {
        Log::info('send method called with request: ', $request->all());

        $student = Student::where('id', $request->student_id)->first();
        if ($student) {
            Log::info('Student found with id: ' . $request->student_id);

            $documentStudent = DocumentStudent::where('document_id', $request->document_id)
                ->where('student_id', $request->student_id);

            if ($request->training_contract_id) {
                $documentStudent = $documentStudent->where('training_contract_id', $request->training_contract_id);
            }

            $documentStudent = $documentStudent->first();
            if ($documentStudent) {
                Log::info('DocumentStudent found with id: ' . $documentStudent->id);
            } else {
                Log::info('No DocumentStudent found, creating new one');

                $document = Document::where('id', $request->document_id)->first();
                if ($document) {
                    Log::info('Document found with id: ' . $request->document_id);
                } else {
                    Log::error('No Document found with id: ' . $request->document_id);
                    return response()->json([
                        'status' => 400,
                        'message' => 'No Document found with id: ' . $request->document_id
                    ]);
                }

                $data = [
                    'document_id' => $document->id,
                    'student_id' => $request->student_id,
                    'training_contract_id' => isset($request->training_contract_id) ? $request->training_contract_id : null,
                    'name' => $document->name . '_' . $student->name . '_' . $student->surname,
                    'document_name' => 'pdf/' . $document->name . '_' . $student->name . '_' . $student->surname . '.pdf'
                ];
                $documentStudent = $this->documentStudentService->create($data);
            }
            try {
                Mail::getSwiftMailer()
                    ->getTransport()
                    ->setUsername('zona@avzformacion.com')
                    ->setPassword('Avz.2021');
                Mail::to($student->email)->send(new SignDocument($documentStudent->name, $documentStudent->key));
                Log::info('Mail sent to: ' . $student->email);
                return response()->json([
                    'status' => 200
                ]);
            } catch (Exception $e) {
                Log::error('Error sending mail: ', $e->getMessage());
                return response()->json([
                    'status' => 400,
                    'message' => $e->getMessage()
                ]);
            }
        }
        Log::error('No Student found with id: ' . $request->student_id);
        return response()->json([
            'status' => 400,
            'message' => 'Error al enviar correo'
        ]);
    }

    public function studentViewPdf($key, $viewName, TrainingContract $trainingContract) {
        Log::info('studentViewPdf method called with key: ' . $key);

        $documentStudent = DocumentStudent::where('key', $key)->first();
        if ($documentStudent) {
            Log::info('DocumentStudent found with key: ' . $key);

            if ($documentStudent->date_signed) {
                Log::info('DocumentStudent already signed');
                return response()->json([
                    'status' => 200,
                    'pdfUrl' => url('storage/' . $documentStudent->document_name),
                    'signed' => true
                ]);
            }

            $student = Student::find($documentStudent->student_id);
            Log::info('Student found with id: ' . $documentStudent->student_id);

            $trainingContract->load('provider');
            $occupation = Occupation::find($trainingContract->occupation_id);
            $company = Company::find($trainingContract->company_id);
            $applicableAgreement = ApplicableAgreement::find($trainingContract->applicable_agreement_id);
            $agreementType = AgreementType::find($applicableAgreement->agreement_type_id);
            $student = Student::find($trainingContract->student_id);
            $student->levelStudy = LevelStudy::find($student->level_study_id);
            $company->companyActivity = CompanyActivity::find($company->company_activity_id);
            $province = Province::find($trainingContract->province_id);
            $trainingElements = TrainingContractElement::getTrainingContractElements($trainingContract->id);
            $monthlyFormationHours = $trainingContract->calculateMonthlyFormationHours($trainingContract->id)->getData()->monthly_formation_hours;
            $bonus = TrainingContractBonus::getBonuses($trainingContract->id);

            $daysWeek = 0;
            if ($trainingContract->monday == 1) $daysWeek++;
            if ($trainingContract->tuesday == 1) $daysWeek++;
            if ($trainingContract->wednesday == 1) $daysWeek++;
            if ($trainingContract->thursday == 1) $daysWeek++;
            if ($trainingContract->friday == 1) $daysWeek++;
            if ($trainingContract->saturday == 1) $daysWeek++;
            if ($trainingContract->sunday == 1) $daysWeek++;
            $fechaActual = Date::now()->format('d/m/Y');

            $pdf = PDF::loadView($viewName, [
                'occupation' => $occupation,
                'trainingContract' => $trainingContract,
                'company' => $company,
                'student' => $student,
                'ocupation' => $occupation,
                'province' => $province,
                'elements' => $trainingElements,
                'applicableAgreement' => $applicableAgreement,
                'agreementType' => $agreementType,
                'fechaActual' => $fechaActual,
                'bonus' => $bonus,
                'monthlyFormationHours' => $monthlyFormationHours,
                'sumaHoras' => 0
            ]);
            Log::info('PDF generated from view');

            Storage::disk('public')->put($documentStudent->document_name, $pdf->output());
            Log::info('PDF saved to storage');

            return response()->json([
                'status' => 200,
                'pdfUrl' => url('storage/' . $documentStudent->document_name),
            ]);
        } else {
            Log::info('No DocumentStudent found with key: ' . $key);
            return response()->json([
                'status' => 404,
                'message' => 'No existe documento'
            ]);
        }
    }

    public function signPdf(Request $request)
    {
        $documentStudent = DocumentStudent::where('key', $request->key)->first();
        if ($request->key) {
            $existingPdfPath = 'public/' . '' . $documentStudent->document_name;
            if (Storage::exists($existingPdfPath)) {
                Storage::delete($existingPdfPath);
            }
            $image = $request->image;
            $document = Document::find($documentStudent->document_id);
            $pdf = PDF::loadView($document->blade, compact('image'));

            Storage::disk('public')->put($documentStudent->document_name, $pdf->output());

            $documentStudent->update([
                'signed' => 1,
                'date_signed' => Carbon::now()->toDateTimeString()
            ]);

            return response()->json([
                'status' => 200,
                'pdfUrl' => url('storage/' . $documentStudent->document_name),
                'signed' => true
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No existe documento'
            ]);
        }
    }

    public function testPdf($viewName, TrainingContract $trainingContract, $orientation = 'portrait') {
        $trainingContract->load('provider', 'occupation', 'company', 'company.companyActivity', 'applicableAgreement.agreementType', 'student.levelStudy', 'province', 'trainingContractExcludedDays');
    
        $trainingElements = TrainingContractElement::getTrainingContractElements($trainingContract->id);
        $monthlyFormationHours = $trainingContract->calculateMonthlyFormationHours($trainingContract->id)->getData()->monthly_formation_hours;
        $bonus = TrainingContractBonus::getBonuses($trainingContract->id);
        $companyType = CompanyType::find($trainingContract->company->company_type_id);
    
        $dias = [];
        $daysWeek = 0;
        if ($trainingContract->monday == 1) {
            $daysWeek++;
            $dias[] = 'L';
        }
        if ($trainingContract->tuesday == 1) {
            $daysWeek++;
            $dias[] = 'M';
        }
        if ($trainingContract->wednesday == 1) {
            $daysWeek++;
            $dias[] = 'X';
        }
        if ($trainingContract->thursday == 1) {
            $daysWeek++;
            $dias[] = 'J';
        }
        if ($trainingContract->friday == 1) {
            $daysWeek++;
            $dias[] = 'V';
        }
        if ($trainingContract->saturday == 1) {
            $daysWeek++;
            $dias[] = 'S';
        }
        if ($trainingContract->sunday == 1) {
            $daysWeek++;
            $dias[] = 'D';
        }
        $fechaActual = Date::now()->format('d/m/Y');
    
        // Filtrar los días excluidos con excluded_day_type_id = 1 y agrupar por grupos consecutivos
        $excludedDays = $trainingContract->trainingContractExcludedDays->filter(function($day) {
            return $day->excluded_day_type_id == 1;
        })->groupBy(function($day) {
            return \Carbon\Carbon::parse($day->day)->format('Y-m');
        })->map(function($days) {
            return [
                'start_date' => $days->min('day'),
                'end_date' => $days->max('day')
            ];
        });
    
        ini_set('max_execution_time', 180); // PARA LOS CFA QUE SON MUY LARGOS, 60 segundos (tiempo por defecto) no es suficiente
    
        $htmlContent = view($viewName, [
            'trainingContract' => $trainingContract,
            'elements' => $trainingElements,
            'fechaActual' => $fechaActual,
            'bonus' => $bonus,
            'monthlyFormationHours' => $monthlyFormationHours,
            'sumaHoras' => 0,
            'dias' => $dias,
            'companyType' => $companyType,
            'daysWeek' => $daysWeek,
            'excludedDays' => $excludedDays // Pasar los días excluidos a la vista
        ])->render();
    
        $htmlContent = $this->adjustImagePaths($htmlContent);
    
        $pdf = PDF::loadHTML($htmlContent);
    
        $pdf->setPaper('a4', $orientation);
        return $pdf->download('test.pdf');
    }
    

    public function generatePdf($viewName, TrainingContractBill $trainingContractBill, $orientation = 'portrait') {
        if (is_null($trainingContractBill->number)) {
            DB::beginTransaction();
            try {
                $lastBill = TrainingContractBill::lockForUpdate()->orderBy('number', 'desc')->first();
                $number = $lastBill ? $lastBill->number + 1 : 1;
                $trainingContractBill->number = $number;
                $trainingContractBill->save();
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }
        }

        if ($trainingContractBill->invoiced == 0) {
            $trainingContractBill->invoiced = 1;
            $trainingContractBill->save();
        }

        Log::info('metodoLlamado' . $trainingContractBill);
        $trainingContractBill->load('provider');
        $trainingContract = TrainingContract::find($trainingContractBill->training_contract_id);
        $trainingContractSeries = TrainingContractSeries::find($trainingContractBill->series_id);
        $occupation = Occupation::find($trainingContract->occupation_id);
        $company = Company::find($trainingContract->company_id);
        $student = Student::find($trainingContract->student_id);
        $trainingContractBonus = TrainingContractBonus::find($trainingContractBill->training_contract_bonus_id);

        $pdf = PDF::loadView($viewName, [
            'occupation' => $occupation,
            'trainingContractBill' => $trainingContractBill,
            'trainingContract' => $trainingContract,
            'company' => $company,
            'student' => $student,
            'trainingContractSeries' => $trainingContractSeries,
            'trainingContractBonus' => $trainingContractBonus
        ]);

        $pdf->setPaper('a4', $orientation);

        return $pdf;
    }

    public function testPdfFactura($viewName, TrainingContractBill $trainingContractBill, $orientation = 'portrait') {
        $pdf = $this->generatePdf($viewName, $trainingContractBill, $orientation);

        return $pdf->download('test.pdf');
    }

    public function generateInvoices(Request $request) {
        ini_set('max_execution_time', 300); // Aumenta el tiempo máximo de ejecución si es necesario

        $zip = new ZipArchive;
        $zipFileName = tempnam(sys_get_temp_dir(), 'invoices') . '.zip';
        $zip->open($zipFileName, ZipArchive::CREATE);

        $billIds = $request->input('billIds');

        $perPage = 50; // Número de facturas a procesar por lote
        $page = 1;
        $updatedBills = []; // Almacenar facturas actualizadas

        do {
            $bills = TrainingContractBill::whereIn('id', $billIds)
                ->orderBy('year', 'asc')
                ->orderBy('month', 'asc')
                ->orderBy('training_contract_id', 'asc')
                ->skip(($page - 1) * $perPage)
                ->take($perPage)
                ->get();

            if ($bills->isEmpty()) {
                break;
            }

            DB::beginTransaction();
            try {
                $lastBill = TrainingContractBill::lockForUpdate()->orderBy('number', 'desc')->first();
                $number = $lastBill ? $lastBill->number + 1 : 1;

                foreach ($bills as $bill) {
                    if (is_null($bill->number)) {
                        $bill->number = $number++;
                        $bill->save();
                    }
                    if ($bill->invoiced == 0) {
                        $bill->invoiced = 1;
                        $bill->save();
                    }
                }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }

            foreach ($bills as $bill) {
                $bill->load(['company', 'training_contract.student']);
                $bill['company'] = $bill->company->name;
                $bill['student'] = $bill->training_contract->student->name . ' ' . $bill->training_contract->student->surname;

                switch ($bill['month']) {
                    case 1:
                        $bill['month_name'] = 'Enero';
                        break;
                    case 2:
                        $bill['month_name'] = 'Febrero';
                        break;
                    case 3:
                        $bill['month_name'] = 'Marzo';
                        break;
                    case 4:
                        $bill['month_name'] = 'Abril';
                        break;
                    case 5:
                        $bill['month_name'] = 'Mayo';
                        break;
                    case 6:
                        $bill['month_name'] = 'Junio';
                        break;
                    case 7:
                        $bill['month_name'] = 'Julio';
                        break;
                    case 8:
                        $bill['month_name'] = 'Agosto';
                        break;
                    case 9:
                        $bill['month_name'] = 'Septiembre';
                        break;
                    case 10:
                        $bill['month_name'] = 'Octubre';
                        break;
                    case 11:
                        $bill['month_name'] = 'Noviembre';
                        break;
                    case 12:
                        $bill['month_name'] = 'Diciembre';
                        break;
                }
                $updatedBills[] = $bill->toArray();
            }

            foreach ($bills as $bill) {
                $pdf = $this->generatePdf('documents.V&Rfactura', $bill);
                $pdfFileName = tempnam(sys_get_temp_dir(), 'invoice') . '.pdf';
                file_put_contents($pdfFileName, $pdf->output());

                $zip->addFile($pdfFileName, "factura_cfa_{$bill->id}.pdf");
            }

            $page++;
        } while (true);

        $zip->close();

        $zipContent = file_get_contents($zipFileName);
        $base64Zip = base64_encode($zipContent);

        return response()->json([
            'zipFile' => $base64Zip,
            'updatedBills' => $updatedBills
        ]);
    }

    public function adjustImagePaths($htmlContent) {
        $search = [
            'src="img-contrato/',
            'href="css/'
        ];

        $replace = [
            'src="' . public_path('img-contrato') . '/',
            'href="' . public_path('css') . '/'
        ];

        return str_replace($search, $replace, $htmlContent);
    }
}
