<?php
/*
 * $Id$
 *
 * This file is part of Double Choco Latte.
 * Copyright (C) 1999-2004 Free Software Foundation
 *
 * Double Choco Latte is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * Double Choco Latte is distributed in the hope that it will be useful,
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

LoadStringResource('bo');

class boStatuses
{
	function showall()
	{
		global $g_oSec;
		
		commonHeader();
		if (!$g_oSec->HasPerm(DCL_ENTITY_STATUS, DCL_PERM_VIEW))
			throw new PermissionDeniedException();

		$obj = new htmlStatuses();
		$obj->PrintAll();
	}

	function add()
	{
		global $g_oSec;
		
		commonHeader();
		if (!$g_oSec->HasPerm(DCL_ENTITY_STATUS, DCL_PERM_ADD))
			throw new PermissionDeniedException();

		$obj = new htmlStatuses();
		$obj->ShowEntryForm();
	}

	function dbadd()
	{
		global $g_oSec;
		
		commonHeader();
		if (!$g_oSec->HasPerm(DCL_ENTITY_STATUS, DCL_PERM_ADD))
			throw new PermissionDeniedException();

		$obj = new dbStatuses();
		$obj->InitFromGlobals();
		$obj->Add();

		$objHTML = new htmlStatuses();
		$objHTML->PrintAll();
	}

	function modify()
	{
		global $g_oSec;
		
		commonHeader();
		if (!$g_oSec->HasPerm(DCL_ENTITY_STATUS, DCL_PERM_MODIFY))
			throw new PermissionDeniedException();

		if (($iID = @DCL_Sanitize::ToInt($_REQUEST['id'])) === null)
		{
			throw new InvalidDataException();
		}
		
		$obj = new dbStatuses();
		if ($obj->Load($iID) == -1)
			return;
			
		$objHTML = new htmlStatuses();
		$objHTML->ShowEntryForm($obj);
	}

	function dbmodify()
	{
		global $g_oSec;
		
		commonHeader();
		if (!$g_oSec->HasPerm(DCL_ENTITY_STATUS, DCL_PERM_MODIFY))
			throw new PermissionDeniedException();

		$obj = new dbStatuses();
		$obj->InitFromGlobals();
		$obj->Edit();
		
		$objHTML = new htmlStatuses();
		$objHTML->PrintAll();
	}
	function delete()
	{
		global $g_oSec;
		
		commonHeader();
		if (!$g_oSec->HasPerm(DCL_ENTITY_STATUS, DCL_PERM_DELETE))
			throw new PermissionDeniedException();

		if (($iID = @DCL_Sanitize::ToInt($_REQUEST['id'])) === null)
		{
			throw new InvalidDataException();
		}
		
		$obj = new dbStatuses();
		if ($obj->Load($iID) == -1)
			return;
		
		ShowDeleteYesNo('Status', 'boStatuses.dbdelete', $obj->id, $obj->name);
	}

	function dbdelete()
	{
		global $g_oSec;
		
		commonHeader();
		if (!$g_oSec->HasPerm(DCL_ENTITY_STATUS, DCL_PERM_DELETE))
			throw new PermissionDeniedException();

		if (($iID = @DCL_Sanitize::ToInt($_REQUEST['id'])) === null)
		{
			throw new InvalidDataException();
		}
		
		$obj = new dbStatuses();
		if ($obj->Load($iID) == -1)
			return;

		if (!$obj->HasFKRef($iID))
		{
			$obj->Delete();
			print(STR_BO_DELETED);
		}
		else
		{
			$obj->SetActive(array($obj->sKeyField => $iID), false);
			print(STR_BO_DEACTIVATED);
		}

		$objHTML = new htmlStatuses();
		$objHTML->PrintAll();
	}
}
