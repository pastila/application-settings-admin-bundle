FROM node:12.18.3

RUN apt-get update && apt-get install libglu1 && npm i -g gulp@4.0.2

#RUN npm i -g npm@6.10.3 eslint@6.8.0
#RUN npm i -g --unsafe-perm tars-cli@1.12.2

WORKDIR /var/www

CMD /bin/bash -c "tail -f /dev/null"
