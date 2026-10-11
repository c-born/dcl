{extends file="_Layout.tpl"}
{block name=title}{$TXT_TITLE|escape}{/block}
{block name=content}
<h2>{$TXT_TITLE|escape}</h2>
<p><strong>{$TXT_DCL|escape}:</strong> {$VAL_DCLVERSION|escape}</p>
<p><strong>Build:</strong> {$VAL_BUILD|escape}</p>
{if $VAL_COMMITDATE != ''}<p><strong>Last change:</strong> {$VAL_COMMITDATE|escape}</p>{/if}
{if $VAL_BUILT != ''}<p><strong>Built:</strong> {$VAL_BUILT|escape}</p>{/if}
<p><strong>PHP:</strong> {$VAL_PHPVERSION|escape}</p>
<h2>{$TXT_YOURVER|escape}</h2>
<p><strong>{$TXT_YOURIP|escape}:</strong> {$VAL_REMOTEADDR|escape}</p>
<p><strong>{$TXT_YOURBROWSER|escape}:</strong> {$VAL_HTTPUSERAGENT|escape}</p>
{/block}