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

class OrganizationCreateViewModel
{
	public $OrganizationId;
	
	public function Insert(array $formCollection)
	{
		$organizationModel = new OrganizationModel();
		$organizationModel->name = $formCollection['name'];
		$organizationModel->active = 'Y';
		$organizationModel->created_on = DCL_NOW;
		$organizationModel->created_by = DCLID;
		$organizationModel->Add();

		if ($organizationModel->org_id < 1)
		{
			throw new InvalidEntityException();
		}
		
		$this->OrganizationId = $organizationModel->org_id;
		
		$aOrgTypes = @Filter::ToIntArray($formCollection['org_type_id']);
		if ($aOrgTypes !== null)
		{
			$organizationTypeXrefModel = new OrganizationTypeXrefModel();
			$organizationTypeXrefModel->org_id = $organizationModel->org_id;
			foreach ($aOrgTypes as $organizationTypeId)
			{
				$organizationTypeXrefModel->org_type_id = $organizationTypeId;
				$organizationTypeXrefModel->Add();
			}
		}

		if ($formCollection['alias'] != '')
		{
			$organizationAliasModel = new OrganizationAliasModel();
			$organizationAliasModel->org_id = $organizationModel->org_id;
			$organizationAliasModel->alias = $formCollection['alias'];
			$organizationAliasModel->created_on = DCL_NOW;
			$organizationAliasModel->created_by = DCLID;
			$organizationAliasModel->Add();
		}

		$addr_type_id = Filter::ToInt($formCollection['addr_type_id']);
		if ($addr_type_id > 0)
		{
			$organizationAddressModel = new OrganizationAddressModel();
			$organizationAddressModel->org_id = $organizationModel->org_id;
			$organizationAddressModel->addr_type_id = $addr_type_id;
			$organizationAddressModel->add1 = $formCollection['add1'];
			$organizationAddressModel->add2 = $formCollection['add2'];
			$organizationAddressModel->city = $formCollection['city'];
			$organizationAddressModel->state = $formCollection['state'];
			$organizationAddressModel->zip = $formCollection['zip'];
			$organizationAddressModel->country = $formCollection['country'];
			$organizationAddressModel->preferred = 'Y';
			$organizationAddressModel->created_on = DCL_NOW;
			$organizationAddressModel->created_by = DCLID;
			$organizationAddressModel->Add();
		}

		$phone_type_id = Filter::ToInt($formCollection['phone_type_id']);
		if ($formCollection['phone_type_id'] > 0 && $formCollection['phone_number'] != '')
		{
			$organizationPhoneModel = new OrganizationPhoneModel();
			$organizationPhoneModel->org_id = $organizationModel->org_id;
			$organizationPhoneModel->phone_type_id = $phone_type_id;
			$organizationPhoneModel->phone_number = $formCollection['phone_number'];
			$organizationPhoneModel->preferred = 'Y';
			$organizationPhoneModel->created_on = DCL_NOW;
			$organizationPhoneModel->created_by = DCLID;
			$organizationPhoneModel->Add();
		}

		$email_type_id = Filter::ToInt($formCollection['email_type_id']);
		if ($formCollection['email_type_id'] > 0 && $formCollection['email_addr'] != '')
		{
			$organizationEmailModel = new OrganizationEmailModel();
			$organizationEmailModel->org_id = $organizationModel->org_id;
			$organizationEmailModel->email_type_id = $email_type_id;
			$organizationEmailModel->email_addr = $formCollection['email_addr'];
			$organizationEmailModel->preferred = 'Y';
			$organizationEmailModel->created_on = DCL_NOW;
			$organizationEmailModel->created_by = DCLID;
			$organizationEmailModel->Add();
		}

		$url_type_id = Filter::ToInt($formCollection['url_type_id']);
		if ($formCollection['url_type_id'] > 0 && $formCollection['url_addr'] != '')
		{
			$organizationUrlModel = new OrganizationUrlModel();
			$organizationUrlModel->org_id = $organizationModel->org_id;
			$organizationUrlModel->url_type_id = $url_type_id;
			$organizationUrlModel->url_addr = $formCollection['url_addr'];
			$organizationUrlModel->preferred = 'Y';
			$organizationUrlModel->created_on = DCL_NOW;
			$organizationUrlModel->created_by = DCLID;
			$organizationUrlModel->Add();
		}		
	}
}