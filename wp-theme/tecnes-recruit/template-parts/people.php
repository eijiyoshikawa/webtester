<?php
$members_default = "先輩に支えられ始まった株式会社TECNESでの挑戦 次は私が後輩を支える番だ|電機システム部|営業職|M.M|2017年入社\n関わるすべての人のベクトルを合わせて 大型プロジェクトに挑む|プラント産業システム部|営業職|Y.T|2014年入社\n顧客利益だけでなく その先にある「社会に良いこと」を追求|環境・機械システム営業部|営業職|N.S|2017年入社\n主体性を持って企業と向き合い提案し、感謝される それが私の成長サイクル|新潟支店|営業職|O.T|2017年入社\n商社ならではの価値を追求し 幅広い業界へ提案 次は本社でさらなる挑戦へ|九州支店|営業職|J.A|2019年入社\n何事も全力で楽しむ 自社製品の海外展開という「未知なる挑戦」も楽しみたい|自動車部品部|営業職|W.R|2005年入社\nUVシステムの営業が切り拓く 「社会インフラ」と「持続可能な未来」への貢献|UVシステム営業部|営業職|E.M|2012年入社";

$rows = tecnes_parse_rows( tecnes_text( 'people_members', $members_default ), 5 );
$thumb_fallbacks = array(
    'people_thumbs01@2x.jpg', 'people_thumbs02@2x.jpg', 'people_thumbs03@2x.jpg',
    'people_thumbs04@2x.jpg', 'people_thumbs05@2x.jpg', 'people_thumbs06@2x.jpg',
    'people_thumbs07@2x.jpg',
);

$members = array();
foreach ( $rows as $i => $r ) {
    list( $catch, $dept, $role, $name, $year ) = $r;
    $members[] = array(
        'slot'     => 'people_' . ( $i + 1 ),
        'fallback' => isset( $thumb_fallbacks[ $i ] ) ? $thumb_fallbacks[ $i ] : end( $thumb_fallbacks ),
        'catch'    => $catch,
        'dept'     => $dept,
        'role'     => $role,
        'name'     => $name,
        'year'     => $year,
    );
}
?>
<section id="people" class="people">
    <div class="l-inner peopleTop">
        <h2 class="ttl01 js-animate fadeIn01">
            <span class="ttl01En"><?php echo esc_html( tecnes_text( 'people_ttl_en', 'PEOPLE' ) ); ?></span>
            <span class="ttl01Ja"><?php echo esc_html( tecnes_text( 'people_ttl_ja', '働く社員を知る' ) ); ?></span>
        </h2>
        <p class="leadTxt">
            <?php echo esc_html( tecnes_text( 'people_lead', '株式会社TECNESで新しい自分に出会い、変わらない自分の信念を持って活躍している先輩たち。どんな想いで働いているのか、エピソードを交えてご紹介します。' ) ); ?>
        </p>
    </div>

    <div class="mainImg">
        <img src="<?php echo tecnes_image( 'people_main', 'modal_img01-01@2x.jpg' ); ?>" alt="" width="1280" height="400" style="width:100%;height:auto;">
    </div>

    <div class="swiperWrap">
        <div class="swiper" id="js-people-swiper">
            <div class="swiper-wrapper">
                <?php foreach ( $members as $m ) : ?>
                    <div class="swiper-slide">
                        <div class="card">
                            <div class="cardThumb">
                                <img src="<?php echo tecnes_image( $m['slot'], $m['fallback'] ); ?>" alt="<?php echo esc_attr( $m['name'] ); ?>">
                            </div>
                            <div class="cardBody">
                                <p class="cardCatch"><?php echo esc_html( $m['catch'] ); ?></p>
                                <div class="cardMeta">
                                    <?php
                                        // 部署が空のときは「・」を出さずに役職のみ表示。
                                        // 役職側も空なら span を出さない。
                                        $meta_parts = array_filter( array( trim( $m['dept'] ), trim( $m['role'] ) ), 'strlen' );
                                        if ( $meta_parts ) :
                                    ?>
                                    <span class="cardDept"><?php echo esc_html( implode( '・', $meta_parts ) ); ?></span>
                                    <?php endif; ?>
                                    <span class="cardName"><?php echo esc_html( $m['name'] ); ?></span>
                                    <span class="cardYear"><?php echo esc_html( $m['year'] ); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
    </div>

</section>
