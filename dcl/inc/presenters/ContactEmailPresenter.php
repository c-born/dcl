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

class ContactEmailPresenter
{
	public function Create($contactId)
	{
		global $dcl_info, $g_oSec;
		
		commonHeader();
		if (!$g_oSec->HasPerm(DCL_ENTITY_CONTACT, DCL_PERM_MODIFY))
			throw new PermissionDeniedException();

		$oSmarty = new SmartyHelper();
		$oEmailType = new EmailTypeHtmlHelper();

		$oSmarty->assign('URL_BACK', menuLink('', 'menuAction=htmlContactDetail.show&contact_id=' . $contactId));

		$oContact = new ContactModel();
		if ($oContact->Load($contactId) == -1)
		    throw new InvalidEntityException();

		$oSmarty->assign('VAL_FIRSTNAME', $oContact->first_name);
		$oSmarty->assign('VAL_LASTNAME', $oContact->last_name);
		$oSmarty->assign('VAL_CONTACTID', $oContact->contact_id);
		$oSmarty->assign('TXT_FUNCTION', 'Add New Contact E-Mail');
		$oSmarty->assign('CMB_EMAILTYPE', $oEmailType->Select());
		$oSmarty->assign('VAL_MENUACTION', 'ContactEmail.Insert');

		$oSmarty->Render('EmailForm.tpl');
	}

	public function Edit(ContactEmailModel $model)
	{
		global $dcl_info, $g_oSec;
		
		commonHeader();
		if (!$g_oSec->HasPerm(DCL_ENTITY_CONTACT, DCL_PERM_MODIFY))
			throw new PermissionDeniedException();
			
		$oSmarty = new SmartyHelper();
		$oEmailType = new EmailTypeHtmlHelper();

		$oSmarty->assign('URL_BACK', menuLink('', 'menuAction=htmlContactDetail.show&contact_id=' . $model->contact_id));

		$oContact = new ContactModel();
		if ($oContact->Load($model->contact_id) == -1)
		    return;

		$oSmarty->assign('VAL_FIRSTNAME', $oContact->first_name);
		$oSmarty->assign('VAL_LASTNAME', $oContact->last_name);
		$oSmarty->assign('VAL_CONTACTID', $oContact->contact_id);

		$oSmarty->assign('VAL_MENUACTION', 'ContactEmail.Update');
		$oSmarty->assign('VAL_CONTACTEMAILID', $model->contact_email_id);
		$oSmarty->assign('VAL_EMAILADDR', $model->email_addr);
		$oSmarty->assign('CMB_EMAILTYPE', $oEmailType->Select($model->email_type_id));
		$oSmarty->assign('VAL_PREFERRED', $model->preferred);
		$oSmarty->assign('TXT_FUNCTION', 'Edit Contact E-Mail');

		$oSmarty->Render('EmailForm.tpl');
	}
}
