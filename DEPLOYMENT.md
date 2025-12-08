# Déploiement Soundscape sur Clever Cloud

Ce document décrit la configuration nécessaire pour déployer l'application Soundscape sur Clever Cloud.

## Prérequis

- Application PHP sur Clever Cloud (déjà créée - voir `.clever.json`)
- Add-on PostgreSQL à configurer
- Add-on FS Bucket à configurer pour la persistance du storage

## 1. Configuration des Add-ons

### PostgreSQL

1. Dans le dashboard Clever Cloud, aller dans votre application
2. Onglet **Service dependencies** → **Link an add-on**
3. Créer un add-on **PostgreSQL**
4. Le lier à votre application

Les variables suivantes seront automatiquement injectées :
- `POSTGRESQL_ADDON_HOST`
- `POSTGRESQL_ADDON_PORT`
- `POSTGRESQL_ADDON_DB`
- `POSTGRESQL_ADDON_USER`
- `POSTGRESQL_ADDON_PASSWORD`

✅ **La configuration database est déjà prête** dans `config/database.php` - aucune action supplémentaire requise.

### FS Bucket (Stockage persistant)

**Crucial pour la persistance des fichiers uploadés** (images de projets via Spatie Media Library).

1. Dans le dashboard, créer un add-on **FS Bucket**
2. Le lier à votre application
3. Récupérer l'**host du bucket** depuis la configuration de l'add-on
   - Format : `bucket-[id]-fsbucket.services.clever-cloud.com`

4. Ajouter la variable d'environnement `CC_FS_BUCKET` :
   ```bash
   CC_FS_BUCKET=/storage/app:bucket-VOTRE-ID-fsbucket.services.clever-cloud.com
   ```

   Cette configuration monte le FS Bucket sur `storage/app`, assurant que :
   - Les fichiers uploadés persistent entre déploiements
   - Les médias (images de projets) sont conservés
   - Le dossier `storage/app/public` est accessible

## 2. Variables d'environnement

Configurer les variables suivantes dans **Environment variables** du dashboard :

### Application

```bash
APP_NAME="Soundscape Audio"
APP_ENV=production
APP_KEY=<générer avec: php artisan key:generate --show>
APP_DEBUG=false
APP_URL=https://votre-app.cleverapps.io
```

### Localisation

```bash
APP_LOCALE=fr
APP_FALLBACK_LOCALE=fr
```

### Logging (Important pour Clever Cloud)

```bash
LOG_CHANNEL=syslog
LOG_LEVEL=info
```

Avec `syslog`, tous les logs Laravel apparaissent dans les logs Clever Cloud.

### Session, Cache & Queue

```bash
SESSION_DRIVER=database
SESSION_LIFETIME=120
CACHE_STORE=database
QUEUE_CONNECTION=database
```

### Filesystem & Media

```bash
FILESYSTEM_DISK=public
MEDIA_DISK=public
```

### Configuration Clever Cloud

```bash
# Webroot Laravel
CC_WEBROOT=/public

# Hook de post-déploiement
CC_POST_BUILD_HOOK=bash clevercloud/post_build.sh

# Reverse proxy (pour headers et IPs corrects)
CC_REVERSE_PROXY_IPS=*
```

### Base de données

**Aucune variable à configurer** - l'add-on PostgreSQL injecte automatiquement :
- `POSTGRESQL_ADDON_HOST`
- `POSTGRESQL_ADDON_PORT`
- `POSTGRESQL_ADDON_DB`
- `POSTGRESQL_ADDON_USER`
- `POSTGRESQL_ADDON_PASSWORD`

La configuration dans `config/database.php` utilise déjà ces variables en fallback.

## 3. Structure des fichiers de déploiement

```
.clever.json              # Configuration de l'app Clever Cloud
clevercloud/
└── post_build.sh        # Hook exécuté après chaque déploiement
```

### Contenu du post_build.sh

Le script est exécuté automatiquement après chaque build et effectue :

1. ✅ **Migrations** - `php artisan migrate --force`
2. ✅ **Storage link** - `php artisan storage:link --force` (crucial pour les médias)
3. ✅ **Optimisations** - Cache config/routes/vues pour meilleures performances

## 4. Processus de déploiement

### Déploiement initial

1. **Lier les add-ons** PostgreSQL et FS Bucket
2. **Configurer toutes les variables d'environnement** listées ci-dessus
3. **Générer APP_KEY** :
   ```bash
   php artisan key:generate --show
   ```
   Copier la clé et la définir dans `APP_KEY`

4. **Premier déploiement** :
   ```bash
   git push clever main
   ```

### Déploiements suivants

Chaque push sur la branche configurée déclenche automatiquement :
1. Build de l'application
2. Exécution de `clevercloud/post_build.sh`
3. Redémarrage de l'application

## 5. Vérifications post-déploiement

### Vérifier les logs

```bash
clever logs -f
```

Vous devriez voir :
```
🚀 Running post-build hooks for Soundscape...
📊 Running migrations...
🔗 Creating storage link...
⚡ Optimizing application...
✅ Post-build hooks completed successfully!
```

### Vérifier le storage

Le lien symbolique `public/storage → storage/app/public` doit exister :

```bash
clever ssh
ls -la public/storage
```

### Tester l'application

1. **Page d'accueil** : https://votre-app.cleverapps.io
2. **Admin** : Créer/modifier un projet et uploader une image
3. **Vérifier** que l'image s'affiche correctement sur le portfolio

## 6. Architecture du stockage

```
FS Bucket (persistant)
    ↓ monté sur
storage/app/
    ├── private/          # Fichiers privés
    └── public/           # Fichiers publics (médias)
        └── [project-id]/ # Images de projets (Spatie Media Library)
            ├── image.jpg
            └── conversions/

public/storage → storage/app/public  (symlink créé par post_build.sh)
```

**URLs des médias** : `https://votre-app.cleverapps.io/storage/[project-id]/image.jpg`

## 7. Spécificités Spatie Media Library

- **Disk configuré** : `public` (via `MEDIA_DISK=public`)
- **Path réel** : `storage/app/public` (monté sur FS Bucket)
- **Conversions d'images** : Gérées en queue (`QUEUE_CONNECTION=database`)
- **Taille max** : 10 MB par fichier (configuré dans `config/media-library.php`)

## 8. Troubleshooting

### Les fichiers uploadés disparaissent après redéploiement

**Cause** : FS Bucket non configuré ou mal monté

**Solution** :
1. Vérifier que l'add-on FS Bucket est lié
2. Vérifier `CC_FS_BUCKET=/storage/app:<bucket-host>`
3. Redéployer l'application

### Erreur "storage/app/public does not exist"

**Cause** : Le lien symbolique n'a pas été créé

**Solution** :
1. Vérifier les logs de build pour voir si `post_build.sh` s'exécute
2. Vérifier `CC_POST_BUILD_HOOK=bash clevercloud/post_build.sh`
3. Exécuter manuellement via SSH :
   ```bash
   clever ssh
   php artisan storage:link --force
   ```

### Les images ne s'affichent pas (404)

**Causes possibles** :
1. `APP_URL` incorrect (avec trailing slash)
2. Lien symbolique manquant
3. Fichiers non uploadés sur le FS Bucket

**Solutions** :
1. Vérifier `APP_URL` (sans `/` à la fin)
2. Vérifier le symlink : `ls -la public/storage`
3. Vérifier le contenu du bucket : `ls -la storage/app/public`

### Erreur de connexion PostgreSQL

**Cause** : Add-on PostgreSQL non lié

**Solution** :
1. Dashboard → Service dependencies
2. Vérifier que PostgreSQL est lié
3. Les variables `POSTGRESQL_ADDON_*` doivent être présentes dans `clever env`

### Migrations ne s'exécutent pas

**Cause** : Hook post_build non configuré ou erreur dans le script

**Solution** :
1. Vérifier `CC_POST_BUILD_HOOK=bash clevercloud/post_build.sh`
2. Vérifier les logs de build
3. Exécuter manuellement :
   ```bash
   clever ssh
   php artisan migrate --force
   ```

### Erreur 500 après déploiement

**Causes possibles** :
1. `APP_KEY` manquant ou invalide
2. Erreur dans les logs (vérifier avec `clever logs`)
3. Cache de config corrompu

**Solutions** :
1. Générer et définir `APP_KEY`
2. Vérifier les logs : `clever logs -f`
3. SSH et clear cache :
   ```bash
   clever ssh
   php artisan config:clear
   php artisan cache:clear
   ```

## 9. Commandes utiles

```bash
# Logs en temps réel
clever logs -f

# SSH dans l'application
clever ssh

# Lister les variables d'environnement
clever env

# Définir une variable
clever env set APP_DEBUG false

# Redéployer manuellement
clever deploy

# Redémarrer l'application
clever restart

# Voir les add-ons liés
clever addons
```

## 10. Checklist de déploiement

- [ ] Add-on PostgreSQL créé et lié
- [ ] Add-on FS Bucket créé et lié
- [ ] Variable `CC_FS_BUCKET` configurée avec l'host du bucket
- [ ] Variable `APP_KEY` générée et définie
- [ ] Variable `APP_URL` correcte (HTTPS, sans trailing slash)
- [ ] Variable `LOG_CHANNEL=syslog` définie
- [ ] Variable `CC_WEBROOT=/public` définie
- [ ] Variable `CC_POST_BUILD_HOOK=bash clevercloud/post_build.sh` définie
- [ ] Variable `CC_REVERSE_PROXY_IPS=*` définie
- [ ] Premier déploiement effectué
- [ ] Logs vérifiés (migrations OK, storage link OK)
- [ ] Test upload d'image dans l'admin
- [ ] Vérification affichage image sur le portfolio

## 11. Sécurité

- ✅ `APP_DEBUG=false` en production
- ✅ `APP_KEY` unique et secret
- ✅ HTTPS activé automatiquement par Clever Cloud
- ✅ Variables sensibles dans env vars, pas dans le code
- ✅ Reverse proxy configuré pour IPs correctes (`bootstrap/app.php`)
- ✅ Trusted proxies actif en production uniquement

## 12. Performance

Le `post_build.sh` active automatiquement les optimisations Laravel :
- ✅ `config:cache` - Cache de configuration
- ✅ `route:cache` - Cache des routes
- ✅ `view:cache` - Cache des vues Blade

Pour de meilleures performances, considérer :
- [ ] Ajouter un add-on Redis pour cache/sessions
- [ ] Configurer un CDN pour les assets statiques
- [ ] Activer la compression d'images (déjà configurée dans `config/media-library.php`)
