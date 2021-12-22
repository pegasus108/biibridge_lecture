lock tables author, opus write;



delete
from opus
where
	author_no in ( {$author_no_list|@join:','} )
	and exists(
		select * from author
		where author.author_no = opus.author_no
		and author.publisher_no = {$publisher_no}
	);

delete
from author
where
	author_no in ( {$author_no_list|@join:','} )
	and publisher_no = {$publisher_no};



unlock tables;

{if $useJpoBookList}
lock tables book write;
{foreach name=useJpoBookList from=$useJpoBookList item=book}
update book set sync_allowed = null where book_no = {$book.book_no|escape};
{/foreach}
unlock tables;
{/if}