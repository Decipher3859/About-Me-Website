<?php

class ContactMail
{
  private const FORM_VALUES = [
    'name' => 'Name',
    'email' => 'E-Mail',
    'phone' => 'Telefon',
    'message' => 'Nachricht',
  ];

  public static function send(): void
  {
    $honeypot = trim($_POST['website'] ?? '');
    if ($honeypot !== '') {
      http_response_code(200);
      echo self::renderResponse('/contact-success', [
        'summary' => [],
        'has_summary' => false,
      ]);
      exit;
    }

    $values = self::submittedValues();
    $name = $values['name'];
    $email = $values['email'];
    $phone = $values['phone'];
    $message = $values['message'];

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
      echo self::renderResponse('/contact-error', [
        'heading' => 'Ihre Nachricht konnte nicht gesendet werden.',
        'message' => 'Bitte prüfen Sie Ihre Eingaben und versuchen Sie es erneut.',
        'errors' => self::errorsForView($errors),
        'has_errors' => true,
        'summary' => self::summaryForView($values),
        'has_summary' => true,
      ]);
      exit;
    }

    $sent = false;
    ob_start();

    try {
      $sent = MailSender::sendContactMessage($name, $email, $phone, $message);
    } catch (Throwable $e) {
      $sent = false;
    } finally {
      ob_end_clean();
    }

    if (!$sent) {
      http_response_code(500);
      echo self::renderResponse('/contact-error', [
        'heading' => 'Die Nachricht konnte nicht gesendet werden.',
        'message' => 'Bitte versuchen Sie es später erneut oder schreiben Sie direkt an kontakt@damianschumachers.de.',
        'errors' => [],
        'has_errors' => false,
        'summary' => self::summaryForView($values),
        'has_summary' => true,
      ]);
      return;
    }

    echo self::renderResponse('/contact-success', [
      'summary' => self::summaryForView($values),
      'has_summary' => true,
    ]);
  }

  public static function methodNotAllowed(): void
  {
    http_response_code(405);
    echo self::renderResponse('/contact-error', [
      'heading' => 'Diese Anfrage ist nicht erlaubt.',
      'message' => 'Bitte senden Sie Ihre Nachricht über das Kontaktformular.',
      'errors' => [],
      'has_errors' => false,
      'summary' => [],
      'has_summary' => false,
    ]);
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

  private static function submittedValues(): array
  {
    return [
      'name' => trim($_POST['name'] ?? ''),
      'email' => trim($_POST['email'] ?? ''),
      'phone' => trim($_POST['phone'] ?? ''),
      'message' => trim($_POST['message'] ?? ''),
    ];
  }

  private static function summaryForView(array $values): array
  {
    $summary = [];

    foreach (self::FORM_VALUES as $field => $label) {
      $value = $values[$field] ?? '';

      $summary[] = [
        'label' => $label,
        'value' => $value !== '' ? $value : 'nicht angegeben',
      ];
    }

    return $summary;
  }

  private static function errorsForView(array $errors): array
  {
    $items = [];

    foreach ($errors as $field => $message) {
      $items[] = [
        'label' => self::FORM_VALUES[$field] ?? $field,
        'message' => $message,
      ];
    }

    return $items;
  }

  private static function renderResponse(string $template, array $content): string
  {
    $templates = new Templates();
    $data = $templates->data('/contact');
    $data['content'] = array_merge($data['content'], $content);

    return $templates->render($template, $data);
  }
}
