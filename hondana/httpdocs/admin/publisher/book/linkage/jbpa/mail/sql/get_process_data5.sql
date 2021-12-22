SELECT SQL_CALC_FOUND_ROWS
	
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
	'' as null1,
	'' as null2,
	'' as null3,
	preliminary_4,
	concat('\"',preliminary_5,'\"') as real_preliminary_5,
	publisher_1,
	publisher_2,
	preliminary_6,
	'' as null4,
	'' as null5,
	preliminary_7,
	trade_code_1
	
FROM linkage_jbpa as n

WHERE
	n.status = 1
	and data_type_1 = 5
	and exists(
		SELECT * from book as b where b.book_no = n.book_no and b.publisher_no = {$publisher_no}
	);
