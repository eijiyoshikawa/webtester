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

const coverImages = [
  "/images/esco_LP/esco_top1.jpg",
  "/images/esco_LP/esco_top2.jpg",
  "/images/esco_LP/esco_top3.jpg",
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

const meritItems = [
  {
    num: "01",
    photo: "/images/esco_LP/merit-01.jpg",
    alt: "収入",
    title: "頑張りが、\nそのまま給料に。",
    body: "月給24万円スタート＋インセンティブ制度。賞与年2回・昇給ありで上限なく稼げます。入社1年以内に月収35万円を超えるメンバーも多数います。",
    tag: "インセンティブ制度あり",
  },
  {
    num: "02",
    photo: "/images/esco_LP/merit-02.jpg",
    alt: "研修",
    title: "未経験でも安心の\n研修・サポート。",
    body: "入社後は先輩が毎日フォロー。学歴・経歴不問で、素直に学べる方なら必ず活躍できます。飲食・販売・倉庫からの転職者が多数活躍中。",
    tag: "1〜2ヶ月の研修制度",
  },
  {
    num: "03",
    photo: "/images/esco_LP/merit-03.jpg",
    alt: "休日",
    title: "直行直帰OK・\n年間休日120日。",
    body: "完全週休2日制も選択可能。年末年始・GW・お盆休暇あり。関西エリア内の勤務なので遠方への転勤もありません。",
    tag: "年間休日120日・転勤なし",
  },
];

const voiceItems = [
  {
    photo: "/images/esco_LP/voice-01.jpg",
    name: "佐藤 健太",
    meta: "27歳 ／ 入社2年目 ／ 前職: 居酒屋店長",
    tag: "MEMBER 01",
    q1: "入社のきっかけは?",
    a1: "前職は居酒屋で月給22万円。どれだけ働いても給料が変わらず、将来に不安を感じていました。LINE相談で「未経験でも稼げる」と聞き、話だけでもと面談に行ったのがきっかけです。",
    q2: "今の仕事のやりがい",
    a2: "お客様に感謝されるのはもちろん、努力が月収に直結するので毎月の達成感がすごい。今は後輩の育成も任されています。",
    salary: "42",
  },
  {
    photo: "/images/esco_LP/voice-02.jpg",
    name: "田中 美咲",
    meta: "24歳 ／ 入社1年目 ／ 前職: アパレル販売",
    tag: "MEMBER 02",
    q1: "未経験で不安はなかった?",
    a1: "正直とても不安でした。でも研修が丁寧で、先輩も毎日フォローしてくれるので3ヶ月で独り立ちできました。女性も活躍しやすい環境です。",
    q2: "入社して変わったこと",
    a2: "収入が倍近く増えて、貯金もできるようになりました。休日もちゃんと取れるので、プライベートも充実しています。",
    salary: "38",
  },
  {
    photo: "/images/esco_LP/voice-03.jpg",
    name: "鈴木 翔",
    meta: "31歳 ／ 入社3年目 ／ 現: チームリーダー",
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

const tocItems: { n: string; label: string; en: string; href: string }[] = [
  { n: "01", label: "数字で見るエスコ", en: "NUMBERS", href: "#ch-01" },
  { n: "02", label: "他社との比較", en: "COMPARE", href: "#ch-02" },
  { n: "03", label: "代表メッセージ", en: "MESSAGE", href: "#ch-03" },
  { n: "04", label: "働く3つの理由", en: "MERIT", href: "#ch-04" },
  { n: "05", label: "先輩社員の声", en: "VOICE", href: "#ch-05" },
  { n: "06", label: "1日の流れ", en: "A DAY", href: "#ch-06" },
  { n: "07", label: "福利厚生", en: "BENEFITS", href: "#ch-07" },
  { n: "08", label: "キャリアパス", en: "CAREER", href: "#ch-08" },
  { n: "09", label: "求める人物像", en: "IDEAL", href: "#ch-09" },
  { n: "10", label: "募集要項", en: "RECRUITMENT", href: "#ch-10" },
  { n: "11", label: "選考フロー", en: "FLOW", href: "#ch-11" },
  { n: "12", label: "よくある質問", en: "FAQ", href: "#ch-12" },
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
  const [status, setStatus] = useState<"idle" | "sending" | "done" | "error">("idle");

  // Cover slider
  useEffect(() => {
    const id = setInterval(() => {
      setSlideIndex((p) => (p + 1) % coverImages.length);
    }, 4500);
    return () => clearInterval(id);
  }, []);

  // Scroll handling
  useEffect(() => {
    let raf = 0;
    const onScroll = () => {
      if (raf) return;
      raf = requestAnimationFrame(() => {
        const y = window.scrollY;
        const h = document.documentElement.scrollHeight - window.innerHeight;
        setScrolled(y > 40);
        setShowBackTop(y > 700);
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

  // Converge IO
  useEffect(() => {
    if (typeof window === "undefined") return;
    const els = document.querySelectorAll<HTMLElement>("[data-c]");
    if (!els.length) return;
    if (!("IntersectionObserver" in window)) {
      els.forEach((el) => el.classList.add("in"));
      return;
    }
    const io = new IntersectionObserver(
      (entries) => {
        entries.forEach((e) => {
          if (e.isIntersecting) {
            e.target.classList.add("in");
            io.unobserve(e.target);
          }
        });
      },
      { threshold: 0.12, rootMargin: "0px 0px -40px 0px" }
    );
    els.forEach((el) => io.observe(el));
    return () => io.disconnect();
  }, []);

  // CountUp IO
  useEffect(() => {
    if (typeof window === "undefined") return;
    if (!("IntersectionObserver" in window)) return;
    const targets = document.querySelectorAll<HTMLElement>("[data-countup]");
    const io = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (!entry.isIntersecting) return;
          const el = entry.target as HTMLElement;
          const end = Number(el.dataset.countup || "0");
          const duration = 1400;
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

  // Exit intent (desktop)
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
    const t = setTimeout(() => document.addEventListener("mouseleave", onLeave), 15000);
    return () => {
      clearTimeout(t);
      document.removeEventListener("mouseleave", onLeave);
    };
  }, []);

  // ESC to close overlays
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
      try { window.ttq.track("Contact"); } catch { /* noop */ }
    }
  };
  const trackSubmit = () => {
    if (typeof window !== "undefined" && window.ttq) {
      try { window.ttq.track("SubmitForm"); } catch { /* noop */ }
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


  return (
    <>
      {/* Background layers */}
      <div className="aurora" aria-hidden="true" />
      <div className="grain" aria-hidden="true" />

      {/* Scroll progress */}
      <div className="progress" style={{ width: `${progress}%` }} aria-hidden="true" />

      {/* ====== NAV ====== */}
      <header className={`nav${scrolled ? " scrolled" : ""}`}>
        <div className="nav__inner">
          <a href="#top" className="nav__brand" aria-label="ESCO PROMOTION">
            <img src="/images/esco_LP/esco_logo.png" alt="ESCO PROMOTION" width="120" height="26" />
            <span className="nav__brand-label">RECRUIT / 2026</span>
          </a>
          <div className="nav__actions">
            <a className="nav__link" href="#apply">ENTRY</a>
            <a
              className="nav__cta"
              href={LINE_URL}
              target="_blank"
              rel="noopener noreferrer"
              onClick={trackContact}
            >
              <LineIcon size={14} />
              LINEで相談
            </a>
            <button
              type="button"
              className={`menu-btn${drawerOpen ? " open" : ""}`}
              aria-label="メニューを開く"
              aria-expanded={drawerOpen}
              aria-controls="sp-drawer"
              onClick={() => setDrawerOpen((v) => !v)}
            >
              <span />
            </button>
          </div>
        </div>
      </header>

      {/* SP drawer */}
      <div
        id="sp-drawer"
        className={`drawer${drawerOpen ? " open" : ""}`}
        role="dialog"
        aria-label="ナビゲーションメニュー"
        aria-hidden={!drawerOpen}
      >
        <nav className="drawer__nav">
          {tocItems.map((item) => (
            <a
              key={item.href}
              href={item.href}
              onClick={() => setDrawerOpen(false)}
            >
              <span>{item.label}</span>
              <span className="n">{item.n} / {item.en}</span>
            </a>
          ))}
          <a
            className="drawer__cta"
            href={LINE_URL}
            target="_blank"
            rel="noopener noreferrer"
            onClick={() => { trackContact(); setDrawerOpen(false); }}
          >
            <LineIcon size={18} />
            LINEで相談する
          </a>
        </nav>
      </div>

      <main id="top">
        {/* ====== OPENING ====== */}
        <section className="opening" aria-label="Hero">
          <div className="opening__top">
            <div className="opening__edition">
              ESCO PROMOTION <b>— Recruit Edition 2026</b>
            </div>
            <div className="opening__year" aria-hidden="true">26</div>
          </div>
          <h1 className="opening__h" data-c="up">
            あなたの<em>「もっと稼ぎたい」</em>を、
            <br />
            現実にする会社。
          </h1>
          <div className="opening__meta" data-c="up" data-d="2">
            <div>
              <b>Position</b>
              営業スタッフ（省エネ機器のイベント案内）
            </div>
            <div>
              <b>Location</b>
              大阪市中央区／関西一円
            </div>
            <div>
              <b>Type</b>
              正社員・中途採用
            </div>
            <div>
              <b>Salary</b>
              月収35万円〜（入社1年目実績）
            </div>
          </div>
          <div className="opening__scroll" aria-hidden="true">
            <span className="line" />
            SCROLL
          </div>
        </section>

        {/* ====== COVER (crossfade image) ====== */}
        <section className="cover" aria-label="カバー">
          <div className="cover__slides">
            {coverImages.map((src, i) => (
              <div
                key={src}
                className={`cover__slide${i === slideIndex ? " active" : ""}`}
              >
                <img
                  src={src}
                  alt="エスコプロモーション チーム"
                  loading={i === 0 ? "eager" : "lazy"}
                  decoding="async"
                />
              </div>
            ))}
          </div>
          <div className="cover__vignette" />
          <div className="cover__caption">
            <b>FIG. 01</b> / TEAM AT WORK — Osaka Office
          </div>
          <div className="cover__frame">
            {String(slideIndex + 1).padStart(2, "0")} / {String(coverImages.length).padStart(2, "0")}
          </div>
        </section>

        {/* ====== INDEX (TOC) ====== */}
        <section className="index-sec" aria-label="目次">
          <div className="index-sec__wrap">
            <div className="index-sec__head" data-c="left">
              <h2>— Contents</h2>
              <p>12 Chapters / 2026 Edition</p>
            </div>
            <div className="toc" data-c="right">
              {tocItems.map((item) => (
                <a key={item.href} href={item.href}>
                  <span className="num">{item.n}</span>
                  <span>{item.label}</span>
                  <span className="en">{item.en}</span>
                </a>
              ))}
            </div>
          </div>
        </section>

        {/* ====== CH 01 — NUMBERS ====== */}
        <section className="chapter" id="ch-01" aria-label="数字で見るエスコ">
          <div className="chapter__head">
            <div className="chapter__meta" data-c="left">
              <div className="chapter__no">01</div>
              <div className="chapter__label">Numbers</div>
            </div>
            <div data-c="right">
              <h2 className="chapter__h">
                数字で見る、<em>エスコの働き方</em>。
              </h2>
              <p className="chapter__lead">
                月収・休日・初期費用の3指標で、「頑張った分だけ稼げる」を可視化します。
              </p>
            </div>
          </div>
          <div className="nums">
            <div className="num" data-c="up">
              <div className="num__label"><b>01</b> Monthly Income</div>
              <div className="num__v">
                <span data-countup="35">0</span>
                <span className="u">万円〜</span>
              </div>
              <div className="num__name">入社1年目で目指せる月収</div>
              <p className="num__desc">月給24万円＋インセンティブ。頑張り次第で青天井の収入。</p>
            </div>
            <div className="num" data-c="up" data-d="1">
              <div className="num__label"><b>02</b> Annual Holidays</div>
              <div className="num__v">
                <span data-countup="120">0</span>
                <span className="u">日</span>
              </div>
              <div className="num__name">年間休日</div>
              <p className="num__desc">完全週休2日制も選択可能。年末年始・GW・お盆あり。</p>
            </div>
            <div className="num" data-c="up" data-d="2">
              <div className="num__label"><b>03</b> Starting Cost</div>
              <div className="num__v">
                <span data-countup="0">0</span>
                <span className="u">円</span>
              </div>
              <div className="num__name">必要な初期費用</div>
              <p className="num__desc">未経験・学歴不問。研修費・道具代は全額会社負担。</p>
            </div>
          </div>
        </section>

        {/* ====== CH 02 — COMPARE ====== */}
        <section className="chapter" id="ch-02" aria-label="他社との比較">
          <div className="chapter__head">
            <div className="chapter__meta" data-c="left">
              <div className="chapter__no">02</div>
              <div className="chapter__label">Compare</div>
            </div>
            <div data-c="right">
              <h2 className="chapter__h">
                同じ時間を使うなら、<em>稼げる仕事</em>を。
              </h2>
              <p className="chapter__lead">
                飲食・販売・倉庫の一般的な給与モデルと、エスコプロモーションの実績を並べました。
              </p>
            </div>
          </div>
          <div className="vs">
            <div className="vs__col vs__col--other" data-c="left">
              <div className="vs__tag">OTHER — 他の仕事（例）</div>
              <div className="vs__sub">飲食・販売・倉庫スタッフ</div>
              <div className="vs__rows">
                <div className="vs__row"><span className="vs__k">Monthly</span><span className="vs__v">18〜21万円</span></div>
                <div className="vs__row"><span className="vs__k">Cap</span><span className="vs__v" style={{ fontSize: 18 }}>ほぼ固定</span></div>
                <div className="vs__row"><span className="vs__k">Incentive</span><span className="vs__v" style={{ fontSize: 18 }}>なし</span></div>
                <div className="vs__row"><span className="vs__k">5yrs Later</span><span className="vs__v" style={{ fontSize: 18 }}>ほぼ変わらず</span></div>
              </div>
            </div>
            <div className="vs__divider" data-c="scale" data-d="1">VS</div>
            <div className="vs__col vs__col--ours" data-c="right" data-d="2">
              <div className="vs__tag">OURS — 当社</div>
              <div className="vs__sub">エスコプロモーション・営業職</div>
              <div className="vs__rows">
                <div className="vs__row"><span className="vs__k">Monthly</span><span className="vs__v">35万円〜</span></div>
                <div className="vs__row"><span className="vs__k">Cap</span><span className="vs__v" style={{ fontSize: 22 }}>青天井</span></div>
                <div className="vs__row"><span className="vs__k">Incentive</span><span className="vs__v" style={{ fontSize: 22 }}>あり</span></div>
                <div className="vs__row"><span className="vs__k">5yrs Later</span><span className="vs__v" style={{ fontSize: 22 }}>実力次第</span></div>
              </div>
            </div>
          </div>
          <div className="vs__note">
            ※他社の月収は時給×8h×22日の目安。エスコの数値は入社1年目社員の平均実績です。
          </div>
          <div className="vs__cta" data-c="up">
            <div className="vs__line">
              同じ時間を使うなら、<em>稼げる仕事</em>を選ぶべきではないですか?
            </div>
            <a
              className="btn-line"
              href={LINE_URL}
              target="_blank"
              rel="noopener noreferrer"
              onClick={trackContact}
            >
              <LineIcon size={20} />
              LINEで詳しく聞いてみる
            </a>
          </div>
        </section>

        {/* ====== CH 03 — MESSAGE / CEO ====== */}
        <section className="chapter" id="ch-03" aria-label="代表メッセージ">
          <div className="chapter__head">
            <div className="chapter__meta" data-c="left">
              <div className="chapter__no">03</div>
              <div className="chapter__label">Message</div>
            </div>
            <div data-c="right">
              <h2 className="chapter__h">
                代表メッセージ。
              </h2>
              <p className="chapter__lead">
                社員一人ひとりの「人生の豊かさ」が、会社の未来をつくる。
              </p>
            </div>
          </div>
          <div className="msg">
            <div className="msg__portrait" data-c="left">
              <img
                src="/images/esco_LP/ceo.jpg"
                alt="代表取締役 山田 太郎"
                loading="lazy"
                decoding="async"
              />
            </div>
            <div className="msg__body" data-c="right">
              <div className="msg__quote">
                頑張る人が、ちゃんと
                <br />
                <em>稼げる会社</em>を
                <br />
                つくりたかった。
              </div>
              <p>
                私自身、若い頃は「頑張っても報われない環境」で悩んでいました。だからこそエスコプロモーションでは、努力した人がきちんと収入を得られる仕組みを整えています。
              </p>
              <p>
                学歴や経歴ではなく、素直さと向上心。それさえあれば、どんなバックグラウンドの方でも活躍できます。あなたの可能性を、私たちと一緒に広げていきませんか。
              </p>
              <div className="msg__sign">
                <span>CEO / 代表取締役</span>
                <b>山田 太郎</b>
              </div>
            </div>
          </div>
        </section>

        {/* ====== CH 04 — MERIT (bento) ====== */}
        <section className="chapter" id="ch-04" aria-label="働く3つの理由">
          <div className="chapter__head">
            <div className="chapter__meta" data-c="left">
              <div className="chapter__no">04</div>
              <div className="chapter__label">Merit</div>
            </div>
            <div data-c="right">
              <h2 className="chapter__h">
                エスコで働く、<em>3つの理由</em>。
              </h2>
              <p className="chapter__lead">
                収入・成長・ライフスタイル。どれか1つではなく、すべてを両立できる環境です。
              </p>
            </div>
          </div>
          <div className="bento">
            {/* Large tile (Merit 01) */}
            <article className="tile tile--large" data-c="up">
              <div className="tile__img">
                <img
                  src={meritItems[0].photo}
                  alt={meritItems[0].alt}
                  loading="lazy"
                  decoding="async"
                />
              </div>
              <div className="tile__inner">
                <div className="tile__num">{`MERIT / ${meritItems[0].num}`}</div>
                <h3 className="tile__h">{meritItems[0].title}</h3>
                <p className="tile__p">{meritItems[0].body}</p>
                <span className="tile__tag">{meritItems[0].tag}</span>
              </div>
            </article>

            {/* Small tiles */}
            <article className="tile" data-c="up" data-d="1">
              <div className="tile__num">{`MERIT / ${meritItems[1].num}`}</div>
              <h3 className="tile__h">{meritItems[1].title}</h3>
              <p className="tile__p">{meritItems[1].body}</p>
              <span className="tile__tag">{meritItems[1].tag}</span>
            </article>
            <article className="tile" data-c="up" data-d="2">
              <div className="tile__num">{`MERIT / ${meritItems[2].num}`}</div>
              <h3 className="tile__h">{meritItems[2].title}</h3>
              <p className="tile__p">{meritItems[2].body}</p>
              <span className="tile__tag">{meritItems[2].tag}</span>
            </article>
          </div>
        </section>

        {/* ====== CH 05 — VOICE (horizontal scroller) ====== */}
        <section className="chapter" id="ch-05" aria-label="先輩社員の声">
          <div className="chapter__head">
            <div className="chapter__meta" data-c="left">
              <div className="chapter__no">05</div>
              <div className="chapter__label">Voice</div>
            </div>
            <div data-c="right">
              <h2 className="chapter__h">
                異業種から転職した、<em>3人のリアル</em>。
              </h2>
              <p className="chapter__lead">
                飲食・販売・倉庫。前職はバラバラ。共通するのは「素直さ」だけでした。
              </p>
            </div>
          </div>
          <div className="voice-strip">
            <div className="voice-scroller">
              {voiceItems.map((v) => (
                <article key={v.name} className="voice-card" data-c="up">
                  <div className="voice-card__img">
                    <span className="voice-card__badge">{v.tag}</span>
                    <img src={v.photo} alt={v.name} loading="lazy" decoding="async" />
                  </div>
                  <div className="voice-card__body">
                    <div className="voice-card__name">{v.name}</div>
                    <div className="voice-card__meta">{v.meta}</div>
                    <div className="voice-card__qa">
                      <div className="voice-card__q">Q. {v.q1}</div>
                      <p className="voice-card__a">{v.a1}</p>
                      <div className="voice-card__q">Q. {v.q2}</div>
                      <p className="voice-card__a">{v.a2}</p>
                    </div>
                    <div className="voice-card__salary">
                      <span className="k">Monthly Income</span>
                      <span className="v">{v.salary}<span className="u">万円</span></span>
                    </div>
                  </div>
                </article>
              ))}
            </div>
          </div>
          <div className="voice-hint">横スクロールで全メンバーを見る</div>
        </section>

        {/* ====== CH 06 — A DAY (sticky image + timeline) ====== */}
        <section className="chapter" id="ch-06" aria-label="1日の流れ">
          <div className="chapter__head">
            <div className="chapter__meta" data-c="left">
              <div className="chapter__no">06</div>
              <div className="chapter__label">A Day</div>
            </div>
            <div data-c="right">
              <h2 className="chapter__h">
                営業スタッフの、<em>1日の流れ</em>。
              </h2>
              <p className="chapter__lead">
                9:30に出社して19:00に退勤。残業はほぼゼロ。具体的な働き方を時系列で。
              </p>
            </div>
          </div>
          <div className="aday">
            <div className="aday__media" data-c="left">
              <img
                src="/images/esco_LP/message.jpg"
                alt="1日の流れ"
                loading="lazy"
                decoding="async"
              />
            </div>
            <div className="aday__list" data-c="right">
              {aDayItems.map((item) => (
                <div key={item.time} className="aday__item">
                  <div className="aday__t">{item.time}</div>
                  <div className="aday__c">
                    <h3>{item.title}</h3>
                    <p>{item.desc}</p>
                  </div>
                </div>
              ))}
            </div>
          </div>
        </section>

        {/* ====== CH 07 — BENEFITS ====== */}
        <section className="chapter" id="ch-07" aria-label="福利厚生">
          <div className="chapter__head">
            <div className="chapter__meta" data-c="left">
              <div className="chapter__no">07</div>
              <div className="chapter__label">Benefits</div>
            </div>
            <div data-c="right">
              <h2 className="chapter__h">
                安心して働ける、<em>福利厚生</em>。
              </h2>
              <p className="chapter__lead">
                社会保険から社員旅行まで、長く働ける仕組みを揃えています。
              </p>
            </div>
          </div>
          <div className="benefits">
            {benefits.map((b, i) => (
              <div
                key={b.title}
                className="benefit"
                data-c="up"
                data-d={String(Math.min(i + 1, 6))}
              >
                <div className="benefit__ic" aria-hidden="true">
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
        </section>

        {/* ====== CH 08 — CAREER ====== */}
        <section className="chapter" id="ch-08" aria-label="キャリアパス">
          <div className="chapter__head">
            <div className="chapter__meta" data-c="left">
              <div className="chapter__no">08</div>
              <div className="chapter__label">Career Path</div>
            </div>
            <div data-c="right">
              <h2 className="chapter__h">
                5年後の、<em>自分の姿</em>。
              </h2>
              <p className="chapter__lead">
                入社0年目から5年以上まで、役職と月収の推移を公開しています。
              </p>
            </div>
          </div>
          <div className="career">
            <div className="career__track">
              {careerSteps.map((c, i) => (
                <div
                  key={c.year}
                  className={`career-step${c.highlight ? " career-step--hl" : ""}`}
                  data-c="up"
                  data-d={String(i + 1)}
                >
                  <div className="career-step__yr">{c.year}</div>
                  <div className="career-step__role">{c.role}</div>
                  <div className="career-step__v">
                    <span data-countup={c.salary}>0</span>
                    <span className="u">万円〜</span>
                  </div>
                  <p className="career-step__desc">{c.desc}</p>
                </div>
              ))}
            </div>
          </div>
        </section>

        {/* ====== CH 09 — IDEAL ====== */}
        <section className="chapter" id="ch-09" aria-label="求める人物像">
          <div className="chapter__head">
            <div className="chapter__meta" data-c="left">
              <div className="chapter__no">09</div>
              <div className="chapter__label">Ideal</div>
            </div>
            <div data-c="right">
              <h2 className="chapter__h">
                こんな方を、<em>求めています</em>。
              </h2>
              <p className="chapter__lead">
                経歴やスキルより、素直さ・向上心・人とのコミュニケーション。
              </p>
            </div>
          </div>
          <div className="ideal">
            <div className="ideal__grid">
              {idealPoints.map((p, i) => (
                <div
                  key={i}
                  className="ideal-item"
                  data-c="up"
                  data-d={String(Math.min(i + 1, 6))}
                >
                  <div className="ideal-item__n">{String(i + 1).padStart(2, "0")}</div>
                  <div className="ideal-item__t">{p}</div>
                </div>
              ))}
            </div>
          </div>
        </section>

        {/* ====== CH 10 — RECRUITMENT ====== */}
        <section className="chapter" id="ch-10" aria-label="募集要項">
          <div className="chapter__head">
            <div className="chapter__meta" data-c="left">
              <div className="chapter__no">10</div>
              <div className="chapter__label">Recruitment</div>
            </div>
            <div data-c="right">
              <h2 className="chapter__h">募集要項。</h2>
              <p className="chapter__lead">
                職種 / 仕事内容 / 応募資格 / 勤務地 / 勤務時間 / 休日 / 給与 / 雇用形態
              </p>
            </div>
          </div>
          <div className="spec" data-c="up">
            <table className="spec__table">
              <tbody>
                <tr><th scope="row">Position / 職種</th><td>営業スタッフ(省エネ機器のイベント案内・アポイント獲得)</td></tr>
                <tr><th scope="row">Work / 仕事内容</th><td>省エネ機器のイベントでの案内・アポイント獲得。まずは運営・接客からスタート。</td></tr>
                <tr><th scope="row">Requirements / 応募資格</th><td>未経験OK(研修あり)／学歴・経歴不問／営業・接客経験があれば優遇<br />素直で前向きに学べる方・コミュニケーションが好きな方歓迎</td></tr>
                <tr><th scope="row">Location / 勤務地</th><td>本社(大阪市中央区)／関西一円のイベント会場　直行直帰OK</td></tr>
                <tr><th scope="row">Hours / 勤務時間</th><td>10:00〜19:00(休憩1時間)</td></tr>
                <tr><th scope="row">Holidays / 休日</th><td>完全週休2日制 or 週休2日制(選択可)<br />年末年始・GW・お盆休暇あり／年間休日120日</td></tr>
                <tr><th scope="row">Salary / 給与</th><td>月給24万円〜＋インセンティブ(経験・能力考慮)　賞与年2回／昇給あり</td></tr>
                <tr><th scope="row">Type / 雇用形態</th><td>正社員</td></tr>
              </tbody>
            </table>
          </div>
        </section>

        {/* ====== CH 11 — FLOW ====== */}
        <section className="chapter" id="ch-11" aria-label="選考フロー">
          <div className="chapter__head">
            <div className="chapter__meta" data-c="left">
              <div className="chapter__no">11</div>
              <div className="chapter__label">Flow</div>
            </div>
            <div data-c="right">
              <h2 className="chapter__h">
                応募から内定まで、<em>最短1週間</em>。
              </h2>
              <p className="chapter__lead">
                履歴書不要。まずはLINEで気軽に話すところから始まります。
              </p>
            </div>
          </div>
          <div className="flow">
            {flowSteps.map((s, i) => (
              <div
                key={s.num}
                className="flow-step"
                data-c="up"
                data-d={String(i + 1)}
              >
                <span className="flow-step__n">{s.num}</span>
                <div className="flow-step__h">{s.title}</div>
                <span className="flow-step__d">所要: {s.duration}</span>
                <p className="flow-step__p">{s.desc}</p>
              </div>
            ))}
          </div>
        </section>

        {/* ====== CH 12 — FAQ ====== */}
        <section className="chapter" id="ch-12" aria-label="よくある質問">
          <div className="chapter__head">
            <div className="chapter__meta" data-c="left">
              <div className="chapter__no">12</div>
              <div className="chapter__label">FAQ</div>
            </div>
            <div data-c="right">
              <h2 className="chapter__h">
                よくある<em>質問</em>。
              </h2>
              <p className="chapter__lead">
                検討中の方からよくいただく質問をまとめました。
              </p>
            </div>
          </div>
          <div className="faq" data-c="up">
            {faqItems.map((item, i) => {
              const open = openFaq === i;
              return (
                <div key={i} className={`faq-item${open ? " open" : ""}`}>
                  <button
                    type="button"
                    className="faq-item__q"
                    aria-expanded={open}
                    aria-controls={`faq-a-${i}`}
                    onClick={() => setOpenFaq(open ? null : i)}
                  >
                    <span className="faq-item__mark">Q.{String(i + 1).padStart(2, "0")}</span>
                    <span className="faq-item__txt">{item.q}</span>
                    <span className="faq-item__plus" aria-hidden="true" />
                  </button>
                  <div id={`faq-a-${i}`} className="faq-item__a" role="region">
                    <div className="faq-item__a-inner">{item.a}</div>
                  </div>
                </div>
              );
            })}
          </div>
        </section>

        {/* ====== BIG CTA BLOCK ====== */}
        <section className="cta-block" aria-label="CTA">
          <div className="cta-block__wrap">
            <div className="cta-block__kicker" data-c="up">— CONTACT</div>
            <h2 className="cta-block__h" data-c="up" data-d="1">
              まずは気軽に、
              <br />
              話を聞いてみませんか?
            </h2>
            <p className="cta-block__p" data-c="up" data-d="2">
              「転職するか迷っている」「詳しく聞きたいだけ」でも大歓迎。
              選考とは関係なく、どんな質問にも答えます。
            </p>
            <div data-c="up" data-d="3">
              <a
                className="btn-line"
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

        {/* ====== APPLY FORM ====== */}
        <section className="chapter" id="apply" aria-label="応募フォーム">
          <div className="chapter__head">
            <div className="chapter__meta" data-c="left">
              <div className="chapter__no">13</div>
              <div className="chapter__label">Apply</div>
            </div>
            <div data-c="right">
              <h2 className="chapter__h">応募フォーム。</h2>
              <p className="chapter__lead">
                「詳しく聞いてみたい」「迷っている」という方も大歓迎です。お気軽にどうぞ。
              </p>
            </div>
          </div>
          <div className="apply-wrap" data-c="up">
            <div className="apply-box">
              {status === "done" ? (
                <div className="apply-done" role="status" aria-live="polite">
                  応募を受け付けました! 担当者よりご連絡いたします。
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
                      <label htmlFor="name">Name <span className="req">必須</span></label>
                      <input
                        id="name"
                        type="text"
                        required
                        placeholder="山田 太郎"
                        value={form.name}
                        onChange={(e) => setForm({ ...form, name: e.target.value })}
                      />
                    </div>
                    <div className="field">
                      <label htmlFor="age">Age <span className="req">必須</span></label>
                      <select
                        id="age"
                        required
                        value={form.age}
                        onChange={(e) => setForm({ ...form, age: e.target.value })}
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
                    <label htmlFor="tel">Phone <span className="req">必須</span></label>
                    <input
                      id="tel"
                      type="tel"
                      required
                      placeholder="090-0000-0000"
                      value={form.tel}
                      onChange={(e) => setForm({ ...form, tel: e.target.value })}
                    />
                  </div>
                  <div className="field">
                    <label htmlFor="email">Email <span className="req">必須</span></label>
                    <input
                      id="email"
                      type="email"
                      required
                      placeholder="example@email.com"
                      value={form.email}
                      onChange={(e) => setForm({ ...form, email: e.target.value })}
                    />
                  </div>
                  <div className="field">
                    <label htmlFor="position">Position</label>
                    <select
                      id="position"
                      value={form.position}
                      onChange={(e) => setForm({ ...form, position: e.target.value })}
                    >
                      <option value="営業職(正社員)">営業職(正社員)</option>
                    </select>
                  </div>
                  <div className="field">
                    <label htmlFor="message">Message</label>
                    <textarea
                      id="message"
                      placeholder="ご質問があればお気軽にどうぞ。"
                      value={form.message}
                      onChange={(e) => setForm({ ...form, message: e.target.value })}
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
        <div className="lp-footer__wrap">
          <div className="lp-footer__top">
            <div>
              <div className="lp-footer__brand">ESCO PROMOTION</div>
              <div className="lp-footer__brand-ja">株式会社エスコプロモーション</div>
            </div>
            <dl className="lp-footer__info">
              <div>
                <dt>Address</dt>
                <dd>大阪府大阪市中央区南船場4丁目6番1号 南船場秋山ビル</dd>
              </div>
              <div>
                <dt>Phone</dt>
                <dd>06-4963-9778</dd>
              </div>
              <div>
                <dt>Founded</dt>
                <dd>2011年4月</dd>
              </div>
              <div>
                <dt>Business</dt>
                <dd>省エネ・創エネ設備の販売・施工・保守、電気工事、電気通信事業</dd>
              </div>
            </dl>
          </div>
          <div className="lp-footer__bottom">
            <span>© {new Date().getFullYear()} ESCO PROMOTION Co., Ltd.</span>
            <span>Recruit 2026 / All rights reserved.</span>
          </div>
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
          <LineIcon size={20} />
          <span>
            <span className="fb-main">LINEで気軽に話を聞く</span>
            <span className="fb-sub"> ／ 選考なし・返信1分・無料</span>
          </span>
        </a>
      </div>

      {/* ====== BACK TO TOP ====== */}
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

      {/* ====== EXIT INTENT MODAL ====== */}
      <div
        className={`exit-modal${exitModalOpen ? " show" : ""}`}
        role="dialog"
        aria-modal="true"
        aria-labelledby="exit-h"
        aria-hidden={!exitModalOpen}
        onClick={(e) => {
          if (e.target === e.currentTarget) setExitModalOpen(false);
        }}
      >
        <div className="exit-modal__body">
          <button
            type="button"
            className="exit-modal__close"
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
          <span className="exit-modal__kicker">LAST CHANCE</span>
          <h3 id="exit-h" className="exit-modal__h">
            まだ迷っていますか?
            <br />
            <em>1分の相談</em>で、全てが解決します。
          </h3>
          <p className="exit-modal__p">
            選考前提ではありません。「詳しく聞きたいだけ」でも大歓迎です。
            LINEで匿名のままご質問いただけます。
          </p>
          <a
            className="btn-line"
            href={LINE_URL}
            target="_blank"
            rel="noopener noreferrer"
            onClick={() => { trackContact(); setExitModalOpen(false); }}
          >
            <LineIcon size={22} />
            LINEで無料相談する
          </a>
        </div>
      </div>
    </>
  );
}
