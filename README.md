# AutoDnevnik

Web aplikacija za vlasnike vozila za kolegij **Napredne tehnike projektiranja web servisa**. Samostalni projekt; tema je odobrena prema potvrdi autora.

Repozitorij: [dcirko/AutoDnevnik](https://github.com/dcirko/AutoDnevnik).

## Što će aplikacija raditi
- Vlastita vozila, servisi, gorivo, kilometraža i troškovi.
- Podsjetnici unutar aplikacije i jednostavan dashboard.
- Javne automobilske vijesti/savjeti s kategorijama, pretragom i straničenjem.
- Registracija, prijava, profil i uloge korisnika, urednika i administratora.
- CMS vijesti/slika/kategorija i administracija korisnika.
- Vlastiti JSON REST API i vanjski VIN servis.

## Status
Faza 1: prijedlog projekta, dokumentacija, Git i priprema razvojnog okruženja. Aplikacija još nije implementirana. Baza se pri prvom pokretanju stvara prazna; shema, demonstracijski podaci i ER dijagram dolaze u fazi 2. `public/` zasad sadrži samo oznaku praznog direktorija; odgovor 403 na početnoj adresi očekivan je do izrade ulazne točke u fazi 2.

## Tehnologije i preduvjeti
Razvojno okruženje koristi Docker Desktop s Linux kontejnerima i Docker Compose. PHP, Apache, MySQL i Composer ne moraju se zasebno instalirati na Windows.

- PHP 8.4, Apache 2.4 i Composer 2 u servisu `web`, bez PHP frameworka.
- MySQL 8.4 u servisu `db` s trajnim Docker volumenom.
- PHP ekstenzija `pdo_mysql` dodaje se pri izgradnji; ostale ekstenzije službene slike provjeriti naredbom niže. Dodatne ovisnosti uvoditi uz konkretnu funkcionalnost.
- HTML/CSS/JavaScript frontend i PHP sesije s CSRF zaštitom planirani su za implementaciju.
- Vanjski servis za prijedlog: [NHTSA vPIC](https://vpic.nhtsa.dot.gov/api/), s ograničenom pokrivenošću vozila izvan američkog tržišta i obaveznim ručnim unosom kao alternativom.

Oznake slika fiksiraju verzijsku granu, ne točnu zakrpu. Točne lokalno provjerene verzije i stanje pokretanja bilježe se u `docs/TASKS.md`.

## Prvo pokretanje (PowerShell)
1. Pokrenuti Docker Desktop i pričekati spreman Linux engine. Provjeriti `docker version` i `docker compose version`.
2. Iz korijena projekta kopirati konfiguracijski primjer, samo ako `.env` još ne postoji:

   ```powershell
   Copy-Item .env.example .env
   ```

3. U `.env` postaviti dvije različite lokalne lozinke za `DB_PASSWORD` i `MYSQL_ROOT_PASSWORD`. `.env` se ne verzionira. Naredba kopiranja ne smije prebrisati postojeću lokalnu konfiguraciju.
4. Provjeriti konfiguraciju i pokrenuti servise:

   ```powershell
   docker compose config --quiet
   docker compose up -d --build
   docker compose ps
   ```

5. Provjeriti alate:

   ```powershell
   docker compose exec web php -v
   docker compose exec web php -m
   docker compose exec web composer --version
   docker compose exec web apache2ctl -t
   ```

Web je vezan samo na `http://localhost:8080`; port se može promijeniti kroz `APP_PORT`, uz usklađivanje `APP_URL`. Apache poslužuje isključivo `public/`, ne dokumentaciju ili `.env`. MySQL port nije objavljen na Windowsu; aplikacija mu pristupa na `db:3306`. Za rad s bazom može se koristiti `docker compose exec db mysql -u autodnevnik -p autodnevnik`, uz unos lozinke u interaktivnom upitu.

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
