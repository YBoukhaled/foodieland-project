FROM node:20-alpine
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
EXPOSE 5173
# The --host flag is critical for Docker
CMD ["npm", "run", "dev", "--", "--host"]