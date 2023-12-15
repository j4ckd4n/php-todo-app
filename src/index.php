<?php
$errors = null;

$db = new SQLite3("/var/www/db/todos.db");
$create_table = <<<SQL
CREATE TABLE IF NOT EXISTS todos (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  task TEXT NOT NULL,
  creation_date DATETIME DEFAULT CURRENT_TIMESTAMP
);
SQL;

$db->query($create_table);

if($_SERVER["REQUEST_METHOD"] == "POST"){
  if(empty($_POST["task-name"])){
    $errors = "You must fill in the task";
  } else {
    $task = filter_input(INPUT_POST, "task-name", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $query = "INSERT INTO todos(task) VALUES (:taskname);";
    $stmt = $db->prepare($query);
    $stmt->bindValue(":taskname", $task, SQLITE3_TEXT);
    $stmt->execute();
    
    $stmt->close();
    $db->close();
    header('Location: index.php');
  }
} elseif ($_SERVER["REQUEST_METHOD"] == "GET") {
  if(isset($_GET["delete"])){
    $id = filter_input(INPUT_GET, "delete", FILTER_SANITIZE_NUMBER_INT);
    $id = filter_var($id, FILTER_VALIDATE_INT);
    if(!empty($id)){
      $query = "DELETE FROM todos WHERE id=:id;";
      $stmt = $db->prepare($query);
      $stmt->bindValue(":id", $id, SQLITE3_INTEGER);
      $result = $stmt->execute();
      
      $stmt->close();
      $db->close();
      header('Location: index.php');
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  <title>TODOs</title>
</head>
<body data-bs-theme="dark">
  <div class="container">
    <header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom">
      <a class="d-flex align-items-center mb-3 mb-md-0 link-body-emphasis text-decoration-none" href="/">
        <spam class="fs-4">SIMPLE TODO APP</spam>
      </a>
    </header>
  </div>
  <div class="container">
    <form method="post" action="index.php" id="input-form">
      <?php if(isset($errors)): ?>
        <div class="alert alert-warning" role="alert">
          <?php echo $errors; ?>
        </div>
      <?php endif; ?>
      <div class="input-group mb-3">
        <div class="form-floating">
          <input type="text" class="form-control" id="task-name" name="task-name" placeholder="">
          <label for="task-name">Enter task name here...</label>
        </div>
        <input type="submit" value="➕" name="add-todo" class="btn btn-warning input-group-text">
      </div>
      <table class="table table-hover">
        <thead>
          <th scope="col">#</th>
          <th scope="col">Task Name</th>
          <th scope="col">Delete Task</th>
        </thead>
        <tbody>
          <?php
            $query = "SELECT * from todos;";
            $result = $db->query($query);
            while($row = $result->fetchArray(SQLITE3_ASSOC)) { ?>
            <tr>
              <th scope="row"><?php echo $row["id"]; ?></th>
              <td><?php echo $row["task"]; ?></td>
              <td>
                <a href="index.php?delete=<?php echo $row["id"]; ?>">❌</a>
              </td>
            </tr>
          <?php }; ?>
        </tbody>
      </table>
    </form>
  </div>
</body>
</html>