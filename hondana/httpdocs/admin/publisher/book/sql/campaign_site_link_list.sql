SELECT
	*

FROM campaign_site_link AS gg

WHERE
publisher_no='{$publisher_no|escape}' AND
book_no='{$book_no|escape}'

ORDER BY `sort` ASC
;