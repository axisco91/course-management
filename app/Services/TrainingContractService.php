<?php
namespace App\Services;

class TrainingContractService
{
    public function calculateHours($training_contract_id)
    {
        Log::info('calculateHours called with id: ' . $id);
    
        $record = TrainingContract::findOrFail($id);
        Log::info('TrainingContract record: ', (array) $record);
    
        $formative_hours_first_year = $record->formative_hours_first_year;
        $formative_hours_second_year = $record->formative_hours_second_year;
    
        $cont_days = 0;
        $date = Carbon::parse($record->beginning_formation);
        $end_date = Carbon::parse($record->end_formation);
        $total_hours = 0;
        $total = 0;
        $vacations = 0;
        $banckholiday = 0;
        $fin_semana = 0;
        $total_days = 0;
        $cont_days_first_year = 0;
        $cont_days_second_year = 0;
        $daily_hours_1 = 0;
        $daily_hours_2 = 0;
        $beginning_formation_carbon = Carbon::parse($record->beginning_formation);
    
        do {
            $excluded = TrainingContractsExcludedDay::nonWorkingDay($id, $date);
            if ($excluded != true){
                $excluded = TrainingContractFestival::existDay($date, $record->id)->first();
                if (!$excluded){
                    switch($date->dayOfWeek){
                        case 0:
                            if ($record->sunday === 0){
                                $fin_semana++;
                            }
                            break;
                        case 1:
                            if ($record->monday === 1){
                                $cont_days++;
                                if ($date->lt($beginning_formation_carbon->copy()->addYear())) {
                                    $cont_days_first_year++;
    
                                } else {
                                    $cont_days_second_year++;
    
                                }
                            }
                            break;
                        case 2:
                            if ($record->tuesday === 1){
                                $cont_days++;
                                if ($date->lt($beginning_formation_carbon->copy()->addYear())) {
                                    $cont_days_first_year++;
    
                                } else {
                                    $cont_days_second_year++;
    
                                }
                            }
                            break;
                        case 3:
                            if ($record->wednesday === 1){
                                $cont_days++;
                                if ($date->lt($beginning_formation_carbon->copy()->addYear())) {
                                    $cont_days_first_year++;
    
                                } else {
                                    $cont_days_second_year++;
    
                                }
                            }
                            break;
                        case 4:
                            if ($record->thursday === 1){
                                $cont_days++;
                                if ($date->lt($beginning_formation_carbon->copy()->addYear())) {
                                    $cont_days_first_year++;
    
                                } else {
                                    $cont_days_second_year++;
    
                                }
                            }
                            break;
                        case 5:
                            if ($record->friday === 1){
                                $cont_days++;
                                if ($date->lt($beginning_formation_carbon->copy()->addYear())) {
                                    $cont_days_first_year++;
    
                                } else {
                                    $cont_days_second_year++;
    
                                }
                            }
                            break;
                        case 6:
                            if ($record->saturday === 0){
                                $fin_semana++;
                            }
                            break;
                    }
                } else {
                    $banckholiday++;
                }
            } else {
                $vacations++;
            }
            $total++;
            $date->addDay();
        } while($end_date->gte($date));
    
        Log::info('formative_hours_first_year: ' . $formative_hours_first_year);
        Log::info('cont_days_first_year: ' . $cont_days_first_year);
        Log::info('formative_hours_second_year: ' . $formative_hours_second_year);
        Log::info('cont_days_second_year: ' . $cont_days_second_year);
    
        if ($cont_days_first_year != 0){
            $daily_hours_1 = $formative_hours_first_year / $cont_days_first_year;
            $daily_hours_1 = round($daily_hours_1, 2);
            
            if ($cont_days_second_year != 0) {
                $daily_hours_2 = $formative_hours_second_year / $cont_days_second_year;
                $daily_hours_2 = round($daily_hours_2, 2);
            } else {
                $daily_hours_2 = 0;
            }
            
            $record->update([
                'total_days' => $cont_days_first_year + $cont_days_second_year,
                'daily_hours_1' => $daily_hours_1,
                'daily_hours_2' => $daily_hours_2,
            ]);
            Log::info('Updated TrainingContract record: ', (array) $record);
        }
    
        // Devuelve una respuesta HTTP con los datos calculados
        return response()->json([
            'status' => 200,
            'total_hours' => $total_hours,
            'daily_hours_1' => $daily_hours_1,
            'daily_hours_2' => $daily_hours_2,
            'total_days' => $cont_days_first_year + $cont_days_second_year,
            // ... (cualquier otro dato que tu front-end necesite) ...
        ]);
    }

}