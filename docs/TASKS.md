# Zadaci i faze rada

## Trenutačno stanje — 2026-10-25
Korisnik je odabrao Laravel backend uz Angular te zadržavanje postojećih Docker/Apache/MySQL servisa. Prijelaz i provjere opisani su u zadatku niže; raniji zapisi faza ostaju povijest rada. Odobrenje frameworka od nastavnika još nije potvrđeno.

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
- [x] Izraditi ER dijagram i SQL shemu u `backend/database/schema.sql`. Prema naknadnom zahtjevu dijagram je izdvojen u `docs/er-diagram.png` i `.svg`; arhitektura sadrži poveznice.
- [x] Provjeriti shemu prvo u izoliranoj privremenoj bazi, zatim postaviti razvojnu bazu i izmišljene demo podatke.
- [x] Dodati CLI instalaciju i demo unos koji ne brišu ni prepisuju postojeće podatke.
- [x] Postaviti Composer autoload, konfiguraciju, PDO, JSON odgovore, Apache usmjeravanje i početni HTML predložak.
- [x] Izvesti 20 transakcijskih provjera baze i 8 HTTP provjera; posebno provjeriti nedostupnost baze i oporavak.
- [x] Ažurirati projektne dokumente i README s ponovljivim naredbama.

## Angular priprema — 2026-10-25
- [x] Prema korisnikovu odabiru zamijeniti plan običnog JavaScripta Angularom i TypeScriptom.
- [x] Generirati minimalni Angular projekt u `frontend/`, sa strict TypeScript postavkama, standalone komponentom i praznom konfiguracijom ruta.
- [x] Uskladiti dokumentaciju i dodati naredbe za instalaciju, razvoj i build frontenda.
- Početni projekt nema poslovne stranice, autentifikaciju, API pozive ni UI biblioteku. PHP backend i baza ostaju postojeći.
- Provjera: Node.js 24.22.0, npm 11.19.1; instalirane ovisnosti i verzioniran lockfile. `npm run build` uspješan, `npm start -- --host 127.0.0.1` pokreće razvojni poslužitelj i GET `/` vraća HTTP 200.

## Odvajanje backenda i frontenda — 2026-10-25
- [x] Premjestiti PHP/Composer projekt, shemu, CLI, testove, lokalnu pohranu i Dockerfile u `backend/`, ravnopravno s `frontend/`.
- [x] Prilagoditi Compose build kontekst/mount, ignoriranje lokalnih datoteka i putanje u dokumentaciji.
- Provjera: `docker compose config --quiet` i Angular `npm run build` prolaze; lokalne ovisnosti i demo računi ostaju ignorirani. PHP/DB testovi nisu ponovno pokrenuti jer Docker Desktop Linux engine nije dostupan.

## Razvojne provjere i pravila — 2026-10-25

- [x] Dodati `docs/workflow.md` i korijenski `AGENTS.md` koji nalaže njegovo čitanje.
- [x] Pripremiti GitHub Actions poslove za frontend i backend, bez pristupa razvojnoj bazi ili lokalnim tajnama.
- [x] Dodati naredbe Prettiera i PHP CS Fixera, PSR-12 konfiguraciju i zajednički `.editorconfig`.
- [x] Dodati lokalni i GitHub predložak malog zadatka s kriterijima prihvaćanja.
- Provjereno lokalno: frontend format/build, Composer validacija, sintaksa 12 PHP datoteka, PHP format te 20 DB i 8 HTTP provjera. Postojeća shema prepoznata je bez izmjena; testni zapisi vraćeni su rollbackom. PHP 8.4.27 i PHP CS Fixer 3.95.27. Composer upozorava na još neodabranu licencu projekta.
- [ ] Nakon slanja konfiguracije na GitHub provjeriti prvi stvarni Actions run. Workflow je lokalno pripremljen; rezultat s GitHub runnera još ne postoji.

## Prijelaz backenda na Laravel — 2026-10-25

- Status: dovršeno; nastavnikovo odobrenje ostaje vanjska otvorena obaveza.
- Cilj: standardna Laravel struktura umjesto vlastitog PHP usmjeravanja, uz očuvanje postojeće baze i API ugovora za health.
- Opseg: Laravel 13, Composer lockfile, Artisan, rute/kontroler, početna migracija, konfiguracija i lokalni ključ, Docker priprema direktorija, testovi, CI i dokumentacija. Ukloniti tehnički PHP HTML predložak.
- Izvan opsega: autentifikacija, poslovni CRUD, Angular API proxy i objava produkcijskog builda.
- Kontekst: D-020; PHP 8.4, MySQL 8.4 i Apache ostaju u Dockeru.
- Otvorena pitanja: nastavnikovo odobrenje frameworka i dalje nije potvrđeno.

Kriteriji prihvaćanja:

- [x] Laravel/Artisan rade kroz postojeći Docker servis; Windows PHP/Composer nisu potrebni.
- [x] Postojeća shema preuzeta je bez izmjene aplikacijskih zapisa; ponovljena migracija ne radi nove promjene.
- [x] Početna migracija radi i na praznoj izoliranoj bazi, a neusklađenu shemu odbija.
- [x] Health zadržava JSON oblik i statuse 200/503; 404/405 i interni 500 ne otkrivaju detalje.
- [x] PHP sintaksa, format, PHPUnit te postojeće DB/HTTP provjere prolaze; README i CI odgovaraju Laravelu.

Provjereno:

- Laravel 13.38.0, PHP 8.4.27 i PHPUnit 12.5.40; ponovljivi `composer install` iz lockfilea i Artisan rade. Laravel je MIT, PHPUnit BSD-3-Clause. Composer audit nema poznatih sigurnosnih upozorenja; validacija prolazi uz ranije upozorenje da licenca samog projekta nije odabrana.
- `docker compose config --quiet`, obnova web slike/servisa i Apache `Syntax OK`; web i postojeći MySQL volumen ostaju pokrenuti. Entry point priprema Laravelove direktorije za pisanje, lokalni ključ generiran je bez ispisa sadržaja.
- `composer lint`: 20 datoteka bez sintaksnih grešaka; `composer format:check`: bez potrebnih promjena.
- `composer test`: 5 PHPUnit testova / 15 tvrdnji, 20 transakcijskih DB provjera i 10 stvarnih HTTP provjera prolaze. PHPUnit pokriva 200/503 i generički 500 čak uz debug, 404 i 405. Laravel standardno dopušta GET/HEAD; nedopuštena metoda vraća `Allow: GET, HEAD`.
- Početna migracija preuzela je razvojnu shemu; SHA-256 sažeci sadržaja svih 11 postojećih tablica jednaki su prije i poslije rada/testova. Dodana je samo Laravelova evidencija `migrations`. Ponovljeni migrate ne radi promjene; demo seeder preskače postojeće korisnike.
- Izolirana baza `autodnevnik_laravel_check_20261025`: instalacija od nule stvara svih 12 tablica i tri uloge; ponovno pokretanje bez promjena. Izravno provjereno odbijanje neusklađenog checksuma i destruktivnog početnog rollbacka.
- Laravel `DatabaseSeeder` na praznoj izoliranoj bazi: 5 računa s provjerenim hashovima bez ispisa lozinki, 3 vozila, 4 članka; ponovljeni unos bez duplikata i zabrana izvan `local`. Koristio je zasebnu privremenu pohranu, ne razvojne demo vjerodajnice. Izolirana baza i njezine dodijeljene ovlasti uklonjene su nakon provjera.
- API `/` sada vraća JSON opis servisa; tehnički PHP predložak uklonjen je. Automatske Laravel storage rute isključene su; provjereni su nedostupnost lokalnih tajni, demo računa i seeder datoteke preko HTTP-a te Git ignoriranje ključa, cachea i ovisnosti.
- Angular nije mijenjan pri Laravel migraciji; prije commita ponovno su uspješno pokrenuti `npm run format:check` i `npm run build`. CI konfiguracija prilagođena je Laravel ključu/migracijama/testovima; rezultat prvog GitHub Actions izvođenja provjeriti nakon pusha. Dodana su Git pravila za LF završetke shell skripti i Artisana kako Windows checkout ne bi pokvario Docker entry point.

## Predložak razvojnog zadatka


Za jednu malu cjelinu kopirati ovaj predložak u aktivne zadatke ili koristiti GitHub Issues obrazac „Razvojni zadatak“. Kriteriji opisuju provjerljiv ishod, ne samo datoteke koje treba napisati. Nepoznate poslovne odluke razjasniti prije ovisne implementacije. Zadatak je dovršen tek kada su kriteriji ispunjeni, relevantne provjere provedene i dokumentacija usklađena.

```markdown
### Naziv zadatka

- Status: planirano / u radu / dovršeno
- Cilj: problem i očekivana korist za korisnika.
- Opseg: što ovaj zadatak uključuje.
- Izvan opsega: što ostaje za zaseban zadatak.
- Kontekst: relevantni zahtjevi, odluke, postojeći kod i preduvjeti.
- Otvorena pitanja: odluke koje još nisu potvrđene ili „nema“.

Kriteriji prihvaćanja:

- [ ] Konkretan uspješan scenarij i očekivani rezultat.
- [ ] Nevaljan unos ili drugi relevantan neuspjeh i očekivani rezultat.
- [ ] Pravila pristupa i vlasništva, kada se primjenjuju.

Provjera:

- Naredbe ili ručni koraci i očekivani ishodi.
- Stvarni rezultat provjera i eventualna ograničenja, upisati nakon rada.
```

### Prvi mali zadatak faze 3: odjava postojeće sesije

- Status: planirano.
- Cilj: prijavljeni korisnik može prekinuti svoju sesiju.
- Preduvjet: prethodno implementirani prijava, sesijski i CSRF mehanizam. Ovaj primjer nije nalog da se faza 3 implementira u sklopu postavljanja razvojnih alata.
- Opseg: backend odjava i pripadajući Angular poziv/gumb.
- Izvan opsega: odjava svih uređaja, promjena lozinke i pamćenje prijave.
- Otvorena pitanja: prije implementacije potvrditi metodu, putanju, statuse i ponašanje kod već istekle sesije; zapisati ih u API ugovor.

Kriteriji prihvaćanja:

- [ ] Korisnik s valjanom sesijom i CSRF tokenom može se odjaviti.
- [ ] Nakon odjave prethodna sesija više ne otvara zaštićene podatke.
- [ ] Nevaljan CSRF token odbija zahtjev i ne prekida postojeću sesiju.
- [ ] Angular nakon uspjeha prikazuje stanje odjavljenog korisnika; kod neuspjeha prikazuje grešku bez lažne potvrde odjave.

Provjera: HTTP test uspješne odjave, ponovnog pristupa sa starom sesijom i nevaljanog CSRF tokena; ručno provjeriti gumb i prikaz greške u pregledniku. Rezultat još nije provjeren jer funkcionalnost nije implementirana.

## Next — faza 3
- [ ] Implementirati registraciju, prijavu, odjavu, profil i promjenu lozinke sa sesijama i CSRF zaštitom.
- [ ] Dodati provjere aktivnog računa, uloge i vlasništva pri svakom zaštićenom zahtjevu.
- [ ] Izraditi responzivan javni dio u Angularu: početna/o projektu, vijesti i detalj s kategorijom, pretragom i straničenjem; povezati PHP API preko razvojnog proxyja.
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
- HTTP `/`: 403, očekivano za prazan `backend/public/` bez indexa; `/.env`: 403, `/docs/PROJECT.md`: 404 i `/.git/config`: 403. Konfiguracija/dokumentacija/Git nisu javno dostupni.
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
- Svih 5 demo lozinki provjereno je protiv spremljenih hashova, bez ispisa lozinki. Pristupni podaci su lokalni u ignoriranom `backend/storage/demo-accounts.json`.
- `composer install`/validacija i PHP sintaksa provjereni. Composer prijavljuje informativno upozorenje da licenca projekta nije odabrana; licenca nije dodana bez dogovora. Vanjske PHP biblioteke još nisu uvedene.
- `composer test`: 20 provjera baze i 8 HTTP provjera uspješno. Testovi baze završavaju rollbackom i ne mijenjaju demo zapise. Provjereni su jedinstven e-mail, iznosi, FK, neusklađena energija, podsjetnik bez roka, status objave, kategorije i brisanje povezanih zapisa.
- `/` vraća HTML 200; `/api/v1/health` vraća JSON 200, nepoznata ruta JSON 404, POST na health JSON 405 s Allow. .env (403), projektni dokumenti (404), demo pristupni podaci (404) i CLI skripte (404) nisu dostupni kroz web.
- Zaustavljena je samo projektna baza radi negativne provjere: health je vratio generički 503, nakon ponovnog pokretanja 200. Servisi su ostavljeni pokrenuti.
- README opisuje instalaciju/autoload/shema/demo/testove. Aplikacija sada ima tehničku početnu stranicu; korisničko sučelje iz faze 3 nije izrađeno.

## Dopuna faze 2 — lokalni pregled baze i izdvojen dijagram
- Omogućen loopback MySQL port `127.0.0.1:3307`, podesiv kroz `DB_FORWARD_PORT`, za DBeaver. Ne mijenja se interna adresa aplikacije `db:3306`.
- Izrađene zasebne PNG/SVG slike sa svim stupcima SQL sheme i Mermaid datoteka sa sažetim ključevima/vezama. Slike su vizualno pregledane.
- Lokalni TCP pristup provjeren MySQL greetingom (protokol 10); DB kontejner je healthy i podaci su sačuvani.
- README sadrži postavke DBeaver veze. Lozinka ostaje u lokalnom .env i nije navedena u dokumentaciji.
