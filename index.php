<?php require_once('./includes/db.php'); ?>

<?php
if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $delete = " DELETE FROM users WHERE user_id= '$id'";
  $stmt1 = $pdo->query($delete);

  if ($stmt1) {
    echo "successfully Deleted";
  } else {
    echo "Query Fail";
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Task List</title>
  <link rel="stylesheet" href="./css/bootstrap.min.css">
</head>

<body>
  <div class="container">



    <?php

    if (isset($_POST['addNewUser'])) {
      $user_name = $_POST['username'];
      $user_email = $_POST['email'];
      $user_password = "SECRETKEY";
      if (empty($user_name) || empty($user_email)) {
        $error = true;
      } else {
        // add  new user
        $sql = "INSERT INTO users(user_name,user_email,user_password) VALUES (:name,:email, :password)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
          ':name' => $user_name,
          ':email' => $user_email,
          ':password' => $user_password
        ]);
        header("location: index.php");
      }
    }

    ?>
    <form class="py-4" action="index.php" method="POST">
      <div class="row">
        <div class="col">
          <input type="text" name="username" class="form-control" placeholder="Username">
        </div>
        <div class="col">
          <input type="text" name="email" class="form-control" placeholder="Email Address">
        </div>
        <div class="col">
          <input type="submit" name="addNewUser" class="form-control btn btn-secondary" value="Add New User">
          <?php echo isset($error) ? "<p> Field Can't be blank</p>" : ""; ?>
        </div>
      </div>
    </form>



    <h2>All Users</h2>
    <table class="table">
      <thead class="thead-dark">
        <tr>
          <th scope="col">ID</th>
          <th scope="col">Username</th>
          <th scope="col">Email</th>
          <th scope="col">Edit</th>
          <th scope="col">Delete</th>
        </tr>
      </thead>
      <tbody>

        <?php

        $sql = 'SELECT * FROM users';
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        while ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
          $user_id = $user['user_id'];
          $user_name = $user['user_name'];
          $user_email = $user['user_email']; ?>

          <tr>
            <th> <?php echo $user_id; ?> </th>
            <td><?php echo $user_name; ?></td>
            <td><?php echo $user_email; ?></td>
            <td>
              <form action="edit-user.php" method="POST">
                <input type="hidden" value="<?php echo $user_id; ?>" name="val" />
                <input type="submit" class="btn btn-link" value="Edit" name="submit" />
              </form>

            </td>
            <td>
              <form action="index.php" method="POST">
                <a class="btn btn-link" href="?id=<?php echo $user_id;  ?>">delete </a>
                <!-- <input type="hidden" value="< ?php echo $uder_id; ?>" name="val" />
                      <input type="submit" class="btn btn-link" value="Delete" name="submit" /> -->
              </form>

            </td>
          </tr>

        <?php

        }

        ?>

      </tbody>
    </table>
    <?php
    if (isset($_POST['submit'])) {
      $id = $_POST['val'];
      $sql = "DELETE FROM users WHERE user_id =:id";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([':id' => $id]);
    }

    ?>

  </div>
  <?php require_once('./includes/footer.php'); ?>