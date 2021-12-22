SELECT SQL_CALC_FOUND_ROWS
	issuer_1,
	issuer_kana_1,
	publisher_2,
	publisher_kana_2,
	name_1,
	kana_1,
	volume_1,
	sub_kana_1,
	sub_1,
	author_1,
	author_kana_1,
	author_note_1,
	author_2,
	author_kana_2,
	author_note_2,
	series_1,
	release_date_1,
	content_1,
	price_1,
	price_special_1,
	price_special_policy_1,
	isbn_1,
	magazine_code_1,
	distribution_type_1,
	book_size_1,
	page_1,
	order_status_1,
	circulation_1,
	genre_1,
	genre_2,
	target_1,
	rubi_status_1,
	version_release_1,
	note_1,
	appendices_status_1,
	appendices_type_1,
	appendices_other_1,
	appendices_loan_1,
	appendix_1,
	win_info_1,
	reader_page_status_1,
	reader_page_1,
	unaccompanied_status_1,
	explain_1,
	by_format_1,
	by_obi_1,
	representative_editor_1,
	representative_comment_1,
	conflicts_1,
	typist_1,
	typist_tel_1,
	type_date_1

FROM linkage_trc as n

WHERE
	n.status = 1
	and exists(
		SELECT * from book as b where b.book_no = n.book_no and b.publisher_no = {$publisher_no}
	);