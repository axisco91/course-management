<?php

namespace App\Http\Controllers;

use App\Models\MainCompany;
use Illuminate\Http\Request;

class PublicCompanyController extends Controller
{
    private function companies()
    {
        return MainCompany::query()->where('active', 1)
            ->whereNotIn('id', config('client_access.excluded_ids', [1]))
            ->select(['id', 'name', 'url']);
    }

    private function present(MainCompany $company): ?array
    {
        $domain = strtolower(trim((string) config('client_access.domain')));
        if (!$domain || !preg_match('/^[a-z0-9.-]+$/', $domain)) return null;
        $url = trim((string) $company->url);
        if (!str_contains($url, '://')) $url = 'https://' . $url;
        if (!preg_match('~^https://([a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?)\.' . preg_quote($domain, '~') . '/?$~i', $url, $matches)) return null;
        $subdomain = strtolower($matches[1]);
        if (in_array($subdomain, ['accesoclientes', 'www', 'api'], true)) {
            return null;
        }
        return ['id' => $company->id, 'name' => $company->name, 'subdomain' => $subdomain,
            'login_url' => 'https://' . $subdomain . '.' . $domain . '/login'];
    }

    public function search(Request $request)
    {
        $data = $request->validate(['q' => 'required|string|min:3|max:100']);
        // Escape LIKE wildcards: the search term is literal user input.
        $term = str_replace(['!', '%', '_'], ['!!', '!%', '!_'], trim($data['q']));
        $results = [];
        foreach ($this->companies()->whereRaw("LOWER(name) LIKE ? ESCAPE '!'", ['%' . mb_strtolower($term) . '%'])
            ->orderBy('name')->orderBy('id')->cursor() as $company) {
            if ($item = $this->present($company)) {
                $results[] = $item;
                if (count($results) === 10) {
                    break;
                }
            }
        }
        return response()->json(['data' => $results])->header('Cache-Control', 'no-store');
    }

    public function show(int $id)
    {
        $company = $this->companies()->find($id);
        $item = $company ? $this->present($company) : null;
        abort_unless($item, 404);
        return response()->json(['data' => $item])->header('Cache-Control', 'no-store');
    }
}
