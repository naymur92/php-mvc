<?php
$layoutFile = 'layouts.main';
?>

<div class="col-sm-12">
    <h2>Users List</h2>
    <a class="btn btn-link" href="/users/create">Create New User</a>
    <hr>

    <table class="table table-hover">
        <tr>
            <th>SL.</th>
            <th>Name</th>
            <th>Email</th>
            <th>Mobile</th>
            <th>Created At</th>
            <th>Action</th>
        </tr>

        <?php foreach ($users as $key => $user): ?>
            <tr>
                <td><?= $key + 1 ?></td>
                <td><?= $user['name'] ?></td>
                <td><?= $user['email'] ?></td>
                <td><?= $user['mobile'] ?></td>
                <td><?= date("Y-m-d", strtotime($user['created_at'])) ?></td>
                <td></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>