import { NextResponse } from "next/server";
import nodemailer from "nodemailer";

export const runtime = "nodejs";

type ApplyPayload = {
  name?: string;
  age?: string;
  tel?: string;
  email?: string;
  position?: string;
  message?: string;
};

const AGE_LABELS: Record<string, string> = {
  "20-24": "20代前半(20〜24歳)",
  "25-29": "20代後半(25〜29歳)",
  "30-34": "30代前半(30〜34歳)",
  "35+": "35歳以上",
};

export async function POST(request: Request) {
  let payload: ApplyPayload;
  try {
    payload = (await request.json()) as ApplyPayload;
  } catch {
    return NextResponse.json({ ok: false, error: "invalid json" }, { status: 400 });
  }

  const { name, age, tel, email, position, message } = payload;

  if (!name || !age || !tel || !email) {
    return NextResponse.json(
      { ok: false, error: "missing required fields" },
      { status: 400 }
    );
  }

  const host = process.env.SMTP_HOST;
  const port = Number(process.env.SMTP_PORT || 587);
  const user = process.env.SMTP_USER;
  const pass = process.env.SMTP_PASS;
  const to = process.env.APPLY_TO_EMAIL;
  const from = process.env.APPLY_FROM_EMAIL || user;

  const ageLabel = AGE_LABELS[age] ?? age;

  const textBody = [
    "ESCO PROMOTION 採用LPより応募がありました。",
    "",
    `お名前: ${name}`,
    `年齢: ${ageLabel}`,
    `電話番号: ${tel}`,
    `メールアドレス: ${email}`,
    `希望職種: ${position ?? ""}`,
    "",
    "メッセージ:",
    message ?? "(なし)",
  ].join("\n");

  // SMTP設定がない場合はログに出力して成功で返す(開発/未設定環境向け)
  if (!host || !user || !pass || !to) {
    console.log("[apply] SMTP env not configured. Payload:\n" + textBody);
    return NextResponse.json({ ok: true, mailed: false });
  }

  try {
    const transporter = nodemailer.createTransport({
      host,
      port,
      secure: port === 465,
      auth: { user, pass },
    });

    await transporter.sendMail({
      from,
      to,
      replyTo: email,
      subject: "【採用LP】新規応募",
      text: textBody,
    });

    return NextResponse.json({ ok: true, mailed: true });
  } catch (err) {
    console.error("[apply] sendMail error", err);
    return NextResponse.json(
      { ok: false, error: "mail send failed" },
      { status: 500 }
    );
  }
}
