--
-- PostgreSQL database dump
--

\restrict gOnnxJRHXH329bdxJaTZpB7TpQToChUNQ67xNeilXGHuQFBsqR80MLwX5CkgVeK

-- Dumped from database version 17.6
-- Dumped by pg_dump version 17.6

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: event_status; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.event_status AS ENUM (
    'ongoing',
    'canceled',
    'completed'
);


ALTER TYPE public.event_status OWNER TO postgres;

--
-- Name: payment_type; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.payment_type AS ENUM (
    'free',
    'paid',
    'pending'
);


ALTER TYPE public.payment_type OWNER TO postgres;

--
-- Name: user_role; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.user_role AS ENUM (
    'admin',
    'peserta'
);


ALTER TYPE public.user_role OWNER TO postgres;

--
-- Name: fungsi_kembalikan_kuota(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.fungsi_kembalikan_kuota() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    UPDATE events 
    SET kuota = kuota + 1 
    WHERE id_event = OLD.id_event;
    
    RETURN OLD;
END;
$$;


ALTER FUNCTION public.fungsi_kembalikan_kuota() OWNER TO postgres;

--
-- Name: fungsi_kurangi_kuota(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.fungsi_kurangi_kuota() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
DECLARE
    v_kuota_sekarang INT;
BEGIN
    SELECT kuota INTO v_kuota_sekarang FROM events WHERE id_event = NEW.id_event;

    IF v_kuota_sekarang > 0 THEN
        UPDATE events 
        SET kuota = kuota - 1 
        WHERE id_event = NEW.id_event;
        RETURN NEW;
    ELSE
        RAISE EXCEPTION 'Gagal daftar: Kuota event sudah penuh!';
    END IF;
END;
$$;


ALTER FUNCTION public.fungsi_kurangi_kuota() OWNER TO postgres;

--
-- Name: sp_daftar_event(integer, integer, public.payment_type, character varying); Type: PROCEDURE; Schema: public; Owner: postgres
--

CREATE PROCEDURE public.sp_daftar_event(IN p_id_user integer, IN p_id_event integer, IN p_status_bayar public.payment_type, IN p_bukti character varying)
    LANGUAGE plpgsql
    AS $$
BEGIN
    
     
    INSERT INTO registrations (id_user, id_event, status_pembayaran, bukti_pembayaran)
    VALUES (p_id_user, p_id_event, p_status_bayar, p_bukti);
 END;
$$;


ALTER PROCEDURE public.sp_daftar_event(IN p_id_user integer, IN p_id_event integer, IN p_status_bayar public.payment_type, IN p_bukti character varying) OWNER TO postgres;

--
-- Name: sp_update_status_event(); Type: PROCEDURE; Schema: public; Owner: postgres
--

CREATE PROCEDURE public.sp_update_status_event()
    LANGUAGE plpgsql
    AS $$
BEGIN
    -- Mengubah menjadi 'ongoing' jika hari ini berada di rentang tanggal event
    UPDATE events
    SET status_event = 'ongoing'
    WHERE CURRENT_DATE >= tanggal_mulai 
      AND CURRENT_DATE <= tanggal_selesai
      AND status_event != 'ongoing';

    -- Mengubah menjadi 'completed' jika tanggal hari ini sudah melewati tanggal selesai
    UPDATE events
    SET status_event = 'completed'
    WHERE CURRENT_DATE > tanggal_selesai
      AND status_event != 'completed';

    RAISE NOTICE 'Sinkronisasi status event ke ongoing/completed berhasil dilakukan.';
END;
$$;


ALTER PROCEDURE public.sp_update_status_event() OWNER TO postgres;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: events; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.events (
    id_event integer NOT NULL,
    nama_event character varying(150) NOT NULL,
    deskripsi text,
    tipe_event character varying(50),
    tanggal_mulai date NOT NULL,
    tanggal_selesai date NOT NULL,
    lokasi character varying(255),
    penyelenggara character varying(100),
    kuota integer DEFAULT 0,
    status_event public.event_status DEFAULT 'ongoing'::public.event_status,
    harga numeric(12,2) DEFAULT 0.00,
    poster_event character varying(255),
    url_sertifikat text,
    info_bayar text
);


ALTER TABLE public.events OWNER TO postgres;

--
-- Name: events_id_event_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.events_id_event_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.events_id_event_seq OWNER TO postgres;

--
-- Name: events_id_event_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.events_id_event_seq OWNED BY public.events.id_event;


--
-- Name: registrations; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.registrations (
    id_regist integer NOT NULL,
    id_user integer,
    id_event integer,
    waktu_regist timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    status_pembayaran public.payment_type NOT NULL,
    bukti_pembayaran character varying(255) DEFAULT NULL::character varying
);


ALTER TABLE public.registrations OWNER TO postgres;

--
-- Name: registrations_id_regist_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.registrations_id_regist_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.registrations_id_regist_seq OWNER TO postgres;

--
-- Name: registrations_id_regist_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.registrations_id_regist_seq OWNED BY public.registrations.id_regist;


--
-- Name: users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users (
    id_user integer NOT NULL,
    nama character varying(100) NOT NULL,
    email character varying(100) NOT NULL,
    password character varying(255) NOT NULL,
    no_telp character varying(15),
    role public.user_role DEFAULT 'peserta'::public.user_role,
    foto_profil character varying(255) DEFAULT 'default.jpg'::character varying
);


ALTER TABLE public.users OWNER TO postgres;

--
-- Name: users_id_user_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.users_id_user_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.users_id_user_seq OWNER TO postgres;

--
-- Name: users_id_user_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.users_id_user_seq OWNED BY public.users.id_user;


--
-- Name: view_event_user; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW public.view_event_user AS
 SELECT r.id_regist,
    r.id_user,
    e.nama_event,
    e.poster_event,
    e.tanggal_mulai,
    e.tanggal_selesai,
    e.lokasi,
    e.status_event,
    e.url_sertifikat,
    e.harga,
    r.waktu_regist,
    r.status_pembayaran,
    r.bukti_pembayaran
   FROM (public.registrations r
     JOIN public.events e ON ((r.id_event = e.id_event)));


ALTER VIEW public.view_event_user OWNER TO postgres;

--
-- Name: view_pendaftar_event; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW public.view_pendaftar_event AS
 SELECT r.id_regist,
    u.id_user,
    u.nama AS nama_mhs,
    u.email,
    e.nama_event,
    r.waktu_regist,
    r.status_pembayaran,
    r.bukti_pembayaran
   FROM ((public.registrations r
     JOIN public.users u ON ((r.id_user = u.id_user)))
     JOIN public.events e ON ((r.id_event = e.id_event)));


ALTER VIEW public.view_pendaftar_event OWNER TO postgres;

--
-- Name: events id_event; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.events ALTER COLUMN id_event SET DEFAULT nextval('public.events_id_event_seq'::regclass);


--
-- Name: registrations id_regist; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.registrations ALTER COLUMN id_regist SET DEFAULT nextval('public.registrations_id_regist_seq'::regclass);


--
-- Name: users id_user; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users ALTER COLUMN id_user SET DEFAULT nextval('public.users_id_user_seq'::regclass);


--
-- Data for Name: events; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.events (id_event, nama_event, deskripsi, tipe_event, tanggal_mulai, tanggal_selesai, lokasi, penyelenggara, kuota, status_event, harga, poster_event, url_sertifikat, info_bayar) FROM stdin;
2	Gempar Batch 2: Workshop Dr. Rulli Nasrullah dan Eddy Wijaya	Workshop mendalam tentang strategi komunikasi digital dan media sosial.	Workshop	2026-01-20	2026-01-20	Cafe Kita Lt. 3, Kebayoran Baru	Himpunan Mahasiswa Ilmu Komunikasi UAG	100	ongoing	0.00	gempar_b2.jpeg	\N	\N
8	Musabaqah Hifdzil Quran (MHQ)	Lomba menghafal Al-Quran tingkat mahasiswa dalam rangkaian Bright Society Fest.	Lomba	2025-12-12	2025-12-13	Menara 165	Bright Society Fest 2025	100	completed	85000.00	mhq_2025.jpeg	https://drive.google.com/drive/folders/contoh-link-mhq	Bank BNI, Rek: 333444, A/N: Siti
5	Gempar with Faiz Sanad	Sesi berbagi inspirasi dan pengalaman bersama praktisi komunikasi Faiz Sanad.	Seminar	2026-02-03	2026-02-03	Auditorium Lt 18, Menara 165	Himpunan Mahasiswa Ilmu Komunikasi UAG	100	ongoing	0.00	gempar_faiz.jpeg	\N	\N
9	Business Case Competition 2025	Kompetisi pemecahan kasus bisnis nyata untuk mengasah kemampuan analitis.	Lomba	2025-12-12	2025-12-13	Menara 165	Bright Society Fest	100	completed	150000.00	bcc_2025.jpeg	https://drive.google.com/drive/folders/contoh-link-bcc	Bank BCA, Rek: 555666, A/N: Panitia Hebat
12	Break The Cycle with Coach Nia Fiani	\N	Workshop	2026-01-02	2026-01-02	Gripa Studio, Jakarta	\N	30	completed	299000.00	1768151898_Workshop.jpeg	https://drive.google.com/drive/folders/contoh-link-psc	\N
11	Digital Poster Competition	Kompetisi desain poster kreatif bertemakan inovasi masa depan.	Lomba	2025-12-12	2025-12-13	Menara 165	Bright Society Fest 2025	100	completed	50000.00	poster_comp.jpeg	https://drive.google.com/drive/folders/contoh-link-poster	Bank BCA, Rek: 555666, A/N: Panitia Hebat
4	Gempar Batch 4: Partnership for The Goals	Seminar tentang kolaborasi strategis untuk mencapai target pembangunan berkelanjutan.	Seminar	2026-02-14	2026-02-14	Auditorium Lt 18, Menara 165	Himpunan Mahasiswa Ilmu Komunikasi UAG	98	ongoing	0.00	gempar_b4.jpeg	\N	\N
3	Gempar Batch 3: Retorika Gen Z	Seni berbicara dan persuasi bagi generasi Z di era digital.	Workshop	2026-01-02	2026-01-02	Auditorium Lt 18, Menara 165	Himpunan Mahasiswa Ilmu Komunikasi UAG	98	completed	0.00	gempar_b3.jpeg	https://drive.google.com/drive/folders/contoh-link-psc	\N
10	Public Speaking Competition 2025	Lomba pidato dan public speaking untuk meningkatkan kepercayaan diri.	Lomba	2025-12-12	2025-12-13	Menara 165	Bright Society Fest 2025	99	completed	85000.00	psc_2025.jpeg	https://drive.google.com/drive/folders/contoh-link-psc	Bank BCA, Rek: 555666, A/N: Panitia Hebat
14	EFI's Guest Lecturer	Handling Questions Techniques	Seminar	2026-01-18	2026-01-18	Auditorium Lt 18, Menara 165	\N	55	ongoing	0.00	1768203969_post1.jpeg	\N	\N
6	Ngopi Vol. 8	Ngobrol Pintar volume 8: Diskusi santai isu terkini mahasiswa komunikasi.	Seminar	2026-03-13	2026-03-13	Auditorium Lt 18, Menara 165	Himpunan Mahasiswa Ilmu Komunikasi UAG	98	ongoing	0.00	ngopi_8.jpeg	\N	\N
7	Pasar Lokal Muda 2026	Festival kewirausahaan muda yang menampilkan produk-produk kreatif lokal.	Festival	2026-01-18	2026-01-18	Mbloc Space, Jakarta Selatan	ESQ Business School	499	ongoing	0.00	pasar_lokal.jpeg	\N	\N
1	Asia Pacific Youth Empowerment Summit 2026	Pertemuan pemimpin muda se-Asia Pasifik untuk pengembangan kapasitas kepemimpinan.	Summit	2026-01-14	2026-01-17	Kuala Lumpur	Youth Empowerment Conference UAG x Asia Pacific Leaders	26	ongoing	3000000.00	summit_2026.jpeg	\N	Bank Mandiri, Rek: 111222, A/N: Andi
\.


--
-- Data for Name: registrations; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.registrations (id_regist, id_user, id_event, waktu_regist, status_pembayaran, bukti_pembayaran) FROM stdin;
1	2	1	2025-12-22 21:01:05.454031	paid	bukti_tf_1.jpg
2	3	2	2025-12-22 21:01:05.454031	free	\N
3	4	8	2025-12-22 21:01:05.454031	paid	bukti_tf_8.jpg
4	5	8	2025-12-22 21:01:05.454031	paid	bukti_tf_8_alt.jpg
5	2	7	2025-12-22 21:01:05.454031	free	\N
10	2	6	2025-12-31 17:47:19.966264	free	\N
11	2	4	2025-12-31 17:47:37.377488	free	\N
13	3	3	2026-01-01 22:54:56.50794	free	\N
14	3	10	2026-01-03 13:56:47.352327	paid	BUKTI_3_1767426940.png
17	9	1	2026-01-10 06:35:20.098102	pending	\N
15	10	1	2026-01-10 06:12:16.355552	paid	BUKTI_10_1768000681.png
21	5	7	2026-01-13 01:18:51.04185	free	\N
22	5	1	2026-01-13 01:20:00.002299	paid	BUKTI_5_1768242041.jpeg
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users (id_user, nama, email, password, no_telp, role, foto_profil) FROM stdin;
1	Admin Sistem UAG	admin@uag.ac.id	admin123	081234567890	admin	admin.jpg
2	Ahmad Fauzi	ahmad.fauzi@student.uag.ac.id	fauzi123	085711223344	peserta	user1.jpg
3	Siti Aminah	siti.aminah@student.uag.ac.id	siti123	085755667788	peserta	user2.jpg
4	Budi Santoso	budi.santoso@student.uag.ac.id	budi123	081299001122	peserta	user3.jpg
5	Dewi Lestari	dewi.lestari@student.uag.ac.id	dewi123	081344556677	peserta	user4.jpg
6	Rizky Ramadhan	rizky.ramadhan@student.uag.ac.id	rizky123	081988776655	peserta	user5.jpg
7	Putri Handayani	putri.handayani@student.uag.ac.id	putri123	081233445566	peserta	user6.jpg
8	Fajar Nugraha	fajar.nugraha@student.uag.ac.id	fajar123	081566778899	peserta	user7.jpg
9	Lani Cahyani	lani.cahyani@student.uag.ac.id	lani123	085611224455	peserta	user8.jpg
10	Hendra Wijaya	hendra.wijaya@student.uag.ac.id	hendra123	081822334455	peserta	user9.jpg
11	Haloo	halo@gmail.com	$2y$10$xd/CjDbWCvZXBYsiq6kNDe6IwPbgtIjE52uYvwknsKDuarKtWsaDG	\N	peserta	default.jpg
\.


--
-- Name: events_id_event_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.events_id_event_seq', 14, true);


--
-- Name: registrations_id_regist_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.registrations_id_regist_seq', 22, true);


--
-- Name: users_id_user_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.users_id_user_seq', 11, true);


--
-- Name: events events_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.events
    ADD CONSTRAINT events_pkey PRIMARY KEY (id_event);


--
-- Name: mv_event_financial_summary; Type: MATERIALIZED VIEW; Schema: public; Owner: postgres
--

CREATE MATERIALIZED VIEW public.mv_event_financial_summary AS
 SELECT e.id_event,
    e.nama_event,
    count(r.id_regist) FILTER (WHERE (r.status_pembayaran = 'paid'::public.payment_type)) AS peserta_lunas,
    sum(e.harga) FILTER (WHERE (r.status_pembayaran = 'paid'::public.payment_type)) AS total_uang_masuk,
    (e.kuota - count(r.id_regist)) AS sisa_kuota
   FROM (public.events e
     LEFT JOIN public.registrations r ON ((e.id_event = r.id_event)))
  GROUP BY e.id_event
  WITH NO DATA;


ALTER MATERIALIZED VIEW public.mv_event_financial_summary OWNER TO postgres;

--
-- Name: registrations registrations_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.registrations
    ADD CONSTRAINT registrations_pkey PRIMARY KEY (id_regist);


--
-- Name: users users_email_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_key UNIQUE (email);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id_user);


--
-- Name: idx_events_status; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_events_status ON public.events USING btree (status_event);


--
-- Name: idx_events_tipe; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_events_tipe ON public.events USING btree (tipe_event);


--
-- Name: idx_registrations_event; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_registrations_event ON public.registrations USING btree (id_event);


--
-- Name: idx_registrations_user; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_registrations_user ON public.registrations USING btree (id_user);


--
-- Name: idx_unique_registration; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX idx_unique_registration ON public.registrations USING btree (id_user, id_event);


--
-- Name: registrations trg_pendaftaran_baru; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER trg_pendaftaran_baru BEFORE INSERT ON public.registrations FOR EACH ROW EXECUTE FUNCTION public.fungsi_kurangi_kuota();


--
-- Name: registrations trg_pendaftaran_batal; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER trg_pendaftaran_batal AFTER DELETE ON public.registrations FOR EACH ROW EXECUTE FUNCTION public.fungsi_kembalikan_kuota();


--
-- Name: registrations fk_event; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.registrations
    ADD CONSTRAINT fk_event FOREIGN KEY (id_event) REFERENCES public.events(id_event) ON DELETE CASCADE;


--
-- Name: registrations fk_user; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.registrations
    ADD CONSTRAINT fk_user FOREIGN KEY (id_user) REFERENCES public.users(id_user) ON DELETE CASCADE;


--
-- Name: users admin_akses_semua_policy; Type: POLICY; Schema: public; Owner: postgres
--

CREATE POLICY admin_akses_semua_policy ON public.users TO role_admin USING (true);


--
-- Name: users peserta_pribadi_policy; Type: POLICY; Schema: public; Owner: postgres
--

CREATE POLICY peserta_pribadi_policy ON public.users FOR SELECT TO role_peserta USING ((id_user = (current_setting('app.current_user_id'::text))::integer));


--
-- Name: users; Type: ROW SECURITY; Schema: public; Owner: postgres
--

ALTER TABLE public.users ENABLE ROW LEVEL SECURITY;

--
-- Name: PROCEDURE sp_daftar_event(IN p_id_user integer, IN p_id_event integer, IN p_status_bayar public.payment_type, IN p_bukti character varying); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON PROCEDURE public.sp_daftar_event(IN p_id_user integer, IN p_id_event integer, IN p_status_bayar public.payment_type, IN p_bukti character varying) TO role_admin;
GRANT ALL ON PROCEDURE public.sp_daftar_event(IN p_id_user integer, IN p_id_event integer, IN p_status_bayar public.payment_type, IN p_bukti character varying) TO role_peserta;


--
-- Name: PROCEDURE sp_update_status_event(); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON PROCEDURE public.sp_update_status_event() TO role_admin;


--
-- Name: TABLE events; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.events TO role_admin;
GRANT SELECT ON TABLE public.events TO role_peserta;


--
-- Name: SEQUENCE events_id_event_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.events_id_event_seq TO role_admin;
GRANT SELECT,USAGE ON SEQUENCE public.events_id_event_seq TO role_peserta;


--
-- Name: TABLE registrations; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.registrations TO role_admin;


--
-- Name: SEQUENCE registrations_id_regist_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.registrations_id_regist_seq TO role_admin;
GRANT SELECT,USAGE ON SEQUENCE public.registrations_id_regist_seq TO role_peserta;


--
-- Name: TABLE users; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON TABLE public.users TO role_admin;


--
-- Name: SEQUENCE users_id_user_seq; Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON SEQUENCE public.users_id_user_seq TO role_admin;
GRANT SELECT,USAGE ON SEQUENCE public.users_id_user_seq TO role_peserta;


--
-- Name: mv_event_financial_summary; Type: MATERIALIZED VIEW DATA; Schema: public; Owner: postgres
--

REFRESH MATERIALIZED VIEW public.mv_event_financial_summary;


--
-- PostgreSQL database dump complete
--

\unrestrict gOnnxJRHXH329bdxJaTZpB7TpQToChUNQ67xNeilXGHuQFBsqR80MLwX5CkgVeK

