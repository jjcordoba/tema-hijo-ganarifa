<?php
add_action('login_enqueue_scripts', 'bs_change_login_logo_styles');
function bs_change_login_logo_styles()
{
  $custom_logo_id = get_theme_mod('custom_logo');
  $logo = wp_get_attachment_image_src($custom_logo_id, 'full');
  $logo_url = $logo[0];
  ?>

  <style type="text/css">
    #login h1 a {
      background-image: url('<?php echo esc_url($logo_url); ?>');
      background-size: contain;
      width: 100%;
      height: 250px;
    }

    div p a {
      color: #ffffff!important;
      font-size: 18px;
    }
    div p a:hover {
      color: #edbb5a!important;
      font-size: 18px;
    }

    #loginform {
      border: 0;
    }

    body.login {
      background: linear-gradient(90deg, rgba(223, 17, 124, 1) 0%, rgba(108, 0, 146, 1) 100%) !important;
      background-repeat: no-repeat;
      background-size: cover;
      background-position: center;
    }

    .login-actions {
      display: flex;
      flex-direction: column-reverse;
      align-items: center;
    }
  </style>
<?php
}

add_filter('login_display_language_dropdown', '__return_false');
?>
