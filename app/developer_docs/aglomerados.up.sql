--
-- PostgreSQL database dump
--

-- Dumped from database version 9.5.16
-- Dumped by pg_dump version 9.5.16

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', 'public' , false);
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: aglomerados; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.aglomerados (
    id integer NOT NULL,
    codigo character(4),
    nombre character varying,
    fecha_desde timestamp without time zone,
    fecha_hasta timestamp without time zone,
    observacion_id integer,
    geometria_id integer,
    tipo_de_poblacion_id integer
);


--
-- Name: aglomerado_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.aglomerado_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: aglomerado_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.aglomerado_id_seq OWNED BY public.aglomerados.id;


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.aglomerados ALTER COLUMN id SET DEFAULT nextval('public.aglomerado_id_seq'::regclass);


--
-- Name: aglomerados_codigo_index; Type: INDEX; Schema: public; Owner: laravel
-- Name: aglomerados_nombre_index; Type: INDEX; Schema: public; Owner: laravel
--

CREATE INDEX aglomerados_nombre_index ON public.aglomerados USING btree (nombre);
CREATE INDEX aglomerados_codigo_index ON public.aglomerados USING btree (codigo);


--
-- PostgreSQL database dump complete
--

