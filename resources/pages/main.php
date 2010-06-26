<?php
# Check was this file linked directly
if(!defined('BASEPATH')) exit('No direct script access allowed');
?>
				<div id="top">
					<h1>Sign in to Ticket System v0.2</h1>
				</div>
				<span class="descText">New astonishing ticket system</span>
				<div class="mainContent">
					<div class="innerContent">
						<p class="note">Use <strong>admin</strong> for the username and the password to sign in as <strong>admin</strong>.</p>
						<p class="note">Use <strong>user</strong> for the username and the password to sign in as <strong>user</strong>.</p>
						<p class="note">Use <strong>newuser</strong> for the username and the password to sign in as <strong>newuser</strong>.</p>
						<form action="" method="post" id="signIn">
							<div>
								<label for="username">Username:</label>
								<input type="text" name="username" id="username" class="textInput" value="" size="30" />
							</div>
							<div>
								<label for="userpass">Password:</label>
								<input type="password" name="userpass" id="userpass" class="textInput" value="" size="30" />
							</div>
							<div id="buttons">
								<input type="submit" name="loginFormSubmit" id="loginFormSubmit" class="formSubmit" value="Sign in" />
								<input type="submit" name="registerFormSubmit" id="registerFormSubmit" class="formSubmit" value="Join" />
							</div>
						</form>
					</div>
				</div>