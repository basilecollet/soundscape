# 📋 Plan de Développement - Soundscape Audio (Adapté)

## 🎯 Vue d'ensemble du projet

**Soundscape** - Plateforme pour ingénieur son (développement solo)
- **Portfolio/Vitrine** : SEO-optimisé avec Controllers Laravel
- **E-commerce** : Vente de sons avec guest checkout (Livewire)
- **Administration** : Gestion complète (Livewire)

## ✅ État actuel du projet

### Déjà implémenté
```markdown
✅ Infrastructure Docker optimisée (PHP 8.3, PostgreSQL, Node 20)
✅ Laravel 12 + Livewire + Tailwind CSS configurés
✅ TDD avec Pest PHP + SQLite in-memory
✅ CI/CD GitHub Actions (quality + tests)
✅ Laravel Pint + PHPStan niveau 8
✅ Makefile avec commandes simplifiées
✅ Authentication Laravel standard
✅ Modèle PageContent pour contenu dynamique
✅ Composants Livewire migrés (ContactSection, HomeSection, AboutSection, Navbar, Footer)
✅ Pages Settings (profile, password, appearance)
✅ Structure de base (home, dashboard, auth)
```

## ⚖️ Mise en conformité RGPD (transversale)

### Obligations légales
```markdown
✅ Site e-commerce européen → Conformité RGPD obligatoire
✅ Guest checkout → Traitement de données personnelles
✅ Cookies analytics → Consentement requis
✅ Newsletters → Double opt-in
```

### Données traitées
```markdown
- Portfolio : Analytics (IP, navigation)
- E-commerce : Email, nom, IP, historique commandes
- Admin : Données utilisateur complètes
- Marketing : Email newsletters (optionnel)
```

## 🏗️ Phase 0 : Refactoring Architecture + RGPD Foundation (4-5 jours)

### Objectifs
Restructurer l'existant en architecture DDD/SOLID et poser les bases RGPD

### Tâches
```markdown
- [ ] Créer la structure DDD
  - [ ] app/Domain/Portfolio/
  - [ ] app/Domain/Shop/
  - [ ] app/Domain/Admin/
  - [ ] app/Application/
  - [ ] app/Infrastructure/
  
- [ ] Refactorer l'existant
  - [ ] Déplacer PageContent → Domain/Portfolio/Models/
  - [ ] Créer ContentRepository interface
  - [ ] Extraire la logique des composants vers Services
  - [ ] Créer des DTOs pour les data transfers
  
- [ ] Adapter les tests existants
  - [ ] Mettre à jour les namespaces
  - [ ] Ajouter tests pour les nouveaux services

- [ ] RGPD Foundation
  - [ ] Créer modèle ConsentLog (stockage consentements)
  - [ ] Middleware CookieConsent
  - [ ] Service GdprService pour gestion données
  - [ ] Commande Artisan pour export/suppression données
```

## 📱 Phase 1 : Portfolio/Vitrine SEO (1-2 semaines)

### 1.1 Conversion pages existantes en Controllers (2-3 jours)
```markdown
- [ ] Convertir la homepage
  - [ ] Créer PortfolioController@home
  - [ ] Migrer la logique de home/index.blade.php
  - [ ] Garder les composants existants (HomeSection, AboutSection)
  - [ ] Ajouter meta tags SEO
  
- [ ] Améliorer ContactSection
  - [ ] Créer ContactController@show pour SEO
  - [ ] Garder le composant Livewire pour le formulaire
  - [ ] Ajouter validation et envoi d'email
  - [ ] Ajouter consentement RGPD au formulaire
```

### 1.2 Nouvelles fonctionnalités Portfolio (3-4 jours)
```markdown
- [ ] Modèles et migrations
  - [ ] Étendre PageContent pour devenir Project
  - [ ] Ajouter Category model
  - [ ] Migration pour projects (slug, meta_description, featured)
  
- [ ] Controllers et vues
  - [ ] ProjectController@index (liste des projets)
  - [ ] ProjectController@show (détail avec slug)
  - [ ] Templates Blade avec structured data
  
- [ ] Services
  - [ ] SeoService pour meta tags dynamiques
  - [ ] MediaService (utiliser l'upload existant)

- [ ] RGPD Portfolio
  - [ ] Banner cookies avec Livewire component
  - [ ] Page Politique de confidentialité
  - [ ] Page Mentions légales
  - [ ] Analytics conditionnels (GA4/Plausible selon consentement)
```

### 1.3 Blog simple (2 jours)
```markdown
- [ ] Réutiliser PageContent pour les articles
  - [ ] Ajouter type='blog' au modèle existant
  - [ ] BlogController avec pagination
  - [ ] RSS feed pour SEO
```

## 🛒 Phase 2 : E-commerce avec Guest Checkout (2-3 semaines)

### 2.1 Infrastructure Shop (3-4 jours)
```markdown
- [ ] Modèles (étendre depuis l'existant)
  - [ ] Product (réutiliser structure PageContent)
  - [ ] Order (sans user_id, guest_email, ip_address pour RGPD)
  - [ ] License (types de licences)
  
- [ ] Services
  - [ ] CartService (session-based)
  - [ ] GuestOrderService (avec logging RGPD)
  - [ ] DownloadService avec tokens temporaires
  
- [ ] RGPD E-commerce
  - [ ] Consentement obligatoire au checkout
  - [ ] Politique de retour/remboursement
  - [ ] Conservation limitée des données (30 jours pour guests)
  - [ ] Droit à l'effacement automatique
```

### 2.2 Composants Livewire Shop (4-5 jours)
```markdown
- [ ] Adapter les composants existants
  - [ ] Transformer HomeSection en ProductCatalog
  - [ ] Créer ProductCard (style similaire aux cards existantes)
  - [ ] Cart component (sidebar comme dashboard existant)
  
- [ ] Guest Checkout
  - [ ] GuestCheckoutForm avec checkbox RGPD obligatoire
  - [ ] PaymentIntegration (Stripe/PayPal)
  - [ ] OrderConfirmation avec liens de téléchargement
  - [ ] Email de confirmation avec mentions RGPD
```

### 2.3 Système de téléchargement (2 jours)
```markdown
- [ ] DownloadController
  - [ ] Liens signés temporaires (24h max)
  - [ ] Streaming sécurisé des fichiers audio
  - [ ] Page de téléchargement pour les invités
  - [ ] Logging des téléchargements pour audit RGPD
```

## 🔧 Phase 3 : Administration (1-2 semaines)

### 3.1 Étendre l'auth existante (1 jour)
```markdown
- [ ] Ajouter is_admin au User model existant
- [ ] AdminMiddleware simple
- [ ] Adapter le dashboard existant pour l'admin
```

### 3.2 CRUD avec Livewire (4-5 jours)
```markdown
- [ ] Products Management
  - [ ] Réutiliser les patterns des Settings pages
  - [ ] ProductManager (index, create, edit)
  - [ ] Upload audio avec preview
  
- [ ] Orders Management  
  - [ ] OrdersTable component
  - [ ] OrderDetails modal
  - [ ] Export CSV simple
  
- [ ] Content Management
  - [ ] Étendre PageContent management
  - [ ] ProjectManager pour portfolio
  - [ ] Réutiliser les forms existants
```

### 3.3 Dashboard Analytics (2 jours)
```markdown
- [ ] Adapter le dashboard existant
  - [ ] Stats cards (ventes, produits, visiteurs)
  - [ ] Graphique simple avec Chart.js
  - [ ] Recent orders table

- [ ] RGPD Admin Tools
  - [ ] Interface de gestion des consentements
  - [ ] Export données client (sur demande)
  - [ ] Suppression données (droit à l'oubli)
  - [ ] Audit log des accès aux données
```

## 🧪 Phase 4 : Tests & Optimisation (3-4 jours)

### 4.1 Tests (2 jours)
```markdown
- [ ] Adapter les tests existants
  - [ ] Tests pour les nouveaux controllers
  - [ ] Tests pour CartService
  - [ ] Tests pour GuestCheckout flow
  
- [ ] Nouveaux tests critiques
  - [ ] Test complet achat guest
  - [ ] Test téléchargement sécurisé
  - [ ] Test admin CRUD
  - [ ] Tests RGPD (consentement, export, suppression)
```

### 4.2 Performance (1-2 jours)
```markdown
- [ ] Cache (utiliser Redis de Docker)
  - [ ] Cache les pages portfolio
  - [ ] Cache les requêtes products
  
- [ ] Optimisations
  - [ ] Lazy loading images existantes
  - [ ] Minification assets avec Vite existant
  - [ ] Compression des audio previews
```

## 🚀 Phase 5 : Déploiement Clever Cloud (2-3 jours)

### 5.1 Préparation (1 jour)
```markdown
- [ ] Adapter docker-compose pour production
- [ ] Configuration .env.production
- [ ] Migrer de PostgreSQL local vers Clever Cloud
- [ ] Configuration stockage fichiers (Clever FS)

- [ ] RGPD Production
  - [ ] SSL obligatoire (HTTPS)
  - [ ] Localisation serveurs UE (Clever Cloud France)
  - [ ] Backup chiffrés avec rétention limitée
  - [ ] Monitoring accès données sensibles
```

### 5.2 Mise en production (1-2 jours)
```markdown
- [ ] Setup Clever Cloud
  - [ ] Application PHP
  - [ ] Add-on PostgreSQL
  - [ ] Add-on Redis
  
- [ ] Adapter GitHub Actions existantes
  - [ ] Workflow de déploiement
  - [ ] Tests avant deploy
```

## 📊 Roadmap simplifiée (Solo Dev)

### Semaine 1-2 : Foundation
- Refactoring architecture DDD
- Portfolio avec SEO
- Blog simple

### Semaine 3-5 : E-commerce
- Catalogue produits
- Guest checkout
- Système de paiement

### Semaine 6-7 : Administration
- Dashboard admin
- CRUD produits/commandes
- Analytics basiques

### Semaine 8 : Finalisation
- Tests complets
- Optimisations
- Déploiement

## 🎯 Approche pragmatique Solo Dev

### Principes
```markdown
✅ Réutiliser au maximum l'existant
✅ Éviter l'over-engineering
✅ Itérer rapidement (MVP first)
✅ Tester les features critiques uniquement
✅ Documentation minimale mais claire
```

### Workflow Git simplifié
```bash
main
├── develop
└── feature/current-work  # Une seule feature branch active
```

### Outils de suivi
```markdown
- [ ] GitHub
- [ ] DevelopmentPlan.md dans le projet pour les tâches
```

## 📝 Quick Wins (à faire en premier)

1. **SEO Homepage** (1 jour)
   - Convertir en Controller
   - Ajouter meta tags
   - Générer sitemap

2. **Formulaire contact fonctionnel** (0.5 jour)
   - Validation existante
   - Envoi email

3. **Page projets simple** (1 jour)
   - Réutiliser PageContent
   - Liste + détail

4. **Produit MVP** (2 jours)
   - 5-10 produits tests
   - Checkout Stripe basique
   - Email avec lien

5. **Conformité RGPD basique** (1 jour)
   - Banner cookies
   - Politique de confidentialité
   - Mentions légales
   - Checkbox consentement

## ⏱️ Estimation réaliste

**Durée totale** : 6-8 semaines (à temps plein)
**Ou** : 3-4 mois (mi-temps, soirs/weekends)

**Priorités** :
1. Portfolio SEO → Visibilité
2. Shop basique → Revenus
3. Admin minimal → Gestion
4. Améliorations → Itératif

## 📈 Métriques de succès

### KPIs Techniques
- [ ] Page Speed Score > 90
- [ ] Test Coverage > 80%
- [ ] SEO Score > 85
- [ ] Mobile Responsive 100%
- [ ] Conformité RGPD 100%

### KPIs Business  
- [ ] Temps de mise en ligne < 2 mois
- [ ] Premier produit vendu < 1 semaine après launch
- [ ] 10 projets portfolio publiés
- [ ] 20 produits audio en catalogue

## 🔄 Évolutions futures (Post-MVP)

### Fonctionnalités
```markdown
- [ ] Système de newsletter avec double opt-in
- [ ] Multi-langue (FR/EN) avec consentement par langue
- [ ] API pour intégrations tierces (avec authentification)
- [ ] Abonnements/Subscriptions (données bancaires sécurisées)
- [ ] Forum communautaire (modération données)
- [ ] Tutoriels vidéo (cookies analytics avancés)
- [ ] Pack bundles avec réductions
- [ ] Programme d'affiliation
```

### RGPD Avancé
```markdown
- [ ] Consentement granulaire par finalité
- [ ] Portabilité des données (export JSON/XML)
- [ ] Anonymisation automatique après 3 ans
- [ ] Privacy by design pour nouvelles features
- [ ] Audit RGPD externe (recommandé après 6 mois)
- [ ] Désignation DPO si >10k utilisateurs/an
```

---

**Document créé le** : 2025-08-16  
**Dernière mise à jour** : 2025-08-23  
**Version** : 1.1.0

Ce plan **pragmatique** réutilise 70% de l'existant et se concentre sur l'essentiel pour un développeur solo.
