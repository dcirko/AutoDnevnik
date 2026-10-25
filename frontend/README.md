# AutoDnevnik frontend

Početni Angular 22/TypeScript projekt generiran Angular CLI-jem 22.2.4.
Koristi strict TypeScript, standalone korijensku komponentu, CSS i prazne rute.
Poslovne stranice, API integracija i autentifikacija još nisu implementirani.

Potreban je Node.js 24 LTS, najmanje 24.15.0, i npm. Iz ovog direktorija:

```powershell
npm ci
npm start
```

Razvojni poslužitelj: `http://localhost:4200`.

```powershell
npm run build
```

Produkcijski izlaz: `dist/autodnevnik/browser/`.
PHP backend i MySQL pokreću se prema [glavnom README-u](../README.md).
Proxy prema API-ju i Apache posluživanje builda dodaju se uz buduće funkcionalnosti.
