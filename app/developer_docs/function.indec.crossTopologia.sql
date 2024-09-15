 CREATE OR REPLACE FUNCTION indec.crosstopologia(esquema character varying, tabla character varying)
   RETURNS table (
    ogc_fid integer,
    toponame character varying,
    edge_id integer,
    wkb_geometry public.geometry,
    geom public.geometry
  )
         LANGUAGE plpgsql
       AS $function$
       DECLARE 
           var_r record;
           estearc character varying;
           miquery character varying;
       topo_sql character varying;
       topo_name character varying;
       edge_sql character varying;
           srid character varying;
          myquery_result character varying;
          id integer;
      BEGIN
      estearc = esquema||'.'||tabla;
      RAISE NOTICE  'Tabla: %', estearc;
      
      CREATE EXTENSION IF NOT EXISTS postgis_topology; 
      
      miquery = format('SELECT st_srid(wkb_geometry) as srid FROM %1$s limit 1 ;'
                       ,estearc); 
     RAISE NOTICE 'Query: %',miquery;
      execute miquery into srid;
      RAISE NOTICE 'SRID: %',srid;
     topo_name := srid||'_topo';
          topo_sql = format('SELECT id FROM topology.topology WHERE name = ''%1$s'';'
                            ,topo_name); 
          EXECUTE topo_sql into id;
          RAISE NOTICE 'SQL % . Con id:%',topo_sql,id;    
          IF (id>0) THEN
              miquery = format('
              -- CREAR TOPOLOGIA
              SELECT topology.TopologySummary(''%1$s'') as myquery_result;'
                               ,topo_name); 
              
              EXECUTE miquery into myquery_result;
          
              RAISE NOTICE 'Topologia ya creada';
              -- TODO: Devolver Id, nombre, summary?
              RAISE NOTICE '%', myquery_result;
          ELSE
              RAISE NOTICE 'No existe topologia';
          END IF;
      
      
      --ARCOS que cruzan topologia.
      edge_sql=format('--ARCOS desde topologia.
                      SELECT distinct ogc_fid, ''%2$s''::character varying toponame, b.edge_id, wkb_geometry, geom 
                      from %1$s.arc a JOIN "%2$s".edge b 
                      on a.wkb_geometry && b.geom and st_crosses(a.wkb_geometry,b.geom)'
       ,esquema,topo_name);
       
       RETURN query EXECUTE edge_sql;
      
     END 
   $function$
