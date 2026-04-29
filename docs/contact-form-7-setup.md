# Contact Form 7 設定テンプレート

> 既存サイトデザインに合わせるための Contact Form 7（CF7）の設定資料。
> CF7 のデフォルト出力では `.cnt-form__*` クラスが付かないので、本資料の通りに設定する必要があります。

---

## 📋 CF7 「フォーム」タブに貼り付ける内容

WordPress 管理画面 → **お問い合わせ → コンタクトフォーム** → 既存の「コンタクトフォーム1」を編集 → **「フォーム」タブ** に以下を**まるごとコピー＆ペースト**してください。

```html
<div class="cnt-form__group">
  <label class="cnt-form__label" for="cf7-subject">ご用件 <span class="cnt-form__required">*</span></label>
  [select* your-subject id:cf7-subject class:cnt-form__select "サービスについて" "その他"]
</div>

<div class="cnt-form__group">
  <label class="cnt-form__label" for="cf7-name">お名前 <span class="cnt-form__required">*</span></label>
  [text* your-name id:cf7-name class:cnt-form__input]
</div>

<div class="cnt-form__group">
  <label class="cnt-form__label" for="cf7-email">メールアドレス <span class="cnt-form__required">*</span></label>
  [email* your-email id:cf7-email class:cnt-form__input]
</div>

<div class="cnt-form__group">
  <label class="cnt-form__label" for="cf7-company">社名</label>
  [text your-company id:cf7-company class:cnt-form__input]
</div>

<div class="cnt-form__group">
  <label class="cnt-form__label" for="cf7-message">お問い合わせ内容 <span class="cnt-form__required">*</span></label>
  [textarea* your-message id:cf7-message class:cnt-form__textarea rows:8]
</div>

<div class="cnt-form__footer">
  <span class="cnt-form__privacy">
    [acceptance privacy-consent class:cnt-form__privacy-input]<a href="/privacy-policy/" target="_blank">プライバシーポリシー</a>に同意する[/acceptance]
  </span>
  [submit class:cnt-form__submit "送信する"]
</div>
```

「保存」ボタンをクリック。

---

## 📧 「メール」タブの設定

CF7 の **「メール」タブ** で、フォーム送信時の通知メールを設定。

### 送信先（管理者宛）

| 項目 | 値 |
|------|---|
| **送信先** | 受信したいメールアドレス（例：`info@anju-group.com`） |
| **送信元** | `[your-name] <wordpress@your-domain.com>` |
| **題名** | `【お問い合わせ】[your-subject] - [your-name] 様より` |
| **追加ヘッダー** | `Reply-To: [your-email]` |

**メッセージ本文：**
```
合同会社あんじゅ Webサイトより、お問い合わせがありました。

────────────────────
■ ご用件
[your-subject]

■ お名前
[your-name]

■ メールアドレス
[your-email]

■ 社名
[your-company]

■ お問い合わせ内容
[your-message]
────────────────────

このメールは合同会社あんじゅ Webサイトの
お問い合わせフォームより送信されました。
```

### 送信先（自動返信、お問い合わせ者宛）

「**メール（2）を使用**」のチェックを入れて、お問い合わせした方への自動返信メールを設定。

| 項目 | 値 |
|------|---|
| **送信先** | `[your-email]` |
| **送信元** | `合同会社あんじゅ <info@your-domain.com>` |
| **題名** | `【自動返信】お問い合わせありがとうございます` |
| **追加ヘッダー** | `Reply-To: info@anju-group.com` |

**メッセージ本文：**
```
[your-name] 様

合同会社あんじゅです。
この度はお問い合わせいただきまして、誠にありがとうございます。

以下の内容で承りました。
担当者より、改めてご連絡させていただきますので、
今しばらくお待ちくださいませ。

────────────────────
■ ご用件
[your-subject]

■ お問い合わせ内容
[your-message]
────────────────────

※本メールは自動配信されています。
※お心当たりの無い方は、お手数ですが破棄いただきますようお願いいたします。

──────────
合同会社あんじゅ
〒143-0023 東京都大田区山王3-35-7 八景坂マンション301
https://anju-group.com/
──────────
```

---

## 💬 「メッセージ」タブの設定

そのままで OK。デザインは theme の CSS が当たるので変更不要。

必要に応じて文言を調整：
- 「**メッセージが正常に送信されました。**」 → 「**お問い合わせを送信いたしました。担当者より折り返しご連絡いたします。**」

---

## ⚙️ 「その他の設定」タブ（送信後リダイレクト）

CF7 で送信完了後に `/contact/thanks/` ページへ自動遷移させる場合：

```
on_sent_ok: "location.replace('/contact/thanks/');"
```

> ⚠️ **注意：** CF7 v5.0 以降、`on_sent_ok` は非推奨になっており動作しないことがあります。動作しない場合は **Redirection for Contact Form 7** プラグインを別途インストールしてください。

### 推奨：プラグイン併用（より確実）

1. 「**Redirection for Contact Form 7**」プラグインをインストール・有効化
2. CF7 編集画面 → 「Redirect Settings」タブが追加される
3. **Redirect to URL** に `/contact/thanks/` を入力
4. 保存

---

## 🛡 reCAPTCHA（スパム対策）の設定

スパム送信を防ぐため、Google reCAPTCHA v3 を設定：

1. https://www.google.com/recaptcha/admin/create で reCAPTCHA キー取得
   - reCAPTCHA タイプ：**v3**
   - ドメイン：本番サイトのドメイン（例：`anju-group.com`）
2. **サイトキー** と **シークレットキー** を控える
3. CF7 の設定 → **インテグレーション** タブ → reCAPTCHA → 「インテグレーションのセットアップ」
4. キーを入力 → 保存

---

## 🔌 Contact Form 7 ショートコードを固定ページに貼り付け

1. WP管理画面 → 固定ページ → **「お問い合わせ」を編集**
2. 編集画面下部に表示される **「お問い合わせページ設定」** タブ
3. **Contact Form 7 ショートコード** フィールドに以下を入力：

```
[contact-form-7 id="123" title="コンタクトフォーム1"]
```

> ⚠️ `id="123"` の数字は、CF7 で作成したフォームの実際のIDに置き換えてください。
> CF7 管理画面の フォーム一覧画面で各フォームの右側に表示される「ショートコード」をそのままコピーすればOKです。

4. 「更新」ボタン

---

## ✅ 動作確認

1. `/contact/` にアクセス
2. デザインが既存サイトと同じか確認
   - 入力欄：背景 #f5f2ef、下線スタイル
   - 「送信する」ボタン：ピンク色の丸い形
   - プライバシーポリシー同意：チェックボックス
3. 試しにフォームを送信してみる
4. 管理者メアドに通知メール、送信者メアドに自動返信メールが届くか
5. 送信後、`/contact/thanks/` ページへ遷移するか
6. CF7 の「フライング送信履歴」（Flamingo プラグイン）に記録されているか

---

## 🐛 デザインが崩れる場合のチェック

| 症状 | 原因と対処 |
|------|-----------|
| 入力欄が標準ボックスに見える | CF7 のフォーム定義に `class:cnt-form__input` を入れ忘れ |
| 送信ボタンが灰色 | `class:cnt-form__submit` が抜けている |
| プライバシー同意がチェックボックスに見えない | `[acceptance]` の中に `<a>` リンクが正しく入っているか確認 |
| ラベルが表示されない | `<label>...</label>` の HTML が落ちていないか確認 |
| キャッシュで古い見た目が残る | キャッシュプラグインのキャッシュクリア |

---

## 📚 関連ドキュメント

- `docs/site-operations-manual.md` — 運用マニュアル
- `docs/launch-checklist.md` — ローンチ前チェックリスト
- WP 管理画面 → お問い合わせ → コンタクトフォーム → ヘルプタブ
