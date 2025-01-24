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
            <input type="text" name="name" id="_name" class="form-control <?= hasError('name') ? 'is-invalid' : '' ?>" value="<?= old('name') ?>">

            <?php foreach (errors('name') as $error) : ?>
                <span class="invalid-feedback" role="alert">
                    <?= $error ?>
                </span>
            <?php endforeach; ?>
        </div>
        <div class="form-group mt-2">
            <label for="_mail">Email</label>
            <input type="text" name="email" id="_mail" class="form-control <?= hasError('email') ? 'is-invalid' : '' ?>" value="<?= old('email') ?>">

            <?php foreach (errors('email') as $error) : ?>
                <span class="invalid-feedback" role="alert">
                    <?= $error ?>
                </span>
            <?php endforeach; ?>
        </div>

        <div class="form-group mt-2">
            <label for="_mobile">Mobile</label>
            <input type="text" name="mobile" id="_mobile" class="form-control <?= hasError('mobile') ? 'is-invalid' : '' ?>" value="<?= old('mobile') ?>">

            <?php foreach (errors('mobile') as $error) : ?>
                <span class="invalid-feedback" role="alert">
                    <?= $error ?>
                </span>
            <?php endforeach; ?>
        </div>

        <div class="form-group mt-2">
            <label for="_pass">Password</label>
            <input type="password" name="password" id="_pass" class="form-control <?= hasError('password') ? 'is-invalid' : '' ?>" value="<?= old('password') ?>">

            <?php foreach (errors('password') as $error) : ?>
                <span class="invalid-feedback" role="alert">
                    <?= $error ?>
                </span>
            <?php endforeach; ?>
        </div>

        <input type="submit" value="Create" class="btn btn-primary mt-2">
    </form>
</div>