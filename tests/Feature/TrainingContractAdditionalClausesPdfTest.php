<?php

namespace Tests\Feature;

use Tests\TestCase;

class TrainingContractAdditionalClausesPdfTest extends TestCase
{
    private function renderClauses(string $clause, array $assets): string
    {
        return view('documents.training-contract-additional-clauses', [
            'trainingContract' => (object) [
                'document_additional_clause' => $clause,
                'beginning' => '2026-10-27',
                'company' => (object) ['population' => 'Madrid'],
            ],
            'clauseAssets' => $assets,
        ])->render();
    }

    public function brands(): array
    {
        return [
            'AVZ' => [['ue' => 'AVZ/ue.PNG', 'sepe' => 'AVZ/sepe.PNG']],
            'VR' => [['ue' => 'img-contrato/ue.png', 'sepe' => 'img-contrato/sepe.png']],
        ];
    }

    /** @dataProvider brands */
    public function test_preserves_line_breaks_and_escapes_user_text(array $assets): void
    {
        $clause = "Primera cláusula\r\nSegunda\n\nÚltima <script>alert('texto')</script>";
        $html = $this->renderClauses($clause, $assets);

        $this->assertStringContainsString(nl2br(e($clause)), $html);
        $this->assertStringNotContainsString('<script>', $html);
        $this->assertStringNotContainsString('overflow: hidden', $html);
        $this->assertStringNotContainsString('position: absolute', $html);
        $this->assertLessThan(strpos($html, 'Y para que conste'), strpos($html, 'Última'));
        $this->assertStringContainsString('IMPORTANTE', $html);
    }

    /** @dataProvider brands */
    public function test_long_clauses_paginate_and_empty_and_short_clauses_fit(array $assets): void
    {
        $style = '<style>'.file_get_contents(public_path('css/contrato.css')).'</style>';
        $counts = [];
        foreach (['', "Primera\n\nÚltima", str_repeat("Cláusula de formación y prevención.\nSegundo párrafo.\n\n", 130).'FIN'] as $clause) {
            $html = '<html><head><meta charset="utf-8">'.$style.'</head><body>'.$this->renderClauses($clause, $assets).'</body></html>';
            $pdf = app('dompdf.wrapper')->loadHTML($html)->setPaper('a4');
            $pdf->output();
            $counts[] = $pdf->getDomPDF()->getCanvas()->get_page_count();
        }
        $this->assertSame(1, $counts[0]);
        $this->assertSame(1, $counts[1]);
        $this->assertGreaterThan(2, $counts[2]);
    }
    /** @dataProvider brands */
    public function test_signatures_use_remaining_space_after_clauses(array $assets): void
    {
        $clause = str_repeat("Texto de prueba para comprobar el espacio y las firmas.\n", 44).'FIN';
        $html = '<html><head><meta charset="utf-8"><style>'.file_get_contents(public_path('css/contrato.css')).'</style></head><body>'.$this->renderClauses($clause, $assets).'</body></html>';
        $pdf = app('dompdf.wrapper')->loadHTML($html)->setPaper('a4');
        $pdf->output();

        $this->assertSame(1, $pdf->getDomPDF()->getCanvas()->get_page_count());
    }

}
