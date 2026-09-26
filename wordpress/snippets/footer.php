<?php
/**
 * Code Snippets → "Gusto - Footer"
 * [gusto_footer] -> va en las 3 páginas (Inicio, Menú, Pedidos), al final de cada una.
 */

add_shortcode('gusto_footer', function() {
    ob_start();
    gusto_base_styles();
    ?>
    <style>
      .footer-bg{
        background:var(--bg-deep); padding-top:40px; border-top:1px solid var(--border);
        width:100vw; margin-left:calc(-50vw + 50%);
      }
      .footer{
        padding:30px 0;
        display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:16px;
      }
      .footer p{color:var(--text-muted); font-size:0.85rem;}
      .footer .logo{font-size:1.1rem;}
    </style>
    <div class="footer-bg">
      <div class="wrap footer">
        <a href="<?php echo esc_url( home_url('/') ); ?>" class="logo">Gusto<span>Comida Peruana</span></a>
        <p>&copy; <?php echo esc_html( date('Y') ); ?> Gusto Comida Peruana. Todos los derechos reservados.</p>
      </div>
    </div>
    <?php
    return ob_get_clean();
});
