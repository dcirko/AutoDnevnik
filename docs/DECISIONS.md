# Važne odluke

Ovdje bilježimo potvrđene odluke, njihov razlog i eventualne kasnije zamjene. Otvoreni prijedlozi ostaju u `ARCHITECTURE.md` i `TASKS.md` dok ih ne potvrdimo.

## D-001 — Postupni razvoj s prioritetom zahtjeva kolegija
- **Datum:** 2026-10-18
- **Status:** potvrđeno korisnikovim početnim smjernicama
- **Odluka:** prvo ispuniti obavezne zahtjeve kolegija, a dodatne mogućnosti uvoditi naknadno. Izbjegavati nepotreban overengineering.
- **Razlog:** projekt mora ostati izvediv, razumljiv i usklađen s kriterijima kolegija.

## D-002 — Početna dokumentacija u pet datoteka
- **Datum:** 2026-10-18
- **Status:** potvrđeno korisnikovim početnim smjernicama
- **Odluka:** koristiti `PROJECT.md`, `REQUIREMENTS.md`, `ARCHITECTURE.md`, `TASKS.md` i `DECISIONS.md` u `docs/`. API, bazu i testiranje zasad opisivati u postojećim dokumentima.
- **Razlog:** održati mali broj korisnih dokumenata. `PROJECT.md` i `REQUIREMENTS.md` glavni su izvor istine; `TASKS.md` prati rad, a ovaj dokument pamti odluke i razloge.
- **Pravilo održavanja:** kod većih promjena ažurirati pogođene dokumente u istoj promjeni.

## D-003 — Tehnološki okvir i API
- **Datum:** 2026-10-18
- **Status:** potvrđeno korisnikovim početnim smjernicama
- **Odluka:** PHP backend, relacijska baza, HTML/CSS/JavaScript frontend, vlastiti REST API koji aplikacija koristi, JSON kao primarni format i barem jedan vanjski API. Razvoj pratiti kroz Git/GitHub.
- **Razlog:** usklađenost s projektnim smjerom i navedenim zahtjevima kolegija.
- **Otvoreno:** MySQL ili PostgreSQL, pružatelj vanjskog API-ja i detalji implementacije. VIN dekoder ima prioritet pri izboru integracije.

## D-004 — Uvjet za PHP framework
- **Datum:** 2026-10-18
- **Status:** potvrđeno korisnikovim početnim smjernicama
- **Odluka:** framework koristiti samo ako postoji opravdana potreba i ako je dopušten ili dogovoren s profesorom.
- **Razlog:** jednostavnost razvoja i poštovanje pravila kolegija.
- **Napomena:** nijedan framework još nije odabran; ni pristup bez frameworka još nije konačno potvrđen.

## D-005 — Samostalan rad i odobrena tema
- **Datum:** 2026-10-19
- **Status:** potvrđeno korisnikovim odgovorom
- **Odluka:** AutoDnevnik se izrađuje samostalno; korisnik potvrđuje da je tema odobrena.
- **Otvoreno:** točan LMS rok, status formalne prijave i zasebna tema/predložak seminara. Okvirni kraj godine nije potvrđeni datum predaje.

## D-006 — Opseg prve cjelovite verzije
- **Datum:** 2026-10-19
- **Status:** potvrđeno korisnikovim odgovorom na prijedlog opsega
- **Odluka:** vlastita vozila, servisi, gorivo/kilometraža, troškovi, jednostavan dashboard i podsjetnici unutar aplikacije, uz obavezni javni dio, CMS, administraciju i API-je.
- **Razlog:** zaokružen tematski proizvod uz kontroliran opseg. E-mail podsjetnici nisu uključeni u prvu verziju.
- **Otvoreno:** polja, obračuni i tehnički izbori ostaju prijedlozi u `ARCHITECTURE.md`.

## D-007 — Plan prema profesorovim tjednim fazama
- **Datum:** 2026-10-19
- **Status:** prema korisnikovoj uputi i dostavljenom rasporedu
- **Odluka:** pratiti osam faza iz detaljnih uputa (1.–2., 3.–4., 5.–6., 7.–8., 9.–10., 11.–12., 13.–14. i 15. tjedan), s tematskim modulima uklopljenima u isti plan.
- **Razlog:** uskladiti razvoj s kolegijem. API nastaje uz prve podatkovne funkcionalnosti; cjelovita provjera i dokumentiranje pripadaju 9.–10. tjednu.
- **Napomena:** rokove potvrđuje LMS. Sigurnost, provjere i seminar prate rad tijekom cijelog semestra.

## Provjera izvora — 2026-10-19
Ovo su zahtjevi dostavljenih izvora, a ne novi korisnikovi tehnički odabiri:
- `upute.txt`: JSON ili XML uz pripadajuću shemu za barem jedan unos. Zbog D-003 planiramo obaveznu JSON Schemu.
- `upute.txt`: vlastita PHP struktura je dopuštena; framework zahtijeva dogovor s nastavnikom. Framework nije odabran.
- `upute 2.txt`: javni GitHub, YouTube Unlisted/Public, .txt s poveznicama, seminar i Teams materijali dio su predaje. Plan ih evidentira; objava i slanje nisu izvršeni.
- `Uvodno predavanje.pdf`: samostalni seminar i prezentacija; posebne upute za seminar povezane su u PDF-u, ali još nisu pregledane.

## D-008 — Docker i početni tehnički paket
- **Datum:** 2026-10-20
- **Status:** korisnik odabrao Docker nakon ponuđenog PHP/MySQL/Apache/Composer paketa; verzijske grane i konfiguracija pripremljene tijekom faze 1
- **Odluka:** PHP 8.4 bez frameworka, PDO i MySQL 8.4, HTML/CSS/obični JavaScript, PHP sesije i CSRF zaštita. Docker Compose s dva servisa: PHP/Apache s Composerom 2 i MySQL s trajnim volumenom.
- **Razlog:** prati tehnologije kolegija i izbjegava zasebne instalacije PHP-a, Apachea i baze na Windowsu. Vlastita PHP struktura izričito je dopuštena u uputama.
- **Granica:** razvojno okruženje, ne gotova javna instalacija. Shema i aplikacijska logika dolaze u sljedećim fazama. Rezultat pokretanja vodi se u `TASKS.md`.

## D-009 — NHTSA vPIC za početnu VIN integraciju
- **Datum:** 2026-10-20
- **Status:** odabrano tijekom faze 1 nakon provjere službene dokumentacije/FAQ-a i stvarnog javnog primjer-upita
- **Odluka:** NHTSA vPIC za dopunu podataka pri unosu vozila; backend zahtjev, timeout, korisnička potvrda podataka i ručni unos kao alternativa.
- **Razlog:** javno dostupan servis bez registracije i API ključa, podržava JSON i ima smislenu vezu s temom projekta.
- **Ograničenje:** nije potpuni europski VIN dekoder. Američko tržište određuje pokrivenost; nepotpun rezultat i ErrorCode moraju se obraditi. Prije integracije provjeriti reprezentativne primjere.
- **Izvori:** https://vpic.nhtsa.dot.gov/api/ i https://vpic.nhtsa.dot.gov/api/home/index/faq ; rezultat provjere u `ARCHITECTURE.md`.

## D-010 — Javni repozitorij i lokalni nastavni materijali
- **Datum:** 2026-10-20
- **Status:** korisnik potvrdio GitHub račun `dcirko`; priprema repozitorija dio je tražene faze 1
- **Odluka:** repozitorij `dcirko/AutoDnevnik`, grana `main`. Verzionaraju se vlastiti projektni dokumenti i kod; izvorne upute/PDF, lokalni .env, podaci i ovisnosti isključeni su kroz .gitignore.
- **Razlog:** javni repozitorij treba sadržavati projekt i dokumentaciju bez nastavnih pristupnih podataka i lokalnih tajni.

## D-011 — Tema seminara i ritam rada
- **Datum:** 2026-10-24
- **Status:** potvrđeno korisnikovom porukom
- **Odluka:** tema seminara je „Usporedba REST i SOAP web servisa u modernim web aplikacijama“. Rok projekta je do kraja semestra; napredak objavljujemo kroz smislene commitove svaki tjedan ili svaka dva tjedna.
- **Granica:** seminar uspoređuje REST i SOAP; aplikacija i dalje koristi dogovoreni REST API. Točan kalendarski datum, predložak i formalni status prijave još treba provjeriti.

## D-012 — Odvojeno planiranje seminara i projekta
- **Datum:** 2026-10-24
- **Status:** izričito pojašnjeno korisnikovom porukom
- **Odluka:** seminar i projekt AutoDnevnik su zasebne cjeline. Osam razvojnih faza odnosi se na aplikaciju i njezinu dokumentaciju; pisanje seminara nije projektna faza niti preduvjet za fazu 2.
- **Napomena:** zahtjevi profesora za paket predaje ostaju evidentirani, ali ne znače da seminar postaje dio implementacije AutoDnevnika.

## D-013 — Električna vozila i evidencija energije
- **Datum:** 2026-10-24
- **Status:** potvrđeno korisnikovim odgovorima u fazi 2
- **Odluka:** prva verzija uključuje benzin, dizel i električna vozila. Zajednička evidencija energije sprema točenja u litrama i punjenja u kWh. Električna vozila prate kupljenu energiju i troškove, bez stanja baterije i procjene kWh/100 km.
- **Razlog:** pokriti električna vozila bez lažnog izračuna potrošnje iz nepotpunih podataka. Jedinice se ne miješaju. LPG i hibridne pogone zasad ne uvodimo.

## D-014 — Troškovi, podsjetnici, vlasništvo i brisanje
- **Datum:** 2026-10-24
- **Status:** potvrđeno korisnikovim odgovorima u fazi 2
- **Odluka:** servis, energija i dodatni troškovi zasebni su izvori koji se u ukupnom trošku zbrajaju jednom. Podsjetnik ima datum, kilometražu ili oba praga, a dospijeva kad je dosegnut bilo koji postavljeni prag.
- **Privatnost:** jedno vozilo pripada jednom korisniku. Urednik/admin mogu voditi svoja vozila, ali nemaju pristup tuđim privatnim evidencijama.
- **Brisanje:** vozilo se nakon potvrde briše s pripadajućim evidencijama; korisnici se deaktiviraju. Detaljne FK akcije i buduća transakcija brisanja opisani su u arhitekturi.

## D-015 — Početna shema i mali PHP temelj
- **Datum:** 2026-10-24
- **Status:** provedeno tijekom faze 2 u okviru dogovorenih zahtjeva
- **Odluka:** početna SQL shema s deset aplikacijskih tablica i evidencijom verzije; vlastita PHP struktura s Composer PSR-4 autoloadom, PDO, odvojenim JSON odgovorima i HTML predloškom. Bez vanjskih PHP biblioteka u ovoj fazi.
- **Razlog:** ponovljiva instalacija, stvarni ključevi/ograničenja i čitljiva organizacija bez gradnje vlastitog frameworka. Instalacija ne briše postojeće tablice; demo unos ne prepisuje postojeće korisnike.
- **Provjera:** izolirana instalacija, 20 transakcijskih provjera baze, HTTP odgovori i nedostupnost privatnih putanja. Autentifikacija, poslovne rute i CMS ostaju u sljedećim fazama.
