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
 * Pagination class
 * 
 * @package			Ticket System
 * @subpackage		Libraries
 * @category		Libraries
 * @author			Eugene Serkin
 * @link			http://art-coder.com
 */

class Pagination {
	
	/**
	 * Database instance.
	 * @var MySQLDatabase
	 */
	private $db;
	
	/**
	 * User instance.
	 * @var UserManager
	 */
	private $user;
	
	/**
	 * On what page is user.
	 * @var int
	 */
	private $curPage = 0;
	
	/**
	 * Does selected page exist or not.
	 * @var bool
	 */
	private $pageCheck;
	
	/**
	 * From which entry to start?
	 * @var int
	 */
	private $startFrom = 0;
	
	/**
	 * Number of entries per page.
	 * @var int
	 */
	private $entryPerPage = 2;
	
	/**
	 * Entries displayed per selected page.
	 * @var resource
	 */
	private $entriesToDisplay;
	
	/**
	 * Number of entries, returned from database for specified user.
	 * @var int
	 */
	private $totalEntryNum;
	
	/**
	 * Number of pages.
	 * @var int
	 */
	private $numPages;
	
	/**
	 * Previous page.
	 * @var int
	 */
	private $prevPage;
	
	/**
	 * Next page.
	 * @var int
	 */
	private $nextPage;
	
	/**
	 * Constructor
	 *
	 * @access	public
	 * @param	MySQLDatabase	Database instance.
	 * @param	UserManager	User instance.
	 */
	public function __construct(MySQLDatabase $db, UserManager $user) {
		# Create link for Database
		$this->db = $db;
		# Create link for UserManager
		$this->user = $user;
	}
	
	/**
	 * Main method.
	 * Returns resource with selected entries.
	 *
	 * @access	public
	 * @param	int	Select on what page user is currebtly.
	 * @return	resource
	 */
	public function paginate($page_num = '') {
		if(($page_num == '') || ($page_num == 1)) $this->curPage = 1;
		else $this->curPage = $page_num;
		
		$this->pageCheck = $this->chkPageNr($this->curPage);
		
		$this->startFrom = ($this->curPage - 1) * $this->entryPerPage;
		
		if($this->user->ugroup == 1) {
			$this->entriesToDisplay = $this->db->query("SELECT * FROM ts_tickets_view WHERE status_name = 'Opened' LIMIT ".$this->db->escapeVal($this->startFrom).", ".$this->db->escapeVal($this->entryPerPage)."");
		} else {
			$this->entriesToDisplay = $this->db->query("SELECT * FROM ts_tickets_view WHERE author_id = ".$this->db->escapeVal($this->user->id)." LIMIT ".$this->db->escapeVal($this->startFrom).", ".$this->db->escapeVal($this->entryPerPage)."");
		}
		
		return $this->entriesToDisplay;
	}
	
	/**
	 * Checks, is selected page in available range.
	 *
	 * @access	private
	 * @param	int	Selected page number.
	 * @return	bool
	 */
	private function chkPageNr($page_numbeer) {
		if($page_numbeer >= 1) {
			if($page_numbeer <= $this->getNumPages()) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	/**
	 * Calculate the number of pages.
	 *
	 * @access	public
	 * @return	int
	 */
	public function getNumPages() {
		if($this->user->ugroup == 1) {
			$query = $this->db->query("SELECT COUNT(1) AS total FROM ts_tickets_view WHERE status_name = 'Opened'");
			$total = $this->db->fetchAssoc($query);
		} else {
			$query = $this->db->query("SELECT COUNT(1) AS total FROM ts_tickets_view WHERE author_id = ".$this->db->escapeVal($this->user->id)."");
			$total = $this->db->fetchAssoc($query);
		}
		
		$this->totalEntryNum = (int)$total['total'];
		
		$this->numPages = ceil($this->totalEntryNum / $this->entryPerPage);
		
		return $this->numPages;
	}
	
	/**
	 * Checks existance of given page.
	 *
	 * @access	public
	 * @return	bool
	 */
	public function pageExists() {
		return $this->pageCheck;
	}
	
	/**
	 * Returns currently selected page number.
	 *
	 * @access	public
	 * @return	int
	 */
	public function getCurPage() {
		return $this->curPage;
	}
	
	/**
	 * Sets next and previous page.
	 *
	 * @access	public
	 * @param	bool	Next or previous page.
	 * @return	int
	 */
	public function setNextPreviousPage($next_page = false) {
		# So it is $prevPage
		if(!$next_page) {
			if($this->curPage == 1) $this->prevPage = $this->curPage;
			else $this->prevPage = $this->curPage - 1;
			
			return $this->prevPage;
		} else {
			if($this->numPages > $this->curPage) $this->nextPage = $this->curPage + 1;
			else $this->nextPage = $this->curPage;
			
			return $this->nextPage;
		}
	}
}
//	END Pagination Class

/* End of file Pagination.php */
/* Location: ./system/libraries/Pagination.php */
?>