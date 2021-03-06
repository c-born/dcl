<?php
/*
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

$GLOBALS['phpgw_baseline']['dcl_wiki'] = array(
	'fd' => array(
		'dcl_entity_type_id' => array('type' => 'int', 'precision' => 4, 'nullable' => false),
		'dcl_entity_id' => array('type' => 'int', 'precision' => 4, 'nullable' => false),
		'dcl_entity_id2' => array('type' => 'int', 'precision' => 4, 'nullable' => false),
		'page_name' => array('type' => 'varchar', 'precision' => 255, 'nullable' => false),
		'page_text' => array('type' => 'text'),
		'page_date' => array('type' => 'timestamp'),
		'page_ip' => array('type' => 'varchar', 'precision' => 255)
	),
	'pk' => array('dcl_entity_type_id', 'dcl_entity_id', 'dcl_entity_id2', 'page_name'),
	'fk' => array(),
	'ix' => array(),
	'uc' => array()
);

if ($GLOBALS['dcl_domain_info'][$GLOBALS['dcl_domain']]['dbType'] == 'mysql')
{
	// INNOdb has max of 767 / 4-byte UTF8 - 12 bytes for int columns in key
	$GLOBALS['phpgw_baseline']['dcl_wiki']['fd']['page_name']['precision'] = (int)(767 / 4 - 12);
}