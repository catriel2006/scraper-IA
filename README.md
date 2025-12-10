# Scraper IA – API de recherche de profils

Application de démonstration qui prend une requête (ex : "elon musk tesla"), génère une liste de liens pertinents en rapport avec la personne (LinkedIn, Google, site officiel…), tente de scraper les pages accessibles et renvoie soit un résumé JSON structuré, soit un message propre en cas de blocage (anti‑bot).

## Technologies utilisées

- Backend : Laravel (API REST JSON)
- Frontend : HTML/CSS + JavaScript (fetch API)
- HTTP client : Guzzle / Laravel HTTP client
- IA : Prévu pour intégration avec une API d’IA (ex. Gemini )
- Scraping avancé (optionnel) : Prévu pour SERPAPI / Apify / autres providers

## Installation

1. Cloner le dépôt
2. Installer les dépendances PHP
   - `composer install`
3. Copier le fichier d’environnement
   - `cp .env.example .env`
4. Générer la clé d’application
   - `php artisan key:generate`
5. Lancer le serveur de développement
   - `php artisan serve` (par défaut sur http://127.0.0.1:8000)

Le frontend se connecte à l’endpoint :
- `POST /api/scrape-person`

## Utilisation

### 1. Formulaire web

- Ouvrir la page `index.html`
- Saisir une requête (ex. "elon musk tesla")
- Soumettre le formulaire

### 2. Endpoint API

`POST /api/scrape-person`

Body JSON (exemple) :

{
"query": "elon musk tesla"
}


Réponse JSON (cas général) :
- `query` : requête envoyée
- `texts_found` : nombre de sources exploitables trouvées
- `links_tried` : liste des liens testés (LinkedIn, Google, site officiel…)
- `results` : objet contenant soit un résumé, soit un message de statut

## Gestion des anti‑bots

Sur des sites fortement protégés (LinkedIn, Google, etc.), l’application :
- Enregistre et renvoie la liste des `links_tried`
- N’essaie pas de contourner illégalement les protections
- Retourne un message clair, par exemple :
  - `status` : "NO_DATA"
  - `message` : "Anti-bot détectés. Prêt pour SERP API/Apify avec clés fournies."

L’architecture est prête pour connecter des services de scraping professionnels (SERPAPI, Apify, etc.) afin d’obtenir les données de façon plus robuste.


