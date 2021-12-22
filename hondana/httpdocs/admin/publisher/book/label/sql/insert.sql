lock tables label write;


select @myRight := rgt,
@myDepth := depth + 1
from label
where publisher_no = {$publisher_no}
{if $root}
and lft = 1
{else}
AND label_no = {$target_label}
{/if}
;


update label
set
lft = lft + 2
where @myRight <= lft
AND publisher_no = {$publisher_no};


update label
set
rgt = rgt + 2
where @myRight <= rgt
AND publisher_no = {$publisher_no};


select @myNo := coalesce(max(label_no), 0) + 1
from label;


insert into label
(
label_no,
publisher_no,
name,
url,
lft,
rgt,
depth,
c_stamp
)
values (
@myNo
, {$publisher_no}
, '{$name}'
, '{$url}'
, @myRight
, @myRight + 1
, @myDepth,
current_timestamp
);


unlock tables;
