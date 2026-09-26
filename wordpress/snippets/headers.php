<?php
/**
 * Code Snippets → "Gusto - Headers"
 * Contiene los dos shortcodes de header:
 *   [gusto_header_full]   -> SOLO en la página Inicio (nav completo)
 *   [gusto_header_simple] -> en las páginas Menú y Pedidos (logo + volver al sitio)
 * Van en el mismo snippet porque ambos usan gusto_base_styles() -- si estuvieran
 * en snippets separados, PHP tiraría error de "función ya declarada" si algún
 * día se duplica esa función en otro snippet activo.
 */

// Variables de color, fuentes y estilos base del sitio (para que estén en cualquier página)
function gusto_base_styles() {
    ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,600;9..144,700;9..144,900&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
      :root{
        --bg-primary:#1D1D23;
        --bg-deep:#0D0705;
        --bg-brown:#2A1206;
        --gold:#D8AA35;
        --gold-bright:#ECAA00;
        --gold-deep:#B97810;
        --orange:#FA8C00;
        --text-light:#F2F2F2;
        --text-white:#FFFFFF;
        --text-muted:#9C9C9C;
        --border:rgba(216,170,53,0.22);
        --serif:'Fraunces', serif;
        --sans:'Manrope', sans-serif;
      }
      *{margin:0;padding:0;box-sizing:border-box;}
      html{scroll-behavior:smooth;}
      body{background:var(--bg-primary) !important; color:var(--text-light); font-family:var(--sans); line-height:1.6; overflow-x:hidden;}
      /* Igual que menu/carta.html original: el fondo oscuro va directo en el body, no en un div interno.
         Apunta al ID de la página de Menú (page-id-18) en vez de :has(), por compatibilidad. */
      body.page-id-18{background:var(--bg-deep) !important;}
      ::selection{background:var(--gold); color:var(--bg-deep);}
      ::-webkit-scrollbar{width:10px;}
      ::-webkit-scrollbar-track{background:var(--bg-deep);}
      ::-webkit-scrollbar-thumb{background:linear-gradient(var(--gold-deep), var(--gold)); border-radius:10px;}
      section{scroll-margin-top:80px;}
      .wrap{max-width:1200px; margin:0 auto; padding:0 24px;}
      h1,h2,h3{font-family:var(--serif); font-weight:700; letter-spacing:-0.01em;}
      a{color:inherit; text-decoration:none;}
      img,svg{max-width:100%; display:block;}
      .logo{font-family:var(--serif); font-size:1.5rem; font-weight:700; color:var(--gold); letter-spacing:0.02em;}
      .logo span{color:var(--text-white); font-weight:400; font-size:0.95rem; display:block; letter-spacing:0.3em; text-transform:uppercase; margin-top:-2px;}
      .logo:hover{color:var(--gold);}
      .logo:hover span{color:var(--text-white);}
    </style>
    <?php
}

// Header completo (nav con menú) - SOLO para la página de Inicio
add_shortcode('gusto_header_full', function() {
    ob_start();
    gusto_base_styles();
    ?>
    <style>
      .nav{position:fixed; top:0; left:0; right:0; z-index:1000; padding:20px 0; transition:background .4s ease, padding .4s ease, box-shadow .4s ease;}
      .nav.scrolled{background:rgba(13,7,5,0.82); backdrop-filter:blur(14px); -webkit-backdrop-filter:blur(14px); padding:12px 0; box-shadow:0 8px 30px rgba(0,0,0,0.35); border-bottom:1px solid var(--border);}
      .nav .wrap{display:flex; align-items:center; justify-content:space-between;}
      .nav-links{display:flex; gap:36px; align-items:center;}
      .nav-links a{font-size:0.92rem; font-weight:600; color:var(--text-light); position:relative; padding:4px 0; transition:color .3s;}
      .nav-links a::after{content:''; position:absolute; left:0; bottom:0; width:0; height:2px; background:var(--gold); transition:width .3s ease;}
      .nav-links a:hover{color:var(--gold-bright);}
      .nav-links a:hover::after{width:100%;}
      .nav-links a.nav-cta, .mobile-panel a.nav-cta{background:linear-gradient(135deg, var(--gold), var(--orange)); color:var(--bg-deep) !important; padding:10px 22px; border-radius:30px; font-weight:700; font-size:0.88rem; white-space:nowrap; box-shadow:0 4px 16px rgba(216,170,53,0.3); transition:transform .3s ease, box-shadow .3s ease;}
      .nav-cta:hover{transform:translateY(-2px); box-shadow:0 8px 22px rgba(216,170,53,0.45);}
      .nav-cta::after{display:none;}
      .nav-links a.nav-cta-delivery, .mobile-panel a.nav-cta-delivery{background:transparent; color:var(--gold-bright) !important; border:1.5px solid var(--gold); box-shadow:none;}
      .nav-cta-delivery:hover{background:rgba(216,170,53,0.12); box-shadow:none;}
      .burger{display:none; flex-direction:column; gap:5px; background:none; border:none; cursor:pointer; z-index:1100;}
      .burger span{width:26px; height:2px; background:var(--gold); transition:.3s;}
      .mobile-panel{position:fixed; inset:0; background:rgba(13,7,5,0.97); backdrop-filter:blur(10px); display:flex; flex-direction:column; align-items:center; justify-content:center; gap:28px; transform:translateY(-100%); transition:transform .45s ease; z-index:1050;}
      .mobile-panel.open{transform:translateY(0);}
      .mobile-panel a{font-family:var(--serif); font-size:1.6rem; color:var(--text-white);}
      .mobile-panel a.nav-cta{font-size:1rem;}
      @media (max-width:860px){ .nav-links{display:none;} .burger{display:flex;} }
    </style>

    <nav class="nav" id="nav">
      <div class="wrap">
        <a href="<?php echo esc_url( home_url('/') ); ?>" class="logo">Gusto<span>Comida Peruana</span></a>
        <div class="nav-links">
          <a href="#nosotros">Nosotros</a>
          <a href="#carta">Carta</a>
          <a href="#galeria">Galería</a>
          <a href="#resenas">Reseñas</a>
          <a href="#contacto">Contacto</a>
          <a href="<?php echo esc_url( home_url('/pedidos/') ); ?>" class="nav-cta nav-cta-delivery">¡Pedí Delivery!</a>
          <a href="https://wa.link/amosx9" target="_blank" rel="noopener" class="nav-cta">Reservá</a>
        </div>
        <button class="burger" id="burger" aria-label="Abrir menú"><span></span><span></span><span></span></button>
      </div>
    </nav>

    <div class="mobile-panel" id="mobilePanel">
      <a href="#nosotros">Nosotros</a>
      <a href="#carta">Carta</a>
      <a href="#galeria">Galería</a>
      <a href="#resenas">Reseñas</a>
      <a href="#contacto">Contacto</a>
      <a href="<?php echo esc_url( home_url('/pedidos/') ); ?>" class="nav-cta nav-cta-delivery">¡Pedí Delivery!</a>
      <a href="https://wa.link/amosx9" target="_blank" rel="noopener" class="nav-cta">Reservá por WhatsApp</a>
    </div>

    <script>
      (function(){
        var nav = document.getElementById('nav');
        if (nav) {
          window.addEventListener('scroll', function(){ nav.classList.toggle('scrolled', window.scrollY > 40); });
        }
        var burger = document.getElementById('burger');
        var panel = document.getElementById('mobilePanel');
        if (burger && panel) { burger.addEventListener('click', function(){ panel.classList.toggle('open'); }); }
      })();
    </script>
    <?php
    return ob_get_clean();
});

// Header simple (logo + volver al sitio) - para Menú y Pedidos
add_shortcode('gusto_header_simple', function() {
    ob_start();
    gusto_base_styles();
    ?>
    <style>
      .gusto-simple-header{
        position:fixed; top:0; left:0; right:0; z-index:1000;
        background:rgba(13,7,5,0.92); backdrop-filter:blur(10px);
        border-bottom:1px solid var(--border); padding:14px 0;
      }
      .gusto-simple-header-inner{display:flex; align-items:center; justify-content:space-between;}
      .gusto-back-link{font-size:0.85rem; font-weight:600; color:var(--gold); white-space:nowrap;}
      .gusto-back-link:hover{color:var(--gold-bright);}
    </style>
    <header class="gusto-simple-header">
      <div class="wrap gusto-simple-header-inner">
        <a href="<?php echo esc_url( home_url('/') ); ?>" class="logo">Gusto<span>Comida Peruana</span></a>
        <a href="<?php echo esc_url( home_url('/') ); ?>" class="gusto-back-link">&larr; Volver al sitio</a>
      </div>
    </header>
    <?php
    return ob_get_clean();
});
