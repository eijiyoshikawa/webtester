<?php
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="lHeader" id="js-l-header">
    <div class="lHeaderInner">
        <p class="logo">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logoLink">
                <span class="logoImage">
                    <img src="<?php echo tecnes_image( 'logo', 'logo.png' ); ?>" alt="<?php bloginfo( 'name' ); ?>" width="200" height="50">
                </span>
                <span class="logoTxt">RECRUIT SITE</span>
            </a>
        </p>

        <ul class="menu">
            <li><a href="<?php echo esc_url( home_url( '/#business' ) ); ?>"><span class="menuJpn">仕事を知る</span></a></li>
            <li><a href="<?php echo esc_url( home_url( '/#people' ) ); ?>"><span class="menuJpn">働く社員を知る</span></a></li>
            <li><a href="<?php echo esc_url( home_url( '/#environment' ) ); ?>"><span class="menuJpn">働く環境を知る</span></a></li>
        </ul>

        <ul class="btn">
            <li class="btnEntry">
                <a href="<?php echo tecnes_line_url(); ?>" target="_blank" rel="noopener noreferrer" class="btnEntryLink">
                    <span class="btnEntryLabel">LINEで応募</span>
                </a>
            </li>
        </ul>

        <p class="btnMenu">
            <a href="#" class="btnMenuLink" id="js-menu-toggle">
                <span class="btnMenuLine"></span>
                <span class="btnMenuLine"></span>
                <span class="btnMenuLine"></span>
                <span class="btnMenuText">MENU</span>
            </a>
        </p>
    </div>

    <?php get_template_part( 'template-parts/gnav' ); ?>
</header>
