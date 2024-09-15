--
-- PostgreSQL database dump
--

-- Dumped from database version 9.5.16
-- Dumped by pg_dump version 9.5.16

SET client_encoding = 'UTF8';
SELECT pg_catalog.set_config('search_path', 'public' , false);

--
-- Name: historico_provincia; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE IF NOT EXISTS public.historico_provincia
(
    nuevo_id integer NOT NULL,
    viejo_id integer NOT NULL,
    CONSTRAINT historico_provincia_pkey PRIMARY KEY (nuevo_id, viejo_id),
    CONSTRAINT fk_nuevo_provincia FOREIGN KEY (nuevo_id)
        REFERENCES public.provincia (id) MATCH SIMPLE
        ON UPDATE NO ACTION
        ON DELETE NO ACTION,
    CONSTRAINT fk_viejo_provincia FOREIGN KEY (viejo_id)
        REFERENCES public.provincia (id) MATCH SIMPLE
        ON UPDATE NO ACTION
        ON DELETE NO ACTION
)


-- Crea tabla operativo_provincia
CREATE TABLE IF NOT EXISTS public.operativo_provincia
(
    provincia_id integer NOT NULL,
    operativo_id integer NOT NULL,
    CONSTRAINT pkoperativo_provincia PRIMARY KEY (provincia_id, operativo_id),
    CONSTRAINT fk_operativo_provincia_provincia FOREIGN KEY (provincia_id)
        REFERENCES public.provincia (id) MATCH SIMPLE
        ON UPDATE NO ACTION
        ON DELETE NO ACTION,
    CONSTRAINT fk_operativo_provincia_operativo FOREIGN KEY (operativo_id)
        REFERENCES public.operativo (id) MATCH SIMPLE
        ON UPDATE NO ACTION
        ON DELETE NO ACTION
)