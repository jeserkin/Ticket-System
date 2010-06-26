<?php
# Check was this file linked directly
if(!defined('BASEPATH')) exit('No direct script access allowed');

if($umanager->isAdmin()) {
	echo "Error. Admin doesn't need to create tickets.";
} else {
?>
				<div id="top">
					<h1>Ticket System</h1>
					<span class="topRight">Hi <?php echo $_SESSION['username']; ?>!</span>
				</div>
				<ul id="nav">
					<li>
						<a href="/dashboard/">Tickets</a>
					</li>
					<li>
						<a href="./">New ticket</a>
					</li>
					<!-- Access to current BD trough phpMyAdmin -->
					<li>
						<a href="/phpmyadmin/index.php?db=ticket_system" id="myadmin"><strong>phpMyAdmin</strong></a>
					</li>
					<!-- Access to current BD trough phpMyAdmin END -->
					<li class="navStandout">
						<a href="<?php echo $config['base_url'] . "/logout/"; ?>">Sign out</a>
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
									<input type="text" name="ticketSubject" id="ticketSubject" />
								</div>
								<div id="ticketPriority">
									<label for="ticketUrgency">Urgency:</label>
									<select name="ticketUrgency" id="ticketUrgency">
									<?php
										foreach($ticket->displayPriorities() as $key=>$value)
											echo "<option value=\"{$key}\">{$value}</option>";
									?>
									</select>
								</div>
								<div id="ticketCategory">
									<label for="ticketServices">Related Services:</label>
									<select name="ticketServices" id="ticketServices">
									<?php
										foreach($ticket->displayCategories() as $key=>$value)
											echo "<option value=\"{$key}\">{$value}</option>";
									?>
									</select>
								</div>
								<div id="ticketMain">
									<!-- <div id="ticketTools"> 
										test2
									</div> -->
									<div id="ticketContent">
										<textarea name="ticket" id="ticket" rows="" cols=""></textarea>
									</div>
								</div>
							</div>
							<div id="ticketSubmit">
								<input type="submit" name="newTicket" id="newTicket" value="Create ticket" />
							</div>
						</form>
					</div>
				</div>
<?php
}
?>