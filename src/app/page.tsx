"use client";

import { useEffect, useState, FormEvent } from "react";

declare global {
  interface Window {
    AOS?: { init: (opts: Record<string, unknown>) => void };
    ttq?: { track: (event: string, params?: Record<string, unknown>) => void };
  }
}

const LINE_URL = "https://line.me/R/ti/p/@escopromotion";

const LineIcon = ({ size = 18 }: { size?: number }) => (
  <svg
    width={size}
    height={size}
    viewBox="0 0 24 24"
    fill="currentColor"
    aria-hidden="true"
    focusable="false"
  >
    <path d="M19.365 9.863c.349 0 .63.285.63.631 0 .345-.281.63-.63.63H17.61v1.125h1.755c.349 0 .63.283.63.63 0 .344-.282.629-.631.629h-2.386c-.346 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63h2.386c.346 0 .627.285.627.63 0 .349-.281.63-.63.63H17.61v1.125h1.755zm-3.855 3.016c0 .27-.174.51-.432.596-.064.021-.133.031-.199.031-.211 0-.391-.09-.51-.25l-2.443-3.317v2.94c0 .344-.279.629-.631.629-.346 0-.626-.285-.626-.629V8.108c0-.27.173-.51.43-.595.06-.023.136-.033.194-.033.195 0 .375.104.495.254l2.462 3.33V8.108c0-.345.282-.63.63-.63.345 0 .63.285.63.63v4.771zm-5.741 0c0 .344-.282.629-.631.629-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63.346 0 .628.285.628.63v4.771zm-2.466.629H4.917c-.345 0-.63-.285-.63-.629V8.108c0-.345.285-.63.63-.63.348 0 .63.285.63.63v4.141h1.756c.348 0 .629.283.629.63 0 .344-.282.629-.629.629M24 10.314C24 4.943 18.615.572 12 .572S0 4.943 0 10.314c0 4.811 4.27 8.842 10.035 9.608.391.082.923.258 1.058.59.12.301.079.766.038 1.08l-.164 1.02c-.045.301-.24 1.186 1.049.645 1.291-.539 6.916-4.078 9.436-6.975C23.176 14.393 24 12.458 24 10.314" />
  </svg>
);

const heroPcImages = [
  "/images/esco_LP/esco_top3.svg",
  "/images/esco_LP/esco_top1.svg",
  "/images/esco_LP/esco_top2.svg",
];

const galleryImgs = [
  "/images/esco_LP/gallery-01.svg",
  "/images/esco_LP/gallery-02.svg",
  "/images/esco_LP/gallery-03.svg",
  "/images/esco_LP/gallery-04.svg",
];

const faqItems: { q: string; a: string }[] = [
  {
    q: "ノルマはありますか?",
    a: "罰則型のノルマはありません。目標数値はありますが、達成できなくても給与が下がることはありません。成長のプロセスを重視して評価します。",
  },
  {
    q: "未経験でも本当に稼げますか?",
    a: "はい、稼げます。飲食・販売・倉庫など全くの異業種からの転職者が多数活躍中です。早い方は3ヶ月でインセンティブを得始め、1年以内に月収35万円を超えるメンバーも多くいます。",
  },
  {
    q: "研修はどのくらいの期間ですか?",
    a: "基本研修は1〜2ヶ月です。商品知識・トーク・接客マナーを丁寧に教えます。研修後も先輩のフォローが続きます。",
  },
  {
    q: "転勤はありますか?",
    a: "基本的に関西エリア内での勤務です。遠方への転勤はなく、直行直帰も可能です。",
  },
  {
    q: "在職中でも応募できますか?",
    a: "もちろんです。まずはLINEでご相談ください。状況に合わせて柔軟に対応します。",
  },
  {
    q: "まず話を聞くだけでも大丈夫ですか?",
    a: "大歓迎です。「まだ転職を決めていない」という方もお気軽にどうぞ。選考とは関係なく対応します。",
  },
];

const meritItems: {
  num: string;
  photo: string;
  alt: string;
  title: string;
  body: string;
  tag: string;
}[] = [
  {
    num: "01",
    photo: "/images/esco_LP/merit-01.svg",
    alt: "収入",
    title: "頑張りが、そのまま\n給料に反映される",
    body: "月給24万円スタート＋インセンティブ制度。賞与年2回・昇給ありで上限なく稼げます。入社1年以内に月収35万円を超えるメンバーも多数います。「給料が上がらない」という悩みとはここで永遠にお別れできます。",
    tag: "インセンティブ制度あり",
  },
  {
    num: "02",
    photo: "/images/esco_LP/merit-02.svg",
    alt: "研修",
    title: "未経験でも安心の\n研修・サポート体制",
    body: "入社後は先輩が毎日フォロー。学歴・経歴不問で、素直に学べる方なら必ず活躍できます。基本研修は1〜2ヶ月。飲食・販売・倉庫から転職した社員が多数活躍中です。",
    tag: "1〜2ヶ月の研修制度",
  },
  {
    num: "03",
    photo: "/images/esco_LP/merit-03.svg",
    alt: "休日",
    title: "直行直帰OK・\n年間休日120日",
    body: "完全週休2日制も選択可能。年末年始・GW・お盆休暇あり。関西エリア内の勤務なので遠方への転勤もありません。体力を消耗する仕事とは違い、オフもしっかり確保できます。",
    tag: "年間休日120日・転勤なし",
  },
];

const voiceItems = [
  {
    photo: "/images/esco_LP/voice-01.svg",
    name: "佐藤 健太",
    meta: "27歳・入社2年目／前職: 居酒屋店長",
    tag: "MEMBER 01",
    q1: "入社のきっかけは?",
    a1: "前職は居酒屋で月給22万円。どれだけ働いても給料が変わらず、将来に不安を感じていました。LINE相談で「未経験でも稼げる」と聞き、話だけでもと面談に行ったのがきっかけです。",
    q2: "今の仕事のやりがい",
    a2: "お客様に感謝されるのはもちろん、努力が月収に直結するので毎月の達成感がすごい。今は後輩の育成も任されていて、責任ある立場を早く経験できています。",
    salary: "42",
  },
  {
    photo: "/images/esco_LP/voice-02.svg",
    name: "田中 美咲",
    meta: "24歳・入社1年目／前職: アパレル販売",
    tag: "MEMBER 02",
    q1: "未経験で不安はなかった?",
    a1: "正直とても不安でした。でも研修が丁寧で、先輩も毎日フォローしてくれるので3ヶ月で独り立ちできました。女性も活躍しやすい環境です。",
    q2: "入社して変わったこと",
    a2: "収入が倍近く増えて、貯金もできるようになりました。休日もちゃんと取れるので、プライベートも充実しています。同世代より一歩先を行けている実感があります。",
    salary: "38",
  },
  {
    photo: "/images/esco_LP/voice-03.svg",
    name: "鈴木 翔",
    meta: "31歳・入社3年目／現: チームリーダー",
    tag: "MEMBER 03",
    q1: "前職から転職した理由",
    a1: "倉庫の仕分け作業を5年やっていて、体力的にも経済的にも限界でした。知り合いの紹介でエスコを知り、30歳からでも挑戦できると聞いて決断しました。",
    q2: "キャリアアップの実感",
    a2: "入社1年で月収40万円を達成、3年目でチームリーダーに。マネジメントも任されるようになり、将来の幹部候補として育ててもらっています。",
    salary: "55",
  },
];

const aDayItems = [
  { time: "09:30", title: "出社・朝礼", desc: "当日の担当会場・目標を共有。チームで士気を上げて1日をスタート。" },
  { time: "10:00", title: "商品学習・準備", desc: "新製品の知識インプットやトークの練習。イベントで使う資料や備品を準備。" },
  { time: "12:00", title: "ランチタイム", desc: "先輩・同期とランチ。ざっくばらんに相談できる時間です。" },
  { time: "13:00", title: "会場へ移動", desc: "担当のイベント会場へ直行。関西エリア内なので長距離移動はありません。" },
  { time: "14:00", title: "接客・アポイント獲得", desc: "来場者に省エネ機器のメリットを分かりやすく説明。興味を持った方にアポイントを取得。" },
  { time: "17:00", title: "事務所に帰社", desc: "会場を片付けて帰社。チーム全員で結果をシェア。" },
  { time: "18:00", title: "日報・翌日準備", desc: "1日の学びを日報にまとめる。翌日の目標も設定してルーティン化。" },
  { time: "19:00", title: "退勤", desc: "残業はほぼナシ。直行直帰が可能な日もあります。" },
];

const benefits = [
  { title: "各種社会保険完備", desc: "健康・厚生年金・雇用・労災すべて完備。" },
  { title: "交通費全額支給", desc: "通勤・イベント移動費は全額会社負担。" },
  { title: "研修制度", desc: "入社後1〜2ヶ月の基本研修＋OJT。" },
  { title: "資格取得支援", desc: "電気・省エネ関連資格の受験料を会社が負担。" },
  { title: "社員旅行", desc: "年1回、国内外の人気スポットへ。" },
  { title: "決算賞与", desc: "業績に応じた決算賞与あり。" },
  { title: "インセンティブ", desc: "成果に応じて毎月インセンティブを還元。" },
  { title: "制服貸与", desc: "イベント用の制服を貸与。クリーニング代も負担。" },
];

const careerSteps = [
  { year: "YEAR 0", role: "新入社員", salary: "24", desc: "研修期間。商品知識・接客・基本トークを身につける。" },
  { year: "YEAR 1", role: "営業スタッフ", salary: "35", desc: "独り立ちしインセンティブを獲得。平均月収35万円〜。", highlight: true },
  { year: "YEAR 3", role: "チームリーダー", salary: "50", desc: "後輩の育成・チーム管理も担当。月収50万円も可能。" },
  { year: "YEAR 5+", role: "マネージャー", salary: "70", desc: "エリア統括・幹部候補。月収70万円以上の実績多数。" },
];

const idealPoints = [
  "素直に学べる方",
  "人と話すことが好きな方",
  "稼ぎたい想いが強い方",
  "チームで協力できる方",
  "前向きに挑戦できる方",
  "成長意欲のある方",
];

const flowSteps = [
  { num: "01", title: "応募", duration: "1分", desc: "LINEまたは応募フォームから。履歴書は不要です。" },
  { num: "02", title: "LINE面談", duration: "30分", desc: "担当者とカジュアルに会話。疑問を解消できます。" },
  { num: "03", title: "一次面接", duration: "60分", desc: "現場マネージャーと対面。仕事内容を詳しく説明。" },
  { num: "04", title: "最終面接", duration: "60分", desc: "代表面接。お互いのマッチングを最終確認。" },
  { num: "05", title: "内定", duration: "最短1週間", desc: "内定から入社日まで柔軟に調整可能です。" },
];

export default function Home() {
  const [slideIndex, setSlideIndex] = useState(0);
  const [scrolled, setScrolled] = useState(false);
  const [openFaq, setOpenFaq] = useState<number | null>(null);
  const [drawerOpen, setDrawerOpen] = useState(false);
  const [showBackTop, setShowBackTop] = useState(false);
  const [progress, setProgress] = useState(0);
  const [exitModalOpen, setExitModalOpen] = useState(false);
  const [form, setForm] = useState({
    name: "",
    age: "",
    tel: "",
    email: "",
    position: "営業職(正社員)",
    message: "",
  });
  const [status, setStatus] = useState<"idle" | "sending" | "done" | "error">(
    "idle"
  );

  // AOS init (with retry for CDN load timing)
  useEffect(() => {
    const tryInit = () => {
      if (typeof window !== "undefined" && window.AOS) {
        window.AOS.init({ duration: 600, once: true, offset: 40 });
        return true;
      }
      return false;
    };
    if (tryInit()) return;
    const id = setInterval(() => {
      if (tryInit()) clearInterval(id);
    }, 100);
    const timeout = setTimeout(() => clearInterval(id), 5000);
    return () => {
      clearInterval(id);
      clearTimeout(timeout);
    };
  }, []);

  // Hero slider
  useEffect(() => {
    const id = setInterval(() => {
      setSlideIndex((prev) => (prev + 1) % heroPcImages.length);
    }, 3000);
    return () => clearInterval(id);
  }, []);

  // Scroll-related: header shadow, progress bar, back-to-top visibility
  useEffect(() => {
    let raf = 0;
    const onScroll = () => {
      if (raf) return;
      raf = requestAnimationFrame(() => {
        const y = window.scrollY;
        const h = document.documentElement.scrollHeight - window.innerHeight;
        setScrolled(y > 40);
        setShowBackTop(y > 600);
        setProgress(h > 0 ? Math.min(100, (y / h) * 100) : 0);
        raf = 0;
      });
    };
    onScroll();
    window.addEventListener("scroll", onScroll, { passive: true });
    return () => {
      window.removeEventListener("scroll", onScroll);
      if (raf) cancelAnimationFrame(raf);
    };
  }, []);

  // Converge-on-scroll (IntersectionObserver)
  useEffect(() => {
    if (typeof window === "undefined") return;
    const els = document.querySelectorAll<HTMLElement>("[data-converge]");
    if (!els.length) return;
    if (!("IntersectionObserver" in window)) {
      els.forEach((el) => el.classList.add("in"));
      return;
    }
    const io = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add("in");
            io.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.15, rootMargin: "0px 0px -40px 0px" }
    );
    els.forEach((el) => io.observe(el));
    return () => io.disconnect();
  }, []);

  // CountUp on view (NUMBERS / CAREER)
  useEffect(() => {
    if (typeof window === "undefined") return;
    if (!("IntersectionObserver" in window)) return;
    const targets = document.querySelectorAll<HTMLElement>("[data-countup]");
    if (!targets.length) return;
    const io = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (!entry.isIntersecting) return;
          const el = entry.target as HTMLElement;
          const end = Number(el.dataset.countup || "0");
          const duration = 1200;
          const start = performance.now();
          const render = (t: number) => {
            const p = Math.min(1, (t - start) / duration);
            const eased = 1 - Math.pow(1 - p, 3);
            el.textContent = String(Math.round(end * eased));
            if (p < 1) requestAnimationFrame(render);
          };
          requestAnimationFrame(render);
          io.unobserve(el);
        });
      },
      { threshold: 0.4 }
    );
    targets.forEach((t) => io.observe(t));
    return () => io.disconnect();
  }, []);

  // Exit intent (desktop only, one-time per session)
  useEffect(() => {
    if (typeof window === "undefined") return;
    if (window.matchMedia("(max-width: 900px)").matches) return;
    if (sessionStorage.getItem("exit-shown")) return;
    const onLeave = (e: MouseEvent) => {
      if (e.clientY <= 0) {
        sessionStorage.setItem("exit-shown", "1");
        setExitModalOpen(true);
        document.removeEventListener("mouseleave", onLeave);
      }
    };
    // delay binding so modal doesn't pop on immediate navigation
    const t = setTimeout(() => {
      document.addEventListener("mouseleave", onLeave);
    }, 15000);
    return () => {
      clearTimeout(t);
      document.removeEventListener("mouseleave", onLeave);
    };
  }, []);

  // Close drawer on ESC + lock body scroll while open
  useEffect(() => {
    const onKey = (e: KeyboardEvent) => {
      if (e.key === "Escape") {
        setDrawerOpen(false);
        setExitModalOpen(false);
      }
    };
    window.addEventListener("keydown", onKey);
    return () => window.removeEventListener("keydown", onKey);
  }, []);

  const trackContact = () => {
    if (typeof window !== "undefined" && window.ttq) {
      try {
        window.ttq.track("Contact");
      } catch {
        /* noop */
      }
    }
  };

  const trackSubmit = () => {
    if (typeof window !== "undefined" && window.ttq) {
      try {
        window.ttq.track("SubmitForm");
      } catch {
        /* noop */
      }
    }
  };

  const onSubmit = async (e: FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    setStatus("sending");
    try {
      const res = await fetch("/api/apply", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(form),
      });
      if (!res.ok) throw new Error("failed");
      setStatus("done");
      trackSubmit();
    } catch {
      setStatus("error");
    }
  };

  const tocItems: { label: string; en: string; href: string }[] = [
    { label: "代表メッセージ", en: "CEO", href: "#ceo" },
    { label: "数字で見るエスコ", en: "NUMBERS", href: "#numbers" },
    { label: "選ばれる理由", en: "MERIT", href: "#merit" },
    { label: "先輩社員の声", en: "VOICE", href: "#voice" },
    { label: "1日の流れ", en: "A DAY", href: "#aday" },
    { label: "福利厚生", en: "BENEFITS", href: "#benefits" },
    { label: "キャリアパス", en: "CAREER", href: "#career" },
    { label: "募集要項", en: "RECRUITMENT", href: "#recruitment" },
    { label: "選考フロー", en: "FLOW", href: "#flow" },
    { label: "よくある質問", en: "FAQ", href: "#faq" },
    { label: "応募フォーム", en: "APPLY", href: "#apply" },
  ];

  return (
    <>
      {/* Background animated blobs */}
      <div className="bg-anim" aria-hidden="true">
        <div className="blob blob-1" />
        <div className="blob blob-2" />
        <div className="blob blob-3" />
      </div>

      {/* Scroll progress bar */}
      <div
        className="scroll-progress"
        style={{ width: `${progress}%` }}
        aria-hidden="true"
      />

      {/* ====== HEADER ====== */}
      <header className={`lp-header${scrolled ? " scrolled" : ""}`}>
        <div className="header">
          <a href="#" className="header-logo" aria-label="ESCO PROMOTION">
            <img
              src="/images/esco_LP/esco_logo.svg"
              alt="ESCO PROMOTION 株式会社エスコプロモーション"
            />
          </a>
          <div style={{ display: "flex", alignItems: "center", gap: 4 }}>
            <button
              type="button"
              className={`header-menu-btn${drawerOpen ? " open" : ""}`}
              aria-label="メニューを開く"
              aria-expanded={drawerOpen}
              aria-controls="sp-drawer"
              onClick={() => setDrawerOpen((v) => !v)}
            >
              <span />
            </button>
            <a
              className="header-cta"
              href={LINE_URL}
              target="_blank"
              rel="noopener noreferrer"
              onClick={trackContact}
            >
              <LineIcon size={14} />
              LINEで相談
            </a>
          </div>
        </div>
      </header>

      {/* SP drawer */}
      <div
        id="sp-drawer"
        className={`sp-drawer${drawerOpen ? " open" : ""}`}
        role="dialog"
        aria-label="ナビゲーションメニュー"
        aria-hidden={!drawerOpen}
      >
        <nav>
          {tocItems.map((item) => (
            <a
              key={item.href}
              href={item.href}
              onClick={() => setDrawerOpen(false)}
            >
              {item.label}
              <span className="en">{item.en}</span>
            </a>
          ))}
          <a
            className="drawer-cta"
            href={LINE_URL}
            target="_blank"
            rel="noopener noreferrer"
            onClick={() => {
              trackContact();
              setDrawerOpen(false);
            }}
          >
            <LineIcon size={18} />
            LINEで相談する
          </a>
        </nav>
      </div>

      <main>
        {/* ====== HERO ====== */}
        <section className="hero" aria-label="キービジュアル">
          <div className="hero-slides">
            {heroPcImages.map((src, i) => (
              <img
                key={src}
                className={`hero-img-pc${i === slideIndex ? " active" : ""}`}
                src={src}
                alt="エスコプロモーション"
              />
            ))}
          </div>
          <img
            className="hero-img-sp"
            src="/images/esco_LP/hero.svg"
            alt="エスコプロモーション"
          />
          <div className="hero-scroll" aria-hidden="true">
            <span>SCROLL</span>
            <svg
              width="18"
              height="18"
              viewBox="0 0 24 24"
              fill="none"
              strokeWidth="1.5"
              strokeLinecap="round"
              strokeLinejoin="round"
            >
              <path d="M12 5v14M5 12l7 7 7-7" />
            </svg>
          </div>
        </section>

        {/* ====== HERO TEXT ====== */}
        <section className="hero-text-sec" data-aos="fade-up" data-aos-delay="100">
          <h1>
            あなたの<em>「もっと稼ぎたい」</em>を、
            <br />
            現実にする会社。
          </h1>
          <p className="hero-sub">
            ESCO PROMOTION ／ 中途採用 ／ 営業職
          </p>
          <div className="trust-badges" data-aos="fade-up" data-aos-delay="200">
            <span className="trust-badge">
              <span className="num">14<span>年</span></span>創業からの実績
            </span>
            <span className="trust-badge">
              <span className="num">92<span>%</span></span>3年定着率
            </span>
            <span className="trust-badge">
              <span className="num">120<span>名</span></span>在籍社員
            </span>
            <span className="trust-badge">
              <span className="num">100<span>%</span></span>未経験スタート
            </span>
          </div>
        </section>

        {/* ====== GALLERY ====== */}
        <section className="gallery-sec" aria-label="ギャラリー">
          <div className="gallery-wrap">
            {[...galleryImgs, ...galleryImgs].map((src, i) => (
              <img
                key={`${src}-${i}`}
                src={src}
                alt=""
                role="presentation"
                loading="lazy"
              />
            ))}
          </div>
        </section>

        {/* ====== MESSAGE ====== */}
        <section className="message-sec">
          <div className="message-inner">
            <div className="message-text" data-aos="fade-up">
              <span className="sec-label">MESSAGE</span>
              <h2>
                「頑張る人が、
                <br />
                ちゃんと<span>稼げる</span>会社」
              </h2>
              <p>
                飲食・販売・倉庫で一生懸命働いても、給料が変わらない。
                <br />
                そんな悔しさを持つあなたに届けたい。
                <br />
                エスコプロモーションは、あなたの努力が
                <br />
                そのまま収入になる環境を用意しています。
              </p>
              <a
                className="btn-primary"
                href={LINE_URL}
                target="_blank"
                rel="noopener noreferrer"
                onClick={trackContact}
              >
                <LineIcon size={18} />
                まずはLINEで話を聞く
              </a>
            </div>
            <div
              className="message-photo"
              data-aos="fade-up"
              data-aos-delay="100"
            >
              <img src="/images/esco_LP/merit-03.svg" alt="エスコのチーム" />
            </div>
          </div>
        </section>

        {/* ====== CEO MESSAGE ====== */}
        <section className="ceo-sec" id="ceo">
          <div className="ceo-inner">
            <div className="ceo-photo" data-converge="left">
              <img src="/images/esco_LP/ceo.svg" alt="代表取締役 山田 太郎" />
            </div>
            <div className="ceo-body" data-converge="right">
              <span className="sec-label">CEO MESSAGE</span>
              <h2>
                社員一人ひとりの
                <br />
                <em>「人生の豊かさ」</em>が、
                <br />
                会社の未来をつくる。
              </h2>
              <p>
                私自身、若い頃は「頑張っても報われない環境」で悩んでいました。だからこそエスコプロモーションでは、
                努力した人がきちんと収入を得られる仕組みを整えています。
              </p>
              <p>
                学歴や経歴ではなく、素直さと向上心。それさえあれば、どんなバックグラウンドの方でも活躍できます。
                あなたの可能性を、私たちと一緒に広げていきませんか。
              </p>
              <div className="ceo-sign">
                <span className="role">代表取締役</span>
                <span className="name">山田 太郎</span>
              </div>
            </div>
          </div>
        </section>

        {/* ====== NUMBERS ====== */}
        <section className="sec bg-gray" id="numbers">
          <div className="inner">
            <div style={{ textAlign: "center" }} data-aos="fade-up">
              <span className="sec-label">NUMBERS</span>
            </div>
            <div className="numbers-grid">
              <div className="num-item" data-converge="left">
                <span className="num">
                  <span data-countup="35">0</span>
                  <span>万円〜</span>
                </span>
                <span className="num-label">入社1年目で目指せる月収</span>
                <p className="num-desc">
                  月給24万円＋インセンティブ。
                  <br />
                  頑張り次第で青天井。
                </p>
              </div>
              <div className="num-item" data-converge="scale" data-d="1">
                <span className="num">
                  <span data-countup="120">0</span>
                  <span>日</span>
                </span>
                <span className="num-label">年間休日</span>
                <p className="num-desc">
                  完全週休2日制も選択可。
                  <br />
                  年末年始・GW・お盆あり。
                </p>
              </div>
              <div className="num-item" data-converge="right" data-d="2">
                <span className="num">
                  <span data-countup="0">0</span>
                  <span>円</span>
                </span>
                <span className="num-label">必要な初期費用</span>
                <p className="num-desc">
                  未経験・学歴不問。
                  <br />
                  研修費・道具代は全額会社負担。
                </p>
              </div>
            </div>
          </div>
        </section>

        {/* ====== COMPARE ====== */}
        <section className="sec">
          <div className="inner">
            <div style={{ textAlign: "center" }} data-aos="fade-up">
              <span className="sec-label">COMPARE</span>
              <h2
                style={{
                  fontSize: "clamp(22px, 3.5vw, 36px)",
                  lineHeight: 1.35,
                }}
              >
                同じフルタイムでも、
                <br />
                月収にこれだけの差がある。
              </h2>
            </div>

            <div className="compare-wrap">
              <div className="compare-side other" data-converge="left">
                <div className="compare-top">
                  <span className="tag">他の仕事(例)</span>
                  <h3>飲食・販売・倉庫スタッフ</h3>
                </div>
                <div className="compare-row">
                  <span className="cr-label">月収目安</span>
                  <span className="cr-val">18〜21万円</span>
                </div>
                <div className="compare-row">
                  <span className="cr-label">収入の上限</span>
                  <span className="cr-val" style={{ fontSize: 14 }}>
                    ほぼ固定
                  </span>
                </div>
                <div className="compare-row">
                  <span className="cr-label">インセンティブ</span>
                  <span className="cr-val" style={{ fontSize: 14 }}>
                    なし
                  </span>
                </div>
                <div className="compare-row">
                  <span className="cr-label">5年後の月収</span>
                  <span className="cr-val" style={{ fontSize: 14 }}>
                    ほぼ変わらず
                  </span>
                </div>
              </div>

              <div className="compare-vs" data-converge="scale" data-d="1">VS</div>

              <div className="compare-side espro" data-converge="right" data-d="2">
                <div className="compare-top">
                  <span className="tag">当社</span>
                  <h3>エスコプロモーション・営業職</h3>
                </div>
                <div className="compare-row">
                  <span className="cr-label">月収目安</span>
                  <span className="cr-val">35万円〜</span>
                </div>
                <div className="compare-row">
                  <span className="cr-label">収入の上限</span>
                  <span className="cr-val" style={{ fontSize: 16 }}>
                    青天井
                  </span>
                </div>
                <div className="compare-row">
                  <span className="cr-label">インセンティブ</span>
                  <span className="cr-val" style={{ fontSize: 16 }}>
                    あり
                  </span>
                </div>
                <div className="compare-row">
                  <span className="cr-label">5年後の月収</span>
                  <span className="cr-val" style={{ fontSize: 16 }}>
                    実力次第
                  </span>
                </div>
              </div>
            </div>

            <p className="compare-note">
              ※他社の月収は時給×8h×22日の目安。エスコの数値は入社1年目社員の平均実績です。
            </p>

            <div className="compare-cta" data-aos="fade-up">
              <p>
                同じ時間を使うなら、
                <br />
                <em>稼げる仕事を選ぶべき</em>ではないですか?
              </p>
              <a
                className="btn-primary"
                href={LINE_URL}
                target="_blank"
                rel="noopener noreferrer"
                onClick={trackContact}
              >
                <LineIcon size={18} />
                LINEで詳しく聞いてみる
              </a>
            </div>
          </div>
        </section>

        {/* ====== MERIT ====== */}
        <section className="sec bg-gray" style={{ padding: 0 }} id="merit">
          <div className="merit-title-block">
            <span className="sec-label">MERIT</span>
            <h2>
              エスコプロモーションで
              <br />
              働く3つの理由
            </h2>
          </div>

          <div className="merit-list">
            {meritItems.map((m, i) => (
              <article
                key={m.num}
                className="merit-item"
                data-converge={i % 2 === 0 ? "left" : "right"}
              >
                <div className="merit-photo">
                  <img src={m.photo} alt={m.alt} />
                  <span className="merit-num-bg">{m.num}</span>
                </div>
                <div className="merit-body">
                  <span className="sec-label">{`MERIT ${m.num}`}</span>
                  <h3>{m.title}</h3>
                  <p>{m.body}</p>
                  <span className="tag">{m.tag}</span>
                </div>
              </article>
            ))}
          </div>
        </section>

        {/* ====== VOICE ====== */}
        <section className="sec" id="voice">
          <div className="inner">
            <div style={{ textAlign: "center" }} data-aos="fade-up">
              <span className="sec-label">VOICE</span>
              <h2>
                先輩社員の声
              </h2>
            </div>
            <div className="voice-grid">
              {voiceItems.map((v, i) => {
                const dir = i === 0 ? "left" : i === 1 ? "scale" : "right";
                return (
                <article
                  key={v.name}
                  className="voice-card"
                  data-converge={dir}
                  data-d={i + 1}
                >
                  <div className="voice-photo">
                    <img src={v.photo} alt={v.name} />
                  </div>
                  <div className="voice-body">
                    <span className="voice-tag">{v.tag}</span>
                    <h3 className="voice-name">{v.name}</h3>
                    <p className="voice-meta">{v.meta}</p>
                    <p className="voice-q">{v.q1}</p>
                    <p className="voice-a">{v.a1}</p>
                    <p className="voice-q">{v.q2}</p>
                    <p className="voice-a">{v.a2}</p>
                    <div className="voice-salary">
                      <span className="label">現在の月収</span>
                      <span className="val">
                        {v.salary}<span>万円</span>
                      </span>
                    </div>
                  </div>
                </article>
                );
              })}
            </div>
          </div>
        </section>

        {/* ====== A DAY ====== */}
        <section className="sec bg-gray" id="aday">
          <div className="inner">
            <div style={{ textAlign: "center" }} data-aos="fade-up">
              <span className="sec-label">A DAY</span>
              <h2>営業スタッフの1日</h2>
            </div>
            <div className="aday-wrap">
              {aDayItems.map((item, i) => (
                <div
                  key={item.time}
                  className="aday-item"
                  data-converge={i % 2 === 0 ? "left-s" : "right-s"}
                  data-d={Math.min(i + 1, 5)}
                >
                  <div className="aday-time">{item.time}</div>
                  <div className="aday-dot" aria-hidden="true" />
                  <div className="aday-content">
                    <h3>{item.title}</h3>
                    <p>{item.desc}</p>
                  </div>
                </div>
              ))}
            </div>
          </div>
        </section>

        {/* ====== BENEFITS ====== */}
        <section className="sec" id="benefits">
          <div className="inner">
            <div style={{ textAlign: "center" }} data-aos="fade-up">
              <span className="sec-label">BENEFITS</span>
              <h2>福利厚生・待遇</h2>
            </div>
            <div className="benefits-grid">
              {benefits.map((b, i) => (
                <div
                  key={b.title}
                  className="benefit-item"
                  data-converge="up"
                  data-d={Math.min(i + 1, 6)}
                >
                  <div className="benefit-icon" aria-hidden="true">
                    <svg
                      viewBox="0 0 24 24"
                      fill="none"
                      stroke="currentColor"
                      strokeWidth="2"
                      strokeLinecap="round"
                      strokeLinejoin="round"
                    >
                      <polyline points="20 6 9 17 4 12" />
                    </svg>
                  </div>
                  <h3>{b.title}</h3>
                  <p>{b.desc}</p>
                </div>
              ))}
            </div>
          </div>
        </section>

        {/* ====== CAREER ====== */}
        <section className="sec bg-gray" id="career">
          <div className="inner">
            <div style={{ textAlign: "center" }} data-aos="fade-up">
              <span className="sec-label">CAREER PATH</span>
              <h2>
                入社後のキャリアパス
              </h2>
            </div>
            <div className="career-wrap">
              {careerSteps.map((c, i) => {
                const dir =
                  i === 0 ? "left" : i === 1 ? "left-s" : i === 2 ? "right-s" : "right";
                return (
                  <div
                    key={c.year}
                    className={`career-step${c.highlight ? " highlight" : ""}`}
                    data-converge={dir}
                    data-d={i + 1}
                  >
                    <div className="career-year">{c.year}</div>
                    <div className="career-role">{c.role}</div>
                    <div className="career-salary">
                      <span data-countup={c.salary}>0</span>
                      <span>万円〜</span>
                    </div>
                    <p className="career-desc">{c.desc}</p>
                  </div>
                );
              })}
            </div>
          </div>
        </section>

        {/* ====== IDEAL CANDIDATE ====== */}
        <section className="sec" id="ideal">
          <div className="inner">
            <div style={{ textAlign: "center" }} data-aos="fade-up">
              <span className="sec-label">IDEAL</span>
              <h2>こんな方を求めています</h2>
            </div>
            <div className="ideal-list">
              {idealPoints.map((p, i) => (
                <div
                  key={i}
                  className="ideal-item"
                  data-converge={i % 2 === 0 ? "left-s" : "right-s"}
                  data-d={Math.min(Math.floor(i / 2) + 1, 3)}
                >
                  <div className="ideal-check" aria-hidden="true">
                    <svg
                      viewBox="0 0 24 24"
                      fill="none"
                      stroke="currentColor"
                      strokeWidth="3"
                      strokeLinecap="round"
                      strokeLinejoin="round"
                    >
                      <polyline points="20 6 9 17 4 12" />
                    </svg>
                  </div>
                  <span className="ideal-text">{p}</span>
                </div>
              ))}
            </div>
          </div>
        </section>

        {/* ====== LINE CTA ====== */}
        <section className="line-cta-sec">
          <div className="inner">
            <span className="kicker" data-aos="fade-up">
              CONTACT
            </span>
            <h2 data-aos="fade-up">
              まずは気軽に
              <br />
              話を聞いてみませんか?
            </h2>
            <p data-aos="fade-up" data-aos-delay="80">
              「転職するか迷っている」「詳しく聞きたいだけ」でも大歓迎。
              <br />
              選考とは関係なく、どんな質問にも答えます。
            </p>
            <div data-aos="fade-up" data-aos-delay="120">
              <a
                className="btn-line-lg"
                href={LINE_URL}
                target="_blank"
                rel="noopener noreferrer"
                onClick={trackContact}
              >
                <LineIcon size={22} />
                公式LINEで相談する(無料)
              </a>
            </div>
          </div>
        </section>

        {/* ====== RECRUITMENT ====== */}
        <section className="sec" id="recruitment">
          <div className="inner">
            <div style={{ textAlign: "center" }} data-aos="fade-up">
              <span className="sec-label">RECRUITMENT</span>
              <h2 data-aos="fade-up">募集要項</h2>
            </div>
            <div className="recruit-wrap" data-aos="fade-up">
              <table className="recruit-table">
                <tbody>
                  <tr>
                    <th scope="row">職種</th>
                    <td>営業スタッフ(省エネ機器のイベント案内・アポイント獲得)</td>
                  </tr>
                  <tr>
                    <th scope="row">仕事内容</th>
                    <td>
                      省エネ機器のイベントでの案内・アポイント獲得。まずは運営・接客からスタート。
                    </td>
                  </tr>
                  <tr>
                    <th scope="row">応募資格</th>
                    <td>
                      未経験OK(研修あり)／学歴・経歴不問／営業・接客経験があれば優遇
                      <br />
                      素直で前向きに学べる方・コミュニケーションが好きな方歓迎
                    </td>
                  </tr>
                  <tr>
                    <th scope="row">勤務地</th>
                    <td>本社(大阪市中央区)／関西一円のイベント会場　直行直帰OK</td>
                  </tr>
                  <tr>
                    <th scope="row">勤務時間</th>
                    <td>10:00〜19:00(休憩1時間)</td>
                  </tr>
                  <tr>
                    <th scope="row">休日・休暇</th>
                    <td>
                      完全週休2日制 or 週休2日制(選択可)
                      <br />
                      年末年始・GW・お盆休暇あり／年間休日120日
                    </td>
                  </tr>
                  <tr>
                    <th scope="row">給与</th>
                    <td>
                      月給24万円〜＋インセンティブ(経験・能力考慮)　賞与年2回／昇給あり
                    </td>
                  </tr>
                  <tr>
                    <th scope="row">雇用形態</th>
                    <td>正社員</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </section>

        {/* ====== FLOW (selection steps) ====== */}
        <section className="sec bg-gray" id="flow">
          <div className="inner">
            <div style={{ textAlign: "center" }} data-aos="fade-up">
              <span className="sec-label">FLOW</span>
              <h2>応募から内定までの流れ</h2>
            </div>
            <div className="flow-wrap">
              {flowSteps.map((s, i) => {
                const dir =
                  i === 0 ? "left" :
                  i === 1 ? "left-s" :
                  i === 2 ? "scale" :
                  i === 3 ? "right-s" : "right";
                return (
                <div
                  key={s.num}
                  className="flow-step"
                  data-converge={dir}
                  data-d={i + 1}
                >
                  <span className="flow-num">STEP {s.num}</span>
                  <div className="flow-title">{s.title}</div>
                  <div className="flow-duration">所要: {s.duration}</div>
                  <p className="flow-desc">{s.desc}</p>
                </div>
                );
              })}
            </div>
          </div>
        </section>

        {/* ====== FAQ ====== */}
        <section className="sec bg-gray" id="faq">
          <div className="inner">
            <div style={{ textAlign: "center" }} data-aos="fade-up">
              <span className="sec-label">FAQ</span>
              <h2>よくある質問</h2>
            </div>
            <div className="faq-list" data-aos="fade-up">
              {faqItems.map((item, i) => {
                const open = openFaq === i;
                return (
                  <div
                    key={i}
                    className={`faq-item${open ? " open" : ""}`}
                  >
                    <button
                      type="button"
                      className="faq-q"
                      aria-expanded={open}
                      aria-controls={`faq-a-${i}`}
                      onClick={() => setOpenFaq(open ? null : i)}
                    >
                      <span className="faq-q-mark">Q.</span>
                      <span className="faq-qtxt">{item.q}</span>
                      <svg
                        className="faq-arrow"
                        width="18"
                        height="18"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        strokeWidth="1.8"
                        strokeLinecap="round"
                        strokeLinejoin="round"
                        aria-hidden="true"
                      >
                        <path d="M6 9l6 6 6-6" />
                      </svg>
                    </button>
                    {open && (
                      <div
                        id={`faq-a-${i}`}
                        className="faq-a"
                        role="region"
                      >
                        {item.a}
                      </div>
                    )}
                  </div>
                );
              })}
            </div>
          </div>
        </section>

        {/* ====== APPLY ====== */}
        <section className="sec" id="apply">
          <div className="inner">
            <div style={{ textAlign: "center" }} data-aos="fade-up">
              <span className="sec-label">APPLY</span>
              <h2>応募フォーム</h2>
            </div>
            <div className="apply-box" data-aos="fade-up">
              <p className="apply-lead">
                「詳しく聞いてみたい」「迷っている」という方も大歓迎です。
                <br />
                お気軽にご応募ください。
              </p>

              {status === "done" ? (
                <div className="apply-done" role="status" aria-live="polite">
                  応募を受け付けました!担当者よりご連絡いたします。
                </div>
              ) : (
                <form className="apply-form" onSubmit={onSubmit} noValidate>
                  {status === "error" && (
                    <div className="apply-error" role="alert" aria-live="assertive">
                      エラーが発生しました。LINEよりお問い合わせください。
                    </div>
                  )}

                  <div className="form-row">
                    <div className="field">
                      <label htmlFor="name">
                        お名前<span className="req">必須</span>
                      </label>
                      <input
                        id="name"
                        type="text"
                        required
                        placeholder="山田 太郎"
                        value={form.name}
                        onChange={(e) =>
                          setForm({ ...form, name: e.target.value })
                        }
                      />
                    </div>
                    <div className="field">
                      <label htmlFor="age">
                        年齢<span className="req">必須</span>
                      </label>
                      <select
                        id="age"
                        required
                        value={form.age}
                        onChange={(e) =>
                          setForm({ ...form, age: e.target.value })
                        }
                      >
                        <option value="">選択してください</option>
                        <option value="20-24">20代前半(20〜24歳)</option>
                        <option value="25-29">20代後半(25〜29歳)</option>
                        <option value="30-34">30代前半(30〜34歳)</option>
                        <option value="35+">35歳以上</option>
                      </select>
                    </div>
                  </div>

                  <div className="field">
                    <label htmlFor="tel">
                      電話番号<span className="req">必須</span>
                    </label>
                    <input
                      id="tel"
                      type="tel"
                      required
                      placeholder="090-0000-0000"
                      value={form.tel}
                      onChange={(e) =>
                        setForm({ ...form, tel: e.target.value })
                      }
                    />
                  </div>

                  <div className="field">
                    <label htmlFor="email">
                      メールアドレス<span className="req">必須</span>
                    </label>
                    <input
                      id="email"
                      type="email"
                      required
                      placeholder="example@email.com"
                      value={form.email}
                      onChange={(e) =>
                        setForm({ ...form, email: e.target.value })
                      }
                    />
                  </div>

                  <div className="field">
                    <label htmlFor="position">希望職種</label>
                    <select
                      id="position"
                      value={form.position}
                      onChange={(e) =>
                        setForm({ ...form, position: e.target.value })
                      }
                    >
                      <option value="営業職(正社員)">営業職(正社員)</option>
                    </select>
                  </div>

                  <div className="field">
                    <label htmlFor="message">メッセージ・ご質問(任意)</label>
                    <textarea
                      id="message"
                      placeholder="ご質問があればお気軽にどうぞ。"
                      value={form.message}
                      onChange={(e) =>
                        setForm({ ...form, message: e.target.value })
                      }
                    />
                  </div>

                  <button
                    type="submit"
                    className="btn-submit"
                    disabled={status === "sending"}
                  >
                    {status === "sending" ? "送信中..." : "この内容で応募する"}
                  </button>

                  <p className="privacy">
                    送信いただいた個人情報は採用目的のみに使用し、適切に管理いたします。
                  </p>
                </form>
              )}
            </div>
          </div>
        </section>
      </main>

      {/* ====== FOOTER ====== */}
      <footer className="lp-footer">
        <div className="f-logo">ESCO PROMOTION</div>
        <div className="f-logo-ja">株式会社エスコプロモーション</div>
        <div className="f-info">
          <p>
            <span>所在地</span>
            大阪府大阪市中央区南船場4丁目6番1号 南船場秋山ビル
          </p>
          <p>
            <span>電話番号</span>
            06-4963-9778
          </p>
          <p>
            <span>設立</span>
            2011年4月
          </p>
          <p>
            <span>事業内容</span>
            省エネ・創エネ設備の販売・施工・保守、電気工事、電気通信事業
          </p>
        </div>
        <div className="f-copy">
          © {new Date().getFullYear()} ESCO PROMOTION Co., Ltd. All rights
          reserved.
        </div>
      </footer>

      {/* ====== FIXED BAR ====== */}
      <div className="fixed-bar">
        <a
          href={LINE_URL}
          target="_blank"
          rel="noopener noreferrer"
          onClick={trackContact}
        >
          <LineIcon size={24} />
          <span>
            <span className="fb-main">まずはLINEで気軽に話を聞いてみる</span>
            <span className="fb-sub">選考なし・返信1分・無料で相談できます</span>
          </span>
        </a>
      </div>

      {/* Back to top */}
      <button
        type="button"
        className={`back-top${showBackTop ? " show" : ""}`}
        aria-label="ページトップへ戻る"
        onClick={() => window.scrollTo({ top: 0, behavior: "smooth" })}
      >
        <svg
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          strokeWidth="2"
          strokeLinecap="round"
          strokeLinejoin="round"
          aria-hidden="true"
        >
          <path d="M12 19V5M5 12l7-7 7 7" />
        </svg>
      </button>

      {/* Exit intent modal */}
      <div
        className={`exit-modal${exitModalOpen ? " show" : ""}`}
        role="dialog"
        aria-modal="true"
        aria-labelledby="exit-modal-title"
        aria-hidden={!exitModalOpen}
        onClick={(e) => {
          if (e.target === e.currentTarget) setExitModalOpen(false);
        }}
      >
        <div className="exit-modal-body">
          <button
            type="button"
            className="exit-modal-close"
            aria-label="閉じる"
            onClick={() => setExitModalOpen(false)}
          >
            <svg
              width="18"
              height="18"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              strokeWidth="2"
              strokeLinecap="round"
              strokeLinejoin="round"
              aria-hidden="true"
            >
              <path d="M18 6L6 18M6 6l12 12" />
            </svg>
          </button>
          <span className="exit-modal-kicker">LAST CHANCE</span>
          <h3 id="exit-modal-title">
            まだ迷っていますか?
            <br />
            <em>1分の相談</em>で、全てが解決します。
          </h3>
          <p>
            選考前提ではありません。「詳しく聞きたいだけ」でも大歓迎です。
            <br />
            LINEで匿名のままご質問いただけます。
          </p>
          <a
            className="btn-line-lg"
            href={LINE_URL}
            target="_blank"
            rel="noopener noreferrer"
            onClick={() => {
              trackContact();
              setExitModalOpen(false);
            }}
          >
            <LineIcon size={22} />
            LINEで無料相談する
          </a>
        </div>
      </div>
    </>
  );
}
