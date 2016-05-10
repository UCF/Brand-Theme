<?php get_header(); ?>

<h1>Log In</h1>
<p>
	To download brand assets, please log in using your NID and NID password below.
</p>

<form method="post" id="ldap-auth-form" action=".">

	<?php if ( $ldap_error ): ?>
	<div class="alert alert-danger">
		<strong>Error:</strong>
		<p>
			Your NID or password is invalid or the authentication service was unavailable.
		</p>
		<p>
			To verify your NID, go to <a href="http://my.ucf.edu/">myUCF</a> and select "What are my PID and NID?"<br>
			To reset your password, go to the <a href="http://mynid.ucf.edu/">Change Your NID Password</a> page.<br>
			For further help, contact the Service Desk at 407-823-5117, Monday-Friday 8am-5pm.
		</p>
	</div>
	<?php endif; ?>

	<div class="">
		<div class="form-group">
			<label for="uid-username">NID (Network ID)</label>
			<input name="uid-username" class="form-control" id="uid-username" type="text">
		</div>
		<div class="form-group">
			<label for="uid-password">Password</label>
			<input name="uid-password" id="uid-password" class="form-control" type="password">
		</div>
		<input name="uid-submit-auth" class="btn btn-default" id="uid-submit-auth" type="submit" value="Submit">
	</div>

	<?php wp_nonce_field( 'uid-auth', 'uid_auth_nonce' ); ?>

</form>

<?php get_footer(); ?>
