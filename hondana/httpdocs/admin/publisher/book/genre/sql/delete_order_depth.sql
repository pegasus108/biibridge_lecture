SELECT nc.*
FROM `genre` as nc
WHERE nc.genre_no in ({$listString})
order by nc.depth desc;