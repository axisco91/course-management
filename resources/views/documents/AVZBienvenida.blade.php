<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Guia_Bienvenida</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bienvenida.css">

</head>
<body>
    <table>
        <tr>
            <td width="55%"  style="border: none; text-align:left;">
            </td>
            <td width="45%"  style="border: none;">
                <div class="col-md-12">
                    <img src="AVZ/logo.png" alt="logoAzul" class="img-fluid fixed-height-img-logo">
                </div>
            </td>
        </tr>
    </table>

    <div class="container mt-3">
        <p>Estimado/a {{$trainingContract->student->name}} {{$trainingContract->student->surname}}.</p> 
    </div>

    <p>Ante todo quisiera darle la bienvenida a nuestro Centro de Formación Avz Formación para realizar la Actividad Formativa obligatoria vinculada a su Contrato de Formación en Alternancia:</p>

    <table>
        @foreach ($elements as $e)
        <tr>
            <td width="60%"  style="border: none; text-align:left;">
                {{$e->training_action->code}} {{$e->training_action->name}}
            </td>
            <td width="50%"  style="border: none;">
                {{$e->training_contract->}}
            </td>
        </tr>
        @endforeach
    </table>

    
</body>
</html>