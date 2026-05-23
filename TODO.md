# TODO

## SEO・サイト改善 関連

### 🔴 ユーザー側で確認が必要

- [ ] **`system.suiseistudio.com` の管理画面（ログイン）URLを確認する**
  - 背景: 予約システムの管理者ログインURLがソース内に見当たらない
  - 確認方法（いずれか）:
    1. ブラウザで `https://system.suiseistudio.com/` を開き、フッターの「Powered by」やfaviconからサービス名を特定
    2. ページのソース（view-source）の `<title>` `<meta>` や静的ファイルの読み込みパス
    3. 予約システム導入時の契約メール・請求書を検索（`予約` `system` `管理` 等のキーワード）
  - 確認できたらClaudeに共有 → ログインURL形式を特定可能

### 🟡 今後やると効果が大きいSEO施策（未着手）

- [ ] OG画像専用ファイル（1200×630）を作成し `assets/img/ogp.jpg` として配置
- [ ] Google Search Console にサイト登録 + `sitemap.xml` を送信
- [ ] Googleビジネスプロフィール（旧マイビジネス）の整備
- [ ] 画像のWebP化（コアウェブバイタル改善）
- [ ] フッターに小さな言語切替リンク「English / 日本語」を追加（必要なら）
- [ ] ブログ記事の継続投稿（月1〜2本）
- [ ] サブドメイン統一（`www` あり/なし）の301リダイレクト設定（サーバ側）

### ✅ 完了済み

- [x] 旧テンプレ残骸ページの削除（about/works/recruit/contact/news/privacypolicy/thanks）
- [x] meta description / OGP / Twitter Card / canonical の整備
- [x] LocalBusiness/DanceSchool JSON-LD + Offer + FAQPage + BreadcrumbList
- [x] robots.txt と sitemap.xml の整備
- [x] `/info/` 各お知らせの個別ページ化（NewsArticle JSON-LD付き）
- [x] hreflang と英語版ページ（`/en/`）の整備
- [x] インラインの英語テキストに `lang="en"` 付与
- [x] 記事ページの h1 重複解消
- [x] alt属性のキーワード入り改善
- [x] 英語版の支払い記載を「On-site payment only」に統一
