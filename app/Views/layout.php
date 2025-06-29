<?php
// Against XSS
function esc(string $str): string {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// Headers HTTP
header('X-Frame-Options: DENY'); // Vs clickjacking. App can't be loaded in iframe.
header('X-Content-Type-Options: nosniff'); // Vs MIME exploits. No sniffing (guessing) filetype -> no risk of executing code in txt/jpg/etc file.
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' ; style-src 'self' 'unsafe-inline' ; img-src 'self' ".BASE_URL); // Vs XSS. Only scripts from same domain (default;js;css;img).

?>

<!DOCTYPE html>
<html lang="fr" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <title><?= esc($title ?? 'Bienvenue '); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Bootstrap v5.3.6 - Compiled and minified CSS bundle -->
    <link rel="stylesheet" href="<?=BASE_URL?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?=BASE_URL?>assets/css/style.css">

    <!-- JQuery v3.7.1 - Compressed, production version -->
    <script defer src="<?=BASE_URL?>assets/js/jquery-3.7.1.min.js"></script>

    <!-- Bootstrap v5.3.6 - Compiled and minified JS bundle (includes Popper but not jQuery, jQuery must come first) -->
    <script defer src="<?=BASE_URL?>assets/js/bootstrap.bundle.min.js"></script>
    <script defer src="<?= BASE_URL ?>assets/js/script.js"></script>

    <link rel="icon" type="image/x-icon" href="<?=BASE_URL?>assets/favicon.ico">
</head>
<body class="d-flex flex-column min-vh-100" id="bootstrap-overrides">
    
<div class="site">

    <div><?php require_once __DIR__ . '/elements/header.php'; ?></div>
    <div>
    <main>
        <div class="container my-3">
            <?= $content ?? ''; ?>
        </div>        
    </main>
    </div>

    <div><?php require_once __DIR__ . '/elements/footer.php'; ?></div>

</div>

</body>
</html> 