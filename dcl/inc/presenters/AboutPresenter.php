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

LoadStringResource('ver');

class AboutPresenter
{
	public function Detail()
	{
		global $dcl_info;

		commonHeader();

		$template = new SmartyHelper();

		$template->assign('TXT_TITLE', STR_VER_TITLE);
		$template->assign('TXT_YOURVER', STR_VER_YOURVER);

		$template->assign('TXT_DCL', STR_VER_DCL);
		$template->assign('TXT_YOURIP', STR_VER_YOURIP);
		$template->assign('TXT_YOURBROWSER', STR_VER_YOURBROWSER);

		$template->assign('VAL_DCLVERSION', $dcl_info['DCL_VERSION']);
		// build-info.php is written by the deploy script (commit, commit date, build date); a development copy does not have it
		$aBuild = array('build' => 'development copy (no build information)', 'commit_date' => '', 'built' => '');
		$sBuildFile = DCL_ROOT . 'inc/build-info.php';
		if (is_readable($sBuildFile))
		{
			$aLoaded = include $sBuildFile;
			if (is_array($aLoaded))
				$aBuild = array_merge($aBuild, $aLoaded);
		}

		$template->assign('VAL_BUILD', $aBuild['build']);
		$template->assign('VAL_COMMITDATE', $aBuild['commit_date']);
		$template->assign('VAL_BUILT', $aBuild['built']);
		$template->assign('VAL_PHPVERSION', PHP_VERSION);
		$template->assign('VAL_REMOTEADDR', $_SERVER['REMOTE_ADDR']);
		$template->assign('VAL_HTTPUSERAGENT', $_SERVER['HTTP_USER_AGENT']);

		$template->Render('About.tpl');
	}
}
