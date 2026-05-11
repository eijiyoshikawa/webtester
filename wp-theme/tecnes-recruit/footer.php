<?php
?>
<footer class="lFooter">
    <div class="lFooterInner">
        <div class="rightBox">
            <ul class="menu01">
                <li class="btnTop">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="menuTtl">TOP</a>
                </li>
                <li>
                    <a href="<?php echo esc_url( home_url( '/#business' ) ); ?>" class="menuTtl">仕事を知る</a>
                    <ul class="subMenu">
                        <li><a href="<?php echo esc_url( home_url( '/#business' ) ); ?>">OUR BUSINESS 仕事を知る</a></li>
                        <li><a href="<?php echo esc_url( home_url( '/#flow' ) ); ?>">仕事の流れ</a></li>
                    </ul>
                </li>
                <li>
                    <a href="<?php echo esc_url( home_url( '/#people' ) ); ?>" class="menuTtl">働く社員を知る</a>
                </li>
                <li>
                    <a href="<?php echo esc_url( home_url( '/#environment' ) ); ?>" class="menuTtl">働く環境を知る</a>
                    <ul class="subMenu">
                        <li><a href="<?php echo esc_url( home_url( '/#environment' ) ); ?>">ENVIRONMENT 働く環境を知る</a></li>
                    </ul>
                </li>
                <li>
                    <a href="<?php echo esc_url( home_url( '/#footnav' ) ); ?>" class="menuTtl">採用情報</a>
                </li>
            </ul>
            <ul class="menu02">
                <?php $privacy = tecnes_opt( 'privacy_url' ); ?>
                <li>
                    <a href="<?php echo $privacy ? esc_url( $privacy ) : esc_url( home_url( '/privacy/' ) ); ?>"<?php echo $privacy ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>>
                        <span>プライバシーポリシー</span>
                    </a>
                </li>
                <?php if ( $corp = tecnes_opt( 'corp_site_url' ) ) : ?>
                <li>
                    <a href="<?php echo esc_url( $corp ); ?>" target="_blank" rel="noopener noreferrer">
                        <span>コーポレートサイト</span>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>

        <div class="leftBox">
            <p class="footerLogo">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <img src="<?php echo tecnes_image( 'logo', 'logo.png' ); ?>" alt="<?php bloginfo( 'name' ); ?>">
                </a>
            </p>
            <ul class="list01">
                <?php if ( $founded = tecnes_opt( 'founded_date' ) ) : ?>
                <li>
                    <span class="itemTtl">創立</span>
                    <span><?php echo esc_html( $founded ); ?></span>
                </li>
                <?php endif; ?>
                <?php if ( $capital = tecnes_opt( 'capital' ) ) : ?>
                <li>
                    <span class="itemTtl">資本金</span>
                    <span><?php echo esc_html( $capital ); ?></span>
                </li>
                <?php endif; ?>
            </ul>
            <?php $postal = tecnes_opt( 'postal_code' ); $addr = tecnes_opt( 'address' ); ?>
            <?php if ( $postal || $addr ) : ?>
            <p class="address">
                <?php echo esc_html( trim( $postal . ' ' . $addr ) ); ?>
                <br class="u-sm-max">
            </p>
            <?php endif; ?>
            <?php if ( $map = tecnes_opt( 'map_url' ) ) : ?>
            <a href="<?php echo esc_url( $map ); ?>" target="_blank" rel="noopener noreferrer" class="mapLink">
                <span>Googleマップ</span>
            </a>
            <?php endif; ?>
            <?php if ( $tel = tecnes_opt( 'tel' ) ) : ?>
            <p class="tel"><?php echo esc_html( $tel ); ?></p>
            <?php endif; ?>
            <ul class="list02">
                <?php foreach ( array( 'station_1', 'station_2', 'station_3' ) as $key ) :
                    $st = tecnes_opt( $key );
                    if ( ! $st ) continue;
                ?>
                <li>
                    <span class="stationIco">地</span>
                    <span><?php echo esc_html( $st ); ?></span>
                </li>
                <?php endforeach; ?>
            </ul>
            <p class="copyright">
                &copy; <?php echo esc_html( tecnes_opt( 'company_en', get_bloginfo( 'name' ) ) ); ?> All rights reserved.
            </p>
        </div>
    </div>
</footer>

<?php get_template_part( 'template-parts/line-cta' ); ?>
<?php wp_footer(); ?>
</body>
</html>
