# Overview

- [Overview](#overview)
- [Commande Github](#commande-github)
- [Mise en place du projet](#mise-en-place-du-projet)
- [Hackathon](#hackathon)
  - [Présentation de l’entreprise cliente](#présentation-de-lentreprise-cliente)

> [!NOTE] A l'attention du groupe
> Les commandes suivantes sont données à titre informatif et peuvent ne pas convenir à toutes les situations
> 

# Commande Github
```powershell
# Création d'une nouvelle branche
[main] git branch nouvelle_branche

# Déplacement vers la nouvelle branche
[main] git checkout nouvelle_branche

# Récupération des dernières mise à jours du repos
[nouvelle_branche] git fetch origin

# Récupération du contenu de la branche main à jour
[nouvelle_branche] git merge origin/main

# Ajout des fichier 
[nouvelle_branche] git add .

# Ajouter un message de commit
[nouvelle_branche] git commit -m "message de commit"

# Envoyer la mise à jour
[nouvelle_branche] git push --set-upstream origin nouvelle_branche
```

Cliquer sur le lien affiché
```powershell
remote:     Create a pull request for 'update_readme' on GitHub by visiting:
remote:     https://github.com/LeoLChalot/UNIT-TEST-PHP-JS/pull/new/update_readme
```


# Mise en place du projet

Le fichier `.env.example` sert de base.
Il faudra penser à adapter la variable d'environnement `DATABASE_URL` avec le nom de la base de données créée ci-dessous

`git clone https://github.com/LeoLChalot/UNIT-TEST-PHP-JS.git`

`cd UNIT-TEST-PHP-JS` 

`composer update`

`npm i`

`php bin/console doctrine:database:create`

`php bin/console make:migration`

`php bin/console doctrine:migrations:migrate`

> [!WARNING]   Nous avons eu quelques soucis de comportement avec Composer
> En cas de problème, contactez-nous

# Hackathon
En tant qu'intermédiaire, mon rôle est de mettre en relation des développeurs freelances avec des
entreprises ayant des besoins spécifiques en solutions numériques. Aujourd’hui, j’ai constitué une équipe
de freelances aux compétences variées afin de répondre à une problématique soulevée par l’entreprise
Édifis Pro (EP), qui m’a mandaté pour trouver une équipe capable de lui proposer une solution adaptée.

## Présentation de l’entreprise cliente
L’entreprise EP est une société spécialisée dans le secteur du BTP. Elle emploie environ cinquante
ouvriers et chefs de chantier, ainsi qu’une dizaine de collaborateurs dédiés aux fonctions administratives.
Opérant à l’échelle régionale, l’entreprise se charge de la construction de bâtiments à usage
professionnel, tels que des hangars, des bureaux ou encore des magasins.
Il n’est pas rare que plusieurs chantiers soient menés simultanément. Grâce à son effectif conséquent,
l’entreprise est en mesure d’assurer une répartition des équipes sur les différents sites en fonction des
besoins. Actuellement, cette planification est gérée par le service administratif. Cependant, des erreurs
d’affectation surviennent régulièrement, entraînant des situations problématiques :
Certains employés se retrouvent assignés à plusieurs chantiers en même temps.

- D’autres sont affectés à des chantiers qui ne correspondent pas à leurs compétences ou à la planification initialement prévue.
- planification initialement prévue.

L’entreprise EP souhaite mettre en place une solution permettant d’optimiser et de fiabiliser l’affectation
des salariés sur les chantiers. L’objectif est d’éliminer les erreurs de planification tout en facilitant le travail
de l’administration et en garantissant une meilleure gestion des ressources humaines sur le terrain.
Nous devons donc proposer une solution adaptée aux besoins spécifiques de l’entreprise, en prenant en
compte ses contraintes organisationnelles et opérationnelles.

