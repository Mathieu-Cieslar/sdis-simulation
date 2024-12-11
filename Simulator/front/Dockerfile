# Étape 1 : Utiliser une image Node officielle
FROM node:16

# Étape 2 : Définir le répertoire de travail
WORKDIR /app

# Étape 3 : Copier les fichiers package.json et package-lock.json
COPY package*.json ./

# Étape 4 : Installer les dépendances
RUN npm install

# Étape 5 : Copier le reste des fichiers de l'application
COPY . .

# Étape 6 : Exposer le port 8080
EXPOSE 8082

# Étape 7 : Lancer le serveur de développement Vue.js
CMD ["npm", "run", "serve", "--", "--host", "0.0.0.0", "--port", "8082"]
