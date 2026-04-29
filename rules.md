# rules.md

## Doel van dit document
Dit document is de vaste bouw- en schrijfrichtlijn voor dit project in VS Code. Alles wat gegenereerd, aangepast of voorgesteld wordt, moet compatibel zijn met:

- Laravel 13
- Livewire 4
- de officiële Laravel Livewire starter kit die al als basis is geïnstalleerd
- Flux UI free tier
- MySQL of MariaDB
- Pest

Dit project is geen generieke demo-app. Dit project is een CMS en webshop-backend in Laravel 13 + Livewire 4, gebouwd op basis van de aangeleverde frontend uit `die all hier staat`, binnen het bestaande project `webshop`.

De frontend uit de zip is het visuele referentiepunt. De backend, CMS-structuur, business logic, database, authenticatie, checkout en admin flows moeten hier logisch op aansluiten.

---

## Projectcontext

### Startpunt
- Het project is al opgezet en geïnstalleerd.
- De codebase draait al op Laravel 13 en Livewire 4.
- We bouwen verder op de bestaande starter kit, niet opnieuw vanaf nul.
- Het zip-bestand `design zit al in de app` bevat de frontend die functioneel en visueel geanalyseerd moet worden.
- Het examendocument bepaalt de functionele en architecturale verwachtingen.

### Hoofddoel
Bouw een senior-level mini webshop/CMS met:
- publieke shopfrontend
- productdetailpagina's
- winkelmandje
- checkout met Stripe
- authenticatie
- social login
- admin backend voor producten, categorieën en orders
- duidelijke architectuur volgens Laravel conventies

---

## Niet-onderhandelbare versie- en syntaxregels

### Algemeen
Gebruik uitsluitend syntax, projectstructuur en patronen die compatibel zijn met Laravel 13 en Livewire 4.

### Verboden
Gebruik nooit:
- Livewire 2 of Livewire 3 syntax als die afwijkt van Livewire 4
- oude Volt route-syntax zoals `Volt::route()`
- voorbeelden gebaseerd op Laravel 10, 11 of 12 als ze niet 100 procent compatibel zijn
- Jetstream-aanpak als vervanging van de huidige starter kit
- Filament als admin framework voor dit project, tenzij dit expliciet later beslist wordt
- Inertia, Vue, React of Alpine-first oplossingen voor zaken die in Livewire 4 thuishoren
- business logic in Blade views
- business logic in routes
- business logic in models behalve scopes, accessors, casts en kleine domeinhulpen
- ongecontroleerde inline queries in views of render-methodes
- `float` voor geldbedragen
- hardcoded IDs
- code die gebaseerd is op tutorial-stijl hacks in plaats van Laravel-conventies

### Verplicht
Gebruik wel:
- `Route::livewire()` voor full-page Livewire pagina's waar passend
- Livewire 4 single-file components als standaard, tenzij een multi-file component aantoonbaar beter is
- constructor dependency injection
- Actions voor concrete business operaties
- Services voor integratie met externe systemen of gebundelde technische logica
- Enums voor statusvelden
- Policies voor autorisatie
- middleware voor admin-afscherming
- eager loading tegen N+1-problemen
- typed properties, type hints en return types

---

## Livewire 4 regels

### Componentstrategie
Gebruik standaard **single-file Livewire componenten** in Livewire 4-stijl.

Voorkeur:
- pagina-componenten als single-file component
- herbruikbare UI-componenten alleen wanneer dat echt meerwaarde biedt
- componenten mager houden
- complexe logica extraheren naar Actions, Services, Form Objects of computed properties

### Componentregels
Elke Livewire component:
- heeft één duidelijke verantwoordelijkheid
- doet geen zware business logic zelf
- doet geen grote samengestelde querylogica in de view
- valideert correct
- gebruikt `#[Computed]` voor afgeleide waarden
- gebruikt `#[Validate]` of een Form Object voor validatie
- gebruikt `WithPagination` waar nodig
- gebruikt `WithFileUploads` waar nodig
- gebruikt lifecycle hooks alleen wanneer functioneel nodig

### Routingregels
Gebruik duidelijke, expliciete routes.

Voorbeelden van toegestane aanpak:
- `Route::get()` voor klassieke controller-acties indien logisch
- `Route::livewire()` voor Livewire full-page schermen
- route model binding op `slug` waar relevant

Geen onduidelijke mengvormen.

### State-regels
- UI-state hoort in Livewire
- domeinstate hoort in database, services of actions
- cart-state mag in sessie voor gasten en in database voor ingelogde gebruikers
- Livewire componenten mogen niet fungeren als dumpplaats voor alle logica

---

## Laravel 13 architectuurregels

### Separation of concerns
Houd de lagen strikt gescheiden.

#### Routes
Routes doen alleen:
- endpointdefinitie
- middleware koppelen
- eventueel controller of Livewire component aanroepen

Routes doen niet:
- queries
- validatie
- business logic

#### Controllers
Controllers zijn dun.
Controllers doen alleen:
- request ontvangen
- autoriseren
- valideren via Form Request als klassiek request
- Action of Service aanroepen
- response teruggeven

#### Livewire componenten
Livewire is de interactieve controllerlaag van de frontend.
Livewire componenten mogen:
- user input verwerken
- validatie doen
- UI-state beheren
- actions/services aanroepen

Livewire componenten mogen niet:
- Stripe logica bevatten
- orderafhandeling volledig zelf doen
- complexe persistence-regels uitwerken
- grote queryketens verspreid door de class hebben

#### Actions
Gebruik Actions voor concrete use cases.

Voorbeelden:
- `AddItemToCartAction`
- `RemoveCartItemAction`
- `UpdateCartItemQuantityAction`
- `CreateOrderAction`
- `MarkOrderAsPaidAction`
- `CreateCheckoutSessionAction`
- `CreateProductAction`
- `UpdateProductAction`
- `SoftDeleteProductAction`

Regels:
- één action = één duidelijke business operatie
- één publieke methode, bij voorkeur `handle()`
- geen view rendering in actions
- actions orchestreren domeinlogica

#### Services
Gebruik Services voor:
- Stripe-integratie
- social login account-linking logica indien te groot voor action
- image storage/logica indien nodig
- QR-login infrastructuur indien die bonus gebouwd wordt

Voorbeelden:
- `StripeService`
- `SocialAccountService`
- `ProductImageService`
- `QrLoginService`

#### Models
Models bevatten alleen:
- relaties
- scopes
- casts
- accessors / mutators
- kleine domeinhulpen

Models bevatten niet:
- checkout flow
- Stripe-calls
- mailverzending
- complete orderworkflow

---

## Domeinstructuur die gevolgd moet worden

Gebruik deze domeinen als basis:

- Catalog
- Cart
- Checkout
- Orders
- Auth
- Admin

Voorkeursstructuur:

```text
app/
├── Actions/
│   ├── Cart/
│   ├── Checkout/
│   ├── Orders/
│   ├── Products/
│   └── Categories/
├── Enums/
├── Http/
│   ├── Controllers/
│   ├── Middleware/
│   └── Requests/
├── Livewire/
│   ├── Pages/
│   │   ├── Shop/
│   │   ├── Cart/
│   │   ├── Checkout/
│   │   ├── Account/
│   │   └── Admin/
│   └── Forms/
├── Models/
├── Policies/
├── Services/
├── Events/
├── Listeners/
└── Support/
```

Als single-file components gebruikt worden, zorg dan voor een consistente naamgeving en locatie die overeenkomt met de route-naamgeving.

---

## Frontend-analyse van KICKZ en omzetting naar Laravel + Livewire

De frontend zip toont minstens deze schermen:
- home / productlisting
- productdetail
- checkout / mandje-overzicht
- login

Daarnaast toont de frontend impliciet deze concepten:
- merkgerichte premium sneaker/webshop uitstraling
- productbadges zoals sale, new, limited
- voorraadindicatie
- social proof
- snapshot-prijs in checkout
- login met klassieke login, social login en QR-login teaser

### Wat functioneel vertaald moet worden

#### 1. Home / productlisting
De `index.html` moet vertaald worden naar een Livewire shop-pagina met:
- productgrid
- pagination
- zoekfunctie
- categoriefilter
- badgeweergave
- prijsweergave
- klik naar detailpagina via slug
- eventueel featured of promotionele blokken beheerd vanuit CMS of voorlopig statisch opgebouwd

#### 2. Productdetailpagina
De `detail.html` moet vertaald worden naar een productdetailpagina met:
- naam
- slug-route
- beschrijving
- prijs
- hoofdafbeelding
- voorraadstatus
- badge-indicatie
- knop om toe te voegen aan mandje

#### 3. Checkout / mandje
De `checkout.html` moet opgesplitst worden in echte logische flows:
- mandjepagina
- checkoutpagina met adresgegevens
- orderoverzicht
- redirect naar Stripe Checkout
- successpagina

De frontend mockup toont alles compact in één scherm, maar de backend moet technisch correct worden opgebouwd.

#### 4. Login
De `login.html` moet aansluiten op de reeds aanwezige starter kit auth, maar uitgebreid worden met:
- social login
- optioneel QR-login
- admintoegang via rolgebaseerde afscherming, niet via aparte hacky login

---

## CMS-vertaling van de KICKZ frontend

Het CMS moet de volgende entiteiten minimaal ondersteunen.

### Category
Velden minimaal:
- id
- name
- slug
- description optioneel
- is_active optioneel
- timestamps
- soft deletes

### Product
Velden minimaal:
- id
- category_id
- name
- slug
- description
- price
- stock
- image
- badge of statusveld indien logisch
- is_active
- is_featured optioneel
- timestamps
- soft deletes

### Order
Velden minimaal:
- id
- user_id nullable
- order_number
- total_amount
- status
- stripe_session_id
- stripe_payment_intent_id
- billing en shipping gerelateerde velden
- timestamps
- soft deletes

### OrderItem of OrderDetail
Velden minimaal:
- id
- order_id
- product_id
- product_name_snapshot
- price_snapshot
- quantity
- subtotal
- timestamps

### User
Uitbreiden met:
- role
- eventueel social provider info via aparte tabel indien properder

### Eventuele extra tabellen
- carts
- cart_items
- social_accounts
- qr_login_tokens

Gebruik extra tabellen alleen wanneer ze de architectuur verbeteren.

---

## Database-regels

### Geldbedragen
Geldbedragen altijd als:
- `decimal(10,2)` of vergelijkbaar
- nooit `float`

### Slugs
- producten en categorieën hebben unieke slugs
- slug wordt gebruikt in URLs

### Foreign keys
- altijd expliciet met correcte `constrained()` en delete-regels
- denk na over `cascade`, `restrict`, `nullOnDelete`

### Indexes
Voorzie indexes op minstens:
- slugs
- order_number
- status
- foreign keys
- kolommen die vaak gefilterd worden

### Soft deletes
Gebruik soft deletes op:
- products
- categories
- orders

### Seeders
Na `php artisan migrate:fresh --seed` moet het project direct demo-klaar zijn.

---

## Enums-regels

Gebruik PHP backed enums voor minstens:
- `OrderStatus`
- `UserRole`

Eventueel ook:
- product badge type
- voorraadstatus als dat architecturaal zinvol is

Plaats enums in `app/Enums`.

Gebruik casts in models.

---

## Policies en middleware

### Policies
Voorzie minstens:
- `OrderPolicy`
- `ProductPolicy`
- `CategoryPolicy`

Regels:
- gebruiker ziet enkel eigen orders
- alleen admin kan producten, categorieën en orderbeheer in admin aanpassen

### Middleware
Voorzie een eigen admin middleware, bijvoorbeeld:
- `EnsureUserIsAdmin`

Regels:
- adminroutes altijd afgeschermd
- middleware registreren volgens de huidige Laravel 13 aanpak
- liever alias gebruiken dan lange class names in routes

---

## Stripe-regels

### Functioneel
Stripe moet werken via test mode.

Flow:
1. gebruiker vult checkoutgegevens in
2. order wordt aangemaakt met status `pending`
3. Stripe Checkout Session wordt aangemaakt
4. gebruiker wordt doorgestuurd naar Stripe
5. success flow verifieert betaling via Stripe API
6. pas daarna wordt order `paid`

### Verboden
- een order op `paid` zetten louter op basis van query params
- Stripe calls rechtstreeks in een Blade view of Livewire render
- secrets hardcoden

### Verplicht
- Stripe integratie in service
- verwerkingsstappen in action(s)
- duidelijke foutafhandeling

---

## Authenticatie-regels

Gebruik de bestaande starter kit als basis.

### Verplicht
- standaard login en registratie behouden
- wachtwoord reset behouden
- social login met minstens 2 providers voorbereiden volgens exameneis

### Account-linking
Wanneer een social login binnenkomt met een bestaand e-mailadres, moet de linking expliciet en doordacht gebeuren.

Maak hiervoor geen snelle workaround. Documenteer de gekozen aanpak.

---

## QR-login regels

QR-login is bonus.
Alleen implementeren als de basis stabiel is.

Indien gebouwd:
- token-based
- expiry voorzien
- polling of eventmatige bevestiging correct opzetten
- localhost beperkingen correct meenemen

Geen halve proof-of-concept in productiecode laten staan.

---

## Testing-regels

Gebruik Pest.

### Minimaal te voorzien
- feature tests voor cart
- feature tests voor checkout en orderaanmaak
- policy tests
- admin authorization tests
- product CRUD tests
- unit test voor minstens één action
- unit test voor minstens één model scope of accessor

### Testregels
- gebruik factories
- gebruik `RefreshDatabase`
- geen hardcoded IDs
- test namen moeten beschrijvend zijn

---

## Frontend-integratie regels

### Belangrijke regel
De KICKZ frontend is een **design reference**, geen 1-op-1 runtime die rechtstreeks gekopieerd moet worden als losse HTML-bestanden.

### Wat wel moet
- de visuele stijl vertalen naar Blade + Livewire
- herbruikbare layout bouwen
- Tailwind-klassen integreren in Laravel views/components
- assets correct via Vite of lokale assets beheren

### Wat niet mag
- volledige HTML-bestanden los naast Laravel laten bestaan als eindoplossing
- CDN Tailwind in finale app behouden als dat botst met de bestaande buildketen
- mock data in `<script>` arrays laten zitten als vervanging van echte database-data
- frontend gedrag in losse demo-hacks laten bestaan wanneer het via Livewire of nette JS-integratie hoort te lopen

### Concreet voor de zip
Alles uit de frontend zip moet worden vertaald naar:
- layout(s)
- pagina's
- componenten
- echte data uit database
- nette assetstructuur

---

## Regels voor AI-assistent in VS Code

Wanneer je code voorstelt of schrijft voor dit project, volg dan altijd deze instructies:

1. Schrijf uitsluitend code die compatibel is met Laravel 13 en Livewire 4.
2. Baseer je op de huidige officiële documentatie, niet op verouderde voorbeelden.
3. Respecteer de reeds bestaande starter kit-structuur van dit project.
4. Gebruik Livewire 4 single-file components als standaardkeuze.
5. Gebruik `Route::livewire()` wanneer een full-page Livewire pagina logisch is.
6. Gebruik Laravel-conventies boven eigen creatieve structuren.
7. Houd controllers en Livewire componenten dun.
8. Verplaats business logic naar Actions en Services.
9. Gebruik Enums, Policies, middleware, scopes, casts en eager loading waar passend.
10. Gebruik nooit mock data als eindoplossing wanneer echte database-structuur nodig is.
11. Gebruik nooit `float` voor prijzen of totalen.
12. Genereer geen code die securityregels omzeilt, vooral niet voor auth, admin of Stripe.
13. Werk stapsgewijs en domeingedreven.
14. Toon bij grotere wijzigingen altijd eerst welke bestanden aangemaakt of aangepast moeten worden.
15. Geef de voorkeur aan kleine, gerichte wijzigingen boven grote ongerichte dumps code.
16. Als een oplossing meerdere opties heeft, kies de optie die het best aansluit op Laravel 13 + Livewire 4 + senior architecture.
17. Als oudere Livewire/Volt syntax mogelijk lijkt, kies altijd de Livewire 4 aanpak.
18. Respecteer dat dit project een CMS/webshop is en geen losse demo van Livewire features.
19. Schrijf alle code production-minded, leesbaar en testbaar.
20. Voeg alleen comments toe waar logica niet vanzelfsprekend is.

---

## Regels voor bestandsaanmaak

### Maak waarschijnlijk deze bestanden aan

#### Models
- `app/Models/Category.php`
- `app/Models/Product.php`
- `app/Models/Order.php`
- `app/Models/OrderItem.php`
- eventueel `app/Models/Cart.php`
- eventueel `app/Models/CartItem.php`
- eventueel `app/Models/SocialAccount.php`
- eventueel `app/Models/QrLoginToken.php`

#### Enums
- `app/Enums/OrderStatus.php`
- `app/Enums/UserRole.php`

#### Actions
- `app/Actions/Cart/...`
- `app/Actions/Checkout/...`
- `app/Actions/Products/...`
- `app/Actions/Orders/...`

#### Services
- `app/Services/StripeService.php`
- eventueel `app/Services/SocialAccountService.php`
- eventueel `app/Services/QrLoginService.php`

#### Policies
- `app/Policies/OrderPolicy.php`
- `app/Policies/ProductPolicy.php`
- `app/Policies/CategoryPolicy.php`

#### Middleware
- `app/Http/Middleware/EnsureUserIsAdmin.php`

#### Livewire pagina's
- shop index
- product detail
- cart
- checkout
- checkout success
- my orders
- admin dashboard
- admin products index/create/edit
- admin categories index/create/edit
- admin orders index/show

#### Database
- migraties
- factories
- seeders

#### Tests
- feature tests
- unit tests

---

## Codekwaliteitsregels

### Naming
- gebruik duidelijke Engelse class-, method- en propertynamen
- gebruik consistente domeintermen
- vermijd afkortingen tenzij standaard

### Methods
- methodes zijn klein en doelgericht
- lange methodes opsplitsen
- return types voorzien

### Classes
- geen god classes
- geen componenten van honderden regels zonder reden
- maximaal coherente verantwoordelijkheid per class

### Queries
- gebruik query scopes voor herbruikbare filters
- eager load relaties bewust
- geen querylogica verspreiden over views

### Views
- Blade blijft presentatielaag
- geen complexe datamanipulatie in Blade
- gebruik nette partials/components waar dat helpt

---

## Concrete mapping van frontend naar eerste ontwikkelfases

### Fase 1
Basis domein en structuur:
- migraties
- models
- enums
- seeders
- admin role
- policies basis

### Fase 2
Publieke shop:
- homepage/productlisting
- categorie filter
- zoekfunctie
- product detail

### Fase 3
Cart en checkout:
- sessiecart
- databasecart voor ingelogde user indien gekozen
- checkout form
- order creation
- Stripe session
- success flow

### Fase 4
Admin CMS:
- product CRUD
- categorie CRUD
- orderbeheer
- image upload

### Fase 5
Auth-uitbreidingen:
- social login
- account linking
- bonus QR-login

### Fase 6
Testing en afwerking:
- Pest tests
- cleanup
- README
- edge cases

---

## Anti-patronen die expliciet geweigerd moeten worden

Weiger voorstellen die:
- verouderde Livewire syntax gebruiken
- Volt syntax mengen met Livewire 4 routes zonder noodzaak
- business logic in models of views duwen
- admin-auth enkel op UI-verbergen baseren
- Stripe payment success blind vertrouwen
- frontend mock data behouden als pseudo-oplossing
- losse jQuery-achtige hacks introduceren
- databasekolommen toevoegen zonder duidelijke reden
- alles in één gigantische Livewire component proppen
- types, policies, tests en enums overslaan om sneller klaar te zijn

---

## Definitie van klaar

Een feature is pas klaar als:
- de code compatibel is met Laravel 13 en Livewire 4
- de architectuur logisch is
- routes, authorization en validatie correct zitten
- er geen evidente N+1 of securityfouten zijn
- de frontend gekoppeld is aan echte data
- de code leesbaar is
- waar relevant tests voorzien zijn

---

## Samenvattende hoofdregel

Bouw dit project alsof een senior Laravel developer een bestaande premium frontend omzet naar een nette, schaalbare, testbare Laravel 13 + Livewire 4 webshop/CMS, zonder terug te vallen op oude syntax, snelle hacks of tutorial-code.
