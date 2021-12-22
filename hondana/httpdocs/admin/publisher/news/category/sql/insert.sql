select @myRight := rgt,
@myDepth := depth + 1
from news_category
where publisher_no = '{$publisher_no|escape}'
{if $root}
and lft = 1
{else}
and news_category_no = '{$target_category|escape}'
{/if}
;


update news_category
set
lft = lft + 2
where @myRight <= lft
AND publisher_no = '{$publisher_no|escape}';


update news_category
set
rgt = rgt + 2
where @myRight <= rgt
AND publisher_no = '{$publisher_no|escape}';


select @myNo := coalesce(max(news_category_no), 0) + 1
from news_category;


insert into news_category
(
news_category_no,
publisher_no,
name,
lft,
rgt,
depth,
c_stamp
)
values (
@myNo
, '{$publisher_no|escape}'
, '{$category_name}'
, @myRight
, @myRight + 1
, @myDepth,
current_timestamp
);


