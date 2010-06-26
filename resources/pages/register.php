<?php
# Check was this file linked directly
if(!defined('BASEPATH')) exit('No direct script access allowed');
?>
				<div id="top">
					<h1>Join the system</h1>
				</div>
				<span class="descText">Please fill in the registration form</span>
				<div class="mainContent">
					<div class="innerContent">
						<form action="" method="post" id="signUp">
							<div class="required">
								<div>
									<label for="usernameReg">Username:</label>
									<input type="text" name="usernameReg" id="usernameReg" class="textInput" value="" size="30" />
								</div>
								<div>
									<label for="password1">Password:</label>
									<input type="password" name="password1" id="password1" class="textInput" value="" size="30" />
								</div>
								<div>
									<label for="password2">Confirm password:</label>
									<input type="password" name="password2" id="password2" class="textInput" value="" size="30" />
								</div>
								<div>
									<label for="email">E-mail:</label>
									<input type="text" name="email" id="email" class="textInput" value="" size="30" />
								</div>
								<!--
								<div id="oi">
									<p style="margin: 5px 0; font-weight: bold; font-size: 13px;">Optional Information</p>
								</div>
								-->
							</div>
							<!--
							<div class="optional">
								<div>
									<label for="fname">First Name:</label>
									<input type="text" name="fname" id="fname" class="textInput" value="" size="30" />
								</div>
								<div>
									<label for="lname">Last Name:</label>
									<input type="text" name="lname" id="lname" class="textInput" value="" size="30" />
								</div>
							</div>
							-->
							<div id="buttons">
								<input type="submit" name="register" id="register" class="formSubmit" value="Sign up" />
							</div>
						</form>
					</div>
				</div>