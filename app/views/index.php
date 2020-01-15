<?php section("main") ?>

	<h1><?= $name ?? $user->name ?? "Buddy" ?> has token <?= isset($user) ? $user->tokens->first()->token : "" ?></h1>

<?php endsection() ?>

<?php extend("home") ?>