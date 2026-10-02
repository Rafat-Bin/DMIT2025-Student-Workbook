SELECT city_name, province FROM cities WHERE 1;
-- gives us just city name and province

SELECT city_name, province FROM cities LIMIT 5;
-- only 5 records returned 

SELECT city_name from cities where province = 'ab';
-- only cities from Alberta

SELECT city_name, province, population from cities order by population DESC;
-- from biggest to smallest city

SELECT city_name, population from cities WHERE province = 'on' AND population > 1000000;
-- all cities of at least 1 million people in ontario

SELECT * FROM cities WHERE is_capital = 1;
-- all capital cities

SELECT * FROM cities where city_name like '%john%';
-- all cities that contain john

SELECT * FROM cities where province = 'ns' or province = 'nb' or province = 'nl' or province = 'pe';
-- all maritime provinces

SELECT * FROM cities where (province = 'ns' or province = 'nb' or province = 'nl' or province = 'pe') AND is_capital = true;
-- maritime capital cities



-- 1. find me the smallest city


-- 2. find all cities with a population between 10,000 and 40,000


-- 3. find all ontario cities from largest to smallest, but only return the records from 3 - 5
