<?php

namespace App\Http\Controllers\Api;

use App\Helpers\GeneralHelpers;
use App\Models\EmailLog;
use App\Models\Tracing;
use App\Services\TracingEmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class EmailLogController extends BaseController
{
    public function index(Request $request)
    {
        $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        $query = EmailLog::query()
            ->with([
                'student:id,name,surname,email',
                'course:id,name,group',
            ])
            ->when($mainCompanyId, fn ($q) => $q->where('main_company_id', $mainCompanyId))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('mail_type'), fn ($q) => $q->where('mail_type', $request->mail_type))
            ->when($request->filled('search_text'), function ($q) use ($request) {
                $search = trim((string) $request->search_text);
                $q->where(function ($subQuery) use ($search) {
                    $subQuery->where('subject', 'like', '%' . $search . '%')
                        ->orWhere('original_to', 'like', '%' . $search . '%')
                        ->orWhere('final_to', 'like', '%' . $search . '%')
                        ->orWhereHas('student', function ($studentQuery) use ($search) {
                            $studentQuery->where('name', 'like', '%' . $search . '%')
                                ->orWhere('surname', 'like', '%' . $search . '%')
                                ->orWhere('email', 'like', '%' . $search . '%');
                        })
                        ->orWhereHas('course', function ($courseQuery) use ($search) {
                            $courseQuery->where('name', 'like', '%' . $search . '%')
                                ->orWhere('group', 'like', '%' . $search . '%');
                        });
                });
            });

        $sortParam = (string) $request->get('sort', '-created_at');
        $direction = str_starts_with($sortParam, '-') ? 'desc' : 'asc';
        $sortField = ltrim($sortParam, '-');
        $sortColumn = in_array($sortField, ['created_at', 'sent_at', 'status', 'mail_type', 'subject'], true)
            ? $sortField
            : 'created_at';

        $query->orderBy($sortColumn, $direction);

        if ($request->filled('perPage')) {
            $paginator = $query->paginate((int) $request->perPage);
            $paginationData = GeneralHelpers::generatePaginationData($paginator);

            return $this->sendResponse([
                'email_logs' => $paginator->items(),
                'links' => $paginationData['links'],
                'meta' => $paginationData['meta'],
            ], trans('Obtenido con éxito'));
        }

        return $this->sendResponse([
            'email_logs' => $query->get(),
        ], trans('Obtenido con éxito'));
    }

    public function resend($id, Request $request, TracingEmailService $tracingEmailService)
    {
        $mainCompanyId = GeneralHelpers::urlObtainCompanyId($request->headers->get('origin'), Auth::id());

        $emailLog = EmailLog::query()
            ->when($mainCompanyId, fn ($query) => $query->where('main_company_id', $mainCompanyId))
            ->find($id);

        if (!$emailLog) {
            return response()->json(['message' => 'Registro de correo no encontrado.'], 404);
        }

        if (!$emailLog->tracing_id) {
            return response()->json(['message' => 'Este correo no pertenece a un seguimiento.'], 400);
        }

        $type = match ($emailLog->mail_type) {
            'greeting' => 'welcome',
            'quarter' => 'quarter',
            'half' => 'half',
            'three_quarters' => 'three_quarters',
            'final' => 'final',
            'course_end_reminder' => 'one_week',
            default => null,
        };

        if (!$type) {
            return response()->json(['message' => 'Este tipo de correo no admite reenvío.'], 400);
        }

        $tracing = Tracing::find($emailLog->tracing_id);
        if (!$tracing) {
            return response()->json(['message' => 'El seguimiento asociado ya no existe.'], 400);
        }

        try {
            $tracingEmailService->sendTracingMail($tracing, $type, [
                'idempotency_suffix' => 'email-log-'.$emailLog->id.'-'.Str::uuid(),
            ]);

            return $this->sendResponse([], 'Correo reenviado correctamente.');
        } catch (\Throwable $exception) {
            return response()->json(['message' => $exception->getMessage()], 400);
        }
    }
}
