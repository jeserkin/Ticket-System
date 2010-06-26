<?php
# Check was this file linked directly
if(!defined('BASEPATH')) exit('No direct script access allowed');
?>
				<div id="top">
					<h1>Ticket System</h1>
					<span class="topRight">Hi <?php echo $_SESSION['username']; ?>!</span>
				</div>
				<ul id="nav">
					<li>
						<a href="/dashboard/">Tickets</a>
					</li>
					<?php if(!$umanager->isAdmin()) { ?>
					<li>
						<a href="./newticket/">New ticket</a>
					</li>
					<?php } ?>
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
					<span class="subTitle"><?php if(!$umanager->isAdmin()) { ?>Your tickets<?php } else { ?>Waiting for response<?php } ?></span>
				</h2>
				<?php
					$result = $page->paginate(@$_REQUEST['page']);
					if($page->pageExists()) {
				?>
				<ul class="list">
					<li class="key">
						<p class="elem1">Date</p>
						<p class="elem2">Category</p>
						<p class="elem3">Subject</p>
						<p class="elem4">Status</p>
						<p class="elem5">Priority</p>
					</li>
					<?php
							while($row = $db->fetchAssoc($result)) {
					?>
					<li>
						<p class="elem1"><?php echo substr($row['date_time'], 0, 10); ?></p>
						<p class="elem2"><?php echo $row['category_name']; ?></p>
						<p class="elem3">
							<a href="./viewticket/<?php echo $row['id']; ?>"><?php echo $row['subject']; ?></a>
						</p>
						<p class="elem4"><?php echo $row['status_name']; ?></p>
						<p class="elem5"><?php echo $row['priority_name']; ?></p>
					</li>
					<?php
							}
					?>
				</ul>
					<?php if($page->getNumPages() > 1) { ?>
				<div class="pagination">
					<ul>
						<li <?php if($page->getCurPage() == 1) echo "class=\"dis\""; ?>>
							<?php if($page->getCurPage() == 1) { ?>
								<span>Previous</span>
							<?php } else { ?>
								<a href="/dashboard/<?php echo $page->setNextPreviousPage(); ?>">Previous</a>
							<?php } ?>
						</li>
						<?php for($i = 1; $i <= $page->getNumPages(); $i++) { ?>
						<li <?php if($page->getCurPage() == $i) echo "class=\"dis\""; ?>>
							<?php if($page->getCurPage() == $i) { ?>
								<span><?php echo $i; ?></span>
							<?php } else { ?>
								<a href="/dashboard/<?php echo $i; ?>"><?php echo $i; ?></a>
							<?php } ?>
						</li>
						<?php } ?>
						<li <?php if($page->getCurPage() == $page->getNumPages()) echo "class=\"dis\""; ?>>
							<?php if($page->getCurPage() == $page->getNumPages()) { ?>
								<span>Next</span>
							<?php } else { ?>
								<a href="/dashboard/<?php echo $page->setNextPreviousPage(true); ?>">Next</a>
							<?php } ?>
						</li>
					</ul>
				</div>
					<?php } ?>
				<?php } else { ?>
				<span>You don't have any tickets or page doesn't exist!</span>
				<?php } ?>