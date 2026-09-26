# Handoff — Migración de Gusto Comida Peruana a WordPress

## Contexto del proyecto

Sitio original: HTML estático, ahora archivado en `sitio-estatico-viejo/` (`index.html`, `menu/carta.html`, `pedidos.html`). **Ya no está publicado**: `gustocomidaperuana.net` corre en WordPress y GitHub ya no está conectado al hosting.

Se migró ese mismo diseño a WordPress, en un **subdominio de prueba**: `https://darkblue-lapwing-982628.hostingersite.com` (WordPress + Elementor Pro + tema Hello Elementor). El dominio real (`gustocomidaperuana.net`) todavía no se pasó — es el siguiente paso grande.

**Google Tag Manager** (`GTM-P6ZRCQ5Z`) — el contenedor en sí ya está armado del lado de Google (ver "Estado de GTM" más abajo) y el plugin **GTM4WP ya está instalado** en WordPress, pero falta terminar su configuración: el "GTM Container placement" está en **Off**, así que el `gtm.js` todavía no se carga en la página real.

## Estado actual: ✅ Inicio, Menú y Pedidos funcionando

Las 3 páginas están armadas y confirmadas funcionando en el subdominio de prueba:

- **Inicio** (`/`) — widget HTML con `wordpress/pages/inicio.html`, header `gusto_header_full`.
- **Menú** (`/menu/`) — widget HTML con `wordpress/pages/menu.html`, header `gusto_header_simple`.
- **Pedidos** (`/pedidos/`) — widget HTML con `wordpress/pages/pedidos.html`, header `gusto_header_simple`.
- Las 3 páginas comparten el footer `gusto_footer` (Theme Builder, condición: todo el sitio).

## Arquitectura

- **Code Snippets** (plugin) → 2 snippets PHP con shortcodes:
  - `wordpress/snippets/headers.php` → shortcodes `gusto_header_full` (nav completo, solo Inicio) y `gusto_header_simple` (logo + volver al sitio, Menú y Pedidos). Contiene `gusto_base_styles()` (variables de color, fuentes, reset — se llama desde los 3 shortcodes).
  - `wordpress/snippets/footer.php` → shortcode `gusto_footer`.
- **Elementor Theme Builder** (Pro) → plantillas "Header Inicio", "Header Simple", "Footer Gusto".
- **3 páginas de WordPress**, cada una con un solo widget **HTML** de Elementor, contenido en `wordpress/pages/*.html`.
- Plantilla de página: **"Elementor Full Width"**.
- Carpeta `assets/` subida al servidor (mismo nivel que `wp-content`), rutas absolutas `/assets/...`.

## ⚠️ Dos cosas que costaron MUCHÍSIMO tiempo — leer antes de tocar nada

### 1. Nunca escribir un nombre de shortcode entre corchetes en ningún comentario

Si un comentario (HTML o CSS) dentro de un widget HTML dice algo como corchete-gusto_header_simple-corchete, **WordPress lo interpreta como una llamada real al shortcode** y lo reemplaza ahí mismo con todo su HTML/CSS — aunque esté adentro de un `/* comentario */` que nadie iba a ver. Esto rompió por completo la página de Pedidos (el `<style>` quedó cortado a la mitad, todo el CSS de ahí en adelante se mostraba como texto plano en la página). Ya se sacaron todos los corchetes de los 3 archivos en `wordpress/pages/`. Si vas a escribir sobre un shortcode en un comentario, escribí el nombre **sin corchetes**.

### 2. Después de CUALQUIER cambio, hay que purgar caché en DOS lugares, no uno

1. WordPress → Code Snippets: guardar/Publicar el cambio (obvio).
2. Purgar LiteSpeed Cache **desde el plugin de WordPress** (ícono de rayo en la barra de admin, o Ajustes → LiteSpeed Cache → Toolbox → Purge All).
3. Purgar caché **desde hPanel de Hostinger** (fuera de WordPress — hPanel → tu sitio → sección de Caché/Rendimiento → Purgar todo). **Este paso es el que casi siempre faltaba** y hacía que cambios ya guardados no se vieran reflejados en la página real durante horas de pruebas.

Si hacés un cambio y "no se ve nada distinto", sospechar primero de la caché de hPanel antes de asumir que el código está mal. Se puede confirmar bajando el HTML real con `curl -A "Mozilla/5.0" URL` y buscando si el cambio nuevo aparece en el texto — si no aparece, es guardado/caché, no el código.

## Bugs ya resueltos en esta sesión

- **Ancho angosto en Menú** (contenido y header en una columna de ~650px, con espacio vacío al costado) → resuelto con el truco full-bleed `width:100vw; margin-left:calc(-50vw + 50%);` en `.gusto-carta-wrap` (sin `position:relative; left:50%`, que era la versión vieja que quedaba desalineada). Mismo truco aplicado al `.footer-bg`.
- **Footer no ocupaba todo el ancho** → mismo truco full-bleed aplicado a `.footer-bg` en `footer.php`.
- **Color del footer** → separado en `.footer-bg` (fondo `var(--bg-deep)`) + `.wrap.footer` (contenido centrado) adentro.
- **Hover raro (gris/azul) en el logo "Gusto"** → el tema pisaba el color en `:hover`; se agregó `.logo:hover{color:var(--gold);} .logo:hover span{color:var(--text-white);}` en `gusto_base_styles()`.
- **Contenido de Menú "desaparecía" al editar** → pasó porque se borró y recreó el contenedor de Elementor con otra configuración (Ancho encajado en vez de Ancho completo). Se resolvió restaurando una revisión anterior del Historial de Elementor. Lección: si hay que tocar la configuración de un Contenedor, anotar los valores originales antes (Ancho del contenido, Altura mínima, Dirección) por si hay que volver atrás.
- **CSS de Pedidos se veía como texto plano en la página** → causado por el bug de los corchetes (ver arriba). Resuelto.

## Pendiente / no resuelto

- **Cosmético menor en Menú**: en pantallas donde el contenido de la carta es más corto que la altura de la ventana, puede verse un poco del color de fondo general del sitio (azul grisáceo, `--bg-primary`) por debajo del footer, en vez de terminar la página justo ahí. Se investigó a fondo (incluso confirmando con JS en la consola que no hay ningún elemento roto ni con altura anormal — todo mide lo que debería). No se encontró una forma de sacarlo sin arriesgar romper el diseño de Inicio/Pedidos (ya pasó una vez: cambiar el fondo del `<body>` global tiñó secciones de Inicio que no debían cambiar). **Se decidió dejarlo así** — es un detalle muy menor, no vale el riesgo de seguir tocando el layout global. Si en el futuro se quiere retomar, la pista más prometedora sin probar todavía es un patrón "sticky footer" con `body{display:flex;flex-direction:column;min-height:100vh}` aplicado **solo** en la página de Menú vía `body.page-id-18{...}` (ese selector por ID de página, no `:has()`, para máxima compatibilidad).

## Estado de GTM (revisado 2026-08-10)

Verificado bajando el HTML real de `https://darkblue-lapwing-982628.hostingersite.com/` con `curl -A "Mozilla/5.0"` (mismo método que recomienda este HANDOFF más arriba):

- El plugin **GTM4WP está instalado y activo** — inyecta el `dataLayer` y sus scripts en el `<head>`.
- Pero aparece literalmente esto en el HTML: `<!-- GTM Container placement set to off -->` y en consola `[GTM4WP] Google Tag Manager container code placement set to OFF !!!`. O sea: el data layer se inicializa, pero el script de `gtm.js` (el contenedor `GTM-P6ZRCQ5Z` en sí) **todavía no se carga en la página**. Falta un solo ajuste dentro del plugin, no instalarlo de cero.
- El contenedor de GTM del lado de Google ya está completamente armado (exportado como `GTM-P6ZRCQ5Z_v3.json` en la raíz del repo, versión 3, `exportTime: 2026-08-08 20:42:47`). Contiene:
  - Tag `GA4 - Inicialización` → `G-F10MZSQZVV`, dispara en todas las páginas.
  - Tag `GA4 - Evento - WHATSAPP Web Gusto` → evento `whatsapp_web`, dispara con el trigger `Click WHATSAPP`.
  - Tag `Google Ads - Etiqueta de Google` → `AW-18311599565`, dispara en todas las páginas.
  - Tag `Google Ads - Conversión - WHATSAPP Web Gusto` → conversion label `ZhpwCI6-7s8cEM2r05tE`, dispara con el trigger `Click WHATSAPP`.
  - Tag `Google Ads - Vinculador de conversiones` (Conversion Linker), dispara en todas las páginas.
  - Trigger `Click WHATSAPP` (tipo `LINK_CLICK`): dispara cuando `{{Click URL}}` contiene `wa.link` — coincide con los links reales del sitio (`https://wa.link/amosx9` en `inicio.html` y `headers.php`).
- Se detectó además un plugin no documentado antes: **PixelYourSite** (Meta Pixel) activo en el sitio, actualmente sin pixel configurado (`"PixelYourSite: no pixel configured"` en consola). No estaba en el HANDOFF original — confirmar con el dueño del sitio si se quiere usar o desinstalar para no dejar plugins sueltos.

**Para terminar el punto 1** (acción manual en el admin de WordPress, no se puede hacer por código):
1. WordPress admin → Ajustes → **Google Tag Manager** (o donde el plugin GTM4WP haya quedado en el menú).
2. Cargar el ID de contenedor `GTM-P6ZRCQ5Z` si no está ya puesto.
3. Cambiar **"GTM Container placement"** de `Off` a `Insert GTM code manually` está bien solo si van a pegar el snippet a mano — para que el plugin lo inyecte solo, elegir la opción que lo pone en `<head>` (normalmente la primera de la lista, no "Off").
4. Guardar, purgar caché en los dos lugares de siempre (LiteSpeed del plugin + hPanel — ver sección de arriba).
5. Verificar con `curl -A "Mozilla/5.0" URL` que ya no diga "placement set to off" y que aparezca `googletagmanager.com/gtm.js?id=GTM-P6ZRCQ5Z`.
6. Abrir el sitio, entrar a GTM → modo **Preview**, conectar al subdominio de prueba, hacer clic en un link de WhatsApp y confirmar que dispara el trigger `Click WHATSAPP` y las 2 tags asociadas (GA4 evento + Google Ads conversión).

## Pendiente antes de pasar el dominio real

1. ~~Instalar GTM4WP~~ → plugin ya instalado, falta solo prender el "Container placement" (ver sección de arriba) y verificar en modo Preview.
2. SEO: reinstalar meta tags/schema con Yoast o RankMath (el sitio estático ya lo tenía armado a mano en el `<head>` de `sitio-estatico-viejo/index.html`, `menu/carta.html`, `pedidos.html` — hay que trasladarlo).
3. Ocultar "Título de la página" en Menú y Pedidos si no está ya (⚙ Ajustes de página → Título de la página → Ocultar). **Revisado 2026-08-10 con curl**: en Menú ya está oculto (no aparece `entry-title` en el HTML). En **Pedidos todavía NO** — el HTML real trae `<h1 class="entry-title">Pedidos</h1>` metido por el tema, además del `<h1 class="page-title">` propio del widget (p.ej. "Elegí tus platos") — se ven duplicados. Falta ir a Editar página → Pedidos → ⚙ Ajustes de página → Título de la página → Ocultar, guardar y purgar caché en los dos lugares.
4. Revisar que todos los links internos (`/menu/`, `/pedidos/`, `/`) sigan apuntando bien una vez que el dominio cambie. Ya son en su mayoría relativos o usan `home_url()` en los snippets PHP (`headers.php`, `footer.php`), así que deberían migrar solos; los únicos absolutos son externos (`wa.link`, Instagram, Facebook), que no cambian con el dominio.
5. El sitio de prueba tiene `<meta name="robots" content="noindex, nofollow">` — correcto mientras es subdominio de prueba, pero hay que sacarlo (o confirmar que Yoast/RankMath lo controle) antes de que este WordPress sea el sitio real, si no Google no lo va a indexar.

## Migración del dominio real (`gustocomidaperuana.net`) — siguiente paso grande

Pendiente de definir en la próxima sesión. Puntos a resolver ahí:
- Plugin de migración (Duplicator o similar) para reemplazar todas las URLs del subdominio de prueba por el dominio real.
- Reasignar el dominio en hPanel al sitio nuevo de WordPress.
- Apagar el auto-deploy de GitHub del sitio estático viejo (para que no compita con el nuevo una vez que el dominio apunte a WordPress).
- Verificar que `robots.txt` y `sitemap.xml` del sitio viejo no queden pisando al nuevo.
- Backup del sitio estático viejo antes de apagar nada (por las dudas).

## Archivos de referencia

- `wordpress/snippets/headers.php` — shortcodes de header + estilos base compartidos.
- `wordpress/snippets/footer.php` — shortcode de footer.
- `wordpress/pages/inicio.html`, `menu.html`, `pedidos.html` — contenido de cada widget HTML.
- `GTM-P6ZRCQ5Z_v3.json` (raíz del repo) — export del contenedor de Google Tag Manager (tags, triggers, variables), versión 3. Sirve como respaldo/referencia de la config si hay que recrearla o importarla en otra cuenta de GTM.
