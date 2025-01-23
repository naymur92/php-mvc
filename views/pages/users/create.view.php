<?php
$layoutFile = 'layouts.main';
?>

<div class="col-sm-12">
    <h2>Users Create</h2>
    <a class="btn btn-link" href="/users">Back</a>
    <hr>

    <form action="/users" method="POST">
        <div class="form-group">
            <label for="_name">Name</label>
            <input type="text" name="name" id="_name" class="form-control">
        </div>
        <div class="form-group mt-2">
            <label for="_mail">Email</label>
            <input type="text" name="email" id="_mail" class="form-control">
        </div>
        <div class="form-group mt-2">
            <label for="_mobile">Mobile</label>
            <input type="text" name="mobile" id="_mobile" class="form-control">
        </div>

        <input type="submit" value="Create" class="btn btn-primary mt-2">
    </form>
</div>