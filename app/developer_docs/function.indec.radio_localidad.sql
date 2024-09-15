/*
regenera la relacion radio_localidad de los listados de todos los ePPDDLLL
autor: -h+m
fecha: 2022-04-11 Lu
*/

DROP FUNCTION if exists indec.radio_localidad();
create or replace function indec.radio_localidad()
 returns table (
 radio text, localidad text 
)
language plpgsql volatile
set client_min_messages = 'notice'
as $function$
declare
strSQL text;
rec record;
begin
RAISE NOTICE 'Buscando radios de todas las localidades...';
 FOR rec IN SELECT table_schema, table_name
            FROM information_schema.tables
            WHERE table_schema like 'e________' and table_name = 'listado'
 LOOP
     strSQL := CONCAT_WS(' union ',strSQL,'select prov || dpto || frac || radio, substr( ''' ||
               rec.table_schema || ''',2,8) from ' || 
               rec.table_schema || '.' || rec.table_name);
 END LOOP;

return query
EXECUTE strSQL;

end
$function$

