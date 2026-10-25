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
Početna shema (10 aplikacijskih tablica i evidencija verzije), ER dijagram i izmišljeni demo podaci su izrađeni. Backend koristi Laravel 13 uz postojeću API provjeru pristupa bazi i migraciju koja je preuzela postojeću shemu bez izmjene podataka. Prijava, javne vijesti, CMS i privatne korisničke funkcionalnosti dolaze u sljedećim fazama. Seminar je zasebna cjelina. Nastavnikovo odobrenje frameworka još nije potvrđeno.

## Tehnologije i preduvjeti
Razvojno okruženje koristi Docker Desktop s Linux kontejnerima i Docker Compose. PHP, Apache, MySQL i Composer ne moraju se zasebno instalirati na Windows.

- PHP 8.4, Laravel 13, Apache 2.4 i Composer 2 u servisu `web`.
- MySQL 8.4 u servisu `db` s trajnim Docker volumenom.
- PHP ekstenzija `pdo_mysql` dodaje se pri izgradnji; ostale Laravelove potrebne ekstenzije dostupne su u odabranoj PHP slici i provjeravaju se Composerom. Composer instalira Laravel i njegove ovisnosti te razvojne alate PHPUnit i PHP CS Fixer. `backend/vendor/` nastaje lokalno, `backend/composer.lock` se verzionira.
- Angular 22 i TypeScript frontend u `frontend/`, uz HTML predloške i CSS. Početni projekt je izrađen; poslovno sučelje i povezivanje s API-jem slijede. Laravelove sesije s CSRF zaštitom i autentifikacija planirani su za implementaciju.
- Vanjski servis za prijedlog: [NHTSA vPIC](https://vpic.nhtsa.dot.gov/api/), s ograničenom pokrivenošću vozila izvan američkog tržišta i obaveznim ručnim unosom kao alternativom.

Oznake slika fiksiraju verzijsku granu, ne točnu zakrpu. Točne lokalno provjerene verzije i stanje pokretanja bilježe se u `docs/TASKS.md`.

## Angular frontend (PowerShell)
Projekt je podijeljen na ravnopravne direktorije `backend/` (PHP, Composer, baza i backend testovi) i `frontend/` (Angular, TypeScript i npm). Zajednička dokumentacija, `compose.yaml` i lokalni `.env` nalaze se u korijenu repozitorija. Docker web servis montira samo `backend/`.

Za frontend su lokalno potrebni Node.js 24 LTS (najmanje 24.15.0) i npm. Globalna instalacija Angular CLI-ja nije potrebna. Backend i baza i dalje se pokreću kroz Docker prema sljedećem odjeljku.

```powershell
cd frontend
npm ci
npm start
```

Otvoriti `http://localhost:4200`. To je početni Angular projekt s praznim rutama, bez poslovnih stranica i API poziva. Laravel API je na `http://localhost:8080`; `/` vraća JSON opis servisa. Tehnički PHP HTML predložak je uklonjen. Razvojni proxy za `/api` i posluživanje produkcijskog Angular builda dodat ćemo pri povezivanju funkcionalnosti; plan ostaje isto porijeklo i Laravelove sesije/CSRF.

Produkcijski build, iz direktorija `frontend/`:

```powershell
npm run build
```

Rezultat je u `frontend/dist/autodnevnik/browser/`; zasad se ne objavljuje kroz Apache. `frontend/package-lock.json` se verzionira, a `node_modules/`, `dist/` i `.angular/` se ignoriraju.

## Prvo pokretanje backenda (PowerShell)
Sve Docker Compose naredbe izvršavaju se iz korijena repozitorija (ako ste u `frontend/`, prvo `cd ..`). Composer u kontejneru već radi iz backend korijena, pa njegove naredbe ostaju iste.

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

5. Instalirati Laravel ovisnosti, pripremiti lokalni ključ, instalirati shemu i po želji demo podatke:

   ```powershell
   docker compose exec web composer install
   if (-not (Test-Path -LiteralPath backend/.env)) {
       Copy-Item backend/.env.example backend/.env
       docker compose exec web php artisan key:generate --no-interaction
   }
   docker compose exec web composer db:install
   docker compose exec web composer db:seed
   ```

   `composer db:install` pokreće `php artisan migrate --no-interaction`. Početna migracija na praznoj bazi instalira izvornu SQL shemu, a postojeću prepoznaje po verziji, checksumu i očekivanim tablicama. Dodaje samo evidenciju migracija; ne briše ni prepisuje aplikacijske podatke. Djelomičnu ili neusklađenu početnu shemu odbija. MySQL DDL nema rollback cijele instalacije. Početna preuzeta shema namjerno nema destruktivan rollback; daljnje promjene uvodimo novim migracijama. Ne koristiti `migrate:fresh`/`migrate:refresh` nad razvojnom bazom s podacima.

   Korijenski `.env` služi Docker Composeu i sadrži DB konfiguraciju. `backend/.env` služi Laravelu i zasad sadrži samo njegov `APP_KEY`; oba su izvan Gita. Ključ se generira jednom i čuva kroz ponovna pokretanja. Ako datoteka već postoji, ali je `APP_KEY` prazan, pokrenuti `docker compose exec web php artisan key:generate --no-interaction`. Ne mijenjati postojeći ključ bez razloga jer ga koriste šifrirani podaci i kolačići. Docker ulazna skripta priprema direktorije `bootstrap/cache` i `storage/framework` za pisanje web procesa.

   `composer db:seed` pokreće Laravelov `artisan db:seed` i `database/seeders/DatabaseSeeder.php`, samo uz `APP_ENV=local`. Koristi izmišljene podatke i nasumične lozinke te preskače unos kada korisnici već postoje. Računi su zapisani samo u `backend/storage/demo-accounts.json`, izvan javnog direktorija i Gita; po potrebi ih pogledati lokalno. Ne slati ih u javni repozitorij. Prijava tim računima dolazi u fazi 3.

6. Otvoriti `http://localhost:8080` za JSON opis API-ja ili `http://localhost:8080/api/v1/health` za provjeru baze. Očekivani health odgovor je `{"data":{"status":"ok"}}`. Bez instalirane/dostupne baze health vraća 503. Korisničko sučelje otvara se zasebno na Angular portu 4200.

7. Provjeriti alate i pokrenuti testove:

   ```powershell
   docker compose exec web php -v
   docker compose exec web php -m
   docker compose exec web composer --version
   docker compose exec web apache2ctl -t
   docker compose exec web composer test
   ```

Web je vezan samo na `http://localhost:8080`; port se može promijeniti kroz `APP_PORT`, uz usklađivanje `APP_URL`. Apache poslužuje isključivo `backend/public/`, ne dokumentaciju ili `.env`. MySQL je za lokalni DB klijent dostupan na `127.0.0.1:3307`; aplikacija unutar Dockera i dalje koristi `db:3306`. Za CLI rad može se koristiti `docker compose exec db mysql -u autodnevnik -p autodnevnik`, uz unos lozinke u interaktivnom upitu.

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

`composer test` pokreće PHPUnit provjere JSON ugovora i skrivanja internih grešaka, zatim postojeće testove baze i HTTP-a. Testovi baze koriste privremene zapise u transakciji i na kraju rade rollback, bez promjene demo zapisa (AUTO_INCREMENT može preskočiti testne brojeve). HTTP testovi provjeravaju lokalni Apache iz web kontejnera. Pozitivan health test zahtijeva instaliranu shemu i pokrenutu bazu. Rezultati i obuhvat opisani su u `docs/ARCHITECTURE.md`; uspjeh tih testova ne znači da su buduća autentifikacija i CRUD već implementirani.

## Početni podatkovni model
Korisnik ima više vozila; vozilo ima servise, energiju, dodatne troškove i podsjetnike. Vijesti imaju autora, kategoriju i najviše jednu sliku. Količine goriva vode se u litrama, električna punjenja u kWh, a iznosi u eurima. Električna vozila u prvoj verziji prate energiju i troškove bez procjene kWh/100 km. Troškovi servisa i energije ne kopiraju se u dodatne troškove.

Potpuna početna shema je u `backend/database/schema.sql`; njezin sadržaj i checksum ostaju sačuvani pri prijelazu na Laravel. Migracije su u `backend/database/migrations/`. ER dijagram je zasebna [PNG slika](docs/er-diagram.png) ili [SVG slika](docs/er-diagram.svg); [pravila modela](docs/ARCHITECTURE.md#er-dijagram-početne-sheme) ostaju u arhitekturi. Naredbe `backend/bin/` namijenjene su CLI-ju, ne web pristupu. Za pokretanje na drugom računalu slijediti gornje korake; .env i lokalne lozinke ne prenose se kroz Git.

## Svakodnevni rad
```powershell
docker compose up -d
docker compose logs --tail 50 web
docker compose stop
```

`stop` čuva bazu. `docker compose down` uklanja kontejnere i mrežu, ali čuva volumen baze; ne dodavati `--volumes` ako se podaci trebaju sačuvati. MySQL inicijalne varijable vrijede pri prvom stvaranju baze; naknadna promjena lozinke u `.env` ne mijenja lozinku u već inicijaliziranoj bazi.

## Formatiranje i automatske provjere

Frontend koristi postojeći Prettier i `frontend/.prettierrc`, a backend razvojnu Composer ovisnost `php-cs-fixer/shim` (MIT licenca) i PSR-12 pravila iz `backend/.php-cs-fixer.dist.php`. `.editorconfig` usklađuje uvlačenje i završetke redaka. Composer pri instalaciji paketa koristi `unzip` iz PHP Docker slike; nakon preuzimanja promjene prvo obnoviti web servis i instalirati razvojne ovisnosti:

```powershell
docker compose up -d --build --wait
docker compose exec web composer install
```

Provjere backenda iz korijena projekta:

```powershell
docker compose exec web composer lint
docker compose exec web composer format:check
docker compose exec web composer test
```

Testovi zahtijevaju instaliranu shemu prema uputama za prvo pokretanje. Automatski popravak formata: `docker compose exec web composer format`.

Provjere frontenda iz `frontend/`:

```powershell
npm run format:check
npm run build
```

Automatski popravak formata: `npm run format`. Frontend još nema funkcionalne testove; build provjerava kompilaciju, a format provjerava raspored koda.

`.github/workflows/ci.yml` izvodi iste provjere pri svakom pushu i pull requestu, uz mogućnost ručnog pokretanja u GitHub Actions. Backend posao podiže vlastitu MySQL bazu na privremenom GitHub runneru, instalira shemu i pokreće testove baze/HTTP-a. CI lozinke služe samo toj privremenoj bazi; GitHub tajne i lokalni `.env` nisu potrebni. Provjere prijavljuju pogreške i ne prepravljaju niti commitaju kod. Pokrenut workflow ne zabranjuje sam po sebi merge; obavezni statusi za spajanje podešavaju se zasebno u pravilima repozitorija.

Razvojni zadatak započeti ciljem, opsegom, kriterijima prihvaćanja i načinom provjere. Predložak je u `docs/TASKS.md` i u GitHub Issues obrascu „Razvojni zadatak“. Za lokalni rad dovoljno je ispuniti ga u `TASKS.md`; otvaranje GitHub issuea nije obavezno.

## Dokumentacija i plan

- [Pravila razvoja i rada s AI alatima](docs/workflow.md)
- [Projekt i prijedlog teme](docs/PROJECT.md)
- [Zahtjevi i predaja](docs/REQUIREMENTS.md)
- [Arhitektura i otvorena pravila](docs/ARCHITECTURE.md)
- [Faze i trenutačni zadaci](docs/TASKS.md)
- [Odluke](docs/DECISIONS.md)

Izvorne nastavne upute/PDF ostaju lokalno i nisu dio javnog repozitorija. Dokumentacija projekta sadrži sažetak obaveza. Lozinke, ključeve i stvarne osobne podatke ne spremati u repozitorij.

## Korištenje AI alata
OpenAI Codex korišten je pri analizi zahtjeva, planiranju, dokumentaciji, pripremi okruženja, početnoj implementaciji/testovima i prijelazu na Laravel. Prema dogovoru piše većinu koda te uz funkcionalnosti objašnjava tok zahtjeva, ključne datoteke i provjere. Autor provjerava rezultat i odgovara za razumijevanje, sigurnost i ispravnost predanog projekta.
