<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CollaboratorController extends BaseController
{
    public function collaborators(Request $request) {
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        return $this->sendResponse(
            [
                'collaborators' => User::select('*', 'id as value', DB::raw("CONCAT(users.name,' ',users.surname) as label"))
                    ->where('has_commission', 1)
                    ->FilterMainCompany($mainCompanyId)
                    ->get(),
            ],
            trans('Guardado con éxito')
        );
    }
}
