-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-04-2026 a las 11:58:05
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `medicos_mundo`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `blog`
--

CREATE TABLE `blog` (
  `id` int(255) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `contenido` text NOT NULL,
  `url_icono` varchar(255) NOT NULL,
  `url_extra` varchar(255) NOT NULL,
  `fecha_modificacion` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `blog`
--

INSERT INTO `blog` (`id`, `titulo`, `descripcion`, `contenido`, `url_icono`, `url_extra`, `fecha_modificacion`) VALUES
(1, 'Tu futuro laboral en España: Un camino que recorremos juntas', 'Psicólogas y orientadoras expertas impulsando juntas tu propia trayectoria.', 'Es un verdadero honor darte la bienvenida a este nuevo espacio. Inauguramos este blog con la ilusión de convertirnos en tu punto de referencia, una mano amiga y una fuente de información fiable en una de las etapas más determinantes de la vida: la conquista de tu lugar en el mundo del trabajo.\r\n\r\nSomos Médicas del Mundo, y aunque nuestra trayectoria siempre ha estado ligada a la salud, entendemos que el bienestar integral es imposible sin una estabilidad vital. Por ello, estamos plenamente convencidas de que el acceso a un empleo digno es un derecho fundamental que permite a cada una de nosotras ser dueñas de su propio destino.\r\n\r\n¿A quiénes acompañamos en este trayecto?\r\nEste rincón ha sido diseñado pensando en todas aquellas que os encontráis en diferentes momentos de vuestra trayectoria profesional en España. Nuestra meta es ofrecer herramientas precisas para:\r\n\r\nLa reincorporación laboral: Para aquellas que, tras una pausa por cuidados, salud o motivos personales, se sienten preparadas para retomar su actividad y necesitan actualizarse.\r\n\r\nLa mejora de la situación actual: Para las que ya están trabajando pero buscan condiciones más justas, una mayor satisfacción o un cambio de rumbo que las haga sentir realizadas.\r\n\r\nLa incorporación inicial: Especialmente para quienes acaban de llegar o se enfrentan por primera vez al mercado de trabajo español, enfrentando las barreras de la burocracia y la falta de red.\r\n\r\nUn equipo de expertas a tu disposición\r\nNo estás sola en esto. Para que este proceso sea sólido, contamos con una red de profesionales entregadas y con una sensibilidad especial hacia las realidades que nos atraviesan. Nuestras psicólogas y orientadoras forman el núcleo de este acompañamiento.\r\n\r\nNuestras psicólogas no solo se centran en la salud emocional, sino en fortalecer la confianza y la seguridad que a veces se ven mermadas tras largas búsquedas o experiencias negativas. Se encargan de que cada una de vosotras se sienta empoderada, reconocida en sus propias capacidades y lista para afrontar cualquier entrevista con la cabeza alta.\r\n\r\nPor otro lado, nuestras orientadoras laborales son las arquitectas de tu estrategia de búsqueda. Son expertas en el mercado español y conocen a fondo los entresijos de la formación profesional, la convalidación de estudios y la creación de redes de contacto. Ellas te guiarán en el diseño de una hoja de ruta personalizada, asegurándose de que cada paso que des sea firme y seguro.\r\n\r\n¿Qué hacemos exactamente por ti?\r\nNuestra labor es profunda y diversificada. Trabajamos desde la escucha activa para entender cuáles son tus barreras y cómo transformarlas en oportunidades. A través de este blog y de nuestras intervenciones directas, nos dedicamos a:\r\n\r\nAnalizar el mercado laboral: Identificamos dónde están las oportunidades reales y qué sectores están demandando nuevas profesionales.\r\n\r\nFormación y capacitación: Te informamos sobre los cursos y las competencias más valoradas actualmente para que estés siempre a la vanguardia.\r\n\r\nApoyo en trámites y derechos: No queremos que ninguna se sienta perdida entre papeles. Explicamos de forma sencilla tus derechos como trabajadora para que nadie pueda aprovecharse de tu situación.\r\n\r\nCuidado emocional: Entendemos que buscar trabajo es, en sí mismo, un trabajo agotador. Por eso, nuestras sesiones de apoyo son espacios seguros donde compartir miedos y renovar fuerzas.\r\n\r\nEstamos aquí porque creemos en tu potencial, en tu fuerza y en tu derecho a prosperar. Esperamos que cada artículo, cada consejo y cada palabra que compartamos aquí sea una semilla que florezca en una vida laboral plena y satisfactoria.\r\n\r\n¡Bienvenida a este viaje hacia tu autonomía profesional! ¿Hay algún tema específico sobre la búsqueda de empleo en España que te gustaría que nuestras expertas trataran en el próximo artículo?', 'https://roncesvalleszubiri.com/wp-content/uploads/2018/10/ESTAND-MEDICOS-DEL-MUNDO.jpg', 'https://www.medicosdelmundo.org/', '2026-04-15');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bloque`
--

CREATE TABLE `bloque` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `id_categoria` int(11) NOT NULL,
  `contenido` text NOT NULL,
  `url_oficial` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `bloque`
--

INSERT INTO `bloque` (`id`, `titulo`, `descripcion`, `id_categoria`, `contenido`, `url_oficial`) VALUES
(2, 'Tipos de Jornada', 'Conoce que tipos de Jornada existen', 4, 'Entender cuánto tiempo dedicas a tu empleo es fundamental para organizar tu vida personal y familiar. La jornada de trabajo es ese tiempo (diario, semanal o anual) que como trabajadora dedicas a tus tareas y a lo que solicita tu jefa o jefe, siempre según lo que hayáis pactado de mutuo acuerdo en tu contrato.\r\nRecuerda que, por ley, el máximo suele ser de 40 horas semanales de promedio. Aquí te explicamos cómo pueden ser tus horarios:\r\nJornada Completa: Trabajas las 40 horas semanales estipuladas.\r\nJornada Parcial: Realizas menos de 40 horas a la semana y, por tanto, tu sueldo será proporcional a esas horas.\r\nJornada Continua: Trabajas de forma ininterrumpida, aunque tienes derecho a un descanso de entre 15 y 30 minutos para comer o desconectar.\r\nJornada Discontinua (Partida): Tu día se divide en dos bloques, separados por un descanso largo (mínimo de una hora) para comer.\r\nJornada Reducida: Si eres cuidadora de menores o familiares, puedes solicitar reducir tu horario (entre un 1/8 y un 1/2 de la jornada). Ten en cuenta que esto reducirá tu sueldo de forma proporcional (entre un 12,5% y un 50%).\r\nTrabajo a Turnos: Rotarás en diferentes horarios (mañana, tarde y noche) ocupando el mismo puesto de forma sucesiva. \r\n\r\n¿Cómo mirarlo en tu contrato?\r\nBusca siempre el apartado de \"Jornada\" o \"Tiempo de trabajo\". Allí debe especificar claramente el número de horas y si tu jornada es completa o parcial. Si tienes dudas, nuestras orientadoras pueden ayudarte a revisarlo.\r\n', 'https://www.mites.gob.es/es/Guia/texto/guia_6/index.htm'),
(3, '¿Qué mirar en tu contrato?', 'Todo lo que debes saber antes de firmar', 6, 'Firmar un contrato es el paso que te vincula formalmente con una empresa. Como trabajadora, estás protegida por el Estatuto de los Trabajadores siempre que tu relación sea voluntaria, personal (lo haces tú misma), retribuida (cobras un sueldo) y dependiente (sigues instrucciones de la empresa).\r\nEl Periodo de Prueba\r\nEs ese tiempo inicial para ver si encajas en el puesto. Lo más importante: se cobra igual y ambas partes podéis romper la relación sin preaviso ni indemnización.\r\n\r\nSi eres técnica titulada: Hasta 6 meses.\r\n\r\nPara el resto de trabajadoras: Hasta 2 meses (o 3 en empresas pequeñas).\r\n\r\nTipos de Contratos que te pueden ofrecer:\r\n\r\nIndefinido: No tiene fecha de fin. Es el que más estabilidad te da.\r\n\r\n\r\nFijo-Discontinuo: Eres parte de la plantilla fija, pero solo trabajas en épocas específicas (como campañas agrícolas o turísticas).\r\n\r\n\r\nTemporal por circunstancias: Para \"picos\" de trabajo inesperados.\r\n\r\n\r\nDe Sustitución: Para cubrir la baja de otra compañera; en el contrato debe figurar el nombre de la persona a la que sustituyes.\r\nTus derechos mínimos:\r\n\r\n\r\nSMI: Lo mínimo que puedes cobrar en 2026 por jornada completa son 1.221€ brutos mensuales (en 14 pagas).\r\n\r\n\r\nFiniquito: Si te vas, siempre deben pagarte las vacaciones que no hayas disfrutado y la parte de las pagas extra que ya hayas generado.', 'https://www.sepe.es/HomeSepe/que-es-el-sepe/comunicacion-institucional/publicaciones/publicaciones-oficiales/listado-pub-empleo/guia-contratos.html'),
(4, 'Formación Profesional Básica', 'Aprende a inscribirte en FPB', 8, 'Si por alguna razón no pudiste terminar la secundaria en su momento, no te preocupes; nunca es tarde para retomar tu camino. Esta formación está pensada para que te sientas capacitada y obtengas una base sólida en un oficio mientras consigues tu título de Graduada en ESO.\r\n\r\n¿Qué es? Es una formación de dos años que combina teoría y práctica en un entorno muy real.\r\n\r\n¿Quiénes pueden acceder? Generalmente se accede entre los 15 y 17 años, pero existen ofertas específicas para mujeres adultas que quieren reincorporarse al sistema educativo sin tener el título de la ESO.\r\n\r\n¿Qué obtienes? Al finalizar, serás una Técnica Básica en tu especialidad y, además, obtendrás directamente el título de la ESO.\r\n\r\nDocumentación y pasos para tu matrícula:\r\n\r\nDNI, NIE o Pasaporte vigente.\r\n\r\nCertificado de estudios del último curso que realizaste.\r\n\r\nFormulario de solicitud: Cada Comunidad Autónoma tiene sus propios plazos (suelen ser entre mayo y julio).', 'https://www.todofp.es/que-estudiar/grados-d/fp-grado-basico.html'),
(5, 'Formación Profesional de Grado Medio', 'Conviértete en una Técnica Especialista', 9, 'Esta es la opción ideal si ya tienes tu título de secundaria o equivalente y quieres aprender una profesión de forma muy práctica. Al ser una estudiante de Grado Medio, te sumergirás en el mercado laboral desde el primer día.\r\n\r\nRequisitos de acceso:\r\n\r\nTener el título de la ESO o uno superior.\r\n\r\nO bien, haber superado la prueba de acceso a Grado Medio (debes tener al menos 17 años).\r\n\r\nDocumentación: Título de la ESO (original y copia) y tu documento de identidad.\r\nEn el botón de abajo puedes ver una lista con los diferentes FPGM que hay disponibles.', 'https://todofp.es/que-estudiar/grados-d/grado-medio.html'),
(6, 'Formación Profesional de Grado Superior', 'Da el salto a Técnica Superior', 10, 'Si buscas una formación con mayor responsabilidad y una base teórica más profunda, el Grado Superior es para ti. Como futura técnica superior, estarás a un paso de puestos de mando intermedio o incluso de la Universidad.\r\n\r\nRequisitos de acceso:\r\n\r\nTener el título de Bachillerato o equivalente.\r\n\r\nO tener un título de Técnica (Grado Medio).\r\n\r\nO haber superado la prueba de acceso a Grado Superior (debes tener 19 años).\r\n\r\nDocumentación: Título de Bachiller o título de Grado Medio, junto a tu identificación.', 'https://www.todofp.es/que-estudiar/grados-d/grado-superior.html'),
(7, 'Universidad para Adultas', 'Tu meta universitaria a cualquier edad', 11, 'Si tu sueño siempre fue ser una graduada universitaria, en España tienes vías específicas para entrar aunque no hicieras el examen de selectividad en su momento. Lo que más se valora es tu madurez e ilusión.\r\n\r\nPrueba para mayores de 25 años: Si tienes esta edad o la cumples en el año del examen. Consta de una fase general (lengua, comentario de texto e idioma) y una específica de la carrera que elijas.\r\n\r\nPrueba para mayores de 45 años: Si no tienes experiencia laboral acreditable en el sector pero quieres estudiar, esta prueba es más sencilla y suele incluir una entrevista personal para valorar tu idoneidad.\r\n\r\nDocumentación:\r\n\r\nInscripción: Suele realizarse en los meses de enero o febrero en la universidad donde quieras examinarte.\r\n\r\nIdentificación: DNI o NIE.', 'https://academico.unizar.es/acceso-admision-grado/acceso-mayores-25/pam25'),
(8, 'Homologación de Títulos Extranjeros', 'Que tu esfuerzo en el extranjero brille aquí', 12, '\r\nSabemos que muchas de vosotras venís con una mochila llena de conocimientos y estudios de vuestros países de origen. No queremos que ese esfuerzo se pierda. Homologar tu título es el proceso para que tu diploma sea reconocido oficialmente en España.\r\n\r\n¿Qué puedes hacer con tu título reconocido?\r\n\r\nHomologación a la ESO: Te permite acceder a cualquier FP de Grado Medio.\r\n\r\nHomologación al Bachillerato: Te abre las puertas de la FP Superior y de la Universidad.\r\n\r\nPasos que debes seguir:\r\n\r\nLegalización: Tu título debe estar legalizado (normalmente con la Apostilla de la Haya).\r\n\r\nTraducción: Si no está en castellano, debe ser traducido por una traductora jurada.\r\n\r\nSolicitud: Se hace a través de la sede electrónica del Ministerio. ¡Atención! La homologación al título de la ESO es gratuita.', 'https://www.educacionfpydeportes.gob.es/mc/convalidacion-homologacion/convalidacion-no-universitaria.html'),
(9, 'Cómo crear un CV ', 'Tu carta de presentación: Mucho más que un papel', 14, 'Tu currículum es la primera imagen que una empresa tendrá de ti. No lo veas solo como una lista de sitios donde has estado, sino como la oportunidad de demostrar que eres la profesional preparada que están buscando. Queremos que, al leerlo, cualquier reclutadora sienta tu valía y tu potencial.\r\n\r\n1. Una estructura clara y visual\r\nPara que no pasen de largo tu perfil, la información debe estar organizada. Te recomendamos este orden:\r\n\r\nEncabezado profesional: Tu nombre debe resaltar. Incluye tu teléfono, un correo electrónico serio y, si tienes, tu perfil de LinkedIn. No es obligatorio poner foto ni dirección exacta si no te sientes cómoda; lo que importa es tu talento.\r\n\r\nTu extracto o \"Sobre mí\": Escribe tres o cuatro líneas en femenino que resuman quién eres. Por ejemplo: \"Soy una administrativa organizada y resolutiva, con gran capacidad de aprendizaje y apasionada por el trato con el público\".\r\n\r\nTu trayectoria (Experiencia): Ordénala de la más reciente a la más antigua. Describe tus funciones de forma activa: \"Encargada de la gestión...\", \"Responsable de la atención a...\".\r\n\r\nEl valor de los cuidados: Si has hecho una pausa laboral para cuidar de tus hijos o de personas mayores, ¡no lo escondas! Esas vivencias te han convertido en una mujer con gran capacidad de gestión, paciencia y resiliencia. Puedes incluirlo como \"Gestión de cuidados y logística familiar\", resaltando tus habilidades organizativas.\r\n\r\nFormación y habilidades: Añade tus títulos y no olvides tus \"habilidades blandas\" (ser empática, puntual, saber trabajar en equipo con otras compañeras).\r\n\r\n2. Herramientas para que brille\r\nNo necesitas ser una experta en diseño. Puedes usar plantillas gratuitas que harán el trabajo por ti:\r\n\r\nCanva: Es muy intuitiva y tiene secciones específicas para \"Currículum\". Elige una que sea limpia y fácil de leer.\r\n\r\nEuropass: Si buscas empleo en instituciones públicas o en el extranjero, este es el modelo oficial europeo.\r\n\r\nConsejo de nuestras orientadoras: Intenta que tu CV no ocupe más de una página. Sé breve, clara y directa. ¡Tú puedes!', 'https://europass.europa.eu/es/create-europass-cv'),
(10, 'Preparando tu entrevista de trabajo', 'Confianza y seguridad: El momento de brillar', 15, 'Llegar a la entrevista significa que ya les has interesado. Ahora, el objetivo es confirmarles que eres la candidata ideal. Entendemos que puedas sentirte nerviosa, pero recuerda: tú también estás evaluando si esa empresa es el lugar donde quieres crecer.\r\n\r\n1. Antes de la cita: La preparación es tu fuerza\r\nInvestiga la empresa: Entra en su web. ¿Qué hacen? ¿Cuáles son sus valores? Cuanto más sepas, más segura te sentirás al hablar con ellas.\r\n\r\nEnsaya tu historia: Practica cómo contar tu experiencia en femenino. Si te preguntan por un bache en tu CV, responde con honestidad y positividad: \"Durante ese tiempo me dediqué a mi formación personal y al cuidado de mi familia, lo que reforzó mi capacidad de organización\".\r\n\r\nTu imagen, tu elección: No hay una norma fija, pero nuestras psicólogas recomiendan que elijas ropa con la que te sientas tú misma, cómoda y profesional. La seguridad empieza por sentirte bien con tu propia piel.\r\n\r\n2. Durante la entrevista: Comunicación y actitud\r\nLenguaje corporal: Intenta mantener una postura relajada pero firme. Mira a los ojos, sonríe cuando sea oportuno y escucha con atención.\r\n\r\nHabla de tus logros: No tengas miedo de decir: \"Fui la responsable de...\" o \"Logré mejorar la eficiencia en...\". Es el momento de reconocer tu propio mérito.\r\n\r\nHaz preguntas: Al final, pregunta sobre el equipo de trabajo, si hay posibilidades de formación o cómo es el día a día. Esto demuestra que eres una mujer proactiva e interesada en el proyecto.\r\n\r\n3. El cierre: Deja una huella positiva\r\nAl terminar, agradece el tiempo que te han dedicado. Si te sientes con fuerzas, puedes enviar un breve correo electrónico un par de horas después agradeciendo la oportunidad. Es un detalle de cortesía que te hará destacar como una profesional detallista.\r\n\r\nRecuerda: Cada entrevista es un aprendizaje. Si no te llaman esta vez, no significa que no seas válida; simplemente significa que tu oportunidad perfecta está un poco más adelante.', 'https://www.sepe.es/HomeSepe/encontrar-trabajo.html'),
(11, 'Derechos Principales', 'Conoce tus derechos generales', 19, 'Como trabajadora, tienes una serie de derechos que nadie puede quitarte porque están protegidos por las leyes más importantes de este país. Son tu punto de partida y la garantía de que eres una mujer libre dentro de tu puesto de trabajo.\r\nEl primero y más fundamental es el derecho a elegir libremente tu profesión o tu oficio. Esto significa que nadie puede obligarte a hacer un trabajo que tú no hayas aceptado o para el que no hayas dado tu consentimiento. Tu voluntad siempre es lo primero.\r\nTambién tienes derecho a la negociación colectiva. Puede sonar complicado, pero en realidad es algo muy sencillo y muy poderoso: significa que puedes unirte a tus compañeras de trabajo para negociar juntas mejores condiciones laborales, ya sea el sueldo, los descansos o la seguridad. Cuando vais en grupo, tenéis mucha más fuerza que por separado, y la empresa está obligada a escucharos.\r\nSi las cosas van mal y sientes que no hay otra salida, tienes derecho a la huelga y a tomar medidas de conflicto colectivo para que tu voz sea escuchada. Y lo más importante: la empresa no puede castigarte ni despedirte por ejercer este derecho. Además, tienes derecho de reunión, lo que te permite juntarte con tus compañeras para hablar abiertamente sobre vuestra situación en la empresa y tomar decisiones juntas.\r\nEstos derechos fundamentales existen para recordarte que no eres una simple empleada. Eres una parte esencial de la empresa, con voz, con capacidad de decisión y con protección legal total frente a cualquier abuso de poder.', 'https://www.boe.es/buscar/act.php?id=BOE-A-2015-11430'),
(12, 'Discriminación', 'Aprende a ver para que no te discriminen', 20, 'La ley es muy clara en este punto: tienes el derecho absoluto a no ser discriminada. Ni directa ni indirectamente. Esto abarca muchísimas situaciones que quizás no sabías que estaban protegidas.\r\nNadie puede darte peores condiciones de trabajo, negarte un puesto de trabajo, pagarte menos o tratarte de forma diferente por el simple hecho de ser mujer. Tampoco pueden hacerlo por tu estado civil, por tu edad, por tus creencias o por cualquier otra característica personal. La ley prohíbe todo esto sin excepción.\r\nUno de los derechos más importantes dentro de este bloque es el de la igualdad retributiva. Significa que si tú y un compañero hombre realizáis el mismo trabajo o un trabajo de igual valor, la empresa está obligada a pagaros exactamente lo mismo. No solo en el sueldo base, sino también en todos los complementos y pluses que pueda haber. Si cobras menos que él sin ninguna justificación objetiva, eso es ilegal.\r\nEste derecho también te protege como madre. Tu carrera no puede verse frenada ni pueden reducirse tus posibilidades de ascenso por el hecho de tener hijos o de haberte cogido una baja de maternidad. Ser madre no puede costarte oportunidades laborales.\r\nPor último, este bloque incluye la protección total frente al acoso sexual y al acoso por razón de sexo. La empresa tiene la obligación de garantizar que trabajas en un entorno libre de comentarios inapropiados, gestos ofensivos o cualquier trato humillante o intimidatorio. Proteger tu dignidad no es una opción para ellos, es una obligación legal.', 'https://www.boe.es/buscar/act.php?id=BOE-A-2007-6115'),
(13, 'Derechos Laborales Básicos', 'Conoce tus derechos del día a día ', 21, 'Los derechos laborales son los que regulan lo que ocurre en tu trabajo cada día y aseguran que nadie pisotee tu esfuerzo ni tu profesionalidad.\r\nEl más importante de todos es el derecho a la ocupación efectiva. Esto quiere decir que la empresa no solo tiene que pagarte a final de mes, sino que está obligada a darte trabajo real, tareas concretas y acordes a tu categoría profesional. No pueden dejarte sin funciones, ignorarte o apartarte como forma de castigo o presión. Eso no solo es injusto, es ilegal, porque daña directamente tu valor y tu desarrollo como profesional.\r\nTambién tienes derecho a la promoción profesional. Si llevas tiempo en la empresa, haces bien tu trabajo y tienes méritos, tienes derecho a ascender y mejorar tu categoría. La empresa no puede bloquearte el camino de forma injustificada.\r\nDentro de este bloque también se incluye tu derecho a la integridad física, es decir, a trabajar en condiciones que no pongan en peligro tu salud ni tu seguridad. Y si en algún momento sientes que tu contrato no se está cumpliendo, tienes total libertad para reclamar ante los tribunales o ante la Inspección de Trabajo. La ley incluso te protege específicamente en este caso: se llama Garantía de Indemnidad, y significa que si reclamar tus derechos de forma legal, la empresa tiene prohibido despedirte o sancionarte como consecuencia. Puedes alzar la voz sin miedo', 'https://www.boe.es/buscar/act.php?id=BOE-A-2015-11430'),
(14, 'Derechos de Formación', 'Aprende tus derechos antes de formarte', 22, 'Tu crecimiento profesional importa, y la ley lo reconoce. Tienes derecho a la formación profesional para el empleo, lo que significa que la empresa tiene que facilitarte las cosas si quieres seguir estudiando o formándote.\r\nSi estás cursando estudios oficiales para conseguir un título, la empresa debe ayudarte de dos formas concretas: primero, tienes preferencia para elegir tu turno de trabajo de forma que no entre en conflicto con tus clases. Segundo, tienes derecho a permisos retribuidos, es decir, días libres pagados, para poder ir a tus exámenes oficiales. No te están haciendo un favor. Es su obligación.\r\nPero hay más. Si llevas más de un año trabajando en la misma empresa, tienes derecho a 20 horas de formación al año vinculadas a tu sector, y esas horas son pagadas. Lo mejor de todo es que son acumulables durante hasta cinco años, lo que significa que podrías llegar a juntar hasta 100 horas para dedicarlas a un curso importante o incluso a un máster, siempre pactándolo con la empresa.\r\nEste derecho existe para que tu perfil profesional no se quede atrás y para que siempre puedas adaptarte a los cambios que vayan surgiendo en tu sector. La empresa está obligada a invertir en tu conocimiento.', 'https://www.boe.es/buscar/act.php?id=BOE-A-2015-11430'),
(15, 'Salud Laboral', 'Cuidate', 23, 'Trabajar en un entorno seguro no es un privilegio ni algo que la empresa te concede si quiere. Es tu derecho más básico, y la ley obliga a las empresas a cumplirlo de forma muy estricta.\r\nLa empresa debe evaluar todos y cada uno de los riesgos que existen en tu puesto de trabajo, ya sean físicos como el ruido, los productos químicos o el esfuerzo físico, o también riesgos más invisibles como el estrés o la mala postura. Una vez identificados, tienen la obligación de darte la formación necesaria para que sepas cómo protegerte. Además, deben entregarte de forma gratuita todos los equipos de protección individual que necesites para realizar tu trabajo con seguridad, como calzado especial, guantes, cascos o cualquier otro material.\r\nTambién tienes derecho a revisiones médicas periódicas adaptadas a los riesgos concretos de tu puesto, y a participar y ser consultada en todo lo relacionado con la seguridad de tu lugar de trabajo. Tu opinión importa.\r\nPero el punto más importante de todos es este: si en algún momento detectas que existe un riesgo grave e inmediato para tu vida o tu salud, como una máquina en mal estado o una situación peligrosa, tienes derecho legal a parar lo que estás haciendo y abandonar ese lugar de inmediato. Y la empresa no puede sancionarte por ello. Tu vida y tu salud siempre están por encima de cualquier beneficio económico.', 'https://www.boe.es/buscar/act.php?id=BOE-A-1995-24292'),
(16, 'Derechos Salariales', 'Aseguraste de que te dan bien tu remuneración mensual', 24, 'El salario es tu compensación por el tiempo y el esfuerzo que dedicas a tu trabajo, y la ley te garantiza recibirlo de forma justa y puntual.\r\nTienes derecho a cobrar en la fecha acordada, normalmente a final de mes. Y la cantidad nunca puede ser inferior a lo que establezca tu convenio colectivo ni al Salario Mínimo Interprofesional, el SMI. Si la empresa se retrasa en pagarte, no solo están incumpliendo la ley, sino que además tienes derecho a reclamar un interés por demora del 10% anual sobre la cantidad que te deban.\r\nCada mes tienes derecho a recibir una nómina clara y detallada. En ese documento tiene que aparecer todo: tu salario base, los complementos por antigüedad, por trabajar de noche, por peligrosidad, por transporte y cualquier otro que te corresponda, así como las horas extra que hayas realizado. Revísala siempre con atención.\r\nUno de los puntos más importantes, y que muchas veces pasa desapercibido, es comprobar que en tu nómina aparecen las cotizaciones correctas a la Seguridad Social. Ese dinero es el que determina lo que cobrarás si te quedas sin trabajo, si te pones enferma, si te quedas embarazada o, en el futuro, cuando te jubiles. Es tu red de seguridad, así que asegúrate de que se está pagando bien.', 'https://www.boe.es/buscar/act.php?id=BOE-A-2015-11430'),
(17, 'Derechos Sindicales', 'Aprende sobre sindicatos', 25, 'No tienes por qué enfrentarte sola a los problemas que puedan surgir en tu empresa. La unión con otras trabajadoras es un derecho fundamental que la ley reconoce y protege.\r\nTienes derecho a la libre sindicación, lo que significa que puedes afiliarte al sindicato que tú elijas para que te asesoren y te defiendan cuando lo necesites. Incluso puedes participar en la creación de un sindicato nuevo si lo consideras necesario. También tienes derecho a elegir y votar a tus representantes dentro de la empresa, ya sean las Delegadas de Personal o el Comité de Empresa.\r\nEstas representantes tienen un papel fundamental en tu protección: tienen acceso a una copia básica de todos los contratos que se firmen en la empresa, lo que les permite vigilar que no haya condiciones injustas ni diferencias de trato entre unas trabajadoras y otras.\r\nTienes además derecho a participar en asambleas dentro de tu empresa para debatir asuntos laborales con tus compañeras. Y, muy importante: si la empresa quiere tomar decisiones grandes que os afecten a todas, como despidos colectivos, bajadas de sueldo o traslados, está obligada a negociarlo previamente con los representantes de las trabajadoras. No pueden imponéroslo sin más.\r\nLos derechos sindicales son, en definitiva, tu mejor garantía de que las cosas se hacen de forma justa. Porque cuando vais juntas, es mucho más difícil que alguien ignore vuestros derechos.', 'https://www.boe.es/buscar/act.php?id=BOE-A-1985-16660');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `id` int(11) NOT NULL,
  `nombre_categoria` varchar(255) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `url_catIcono` varchar(255) DEFAULT NULL,
  `id_madre` int(11) DEFAULT NULL,
  `url_subcatIcono` varchar(255) DEFAULT NULL,
  `fecha_actualizacion` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id`, `nombre_categoria`, `descripcion`, `url_catIcono`, `id_madre`, `url_subcatIcono`, `fecha_actualizacion`) VALUES
(1, 'Jornada', 'Aprende todo sobre tu jornada de trabajo', 'https://images.pexels.com/photos/6234987/pexels-photo-6234987.jpeg', NULL, 'https://images.pexels.com/photos/6234987/pexels-photo-6234987.jpeg', 2026),
(4, 'Tipos de Jornada', 'Conoce que tipos de Jornada existen', 'https://images.pexels.com/photos/7428205/pexels-photo-7428205.jpeg', 1, 'https://images.pexels.com/photos/7428205/pexels-photo-7428205.jpeg', 2026),
(5, 'Contratos', 'Aprende a interpretar tu contrato', 'https://images.pexels.com/photos/5989926/pexels-photo-5989926.jpeg', NULL, 'https://images.pexels.com/photos/5989926/pexels-photo-5989926.jpeg', 2026),
(6, '¿Qué mirar en tu contrato?', 'Todo lo que debes saber antes de firmar', 'https://images.pexels.com/photos/9301839/pexels-photo-9301839.jpeg', 5, 'https://images.pexels.com/photos/9301839/pexels-photo-9301839.jpeg', 2026),
(7, 'Formación ', 'Infórmate sobre como formarte en España', 'https://images.pexels.com/photos/5905561/pexels-photo-5905561.jpeg', NULL, 'https://images.pexels.com/photos/5905561/pexels-photo-5905561.jpeg', 2026),
(8, 'Formación Profesional Básica', 'Aprende a inscribirte en FPB', 'https://images.pexels.com/photos/28812520/pexels-photo-28812520.jpeg', 7, 'https://images.pexels.com/photos/28812520/pexels-photo-28812520.jpeg', 2026),
(9, 'Formación Profesional de Grado Medio', 'Conviértete en una Técnica Especialista', 'https://images.pexels.com/photos/1181638/pexels-photo-1181638.jpeg', 7, 'https://images.pexels.com/photos/1181638/pexels-photo-1181638.jpeg', 2026),
(10, 'Formación Profesional de Grado Superior', 'Da el salto a Técnica Superior', 'https://images.pexels.com/photos/4872039/pexels-photo-4872039.jpeg', 7, 'https://images.pexels.com/photos/4872039/pexels-photo-4872039.jpeg', 2026),
(11, 'Universidad para Adultas', 'Tu meta universitaria a cualquier edad', 'https://images.pexels.com/photos/6399969/pexels-photo-6399969.jpeg', 7, 'https://images.pexels.com/photos/6399969/pexels-photo-6399969.jpeg', 2026),
(12, 'Homologación de Títulos Extranjeros', 'Que tu esfuerzo en el extranjero brille aquí', 'https://images.pexels.com/photos/7033272/pexels-photo-7033272.jpeg', 7, 'https://images.pexels.com/photos/7033272/pexels-photo-7033272.jpeg', 2026),
(13, 'CV y Entrevistas', 'Aprende a afrontar el mundo laboral', 'https://images.pexels.com/photos/5989925/pexels-photo-5989925.jpeg', NULL, 'https://images.pexels.com/photos/5989925/pexels-photo-5989925.jpeg', 2026),
(14, 'Cómo crear un CV ', 'Tu carta de presentación: Mucho más que un papel', 'https://images.pexels.com/photos/29233079/pexels-photo-29233079.jpeg', 13, 'https://images.pexels.com/photos/29233079/pexels-photo-29233079.jpeg', 2026),
(15, 'Preparando tu entrevista de trabajo', 'Confianza y seguridad: El momento de brillar', 'https://images.pexels.com/photos/5439152/pexels-photo-5439152.jpeg', 13, 'https://images.pexels.com/photos/5439152/pexels-photo-5439152.jpeg', 2026),
(17, 'Derechos', 'Conoce tus derechos', 'https://images.pexels.com/photos/5989925/pexels-photo-5989925.jpeg', NULL, 'https://images.pexels.com/photos/5989925/pexels-photo-5989925.jpeg', 2026),
(18, 'Nóminas', 'Como interpretar tu nómina', 'https://www.istockphoto.com/es/foto/manos-contadora-y-mujer-con-documento-para-finanzas-impuestos-o-auditor%C3%ADa-en-oficina-gm1589980410-529358123', NULL, 'https://www.istockphoto.com/es/foto/manos-contadora-y-mujer-con-documento-para-finanzas-impuestos-o-auditor%C3%ADa-en-oficina-gm1589980410-529358123', 2026),
(19, 'Derechos Principales', 'Conoce tus derechos generales', 'https://images.pexels.com/photos/8731038/pexels-photo-8731038.jpeg', 17, 'https://images.pexels.com/photos/8731038/pexels-photo-8731038.jpeg', 2026),
(20, 'Discriminación', 'Aprende a ver para que no te discriminen', 'https://images.pexels.com/photos/7640490/pexels-photo-7640490.jpeg', 17, 'https://images.pexels.com/photos/7640490/pexels-photo-7640490.jpeg', 2026),
(21, 'Derechos Laborales Básicos', 'Conoce tus derechos del día a día ', 'https://images.pexels.com/photos/8297234/pexels-photo-8297234.jpeg', 17, 'https://images.pexels.com/photos/8297234/pexels-photo-8297234.jpeg', 2026),
(22, 'Derechos de Formación', 'Aprende tus derechos antes de formarte', 'https://images.pexels.com/photos/31439022/pexels-photo-31439022.jpeg', 17, 'https://images.pexels.com/photos/31439022/pexels-photo-31439022.jpeg', 2026),
(23, 'Salud Laboral', 'Cuidate', 'https://images.pexels.com/photos/6753401/pexels-photo-6753401.jpeg', 17, 'https://images.pexels.com/photos/6753401/pexels-photo-6753401.jpeg', 2026),
(24, 'Derechos Salariales', 'Aseguraste de que te dan bien tu remuneración mensual', 'https://www.pexels.com/es-es/foto/hombre-mano-sin-rostro-rico-4386433/', 17, 'https://www.pexels.com/es-es/foto/hombre-mano-sin-rostro-rico-4386433/', 2026),
(25, 'Derechos Sindicales', 'Aprende sobre sindicatos', 'https://www.pexels.com/es-es/foto/persona-sentado-estudiando-pelo-rizado-7640424/', 17, 'https://www.pexels.com/es-es/foto/persona-sentado-estudiando-pelo-rizado-7640424/', 2026);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contenido_bloque`
--

CREATE TABLE `contenido_bloque` (
  `id` int(11) NOT NULL,
  `id_bloque` int(11) NOT NULL,
  `url_externa` varchar(255) NOT NULL,
  `url_icono` varchar(255) DEFAULT NULL,
  `url_extra` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `faq`
--

CREATE TABLE `faq` (
  `id` int(11) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `contenido` varchar(255) NOT NULL,
  `fecha` date NOT NULL,
  `respuesta` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id` int(11) NOT NULL,
  `tipo` varchar(255) NOT NULL,
  `descripción` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id`, `tipo`, `descripción`) VALUES
(0, 'admin', NULL),
(1, 'editora', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuaria`
--

CREATE TABLE `usuaria` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `correo` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `url_foto` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuaria`
--

INSERT INTO `usuaria` (`id`, `nombre`, `correo`, `password`, `id_rol`, `url_foto`) VALUES
(6, 'admin', 'admin@gmail.com', '$2y$10$KiNpmxQx6VCw/lidcxdmx.H2BELad/r1biaapPDM.uTmv2/VC4xlq', 0, NULL),
(7, 'editora', 'editora@gmail.com', '$2y$10$OQfoK5rWAbtbF4QricsfNOT20AV7iDiPTm77PHoFbUDBFezMmudCG', 1, NULL),
(8, 'Rodrigo', 'rodrigooalbarracin@gmail.com', '$2y$10$NQ/qlUlFkHp8pOq/V5cvnuO/xVWVVXyLUZO9IYRK6QFFbFpAxlnYa', 0, 'https://i.pinimg.com/webp/736x/73/c9/4b/73c94b09050743fd26105929006e214d.webp');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `blog`
--
ALTER TABLE `blog`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `bloque`
--
ALTER TABLE `bloque`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bloque_categoria` (`id_categoria`);

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `contenido_bloque`
--
ALTER TABLE `contenido_bloque`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contenido_bloque` (`id_bloque`);

--
-- Indices de la tabla `faq`
--
ALTER TABLE `faq`
  ADD PRIMARY KEY (`id`),
  ADD KEY `faq_categoria` (`id_categoria`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuaria`
--
ALTER TABLE `usuaria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuaria_rol` (`id_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `blog`
--
ALTER TABLE `blog`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `bloque`
--
ALTER TABLE `bloque`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `contenido_bloque`
--
ALTER TABLE `contenido_bloque`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `faq`
--
ALTER TABLE `faq`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuaria`
--
ALTER TABLE `usuaria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `bloque`
--
ALTER TABLE `bloque`
  ADD CONSTRAINT `bloque_categoria` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id`);

--
-- Filtros para la tabla `contenido_bloque`
--
ALTER TABLE `contenido_bloque`
  ADD CONSTRAINT `contenido_bloque` FOREIGN KEY (`id_bloque`) REFERENCES `bloque` (`id`);

--
-- Filtros para la tabla `faq`
--
ALTER TABLE `faq`
  ADD CONSTRAINT `faq_categoria` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id`);

--
-- Filtros para la tabla `usuaria`
--
ALTER TABLE `usuaria`
  ADD CONSTRAINT `usuaria_rol` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
