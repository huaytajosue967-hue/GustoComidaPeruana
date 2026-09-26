# Gusto Comida Peruana — gustocomidaperuana.net

El sitio en vivo corre en **WordPress + Elementor** (Hostinger). Este repo es la fuente de lo que se pega ahí.

| Carpeta / archivo | Qué es | ¿Se edita? |
|---|---|---|
| `wordpress/pages/` | Contenido del widget HTML de cada página (Inicio, Menú, Pedidos) | **Sí** → pegar en Elementor |
| `wordpress/snippets/` | Headers y footer (shortcodes PHP del plugin Code Snippets) | **Sí** → pegar en Code Snippets |
| `wordpress/HANDOFF.md` | Arquitectura, bugs conocidos y cómo purgar caché | Leer antes de tocar |
| `assets/` | Imágenes y video, subidos al servidor en `/assets/` | Si se cambia una imagen, resubir por File Manager |
| `business-data.json` | Datos del negocio, colores, horarios, reglas del cliente | Referencia |
| `GTM-P6ZRCQ5Z_v3.json` | Export del contenedor de Google Tag Manager | Respaldo |
| `sitio-estatico-viejo/` | Sitio HTML original, ya no publicado. Su `<head>` tiene los meta tags / schema de SEO de referencia | No — solo archivo |

Después de cualquier cambio: guardar en WordPress y purgar caché en **LiteSpeed** y en **hPanel**.
