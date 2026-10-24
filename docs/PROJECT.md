# AutoDnevnik

## Svrha
AutoDnevnik je web aplikacija za vlasnike vozila, razvijena za kolegij **Napredne tehnike projektiranja web servisa**. Omogućuje pregled podataka o vozilima, održavanju, gorivu, kilometraži i troškovima na jednom mjestu.

Prioritet su obavezni zahtjevi kolegija, jednostavna arhitektura i postupni razvoj. Dodatne mogućnosti uvodimo nakon što osnovna aplikacija radi i ispunjava obaveze.

## Korisnici i funkcionalnosti
- **Posjetitelj:** čita javne vijesti i savjete te može otvoriti registraciju i prijavu.
- **Korisnik:** upravlja vlastitim vozilima i njihovim servisima, gorivom, kilometražom, troškovima i podsjetnicima; pregledava vlastitu statistiku.
- **Urednik:** upravlja automobilskim vijestima i savjetima kroz CMS.
- **Administrator:** upravlja korisnicima i ulogama te ima pristup administraciji sadržaja.

## Opseg
Planirane funkcionalnosti su registracija i prijava, vozila, servisne evidencije, gorivo i kilometraža, troškovi, podsjetnici, dashboard, CMS i administracija. Aplikacija koristi vlastiti REST API i barem jedan vanjski API, prvenstveno za dekodiranje VIN-a.

Za prvu cjelovitu verziju razvijamo jednostavne obrasce, popise i preglede te podsjetnike unutar aplikacije. Slanje e-mailom, napredna analitika i druge nadogradnje nisu uključeni. Ne uvodimo dodatne servise ni složenu infrastrukturu bez potrebe.

## Tehnološki okvir
- PHP 8.4 backend bez frameworka, MySQL 8.4 i PDO.
- HTML, CSS i JavaScript frontend.
- Vlastiti REST API koji frontend stvarno koristi; JSON kao primarni format razmjene.
- Git i kontinuirani razvoj na GitHubu.
- Docker Compose: PHP/Apache i Composer u web kontejneru, MySQL u zasebnom kontejneru.
- PHP sesije i CSRF zaštita; frontend i API na istom porijeklu.
- Za prijedlog projekta odabran je NHTSA vPIC VIN API, uz ručni unos i jasno ograničenu pokrivenost.

## Kako koristimo dokumentaciju
Prije veće odluke ili izmjene prvo pročitati ovaj dokument i `REQUIREMENTS.md`, zatim `TASKS.md`, `DECISIONS.md` i relevantan dio `ARCHITECTURE.md`.

- `PROJECT.md` i `REQUIREMENTS.md` glavni su izvor istine za cilj i zahtjeve.
- `ARCHITECTURE.md` opisuje tehnički pristup i jasno razlikuje prijedloge od potvrđenih odluka.
- `TASKS.md` bilježi trenutačni rad i sljedeće korake.
- `DECISIONS.md` bilježi važne odluke i razloge.

Kod promjene opsega, arhitekture ili plana ažurirati odgovarajuće dokumente u istoj promjeni. Zadatak označiti dovršenim tek kad rezultat postoji i provjeren je. Nove dokumente `API.md`, `DATABASE.md` i `TESTING.md` izdvojiti tek kada sadržaj preraste postojeće dokumente.

## Trenutačno stanje
Dana 2026-10-19 pregledane su izvorne upute `upute.txt`, kratke upute za predaju `upute 2.txt` i korisnikov PDF `Uvodno predavanje.pdf`. Zahtjevi i faze rada usklađeni su s tim izvorima. Izvorne datoteke služe kao dokaz zahtjeva; tehnički prijedlozi postaju odluke tek nakon dogovora.

Korisnik radi samostalno i potvrdio je da je tema AutoDnevnik odobrena. Prihvaćen je opseg vozila, servisa, goriva/kilometraže, troškova, jednostavnog dashboarda i podsjetnika unutar aplikacije. E-mail podsjetnici nisu dio prve verzije. Obavezni javni sadržaj, CMS, administracija, vlastiti API i vanjski servis ostaju prioritet.

Dana 2026-10-20 započela je faza 1. Korisnik je odabrao Docker nakon prijedloga PHP/MySQL/Apache/Composer okruženja. Pripremljene su razvojne konfiguracije, README, primjer lokalne konfiguracije i Git/GitHub repozitorij.

U fazi 2, 2026-10-24, izrađeni su shema baze, ER dijagram, demonstracijski podaci i početna PHP struktura s JSON provjerom pristupa bazi. Korisnik je potvrdio benzinska/dizelska i električna vozila; točenja i punjenja vode se u zajedničkoj evidenciji energije uz odvojene jedinice. Električna vozila za početak prate kWh i troškove, bez stanja baterije i procjene kWh/100 km. Prijava, javne vijesti, CMS i privatni CRUD još nisu implementirani. Rezultati provjera i sljedeći zadaci nalaze se u `TASKS.md`.

Plan prati profesorovih 15 tjedana u `TASKS.md`. Korisnik je 2026-10-24 potvrdio rok do kraja semestra; točan kalendarski datum provjeriti u LMS-u. Smislene izmjene spremamo i šaljemo na GitHub svaki tjedan ili svaka dva tjedna, uz ranije commitove kada je korisna cjelina dovršena.

## Prijedlog projekta za fazu 1
- **Naziv:** AutoDnevnik.
- **Problem:** vlasnik podatke o servisima, gorivu, troškovima i rokovima vozila često vodi na više mjesta, pa teško prati ukupne izdatke i sljedeće obaveze.
- **Cilj:** objediniti te podatke u jednostavnoj web aplikaciji s privatnim evidencijama vlasnika.
- **Ciljani korisnici:** vlasnici jednog ili više vozila; urednici automobilskih sadržaja i administrator aplikacije.
- **Planirane funkcionalnosti:** vozila, servisi, gorivo/kilometraža, dodatni troškovi, podsjetnici u aplikaciji, dashboard, javne vijesti/savjeti, CMS i administracija korisnika/uloga.
- **Tehnologije:** PHP 8.4 bez frameworka, PDO, MySQL 8.4, Apache, HTML/CSS/JavaScript, JSON REST API, PHP sesije, Composer i Docker Compose.
- **Vanjski servis:** NHTSA vPIC za prijedlog marke, modela, modelne godine i dostupnih tehničkih podataka pri unosu VIN-a. Nepotpuna pokrivenost, posebno vozila koja nisu namijenjena američkom tržištu, ne smije blokirati ručni unos.
- **Organizacija:** samostalan rad; kontinuirano verzioniranje na GitHub računu `dcirko`.
- **Status:** pripremljen prijedlog u projektnoj dokumentaciji; ništa nije poslano profesoru ili LMS-u kroz ovaj rad. Tema aplikacije već je odobrena prema korisniku.

## Seminar — zasebna obaveza
Seminar i projekt AutoDnevnik su odvojene cjeline, prema korisnikovoj uputi 2026-10-24. Tema seminara je „Usporedba REST i SOAP web servisa u modernim web aplikacijama“. Seminar nije dokumentacija AutoDnevnika niti njegova razvojna faza. Formalni status prijave i poseban predložak još nisu provjereni; te stavke ne blokiraju razvoj aplikacije.

## Pregled aplikacije
AutoDnevnik je osobni dnevnik za jedno ili više vlastitih vozila. Korisnik otvara račun, unosi vozilo ručno ili uz pomoć VIN servisa, vodi evidencije i pregledava troškove te obaveze. Javni dio donosi automobilske vijesti i savjete s kategorijama, pretragom i straničenjem. Urednik izrađuje i uređuje vlastite vijesti, a administrator upravlja svim vijestima, kategorijama, korisnicima i ulogama.

Predviđene cjeline su početna stranica, vijesti i detalj vijesti, informacije o projektu, registracija/prijava, profil i promjena lozinke, dashboard, popis i detalj vozila s evidencijama i podsjetnicima, CMS te administracija. API se razvija uz funkcionalnosti koje ga koriste, a u 9.–10. tjednu sustavno se dovršava, dokumentira i provjerava.
