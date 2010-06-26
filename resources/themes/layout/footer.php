			</div>
		</div>
		<div id="footer">
			<div id="innerFooter">
				<div>
					<span class="left">&copy;&nbsp;2010&nbsp;Ticket System&nbsp;<strong>(</strong>build&nbsp;<a href="<?php echo $config['base_url'].'/license/'; ?>">120610.1752</a><strong>)</strong></span>
				</div>
				<div>
					<span class="right">Script by <a href="<?php echo $config['base_url']; ?>">Eugene Serkin</a></span>
				</div>
			</div>
		</div>
		<script type="text/javascript" src="http://www.google.com/jsapi" charset="utf-8"></script>
		<script type="text/javascript" charset="utf-8">
			google.load("jquery", "1.4.2");
		</script>
		<script type="text/javascript">
			$(document).ready(function() {
				$('a#myadmin').click(function() {
					window.open($(this).attr('href'));
					return false;
				});
			 	/* OR $('a#myadmin').attr('tagret', '_blank'); */
			 	
			 	$('#oi').click(function() {
			 		var clicked = $('div.optional').attr('display');
			 		if(clicked == "block") {
			 			$('div.optional').fadeOut('slow');
			 		} else {
			 			$('div.optional').fadeIn('slow');
		 			}
		 			document.write(clicked);
			 		return false;
			 	});
			});
		</script>
	</body>
</html>
<?php
	# Closes connection to server and currently opened DB.
	$db->closeConnection();
?>