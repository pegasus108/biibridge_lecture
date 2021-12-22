lock tables genre, book_genre write;

{foreach name=deleteGenre from=$deleteGenreList item=deleteGenre}

select @myL := lft from genre
where
	genre_no = {$deleteGenre.genre_no};

delete
from genre
where
	genre_no = {$deleteGenre.genre_no};


update genre
set
	lft = lft - 2
where
	lft > @myL
	and publisher_no = {$publisher_no};


update genre
set
	rgt = rgt - 2
where
	rgt > @myL
	and publisher_no = {$publisher_no};


delete from book_genre
where
	genre_no = {$deleteGenre.genre_no};


{/foreach}

unlock tables;
