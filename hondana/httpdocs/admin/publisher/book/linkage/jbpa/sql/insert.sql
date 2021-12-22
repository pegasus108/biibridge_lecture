{* lock tables linkage_jbpa, linkage_shared_field write; *}

INSERT INTO
	linkage_jbpa(
		book_no,
		status,
		set_date,
		data_type_1,
		isbn_1,
		category_1,
		name_1,
		kana_1,
		preliminary_1,
		sub_1,
		version_1,
		preliminary_2,
		series_1,
		series_kana_1,
		preliminary_3,
		author_1,
		author_type_1,
		author_kana_1,
		author_2,
		author_type_2,
		author_kana_2,
		author_3,
		author_type_3,
		author_kana_3,
		book_date_1,
		release_date_1,
		book_size_2,
		page_1,
		set_code_1,
		price_1,
		price_change_date_1,
		price_special_1,
		price_special_policy_1,
		preliminary_4,
		preliminary_5,
		publisher_1,
		publisher_2,
		preliminary_6,
		out_status_1,
		out_date_1,
		preliminary_7,
		trade_code_1,
		c_stamp
	)
	VALUES(
		{$book.book_no|escape},
		1,
		current_timestamp,
		'{$data_type_1}',
		'{$isbn_1}',
		'{$category_1}',
		'{$name_1}',
		'{$kana_1}',
		'{$preliminary_1}',
		'{$sub_1}',
		'{$version_1}',
		'{$preliminary_2}',
		'{$series_1}',
		'{$series_kana_1}',
		'{$preliminary_3}',
		'{$author_1}',
		'{$author_type_1}',
		'{$author_kana_1}',
		'{$author_2}',
		'{$author_type_2}',
		'{$author_kana_2}',
		'{$author_3}',
		'{$author_type_3}',
		'{$author_kana_3}',
		'{$book_date_1_year}{$book_date_1_month}',
		'{$release_date_1_year}{$release_date_1_month}{$release_date_1_day}',
		'{$book_size_2}',
		'{$page_1}',
		'{$set_code_1}',
		'{$price_1}',
		'{$price_change_date_1_year}{$price_change_date_1_month}{$price_change_date_1_day}',
		'{$price_special_1}',
		'{$price_special_policy_1_year}{$price_special_policy_1_month}{$price_special_policy_1_day}',
		'{$preliminary_4}',
		'{if $data_type_1 == 5}{$preliminary_5}{else}{$preliminary_5|@join:','}{/if}',
		'{$publisher_1}',
		'{$publisher_2}',
		'{$preliminary_6}',
		'{$out_status_1}',
		'{$out_date_1_year}{$out_date_1_month}{$out_date_1_day}',
		'{$preliminary_7_year}{$preliminary_7_month}{$preliminary_7_day}',
		'{$trade_code_1}',
		now()
	)
ON DUPLICATE KEY
	UPDATE
		status = values(status),
		set_date = values(set_date),
		data_type_1 = values(data_type_1),
		isbn_1 = values(isbn_1),
		category_1 = values(category_1),
		name_1 = values(name_1),
		kana_1 = values(kana_1),
		preliminary_1 = values(preliminary_1),
		sub_1 = values(sub_1),
		version_1 = values(version_1),
		preliminary_2 = values(preliminary_2),
		series_1 = values(series_1),
		series_kana_1 = values(series_kana_1),
		preliminary_3 = values(preliminary_3),
		author_1 = values(author_1),
		author_type_1 = values(author_type_1),
		author_kana_1 = values(author_kana_1),
		author_2 = values(author_2),
		author_type_2 = values(author_type_2),
		author_kana_2 = values(author_kana_2),
		author_3 = values(author_3),
		author_type_3 = values(author_type_3),
		author_kana_3 = values(author_kana_3),
		book_date_1 = values(book_date_1),
		release_date_1 = values(release_date_1),
		book_size_2 = values(book_size_2),
		page_1 = values(page_1),
		set_code_1 = values(set_code_1),
		price_1 = values(price_1),
		price_change_date_1 = values(price_change_date_1),
		price_special_1 = values(price_special_1),
		price_special_policy_1 = values(price_special_policy_1),
		preliminary_4 = values(preliminary_4),
		preliminary_5 = values(preliminary_5),
		publisher_1 = values(publisher_1),
		publisher_2 = values(publisher_2),
		preliminary_6 = values(preliminary_6),
		out_status_1 = values(out_status_1),
		out_date_1 = values(out_date_1),
		preliminary_7 = values(preliminary_7),
		trade_code_1 = values(trade_code_1)
;


{foreach from=$fieldList item=field}

INSERT INTO
	linkage_shared_field(
		field_id,
		book_no,
		table_id
	)values(
		'{$field}',
		{$book.book_no|escape},
		'jbpa'
	)
on DUPLICATE KEY
	UPDATE
		table_id = VALUES(table_id)
;

{/foreach}


{* unlock tables; *}
