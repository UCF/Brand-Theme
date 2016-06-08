<?php get_header(); ?>

<div class="container">
	<div class="row">
		<div class="col-md-3">
			<?php
			wp_nav_menu( array(
				'theme_location' => 'left-menu',
				'container' => false,
				'menu_class' => 'list-inline site-left-menu'
			) );
			?>
		</div>
		<div class="col-md-9">
			<h1>Log In</h1>
			<p class="desc">
				You must log in to access or request a Unit Identifier. Please enter your UCF Federated Identity below.
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
					<div class="col-md-8 col-sm-12">
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
