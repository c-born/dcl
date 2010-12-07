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

class boFaqanswers
{
	function add()
	{
		global $g_oSec;
		
		commonHeader();
		if (!$g_oSec->HasPerm(DCL_ENTITY_FAQANSWER, DCL_PERM_ADD))
			throw new PermissionDeniedException();

		if (($iID = @DCL_Sanitize::ToInt($_REQUEST['questionid'])) === null)
		{
			throw new InvalidDataException();
		}
		
		$objF = new dbFaqquestions();
		if ($objF->Load($iID) == -1)
		{
			printf(STR_BO_CANNOTLOADQUESTION, $iID);
			return;
		}

		$obj = new htmlFaqanswers();
		$obj->DisplayForm();

		$objH = new htmlFaqquestions();
		print('<p>');
		$objH->ShowQuestion($objF);
	}

	function dbadd()
	{
		global $g_oSec;
		
		commonHeader();
		if (!$g_oSec->HasPerm(DCL_ENTITY_FAQANSWER, DCL_PERM_ADD))
			throw new PermissionDeniedException();

		if (($iID = @DCL_Sanitize::ToInt($_REQUEST['questionid'])) === null)
		{
			throw new InvalidDataException();
		}
		
		$objF = new dbFaqquestions();
		if ($objF->Load($iID) == -1)
		{
			printf(STR_BO_CANNOTLOADQUESTION, $iID);
			return;
		}

		$obj = new dbFaqanswers();
		$obj->InitFromGlobals();
		$obj->createby = $GLOBALS['DCLID'];
		$obj->createon = DCL_NOW;
		$obj->active = 'Y';
		$obj->Add();

		$objH = new htmlFaqquestions();
		print('<p>');
		$objH->ShowQuestion($objF);
	}

	function modify()
	{
		global $g_oSec;
		
		commonHeader();
		if (!$g_oSec->HasPerm(DCL_ENTITY_FAQANSWER, DCL_PERM_MODIFY))
			throw new PermissionDeniedException();

		if (($iID = @DCL_Sanitize::ToInt($_REQUEST['answerid'])) === null)
		{
			throw new InvalidDataException();
		}
		
		$obj = new dbFaqanswers();
		if ($obj->Load($iID) == -1)
			return;
			
		$objH = new htmlFaqanswers();
		$objH->DisplayForm($obj);
	}

	function dbmodify()
	{
		global $g_oSec;
		
		commonHeader();
		if (!$g_oSec->HasPerm(DCL_ENTITY_FAQANSWER, DCL_PERM_MODIFY))
			throw new PermissionDeniedException();

		if (($iID = @DCL_Sanitize::ToInt($_REQUEST['questionid'])) === null)
		{
			throw new InvalidDataException();
		}
		
		$objF = new dbFaqquestions();
		if ($objF->Load($iID) == -1)
		{
			return;
		}

		$obj = new dbFaqanswers();
		$obj->InitFromGlobals();
		$obj->active = @DCL_Sanitize::ToYN($_REQUEST['active']);
		$obj->modifyby = $GLOBALS['DCLID'];
		$obj->modifyon = DCL_NOW;
		$obj->Edit();
		
		$objH = new htmlFaqquestions();
		$objH->ShowQuestion($objF);
	}

	function delete()
	{
		global $g_oSec;
		
		commonHeader();
		if (($iID = @DCL_Sanitize::ToInt($_REQUEST['answerid'])) === null)
		{
			throw new InvalidDataException();
		}
		
		if (!$g_oSec->HasPerm(DCL_ENTITY_FAQANSWER, DCL_PERM_DELETE, $iID))
			throw new PermissionDeniedException();

		$obj = new dbFaqanswers();
		if ($obj->Load($iID) == -1)
			return;
		
		ShowDeleteYesNo(STR_FAQ_ANSWER, 'boFaqanswers.dbdelete', $iID, $obj->answertext, false, 'answerid');
	}

	function dbdelete()
	{
		global $g_oSec;
		
		commonHeader();
		if (($iID = @DCL_Sanitize::ToInt($_REQUEST['answerid'])) === null)
		{
			throw new InvalidDataException();
		}
		
		if (!$g_oSec->HasPerm(DCL_ENTITY_FAQANSWER, DCL_PERM_DELETE, $iID))
			throw new PermissionDeniedException();

		$obj = new dbFaqanswers();
		if ($obj->Load($iID) == -1)
			return;
			
		$iQuestionID = $obj->questionid;
		$obj->Delete($iID);
		
		$objQ = new dbFaqquestions();
		if ($objQ->Load($iQuestionID) == -1)
		{
			return -1;
		}
		
		$objH = new htmlFaqquestions();
		$objH->ShowQuestion($objQ);
	}
}
