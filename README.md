# AutoDnevnik

Web aplikacija za vlasnike vozila za kolegij **Napredne tehnike projektiranja web servisa**. Samostalni projekt; tema je odobrena prema potvrdi autora.

Repozitorij: [dcirko/AutoDnevnik](https://github.com/dcirko/AutoDnevnik).

## Što će aplikacija raditi
- Vlastita benzinska, dizelska i električna vozila, servisi, gorivo/punjenja, kilometraža i troškovi.
- Podsjetnici unutar aplikacije i jednostavan dashboard.
- Javne automobilske vijesti/savjeti s kategorijama, pretragom i straničenjem.
- Registracija, prijava, profil i uloge korisnika, urednika i administratora.
- CMS vijesti/slika/kategorija i administracija korisnika.
- Vlastiti JSON REST API i vanjski VIN servis.

## Status
Faza 2: početna shema (10 aplikacijskih tablica i evidencija verzije), ER dijagram, izmišljeni demo podaci i PHP struktura su izrađeni. Postoje tehnička početna stranica i API provjera pristupa bazi. Prijava, javne vijesti, CMS i privatne korisničke funkcionalnosti dolaze u sljedećim fazama. Seminar je zasebna cjelina.

## Tehnologije i preduvjeti
Razvojno okruženje koristi Docker Desktop s Linux kontejnerima i Docker Compose. PHP, Apache, MySQL i Composer ne moraju se zasebno instalirati na Windows.

- PHP 8.4, Apache 2.4 i Composer 2 u servisu `web`, bez PHP frameworka.
- MySQL 8.4 u servisu `db` s trajnim Docker volumenom.
- PHP ekstenzija `pdo_mysql` dodaje se pri izgradnji. `composer.json` provjerava PHP/PDO/MySQL/cURL/JSON preduvjete; Composer trenutačno služi za PSR-4 učitavanje vlastitih klasa, bez vanjskih PHP biblioteka. `vendor/` nastaje lokalno, `composer.lock` se verzionira.
- HTML/CSS/JavaScript frontend i PHP sesije s CSRF zaštitom planirani su za implementaciju.
- Vanjski servis za prijedlog: [NHTSA vPIC](https://vpic.nhtsa.dot.gov/api/), s ograničenom pokrivenošću vozila izvan američkog tržišta i obaveznim ručnim unosom kao alternativom.

Oznake slika fiksiraju verzijsku granu, ne točnu zakrpu. Točne lokalno provjerene verzije i stanje pokretanja bilježe se u `docs/TASKS.md`.

## Prvo pokretanje (PowerShell)
1. Pokrenuti Docker Desktop i pričekati spreman Linux engine. Provjeriti `docker version` i `docker compose version`.
2. Iz korijena projekta kopirati konfiguracijski primjer, samo ako `.env` još ne postoji:

   ```powershell
   if (-not (Test-Path -LiteralPath .env)) { Copy-Item .env.example .env }
   ```

3. U `.env` postaviti dvije različite lokalne lozinke za `DB_PASSWORD` i `MYSQL_ROOT_PASSWORD`. `.env` se ne verzionira. Naredba kopiranja ne smije prebrisati postojeću lokalnu konfiguraciju.
4. Provjeriti konfiguraciju i pokrenuti servise:

   ```powershell
   docker compose config --quiet
   docker compose up -d --build
   docker compose ps
   ```

5. Pripremiti PHP autoload, instalirati shemu i po želji demo podatke:

   ```powershell
   docker compose exec web composer install
   docker compose exec web composer db:install
   docker compose exec web composer db:seed
   ```

   Instalacija radi samo na praznoj bazi; pri ponovnom pokretanju prepoznaje istu shemu. Ne briše ni prepisuje postojeće tablice. Ako je početna instalacija djelomična ili je SQL izmijenjen nakon primjene, odbit će nastaviti. MySQL DDL nema rollback cijele skripte. Promjene primijenjene sheme uvodimo novim migracijama.

   Demo unos koristi izmišljene podatke i nasumične lozinke te preskače unos kada korisnici već postoje. Računi su zapisani samo u `storage/demo-accounts.json`, izvan javnog direktorija i Gita; po potrebi ih pogledati lokalno. Ne slati ih u javni repozitorij. Prijava tim računima dolazi u fazi 3.

6. Otvoriti `http://localhost:8080` za tehničku početnu stranicu ili `http://localhost:8080/api/v1/health` za JSON provjeru. Očekivani odgovor je `{"data":{"status":"ok"}}`. Bez instalirane/dostupne baze health vraća 503.

7. Provjeriti alate i pokrenuti testove:

   ```powershell
   docker compose exec web php -v
   docker compose exec web php -m
   docker compose exec web composer --version
   docker compose exec web apache2ctl -t
   docker compose exec web composer test
   ```

Web je vezan samo na `http://localhost:8080`; port se može promijeniti kroz `APP_PORT`, uz usklađivanje `APP_URL`. Apache poslužuje isključivo `public/`, ne dokumentaciju ili `.env`. MySQL je za lokalni DB klijent dostupan na `127.0.0.1:3307`; aplikacija unutar Dockera i dalje koristi `db:3306`. Za CLI rad može se koristiti `docker compose exec db mysql -u autodnevnik -p autodnevnik`, uz unos lozinke u interaktivnom upitu.

### Pregled baze u DBeaveru
SSMS je klijent za Microsoft SQL Server; ovaj projekt koristi MySQL. U DBeaveru odabrati novu MySQL vezu:

| Postavka | Vrijednost |
| --- | --- |
| Host | `127.0.0.1` |
| Port | `3307` (ili vrijednost `DB_FORWARD_PORT`) |
| Database | `autodnevnik` (ili `DB_DATABASE`) |
| Username | `autodnevnik` (ili `DB_USERNAME`) |
| Password | Lokalna vrijednost `DB_PASSWORD` iz `.env`, ne root lozinka |
| JDBC URL | `jdbc:mysql://127.0.0.1:3307/autodnevnik` |

Pokrenuti Docker servise, zatim Test Connection i Finish. U stablu veze otvoriti `autodnevnik` → Tables; odabrati tablicu i prikaz Data za postojeće demo zapise. Pri prvom povezivanju klijent može tražiti preuzimanje MySQL JDBC drivera. Lozinku ne upisivati u dokumentaciju ili javni URL.

Testovi baze koriste privremene zapise u transakciji i na kraju rade rollback, bez promjene demo zapisa (AUTO_INCREMENT može preskočiti testne brojeve). HTTP testovi provjeravaju lokalni Apache iz web kontejnera. Pozitivan health test zahtijeva instaliranu shemu i pokrenutu bazu. Rezultati i obuhvat opisani su u `docs/ARCHITECTURE.md`; uspjeh tih testova ne znači da su buduća autentifikacija i CRUD već implementirani.

## Početni podatkovni model
Korisnik ima više vozila; vozilo ima servise, energiju, dodatne troškove i podsjetnike. Vijesti imaju autora, kategoriju i najviše jednu sliku. Količine goriva vode se u litrama, električna punjenja u kWh, a iznosi u eurima. Električna vozila u prvoj verziji prate energiju i troškove bez procjene kWh/100 km. Troškovi servisa i energije ne kopiraju se u dodatne troškove.

Potpuna početna shema je u `database/schema.sql`. ER dijagram je zasebna [PNG slika](docs/er-diagram.png) ili [SVG slika](docs/er-diagram.svg); [pravila modela](docs/ARCHITECTURE.md#er-dijagram-početne-sheme) ostaju u arhitekturi. Naredbe `bin/` namijenjene su CLI-ju, ne web pristupu. Za pokretanje na drugom računalu slijediti gornje korake; .env i lokalne lozinke ne prenose se kroz Git.

## Svakodnevni rad
```powershell
docker compose up -d
docker compose logs --tail 50 web
docker compose stop
```

`stop` čuva bazu. `docker compose down` uklanja kontejnere i mrežu, ali čuva volumen baze; ne dodavati `--volumes` ako se podaci trebaju sačuvati. MySQL inicijalne varijable vrijede pri prvom stvaranju baze; naknadna promjena lozinke u `.env` ne mijenja lozinku u već inicijaliziranoj bazi.

## Dokumentacija i plan
- [Projekt i prijedlog teme](docs/PROJECT.md)
- [Zahtjevi i predaja](docs/REQUIREMENTS.md)
- [Arhitektura i otvorena pravila](docs/ARCHITECTURE.md)
- [Faze i trenutačni zadaci](docs/TASKS.md)
- [Odluke](docs/DECISIONS.md)

Izvorne nastavne upute/PDF ostaju lokalno i nisu dio javnog repozitorija. Dokumentacija projekta sadrži sažetak obaveza. Lozinke, ključeve i stvarne osobne podatke ne spremati u repozitorij.

## Korištenje AI alata
OpenAI Codex korišten je za pomoć pri analizi zahtjeva, planiranju i pripremi dokumentacije/razvojnog okruženja. Autor provjerava rezultat i odgovara za razumijevanje, sigurnost i ispravnost predanog projekta. Ovaj opis ažurira se uz daljnju uporabu.
