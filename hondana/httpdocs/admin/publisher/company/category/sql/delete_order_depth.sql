SELECT nc.*
FROM `company_category` as nc
WHERE nc.company_category_no in ({$listString})
order by nc.depth desc;