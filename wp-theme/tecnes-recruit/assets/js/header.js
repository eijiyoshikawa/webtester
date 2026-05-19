(function () {
    'use strict';

    /**
     * iOS Safari / 古い Android Chrome のアドレスバー高さ変動による
     * 「メニューが画面の半分しか出ない」バグ対策。
     *   100vh は描画時点のビューポート高だが、アドレスバー表示/非表示で
     *   ズレるため、JS で実測した値を --tecnes-vh に書き込んで利用する。
     *   Gnav.css 側でも 100dvh を併記しているが、dvh 未対応ブラウザの
     *   ために二重で防御する。
     */
    var setVhVar = function () {
        var vh = window.innerHeight * 0.01;
        document.documentElement.style.setProperty('--tecnes-vh', vh + 'px');
    };

    document.addEventListener('DOMContentLoaded', function () {
        var header = document.getElementById('js-l-header');
        var toggle = document.getElementById('js-menu-toggle');
        var gnav   = document.getElementById('js-gnav');

        setVhVar();
        // リサイズだけでなく、オリエンテーション変更とアドレスバー伸縮にも追従。
        window.addEventListener('resize', setVhVar);
        window.addEventListener('orientationchange', setVhVar);

        if (header) {
            var onScroll = function () {
                if (window.scrollY > 0) {
                    header.classList.add('fixed');
                } else {
                    header.classList.remove('fixed');
                }
            };
            window.addEventListener('scroll', onScroll, { passive: true });
            onScroll();
        }

        var closeMenu = function () {
            if (gnav) gnav.classList.remove('isOpen');
            document.body.classList.remove('is-menu-open');
        };

        var openMenu = function () {
            // メニューを開く瞬間に最新の vh を再計算して取りこぼし防止。
            setVhVar();
            if (gnav) gnav.classList.add('isOpen');
            document.body.classList.add('is-menu-open');
        };

        if (toggle) {
            toggle.addEventListener('click', function (e) {
                e.preventDefault();
                if (gnav && gnav.classList.contains('isOpen')) {
                    closeMenu();
                } else {
                    openMenu();
                }
            });
        }

        document.querySelectorAll('[data-gnav-close]').forEach(function (el) {
            el.addEventListener('click', closeMenu);
        });

        // Esc キーでも閉じられるように（アクセシビリティ）。
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && gnav && gnav.classList.contains('isOpen')) {
                closeMenu();
            }
        });
    });
})();
