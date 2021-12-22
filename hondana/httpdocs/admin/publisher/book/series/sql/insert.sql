lock tables series write;


select @myRight := rgt,
@myDepth := depth + 1
from series
where publisher_no = {$publisher_no}
{if $root}
and lft = 1
{else}
and series_no = {$target_series}
{/if}
;


update series
set
lft = lft + 2
where @myRight <= lft
AND publisher_no = {$publisher_no};


update series
set
rgt = rgt + 2
where @myRight <= rgt
AND publisher_no = {$publisher_no};


select @myNo := coalesce(max(series_no), 0) + 1
from series;


insert into series
(
series_no,
publisher_no,
name,
kana,
lft,
rgt,
depth,
c_stamp
)
values (
@myNo
, {$publisher_no}
, '{$name}'
, '{$kana}'
, @myRight
, @myRight + 1
, @myDepth,
current_timestamp
);


unlock tables;
