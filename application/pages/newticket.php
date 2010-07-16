<?php

# Check was this fil linked directly
if(!defined('SYSPATH')) exit('No direct script access allowed!');

if($umanager->isAdmin()) {
	echo "Error. Admin doesn't need to create tickets.";
} else {
?>
				<div id="top">
					<h1><?php echo $appData['appname']; ?></h1>
					<span class="topRight">Hi <?php echo $_SESSION['username']; ?>!</span>
				</div>
				<ul id="nav">
					<li>
						<a href="/dashboard/">Tickets</a>
					</li>
					<?php if(!$umanager->isAdmin()) { ?>
					<li>
						<a href="./">New ticket</a>
					</li>
					<?php } ?>
					<!-- Access to current DB through phpMyAdmin -->
					<li>
						<a href="/phpmyadmin/index.php?db=ticket_system" id="myadmin"><strong>phpMyAdmin</strong></a>
					</li>
					<!-- Access to current DB through phpMyAdmin END -->
					<li class="navStandout">
						<a href="<?php echo $appData['base_url'].'/sign_out/'; ?>">Sign out</a>
					</li>
				</ul>
				<h2 class="subHead">
					<span class="subTitle">Create new ticket</span>
				</h2>
				<div class="mainContent">
					<div class="innerContent">
						<form action="" method="post">
							<div id="ticketForm">
								<div id="ticketTopic">
									<label for="ticketSubject">Ticket subject:</label>
									<input type="text" name="ticketSubject" id="ticketSubject" value="" />
								</div>
								<div id="ticketPriority">
									<label for="ticketUrgency">Urgency:</label>
									<select name="ticketUrgency" id="ticketUrgency">
									<?php foreach($ticket->displayPriorities() as $key=>$value) { ?>
										<option value="<?php echo $key; ?>"><?php echo $value; ?></option>
									<?php } ?>
									</select>
								</div>
								<div id="ticketCategory">
									<label for="ticketServices">Related services:</label>
									<select name="ticketServices" id="ticketServices">
									<?php foreach($ticket->displayCategories() as $key=>$value) { ?>
										<option value="<?php echo $key; ?>"><?php echo $value; ?></option>
									<?php } ?>
									</select>
								</div>
								<div id="ticketMain">
									<div id="ticketContent">
										<textarea name="ticket" id="ticket" cols="" rows=""></textarea>
									</div>
								</div>
							</div>
							<div id="ticketSubmit">
								<input type="submit" name="newTicket" id="newTicket" value="Create ticket" />
							</div>
						</form>
					</div>
				</div>
<?php } ?>