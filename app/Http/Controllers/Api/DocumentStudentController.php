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
use App\Models\TrainingContractElement; 
use App\Models\TrainingContractBonus;
use App\Models\Province;
use Illuminate\Support\Facades\Date;

class DocumentStudentController extends BaseController
{
    private $documentStudentService;

    public function __construct(DocumentStudentService $documentStudentService)
    {
        $this->documentStudentService = $documentStudentService;
    }

    /**
     * Obtenemos los documentos
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Creamos un documento
     * @param TrainingActionRequests $request
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Obtenemos la acción formativa
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Eliminar acciones formativas
     * @param $id
     * @return \Illuminate\Http\JsonResponse|void
     */
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
                'name' => $document->name.'_'.$student->name.'_'.$student->surname,
                'document_name' => 'pdf/'.$document->name.'_'.$student->name.'_'.$student->surname.'.pdf'
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
        } catch(Exception $e) {
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

    // Obtenemos el documento del alumno
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

        // Obtenemos el estudiante
        $student = Student::find($documentStudent->student_id);
        Log::info('Student found with id: ' . $documentStudent->student_id);

        // Cargamos la vista Blade con los datos del estudiante
        $occupation = Occupation::find($trainingContract->occupation_id); 
        $company = Company::find($trainingContract->company_id); 
        $trainingElements = TrainingContractElement::getTrainingContractElements($trainingContract->id);  
        $monthlyFormationHours = $trainingContract->calculateMonthlyFormationHours($trainingContract->id)->getData()->monthly_formation_hours;
        $bonus = TrainingContractBonus::getBonuses($trainingContract->id);

        $daysWeek = 0; 
        if($trainingContract->monday == 1) $daysWeek++; 
        if($trainingContract->tuesday == 1) $daysWeek++; 
        if($trainingContract->wednesday == 1) $daysWeek++; 
        if($trainingContract->thursday == 1) $daysWeek++; 
        if($trainingContract->friday == 1) $daysWeek++; 
        if($trainingContract->saturday == 1) $daysWeek++; 
        if($trainingContract->sunday == 1) $daysWeek++; 
        $fechaActual = Date::now()->format('d/m/Y');

        $pdf = PDF::loadView($viewName, ['student' => $student, 'occupation'=>$occupation, 
        'trainingContract' => $trainingContract, 
        'company'=>$company, 
        'elements' => $trainingElements, 
        'daysWeek' => $daysWeek, 
        'fechaActual' => $fechaActual,
        'monthlyFormationHours' => $monthlyFormationHours,
        'bonus' => $bonus, 
        'sumaHoras' => 0]);

        Log::info('PDF generated from view');

        // Guardamos el PDF en el sistema de archivos
        Storage::disk('public')->put($documentStudent->document_name, $pdf->output());
        Log::info('PDF saved to storage');

        // Devolvemos la URL para acceder al PDF guardado
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
            // Path to the existing PDF file
            $existingPdfPath = 'public/'.''.$documentStudent->document_name;
            if (Storage::exists($existingPdfPath)) {
                Storage::delete($existingPdfPath);
            }
            $image = $request->image;
            $document = Document::find($documentStudent->document_id);
            $pdf = PDF::loadView($document->blade, compact('image' ));

            Storage::disk('public')->put($documentStudent->document_name, $pdf->output());

            $documentStudent->update([
                'signed' => 1,
                'date_signed' => Carbon::now()->toDateTimeString()
            ]);

            // Optionally, you can return the modified PDF for download
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
        $trainingContract->load('provider');
        $occupation = Occupation::find($trainingContract->occupation_id); 
        $company = Company::find($trainingContract->company_id); 
        $trainingElements = TrainingContractElement::getTrainingContractElements($trainingContract->id);  
        $monthlyFormationHours = $trainingContract->calculateMonthlyFormationHours($trainingContract->id)->getData()->monthly_formation_hours;
        $bonus = TrainingContractBonus::getBonuses($trainingContract->id);
        $province = Province::find($trainingContract->province_id);
    
        $daysWeek = 0; 
        if($trainingContract->monday == 1) $daysWeek++; 
        if($trainingContract->tuesday == 1) $daysWeek++; 
        if($trainingContract->wednesday == 1) $daysWeek++; 
        if($trainingContract->thursday == 1) $daysWeek++; 
        if($trainingContract->friday == 1) $daysWeek++; 
        if($trainingContract->saturday == 1) $daysWeek++; 
        if($trainingContract->sunday == 1) $daysWeek++; 
        $fechaActual = Date::now()->format('d/m/Y');
    
        $pdf = PDF::loadView($viewName, ['occupation'=>$occupation, 
        'trainingContract' => $trainingContract, 
        'company'=>$company, 
        'elements' => $trainingElements, 
        'daysWeek' => $daysWeek, 
        'fechaActual' => $fechaActual,
        'monthlyFormationHours' => $monthlyFormationHours,
        'bonus' => $bonus, 
        'sumaHoras' => 0, 
        'province' => $province]);

        $pdf->setPaper('a4', $orientation);
        
        // Devolvemos el PDF como una respuesta de descarga
        return $pdf->download('test.pdf');
    }
}
