<?php

# Check was this fil linked directly
if(!defined('SYSPATH')) exit('No direct script access allowed!');

?>
				<div id="top">
					<h1>Sign in to <?php echo $appData['appname']; ?></h1>
				</div>
				<span class="descText"><?php echo $appData['appdescription']; ?></span>
				<div class="mainContent">
					<div class="innerContent">
						<p class="note">Use <strong>admin</strong> for the user name and the password to sign in as <strong>admin</strong>.</p>
						<p class="note">Use <strong>user</strong> for the user name and the password to sign in as <strong>user</strong>.</p>
						<p class="note">Use <strong>newuser</strong> for the user name and the password to sign in as <strong>newuser</strong>.</p>
						<form action="" method="post" id="signIn">
							<div>
								<label for="username">User name:</label>
								<input type="text" name="username" id="username" class="textInput" value="" size="30" />
							</div>
							<div>
								<label for="userpass">Password:</label>
								<input type="password" name="userpass" id="userpass" class="textInput" value="" size="30" />
							</div>
							<div id="buttons">
								<input type="submit" name="signInFormSubmit" id="signInFormSubmit" class="formSubmit" value="Sign in" />
								<input type="submit" name="signUpFormSubmit" id="signUpFormSubmit" class="formSubmit" value="Join" />
							</div>
						</form>
					</div>
				</div>