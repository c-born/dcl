<?php
	/*
	 * Double Choco Latte - Source Configuration Management System
	 * Copyright (C) 1999  Michael L. Dean & Tim R. Norman
	 *
	 * This program is free software; you can redistribute it and/or
	 * modify it under the terms of the GNU General Public License
	 * as published by the Free Software Foundation; either version 2
	 * of the License, or (at your option) any later version.
	 *
	 * This program is distributed in the hope that it will be useful,
	 * but WITHOUT ANY WARRANTY; without even the implied warranty of
	 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	 * GNU General Public License for more details.
	 *
	 * You should have received a copy of the GNU General Public License
	 * along with this program; if not, write to the Free Software
	 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
	 *
	 * Select License Info from the Help menu to view the terms and conditions of this license.
	 */

LoadStringResource('wo');
LoadStringResource('tck');
LoadStringResource('menu');
class DCLNavBar
{
	var $t;
	var $_class;
	var $_method;

	function __construct()
	{
		global $dcl_info;

		$this->t = new SmartyHelper();
		if (IsSet($_REQUEST['menuAction']) && $_REQUEST['menuAction'] != 'clearScreen')
			list($this->_class, $this->_method) = explode('.', $_REQUEST['menuAction']);
	}

	function createGlobal()
	{
		global $g_oSec, $dcl_info;
		$aItems = array();

		if ($g_oSec->HasPerm(DCL_ENTITY_WORKORDER, DCL_PERM_ADD))
			$aItems[] = array(DCL_MENU_NEWWORKORDER, 'WorkOrder.Create', 'new-16.png');

		if ($g_oSec->HasPerm(DCL_ENTITY_PROJECT, DCL_PERM_ADD))
			$aItems[] = array(DCL_MENU_NEWPROJECT, 'Project.Create', 'new-16.png');

		if ($g_oSec->HasPerm(DCL_ENTITY_TICKET, DCL_PERM_ADD))
			$aItems[] = array(DCL_MENU_NEWTICKET, 'boTickets.add', 'new-16.png');

		if ($dcl_info['DCL_WIKI_ENABLED'] == 'Y' && $g_oSec->HasPerm(DCL_ENTITY_GLOBAL, DCL_PERM_VIEWWIKI))
			$aItems[] = array(DCL_MENU_MAINWIKI, 'htmlWiki.show&type=0&name=FrontPage', 'book-16.png');

		$aItems[] = array('Print', 'javascript:printer_friendly();', 'print-16.png');

		$this->t->assign('VAL_TITLE', STR_CMMN_OPTIONS);

		return $this->renderItems($aItems);
	}

	function createGroupContext()
	{
		global $g_oSec, $dcl_info;
		$aItems = array();

		if ($this->_isWorkorderGroup())
		{
			if ($g_oSec->HasPerm(DCL_ENTITY_WORKORDER, DCL_PERM_ACTION))
				$aItems[] = array(DCL_MENU_MYWOS, 'WorkOrder.SearchMy', 'home-16.png');

			if ($g_oSec->HasPerm(DCL_ENTITY_WORKORDER, DCL_PERM_ADD))
				$aItems[] = array(DCL_MENU_NEW, 'WorkOrder.Create', 'new-16.png');

			if ($g_oSec->HasPerm(DCL_ENTITY_WORKORDER, DCL_PERM_IMPORT))
				$aItems[] = array(DCL_MENU_IMPORT, 'WorkOrder.Import', 'import-16.png');

			if ($g_oSec->HasPerm(DCL_ENTITY_WORKORDER, DCL_PERM_REPORT))
			{
				$aItems[] = array(DCL_MENU_ACTIVITY, 'reportPersonnelActivity.getparameters', 'exec-16.png');
				$aItems[] = array(DCL_MENU_GRAPH, 'WorkOrder.GraphCriteria', 'exec-16.png');
			}

			if ($g_oSec->HasPerm(DCL_ENTITY_WORKORDER, DCL_PERM_SEARCH))
				$aItems[] = array(DCL_MENU_SEARCH, 'WorkOrder.Criteria', 'search-16.png');

			if ($g_oSec->HasAnyPerm(array(DCL_ENTITY_WORKORDER => array($g_oSec->PermArray(DCL_PERM_VIEW), $g_oSec->PermArray(DCL_PERM_VIEWSUBMITTED), $g_oSec->PermArray(DCL_PERM_VIEWACCOUNT)))))
				$aItems[] = array(DCL_MENU_BROWSE, 'WorkOrder.Browse', 'exec-16.png');

			$this->t->assign('VAL_TITLE', DCL_MENU_WORKORDERS);
		}
		else if ($this->_isTicketGroup())
		{
			if ($g_oSec->HasPerm(DCL_ENTITY_TICKET, DCL_PERM_ACTION))
				$aItems[] = array(DCL_MENU_MYTICKETS, 'htmlTickets.show&filterReportto=' . DCLID, 'home-16.png');

			if ($g_oSec->HasPerm(DCL_ENTITY_TICKET, DCL_PERM_ADD))
				$aItems[] = array(DCL_MENU_NEW, 'boTickets.add', 'new-16.png');

			if ($g_oSec->HasPerm(DCL_ENTITY_TICKET, DCL_PERM_REPORT))
			{
				$aItems[] = array(DCL_MENU_ACTIVITY, 'reportTicketActivity.getparameters', 'exec-16.png');
				$aItems[] = array(DCL_MENU_GRAPH, 'boTickets.graph', 'exec-16.png');
			}

			if ($g_oSec->HasPerm(DCL_ENTITY_TICKET, DCL_PERM_SEARCH))
				$aItems[] = array(DCL_MENU_SEARCH, 'htmlTicketSearches.Show', 'search-16.png');

			if ($g_oSec->HasAnyPerm(array(DCL_ENTITY_TICKET => array($g_oSec->PermArray(DCL_PERM_VIEW), $g_oSec->PermArray(DCL_PERM_VIEWSUBMITTED), $g_oSec->PermArray(DCL_PERM_VIEWACCOUNT)))))
				$aItems[] = array(DCL_MENU_BROWSE, 'htmlTickets.show', 'exec-16.png');

			$this->t->assign('VAL_TITLE', DCL_MENU_TICKETS);
		}
		else if ($this->_isProjectGroup())
		{
			if ($g_oSec->HasPerm(DCL_ENTITY_PROJECT, DCL_PERM_VIEW))
				$aItems[] = array(DCL_MENU_MYPROJECTS, 'Project.Index&filterReportto=' . DCLID, 'home-16.png');

			if ($g_oSec->HasPerm(DCL_ENTITY_PROJECT, DCL_PERM_ADD))
				$aItems[] = array(DCL_MENU_NEW, 'Project.Create', 'new-16.png');

			if ($g_oSec->HasPerm(DCL_ENTITY_PROJECT, DCL_PERM_VIEW))
				$aItems[] = array(DCL_MENU_BROWSE, 'Project.Index', 'exec-16.png');

			$this->t->assign('VAL_TITLE', DCL_MENU_PROJECTS);
		}
		else if ($this->_isAdminGroup())
		{
			if ($g_oSec->HasPerm(DCL_ENTITY_STATUS, DCL_PERM_ADD))
				$aItems[] = array(STR_CMMN_NEW, 'Status.Create', '');
	
			if ($g_oSec->HasPerm(DCL_ENTITY_ADMIN, DCL_PERM_VIEW))
			{
				$aItems[] = array(DCL_MENU_SYSTEMSETUP, 'SystemSetup.Index', '');
			}
			
			$this->t->assign('VAL_TITLE', DCL_MENU_ADMIN);
		}
		else
			return;

		return $this->renderItems($aItems);
	}

	function renderItems(&$aItems)
	{
		$aLinks = array();
		$i = 0;
		foreach ($aItems as $aItem)
		{
			$aLinks[$i] = array();
			if (substr($aItem[1], 0, 11) == 'javascript:')
				$aLinks[$i]['onclick'] = $aItem[1];
			else
				$aLinks[$i]['onclick'] = menuLink('', 'menuAction=' . $aItem[1]);
			
			$aLinks[$i]['text'] = $aItem[0];
			$aLinks[$i]['image'] = $aItem[2];
			
			$i++;
		}

		$this->t->assign('VAL_NAVBOXITEMS', $aLinks);

		return $this->t->ToString('navbar.tpl');
	}

	function getHtml()
	{
		$retVal = $this->createGroupContext();
		$retVal .= $this->createGlobal();

		return $retVal;
	}

	function _isWorkorderGroup()
	{
		return ($this->_class == 'reportPersonnelActivity' ||
				$this->_class == 'WorkOrder' ||
				$this->_class == 'boTimecards' ||
				($this->_class == 'htmlSearchBox' && $_REQUEST['which'] == 'workorders')
			);
	}

	function _isWorkorderItem()
	{
		global $menuAction;

		if (!IsSet($_REQUEST['jcn']) || $_REQUEST['jcn'] == '' || !IsSet($_REQUEST['seq']) || $_REQUEST['seq'] == '')
			return false;

		$bSearchBox = ($this->_class == 'htmlSearchBox' &&
				$_REQUEST['which'] == 'workorders' &&
				preg_match('/^([0-9]+)[-]([0-9]*)$/', $_REQUEST['search_text'], $reg)
			);

		if ($bSearchBox)
		{
			$_REQUEST['jcn'] = $reg[1];
			$_REQUEST['seq'] = $reg[2];
		}

		return ($bSearchBox ||
				$menuAction == 'WorkOrder.Detail' ||
				$menuAction == 'boTimecards.add' ||
				$menuAction == 'WorkOrder.Attachment' ||
				$menuAction == 'WorkOrder.Reassign' ||
				$menuAction == 'WorkOrder.Edit'
			);
	}

	function _isAdminGroup()
	{
		return false;// (in_array($this->_class, array('StatusController')));
	}

	function _isTicketGroup()
	{
		return ($this->_class == 'htmlTickets' ||
				$this->_class == 'boTickets' ||
				$this->_class == 'boTicketresolutions' ||
				$this->_class == 'reportTicketActivity' ||
				($this->_class == 'htmlSearchBox' && $_REQUEST['which'] == 'tickets')
			);
	}

	function _isTicketItem()
	{
		global $menuAction;

		$bSearchBox = ($this->_class == 'htmlSearchBox' &&
				$_REQUEST['which'] == 'tickets' &&
				preg_match('/^([0-9]+)$/', $_REQUEST['search_text'], $reg)
			);

		if ($bSearchBox)
			$_REQUEST['ticketid'] = $reg[1];

		return ($bSearchBox ||
				$menuAction == 'boTicketresolutions.add' ||
				$menuAction == 'boTickets.reassign' ||
				$menuAction == 'boTickets.modify' ||
				$menuAction == 'boTickets.delete' ||
				$menuAction == 'boTickets.copyToWO' ||
				$menuAction == 'boTickets.upload' ||
				$menuAction == 'boTickets.view'
			);
	}

	function _isProjectGroup()
	{
		return ($this->_class == 'ProjectDetailPresenter' ||
				$this->_class == 'Project' ||
				($this->_class == 'htmlSearchBox' && $_REQUEST['which'] == 'dcl_projects')
			);
	}

	function _isProjectItem()
	{
		global $menuAction;

		return ($menuAction == 'Project.Detail');
	}
}
