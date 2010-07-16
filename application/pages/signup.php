<?php

# Check was this fil linked directly
if(!defined('SYSPATH')) exit('No direct script access allowed!');

?>
				<div id="top">
					<h1>Join the system</h1>
				</div>
				<span class="descText">Please fill in the registration form</span>
				<div class="mainContent">
					<div class="innerContent">
						<form action="" method="post" id="join">
							<div class="required">
								<div>
									<label for="username">User name:</label>
									<input type="text" name="username" id="username" class="textInput" value="" size="30" />
								</div>
								<div>
									<label for="userpass1">Password:</label>
									<input type="password" name="userpass1" id="userpass1" class="textInput" value="" size="30" />
								</div>
								<div>
									<label for="userpass2">Confirm password:</label>
									<input type="password" name="userpass2" id="userpass2" class="textInput" value="" size="30" />
								</div>
								<div>
									<label for="email">E-mail:</label>
									<input type="text" name="email" id="email" class="textInput" value="" size="30" />
								</div>
							</div>
							<div id="buttons">
								<input type="submit" name="signUp" id="signUp" class="formSubmit" value="Sign up" />
							</div>
						</form>
					</div>
				</div>