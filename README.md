# Site de Généalogie

## Partie 3 : Sécurisation des Modifications via Approbation Communautaire

### *1. Structure de la Base de Données*

Le schéma de la base de données a été étendu pour inclure les fonctionnalités de sécurité et d'approbation communautaire. Voici les principales tables ajoutées :

- *invitations*
- *modification_proposals*
- *proposal_votes*
- *modification_history*

Vous pouvez visualiser le schéma complet [ici](https://dbdiagram.io/d/67730dbf5406798ef7f417c6).

### *2. Évolution des Données*

#### *2.1. Propositions de Modifications*

1. *Création d'une Proposition :*
   - *Action :* Un utilisateur propose une modification (mise à jour des informations ou ajout d'une relation).
   - *Insertion :* Création d'une entrée dans modification_proposals avec status = pending.
   - *Historique :* Ajout d'une entrée dans modification_history avec action = created.

#### *2.2. Votes sur les Propositions*

1. *Vote des Membres :*
   - *Action :* Les membres de la communauté votent pour approuver ou rejeter la proposition.
   - *Insertion :* Chaque vote est enregistré dans proposal_votes.
   - *Mise à Jour du Statut :*
     - Si >= 3 approve : status = approved.
     - Si >= 3 reject : status = rejected.
   - *Historique :* Ajout d'entrées dans modification_history pour chaque changement de statut.

#### *2.3. Exécution des Modifications*

1. *Action après Approbation :*
   - *Action :* Application des modifications proposées à la table people ou relationships.
   - *Mise à Jour :* status = executed.
   - *Historique :* Ajout d'une entrée dans modification_history avec action = executed.

2. *Action après Rejet :*
   - *Action :* Proposition invalidée, aucune modification appliquée.
   - *Mise à Jour :* status = invalidated.
   - *Historique :* Ajout d'une entrée dans modification_history avec action = invalidated.

### *3. Fonctionnement Global*

1. *Ajout de Membres de la Famille :*
   - Les utilisateurs peuvent ajouter de nouveaux membres en créant des fiches personnes et en définissant des relations familiales.

2. *Invitations :*
   - Les utilisateurs peuvent inviter des membres de leur famille à rejoindre le site. Une fois inscrits, les utilisateurs invités acquièrent la fiche personne qui leur est associée.

3. *Inscription sans Invitation :*
   - Les nouveaux utilisateurs peuvent s'inscrire et créer directement leur propre fiche personne sans invitation préalable.

4. *Propositions de Modifications :*
   - Les utilisateurs peuvent proposer des modifications aux fiches personnes ou ajouter de nouvelles relations. Ces propositions nécessitent l'approbation de la communauté.

5. *Validation des Modifications :*
   - Les propositions sont examinées et votées par les membres de la communauté. Une proposition est approuvée après 3 votes positifs et rejetée après 3 votes négatifs.



### *5. Instructions pour l'Installation des Nouvelles Fonctionnalités*

1. *Migrer la Base de Données :*
   
   ```bash
   php artisan migrate
