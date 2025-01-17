# Dockerfile for Vite
FROM node:18-alpine

WORKDIR /var/www

COPY package*.json ./
RUN npm install

COPY . .

CMD ["npm", "run", "dev"]
