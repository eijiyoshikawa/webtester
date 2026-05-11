<?php
get_header(); ?>

<main class="l-main">
    <?php
    foreach ( array( 'main-visual', 'read', 'business', 'flow', 'about', 'discovery', 'people', 'environment', 'footnav' ) as $part ) {
        get_template_part( 'template-parts/' . $part );
    }
    ?>
</main>

<?php get_footer();
