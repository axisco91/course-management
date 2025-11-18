<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Certificado de Diploma</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            text-align: center;
            padding: 60px;
            line-height: 1.8;
        }
        h1 {
            font-size: 32px;
            text-transform: uppercase;
        }
        h2 {
            font-size: 24px;
            margin-top: 30px;
        }
        p {
            margin: 20px 0;
            font-size: 16px;
        }
        .section-title {
            font-size: 18px;
            margin-top: 40px;
            text-decoration: underline;
        }
        ul {
            list-style: none;
            padding-left: 0;
        }
        .footer {
            margin-top: 60px;
            display: flex;
            justify-content: space-around;
        }
    </style>
</head>
<body>

<h1>CERTIFICADO DE DIPLOMA</h1>

<h2>DIPLOMA</h2>
<h2>152 - COMT07 GESTIÓN BÁSICA DEL ALMACÉN</h2>

<p>AVZ FORMACIÓN S.L., con CIF B16826638 certifica que D/ Dña. <strong>{{ $trainingContract->student_id }}</strong></p>

<p>Con DNI <strong>{{ $trainingContract->student_id }}</strong>, ha desarrollado con aprovechamiento y evaluación positiva la Acción Formativa en la modalidad de Teleformación con una duración de <strong>{{ $trainingContract->student_id }}</strong> horas, siendo su fecha de inicio <strong>{{ $trainingContract->student_id }}</strong> y de finalización <strong>{{ $trainingContract->student_id }}</strong>.</p>

<p>Y a los efectos oportunos firmo el presente Certificado en Lucena, a 14 de marzo de 2025.</p>

<div class="footer">
    <span>Centro de Formación</span>
    <span>Alumno</span>
</div>

<div class="section-title">CONTENIDOS</div>

<p class="section-title">UNIDAD DIDÁCTICA 1. CARACTERÍSTICAS BÁSICAS DEL ALMACÉN</p>
<ul>
    <li>El almacén: concepto y finalidad</li>
    <li>Principios almacén</li>
    <li>Funciones de almacén</li>
    <li>Áreas de almacén: recepción, almacenamiento y entrega</li>
    <li>El almacén de Amazon</li>
    <li>El almacén de Ikea</li>
</ul>

<p class="section-title">UNIDAD DIDÁCTICA 2. EQUIPAMIENTO Y SISTEMAS DE MOVIMIENTO Y COLOCACIÓN EN EL ALMACÉN</p>
<ul>
    <li>Equipo de trabajo y trabajo en equipo en el almacén</li>
    <li>Aplicación del concepto de trabajo en equipo</li>
    <li>Equipos de almacenamiento y unidades de manipulación</li>
    <li>Protección física y movimiento de cargas</li>
</ul>

<p class="section-title">UNIDAD DIDÁCTICA 3. PREVENCIÓN DE RIESGOS LABORALES</p>
<ul>
    <li>Riesgos, accidentes y medidas preventivas</li>
    <li>Limpieza y hábitos de trabajo</li>
    <li>Emergencias e incendios</li>
</ul>

</body>
</html>
