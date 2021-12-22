SELECT nc.*
FROM `news_category` as nc
WHERE nc.news_category_no in ({$listString})
order by nc.depth desc;