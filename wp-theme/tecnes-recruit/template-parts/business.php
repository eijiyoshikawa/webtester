<?php
$elec_default  = "電気・通信 #01|電気通信工事|情報通信のインフラを支える|光ファイバー、電話、LAN、無線設備などの情報通信インフラ整備を行います。オフィスや工場、公共施設のネットワーク基盤を構築し、社会のあらゆる通信を支えます。|＃光ファイバー敷設,＃LAN・通信工事,＃無線設備工事\n電気・通信 #02|電気工事|安全な電力をお届け|一般家庭から商業施設、工場まで、配電・配線、受変電設備の設置・改修を担当します。電気がなければ社会は動きません。安全と品質を最優先に施工します。|＃配電・配線工事,＃受変電設備,＃キュービクル\n電気・通信 #03|太陽光発電・再エネ工事|持続可能な未来をつくる|産業用・住宅用の太陽光発電システムの設計・施工を行います。カーボンニュートラル社会に向けて、クリーンエネルギーの導入をサポートします。|＃太陽光発電システム,＃蓄電池設置,＃再生可能エネルギー\n電気・通信 #04|メンテナンス|止まらない設備を守る|電気・通信設備の定期点検、故障時の緊急対応、改修工事まで一貫して対応します。トラブルを未然に防ぎ、お客さまの事業継続を支えます。|＃定期点検,＃緊急対応,＃設備改修";
$civil_default = "土木・建築 #01|土木工事|街の基盤をつくる|道路、造成、河川、上下水道など、生活と産業を支える社会インフラの整備を行います。安全で快適な暮らしの土台を、日々の施工で築き上げています。|＃道路工事,＃造成工事,＃インフラ整備\n土木・建築 #02|舗装工事|人と車の安全を支える|アスファルト舗装、コンクリート舗装を中心に、道路や駐車場、敷地内の舗装工事を施工します。耐久性と仕上がりにこだわり、長く使える道をつくります。|＃アスファルト舗装,＃コンクリート舗装,＃駐車場・敷地舗装\n土木・建築 #03|とび・土木工事|高所と地中の現場を担う|建設現場の仮設足場、解体、基礎工事など、現場を支える基幹工事を担当します。安全教育と豊富な経験で、難しい現場にも対応します。|＃仮設足場,＃基礎工事,＃解体工事\n土木・建築 #04|管工事|生活を支える\"水\"のプロフェッショナル|給排水設備、ガス配管、空調設備の配管工事を行います。生活と産業に不可欠なライフラインを、確かな技術で支えます。|＃給排水工事,＃ガス配管,＃空調設備配管";

$elec_fallbacks  = array( 'modal_img01-01@2x.jpg', 'modal_img01-02@2x.jpg', 'modal_img01-03@2x.jpg', 'modal_img01-04@2x.jpg' );
$civil_fallbacks = array( 'modal_img01-05@2x.jpg', 'modal_img01-06@2x.jpg', 'modal_img02-01@2x.jpg', 'modal_img02-02@2x.jpg' );

function tecnes_build_modals( $text, $id_prefix, $fallbacks, $slot_prefix ) {
    $rows  = tecnes_parse_rows( $text, 5 );
    $items = array();
    foreach ( $rows as $i => $r ) {
        list( $num, $title, $sub, $body, $keywords_csv ) = $r;
        $keywords = array_filter( array_map( 'trim', explode( ',', (string) $keywords_csv ) ) );
        $items[] = array(
            'id'       => $id_prefix . sprintf( '-%02d', $i + 1 ),
            'num'      => $num,
            'title'    => $title,
            'subTitle' => $sub,
            'body'     => $body,
            'keywords' => array_values( $keywords ),
            'slot'     => $slot_prefix . ( $i + 1 ),
            'fallback' => isset( $fallbacks[ $i ] ) ? $fallbacks[ $i ] : end( $fallbacks ),
        );
    }
    return $items;
}

$electrical_modals = tecnes_build_modals( tecnes_text( 'business_modals_elec',  $elec_default ),  'elec',  $elec_fallbacks,  'biz_trading_' );
$civil_modals      = tecnes_build_modals( tecnes_text( 'business_modals_civil', $civil_default ), 'civil', $civil_fallbacks, 'biz_mfg_' );
$all_modals        = array_merge( $electrical_modals, $civil_modals );
?>
<section id="business" class="business headerShow">
    <div class="l-inner businessInner">
        <h2 class="ttl01">
            <span class="ttl01En"><?php echo esc_html( tecnes_text( 'business_ttl_en', 'OUR BUSINESS' ) ); ?></span>
            <span class="ttl01Ja"><?php echo esc_html( tecnes_text( 'business_ttl_ja', '仕事を知る' ) ); ?></span>
        </h2>

        <p class="leadTxt">
            <?php echo wp_kses_post( tecnes_text( 'business_lead', '電気・通信から土木・建築まで、暮らしと産業を支える幅広い建設工事に対応。<br>確かな技術とチームワークで、地域社会のインフラを支えています。' ) ); ?>
        </p>

        <div class="cards">
            <div class="card">
                <div class="cardImg">
                    <img src="<?php echo tecnes_image( 'trading_card', 'trading_img@2x.jpg' ); ?>" alt="<?php echo esc_attr( tecnes_text( 'business_card1_en', 'ELECTRICAL' ) ); ?>">
                </div>
                <div class="cardBody">
                    <p class="cardEn"><?php echo esc_html( tecnes_text( 'business_card1_en', 'ELECTRICAL' ) ); ?></p>
                    <p class="cardJa"><?php echo esc_html( tecnes_text( 'business_card1_ja', '電気・通信のしごと' ) ); ?></p>
                    <h3 class="cardTtl"><?php echo wp_kses_post( tecnes_text( 'business_card1_ttl', '情報と電力で社会を<br>つなぐ建設のプロ' ) ); ?></h3>
                    <p class="cardLead"><?php echo esc_html( tecnes_text( 'business_card1_lead', '電気通信・電気工事・再エネ・メンテナンスの一貫対応' ) ); ?></p>
                    <ul class="cardCategories">
                        <?php foreach ( $electrical_modals as $cat ) : ?>
                            <li>
                                <button class="cardCategory" data-modal-open="<?php echo esc_attr( $cat['id'] ); ?>">
                                    <?php echo esc_html( $cat['title'] ); ?>
                                </button>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php if ( ! empty( $electrical_modals ) ) : ?>
                        <div class="arrowWrap" data-modal-open="<?php echo esc_attr( $electrical_modals[0]['id'] ); ?>" style="cursor:pointer;">
                            <span class="arrowTxt">MORE</span>
                            <span class="arrowIco"></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card">
                <div class="cardImg">
                    <img src="<?php echo tecnes_image( 'manufacturing_card', 'manufacturing_img@2x.jpg' ); ?>" alt="<?php echo esc_attr( tecnes_text( 'business_card2_en', 'CIVIL' ) ); ?>">
                </div>
                <div class="cardBody">
                    <p class="cardEn"><?php echo esc_html( tecnes_text( 'business_card2_en', 'CIVIL' ) ); ?></p>
                    <p class="cardJa"><?php echo esc_html( tecnes_text( 'business_card2_ja', '土木・建築のしごと' ) ); ?></p>
                    <h3 class="cardTtl"><?php echo wp_kses_post( tecnes_text( 'business_card2_ttl', '街と暮らしの基盤を<br>つくる建設のプロ' ) ); ?></h3>
                    <p class="cardLead"><?php echo esc_html( tecnes_text( 'business_card2_lead', '土木・舗装・とび土木・管工事まで、現場を支える総合力' ) ); ?></p>
                    <ul class="cardCategories">
                        <?php foreach ( $civil_modals as $cat ) : ?>
                            <li>
                                <button class="cardCategory" data-modal-open="<?php echo esc_attr( $cat['id'] ); ?>">
                                    <?php echo esc_html( $cat['title'] ); ?>
                                </button>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php if ( ! empty( $civil_modals ) ) : ?>
                        <div class="arrowWrap" data-modal-open="<?php echo esc_attr( $civil_modals[0]['id'] ); ?>" style="cursor:pointer;">
                            <span class="arrowTxt">MORE</span>
                            <span class="arrowIco"></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="modalOverlay" id="js-business-modal" hidden>
        <div class="modalBox">
            <button class="modalClose" data-modal-close aria-label="閉じる"></button>
            <div class="modalImg">
                <img src="" alt="" id="js-modal-img">
            </div>
            <div class="modalBody">
                <p class="modalNum" id="js-modal-num"></p>
                <h3 class="modalTtl" id="js-modal-ttl"></h3>
                <p class="modalSubTtl" id="js-modal-sub"></p>
                <p class="modalTxt" id="js-modal-txt"></p>
                <ul class="modalKeywords" id="js-modal-keywords"></ul>
                <div class="modalNav">
                    <button class="modalNavBtn" data-modal-prev>← 前の事業</button>
                    <button class="modalNavClose" data-modal-close>閉じる</button>
                    <button class="modalNavBtn" data-modal-next>次の事業 →</button>
                </div>
            </div>
        </div>
    </div>

    <script id="js-business-modal-data" type="application/json"><?php
        $payload = array();
        foreach ( $all_modals as $m ) {
            $m['img'] = tecnes_image( $m['slot'], $m['fallback'] );
            unset( $m['slot'], $m['fallback'] );
            $payload[] = $m;
        }
        echo wp_json_encode( $payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
    ?></script>
</section>
