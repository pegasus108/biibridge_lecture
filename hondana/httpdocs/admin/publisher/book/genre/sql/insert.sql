lock tables genre write;


select @myRight := rgt,
@myDepth := depth + 1
from genre
where publisher_no = {$publisher_no}
{if $root}
and lft = 1
{else}
AND genre_no = {$target_genre}
{/if}
;


update genre
set
lft = lft + 2
where @myRight <= lft
AND publisher_no = {$publisher_no};


update genre
set
rgt = rgt + 2
where @myRight <= rgt
AND publisher_no = {$publisher_no};


select @myNo := coalesce(max(genre_no), 0) + 1
from genre;


insert into genre
(
genre_no,
publisher_no,
name,
lft,
rgt,
depth,
c_stamp
)
values (
@myNo
, {$publisher_no}
, '{$name}'
, @myRight
, @myRight + 1
, @myDepth,
current_timestamp
);


unlock tables;
