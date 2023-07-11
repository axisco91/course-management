<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingContractFestival extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['training_contract_id', 'nacional_festival_id', 'province_festival_id', 'population_festival_id'];

    /**
     * Scope para obtener los festivales
     * @param $query
     * @return mixed
     */
    public function scopeFestivals($query) {
        return $query->select('training_contract_festivals.*')
            ->leftjoin('nacional_festivals', 'nacional_festivals.id', '=', 'training_contract_festivals.nacional_festival_id')
            ->leftjoin('province_festivals', 'province_festivals.id', '=', 'training_contract_festivals.province_festival_id')
            ->leftjoin('population_festivals', 'population_festivals.id', '=', 'training_contract_festivals.population_festival_id')
            ->selectRaw('
                training_contract_festivals.*,
                CASE
                    WHEN nacional_festivals.id IS NOT NULL THEN CONCAT(nacional_festivals.name, " (nacional)")
                    WHEN province_festivals.id IS NOT NULL THEN CONCAT(province_festivals.name, " (province)")
                    WHEN population_festivals.id IS NOT NULL THEN CONCAT(population_festivals.name, " (population)")
                END AS name,
                CASE
                    WHEN nacional_festivals.id IS NOT NULL THEN nacional_festivals.day
                    WHEN province_festivals.id IS NOT NULL THEN province_festivals.day
                    WHEN population_festivals.id IS NOT NULL THEN population_festivals.day
                END AS day
            ');
    }

    /**
     * Metodo para crear los festivales
     * @param $training_contract
     * @param $id
     * @param $type
     * @param $start
     * @param $end
     * @return bool
     */
    public static function createTrainingContractFestivals($training_contract, $id, $type, $start, $end){
        if ($type == 'province') {
            $festivals = ProvinceFestival::where('province_id', $id);
        } else if ($type == 'population') {
            $festivals = PopulationFestival::where('population_id', $id);
        } else if ($type == 'nacional') {
            $festivals = NacionalFestival::select('nacional_festivals.*');
        }

        if ($start && $end) {
            $festivals->whereBetween('day', [$start, $end]);
        } else if ($start) {
            $festivals->whereDate('day', '>=', $start);
        } else if ($end) {
            $festivals->whereDate('day', '<=', $end);
        }

        $festivals = $festivals->get();
        if ($festivals){
            foreach ($festivals as $festival){
                if ($type == 'province') {
                    $training_contract_festival = TrainingContractFestival::where('province_festival_id', $festival->id)
                        ->where('training_contract_id', $training_contract)->first();
                } else if ($type == 'population') {
                    $training_contract_festival = TrainingContractFestival::where('population_festival_id', $festival->id)
                        ->where('training_contract_id', $training_contract)->first();
                } else if ($type == 'nacional') {
                    $training_contract_festival = TrainingContractFestival::where('nacional_festival_id', $festival->id)
                        ->where('training_contract_id', $training_contract)->first();
                }

                if (!$training_contract_festival){
                    if ($type == 'province') {
                        TrainingContractFestival::create([
                            'training_contract_id' => $training_contract,
                            'province_festival_id' => $festival->id
                        ]);
                    } else if ($type == 'population') {
                        TrainingContractFestival::create([
                            'training_contract_id' => $training_contract,
                            'population_festival_id' => $festival->id
                        ]);
                    } else if ($type == 'nacional') {
                        TrainingContractFestival::create([
                            'training_contract_id' => $training_contract,
                            'nacional_festival_id' => $festival->id
                        ]);
                    }
                }
            }
        }
        return true;
    }

    public function scopeExistDay($query, $day, $training_contract_id){
        $query->select('training_contract_festivals.*')
            ->leftJoin('nacional_festivals', 'nacional_festivals.id', '=', 'training_contract_festivals.nacional_festival_id')
            ->leftJoin('province_festivals', 'province_festivals.id', '=', 'training_contract_festivals.province_festival_id')
            ->leftJoin('population_festivals', 'population_festivals.id', '=', 'training_contract_festivals.population_festival_id')
            ->where(function ($query) use ($day) {
                $query->where(function ($query) use ($day) {
                    $query->whereNotNull('nacional_festivals.id')
                        ->where('nacional_festivals.day', '=', $day);
                })
                    ->orWhere(function ($query) use ($day) {
                        $query->whereNotNull('province_festivals.id')
                            ->where('province_festivals.day', '=', $day);
                    })
                    ->orWhere(function ($query) use ($day) {
                        $query->whereNotNull('population_festivals.id')
                            ->where('population_festivals.day', '=', $day);
                    });
            })->where('training_contract_festivals.training_contract_id', $training_contract_id);
    }
}
