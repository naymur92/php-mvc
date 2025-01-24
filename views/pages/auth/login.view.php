<?php
$layoutFile = 'layouts.main';
?>

<div class="col-sm-8">
    <h2>Login Form</h2>
    <a class="btn btn-link" href="/">Back</a>
    <hr>

    <form action="/login" method="POST">

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
            <label for="_pass">Password</label>
            <input type="password" name="password" id="_pass" class="form-control <?= hasError('password') ? 'is-invalid' : '' ?>" value="<?= old('password') ?>">

            <?php foreach (errors('password') as $error) : ?>
                <span class="invalid-feedback" role="alert">
                    <?= $error ?>
                </span>
            <?php endforeach; ?>
        </div>

        <input type="submit" value="Login" class="btn btn-primary mt-2">
    </form>
</div>