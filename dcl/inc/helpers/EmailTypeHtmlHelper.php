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

class EmailTypeHtmlHelper
{
	public function Select($default = 0, $cbName = 'email_type_id', $size = 0, $activeOnly = true)
	{
		$filter = '';
		$table = 'dcl_email_type';
		$order = 'email_type_name';

		$obj = new SelectHtmlHelper();
		$obj->SetOptionsFromDb($table, 'email_type_id', 'email_type_name', $filter, $order);
		$obj->DefaultValue = $default;
		$obj->Id = $cbName;
		$obj->Size = $size;
		$obj->FirstOption = STR_CMMN_SELECTONE;

		return $obj->GetHTML();
	}
}