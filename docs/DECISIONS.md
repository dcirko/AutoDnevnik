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
- **Kasnija izmjena:** D-017 zamjenjuje odabir običnog JavaScripta Angularom i TypeScriptom; ostali odabiri ostaju.

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

## D-016 — Lokalni GUI pristup bazi i zaseban ER prikaz
- **Datum:** 2026-10-24
- **Status:** prema korisnikovu zahtjevu za pregled baze i izdvojenu sliku dijagrama
- **Odluka:** MySQL je lokalnom klijentu dostupan na `127.0.0.1:3307`, uz postojeći aplikacijski račun. PHP u Dockeru i dalje koristi `db:3306`. DBeaver je već instaliran na računalu; SQL Server/SSMS ne uvodimo u tehnološki paket.
- **Dijagram:** PNG i SVG datoteke odvojene su od Markdowna, uz zaseban Mermaid pregled glavnih ključeva. Dokumenti sadrže poveznice na te datoteke.
- **Provjera:** lokalni port prihvaća MySQL protokol; trajni volumen i postojeći podaci sačuvani su pri ponovnom stvaranju DB kontejnera.

## D-017 — Angular i TypeScript frontend
- **Datum:** 2026-10-25
- **Status:** izričito odabrano korisnikovom porukom
- **Odluka:** frontend razvijati kao Angular 22/TypeScript SPA u `frontend/`, uz HTML predloške i CSS. Ovaj odabir zamjenjuje obični JavaScript iz D-003/D-008. PHP 8.4 bez frameworka, MySQL, sesije, CSRF i JSON REST API ostaju.
- **Razlog:** komponentna organizacija obrazaca, popisa, dashboarda i CMS-a te korisnikova želja za Angularom.
- **Okruženje:** Node.js 24 LTS i npm za razvoj/build; Angular CLI je lokalna ovisnost. PHP i baza i dalje rade u Dockeru. Cilj je isto porijeklo; razvojni proxy i posluživanje produkcijskog builda povezati uz funkcionalnosti.
- **Granica ove promjene:** samo početni minimalni projekt, prazne rute i dokumentacija. Bez poslovnih stranica, API integracije, UI biblioteke i autentifikacije.

## D-018 — Odvojeni backend i frontend
- **Datum:** 2026-10-25
- **Status:** prema izričitom korisnikovu zahtjevu
- **Odluka:** PHP/Composer projekt, baza, backend testovi i Dockerfile nalaze se u `backend/`; Angular/npm projekt u ravnopravnom `frontend/`. Dokumentacija, Compose i lokalni `.env` ostaju u korijenu repozitorija.
- **Okruženje:** Docker build kontekst i web bind mount ograničeni su na `backend/`. Interna putanja `/var/www/html`, nazivi servisa, portovi i trajni volumen baze ostaju isti.

## D-019 — Automatske provjere, formatteri i mali zadaci
- **Datum:** 2026-10-25
- **Status:** korisnik prihvatio sva tri prijedloga iz razgovora o pravilima razvoja
- **Odluka:** GitHub Actions provjerava frontend format/build te backend konfiguraciju, sintaksu, format i postojeće DB/HTTP testove. Prettier ostaje frontend formatter; backend koristi razvojni `php-cs-fixer/shim` i PSR-12 bez rizičnih pravila, uz zaključanu verziju u Composer lockfileu.
- **Organizacija:** mali razvojni zadaci imaju cilj, opseg, kriterije prihvaćanja i način provjere. Predložak postoji u `TASKS.md` i GitHub Issues obrascu.
- **Granice:** CI koristi zasebnu bazu na privremenom runneru. Frontend još nema funkcionalne testove. Obavezni statusi za merge nisu postavljeni, a konfiguracija se na GitHubu izvršava tek nakon slanja promjena.

## D-020 — Laravel backend i objašnjenja uz implementaciju
- **Datum:** 2026-10-25
- **Status:** korisnik izričito odabrao Laravel i naložio prijelaz; nastavnikovo odobrenje frameworka još nije potvrđeno
- **Odluka:** Laravel 13 (MIT) na postojećem PHP-u 8.4; zadržati Apache, MySQL 8.4, Docker Compose i odvojeni Angular frontend. Zamjenjuje odabir backenda bez frameworka iz D-008/D-015/D-017; uvjet kolegija iz D-004 ostaje otvoren.
- **Razlog:** Codex piše većinu implementacije; standardna struktura, dokumentirane mogućnosti i gotovi infrastrukturni mehanizmi olakšavaju održavanje i usporedbu sa Spring Bootom.
- **Rad s korisnikom:** uz funkcionalnosti objasniti tijek zahtjeva, ključne datoteke, poslovna pravila i stvarne provjere. Autor treba razumjeti i provjeriti predani kod.
- **Baza:** zadržati izvornu SQL shemu i njezin checksum. Početna Laravel migracija prepoznaje postojeću shemu bez ponovnog kreiranja tablica; praznu bazu instalira iz istog SQL-a. Dodaje se Laravelova evidencija `migrations`. Početni `down()` odbija brisanje preuzetih tablica; daljnje izmjene provode se novim migracijama.
- **Sučelje:** ukloniti tehnički PHP predložak; Laravel na `/` vraća JSON opis servisa, a Angular se razvija na portu 4200. Posluživanje Angular builda i razvojni proxy još nisu povezani.
- **Konfiguracija:** korijenski `.env` ostaje Compose konfiguracija s DB vjerodajnicama. `backend/.env` sadrži lokalni Laravel `APP_KEY` i ne verzionira se. Ključ se generira jednom i ne mijenja pri svakom pokretanju. Laravel i DB veza koriste UTC, a prikaz lokalnog vremena ostaje Europe/Zagreb.
- **Ovisnosti:** Composer lockfile bilježi Laravel i tranzitivne biblioteke; PHPUnit (BSD-3-Clause) dodan je za provjere odgovora i skrivanja internih grešaka. Postojeći DB i HTTP testovi ostaju.
- **Izvori:** [Laravel 13 podrška i PHP kompatibilnost](https://laravel.com/framework/docs/releases), [deployment i preduvjeti](https://laravel.com/framework/docs/deployment), [migracije](https://laravel.com/framework/docs/migrations).
