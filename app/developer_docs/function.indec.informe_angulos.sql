CREATE OR REPLACE FUNCTION indec.informe_angulos(esquema character varying, tabla character varying)
 RETURNS TABLE(prov text, depto text, frac text, radio text, mza text, link character varying, lados text, cant_lados bigint, lado_inicial_final text, ok boolean, mensaje text, geom_error geometry, cover text, orden_de_lados text)
 LANGUAGE plpgsql
AS $function$

--/*
-- Titulo: Function SQL Chequeo de validaciones sobre lados
-- Nonmbre: function_informe_angulos.sql
-- Descripción: A partir de los arcos (e00), se genera un arco para cada lado de manzana, 
--              ordenados en sentido horario para cada manzana ("lados_de_manzana"). 
--              En "lado_manzna" se formatea para salida y se calcula el angulo de cada lado, aquí se controla que el aldo no sea discontinuo. 
--              En "orden_lados" se verifica que la conección de fin de lado con incio de lado corresponda con la numeración.
--              Por último la consulta genera el informe donde se verifica: - La numeración de lados debe comenzar en 1. - El numero del mayor lado debe coincidir con la cantidad de lados. 
--              ( count(*)=max(lado) and min(lado)=1 and count(error_msg)=0 and count(distinct lado)=max(lado) and count(error_norte)=0  y no aparece en tabla ''lados_verificados'' as ok )
-- Precondición: Deben estar cargadas las cobberturas eXXXX.e00 en la base de datos postgresql/postgis. En un esquema eXXXX, con las tabla arc
-- Fecha: 28/03/2018
-- Autor: manureta - geoestadistica@indec
-- Version 1.0
--*/
DECLARE 
    var_r record;
    estearc character varying;
    miquery character varying;
BEGIN
estearc = esquema||'.'||tabla;
RAISE NOTICE  'Tabla: %', estearc;

miquery = format('
with e00 as
(
SELECT codigo10, nomencla, codigo20, ancho, anchomed, tipo, nombre, ladoi, ladod, desdei, desded, hastai, hastad, mzai, mzad, codloc20, nomencla10, nomenclai, nomenclad, wkb_geometry,''%1$s''::text cover FROM %2$s
),
lados_de_manzana as (
    select codigo20,mzai||''-''||ladoi as lado_id, mzai as mza, ladoi as lado, avg(anchomed) as anchomed,
        st_linemerge(st_union(st_reverse(wkb_geometry))) as geom,cover
    from e00
    where mzai is not Null and mzai != ''''
    group by codigo20,mzai, ladoi,cover
    union
    select codigo20,mzad||''-''||ladod as lado_id, mzad as mza, ladod as lado, avg(anchomed) as anchomed,
        st_linemerge(st_union(wkb_geometry)) as geom,cover
    from e00
    where mzai is not Null and mzad != ''''
    group by codigo20,mzad, ladod,cover
),
lados_codigos as (
    select codigo20, lado_id, mza, lado,
        st_simplifyVW(st_linemerge(st_union(geom)),10) as geom,cover
    from lados_de_manzana
    group by codigo20,lado_id, mza, lado,cover
),
lado_manzana AS (
    select substring(mza,1,2) as prov,substring(mza,3,3) as depto,substring(mza,6,3) as codloc,substring(mza,9,2) as frac, substring(mza,11,2) radio, substring(mza,13,3) as mza, 
        codigo20,lado_id, mza link, lado, geom, st_azimuth(st_startpoint(geom),st_endpoint(geom)) azimuth,cover,
        CASE WHEN st_geometrytype(geom) != ''ST_LineString'' THEN ''Lado discontinuo'' END as error_msg,
        row_number() OVER w as ranking
    from lados_codigos
        WINDOW w AS (PARTITION BY mza ORDER BY st_y(st_startpoint(geom))  desc,
                                                                            st_x(st_startpoint(geom))  ASC)
    ORDER BY mza,lado
),
lado_manzana_norte AS (
SELECT *,
    CASE WHEN ranking=1 and lado!=1 THEN ''Nodo Norte, no inicia lado 1'' END as error_norte
    FROM lado_manzana ORDER BY mza,lado
), 
orden_lados AS (
    SELECT link, min(test) test FROM (
        SELECT link, lado, max(test) test FROM (
            SELECT l.link,l.lado,
            CASE WHEN l.lado=max(l.lado) OVER (PARTITION BY l.link ) and l2.lado=1 THEN ''OK''
                  WHEN l2.lado-l.lado=1 THEN ''OK''
                  ELSE ''-''
            END as test
            FROM
        lado_manzana_norte l JOIN lado_manzana_norte l2 ON st_endpoint(l.geom)=st_startpoint(l2.geom) and l.link=l2.link
    ) foo
    GROUP BY link,lado
    ORDER BY link,lado ) bar GROUP BY link
), 
angulo_de_giro AS (
        SELECT link, lado, ''Angulo entre: ''||ptoN||''->''||ptoN+1||'' y ''||ptoN+1||''->''||ptoN+2 as donde,
            (
                ST_Azimuth(pto_i,pto_sig)-
                ST_Azimuth(pto_sig, pto_sig_sig) 
            )as angle,
    			ST_Azimuth(pto_i,pto_sig) as azimuth_1,
                ST_Azimuth(pto_sig,pto_sig_sig) as azimuth_2
    FROM (
            SELECT l.link,l.lado,ST_NPoints(geom) as cant_points,generate_series(1, ST_NPoints(geom)-1) ptoN,
            ST_PointN(
                        geom,
                        generate_series(1, ST_NPoints(geom)-2)
                       ) pto_i,
		        ST_PointN(
                        geom,
                        generate_series(2, ST_NPoints(geom)-1)
                       ) pto_sig,
						ST_PointN(
                        geom,
                        generate_series(3, ST_NPoints(geom))
                       ) pto_sig_sig
	          FROM
        lado_manzana l WHERE ST_NPoints(geom)>2
    ) foo
    WHERE st_distance(pto_i,pto_sig)>10 and st_distance(pto_sig,pto_sig_sig) > 10
    GROUP BY link,lado,pto_i,pto_sig,pto_sig_sig,ptoN
    ORDER BY link,lado )
, lado_angulado as (
SELECT link,string_Agg(distinct lado||'' (''||round(angle_grados::numeric,1)||''°)'','','') lados FROM (
	SELECT *,CASE WHEN abs(angle) < pi() THEN abs(angle)/(2*pi())*360 ELSE (abs(angle)/(2*pi())*360) - 180 END as angle_grados                         

    FROM angulo_de_giro WHERE abs(angle) BETWEEN ((3/8.0)*pi()) and ((13.0/8)*pi())
)foo2 GROUP BY link
    )
SELECT prov,depto,frac,radio,mza,lmn.link,
    string_agg(lmn.lado::text||''(''||codigo20||'')'','','' order by lmn.lado) "lados(codigo20)",
	count(*) as cant_lados,
    min(lmn.lado)||'' -> ''||max(lmn.lado) lado_inicial_final,
	CASE WHEN
	count(*)=max(lmn.lado) and min(lmn.lado)=1 and count(error_msg)=0 and count(distinct lmn.lado)=max(lmn.lado) and count(error_norte)=0
	and min(test)=''OK'' and lmn.link not in ( select link from lado_angulado)
--    or lmn.link in ( select lado_id from lados_verificados) 
    THEN true
    ELSE false
    END
    as ok,
    concat_ws(''. '',
        string_agg(distinct lmn.lado::text||'':''||error_msg,'', ''),
        string_agg(distinct lmn.lado::text||'':''||error_norte,'', ''),          
    CASE WHEN min(lmn.lado)!=1 THEN ''No comienza con el lado 1'' END,
    CASE WHEN count(*)!=max(lmn.lado) THEN ''No coincide la cantidad de lados con el numero del lado mayor'' END,
    CASE WHEN count(distinct lmn.lado)!=count(*) THEN ''Existen lados repetidos'' END,
    CASE WHEN min(test)!=''OK'' THEN ''Los lados estan desordenados'' END,
    CASE WHEN lmn.link in ( select link from lado_angulado) THEN ''Angulo a revisar'' END,
		string_agg(distinct lados,'','')
             ) mensaje,
    st_union(lmn.geom) geom,
    cover, min(test) as orden_de_lados
FROM lado_manzana_norte lmn 
			LEFT JOIN orden_lados ol ON ol.link=lmn.link
			LEFT JOIN lado_angulado la ON la.link=lmn.link
GROUP BY prov,depto,frac,radio,mza,lmn.link,cover
order by ok,min(test) desc,count(*)=max(lmn.lado), mensaje,link;
',esquema,estearc); 

--RAISE NOTICE 'SQL %',miquery;
RETURN query EXECUTE miquery;
END
$function$ 
