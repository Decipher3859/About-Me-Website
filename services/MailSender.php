<?php

use PHPMailer\PHPMailer\PHPMailer;

class MailSender
{
  public static function sendContactMessage(
    string $name,
    string $email,
    string $phone,
    string $message
  ): bool {
    try {
      $config = self::config();
      $mail = new PHPMailer(true);

      // SMTP
      $mail->isSMTP();
      $mail->Host = $config['SMTP_HOST'];
      $mail->SMTPAuth = true;
      $mail->Username = $config['SMTP_USERNAME'];
      $mail->Password = $config['SMTP_PASSWORD'];
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
      $mail->Port = (int) $config['SMTP_PORT'];

      // Sender / Empfänger
      $mail->setFrom($config['SMTP_FROM'], $config['SMTP_FROM_NAME']);
      $mail->addAddress($config['MAIL_TO'], $config['MAIL_TO_NAME']);
      $mail->addReplyTo($email, $name);

      $mail->Subject = 'Anfrage über Web-Portfolio';

      $mail->Body = <<<TEXT
      Name: {$name}
      E-Mail: {$email}
      Telefon: {$phone}

      Nachricht:
      {$message}
      TEXT;

      $mail->send();
      return true;
    } catch (\Throwable $e) {
      return false;
    }
  }

  private static function config(): array
  {
    $keys = [
      'SMTP_HOST',
      'SMTP_PORT',
      'SMTP_USERNAME',
      'SMTP_PASSWORD',
      'SMTP_FROM',
      'SMTP_FROM_NAME',
      'MAIL_TO',
      'MAIL_TO_NAME',
    ];

    $config = [];

    foreach ($keys as $key) {
      $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);

      if ($value === false || trim((string) $value) === '') {
        throw new \RuntimeException('Missing mail configuration: ' . $key);
      }

      $config[$key] = trim((string) $value);
    }

    return $config;
  }
}
