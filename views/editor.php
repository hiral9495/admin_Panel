<?php
include "main.php"; ?>

    <div class="row">
        <div class="col d-flex justify-content-between align-items-center">
            <h1 class="mb-0">Editor Dashboard</h1>
            <?php if (isset($_SESSION["super_admin_backup"])): ?>
                <form action="superAdmin" method="POST" class="mb-0">
                    <button type="submit" class="btn btn-primary">Return to Super Admin Dashboard</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col">
            <p>Welcome to the Editor dashboard! <b><?php echo strtoupper(
                $_SESSION["username"]
            ); ?></b></p>
        </div>
    </div>
</div> 
</body>
</html>