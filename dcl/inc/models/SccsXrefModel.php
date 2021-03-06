<?php
/*
 * This file is part of Double Choco Latte.
 * Copyright (C) 1999-2011 Free Software Foundation
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
class SccsXrefModel extends DbProvider
{
	public function __construct()
	{
		parent::__construct();
		$this->TableName = 'dcl_sccs_xref';
		LoadSchema($this->TableName);
		
		parent::Clear();
	}

	public function ListChangeLog($type, $id1, $id2 = 0)
	{
		if (($type = Filter::ToInt($type)) === null ||
			($id1 = Filter::ToInt($id1)) === null ||
			($id2 = Filter::ToInt($id2)) === null)
		{
			throw new InvalidDataException();
		}
		
		$this->Clear();

		$sql = 'SELECT sccs_descr, personnel.short, sccs_project_path, sccs_file_name, ';
		$sql .= 'sccs_version, sccs_comments, ';
		$sql .= $this->ConvertTimestamp('sccs_checkin_on', 'sccs_checkin_on');
		$sql .= " FROM dcl_sccs_xref, dcl_sccs, personnel WHERE dcl_entity_type_id=$type AND ";
		$sql .= "dcl_entity_id = $id1 and dcl_entity_id2 = $id2 AND dcl_sccs_xref.dcl_sccs_id = dcl_sccs.dcl_sccs_id ";
		$sql .= " AND dcl_sccs_xref.personnel_id = personnel.id ";
		$sql .= 'ORDER BY sccs_descr, sccs_project_path, sccs_file_name, sccs_version';
		if (!$this->Query($sql))
			return -1;

		return 1;
	}
}
