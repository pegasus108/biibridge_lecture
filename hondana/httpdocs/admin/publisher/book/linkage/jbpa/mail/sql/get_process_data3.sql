SELECT SQL_CALC_FOUND_ROWS
	data_type_1,
	isbn_1,
	'' as null1,
	'' as null2,
	'' as null3,
	'' as null4,
	'' as null5,
	'' as null6,
	'' as null7,
	'' as null8,
	'' as null9,
	'' as null10,
	'' as null11,
	'' as null12,
	'' as null13,
	'' as null14,
	'' as null15,
	'' as null16,
	'' as null17,
	'' as null18,
	'' as null19,
	'' as null20,
	'' as null21,
	'' as null22,
	'' as null23,
	'' as null24,
	price_1,
	price_change_date_1,
	'' as null25,
	'' as null26,
	'' as null27,
	'' as null28,
	'' as null29,
	'' as null30,
	'' as null31,
	'' as null32,
	'' as null33,
	'' as null34,
	'' as null35

FROM linkage_jbpa as n

WHERE
	n.status = 1
	and data_type_1 = 3
	and exists(
		SELECT * from book as b where b.book_no = n.book_no and b.publisher_no = {$publisher_no}
	);
