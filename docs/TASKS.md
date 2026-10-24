# Zadaci i faze rada

## Trenutačno stanje — 2026-10-24
Faza 2: početna shema, ER dijagram, demonstracijski podaci, PHP struktura i provjere su dovršeni. Baza više nije prazna: postoji 10 aplikacijskih tablica i tablica verzije sheme. Korisnik je potvrdio električna vozila uz benzin/dizel, troškove bez dupliciranja, podsjetnike te pravila vlasništva i brisanja. Prijava, javne vijesti i privatni CRUD još nisu implementirani. Repo: https://github.com/dcirko/AutoDnevnik , grana `main`.

## Faza 1 — provedena priprema
- [x] Postaviti pet početnih dokumenata.
- [x] Pregledati `upute.txt`, `upute 2.txt` i `Uvodno predavanje.pdf`.
- [x] Uskladiti zahtjeve, uključujući JSON Schemu, CMS/kategorije, ovlasti i predaju.
- [x] Potvrditi samostalan rad, odobrenu temu i tematski opseg.
- [x] Napisati prijedlog faza prema profesorovu rasporedu.
- [x] Dogovoriti bazu, lokalno okruženje, PHP pristup i autentifikaciju: Docker Compose, PHP bez frameworka, MySQL, PHP sesije i CSRF.
- [x] Pripremiti prijedlog projekta u `PROJECT.md`, uključujući tehnologije i vanjski servis.
- [x] Pripremiti Docker konfiguraciju, README, .gitignore i .env.example; lokalne lozinke generirane su u ignorirani .env.
- [x] Pokrenuti okruženje i provjeriti verzije, PHP ekstenzije, vezu na bazu i nedostupnost privatnih datoteka kroz Apache.
- [x] Inicijalizirati lokalni Git i stvoriti javni repozitorij `dcirko/AutoDnevnik`.
- [x] Povezati lokalni origin i poslati početni commit na GitHub; objavljene datoteke i javna vidljivost provjereni u pregledniku.
- [ ] Provjeriti točan kalendarski datum predaje projekta u LMS-u; potvrđen okvir je kraj semestra.
- [x] Provjeriti i odabrati početni VIN servis: NHTSA vPIC; ograničena pokrivenost izvan američkog tržišta dokumentirana je i ručni unos ostaje dostupan.

## Faza 2 — provedeno 2026-10-24
- [x] Zajedno potvrditi opseg električnih vozila, model troškova/podsjetnika i privatnost/brisanje.
- [x] Razraditi početna polja, ključeve, ograničenja i pravila brisanja.
- [x] Izraditi ER dijagram u `ARCHITECTURE.md` i primjenjivu SQL shemu u `database/schema.sql`.
- [x] Provjeriti shemu prvo u izoliranoj privremenoj bazi, zatim postaviti razvojnu bazu i izmišljene demo podatke.
- [x] Dodati CLI instalaciju i demo unos koji ne brišu ni prepisuju postojeće podatke.
- [x] Postaviti Composer autoload, konfiguraciju, PDO, JSON odgovore, Apache usmjeravanje i početni HTML predložak.
- [x] Izvesti 20 transakcijskih provjera baze i 8 HTTP provjera; posebno provjeriti nedostupnost baze i oporavak.
- [x] Ažurirati projektne dokumente i README s ponovljivim naredbama.

## Next — faza 3
- [ ] Implementirati registraciju, prijavu, odjavu, profil i promjenu lozinke sa sesijama i CSRF zaštitom.
- [ ] Dodati provjere aktivnog računa, uloge i vlasništva pri svakom zaštićenom zahtjevu.
- [ ] Izraditi responzivan javni dio: početna/o projektu, vijesti i detalj s kategorijom, pretragom i straničenjem.
- [ ] Dodati osnovno upravljanje vlastitim vozilima kroz frontend i API.
- [ ] Provjeriti uspješne/neuspješne tokove i ažurirati API dokumentaciju uz implementirane rute.

## Faze prema profesorovu rasporedu
Plan slijedi odjeljak 12 u `upute.txt`. Ovo su relativni tjedni semestra, ne potvrđeni datumi; rokovi dolaze iz LMS-a. AutoDnevnik module uklapamo u iste faze. API uvodimo uz prve funkcionalnosti, a u 9.–10. tjednu dovršavamo njegov ugovor, dokumentaciju i provjere. Sigurnost i testiranje provodimo tijekom cijelog razvoja.

### Faza 1 — 1.–2. tjedan: tema, zahtjevi i okruženje
- Potvrditi korisnike, opseg i rokove projekta; pripremiti prijedlog projekta.
- Odabrati PHP pristup, relacijsku bazu i lokalno okruženje te provjeriti vanjski servis.
- Instalirati potrebne alate, inicijalizirati Git i povezati javni GitHub repozitorij.
- Dodati .gitignore, osnovni README i primjer konfiguracije bez tajni.
- **Rezultat:** prijedlog projekta, dogovoreni alati i repozitorij s početnom poviješću. Vanjski servis naveden je u prijedlogu.

### Faza 2 — 3.–4. tjedan: baza i organizacija aplikacije
- Definirati polja, veze, ograničenja i pravila brisanja za račune/uloge, sadržaj i AutoDnevnik.
- Dogovoriti obračun troškova/potrošnje i uvjete podsjetnika prije relevantne sheme.
- Napraviti ER dijagram, SQL skriptu/migracije i izmišljene demonstracijske podatke.
- Postaviti PHP strukturu, javni direktorij, pristup bazi, usmjeravanje i osnovni JSON odgovor/grešku.
- **Rezultat:** ponovljivo kreiranje baze i osnovna aplikacija koja pristupa bazi.

### Faza 3 — 5.–6. tjedan: javni dio i korisnički računi
- Početna stranica, informacije o projektu, objavljene vijesti i detalj.
- Pretraga, kategorije i straničenje vijesti; početni sadržaj dolazi iz demo baze.
- Registracija, prijava, odjava, profil i promjena lozinke, uz sesije i provjere pristupa.
- Početni privatni dio: popis/detalj i ručni unos/uređivanje/brisanje vlastitog vozila.
- **Rezultat:** funkcionalan responzivan javni dio i računi; vlasnik upravlja svojim vozilima kroz početni API.

### Faza 4 — 7.–8. tjedan: CMS i ovlasti
- Vijesti: uređivač obogaćenog teksta, slika, kategorija, nacrt, objava/povlačenje i potvrđeno brisanje.
- Urednik uređuje vlastite vijesti; administrator sve vijesti i kategorije.
- Administracija korisnika, promjena uloge i aktivacija/deaktivacija s učinkom na otvorene sesije.
- Sanitizacija HTML-a, siguran upload i blokiranje javnog pristupa nacrtima.
- **Rezultat:** obavezni CMS i administracija rade uz provjerene granice ovlasti.

### Faza 5 — 9.–10. tjedan: cjelovit vlastiti API i evidencije
- Sustavno dovršiti rute, metode, statuse, JSON odgovore, filtre i straničenje.
- Dokumentirati rute i provjeriti ih kroz HTTP zahtjeve te frontend.
- Dodati JSON Schemu barem jednog unosa (kandidat: stvaranje vijesti) i provjeru valjanog/nevaljanog primjera.
- Na vozila nadograditi servise, gorivo/kilometražu i dodatne troškove; spriječiti dvostruko zbrajanje.
- **Rezultat:** dokumentiran i provjeren API koji aplikacija koristi, uz osnovne evidencije. Ako tematske evidencije kasne, dovršiti ih u fazi 6 uz prioritet obaveznom API-ju.

### Faza 6 — 11.–12. tjedan: vanjski servis i povezivanje AutoDnevnika
- Integrirati prethodno odabrani VIN servis kroz backend; korisnik potvrđuje/uređuje dobivene podatke.
- Obraditi timeout, pogrešku, nepotpun odgovor i ručni unos kada servis ne pomogne.
- Dovršiti evidencije, podsjetnike unutar aplikacije i jednostavan dashboard.
- Provjeriti izračune, privatnost svih zapisa i cjelovit tok korisnika.
- **Rezultat:** stvarna vanjska razmjena podataka i povezana funkcionalna verzija AutoDnevnika.

### Faza 7 — 13.–14. tjedan: završna provjera i dokumentacija
- Izvesti i evidentirati sve obavezne pozitivne/negativne scenarije iz `REQUIREMENTS.md`.
- Provjeriti mobilni prikaz, XSS/CSRF, upload, sesije, ovlasti i privatnost zapisa.
- Uskladiti README, ER, API, opis integracije, ovisnosti i rezultate testiranja.
- Provjeriti pokretanje prema uputama na čistoj konfiguraciji; pripremiti prezentaciju projekta.
- **Rezultat:** stabilna aplikacija koja se može ponovljivo pokrenuti i dokumentacija usklađena s kodom.

### Faza 8 — 15. tjedan: demonstracija i predaja
- Scenarij obrane: javni dio, različite uloge, CMS/kategorije/korisnici, vozila/evidencije, vlastiti/vanjski API i barem jedna greška.
- Snimiti video, objaviti ga na YouTubeu kao Unlisted ili Public i pripremiti screenshots.
- Pripremiti .txt s podacima/poveznicama i prezentaciju projekta.
- Predati projektne materijale na Moodle/LMS i Teams prema aktualnim uputama. Seminar i njegov Turnitin postupak zasebna su obaveza.
- **Rezultat:** potpuna predaja i pripremljena obrana; dovršena aplikacija sama nije cijela predaja.

## Kroz cijeli semestar
- Smisleni Git commitovi, ažuran popis zadataka i dokumentacija uz promjene. Dogovoren ritam objave napretka: svaki tjedan ili svaka dva tjedna; dovršene cjeline mogu se commitati ranije.
- Bilježiti izvore, razloge izbora i rezultate za projektnu dokumentaciju.
- Provjeravati validaciju, ovlasti, tuđe resurse i greške uz svaku funkcionalnost.
- Proučiti korišteni i generirani kod kako bi korisnik mogao objasniti rješenje.

## Sljedeći konkretan korak
Nakon faze 2 slijedi faza 3. Početi autentifikacijom i provjerama pristupa prije privatnog CRUD-a, zatim javnim vijestima i vozilima. Točan LMS datum još provjeriti; seminar ostaje zaseban i ne blokira razvoj aplikacije.

## Zasebno od projekta — seminar
- [x] Tema: „Usporedba REST i SOAP web servisa u modernim web aplikacijama“.
- [ ] Formalni status prijave, predložak i rok seminara provjeriti zasebno.
- Seminar nije dio osam razvojnih faza AutoDnevnika. Zahtjeve kolegija za zajednički paket predaje provjeriti bez spajanja tih dviju cjelina.

## Evidencija rada — faza 1, 2026-10-20
- Pročitane projektne datoteke, izvorne tekstualne upute i korisnikova uputa `RTK.md`.
- Potvrđen Docker izbor; pripremljena dva servisa bez zasebne Windows instalacije PHP-a/baze. Docker Desktop već je bio instaliran, pokrenut je njegov Linux engine.
- Stvarno provjerene verzije: Docker CLI 29.8.2, Compose v5.6.0, PHP 8.4.26, Apache 2.4.68, Composer 2.10.3 i MySQL 8.4.11.
- `docker compose config --quiet` i `docker compose up -d --build`: uspješno. Baza je healthy, web radi na loopback portu 8080.
- `apache2ctl -t`: Syntax OK. PHP ekstenzije PDO, pdo_mysql, curl, json, mbstring, fileinfo, openssl i session dostupne.
- PDO prijava aplikacijskim DB računom i `SELECT 1`: uspješno; `SHOW TABLES`: 0, očekivano prije faze 2. Provjera nije unosila aplikacijske podatke.
- HTTP `/`: 403, očekivano za prazan `public/` bez indexa; `/.env`: 403, `/docs/PROJECT.md`: 404 i `/.git/config`: 403. Konfiguracija/dokumentacija/Git nisu javno dostupni.
- vPIC javni primjer s djelomičnim VIN-om: HTTP 200 / JSON, BMW X3, 2011, Gasoline, ErrorCode 6. Provjerena dostupnost i polja; aplikacijska integracija ostaje u fazi 6.
- Git ignorira lokalni .env i izvorne nastavne materijale; vlastita dokumentacija i razvojna konfiguracija ulaze u repozitorij.
- Početni commit `8b64c91` poslan je na `origin/main`; u pregledniku potvrđena javna vidljivost i popis vlastitih projektnih datoteka. .env i izvorni nastavni materijali nisu objavljeni.
- AI pomoć: Codex za analizu, dokumentaciju, Docker konfiguraciju i provjere; opis dodan u README.
- Preostaje od korisnika: potvrda teme individualnog seminara, status njegove prijave i točan LMS rok. Prijedlog teme nalazi se u `PROJECT.md`; nijedna prijava ili predaja nije poslana.

## Dopuna dogovora — 2026-10-24
- Korisnik potvrdio temu seminara „Usporedba REST i SOAP web servisa u modernim web aplikacijama“ i rok do kraja semestra.
- Dogovoreno kontinuirano spremanje napretka na GitHub svaki tjedan ili svaka dva tjedna, kroz smislene cjeline.
- Ova dopuna razrješava raniju nepoznanicu teme seminara; ne potvrđuje formalnu prijavu niti točan kalendarski datum.

## Evidencija rada — faza 2, 2026-10-24
- Korisnik je kroz pitanja potvrdio električna vozila, kWh/troškove bez SOC-a i kWh/100 km, zasebne izvore troška, dospijeće podsjetnika po bilo kojem pragu te jednog vlasnika i brisanje povezanih zapisa.
- Primijenjena je `001_initial` shema. Tablice/veze prikazane su u ER dijagramu u arhitekturi; status napretka zahtjeva označen samo za stvarno provedeni dio.
- Instalacija i testovi prvo su izvedeni u izoliranoj bazi `autodnevnik_phase2_check`; ta baza i pripadajuća testna ovlast potom su uklonjeni. Razvojna baza nije resetirana niti brisana.
- Demo stanje: 3 uloge, 5 korisnika, 3 kategorije, 4 vijesti, 3 vozila, 2 servisa, 6 energetskih unosa, 2 dodatna troška, 4 podsjetnika. Slike se zasad provjeravaju testnim metapodacima; stvarne demo datoteke dodati uz CMS.
- Svih 5 demo lozinki provjereno je protiv spremljenih hashova, bez ispisa lozinki. Pristupni podaci su lokalni u ignoriranom `storage/demo-accounts.json`.
- `composer install`/validacija i PHP sintaksa provjereni. Composer prijavljuje informativno upozorenje da licenca projekta nije odabrana; licenca nije dodana bez dogovora. Vanjske PHP biblioteke još nisu uvedene.
- `composer test`: 20 provjera baze i 8 HTTP provjera uspješno. Testovi baze završavaju rollbackom i ne mijenjaju demo zapise. Provjereni su jedinstven e-mail, iznosi, FK, neusklađena energija, podsjetnik bez roka, status objave, kategorije i brisanje povezanih zapisa.
- `/` vraća HTML 200; `/api/v1/health` vraća JSON 200, nepoznata ruta JSON 404, POST na health JSON 405 s Allow. .env (403), projektni dokumenti (404), demo pristupni podaci (404) i CLI skripte (404) nisu dostupni kroz web.
- Zaustavljena je samo projektna baza radi negativne provjere: health je vratio generički 503, nakon ponovnog pokretanja 200. Servisi su ostavljeni pokrenuti.
- README opisuje instalaciju/autoload/shema/demo/testove. Aplikacija sada ima tehničku početnu stranicu; korisničko sučelje iz faze 3 nije izrađeno.
