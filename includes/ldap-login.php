<?php get_header(); ?>

<div class="container">
	<div class="row">
		<div class="content col-sm-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
			<h2>Unit Identity Lock-up Access</h2>
			<p class="h2">
				You must log in to access or request at Unit Identity Lock-up. Please enter your UCF Federated Identity (NID) to log in.
			</p>
			<form method="post" id="ldap-auth-form" action=".">
				<?php if ( $ldap_error ): ?>
				<div class="alert alert-danger">
					<strong>Error:</strong>
					<p>
						Your NID or password is invalid or the authentication service was unavailable.
					</p>
					<p>
						To verify your NID, go to <a href="https://my.ucf.edu/">myUCF</a> and select "What are my PID and NID?"<br>
						To reset your password, go to the <a href="https://mynid.ucf.edu/">Change Your NID Password</a> page.<br>
						For further help, contact the Service Desk at 407-823-5117, Monday-Friday 8am-5pm.
					</p>
				</div>
				<?php endif;
			?>
				<div class="row">
					<div class="col-sm-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
						<div class="form-group">
							<label for="uid-username">NID (Network ID)</label>
							<input name="uid-username" class="form-control" id="uid-username" type="text" placeholder="NID (Network ID)">
						</div>
						<div class="form-group">
							<label for="uid-password">Password</label>
							<input name="uid-password" id="uid-password" class="form-control" type="password" placeholder="Password">
						</div>
						<input name="uid-submit-auth" class="btn btn-ucf" id="uid-submit-auth" type="submit" value="Submit">
					</div>
				</div>
				<p class="forgot-password">
					Forget your NID or pasword? Reset it <a href="http://mynid.ucf.edu/">here</a>.
				</p>
				<hr>
				<?php wp_nonce_field( 'uid-auth', 'uid_auth_nonce' );
			?>
			</form>
		</div>
	</div>
</div>

<?php get_footer(); ?>
