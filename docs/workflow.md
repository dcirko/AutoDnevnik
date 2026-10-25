# Pravila razvoja AutoDnevnika

Ove upute vrijede za Codex i druge AI alate pri radu u ovom repozitoriju. Cilj je jednostavan, dosljedan kod koji autor razumije i može održavati. Korijenski `AGENTS.md` upućuje na ovaj dokument radi učitavanja pravila u budućim zadacima.

## Prije pisanja koda

1. Pročitaj `PROJECT.md`, `REQUIREMENTS.md` i relevantan zadatak u `TASKS.md`. Za tehničke odluke provjeri `DECISIONS.md` i odgovarajući dio `ARCHITECTURE.md`.
2. Pregledaj `git status` i postojeći kod koji će promjena zahvatiti. Sačuvaj korisnikove lokalne izmjene i ne uključuj ih usput u svoj commit.
3. Potraži sličnu funkcionalnost prije dodavanja nove klase, komponente, servisa ili pomoćne funkcije.
4. Odredi najmanju cjelinu koja ispunjava zahtjev i način provjere. Za razvojni zadatak koristi predložak iz `docs/TASKS.md` ili GitHub obrazac „Razvojni zadatak“. Za veći zahvat kratko navedi korake; za sitnu izmjenu nije potreban zaseban plan.
5. Ne izmišljaj poslovna pravila, API ugovore ni status dovršenosti. Provjerljive tehničke nepoznanice istraži sam; pitaj kada odgovor bitno mijenja ponašanje aplikacije.

## Stil koda

- Prati stil okolnog koda, konfiguraciju formatiranja i postojeću strukturu projekta. Ne preformatiraj nepovezane datoteke.
- Piši izravna rješenja. Nova apstrakcija treba riješiti stvarno ponavljanje ili jasno odvojiti odgovornost.
- Ne dodaj generičke repozitorije, tvornice, bazne klase, konfiguracijske slojeve ni vlastiti framework za jednu konkretnu potrebu.
- Koristi jasna imena iz domene, primjerice `vehicleId`, `fuelEntries` ili `totalCost`. Izbjegavaj `data`, `result`, `manager` i `helper` kada se iz imena može preciznije opisati sadržaj ili odgovornost.
- Funkcija treba imati povezanu odgovornost. Nemoj stvarati niz funkcija koje samo prosljeđuju iste argumente bez dodatne vrijednosti.
- Ne kopiraj poslovnu logiku između ruta, komponenti i servisa. Iskoristi postojeći kod ili izdvoji zajednički dio kada to smanjuje stvarno ponavljanje.
- Ne ostavljaj neiskorištene importe, varijable, zakomentirani kod, prazne metode, probne ispise ni zamjenske implementacije koje glume gotovu funkcionalnost.
- Ne dodaj emojije, ukrasne Unicode simbole, ASCII bannere ni oznake poput `NEW`, `FIXED` ili `AI-generated` u kod, logove i commit poruke. Standardni znakovi jezika i hrvatska slova ostaju normalan dio sadržaja.
- Ne dodaj pohvale vlastitom kodu, npr. „robust“, „elegant“, „production-ready“ ili „enterprise-grade“. Nazivi i dokumentacija trebaju opisivati ponašanje.
- Ne kompliciraj kod radi oponašanja čovjeka. Čitljivost, ispravnost i dosljednost imaju prednost pred stilskim dojmom.

## Komentari

- Objašnjavajući komentari dopušteni su samo na razini cijele datoteke: jedan kratak blok na početku, samo ako objašnjava svrhu, ograničenje ili važnu pretpostavku koja nije očita iz naziva.
- Početni blok nije obavezan. Ne dodaj isti generički opis u svaku datoteku.
- Ne dodaj komentare uz naredbe, iznad metoda, funkcija, klasa ili polja, unutar predložaka ni između CSS/SQL sekcija. To uključuje opisni PHPDoc i JSDoc.
- Ne prepričavaj kod komentarima poput „dohvati korisnika“, „provjeri unos“ ili „vrati rezultat“. Poboljšaj imenovanje i strukturu.
- Duža obrazloženja i poslovne odluke pripadaju u `docs/ARCHITECTURE.md` ili `docs/DECISIONS.md`, a otvoreni posao u `docs/TASKS.md`, umjesto `TODO`/`FIXME` komentara.
- Zakonske/licencne obavijesti i nužne direktive alata nisu objašnjavajući komentari. Sačuvaj ih; ne dodaj direktive za utišavanje grešaka da bi provjere prošle.
- Ovo pravilo primjenjuj na novi i izmijenjeni kod. Nemoj usput čistiti sve komentare u repozitoriju niti ručno uređivati generirane datoteke i vanjske ovisnosti.

## Obrasci koje treba prepoznati i izbjeći

Ovo je praktičan popis za pregled promjene, a ne detektor AI autorstva. Isti problemi pojavljuju se i u ručno pisanom kodu. Komentari i emojiji ovdje su projektna stilska odluka; istraživanja ne dokazuju autorstvo pojedine datoteke na temelju takvih znakova.

| Obrazac | Pravilo za ovaj projekt |
| --- | --- |
| Pretjerano komentiranje i tutorijalski tekst | Kod izražava korake; kratak opis datoteke koristi se samo kada pomaže. |
| Generički boilerplate i pretjerana arhitektura | Dodaj samo strukturu potrebnu za sadašnji zahtjev. |
| Kopiranje sličnih blokova | Prvo potraži postojeću implementaciju i zajedničku logiku. |
| Rješenje izdvojeno od ostatka projekta | Koristi postojeće tokove, nazive, odgovore API-ja i granice backend/frontend dijela. |
| Izmišljene metode, paketi i opcije konfiguracije | Provjeri instaliranu verziju, stvarne potpise i službenu dokumentaciju. |
| Obrambeni kod bez razloga | Validiraj podatke na ulaznim granicama; ne ponavljaj istu provjeru kroz svaki interni sloj. |
| Gutanje grešaka i lažni uspjeh | Ne koristi prazan `catch`, PHP `@` ni zadanu praznu listu koja kvar prikazuje kao uspješan dohvat. |
| Pretjerano korištenje `any`, castova i non-null tvrdnji | Modeliraj stvarne tipove i provjeri nepouzdane podatke na ulazu. |
| Implementacija samo sretnog puta | Obradi relevantne greške, prazne rezultate, autorizaciju i rubne vrijednosti. |
| Nepotreban `async`, retry ili cache | Uvedi ih samo uz stvarnu potrebu; ponavljanje upisa ne smije stvarati duplikate. |
| Širok refaktor uz malu funkcionalnost | Zadrži promjenu u opsegu zadatka; nepovezane prijedloge navedi odvojeno. |
| Testovi koji samo potvrđuju vlastitu implementaciju | Provjeri očekivano ponašanje, regresiju i važne slučajeve greške. |
| Brisanje testa ili popuštanje provjera radi prolaza | Ispravi uzrok; očekivanje mijenjaj samo uz opravdanu promjenu zahtjeva. |
| Izmišljeni podaci koji skrivaju nedovršenu integraciju | Demo podaci moraju biti jasno odvojeni od stvarnih odgovora aplikacije. |
| Tvrdnja da je sve provjereno bez izvršenih provjera | Navedi što je pokrenuto, rezultat i ono što nije provjereno. |

## Primjena na ovaj projekt

- Backend koristi Laravel 13 na PHP-u 8.4, MySQL i Composer. Frontend ostaje Angular/TypeScript. Koristi Laravelove rute, kontrolere, validaciju, autorizaciju i migracije umjesto paralelne vlastite infrastrukture. Ne mijenjaj tehnološki okvir kao usputni dio zadatka.
- Korisnik radi uz Codex koji piše većinu koda. Uz svaku dovršenu funkcionalnost ukratko objasni put zahtjeva, ulogu ključnih datoteka, poslovna pravila i način provjere. Objašnjenja pripadaju razgovoru ili dokumentaciji, ne komentarima uz naredbe u kodu.
- Koristi PHP tipove i `declare(strict_types=1)` prema postojećem obrascu. TypeScript tipove nemoj zaobilaziti radi bržeg prolaza builda.
- SQL upiti koriste parametre za vrijednosti. Pristup privatnim evidencijama mora provjeriti vlasništvo na backendu; skrivanje gumba u Angularu nije autorizacija.
- Čuvaj dogovoreni oblik JSON odgovora. Interni detalji iznimki i tajne ne smiju završiti u odgovoru ili korisničkoj poruci.
- Zadrži stvarnu validaciju, autorizaciju, CSRF zaštitu i sigurnosne provjere. Pravilo protiv suvišnog obrambenog koda nije razlog da se one uklone.
- Za vanjski VIN API obradi timeout, neuspjeh i nepotpune podatke prema arhitekturi; ručni unos ostaje dostupan.
- Ne miješaj litre i kWh i ne zbrajaj isti trošak dvaput. Ne pretpostavljaj da su sva vozila benzinska.
- Nove ovisnosti uvodi samo uz konkretnu potrebu koju postojeći kod i standardni alati ne pokrivaju. Provjeri paket, verziju i licencu te ažuriraj pripadajući lockfile.
- Ne spremaj tajne, `.env`, stvarne osobne podatke ni lokalne demo vjerodajnice u Git. Ne briši podatke ili Docker volumene radi popravljanja testova.

## Provjera prije završetka

1. Pregledaj diff: jesu li promjene povezane sa zadatkom i jesu li ostali duplicirana logika, suvišni komentari, neiskorišten kod ili probni ispisi?
2. Pokreni provjere primjerene promjeni. Za backend iz korijena projekta pokreni `docker compose exec web composer lint`, `docker compose exec web composer format:check` i `docker compose exec web composer test`; testovi zahtijevaju pokrenute servise i instaliranu shemu. Za frontend pokreni `npm run format:check` i `npm run build` iz `frontend/`. GitHub workflow `.github/workflows/ci.yml` pokreće iste provjere na push i pull request događaje.
3. Frontend trenutačno nema `test` ni `lint` npm skriptu. Ne prijavljuj ih kao izvršene i ne pretpostavljaj da uspješan build potvrđuje ponašanje u pregledniku.
4. Za novu poslovnu logiku ili ispravak greške dodaj smislen test kada postoji odgovarajuća infrastruktura; ako ne postoji, provjeri ponašanje drugim prikladnim postupkom i navedi ograničenje. Za samu dokumentaciju provjeri poveznice i diff; aplikacijski build nije potreban.
5. Ne mijenjaj testove samo da prolaze. Ako provjera ne radi zbog okruženja, opiši prepreku i ne označavaj je uspješnom.
6. Ažuriraj pogođenu dokumentaciju kada se promijene ponašanje, arhitektura ili plan. Ne označavaj buduću funkcionalnost dovršenom zato što postoji njezin kostur.
7. Završni izvještaj neka kratko kaže što je promijenjeno, kako je provjereno i postoji li preostalo ograničenje.

Formatiranje popravi naredbom `npm run format` u `frontend/` ili `docker compose exec web composer format` iz korijena projekta. Prettier koristi `frontend/.prettierrc`, a PHP CS Fixer `backend/.php-cs-fixer.dist.php` s PSR-12 pravilima bez rizičnih preinaka. Formatter uređuje raspored koda; pravila o komentarima, imenovanju i poslovnoj logici i dalje se provjeravaju pregledom.

## Commit poruke

Koristi Conventional Commits oblik: `tip(opseg): kratak opis`. Opseg nije obavezan. Prefiksi su standardni engleski, a opis za ovaj projekt piši prirodnim hrvatskim jezikom, bez završne točke.

- `feat`: nova funkcionalnost; `fix`: ispravak greške.
- `refactor`: reorganizacija bez promjene ponašanja; `perf`: poboljšanje performansi.
- `docs`: dokumentacija; `test`: testovi; `style`: samo formatiranje koda.
- `build`: build i ovisnosti; `ci`: automatizirane provjere; `chore`: ostalo održavanje.
- Opis treba reći što se promijenilo, npr. `fix(api): odbij pristup tuđem vozilu`, umjesto `fix: razni popravci`.
- Jedan commit predstavlja jednu smislenu cjelinu. Ne miješaj funkcionalnost, nepovezan refaktor i preformatiranje projekta.
- Bez emojija, marketinških izraza, dugih popisa datoteka i generičkih fraza poput „enhance functionality“, „comprehensive improvements“ ili „update code“.
- Tijelo poruke dodaj samo kada treba objasniti razlog, ograničenje ili promjenu ugovora. Za nekompatibilnu promjenu koristi `!` i opiši posljedicu u `BREAKING CHANGE:` podnožju.
- Ne dodaj automatski promotivne potpise alata. Sačuvaj obaveznu atribuciju i projektni zapis o uporabi AI-ja; prirodan stil poruke ne znači prikrivanje korištenih alata.
- Pisanje ovih pravila nije nalog za commit ili push. Izvrši ih kada su dio korisnikova zadatka ili već dogovorenog načina rada.

Primjeri:

```text
feat(vozila): dodaj ručni unos vozila
fix(api): odbij pristup tuđem vozilu
refactor(troskovi): izdvoji zajednički izračun ukupnog troška
docs: dodaj pravila razvoja
test(api): provjeri istek korisničke sesije
```

## Izvori i granice zaključaka

Pregledano 2026-10-25. Pravila su kombinacija korisnikovih stilskih zahtjeva, postojećih projektnih odluka i preporuka iz izvora. Tablica nije rang-lista učestalosti: izvori ne daju univerzalni popis „najpoznatijih AI znakova“.

- [GitHub: Review AI-generated code](https://docs.github.com/en/copilot/tutorials/review-ai-generated-code) preporučuje provjeru usklađenosti s projektom, stvarnih API-ja i ovisnosti, funkcionalnosti i testova. Posebno upozorava na izmišljene API-je i uklanjanje testova umjesto ispravka uzroka.
- [GitClear: The Maintainability Gap](https://www.gitclear.com/the_ai_code_quality_maintainability_gap) opisuje trendove dupliciranja, slabije ponovne uporabe i prikrivanja grešaka u analiziranom skupu promjena. To je istraživanje dobavljača alata i pokazatelj rizika održavanja, a ne dokaz da je određeni obrazac ili pojedina greška nastala zbog AI-ja.
- [Conventional Commits 1.0.0](https://www.conventionalcommits.org/en/v1.0.0/) definira oblik commit poruka i značenje `feat`, `fix` i nekompatibilnih promjena. Hrvatski opis je lokalna odluka ovog projekta.
- [OpenAI: Custom instructions with AGENTS.md](https://learn.chatgpt.com/docs/agent-configuration/agents-md) opisuje učitavanje projektnih uputa. Zato korijenski `AGENTS.md` izričito nalaže čitanje ovog dokumenta; proizvoljna datoteka u `docs/` sama se ne učitava kao standardna projektna uputa.
