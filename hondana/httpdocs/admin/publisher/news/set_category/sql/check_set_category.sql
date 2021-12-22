select
nnc.*

from news as n
left join news_news_category as nnc on n.news_no = nnc.news_no
left join news_category as nc on nnc.news_category_no = nc.news_category_no

where
n.publisher_no = '{$publisher_no}'
and nc.publisher_no = '{$publisher_no}'
and nnc.news_category_no = '{$news_category_no}'
and nnc.news_no in ({$listString})
;
