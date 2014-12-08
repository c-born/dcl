<?php
/*
 * This file is part of Double Choco Latte.
 * Copyright (C) 1999-2014 Free Software Foundation
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

class MeasurementUnitService
{
	public function GetData()
	{
		$page = @Filter::ToInt($_REQUEST['page']);
		$limit = @Filter::ToInt($_REQUEST['rows']);
		$sidx = @Filter::ToSqlName($_REQUEST['sidx']);
		$sord = isset($_REQUEST['sord']) ? $_REQUEST['sord'] : 'asc';

		if ($sord != 'asc' && $sord != 'desc')
			$sord = 'asc';

		if ($sidx === null)
			$sidx = 'unit_name';

		if ($page === null)
			$page = 1;

		if ($limit === null)
			$limit = 1;

		$validColumns = array('measurement_unit_id', 'unit_name', 'unit_abbr');
		if (!in_array($sidx, $validColumns))
			$sidx = 'unit_name';

		$idFilter = @Filter::ToInt($_REQUEST['measurement_unit_id']);
		$nameFilter = @$_REQUEST['unit_name'];
		$abbrFilter = @$_REQUEST['unit_abbr'];

		$model = new MeasurementUnitModel();
		$queryHelper = new MeasurementUnitSqlQueryHelper();
		$queryHelper->AddDef('columns', '', array('measurement_unit_id', 'unit_name', 'unit_abbr'));

		if ($idFilter !== null)
			$queryHelper->AddDef('filter', 'measurement_unit_id', $idFilter);

		if (isset($nameFilter))
			$queryHelper->AddDef('filterlike', 'unit_name', $nameFilter);

		if (isset($abbrFilter))
			$queryHelper->AddDef('filterlike', 'unit_abbr', $abbrFilter);

		$queryHelper->AddDef('order', '', array($sidx . ' ' . $sord));

		$retVal = new stdClass();
		$retVal->records = $model->ExecuteScalar($queryHelper->GetSQL(true));
		$retVal->page = $page;
		$retVal->total = ceil($retVal->records / $limit);

		$query = $queryHelper->GetSQL();
		if ($limit > 0)
			$model->LimitQuery($query, ($page - 1) * $limit, $limit);
		else
			$model->Query($query);

		$allRecs = $model->FetchAllRows();

		$retVal->rows = array();
		if ($retVal->records > 0)
		{
			foreach ($allRecs as $record)
			{
				$retVal->rows[] = array('id' => $record[0], 'cell' => $record);
			}
		}

		header('Content-Type: application/json');
		echo json_encode($retVal);
		exit;
	}
}