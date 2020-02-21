<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="/jokes.css">
    <title><?=$title?></title>
  </head>
  <body>
    <nav>
    <header>
      <h1>WEB SERVER FMU PROJECT</h1>
    </header>
      <ul>
        <li><a href="/">HOME</a></li>
        <li><a href="/joke/list">FMU DATA CONTENT</a></li>
        <li><a href="/joke/edit">FMU CONTENT REGISTER</a></li>
        <?php echo $loggedIn ?>
        <?php if($loggedIn): ?>
        <li><a href="/logout">LOGOUT</a></li>
        <?php else: ?>
        <li><a href="/login">LOGIN</a></li>
        <?php endif; ?>
      </ul>
    </nav>

    <main>
      <?=$output?>
    </main>
    <?php include 'footer.html.php'; ?>
  </body>
</html>
