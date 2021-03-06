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

LoadStringResource('sev');

class SeverityPresenter
{
	public function Index()
	{
        global $g_oSec;

        commonHeader();
        RequirePermission(DCL_ENTITY_SEVERITY, DCL_PERM_VIEW);

        $smartyHelper = new SmartyHelper();
        $smartyHelper->assign('PERM_ADD', $g_oSec->HasPerm(DCL_ENTITY_SEVERITY, DCL_PERM_ADD));
        $smartyHelper->assign('PERM_EDIT', $g_oSec->HasPerm(DCL_ENTITY_SEVERITY, DCL_PERM_MODIFY));
        $smartyHelper->assign('PERM_DELETE', $g_oSec->HasPerm(DCL_ENTITY_SEVERITY, DCL_PERM_DELETE));
        $smartyHelper->assign('PERM_ADMIN', $g_oSec->HasPerm(DCL_ENTITY_ADMIN, DCL_PERM_VIEW));
        $smartyHelper->Render('SeverityGrid.tpl');
        return;
		global $g_oSec;

		commonHeader();
		if (!$g_oSec->HasPerm(DCL_ENTITY_SEVERITY, DCL_PERM_VIEW))
			throw new PermissionDeniedException();

		$model = new SeverityModel();
		$model->Query("SELECT id,active,short,name,weight FROM severities ORDER BY name");
		$allRecs = $model->FetchAllRows();

		$oTable = new TableHtmlHelper();
		$oTable->setCaption(STR_SEV_TABLETITLE);
		$oTable->addColumn(STR_SEV_ID, 'numeric');
		$oTable->addColumn(STR_SEV_ACTIVE, 'string');
		$oTable->addColumn(STR_SEV_SHORT, 'string');
		$oTable->addColumn(STR_SEV_NAME, 'string');
		$oTable->addColumn(STR_SEV_WEIGHT, 'numeric');

		if ($g_oSec->HasPerm(DCL_ENTITY_SEVERITY, DCL_PERM_ADD))
			$oTable->addToolbar(menuLink('', 'menuAction=Severity.Create'), STR_CMMN_NEW);

		if ($g_oSec->HasPerm(DCL_ENTITY_ADMIN, DCL_PERM_VIEW))
			$oTable->addToolbar(menuLink('', 'menuAction=SystemSetup.Index'), DCL_MENU_SYSTEMSETUP);

		if (count($allRecs) > 0 && $g_oSec->HasAnyPerm(array(DCL_ENTITY_SEVERITY => array($g_oSec->PermArray(DCL_PERM_MODIFY), $g_oSec->PermArray(DCL_PERM_DELETE)))))
		{
			$oTable->addColumn(STR_CMMN_OPTIONS, 'html');
			for ($i = 0; $i < count($allRecs); $i++)
			{
				$options = '';
				if ($g_oSec->HasPerm(DCL_ENTITY_SEVERITY, DCL_PERM_MODIFY))
					$options = '<a href="' . menuLink('', 'menuAction=Severity.Edit&id=' . $allRecs[$i][0]) . '">' . STR_CMMN_EDIT . '</a>';

				if ($g_oSec->HasPerm(DCL_ENTITY_SEVERITY, DCL_PERM_DELETE))
				{
					if ($options != '')
						$options .= '&nbsp;|&nbsp;';

					$options .= '<a href="' . menuLink('', 'menuAction=Severity.Delete&id=' . $allRecs[$i][0]) . '">' . STR_CMMN_DELETE . '</a>';
				}

				$allRecs[$i][] = $options;
			}
		}
		
		$oTable->setData($allRecs);
		$oTable->setShowRownum(true);
		$oTable->render();
	}

	public function Create()
	{
		global $g_oSec;

		commonHeader();
		if (!$g_oSec->HasPerm(DCL_ENTITY_SEVERITY, DCL_PERM_ADD))
			throw new PermissionDeniedException();

		$t = new SmartyHelper();

		$t->assign('TXT_FUNCTION', STR_SEV_ADD);
		$t->assign('menuAction', 'Severity.Insert');
		$t->assign('CMB_ACTIVE', GetYesNoCombo('Y', 'active', 0, false));

		$t->Render('SeveritiesForm.tpl');
	}

	public function Edit(SeverityModel $model)
	{
		global $g_oSec;

		commonHeader();
		if (!$g_oSec->HasPerm(DCL_ENTITY_SEVERITY, DCL_PERM_MODIFY))
			throw new PermissionDeniedException();

		$t = new SmartyHelper();

		$t->assign('TXT_FUNCTION', STR_SEV_EDIT);
		$t->assign('menuAction', 'Severity.Update');
		$t->assign('id', $model->id);
		$t->assign('CMB_ACTIVE', GetYesNoCombo($model->active, 'active', 0, false));
		$t->assign('VAL_SHORT', $model->short);
		$t->assign('VAL_NAME', $model->name);
		$t->assign('VAL_WEIGHT', $model->weight);

		$t->Render('SeveritiesForm.tpl');
	}

	public function Delete(SeverityModel $model)
	{
		global $g_oSec;

		commonHeader();
		if (!$g_oSec->HasPerm(DCL_ENTITY_SEVERITY, DCL_PERM_DELETE))
			throw new PermissionDeniedException();
		
		ShowDeleteYesNo('Severity', 'Severity.Destroy', $model->id, $model->name);
	}
}
