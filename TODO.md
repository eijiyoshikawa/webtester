# TODO

## 📌 重要情報のメモ

- **管理システムログインURL**: `https://system.suiseistudio.com/admin`
- **予約システムURL（顧客向け）**: `https://system.suiseistudio.com/`
- **作業ブランチ**: `claude/eloquent-turing-2BWTR`

## ✏️ 後で差し替えが必要

- [ ] **著者写真ファイルのアップロード**: `/assets/img/author-eiji-yoshikawa.jpg` （512×512 正方形推奨）
  - 現在は `<img>` の `onerror` でフォールバック → グラデーション背景に「EY」テキスト表示
  - 画像ファイルをそのパスに追加すれば、自動的に全記事と著者ページで顔写真表示に切り替わる
  - 推奨フォーマット: JPG または WebP、200KB以下、顔が中心の正方形クロップ

## 📰 ブログ運用について

### 公開済み（10記事 / 2026-05-23 公開）
- A: 枚方ダンスレンタルスタジオの選び方／京阪沿線ダンス練習場所まとめ／枚方 学割スタジオ
- B: 初心者30日プラン／TikTok動画撮影／ヒールダンス注意点
- C: 文化祭ダンス逆算スケジュール／春からスタジオデビュー
- D: カラオケvsスタジオ／大阪料金相場ガイド

### スケジュール公開（毎月15日 09:00 JST に GitHub Action が自動公開）
| 公開日 | カテゴリ | 記事 |
|---|---|---|
| 2026-06-15 | A | 枚方市駅周辺のダンス練習スポット完全ガイド |
| 2026-06-15 | C | 夏休みダンス強化 集中練習プラン |
| 2026-07-15 | B | 鏡稽古完全ガイド |
| 2026-07-15 | D | 個人練習用 vs グループ練習用 スタジオ |
| 2026-08-15 | A | 北河内エリアのダンススタジオまとめ |
| 2026-08-15 | C | ハロウィン撮影アイデア |

### 追加で書く必要のあるスケジュール記事（残り18本 = 9か月分）
`scripts/articles_data.py` の `SCHEDULED` リストに追加して `python3 scripts/generate_blog.py` で再生成。

- 2026-09-15: B（振り入れ効率化）／D（月額vs時間貸し）
- 2026-10-15: A（樟葉駅エリア練習環境）／C（年末発表会1か月プラン）
- 2026-11-15: B（初心者の壁の越え方）／D（スタジオ選びチェック10項目）
- 2026-12-15: A（枚方ダンスサークル活動場所）／C（新年スタートのススメ）
- 2027-01-15: B（SNS映え動画の撮り方）／D（プライベートvsグループレッスン）
- 2027-02-15: A（牧野・御殿山エリア）／C（卒業シーズン動画撮影）
- 2027-03-15: B（ジャンル別練習ポイント）／D（設備チェックポイント音響・床・鏡）
- 2027-04-15: A（ダンスバトル・コンテスト情報）／C（GW集中合宿）
- 2027-05-15: B（自宅vsスタジオ組み合わせ）／D（インストラクター視点の選び方）

### GitHub Action
- ワークフロー: `.github/workflows/publish-blog.yml`
- スクリプト: `.github/scripts/publish_articles.py`
- 手動実行も可（GitHub Actions タブから "Publish scheduled blog articles" → Run workflow、対象日指定）

---

## 🔴 公開直後にやること（必須）

- [ ] **Google Search Console** にサイト登録 + `https://www.suiseistudio.com/sitemap.xml` を送信
  - 旧URL（`/works/`, `/about/`, `/contact/` 等）が既にインデックスされていた場合は「削除ツール」で除去申請
- [ ] **リッチリザルトテスト**（https://search.google.com/test/rich-results）で以下を検証
  - LocalBusiness / DanceSchool
  - FAQPage
  - NewsArticle（記事3つ）
  - BreadcrumbList
- [ ] **OGP確認**（https://www.opengraph.xyz/）でSNSプレビューを確認
- [ ] 英語版 `/en/` とスペイン語版 `/es/` のインデックス状況確認

---

## 🟡 中期で取り組むSEO施策

### 画像・ビジュアル
- [ ] **OG画像専用ファイル**（1200×630）を作成し `assets/img/ogp.jpg` として配置
  - 全ページの `og:image` を専用画像に差し替え
- [ ] 画像のWebP化（コアウェブバイタル改善）
  - `assets/img/` 配下のJPG/PNGをWebPに変換
- [ ] ページ別の専用OG画像（記事ごとに個別画像を作るとSNSシェア時のCTR向上）

### ローカルSEO
- [ ] **Googleビジネスプロフィール**の整備
  - 住所・営業時間・写真・口コミ獲得
  - 「枚方 ダンススタジオ」のマップ検索流入はこれが一番効く

### UI/UX
- [ ] フッターに小さな**言語切替リンク**（English / Español / 日本語）を追加
  - 現状 hreflang のみで検索エンジン経由でしか言語切替できない
- [ ] ページ表示速度のチューニング（Lighthouse 計測後）
  - CSS/JS の minify、Google Fonts の preload、フォントサブセット化

### コンテンツ
- [ ] **ブログ記事の継続投稿**（月1〜2本）
  - 「枚方でダンス練習できるスタジオの選び方」
  - 「撮影に使える防音スタジオ」
  - 「学割で使えるダンス練習場所」 など
- [ ] 被リンク獲得（地元ダンスインストラクター・スクール・撮影クリエイターからの紹介）

### サーバ・インフラ
- [ ] **サブドメイン統一**（`www` あり / なし）の 301 リダイレクト設定
- [ ] HTTPS ヘッダ整備（HSTS, X-Content-Type-Options 等）

---

## 🟢 検討段階の項目

- [ ] **管理システムへの自動ログイン連携**（必要なら）
- [ ] hero の h1 にキーワード入りテキスト追加（視覚デザインに手を入れる場合）
- [ ] 英語版/スペイン語版で hero の `<div class="hero__side">` を現地語に動的差し替え
  - 現状: JP=「大阪・枚方」, EN=「Hirakata, Osaka」, ES=「Hirakata, Osaka」

---

## ✅ 完了済み（履歴）

### 第1次（SEO基礎）
- [x] 旧テンプレ残骸ページの削除（about/works/recruit/contact/news/privacypolicy/thanks）
- [x] meta description / OGP / Twitter Card / canonical の整備
- [x] LocalBusiness/DanceSchool JSON-LD（住所/営業時間/Instagram）
- [x] robots.txt と sitemap.xml の作成
- [x] 404.html に noindex 設定

### 第2次（記事個別ページ）
- [x] `/info/` 各お知らせの個別ページ化（3記事）
- [x] 各記事に NewsArticle JSON-LD を付与
- [x] お知らせ一覧から個別記事へのリンク

### 第3次（多言語・構造化データ追加）
- [x] hreflang と英語版ページ `/en/`（6ページ）の整備
- [x] インラインの英語テキストに `lang="en"` 付与
- [x] 記事ページの h1 重複解消
- [x] alt 属性のキーワード入り改善
- [x] FAQPage JSON-LD（主要12問）
- [x] BreadcrumbList JSON-LD（サブページ全て）
- [x] Offer / OfferCatalog / paymentAccepted / priceRange

### 第4次（英語版調整）
- [x] 英語版の支払い記載を「On-site payment only」に統一

### 第5次（スペイン語対応）
- [x] スペイン語版ページ `/es/`（6ページ）の整備
- [x] JP/EN 全12ページの hreflang に es 追加
- [x] sitemap.xml に ES URL 追加（xhtml:link で ja/en/es 併記）

### 第6次（利用案内の実用化）
- [x] `/en/riyou/` と `/es/riyou/` をサマリーから15セクションの実用詳細版に拡充
  - 予約・料金・キャンセル・履物・入退室・機材・禁止行為・罰金額など実用情報を網羅
