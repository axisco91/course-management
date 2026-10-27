<style>
    .additional-clause-text {
        background-color: #f1f4ff;
        padding: 20px;
        font-size: 10px;
        text-align: left;
        overflow-wrap: break-word;
        word-wrap: break-word;
        page-break-inside: auto;
    }
    .additional-clause-empty { height: 200px; }
    .additional-clause-closing { page-break-inside: auto; margin-top: 12px; }
    .additional-clause-signatures { height: 110px; page-break-inside: avoid; }
    .additional-clause-notice { page-break-inside: avoid; }
    .additional-clause-footer { width: 100%; margin-top: 10px; }
    .additional-clause-page:before { content: counter(page); }
</style>
<div class="mt-4 borde-redondeado" style="page-break-inside: auto;">
    <h1 class="text-center">CLAUSULAS ADICIONALES</h1>
    @if((string) $trainingContract->document_additional_clause !== '')
        <div class="additional-clause-text">{!! nl2br(e($trainingContract->document_additional_clause)) !!}</div>
    @else
        <div class="additional-clause-text additional-clause-empty">
            @for($line = 0; $line < 16; $line++)
                {{ str_repeat('.', 150) }}<br>
            @endfor
        </div>
    @endif
</div>
<div class="additional-clause-closing">
    <p>Y para que conste, se extiende este contrato por triplicado ejemplar en el lugar y fecha a continuación indicados, firmando las partes interesadas.</p>
    @php
        // Verificar si $trainingContract->beginning está definido y tiene el formato correcto
        if (isset($trainingContract->beginning) && preg_match('/\d{4}-\d{2}-\d{2}/', $trainingContract->beginning)) {
            // Convertir la cadena de fecha en un objeto DateTime
            $beginningDate = DateTime::createFromFormat('Y-m-d', $trainingContract->beginning);

            // Formatear la fecha al español
            $formatter = new IntlDateFormatter('es_ES', IntlDateFormatter::LONG, IntlDateFormatter::NONE);
            $formatter->setPattern('d \'de\' MMMM \'de\' Y');
            $fechaFormateada = $formatter->format($beginningDate);

            // Separar el día, mes y año de la fecha formateada
            $fechaPartes = explode(' ', $fechaFormateada);
            $dia = $fechaPartes[0];
            $nombre_mes = $fechaPartes[2];
            $anio = $fechaPartes[4];
        } else {
            // Proporcionar valores por defecto si $trainingContract->beginning no está definido o no tiene el formato correcto
            $nombre_mes = 'mes desconocido';
            $dia = '00';
            $anio = '0000';
        }
    @endphp

    <p>En <span class="dots">{{$trainingContract->company->population}} </span> a <span class="dots">{{$dia}} </span> de <span class="dots">{{$nombre_mes}}</span> de <span class="dots">{{$anio}}</span></p>



    <div class="additional-clause-signatures">
        <table>
            <tr>
                <td style="width: 33%; border:none;">
                    <p>El/la trabajador/a</p>
                </td>
                <td style="width: 33%; border:none;">
                    <p>El/la representante de la empresa</p>
                </td>
                <td style="width: 33%; border:none;">
                    <p>El/la representante legal del/la menor, si procede</p>
                </td>
            </tr>
        </table>
    </div>

    <div class="additional-clause-notice">
    <h1 class="text-center">IMPORTANTE</h1>
    <h1 class="text-center">(TODAS LAS PÁGINAS CUMPLIMENTADAS DE ESTE CONTRATO DEBERÁN IR FIRMADAS EN EL MARGEN IZQUIERDO PARA MAYOR SEGURIDAD JURÍDICA)</h1>
<div class="additional-clause-footer">
    <table>
        <tr>
            <td width="48%" style="border: none; text-align:left;">
                <img src="{{ file_exists(public_path($clauseAssets['ue'])) ? public_path($clauseAssets['ue']) : public_path('AVZ/ue.PNG') }}" alt="ue" class="img-fluid fixed-height-img-ue2">
            </td>
            <td width="4%" style="border: none; text-align:center;">
                <span class="additional-clause-page"></span>
            </td>
            <td width="48%" style="border: none; text-align:right;">
                <img src="{{ file_exists(public_path($clauseAssets['sepe'])) ? public_path($clauseAssets['sepe']) : public_path('AVZ/sepe.PNG') }}" alt="sepe" class="img-fluid fixed-height-img-sepe2">
            </td>
        </tr>
    </table>
</div>
<br>



</div>
</div>
