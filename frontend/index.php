<?php
$db = pg_connect(sprintf(
  "host=%s dbname=%s user=%s password=%s",
  getenv('DB_HOST'), getenv('DB_NAME'),
  getenv('DB_USER'), getenv('DB_PASSWORD')
));
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $t = pg_escape_literal($db, $_POST['title']);
  $c = pg_escape_literal($db, $_POST['content']);
  pg_query($db, "INSERT INTO bulletins(title,content,created_at) VALUES($t,$c,now())");
}
$res = pg_query($db, "SELECT title,content,created_at FROM bulletins ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Office Bulletin Board</title>
  <style>
    body { font-family: Arial; max-width:600px; margin:auto; padding:20px; }
    form, ul { margin-bottom:20px; }
    input, textarea { width:100%; padding:8px; margin:4px 0; }
    button { padding:8px 16px; }
    li { border:1px solid #ccc; padding:8px; margin-bottom:8px; }
    small { color:#666; }
  </style>
</head>
<body>
  <h1>Office Bulletin Board</h1>
  <form method="post">
    <input name="title" placeholder="Title" required><br>
    <textarea name="content" placeholder="Content" required></textarea><br>
    <button>Post</button>
  </form>
  <ul>
    <?php while ($row = pg_fetch_assoc($res)): ?>
      <li>
        <strong><?= htmlspecialchars($row['title']) ?></strong><br>
        <?= nl2br(htmlspecialchars($row['content'])) ?><br>
        <small><?= date('Y-m-d H:i:s', strtotime($row['created_at'])) ?></small>
      </li>
    <?php endwhile; ?>
  </ul>
</body>
</html>
