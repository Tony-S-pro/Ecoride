<?php
// Against XSS
function esc(string $str): string {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// Headers HTTP
header('X-Frame-Options: DENY'); // Vs clickjacking. App can't be loaded in iframe.
header('X-Content-Type-Options: nosniff'); // Vs MIME exploits. No sniffing (guessing) filetype -> no risk of executing code in txt/jpg/etc file.
header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self'"); // Vs XSS. Only scripts from same domain (default;js;css).

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= esc($title ?? 'Bienvenue '); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/styles.css">
    <script defer src="<?= BASE_URL ?>assets/js/script.js"></script>
</head>
<body>

    <?php require_once __DIR__ . '/elements/header.php'; ?>

    <main class="container">
        <?= $content ?? ''; ?>
    </main>

    <?php require_once __DIR__ . '/elements/footer.php'; ?>

</body>
</html> 