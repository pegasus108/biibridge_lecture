{*lock tables book, opus, book_relate, book_genre, book_series, news_relate,linkage_{$linkage_id_list|@join:', linkage_'}, book_honzuki,yondemill_book write;*}


delete
from book_relate
where
	(
		book_relate.book_no in ( {$book_no_list|@join:','} )
		or book_relate.book_relate_book_no in ( {$book_no_list|@join:','} )
	)and exists(
		select * from book
		where(
				book.book_no = book_relate.book_no
				or book.book_no = book_relate.book_relate_no
			)
		and book.publisher_no = '{$publisher_no|escape}'
	);


delete
from opus
where
	opus.book_no in ( {$book_no_list|@join:','} )
	and exists(
		select * from book
		where book.book_no = opus.book_no
		and book.publisher_no = '{$publisher_no|escape}'
	);


delete
from book_genre
where
	book_genre.book_no in ( {$book_no_list|@join:','} )
	and exists(
		select * from book
		where book.book_no = book_genre.book_no
		and book.publisher_no = '{$publisher_no|escape}'
	);

delete
from book_series
where
	book_series.book_no in ( {$book_no_list|@join:','} )
	and exists(
		select * from book
		where book.book_no = book_series.book_no
		and book.publisher_no = '{$publisher_no|escape}'
	);

delete
from campaign_site_link
where
	campaign_site_link.book_no in ( {$book_no_list|@join:','} )
	and exists(
		select * from book
		where book.book_no = campaign_site_link.book_no
		and book.publisher_no = '{$publisher_no|escape}'
	);

delete
from special_site_link
where
	special_site_link.book_no in ( {$book_no_list|@join:','} )
	and exists(
		select * from book
		where book.book_no = special_site_link.book_no
		and book.publisher_no = '{$publisher_no|escape}'
	);

delete
from book_label
where
	book_label.book_no in ( {$book_no_list|@join:','} )
	and exists(
		select * from book
		where book.book_no = book_label.book_no
		and book.publisher_no = '{$publisher_no|escape}'
	);

delete
from book_ebookstores
where
	book_ebookstores.book_no in ( {$book_no_list|@join:','} )
	and exists(
		select * from book
		where book.book_no = book_ebookstores.book_no
		and book.publisher_no = '{$publisher_no|escape}'
	);

delete
from book_format_book
where
	(
		book_format_book.book_no in ( {$book_no_list|@join:','} )
		or book_format_book.book_no_other in ( {$book_no_list|@join:','} )
	)and exists(
		select * from book
		where(
				book.book_no = book_format_book.book_no
				or book.book_no = book_format_book.book_no_other
			)
		and book.publisher_no = '{$publisher_no|escape}'
	);


{foreach from=$linkage_id_list item=id}

delete from
	linkage_{$id}
where
	linkage_{$id}.book_no in ( {$book_no_list|@join:','} )
	and exists(
		select *
		from book
		where
		book.book_no = linkage_{$id}.book_no
		and book.publisher_no = '{$publisher_no|escape}'
	)
;

{/foreach}

delete
from book_honzuki
where
	book_no in ( {$book_no_list|@join:','} )
	and exists(
		select * from book
		where book.book_no = book_honzuki.book_no
		and book.publisher_no = '{$publisher_no|escape}'
	);

delete
from book
where
	book_no in ( {$book_no_list|@join:','} )
	and publisher_no = '{$publisher_no|escape}';

{if $deleleYondemillList}
delete
from yondemill_book
where
yondemill_id in ({$deleleYondemillList|@join:','} )
;
{/if}

{*unlock tables;*}
