/**
 * i18n.js — Sistema de traducción multilingüe
 * Médicos del Mundo
 * Idiomas: Español (es), English (en), Français (fr), العربية (ar), Kiswahili (sw), Hausa (ha)
 *
 * Traducciones estáticas (nav, hero, secciones fijas) → diccionario TRANSLATIONS
 * Traducciones dinámicas (categorías, blog, descripciones) → API de Claude
 *   · Marcar elementos con el atributo:  data-ai-translate
 *   · El texto original (siempre en español) se guarda en:  data-ai-original
 *   · Resultado cacheado en sessionStorage con clave: mdm_ai_{lang}_{hash}
 *
 * Persiste el idioma elegido entre recargas y pestañas con localStorage.
 */

/* ─────────────────────────────────────────────
   CONSTANTES
───────────────────────────────────────────── */
const LANG_KEY    = 'mdm_lang';
const AI_MODEL    = 'claude-sonnet-4-20250514';
const CLAUDE_API  = 'https://api.anthropic.com/v1/messages';

const LANGS = {
  es: { flag: '🇪🇸', label: 'Español' },
  en: { flag: '🏴󠁧󠁢󠁥󠁮󠁧󠁿', label: 'English' },
  fr: { flag: '🇫🇷', label: 'Français' },
  ar: { flag: '🇸🇦', label: 'العربية' },
  sw: { flag: '🇹🇿', label: 'Kiswahili' },
  ha: { flag: '🇳🇬', label: 'Hausa' },
};

const LANG_NAMES = {
  es: 'Spanish',
  en: 'English',
  fr: 'French',
  ar: 'Arabic',
  sw: 'Kiswahili',
  ha: 'Hausa',
};

/* ─────────────────────────────────────────────
   DICCIONARIO ESTÁTICO
───────────────────────────────────────────── */
const TRANSLATIONS = {
  es: {
    /* NAV */
    nav_inicio:       'Inicio',
    nav_categorias:   'Categorías',
    nav_sobre:        'Sobre Nosotras',
    nav_blog:         'Blog',
    nav_contacto:     'Contacto',
    nav_login:        'INICIAR SESIÓN',
    nav_admin:        'Panel Admin',
    nav_volver:       'Volver a inicio',
    /* HERO index */
    hero_titulo:      'Bienvenida',
    hero_sub:         'Conoce tus derechos y encuentra el apoyo que necesitas',
    /* Secciones index */
    s1_titulo:        'Tus derechos en España',
    s1_texto:         'En Médicos del Mundo te ayudamos a entender cómo funciona el mundo laboral. Queremos que conozcas tus derechos para evitar abusos y que sepas que tienes un equipo que te respalda.',
    s1_cta:           'Saber más',
    s2_titulo:        'Acción y Acompañamiento',
    s2_texto:         'Nuestro equipo no solo te orienta, te acompaña. Estamos presentes para que tu voz sea escuchada y garantizando un acceso al trabajo digno.',
    s2_cta:           'Ver blog',
    s3_titulo:        'Sobre Nosotras',
    s3_texto:         'Somos una ONG de sanitarias y voluntarias. Contamos con psicólogas y orientadoras profesionales a tu disposición.',
    /* Footer */
    footer_tel:       'Contáctanos:',
    footer_sig:       'Síguenos',
    footer_enc:       'Encuéntranos',
    /* Categorías */
    cat_titulo:       'Categorías',
    cat_buscador:     '¿Qué necesitas buscar?',
    /* Blog */
    blog_h2:          'Actualidad y Salud',
    blog_sub:         'Infórmate con los mejores artículos escritos por profesionales.',
    blog_ultimas:     'Últimas Publicaciones',
    blog_vacio:       'Aún no hay noticias publicadas.',
    blog_leer:        'Leer más',
  },

  en: {
    nav_inicio:       'Home',
    nav_categorias:   'Categories',
    nav_sobre:        'About Us',
    nav_blog:         'Blog',
    nav_contacto:     'Contact',
    nav_login:        'LOG IN',
    nav_admin:        'Admin Panel',
    nav_volver:       'Back to home',
    hero_titulo:      'Welcome',
    hero_sub:         'Know your rights and find the support you need',
    s1_titulo:        'Your Rights in Spain',
    s1_texto:         'At Médicos del Mundo we help you understand how the labor world works. We want you to know your rights to avoid abuse and to know you have a team backing you up.',
    s1_cta:           'Learn more',
    s2_titulo:        'Action and Support',
    s2_texto:         'Our team does not only guide you, it accompanies you. We are present so your voice is heard and ensuring access to decent work.',
    s2_cta:           'See blog',
    s3_titulo:        'About Us',
    s3_texto:         'We are an NGO of healthcare workers and volunteers. We have psychologists and professional advisors at your disposal.',
    footer_tel:       'Contact us:',
    footer_sig:       'Follow us',
    footer_enc:       'Find us',
    cat_titulo:       'Categories',
    cat_buscador:     'What do you need to search?',
    blog_h2:          'News & Health',
    blog_sub:         'Stay informed with the best articles written by professionals.',
    blog_ultimas:     'Latest Publications',
    blog_vacio:       'No news published yet.',
    blog_leer:        'Read more',
  },

  fr: {
    nav_inicio:       'Accueil',
    nav_categorias:   'Catégories',
    nav_sobre:        'À propos',
    nav_blog:         'Blog',
    nav_contacto:     'Contact',
    nav_login:        'SE CONNECTER',
    nav_admin:        'Panneau Admin',
    nav_volver:       'Retour à l\'accueil',
    hero_titulo:      'Bienvenue',
    hero_sub:         'Connaissez vos droits et trouvez le soutien dont vous avez besoin',
    s1_titulo:        'Vos droits en Espagne',
    s1_texto:         'Chez Médicos del Mundo, nous vous aidons à comprendre le fonctionnement du monde du travail. Nous voulons que vous connaissiez vos droits pour éviter les abus.',
    s1_cta:           'En savoir plus',
    s2_titulo:        'Action et Accompagnement',
    s2_texto:         'Notre équipe ne vous oriente pas seulement, elle vous accompagne. Nous sommes présents pour que votre voix soit entendue et pour garantir un accès à un travail décent.',
    s2_cta:           'Voir le blog',
    s3_titulo:        'À propos de nous',
    s3_texto:         'Nous sommes une ONG de professionnels de santé et de bénévoles. Nous avons des psychologues et des conseillers professionnels à votre disposition.',
    footer_tel:       'Contactez-nous :',
    footer_sig:       'Suivez-nous',
    footer_enc:       'Trouvez-nous',
    cat_titulo:       'Catégories',
    cat_buscador:     'Que cherchez-vous ?',
    blog_h2:          'Actualité et Santé',
    blog_sub:         'Informez-vous avec les meilleurs articles écrits par des professionnels.',
    blog_ultimas:     'Dernières Publications',
    blog_vacio:       'Aucune actualité publiée pour l\'instant.',
    blog_leer:        'Lire la suite',
  },

  ar: {
    nav_inicio:       'الرئيسية',
    nav_categorias:   'الفئات',
    nav_sobre:        'من نحن',
    nav_blog:         'المدونة',
    nav_contacto:     'اتصل بنا',
    nav_login:        'تسجيل الدخول',
    nav_admin:        'لوحة الإدارة',
    nav_volver:       'العودة إلى الرئيسية',
    hero_titulo:      'أهلاً وسهلاً',
    hero_sub:         'اعرفي حقوقك وجدي الدعم الذي تحتاجينه',
    s1_titulo:        'حقوقك في إسبانيا',
    s1_texto:         'في Médicos del Mundo نساعدك على فهم سوق العمل. نريدك أن تعرفي حقوقك لتجنب الإساءة وتعلمي أن لديك فريقاً يدعمك.',
    s1_cta:           'اعرف أكثر',
    s2_titulo:        'العمل والمرافقة',
    s2_texto:         'فريقنا لا يرشدك فحسب، بل يرافقك. نحن هنا حتى يُسمع صوتك وضمان الوصول إلى عمل لائق.',
    s2_cta:           'عرض المدونة',
    s3_titulo:        'من نحن',
    s3_texto:         'نحن منظمة غير حكومية من العاملين في الرعاية الصحية والمتطوعين. لدينا علماء نفس ومستشارون مهنيون في خدمتك.',
    footer_tel:       'اتصلي بنا:',
    footer_sig:       'تابعينا',
    footer_enc:       'جدينا',
    cat_titulo:       'الفئات',
    cat_buscador:     'ماذا تبحثين؟',
    blog_h2:          'الأخبار والصحة',
    blog_sub:         'ابقِ على اطلاع بأفضل المقالات المكتوبة من قِبَل المحترفين.',
    blog_ultimas:     'أحدث المنشورات',
    blog_vacio:       'لم يتم نشر أي أخبار بعد.',
    blog_leer:        'اقرئي أكثر',
  },

  sw: {
    nav_inicio:       'Nyumbani',
    nav_categorias:   'Makundi',
    nav_sobre:        'Kuhusu Sisi',
    nav_blog:         'Blogu',
    nav_contacto:     'Wasiliana',
    nav_login:        'INGIA',
    nav_admin:        'Jopo la Msimamizi',
    nav_volver:       'Rudi nyumbani',
    hero_titulo:      'Karibu',
    hero_sub:         'Jua haki zako na upate msaada unaohitajika',
    s1_titulo:        'Haki Zako Hispania',
    s1_texto:         'Katika Médicos del Mundo tunakusaidia kuelewa jinsi ulimwengu wa kazi unavyofanya kazi. Tunataka ujue haki zako ili kuepuka unyanyasaji.',
    s1_cta:           'Jifunze zaidi',
    s2_titulo:        'Hatua na Msaada',
    s2_texto:         'Timu yetu haikuongozi tu, inakuandamana. Tuko hapa ili sauti yako isikiwe na kuhakikisha ufikiaji wa kazi ya heshima.',
    s2_cta:           'Ona blogu',
    s3_titulo:        'Kuhusu Sisi',
    s3_texto:         'Sisi ni shirika lisilo la kiserikali la wataalamu wa afya na wanaojitolea. Tuna wasaikolojia na washauri wa kitaalamu wako.',
    footer_tel:       'Wasiliana nasi:',
    footer_sig:       'Tufuate',
    footer_enc:       'Tupate',
    cat_titulo:       'Makundi',
    cat_buscador:     'Unatafuta nini?',
    blog_h2:          'Habari na Afya',
    blog_sub:         'Jielimishe na makala bora yaliyoandikwa na wataalamu.',
    blog_ultimas:     'Machapisho ya Hivi Karibuni',
    blog_vacio:       'Hakuna habari zilizochapishwa bado.',
    blog_leer:        'Soma zaidi',
  },

  ha: {
    nav_inicio:       'Gida',
    nav_categorias:   'Rukunoni',
    nav_sobre:        'Game da Mu',
    nav_blog:         'Blog',
    nav_contacto:     'Tuntuɓa',
    nav_login:        'SHIGA',
    nav_admin:        'Panel na Admin',
    nav_volver:       'Koma gida',
    hero_titulo:      'Maraba',
    hero_sub:         'Ka san haƙƙoƙinka ka sami goyon bayan da kake buƙata',
    s1_titulo:        'Haƙƙoƙinka a Spain',
    s1_texto:         'A Médicos del Mundo muna taimaka maka fahimtar yadda duniyar aiki ke aiki. Muna so ka san haƙƙoƙinka don guje wa zalunci.',
    s1_cta:           'Ƙara koyo',
    s2_titulo:        'Aiki da Taimakon Kai',
    s2_texto:         'Ƙungiyarmu ba wai kawai tana shiryar da kai ba, tana rakiyar ka. Muna nan don a ji muryarka da kuma tabbatar da samun aikin da ya dace.',
    s2_cta:           'Duba blog',
    s3_titulo:        'Game da Mu',
    s3_texto:         'Muna ƙungiya ta ma\'aikatan lafiya da masu sa kai. Muna da masana ilimin halin ɗan adam da mashawarta ƙwararru a sabis ɗinka.',
    footer_tel:       'Tuntuɓe mu:',
    footer_sig:       'Bi mu',
    footer_enc:       'Samu mu',
    cat_titulo:       'Rukunoni',
    cat_buscador:     'Me kake nema?',
    blog_h2:          'Labarai da Lafiya',
    blog_sub:         'Sanar da kai tare da mafi kyawun labaru da ƙwararru suka rubuta.',
    blog_ultimas:     'Wallafe-wallafen Kwanan Nan',
    blog_vacio:       'Babu labarai da aka buga tukuna.',
    blog_leer:        'Karanta ƙari',
  },
};

/* ─────────────────────────────────────────────
   GUARDAR / LEER IDIOMA
───────────────────────────────────────────── */
function getLang() {
  return localStorage.getItem(LANG_KEY) || 'es';
}
function setLang(code) {
  localStorage.setItem(LANG_KEY, code);
}

/* ─────────────────────────────────────────────
   TRADUCCIONES ESTÁTICAS (data-i18n)
───────────────────────────────────────────── */
function applyTranslations(lang) {
  const t = TRANSLATIONS[lang];
  if (!t) return;

  /* RTL para árabe */
  document.documentElement.setAttribute('dir', lang === 'ar' ? 'rtl' : 'ltr');
  document.documentElement.setAttribute('lang', lang);

  document.querySelectorAll('[data-i18n]').forEach(el => {
    const key = el.getAttribute('data-i18n');
    if (t[key] !== undefined) {
      if (el.tagName === 'INPUT' && el.hasAttribute('placeholder')) {
        el.setAttribute('placeholder', t[key]);
      } else {
        el.textContent = t[key];
      }
    }
  });
}

/* ─────────────────────────────────────────────
   TRADUCCIÓN DINÁMICA CON CLAUDE API
   ─────────────────────────────────────────────
   Cómo usar en tu HTML:
     <h3 data-ai-translate>Salud Laboral</h3>
     <p  data-ai-translate>Descripción generada dinámicamente por PHP/JS</p>

   · El texto español original se guarda automáticamente en data-ai-original
     la primera vez que se inicializa (idioma = es).
   · Para idiomas ≠ es, se recogen todos esos elementos, se envían en un
     único batch a Claude y se aplican las traducciones devueltas.
   · El resultado se cachea en sessionStorage para no repetir la llamada
     mientras el usuario navega en la misma sesión.
───────────────────────────────────────────── */

/**
 * Guarda el texto español original en data-ai-original (solo una vez).
 * Llamar al cargar la página, ANTES de cualquier cambio de idioma.
 */
function snapshotOriginals() {
  document.querySelectorAll('[data-ai-translate]').forEach(el => {
    if (!el.dataset.aiOriginal) {
      el.dataset.aiOriginal = el.textContent.trim();
    }
  });
}

/**
 * Genera una clave de caché en sessionStorage basada en el idioma
 * y un hash ligero de los textos originales presentes en el DOM.
 */
function buildCacheKey(lang, texts) {
  const hash = texts.join('|').split('').reduce((acc, c) => {
    return ((acc << 5) - acc + c.charCodeAt(0)) | 0;
  }, 0);
  return `mdm_ai_${lang}_${Math.abs(hash)}`;
}

/**
 * Llama a la API de Claude con un array de textos en español
 * y devuelve un array de textos traducidos al idioma indicado.
 * @param {string[]} texts  — Textos originales (español)
 * @param {string}   lang   — Código de idioma destino
 * @returns {Promise<string[]>}
 */
async function callClaudeTranslate(texts, lang) {
  const langName = LANG_NAMES[lang] || lang;

  const prompt = `You are a professional translator for an NGO website that helps migrant women in Spain understand their labor rights.

Translate the following JSON array of strings from Spanish to ${langName}.
Keep the tone warm, clear, and accessible. Preserve proper nouns (e.g. "Médicos del Mundo").
Respond ONLY with a valid JSON array of translated strings — same order, same count, no extra text.

${JSON.stringify(texts)}`;

  const response = await fetch(CLAUDE_API, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      model: AI_MODEL,
      max_tokens: 1000,
      messages: [{ role: 'user', content: prompt }],
    }),
  });

  if (!response.ok) {
    const err = await response.json().catch(() => ({}));
    throw new Error(`Claude API error ${response.status}: ${err?.error?.message || response.statusText}`);
  }

  const data = await response.json();
  const raw = (data.content || [])
    .filter(b => b.type === 'text')
    .map(b => b.text)
    .join('');

  // Limpiar posibles bloques de código markdown (```json … ```)
  const clean = raw.replace(/^```(?:json)?\s*/i, '').replace(/\s*```$/, '').trim();
  return JSON.parse(clean);
}

/**
 * Indicador de carga que se superpone levemente sobre los elementos dinámicos.
 */
function showAiLoader() {
  if (document.getElementById('ai-translate-loader')) return;
  const loader = document.createElement('div');
  loader.id = 'ai-translate-loader';
  loader.innerHTML = `
    <span class="ai-loader-dot"></span>
    <span class="ai-loader-dot"></span>
    <span class="ai-loader-dot"></span>
    <span class="ai-loader-text">Traduciendo…</span>
  `;
  document.body.appendChild(loader);
}

function hideAiLoader() {
  const loader = document.getElementById('ai-translate-loader');
  if (loader) loader.remove();
}

/**
 * Función principal de traducción dinámica.
 * · Si lang === 'es', restaura los textos originales.
 * · Si hay caché, la usa directamente.
 * · Si no, llama a Claude, cachea y aplica.
 * @param {string} lang
 */
async function translateDynamic(lang) {
  const elements = Array.from(document.querySelectorAll('[data-ai-translate]'));
  if (elements.length === 0) return;

  // Restaurar español sin llamada a la API
  if (lang === 'es') {
    elements.forEach(el => {
      if (el.dataset.aiOriginal) el.textContent = el.dataset.aiOriginal;
    });
    return;
  }

  const originals = elements.map(el => el.dataset.aiOriginal || el.textContent.trim());
  const cacheKey  = buildCacheKey(lang, originals);

  // Intentar caché
  try {
    const cached = sessionStorage.getItem(cacheKey);
    if (cached) {
      const translations = JSON.parse(cached);
      elements.forEach((el, i) => {
        if (translations[i] !== undefined) el.textContent = translations[i];
      });
      return;
    }
  } catch (_) { /* sessionStorage no disponible */ }

  // Llamada a Claude
  showAiLoader();
  try {
    const translations = await callClaudeTranslate(originals, lang);

    // Aplicar al DOM
    elements.forEach((el, i) => {
      if (translations[i] !== undefined) el.textContent = translations[i];
    });

    // Guardar en caché
    try {
      sessionStorage.setItem(cacheKey, JSON.stringify(translations));
    } catch (_) { /* quota exceeded — ignorar */ }

  } catch (err) {
    console.warn('[i18n] Error en traducción dinámica:', err.message);
    // Si falla, mantenemos el texto original para no romper la UX
  } finally {
    hideAiLoader();
  }
}

/* ─────────────────────────────────────────────
   WIDGET DE IDIOMA
───────────────────────────────────────────── */
function buildLangSwitcher() {
  const current = getLang();

  const wrapper = document.createElement('div');
  wrapper.id = 'lang-switcher';
  wrapper.innerHTML = `
    <button class="lang-btn-current" id="lang-btn-current" aria-haspopup="listbox" aria-expanded="false">
      <span class="lang-flag">${LANGS[current].flag}</span>
      <span class="lang-label">${LANGS[current].label}</span>
      <i class="fa-solid fa-chevron-down lang-chevron"></i>
    </button>
    <ul class="lang-dropdown" role="listbox" id="lang-dropdown">
      ${Object.entries(LANGS).map(([code, info]) => `
        <li role="option" aria-selected="${code === current}" data-code="${code}" class="lang-option${code === current ? ' active' : ''}">
          <span class="lang-flag">${info.flag}</span>
          <span class="lang-label">${info.label}</span>
        </li>
      `).join('')}
    </ul>
  `;

  const btn      = wrapper.querySelector('#lang-btn-current');
  const dropdown = wrapper.querySelector('#lang-dropdown');

  /* Abrir / cerrar */
  btn.addEventListener('click', e => {
    e.stopPropagation();
    const open = dropdown.classList.toggle('open');
    btn.setAttribute('aria-expanded', open);
  });

  /* Seleccionar idioma */
  dropdown.querySelectorAll('.lang-option').forEach(li => {
    li.addEventListener('click', () => {
      const code = li.dataset.code;
      setLang(code);

      // 1. Traducciones estáticas (instantáneas)
      applyTranslations(code);

      // 2. Traducciones dinámicas vía Claude (asíncrono)
      translateDynamic(code);

      /* Actualizar botón */
      btn.querySelector('.lang-flag').textContent  = LANGS[code].flag;
      btn.querySelector('.lang-label').textContent = LANGS[code].label;

      /* Marcar opción activa */
      dropdown.querySelectorAll('.lang-option').forEach(o => {
        o.classList.toggle('active', o.dataset.code === code);
        o.setAttribute('aria-selected', o.dataset.code === code);
      });

      dropdown.classList.remove('open');
      btn.setAttribute('aria-expanded', false);
    });
  });

  /* Cerrar al clicar fuera */
  document.addEventListener('click', () => {
    dropdown.classList.remove('open');
    btn.setAttribute('aria-expanded', false);
  });

  return wrapper;
}

/* ─────────────────────────────────────────────
   ESTILOS (widget + loader)
───────────────────────────────────────────── */
function injectStyles() {
  const style = document.createElement('style');
  style.textContent = `
    /* ── Widget de idioma ── */
    #lang-switcher {
      position: absolute;
      right: 140px;
      top: 50%;
      transform: translateY(-50%);
      z-index: 9999;
      font-family: inherit;
    }
    .boton-acceso ~ #lang-switcher,
    #lang-switcher:has(~ .boton-acceso) { right: 200px; }

    .lang-btn-current {
      display: flex;
      align-items: center;
      gap: 6px;
      background: rgba(255,255,255,0.18);
      border: 1.5px solid rgba(255,255,255,0.55);
      border-radius: 24px;
      padding: 6px 14px;
      cursor: pointer;
      color: #fff;
      font-size: 14px;
      font-weight: 600;
      backdrop-filter: blur(6px);
      transition: background 0.2s, border-color 0.2s;
      white-space: nowrap;
    }
    .lang-btn-current:hover { background: rgba(255,255,255,0.28); }
    .lang-flag   { font-size: 18px; line-height: 1; }
    .lang-chevron { font-size: 11px; transition: transform 0.2s; }
    #lang-dropdown.open { display: block; }
    #lang-dropdown.open ~ #lang-btn-current .lang-chevron { transform: rotate(180deg); }

    .lang-dropdown {
      display: none;
      position: absolute;
      top: calc(100% + 8px);
      right: 0;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 8px 32px rgba(0,0,0,0.18);
      list-style: none;
      padding: 6px 0;
      margin: 0;
      min-width: 160px;
      overflow: hidden;
    }
    .lang-option {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 9px 16px;
      cursor: pointer;
      color: #333;
      font-size: 14px;
      font-weight: 500;
      transition: background 0.15s;
    }
    .lang-option:hover  { background: #f0f4ff; }
    .lang-option.active { background: #e8f0fe; font-weight: 700; color: #1a3c6e; }

    .cabecera-minimal #lang-switcher  { position: static; transform: none; }
    .contenedor-header                { position: relative; }
    .contenedor-header #lang-switcher { position: absolute; right: 0; top: 50%; transform: translateY(-50%); }
    .lang-btn-current                 { border-color: rgba(255,255,255,0.6); }
    .cabecera-minimal .lang-btn-current,
    .contenedor-hero  .lang-btn-current { color: #fff; }
    .cabecera-minimal .lang-btn-current { background: rgba(255,255,255,0.15); }

    /* RTL */
    [dir="rtl"] #lang-switcher  { right: auto; left: 140px; }
    [dir="rtl"] .lang-dropdown  { right: auto; left: 0; }

    @media (max-width: 768px) {
      #lang-switcher          { right: 60px; }
      [dir="rtl"] #lang-switcher { left: 60px; right: auto; }
      .lang-label             { display: none; }
      .lang-btn-current       { padding: 6px 10px; }
    }

    /* ── Loader de traducción IA ── */
    #ai-translate-loader {
      position: fixed;
      bottom: 24px;
      left: 50%;
      transform: translateX(-50%);
      display: flex;
      align-items: center;
      gap: 8px;
      background: rgba(26, 60, 110, 0.92);
      color: #fff;
      font-size: 13px;
      font-weight: 500;
      padding: 10px 20px;
      border-radius: 24px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.25);
      z-index: 99999;
      backdrop-filter: blur(8px);
      animation: ai-loader-fadein 0.2s ease;
    }
    @keyframes ai-loader-fadein {
      from { opacity: 0; transform: translateX(-50%) translateY(8px); }
      to   { opacity: 1; transform: translateX(-50%) translateY(0); }
    }
    .ai-loader-dot {
      width: 7px;
      height: 7px;
      background: #7eb8f7;
      border-radius: 50%;
      animation: ai-dot-bounce 1.2s infinite ease-in-out;
    }
    .ai-loader-dot:nth-child(2) { animation-delay: 0.2s; }
    .ai-loader-dot:nth-child(3) { animation-delay: 0.4s; }
    @keyframes ai-dot-bounce {
      0%, 80%, 100% { transform: scale(0.7); opacity: 0.5; }
      40%            { transform: scale(1.2); opacity: 1; }
    }
    .ai-loader-text { margin-left: 4px; letter-spacing: 0.02em; }

    /* Efecto sutil en los elementos mientras se traduce */
    [data-ai-translate].ai-translating {
      opacity: 0.55;
      transition: opacity 0.25s;
    }
  `;
  document.head.appendChild(style);
}

/* ─────────────────────────────────────────────
   PUNTO DE ENTRADA
───────────────────────────────────────────── */
function initI18n() {
  injectStyles();

  // Guardar textos originales antes de tocar el DOM
  snapshotOriginals();

  const lang = getLang();

  /* Insertar el widget en el nav */
  const nav = document.querySelector('.barra-nav') || document.querySelector('.contenedor-header');
  if (nav) {
    nav.style.position = 'relative';
    nav.appendChild(buildLangSwitcher());
  }

  // Aplicar traducciones estáticas
  applyTranslations(lang);

  // Aplicar traducciones dinámicas si el idioma guardado no es español
  if (lang !== 'es') {
    translateDynamic(lang);
  }
}

/* Esperar a que el DOM esté listo */
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initI18n);
} else {
  initI18n();
}
