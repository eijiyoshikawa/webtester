<?php
$slides = array(
    array( 'slot' => 'mv_slide_1', 'fallback' => 'mv_slide01-01.png' ),
    array( 'slot' => 'mv_slide_2', 'fallback' => 'mv_slide02-01.png' ),
    array( 'slot' => 'mv_slide_3', 'fallback' => 'mv_slide03-01.png' ),
    array( 'slot' => 'mv_slide_4', 'fallback' => 'mv_slide04-01.png' ),
);
?>
<div class="mv">
    <p class="mvScroll"><span class="mvScrollEn">SCROLL</span></p>
    <div class="mvInner">
        <div id="mvSlide01" class="splide mvSlider">
            <div class="splide__track">
                <ul class="splide__list">
                    <?php foreach ( $slides as $slide ) : ?>
                        <li class="splide__slide">
                            <span class="slideBg"></span>
                            <span class="slideImg">
                                <img src="<?php echo tecnes_image( $slide['slot'], $slide['fallback'] ); ?>" alt="" style="object-fit:cover;width:100%;height:100%;">
                            </span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="progressWrap">
                <div class="progressInner">
                    <button class="splideToggle splide__toggle" id="js-mv-toggle">
                        <span class="splide__toggle__play">▶</span>
                        <span class="splide__toggle__pause">⏸</span>
                    </button>
                    <div class="splideProgress">
                        <div class="splideProgressBar" id="js-mv-progress-bar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
