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

export default function Home() {
  const [slideIndex, setSlideIndex] = useState(0);
  const [scrolled, setScrolled] = useState(false);
  const [openFaq, setOpenFaq] = useState<number | null>(null);
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

  useEffect(() => {
    const id = setInterval(() => {
      setSlideIndex((prev) => (prev + 1) % heroPcImages.length);
    }, 3000);
    return () => clearInterval(id);
  }, []);

  useEffect(() => {
    const onScroll = () => setScrolled(window.scrollY > 40);
    window.addEventListener("scroll", onScroll);
    return () => window.removeEventListener("scroll", onScroll);
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

  return (
    <>
      {/* ====== HEADER ====== */}
      <header className={`lp-header${scrolled ? " scrolled" : ""}`}>
        <div className="header">
          <a href="#" className="header-logo" aria-label="ESCO PROMOTION">
            <img
              src="/images/esco_LP/esco_logo.svg"
              alt="ESCO PROMOTION 株式会社エスコプロモーション"
            />
          </a>
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
      </header>

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

        {/* ====== NUMBERS ====== */}
        <section className="sec bg-gray">
          <div className="inner">
            <div style={{ textAlign: "center" }} data-aos="fade-up">
              <span className="sec-label">NUMBERS</span>
            </div>
            <div className="numbers-grid" data-aos="fade-up">
              <div className="num-item">
                <span className="num">
                  35<span>万円〜</span>
                </span>
                <span className="num-label">入社1年目で目指せる月収</span>
                <p className="num-desc">
                  月給24万円＋インセンティブ。
                  <br />
                  頑張り次第で青天井。
                </p>
              </div>
              <div className="num-item">
                <span className="num">
                  120<span>日</span>
                </span>
                <span className="num-label">年間休日</span>
                <p className="num-desc">
                  完全週休2日制も選択可。
                  <br />
                  年末年始・GW・お盆あり。
                </p>
              </div>
              <div className="num-item">
                <span className="num">
                  0<span>円</span>
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

            <div className="compare-wrap" data-aos="fade-up">
              <div className="compare-side other">
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

              <div className="compare-vs">VS</div>

              <div className="compare-side espro">
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
        <section className="sec bg-gray" style={{ padding: 0 }}>
          <div className="merit-title-block">
            <span className="sec-label">MERIT</span>
            <h2>
              エスコプロモーションで
              <br />
              働く3つの理由
            </h2>
          </div>

          <div className="merit-list">
            {meritItems.map((m) => (
              <article
                key={m.num}
                className="merit-item"
                data-aos="fade-up"
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
        <section className="sec">
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

        {/* ====== FAQ ====== */}
        <section className="sec bg-gray">
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
    </>
  );
}
