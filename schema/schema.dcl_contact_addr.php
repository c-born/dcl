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

$GLOBALS['phpgw_baseline']['dcl_contact_addr'] = array(
	'fd' => array(
		'contact_addr_id' => array('type' => 'auto', 'precision' => 4, 'nullable' => false),
		'contact_id' => array('type' => 'int', 'precision' => 4, 'nullable' => false),
		'addr_type_id' => array('type' => 'int', 'precision' => 4, 'nullable' => false),
		'add1' => array('type' => 'varchar', 'precision' => 50, 'nullable' => true),
		'add2' => array('type' => 'varchar', 'precision' => 50, 'nullable' => true),
		'city' => array('type' => 'varchar', 'precision' => 50, 'nullable' => true),
		'state' => array('type' => 'varchar', 'precision' => 30, 'nullable' => true),
		'zip' => array('type' => 'varchar', 'precision' => 20, 'nullable' => true),
		'country' => array('type' => 'varchar', 'precision' => 40, 'nullable' => true),
		'preferred' => array('type' => 'char', 'precision' => 1, 'default' => 'N', 'nullable' => false),
		'created_on' => array('type' => 'timestamp', 'nullable' => false, 'default' => 'now()'),
		'created_by' => array('type' => 'int', 'precision' => 4, 'nullable' => false),
		'modified_on' => array('type' => 'timestamp', 'nullable' => true),
		'modified_by' => array('type' => 'int', 'precision' => 4, 'nullable' => true)
	),
	'pk' => array('contact_addr_id'),
	'fk' => array(),
	'ix' => array(
		'ix_dcl_contact_addr_contact' => array('contact_id')
	),
	'uc' => array()
);
?>