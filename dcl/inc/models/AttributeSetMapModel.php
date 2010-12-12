<?php
/*
 * This file is part of Double Choco Latte.
 * Copyright (C) 1999-2010 Free Software Foundation
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

class AttributeSetMapModel extends dclDB
{
	public function __construct()
	{
		parent::dclDB();
		$this->TableName = 'attributesetsmap';
		
		LoadSchema($this->TableName);
		
		parent::Clear();
	}

	public function Edit()
	{
		// Do nothing
	}

	public function Delete()
	{
		return parent::Delete(array('setid' => $this->setid, 'typeid' => $this->typeid, 'keyid' => $this->keyid));
	}
	
	public function DeleteBySetType($iSetID, $iTypeID)
	{
		if (($iSetID = DCL_Sanitize::ToInt($iSetID)) == NULL ||
			($iTypeID = DCL_Sanitize::ToInt($iTypeID)) == NULL)
		{
			throw new InvalidDataException();
		}
		
		$sSQL = 'DELETE FROM attributesetsmap WHERE setid = ';
		$sSQL .= $this->FieldValueToSQL('setid', $iSetID);
		$sSQL .= ' AND typeid = ';
		$sSQL .= $this->FieldValueToSQL('typeid', $iTypeID);
		
		return $this->Execute($sSQL);
	}

	public function LoadMapForType($setid, $typeid)
	{
		if (($setid = DCL_Sanitize::ToInt($setid)) == NULL ||
			($typeid = DCL_Sanitize::ToInt($typeid)) == NULL)
		{
			throw new InvalidDataException();
		}
		
		$this->Clear();
		$sql = 'SELECT * FROM attributesetsmap WHERE setid=' . $setid . ' AND typeid=' . $typeid . ' ORDER BY weight';
		if (!$this->Query($sql))
			return -1;

		return 1;
	}
}