<?php
/**
 * お問い合わせフォーム送信エンドポイント（Xserver用）
 * - POSTのみ受付・ハニーポット/簡易バリデーション付き
 * - info@izayoi.co.jp へ mb_send_mail で送信
 */

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['ok' => false, 'error' => 'method not allowed']);
  exit;
}

// ハニーポット（botは見えないwebsite欄を埋める）
if (!empty($_POST['website'] ?? '')) {
  // botには成功したふりを返す
  echo json_encode(['ok' => true]);
  exit;
}

$name    = trim((string)($_POST['name'] ?? ''));
$company = trim((string)($_POST['company'] ?? ''));
$email   = trim((string)($_POST['email'] ?? ''));
$topic   = trim((string)($_POST['topic'] ?? ''));
$message = trim((string)($_POST['message'] ?? ''));

if ($name === '' || $email === '' || $topic === '' || $message === '') {
  http_response_code(400);
  echo json_encode(['ok' => false, 'error' => 'missing fields']);
  exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  http_response_code(400);
  echo json_encode(['ok' => false, 'error' => 'invalid email']);
  exit;
}

// ヘッダインジェクション対策
$name  = str_replace(["\r", "\n"], '', $name);
$email = str_replace(["\r", "\n"], '', $email);
$topic = str_replace(["\r", "\n"], '', $topic);

// 長さ制限（暴走対策）
$message = mb_substr($message, 0, 5000);

mb_language('Japanese');
mb_internal_encoding('UTF-8');

$to      = 'info@izayoi.co.jp';
$subject = '【HPお問い合わせ】' . $topic . ' — ' . $name . '様';

$body  = "IZAYOI公式サイトのお問い合わせフォームから送信されました。\n\n";
$body .= "■ お名前\n{$name}\n\n";
$body .= "■ 会社名・屋号\n" . ($company !== '' ? $company : '（未記入）') . "\n\n";
$body .= "■ メールアドレス\n{$email}\n\n";
$body .= "■ ご相談内容\n{$topic}\n\n";
$body .= "■ メッセージ\n{$message}\n\n";
$body .= "----\n送信日時: " . date('Y-m-d H:i:s') . "\nIP: " . ($_SERVER['REMOTE_ADDR'] ?? 'unknown') . "\n";

// Fromはドメインのアドレス、Reply-Toに送信者を設定（迷惑メール判定対策）
$headers  = "From: IZAYOI HP <info@izayoi.co.jp>\r\n";
$headers .= "Reply-To: {$email}\r\n";

$sent = mb_send_mail($to, $subject, $body, $headers);

if ($sent) {
  echo json_encode(['ok' => true]);
} else {
  http_response_code(500);
  echo json_encode(['ok' => false, 'error' => 'mail failed']);
}
