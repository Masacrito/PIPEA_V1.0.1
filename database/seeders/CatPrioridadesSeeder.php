<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CatPrioridadesSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('cat_lineas_accion_prioridades')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // id_objetivo: 1-10 insertados en orden en CatObjetivosSeeder
        // Eje 01 → Obj 1 (id=1): prioridades 1-13
        // Eje 01 → Obj 2 (id=2): prioridad  14
        // Eje 02 → Obj 3 (id=3): prioridades 15-24
        // Eje 02 → Obj 4 (id=4): prioridades 25-28
        // Eje 02 → Obj 5 (id=5): prioridades 29-34
        // Eje 03 → Obj 6 (id=6): prioridades 35-43
        // Eje 03 → Obj 7 (id=7): prioridades 44-49
        // Eje 04 → Obj 8 (id=8): prioridades 50-61
        // Eje 04 → Obj 9 (id=9): prioridades 62-64
        // Eje 04 → Obj 10(id=10): prioridades 65-67

        $prioridades = [
            // ── Objetivo 1 ────────────────────────────────────────────────────
            [1,  1, 'Desarrollar e implementar un sistema único de información sobre compras, contrataciones y adquisiciones públicas, que incluya un padrón estatal de proveedores del gobierno y un sistema homologado de contrataciones públicas, vinculados al Sistema Electrónico Estatal.'],
            [1,  2, 'Promover el establecimiento de alianzas estratégicas entre universidades públicas, institutos y centros de investigación para conformar grupos estratégicos interdisciplinarios para realizar estudios de investigación en materia de corrupción, impunidad y ética pública.'],
            [1,  3, 'Fortalecer los mecanismos de protección a denunciantes, alertadores, testigos, personas servidoras públicas, peritos y víctimas de hechos de corrupción, a través de la instrumentación de aplicaciones móviles, correos electrónicos, páginas de internet oficiales que permitan el anonimato e integridad y protección del denunciante.'],
            [1,  4, 'Usar herramientas tecnológicas para capacitar a la ciudadanía en general sobre los procedimientos de denuncia para garantizar el anonimato de los denunciantes, así como para difundir el valor y la utilidad de la denuncia ciudadana.'],
            [1,  5, 'Promover la cultura de la denuncia basada en el respeto de los derechos.'],
            [1,  6, 'Proporcionar atención de intérpretes de lenguas indígenas a las personas de comunidades indígenas para que realicen denuncias, procurando una atención especial por posibles afectaciones a sus derechos.'],
            [1,  7, 'Elaborar protocolos específicos para prevenir actos de corrupción con perspectiva de género e intercultural.'],
            [1,  8, 'Crear criterios y mecanismos de medición con perspectiva de género e interculturalidad que reflejen los resultados de la gestión del Sistema Anticorrupción.'],
            [1,  9, 'Crear un sistema que permita la identificación de patrones y generen estadísticas públicas sobre los tipos de faltas administrativas o hechos de corrupción y su frecuencia.'],
            [1, 10, 'Generar indicadores con perspectiva de género e interculturalidad para fortalecer los mecanismos institucionales de rendición de cuentas, así como evaluar las políticas públicas para combatir la corrupción.'],
            [1, 11, 'Crear un sistema electrónico de seguimiento documental de la denuncia hasta su conclusión, en tiempo real con pertinencia cultural e información desagregada.'],
            [1, 12, 'Implementar políticas y estrategias efectivas a través de mecanismos de gobierno abierto y transparencia proactiva que fortalezcan el combate a la corrupción en materia de prevención, denuncia, detección, sustanciación e investigación de hechos de corrupción.'],
            [1, 13, 'Incorporar sistemas estandarizados e interoperables en los entes públicos orientados a la prevención y detección de faltas administrativas y delitos por hechos de corrupción.'],
            // ── Objetivo 2 ────────────────────────────────────────────────────
            [2, 14, 'Fortalecer a los recursos humanos y financieros en la investigación de delitos por hechos de corrupción.'],
            // ── Objetivo 3 ────────────────────────────────────────────────────
            [3, 15, 'Fortalecimiento de los procedimientos de designación de jueces y magistrados que garanticen la imparcialidad, autonomía, paridad e independencia judicial.'],
            [3, 16, 'Impulsar concursos públicos para la selección de titulares y personal de los órganos internos de control que privilegie la capacidad y trayectoria profesional con una base normativa estricta de selección y paridad de género.'],
            [3, 17, 'Profesionalizar a las personas servidoras públicas para las funciones que desempeña, así como para erradicar la corrupción y poder regular y mejorar los procesos de ingreso, promoción y permanencia de las personas trabajadoras al servicio del Estado.'],
            [3, 18, 'Fomentar la contratación de personas hablantes de lenguas originarias y/o traductores en las dependencias en donde existan puntos de contacto ciudadanía-gobierno.'],
            [3, 19, 'Rotación interna de las personas servidoras públicas del Poder Judicial y Fiscalía.'],
            [3, 20, 'Promover el diseño, implementación y evaluación del desempeño de programas de capacitación, certificación de capacidades y desarrollo profesional en el servicio público enfocadas a las funciones que desempeña, así como al control de la corrupción, responsabilidades administrativas y ética pública.'],
            [3, 21, 'Contar en la administración pública municipal con un servicio profesional o civil de carrera en áreas técnicas como la tesorería, el órgano interno de control y la unidad de transparencia.'],
            [3, 22, 'Diseñar un esquema de capacitación y profesionalización con pertinencia cultural y perspectiva de género de los órganos internos de control del nivel estatal y municipal.'],
            [3, 23, 'Fortalecer la base de datos de proveedores sancionados en la ejecución de contratos de obra pública y servicios relacionados con las mismas y que éstas se adapten a los esquemas de gobierno abierto.'],
            [3, 24, 'Promover la implementación de filtros de selección relevantes para las personas servidoras públicas de los órganos internos de control y áreas de responsabilidades, garantizando la experiencia e igualdad de oportunidades con base en el mérito.'],
            // ── Objetivo 4 ────────────────────────────────────────────────────
            [4, 25, 'Verificar la existencia, actualización y cumplimiento de la normatividad interna de los entes públicos, tales como los manuales de inducción o los catálogos de puestos.'],
            [4, 26, 'Publicar de manera clara, simple, completa en datos abiertos los resultados de las auditorías que realizan las autoridades competentes.'],
            [4, 27, 'Publicar las declaraciones de integridad de los contratistas.'],
            [4, 28, 'Impulsar la publicación de las leyes de ingresos y egresos de los Ayuntamientos y especificar los topes de los montos de contratación de bienes y servicios y obra pública.'],
            // ── Objetivo 5 ────────────────────────────────────────────────────
            [5, 29, 'Impulsar la consolidación y evaluación a escala estatal de los procesos de armonización contable, así como de mecanismos que promuevan el ejercicio de los recursos públicos con criterios de austeridad y disciplina financiera, bajo los principios de eficiencia y eficacia.'],
            [5, 30, 'Difundir de forma clara y accesible los procedimientos que realizan las entidades de fiscalización superior, particularmente sus atribuciones en materia de auditoría, investigación y publicación de informes de su revisión de la cuenta pública.'],
            [5, 31, 'Realizar evaluaciones de riesgos de corrupción dentro de los entes públicos.'],
            [5, 32, 'Ampliar las capacidades de análisis y facultades de investigación de los órganos fiscalizadores en temas como enriquecimiento ilícito, contrataciones de obra pública y adquisiciones, así como de conflicto de interés.'],
            [5, 33, 'Impulsar el desarrollo y utilización de metodologías de análisis de BigData e inteligencia artificial relacionadas con la identificación de riesgos, evaluación, mejora de la gestión, auditoría y fiscalización.'],
            [5, 34, 'Fortalecer los procesos de evaluación de las actividades que realizan los entes públicos para detectar posibles afectaciones de los derechos y en particular de las personas más desprotegidas.'],
            // ── Objetivo 6 ────────────────────────────────────────────────────
            [6, 35, 'Fortalecer mecanismos de evaluación de los programas presupuestarios con enfoques en derechos humanos, perspectiva de género y gestión de riesgos de corrupción para proponer acciones de mejora en el rediseño de programas sociales.'],
            [6, 36, 'Coordinar esfuerzos para incrementar el número y la calidad de los trámites y servicios gubernamentales ofrecidos por internet.'],
            [6, 37, 'Facilitar el uso de herramientas tecnológicas para transparentar información sobre trámites, uso de recursos públicos, datos sobre faltas administrativas y hechos de corrupción, la respuesta de las autoridades ante ellos y los procesos de prevención, investigación, detección y sanción de éstos.'],
            [6, 38, 'Facilitar a la sociedad el conocimiento a través de campañas de difusión masiva, acerca de los trámites y servicios públicos que son gratuitos y en línea con pertinencia cultural.'],
            [6, 39, 'Consolidar un gobierno sin archivos de papel que cumpla tanto con la Ley de Transparencia y Acceso a la Información, como con la Ley de Archivos.'],
            [6, 40, 'Promover la mejora, simplificación y homologación de los trámites y servicios públicos a través del desarrollo de sistemas de evaluación ciudadana y políticas de transparencia proactiva.'],
            [6, 41, 'Fomentar la colaboración interinstitucional y el intercambio de información (portabilidad de datos) que permitan un fortalecimiento y simplificación de los puntos de contacto gobierno-sociedad.'],
            [6, 42, 'Mejorar los portales de transparencia de los ayuntamientos y de otros entes públicos municipales con pertinencia cultural y grupos vulnerables.'],
            [6, 43, 'Colaborar con el organismo garante local, organizaciones de la sociedad civil e instancias públicas nacionales e internacionales para promover el cumplimiento de la normativa en los temas de transparencia, transparencia proactiva, gobierno abierto y protección de datos personales.'],
            // ── Objetivo 7 ────────────────────────────────────────────────────
            [7, 44, 'Impulsar una reforma a la Ley de Adquisiciones que regule la planeación, contratación, ejecución y auditoría de bienes y servicios con un enfoque para disminuir las adjudicaciones directas.'],
            [7, 45, 'Promover la creación, adopción de criterios y estándares unificados en las compras, contrataciones y adquisiciones públicas, que acoten espacios de arbitrariedad y mejoren su transparencia y fiscalización.'],
            [7, 46, 'Promover una reforma a la Ley de Obra Pública que atienda los procesos de planeación bajo lineamientos de participación ciudadana, contratación, ejecución y auditoría de las obras.'],
            [7, 47, 'Transparentar y hacer públicas en formato de datos abiertos las observaciones de las auditorías internas y externas realizadas por los órganos de fiscalización estatal.'],
            [7, 48, 'Explotar e implementar el estándar de contrataciones abiertas, asegurando que los sujetos obligados cumplan con la normativa, publicando información de calidad en tiempo y forma con un criterio previo para la ejecución del proceso licitatorio.'],
            [7, 49, 'Desarrollar e implementar políticas de gobierno abierto y transparencia proactiva que fortalezcan la rendición de cuentas y la contraloría social en materia de infraestructura u obra pública.'],
            // ── Objetivo 8 ────────────────────────────────────────────────────
            [8, 50, 'Fortalecer los procesos de selección de consejeras y consejeros de los órganos autónomos del estado, con los principios de máxima publicidad, transparencia, autonomía, equidad, parlamento abierto con mecanismos prácticos de fácil verificación por parte de la ciudadanía.'],
            [8, 51, 'Elaborar y aplicar anualmente una encuesta en materia de corrupción, impunidad y ética pública, dirigida a la ciudadanía y personas servidoras públicas.'],
            [8, 52, 'Garantizar que los criterios de elegibilidad de personas beneficiarias de los programas sociales sean claros, ampliamente difundidos con pertinencia cultural, perspectiva de género y efectivamente observados.'],
            [8, 53, 'Instrumentar mecanismos para que los ciudadanos, en particular la población vulnerable, pueda dar seguimiento y entender cómo se ejerce el presupuesto en tiempo real.'],
            [8, 54, 'Grabar, transmitir y difundir por medios oficiales las audiencias públicas del proceso de selección de las y los integrantes de los organismos constitucionales autónomos.'],
            [8, 55, 'Implementar observatorios ciudadanos.'],
            [8, 56, 'Fortalecer la contraloría social.'],
            [8, 57, 'Diseñar y poner en práctica un sistema de vigilancia ciudadana en el cumplimiento de criterios de selección de jueces, fiscales y titulares del órgano interno de control.'],
            [8, 58, 'Analizar la información emanada de las auditorías sociales, las quejas y los comentarios de la ciudadanía en general para realizar acciones de mejora que garanticen solventar y cumplir en los programas y proyectos donde se ejerzan recursos públicos.'],
            [8, 59, 'Realizar campañas de comunicación sobre la corrupción, sus costos, implicaciones y elementos disponibles para su combate.'],
            [8, 60, 'Fortalecer los Consejos de Participación Ciudadana autónomos e independientes.'],
            [8, 61, 'Crear espacios de consulta con la ciudadanía respecto a iniciativas legislativas.'],
            // ── Objetivo 9 ────────────────────────────────────────────────────
            [9, 62, 'Transmitir en vivo por redes sociales las juntas de los comités de obras y adquisiciones y alojarlos en las plataformas institucionales.'],
            [9, 63, 'Implementar con pertinencia cultural los mecanismos de participación ciudadana para vigilar y evaluar el desempeño de los comités de obras y adquisiciones y servicios públicos.'],
            [9, 64, 'Promover que la iniciativa privada proponga y acompañe en el diseño y elaboración de protocolos, diagnósticos y análisis en materia de corrupción.'],
            // ── Objetivo 10 ───────────────────────────────────────────────────
            [10, 65, 'Inculcar valores, clases de civismo y derechos humanos en las escuelas.'],
            [10, 66, 'Organizar y coordinar actividades y talleres en materia de combate a la corrupción.'],
            [10, 67, 'Establecer relaciones de colaboración con institutos de formación y organizaciones de la sociedad civil para diseñar los programas de capacitación.'],
        ];

        foreach ($prioridades as [$id_objetivo, $numero, $descripcion]) {
            DB::table('cat_lineas_accion_prioridades')->insert([
                'id_objetivo' => $id_objetivo,
                'numero'      => $numero,
                'prioridad'   => $descripcion,
                'activo'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    }
}
