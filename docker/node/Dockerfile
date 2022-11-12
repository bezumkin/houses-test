ARG NODE_VERSION=${NODE_VERSION}

FROM node:${NODE_VERSION}

CMD yarn && ([ -d public/build ] || yarn generate) && yarn dev