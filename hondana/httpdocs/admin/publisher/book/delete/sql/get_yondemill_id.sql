
select yondemill_id from book where book_no in({$book_no_list|@join:','});


