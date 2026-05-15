# installation de node


prendre la version LTS

https://nodejs.org/en/download

ne rien cocher sauf confid

## installation de sass

ouvrir un terminal

npm install -g sass

si message: npm : Impossible de charger le fichier C:\Program Files\nodejs\npm.ps1, car l’exécution de scripts est désactivée sur
ce système. Pour plus d’informations, consultez about_Execution_Policies à l’adresse

Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser

## démarrer sass pour scss

sass ./assets/style.scss ./build/style.css --style=compressed --watch

