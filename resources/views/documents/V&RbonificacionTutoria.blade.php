<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bonificación por tutoría - V&R</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .header {
            background-color: rgb(24, 57, 46);
            color: white;
            text-align: center;
            padding: 15px;
            margin-bottom: 20px;
        }

        .header span {
            text-decoration: underline;
        }

        .contenedor-tablas {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            padding: 20px;
        }

        .tabla {
            width: 48%; /* Ajusta el ancho para asegurar que ambas tablas caben en una fila */
            box-sizing: border-box;
            padding: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid rgb(184, 206, 198);
        }

        th, td {
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: rgb(24, 57, 46);
            color: rgb(184, 206, 198);
        }

        .section-title {
            font-size: 1.2em;
            margin: 0;
            padding: 10px;
            background-color: rgb(24, 57, 46);
            color: white;
            text-align: center;
            width: 100%;
            box-sizing: border-box;
        }

        .section-title.baja {
            background-color: rgb(184, 206, 198);
            color: rgb(24, 57, 46);
        }

        .footer {
            background-color: rgb(24, 57, 46);
            color: white;
            text-align: center;
            padding: 10px;
            margin-top: 20px;
        }

        .footer div {
            display: inline-block;
            margin: 0 20px;
        }

        .footer svg {
            vertical-align: middle;
            margin-right: 10px;
        }

        .footer img {
            width: 100px;
            vertical-align: middle;
        }
    </style>
</head>
<body>
    <div class="header">
        TABLA DE BONIFICACIÓN POR TUTORIZACIÓN SEGÚN DÍA DE <span>ALTA</span> Y <span>BAJA</span>
    </div>

    <div class="contenedor-tablas">
        <div class="tabla">
            <table>
                <thead>
                    <tr>
                        <th colspan="3">Plantilla de menos de 5 trabajadores (Alta)</th>
                    </tr>
                    <tr>
                        <th>Día</th>
                        <th>Horas</th>
                        <th>Importe</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td>1</td><td>40</td><td>80,00 €</td></tr>
                    <tr><td>2</td><td>38</td><td>76,00 €</td></tr>
                    <tr><td>3</td><td>37</td><td>74,00 €</td></tr>
                    <tr><td>4</td><td>36</td><td>72,00 €</td></tr>
                    <tr><td>5</td><td>34</td><td>68,00 €</td></tr>
                    <tr><td>6</td><td>33</td><td>66,00 €</td></tr>
                    <tr><td>7</td><td>32</td><td>64,00 €</td></tr>
                    <tr><td>8</td><td>30</td><td>60,00 €</td></tr>
                    <tr><td>9</td><td>29</td><td>58,00 €</td></tr>
                    <tr><td>10</td><td>28</td><td>56,00 €</td></tr>
                    <tr><td>11</td><td>26</td><td>52,00 €</td></tr>
                    <tr><td>12</td><td>25</td><td>50,00 €</td></tr>
                    <tr><td>13</td><td>24</td><td>48,00 €</td></tr>
                    <tr><td>14</td><td>22</td><td>44,00 €</td></tr>
                    <tr><td>15</td><td>21</td><td>42,00 €</td></tr>
                    <tr><td>16</td><td>20</td><td>40,00 €</td></tr>
                    <tr><td>17</td><td>18</td><td>36,00 €</td></tr>
                    <tr><td>18</td><td>17</td><td>34,00 €</td></tr>
                    <tr><td>19</td><td>16</td><td>32,00 €</td></tr>
                    <tr><td>20</td><td>14</td><td>28,00 €</td></tr>
                    <tr><td>21</td><td>13</td><td>26,00 €</td></tr>
                    <tr><td>22</td><td>12</td><td>24,00 €</td></tr>
                    <tr><td>23</td><td>10</td><td>20,00 €</td></tr>
                    <tr><td>24</td><td>9</td><td>18,00 €</td></tr>
                    <tr><td>25</td><td>8</td><td>16,00 €</td></tr>
                    <tr><td>26</td><td>6</td><td>12,00 €</td></tr>
                    <tr><td>27</td><td>5</td><td>10,00 €</td></tr>
                    <tr><td>28</td><td>4</td><td>8,00 €</td></tr>
                    <tr><td>29</td><td>2</td><td>4,00 €</td></tr>
                    <tr><td>30</td><td>1</td><td>2,00 €</td></tr>
                    <tr><td>31</td><td>1</td><td>2,00 €</td></tr>
                </tbody>
            </table>
        </div>

        <div class="tabla">
            <table>
                <thead>
                    <tr>
                        <th colspan="3">Plantilla de 5 o más trabajadores (Alta)</th>
                    </tr>
                    <tr>
                        <th>Día</th>
                        <th>Horas</th>
                        <th>Importe</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td>1</td><td>40</td><td>60,00 €</td></tr>
                    <tr><td>2</td><td>38</td><td>57,00 €</td></tr>
                    <tr><td>3</td><td>37</td><td>55,00 €</td></tr>
                    <tr><td>4</td><td>36</td><td>54,00 €</td></tr>
                    <tr><td>5</td><td>34</td><td>51,00 €</td></tr>
                    <tr><td>6</td><td>33</td><td>49,00 €</td></tr>
                    <tr><td>7</td><td>32</td><td>48,00 €</td></tr>
                    <tr><td>8</td><td>30</td><td>45,00 €</td></tr>
                    <tr><td>9</td><td>29</td><td>43,00 €</td></tr>
                    <tr><td>10</td><td>28</td><td>42,00 €</td></tr>
                    <tr><td>11</td><td>26</td><td>39,00 €</td></tr>
                    <tr><td>12</td><td>25</td><td>37,00 €</td></tr>
                    <tr><td>13</td><td>24</td><td>36,00 €</td></tr>
                    <tr><td>14</td><td>22</td><td>33,00 €</td></tr>
                    <tr><td>15</td><td>21</td><td>31,00 €</td></tr>
                    <tr><td>16</td><td>20</td><td>30,00 €</td></tr>
                    <tr><td>17</td><td>18</td><td>27,00 €</td></tr>
                    <tr><td>18</td><td>17</td><td>25,00 €</td></tr>
                    <tr><td>19</td><td>16</td><td>24,00 €</td></tr>
                    <tr><td>20</td><td>14</td><td>21,00 €</td></tr>
                    <tr><td>21</td><td>13</td><td>19,00 €</td></tr>
                    <tr><td>22</td><td>12</td><td>18,00 €</td></tr>
                    <tr><td>23</td><td>10</td><td>15,00 €</td></tr>
                    <tr><td>24</td><td>9</td><td>13,00 €</td></tr>
                    <tr><td>25</td><td>8</td><td>12,00 €</td></tr>
                    <tr><td>26</td><td>6</td><td>9,00 €</td></tr>
                    <tr><td>27</td><td>5</td><td>7,00 €</td></tr>
                    <tr><td>28</td><td>4</td><td>6,00 €</td></tr>
                    <tr><td>29</td><td>2</td><td>3,00 €</td></tr>
                    <tr><td>30</td><td>1</td><td>1,00 €</td></tr>
                    <tr><td>31</td><td>1</td><td>1,00 €</td></tr>
                </tbody>
            </table>
        </div>

        <div class="tabla">
            <div class="section-title baja">TABLA DE BONIFICACIÓN POR TUTORIZACIÓN SEGÚN DÍA DE <span>BAJA</span></div>
            <table>
                <thead>
                    <tr>
                        <th colspan="3">Plantilla de menos de 5 trabajadores</th>
                    </tr>
                    <tr>
                        <th>Día</th>
                        <th>Horas</th>
                        <th>Importe</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td>1</td><td>1</td><td>2,00 €</td></tr>
                    <tr><td>2</td><td>3</td><td>6,00 €</td></tr>
                    <tr><td>3</td><td>4</td><td>8,00 €</td></tr>
                    <tr><td>4</td><td>5</td><td>10,00 €</td></tr>
                    <tr><td>5</td><td>6</td><td>12,00 €</td></tr>
                    <tr><td>6</td><td>8</td><td>16,00 €</td></tr>
                    <tr><td>7</td><td>9</td><td>18,00 €</td></tr>
                    <tr><td>8</td><td>10</td><td>20,00 €</td></tr>
                    <tr><td>9</td><td>12</td><td>24,00 €</td></tr>
                    <tr><td>10</td><td>13</td><td>26,00 €</td></tr>
                    <tr><td>11</td><td>14</td><td>28,00 €</td></tr>
                    <tr><td>12</td><td>16</td><td>32,00 €</td></tr>
                    <tr><td>13</td><td>17</td><td>34,00 €</td></tr>
                    <tr><td>14</td><td>18</td><td>36,00 €</td></tr>
                    <tr><td>15</td><td>20</td><td>40,00 €</td></tr>
                    <tr><td>16</td><td>21</td><td>42,00 €</td></tr>
                    <tr><td>17</td><td>22</td><td>44,00 €</td></tr>
                    <tr><td>18</td><td>24</td><td>48,00 €</td></tr>
                    <tr><td>19</td><td>25</td><td>50,00 €</td></tr>
                    <tr><td>20</td><td>26</td><td>52,00 €</td></tr>
                    <tr><td>21</td><td>28</td><td>56,00 €</td></tr>
                    <tr><td>22</td><td>29</td><td>58,00 €</td></tr>
                    <tr><td>23</td><td>30</td><td>60,00 €</td></tr>
                    <tr><td>24</td><td>32</td><td>64,00 €</td></tr>
                    <tr><td>25</td><td>33</td><td>66,00 €</td></tr>
                    <tr><td>26</td><td>34</td><td>68,00 €</td></tr>
                    <tr><td>27</td><td>36</td><td>72,00 €</td></tr>
                    <tr><td>28</td><td>37</td><td>74,00 €</td></tr>
                    <tr><td>29</td><td>38</td><td>76,00 €</td></tr>
                    <tr><td>30</td><td>40</td><td>80,00 €</td></tr>
                    <tr><td>31</td><td>40</td><td>80,00 €</td></tr>
                </tbody>
            </table>
        </div>

        <div class="tabla">
            <table>
                <thead>
                    <tr>
                        <th colspan="3">Plantilla de 5 o más trabajadores</th>
                    </tr>
                    <tr>
                        <th>Día</th>
                        <th>Horas</th>
                        <th>Importe</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td>1</td><td>1</td><td>1,00 €</td></tr>
                    <tr><td>2</td><td>3</td><td>4,00 €</td></tr>
                    <tr><td>3</td><td>4</td><td>6,00 €</td></tr>
                    <tr><td>4</td><td>5</td><td>7,00 €</td></tr>
                    <tr><td>5</td><td>6</td><td>9,00 €</td></tr>
                    <tr><td>6</td><td>8</td><td>12,00 €</td></tr>
                    <tr><td>7</td><td>9</td><td>13,00 €</td></tr>
                    <tr><td>8</td><td>10</td><td>15,00 €</td></tr>
                    <tr><td>9</td><td>12</td><td>18,00 €</td></tr>
                    <tr><td>10</td><td>13</td><td>19,00 €</td></tr>
                    <tr><td>11</td><td>14</td><td>21,00 €</td></tr>
                    <tr><td>12</td><td>16</td><td>24,00 €</td></tr>
                    <tr><td>13</td><td>17</td><td>25,00 €</td></tr>
                    <tr><td>14</td><td>18</td><td>27,00 €</td></tr>
                    <tr><td>15</td><td>20</td><td>30,00 €</td></tr>
                    <tr><td>16</td><td>21</td><td>31,00 €</td></tr>
                    <tr><td>17</td><td>22</td><td>33,00 €</td></tr>
                    <tr><td>18</td><td>24</td><td>36,00 €</td></tr>
                    <tr><td>19</td><td>25</td><td>37,00 €</td></tr>
                    <tr><td>20</td><td>26</td><td>39,00 €</td></tr>
                    <tr><td>21</td><td>28</td><td>42,00 €</td></tr>
                    <tr><td>22</td><td>29</td><td>43,00 €</td></tr>
                    <tr><td>23</td><td>30</td><td>45,00 €</td></tr>
                    <tr><td>24</td><td>32</td><td>48,00 €</td></tr>
                    <tr><td>25</td><td>33</td><td>49,00 €</td></tr>
                    <tr><td>26</td><td>34</td><td>51,00 €</td></tr>
                    <tr><td>27</td><td>36</td><td>54,00 €</td></tr>
                    <tr><td>28</td><td>37</td><td>55,00 €</td></tr>
                    <tr><td>29</td><td>38</td><td>57,00 €</td></tr>
                    <tr><td>30</td><td>40</td><td>60,00 €</td></tr>
                    <tr><td>31</td><td>40</td><td>60,00 €</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <footer class="footer">
        <div>
            <svg xmlns="http://www.w3.org/2000/svg" class=" w-8 h-8" fill="currentColor" class="bi bi-telephone" viewBox="0 0 16 16">
                <path d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.6 17.6 0 0 0 4.168 6.608 17.6 17.6 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.68.68 0 0 0-.58-.122l-2.19.547a1.75 1.75 0 0 1-1.657-.459L5.482 8.062a1.75 1.75 0 0 1-.46-1.657l.548-2.19a.68.68 0 0 0-.122-.58zM1.884.511a1.745 1.745 0 0 1 2.612.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.68.68 0 0 0 .178.643l2.457 2.457a.68.68 0 0 0 .644.178l2.189-.547a1.75 1.75 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.6 18.6 0 0 1-7.01-4.42 18.6 18.6 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877z"/>
            </svg>
            <p class="ml-2">Silvia Arcas - 651 926 502</p>
        </div>
        <div>
            <svg xmlns="http://www.w3.org/2000/svg" class=" w-8 h-8" fill="currentColor" class="bi bi-envelope-at" viewBox="0 0 16 16">
                <path d="M2 2a2 2 0 0 0-2 2v8.01A2 2 0 0 0 2 14h5.5a.5.5 0 0 0 0-1H2a1 1 0 0 1-.966-.741l5.64-3.471L8 9.583l7-4.2V8.5a.5.5 0 0 0 1 0V4a2 2 0 0 0-2-2zm3.708 6.208L1 11.105V5.383zM1 4.217V4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v.217l-7 4.2z"/>
                <path d="M14.247 14.269c1.01 0 1.587-.857 1.587-2.025v-.21C15.834 10.43 14.64 9 12.52 9h-.035C10.42 9 9 10.36 9 12.432v.214C9 14.82 10.438 16 12.358 16h.044c.594 0 1.018-.074 1.237-.175v-.73c-.245.11-.673.18-1.18.18h-.044c-1.334 0-2.571-.788-2.571-2.655v-.157c0-1.657 1.058-2.724 2.64-2.724h.04c1.535 0 2.484 1.05 2.484 2.326v.118c0 .975-.324 1.39-.639 1.39-.232 0-.41-.148-.41-.42v-2.19h-.906v.569h-.03c-.084-.298-.368-.63-.954-.63-.778 0-1.259.555-1.259 1.4v.528c0 .892.49 1.434 1.26 1.434.471 0 .896-.227 1.014-.643h.043c.118.42.617.648 1.12.648m-2.453-1.588v-.227c0-.546.227-.791.573-.791.297 0 .572.192.572.708v.367c0 .573-.253.744-.564.744-.354 0-.581-.215-.581-.8Z"/>
            </svg>
            <p class="ml-2">silvia.arcas@vrconsultores.es</p>
        </div>
        <div>
            <svg xmlns="http://www.w3.org/2000/svg" class=" w-8 h-8" fill="currentColor" class="bi bi-laptop" viewBox="0 0 16 16">
                <path d="M13.5 3a.5.5 0 0 1 .5.5V11H2V3.5a.5.5 0 0 1 .5-.5zm-11-1A1.5 1.5 0 0 0 1 3.5V12h14V3.5A1.5 1.5 0 0 0 13.5 2zM0 12.5h16a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 12.5"/>
            </svg>
            <p class="ml-2">www.vrconsultores.es</p>
        </div>
        <div>
            <img src="../public/logo2.png" alt="Logo">
        </div>
    </footer>
</body>
</html>
