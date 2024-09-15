--
-- PostgreSQL database dump
--

-- Dumped from database version 12.2 (Ubuntu 12.2-4)
-- Dumped by pg_dump version 12.2 (Ubuntu 12.2-4)

SET statement_timeout = 0;
SET lock_timeout = 0;

SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', 'public', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

--SET default_table_access_method = heap;

--
-- Name: operativo; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.operativo (
    nombre character varying(50),
    observacion character varying(50),
    id integer NOT NULL
);


--
-- Name: operativo__localidad; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.operativo__localidad (
    localidad_id integer NOT NULL,
    operativo_id integer NOT NULL
);


--
-- Name: operativo__paraje; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.operativo__paraje (
    paraje_id integer NOT NULL,
    operativo_id integer NOT NULL
);


--
-- Name: operativo__base_antartica; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.operativo__base_antartica (
    base_antartica_id integer NOT NULL,
    operativo_id integer NOT NULL
);


--
-- Name: operativo__entidad; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.operativo__entidad (
    entidad_id integer NOT NULL,
    operativo_id integer NOT NULL
);


--
-- Name: operativo__gobierno_local; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.operativo__gobierno_local (
    gobierno_local_id integer NOT NULL,
    operativo_id integer NOT NULL
);


--
-- Name: operativo_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.operativo_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: operativo_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.operativo_id_seq OWNED BY public.operativo.id;


--
-- Name: operativo id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.operativo ALTER COLUMN id SET DEFAULT nextval('public.operativo_id_seq'::regclass);


--
-- PostgreSQL database dump complete
--

