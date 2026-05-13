<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://kit.fontawesome.com/8f68a85f55.js" crossorigin="anonymous"></script>
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<!-- ✅ Start site-wrapper (for sticky footer layout) -->
<div class="site-wrapper">

<header class="site-header">
  <div class="header-inner">
    <div class="logo">
      <a href="<?php echo esc_url(home_url('/')); ?>">
        <?php bloginfo('name'); ?>
      </a>
    </div>
    
    <label class="dark-mode-toggle">
      <input type="checkbox" id="toggle-dark">
      <span class="slider"></span>
    </label>

    <!-- ✅ Desktop Menu -->
    <nav class="main-nav">
      <?php
      wp_nav_menu(array(
        'theme_location' => 'main-menu',
        'container' => false,
        'menu_class' => 'nav-menu',
      ));
      ?>
    </nav>

    <!-- Hamburger Button (for mobile) -->
    <button class="menu-toggle" id="menu-toggle" aria-label="Open menu">
      ☰
    </button>
  </div>
</header>

<!-- Mobile Sidebar Menu -->
<nav class="mobile-menu" id="mobile-menu">
  <button class="close-menu" id="close-menu" aria-label="Close menu">×</button>
  <?php
  wp_nav_menu(array(
    'theme_location' => 'mobile-menu',
    'container' => false,
    'menu_class' => 'nav-menu',
  ));
  ?>
</nav>

<!-- Overlay -->
<div class="menu-overlay" id="menu-overlay"></div>

<!-- ✅ Start site-content -->
<div class="site-content">
