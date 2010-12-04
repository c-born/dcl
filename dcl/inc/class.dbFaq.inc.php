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

LoadStringResource('db');
class dbFaq extends dclDB
{
	function dbFaq()
	{
		parent::dclDB();
		$this->TableName = 'faq';
		LoadSchema($this->TableName);

		$this->foreignKeys = array('faqtopics' => 'faqid');

		parent::Clear();
	}

	function Add()
	{
		$this->active = 'Y';
		$this->createby = $GLOBALS['DCLID'];
		$this->createon = DCL_NOW;
		return parent::Add();
	}

	function Edit()
	{
		$this->modifyby = $GLOBALS['DCLID'];
		$this->modifyon = DCL_NOW;
		return parent::Edit(array('createby', 'createon'));
	}

	function Delete()
	{
		$oDB = new dbFaqtopics();
		$oDB->DeleteByFaq($this->faqid);
		
		return parent::Delete(array('faqid' => $this->faqid));
	}

	function Load($id)
	{
		return parent::Load(array('faqid' => $id));
	}
}
