<?php

# Check was this fil linked directly
if(!defined('SYSPATH')) exit('No direct script access allowed!');

/**
 * Ticket System
 * 
 * Non-commercial application.
 * 
 * @package			Ticket System
 * @author			Eugene Serkin
 * @copyright		Copyright (c) 2010, Art-Coder
 * @license			http://#
 * @link			http://art-coder.com
 * @since			Version 0.2
 */

//------------------------------------------------

/**
 * Ticket class
 *
 * Main class for handling tickets.
 * 
 * @package			Ticket System
 * @subpackage		Libraries
 * @category		Libraries
 * @author			Eugene Serkin
 * @link			http://art-coder.com
 */

class Ticket {
	
	/**
	 * Database instance.
	 * @var MySQLDatabase
	 */
	private $db;
	
	/**
	 * Validation instance.
	 * @var Validation
	 */
	private $validator;
	
	/**
	 * Constructor
	 *
	 * @access	public
	 * @param	MySQLDatabase	Database instance.
	 * @param	Validation	Validation instance.
	 */
	public function __construct(MySQLDatabase $db, Validation $validator) {
		# Create link for Database
		$this->db = $db;
		# Create link for Validation
		$this->validator = $validator;
	}
	
	/**
	 * Get all available priorities.
	 *
	 * @access	public
	 * @return	array
	 */
	public function displayPriorities() {
		$allPrior = $this->db->query("SELECT * FROM ts_ticket_priority ORDER BY id");
		$keys = array();
		$values = array();
		
		while($priorities = $this->db->fetchAssoc($allPrior)) {
			//array_push($keys, (int)$priorities['id']);
			//array_push($value, $priorities['priority_name']);
			$keys[] = (int)$priorities['id'];
			$values[] = $priorities['priority_name'];
		}
		
		return array_combine($keys, $values);
	}
	
	/**
	 * Get all available categories.
	 *
	 * @access	public
	 * @return	array
	 */
	public function displayCategories() {
		$allCats = $this->db->query("SELECT * FROM ts_ticket_category ORDER BY id");
		$keys = array();
		$values = array();
		
		while($categories = $this->db->fetchAssoc($allCats)) {
			//array_push($keys, (int)$categories['id']);
			//array_push($values, $categories['category_name']);
			$keys[] = (int)$categories['id'];
			$values[] = $categories['category_name'];
		}
		
		return array_combine($keys, $values);
	}
	
	/**
	 * Add new ticket into database.
	 *
	 * @access	public
	 * @param	int	Selected urgency.
	 * @param	int	Selected category.
	 * @param	string	Subject of the ticket.
	 * @param	string	Content of the ticket.
	 */
	public function addTicket($urgency, $services, $subject = "", $content = "") {
		if($this->validator->required($urgency, $services, $subject, $content)) {
			$query1 = $this->chkTicketExistance("table", "ts_ticket_topic", "subject", $subject);
			$query2 = $this->chkTicketExistance("table", "ts_ticket_topic", "content", $content);
			if(!$query1 && !$query2) {
				// Later user method whoIsFromStaff
				$this->db->query("
					INSERT INTO ts_ticket_topic(
						id,
						author_id,
						recepient_id,
						subject,
						date_time,
						category_id,
						priority_id,
						status_id,
						content,
						user_ip
					) VALUES(
						NULL,
						".$this->db->escapeVal($_SESSION['id']).",
						1,
						'".$this->db->escapeVal($subject)."',
						NOW(),
						".$this->db->escapeVal($services).",
						".$this->db->escapeVal($urgency).",
						1,
						'".$this->db->escapeVal($this->validator->eliminateTags($content))."',
						'".$this->db->escapeVal($_SERVER['REMOTE_ADDR'])."'
					)
				");
			}
		}
		
		header("Location: / ");
	}
	
	/**
	 * Add reply to specified ticket.
	 *
	 * @access	public
	 * @param	int	Ticket id.
	 * @param	int	ID of the respondent.
	 * @param	string	Content of the reply message.
	 */
	public function replyToTicket($ticket_id, $respondent, $reply_content) {
		if($this->chkTicketExistance("table", "ts_ticket_topic", "id", $ticket_id)) {
			$this->changeTicketStatus($ticket_id, 1, false);
		}
		
		if($this->validator->required($reply_content)) {
			$this->db->query("
				INSERT INTO ts_ticket_reply(
					id,
					ticket_id,
					resp_id,
					date_time,
					content
				) VALUES (
					NULL,
					".$this->db->escapeVal($ticket_id).",
					".$this->db->escapeVal($respondent).",
					NOW(),
					'".$this->db->escapeVal($this->validator->eliminateTags($reply_content))."'
				)
			");
		}
	}
	
	/**
	 * Method for checking the existance of the ticket.
	 *
	 * @access	public
	 * @param	string	The type of the search request.
	 * @param	string	Search request itself.
	 * @return	int
	 */
	public function chkTicketExistance($type, $tbl_query) {
		$argsArray = func_get_args();
		switch($type) {
			case "query":
				$result = $this->db->numRows($tbl_query);
				break;
			case "table":
				$countArgs = func_num_args() - 2;
				$query = "SELECT * FROM ".$this->db->escapeVal($tbl_query)." ";
				if(fmod($countArgs, 2) == 0) {
					$pairsCount = $countArgs / 2;
					if($pairsCount == 1) {
						$value = (gettype($argsArray[3]) == 'string') ? "'".$argsArray[3]."'" : $argsArray[3];
						$whereClause = $argsArray[2]." = ".$value;
					} else {
						$i = 2;
						$pair = 1;
						while($i <= $countArgs) {
							$whereClause .= $argsArray[$i]." = ";
							$i++;
							$value = (gettype($argsArray[$i]) == 'string') ? "'".$argsArray[$i]."'" : $argsArray[$i];
							$whereClause .= $value;
							if($pair != $pairsCount) $whereClause .= " AND ";
							$pair++;
							$i++;
						}
					}
				} else {
					return false;
				}
				$query .= "WHERE ".$whereClause;
				$fquery = $this->db->query($query);
				$result = $this->db->numRows($fquery);
				break;
		}
		
		return $result;
	}
	
	/**
	 * Check status of the ticket
	 *
	 * @access	public
	 * @param	int	ID of the ticket.
	 * @return	bool
	 */
	public function chkTicketStatus($ticket_id) {
		# Status 1 - Opened, 2 - Closed
		$query = $this->db->query("SELECT status_id FROM ts_ticket_topic WHERE id = ".$this->db->escapeVal($ticket_id)."");
		$chkTicketStatus = $this->db->fetchAssoc($query);
		
		if($chkTicketStatus['status_id'] == 2) return false;
		else return true;
	}
	
	/**
	 * Method for changing status of the ticket.
	 *
	 * @access	public
	 * @param	int	ID of the ticket.
	 * @param	int	Status of the ticket.
	 * @param	bool	Set redirect or not. By default redirects.
	 */
	public function changeTicketStatus($ticket_id, $status, $redirect = true) {
		$this->chkTicketStatus($ticket_id);
		$this->db->query("UPDATE ts_ticket_topic SET status_id = ".$this->db->escapeVal($status)." WHERE id = ".$this->db->escapeVal($ticket_id)."");
		if($redirect) header("Location: / ");
	}
	
	/**
	 * Receives ticket topics. Amount depends on user rights.
	 *
	 * @access	public
	 * @param	bool	Sets whether user has admin rights or not.
	 * @param	int	ID of the ticket.
	 * @param	int ID of the author of the ticket.
	 * @return	bool | array
	 */
	public function getTicketTopic($is_admin, $ticket_id, $author_id = '') {
		if($is_admin) {
			$query = $this->db->query("SELECT * FROM ts_tickets_view WHERE id = ".$this->db->escapeVal($ticket_id)."");
		} else {
			$query = $this->db->query("SELECT * FROM ts_tickets_view WHERE id = ".$this->db->escapeVal($ticket_id)." AND author_id = ".$this->db->escapeVal($author_id)."");
		}
		
		if($this->chkTicketExistance("query", $query) == 0) return false;
		
		$result = $this->db->fetchAssoc($query);
		return $result;
	}
	
	/**
	 * Method checks for any replies for specified ticket.
	 *
	 * @access	public
	 * @param	int	ID of the ticket.
	 * @return	bool
	 */
	public function chkForReplies($ticket_id) {
		$query = $this->db->query("SELECT COUNT(1) AS total FROM ts_replies_view WHERE ticket_id = ".$this->db->escapeVal($ticket_id)."");
		$result = $this->db->fetchAssoc($query);
		
		if((int)$result['total'] == 0) return false;
		else return true;
	}
	
	/**
	 * Get all replies for specified ticket.
	 *
	 * @access	public
	 * @param	int	ID of the ticket.
	 * @return	resource
	 */
	public function getTicketReplies($ticket_id) {
		$query = $this->db->query("SELECT * FROM ts_replies_view WHERE ticket_id = ".$this->db->escapeVal($ticket_id)."");
		return $query;
	}
}
//	END Ticket Class

/* End of file Ticket.php */
/* Location: ./application/libraries/Ticket.php */
?>