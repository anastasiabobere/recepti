# Receptes Platforma

## Anastasija Bobere, ab25286

## Projekta apraksts

Šī projekta ietvaros tiek izstrādāta interneta recepšu platforma, kas ļauj lietotājiem pievienot, pārvaldīt un koplietot receptes. Platforma paredzēta ēdienu gatavošanas entuziastiem, kuri vēlas glabāt savas receptes, meklēt citu lietotāju publicēto saturu un iesaistīties kopienā.

---

## Izstrādes platforma

Sistēma izstrādāta, izmantojot:

- **PHP 8.3**
- **Laravel Framework**
- **MySQL** datubāzi
- **GitHub** koda versiju kontrolei
- **Railway** aplikācijas izvietošanai (deployment)

---

## Galvenā funkcionalitāte

Sistēma ļauj lietotājiem:

- pievienot receptes (ar sastāvdaļām, aprakstu un attēliem);
- sadalīt receptes kategorijās
  (piemēram: deserti, zupas, brokastis, vegāniskie ēdieni);
- pievienot atslēgas vārdus (tags)
  (piemēram: medus kūka, siļķe kažokā, ābolu pīrāgs, rosols);
- meklēt receptes pēc:
    - nosaukuma
    - sastāvdaļām
    - kategorijas

- skatīt citu lietotāju receptes;
- vērtēt receptes;
- komentēt receptes.

---

## Biznesa joma

Aplikācija darbojas **ēdienu un dzīvesstila digitālajā jomā**, piedāvājot platformu recepšu glabāšanai un koplietošanai.

Galvenie uzdevumi:

- vienkāršot recepšu pārvaldību;
- veicināt lietotāju iesaisti (komentāri, vērtējumi);
- nodrošināt ērtu meklēšanu un filtrēšanu;
- veidot kopienu ap ēdienu gatavošanu.

---

## Datu reģistri

Galvenie sistēmas objekti:

- **User (Lietotājs)**
- **Recipe (Recepte)**
- **Category (Kategorija)**
- **Tag (Atslēgas vārds)**
- **Comment (Komentārs)**

### Attiecības starp datiem

- Viens lietotājs var izveidot vairākas receptes.
- Viena recepte pieder vienai kategorijai.
- Vienai receptei var būt vairāki atslēgas vārdi.
- Vienai receptei var būt vairāki komentāri.
- Katrs komentārs pieder konkrētam lietotājam.

---

## Lietotāju lomas

### Vienkāršs apmeklētājs

Var:

- apskatīt publiskās receptes;
- izmantot meklēšanu.

### Reģistrēts lietotājs

Var:

- pievienot receptes;
- rediģēt savas receptes;
- dzēst savas receptes;
- komentēt receptes;
- vērtēt receptes;
- pievienot atslēgas vārdus.

### Administrators

Var:

- pārvaldīt visas receptes;
- dzēst nepiemērotu saturu;
- bloķēt lietotājus.

---

## Tipiskie lietošanas scenāriji

### 1. Recepšu pārlūkošana

Apmeklētājs atver mājaslapu un meklē recepti pēc atslēgvārda, piemēram, **“pankūkas”**. Sistēma parāda atbilstošos rezultātus.

### 2. Receptes pievienošana

Reģistrēts lietotājs piesakās sistēmā, aizpilda receptes formu (nosaukums, sastāvdaļas, apraksts) un saglabā recepti.

### 3. Komentēšana

Lietotājs atver recepti un pievieno komentāru.

### 4. Satura moderācija

Administrators atrod neatbilstošu saturu un dzēš to no sistēmas.

---

## Sistēmas arhitektūra (MVC)

### Modeļi (Models)

- User
- Recipe
- Category
- Tag
- Comment

### Skati (Views)

- sākumlapa (recepšu saraksts)
- receptes detalizētais skats
- receptes pievienošanas forma
- lietotāja profils

### Kontrolleri (Controllers)

- RecipeController
- UserController
- CommentController
- AdminController

---

## Sistēmas saskarne

Receptes detalizētajā skatā lietotājs var:

- izlasīt receptes aprakstu;
- apskatīt komentārus;
- atrast citas tā paša lietotāja receptes;
- pāriet uz:
    - galveno lapu
    - savu profilu
    - citām receptēm

---

## API Integrācijas

Projektā tiek izmantota ārēja API integrācija:

### MyMemory Translation API

**MyMemory Translation API** tiek izmantots teksta tulkošanas funkcionalitātei, ļaujot nepieciešamības gadījumā tulkot receptes vai lietotāju saturu dažādās valodās.

---

## Izvietošana (Deployment)

Aplikācija tiek izvietota, izmantojot **Railway**, kas nodrošina:

- vienkāršu deployment procesu;
- servera hostingu;
- datubāzes savienojamību;
- automātisku atjauninājumu izvietošanu.

---

## Acknowledgements

- Laravel
- PHP
- MySQL
- GitHub
- MyMemory Translation API
- Railway
