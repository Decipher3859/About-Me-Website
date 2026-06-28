<?php

class ContactMail
{
  public static function send(): void
  {
    $honeypot = trim($_POST['website'] ?? '');
    if ($honeypot !== '') {
      http_response_code(200);
      echo 'Vielen Dank für Ihre Nachricht.';
      exit;
    }

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $message = trim($_POST['message'] ?? '');

    echo '<pre>';
    var_dump($_POST);     // DEBUG: Gibt den Inhalt des Posts aus.
    echo '</pre>';

    $errors = [];

    foreach (
      [
        'name' => self::validateName($name),
        'email' => self::validateEmail($email),
        'phone' => self::validatePhone($phone),
        'message' => self::validateMessage($message),
      ] as $field => $error
    ) {
      if ($error !== null) {
        $errors[$field] = $error;
      }
    }

    if ($errors !== []) {
      http_response_code(422);

      echo '<pre>';
      var_dump($errors);
      echo '</pre>';

      exit;
    }

    $sent = MailSender::sendContactMessage($name, $email, $phone, $message);

    if (!$sent) {
      http_response_code(500);
      echo 'Die Nachricht konnte nicht gesendet werden.';
      return;
    }

    echo 'Vielen Dank für Ihre Nachricht.';
  }

  private static function validateName(string $name): ?string
  {
    if ($name === '') {
      return 'Name fehlt.';
    }
    return null;
  }

  private static function validateEmail(string $email): ?string
  {
    if ($email === '') {
      return 'E-Mail fehlt.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      return 'E-Mail-Adresse ungültig.';
    }
    return null;
  }

  private static function validatePhone(string $phone): ?string
  {
    if ($phone !== '' && !preg_match('/^[0-9+\s()\/-]{6,30}$/', $phone)) {
      return 'Telefonnummer ungültig.';
    };
    return null;
  }

  private static function validateMessage(string $message): ?string
  {
    if ($message === '') {
      return 'Nachricht fehlt.';
    } elseif (mb_strlen($message) < 10) {
      return 'Nachricht zu kurz.';
    }
    return null;
  }
}
