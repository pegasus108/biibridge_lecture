SELECT count(*) as recommend_count
FROM book
WHERE
{if $book_no_list}book_no not in({$book_no_list|@join:","}) and {/if}
recommend_status = 1 and publisher_no = {$publisher_no};