<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Ticket System - Dashbord</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="/public/css/style.css" media="screen" />
	</head>
	<body>
		<div id="container">
			<div id="innerContainer">
				<div id="top">
					<h1>Ticket System</h1>
					<span class="topRight">Hi Eugene!</span>
				</div>
				<ul id="nav">
					<li>
						<a href="#">Tickets</a>
					</li>
					<li>
						<a href="#">New ticket</a>
					</li>
					<li class="navStandout">
						<a href="#">Sign out</a>
					</li>
				</ul>
				<h2 class="subHead">
					<span class="subTitle">Your tickets</span>
				</h2>
				<ul class="list">
					<li class="key">
						<p class="elem1">Date</p>
						<p class="elem2">Category</p>
						<p class="elem3">Subject</p>
						<p class="elem4">Status</p>
						<p class="elem5">Priority</p>
					</li>
					<li>
						<p class="elem1">08/03/2010</p>
						<p class="elem2">Support</p>
						<p class="elem3">
							<a href="#">Testing 1</a>
						</p>
						<p class="elem4">Opened</p>
						<p class="elem5">Medium</p>
					</li>
					<li>
						<p class="elem1">14/02/2010</p>
						<p class="elem2">Support</p>
						<p class="elem3">
							<a href="#">Testing 2</a>
						</p>
						<p class="elem4">Closed</p>
						<p class="elem5">Medium</p>
					</li>
					<li>
						<p class="elem1">02/01/2010</p>
						<p class="elem2">Support</p>
						<p class="elem3">
							<a href="#">Testing 3</a>
						</p>
						<p class="elem4">Opened</p>
						<p class="elem5">Low</p>
					</li>
					<li>
						<p class="elem1">03/12/2009</p>
						<p class="elem2">Support</p>
						<p class="elem3">
							<a href="#">Testing 4</a>
						</p>
						<p class="elem4">Closed</p>
						<p class="elem5">Medium</p>
					</li>
					<li>
						<p class="elem1">26/01/2010</p>
						<p class="elem2">Support</p>
						<p class="elem3">
							<a href="#">Testing 5</a>
						</p>
						<p class="elem4">Opened</p>
						<p class="elem5">High</p>
					</li>
				</ul>
				<div class="pagination">
					PAGE&nbsp;<span class="current">1</span><a href="#">2</a><a href="#">3</a>
				</div>
			</div>
		</div>
		<div id="footer">
			<div id="innerFooter">
				<div>
					<span class="left">&copy;&nbsp;2010&nbsp;Ticket System</span>
				</div>
				<div>
					<span class="right">Script by <a href="#">Eugene Serkin</a></span>
				</div>
			</div>
		</div>
		<script type="text/javascript" src="http://www.google.com/jsapi" charset="utf-8"></script>
		<script type="text/javascript" charset="utf-8">
			google.load("jquery", "1.4.2");
		</script>
		<script type="text/javascript">
			$(document).ready(function() {
				
			});	
		</script>
	</body>
</html>