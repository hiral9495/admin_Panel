<!-- C:\wamp64\www\Admin_Panel\views\userList.php -->
<?php
   ini_set('display_errors', 1);
   ini_set('display_startup_errors', 1);
   error_reporting(E_ALL);
   ini_set('error_log', 'custom-error.log');
   
   include 'main.php';
   ?>
<div class="container">
   <h1>User List</h1>
   <table class="table">
      <thead>
         <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>User Role</th>
            <th>Actions</th>
         </tr>
      </thead>
      <tbody>
         <?php foreach ($users as $user): ?>
         <tr>
            <td><?php echo htmlspecialchars($user['id']); ?></td>
            <td><?php echo htmlspecialchars($user['name']); ?></td>
            <td><?php echo htmlspecialchars($user['email']); ?></td>
            <td><?php echo htmlspecialchars($user['user_type']); ?></td>
            <td>
               <button type="button" id="<?php echo htmlspecialchars($user['id']); ?>" class="btn btn-primary loginAs">Login As User</button>
            </td>
         </tr>
         <?php endforeach; ?>
      </tbody>
   </table>
</div>
</div>
</div> 
</body>
</html>