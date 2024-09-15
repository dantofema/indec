--
-- PostgreSQL database dump
--

-- Dumped from database version 11.5
-- Dumped by pg_dump version 11.6 (Ubuntu 11.6-1.pgdg18.04+1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: segmentacion; Type: SCHEMA; Schema: -; Owner: laravel
--

CREATE SCHEMA IF NOT EXISTS segmentacion;


SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: adyacencias; Type: TABLE; Schema: segmentacion; Owner: laravel
--

CREATE TABLE IF NOT EXISTS segmentacion.adyacencias (
    shape text,
    prov integer,
    dpto integer,
    codloc integer,
    frac integer,
    radio integer,
    mza integer,
    lado integer,
    mza_ady integer,
    lado_ady integer,
    tipo text
);


--
-- Name: conteos; Type: TABLE; Schema: segmentacion; Owner: laravel
--

CREATE TABLE IF NOT EXISTS segmentacion.conteos (
    tabla text,
    prov integer,
    dpto integer,
    codloc integer,
    frac integer,
    radio integer,
    mza integer,
    lado integer,
    conteo bigint,
    id serial NOT NULL
);


--
-- PostgreSQL database dump complete
--
