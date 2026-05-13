#!/usr/bin/env python3
"""
あんじゅ Web サイト運用マニュアルの PDF をビルドする。

入力: docs/site-operations-manual.md
出力: assets/pdf/operations-manual.pdf

依存: pip install weasyprint markdown
実行: python3 scripts/build-manual-pdf.py
"""

from pathlib import Path
import markdown
from weasyprint import HTML, CSS

REPO_ROOT = Path(__file__).resolve().parent.parent
MD_PATH = REPO_ROOT / "docs" / "site-operations-manual.md"
OUT_PATH = REPO_ROOT / "assets" / "pdf" / "operations-manual.pdf"

PDF_CSS = """
@page {
  size: A4;
  margin: 18mm 16mm 22mm 16mm;
  @bottom-center {
    content: "あんじゅ Webサイト 運用マニュアル — " counter(page) " / " counter(pages);
    font-family: "Noto Sans JP", "Hiragino Sans", "Yu Gothic", sans-serif;
    font-size: 9pt;
    color: #a89086;
  }
}
* { box-sizing: border-box; }
body {
  font-family: "Noto Sans JP", "Hiragino Sans", "Yu Gothic", sans-serif;
  color: #3a3530;
  line-height: 1.75;
  font-size: 10.5pt;
}
h1 {
  font-size: 22pt;
  color: #5a4a3f;
  border-bottom: 2px solid #c4a8a0;
  padding-bottom: 10pt;
  margin: 0 0 18pt;
  page-break-after: avoid;
}
h2 {
  font-size: 15pt;
  color: #5a4a3f;
  margin: 24pt 0 10pt;
  padding-left: 10pt;
  border-left: 4pt solid #c4a8a0;
  page-break-after: avoid;
}
h3 {
  font-size: 12pt;
  color: #6a5848;
  margin: 18pt 0 6pt;
  page-break-after: avoid;
}
p { margin: 6pt 0; }
ul, ol { margin: 6pt 0; padding-left: 22pt; }
li { margin: 3pt 0; }
strong { color: #5a4a3f; }
code {
  background: #f5ede8;
  color: #8a6a5a;
  padding: 1pt 5pt;
  border-radius: 3pt;
  font-family: "SF Mono", "Menlo", "Consolas", monospace;
  font-size: 9pt;
}
a { color: #a89086; text-decoration: underline; }
blockquote {
  background: #fdf8f3;
  border-left: 3pt solid #d4b89c;
  margin: 10pt 0;
  padding: 8pt 14pt;
  color: #6a5848;
  font-size: 10pt;
  page-break-inside: avoid;
}
blockquote p { margin: 3pt 0; }
hr {
  border: none;
  border-top: 1pt solid #ede0d8;
  margin: 18pt 0;
}
table {
  width: 100%;
  border-collapse: collapse;
  margin: 10pt 0;
  font-size: 9.5pt;
  page-break-inside: avoid;
}
thead th {
  background: #c4a8a0;
  color: #fff;
  text-align: left;
  padding: 7pt 10pt;
  font-weight: 600;
}
tbody td {
  border-bottom: 0.5pt solid #ede0d8;
  padding: 6pt 10pt;
}
tbody tr:nth-child(even) td { background: #fbf7f4; }
.cover {
  text-align: center;
  padding: 80pt 0 60pt;
  page-break-after: always;
}
.cover__title {
  font-size: 26pt;
  color: #5a4a3f;
  margin-bottom: 16pt;
  letter-spacing: 0.04em;
}
.cover__sub {
  font-size: 12pt;
  color: #8a7868;
  margin-bottom: 40pt;
}
.cover__meta {
  display: inline-block;
  text-align: left;
  background: #faf7f5;
  border-radius: 8pt;
  padding: 16pt 24pt;
  font-size: 10pt;
  color: #6a5848;
}
.cover__meta p { margin: 4pt 0; }
"""

COVER_HTML = """
<section class="cover">
  <div class="cover__title">あんじゅ Webサイト<br>運用マニュアル</div>
  <div class="cover__sub">Web Operations Manual</div>
  <div class="cover__meta">
    <p>発行：合同会社あんじゅ</p>
    <p>対象：サイトを編集する従業員の方</p>
    <p>収録：お知らせ更新／ブログ投稿／画像差し替え／PDF差し替え／拠点情報の編集</p>
  </div>
</section>
"""


def build():
    md_text = MD_PATH.read_text(encoding="utf-8")
    md = markdown.Markdown(extensions=["extra", "tables", "sane_lists"])
    body_html = md.convert(md_text)

    full_html = f"""<!DOCTYPE html>
<html lang="ja">
<head><meta charset="utf-8"><title>あんじゅ Webサイト 運用マニュアル</title></head>
<body>
{COVER_HTML}
<main>{body_html}</main>
</body>
</html>"""

    OUT_PATH.parent.mkdir(parents=True, exist_ok=True)
    HTML(string=full_html).write_pdf(
        str(OUT_PATH),
        stylesheets=[CSS(string=PDF_CSS)],
    )
    print(f"Wrote: {OUT_PATH}  ({OUT_PATH.stat().st_size:,} bytes)")


if __name__ == "__main__":
    build()
