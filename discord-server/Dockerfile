# Dockerfile
FROM node:20-alpine

# Create app directory
WORKDIR /app

# Install app dependencies
COPY package*.json ./
RUN npm install

# Bundle app source
COPY . .

# Expose port (if needed for debugging; not required for Discord bots)
EXPOSE 3000

# Run the bot
CMD [ "node", "index.js" ]