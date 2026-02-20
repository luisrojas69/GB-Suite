<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MedCie10Seeder extends Seeder {
    public function run() {
        $diagnosticos = [
            // --- PREVENTIVOS Y ADMINISTRATIVOS (Z) ---
            ['codigo' => 'Z00.0', 'descripcion' => 'Examen médico general (Chequeo de rutina)'],
            ['codigo' => 'Z02.7', 'descripcion' => 'Expedición de certificado médico (Vacaciones/Aptitud)'],
            ['codigo' => 'Z10.0', 'descripcion' => 'Examen de salud ocupacional de rutina'],
            ['codigo' => 'Z00.8', 'descripcion' => 'Otros exámenes generales (Pre-empleo/Periódico)'],
            ['codigo' => 'Z04.2', 'descripcion' => 'Examen por accidente de trabajo'],
            ['codigo' => 'Z04.3', 'descripcion' => 'Examen por otro traumatismo (Urgencias)'],
            ['codigo' => 'Z54.0', 'descripcion' => 'Convalecencia después de cirugía'],
            ['codigo' => 'Z57.1', 'descripcion' => 'Exposición ocupacional a radiación'],
            ['codigo' => 'Z57.3', 'descripcion' => 'Exposición ocupacional a polvo'],
            ['codigo' => 'Z57.7', 'descripcion' => 'Exposición ocupacional a vibraciones'],
            ['codigo' => 'Z71.3', 'descripcion' => 'Asesoría y vigilancia nutricional'],
            ['codigo' => 'Z73.0', 'descripcion' => 'Agotamiento (Burnout/Problemas relacionados con el estilo de vida)'],

            // --- MÚSCULO-ESQUELÉTICOS / ERGONOMÍA (M, G) ---
            ['codigo' => 'M54.5', 'descripcion' => 'Lumbago no especificado (Dolor de espalda)'],
            ['codigo' => 'M54.2', 'descripcion' => 'Cervicalgia'],
            ['codigo' => 'G56.0', 'descripcion' => 'Síndrome del túnel carpiano'],
            ['codigo' => 'M75.1', 'descripcion' => 'Síndrome del manguito rotador'],
            ['codigo' => 'M77.1', 'descripcion' => 'Epicondilitis lateral (Codo de tenista)'],
            ['codigo' => 'M77.0', 'descripcion' => 'Epicondilitis medial (Codo de golfista)'],
            ['codigo' => 'M65.4', 'descripcion' => 'Tenosinovitis de estiloides radial (De Quervain)'],
            ['codigo' => 'M54.4', 'descripcion' => 'Lumbago con ciática'],
            ['codigo' => 'M51.2', 'descripcion' => 'Otros desplazamientos de disco intervertebral especificados'],
            ['codigo' => 'M79.1', 'descripcion' => 'Mialgia'],
            ['codigo' => 'M79.7', 'descripcion' => 'Fibromialgia'],
            ['codigo' => 'M25.5', 'descripcion' => 'Dolor en articulación'],
            ['codigo' => 'G54.0', 'descripcion' => 'Trastornos del plexo braquial'],
            ['codigo' => 'M50.1', 'descripcion' => 'Trastorno de disco cervical con radiculopatía'],
            ['codigo' => 'M70.2', 'descripcion' => 'Bursitis olecraniana'],
            ['codigo' => 'M72.2', 'descripcion' => 'Fibromatosis de la fascia plantar (Fascitis)'],

            // --- TRAUMATISMOS Y ACCIDENTES (S, T) ---
            ['codigo' => 'S61.0', 'descripcion' => 'Herida de dedo(s) de la mano sin daño de la uña'],
            ['codigo' => 'T14.0', 'descripcion' => 'Traumatismo superficial no especificado'],
            ['codigo' => 'S93.4', 'descripcion' => 'Esguince y torcedura de los tobillos'],
            ['codigo' => 'S60.2', 'descripcion' => 'Contusión de otras partes de la muñeca y de la mano'],
            ['codigo' => 'S62.6', 'descripcion' => 'Fractura de otros dedos de la mano'],
            ['codigo' => 'S52.5', 'descripcion' => 'Fractura de la epífisis inferior del radio'],
            ['codigo' => 'S83.6', 'descripcion' => 'Esguince y torcedura de otras partes de la rodilla'],
            ['codigo' => 'S00.0', 'descripcion' => 'Traumatismo superficial del cuero cabelludo'],
            ['codigo' => 'T15.0', 'descripcion' => 'Cuerpo extraño en la córnea'],
            ['codigo' => 'S06.0', 'descripcion' => 'Conmoción cerebral'],
            ['codigo' => 'S42.0', 'descripcion' => 'Fractura de la clavícula'],
            ['codigo' => 'S33.5', 'descripcion' => 'Esguinces y torceduras de la columna lumbar'],
            ['codigo' => 'T30.0', 'descripcion' => 'Quemadura de región del cuerpo no especificada'],
            ['codigo' => 'T23.2', 'descripcion' => 'Quemadura de segundo grado de la muñeca y de la mano'],
            ['codigo' => 'S61.9', 'descripcion' => 'Herida de la muñeca y de la mano, parte no especificada'],
            ['codigo' => 'S90.1', 'descripcion' => 'Contusión de dedo(s) del pie sin daño de la uña'],
            ['codigo' => 'T79.3', 'descripcion' => 'Infección postraumática de herida, no clasificada'],

            // --- ENFERMEDADES COMUNES Y RESPIRATORIAS (J, A, I, E) ---
            ['codigo' => 'J00',   'descripcion' => 'Rinofaringitis aguda (Resfriado común)'],
            ['codigo' => 'I10',   'descripcion' => 'Hipertensión esencial (primaria)'],
            ['codigo' => 'E11',   'descripcion' => 'Diabetes mellitus no insulinodependiente'],
            ['codigo' => 'A09',   'descripcion' => 'Diarrea y gastroenteritis de presunto origen infeccioso'],
            ['codigo' => 'J02.9', 'descripcion' => 'Faringitis aguda, no especificada'],
            ['codigo' => 'J03.9', 'descripcion' => 'Amigdalitis aguda, no especificada'],
            ['codigo' => 'J30.4', 'descripcion' => 'Rinitis alérgica, no especificada'],
            ['codigo' => 'J45.0', 'descripcion' => 'Asma predominantemente alérgica'],
            ['codigo' => 'B35.3', 'descripcion' => 'Tinea pedis (Pie de atleta)'],
            ['codigo' => 'E66.9', 'descripcion' => 'Obesidad, no especificada'],
            ['codigo' => 'E78.5', 'descripcion' => 'Hiperlipidemia, no especificada (Colesterol/Triglicéridos)'],
            ['codigo' => 'I83.9', 'descripcion' => 'Venas varicosas de los miembros inferiores sin úlcera ni inflamación'],
            ['codigo' => 'K21.9', 'descripcion' => 'Enfermedad del reflujo gastroesofágico sin esofagitis'],
            ['codigo' => 'K29.7', 'descripcion' => 'Gastritis, no especificada'],

            // --- SALUD MENTAL Y FACTORES PSICOSOCIALES (F) ---
            ['codigo' => 'F43.0', 'descripcion' => 'Reacción al estrés agudo'],
            ['codigo' => 'F41.1', 'descripcion' => 'Ansiedad generalizada'],
            ['codigo' => 'F43.2', 'descripcion' => 'Trastornos de adaptación'],
            ['codigo' => 'F32.9', 'descripcion' => 'Episodio depresivo, no especificado'],
            ['codigo' => 'F51.0', 'descripcion' => 'Insomnio no orgánico'],

            // --- DERMATOLOGÍA OCUPACIONAL (L) ---
            ['codigo' => 'L23.9', 'descripcion' => 'Dermatitis alérgica de contacto, de causa no especificada'],
            ['codigo' => 'L24.9', 'descripcion' => 'Dermatitis de contacto por irritantes, de causa no especificada'],
            ['codigo' => 'L70.9', 'descripcion' => 'Acné, no especificado'],
            ['codigo' => 'L50.9', 'descripcion' => 'Urticaria, no especificada'],

            // --- OFTALMOLOGÍA Y OTORRINO (H) ---
            ['codigo' => 'H10.1', 'descripcion' => 'Conjuntivitis atópica aguda'],
            ['codigo' => 'H10.9', 'descripcion' => 'Conjuntivitis, no especificada'],
            ['codigo' => 'H53.1', 'descripcion' => 'Alteraciones visuales subjetivas (Astenopía/Fatiga visual)'],
            ['codigo' => 'H83.3', 'descripcion' => 'Efectos del ruido sobre el oído interno (Hipoacusia inducida por ruido)'],
            ['codigo' => 'H60.9', 'descripcion' => 'Otitis externa, no especificada'],
            ['codigo' => 'H61.2', 'descripcion' => 'Cerumen impactado (Tapón de cera)'],

            // --- OTROS SÍNTOMAS Y HALLAZGOS (R) ---
            ['codigo' => 'R51',   'descripcion' => 'Cefalea (Dolor de cabeza)'],
            ['codigo' => 'R53',   'descripcion' => 'Malestar y fatiga'],
            ['codigo' => 'R05',   'descripcion' => 'Tos'],
            ['codigo' => 'R10.4', 'descripcion' => 'Otros dolores abdominales y los no especificados'],
            ['codigo' => 'R42',   'descripcion' => 'Mareo y desvanecimiento'],
            ['codigo' => 'R06.0', 'descripcion' => 'Disnea (Dificultad respiratoria)'],

            // --- ADICIONALES FRECUENTES EN CAMPO / MINERÍA / CONSTRUCCIÓN ---
            ['codigo' => 'B30.9', 'descripcion' => 'Conjuntivitis viral, sin otra especificación'],
            ['codigo' => 'S93.6', 'descripcion' => 'Esguince y torcedura de pie'],
            ['codigo' => 'S60.0', 'descripcion' => 'Contusión de dedo(s) de la mano sin daño de la uña'],
            ['codigo' => 'L03.0', 'descripcion' => 'Celulitis de dedos de la mano o de los pies'],
            ['codigo' => 'M76.8', 'descripcion' => 'Otras entesopatías del miembro inferior (Síndrome tibial)'],
            ['codigo' => 'N39.0', 'descripcion' => 'Infección de vías urinarias, sitio no especificado'],
            ['codigo' => 'M22.4', 'descripcion' => 'Condromalacia de la rótula'],
            ['codigo' => 'G43.9', 'descripcion' => 'Migraña, no especificada'],
            ['codigo' => 'K40.9', 'descripcion' => 'Hernia inguinal unilateral o no especificada, sin obstrucción ni gangrena'],
            ['codigo' => 'M17.9', 'descripcion' => 'Osteoartrosis de la rodilla, no especificada'],
            ['codigo' => 'M19.9', 'descripcion' => 'Artrosis, no especificada'],
            ['codigo' => 'T75.4', 'descripcion' => 'Efectos de la corriente eléctrica'],
            ['codigo' => 'T67.0', 'descripcion' => 'Insolación y golpe de calor'],
            ['codigo' => 'J01.9', 'descripcion' => 'Sinusitis aguda, no especificada'],
            ['codigo' => 'L02.9', 'descripcion' => 'Absceso cutáneo, furúnculo y ántrax de sitio no especificado'],
            ['codigo' => 'S30.0', 'descripcion' => 'Contusión de la región lumbosacra y de la pelvis'],
            ['codigo' => 'S50.0', 'descripcion' => 'Contusión del codo'],
            ['codigo' => 'M79.6', 'descripcion' => 'Dolor en miembro (brazo, pierna)'],
            ['codigo' => 'S13.4', 'descripcion' => 'Esguince y torcedura de la columna cervical (Latigazo cervical)'],
            ['codigo' => 'Z71.2', 'descripcion' => 'Persona que consulta para la explicación de hallazgos de exámenes'],
        ];
        foreach ($diagnosticos as $diag) {
            DB::table('med_cie10')->updateOrInsert(['codigo' => $diag['codigo']], $diag);
        }
    }
}