{dcl_validator_init}
<form class="form-horizontal" method="post" action="{$URL_MAIN_PHP}">
	<input type="hidden" name="menuAction" value="{$menuAction}">
	{if $url_type_id}<input type="hidden" name="url_type_id" value="{$url_type_id}">{/if}
	<fieldset>
		<legend>{$TXT_FUNCTION|escape}</legend>
		{dcl_form_control id=url_type_name controlsize=4 label=$smarty.const.STR_CMMN_NAME required=true}
		{dcl_input_text id=url_type_name maxlength=50 value=$VAL_NAME}
		{/dcl_form_control}
	</fieldset>
	<fieldset>
		<div class="row">
			<div class="col-sm-offset-2">
				<input class="btn btn-primary" type="button" onclick="validateAndSubmitForm(this.form);" value="{$smarty.const.STR_CMMN_SAVE}">
				<input class="btn btn-link" type="button" onclick="location.href = '{$URL_MAIN_PHP}?menuAction=UrlType.Index';" value="{$smarty.const.STR_CMMN_CANCEL}">
			</div>
		</div>
	</fieldset>
</form>
<script type="text/javascript">
	$(function() {
		$("#url_type_name").focus();
	});

	function validateAndSubmitForm(form)
	{
		var aValidators = new Array(
				new ValidatorString(form.elements["url_type_name"], "{$smarty.const.STR_CMMN_NAME}")
		);

		for (var i in aValidators)
		{
			if (!aValidators[i].isValid())
			{
				alert(aValidators[i].getError());
				if (typeof(aValidators[i]._Element.focus) == "function")
					aValidators[i]._Element.focus();
				return;
			}
		}

		form.submit();
	}
</script>
