<?php

namespace App\Http\Controllers\API;
use App\Models\Certification;
use App\Models\TrainingAction;
use App\Models\TrainingContract;
use App\Models\TrainingContractElement;
use App\Models\TrainingContractsExcludedDay;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TrainingContractController extends BaseController
{
    public function getTrainingContracts() {
        try {
            return TrainingContract::getTrainingContracts();
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function create(Request $request){
        $data = json_decode($request->getContent(), true);
        try {
            $contract = TrainingContract::createTrainingContract($data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'training_contract' => TrainingAction::getTrainingAction($contract->id)
        ]);
    }

    public function edit($id, Request $request){
        $data = json_decode($request->getContent(), true);
        try {
            $contract = TrainingContract::updateTrainingContract($id, $data);
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'error' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'training_contract' => TrainingContract::getTrainingContract($contract->id)
        ]);
    }

    public function getTrainingContract($id){
        $contract = TrainingContract::getTrainingContract($id);
        if ($contract) {
            return response()->json([
                'status' => 200,
                'training_contract' => $contract
            ]);
        }
        return response()->json([
            'status' => 400,
            'message' => 'Contrato no existe'
        ]);
    }

    public function destroy($id){
        if ($id) {
            try {
                TrainingContract::destroy($id);
                return response()->json([
                    'status' => 200
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 400,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    public function getCFANumber(){
        $training = TrainingContract::orderBy('id', 'desc')->first();
        $id = $training['id']+1;
        if ($id < 10) {
            $number_cfa = '000'.$id;
        }
        else if ($id < 100) {
            $number_cfa = '00'.$id;
        }
        else if ($id < 1000) {
            $number_cfa = '0'.$id;
        } else {
            $number_cfa = $id;
        }
        return response()->json([
            'number_cfa' => $number_cfa
        ]);
    }

    public function getSpecialties($id) {
        if ($id) {
            try {
                return TrainingAction::getSpecialties($id);
            } catch (\Exception $e) {
                return response()->json([
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    public function getCertifications($id) {
        if ($id) {
            try {
                return Certification::getCertificationsNotinTrainingContract($id);
            } catch (\Exception $e) {
                return response()->json([
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    public function countTrainingContracts() {
        return TrainingContract::count();
    }

    public function calculateHours($id) {
        $record = TrainingContract::findOrFail($id);
        $training_contract_certifications = TrainingContractElement::getTrainingContractElements($id);
        $cont_days = 0;
        $date = Carbon::parse($record->beginning_formation);
        $end_date = Carbon::parse($record->end_formation);
        $hours_days = 0;
        do {
            $excluded = TrainingContractsExcludedDay::nonWorkingDay($id, $date);
            if ($excluded != true){
                switch($date->dayOfWeek){
                    case 0:
                        if ($record->sunday == 1){
                            $cont_days++;
                        }
                        break;
                    case 1:
                        if ($record->monday == 1){
                            $cont_days++;
                        }
                        break;
                    case 2:
                        if ($record->tuesday == 1){
                            $cont_days++;
                        }
                        break;
                    case 3:
                        if ($record->wednesday == 1){
                            $cont_days++;
                        }
                        break;
                    case 4:
                        if ($record->thursday == 1){
                            $cont_days++;
                        }
                        break;
                    case 5:
                        if ($record->friday == 1){
                            $cont_days++;
                        }
                        break;
                    case 6:
                        if ($record->saturday == 1){
                            $cont_days++;
                        }
                        break;
                }
            }
            $date->addDay();
        } while($end_date->gt($date));
        if ($cont_days != 0){
            $hours_days = $record->total_hours / $cont_days;
            $hours_days = floor($hours_days * 100) / 100;
            $record->update([
                'total_days' => $cont_days,
                'daily_hours' => $hours_days
            ]);
            $total_days = $cont_days;
            $daily_hours = $hours_days;
        }
        foreach($training_contract_certifications as $training_element){
            if ($training_element === $training_contract_certifications[0]){
                $beginning = Carbon::parse($record->beginning_formation);
            }
            $training_element->update([
                'beginning' => $beginning
            ]);

            if ($training_element->training_action_id){
                $training_action = TrainingAction::find($training_element->training_action_id);
                $total_hours = $training_action->total_hours;
                $total_days = $total_hours / $hours_days;
            } else if($training_element->certification_id) {
                $certification = Certification::find($training_element->certification_id);
                $total_hours = $certification->total_hours;
                $total_days = $total_hours / $hours_days;
            } else {
                break;
            }
            $total_days = round($total_days);
            $training_element->update([
                'total_days' => $total_days
            ]);
            while($total_days != 0){
                $excluded = TrainingContractsExcludedDay::nonWorkingDay($id, $date);
                if ($excluded != true){
                    switch($beginning->dayOfWeek){
                        case 0:
                            if ($record->sunday == 1){
                                $total_days--;
                            }
                            break;
                        case 1:
                            if ($record->monday == 1){
                                $total_days--;
                            }
                            break;
                        case 2:
                            if ($record->tuesday == 1){
                                $total_days--;
                            }
                            break;
                        case 3:
                            if ($record->wednesday == 1){
                                $total_days--;
                            }
                            break;
                        case 4:
                            if ($record->thursday == 1){
                                $total_days--;
                            }
                            break;
                        case 5:
                            if ($record->friday == 1){
                                $total_days--;
                            }
                            break;
                        case 6:
                            if ($record->saturday == 1){
                                $total_days--;
                            }
                            break;
                    }
                }
                $end = $beginning->addDay();
            }
            $training_element->update([
                'end' => $end
            ]);
            $beginning = $beginning->addDay();
        }
        return response()->json([
            'status' => 200,
            'total_hours' => $total_hours,
            'daily_hours' => $daily_hours,
            'total_days' => $cont_days
        ]);
    }
}
