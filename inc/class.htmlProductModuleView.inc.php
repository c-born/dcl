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

LoadStringResource('chk');
import('htmlView');
class htmlProductModuleView extends htmlView
{
	function htmlProductModuleView()
	{
		global $g_oSec;

		parent::htmlView();

		if ($g_oSec->HasAnyPerm(array(DCL_ENTITY_PRODUCTMODULE => array($g_oSec->PermArray(DCL_PERM_MODIFY), $g_oSec->PermArray(DCL_PERM_DELETE)))))
			$this->sColumnTitle = STR_CMMN_OPTIONS;

		$this->sSortAction = 'htmlProductModuleView.execurl';
	}

	function _SetActionFormOptions()
	{
		global $g_oSec;
		
		$aLinks = array();
		
		if (($product_id = DCL_Sanitize::ToInt($_REQUEST['product_id'])) === null)
		{
			trigger_error('Data sanitize failed.');
			return;
		}
		
		if ($g_oSec->HasPerm(DCL_ENTITY_PRODUCTMODULE, DCL_PERM_ADD))
			$aLinks[STR_CMMN_NEW] = menuLink('', 'menuAction=htmlProductModules.add&product_id=' . $product_id);

		if ($g_oSec->HasPerm(DCL_ENTITY_PRODUCT, DCL_PERM_VIEW))
			$aLinks['Detail'] = menuLink('', 'menuAction=boProducts.view&id=' . $product_id);

		$this->_SetVar('hActionLinkSetLinks', '');
		$bFirst = true;
		foreach ($aLinks as $sText => $sLink)
		{
			if ($bFirst)
				$bFirst = false;
			else
				$this->Template->parse('hActionLinkSetLinks', 'actionLinkSetSep', true);

			$this->_SetVar('LNK_ACTIONVALUE', $sLink);
			$this->_SetVar('VAL_ACTIONVALUE', $sText);
			$this->Template->parse('hActionLinkSetLinks', 'actionLinkSetLink', true);
		}

		$this->Template->parse('hActionLinkSet', 'actionLinkSet');
		$this->Template->parse('hActions', 'actions');
	}

	function _DisplayOptions()
	{
		global $dcl_info, $g_oSec;

		$this->_SetVar('hDetailColumnLinkSetLinks', '');
		$id = $this->oDB->f('product_module_id');


		if ($g_oSec->HasPerm(DCL_ENTITY_PRODUCTMODULE, DCL_PERM_MODIFY))
			$this->_AddDisplayOption(STR_CMMN_EDIT, menuLink('', 'menuAction=htmlProductModules.modify&product_module_id=' . $id));

		if ($g_oSec->HasPerm(DCL_ENTITY_PRODUCTMODULE, DCL_PERM_DELETE))
			$this->_AddDisplayOption(STR_CMMN_DELETE, menuLink('', 'menuAction=htmlProductModules.delete&product_module_id=' . $id), true);

		$this->Template->parse('hDetailColumnLinkSet', 'detailColumnLinkSet');
		$this->Template->parse('hDetailCells', 'detailCells', true);

		// this avoids repeating cells
		$this->_ResetDetailCells();
	}
}
?>