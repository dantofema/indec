WITH 

schemas AS (
SELECT schemaname as name, 
  sum(pg_relation_size(quote_ident(schemaname) || '.' || quote_ident(tablename)))::bigint as size 
FROM pg_tables
where schemaname ilike 'e%'
GROUP BY schemaname
),

db AS (
SELECT pg_database_size(current_database()) AS size
)

SELECT schemas.name, 
       pg_size_pretty(schemas.size) as absolute_size,
       round(schemas.size::numeric / (SELECT size FROM db) * 100, 2) as relative_size
FROM schemas
;


