<?php
require 'database.php';

$message = '';

// Check if the form was submitted for adding a new item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'], $_POST['description'])) {
    // The form was submitted to add a new item
    $title = $_POST['title'];
    $description = $_POST['description'];
    
    $stmt = $pdo->prepare('INSERT INTO todoitems (Title, Description) VALUES (?, ?)');
    if ($stmt->execute([$title, $description])) {
        $message = 'New item added.';
    } else {
        $message = 'An error occurred.';
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove'], $_POST['itemNum'])) {
    // The form was submitted to remove an item
    $itemNum = $_POST['itemNum'];
    
    $stmt = $pdo->prepare('DELETE FROM todoitems WHERE ItemNum = ?');
    if ($stmt->execute([$itemNum])) {
        $message = 'Item removed.';
    } else {
        $message = 'An error occurred while removing the item.';
    }
}


$stmt = $pdo->prepare('SELECT * FROM todoitems');
$stmt->execute();
$items = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ToDo List Application</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/main.css">
</head>
</head> 
<body>
    <header>
        <h1>My To Do List</h1>
    </header>

    <?php if (!empty($message)): ?>
        <p><?= $message; ?></p>
    <?php endif; ?>

    <?php if (empty($items)): ?>
        <p>No to-do list items exist yet.</p>
    <?php else: ?>
        
        <ul>
            <?php foreach ($items as $item): ?>
                <li class="todo-item">
            <div class="item-text">
                <div class="item-title"><?= htmlspecialchars($item['Title']); ?></div>
                <div class="item-description"><?= htmlspecialchars($item['Description']); ?></div>
            </div>
            <form method="post" action="index.php" class="item-remove">
                <input type="hidden" name="itemNum" value="<?= htmlspecialchars($item['ItemNum']); ?>">
                <button type="submit" name="remove" class="remove-btn">X</button>
            </form>
        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </section>

        <section class="add-item-form">
            <h2>Add Item</h2>
            <form method="post" action="index.php">
                <div class="form-group">
                    <label for="title">Title:</label>
                    <input type="text" name="title" id="title" required>
                </div>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <input type="text" name="description" id="description" required>
                </div>
                <div class="form-group">
                    <input type="submit" value="Add" class="add-btn">
                </div>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; <?= date('Y'); ?> My To-Do List Application</p>
    </footer>
</body>
</html>