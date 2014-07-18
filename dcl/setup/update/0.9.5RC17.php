<?php
function dcl_upgrade0_9_5RC17()
{
	global $phpgw_setup, $setup_info;

	$phpgw_setup->oProc->Query("INSERT INTO dcl_config (dcl_config_name, dcl_config_field, dcl_config_varchar) VALUES ('DCL_FORCE_SECURE_COOKIE', 'dcl_config_varchar', 'N')");

	$phpgw_setup->oProc->Query("UPDATE dcl_config SET dcl_config_varchar='0.9.5RC18' WHERE dcl_config_name='DCL_VERSION'");

	$setup_info['dcl']['currentver'] = '0.9.5RC18';
	return $setup_info['dcl']['currentver'];
}