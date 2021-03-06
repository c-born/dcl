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

$GLOBALS['phpgw_baseline']['dcl_contact'] = array(
	'fd' => array(
		'contact_id' => array('type' => 'auto', 'precision' => 4, 'nullable' => false),
		'first_name' => array('type' => 'varchar', 'precision' => 50, 'nullable' => false),
		'last_name' => array('type' => 'varchar', 'precision' => 50, 'nullable' => false),
		'middle_name' => array('type' => 'varchar', 'precision' => 50, 'nullable' => true),
		'active' => array('type' => 'varchar', 'precision' => 30, 'nullable' => false, 'default' => 'Y'),
		'created_on' => array('type' => 'timestamp', 'nullable' => false),
		'created_by' => array('type' => 'int', 'precision' => 4, 'nullable' => false),
		'modified_on' => array('type' => 'timestamp', 'nullable' => true),
		'modified_by' => array('type' => 'int', 'precision' => 4, 'nullable' => true)
	),
	'pk' => array('contact_id'),
	'fk' => array(),
	'ix' => array(
		'ix_dcl_contact_name_id' => array('last_name', 'first_name', 'contact_id')
	),
	'uc' => array()
);

$GLOBALS['phpgw_baseline']['dcl_contact']['joins'] = array(
	'dcl_org' => "dcl_org.org_id = dcl_org_contact.org_id",
	'dcl_org_contact' => "dcl_contact.contact_id = dcl_org_contact.contact_id",
	'dcl_contact_license' => "dcl_contact.contact_id = dcl_contact_license.contact_id",
	'dcl_contact_addr' => "dcl_contact.contact_id = dcl_contact_addr.contact_id AND dcl_contact_addr.preferred = 'Y'",
	'dcl_contact_email' => "dcl_contact.contact_id = dcl_contact_email.contact_id AND dcl_contact_email.preferred = 'Y'",
	'dcl_contact_phone' => "dcl_contact.contact_id = dcl_contact_phone.contact_id AND dcl_contact_phone.preferred = 'Y'",
	'dcl_contact_url' => "dcl_contact.contact_id = dcl_contact_url.contact_id AND dcl_contact_url.preferred = 'Y'"
);
