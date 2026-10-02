-- update the population and trivia of city id 20
UPDATE cities
    set trivia = 'This city is known for the beautiful parks' ,
    population = 12345
    WHERE cid = 20;



-- increase the population of all cities in AB and SK by 1000

update cities
    set population = population + 1000

    where province in ('ab','sk')