select avg(price_week) from (select count(1) as views, REPLACE(data, "#/app/item/", "") as item_id, L.price_week from tracking_event
left join (select price_week, id from item) L  on (L.id = REPLACE(data, "#/app/item/", ""))
where data like "%/item/%" and source = 2 and price_week is not null group by item_id order by views asc limit 50) x