<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class occupationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datos = [
            ['name' => 'Empleados administrativos con tareas de atención al público no clasificados bajo otros epígrafes  (45001019)'],
            ['name' => 'Empleados administrativos de contabilidad, en general  (41111011)'],
            ['name' => 'Empleados administrativos de los servicios de almacenamiento y recepción  (41211056)'],
            ['name' => 'Empleados administrativos de seguros  (41131033)'],
            ['name' => 'Empleados administrativos de servicio de personal  (41121012)'],
            ['name' => 'Empleados administrativos, en general  (43091029)'],
            ['name' => 'Operadores de central telefónica  (44231013)'],
            ['name' => 'Operadores-grabadores de datos en ordenador  (43011025)'],
            ['name' => 'Ordenanzas  (94311020)'],
            ['name' => 'Recepcionistas en establecimientos distintos de oficinas, en general  (44121048)'],
            ['name' => 'Recepcionistas-telefonistas en oficinas, en general  (44121057)'],
            ['name' => 'Técnicos en seguros  (35211020)'],
            ['name' => 'Jardineros, en general  (61201028)'],
            ['name' => 'Peones agrícolas, en general.  (95111016)'],
            ['name' => 'Peones de horticultura, jardinería  (95121019)'],
            ['name' => 'Agentes comerciales inmobiliario  (35341028)'],
            ['name' => 'Cajeros de comercio  (55001018)'],
            ['name' => 'Dependiente de frutería  (52201132)'],
            ['name' => 'Dependiente de panadería, pastelería y confitería  (52201231)'],
            ['name' => 'Dependientes de artículos de deporte, caza y pesca  (52201013)'],
            ['name' => 'Dependientes de artículos de regalo  (52201046)'],
            ['name' => 'Dependientes de calzado y artículos de piel  (52201057)'],
            ['name' => 'Dependientes de carnicería y/o charcutería  (52201068)'],
            ['name' => 'Dependientes de comercio, en general  (52201079)'],
            ['name' => 'Dependientes de electrodomésticos  (52201091)'],
            ['name' => 'Dependientes de floristería  (52201121)'],
            ['name' => 'Dependientes de juguetería  (52201176)'],
            ['name' => 'Dependientes de muebles y artículos de decoración  (52201213)'],
            ['name' => 'Dependientes de peletería  (52201240)'],
            ['name' => 'Dependientes de perfumería y droguería  (52201251)'],
            ['name' => 'Dependientes de pescadería  (52201262)'],
            ['name' => 'Dependientes de ropa de hogar  (52201325)'],
            ['name' => 'Dependientes de tejidos y prendas de vestir  (52201334)'],
            ['name' => 'Embaladores-empaquetadores-etiquetadores, a mano  (97001010)'],
            ['name' => 'Empleados del área de atención al cliente  (44111018)'],
            ['name' => 'Expendedores de combustible  (54301012)'],
            ['name' => 'Reponedores de hipermercado  (98201011)'],
            ['name' => 'Técnicos en publicidad y/o relaciones públicas  (26511049)'],
            ['name' => 'Teleoperadores  (44241016)'],
            ['name' => 'Vendedor por teléfono  (54201013)'],
            ['name' => 'Vendedores no clasificados bajo otros epígrafes  (54991013)'],
            ['name' => 'Vendedores técnicos, en general  (26401047)'],
            ['name' => 'Almaceneros de empresas de transporte  (41211023)'],
            ['name' => 'Conductores-operadores de carretilla elevadora, en general  (83331015)'],
            ['name' => 'Mozos de carga y descarga, almacén y/o mercado de abastos  (98111024)'],
            ['name' => 'Peones del transporte en general  (98111060)'],
            ['name' => 'Ayudantes de servicios (hostelería)  (92101016)'],
            ['name' => 'Bármanes  (51201016)'],
            ['name' => 'Camareros de barra y/o dependientes de cafetería  (51201027)'],
            ['name' => 'Camareros de sala o jefes de rango  (51201038)'],
            ['name' => 'Camareros, en general  (51201049)'],
            ['name' => 'Cocineros, en general  (51101026)'],
            ['name' => 'Jefes de barra o cafetería  (51201050)'],
            ['name' => 'Marmitones  (93101013)'],
            ['name' => 'Pinches de cocina  (93101024)'],
            ['name' => 'Preparadores de catering  (51101059)'],
            ['name' => 'Albañiles  (71211015)'],
            ['name' => 'Enlucidores-yesistas  (72121021)'],
            ['name' => 'Peones de la construcción de edificios  (96021013)'],
            ['name' => 'Soladores-alicatadores, en general  (72401073)'],
            ['name' => 'Instaladores electricistas de edificios y viviendas  (75101015)'],
            ['name' => 'Instaladores electricistas, en general  (75101033)'],
            ['name' => 'Instaladores de conducciones de aire acondicionado y  ventilación  (72501018)'],
            ['name' => 'Instaladores-Ajustadores de instalaciones de refrigeración y aire acondicionado  (72501030)'],
            ['name' => 'Mantenedor de edificios  (71911012)'],
            ['name' => 'Mecánicos de mantenimiento industrial  (74031142)'],
            ['name' => 'Mecánicos-ajustadores de maquinaria industrial, en general  (74031209)'],
            ['name' => 'Peón de la industria alimentaria  (97001056)'],
            ['name' => 'Administradores de sistemas de redes  (27211018)'],
            ['name' => 'Diseñadores de páginas web  (27131015)'],
            ['name' => 'Programadores de aplicaciones informáticas  (38201017)'],
            ['name' => 'Técnicos de soporte de web  (38141010)'],
            ['name' => 'Técnicos de operaciones de sistemas informáticos  (38111011)'],
            ['name' => 'Instaladores de sistemas fotovoltaicos y eólicos  (75211101)'],
            ['name' => 'Montadores de placas de energía solar  (72941032)'],
            ['name' => 'Operadores en central solar fotovoltaica  (31311111)'],
            ['name' => 'Diseñadores gráficos y multimedia  (24841012)'],
            ['name' => 'Asistentes domiciliarios  (57101013)'],
            ['name' => 'Auxiliar de ayuda a domicilio  (51130024)'],
            ['name' => 'Cuidadores de personas con discapacidad y/o dependencia, en instituciones  (56291025)'],
            ['name' => 'Monitores de educación y tiempo libre  (37241034)'],
            ['name' => 'Monitores socio-culturales  (37241043)'],
            ['name' => 'Personal de limpieza o limpiadores, en general  (92101050)'],
            ['name' => 'Mecánicos de mantenimiento y reparación de automoción, en general  (74011034)'],
            ['name' => 'Mecánicos-ajustadores del automóvil, en general (turismos y furgonetas)  (74011119)'],
            ['name' => 'Agentes Comerciales (35101019)'],
            ['name' => 'Pasteleros (77031048)'],
        ];
        
        
         // Insertar los datos en la tabla
         DB::table('occupations')->insert($datos);
    }
}
