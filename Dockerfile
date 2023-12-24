# Some of the code is taken from https://github.com/vercel/next.js/blob/canary/examples/with-docker/Dockerfile

FROM node:20-alpine3.18 AS base

# Install all dependencies
FROM base as deps

COPY . /app
WORKDIR /app
RUN npm install && npm run build

ENV NODE_ENV="production"

EXPOSE 3000

CMD ["npm", "run", "start"]
