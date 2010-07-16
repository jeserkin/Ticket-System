<?php

# Check was this fil linked directly
if(!defined('SYSPATH')) exit('No direct script access allowed!');

?>
			</div>
		</div>
		<div id="footer">
			<div id="innerFooter">
				<div>
					<span class="left">&copy; 2010 Powered by Ticket System <strong>(</strong>buid <a href="<?php echo $appData['base_url'].'/license/'; ?>">030710.2211</a><strong>)</strong></span>
				</div>
				<div>
					<span class="right">Script by <a href="<?php echo $appData['base_url']; ?>">Eugene Serkin</a></span>
				</div>
			</div>
		</div>
		<script type="text/javascript" src="http://www.google.com/jsapi" charset="utf-8"></script>
		<script type="text/javascript">
			google.load("jquery", "1.4.2");
			google.load("mootools", "1.2.4");
		</script>
		<script type="text/javascript">
			$(document).ready(function() {
				$('a#myadmin').click(function() {
					window.open($(this).attr('href'));
					return false;
				});
				/* OR $('a#myadmin').attr('target', '_blank'); */
			});
			
			var err = new Fx.Tween('error', {
				link: 'chain',
				property: 'opacity',
				duration: 1000
			});
			(function() {
				err.start(0.5).start(1);
			}).periodical(1000);
		</script>