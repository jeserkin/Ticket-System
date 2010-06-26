<?php
	//echo realpath(dirname(__FILE__));
?>
				<div id="top">
					<h1>Testing Area</h1>
				</div>
				<div class="mainContent">
					<div class="innerContent">
						<p class="spaceLine">
							<?php
								# Will only exist after Sign In
								echo (double)PHP_VERSION;
							?>
						</p>
						<p class="spaceLine">
							<?php
								$from = "jevgeni.serkin@gmail.com,admin@admin.com";
								function _str_to_array($email) {
									if(!is_array($email)) {
										if(strpos($email, ',') !== FALSE) {
											$email = preg_split('/[\s,]/', $email, -1, PREG_SPLIT_NO_EMPTY);
										} else {
											$email = trim($email);
											settype($email, "array");
										}
									}
									return $email;
								}
								$temp = _str_to_array($from);
								var_dump($temp);
								echo "<br /><br />";
							?>
						</p>
						<!-- -->
						<p class="testification"><?=@$_SERVER['argv']?> - <strong>argv</strong></p>
						<p class="testification"><?=@$_SERVER['argc']?> - <strong>argc</strong></p>
						<p class="testification"><?=@$_SERVER['GATEWAY_INTERFACE']?> - <strong>GATEWAY_INTERFACE</strong></p>
						<p class="testification"><?=@$_SERVER['SERVER_ADDR']?> - <strong>SERVER_ADDR</strong></p>
						<p class="testification"><?=@$_SERVER['SERVER_NAME']?> - <strong>SERVER_NAME</strong></p>
						<p class="testification"><?=@$_SERVER['SERVER_SOFTWARE']?> - <strong>SERVER_SOFTWARE</strong></p>
						<p class="testification"><?=@$_SERVER['SERVER_PROTOCOL']?> - <strong>SERVER_PROTOCOL</strong></p>
						<p class="testification"><?=@$_SERVER['REQUEST_METHOD']?> - <strong>REQUEST_METHOD</strong></p>
						<p class="testification"><?=@$_SERVER['REQUEST_TIME']?> - <strong>REQUEST_TIME</strong></p>
						<p class="testification"><?=@$_SERVER['QUERY_STRING']?> - <strong>QUERY_STRING</strong></p>
						<p class="testification"><?=@$_SERVER['DOCUMENT_ROOT']?> - <strong>DOCUMENT_ROOT</strong></p>
						<p class="testification"><?=@$_SERVER['HTTP_ACCEPT']?> - <strong>HTTP_ACCEPT</strong></p>
						<p class="testification"><?=@$_SERVER['HTTP_ACCEPT_CHARSET']?> - <strong>HTTP_ACCEPT_CHARSET</strong></p>
						<p class="testification"><?=@$_SERVER['HTTP_ACCEPT_ENCODING']?> - <strong>HTTP_ACCEPT_ENCODING</strong></p>
						<p class="testification"><?=@$_SERVER['HTTP_ACCEPT_LANGUAGE']?> - <strong>HTTP_ACCEPT_LANGUAGE</strong></p>
						<p class="testification"><?=@$_SERVER['HTTP_CONNECTION']?> - <strong>HTTP_CONNECTION</strong></p>
						<p class="testification"><?=@$_SERVER['HTTP_HOST']?> - <strong>HTTP_HOST</strong></p>
						<p class="testification"><?=@$_SERVER['HTTP_REFERER']?> - <strong>HTTP_REFERER</strong></p>
						<p class="testification"><?=@$_SERVER['HTTP_USER_AGENT']?> - <strong>HTTP_USER_AGENT</strong></p>
						<p class="testification"><?=@$_SERVER['HTTPS']?> - <strong>HTTPS</strong></p>
						<p class="testification"><?=@$_SERVER['REMOTE_ADDR']?> - <strong>REMOTE_ADDR</strong></p>
						<p class="testification"><?=@$_SERVER['REMOTE_HOST']?> - <strong>REMOTE_HOST</strong></p>
						<p class="testification"><?=@$_SERVER['REMOTE_PORT']?> - <strong>REMOTE_PORT</strong></p>
						<p class="testification"><?=@$_SERVER['SCRIPT_FILENAME']?> - <strong>SCRIPT_FILENAME</strong></p>
						<p class="testification"><?=@$_SERVER['SERVER_ADMIN']?> - <strong>SERVER_ADMIN</strong></p>
						<p class="testification"><?=@$_SERVER['SERVER_PORT']?> - <strong>SERVER_PORT</strong></p>
						<p class="testification"><?=@$_SERVER['SERVER_SIGNATURE']?> - <strong>SERVER_SIGNATURE</strong></p>
						<p class="testification"><?=@$_SERVER['PATH_TRANSLATED']?> - <strong>PATH_TRANSLATED</strong></p>
						<p class="testification"><?=@$_SERVER['SCRIPT_NAME']?> - <strong>SCRIPT_NAME</strong></p>
						<p class="testification"><?=@$_SERVER['REQUEST_URI']?> - <strong>REQUEST_URI</strong></p>
						<p class="testification"><?=@$_SERVER['PHP_AUTH_DIGEST']?> - <strong>PHP_AUTH_DIGEST</strong></p>
						<p class="testification"><?=@$_SERVER['PHP_AUTH_USER']?> - <strong>PHP_AUTH_USER</strong></p>
						<p class="testification"><?=@$_SERVER['PHP_AUTH_PW']?> - <strong>PHP_AUTH_PW</strong></p>
						<p class="testification"><?=@$_SERVER['AUTH_TYPE']?> - <strong>AUTH_TYPE</strong></p>
						<!-- -->
					</div>
				</div>
<?php
	
?>