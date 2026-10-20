# Arhitektura

## Status
Ovo je početni tehnički okvir. Odluke su u `DECISIONS.md`; preostali prijedlozi nisu konačni odabiri. U fazi 1 pripremljeno je Docker razvojno okruženje; funkcionalnosti aplikacije još nisu implementirane.

## Potvrđeni okvir
- PHP 8.4 bez frameworka, MySQL 8.4, PDO; Docker Compose s PHP/Apache web servisom i bazom.
- HTML/CSS/JavaScript frontend koji koristi vlastiti REST API.
- JSON kao primarni format razmjene.
- Uloge posjetitelja, korisnika, urednika i administratora.
- CMS za automobilske vijesti i savjete.
- Barem jedna vanjska API integracija, prvenstveno VIN dekoder.

## Predloženi tok podataka
Preglednik šalje HTTP zahtjeve vlastitom PHP API-ju. Backend provjerava ulaze, prijavu, ulogu i vlasništvo, izvršava poslovna pravila i pristupa bazi kroz pripremljene SQL upite. Frontend obrađuje JSON odgovore i prikazuje rezultat.

Odabrano je isto porijeklo za frontend i API te autentifikacija PHP sesijom s CSRF zaštitom promjena. Detaljne postavke provjeriti uz implementaciju. Lokalno koristimo HTTP isključivo na loopback adresi; javna instalacija zahtijeva HTTPS i Secure sesijski kolačić.

## Predložena struktura projekta
```text
docs/           projektna dokumentacija
public/         javna ulazna točka i frontend resursi
src/            PHP obrada zahtjeva, poslovna logika i pristup podacima
config/         konfiguracija bez verzioniranih tajni
database/       verzionirana shema/migracije i razvojni početni podaci
tests/          testovi
```
Za početak odvojiti usmjeravanje zahtjeva, obradu, poslovna pravila i pristup bazi bez nepotrebnih slojeva. Vlastita PHP struktura bez frameworka odabrana je u fazi 1; detalje izraditi u fazi 2.

## Lokalno okruženje — faza 1
- `compose.yaml`: servis `web` gradi se iz `docker/php/Dockerfile`, servis `db` koristi službenu sliku `mysql:8.4`.
- Web koristi `php:8.4-apache`, Composer 2 i dodanu ekstenziju `pdo_mysql`. Slike su vezane uz verzijsku granu; točne dobivene zakrpe evidentirati nakon pokretanja.
- Samo `public/` poslužuje se pregledniku, na `127.0.0.1:8080`. Pristup popisu datoteka je isključen. Aplikacijska ulazna točka dolazi u fazi 2.
- Baza nema objavljen host port. Podaci su u trajnom volumenu `db-data`; aplikacija koristi zaseban DB račun, ne root.
- `.env` ostaje lokalno, `.env.example` sadrži primjer bez stvarnih lozinki. Compose prosljeđuje varijable procesu; PHP će ih čitati kroz `getenv()`, pa zasebna dotenv biblioteka zasad nije potrebna.
- PHP/Composer/MySQL ne instaliraju se posebno na Windows; radi se kroz Docker naredbe iz README-a.

## Baza i vlasništvo
Odabran je MySQL 8.4. Početni kandidati za entitete su korisnik, uloga, članak, kategorija, slika članka, vozilo, servis, točenje goriva, dodatni trošak i podsjetnik. Vijest ima autora i kategoriju te metapodatke slike ako je dodana. Vozilo bi pripadalo jednom korisniku, a evidencije i podsjetnici jednom vozilu. Predlaže se jedna spremljena uloga po korisniku; potvrditi prije sheme.

Prije implementacije odrediti obavezna polja, strane ključeve, pravila brisanja i način povezivanja servisa/goriva s troškovima kako se isti iznos ne bi dvostruko zbrajao. Pravila obračuna potrošnje i troškova također treba izričito definirati. ER dijagram i detalje sheme zasad vodimo ovdje.

## Uloge i pristup
| Uloga | Predviđeni pristup |
| --- | --- |
| Posjetitelj | Objavljene vijesti/savjeti, registracija i prijava |
| Korisnik | Vlastita vozila, evidencije, podsjetnici i statistika |
| Urednik | Izrada i uređivanje vlastitih vijesti/savjeta |
| Administrator | Sve vijesti, kategorije, korisnici, uloge i aktivacija/deaktivacija računa |

Posjetitelj je neprijavljeno stanje; ne mora biti spremljena uloga u bazi. Nasljeđivanje ovlasti urednika i administratora treba potvrditi. Urednička ili administratorska uloga sama po sebi ne daje pristup privatnim evidencijama drugih korisnika.

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

## Prijedlozi za dogovor prije implementacije
Poslovna pravila u nastavku još nisu potvrđena. Tehničke stavke označene kao odabrane pripremljene su u fazi 1; detaljne biblioteke tek treba odabrati.

| Tema | Prijedlog za prvu verziju |
| --- | --- |
| PHP | Odabrano: vlastita struktura bez frameworka i PDO; biblioteke za sanitizaciju i JSON Schema validaciju odabrati uz implementaciju |
| Frontend | HTML/CSS i obični JavaScript s fetch pozivima API-ju; jedna aplikacija i isto porijeklo |
| Autentifikacija | Odabrano: PHP sesija i CSRF token |
| Baza/okruženje | Odabrano: MySQL 8.4, PHP 8.4/Apache i Composer 2 kroz Docker Compose |
| Uloge | Korisnik, urednik, administrator; urednik/admin imaju i mogućnosti običnog korisnika nad vlastitim vozilima, bez pristupa tuđima |
| Vijesti | Jedna kategorija i jedna opcionalna slika; nacrt/objavljeno, uz vrijeme objave; povlačenje vraća u nacrt |
| Kategorije | Zabraniti brisanje kategorije koja sadrži vijesti; prvo promijeniti kategoriju tih vijesti |
| Vozilo | Naziv, marka, model, godina i početna kilometraža; VIN opcionalan radi ručnog unosa; obaveznost/ostala polja tek se dogovaraju |
| Servis | Datum, kilometraža, opis i iznos; radionica/napomena opcionalne |
| Gorivo | Datum, kilometraža, litre, ukupni iznos i oznaka punog spremnika; prva verzija prvenstveno za vozila s tekućim gorivom |
| Troškovi | Servis i gorivo nose vlastiti iznos; dodatni troškovi za registraciju, osiguranje, parking itd.; dashboard zbraja tri izvora jednom, bez kopiranja iznosa |
| Novac/jedinice | EUR, kilometri i litre; novac spremati decimalno, ne kao binarni floating point |
| Potrošnja | Od punog do punog spremnika: zbroj litara nakon početnog punog do završnog punog / razlika kilometraže × 100, uključujući međutočenja; bez dovoljne evidencije prikazati nedostatak podataka, ne nulu |
| Podsjetnici | Datum i/ili kilometraža, status otvoreno/dovršeno; dospjelo kad je dosegnut bilo koji prag; kilometarski status prema posljednjoj evidentiranoj kilometraži |
| Dashboard | Vozilo/razdoblje, servis/gorivo/ostali troškovi i ukupno, potrošnja kada je moguća te dospjeli/nadolazeći podsjetnici; prag za nadolazeće dogovoriti |
| Brisanje | Vozilo s pripadajućim evidencijama nakon jasne potvrde, uz transakciju gdje treba; korisnike primarno deaktivirati |
| VIN | Poziv na izričitu radnju, rezultat predlaže polja koja korisnik potvrđuje; nepotpun rezultat ne blokira ručni unos |

Prije sheme još potvrditi podržavamo li električna vozila i zasebno punjenje, pravila ispravaka kilometraže te detaljna polja/ograničenja. Te nadogradnje nisu preduvjet obavezne jezgre kolegija.

## Vanjski API
Za prijedlog projekta odabran je NHTSA vPIC. [Dokumentacija](https://vpic.nhtsa.dot.gov/api/) i [FAQ](https://vpic.nhtsa.dot.gov/api/home/index/faq) provjereni su 2026-10-20. Javan je i ne zahtijeva registraciju/ključ; servis ima automatsku kontrolu prometa, pa ga ne tretirati kao neograničen resurs. Pokrivenost stranih vozila ovisi o registraciji proizvođača za američko tržište.

Predviđeni zahtjev je `GET /vehicles/DecodeVinValues/{vin}?format=json&modelyear={godina}`. Poziv dolazi iz backenda na izričitu korisničku radnju, s timeoutom 10 sekundi. Godinu poslati kada je poznata. Dohvaćena polja `Make`, `Model`, `ModelYear` i `FuelTypePrimary` samo su prijedlog za potvrdu korisnika; prazna/nepouzdana polja ne prepisuju njegov unos.

Stvarni javni primjer iz dokumentacije, `/vehicles/DecodeVinValues/5UXWX7C5*BA?format=json&modelyear=2011`, provjeren je iz PowerShella 2026-10-20: HTTP 200, JSON, jedan rezultat, BMW / X3 / 2011 / Gasoline, `ErrorCode=6`. Primjer koristi djelomičan VIN, stoga HTTP 200 sam po sebi nije dokaz potpunog dekodiranja. Pri implementaciji obraditi i API-jeve `ErrorCode`/`ErrorText`, ne samo HTTP status. To je provjera prikladnosti servisa, ne dovršena integracija u aplikaciji.

Ne obećavamo potpunu pokrivenost hrvatskih/europskih vozila. Ručni unos ostaje dostupan. Prije faze 6 ponoviti provjeru dostupnosti i reprezentativnih VIN-ova; drugog pružatelja uvoditi tek ako se pokaže potrebnim, uz dokumentiranu zamjenu.

## Otvorene odluke
- Detalji API ugovora i implementacija sesijske autentifikacije.
- Provjera pokrivenosti odabranog VIN servisa prije integracije.
- Podatkovni model, obračun statistike i prikaz podsjetnika.
- ER dijagram, alati za testiranje i okruženje predaje.
