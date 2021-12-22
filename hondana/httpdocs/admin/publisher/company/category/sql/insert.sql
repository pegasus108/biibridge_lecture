select @myRight := rgt,
@myDepth := depth + 1
from company_category
where publisher_no = '{$publisher_no|escape}'
{if $root}
and lft = 1
{else}
and company_category_no = '{$target_category|escape}'
{/if}
;


update company_category
set
lft = lft + 2
where @myRight <= lft
AND publisher_no = '{$publisher_no|escape}';


update company_category
set
rgt = rgt + 2
where @myRight <= rgt
AND publisher_no = '{$publisher_no|escape}';


select @myNo := coalesce(max(company_category_no), 0) + 1
from company_category;


insert into company_category
(
company_category_no,
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


