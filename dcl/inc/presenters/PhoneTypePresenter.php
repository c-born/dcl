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

LoadStringResource('wo');
LoadStringResource('cfg');

class PhoneTypePresenter
{
	public function Index()
	{
		global $g_oSec;

		commonHeader();
		if (!$g_oSec->HasPerm(DCL_ENTITY_PHONETYPE, DCL_PERM_VIEW))
			throw new PermissionDeniedException();

		$o = new PhoneTypeSqlQueryHelper();
		$o->title = sprintf('Phone Types');
		$o->AddDef('columns', '', array('phone_type_id', 'phone_type_name'));
		$o->AddDef('columnhdrs', '', array(STR_CMMN_ID, STR_CMMN_NAME));
		$o->AddDef('order', '', 'phone_type_name');

		$oDB = new PhoneTypeModel();
		if ($oDB->query($o->GetSQL()) == -1)
			return;
			
		$allRecs = $oDB->FetchAllRows();

		$oTable = new TableHtmlHelper();
		$oTable->setCaption('Phone Types');
		$oTable->addColumn(STR_CMMN_ID, 'numeric');
		$oTable->addColumn(STR_CMMN_NAME, 'string');

		if ($g_oSec->HasPerm(DCL_ENTITY_PHONETYPE, DCL_PERM_ADD))
			$oTable->addToolbar(menuLink('', 'menuAction=PhoneType.Create'), STR_CMMN_NEW);

		if ($g_oSec->HasPerm(DCL_ENTITY_ADMIN, DCL_PERM_VIEW))
			$oTable->addToolbar(menuLink('', 'menuAction=SystemSetup.Index'), DCL_MENU_SYSTEMSETUP);

		if (count($allRecs) > 0 && $g_oSec->HasAnyPerm(array(DCL_ENTITY_PHONETYPE => array($g_oSec->PermArray(DCL_PERM_MODIFY), $g_oSec->PermArray(DCL_PERM_DELETE)))))
		{
			$oTable->addColumn(STR_CMMN_OPTIONS, 'html');
			for ($i = 0; $i < count($allRecs); $i++)
			{
				$options = '';
				if ($g_oSec->HasPerm(DCL_ENTITY_PHONETYPE, DCL_PERM_MODIFY))
					$options = '<a href="' . menuLink('', 'menuAction=PhoneType.Edit&phone_type_id=' . $allRecs[$i][0]) . '">' . STR_CMMN_EDIT . '</a>';

				if ($g_oSec->HasPerm(DCL_ENTITY_PHONETYPE, DCL_PERM_DELETE))
				{
					if ($options != '')
						$options .= '&nbsp;|&nbsp;';

					$options .= '<a href="' . menuLink('', 'menuAction=PhoneType.Delete&phone_type_id=' . $allRecs[$i][0]) . '">' . STR_CMMN_DELETE . '</a>';
				}

				$allRecs[$i][] = $options;
			}
		}
		
		$oTable->setData($allRecs);
		$oTable->setShowRownum(true);
		$oTable->sTemplate = 'TableView.tpl';
		$oTable->render();
	}

	public function Create()
	{
		global $g_oSec;
		
		commonHeader();
		if (!$g_oSec->HasPerm(DCL_ENTITY_PHONETYPE, DCL_PERM_ADD))
			throw new PermissionDeniedException();

		$t = new SmartyHelper();

		$t->assign('TXT_FUNCTION', 'Add Phone Type');
		$t->assign('menuAction', 'PhoneType.Insert');

		$t->Render('PhoneTypeForm.tpl');
	}

	public function Edit(PhoneTypeModel $model)
	{
		global $g_oSec;
		
		commonHeader();
		if (!$g_oSec->HasPerm(DCL_ENTITY_PHONETYPE, DCL_PERM_MODIFY))
			throw new PermissionDeniedException();

		$t = new SmartyHelper();

		$t->assign('TXT_FUNCTION', 'Edit Phone Type');
		$t->assign('menuAction', 'PhoneType.Update');
		$t->assign('phone_type_id', $model->phone_type_id);
		$t->assign('VAL_NAME', $model->phone_type_name);

		$t->Render('PhoneTypeForm.tpl');
	}

	public function Delete(PhoneTypeModel $model)
	{
		global $g_oSec;
		
		commonHeader();
		if (!$g_oSec->HasPerm(DCL_ENTITY_PHONETYPE, DCL_PERM_DELETE))
			throw new PermissionDeniedException();

		ShowDeleteYesNo('Phone Type', 'PhoneType.Destroy', $model->phone_type_id, $model->phone_type_name);
	}
}
