{*lock tables book write;*}

update book set
	`yondemill_id` = {$yondemill_id},
	`yondemill_created_at` =
	CASE WHEN `yondemill_created_at` is null THEN '{$yondemill_created_at}'
	ELSE `yondemill_created_at`
	END

where
	book_no = {$book_no}
;

{*unlock tables;*}
