<?php

namespace App\Http\Controllers\Api;
use App\Helpers\GeneralHelpers;
use App\Http\Resources\WebPlatformResource;
use App\Models\WebPlatform;
use App\Services\MoodleProvisioningClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RuntimeException;

class WebPlatformController extends BaseController
{
    public function webPlatforms(Request $request)
    {
        try {
            $mainCompanyId = GeneralHelpers::urlObtainCompanyId(
                $request->headers->get('origin'),
                Auth::id()
            );

            $query = WebPlatform::getWebPlatform($mainCompanyId);

            // ✅ FILTRO POR NOMBRE
            // Ej: ?search=moodle
            if ($request->filled('search')) {
                $search = trim($request->input('search'));
                $query->where('name', 'LIKE', '%' . $search . '%');
            }

            // ✅ SORT (por defecto name asc)
            // ?sort=name   -> asc
            // ?sort=-name  -> desc
            $sort = $request->input('sort', 'name');
            $direction = 'asc';

            if (is_string($sort) && strlen($sort) > 0 && $sort[0] === '-') {
                $direction = 'desc';
                $sort = substr($sort, 1);
            }

            // (opcional) whitelist de columnas ordenables
            $allowedSorts = ['id', 'name', 'created_at', 'updated_at'];
            if (!in_array($sort, $allowedSorts, true)) {
                $sort = 'name';
                $direction = 'asc';
            }

            $query->orderBy($sort, $direction);

            // ✅ PAGINACIÓN
            if ($request->filled('perPage')) {
                $perPage = (int) $request->perPage;

                $paginator = $query->paginate($perPage);

                $webPlatforms = WebPlatformResource::collection($paginator);

                $paginationData = GeneralHelpers::generatePaginationData($paginator);

                return $this->sendResponse(
                    [
                        'web_platforms' => $webPlatforms,
                        'links' => $paginationData['links'],
                        'meta'  => $paginationData['meta'],
                    ],
                    trans('Obtenido con éxito')
                );
            }

            // ✅ SIN PAGINACIÓN
            $webPlatforms = WebPlatformResource::collection($query->get());

            return $this->sendResponse(
                [
                    'web_platforms' => $webPlatforms,
                ],
                trans('Obtenido con éxito')
            );
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function create(Request $request, MoodleProvisioningClient $moodleClient){
        try {
            $request->validate([
                'required_moodle_usernames' => ['nullable', 'array'],
                'required_moodle_usernames.*' => ['string'],
                'required_moodle_role_shortnames' => ['nullable', 'array'],
                'required_moodle_role_shortnames.*' => ['string'],
            ]);
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());
            $data = $request->all();
            $data['main_company_id'] = $mainCompanyId;
            $data['required_moodle_usernames'] = $this->requiredUsernames($request);
            $data['required_moodle_roles'] = $this->requiredRoles($request, $data['required_moodle_usernames']);

            $validationPlatform = new WebPlatform([
                'url' => $data['url'] ?? null,
                'token' => $data['token'] ?? null,
            ]);
            $moodleClient->assertUsersExist($validationPlatform, $data['required_moodle_usernames']);

            $web = WebPlatform::createWithService($data);

            return $this->sendResponse(
                [
                    'web_platform' => new WebPlatformResource($web),
                ],
                trans('Creado con éxito')
            );
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function edit($id, Request $request, MoodleProvisioningClient $moodleClient){
        try {
           $request->validate([
               'required_moodle_usernames' => ['nullable', 'array'],
               'required_moodle_usernames.*' => ['string'],
               'required_moodle_role_shortnames' => ['nullable', 'array'],
               'required_moodle_role_shortnames.*' => ['string'],
           ]);
           $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

            $web = WebPlatform::where('id', $id)
                ->where('main_company_id', $mainCompanyId)
                ->first();

            if (!$web) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Plataforma web no encontrado'
                ]);
            }

            $data = $request->all();
            $data['required_moodle_usernames'] = $this->requiredUsernames($request);
            $data['required_moodle_roles'] = $this->requiredRoles($request, $data['required_moodle_usernames']);

            $validationPlatform = $web->replicate();
            $validationPlatform->url = $data['url'] ?? $web->url;
            if (filled($data['token'] ?? null)) {
                $validationPlatform->token = $data['token'];
            }
            $moodleClient->assertUsersExist($validationPlatform, $data['required_moodle_usernames']);

            $web->updateWithService($data);

            return $this->sendResponse(
                [
                    'web_platform' => new WebPlatformResource(WebPlatform::getWebPlatform($mainCompanyId)->where('web_platforms.id', $web->id)->first()),
                ],
                trans('Guardado con éxito')
            );
        } catch (\Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getWebPlatform($id, Request $request){
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        $web = WebPlatform::where('id', $id)
            ->where('main_company_id', $mainCompanyId)
            ->first();

        if (!$web) {
            return response()->json([
                'status' => 404,
                'message' => 'Plataforma web no encontrado'
            ]);
        }

        return $this->sendResponse(
            [
                'web_platform' => new WebPlatformResource($web),
            ],
            trans('Obtenido con éxito')
        );
    }

    public function destroy($id, Request $request){
        if ($id) {
            try {
               $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

                $web = WebPlatform::where('id', $id)
                    ->where('main_company_id', $mainCompanyId)
                    ->first();

                if (!$web) {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Plataforma web no encontrado'
                    ]);
                }

                WebPlatform::destroy($id);
                return $this->sendResponse(
                    [],
                    trans('Eliminado con éxito')
                );
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 400,
                    'message' => $e->getMessage()
                ]);
            }
        }
    }

    public function count(Request $request){
       $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        return WebPlatform::where('main_company_id', $mainCompanyId)->count();
    }

    private function requiredUsernames(Request $request): array
    {
        $usernames = array_map(
            fn ($username) => trim((string) $username),
            $request->input('required_moodle_usernames', [])
        );

        if (in_array('', $usernames, true)) {
            throw new RuntimeException('Los usernames Moodle obligatorios no pueden estar vacíos.');
        }

        $normalized = array_map(fn ($username) => mb_strtolower($username), $usernames);
        if (count($normalized) !== count(array_unique($normalized))) {
            throw new RuntimeException('Los usernames Moodle obligatorios no pueden estar duplicados.');
        }

        return array_values($usernames);
    }

    private function requiredRoles(Request $request, array $usernames): array
    {
        $roles = array_map(
            fn ($role) => trim((string) $role),
            $request->input('required_moodle_role_shortnames', [])
        );

        if (count($roles) !== count($usernames) || in_array('', $roles, true)) {
            throw new RuntimeException('Cada usuario Moodle obligatorio debe tener un rol configurado.');
        }

        foreach ($roles as $role) {
            if (!preg_match('/^[a-z0-9_-]+$/i', $role)) {
                throw new RuntimeException('El nombre corto del rol Moodle no es válido: '.$role.'.');
            }
        }

        return array_combine($usernames, $roles) ?: [];
    }
}
