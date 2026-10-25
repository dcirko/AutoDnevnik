# Zahtjevi

Popis je 2026-10-19 uspoređen s `docs/upute.txt` (detaljni zahtjevi, odjeljci 1–18), `docs/upute 2.txt` (predaja) i `docs/Uvodno predavanje.pdf` (29 stranica; lokalna kopija). Izvorni nastavni materijali ostaju lokalno i nisu dio javnog repozitorija. Neoznačena stavka znači da još nije implementirana ili provjerena. Profesorovi primjeri ruta i dodatnih funkcionalnosti nisu automatski naši odabiri tehnologije.

## Obavezni zahtjevi kolegija
- [ ] Frontend aplikacije.
- [ ] PHP backend.
- [x] Početna relacijska baza podataka izrađena i provjerena u fazi 2; uporaba kroz poslovne funkcionalnosti slijedi.
- [ ] CMS/admin sučelje.
- [ ] Uloge: posjetitelj, korisnik, urednik i administrator.
- [ ] Modul vijesti/obavijesti (automobilske vijesti i savjeti).
- [ ] Vlastiti REST API koji aplikacija stvarno koristi.
- [ ] Integriran najmanje jedan vanjski API.
- [ ] Pravilne HTTP metode i statusni kodovi.
- [ ] Validacija ulaznih podataka na backendu.
- [ ] Autentifikacija i autorizacija.
- [ ] Kontinuirani razvoj na GitHubu.
- [ ] Završna dokumentacija.
- [x] Početni ER dijagram usklađen s primijenjenom shemom; održavati ga uz daljnje promjene.
- [ ] API dokumentacija usklađena s implementiranim endpointima.
- [ ] Testovi i evidentirani rezultati provjere.

## Zahtjevi AutoDnevnika
- [x] Odabran Angular/TypeScript i izrađen početni projekt u `frontend/`; poslovno sučelje i API integracija još nisu implementirani.
- [ ] Registracija, prijava i odjava korisnika.
- [ ] Dodavanje, pregled, uređivanje i brisanje vlastitih vozila.
- [ ] Evidencija servisa po vozilu.
- [ ] Evidencija točenja goriva i kilometraže.
- [ ] Evidencija električnih punjenja (kWh, datum, kilometraža i iznos); u prvoj verziji bez stanja baterije i procjene kWh/100 km.
- [ ] Evidencija troškova po vozilu.
- [ ] Podsjetnici za servis, registraciju, tehnički pregled i slične obaveze.
- [ ] Dashboard sa statistikom troškova i potrošnje.
- [ ] CMS za uređivanje i objavu vijesti/savjeta.
- [ ] Javni pregled objavljenih vijesti/savjeta.
- [ ] Administracija korisnika i uloga.
- [ ] JSON kao primarni format razmjene API podataka.
- [ ] Dohvat podataka o vozilu putem vanjskog API-ja, prvenstveno VIN dekodera.

## Sigurnost
- [ ] Lozinke se pohranjuju kroz PHP password hashing i provjeravaju odgovarajućim funkcijama.
- [ ] SQL koristi pripremljene upite i vezane parametre.
- [ ] Korisnički sadržaj sigurno se prikazuje radi zaštite od XSS-a.
- [ ] Zahtjevi koji mijenjaju stanje zaštićeni su od CSRF-a pri autentifikaciji kolačićem.
- [ ] Sesije imaju sigurne postavke kolačića i regeneraciju identifikatora nakon prijave.
- [ ] API na backendu provjerava prijavu, ulogu i vlasništvo nad resursom.
- [ ] Korisnik ne može čitati ni mijenjati vozila i evidencije drugog korisnika.
- [ ] Javna registracija ne omogućuje dodjelu povlaštene uloge.
- [ ] Tajne i pristupni podaci nisu u Git repozitoriju ni frontend kodu.
- [ ] API ne izlaže interne greške, SQL ni osjetljive podatke.
- [ ] Prijava i pozivi vanjskog API-ja imaju primjerenu zaštitu od zlouporabe.

## Potrebno razjasniti
- [x] Usporediti zahtjeve s dostavljenim izvornim uputama.
- [x] Potvrditi obaveznost sheme: JSON Schema ili XSD za barem jedan unos; zbog odabranog JSON-a koristimo JSON Schemu.
- [x] Provjeriti uvjete PHP pristupa: vlastita struktura dopuštena; framework samo uz dogovor s nastavnikom. Korisnik je 2026-10-25 odabrao Laravel umjesto ranijeg pristupa bez frameworka.
- [ ] Potvrditi nastavnikovo odobrenje korištenja Laravela; korisnikov odabir ne predstavlja tu potvrdu.
- [ ] Potvrditi okruženje izvođenja i zahtjeve završne predaje.
- [x] Potvrditi okvirni rok: kraj semestra prema korisniku 2026-10-24.
- [ ] Provjeriti točan LMS datum i status formalne prijave; tema aplikacije je prema korisniku odobrena, radi samostalno.
- [x] Dogovoriti temu seminara: „Usporedba REST i SOAP web servisa u modernim web aplikacijama“.
- [ ] Pročitati posebne upute/predložak seminara povezane u PDF-u i provjeriti formalnu prijavu.
- [x] Odabrati tehnički minimum faze 1: Docker Compose, PHP 8.4/Apache, MySQL 8.4, Composer 2 i PHP sesije; za vanjski servis odabran NHTSA vPIC nakon provjere dokumentacije i javnog upita.
- [x] Potvrditi početna pravila i razraditi polja za shemu u fazi 2; daljnje vremenske provjere razraditi uz CRUD.

## Dopune obaveznih zahtjeva nakon pregleda izvora
Sljedeće stavke razrađuju gornji sažeti popis; nisu dodatne opcionalne funkcionalnosti.

### Javni dio (upute, odjeljak 3)
- [ ] Responzivna početna stranica s opisom aplikacije i izdvojenim sadržajem te stranica o projektu.
- [ ] Popis i detalj objavljenih vijesti; kategorije, pretraga naslova/sadržaja i straničenje.
- [ ] Prikaz naslova, sažetka, sadržaja, kategorije, autora, datuma objave i slike ako postoji.
- [ ] Podaci iz baze/API-ja, ne samo ručno upisani HTML primjeri.
- [ ] Profil prijavljenog korisnika.
- [ ] Razumljive oznake obrazaca, greške uz polja, poruke uspjeha, prazni rezultati i nedostupan servis.

### CMS i kategorije (odjeljak 5)
- [ ] Dodavanje, pregled, pretraživanje i uređivanje vijesti te brisanje uz potvrdu.
- [ ] Naslov, sažetak, sadržaj kroz uređivač obogaćenog teksta i dodjela kategorije.
- [ ] Prijenos, zamjena i uklanjanje slike.
- [ ] Nacrt, objava i povlačenje vijesti iz javnog prikaza.
- [ ] Autor, vrijeme izrade i posljednje izmjene; datum objave za javni prikaz.
- [ ] Nacrti/povučene vijesti nedostupni javnim stranicama i javnom API-ju, uključujući izravan dohvat ID-a.
- [ ] Zasebna administracija kategorija i definirano pravilo brisanja korištene kategorije.

### Korisnici i ovlasti (odjeljak 6)
- [ ] Promjena vlastite lozinke i upravljanje vlastitim profilom.
- [ ] Urednik izrađuje i uređuje vlastite vijesti; ne smije mijenjati tuđe.
- [ ] Administrator upravlja svim vijestima, kategorijama, korisnicima i ulogama.
- [ ] Administratorski pregled korisnika, promjena uloge i aktivacija/deaktivacija.
- [ ] Deaktivacija blokira i već otvorene sesije; provjeravaju se aktivnost i aktualne ovlasti na backendu.
- [ ] Zaštićene radnje provjeravaju pravo pristupa konkretnom zapisu, ne samo vidljivost gumba.
- [ ] Urednička/administratorska uloga ne daje pristup privatnim vozilima i evidencijama drugih korisnika.

### Organizacija i baza (odjeljci 4 i 7)
- [x] Početna PHP struktura odvaja konfiguraciju, bazu i prikaz; poslovne obrade dodavati uz buduće funkcionalnosti.
- [x] Početni model korisnika, uloga, vijesti, kategorija i slika uz entitete AutoDnevnika.
- [x] Početni primarni/strani ključevi, tipovi, pravila brisanja i jedinstven e-mail provjereni testovima.
- [x] Instalacijska SQL skripta i izmišljeni demo podaci: sve uloge, više kategorija, nacrti i objavljene vijesti. Stvarne demo slike dodati uz CMS.
- [ ] Transakcije za promjene povezanih zapisa koje moraju ostati usklađene.
- [ ] Dokumentirane biblioteke i ovisnosti.

### API ugovor (odjeljak 8)
- [ ] Javni dohvat popisa/detalja vijesti, zaštićeno stvaranje/izmjena/brisanje te dohvat kategorija.
- [ ] Content-Type, dosljedan JSON uspjeh/greška, ispravne metode i statusi; 204 nema tijelo.
- [ ] Validacija obaveznih polja, tipova i dopuštenih vrijednosti; autentifikacija/autorizacija izmjena.
- [ ] Straničenje i barem jedan filtar; javne vijesti dodatno zadovoljavaju pretragu i kategorije.
- [ ] JSON Schema barem jednog zahtjeva za unos i demonstrirana provjera valjanog/nevaljanog primjera.
- [ ] Stvarna uporaba API-ja u aplikaciji, najmanje popis i detalj vijesti.

### Vanjski servis (odjeljak 9)
- [ ] Prije izbora provjeriti dokumentaciju, dostupnost, pokrivenost i ograničenja.
- [ ] Demonstrirati stvaran zahtjev, čitanje odgovora, izdvajanje podataka i uporabu u aplikaciji.
- [ ] Obraditi uspjeh, nedostupnost/pogrešku i neočekivanu strukturu/nedostajuća polja.
- [ ] Timeout i sprječavanje nepotrebnog ponavljanja poziva.
- [ ] Ako se koriste spremljeni odgovori, prikazati vrijeme posljednjeg uspješnog dohvata.
- [ ] Jasno označeni testni odgovori smiju demonstrirati kvar; ne zamjenjuju stvarnu integraciju.

### Dopune sigurnosti (odjeljak 10)
- [ ] Backend sanitizacija dopuštenog HTML-a iz uređivača vijesti.
- [ ] Provjera vrste i veličine slika te onemogućeno izvršavanje prenesenih datoteka.
- [ ] Siguran završetak sesije pri odjavi.
- [ ] HTTPS na javno dostupnoj instalaciji, ako se postavlja.
- [ ] Ako se uvede XML obrada, spriječiti vanjske entitete i nedopuštene resurse.

## Minimalni scenariji provjere (odjeljak 13)
Za svaki scenarij zapisati očekivani i dobiveni rezultat. Ključne provjere prava pristupa i poslovnih izračuna ciljano automatizirati.

- [ ] Valjana/nevaljana registracija i već registriran e-mail.
- [ ] Ispravna/pogrešna lozinka, odjava, promjena lozinke i deaktivacija otvorene sesije.
- [ ] Administracija bez prijave, urednik mijenja tuđu vijest, korisnik pokušava promijeniti vlastitu ulogu.
- [ ] Stvaranje, uređivanje, objava, povlačenje i brisanje vijesti.
- [ ] Javni dohvat nacrta, prazna pretraga i nepostojeći zapis.
- [ ] Nevaljan API unos, zahtjev bez ovlasti i valjan/nevaljan JSON Schema primjer.
- [ ] Vanjski API: uspjeh, nedostupnost i neočekivan odgovor.
- [ ] Nedopušten upload i mobilni prikaz.
- [ ] Pristup vozilu/evidencijama drugog korisnika, brisanje povezanih zapisa i izračuni troškova/potrošnje.

## Potpuna predaja (odjeljci 14–16, kratke upute i PDF)
- [ ] Javni GitHub repozitorij s kontinuiranom poviješću, kodom, dokumentacijom, .gitignore, primjerom konfiguracije bez tajni, shemom/migracijama i demo podacima.
- [ ] README: verzija PHP-a, ekstenzije, baza, ovisnosti, konfiguracija i ponovljiv postupak instalacije/pokretanja.
- [ ] ER dijagram, opis uloga i API dokumentacija: metoda, putanja, ovlasti, parametri, zahtjev/odgovor i moguće greške svake rute.
- [ ] Opis vanjske integracije i evidentirani rezultati testiranja.
- [ ] Samostalni seminar prema zasebnim uputama, citirani izvori i Turnitin postupak prema uputama kolegija.
- [ ] Prezentacija i pripremljena obrana svih ključnih tokova, uloga i barem jedne pogreške.
- [ ] Kratko navesti korištene AI alate i svrhu uporabe; razumjeti i provjeriti predani kod.
- [ ] Video snimka ključnih stranica/funkcionalnosti na YouTubeu kao Unlisted ili Public, ne Private.
- [ ] .txt s imenom/prezimenom, predmetom, nazivom projekta i GitHub/YouTube poveznicama.
- [ ] Moodle/LMS: .txt i seminar te ostalo prema konkretnom zadatku.
- [ ] Teams / kanal grupe / Datoteke: video ili poveznica, 5–6 screenshotova (najmanje jedna slika po stranici prema kratkim uputama), GitHub i YouTube poveznice, seminar PDF/DOCX. Ako stranica ima više, pripremiti više snimki za pokrivanje ključnih prikaza.
- [ ] Testne pristupne podatke dostaviti kroz LMS; ne objavljivati stvarne/produkcijske lozinke.
- [ ] Poveznica na demonstracijsku instalaciju ako se aplikacija javno postavlja; javni hosting nije izričito obavezan u detaljnim uputama.

## Granica prve verzije
Korisnik je 2026-10-19 prihvatio sve gore navedene tematske evidencije, jednostavan dashboard i podsjetnike unutar aplikacije. E-mail podsjetnici, XML, SOAP, AI modul i napredna analitika nisu dio prve verzije. Docker je moguć izbor okruženja, a ne obavezna funkcionalnost kolegija. Polja i pravila obračuna iz `ARCHITECTURE.md` još su prijedlozi.

## Kriterij dovršenosti funkcionalnosti
Funkcionalnost je dovršena kada radi kroz frontend i odgovarajući vlastiti API, trajno sprema podatke gdje je potrebno, provjerava ulaze i prava pristupa, smisleno prikazuje greške te ima odgovarajuću provjeru i ažuriranu dokumentaciju.
