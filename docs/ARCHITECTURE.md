# Arhitektura

## Status
U fazi 2 izrađeni su početna shema baze, demonstracijski podaci i PHP ulazna točka s provjerom pristupa bazi. Korisnik je 2026-10-25 odabrao Laravel 13 umjesto vlastitog PHP usmjeravanja (D-020). Poslovne odluke potvrđene su 2026-10-24 i evidentirane u `DECISIONS.md`. Korisnički računi, javne vijesti i privatni CRUD još nisu implementirani kroz sučelje/API. Nastavnikovo odobrenje frameworka još nije potvrđeno.

## Potvrđeni okvir
- PHP 8.4 i Laravel 13, MySQL 8.4; Docker Compose s PHP/Apache web servisom i bazom. Laravelov DB sloj koristi PDO.
- Angular/TypeScript frontend u `frontend/`, uz HTML predloške i CSS, koji će koristiti vlastiti REST API.
- JSON kao primarni format razmjene.
- Uloge posjetitelja, korisnika, urednika i administratora.
- CMS za automobilske vijesti i savjete.
- Barem jedna vanjska API integracija, prvenstveno VIN dekoder.

## Predloženi tok podataka
Preglednik šalje HTTP zahtjeve vlastitom PHP API-ju. Backend provjerava ulaze, prijavu, ulogu i vlasništvo, izvršava poslovna pravila i pristupa bazi kroz pripremljene SQL upite. Frontend obrađuje JSON odgovore i prikazuje rezultat.

Odabrano je isto porijeklo za frontend i API te autentifikacija Laravelovom sesijom s CSRF zaštitom promjena. Detaljne postavke i SPA integraciju provjeriti uz implementaciju računa; postojanje frameworka ne znači da je autentifikacija gotova. Lokalno koristimo HTTP isključivo na loopback adresi; javna instalacija zahtijeva HTTPS i Secure sesijski kolačić.

## Struktura projekta
```text
docs/           projektna dokumentacija
backend/        zaseban PHP/Composer projekt
  public/       Laravelova javna PHP ulazna točka
  app/          kontroleri i buduća poslovna logika/modeli
  bootstrap/    priprema Laravela i lokalni cache
  routes/       Laravelove API rute
  config/       konfiguracija bez verzioniranih tajni
  database/     shema/migracije i razvojni početni podaci
  tests/        backend testovi
  bin/          pomoćna provjera PHP sintakse
  artisan       Laravel CLI za migracije, demo unos i razvojne naredbe
  docker/       PHP/Apache konfiguracija i Dockerfile
frontend/       Angular/TypeScript SPA, zaseban npm projekt
compose.yaml    zajedničko pokretanje backenda i baze
```
`backend/public/index.php` učitava Composer i Laravel te predaje zahtjev frameworku. `backend/bootstrap/app.php` registrira API prefiks `/api/v1`, JSON opis servisa na `/` i dogovoreni oblik grešaka bez internih detalja. `backend/routes/api.php` povezuje health rutu s `app/Http/Controllers/HealthController.php`. Kontroler prima Laravelovu DB vezu kroz dependency injection i provjerava čitanje tablice uloga. `config/database.php` definira MySQL vezu i UTC. `artisan` je CLI ulazna točka. `src/` i tehnički PHP predložak više se ne koriste; korisničko sučelje ostaje Angular u `frontend/`.

Laravel 13 instalira se Composerom (MIT licenca, točna verzija u lockfileu). PHPUnit provjerava odgovore i greške; postojeći transakcijski DB i stvarni HTTP testovi ostaju. Kod novih funkcionalnosti koristiti Laravelovu validaciju/autorizaciju i prilagoditi JSON greške prema stvarnom API ugovoru, bez gradnje paralelnog frameworka. Eloquent modeli i autentifikacija još nisu implementirani.

## Lokalno okruženje — faza 1
- `compose.yaml`: servis `web` gradi se iz `backend/docker/php/Dockerfile`, servis `db` koristi službenu sliku `mysql:8.4`.
- Build kontekst je `./backend`, a samo `./backend` montira se u `/var/www/html`; frontend i dokumentacija nisu dio backend kontejnera. Putanje unutar kontejnera ostaju relativne backend korijenu.
- Web koristi `php:8.4-apache`, Composer 2, dodanu ekstenziju `pdo_mysql` i `unzip` za instalaciju Composer paketa. Razvojni formatter `php-cs-fixer/shim` koristi PSR-12; nije runtime ovisnost aplikacije. Slike su vezane uz verzijsku granu; točne dobivene zakrpe evidentirati nakon pokretanja.
- Samo `backend/public/` poslužuje se pregledniku, na `127.0.0.1:8080`. Pristup popisu datoteka je isključen. Apache `FallbackResource /index.php` šalje nepostojeće putanje PHP usmjeravanju; stvarne javne resurse poslužuje normalno.
- Za lokalni pregled u DBeaveru baza je dostupna na `127.0.0.1:3307` (podesivo kroz `DB_FORWARD_PORT`). Port nije vezan na javna mrežna sučelja. Unutar Dockera aplikacija i dalje koristi `db:3306`. Podaci su u trajnom volumenu `db-data`; koristi se zaseban DB račun, ne root.
- Korijenski `.env` ostaje lokalna Compose konfiguracija; Compose prosljeđuje DB varijable procesu. Laravel ih čita kroz `env()` u konfiguraciji. Lokalni `backend/.env` sadrži `APP_KEY`, generiran jednom kroz Artisan; oba primjera `.env.example` nemaju tajne. Laravel učitava svoju dotenv podršku; ključ se ne ispisuje ni verzionira.
- PHP/Composer/MySQL ne instaliraju se posebno na Windows; radi se kroz Docker naredbe iz README-a.

## Baza i vlasništvo
Izvor stvarne sheme je `backend/database/schema.sql`: deset aplikacijskih tablica i tehnička tablica `schema_versions`. Koristimo MySQL 8.4/InnoDB, UTF-8 (`utf8mb4`), primarne/strane ključeve, jedinstvene indekse i CHECK ograničenja. Svaki korisnik ima jednu ulogu, vozilo jednog vlasnika, a svaka privatna evidencija jedno vozilo. Posjetitelj nije zapis u tablici uloga.

### Tablice i važna polja
| Tablica | Namjena i obavezni podaci |
| --- | --- |
| `roles` | Kod user/editor/admin i prikazni naziv |
| `users` | Uloga, ime, jedinstven e-mail, hash lozinke, aktivnost i vremena izrade/izmjene |
| `categories` | Jedinstven naziv kategorije vijesti |
| `articles` | Autor, kategorija, naslov, sažetak, HTML sadržaj, nacrt/objavljeno i vremena izrade/izmjene/objave |
| `article_images` | Najviše jedna slika po vijesti: putanja, izvorni naziv, MIME, veličina, alternativni tekst; datoteka se sprema zasebno |
| `vehicles` | Vlasnik, naziv, marka, model, godina, vrsta energije i početna kilometraža; VIN/registracija/vrijeme VIN dohvata opcionalni |
| `service_entries` | Vozilo, datum, kilometraža, opis i iznos EUR; radionica opcionalna |
| `energy_entries` | Vozilo i njegova vrsta energije, datum, kilometraža, količina i iznos EUR; benzin/dizel imaju oznaku punog spremnika, električno punjenje nema |
| `expenses` | Vozilo, datum, kategorija dodatnog troška, opis i iznos EUR; kilometraža opcionalna |
| `reminders` | Vozilo, naslov i datum i/ili kilometarski prag; vrijeme dovršetka i napomena opcionalni |
| `schema_versions` | Verzija početne sheme, SHA-256 kontrolni zbroj i vrijeme primjene; nije poslovni entitet |
| `migrations` | Laravelova evidencija izvršenih migracija, dodana pri prijelazu na framework; nije dio izvorne poslovne sheme |

Novac je `DECIMAL(12,2)`, količina `DECIMAL(12,3)`, kilometraža nenegativan cijeli broj. Ne pohranjujemo izračunate ukupne troškove, prosječnu potrošnju ni dupliciranu trenutačnu kilometražu. VIN nije obavezan; kada postoji, mora imati 17 znakova i jedinstven je unutar vlasnikovih vozila. Stroža provjera VIN znakova i dinamička granica godine slijede uz API validaciju.

Datumi događaja (`occurred_on`, `due_on`) lokalni su kalendarski datumi. Tehnička vremena (`created_at`, `published_at`, `completed_at` itd.) spremaju se u UTC; PDO postavlja vremensku zonu veze na UTC. Sučelje ih prikazuje u Europe/Zagreb.

### ER dijagram početne sheme
Prikazane su stvarne tablice, polja i kardinalnosti. Potpuna ograničenja nalaze se u SQL datoteci. Veza vozilo–energija u SQL-u koristi složeni strani ključ `(vehicle_id, energy_type)`.

Dijagram je izdvojen iz Markdowna u zasebne datoteke:

- [PNG slika s potpunim poljima](er-diagram.png)
- [SVG vektorska slika](er-diagram.svg)
- [Mermaid pregled ključeva i veza](er-diagram.mmd)

Slike prikazuju sva polja početne SQL sheme. Mermaid datoteka zadržava sažeti pregled glavnih ključeva i kardinalnosti.

### Pravila brisanja i promjena
- Korisnika s vozilima ili člancima ne brišemo fizički; FK to blokira. Administracija će mijenjati `is_active`.
- Kategoriju koju koristi vijest i ulogu koju koristi korisnik nije dopušteno obrisati (`RESTRICT`).
- Brisanje članka kaskadno uklanja metapodatke slike. Brisanje stvarne datoteke treba implementirati uz CMS; FK ne briše datoteke s diska.
- Brisanje vozila treba provesti u transakciji: nakon provjere vlasnika ukloniti njegove `energy_entries`, zatim vozilo. FK kaskadno uklanja servise, dodatne troškove i podsjetnike. Složeni FK energije namjerno je restriktivan zbog [MySQL pravila za CHECK i referencijalne akcije](https://dev.mysql.com/doc/refman/8.4/en/create-table-check-constraints.html); izravan pokušaj brisanja vozila s energijom zato se odbija.
- Složeni FK energije sprječava benzin za električno vozilo i promjenu vrste energije vozila koje već ima te evidencije.
- CASCADE nije autorizacija: vlasništvo se mora provjeriti na backendu prije svih privatnih radnji, što slijedi uz račune i CRUD.

### Instalacija i demo podaci
`composer db:install` pokreće Laravelov `artisan migrate`. Početna migracija `2026_10_25_000000_adopt_initial_schema` na praznoj bazi primjenjuje postojeći `database/schema.sql`; već instaliranu shemu prepoznaje po `schema_versions` checksumu i očekivanim tablicama. Laravel dodaje tehničku tablicu `migrations`, bez izmjene aplikacijskih zapisa. Ako shema ne odgovara ili je instalacija djelomična, migracija odbija nastaviti; nema automatskog DROP-a/resetiranja. Početni `down()` odbija brisanje preuzete sheme. MySQL DDL nema rollback cijele instalacije. Daljnje promjene uvoditi novim migracijama, ne prepisivati SQL s već primijenjenim checksumom.

`composer db:seed` pokreće Laravelov `artisan db:seed` i `database/seeders/DatabaseSeeder.php`. Dopušten je samo uz `APP_ENV=local`; unosi izmišljene podatke u transakciji, bez prepisivanja postojećih korisnika. Sadrži 5 računa (dva vlasnika, urednik, admin, neaktivan korisnik), 3 kategorije, 4 vijesti (3 objavljene, 1 nacrt), 3 vozila (benzin, električno, dizel), 2 servisa, 6 točenja/punjenja, 2 dodatna troška i 4 podsjetnika. Slike će dobiti stvarne demo datoteke uz CMS u fazi 4; trenutačno su njihova ograničenja provjerena testnim metapodacima.

Svaki demo račun dobiva nasumičnu lozinku, u bazu se sprema hash. Lokalni pristupni podaci su isključivo u `backend/storage/demo-accounts.json`, izvan javnog direktorija i Gita. Te račune još nije moguće prijaviti jer autentifikacija dolazi u fazi 3.

Laravelov cache i sesije zasad koriste datoteke, bez dodatnih tablica ili Redisa. Automatsko posluživanje lokalnog storage diska isključeno je; `storage/` nije javni direktorij. SQL datoteka ostaje bajtno nepromijenjena zbog primijenjenog checksuma, pa njezina početna napomena o staroj instalacijskoj skripti ostaje povijesna; aktualna instalacija ide kroz migraciju.

## Uloge i pristup
| Uloga | Predviđeni pristup |
| --- | --- |
| Posjetitelj | Objavljene vijesti/savjeti, registracija i prijava |
| Korisnik | Vlastita vozila, evidencije, podsjetnici i statistika |
| Urednik | Izrada i uređivanje vlastitih vijesti/savjeta |
| Administrator | Sve vijesti, kategorije, korisnici, uloge i aktivacija/deaktivacija računa |

Posjetitelj je neprijavljeno stanje i ne sprema se u bazu. Urednik i administrator imaju i mogućnosti običnog korisnika za vlastita vozila, bez pristupa privatnim evidencijama drugih korisnika. To pravilo korisnik je potvrdio 2026-10-24; provjere u aplikaciji slijede uz autentifikaciju.

## Predloženi REST API
Predlaže se prefiks `/api/v1/` i resursi za autentifikaciju, vozila, servise, gorivo, troškove, podsjetnike, članke i administraciju korisnika. Točne rute i ugovore definirati uz svaku funkcionalnost.

- `GET` čita podatke bez promjene stanja.
- `POST` stvara resurse ili pokreće odgovarajuću radnju, primjerice prijavu.
- `PATCH` djelomično mijenja resurs; `PUT` koristiti ako se uvede potpuna zamjena.
- `DELETE` briše resurs.
- Predviđeni statusi: `200`, `201`, `204`, `400`, `401`, `403`, `404`, `409`, `422`, `429` i `500`, prema ishodu zahtjeva.
- Ujednačiti JSON format grešaka; odgovor `204` nema tijelo.

API razvijamo od prve funkcionalnosti koja treba podatke. Dokumentirati ulaze, odgovore, prava pristupa, validaciju i primjere zahtjeva uz implementaciju. Prema izvornim uputama obavezna je JSON Schema za barem jedan unos i provjera valjanog/nevaljanog primjera; kandidat je stvaranje vijesti. Sam ručni pregled JSON-a nije demonstracija provjere prema shemi.

Vlastiti API prihvaća JSON za strukturirane podatke; prijenos slike može imati zasebnu zaštićenu rutu s multipart/form-data. Točan ugovor određuje se uz CMS. Javni dohvat članaka uvijek primjenjuje uvjet objavljenosti, uključujući dohvat pojedinačnog ID-a.

## Obavezna ponašanja potvrđena uputama
- Backend provjerava aktivnost računa i aktualne ovlasti pri svakom zaštićenom zahtjevu kako deaktivacija i promjena uloge ne bi ostavile stara prava u otvorenoj sesiji.
- Urednik smije izrađivati/uređivati vlastite članke; tuđim člancima upravlja administrator. Dodatne uredničke radnje precizirati u API ugovoru.
- Slike zahtijevaju provjeru sadržaja/vrste i veličine, kontrolirana imena i pohranu bez mogućnosti izvršavanja.
- Uređivač obogaćenog teksta zahtijeva backend sanitizaciju dopuštenog HTML-a, uz biblioteku čije uvjete i ovisnosti treba provjeriti prije izbora.

## Poslovna pravila i odabiri prve verzije
Korisnik je 2026-10-24 potvrdio električna vozila, model troškova/podsjetnika, privatnost i brisanje. Detalji polja i ograničenja razrađeni su u fazi 2. Biblioteke za CMS i JSON Schemu odabrat ćemo uz te funkcionalnosti.

| Tema | Prijedlog za prvu verziju |
| --- | --- |
| PHP | Odabrano: Laravel 13 na PHP-u 8.4; biblioteke za sanitizaciju i JSON Schema validaciju odabrati uz implementaciju |
| Frontend | Angular 22 i TypeScript, standalone komponente, Router, HTML/CSS; komunikacija s PHP REST API-jem slijedi uz funkcionalnosti |
| Autentifikacija | Odabrano: Laravelova sesija i CSRF zaštita; SPA povezivanje i pravila implementirati u fazi 3 |
| Baza/okruženje | Odabrano: MySQL 8.4, PHP 8.4/Apache i Composer 2 kroz Docker Compose |
| Uloge | Korisnik, urednik, administrator; urednik/admin imaju i mogućnosti običnog korisnika nad vlastitim vozilima, bez pristupa tuđima |
| Vijesti | Jedna kategorija i jedna opcionalna slika; nacrt/objavljeno, uz vrijeme objave; povlačenje vraća u nacrt |
| Kategorije | Zabraniti brisanje kategorije koja sadrži vijesti; prvo promijeniti kategoriju tih vijesti |
| Vozilo | Naziv, marka, model, godina i početna kilometraža; VIN opcionalan radi ručnog unosa; obaveznost/ostala polja tek se dogovaraju |
| Servis | Datum, kilometraža, opis i iznos; radionica/napomena opcionalne |
| Energija | Benzin/dizel u litrama s oznakom punog spremnika; električna punjenja u kWh, bez te oznake; sve uz datum, kilometražu i iznos |
| Troškovi | Servis i gorivo nose vlastiti iznos; dodatni troškovi za registraciju, osiguranje, parking itd.; dashboard zbraja tri izvora jednom, bez kopiranja iznosa |
| Novac/jedinice | EUR, kilometri i litre; novac spremati decimalno, ne kao binarni floating point |
| Potrošnja | Benzin/dizel: od punog do punog, zbroj litara nakon početnog punog do završnog punog / razlika km × 100, uz međutočenja; prvi puni unos nije potrošnja tog intervala. Bez dovoljno podataka nema izračuna. Električno: ukupni napunjeni kWh i troškovi, bez procjene kWh/100 km i bez evidencije stanja baterije |
| Podsjetnici | Datum i/ili kilometraža, status otvoreno/dovršeno; dospjelo kad je dosegnut bilo koji prag; kilometarski status prema posljednjoj evidentiranoj kilometraži |
| Dashboard | Vozilo/razdoblje, servis/gorivo/ostali troškovi i ukupno, potrošnja kada je moguća te dospjeli/nadolazeći podsjetnici; prag za nadolazeće dogovoriti |
| Brisanje | Vozilo s pripadajućim evidencijama nakon jasne potvrde, uz transakciju gdje treba; korisnike primarno deaktivirati |
| VIN | Poziv na izričitu radnju, rezultat predlaže polja koja korisnik potvrđuje; nepotpun rezultat ne blokira ručni unos |

Električna vozila uključena su, LPG i hibridna vozila nisu dio početnog modela. Jedinica količine izvodi se iz vrste energije (`electricity` znači kWh, ostalo litre); količine različitih jedinica ne zbrajaju se. Trenutačna kilometraža bit će maksimum početne i zabilježene kilometraže iz evidencija; podsjetnik nije očitanje kilometraže. Provjeru vremenskog redoslijeda/ispravaka povijesnih očitanja i prag "uskoro" razraditi uz CRUD i dashboard. Međutablična i vremenska pravila ne može zamijeniti CHECK pojedinog retka.

### Početne rute — nakon prijelaza na Laravel
| Metoda / putanja | Odgovor |
| --- | --- |
| `GET /` | 200, JSON `{"data":{"name":"AutoDnevnik API"}}`; Angular je zasebno na portu 4200 |
| `GET /api/v1/health` | 200 i `{"data":{"status":"ok"}}` ako PDO može pročitati tablicu uloga |
| Nedostupna baza na health ruti | 503 i `{"error":{"code":"service_unavailable","message":"Usluga trenutačno nije dostupna."}}` |
| `HEAD` poznate rute | 200 bez tijela; standardno Laravelovo mapiranje uz `GET` |
| Druga metoda poznate rute | 405, JSON greška `method_not_allowed`, `Allow: GET, HEAD` |
| Nepoznata putanja | 404, JSON greška `not_found` |

Ovo je samo tehnički temelj API-ja. Rute vijesti i vozila, prijava, autorizacija, validacija unosa i JSON Schema nisu gotove ovom fazom. JSON tijela ne izlažu konfiguraciju, verzije, tablice ni PDO detalje; detalji nastale greške u ovoj fazi svode se na klasu iznimke u internom logu.

### Provjere faze 2
- Instalacija sheme prvo provjerena u novoj izoliranoj bazi, zatim primijenjena na razvojnu bazu. Privremena baza i njezine dodijeljene ovlasti uklonjene su nakon provjera.
- `backend/tests/database.php`: 20 provjera ograničenja, decimalnog zbrajanja i brisanja. Testni podaci uvijek se vraćaju rollbackom; InnoDB AUTO_INCREMENT brojači mogu preskočiti iskorištene testne brojeve.
- `backend/tests/http.php`: 8 provjera stvarnih HTTP odgovora, JSON oblika, Allow zaglavlja i nedostupnosti privatnih putanja. Pokretanje unutar web kontejnera; `TEST_BASE_URL` može promijeniti osnovnu adresu.
- Posebno provjereno privremeno zaustavljanje projektne baze: health vraća 503 bez internih detalja, nakon pokretanja ponovno 200.
- PHP sintaksa i Apache konfiguracija provjereni. Instalacija i demo unos ponovno pokrenuti bez dupliciranja/prepisivanja podataka.

## Vanjski API
Za prijedlog projekta odabran je NHTSA vPIC. [Dokumentacija](https://vpic.nhtsa.dot.gov/api/) i [FAQ](https://vpic.nhtsa.dot.gov/api/home/index/faq) provjereni su 2026-10-20. Javan je i ne zahtijeva registraciju/ključ; servis ima automatsku kontrolu prometa, pa ga ne tretirati kao neograničen resurs. Pokrivenost stranih vozila ovisi o registraciji proizvođača za američko tržište.

Predviđeni zahtjev je `GET /vehicles/DecodeVinValues/{vin}?format=json&modelyear={godina}`. Poziv dolazi iz backenda na izričitu korisničku radnju, s timeoutom 10 sekundi. Godinu poslati kada je poznata. Dohvaćena polja `Make`, `Model`, `ModelYear` i `FuelTypePrimary` samo su prijedlog za potvrdu korisnika; prazna/nepouzdana polja ne prepisuju njegov unos.

Stvarni javni primjer iz dokumentacije, `/vehicles/DecodeVinValues/5UXWX7C5*BA?format=json&modelyear=2011`, provjeren je iz PowerShella 2026-10-20: HTTP 200, JSON, jedan rezultat, BMW / X3 / 2011 / Gasoline, `ErrorCode=6`. Primjer koristi djelomičan VIN, stoga HTTP 200 sam po sebi nije dokaz potpunog dekodiranja. Pri implementaciji obraditi i API-jeve `ErrorCode`/`ErrorText`, ne samo HTTP status. To je provjera prikladnosti servisa, ne dovršena integracija u aplikaciji.

Ne obećavamo potpunu pokrivenost hrvatskih/europskih vozila. Ručni unos ostaje dostupan. Prije faze 6 ponoviti provjeru dostupnosti i reprezentativnih VIN-ova; drugog pružatelja uvoditi tek ako se pokaže potrebnim, uz dokumentiranu zamjenu.

## Otvorene odluke
- Detalji API ugovora i implementacija sesijske autentifikacije.
- Provjera pokrivenosti odabranog VIN servisa prije integracije.
- Validacija povijesnih očitanja i detalji prikaza podsjetnika/obračuna statistike kroz budući API.
- Okruženje završne predaje.
