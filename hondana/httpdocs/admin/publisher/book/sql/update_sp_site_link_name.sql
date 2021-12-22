{foreach name=spsite from=$special_site_link_namelist key=k item=v}

UPDATE special_site_link SET
	`imagefile` = '{$v|escape}'

WHERE
	special_site_link_no = {$k}
;
{/foreach}

