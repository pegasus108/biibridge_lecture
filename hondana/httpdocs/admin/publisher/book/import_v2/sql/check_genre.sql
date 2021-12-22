SELECT
g.*

FROM genre AS g,
genre as pg

WHERE
g.publisher_no={$publisher_no}
and pg.publisher_no={$publisher_no}
and g.name = '{$name}'
and g.depth = {$depth}
and pg.genre_no = {$target_genre}
and pg.lft < g.lft
and pg.rgt > g.rgt

limit 1
;