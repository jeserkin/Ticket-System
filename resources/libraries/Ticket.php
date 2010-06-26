<?php

# Check was this file linked directly
if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Ticket System
 * 
 * Commercial application for PHP 4.3.2 or newer
 * 
 * @package			TicketSystem
 * @author			Eugene Serkin
 * @copyright		Copyright (c) 2010, Art-Coder
 * @license			http://localhost/license/
 * @link			http://art-coder.com
 * @since			Version 0.2
 * @filesource		
 */

//-----------------------------------------------

/**
 * Ticket Class
 * 
 * @package			TicketSystem
 * @subpackage		Libraries
 * @category		Libraries
 * @author			Eugene Serkin
 * @link			http://art-coder.com
 */

class Ticket {
	
	/**
	 * Database instance.
	 * @var	MySQLDatabase
	 */
	private $db;
	
	/**
	 * Validation instance.
	 * @var	Validation
	 */
	private $validator;
	
	/**
	 * Constructor.
	 * 
	 * @access	public
	 * @param	MySQLDatabase		Database instance.
	 * @param	Validation		Validation instance.
	 */
	public function __construct(MySQLDatabase $db, Validation $validator) {
		$this->db = $db;
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
			array_push($keys, (int)$priorities['id']);
			array_push($values, $priorities['priority_name']);
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
			array_push($keys, (int)$categories['id']);
			array_push($values, $categories['category_name']);
		}
		
		return array_combine($keys, $values);
	}
	
	/**
	 * Add tikcet into database.
	 * 
	 * @access	public
	 * @param	int		Selected urgency.
	 * @param	int		Selected category.
	 * @param	string		Subject of the ticket.
	 * @param	string		Content of the ticket.
	 */
	public function addTicket($urgency, $services, $subject = "", $content = "") {
		if($this->validator->required($urgency, $services, $subject, $content)) {
			$query1 = $this->checkTicketExistance("table", "ts_ticket_topic", "subject", $subject);
			$query2 = $this->checkTicketExistance("table", "ts_ticket_topic", "content", $content);
			if(!$query1 && !$query2) {
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
						{$_SESSION['id']},
						1,
						'".$this->db->escapeValue($subject)."',
						NOW(),
						{$services},
						{$urgency},
						1,
						'".$this->db->escapeValue($this->validator->eliminateTags($content))."',
						'{$_SERVER['REMOTE_ADDR']}'
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
	 * @param	int		Ticket id.
	 * @param	int		ID of the respondent.
	 * @param	string		Content of the reply message.
	 */
	public function replyToTicket($ticketId, $respondent, $replyCont) {
		if($this->checkTicketExistance("table", "ts_ticket_topic", "id", $ticketId))
			$this->changeTicketStatus($ticketId, 1, false);
			
		if($this->validator->required($replyCont)) {
			$this->db->query("
				INSERT INTO ts_ticket_reply(
					id,
					ticket_id,
					resp_id,
					date_time,
					content
				) VALUES(
					NULL,
					".$this->db->escapeValue($ticketId).",
					".$this->db->escapeValue($respondent).",
					NOW(),
					'".$this->db->escapeValue($this->validator->eliminateTags($replyCont))."'
				)
			");
		}
	}
	
	/**
	 * 
	 */
	 //TODO:Maybe make this method to check all kinds of stuff. For example not only row count, but also check status for example.
	public function checkTicketExistance($type, $tbl_query) {
		$argsArray = func_get_args();
		switch($type) {
			case "query":
				$result = $this->db->numRows($tbl_query);
				break;
			case "table":
				$countArgs = func_num_args() - 2;
				$argsArray = func_get_args();
				$query = "SELECT * FROM ".$this->db->escapeValue($tbl_query)." ";
				if(fmod($countArgs, 2) == 0) {
					$pairsCount = $countArgs / 2;
					if($pairsCount == 1) {
						$value = (gettype($argsArray[3]) == "string") ? "'".$argsArray[3]."'" : $argsArray[3];
						$whereClause = $argsArray[2]." = ".$value;
					} else {
						$i = 2;
						$pair = 1;
						while($i <= $countArgs) {	
							@$whereClause .= $argsArray[$i]." = ";
							$i++;
							$value = (gettype($argsArray[$i]) == "string") ? "'".$argsArray[$i]."'" : $argsArray[$i];
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
	 * Checks status of the ticket
	 * 
	 * @access	public
	 * @param	int		ID of the ticket
	 * @return	bool
	 */
	public function checkTicketStatus($ticketId) {
		# Status 1 - Opened, 2 - Closed
		$query = $this->db->query("SELECT status_id FROM ts_ticket_topic WHERE id = ".$this->db->escapeValue($ticketId));
		$checkTicketStatus = $this->db->fetchAssoc($query);
		
		if($checkTicketStatus['status_id'] == 2) return false;
		else return true;
	}
	 
	 /**
	 * Changes status of the ticket
	 * If needed, redirects
	 * 
	 * @access	public
	 * @param	int		ID of the ticket
	 * @param	int		Status of the ticket
	 * @param	bool	Set redirect or not. By default redirects.
	 */
	public function changeTicketStatus($ticketId, $status, $redirect = true) {
		# Status 1 - Opened, 2 - Closed
		$this->checkTicketStatus($ticketId);
		$this->db->query("UPDATE ts_ticket_topic SET status_id = ".$this->db->escapeValue($status)." WHERE id = ".$this->db->escapeValue($ticketId));
		if($redirect) header("Location: / ");
	}
	
	/**
	 * 
	 */
	public function getTicketTopic($isAdmin, $ticketId, $authorId = "") {
		if($isAdmin) {
			$query = $this->db->query("SELECT * FROM ts_tickets_view WHERE id = ".$this->db->escapeValue($ticketId));
		} else {
			$query = $this->db->query("SELECT * FROM ts_tickets_view WHERE id = ".$this->db->escapeValue($ticketId)." AND author_id = ".$this->db->escapeValue($authorId));
		}
		
		if($this->checkTicketExistance("query", $query) == 0) return false;
		
		$result = $this->db->fetchAssoc($query);
		return $result;
	}
	
	/**
	 * Checks are there any replies for ticket
	 * 
	 * @access	public
	 * @param	int		ID of the ticket
	 * @return	bool
	 */
	public function checkForReplies($ticketId) {
		$query = $this->db->query("SELECT COUNT(1) AS total FROM ts_replies_view WHERE ticket_id = ".$this->db->escapeValue($ticketId));
		$result = $this->db->fetchAssoc($query);
		
		if((int)$result['total'] < 1) return false;
		else return true;
	}
	
	/**
	 * 
	 */
	 public function getTicketReplies($ticketId) {
	 	$query = $this->db->query("SELECT * FROM ts_replies_view WHERE ticket_id = ".$this->db->escapeValue($ticketId));
	 	return $query;
	 }
}
//	END Ticket

/* End of file Ticket.php */
/* Location: ./resources/libraries/Ticket.php */
?>