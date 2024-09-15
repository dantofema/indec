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
-- Name: fuente; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.fuente (
    id integer NOT NULL,
    nombre character varying(50)
);


--
-- Name: fuente_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.fuente_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: fuente_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.fuente_id_seq OWNED BY public.fuente.id;


--
-- Name: fuente id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.fuente ALTER COLUMN id SET DEFAULT nextval('public.fuente_id_seq'::regclass);


--
-- PostgreSQL database dump complete
--

