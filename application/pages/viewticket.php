<?php

# Check was this fil linked directly
if(!defined('SYSPATH')) exit('No direct script access allowed!');

if(@$_REQUEST['ticket'] == '') {
	echo "Error.";
} else {
	if(isset($_POST['tAnswer'])) {
		$ticket->replyToTicket($_REQUEST['ticket'], $_SESSION['id'], $_POST['answerCont']);
	}
	
	$ttopic = $ticket->getTicketTopic($umanager->isAdmin(), $_REQUEST['ticket'], $_SESSION['id']);
	if($ttopic == false) {
		echo "Error.";
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
						<a href="../newticket/">New ticket</a>
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
					<span class="subTitle">Ticket #<?php echo $ttopic['id']; ?></span>
				</h2>
				<div class="mainContent">
					<div class="innerContent">
						<ul class="list">
							<li class="key">
								<p class="elem1 strong">Date</p>
								<p class="elem2 strong">Category</p>
								<p class="elem3 strong">Subject</p>
								<p class="elem4 strong">Status</p>
								<p class="elem5 strong">Priority</p>
							</li>
							<li>
								<p class="elem1"><?php echo substr($ttopic['date_time'], 0, 10); ?></p>
								<p class="elem2"><?php echo $ttopic['category_name']; ?></p>
								<p class="elem3"><?php echo $ttopic['subject']; ?></p>
								<p class="elem4"><?php echo $ttopic['status_name']; ?></p>
								<p class="elem5"><?php echo $ttopic['priority_name']; ?></p>
							</li>
						</ul>
					</div>
				</div>
				<div class="ticketMessage">
					<div class="innerMessage">
						<div class="messageHead">
							<div class="userPart">
								<ul>
									<li class="userName"><?php echo $ttopic['username']; ?></li>
									<li><?php echo $ttopic['ugroup_name']; ?></li>
								</ul>
							</div>
							<div class="datePart">
								<p><?php echo substr($ttopic['date_time'], 0, 16); ?></p>
							</div>
						</div>
						<div class="messageContent">
							<?php echo $ttopic['content']; ?>
							<br />
							<span>----------------------------</span>
							<br />
							<?php echo $ttopic['user_ip']; ?>
						</div>
					</div>
				</div>
				<?php
					if($ticket->chkForReplies($_REQUEST['ticket'])) {
						$result = $ticket->getTicketReplies($_REQUEST['ticket']);
						while(($treplies = $db->fetchAssoc($result)) != NULL) {
							if($treplies['ugroup'] == 1) {
								$border = "adminBorder";
								$head = "adminHead";
								$cont = "adminCont";
							} else {
								$border = $head = $cont = "";
							}
				?>
				<div class="ticketAnswers <?php echo $border." ".$cont; ?>">
					<div class="innerAnswers">
						<div class="answersHead <?php echo $head; ?>">
							<div class="respondentPart">
								<ul>
									<li class="respondentName"><?php echo $treplies['username']; ?></li>
									<li><?php echo $treplies['ugroup_name']; ?></li>
								</ul>
							</div>
							<div class="datePart">
								<p><?php echo substr($treplies['date_time'], 0, 16); ?></p>
							</div>
						</div>
						<div class="answersContent">
							<?php echo $treplies['content']; ?>
						</div>
					</div>
				</div>
				<?php
						}
					}
				?>
				<?php if($ticket->chkTicketStatus($_REQUEST['ticket'])) { ?>
				<div id="tClose">
					<form action="" method="post">
						<input type="submit" name="ticketClose" id="ticketClose" value="Close ticket" />
					</form>
				</div>
				<?php } ?>
				<h2 class="subHead">
					<span class="subTitle">Reply</span>
				</h2>
				<div class="ticketReply">
					<div class="innerReply">
						<form action="" method="post">
							<div class="whoReply">
								<label for="whoName">Name:</label>
								<span id="whoName"><?php echo $_SESSION['username']; ?></span>
							</div>
							<div class="whoEmail">
								<label for="whoMail"></label>
								<span id="whoMail"><?php echo $_SESSION['email']; ?></span>
							</div>
							<div class="answer">
								<textarea name="answerCont" id="answerCont" cols="" rows="12"></textarea>
							</div>
							<div class="submitAnswer">
								<input type="submit" name="tAnswer" id="tAnswer" value="Answer" />
							</div>
						</form>
					</div>
				</div>
<?php
	}
}
?>