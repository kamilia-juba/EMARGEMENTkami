# Projet PRWB 2223 - Gestion de comptes entre amis

## Notes de version itération 1 

### Liste des utilisateurs et mots de passes

  * boverhaegen@epfc.eu, password "Password1,", utilisateur
  * bepenelle@epfc.eu, password "Password1,", utilisateur
  * xapigeolet@epfc.eu, password "Password1,", utilisateur
  * mamichel@epfc.eu, password "Password1,", utilisateur
  * amine@hotmail.com, password "Passwordtest1,", utilisateur
  * yacine@hotmail.com, password "Passwordtest1,", utilisateur
  * mustafa@hotmail.com, password "Passwordtest1,", utilisateur
  * 

### Liste des bugs connus

  * Empêche la sauvegarde malgré le fait qu'un utilisateur qui ai coché la checkboxe ou non a un poid de "0"
  * Vérification Backend de la date non faite ainsi que la vérification que ce n'est une date future
  * L'utilisateur connecté (hors créateur) peut se delete d'un tricount
  * Lorsqu'on add un tricount avec un nom très long (ex: 250 char) le string fais déborder la vue et cette dernière s'allonge et fais s'allonger pas page également

### Liste des fonctionnalités supplémentaires

  * Vérification en plus au niveau de l'IBAN lors du signup (on vérifie également si le pays existe)
### Divers

  * Bouton "log out" collé au bas de page
  * L'ajout en base de données se fait directement lorsqu'on appuie sur Add dans l'UC add participant 
  * L'utilisateur connecté (hors créateur) peut se delete d'un tricount
  * Il y a 3 types de fichiers SQL dans le dossiers database: prwb_2223_a07 (bdd vide), prwb_2223_a07_dump (bdd du cours de base en dump),  prwb_2223_a07_dump_test (bdd avec nos tests)

## Notes de version itération 2

...

## Notes de version itération 3 

...